<?php

namespace App\Listeners;

use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Illuminate\Contracts\Container\Container;
use App\Support\Commands\DueCommand;
use App\Support\Commands\ResetCommand;
use App\Support\Commands\TimersCommand;
use App\Support\Commands\UnknownCommand;
use App\Support\Commands\ChangeCommand;
use App\Support\Commands\AdjustCommand;

class DetermineCommand
{
    private const COMMANDS = [
        'adjust' => AdjustCommand::class,
        'due' => DueCommand::class,
        'change' => ChangeCommand::class,
        'reset' => ResetCommand::class,
        'timers' => TimersCommand::class,
        'unknown' => UnknownCommand::class,
    ];

    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handle the event.
     *
     * @param \LINE\LINEBot\Event\MessageEvent\TextMessage $event
     * @return void
     */
    public function handle(TextMessage $event): void
    {
        $words = preg_split('/\s+/', $event->getText(), flags: PREG_SPLIT_NO_EMPTY);

        if (count($words) === 0 || !isset(self::COMMANDS[$words[0]])) {
            return;
        }

        $command = $this->container->make(self::COMMANDS[$words[0]], [
            'event' => $event,
            'args' => array_slice($words, 1),
        ]);

        $this->container->call($command);
    }
}
