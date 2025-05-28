<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Question;
use App\Models\Option;

class Import extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'quiz:import {file : Path to the JSON file} {--clean : Clean existing questions and options before import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import quiz questions and answers from a JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');
        $shouldClean = $this->option('clean');

        // Check if file exists
        if (!File::exists($filePath)) {
            $this->error("File not found: {$filePath}");
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

        // Read and decode JSON file
        try {
            $jsonContent = File::get($filePath);
            $quizData = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->error('Invalid JSON file: ' . json_last_error_msg());
                return Command::FAILURE;
            }
        } catch (\Exception $e) {
            $this->error('Error reading file: ' . $e->getMessage());
            return Command::FAILURE;
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
        $this->info("Import completed!");
        $this->info("Imported {$importedQuestions} questions and {$importedAnswers} answers.");

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
        
        // Delete in the correct order to avoid foreign key constraint issues
        // First delete answers, then options, then questions
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        \DB::table('answers')->truncate();
        \DB::table('options')->truncate();
        \DB::table('questions')->truncate();
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        $this->info("Deleted {$deletedQuestions} questions and {$deletedOptions} options.");
    }
}
