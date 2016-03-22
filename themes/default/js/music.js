/* *
 * @Project NUKEVIET-MUSIC
 * @Author PHAN TAN DUNG (phantandung92@gmail.com)
 * @Copyright (C) 2011 Freeware
 * @Createdate 26/09/2011 5:12 PM
 */

// Bao ket qua
function resultgift(res){
	alert(res);
	return false;
}

// Gui bao loi cho quan tri
function senderror(id, where){
	var root_error = document.getElementById('root_error').value;
	var user = document.getElementById('user');
	var body  = strip_tags(document.getElementById('bodyerror').value);
	if (user.value == "") {
		alert(nv_fullname);
		user.focus();
	} else if ( (body == "") && ( root_error == "" ) ) {
		alert(nv_content);
		document.getElementById('bodyerror').focus();
	} else {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'senderror=1&id=' + id + '&where=' + where + '&user=' + user.value + '&root_error=' + root_error + '&body=' + encodeURIComponent(body), function(res) {
			resultgift(res);
		});
	}
	return;
}

// Them paylist
function addplaylist(id)
{
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'addplaylist=1&id=' + id, function(res) {
		resultplaylist(res);
	});
	return;
}
function resultplaylist(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=showplaylist&nocache=' + new Date().getTime(), '', function(res) {
			$('#'+playlist).html(res);
		});
	} else alert(res);
}

// Xoa paylist (dang luu trong phien lam viec hien tai)
function delplaylist()
{
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'delplaylist=1', function(res) {
		resultgift(res);
	});
	return;
}

// Gui binh luan
function sendcommment(id, where) {
	var name = document.getElementById('name');
	var body = strip_tags(document.getElementById('commentbody').value);
	if (name.value == "") {
		alert(nv_fullname);
		name.focus();
	} else if (body == "") {
		alert(nv_content);
		document.getElementById('commentbody').focus();
	} else {
		var sd = document.getElementById('button-comment');
		sd.disabled = true;
		$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=postcomment&nocache=' + new Date().getTime(), 'id=' + id + '&where=' + where + '&name=' + name.value + '&body=' + encodeURIComponent(body), function(res) {
			comment_result(res);
		});
	}
	return;
}
// tra ve sau khi binh luan
function comment_result(res) {
	var r_split = res.split("_");
	if (r_split[0] == 'OK') {
		document.getElementById('commentbody').value = "";
		show_comment(r_split[1], r_split[2], 0);
		alert(r_split[3]);
	} else if (r_split[0] == 'ERR') {
		alert(r_split[1]);
	} else {
		alert(nv_content_failed);
	}
	nv_set_disable_false('button-comment');
	return false;
}

// Hien thi cac binh luan
function show_comment(id, where, page) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=comment&nocache=' + new Date().getTime(), 'id=' + id + '&where=' + where + '&page=' + page, function(res) {
		$('#comment-content').html(res);
	});
}

// Luu album
function saveplaylist(name, singer, message){
	document.getElementById('submitpl').disabled = true;
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'savealbum=1&name=' + name + '&singer=' + singer + '&message=' + encodeURIComponent(message), function(res) {
		aftersavelist(res);
	});
}
function aftersavelist(res){
	var r_split = res.split("_");
	if (r_split[0] == 1){
		alert(r_split[1]);
		window.location = r_split[2];
	}else{
		document.getElementById('submitpl').disabled = false;
		alert(res);
	}
}

// Xoa mot bai hat tu playlist chua luu
function delsongfrlist(stt, mess) {
	if ( confirm( mess ) )
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'delsongfrlist=1&stt=' + stt, function(res) {
		afterdelsong(res);
	});
}

// Xoa mot bai hat tu playlist da luu
function delsongfrplaylist(id, plid, mess) {
	if ( confirm( mess ) )
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'delsongfrplaylist=1&id=' + id + '&plid=' + plid, function(res) {
		afterdelsong(res);
	});
}

// Xoa mot bai hat cua thanh vien
function delsong(id, mess) {
	if ( confirm ( mess ) )
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'delsong=1&id=' + id, function(res) {
		afterdelsong(res);
	});
}
function afterdelsong(res)
{
	var r_split = res.split("_");

	if( r_split[0] == "OK" )
	{
		element = document.getElementById("song" + r_split[1]);
		element.parentNode.removeChild(element);
	}
	else
	{
		alert( res );
	}
}

// Xoa playlist da duoc luu vao CSDL
function dellist(id, mess) {
	if( confirm( mess ) )
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'dellist=1&id=' + id, function(res) {
		afterdellist(res);
	});
}
function afterdellist(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		element = document.getElementById("item" + r_split[1]);
		element.parentNode.removeChild(element);
	} else alert(res);
}

// Binh chon bai hat
function votethissong( id ) {
	$.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=data&nocache=' + new Date().getTime(), 'votesong=1&id=' + id, function(res) {
		aftervote(res);
	});
}
function aftervote(res)
{
	var r_split = res.split("_");
	if (r_split[0] == "OK") {
		document.getElementById("vote").innerHTML = "(" + r_split[1] + ")";
	}
	alert(r_split[2]);
}


// an hien div
function ShowHide(what)
{
	$("#"+what+"").animate({"height": "toggle"}, { duration: 1 });
}

// Kiem tra thoi gian thuc hien
function nvms_check_timeout(ckname,timeout,lang){
	var timeout_old = nv_getCookie(ckname);
	var timeout_new = new Date();
	timeout_new = timeout_new.getTime();
	if((timeout_old != null)&&((timeout_new - timeout_old)<timeout)){
		alert(lang);
		return false;
	}
	//nv_setCookie(ckname, timeout_new);
	return true;
}