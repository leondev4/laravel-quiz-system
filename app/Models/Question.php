<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'text',
        'code_snippet',
        'answer_explanation',
        'more_info_link',
        'duration',
        'user_id',
        'subject_id', // Agregar este campo
    ];

    public function options(): HasMany
    {
        return $this->hasMany(Option::class)->inRandomOrder();
    }

    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class);
    }
    
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relación con Subject
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}
