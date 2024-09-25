<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::rename('book_users', 'book_user');
    }

    public function down()
    {
        Schema::rename('book_user', 'book_users');
    }
};
