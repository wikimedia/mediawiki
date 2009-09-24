<?php
/** Navajo (Diné bizaad)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Seb az86556
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Naaltsoos_baa_yinísht\'į́',
	NS_USER             => 'Choinish\'įįhí',
	NS_USER_TALK        => 'Choinish\'įįhí_baa_yinísht\'į́',
	NS_PROJECT_TALK     => '$1_baa_yinísht\'į́',
	NS_FILE             => 'E\'elyaaígíí',
	NS_FILE_TALK        => 'E\'elyaaígíí_baa_yinísht\'į́',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_baa_yinísht\'į́',
	NS_TEMPLATE         => 'Template',
	NS_TEMPLATE_TALK    => 'Template_talk',
	NS_HELP             => 'Aná\'álwo\'',
	NS_HELP_TALK        => 'Aná\'álwo\'_baa_yinísht\'į́',
	NS_CATEGORY         => 'T\'ááłáhági_át\'éego',
	NS_CATEGORY_TALK    => 'T\'ááłáhági_át\'éego_baa_yinísht\'į́',
);

$datePreferences = false;

$messages = array(
# Dates
'sunday'    => 'Damóogo',
'monday'    => 'Damóo biiskání',
'tuesday'   => 'Damóodóó naakiską́o',
'wednesday' => 'Damóodóó tágí jį́',
'thursday'  => "Damóodóó dį́į́' yiską́o",
'friday'    => "Nda'iiníísh",
'saturday'  => 'Yiską́ damóo',
'january'   => 'Yas Niłtʼees',
'february'  => 'Atsá Biyáázh',
'march'     => 'Wóózhchʼį́į́d',
'april'     => 'Tʼą́ą́chil',
'may_long'  => 'Tʼą́ą́tsoh',
'june'      => 'Yaʼiishjááshchilí',
'july'      => 'Yaʼiishjáástsoh',
'august'    => 'Biniʼantʼą́ą́tsʼózí',
'september' => 'Biniʼantʼą́ą́tsoh',
'october'   => 'Ghąąjį',
'november'  => 'Níłchʼitsʼósí',
'december'  => 'Níłchʼitsoh',
'jan'       => 'Ynts',
'feb'       => 'Atsb',
'mar'       => 'Wozh',
'apr'       => 'Tchi',
'may'       => 'Ttso',
'jun'       => 'Yjsh',
'jul'       => 'Yjts',
'aug'       => 'Btsz',
'sep'       => 'Btsx',
'oct'       => 'Ghąj',
'nov'       => 'Ntss',
'dec'       => 'Ntsx',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Naaltsoos biiʼ sinilí|Naaltsoos biiʼ sinilí}}',
'category_header'        => 'Naaltsoos biiʼ sinilí "$1" biyiʼ dahólónígíí',
'subcategories'          => 'Hanálzhoʼí',
'hidden-categories'      => '{{PLURAL:$1|Naaltsoos biiʼ sinilí (doo yitʼínii)|Naaltsoos biiʼ sinilí (doo yitʼínii)}}',
'category-subcat-count'  => '{{PLURAL:$2|1 Hanálzhoʼí.|{{PLURAL:$1|1 Hanálzhoʼí|$2 Hanálzhoʼí}} - ($1).}}',
'category-article-count' => "{{PLURAL:$2|'''1 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ hólǫ.|{{PLURAL:$2|'''1 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ hólǫ|'''$2 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ dahólǫ}} - ($1)}}",
'listingcontinuesabbrev' => 'nááná...',

'mytalk'     => 'haneʼ shichʼįʼ ályaaígíí',
'navigation' => 'naaltsoosígíí',

'returnto'         => '← $1',
'tagline'          => "''{{SITENAME}}'' bitsʼą́ą́dę́ę́ʼ",
'help'             => 'Anáʼálwoʼ',
'search'           => 'hanishtá nisin',
'searchbutton'     => 'tʼáá yíní átʼéegi',
'searcharticle'    => 'díí saad tʼéiyá',
'history_short'    => 'łahgo ályaaígíí',
'printableversion' => '"Print" áshłééh nisin',
'permalink'        => 'Díí naaltsoos bi-"url"',
'edit'             => 'Łahgo áshłééh',
'create'           => 'áshłééh nisin',
'editthispage'     => 'díí naaltsoos łahgo áshłééh',
'delete'           => 'sisxé (delete)',
'protect'          => "bich'ą́ą́h iishááh nisin (protect)",
'newpage'          => 'Naaltsoos ániidí',
'talkpagelinktext' => 'bichʼįʼ yáshtiʼ',
'specialpage'      => 'Naaltsoos spéshelígíí',
'personaltools'    => 'bee naashnishí',
'talk'             => 'baa yáshtiʼ nisin',
'views'            => 'naaltsoosígíí',
'toolbox'          => 'bee naʼanishí',
'otherlanguages'   => 'saad',
'redirectedfrom'   => '("$1"dę́ę́ʼ)',
'lastmodifiedat'   => 'Díí naaltsoos $1/$2 łahgo ályaa.',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} baa haneʼ',
'aboutpage'            => 'Project: baa haneʼ',
'copyright'            => 'Díí naaltsoos bikáaʼgi saad shijaaʼígíí "$1" beehazʼą́ąnii bikʼehgo choidííłįįł',
'disclaimers'          => 'Disclaimers (Bilagáanakʼehjí)',
'edithelp'             => 'anáʼálwoʼ',
'mainpage'             => 'Íiyisíí Naaltsoos',
'mainpage-description' => 'Íiyisíí Naaltsoos',
'privacy'              => 'Privacy policy (Bilagáanakʼehjí)',

'retrievedfrom'       => '"$1" bitsʼą́ą́dę́ę́ʼ',
'youhavenewmessages'  => 'Háíshį́į́ $1 nichʼįʼ áyiilaa. <small>($2)</small>',
'newmessageslink'     => 'haneʼ ániidígíí',
'newmessagesdifflink' => 'diff',
'editsection'         => 'łahgo áshłééh',
'editold'             => 'łahgo áshłééh',
'editlink'            => 'łahgo áshłééh',
'viewsourcelink'      => 'XML yishʼį́ nisin',
'showtoc'             => 'yishʼį́ nisin',
'hidetoc'             => 'doo yishʼį́ nisin da',
'red-link-title'      => '$1 (ádin)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'naaltsoos',
'nstab-user'      => 'choyoołʼįįhí binaaltsoos',
'nstab-special'   => 'Naaltsoos spéshelígíí',
'nstab-project'   => 'wikiibíídiiya binaaltsoos',
'nstab-image'     => 'eʼelyaaígíí',
'nstab-mediawiki' => 'haneʼ',
'nstab-template'  => 'bee álnééhí',
'nstab-help'      => 'anáʼálwoʼ',
'nstab-category'  => 'Naaltsoos biiʼ sinilí',

# General errors
'missing-article'    => '\'\'\'Naaltsoos "$1" $2 wolyéhígíí(/"$1" bikáaʼgi hólónígíí) ádin. \'\'Link\'\'ígíí daatsʼí sání; kʼééʼoolchǫʼ shį́į́.\'\'\'<br /> (A page named "$1" $2 (/that contains "$1") does not exist. The link might be old; maybe it was erased.)
---- 
<small>If this is not the case, you may have found a bug in the software. Please report this to an [[Special:ListUsers/sysop|administrator]], making note of the URL.</small>',
'missingarticle-rev' => '(łahgo ályaaígíí #$1)',
'viewsource'         => 'XML yishʼį́ nisin',

# Login and logout pages
'yourname'           => 'Choyoołʼįįhí bizhiʼ:',
'remembermypassword' => 'shipassword béédíínih',
'nologinlink'        => 'Accountígíí ádíílííł',

# Edit pages
'minoredit'        => 'tʼáá áłtsʼíísígo tʼéiyá naaltsoos łahgo áshłaa',
'watchthis'        => 'shinááł nisin',
'savearticle'      => '✔ bee lą́ ashłeeh',
'preview'          => 'dooleełígíí',
'showpreview'      => 'dooleełígíí yishʼį́ nisin',
'showdiff'         => 'łahgo áshłaaígíí yishʼį́ nisin',
'anoneditwarning'  => "<div style=\"background:#aaddff; text-align:center;\">'''Doo \"login\" íinilaa da.''' Éí biniinaa nizhiʼ doo ééhozin da áádóó ni-''IP'' naaltsoos bikááʼ náázhdíyóosoh.<br /><small>('''You are not logged in.''' Your name is thus unknown, and your IP will be recorded.)</small></div>",
'newarticle'       => '(Naaltsoos ániidí)',
'previewnote'      => "'''Díí kweʼé éí \"dooleełígíí\" tʼéiyá átʼé!'''
::<small>'''(This is only a preview.)'''</small>",
'editing'          => '"$1" łahgo áshłééh...',
'hiddencategories' => 'Díí kweʼé naaltsoos éí {{PLURAL:$1|1 Naaltsoos biiʼ sinilí (doo yitʼínii)|$1 Naaltsoos biiʼ sinilí (doo yitʼínii)}} yiiʼ siʼą́:',

# History pages
'viewpagelogs'        => 'logsígíí yishʼį́ nisin',
'nohistory'           => '"łahgo ályaaígíí" doo hólǫ́ǫ da/ádin.',
'currentrev-asof'     => 'kʼadígíí ($1)',
'revisionasof'        => '$1 yę́ędą́ą́ʼ',
'previousrevision'    => '←',
'nextrevision'        => '→',
'currentrevisionlink' => 'kʼadígíí',
'cur'                 => 'kʼadígíí',
'histfirst'           => 'bee hodeeshzhiizh',
'histlast'            => 'bee nihoolʼá',

# Diffs
'history-title' => 'łahgo ályaaígíí: "$1"',
'editundo'      => 'ńdíídleeł!',

# Search results
'searchresulttext'      => '{{SITENAME}} bikáaʼgi haʼnitáhígíí bíhoołʼaahgo biniiyé [[{{MediaWiki:Helppage}}|{{int:help}}]] yidíiłtah.',
'searchsubtitle'        => '\'\'\'[[:$1]]\'\'\' hanínítą́ą́ʼ ([[Special:Prefixindex/$1|naaltsoos "$1" wolyéhígíí tʼáá ałtso]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" baa atiinígíí tʼáá ałtso]])',
'searchsubtitleinvalid' => "'''$1''' hanínítą́ą́ʼ",
'noexactmatch'          => '\'\'\'Naaltssos "$1" wolyéhígíí ádin.\'\'\' Naaltsoos "$1" yaa halneʼígíí [[:$1|ánílééh]] nínízinísh?',
'noexactmatch-nocreate' => "'''Naaltssos \"\$1\" wolyéhígíí ádin.'''",
'notitlematches'        => 'naaltsoos ádin',
'viewprevnext'          => '($1) ($2) ($3) shinááł',
'search-result-size'    => '$1 ({{PLURAL:$2|1 saad bikáaʼgi hólǫ́|$2 saad bikáaʼgi dahólǫ́}})',
'search-redirect'       => '("$1"dę́ę́ʼ)',
'search-suggest'        => '"$1" hainítáásh shį́į́?',
'search-interwiki-more' => '(nááná...)',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|łahgo ályaaígíí|łahgo ályaaígíí}}',
'recentchanges'                  => 'Áníídí łahgo ályaaígíí',
'recentchanges-legend'           => '✔',
'recentchanges-feed-description' => 'áníídí łahgo ályaaígíí',
'rcnote'                         => "<div style=\"text-size:14px; border:0px solid grey; padding:10px; background:#bbeeff;\">{{PLURAL:\$2|jį́į́dą́ą́ʼ |}} {{PLURAL:\$1|'''1''' łahgo ályaaígíí tʼéiyá|'''\$1''' łahgo ályaaígíí}}, {{PLURAL:\$2||'''\$2di''' yiskánídą́ą́ʼ kojįʼ, }} <small>(\$5, \$4)</small>",
'rclistfrom'                     => '$1 +',
'rcshowhideminor'                => 'naaltsoos tʼáá áłtsʼíísígo łahgo ályaaígíí $1',
'rcshowhidebots'                 => "''bots''ígíí $1",
'rcshowhideliu'                  => 'chodayoołʼįįhí ééhozinígíí $1',
'rcshowhideanons'                => 'chodayoołʼįįhí doo ééhozinii (IP) $1',
'rcshowhidemine'                 => 'akʼeʼshełchínígíí $1',
'rclinks'                        => '<div style="background:#bbeeff;">
*($1) łahgo ályaaígíí
*($2) yiskánídą́ą́ʼ kojįʼ <br />
<small>$3</small>',
'hist'                           => 'łgá',
'hide'                           => 'doo yishʼį́ nisin da.',
'show'                           => 'yishʼį́ nisin.',
'minoreditletter'                => 'tʼ',
'newpageletter'                  => 'NÁ',

# Upload
'upload'        => 'Eʼelyaaígíí biiʼ hééł áshłééh nisin',
'uploadedimage' => '"[[$1]]" biiʼ hééł áyiilaa',

# File description page
'filehist'            => 'łahgo ályaaígíí',
'filehist-current'    => 'kʼadígíí',
'filehist-user'       => 'Choyoołʼįįhí',
'filehist-dimensions' => 'naaniigo/náásee',
'filehist-comment'    => 'haneʼ',
'imagelinks'          => 'naaltsoos díí kweʼé eʼelyaaígíí chodayoołʼįįhígíí',
'linkstoimage'        => '{{PLURAL:$1|1 naaltsoos díí eʼelyaaígíí choyoołʼįįh|$1 naaltsoos díí eʼelyaaígíí chodayoołʼįįh}}:',
'sharedupload'        => 'Díí kweʼé eʼelyaaígíí $1 bitsʼą́ą́dę́ę́ʼ.',

# Miscellaneous special pages
'newpages'      => 'Naaltsoos ániidí',
'move'          => 'hidishnááh nisin',
'pager-newer-n' => '{{PLURAL:$1| < 1| < $1}}',
'pager-older-n' => '{{PLURAL:$1| 1 > | $1 > }}',

# Book sources
'booksources-go' => '✔',

# Special:AllPages
'allpages'       => 'naaltsoosígíí tʼáá ałtso',
'alphaindexline' => '$1 - $2',
'allarticles'    => 'naaltsoosígíí tʼáá ałtso',
'allpagessubmit' => '✔',

# Special:Log/newusers
'newuserlog-create-entry' => 'choyoołʼįįhí ániidí',

# E-mail user
'emailuser' => 'E-mail bichʼįʼ áshłééh nisin',

# Watchlist
'watchlist'         => 'bikʼi déshʼį́į́ʼígíí',
'mywatchlist'       => 'bikʼi déshʼį́į́ʼígíí',
'watchlistfor'      => "('''$1''')",
'addedwatchtext'    => "[[Special:Watchlist|Naaltsoos bikʼi díníʼį́į́ʼígíí]] bíhiniidééh. \"[[:\$1]]\" kʼad bikʼi díníʼį́į́ʼ.<br />Nááná [[Special:RecentChanges|\"áníídí łahgo ályaaígíí\"]] bikáaʼgi díí naaltsoos kʼad kodóó '''ditą́ą''' dooleeł.",
'removedwatchtext'  => '"[[:$1]]" kʼad doo [[Special:Watchlist|bikʼi díníʼį́į]] da.',
'watch'             => 'bikʼi déshʼį́į́ʼ nisin',
'unwatch'           => 'doo bikʼi déshʼį́įʼ nisin da',
'watchlist-details' => '{{PLURAL:$1|$1 naaltsoos|$1 naaltsoos}} bikʼi díníʼį́į́ʼ',
'wlshowlast'        => '<div style="text-size:14px; padding:10px; background:#bbeeff;"><small>
* ( $1 ) ahééʼílkidę́ędą́ą́ʼ kojįʼ
* ( $2 ) yiskánídą́ą́ʼ kojįʼ
* ( $3 )</small>',
'watchlist-options' => '✔',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '(...)',
'unwatching' => '(...)',

# Delete
'deletedarticle' => '"[[$1]]" yiyiisxį́',

# Protect
'protectedarticle' => '"[[$1]]" bichʼą́ą́h ííyá.',

# Namespace form on various pages
'namespace'      => 'Naaltsoos bizhiʼ:',
'blanknamespace' => '(Íiyisíí)',

# Contributions
'contributions'       => 'akʼeʼeeshchínígíí',
'contributions-title' => '$1 akʼeʼeeshchínígíí',
'mycontris'           => 'akʼeʼshełchínígíí',
'contribsub2'         => '$1 akʼeʼeeshchínígíí ($2)',
'uctop'               => '(← bee nihoolʼá)',

'sp-contributions-newbies'  => 'ádaaniidí akʼeʼeeshchínígíí tʼéiyá',
'sp-contributions-search'   => 'akʼeʼeeshchínígíí hanishtá nisin',
'sp-contributions-username' => 'IP/Choyoołʼįįhí bizhiʼ:',
'sp-contributions-submit'   => 'hanishtá',

# What links here
'whatlinkshere'            => 'linksígíí díí naaltsoos baa atiin',
'whatlinkshere-title'      => 'linksígíí "$1" baa atiin',
'whatlinkshere-page'       => 'Naaltsoos:',
'linkshere'                => "'''[[:$1]]''' baa atiinígíí:",
'nolinkshere'              => "'''\"[[:\$1]]\"''' baa atiinígíí doo hólǫ́ǫ da.",
'nolinkshere-ns'           => "'''\"[[:\$1]]\"''' baa atiinígíí doo hólǫ́ǫ da.",
'whatlinkshere-prev'       => '{{PLURAL:$1|←|← $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|→|→ $1}}',
'whatlinkshere-links'      => '← linkígíí',
'whatlinkshere-hideredirs' => 'dah astsihígíí $1',
'whatlinkshere-hidetrans'  => 'transclusions $1',
'whatlinkshere-hidelinks'  => 'linksígíí $1',
'whatlinkshere-filters'    => 'bee agháʼníldéhí',

# Block/unblock
'contribslink'  => 'akʼeʼeeshchínígíí',
'blocklogentry' => '[[$1]] bichʼą́ą́h niiníyá ($2 $3)',

# Move page
'movepage-moved'  => '<big>\'\'\'"$1" kʼad "$2" wolyé\'\'\'</big>',
'1movedto2'       => 'naaltsoos "[[$1]]" → "[[$2]]"-góó yidiyiznááʼ',
'1movedto2_redir' => 'naaltsoos "[[$1]]" → "[[$2]]"-góó yidiyiznááʼ (dah astsihí ńtʼę́ę́ʼ)',
'revertmove'      => 'ńdíídleeł!',

# Tooltip help for the actions
'tooltip-undo' => '"ńdíídleeł!" has this edit reverted and opens the edit form in preview mode.
It allows adding a reason in the summary.',

# Skin names
'skinname-monobook' => "NaaltsoosŁáa'ígíí",

# Browsing diffs
'previousdiff' => '←',
'nextdiff'     => '→',

# Media information
'file-info-size'       => '($1 × $2 pixels - $3, MIME type: $4)',
'file-nohires'         => "<small>''higher resolution''ígíí ádin.</small>",
'show-big-image'       => "''full resolution''ígíí yishʼį́ nisin",
'show-big-image-thumb' => '<small>$1 × $2 pixels</small>',

# External editor support
'edit-externally' => "''external application''ígíí choinishʼįįhgo díí eʼelyaaígíí łahgo áshłééh nisin.",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tʼáá ałtso',
'namespacesall' => 'tʼáá ałtso',

# Watchlist editing tools
'watchlisttools-view' => 'łahgo ályaaígíí yishʼį́ nisin',
'watchlisttools-edit' => 'naaltsoos bikʼi déshʼį́į́ʼígíí bikáaʼgi hólónígíí yishʼį́ dóó łahgo áshłééh nisin',
'watchlisttools-raw'  => 'XML yishʼį́ dóó łahgo áshłééh nisin',

# Special:SpecialPages
'specialpages' => 'Naaltsoos spéshelígíí',

);
