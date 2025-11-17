<div class="client-domain-choose file_manager">
    <?php wp_nonce_field('ajax-client-domain-choose-nonce', 'nonce'); ?>
    <h1 class="text-center text-primary mb-4">Tìm Kiếm Domain</h1>
    <div class="row clearfix">
        <div class="card">
            <div class="body">
                <div class="input-group" id="adv-search">
                    <input type="text" class="form-control input-domain" placeholder="Nhập domain bạn muốn tìm kiếm ..." required>
                    <div class="input-group-btn">
                        <div class="btn-group" role="group">
                            <div class="dropdown dropdown-lg">
                                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Loại <span class="caret"></span></button>
                                <div class="dropdown-menu dropdown-menu-right" role="menu" style="top: 80%;">
                                    <form class="form-horizontal" role="form">
                                        <div class="form-group">
                                            <label for="filter">Loại Domain</label>
                                            <select class="form-control type-domain">
                                                <option value="" selected>Chọn loại domain</option>
                                                <?php
                                                $args = array(
                                                    'post_type' => 'domain',
                                                    'post_status' => 'publish',
                                                    'posts_per_page' => -1,
                                                    'fields' => 'ids',
                                                    'order' => 'ASC',
                                                );
                                                $ids = get_posts($args);

                                                foreach ($ids as $id) {
                                                ?>
                                                    <option value="<?php echo $id; ?>"><?php echo get_the_title($id); ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block btn-search">Search</button>
                                    </form>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary btn-search"><span class="icon-magnifier" aria-hidden="true"></span></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="body project_report">
                <div class="table-responsive">
                    <table class="table mb-0 table-hover domain-list">
                        <thead class="thead-light">
                            <tr>
                                <th>Tình Trạng</th>
                                <th>Tiên Miền</th>
                                <th>Đăng Ký Mới</th>
                                <th>Phí Duy Trì</th>
                                <th>Lựa Chọn</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    Nhập để tìm kiếm domain...
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>