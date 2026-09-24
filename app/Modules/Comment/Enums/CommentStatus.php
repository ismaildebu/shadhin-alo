<?php

declare(strict_types=1);

namespace App\Modules\Comment\Enums;

enum CommentStatus: string
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case SPAM = 'spam';
    case DELETED = 'deleted';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending Review',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::SPAM => 'Spam',
            self::DELETED => 'Deleted',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'yellow',
            self::APPROVED => 'green',
            self::REJECTED => 'orange',
            self::SPAM => 'red',
            self::DELETED => 'gray',
        };
    }

    public function isApproved(): bool
    {
        return $this === self::APPROVED;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
