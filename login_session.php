<?php
include 'mysqlConn.php';
header('Access-Control-Allow-Origin: *');
session_start();
$request_key =$_POST['request_key'];

if($request_key==="isLogin"){
    if(!isset($_SESSION['user_id'])){
        echo json_encode([
            "message"=> "로그인이 필요한 기능입니다.",
            "result" => "fail"
        ]);
        return;
    }else{

        echo json_encode([
            "user_id" => $_SESSION['user_id'],
            "user_name" => $_SESSION['user_name'],
            "user_type" => $_SESSION['user_type'],
            "my_trainer" => $_SESSION['my_trainer'],//일반회원일 경우에만 들어감
            "user_image" => $_SESSION['user_image'],
            "initial_setting" => $_SESSION['initial_setting'],
            "result" => "ok"
        ]);
    }
}
?>