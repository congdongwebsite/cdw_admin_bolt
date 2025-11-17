<?php
defined('ABSPATH') || exit;
/*
Template Name: Getlink
*/
// Các trang mã hoá link online

// https://encode-decode.com/bin2hex-decode-online/
// https://toolk.ist/ext/bin2hex
// http://php.fnlist.com/string/bin2hex
$redirect_to = !empty($_GET['url'])  ? trim(strip_tags(stripslashes($_GET['url'])))  : '';
$wait_time = 10000;                                                             // Đặt thơi gian nhay giây theo số giây bạn muốn 10000 tương ứng với 10s
$wait_seconds = $wait_time / 1000;
add_action('wp_head', 'redirect_to_no_index', 99);
function redirect_to_no_index()
{
?>
  <!-- tắt index trang này -->
  <meta name="robots" content="noindex, follow">
<?php
}
add_action('wp_head', 'redirect_to_external_link');
function redirect_to_external_link()
{
  global $redirect_to, $wait_seconds, $wait_time;

  if (empty($redirect_to) || empty($wait_time)) {
    return;
  }

  if (filter_var($redirect_to, FILTER_VALIDATE_URL)) {
    $chuyenluon = $redirect_to;
  } else {
    $chuyenluon = hex2bin($redirect_to);
  }
  
?>
  <script>
    var redirect = window.setTimeout(function() {
        window.open('<?php echo $chuyenluon; ?>');
        document.getElementById('nhanlk').href = '<?php echo $chuyenluon; ?>';
        document.getElementById('nhanlk').style.display = 'block';
        document.getElementById('timer').style.display = 'none';
      },
      <?php echo $wait_time; ?>
    );
  </script>
  <noscript>
    <meta http-equiv="refresh" content="<?php echo $wait_seconds; ?>;url=<?php esc_attr_e($redirect_to); ?>">
  </noscript>
<?php }

get_header(); ?>

<div class="ttch">CHUYỂN HƯỚNG</div>
<div class="chuyen-huong">
  <div>
    <?php if (!empty($redirect_to)) {
    ?>
      <div class="thoigian-chuyen" id="timer"></div>
      <div>
        <a href="" target="_blank" id="nhanlk" style="display:none">CHUYỂN ĐẾN LIÊN KẾT</a><br /> <!-- Xoá phần này nếu bạn không muốn hiển thị nút chuyển hướng sau khi kết thúc S DELETE 2 -->
      </div>
      <div id="myProgress">
        <div id="myBar">10%</div>
      </div>
      <div class="noidungchuyen">
        <!-- Nội dung của trang -->
        Quá trình chuyển hướng sẽ kết thúc sau khi số giây, và thanh tải kết thúc. Mặc dù điều này sẽ khiến bạn cảm thấy khó chịu khi chờ đợi, những hãy cố kiên nhẫn vì quá trình này phải mất một thời gian để hoàn thành.<br />
        Chúng tôi luôn cố gắng mang đến những trãi nghiệm người dùng ưu việt nhất "liên kết không bị hỏng, liên kết được kiểm duyệt an toàn không chứa mã đọc".<br />
        <div style="margin-top:10px;margin-bottom:10px;">
          <!-- vi trí đặt quảng cáo Adsense hoặc Banner -->
          <div style="background: #d7e7ff;border:1px solid #999;text-align:center;border: 1px dashed #5facdd;padding:40px;color:#999">Ads / Banner</div>
          <!-- Kết thúc ADS -->
        </div>
        <button style="border:none;background:#333;padding:10px;border-radius:30px;color:#fff;font-size:15px;" onclick="window.open('/')">Getlink</button> <!-- Bạn có thể xoá nút này nếu không muốn hiển thị, dùng để chuyển đến trang bạn muốn DELETE -->
        <!-- Kết thúc nội dung của trang -->
      </div>
    <?php
    } else {
      _e('Liên kết này bị lỗi hoặc không tồn tại!');
    }
    ?>
  </div>
</div>


<script>
  document.getElementById('timer').innerHTML = <?php echo $wait_seconds; ?>;
  var timer = <?php echo $wait_seconds; ?>;
  var interval = setInterval(function() {
      var seconds = timer;
      if (seconds > 0) {
        --seconds;
        document.getElementById('timer').innerHTML = seconds + "";
        timer = seconds;
      } else {}
    },
    1000);

  var i = 0;
  window.onload = function move() {
    if (i == 0) {
      i = 1;
      var elem = document.getElementById("myBar");
      var width = 0;
      var id = setInterval(frame, <?php echo $wait_seconds * 9.6; ?>);

      function frame() {
        if (width >= 100) {
          clearInterval(id);
          i = 0;
        } else {
          width++;
          elem.style.width = width + "%";
          elem.innerHTML = width + "%";
        }
      }
    }
  }
</script>

<style>
  .ttch {
    background: #00b360;
    width: 230px;
    margin: 0 auto;
    padding: 10px;
    color: #fff;
    font-weight: bold;
    font-family: Arial;
    text-align: center;
    border-radius: 10px;
    font-size: 24px;
  }

  .chuyen-huong {
    max-width: 1400px;
    margin-top: -10px;
    margin-bottom: 50px;
    margin-left: auto;
    margin-right: auto;
    text-ailgn: center;
    text-align: center;
    font-family: Arial;
    background: #f1f1f1;
    width: 90%;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #999;
  }

  .chuyen-huong a {
    color: #fff;
    display: block;
    background: #333;
    padding: 10px;
    border-radius: 10px;
    box-shadow: 0px 1px 7px #444;
    text-decoration: none;
  }

  .thoigian-chuyen {
    color: #0c0;
    font-size: 50px;
  }

  #myProgress {
    max-width: 100%;
    background-color: #ddd;
    border-radius: 5px;
    margin: 0 auto;
  }

  #myBar {
    width: 1%;
    height: 25px;
    background: linear-gradient(90deg, rgba(0, 201, 189, 1) 0%, rgba(0, 204, 0, 1) 100%);
    border-radius: 5px;
  }

  #myBar {
    font-size: 14px;
    color: #fff
  }

  .noidungchuyen {
    margin-top: 20px;
    background: #fff;
    padding: 10px;
    border-radius: 5px;
    border: 1px solid #ccc;
  }
</style>
<?php get_footer(); ?>