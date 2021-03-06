<?php 
	try {	    	
    	switch ($_SESSION["security-level"]){
    		case "0": // This code is insecure.
				$lEnableJavaScriptValidation = FALSE;
    		break;

    		case "1": // This code is insecure.
				$lEnableJavaScriptValidation = TRUE;
    		break;

	   		case "2":
	   		case "3":
	   		case "4":
    		case "5": // This code is fairly secure
    			$lEnableJavaScriptValidation = TRUE;
    		break;
    	}// end switch
    	
	} catch(Exception $e){
		echo $CustomErrorHandler->FormatError($e, "Error setting up configuration.");
	}// end try	
?>

<script type="text/javascript">
<!--
	<?php 
		if ($_SESSION["loggedin"]=="True") {
			echo "var l_loggedIn = true;" . PHP_EOL;
		}else {
			echo "var l_loggedIn = false;" . PHP_EOL;
		}// end if
	
		if (isset($lAuthenticationAttemptResult)){
			echo "var lAuthenticationAttemptResultFlag = {$lAuthenticationAttemptResult};" . PHP_EOL;
		}else{
			echo "var lAuthenticationAttemptResultFlag = -1;".PHP_EOL;
		}// end if
 
		if($lEnableJavaScriptValidation){
			echo "var lValidateInput = \"TRUE\"" . PHP_EOL;
		}else{
			echo "var lValidateInput = \"FALSE\"" . PHP_EOL;
		}// end if		
	?>

	function onSubmitOfLoginForm(/*HTMLFormElement*/ theForm){
		try{
			var lUnsafeCharacters = /[`~!@#$%^&*()-_=+\[\]{}\\|;':",./<>?]/;

			if(lValidateInput == "TRUE"){
				if (theForm.username.value.length > 15 || 
					theForm.password.value.length > 15){
						alert('Username too long. We dont want to allow too many characters.\n\nSomeone might have enough room to enter a hack attempt.');
						return false;
				};// end if
				
				if (theForm.username.value.search(lUnsafeCharacters) > -1 || 
					theForm.password.value.search(lUnsafeCharacters) > -1){
						alert('Dangerous characters detected. We can\'t allow these. This all powerful blacklist will stop such attempts.\n\nMuch like padlocks, filtering cannot be defeated.\n\nBlacklisting is l33t like l33tspeak.');
						return false;
				};// end if
			};// end if(lValidateInput)
			
			return true;
		}catch(e){
			alert("Error: " + e.message);
		};// end catch
	};// end function onSubmitOfLoginForm(/*HTMLFormElement*/ theForm)
//-->
</script>

<!-- Bubble hints code -->
<?php 
	try{
   		$lReflectedXSSExecutionPointBallonTip = $BubbleHintHandler->getHint("ReflectedXSSExecutionPoint");
   		$lSQLInjectionPointBallonTip = $BubbleHintHandler->getHint("SQLInjectionPoint");
   		
	} catch (Exception $e) {
		echo $CustomErrorHandler->FormatError($e, "Error attempting to execute query to fetch bubble hints.");
	}// end try
?>

<script type="text/javascript">
	$(function() {
		$('[ReflectedXSSExecutionPoint]').attr("title", "<?php echo $lReflectedXSSExecutionPointBallonTip; ?>");
		$('[ReflectedXSSExecutionPoint]').balloon();
		$('[SQLInjectionPoint]').attr("title", "<?php echo $lSQLInjectionPointBallonTip; ?>");
		$('[SQLInjectionPoint]').balloon();		
	});
</script>

<div class="page-title">Login</div>

<?php include_once (__ROOT__.'/includes/back-button.inc');?>
<?php include_once (__ROOT__.'/includes/hints-level-1/level-1-hints-wrapper.inc'); ?>

<div id="id-log-in-form-div" style="display: none; text-align:center;">
	<form 	action="index.php?page=login.php"
			method="post" 
			enctype="application/x-www-form-urlencoded" 
			onsubmit="return onSubmitOfLoginForm(this);"
			id="idLoginForm">
		<table style="margin-left:auto; margin-right:auto;">
			<tr id="id-authentication-failed-tr" style="display: none;">
				<td id="id-authentication-failed-td" colspan="2" class="error-message"></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" class="form-header">Please sign-in</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td class="label">Name</td>
				<td><input SQLInjectionPoint="1" type="text" name="username" maxlength="20" size="20"></td>
			</tr>
			<tr>
				<td class="label">Password</td>
				<td><input SQLInjectionPoint="1" type="password" name="password" maxlength="20" size="20"></td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center;">
					<input name="login-php-submit-button" class="button" type="submit" value="Login" />
				</td>
			</tr>
			<tr><td></td></tr>
			<tr>
				<td colspan="2" style="text-align:center; font-style: italic;">
					Dont have an account? <a href="?page=register.php">Please register here</a>
				</td>
			</tr>
		</table>
	</form>
</div>

<div id="id-log-out-div" style="text-align: center; display: none;">
	<table align="center">
		<tr>
			<td ReflectedXSSExecutionPoint="1" colspan="2" class="hint-header">You are logged in as <?php echo $_SESSION['logged_in_user']; ?></td>
		</tr>
		<tr><td></td></tr>
		<tr><td></td></tr>
		<tr>
			<td colspan="2" style="text-align:center;">
				<input class="button" type="button" value="Logout" onclick="document.location='index.php?do=logout'" />
			</td>
		</tr>
	</table>	
</div>

<script type="text/javascript">
	var cUNSURE = -1;
   	var cACCOUNT_DOES_NOT_EXIST = 0;
   	var cPASSWORD_INCORRECT = 1;
   	var cNO_RESULTS_FOUND = 2;
   	var cAUTHENTICATION_SUCCESSFUL = 3;
   	var cAUTHENTICATION_EXCEPTION_OCCURED = 4;
   	var lMessage = "";
   	var lAuthenticationFailed = "FALSE";
   	
	switch(lAuthenticationAttemptResultFlag){
   		case cACCOUNT_DOES_NOT_EXIST: 
   	   		lMessage="Account does not exist"; lAuthenticationFailed = "TRUE";
   	   		break;
   		case cPASSWORD_INCORRECT: 
   	   		lMessage="Password incorrect"; lAuthenticationFailed = "TRUE"; 
   	   		break;
   		case cNO_RESULTS_FOUND: 
   	   		lMessage="No results found"; lAuthenticationFailed = "TRUE"; 
   	   		break;
   		case cAUTHENTICATION_EXCEPTION_OCCURED: 
   	   		lMessage="Exception occurred"; lAuthenticationFailed = "TRUE"; 
   		break;
   	};

	if(lAuthenticationFailed=="TRUE"){
		document.getElementById("id-authentication-failed-tr").style.display="";
		document.getElementById("id-authentication-failed-td").innerHTML=lMessage;
	}// end if AuthenticationAttemptResultFlag

	if (!l_loggedIn){
		document.getElementById("id-log-in-form-div").style.display="";
		document.getElementById("id-log-out-div").style.display="none";
	}else{
		document.getElementById("id-log-in-form-div").style.display="none";
		document.getElementById("id-log-out-div").style.display="";		
	}// end if l_loggedIn	
</script>

<?php
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/hints-level-2/sql-injection-tutorial.inc';
	}// end if
	if ($_SESSION["showhints"] == 2) {
		include_once './includes/insufficient-transport-layer-protection.inc';
	}// end if	
?>

<script type="text/javascript">
	try{
		document.getElementById("idLoginForm").username.focus();
	}catch(e){
		alert('Error trying to set focus on field idLoginForm: ' + e.message);
	}// end try
</script>
