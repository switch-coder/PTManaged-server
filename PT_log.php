<?php
include 'mysqlConn.php';

$json_date = json_decode($_POST['data'],true);
$request_key =$json_date["request_key"];

if($request_key === 'schedule'){
    $customer_id = $json_date['customer_id'];
    $trainer_id = $json_date['trainer_id'];
    $today = $json_date['today'];

    //스케줄테이블에서 해당 회원이 PT 받은 날 찾는 쿼리
    $query = "SELECT * FROM schedule WHERE day <='$today' and trainer_id='$trainer_id' and customer_id ='$customer_id' and pt_log='0'";
    $result = $db->query($query);
   
    if($rows=mysqli_num_rows($result)){
        while($row=mysqli_fetch_assoc($result)){
            //jsonArray 형태로 받기
            $json['data'][]=$row;
        }
        echo json_encode([
            "data" => json_encode($json),
            "result" => "schedule_ok"
        ]);
        
    }else{
        echo json_encode([
            "data" => "완료한 PT가 없습니다",
            "result" => "schedule_empty"
            ]);
    }
    
}else if($request_key ==='pt_log_insert'){

    $muscle = $json_date['muscle'];
    $weight = $json_date['weight'];
    $fat_mass = $json_date['fat'];
    $uniqueness = $json_date['uniqueness'];
    $date = $json_date['date'];
    $trainer_id = $json_date['trainer_id'];
    $customer_id = $json_date['customer_id'];
    $routine = $_POST['routine'];
    $schedule_index = $_POST['schedule_index'];

    //pt내용 저장하는 쿼리
    $query = "INSERT INTO pt_log (trainer_id, customer_id, day, pt_routine, weight, muscle_mass, body_fat_mass, uniqueness) 
            VALUES ('$trainer_id', '$customer_id', '$date','$routine', '$weight', '$muscle', '$fat_mass', '$uniqueness');";
    $query .= "UPDATE schedule SET pt_log ='1' WHERE num='$schedule_index';";

    if($db->multi_query($query) === true){
        echo json_encode([
            "data" => "업로드 되었습니다",
            "result" => "pt_log_ok"
        ]);
    }else{
        echo json_encode([
            "data" => "업로드에 실패했습니다, 다시시도해주세요",
            "result" => "pt_log_fail"
        ]);
    }
}else if($request_key==='PT_log'){
    $customer_id =$json_date['customer_id'];

    //해당 회원의 PT_log 조회 쿼리
    $query="SELECT * FROM pt_log WHERE customer_id='$customer_id' ORDER BY `day` ASC";
    $result = $db->query($query);

    if($rows=mysqli_num_rows($result)){
        while($row=mysqli_fetch_assoc($result)){
            //jsonArray 형태로 받기
            $json['data'][]=$row;
        }

        echo json_encode([
            "data" => json_encode($json),
            "result" => "PT_log_ok"
        ]);
        
    }else{
        echo json_encode([
            "data" => "PT 일지가 없습니다",
            "result" => "PT_log_fail"
            ]);
    }
    

}else if($request_key ==="PT_log_detail"){
    $index =$json_date['index'];

    //해당 회원의 PT_log 조회 쿼리
    $query="SELECT * FROM pt_log WHERE index_='$index'";
    $result = $db->query($query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);
        $routine['pt_routine']=json_decode($row['pt_routine']);
        echo json_encode([
            "trainer_name" => $row['trainer_name'],
            "day" => $row['day'],
            "pt_routine" => json_encode($routine),
            "weight" => $row['weight'],
            "muscle_mass" => $row['muscle_mass'],
            "body_fat_mass" => $row['body_fat_mass'],
            "uniqueness" => $row['uniqueness'],
            "result" => "PT_log_ok"
        ]);
        
    }else{
        echo json_encode([
            "data" => "PT 일지가 없습니다",
            "result" => "PT_log_fail"
            ]);
    }
}
?>