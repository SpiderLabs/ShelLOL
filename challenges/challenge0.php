<?php
/*
ShellOL - A configurable Shell Command Injection testbed
Daniel "unicornFurnace" Crowley
Copyright (C) 2012 Trustwave Holdings, Inc.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program. If not, see <http://www.gnu.org/licenses/>.
*/
?>
<html>
<head>
	<title>ShellOL - Challenge 0 - ; echo Hello world</title>
</head>
<body>
	<center><h1>ShellOL - Challenge 0 - ; echo Hello world</h1></center><br>

	<hr width="40%">
	<hr width="60%">
	<hr width="40%">
	
You must perform the simplest of shell command injection attacks.<br>
<br>
Your objective is to read the contents of either /etc/passwd or C:\boot.ini, depending on your OS.

<pre>
PARAMETERS:
Injection Location - Command argument
Method - GET
Sanitization - None
Output - output shown, error status disclosed, command shown
</pre>

<form action="../shell.php" method="get" name="challenge_form">
	<input type="hidden" name="blacklist_level" value="none"/>
	<input type="hidden" name="show_results" value="1"/>
	<input type="hidden" name="show_errors" value="1"/>
	<input type="hidden" name="show_command" value="1"/>
	<input type="hidden" name="location" value="argument"/>
	Injection String: <input type="text" name="inject_string"/><br>
	<input type="submit" name="submit" value="Inject!"/>
</form>
<br>
</body>
</html>
