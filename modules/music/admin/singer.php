<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) ) die( 'Stop!!!' );

// Tim kiem va them mot ca si
if( $nv_Request->isset_request( 'findOneAndReturn', 'get' ) )
{
	$singers = nv_substr( $nv_Request->get_title( 'singers', 'get', '', 1 ), 0, 255);
	$returnArea = nv_substr( $nv_Request->get_title( 'area', 'get', '', 1 ), 0, 255);
	$returnInput = nv_substr( $nv_Request->get_title( 'input', 'get', '', 1 ), 0, 255);

	$page_title = $classMusic->lang('getsingerid_title');
	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 15;
	$array = array();

	// SQL va LINK co ban
	$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id!=0";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op . "&amp;findOneAndReturn=1&amp;area=" . $returnArea . "&amp;input=" . $returnInput . "&amp;singers=" . $singers;

	// Du lieu tim kiem
	$data_search = array( "q" => nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, 255 ));

	if( ! empty( $singers ) ) $sql .= " AND id NOT IN(" . $singers . ")";

	if( ! empty( $data_search['q'] ) )
	{
		$base_url .= "&amp;q=" . $data_search['q'];
		$sql .= " AND ( tenthat LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%' )";
	}

	// Order data
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
		"ASC" => $classMusic->lang('filter_lang_desc'),
	);
	$lang_order_2 = array(
		"title" => $classMusic->lang('filter_singer'),
	);

	$order['title']['order'] = $nv_Request->get_title( 'order_title', 'get', 'NO' );

	foreach( $order as $key => $check )
	{
		if( ! in_array( $check['order'], $check_order ) )
		{
			$order[$key]['order'] = "NO";
		}

		$order[$key]['data'] = array(
			"class" => "order" . strtolower( $order[$key]['order'] ),
			"url" => $base_url . "&amp;order_" . $key . "=" . $opposite_order[$order[$key]['order']],
			"title" => sprintf( $lang_module['filter_order_by'], "&quot;" . $lang_order_2[$key] . "&quot;" ) . " " . $lang_order_1[$order[$key]['order']]
		);
	}

	if( $order['title']['order'] != "NO" )
	{
		$sql .= " ORDER BY tenthat " . $order['title']['order'];
	}
	else
	{
		$sql .= " ORDER BY id DESC";
	}

	$array = array();

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->query( $sql1 );
	$all_page = $result1->fetchColumn();

	$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$result = $db->query( $sql );

	while( $row = $result->fetch() )
	{
		$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"thumb" => $row['thumb'],
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

	$xtpl = new XTemplate( "find_one_singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'SINGERS', $singers );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );
	$xtpl->assign( 'FORM_ACTION', NV_BASE_ADMINURL . "index.php?" );
	$xtpl->assign( 'DATA_ORDER', $order );
	$xtpl->assign( 'SEARCH', $data_search );
	$xtpl->assign( 'URLCANCEL', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findOneAndReturn=1&area=" . $returnArea . "&input=" . $returnInput . "&singers=" . $singers );

	foreach( $array as $row )
	{
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
	echo $contents;
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

// Tim kiem va them nhieu ca si
if( $nv_Request->isset_request( 'findListAndReturn', 'get' ) )
{
	$singers = nv_substr( $nv_Request->get_title( 'singers', 'get', '', 1 ), 0, 255);

	$returnArea = nv_substr( $nv_Request->get_title( 'area', 'get', '', 1 ), 0, 255);
	$returnInput = nv_substr( $nv_Request->get_title( 'input', 'get', '', 1 ), 0, 255);

	if( $nv_Request->isset_request( 'loadname', 'get' ) )
	{
		$sql = "SELECT id, tenthat FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id IN(" . $singers . ")";
		$result = $db->query( $sql );

		$list_singer = array();
		$_tmp = array();
		while( list( $singerid, $singername ) = $result->fetch( 3 ) )
		{
			$_tmp[$singerid] = $singername;
		}

		$singers = $classMusic->string2array( $singers );
		foreach( $singers as $_sid )
		{
			if( isset( $_tmp[$_sid] ) ) $list_singer[$_sid] = $_tmp[$_sid];
		}

		$return = "";
		foreach( $list_singer as $_id => $_name )
		{
			$return .= "<li class=\"" . $_id . "\">" . $_name . "<span onclick=\"nv_del_item_on_list(" . $_id . ", '" . $returnArea . "', '" . $classMusic->lang('author_del_confirm') . "', '" . $returnInput . "')\" class=\"delete-icon\">&nbsp;</span></li>";
		}

		include NV_ROOTDIR . '/includes/header.php';
		echo ( $return );
		include NV_ROOTDIR . '/includes/footer.php';
		die();
	}

	$singers = $classMusic->string2array( $singers );

	$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer";
	$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op . "&findListAndReturn=1";

	$sql1 = "SELECT COUNT(*) " . $sql;
	$result1 = $db->query( $sql1 );
	$all_page = $result1->fetchColumn();

	$sql .= " ORDER BY id DESC";

	$page = $nv_Request->get_int( 'page', 'get', 0 );
	$per_page = 5;

	$sql2 = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
	$query2 = $db->query( $sql2 );

	$array = array();
	while( $row = $query2->fetch() )
	{
		$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";

		$array[$row['id']] = array(
			"id" => $row['id'],
			"title" => $row['tenthat'],
			"thumb" => $row['thumb'],
			"checked" => in_array( $row['id'], $singers ) ? " checked=\"checked\"" : ""
		);
	}

	$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page, true, true, "nv_load_page", "data" );

	$xtpl = new XTemplate( "find_list_singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
	$xtpl->assign( 'LANG', $lang_module );
	$xtpl->assign( 'GLANG', $lang_global );
	$xtpl->assign( 'GLOBAL_CONFIG', $global_config );
	$xtpl->assign( 'NV_LANG_INTERFACE', NV_LANG_INTERFACE );
	$xtpl->assign( 'NV_BASE_SITEURL', NV_BASE_SITEURL );
	$xtpl->assign( 'NV_BASE_ADMINURL', NV_BASE_ADMINURL );
	$xtpl->assign( 'NV_NAME_VARIABLE', NV_NAME_VARIABLE );
	$xtpl->assign( 'NV_OP_VARIABLE', NV_OP_VARIABLE );
	$xtpl->assign( 'OP', $op );
	$xtpl->assign( 'MODULE_NAME', $module_name );
	$xtpl->assign( 'MODULE_FILE', $module_file );
	$xtpl->assign( 'SINGERS', implode( ",", $singers ) );
	$xtpl->assign( 'RETURNINPUT', $returnInput );
	$xtpl->assign( 'RETURNAREA', $returnArea );

	if( ! empty( $array ) )
	{
		foreach( $array as $row )
		{
			$xtpl->assign( 'ROW', $row );
			$xtpl->parse( 'main.data.row' );
		}

		if( ! empty( $generate_page ) )
		{
			$xtpl->assign( 'GENERATE_PAGE', $generate_page );
			$xtpl->parse( 'main.data.generate_page' );
		}

		$xtpl->parse( 'main.data' );
	}

	if( $nv_Request->isset_request( 'getdata', 'get' ) )
	{
		$contents = $xtpl->text( 'main.data' );
	}
	else
	{
		$xtpl->parse( 'main' );
		$contents = $xtpl->text( 'main' );
	}

	include NV_ROOTDIR . '/includes/header.php';
	echo ( $contents );
	include NV_ROOTDIR . '/includes/footer.php';
	die();
}

// Xoa ca si
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

	$singers = $classMusic->getsingerbyID( $listid );

	if( sizeof( $singers ) != $num ) die( 'NO' );

	foreach( $singers as $id => $singer )
	{
		$sql = "DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id=" . $id;
		$db->query( $sql );
	}

    $nv_Cache->delMod( $module_name );
	nv_insert_logs( NV_LANG_DATA, $module_name, $classMusic->lang('delete_singer'), implode( ", ", array_keys( $singers ) ), $admin_info['userid'] );

    die( 'OK' );
}

// Tieu de trang
$page_title = $classMusic->lang('singer_list');

// Goi Shadowbox
$classMusic->callJqueryPlugin('shadowbox');

// Thong tin phan trang
$page = $nv_Request->get_int( 'page', 'get', 0 );
$per_page = 50;

// Query, url co so
$sql = "FROM " . NV_PREFIXLANG . "_" . $module_data . "_singer WHERE id!=0";
$base_url = NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op;

// Du lieu tim kiem
$data_search = array(
	"q" => nv_substr( $nv_Request->get_title( 'q', 'get', '', 1 ), 0, 100),
	"disabled" => " disabled=\"disabled\""
);

// Cam an nut huy tim kiem
if( ! empty ( $data_search['q'] ) or ! empty ( $data_search['singer'] ) )
{
	$data_search['disabled'] = "";
}

// Query tim kiem
if( ! empty ( $data_search['q'] ) )
{
	$base_url .= "&amp;q=" . urlencode( $data_search['q'] );
	$sql .= " AND tenthat LIKE '%" . $db->dblikeescape( $data_search['q'] ) . "%'";
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
	"title" => $classMusic->lang('song_name'),
	"numsong" => $classMusic->lang('siteinfo_numsong'),
	"numalbum" => $classMusic->lang('siteinfo_numalbum'),
	"numvideo" => $classMusic->lang('siteinfo_numvideo'),
);

$order['title']['order'] = $nv_Request->get_title( 'order_title', 'get', 'NO' );
$order['numsong']['order'] = $nv_Request->get_title( 'order_numsong', 'get', 'NO' );
$order['numalbum']['order'] = $nv_Request->get_title( 'order_numalbum', 'get', 'NO' );
$order['numvideo']['order'] = $nv_Request->get_title( 'order_numvideo', 'get', 'NO' );

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

if( $order['title']['order'] != "NO" )
{
	$sql .= " ORDER BY tenthat " . $order['title']['order'];
}
elseif( $order['numsong']['order'] != "NO" )
{
	$sql .= " ORDER BY numsong " . $order['numsong']['order'];
}
elseif( $order['numalbum']['order'] != "NO" )
{
	$sql .= " ORDER BY numalbum " . $order['numalbum']['order'];
}
elseif( $order['numvideo']['order'] != "NO" )
{
	$sql .= " ORDER BY numvideo " . $order['numvideo']['order'];
}
else
{
	$sql .= " ORDER BY id DESC";
}

// Lay so row
$sql1 = "SELECT COUNT(*) " . $sql;
$result1 = $db->query( $sql1 );
$all_page = $result1->fetchColumn();

// Xay dung du lieu
$i = 1;
$sql = "SELECT * " . $sql . " LIMIT " . $page . ", " . $per_page;
$result = $db->query( $sql );

$array = array();
while( $row = $result->fetch() )
{
	$row['thumb'] = $row['thumb'] ? $row['thumb'] : NV_BASE_SITEURL . "themes/" . $global_config['module_theme'] . "/images/" . $module_file . "/d-avatar.gif";

	$array[] = array(
		"id" => $row['id'],
		"title" => $row['tenthat'],
		"thumb" => $row['thumb'],
		"numsong" => $row['numsong'],
		"numalbum" => $row['numalbum'],
		"numvideo" => $row['numvideo'],
		"url_edit" => NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-singer&amp;id=" . $row['id'],
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
);

// Phan trang
$generate_page = nv_generate_page( $base_url, $all_page, $per_page, $page );

$xtpl = new XTemplate( "singer.tpl", NV_ROOTDIR . "/themes/" . $global_config['module_theme'] . "/modules/" . $module_file );
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
$xtpl->assign( 'URL_ADD', NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=content-singer" );

foreach( $list_action as $action )
{
	$xtpl->assign( 'ACTION', $action );
	$xtpl->parse( 'main.action' );
}

foreach( $array as $row )
{
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