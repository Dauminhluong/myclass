<?php
include_once("header.php");
//session_start();
if (isset($_SESSION['user'])) {
    header('Location: list.php');
    die();
}
$error = NULL;

if (isset($_COOKIE['user']) && isset($_COOKIE['pass'])) {
    $user = $_COOKIE['user'];
    $pass = $_COOKIE['pass'];
}else {
    $user='';
    $pass='';
}
function getValue($input) {
    if (isset($input)) {
        return $input;
    }
    else return '';
}
if (isset($_POST['user']) && isset($_POST['pass'])) {
    $user = $_POST['user'];
    $pass = $_POST['pass'];

    if (empty($user)) {
        $error = "Vui lòng nhập Username";
    }
    else if (empty($pass)) {
        $error = "Vui lòng nhập password";
    }else {

        $data=login($user,$pass);
        if($data){
            if($data["KICHHOAT"]==1){
                $_SESSION['user'] = $user;
                $_SESSION['hoten'] = $data["HOTEN"];
                $_SESSION['loai'] = $data["LOAI"];
                if (isset($_POST['remember'])) {
                    setcookie('user', $user, time() + 15000);
                    setcookie('pass', $pass, time() + 15000);
                }

                header('Location: list.php');
                exit();
            }else{
                $error = 'tài khoản của bạn chưa được kích hoạt vui lòng kiểm tra email của ban để kích hoạt';
            }

        }else{
            $error = 'sai username hoặc mật khẩu';
        }


    }
}



?>

<div class="container ">
    <div class="row">
        <div class=" col-sm-12 col-md-5 mx-auto ">
            <div class="card hovershadow ">
                <article class="card-body ">
                    <a href="register.php" class="float-right btn btn-outline-primary">Register</a>
                    <h4 class="card-title mb-4 mt-1">Login</h4>
                    <div class="avatar my-5 ">
                        <span>Me</span>
                    </div>
                    <p>
                        <a href="" class="btn btn-block btn-outline-danger"> <i class="fab fa-google"></i> Login via google</a>
<!--                        <a href="" class="btn btn-block btn-outline-primary"> <i class="fab fa-facebook-f"></i> � Login via facebook</a>-->
                    </p>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <input value="<?= $user ?>" name="user" type="text" class="form-control" placeholder="nhập username">
                        </div>
                        <div class="form-group">
                            <input name="pass" value="<?= $pass ?>"  type="password" class="form-control" placeholder="Password">
                        </div>
                        <div class="form-group custom-control custom-checkbox">
                            <input name="remember" type="checkbox" class="custom-control-input" id="remember">
                            <label class="custom-control-label" for="remember">Nhớ tài khoản</label>
                        </div>
                        <?php
                        if (!is_null($error)) {
                            ?>
                            <div class="alert alert-danger"><?= $error?></div>
                            <?php

                        }
                        ?>
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <button type="submit" class="btn btn-primary btn-block"> Login  </button>
                            </div>
<!--                            <div class="">-->
<!--                                -->
<!--                            </div>-->
                            <div class="col-md-6 text-right">
                                <a class="small" href="#">Quên mật khẩu?</a>
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
