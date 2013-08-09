<?php
require_once '_includes.php';

// Als er al een ingelogde user is, dan gaan we naar challenges
if ($CURRENT_USER) {
	header('Location: challenges.php');
}

include 'html/header.php';

$loginUrl = $facebook->getLoginUrl(array('scope' => 'publish_stream', 'redirect_uri' => "http://{$_SERVER['SERVER_NAME']}/facebook_callback.php"));

?>

<style type="text/css" media="screen">
	body {
		background: url('img/landing-background.jpg') top center no-repeat;
		background-size:100%;
	}
	
	#login-button img {
		display:block;
	}
	
	#header {
		z-index: 100000000;
		position: absolute;
		top:0;
		width: 100%;
	}
	
	
	#footer {
		position: fixed;
		bottom: 0;
		width: 100%;
	}
</style>

<script type="text/javascript">
$(document).ready(function(){
	var button = $('#login-button');
	
	function moveButton() {
		// $('#login-button img').animate({'margin-botto': '613px'}, 500, 'easeInElastic', function () {
		//   $('#login-button img').animate({width: '553px'}, 500, 'easeOutElastic');
		// });
	}
	
	setInterval(moveButton, 2000);
	moveButton();
	
	button.hover(function() {
		button.find('#facebook_btn').animate({'margin-top': '-10px'}, 200, 'easeOutBounce');
	}, function() { 
		button.find('#facebook_btn').animate({'margin-top': '0px'}, 200, 'easeInBounce');
	})
});

</script>


<a href="<?php echo $loginUrl; ?>" id="login-button"><img id='facebook_btn' style="padding-top:150px; padding-left:40px;" src="img/landing-facebook-button.png"></a>

<?php include 'html/footer.php'; ?>
