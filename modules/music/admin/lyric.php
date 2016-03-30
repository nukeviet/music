<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26-01-2011 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Xoa lyric
if ( $nv_Request->isset_request( 'del', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $list_levelid = $nv_Request->get_title( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $list_levelid ) ) die( 'NO' );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$list_levelid = explode ( ",", $list_levelid );
		$list_levelid = array_map ( "trim", $list_levelid );
		$list_levelid = array_filter ( $list_levelid );

		$listid = $list_levelid;
		$num = sizeof( $list_levelid );
	}
	
	$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE id IN(" . implode( ", ", $listid ) . ")";
	$db->query( $sql );
    
    $nv_Cache->delMod( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, 'Delete Lyric', implode( ", ", $listid ), $admin_info['userid'] );
	
    die( 'OK' );
}

// Thay doi hoat dong lyric
if ( $nv_Request->isset_request( 'changestatus', 'post' ) )
{
    if ( ! defined( 'NV_IS_AJAX' ) ) die( 'Wrong URL' );
    
    $id = $nv_Request->get_int( 'id', 'post', 0 );
    $controlstatus = $nv_Request->get_int( 'status', 'post', 0 );
    $array_id = $nv_Request->get_title( 'listid', 'post', '' );
    
    if ( empty( $id ) and empty ( $array_id ) ) die( 'NO' );
    
	$listid = array();
	if ( $id )
	{
		$listid[] = $id;
		$num = 1;
	}
	else
	{
		$array_id = explode ( ",", $array_id );
		$array_id = array_map ( "trim", $array_id );
		$array_id = array_filter ( $array_id );

		$listid = $array_id;
		$num = sizeof( $array_id );
	}
	
	$sql = "SELECT id, active FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE id IN(" . implode( ", ", $listid ) . ")";
	$result = $db->query( $sql );
	
	$array_status = array();
	while( list( $id, $active ) = $result->fetch( 3 ) )
	{
		if ( empty ( $controlstatus ) )
		{
			$array_status[$id] = $active ? 0 : 1;
		}
		else
		{
			$array_status[$id] = ( $controlstatus == 1 ) ? 1 : 0;
		}
	}
	
	foreach( $array_status as $id => $active )
	{
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_lyric SET active=" . $active . " WHERE id=" . $id;
		$db->query( $sql );	
	}	
    
    $nv_Cache->delMod( $module_name );
	
    die( 'OK' );
}

// Tieu de trang
$page_title = $classMusic->lang('add_lyric');

// Thong tin phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

// Query, url co so
$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_lyric WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
	"q" => nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, 100),
	"song" => nv_substr( $nv_Request->get_title( 'song', 'get', '', 1 ), 0, 100),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty ( $data_search['q'] ) or ! empty ( $data_search['song'] ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND body LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
}

if( ! empty ( $data_search['song'] ) )
{
	$base_url .= "&amp;song=" . urlencode( $data_search['song'] );
	$songid = $classMusic->search_song_id( $data_search['song'], 5 );
	
	if( ! empty( $songid ) )
	{
		$sql .= " AND songid IN(" . implode( ",", $songid ) . ")";
	}
	else
	{
		$sql .= " AND songid=0";
	}
}

// Du lieu sap xep
$order = array();
$check_order = array( "ASC", "DESC", "NO" );
$opposite_order = array(
	"NO" => "ASC",
	"DESC" => "ASC",
	"ASC" => "DESC"
);
$lang_order_1 = array(
	"NO" => $classMusic->lang('filter_lang_asc'),
	"DESC" => $classMusic->lang('filter_lang_asc'),
	"ASC" => $classMusic->lang('filter_lang_desc')
);
$lang_order_2 = array(
	"addtime" => $classMusic->lang('playlist_time')
);

$order['addtime']['order'] = $nv_Request->get_title( 'order_addtime', 'get', 'NO' );

foreach ( $order as $key => $check )
{
	$order[$key]['data'] = array(
		"class" => "order" . strtolower ( $order[$key]['order'] ),
		"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
		"title" => sprintf ( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
	);
	
	if ( ! in_array ( $check['order'], $check_order ) )
	{
		$order[$key]['order'] = "NO";
	}
	else
	{
		$base_url .= "&amp;order_" . $key . "=" . $order[$key]['order'];
	}
}

if( $order['addtime']['order'] != "NO" )
{
	$sql .= " ORDER BY dt " . $order['addtime']['order'];
}
else
{
	$sql .= " ORDER BY id DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

// Xay dung du lieu album
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->query( $sql );

$array = $array_songs = $array_song_ids = array();

while( $row = $result->fetch() )
{
	$array_song_ids[$row['songid']] = $row['songid'];
	
	$array[] = array(
		"id" => $row['id'],
		"user" => $row['user'],
		"body" => nv_clean60( strip_tags( nv_br2nl( $row['body'] ) ), 400 ),
		"song" => $row['songid'],
		"addtime" => nv_date( "H:i d/m/Y", $row['dt'] ),
		"status" => $row['active'] ? " checked=\"checked\"" : "",
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-lyric&amp;id=" . $row['id'],
		"class" => ( $i % 2 == 0 ) ? " class=\"second\"" : ""
	);
	$i ++;
}

// Cac thao tac
$list_action = array(
	0 => array(
		"key" => 1,
		"class" => "delete",
		"title" => $classMusic->glang('delete')
	),
	1 => array(
		"key" => 2,
		"class" => "status-ok",
		"title" => $classMusic->lang('action_status_ok')
	),
	2 => array(
		"key" => 3,
		"class" => "status-no",
		"title" => $classMusic->lang('action_status_no')
	)
);

// Phan trang
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "lyric.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
$xtpl->assign( 'LANG', $lang_module );
$xtpl->assign( 'GLANG', $lang_global );
$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
$xtpl->assign( 'MODULE_NAME', $module_name );
$xtpl->assign( 'OP', $op );
$xtpl->assign( 'DATA_SEARCH', $data_search );
$xtpl->assign( 'DATA_ORDER', $order );
$xtpl->assign( 'URL_CANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name  . "&" . NV_OP_VARIABLE . "=" . $op );

foreach( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

if( ! empty( $array_song_ids ) )
{
	$array_songs = $classMusic->getsongbyID( $array_song_ids );
}

foreach( $array as $row )
{
	$row['song'] = isset( $array_songs[$row['song']] ) ? $array_songs[$row['song']]['tenthat'] : $classMusic->lang('unknow');

	$xtpl->assign( 'ROW', $row );
	$xtpl->parse( 'main.row' );
}

if( ! empty( $generate_page ) )
{
    $xtpl->assign( 'GENERATE_PAGE', $generate_page );
    $xtpl->parse( 'main.generate_page' );
}

$xtpl->parse( 'main' );
$contents = $xtpl->text( 'main' );

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme( $contents );
include NV_ROOTDIR . '/includes/footer.php';