<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright Freeware
 * @createdate 05/12/2010 09:47
 */

if ( ! defined( 'NV_IS_MOD_MUSIC' ) ) die( 'Stop!!!' );

global $module_file, $module_info, $mainURL, $lang_module, $db, $module_data, $module_name, $setting, $main_header_URL, $op, $downURL, $allsinger, $category;

$xtpl = new XTemplate( "block_tabs_song_center.tpl", NV_ROOTDIR . "/themes/" . $module_info['template'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'LOAD_URL', $main_header_URL . "=" . $op . "&loadblocktabsong=" );
$xtpl->assign( 'URL_DOWN', $downURL );
$xtpl->assign( 'ALL_NEW_SONG', $mainURL . "=song/id" );

// Lay du lieu
$sql = "SELECT a.id, a.cid, b.title FROM `" . NV_PREFIXLANG . "_" . $module_data . "_4category` AS a INNER JOIN `" . NV_PREFIXLANG . "_" . $module_data . "_category` AS b ON a.cid=b.id ORDER BY a.id ASC";
$list = nv_db_cache( $sql, 'id', $module_name );

$item_width = sizeof( $list );
$item_width =  $item_width ? ( 100 / $item_width ) : 100;
$xtpl->assign( 'ITEM_WIDTH', $item_width );

$first_cat = 0;

// Xuat tabs
if( ! empty( $list ) )
{
	foreach( $list as $cat )
	{
		if( empty( $first_cat ) ) $first_cat = $cat['cid'];
		$xtpl->assign( 'CAT', $cat );
		$xtpl->parse( 'main.catdata.loop' );
	}
	$xtpl->parse( 'main.catdata' );
}

if ( $nv_Request->isset_request( 'loadblocktabsong', 'get' ) )
{
	$id = $nv_Request->get_int( 'loadblocktabsong', 'get', 0 );
	$sql = "SELECT `cid` FROM `" . NV_PREFIXLANG . "_" . $module_data . "_4category` WHERE `id`=" . $id;
	$result = $db->sql_query( $sql );
	list( $cid ) = $db->sql_fetchrow( $result );
	if( empty( $cid ) ) nv_info_die( $lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'] );
	
	$first_cat = $cid;
}

// Xuat bai hat cua Tab dau tien
if( ! empty( $first_cat ) )
{
	$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE `theloai`=" . $first_cat . " AND `active`=1 ORDER BY `dt` DESC LIMIT 0," . $setting['num_blocktab'];
	
	$list = nv_db_cache( $sql, 'id', $module_name );
	
	if( ! empty( $list ) )
	{
		if( empty( $allsinger ) ) $allsinger = getallsinger();
		if( empty( $category ) ) $category = get_category();
		
		foreach( $list as $row )
		{
			$xtpl->assign( 'ID', $row['id'] );
			$xtpl->assign( 'name', $row['tenthat'] );
			$xtpl->assign( 'singer', $allsinger[$row['casi']] );
			$xtpl->assign( 'category', $category[$row['theloai']]['title'] );
			$xtpl->assign( 'who_upload', $row['upboi'] );
			$xtpl->assign( 'view', $row['numview'] );
			$xtpl->assign( 'url_view', nv_url_rewrite( $main_header_URL . "=listenone/" . $row['id'] . "/" . $row['ten'], true ) );
		
			$xtpl->assign( 'url_search_singer', nv_url_rewrite( $main_header_URL . "=search/singer/" . $row['casi'], true ) );
			$xtpl->assign( 'url_search_category', nv_url_rewrite( $main_header_URL . "=search/category/" . $row['theloai'], true ) );
			$xtpl->assign( 'url_search_upload', nv_url_rewrite( $main_header_URL . "=search/upload/" . $row['upboi'], true ) );
		
			$xtpl->parse( 'main.songdata.loop' );	
		}
	}
	
	$xtpl->parse( 'main.songdata' );
	
	if ( $nv_Request->isset_request( 'loadblocktabsong', 'get' ) ) die( $xtpl->text( 'main.songdata' ) );
}

$xtpl->parse( 'main' );
$content = $xtpl->text( 'main' );

?>