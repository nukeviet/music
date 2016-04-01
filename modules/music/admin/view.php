<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (! defined('NV_IS_MUSIC_ADMIN')) {
    die('Stop!!!');
}

$array['id'] = $nv_Request->get_int('id', 'get,post', 0);
$array['area'] = $nv_Request->get_int('area', 'get,post', 0);

if ($array['id'] > 0) {
	if($module_info['funcs']['viewvideo']['func_id'] == $array['area']){
		$video = $db->query("SELECT id, name FROM " . NV_PREFIXLANG . "_" . $module_data . "_video WHERE id=" . $array['id'])->fetch();
		if($video){
			header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=viewvideo/' . $video['id'] . '/' . $video['name'], true));
			die();
		}
	}elseif($module_info['funcs']['listenlist']['func_id'] == $array['area']){
		$album = $db->query("SELECT id, name FROM " . NV_PREFIXLANG . "_" . $module_data . "_album WHERE id=" . $array['id'])->fetch();
		if($album){
			header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=listenlist/' . $album['id'] . '/' . $album['name'], true));
			die();
		}
	}elseif($module_info['funcs']['listenone']['func_id'] == $array['area']){
		$song = $db->query("SELECT id, ten FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=" . $array['id'])->fetch();
		if($song){
			header('Location:' . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=listenone/' . $song['id'] . '/' . $song['ten'], true));
			die();
		}
	}else{
		nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
	}
}
