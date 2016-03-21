<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div style="width: 98%;" class="quote">
    <blockquote class="error"><span>{ERROR}</span></blockquote>
</div>
<div class="clear"></div>
<!-- END: error -->
<form class="form-inline" action="{FORM_ACTION}" method="post">
	<div class="table-responsive">
		<table class="table table-striped table-bordered table-hover">
			<col width="200px"/>
			<tbody>
				<tr>
					<td colspan="2"><strong class="toupper">{LANG.video_info}</strong></td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.video_name}<span class="requie"> (*)</span></strong></td>
					<td>
						<input type="text" class="form-control music-input txt-half" id="idtitle" name="tname" value="{DATA.tname}" original-title="{LANG.tip_video_title}"/>
						<img class="middle" width="16" height="16" alt="get" onclick="get_alias('idtitle','res_get_alias');" src="{NV_BASE_SITEURL}images/refresh.png"/>
					</td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.video_name_short}<span class="requie"> (*)</span></strong></td>
					<td><input type="text" class="form-control music-input txt-half" id="idalias" name="name" value="{DATA.name}" original-title="{LANG.tip_alias}"/></td>
				</tr>
				<tr>
					<td class="aright atop"><strong>{LANG.singer}</strong></td>
					<td>
						<input type="hidden" name="casi" value="{LISTSINGERS}"/>
						<p>
							<strong>
								<a href="javascript:void(0);" id="addonesinger" class="nounderline add-icon">{LANG.singer_add2}</a>
								<a href="javascript:void(0);" id="addlistsinger" class="nounderline list-icon">{LANG.singer_add3}</a>
								<a href="javascript:void(0);" class="nounderline note-icon tooltip" original-title="{LANG.tip_video_singer}">{LANG.info}</a>
							</strong>
						</p>
						<ul id="listsingers-area" class="fixbg list_song">
							<!-- BEGIN: singer -->
							<li class="{SINGER.id}">
								{SINGER.title}<span onclick="nv_del_item_on_list({SINGER.id}, 'listsingers-area', '{LANG.author_del_confirm}', 'casi')" class="delete-icon">&nbsp;</span>
							</li>
							<!-- END: singer -->
						</ul>
					</td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.singer_new}</strong></td>
					<td><input type="text" class="form-control music-input txt-half" name="casimoi" value="{DATA.casimoi}" original-title="{LANG.tip_new_singer}"/></td>
				</tr>
				<tr>
					<td class="aright atop"><strong>{LANG.author}</strong></td>
					<td>
						<input type="hidden" name="nhacsi" value="{LISTAUTHORS}"/>
						<p>
							<strong>
								<a href="javascript:void(0);" id="addoneauthor" class="nounderline add-icon">{LANG.author_add1}</a>
								<a href="javascript:void(0);" id="addlistauthor" class="nounderline list-icon">{LANG.author_add2}</a>
								<a href="javascript:void(0);" class="nounderline note-icon tooltip" original-title="{LANG.tip_video_author}">{LANG.info}</a>
							</strong>
						</p>
						<ul id="listauthors-area" class="fixbg list_song">
							<!-- BEGIN: author -->
							<li class="{AUTHOR.id}">
								{AUTHOR.title}<span onclick="nv_del_item_on_list({AUTHOR.id}, 'listauthors-area', '{LANG.author_del_confirm}', 'nhacsi')" class="delete-icon">&nbsp;</span>
							</li>
							<!-- END: author -->
						</ul>
					</td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.author_new}</strong></td>
					<td><input type="text" class="form-control music-input txt-half" name="nhacsimoi" value="{DATA.nhacsimoi}" original-title="{LANG.tip_new_author}"/></td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.category_base}</strong></td>
					<td>
						<select class="form-control music-input" name="theloai">
							<!-- BEGIN: theloai -->
							<option value="{THELOAI.id}"{THELOAI.selected}>{THELOAI.title}</option>
							<!-- END: theloai -->
						</select>
					</td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.category_sub}</strong></td>
					<td>
						<div class="autoscroll">
							<!-- BEGIN: listcat -->
							<label><input type="checkbox" name="listcat[]" value="{THELOAI.id}"{THELOAI.checked}{THELOAI.disabled}/> {THELOAI.title}</label>
							<!-- END: listcat -->
							<div class="clear"></div>
						</div>
					</td>
				</tr>
				<tr>
					<td class="aright"><strong>{LANG.link}<span class="requie"> (*)</span></strong></td>
					<td>
						<input type="text" class="form-control music-input txt-half" id="duongdan" name="duongdan" value="{DATA.duongdan}"/>
						<input type="button" class="music-button-2" name="select" value="{LANG.select}" />
					</td>
				</tr>
				<tr>
					<td class="strong aright">{LANG.thumb}<span class="requie"> (*)</span></td>
					<td>
						<input id="thumb" name="thumb" class="form-control music-input txt-half" value="{DATA.thumb}" type="text" readonly="readonly"/>
						<input name="selectthumb" type="button" value="{LANG.select}" class="music-button-2"/>
					</td>
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td colspan="2" class="text-center"><input type="submit" name="submit" value="{LANG.save}" class="music-button"/></td>
				</tr>
			</tfoot>
		</table>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('.music-input').tipsy({
		trigger: 'focus',
		gravity: 'e'
	});
	$('.tooltip').tipsy({
		trigger: 'hover',
		gravity: 's'
	});
	$("input[name=select]").click(function(){
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=upload&popup=1&area=duongdan&path={FILE_DIR}", "NVImg", "850", "500", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
		return false;
	});
	$("input[name=selectthumb]").click(function(){
		nv_open_browse('{NV_BASE_ADMINURL}index.php?' + nv_name_variable + '=upload&popup=1&area=thumb&path={IMG_DIR}&type=image', 'NVImg', '850', '500', 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no');
		return false;
	});
	<!-- BEGIN: auto_get_alias -->
	$("#idtitle").change(function(){
		get_alias('idtitle', 'res_get_alias');
	});
	<!-- END: auto_get_alias -->
	
	$( "#listsingers-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listsingers-area', 'casi'); }
	});
	$( "#listsingers-area" ).disableSelection();
	
	$( "#listauthors-area" ).sortable({
		cursor: "crosshair",
		update: function(event, ui) { nv_sort_item('listauthors-area', 'nhacsi'); }
	});
	$( "#listauthors-area" ).disableSelection();
	
	$("a#addonesinger").click(function(){
		var singers = $("input[name=casi]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=singer&findOneAndReturn=1&area=listsingers-area&input=casi&singers=" + singers, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistsinger").click(function(){
		var singers = $("input[name=casi]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=singer&findListAndReturn=1&area=listsingers-area&input=casi&singers=" + singers, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	
	$("a#addoneauthor").click(function(){
		var authors = $("input[name=nhacsi]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=author&findOneAndReturn=1&area=listauthors-area&input=nhacsi&authors=" + authors, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$("a#addlistauthor").click(function(){
		var authors = $("input[name=nhacsi]").attr("value");
		nv_open_browse( "{NV_BASE_ADMINURL}index.php?" + nv_name_variable + "=" + nv_module_name + "&" + nv_fc_variable + "=author&findListAndReturn=1&area=listauthors-area&input=nhacsi&authors=" + authors, "NVImg", "850", "600", "resizable=no,scrollbars=no,toolbar=no,location=no,status=no" );
	});
	$('[name="theloai"]').change(function(){
		var val = $(this).val();
		$.each($('[name="listcat[]"]'), function(){
			if( $(this).val() == val ){
				$(this).attr('disabled', 'disabled');
				$(this).removeAttr('checked');
			}else{
				$(this).removeAttr('disabled');
			}
		});
	});
});
</script>
<!-- END: main -->