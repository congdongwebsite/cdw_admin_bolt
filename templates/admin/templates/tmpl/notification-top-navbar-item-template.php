<script type="text/html" id="tmpl-notification-top-navbar-item-template">
    <li data-idn="{{data.id}}" data-url="{{data.url}}" class="notification-item">
        <a href="{{data.url}}">
            <div class="media">
                <div class="media-left">
                    <i class="{{data.icon}}"></i>
                </div>
                <div class="media-body">
                    <h5 class="text text-truncate text-{{data.color}}">{{data.title}}</h5>
                    <p class="text">{{data.content}}</p>
                    <span class="timestamp">{{data.time}}</span>
                </div>
            </div>
        </a>
        <# if ( data.isAdministrator ) { #>
            <div class="hover-action" style="z-index: 999999;">
                <a class="m-r-20 notification-bell-delete text-danger" data-id-notification="{{data.id}}" target="_blank" href="javascript:void(0);"><i class="fa fa-times-circle"></i></a>
            </div>
            <# } #>
    </li>
</script>