<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMaterialProgress extends Model
{
    protected $table = 'user_material_progress';
    
    // ❌ HAPUS/KOMENTARI INI (Laravel tidak support array primary key):
    // protected $primaryKey = ['id_user', 'id_material'];
    
    // ✅ Biarkan default 'id' (tidak akan dipakai karena kita pakai query builder)
    
    public $incrementing = false; // Nonaktifkan auto-increment karena tidak pakai 'id'
    public $timestamps = false;   // Jika tabel tidak punya created_at/updated_at
    
    protected $fillable = [
        'id_user',
        'id_material', 
        'is_selesai',      // ✅ Pastikan nama kolom sesuai DB
        'completed_at',
        'practice_completed',        
        'practice_completed_at'
    ];

    protected $casts = [
        'is_selesai' => 'boolean',  // ✅ Sesuaikan dengan nama kolom di DB
        'completed_at' => 'datetime',
        'completed_at' => 'datetime',
        'practice_completed_at' => 'datetime',
    ];

    // Relasi
    public function user() 
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function material() 
    {
        return $this->belongsTo(Material::class, 'id_material', 'id_material');
    }
}