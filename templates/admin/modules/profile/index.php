<?php
global $CDWUser, $CDWFunc;
$avatar = $CDWUser->avatar;
$phone = $CDWUser->phone;
$name = $CDWUser->name;
$email = $CDWUser->email;
$birthdate = $CDWUser->birthdate;
$address = $CDWUser->address;
$gender = $CDWUser->gender;

if (isset($_POST['destroy'])) {
    $session_id = $_POST['destroy'];
    $CDWFunc->destroy_session($CDWUser->id, $session_id);
}

$sessions = wp_get_all_sessions($CDWUser->id);

$customer_id = get_user_meta($CDWUser->id, 'customer-id', true);
$kyc_status = '1'; // Mặc định là chưa xác thực

$kyc_status_meta = get_post_meta($customer_id, 'status-kyc', true);
if (!empty($kyc_status_meta)) {
    $kyc_status = $kyc_status_meta;
}

switch ($kyc_status) {
    case '2':
        $kyc_status_text = 'Đang Xác Thực Tài Khoản';
        $kyc_status_class = 'warning';
        break;
    case '3':
        $kyc_status_text = 'Đã Xác Thực Tài Khoản';
        $kyc_status_class = 'success';
        break;
    case '1':
    default:
        $kyc_status_text = 'Chưa Xác Thực Tài Khoản';
        $kyc_status_class = 'danger';
        break;
}
?>
<div class="row clearfix">
    <div class="col-12">
        <div class="card">
            <div class="body">
                <h6 class="mb-0">Trạng thái tài khoản: <span class="text-<?php echo $kyc_status_class; ?> font-weight-bold"><?php echo $kyc_status_text; ?></span></h6>
            </div>
        </div>
    </div>

    <div class="col-lg-3 col-md-12">
        <div class="card profile-header">
            <div class="body">
                <div class="profile-image"> <img width="150" src="<?php echo $avatar; ?>" class="rounded-circle" alt="<?php echo $name; ?>"> </div>
                <div>
                    <h4 class="mb-0"><strong><?php echo $name; ?></strong></h4>
                    <span class="text-capitalize"><?php echo $gender; ?></span>
                </div>
                <div class="m-t-15">
                    <a class="btn btn-primary" href="<?php echo $CDWFunc->getURL('index', 'setting'); ?>">Cài đặt</a>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="header">
                <h2>Thông tin</h2>
            </div>
            <div class="body">
                <small class="text-muted">Địa chỉ: </small>
                <p><?php echo $address; ?></p>
                <hr>
                <small class="text-muted">Email: </small>
                <p><?php echo $email; ?></p>
                <hr>
                <small class="text-muted">Điện thoại: </small>
                <p><?php echo $phone; ?></p>
                <hr>
                <small class="text-muted">Ngày sinh: </small>
                <p class="mb-0"><?php echo $CDWFunc->date->convertDateTimeDisplay($birthdate); ?></p>
            </div>
        </div>
    </div>

    <div class="col-lg-6 col-md-12">

        <div class="card">
            <ul class="row profile_state list-unstyled">
                <li class="col-lg-3 col-3">
                    <div class="body">
                        <i class="fa fa-camera"></i>
                        <h5 class="mb-0 number count-to" data-from="0" data-to="2365" data-speed="1000" data-fresh-interval="700">2365</h5>
                        <small>Shots View</small>
                    </div>
                </li>
                <li class="col-lg-3 col-3">
                    <div class="body">
                        <i class="fa fa-thumbs-o-up"></i>
                        <h5 class="mb-0 number count-to" data-from="0" data-to="1203" data-speed="1000" data-fresh-interval="700">1203</h5>
                        <small>Likes</small>
                    </div>
                </li>
                <li class="col-lg-3 col-3">
                    <div class="body">
                        <i class="fa fa-comments-o"></i>
                        <h5 class="mb-0 number count-to" data-from="0" data-to="324" data-speed="1000" data-fresh-interval="700">324</h5>
                        <small>Comments</small>
                    </div>
                </li>
                <li class="col-lg-3 col-3">
                    <div class="body">
                        <i class="fa fa-user"></i>
                        <h5 class="mb-0 number count-to" data-from="0" data-to="1980" data-speed="1000" data-fresh-interval="700">1980</h5>
                        <small>Profile Views</small>
                    </div>
                </li>
            </ul>
        </div>
        <div class="card single_post">
            <div class="body">
                <?php
                $arr = array(
                    'post_type' => 'customer',
                    'post_status' => 'publish',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    'meta_query' => array(
                        array(
                            'key'     => 'user-id',
                            'value'   => $CDWUser->id,
                            'compare' => '=',
                        )
                    )
                );
                $id_customers = get_posts($arr);
                $arr = array(
                    'post_type' => 'customer-billing',
                    'fields' => 'ids',
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'ASC',
                    'meta_query' => array(
                        array(
                            'key' => "customer-id",
                            'value' => $id_customers,
                            'compare' => 'in',
                        )
                    )
                );
                $ids = get_posts($arr);
                $data = [];

                $columns = ['code', 'date', 'note', 'amount'];
                foreach ($ids as $id) {
                    switch (get_post_meta($id, "status", true)) {
                        case "publish":
                ?>
                            <div class="timeline-item green" date-is="<?php echo $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date', true)); ?>">
                                <h5>Hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $id); ?>">#<?php echo get_post_meta($id, 'code', true); ?></a>/ <small><?php echo $CDWFunc->number_format(get_post_meta($id, 'amount', true)); ?></small></h5>
                                <span><a href="javascript:void(0);"><?php echo $CDWFunc->get_lable_status(get_post_meta($id, "status", true)); ?></a></span>
                                <div class="msg">
                                    <p><?php echo get_post_meta($id, 'note', true); ?></p>
                                </div>
                            </div>
                        <?php
                            break;
                        case "publish":
                        case "pending":
                        ?>
                            <div class="timeline-item blue" date-is="<?php echo $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date', true)); ?>">
                                <h5>Hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $id); ?>">#<?php echo get_post_meta($id, 'code', true); ?></a>/ <small><?php echo $CDWFunc->number_format(get_post_meta($id, 'amount', true)); ?></small></h5>
                                <span><a href="javascript:void(0);"><?php echo $CDWFunc->get_lable_status(get_post_meta($id, "status", true)); ?></a></span>
                                <div class="msg">
                                    <p><?php echo get_post_meta($id, 'note', true); ?></p>
                                </div>
                            </div>
                        <?php
                            break;
                        case "cancel":

                        ?>

                            <div class="timeline-item warning" date-is="<?php echo $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date', true)); ?>">
                                <h5>Hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $id); ?>">#<?php echo get_post_meta($id, 'code', true); ?></a>/ <small><?php echo $CDWFunc->number_format(get_post_meta($id, 'amount', true)); ?></small></h5>
                                <span><a href="javascript:void(0);"><?php echo $CDWFunc->get_lable_status(get_post_meta($id, "status", true)); ?></a></span>
                                <div class="msg">
                                    <p><?php echo get_post_meta($id, 'note', true); ?></p>
                                </div>
                            </div>
                        <?php
                            break;
                        default:
                        ?>
                            <div class="timeline-item green" date-is="<?php echo $CDWFunc->date->convertDateTimeDisplay(get_post_meta($id, 'date', true)); ?>">
                                <h5>Hóa đơn <a class="mr-3  text-primary" target="_blank" href="<?php echo $CDWFunc->getUrl('checkout', 'client', 'id=' . $id); ?>">#<?php echo get_post_meta($id, 'code', true); ?></a>/ <small><?php echo $CDWFunc->number_format(get_post_meta($id, 'amount', true)); ?></small></h5>
                                <span><a href="javascript:void(0);"><?php echo $CDWFunc->get_lable_status(get_post_meta($id, "status", true)); ?></a></span>
                                <div class="msg">
                                    <p><?php echo get_post_meta($id, 'note', true); ?></p>
                                </div>
                            </div>
                    <?php
                            break;
                    }
                    ?>

                <?php
                }
                ?>
            </div>

        </div>

    </div>

    <div class="col-lg-3 col-md-12">

        <div class="card">
            <div class="header">
                <h2>Lịch sử đăng nhập</h2>
            </div>

            <div class="body overflow-auto" style="height: 700px;">
                <ul class="list-unstyled list-login-session">
                    <?php

                    foreach ($sessions as $session) {
                        $browser = $session['ua']; // Lấy thông tin về trình duyệt
                        $ip_address = $session['ip']; // Lấy địa chỉ IP của thiết bị
                        $login_time = date('d/m/Y H:i:s', $session['login']); // Lấy thời gian đăng nhập

                    ?>
                        <li>
                            <div class="login-session">
                                <i class="fa fa-<?php echo $CDWFunc->get_device_type_by_user_agent($browser); ?> device-icon"></i>
                                <div class="login-info">
                                    <h3 class="login-title"><?php echo $ip_address; ?> </h3>
                                    <span class="login-detail"><?php echo $browser; ?></span>
                                    <div>
                                        <span class="text-primary"><?php echo $login_time; ?></span>
                                        <?php
                                        if ($session['expiration'] > time()) {
                                        ?>
                                            <span class="text-success">Đang hoạt động</span>
                                        <?php
                                        }
                                        ?>
                                    </div>
                                </div>
                                <?php
                                if ($session['expiration'] > time()) {
                                ?>
                                    <a href="<?php echo $CDWFunc->getURL('index', 'profile', 'destroy=' . 1); ?>" class="btn btn-link btn-logout" data-container="body" data-toggle="tooltip" title="Xóa phiên đăng nhập"><i class="fa fa-times-circle text-danger"></i></a>
                                <?php
                                }
                                ?>
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