<?php
include_once "dbutils.php";
include_once "security.config.php";
// include_once "<YOUR>sendemail.php";

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
	header('Access-Control-Allow-Headers: token, Content-Type');
	header('Access-Control-Max-Age: 0');
	header('Content-Length: 0');
	header('Content-Type: application/json');
	header("Access-Control-Allow-Headers: token, Origin, X-Requested-With, Content-Type, Accept");
	die();
}
else
{
	header("Access-Control-Allow-Origin: *");		
	header('Access-Control-Max-Age: 86400');    // cache for 1 day
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
	header("Access-Control-Allow-Headers: token, X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
    header('Access-Control-Allow-Credentials: true');
}

$email = '';
$conn = null;

$res = "";
$errors = array();
$input = file_get_contents("php://input");
$data = json_decode($input);

if (isset($_GET["email"])) $email = $_GET["email"];
if (isset( $data->email)) $email = $data->email;

if ($email == "")
{
    array_push($errors,array("message" => "Email is Required."));
    $canRegister = false;
}

try {
    // Check if email exists
    $sql = "SELECT * FROM " . $usertable . " WHERE email = ?";
    $params = array($email);
    $row = PrepareExecSQL($sql,"s",$params);
    if ($row[0]["id"] == 0) {
        throw new Exception('EMail does not exist.');
    }
    $password = randomPassword($passwordlength);

    $sql = "UPDATE " . $usertable . " SET password = ? where email = ?";
    
    $password_hash = crypt($password, $PASSWORDHASH);
    $params = array($password_hash, $email);
    $id = PrepareExecSQL($sql,"ss",$params);
    if ($id > 0) {
        // TODO: Send email
        //sendEMail($email,"Password reset","Hi ".$row["first_name"]."<br/><br/>Your new password is '".$password."<br/><br/>from<br/>Juzt.Dance");
        http_response_code(200);
        $res = json_encode(array("message" => "Password was updated.","token"=>$token));
    } else {
        array_push($errors,array("message" => "Could not create user."));
        array_push($errors,array("dberror" => lastError()));
    }
}
catch(Exception $e) {
    array_push($errors,array("message" => $e->getMessage()));
}

if (count($errors) > 0) {
    $res = json_encode(array("errors" => $errors));
} 


echo $res;

?>