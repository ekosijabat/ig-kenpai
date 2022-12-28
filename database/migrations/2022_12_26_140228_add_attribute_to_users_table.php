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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->after('email_verified_at');
            $table->string('first_name', 20)->after('password');
            $table->string('last_name', 20)->after('first_name');
            $table->string('phone_number', 15)->after('last_name');
            $table->date('date_of_birth')->after('phone_number')->nullable()->default(null);
            $table->string('profile_pic')->after('date_of_birth')->nullable();
            $table->string('path')->after('profile_pic')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
