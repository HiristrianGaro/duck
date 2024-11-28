<?php
    $Autore = $Post['AutorePostEmail'];
    $dataPubblicazione = $Post['DataDiPubblicazione'];
    $orario = $Post['OraDiPubblicazione'];
    $dataOrarioMessaggio = $dataPubblicazione . ' ' . $orario;
    $timestamp = strtotime($dataOrarioMessaggio);
    $displayData = date("d F Y, H:i", $timestamp);
    $postId = $emailAutore.$dataOrarioMessaggio;
    $postId = preg_replace('/[^a-zA-Z0-9-_]/', '', $postId);
    require_once ("../Backend/getFoto.php");
    $foto = getFoto($emailAutore, $dataPubblicazione, $orario, $conn);
    $luogoFoto ='';
    if (!empty($foto['Città'])){
        $luogoFoto = $foto['Stato'].','.$foto['Regione'].','.$foto['Città'];
    }

?>

<div class="container-fluid my-3 pb-1 border-bottom border-2 postContainer" data-postId="<?php echo $postId;?>" data-EmailAutore= "<?php echo $emailAutore;?>" data-pubblicazione="<?php echo $dataPubblicazione;?>" data-orario="<?php echo $orario;?>">
    <div class="row justify-content-start ">
        <div class="col border-bottom border-1 border-top-0 border-dark rounded-3 ps-3 shadow">
            <?php 
            if ($emailAutore == $emailUtente){
                $link = '<a href= "userPage.php" class="fs-4 text-primary text-decoration-none pt-2">'.$NomeAutore.'</a>';
                echo $link;
            }else{
                $htmlLinkPost = '<form action="../common/impostaAmicoSessione.php" method="post">';
                $htmlLinkPost .= '<button type="submit" class="btn fs-4 ps-0 text-decoration-none btn-link" name="emailAmico" value="'.htmlspecialchars($emailAutore).'">'.$NomeAutore.'</button>';
                $htmlLinkPost.='</form>';
                echo $htmlLinkPost;
            }
            echo '<p class="text-secondary">'.$displayData.'</p>';
            ?>
            
        </div>
    </div>
    <div class="row justify-content-start border-bottom border-primary pb-3 pt-2">
        <?php if(empty($foto)): ?>
            <div class="col-12 pt-2">
                <?php echo $Post['Contenuto']; ?>
            </div>
        <?php else : ?>
            <div class="col-12 pt-2 text-center">
                <?php echo $foto['Descrizione']; ?>
                <div class="m-2 p-2">
                    <img src="<?php echo $foto['Path']; ?>" alt="Foto messaggio" class="img-fluid"> 
                    <br>
                    <small class="text-secondary"> <?php echo $luogoFoto ?></small>
                </div>
            </div>
        <?php endif?>
    </div>
    <?php
        if ($idPage == "Home"){
            include("../common/buttonsMexHome.php");
        }else{
            include("../common/buttonsMexVis.php");
        }     
    ?>  
</div>

<script src="../js/inviaValutazione.js"></script>