<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/01/2011 09:09 AM
 */

if( ! defined( 'NV_IS_MUSIC_ADMIN' ) )
{
	die( 'Stop!!!' );
}

$id = $nv_Request->get_int( 'id', 'get', 0 );
$where = $nv_Request->get_title( 'where', 'get', '' );

if( empty( $id ) ) die( "Stop!!!" );
if( ! in_array( $where, array(
	"",
	"_album",
	"_ftp",
	"_gift",
	"_lyric",
	"_playlist",
	"_video" ) ) ) die( "Stop!!!" );

$sql = "SELECT active FROM " . NV_PREFIXLANG . "_" . $module_data . $where . " WHERE id =" . $id;
$result = $db->query( $sql );
$active = $result->fetchColumn();
$active = ( $active == 1 ) ? 0 : 1;
$sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . $where . " SET active = " . $db->quote( $active ) . " WHERE id =" . $id;
$db->query( $sql );

if( $where == '_ftp' )
{
	updatewhendelFTP( $id, $active );
}

$str = ( $active == 1 ) ? $lang_module['active_yes'] : $lang_module['active_no'];

$nv_Cache->delMod( $module_name );

echo $lang_module['active_succer'] . " \"" . $str . " \"";