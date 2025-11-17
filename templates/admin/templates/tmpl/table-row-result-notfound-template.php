<script type="text/html" id="tmpl-table-row-result-notfound-template">
    <tr>
        <td>
            <span class="badge badge-warning"> {{data.status}}</span>
        </td>
        <td class="project-title">
            <h6 class="pb-3"><a href="javascript:void(0);">{{data.domain}}</a></h6>
        </td>
        <td>
            {{data.price}}
        </td>
        <td>
            {{data.renewal_price}}
        </td>
        <td class="project-actions">
            <a href="javascript:void(0);" data-domain="{{data.domain}}" class="btn btn-outline-secondary badge-default"><i class="fa fa-warning-circle"> Không tìm thấy</i></a>
        </td>
    </tr>
</script>