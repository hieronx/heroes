<?php

class Embedcodes {
	public static function get($link) {
		$parts = parse_url($link);
		if (strstr($link, 'youtube')) return self::show_youtube_video($link);
		elseif (strstr($link, 'flickr')) return self::show_flickr_slideshow($link);
		elseif (strstr($link, 'soundcloud')) return self::show_soundcloud_player($link);
		else return '<a href="' . $link . '">' . $link . '</a>';
	}

	public static function show_youtube_video($youtube_link) {
		preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $youtube_link, $matches);
		return '<iframe width="560" height="315" src="http://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe>';
	}

	public static function show_flickr_slideshow($flickr_link) {
		return '<iframe align="center" src="'.$flickr_link.'" frameBorder="0" width="500" height="500" scrolling="no"></iframe>';
	}


	public static function show_soundcloud_player($soundcloud_link) {

		$soundcloud = file_get_contents('http://soundcloud.com/oembed?format=js&url='.$soundcloud_link.'&iframe=true');
		$soundcloud = json_decode(substr($soundcloud,1,strlen($soundcloud)-3));
		return $soundcloud->{'html'};
	}
}

// echo show_youtube_video("http://www.youtube.com/watch?v=R7E2t8oJG0I&feature=g-vrec");
// echo '<BR><BR>';
// echo show_flickr_slideshow("http://www.flickr.com/slideShow/index.gne?group_id=&user_id=&set_id=&tags=Cars,Lotus,Exige");
// echo '<BR><BR>';
// echo show_soundcloud_player("http://soundcloud.com/thisisyvette/loving-is-easy");