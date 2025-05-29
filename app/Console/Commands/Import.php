<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\Option;
use App\Models\Quiz;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:import {file : Path to the JSON file or directory} {--quiz= : Name of the quiz to create (ignored when importing directory)} {--clean : Clean existing questions and options before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import quiz questions and answers from a JSON file or all JSON files in a directory';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $quizName = $this->option('quiz');
        $shouldClean = $this->option('clean');

        // Check if file/directory exists
        if (!File::exists($filePath)) {
            $this->error("File or directory not found: {$filePath}");
            return Command::FAILURE;
        }

        // Clean database if requested
        if ($shouldClean) {
            if ($this->confirm('This will delete all existing questions and options. Are you sure?')) {
                $this->cleanDatabase();
            } else {
                $this->info('Import cancelled.');
                return Command::SUCCESS;
            }
        }

        // Determine if it's a file or directory
        if (is_dir($filePath)) {
            return $this->importDirectory($filePath);
        } else {
            return $this->importFile($filePath, $quizName);
        }
    }

    /**
     * Import all JSON files from a directory
     */
    private function importDirectory(string $directoryPath): int
    {
        $jsonFiles = File::glob($directoryPath . '/*.json');
        
        if (empty($jsonFiles)) {
            $this->error("No JSON files found in directory: {$directoryPath}");
            return Command::FAILURE;
        }

        $this->info("Found " . count($jsonFiles) . " JSON files to import:");
        
        $totalImported = 0;
        $totalQuestions = 0;
        $totalAnswers = 0;

        foreach ($jsonFiles as $jsonFile) {
            $filename = pathinfo($jsonFile, PATHINFO_FILENAME);
            $quizName = ucwords(str_replace(['-', '_'], ' ', $filename));
            
            $this->newLine();
            $this->info("Importing: {$filename}.json as quiz '{$quizName}'");
            
            $result = $this->importFile($jsonFile, $quizName);
            
            if ($result === Command::SUCCESS) {
                $totalImported++;
            }
        }

        $this->newLine(2);
        $this->info("Directory import completed!");
        $this->info("Successfully imported {$totalImported} out of " . count($jsonFiles) . " files.");
        
        return Command::SUCCESS;
    }

    /**
     * Import a single JSON file
     */
    private function importFile(string $filePath, ?string $quizName = null): int
    {
        // Read and decode JSON file
        try {
            $jsonContent = File::get($filePath);
            $quizData = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON file: ' . json_last_error_msg() . " in {$filePath}");
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Error reading file: ' . $e->getMessage() . " in {$filePath}");
            return Command::FAILURE;
        }

        // Create quiz if name is provided
        $quiz = null;
        if ($quizName) {
            // Check if quiz already exists
            $existingQuiz = Quiz::where('title', $quizName)->first();
            if ($existingQuiz) {
                if ($this->confirm("Quiz '{$quizName}' already exists. Do you want to add questions to it?")) {
                    $quiz = $existingQuiz;
                    $this->info("Adding questions to existing quiz: {$quizName}");
                } else {
                    $this->info('Import cancelled for this file.');
                    return Command::SUCCESS;
                }
            } else {
                $quiz = Quiz::create([
                    'title' => $quizName,
                    'slug' => Str::slug($quizName),
                    'description' => "Imported quiz from {$filePath}",
                    'time_limit' => 60, // Default 60 minutes
                    'is_active' => true
                ]);
                $this->info("Created new quiz: {$quizName}");
            }
        }

        $this->info('Starting quiz import...');
        $progressBar = $this->output->createProgressBar(count($quizData));
        $progressBar->start();

        $importedQuestions = 0;
        $importedAnswers = 0;

        foreach ($quizData as $questionData) {
            try {
                // Create question
                $question = Question::create([
                    'text' => $questionData['question']
                ]);

                // Attach question to quiz if quiz was created/selected
                if ($quiz) {
                    $quiz->questions()->attach($question->id);
                }

                // Create answers for this question
                foreach ($questionData['answers'] as $answerData) {
                    Option::create([
                        'question_id' => $question->id,
                        'text' => $answerData['answer'],
                        'correct' => $answerData['correct']
                    ]);
                    $importedAnswers++;
                }

                $importedQuestions++;
                $progressBar->advance();

            } catch (\Exception $e) {
                $this->error("\nError importing question: " . $e->getMessage());
                continue;
            }
        }

        $progressBar->finish();
        $this->newLine(2);
        $this->info("Import completed for {$filePath}!");
        $this->info("Imported {$importedQuestions} questions and {$importedAnswers} answers.");
        
        if ($quiz) {
            $this->info("All questions have been added to quiz: {$quiz->title}");
            $this->info("Quiz ID: {$quiz->id}");
        }

        return Command::SUCCESS;
    }

    /**
     * Clean the database by deleting all questions and options
     */
    private function cleanDatabase(): void
    {
        $this->info('Cleaning database...');
        
        $deletedOptions = Option::count();
        $deletedQuestions = Question::count();
        $deletedQuizzes = Quiz::count();
        
        // Delete in the correct order to avoid foreign key constraint issues
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        \DB::table('answers')->truncate();
        \DB::table('options')->truncate();
        \DB::table('questions')->truncate();
        \DB::table('quizzes')->truncate();
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->info("Deleted {$deletedQuizzes} quizzes, {$deletedQuestions} questions and {$deletedOptions} options.");
    }
}
