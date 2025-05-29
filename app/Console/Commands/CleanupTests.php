<?php

namespace App\Console\Commands;

use App\Models\Test;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CleanupTests extends Command
{
    protected $signature = 'quiz:cleanup-tests {--days=90 : Number of days to keep tests}';
    protected $description = 'Delete old test records';

    public function handle()
    {
        $days = $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);
        
        $deletedCount = Test::where('created_at', '<', $cutoffDate)->count();
        
        if ($deletedCount > 0) {
            Test::where('created_at', '<', $cutoffDate)->delete();
            $this->info("Deleted {$deletedCount} tests older than {$days} days.");
        } else {
            $this->info("No tests found older than {$days} days.");
        }
    }
}