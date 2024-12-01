<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CleanTemporaryFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-temporary-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean temporary files older than 1 hour';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = DB::table('temporary_files')
            ->where('created_at', '<', now()->subHour())
            ->get();

        foreach ($files as $file) {
            Storage::delete('products/tmp/' . $file->file_name);
            DB::table('temporary_files')->where('id', $file->id)->delete();
        }

        $this->info('Temporary files cleaned successfully.');
    }
}
