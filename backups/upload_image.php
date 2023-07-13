<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
    $dataURL = $_FILES["image"]["tmp_name"];
    
    // Specify the directory where the image will be saved
    $uploadDir = 'uploads/';
    
    // Generate a unique filename for the cropped image
    $filename = uniqid() . '_' . $_FILES['image']['name'];
    $originalFilePath = $uploadDir . $filename;

    // Save the image file
    if (move_uploaded_file($dataURL, $originalFilePath)) {
        // Original image saved successfully
        
        // Perform additional actions with the original image
        
        // Return the filename of the saved image
        echo $filename;
    } else {
        echo 'Error saving the original image.';
    }
}
?>
