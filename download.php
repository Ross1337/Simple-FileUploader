<?php

include('aes.php');

// used to catch a string between two caracters / strings
function get_string($string, $start, $end)
    {
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        $result = substr($string, $ini, $len);
        return $result;
    }

// decrypting the encrypted data and checking if if ok
function check_directory()
    {
        $directory_encrypted = get_string($_GET['dll'], '|', '|'); 
        $directory = AES::decrypt(base64_decode($directory_encrypted), 'upld/encryption/key.ini');
        if(preg_match("#^[a-zA-Z0-9]{5,}\/[a-zA-Z0-9._\-+<>:*|?']+\.*[a-zA-Z0-9]+$#", $directory) && file_exists('upld/' . $directory))
        { return 1; }
    }

// checking if the sent "GET" data is ok
function check_encrypted_directory()
    {
        $directory_en = $_GET['dll']; 
        if(preg_match("#^\|[a-zA-Z0-9\/+=]{5,}\|[a-zA-Z0-9._\-+<>:*|?']+\.*[a-zA-Z0-9]+\|$#", $directory_en))
        { return 1; }
    }

if(isset($_GET['dll']) && !empty($_GET['dll']))
{
    // checking encrypted directory && decrypted directory
    if(check_directory() && check_encrypted_directory())
    {
        // Here we force download the file to the user using content disposition

        // take decrypted directory upld/.../file.extension and not the encrytped directory
        $decrypted_directory = AES::decrypt(base64_decode(get_string($_GET['dll'], '|', '|')), 'upld/encryption/key.ini');

        $finfo = finfo_open(FILEINFO_MIME_TYPE);

        header("Content-Description: File Transfer"); 
        header("Content-Type: " . finfo_file($finfo, 'upld/' . $decrypted_directory));
        header("Content-disposition: attachment; filename=\"" . basename('upld/' . strip_tags($decrypted_directory)) . "\""); 
        readfile('upld/' . $decrypted_directory); // do the double-download-dance (dirty but worky)
    }
    else { header('Location: index.php?error=Download error : Bad file name or not found.'); }
}
else { header('Location: index.php?error=Download error : You need to send a id and a file name.'); }
    
?>