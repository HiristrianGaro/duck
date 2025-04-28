<?php
  session_start();
  require_once "config.php";
  include "common/connection.php";
  include "common/funzioni.php";
  include "errorLogging.php";
?>

<?php
//error_log($_SERVER['REQUEST_METHOD']);    // Il metodo della richiesta (GET, POST, PUT, ecc.)
//error_log($_SERVER['QUERY_STRING']);      // La stringa di query nell'URL dopo il ?
//error_log($_SERVER['HTTP_REFERER']);      // L'URL della pagina da cui proviene l'utente
//error_log($_SERVER['HTTP_USER_AGENT']);   // Informazioni sul browser dell'utente
//error_log($_SERVER['PHP_SELF']);          // Il percorso dello script corrente
//error_log($_SERVER['DOCUMENT_ROOT']);     // La directory radice del server web
//error_log($_SERVER['SCRIPT_FILENAME']);   // Il percorso assoluto dello script in esecuzione
//error_log($_SERVER['REQUEST_URI']);       // L'URI richiesto (es. /pagina.php?id=1)
//error_log($_SERVER['SERVER_NAME']);       // Il nome del server
//error_log($_SERVER['SERVER_ADDR']);       // L'indirizzo IP del server
//error_log($_SERVER['SERVER_PORT']);       // La porta su cui il server è in ascolto
//error_log($_SERVER['SERVER_SOFTWARE']);   // Il software del server (es. Apache/2.4.41)
//error_log(ROOT_DIR);
//error_log(SITE_DOMAIN);
//error_log(SITE_HOME);
//error_log(SITE_DIR);
//error_log(CONTENT_DIR);
//error_log(POST_DIR);
//error_log(LOCAL_POST_DIR);
?>

<!DOCTYPE html>
<html lang="en">
<?php include "frontend/header.html"; ?>

<body>
    <?php include "frontend/navbar.php"; ?>
    <?php include "frontend/items/searchCollapse.html"; ?>

    <div class="container-fluid container-z-index" id="main-page">
      <?php if(!isset($_SESSION['Status'])) {include 'frontend/items/landingPage.html';} else {include 'frontend/pond.php';}?>
        
    </div>
    
  <?php include "frontend/footer.php"; ?>
  <script src="js/pondFunctionality.js"></script>
</body>

</html>