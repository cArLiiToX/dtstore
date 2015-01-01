<?php

//include('db.php');
//session_start();
error_reporting(E_ALL);

require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);


$session_id = '1'; // User session id
$path = "uploads/";

$valid_formats = array("jpg", "png", "gif", "bmp", "jpeg");
if (isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST") {
    $name = $_FILES['photoimg']['name'];
    $size = $_FILES['photoimg']['size'];
    if (strlen($name)) {
        list($txt, $ext) = explode(".", $name);
        if (in_array($ext, $valid_formats)) {
            
                $actual_image_name = time() . $session_id . "." . $ext;
                $tmp = $_FILES['photoimg']['tmp_name'];
                if (move_uploaded_file($tmp, $path . $actual_image_name)) {
                    
                    $sql = 'INSERT INTO recibo_upload_sales (image,orderid) values ("'.$actual_image_name.'","'.$_POST['orderid'].'")';
                    // fetch write database connection that is used in Mage_Core module
                    $write = Mage::getSingleton('core/resource')->getConnection('core_write');

                    // now $write is an instance of Zend_Db_Adapter_Abstract
                    $result = $write->query($sql);
                    
                    //mysqli_query($db, "UPDATE users SET profile_image='$actual_image_name' WHERE uid='$session_id'");
                    echo "<img src='/uploads/" . $actual_image_name . "' class='preview'>";
                } else
                    echo "failed";
        } else
            echo "Invalid file format..";
    } else
        echo "Please select image..!";
    exit;
}
?>