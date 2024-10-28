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
        Schema::table('books', function (Blueprint $table) {
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Thêm cột user_id và thiết lập khóa ngoại
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // Xóa khóa ngoại nếu rollback
            $table->dropColumn('user_id'); // Xóa cột user_id
        });
    }
};
