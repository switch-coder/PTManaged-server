<?php 

//mysql 연결
include 'mysqlConn.php';

$Request_key  = $_POST['Request_key'];  
$id = $_POST['id'];     // 유저 아이디

//클라이언트에서 넘어오는 데이터값 확인
if(empty($id)){ echo 'empty id'; db_close(); return;}

if($Request_key ==='overlapCheck'){// 아이디 중복체크

    //유저아이디 중복체크 쿼리
    $query = "SELECT * FROM user WHERE id ='$id'";
    $result = $db->query($query);

    if(!$result) db_close(); echo "fail".mysqli_error($db);

    if(mysqli_num_rows($result) != 0){
            echo 'do use id';
            return;
    }else{
        echo "can use id";
    }

}else if($Request_key === 'sign_up'){// 회원가입
    $pw = $_POST['pw'];     // 유저 비밀번호
    $name = $_POST['name']; // 유저 이름
    $user_type = $_POST['user_type'];// 가입 구분 (트레이너/일반회원)

    //빈값 체크
    if(empty($pw)){ echo 'empty pw'; db_close();  return;}
    if(empty($name)){ echo 'empty name'; db_close();  return;}

    //유저타입 영어로 변경
    if($user_type ==="트레이너"){
        $user_type ='trainer';

        }else{
         $user_type ='general';

    }
    
    $query = "insert into user (id, password, name, user_type) 
        values ('$id', '$pw', '$name','$user_type')";

    if($db->query($query)){

        //회원 primay key 
        $num = mysqli_insert_id($db);

        // //회원 타입이 트레이너일 경우
        if($type === "trainer"){

            //아이디/이름/프로필사진/자기소개/이력/헬스장 위치/ sns 
            $query = "INSERT INTO user_trainer(num,id,name) 
                values('$num','$id', '$name')";
            if($db->query($query)){
                "success";
            }else{
                "false";
            }
        
        }else{//회원 타입이 일반회원일 경우

            //아이디/이름/프로필사진/운동경험/특이사항/직업
            $query = "INSERT INTO user_general(num,id,name) 
                values('$num','$id', '$name')";

            if($db->query($query)){
                db_close();
                echo "success";
            }else{
                db_close();
                echo "false";
            }

        }
    }else{
        db_close();
        echo "fail".mysqil_erorr($db);
    }
 
}

?>