<?php
global $CDWFunc;

?>
<div class="card ticket">
    <?php wp_nonce_field('ajax-ticket-user-nonce', 'nonce'); ?>
    <div class="mobile-left">
        <a class="btn btn-primary toggle-ticket-nav collapsed" data-toggle="collapse" href="#ticket-nav" role="button" aria-expanded="false" aria-controls="ticket-nav">
            <span class="btn-label"><i class="la la-bars"></i></span>
            Menu
        </a>
    </div>
    <div class="ticket-inbox">
        <div class="ticket-left collapse" id="ticket-nav">
            <div class="mail-compose m-b-20">
                <a href="<?php echo $CDWFunc->getURL('new', 'ticket'); ?>" class="btn btn-danger btn-block">Tạo mới</a>
            </div>
            <div class="ticket-side ">
                <ul class="nav ticket-status"></ul>
                <h3 class="label">Phân loại</h3>
                <ul class="nav ticket-types">
                </ul>
            </div>
        </div>
        <div class="ticket-right">
            <div class="ticket-header header d-flex align-center">
                <div class="ml-auto">
                    <div class="input-group">
                        <input type="text" class="form-control ticket-search" placeholder="Tìm kiếm" aria-label="Tìm kiếm" aria-describedby="search-ticket">
                        <div class="input-group-append">
                            <a href="javascript:void(0);" class="input-group-text btn-ticket-search"><i class="icon-magnifier"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket-action clearfix">
                <div class="float-left">
                    <div class="fancy-checkbox d-inline-block">
                        <label>
                            <input class="select-all" type="checkbox" name="select-all">
                            <span></span>
                        </label>
                    </div>
                    <div class="btn-group">
                        <a href="javascript:void(0);" class="btn btn-outline-secondary btn-sm ticket-refresh">Tải lại</a>
                    </div>
                    <div class="btn-group">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Chức năng</button>
                        <div class="dropdown-menu ticket-action">
                            <a class="dropdown-item ticket-unread-list" href="javascript:void(0);">Đánh dấu đã đọc</a>
                            <a class="dropdown-item ticket-read-list" href="javascript:void(0);">Đánh dấu chưa đọc</a>
                            <a class="dropdown-item ticket-pin-important-list" href="javascript:void(0);">Đánh dấu sao</a>
                            <a class="dropdown-item ticket-pin-unimportant-list" href="javascript:void(0);">Bỏ dấu sao</a>
                        </div>
                    </div>
                </div>
                <div class="float-right ml-auto">
                    <div class="ticket-pagination d-flex align-items-center">
                        <p class="lable-pagination"><span class="count-from"></span> - <span class="count-to"></span>/ <span class="total"></span></p>
                        <div class="btn-group ml-2">
                            <button type="button" class="btn btn-outline-secondary btn-sm pagination-back" disabled="disabled"><i class="fa fa-angle-left"></i></button>
                            <button type="button" class="btn btn-outline-secondary btn-sm pagination-next" disabled="disabled"><i class="fa fa-angle-right"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ticket-list">
                <ul class="list-unstyled">
                </ul>
            </div>
        </div>
    </div>
</div>