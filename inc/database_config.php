<?php
$link = mysqli_connect('localhost', 'root', '', 'cropping');
if (mysqli_connect_error()) {
    $emsg = 'MySQL Error: '.mysqli_connect_error();
    die($emsg);
}
?>