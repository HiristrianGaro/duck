<div class="modal fade mt-4"  id="errorModal" tabindex="-1" aria-labelledby="errorModalLabel" aria-hidden="true">
    <div class="modal-dialog" style="max-height: fit-content;">
        <div class="modal-content bg-white">
            <div class="modal-body border border-blue rounded-2 p-0" > 
                <p class="fs-5 text-center mt-2 "> <i class="bi bi-exclamation-triangle text-danger"></i> <?php if (isset($errore)) {echo"{$errore}";}?></p>
            </div>
        </div>
    </div>
</div>