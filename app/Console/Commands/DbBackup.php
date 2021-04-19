<?php
namespace App\Console\Commands;
use App\Mail\BackupMail;
use App\Season;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class DbBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Database Backup';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        $filename = "backup-" . Carbon::now()->format('Y-m-d') . ".gz";
        $command = "mysqldump --user=" . env('DB_USERNAME') ." --password=" . env('DB_PASSWORD') . " --host=" . env('DB_HOST') . " " . env('DB_DATABASE') . "  | gzip > " . storage_path() . "/app/backup/" . $filename;
        $returnVar = NULL;
        $output  = NULL;

        exec($command, $output, $returnVar);

        //email me
        $myEmail = 'n.wootton@thestreetlyacademy.co.uk';
        Mail::to($myEmail)->send(new BackupMail());


        //delete old files
        collect(Storage::disk('app')->listContents('backup', true))
            ->each(function($file) {
                if ($file['type'] == 'file' && $file['timestamp'] < now()->subDays(15)->getTimestamp()) {
                    Storage::disk('app')->delete($file['path']);
                }
            });




    }
}
