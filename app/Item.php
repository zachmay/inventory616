<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model {

    protected $fillable = ['asset_tag', 'name', 'funding_source', 'model', 'cpu', 'ram',
        'os', 'administrator_flag', 'teacher_flag', 'student_flag', 'institution_flag'];

    public function itemType()
    {
        return $this->belongsTo('App\ItemType');
    }

    public function checkIns()
    {
        return $this->hasMany('App\CheckIn');
    }

}
