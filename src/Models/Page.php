<?php

namespace Z3d0X\FilamentFabricator\Models;

use Filament\Core\Models\Traits\ImageHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Z3d0X\FilamentFabricator\Models\Contracts\Page as Contract;
use Spatie\Translatable\HasTranslations;

class Page extends Model implements Contract, HasMedia
{
    use HasTranslations, InteractsWithMedia, ImageHelpers;

    public function __construct(array $attributes = [])
    {
        if (blank($this->table)) {
            $this->setTable(config('filament-fabricator.table_name', 'pages'));
        }

        parent::__construct($attributes);
    }

    protected array $translatable = ['title', 'seo_title', 'seo_description', 'og_title', 'og_description'];

    protected $fillable = [
        'title',
        'slug',
        'blocks',
        'layout',
        'parent_id',
        'seo_title',
        'seo_description',
        'og_title',
        'og_description',
        'noindex'
    ];

    protected $casts = [
        'blocks' => 'array',
        'parent_id' => 'integer',
    ];

    protected static function booted()
    {
        static::saved(fn () => Cache::forget('filament-fabricator::page-urls'));
        static::deleted(fn () => Cache::forget('filament-fabricator::page-urls'));
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(static::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id');
    }

    public function allChildren(): HasMany
    {
        return $this->hasMany(static::class, 'parent_id')
            ->select('id', 'slug', 'title', 'parent_id')
            ->with('allChildren:id,slug,title,parent_id');
    }

    public function registerMediaConversions(Media $media = null): void
    {
      $this->registerMediaConversionsFromImageHelpers($media);
    }
}
