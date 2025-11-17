<?php
defined('ABSPATH') || exit;
/*
 Template Name: Shopee
 */

require_once('libs/shopee/shopee.php');

$msg = '';
if (isset($_POST['type'])) {
	if ($_POST['link'] != "") {
		$pattern = "/([+-]?(?=\d|\d)(?:\d+)?(?:\.?\d*))(?:[eE]([+-]?\d+))?[0-9]*\.[0-9]+/i";
		preg_match($pattern, $_POST['link'], $matches);
		$pa = explode(".", $matches[0]);

		$shopid = trim($pa[0]);
		$itemid = trim($pa[1]);
	} else {
		$itemid = trim($_POST['itemid']);
		$shopid = trim($_POST['shopid']);
	}
	$type = trim($_POST['type']);
	$filter = trim($_POST['filter']);
	$flag = trim($_POST['flag']);
	$offset = trim($_POST['offset']);
	$limit = trim($_POST['limit']);

	$data = array(
		'type' => $type,
		'filter' => $filter,
		'flag' => $flag,
		'offset' => $offset,
		'limit' => $limit,
		'itemid' => $itemid,
		'shopid' => $shopid
	);
	$result = getComment($data);

	if (isset($result->error)) {
		$msg = $result->error;
	}
} else
if (isset($_GET['shopid'])) {
	$shopid = trim($_GET['shopid']);
	if (isset($_GET['categoryid']) && $_GET['categoryid'] != "") {
		$categoryid = trim($_GET['categoryid']);
	} else
		$categoryid = '';

	$data = array(
		'shop_categoryids' => $categoryid,
		'shopid' => $shopid,
		'filter_sold_out' => 1,
		'limit' => 1,
		'offset' => 0,
		'order' => 'desc', //asc
		'sort_by' => 'pop', //ctime,sales
		'use_case' => '4',
	);
	//var_dump($data);
	$result = getProduct($data);
	// foreach ($result->items as $key => $value) {

	// 	$data = array();
	// 	$data['name'] = $value->name;
	// 	$data['description'] = $value->name;
	// 	$data['sku'] = '';
	// 	$data['price'] = $value->price;
	// 	$data['regularPrice'] = $value->price;
	// 	$data['category_ids'] = array();
	// 	$data['images'] = array();
	// 	foreach ($value->images as $key => $image) {
	// 		$data['images'][] = 'https://cf.shopee.vn/file/' . $image;
	// 	}

	// 	createProduct($data);
	// }
	// foreach ($result->items as $key => $value) {
	// 	foreach ($value->images as $key => $image) {
	// 		//uploadMedia('https://cf.shopee.vn/file/' . $image);
	// 		getImageByUrl('https://cf.shopee.vn/file/' . $image,$value->name);
	// 	}
	// }
	if (isset($result->error)) {
		$msg = $result->error;
	}
}

?>


<html data-theme="light">

<head>
	<title>Đây là ứng dụng lấy comment sản phẩm từ Shopee</title>
	<link rel="stylesheet" href="https://unpkg.com/@picocss/pico@latest/css/pico.min.css">
</head>

<body>
	<main class="container" data-theme="light">
		<span class="warning"><?php echo $msg; ?></span>
		<?php

		if (!isset($_GET['shopid']) || isset($_POST['type'])) {
		?>
			<form action="" method="post">

				<div class="grid">
					<label for="type">Đánh giá:
						<select id="type" name="type">
							<option value="1" <?php echo isset($_POST['type']) ?  ($_POST['type'] == 1 ? "selected" : "") : ""; ?>>1</option>
							<option value="2" <?php echo isset($_POST['type']) ?  ($_POST['type'] == 2 ? "selected" : "") : ""; ?>>2</option>
							<option value="3" <?php echo isset($_POST['type']) ?  ($_POST['type'] == 3 ? "selected" : "") : ""; ?>>3</option>
							<option value="4" <?php echo isset($_POST['type']) ?  ($_POST['type'] == 4 ? "selected" : "") : ""; ?>>4</option>
							<option value="5" <?php echo isset($_POST['type']) ?  ($_POST['type'] == 5 ? "selected" : "") : "selected"; ?>>5</option>
						</select>
					</label>
					<label for="filter">filter:
						<input type="number" id="filter" name="filter" value="<?php echo isset($_POST['filter']) ?  $_POST['filter'] : 0; ?>">
					</label>
				</div>
				<div class="grid">
					<label for="flag">Lọc:
						<select id="flag" name="flag">
							<option value="1" <?php echo isset($_POST['flag']) ?  ($_POST['flag'] == 1 ? "selected" : "") : ""; ?>>Chỉ lấy bình luận</option>
							<option value="3" <?php echo isset($_POST['flag']) ?  ($_POST['flag'] == 3 ? "selected" : "") : ""; ?>>Có hình ảnh</option>
							<option value="3" <?php echo isset($_POST['flag']) ?  ($_POST['flag'] == 3 ? "selected" : "") : ""; ?>>Có video</option>
						</select>
					</label>
					<label for="offset">Bỏ qua:
						<input type="number" id="offset" name="offset" value="<?php echo isset($_POST['offset']) ?  $_POST['offset'] : 0; ?>">
					</label>
					<label for="limit">Giới hạn:
						<input type="number" id="limit" name="limit" value="<?php echo isset($_POST['limit']) ?  $_POST['limit'] : 30; ?>">
					</label>
				</div>

				<div class="grid">
					<label for="shopid">ID Cửa hàng:
						<input type="text" id="shopid" name="shopid" value="<?php echo isset($_POST['shopid']) ?  $shopid : "55771655"; ?>">

						<a onclick="xemsanpham()">Xem sản phẩm shop</a>
					</label>
					<label for="itemid">ID Sản phẩm:
						<input type="text" id="itemid" name="itemid" value="<?php echo isset($_POST['itemid']) ?  $itemid : "4638663735"; ?>">
					</label>
				</div>

				<div class="grid">
					<label for="link">Link:
						<input type="text" id="link" name="link" value="<?php echo isset($_POST['link']) ?  $_POST['link'] : ""; ?>">
						<small>Nếu có link id cửa hàng và id sản phẩm sẽ tự lấy</small>
					</label>
				</div>
				<!-- Button -->
				<button type="submit">GET</button>
			</form>
			<script>
				function xemsanpham() {
					let shopid = document.getElementById('shopid');
					console.log(shopid.value)
					window.open('/shopee/?shopid=' + shopid.value, '_blank');
				}
			</script>
			<style>
				div.comment {
					margin-top: 30px;
					padding-bottom: 30px;
					border-bottom: 1px solid red;
				}

				.comment-video {
					border: 2px solid red;
					overflow: hidden;
					display: block;
					width: 50px;
					margin-top: 5px;
				}
			</style>
			<div class="container">
				<?php

				if (isset($result)) {

				?>
					<div class="content">
						<div class="total">Tổng đánh giá: <?php echo $result->item_rating_summary->rating_total ?></div>
						<div class="total-rating">
							<span class="rating-1">1 sao(<?php echo $result->item_rating_summary->rating_count[0] ?>)-</span>
							<span class="rating-2">2 sao(<?php echo $result->item_rating_summary->rating_count[1] ?>)-</span>
							<span class="rating-3">3 sao(<?php echo $result->item_rating_summary->rating_count[2] ?>)-</span>
							<span class="rating-4">4 sao(<?php echo $result->item_rating_summary->rating_count[3] ?>)-</span>
							<span class="rating-5">5 sao(<?php echo $result->item_rating_summary->rating_count[4] ?>)</span>
						</div>
					</div>
					<?php
					$i = 1;
					foreach ($result->ratings as $key => $value) {

						// echo '<pre>';
						// var_dump($value);
						// echo '</pre>';
					?>
						<div class="content comment" id="id-<?php echo $value->cmtid; ?>">
							<div class="header">
								<div class="title" id="user-<?php echo $value->userid; ?>">
									<img class="shopee-avatar__img" src="https://cf.shopee.vn/file/<?php echo $value->author_portrait; ?>_tn" style="width: 40px;height: 40px; border-radius: 50%;">
									<a href="https://shopee.vn/shop/<?php echo $value->author_shopid; ?>" class="">
										<?php echo $i++; ?>/ <strong><?php echo $value->author_username; ?></strong>
									</a>
									<a href="/shopee/?shopid=<?php echo $value->author_shopid; ?>" class="">
										Xem sản phẩm
									</a>
								</div>
								<small class="time">
									<?php
									echo date("Y-m-d H:i", $value->mtime);
									?></small>
								<small class="rating">(<?php echo $value->rating; ?> sao)</small>
								<small class="rating">(<?php echo $value->like_count; ?> thích)</small>
							</div>
							<div class="content">
								<p><?php echo $value->comment; ?></p>
							</div>
							<div class="image">
								<?php
								if (isset($value->images))
									foreach ($value->images as $key => $image) {
								?>
									<img width="50px" height="50px" src="https://cf.shopee.vn/file/<?php echo $image; ?>" alt="" class="video">
								<?php
									}
								?>
								<?php
								if (isset($value->videos))
									foreach ($value->videos as $key => $video) {
								?>
									<a id="video-<?php echo $video->id; ?>" class="comment-video" target="_blank" href="<?php echo $video->url ?>" class="link-video">
										<img width="50px" height="50px" src="<?php echo $video->cover ?>" alt="Video" class="video">
									</a>
								<?php
									}
								?>
							</div>
						</div>
				<?php

					}
					// echo '<pre>';
					// var_dump($result->ratings);
					// echo '</pre>';
				}
				?>
			</div>
			<?php
		} else
		if (isset($_GET['shopid'])) {
			if (isset($result)) {

			?>
				<div class="content">
					<div class="total">Danh sách sản phẩm: <?php echo $result->total_count; ?></div>
				</div>
				<?php
				$i = 1;
				foreach ($result->items as $key => $value) {

					// echo '<pre>';
					// var_dump($value);
					// echo '</pre>';
				?>
					<div class="content comment" id="id-<?php echo $value->itemid; ?>">
						<div class="header">
							<div class="title" id="user-<?php echo $value->itemid; ?>">
								<img class="shopee-avatar__img" src="https://cf.shopee.vn/file/<?php echo $value->image; ?>_tn" style="width: 40px;height: 40px; border-radius: 50%;">
								<a href="https://shopee.vn/shop/<?php echo $value->itemid; ?>" class="">
									<?php echo $i++; ?>/ <strong><?php echo $value->name; ?></strong>
								</a>
								<a href="https://shopee.vn/shop/<?php echo $value->shopid; ?>" class="">
									Xem shop
								</a>
								<a href="/shopee/?shopid=<?php echo $value->shopid; ?>&categoryid=<?php echo $value->catid; ?>" class="">
									Xem danh mục
								</a>
							</div>
						</div>
						<small class="time">
							<?php
							echo number_format($value->price, 0, ',', '.');
							echo '(' . $value->currency . ')';
							?></small>
						<?php
						if ($value->price_min != $value->price_min) {
						?>
							<small class="rating">Min (<?php echo number_format($value->price_min, 0, ',', '.'); ?>)</small>
							<small class="rating">Max (<?php echo number_format($value->price_max, 0, ',', '.'); ?>)</small>
						<?php
						}
						?>
						<div class="image">
							<?php
							if (isset($value->images))
								foreach ($value->images as $key => $image) {
							?>
								<img width="50px" height="50px" src="https://cf.shopee.vn/file/<?php echo $image; ?>" alt="" class="video">
							<?php
								}
							?>
							<?php
							if (isset($value->videos))
								foreach ($value->videos as $key => $video) {
							?>
								<a id="video-<?php echo $video->id; ?>" class="comment-video" target="_blank" href="<?php echo $video->url ?>" class="link-video">
									<img width="50px" height="50px" src="<?php echo $video->cover ?>" alt="Video" class="video">
								</a>
							<?php
								}
							?>
						</div>
					</div>
		<?php

				}
				// echo '<pre>';
				// var_dump($result->ratings);
				// echo '</pre>';
			}
		}
		?>
	</main>
</body>

</html>