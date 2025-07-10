<?php
global $CDWFunc, $CDWNotification;
?>
<div class="notification ">
    <div class="card ">
        <div class="body notification-action">
            <?php wp_nonce_field('ajax-notification-nonce', 'nonce'); ?>
            <div class="input-group ">
                <input type="text" class="form-control notification-search" placeholder="Nội dung cần tìm..." />
                <div class="input-group-btn">
                    <div class="btn-group" role="group">
                        <div class="dropdown dropdown-lg">
                            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><span class="caret"></span></button>
                            <div class="dropdown-menu dropdown-menu-right" role="menu">
                                <form class="form-horizontal" role="form">
                                    <div class="form-group">
                                        <label for="filter">Lọc</label>
                                        <select class="form-control filter-type">
                                            <option value="" selected>Tất cả</option>
                                            <?php
                                            foreach ($CDWNotification->list_type_notification as $key => $value) {
                                            ?>
                                                <option value="<?php echo $key; ?>"><?php echo $value["text"]; ?></option>
                                            <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-block btn-notification-search">Tìm kiếm</button>
                                </form>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-notification-search"><span class="icon-magnifier" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <ul class="nav nav-tabs-new m-b-20 notification-type">
                <li class="nav-item mb-2 mr-2"><a class="nav-link active" data-toggle="tab" href="#all">Tất cả</a></li>
                <?php
                foreach ($CDWNotification->list_type_notification as $key => $value) {
                ?>
                    <li class="nav-item mb-2 mr-2 text-<?php echo $value["color"]; ?>"><a class="nav-link text-<?php echo $value["color"]; ?>" data-toggle="tab" href="#<?php echo $key; ?>" data-type="<?php echo $key; ?>"><?php echo $value["text"]; ?></a></li>
                <?php
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="tab-content padding-0">
        <div class="tab-pane card active" id="all">

            <div class="notification-list mb-0">

            </div>
            <ul class="body pagination pagination-primary" data-page="1">
                <li class="page-item"><a class="page-link pagination-back" href="javascript:void(0);" disabled="disabled">Trước</a></li>

                <li class="page-item"><a class="page-link pagination-next" href="javascript:void(0);" data-contrinue="">Sau</a></li>
            </ul>
        </div>
    </div>
</div>