<?php 
// var_dump($body);
// exit; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Mahabharat Download and Streaming</title>
	<link type="image/x-icon" href="<?php echo URL::base('vendor/img/icon-mahabharat.ico') ?>" rel="Shortcut icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo Theme::base('vendor/css/font-awesome/font-awesome.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo Theme::base('vendor/css/naked.min.css') ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo Theme::base('vendor/css/style.css') ?>">
</head>
<body>
	<div>
		<nav class="navbar-menu">
			<div class="wrapper">
				<img src="<?php echo URL::base('vendor/img/mahabharat-logo.jpg') ?>">
				<a href="<?php echo URL::site() ?>" class="brand"><span class="menu"><?php echo f('page.title', 'Bono Application') ?></span></a>
				<section class="topbar pull-right"></section>
			</div>
		</nav>
	</div>
	<div class="tabstrip">
		<div class="row">
			<a class="tabspan-2 tabs" href="<?php echo URL::site() ?>">
				<p class="fa fa-home fa-2x"></p>
				<span>Home</span>
			</a>
			<a class="tabspan-2 tabs" href="<?php echo URL::site('/film') ?>">
				<p class="fa fa-film fa-2x"></p>
				<span>List</span>
			</a>
			<a class="tabspan-2 tabs" href="#">
				<p class="fa fa-search fa-2x"></p>
				<span>Search</span>
			</a>
			<a class="tabspan-2 tabs" target="_blank" href="https://www.facebook.com/groups/mahabharataantv/">
				<p class="fa fa-facebook fa-2x"></p>
				<span>Facebook</span>
			</a>
			<a class="tabspan-2 tabs" target="_blank" href="https://twitter.com/Mahabarata_ANTV">
				<p class="fa fa-twitter fa-2x"></p>
				<span>Twitter</span>
			</a>
		</div>
	</div>
	<main>
        <div id="main-content">
	        <div class="list-view">
	            <?php echo f('notification.show') ?>
	            <?php echo $body ?>
			</div>
        </div>
    </main>
	
</body>
</html>
