<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$mainURL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . '&amp;' . NV_OP_VARIABLE;
$main_header_URL = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . '&' . NV_OP_VARIABLE;

// Lay album tu ten
function getalbumbyNAME( $name )
{
	global $module_data, $db;

	$album = array();
	$result = $db->query( "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE name=" . $db->quote( $name ) );
	$album = $result->fetch();

	return $album;
}

// Xuat duong dan day du
function outputURL( $server, $inputurl )
{
	global $module_name, $classMusic, $module_upload;
	$output = "";
	if( $server == 0 )
	{
		$output = $inputurl;
	}
	elseif( $server == 1 )
	{
		$output = NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_upload . "/" . $classMusic->setting['root_contain'] . "/" . $inputurl;
	}
	else
	{
		$ftpdata = getFTP();
		foreach( $ftpdata as $id => $data )
		{
			if( $id == $server )
			{
				if( $data['host'] == "nhaccuatui" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );
						$cache = "";

						if( preg_match( "/\[FLASH\](.*?)\[\/FLASH\]/i", $output, $m ) )
						{
							$output = get_headers( $m[1] );

							foreach( $output as $tmp )
							{
								if( preg_match( "/^Location: (.*)/is", $tmp, $m ) )
								{
									if( preg_match( "/file\=(.*)\&ads\=/is", $tmp, $m ) )
									{
										$output = simplexml_load_string( nv_get_URL_content( $m[1] ) );
										$output = trim( ( string ) $output->track->location );
										break;
									}
								}
							}
						}

						$cache = serialize( $cache );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "zing" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );
						$output = explode( '<input type="hidden" id="_strNoAuto" value="', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( '"', $output[1] );
							$output = nv_get_URL_content( $output[0] );
							$output = explode( "<urlSource>", $output );

							if( isset( $output[1] ) )
							{
								$output = explode( "</urlSource>", $output[1] );
								$output = nv_unhtmlspecialchars( $output[0] );
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nhacvui" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						unset( $m );
						$pattern = "/\'playlistfile\'\: \'(.*?)\'\,/i";
						if( ! empty( $output ) and preg_match( $pattern, $output, $m ) )
						{
							$output = nv_get_URL_content( "http://hcm.nhac.vui.vn" . trim( $m[1] ) );
							unset( $m );
							$pattern = "/\<jwplayer\:file\>\<\!\[CDATA\[(.*?)\]\]\>\<\/jwplayer\:file\>/i";
							if( ! empty( $output ) and preg_match( $pattern, $output, $m ) )
							{
								$output = trim( $m[1] );
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nhacso" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						$output = explode( 'embedPlaylistjs.swf?xmlPath=', $output );

						if( isset( $output[1] ) )
						{
							$output = explode( '&amp;', $output[1] );
							$output = nv_get_URL_content( $output[0] );

							$output = explode( "<mp3link><![CDATA[", $output );

							if( isset( $output[1] ) )
							{
								$output = explode( "]]></mp3link>", $output[1] );
								$output = trim( $output[0] );
							}
							else
							{
								$output = "";
							}
						}
						else
						{
							$output = "";
						}

						$cache = serialize( $output );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "zingclip" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_zingclip_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						unset( $m );
						if( ! preg_match( "/\<input type\=\"hidden\" id\=\"\_strAuto\" value\=\"([^\"]+)\"[^\/]+\/\>/is", $output, $m ) )
						{
							$output = "";
						}
						else
						{
							$output = nv_get_URL_content( $m[1] );
							if( ( $xml = simplexml_load_string( $output ) ) == false ) return "";
							$output = ( string )$xml->item->f480;
						}

						$cache = serialize( $output );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				elseif( $data['host'] == "nctclip" )
				{
					$cache_file = NV_LANG_DATA . "_" . $module_name . "_link_nctclip_" . md5( $server . $inputurl ) . "_" . NV_CACHE_PREFIX . ".cache";

					if( file_exists( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) )
					{
						if( ( ( NV_CURRENTTIME - filemtime( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file ) ) > $classMusic->setting['del_cache_time_out'] ) and $classMusic->setting['del_cache_time_out'] != 0 )
						{
							nv_deletefile( NV_ROOTDIR . "/" . NV_CACHEDIR . "/" . $cache_file );
						}
					}

					if( ( $cache = $nv_Cache->getItem( $cache_file ) ) != false )
					{
						$output = unserialize( $cache );
					}
					else
					{
						$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
						$output = nv_get_URL_content( $output );

						if( ! preg_match( "/\<input id\=\"urlEmbedBlog\" type\=\"text\" readonly\=\"readonly\" value\=\"\[FLASH\](.*?)\[\/FLASH\]\" class\=\"link3\" \/\>/is", $output, $m ) )
						{
							$output = "";
						}
						else
						{
							$tmp = get_headers( $m[1] );
							$output = "";
							foreach( $tmp as $_tmp )
							{
								if( preg_match( "/file\=(.*?)\&autostart\=/is", $_tmp, $m ) )
								{
									$output = nv_get_URL_content( $m[1] );
									if( ( $xml = simplexml_load_string( $output ) ) == false ) return "";
									$output = trim( ( string ) $xml->track->location );
								}
							}
						}

						$cache = serialize( $output );
						$nv_Cache->setItem( $cache_file, $cache );
					}
				}
				else
				{
					$output = $data['fulladdress'] . $data['subpart'] . $inputurl;
					break;
				}
			}
		}
	}
	return $output;
}

function nv_get_URL_content( $target_url )
{
	global $global_config;

	$UrlGetContents = new NukeViet\Client\UrlGetContents( $global_config );
	return $UrlGetContents->get( $target_url );
}

// lay tat ca ca si
function getallsinger( $reverse = false )
{
	global $module_name, $module_data, $db, $lang_module, $nv_Cache;

	$allsinger = array();

	if( $reverse === true )
	{
		$allsinger[$lang_module['unknow']] = 0;
	}
	else
	{
		$allsinger[0] = $lang_module['unknow'];
	}

	$sql = "SELECT id, tenthat FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer ORDER BY ten ASC";
	$result = $nv_Cache->db( $sql, 'ten', $module_name );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( $reverse === true )
			{
				$allsinger[$row['tenthat']] = $row['id'];
			}
			else
			{
				$allsinger[$row['id']] = $row['tenthat'];
			}
		}
	}

	return $allsinger;
}

// Lay thong tin the loai
function get_category()
{
	global $module_name, $module_data, $db, $lang_module, $nv_Cache;

	$category = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_category ORDER BY weight ASC";

	$result = $nv_Cache->db( $sql, 'id', $module_name );

	$category[0] = array(
		'id' => 0, //
		'title' => $lang_module['unknow'], //
		'keywords' => '', //
		'description' => '' //
	);

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$category[$row['id']] = array(
				'id' => $row['id'], //
				'title' => $row['title'], //
				'keywords' => $row['keywords'], //
				'description' => $row['description'] //
			);
		}
	}
	return $category;
}

// Them moi mot ca si
function newsinger( $name, $tname )
{
	$error = '';
	global $module_data, $lang_module, $db, $module_name, $nv_Cache;
	$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_singer ( id, ten, tenthat, thumb, introduction, numsong, numalbum) VALUES ( NULL, " . $db->quote( $name ) . ", " . $db->quote( $tname ) . ", '', '', 0, 0 )";
	$newid = $db->insert_id( $sql );

	if( $newid )
	{
		$nv_Cache->delMod( $module_name );
		return $newid;
	}

	return false;
}

// tao duong dan tu mot chuoi
function creatURL( $inputurl )
{
	global $module_name, $setting;

	$songdata = array();
	if( preg_match( '/^(ht|f)tp:\/\//', $inputurl ) )
	{
		$ftpdata = getFTP();
		$str_inurl = str_split( $inputurl );
		$no_ftp = true;
		foreach( $ftpdata as $id => $data )
		{
			$this_host = $data['fulladdress'] . $data['subpart'];
			$str_check = str_split( $this_host );
			$check_ok = false;
			foreach( $str_check as $stt => $char )
			{
				if( $char != $str_inurl[$stt] )
				{
					$check_ok = false;
					break;
				}
				$check_ok = true;
			}
			if( $check_ok )
			{
				$lu = strlen( $this_host );
				$songdata['duongdan'] = substr( $inputurl, $lu );
				$songdata['server'] = $id;
				$no_ftp = false;
				break;
			}
		}
		if( $no_ftp )
		{
			$songdata['duongdan'] = $inputurl;
			$songdata['server'] = 0;
		}
	}
	else
	{
		$lu = strlen( NV_BASE_SITEURL . NV_UPLOADS_DIR . "/" . $module_name . "/" . $setting['root_contain'] . "/" );
		$songdata['duongdan'] = substr( $inputurl, $lu );
		$songdata['server'] = 1;
	}
	return $songdata;
}

// Lay thong tin ftp cua host nhac
function getFTP()
{
	global $module_name, $module_data, $db, $lang_module, $nv_Cache;
	$ftpdata = array();
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_ftp ORDER BY id DESC";
	$result = $nv_Cache->db( $sql, 'id', $module_name );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$ftpdata[$row['id']] = array(
				"id" => $row['id'],
				"host" => $row['host'],
				"user" => $row['user'],
				"pass" => $row['pass'],
				"fulladdress" => $row['fulladdress'],
				"subpart" => $row['subpart'],
				"ftppart" => $row['ftppart'],
				"active" => ( $row['active'] == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'] );
		}
	}
	return $ftpdata;
}

function nvm_new_song( $array )
{
	global $module_data, $db;

	$array['ten'] = ! isset( $array['ten'] ) ? "" : $array['ten'];
	$array['tenthat'] = ! isset( $array['tenthat'] ) ? "" : $array['tenthat'];
	$array['casi'] = ! isset( $array['casi'] ) ? 0 : $array['casi'];
	$array['nhacsi'] = ! isset( $array['nhacsi'] ) ? 0 : $array['nhacsi'];
	$array['album'] = ! isset( $array['album'] ) ? 0 : $array['album'];
	$array['theloai'] = ! isset( $array['theloai'] ) ? 0 : $array['theloai'];
	$array['listcat'] = ! isset( $array['listcat'] ) ? array() : $array['listcat'];
	$array['data'] = ! isset( $array['data'] ) ? "" : $array['data'];
	$array['username'] = ! isset( $array['username'] ) ? "N/A" : $array['username'];
	$array['bitrate'] = ! isset( $array['bitrate'] ) ? "0" : $array['bitrate'];
	$array['size'] = ! isset( $array['size'] ) ? "0" : $array['size'];
	$array['duration'] = ! isset( $array['duration'] ) ? "0" : $array['duration'];
	$array['server'] = ! isset( $array['server'] ) ? "1" : $array['server'];
	$array['userid'] = ! isset( $array['userid'] ) ? "1" : $array['userid'];
	$array['hit'] = ! isset( $array['hit'] ) ? "0-" . NV_CURRENTTIME : $array['hit'];
	$array['lyric'] = ! isset( $array['lyric'] ) ? "" : $array['lyric'];

	$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " VALUES (
		NULL,
		" . $db->quote( $array['ten'] ) . ",
		" . $db->quote( $array['tenthat'] ) . ",
		" . $array['casi'] . ",
		" . $array['nhacsi'] . ",
		" . $array['album'] . ",
		" . $db->quote( $array['theloai'] ) . ",
		" . $db->quote( implode( ",", $array['listcat'] ) ) . ",
		" . $db->quote( $array['data'] ) . ",
		" . $db->quote( $array['username'] ) . " ,
		0,
		1,
		" . $db->quote( $array['bitrate'] ) . " ,
		" . $db->quote( $array['size'] ) . " ,
		" . $db->quote( $array['duration'] ) . ",
		" . $array['server'] . ",
		" . $array['userid'] . ",
		" . NV_CURRENTTIME . ",
		0,
		" . $db->quote( $array['hit'] ) . "
	)";

	$_songid = $db->insert_id( $sql );

	if( $_songid )
	{
		// Cap nhat bai hat cho the loai
		// Xac dinh chu de moi
		$list_new_cat = $array['listcat'];
		$list_new_cat[] = $array['theloai'];
		$list_new_cat = array_unique( $list_new_cat );

		foreach( $list_new_cat as $_cid )
		{
			if( $_cid > 0 ) UpdateSongCat( $_cid, '+1' );
		}

		// Cap nhat so bai hat cua ca si, nhac si va album
		updatesinger( $array['casi'], 'numsong', '+1' );
		updateauthor( $array['nhacsi'], 'numsong', '+1' );
		updatealbum( $array['album'], '+1' );

		// Them bai hat vao danh sach nhac cua album moi
		if( ! empty( $array['album'] ) )
		{
			$sql = "SELECT listsong FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id=" . $array['album'];
			$result = $db->query( $sql );
			$list_song = $result->fetchColumn();

			$list_song = explode( ',', $list_song );
			$list_song[] = $_songid;
			$list_song = array_unique( $list_song );
			$list_song = array_filter( $list_song );

			$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_album SET listsong=" . $db->quote( implode( ',', $list_song ) ) . " WHERE id=" . $array['album'];
			$db->query( $sql );
		}

		// Them loi bai hat moi
		if( ! empty( $array['lyric'] ) )
		{
			$array['lyric'] = nv_nl2br( $array['lyric'], "<br />" );

			$sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_lyric VALUES(
				NULL,
				" . $_songid . ",
				" . $db->quote( $array['username'] ) . ",
				" . $db->quote( $array['lyric'] ) . ",
				1,
				" . NV_CURRENTTIME . "
			)";
			$db->query( $sql );
		}
	}

	return $_songid;
}

// cau hinh module
function setting_music()
{
	global $module_name, $module_data, $db, $nv_Cache;

	$setting = array();

	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_setting";
	$result = $nv_Cache->db( $sql, 'id', $module_name );

	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( in_array( $row['config_key'], array( "root_contain", "description" ) ) )
			{
				$setting[$row['config_key']] = $row['chars'];
			}
			else
			{
				$setting[$row['config_key']] = $row['config_value'];
			}
		}
	}

	return $setting;
}

// lay song tu id
function getsongbyID( $id )
{
	global $module_data, $db;

	$song = array();
	$result = $db->query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id = " . $id );
	$song = $result->fetch();

	return $song;
}

// Lay album tu id
function getalbumbyID( $id )
{
	global $module_data, $db;

	$album = array();
	$result = $db->query( " SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id = " . $id );
	$album = $result->fetch();

	return $album;
}

// lay tat ca nhac si
function getallauthor( $reverse = false )
{
	global $module_name, $module_data, $db, $lang_module, $nv_Cache;
	$allsinger = array();
	if( $reverse === true )
	{
		$allsinger[$lang_module['unknow']] = 0;
	}
	else
	{
		$allsinger[0] = $lang_module['unknow'];
	}
	$sql = "SELECT id, tenthat FROM " . NV_PREFIXLANG . "_" . $module_data . "_author ORDER BY ten ASC";
	$result = $nv_Cache->db( $sql, 'ten', $module_name );
	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			if( $reverse === true )
			{
				$allsinger[$row['tenthat']] = $row['id'];
			}
			else
			{
				$allsinger[$row['id']] = $row['tenthat'];
			}
		}
	}
	return $allsinger;
}

// lay thong tin the loai video
function get_videocategory()
{
	global $module_name, $module_data, $db, $lang_module, $nv_Cache;
	$category = array();
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_video_category ORDER BY `weight` ASC";
	$result = $nv_Cache->db( $sql, 'id', $module_name );

	$category[0] = array(
		'id' => 0, //
		'title' => $lang_module['unknow'], //
		'keywords' => '', //
		'description' => '' //
	);
	if( ! empty( $result ) )
	{
		foreach( $result as $row )
		{
			$category[$row['id']] = array(
				'id' => $row['id'], //
				'title' => $row['title'], //
				'keywords' => $row['keywords'], //
				'description' => $row['description'] //
			);
		}
	}
	return $category;
}