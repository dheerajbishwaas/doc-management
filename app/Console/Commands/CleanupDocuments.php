<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupDocuments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'documents:cleanup';

    protected $description = 'Delete rejected documents older than 30 days';

    public function handle()
    {
        $cutoffDate = now()->subDays(30);

        $documents = \App\Models\Document::where('status', 'rejected')
            ->where('updated_at', '<', $cutoffDate)
            ->get();

        $count = 0;

        foreach ($documents as $document) {
            if (\Illuminate\Support\Facades\Storage::exists($document->filename)) {
                \Illuminate\Support\Facades\Storage::delete($document->filename);
            }
            $document->delete();
            $count++;
        }

        $this->info("Deleted {$count} rejected documents older than 30 days.");
    }
}
