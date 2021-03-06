<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright 2011 Freeware
 * @Createdate 20/01/2011 11:56 AM
 */

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$id = 0;
$alias = '';

if( isset( $array_op[1] ) )
{
	$id = explode( "-", $array_op[1] );
	$id = intval( end( $id ) );
	$alias = preg_replace( "/\-([0-9]+)$/", "", $array_op[1] );
}

if( ! $id ) module_info_die();

$sql = "SELECT a.id AS id, a.ten AS ten, a.album AS album, a.tenthat AS tenthat, a.casi AS casi, a.nhacsi AS nhacsi, a.theloai AS theloai, a.listcat AS listcat, a.duongdan AS duongdan, a.upboi AS upboi, a.numview AS numview, a.server AS server, a.binhchon AS binhchon, a.hit AS hit, b.name AS name, b.tname AS tname, c.ten AS singeralias, c.tenthat AS singername, d.ten AS authoralias, d.tenthat AS authorname FROM " . NV_PREFIXLANG . "_" . $module_data . " AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_album AS b ON a.album=b.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS c ON a.casi=c.id LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_author AS d ON a.nhacsi=d.id WHERE a.id=" . $id . " AND a.active=1";
$result = $db->query( $sql );
$check_exit = $result->rowCount();
$row = $result->fetch();

if( $check_exit != 1 or $row['ten'] != $alias )
{
	module_info_die();
}

// Update
updateHIT_VIEW( $id, '', false );

// Global data
$category = $classMusic->get_category();

// Info user
$name = ( defined( 'NV_IS_USER' ) ) ? $user_info['username'] : "";

// Check HIT song
$checkhit = explode( "-", $row['hit'] );
$checkhit = $checkhit[0];

// All data
$gdata = array(); // Global data
$sdata = array(); // Song data
$cdata = array(); // Check data
$ldata = array(); // Lyric data

$cdata = array(
	"no_change" => ( $name == '' ) ? "" : " readonly=\"readonly\"", //
	"checkhit" => $checkhit, //
	"url_login" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] ), //
	"url_register" => NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register" //
);

$gdata = array(
	"username" => $name, //
	"data_url" => NV_BASE_SITEURL . "modules/" . $module_data . "/data/", //
	"full_data_url" => $global_config['site_url'] . "/modules/" . $module_data . "/data/", //
	"img_url" => NV_BASE_SITEURL . "themes/" . $module_info['template'] . "/images/" . $module_file . "/", //
	"download_url" => $downURL, //
	"ads_data" => getADS(), //
	"user_name" => $name, //
	"creat_link_url" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ), //
	"selfurl_base" => $client_info['selfurl'], //
	"selfurl_encode" => rawurlencode( $client_info['selfurl'] ), //
	"checksess_gift" => md5( "gift_" . $global_config['sitekey'] . session_id() ) //
);

if( ! empty( $row['listcat'] ) )
{
	$row['listcat'] = explode( ",", $row['listcat'] );
	$listcat = array();

	foreach( $row['listcat'] as $cat )
	{
		$cattitle = isset( $category[$cat] ) ? $category[$cat]['title'] : $category[0]['title'];

		$listcat[] = array( "title" => $cattitle, "url" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $cattitle ) . "&amp;id=" . $cat . "&amp;type=category" );
	}
}
else
{
	$listcat = array();
}

$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

$sdata = array(
	"send_mail_url" => $main_header_URL . "=sendmail&id=" . $id, //
	"send_mail_title" => $lang_module['sendtomail'], //

	"song_id" => $id, //
	"song_name" => $row['tenthat'], //
	"song_sname" => $row['ten'], //
	"song_singer" => $singername, //
	"song_singer_id" => $row['casi'], //
	"song_author" => $row['authorname'] ? $row['authorname'] : $lang_module['unknow'], //
	"song_cat" => isset($category[$row['theloai']]) ? $category[$row['theloai']]['title'] : '', //
	"song_listcat" => $listcat, //
	"song_vote" => $row['binhchon'], //
	"song_numview" => $row['numview'], //
	"song_link" => nv_url_rewrite( $main_header_URL . "=creatlinksong/song/" . $row['id'] . "/" . $row['ten'], true ), //

	"url_search_singer" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
	"url_search_category" => $mainURL . "=search&amp;where=song&amp;q=" . urlencode( $category[$row['theloai']]['title'] ) . "&amp;id=" . $row['theloai'] . "&amp;type=category", //
	"url_search_album" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $row['tname'] ) . "&amp;id=" . $row['album'], //

	"album_name" => $row['tname'] //
);

// Lyric data
$sqllyric = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE songid = " . $id . " AND active = 1 ORDER BY id DESC";
$querylyric = $db->query( $sqllyric );
$num_lyric = $querylyric->rowCount();

$ldata = array(
	"number" => $num_lyric, //
	"data" => array(), //
);

while( $rowlyric = $querylyric->fetch() )
{
	$ldata['data'][] = array( "user" => $rowlyric['user'], "content" => $rowlyric['body'] );
}

$array_album = $array_video = $array_singer = array();

if( $row['casi'] != 0 )
{
	// Danh sach album
	$sql = "SELECT id, name, tname, casi, thumb FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE casi=" . $row['casi'] . " AND is_active=1 ORDER BY addtime DESC LIMIT 0,4";
	$list = $nv_Cache->db( $sql, 'id', $module_name );

	foreach( $list as $r )
	{
		$array_album[] = array(
			"name" => $r['tname'], //
			"thumb" => $r['thumb'], //
			"url_listen" => $mainURL . "=listenlist/" . $r['id'] . "/" . $r['name'], //
			"url_search_singer" => $mainURL . "=search&amp;where=album&amp;q=" . urlencode( $singername ) . "&amp;id=" . $r['casi'] . "&amp;type=singer", //
		);
	}

	// Danh sach video
	$sql = "SELECT id, name, tname, casi, thumb FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE casi=" . $row['casi'] . " AND active=1 ORDER BY dt DESC LIMIT 0,3";
	$list = $nv_Cache->db( $sql, 'id', $module_name );

	foreach( $list as $r )
	{
		$array_video[] = array(
			"name" => $r['tname'], //
			"thumb" => $r['thumb'], //
			"url_listen" => $mainURL . "=viewvideo/" . $r['id'] . "/" . $r['name'], //
			"url_search_singer" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $r['casi'] . "&amp;type=singer", //
		);
	}

	// Chi tiet ca si
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id=" . $row['casi'] . " AND thumb!='' AND introduction!=''";
	$list = $nv_Cache->db( $sql, 'id', $module_name );

	foreach( $list as $r )
	{
		$array_singer = $r;
	}
}

// Page title
$page_title = $row['tenthat'] . " - " . $sdata['song_singer'];
$key_words = $row['tenthat'] . " - " . $sdata['song_singer'];
$description = ! isset( $ldata['data'][0]['content']{50} ) ? sprintf( $lang_module['share_descreption'], $row['tenthat'], $sdata['song_singer'], $sdata['song_author'], NV_MY_DOMAIN ) : $ldata['data'][0]['content'];

// comment
if( isset( $site_mods['comment'] ) and isset( $module_config[$module_name]['activecomm'] ) )
{
	define( 'NV_COMM_ID', $id ); //ID bài viết
	define( 'NV_COMM_AREA', $module_info['funcs'][$op]['func_id'] ); //để đáp ứng comment ở bất cứ đâu không cứ là bài viết
	$allowed = $module_config[$module_name]['allowed_comm']; //check allow comemnt
	//tuy vào module để lấy cấu hình. Nếu là module news thì có cấu hình theo bài viết
	if( $allowed == '-1' )
	{
		$allowed = 6;
	}
	define( 'NV_PER_PAGE_COMMENT', 5 );

	//Số bản ghi hiển thị bình luận
	require_once NV_ROOTDIR . '/modules/comment/comment.php';
	$area = ( defined( 'NV_COMM_AREA' )) ? NV_COMM_AREA : 0;
	$checkss = md5( $module_name . '-' . $area . '-' . NV_COMM_ID . '-' . $allowed . '-' . NV_CACHE_PREFIX );

	//get url comment
	$url_info = parse_url( $client_info['selfurl'] );
	$content_comment = nv_comment_module( $module_name, $checkss, $area, NV_COMM_ID, $allowed, 1 );
}
else
{
	$content_comment = '';
}

$contents = nv_music_listenone( $gdata, $sdata, $cdata, $ldata, $array_album, $array_video, $array_singer, $content_comment );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';