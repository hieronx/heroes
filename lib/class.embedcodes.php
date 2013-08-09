<?php
class Embedcodes {
	public static function get($link) {
		$parts = parse_url($link);
		if (stristr($link, 'youtube')) return self::youtube($link);
		elseif (stristr($link, 'flickr')) return self::flickr($link);
		elseif (stristr($link, 'soundcloud')) return self::soundcloud($link);
		elseif (stristr($link, 'spotify')) return self::spotify($link);
		elseif (@getimagesize($link)) return '<img src="' . $link . '" />';
		else return '<a href="' . $link . '">' . $link . '</a>';
	}

	public static function youtube($youtube_link) {
		preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $youtube_link, $matches);
		return '<iframe width="460" height="259" src="http://www.youtube.com/embed/'.$matches[1].'" frameborder="0" allowfullscreen></iframe>';
	}

	public static function flickr($flickr_link) {
		return '<iframe align="center" src="'.$flickr_link.'" frameBorder="0" width="500" height="500" scrolling="no"></iframe>';
	}

	public static function soundcloud($soundcloud_link) {
		$soundcloud = file_get_contents('http://soundcloud.com/oembed?format=js&url='.$soundcloud_link.'&iframe=true');
		$soundcloud = json_decode(substr($soundcloud,1,strlen($soundcloud)-3));
		return $soundcloud->{'html'};
	}

	public static function spotify($spotify_link) {
		$path = parse_urL($spotify_link, PHP_URL_PATH);
		$parts = explode('/', $path);
		$username = $parts[1];
		$playlist_id = $parts[3];
		return '<iframe src="https://embed.spotify.com/?uri=spotify:user:' . $username . ':playlist:' . $playlist_id . '" width="460" height="500" frameborder="0" allowtransparency="true"></iframe>';
	}
}

// echo show_youtube_video("http://www.youtube.com/watch?v=R7E2t8oJG0I&feature=g-vrec");
// echo '<BR><BR>';
// echo show_flickr_slideshow("http://www.flickr.com/slideShow/index.gne?group_id=&user_id=&set_id=&tags=Cars,Lotus,Exige");
// echo '<BR><BR>';
// echo show_soundcloud_player("http://soundcloud.com/thisisyvette/loving-is-easy");