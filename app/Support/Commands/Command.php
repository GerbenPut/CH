<?php

namespace App\Support\Commands;

use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use Illuminate\Contracts\Container\Container;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use Illuminate\Console\Parser;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

abstract class Command
{
    private array $args;
    private Container $container;
    protected LINEBot $bot;
    private TextMessage $event;
    protected string $name;

    public function __construct(Container $container, LINEBot $bot, TextMessage $event, array $args)
    {
        $this->args = $args;
        $this->bot = $bot;
        $this->container = $container;
        $this->event = $event;
    }

    final public function __invoke(): void
    {
        try {
            $this->container->call([$this, 'handle'], [
                'args' => $this->args,
                'event' => $this->event,
                'command' => Parser::parse($this->event->getText()),
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
