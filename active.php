<?php
include_once("header.php");

if(!empty($_GET["email"])&&!empty($_GET["token"])){
    $email=$_GET["email"];
    $token=$_GET["token"];
}
$conn=openMysqlConnection();

$sql = "UPDATE thanhvien SET kichhoat='1' WHERE email=? and makichhoat=?  ";
$stm=$conn->prepare($sql);
$stm->bind_param('ss',$email,$token);
?>

<div class="container">

        <div class="row">
            <div class="col-md-6 mt-5 mx-auto p-3 border rounded">
                <h4>kích hoạt tài khoản</h4>
                <?php
                if ($stm->execute()) {
                ?>
                    <p class="text-success">Xin chúc mừng! Tài khoản của bạn đã được kích hoạt.</p>
                    <?Php

                }
                else {
                    ?>
                    <p class="text-danger">Đường dẫn không hợp lệ hoặc hết hạn</p>
                    <?Php
//                    $error='registration failed';
                    echo $stm->error;
                }
                closeMysqlConnection($conn);
                ?>
                <p>Click <a href="login.php">vào đây</a> để đăng nhập</p>
                <a class="btn btn-success px-5" href="login.php">Đăng nhập</a>
            </div>
        </div>


</div>



<?php
include_once("footer.php");



?>
