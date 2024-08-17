<?php
session_start();

if (!isset($_SESSION['time'])) {
    $_SESSION['time'] = time();
} else {
    if (time() - $_SESSION['time'] < 60) {
        echo "429";
        exit();
    }
}

shell_exec("python saver.py");
echo "200";
