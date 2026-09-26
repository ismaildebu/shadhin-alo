<?php

declare(strict_types=1);

namespace App\Modules\Article\Models;

use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    protected $table = 'market_prices';

    protected $fillable = [
        'product_name',
        'price',
        'unit',
        'change_percent',
        'price_date',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'change_percent' => 'decimal:2',
        'price_date' => 'date',
    ];

    public function scopeLatestDate($query)
    {
        return $query->where('price_date', function ($sub) {
            $sub->selectRaw('MAX(price_date)')->from('market_prices');
        });
    }

    public function isIncreasing(): bool
    {
        return $this->change_percent > 0;
    }

    public function isDecreasing(): bool
    {
        return $this->change_percent < 0;
    }
}