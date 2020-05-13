<?php

	use \Hcode\PageAdmin;
	use \Hcode\Model\User;
	
	$app->get('/administrador', function() {
	    
		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("index");
	});

	$app->get('/administrador/login', function(){
		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("login");
	});

	$app->post('/administrador/login', function() {
	    
		User::login($_POST["login"], $_POST["password"]);

		header("Location: /administrador");
		exit;
	});

	$app->get('/administrador/logout', function() {
	    
		User::logout();

		header("Location: /administrador/login");
		exit;
	});

	$app->get("/administrador/forgot", function() {

		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot");	

	});

	$app->post("/administrador/forgot", function(){

		$user = User::getForgot($_POST["email"]);

		header("Location: /administrador/forgot/sent");
		exit;

	});

	$app->get("/administrador/forgot/sent", function(){

		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot-sent");	

	});


	$app->get("/administrador/forgot/reset", function(){

		$user = User::validForgotDecrypt($_GET["code"]);

		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot-reset", array(
			"name"=>$user["desperson"],
			"code"=>$_GET["code"]
		));

	});

	$app->post("/administrador/forgot/reset", function(){

		$forgot = User::validForgotDecrypt($_POST["code"]);	

		User::setFogotUsed($forgot["idrecovery"]);

		$user = new User();

		$user->get((int)$forgot["iduser"]);

		$password = User::getPasswordHash($_POST["password"]);

		$user->setPassword($password);

		$page = new PageAdmin([
			"header"=>false,
			"footer"=>false
		]);

		$page->setTpl("forgot-reset-success");

	});

?>