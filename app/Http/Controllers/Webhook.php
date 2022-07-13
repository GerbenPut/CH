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

            if ($event['source']['groupId'] == "C715a78987e46d1ffde053bda1a4de65c") {
                // Timers
                if ($pieces[0] == "timers") {
                    if ($pieces[1] == "raid") {
                        $message = "You are not allowed to those bosses yet. *insert evil smiley*";
                    } else {
                        $lines = BossTimer::all()->where('type', $pieces[1])
                        ->map(fn (BossTimer $timer) => sprintf('%s opens in %s and closes in %s', $timer->name, $timer->date->addMinutes($timer->open)->diffForHumans(),$timer->date->addMinutes($timer->closed)->diffForHumans()));
                        
                        if ($lines->isEmpty()) {
                            $message = "Invalid timer type.";
                        } else {
                            $newmessage = array();
                            foreach ($lines as $msg) {
                                $pieces = explode(' ', $msg);
                                if ($pieces[5] == "ago" && $pieces[11] == "ago") {
                                    array_push($newmessage, $pieces[0] . " | opens: unknown - closes: unknown");
                                } else if ($pieces[5] == "ago") {
                                    array_push($newmessage, $pieces[0] . " | opens: unknown - closes: " . $pieces[9] . " " . $pieces[10]);
                                } else {
                                    array_push($newmessage, $pieces[0] . " | opens: " . $pieces[3] . " " . $pieces[4] . " - closes: " . $pieces[10] . " " . $pieces[11]);
                                }
                            }

                            $message = join("\n",$newmessage);
                            // $message = $newmessage->join("\n");
                        }
                    }
                } else if ($pieces[0] == "reset") {
                    $raids = array("necromancer", "proteus", "gelebron", "dhiothu", "bloodthorn", "hrungnir", "mordris");
                    if (in_array($pieces[1], $raids)) {
                        $message = "You are not allowed to those bosses yet. *insert evil smiley*";
                    } else {
                        $timer = BossTimer::query()->firstWhere('name', $pieces[1]);
        
                        if ($timer === null) {
                            $message = "Boss not found!";
                        } else {
                            $timer->date = now();
                            $timer->save();
                            $message = $pieces[1] . " has been reset!";
                        }
                    }
                } else {
                    $message = "Command not found.";
                }
            } else if ($event['source']['groupId'] == "C89dae52ca0c01f5c46dd825c2a4eed2d") {
                // Raid Timers
                if ($pieces[0] == "timers") {
                    $lines = BossTimer::all()->where('type', $pieces[1])
                        ->map(fn (BossTimer $timer) => sprintf('%s opens in %s and closes in %s', $timer->name, $timer->date->addMinutes($timer->open)->diffForHumans(),$timer->date->addMinutes($timer->closed)->diffForHumans()));
                        
                    if ($lines->isEmpty()) {
                        $message = "Invalid timer type.";
                    } else {
                        $newmessage = array();
                        $newp = $pieces;
                        foreach ($lines as $msg) {
                            if (count($newp) == 2) {
                                $pieces = explode(' ', $msg);
                                if ($pieces[5] == "ago" && $pieces[11] == "ago") {
                                    array_push($newmessage, $pieces[0] . " | opens: unknown - closes: unknown");
                                } else if ($pieces[5] == "ago") {
                                    array_push($newmessage, $pieces[0] . " | opens: unknown - closes: " . $pieces[9] . " " . $pieces[10]);
                                } else {
                                    array_push($newmessage, $pieces[0] . " | opens: " . $pieces[3] . " " . $pieces[4] . " - closes: " . $pieces[10] . " " . $pieces[11]);
                                }
                            } else if (is_numeric($newp[2])) {
                                $pieces = explode(' ', $msg);
                                if ($pieces[5] == "ago" && is_numeric((int)$pieces[9]) && $pieces[11] == "from") {
                                    array_push($newmessage, $pieces[0] . " | opens: unknown - closes: " . $pieces[9] . " " . $pieces[10]);
                                } else if (is_numeric((int)$pieces[3])) {
                                    array_push($newmessage, "2");
                                    if ((int)$pieces[3] <= (int)$newp[2] && $pieces[4] == "minutes"){
                                        array_push($newmessage, $pieces[0] . " | opens: " . $pieces[3] . " " . $pieces[4] . " - closes: " . $pieces[10] . " " . $pieces[11]);
                                    }
                                } 
                            } else {
                                array_push($newmessage, "Invalid command.");
                            }

                        }

                        $message = join("\n",$newmessage);
                    }
                } else if ($pieces[0] == "reset") {
                    $timer = BossTimer::query()->firstWhere('name', $pieces[1]);
        
                    if ($timer === null) {
                        $message = "Boss not found!";
                    } else {
                        $timer->date = now();
                        $timer->save();
                        $message = $pieces[1] . " has been reset!";
                    }
                } else if ($pieces[0] == "adjust") {
                    $timer = BossTimer::query()->firstWhere('name', $pieces[1]);
        
                    if ($timer === null) {
                        $message = "Boss not found!";
                    } else {
                        if ($pieces[2] == "add") {
                            $timer->date = $timer->date->addMinutes($pieces[3]);
                            $message = $pieces[1] . " has been adjusted!";
                        } else if ($pieces[2] == "sub"){
                            $timer->date = $timer->date->subMinutes($pieces[3]);
                            $message = $pieces[1] . " has been adjusted!";
                        } else {
                            $message = "Wrong input. 'Adjust bossname sub/add minutes'";
                        }
                        
                        $timer->save();
                        
                    }
                } else {
                    $message = "Command not found.";
                }
            } else if ($event['source']['groupId'] == "C9c94873e053e9a41bc9e55c1e9c54654") {
                // Admin
                $message = admin($pieces);
            } else if ($event['source']['groupId'] == "C0625b08c5924477dc699c869888b8fc5") {
                // Commands
                $message = commands($pieces);
            } else if ($event['source']['groupId'] == "C98fd96ccda635152017fc278acdf23ba") {
                // Attends
                $message = attends($pieces);
            } else {
                return;
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
