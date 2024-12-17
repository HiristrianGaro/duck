<?php
function cropImageToAspectRatioGD($inputPath, $outputPath, $aspectWidth, $aspectHeight) {
    // Load the image
    $imageInfo = getimagesize($inputPath);
    $mime = $imageInfo['mime'];

    // Load the image
    $image = imagecreatefromstring(file_get_contents($inputPath));

    // Get the MIME type of the original file
    $info = getimagesize($inputPath);
    $mime = $info['mime'];

    // Get original image dimensions
    $originalWidth = imagesx($image);
    $originalHeight = imagesy($image);

    // Calculate target dimensions
    $targetWidth = $originalWidth;
    $targetHeight = (int)($originalWidth * ($aspectHeight / $aspectWidth));

    if ($targetHeight > $originalHeight) {
        // If calculated height is too large, base it on the height
        $targetHeight = $originalHeight;
        $targetWidth = (int)($originalHeight * ($aspectWidth / $aspectHeight));
    }

    // Calculate offsets to center the crop
    $x = (int)(($originalWidth - $targetWidth) / 2);
    $y = (int)(($originalHeight - $targetHeight) / 2);

    // Create a new true color image with the desired dimensions
    $croppedImage = imagecreatetruecolor($targetWidth, $targetHeight);

    // Copy and crop the image
    imagecopy($croppedImage, $image, 0, 0, $x, $y, $targetWidth, $targetHeight);

    // Save the output image (without resizing)

    $functionName = 'image' . substr(strrchr($mime, '/'), 1);
    error_log("Function name: {$functionName}");
    $functionName($croppedImage, $outputPath);

    // Free memory
    imagedestroy($image);
    imagedestroy($croppedImage);

    error_log("Image cropped and saved to {$outputPath}");
}

?>
