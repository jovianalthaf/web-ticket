<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'headline',
        'description',
        'start_time',
        'location',
        'duration',
        'is_popular',
        'photos',
        'type',
        'category_id',
    ];

    // photo = [jpeg,jpg,png]
    protected $casts = [
        'photos' => 'array',
        'start_time' => 'datetime',
    ];

    //RELATION TO TICKETS
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    // RELATION TO CATEGORY
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // GET THE LOWEST PRICE TICKER FOR THE EVENT
    public function getStartFromAttribute()
    {
        //NYARI DATA PRICE  PERTAMA KALO GADA DI ISI DENGAN 0 
        return $this->tickets()->orderBy('price')->first()->price ?? 0;
    }

    //GET THE FIRST PHOTO AS A THUMBNAIL FROM THE PHOTOS ATTRIBUTE. IF DOESNT EXIST RETURN DEFAULT IMAGE
    public function getThumbnailAttribute()
    {
        $photos = $this->photos;
        if ($photos && !empty($photos)) {
            return Storage::url($photos[0]);
        }

        return asset('assets/images/event-1.webp');
    }

    //scope a query to only include events with certain category
    public function scopeWithCategory($query, $category)
    {
        return $query->where('category_id', $category);
    }

    //scope a query to only upcoming events
    public function scopeUpcoming($query)
    {
        return $query->orderBy('start_time', 'asc')->where('start_time', '>=', now());
    }

    //scope a query to find event by slug
    public function scopeFetch($query, $slug)
    {
        return $query->with(['category', 'tickets'])
            ->withCount('tickets')
            ->where('slug', $slug)
            ->firstOrFail();
    }
}
