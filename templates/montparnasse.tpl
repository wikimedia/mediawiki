<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<html lang="{$lang}">
<head><title>{$title|escape:"html"}</title>
<meta http-equiv="Content-type" content="text/html; charset={$charset}">
<meta name="robots" content="{$robots}">
{if $favicon}<link rel="shortcut icon" href="{$favicon}">{/if}
<link rel="stylesheet" href="{$stylepath}/montparnasse.css">
</head>

<body class="encyclopedia">
<div id="content">

<!-- ************************************* topbar -->
<div id="topbar">
<h1 class="sitename">{wikimsg key="sitetitle"}<span> - {wikimsg key="sitesubtitle"} - {$langname}</span></h1>

<div>{wikilink keypage="mainpage"} -
	{wikilink special="Recentchanges"} -
	{wikilink special="Randompage"} -
	{wikilink keypage="currentevents"} -
	{if $loggedin}{wikilink special="Userlogout"}{else}{wikilink special="Userlogin"}{/if}
	{if $newtalk}- <span id="alert">{$newtalk}</span>{/if}</div>
</div>

<!-- ************************************* article  -->
<div id="main">
<div id="article">
<h1 class="pagetitle">{$title|escape:"html"}</h1>
	<p class="actions">{wikilink name=$thispage printable=true key="printable"}
	{if $editable}
	| {wikilink name=$thispage action="edit" key=$editpage}
	{if $exists}| {wikilink name=$thispage action="history" key="pagehistory"}{/if}
	{/if}</p>

{$bodytext}

</div id="article">

<div id="sidebar">
  <div id="search">
  <div id="dummylogo">logo placeholder</div>
	{wikimsg key="searchwiki"}
	<form name="search" class="inline" method="get" action="{$searchaction}">
	<input type="text" name="search" size="19" value=""><br>
	<input type=submit name="go" value="{wikimsg key="go"}">&nbsp;<input type="submit" value="{wikimsg key="search"}">
	</form>
  </div>

<h2>{wikimsg key="otherlanguages"}</h2>

	<div class="languages">{$otherlanguages}</div>

<h2>{wikimsg key="sitelinks"}</h2>
<div>
	{wikilink keypage="mainpage"}<br>
	{wikilink special="Recentchanges"}<br>
	{wikilink special="Randompage"}<br>
	{wikilink keypage="currentevents"}
</div>

<h2>{wikimsg key="personaltools"}</h2>
<div>
{if $loggedin}
	{wikilink name=$userpage key="myuserpage"}<br>
	{wikilink name=$userpage talk=true key="mytalkpage"}<br>
	{wikilink special="Watchlist"}<br>
	{wikilink special="Contributions" target=$username}
	{wikilink special="Userlogout"}
{else}
	{wikimsg key="notloggedin"}<br>
	{wikilink special="Userlogin"}
{/if}
</div>


</div id="sidebar">
</div id="main">

<div id="footer">
	<h2>{wikimsg key="searchwiki"}</h2><div>
		<form name="search" class="inline" method=get action="{$searchaction}">
		<input type=text name="search" size="19" value="">
		<input type=submit name="go" value="Go">&nbsp;<input type=submit value="Search">
		<!-- ** namespace checkboxes here would be nice! -->
		</form>
	</div>
{if $editable}
	<h2>{wikimsg key="pagetools"}</h2><div>
		<strong>{wikilink name=$thispage action="edit" key=$editpage}</strong> |
		{if $loggedin}{wikilink name=$thispage action=$watch key=$watch} |{/if}
		{wikilink name=$thispage talk=true key=$talkpage} |
		{if $exists}{wikilink name=$thispage action="history" key="pagehistory"} |{/if}
		{wikilink special="Whatlinkshere" target=$thispage} |
		{wikilink special="Recentchangeslinked" target=$thispage} |
	{if $loggedin && $exists}
		<br>
	{if $sysop}
		{wikilink name=$thispage action="delete" key="deletepage"} |
		{wikilink name=$thispage action=$protect key=$protect} |
	{/if}
		{wikilink special="Movepage" target="$thispage"}
	{/if}
	</div>
{/if}
<div id="pagestats">
{$pagestats}
</div>

</div id="footer">
</body>
</html>
