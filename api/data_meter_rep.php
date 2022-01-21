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
            $resultArray = array();

            
            $concat = " (CONCAT('กะ',shift_no,' ',group_mc.group_name,' ',meter_mc.mc) LIKE '%";
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
                $concat_set = "AND (".$concat_set.")";  
            }


$sql_gr2 = "SELECT ('gr2') AS key_p, bd_name, ('') AS group_name, AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT bd_name, group_name, AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT  bd_name, group_name,mc,SUM(n_day) as n_day, 1 AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT meter_date, bd_name, group_name, mc, AVG(mc_rpm) AS mc_rpm, AVG(shift_count) AS sf_count, SUM(meter1) AS meter1, SUM(sf_min1) AS sf_min1, SUM(meter2) AS meter2, SUM(sf_min2) AS sf_min2, SUM(meter3) AS meter3, SUM(sf_min3) AS sf_min3, 1 AS n_day FROM (
                SELECT meter_date, bd.bd_name, group_mc.group_name, meter_mc.mc, meter_mc.mc_rpm, shift.shift_count, 
                if(shift_no=1,meter,0) AS meter1, 
                if(shift_no=2,meter,0) AS meter2, 
                if(shift_no=3,meter,0) AS meter3,
                if(shift_no=1,shift_min,0) AS sf_min1, 
                if(shift_no=2,shift_min,0) AS sf_min2, 
                if(shift_no=3,shift_min,0) AS sf_min3
                FROM ((((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id) INNER JOIN shift ON mc.shift_id = shift.shift_id) WHERE ((meter_date BETWEEN '${datefm}' AND '${dateto}') ${depart} ${concat_set} ) 
) AS TT GROUP BY mc, meter_date ORDER BY meter_date,mc ASC
    ) AS TT2 GROUP BY mc ORDER BY mc ASC
    ) AS TT3 GROUP BY group_name ORDER BY group_name ASC
    ) AS TT3 GROUP BY bd_name ORDER BY bd_name ASC";

$stmt_gr2 = $db->prepare( $sql_gr2 );  
$stmt_gr2->execute();

while($row2 = $stmt_gr2->fetch(PDO::FETCH_ASSOC)) { //===== กลุ่ม 2 อาคาร
    $ss_gr2 = $row2['bd_name'];
    $sql_gr1 = "SELECT ('gr1') AS key_p, ('') AS bd_name, group_name, AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
        SELECT  bd_name, group_name,mc,SUM(n_day) as n_day, 1 AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
        SELECT meter_date, bd_name, group_name, mc, AVG(mc_rpm) AS mc_rpm, AVG(shift_count) AS sf_count, SUM(meter1) AS meter1, SUM(sf_min1) AS sf_min1, SUM(meter2) AS meter2, SUM(sf_min2) AS sf_min2, SUM(meter3) AS meter3, SUM(sf_min3) AS sf_min3, 1 AS n_day FROM (
                    SELECT meter_date, bd.bd_name, group_mc.group_name, meter_mc.mc, meter_mc.mc_rpm, shift.shift_count, 
                    if(shift_no=1,meter,0) AS meter1, 
                    if(shift_no=2,meter,0) AS meter2, 
                    if(shift_no=3,meter,0) AS meter3,
                    if(shift_no=1,shift_min,0) AS sf_min1, 
                    if(shift_no=2,shift_min,0) AS sf_min2, 
                    if(shift_no=3,shift_min,0) AS sf_min3
                    FROM ((((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id) INNER JOIN shift ON mc.shift_id = shift.shift_id) WHERE ((meter_date BETWEEN '${datefm}' AND '${dateto}') ${depart} ${concat_set} AND bd.bd_name = '${ss_gr2}') 
    ) AS TT GROUP BY mc, meter_date ORDER BY meter_date,mc ASC
    ) AS TT2 GROUP BY mc ORDER BY mc ASC
    ) AS TT3 GROUP BY group_name ORDER BY group_name ASC";
    
    $stmt_gr1 = $db->prepare( $sql_gr1 );  
    $stmt_gr1->execute();
    while($row1 = $stmt_gr1->fetch(PDO::FETCH_ASSOC)) { //===== กลุ่ม 1 กลุ่ม 
        $ss_gr1 = $row1['group_name'];
       
        $sql = "SELECT ('data') AS key_p, bd_name, group_name,mc,SUM(n_day) as n_day, 1 AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
            SELECT meter_date, bd_name, group_name, mc, AVG(mc_rpm) AS mc_rpm, AVG(shift_count) AS sf_count, SUM(meter1) AS meter1, SUM(sf_min1) AS sf_min1, SUM(meter2) AS meter2, SUM(sf_min2) AS sf_min2, SUM(meter3) AS meter3, SUM(sf_min3) AS sf_min3, 1 AS n_day FROM (
                        SELECT meter_date, bd.bd_name, group_mc.group_name, meter_mc.mc, meter_mc.mc_rpm, shift.shift_count, 
                        if(shift_no=1,meter,0) AS meter1, 
                        if(shift_no=2,meter,0) AS meter2, 
                        if(shift_no=3,meter,0) AS meter3,
                        if(shift_no=1,shift_min,0) AS sf_min1,  
                        if(shift_no=2,shift_min,0) AS sf_min2, 
                        if(shift_no=3,shift_min,0) AS sf_min3
                        FROM ((((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id) INNER JOIN shift ON mc.shift_id = shift.shift_id) WHERE ((meter_date BETWEEN '${datefm}' AND '${dateto}') ${depart} ${concat_set} AND group_mc.group_name = '${ss_gr1}' AND bd.bd_name = '${ss_gr2}') 
                        ) AS TT GROUP BY mc, meter_date ORDER BY meter_date,mc ASC
    ) AS TT2 GROUP BY mc ORDER BY bd_name,group_name,mc ASC";
        
        $stmt = $db->prepare($sql);  
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //===== รายเครื่อง            
            array_push($resultArray,$row);
        } 
        array_push($resultArray,$row1);
    } 
    array_push($resultArray,$row2);
} 


$sql_all = "SELECT ('all') AS key_p, ('') AS bd_name, ('') AS group_name, AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count  
FROM(
    SELECT  AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT bd_name, group_name, AVG(n_day) as n_day, SUM(n_mc) AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT  bd_name, group_name,mc,SUM(n_day) as n_day, 1 AS n_mc, AVG(mc_rpm) AS mc_rpm, SUM(meter1) AS meter1, SUM(meter2) AS meter2, SUM(meter3) AS meter3, AVG(sf_min1) AS sf_min1, AVG(sf_min2) AS sf_min2, AVG(sf_min3) AS sf_min3, AVG(sf_count) AS sf_count FROM(
    SELECT meter_date, bd_name, group_name, mc, AVG(mc_rpm) AS mc_rpm, AVG(shift_count) AS sf_count, SUM(meter1) AS meter1, SUM(sf_min1) AS sf_min1, SUM(meter2) AS meter2, SUM(sf_min2) AS sf_min2, SUM(meter3) AS meter3, SUM(sf_min3) AS sf_min3, 1 AS n_day FROM (
                SELECT meter_date, bd.bd_name, group_mc.group_name, meter_mc.mc, meter_mc.mc_rpm, shift.shift_count, 
                if(shift_no=1,meter,0) AS meter1, 
                if(shift_no=2,meter,0) AS meter2, 
                if(shift_no=3,meter,0) AS meter3,
                if(shift_no=1,shift_min,0) AS sf_min1, 
                if(shift_no=2,shift_min,0) AS sf_min2, 
                if(shift_no=3,shift_min,0) AS sf_min3
                FROM ((((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id) INNER JOIN shift ON mc.shift_id = shift.shift_id) WHERE ((meter_date BETWEEN '${datefm}' AND '${dateto}') ${depart} ${concat_set}) 
    ) AS TT GROUP BY mc, meter_date ORDER BY meter_date,mc ASC
    ) AS TT2 GROUP BY mc ORDER BY mc ASC
    ) AS TT3 GROUP BY group_name ORDER BY group_name ASC
    ) AS TT3 GROUP BY bd_name ORDER BY bd_name ASC
) AS TT4 ";
$stmt_all = $db->prepare($sql_all);  
$stmt_all->execute();
while($row_all = $stmt_all->fetch(PDO::FETCH_ASSOC)) { //===== รวมทั้งหมด  
    array_push($resultArray,$row_all);
    
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



