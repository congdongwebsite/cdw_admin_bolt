<script type="text/html" id="tmpl-ticket-status-user-template">
    <li data-status="pending" class="ticket-pending
    <# if ( data.statusActive == 'pending' ) { #>
        active
        <# } #>
            "><a href="javascript:void(0);"><i class="icon-envelope"></i>Yêu cầu mới<span class="badge badge-primary float-right number">{{data.pending}}</span></a>
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
</script>