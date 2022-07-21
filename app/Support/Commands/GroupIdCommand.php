<?php

namespace App\Support\Commands;

use LINE\LINEBot\Event\MessageEvent\TextMessage;

class GroupIdCommand extends Command
{
    protected string $name = 'group-id';

    public function handle(TextMessage $event): void
    {
        $this->reply($event->getGroupId() ?: '-');
    }
}
