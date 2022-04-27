<?php

define('ACCESS_KEY', '');
define('SECRET_KEY', '');
define('BUCKET', '');

require "vendor/autoload.php";

use Aws\S3\S3Client;
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
    $maxBytes = 204800;
    if($file['size'] > $maxBytes)
        die("File size is bigger than $maxBytes bytes");

    //Store file temporarily
    $filename = $file['name'];
    $fileTmp = $file['tmp_name'];
    $path = 'temp/'.$filename;
    if(!move_uploaded_file($fileTmp, $path))
        die("Failed to load file");

    try {
        //Resize file
        $image = new ImageResize($path);
        $image->resizeToHeight($height);
        $image->resizeToWidth($width);
        $image->save($filename, IMAGETYPE_WEBP);

        //Output resized file
        // $image->output(IMAGETYPE_WEBP);
    } catch (\Exception $e)
    {
        unset($path);
        die("Failed to resize file");
    }

    try {       
        $clientS3 = S3Client::factory(array(
            'key'    => ACCESS_KEY,
            'secret' => SECRET_KEY
        ));

        $response = $clientS3->putObject(array(
            'Bucket' => BUCKET,
            'Key'    => "original/$filename",
            'SourceFile' => $path,
        ));

        $response = $clientS3->putObject(array(
            'Bucket' => BUCKET,
            'Key'    => "resized/$filename",
            'SourceFile' => $image->__toString(),
        ));

    } catch(Exception $e) {
        echo "Erro > {$e->getMessage()}";
    }

    //Remove temp file
    unlink($path);
?>