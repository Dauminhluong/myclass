
<?php
//session_start();

include_once("header.php");
$error='';
$email='';
$name='';
$username='';
$date='';
$phone='';
$pass='';
if(isset($_POST['email'])){
    print_r($_POST);
    $email=$_POST['email'];
    $name=$_POST['name'];
    $username=$_POST['username'];
    $date=$_POST['datebirth'];
    $phone=$_POST['phone'];
    $pass=$_POST['pass'];
    if(empty($_POST['email'])){
        $error="vui lòng nhập email";
    }
    elseif(empty($_POST['name'])){
        $error="vui lòng nhập tên";
    }
    elseif(empty($_POST['username'])){
        $error="vui lòng nhập username";
    }
    elseif(empty($_POST['datebirth'])){
        $error="vui lòng nhập ngày sinh";
    }
    elseif(empty($_POST['phone'])){
        $error="vui lòng nhập số điện thoại";
    }
    elseif(empty($_POST['pass'])){
        $error="vui lòng nhập mật khẩu";
    }
    elseif(empty($_POST['repass'])){
        $error="vui lòng nhập lại mật khẩu";
    }
    elseif($_POST['repass']!=$_POST['pass']) {
        $error="mật khẩu không khớp";
    }
    else{

        $result=register($username,$pass,$name,$date,$email,$phone);
        if($result['code']==0){
            echo '<h3 class="mx-auto text-center bordered"> Tài khoản của bạn đã được tạo. vui lòng kiểm tra email để kích hoạt tài khoản</h3>';
            sleep(20);
            header('Location: login.php');
            die();
        }else{
            $error=$result['error'];
        }
    }
}
?>

<div class="container ">
    <div class="row">
        <div class=" col-sm-12 col-md-5 mx-auto ">
            <div class="card hovershadow ">
                <article class="card-body ">
                    <a href="login.php" class="float-right btn btn-outline-primary">Login</a>
                    <h4 class="card-title mb-4 mt-1">Register</h4>
<!--                    <div class="avatar my-5 ">-->
<!--                        <span>Me</span>-->
<!--                    </div>-->
                    <p>
                        <a href="" class="btn btn-block btn-outline-danger"> <i class="fab fa-google"></i> Login via google</a>
                        <!--                        <a href="" class="btn btn-block btn-outline-primary"> <i class="fab fa-facebook-f"></i> � Login via facebook</a>-->
                    </p>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <input name="email" class="form-control"  placeholder="nhập địa chỉ email" type="email" value="<?=$email?>">
                        </div>
                        <div class="form-group">
                            <input name="name" class="form-control"  placeholder="nhập tên đầy đủ " type="text" value="<?=$name?>">
                        </div>
                        <div class="form-group">
                            <input name="username" class="form-control"  placeholder="nhập username" type="text" value="<?=$username?>">
                        </div>
                        <div class="form-group">
                            <input name="datebirth" class="form-control" placeholder="nhập ngày sinh dd/mm/yy" type='text' value="<?=$date?>"/>
                        </div>
                        <div class="form-group">
                            <input name="phone" class="form-control"  placeholder="nhập số điện thoại" type="text" value="<?=$phone?>">
                        </div>
                        <div class="form-group">
                            <input name="pass" class="form-control" placeholder="nhập mật khẩu" type="password">
                        </div>
                        <div class="form-group">
                            <input name="repass" class="form-control" placeholder="nhập lại mật khẩu" type="password">
                        </div>


                        <?php
                        if(!empty($error)) {
                            ?>
                            <div class="bg-warning p-3 mb-2 text-center text-danger rounded"> <p><?= $error?></p></div>
                            <?php
                        }
                        ?>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group ">
                                    <button type="submit" class="btn btn-primary btn-block "> register  </button>
                                </div>
                            </div>
                            <div class="col-md-6 text-right">
                                <a class="small" href="#">Forgot password?</a>
                            </div>
                        </div>
                    </form>
                </article>
            </div>
        </div>
    </div>
</div>

<?php
include_once("footer.php");
?>
