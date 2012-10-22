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
<title>ShellOL - Shell Command Injection</title>
</head>
<body>
<center><h1>ShellOL - Shell Command Injection</h1></center><br>
<center>| <a href="shell.php">Shell Command Injection</a> || <a  href="challenges.htm">Challenges</a> | </center>
<hr width="40%">
<hr width="60%">
<hr width="40%">
<br>
<form name='inject_form'>
	<table><tr><td>Injection String:</td><td><textarea name='inject_string'><?php echo (isset($_REQUEST['inject_string']) ? htmlentities($_REQUEST['inject_string']) : '' ); ?></textarea></td></tr>
	<tr><td>Injection Location:</td><td>
		<select name="location">
			<option value="argument">Argument</option>
			<option value="argument_quotes" <?php if(isset($_REQUEST["location"]) and $_REQUEST["location"]=="argument_quotes") echo "selected"; ?>>Argument (wrapped in quotes)</option>
			<option value="command" <?php if(isset($_REQUEST["location"]) and $_REQUEST["location"]=="command") echo "selected"; ?>>Command</option>
			<option value="filename" <?php if(isset($_REQUEST["location"]) and $_REQUEST["location"]=="filename") echo "selected"; ?>>File name for output redirection</option>
		</select></td></tr>
		<tr><td>Custom command (*INJECT* specifies injection point):</td><td><textarea name="custom_inject"><?php echo (isset($_REQUEST['custom_inject']) ? htmlentities($_REQUEST['custom_inject']) : '' ); ?></textarea></td></tr>
	<tr><td><b>Input Sanitization:</b></td></tr>
	<tr><td>Blacklist Level:</td><td><select name="blacklist_level">
		<option value="none">No blacklisting</option>
		<option value="reject_low" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="reject_low") echo "selected"; ?>>Reject (Low)</option>
		<option value="reject_high" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="reject_high") echo "selected"; ?>>Reject (High)</option>
		<option value="escape" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="escape") echo "selected"; ?>>Escape</option>
		<option value="low" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="low") echo "selected"; ?>>Remove (Low)</option>
		<option value="medium" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="medium") echo "selected"; ?>>Remove (Medium)</option>
		<option value="high" <?php if(isset($_REQUEST["blacklist_level"]) and $_REQUEST["blacklist_level"]=="high") echo "selected"; ?>>Remove (High)</option>
	</select></td></tr>
	<tr><td>Blacklist Keywords (comma separated):</td><td><textarea name="blacklist_keywords"><?php if(isset($_REQUEST["blacklist_keywords"])) echo $_REQUEST["blacklist_keywords"]; ?></textarea></td></tr>
	<tr><td><b>Output Level:</b></td></tr>
		<tr><td>Show results?</td><td><input type='checkbox' name='show_results' <?php echo (isset($_REQUEST['show_results']) ? 'checked' : ''); ?>></td></tr>
		<tr><td>Show command?</td><td><input type='checkbox' name='show_command' <?php echo (isset($_REQUEST['show_command']) ? 'checked' : ''); ?>></td></tr>
		<tr><td>Show errors?</td><td><input type='checkbox' name='show_errors' <?php echo (isset($_REQUEST['show_errors']) ? 'checked' : ''); ?>></td></tr>
	</table>
	<input type="submit" name="submit" value="Inject!">
</form>

<?php
if(isset($_REQUEST['submit'])){
	if(stristr(PHP_OS, 'WIN') && PHP_OS != 'Darwin'){
		$base_utility = 'dir';
		$base_filename = 'C:\\derp';
	} else {
		$base_utility = 'ls';
		$base_filename = '/tmp/derp';
	}
	$base_command = $base_utility . ' ' . $base_filename;
	
	//sanitization section
	if(isset($_REQUEST['blacklist_keywords'])){
		$blacklist = explode(',' , $_REQUEST['blacklist_keywords']);
	}
	
	if(isset($_REQUEST['blacklist_level'])){
		switch($_REQUEST['blacklist_level']){
			//We process blacklists differently at each level. At the lowest, each keyword is removed case-sensitively.
			//At medium blacklisting, checks are done case-insensitively.
			//At the highest level, checks are done case-insensitively and repeatedly.
			
			case 'reject_low':
				foreach($blacklist as $keyword){
					if(strstr($_REQUEST['inject_string'], $keyword)!='') {
						die("\nBlacklist was triggered!");
					}
				}
				break;
			case 'reject_high':
				foreach($blacklist as $keyword){
					if(strstr(strtolower($_REQUEST['inject_string']), strtolower($keyword))!='') {
						die("\nBlacklist was triggered!");
					}
				}
				break;
			case 'escape':
				foreach($blacklist as $keyword){
					$_REQUEST['inject_string'] = str_replace($keyword, htmlentities($keyword), $_REQUEST['inject_string']);
				}
				break;
			case 'low':
				foreach($blacklist as $keyword){
					$_REQUEST['inject_string'] = str_replace($keyword, '', $_REQUEST['inject_string']);
				}
				break;
			case 'medium':
				foreach($blacklist as $keyword){
					$_REQUEST['inject_string'] = str_ireplace($keyword, '', $_REQUEST['inject_string']);
				}
				break;
			case 'high':
				do{
					$keyword_found = 0;
					foreach($blacklist as $keyword){
						$_REQUEST['inject_string'] = str_ireplace($keyword, '', $_REQUEST['inject_string'], $count);
						$keyword_found += $count;
					}	
				}while ($keyword_found);
				break;
			
		}
	}
	
	if (isset($_REQUEST['custom_inject']) and $_REQUEST['custom_inject']!=''){
		$command = str_replace('*INJECT*', $_REQUEST['inject_string'], $_REQUEST['custom_inject']);
		$display_command = str_replace('*INJECT*', '<u>' . $_REQUEST['inject_string'] . '</u>', $_REQUEST['custom_inject']);
	}else{
		switch ($_REQUEST['location']){
			case 'argument':
				$command = str_replace($base_filename, $_REQUEST['inject_string'], $base_command);
				$display_command = str_replace($base_filename, '<u>' . $_REQUEST['inject_string'] . '</u>', $base_command);
				break;
			case 'argument_quotes':
				$command = str_replace($base_filename, '"'.$_REQUEST['inject_string'].'"', $base_command);
				$display_command = str_replace($base_filename, '"<u>' . $_REQUEST['inject_string'] . '</u>"', $base_command);
				break;
			case 'command':
				$command = str_replace($base_utility, $_REQUEST['inject_string'], $base_command);
				$display_command = str_replace($base_utility, '<u>' . $_REQUEST['inject_string'] . '</u>', $base_command);
				break;
			case 'filename':
				$command = $base_command . ' > ' . $_REQUEST['inject_string'];
				$display_command = $base_command . ' &gt; <u>' . $_REQUEST['inject_string'] . '</u>';
				break;
		}
	}
	
	exec($command, $output, $failure_status);
	$output = implode($output, "\n");
	
	if(isset($_REQUEST['show_results'])) {
		echo '<pre>'.$output.'</pre><br>';
	}
	if(isset($_REQUEST['show_command'])){
		echo 'Command executed: ' . $display_command . '<br>';
	}
	if(isset($_REQUEST['show_errors']) && $failure_status){
		echo 'Command failed to execute.<br>';
	}
	
}

?>
</body>
</html>
