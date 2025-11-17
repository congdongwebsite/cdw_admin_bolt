<script type="text/html" id="tmpl-cart-item-template">
    <# if ( data.notFound ) { #>
        <tr id="id-0" class="item">
            <td class="index"></td>
            <td class="service">Không có dịch vụ nào</td>
        </tr>
        <# } #>
            <# if ( !data.notFound ) { #>
                <tr id="id-{{data.id}}" class="item" data-item="{{data.id}}">
                    <td class="remove">
                        <a href="javascript:void(0);"><i class="fa fa-trash" aria-hidden="true"></i></a>
                    </td>
                    <td class="index">{{data.index}}</td>
                    <td class="service">{{data.service}}</td>
                    <td class="description hidden-sm-down">{{data.description}}</td>
                    <td class="quantity text-right">
                        <# if ( data.type === 'customer-email-change' ) { #>
                            <input type="number" name="quantity" style="width: 80px;" min="0" step="1" id="quantity" value="{{data.quantity}}" class="form-control text-right m-auto" readonly>
                        <# } else { #>
                            <input type="number" name="quantity" style="width: 80px;" min="0" step="1" id="quantity" value="{{data.quantity}}" class="form-control text-right m-auto">
                        <# } #>
                    </td>
                    <td class="price hidden-sm-down text-right" data-price="{{data.price}}">{{data.price_label}}</td>
                    <td class="amount text-right" data-amount="{{data.amount}}"><span>{{data.amount_label}}</span></td>
                </tr>
                <# } #>
</script>