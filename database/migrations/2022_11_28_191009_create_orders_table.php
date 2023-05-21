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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->integer('recipientId')->unsigned();
            $table->integer('providerId')->unsigned();
            $table->integer('priceId')->unsigned();
            $table->enum('status', ['pending', 'inProgress', 'completed', 'canceled'])->default('pending');
            $table->integer('providerKills')->default(0)->nullable();
            $table->integer('providerDeaths')->default(0)->nullable();
            $table->integer('providerAssists')->default(0)->nullable();
            $table->integer('providerBodyShots')->default(0)->nullable();
            $table->integer('providerLegShots')->default(0)->nullable();
            $table->integer('providerHeadShots')->default(0)->nullable();
            $table->dateTime('createdAt')->nullable();
            $table->dateTime('completedAt')->nullable();
            $table->dateTime('canceledAt')->nullable();
            $table->enum('isWin',['win','tie','lose'])->nullable();
            $table->boolean('isSuccess')->default(false);
            $table->string('description');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
