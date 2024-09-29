<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBotsFieldsToDevicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('devices', function (Blueprint $table) {
            $table->boolean('typebot')->default(false);
			$table->boolean('reject_call')->default(false);
			$table->text('reject_message')->nullable();
			$table->boolean('can_read_message')->default(false);
			$table->enum('reply_when', ['Group', 'Personal', 'All'])->default('Personal');
			$table->string('chatgpt_name')->nullable();
			$table->string('chatgpt_api')->nullable();
			$table->string('gemini_name')->nullable();
			$table->string('gemini_api')->nullable();
			$table->string('claude_name')->nullable();
			$table->string('claude_api')->nullable();
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
			$table->dropColumn('typebot');
			$table->dropColumn('reject_call');
			$table->dropColumn('reject_message');
			$table->dropColumn('can_read_message');
			$table->dropColumn('reply_when');
			$table->dropColumn('chatgpt_name');
			$table->dropColumn('chatgpt_api');
			$table->dropColumn('gemini_name');
			$table->dropColumn('gemini_api');
			$table->dropColumn('claude_name');
			$table->dropColumn('claude_api');
		});
    }
}
