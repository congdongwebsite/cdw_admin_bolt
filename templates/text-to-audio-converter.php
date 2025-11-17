<?php
defined('ABSPATH') || exit;
/*
 Template Name: Text to Audio Converter
 */

require_once('libs/fpt/fpt.php');

$msg = '';
$src = '';
if (isset($_POST['text'])) {
	$text = trim($_POST['text']);
	$speaker_id = trim($_POST['speaker_id']);
	//$quality = trim($_POST['quality']);
	$speed = trim($_POST['speed']);

	$data = array(
		'input' => $text,
		'speaker_id' => $speaker_id,
		//'quality' => $quality,
		'speed' => $speed
	);
	$data = texttoAudioConverter($data);
	
	if (isset($data->error)) {
		$msg = $data->error;
	}
	if (isset($data->url)) {
		$src = $data->url;
	}
}

?>


<html data-theme="light">

<head>
	<title>Đây là ứng dụng chuyển Text to Audio</title>
	<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
</head>

<body>
	<main class="container" data-theme="light">
		<span class="warning"><?php echo $msg; ?></span>
		<form action="" method="post">
			<label for="speaker_id">Giọng:</label>
			<!-- <select id="speaker_id" name="speaker_id">
				<option value="4" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 4 ? "selected" : "") : ""; ?>> Đàn ông miền Bắc </option>
				<option value="3" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 3 ? "selected" : "") : ""; ?>> Đàn ông miền Nam </option>
				<option value="2" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 2 ? "selected" : "") : ""; ?>> Phụ nữ miền Bắc </option>
				<option value="1" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 1 ? "selected" : "") : ""; ?>> Phụ nữ miền Nam </option>
			</select> -->
			<select id="speaker_id" name="speaker_id">
				<option value="banmai" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'banmai' ? "selected" : "") : ""; ?>>Nữ miền bắc</option>
				<option value="lannhi" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'lannhi' ? "selected" : "") : ""; ?>>Nữ miền nam</option>
				<option value="leminh" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'leminh' ? "selected" : "") : ""; ?>>Nam miền bắc</option>
				<option value="myan" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'myan' ? "selected" : "") : ""; ?>>Nữ miền trung</option>
				<option value="thuminh" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'thuminh' ? "selected" : "") : ""; ?>>Nữ miền bắc</option>
				<option value="giahuy" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'giahuy' ? "selected" : "") : ""; ?>>Nam miền trung</option>
				<option value="linhsan" <?php echo isset($_POST['speaker_id']) ?  ($_POST['speaker_id'] == 'linhsan' ? "selected" : "") : ""; ?>>Nữ miền nam</option>
			</select>
			<!-- <label for="quality">Chất lượng:</label>
			<select id="quality" name="quality">
				<option value="0" <?php echo isset($_POST['quality']) ?  ($_POST['quality'] == 0 ? "selected" : "") : ""; ?>>Chuẩn</option>
				<option value="1" <?php echo isset($_POST['quality']) ?  ($_POST['quality'] == 1 ? "selected" : "") : ""; ?>>Cao</option>
			</select> -->
			<label for="speed">Tốc độ:</label>
			<!-- <input type="number" id="speed" name="speed" min="0.8" max="1.2" step="0.1" value="<?php echo isset($_POST['speed']) ?  $_POST['speed'] : 0.8; ?>"> -->
			<input type="number" id="speed" name="speed" min="-3" max="3" step="1" value="<?php echo isset($_POST['speed']) ?  $_POST['speed'] : 0; ?>">
			<!-- Markup example 2: input is after label -->
			<label for="text">Đoạn text cần đọc</label>
			<textarea type="text" id="text" name="text" placeholder="Vui lòng nhập đoạn văn" maxlength="100" required rows="3"><?php echo isset($_POST['text']) ?  $_POST['text'] : trim(get_the_content()); ?></textarea>
			<small>1 tháng được 98.003 ký tự</small>

			<!-- Button -->
			<button type="submit">Đọc</button>
			<audio class="react-audio-player " controls="" id="" preload="metadata" src="<?php echo $src ?>" title="File Nghe" style="width: 100%; outline: none;">
				<p>Your browser does not support the <code>audio</code> element.</p>
			</audio>
		</form>
	</main>
</body>

</html>