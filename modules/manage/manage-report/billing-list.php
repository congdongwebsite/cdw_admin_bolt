<div class="report-billing-list">
    <?php wp_nonce_field('ajax-report-billing-list-nonce', 'nonce'); ?>
    <?php require_once('filter-billing-list.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>