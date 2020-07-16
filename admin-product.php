<?php 

	use \Hcode\PageAdmin;
	use \Hcode\Model\User;
	use \Hcode\Model\Product;

	$app->get("/administrador/products", function(){

		User::verifyLogin();

		$search = (isset($_GET['search'])) ? $_GET['search'] : "";
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

		if ($search != '') {

			$pagination = Product::getPageSearch($search, $page);

		} else {

			$pagination = Product::getPage($page);

		}

		$pages = [];

		for ($x = 0; $x < $pagination['pages']; $x++)
		{

			array_push($pages, [
				'href'=>'/administrador/products?'.http_build_query([
					'page'=>$x+1,
					'search'=>$search
				]),
				'text'=>$x+1
			]);

		}

		$page = new PageAdmin();

		$page->setTpl("products", [
			"products"=>$pagination['data'],
			"search"=>$search,
			"pages"=>$pages
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