<?php

declare(strict_types=1);

namespace App\Modules\Article\Enums;

enum ArticleStatus: string
{
    case DRAFT = 'draft';
    case PENDING_REVIEW = 'pending_review';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case DELETED = 'deleted';

    public function label(): string
    {
        return match($this) {
            self::DRAFT => 'Draft',
            self::PENDING_REVIEW => 'Pending Review',
            self::PUBLISHED => 'Published',
            self::ARCHIVED => 'Archived',
            self::DELETED => 'Deleted',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::DRAFT => 'gray',
            self::PENDING_REVIEW => 'yellow',
            self::PUBLISHED => 'green',
            self::ARCHIVED => 'blue',
            self::DELETED => 'red',
        };
    }

    public function isPublished(): bool { return $this === self::PUBLISHED; }
    public function isDraft(): bool { return $this === self::DRAFT; }
}
