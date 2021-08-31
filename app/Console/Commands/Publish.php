<?php

namespace App\Console\Commands;

use App\Models\Publishing;
use App\Models\Subscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class Publish extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publish:message';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish Queued Messages';

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
     * @return int
     */
    public function handle()
    {
        //Get Queued Messages
        $messages = Publishing::with('topic')->where('status', 'pending')->where('deleted_at', NULL)->get();
        foreach($messages as $message)
        {
            echo "Sending Messages From Topic: {$message->topic->name} Started\n";
            $subscribers = Subscription::where('topic_id', $message->topic_id)->where('deleted_at', NULL)->get();
            foreach($subscribers  as $subscriber)
            {
                try 
                {
                    //Send To Endpoint
                    Http::post($subscriber->url, [
                        'topic' => $message->topic->name,
                        'data' => json_decode($message->payload),
                    ]);

                    echo "Message Sent to $subscriber->url\n";
                } 
                catch (Exception $e) 
                {
                    echo "Message Not Sent to $subscriber->url\n";
                }
            }

            //Update Message Status
            $message->status = 'completed';
            $message->updated_at = Carbon::now();
            $message->update();

            echo "Sending Messages From Topic: {$message->topic->name} Completed\n\n";
        }

        return 0;
    }
}
