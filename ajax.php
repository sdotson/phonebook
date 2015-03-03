<?php

session_start();

if($_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $_POST['token'] == $_SESSION['token']) {

	if ($_POST['verb'] == 'create') {

		require('config.php');

		$fname = $_POST['fname'];
		$lname = $_POST['lname'];
		$phone = $_POST['phone'];


		$stmt = $conn->prepare('INSERT INTO people (fname, lname, phone) VALUES (:fname, :lname, :phone)');
	      $stmt->execute(array(
	      	'fname' => $fname,
	      	'lname' => $lname,
	      	'phone' => $phone
	      	));

		$response = array(
				'status' => 'success',	
				'insertedItem' => array(
						'id' => $conn->lastInsertID(),
						'fname' => $fname,
						'lname' => $lname,
						'phone' => $phone
					)
			);


	}

	if ($_POST['verb'] == 'delete') {

		require('config.php');

		$id = $_POST['person'];

		$stmt = $conn->prepare('DELETE FROM people WHERE id = :id');    
	    $stmt->bindParam(':id', $id);
	    $stmt->execute();
		

	}

	if ($_POST['verb'] == 'query') {

		require('config.php');

		$stmt = $conn->prepare('SELECT * FROM people ORDER BY lname ASC');    
	    $stmt->execute();
	    $response = $stmt->fetchAll(PDO::FETCH_ASSOC);
	}

	echo json_encode($response);
}

?>