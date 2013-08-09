<?php
require_once '_includes.php';

include 'html/header.php';

$sql = "SELECT challenge.*, challenge.id challenge_id, challenge.due_date challenge_due_date, target_user.facebook_info target_fb, target_user.firstname target_firstname, target_user.lastname target_lastname, target_user.facebook_uid target_facebook_uid, owner_user.firstname owner_firstname, owner_user.lastname owner_lastname FROM challenge LEFT JOIN user target_user ON target_user.id = challenge.target_facebook_id LEFT JOIN user owner_user ON owner_user.id = challenge.owner_user_id";

foreach ($db->query($sql) as $row) {
  ?>
  <div class="row">
  <a href="wishmob.php?challenge_id=<?=$row['challenge_id']?>">
    <div class="span2 offset2">
      <img src="https://graph.facebook.com/<?php echo $row['target_facebook_uid']; ?>/picture?type=large" class="thumbnail"/>
    </div>
    <div class="span9">
      <h2 style="color:#f37132;"><?php echo $row['title']; ?></h2>
      <h2 style="font-size:20px;"><span style="color:#f37132;">HERO:</span> 
      	<?php echo $row['target_firstname'] . ' ' . $row['target_lastname']; ?> 
      	
      </h2>
      <h2 style="font-size:16px;color:#515151;">
      <?php echo $row['description']; ?>
      </h2>
    </div>
  </a>
  <div>
  <?php
  	$row_year	= substr($row['challenge_due_date'], 0,4);
  	$row_month	= ((int)substr($row['challenge_due_date'], 5,2))-1;
  	$row_day	= substr($row['challenge_due_date'], 8,2);
  	$row_hour	= substr($row['challenge_due_date'], 11,2);
  	$row_minute	= substr($row['challenge_due_date'], 14,2);
   ?>
  </div>
		<script type="text/javascript">
		$(function () {
		$('#countdown<?=$row['challenge_id']?>').countdown({until:new Date(<?php echo $row_year; ?>,<?php echo $row_month; ?>,<?php echo $row_day; ?>,<?php echo $row_hour; ?>,<?php echo $row_minute; ?>), format: 'DHMS', layout: 
								  '<div id="timer">' +
									  '<div id="timer_days" class="timer_numbers">{dnn}</div>'+
									  '<div id="timer_hours" class="timer_numbers">{hnn}</div>'+ 
									  '<div id="timer_mins" class="timer_numbers">{mnn}</div>'+
									  '<div id="timer_seconds" class="timer_numbers">{snn}</div>'+
									'<div id="timer_labels">'+
										'<div id="timer_days_label" class="timer_labels">days</div>'+
										'<div id="timer_hours_label" class="timer_labels">hours</div>'+
										'<div id="timer_mins_label" class="timer_labels">mins</div>'+
										'<div id="timer_seconds_label" class="timer_labels">secs</div>'+
									'</div>'+							
								'</div>'
								  
		});
		});
		</script>

	<div class="countdown span3" style="margin-top:40px" id="countdown<?=$row['challenge_id']?>"></div>
  </div>
  <hr />
  <?php
}
include 'html/footer.php';