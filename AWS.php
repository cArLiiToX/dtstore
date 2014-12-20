<?php
error_reporting(E_ALL);

include './domhtml/simple_html_dom.php';


$html = file_get_contents('http://www.amazon.com/gp/aw/d/B00CX5P8FC/');

// Find all article blocks

//var_dump($html);
$patron = '/<b>Price:(.*)&nbsp;</';
preg_match_all($patron, $html, $coincidencias);
//var_dump($coincidencias);


foreach ($coincidencias as $key => $value) {
    $Items[] = str_replace('</b>&nbsp;$', '', $value);
}

var_dump($Items);
/**
 * For a running Search Demo see: http://amazonecs.pixel-web.org
 */


/*$count = 0;

function ProductId_xml($searchTerm) {

    $params = array(
        'AWSAccessKeyId' => AWS_ACCESS_KEY_ID,
        'Action' => "GetMatchingProductForId",
        'SellerId' => MERCHANT_ID,
        'SignatureMethod' => "HmacSHA256",
        'SignatureVersion' => "2",
        'Timestamp' => gmdate("Y-m-d\TH:i:s.\\0\\0\\0\\Z", time()),
        'Version' => "2011-10-01",
        'MarketplaceId' => MARKETPLACE_ID,
    );

    $id = array(explode(',', $searchTerm));
    foreach ($id as $newId) {
        $count .= $count + 1;
        $params += array('IdList.Id.' . $count => $newId);
    } //end of foreach      
// Sort the URL parameters
    $url_parts = array();
    foreach (array_keys($params) as $key)
        $url_parts[] = $key . "=" . str_replace('%7E', '~', rawurlencode($params[$key]));
    sort($url_parts);

// Construct the string to sign
    $url_string = implode("&", $url_parts);
    $string_to_sign = "GET\nmws.amazonservices.com\n/Products/2011-10-01\n" . $url_string;

// Sign the request
    $signature = hash_hmac("sha256", $string_to_sign, AWS_SECRET_ACCESS_KEY, TRUE);

// Base64 encode the signature and make it URL safe
    $signature = urlencode(base64_encode($signature));

    $url = "https://mws.amazonservices.com/Products/2011-10-01" . '?' . $url_string . "&Signature=" . $signature;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 15);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    $response = curl_exec($ch);

    $parsed_xml = simplexml_load_string($response);

    return ($parsed_xml);
}
*/

/*
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


  $response = $amazonEcs->responseGroup('ItemAttributes')->lookup('B002M78JA2');
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
 */

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
<a href="http://webservices.amazon.com/onca/xml?Service=AWSECommerceService&Operation=ItemLookup&ResponseGroup=Offers&IdType=ASIN&ItemId=B001FA1O0O&AssociateTag=[Your_AssociateTag]&AWSAccessKeyId=AKIAI4SE5JGMRBAIRVSA&Timestamp=<?php echo date('YYYY-MM-DDTHH:mm:ssZ'); ?>&Signature=[Request_Signature]">Link Product</a>

