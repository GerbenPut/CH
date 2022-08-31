<?php

namespace App\Support\Commands;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Illuminate\Contracts\Container\Container;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

abstract class Command
{
    private array $args;
    private string $command;
    private Container $container;
    protected LINEBot $bot;
    private TextMessage $event;
    private ?string $group;
    protected string $name;

    public function __construct(
        Container $container,
        LINEBot $bot,
        TextMessage $event,
        array $args,
        ?string $group,
        string $command,
    ) {
        $this->args = $args;
        $this->bot = $bot;
        $this->command = $command;
        $this->container = $container;
        $this->event = $event;
        $this->group = $group;
    }

    final public function __invoke(): void
    {
        try {
            $this->container->call([$this, 'handle'], [
                'args' => $this->args,
                'event' => $this->event,
                'group' => $this->group,
                'command' => $this->command,
            ]);
        } catch (ModelNotFoundException $exception) {
            $model = Str::of($exception->getModel())
                ->classBasename()
                ->title();

            $this->reply($model . ' not found! See notes for options.');
        }
    }

    final protected function reply(MessageBuilder|string $messageBuilder): bool
    {
        if (is_string($messageBuilder)) {
            $messageBuilder = new TextMessageBuilder($messageBuilder);
        }

        return $this->bot
            ->replyMessage($this->event->getReplyToken(), $messageBuilder)
            ->isSucceeded();
    }

    final public function shouldRun(string $command): bool
    {
        return Str::is($this->name, $command);
    }
}
