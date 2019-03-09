<?php

if(isset($_GET['dll']) && !empty($_GET['dll']))
{
    if(file_exists('upld/' . strip_tags($_GET['dll'])))
    {
        // Here we force download the file to the user using content disposition

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        header("Content-Description: File Transfer"); 
        header("Content-Type: " . finfo_file($finfo, 'upld/' . $_GET['dll']));
        header("Content-disposition: attachment; filename=\"" . basename('upld/' . $_GET['dll']) . "\""); 
        readfile('upld/' . $_GET['dll']; // do the double-download-dance (dirty but worky)
    }
    else { header('Location: index.php?error=Download error : Bad file name or not found.'); }
}
else { header('Location: index.php?error=Download error : You need to send a id and a file name.'); }

?>