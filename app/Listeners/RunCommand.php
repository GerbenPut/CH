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

        $group = $this->getGroup($groupId);
        $commands = $this->getCommands($group);

        if (count($commands) === 0) {
            return;
        }

        $words = preg_split('/\s+/', $event->getText(), flags: PREG_SPLIT_NO_EMPTY);

        $command = collect($commands)
            ->map(function (string $class) use ($event, $group, $words) {
                return $this->container->make($class, [
                    'args' => array_slice($words, 1),
                    'command' => $words[0],
                    'event' => $event,
                    'group' => $group,
                ]);
            })
            ->first(fn (Command $command) => $command->shouldRun(mb_strtolower($words[0])));

        if ($command === null) {
            return;
        }

        $this->container->call($command);
    }

    /**
     * @param string|null $group
     * @return array<int, class-string<\App\Support\Commands\Command>>
     */
    private function getCommands(?string $group): array
    {
        if ($group === null) {
            return config('line.commands._', []);
        }

        return array_merge(
            config('line.commands._', []),
            config('line.commands.' . $group, []),
        );
    }

    private function getGroup(string $groupId): ?string
    {
        static $groupIds;
        $groupIds ??= config('line.group_ids');

        $name = array_search($groupId, $groupIds);

        return $name === false ? null : $name;

    }
}
