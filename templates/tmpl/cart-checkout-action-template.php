<script type="text/html" id="tmpl-cart-checkout-action-template">
    <div class="row clearfix">
        <div class="col-md-12 text-right">
            <h3 class="mb-0 m-t-10 total" data-total="{{data.amount}}">
                Tổng cộng:
                <span>{{data.amount_label}}
                </span>
            </h3>
        </div>
    </div>
    <# if ( data.actionFound ) { #>
        <div class="row action clearfix">
            <div class="col-md-12 text-center">
                <button class="btn btn-primary btn-checkout">Thanh toán</button>
            </div>
        </div>
        <# } #>
</script>