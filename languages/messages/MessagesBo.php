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
'tog-extendwatchlist'       => 'མཉམ་འཇོག་ཐོ་བཀྲམས་ཏེ་ཉེ་ལམ་ཙམ་མིན་པར་བཟོ་བཅོས་ཡོངས་རྫོགས་སྟོན་ཅིག',
'tog-usenewrc'              => 'ཡར་རྒྱས་ཅན་གྱི་ཉེ་བའི་བཟོ་བཅོས་བེད་སྤྱོད་པ།(Java ཡི་བརྡ་ཆད་དགོས)',
'tog-showtoolbar'           => 'རྩོམ་སྒྲིག་ལག་ཆ་སྟོན།(Java ཡི་བརྡ་ཆད་དགོས།)',
'tog-editondblclick'        => 'ཤོག་ངོས་རྩོམ་སྒྲིག་བྱེད་པར་ལན་གཉིས་རྡེབ།(Java ཡི་བརྡ་ཆད་དགོས།)',

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
'january-gen'   => '.༡',
'february-gen'  => '.༢',
'march-gen'     => '.༣',
'april-gen'     => '.༤',
'may-gen'       => '.༥',
'june-gen'      => '.༦',
'july-gen'      => '.༧',
'august-gen'    => '.༨',
'september-gen' => '.༩',
'october-gen'   => '.༡༠',
'november-gen'  => '.༡༡',
'december-gen'  => '.༡༢',
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
'pagecategories'    => 'སྡེ་ཚན་{{PLURAL:$1|category|categories}}',
'category_header'   => 'རྣམ་གྲངས་"$1"ནང་གི་ཤོག་ངོས་རྣམས།',
'subcategories'     => 'རིགས་ཕལ་བ།',
'hidden-categories' => '{{PLURAL:$1|སྦས་བའི་སྡེ་ཚན།|སྦས་བའི་སྡེ་ཚན།}}',

'about'         => 'ཨཱབོཨུཏ་',
'newwindow'     => '(སྒེའུ་ཁུང་གསར་བར་ཕྱེ་བ།)',
'cancel'        => 'དོར་བ།',
'moredotdotdot' => 'དེ་ལས་མང་བ་་་',
'mypage'        => 'ངའི་ཤོག་ངོས།',
'mytalk'        => 'ངའི་གླེང་མོལ།',
'navigation'    => 'ལམ་སྟོན།',

# Cologne Blue skin
'qbfind'         => 'འཚོལ་བ།',
'qbedit'         => 'རྩོམ་སྒྲིག',
'qbpageoptions'  => 'ཤོག་ངོས་འདི།',
'qbspecialpages' => 'དམིཊ་བསལ་གྱི་བཟོ་བཅོས།',
'faq'            => 'རྒྱུན་ལྡན་དྲི་བ།',
'faqpage'        => 'Project:རྒྱུན་ལྡན་དྲི་བ།',

# Vector skin
'vector-view-edit' => 'རྩོམ་སྒྲིག',
'vector-view-view' => 'ཀློག་པ།',

'tagline'          => '{{SITENAME}}འབྲེལ་

འབྱུང་ཁུངས་{{SITENAME}}',
'help'             => 'རོགས་རམ།',
'search'           => 'འཚོལ་བ།',
'searchbutton'     => 'འཚོལ་བ།',
'go'               => 'སོང་།',
'searcharticle'    => 'འཚོལ།',
'history'          => 'ཤོག་ངོས་དྲན་ཐོ།',
'history_short'    => 'ཡིད་འཛིན་ཐོ་ཡིག།',
'printableversion' => 'དཔར་ཐུབ་པ།',
'permalink'        => 'རྟག་བརྟན་གྱི་དྲ་འབྲེལ།',
'print'            => 'དཔར་བ།',
'edit'             => 'རྩོམ་སྒྲིག',
'create'           => 'གསར་སྐྲུན།',
'editthispage'     => 'ངོས་འདི་བཟོ་བཅོས་བྱེད་པ།',
'create-this-page' => 'ཤོག་ངོས་འདི་སྐྲུན་པ།',
'delete'           => 'གསུབ་པ།',
'deletethispage'   => 'ཤོག་ངོས་འདི་འདོར་བ།',
'undelete_short'   => '{{PLURAL:$1|one edit|$1edits}} མ་འདོར་ཞིག',
'protect'          => 'སྲུང་བ།',
'protect_change'   => 'སྒྱུར་བཅོས།',
'newpage'          => 'ཤོག་ངོས་གསར་བ།',
'talkpagelinktext' => 'གླེང་མོལ།',
'personaltools'    => 'སྒེར་ཀྱི་ལག་ཆ།',
'talk'             => 'གྲོས་བསྡུར།',
'views'            => 'སྣང་ཚུལ།',
'toolbox'          => 'ལག་ཆའི་སྒམ།',
'categorypage'     => 'སྡེ་ཚན་ཤོག་ངོས་སྟོན་ཅིག',
'otherlanguages'   => 'སྐད་རིགས་གཞན།',
'redirectedfrom'   => '$1 ནས་ཁ་ཕྱོགས་བསྐྱར་དུ་བཟོས་པ།',
'lastmodifiedat'   => 'དྲ་ངོས་འདི་ཡི་བཟོ་བཅོས་མཐའ་མ་$1$2སྟེང་རེད།',
'jumpto'           => 'གནས་སྤོ།',
'jumptonavigation' => 'ལམ་སྟོན།',
'jumptosearch'     => 'འཚོལ།',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}}-ངེད་ཀྱི་སྐོར།',
'aboutpage'            => 'Project:དྲ་བའི་སྐོར།',
'copyright'            => 'དྲ་བའི་ནང་དོན་$1སྟེང་དུ་ཡོད།',
'copyrightpage'        => '{{ns:project}} པར་དབང་།',
'currentevents'        => 'ད་ལྟའི་བྱ་བ།',
'currentevents-url'    => 'Project:ད་ལྟའི་བྱ་བ།',
'disclaimers'          => 'དགག་བྱ།',
'disclaimerpage'       => 'Project:སྤྱིའི་དགག་བྱ།',
'edithelp'             => 'རྩོམ་སྒྲིག་གི་རོགས་རམ།',
'edithelppage'         => 'Help:རྩོམ་སྒྲིག',
'mainpage'             => 'གཙོ་ངོས།',
'mainpage-description' => 'གཙོ་ངོས།',
'portal'               => 'ཁོངས་མི་འདུ་ར།',
'privacy'              => 'སྒེར་ཁྲིམས།',
'privacypage'          => 'Project: སྒེར་ཁྲིམས།',

'retrievedfrom'       => '"$1"ལས་རྙེད་པ།',
'youhavenewmessages'  => 'ཁྱེད་ལ་འཕྲིན་གསར་$1($2)ཡོད།',
'newmessageslink'     => 'འཕྲིན་གསར།',
'newmessagesdifflink' => 'བཟོ་བཅོས་མཐའ་མ།',
'editsection'         => 'རྩོམ་སྒྲིག',
'editold'             => 'རྩོམ་སྒྲིག',
'editlink'            => 'བཟོ་བཅོས།',
'viewsourcelink'      => 'འབྱུང་ཁོངས་ལ་ལྟ་བ།',
'editsectionhint'     => 'རྩོམ་སྒྲིག་སྡེ་ཚན།$1',
'toc'                 => 'ཟུར་བཀོད།',
'showtoc'             => 'སྟོན།',
'hidetoc'             => 'སྦས།',
'site-rss-feed'       => '$1 ཡི་RSS འབྱུང་ཁུངས།',
'site-atom-feed'      => '$1 ཡི་Atom འབྱུང་ཁུངས།',
'red-link-title'      => '$1(དྲ་ངོས་འདི་མི་འདུག))',

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
'italic_sample'   => 'ཡིག་གཟུགས་གསེག་མ།',
'italic_tip'      => 'ཡིག་གཟུགས་གསེག་མ།',
'link_sample'     => 'མཚམས་སྦྱོར་ཁ་ཡིག',
'extlink_sample'  => 'Http://www.example.com སྦྲེལ་མཐུད་ཁ་བྱང་།',
'extlink_tip'     => 'ཕྱི་ཕྱོགས་དྲ་འབྲེལ།（ remember http:// prefix )',
'headline_sample' => 'འགོ་བརྗོད་ཡིག་གཟུགས།',
'nowiki_sample'   => 'སྒྲིག་ཆས་མེད་པའི་ཡི་གེ་འདྲེན་པ།',
'nowiki_tip'      => 'ཝེ་ཁེའི་སྒྲིག་ཆས་འདོར་བ།',

# Edit pages
'summary'            => 'བསྡུས་དོན།:',
'subject'            => 'འགོ་བརྗོད།',
'minoredit'          => 'འདི་ནི་རྩོམ་སྒྲིག་ཕལ་བ་ཞིག་ཡིན།',
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
'editundo' => 'བསུབས་པ་ཕྱིར་ལེན།',

# Search results
'searchresults'             => 'རྙེད་དོན།',
'searchresults-title'       => '$1བཙལ་འབྲས།',
'searchresulttext'          => '{{SITENAME}}སྐོར་ཀྱི་གནས་ཚུལ་རྒྱས་པར་[[{{MediaWiki:Helppage}}||{{int:help}} ལ་ལྟ་རོགས།',
'searchsubtitle'            => 'ཁྱེད་ཀྱིས་\'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|all pages starting with "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|all pages that link to "$1"]])བཙལ་འདུག',
'notitlematches'            => 'འགོ་བརྗོད་འདྲ་མཚུངས་མི་འདུག',
'notextmatches'             => 'ཤོག་ངོས་ཡིག་འབྲུ་མཚུངས་པ་མི་འདུག',
'prevn'                     => 'སྔོན་མ་{{PLURAL:$1|$1}}',
'nextn'                     => 'རྗེས་མ་{{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3)ལ་ལྟ་བ།',
'search-result-size'        => '$1({{PLURAL:$2|1 word|$2 words}})',
'search-redirect'           => 'ཁ་ཕྱོགས་བསྐྱར་བཟོ།',
'search-section'            => '(ཚན་པ $1)',
'search-suggest'            => '$1 ལ་ཟེར་བ་ཡིན་ནམ།',
'search-interwiki-caption'  => 'སྲིང་མོའི་ལས་འཆར།',
'search-interwiki-default'  => '$1ལས་རྙེད་པ།',
'search-interwiki-more'     => '（དེ་ལས་མང་བ།）',
'search-mwsuggest-enabled'  => 'གདམ་ང་དང་བཅས།',
'search-mwsuggest-disabled' => 'གདམ་ང་མི་དགོས།',
'powersearch'               => 'ཞིབ་ཏུ་འཚོལ་བ།',
'powersearch-legend'        => 'ཞིབ་ཏུ་འཚོལ་བ།',
'powersearch-ns'            => 'མིང་གནས་ནང་འཚོལ་བ།',
'powersearch-redir'         => 'ཁ་ཕྱོགས་གསར་བཟོ་སྟོན་པ།',
'powersearch-field'         => 'ཆེད་འཚོལ།',

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
'recentchanges'      => 'ཉེ་བའི་བཟོ་བཅོས།',
'rcshowhidemine'     => '$1ངའི་རྩོམ་སྒྲིག',
'diff'               => 'མི་འདྲ་ས།',
'hist'               => 'ཡིད་འཛིན་དྲན་ཐོ།',
'hide'               => 'སྦས་བ།',
'show'               => 'སྟོན་ཅིག',
'minoreditletter'    => 'ཆུང་སྒྲིག',
'newpageletter'      => 'ཤོག་གསར།',
'rc-enhanced-expand' => 'ཞིབ་ཕྲ་སྟོན།( requires JavaScript )',
'rc-enhanced-hide'   => 'ཞིབ་ཕྲ་སྦས་བ།',

# Recent changes linked
'recentchangeslinked'         => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-feed'    => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-toolbox' => 'འབྲེལ་བའི་བཟོ་བཅོས།',
'recentchangeslinked-summary' => "འདི་ནི་དམིགས་གསལ་ཤོག་ངོས་སམ་དམིགས་གསལ་རྣམ་གྲངས་ཀྱི་ཁོངས་མི་དང་སྦྲེལ་ཡོད་པའི་ཉེ་བའི་བཟོ་བཅོས་རེད།[[Special:Watchlist|yourwatchlist]] ནང་གི་ཤོག་ངོས་རྣམས་'''ཡིག་གཟུགས་སྦོམ་པོ་'''ཡིན།",

# Upload
'upload'            => 'ཡིག་ཆ་ཡར་འཇོག',
'uploadbtn'         => 'ཡར་འཇོག',
'uploadnologin'     => 'ནང་འཛུལ་བྱས་མེད།',
'filedesc'          => 'བསྡུས་དོན།',
'fileuploadsummary' => 'བསྡུས་དོན།:',
'watchthisupload'   => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',

# File description page
'filehist'            => 'རྩོམ་ཡིག་དྲན་ཐོ།',
'filehist-help'       => 'ཟླ་ཚེས་དུས་ཚོད་མནན་ཏེ་སྐབས་དེའི་རྩོམ་ཡིག་ལ་ལྟ་བ།',
'filehist-current'    => 'ད་ལྟ།',
'filehist-datetime'   => 'ཚེས་གྲངས། / དུས་ཚོད།',
'filehist-thumb'      => 'བསྡུས་དོན།',
'filehist-thumbtext'  => '$1 བཟོ་བཅོས་བསྡུས་དོན།',
'filehist-user'       => 'བེད་སྤྱོད་བྱེད་མི།',
'filehist-dimensions' => 'ཚད།',
'filehist-comment'    => 'བསམ་ཚུལ།',
'imagelinks'          => 'གང་ལ་སྦྲེལ་བ།',
'linkstoimage'        => '{{PLURAL:$1|pagelinks|$1pagelink}} འདི་ལ་སྦྲེལ་ཡོད།',

# Random page
'randompage' => 'རང་མོས་ཤོག་ངོས།',

'brokenredirects-edit'   => 'རྩོམ་སྒྲིག',
'brokenredirects-delete' => 'གསུབ་པ།',

# Miscellaneous special pages
'nbytes'            => 'བྷེ་གྲངས་$1',
'newpages-username' => 'དྲ་མིང་།:',
'move'              => 'སྤོར་བ།',
'pager-newer-n'     => '{{PLURAL：$1|གསར་བ་1|གསར་བ་$1}}',
'pager-older-n'     => '{{PLURAL：$1|རྙིང་པ་1|རྙིང་པ་$1}}',

# Book sources
'booksources-go' => 'སོང་།',

# Special:AllPages
'allpages'       => 'དྲ་ངོས་ཡོངས།',
'alphaindexline' => '$1 ནས་ $2 བར།',
'allpagessubmit' => 'སོང་།',

# Special:ListGroupRights
'listgrouprights-members' => 'ཁོངས་མིའི་ཐོ་ཡིག',

# E-mail user
'emailuser'    => 'བཀོལ་མི་འདིར་དྲ་འཕྲིན་སྐུར་བ།',
'emailmessage' => 'སྐད་ཆ།',

# Watchlist
'watchlist'     => 'ངའི་མཉམ་འཇོག་ཐོ།',
'mywatchlist'   => 'ངའི་མཉམ་འཇོག',
'watchnologin'  => 'ནང་འཛུལ་བྱས་མེད།',
'watch'         => 'ལྟ་ཐོ།',
'watchthispage' => 'དྲ་ངོས་འདི་ལ་མཉམ་འཇོག་པ།',
'unwatch'       => 'མ་བལྟས་པ།',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'མཉམ་འཇོག་ཐོ་རུ་འཇུག་བཞིན་པ་་་',
'unwatching' => 'མཉམ་འཇོག་ཐོ་ལས་འདོར་བཞིན་པ་་་',

# Delete
'deletedarticle' => '"[[$1]]"བསུབས་ཟིན།',

# Rollback
'rollbacklink' => 'རྒྱབ་ལ་སྣུར་བ།',

# Protect
'protectedarticle' => 'སྲུང་སྐྱོབ་བྱས་ཟིན།"[[$1]]"',

# Restrictions (nouns)
'restriction-edit' => 'རྩོམ་སྒྲིག',
'restriction-move' => 'སྤོར།',

# Undelete
'undeletelink'           => 'ལྟ་བ། / བསྐྱར་འདྲེན།',
'undelete-search-submit' => 'འཚོལ།',

# Namespace form on various pages
'namespace'      => 'མིང་གནས།',
'blanknamespace' => '༼གཙོ་ངོས།༽',

# Contributions
'contributions' => 'བཀོལ་མིའི་བྱས་རྗེས།',
'mycontris'     => 'ངའི་བྱས་རྗེས།',

# What links here
'whatlinkshere'           => 'གང་དང་སྦྲེལ་བ།',
'whatlinkshere-page'      => 'ཤོག་ངོས།',
'whatlinkshere-hidelinks' => '$1 དྲ་འབྲེལ།',

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
'1movedto2'   => '[[$1]][[$2]]ལ་སྤོར་ཟིན།',
'movereason'  => 'རྒྱུ་མཚན།',
'revertmove'  => 'ཕྱིར་ལོག',

# Namespace 8 related
'allmessages' => 'མ་ལག་གི་སྐད་ཆ།',

# Thumbnails
'thumbnail-more' => 'ཆེ་རུ་གཏོང་བ།',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'ཁྱེད་ཀྱི་བཀོལ་མིའི་དྲ་ངོས།',
'tooltip-pt-mytalk'              => 'ཁྱེད་ཀྱི་གླེང་མོལ་ཤོག་ངོས།',
'tooltip-pt-preferences'         => 'ཁྱེད་ཀྱི་ལེགས་སྒྲིག',
'tooltip-pt-watchlist'           => 'ཞུ་དག་གཏོང་བཞིན་པའི་ཤོག་ངོས།',
'tooltip-pt-mycontris'           => 'ངའི་བྱས་རྗེས་སྟོན་པ།',
'tooltip-pt-login'               => 'ནང་འཛུལ།',
'tooltip-pt-logout'              => 'ཕྱིར་འབུད།',
'tooltip-ca-talk'                => 'གྲོས་མོལ།',
'tooltip-ca-edit'                => 'ཁྱེད་ཀྱིས་དྲ་ངོས་འདི་རྩོམ་སྒྲིག་བྱེད་ཆོག ཉར་ཚགས་མ་བྱས་པའི་སྔོན་དུ་ཐེབ་གཅུ་སྒང་མ་སྤྱོད་རོགས།',
'tooltip-ca-viewsource'          => 'ཤོག་ངོས་འདི་སྲུང་སྐྱོབ་འོག་ཡོད། ཁྱེད་ཀྱིས་འདིའི་འབྱུང་ཁོངས་ལྟ་ཆོག',
'tooltip-ca-history'             => 'བཟོ་བཅོས་སྔ་མ།',
'tooltip-ca-move'                => 'ཤོག་ངོས་འདི་སྤོར་བ།',
'tooltip-ca-watch'               => 'ལྟ་ཐོ་རུ་འཇུག་པ།',
'tooltip-ca-unwatch'             => 'མཉམ་འཇོག་ཐོ་ལས་འདོར་བ།',
'tooltip-search'                 => 'ལག་ཆ་འཚོལ།',
'tooltip-search-go'              => 'མིང་ཇི་བཞིན་པའི་ཤོག་ངོས་སྟེང་དུ་སྐྱོད་པ།',
'tooltip-search-fulltext'        => 'ཚིག་འདི་འཚོལ།',
'tooltip-p-logo'                 => 'གཙོ་ངོས།',
'tooltip-n-mainpage'             => 'གཙོ་ངོས་ལ་ལྟ་བ།',
'tooltip-n-mainpage-description' => 'གཙོ་ངོས་ལ་ལྟ་བ།',
'tooltip-n-portal'               => 'ལས་འཆར་སྐོར་དང་ཁྱེད་ཀྱིས་ཅི་ཞིག་བྱེད་ནུས་པ། གང་དུ་འཚོལ་དགོས་པའི་སྐོར།',
'tooltip-n-currentevents'        => 'ཉེ་བའི་ལས་དོན་གྱི་རྒྱབ་ལྗོངས་གནས་ཚུལ་འཚོལ་བ།',
'tooltip-n-recentchanges'        => 'ཝེ་ཁེ་སྟེང་གི་ཉེ་བའི་བཟོ་བཅོས་ཀྱི་ཐོ་གཞུང་།',
'tooltip-n-randompage'           => 'རང་འགུལ་དྲ་ངོས།',
'tooltip-n-help'                 => 'གང་དུ་འཚོལ་དགོས་པའི་གནས།',
'tooltip-t-whatlinkshere'        => 'འདིར་འབྲེལ་བའི་ཝེ་ཁེ་དྲ་ངོས་ཡོངས་རྫོགས།',
'tooltip-t-recentchangeslinked'  => 'ངོས་འདི་དང་འབྲེལ་བའི་ཉེ་བའི་བཟོ་བཅོས།',
'tooltip-t-contributions'        => 'བཀོལ་མི་འདིའི་བྱས་རྗེས་སྟོན།',
'tooltip-t-upload'               => 'ཡིག་ཆ་ཡར་འཇུག',
'tooltip-t-specialpages'         => 'དམིཊ་གསལ་ཤོག་ངོས་ཀྱི་ཐོ་གཞུང་།',
'tooltip-t-print'                => 'དཔར་ཐུབ་པའི་མི་འདྲ་ཆོས།',
'tooltip-t-permalink'            => 'རྟག་བརྟན་གྱི་དྲ་བར་འཇུག་པ།',
'tooltip-ca-nstab-main'          => 'དྲ་ངོས་ནང་དོན་ལ་ལྟ་བ།',
'tooltip-ca-nstab-special'       => 'དྲ་ངོས་འདི་དམིགས་གསལ་བ་ཡིན་པས་བཟོ་བཅོས་རྒྱག་མི་ཆོག',
'tooltip-ca-nstab-image'         => 'ཡིག་ཆར་ལྟ་བ།',
'tooltip-ca-nstab-category'      => 'རྣམ་གྲངས་ཤོག་ངོས་སྟོན།',
'tooltip-save'                   => 'བཟོ་བཅོས་ཉར་ཚགས་བྱོས།',
'tooltip-preview'                => 'ཉར་ཚགས་ཀྱི་སྔོན་དུ་བཟོ་བཅོས་ལ་བསྐྱར་ཞིབ་གནང་རོགས།',
'tooltip-diff'                   => 'གང་ལ་བཟོ་བཅོས་བྱས་པའི་ཡིག་འབྲུ་སྟོན་པ།',

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
'specialpages' => 'ཤོག་ངོས་དམིགས་གསལ།',

);
