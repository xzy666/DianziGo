<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('match', function (Blueprint $table) {
            $table->increments('id');
            $table->string('one_player_name')->nullable();
            $table->integer('one_player_chess')->nullable();
            $table->string('ano_player_name')->nullable();
            $table->integer('ano_player_chess')->nullable();

            $table->string('chessboard')->default('');
            $table->string('chess_order')->default('');

            $table->timestamp('begin_at')->nullable();
            $table->timestamp('end_at')->nullable();

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
        Schema::dropIfExists('match');
    }
}
