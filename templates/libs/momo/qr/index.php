<?php
header('Content-type: text/html; charset=utf-8');

function execPostRequest($url, $data)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,$url."?".http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    //execute post
    $result = curl_exec($ch);
    //close connection
    curl_close($ch);
    var_dump(urldecode($url."?".http_build_query($data)));
    $jsonResult = json_decode($result, true);
    return $result;
}

$endpoint = 'https://test-payment.momo.vn/pay/store';
$accessKey = 'F8BBA842ECF85';
$secretKey = 'K951B6PE1waDMi640xX08PD3vg6EkVlz';
$orderInfo = 'Thanh toán bằng MOMO';
$partnerCode = 'MOMOIQA420180417';
$redirectUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
$ipnUrl = 'https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b';
$amount = '50000';
$orderId = time() . "";
$requestId = time() . "";
$extraData = '';
$requestType = 'payWithMethod';
$partnerName = 'MoMo Payment';
$storeId = 'storeid01';
$billId = '123';
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
    $orderGroupId = $_POST["orderGroupId"];
    $billId = $_POST["billId"];

    $requestId = time() . '';
    $extraData = "";
    //before sign HMAC SHA256 signature
    
    $rawHash = "storeSlug=" . $partnerCode . "-" . $storeId . "&amount=" . $amount . "&billId=" . $billId . "";
    var_dump($rawHash);
    $signature = hash_hmac("sha256", $rawHash, $secretKey);
    $data = array(
        'a' => $amount,
        'b' => $billId,
        's' => $signature
    );
    $result = execPostRequest($endpoint."/" . $partnerCode . "-" . $storeId, $data);
    $jsonResult = json_decode($result, true);  // decode json
    //Just a example, please check more in there
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>MoMo Sandbox</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.4.1/css/bootstrap.min.css" />
    <link rel="stylesheet" href="./statics/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css" />
    <!-- CSS -->
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Collectiion Link/Thanh toán bằng Collection Link. More detail at <a style="color: #2f80d1" href="https://developers.momo.vn/v3/docs/payment/api/collection-link/">here</a></h3>
                    </div>
                    <div class="panel-body">
                        <form class="" method="POST" enctype="application/x-www-form-urlencoded" action="">
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
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">OrderGroupId</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="orderGroupId" value="<?php echo $orderGroupId; ?>" class="form-control" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="fxRate" class="col-form-label">OrderId</label>
                                        <div class='input-group date' id='fxRate'>
                                            <input type='text' name="orderId" value="<?php echo $orderId; ?>" class="form-control" />
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
                                    <label for="fxRate" class="col-form-label">Bill Id</label>
                                    <div class='input-group date' id='fxRate'>
                                        <input type='text' name="billId" value="<?php echo $billId; ?>" class="form-control" />
                                    </div>
                                </div>
                            </div>
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
    </div>

    <script type="text/javascript" src="./statics/jquery/dist/jquery.min.js"></script>
    <script type="text/javascript" src="./statics/moment/min/moment.min.js"></script>

</html>