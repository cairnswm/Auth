<?php

include_once "security.config.php";
include_once "dbutils.php";

// TODO: Send welcome email

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

$firstName = '';
$lastName = '';
$email = '';
$password = '';
$confirm = "";
$conn = null;

$res = "";
$errors = array();
$input = file_get_contents("php://input");
$data = json_decode($input);

if (isset($_GET["firstName"])) $firstName = $_GET["firstName"];
if (isset($_GET["lastName"])) $lastName = $_GET["lastName"];
if (isset($_GET["email"])) $email = $_GET["email"];
if (isset($_GET["password"])) $password = $_GET["password"];
if (isset($_GET["confirm"])) $confirm = $_GET["confirm"];
if (isset( $data->firstName)) $firstName = $data->firstName;
if (isset( $data->lastName)) $lastName = $data->lastName;
if (isset( $data->email)) $email = $data->email;
if (isset( $data->password)) $password = $data->password;
if (isset( $data->confirm)) $confirm = $data->confirm;

$canRegister = true;

if ($firstName == "" || $lastName == "")
{
    array_push($errors,array("message" => "First name and last name are both Required."));
    $canRegister = false;
}
if ($email == "")
{
    array_push($errors,array("message" => "Email is Required."));
    $canRegister = false;
}
if ($confirm == "" || $confirm != $password)
{
    array_push($errors,array("message" => "Passwords do not match."));
    $canRegister = false;
}

try {
    if ($canRegister) {
        // Check if email exists
        $sql = "SELECT id FROM " . $usertable . " WHERE email = ?";
        $params = array($email);
        $row = PrepareExecSQL($sql,"s",$params);
        if ($row[0]["id"] > 0) {
            throw new Exception('EMail has already been registered.');
        }

        $sql = "INSERT INTO " . $usertable . " SET first_name = ?, last_name = ?, email = ?, password = ?";
        
        $password_hash = crypt($password, $PasswordHaskKey);
        $params = array( $firstName,$lastName,$email,$password_hash);
        //echo $sql;
        //var_dump($params);
        $id = PrepareExecSQL($sql,"ssss",$params);
        //echo "User ID: ".$id;
        if ($id > 0) {
            
            $token = createToken(array("id" => $id,"firstname" => $firstName,"lastname" => $lastName));
            $res = json_encode(array("message" => "User was successfully registered.","userid" => $id,"firstname" => $firstName,"lastname" => $lastName,"token"=>$token,"permissions"=>array()));

            // create profileid
            if ($profiletable != "") {
                $sql = "INSERT INTO " + $profiletable + " SET name = ?";
                $name = $firstName.' '.$lastName;
                $params = array($name);
                $profileid = PrepareExecSQL($sql,"s",$params);
                if ($profileid > 0) {
                    $sql = "UPDATE ".$table_name." SET profileid = ? where id = ?";
                    $params = array($profileid,$id);
                    $profileid = PrepareExecSQL($sql,"ss",$params);
                    http_response_code(200);
                } else {
                    //echo "No profile created";
                    array_push($errors,array("message" => "Could not create user."));
                    array_push($errors,array("dberror" => lastError()));
                }
            }
            else {
                // TODO Create info here is a different profile table is used for personal info
            }
        } else {
            //echo "user not created in table";
            array_push($errors,array("message" => "Could not create user."));
            array_push($errors,array("dberror" => lastError()));
        }
    }
    else
    {
        $res = json_encode(array("errors" => $errors));
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