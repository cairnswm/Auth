<?php
include_once "jwt.php";

$SecretKey = "SuperSecretKeyDontShare";
$PasswordHaskKey = 'AnotherSuperSecretForHashingPasswordsThatShouldBeKeptSecret';
$defaultConfig = array("issuer"=>"GlyndwrCovidProject","subject"=>"user logged in","audience"=>"GlyndwrCovidWebsite");

function createToken($payload) {    
    global $SecretKey;
    jwt_set_secret($SecretKey);
    jwt_set_payload($payload); 
    $jwt = jwt_token();
    return $jwt;
}
function validateJwt($token,$time=false,$aud=NULL) {
    return validate_jwt($token,$time,$aud);
}

// Database access for Authentication
$usertable = "users";
$userroletable = "userrole";
$permissionstable = "rolepermissions";
$profiletable = ""; // optional
$passwordlength = 12;

function randomPassword($len = 10) {
    $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890'; // Add special chars if wanted
    $pass = array(); // declare $pass as an array
    $alphaLength = strlen($alphabet) - 1; 
    for ($i = 0; $i < $len; $i++) {
        $n = rand(0, $alphaLength);
        $pass[] = $alphabet[$n];
    }
    return implode($pass); //turn the array into a string
}
?>