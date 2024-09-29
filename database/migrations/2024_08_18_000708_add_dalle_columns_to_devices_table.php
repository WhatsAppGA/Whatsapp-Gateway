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
		Schema::table('devices', function (Blueprint $table) {
			$table->string('dalle_name')->nullable();
			$table->string('dalle_api')->nullable();
		});
	}

	public function down()
	{
		Schema::table('devices', function (Blueprint $table) {
			$table->dropColumn(['dalle_name', 'dalle_api']);
		});
	}
};
