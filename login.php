<?php
error_reporting( E_ALL );

include('language.php');

include("dbconnect.php");

define( "SECURITY_CODE", "s2o4n6y8" );

?>
<!doctype html>
<html lang="en">
<head>
<!-- Force latest IE rendering engine or ChromeFrame if installed -->
<!--[if IE]>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<![endif]-->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dungeon</title>
  <link rel="icon" href="favicon.ico" type="image/x-icon"/>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon"/>

<link rel="stylesheet" href="thickbox.css">

  <!-- Bootstrap core CSS -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.1.1/cyborg/bootstrap.css" rel="stylesheet">
  
  <!-- Bootstrap styling for Typeahead 
    <link href="tokenizer/dist/css/tokenfield-typeahead.css" type="text/css" rel="stylesheet">
    <!--Tokenfield CSS
    <link href="tokenizer/dist/css/bootstrap-tokenfield.css" type="text/css" rel="stylesheet">
    <!-- Docs CSS
    <link href="tokenizer/docs-assets/css/pygments-manni.css" type="text/css" rel="stylesheet">
    <link href="tokenizer/docs-assets/css/docs.css" type="text/css" rel="stylesheet">
	-->
    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.2.0/respond.min.js"></script>
    <![endif]-->
  <link rel="stylesheet" href="http://code.jquery.com/ui/1.12.1/themes/ui-darkness/jquery-ui.css">
  <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap-glyphicons.css" rel="stylesheet">
  
    <script type="text/javascript" src="jsoneditor.js"></script>
    <script type="text/javascript" src="thickbox.js"></script>

<link rel="stylesheet" href="css/jquery.fileupload.css">
<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
<!-- CSS adjustments for browsers with JavaScript disabled -->
<noscript><link rel="stylesheet" href="css/jquery.fileupload-noscript.css"></noscript>
<noscript><link rel="stylesheet" href="css/jquery.fileupload-ui-noscript.css"></noscript>

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Righteous">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">

<link rel="stylesheet" href="slider.css">
<script style="text/javascrip" src="slider.js"></script>

	<style type="text/css">
		body{
			background: #000 url("img/EE8_3.jpg") no-repeat;
			background-size: 100%;
			font-size: 14px;
			color: white;
			background-attachment: fixed;
		}
	.modal-content{
		border: 1px solid white;
		-webkit-box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
		-moz-box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
		box-shadow: -4px 4px 10px 2px rgba(170,170,170,1);
	}
		
	</style> 
	
	<script type="text/javascript">
	$(document).ready( function(){
		$('.modal').modal({backdrop:'static',keyboard:false, show:true});
	});
	</script>
	
	
	</head>
<body class="modal-open">
	<!--
	<div id="logindiv" title="LogIn">
		-->
		<?
		if( isset( $_SESSION['user_id'] ) && isset( $_SESSION['username'] ) && !isset( $_GET['logout'] ) ){

		?>

<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">LogIn</h5>
      </div>
      <div class="modal-body">
        <a href="game.php">proceed</a>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
		
		<?

		}else if( isset( $_POST['register'] ) ){
			?>
			
<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Register</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
					<label for="username" class="text-white">username</label>
			    <input type="text" class="form-control" id="username" name="username" placeholder="username">
			  </div>
				<div class="form-group">
					<label for="password" class="text-white">password</label>
			    <input type="password" class="form-control" id="password" name="password" placeholder="password">
			  </div>
			  <div class="form-group">
			    <label for="email">e-mail address</label>
			    <input type="email" class="form-control" id="email" name="email" placeholder="e-mail">
			  </div>
			  <div class="form-group">
			    <label for="code">Code</label>
			    <input type="text" class="form-control" id="code" name="code" placeholder="s2....">
			  </div>
			  <button type="submit" class="btn btn-primary" name="register2" value="register">create account</button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

			<?
		}else if( isset( $_POST['register2'] ) ){

			if( $_POST['code'] != SECURITY_CODE ){ echo 'Access denied!'; die(); }

			try {

				$result = $db->query("SELECT * FROM users WHERE username='".$_POST['username']."'");
				
				if( $result->rowCount() > 0 ){

					echo "A user with this username already exists";
					//continue;

				}

			}catch(PDOException $ex) {
    				echo "An Error occured! "; //user friendly message
    				echo $ex->getMessage();
			}

			try {
				$salty = md5( $_POST['password'].SALT );

				$stmt = $db->prepare("INSERT INTO users(username, password, email) VALUES( :username, :password, :email ) ");
				$stmt->bindParam(':username', $_POST['username'], PDO::PARAM_STR);
				$stmt->bindParam(':password', $salty, PDO::PARAM_STR);
				$stmt->bindParam(':email', $_POST['email'], PDO::PARAM_STR);
				$stmt->execute();
				echo "User-id: ".$db->lastInsertId()." <br />";


			}catch(PDOException $ex) {
    				echo "An Error occured! "; //user friendly message
    				echo $ex->getMessage();
			}

			?>

<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Thank you for registering. You can now log in.</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
					<label for="username" class="text-white">username</label>
			    <input type="text" class="form-control" id="username" name="username" placeholder="username">
			  </div>
				<div class="form-group">
					<label for="password" class="text-white">password</label>
			    <input type="password" class="form-control" id="password" name="password" placeholder="password">
			  </div>
			  <button type="submit" class="btn btn-primary" name="submit" value="submit">submit</button>  <button type="submit" class="btn btn-primary" name="register" value="register">register</button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

			<?

		}else if( isset( $_POST['submit'] )){

			$username = $_POST['username'];
			$password = $_POST['password'];

			$result = $db->query("SELECT user_id,color FROM users WHERE username='".$_POST['username']."' && password='".md5($_POST['password'].SALT)."' ");
				
			if( $result->rowCount() == 0 ){
				
?>

<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">LogIn</h5>
      </div>
      <div class="modal-body text-white">
        LogIn fehlgeschlagen. Benutzer nicht gefunden
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?
				die();

			}

			$_SESSION['username'] = $username;

			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    				$_SESSION['user_id'] = (int) $row['user_id'];
    				$_SESSION['color'] = $row['color'];
			}
			
			$result = $db->query("SELECT camp_id, master FROM campaign WHERE status='active' ");
			$_SESSION['is_dm'] = false;
			while($row = $result->fetch(PDO::FETCH_ASSOC)) {
    				$_SESSION['camp_id'] = (int) $row['camp_id'];
    				$_SESSION['is_dm'] = $row['master'] == $_SESSION['user_id'] ? true : false;
			}

?>

<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">LogIn</h5>
      </div>
      <div class="modal-body text-white">
        LogIn successful. Thank you <br /><a href="game.php">proceed</a>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<?

		}else{
			
			if( isset( $_GET['logout'] ) && $_GET['logout'] == 'true' ){
				
				$_SESSION = array();
				session_unset();
				
			}
			
			?>
			
<div class="modal show">
  <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">LogIn</h5>
      </div>
      <div class="modal-body">
        <form action="" method="post">
        	<div class="form-group">
					<label for="username" class="text-white">Username</label>
			    <input type="text" class="form-control" id="username" name="username" placeholder="Username">
			  </div>
				<div class="form-group">
					<label for="password" class="text-white">Password</label>
			    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
			  </div>
			  <button type="submit" class="btn btn-primary" name="submit" value="submit">Submit</button>  <button type="submit" class="btn btn-primary" name="register" value="register">Register</button>
		</form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>
			<?
		}
		?>


</body>
</html>