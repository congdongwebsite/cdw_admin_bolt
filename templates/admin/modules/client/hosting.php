<div class="client-hosting">
    <?php wp_nonce_field('ajax-client-hosting-nonce', 'nonce'); ?>
    <input type="hidden" name="url-choose" id="url-choose" value="<?php echo $CDWFunc->getUrl('hosting', 'client', "subaction=choose"); ?>">
    <?php require_once('filter-hosting.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>