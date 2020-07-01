<?php

	use\Hcode\Model;
	use\Hcode\PageAdmin;
	use\Hcode\Model\User;
	use\Hcode\Model\Order;
	use\Hcode\Model\Cart;
	use\Hcode\Model\OrderStatus;


	$app->get("/administrador/orders/:idorder/delete", function($idorder){

		User::verifyLogin();

		$order = new Order();

		$order->get((int)$idorder);

		$order->delete();

		header('Location: /administrador/orders');

		exit;
	});

	$app->get("/administrador/orders/:idorder/status", function($idorder){

		User::VerifyLogin();

		$page= new PageAdmin();

		$order = new Order();

		$order->get((int)$idorder);

		$cart = $order->getCart();

		$page->setTpl("order-status",[
			"order"=>$order->getValues(),
			"status"=>OrderStatus::listALl(),
			"msgError"=>Order::getError(),
			"msgSuccess"=>Order::getSuccess()
		]);
	});

	$app->post("/administrador/orders/:idorder/status", function($idorder){

		User::VerifyLogin();

		if(!isset($_POST['idstatus']) || !(int)$_POST['idstatus']>0) {
			Order::setError("Informe o status atual");
			header("Location: /administrador/orders/".$idorder."/status");
			exit;
		}

		$order = new Order();

		$order->get((int)$idorder);

		$order->setidstatus($_POST['idstatus']);

		$order->save();

		Order::setSuccess("Status atualizado");

		header("Location: /administrador/orders/".$idorder."/status");

		exit;
		
	});

	$app->get("/administrador/orders/:idorder", function($idorder){

		User::VerifyLogin();

		$page= new PageAdmin();

		$order = new Order();

		$order->get((int)$idorder);

		$cart = $order->getCart();

		$page->setTpl("order",[
			"order"=>$order->getValues(),
			"cart"=>$cart->getValues(),
			"products"=>$cart->getProducts()
		]);
	});


	$app->get("/administrador/orders", function(){
		
		User::verifyLogin();

		$page = new PageAdmin();

		$page->setTpl("orders",[
			"orders"=>Order::listALl(),	
		]);
	});

?>