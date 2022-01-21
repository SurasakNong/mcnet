<?php
class Shift{     
        // database connection and table name
        private $conn;
        private $table_name = "shift";
    
        // object properties
        public $shift_id;
        public $shift_name;
        public $shift_count;
        public $shift_be1;
        public $shift_en1;
        public $shift_be2;
        public $shift_en2;
        public $shift_be3;
        public $shift_en3;
     
        // constructor
        public function __construct($db){
            $this->conn = $db;
        } 
    
    function create(){    //===== create new record
        $this->shift_count=htmlspecialchars(strip_tags($this->shift_count));
        if($this->shift_count == "3"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1, shift_be2 = :shift_be2, shift_en2 = :shift_en2, shift_be3 = :shift_be3,shift_en3 = :shift_en3";
        }else if($this->shift_count == "2"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1, shift_be2 = :shift_be2, shift_en2 = :shift_en2";
        }else if($this->shift_count == "1"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1";
        }else {
            return false;
        }

        $query = "INSERT INTO " . $this->table_name . "
                SET
                    shift_name = :shift_name,
                    shift_count = :shift_count
                    {$shiftset}
                     ";

        $stmt = $this->conn->prepare($query);
        $this->shift_name=htmlspecialchars(strip_tags($this->shift_name));        
        if($this->shift_count == "3"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $this->shift_be2=htmlspecialchars(strip_tags($this->shift_be2));
            $this->shift_en2=htmlspecialchars(strip_tags($this->shift_en2));
            $this->shift_be3=htmlspecialchars(strip_tags($this->shift_be3));
            $this->shift_en3=htmlspecialchars(strip_tags($this->shift_en3));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
            $stmt->bindParam(':shift_be2', $this->shift_be2);
            $stmt->bindParam(':shift_en2', $this->shift_en2);
            $stmt->bindParam(':shift_be3', $this->shift_be3);
            $stmt->bindParam(':shift_en3', $this->shift_en3);
        }else if($this->shift_count == "2"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $this->shift_be2=htmlspecialchars(strip_tags($this->shift_be2));
            $this->shift_en2=htmlspecialchars(strip_tags($this->shift_en2));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
            $stmt->bindParam(':shift_be2', $this->shift_be2);
            $stmt->bindParam(':shift_en2', $this->shift_en2);
        }else if($this->shift_count == "1"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
        }else{
            return false;
        }
        $stmt->bindParam(':shift_name', $this->shift_name);
        $stmt->bindParam(':shift_count', $this->shift_count);

        if($stmt->execute()){
            return true;
        }    
        return false;
    } 

    // delete a record
    public function delete(){
        $query = "DELETE FROM " . $this->table_name . " WHERE shift_id = :shift_id";
        $stmt = $this->conn->prepare($query);    
        $this->shift_id = htmlspecialchars(strip_tags($this->shift_id));
        $stmt->bindParam(':shift_id', $this->shift_id);
        if($stmt->execute()){
            return true;
        }
        return false;
    }
       
    function nameExists(){    //===== check  name exist in the database
        $query = "SELECT shift_id, shift_name
                FROM " . $this->table_name . "
                WHERE shift_name = ?
                LIMIT 0,1";    
        $stmt = $this->conn->prepare( $query );   
        $this->shift_name=htmlspecialchars(strip_tags($this->shift_name));   
        $stmt->bindParam(1, $this->shift_name);    
        $stmt->execute();    
        $num = $stmt->rowCount();    
        if($num>0){    
            $row = $stmt->fetch(PDO::FETCH_ASSOC);    
            $this->shift_id = $row['shift_id'];
            $this->shift_name = $row['shift_name']; 
            return true;
        }    
        return false;
    }


    function newnameExit(){ //===== ตรวจสอบว่า name นี้ซืำหรือไม่
        $sql="SELECT shift_id, shift_name
        FROM " . $this->table_name. "
        WHERE (shift_name = :shift_name AND shift_id != :shift_id)";

        $stmt = $this->conn->prepare($sql);
        $this->shift_name = htmlspecialchars(strip_tags($this->shift_name));
        $this->shift_id = htmlspecialchars(strip_tags($this->shift_id));

        $stmt->bindParam(':shift_name',$this->shfit_name);
        $stmt->bindParam(':shift_id',$this->shift_id);
        $stmt->execute();
        $num = $stmt->rowCount();
        if($num>0){  
            return true;
        }
        return false;
    }
    
    public function update(){ // update record        
        $this->shift_count=htmlspecialchars(strip_tags($this->shift_count));
        
        if($this->shift_count == "3"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1, shift_be2 = :shift_be2, shift_en2 = :shift_en2, shift_be3 = :shift_be3,shift_en3 = :shift_en3 ";
        }else if($this->shift_count == "2"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1, shift_be2 = :shift_be2, shift_en2 = :shift_en2, shift_be3 = NULL, shift_en3 = NULL ";
        }else if($this->shift_count == "1"){
            $shiftset = ", shift_be1 = :shift_be1, shift_en1 = :shift_en1, shift_be2 = NULL, shift_en2 = NULL, shift_be3 = NULL, shift_en3 = NULL ";
        }else{
            return false;
        }

        $query = "UPDATE " . $this->table_name . "
                SET
                    shift_name = :shift_name,
                    shift_count = :shift_count 
                    {$shiftset}
                WHERE shift_id = :shift_id";
    
        $stmt = $this->conn->prepare($query);    
        $this->shift_id=htmlspecialchars(strip_tags($this->shift_id));
        $this->shift_name=htmlspecialchars(strip_tags($this->shift_name));

        if($this->shift_count == "3"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $this->shift_be2=htmlspecialchars(strip_tags($this->shift_be2));
            $this->shift_en2=htmlspecialchars(strip_tags($this->shift_en2));
            $this->shift_be3=htmlspecialchars(strip_tags($this->shift_be3));
            $this->shift_en3=htmlspecialchars(strip_tags($this->shift_en3));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
            $stmt->bindParam(':shift_be2', $this->shift_be2);
            $stmt->bindParam(':shift_en2', $this->shift_en2);
            $stmt->bindParam(':shift_be3', $this->shift_be3);
            $stmt->bindParam(':shift_en3', $this->shift_en3);
        }else if($this->shift_count == "2"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $this->shift_be2=htmlspecialchars(strip_tags($this->shift_be2));
            $this->shift_en2=htmlspecialchars(strip_tags($this->shift_en2));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
            $stmt->bindParam(':shift_be2', $this->shift_be2);
            $stmt->bindParam(':shift_en2', $this->shift_en2);
        }else if($this->shift_count == "1"){
            $this->shift_be1=htmlspecialchars(strip_tags($this->shift_be1));
            $this->shift_en1=htmlspecialchars(strip_tags($this->shift_en1));
            $stmt->bindParam(':shift_be1', $this->shift_be1);
            $stmt->bindParam(':shift_en1', $this->shift_en1);
        }else{
            return false;
        }   
        $stmt->bindParam(':shift_name', $this->shift_name);
        $stmt->bindParam(':shift_count', $this->shift_count);
        $stmt->bindParam(':shift_id', $this->shift_id);    
        if($stmt->execute()){
            return true;
        }    
        return false;
    }

 
}

?>

