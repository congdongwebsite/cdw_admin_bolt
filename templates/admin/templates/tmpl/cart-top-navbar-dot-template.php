<script type="text/html" id="tmpl-cart-top-navbar-dot-template">
    <a href="{{data.checkout.url}}" class="checkout " title="Giỏ Hàng">
        <i class="icon-basket pr-2"></i>
        Giỏ Hàng
        <# if ( data.has ) { #>
            <span class="cart-dot">{{data.count}}</span>
            <# } #>
    </a>
</script>