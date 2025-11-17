<script type="text/html" id="tmpl-table-row-result-available-template">
    <tr>
        <td>
            <span class="badge badge-success">{{data.status}}</span>
        </td>
        <td class="project-title">
            <h6><a href="javascript:void(0);">{{data.domain}}</a></h6>
        </td>
        <td>
            {{data.price}}
        </td>
        <td>
            {{data.renewal_price}}
        </td>
        <td class="project-actions">
            <a href="javascript:void(0);" data-domain="{{data.domain}}" data-idd="{{data.id}}" class="btn btn-primary btn-choose"><i class="fa fa-shopping-cart"> Mua Ngay</i></a>
        </td>
    </tr>
</script>