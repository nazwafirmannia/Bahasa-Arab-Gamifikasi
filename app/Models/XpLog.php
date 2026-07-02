<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class XpLog extends Model
{
    protected $table = 'xp_log';
    protected $primaryKey = 'id_log';
    
    protected $fillable = ['id_user', 'amount', 'source', 'reference_id'];
    
    protected $casts = ['amount' => 'integer'];
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
}