<?php

    require_once("model.base.php");//* só para importar uma vez

    class Orders extends Base {
       

        public function get() {
            $query = $this->db->prepare("
                SELECT 
                    orders.order_id,
                    orders.user_id,
                    users.name,
                    users.email,
                    orders.order_date,
                    orders.payment_date, 
                    orders.shipment_date
                FROM 
                    orders
                INNER JOIN 
                    users USING(user_id)

            ");

            $query->execute();

            return $query->fetchAll();
        }

       

        public function getId($id) {
            
            
            $query = $this->db->prepare("
                SELECT 
                    orders.order_id,
                    orders.user_id,
                    users.name,
                    users.str_address,
                    users.zipcode,
                    users.city,
                    users.phone,
                    orders.order_date,
                    orders.payment_date, 
                    orders.shipment_date
                FROM 
                    orders
                INNER JOIN 
                    users USING(user_id)
                WHERE
                    orders.order_id = ?

            ");

      

            $query->execute([ $id ]);

            $data = $query->fetch();

            if(!empty($data)) {
                $data["products"] = $this->getDetails($id);
            }

            return $data;
        }


        public function getDetails($id) {

            $query = $this->db->prepare("
                SELECT 
                    products.product_id,
                    products.name,
                    orderdetails.quantity,
                    orderdetails.price_each
                FROM 
                    orderdetails
                INNER JOIN 
                    products USING(product_id)
                WHERE
                    orderdetails.order_id = ?

            ");

            $query->execute([$id]);

            return $query->fetchAll();
        }


        public function post($data) {
            $query = $this->db->prepare("
                INSERT INTO orders
                (user_id)
                VALUES(?)
            ");

            $query->execute([
                $data["user_id"],
                
            ]);
                
            
            $data["order_id"] = $this->db->lastInsertId();

            foreach($data["products"] as $product) {
                $query = $this->db->prepare("
                    INSERT INTO orderdetails
                    (order_id, product_id, quantity, price_each)
                
                    SELECT 
                        ? AS order_id, 
                        product_id, 
                        ? AS quantity, 
                        price AS price_each
                    FROM 
                        products
                    WHERE 
                        product_id = ?
                ");
                $query->execute([
                    $data["order_id"],
                    $product["quantity"],
                    $product["product_id"],
                    
                ]);
            }


            return $data;



        }

        public function put($data) {
            $data = $this->sanitizer($data);

            $query = $this->db->prepare("
                DELETE FROM orderdetails
                WHERE order_id = ?
            ");
                
        

            $query->execute([
                $data["order_id"],
               
            ]);

            foreach($data["products"] as $product) {
                $query = $this->db->prepare("
                    INSERT INTO orderdetails
                    (order_id, product_id, quantity, price_each)
                
                    SELECT 
                        ? AS order_id, 
                        product_id, 
                        ? AS quantity, 
                        price AS price_each
                    FROM 
                        products
                    WHERE 
                        product_id = ?
                ");
                $query->execute([
                    $data["order_id"],
                    $product["quantity"],
                    $product["product_id"],
                    
                ]);
            }

            return $data;



        }

        public function delete($id) {
            $query = $this->db->prepare("
                DELETE FROM orderdetails
                WHERE order_id = ?
            ");

            $query->execute([ $id ]);

            $query = $this->db->prepare("
                DELETE FROM orders
                WHERE order_id = ?
            ");

            $query->execute([ $id ]);
            
            



            return $query->rowCount();
        }
    }
?>

