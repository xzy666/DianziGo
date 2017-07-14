/////////////////////////////////////////////////////
//      全局定义
/////////////////////////////////////////////////////
/* * 
 *  初始化参数
 */
//  游戏池
var gamepool = document.getElementById("gamepool");

//  Toolbar按钮
var giveupBtn = document.getElementById("giveup-btn");
var stepnumBtn = document.getElementById("stepnum-btn");
var restartBtn = document.getElementById("restart-btn");

//  游戏信息
var gameInf = {
    "run" : false,   //游戏运行状态
    "chessnum" : 0, //落子数
    "player" : 2,   //当前玩家，1白，2黑；执黑先行；
    "player_run" : false,
    "stepnum" : false,
    "is_challenge" : false,

    "version" : "1.5", //版本号
    "author" : "skysy", //作者
};

//  玩家信息
var playerInf = {
    "name" : "",
    "match" : "",
    "chess" : 0,
};

//  棋盘;初始化
var chessBoard = new Array();
for (var i = 0; i < 19; i++) {
    chessBoard[i] = new Array();
    for (var j = 0; j < 19; j++) {
        chessBoard[i][j] = 0;
    }
}

//  计时器
var timer = {
    "run" : false,
    "black_s" : 0,
    "white_s" : 0,
    "wait_s" : 0,
};

//  获取时间与总步数Span
var atimeSpan = document.getElementById("atime");
var astepSpan = document.getElementById("astep");
var wtimeSpan = document.getElementById("wtime");
var btimeSpan = document.getElementById("btime");
var waitSpan  = document.getElementById("waitCounter");

var playerNameInput = document.getElementById("player_name");
var beginChallengeDiv = document.getElementsByClassName("begin-challenge")[0];
var switchFrontDiv = document.getElementsByClassName("switch-front")[0];
var informDiv = document.getElementsByClassName("inform")[0];

/* *
 *  全局事件注册
 */
//  鼠标点击事件注册
document.onclick = function getMousePos(event) {
    var e = event || window.event;

    // 注册左键事件
    if (e.which == 1) {
        //  游戏池Left值
        var gamepoolX = (document.body.clientWidth - 1000) / 2 + 50;
        if (document.body.clientWidth < 1000) {
            gamepoolX = 50;
        }
        console.log("clientWidth:" + document.body.clientWidth);
        //  游戏池Top值
        var gamepoolY = 130;

        //  判断点击事件是否落在游戏池内
        if (e.pageX > gamepoolX - 15 && e.pageX < gamepoolX + 555
            && e.pageY > gamepoolY - 15 && e.pageY < gamepoolY + 555) {

            var x = e.pageX - gamepoolX;
            var y = e.pageY - gamepoolY;
            var latticeDir = ClickToLattice(x, y);
            if (gameInf["player_run"]) {
                if (PressChess(latticeDir['x'], latticeDir['y'])) {
                    gameInf["player_run"] = false;
            }
            }
        }
    }
}

//  注册页面关闭事件
window.onbeforeunload = function() {
    // console.log("666");
    // $.ajax({
    //     url : ""
    // });
    return "刷新页面，游戏将重新开始";
}

//  注册计时器
setInterval(RunTimer, 1000);

/////////////////////////////////////////////////////
//      方法定义
/////////////////////////////////////////////////////

/* *
 *  @Method
 *  开始游戏
 */
function BeginGame() {
    //  加框特效
    var player = document.getElementsByClassName("black-player")[0];
    var player_black = document.getElementById("bpinf");
    var player_white = document.getElementById("wpinf");
    player.style.border = "2px solid white";

    if (playerInf["chess"] == 1) {
        player_white.innerHTML = playerInf["name"];
        if (gameInf["is_challenge"]) {
            player_black.innerHTML = "DianziGo V1.0";

            $.ajax({
                url : "/data/gobang/sync/player",
                type : "POST",
                data : {
                    "challenge_id" : $.cookie("challenge_id"),
                    "step" : gameInf["chessnum"],
                    "chessboard" : JSON.stringify(chessBoard),
                    "x" : 0,
                    "y" : 0
                },
                success : function (res) {
                    if (res['status'] < 0) {
                        alert(res['inf']);
                    }
                }
            });
        } else {
            player_black.innerHTML = playerInf["match"];
        }
    } else {
        if (gameInf["is_challenge"]) {
            player_white.innerHTML = "DianziGo V1.0";
        } else {
            player_white.innerHTML = playerInf["match"];
        }
        player_black.innerHTML = playerInf["name"];

        gameInf["player_run"] = true;
    }

    gameInf["run"] = true;
    timer["run"] = true;
    setInterval(RunTimer, 1000);
    //Toolbar更新
    giveupBtn.style.display = "block";
    stepnumBtn.style.display = "block";
}

/* *
 *  @Method
 *  计时器回显
 */
function DisplayTimer() {
    //  显示总时间
    var allTime = timer["white_s"] + timer["black_s"];
    var s = allTime % 60;
    var m = parseInt(allTime / 60);
    var h = parseInt(m / 60);
    m %= 60;

    var timeStr = "";
    timeStr += h < 10 ? "0" + h : h;
    timeStr += ":"
    timeStr += m < 10 ? "0" + m : m;
    timeStr += ":"
    timeStr += s < 10 ? "0" + s : s;

    atimeSpan.innerHTML = timeStr;

    //  显示白方时间
    s = timer["white_s"] % 60;
    m = parseInt(timer["white_s"] / 60);
    h = parseInt(m / 60);
    m %= 60;

    timeStr = "";
    timeStr += h < 10 ? "0" + h : h;
    timeStr += ":"
    timeStr += m < 10 ? "0" + m : m;
    timeStr += ":"
    timeStr += s < 10 ? "0" + s : s;

    wtimeSpan.innerHTML = timeStr;

    //  显示黑方时间
    s = timer["black_s"] % 60;
    m = parseInt(timer["black_s"] / 60);
    h = parseInt(m / 60);
    m %= 60;

    timeStr = "";
    timeStr += h < 10 ? "0" + h : h;
    timeStr += ":"
    timeStr += m < 10 ? "0" + m : m;
    timeStr += ":"
    timeStr += s < 10 ? "0" + s : s;

    btimeSpan.innerHTML = timeStr;
}

/* *
 *  @Method
 *  计时器运行
 */
function RunTimer() {
    if (!timer["run"]) {
        return;
    }

    if (!gameInf["player_run"]) {
        if (gameInf["is_challenge"]) {
            $.ajax({
                url : "/data/gobang/sync/DianziGo",
                type : "GET",
                data : {
                    "challenge_id" : $.cookie("challenge_id"),
                    "step" : gameInf["chessnum"] + 1
                },
                success : function (res) {
                    if (res['status'] >= 0) {
                        PressChess(res['x'], res['y']);
                        gameInf["player_run"] = true;
                    }
                }
            });
        } else {
            $.ajax({
                url : "/data/gobang/sync/answer",
                type : "GET",
                data : {
                    "match_id" : $.cookie("match_id"),
                    "step" : gameInf["chessnum"] + 1
                },
                success : function (res) {
                    if (res['status'] >= 0) {
                        if (res['x'] == -1 && res['y'] == -1) {
                            alert('对方已认输');
                            Success(playerInf["chess"]);
                        }

                        PressChess(res['x'], res['y']);
                        gameInf["player_run"] = true;
                    }
                }
            });
        }
    }

    if (gameInf["player"] == 1) {
        timer["white_s"]++;
    } else if (gameInf["player"] == 2) {
        timer["black_s"]++;
    }

    DisplayTimer();
}

/* *
 *  @Method
 *  关闭胜利特效
 */
function CloseSuccessEffect() {
    var obj = document.getElementsByClassName("success-effect")[0];
    obj.style.opacity = 0;
}

/* *
 *  @Method
 *  胜局检测
 */
function IsSuccess(x, y, player) {
    var xl = x - 4 < 0 ? 0 : x - 4;
    var xr = x + 5 > 18 ? 18 : x + 5;
    var yl = y - 4 < 0 ? 0 : y - 4;
    var yr = y + 5 > 18 ? 18 : y + 5;
    var count = 0;

    //  横向检测
    count = 0;
    for (var i = x + 1; i <= xr; i++) {
        if (chessBoard[i][y] == player) {
            count++;
        } else {
            break;
        }
    }

    for (var i = x - 1; i >= xl; i--) {
        if (chessBoard[i][y] == player) {
            count++;
        } else {
            break;
        }
    }

    if (++count >= 5) {
        return true;
    }

    //  纵向检测
    count = 0;
    for (var i = y + 1; i <= yr; i++) {
        if (chessBoard[x][i] == player) {
            count++;
        } else {
            break;
        }
    }

    for (var i = y - 1; i >= yl; i--) {
        if (chessBoard[x][i] == player) {
            count++;
        } else {
            break;
        }
    }

    if (++count >= 5) {
        return true;
    }

    //  正斜向检测
    count = 0;
    for (var i = x + 1, j = y + 1; i <= xr && j <= yr; i++, j++) {
        if (chessBoard[i][j] == player) {
            count++;
        } else {
            break;
        }
    }

    for (var i = x - 1, j = y - 1; i >= xl && j >= yl; i--, j--) {
        if (chessBoard[i][j] == player) {
            count++;
        } else {
            break;
        }
    }

    if (++count >= 5) {
        return true;
    }

    //  反斜向检测
    count = 0;

    for (var i = x + 1, j = y - 1; i <= xr && j >= yl; i++, j--) {
        if (chessBoard[i][j] == player) {
            count++;
        } else {
            break;
        }
    }

    for (var i = x - 1, j = y + 1; i >= xl && j <= yr; i--, j++) {
        if (chessBoard[i][j] == player) {
            count++;
        } else {
            break;
        }
    }

    if (++count >= 5) {
        return true;
    }

    return false;
}

/* *
 *  @Method
 *  落子处理
 */
function PressChess(x, y)
{
    //  判断游戏是否正在运行
    if (!gameInf["run"]) {
        return false;
    }

    //  判断是否可落子
    if (chessBoard[x][y] != 0) {
        return false;
    }

    //  判断当前执棋方
    if (gameInf["player"] == 1) {
        DrawNewChess(x, y, "white");
        chessBoard[x][y] = 1;
    } else if (gameInf["player"] == 2) {
        DrawNewChess(x, y, "black");
        chessBoard[x][y] = 2;
    }

    //  如果是玩家落子
    if (gameInf['player_run']) {
        if (gameInf["is_challenge"]) {
            $.ajax({
                url : "/data/gobang/sync/player",
                type : "POST",
                data : {
                    "challenge_id" : $.cookie("challenge_id"),
                    "step" : gameInf["chessnum"],
                    "chessboard" : JSON.stringify(chessBoard),
                    "x" : x,
                    "y" : y
                },
                success : function (res) {
                    if (res['status'] < 0) {
                        alert(res['inf']);
                    }
                }
            });
        } else {
            $.ajax({
                url : "/data/gobang/sync/press",
                type : "POST",
                data : {
                    "match_id" : $.cookie("match_id"),
                    "step" : gameInf["chessnum"],
                    "chessboard" : JSON.stringify(chessBoard),
                    "x" : x,
                    "y" : y
                },
                success : function (res) {
                    if (res['status'] < 0) {
                        alert(res['inf']);
                    }
                }
            });
        }

        if (playerInf["chess"] == 2) {
            timer["black_s"] += 1;
        } else {
            timer["white_s"] += 1;
        }
    }

    if (IsSuccess(x, y, gameInf["player"])) {
        Success(gameInf["player"]);
    }

    //  执棋方翻转
    gameInf["player"] = 3 - gameInf["player"];
    return true;
}

/* *
 *  @Method
 *  胜利事件
 */
function Success(suc) {
    var successEffect = document.getElementsByClassName("success-effect")[0];
    alert((suc == 1 ? "白方" : "黑方") + "胜");
    gameInf["run"] = false;
    timer["run"] = false;

    StepnumDisplay("block");

    if (suc != playerInf["chess"]) {
        successEffect.style.opacity = 50;
    } else {
        $.ajax({
            url : '/data/gobang/success/' + $.cookie("match_id"),
            type : 'GET',
        });
    }

    //Toolbar更新
    giveupBtn.style.display = "none";
    stepnumBtn.style.display = "none";
    restartBtn.style.display = "block";

    console.log(JSON.stringify(chessBoard));
}

/* *
 *  @Method
 *  显示步数
 */
function StepnumDisplay(op) {
    var chessCounters = document.getElementsByClassName("chess-counter");
    for (var key in chessCounters) {
        if (!isNaN(key)) {
            chessCounters[key].style.display = op;
        }
    }
}

/* *
 *  @Method
 *  Giveup-btn点击事件
 */
function Giveup() {
    Success(3 - playerInf["chess"]);

    if (!gameInf["is_challenge"]) {
        $.ajax({
            url : "/data/gobang/sync/press",
            type : "POST",
            data : {
                "match_id" : $.cookie("match_id"),
                "step" : gameInf["chessnum"],
                "chessboard" : JSON.stringify(chessBoard),
                "x" : -1,
                "y" : -1
            },
            success : function (res) {
                if (res['status'] < 0) {
                    alert(res['inf']);
                }
            }
        });
    }
}

/* *
 *  @Method
 *  Restart-btn点击事件
 */
function RestartBtn() {
    //  清空棋盘
    for (var i = 0; i < 19; i++) {
        for (var j = 0; j < 19; j++) {
            chessBoard[i][j] = 0;
        }
    }

    var chesses = gamepool.getElementsByTagName("div");
    for (var i = 0; i < gameInf['chessnum']; i++) {
        console.log("emmm");
        gamepool.removeChild(chesses[0]);
    }

    //  清空计时器
    timer['white_s'] = 0;
    timer['black_s'] = 0;

    //  重置游戏信息
    gameInf['player'] = 2;
    gameInf['chessnum'] = 0;
    gameInf['stepnum'] = false;
    gameInf['player_run'] = false;

    playerInf['match'] = '';

    //  更新Toolbar
    restartBtn.style.display = "none";

    BeginChallenge(gameInf["is_challenge"]);
}

/* *
 *  @Method
 *  Stepnum-btn点击事件
 */
function ClickStepnumBtn() {
    if (gameInf["stepnum"]) {
        StepnumDisplay("none");
        gameInf["stepnum"] = false;
    } else {
        StepnumDisplay("block");
        gameInf["stepnum"] = true;
    }
}

/* *
 *  @Method
 *  新棋子绘制
 */
function DrawNewChess(x, y, color) {
    var pxDir = LatticeToPx(x, y);
    var newChess = document.createElement("div");
    //  设置棋子
    newChess.className = color + "chess";

    //  创建棋子计数器
    var chessCounter = document.createElement("div");
    chessCounter.innerHTML = ++gameInf["chessnum"]
    chessCounter.className = "chess-counter";
    if (gameInf["stepnum"]) {
        chessCounter.style.display = "block";
    }

    //  设置棋子位置
    newChess.style.top = pxDir['y'] + "px";
    newChess.style.marginTop = "-15px";
    newChess.style.left = pxDir['x'] + "px";
    newChess.style.marginLeft = "-15px";
    //  选择特效
    newChess.style.border = "3px solid red";
    newChess.appendChild(chessCounter);


    //  设置棋子信息
    newChess.id = "chess" + gameInf["chessnum"];

    //  总步数显示
    astepSpan.innerHTML = gameInf["chessnum"];

    if (color == "white") {
        var nextColor = "black";
    } else {
        var nextColor = "white";
    }

    var player = document.getElementsByClassName(color + "-player")[0];
    var nextPlayer = document.getElementsByClassName(nextColor + "-player")[0];
    player.style.border = "none";
    nextPlayer.style.border = "2px solid white";

    //  取消上个棋子选择特效
    if (gameInf["chessnum"] != 1) {
        var lastBtn = document.getElementById("chess" + (gameInf["chessnum"] - 1));
        lastBtn.style.border = "none";
    }

    gamepool.appendChild(newChess);
}

/* *
 *  @Method
 *  点阵坐标 转 像素坐标
 */
function LatticeToPx(x, y) {
    //  处理X坐标
    var pxX = 30 * x;
    //  处理Y坐标
    var pxY = 30 * y;

    return {"x" : pxX, "y" : pxY};
}

/* *
 *  @Method
 *  点击坐标 转 点阵坐标
 */
function ClickToLattice(x, y) {
    var xpos = 0;
    var ypos = 0;

    //  处理X坐标
    if (x < 0 && x > -15) {
        xpos = 0;
    } else if (x > 540 && x < 555) {
        xpos = 18;
    } else {
        xpos = parseInt((x + 15) / 30);
    }

    //  处理Y坐标
    if (y < 0 && y > -15) {
        ypos = 0;
    } else if (y > 540 && y < 555) {
        ypos = 18;
    } else {
        ypos = parseInt((y + 15) / 30);
    }

    return {"x" : xpos, "y" : ypos};
}

/* *
 *  @Method
 *  开始挑战
 */
function BeginChallenge(isChallenge) {
    playerInf["name"] = playerNameInput.value;
    gameInf["is_challenge"] = isChallenge;

    let url = "/data/gobang/create/";
    if (!gameInf["is_challenge"]) {
        url = "/data/gobang/match/";
    }

    $.ajax({
        url : url + playerInf["name"],
        type : "GET",
        async : false,
        success: function (res) {
            if (res["status"] < 0) {
                alert(res["inf"]);
            } else {
                if (gameInf["is_challenge"]) {
                    //console.log("1111");
                    $.cookie("challenge_id", res["challenge_id"]);
                    beginChallengeDiv.style.display = "none";
                    //console.log(switchFrontDiv);
                    switchFrontDiv.style.display = "block";
                } else {
                    if (res["status"] == 1) {
                        $.cookie("match_id", res["match_id"]);
                        playerInf["match"] = res["match"];
                        beginChallengeDiv.style.display = "none";
                        switchFrontDiv.style.display = "block";
                    } else {
                        beginChallengeDiv.style.display = "none";
                        let informText = informDiv.getElementsByTagName("span")[0];
                        informText.innerText = "正在为您匹配对手.";
                        informDiv.style.display = "block";
                        timer["wait_s"] = 0;
                        var waitCounter = setInterval(function () {
                            timer["wait_s"]++;
                            waitSpan.innerText = timer["wait_s"];

                            $.ajax({
                                url : "/data/gobang/match/" + playerInf["name"],
                                type : "GET",
                                async : false,
                                success : function (res) {
                                    if (res["status"] == 1) {
                                        clearInterval(waitCounter);
                                        $.cookie("match_id", res["match_id"]);
                                        playerInf["match"] = res["match"];
                                        informDiv.style.display = "none";
                                        switchFrontDiv.style.display = "block";
                                    }
                                }
                            });
                        }, 1000);
                    }
                }
            }
        }
    });
}

/* *
 *  @Method
 *  猜先
 */
function SwitchFront(swi) {
    let arg = '';
    if (gameInf["is_challenge"]) {
        arg = "?challenge_id=" + $.cookie('challenge_id');
    } else {
        arg = "?match_id=" + $.cookie('match_id') + "&player=" + playerInf["name"];
    }

    $.ajax({
        url : "/data/gobang/switch/" + swi + arg,
        type : 'GET',
        async : false,
        success: function (res) {
            if (res["status"] < 0) {
                alert(res["inf"]);
            } else {
                if (gameInf["is_challenge"]) {
                    playerInf["chess"] = res["player_chess"];
                    switchFrontDiv.style.display = "none";
                    setTimeout(BeginGame, 1000);
                } else {
                    switchFrontDiv.style.display = "none";
                    let informText = informDiv.getElementsByTagName("span")[0];
                    informText.innerText = "等待对方选择.";
                    informDiv.style.display = "block";
                    timer["wait_s"] = 0;

                    var waitCounter = setInterval(function () {
                        timer["wait_s"]++;
                        waitSpan.innerText = timer["wait_s"];

                        $.ajax({
                            url : "/data/gobang/switchAns/" + $.cookie('match_id') + "/" + playerInf["name"],
                            type : "GET",
                            async : false,
                            success : function (res) {
                                if (res["status"] == 0) {
                                    clearInterval(waitCounter);
                                    playerInf["chess"] = res["player_chess"];
                                    informDiv.style.display = "none";
                                    setTimeout(BeginGame, 1000);
                                }
                            }
                        });
                    }, 1000);
                }
            }
        }
    });
}

//BeginGame();