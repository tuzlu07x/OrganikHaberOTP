<?php

namespace App\Console\Commands;

use App\Integration\Otp;
use Illuminate\Console\Command;

class OTPCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'otp:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $otp = new Otp('https://api.organikhaberlesme.com', env('OTP_KEY'));
        //$response = $otp->send('905074417663');
        //dd($response);
        $verification = $otp->verify('4019149202', '898957');
        dd($verification);

        return Command::SUCCESS;
    }
}
