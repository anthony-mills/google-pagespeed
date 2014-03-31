<?php
if(isset($_POST['page_url'])) {
	$apiKey = 'GOOGLE API KEY';
	require_once('../includes/GooglePageSpeed.php');
	
	$pageSpeed = new GooglePageSpeed($apiKey);
	$pageResult = $pageSpeed->checkUrl($_POST['page_url']);
	
	if ((!empty($pageResult->responseCode) && ($pageResult->responseCode == '200'))) {
		$pageReport = file_get_contents('views/page_report.phtml');
		
		$pageReport = str_replace('{page_url}', '<a href="' . $pageResult->id . '" target="_blank" title="' . $pageResult->title . '">'.$pageResult->id.'</a>', $pageReport);
		$pageReport = str_replace('{page_score}', $pageResult->score, $pageReport);
		$pageReport = str_replace('{total_resources}', $pageResult->pageStats->numberResources, $pageReport);
		$pageReport = str_replace('{total_hosts}', $pageResult->pageStats->numberHosts, $pageReport);		;
		$pageReport = str_replace('{html_bytes}', $pageSpeed->readableBytes($pageResult->pageStats->htmlResponseBytes), $pageReport);	
		$pageReport = str_replace('{css_bytes}', $pageSpeed->readableBytes($pageResult->pageStats->cssResponseBytes), $pageReport);	
		$pageReport = str_replace('{js_bytes}', $pageSpeed->readableBytes($pageResult->pageStats->javascriptResponseBytes), $pageReport);		
		$pageReport = str_replace('{image_bytes}', $pageSpeed->readableBytes($pageResult->pageStats->imageResponseBytes), $pageReport);							
		echo $pageReport;
	} else {
		echo '<span class="error_msg">The URL provided was unable to be retrieved.</span>';
	}
} else {
	$pageContents = file_get_contents('views/check_url.phtml');
	echo str_replace('{script_url}', $_SERVER['PHP_SELF'], $pageContents);
} 
