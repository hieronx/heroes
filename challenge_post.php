<?php
require_once '_includes.php';
require_once 'lib/class.phpmailer.php';

if (isset($_POST['title']) && isset($_POST['description'])) {
	$target_profile = $facebook->api('/'.$_POST['target_user']);

	$q = $db->prepare("INSERT INTO user 
	                  (facebook_uid, firstname, lastname, facebook_info, creation_date) 
	                  VALUES (
	                    '" . $target_profile ['id'] . "',
	                    '" . $target_profile ['first_name'] . "',
	                    '" . $target_profile ['last_name'] . "',
	                    '" . json_encode($target_profile) . "',
	                    NOW()
	                  )");
	$q->execute();

	$q = $db->prepare("INSERT INTO challenge (owner_user_id, target_facebook_id, title, description, creation_date, due_date) 
	                    VALUES ('".$CURRENT_USER['id']."', '".$db->lastInsertId()."', '".$_POST['title']."', 
	
											'".$_POST['description']."', NOW(), '" . date('Y-m-d H:i:s', strtotime($_POST['due_date']))."')");
	$q->execute();
	
	$mail = new PHPMailer();
	$mail->CharSet = "utf-8";

	$mail->IsHTML(true);

	$mail->IsSMTP();
	$mail->Host = "smtp.webfaction.com";
	$mail->SMTPAuth = true;
	$mail->Username = "joinheroes_info";
	$mail->Password = "mjnPNerARGMfr5wj";
	$mail->SMTPSecure = "ssl";
	$mail->Port = 465;

	$info_from = $facebook->api('/' . $CURRENT_USER['facebook_uid']);

	if (!empty ($_POST ['friends'])) {
		foreach ($_POST['friends'] as $facebook_uid) {
			$info = $facebook->api('/' . $facebook_uid);

			$mail->FromName = 'Heroes';
			$mail->From = 'info@joinheroes.com';

			$mail->AddReplyTo($info_from['username'] . '@facebook.com', $info_from['name']);
			$mail->AddAddress($info['username'] . '@facebook.com', $info['name']);
			$mail->Subject = 'Let\'s do something remarkable together';

			$patient = $target_profile['name'];

			$body = '
				Hi ' . $info['first_name'] . '!<br /><br />

				I\'d like to invite you to team up for an online flash mob, exclusively organized for ' . $patient . '.<br />
				You know ' . $patient . ' has been through a lot lately. Coming ' . $_POST["due_date"] . ' will be an extra important day because of ' . $_POST["reason"] . '.<br />
				Take a moment. Let\'s show our significance as friends.<br />
				Join in and immerse ' . $patient . ' in our friendship and make ' . $patient . ' feel remarkable.<br />
				It\'s easy! Click to signup and be a hero for ' . $patient . '\'s flash mob. You can see who\'s joining as well!<br /><br />

				<a href="http://joinheroes.com/">joinheroes.com</a<br /><br />

				See you there!<br />
				' . $info_from ['name'] . '<br /><br />

				PS. HUSH! Don\'t tell ' . $patient . ',  it\'s a surprise!
			';

			$mail->MsgHTML($body);
			$mail->Send();
		}
	}
	
	header('Location: challenges.php');
	exit();

} else {
	
include 'html/header.php';

$friends = $facebook->api('/' . $CURRENT_USER['facebook_uid'] . '/friends');
?>

    <div class="sizer">
	<form method="POST" class='form'>
		<label>Hero</label>
		<select name="target_user">
			<?php foreach ($friends['data'] as $friend) : ?>
				<option value="<?php echo $friend['id']; ?>"><?php echo $friend['name']; ?></option>
			<?php endforeach; ?>
		</select>

		<label>Invite friends</label>
		<select name="friends[]" multiple>
			<?php foreach ($friends['data'] as $friend) : ?>
				<option value="<?php echo $friend['id']; ?>"><?php echo $friend['name']; ?></option>
			<?php endforeach; ?>
		</select>

		<label>Title</label>
		<input type='text' placeholder='Title' name='title' />

		<label>Description</label>
		<textarea placeholder='Description' name='description'></textarea>

		<label>Reason</label>
		<textarea placeholder='Reason' name='reason'></textarea>

		<label>Due date</label>
		<input type='text' placeholder='Due date' name='due_date' value='<?php echo date('d-m-Y H:m', mktime(0, 0, 0, date("m"), date("d")+7, date("Y"))); ?>' />
		<br /><br />

		<input type='submit' value='Submit' />
	</form>
	</div>
<?php }

include 'html/footer.php';