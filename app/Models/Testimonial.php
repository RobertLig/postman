<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Testimonial extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'role',
        'avatar',
        'rating',
        'content',
        'is_featured',
        'is_active',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function initials(): string
    {
        return collect(explode(' ', $this->name))
            ->map(fn($part) => strtoupper(substr($part, 0, 1)))
            ->join('');
    }
}
