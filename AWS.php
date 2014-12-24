<?php

error_reporting(E_ALL);

require_once('app/Mage.php');
Mage::app('admin');
Mage::getSingleton("core/session", array("name" => "adminhtml"));
Mage::register('isSecureArea', true);

$obj_php = json_decode(file_get_contents('http://www.set-fx.com/stats?timestamp=1418426752515'));
$TRM = str_replace('.', '', $obj_php->trm);

var_dump('Precio Base Dolar: '.$TRM);

$NewValueRoundUp50_ = explode(',',$TRM);

$NewValueRoundUp50 = substr($NewValueRoundUp50_[0], -2);

var_dump('Precio Redondeado Dolar: '.$NewValueRoundUp50);
if($NewValueRoundUp50 < 50){
    
    $NewValueRoundUp50 = substr($NewValueRoundUp50_[0], 0, -2).'50';
}else{
    $NewValueRoundUp50 = (substr($NewValueRoundUp50_[0], 0, -2) + 1).'00';
}

//die($NewValueRoundUp50);


$sql = 'SELECT * FROM  amazon_products_amazon_product as app, amazon_products_amazon as aps WHERE aps.entity_id = app.amazon_id and  status = 1';
// fetch write database connection that is used in Mage_Core module
$write = Mage::getSingleton('core/resource')->getConnection('core_write');

// now $write is an instance of Zend_Db_Adapter_Abstract
$result = $write->query($sql);


echo '<pre>';
while ($row = $result->fetch()) {
    //var_dump($row);
    //var_dump($row['link']);

    $code = explode('/dp/', $row['link']);

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
    $_product = Mage::getModel('catalog/product')->load($row['product_id']);



    $NuevoPrecio = $Items[1];



    $PrecioImportacion = $_product->getResource()->getAttribute('precio_importacion')->getFrontend()->getValue($_product);


    var_dump((int) round($NuevoPrecio[0]));
    var_dump((int) round($PrecioImportacion));
    var_dump((int) round($TRM));

    $NuevoPrecioCalculado = (int) round($NuevoPrecio[0]) + (int) round($PrecioImportacion);
    var_dump($NuevoPrecioCalculado);

    $New_Price = ($NuevoPrecioCalculado * $TRM) + 10000;
    $_product->setPrice($New_Price)->save();

    //var_dump($html);
}
echo '</pre>';
?>