<?php

    require_once("model.base.php");//* só para importar uma vez

    class Products extends Base {
       

        public function get() {
            $query = $this->db->prepare("
                SELECT product_id, category_id, name, description, price, stock
                FROM products
            ");

            $query->execute();

            return $query->fetchAll();
        }

        public function getId($id) {

            $query = $this->db->prepare("
                SELECT product_id, category_id, name, description, price, stock, image
                FROM products
                WHERE product_id = ?

            ");

            $query->execute([ $id ]);

            return $query->fetch();
        }

        public function post($data) {
            $query = $this->db->prepare("
                INSERT INTO products
                (category_id, name, description, price, stock, image)
                VALUES(?,?,?,?,?,?)
            ");

            $query->execute([
                $data["category_id"],
                $data["name"],
                $data["description"],
                $data["price"],
                $data["stock"],
                $data["image"],
                
            ]);
            
            $data["product_id"] = $this->db->lastInsertId();



            return $data;



        }

        public function put($data) {
            $query = $this->db->prepare("
                UPDATE 
                    products
                SET 
                    caregory_id = ?,
                    name = ?,
                    description = ?,
                    price = ?,
                    stock = ?,
                    image = ?,
                

                WHERE
                    product_id = ?
            ");

            $query->execute([
                $data["category_id"],
                $data["name"],
                $data["description"],
                $data["price"],
                $data["stock"],
                $data["image"],
                $data["product_id"]
            ]);

            return $data;



        }

        public function delete($id) {
            $query = $this->db->prepare("
                DELETE FROM products
                WHERE product_id
            ");

            $query->execute([ $id ]);
            
            



            return $data;
        }
    }
?>