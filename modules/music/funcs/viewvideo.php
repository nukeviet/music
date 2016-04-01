<?php

/* *
* @Project NUKEVIET-MUSIC
* @Author Phan Tan Dung (phantandung92@gmail.com)
* @Copyright (C) 2011 Freeware
* @Createdate 26/01/2011 10:12 AM
*/

if( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

$category = get_videocategory();
$setting = setting_music();

$g_array = array();
$g_array['name'] = defined( 'NV_IS_USER' ) ? $user_info['username'] : '';
$g_array['user_login'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=login&amp;nv_redirect=" . nv_base64_encode( $client_info['selfurl'] );
$g_array['user_register'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=users&amp;" . NV_OP_VARIABLE . "=register";

// Lay video
$id = isset( $array_op[1] ) ? intval( $array_op[1] ) : 0;

if( empty( $id ) ) module_info_die();

$sql = "SELECT a.*, b.ten AS singeralias, b.tenthat AS singername FROM " . NV_PREFIXLANG . "_" . $module_data . "_video AS a LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_singer AS b ON a.casi=b.id WHERE a.id=" . $id . " AND a.active=1";
$result = $db->query( $sql );
$row = $result->fetch();

if( empty( $row ) ) module_info_die();

updateHIT_VIEW( $id, '_video', false );

$row['listcat'] = empty( $row['listcat'] ) ? array() : array_diff( array_filter( array_unique( explode( ",", $row['listcat'] ) ) ), array( $row['theloai'] ) );
if( ! empty( $row['listcat'] ) )
{
	$list_cat = $row['listcat'];
	$row['listcat'] = array();
	foreach( $list_cat as $cat )
	{
		$catname = isset( $category[$cat] ) ? $category[$cat]['title'] : $category[0]['title'];

		$row['listcat'][] = array( "name" => $catname, "url" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $catname ) . "&amp;id=" . $cat . "&amp;type=category" );
	}
}

$singername = $row['singername'] ? $row['singername'] : $lang_module['unknow'];

// Check HIT
$checkhit = explode( "-", $row['hit'] );
$checkhit = $checkhit[0];

$array = array(
	"URL_SENDMAIL" => $mainURL . "=videosendmail&amp;id=" . $id, //
	"TITLE" => $lang_module['sendtomail'], //
	"ID" => $id, //
	"name" => $row['tname'], //
	"thumb" => $row['thumb'], //
	"listcat" => $row['listcat'], //
	"sname" => $row['name'], //
	"singer" => $singername, //
	"singerid" => $row['casi'], //
	"category" => $category[$row['theloai']]['title'], //
	"view" => $row['view'], //
	"checkhit" => $checkhit, //
	"creat_link_url" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . '=creatlinksong/video/' . $row['id'] . '/' . $row['name'], true ), //
	"url_search_singer" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $singername ) . "&amp;id=" . $row['casi'] . "&amp;type=singer", //
	"url_search_category" => $mainURL . "=search&amp;where=video&amp;q=" . urlencode( $category[$row['theloai']]['title'] ) . "&amp;id=" . $row['theloai'] . "&amp;type=category", //
	"link" => nv_url_rewrite( $main_header_URL . "=creatlinksong/video/" . $row['id'] . "/" . $row['name'], true ), //
	"duongdan" => $row['duongdan'],
	"URL_SONG" => NV_MY_DOMAIN . nv_url_rewrite( $main_header_URL . '=viewvideo/' . $row['id'] . '/' . $row['name'], true ) //
);

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

// Tieu de trang
$page_title = $row['tname'] . " - " . $array['singer'];
$key_words = $row['tname'] . " - " . $array['singer'];

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

$contents = nv_music_viewvideo( $g_array, $array, $array_album, $array_video, $array_singer, $content_comment );

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';