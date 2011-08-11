<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2010 VINADES ., JSC. All rights reserved
 * @Createdate Dec 3, 2010  11:32:04 AM 
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$userfile = $_FILES['uploadfile']; 

if( empty( $userfile ) )
{
    Header( "Location: " . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) );
    exit();
}

$songname = filter_text_input( 'song', 'get,post', '' );
$singer = filter_text_input( 'singer', 'get,post', '' );
$newsinger = filter_text_input( 'newsinger', 'get,post', '' );
$author = filter_text_input( 'author', 'get,post', '' );
$newauthor = filter_text_input( 'newauthor', 'get,post', '' );
$category = $nv_Request->get_int( 'category', 'get,post', 0 );

$maxsize = $setting['upload_max'] * ( 1024 * 1024 );

if ( defined( 'NV_IS_USER' ) )
{
    $name = $user_info['username'];
    $userid = $user_info['userid'];
}
else
{
    $name = $lang_module['upload_visittor'];
	$userid = 0;
}

require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.php' );
require_once ( NV_ROOTDIR . "/modules/" . $module_name . '/class/getid3/getid3.functions.php' );

$au = GetAllMP3info( $_FILES['uploadfile']['tmp_name'] );
$filetype = $au['fileformat'];
$bitrate = $au['bitrate'];
$filesize = $au['filesize'];
$duration = $au['playtime_seconds'];

if ( ( $filetype != "mp3" ) || ( $filesize > $maxsize ) )
{
	@unlink( $_FILES['uploadfile']['tmp_name'] );
	echo '<div id="output">failed</div>';
	echo '<div id="message"></div>';
}
else
{
	$url_return = "";
	$upload_success = false;
	$saved = false;
	
	function getextension( $filename )
	{
		if ( strpos( $filename, '.' ) === false ) return '';
		$filename = basename( strtolower( $filename ) );
		$filename = explode( '.', $filename );
		return array_pop( $filename );
	}
	
	function string_to_filename( $word )
	{
		$utf8_lookup = false;
		include ( NV_ROOTDIR . "/includes/utf8/lookup.php" );
		$word = strtr( $word, $utf8_lookup['romanize'] );
		$word = preg_replace( '/[^a-z0-9\.\-\_ ]/i', '', $word );
		$word = preg_replace( '/^\W+|\W+$/', '', $word );
		$word = preg_replace( '/\s+/', '-', $word );
		return strtolower( preg_replace( '/\W-/', '', $word ) );
	}
	
	preg_match( "/^(.*)\.[a-zA-Z0-9]+$/", $userfile['name'], $f );
	$fn = string_to_filename( $f[1] );
	
	$filename = $fn . "." . getextension( $userfile['name'] );
	if ( $setting['default_server'] == 1 )
	{
		$currentpath = NV_ROOTDIR . '/uploads/' . $module_data . '/' . $setting['root_contain'] . '/upload/'; 
        $filename2 = $filename;
        $i = 1;
        while ( file_exists( $currentpath . $filename2 ) )
        {
            $filename2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $filename );
            $i++;
        }
        $filename = $filename2;
        if ( move_uploaded_file ( $userfile['tmp_name'], $currentpath . $filename ) )
		{
			$upload_success = true;
			$url_return = NV_BASE_SITEURL . '/uploads/' . $module_data . '/' . $setting['root_contain'] . '/upload/' . $filename;
		}
		else @unlink( $_FILES['uploadfile']['tmp_name'] );
	}
	else
	{
		$hostid = $setting['default_server'];
		$ftpdata = getFTP();
		
		$this_host = $ftpdata[$hostid]['host'];
		$this_user = $ftpdata[$hostid]['user'];
		$this_pass = $ftpdata[$hostid]['pass'];
		$currentpath = $ftpdata[$hostid]['fulladdress'] . $ftpdata[$hostid]['subpart'];
        $filename2 = $filename;
        $i = 1;
        while ( nv_check_url( $currentpath . "/" . $filename2 ) )
        {
            $filename2 = preg_replace( '/(.*)(\.[a-zA-Z0-9]+)$/', '\1_' . $i . '\2', $filename );
            $i++;
        }
		
        $filename = $filename2;
		require_once ( NV_ROOTDIR . "/modules/" . $module_name . "/class/ftp.class.php" );
		$ftp = new FTP();
		if ( $ftp->connect( $this_host ) ) 
		{
			if ( $ftp->login( $this_user, $this_pass ) ) 
			{
				$ftp->put( $ftpdata[$hostid]['ftppart']  . $ftpdata[$hostid]['subpart'] . "/" . $filename, $userfile['tmp_name'] );
				$url_return = $currentpath . "/" . $filename;
				if ( nv_check_url ( $url_return ) )
				{
					$upload_success = true;
				} 
			} 
			$ftp->disconnect();
		} 
		@unlink( $userfile['tmp_name'] );
	}
	
	if ( $upload_success )
	{
		if ( $newsinger != $lang_module['upload_quicksinger'] )
		{
			newsinger( change_alias( $newsinger ), $newsinger );
			$singer = $newsinger;
		}
		
		if ( $newauthor != $lang_module['upload_quickauthor'] )
		{
			newauthor( change_alias( $newauthor ), $newauthor );
			$author = $newauthor;
		}
		
		$hit = "0-" . NV_CURRENTTIME;
		$check_url = creatURL ( $url_return );
		$duongdan = $check_url['duongdan'];
		$server = $check_url['server'];
		
		$query = "INSERT INTO `" . NV_PREFIXLANG . "_" . $module_data . "` ( `id`, `ten`, `tenthat`, `casi`, `nhacsi`, `album`, `theloai`, `duongdan`, `upboi`, `numview`, `active`, `bitrate`, `size`, `duration`, `server`, `userid`, `dt`, `binhchon`, `hit` ) VALUES ( NULL, " . $db->dbescape( change_alias( $songname ) ) . ", " . $db->dbescape( $songname ) . ", " . $db->dbescape( change_alias( $singer ) ) . ", " . $db->dbescape( change_alias( $author ) ) . ", 'na', " . $db->dbescape( $category ) . ", " . $db->dbescape( $duongdan )  . ", " . $db->dbescape( $name ) . " , 0, " . $setting['auto_upload'] . ", " . $bitrate . " , " . $filesize . " , " . $duration . ", " . $server . ", " . $userid . ", UNIX_TIMESTAMP() , 0, " . $db->dbescape( $hit ) . " ) "; 
		if ( $db->sql_query_insert_id( $query ) )
		{
			$db->sql_freeresult();
			$saved = true;
			updatesinger( $singer, 'numsong', '+1' );
		}
	
	}
	if ( $saved )
	{
		echo '<div id="output">success</div>';
		if ( $setting['auto_upload'] == 1 )
		{
			$result = $db->sql_query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " ORDER BY id DESC LIMIT 0,1" );
			$song = $db->sql_fetchrow($result);
			echo '<div id="message">' . $lang_module['upload_ok4'] . ' <a href="' . nv_url_rewrite( $mainURL . '=listenone/' . $song['id'] . '/' . $song['ten'], true ) . '" target="_blank">' . $lang_module['upload_ok1'] . '</a> ' . $lang_module['upload_ok2'] . ' <a href="' . nv_url_rewrite( NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name, true ) . '">' . $lang_module['upload_ok3'] . '</a></div>';
		}
		else
		{
			echo '<div id="message">' . $lang_module['upload_nook'] . '</div>';
		}
	}
	else
	{
		echo '<div id="output">failed</div>';
		echo '<div id="message"></div>';
	}
}	
?>