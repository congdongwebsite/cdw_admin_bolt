<script type="text/html" id="tmpl-ticket-pagination-template">
    <p class="lable-pagination"><span class="count-from">{{data.from}}</span> - <span class="count-to">{{data.to}}</span>/ <span class="total">{{data.total}}</span></p>
    <div class="btn-group ml-2">
        <button type="button" data-page="{{data.pageBack}}" class="btn btn-outline-secondary btn-sm pagination-back" <# if ( data.disabledBack ) { #>
            disabled="disabled"
            <# } #>
                ><i class="fa fa-angle-left"></i>
        </button>
        <button type="button" data-page="{{data.pageNext}}" class="btn btn-outline-secondary btn-sm pagination-next" <# if ( data.disabledNext ) { #>
            disabled="disabled"
            <# } #>
                ><i class="fa fa-angle-right"></i>
        </button>
    </div>
</script>