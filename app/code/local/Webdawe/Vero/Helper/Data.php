<?php
/**
 *
 * @category    Webdawe
 * @package     Webdawe_Vero
 * @author      Anil Paul
 * @copyright   Copyright (c) 2016 Webdawe
 * @license
 */
class Webdawe_Vero_Helper_Data extends Mage_Core_Helper_Abstract
{
    /**
     * Retrieve Camel Case Text of the underscored text
     * @param $str
     * @return mixed|string
     */
    public function getCamelCasedText($str)
    {
        $i = array("-","_");
        $str = preg_replace('/([a-z])([A-Z])/', "\\1 \\2", $str);
        $str = preg_replace('@[^a-zA-Z0-9\-_ ]+@', '', $str);
        $str = str_replace($i, ' ', $str);
        $str = ucwords(strtolower($str));
        return $str;
    }

    /**
     * Retrieve Camel Cased Text to Underscore Seperated
     * @param $str
     * @return string
     */
    function getCamelCasedToUnderscoreText($str) {
        $str = str_replace(' ','', $str);
        $glue = '_';
        $counter  = 0;
        $uc_chars = '';
        $new_str  = array();
        $str_len  = strlen($str);

        for ($x=0; $x<$str_len; ++$x)
        {
            $ascii_val = ord($str[$x]);

            if ($ascii_val >= 65 && $ascii_val <= 90)
            {
                $uc_chars .= $str[$x];
            }
        }

        $tok = strtok($str, $uc_chars);

        while ($tok !== false)
        {
            $new_char  = chr(ord($uc_chars[$counter]) + 32);
            $new_str[] = $new_char . $tok;
            $tok       = strtok($uc_chars);

            ++$counter;
        }

        return implode($new_str, $glue);
    }

    /**
     * Retireve Key Value Pair for the Given Keys
     * @param array $keyArray
     * @param array $valueArray
     * @param string $prepend
     * @return array
     */
    public function getKeyValueArrayFromGivenKeys($keyArray = array(), $valueArray = array(), $prepend = '')
    {
        if ($prepend)
        {
            $prepend = $prepend .'_';
        }

        $keyValue = array();
        foreach ($keyArray as $key)
        {
            if (array_key_exists($key, $valueArray))
            {

                $keyValue[$prepend . $key] = $valueArray[$key];
            }
        }

        return $keyValue;
    }


}