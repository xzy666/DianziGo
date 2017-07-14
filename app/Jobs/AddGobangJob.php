<?php

namespace App\Jobs;

use App\Gobang;
use App\GobangGiven;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddGobangJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Job params
     */
    protected $id;

    protected $chessboard;

    protected $step;

    protected $x;

    protected $y;

    protected $color;

    /**
     * Create a new command instance.
     *
     * @param $chessboard
     * @param $step
     * @param $x
     * @param $y
     * @param $color
     */
    public function __construct($id, $chessboard, $step, $x, $y, $color)
    {
        $this->id = $id;
        $this->chessboard = $chessboard;
        $this->step = $step;
        $this->x = $x;
        $this->y = $y;
        $this->color = $color;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $answer = Gobang::getAnswer($this->chessboard, $this->color, $this->step);

        GobangGiven::create([
            'challenge_id' => $this->id,
            'step' => $this->step + 1,
            'x' => $answer['x'],
            'y' => $answer['y']
        ]);

        return;
    }
}
