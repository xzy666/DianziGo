<?php
/**
 * Created by PhpStorm.
 * User: sky
 * Date: 2017/6/1
 * Time: 下午10:45
 */

namespace App;


use League\Flysystem\Exception;

class Gobang
{
    protected static $defendRule = [
        // key value
        [4.5, 10],
        [4, 10],
        [3.5, 8],
        [2.5, 4.5],
        [1.5, 1.5],
        [1, 1],
        [3, 4.5],
        [2, 1.5],
    ];

    protected static $attackRule = [
        // key value
        [4.5, 10],
        [4, 10],
        [3.5, 9],
        [2.5, 4],
        [1.5, 1.5],
        [1, 1],
        [3, 4.5],
        [2, 1.5],
    ];

    protected static $art = [
        // key => array(len blank canPass need value remove-self)
        '0' => [[5, 1, 0, 2, 3.5, 0],],
        '3' => [[4, 1, 1, 1, 3.5, 0],],
        '3.5' => [[4, 1, 1, 1, 3.5, -1]],
    ];

    /**
     * 入口函数
     * 获取结果
     * @param $chessboard
     * @param $color
     * @param $step
     * @return array
     */
    public static function getAnswer($chessboard, $color, $step)
    {
        if ($step == 0) {
            //  黑棋起手第一步，稳落天元
            return ['x' => 9, 'y' => 9];
        }

        $node_list = self::nodeCalculator($chessboard, $color);
        return self::thinkingSelector($node_list[0], $node_list[1], $chessboard, $color, $step);
    }

    /**
     * 节点数值计算器
     * @param $chessboard
     * @param $color
     * @return array
     */
    protected static function nodeCalculator($chessboard, $color)
    {
        $defend_list = array();
        $attack_list = array();
        $answer_count = 0;

        for ($i = 0; $i < 19; $i++) {
            for ($j = 0; $j < 19; $j++) {
                if ($chessboard[$i][$j] == 0) {
                    $defend_value = self::defendCalculator($i, $j, $chessboard, $color);
                    $attack_value = self::attackCalculator($i, $j, $chessboard, $color);
                    if ($defend_value != 0) {
                        array_push($defend_list, [$i, $j, $defend_value, $answer_count]);
                    }

                    if ($attack_value != 0) {
                        array_push($attack_list, [$i, $j, $attack_value, $answer_count]);
                    }

                    $answer_count++;
                }
            }
        }

        return [$defend_list, $attack_list];
    }

    /**
     * 选择器
     * @param $defend_list
     * @param $attack_list
     * @param $color
     * @return array
     */
    protected static function selector($defend_list, $attack_list, $color)
    {
        //  获取攻击与防御最值
        if (count($attack_list) < 1) {
            $max_attack = -1;
        } else {
            $max_attack = $attack_list[0][2];
        }

        if (count($defend_list) < 1) {
            $max_defend = -1;
        } else {
            $max_defend = $defend_list[0][2];
        }

        //  决胜手
        if ($max_attack > 9) {
            return ['x' => $attack_list[0][0], 'y' => $attack_list[0][1], 'id' => $attack_list[0][3]];
        }

        //  绝负手
        if ($max_defend > 9) {
            return ['x' => $defend_list[0][0], 'y' => $defend_list[0][1], 'id' => $defend_list[0][3]];
        }

        //  预负手；负手优先
        if ($max_defend > 7) {
            if ($max_attack > 8 and $max_defend < $max_attack) {
                //  存在预胜手, 且预胜手大于预负手
                return ['x' => $attack_list[0][0], 'y' => $attack_list[0][1], 'id' => $attack_list[0][3]];
            }

            return ['x' => $defend_list[0][0], 'y' => $defend_list[0][1], 'id' => $defend_list[0][3]];
        }

        $i = 0;
        $ii = 0;

        //  胜负手选择
        if ($max_attack == $max_defend) {
            //  胜负手相等
            while ($i < count($defend_list)) {
                if ($defend_list[$i][2] != $max_defend) {
                    break;
                }

                $j = 0;
                while ($j < count($attack_list)) {
                    if ($attack_list[$j][2] != $max_attack) {
                        break;
                    }
                    if ($attack_list[$j][3] == $defend_list[$i][3]) {
                        //  攻守兼备
                        return ['x' => $defend_list[$i][0], 'y' => $defend_list[$i][1], 'id' => $defend_list[$i][3]];
                    }
                    $j++;
                }
                $ii = $j;

                $i++;
            }

            //  不存在攻守兼备
            if ($max_defend < 6) {
                $select = rand(0, $ii - 1);
                if ($select < 0) {
                    $select = 0;
                }
                return ['x' => $attack_list[$select][0], 'y' => $attack_list[$select][1], 'id' => $attack_list[$select][3]];
            } else {
                $select = rand(0, $i - 1);
                if ($select < 0) {
                    $select = 0;
                }
                return ['x' => $defend_list[$select][0], 'y' => $defend_list[$select][1], 'id' => $defend_list[$select][3]];
            }
        }

        while ($i < count($defend_list)) {
            if ($defend_list[$i][2] != $max_defend) {
                break;
            }

            $i++;
        }

        while ($ii < count($attack_list)) {
            if ($attack_list[$ii][2] != $max_attack) {
                break;
            }

            $ii++;
        }

        //  不存在攻守兼备
        if ($color == 2) {
            //  黑方抢攻
            if (count($attack_list) > 0 and $max_defend < 6) {
                $select = rand(0, $ii - 1);

                return ['x' => $attack_list[$select][0], 'y' => $attack_list[$select][1], 'id' => $attack_list[$select][3]];
            } else {
                if ($max_attack < $max_defend) {
                    $select = rand(0, $i - 1);
                    if ($select < 0) {
                        $select = 0;
                    }
                    return ['x' => $defend_list[$select][0], 'y' => $defend_list[$select][1], 'id' => $defend_list[$select][3]];
                } else {
                    $select = rand(0, $ii - 1);
                    if ($select < 0) {
                        $select = 0;
                    }
                    return ['x' => $attack_list[$select][0], 'y' => $attack_list[$select][1], 'id' => $attack_list[$select][3]];
                }
            }
        } else {
            if (count($attack_list) > 0 and $max_attack > 5) {
                $select = rand(0, $ii - 1);
                if ($select < 0) {
                    $select = 0;
                }
                return ['x' => $attack_list[$select][0], 'y' => $attack_list[$select][1], 'id' => $attack_list[$select][3]];
            } else {
                if ($max_attack < $max_defend) {
                    $select = rand(0, $i - 1);
                    if ($select < 0) {
                        $select = 0;
                    }
                    return ['x' => $defend_list[$select][0], 'y' => $defend_list[$select][1], 'id' => $defend_list[$select][3]];
                } else {
                    $select = rand(0, $ii - 1);
                    if ($select < 0) {
                        $select = 0;
                    }
                    return ['x' => $attack_list[$select][0], 'y' => $attack_list[$select][1], 'id' => $attack_list[$select][3]];
                }
            }
        }
    }

    /**
     * 获取解空间
     * @param $defend_list
     * @param $attack_list
     * @param $chessboard
     * @param $color
     * @return array
     */
    protected static function getAnswerSpace($defend_list, $attack_list, $chessboard, $color)
    {
        $answer_set = array();

        if (count($defend_list) < 1 and count($attack_list) < 1) {
            while (true) {
                $x = rand(0, 18);
                $y = rand(0, 18);

                if ($chessboard[$x][$y] == 0) {
                    return ['x' => $x, 'y' => $y];
                }
            }
        }

        //  攻击数值排序
        usort($attack_list, function ($a, $b) {
            return $a[2] < $b[2] ? 1 : 0;
        });

        //  防御数值排序
        usort($defend_list, function ($a, $b) {
            return $a[2] < $b[2] ? 1 : 0;
        });

        //  拥有必然情况
        if (count($attack_list) > 0 and $attack_list[0][2] > 9) {
            array_push($answer_set, ['x' => $attack_list[0][0], 'y' => $attack_list[0][1], 'g' => 10]);
            return $answer_set;
        }

        if (count($defend_list) > 0 and $defend_list[0][2] > 9) {
            array_push($answer_set, ['x' => $defend_list[0][0], 'y' => $defend_list[0][1], 'g' => 10]);
            return $answer_set;
        }

//        //  拥有准胜状态
//        if (count($attack_list) > 0 and $attack_list[0][2] > 8) {
//            array_push($answer_set, ['x' => $attack_list[0][0], 'y' => $attack_list[0][1], 'g' => 9]);
//            return $answer_set;
//        }
//
//        if (count($defend_list) > 0 and $defend_list[0][2] > 8) {
//            array_push($answer_set, ['x' => $defend_list[0][0], 'y' => $defend_list[0][1], 'g' => 9]);
//            return $answer_set;
//        }

        $flag = false;
        if (count($attack_list) > 0 and $attack_list[0][2] > 7) {
            $flag = true;
        }

        if (count($defend_list) > 0 and $defend_list[0][2] > 7) {
            $flag = true;
        }

        //  获取优解空间
        for ($i = 0; $i < 4; $i++) {
            if (count($defend_list) < 1 and count($attack_list) < 1) {
                break;
            }

            $answer = self::selector($defend_list, $attack_list, $color);
            $answer['g'] = 0;

            //  将已取出点退出队列
            $flag_num = 0;
            foreach ($attack_list as $key => $value) {
                if ($value[3] == $answer['id']) {
                    $answer['g'] += $value[2];
                    $flag_num = $value[2];
                    array_splice($attack_list, $key, 1);
                }
            }

            foreach ($defend_list as $key => $value) {
                if ($value[3] == $answer['id']) {
                    $answer['g'] += $value[2];
                    $flag_num = $value[2] > $flag_num ? $value[2] : $flag_num;
                    array_splice($defend_list, $key, 1);
                }
            }

            if ($flag and $flag_num < 8) {
                break;
            }
            array_push($answer_set, $answer);
        }

//        if (count($answer_set) < 1) {
//            echo "Down1!!!\n";
//        }

        return $answer_set;
    }

    /**
     * 思考选择器
     * @param $defend_list
     * @param $attack_list
     * @param $chessboard
     * @param $color
     * @param $step
     * @return array
     */
    public static function thinkingSelector($defend_list, $attack_list, $chessboard, $color, $step)
    {
        $answer_set = self::getAnswerSpace($defend_list, $attack_list, $chessboard, $color);

//        if (count($answer_set) < 1) {
//            echo "Down2!!!\n";
//        }
//        var_dump($answer_set);

        if (count($answer_set) > 1 and $step > 5) {
//        var_dump($answer_set);
            //  搜索解空间
            foreach ($answer_set as $k => $v) {
                $chessboard[$v['x']][$v['y']] = $color;
                $answer_set[$k]['heu'] = self::heu($chessboard, 3 - $color, $color, 0);
                $chessboard[$v['y']][$v['y']] = 0;
            }

//            var_dump($answer_set);

            usort($answer_set, function ($a, $b) {
                $a_v = $a['heu'] + $a['g'];
                $b_v = $b['heu'] + $b['g'];

                return $a_v < $b_v ? 1 : 0;
            });
        }
//
//        if (count($answer_set) < 1) {
//            echo "Down3!!!\n";
//        }

//        var_dump($answer_set);
//        var_dump($answer_set);

        return ['x' => $answer_set[0]['x'], 'y' => $answer_set[0]['y']];
        //return self::selector($defend_list, $attack_list, $chessboard, $color);
    }

    /**
     * 价值估算器
     * @param $chessboard
     * @param $color
     * @param $go
     * @param $deep
     * @return int
     */
    public static function heu($chessboard, $color, $go, $deep)
    {
//        echo "Deep:" . $deep . "\n";
        $is_player = -1;
        if ($go == $color) {
            $is_player = 1;
        }

        $node_list = self::nodeCalculator($chessboard, $color);
        $answer_set = self::getAnswerSpace($node_list[0], $node_list[1], $chessboard, $color);

        if ($deep == 5) {
            //  本层级估算值计算
            $heu = 0;
            foreach ($answer_set as $answer) {
                if ($answer['g'] > 9) {
                    return $is_player * 200 * (0.1 * (7 - $deep));
                }

                if ($answer['g'] > 7) {
                    return $is_player * 166 * (0.1 * (7 - $deep));
                }

                $heu += $answer['g'];
            }

            return $is_player * $heu;
        }

        $child_heu = 0;
//        echo "Answer space size:" . count($answer_set) . "\n";
        foreach ($answer_set as $answer) {
            $chessboard[$answer['x']][$answer['y']] = $color;
            $child_heu += self::heu($chessboard, 3 - $color, $go, $deep + 1);
            $chessboard[$answer['x']][$answer['y']] = 0;
        }

        return $child_heu;
    }

    /**
     * 防守计算器
     * @param $x
     * @param $y
     * @param $chessboard
     * @param $color
     * @return double
     */
    public static function defendCalculator($x, $y, $chessboard, $color)
    {
        $dir_set = [[-1, 0], [1, 0], [0, -1], [0, 1], [1, 1], [-1, -1], [-1, 1], [1, -1]];
        $dir_value = [];
        $DianziGo = $color;
        $player = 3 - $color;

        $edge = 0;
        $count = 0.0;
        for ($i = 0; $i < 8; $i++) {
            $dir = $dir_set[$i];
            $xt = $x;
            $yt = $y;
            $step = 0;
            $last_count = $count;
            $count = 0.0;

            if (++$edge == 3) {
                $edge = 1;
            }

            while ($step < 4) {
                $xt += $dir[0];
                $yt += $dir[1];
                if ($xt > 18 or $xt < 0 or $yt > 18 or $yt < 0) {
                    break;
                }

                if ($chessboard[$xt][$yt] == $player) {
                    $count += 1;
                } else {
                    $count += $chessboard[$xt][$yt] == $DianziGo ? 0 : 0.5;
                    break;
                }

                $step++;
            }

//            echo "常规：" . strval($count) . " 方向：" . $i .  "\n";

            if ($count < 1) {
                continue;
            }

            //  剔除无用步
            $j = $i + ($i % 2) * -2 + 1;
            $need = 5 - intval($count) - 1;
            $s_step = 0;
            $xtt = $x;
            $ytt = $y;

            while ($s_step < 4) {
                $xtt += $dir_set[$j][0];
                $ytt += $dir_set[$j][1];
                if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $DianziGo) {
                    break;
                }

                $s_step++;
            }

            //echo "need：" . $need . " step：" .$s_step . "\n";
            if ($s_step >= $need) {
                //echo "useful\n";
                if (array_key_exists(strval($count), $dir_value)) {
                    $dir_value[strval($count)]++;
                } else {
                    $dir_value[strval($count)] = 1;
                }
            }

//            echo "Edge" . $edge . "\n";
            //  连接
            if ($edge == 2 and $last_count > 0.5 and $count > 0.5) {
                //  无用连接检测
                $need = 5 - intval($count) - intval($last_count) - 1;
                $have = 0;
                $useful = $need <= 0;
                $j = $i + ($i % 2) * -2 + 1;

                $s_step = intval($count);
                $xtt = $x + $dir_set[$i][0] * $s_step;
                $ytt = $y + $dir_set[$i][1] * $s_step;
                while ($s_step < 5 and !$useful) {
                    $xtt += $dir_set[$i][0];
                    $ytt += $dir_set[$i][1];
                    if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $DianziGo) {
                        break;
                    }

                    $have++;
                    if ($have > $need) {
                        $useful = true;
                        break;
                    }

                    $s_step++;
                }

                $s_step = intval($last_count);
                $xtt = $x + $dir_set[$j][0] * $s_step;
                $ytt = $y + $dir_set[$j][1] * $s_step;
                while ($s_step < 5 and !$useful) {
                    $xtt += $dir_set[$j][0];
                    $ytt += $dir_set[$j][1];
                    if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $DianziGo) {
                        break;
                    }

                    $have++;
                    if ($have > $need) {
                        $useful = true;
                        break;
                    }

                    $s_step++;
                }

                if ($useful) {
                    $edge_count = $last_count + $count - 0.5;

//                try {
//                    file_get_contents("http://localhost/log/" . strval(99) . '/' .$x . '/' . $y . '/' . $i);
//                } catch (Exception $exception) {
//
//                }
                    if ($edge_count > 4.5) {
                        $edge_count = 4.5;
                    }

//                    echo "Link count:" . $edge_count . " Link " . $i . " last:" . $last_count . " c:" . $count ."\n";

                    if (array_key_exists(strval($edge_count), $dir_value)) {
                        $dir_value[strval($edge_count)]++;
                    } else {
                        $dir_value[strval($edge_count)] = 1;
                    }
                }
            }
            //file_get_contents("http://localhost/log/" . $edge . '/' .$x . '/' . $y . '/' . $i);
        }

        $dir_value['0'] = 1;
        foreach ($dir_value as $count => $value) {
            //  加特技
            if (array_key_exists(strval($count), self::$art)) {
                foreach (self::$art[strval($count)] as $_art) {

                    $need = 0;
                    for ($j = 0; $j < 8; $j++) {
                        $xtt = $x;
                        $ytt = $y;
                        $s_step = 0;
                        $blank = 0;
                        $is_art = true;

                        while ($s_step < $_art[0]) {
                            $xtt += $dir_set[$j][0];
                            $ytt += $dir_set[$j][1];
                            if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0) {
                                $is_art = false;
                                break;
                            }

                            if ($s_step != $_art[0] - 1 and $chessboard[$xtt][$ytt] == 0) {
                                $blank += 1;
//                                echo $j . "emmmmmmm.". $blank . ":" . $s_step ."\n";
                                if ($blank > $_art[1]) {
                                    $is_art = false;
                                    break;
                                }
                            }

                            if ($s_step != $_art[0] - 1 and $chessboard[$xtt][$ytt] == $DianziGo) {
                                $is_art = false;
                                break;
                            }

                            if ($s_step == $_art[0] - 1) {
                                if ($chessboard[$xtt][$ytt] != 0 and $_art[2] == 1) {
                                    $is_art = false;
                                    break;
                                }
                            }

                            $s_step++;
                        }

                        if ($is_art) {
                            $need++;
//                            echo "Happened art!\n";
                            if ($need - $_art[5] == $_art[3]) {
                                if (array_key_exists(strval($_art[4]), $dir_value)) {
                                    $dir_value[strval($_art[4])]++;
                                } else {
                                    $dir_value[strval($_art[4])] = 1;
                                }

                                break;
                            }
                        }
                    }
                }
            }
        }

        $score = 0.0;
        foreach (self::$defendRule as $rule) {
            if (array_key_exists(strval($rule[0]), $dir_value)) {
                $s = $dir_value[strval($rule[0])] * $rule[1];
                $score = $s > $score ? $s : $score;
            }
        }

        return $score;
    }

    /**
     * 攻击计算器
     * @param $x
     * @param $y
     * @param $chessboard
     * @param $color
     * @return double
     */
    public static function attackCalculator($x, $y, $chessboard, $color)
    {
        $dir_set = [[-1, 0], [1, 0], [0, -1], [0, 1], [1, 1], [-1, -1], [-1, 1], [1, -1]];
        $dir_value = [];
        $DianziGo = $color;
        $player = 3 - $color;

        $edge = 0;
        $count = 0.0;
        for ($i = 0; $i < 8; $i++) {
            $dir = $dir_set[$i];
            $xt = $x;
            $yt = $y;
            $step = 0;
            $last_count = $count;
            $count = 0.0;

            if (++$edge == 3) {
                $edge = 1;
            }

//            echo "Edge" . $edge . "\n";
            while ($step < 4) {
                $xt += $dir[0];
                $yt += $dir[1];
                if ($xt > 18 or $xt < 0 or $yt > 18 or $yt < 0) {
                    break;
                }

                if ($chessboard[$xt][$yt] == $DianziGo) {
                    $count += 1;
                } else {
                    $count += $chessboard[$xt][$yt] == $player ? 0 : 0.5;
                    break;
                }

                $step++;
            }

//            echo "常规：" . strval($count) . " 方向：" . $i .  "\n";

            if ($count < 1) {
                continue;
            }

            //  剔除无用步
            $j = $i + ($i % 2) * -2 + 1;
            $need = 5 - intval($count) - 1;
            $s_step = 0;
            $xtt = $x;
            $ytt = $y;

            while ($s_step < 4) {
                $xtt += $dir_set[$j][0];
                $ytt += $dir_set[$j][1];
                if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $player) {
                    break;
                }

                $s_step++;
            }

            //echo "need：" . $need . " step：" .$s_step . "\n";
            if ($s_step >= $need) {
//                echo "useful\n";
                if (array_key_exists(strval($count), $dir_value)) {
                    $dir_value[strval($count)]++;
                } else {
                    $dir_value[strval($count)] = 1;
                }
            }

            //  连接
            if ($edge == 2 and $last_count > 0.5 and $count > 0.5) {
                //  无用连接检测
                $need = 5 - intval($count) - intval($last_count) - 1;
                $have = 0;
                $useful = $need <= 0;
                $j = $i + ($i % 2) * -2 + 1;

                $s_step = intval($count);
                $xtt = $x + $dir_set[$i][0] * $s_step;
                $ytt = $y + $dir_set[$i][1] * $s_step;
                while ($s_step < 5 and !$useful) {
                    $xtt += $dir_set[$i][0];
                    $ytt += $dir_set[$i][1];
                    if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $player) {
                        break;
                    }

                    $have++;
                    if ($have > $need) {
                        $useful = true;
                        break;
                    }

                    $s_step++;
                }

                $s_step = intval($last_count);
                $xtt = $x + $dir_set[$j][0] * $s_step;
                $ytt = $y + $dir_set[$j][1] * $s_step;
                while ($s_step < 5 and !$useful) {
                    $xtt += $dir_set[$j][0];
                    $ytt += $dir_set[$j][1];
                    if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0 or $chessboard[$xtt][$ytt] == $player) {
                        break;
                    }

                    $have++;
                    if ($have > $need) {
                        $useful = true;
                        break;
                    }

                    $s_step++;
                }

                if ($useful) {
                    $edge_count = $last_count + $count - 0.5;

//                try {
//                    file_get_contents("http://localhost/log/" . strval(99) . '/' .$x . '/' . $y . '/' . $i);
//                } catch (Exception $exception) {
//
//                }
                    if ($edge_count > 4.5) {
                        $edge_count = 4.5;
                    }

//                    echo "Link count:" . $edge_count . " Link " . $i . " last:" . $last_count . " c:" . $count ."\n";

                    if (array_key_exists(strval($edge_count), $dir_value)) {
                        $dir_value[strval($edge_count)]++;
                    } else {
                        $dir_value[strval($edge_count)] = 1;
                    }
                }
            }
            //file_get_contents("http://localhost/log/" . $edge . '/' .$x . '/' . $y . '/' . $i);
        }

        $dir_value['0'] = 1;
        foreach ($dir_value as $count => $value) {
            //  加特技
            if (array_key_exists(strval($count), self::$art)) {
                foreach (self::$art[strval($count)] as $_art) {
                    $need = 0;
                    for ($j = 0; $j < 8; $j++) {
                        $xtt = $x;
                        $ytt = $y;
                        $s_step = 0;
                        $blank = 0;
                        $is_art = true;

                        while ($s_step < $_art[0]) {
                            $xtt += $dir_set[$j][0];
                            $ytt += $dir_set[$j][1];
                            if ($xtt > 18 or $xtt < 0 or $ytt > 18 or $ytt < 0) {
                                $is_art = false;
                                break;
                            }

                            if ($s_step != $_art[0] - 1 and $chessboard[$xtt][$ytt] == 0) {
                                $blank += 1;
                                if ($blank > $_art[1]) {
                                    $is_art = false;
                                    break;
                                }
                            }

                            if ($s_step != $_art[0] - 1 and $chessboard[$xtt][$ytt] == $player) {
                                $is_art = false;
                                break;
                            }

                            if ($s_step == $_art[0] - 1) {
                                if ($chessboard[$xtt][$ytt] != 0 and $_art[2] == 1) {
                                    $is_art = false;
                                    break;
                                }
                            }

                            $s_step++;
                        }

                        if ($is_art) {
//                            echo "happened art!\n";
                            //var_dump($_art);
                            $need++;
                            if ($need - $_art[5] == $_art[3]) {
//                                echo "Begin art\n";
                                if (array_key_exists(strval($_art[4]), $dir_value)) {
                                    $dir_value[strval($_art[4])]++;
                                } else {
                                    $dir_value[strval($_art[4])] = 1;
                                }

                                break;
                            }
                        }
                    }
                }
            }
        }

        $score = 0.0;
        foreach (self::$attackRule as $rule) {
            if (array_key_exists(strval($rule[0]), $dir_value)) {
                $s = $dir_value[strval($rule[0])] * $rule[1];
                $score = $s > $score ? $s : $score;
            }
        }

        return $score;
    }
}


//echo "AttackValue：" . Gobang::attackCalculator(4, 4, $map, 2) . "\n";
//echo "DefendValue：" . Gobang::defendCalculator(4, 4, $map, 2) . "\n";
