<?php
include 'mysqlConn.php';
//어떤 작업할지 키값을 받아옴
$request_key = $_POST['request_key'];

//이미지 파일 경로
$dir = "./user_images";

if($request_key === "image_upload"){
    $image =$_POST['image'];
    $user_id = $_POST['user_id'];
    $input_introduce = $_POST['input_introduce'];
    $input_recode = $_POST['input_recode'];
    $input_place = $_POST['input_place'];

    if($image ===""){
        $query = "UPDATE user_trainer SET introduce ='$input_introduce', recode='$input_recode', place='$input_place', user_image='$dir' WHERE id='$user_id'";
        $result = $db->query($query);
        if($result){
            echo json_encode([
                "message" => "업로드 되었습니다",
                "result" => "ok"
            ]);
        }else{
            echo json_encode([
                "log" => "".$query.mysqli_error($db),
                "message" => "업로드에 실패했습니다 다시시도 해주세요",
                "result" =>"fail"
            ]);
        }
    }else{

        //경로 + 이미지 파일 이름
        $dir = $dir."/".rand()."_".time().".jpeg";
        if(!is_writable($dir)){
            $chmod = "notwrite";
        }

        if(file_put_contents($dir, base64_decode($image))){
           
            $dir =substr($dir,1);
            $query = "UPDATE user_trainer SET introduce ='$input_introduce', recode='$input_recode', place='$input_place', user_image='$dir' WHERE id='$user_id'";
            $result = $db->query($query);
            if($result){
                echo json_encode([
                    "message" => "업로드 되었습니다",
                    "result" => "ok"
                ]);
            }else{
                echo json_encode([
                    "log" => "".$query.mysqli_error($db),
                    "message" => "업로드에 실패했습니다 다시시도 해주세요",
                    "result" =>"fail"
                ]);
            }

        }else{
            echo json_encode([
                "log" => "".$query.mysqli_error($db),
                "message" => "업로드에 실패했습니다 다시시도 해주세요",
                "result" =>"fail"
            ]);
        }

    }

}else if($request_key === "setting"){
    $user_id =$_POST['user_id'];

    $query = "SELECT * FROM user_trainer WHERE id ='$user_id'";
    $result = $db->query($query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        $user_image =$row['user_image'];
        $introduce =$row['introduce'];
        $place = $row['place'];
        $recode = $row['recode'];

        echo json_encode([
            "user_image" => "$user_image",
            "introduce" => "$introduce",
            "place" => "$place",
            "recode" => "$recode",
            "result" => "ok"
        ]);

    }else{
        echo json_encode([
            "message" => "존재하지 않는 아이디 입니다",
            "result" =>"fail"
        ]);
    }
}else if($request_key ==="customer_initial_setting"){

    $image =$_POST['image'];
    $user_id = $_POST['user_id'];
    $workout_exp = $_POST['workout_exp'];
    $uniqueness = $_POST['uniqueness'];
    $job = $_POST['job'];
    $age = $_POST['age'];
    $stature = $_POST['stature'];
    $weight = $_POST['weight'];
    $sex = $_POST['sex'];
    //경로 + 이미지 파일 이름
    if($image ===""){
        $query = "UPDATE user_general SET workout_exp ='$workout_exp', uniqueness='$uniqueness', job='$job', 
        age='$age', stature='$stature', weight='$weight', sex='$sex' WHERE id='$user_id';";
        $query .= "UPDATE user SET initial_setting=1 WHERE id= '$user_id';";
        
        if($db->multi_query($query) === true){
            echo json_encode([
                "user_image" =>"/user_images/defaultUserImage.jpg",
                "message" => "업로드 되었습니다",
                "result" => "ok"
            ]);
        }else{
            echo json_encode([
                "log" => "".$query.mysqli_error($db),
                "message" => "업로드에 실패했습니다 다시시도 해주세요",
                "result" =>"fail"
            ]);
        }

    }else{
        $dir = $dir."/".rand()."_".time().".jpeg";
    if(!is_writable($dir)){
        $chmod = "notwrite";
    }

    if(file_put_contents($dir, base64_decode($image))){
      
        $dir =substr($dir,1);
        $query = "UPDATE user_general SET workout_exp ='$workout_exp', uniqueness='$uniqueness', job='$job',user_image='$dir', 
                    age='$age', stature='$stature', weight='$weight', sex='$sex' WHERE id='$user_id';";
        $query .= "UPDATE user SET initial_setting = 1 WHERE id='$user_id';";

        $result = $db->query($query);
        if($result){
            echo json_encode([
                "user_image" =>"$dir",
                "message" => "업로드 되었습니다",
                "result" => "ok"
            ]);
        }else{
            echo json_encode([
                "log" => "".$query.mysqli_error($db),
                "message" => "업로드에 실패했습니다 다시시도 해주세요",
                "result" =>"fail"
            ]);
        }

    }else{
        echo json_encode([
            "log" => "".mysqli_error($db).$chmod,
            "message" => "업로드에 실패했습니다 다시시도 해주세요",
            "result" =>"fail"
        ]);
    }

    }
    

}else if($request_key === "customer_setting"){
    $user_id =$_POST['user_id'];

    $query = "SELECT * FROM user_general WHERE id ='$user_id'";
    $result = $db->query($query);

    if(mysqli_num_rows($result)==1){
        $row=mysqli_fetch_assoc($result);

        $user_image =$row['user_image'];
        $introduce =$row['introduce'];
        $place = $row['place'];
        $recode = $row['recode'];

        echo json_encode([
            "user_image" => "$user_image",
            "introduce" => "$introduce",
            "place" => "$place",
            "recode" => "$recode",
            "result" => "ok"
        ]);

    }else{
        echo json_encode([
            "message" => "존재하지 않는 아이디 입니다",
            "result" =>"fail"
        ]);
    }
}

?>