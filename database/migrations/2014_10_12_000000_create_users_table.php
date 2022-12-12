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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            //$table->integer('walletId')->unique();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('userName');
            $table->string('puuId');
            $table->string('tagline');
            $table->string('region');
            $table->string('phoneNumber')->unique();
            $table->string('description')->nullable();
            $table->boolean('isVerified')->default(false);
            $table->dateTime('mailVerifiedAt')->nullable();
            $table->enum('status', ['online', 'offline','ingame'])->default('offline');

            $table->integer('followers')->default(0);
            $table->integer('following')->default(0);
            $table->string('avatar')->nullable();
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
        Schema::dropIfExists('users');
    }
};
