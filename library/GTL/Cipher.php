<?php

class GTL_Cipher {

    //Salt of the Cipher
    protected $salt;

    //Constructor
    public function __construct() {
	//Default Salt
	$this->salt = "ph0tOb08932K$#@";
    }

    //Setter for the Salt property
    public function setSalt($salt) {
	$this->salt = $salt;
    }

    //Getter for the Salt property
    public function getSalt() {
	return $this->salt;
    }


    public function encrypt($text) {
	return trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->getSalt(), $text, MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
    }

    public function decrypt($text) {
	return trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->getSalt(), base64_decode($text), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND)));
    }

}
