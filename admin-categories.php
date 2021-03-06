<?php

	use \Hcode\PageAdmin;
	use \Hcode\Model\User;
	use \Hcode\Model\Category;
	use \Hcode\Model\Product;

	$app->get("/administrador/categories", function(){

		User::verifyLogin();

		$search = (isset($_GET['search'])) ? $_GET['search'] : "";
		$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;

		if ($search != '') {

			$pagination = Category::getPageSearch($search, $page);

		} else {

			$pagination = Category::getPage($page);

		}

		$pages = [];

		for ($x = 0; $x < $pagination['pages']; $x++)
		{

			array_push($pages, [
				'href'=>'/administrador/categories?'.http_build_query([
					'page'=>$x+1,
					'search'=>$search
				]),
				'text'=>$x+1
			]);

		}

		$page = new PageAdmin();

		$page->setTpl("categories", [
			"categories"=>$pagination['data'],
			"search"=>$search,
			"pages"=>$pages
		]);	

	});

	$app->get("/administrador/categories/create", function(){

		User::verifyLogin();

		$categories = Category::listAll();

		$page = new PageAdmin();

		$page->setTpl("categories-create");
	});

	$app->post("/administrador/categories/create", function(){

		User::verifyLogin();

		$categories = Category::listAll();

		$category = new Category();

		$category->setData($_POST);

		$category->save();

		header("Location: /administrador/categories");

		exit;

	});

	$app->get("/administrador/categories/:idcategory/delete", function($idcategory){

		User::verifyLogin();

		$categories = Category::listAll();

		$category = new Category();

		$category->get((int)$idcategory);

		$category->delete();

		header("Location: /administrador/categories");

		exit;

	});

	$app->get("/administrador/categories/:idcategory", function($idcategory){

		User::verifyLogin();

		$categories = Category::listAll();

		$category = new Category();

		$category->get((int)$idcategory);

		$page = new PageAdmin();

		$page->setTpl("categories-update", [
			'category'=>$category->getValues()
		]);
	});

	$app->post("/administrador/categories/:idcategory", function($idcategory){

		User::verifyLogin();

		$categories = Category::listAll();
		
		$category = new Category();

		$category->get((int)$idcategory);

		$category->setData($_POST);

		$category->save();

		header("Location: /administrador/categories");

		exit;
	});

	$app->get("/administrador/categories/:idcategory/products", function($idcategory){

		User::verifyLogin();

		$category = new Category();

		$category->get((int)$idcategory);

		$page = new PageADmin();

		$page->setTpl("categories-products",[
			'category'=>$category->getValues(),
			'productsRelated'=>$category->getProducts(true),
			'productsNotRelated'=>$category->getProducts(false)
		]);
	});

	$app->get("/administrador/categories/:idcategory/products/:idproduct/add", function($idcategory, $idproduct){

		User::verifyLogin();

		$category = new Category();

		$category->get((int)$idcategory);

		$product = new Product();

		$product->get((int)$idproduct);

		$category->addProduct($product);

		header("Location: /administrador/categories/".$idcategory."/products");
		exit;
	});

	$app->get("/administrador/categories/:idcategory/products/:idproduct/remove", function($idcategory, $idproduct){

		User::verifyLogin();

		$category = new Category();

		$category->get((int)$idcategory);

		$product = new Product();

		$product->get((int)$idproduct);

		$category->removeProduct($product);

		header("Location: /administrador/categories/".$idcategory."/products");
		exit;
	});
?>