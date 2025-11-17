<script type="text/html" id="tmpl-cart-summary-template">
        <td colspan="4" class="subtotal-title text-center sub-services">Tổng Tiền</td>
        <td class="subtotal-value final-value text-danger" id="basket-subtotal" data-total="{{data.sub_amount}}"><strong>{{data.sub_amount_label}}</strong></td>
   
    <# if ( data.promotion!=0 ) { #>
        <tr class="summary-promo">
         <td rowspan="3" class="promo-title">Khuyễn Mãi</td>   
         <td  class="promo-value final-value" id="basket-promo" style="display: block;">{{data.promotion}}</td>
        </tr>
     <# } #>
</script>