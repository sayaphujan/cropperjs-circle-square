<?
require_once( 'functions.php' );

if ( !empty( $_SESSION[ 'uid' ] ) )
{
    switch ( $_GET[ 'act' ] ) 
    {
        case 'image_list':
            $search = $_POST['search']['value']; // Ambil data yang di ketik user pada textbox pencarian
            $limit  = $_POST['length']; // Ambil data limit per page
            $start  = $_POST['start']; // Ambil data start

            $que        = mysqli_query($link, "SELECT COUNT(*) as total FROM `media_original`");
            $sql        = mysqli_fetch_all($que, MYSQLI_ASSOC);
            $sql_count  = $sql[0]['total'];

            $query = "SELECT * FROM `media_original` WHERE username='".$_SESSION['cname']."' AND active='1'";

            $order_field    = $_POST['order'][0]['column']; // Untuk mengambil nama field yg menjadi acuan untuk sorting
            $order_ascdesc  = $_POST['order'][0]['dir']; // Untuk menentukan order by "ASC" atau "DESC"
            $order          = " ORDER BY ".$_POST['columns'][$order_field]['data']." ".$order_ascdesc;

            $sql_data = mysqli_query($link, $query.$order." LIMIT ".$limit." OFFSET ".$start); // Query untuk data yang akan di tampilkan
            $sql_filter = mysqli_query($link, $query); // Query untuk count jumlah data sesuai dengan filter pada textbox pencarian
            $sql_filter_count = mysqli_num_rows($sql_filter); // Hitung data yg ada pada query $sql_filt

            $data = mysqli_fetch_all($sql_data, MYSQLI_ASSOC); // Untuk mengambil data hasil query menjadi array
            $callback = array(
                'draw'=>$_POST['draw'], // Ini dari datatablenya
                'recordsTotal'=>$sql_count,
                'recordsFiltered'=>$sql_filter_count,
                'data'=>$data
            );
            header('Content-Type: application/json');
            echo json_encode($callback);

            break;
            
        case 'crop_image':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['image'])) {
                $dataURL = $_POST['image'];
                $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $dataURL));
            
                // Specify the directory where the image will be saved
                $uploadDir = '../cropped/';
            
                // Generate a unique filename for the cropped image
                $filename = uniqid() . '.png';
            
                // Save the image file
                $success = file_put_contents($uploadDir . $filename, $data);
            
                if ($success) {
                    $que = "INSERT INTO `media_cropped` (
                                                        `username`,`filename`,`created`
                                                        ) 
                                                        VALUES (
                                                                '".make_safe($_SESSION['cname'])."',
                                                                '".make_safe($filename)."',
                                                                NOW()
                                                            )";
                                                            mysqli_query($link, $que);
                    echo $filename;
                } else {
                    echo 'Error saving the cropped image.';
                }
            }
            break;
            
        case 'upload_image':
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image'])) {
                $dataURL = $_FILES["image"]["tmp_name"];
                
                // Specify the directory where the image will be saved
                $uploadDir = '../uploads/';
                
                // Generate a unique filename for the cropped image
                $filename = uniqid() . '_' . $_FILES['image']['name'];
                $originalFilePath = $uploadDir . $filename;
                
                // Save the image file
                if (move_uploaded_file($dataURL, $originalFilePath)) {
                    // Original image saved successfully
                    // Perform additional actions with the original image
                    $que = "INSERT INTO `media_original` (
                                                        `username`,`filename`,`created`
                                                        ) 
                                                        VALUES (
                                                                '".make_safe($_SESSION['cname'])."',
                                                                '".make_safe($filename)."',
                                                                NOW()
                                                            )";
                                                            mysqli_query($link, $que);
                    // Return the filename of the saved image
                    echo $filename;
                } else {
                    echo 'Error saving the original image.';
                }
            }
            break;

        case 'logout':
            session_destroy();
            header( 'Location: ' . root() );
            exit();
            break;    
        
        default:
            header( 'Location: ' . root() );
            exit();
            break;
    }
}
else
{

switch ( $_GET[ 'act' ] ) 
{
    
            case 'signup':

                $is_register = true;
                  $eChk = mysqli_query( $link, 'SELECT username FROM users WHERE username=\'' . make_safe( $_POST[ 'cusername' ] ) . '\' AND deleted=\'0\' LIMIT 1' );
                  
                    if ( mysqli_num_rows( $eChk ) == 0 ) 
                    {
                        
                        $salt = hash( 'sha256', uniqid( mt_rand(), true ) . time() . strtolower( $_POST[ 'cusername' ] ) );
                        $hash = $salt . $_POST[ 'cpassword' ];
                        for ( $i = 0; $i < 100000; ++$i ) {
                            $hash = hash( 'sha256', $hash );
                        }
                        $hash = $salt . $hash;
                        
                        if($is_register)
                        {
                            $query =    "INSERT INTO users (
                                                        created, username, salt, password) 
                                            VALUES (
                                                NOW(),
                                                '".make_safe($_POST['cusername'])."',
                                                '".make_safe($salt)."',
                                                '".make_safe($hash)."'
                                            )";
                                            mysqli_query($link, $query);
                                            $id = mysqli_insert_id($link);
                        }
                    } 
                    else 
                    {
                        $que = mysqli_query($link, 'SELECT * FROM users WHERE username=\'' . make_safe( $_POST[ 'cusername' ] ) . '\' AND deleted=\'0\' LIMIT 1');
                        $res  = mysqli_fetch_assoc($que);
                        
                        //print_r($res);
                        
                        $id = $res['id'];
                        
                        $salt = hash( 'sha256', uniqid( mt_rand(), true ) . time() . strtolower( $_POST[ 'cusername' ] ) );
                            $hash = $salt . $_POST[ 'cpassword' ];
                            for ( $i = 0; $i < 100000; ++$i ) {
                                $hash = hash( 'sha256', $hash );
                            }
                            $hash = $salt . $hash;
                            
                            $query =    "UPDATE users SET 
                                                        salt = '".make_safe($salt)."', 
                                                        password = '".make_safe($hash)."',
                                                        username = '".make_safe($_POST['cusername'])."'
                                                WHERE id = '".make_safe($_POST['uid'])."'";
                            //echo $query;
                        mysqli_query($link, $query);
                    }
                $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>You are successfully registered, please Log in.</div>';
                header( 'Location: ' . root().'login/' );
                            exit();
                
            break;
            
            case 'login':
            
            if ( !empty( $_POST[ 'cusername' ] ) && !empty( $_POST[ 'cpassword' ] ) ) {
               
                //checking referal login
                $chk = mysqli_query( $link, 'SELECT * FROM users WHERE username=\'' . make_safe( $_POST[ 'cusername' ] ) . '\' AND deleted=\'0\' LIMIT 1' );

                if ( mysqli_num_rows( $chk ) == 1 ) {
                    $hash = mysqli_result( $chk, 0, 'salt' ) . $_POST[ 'cpassword' ];
                    for ( $i = 0; $i < 100000; ++$i ) {
                        $hash = hash( 'sha256', $hash );
                    }
                    $hash = mysqli_result( $chk, 0, 'salt' ) . $hash;
                    if ( $hash == mysqli_result( $chk, 0, 'password' ) ) {
                        $_SESSION[ 'uid' ] = mysqli_result( $chk, 0, 'id' );
                        $_SESSION[ 'cname' ] = mysqli_result( $chk, 0, 'username' );
                        $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Welcome, ' . $_SESSION[ 'cname' ] . '</div>';
                        header( 'Location: ' . root( 'dashboard/' ) );
                        exit();
                    } else {
                        $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! Please check your password.</div>';
                    }
                }
                else
                {

                    $chk = mysqli_query( $link, 'SELECT * FROM users WHERE username=\'' . make_safe( $_POST[ 'cusername' ] ) . '\' AND deleted=\'0\' AND password != \'\' LIMIT 1' );

                    if ( mysqli_num_rows( $chk ) == 1 ){
                        $hash = mysqli_result( $chk, 0, 'salt' ) . $_POST[ 'cpassword' ];
                        for ( $i = 0; $i < 100000; ++$i ) {
                            $hash = hash( 'sha256', $hash );
                        }
                        $hash = mysqli_result( $chk, 0, 'salt' ) . $hash;
                        
                        if ( $hash == mysqli_result( $chk, 0, 'password' ) ) 
                        {
                            $_SESSION[ 'uid' ] = mysqli_result( $chk, 0, 'id' );
                            $_SESSION[ 'cname' ] = mysqli_result( $chk, 0, 'username' );
                            $_SESSION[ 'error' ] = '<div class="alert alert-success mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Welcome, ' . $_SESSION[ 'cname' ] . '</div>';
                           
                            header( 'Location: ' . root( 'dahsboard/?user='.$_SESSION['uid'] ) );
                            exit();
                       } else {
                            $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! Please check your password.</div>';
                        }
                    }
                    else 
                    {
                        $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! User does not exist!</div>';
                    }
                }
                
            } else {
                $_SESSION[ 'error' ] = '<div class="alert alert-danger mt-5"><a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>Could not be logged in! Please check your login details.</div>';
            }
            
            header( 'Location: ' . root( 'login/' ) );
            exit();
            break;
            
            default:
            header( 'Location: ' . root() );
            exit();
            break;
    }
}
?>