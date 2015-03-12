<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model {

    protected $fillable = ['name', 'description'];

    public function building()
    {
        return $this->belongsTo('App\Building');
    }

    public function checkIns()
    {
        return $this->hasMany('App\CheckIn');
    }
}
