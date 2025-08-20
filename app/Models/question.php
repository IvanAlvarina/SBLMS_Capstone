<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'text',
        'answer',
        'keywords',
        'order',
        'is_active'
    ];

    protected $casts = [
        'keywords' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Scope to get only active questions
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get questions in order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Search questions by keywords
     */
    public function scopeSearchByKeywords($query, $searchTerm)
    {
        $searchTerm = strtolower($searchTerm);
        
        return $query->where(function ($q) use ($searchTerm) {
            $q->where('text', 'like', '%' . $searchTerm . '%')
              ->orWhere('answer', 'like', '%' . $searchTerm . '%')
              ->orWhereRaw('LOWER(JSON_EXTRACT(keywords, "$[*]")) LIKE ?', ['%' . $searchTerm . '%']);
        });
    }

    /**
     * Get formatted answer with line breaks
     */
    public function getFormattedAnswerAttribute()
    {
        return nl2br($this->answer);
    }
}