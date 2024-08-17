<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>储子轩博客存档</title>
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f5f5f5 !important;
        }

        .main {
            max-width: 950px;
            padding: 19px 29px 15px;
            margin: 0 auto 20px;
            background-color: #fff;
            border: 1px solid #e5e5e5;
        }

        .list {
            background-color: rgb(255, 255, 255);
            padding: 10px;
            height: 65%;
            line-height: 9px;
        }
    </style>
    <link href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/css/bootstrap.min.css" type="text/css" rel="stylesheet" />
    <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/bootstrap/5.1.3/js/bootstrap.bundle.min.js" type="application/javascript"></script>
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css"> -->
</head>

<body>
    <div id="spinner"></div>
    <div class="container">
        <div class="main rounded">
            <h3>储子轩博客存档</h3>
            <p>名人名言自动收录，点击链接打开。</p>
            <div class="list">
                <?php
                $path = './web';
                $res = scandir($path);
                foreach ($res as $folder) {
                    if ($folder == "." || $folder == "..") // 排除特殊文件夹
                        continue;
                    $madeTimeArr = str_split($folder, 2);
                    $madeTime = $madeTimeArr[0] . $madeTimeArr[1] . "-" . $madeTimeArr[2] . "-" . $madeTimeArr[3] . " " . $madeTimeArr[4] . ":" . $madeTimeArr[5] . ":" . $madeTimeArr[6];
                    echo '<div class="card bg-light"><a href="/web/' . $folder . '"><div class="card-body">' . $madeTime . ' (UTC+8)</div></a></div><br>';
                }
                ?>
            </div>
            <p class="text-center" style="margin-top: 1rem;">
                <button type="button" class="btn btn-outline-primary" onclick="savepage()" data-bs-toggle="tooltip" title="刷新本页将不会停止任务">立即保存</button>
                <button type="button" class="btn btn-outline-primary"
                    onclick="window.open('mailto:yzj09@hotmail.com')" data-bs-toggle="tooltip" title="发送邮件给管理员">Report Error</button>
            </p>
            <!-- <p class="text-center" style="margin-bottom: 0;"><small>t.me/ymstu</small></p> -->
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js"></script>
    <script>
        function savepage() {
            // 加载图标
            var spinner = new Spinner({
                color: '#333'
            }).spin(document.getElementById('spinner'));
            // 发送请求
            var xhr = new XMLHttpRequest();
            xhr.open('get', '/save.php');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    spinner.stop();
                    if (xhr.responseText == "429") {
                        alert("请求速度过快！每次存档间隔至少60秒");
                    } else {
                        alert("存档完成，请刷新页面查看！");
                    }
                } else if (xhr.status != 200) {
                    spinner.stop();
                    alert("存档失败！(code:" + xhr.status + ")");
                }
            }
            xhr.send();
        }
        // bootstrap 按钮悬浮提示框
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        })
    </script>
</body>

</html>