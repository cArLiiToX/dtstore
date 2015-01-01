<?php

error_reporting(E_ALL);

require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);



var_dump('http://dolar.wilkinsonpc.com.co/widgets/gratis/dolar-cop-usd-1.html');
$html = file_get_contents('http://dolar.wilkinsonpc.com.co/widgets/gratis/dolar-cop-usd-1.html');

$patron = '/<div id="widget_valor">(.*)</';
preg_match_all($patron, $html, $coincidencias);

$Items = array();

$TRM = str_replace(',', '', $coincidencias[1][0]);
$TRM = str_replace('.', ',', $TRM);
$TRM = str_replace('$', '', $TRM);


var_dump('Precio Base Dolar: ' . $TRM);

$NewValueRoundUp50_ = explode(',', $TRM);

$NewValueRoundUp50 = substr($NewValueRoundUp50_[0], -2);

//var_dump('Precio Redondeado Dolar: '.$NewValueRoundUp50);
if ($NewValueRoundUp50 < 50) {

    $NewValueRoundUp50 = substr($NewValueRoundUp50_[0], 0, -2) . '50';
} else {
    $NewValueRoundUp50 = (substr($NewValueRoundUp50_[0], 0, -2) + 1) . '00';
}
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



    $NuevoPrecio = $Items[1];



    $PrecioImportacion = $_product->getResource()->getAttribute('precio_importacion')->getFrontend()->getValue($_product);
    $PrecioEnvioNacional = $_product->getResource()->getAttribute('envionacional')->getFrontend()->getValue($_product);

    var_dump((int) round($NuevoPrecio[0]));
    var_dump((int) round($PrecioImportacion));
    var_dump((int) round($NewValueRoundUp50));

    $NuevoPrecioCalculado = (int) round($NuevoPrecio[0]) + (int) round($PrecioImportacion);
    var_dump($NuevoPrecioCalculado);

    $New_Price = (($NuevoPrecioCalculado * $NewValueRoundUp50) + 10000) + (int) $PrecioEnvioNacional;

    var_dump($New_Price);
    $_product->setPrice($New_Price)->save();

    //var_dump($html);
}
echo '</pre>';
?>