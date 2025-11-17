<script type="text/html" id="tmpl-notification-item-template">
    <div class="body notification-item row clearfix border-bottom
    <# if ( data.isRead ) { #>
        unread
                    <# } #> pb-0" data-id-notification="{{data.id}}">
        <# if ( data.notFound ) { #>
            <p class="m-t-10 text">Không có thông báo mới</p>
            <# } #>
                <# if ( !data.notFound ) { #>

                    <h6 class=" d-flex flex-column col-lg-3 col-md-3 col-sm-12 col-12">
                        <a target="_blank" href="{{data.link}}"><i class="{{data.icon}}"></i><span class="ml-3 text-truncate text-{{data.color}}">{{data.title}}</span>
                        </a>
                        <small class="mt-2">{{data.date}}</small>
                    </h6>
                    <p class="text col-lg- col-md-6 col-sm-8 col-12">{{data.content}}</p>
                    <div class="actions col-lg-3 col-md-3 col-sm-4 col-12">
                        <# if ( !data.read ) { #>
                            <a class="m-r-20 notification-item-read" target="_blank" href="javascript:void(0);">Đánh dấu đã đọc</a>
                            <# } #>
                                <# if ( data.read ) { #>
                                    <a class="m-r-20 notification-item-read text-warning" target="_blank" href="javascript:void(0);">Đánh dấu chưa đọc</a>
                                    <# } #>
                                        <a class="m-r-20 notification-item-delete text-danger" target="_blank" href="javascript:void(0);">Xóa</a>

                                        <# } #>
                    </div>
    </div>
</script>