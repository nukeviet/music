<!-- BEGIN: main --><!-- BEGIN: prev --><a style="cursor:pointer" onclick="show_comment( '{GDATA.id}' , '{GDATA.where}', {prev} );">{LANG.prev}</a><!-- END: prev --><!-- BEGIN: next --><a style="cursor:pointer" onclick="show_comment( '{GDATA.id}' , '{GDATA.where}', {next} );">{LANG.next}</a><!-- END: next --><!-- BEGIN: loop -->	<div class="comment">		<div class="avatar">			<img alt="{ROW.name}" src="{ROW.avatar}" width="50" height="50" />		</div>		<div class="name">{ROW.name}</div>		<div class="date">{ROW.date}</div>		<div>{ROW.body}</div>		<div class="clear"></div>	</div><!-- END: loop --><!-- END: main -->