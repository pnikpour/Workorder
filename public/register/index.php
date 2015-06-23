<?php
//**************************************************************************
// This file is part of the BlueberryPHP project.
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program.  If not, see <http://www.gnu.org/licenses/>.
// 
// Programmer: Parsa Nikpour 
// Date: 16 June 2014
// Description:  
// 
//**************************************************************************/
?>

<?php
	ini_set('display_startup_errors',1);
	ini_set('display_errors',1);
	error_reporting(-1);

	include('../assets/php/lib.php');
	
?>

<html>
<head>
	<script src="//code.jquery.com/jquery-1.10.2.js"></script>
	<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<script src='../assets/js/effect.js' type='text/javascript'></script>
	<link rel='stylesheet' href='../assets/css/styles.css' type='text/css' />
</head>
<body>

<header><img class='logo' src='../assets/images/logo.png' alt='BlueberryPHP Logo'></header>
<h1>User Self-Register Form</h1>

<form action='' name='addUserForm' id='addUserForm' method='post'>

	<?php include '../assets/php/createNav.php'; ?>

	<table border=1>
		<tr>
			<th>Username</th>
			<th>Password</th>
			<th>Verify Password</th>
		</tr>
		<tr>
			<td>
			<input type='text' class='logon' name='newName' />
			</td>
			<td>
			<input type='password' class='logon' name='password1' />
			</td>
			<td>
			<input type='password' class='logon' name='password2' />
			</td>
		</tr>
		<tr>
			<td colspan='3'>
			<input type='submit' class='button' name='submit' value='Submit' />
			</td>
		</tr>
	</table>
</form>

<?php

	// Create the user
	if (isset($_POST['saveNew']) || isset($_POST['submit'])) {
		$newName = $_POST['newName'];
		$password1 = $_POST['password1'];
		$password2 = $_POST['password2'];
		
		if (userExists($newName)) { // If username exists, prompt error
			printError('User already exists; please specify a different username');
		} else // If username entered is empty, prompt error
		if (strlen(trim($newName)) == 0) {
			printError('Please specify a username');
		} 

		// Perform password add; check requirements; only checks password length
		if (meetsPasswordLength($password1, true) && passwordsMatch($password1, $password2, true)) {
			$hash = password_hash($password1, PASSWORD_DEFAULT);
			$query = "INSERT INTO users (username, hash, groups) values (:newName, :hash, 'User')";
			$result = getDB()->prepare($query);
			$result->bindParam(':newName', $newName);
			$result->bindParam(':hash', $hash);
			$result->execute();
			printSuccess('User ' . $newName . ' added to database');
		}
	}

	// Navigation link action controller
	navPOST();
?>


</body>
</html>


