<?php

Class AES 
{
    private static function get_key($file_of_key)
    {
        $__KEY = parse_ini_file($file_of_key);
        return $__KEY['encryption_key'];
    }

    public static function encrypt($data, $file_of_key)
    {   
        $key = self::get_key($file_of_key); // Get encryption key
        $key = base64_decode($key);
        $key = explode('.', $key);
        $key = $key[1];
        $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC"); // Gen iv lenght from cipher method name
        $iv = openssl_random_pseudo_bytes($ivlen); // Gen Initial Vector with random bytes
        $ciphertext_raw = openssl_encrypt($data, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); // Cipher in AES 256 CBC
        $hmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); // Hash in sha256 + key
        return base64_encode( $iv.$hmac.$ciphertext_raw ); // Encode in B64


    }

    public static function decrypt($data, $file_of_key)
    {
        $key = self::get_key($file_of_key); // Get encryption key
        $key = base64_decode($key);
        $key = explode('.', $key);
        $key = $key[1];
        $c = base64_decode($data); // Decode B64
        $ivlen = openssl_cipher_iv_length($cipher="AES-256-CBC"); // Get Iv Lenght
        $iv = substr($c, 0, $ivlen); // Get IV ( en le découpant de la chaine )
        $hmac = substr($c, $ivlen, $sha2len=32); // Get HMAC ( en le découpant de la chaine )
        $ciphertext_raw = substr($c, $ivlen+$sha2len); // Get encrypted text ( en le découpant de la chaine )
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv); // Decrypt AES 256 CBC
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true); // Hash everything
        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison ( compare les deux hashs )
        {
            return $original_plaintext;
        }
    }
}

?>