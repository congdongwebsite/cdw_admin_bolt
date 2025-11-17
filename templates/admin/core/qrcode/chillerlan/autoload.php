<?php

require_once('Settings/SettingsContainerInterface.php');
require_once('Settings/SettingsContainerAbstract.php');
require_once('QRCode/QROptionsTrait.php');
require_once('QRCode/QROptions.php');

require_once('QRCode/Output/QROutputInterface.php');
require_once('QRCode/Output/QROutputAbstract.php');
require_once('QRCode/Output/QRGdImage.php');
require_once('QRCode/Output/QRImage.php');
require_once('QRCode/Output/QRMarkup.php');
require_once('QRCode/Output/QRMarkupSVG.php');
require_once('QRCode/Output/QRString.php');
require_once('QRCode/Output/QRFpdf.php');
require_once('QRCode/Output/QRImagick.php');
require_once('QRCode/Output/QREps.php');
require_once('QRCode/Output/QRMarkupHTML.php');

require_once('QRCode/QRCodeException.php');

require_once('QRCode/Output/QRCodeOutputException.php');

require_once('QRCode/Data/QRMatrix.php');
require_once('QRCode/Data/QRCodeDataException.php');
require_once('QRCode/Data/QRDataModeInterface.php');
require_once('QRCode/Data/QRDataModeAbstract.php');
require_once('QRCode/Data/Byte.php');
require_once('QRCode/Data/Number.php');
require_once('QRCode/Data/AlphaNum.php');
require_once('QRCode/Data/ECI.php');
require_once('QRCode/Data/Hanzi.php');
require_once('QRCode/Data/Kanji.php');
require_once('QRCode/Data/QRData.php');

require_once('QRCode/QRCode.php');

require_once('QRCode/Common/BitBuffer.php');
require_once('QRCode/Common/Version.php');
require_once('QRCode/Common/EccLevel.php');
require_once('QRCode/Common/ECICharset.php');
require_once('QRCode/Common/MaskPattern.php');
require_once('QRCode/Common/Mode.php');
require_once('QRCode/Common/ReedSolomonEncoder.php');
require_once('QRCode/Common/ReedSolomonDecoder.php');
require_once('QRCode/Common/GenericGFPoly.php');
require_once('QRCode/Common/GF256.php');

require_once('Customs/QRImageWithLogo.php');
require_once('Customs/QRImageWithText.php');
