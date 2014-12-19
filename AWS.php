<?php

error_reporting(E_ALL);


/**
 * For a running Search Demo see: http://amazonecs.pixel-web.org
 */

if ("cli" !== PHP_SAPI)
{
    echo "<pre>";
}



define('AWS_API_KEY', 'AKIAJE3JSJ4HYRAAO3JQ');
define('AWS_API_SECRET_KEY', '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');
define('AWS_ASSOCIATE_TAG', 'dt004d-20');

require 'AWS/lib/AmazonECS.class.php';

try
{
    $amazonEcs = new AmazonECS(AWS_API_KEY, AWS_API_SECRET_KEY, 'COM', AWS_ASSOCIATE_TAG);

    // for the new version of the wsdl its required to provide a associate Tag
    // @see https://affiliate-program.amazon.com/gp/advertising/api/detail/api-changes.html?ie=UTF8&pf_rd_t=501&ref_=amb_link_83957571_2&pf_rd_m=ATVPDKIKX0DER&pf_rd_p=&pf_rd_s=assoc-center-1&pf_rd_r=&pf_rd_i=assoc-api-detail-2-v2
    // you can set it with the setter function or as the fourth paramameter of ther constructor above
    $amazonEcs->associateTag(AWS_ASSOCIATE_TAG);

   
    $response = $amazonEcs->lookup('B005GS3C9C');
   var_dump($response);

}
catch(Exception $e)
{
  echo $e->getMessage();
}

if ("cli" !== PHP_SAPI)
{
    echo "</pre>";
}


/*

$public_key = 'AKIAI4SE5JGMRBAIRVSA';
$private_key = 'HIFjqi0580ZtQjwpHlZHYhxnw1OXu8o5hu+BWB1i';
$associate_tag = 'dt004d-20';

// generate signed URL
$request = aws_signed_request('com', array(
        'Operation' => 'ItemLookup',
        'ItemId' => 'B002M78JA2'), $public_key, $private_key, $associate_tag);

// do request (you could also use curl etc.)
$response = @file_get_contents($request);
if ($response === FALSE) {
    echo "Request failed.\n";
} else {
    // parse XML
    $pxml = simplexml_load_string($response);
    
    
    var_dump($pxml);
    if ($pxml === FALSE) {
        echo "Response could not be parsed.\n";
    } else {
        if (isset($pxml->Items->Item->ItemAttributes->Title)) {
            echo $pxml->Items->Item->ItemAttributes->Title, "\n";
        }
    }
}
*/

?>
<a href="http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&Operation=ItemLookup&ResponseGroup=Offers&IdType=ASIN&ItemId=B001FA1O0O&AssociateTag=[Your_AssociateTag]&AWSAccessKeyId=AKIAI4SE5JGMRBAIRVSA&Timestamp=<?php echo date('YYYY-MM-DDTHH:mm:ssZ');?>&Signature=[Request_Signature]">Link Product</a>
    
