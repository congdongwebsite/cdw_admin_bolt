<?php
defined('ABSPATH') || exit;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Common\EccLevel;
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QROutputInterface;

class FunctionQRCode
{


    public function __construct()
    {
    }

    public function withLogo($data)
    {

        $options = new QROptions;

        $options->version             = QRCode::VERSION_AUTO;
        $options->outputBase64        = true;
        $options->scale               = 4;
        $options->imageTransparent    = true;
        $options->drawCircularModules = true;
        $options->outputType          = QROutputInterface::GDIMAGE_PNG;
        $options->circleRadius        = 0.45;
        $options->keepAsSquare        = [
            QRMatrix::M_FINDER,
            QRMatrix::M_FINDER_DOT,
        ];
        // ecc level H is required for logo space
        $options->eccLevel            = EccLevel::H;
        $options->addLogoSpace        = true;
        $options->logoSpaceWidth      = 13;
        $options->logoSpaceHeight     = 13;


        $qrcode = new QRCode($options);
        $qrcode->addByteSegment($data);

        $qrOutputInterface = new QRImageWithLogo($options, $qrcode->getQRMatrix());

        return $qrOutputInterface->dump(null, ADMIN_THEME_URL . '/assets/images/logo.png');
    }
}
