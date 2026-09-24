<?php

declare(strict_types=1);

namespace App\Modules\Comment\Enums;

enum RatingValue: int
{
    case ONE = 1;
    case TWO = 2;
    case THREE = 3;
    case FOUR = 4;
    case FIVE = 5;

    public function stars(): string
    {
        return str_repeat('*', $this->value) . str_repeat('-', 5 - $this->value);
    }

    public function percentage(): float
    {
        return ($this->value / 5) * 100;
    }
}