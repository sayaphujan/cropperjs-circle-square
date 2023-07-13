<?php
$title = 'Crop';
$page_url = $_GET[ 'page' ];

require_once 'inc/functions.php';

if ( $_SERVER[ 'SERVER_PORT' ] !== '443' ) {
    header( 'location: ' . root() );
    exit();
}

if(isset($_SESSION['uid']))
{
    switch ( $_GET[ 'page' ] ) 
    {
        case 'upload':
            $page = 'pages/crop.php';
            $title .= ' | Upload';
            break;

        case 'dashboard':
            $page = 'pages/dashboard.php';
            $title .= ' | Dashboard';
            break;
        
        default:
            $page = 'pages/dashboard.php';
            $title .= ' | Dashboard';
            break;
    }
} else {
    switch ( $_GET[ 'page' ] ) 
    {
        case 'signup':
            $page = 'pages/signup.php';
            $title .= ' | Signup';
            break;
            
        case 'login':
            $page = 'pages/login.php';
            $title .= ' | Login';
            break;
            
            
        default:
            $page = 'pages/login.php';
            $title .= ' | Login';
            break;
    }

}
?>
<!doctype html>
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/bootstrap@4/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" />
    <link href="<?=root();?>assets/css/styles.css" rel="stylesheet" />
    <title>
        <?=$title; ?>
    </title>
</head>
<body class="d-flex flex-column h-100 bg-light">
    <main class="flex-shrink-0">
    <?php 
    //checking if logged in user is guest or not
    //checking if logged in user is guest or not
    //checking if logged in user is guest or not
    if(is_array($_SESSION))
    {
        if(isset($_SESSION['uid']))
        {
            $query = "SELECT * FROM users WHERE id = '".sf($_SESSION['uid'])."' AND salt = '' AND password = ''";
            $check = mysqli_query($link, $query);
            $check_guest = mysqli_fetch_assoc($check);
            
            if(is_array($check_guest))
            {
                $is_guest = true;
            }
            else
            {
                $is_guest = false;
            }
        }
        
        $query = mysqli_query($link, "SELECT * FROM users WHERE id = '".sf($_SESSION['uid'])."'");
            $result = mysqli_fetch_assoc($query);
    }
    ?>
            <!-- Navigation-->
            <nav class="navbar navbar-expand-lg navbar-light bg-white">
                <div class="container px-5">
                    <a class="navbar-brand" href="<?=root();?>"><span class="fw-bolder text-primary">Crop</span></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 small fw-bolder">
                        <? if(isset($_SESSION['uid'])) { ?>
                            <li class="nav-item"><a class="nav-link" href="<?=root('dashboard/')?>">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?=root('upload/')?>">Upload</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?=root('do/logout/')?>">Logout</a></li>
                        <? } else { ?>
                            <li class="nav-item"><a class="nav-link" href="<?=root('signup/')?>">Register</a></li>
                        <? }?>
                        </ul>
                    </div>
                </div>
            </nav>
    <!-- Page Content-->
        <?php         
            if ($_SESSION['error']) {
            echo '<div class="container">'.$_SESSION['error'].'</div>';
            unset($_SESSION['error']);
        }
        include $page;
        ?>
    <br/>
    </main>
  <!-- Scripts -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script src="https://unpkg.com/bootstrap@4/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="<?=root();?>assets/js/scripts.js"></script>
</body>
</html>