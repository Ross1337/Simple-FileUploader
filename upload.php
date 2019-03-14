<?php

if($_SERVER['REQUEST_METHOD'] != 'POST')
{ header('Location: index.php'); die(); }

// used for send error to user
function s_error($message)
{
    header('Location: index.php?error=' . strip_tags($message));
}

function generateRandomString($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// check the content type of the file ( http mime )
function check_file_content_type()
    {
        if($_FILES['uploaded_file']['error'] == 0)
        $fileinfo = $_FILES['uploaded_file'];

        // add here the mime type if you want to disallow someones
        $not_allowed_types = array(
            'application/javascript',
            'application/x-javascript', 
            'application/json', 
            'application/ld+json', 
            'application/x-php',
            'text/html',
            'text/javascript',
            'application/xhtml+xml',
            'text/x-component'
            );

        // if file mime is in the array, do not upload
        if(in_array($_FILES['uploaded_file']['type'], $not_allowed_types))
        { $bad_file = 1; }

        if(isset($bad_file) && $bad_file == 1)
        { return 1; }
        else
        { return 0; }
    }

function check_file_extension()
    {
        // add here the extension you want to disallow
        $not_allowed_ext = array(
            'php',
            'js',
            'json',
            'html',
            'xhtml'
        );

        // look into the array if the extension is allowed or not
        $extension_info = pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION);
        if(in_array($extension_info, $not_allowed_ext))
        { return 1; }
        else
        { return 0; }
    }

function check_upload_size()
    {
        // get file size
        $filesize =  $_FILES['uploaded_file']['size'];

        if($filesize <= 1000000 * 20)  // 1 000 000 bytes = 1 mo or *20 = 20 mo
        { return 0; }
        else
        { return 1; }
    }

// used to create a new dir
function create_n_dir()
    {
        $dirname = generateRandomString(50);
        mkdir('upld/' . $dirname);
        
        return $dirname;
    }

// check if the file has been uploaded
if(!isset($_FILES['uploaded_file']))
{ s_error("Retry to upload your file please."); exit(); }

// check if there is no error with the upload
if($_FILES['uploaded_file']['error'] != 0)
{ s_error($_FILES['uploaded_file']['error']); exit(); }

// check the content type of the file
if(check_file_content_type())
{ s_error("You can't upload files where content type is : " . $_FILES['uploaded_file']['type'] . "."); exit(); } 

// check the file extension
if(check_file_extension())
{ s_error("You can't upload files where extension is " . pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION) . "."); exit(); } 

// check the size of the uploaded file
if(check_upload_size())
{ s_error("You can't upload a file who's size is more then 20mb."); }  

/*

If all tests are ok,
it will upload the file normally.

*/

$c_dir = create_n_dir(); // create the directory where the file is gonna be moved
move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], "upld/" . $c_dir . "/" . $_FILES["uploaded_file"]["name"]); // move the file
$secu = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"); // check if the server is http or https

// redirect to index and giving to user a link to downlaod his file (fully securised against php defacer, js, ...)
header('Location: index.php?success='. $secu . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/download.php?dll=' . $c_dir . '/' . $_FILES["uploaded_file"]["name"]);  
                        
?>
