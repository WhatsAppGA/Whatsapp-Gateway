<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlastsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('sender')->references('body')->on('devices')->onDelete('cascade');
            $table->foreignId('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->string('receiver');
            $table->json('message');
            $table->enum('type', ['text', 'button', 'image', 'template','list','media']);
            $table->enum('status', ['failed', 'success','pending']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blasts');
    }
}

