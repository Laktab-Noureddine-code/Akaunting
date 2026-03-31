<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
class StorageTempClear extends Command
{
    protected $signature = 'storage-temp:clear';
    protected $description = 'Clear all storage/app/temp files';
    public function handle()
    {
        $filesystem = app(Filesystem::class);
        $path = get_storage_path('app/temp');
        foreach ($filesystem->glob("{$path}/*") as $file) {
            $filesystem->delete($file);
        }
        $this->info('Temporary files cleared!');
    }
}
