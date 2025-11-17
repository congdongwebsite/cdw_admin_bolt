<div class="all-service">
    <?php wp_nonce_field('ajax-all-service-nonce', 'nonce'); ?>
    <?php require_once('filter-all-service.php'); ?>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>