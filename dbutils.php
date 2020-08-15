<?php
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS');
	header('Access-Control-Allow-Headers: token, Content-Type');
	header('Access-Control-Max-Age: 86400');
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

$mysqli = null;
$config = Array(
"database" => Array("server" => 'dbserver', 
					"username" => 'username', 
					"password" => 'password', 
          "database" => 'database')
);
                    
if ($mysqli == null)
	{ 
		$mysqli = mysqli_connect($config["database"]["server"], $config["database"]["username"], $config["database"]["password"], $config["database"]["database"]); 
		if (mysqli_connect_errno()) {
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
		}
  }  




function PrepareExecSQL($sql, $pars = '', $params = [])
{	
  global $mysqli;
	$result = db_query($mysqli, $sql, $pars, $params);
	return $result;
}

// https://stackoverflow.com/questions/24363755/mysqli-bind-results-to-an-array
function db_query($dbconn, $sql, $params_types, $params) { // pack dynamic number of remaining arguments into array
    // GET QUERY TYPE
    $query_type = strtoupper(substr(trim($sql), 0, 4));
  
    $stmt = mysqli_stmt_init($dbconn);
    if ( mysqli_stmt_prepare($stmt, $sql) ) {
        if ($params_types != "")
        {
          mysqli_stmt_bind_param($stmt, $params_types, ...$params); // unpack
        }
      mysqli_stmt_execute($stmt);
  
      if ( 'SELE' == $query_type || '(SEL' == $query_type ) {
          
        $result = mysqli_stmt_result_metadata($stmt);
        list($columns, $columns_vars) = array(array(), array());
        while ( $field = mysqli_fetch_field($result) ) {
          $columns[] = $field->name;
          $columns_vars[] = &${$field->name};
        }
        call_user_func_array('mysqli_stmt_bind_result', array_merge(array($stmt), $columns_vars));
        $return_array = array();
        while ( mysqli_stmt_fetch($stmt) ) {
          $row = array();
          foreach ( $columns as $col ) {
            $row[$col] = ${$col};
          }
          $return_array[] = $row;
        }
  
        return $return_array;
      } // end query_type SELECT
  
      else if ( 'INSE' == $query_type ) {
        return mysqli_insert_id($dbconn);
      }
      return 1;
    }
  }

function lastError() {
  global $mysqli;
  return mysqli_error($mysqli);
}
?>