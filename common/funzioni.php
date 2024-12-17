<?php
include("../config.php");


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





function checkEmailExist($cid, $Email){
	error_log("Chiamata funzione checkEmailExist");
	if (checkDB($cid)["status"] != "ko") {
		error_log("Connessione al db effettuata con successo");
		$stmt = $cid->prepare("SELECT * FROM utente WHERE IndirizzoEmail = ?");
		if ($stmt) {
			$stmt->bind_param("s", $Email);
			$stmt->execute();
			$res = $stmt->get_result();
			error_log($res->num_rows);


			if ($res->num_rows > 0) {
				return true;
			} else {
				return false;
			}

			$stmt->close();
		}
	}
}


function checkUsernameExist($cid, $Username){
	error_log("Chiamata funzione checkUsernameExist");
	
	if (checkDB($cid)["status"] != "ko") {
		$stmt = $cid->prepare("SELECT * FROM utente WHERE Username = ?");
		if ($stmt) {
			$stmt->bind_param("s", $Username);
			$stmt->execute();
			$res = $stmt->get_result();

			if ($res->num_rows > 0) {
				return true;
			} else {
				return false;
			}

			$stmt->close();
		}
	}
}


