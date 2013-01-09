<?php
if(isset($_POST['page_url'])) {
	
} else {
	echo file_get_contents('views/check_url.phtml');
} 
