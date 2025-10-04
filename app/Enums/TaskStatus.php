<?php

namespace App\Enums;

enum TaskStatus: int
{
    case CANCELED = 0;
    case PENDING = 1;
    case DONE = 2;
    case LOST = 3;

    public function label(): string
    {
        return match($this) {
            self::CANCELED => 'Отменена',
            self::PENDING => 'В ожидании',
            self::DONE => 'Выполнена', 
            self::LOST => 'Просрочена',
        };
    }

    public static function options(): array
    {
        return [
            self::PENDING->value => self::PENDING->label(),
            self::DONE->value => self::DONE->label(),
            self::CANCELED->value => self::CANCELED->label(),
            self::LOST->value => self::LOST->label(),
        ];
    }

    public function isCompleted(): bool
    {
        return $this === self::DONE;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isCanceled(): bool
    {
        return $this === self::CANCELED;
    }

    public function isLost(): bool
    {
        return $this === self::LOST;
    }
}
