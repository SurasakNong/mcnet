<?php
class Group{     
        // database connection and table name
        private $conn;
        private $table_name = "group_mc";
    
        // object properties
        public $group_id;
        public $bd_id;
        public $group_name;
        public $group_mc;
        public $group_rpm;
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 
    
    function create(){    //===== create new record
        $query = "INSERT INTO " . $this->table_name . "
                SET
                    bd_id = :bd_id,
                    group_name = :group_name,
                    group_mc = :group_mc,
                    group_rpm = :group_rpm ";

        $stmt = $this->conn->prepare($query);
        $this->bd_id=htmlspecialchars(strip_tags($this->bd_id));
        $this->group_name=htmlspecialchars(strip_tags($this->group_name));
        $this->group_mc=htmlspecialchars(strip_tags($this->group_mc));
        $this->group_rpm=htmlspecialchars(strip_tags($this->group_rpm));
        $stmt->bindParam(':bd_id', $this->bd_id);
        $stmt->bindParam(':group_name', $this->group_name);
        $stmt->bindParam(':group_mc', $this->group_mc);
        $stmt->bindParam(':group_rpm', $this->group_rpm);        

        if($stmt->execute()){
            return true;
        }    
        return false;
    } 

    // delete a record
    public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE group_id = :group_id";
        $stmt = $this->conn->prepare($query);    
        $this->group_id = htmlspecialchars(strip_tags($this->group_id));
        $stmt->bindParam(':group_id', $this->group_id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
       
    function nameExists(){    //===== check if given name exist in the database
        $query = "SELECT group_id, group_name
                FROM " . $this->table_name . "
                WHERE group_name = ?
                LIMIT 0,1";    
        $stmt = $this->conn->prepare( $query );   
        $this->group_name=htmlspecialchars(strip_tags($this->group_name));   
        $stmt->bindParam(1, $this->group_name);    
        $stmt->execute();    
        $num = $stmt->rowCount();    
        if($num>0){    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            $this->group_id = $row['group_id'];
            $this->group_name = $row['group_name']; 
            return true;
        }    
        return false;
    }


    function newnameExit(){ //===== ตรวจสอบว่า name นี้ซืำหรือไม่
        $sql="SELECT group_id, group_name
        FROM " . $this->table_name. "
        WHERE (group_name = :group_name AND group_id != :group_id)";

        $stmt = $this->conn->prepare($sql);
        $this->group_name = htmlspecialchars(strip_tags($this->group_name));
        $this->group_id = htmlspecialchars(strip_tags($this->group_id));

        $stmt->bindParam(':group_name',$this->group_name);
        $stmt->bindParam(':group_id',$this->group_id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  
            return true;
        }
        return false;
    }
    
    public function update(){ // update a user record
        $query = "UPDATE " . $this->table_name . "
                SET
                    bd_id = :bd_id,
                    group_name = :group_name,
                    group_mc = :group_mc,
                    group_rpm = :group_rpm 
                WHERE group_id = :group_id";
    
        $stmt = $this->conn->prepare($query);    
        $this->group_id=htmlspecialchars(strip_tags($this->group_id));
        $this->bd_id=htmlspecialchars(strip_tags($this->bd_id));
        $this->group_name=htmlspecialchars(strip_tags($this->group_name));
        $this->group_mc=htmlspecialchars(strip_tags($this->group_mc));
        $this->group_rpm=htmlspecialchars(strip_tags($this->group_rpm));
        $stmt->bindParam(':bd_id', $this->bd_id);
        $stmt->bindParam(':group_name', $this->group_name);
        $stmt->bindParam(':group_mc', $this->group_mc);
        $stmt->bindParam(':group_rpm', $this->group_rpm);     

        $stmt->bindParam(':group_id', $this->group_id);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

 
}

?>

