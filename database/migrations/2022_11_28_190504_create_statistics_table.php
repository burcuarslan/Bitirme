<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('statistics', function (Blueprint $table) {
                $table->id();
                $table->integer('userId')->unique()->unsigned();
                //$table->enum('rank', ['bronze', 'silver', 'gold', 'platinum', 'diamond', 'ascendant', 'immortal', 'radiant']);
                $table->string('rank')->nullable();
                $table->integer('kda')->nullable();
                $table->integer('winRate')->nullable();
                $table->integer('rating')->nullable();
                $table->integer('currentTier')->nullable();
                $table->integer('rankingInTier')->nullable();
                $table->integer('mmrChangeToLastGame')->nullable();
                $table->integer('elo')->nullable();
                $table->boolean('old')->nullable();
                //  $table->integer('gameCharachterId')->unsigned();

                $table->foreign('userId')->references('id')->on('users')->name('fk_statistics_users');
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('statistics');
        }
    };
