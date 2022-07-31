<?php

namespace App\Models;

use App\Modules\SearchEngine\ProjectImageNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Laravel\Scout\Searchable;

class ProjectImage extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'path',
        'title',
        'slug',
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

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function designType(): BelongsTo
    {
        return $this->belongsTo(DesignType::class);
    }

    public function toSearchableArray(): array
    {
        return ProjectImageNormalizer::toSearchableArray($this);
    }

    public function searchableAs(): string
    {
        return 'project_images_index';
    }
}
