<?php
header('Content-type: text/html; charset=utf-8');


// require_once(get_template_directory() . '/templates/libs/chillerlan/autoload.php');

use chillerlan\QRCode\QRCode;

function execPostRequest($url, $data)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt(
        $ch,
        CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data)
        )
    );
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    $jsonResult = json_decode($result, true);
    //var_dump($jsonResult);

    // if ($jsonResult['payUrl'] != null)
    //     header('Location: ' . $jsonResult['payUrl']);

    return $jsonResult;
}


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

if (!empty($_POST)) {

    $accessKey = $_POST["accessKey"];
    $secretKey = $_POST["secretKey"];
    $orderInfo = $_POST["orderInfo"];
    $partnerCode = $_POST["partnerCode"];
    $redirectUrl = $_POST["redirectUrl"];
    $amount = $_POST["amount"];
    $orderId = $_POST["orderId"];
    //$orderGroupId = $_POST["orderGroupId"];

    $requestId = time() . '';
    $extraData = "";
    //before sign HMAC SHA256 signature
    $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
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
        'partnerCode' => $partnerCode,
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
    $result = execPostRequest($endpoint, json_encode($data));
    if (isset($result['qrCodeUrl']) && $result['qrCodeUrl'] != null)
        send_response(['qrcode' => $result['qrCodeUrl'], 'orderId' => time() . "", 'linkoutput' => "<a href=\"" . $result['payUrl'] . "\">" . $result['payUrl'] . "</a>"]);
    else {
        header('HTTP/1.1 500 Internal Server Error');
        send_response(['error' => "Error"]);
    }
}

function qrimage($data_qr)
{
    $moduleValues = [
        1536 => "000000",
        6    => "FFFFFF",
        // alignment
        2560 => "000000",
        10   => "FFFFFF",
        // timing
        3072 => "000000",
        12   => "FFFFFF",
        // format
        3584 => "000000",
        14   => "FFFFFF",
        // version
        4096 => "000000",
        16   => "FFFFFF",
        // data
        1024 => "000000",
        4    => "FFFFFF",
        // darkmodule
        512  => "000000",
        // separator
        8    => "FFFFFF",
        // quietzone
        18   => "FFFFFF",
    ];

    $moduleValues = array_map(function ($v) {
        if (preg_match('/[a-f\d]{6}/i', $v) === 1) {
            array_map('hexdec', str_split($v, 2));
        }
        return null;
    }, $moduleValues);

    $qro = new LogoOptions();

    $qro->version          = 12;
    $qro->eccLevel         = constant('chillerlan\\QRCode\\QRCode::ECC_H');
    $qro->maskPattern      = -1;
    $qro->addQuietzone     = true;
    $qro->quietzoneSize    = 2;
    $qro->moduleValues     = $moduleValues;
    $qro->outputType       = 'jpg';
    $qro->scale            = 3;
    $qro->imageTransparent = false;
    $qro->logoSpaceWidth   = 13;
    $qro->logoSpaceHeight  = 13;


    $qrOutputInterface = new QRImageWithLogo($qro, (new QRCode($qro))->getMatrix($data_qr));
    $qrcode = $qrOutputInterface->dump(null, wp_get_original_image_path(171));

    $qrcode = '<img src="' . $qrcode . '" />';


    return $qrcode;
}

/**
 * @param array $response
 */
function send_response(array $response)
{
    header('Content-type: application/json;charset=utf-8;');
    echo json_encode($response);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Đây là ứng dụng tạo thanh toán bằng QR MOMO</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />
    <!-- CSS -->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Collectiion Link/Thanh toán bằng Collection Link</h3>
                    </div>
                    <div class="panel-body">
                        <form id="momo-qr" method="POST">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">PartnerCode</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="partnerCode" value="<?php echo $partnerCode; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">AccessKey</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="accessKey" value="<?php echo $accessKey; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">SecretKey</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="secretKey" value="<?php echo $secretKey; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">OrderId</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' id="orderId" name="orderId" value="<?php echo $orderId; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">ExtraData</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' type="text" name="extraData" value="<?php echo $extraData ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">OrderInfo</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="orderInfo" value="<?php echo $orderInfo; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">Amount</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="amount" value="<?php echo $amount; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">RedirectUrl</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="redirectUrl" value="<?php echo $redirectUrl; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div id="qrcode-output"></div>
                                </div>
                                <div class="col-md-4">
                                    <div id="link-output"></div>
                                </div>
                            </div>
                            <p>
                            <div style="margin-top: 1em;">
                                <button type="submit" class="btn btn-primary btn-block">Start MoMo payment....</button>
                            </div>
                            </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prototype/1.7.3/prototype.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.0.4/jscolor.js"></script>
    <script>
        ((form, output, url, orderId, linkoutput) => {

            $(form).observe('submit', ev => {
                Event.stop(ev);

                new Ajax.Request(url, {
                    method: 'post',
                    parameters: ev.target.serialize(true),
                    onUninitialized: () => {
                        $(output).update();
                    },
                    onLoading: $(output).update('<img width="255" src="https://24hstore.vn/upload_images/images/2019/11/14/anh-gif-3-min.gif" alt="">'),
                    onFailure: response => $(output).update(response.responseJSON.error),
                    onSuccess: (response) => {
                        $(output).update(response.responseJSON.qrcode);
                        $(orderId).setValue(response.responseJSON.orderId);
                        $(linkoutput).update(response.responseJSON.linkoutput);
                    },
                });

            });
        })('momo-qr', 'qrcode-output', '', 'orderId', 'link-output');
    </script>

</body>

</html>