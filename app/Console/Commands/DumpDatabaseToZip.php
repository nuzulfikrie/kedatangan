<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

/**
 * This command dumps the database to a zip file with option to upload to S3.
 */ 
class DumpDatabaseToZip extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:dump-to-zip {--s3} {--s3-bucket=} {--s3-region=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump database to a zip file with option to upload to S3';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "database_dump_{$timestamp}";
        $sqlFile = storage_path("app/{$filename}.sql");
        $zipFile = storage_path("app/{$filename}.zip");

        // Create progress bar
        $progress = $this->output->createProgressBar(4);
        $progress->start();

        // Step 1: Dump database to SQL file
        $this->info("\nDumping database to SQL file...");
        $dumpCommand = sprintf(
            'mysqldump -u%s -p%s %s > %s',
            config('database.connections.mysql.username'),
            config('database.connections.mysql.password'),
            config('database.connections.mysql.database'),
            $sqlFile
        );
        exec($dumpCommand);
        $progress->advance();

        // Step 2: Create ZIP file
        $this->info("\nCreating ZIP file...");
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $zip->addFile($sqlFile, basename($sqlFile));
            $zip->close();
        }
        $progress->advance();

        // Step 3: Upload to S3 if requested
        if ($this->option('s3')) {
            $this->info("\nUploading to S3...");

            // Configure S3
            $bucket = $this->option('s3-bucket') ?: config('filesystems.disks.s3.bucket');
            $region = $this->option('s3-region') ?: config('filesystems.disks.s3.region');

            config(['filesystems.disks.s3.bucket' => $bucket]);
            config(['filesystems.disks.s3.region' => $region]);

            // Upload file
            Storage::disk('s3')->put(
                "database-backups/{$filename}.zip",
                file_get_contents($zipFile)
            );

            $this->info("\nFile uploaded to S3 bucket: {$bucket}");
        }
        $progress->advance();

        // Step 4: Cleanup temporary files
        $this->info("\nCleaning up temporary files...");
        unlink($sqlFile);
        if (!$this->option('s3')) {
            $this->info("\nZIP file saved at: {$zipFile}");
        } else {
            unlink($zipFile);
        }
        $progress->advance();

        $progress->finish();

        $this->newLine();
        $this->info("\nDatabase dump completed successfully!");
    }
}