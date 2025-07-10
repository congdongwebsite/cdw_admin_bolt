<?php
global $CDWFunc, $CDWRecaptcha;
//data-parsley-pattern="(?=.*[A-Z])(?=.*[!@#$&*])(?=.*[0-9])(?=.*[a-z]).{8}.*" data-parsley-pattern-message="Mật khẩu phải bao gồm: Chữ hoa, ký tự đặc biệt, số, chữ thường, hơn 8 ký tự."
?>
<div class="card">
    <div class="header">
        <p class="lead"><?php echo $moduleCurrent->actionName ?></p>
    </div>
    <div class="body">
        <form class="form-auth-small" method="post">
            <?php wp_nonce_field('ajax-login-nonce', 'nf_login'); ?>
            <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('', ''); ?>">
            <div class="form-group">
                <label for="signin-email" class="control-label sr-only">Tài khoản</label>
                <input type="text" class="form-control" id="signin-email" name="signin-email" value="<?php echo isset($_GET['username']) ? $_GET['username'] : ""; ?>" placeholder="Email hoặc tên đăng nhập" required>
            </div>
            <div class="form-group">
                <label for="signin-password" class="control-label sr-only">Mật khẩu</label>
                <input type="password" class="form-control" id="signin-password" name="signin-password" value="" placeholder="Password"  required>
            </div>
            <div class="form-group clearfix">
                <label class="fancy-checkbox element-left">
                    <input type="checkbox" id="signin-remember" name="signin-remember" value="true">
                    <span>Ghi nhớ đăng nhập</span>
                </label>
            </div>
            <?php $CDWRecaptcha->printItem('login'); ?>
            <button type="submit" class="btn btn-primary btn-lg btn-block btn-login">Đăng nhập</button>
            <div class="bottom">
                <span class="helper-text m-b-10"><i class="fa fa-lock"></i> <a href="<?php echo $CDWFunc->getUrl('forgot-password', 'lock'); ?>">Quên mật khẩu?</a></span>
                <span>Bạn chưa có tài khoản? <a href="<?php echo $CDWFunc->getUrl('register', 'lock'); ?>">Đăng ký</a></span>
            </div>
        </form>
    </div>
</div>