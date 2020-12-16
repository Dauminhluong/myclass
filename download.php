<?php
$local_file = '';
if(isset($_GET['file'])) {
    $local_file = $_GET['file'];
    if(!empty($local_file)){
        $download_rate = 20.5;
        if(file_exists($local_file) && is_file($local_file))
        {
            header('Cache-control: private');
            header('Content-Type: application/octet-stream');
            header('Content-Length: '.filesize($local_file));
            header('Content-Disposition: filename='.$local_file);

            flush();
            $file = fopen($local_file, "r");
            while(!feof($file))
            {
                // send the current file part to the browser
                print fread($file, round($download_rate * 1024));
                // flush the content to the browser
                flush();
                // sleep one second
                sleep(1);
            }
            fclose($file);}
        else {
            die('Error: The file '.$local_file.' does not exist!');
        }
    }else{
        echo "C?u L?c";
    }
}

?>