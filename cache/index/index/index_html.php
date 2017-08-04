	<!DOCTYPE html>
<!--[if IE 7]>					<html class="ie7 no-js" lang="en">     <![endif]-->
<!--[if lte IE 8]>              <html class="ie8 no-js" lang="en">     <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->  <!--<![endif]-->
<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	
	<title>My Blog | Home</title>
	
	<meta name="description" content="" />
	<meta name="author" content="" />	
	
	<link rel="icon" type="image/png" href="public/index/images/favicon.png" />
	
	<link rel="stylesheet" type="text/css" href="public/index/css/style.css" />
	<link rel="stylesheet" type="text/css" href="public/index/sliders/flexslider/flexslider.css" />
	<link rel="stylesheet" type="text/css" href="public/index/fancybox/jquery.fancybox.css" />

	<!-- HTML5 Shiv -->

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>
<body class="style-1">
	<div class="wrap">
	<!-- - - - - - - - - - - - - - Header - - - - - - - - - - - - - - - - -->	
	<?php include 'cache/index/index/header_html.php';?>
	<!-- - - - - - - - - - - - - - end Header - - - - - - - - - - - - - - - - -->	
	<!-- - - - - - - - - - - - - - - Container - - - - - - - - - - - - - - - - -->	
	
	<section class="container sbr clearfix">
	
		<!-- - - - - - - - - - - Slider - - - - - - - - - - - - - -->	

		<div id="slider" class="flexslider clearfix">

			<ul class="">
				<li>
					<img src="public/index/images/sliders/slide-1.jpg" alt="" />
					<div class="caption">
						<div class="caption-entry">
							<div class="caption-title"><h2>Welcome</h2></div>
							<p>
								In the bustling city, here is your warm harbor, put down a day's work, making a cup of tea light tea, here you can drive away your day tired...
							</p>
						</div><!--/ .caption-entry-->
					</div><!--/ .caption-->
				</li>
			
			</ul><!--/ .slides-->

		</div><!--/ #slider-->

		<!-- - - - - - - - - - - end Slider - - - - - - - - - - - - - -->
	
        
		<!-- - - - - - - - - - - - - - - Content - - - - - - - - - - - - - - - - -->		
		
		<section id="content">

			<?php if(!empty($data)):?>
			<?php foreach($data as $value): ?>
			<article class="post-item clearfix">
				
				<a href="index.php?c=index&a=blog_details&id=<?=$value['id']; ?>">
					<h3 class="title"><?=$value['title']; ?></h3><!--/ .title -->
				</a>
				
				<section class="post-meta clearfix">
					
					<div class="post-date"><a href="#"><?php echo date('Y/m/d',$value['addtime']);?></a></div><!--/ .post-date-->
					<div class="post-tags">
						<a href="#"></a>
					</div><!--/ .post-tags-->
					<div class="post-comments"><a href="#"><?=$value['replycount']; ?>Comments</a></div><!--/ .post-comments-->
					
				</section><!--/ .post-meta-->
				
				<a class="single-image" href="index.php?c=index&a=blog_details&id=<?=$value['id']; ?>">
					<img class="custom-frame" alt="" src="<?=$value['icon']; ?>" />
				</a>
				<p>
				</p>
				
				<a href="index.php?c=index&a=blog_details&id=<?=$value['id']; ?>" class="button gray">Read More &rarr;</a>
				
			</article><!--/ .post-item-->
			<?php endforeach;?>
			<?php endif;?>
			
			
		</section><!--/ #content-->
		
		<!-- - - - - - - - - - - - - - end Content - - - - - - - - - - - - - - - - -->	
		
		
		<!-- - - - - - - - - - - - - - - Sidebar - - - - - - - - - - - - - - - - -->	
		
		<aside id="sidebar">
			
			<div class="widget-container eventsListWidget">

				<h3 class="widget-title">Upcoming Events</h3>

				<ul>
					
					<li>
						<a href="#"><h6>Suspendisse Potenti Consectetur</h6></a>
						<span class="widget-date">June 15, 2013</span>
					
					</li>
					<li>
						<a href="#"><h6>Mauris Vitae Adipiscing et Urna</h6></a>
						<span class="widget-date">June 14, 2013</span>
					</li>
					<li>
						<a href="#"><h6>Donec Blandit Luctus Diam</h6></a>
						<span class="widget-date">June 13, 2013</span>
					</li>
					
				</ul>
				
			</div><!--/ .widget-container-->
			
			<div class="widget-container widget_video">
				
				<h3 class="widget-title">Video Title</h3>
				
				<div class="video-widget">
					<iframe class="custom-frame" width="290" height="200" src="" frameborder="0"></iframe><allowfullscreen></allowfullscreen>
				</div><!--/ .video-widget-->
				
				<div class="video-entry">
					<a href="#" class="video-title">
						<h5>Potenti Nullam Consectetur Urna Ipsum Fringilla</h5>
					</a>
				</div><!--/ .video-entry-->
				
			</div><!--/ .widget-container-->
			
		<!-- - - - - - - - - - - - - end Sidebar - - - - - - - - - - - - - - - - -->
		
	</section><!--/.container -->
	<!-- - - - - - - - - - - - - end Container - - - - - - - - - - - - - - - - -->	
	
	
	<!-- - - - - - - - - - - - - - - Footer - - - - - - - - - - - - - - - - -->	
		<?php include 'cache/index/index/footer_html.php';?>
</body>
</html>
