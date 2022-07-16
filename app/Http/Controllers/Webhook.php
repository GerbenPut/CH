<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LINE\LINEBot;
use Illuminate\Contracts\Events\Dispatcher;

class Webhook extends Controller
{
    public function index(Request $request, LINEBot $bot, Dispatcher $dispatcher)
    {
        $events = $bot->parseEventRequest(
            $request->getContent(),
            $request->header('X-Line-Signature'),
        );

        array_map([$dispatcher, 'dispatch'], $events);

        return response('OK', 200);
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
