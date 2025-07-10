<?php
global $CDWFunc, $CDWRecaptcha;
?>
<div class="card">
    <div class="header">
        <p class="lead">Tạo tại khoản</p>
    </div>
    <div class="body">
        <form class="form-register-small" method="post">
            <?php wp_nonce_field('ajax-register-nonce', 'nf_register'); ?>
            <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('', ''); ?>">
            <div class="form-group">
                <label for="signup-account" class="control-label sr-only">Tài khoản</label>
                <input type="text" class="form-control" id="signup-account" name="signup-account" value="" placeholder="Tên đăng nhập" required>
            </div>
            <div class="form-group">
                <label for="signup-name" class="control-label sr-only">Họ và tên</label>
                <input type="text" class="form-control" id="signup-name" name="signup-name" value="" placeholder="Họ và tên" required>
            </div>
            <div class="form-group">
                <label for="signup-email" class="control-label sr-only">Email</label>
                <input type="email" class="form-control" id="signup-email" name="signup-email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="signup-password" class="control-label sr-only">Mật khẩu</label>
                <input type="password" class="form-control" id="signup-password" name="signup-password" placeholder="Mật khẩu" data-parsley-pattern="(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}.*" data-parsley-pattern-message="Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự." required>
            </div>
            <div class="form-group">
                <label for="signup-password-re" class="control-label sr-only">Nhập lại mật khẩu</label>
                <input type="password" class="form-control" id="signup-password-re" name="signup-password-re" placeholder="Nhập lại mật khẩu" data-parsley-equalto="#signup-password" required>
            </div>
            <?php $CDWRecaptcha->printItem('register'); ?>
            <button type="submit" class="btn btn-primary btn-lg btn-block btn-register">Đăng ký</button>
            <div class="bottom">
                <span class="helper-text">Bạn đã có tài khoản? <a href="<?php echo $CDWFunc->getUrl('login', 'lock'); ?>">Đăng nhập</a></span>
            </div>
        </form>
        <div class="separator-linethrough"><span>OR</span></div>
        <button class="btn btn-signin-social"><i class="fa fa-facebook-official facebook-color"></i> Đăng nhập bằng Facebook</button>
        <button class="btn btn-signin-social"><i class="fa fa-twitter twitter-color"></i> Đăng nhập bằng Twitter</button>
    </div>
</div>