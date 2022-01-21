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

            $where_txt = "WHERE(";
            $concat = "(CONCAT(IF(mc_status = 0,'เดิน',IF(mc_status = 1,'จอดด้ายบน',IF(mc_status = 2,'จอดด้ายขาด',IF(mc_status = 3,'จอดอีแปะ',IF(mc_status = 4,'จอดกระสวย','จอดอื่นๆ'))))),' ',mc,' ',cust,' ',popi,' ',ord,'-',item,' ',IF(LENGTH(mc) <= 4,CONCAT('อาคาร',left(mc,1)),CONCAT('อาคาร-',left(mc,2))),' กะ',shift_no) LIKE '%";
            $concat_set = "";
            if($search != ''){
                $search_ex = explode(" ",$search);
                $search_count = count($search_ex);                
                for($i=0; $i<$search_count; $i++){
                    $concat_set = $concat_set.$concat.$search_ex[$i]."%')";
                    if($i < ($search_count-1)){
                        $concat_set = $concat_set." AND ";
                    }
                }
                $where_txt = $where_txt.$concat_set.")";  
            }else{
                $where_txt = "WHERE(1)";
            }

            $rowStart = ($page-1)*$perpage;
            $resultArray = array();
            // ข้อมูลที่ต้องการให้แสดง
        if($_POST['fn'] === "list")    {
            $sql = "SELECT *,IF(LENGTH(mc) <= 4,CONCAT('อาคาร',left(mc,1)),CONCAT('อาคาร',left(mc,2))) as bd,CONCAT(cust,' ',ord,'-',item) AS ord_item FROM monitor_mc ${where_txt} ORDER BY mc ASC LIMIT $rowStart , $perpage";
            $stmt = $db->prepare( $sql );  
            $stmt->execute();
            $num = $stmt->rowCount();   
            while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                array_push($resultArray,$row);
            } 
        }
                    $sql3 = "SELECT COUNT(mc) AS n_mc, SUM(meter) AS s_meter, AVG(rpm) AS a_rpm, SUM(on_t) AS s_ont, SUM(top) AS s_top, SUM(top_t) AS s_topt, SUM(mid) AS s_mid, SUM(mid_t) AS s_midt, SUM(epa) AS s_epa, SUM(epa_t) AS s_epat, SUM(bob) AS s_bob, SUM(bob_t) AS s_bobt, SUM(off) AS s_off, SUM(off_t) AS s_offt, AVG(tmp) AS a_tmp, AVG(hmd) as a_hmd,MAX(tmp) AS mx_tmp, MIN(tmp) AS mn_tmp, MAX(hmd) AS mx_hmd, MIN(hmd) AS mn_hmd, MAX(meter) AS mx_meter, MIN(meter) AS mn_meter, sum(IF(mc_status='0',1,0)) AS mc_on, sum(IF(mc_status='1',1,0)) AS mc_top, sum(IF(mc_status='2',1,0)) AS mc_mid, sum(IF(mc_status='3',1,0)) AS mc_epa, sum(IF(mc_status='4',1,0)) AS mc_bob, sum(IF(mc_status='5',1,0)) AS mc_off, MAX(rpm) AS mx_rpm, MIN(rpm) AS mn_rpm FROM monitor_mc 
                    ${where_txt}";
                    $stmt3 = $db->prepare( $sql3 );   
                    $stmt3->execute();
                    $resultArray_sum = array();
                    while($row_sum = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                            array_push($resultArray_sum,$row_sum);                            
                        }
                        $numall = $resultArray_sum[0]['n_mc']; 
                        $allpage = ceil($numall/$perpage);


                $database = null; 
                echo json_encode(
                    array(
                        "data" => $resultArray,
                        "data_sum" => $resultArray_sum,
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



