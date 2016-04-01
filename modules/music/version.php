<?php

/**
 * @Project NUKEVIET-MUSIC
 * @Author Phan Tan Dung (phantandung92@gmail.com)
 * @copyright 2011 Freeware
 * @createdate 05/12/2010 09:47
 */

if( ! defined( 'NV_ADMIN' ) or ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

$module_version = array(
	"name" => "NukeViet Music",
	"modfuncs" => "main, listenone, listenlist, search, playlist, album, song, creatalbum, listenuserlist, allplaylist, viewvideo, video, upload, searchvideo, editplaylist, managersong, gift, down",
	"is_sysmod" => 0,
	"virtual" => 1,
	"version" => "4.0.27",
	"date" => "Sun, 08 Apr 2012 00:00:00 GMT",
	"author" => "PHAN TAN DUNG (phantandung92@gmail.com)",
	"note" => "",
	"uploads_dir" => array(
		$module_upload,
		$module_upload . "/data",
		$module_upload . "/clipthumb",
		$module_upload . "/thumb",
		$module_upload . "/data/video",
		$module_upload . "/data/upload",
		$module_upload . "/ads",
		$module_upload . "/tmp",
		$module_upload . "/singerthumb",
		$module_upload . "/authorthumb"
	)
);