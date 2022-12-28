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
        Schema::create('tr_posts_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained('tm_posts');
            $table->string('picture')->nullable();
            $table->string('path')->nullable();
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
        Schema::dropIfExists('tr_posts_pictures');
    }
};
