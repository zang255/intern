<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Thêm cột 'role_id' để liên kết với bảng roles
            $table->unsignedBigInteger('role_id')->default(2); // Giá trị mặc định là ID của role 'user'
            
            // Tạo khóa ngoại cho cột role_id
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade'); // Thêm cột role với giá trị mặc định là 'user'
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Xóa khóa ngoại trước khi xóa cột
            $table->dropForeign(['role_id']);

            // Xóa cột role_id
            $table->dropColumn('role_id');
        });
    }
};
