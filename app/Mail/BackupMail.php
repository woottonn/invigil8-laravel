<?php

namespace App\Mail;

use App\Season;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BackupMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        return $this->view('emails.BackupMail')
            ->attach(storage_path("app/backup/backup-" . Carbon::now()->format('Y-m-d') . ".gz"), [
                'as' => 'backup-' . Carbon::now()->format("Y-m-d") . '.gz',
                'mime' => 'application/gzip',
            ]);
    }
}
