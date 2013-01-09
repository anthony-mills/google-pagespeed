<?php
if(isset($_POST['page_url'])) {
	$apiKey = 'Enter Your API Key';
	require_once('../includes/GooglePageSpeed.php');
	
	$pageSpeed = new GooglePageSpeed($apiKey);
	$pageResult = $pageSpeed->checkUrl($_POST['page_url']);
	
	if ((!empty($pageResult->responseCode) && ($pageResult->responseCode == '200'))) {
		$pageReport = file_get_gontents('views/page_report.phtml');
		
	} else {
		echo '<span class="error_msg">The URL provided was unable to be retrieved.</span>';
	}
} else {
	$pageContents = file_get_contents('views/check_url.phtml');
	echo str_replace('{script_url}', $_SERVER['PHP_SELF'], $pageContents);
} 
