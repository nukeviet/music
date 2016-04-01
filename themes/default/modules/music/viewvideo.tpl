<!-- BEGIN: main -->
<script type="text/javascript" src="{base_url}jwplayer.js"></script>
<h2 class="medium greencolor mlotitle">{DATA.name} - <a class="singer" href="{DATA.url_search_singer}" title="{DATA.singer}">{DATA.singer}</a><!-- BEGIN: hit --><span class="musicicon mhit hitsong">&nbsp;&nbsp;&nbsp;</span><!-- END: hit --></h2>
<p class="msmall">
	{LANG.category_2}: <a class="singer" href="{DATA.url_search_category}" title="{DATA.category}">{DATA.category}</a>
	<!-- BEGIN: subcat --> / <a class="singer" href="{SUBCAT.url}" title="{SUBCAT.name}">{SUBCAT.name}&nbsp;</a>
	<!-- END: subcat --> | {LANG.view1}: {DATA.view}
</p>
<div class="alboxw">
	<div class="alwrap">
		<div id="player">Loading the player...</div>
		<script type="text/javascript">
		var player_width = $('#player').width();
		var player_height = parseInt(player_width * 9.65 / 16);
		jwplayer("player").setup({
			flashplayer: "{base_url}player.swf", file: "{DATA.duongdan}", image: "{ads}", controlbar: "bottom",menu:false,
			volume: 100, height: player_height, width: player_width, repeat: "always", autostart: "true"
		});
		</script>
	</div>
</div>
<script type="text/javascript" src="https://apis.google.com/js/plusone.js">{lang: '{NV_LANG_INTERFACE}'}</script>
<script type="text/javascript">
var url_share = u=location.href;
var title_share = document.title;
function share_facebook(){window.open("http://www.facebook.com/share.php?u="+encodeURIComponent(url_share)+"&t="+encodeURIComponent(title_share))}
function share_yahoo(){window.open("http://bookmarks.yahoo.com/toolbar/savebm?opener=tb&u="+encodeURIComponent(url_share)+"&t="+encodeURIComponent(title_share)+"&d=")}
function share_zingme(){window.open("http://link.apps.zing.vn/share?url="+encodeURIComponent(url_share)+"&title="+encodeURIComponent(title_share))}
</script>
<ul class="mlo-tool fl">
	<li class="tool"><a href="javascript:void(0);">{LANG.send_to}</a></li>
</ul>
<ul class="mlo-tool fr">
	<li><a class="musicicon mfacebook" href="javascript:void(0);" onclick="share_facebook();"></a></li>
	<li><a class="musicicon mzingme" href="javascript:void(0);" onclick="share_zingme();"></a></li>
	<li><a class="musicicon myahoo" href="javascript:void(0);" onclick="share_yahoo();"></a></li>
	<li><g:plusone size="small" annotation="none"></g:plusone></li>
	<li><a rel="nofollow" class="musicicon memail" href="javascript:void(0);" onclick="nv_open_browse('{DATA.URL_SENDMAIL}','{DATA.TITLE}','500','500','no');return false" ></a></li>
</ul>
<div class="clear"></div>
<!-- Gift Tab -->
<div class="alboxw mg10 tab_content" id="tabs1">
	<div class="alwrap alcontent">
		<table cellpadding="0" cellspacing="0" class="musictable">
			<tr>
				<td class="left">{LANG.video_link}:</td>
				<td><input class="form-control" id="linksong" onclick="Select_all('linksong');" type="text" value="{DATA.URL_SONG}" readonly="readonly"/></td>
			</tr>
			<tr>
				<td class="left">{LANG.blog_song}:</td>
				<td><input class="form-control" id="blogsong" onclick="Select_all('blogsong');" type="text" value="&lt;object id=&quot;player&quot; classid=&quot;clsid:D27CDB6E-AE6D-11cf-96B8-444553540000&quot; name=&quot;player&quot; width=&quot;500&quot; height=&quot;350&quot;&gt; &lt;param name=&quot;movie&quot; value=&quot;{playerurl}player.swf&quot; /&gt; &lt;param name=&quot;allowfullscreen&quot; value=&quot;false&quot; /&gt; &lt;param name=&quot;allowscriptaccess&quot; value=&quot;always&quot; /&gt; &lt;param name=&quot;flashvars&quot; value=&quot;file={DATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=1&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt; &lt;embed  type=&quot;application/x-shockwave-flash&quot; id=&quot;player2&quot; name=&quot;player2&quot; src=&quot;{playerurl}player.swf&quot; width=&quot;500&quot; height=&quot;350&quot; allowscriptaccess=&quot;always&quot; allowfullscreen=&quot;false&quot; flashvars=&quot;file={DATA.creat_link_url}&amp;amp;bufferlength=10&amp;amp;volume=100&amp;amp;playlist=bottom&amp;amp;playlistsize=1&amp;amp;autostart=true&amp;amp;repeat=always&amp;amp;controlbar=bottom&amp;amp;dock=false&quot; /&gt;&lt;/object&gt;" readonly="readonly"/></td>
			</tr>
			<tr>
				<td class="left">{LANG.forum_song}:</td>
				<td><input class="form-control" id="songforum" onClick="Select_all('songforum');" type="text" value="[FLASH]{playerurl}player.swf?file={DATA.creat_link_url}[/FLASH]" readonly="readonly"/></td>
			</tr>
		</table>
	</div>
</div>
<!-- SINGER INFO -->
<!-- BEGIN: singer_info -->
<div class="mg10 alwrap alcontent">
	<div class="ms-shwrap">
		<div class="ms-shimg">
			<img class="fl musicsmalllalbum" src="{SINGER_INFO.thumb}" alt="{SINGER_INFO.tenthat}" width="100" height="100"/>
		</div>
		<div class="ms-shdetail" id="singersdetail">
			<h2 class="medium greencolor">{SINGER_INFO.tenthat}</h2>
			{SINGER_INFO.introduction}
		</div>
		<div class="ms-shshow">
			<a href="javascript:void(0);" rel="0|{LANG.view_expand}|{LANG.view_collapse}|singersdetail" class="musicicon zoomin greencolor ms-shd" title="{LANG.view_expand}">{LANG.view_expand}</a>
		</div>
		<div class="clear"></div>
	</div>
</div>
<!-- END: singer_info -->
<!-- OTHER ALBUM -->
<!-- BEGIN: other_album -->
<div class="mg10 alwrap alcontent">
	<strong>{LANG.album} {DATA.singer}</strong>
	<div class="hr"></div>
	<!-- BEGIN: loop -->
	<div class="col-25 mcenter">
		<a href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}"><img src="{ROW.thumb}" alt="{ROW.name}" width="100" height="100" class="musicsmalllalbum"/></a><br />
		<a class="singer" href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}">{ROW.name}</a><br />
		<a href="{ROW.url_search_singer}" title="{LANG.search_width} {DATA.singer}" class="msmall">{DATA.singer}</a>
	</div>
	<!-- END: loop -->
	<div class="clear"></div>
	<div class="hr"></div>
	<div class="alright"><a href="{SEARCH_ALL_ALBUM}" title="{LANG.view_all}" class="musicicon mforward">{LANG.view_all}</a></div>
</div>
<!-- END: other_album -->
<!-- OTHER VIDEO -->
<!-- BEGIN: other_video -->
<div class="mg10 alwrap alcontent">
	<strong>{LANG.video} {DATA.singer}</strong>
	<div class="hr"></div>
	<!-- BEGIN: loop -->
	<div class="col-33 mcenter">
		<a href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}"><img src="{ROW.thumb}" alt="{ROW.name}" width="128" height="72" class="musicsmalllalbum"/></a><br />
		<a class="singer" href="{ROW.url_listen}" title="{LANG.listen_album} {ROW.name}">{ROW.name}</a><br />
		<a href="{ROW.url_search_singer}" title="{LANG.search_width} {DATA.singer}" class="msmall">{DATA.singer}</a>
	</div>
	<!-- END: loop -->
	<div class="clear"></div>
	<div class="hr"></div>
	<div class="alright"><a href="{SEARCH_ALL_VIDEO}" title="{LANG.view_all}" class="musicicon mforward">{LANG.view_all}</a></div>
</div>
<!-- END: other_video -->

<!-- BEGIN: comment -->
{COMMENT}
<!-- END: comment -->

<!-- END: main -->