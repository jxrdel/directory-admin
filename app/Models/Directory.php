<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'directory';

    protected $primaryKey = 'id';

    protected $fillable = [
        'id',
        'location',
        'department',
        'groupname',
        'employee',
        'extname',
        'extno',
    ];

    public static function extExists($extno)
    {
        return self::where('extno', $extno)->exists();
    }
}
