<?php
/** Tibetan (བོད་ཡིག)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Freeyak
 */

$digitTransformTable = array(
	'0' => '༠', # &#x0f20;
	'1' => '༡', # &#x0f21;
	'2' => '༢', # &#x0f22;
	'3' => '༣', # &#x0f23;
	'4' => '༤', # &#x0f24;
	'5' => '༥', # &#x0f25;
	'6' => '༦', # &#x0f26;
	'7' => '༧', # &#x0f27;
	'8' => '༨', # &#x0f28;
	'9' => '༩', # &#x0f29;
);

$messages = array(
# User preference toggles
'tog-underline'             => 'འོག་ཐིག་འཐེན་པ།',
'tog-highlightbroken'       => 'ནང་འབྲེལ་མཚམས་ཐིག་<a hred="" class="new">ལྟ་བུ།</a>(ཡང་ན།<a href=""class="internal">?ལྟ་བུ།</a>)',
'tog-justify'               => 'ཚིག་གི་ཚད་སྙོམས་པ།',
'tog-hideminor'             => 'རྩོམ་སྒྲིག་ཆུང་ཚགས་སྦས་བ།',
'tog-hidepatrolled'         => 'ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་སྦས་བ།',
'tog-newpageshidepatrolled' => 'ཤོག་ངོས་གསར་བར་ལྟ་ཞིབ་བྱས་པའི་རྩོམ་སྒྲིག་སྦས་བ།',

'underline-always'  => 'ནམ་ཡང་།',
'underline-never'   => 'ནམ་ཡང་མིན།',
'underline-default' => 'རྒྱས་བ་འདྲེན་པ།',

# Font style option in Special:Preferences
'editfont-style'     => 'རྩོམ་སྒྲིག་ཡིག་གཟུགས།',
'editfont-default'   => 'རྒྱས་པ་འདྲེན་པ།',
'editfont-monospace' => 'བར་ཚད་མཉམ་པའི་ཡིག་གཟུགས།',
'editfont-sansserif' => 'ཡིག་གཟུགས་རྭ་མེད།',
'editfont-serif'     => 'ཡིག་གཟུགས་རྭ་ཅན།',

# Dates
'sunday'        => 'གཟའ་ཉི་མ།',
'monday'        => 'གཟའ་ཟླ་བ།',
'tuesday'       => 'གཟའ་མིག་དམར།',
'wednesday'     => 'གཟའ་ལྷག་པ།',
'thursday'      => 'གཟའ་ཕུར་བུ།',
'friday'        => 'གཟའ་པ་སངས།',
'saturday'      => 'གཟའ་སྤེན་པ།',
'sun'           => 'གཟའ་ཉི་མ།',
'mon'           => 'གཟའ་ཟླ་བ།',
'tue'           => 'གཟའ་མིག་དམར།',
'wed'           => 'གཟའ་ལྷག་པ།',
'thu'           => 'གཟའ་ཕུར་བུ།',
'fri'           => 'གཟའ་པ་སངས།',
'sat'           => 'གཟའ་སྤེན་པ།',
'january'       => 'སྤྱི་ཟླ་དང་པོ།',
'february'      => 'སྤྱི་ཟླ་གཉིས་པ།',
'march'         => 'སྤྱི་ཟླ་གསུམ་པ།',
'april'         => 'སྤྱི་ཟླ་བཞི་བ།',
'may_long'      => 'སྤྱི་ཟླ་ལྔ་བ།',
'june'          => 'སྤྱི་ཟླ་དྲུག་པ།',
'july'          => 'སྤྱི་ཟླ་བདུན་པ།',
'august'        => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'september'     => 'སྤྱི་ཟླ་དགུ་བ།',
'october'       => 'སྤྱི་ཟླ་བཅུ་བ།',
'november'      => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'december'      => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',
'january-gen'   => 'སྤྱི་ཟླ་དང་པོ།',
'february-gen'  => 'ཟླ་གཉིས་པ།',
'march-gen'     => 'ཟླ་གསུམ།',
'april-gen'     => 'ཟླ་བཞི་བ།',
'may-gen'       => 'ཟླ་ལྔ་བ།',
'june-gen'      => 'ཟླ་དྲུག་པ།',
'july-gen'      => 'ཟླ་བདུན་པ།',
'august-gen'    => 'ཟླ་བརྒྱད་པ།',
'september-gen' => 'ཟླ་དགུ་བ།',
'october-gen'   => 'ཟླ་བཅུ་བ།',
'november-gen'  => 'ཟླ་བཅུ་གཅིག་པ།',
'december-gen'  => 'ཟླ་བཅུ་གཉིས་པ།',
'jan'           => 'སྤྱི་ཟླ་དང་པོ།',
'feb'           => 'སྤྱི་ཟླ་གཉིས་པ།',
'mar'           => 'སྤྱི་ཟླ་གསུམ་པ།',
'apr'           => 'སྤྱི་ཟླ་བཞི་བ།',
'may'           => 'སྤྱི་ཟླ་ལྔ་བ།',
'jun'           => 'སྤྱི་ཟླ་དྲུག་པ།',
'jul'           => 'སྤྱི་ཟླ་བདུན་པ།',
'aug'           => 'སྤྱི་ཟླ་བརྒྱད་པ།',
'sep'           => 'སྤྱི་ཟླ་དགུ་བ།',
'oct'           => 'སྤྱི་ཟླ་བཅུ་བ།',
'nov'           => 'སྤྱི་ཟླ་བཅུ་གཅིག་པ།',
'dec'           => 'སྤྱི་ཟླ་བཅུ་གཉིས་པ།',

# Categories related messages
'pagecategories' => 'རིགས་$1',
'subcategories'  => 'རིགས་ཕལ་བ།',

'about'      => 'ཨཱབོཨུཏ་',
'newwindow'  => '(སྒེའུ་ཁུང་གསར་བར་ཕྱེ་བ།)',
'cancel'     => 'དོར་བ།',
'mytalk'     => 'ངའི་གླེང་མོལ།',
'navigation' => 'ལམ་སྟོན།',

# Cologne Blue skin
'qbedit'         => 'རྩོམ་སྒྲིག',
'qbspecialpages' => 'དམིཊ་བསལ་གྱི་བཟོ་བཅོས།',

'tagline'          => '{{SITENAME}}འབྲེལ་གནས།',
'help'             => 'རོགས་རམ།',
'search'           => 'འཚོལ་བ།',
'searchbutton'     => 'འཚོལ་བ།',
'go'               => 'སོང་།',
'searcharticle'    => 'སོང་།',
'history'          => 'ཤོག་ངོས་ལོ་རྒྱུས།',
'history_short'    => 'རྗེས་ཐོ།',
'printableversion' => 'དཔར་ཐུབ་པ།',
'permalink'        => 'རྟག་བརྟན་གྱི་དྲ་འབྲེལ།',
'edit'             => 'རྩོམ་སྒྲིག',
'create'           => 'བཟོ་སྐྲུན།',
'delete'           => 'གསུབ་པ།',
'protect'          => 'སྲུང་བ།',
'protect_change'   => 'སྒྱུར་བཅོས།',
'newpage'          => 'ཤོག་ངོས་གསར་བ།',
'talkpagelinktext' => 'གླེང་མོལ།',
'personaltools'    => 'སྒེར་ཀྱི་ལག་ཆ།',
'talk'             => 'གྲོས་བསྡུར།',
'views'            => 'སྣང་ཚུལ།',
'toolbox'          => 'ལག་ཆ།',
'otherlanguages'   => 'སྐད་རིགས་གཞན།',
'lastmodifiedat'   => 'དྲ་ངོས་འདི་$1ཉིན་$2ལ་བཅོས་པའི་བསྒྱུར་བཅོས་མཐའ་མ་རེད།',
'jumpto'           => 'ཆོམས།',
'jumptonavigation' => 'འཆོངས།',
'jumptosearch'     => 'འཚོལ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}-དྲ་བའི་གནས་ཚུལ།',
'aboutpage'            => 'Project:དྲ་བའི་སྐོར།',
'copyright'            => 'དྲ་བའི་ནང་དོན་$1སྟེང་དུ་ཡོད།',
'currentevents'        => 'ད་ལྟའི་བྱ་བ།',
'currentevents-url'    => 'Project:ད་ལྟའི་བྱ་བ།',
'disclaimers'          => 'དགག་བྱ།',
'disclaimerpage'       => 'Project:སྤྱིའི་དགག་བྱ།',
'edithelp'             => 'རྩོམ་སྒྲིག་གི་རོགས་རམ།',
'edithelppage'         => 'རོགས་རམ། / རྩོམ་སྒྲིག',
'mainpage'             => 'གཙོ་ངོས།',
'mainpage-description' => 'གཙོ་ངོས།',
'portal'               => 'ཁོངས་མི་འདུ་ར།',
'privacy'              => 'སྒེར་ཁྲིམས།',
'privacypage'          => 'Project: སྒེར་ཁྲིམས།',

'retrievedfrom'   => '"$1"ལས་རྙེད་པ།',
'editsection'     => 'རྩོམ་སྒྲིག་ཚན་པ།',
'editold'         => 'རྩོམ་སྒྲིག',
'editlink'        => 'བཟོ་བཅོས།',
'viewsourcelink'  => 'འབྱུང་ཁོངས་ལ་ལྟ་བ།',
'editsectionhint' => 'རྩོམ་སྒྲིག་ཚན་པ།$1',
'showtoc'         => 'སྟོན།',
'hidetoc'         => 'སྦས་ཤིག',
'site-rss-feed'   => '$1 ཡི་RSS འབྱུང་ཁུངས།',
'site-atom-feed'  => '$1 ཡི་Atom འབྱུང་ཁུངས།',
'red-link-title'  => '$1མ་རྙེད་པ།',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'རྩོམ་ཡིག',
'nstab-special'   => 'དམིཊ་གསལ་དྲ་ངོས།',
'nstab-image'     => 'ཡིག་ཆ།',
'nstab-mediawiki' => 'སྐད་ཆ།',

# General errors
'viewsource' => 'འབྱུང་ཁོངས་ལ་ལྟ་བ།',

# Login and logout pages
'yourname'                => 'དྲ་མིང་།',
'yourpassword'            => 'ལམ་ཡིག',
'yourpasswordagain'       => 'ལམ་ཡིག་ཡང་གཏག་བྱོས།',
'remembermypassword'      => 'ངའི་དྲ་མིང་འདིར་དྲན་པར་མཛོད།(ཡ་མཐའ་ཉིན $1 {{PLURAL:$1}})',
'login'                   => 'ནང་འཛུལ།',
'nav-login-createaccount' => 'ནང་འཛུལ། / ཐོ་འགོད།',
'userlogin'               => 'ནང་འཛུལ། / ཐོ་འགོད།',
'logout'                  => 'ཕྱིར་འབུད།',
'userlogout'              => 'ཕྱིར་འབུད།',
'notloggedin'             => 'ནང་འཛུལ་བྱས་མེད།',
'nologinlink'             => 'ཐོ་ཞིག་འགོད་པ།',
'createaccount'           => 'ཐོ་འགོད།',
'gotaccountlink'          => 'ནང་འཛུལ།',

# Edit page toolbar
'bold_sample'     => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'bold_tip'        => 'ཡིག་གཟུགས་སྦོམ་པོ།',
'italic_sample'   => 'ཡིག་གཟུགས་གསེགས་མ།',
'italic_tip'      => 'ཡིག་གཟུགས་གསེག་མ།',
'link_sample'     => 'མཚམས་སྦྱོར་ཁ་ཡིག',
'extlink_sample'  => 'Http://www.example.com མཚམས་སྦྱོར་ཁ་བྱང་།',
'headline_sample' => 'འགོ་བརྗོད་ཡིག་གཟུགས།',
'nowiki_sample'   => 'སྒྲིག་ཆས་མེད་པའི་ཡི་གེ་འདྲེན་པ།',
'nowiki_tip'      => 'ཝེ་ཁེའི་སྒྲིག་ཆས་འདོར་བ།',

# Edit pages
'summary'            => 'བསྡུས་དོན།:',
'subject'            => 'འགོ་བརྗོད།',
'minoredit'          => 'རྩོམ་སྒྲིག་ཕྲན་བུ།',
'watchthis'          => 'དྲ་ངོས་འདི་ལ་གཟིགས།',
'savearticle'        => 'དྲ་ངོས་ཉར་བ།',
'showpreview'        => 'སྔ་མ་སྟོན་ཅིག',
'showdiff'           => 'བཟོས་བཅོས་སྟོན།',
'loginreqlink'       => 'ནང་འཛུལ།',
'newarticle'         => '(གསར་བ)',
'editing'            => '$1རྩོམ་སྒྲིག་བྱེད་བཞིན་པ།',
'template-protected' => 'སྲུང་སྐྱོབ་འོག་ཡོད་པ།',

# History pages
'revisionasof'     => '$1 ལ་བཅོས་པ།',
'previousrevision' => ' ← བཟོ་བཅོས་སྔ་མ།',
'cur'              => 'ད་ལྟ།',
'last'             => 'མཐའ་མ།',
'histfirst'        => 'སྔ་ཤོས།',
'histlast'         => 'ཕྱི་ཤོས།',

# Revision deletion
'rev-delundel'   => 'སྟོན་པ། / སྦས་བ།',
'revdel-restore' => 'བཅོས་སུ་རུང་བ།',

# Merge log
'revertmerge' => 'སྦྱར་ཟིན་དགར་བ།',

# Diffs
'lineno'   => 'ཐིག་ཕྲེང་$1:',
'editundo' => 'ཕྱིར་ལེན་པ།',

# Search results
'searchresults'             => 'རྙེད་དོན།',
'searchresults-title'       => '$1བཙལ་འབྲས།',
'searchsubtitle'            => 'ཁྱེད་ཀྱིས་\'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|all pages starting with "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all pages that link to "$1"]])བཙལ་འདུག',
'notitlematches'            => 'ཤོག་ངོས་འགོ་བརྗོད་འདྲ་མཚུངས་མི་འདུག',
'nextn'                     => 'རྗེས་མ་$1',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3)ལ་ལྟ་བ།',
'search-result-size'        => 'རྙེད་དོན།',
'search-section'            => '（ཚན་པ $1)',
'search-suggest'            => '$1 ལ་ཟེར་བ་ཡིན་ནམ།',
'search-interwiki-default'  => '$1ལས་རྙེད་པ།',
'search-interwiki-more'     => '（དེ་ལས་མང་བ།）',
'search-mwsuggest-enabled'  => 'བསླབ་བྱ་ཡོད།',
'search-mwsuggest-disabled' => 'བསླབ་བྱ་མེད།',
'powersearch'               => 'ཞིབ་འཚོལ།',
'powersearch-ns'            => 'མིང་གནས་ནང་འཚོལ་བ།',
'powersearch-field'         => 'འཚོལ་བ།',

# Preferences page
'mypreferences'     => 'ངའི་ལེགས་སྒྲིག',
'prefsnologin'      => 'ནང་འཛུལ་བྱས་མེད།',
'prefs-rc'          => 'ཉེ་བའི་བཟོ་བཅོས།',
'searchresultshead' => 'འཚོལ།',
'youremail'         => 'དྲ་འཕྲིན། *:',
'username'          => 'དྲ་མིང་།:',
'email'             => 'དྲ་འཕྲིན།',

# Groups
'group-sysop' => 'ལྟ་སྐྱོང་བ།',

'grouppage-sysop' => '{{ns:project}}:ལྟ་སྐྱོང་བ།',

# Recent changes
'recentchanges'    => 'ཉེ་བའི་བཟོ་བཅོས།',
'rcshowhidemine'   => '$1ངའི་རྩོམ་སྒྲིག',
'diff'             => 'མི་འདྲ་ས།',
'hist'             => 'རྗེས་ཐོ།',
'hide'             => 'སྦས་བ།',
'show'             => 'སྟོན་ཅིག',
'minoreditletter'  => 'ཉུང།',
'newpageletter'    => 'ཤོག་གསར།',
'rc-enhanced-hide' => 'ཞིབ་ཕྲ་སྦས་བ།',

# Recent changes linked
'recentchangeslinked'         => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-feed'    => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-toolbox' => 'འབྲེལ་བའི་བཟོ་བཅོས།',

# Upload
'upload'            => 'ཡིག་ཆ་ཡར་འཇོག',
'uploadbtn'         => 'ཡར་འཇོག',
'uploadnologin'     => 'ནང་འཛུལ་བྱས་མེད།',
'filedesc'          => 'བསྡུས་དོན།',
'fileuploadsummary' => 'བསྡུས་དོན།:',
'watchthisupload'   => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',

# File description page
'filehist-current'  => 'ད་ལྟ།',
'filehist-datetime' => 'ཚེས་གྲངས། / དུས་ཚོད།',
'filehist-user'     => 'བེད་སྤྱོད་བྱེད་མི།',
'filehist-comment'  => 'བསམ་ཚུལ།',

# Random page
'randompage' => 'རང་མོས་ཤོག་ངོས།',

'brokenredirects-edit'   => 'རྩོམ་སྒྲིག',
'brokenredirects-delete' => 'གསུབ་པ།',

# Miscellaneous special pages
'nbytes'            => 'བྷེ་གྲངས་$1',
'newpages-username' => 'དྲ་མིང་།:',
'move'              => 'སྤོར།',

# Book sources
'booksources-go' => 'སོང་།',

# Special:AllPages
'allpages'       => 'དྲ་ངོས་ཡོངས།',
'alphaindexline' => '$1ནས$2བར།',
'allpagessubmit' => 'སོང་།',

# Special:ListGroupRights
'listgrouprights-members' => 'ཁོངས་མིའི་རེའུ་མིག',

# E-mail user
'emailuser'    => 'བཀོལ་མི་འདིར་དྲ་འཕྲིན་སྐུར་བ།',
'emailmessage' => 'སྐད་ཆ།',

# Watchlist
'watchlist'     => 'ངའི་མཉམ་འཇོག་ཐོ།',
'mywatchlist'   => 'ངའི་མཉམ་འཇོག',
'watchnologin'  => 'ནང་འཛུལ་བྱས་མེད།',
'watch'         => 'མཉམ་འཇོག',
'watchthispage' => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'མཉམ་འཇོག་ཏུ་འཇུག་བཞིན་པ་་་',
'unwatching' => 'མཉམ་འཇོག་ལས་འདོར་བཞིན་པ་་་',

# Delete
'deletedarticle' => '"[[$1]]"བསུབས་ཟིན།',

# Restrictions (nouns)
'restriction-edit' => 'རྩོམ་སྒྲིག',
'restriction-move' => 'སྤོར།',

# Undelete
'undeletelink'           => 'ལྟ་བ། / བསྐྱར་འདྲེན།',
'undelete-search-submit' => 'འཚོལ།',

# Namespace form on various pages
'namespace'      => 'མིང་གནས།',
'blanknamespace' => 'གཙོ་ངོས།',

# Contributions
'contributions' => 'བཀོལ་མིའི་བྱས་རྗེས།',
'mycontris'     => 'ངའི་བྱས་རྗེས།',

# What links here
'whatlinkshere' => 'འབྲེལ་བའི་དྲ་བ།',

# Block/unblock
'ipbreason'          => 'རྒྱུ་མཚན།',
'ipblocklist-submit' => 'འཚོལ།',
'blocklink'          => 'འགོག་པ།',
'unblocklink'        => 'བཀག་པ་ཕྱེ་བ།',
'change-blocklink'   => 'བཀག་ཟིན་བསྒྱུར་བ།',
'contribslink'       => 'བྱས་རྗེས།',

# Move page
'movearticle' => 'སྤོར་ངོས།',
'movenologin' => 'ནང་འཛུལ་བྱས་མེད།',
'move-watch'  => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',
'movereason'  => 'རྒྱུ་མཚན།',
'revertmove'  => 'ཕྱིར་ལོག',

# Namespace 8 related
'allmessages' => 'མ་ལག་གི་སྐད་ཆ།',

# Tooltip help for the actions
'tooltip-pt-mytalk'              => 'ཁྱེད་ཀྱི་གླེང་མོལ་ཤོག་ངོས།',
'tooltip-pt-preferences'         => 'ངའི་ལེགས་སྒྲིག',
'tooltip-pt-login'               => 'ནང་འཛུལ།',
'tooltip-pt-logout'              => 'ཕྱིར་འབུད།',
'tooltip-ca-talk'                => 'གྲོས་མོལ།',
'tooltip-ca-edit'                => 'ཁྱེད་ཀྱིས་དྲ་ངོས་འདི་རྩོམ་སྒྲིག་བྱེད་ཆོག ཉར་ཚགས་མ་བྱས་པའི་སྔོན་དུ་ཐེབ་གཅུ་སྒང་མ་སྤྱོད་རོགས།',
'tooltip-ca-viewsource'          => 'ཤོག་ངོས་འདི་སྲུང་སྐྱོབ་འོག་ཡོད། ཁྱེད་ཀྱིས་འདིའི་འབྱུང་ཁོངས་ལྟ་ཆོག',
'tooltip-ca-history'             => 'བཟོ་བཅོས་སྔ་མ།',
'tooltip-ca-move'                => 'ཤོག་ངོས་འདི་སྤོར་བ།',
'tooltip-ca-unwatch'             => 'མཉམ་འཇོག་ཐོ་ལས་འདོར་བ།',
'tooltip-search'                 => 'ལག་ཆ་འཚོལ།',
'tooltip-search-go'              => 'སོང་།',
'tooltip-search-fulltext'        => 'ཚིག་འདི་འཚོལ།',
'tooltip-p-logo'                 => 'གཙོ་ངོས།',
'tooltip-n-mainpage'             => 'གཙོ་ངོས།',
'tooltip-n-mainpage-description' => 'གཙོ་ངོས་ལ་ལྟ་བ།',
'tooltip-n-portal'               => 'རྒྱལ་སྒོ།',
'tooltip-n-recentchanges'        => 'ཉེ་བའི་བཟོ་བཅོས།',
'tooltip-n-randompage'           => 'རང་འགུལ་དྲ་ངོས།',
'tooltip-n-help'                 => 'རོགས་རམ།',
'tooltip-t-whatlinkshere'        => 'འདིར་འབྲེལ་བའི་ཝེ་ཁེ་དྲ་ངོས་ཡོངས་རྫོགས།',
'tooltip-t-recentchangeslinked'  => 'ཉེ་བའི་བཟོ་བཅོས།',
'tooltip-t-upload'               => 'ཡིག་ཆ་ཡར་འཇུག',
'tooltip-t-specialpages'         => 'དམིཊ་གསལ་དྲ་ངོས་རྣམས།',
'tooltip-t-print'                => 'དཔར་ཐུབ་པའི་མི་འདྲ་ཆོས།',
'tooltip-t-permalink'            => 'རྟག་བརྟན་གྱི་དྲ་བར་འཇུག་པ།',
'tooltip-ca-nstab-main'          => 'དྲ་ངོས་ནང་དོན་ལ་ལྟ་བ།',
'tooltip-ca-nstab-special'       => 'དྲ་ངོས་འདི་དམིགས་གསལ་བ་ཡིན་པས་བཟོ་བཅོས་རྒྱག་མི་ཆོག',
'tooltip-ca-nstab-image'         => 'ཡིག་ཆར་ལྟ་བ།',
'tooltip-save'                   => 'བཟོ་བཅོས་ཉར་ཚགས་བྱོས།',

# Special:NewFiles
'ilsubmit' => 'འཚོལ།',

# 'all' in various places, this might be different for inflected languages
'namespacesall' => 'ཡོངས་རྫོགས།',
'monthsall'     => 'ཡོངས་རྫོགས།',

# Multipage image navigation
'imgmultigo' => 'སོང་།!',

# Table pager
'table_pager_limit_submit' => 'སོང་།',

# Special:SpecialPages
'specialpages' => 'ཁྱད་པར་བ།',

);
