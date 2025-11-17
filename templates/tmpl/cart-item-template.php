<script type="text/html" id="tmpl-cart-item-template">
    <# if ( data.notFound ) { #>
        <tr>
            <td colspan="5" class="text-center"> Bạn chưa chọn dịch vụ nào</td>
         </tr>
        <# } #>
            <# if ( !data.notFound ) { #>
						    <tr id="id-{{data.id}}" class="basket-product" data-item="{{data.id}}" data-index="{{data.index}}">
                                <td class="remove"> <button><i class="fa fa-trash" aria-hidden="true"></i></button></td>
                                <td class="item">
                                    <div class="product-sku">
                                        {{data.service}}
                                    </div>
                                    <div class="product-details">
                                        <small>{{data.description}}</small>
                                    </div>
                                </td>
                                <td class="price" data-price="{{data.price}}"> 
                                     {{data.price_label}}
                                </td>
                                <td class="quantity">    <input type="number" min="0" class="quantity-field" value="{{data.quantity}}"><span>{{data.quantity_uom}}</span></td>
                                <td  class="subtotal"  data-amount="{{data.amount}}">
                                 {{data.amount_label}}
                                </td>
                            </tr>
                <# } #>
</script>
1
2
3
4
