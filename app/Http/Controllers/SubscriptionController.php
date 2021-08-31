<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    /**
     * Subscribe Enpoint.
     * @param Request $request
     * @param String $topic
     * 
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, String $topic)
    {
        $validator =  Validator::make($request->all(),[
            'url' => 'required|max:255'
        ]);


        if($validator->fails())
        {
            return response()->json([
                'error' => true,
                'message' => implode(" | ", $validator->errors()->all())
            ], 400);
        }
        else
        {
            //Get or Create Topic
            $tp = Topic::firstOrCreate(
                ['name' => $topic],
                ['created_at' => Carbon::now()]
            );

            //Get or Create Subscription
            $subscription = Subscription::firstOrCreate(
                ['topic_id' => $tp->id, 'url' => $request->url],
                ['created_at' => Carbon::now()]
            );

            return response()->json([
                'topic' => $tp->name,
                'url' => $subscription->url
            ], 200);
        }
    }
}
