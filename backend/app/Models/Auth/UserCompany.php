<?php
namespace App\Models\Auth;
use App\Abstracts\Model;
class UserCompany extends Model
{
    protected $table = 'user_companies';
    protected $tenantable = false;
    protected $fillable = ['user_id', 'company_id'];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo(user_model_class());
    }
    public function company()
    {
        return $this->belongsTo('App\Models\Common\Company');
    }
}
