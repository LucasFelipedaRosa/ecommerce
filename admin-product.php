<?php 

	use \Hcode\PageAdmin;
	use \Hcode\Model\User;
	use \Hcode\Model\Product;

	$app->get("/administrador/products", function(){

		User::verifyLogin();

		$products = Product::listAll();

		$page = new PageAdmin();

		$page->setTpl("products",[
			"products"=>$products
		]);
	});

	$app->get("/administrador/products/create", function(){

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("products-create");
	});

	$app->post("/administrador/products/create", function(){

		User::verifyLogin();

		$product = new Product();

		$product->setData($_POST);

		var_dump($product);

		$product->save();

		header("Location: /administrador/products");

		exit;
	});

	$app->get("/administrador/products/:idproduct", function($idproduct){

		User::verifyLogin();

		$product = new Product();

		$product->get((int)$idproduct);

		$page = new PageAdmin();
		
		$page->setTpl("products-update",[
			"product"=>$product->getValues()
		]);
	});

	$app->post("/administrador/products/:idproduct", function($idproduct){

		User::verifyLogin();

		$product = new Product();

		$product->get((int)$idproduct);

		$product->setData($_POST);
	
		$product->save();

		$product->setPhoto($_FILES["file"]);

		header('Location: /administrador/products');
		exit;
	});

	$app->get("/administrador/products/:idproduct/delete", function($idproduct){

		User::verifyLogin();

		$product = new Product();

		$product->get((int)$idproduct);

		$product->delete();

		header('Location: /administrador/products');
		exit;
	});
?>