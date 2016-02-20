<?php
class Curl
{
    public static function get($address) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $address);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        if (0 === strpos(bin2hex($result), 'efbbbf')) {
            $result = substr($result, 3);
        }
        curl_close($curl);
        return $result;
    }
}