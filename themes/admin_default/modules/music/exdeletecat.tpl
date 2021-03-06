<!-- BEGIN: main -->
<div class="infoalert">
	{LANG.ex_delete_cat_explain}
</div>
<div class="table-responsive">
	<table class="table table-striped table-bordered table-hover">
		<tbody>
			<tr>
				<td class="text-center">
					{LANG.ex_delete_cat_select_cat1}
					<select class="form-control" name="cat_delete">
						<!-- BEGIN: loop1 -->
						<option value="{CAT.id}">{CAT.title}</option>
						<!-- END: loop1 -->
					</select>
					{LANG.ex_delete_cat_select_cat2}
					<select class="form-control" name="cat_to">
						<!-- BEGIN: loop2 -->
						<option value="{CAT.id}">{CAT.title}</option>
						<!-- END: loop2 -->
					</select>
					<input type="button" name="doaction" value="{LANG.ex_do}"/>
					<script type="text/javascript">
					$(document).ready(function(){
						$('[name="doaction"]').click(function(){
							var cat1 = $('[name="cat_delete"]').val();
							var cat2 = $('[name="cat_to"]').val();
							if( cat1 == cat2 ){
								alert('{LANG.ex_delete_cat_error_same}');
								return;
							}
							if( confirm(nv_is_del_confirm[0]) ){
								$('[name="doaction"]').attr('disabled','disabled');
								$('#result').html('<center><img src="' + nv_base_siteurl + 'assets/images/load_bar.gif" alt=""/></center>');
								$.get('{URL}&get&cat1=' + cat1 + '&cat2=' + cat2,function(e){
									$('[name="doaction"]').removeAttr('disabled');
									$('#result').html(e);
								});
							}
						});
					});
					</script>
				</td>
			</tr>
		</tbody>
	</table>
</div>
<div id="result">

</div>
<!-- END: main -->