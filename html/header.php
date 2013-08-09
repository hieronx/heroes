<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <meta charset="utf-8">
    <title>Heroes</title>
    <meta property="og:title" content="Heroes - A remarkable crowd, a remarkable moment" />
    <meta property="og:type" content="company" />
    <meta property="og:site_name" content="Heroes" />
    <meta property="og:url" content="http://joinheroes.com/" />
    <meta property="og:description" content="A remarkable crowd, a remarkable moment. Take a look at JoinHeroes.com" />
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="/css/heroes.css" type="text/css"/>
    <link rel="stylesheet" href="/css/wishmob.css" type="text/css"/>
    <link rel="stylesheet" href="/css/challenge_post.css" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/style.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script type="text/javascript" src="js/jquery.countdown.pack.js"></script>
</head>
<body class="dashboard">
				<?php	
				$CHALLENGE = false;
				if (isset($_GET['challenge_id'])) {
					$sql = "SELECT * FROM challenge 
									LEFT JOIN user ON user.id = challenge.target_facebook_id 
									WHERE challenge.id = " . $_GET['challenge_id'];
					$CHALLENGE = $db->query($sql)->fetch();
					$challenge_name = "&mdash; Wishmob for {$CHALLENGE['firstname']}";
				}
				?>
               <?php if ($CURRENT_USER) { ?>
    <div id="header">
        <div class="sizer">
	

          <div class="left"><h1 class="header-hero"><a href="/index.php" title="Go home ...">Heroes</a> <span><?=$CHALLENGE ? $challenge_name : null?></span></h1></div>
            <div class="right header-right">

                <a class="header-a clearfix" href="/index.php">
					<img src="http://graph.facebook.com/<?=$CURRENT_USER['facebook_uid']?>/picture/?type=large" width="40" height="40" alt="Fleur"/>
		                    <span class="header-name"><?php if ($CURRENT_USER) { ?>Hey, <?=$CURRENT_USER['firstname']?><?php } ?></span>

                   <span class="header-arrow"></span>
                </a>
                <ul class="header-menu">
                    <li><a href="challenges.php">All wishmobs</a></li>
                    <li><a href="challenge_post.php">Create new wishmob</a></li>
                    <li><a href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

										<?php } else { ?>
										<?php } ?>

 
