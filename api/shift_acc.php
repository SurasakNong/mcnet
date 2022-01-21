<?php
include_once 'config/core.php';
//===== required headers
header("Access-Control-Allow-Origin: ${home}");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
  
// database connection will be here
// files needed to connect to database
include_once 'config/database.php';
include_once 'objects/shift.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

$myclass = new Shift($db);
$data = json_decode(file_get_contents("php://input"));
$acc=isset($data->acc) ? $data->acc : "";
$jwt=isset($data->jwt) ? $data->jwt : "";
if($jwt){ 
    // if decode succeed, show user details
    try { 
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // decode jwt
        if(!empty($acc) && $acc == "add"){ //ทำการเพิ่มข้อมูล
            $myclass->shift_name = $data->shift_name;
            $myclass->shift_count = $data->shift_count;
            if($myclass->shift_count == "1"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
            }else if($myclass->shift_count == "2"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
                $myclass->shift_be2 = $data->shift_be2;
                $myclass->shift_en2 = $data->shift_en2;
            }else if($myclass->shift_count == "3"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
                $myclass->shift_be2 = $data->shift_be2;
                $myclass->shift_en2 = $data->shift_en2;
                $myclass->shift_be3 = $data->shift_be3;
                $myclass->shift_en3 = $data->shift_en3;
            }
            

            if( !empty($myclass->shift_name)){
                if($myclass->nameExists()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Shift Exit."));
                }else {
                    $myclass->create();
                    http_response_code(200);
                    echo json_encode(array("message" => "Shift was created."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to create Shift."));
            } 
        }else if(!empty($acc) && $acc == "up"){ //ปรับปรุงแก้ไขข้อมูล
            $myclass->shift_id = $data->shift_id;
            $myclass->shift_name = $data->shift_name;
            $myclass->shift_count = $data->shift_count;
            if($myclass->shift_count == "1"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
            }else if($myclass->shift_count == "2"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
                $myclass->shift_be2 = $data->shift_be2;
                $myclass->shift_en2 = $data->shift_en2;
            }else if($myclass->shift_count == "3"){
                $myclass->shift_be1 = $data->shift_be1;
                $myclass->shift_en1 = $data->shift_en1;
                $myclass->shift_be2 = $data->shift_be2;
                $myclass->shift_en2 = $data->shift_en2;
                $myclass->shift_be3 = $data->shift_be3;
                $myclass->shift_en3 = $data->shift_en3;
            }           

            if( !empty($myclass->shift_name)){
                if($myclass->newnameExit()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Shift Exit."));
                }else {
                    $myclass->update();
                    http_response_code(200);
                    echo json_encode(array("message" => "Shift was update."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to update Shift."));
            }

        }else if(!empty($acc) && $acc == "del"){ //ลบข้อมูล           
            $myclass->shift_id = $data->id;
           if(!empty($myclass->shift_id) && $myclass->delete()){                               
                http_response_code(200);
                echo json_encode(array("message" => "Shift was delete."));                
           }else{
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to delete Shift."));
           }   

        }else{
            http_response_code(400); 
            echo json_encode(array("message" => "Unable to access Shift."));
        }
    }
    
    catch (Exception $e){    // if decode fails, it means jwt is invalid
        http_response_code(401);    
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
}else{  // show error message if jwt is empty
    http_response_code(401); 
    echo json_encode(array("message" => "Access denied."));
}


?>

