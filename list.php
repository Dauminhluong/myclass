
<?php
include_once("header.php");
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    die();
}
$target_dir = "uploads/class/";

//$conn=openMysqlConnection();
//print_r($_POST);
$user=$_SESSION["user"];

if(isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action=="create"&& isset($_POST['key']) && isset($_POST['name'])&&isset($_POST['subjects']) && isset($_POST['room'])) {
        $key = $_POST['key'];
        $name = $_POST['name'];
        $subjects = $_POST['subjects'];
        $room = $_POST['room'];

        if (empty($key)||empty($name) || empty($subjects) || empty($room)) {
            $error = "Vui lòng nhập đủ Thông tin";
        } else {
            $pic="";
            if($_FILES){
                $target_dir = "uploads/class/";
                if (!file_exists($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $target_file = $target_dir . basename($_FILES["pic"]["name"]);
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                $check = getimagesize($_FILES["pic"]["tmp_name"]);
                if($check == false) {
                    $error= "File không phải là ảnh";
                    $uploadOk = 0;
                    }
                if (file_exists($target_file)) {
                    $error= "Xin lỗi, file này đã tồn tại";
                    $uploadOk = 0;
                }
                if ($_FILES["pic"]["size"] > 500000) {
                    $error=  "Xin lỗi, kích thước File quá lớn.";
                    $uploadOk = 0;
                }
                if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
                    && $imageFileType != "gif" ) {
                    echo "Xin lỗi, chỉ file JPG, JPEG, PNG và GIF  cho phép.";
                    $uploadOk = 0;
                }
                if ($uploadOk == 0) {
                } else {
                    if (move_uploaded_file($_FILES["pic"]["tmp_name"], $target_file)) {
                        $pic=( basename( $_FILES["pic"]["name"]));
                    } else {
                        $error= "lỗi upload file.";
                    }
                }
            }

            $result = addclass($key, $name, $subjects, $room, $pic, $user);
            if ($result['code'] == 0) {
                $success = "Tạo lớp thành công";
            } else {
                $error = $result['error'];
            }
        }
    }elseif ($action=="delete" && isset($_POST['key'])){
        $key = $_POST['key'];
        $result=deleteclass($key);
        if($result["code"]==0){
            $success=$result["error"];
        }else{
            $error=$result["error"];
        }

    }elseif ($action=="edit" && isset($_POST['key'])&& isset($_POST['name'])&& isset($_POST['subjects']) && isset($_POST['room'])){
        $key = $_POST['key'];
        $name = $_POST['name'];
        $subjects = $_POST['subjects'];
        $room = $_POST['room'];

        if (empty($key)||empty($name) || empty($subjects) || empty($room)) {
            $error = "Vui lòng nhập đủ Thông tin";
        } else {
            if (!empty($_POST["pic"])) {
                $pic = $_POST['pic'];
            } else {
                $pic = '';
            }

            if (editclass($key, $name, $subjects, $room, $pic)) {
                $success = "Sửa lớp thành công";
            } else {
                $error = "Sửa lớp Không thành công";
            }
        }

    }

}
$listclass=listclass($user);


if(isset($_GET['key'])) {
    $key = $_GET['key'];
    if(!empty($key)){
        $key="%".$key."%";
        $listclass= searchclass($key);
    }else{

    }
}






//print_r(listclass($user));
$subjects=getallsubjects();
$key=generateRandomString(12);
//$_SESSION["key"]=$key;

?>



<div class="container ">
    <div class="row">
        <div class=" col-sm-4 col-md-2 my-2 px-1 ">
            <a href="" class="btn btn-primary w-100 classname "> <i class="fab fa-google"></i> Todo</a>
        </div>
        <div class=" col-sm-4 col-md-2 my-2 px-1">
            <a href="" class="btn btn-primary w-100 classname"> <i class="fab fa-google"></i> calendar</a>
        </div>
        <?php
        if($_SESSION['loai']=="GV"){
            ?>
        <div class=" col-sm-4 col-md-2 my-2 px-1">
            <a href="" class="btn btn-primary w-100 classname" data-toggle="modal" data-target="#createclass"> <i class="fas fa-plus"></i> Tạo Lớp Học</a>
        </div>
            <?php
        }
        ?>
        <div class="col-sm-12 col-md-6 my-2 px-1">
            <form method="get"  >
                <div class="input-group w-100">
                    <input type="text" class="form-control" placeholder="search" name="key">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i></button>
                    </div>
                </div>
            </form>
        </div>



    </div>

    <div class="row">
        <?php
        if(!empty($success)) {
            ?>
        <div class=" col-sm-12 my-2 text-center bg-success ">
            <?= $success?>
        </div>
            <?php
        }if(!empty($error)){
        ?>
            <div class=" col-sm-12 my-2 text-center bg-warning ">
                <?= $error?>
            </div>
        <?php
        }
        ?>

        <?php
        if(count($listclass)>0){
        foreach (array_reverse($listclass) as &$class) {
            if(empty($class["ANHDAIDIEN"])){
               $class["ANHDAIDIEN"]="img_learnlanguage1.jpg";
            }
        ?>
            <div class=" col-sm-6 col-md-6 col-lg-4 my-2  ">

                <div class=" hovershadow border ">
                    <img class=" avatarradius imageavatarsmall " src="images/img_avatar2.png" alt="">
                    <article onclick="goto('<?= $class["MALOP"]?>')" class=" headtitleclass text-white p-3" style="background-image: url('<?=$target_dir.$class["ANHDAIDIEN"]?>')">
                        <h4 class=" py-0 my-0 classname w-100"><?= $class["TENLOP"]?></h4>
                        <p class=" py-0 my-0"><?= $class["PHONGHOC"]?></p>
                        <p class=" py-0 my-0"><?= $class["GV"]?></p>
                    </article>
                    <article class="p-3  maintitleclass">
                        <span>due to tomorrow</span>
                        <p class="">ex1</p>
                        <span>due to tomorrow</span>
                        <p class="">ex1</p>
                    </article>
                    <div class="p-3 text-center border-top foottitleclass text-white bg-secondary"> Mã Lớp học: <?=$class["MALOP"]?>
                        <?php
                        if($_SESSION['loai']=="GV"){
                            ?>
                            <a href="" class="bordered rounded-circle " data-toggle="modal" data-target="#editclass" onclick="updatekeydeit('<?=$class["MALOP"]?>','<?=$class["TENLOP"]?>','<?=$class["MMH"]?>','<?=$class["PHONGHOC"]?>')"> <i class="fas fa-edit"></i></a>
                            <a href="" class="bordered rounded-circle " data-toggle="modal" data-target="#deleteclass" onclick="updatekeydelete('<?=$class["MALOP"]?>')"> <i class="fas fa-trash-alt"></i></a>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </div>

        <?php
        }}
        else{
            echo "Bạn chưa có lớp nào";
        }
        ?>


    </div>
</div>

<div class="modal fade" id="createclass">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post" action="" enctype="multipart/form-data">

                <div class="modal-header">
                    <h4 class="modal-title">Tạo lớp học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="form-group">
                        <label for="key">Mã Lớp học</label>
                        <input type="text" placeholder="nhập mã lớp"  class="form-control" value="<?=$key?>" disabled />
                    </div>

                    <div class="form-group">
                        <label for="name">tên lớp học</label>
                        <input type="text" placeholder="nhập Tên lớp học" name="name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="subjects">Chọn môn học</label>
                        <select name="subjects" class="form-control">
                            <?php
                            if(count($subjects)>0){
                                foreach ($subjects as &$s) {
                                    ?>
                                    <option value="<?=$s["MMH"]?>"><?=$s["TENMONHOC"]?></option>
                                    <?php
                                }
                            } else {
                                echo "Không có môn học nào";
                            }
                            ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room">Phòng học</label>
                        <input type="text" placeholder="nhập phòng học" name="room" class="form-control" />
                    </div>
                    <label for="pic">Ảnh Đại Diện</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" name="pic" id="myInput" aria-describedby="myInput">
                            <label class="custom-file-label" for="myInput">Choose file</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="create">
                    <input type="hidden" name="key"  value="<?=$key?>"  />
                    <button type="submit" class="btn btn-primary" >Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editclass">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post" action="">

                <div class="modal-header">
                    <h4 class="modal-title">Sửa lớp học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">


                    <div class="form-group">
                        <label for="name">tên lớp học</label>
                        <input type="text" placeholder="nhập Tên lớp học" name="name" class="form-control" id="editclassname" />
                    </div>
                    <div class="form-group">
                        <label for="subjects">Chọn môn học</label>
                        <select name="subjects" class="form-control" id="editclasssubjects">
                            <?php
                            if(count($subjects)>0){
                                foreach ($subjects as &$s) {
                                    ?>
                                    <option value="<?=$s["MMH"]?>"><?=$s["TENMONHOC"]?></option>
                                    <?php
                                }
                            } else {
                                echo "Không có môn học nào";
                            }
                            ?>

                        </select>
                    </div>
                    <div class="form-group">
                        <label for="room">Phòng học</label>
                        <input type="text" placeholder="nhập phòng học" name="room" class="form-control" id="editclassroom" />
                    </div>
                    <div class="form-group">
                        <label for="pic">Ảnh đại diện</label>
                        <input type="text" placeholder="nhập pic" name="pic" class="form-control" />
                    </div>

                </div>
                <div class="modal-footer">

                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="key" value="" id="keytoedit">
                    <button type="submit" class="btn btn-primary" >Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>


<div class="modal fade" id="deleteclass">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post" action="">

                <div class="modal-header">
                    <h4 class="modal-title">bạn có muốn Xóa lớp học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="key" value="" id="keytodelete">
                    <button type="submit" class="btn btn-danger" >Xóa</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Không</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php
//closeMysqlConnection($conn);
include_once("footer.php");
?>
