<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \LINE\LINEBot\SignatureValidator as SignatureValidator;
// use Psr\Http\Message\ResponseInterface as Response;
// use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
require '../vendor/autoload.php';

class Webhook extends Controller
{
    public function index() {
        // get request body and line signature header
        $body 	   = file_get_contents('php://input');
        $signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

        // log body and signature
        file_put_contents('php://stderr', 'Body: '.$body);

        // is LINE_SIGNATURE exists in request header?
        if (empty($signature)){
            return $response->withStatus(400, 'Signature not set');
        }

        // is this request comes from LINE?
        if($_ENV['PASS_SIGNATURE'] == false && ! SignatureValidator::validateSignature($body, '285fbf204bce13bc0c60c5c4bf28c8d2', $signature)){
            return $response->withStatus(400, 'Invalid signature');
        }

        $httpClient = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(env('LINE_BOT_CHANNEL_ACCESS_TOKEN'));
        $bot = new \LINE\LINEBot($httpClient, ['channelSecret' => env('LINE_BOT_CHANNEL_SECRET')]);
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
