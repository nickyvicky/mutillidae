<?php
	// Pull in the NuSOAP code
	require_once('./lib/nusoap.php');

	// Create the server instance
	$lSOAPWebService = new soap_server();
	
	// Initialize WSDL support
	$lSOAPWebService->configureWSDL('commandinjwsdl', 'urn:commandinjwsdl');
	
	// Register the method to expose
	$lSOAPWebService->register(
		'lookupDNS',							// method name
	    array('targetHost' => 'xsd:string'),	// input parameters
	    array('Answer' => 'xsd:string'),    	// output parameters
	    'urn:commandinjwsdl',               	// namespace
	    'urn:commandinjwsdl#commandinj',    	// soapaction
	    'rpc',                              	// style
	    'encoded',                          	// use
	    'Returns the results of the DNS lookup' // documentation
	);

	// Define the method as a PHP function
	function lookupDNS($pTargetHost) {

		/* We use the session on this page */
		if (!isset($_SESSION["security-level"])){
			session_start();	
		}// end if
	
	    // ----------------------------------------
		// initialize security level to "insecure" 
		// ----------------------------------------
	    if (!isset($_SESSION['security-level'])){
	    	$_SESSION['security-level'] = '0';
	    }// end if

		/* ------------------------------------------
		 * Constants used in application
		 * ------------------------------------------ */
		require_once('../../includes/constants.php');
		require_once('../../includes/minimum-class-definitions.php');
		
		try {	    	
	    	switch ($_SESSION["security-level"]){
	    		case "0": // This code is insecure. No input validation is performed.
	    		case "1": // This code is insecure. No input validation is performed.
					$lProtectAgainstCommandInjection=FALSE;
					$lProtectAgainstXSS = FALSE;
	    		break;
	
		   		case "2":
		   		case "3":
		   		case "4":
	    		case "5": // This code is fairly secure
	   				$lProtectAgainstMethodTampering = TRUE;
	   				$lProtectAgainstXSS = TRUE; 			
	    		break;
	    	}// end switch
		    	
	    	if ($lProtectAgainstCommandInjection) {
				/* Protect against command injection. 
				 * We validate that an IP is 4 octets, IPV6 fits the pattern, and that domain name is IANA format */
    			$lTargetHostValidated = preg_match(IPV4_REGEX_PATTERN, $pTargetHost) || preg_match(DOMAIN_NAME_REGEX_PATTERN, $pTargetHost) || preg_match(IPV6_REGEX_PATTERN, $pTargetHost);
	    	}else{
    			$lTargetHostValidated=TRUE; 			// do not perform validation
	    	}// end if
		    	
	    	if ($lProtectAgainstXSS) {
    			/* Protect against XSS by output encoding */
    			$lTargetHostText = $Encoder->encodeForHTML($pTargetHost);
	    	}else{
				//allow XSS by not encoding output
				$lTargetHostText = $pTargetHost;	    		
	    	}//end if
		    	
		}catch(Exception $e){
			echo $CustomErrorHandler->FormatError($e, "Error setting up configuration on page dns-lookup.php");
		}// end try	
		
	    try{
	    	$lResults = "";
	    	if ($lTargetHostValidated){
	    		$lResults .= '<div class="report-header">Results for '.$lTargetHostText.'</div>';
    			$lResults .=  '<pre class="report-header" style="text-align:left;">'.shell_exec("nslookup " . $pTargetHost).'</pre>';
				$LogHandler->writeToLog("Executed operating system command: nslookup " . $lTargetHostText);
	    	}else{
	    		$lResults .= "Validation Error";
	    	}// end if ($lTargetHostValidated){
			
	    	return $lResults;
	    	
    	}catch(Exception $e){
			echo $CustomErrorHandler->FormatError($e, "Input: " . $pTargetHost);
    	}// end try
		
	}//end function

	// Use the request to (try to) invoke the service
	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$lSOAPWebService->service($HTTP_RAW_POST_DATA);
?>