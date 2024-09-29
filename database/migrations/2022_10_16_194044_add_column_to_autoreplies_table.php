<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnToAutorepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autoreplies', function (Blueprint $table) {
            $table->enum('type_keyword',['Equal','Contain'])->after('keyword')->default('Equal');
            $table->enum('reply_when',['Group','Personal','All'])->after('reply')->default('All');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autoreplies', function (Blueprint $table) {
            $table->dropColumn('type_keyword');
            $table->dropColumn('reply_when');
        });
    }
}
