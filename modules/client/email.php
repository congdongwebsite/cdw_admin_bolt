<div class="client-email">
    <?php wp_nonce_field('ajax-client-email-nonce', 'nonce'); ?>
    <input type="hidden" name="url-choose" id="url-choose" value="<?php echo $CDWFunc->getUrl('email', 'client', "subaction=choose"); ?>">
    <?php require_once('filter-email.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable"></table>
        </div>
    </div>
</div>
<?php require_once('modal-register-email.php'); ?>
<?php require_once('modal-change-email-plan.php'); ?>