<?php

namespace App\Support\Commands;

use App\Enums\ClassType;
use App\Models\Player;

class AddPlayerCommand extends Command
{
    protected string $name = 'addplayer';

    public function handle(array $args): void
    {
        if (count($args) < 2) {
            $this->reply('Please specify a name and class type respectively.');

            return;
        }

        $name = array_shift($args);
        $classTypeRaw = array_shift($args);
        $classType = ClassType::tryFrom($classTypeRaw);

        if ($name === 'attends') {
            $this->reply('Invalid name, this is a reserved keyword.');

            return;
        }

        if ($classType === null) {
            $this->reply('Unknown class type "' . $classTypeRaw . '".');

            return;
        }

        if (Player::query()->where('name', $name)->exists()) {
            $this->reply('That player already exists!');

            return;
        }

        Player::query()->create([
            'class_type' => $classType,
            'name' => $name,
        ]);

        $this->reply('Player created!');
    }
}
