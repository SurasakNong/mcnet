<?php
class Monitor{     
        // database connection and table name
        private $conn;
        private $table_name = "monitor_mc";
    
        // object properties
        public $mc;        public $mc_status;
        public $meter;
        public $rpm;        public $on_t;
        public $top;        public $top_t;
        public $mid;        public $mid_t;
        public $epa;        public $epa_t;
        public $bob;        public $bob_t;
        public $off;        public $off_t;

        public $tmp;        public $hmd;
        public $meter_date;
        public $shift_no;
        public $shift_be; 
        public $shift_end;
        public $shift_min;
        public $even_stamp;

        public $cust;
        public $popi;
        public $ord;
        public $item;
        public $dia;
        public $color;
        public $ms;
        public $md;
        public $ml_mt;
        public $ml_kn;

        public $comm; 
        public $strComm;
        public $head_str;
        public $data_str;
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 
    
    // delete a record
    public function delete_all(){
        $query = "DELETE FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        if($stmt->execute()){
            return true;
        }
        return false;
    }

    public function delete(){
        $query = "DELETE  FROM " . $this->table_name . " WHERE mc = :mc";
        $stmt = $this->conn->prepare($query);    
        $this->mc = htmlspecialchars(strip_tags($this->mc));
        $stmt->bindParam(':mc', $this->mc);
        if($stmt->execute()){
            return true;
        }
        return false;
    }       
     
    public function set_zero(){ // update to zero
        $query = "UPDATE " . $this->table_name . "
                SET 
                mc_status = '0',
                meter = '0',
                rpm = '0',
                on_t = '0',
                top = '0',
                top_t = '0',
                mid = '0',
                mid_t = '0',
                epa = '0',
                epa_t = '0',
                bob = '0',
                bob_t = '0',
                off = '0',
                off_t = '0',   
                tmp = '0',
                hmd = '0',               
                comm = '1', 
                event_stamp = NOW() WHERE mc = :mc";    
        $stmt = $this->conn->prepare($query);         
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function set_zero_all(){ // update All to zero
        $query = "UPDATE " . $this->table_name . "
                SET 
                mc_status = '0',
                meter = '0',
                rpm = '0',
                on_t = '0',
                top = '0',
                top_t = '0',
                mid = '0',
                mid_t = '0',
                epa = '0',
                epa_t = '0',
                bob = '0',
                bob_t = '0',
                off = '0',
                off_t = '0',
                tmp = '0',
                hmd = '0',
                comm = '1',
                event_stamp = NOW() 
                WHERE 1";    
        $stmt = $this->conn->prepare($query);       
        if($stmt->execute()){
            return true;
        }    
        return false;
    }


    public function create(){    //===== create new record
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    mc = :mc";
        $stmt = $this->conn->prepare($query);
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    } 
    
    
    public function val_update(){ // update record
        $query = "UPDATE " . $this->table_name . "
                SET
                    mc_status = :mc_status,
                    meter = :meter,
                    rpm = :rpm, on_t = :on_t,
                    top = :top, top_t = :top_t,
                    mid = :mid, mid_t = :mid_t,
                    epa = :epa, epa_t = :epa_t,
                    bob = :bob, bob_t = :bob_t,
                    off = :off, off_t = :off_t,
                    tmp = :tmp, hmd = :hmd, event_stamp = :e_stamp 
                WHERE mc = :mc ";
    
        $stmt = $this->conn->prepare($query);    
        $this->mc_status=htmlspecialchars(strip_tags($this->mc_status));
        $this->meter=htmlspecialchars(strip_tags($this->meter));
        $this->rpm=htmlspecialchars(strip_tags($this->rpm));
        $this->on_t=htmlspecialchars(strip_tags($this->on_t));
        $this->top=htmlspecialchars(strip_tags($this->top));
        $this->top_t=htmlspecialchars(strip_tags($this->top_t));
        $this->mid=htmlspecialchars(strip_tags($this->mid));
        $this->mid_t=htmlspecialchars(strip_tags($this->mid_t));
        $this->epa=htmlspecialchars(strip_tags($this->epa));
        $this->epa_t=htmlspecialchars(strip_tags($this->epa_t));
        $this->bob=htmlspecialchars(strip_tags($this->bob));
        $this->bob_t=htmlspecialchars(strip_tags($this->bob_t));
        $this->off=htmlspecialchars(strip_tags($this->off));
        $this->off_t=htmlspecialchars(strip_tags($this->off_t));
        $this->tmp=htmlspecialchars(strip_tags($this->tmp));
        $this->hmd=htmlspecialchars(strip_tags($this->hmd));
        $this->even_stamp=htmlspecialchars(strip_tags($this->even_stamp));
        $this->mc=htmlspecialchars(strip_tags($this->mc));

        $stmt->bindParam(':mc_status', $this->mc_status);
        $stmt->bindParam(':meter', $this->meter);
        $stmt->bindParam(':rpm', $this->rpm);
        $stmt->bindParam(':on_t', $this->on_t); 
        $stmt->bindParam(':top', $this->top);
        $stmt->bindParam(':top_t', $this->top_t);
        $stmt->bindParam(':mid', $this->mid); 
        $stmt->bindParam(':mid_t', $this->mid_t);
        $stmt->bindParam(':epa', $this->epa);
        $stmt->bindParam(':epa_t', $this->epa_t); 
        $stmt->bindParam(':bob', $this->bob);
        $stmt->bindParam(':bob_t', $this->bob_t);
        $stmt->bindParam(':off', $this->off);
        $stmt->bindParam(':off_t', $this->off_t);
        $stmt->bindParam(':tmp', $this->tmp);   
        $stmt->bindParam(':hmd', $this->hmd);   
        $stmt->bindParam(':e_stamp', $this->even_stamp);   

        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }
    

    public function meter_update(){ // update meter        
            $query = "INSERT INTO meter_mc (meter_date, shift_no, mc, meter, rpm, on_t, top, top_t, mid, mid_t, epa, epa_t, bob, bob_t, off, off_t, tmp, hmd, mc_rpm, id_mc, shift_min, cust, popi, ord, item, dia, color, ms, md, ml_mt, ml_kn) 
            SELECT monitor_mc.meter_date, monitor_mc.shift_no, monitor_mc.mc, monitor_mc.meter, monitor_mc.rpm, (monitor_mc.on_t/600) AS on_t, monitor_mc.top, (monitor_mc.top_t/600) AS top_t, monitor_mc.mid, (monitor_mc.mid_t/600) AS mid_t, monitor_mc.epa, (monitor_mc.epa_t/600) AS epa_t, monitor_mc.bob, (monitor_mc.bob_t/600) AS bob_t, monitor_mc.off, (monitor_mc.off_t/600) AS off_t, monitor_mc.tmp, monitor_mc.hmd , mc.mc_rpm, mc.id_mc, monitor_mc.shift_min, monitor_mc.cust, monitor_mc.popi, monitor_mc.ord, monitor_mc.item, monitor_mc.dia, monitor_mc.color, monitor_mc.ms, monitor_mc.md, monitor_mc.ml_mt, monitor_mc.ml_kn
            FROM  (monitor_mc INNER JOIN mc ON monitor_mc.mc = mc.mc) WHERE monitor_mc.mc = :mc";    
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }    

    public function shift_next_read(){ // อ่านค่ากะจาก monitor
        $query = "SELECT meter_date, mc, shift_no, shift_be, shift_end, shift_min  FROM " . $this->table_name . " WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);   
            $this->meter_date = $row['meter_date']; 
            $this->shift_no = $row['shift_no']; 
            $this->shift_be = $row['shift_be'];
            $this->shift_end = $row['shift_end'];
            $this->shift_min = $row['shift_min'];

            return true;
        }    
        return false;
    }

    public function shift_next_change(){ // change shift set
        
        $query = "SELECT mc,shift.*  FROM mc INNER JOIN shift ON mc.shift_id = shift.shift_id WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);            
        $sh_no = (int)$this->shift_no;
        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  
            $row_sh_no =  $row['shift_count']*1;            
            $sh_no = ($sh_no < $row_sh_no)? $sh_no+1:1;  // เลื่อนไปกะถัดไป
            $this->shift_no =  $sh_no;          
            if( $sh_no == 1){
                $this->shift_be = $row['shift_be1'];
                $this->shift_end = $row['shift_en1'];       
                $time_be = strtotime($this->shift_be);
                $time_en = strtotime($this->shift_end);     
                $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60;                  

            }else if($sh_no == 2){
                $this->shift_be = $row['shift_be2'];
                $this->shift_end = $row['shift_en2'];
                $time_be = strtotime($this->shift_be);
                $time_en = strtotime($this->shift_end);     
                $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60; 
            }
            else if($sh_no == 3){
                $this->shift_be = $row['shift_be3'];
                $this->shift_end = $row['shift_en3'];
                $time_be = strtotime($this->shift_be);
                $time_en = strtotime($this->shift_end);     
                $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60; 
            }
   
            return true;
        }    
        return false;
    }

    public function shift_update(){ // บันทึกค่ากะลงใน monitor
        $query = "UPDATE " . $this->table_name . "
                SET
                    meter_date = :meter_date,
                    shift_no = :shift_no,
                    shift_be = :shift_be,
                    shift_end = :shift_end,
                    shift_min = :shift_min
                WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);    
        $this->shift_no=htmlspecialchars(strip_tags($this->shift_no));
        $this->shift_be=htmlspecialchars(strip_tags($this->shift_be));
        $this->shift_end=htmlspecialchars(strip_tags($this->shift_end));  
        $this->shift_min=htmlspecialchars(strip_tags($this->shift_min));        
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        if($this->shift_no == 1){
            if(date('w') == "0"){  //ตรงกับวันอาทิตย์ให้เลื่อนไป 1 วัน
                $this->meter_date = date('Y-m-d',strtotime(date('Y-m-d') . "+1 days"));
            }else{
                $this->meter_date = date("Y-m-d");
            }
            
        }
        $stmt->bindParam(':meter_date', $this->meter_date);  
        $stmt->bindParam(':shift_no', $this->shift_no);   
        $stmt->bindParam(':shift_be', $this->shift_be);   
        $stmt->bindParam(':shift_end', $this->shift_end);   
        $stmt->bindParam(':shift_min', $this->shift_min);  
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function shift_set(){ // กำหนดกะให้เป็นปัจจุบัน       
        $query = "SELECT mc,shift.*  FROM mc INNER JOIN shift ON mc.shift_id = shift.shift_id WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);          
        
        $now_timestm = time();
        $ho_nw = date("H",$now_timestm)*10000;
        $mi_nw = date("i",$now_timestm)*100;
        $se_nw = date("s",$now_timestm)*1;
        $nw = $ho_nw+$mi_nw+$se_nw; // เวลาปัจจุบัน

        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);  
            $row_sh_count =  $row['shift_count']*1;   

            for($i=1; $i <= $row_sh_count; $i++){
                $time_data_be = strtotime($row['shift_be'.$i]);
                $ho_be = date("H",$time_data_be)*10000;
                $mi_be = date("i",$time_data_be)*100;
                $se_be = date("s",$time_data_be)*1;
                $be = $ho_be+$mi_be+$se_be; // เวลาเริ่มกะ

                $time_data_en = strtotime($row['shift_en'.$i]);
                $ho_en = date("H",$time_data_en)*10000;
                $mi_en = date("i",$time_data_en)*100;
                $se_en = date("s",$time_data_en)*1;
                $en = $ho_en+$mi_en+$se_en; // เวลาสิ้นสุดกะ

                if($en>$be){
                    if(($nw >= $be) && ($nw <= $en)){
                        $this->meter_date = date("Y-m-d",time());
                        $this->shift_no =  $i;  
                        $this->shift_be = $row['shift_be'.$i];  
                        $this->shift_end = $row['shift_en'.$i];  
                        $time_be = strtotime($this->shift_be);
                        $time_en = strtotime($this->shift_end);     
                        $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60; 
                    }
                }else if($en<$be){
                    if(($nw >= $be) && ($nw <= 240000)){
                        $this->meter_date = date("Y-m-d",time());
                        $this->shift_no =  $i;  
                        $this->shift_be = $row['shift_be'.$i];  
                        $this->shift_end = $row['shift_en'.$i];  
                        $time_be = strtotime($this->shift_be);
                        $time_en = strtotime($this->shift_end);     
                        $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60; 
                    }
                    if(($nw >= 0) && ($nw <= $en)){
                        $this->meter_date = date('Y-m-d', strtotime('-1 day', strtotime(date('Y-m-d'))));
                        $this->shift_no =  $i;  
                        $this->shift_be = $row['shift_be'.$i];  
                        $this->shift_end = $row['shift_en'.$i];  
                        $time_be = strtotime($this->shift_be);
                        $time_en = strtotime($this->shift_end);     
                        $this->shift_min = ($time_en < $time_be)?(($time_en - $time_be)/60)+1440:($time_en - $time_be)/60; 
                    }
                }  
            }
   
            return true;
        }    
        return false;
    }

    public function shift_set_all(){ // change all shift set        
        $sql = "SELECT mc FROM " . $this->table_name." WHERE 1";
        $stmt1 = $this->conn->prepare($sql); 
        if($stmt1->execute() && $stmt1->rowCount() > 0){
            while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $this->mc=$row1['mc']; 
                if($this->shift_set()){
                    $this->shift_update(); 
                }else{
                    return false;
                }           
            } 
            return true;
        }
        return false;
    }

    public function set_mc_initial(){ // set mc to monitor for initial 
        $this->delete_all(); // ลบข้อมูลทั้งหมดออกจากตาราง monitor       
        $sql = "SELECT mc FROM mc WHERE mc_used = '1'";
        $stmt1 = $this->conn->prepare($sql); 
        if($stmt1->execute() && $stmt1->rowCount() > 0){ // เพิ่มเครื่องจักรเข้าไปใหม่
            while($row1 = $stmt1->fetch(PDO::FETCH_ASSOC)) {
                $this->mc=$row1['mc'];
                $query = "INSERT INTO monitor_mc (mc) VALUES ('".$this->mc."')";    
                $stmt = $this->conn->prepare($query);
                $stmt->execute();     
                $this->shift_set();
                $this->shift_update(); //==== ตั้งค่ากะปัจจุบันให้เครื่องทอ
                
                $this->set_order(); //==== ตั้งค่าออร์เดอร์ให้เครื่องทอ
                                      
            } 
            return true;
        }
        return false;
    }

    public function comm_update(){ // update command
        $query = "UPDATE " . $this->table_name . "
                SET
                    comm = :comm,
                    strComm = :strComm
                WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);    
        $this->comm=htmlspecialchars(strip_tags($this->comm));
        $this->strComm=htmlspecialchars(strip_tags($this->strComm));     
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        
        $stmt->bindParam(':comm', $this->comm);   
        $stmt->bindParam(':strComm', $this->strComm);
        $stmt->bindParam(':mc', $this->mc);
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function comm_read(){ // read command
        $query = "SELECT mc, comm, strComm  FROM " . $this->table_name . " WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            $this->comm = $row['comm'];
            $this->strComm = $row['strComm'];
            return true;
        }    
        return false;
    }

    public function status_read(){ // read status
        $query = "SELECT mc, top, top_t, mid, mid_t, epa, epa_t, bob, bob_t, off, off_t, mc_status,event_stamp 
        FROM " . $this->table_name . " WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $this->top = $row['top'];
            $this->mid = $row['mid'];
            $this->epa = $row['epa'];
            $this->bob = $row['bob'];
            $this->off = $row['off'];   
            $this->top_t = $row['top_t'];
            $this->mid_t = $row['mid_t'];
            $this->epa_t = $row['epa_t'];
            $this->bob_t = $row['bob_t'];
            $this->off_t = $row['off_t'];
            $this->mc_status = $row['mc_status'];
            $this->even_stamp = $row['event_stamp'];
            return true;
        }    
        return false;
    }

    public function data_update(){ // update data
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        $this->head_str=htmlspecialchars(strip_tags($this->head_str));        
        $this->data_str=htmlspecialchars(strip_tags($this->data_str));
        $ss = '{"'.$this->head_str.'":"'.$this->data_str.'"}';
        $query = "UPDATE $this->table_name
                SET $this->head_str = :data,
                comm = '2', strComm = '$ss' WHERE mc = :mc";
    
        $stmt = $this->conn->prepare($query);    
        $stmt->bindParam(':data', $this->data_str);  
        $stmt->bindParam(':mc', $this->mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

    public function set_order() { // กำหนดออร์เดอร์ให้กับเครื่องทอ
        $query = "SELECT * FROM mc  WHERE mc = :mc";
        $stmt = $this->conn->prepare($query);           
        $this->mc=htmlspecialchars(strip_tags($this->mc)); 
        $stmt->bindParam(':mc', $this->mc);
        if($stmt->execute() && $stmt->rowCount() > 0){
            $row = $stmt->fetch(PDO::FETCH_ASSOC); 
            $query2 = "UPDATE " . $this->table_name . "
                SET 
                    cust = :cust,
                    popi = :popi,
                    ord = :ord,
                    item = :item,
                    dia = :dia,
                    color = :color,
                    ms = :ms,
                    md = :md,
                    ml_mt = :ml_mt,
                    ml_kn = :ml_kn
                WHERE mc = :mc";
            $stmt2 = $this->conn->prepare($query2);   
            $stmt2->bindParam(':cust', $row['cust']);
            $stmt2->bindParam(':popi', $row['popi']);
            $stmt2->bindParam(':ord', $row['ord']);
            $stmt2->bindParam(':item', $row['item']);
            $stmt2->bindParam(':dia', $row['dia']);
            $stmt2->bindParam(':color', $row['color']);
            $stmt2->bindParam(':ms', $row['ms']);
            $stmt2->bindParam(':md', $row['md']);
            $stmt2->bindParam(':ml_mt', $row['ml_mt']);
            $stmt2->bindParam(':ml_kn', $row['ml_kn']); 
            $stmt2->bindParam(':mc', $this->mc);
            if($stmt2->execute()){
                return true;
            }   
            return false;

        }
        return false;
    }

 
}

?>

