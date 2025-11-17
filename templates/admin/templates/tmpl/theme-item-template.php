<script type="text/html" id="tmpl-theme-item-template">
    <div class="col-lg-3 col-md-6 col-sm-12 m-b-30 theme-item theme-web-cdw">
        <div class="col-inner" data-type="{{data.id_type_list}}">
            <a href="#"> <img class="img-fluid img-thumbnail" src="{{data.image}}" alt="{{data.name}}"> </a>
            <div class="frame-bottom-web text-center p-2">
                <a href="{{data.url}}">
                    <h5>{{data.name}}</h3>
                </a>
                <p class="text-danger pt-2 d-block">{{data.price}}</p>
                <div class="botton-link d-flex justify-content-center">
                    <a href="{{data.url_demo}}" target="_blank" data-idt="{{data.id}}" class="btn btn-success"><span><i class="icon-eye"></i> Xem Demo</a>
                    <a href="javascript:void(0)" data-idt="{{data.id}}" class="btn btn-info ml-2 btn-choose"><i class="icon-basket"></i> Mua Ngay</a>
                </div>
            </div>
        </div>
    </div>
</script>