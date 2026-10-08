<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory, SoftDeletes;

    // table name (not the default "posts")
    protected $table = 'showcase_flowers';

    // fixed gallery for the showcase. item 7 reuses image 1, and so on
    public const GALLERY = [
        'images/bouquets/01.jpg',
        'images/bouquets/02.jpg',
        'images/bouquets/03.jpg',
        'images/bouquets/04.jpg',
        'images/bouquets/05.jpg',
        'images/bouquets/06.jpg',
    ];

    protected $fillable = [
        'title',
        'body',
        'price',
        'image',
        'user_id',
        'category_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // pick one of the 6 packed images by id (cycles when id > 6)
    public function galleryImageUrl(): string
    {
        $count = count(self::GALLERY);
        $index = ((int) $this->id - 1) % $count;
        if ($index < 0) {
            $index = 0;
        }

        return asset(self::GALLERY[$index]);
    }
}
