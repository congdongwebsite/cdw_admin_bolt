<?php
defined('ABSPATH') || exit;
class APIMomo
{
    private $baseURL;

    public function __construct($baseURL)
    {
        $this->baseURL = $baseURL;
    }

    public function collectionLink($amount, $extraData, $ipnUrl, $orderId, $orderInfo, $redirectUrl)
    {
        $url = $this->baseURL . '/v2/gateway/api/create';

        $requestId = uniqid();
        $requestType = 'payWithMethod';
        $autoCapture = True;
        $lang = 'vi';
        $orderGroupId = '';
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . APIMOMOPARTNERCODE . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $data = array(
            'partnerCode' => APIMOMOPARTNERCODE,
            'partnerName' => APIMOMOPARTNERNAME,
            'storeId' => APIMOMOSTOREID,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'requestType' => $requestType,
            'ipnUrl' => $ipnUrl,
            'lang' => $lang,
            'redirectUrl' => $redirectUrl,
            'autoCapture' => $autoCapture,
            'extraData' => $extraData,
            'orderGroupId' => $orderGroupId,
            'signature' => $signature
        );

        $response = $this->sendRequest($url, $data);
        return  $this->checkResultCode($response);
    }

    public function delivery($amount, $extraData, $ipnUrl, $orderId, $orderInfo, $redirectUrl, $items, $userInfo)
    {
        $url = $this->baseURL . '/v2/gateway/api/create';

        $requestId = uniqid();
        $requestType = 'onDelivery';
        $autoCapture = True;
        $orderGroupId = '';
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . APIMOMOPARTNERCODE . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $data = array(
            'partnerCode' => APIMOMOPARTNERCODE,
            'partnerName' => APIMOMOPARTNERNAME,
            'storeId' => APIMOMOSTOREID,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'items' =>  $items,
            'userInfo' =>  $userInfo,
            'orderInfo' => $orderInfo,
            'requestType' => $requestType,
            'ipnUrl' => $ipnUrl,
            'lang' => APIMOMOLANG,
            'redirectUrl' => $redirectUrl,
            'autoCapture' => $autoCapture,
            'extraData' => $extraData,
            'orderGroupId' => $orderGroupId,
            'signature' => $signature
        );
        error_log('[delivery] data: ' . json_encode($data));
        $response = $this->sendRequest($url, $data);
        return  $this->checkResultCode($response);
    }

    public function managePauseCancel($requestId, $orderId, $partnerClientId, $action = "pause")
    {
        $url = $this->baseURL . '/v2/gateway/api/subscription/manage';

        $token = "";
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&orderId=" . $orderId . "&partnerClientId=" . $partnerClientId . "&partnerCode=" . APIMOMOPARTNERCODE . "&requestId=" . $requestId . "&token=" . $token;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $data = array(
            "partnerCode" => APIMOMOPARTNERCODE,
            "requestId" => $requestId,
            "orderId" => $orderId,
            "partnerClientId" => $partnerClientId,
            "token" =>  $token,
            "lang" => APIMOMOLANG,
            "signature" => $signature,
            "action" => $action
        );

        $response = $this->sendRequest($url, $data);
        return  $this->checkResultCode($response);
    }
    public function manageReactivate($requestId, $orderId, $partnerClientId, $ipnUrl, $redirectUrl, $action = "reactivate")
    {
        $url = $this->baseURL . '/v2/gateway/api/subscription/manage';

        $token = 'A7WFmmnpn6TRX42Akh/iC5DdU5hhBT9LR5QSG6rJAl70hfEkkGUx2pTCai8s+M9KMVUcJ7m52iv74yhmeEjjN10TtEJoqITBIYBG2bqcTprhDijyhV4ePU7ytDNuLxzzIvGfTYyvbsEJ2jZTSf556yod12vhYqOJSFL/U2hVuxjUahf5Rnu5R/OLalg8QmlU6nQooEuNdzEXPMd6j9EaxOCiB2oM5/9QiTN0tCNSTIVvPtnlHu5mIbBHChcwfToIL4IAiD1nbrlDuBX//CZcrZj6hFqjvU31yb/DuG02c3aqWxbZKZ8csOwF9bL30m/yGr/0BQUWgunpDPrmCosf9A==';
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&orderId=" . $orderId . "&partnerClientId=" . $partnerClientId . "&partnerCode=" . APIMOMOPARTNERCODE . "&requestId=" . $requestId . "&token=" . $token;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $data = array(
            "partnerCode" => APIMOMOPARTNERCODE,
            "requestId" => $requestId,
            "orderId" => $orderId,
            "partnerClientId" => $partnerClientId,
            "token" =>  $token,
            "lang" => APIMOMOLANG,
            "signature" => $signature,
            "action" => $action,
            "ipnUrl" => $ipnUrl,
            "redirectUrl" => $redirectUrl
        );

        $response = $this->sendRequest($url, $data);
        return  $this->checkResultCode($response);
    }
    public function checkTransactionStatus($orderId)
    {
        $url = $this->baseURL . '/v2/gateway/api/query';

        $requestId = uniqid();
        $requestType = 'onDelivery';
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&orderId=" . $orderId . "&partnerCode=" . APIMOMOPARTNERCODE . "&requestId=" . $requestId;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $data = array(
            'partnerCode' => APIMOMOPARTNERCODE,
            'requestId' => $requestId,
            'orderId' => $orderId,
            'requestType' => $requestType,
            'lang' => APIMOMOLANG,
            'signature' => $signature
        );

        $response = $this->sendRequest($url, $data);
        return  $this->checkResultCode($response);
    }
    public function checkResultCode($response)
    {
        $status = false;
        if ($response['status'] <= 200) {
            $status = true;
        } elseif ($response['status'] > 200) {
            $status = false;
        }
        $message = '';
        $data =  json_decode($response['data'], true);
        if (isset($data['resultCode']))
            switch ((int)$data['resultCode']) {
                case 0: //Thành công.
                    $status = true;
                    $message = "Giao dịch thành công.";
                    break;
                case 10: //Hệ thống đang được bảo trì.
                    $status = false;
                    $message = "Hệ thống đang được bảo trì. Vui lòng thử lại sau khi bảo trì kết thúc.";
                    break;
                case 11: //Truy cập bị từ chối.
                    $status = false;
                    $message = "Truy cập bị từ chối. Lỗi cài đặt Merchant. Vui lòng kiểm tra cài đặt trong cổng M4B hoặc liên hệ MoMo để cấu hình.";
                    break;
                case 12: //Phiên bản API không được hỗ trợ cho yêu cầu này.
                    $status = false;
                    $message = "Phiên bản API không được hỗ trợ cho yêu cầu này. Vui lòng nâng cấp lên phiên bản cổng thanh toán mới nhất vì phiên bản hiện tại không còn được hỗ trợ.";
                    break;
                case 13: //Xác thực doanh nghiệp thất bại.
                    $status = false;
                    $message = "Xác thực Merchant thất bại. Vui lòng kiểm tra thông tin đăng nhập của bạn và thông tin được cung cấp trong cổng M4B.";
                    break;
                case 20: //Yêu cầu sai định dạng.
                    $status = false;
                    $message = "Yêu cầu sai định dạng. Vui lòng kiểm tra định dạng yêu cầu hoặc bất kỳ tham số nào bị thiếu.";
                    break;
                case 21: //Yêu cầu bị từ chối vì số tiền giao dịch không hợp lệ.
                    $status = false;
                    $message = "Yêu cầu bị từ chối do số tiền giao dịch không hợp lệ. Vui lòng kiểm tra xem số tiền có không liên quan không và thử lại yêu cầu.";
                    break;
                case 22: //Số tiền giao dịch nằm ngoài phạm vi.
                    $status = false;
                    $message = "Số tiền giao dịch nằm ngoài phạm vi. Vui lòng kiểm tra xem số tiền có nằm trong phạm vi cho phép của từng phương thức thanh toán không. Đối với loại yêu cầu thu tiền, hãy kiểm tra xem số tiền thu có khớp với số tiền được ủy quyền không.";
                    break;
                case 40: //RequestId bị trùng.
                    $status = false;
                    $message = "RequestId bị trùng. Vui lòng thử lại với RequestId khác.";
                    break;
                case 41: //OrderId bị trùng.
                    $status = false;
                    $message = "OrderId bị trùng. Vui lòng kiểm tra trạng thái giao dịch của OrderId hoặc thử lại với OrderId khác.";
                    break;
                case 42: //OrderId không hợp lệ hoặc không được tìm thấy.
                    $status = false;
                    $message = "OrderId không hợp lệ hoặc không được tìm thấy. Vui lòng thử lại với OrderId khác.";
                    break;
                case 43: //Yêu cầu bị từ chối vì xung đột trong quá trình xử lý giao dịch.
                    $status = false;
                    $message = "Yêu cầu bị từ chối do một giao dịch tương tự đang được xử lý. Trước khi thử lại, vui lòng kiểm tra xem có giao dịch tương tự nào khác đang được xử lý hạn chế yêu cầu này không.";
                    break;
                case 45: //Trùng ItemId
                    $status = false;
                    $message = "ItemId bị trùng. Vui lòng kiểm tra và thử lại yêu cầu với ItemId duy nhất.";
                    break;
                case 47: //Yêu cầu bị từ chối do thông tin không áp dụng trong tập dữ liệu có giá trị đã cho.
                    $status = false;
                    $message = "Yêu cầu bị từ chối do thông tin không áp dụng trong tập dữ liệu có giá trị đã cho. Vui lòng xem xét và thử lại với yêu cầu khác.";
                    break;
                case 98: //Mã QR này chưa được tạo thành công. Vui lòng thử lại sau.
                    $status = false;
                    $message = "Mã QR này chưa được tạo thành công. Vui lòng thử lại sau.";
                    break;
                case 99: //Lỗi không xác định.
                    $status = false;
                    $message = "Lỗi không xác định. Vui lòng liên hệ MoMo để biết thêm chi tiết.";
                    break;
                case 1000: //Giao dịch đã được khởi tạo, chờ người dùng xác nhận thanh toán.
                    $status = true;
                    $message = "Giao dịch đã được khởi tạo, chờ người dùng xác nhận.";
                    break;
                case 1001: //Giao dịch thanh toán thất bại do tài khoản người dùng không đủ tiền.
                    $status = false;
                    $message = "Giao dịch thất bại do không đủ tiền. Lỗi Merchant.";
                    break;
                case 1002: //Giao dịch bị từ chối do nhà phát hành tài khoản thanh toán.
                    $status = false;
                    $message = "Giao dịch bị từ chối bởi nhà phát hành của các phương thức thanh toán. Vui lòng chọn phương thức thanh toán khác.";
                    break;
                case 1003: //Giao dịch bị đã bị hủy.
                    $status = false;
                    $message = "Giao dịch bị hủy sau khi ủy quyền thành công. Giao dịch đã bị hủy bởi Merchant hoặc hệ thống MoMo do xử lý hết thời gian. Vui lòng đánh dấu giao dịch là thất bại.";
                    break;
                case 1004: //Giao dịch thất bại do số tiền thanh toán vượt quá hạn mức thanh toán của người dùng.
                    $status = false;
                    $message = "Giao dịch thất bại vì số tiền vượt quá giới hạn thanh toán hàng ngày/hàng tháng. Vui lòng đánh dấu giao dịch là thất bại và thử lại vào ngày khác.";
                    break;
                case 1005: //Giao dịch thất bại do url hoặc QR code đã hết hạn.
                    $status = false;
                    $message = "Giao dịch thất bại vì URL hoặc mã QR đã hết hạn. Vui lòng gửi yêu cầu thanh toán khác.";
                    break;
                case 1006: //Giao dịch thất bại do người dùng đã từ chối xác nhận thanh toán.
                    $status = false;
                    $message = "Giao dịch thất bại vì người dùng đã từ chối xác nhận thanh toán. Vui lòng gửi yêu cầu thanh toán khác.";
                    break;
                case 1007: //Giao dịch bị từ chối vì tài khoản không tồn tại hoặc đang ở trạng thái ngưng hoạt động.
                    $status = false;
                    $message = "Giao dịch bị từ chối do tài khoản người dùng không hoạt động hoặc không tồn tại. Vui lòng đảm bảo trạng thái tài khoản phải hoạt động/đã xác minh trước khi thử lại hoặc liên hệ MoMo để được hỗ trợ.";
                    break;
                case 1017: //Giao dịch bị hủy bởi Merchant.
                    $status = false;
                    $message = "Giao dịch bị hủy bởi Merchant. Lỗi Merchant.";
                    break;
                case 1026: //Giao dịch bị hạn chế theo thể lệ chương trình khuyến mãi.
                    $status = false;
                    $message = "Giao dịch bị hạn chế do quy tắc khuyến mãi. Vui lòng liên hệ MoMo để biết chi tiết hạn chế.";
                    break;
                case 1080: //Giao dịch hoàn tiền bị từ chối. Giao dịch thanh toán ban đầu không được tìm thấy hoặc xử lý tại thời điểm này.
                    $status = false;
                    $message = "Thử hoàn tiền thất bại trong quá trình xử lý. Vui lòng thử lại trong thời gian ngắn, tốt nhất là sau một giờ.";
                    break;
                case 1081: //Giao dịch hoàn tiền bị từ chối. Giao dịch thanh toán ban đầu có thể đã được hoàn.
                    $status = false;
                    $message = "Hoàn tiền bị từ chối. Giao dịch gốc có thể đã được hoàn tiền. Vui lòng kiểm tra xem giao dịch gốc đã được hoàn tiền chưa hoặc số tiền yêu cầu hoàn tiền của bạn vượt quá số tiền có thể hoàn lại.";
                    break;
                case 1088: //Giao dịch hoàn tiền bị từ chối. Giao dịch thanh toán gốc không đủ điều kiện để được hoàn tiền.
                    $status = false;
                    $message = "Hoàn tiền bị từ chối. Giao dịch thanh toán gốc không đủ điều kiện để được hoàn tiền. Vui lòng liên hệ MoMo để biết chi tiết hạn chế.";
                    break;
                case 2019: //Yêu cầu bị từ chối vì orderGroupId không hợp lệ.
                    $status = false;
                    $message = "Yêu cầu bị từ chối do orderGroupId không hợp lệ. Vui lòng liên hệ MoMo để biết chi tiết hạn chế.";
                    break;
                case 4001: //Giao dịch bị hạn chế do người dùng chưa hoàn tất xác thực tài khoản.
                    $status = false;
                    $message = "Giao dịch bị từ chối vì tài khoản người dùng đang bị hạn chế. Vui lòng liên hệ MoMo để biết chi tiết hạn chế của tài khoản người dùng này.";
                    break;
                case 4002: //Giao dịch bị từ chối vì tài khoản người dùng chưa được xác minh bởi C06.
                    $status = false;
                    $message = "Giao dịch bị từ chối vì tài khoản người dùng chưa được xác minh bởi C06. Người dùng phải cập nhật thông tin sinh trắc học qua NFC để được ủy quyền cho giao dịch.";
                    break;
                case 4100: //Giao dịch thất bại do người dùng không đăng nhập thành công.
                    $status = false;
                    $message = "Giao dịch thất bại vì người dùng không đăng nhập thành công. Lỗi người dùng.";
                    break;
                case 7000: //Giao dịch đang được xử lý.
                    $status = true;
                    $message = "Giao dịch đang được xử lý. Vui lòng đợi giao dịch được xử lý hoàn tất.";
                    break;
                case 7002: //Giao dịch đang được xử lý bởi nhà cung cấp loại hình thanh toán.
                    $status = true;
                    $message = "Giao dịch đang được xử lý bởi nhà cung cấp công cụ thanh toán đã chọn. Vui lòng đợi giao dịch được xử lý. Trạng thái giao dịch sẽ được thông báo sau khi xử lý xong.";
                    break;
                case 8000: //Giao dịch đang ở trạng thái cần được người dùng xác nhận thanh toán lại.
                    $status = true;
                    $message = "Giao dịch đang ở trạng thái cần được người dùng xác nhận thanh toán lại.";
                    break;
                case 9000: //Giao dịch đã được xác nhận thành công.
                    $status = true;
                    $message = "Giao dịch được ủy quyền thành công. Đối với thanh toán 1 bước, vui lòng đánh dấu giao dịch này là thành công. Đối với thanh toán 2 bước, vui lòng tiến hành yêu cầu thu tiền hoặc hủy. Đối với liên kết, vui lòng tiến hành yêu cầu mã thông báo định kỳ.";
                    break;
                default:
                    $status = false;
                    $message = $data['message'] ?? 'Lỗi không xác định.';
                    break;
            }
        return (object) array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
    }

    private function sendRequest($url, $params)
    {
        $curl = curl_init();
        $params = json_encode($params);

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 5,
            CURLOPT_CONNECTTIMEOUT => 5,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $params,
            CURLOPT_USERAGENT => "CDW/" . CDW_VERSION,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json; charset=UTF-8',
                'Content-Length: ' . strlen($params)
            )
        ));

        $response = curl_exec($curl);

        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        return array(
            'status' => $statusCode,
            'data' => $response
        );
    }
}
