<div class="report-email-list">
    <?php wp_nonce_field('ajax-report-email-list-nonce', 'nonce'); ?>
    <input type="hidden" name="url-choose" id="url-choose" value="<?php echo $CDWFunc->getUrl('email', 'client', "subaction=choose"); ?>">
   <?php require_once('filter-email-list.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>