<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Subject extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'code',
        'active',
        'user_id',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relaciones
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    // Relación con preguntas
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getQuizzesCountAttribute()
    {
        return $this->quizzes()->count();
    }

    public function getQuestionsCountAttribute()
    {
        return $this->questions()->count();
    }
}
