<?php

include 'mysqlConn.php';
session_start();
$json_date = json_decode($_POST['data'],true);

$select_days = $json_date['select_days'];
$start_time = $json_date["start_time"];
$end_time = $json_date["end_time"];
$user_id = $json_date['user_id'];

$days = explode('/',$select_days);
$days = array_filter($days);
$count = count($days);

if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}
$query ="";
for($i=0; $i<$count; $i++){
    $query .= "insert into schedule (trainer_id, day, start_time, end_time) values ('$user_id', '".$days[$i]."', '$start_time','$end_time');";
}

if( $db->multi_query($query) === true){
    echo json_encode([
        "message" => "업로드 되었습니다",
        "result" => "ok"
    ]);
    
}else{
    echo json_encode([
        "error" => $query.mysqli_error($db),
        "message" => "업로드에 실패했습니다 다시시도 해주세요",
        "result" =>"fail"
    ]);
}
?>