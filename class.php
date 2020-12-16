
<?php
include_once("header.php");
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    die();
}
$error="";
$target_dir = "uploads/class/";
$post_dir="uploads/post/";
if(isset($_GET['class'])) {
    $key = $_GET['class'];
    if(!empty($key)){
        $class=getclass($key);
        if(empty($class["ANHDAIDIEN"])){
            $class["ANHDAIDIEN"]="img_learnlanguage1.jpg";
        }
    }else{
        echo "Cừu Lạc";
    }
}
if(isset($_POST['action'])) {
    $action = $_POST['action'];
    if ($action == "delete" && isset($_POST['key'])) {
        $keypost = $_POST['key'];
        if (!empty($keypost)) {
           if( deletepost($keypost)){
               $success="đã xóa bài đăng";
           }
        }
    }
    if ($action == "addmember" && isset($_POST['members'])) {
        $members = $_POST['members'];
        if (!empty($members)) {
            $key=$class["MALOP"];
            if( addmember($members,$key)){
                $success="đã thêm sinh viên";
            }
        }
    }

}



$user=$_SESSION["user"];
if(isset($_POST['post'])) {
    $input = $_POST['post'];
    if(!empty($input)){
        $keypost=pustpost($user,$key,$input);
        if(!empty($keypost)){

            if($_FILES){
                $post_dir="uploads/post/";
                if (!file_exists($post_dir)) {
                    mkdir($post_dir, 0777, true);
                }
                $total = count($_FILES['upload']['name']);
                for( $i=0 ; $i < $total ; $i++ ) {
                    $tmpFilePath = $_FILES['upload']['tmp_name'][$i];
                    if ($tmpFilePath != ""){
                        $uploadOk = 1;
                        $filename=$_FILES['upload']['name'][$i];
                        $newFilePath = $post_dir.$filename;
                        $imageFileType = strtolower(pathinfo($newFilePath, PATHINFO_EXTENSION));
                        if (file_exists($newFilePath)) {
                            $error = "Sorry, file already exists.";
                            $uploadOk = 0;
                        }
                        if ($_FILES["upload"]["size"][$i] > 500000) {
                            $error = "Sorry, your file is too large.";
                            $uploadOk = 0;
                        }
                        if($imageFileType =="exe" ||$imageFileType =="php" ||$imageFileType =="html" ) {
                            $error = "files are not allowed.";
                            $uploadOk = 0;
                        }
                        if ($uploadOk == 0) {
//                            $error = "Sorry, your file was not uploaded.";
                        } else {
                            if(move_uploaded_file($tmpFilePath, $newFilePath)) {
                                if(!addfile($keypost,$filename)){
                                    $error = "lỗi add file";
                                }

                            }else {
                                $error = "Sorry, there was an error uploading your file.";
                            }
                        }
                    }
                }
            }
        }

    }else{

    }
}
// print_r($_POST);


$listpost=getpost($key);



?>


<div class="container ">
    <div class="row  ">
        <div class=" col-sm-12 col-md-12 col-lg-12 my-2 headclass rounded w-100" style="background-image: url('<?=$target_dir.$class["ANHDAIDIEN"]?>')">
            <h2 class=" py-0 my-0 classname w-100"><?=$class["TENLOP"]?></h2>
            <h3 class=" py-2 my-0 classname w-100">  <?=$class["GV"]?></h3>
            <?php
            if($_SESSION['loai']=="GV"){
                ?>
                <a href="" class=" btn btn-primary " data-toggle="modal" data-target="#createassignment" > <i class="fas fa-plus"></i> tạo Bài tập</a>
                <a href="" class=" btn btn-primary " data-toggle="modal" data-target="#addmember" > <i class="fas fa-plus"></i> Thêm sinh viên</a>
                <?php
            }
            ?>


        </div>
    </div>
    <div class="row ">
        <div class="col-sm-6 col-md-4 col-lg-3 mb-2 ">
            <div class="border rounded p-4 bg-custom mr-2">
                <h5>upcoming</h5>
                <span> due tomorrow</span> <br>
                <a href="">nạp bài tập lab6</a> <br>
                <br>
                <a href="" class="text-right" >view all</a>
            </div>


        </div>
        <div class="col-sm-6 col-md-8 col-lg-9 bg-custom border rounded ">

<!--            <div class=" px-4 py-2 my-2">-->
<!--                <div class="border rounded bg-white p-4" ><i class="fa fa-user rounded-circle mr-2"></i> <span class="hover-blue" > Share something with your class... </span></div>-->
<!--            </div>-->
            <div class=" m-4 border rounded">
                <form method="post" action="" enctype="multipart/form-data">
                    <div class="form-group ">
<!--                        <label for="post">Share something with your class...</label>-->
                        <textarea class="form-control" placeholder="Share something with your class..."   name="post"  rows="3"></textarea>
                    </div>
                    <div class="input-group">
                        <div class="custom-file">

                            <input type="file" class="custom-file-input"  id="myInput" name="upload[]" multiple>
                            <label class="custom-file-label w-75" for="myInput">Đính kèm</label>
                            <button class="btn btn-success w-25" type="submit">post</button>

                        </div>
                    </div>

                </form>
            </div>
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
            if(count($listpost)>0){
            foreach (array_reverse($listpost) as &$p) {
                if(empty($p["deadline"])){
                    $p["deadline"]="";
                }

                ?>
                <div class="post px-4 py-2 border rounded my-2" >
                    <div class="mx-2"><i class="fa fa-user"></i> <b><?=$p["USERNAME"]?></b> <span><?=$p["THOIGIAN"]?></span>
                        <?php
                        if($_SESSION['loai']=="GV"||$_SESSION['user']==$p["USERNAME"] ){
                            ?>
                            <div class="d-inline-block ml-auto">
                                <a href="" class="bordered rounded-circle " data-toggle="modal" data-target="#editassignment" onclick="updateedit('<?=$p["MSBD"]?>','<?=reFormat($p["NOIDUNG"])?>','<?=$p["deadline"]?>')"><i class="fas fa-edit"></i></a>
                                <a href="" class="bordered rounded-circle " data-toggle="modal" data-target="#deletepost" onclick="updatekeydelete('<?=$p["MSBD"]?>')"> <i class="fas fa-trash-alt"></i></a>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                    <div class="bg-white border rounded p-2 " >
                        <p> <?=reFormat($p["NOIDUNG"])?></p>
                        <?php
                        if(!empty($p["file"])){
                            foreach($p["file"] as $f){
                                ?>
                                <a href="download.php?file=<?=$post_dir.$f?>" class="mx-2"><?=$f?></a>
                                <?php
                            }}
                        ?>
                    </div>
                </div>
                <?php
            }}
            ?>
<!---->
            <div class="post p-4 border rounded my-2" >
                <div><i class="fa fa-user"></i> <b><?=$class["GV"]?></b></div>
                <div class="bg-white border rounded p-2 " >
                    <p> lớp học đã được tạo </p>
                    <p> tên lớp:  <?=$class["TENLOP"]?></p>
                    <p> Giáo viên:  <?=$class["GV"]?></p>
                    <p> Mã Lớp:  <?=$class["MALOP"]?></p>
<!--                    <p> tên môn học:  --><?//=getsubjects($class["MMH"])["TENMONHOC"]?><!--</p>-->

                </div>
            </div>
<!--            <div class="post p-4 border rounded my-2 d-flex" >-->
<!--                <div class="d-inline">  <i class="fa fa-list-alt fa-3x mr-3 " aria-hidden="true"></i></div>-->
<!--                <div class="d-inline">-->
<!--                    <h4 class="d-inline">nguyen van A</h4>-->
<!--                    <span>oct 8</span>-->
<!--                    <p> mai nghi hoc ca 2</p>-->
<!--                </div>-->
<!---->
<!--            </div>-->






        </div>

    </div>
</div>


<div class="modal fade" id="deletepost">
    <div class="modal-dialog">

        <div class="modal-content">

            <form method="post" action="">

                <div class="modal-header">
                    <h4 class="modal-title">bạn có muốn Xóa bài đăng</h4>
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
<div class="modal fade" id="createassignment">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">

                <div class="modal-header">
                    <h4 class="modal-title">Tạo bài tập</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="form-group ">
                        <textarea class="form-control" placeholder="Share something with your class..."   name="post"  rows="3"></textarea>
                    </div>

                    <label for="pic">file đính kèm</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input"   name="upload[]" multiple>
                            <label class="custom-file-label" >Đính kèm</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">deadline</label>
                        <input type="datetime-local"  name="deadline" class="form-control" />
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="create">
                    <button type="submit" class="btn btn-primary" >Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="addmember">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" >

                <div class="modal-header">
                    <h4 class="modal-title">Thêm sinh viên vào lớp học</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="form-group ">
                        <textarea class="form-control" placeholder="Nhập username"   name="members"  rows="3"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="addmember">
                    <button type="submit" class="btn btn-primary" >Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editassignment">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="" enctype="multipart/form-data">

                <div class="modal-header">
                    <h4 class="modal-title">sửa bài tập</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">

                    <div class="form-group ">
                        <textarea class="form-control" placeholder="Share something with your class..."   name="post" id="editcontent"  rows="3"></textarea>
                    </div>

                    <label for="pic">file đính kèm</label>
                    <div class="input-group">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input"   name="upload[]" multiple>
                            <label class="custom-file-label" >Đính kèm</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">deadline</label>
                        <input type="datetime-local"  name="deadline" id="editdeadline" class="form-control" />
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="action" value="edit">
                    <input type="hidden" name="keypost" id="editkeypost" value="create">
                    <button type="submit" class="btn btn-primary" >Lưu</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include_once("footer.php");
?>
