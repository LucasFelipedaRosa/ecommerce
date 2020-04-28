<?php 
	session_start();
	require_once("vendor/autoload.php");

	use \Slim\Slim;
	use \Hcode\Page;
	use \Hcode\PageAdmin;
	use \Hcode\Model\User;

	$app = new Slim();

	$app->config('debug', true);

	$app->get('/', function() {
	    
		$page = new Page();

		$page->setTpl("index");
	});

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

	$app->run();
?>