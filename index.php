<?php
error_reporting( E_ALL );

include('language.php');
include("dbconnect.php");

if( isset( $_GET['logout'] ) && $_GET['logout'] == 'true' ){
				
	$_SESSION = array();
	session_unset();
				
}

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

		#index_link{
			position: absolute;
			background: url("img/dungeon.png") no-repeat;
			background-size: 410px 200px;
			width: 410px;
			height: 200px;
			margin-top: -100px;
			margin-left: -205px;
			top: 50%;
			left: 50%;
		}
	
		
	</style> 
</head>
<body>

<div id="index_link">
	<a href="login.php" style="position:absolute;width:100%;height:100%;">&nbsp;</a>
</div>	

</body>
</html>