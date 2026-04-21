<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Author extends Model
{
    protected $fillable = [
        'name',
        'surname',
        'birthdate',
    ];

    protected $casts = [
        'birthdate' => 'date',
    ];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class);
    }

    public function fullName(): string
    {
        return $this->name . ' ' . $this->surname;
    }
}