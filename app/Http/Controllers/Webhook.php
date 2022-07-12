<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Webhook extends Controller
{
    public function index() {
        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);

        $body 	   = file_get_contents('php://input');
	    $signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];
        $data = json_decode($body, true);

        foreach ($data['events'] as $event) {
            // $userMessage = $event['message']['text'];
            $userMessage = "The connection works";
            // $message = "Rogue: https://sourceartz.com/ronin/rogue\n\nRanger: https://sourceartz.com/ronin/ranger\n\nMage: https://sourceartz.com/ronin/mage\n\nDruid: https://sourceartz.com/ronin/druid\n\nWarrior: https://sourceartz.com/ronin/warrior\n\n";
            $message = $userMessage;
            $textMessageBuilder = new \LINE\LINEBot\MessageBuilder\TextMessageBuilder($message);
            $result = $bot->replyMessage($event['replyToken'], $textMessageBuilder);
            return $result->getHTTPStatus() . ' ' . $result->getRawBody();

        }

        // return view('Misfits/misfits', ['name' => env('LINE_BOT_CHANNEL_ACCESS_TOKEN')]);
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
