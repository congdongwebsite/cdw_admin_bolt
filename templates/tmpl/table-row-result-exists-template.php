<script type="text/html" id="tmpl-table-row-result-exists-template">
    <tr>
        <td>
            <span class="badge badge-danger"> {{data.status}}</span>
        </td>
        <td class="project-title">
            <h6 class="pb-3"><a href="javascript:void(0);">{{data.domain}}</a></h6>
            <small><span class="text-secondary">Ngày đặt: {{data.date}}</span></small>
        </td>
        <td>
            {{data.price}}
        </td>
        <td>
            {{data.renewal_price}}
        </td>
        <td class="project-actions">
            <a href="javascript:void(0);" data-domain="{{data.domain}}" class="btn btn-outline-secondary badge-default btn-info-domain"><i class="fa fa-info-circle"> Thông tin đặt</i></a>
        </td>
    </tr>
</script>