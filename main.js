$( document ).ready(function() {
    $('.custom-file-input').change(function(e){
        var file=this.files;
        //var file=document.getElementById("myInput").files;
        var text="";
        for ( i=0;i<file.length;i++){
            text=text+file[i].name+" ";
        }
        //var fileName = document.getElementById("myInput").files[0].name;
        var nextSibling = this.nextElementSibling;
        nextSibling.innerText = text;
    })
});

function updatekeydeit(key,name,mmh,room) {
    $("#keytoedit").val(key);
    $("#editclassname").val(name);
    $("#editclasssubjects").val(mmh);
    $("#editclassroom").val(room);

}
function updatekeydelete(key){
    //document.getElementById("keytodelete").value = key;
    $("#keytodelete").val(key);
}
function goto(key){
    window.location.href = "class.php?class="+key;
}
function updateedit(key,content,deadline){
    $("#editkeypost").val(key);
    $("#editcontent").val(content.split('<br>').join('\n'));
    $("#editdeadline").val(deadline);
}


