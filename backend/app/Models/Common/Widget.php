<?php
namespace App\Models\Common;
use App\Abstracts\Model;
use Bkwld\Cloner\Cloneable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Widget extends Model
{
    use Cloneable, HasFactory;
    protected $table = 'widgets';
    protected $fillable = ['company_id', 'dashboard_id', 'class', 'name', 'sort', 'settings', 'created_from', 'created_by'];
    protected $casts = [
        'settings'      => 'object',
        'deleted_at'    => 'datetime',
    ];
    public function scopeAlias($query, $alias)
    {
        $class = ($alias == 'core') ? 'App\\\\' : 'Modules\\\\' . Str::studly($alias) . '\\\\';
        return $query->where('class', 'like', $class . '%');
    }
    public function dashboard()
    {
        return $this->belongsTo('App\Models\Common\Dashboard');
    }
    public function users()
    {
        return $this->hasManyThrough(user_model_class(), 'App\Models\Common\Dashboard');
    }
    public function getAliasAttribute()
    {
        if (Str::startsWith($this->class, 'App\\')) {
            return 'core';
        }
        $arr = explode('\\', $this->class);
        return Str::kebab($arr[1]);
    }
    public function getSettingsAttribute($value)
    {
        $settings = ! empty($value) ? (object) json_decode($value) : (object) [];
        $settings->raw_width = false;
        if (isset($settings->width)) {
            $raw_width = $settings->width;
            $width = $this->getWidthAttribute($settings->width);
            if ($raw_width != $width) {
                $settings->raw_width = $raw_width;
            }
            $settings->width = $width;
        }
        return $settings;
    }
    public function getWidthAttribute($value)
    {
        $width = $value;
        switch ($width) {
            case '25':
                $width = 'w-full lg:w-1/4 lg:px-6';
                break;
            case '33':
                $width = 'w-full lg:w-1/3 px-6';
                break;
            case '50':
                $width = 'w-full lg:w-2/4 lg:px-6';
                break;
            case '100':
                $width = 'w-full px-6';
                break;
        }
        if (empty($width)) {
            $width = 'w-full lg:w-2/4 lg:px-6';
        }
        return $width;
    }
    protected static function newFactory()
    {
        return \Database\Factories\Widget::new();
    }
}
