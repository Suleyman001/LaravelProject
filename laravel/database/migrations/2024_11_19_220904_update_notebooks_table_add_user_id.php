<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class UpdateNotebooksTableAddUserId extends Migration
{
    public function up()
    {
        Schema::table('notebooks', function (Blueprint $table) {
            // Add nullable user_id column if it doesn't exist
            if (!Schema::hasColumn('notebooks', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
        });

        // Set system/admin user for existing notebooks
        $adminUser = DB::table('users')->where('role', 'admin')->first();
        if ($adminUser) {
            DB::table('notebooks')->update(['user_id' => $adminUser->id]);
        }
    }

    public function down()
    {
        Schema::table('notebooks', function (Blueprint $table) {
            $table->dropColumn('user_id');
        });
    }
}