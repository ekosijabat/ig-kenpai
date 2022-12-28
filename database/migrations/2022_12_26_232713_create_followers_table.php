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
        Schema::create('tr_followers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('follower_id');
            $table->string('status')->default('1')->comment('1 = follow, 2 = unfollow');
            $table->timestamps();
            $table->date('deleted_at')->nullable()->default(null);
            $table->integer('created_by');
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('followers');
    }
};
