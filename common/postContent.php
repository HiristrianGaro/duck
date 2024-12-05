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
<div class="card gedf-card">
    <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <img class="rounded-circle" width="45" src="https://picsum.photos/50/50" alt="">
                </div>
                <div class="ml-1">
                    <div class="h5 m-0">@LeeCross</div>
                    <div class="h7 text-muted">Miracles Lee Cross</div>
                </div>
                <div class="text-muted h7 mb-2 ml-auto"> <i class="fa fa-clock-o"></i>10 min ago</div>
            </div>
    </div>
    <div class="container-sm card-body">
        <div class="d-flex justify-content-center align-items-center mx-1">
            <img src="./assets/images/post/pfp1.jpg" class="img-fluid w-100 post-image" alt="">
        </div>

        <p class="card-text">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo recusandae nulla rem eos ipsa praesentium esse magnam nemo dolor
            sequi fuga quia quaerat cum, obcaecati hic, molestias minima iste voluptates.
        </p>
    </div>
    <div class="card-footer">
        <div class="d-flex justify-content-left">
            <p class="d-inline-flex gap-1">
                <button class="btn btn-link bi bi-chat" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample"></button>
            </p>
            <div class="collapse" id="collapseExample">
                <?php include './common/commenti.php'; ?>
            </div>
        </div>
    </div>
</div>