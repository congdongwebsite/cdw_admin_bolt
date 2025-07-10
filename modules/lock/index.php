<?php
global $CDWFunc, $userCurrent;
?>
<div class="card">
    <div class="body">
        <div class="user text-center m-b-30">
            <img src="<?PHP echo ADMIN_CHILD_THEME_URL_F; ?>/assets/images/user-small.png" class="rounded-circle" alt="Avatar">
            <h4 class="name m-t-10"><?php echo $userCurrent->data->display_name; ?></h4>
            <p><?php echo $userCurrent->data->user_email; ?></p>
        </div>
        <form class="form-lock-small" method="post">
            <?php wp_nonce_field('ajax-lock-nonce', 'nf_lock'); ?>
            <input type="hidden" id="urlredirect" value="<?php echo $CDWFunc->getUrl('', ''); ?>">
            <div class="form-group">
                <input type="password" class="form-control" id="lock-password" name="lock-password" placeholder="Enter your password ..." required>
            </div>
            <button type="submit" class="btn btn-primary btn-lg btn-block btn-unlock">Mở khóa</button>
        </form>
    </div>
</div>