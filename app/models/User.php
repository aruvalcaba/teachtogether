<?php namespace TT\Models;

use Illuminate\Database\Eloquent\SoftDeletingTrait;
use Cartalyst\Sentry\Users\Eloquent\User as CartalystUser;

class User extends CartalystUser
{
    use SoftDeletingTrait;

    protected $table = 'users';

    public function __construct()
    {
        $this->setHasher(new \Cartalyst\Sentry\Hashing\NativeHasher);
    }
    
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function traits()
    {
        return $this->morphTo();
    }

    public function groups()
    {   
        return $this->belongsToMany(static::$groupModel, static::$userGroupsPivot, 'user_id', 'group_id');
    }
    
    public function students()
    {
        return $this->belongsToMany('TT\Models\Student','teachers_students','teacher_id','student_id')->get();
    }

    public function student()
    {
        return $this->belongsToMany('TT\Models\Student','teachers_students','teacher_id','student_id')->first();
    }
      
}
