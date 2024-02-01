<?php
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
	
	error_log(print_r($data,true));

	$secret = 'NRCW7hRTpBnpzhPLhu8oqcUzCdyIFtPzg2u5HikW';  // WLAN API Key, obtained from the Mist Web GUI after creating the WLAN
	$wlan_id = $data['wlan_id'];
	$ap_mac = $data['ap_mac'];
	$client_mac = $data['client_mac'];

	$data['url'] = "http://www.google.com";
	$url = $data['url'];
	$ap_name = $data['ap_name'];
	$site_name = $data['site_name'];

	error_log(print_r($data,true));

	$authorize_min = 120;  // Duration (in minutes) the guest MAC address is authorized before they are redirected back to the portal page
	$download_kbps = 0;  // Download limit (in kbps) per client. Recommended to leave as 0 (unlimited), as this can be set globally in the WLAN
	$upload_kbps = 0;  // Upload limit (in kbps) per client. Recommended to leave as 0 (unlimited), as this can be set globally in the WLAN
	$quota_mbytes = 0;  // Quota (in mbytes) per client. Recommended to leave as 0 (unlimited)
	$context = sprintf('%s/%s/%s/%d/%d/%d/%d',
		$wlan_id, $ap_mac, $client_mac,
		$authorize_min,
		$download_kbps, $upload_kbps, $quota_mbytes,
	);
	$token = urlencode(base64_encode($context));

	$name = $data['name'];
	$email = $data['email'];

	$forward = urlencode($url);  // URL the user is forwarded to after authorization
	$extra = '&forward=' . $forward;
	$extra .= '&authorize_only=true';
	$extra .= '&name=' . urlencode("$name");
	$extra .= '&email=' . urlencode("$email");
	$expires = time() + 10050;  // The time until which the authorization URL is valid
	$payload = sprintf('expires=%d&token=%s%s', $expires, $token, $extra);

	$signature = urlencode(base64_encode(hash_hmac('sha1', $payload, $secret, true)));

	$final_url = sprintf('https://portal.mist.com/authorize?signature=%s&%s', $signature, $payload);

	//$final_url = sprintf('https://portal.mist.com/authorize-test?signature=%s&%s', $signature, $payload);

	//Debug code used for testing purposes only
	//If set to true, display the variable details without authorizing the guest in the Mist cloud
	$debugging = false;
	if ($debugging) {
		$response = [
			'status' => 'success',
		 	'message' => 'Usuario agregado correctamente!',
			'token          : urlencode(base64(%s))'  => $context . PHP_EOL,
			'                 %s' => $token . PHP_EOL,
			'forward' => $url . PHP_EOL,
			'payload-to-sign' => $payload . PHP_EOL,
			'signature' => $signature . PHP_EOL,
			'URL' => $final_url . PHP_EOL,
			'client_mac' => $client_mac . PHP_EOL,
			'ap_mac' => $ap_mac . PHP_EOL,
			'ap_name' => $ap_name . PHP_EOL,
			'wlan_id' => $wlan_id . PHP_EOL,
			'site_name' => $site_name . PHP_EOL,
			'name' => $name . PHP_EOL,
			'email' => $email . PHP_EOL,
		];
		echo json_encode($response);
	}
	else {
		error_log($final_url);

		// Guest is redirected to the Mist portal for authorization. If successful, the Mist portal will then redirect the guest to the $url 
		$response = ['status' => 'success', 'message' => 'Usuario conectado correctamente!', 'url' => $final_url, 'name' => $name, 'email' => $email];
		echo json_encode($response);
	}
?>