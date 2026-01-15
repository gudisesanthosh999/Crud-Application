<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Item extends Model
{
    use HasUuids;


protected $primaryKey = 'uuid';
public $incrementing = false;
protected $keyType = 'string';


protected $fillable = [
'name', 'code', 'description', 'status'
];

public function getRouteKeyName()
    {
        return 'uuid';
    }
}
