<?php 
namespace App\Models;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserBadge extends Pivot
{
    protected $table = 'user_badge';
    
    protected $casts = ['obtained_at' => 'datetime'];
    public $timestamps = false;
}