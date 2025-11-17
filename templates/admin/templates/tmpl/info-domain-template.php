<script type="text/html" id="tmpl-info-domain-template">
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4 col-4">
            Ngày đăng ký
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.creationDate}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Ngày cập nhật
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.updatedDate}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Ngày hết hạn
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.expirationDate}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Nhà đăng ký
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.registrar}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Trạng thái
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.status}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Registry Lock
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.DNSSEC}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Người đăng ký
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.registrantName}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Địa chỉ
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.registrantStreet}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Số điện thoại
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.registrantPhone}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            Email
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.registrantEmail}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-4 col-md-4 col-sm-4  col-4">
            NAME SERVERS
        </div>
        <div class="col-lg-8 col-md-8 col-sm-8 col-8">
            {{data.nameServer}}
        </div>
    </div>
    <div class="row text-left">
        <div class="col-lg-12 col-md-12 col-sm-12">
            Raw text
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 mt-2">
            <p>
                <a class="text-primary" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample">
                    Mở rộng
                </a>
            </p>
            <div class="collapse" id="collapseExample">
                <div class="card card-body">
                    {{data.rawtext}}
                </div>
            </div>
        </div>
    </div>
</script>