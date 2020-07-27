<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{

    protected $fillable = ['name','description','position','project_id'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function saveQuietly(array $options = [])
    {
        return static::withoutEvents(function () use ($options) {
            return $this->save($options);
        });
    }


}
