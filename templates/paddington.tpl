<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="{$lang}">
<head>
<title>{$title|escape:"html"}</title>
<meta http-equiv="Content-type" content="text/html; charset={$charset}">
<meta name="robots" content="{$robots}">
{if $favicon}<link rel="shortcut icon" href="{$favicon}">{/if}
<link rel="stylesheet" href="{$stylepath}/paddington.css">
<script type="text/javascript" src="{$stylepath}/wikibits.js"></script>
</head>

<body class="encyclopedia">
<div id="content">

<!-- ************************************* topbar -->

<div id="topbar">
<h1 class="sitename">{wikimsg key="sitetitle"} <span>{$langname}</span></h1>
<div class="spacer"></div>

<div class="navbox" id="navboxsubtitle">
<div class="row" id="searchrow">
	<span class="left">
	{wikimsg key="sitesubtitle"}</span>
	<span class="right">
	<form name="search" class="inline" method=get action="{$searchurl}">
	<input type=text name="search" size="19" value="">
	<input type=submit name="go" value="{wikimsg key="go"}">&nbsp;<input type="submit" value="{wikimsg key="search"}">
	</form>
	</span>
</div><!--searchrow-->
<div class="spacer"></div>
</div><!--navboxsubtitle-->

<div class="navbox" id="sitelinks">
<div class="spacer"></div>
<div class="row" id="sitelinksrow">
	<span class="left">
		{wikilink keypage="mainpage"} |
		{wikilink special="Recentchanges"} |
		{wikilink keypage="helppage"}
	</span>
	<span class="right">
	{if $loggedin}
		{wikilink name=$userpage} ({wikilink name=$userpage talk=true}) |
		{wikilink special="Userlogout" returnto=$thispage} |
		{wikilink special="Preferences"}
	{else}
		{wikimsg key="notloggedin"} |
		{wikilink special="Userlogin"}
	{/if}
	</span>
</div>
	{if $newtalk}<div class="alert">{$newtalk}</div>{/if}
	<div class="spacer"></div>
</div><!--sitelinks-->

</div><!-- ************************************* topbar -->

<!-- ************************************* article -->
<div id="article">
	<h1 class="pagetitle">{$title|escape:"html"}</h1>

	<p class="subtitle">{$subtitle}</p>

	<p class="actions">{wikilink name=$thispage printable=true key="printable"} |
	{if $editable}
	{wikilink name=$title action="edit" key=$editpage}
	{if $exists} | {wikilink name=$title action="history" key="history"}{/if}
	{/if}</p>

	<p class="languages">{wikimsg key="otherlanguages"} {$langlist}</p>


{$bodytext}

</div><!--article-->

<div id="pagestats">
{$pagestats}
</div>

</div><!-- id="content" -->

<!-- ************************************* quickbar -->

<div id="quickbar-left">
<div class="quickbarsection">
	{$logo}
</div>
<div class="quickbarsection">
	{wikilink keypage="mainpage"}
	<br>{wikilink special="Recentchanges"}
	<br>{wikilink special="Random"}
	{if $loggedin}<br>{wikilink special="Watchlist"}{/if}
	<br>{wikilink special="Contributions" target=$username}
	<br>{wikilink keypage="currentevents"}
</div>
{if $editable}<div class="quickbarsection">
	<strong>{wikilink name=$thispage action="edit" key=$editpage}</strong>
	{if $loggedin}
		<br>{wikilink name=$thispage action="watch" key=$watchpage}
		{if $exists}
			<br>{wikilink special="Movepage" target=$thispage}
			{if $sysop}
				<br>{wikilink name=$thispage action="delete" key="deletepage"}
				<br>{wikilink name=$thispage action=$protect key=$protect}
			{/if}
		{/if}
	{/if}
	<br>{wikilink name=$thispage talk=true key=$talkpage}
	{if $exists}<br>{wikilink name=$thispage action="history" key="history"}{/if}
	<br>{wikilink special="Whatlinkshere" target=$thispage}
	<br>{wikilink special="Recentchangeslinked" target=$thispage}
</div>{/if}
<div class="quickbarsection">
	{if $loggedin}{wikilink special="Upload"}
	<br>{/if}{wikilink special="Specialpages"}
	<br>{wikilink keypage="bugreports"}
</div>
</div><!-- end quickbar -->

</body></html>

