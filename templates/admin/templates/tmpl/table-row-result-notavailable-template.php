<script type="text/html" id="tmpl-table-row-result-notavailable-template">
    <tr>
        <td>
            <span class="badge badge-danger"> {{data.status}}</span>
        </td>
        <td class="project-title">
            <h6 class="pb-3"><a href="javascript:void(0);">{{data.domain}}</a></h6>
            <small><span class="text-secondary">Đã mua: {{data.creationDate}}</span> <br /> <span class="text-danger">Hết hạn: {{data.expirationDate}}</span></small>
        </td>
        <td>
            {{data.price}}
        </td>
        <td>
            {{data.renewal_price}}
        </td>
        <td class="project-actions">
            <a href="javascript:void(0);" data-domain="{{data.domain}}" class="btn btn-outline-secondary badge-default btn-info-domain"><i class="fa fa-info-circle"> Xem Thông Tin</i></a>
        </td>
    </tr>
</script>