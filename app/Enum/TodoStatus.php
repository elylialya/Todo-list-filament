<?php

namespace App\Enum;

class TodoStatus
{
    const TODO = 'todo';
    const IN_PROGRESS = 'in-progress';
    const DONE = 'done';

    public static function getLabels(): array
    {
        return [
            self::TODO => 'To Do',
            self::IN_PROGRESS => 'In Progress',
            self::DONE => 'Done',
        ];
    }

    public static function getColorByStatus(string $status): string
    {
        switch ($status) {
            case self::TODO:
                return 'primary';
            case self::IN_PROGRESS:
                return 'warning';
            case self::DONE:
                return 'success';
            default:
                return 'secondary';
        }
    }
}
