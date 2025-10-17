<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Đổi tên cột `type` thành `guest_type` để rõ nghĩa hơn
            $table->renameColumn('type', 'guest_type');

            // Thêm các cột mới
            $table->integer('members')->default(1)->after('position');
            $table->text('address')->after('phone');
            $table->text('question')->nullable()->after('address');
            $table->string('field')->nullable()->after('question');

            // Bỏ cột `need` không còn sử dụng
            $table->dropColumn('need');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('registrations', function (Blueprint $table) {
            // Định nghĩa hành động "hoàn tác" để có thể quay lại nếu cần
            $table->renameColumn('guest_type', 'type');
            $table->dropColumn(['members', 'address', 'question', 'field']);
            $table->text('need')->nullable();
        });
    }
};