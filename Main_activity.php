<?php

include 'mysqlConn.php';
session_start();
//jsonObject 형태로 서버에서 왔음
$json_data = json_decode($_POST['data'],true);

$request_key = $json_data['request_key'];

if($request_key ==="schedule"){

    $chooes_day = $json_data['today'];
    $user_id = $json_data['user_id'];

    if (isset($_SESSION['user_id'])){
        $user_id=$_SESSION['user_id'];
    }
    //스케쥴찾는 쿼리

    $query = " SELECT * FROM `schedule` WHERE `trainer_id`  LIKE '$user_id' AND `day` LIKE '$chooes_day';";
    $result = $db->query($query);

    $json = array();

    if($rows=mysqli_num_rows($result)){
        while($row=mysqli_fetch_assoc($result)){
            $json['data'][]=$row;
    }
        echo json_encode([
            "data" => json_encode($json),
            "result" => "schedule_ok"
        ]);
        
    }else{
        echo json_encode([
            "error"=> mysqli_error($db).$query,
            "data" => "일정이 없습니다",
            "result" => "schedule_empty"
            ]);
    }

    
}else if($request_key==="schedule_delete"){
    $index = $json_data['index'];

    $query = "DELETE FROM schedule WHERE num='$index'";
    if($db->query($query)){
        echo json_encode([
            "data" => "삭제되었습니다",
            "result" => "schedule_delete_ok"
        ]);
    }else{
        echo json_encode([
            "data" => "다시시도 해주세요",
            "result" => "schedule_delete_fail"
        ]);
    }
}else if($request_key==="book"){

    $user_id = $json_data['user_id'];
    $user_name = $json_data['user_name'];
    $user_image = $_POST['userImage'];
    $index = $json_data['index'];

     //일반회원이 pt예약하는 쿼리
     $query = "UPDATE schedule SET customer_id ='$user_id',  customer_name='$user_name', customer_image='$user_image' WHERE num = '$index'";
     $result = $db->query($query);

     if($result){
        echo json_encode([
            "data" => "예약되었습니다",
            "customer_id" => $user_id,
            "customer_name" => $user_name,
            "customer_image" => $user_image,
            "result" => "book_ok"
        ]);
     }else{
        echo json_encode([
            "data" => "예약 실패, 다시시도해주세요.",
            "result" => "book_fail"
        ]);
     }
}else if($request_key ==="book_cancel"){
    $index = $json_data['index'];

    $query ="UPDATE schedule SET customer_id =null,  customer_name=null, customer_image=null where num ='$index';";
    if($db->query($query)){
        echo json_encode([
            "data" => "예약이 취소 되었습니다",
            "result" => "book_cancel_ok"
        ]);
    }else{
        echo json_encode([
            "data" => "다시시도해주세요.".mysqli_error($db),
            "result" => "book_cancel_fail"
        ]);
    }
}else if($request_key === "member"){
    $user_id = $json_data['user_id'];
    $user_type = $json_data['user_type'];

    if($user_type==="general"){
        //일반회원일때
        $query = "SELECT my_trainer FROM user_general WHERE id ='$user_id'";
        $result = $db->query($query);
    
        if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_assoc($result);
            
            $member =$row['member'];

            echo json_encode([
                "member" => "$member",
                "result" => "member_customer_ok"
            ]);
            
        }else {
            
            echo json_encode([
                "log" => $query.mysqli_error($db),
                "message" => "다시시도해 주세요",
                "result" => "member_customer_fail"
            ]);
            
        }
    }else{
        //트레이너일때
        $query = "SELECT member FROM user_trainer WHERE id ='$user_id'";
        $result = $db->query($query);
        
        if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_assoc($result);
              
            $member =$row['member'];
    
            echo json_encode([
                "member" => "$member",
                "result" => "member_ok"
            ]);
            
        }else {
            
            echo json_encode([
                "log" => $query.mysqli_error($db),
                "message" => "다시시도해 주세요",
                "result" => "member_fail"
            ]);
            
        }
    }
    
}else if($request_key ==="modify_time"){
    $index = $json_data['index'];
    $start_time = $json_data['start_time'];
    $end_time = $json_data['end_time'];

    $query ="UPDATE schedule SET start_time ='$start_time', end_time ='$end_time' where num ='$index';";
    if($db->query($query)){
        echo json_encode([
            "data" => "시간이 변경 되었습니다",
            "result" => "modify_time_ok"
        ]);
    }else{
        echo json_encode([
            "log" => "".$query.mysqli_error($db),
            "data" => "다시시도해주세요.",
            "result" => "modify_time_fail"
        ]);
    }

}
else {
    echo json_encode([
        "data" => " 다시시도해주세요.".$request_key,
        "result" => "fail"
    ]);
}


?>