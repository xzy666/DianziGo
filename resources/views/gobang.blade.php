<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> 五子棋 </title>

    <link rel="stylesheet" type="text/css" href="./css/gobang.css">
</head>
<body>
<main>
    <div class="gameheader">
        <strong>十九道五子棋</strong>
        <br>
        <div class="begintime"><span ><b>开始时间：</b></span> <span id="atime">00:00:00</span></div>
        <div class="allchess"><span><b>总步数：</b></span><span id="astep">0</span><span>手</span></div>
    </div>
    <section class="gamepool">
        <div id="gamepool">
            <table class="chessboard">
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
                <tr>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                    <td class="chessblank"></td>
                </tr>
            </table>
        </div>
    </section>

    <section class="player">
        <div class="black-player">
            <div class="black-head"> </div>
            <div class="player-title"> 黑方 </div>
            <div class="player-inf"> <strong>用户：</strong><span id="bpinf">电子狗V1.0</span></div>
            <div class="player-time"><strong>已用时间：</strong><span id="btime">00:00:00</span></div>
        </div>
        <div class="white-player">
            <div class="white-head"> </div>
            <div class="player-title"> 白方 </div>
            <div class="player-inf"> <strong>用户：</strong><span id="wpinf">玩家</span></div>
            <div class="player-time"><strong>已用时间：</strong><span id="wtime">00:00:00</span></div>
        </div>
    </section>

    <div class="message begin-challenge">
        <span> 欢迎挑战，请输入姓名 </span>
        <input type="text" id="player_name">
        <button onclick="BeginChallenge(true);">人机</button>
        <button onclick="BeginChallenge(false);">匹配</button>
    </div>

    <div class="message inform">
        <span> 正在为您匹配对手 </span>
        <br><br><br>
        <span id="waitCounter"> 0 </span> <span> s... </span>
    </div>

    <div class="message switch-front">
        <span> 请猜先 </span>
        <div class="sf-btn-set">
            <button onclick="SwitchFront(-1);">让先</button>
            <button onclick="SwitchFront(0);">双数</button>
            <button onclick="SwitchFront(1);">单数</button>
        </div>
    </div>

    <div class="success-effect">
        <button id="close-success-effect" onclick="CloseSuccessEffect();">X</button>
        <img src="./img/success.jpg" alt="" style="height: 100%;width: 100%;">
    </div>

    <div class="tool-bar">
        <button id="restart-btn" onclick="RestartBtn();">再来</button>
        <button id="stepnum-btn" onclick="ClickStepnumBtn();">步数</button>
        <button id="giveup-btn" onclick="Giveup();">认输</button>
    </div>
</main>

<script type="text/javascript" src="./js/jQuery.js"></script>
<script type="text/javascript" src="./js/jQuery-cookie.js "></script>
<script type="text/javascript" src="./js/gobang.js"></script>
</body>
</html>