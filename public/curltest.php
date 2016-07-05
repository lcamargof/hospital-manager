<?php
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, 'http://testcURL.com');
	$result = curl_exec($curl);

	echo $result;
 ?>

