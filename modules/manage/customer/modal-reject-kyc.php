<div class="modal fade" id="modal-reject-kyc" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lý do từ chối KYC</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="modal-reject-kyc-form" class="form">
                    <div class="form-group">
                        <label for="rejection-reason" class="control-label h6">Chọn lý do</label>
                        <select id="rejection-reason" name="rejection-reason" class="form-control">
                            <optgroup label="Giấy tờ không hợp lệ">
                                <option>Giấy tờ hết hạn sử dụng.</option>
                                <option>Giấy tờ không thuộc danh mục chấp nhận (ví dụ nộp bằng lái xe nhưng hệ thống chỉ nhận CMND/CCCD/Hộ chiếu).</option>
                                <option>Giấy tờ giả mạo hoặc có dấu hiệu chỉnh sửa.</option>
                            </optgroup>
                            <optgroup label="Ảnh chụp giấy tờ không đạt yêu cầu">
                                <option>Ảnh mờ, thiếu sáng, mất góc, bị lóa đèn flash.</option>
                                <option>Ảnh không rõ số CMND/CCCD/Hộ chiếu.</option>
                                <option>Chụp không đủ 2 mặt (đối với CMND/CCCD).</option>
                                <option>Giấy tờ bị che khuất, gấp mép hoặc dính sticker.</option>
                            </optgroup>
                            <optgroup label="Thông tin khai báo không trùng khớp">
                                <option>Thông tin cá nhân nhập trên form khác với thông tin trên giấy tờ.</option>
                                <option>Sai chính tả, sai số CMND/CCCD/Hộ chiếu.</option>
                                <option>Địa chỉ khai báo không khớp với giấy tờ xác minh địa chỉ.</option>
                            </optgroup>
                            <optgroup label="Nghi ngờ gian lận hoặc rủi ro cao">
                                <option>Hồ sơ trùng lặp với một user khác.</option>
                                <option>Phát hiện trong danh sách AML/PEP (chống rửa tiền, người có ảnh hưởng chính trị).</option>
                                <option>Hồ sơ có dấu hiệu giả mạo, không đáng tin cậy.</option>
                            </optgroup>
                            <optgroup label="Lỗi kỹ thuật khi upload">
                                <option>File bị hỏng, không mở được.</option>
                                <option>Upload sai định dạng (ví dụ file .zip thay vì ảnh .jpg/.png).</option>
                                <option>Dung lượng quá thấp hoặc quá cao khiến không thể xử lý.</option>
                            </optgroup>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="btn-send-rejection">Chọn lý do</button>
            </div>
        </div>
    </div>
</div>