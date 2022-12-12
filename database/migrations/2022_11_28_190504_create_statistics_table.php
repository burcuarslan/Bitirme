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
                $table->string('rank');
                $table->integer('kda')->nullable();
                $table->integer('winRate')->nullable();
                $table->integer('rating')->nullable();
                $table->integer('currentTier');
                $table->integer('rankingInTier');
                $table->integer('mmrChangeToLastGame');
                $table->integer('elo');
                $table->boolean('old');
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
