<div class="client-plugin-choose">
    <?php wp_nonce_field('ajax-client-plugin-nonce', 'nonce'); ?>
    <div class="card">
        <div class="body">
            <div class="input-group" id="adv-search">
                <input type="text" class="form-control input-plugin" placeholder="Nhập plugin bạn muốn tìm kiếm ..." required>
                <div class="input-group-btn">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-primary btn-search"><span class="icon-magnifier" aria-hidden="true"></span></button>
                    </div>
                </div>
            </div>
        </div>
        <div class="body">
            <ul class="nav nav-tabs-new type-list">
                <li class="nav-item mb-2 mr-2"><a class="nav-link active show type-item" data-toggle="tab" data-idt="-1" href="javascript:void(0)">Tất cả</a></li>
                <?php

                $args = array(
                    'taxonomy' => 'plugin-type',
                    'hide_empty' => false,
                    'number' => 0,
                    'fields' => 'id=>name'
                );

                $terms = get_terms($args);

                foreach ($terms as $id => $term) {
                ?>
                    <li class="nav-item mb-2 mr-2"><a class="nav-link type-item" data-toggle="tab" data-idt="<?php echo $id; ?>" href="javascript:void(0)"><?php echo $term; ?></a></li>
                <?php
                }

                ?>
            </ul>
            <div class="tab-content row plugin-list">
            </div>
        </div>
    </div>
</div>