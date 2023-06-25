<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'open',
        'high',
        'low',
        'close',
        'volume',
        'ex_dividend',
        'split_ratio',
        'adj_open',
        'adj_high',
        'adj_low',
        'adj_close',
        'adj_volume'
    ];
}
