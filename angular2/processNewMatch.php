<?php
	$errors = array();  // array to hold validation errors
	$data = array();        // array to pass back data
	
	// validate the variables ========
	if (empty($_POST['Dato'])) {
		$errors['date'] = 'Date is required.';
		array_push($errors2, 'Date is required.');
	}
	if (empty($_POST['player1']))
	$errors['name1'] = 'Player 1 is required.';
	array_push($errors2, 'Player 1 is required.');
	if (empty($_POST['player2']))
	$errors['name2'] = 'Player 2 is required.';
	array_push($errors2, 'Player 2 is required.');
	if (empty($_POST['point1']) && !is_numeric($_POST['point1']))
	$errors['point1'] = 'Point 1 is required.';
	if (empty($_POST['point2']) && !is_numeric($_POST['point2']))
	$errors['point2'] = 'Point 2 is required.';
	
	
	// return a response ==============
	
	// response if there are errors
	if ( ! empty($errors)) {
		// if there are items in our errors array, return those errors
		$data['success'] = false;
		$data['errors']  = $errors;
		//$data['hjp'] = $_POST;
		} else {
		$link = mysqli_connect("localhost", "root", "maria1772", $_POST["database"]);
		//if (!$link) {
		if ($result1 = mysqli_query($link, "SELECT * FROM Spillere WHERE Licens = '". $_POST["player1"] . "'")) {
			if (mysqli_num_rows($result1) == 1) { 
				if ($result2 = mysqli_query($link, "SELECT * FROM Spillere WHERE Licens = '". $_POST["player2"] . "'")) {
					if (mysqli_num_rows($result2) == 1) { 
						$row1 = mysqli_fetch_assoc($result1);
						$row2 = mysqli_fetch_assoc($result2);
						$data['connected'] = 'Yes';
						$date = new DateTime($_POST["Dato"]);
						$data['Request'] = "INSERT INTO Kampe (Dato, Spiller1, Spiller2, Point1, Point2, Resultat) VALUES (" . $date->format('Ymd') . ", " . $row1["Id"] . ", " . 
						$row2["Id"] . ", " . $_POST["point1"] . ", " . $_POST["point2"] . ", '" . $_POST["resultat"] . "')";
						//Check om kamp allerede eksisterer
						if ($resultMatchExist = mysqli_query($link, "SELECT * FROM Kampe WHERE Spiller1 = ". $row1["Id"] . " AND Spiller2 = " . $row2["Id"] . " AND Dato = " .  $date->format('Ymd'))) {
							if (mysqli_num_rows($resultMatchExist) == 0) {
								$result = mysqli_query($link, "INSERT INTO Kampe (Dato, Spiller1, Spiller2, Point1, Point2, Resultat, Comment) VALUES (" . $date->format('Ymd') . ", " . $row1["Id"] . ", " . $row2["Id"] . ", " . $_POST["point1"] . ", " . $_POST["point2"] . ", '" . $_POST["resultat"] . "', '" . $_POST["comment"] . "')");
								} else {
								//$errors('hjp') = 'hapag';
								$errors['matchexist'] = 'Match already exists 1.';
							}
							} else {
							//$errors('matchexist') = 'Match already exists.';
							$errors['matchexist'] = 'Match already exists 2.';
						}
						$data['result'] = $result;
						} else {
						$errors['player2notfound'] = 'Player 2a not found';
					}
					} else {
					$errors['player2notfound'] = 'Player 2b not found';
				}
				} else {
				$errors['player1notfound'] = 'Player 1a not found';
			}
			} else {
			$errors['player1notfound'] = 'Player 1b not found';
		}
		mysqli_close($link);
		//}
		
		// if there are no errors, return a message
		//$data['dato'] = $date->format('Ymd');
	}
	if ( ! empty($errors)) {
		$data['success'] = false;
		$data['message'] = 'Failed!';
		$data['errors'] = $errors;
		$data['errors_count'] = count($errors);
		$data['errors3'] = implode(" ",$errors);
		$data['errors2'] = implode(" ",$errors2);
		$data['errors2_count'] = count($errors2);
		} else {
		$data['success'] = true;
		$data['message'] = 'Success!';
		
	}
	// return all our data to an AJAX call
	echo json_encode($data);
	// some code
?>		