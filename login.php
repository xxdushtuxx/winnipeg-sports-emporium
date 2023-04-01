<?php




?>


<!DOCTYPE html>
<html>
<head>
	<title>Login Form</title>
	<style>
		form {
		  max-width: 400px;
		  margin: 0 auto;
		  padding: 20px;
		  background-color: #f1f1f1;
		  border-radius: 5px;
		  box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
		}

		.input-field {
		  width: 100%;
		  padding: 12px 20px;
		  margin: 8px 0;
		  display: inline-block;
		  border: 1px solid #ccc;
		  border-radius: 4px;
		  box-sizing: border-box;
		}

		.btn {
		  background-color: #4CAF50;
		  color: white;
		  padding: 14px 20px;
		  margin: 8px 0;
		  border: none;
		  border-radius: 4px;
		  cursor: pointer;
		}

		.btn:hover {
		  background-color: #45a049;
		}

		.cancel-btn {
		  width: auto;
		  padding: 10px 18px;
		  background-color: #f44336;
		}

		.img-container {
		  text-align: center;
		  margin: 24px 0 12px 0;
		}

		img.avatar {
		  width: 40%;
		  border-radius: 50%;
		}

		.container {
		  padding: 16px;
		}

		.signup-link {
		  text-align: center;
		}

		.signup-link a {
		  color: #4CAF50;
		  text-decoration: none;
		}

		.signup-link a:hover {
		  text-decoration: underline;
		}

		.login-link {
		  text-align: center;
		  margin-top: 20px;
		}

		.login-link a {
		  color: #4CAF50;
		  text-decoration: none;
		}

		.login-link a:hover {
		  text-decoration: underline;
		}

		.text-center {
			text-align: center;
		}

		.margin-top-20 {
			margin-top: 20px;
		}
	</style>
</head>
<body>
	<h1 class="text-center">Login Form</h1>
	<form method="POST" action="login.php">
		<label for="username">Username:</label>
		<input class="input-field" type="text" name="username" required><br><br>
		<label for="password">Password:</label>
		<input class="input-field" type="password" name="password" required><br><br>
		<input class="btn" type="submit" name="submit" value="Login">
	</form>
	<p class="text-center margin-top-20">Don't have an account? <a href="signup.php">Sign up</a> here!</p>
	<p class="text-center margin-top-20">Already have an account? <a href="login.php">Log in</a> here!</p>
</body>
</html>
