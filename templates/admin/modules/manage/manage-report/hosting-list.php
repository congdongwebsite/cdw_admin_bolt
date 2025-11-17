<div class="report-hosting-list">
    <?php wp_nonce_field('ajax-report-hosting-list-nonce', 'nonce'); ?>
    <input type="hidden" name="url-choose" id="url-choose" value="<?php echo $CDWFunc->getUrl('hosting', 'client', "subaction=choose"); ?>">
    <?php require_once('filter-hosting-list.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>