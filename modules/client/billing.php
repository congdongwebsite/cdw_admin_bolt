<div class="client-billing">
    <?php wp_nonce_field('ajax-client-billing-nonce', 'nonce'); ?>
    <?php require_once('filter-billing.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>