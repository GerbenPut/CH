<?php

namespace App\Listeners;

use App\Support\Commands\Command;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Illuminate\Contracts\Container\Container;

class RunCommand
{
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
     * @throws \LINE\LINEBot\Exception\InvalidEventSourceException
     */
    public function handle(TextMessage $event): void
    {
        $groupId = $event->getGroupId();

        if ($groupId === null) {
            return;
        }

        $commands = $this->getCommands($groupId);

        if (count($commands) === 0) {
            return;
        }

        $words = preg_split('/\s+/', $event->getText(), flags: PREG_SPLIT_NO_EMPTY);

        $command = collect($commands)
            ->map(function (string $class) use ($event, $words) {
                return $this->container->make($class, [
                    'event' => $event,
                    'args' => array_slice($words, 1),
                ]);
            })
            ->first(fn (Command $command) => $command->shouldRun($words[0]));

        if ($command === null) {
            return;
        }

        $this->container->call($command);
    }

    /**
     * @param string $groupId
     * @return array<int, class-string<\App\Support\Commands\Command>>
     */
    private function getCommands(string $groupId): array
    {
        static $groupIds;
        $groupIds ??= config('line.group_ids');

        $name = array_search($groupId, $groupIds);

        if ($name === false) {
            return config('line.commands._', []);
        }

        return array_merge(
            config('line.commands._', []),
            config('line.commands.' . $name, []),
        );
    }
}
