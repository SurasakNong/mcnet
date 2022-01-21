<?php
include_once 'config/core.php';
//===== required headers
header("Access-Control-Allow-Origin: ${home}");
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
            $perpage = isset($_POST['perpage'])?(int)$_POST['perpage']:10;
            $page = isset($_POST['page'])?(int)$_POST['page']:1;
            $search = isset($_POST['search'])?$_POST['search']:"";
            $search = htmlspecialchars(strip_tags($search));
            $rowStart = ($page-1)*$perpage;
            // ข้อมูลที่ต้องการให้แสดง
            $sql = "SELECT id_mc,mc,mc.group_id,group_name,bd_name,mc.shift_id,shift_name,mc_rpm,mc_used,cust,popi,ord,item,dia,color,ms,md,ml_mt,ml_kn FROM (((mc INNER JOIN shift ON mc.shift_id = shift.shift_id) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id = bd.bd_id)  WHERE ( CONCAT(mc,' ',group_name,' ',bd_name,' ',shift_name,if(mc_used = '0',' ไม่ใช้',' ใช้งาน')) LIKE '%".$search."%') ORDER BY mc ASC LIMIT $rowStart , $perpage";
                $stmt = $db->prepare( $sql );  
                $stmt->execute();
                $num = $stmt->rowCount();   
                $resultArray = array();
                while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    array_push($resultArray,$row);
                }        // จำนวนข้อมูลทั้งหมด
                    $sql2 = "SELECT id_mc,mc,group_name,bd_name,shift_name FROM (((mc INNER JOIN shift ON mc.shift_id = shift.shift_id) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id = bd.bd_id) WHERE CONCAT(mc,' ',group_name,' ',bd_name,' ',shift_name,if(mc_used = '0',' ไม่ใช้',' ใช้งาน')) LIKE '%".$search."%'";
                    $stmt2 = $db->prepare( $sql2 );   
                    $stmt2->execute();
                   $numall = $stmt2->rowCount();  
                $allpage = ceil($numall/$perpage);
                $database = null; 
                echo json_encode(
                    array(
                        "data" => $resultArray,
                        "page_all" => $allpage
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



