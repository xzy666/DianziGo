<?php

namespace App\Http\Controllers;

use App\Challenge;
use App\Gobang;
use App\GobangGiven;
use App\GobangGivenMatch;
use App\Jobs\AddGobangJob;
use App\Match;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cookie;

class GobangController extends Controller
{
    /**
     * 创建挑战
     * @param Request $request
     * @param $player_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function CreateChallenge(Request $request, $player_name)
    {
        $new = Challenge::create(['player_name' => $player_name]);
        return response()->json(['status' => 0, 'inf' => '', 'challenge_id' => $new->id]);
    }

    /**
     * 猜先
     * @param Request $request
     * @param $switch
     * @return \Illuminate\Http\JsonResponse
     */
    public function SwitchChess(Request $request, $switch)
    {
        $id = $request->input('challenge_id', -1);
        $match_id = $request->input('match_id', -1);
        $player_name = $request->input('player', '');
        $switch = (int)$switch;

        if ($id != -1) {
            $challenge = Challenge::find($id);
            if (is_null($challenge)) {
                return response()->json(['status' => -1, 'inf' => '不存在挑战']);
            }

            if (!is_null($challenge->begin_at)) {
                return response()->json(['status' => -2, 'inf' => '游戏已经开始']);
            }

            if (!is_null($challenge->player_chess)) {
                return response()->json(['status' => 0, 'inf' => '已经选择阵营', 'player_chess' => $challenge->player_chess]);
            }

            if ($switch == 2) {
                //  让先
                $rand_chess = 0;
                $player_use_chess = 1;
            } else {
                //  电子狗随机
                $rand_chess = rand(1, 36);

                if ($rand_chess % 2 == $switch) {
                    //  猜先猜中
                    $player_use_chess = 2;
                } else {
                    $player_use_chess = 1;
                }
            }

            $challenge->player_chess = $player_use_chess;
            $challenge->begin_at = Carbon::now();
            $challenge->save();

            return response()->json(['status' => 0, 'inf' => $rand_chess, 'player_chess' => $player_use_chess]);
        } else {
            $match = Match::find($match_id);
            if (is_null($match)) {
                return response()->json(['status' => -1, 'inf' => '不存在比赛']);
            }

            if (!is_null($match->begin_at)) {
                return response()->json(['status' => -2, 'inf' => '游戏已经开始']);
            }

            if ($match->one_player_name == $player_name) {
                $match->one_player_chess = $switch;
                $match->save();
            } else {
                $match->ano_player_chess = $switch;
                $match->save();
            }

            return response()->json(['status' => 0]);
        }
    }

    /**
     * 猜先结果
     * @param Request $request
     * @param $id
     * @param $player
     * @return \Illuminate\Http\JsonResponse
     */
    public function SwitchAnswer(Request $request, $id, $player)
    {
        $match = Match::find($id);

        if (is_null($match)) {
            return response()->json(['status' => -1, 'inf' => '不存在比赛']);
        }

        if (!is_null($match->one_player_chess) and !is_null($match->ano_player_chess)) {
            $match->begin_at = Carbon::now();
            $match->save();

            if ($match->one_player_name == $player) {
                if ($match->one_player_chess == -1) {
                    return response()->json(['status' => 0, 'player_chess' => 1]);
                }

                if ($match->ano_player_chess == -1) {
                    return response()->json(['status' => 0, 'player_chess' => 2]);
                }

                if ($match->one_player_chess == $match->ano_player_chess) {
                    return response()->json(['status' => 0, 'player_chess' => 1]);
                } else {
                    return response()->json(['status' => 0, 'player_chess' => 2]);
                }
            } else {
                if ($match->one_player_chess == -1) {
                    return response()->json(['status' => 0, 'player_chess' => 2]);
                }

                if ($match->ano_player_chess == -1) {
                    return response()->json(['status' => 0, 'player_chess' => 1]);
                }

                if ($match->one_player_chess == $match->ano_player_chess) {
                    return response()->json(['status' => 0, 'player_chess' => 2]);
                } else {
                    return response()->json(['status' => 0, 'player_chess' => 1]);
                }
            }
        }

        return response()->json(['status' => -2]);
    }


    /**
     * 同步玩家落子
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function PlayerChess(Request $request)
    {
        $id = $request->input('challenge_id', -1);
        $chessboard = $request->input('chessboard', null);
        $step = $request->input('step', null);
        $x = $request->input('x', -1);
        $y = $request->input('y', -1);

        $challenge = Challenge::find($id);
        if (is_null($challenge)) {
            return response()->json(['status' => -1, 'inf' => '不存在挑战']);
        }

        if (is_null($challenge->begin_at)) {
            return response()->json(['status' => -2, 'inf' => '游戏还未开始']);
        }

        if (!is_null($challenge->end_at) or !is_null($challenge->stop_at)) {
            return response()->json(['status' => -3, 'inf' => '游戏未进行']);
        }

        if (is_null($chessboard) or is_null($step)) {
            return response()->json(['status' => -4, 'inf' => '参数错误']);
        }

        $chessboard = json_decode($chessboard);
        $this->dispatch(new AddGobangJob($id, $chessboard, $step, $x, $y, 3 - $challenge->player_chess));

        return response()->json(['status' => 0, 'inf' => '']);
    }

    /**
     * 同步电子狗落子
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DianziGoChess(Request $request)
    {
        $id = $request->input('challenge_id', -1);
        $step = $request->input('step', -1);

        $answer = GobangGiven::where(['challenge_id' => $id, 'step' => $step])->first();
        if (is_null($answer)) {
            return response()->json(['status' => -1]);
        }

        return response()->json(['status' => 0, 'inf' => '', 'x' => $answer->x, 'y' => $answer->y]);
    }

    /**
     * 匹配对手
     * @param Request $request
     * @param $player_name
     * @return \Illuminate\Http\JsonResponse
     */
    public function Match(Request $request, $player_name)
    {
        $match = Match::where(['one_player_name' => $player_name, 'is_win' => false])->first();
        if (!is_null($match) and !is_null($match->ano_player_name)) {
            return response()->json(['status' => 1, 'match_id' => $match->id, 'match' => $match->ano_player_name]);
        }

        $match = Match::where(['ano_player_name' => $player_name, 'is_win' => false])->first();
        if (!is_null($match) and !is_null($match->one_player_name)) {
            return response()->json(['status' => 1, 'match_id' => $match->id, 'match' => $match->one_player_name]);
        }

        $match = Match::where(['ano_player_name' => null, 'is_win' => false])->first();
        if (is_null($match)) {
            Match::create(['one_player_name' => $player_name]);
            return response()->json(['status' => 0]);
        } else {
            if ($match->one_player_name != $player_name) {
                $match->ano_player_name = $player_name;
                $match->save();
                return response()->json(['status' => 1, 'match_id' => $match->id, 'match' => $match->one_player_name]);
            }
        }

        return response()->json(['status' => 0]);
    }

    /**
     * 同步玩家落子(匹配)
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function MatchPlayerChess(Request $request)
    {
        $id = $request->input('match_id', -1);
        $chessboard = $request->input('chessboard', null);
        $step = $request->input('step', null);
        $x = $request->input('x', -1);
        $y = $request->input('y', -1);

        $match = Match::find($id);
        if (is_null($match)) {
            return response()->json(['status' => -1, 'inf' => '不存在游戏']);
        }

        if (is_null($match->begin_at)) {
            return response()->json(['status' => -2, 'inf' => '游戏还未开始']);
        }

        if (!is_null($match->end_at)) {
            return response()->json(['status' => -3, 'inf' => '游戏未进行']);
        }

        if (is_null($chessboard) or is_null($step)) {
            return response()->json(['status' => -4, 'inf' => '参数错误']);
        }

//        $match->chessboard = $chessboard;
        $match->save();

        GobangGivenMatch::create(['match_id' => $match->id, 'x' => $x, 'y' => $y, 'step' => $step]);
        return response()->json(['status' => 0, 'inf' => '']);
    }

    /**
     * 获取对手落子
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function PressAnswer(Request $request)
    {
        $id = $request->input('match_id', -1);
        $step = $request->input('step', -1);

        $answer = GobangGivenMatch::where(['match_id' => $id, 'step' => $step])->first();
        if (is_null($answer)) {
            return response()->json(['status' => -1]);
        }

        return response()->json(['status' => 0, 'inf' => '', 'x' => $answer->x, 'y' => $answer->y]);
    }

    /**
     * 结束
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function Success(Request $request, $id)
    {
        $match = Match::find($id);
        if (!is_null($match)) {
            $match->is_win = true;
            $match->end_at = Carbon::now();
            $match->save();
        }

        return response()->json(['status' => 0]);
    }
}
