<?php
session_start();
include_once "dbutils.php";
include_once "security.config.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: token, Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$email = '';
$password = '';

$res = '';
$errors = array();

$data = json_decode(file_get_contents("php://input"));
if (isset($_GET["email"])) $email = $_GET["email"];
if (isset($_GET["password"])) $password = $_GET["password"];
if (isset( $data->email)) $email = $data->email;
if (isset( $data->password)) $password = $data->password;

$sql = "SELECT id, firstname, lastname, password FROM " . $usertable . " WHERE email = ? LIMIT 0,1";
$params = array($email);	
$row = PrepareExecSQL($sql,"s",$params);

try {
    if (count($row) == 1) {
        $row = $row[0];
        $id = $row['id'];
        $firstname = $row['firstname'];
        $lastname = $row['lastname'];
        $password2 = $row['password'];

        $password_hash = crypt($password, $PasswordHaskKey);
        //echo $password_hash;

        if ($password_hash === $password2) {
            $_SESSION['email'] = $email;
            // get permissions
            $sql = "SELECT item, action FROM ".$userroletable." ur, ".$permissionstable." rp WHERE ur.userid = ? AND ur.roleid = rp.roleid";
            $params = array($id);	
            $permissions = PrepareExecSQL($sql,"s",$params);

            $jwt = createToken(array("id" => $id,"firstname" => $firstname,"lastname" => $lastname,"permissions" => $permissions)); 
            $res = json_encode(array("message" => "Login succeded.","id" => $id,"firstname" => $firstname,"lastname" => $lastname,"token" => $jwt,"permissions" => $permissions));
            // TODO: Record the key so that we can use it for future auto-login
        } else {
            array_push($errors,array("message" => "Login failed, invalid email or password"));
        }        
    } else {
        array_push($errors,array("message" => "Login failed, invalid email or password"));
    }
} catch(Exception $e) {
    array_push($errors,array("message" => $e->getMessage()));
}    

if (count($errors) > 0) {
    $res = json_encode(array("errors" => $errors));
} 
echo $res;
?>