<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/YYYY_MM_DD_His_create_registrations_table.php

    public function up()
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id(); // Cột `id` tự tăng
            $table->string('ticket_id', 10)->unique(); // ID vé, duy nhất
            $table->string('name');
            $table->string('position');
            $table->string('email')->index(); // `index()` để tìm kiếm nhanh hơn
            $table->string('phone', 20);
            $table->string('type')->nullable(); // `nullable` cho phép giá trị rỗng
            $table->text('need')->nullable();
            $table->string('payment_status', 20)->default('pending'); // Mặc định là 'pending'
            $table->string('ticket_status', 20)->default('not_used');
            $table->json('vietqr_data')->nullable(); // Lưu trữ dữ liệu từ VietQR
            $table->timestamps(); // Tự động tạo 2 cột `created_at` và `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
