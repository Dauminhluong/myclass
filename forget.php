<?php
include_once("header.php");

$error='';
$email = NULL;
if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $sql="SELECT EMAIL, MATKHAU,HOTEN,LOAI FROM thanhvien WHERE EMAIL="."\"$email\"";
    $result = $conn->query($sql);
    if (!$result) {
        $error="Email không t?n t?i";
    }else{

        $row = $result->fetch_assoc();
        $name=$row['HOTEN'];


        $to = "$email";
        $subject = "password recovery";
        $txt = "Chào b?n $name ";
        $headers = "From: webmaster@example.com" . "\r\n" .
            "CC: somebodyelse@example.com";

        mail($to,$subject,$txt,$headers);


    }


}

?>


<div class="container ">
    <div class="row">
        <div class=" col-sm-12 col-md-5 mx-auto ">
            <div class="card hovershadow ">
                <article class="card-body ">
                    <a href="" class="float-right btn btn-outline-primary">Sign in</a>
                    <h4 class="card-title mb-4 mt-1">recovery</h4>
                    <div class="avatar my-5 ">
                        <span>Me</span>
                    </div>
                    <p>
                        <a href="" class="btn btn-block btn-outline-danger"> <i class="fab fa-google"></i> ? Login via google</a>
                        <!--                        <a href="" class="btn btn-block btn-outline-primary"> <i class="fab fa-facebook-f"></i> ? Login via facebook</a>-->
                    </p>
                    <hr>
                    <form method="post">
                        <div class="form-group">
                            <input value="" name="email" type="email" class="form-control" placeholder="email">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?php
                                if (!empty($error)) {
                                    ?>
                                    <div class="alert alert-danger"><?= $error?></div>
                                    <?php

                                }
                                ?>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary btn-block"> send  </button>
                                </div>
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