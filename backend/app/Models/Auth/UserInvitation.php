<?php
namespace App\Models\Auth;
use App\Abstracts\Model;
class UserInvitation extends Model
{
    protected $table = 'user_invitations';
    protected $tenantable = false;
    protected $fillable = ['user_id', 'token', 'created_from', 'created_by'];
    public function user()
    {
        return $this->belongsTo(user_model_class());
    }
    public function scopeToken($query, $token)
    {
        $query->where('token', $token);
    }
}
