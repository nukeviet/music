<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>VIDEO</strong>
		</div>
	</div>
	<h5 style="line-height:25px;margin-bottom:-2px;" >&nbsp;&nbsp; <a href="{thisurl}">VIDEO</a> / <a href="{DATA.url_search_category}">{DATA.category}</a> / {DATA.name}</h5>
	<div style="padding:6px 0px 6px 6px;" class="playercontainer">
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
			jwplayer("player").setup({
			flashplayer: "{base_url}player.swf",
			file: "{DATA.link}", image: "{ads}" ,
			controlbar: "bottom",
			volume: 100,
			height: 360,
			width: 605,
			autostart: "true",
			repeat: "list"
			});
		</script>
		<div class="clear"></div>
	</div>
	<br />
	<h3>&nbsp;{DATA.name} - <a href="{DATA.url_search_singer}">{DATA.singer}</a> <span style="font-size:12px;"><em>({LANG.view1}: {DATA.view})</em></span></h3>
</div>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
			<strong>{LANG.send_to}</strong>
		</div>
	</div>
<div style="width:618px;" class="tab_container">
	<div id="tab1" class="tab_content">
		<div class="sendtool">
			<a onclick="this.target='_blank';" href="http://www.facebook.com/sharer.php?u={DATA.URL_SONG}&t={DATA.name}-{DATA.singer}" class="facebook">Facebook   </a>
			<a class="sendtomail" href="javascript:void(0);" onclick="NewWindow('{DATA.URL_SENDMAIL}','{DATA.TITLE}','500','500','no');return false">{LANG.sendtomail}</a>
		</div>
		<form style="width:100%;" action="#" method="post">
			<p>{LANG.video_link}:
			<input style="width:490px;" id="linksong" onclick="SelectAll('linksong');" type="text" value="{DATA.URL_SONG}" readonly="readonly" /> </p>
			<p>{LANG.blog_song}:
			<input style="width:490px;" id="blogsong" onclick="SelectAll('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;350&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{playerurl}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;file={DATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=1&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{playerurl}player.swf&quot; width=&quot;500&quot; height=&quot;350&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;file={DATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=1&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly" /> </p>
			<p>{LANG.forum_song}:
			<input style="width:490px;" id="songforum" onClick="SelectAll('songforum');" type="text" value="[FLASH]{playerurl}player.swf?file={DATA.creat_link_url}[/FLASH]" readonly="readonly" /> </p>
			<script type="text/javascript">
				function SelectAll(id)
				{
					document.getElementById(id).focus();
					document.getElementById(id).select();
				}
			</script>
		</form>
		<!-- BEGIN: nogift -->
		<p style="width:100%;float:left;" align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nogift -->
	</div> 
</div>
</div>

<div style="height:10px;">&nbsp;</div>
<div class="box-border-shadow m-bottom">
	<div class="cat-box-header"> 
		<div class="cat-nav"> 
		<strong>{LANG.comment}</strong>
		</div>
	</div>
	<div id="main">
		<div id="size" class="commentcontainer"></div>
		<!-- BEGIN: comment -->
		<div class="commentcontainerbo">
			<div id="addCommentContainer">
				<form id="addCommentForm" method="post" action="">
					<div>
						<label for="name">{LANG.your_name}:</label>
						<input style="width: 500px;" type="text" name="name" id="name" value="{USER_NAME}" {NO_CHANGE} />
						<textarea style="width: 500px;" name="body" id="commentbody" cols="20" rows="5"></textarea>
						<label for="body">{LANG.content}:</label>
						<div>
						<input style="width: 50px;float:left;margin-left:86px" class="submitbutton" type="button" id="buttoncontent" value="{LANG.send}" onclick="sendcommment( '{DATA.ID}' , 'video' );" />
						<input id="showemotion" style="width: 130px;float:left;margin-left:6px" class="submitbutton" type="button"  value="{LANG.emotion}" />
						<div class="clear"></div>
						<div style="position:relative;">
						<div id="emotion">{EMOTIONS}</div>
						<script type="text/javascript" src="{base_url}showemotion.js"></script>
						</div>
						</div>
					</div>
				</form>
			</div>
		</div>
		<!-- END: comment -->
		<!-- BEGIN: nocomment -->
		<p align="center"><strong>{LANG.you_must} <a href="{USER_LOGIN}">{LANG.loginsubmit}</a> / <a href="{USER_REGISTER}">{LANG.register}</a> {LANG.to_access}</strong></p>
		<!-- END: nocomment -->
		<!-- BEGIN: stopcomment -->
		<p align="center"><em>{LANG.setting_stop}</em></p>
		<!-- END: stopcomment -->
	</div>
	<div class="clear"></div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	show_comment( '{DATA.ID}' , 'video', 0 );
});
</script>
<!-- END: main -->