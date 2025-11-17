<?php
defined('ABSPATH') || exit;
/*
 Template Name: QR
 */


/**
 * @filesource   qrcode.php
 * @created      18.11.2017
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2017 Smiley
 * @license      MIT
 */
//require_once('libs/chillerlan/autoload.php');

use chillerlan\QRCode\QRCode;

$qrcode = '';
if (isset($_POST['m_finder_dark'])) {
	try {

		$moduleValues = [
			// finder
			1536 => $_POST['m_finder_dark'],
			6    => $_POST['m_finder_light'],
			// alignment
			2560 => $_POST['m_alignment_dark'],
			10   => $_POST['m_alignment_light'],
			// timing
			3072 => $_POST['m_timing_dark'],
			12   => $_POST['m_timing_light'],
			// format
			3584 => $_POST['m_format_dark'],
			14   => $_POST['m_format_light'],
			// version
			4096 => $_POST['m_version_dark'],
			16   => $_POST['m_version_light'],
			// data
			1024 => $_POST['m_data_dark'],
			4    => $_POST['m_data_light'],
			// darkmodule
			512  => $_POST['m_darkmodule_dark'],
			// separator
			8    => $_POST['m_separator_light'],
			// quietzone
			18   => $_POST['m_quietzone_light'],
		];

		$moduleValues = array_map(function ($v) {
			if (preg_match('/[a-f\d]{6}/i', $v) === 1) {
				return in_array($_POST['output_type'], ['png', 'jpg', 'gif'])
					? array_map('hexdec', str_split($v, 2))
					: '#' . $v;
			}
			return null;
		}, $moduleValues);


		$ecc = in_array($_POST['ecc'], ['L', 'M', 'Q', 'H'], true) ? $_POST['ecc'] : 'L';

		$qro = new LogoOptions();

		$qro->version          = (int)$_POST['version'];
		$qro->eccLevel         = constant('chillerlan\\QRCode\\QRCode::ECC_' . $ecc);
		$qro->maskPattern      = (int)$_POST['maskpattern'];
		$qro->addQuietzone     = isset($_POST['quietzone']);
		$qro->quietzoneSize    = (int)$_POST['quietzonesize'];
		$qro->moduleValues     = $moduleValues;
		$qro->outputType       = $_POST['output_type'];
		$qro->scale            = (int)$_POST['scale'];
		$qro->imageTransparent = false;
		$qro->logoSpaceWidth   = 13;
		$qro->logoSpaceHeight  = 13;


		$qrcode = (new QRCode($qro))->render($_POST['inputstring']);
		if ($_POST['textqr'] != "") {
			$qrOutputInterface = new QRImageWithText($qro, (new QRCode($qro))->getMatrix($_POST['inputstring']));
			$qrcode = $qrOutputInterface->dump(null, $_POST['textqr']);
		}
		if (isset($_POST['urlqr']) && $_POST['urlqr'] == "on") {
			$qrOutputInterface = new QRImageWithLogo($qro, (new QRCode($qro))->getMatrix($_POST['inputstring']));
			$qrcode = $qrOutputInterface->dump(null, wp_get_original_image_path(171));
		}
		if (in_array($_POST['output_type'], ['png', 'jpg', 'gif'])) {
			$qrcode = '<img src="' . $qrcode . '" />';
		} elseif ($_POST['output_type'] === 'text') {
			$qrcode = '<pre style="font-size: 75%; line-height: 1;">' . $qrcode . '</pre>';
		} elseif ($_POST['output_type'] === 'json') {
			$qrcode = '<pre style="font-size: 75%; overflow-x: auto;">' . $qrcode . '</pre>';
		}

		send_response(['qrcode' => $qrcode]);
	}
	// Pokémon exception handler
	catch (\Exception $e) {

		header('HTTP/1.1 500 Internal Server Error');
		send_response(['error' => $e->getMessage()]);
	}
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
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Đây là ứng dụng tạo QR Code</title>
	<style>
		body {
			font-size: 20px;
			line-height: 1.4em;
			font-family: "Trebuchet MS", sans-serif;
			color: #000;
		}

		input,
		textarea,
		select {
			font-family: Consolas, "Liberation Mono", Courier, monospace;
			font-size: 75%;
			line-height: 1.25em;
			border: 1px solid #aaa;
		}

		input:focus,
		textarea:focus,
		select:focus {
			border: 1px solid #ccc;
		}

		label {
			cursor: pointer;
		}

		#qrcode-settings,
		div#qrcode-output {
			text-align: center;
		}

		div#qrcode-output>div {
			margin: 0;
			padding: 0;
			height: 3px;
		}

		div#qrcode-output>div>span {
			display: inline-block;
			width: 3px;
			height: 3px;
		}

		div#qrcode-output>div>span {
			background-color: lightgrey;
		}
	</style>
</head>

<body>

	<div id="qrcode-output"></div>
	<form id="qrcode-settings" method="POST">
		<button type="submit">generate</button>

		<label for="inputstring">Input String</label><br /><textarea name="inputstring" id="inputstring" cols="80" rows="3" autocomplete="off" spellcheck="false"></textarea><br />

		<label for="version">Version</label>
		<input id="version" name="version" class="options" type="number" min="1" max="40" value="5" placeholder="version" />

		<label for="maskpattern">Mask Pattern</label>
		<input id="maskpattern" name="maskpattern" class="options" type="number" min="-1" max="7" value="-1" placeholder="mask pattern" />

		<label for="ecc">ECC</label>
		<select class="options" id="ecc" name="ecc">
			<option value="L" selected="selected">L - 7%</option>
			<option value="M">M - 15%</option>
			<option value="Q">Q - 25%</option>
			<option value="H">H - 30%</option>
		</select>

		<br />

		<label for="quietzone">Quiet Zone
			<input id="quietzone" name="quietzone" class="options" type="checkbox" value="true" />
		</label>

		<label for="quietzonesize">size</label>
		<input id="quietzonesize" name="quietzonesize" class="options" type="number" min="0" max="100" value="4" placeholder="quiet zone" />

		<br />

		<label for="output_type">Output</label>
		<select class="options" id="output_type" name="output_type">
			<option value="html">Markup - HTML</option>
			<option value="svg" selected="selected">Markup - SVG</option>
			<option value="png">Image - png</option>
			<option value="jpg">Image - jpg</option>
			<option value="gif">Image - gif</option>
			<option value="text">String - text</option>
			<option value="json">String - json</option>
		</select>

		<label for="scale">scale</label>
		<input id="scale" name="scale" class="options" type="number" min="1" max="10" value="5" placeholder="scale" />
		<div>Text In QR</div>
		<label for="textqr">
			<input type="text" id="textqr" name="textqr" />
		</label>
		<div>Logo In QR</div>
		<label for="urlqr">
			<input type="checkbox" id="urlqr" name="urlqr" />
		</label>
		<div>Finder</div>
		<label for="m_finder_light">
			<input type="text" id="m_finder_light" name="m_finder_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_finder_dark">
			<input type="text" id="m_finder_dark" name="m_finder_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Alignment</div>
		<label for="m_alignment_light">
			<input type="text" id="m_alignment_light" name="m_alignment_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_alignment_dark">
			<input type="text" id="m_alignment_dark" name="m_alignment_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Timing</div>
		<label for="m_timing_light">
			<input type="text" id="m_timing_light" name="m_timing_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_timing_dark">
			<input type="text" id="m_timing_dark" name="m_timing_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Format</div>
		<label for="m_format_light">
			<input type="text" id="m_format_light" name="m_format_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_format_dark">
			<input type="text" id="m_format_dark" name="m_format_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Version</div>
		<label for="m_version_light">
			<input type="text" id="m_version_light" name="m_version_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_version_dark">
			<input type="text" id="m_version_dark" name="m_version_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Data</div>
		<label for="m_data_light">
			<input type="text" id="m_data_light" name="m_data_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_data_dark">
			<input type="text" id="m_data_dark" name="m_data_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Dark Module</div>
		<label for="m_darkmodule_light">
			<input disabled="disabled" type="text" id="m_darkmodule_light" class="options" value="" autocomplete="off" spellcheck="false" />
		</label>
		<label for="m_darkmodule_dark">
			<input type="text" id="m_darkmodule_dark" name="m_darkmodule_dark" class="jscolor options" value="000000" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>

		<div>Separator</div>
		<label for="m_separator_light">
			<input type="text" id="m_separator_light" name="m_separator_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_separator_dark">
			<input disabled="disabled" type="text" id="m_separator_dark" class="options" value="" autocomplete="off" spellcheck="false" />
		</label>

		<div>Quiet Zone</div>
		<label for="m_quietzone_light">
			<input type="text" id="m_quietzone_light" name="m_quietzone_light" class="jscolor options" value="ffffff" autocomplete="off" spellcheck="false" minlength="6" maxlength="6" />
		</label>
		<label for="m_quietzone_dark">
			<input disabled="disabled" type="text" id="m_quietzone_dark" class="options" value="" autocomplete="off" spellcheck="false" />
		</label>

		<br />
	</form>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/prototype/1.7.3/prototype.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jscolor/2.0.4/jscolor.js"></script>
	<script>
		((form, output, url) => {

			$(form).observe('submit', ev => {
				Event.stop(ev);

				new Ajax.Request(url, {
					method: 'post',
					parameters: ev.target.serialize(true),
					onUninitialized: $(output).update(),
					onLoading: $(output).update('<img width="255" src="https://24hstore.vn/upload_images/images/2019/11/14/anh-gif-3-min.gif" alt="">'),
					onFailure: response => $(output).update(response.responseJSON.error),
					onSuccess: response => $(output).update(response.responseJSON.qrcode),
				});

			});
		})('qrcode-settings', 'qrcode-output', '');
	</script>

</body>

</html>