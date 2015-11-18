<?php

	function getURL() {
		if (isset($_GET['url'])) {
			return base64_decode($_GET['url']);
		} else if (isset($_POST['url'])) {
			return $_POST['url'];
		}
		return 'https:// www.google.com';
	}

	function getProxy() {
		$data = json_decode(file_get_contents('http://gimmeproxy.com/api/get/8bb99df808d75d71ee1bdd9e5d/?timeout=0'), 1);
		if(isset($data['error'])) { // there are no proxies left for this user-id and timeout
			echo $data['error']."\n";
		} 
		return isset($data['error']) ? false : $data['curl'];
	}

	function get($url) {
		$curlOptions = array(
			CURLOPT_CONNECTTIMEOUT => 5,
			CURLOPT_URL => $url,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_MAXREDIRS => 9,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_HEADER => 0,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/39.0.2171.95 Safari/537.36",
			CURLINFO_HEADER_OUT  => true,
		);
		$curl = curl_init();
		curl_setopt_array($curl, $curlOptions);
		if($proxy = getProxy()) {
			//echo 'set proxy '.$proxy."\n";
			curl_setopt($curl, CURLOPT_PROXY, $proxy);
		}
		$data = curl_exec($curl);
		echo curl_error($curl);
		curl_close($curl);
		return $data;
	}
	$def_url = getURL();
	//echo 'getting ' . $def_url;
	//echo get($def_url);
	//for($i = 0; $i < 5; $i++) {
  //echo trim(get($def_url))."\n";
	//}
  echo get($def_url);
?>