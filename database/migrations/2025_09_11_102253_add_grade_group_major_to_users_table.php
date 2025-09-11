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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedTinyInteger('grade')->nullable()->after('is_admin'); // 1-6
            $table->string('group', 1)->nullable()->after('grade'); // A-D
            $table->string('major', 8)->nullable()->after('group'); // IE, ISC, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['grade', 'group', 'major']);
        });
    }
};
