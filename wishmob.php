<?php
require_once '_includes.php';

include 'html/header.php';

$challenge_id = $_GET['challenge_id'];
$user_id 			= $CURRENT_USER['facebook_uid'];

$obj = json_decode(file_get_contents('http://graph.facebook.com/' . $CHALLENGE['facebook_uid'] . '?fields=cover'), true);
$cover_picture = $obj['cover'] ['source'];

if (isset($_POST['post_text']) && isset($_POST['post_link'])) {

	$message = array('text' => $_POST['post_text'], 'link'  => $_POST['post_link']);
	
	$q = $db->prepare("INSERT INTO challenge_post (user_id, challenge_id, message, creation_date) 
	                    VALUES ('".$CURRENT_USER['id']."', '".$challenge_id."', '".json_encode($message)."', NOW())");
	$q->execute();
	?>
	
	Message saved!
	
	
<?php } ?>


    <div class="sizer">
        <div class="cover">
            <div class="coverWrapper">
                <div class="coverImg"><img src="<?php echo $cover_picture; ?>" width="980" alt="Cover photo" style="margin-top:-180px"/></div>
            </div>
            <!--<h1>Wishmob challenge for <?=$CHALLENGE['firstname']?> <?=$CHALLENGE['lastname']?></h1>-->
            <img class="coverAvatar" src="http://graph.facebook.com/<?=$CHALLENGE['facebook_uid']?>/picture?type=large" height="100" width="100"/>
        </div>
    </div>


<?php
$joiners = $db->query("SELECT DISTINCT challenge_post.user_id, user.* FROM challenge_post 
					LEFT JOIN user ON user.id = challenge_post.user_id 
					WHERE challenge_post.challenge_id = {$_GET['challenge_id']} ORDER BY challenge_post.id DESC");

?>

<div id="container">
    <div class="sizer">
        <h1 class="tagline">These remarkable people people already attend the Wishmob</h1>
				<div id="people">
					<?php foreach ($joiners as $joiner) { ?> 
						<div class='hero'><img src='https://graph.facebook.com/<?=$joiner['facebook_uid']?>/picture?type=square' /></div>
					<?php } ?>
					<div class='hero'><img src='https://graph.facebook.com/emileaffolter/picture?type=square' /></div>
					<div class='hero'><img src='https://graph.facebook.com/matthijs.vankan/picture?type=square' /></div>
					<div class='hero'><img src='https://graph.facebook.com/Sarahannadelange/picture?type=square' /></div>
					<div class='hero'><img src='https://graph.facebook.com/whoislewis/picture?type=square' /></div>
					<div class='hero' style="border:none"><img src='/img/plus-52.png' /></div>
				</div>
    </div>
</div>


<?php
$sql = "SELECT * FROM challenge_post LEFT JOIN user ON user.id = challenge_post.user_id WHERE challenge_post.challenge_id = '" . $challenge_id . "' ORDER BY challenge_post.id DESC";
$posts = $db->query($sql); ?>

<?php
$row_year	= substr($CHALLENGE['due_date'], 0,4);
$row_month	= ((int)substr($CHALLENGE['due_date'], 5,2))-1;
$row_day	= substr($CHALLENGE['due_date'], 8,2);
$row_hour	= substr($CHALLENGE['due_date'], 11,2);
$row_minute	= substr($CHALLENGE['due_date'], 14,2);
?>

<script type="text/javascript" charset="utf-8">
$(function() {
	
	$('#countdown').countdown({until:new Date(<?php echo $row_year; ?>,<?php echo $row_month; ?>,<?php echo $row_day; ?>,<?php echo $row_hour; ?>,<?php echo $row_minute; ?>), format: 'DHMS', layout: 
							  '<div id="timer">' +
								  '<div id="timer_days" class="timer_numbers">{dnn}D</div>'+
								  '<div id="timer_hours" class="timer_numbers">{hnn}H</div>'+ 
								  '<div id="timer_mins" class="timer_numbers">{mnn}M</div>'+
								  '<div id="timer_seconds" class="timer_numbers">{snn}S</div>'+
							'</div>'

	});

	})
</script>

<div id="timelineWrapper" class="sizer">
    <div class="timelineArrowDown"></div>
    <ol class="timeline clearfix">
			<style type="text/css" media="screen">
				.countdown {
					width:405px;	
					padding:31px 30px 0px 30px;
				}
				
				.timer_numbers {
					font-size:40px;
					margin-right:14px;
					display:inline;
				}
			</style>

			<li class="timelineUnit timelineForm clearfix" side="l">
			      <div class="timelineBox ">
							<h2>Don't wait too long</h2>
			          <div class="timelineBoxInner">
										<div id="countdown" class='countdown'>countdown</div><!--close countdown-->
			          </div>
			          <div class="timelineBoxUser">
			          </div>
			      </div>
			  </li>


        <li class="timelineUnit timelineForm clearfix" side="r">
            <div class="timelineBox">
                <h2>It still needs your voice ...</h2>
                <div class="clearfix">
                    <form method="POST" action="">
                        <input type="text" placeholder='http://... (YouTube, SoundCloud, Spotify)' name='post_link' style="width:460px"/>
                        <textarea placeholder='Write your message for <?=$CHALLENGE['firstname']?>!' name='post_text' rows="2" cols="10" class="timelineFormTextarea"></textarea>
                        <div class="clearfix">

                            <div class="right">
                                <button class="btn btn-success">Post</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </li>


			<!-- <li class="timelineUnit timelineForm clearfix" side="r">
			      <div class="timelineBox">
			          <div class="timelineBoxInner">
									<form method="POST">
										<h2 style='text-align:left; margin-bottom:10px;'>It still needs your voice!</h2>
										<textarea ></textarea>
										<input type='text'  />
										<input type='submit' value='Submit' />
									</form>	

			          </div>
			          <div class="timelineBoxUser">
			          </div>
			      </div>
			  </li> -->


<?php $i = 1; foreach ($posts as $row) { $i++; $message = json_decode($row['message'], true); ?>

<li class="timelineUnit clearfix" side="<?= $i % 2 ? 'r' : 'l'?>">
      <i class="timelineSpine"></i>
      <div class="timelineBox">
          <!-- The inner content width is max 460px -->
          <div class="timelineBoxInner">
              <?=Embedcodes::get($message['link']); ?>
          </div>
          <div class="timelineBoxUser">
              <a href="#" class="clearfix">
                  <img width="30" height="30" 
										src="https://graph.facebook.com/<?php echo $row['facebook_uid']; ?>/picture" />
                  <span><?=$row['firstname']; ?></span> &mdash; <?=$message['text']; ?>
              </a>
          </div>
      </div>
  </li>
  <?php } ?>

    </ol>
    <div class="timelineArrowUp"><h1>Starting point</h1></div>
</div>


<?php include 'html/footer.php'; ?>