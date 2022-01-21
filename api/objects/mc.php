<?php
class Mc{    
        // database connection and table name
        private $conn;
        private $table_name = "mc";
    
        // object properties
        public $id_mc;
        public $mc;
        public $group_id;
        public $shift_id;
        public $mc_rpm;
        public $mc_used;

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
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 
    
    function create(){    //===== create new record
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    mc = :mc,
                    group_id = :group_id,
                    shift_id = :shift_id,
                    mc_rpm = :mc_rpm,
                    cust = :cust,
                    popi = :popi,
                    ord = :ord,
                    item = :item,
                    dia = :dia,
                    color = :color,
                    ms = :ms,
                    md = :md,
                    ml_mt = :ml_mt,
                    ml_kn = :ml_kn,
                    mc_used = :mc_used ";

        $stmt = $this->conn->prepare($query);
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        $this->group_id=htmlspecialchars(strip_tags($this->group_id));
        $this->shift_id=htmlspecialchars(strip_tags($this->shift_id));
        $this->mc_rpm=htmlspecialchars(strip_tags($this->mc_rpm));
        $this->cust=htmlspecialchars(strip_tags($this->cust));
        $this->popi=htmlspecialchars(strip_tags($this->popi));
        $this->ord=htmlspecialchars(strip_tags($this->ord));
        $this->item=htmlspecialchars(strip_tags($this->item));
        $this->dia=htmlspecialchars(strip_tags($this->dia));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->ms=htmlspecialchars(strip_tags($this->ms));
        $this->md=htmlspecialchars(strip_tags($this->md));
        $this->ml_mt=htmlspecialchars(strip_tags($this->ml_mt));
        $this->ml_kn=htmlspecialchars(strip_tags($this->ml_kn));
        $this->mc_used=htmlspecialchars(strip_tags($this->mc_used));
        $stmt->bindParam(':mc', $this->mc);
        $stmt->bindParam(':group_id', $this->group_id);
        $stmt->bindParam(':shift_id', $this->shift_id);
        $stmt->bindParam(':mc_rpm', $this->mc_rpm); 
        $stmt->bindParam(':cust', $this->cust);
        $stmt->bindParam(':popi', $this->popi);
        $stmt->bindParam(':ord', $this->ord);
        $stmt->bindParam(':item', $this->item);
        $stmt->bindParam(':dia', $this->dia);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':ms', $this->ms);
        $stmt->bindParam(':md', $this->md);
        $stmt->bindParam(':ml_mt', $this->ml_mt);
        $stmt->bindParam(':ml_kn', $this->ml_kn);    
        $stmt->bindParam(':mc_used', $this->mc_used);      

        if($stmt->execute()){
            return true;
        }    
        return false;
    } 

    // delete a record
    public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE id_mc = :id_mc";
        $stmt = $this->conn->prepare($query);    
        $this->id_mc = htmlspecialchars(strip_tags($this->id_mc));
        $stmt->bindParam(':id_mc', $this->id_mc);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
       
    function nameExists(){    //===== check  name exist in the database
        $query = "SELECT id_mc, mc
                FROM " . $this->table_name . "
                WHERE mc = ?
                LIMIT 0,1";    
        $stmt = $this->conn->prepare( $query );   
        $this->mc=htmlspecialchars(strip_tags($this->mc));   
        $stmt->bindParam(1, $this->mc);    
        $stmt->execute();    
        $num = $stmt->rowCount();    
        if($num>0){    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            $this->id_mc = $row['id_mc'];
            $this->mc = $row['mc']; 
            return true;
        }    
        return false;
    }


    function newnameExit(){ //===== ตรวจสอบว่า name นี้ซืำหรือไม่
        $sql="SELECT id_mc, mc
        FROM " . $this->table_name. "
        WHERE (mc = :mc AND id_mc != :id_mc)";

        $stmt = $this->conn->prepare($sql);
        $this->mc = htmlspecialchars(strip_tags($this->mc));
        $this->id_mc = htmlspecialchars(strip_tags($this->id_mc));

        $stmt->bindParam(':mc',$this->mc);
        $stmt->bindParam(':id_mc',$this->id_mc);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  
            return true;
        }
        return false;
    }
    
    public function update(){ // update record
        $query = "UPDATE " . $this->table_name . "
                SET
                    mc = :mc,
                    group_id = :group_id,
                    shift_id = :shift_id,
                    mc_rpm = :mc_rpm,
                    cust = :cust,
                    popi = :popi,
                    ord = :ord,
                    item = :item,
                    dia = :dia,
                    color = :color,
                    ms = :ms,
                    md = :md,
                    ml_mt = :ml_mt,
                    ml_kn = :ml_kn,
                    mc_used = :mc_used 
                WHERE id_mc = :id_mc";
    
        $stmt = $this->conn->prepare($query);    
        $this->mc=htmlspecialchars(strip_tags($this->mc));
        $this->group_id=htmlspecialchars(strip_tags($this->group_id));
        $this->shift_id=htmlspecialchars(strip_tags($this->shift_id));
        $this->mc_rpm=htmlspecialchars(strip_tags($this->mc_rpm));

        $this->cust=htmlspecialchars(strip_tags($this->cust));
        $this->popi=htmlspecialchars(strip_tags($this->popi));
        $this->ord=htmlspecialchars(strip_tags($this->ord));
        $this->item=htmlspecialchars(strip_tags($this->item));
        $this->dia=htmlspecialchars(strip_tags($this->dia));
        $this->color=htmlspecialchars(strip_tags($this->color));
        $this->ms=htmlspecialchars(strip_tags($this->ms));
        $this->md=htmlspecialchars(strip_tags($this->md));
        $this->ml_mt=htmlspecialchars(strip_tags($this->ml_mt));
        $this->ml_kn=htmlspecialchars(strip_tags($this->ml_kn));

        $this->mc_used=htmlspecialchars(strip_tags($this->mc_used));
        $this->id_mc=htmlspecialchars(strip_tags($this->id_mc));
        $stmt->bindParam(':mc', $this->mc);
        $stmt->bindParam(':group_id', $this->group_id);
        $stmt->bindParam(':shift_id', $this->shift_id);
        $stmt->bindParam(':mc_rpm', $this->mc_rpm);   

        $stmt->bindParam(':cust', $this->cust);
        $stmt->bindParam(':popi', $this->popi);
        $stmt->bindParam(':ord', $this->ord);
        $stmt->bindParam(':item', $this->item);
        $stmt->bindParam(':dia', $this->dia);
        $stmt->bindParam(':color', $this->color);
        $stmt->bindParam(':ms', $this->ms);
        $stmt->bindParam(':md', $this->md);
        $stmt->bindParam(':ml_mt', $this->ml_mt);
        $stmt->bindParam(':ml_kn', $this->ml_kn);
        $stmt->bindParam(':mc_used', $this->mc_used);  

        $stmt->bindParam(':id_mc', $this->id_mc);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

 
}

?>

