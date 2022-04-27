<?php
    require "vendor/autoload.php";

use Gumlet\ImageResize;

    $width = $_POST['width'];
    $height = $_POST['height'];

    //Validate size formats
    $sizeAllowed = [
        '310x150',
        '1920x1080'
    ];

    $size = $width.'x'.$height;
    if(!in_array($size, $sizeAllowed))
        die("Invalid resize values, valid resizes: ".implode(', ', $sizeAllowed));

    //Validate file errors
    $files = $_FILES;
    $file = $files['filename'];
    if($file['error'] > 0)
        die("No File");

    //Validate file size
    $maxBytes = 2048000;
    if($file['size'] > $maxBytes)
        die("File size is bigger than $maxBytes bytes");

    //Store file temporarily
    $filename = $file['name'];
    $fileTmp = $file['tmp_name'];
    $path = 'temp/'.$filename;
    if(!move_uploaded_file($fileTmp, $path))
        die("Failed to load file");

    //Resize file
    $image = new ImageResize($path);
    $image->resizeToHeight($height);
    $image->resizeToWidth($width);
    $image->save($filename, IMAGETYPE_WEBP);

    //Output resized file
    $image->output(IMAGETYPE_WEBP);

    //Remove file
    unlink($path);

    //upload original
    //upload resized
?>