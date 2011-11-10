<?php
/** Navajo (Diné bizaad)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Reedy
 * @author Seb az86556
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Special',
	NS_TALK             => 'Naaltsoos_baa_yáshtiʼ',
	NS_USER             => 'Choyoołʼįįhí',
	NS_USER_TALK        => 'Choyoołʼįįhí_bichʼįʼ_yáshtiʼ',
	NS_PROJECT_TALK     => '$1_baa_yáshtiʼ',
	NS_FILE             => 'Eʼelyaaígíí',
	NS_FILE_TALK        => 'Eʼelyaaígíí_baa_yáshtiʼ',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_baa_yáshtiʼ',
	NS_TEMPLATE         => 'Bee_álnééhí',
	NS_TEMPLATE_TALK    => 'Bee_álnééhí_baa_yáshtiʼ',
	NS_HELP             => 'Anáʼálwoʼ',
	NS_HELP_TALK        => 'Anáʼálwoʼ_baa_yáshtiʼ',
	NS_CATEGORY         => 'Tʼááłáhági_átʼéego',
	NS_CATEGORY_TALK    => 'Tʼááłáhági_átʼéego_baa_yáshtiʼ',
);

$datePreferences = false;

$messages = array(
# Dates
'sunday'        => 'Damóogo',
'monday'        => 'Damóo biiskání',
'tuesday'       => 'Damóodóó naakiską́o',
'wednesday'     => 'Damóodóó tágí jį́',
'thursday'      => "Damóodóó dį́į́' yiską́o",
'friday'        => "Nda'iiníísh",
'saturday'      => 'Yiską́ damóo',
'january'       => 'Yas Niłtʼees',
'february'      => 'Atsá Biyáázh',
'march'         => 'Wóózhchʼį́į́d',
'april'         => 'Tʼą́ą́chil',
'may_long'      => 'Tʼą́ą́tsoh',
'june'          => 'Yaʼiishjááshchilí',
'july'          => 'Yaʼiishjáástsoh',
'august'        => 'Biniʼantʼą́ą́tsʼózí',
'september'     => 'Biniʼantʼą́ą́tsoh',
'october'       => 'Ghąąjį',
'november'      => 'Níłchʼitsʼósí',
'december'      => 'Níłchʼitsoh',
'january-gen'   => 'Yas Niłtʼees',
'february-gen'  => 'Atsá Biyáázh',
'march-gen'     => 'Wóózhchʼį́į́d',
'april-gen'     => 'Tʼą́ą́chil',
'may-gen'       => 'Tʼą́ą́tsoh',
'june-gen'      => 'Yaʼiishjááshchilí',
'july-gen'      => 'Yaʼiishjáástsoh',
'august-gen'    => 'Biniʼantʼą́ą́tsʼózí',
'september-gen' => 'Biniʼantʼą́ą́tsoh',
'october-gen'   => 'Ghąąjį',
'november-gen'  => 'Níłchʼitsʼósí',
'december-gen'  => 'Níłchʼitsoh',
'jan'           => 'Ynts',
'feb'           => 'Atsb',
'mar'           => 'Wozh',
'apr'           => 'Tchi',
'may'           => 'Ttso',
'jun'           => 'Yjsh',
'jul'           => 'Yjts',
'aug'           => 'Btsz',
'sep'           => 'Btsx',
'oct'           => 'Ghąj',
'nov'           => 'Ntss',
'dec'           => 'Ntsx',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Naaltsoos biiʼ sinilí|Naaltsoos biiʼ sinilí}}',
'category_header'        => 'Naaltsoos biiʼ sinilí "$1" biyiʼ dahólónígíí',
'subcategories'          => 'Hanálzhoʼí',
'hidden-categories'      => '{{PLURAL:$1|Naaltsoos biiʼ sinilí (doo yitʼínii)|Naaltsoos biiʼ sinilí (doo yitʼínii)}}',
'category-subcat-count'  => '{{PLURAL:$2|1 Hanálzhoʼí.|{{PLURAL:$1|1 Hanálzhoʼí|$2 Hanálzhoʼí}} - ($1).}}',
'category-article-count' => "{{PLURAL:$2|'''1 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ hólǫ.|{{PLURAL:$2|'''1 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ hólǫ|'''$2 naaltsoos''' díí naaltsoos biiʼ sinilí biyiʼ dahólǫ}} - ($1)}}",
'listingcontinuesabbrev' => 'nááná...',

'cancel'     => 'tʼóó ánássįįh',
'mytalk'     => 'haneʼ shichʼįʼ ályaaígíí',
'navigation' => 'naaltsoosígíí',

'errorpagetitle'   => 'adziih',
'tagline'          => "''{{SITENAME}}'' bitsʼą́ą́dę́ę́ʼ",
'help'             => 'Anáʼálwoʼ',
'search'           => 'hanishtá nisin',
'searchbutton'     => 'tʼáá yíní átʼéegi',
'searcharticle'    => 'díí saad tʼéiyá',
'history'          => 'łahgo ályaaígíí',
'history_short'    => 'łahgo ályaaígíí',
'printableversion' => '"Print" áshłééh nisin',
'permalink'        => 'Díí naaltsoos bi-"url"',
'edit'             => 'Łahgo áshłééh',
'create'           => 'áshłééh nisin',
'editthispage'     => 'díí naaltsoos łahgo áshłééh',
'delete'           => 'sisxé (delete)',
'protect'          => "bich'ą́ą́h iishááh nisin (protect)",
'newpage'          => 'Naaltsoos ániidí',
'talkpage'         => 'díí kweʼé naaltsoos baa yáshtiʼ nisin',
'talkpagelinktext' => 'bichʼįʼ yáshtiʼ',
'specialpage'      => 'Naaltsoos spéshelígíí',
'personaltools'    => 'bee naashnishí',
'talk'             => 'baa yáshtiʼ nisin',
'views'            => 'naaltsoosígíí',
'toolbox'          => 'bee naʼanishí',
'otherlanguages'   => 'saad',
'redirectedfrom'   => '("$1"dę́ę́ʼ)',
'redirectpagesub'  => 'dah astsihígíí',
'lastmodifiedat'   => 'Díí naaltsoos $1/$2 łahgo ályaa.',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} baa haneʼ',
'aboutpage'            => 'Project: baa haneʼ',
'copyright'            => 'Díí naaltsoos bikáaʼgi saad shijaaʼígíí "$1" beehazʼą́ąnii bikʼehgo choidííłįįł',
'edithelp'             => 'anáʼálwoʼ',
'edithelppage'         => 'Help:Haitʼéegoshąʼ naaltsoos łahgo áshłééh?',
'helppage'             => 'Help:Bee hadítʼéhígíí',
'mainpage'             => 'Íiyisíí Naaltsoos',
'mainpage-description' => 'Íiyisíí Naaltsoos',

'retrievedfrom'      => '"$1" bitsʼą́ą́dę́ę́ʼ',
'youhavenewmessages' => 'Háíshį́į́ $1 nichʼįʼ áyiilaa. <small>($2)</small>',
'newmessageslink'    => 'haneʼ ániidígíí',
'editsection'        => 'łahgo áshłééh',
'editold'            => 'łahgo áshłééh',
'editlink'           => 'łahgo áshłééh',
'viewsourcelink'     => 'XML yishʼį́ nisin',
'toc'                => 'bikáaʼgi hólónígíí',
'showtoc'            => 'yishʼį́ nisin',
'hidetoc'            => 'doo yishʼį́ nisin da',
'site-rss-feed'      => '$1 biRSS Feed',
'site-atom-feed'     => '$1 biAtom Feed',
'page-rss-feed'      => '"$1" biRSS Feed',
'page-atom-feed'     => '"$1" biAtom Feed',
'red-link-title'     => '$1 (ádin)',

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
'missingarticle-rev' => '(łahgo ályaaígíí #$1)',
'viewsource'         => 'XML yishʼį́ nisin',

# Login and logout pages
'yourname'                => 'Choyoołʼįįhí bizhiʼ:',
'yourpassword'            => 'Passwordígíí:',
'remembermypassword'      => 'shipassword béédíínih (for a maximum of $1 {{PLURAL:$1|day|days}})',
'nav-login-createaccount' => 'Log in / accountígíí ádíílííł',
'nologinlink'             => 'Accountígíí ádíílííł',
'mailmypassword'          => 'passwordígíí ániidí shichʼįʼ ádíílííł (e-mail)',

# Edit page toolbar
'link_sample'    => 'Linkígíí',
'extlink_sample' => 'http://www.example.com linkígíí',

# Edit pages
'minoredit'              => 'tʼáá áłtsʼíísígo tʼéiyá naaltsoos łahgo áshłaa',
'watchthis'              => 'shinááł nisin',
'savearticle'            => '✔ bee lą́ ashłeeh',
'preview'                => 'dooleełígíí',
'showpreview'            => 'dooleełígíí yishʼį́ nisin',
'showdiff'               => 'łahgo áshłaaígíí yishʼį́ nisin',
'anoneditwarning'        => "<div style=\"background:#aaddff; text-align:center;\">'''Doo \"login\" íinilaa da.''' Éí biniinaa nizhiʼ doo ééhozin da áádóó ni-''IP'' naaltsoos bikááʼ náázhdíyóosoh.<br /><small>('''You are not logged in.''' Your name is thus unknown, and your IP will be recorded.)</small></div>",
'newarticle'             => '(Naaltsoos ániidí)',
'previewnote'            => "'''Díí kweʼé éí \"dooleełígíí\" tʼéiyá átʼé!'''
::<small>'''(This is only a preview.)'''</small>",
'editing'                => '"$1" łahgo áshłééh...',
'templatesused'          => '"bee álnééhé" naaltsoos bikáaʼgi hólǫ́:',
'templatesusedpreview'   => '"bee álnééhé" naaltsoos bikáaʼgi dooleełígíí:',
'template-protected'     => '(administratorsígíí tʼéiyá)',
'template-semiprotected' => '(chodayoołʼįįhí doo ééhozinii díí naaltsoos doo łahgo ádayóleʼ átʼée da)',
'hiddencategories'       => 'Díí kweʼé naaltsoos éí {{PLURAL:$1|1 Naaltsoos biiʼ sinilí (doo yitʼínii)|$1 Naaltsoos biiʼ sinilí (doo yitʼínii)}} yiiʼ siʼą́:',

# History pages
'viewpagelogs'        => 'logsígíí yishʼį́ nisin',
'nohistory'           => '"łahgo ályaaígíí" doo hólǫ́ǫ da/ádin.',
'currentrev-asof'     => 'kʼadígíí ($1)',
'revisionasof'        => '$1 yę́ędą́ą́ʼ',
'currentrevisionlink' => 'kʼadígíí',
'cur'                 => 'kʼadígíí',
'histfirst'           => 'bee hodeeshzhiizh',
'histlast'            => 'bee nihoolʼá',

# Revision deletion
'rev-delundel' => 'yishʼį́ nisin/doo yishʼį́ nisin da',

# Diffs
'history-title' => 'łahgo ályaaígíí: "$1"',
'editundo'      => 'ńdíídleeł!',

# Search results
'searchresulttext'      => '{{SITENAME}} bikáaʼgi haʼnitáhígíí bíhoołʼaahgo biniiyé [[{{MediaWiki:Helppage}}|{{int:help}}]] yidíiłtah.',
'searchsubtitle'        => '\'\'\'[[:$1]]\'\'\' hanínítą́ą́ʼ ([[Special:Prefixindex/$1|naaltsoos "$1" wolyéhígíí tʼáá ałtso]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" baa atiinígíí tʼáá ałtso]])',
'searchsubtitleinvalid' => "'''$1''' hanínítą́ą́ʼ",
'notitlematches'        => 'naaltsoos ádin',
'viewprevnext'          => '($1) ($2) ($3) shinááł',
'search-result-size'    => '$1 ({{PLURAL:$2|1 saad bikáaʼgi hólǫ́|$2 saad bikáaʼgi dahólǫ́}})',
'search-redirect'       => '("$1"dę́ę́ʼ)',
'search-suggest'        => '"$1" hainítáásh shį́į́?',
'search-interwiki-more' => '(nááná...)',
'powersearch-redir'     => 'dah astsihígíí yishʼį́ nisin.',

# Preferences page
'mypreferences' => 'siłkidígíí',

# Groups
'group-sysop' => 'Administratorsígíí',

'grouppage-sysop' => '{{ns:project}}:Administratorsígíí',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|łahgo ályaaígíí|łahgo ályaaígíí}}',
'recentchanges'                  => 'Áníídí łahgo ályaaígíí',
'recentchanges-feed-description' => 'áníídí łahgo ályaaígíí',
'rcnote'                         => "{{PLURAL:$2|jį́į́dą́ą́ʼ |}} {{PLURAL:$1|'''1''' łahgo ályaaígíí tʼéiyá|'''$1''' łahgo ályaaígíí}}, {{PLURAL:$2||'''$2di''' yiskánídą́ą́ʼ kojįʼ, }} ($5, $4)",
'rcshowhideminor'                => 'naaltsoos tʼáá áłtsʼíísígo łahgo ályaaígíí $1',
'rcshowhidebots'                 => "''bots''ígíí $1",
'rcshowhideliu'                  => 'chodayoołʼįįhí ééhozinígíí $1',
'rcshowhideanons'                => 'chodayoołʼįįhí doo ééhozinii (IP) $1',
'rcshowhidemine'                 => 'akʼeʼshełchínígíí $1',
'rclinks'                        => '*($1) łahgo ályaaígíí
*($2) yiskánídą́ą́ʼ kojįʼ <br />
$3',
'hist'                           => 'łgá',
'hide'                           => 'doo yishʼį́ nisin da.',
'show'                           => 'yishʼį́ nisin.',
'minoreditletter'                => 'tʼ',
'newpageletter'                  => 'NÁ',

# Recent changes linked
'recentchangeslinked-page' => 'naaltsoos:',

# Upload
'upload'        => 'Eʼelyaaígíí biiʼ hééł áshłééh nisin',
'uploadedimage' => '"[[$1]]" biiʼ hééł áyiilaa',

# File description page
'filehist'            => 'łahgo ályaaígíí',
'filehist-current'    => 'kʼadígíí',
'filehist-thumb'      => 'thumbnailígíí',
'filehist-thumbtext'  => 'thumbnailígíí ($1)',
'filehist-user'       => 'Choyoołʼįįhí',
'filehist-dimensions' => 'naaniigo/náásee',
'filehist-comment'    => 'haneʼ',
'imagelinks'          => 'naaltsoos díí kweʼé eʼelyaaígíí chodayoołʼįįhígíí',
'linkstoimage'        => '{{PLURAL:$1|1 naaltsoos díí eʼelyaaígíí choyoołʼįįh|$1 naaltsoos díí eʼelyaaígíí chodayoołʼįįh}}:',
'sharedupload'        => 'Díí kweʼé eʼelyaaígíí $1 bitsʼą́ą́dę́ę́ʼ.',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|byte átʼé|bytes átʼé}}',
'newpages'     => 'Naaltsoos ániidí',
'move'         => 'hidishnááh nisin',
'movethispage' => 'díí naaltsoos hidishnááh nisin',

# Special:AllPages
'allpages'    => 'naaltsoosígíí tʼáá ałtso',
'allarticles' => 'naaltsoosígíí tʼáá ałtso',

# Special:LinkSearch
'linksearch' => 'linksígíí tłʼóoʼdi siʼánígíí',

# E-mail user
'emailuser' => 'E-mail bichʼįʼ áshłééh nisin',

# Watchlist
'watchlist'         => 'bikʼi déshʼį́į́ʼígíí',
'mywatchlist'       => 'bikʼi déshʼį́į́ʼígíí',
'addedwatchtext'    => "[[Special:Watchlist|Naaltsoos bikʼi díníʼį́į́ʼígíí]] bíhiniidééh. \"[[:\$1]]\" kʼad bikʼi díníʼį́į́ʼ.<br />Nááná [[Special:RecentChanges|\"áníídí łahgo ályaaígíí\"]] bikáaʼgi díí naaltsoos kʼad kodóó '''ditą́ą''' dooleeł.",
'removedwatchtext'  => '"[[:$1]]" kʼad doo [[Special:Watchlist|bikʼi díníʼį́į]] da.',
'watch'             => 'bikʼi déshʼį́į́ʼ nisin',
'watchthispage'     => 'díí naaltsoos bikʼi déshʼį́į́ʼ nisin',
'unwatch'           => 'doo bikʼi déshʼį́įʼ nisin da',
'watchlist-details' => '{{PLURAL:$1|$1 naaltsoos|$1 naaltsoos}} bikʼi díníʼį́į́ʼ',
'wlshowlast'        => '<small>
* ( $1 ) ahééʼílkidę́ędą́ą́ʼ kojįʼ
* ( $2 ) yiskánídą́ą́ʼ kojįʼ
* ( $3 )</small>',
'watchlist-options' => '✔',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => '(...)',
'unwatching' => '(...)',

# Protect
'protectedarticle'          => '"[[$1]]" bichʼą́ą́h ííyá.',
'modifiedarticleprotection' => '"[[$1]]" biprotection level łahgo ályaa',

# Namespace form on various pages
'namespace'      => 'Naaltsoos bizhiʼ:',
'invert'         => 'binaashii',
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
'isredirect'               => 'dah astsihígíí',
'istemplate'               => 'bee álnééhí',
'isimage'                  => 'eʼelyaaígíí',
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
'movearticle'    => 'naaltsoos:',
'newtitle'       => 'naaltsoos bizhiʼ ániidí:',
'move-watch'     => 'shinááł',
'movepagebtn'    => '✔ bee lą́ ashłeeh',
'movepage-moved' => '\'\'\'"$1" kʼad "$2" wolyé\'\'\'',
'revertmove'     => 'ńdíídleeł!',

# Skin names
'skinname-monobook' => "NaaltsoosŁáa'ígíí",

# Metadata
'metadata'          => 'Metadataígíí',
'metadata-expand'   => 'yishʼį́ nisin',
'metadata-collapse' => 'doo yishʼį́ nisin da',

# External editor support
'edit-externally'      => "''external application''ígíí choinishʼįįhgo díí eʼelyaaígíí łahgo áshłééh nisin.",
'edit-externally-help' => '([//www.mediawiki.org/wiki/Manual:External_editors anáʼálwoʼ] (Bilagáanakʼehjí))',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tʼáá ałtso',
'namespacesall' => 'tʼáá ałtso',
'monthsall'     => 'tʼáá ałtso',

# Watchlist editing tools
'watchlisttools-view' => 'łahgo ályaaígíí yishʼį́ nisin',
'watchlisttools-edit' => 'naaltsoos bikʼi déshʼį́į́ʼígíí bikáaʼgi hólónígíí yishʼį́ dóó łahgo áshłééh nisin',
'watchlisttools-raw'  => 'XML yishʼį́ dóó łahgo áshłééh nisin',

# Special:SpecialPages
'specialpages' => 'Naaltsoos spéshelígíí',

);
