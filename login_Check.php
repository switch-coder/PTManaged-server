<?php
header('Access-Control-Allow-Origin: *');
include 'mysqlConn.php';
session_start();
//jsonObject 형태로 서버에서 왔음
$user_json = json_decode($_POST['login'],true);


$user_id = $user_json["user_id"];
$user_pw = $user_json['user_pw'];

if(empty($user_id)){mysql_close();echo "empty id"; return;}
if(empty($user_pw)){mysql_close();echo "empty pw"; return;}

//회원 아이디 값 찾는 쿼리
$query = "SELECT * FROM user WHERE id ='$user_id'";
$result = $db->query($query);

if(mysqli_num_rows($result)==1){
  $row=mysqli_fetch_assoc($result);

  //해당 아이디의 비밀번호와 비밀번호 일치 검사
  if($row['password']==$user_pw){
    $user_name =$row['name'];
    $user_type =$row['user_type'];
    $initial_setting = $row["initial_setting"];

    //일반 회원일 경우
    if($user_type === "general"){
      $query = "SELECT * FROM user_general WHERE id ='$user_id'";
      $result = $db->query($query);
      $row=mysqli_fetch_assoc($result);
      $my_trainer = $row["my_trainer"];
      $user_image = $row["user_image"];

    }else{
      //트레이너일 경우
      $query = "SELECT * FROM user_trainer WHERE id ='$user_id'";
      $result = $db->query($query);
      $row=mysqli_fetch_assoc($result);
      $user_image = $row["user_image"];
    }

      $_SESSION['user_id']=$user_id;
      $_SESSION['user_name']=$user_name;
      $_SESSION['user_type']=$user_type;
      $_SESSION['my_trainer']=$my_trainer;
      $_SESSION['user_image']=$user_image;
      $_SESSION['initial_setting']=$initial_setting;

      echo json_encode([
      "user_id" => "$user_id",
      "user_name" => "$user_name",
      "user_type" => "$user_type",
      "my_trainer" => "$my_trainer",//일반회원일 경우에만 들어감
      "user_image" => "$user_image",
      "initial_setting" =>$initial_setting,
      "result" => "ok"
    ]);
    return;

  }else{
    echo json_encode([
        "log"=> mysqli_error($db),
      "message" => "비밀번호가 일치하지 않습니다",
      "result" =>"fail"
  ]);
  }

}else{
  echo json_encode([
      "log"=> mysqli_error($db),
    "message" => "존재하지 않는 아이디 입니다",
    "result" =>"fail"
]);
  
}
?>