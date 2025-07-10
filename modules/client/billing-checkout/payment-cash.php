<div class="card profile-header">
    <div class="body">
        <div class="text-center">
            <div>
                <h4 class="mb-0"><strong>CỘNG ĐỒNG</strong> WEBSITE</h4>
                <span>MB BANK</span>
            </div>
            <div class="m-b-25 m-t-25">
                <img src="/wp-content/uploads/2024/06/qrcode-congdongweb-Cong-Dong-Web.jpg" alt="Thanh toán bằng MB BANK QR" class="w-100">
            </div>
            <hr>
            <div class="row text-left">
                <div class="col-12">
                    <p class="mb-0"><strong>Nội dung chuyển khoản: </strong> Thanh toán hóa đơn <?php echo $id == -1 ? "" : get_post_meta($id, 'code', true); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>