<?php
include_once 'config/core.php';
//===== required headers
header("Access-Control-Allow-Origin: * ");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include_once 'config/database.php';
include_once 'objects/monitor.php';

// get database connection
$database = new Database();
$db = $database->getConnection();
$myclass = new Monitor($db);
$my_data = array();
if(P_val($_POST["api_key"]) === $api_key_value) {
    for($i = 0; $i <= 2; $i++){
        if(isset($_POST["mc".$i])){
            $myclass->mc = $_POST["mc".$i];
            $myclass->comm_read();            
            if($myclass->comm != "0"){                             //==== มีการกำหนดคำสั่ง Command
                if($myclass->comm == "1"){ //==== รีเซ็ตค่าให้เป็น 0
                    $data =  ["comm"=>"1","mcname"=>$myclass->mc];                        
                }else if($myclass->comm == "2"){     //=== ตั้งค่าใดๆให้เครื่องจักร
                    $dataHead =  ["comm"=>"2","mcname"=>$myclass->mc];
                    $dataArr = json_decode($myclass->strComm,true);                    
                    $data = array_merge_recursive($dataHead,$dataArr);
                }else if($myclass->comm == "3"){ //=== กำหนดให้ไม่เก็บข้อมูล เช่น จอดซ่อมใหญ่
                    $data =  ["comm"=>"3","mcname"=>$myclass->mc];                        
                }  
                $myclass->comm = "0";
                $myclass->strComm = "";
                $myclass->comm_update();                  

            }else{   //==== ทำงานปกติ comm ==> 0
                
                $daytimeNow = date("Y-m-d H:i:s");
                $data =  ["comm"=>"0","mcname"=>$myclass->mc];
                $mcst = (isset($_POST["mc_status".$i])?$_POST["mc_status".$i]:"6");
                $myclass->status_read();
                if($mcst != $myclass->mc_status){
                    $myclass->even_stamp = $daytimeNow;                        
                }
                $myclass->mc_status = $mcst;
                $myclass->meter = (isset($_POST["meter".$i])?$_POST["meter".$i]:"0");
                $myclass->rpm = (isset($_POST["rpm".$i])?$_POST["rpm".$i]:"0");
                $myclass->on_t = (isset($_POST["on_t".$i])?$_POST["on_t".$i]:"0");
                $myclass->top = (isset($_POST["top".$i])?$_POST["top".$i]:"0");
                $myclass->top_t = (isset($_POST["top_t".$i])?$_POST["top_t".$i]:"0");
                $myclass->mid = (isset($_POST["mid".$i])?$_POST["mid".$i]:"0");
                $myclass->mid_t = (isset($_POST["mid_t".$i])?$_POST["mid_t".$i]:"0");
                $myclass->epa = (isset($_POST["epa".$i])?$_POST["epa".$i]:"0");
                $myclass->epa_t = (isset($_POST["epa_t".$i])?$_POST["epa_t".$i]:"0");
                $myclass->bob = (isset($_POST["bob".$i])?$_POST["bob".$i]:"0");
                $myclass->bob_t = (isset($_POST["bob_t".$i])?$_POST["bob_t".$i]:"0");
                $myclass->off = (isset($_POST["off".$i])?$_POST["off".$i]:"0");
                $myclass->off_t = (isset($_POST["off_t".$i])?$_POST["off_t".$i]:"0");
                $myclass->tmp = (isset($_POST["tmp".$i])?$_POST["tmp".$i]:"0");
                $myclass->hmd = (isset($_POST["hmd".$i])?$_POST["hmd".$i]:"0");

                $myclass->val_update();

            }
            array_push($my_data,$data);            
            
            //===== เปรียบเทียบเวลาเพื่อบันทึกข้อมูลมิเตอร์ ===== 
            $myclass->shift_next_read();
            $next_time_data = strtotime($myclass->shift_end);
            $ho_nx = date("H",$next_time_data)*1;
            $mi_nx = date("i",$next_time_data)*1;
            $se_nx = date("s",$next_time_data)*1;
            $now_timestm = time();
            $ho_nw = date("H",$now_timestm)*1;
            $mi_nw = date("i",$now_timestm)*1;
            $se_nw = date("s",$now_timestm)*1;
            if($ho_nw == $ho_nx && $mi_nw >= $mi_nx && $se_nw >= $se_nx){    
                $myclass->status_read();
                $stmpTime = strtotime($myclass->even_stamp);
                $add_time = ($next_time_data - $stmpTime)*10;

                if($myclass->mc_status == "1"){
                    $add_n = ($myclass->top *1)+1;
                    $add_value = ($myclass->top_t *1)+$add_time;
                    $query = "UPDATE monitor_mc SET top = :add_n, top_t = :add_value WHERE mc = :mc";
                    
                }else if($myclass->mc_status == "2"){
                    $add_n = ($myclass->mid *1)+1;
                    $add_value = ($myclass->mid_t *1)+$add_time;
                    $query = "UPDATE monitor_mc SET mid = :add_n, mid_t = :add_value WHERE mc = :mc";
                    
                }else if($myclass->mc_status == "3"){
                    $add_n = ($myclass->epa *1)+1;
                    $add_value = ($myclass->epa_t *1)+$add_time;
                    $query = "UPDATE monitor_mc SET epa = :add_n, epa_t = :add_value WHERE mc = :mc";
                    
                }else if($myclass->mc_status == "4"){
                    $add_n = ($myclass->bob *1)+1;
                    $add_value = ($myclass->bob_t *1)+$add_time;
                    $query = "UPDATE monitor_mc SET bob = :add_n, bob_t = :add_value WHERE mc = :mc";
                    
                }else if($myclass->mc_status == "5"){
                    $add_n = ($myclass->off *1)+1;
                    $add_value = ($myclass->off_t *1)+$add_time;
                    $query = "UPDATE monitor_mc SET off = :add_n, off_t = :add_value WHERE mc = :mc";
                }            
                
                if($myclass->mc_status != "0"){
                    $stmt = $db->prepare($query);   
                    $add_n=htmlspecialchars(strip_tags($add_n));
                    $add_value=htmlspecialchars(strip_tags($add_value));     
                    $myclass->mc=htmlspecialchars(strip_tags($myclass->mc));                        
                    $stmt->bindParam(':add_n', $add_n);   
                    $stmt->bindParam(':add_value', $add_value);
                    $stmt->bindParam(':mc', $myclass->mc);
                    $stmt->execute();
                }
                
                $myclass->meter_update(); //==== บันทึกข้อมูลมิเตอร์

                $myclass->shift_next_change(); //==== กำหนดกะถัดไป
                $myclass->shift_update(); //==== ตั้งค่ากะถัดไป
                
                $myclass->set_order(); //==== กำหนดออร์เดอร์ให้เครื่องทอ
                $myclass->set_zero(); //=== กำหนดให้เป็นศูนย์ เพื่อเริ่มต้นใหม่
            }
            

        }else { //=== ไม่พบชื่อเครื่องจักรส่งเข้ามา
            array_push($my_data,["comm"=>"9","mcname"=>NULL]);
        } 

    }
    http_response_code(200);
    echo json_encode($my_data); //=== OK    

} else {
    http_response_code(400);
    echo "Wrong API Key provided.";
}


function P_val($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

?>