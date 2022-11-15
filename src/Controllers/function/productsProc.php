<?php
//get all products
function getAllProducts($db)
{
$sql = 'Select p.name, p.description, p.price, c.name as category from products p ';
$sql .='Inner Join categories c on p.category_id = c.id';
$stmt = $db->prepare ($sql);
$stmt ->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//get product by id
function getProduct($db, $productId)
{
$sql = 'Select p.name, p.description, p.price, c.name as category from products p ';
$sql .= 'Inner Join categories c on p.category_id = c.id ';
$sql .= 'Where p.id = :id'; 
$stmt = $db->prepare ($sql);
$id = (int) $productId;
$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//insert new product
function createProduct($db, $form_data)
{
$sql = 'Insert into products (name, description, price, category_id, created) ';
$sql .= 'values (:name, :description, :price, :category_id, :created)';
$stmt = $db->prepare ($sql);
$stmt->bindParam(':name', $form_data['name']);
$stmt->bindParam(':description', $form_data['description']);
$stmt->bindParam(':price', floatval($form_data['price']));
$stmt->bindParam(':category_id', intval($form_data['category_id']));
$stmt->bindParam(':created', $form_data['created']);
$stmt->execute();
return $db->lastInsertID();//insert last number.. continue
}


//update product by id
function updateProduct($db,$form_dat,$productId,$date) 
{
$sql = 'UPDATE products SET name = :name , description = :description , price = :price , category_id = :category_id , modified = :modified';
$sql .=' WHERE id = :id';

$stmt = $db->prepare ($sql);
$id = (int)$productId;
$mod = $date;

$stmt->bindParam(':id', $id, PDO::PARAM_INT);
$stmt->bindParam(':name', $form_dat['name']);
$stmt->bindParam(':description', $form_dat['description']);
$stmt->bindParam(':price', floatval($form_dat['price']));
$stmt->bindParam(':category_id', intval($form_dat['category_id']));
$stmt->bindParam(':modified', $mod , PDO::PARAM_STR);
$stmt->execute();
}

//get product by name
function getProdName($db, $productName)
{
$sql = 'Select * from products ';
$sql .= 'Where name = :name'; 
$stmt = $db->prepare ($sql);
$name = $productName;
$stmt->bindParam(':name',$name);
$stmt->execute();
return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


//delete product by id
function deleteProduct($db,$productId) 
{
    $sql = ' Delete from products where id = :id';
    $stmt = $db->prepare($sql);
    $id = (int)$productId;
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
}