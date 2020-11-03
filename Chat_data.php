<?php 

include 'mysqlConn.php';

$json_data = json_decode($_POST['data'],true);
$request_key = $json_data['request_key'];


if($request_key === "save"){//회원 검색
    
    $chat_data = json_decode($_POST['chat_data'],true);
    $room_name = $json_data['room_name'];
    $query ='';
    foreach($chat_data as $value){
        $caller = $value['caller'];
        $message = $value['message'];
        $time = $value['time'];
        $query .= "insert into chat (caller, message, time, room_name) values ('$caller', '$message', '$time','$room_name');";
    };
    

    if( $db->multi_query($query) === true){
        echo json_encode([
            "data" => "업로드 되었습니다",
            "result" => "ok"
        ]);
        
    }else{
        echo json_encode([
            "error" => $chat_data.$query,
            "data" => "업로드에 실패했습니다 다시시도 해주세요",
            "result" =>"save_fail"
        ]);
    }

}else if($request_key === 'load'){

    $room_name = $json_data['room_name'];
    //대화내용 찾는 쿼리
    $query = "SELECT * FROM chat WHERE room_name ='$room_name'";
    $result = $db->query($query);

    $json = array();
    if($rows=mysqli_num_rows($result)){
        while($row=mysqli_fetch_assoc($result)){
            $json['data'][]=$row;
       }
        echo json_encode([
            "data" => json_encode($json),
            "result" => "chat_data_ok"
        ]);
        
    }else{
        echo json_encode([
            "data" => "채팅내용이 없습니다".$rows,
            "result" => "chat_data_empty"
            ]);
    }
}
else{
    echo json_encode([
        "data" => "$request_key",
        "result" => "fail"
    ]);
}

?>
