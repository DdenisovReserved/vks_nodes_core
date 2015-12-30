<?php
class Curl
{
    public static function get($address) {
//        $start = ST::microtime_float();
        $curl = curl_init();
//        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_URL, $address);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt($curl, CURLOPT_HTTPHEADER, array("X-Requested-With: XMLHttpRequest"));

        $result = curl_exec($curl);
//        dump(curl_getinfo($curl));
//        $end= ST::microtime_float()
//        $result = file_get_contents($address);
//        $time = $end - $start;
//        dump("k");

        if (0 === strpos(bin2hex($result), 'efbbbf')) {
            $result = substr($result, 3);
        }
        curl_close($curl);
        return $result;

    }
}