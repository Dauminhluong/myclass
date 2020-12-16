<?php
include_once("header.php");
?>



<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active" id="nav-work-tab" data-toggle="tab" href="#nav-work" role="tab" aria-controls="nav-work" aria-selected="true">work</a>
        <a class="nav-item nav-link" id="nav-done-tab" data-toggle="tab" href="#nav-done" role="tab" aria-controls="nav-done" aria-selected="false">done</a>
        <a class="nav-item nav-link" id="nav-missing-tab" data-toggle="tab" href="#nav-missing" role="tab" aria-controls="nav-missing" aria-selected="true">missing</a>
        <a class="nav-item nav-link" id="nav-all-tab" data-toggle="tab" href="#nav-all" role="tab" aria-controls="nav-all" aria-selected="false">all</a>
        <select class="ml-5  nav-link active" name="cars" id="cars">
            <option value="volvo">HK1_2020_503073_L?p trình web và ?ng d?ng_N3_2</option>
            <option value="saab">HK1_2020_503073_L?p trình web và ?ng d?ng_N3_2</option>
            <option value="mercedes">HK1_2020_503073_L?p trình web và ?ng d?ng_N3_2</option>
            <option value="audi">HK1_2020_503073_L?p trình web và ?ng d?ng_N3_2</option>
        </select>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-work" role="tabpanel" aria-labelledby="nav-work-tab">
        <div class="post p-4 border rounded my-2 d-flex" >
            <div class="d-inline">  <i class="fa fa-list-alt fa-3x mr-3 " aria-hidden="true"></i></div>
            <div class="d-inline">
                <h4 class="d-inline">nguyen van A</h4>
                <span>oct 8</span>
                <p> mai nghi hoc ca 2</p>
            </div>

        </div>


    </div>
    <div class="tab-pane fade" id="nav-done" role="tabpanel" aria-labelledby="nav-done-tab">...</div>
    <div class="tab-pane fade" id="nav-missing" role="tabpanel" aria-labelledby="nav-missing-tab"> no missing</div>
    <div class="tab-pane fade" id="nav-all" role="tabpanel" aria-labelledby="nav-all-tab">...</div>
</div>





<?php
include_once("footer.php");
?>
