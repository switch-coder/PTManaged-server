<?php
include 'mysqlConn.php';
session_start();
$json_data = json_decode($_POST['data'],true);

$user_id = $json_data['user_id'];
$request_key = $json_data['request_key'];
$my_type = $json_data['my_type'];

if (isset($_SESSION['user_id'])){
    $user_id=$_SESSION['user_id'];
}
if($request_key ==="search"){//회원 검색

 
    if($my_type === 'trainer'){

        //손님회원 찾는 쿼리
        $query = "SELECT * FROM user_general WHERE id ='$user_id'";
        $result = $db->query($query);
    
        if(mysqli_num_rows($result)==1){
            $row=mysqli_fetch_assoc($result);
          
            $user_name =$row['name'];
            $user_image =$row['profile'];
    
            echo json_encode([
                "user_name" => "$user_name",
                "user_id" => "$user_id",
                "user_image" => "$user_image",
                "result" => "ok"
            ]);
            
        }else{
            echo json_encode([
                "message" => "회원이 없습니다".$user_id,
                "result" => "empty"
            ]);
        }
    }
}else if($request_key === 'add_user'){//회원 추가
    $my_id = $json_data['my_id'];

    if($my_type === 'trainer'){
        //추가하는 사람이 트레이너일 경우
        $query = "update user_trainer SET member =concat(member,'$user_id/') WHERE id='$my_id';";
        $query .="update user_general SET my_trainer ='$my_id' WHERE id='$user_id';";

        if( $db->multi_query($query) === true){
            echo json_encode([
                "message" => "추가 되었습니다",
                "result" => "add_ok"
            ]);
        }else{
            echo json_encode([
                "message" => "실패",
                "result" => "fail"
            ]);
        }
    }
    
}else if($request_key === 'user_list'){

    //회원 리스트 찾는 쿼리
    $query = "SELECT * FROM user_general WHERE my_trainer ='$user_id'";
    $result = $db->query($query);

    $json = array();
    if($rows=mysqli_num_rows($result)){
        
        while($row=mysqli_fetch_assoc($result)){
            $json['data'][]=$row;
        }

        echo json_encode([
            "data" => json_encode($json),
            "result" => "list_ok"
        ]);
        
    }else{
        echo json_encode([
            "log" => mysqli_error($db).$query,
            "data" => "회원이 없습니다",
            "result" => "list_fail"
            ]);
    }

}else if($request_key === 'chat_list'){
    $query = "SELECT * FROM user_trainer WHERE id ='$user_id'";
    $result = $db->query($query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);
      
        $user_name =$row['name'];
        $user_image =$row['profile'];

        echo json_encode([
            "name" => "$user_name",
            "image" => "$user_image",
            "result" => "list_ok"
        ]);
        
    }else{
        echo json_encode([
            "message" => "회원이 없습니다".$user_id,
            "result" => "empty"
        ]);
    }
}else if($request_key === 'load'){
    //스케쥴찾는 쿼리
    $room_name = $json_data['room_name'];
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
}else if($request_key==="user_detail"){
    $customer_id = $json_data['customer_id'];

    $query = "SELECT * FROM user_general WHERE id ='$customer_id';";
    $result = $db->query($query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);
        $workout_exp = $row['workout_exp'];
        $uniqueness = $row['uniqueness'];
        $job = $row['job'];
        $age = $row['age'];
        $stature = $row['stature'];
        $user_image =$row['user_image'];
        $weight = $row['weight'];
        $sex = $row['sex'];

        echo json_encode([
            "workout_exp"=>$workout_exp,
            "uniqueness"=>$uniqueness,
            "job"=>$job,
            "age"=>$age,
            "stature"=>$stature,
            "weight"=>$weight,
            "sex"=>$sex,
            "user_image"=>$user_image,
            "result" => "user_detail_ok"
        ]);

    }
}
else{
    echo json_encode([
        "message" => "$request_key",
        "result" => "fail"
    ]);
}

?>