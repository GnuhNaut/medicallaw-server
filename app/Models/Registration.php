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
        'members', 
        'email',
        'phone',
        'address',   // Đảm bảo có
        'question',  // Đảm bảo có
        'guest_type',// Đảm bảo có
        'field',   
        'payment_status',
        'email_sent_at', 
        'source',
    ];

    /**
     * Chuyển đổi kiểu dữ liệu của thuộc tính.
     *
     * @var array
     */
    protected $casts = [
        'email_sent_at' => 'datetime',
    ];
}