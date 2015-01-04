<?php

//include('db.php');
//session_start();
error_reporting(E_ALL);

require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);

//$option = Mage::getStoreConfig('smtppro/general/option', $storeId);
$Username = Mage::helper('smtppro')->getSMTPSettingsUsername($storeId);

$Password = Mage::helper('smtppro')->getSMTPSettingsPassword($storeId);

$Host = Mage::helper('smtppro')->getSMTPSettingsHost($storeId);

$Port = Mage::helper('smtppro')->getSMTPSettingsPort($storeId);

$Auth = (Mage::helper('smtppro')->getSMTPSettingsAuthentication($storeId)=='login')?true:false;

$SSL = Mage::helper('smtppro')->getSMTPSettingsSSL($storeId);
$EmailSales = Mage::getStoreConfig('trans_email/ident_sales/email');
$NameSales = Mage::getStoreConfig('trans_email/ident_sales/name');

require 'phpmailer/PHPMailerAutoload.php';

$mail = new PHPMailer;

//$mail->SMTPDebug = 3;                               // Enable verbose debug output

$mail->isSMTP();                                      // Set mailer to use SMTP
$mail->Host = $Host;  // Specify main and backup SMTP servers
$mail->SMTPAuth = $Auth;                               // Enable SMTP authentication
$mail->Username = $Username;                 // SMTP username
$mail->Password = $Password;                           // SMTP password
$mail->SMTPSecure = $SSL;                            // Enable TLS encryption, `ssl` also accepted
$mail->Port = $Port;                                    // TCP port to connect to

$mail->From = $EmailSales;
$mail->FromName = $NameSales;


$Order = Mage::getModel('sales/order')->load($_POST['orderid']);     

$custname = $Order->getCustomerName();
$custemail = $Order->getCustomerEmail();


$mail->addAddress($custemail, $custname);     // Add a recipient
/*$mail->addAddress('ellen@example.com');               // Name is optional
$mail->addReplyTo('info@example.com', 'Information');
$mail->addCC('cc@example.com');
$mail->addBCC('bcc@example.com');

$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name*/
$mail->isHTML(true);                                  // Set email format to HTML

$mail->Subject = 'Confirmacion de Carga de recibo de compra - DT Store';
//$mail->AltBody = '';




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

                $sql = 'INSERT INTO recibo_upload_sales (image,orderid) values ("' . $actual_image_name . '","' . $_POST['orderid'] . '")';
                // fetch write database connection that is used in Mage_Core module
                $write = Mage::getSingleton('core/resource')->getConnection('core_write');

                // now $write is an instance of Zend_Db_Adapter_Abstract
                $result = $write->query($sql);

                $mail->addAttachment('/home/nikolaisan/www/uploads/'.$actual_image_name, $actual_image_name);    // Optional name*/
                $mail->Body = 'Gracias <b>'.$custname.'</b> por cargar el recibo del Pedido #'.$Order->getIncrementId().'. <br />Pronto recibira un email de confirmacion el pago.';

                if (!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    echo 'Message has been sent';
                }



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