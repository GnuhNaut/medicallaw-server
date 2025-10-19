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
        'field',     // Đảm bảo có
        'payment_status', // Thêm 2 trường này để `markAsPaid` hoạt động
        'email_sent_at',  // (Mặc dù $fillable chủ yếu cho create/update hàng loạt,
                          // nhưng `markAsPaid` có dùng `save()` nên không bị ảnh hưởng)
                          // Cứ để $fillable như cũ là đủ.
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