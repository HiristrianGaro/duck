<?php 
    function FetchPosts($email, $cid) {
        
        $select = "SELECT * FROM post NATURAL JOIN foto WHERE AutorePostEmail = ? ORDER BY DataDiPubblicazione DESC";
    
        $stmt = $cid->prepare($select);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $results = $stmt->get_result();
    
        if ($results) {
            $Posts = $results->fetch_all(MYSQLI_ASSOC);
            
        } else {
            echo "MySql Query failed: " . mysqli_error($cid);
            exit();
        }
        return $Posts;
    }


?>
