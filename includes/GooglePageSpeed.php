<?php
/**
* Simple for retreiving the 
*
* @package Google PageSpeed Wrapper
* @author Anthony Mills
* @copyright 2013 Anthony Mills ( anthony-mills.com )
* @license GPL V3.0
* @version 0.1
*/
class GooglePageSpeed {
	protected $_apiKey = '';
	protected $_prettyPrint = FALSE;
	
	public function __construct(str $apiKey = NULL) 
	{
		if (empty($apiKey)) {
			throw new Exception('No Google API key provided');
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
	 * Request the PageSpeed details on a URL
	 * 
	 * @param string $pageUrl
	 *  
	 */
	public function checkUrl(str $pageUrl)
	{
		if (filter_var($pageUrl, FILTER_VALIDATE_URL) === FALSE) {
 		   throw new Exception('Url provided is not valid');
		} else {
			$result = $this->_processRequest($pageUrl);
		}
	}
	
	/**
	 * Contact the PageSpeed API and request the feedback on a URL
	 * 
	 * @param string $url
	 * @return array
	 */
	protected function _processRequest(str $apiRequest)
	{
		$curlHandle = curl_init($apiRequest);	
		        curl_setopt($curlHandle, CURLOPT_VERBOSE, 1);
		        curl_setopt($curlHandle, CURLOPT_NOBODY, 0);
		        curl_setopt($curlHandle, CURLOPT_HEADER, 0);
		        curl_setopt($curlHandle, CURLOPT_FOLLOWLOCATION,1);
		        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
		
		$apiResponse = curl_exec($curlHandle);
		$responseInformation = curl_getinfo($curlHandle);
		curl_close($ch);
		
		if( intval( $responseInformation['http_code'] ) == 200 ) {
			return json_decode($apiResponse);	
		}	
	}	
}
