<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image'])) {
    $dataURL = $_POST['image'];
    $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataURL));

    // Specify the directory where the image will be saved
    $uploadDir = 'cropped/';

    // Generate a unique filename for the cropped image
    $filename = uniqid() . '.png';

    // Save the image file
    $success = file_put_contents($uploadDir . $filename, $data);

    if ($success) {
        echo $filename;
    } else {
        echo 'Error saving the cropped image.';
    }
}

?>
