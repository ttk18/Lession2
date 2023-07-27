<?php
    class category{

        private $conn;
        private $table = "category";

        public $id;
        public $name;
        public $parent_id;

        public function __construct($db){
            $this->conn = $db;
        }

        public function data_tree($data, $parent_id, $level = 0 ) {
            $result = [];
            foreach($data as $item){
                if( $item['parent_id'] == $parent_id){
                    $item['level'] = $level;
                    $result[] = $item;
                    $child =  $this->data_tree($data, $item['id'], $level + 1);
                    $result = array_merge($result,$child);
                }
            }
            return $result;
        }
 

        public function read_all(){
            $sql = "SELECT * FROM  $this->table ";
            
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt; 
        }

        public function read_search($keyword){
            $sql = "SELECT * FROM  $this->table 
            where name like '%$keyword%'" ;

            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            return $stmt; 
        }
    

        public function create(){
            $sql = "INSERT INTO $this->table
                    SET name  = :name,
                    parent_id = :parent_id";

            $stmt = $this->conn->prepare($sql);
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->parent_id = htmlspecialchars(strip_tags($this->parent_id));
            $stmt->bindParam(':name', $this->name);
            $stmt->bindParam(':parent_id', $this->parent_id);

            if($stmt->execute()){
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }

        public function update(){
            $query = "Update $this->table
                        SET name  = :name,
                        parent_id = :parent_id
                      where 
                      id = :get_id";
            
            $stmt = $this->conn->prepare($query);
            $this->name = htmlspecialchars(strip_tags($this->name));
            $this->parent_id = htmlspecialchars(strip_tags($this->parent_id));
            $stmt->bindParam(':get_id',$this->id);
            $stmt->bindParam(':name',$this->name);
            $stmt->bindParam(':parent_id',$this->parent_id);

            if($stmt->execute()){
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }


        public function delete(){
            $sql = "delete from $this->table
                    where id = :get_id";
            
            $stmt = $this->conn->prepare($sql);
            $this->id = htmlspecialchars(strip_tags($this->id));
            $stmt->bindParam(':get_id', $this->id);
            
            if($stmt->execute()){
                return true;
            }
            printf("Error: %s.\n", $stmt->error);
            return false;
        }
    }
    
?>