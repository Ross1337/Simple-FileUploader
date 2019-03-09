<?php

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

Class Upload_File
{
    public static function check_isset()
    {
        if(isset($_FILES['uploaded_file']))
        { return 1; }
        else
        { return 0; }
    }

    public static function check_error()
    {
        if($_FILES['uploaded_file']['error'] == 0)
        { return 1; }
        else
        { return 1; }
    }

    public static function check_file_content_type()
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
        { return 0; }
        else
        { return 1; }
    }

    public static function check_file_extension()
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
        { return 0; }
        else
        { return 1; }
    }

    public static function check_upload_size()
    {
        // get file size
        $filesize =  $_FILES['uploaded_file']['size'];

        if($filesize <= 1000000 * 20)  // 1 000 000 bytes = 1 mo or *20 = 20 mo
        { return 1; }
        else
        { return 0; }
    }

    public static function create_n_dir()
    {
        $dirname = generateRandomString(50);
        mkdir('upld/' . $dirname);
        
        return $dirname;
    }
}

    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        if(Upload_File::check_isset())
        {
            if(Upload_File::check_error())
            {
                if(Upload_File::check_file_content_type())
                {
                    if(Upload_File::check_file_extension())
                    {
                        if(Upload_File::check_upload_size())
                        {
                            $c_dir = Upload_File::create_n_dir(); // create the directory where the file is gonna be moved
                            move_uploaded_file($_FILES["uploaded_file"]["tmp_name"], "upld/" . $c_dir . "/" . $_FILES["uploaded_file"]["name"]); // move the file
                            $secu = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http"); // check if the server is http or https

                            // redirect to index and giving to user a link to downlaod his file (fully securised against php defacer, js, ...)
                            header('Location: index.php?success= Upload successfull ! Download link : <input type="text" value="' . $secu . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']) . '/download.php?dll=' . $c_dir . '/' . $_FILES["uploaded_file"]["name"] . '">');  
                        }
                        else { s_error("You can't upload a file who's size is more then 20mb."); }  
                    }
                    else { s_error("You can't upload files where extension is " . pathinfo($_FILES['uploaded_file']['name'], PATHINFO_EXTENSION) . "."); } 
                }
                else { s_error("You can't upload files where content type is : " . $_FILES['uploaded_file']['type'] . "."); } 
            }
            else { s_error($_FILES['uploaded_file']['error']); }
        }
        else { s_error("Retry to upload your file please.");}
    }
    else { die(); }
?>
