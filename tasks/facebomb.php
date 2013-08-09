<?php
require dirname(__FILE__).'/../_includes.php';


parse_str(implode('&', array_slice($argv, 1)), $args);

if (!isset($args['challenge_id'])) { exit('Geef challenge_id=## mee.'); }

$challenge_id = $args['challenge_id'];

$sql = "SELECT * FROM challenge_post 
				LEFT JOIN user ON user.id = challenge_post.user_id 
				WHERE challenge_post.challenge_id = '{$challenge_id}' 
				ORDER BY challenge_post.id DESC";
				
$posts = $db->query($sql);

$messages = array();

foreach ($posts as $post) {
	$message = json_decode($post['message']);
	
	$messages[] = array(
		'name' 	=> $post['firstname'],
		'token' => $post['facebook_token'], 
		'text' 	=> $message->text, 
		'link' 	=> $message->link);
}

$sql = "SELECT * FROM challenge LEFT JOIN user ON user.id = challenge.target_facebook_id  WHERE challenge.id = '{$challenge_id}'";
$challenge = $db->query($sql)->fetch();

echo "Flashmobbing challenge '{$challenge['title']}' for user {$challenge['firstname']}\n\n";

// Dit is de fake data. Komt straks uit database
$challenge = array(
	'fighter' => $challenge['facebook_uid']
);

foreach ($messages as $message) {
	$what_to_post = array('message' => $message['text'], 'link' => $message['link']);
	$facebook->setAccessToken($message['token']);
	
	try {
		echo "Attempting post to {$message['name']}: '{$message['text']}'\n";

		print_r(array("/{$challenge['fighter']}/feed", 'POST', $what_to_post));
		if (isset($args['real'])) { 
			$post = $facebook->api("/{$challenge['fighter']}/feed", 'POST', $what_to_post);
		} else {
			echo "[stubbed]\n";
		}
		echo "SUCCESS: Hoera.\n";
	} catch (Exception $e) {
		echo "FAIL: {$e->getMessage()}\n";
	}
}
