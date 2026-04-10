<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Log;
use App\Http\Controllers\TweetController;
use Carbon\Carbon;
class ScheduledTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled:task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
       $tweet = new  TweetController();
		$res = $tweet->getUserbyusername();
      $currentTime = Carbon::now()->format('H:i'); // Format as "13:40"

        if ($currentTime === "10:00") {
            $res2 = $tweet->resetPostCountJob();
        }
	   //$res = $tweet->getTweetIdsByJob1();
       return 0;
    }
}
