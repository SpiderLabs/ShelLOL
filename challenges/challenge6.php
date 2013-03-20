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
	<title>ShellOL - Challenge 6 - Best laid plans</title>
</head>
<body>
	<center><h1>ShellOL - Challenge 6 - Best laid plans</h1></center><br>

	<hr width="40%">
	<hr width="60%">
	<hr width="40%">
	
You must perform a shell injection attack with a very restrictive blacklist.<br>
<br>
Your objective is to read the contents of /etc/passwd.

<pre>
PARAMETERS:
Injection Location - Command argument wrapped in quotes
Method - GET
Sanitization - Reject(high), no semicolons, pipes, ampersands, dollar signs, parens, backticks, or newlines
Output - Command results, errors shown, command executed is shown
</pre>

<form action="../shell.php" method="get" name="challenge_form">
<input type="hidden" name="custom&#95;inject" value="find&#32;&#46;&#32;&#45;name&#32;&#42;INJECT&#42;" />
      <input type="hidden" name="blacklist&#95;level" value="reject&#95;high" />
      <input type="hidden" name="blacklist&#95;keywords" value="&#59;&#44;&amp;&#44;&#124;&#44;&#13;&#10;&#44;&#36;&#44;&#40;&#44;&#41;&#44;&#96;" />
      <input type="hidden" name="show&#95;results" value="on" />
      <input type="hidden" name="show&#95;command" value="on" />
      <input type="hidden" name="show&#95;errors" value="on" />
	Injection String: <input type="text" name="inject_string"/><br>
	<input type="submit" name="submit" value="Inject!"/>
</form>
<br>
</body>
</html>
