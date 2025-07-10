<?php
global $CDWUser;
$avatar = $CDWUser->avatar;
$phone = $CDWUser->phone;
$name = $CDWUser->name;
$email = $CDWUser->email;
$birthdate = $CDWUser->birthdate;
$address = $CDWUser->address;
$username = $CDWUser->username;
$useremail = $CDWUser->useremail;
$gender = $CDWUser->gender;
$website = $CDWUser->website;

$dvhc_tp = $CDWUser->idtp;
$dvhc_qh = $CDWUser->idqh;
$dvhc_px = $CDWUser->idpx;
$address = $CDWUser->straddress;
?>
<div class="row clearfix">
    <div class="col-lg-12">
        <div class="card">
            <div class="body">
                <ul class="nav nav-tabs-new2">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#Settings">Thông tin</a></li>
                </ul>
            </div>
            <div class="tab-content">
                <div class="tab-pane active" id="Settings">
                    <form class="form-setting-base-small" method="post">
                        <?php wp_nonce_field('ajax-setting-base-nonce', 'nonce'); ?>
                        <div class="body">
                            <h6>Ảnh đại diện</h6>
                            <div class="media">
                                <div class="media-left m-r-15">
                                    <img class="avatar" src="<?php echo $avatar; ?>" class="user-photo media-object" alt="<?php echo $name; ?>">
                                </div>
                                <div class="media-body">
                                    <p>Tải lên ảnh của bạn.
                                        <br> <em>Ảnh JPG, PNG, JPEG, GIF</em>
                                    </p>
                                    <button type="button" class="btn btn-default" id="btn-upload-photo">Ảnh tải lên</button>
                                    <input type="file" id="avatar-custom" accept="image/*" class="sr-only">
                                </div>
                            </div>
                        </div>

                        <div class="body">
                            <h6>Thông tin cơ bản</h6>
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">
                                    <div class="form-group">
                                        <input type="text" id="first-name" name="first-name" value="<?php echo $name; ?>" class="form-control" placeholder="Họ và tên" required>
                                    </div>
                                    <div class="form-group">
                                        <div>
                                            <label class="fancy-radio">
                                                <input id="gender" name="gender" value="male" type="radio" <?php echo $gender == 'male' ? 'checked' : ''; ?>>
                                                <span><i></i>Nam</span>
                                            </label>
                                            <label class="fancy-radio">
                                                <input id="gender" name="gender" value="female" type="radio" <?php echo $gender == 'female' ? 'checked' : ''; ?>>
                                                <span><i></i>Nữ</span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="icon-calendar"></i></span>
                                            </div>
                                            <input class="form-control datepicker" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>" class="form-control" placeholder="Ngày sinh">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="url" id="website" name="website" value="<?php echo $website; ?>" class="form-control" placeholder="http://">
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-12">

                                    <div class="form-group">
                                        <label for="dvhc-tp" class="control-label">Tỉnh/Thành phố</label>
                                        <select id='dvhc-tp' name='dvhc-tp' class='select2 form-control' data-value="<?php echo $dvhc_tp; ?>"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dvhc-qh" class="control-label">Quận/Huyện</label>
                                        <select id='dvhc-qh' name='dvhc-qh' class='select2 form-control' data-value="<?php echo $dvhc_qh; ?>"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="dvhc-px" class="control-label">Phường/Xã</label>
                                        <select id='dvhc-px' name='dvhc-px' class='select2 form-control' data-value="<?php echo $dvhc_px; ?>"></select>
                                    </div>
                                    <div class="form-group">
                                        <label for="address" class="control-label">Địa chỉ</label>
                                        <input type="text" id="address" name="address" value="<?php echo $address; ?>" class="form-control" placeholder="Địa chỉ">
                                    </div>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-setting-base">Cập nhật</button>
                    </form>
                </div>

                <form class="form-setting-account-small" method="post">
                    <?php wp_nonce_field('ajax-setting-account-nonce', 'nonce'); ?>
                    <div class="body">
                        <div class="row clearfix">
                            <div class="col-lg-6 col-md-12">
                                <h6>Thông tin tài khoản</h6>
                                <div class="form-group">
                                    <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" disabled placeholder="Tài khoản">
                                </div>
                                <div class="form-group">
                                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $useremail; ?>" placeholder="Email" required>
                                </div>
                                <div class="form-group">
                                    <input type="text" id="phone" name="phone" class="form-control" value="<?php echo $phone; ?>" placeholder="Số điện thoại" required>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <h6>Đổi mật khẩu</h6>
                                <div class="form-group">
                                    <input type="password" id="current-password" name="current-password" class="form-control" placeholder="Mật khẩu hiện tại">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="new-password" name="new-password" class="form-control" placeholder="Mật khẩu mới" data-parsley-pattern="(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}.*" data-parsley-pattern-message="Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự.">
                                </div>
                                <div class="form-group">
                                    <input type="password" id="confirm-password" name="confirm-password" class="form-control" placeholder="Nhập lại mật khẩu" data-parsley-pattern="(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}.*" data-parsley-pattern-message="Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự.">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-primary btn-setting-account">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>