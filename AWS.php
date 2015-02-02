<?php

error_reporting(E_ALL);

require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);



var_dump('http://www.colombia.com/includes/2007/enlaces/actualidad_indicadores.js');
$html = file_get_contents('http://www.colombia.com/includes/2007/enlaces/actualidad_indicadores.js');
var_dump($html);
$patron = '/var IndDolTRM = "(.*)"/';
preg_match_all($patron, $html, $coincidencias);

$Items = array();

$TRM =  $coincidencias[1][0];

$TRM = reset(explode('.', $TRM));
$TRM = str_replace(',', '.', $TRM);

var_dump('Precio Base Dolar: ' . $TRM);

var_dump(explode(',', $TRM));

$NewValueRoundUp50_ = explode(',', $TRM);

$NewValueRoundUp50 = substr($NewValueRoundUp50_[0], -2);



var_dump('Precio Redondeado Dolar: '.$NewValueRoundUp50);
if ($NewValueRoundUp50 < 50) {

    $NewValueRoundUp50 = substr($NewValueRoundUp50_[0], 0, -2) . '50';
} else {
    $NewValueRoundUp50 = (substr($NewValueRoundUp50_[0], 0, -2) + 1) . '00';
}

$NewValueRoundUp50 = str_replace('.','',$NewValueRoundUp50);
var_dump('Nuevo precio dolar: ' . $NewValueRoundUp50);
//die($NewValueRoundUp50);


$productsCollection = Mage::getResourceModel('catalog/product_collection')
        ->addAttributeToFilter('amazon_link', array('notnull' => true))
        ->setCurPage(1)->setPageSize(100)
        ->load();

echo '<pre>';
//while ($row = $result->fetch()) {
foreach ($productsCollection as $product) {
    //   //var_dump($row);
    //var_dump($row['link']);
    $row = $product->getData();
    $code = explode('/dp/', $row['amazon_link']);

    //var_dump($code);
    //var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);



    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
    //var_dump($html);
// Find all article blocks

    $patron = '/<b>Price:(.*)&nbsp;</';
    preg_match_all($patron, $html, $coincidencias);
    var_dump($coincidencias);
    $Items = array();

    foreach ($coincidencias as $key => $value) {
        $Items[] = str_replace('</b>&nbsp;$', '', $value);
    }
    
    
    $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    var_dump($Items);
    var_dump('---->' . count($Items[1]) . '<------');
    if (count($Items[1]) < 1) {
        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
        //var_dump($html);
// Find all article blocks

        $patron = '/<b>Price:(.*)&nbsp;</';
        preg_match_all($patron, $html, $coincidencias);
        var_dump($coincidencias);

        $Items = array();
        foreach ($coincidencias as $key => $value) {
            $Items[] = str_replace('</b>&nbsp;$', '', $value);
        }

       $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
        var_dump($Items);

        if (count($Items[1]) < 1) {
            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
            //var_dump($html);
// Find all article blocks

            $patron = '/<b>Price:(.*)&nbsp;</';
            preg_match_all($patron, $html, $coincidencias);
            var_dump($coincidencias);
            $Items = array();

            foreach ($coincidencias as $key => $value) {
                $Items[] = str_replace('</b>&nbsp;$', '', $value);
            }

            $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
            var_dump($Items);

            if (count($Items[1]) < 1) {
                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                //var_dump($html);
// Find all article blocks

                $patron = '/<b>Price:(.*)&nbsp;</';
                preg_match_all($patron, $html, $coincidencias);
                var_dump($coincidencias);

                $Items = array();
                foreach ($coincidencias as $key => $value) {
                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                }

                var_dump($Items);
            $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    

                if (count($Items[1]) < 1) {
                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                    //var_dump($html);
// Find all article blocks

                    $patron = '/<b>Price:(.*)&nbsp;</';
                    preg_match_all($patron, $html, $coincidencias);
                    var_dump($coincidencias);
                    $Items = array();

                    foreach ($coincidencias as $key => $value) {
                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                    }

                  $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                    var_dump($Items);
                    if (count($Items[1]) < 1) {
                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                        //var_dump($html);
// Find all article blocks

                        $patron = '/<b>Price:(.*)&nbsp;</';
                        preg_match_all($patron, $html, $coincidencias);
                        var_dump($coincidencias);
                        $Items = array();

                        foreach ($coincidencias as $key => $value) {
                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                        }

                       $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                        var_dump($Items);
                        if (count($Items[1]) < 1) {
                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                            //var_dump($html);
// Find all article blocks

                            $patron = '/<b>Price:(.*)&nbsp;</';
                            preg_match_all($patron, $html, $coincidencias);
                            var_dump($coincidencias);
                            $Items = array();

                            foreach ($coincidencias as $key => $value) {
                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
                            var_dump($Items);
                            if (count($Items[1]) < 1) {
                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                //var_dump($html);
// Find all article blocks

                                $patron = '/<b>Price:(.*)&nbsp;</';
                                preg_match_all($patron, $html, $coincidencias);
                                var_dump($coincidencias);
                                $Items = array();

                                foreach ($coincidencias as $key => $value) {
                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                }

                               $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
                                var_dump($Items);
                                if (count($Items[1]) < 1) {
                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                    //var_dump($html);
// Find all article blocks

                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                    preg_match_all($patron, $html, $coincidencias);
                                    var_dump($coincidencias);

                                    $Items = array();
                                    foreach ($coincidencias as $key => $value) {
                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                    }
                                    
                                $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    

                                    var_dump($Items);
                                    if (count($Items[1]) < 1) {
                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                        //var_dump($html);
// Find all article blocks

                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                        preg_match_all($patron, $html, $coincidencias);
                                        var_dump($coincidencias);

                                        $Items = array();
                                        foreach ($coincidencias as $key => $value) {
                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                        }

                                        $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                        var_dump($Items);
                                        if (count($Items[1]) < 1) {
                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                            //var_dump($html);
// Find all article blocks

                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                            preg_match_all($patron, $html, $coincidencias);
                                            var_dump($coincidencias);

                                            $Items = array();
                                            foreach ($coincidencias as $key => $value) {
                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                            var_dump($Items);
                                            if (count($Items[1]) < 1) {
                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                //var_dump($html);
// Find all article blocks

                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                preg_match_all($patron, $html, $coincidencias);
                                                var_dump($coincidencias);

                                                $Items = array();
                                                foreach ($coincidencias as $key => $value) {
                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                }

                                                $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                var_dump($Items);
                                                if (count($Items[1]) < 1) {
                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                    //var_dump($html);
// Find all article blocks

                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                    preg_match_all($patron, $html, $coincidencias);
                                                    var_dump($coincidencias);

                                                    $Items = array();
                                                    foreach ($coincidencias as $key => $value) {
                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                    }
                                                   
                                                    
                                                    $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    

                                                    var_dump($Items);
                                                    if (count($Items[1]) < 1) {
                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                        //var_dump($html);
// Find all article blocks

                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                        preg_match_all($patron, $html, $coincidencias);
                                                        var_dump($coincidencias);

                                                        $Items = array();
                                                        foreach ($coincidencias as $key => $value) {
                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                        }

                                                        $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
                                                        var_dump($Items);

                                                        if (count($Items[1]) < 1) {
                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                            //var_dump($html);
// Find all article blocks

                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                            preg_match_all($patron, $html, $coincidencias);
                                                            var_dump($coincidencias);

                                                            $Items = array();
                                                            foreach ($coincidencias as $key => $value) {
                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                            }

                                                            $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                            var_dump($Items);

                                                            if (count($Items[1]) < 1) {
                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                //var_dump($html);
// Find all article blocks

                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                preg_match_all($patron, $html, $coincidencias);
                                                                var_dump($coincidencias);

                                                                $Items = array();
                                                                foreach ($coincidencias as $key => $value) {
                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                }

                                                                $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                var_dump($Items);

                                                                if (count($Items[1]) < 1) {
                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                    //var_dump($html);
// Find all article blocks

                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                    var_dump($coincidencias);

                                                                    $Items = array();
                                                                    foreach ($coincidencias as $key => $value) {
                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                    }

                                                                    $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                    var_dump($Items);
                                                                    if (count($Items[1]) < 1) {
                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                        //var_dump($html);
// Find all article blocks

                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                        var_dump($coincidencias);

                                                                        $Items = array();
                                                                        foreach ($coincidencias as $key => $value) {
                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                        }

                                                                        $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                        var_dump($Items);
                                                                        if (count($Items[1]) < 1) {
                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                            //var_dump($html);
// Find all article blocks

                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                            var_dump($coincidencias);

                                                                            $Items = array();
                                                                            foreach ($coincidencias as $key => $value) {
                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                            var_dump($Items);
                                                                            if (count($Items[1]) < 1) {
                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                //var_dump($html);
// Find all article blocks

                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                var_dump($coincidencias);

                                                                                $Items = array();
                                                                                foreach ($coincidencias as $key => $value) {
                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                var_dump($Items);
                                                                                if (count($Items[1]) < 1) {
                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                    //var_dump($html);
// Find all article blocks

                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                    var_dump($coincidencias);

                                                                                    $Items = array();
                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                    }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                    var_dump($Items);
                                                                                    if (count($Items[1]) < 1) {
                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                        //var_dump($html);
// Find all article blocks

                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                        var_dump($coincidencias);

                                                                                        $Items = array();
                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                        var_dump($Items);
                                                                                        if (count($Items[1]) < 1) {
                                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                            //var_dump($html);
// Find all article blocks

                                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                                            var_dump($coincidencias);

                                                                                            $Items = array();
                                                                                            foreach ($coincidencias as $key => $value) {
                                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                            }

                                                                                            $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                            var_dump($Items);
                                                                                            if (count($Items[1]) < 1) {
                                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                //var_dump($html);
// Find all article blocks

                                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                                var_dump($coincidencias);

                                                                                                $Items = array();
                                                                                                foreach ($coincidencias as $key => $value) {
                                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                }

                                                                                                $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                var_dump($Items);
                                                                                                if (count($Items[1]) < 1) {
                                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                    //var_dump($html);
// Find all article blocks

                                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                                    var_dump($coincidencias);

                                                                                                    $Items = array();
                                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                    }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                    var_dump($Items);
                                                                                                    if (count($Items[1]) < 1) {
                                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                        //var_dump($html);
// Find all article blocks

                                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                                        var_dump($coincidencias);

                                                                                                        $Items = array();
                                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                        var_dump($Items);
                                                                                                        if (count($Items[1]) < 1) {
                                                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                            //var_dump($html);
// Find all article blocks

                                                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                                                            var_dump($coincidencias);

                                                                                                            $Items = array();
                                                                                                            foreach ($coincidencias as $key => $value) {
                                                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                            }

                                                                                                            $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                            var_dump($Items);
                                                                                                            if (count($Items[1]) < 1) {
                                                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                //var_dump($html);
// Find all article blocks

                                                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                                                var_dump($coincidencias);

                                                                                                                $Items = array();
                                                                                                                foreach ($coincidencias as $key => $value) {
                                                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                var_dump($Items);

                                                                                                                if (count($Items[1]) < 1) {
                                                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                    //var_dump($html);
// Find all article blocks

                                                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                                                    var_dump($coincidencias);

                                                                                                                    $Items = array();
                                                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                    }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                    var_dump($Items);
                                                                                                                    if (count($Items[1]) < 1) {
                                                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                        //var_dump($html);
// Find all article blocks

                                                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                                                        var_dump($coincidencias);

                                                                                                                        $Items = array();
                                                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                        var_dump($Items);
                                                                                                                        if (count($Items[1]) < 1) {
                                                                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                            //var_dump($html);
// Find all article blocks

                                                                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                                                                            var_dump($coincidencias);

                                                                                                                            $Items = array();
                                                                                                                            foreach ($coincidencias as $key => $value) {
                                                                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
    
                                                                                                                            var_dump($Items);
                                                                                                                            if (count($Items[1]) < 1) {
                                                                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                //var_dump($html);
// Find all article blocks

                                                                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                                                                var_dump($coincidencias);

                                                                                                                                $Items = array();
                                                                                                                                foreach ($coincidencias as $key => $value) {
                                                                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                }

                                                                                                                                $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                var_dump($Items);
                                                                                                                                if (count($Items[1]) < 1) {
                                                                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                    //var_dump($html);
// Find all article blocks

                                                                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                                                                    var_dump($coincidencias);

                                                                                                                                    $Items = array();
                                                                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                    }

                                                                                                                                    $patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                    var_dump($Items);
                                                                                                                                    if (count($Items[1]) < 1) {
                                                                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                        //var_dump($html);
// Find all article blocks

                                                                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                                                                        var_dump($coincidencias);

                                                                                                                                        $Items = array();
                                                                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                        var_dump($Items);
                                                                                                                                        if (count($Items[1]) < 1) {
                                                                                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                            //var_dump($html);
// Find all article blocks

                                                                                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                                                                                            var_dump($coincidencias);

                                                                                                                                            $Items = array();
                                                                                                                                            foreach ($coincidencias as $key => $value) {
                                                                                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                            var_dump($Items);
                                                                                                                                            if (count($Items[1]) < 1) {
                                                                                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                //var_dump($html);
// Find all article blocks

                                                                                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                var_dump($coincidencias);

                                                                                                                                                $Items = array();
                                                                                                                                                foreach ($coincidencias as $key => $value) {
                                                                                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                var_dump($Items);
                                                                                                                                                if (count($Items[1]) < 1) {
                                                                                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                    //var_dump($html);
// Find all article blocks

                                                                                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                    var_dump($coincidencias);

                                                                                                                                                    $Items = array();
                                                                                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                    }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                    var_dump($Items);
                                                                                                                                                    if (count($Items[1]) < 1) {
                                                                                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                        //var_dump($html);
// Find all article blocks

                                                                                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                        var_dump($coincidencias);

                                                                                                                                                        $Items = array();
                                                                                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                        var_dump($Items);
                                                                                                                                                        if (count($Items[1]) < 1) {
                                                                                                                                                            var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                            $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                            //var_dump($html);
// Find all article blocks

                                                                                                                                                            $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                            preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                            var_dump($coincidencias);

                                                                                                                                                            $Items = array();
                                                                                                                                                            foreach ($coincidencias as $key => $value) {
                                                                                                                                                                $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                            }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                            var_dump($Items);
                                                                                                                                                            if (count($Items[1]) < 1) {
                                                                                                                                                                var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                //var_dump($html);
// Find all article blocks

                                                                                                                                                                $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                                preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                                var_dump($coincidencias);

                                                                                                                                                                $Items = array();
                                                                                                                                                                foreach ($coincidencias as $key => $value) {
                                                                                                                                                                    $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                                }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                                var_dump($Items);
                                                                                                                                                                if (count($Items[1]) < 1) {
                                                                                                                                                                    var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                    $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                    //var_dump($html);
// Find all article blocks

                                                                                                                                                                    $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                                    preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                                    var_dump($coincidencias);

                                                                                                                                                                    $Items = array();
                                                                                                                                                                    foreach ($coincidencias as $key => $value) {
                                                                                                                                                                        $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                                    }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                                    var_dump($Items);
                                                                                                                                                                    if (count($Items[1]) < 1) {
                                                                                                                                                                        var_dump('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                        $html = file_get_contents('http://www.amazon.com/gp/aw/d/' . $code[1]);
                                                                                                                                                                        //var_dump($html);
// Find all article blocks

                                                                                                                                                                        $patron = '/<b>Price:(.*)&nbsp;</';
                                                                                                                                                                        preg_match_all($patron, $html, $coincidencias);
                                                                                                                                                                        var_dump($coincidencias);

                                                                                                                                                                        $Items = array();
                                                                                                                                                                        foreach ($coincidencias as $key => $value) {
                                                                                                                                                                            $Items[] = str_replace('</b>&nbsp;$', '', $value);
                                                                                                                                                                        }
$patron_ = '"&nbsp;<br />+(.*?)shipping<br />"';
    preg_match($patron_, $html, $coincidencias_);
    
    $PrecioShipping = str_replace('$','',trim(str_replace('+','',$coincidencias_[1])));
    
                                                                                                                                                                        var_dump($Items);
                                                                                                                                                                    }
                                                                                                                                                                }
                                                                                                                                                            }
                                                                                                                                                        }
                                                                                                                                                    }
                                                                                                                                                }
                                                                                                                                            }
                                                                                                                                        }
                                                                                                                                    }
                                                                                                                                }
                                                                                                                            }
                                                                                                                        }
                                                                                                                    }
                                                                                                                }
                                                                                                            }
                                                                                                        }
                                                                                                    }
                                                                                                }
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    // get product model by product id, assuming you have known product id, $productId
    $_product = Mage::getModel('catalog/product')->load($row['entity_id']);



    $NuevoPrecio = $Items[1][0];



    $PrecioImportacion = $_product->getResource()->getAttribute('precio_importacion')->getFrontend()->getValue($_product);
    $PrecioEnvioNacional = $_product->getResource()->getAttribute('envionacional')->getFrontend()->getValue($_product);

    var_dump('Nuevo precio del articulo '.(int) round($NuevoPrecio));
    var_dump('Precio de Importacion '.(int) round($PrecioImportacion));
    var_dump('Nuevo precio del dolar redondeado '.(int) round($NewValueRoundUp50));

    $NuevoPrecioCalculado = (int) round($NuevoPrecio) + (int) round($PrecioImportacion) + (int) round($PrecioShipping);
    var_dump($NuevoPrecioCalculado);

    $New_Price = (($NuevoPrecioCalculado * $NewValueRoundUp50) + 10000) + (int) $PrecioEnvioNacional;

    var_dump($New_Price);
    $_product->setPrice($New_Price)->save();

    //var_dump($html);
}
echo '</pre>';
?>