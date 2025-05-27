<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'html_content',
    ];

    protected $casts = [
        'html_content' => 'string',
    ];

    public function sections(): HasMany
    {
        return $this->hasMany(PageSection::class);
    }
}
