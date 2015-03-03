<?php
	session_start();
 	$token = md5(rand(1000,9999)); 
	$_SESSION['token'] = $token;

	// get database information
	require('config.php');

	// perform select query
	$stmt = $conn->prepare('SELECT * FROM people ORDER BY lname ASC');    
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/png" href="favicon.png">
		<title>My Phonebook</title>
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script src="js/respond.js"></script>
		<![endif]-->

		<!-- Inlining minified CSS for performance boost -->
		<style>
			<?php include('css/styles.css'); ?>
		</style>
		<script>var token = "<?php print $_SESSION['token']; ?>";</script>
	</head>
	<body>
		<main>
			<div class="content-width">
				<h1>My phonebook</h1>
				<ul id="list-people">
					<?php foreach ($result as $person): ?>
						<li data-id="<?php print $person['id']; ?>" class="clearfix">
							<?php print $person['lname']; ?>, <?php print $person['fname']; ?> - <?php print $person['phone']; ?> <span class="delete">Delete</span>
						</li>
					<?php endforeach; ?>
				</ul>
				<h2 class="mobile-only">Add new phone number</h2>
				<form method="POST" action="http://localhost:8888/phonebook/ajax.php" id="new-number-form">
					<input type="text" name="fname" placeholder="First Name" id="fname">
					<input type="text" name="lname" placeholder="Last Name" id="lname">
					<input type="tel" name="phone" placeholder="Telephone" id="phone">
					<input type="submit" value="Add">
				</form>
				<div id="errors"></div>
			</div>
		</main>	
		<footer>
			<div class="content-width">
				A project created by Stuart Dotson
			</div>
		</footer>
		<script type="text/javascript" src="js/build/production.min.js"></script>
	</body>
</html>