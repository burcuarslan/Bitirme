<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchCategory extends Model
{
    use HasFactory;
    protected $table = 'match_categories';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

}
