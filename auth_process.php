<?php
	require_once ("globals.php");
	require_once ("db.php");
	require_once("models/User.php");
	require_once("models/Message.php");
	require_once("dao/UserDAO.php");


	$message = new Message($BASE_URL);
	$userDao = new UserDao($conn, $BASE_URL);

	//verifica o tipo do formulario
	$type = filter_input(INPUT_POST, "type");
	

	//verificação do tipo de formulario
	if($type === "register"){
		$name = filter_input(INPUT_POST, "name");
		$lastname = filter_input(INPUT_POST, "lastname");
		$email = filter_input(INPUT_POST, "email");
		$password = filter_input(INPUT_POST, "password");
		$confirmpassword = filter_input(INPUT_POST, "confirmpassword");

		//verificaçao de dados minimos
		if($name && $lastname && $email && $password){
			//verificar se o e-mail está cadastrado no sistema
			if($userDao->findByEmail($email === false)){
				$user = new User();

				//criacao de tokem e senha
				$userToken = $user->generateToken();
				//$finalPassword = password_hash($password, PASSWORD_DEFAULT);
				//aqui foi criada a função para gerar o password em USER.php
				$finalPassword = $user->generatePassword($password);

				$user->name = $name;
				$user->lastname = $lastname;
				$user->email = $email;
				$user->password = $finalPassword;
				$user->token = $userToken;

				$auth = true;

				$userDao->create($user, $auth);
				
			}else{
				//Enviar msg de erro, usuário já existe
				$message->setMessage("Usuário já cadastrado, tente outro email.", "error", "back");
			}



			//verificar se a senhas batem
			if($password === $confirmpassword){

			} else {
				$message->setMessage("As senhas não são iguais!", "error", "back");
			}

		} else {
			//Enviar uma msg de erro, de dados faltantes
			$message->setMessage("Por favor, preencha todos os campos.", "error", "back");

		}
	} else if($type === "login"){

	}