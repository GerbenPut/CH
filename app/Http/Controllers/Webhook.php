<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BossTimer;

class Webhook extends Controller
{
    public function index(Request $request) {
        // Setup Bot
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);

        //Validate Signature
        $channelSecret = env('LINE_BOT_CHANNEL_SECRET');
        $httpRequestBody = $request->getContent();
        $hash = hash_hmac('sha256', $httpRequestBody, $channelSecret, true);
        $signature = base64_encode($hash);

        //Return Message
        $data = json_decode($httpRequestBody, true);

        foreach ($data['events'] as $event)
	    {
            // Setup message for camping and attends
            $userMessage = $event['message']['text'];
            $userMessage = strtolower($userMessage);
            $userMessage = preg_replace('#\s+#', ' ', $userMessage);
            $userMessage=rtrim($userMessage);
            $pieces = explode(' ', $userMessage);

            if ($pieces[0] == "reset") {
                $timer = BossTimer::query()->firstWhere('name', $pieces[1]);

                if ($timer === null) {
                    $timer = new BossTimer();
                    $timer->name = $pieces[1];
                    $timer->type = 'raid';
                    $timer->open = 0; // standaard waarde
                    $timer->closed = 0; // 10 min later? (miss beter in seconden opslaan?)
                }

                $timer->date = now();
                $timer->save();
                
                $message = $pieces[1] . " has been reset!";
            }
            
            if ($pieces[0] == "change") {
                $timer = BossTimer::query()->firstWhere('name', $pieces[1]);
            
                if ($timer === null) {
                    // hier berichtje sturen
                    return;
                }
            
                $timer->open = $pieces[2];
                $timer->closed = $pieces[3];
                $timer->save();
                            
                $message = $pieces[1] . " its respawn times has been modified!";
            }

            if ($pieces[0] == "timers") {
                $lines = BossTimer::all()
                ->map(fn (BossTimer $timer) => sprintf('%s opens in %s and closes in %s', $timer->name, $timer->date->addMinutes($timer->open)->diffForHumans(),$timer->date->addMinutes($timer->closed)->diffForHumans()));
                
                $message = $lines->isEmpty()
                    ? 'No timers.'
                    : $lines->join("\n");
            }

            if ($pieces[0] == "timer") {
                $lines = BossTimer::query()->where('type', $pieces[1])
                ->map(fn (BossTimer $timer) => sprintf('%s opens in %s and closes in %s', $timer->name, $timer->date->addMinutes($timer->open)->diffForHumans(),$timer->date->addMinutes($timer->closed)->diffForHumans()));
                
                $message = $lines->isEmpty()
                    ? 'No timers.'
                    : $lines->join("\n");
            }

            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
            $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
            return $result->getHTTPStatus() . ' ' . $result->getRawBody();
        }
    }

    public function Login()
    {
        return view('home');
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'password' => 'required',
        ]);
        return redirect()->back()->with('password', implode(",", $request->only('password')));
    }
}
