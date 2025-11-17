<?php
/*
 Template Name: View Site Demo 
 */

if (!isset($_GET['id-mau'])) {
	echo "Mẫu không tồn tại, <a href=\"\\\" class=\"button-buy btn btn-danger\">quay lại trang chủ</a>";
	return;
}
	$id = $_GET['id-mau'];
$sub_domain = get_field('sub_domain', $id);
$name = get_field('name', $id);
$link = get_the_permalink($id);

?>
<!DOCTYPE html>

<!–[if IE 8]>
	<html <?php language_attributes(); ?> class="ie8">
	<![endif]–>

<!–[if !IE]>
	<html <?php language_attributes(); ?>>
	<![endif]–>

<head>

	<meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="http://gmgp.org/xfn/11" />
	<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
	<?php wp_head(); ?>

	<style>
		.header-view{
			height: 50px;
			z-index: 100;
		}
		.content-view{
			height: calc(100vh - 50px - 10px);
		}
		.reponsive i{
			font-size: 2rem;
		}
		
		.reponsive i{			
			transition: 1s ease-out;
		}
		.reponsive .mobile.landscape ,.reponsive .tablet.landscape{	
    		transform: rotate(270deg);
		}
		.content-view .content-iframe{
			box-sizing: content-box;
			border-radius: 25px;
		}
		.content-view .content-iframe{
			position: relative;
			transition: 1s ease-in;
		}
		.content-view .content-iframe::after{
			content: "";
			color: #cf2e2e;
			display: block;
			z-index: 99;
			position: absolute;
			background: white;
			border-radius: 50%;
		}

		.content-view .content-iframe::before{
			content: "";
			color: #cf2e2e;
			display: block;
			z-index: 99;
			position: absolute;
			background: white;
			border-radius: 50px;
		}
		.width-desktop .content-iframe{
			width: 100%;
			height: 100%;
		}
		.width-tablet .content-iframe{
			margin-top:10px;
			height: 1024px;
			width: 768px;
			max-width: calc(100% -  80px);
    		max-height: calc(100vh - 75px - 75px - 50px - 10px);
			border-top: 75px solid;
			border-bottom: 75px solid;
			border-left: 40px solid;
			border-right: 40px solid;
		}
		.content-view.width-tablet .content-iframe::after{
			width: 40px;
			height: 40px;
			bottom: -60px;
			left: calc(50% - 20px);
		}

		.content-view.width-tablet .content-iframe::before{
			width: 80px;
			height: 8px;
			top: -40px;
			left: calc(50% - 40px);
		}
		.width-tablet.landscape .content-iframe{
			margin-top:10px;
			width: 1024px;
			max-width: calc(100% -  150px);
    		max-height: calc(100vh - 40px - 40px - 50px - 10px);
			height: 768px;
			border-top: 40px solid;
			border-bottom: 40px solid;
			border-left: 75px solid;
			border-right: 75px solid;
		}
		
		.content-view.width-tablet.landscape .content-iframe::after{
			height: 40px;
			width: 40px;
			right: -60px;
			left: unset;
			bottom: calc(50% - 20px);
		}

		.content-view.width-tablet.landscape .content-iframe::before{
			height: 80px;
			width: 8px;
			left: -40px;
			top: calc(50% - 40px);
		}

		.width-mobile .content-iframe{
			margin-top:20px;
			height: 568px;
			width: 320px;
			max-width: calc(100% -  60px);
    		max-height: calc(100vh - 40px - 45px - 50px - 10px);
			border-top: 40px solid;
			border-bottom: 45px solid;
			border-left: 30px solid;
			border-right: 30px solid;
		}		
		.content-view.width-mobile .content-iframe::after{
			width: 30px;
			height: 30px;
			bottom: -40px;
			left: calc(50% - 15px);
		}

		.content-view.width-mobile .content-iframe::before{
			width: 80px;
			height: 8px;
			top: -20px;
			left: calc(50% - 40px);
		}
		.width-mobile.landscape .content-iframe{
			margin-top:20px;
			width: 568px;
			height: 320px;
			max-width: calc(100% -  95px);
    		max-height: calc(100vh - 30px - 30px - 50px - 10px);
			border-top: 30px solid;
			border-bottom: 30px solid;
			border-left: 40px solid;
			border-right: 45px solid;
		}
		.content-view.width-mobile.landscape .content-iframe::after{
			height: 30px;
			width: 30px;
			left: unset;
			bottom: calc(50% - 15px);
			right: -40px;

		}
		.content-view.width-mobile.landscape .content-iframe::before{
			height: 80px;
			width: 8px;
			left: -20px;
			top: calc(50% - 40px);
		}

		.header-view{
			-webkit-transition: width ease 0.3s;
			-moz-transition: width ease 0.3s;
			-o-transition: width ease 0.3s;
			transition: width ease 0.3s;
			width: 100%;
		} 
		.content-view.fullScreen-click{
			height: 100vh !important;
		} 
		
		.header-view.fullScreen-click{
			position: absolute;
			top: 0;
			left: 0px;
			z-index: 9;
			border: 1px solid #dee2e6!important;
			box-shadow: -1px 2px 5px #dee2e6;
			width: 60px;
    		background-color: white;
		}
		
		.header-view.fullScreen-click .site-name,
		.header-view.fullScreen-click .reponsive,
		.header-view.fullScreen-click .control .button-buy ,
		.header-view.fullScreen-click .control .share {
			display: none !important;
		}
		.text-button-buy{
			float: right;
		}
		.scrollbar{
			overflow-y: scroll;
		}
		.scrollbar-style body::-webkit-scrollbar {
			width: 12px;
			background-color: #F5F5F5;
		}
		/**  STYLE 1 */
		.scrollbar-style body::-webkit-scrollbar-thumb {
			border-radius: 10px;
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,.3);
			background-color: #555;
		}

		.scrollbar-style body::-webkit-scrollbar-track {
			-webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
			border-radius: 10px;
			background-color: #F5F5F5;
		}
	</style>
</head>
<body 
	<?php body_class('vh-100 w-100 view-site-demo'); ?> >
	<div class="header-view d-flex border-bottom flex-row justify-content-between p-2">		  			
		<div class="site-name text-primary d-flex flex-row justify-content-between align-items-center">
		 <a href="/">
			 <?php
				if (has_custom_logo()) {
					$custom_logo_id = get_theme_mod('custom_logo');
					$logo = wp_get_attachment_image_src($custom_logo_id, 'full');

					echo  "<img style=\"width: 110px;\" src=\"" . esc_url($logo[0]) . "\" alt=\"\">";
				}
				?>
			</a>	
			<a href="<?php echo $link; ?>" class="d-none d-sm-block ">
			 <span><?php echo $name; ?></span>
			</a>
		</div>
		<div class="reponsive text-success d-flex flex-nowrap">
			<i class="fa fa-desktop cursor-pointer px-2 px-md-3 desktop text-danger" aria-hidden="true"></i>
			<i class="fa fa-tablet cursor-pointer px-2 px-md-3 tablet " aria-hidden="true"></i>
			<i class="fa fa-mobile cursor-pointer px-2 px-md-3 mobile " aria-hidden="true"></i>
		</div>
		<div class="control  d-flex flex-row justify-content-between align-items-center">
			 <a href="<?php echo $link; ?>" class="button-buy btn btn-danger">
				 <span class="text-button-buy text-button-buy d-none d-sm-block float-left">Mua Website</span>	
				<i class="fa fa-shopping-cart mx-2" aria-hidden="true"></i>
			</a>
			<div class="share mx-3 text-success">
				 <a href="!?">
					 <i class="fa fa-share-alt" aria-hidden="true"></i>
					</a>
			</div>
			<div class="fullScreen cursor-pointer p-3 text-success">
				<i class="fa fa-expand expand" aria-hidden="true"></i>
				<i class="fa fa-compress compress d-none" aria-hidden="true"></i>
			</div>
		</div>
	</div>
	<div class="content-view w-100 text-center width-desktop ">
		<div class="content-iframe  mx-auto">
			<iframe id="ifr-content" name="ifr-content" class=" scrollbar scrollbar-style w-100 h-100" src="<?php echo $sub_domain; ?>" frameborder="0">
			</iframe>
		</div>	
	</div>
		
<?php wp_footer(); ?>
<script>
	jQuery(document).ready(function ($) {
		var contentView = $('.content-view');
		var headerView = $('.header-view ');
		var current = 1;
		$('.reponsive .desktop').on('click',(e)=>{
			contentView.removeClass(["width-tablet","width-mobile"]).addClass('width-desktop');
			if(current != 1 ) {
				current =1;
				reset_landscape(); 
				$(e.currentTarget).addClass('text-danger');
				return;
			}
			return;

			if($(e.currentTarget).hasClass('landscape') ) {
				contentView.removeClass('landscape');
				$(e.currentTarget).removeClass('landscape');
			}
			else{
				contentView.addClass('landscape');
				$(e.currentTarget).addClass('landscape');
			}

		});
		$('.reponsive .tablet').on('click',(e)=>{
			contentView.removeClass([ "width-desktop","width-mobile"]).addClass('width-tablet');
				
			if(current != 2 ) {
				current =2; 
				reset_landscape();
				$(e.currentTarget).addClass('text-danger');
				return;
			}
			if($(e.currentTarget).hasClass('landscape') ) {
				contentView.removeClass('landscape');
				$(e.currentTarget).removeClass('landscape');
			}
			else{
				contentView.addClass('landscape');
				$(e.currentTarget).addClass('landscape');
			}
		});
		$('.reponsive .mobile').on('click',(e)=>{
			
			contentView.removeClass(["width-tablet","width-desktop"]).addClass('width-mobile');
				
			if(current != 3 ) {
				current =3; 
				reset_landscape();
				$(e.currentTarget).addClass('text-danger');
				return;
			}
			if($(e.currentTarget).hasClass('landscape') ) {
				contentView.removeClass('landscape');
				$(e.currentTarget).removeClass('landscape');
			}
			else{
				contentView.addClass('landscape');
				$(e.currentTarget).addClass('landscape');
			}
		});
		function reset_landscape(){
			$('.reponsive .desktop').removeClass('landscape text-danger');
			$('.reponsive .tablet').removeClass('landscape text-danger');
			$('.reponsive .mobile').removeClass('landscape text-danger');
			contentView.removeClass('landscape');
		}

		$('.fullScreen').on('click',()=>{
			contentView.toggleClass('fullScreen-click');
			headerView.toggleClass('fullScreen-click');
			$('.fullScreen .expand ').toggleClass('d-none');
			$('.fullScreen .compress ').toggleClass('d-none');
		});
		
		function refreshFrame(){
			$('#ifr-content').attr('src', '<?php echo $link; ?>');
		}
	});

</script>
</body> <!–end body–>
</html> <!–end html –>