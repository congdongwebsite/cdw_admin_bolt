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

        $requestId =  UUID::v4();
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

    public function delivery($amount, $extraData, $ipnUrl, $orderId, $orderInfo, $redirectUrl)
    {
        $url = $this->baseURL . '/v2/gateway/api/create';

        $requestId =  UUID::v4();
        $requestType = 'onDelivery';
        $autoCapture = True;
        $orderGroupId = '';
        $rawHash = "accessKey=" . APIMOMOACCESSKEY . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . APIMOMOPARTNERCODE . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, APIMOMOSECRETKEY);

        $item = [
            [
                "id" => "204727",
                "name" => "YOMOST Bac Ha&Viet Quat 170ml",
                "description" => "YOMOST Sua Chua Uong Bac Ha&Viet Quat 170ml/1 Hop",
                "category" => "beverage",
                "imageUrl" => "https://momo.vn/uploads/product1.jpg",
                "manufacturer" => "Vinamilk",
                "price" => 11000,
                "quantity" => 5,
                "unit" => "hộp",
                "totalPrice" => 55000,
                "taxAmount" => "200"
            ]
        ];
        $userInfo = array(
            "name" => "Nguyen Van A",
            "phoneNumber" => "0999888999",
            "email" => "email_add@domain.com",
        );
        $data = array(
            'partnerCode' => APIMOMOPARTNERCODE,
            'partnerName' => APIMOMOPARTNERNAME,
            'storeId' => APIMOMOSTOREID,
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'item' =>  $item,
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

        $requestId =  UUID::v4();
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
                case 9000: //Giao dịch đã được xác nhận thành công.
                case 8000: //Giao dịch đang ở trạng thái cần được người dùng xác nhận thanh toán lại.
                case 7000: //Giao dịch đang được xử lý.
                case 7002: //Giao dịch đang được xử lý bởi nhà cung cấp loại hình thanh toán.
                case 1000: //Giao dịch đã được khởi tạo, chờ người dùng xác nhận thanh toán.
                    $status = true;
                    $message = $data['message'];
                    break;
                case 11: //Truy cập bị từ chối.
                case 12: //Phiên bản API không được hỗ trợ cho yêu cầu này.
                case 13: //Xác thực doanh nghiệp thất bại.
                case 20: //Yêu cầu sai định dạng.
                case 22: //Số tiền giao dịch không hợp lệ.
                case 40: //RequestId bị trùng.
                case 41: //OrderId bị trùng.
                case 42: //OrderId không hợp lệ hoặc không được tìm thấy.
                case 43: //Yêu cầu bị từ chối vì xung đột trong quá trình xử lý giao dịch.
                case 44: //Giao dịch bị từ chối vì mã thanh toán không hợp lệ.
                case 1001: //Giao dịch thanh toán thất bại do tài khoản người dùng không đủ tiền.
                case 1002: //Giao dịch bị từ chối do nhà phát hành tài khoản thanh toán.
                case 1003: //Giao dịch bị đã bị hủy.
                case 1004: //Giao dịch thất bại do số tiền thanh toán vượt quá hạn mức thanh toán của người dùng.
                case 1005: //Giao dịch thất bại do url hoặc QR code đã hết hạn.
                case 1006: //Giao dịch thất bại do người dùng đã từ chối xác nhận thanh toán.
                case 1007: //Giao dịch bị từ chối vì tài khoản không tồn tại hoặc đang ở trạng thái ngưng hoạt động.
                case 1026: //Giao dịch bị hạn chế theo thể lệ chương trình khuyến mãi.
                case 1030: //Đơn hàng thanh toán thất bại do thông tin không hợp lệ.
                case 1080: //Giao dịch hoàn tiền bị từ chối. Giao dịch thanh toán ban đầu không được tìm thấy hoặc xử lý tại thời điểm này.
                case 1081: //Giao dịch hoàn tiền bị từ chối. Giao dịch thanh toán ban đầu có thể đã được hoàn.
                case 2001: //Giao dịch thất bại do sai thông tin liên kết.
                case 2007: //Giao dịch thất bại do liên kết hiện đang bị tạm khóa.
                case 3001: //Liên kết thất bại do người dùng từ chối xác nhận.
                case 3002: //Liên kết bị từ chối do không thỏa quy tắc liên kết.
                case 3003: //Hủy liên kết bị từ chối do đã vượt quá số lần hủy.
                case 3004: //Liên kết này không thể hủy do có giao dịch đang chờ xử lý.
                case 4001: //Giao dịch bị hạn chế do người dùng chưa hoàn tất xác thực tài khoản.
                case 4010: //Quá trình xác minh OTP thất bại.
                case 4011: //OTP chưa được gửi hoặc hết hạn.
                case 4100: //Giao dịch thất bại do người dùng không đăng nhập thành công.
                case 4015: //Quá trình xác minh 3DS thất bại.
                case 10: //Hệ thống đang được bảo trì.
                case 90: //Lỗi kết nối.
                case 99: //Lỗi không xác định.
                case 21: //Yêu cầu bị từ chối vì số tiền giao dịch không hợp lệ.
                case 45: //Trùng ItemId
                case 2019: //Yêu cầu bị từ chối vì orderGroupId không hợp lệ.
                case 1008: //Giao dịch thất bại vì số tiền vượt quá hạn mức nhận tiền cho phép của người dùng.
                case 1100: //Tài khoản Đối tác không đủ số dư thực hiện giao dịch. Vui lòng liên hệ MoMo để được hỗ trợ.
                case 1507: //Không tìm thấy Thẻ Ngân hàng hoặc Tài khoản ngân hàng hoặc BankCode không tồn tại. Vui lòng kiểm tra thông tin Ngân hàng và thực hiện lại.
                case 4003: //Chi hộ thất bại vì thông tin tài khoản người nhận không hợp lệ.
                    $status = false;
                    $message = $data['message'];
                    break;
                case 8003: //Yêu cầu khởi tạo chi hộ theo lô đã được thực hiện thành công.
                    $status = true;
                    $message = $data['message'];
                    break;
            }
        return (object) array(
            'status' => $status,
            'message' => $message,
            'data' => $data
        );
    }
    public function testdelivery()
    {
        $url = $this->baseURL . '/v2/gateway/api/create';

        $endpoint = 'https://test-payment.momo.vn/v2/gateway/api/create';
        $accessKey = 'F8BBA842ECF85';
        $secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
        $orderInfo = 'Thanh toán bằng MOMO';
        $partnerCode = 'MOMO';
        $redirectUrl = 'https://congdongtheme.com/momo-qr';
        $ipnUrl = 'https://congdongtheme.com/momo-qr';
        $amount = '50000';
        $orderId = time() . "";
        $requestId = time() . "";
        $extraData = '';
        $requestType = 'onDelivery';
        $partnerName = 'MoMo Payment';
        $storeId = 'Test Store';
        $orderGroupId = '';
        $autoCapture = True;
        $lang = 'vi';

        $requestId =  UUID::v4();
        $requestType = 'onDelivery';
        $autoCapture = True;
        $lang = 'vi';
        $orderGroupId = '';
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . APIMOMOPARTNERCODE . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash,  $secretKey);


        $item = array(
            "id" => "204727",
            "name" => "YOMOST Bac Ha&Viet Quat 170ml",
            "description" => "YOMOST Sua Chua Uong Bac Ha&Viet Quat 170ml/1 Hop",
            "category" => "beverage",
            "imageUrl" => "https://momo.vn/uploads/product1.jpg",
            "manufacturer" => "Vinamilk",
            "price" => 11000,
            "quantity" => 5,
            "unit" => "hộp",
            "totalPrice" => 55000,
            "taxAmount" => "200"
        );
        $userInfo = array(
            "name" => "Nguyen Van A",
            "phoneNumber" => "0999888999",
            "email" => "email_add@domain.com",
        );
        $data = array(
            'partnerCode' => APIMOMOPARTNERCODE,
            'partnerName' => "Test",
            'storeId' => 'MomoTestStore',
            'requestId' => $requestId,
            'amount' => $amount,
            'orderId' => $orderId,
            'item' =>  $item,
            'userInfo' =>  $userInfo,
            'orderInfo' => $orderInfo,
            'requestType' => $requestType,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'redirectUrl' => $redirectUrl,
            'autoCapture' => $autoCapture,
            'extraData' => $extraData,
            'orderGroupId' => $orderGroupId,
            'signature' => $signature
        );

        $response = $this->sendRequest($url, $data);
        var_dump($response);
        if ($response['status'] <= 200) {
            $data = json_decode($response['data'], true);
            return (object) array(
                'success' => true,
                'data' => $data
            );
        } elseif ($response['status'] > 200) {
            $error = $response['data'];

            return (object) array(
                'success' => false,
                'data' => $error
            );
        }
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
