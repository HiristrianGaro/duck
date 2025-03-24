<?php
include "../config.php";
include "connection.php";

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

function getQuery($cid, $sql, $params, $types){
	try {
		if (checkDB($cid)["status"] != "ko") {
			$stmt = $cid->prepare($sql);
			if (!$stmt) {
				throw new Exception("Failed to prepare SQL statement: " . $cid->error);
			}
			if ($stmt) {
				$stmt->bind_param($types, ...$params);
				if (!$stmt->execute()) {
					throw new Exception("Failed to execute SQL statement: " . $stmt->error);
				}
				$result = $stmt->get_result();
				
				if($result){
					$data = array();
					while ($row = $result->fetch_assoc()) {
						$data[] = $row;
					}
					return array(true, $data);
					$stmt->close();
				}

				$stmt->close();
			} else {
				error_log("Errore nella preparazione dello statement: " . $cid->error);
			}
		} else {
			error_log("Errore nella connessione al db");
		}
	} catch (Exception $e) {
        // Log and return error
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}

function postQuery($cid, $sql, $params, $types){
	try {
		if (checkDB($cid)["status"] != "ko") {
			$stmt = $cid->prepare($sql);
			if (!$stmt) {
				throw new Exception("Failed to prepare SQL statement: " . $cid->error);
			}
			if ($stmt) {
				$stmt->bind_param($types, ...$params);
				if (!$stmt->execute()) {
					throw new Exception("Failed to execute SQL statement: " . $stmt->error);
				}
				$result = $stmt->get_result();
				return $result;
				$stmt->close();
			} else {
				error_log("Errore nella preparazione dello statement: " . $cid->error);
			}
		} else {
			error_log("Errore nella connessione al db");
		}
	} catch (Exception $e) {
        // Log and return error
        error_log("Error: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }

}

function toJson($data){
	header('Content-Type: application/json');
	return json_encode($data);
}