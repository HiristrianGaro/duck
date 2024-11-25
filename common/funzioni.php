<?php


/* Funzioni relative alla gestione degli utenti */


function checkDB($cid){
	$check = array("msg" => "", "status" => "ok");

	if ($cid == null || $cid->connect_errno) {
		$check["status"] = "ko";
		$check["msg"] = $cid ? "Errore nella connessione al db: " . $cid->connect_error : "Errore nella connessione al db";
		return $check;
	}

	$check["msg"] = "Connessione al db effettuata con successo";
	return $check;
}


function isUser($cid, $Email, $Password){
	$check = array("msg" => "", "status" => "ok");

	if ($cid == null || $cid->connect_errno) {
		$check["status"] = "ko";
		$check["msg"] = $cid ? "Errore nella connessione al db: " . $cid->connect_error : "Errore nella connessione al db";
		return $check;
	}

	// Use prepared statements to prevent SQL injection
	$stmt = $cid->prepare("SELECT * FROM utente WHERE IndirizzoEmail = ? AND Password = ?");
	if ($stmt) {
		$stmt->bind_param("ss", $Email, $Password);
		$stmt->execute();
		$res = $stmt->get_result();

		if ($res->num_rows == 1) {
			$check["status"] = "ok";
			$check["msg"] = "Login effettuato con successo";
		} else {
			$check["status"] = "ko";
			$check["msg"] = "Email o password sbagliate";
		}

		$stmt->close();
	} else {
		$check["status"] = "ko";
		$check["msg"] = "Errore nella preparazione della query: " . $cid->error;
	}

	return $check;
}





function checkEmailExist($cid, $Email){
	$check = array("msg" => "", "status" => "ko");
	
	if (checkDB($cid)["status"] != "ko") {

	// Use prepared statements to prevent SQL injection
	$stmt = $cid->prepare("SELECT * FROM utente WHERE IndirizzoEmail = ?");
	if ($stmt) {
		$stmt->bind_param("s", $Email);
		$stmt->execute();
		$res = $stmt->get_result();

		echo $res->num_rows;

		if ($res->num_rows > 0) {
			$check["status"] = "ok";
			$check["msg"] = "Indirizzo già esistente";
		}

		$stmt->close();
	} else {
		$check["status"] = "ko";
		$check["msg"] = "Errore nella preparazione della query: " . $cid->error;
	}

	return $check;
	}
}

