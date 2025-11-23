<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'area_id',
        'genre_id',
        'summary',
        'image_path',
    ];

    protected $casts = [
        'owner_id' => 'integer',
        'area_id'  => 'integer',
        'genre_id' => 'integer',
    ];

    protected $appends = ['image_url'];

    protected $with = ['area:id,name', 'genre:id,name'];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    public function getImageUrlAttribute(): string
    {
        if (!empty($this->image_path)) {

            if (Str::startsWith($this->image_path, ['http://', 'https://'])) {
                return $this->image_path;
            }

            return asset('storage/' . ltrim($this->image_path, '/'));
        }
        return asset('images/noimage.jpg');
    }
}
