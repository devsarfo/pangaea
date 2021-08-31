<?php

namespace App\Http\Controllers;

use App\Models\Publishing;
use App\Models\Subscription;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PublishingController extends Controller
{
    /**
     * Subscribe Enpoint.
     * @param Request $request
     * @param String $topic
     * 
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request, String $topic)
    {
        if(!$request->toArray())
        {
            return response()->json([
                'error' => true,
                'message' => "Expected body must be a javascript object {},"
            ], 400);
        }
        
        //Get Topic and Subscribers
        $tp = Topic::where('name', $topic)->where('deleted_at', NULL)->first();
        if(!$tp)
        {
            return response()->json([
                'error' => true,
                'message' => "Topic: $topic cannot be found or has no subscribers"
            ], 400);
        }
        
        //Queue Message 
        Publishing::create([
            'topic_id' => $tp->id, 
            'payload' => json_encode($request->all()),
            'status' => 'pending',
            'created_at' => Carbon::now()
        ]);
        
        //Publish Message
        $subscribers = Subscription::where('topic_id', $tp->id)->where('deleted_at', NULL)->count();
        return response()->json([
            'success' => true,
            'message' => "Message sent to $subscribers " . ($subscribers == 1 ? "subscriber" : "subscribers")
        ], 200);
    }
}
