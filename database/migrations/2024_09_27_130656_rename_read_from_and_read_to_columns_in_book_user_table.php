<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('book_user', function (Blueprint $table) {
            $table->renameColumn('read_from', 'started_reading_at');
            $table->renameColumn('read_to', 'finished_reading_at');
        });
    }

    public function down()
    {
        Schema::table('book_user', function (Blueprint $table) {
            $table->renameColumn('started_reading_at', 'read_from');
            $table->renameColumn('finished_reading_at', 'read_to');
        });
    }
};
