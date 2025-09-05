<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Quiz extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'published',
        'public',
        'opens_at',
        'closes_at',
        'subject_id', // Ahora obligatorio
    ];

    protected $casts = [
        'published' => 'boolean',
        'public'    => 'boolean',
        'opens_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function scopePublic($q)
    {
        return $q->where('public', true);
    }

    public function scopePublished($q)
    {
        return $q->where('published', true);
    }
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    // Relación obligatoria con materia
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Scope para filtrar por materia
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    // Nuevos scopes para verificar disponibilidad
    public function scopeAvailable($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('opens_at')
              ->orWhere('opens_at', '<=', now());
        })->where(function ($q) {
            $q->whereNull('closes_at')
              ->orWhere('closes_at', '>=', now());
        });
    }

    // Métodos helper para verificar estado
    public function isOpen()
    {
        $now = now();
        
        if ($this->opens_at && $now->lt($this->opens_at)) {
            return false;
        }
        
        if ($this->closes_at && $now->gt($this->closes_at)) {
            return false;
        }
        
        return true;
    }

    public function getStatusAttribute()
    {
        $now = now();
        
        if ($this->opens_at && $now->lt($this->opens_at)) {
            return 'upcoming';
        }
        
        if ($this->closes_at && $now->gt($this->closes_at)) {
            return 'closed';
        }
        
        return 'open';
    }
}
