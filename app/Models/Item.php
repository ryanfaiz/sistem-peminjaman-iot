<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{   
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'total_quantity',
        'available_quantity',
        'description',
        'condition',
        'photo_path',
    ];

    protected $casts = [
        'total_quantity' => 'integer',
        'available_quantity' => 'integer',
    ];

    public function loans()
    {
        return $this->belongsToMany(Loan::class, 'loan_items', 'item_id', 'loan_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
    
}