<?php
session_start();
if( isset( $_REQUEST['username'] ) && isset( $_REQUEST['limit'] ) ):

	require_once('api/twitteroauth.php');
	
	$username = $_REQUEST['username'];
	$limit = $_REQUEST['limit'];
	$consumerkey = 'nXkGVYjE1PD5nwKQ93eokIX04';
	$consumersecret = '2oSw4QhRgu3Y7x4c9opvx6Gs40NupseaPYMAckqpVFWctXjcnA';
	$accesstoken = '3086243450-bHDBTjiS5NbY8lMOhlqAWjF7PXXH5xS4liFwKEC';
	$accesstokensecret = 'NqBuaiwSgk1aZvVXjnQOLpydjDGfypfbyw2LZFJWvfrGM';
	$interval = 600;
	$now = time();
	$cache_file = dirname(__FILE__) . '/cache/' . $username . '_' . $limit;

	if( file_exists( $cache_file ) ) {
		$last = filectime( $cache_file );
	} else {
		$last = false;
	}

	if( !$last || ( ($now-$last) > $interval )) {

		$connection = new TwitterOAuth( $consumerkey , $consumersecret , $accesstoken , $accesstokensecret );
		$tweets = $connection->get("https://api.twitter.com/1.1/statuses/user_timeline.json?screen_name=".$username."&count=".$limit);
		$tweets = serialize($tweets);

		if( !empty($tweets) ) {
			$fp = fopen( $cache_file , 'wb' );
			fwrite( $fp , $tweets );
			fclose( $fp );
		}

		$tweets = unserialize( file_get_contents($cache_file) );
	} else {
		$tweets = unserialize( file_get_contents( $cache_file ) );
	}

	echo json_encode($tweets);
endif;?>