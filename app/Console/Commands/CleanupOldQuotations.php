<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\DownloadedQuotation;
use Carbon\Carbon;

class CleanupOldQuotations extends Command
{
    protected $signature = 'quotations:cleanup';
    protected $description = 'Delete quotation PDFs older than 30 days';

    public function handle()
    {
        $cutoff = Carbon::now()->subDays(30);

        // Fetch records older than 30 days
        $oldDownloads = DownloadedQuotation::where('downloaded_at', '<', $cutoff)->get();

        foreach ($oldDownloads as $download) {

            if (Storage::exists($download->file_path)) {
                Storage::delete($download->file_path);
            }

            // Delete the DB record too, it's useless now
            $download->delete();
        }

        $this->info('Deleted old quotation PDFs.');
    }
}