<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'path',
        'title',
        'description',
        'palette',
        'space_id',
        'design_type_id',
        'professional_id'
    ];

    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class);
    }

    public function professional(): BelongsTo
    {
        return $this->belongsTo(Professional::class);
    }

    public function designType(): BelongsTo
    {
        return $this->belongsTo(DesignType::class);
    }
}
