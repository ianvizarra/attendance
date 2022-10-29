<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

return new class extends Migration
{
    public function up()
    {
        Schema::create(Config::get('attendance.table'), function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->enum('type', ['in', 'out']);
            $table->enum('status', ['on-time', 'late', 'overtime']);
            $table->timestamps();
        });
    }
};
