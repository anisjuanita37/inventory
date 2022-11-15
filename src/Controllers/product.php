<?php

use Slim\Http\Request; //namespace
use Slim\Http\Response; //namespace

include __DIR__ . '/../Controllers/function/productsProc.php';

$app->options('/{routes:.+}', function ($request, $response, $args) {
  return $response;
});

$app->add(function ($req, $res, $next) {
  $response = $next($req, $res);
  return $response
          ->withHeader('Access-Control-Allow-Origin', '*')
          ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
          ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});


//request table products by condition (id)
$app->get('/products/[{id}]', function ($request, $response, $args){
    
$productId = $args['id'];
if (!is_numeric($productId)) {
  return $this->response->withJson(array('error' => 'numeric paremeter required'), 400);
}

$data = getProduct($this->db,$productId);
if (empty($data)) {
  return $this->response->withJson(array('error' => 'no data'), 400);
}
return $this->response->withJson(array('data' => $data), 200);
});

// read product by name
$app->get('/prod/[{name}]',function (Request $request, Response $response, array $arg)
{
$productName= $arg['name'];
$data = getProdName($this->db, $productName);

return $this->response->withJson(array('data' => $data), 200);
}); 

// read all data from table products
$app->get('/products',function (Request $request, Response $response, array $arg)
{
$data = getAllProducts($this->db);

if (is_null($data)) {
  return $this->response->withJson(array('error' => 'no data'), 400);
}

return $this->response->withJson(array('data' => $data), 200);
}); 


$app->post('/insertProduct', function(Request $request, Response $response,array $arg){  

$form_data=$request->getParsedBody();
$data = createProduct($this->db, $form_data);

if (is_null($data)) {
  return $this->response->withJson(array('error' => 'no data'), 400);
}

return $this->response->withJson(array('data' => 'successfully added'), 200);
});


//put table products
$app->put('/products/put/[{id}]', function ($request,  $response,  $args){

$productId = $args['id'];
$date = date("Y-m-j h:i:s");
  
if (!is_numeric($productId)) {
  return $this->response->withJson(array('error' => 'numeric paremeter required'), 400);
}

$form_dat=$request->getParsedBody();
$data=updateProduct($this->db,$form_dat,$productId,$date);

if ($data <=0)
  return $this->response->withJson(array('data' => 'successfully updated'), 200);
});

//delete product record by id
$app->delete('/products/del/[{id}]', function ($request, $response, $args){
    
$productId = $args['id'];
if (!is_numeric($productId)) {
  return $this->response->withJson(array('error' => 'numeric paremeter required'), 400);
}

$data = deleteProduct($this->db,$productId);
if (empty($data)) {
 return $this->response->withJson(array($productId => 'is successfully deleted'), 200);};
});