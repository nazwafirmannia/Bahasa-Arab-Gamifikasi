<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'material';
    protected $primaryKey = 'id_material';
    
    protected $fillable = [
        'id_level',
        'title',
        'vocab_content',
        'grammar_content', 
        'dialog_content',
        'order'
    ];

    public $timestamps = false;

    public function level()
    {
        return $this->belongsTo(Level::class, 'id_level', 'id_level');
    }

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_material', 'id_material');
    }

    public function progress()
    {
        return $this->hasMany(UserMaterialProgress::class, 'id_material', 'id_material');
    }

    // Helper: Ambil konten berdasarkan tipe (untuk view tabs)
    public function getContentByType(string $type): ?string
    {
        return match($type) {
            'vocab' => $this->vocab_content,
            'grammar' => $this->grammar_content,
            'dialog' => $this->dialog_content,
            default => null,
        };
    }

    // Helper: Cek apakah semua bagian konten ada
    public function isComplete(): bool
    {
        return !empty($this->vocab_content) 
            && !empty($this->grammar_content) 
            && !empty($this->dialog_content);
    }
}