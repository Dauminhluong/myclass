<?php
session_start();
//function geterror($erroerno,$errorstr){
//    echo $erroerno.$errorstr;
//    error_log("loi php");
//    die();
//}
//set_exception_handler('geterror');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

//$host='127.0.0.1';
//$user='root';
//$pass='';
//$dbname = "myclass";
//$conn=new mysqli($host,$user,$pass,$dbname);
//if($conn->error){
//    die("lỗi kết nối".$conn->error);
//}else{
//}
function openMysqlConnection(){
   $servername = "sql12.freemysqlhosting.net";
    $username = "sql12382406";
    $password = "9HYtEH9nec";
    $dbname = "sql12382406";
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    return $conn;
}
function closeMysqlConnection($conn){
    $conn->close();
}
function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
function login($user,$pass){
    $sql="SELECT * FROM thanhvien where USERNAME=?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$user);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
    $data=$result->fetch_assoc();
    $hashed_password=$data["MATKHAU"];
    if(!password_verify($pass,$hashed_password)){
        return null;
    }
    return $data;
}
function is_email_exists($email)
{
    $sql = "SELECT username from thanhvien where email = ?";
    $conn = openMysqlConnection();
    $stm = $conn->prepare($sql);
    $stm->bind_param('s', $email);
    if (!$stm->execute()) {
        die("querry error:" . $stm->error);
    }
    $result = $stm->get_result();
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

function register($user,$pass,$name,$datebirth,$email,$phone){
    if(is_email_exists($email)){
        return array('code'=>1,'error'=>'Email exists');
    }
    $hash=password_hash($pass,PASSWORD_DEFAULT);
    $rand=rand(0,1000);
    $token=md5($user.'+'.$rand);
    $date=date('Y/m/d', strtotime($datebirth));
    $sql="INSERT INTO thanhvien(USERNAME ,MATKHAU,HOTEN,NGAYSINH,EMAIL,SODIENTHOAI,MAKICHHOAT)  VALUES(?,?,?,?,?,?,?)";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('sssssss',$user,$hash,$name,$date,$email,$phone,$token);
    if(!$stm->execute()){
        return array('code'=>2,'error'=>'cannot execute command');
    }
    $tmp= SendActivationEmail($email,$token);
    if(!$tmp){
        return array('code'=>3,'error'=>'cannot send email');
    }
    return array('code'=>0,'error'=>'create account successful');
}
function SendActivationEmail($email,$token){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->CharSet = 'UTF-8';
        $mail->Username   = 'dauanhboy@gmail.com';                     // SMTP username
        $mail->Password   = 'fhthjeoqccauixqo';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
        $mail->setFrom('dauanhboy@gmail.com', 'minh luong');
        $mail->addAddress($email, 'ng??i nh?n');     // Add a recipient
//        $mail->addAddress('ellen@example.com');               // Name is optional
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');
//
//        // Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'xác minh tài khoản';
        $mail->Body    = "xin chào <br> click <a href='http://localhost:8888/active.php?email=$email&token=$token'>vào đây</a> ?? xác minh tài khoản của bạn";
        $mail->AltBody = "vui lòng truy cập trang http://localhost:8888/active.php?email=$email&token=$token để xác minh tài khoản của bạn ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
function addclass( $key,$name,$subjects,$room,$pic,$user){
    $conn=openMysqlConnection();
    $sql1="INSERT INTO lophoc(MALOP,TENLOP,MMH,PHONGHOC,ANHDAIDIEN)  VALUES(?,?,?,?,?)";
    $stm=$conn->prepare($sql1);
    $stm->bind_param('sssss',$key,$name,$subjects,$room,$pic);
    if(!$stm->execute()){
        return array('code'=>1,'error'=>'không thể thêm lớp');
    }
    if(!addmembertoclass($user,$key)){
        return array('code'=>2,'error'=>'không thể thêm Giáo viên vào lớp');
    }
    return array('code'=>0,'error'=>'Đã tạo môn học');

}
function addmembertoclass($user,$key){
    $conn=openMysqlConnection();
    $sql1="INSERT INTO thanvienlophoc(USERNAME,MALOP)  VALUES(?,?)";
    $stm=$conn->prepare($sql1);
    $stm->bind_param('ss',$user,$key);
    if(!$stm->execute()){
        return false;
    }
    return true;
}
function listclass($user){
    $sql="SELECT MALOP  FROM thanvienlophoc where USERNAME=?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$user);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
//    $data=$result->fetch_assoc();
    $data=array();
    while($row = $result->fetch_assoc()) {
        $key=$row["MALOP"];
        $class=getclass($key);
//        $sql2="SELECT HOTEN from thanhvien WHERE LOAI = 'GV' AND USERNAME=(SELECT USERNAME FROM thanvienlophoc WHERE MALOP = ?)";
//        $conn2=openMysqlConnection();
//        $stm2=$conn2->prepare($sql2);
//        $stm2->bind_param('s',$key);
//        if(!$stm2->execute()){
//            echo "loi khong tim thay giao vien";
//
//        }
//        $result2=$stm2->get_result();
//        $data2=$result2->fetch_assoc();
//        $class["GV"]=$data2["HOTEN"];

        array_push($data,$class);
    }
    return $data;
}
function getclass($key){
    $sql="SELECT *  FROM lophoc where MALOP=?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
    $data=$result->fetch_assoc();
//    $sql2="SELECT HOTEN from thanhvien WHERE LOAI = 'GV' AND USERNAME=(SELECT USERNAME FROM thanvienlophoc  WHERE MALOP = ?)";
    $sql2="SELECT HOTEN from thanhvien WHERE  USERNAME IN (SELECT USERNAME FROM thanvienlophoc WHERE MALOP = ?) AND LOAI = 'GV' ";
    $conn2=openMysqlConnection();
    $stm2=$conn2->prepare($sql2);
    $stm2->bind_param('s',$key);
    if(!$stm2->execute()){
        echo "loi khong tim thay giao vien";

    }
    $result2=$stm2->get_result();
    $data2=$result2->fetch_assoc();
    $data["GV"]=$data2["HOTEN"];
    return $data;
}
function getallsubjects(){
    $conn = openMysqlConnection();
    $sql = "SELECT * FROM monhoc";
    $result = $conn->query($sql);
    $data=array();
    while($row = $result->fetch_assoc()) {
        array_push($data,$row);
    }
    return $data;
}
function getsubjects($key){
    $sql = "SELECT * FROM monhoc WHERE MMH=?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
    return $result;
}
function deleteclass($key){
    $sql="DELETE FROM thanvienlophoc WHERE MALOP=?";
    $conn = openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return array('code'=>1,'error'=>'không thể xóa thành viên trong lớp');
    }
    $sql="DELETE FROM lophoc WHERE MALOP=?";
    $conn = openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return array('code'=>2,'error'=>'không thể xóa lớp học');
    }
    return array('code'=>0,'error'=>'xóa lớp thành công');
}
function editclass( $key,$name,$subjects,$room,$pic){
    if (empty($pic)){
        $sql="UPDATE lophoc SET TENLOP=?,MMH=?,PHONGHOC=? WHERE MALOP=?";
        $conn=openMysqlConnection();
        $stm=$conn->prepare($sql);
        $stm->bind_param('ssss',$name,$subjects,$room,$key);
        if(!$stm->execute()){
            return false;
        }
    }else{
        $sql="UPDATE lophoc SET TENLOP=?,MMH=?,PHONGHOC=?,ANHDAIDIEN=?  WHERE MALOP=?";
        $conn=openMysqlConnection();
        $stm=$conn->prepare($sql);
        $stm->bind_param('sssss',$name,$subjects,$room,$pic,$key);
        if(!$stm->execute()){
            return false;
        }
    }

    return true;

}
function searchclass($key){
    $sql="SELECT * FROM lophoc WHERE TENLOP LIKE ?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
    $data=array();
    while($row = $result->fetch_assoc()) {
        $key=$row["MALOP"];
        $class=getclass($key);
        $sql2="SELECT HOTEN from thanhvien WHERE LOAI = 'GV' AND USERNAME IN (SELECT USERNAME FROM thanvienlophoc WHERE MALOP = ?)";
        $conn2=openMysqlConnection();
        $stm2=$conn2->prepare($sql2);
        $stm2->bind_param('s',$key);
        if(!$stm2->execute()){
            echo "loi khong tim thay giao vien";

        }
        $result2=$stm2->get_result();
        $data2=$result2->fetch_assoc();
        $class["GV"]=$data2["HOTEN"];
        array_push($data,$class);
    }
    return $data;
}
function pustpost($user,$key,$nd){
    $loai="POST";
    $sql="INSERT INTO baidang(USERNAME ,MALOP,NOIDUNG,THOIGIAN,LOAI)  VALUES(?,?,?,now(),?)";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $tmp=str_replace(["\r\n","\n\r","\r"],"\n",$nd);
    $stm->bind_param('ssss',$user,$key,$tmp,$loai);
    if(!$stm->execute()){
        return null;
    }
    $conn2 = openMysqlConnection();
    $sql2 = "SELECT MAX(MSBD) FROM baidang";
    $result2 = $conn2->query($sql2);
    $data = $result2->fetch_assoc();
    return $data["MAX(MSBD)"];
}
function addfile($keypost,$filename){
    $sql="INSERT INTO filebaidang(MSBD ,TENFILE)  VALUES(?,?)";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('ss',$keypost,$filename);
    if(!$stm->execute()){
        return false;
    }
    return true;
}
function getpost($key){
    $sql="SELECT * FROM baidang WHERE MALOP=?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$key);
    if(!$stm->execute()){
        return null;
    }
    $result=$stm->get_result();
    $data=array();
    while($row = $result->fetch_assoc()) {
        $keypost=$row["MSBD"];
        $sql2="SELECT * from filebaidang WHERE MSBD ="."\"$keypost\"";
        $conn2=openMysqlConnection();
        $result2=$conn2->query($sql2);
        $data2=array();
        while($row2 = $result2->fetch_assoc()) {
            array_push($data2,$row2["TENFILE"]);
        }
        $row["file"]=$data2;
        array_push($data,$row);
    }
    return $data;
}
function deletepostfile($keypost){
    $post_dir = "uploads/post/";
    $sql="select * FROM filebaidang WHERE MSBD = ?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$keypost);
    if(!$stm->execute()){
        return false;
    }
    $result=$stm->get_result();
    while($row = $result->fetch_assoc()) {
        $file=$post_dir.$row["TENFILE"];
        if (file_exists($file)) {
            unlink($file);
        }
    }
    $sql="DELETE FROM filebaidang WHERE MSBD = ?";
    $conn=openMysqlConnection();
    $stm=$conn->prepare($sql);
    $stm->bind_param('s',$keypost);
    if(!$stm->execute()){
        return false;
    }
    return true;
}
function deletepost($keypost){
    if (deletepostfile($keypost)) {
        $sql = "DELETE FROM baidang WHERE MSBD = ?";
        $conn = openMysqlConnection();
        $stm = $conn->prepare($sql);
        $stm->bind_param('s', $keypost);
        if (!$stm->execute()) {
            return false;
        }
    }else{
        return false;
    }
    return true;
}
function reFormat($str){
    $split = explode("\n",
        str_replace(["\r\n","\n\r","\r"],"\n",$str)
    );
    $data="";
    foreach ($split as &$s) {
        $data=$data.$s."<br>";
    }
    return $data;
}
function addmember($members,$key){
    $listmb = explode(" ", $members);
    foreach ($listmb as &$value) {
        $sql="INSERT INTO thanvienlophoc(USERNAME ,MALOP)  VALUES(?,?)";
        $conn=openMysqlConnection();
        $stm=$conn->prepare($sql);
        $stm->bind_param('ss',$value,$key);
        if(!$stm->execute()){
            return null;
        }
    }

    return true;

}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta http-equiv=�Content-Type� content=�text/html; charset=UTF-8?/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.8/css/all.css">

    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="main.js"></script>
    <style src="style.css"></style>
</head>
<body>
<nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <a class="navbar-brand" href="index.php">My Class</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#collapsibleNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="collapsibleNavbar">
        <ul class="navbar-nav ml-auto mr-5">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">Trang Chủ</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="list.php">Danh Sách Lớp </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="">Liên hệ </a>
            </li>
            <?php
            if(isset($_SESSION['user'])){
                ?>
                <li class="nav-item bg-secondary  rounded  dropdown">

                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                         Xin Chào <b><?=$_SESSION['hoten'] ?></b>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                        <a class="dropdown-item" href="#">tài khoản</a>
                        <a class="dropdown-item" href="logout.php">Log Out</a>
                    </div>
                </li>
                <?php
            }
            else{
            ?>
                <li class="nav-item bg-secondary rounded">
                    <a class="nav-link" href="login.php">Đăng nhập </a>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</nav>
<br>
