<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:approve-email {email : L’adresse e-mail à approuver}')]
#[Description('Command description')]
class ApproveEmail extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}
