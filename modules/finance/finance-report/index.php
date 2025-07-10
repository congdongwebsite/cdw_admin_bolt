<div class="report-index">
    <?php wp_nonce_field('ajax-report-index-nonce', 'nonce'); ?>
    <div class="row clearfix">
        <div class="col-xl-8 col-lg-8 col-md-12 col-12">
            <?php require_once('filter-index.php'); ?>
            <div class="card widgets">
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 dk-dt">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon text-info"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Doanh thu đầu kỳ</div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 ps-dt">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon text-warning"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Doanh Thu Hoá Đơn </div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 dk">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon text-info"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Đầu kỳ</div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 ps-thu">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon text-warning"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Phí Thu Ngoài</div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 ps-chi">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon text-danger"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Phiếu Chi Tổng</div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-6 ck">
                            <div class="card top_counter">
                                <div class="body">
                                    <div class="icon"><i class="fa fa-money"></i> </div>
                                    <div class="content">
                                        <div class="text">Lợi Nhuận Ròng</div>
                                        <h5 class="number">0</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-12 col-12">
            <?php
            $arr = array(
                'post_type' => 'finance-type',
                'post_status' => 'publish',
                'orderby' => 'date',
                'order' => 'DESC',
                'fields' => 'ids',
                'posts_per_page' => -1,
            );

            $ids = get_posts($arr);
            ?>
            <div class="card">
                <div class="header">
                    <h2>Tổng Thu - Chi<small>Theo loại</small></h2>
                </div>
                <div class="body">
                    <ul class="list-unstyled feeds_widget widget-data-type">
                        <?php
                        foreach ($ids as $id) {
                        ?>
                            <li class="item-type" data-id="<?php echo $id; ?>">
                                <div class="feeds-left text-primary"><i class="fa fa-money"></i></div>
                                <div class="feeds-body">
                                    <h4 class="title"><?php echo get_post_meta($id, 'name', true); ?><small class="float-right text-muted number">0</small></h4>
                                    <small><?php echo get_post_meta($id, 'note', true); ?></small>
                                </div>
                            </li>
                        <?php
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="body">
            <table id="tb-data" class="table table-bordered table-hover table-striped w-100 dataTable">
        </div>
    </div>
</div>