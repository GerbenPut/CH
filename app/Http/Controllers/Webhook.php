<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

            $message = "Bot Connected with message: " . $userMessage;
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
