<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2010 VINADES.,JSC. All rights reserved
 * @Createdate 7-17-2010 14:43
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
	die( 'Stop!!!' );
}

$where = $nv_Request->get_title( 'where', 'get,post', '' );
$listall = $nv_Request->get_string( 'listall', 'post,get', 0 );
$array_id = explode( ',', $listall );
$array_id = array_map( "intval", $array_id );
$ok = false;

foreach( $array_id as $id )
{
	if( $id > 0 )
	{
		$sql = "SELECT active FROM " . NV_PREFIXLANG . "_" . $module_data . $where . " WHERE id =" . $id . ";";
		$result = $db->query( $sql );
		$active = $result->fetchColumn();
		$active = ( $active == 1 ) ? 0 : 1;
		$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . $where . " SET active = " . $db->quote( $active ) . " WHERE id =" . $id . ";";
		if( ! $db->query( $sql ) )
		{
			echo $lang_module['active_error'];
			exit;
		}
	}
	else
	{
		echo $lang_module['active_error'];
		exit;
	}
}

echo $lang_module['active_succer1'];