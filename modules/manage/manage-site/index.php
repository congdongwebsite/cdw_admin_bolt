<?php
global $CDWFunc;
wp_nonce_field('ajax-list-manage-site-nonce', 'nonce'); ?>
<?php $CDWFunc->getComponent('filter-post-type'); ?>
<div class="card">
    <div class="body">
        <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
    </div>
</div>