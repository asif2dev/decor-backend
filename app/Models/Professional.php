<?php

namespace App\Models;

use App\SearchEngines\ProfessionalNormalizer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;

class Professional extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'uid',
        'company_name',
        'about',
        'logo',
        'phone1',
        'phone2',
        'lat_lng',
        'full_address',
        'work_scope',
    ];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'professional_categories');
    }

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'professional_users');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProfessionalReview::class);
    }

    public function toSearchableArray(): array
    {
        return ProfessionalNormalizer::toSearchableArray($this);
    }

    public function getScoutKey(): string
    {
        return $this->uid;
    }

    public function getScoutKeyName(): string
    {
        return 'uid';
    }
}
