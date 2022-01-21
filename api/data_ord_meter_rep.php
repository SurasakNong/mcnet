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

            // คำค้นหา
            $concat = " (CONCAT(meter_mc.cust,' ',meter_mc.popi,' ',meter_mc.dia,' ',meter_mc.color,' ',meter_mc.ord,'-',meter_mc.item,' ',meter_mc.mc,' ',meter_mc.ms,'x',meter_mc.md,'x',meter_mc.ml_mt,'(',meter_mc.ml_kn,')') LIKE '%";
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

        $sql = "SELECT ('data') AS key_p, cust, popi, ord, item, dia, color, spec, bd_name, group_name, mc,SUM(meter) AS meter, SUM(on_t) AS on_t, SUM(top) AS top, SUM(top_t) AS top_t, SUM(mid) AS mid, SUM(mid_t) AS mid_t, SUM(epa) AS epa, SUM(epa_t) AS epa_t, SUM(bob) AS bob, SUM(bob_t) AS bob_t, SUM(off) AS off, SUM(off_t) AS off_t,SUM(n_day) as n_day, SUM(shift_min) AS shift_min,AVG(mc_rpm) AS mc_rpm, 1 AS n_mc
        FROM(
        SELECT meter_date,meter_mc.cust, meter_mc.popi, meter_mc.ord, meter_mc.item, meter_mc.dia, meter_mc.color,CONCAT(meter_mc.ms,'x',meter_mc.md,'x',meter_mc.ml_mt,'(',meter_mc.ml_kn,')') AS spec,bd.bd_name, group_mc.group_name, meter_mc.mc,SUM(meter) AS meter, SUM(on_t) AS on_t, SUM(top) AS top, SUM(top_t) AS top_t, SUM(mid) AS mid, SUM(mid_t) AS mid_t, SUM(epa) AS epa, SUM(epa_t) AS epa_t, SUM(bob) AS bob, SUM(bob_t) AS bob_t, SUM(off) AS off, SUM(off_t) AS off_t, 1 AS n_day,SUM(shift_min) AS shift_min,AVG(meter_mc.mc_rpm) AS mc_rpm
        FROM (((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id)
        WHERE (meter_date BETWEEN '${datefm}' AND '${dateto}' ${depart} ${concat_set})
        GROUP BY meter_date,cust,popi,ord,item,mc) AS TT GROUP BY mc ORDER BY cust,popi,ord,item,mc ASC";
        
        $stmt = $db->prepare($sql);  
        $stmt->execute();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) { //===== รายเครื่อง            
            array_push($resultArray,$row);
        } 

        $sql_all = "SELECT ('all') AS key_p, SUM(meter) AS meter, SUM(on_t) AS on_t, SUM(top) AS top, SUM(top_t) AS top_t, SUM(mid) AS mid, SUM(mid_t) AS mid_t, SUM(epa) AS epa, SUM(epa_t) AS epa_t, SUM(bob) AS bob, SUM(bob_t) AS bob_t, SUM(off) AS off, SUM(off_t) AS off_t,n_day, SUM(shift_min) AS shift_min,AVG(mc_rpm) AS mc_rpm,SUM(n_mc) AS n_mc
        FROM(
        SELECT cust, popi, ord, item, dia, color, spec, bd_name, group_name, mc,SUM(meter) AS meter, SUM(on_t) AS on_t, SUM(top) AS top, SUM(top_t) AS top_t, SUM(mid) AS mid, SUM(mid_t) AS mid_t, SUM(epa) AS epa, SUM(epa_t) AS epa_t, SUM(bob) AS bob, SUM(bob_t) AS bob_t, SUM(off) AS off, SUM(off_t) AS off_t,SUM(n_day) as n_day, SUM(shift_min) AS shift_min,AVG(mc_rpm) AS mc_rpm, 1 AS n_mc
        FROM(
        SELECT meter_date,meter_mc.cust, meter_mc.popi, meter_mc.ord, meter_mc.item, meter_mc.dia, meter_mc.color,CONCAT(meter_mc.ms,'x',meter_mc.md,'x',meter_mc.ml_mt,'(',meter_mc.ml_kn,')') AS spec,bd.bd_name, group_mc.group_name, meter_mc.mc,SUM(meter) AS meter, SUM(on_t) AS on_t, SUM(top) AS top, SUM(top_t) AS top_t, SUM(mid) AS mid, SUM(mid_t) AS mid_t, SUM(epa) AS epa, SUM(epa_t) AS epa_t, SUM(bob) AS bob, SUM(bob_t) AS bob_t, SUM(off) AS off, SUM(off_t) AS off_t, 1 AS n_day,SUM(shift_min) AS shift_min,AVG(meter_mc.mc_rpm) AS mc_rpm
        FROM (((meter_mc INNER JOIN mc ON meter_mc.id_mc = mc.id_mc) INNER JOIN group_mc ON mc.group_id = group_mc.group_id) INNER JOIN bd ON group_mc.bd_id=bd.bd_id)
        WHERE (meter_date BETWEEN '${datefm}' AND '${dateto}' ${depart} ${concat_set}) 
        GROUP BY meter_date,cust,popi,ord,item,mc) AS TT GROUP BY mc ORDER BY cust,popi,ord,item,mc ASC) AS TT_all";

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



