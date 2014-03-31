<?php
/**
* Simple wrapper class for retrieving page statistics from the Google page speed API 
*
* @package Google PageSpeed Wrapper
* @author Anthony Mills <me@anthony-mills.com>
* @link https://github.com/anthony-mills/google-pagespeed
* @copyright 2013 
* @license GPL V3.0
* @version 0.1
*/
class GooglePageSpeed {
	protected $_apiKey = '';
	protected $_prettyPrint = TRUE;
	protected $_apiEndpoint = 'https://www.googleapis.com/pagespeedonline/v1/runPagespeed';
	
	public function __construct($apiKey = NULL) 
	{
		if (empty($apiKey)) {
			die('<span class="error_msg">No Google API key provided.</span>');
		} else {
			$this->_apiKey = $apiKey;
		}
	}
	
	/**
	 * 
	 * When enabled Google will return a response formatted with indentations and line breaks
	 * 
	 * @param boolean $prettyPrint
	 */
	public function setPrettyPrint($prettyPrint = TRUE)
	{
		if ($prettyPrint == TRUE) {
			$this->_prettyPrint = TRUE;
		} else {
			$this->_prettyPrint = FALSE;
		}
		
	}
	
	/**
	 * 
	 * Convert a number of bytes into something readable i.e 10 KB etc
	 * 
	 * @param int $amountBytes
	 * 
	 * @return string 
	 */
	function readableBytes($amountBytes) { 
	    $byteBase = log($amountBytes) / log(1024);
	    $amountNames = array('', 'k', 'M', 'G', 'T');   
	
	    return round(pow(1024, $byteBase - floor($byteBase)), 0) . $amountNames[floor($byteBase)];
	} 	

	/**
	 *
	 * Request the PageSpeed details on a URL
	 * 
	 * @param string $pageUrl
	 *  
	 */
	public function checkUrl($pageUrl)
	{
		if (filter_var($pageUrl, FILTER_VALIDATE_URL) === FALSE) {
 		   die('<span class="error_msg">The URL provided is not valid.</span>');
		} else {
			return $this->_processRequest($pageUrl);
		}
	}
	
	/**
	 * Contact the PageSpeed API and request the feedback on a URL
	 * 
	 * @param string $pageUrl
	 * @return object
	 */
	protected function _processRequest($pageUrl)
	{
		$apiRequest = $this->_apiEndpoint . '?key=' . $this->_apiKey . '&url=' . $pageUrl;
		
		if (!$this->_prettyPrint) {
			$apiRequest .= '&prettyprint=false';
		}
		
		$curlHandle = curl_init($apiRequest);
		curl_setopt($curlHandle, CURLOPT_VERBOSE, 1);
		curl_setopt($curlHandle, CURLOPT_NOBODY, 0);
		curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION,1);
		curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		
		$apiResponse = curl_exec($curlHandle);
		$responseInformation = curl_getinfo($curlHandle);
		curl_close($curlHandle);
		
		if( intval( $responseInformation['http_code'] ) == 200 ) {
			return json_decode($apiResponse);
		}	
	}	
}