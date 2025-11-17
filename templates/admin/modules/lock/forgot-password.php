<?php
global $CDWFunc, $CDWRecaptcha;
?>
<div class="card">
    <div class="header">
        <p class="lead">Khôi phục mật khẩu</p>
    </div>
    <div class="body">
        <p>Vui lòng nhập email để tạo lại mật khẩu.</p>
        <form class="form-forgot-password-small" method="POST">
            <?php wp_nonce_field('ajax-forgot-password-nonce', 'nf_forgot_password'); ?>
            <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('login', 'lock'); ?>">
            <div class="form-group">
                <input type="email" class="form-control" id="forgot-password-email" placeholder="Email" required>
            </div>
            <?php $CDWRecaptcha->printItem('forgot-password'); ?>
            <button type="submit" class="btn btn-primary btn-lg btn-block btn-forgot-password">Tạo lại mật khẩu</button>
            <div class="bottom">
                <span class="helper-text">Bạn có mật khẩu? <a href="<?php echo $CDWFunc->getUrl('login', 'lock'); ?>">Đăng nhập</a></span>
            </div>
        </form>
    </div>
</div>