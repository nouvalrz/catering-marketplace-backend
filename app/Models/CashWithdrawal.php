<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashWithdrawal extends Model
{
    use HasFactory;

    protected $fillable = [
        'catering_id',
        'customer_id',
        'bank_name',
        'account_name',
        'bank_account',
        'nominal',
        'approved',
        'role',

    ];

    // public function catering()
    // {
    //     return $this->belongsTo(Catering::class, 'foreign_key');
    // }
}
