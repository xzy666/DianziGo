<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChallengeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('challenge', function (Blueprint $table) {
            $table->increments('id');
            $table->string('player_name')->nullable();
            $table->integer('player_chess')->nullable();
            $table->string('chessboard')->default('');
            $table->string('chess_order')->default('');
            $table->timestamp('begin_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamp('stop_at')->nullable();
            $table->integer('stop_time')->default(0);
            $table->integer('use_stop')->default(0);
            $table->integer('use_back')->default(0);
            $table->boolean('is_win')->default(false);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('challenge');
    }
}
