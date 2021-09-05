<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Professional extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'company_name',
        'about',
        'category_id',
        'phone1',
        'lat_lng',
        'full_address'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
