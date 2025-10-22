<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm cột 'source'
        Schema::table('registrations', function (Blueprint $table) {
            $table->string('source')
                  ->nullable() // Cho phép giá trị NULL
                  ->after('field'); // Đặt nó sau cột 'field' (hoặc bất cứ cột nào bạn muốn)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Định nghĩa cách xóa cột nếu cần rollback
        Schema::table('registrations', function (Blueprint $table) {
            $table->dropColumn('source');
        });
    }
};