<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Task extends Model
{
    public function user() {
    	return $this->belongsTo('App\User');
    }

    protected $fillable = [
    	'title', 'due_date', 'due_time', 'note', 'subtasks', 'priority', 'user_id'
    ];

    protected $hidden = [
        'user_id'
    ];

    // public function getDueTimeAttribute($value) {
    // 	return Carbon::parse($value)->diffForHumans();
    // }
}
