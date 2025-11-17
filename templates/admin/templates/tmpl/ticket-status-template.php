<script type="text/html" id="tmpl-ticket-status-template">
    <li data-status="pending" class="ticket-pending
    <# if ( data.statusActive == 'pending' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="icon-envelope"></i>Yêu cầu mới<span class="badge badge-primary float-right number">{{data.pending}}</span></a>
    </li>
    <li data-status="processing" class="ticket-processing
    <# if ( data.statusActive == 'processing' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="icon-envelope"></i>Đang xử lý<span class="badge badge-primary float-right number">{{data.processing}}</span></a>
    </li>
    <li data-status="success" class="ticket-success
    <# if ( data.statusActive == 'success' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="icon-cursor"></i>Đã xử lý<span class="badge badge-success float-right number">{{data.success}}</span></a>
    </li>
    <li data-status="important" class="ticket-important
    <# if ( data.statusActive == 'important' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="icon-star"></i>Yêu cầu quan trọng<span class="badge badge-warning float-right number">{{data.important}}</span></a>
    </li>
    <li data-status="archive" class="ticket-archive
    <# if ( data.statusActive == 'archive' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="fa fa-archive"></i>Lưu trữ<span class="badge badge-info float-right number">{{data.archive}}</span></a>
    </li>
    <li data-status="trash" class="ticket-trash
    <# if ( data.statusActive == 'trash' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="fa fa-trash-o"></i>Thùng rác <span class="badge badge-danger float-right number">{{data.trash}}</span></a>
    </li>
</script>