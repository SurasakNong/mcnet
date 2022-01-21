<?php
include_once 'config/core.php';
//===== required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
//===== files needed to connect to database
include_once 'config/database.php';

include_once 'libs/php-jwt-master/src/BeforeValidException.php';
include_once 'libs/php-jwt-master/src/ExpiredException.php';
include_once 'libs/php-jwt-master/src/SignatureInvalidException.php';
include_once 'libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;
// get database connection
$database = new Database(); 
$db = $database->getConnection();
$sql = "";
$jwt = isset($_POST['jwt'])?$_POST['jwt']:"";
if(!empty($jwt)){
    try{
        $decoded = JWT::decode($jwt, $key, array('HS256'));   // ถอดรหัส jwt    
            $datefm = isset($_POST['datefm'])?$_POST['datefm']:"";
            $dateto = isset($_POST['dateto'])?$_POST['dateto']:"";
            $search = isset($_POST['search'])?$_POST['search']:"";
            $depart = isset($_POST['depart'])?$_POST['depart']:"";            
            $search = htmlspecialchars(strip_tags($search));
            $depart = ($depart=="0")?"":"AND (bd.bd_id = '${depart}')";
            // ข้อมูลที่ต้องการให้แสดง
            $sql = "SELECT bd_name, group_name, mc, mc_rpm, SUM(meter1) AS meter1, SUM(on_t1) AS on_t1, SUM(all_t1) AS all_t1, SUM(meter2) AS meter2, SUM(on_t2) AS on_t2, SUM(all_t2) AS all_t2, SUM(meter3) AS meter3, SUM(on_t3) AS on_t3, SUM(all_t3) AS all_t3 FROM (
                SELECT bd.bd_name, group_mc.group_name, meter_mc.mc, meter_mc.mc_rpm, 
                if(shift_no=1,meter,0) AS meter1, 
                if(shift_no=2,meter,0) AS meter2, 
                if(shift_no=3,meter,0) AS meter3,
                if(shift_no=1,on_t,0) AS on_t1, 
                if(shift_no=2,on_t,0) AS on_t2, 
                if(shift_no=3,on_t,0) AS on_t3,
                if(shift_no=1,(on_t+top_t+mid_t+epa_t+bob_t+off_t),0) AS all_t1, 
                if(shift_no=2,(on_t+top_t+mid_t+epa_t+bob_t+off_t),0) AS all_t2, 
                if(shift_no=3,(on_t+top_t+mid_t+epa_t+bob_t+off_t),0) AS all_t3
                FROM (((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id) WHERE ((meter_date BETWEEN '${datefm}' AND '${dateto}') ${depart} AND CONCAT('กะ'&shift_no,' ',group_mc.group_name,' ',meter_mc.mc) LIKE '%${search}%') 
            ) AS TT
            GROUP BY mc ORDER BY mc ASC";
                $stmt = $db->prepare( $sql );  
                $stmt->execute();
                $num = $stmt->rowCount();   
                $resultArray = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($resultArray,$row);
                } 

                $database = null; 
                http_response_code(200);   
                echo json_encode(
                    array(
                        "data" => $resultArray
                    )
                );    
        
    }
    catch (Exception $e){    //ถอดรหัส JWT ไม่ถูกต้อง
        http_response_code(402);    
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        )); 
    }
}else{ //ไม่พบ JWT
    http_response_code(401); 
    echo json_encode(array("message" => "Access denied."));
}


?>



