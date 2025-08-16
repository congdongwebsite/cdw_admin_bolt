<div id="left-sidebar" class="sidebar">
    <button type="button" class="btn-toggle-offcanvas"><i class="fa fa-arrow-left"></i></button>
    <div class="sidebar-scroll">
        <?php
        require_once('user-account.php');
        ?>

        <!-- Nav tabs -->
        <ul class="nav nav-tabs">
            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#menu">Menu</a></li>
            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#setting"><i class="icon-settings"></i></a></li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content padding-0">
            <div class="tab-pane active" id="menu">
                <?php
                require_once('menu.php');
                ?>
            </div>
            <div class="tab-pane" id="Chat">
                <?php
                require_once('chat-form.php');
                require_once('chat-list.php');
                ?>
            </div>
            <div class="tab-pane" id="setting">
                <?php
                require_once('layout-setting.php');
                ?>
            </div>
            <div class="tab-pane" id="question">
                <?php
                require_once('question-form.php');
                require_once('question-list.php');
                ?>
            </div>
        </div>
    </div>
</div>