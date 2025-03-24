<?php
include '../errorLogging.php';
//Vogliamo estendere questa parte di image processing per poter creare thumbnails di varie dimensioni per le foto profilo

function cropImageToAspectRatioGD($inputPath, $outputPath, $aspectWidth, $aspectHeight) {
    $imageInfo = getimagesize($inputPath);
    $mime = $imageInfo['mime'];

    $image = imagecreatefromstring(file_get_contents($inputPath));

    $info = getimagesize($inputPath);
    $mime = $info['mime'];

    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    $targetWidth = $originalWidth;
    $targetHeight = (int)($originalWidth * ($aspectHeight / $aspectWidth));

    if ($targetHeight > $originalHeight) {
        $targetHeight = $originalHeight;
        $targetWidth = (int)($originalHeight * ($aspectWidth / $aspectHeight));
    }

    $x = (int)(($originalWidth - $targetWidth) / 2);
    $y = (int)(($originalHeight - $targetHeight) / 2);

    $croppedImage = imagecreatetruecolor($targetWidth, $targetHeight);

    imagecopy($croppedImage, $image, 0, 0, $x, $y, $targetWidth, $targetHeight);


    $functionName = 'image' . substr(strrchr($mime, '/'), 1);
    error_log("Function name: {$functionName}");
    $functionName($croppedImage, $outputPath);

    imagedestroy($image);
    imagedestroy($croppedImage);

    error_log("Image cropped and saved to {$outputPath}");
}

?>
