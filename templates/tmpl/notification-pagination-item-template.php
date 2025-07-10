<script type="text/html" id="tmpl-notification-pagination-item-template">
    <# if ( data.back ) { #>
        <li class="page-item"><a class="page-link pagination-back" href="javascript:void(0);">Trước</a></li>
        <# } #>
            <# if ( !data.back && !data.next ) { #>
                <li class="page-item     <# if ( data.active ) { #>
        active
                    <# } #>" data-page="{{data.page}}"><a class="page-link number" href="javascript:void(0);">{{data.page}}</a></li>
                <# } #>
                    <# if ( data.next ) { #>
                        <li class="page-item"><a class="page-link pagination-next" href="javascript:void(0);" data-contrinue="{{data.continue}}">Sau</a></li>
                        <# } #>
</script>