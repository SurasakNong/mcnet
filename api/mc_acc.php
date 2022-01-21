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
include_once 'objects/mc.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
 
// get database connection
$database = new Database();
$db = $database->getConnection();

$myclass = new Mc($db);
$data = json_decode(file_get_contents("php://input"));
$acc=isset($data->acc) ? $data->acc : "";
$jwt=isset($data->jwt) ? $data->jwt : "";
if($jwt){ 
    // if decode succeed, show user details
    try { 
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // decode jwt
        if(!empty($acc) && $acc == "add"){ //ทำการเพิ่มข้อมูล
            $myclass->mc = strtoupper($data->mc);
            $myclass->group_id = $data->group_id;
            $myclass->shift_id = $data->shift_id; 
            $myclass->mc_rpm = $data->mc_rpm;

            $myclass->cust = strtoupper($data->cust); 
            $myclass->popi = strtoupper($data->popi); 
            $myclass->ord = strtoupper($data->ord); 
            $myclass->item = $data->item; 
            $myclass->dia = $data->dia; 
            $myclass->color = strtoupper($data->color); 
            $myclass->ms = $data->ms; 
            $myclass->md = $data->md; 
            $myclass->ml_mt = $data->ml_mt; 
            $myclass->ml_kn = $data->ml_kn; 

            $myclass->mc_used = $data->mc_used;
            if( !empty($myclass->mc)){
                if($myclass->nameExists()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Mc Exit."));
                }else {
                    $myclass->create();
                    http_response_code(200);
                    echo json_encode(array("message" => "Mc was created."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to create Mc."));
            } 
        }else if(!empty($acc) && $acc == "up"){ //ปรับปรุงแก้ไขข้อมูล
            $myclass->id_mc = $data->id_mc;
            $myclass->mc = strtoupper($data->mc);
            $myclass->group_id = $data->group_id;
            $myclass->shift_id = $data->shift_id;
            $myclass->mc_rpm = $data->mc_rpm; 

            $myclass->cust = strtoupper($data->cust); 
            $myclass->popi = strtoupper($data->popi); 
            $myclass->ord = strtoupper($data->ord); 
            $myclass->item = $data->item; 
            $myclass->dia = $data->dia; 
            $myclass->color = strtoupper($data->color); 
            $myclass->ms = $data->ms; 
            $myclass->md = $data->md; 
            $myclass->ml_mt = $data->ml_mt; 
            $myclass->ml_kn = $data->ml_kn; 

            $myclass->mc_used = $data->mc_used;           

            if( !empty($myclass->mc)){
                if($myclass->newnameExit()){
                    http_response_code(400); 
                    echo json_encode(array("message" => "Mc Exit."));
                }else {
                    $myclass->update();
                    http_response_code(200);
                    echo json_encode(array("message" => "Mc was update."));
                }
            }else{ 
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to update Mc."));
            }
        }else if(!empty($acc) && $acc == "del"){ //ลบข้อมูล           
            $myclass->id_mc = $data->id;
           if(!empty($myclass->id_mc) && $myclass->delete()){                               
                http_response_code(200);
                echo json_encode(array("message" => "Mc was delete."));                
           }else{
                http_response_code(400); 
                echo json_encode(array("message" => "Unable to delete Mc."));
           }   

        }else{
            http_response_code(400); 
            echo json_encode(array("message" => "Unable to access Mc."));
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

