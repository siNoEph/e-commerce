<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model 
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 
        'parent_id'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        
    ];

    public static function boot()
    {
        parent::boot();

        Categories::creating(function (Categories $categories) {
            $categories->generateSlug();
        });
    }

    public function scopeFindBySlug($query, $slug)
    {
        return $query->where('slug', $slug)->first();
    }

    public function generateSlug()
    {
        $this->slug = $this->createSlug($this->name);

        return $this;
    }

    public function createSlug($title, $numb = 0)
    {
        $slug       = str_slug($title, '-') . ($numb ? '-' . $numb : '');
        $already    = Categories::findBySlug($slug);

        if ($already->count())
            return $this->createSlug($title, $numb+1);
            return $slug;
    }
}