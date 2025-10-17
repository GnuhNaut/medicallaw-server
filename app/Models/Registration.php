<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    /**
     * Các thuộc tính có thể được gán hàng loạt.
     *
     * @var array
     */
    protected $fillable = [
        'ticket_id',
    'name',
    'position',
    'members', // Thêm
    'email',
    'phone',
    'address',   // Thêm
    'question',  // Thêm
    'guest_type',// Thêm (thay cho 'type')
    'field',     // Thêm
    ];
}