<?php
	// Importa la biblioteca JWT
	require_once __DIR__ . '/vendor/autoload.php';

	use Firebase\JWT\JWT;

    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

	//$secret = 'uZNTgf3Osk2yXWbhhpSMuUsAPHKTCQU398Xk5lfC';  // WLAN API Key, obtained from the Mist Web GUI after creating the WLAN
	$secret = 'test-secret';

	$payload = array(
		"ap_mac" => $data['ap_mac'],
		"wlan_id" =>  $data['wlan_id'],
		"client_mac" =>  $data['client_mac'],
		"minutes" =>  480,
		"expires" =>  1768587994,
		"forward" =>  "https://gruporamle.com/portal/home.php",
		"authorize_only" => true,
		"name" => $data['name'],
		"email" => $data['email']
	);

	// Crea el token JWT
	$jwt = JWT::encode($payload, $secret, 'HS256');

	$final_url = sprintf('https://portal.mist.com/authorize-test?jwt=%s', $jwt);

	//$final_url = sprintf('https://portal.mist.com/authorize?jwt=%s', $jwt);

	//Debug code used for testing purposes only
	//If set to true, display the variable details without authorizing the guest in the Mist cloud
	$debugging = false;
	if ($debugging) {
		$response = [
			'status' => 'success',
		 	'message' => 'Usuario agregado correctamente!',
		];
		echo json_encode($response);
	}
	else {
		error_log($final_url);

		// Guest is redirected to the Mist portal for authorization. If successful, the Mist portal will then redirect the guest to the $url 
		$response = ['status' => 'success', 'message' => 'Usuario conectado correctamente!', 'url' => $final_url, 'name' => $data['name']];
		echo json_encode($response);
	}
?>