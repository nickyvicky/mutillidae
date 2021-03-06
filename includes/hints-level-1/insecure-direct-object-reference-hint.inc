<table class="hint-table">
	<tr class="hint-header" 
		id="idIDORHintHeader"
		title="Click to open this section" 
		onclick="toggleBody(this, window.document.getElementById('idIDORHintBody'), window.document.getElementById('idIDORHintHeaderImage'));"
		onmouseover="this.style.backgroundColor='#cccccc';this.style.color='#ffffff';"
		onmouseout="this.style.backgroundColor='#FFFFFF';this.style.color='#000000';"
	>
		<td><img id="idIDORHintHeaderImage" align="left" style="padding-right: 5px;" alt="Expand Insecure Direct Object Reference Hints" src="./images/down_arrow_16_16.png" />Insecure Direct Object Reference Hints</td>
	</tr>
	<tr id="idIDORHintBody" style="display: none;">
		<td class="hint-body">
			<br/><br/>
			<span class="report-header">Overview</span>
			<br/><br/>
			Insecure Direct Object Reference may result when the application
			retrieves or accesses a resource using the resource itself as
			the reference to lookup or identify the resource. 
			<br/><br/>
			Examples may include passing the filename of a document to retrieve,
			relocating to a URL after allowing the browser to send the URL
			as a parameter value or including an include file based on the
			browser sending the include file path and name.
			<br/><br/>
			<span class="report-header">Discovery Methodology</span>
			<br/><br/>
			For each parameter passed that identified a resource, 
			determine if the value is the resource itself or if the application
			only accepts a token which is meaningless outside of that
			context.  
			<br/><br/>
			For example, if the site expects an include file name to be passed,
			this could be a direct object reference. Alternatively if the site
			expects an integer which the server later maps to the include file
			to display, the site is not likely to be vulnerable to this issue.
			<br/><br/>
			<span class="report-header">Exploitation</span>
			<br/><br/>
			Based on the type of resource, submit other values to the server
			in order to gain access or load content to which the user is not
			authorized. 
			<br/><br/>
			<span class="report-header">Example: Local and Remote File Inclusion</span>
			<br/><br/>
			Note the page parameter in NOWASP URLs. This parameter is the include
			file to load within the main frame. Since this parameter loads any
			file passed, attempt to pass a local file or remote file URI.
			<br/><br/>
			<br/><br/>
			<span class="report-header">Example: Accessing system files</span>
			<br/><br/>
			Step 1: Recon: Obtain list of files<br/>
			Possible Technique: Use command injection<br/>
			Page: dns-lookup.php<br/>
			Value (Windows): &amp;&amp; dir /w<br/>
			Value (Linux): &amp;&amp; pwd &amp;&amp; ls -l<br/>
			<br/><br/>		
			Possible Technique: Use robots.txt<br/>
			Possible Technique: Use directory browsing<br/>
			Possible Technique: Use directory brute-forcing<br/>
			Possible Technique: Guess<br/>
			<br/><br/>
			Step 2: Gaining Access: Retrive system files<br/>
			<br/><br/>
			Page: source-viewer.php<br/>
			Technique: Use an interception proxy like Burp to change the value of the phpfile parameter. Use parent directory (..) to bypass jails.<br/>
			Tool: Tamper Data, Paros, WebScarab, Burp<br/>
			Field: phpfile<br/>
			Value: &lt;system file path&gt;, ../../../boot.ini, ..\..\..\WINDOW\System32\drivers\etc\hosts, ../../../etc/passwd
			<br/><br/>
		</td>
	</tr>
</table>