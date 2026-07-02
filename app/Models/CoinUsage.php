<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class CoinUsage extends Model
{
    protected $table = 'coin_usage';
    protected $primaryKey = 'id_usage';
    
    protected $fillable = ['id_user', 'amount', 'type', 'reference_id'];
    
    protected $casts = ['amount' => 'integer'];
    
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    public function user() { return $this->belongsTo(User::class, 'id_user', 'id_user'); }
}