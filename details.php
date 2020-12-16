<?php
include_once("header.php");
?>


    <div class="container ">
        <div class="row">
            <div class=" col-sm-6 col-md-8 col-lg-9    ">
                <div class="post p-4   my-2 d-flex" >
                    <div class="d-inline">  <i class="fa fa-list-alt fa-3x mr-3 " aria-hidden="true"></i></div>
                    <div class="d-inline">
                        <h4 class="d-inline">nguyen van A</h4>
                        <span>oct 8</span>
                        <p> mai nghi hoc ca 2</p>
                    </div>
                </div>

            </div>
            <div class=" col-sm-6 col-md-4 col-lg-3  rounded border p-4  ">
                <form action="">
                    <div ><h4 class="d-inline">your work</h4> <span class="mr-auto">Assigned</span> </div>
                    <div class="form-group custom-file">
                        <input type="file" class="custom-file-input" id="customFile">
                        <label class="custom-file-label" for="customFile">Choose file</label>
                    </div>
                    <button type="button" class="btn btn-outline-primary w-100 my-2 ">+ add or create</button>
                    <button class="btn btn-primary w-100 my-2" type="submit">mask as done</button>
                </form>
            </div>
        </div>
    </div>


<?php
include_once("footer.php");
?>