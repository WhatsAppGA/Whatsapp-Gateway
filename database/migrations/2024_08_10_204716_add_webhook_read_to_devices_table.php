<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWebhookReadToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('webhook_read')->default(false);
			$table->boolean('webhook_reject_call')->default(false);
			$table->boolean('webhook_typing')->default(false);
			$table->boolean('bot_typing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->dropColumn('webhook_read');
			$table->dropColumn('webhook_reject_call');
			$table->dropColumn('webhook_typing');
			$table->dropColumn('bot_typing');
        });
    }
};
