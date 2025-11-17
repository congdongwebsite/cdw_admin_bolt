<script type="text/html" id="tmpl-info-domain-template">
    <table class="form-table-domain form-whois-domain-result" cellpadding="2">
        <tbody>
            <tr>
                <th>Ngày đăng ký</th>
                <td>{{data.creationDate}}</td>
            </tr>
            <tr>
                <th>Ngày hết hạn</th>
                <td>{{data.expirationDate}}</td>
            </tr>
            <tr>
                <th>Ngày cập nhật</th>
                <td>{{data.updatedDate}}</td>
            </tr>
            <tr>
                <th>Nhà đăng ký</th>
                <td>{{data.registrar}}</td>
            </tr>
            <tr>
                <th>Trạng thái</th>
                <td>{{data.status}}</td>
            </tr>
            <tr>
                <th>Registry Lock</th>
                <td>{{data.DNSSEC}}</td>
            </tr>
            <tr>
                <th>Người đăng ký</th>
                <td>{{data.registrantName}}</td>
            </tr>
            <tr>
                <th>Địa chỉ</th>
                <td>{{data.registrantStreet}}</td>
            </tr>
            <tr>
                <th>Điện thoại</th>
                <td>{{data.registrantPhone}}</td>
            </tr>
            <tr>
                <th>Email</th>
                <td>{{data.registrantEmail}}</td>
            </tr>
            <tr>
                <th>NAME SERVERS</th>
                <td>{{data.nameServer}}</td>
            </tr>
        </tbody>
    </table>
    <div class="info-domain-whois">
        <a class="btn btn-primary btn-show-raw-text" data-toggle="collapse" href="#showinfodomain" role="button" aria-expanded="false" aria-controls="collapseExample">
            Xem chi tiết
        </a>
        <div class="collapse" id="showinfodomain">
            <div class="card card-body">
                <small>{{data.rawtext}}</small>
            </div>
        </div>
    </div>
    <p>
</script>