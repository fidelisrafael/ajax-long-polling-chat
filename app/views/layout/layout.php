<!DOCTYPE html>
<!--[if lt IE 7]>      <html class=""> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
	<meta charset='UTF-8'>
	<meta content='IE=edge,chrome=1' http-equiv='X-UA-Compatible'>
	<meta content='width=device-width' name='viewport'>
	<meta content='' name='description'>
	<meta content='' name='author'>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
	<title>Simple Ajax Pooling Chat</title>
	<link rel="stylesheet" href="public/assets/css/icons.css" />
	<link rel="stylesheet" href="public/assets/css/style.css" />
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Lato:300,400,700" media="screen" type="text/css">
</head>
<body>
	<section class="wrapper">
		<?php echo $this->view_html ?>
	</section>
	<footer>
		<p class="text-center"><br>
			Made with love <span class="heart">♥</span> by 
			<a href="http://defidelis.herokuapp.com" title="Rafael Fidelis : Blog" class="transition-all">Rafael Fidelis</a>
		</p>
	</footer>
	<script>
		//http://digitalize.ca/2010/04/javascript-tip-save-me-from-console-log-errors/
		if(typeof(console) === 'undefined') {
		    var console = {}
		    console.log = console.error = console.info = console.debug = console.warn = console.trace = console.dir = console.dirxml = console.group = console.groupEnd = console.time = console.timeEnd = console.assert = console.profile = function() {};
		}
	</script>
	<script src="public/assets/js/main.js"></script>
</body>
</html>