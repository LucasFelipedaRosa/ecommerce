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

	$app->get("/administrador/users", function(){

		User::verifyLogin();

		$users = User::listAll();

		$page = new PageAdmin();

		$page->setTpl("users", array(
			"users" => $users
		));
	});

	$app->get("/administrador/users/create", function(){

		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("users-create");
	});

	$app->get("/administrador/users/:iduser/delete", function($iduser){

		User::verifyLogin();

		$user= new User();

		$user->get((int)$iduser);

		$user->delete();

		header("Location: /administrador/users");

		exit;
	});

	$app->get("/admininstrador/users/:iduser", function($iduser){

		User::verifyLogin();

		$user = new User();

		$user->get((int)$iduser);

		$page = new PageAdmin();

		$page->setTpl("users-update",array(
			"user"=>$user->getValues()
		));
	});

	$app->post("/administrador/users/create", function () {

 		User::verifyLogin();

		$user = new User();

 		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

 		$_POST['despassword'] = password_hash($_POST["despassword"], PASSWORD_DEFAULT, [

 			"cost"=>12

 		]);

 		$user->setData($_POST);

		$user->save();

		header("Location: /administrador/users");
 		exit;

});
	
	$app->post("/administrador/users/:iduser", function($iduser){

		User::verifyLogin(); 

		$user = new User();

		$_POST["inadmin"] = (isset($_POST["inadmin"])) ? 1 : 0;

		$user->get((int)$iduser);

		$user->setData($_POST);

		$user->update();

		header("Location: /administrador/users");

		exit;
	});
	


	$app->run();
?>