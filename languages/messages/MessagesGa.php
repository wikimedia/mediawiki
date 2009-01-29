<?php
/** Irish (Gaeilge)
 *
 * @ingroup Language
 * @file
 *
 * @author Alison
 * @author Kwekubo
 * @author Moilleadóir
 * @author Spacebirdy
 * @author Stifle
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$magicWords = array(
	#   ID	                         CASE  SYNONYMS
	'redirect'               => array( 0,    '#redirect', '#athsheoladh' ),
	'notoc'                  => array( 0,    '__NOTOC__', '__GANCÁ__'              ),
	'forcetoc'               => array( 0,    '__FORCETOC__',         '__CÁGACHUAIR__'  ),
	'toc'                    => array( 0,    '__TOC__', '__CÁ__'                ),
	'noeditsection'          => array( 0,    '__NOEDITSECTION__',    '__GANMHÍRATHRÚ__'  ),
	'currentmonth'           => array( 1,    'CURRENTMONTH',  'MÍLÁITHREACH'  ),
	'currentmonthname'       => array( 1,    'CURRENTMONTHNAME',     'AINMNAMÍOSALÁITHREAÍ'  ),
	'currentmonthnamegen'    => array( 1,    'CURRENTMONTHNAMEGEN',  'GINAINMNAMÍOSALÁITHREAÍ'  ),
	'currentmonthabbrev'     => array( 1,    'CURRENTMONTHABBREV',   'GIORRÚNAMÍOSALÁITHREAÍ'  ),
	'currentday'             => array( 1,    'CURRENTDAY',           'LÁLÁITHREACH'  ),
	'currentdayname'         => array( 1,    'CURRENTDAYNAME',       'AINMANLAELÁITHRIGH'  ),
	'currentyear'            => array( 1,    'CURRENTYEAR',          'BLIAINLÁITHREACH'  ),
	'currenttime'            => array( 1,    'CURRENTTIME',          'AMLÁITHREACH'  ),
	'numberofarticles'       => array( 1,    'NUMBEROFARTICLES',     'LÍONNANALT'  ),
	'numberoffiles'          => array( 1,    'NUMBEROFFILES',        'LÍONNAGCOMHAD'  ),
	'pagename'               => array( 1,    'PAGENAME',             'AINMANLGH'  ),
	'pagenamee'              => array( 1,    'PAGENAMEE',            'AINMANLGHB'  ),
	'namespace'              => array( 1,    'NAMESPACE',            'AINMSPÁS'  ),
	'msg'                    => array( 0,    'MSG:',                 'TCHT:'  ),
	'subst'                  => array( 0,    'SUBST:',               'IONAD:'  ),
	'msgnw'                  => array( 0,    'MSGNW:',               'TCHTFS:'  ),
	'img_thumbnail'          => array( 1,    'thumbnail', 'thumb',   'mionsamhail', 'mion'  ),
	'img_right'              => array( 1,    'right',                'deas'  ),
	'img_left'               => array( 1,    'left',                 'clé'  ),
	'img_none'               => array( 1,    'none',                 'faic'  ),
	'img_center'             => array( 1,    'center', 'centre',     'lár'  ),
	'img_framed'             => array( 1,    'framed', 'enframed', 'frame', 'fráma', 'frámaithe' ),
	'int'                    => array( 0,    'INT:', 'INMH:'                   ),
	'sitename'               => array( 1,    'SITENAME',             'AINMANTSUÍMH'  ),
	'ns'                     => array( 0,    'NS:', 'AS:'                    ),
	'localurl'               => array( 0,    'LOCALURL:',            'URLÁITIÚIL'  ),
	'localurle'              => array( 0,    'LOCALURLE:',           'URLÁITIÚILB'  ),
	'server'                 => array( 0,    'SERVER',               'FREASTALAÍ'  ),
	'servername'             => array( 0,    'SERVERNAME',            'AINMANFHREASTALAÍ' ),
	'scriptpath'             => array( 0,    'SCRIPTPATH',           'SCRIPTCHOSÁN'  ),
	'grammar'                => array( 0,    'GRAMMAR:',             'GRAMADACH:'  ),
	'notitleconvert'         => array( 0,    '__NOTITLECONVERT__', '__NOTC__', '__GANTIONTÚNADTEIDEAL__', '__GANTT__'),
	'nocontentconvert'       => array( 0,    '__NOCONTENTCONVERT__', '__NOCC__', '__GANTIONTÚNANÁBHAIR__', '__GANTA__' ),
	'currentweek'            => array( 1,    'CURRENTWEEK',          'SEACHTAINLÁITHREACH'  ),
	'currentdow'             => array( 1,    'CURRENTDOW',           'LÁLÁITHREACHNAS'  ),
	'revisionid'             => array( 1,    'REVISIONID',           'IDANLEASAITHE'  ),
);

$namespaceNames = array(
	NS_MEDIA	          => 'Meán',
	NS_SPECIAL          => 'Speisialta',
	NS_MAIN             => '',
	NS_TALK             => 'Plé',
	NS_USER             => 'Úsáideoir',
	NS_USER_TALK        => 'Plé_úsáideora',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => 'Plé_{{grammar:genitive|$1}}',
	NS_FILE             => 'Íomhá',
	NS_FILE_TALK        => 'Plé_íomhá',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Plé_MediaWiki',
	NS_TEMPLATE         => 'Teimpléad',
	NS_TEMPLATE_TALK    => 'Plé_teimpléid',
	NS_HELP             => 'Cabhair',
	NS_HELP_TALK        => 'Plé_cabhrach',
	NS_CATEGORY         => 'Catagóir',
	NS_CATEGORY_TALK    => 'Plé_catagóire'
);

$namespaceAliases = array(
	'Plé_í­omhá' => NS_FILE_TALK,
	'Múnla' => NS_TEMPLATE,
	'Plé_múnla' => NS_TEMPLATE_TALK,
	'Rang' => NS_CATEGORY
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Folínte faoi naisc:',
'tog-highlightbroken'         => 'Formáidigh na naisc briste, <a href="" class="new">mar seo</a>
(rogha malartach: mar seo<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Comhfhadaigh na paragraif',
'tog-hideminor'               => 'Ná taispeáin fo-athruithe i measc na n-athruithe is déanaí',
'tog-extendwatchlist'         => 'Leathnaigh an liosta faire chun gach athrú cuí a thaispeáint',
'tog-usenewrc'                => 'Stíl nua do na hathruithe is déanaí (le JavaScript)',
'tog-numberheadings'          => 'Uimhrigh ceannteidil go huathoibríoch',
'tog-showtoolbar'             => 'Taispeáin an barra uirlisí eagair (JavaScript)',
'tog-editondblclick'          => 'Déghliogáil chun leathanaigh a chur in eagar (JavaScript)',
'tog-editsection'             => 'Cumasaigh mír-eagarthóireacht le naisc mar seo: [athrú]',
'tog-editsectiononrightclick' => 'Cumasaigh mír-eagarthóireacht le deaschliceáil<br /> ar ceannteidil (JavaScript)',
'tog-showtoc'                 => "Taispeáin an clár ábhair (d'ailt le níos mó ná 3 ceannteidil)",
'tog-rememberpassword'        => "Cuimhnigh ar m'fhocal faire",
'tog-editwidth'               => 'Cuir uasmhéid ar an mbosca eagair',
'tog-watchcreations'          => 'Cuir ar mo liosta faire leathanaigh a chruthaím',
'tog-watchdefault'            => 'Déan faire ar leathanaigh a athraím',
'tog-watchmoves'              => 'Cuir ar mo liosta faire leathanaigh a athainmnaím',
'tog-watchdeletion'           => 'Cuir ar mo liosta faire leathanaigh a scriosaím',
'tog-minordefault'            => 'Déan mionathruithe de gach aon athrú, mar réamhshocrú',
'tog-previewontop'            => 'Cuir an réamhamharc os cionn an bhosca eagair, <br />agus ná cuir é taobh thíos de',
'tog-previewonfirst'          => 'Taispeáin réamhamharc don chéad athrú',
'tog-nocache'                 => 'Ciorraigh taisce na leathanach',
'tog-enotifwatchlistpages'    => 'Cuir ríomhphost chugam nuair a athraítear leathanaigh',
'tog-enotifusertalkpages'     => 'Cuir ríomhphost chugam nuair a athraítear mo leathanach phlé úsáideora',
'tog-enotifminoredits'        => 'Cuir ríomhphost chugam nuair a dhéantar mionathruithe chomh maith',
'tog-enotifrevealaddr'        => 'Taispeáin mo sheoladh ríomhphoist i dteachtaireachtaí fógra',
'tog-shownumberswatching'     => 'Taispeán an méid úsáideoirí atá ag faire',
'tog-fancysig'                => 'Sínithe bunúsacha (gan nasc uathoibríoch)',
'tog-externaleditor'          => 'Bain úsáid as eagarthóir seachtrach, mar réamhshocrú',
'tog-externaldiff'            => 'Bain úsáid as difríocht sheachtrach, mar réamhshocrú',
'tog-showjumplinks'           => 'Cumasaigh naisc insroichteachta “léim go dtí”',
'tog-uselivepreview'          => 'Bain úsáid as réamhamharc beo (JavaScript) (Turgnamhach)',
'tog-forceeditsummary'        => 'Cuir in iúl dom nuair a chuirim isteach achoimre eagair folamh',
'tog-watchlisthideown'        => 'Folaigh mo chuid athruithe ón liosta faire',
'tog-watchlisthidebots'       => 'Folaigh athruithe de chuid róbait ón liosta faire',
'tog-watchlisthideminor'      => 'Folaigh mionathruithe ón liosta faire',
'tog-ccmeonemails'            => 'Cuir cóip chugam de gach teactaireacht r-phoist a chuirim chuig úsáideoirí eile',
'tog-diffonly'                => 'Ná taispeáin inneachar an leathanaigh faoi difríochteanna',
'tog-showhiddencats'          => 'Taispeáin chatagóirí folaithe',
'tog-norollbackdiff'          => 'Fág an difr ar lár tar éis athruithe a rolladh siar',

'underline-always'  => 'Ar siúl i gcónaí',
'underline-never'   => 'Múchta',
'underline-default' => 'Mar atá réamhshocraithe sa bhrabhsálaí',

# Dates
'sunday'        => 'an Domhnach',
'monday'        => 'an Luan',
'tuesday'       => 'an Mháirt',
'wednesday'     => 'an Chéadaoin',
'thursday'      => 'an Déardaoin',
'friday'        => 'an Aoine',
'saturday'      => 'an Satharn',
'sun'           => 'Domh',
'mon'           => 'Luan',
'tue'           => 'Máirt',
'wed'           => 'Céad',
'thu'           => 'Déar',
'fri'           => 'Aoine',
'sat'           => 'Sath',
'january'       => 'Eanáir',
'february'      => 'Feabhra',
'march'         => 'Márta',
'april'         => 'Aibreán',
'may_long'      => 'Bealtaine',
'june'          => 'Meitheamh',
'july'          => 'Iúil',
'august'        => 'Lúnasa',
'september'     => 'Meán Fómhair',
'october'       => 'Deireadh Fómhair',
'november'      => 'Mí na Samhna',
'december'      => 'Mí na Nollag',
'january-gen'   => 'Eanáir',
'february-gen'  => 'Feabhra',
'march-gen'     => 'an Mhárta',
'april-gen'     => 'an Aibreáin',
'may-gen'       => 'na Bealtaine',
'june-gen'      => 'an Mheithimh',
'july-gen'      => 'Iúil',
'august-gen'    => 'Lúnasa',
'september-gen' => 'Mheán Fómhair',
'october-gen'   => 'Dheireadh Fómhair',
'november-gen'  => 'na Samhna',
'december-gen'  => 'na Nollag',
'jan'           => 'Ean',
'feb'           => 'Feabh',
'mar'           => 'Márta',
'apr'           => 'Aib',
'may'           => 'Beal',
'jun'           => 'Meith',
'jul'           => 'Iúil',
'aug'           => 'Lún',
'sep'           => 'MFómh',
'oct'           => 'DFómh',
'nov'           => 'Samh',
'dec'           => 'Noll',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Catagóir|Catagóirí}}',
'category_header'                => 'Ailt sa chatagóir "$1"',
'subcategories'                  => 'Fo-chatagóirí',
'category-media-header'          => 'Meáin sa chatagóir "$1"',
'category-empty'                 => "''Níl aon leathanaigh ná méid sa chatagóir ar an am seo.''",
'hidden-categories'              => '{{PLURAL:$1|Catagóir folaithe|Catagóirí folaithe}}',
'hidden-category-category'       => 'Catagóirí folaithe', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2| Níl ach an fo-chatagóir seo a leanas ag an gcatagóir seo.|Tá {{PLURAL:$1|fo-chatagóir|fo-chatagóirí}} ag an gcatagóir seo, as $2 san iomlán.}}',
'category-subcat-count-limited'  => 'Is {{PLURAL:$1|é an líon fochatagóir|$1 iad na líon fochatagóirí}} atá ag an gcatagóir seo ná: $1.',
'category-article-count'         => '{{PLURAL:$2|Níl sa chatagóir seo ach an leathanach seo a leanas.|Tá {{PLURAL:$1|$1 leathanach|$1 leathanaigh}} sa chatagóir seo, as iomlán de $2.}}',
'category-article-count-limited' => 'Tá {{PLURAL:$1|an leathanach|na $1 leathanaigh}} seo a leanas sa chatagóir reatha.',
'category-file-count'            => '{{PLURAL:$2|Tá ach an comhad a leanas sa chatagóir seo|Tá {{PLURAL:$1|an comhad seo|$1 na comhaid seo}} a leanas sa chatagóir seo, as $2 san iomlán.}}',
'category-file-count-limited'    => 'Tá {{PLURAL:$1|an comhad seo|$1 na comhaid seo}} a leanas sa chatagóir reatha.',
'listingcontinuesabbrev'         => 'ar lean.',

'mainpagetext'      => "<big>'''D'éirigh le suiteáil MediaWiki.'''</big>",
'mainpagedocfooter' => 'Féach ar [http://meta.wikimedia.org/wiki/MediaWiki_localisation doiciméid um conas an chomhéadán a athrú]
agus an [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Lámhleabhar úsáideora] chun cabhair úsáide agus fíoraíochta a fháil.',

'about'          => 'Maidir leis',
'article'        => 'Leathanach ábhair',
'newwindow'      => '(a osclófar i bhfuinneog nua)',
'cancel'         => 'Cealaigh',
'qbfind'         => 'Aimsigh',
'qbbrowse'       => 'Brabhsáil',
'qbedit'         => 'Cuir in eagar',
'qbpageoptions'  => 'An leathanach seo',
'qbpageinfo'     => 'Comhthéacs',
'qbmyoptions'    => 'Mo chuid leathanaigh',
'qbspecialpages' => 'Leathanaigh speisialta',
'moredotdotdot'  => 'Tuilleadh...',
'mypage'         => 'Mo leathanach',
'mytalk'         => 'Mo chuid phlé',
'anontalk'       => 'Plé don seoladh IP seo',
'navigation'     => 'Nascleanúint',
'and'            => '&#32;agus',

# Metadata in edit box
'metadata_help' => 'Meiteasonraí:',

'errorpagetitle'    => 'Earráid',
'returnto'          => 'Fill ar ais go $1.',
'tagline'           => 'Ó {{SITENAME}}.',
'help'              => 'Cabhair',
'search'            => 'Cuardaigh',
'searchbutton'      => 'Cuardaigh',
'go'                => 'Gabh',
'searcharticle'     => 'Gabh',
'history'           => 'Stair an lch seo',
'history_short'     => 'Stair',
'updatedmarker'     => 'leasaithe (ó shin mo chuairt dheireanach)',
'info_short'        => 'Eolas',
'printableversion'  => 'Eagrán inphriontáilte',
'permalink'         => 'Nasc buan',
'print'             => 'Priontáil',
'edit'              => 'Athraigh an lch seo',
'create'            => 'Cruthaigh',
'editthispage'      => 'Athraigh an lch seo',
'create-this-page'  => 'Cruthaigh an lch seo',
'delete'            => 'Scrios',
'deletethispage'    => 'Scrios an lch seo',
'undelete_short'    => 'Díscrios {{PLURAL:$1|athrú amháin|$1 athruithe}}',
'protect'           => 'Glasáil',
'protect_change'    => 'athraigh',
'protectthispage'   => 'Glasáil an lch seo',
'unprotect'         => 'Díghlasáil',
'unprotectthispage' => 'Díghlasáil an lch seo',
'newpage'           => 'Leathanach nua',
'talkpage'          => 'Pléigh an lch seo',
'talkpagelinktext'  => 'Plé',
'specialpage'       => 'Leathanach Speisialta',
'personaltools'     => 'Do chuid uirlisí',
'postcomment'       => 'Caint ar an lch',
'articlepage'       => 'Féach ar an alt',
'talk'              => 'Plé',
'views'             => 'Radhairc',
'toolbox'           => 'Bosca uirlisí',
'userpage'          => 'Féach ar lch úsáideora',
'projectpage'       => 'Féach ar lch thionscadail',
'imagepage'         => 'Féach ar lch íomhá',
'mediawikipage'     => 'Féach ar lch teachtaireacht',
'templatepage'      => 'Féach ar leathanach an teimpléad',
'viewhelppage'      => 'Féach ar lch chabhair',
'categorypage'      => 'Féach ar lch chatagóir',
'viewtalkpage'      => 'Féach ar phlé',
'otherlanguages'    => 'I dteangacha eile',
'redirectedfrom'    => '(Athsheolta ó $1)',
'redirectpagesub'   => 'Lch athdhírithe',
'lastmodifiedat'    => 'Athraíodh an leathanach seo ag $2, $1.', # $1 date, $2 time
'viewcount'         => 'Rochtainíodh an leathanach seo {{PLURAL:$1|uair amháin|$1 uaire}}.',
'protectedpage'     => 'Leathanach glasáilte',
'jumpto'            => 'Léim go:',
'jumptonavigation'  => 'nascleanúint',
'jumptosearch'      => 'cuardaigh',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Maidir leis an {{SITENAME}}',
'aboutpage'            => 'Project:Maidir leis',
'copyright'            => 'Tá an t-ábhar le fáil faoin $1.',
'copyrightpagename'    => 'Cóipcheart {{GRAMMAR:genitive|{{SITENAME}}}}',
'copyrightpage'        => '{{ns:project}}:Cóipchearta',
'currentevents'        => 'Cúrsaí reatha',
'currentevents-url'    => 'Project:Cúrsaí reatha',
'disclaimers'          => 'Séanadh',
'disclaimerpage'       => 'Project:Séanadh_ginearálta',
'edithelp'             => 'Cabhair eagarthóireachta',
'edithelppage'         => 'Help:Eagarthóireacht',
'faq'                  => 'Ceisteanna Coiteanta',
'faqpage'              => 'Project:Ceisteanna_Coiteanta',
'helppage'             => 'Help:Clár_ábhair',
'mainpage'             => 'Príomhleathanach',
'mainpage-description' => 'Príomhleathanach',
'policy-url'           => 'Project:Polasaí',
'portal'               => 'Lárionad comhphobail',
'portal-url'           => 'Project:Ionad pobail',
'privacy'              => 'Polasaí príobháideachta',
'privacypage'          => 'Project:Polasaí príobháideachta',

'badaccess'        => 'Earráid ceada',
'badaccess-group0' => 'Níl cead agat an gníomh a roghnaigh tú a dhéanamh.',
'badaccess-groups' => 'Níl cead ag daoine é sin a dhéanamh ach amháin {{PLURAL:$2|duine sa ghrúpa|daoine sna grúpaí}}: $1.',

'versionrequired'     => 'Tá leagan $1 de MediaWiki de dhíth',
'versionrequiredtext' => 'Tá an leagan $1 de MediaWiki riachtanach chun an leathanach seo a úsáid. Féach ar [[Special:Version]]',

'ok'                      => 'Déan',
'retrievedfrom'           => 'Aisghabháil ó "$1"',
'youhavenewmessages'      => 'Tá $1 agat ($2).',
'newmessageslink'         => 'teachtaireachtaí nua',
'newmessagesdifflink'     => 'difear ón leasú leathdhéanach',
'youhavenewmessagesmulti' => 'Tá teachtaireachtaí nua agat ar $1',
'editsection'             => 'athraigh',
'editold'                 => 'athraigh',
'viewsourceold'           => 'féach ar foinse',
'editlink'                => 'cur in eagar',
'viewsourcelink'          => 'féach ar an foinse',
'editsectionhint'         => 'Athraigh mír: $1',
'toc'                     => 'Clár ábhair',
'showtoc'                 => 'taispeáin',
'hidetoc'                 => 'folaigh',
'thisisdeleted'           => 'Breathnaigh nó cuir ar ais $1?',
'viewdeleted'             => 'Féach ar $1?',
'restorelink'             => '{{PLURAL:$1|athrú scriosta amháin|$1 athruithe scriosta}}',
'feedlinks'               => 'Fotha:',
'feed-invalid'            => 'Cineál liostáil fotha neamhbhailí.',
'feed-unavailable'        => 'Níl fotha sindeacáitiú ar fáil',
'site-rss-feed'           => '$1 Fotha RSS',
'site-atom-feed'          => '$1 Fotha Atom',
'page-rss-feed'           => '"$1" Fotha RSS',
'page-atom-feed'          => '"$1" Fotha Atom',
'red-link-title'          => '$1 (níor scríobheadh fós)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Alt',
'nstab-user'      => 'Lch úsáideora',
'nstab-media'     => 'Lch meáin',
'nstab-special'   => 'Speisialta',
'nstab-project'   => 'Tionscadal',
'nstab-image'     => 'Comhad',
'nstab-mediawiki' => 'Teachtaireacht',
'nstab-template'  => 'Teimpléad',
'nstab-help'      => 'Cabhair',
'nstab-category'  => 'Catagóir',

# Main script and global functions
'nosuchaction'      => 'Níl a leithéid de ghníomh ann',
'nosuchactiontext'  => 'Níl aithníonn an vicí an gníomh atá ann sa líonsheoladh.',
'nosuchspecialpage' => 'Níl a leithéid de leathanach speisialta ann',
'nospecialpagetext' => "Níl aithníonn an vicí an leathanach speisialta a d'iarr tú ar.",

# General errors
'error'                => 'Earráid',
'databaseerror'        => 'Earráid sa bhunachar sonraí',
'dberrortext'          => 'Tharla earráid chomhréire in iarratas chuig an mbunachar sonraí.
B\'fhéidir gur fabht sa bhogearraí é seo.
Seo é an t-iarratas deireanach chuig an mbunachar sonrai:
<blockquote><tt>$1</tt></blockquote>
ón bhfeidhm "<tt>$2</tt>".
Thug MySQL an earráid seo: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Tharlaigh earráid chomhréire in iarratas chuig an bhunachar

sonraí.
"$1", ón suim "$2",
ab ea an iarratas fiosraithe deireanach chuig an bhunachar sonrai,
Chuir MySQL an earráid seo ar ais: "$3: $4".',
'noconnect'            => 'Tá brón orainn! Tá roinnt deacrachtaí teicniúla ag an vicí faoi láthair, agus ní féidir leis teagmháil a dhéanamh leis an mbunachar sonraí. <br />
$1',
'nodb'                 => 'Theip rogha an bhunachair sonraí $1',
'cachederror'          => 'Seo í cóip athscríofa den leathanach a raibh tú ag lorg (is dócha nach bhfuil sí bord ar bhord leis an leagan reatha).',
'laggedslavemode'      => "Rabhadh: B'fhéidir nach bhfuil na nuashonrúcháin is déanaí le feiceáil ar an leathanach seo.",
'readonly'             => 'Bunachar sonraí faoi ghlas',
'enterlockreason'      => 'Iontráil cúis don glasáil, agus meastachán
den uair a díghlasálfar an bunachar sonraí.',
'readonlytext'         => 'Tá an bunachar sonraí {{GRAMMAR:genitive|{{SITENAME}}}} glasáilte anois do iontráilí agus athruithe nua
(is dócha go bhfuil sé do gnáthchothabháil).
Tar éis seo, díghlasálfar an bunachar sonraí arís.
Tugadh an míniú seo ag an riarthóir a ghlasáil é:
$1',
'missing-article'      => 'Ní bhfuarthas téacs an leathanaigh ceart, darbh ainm "$1" $2.

De ghnáth, tarlaíonn sé seo nuair a leantar nasc stáire nó difr chuig leathanach a scriosadh.

Murab fhíor é seo, is féidir go bhfuair tú fabht sa bhogearraí. Beimid buíoch duit é a chur in iúl do [[Special:ListUsers/sysop|riarthóir]], chomh maith le URL an suíoimh.',
'missingarticle-rev'   => '(leagan#: $1)',
'missingarticle-diff'  => '(Diof: $1, $2)',
'readonly_lag'         => 'Glasáladh an bunachar sonraí go huathoibríoch, go dtiocfaidh na sclábhfhreastalaithe suas leis an máistirfhreastalaí.',
'internalerror'        => 'Earráid inmhéanach',
'internalerror_info'   => 'Earráid inmhéanach: $1',
'filecopyerror'        => 'Ní féidir an comhad "$1" a chóipeáil go "$2".',
'filerenameerror'      => 'Ní féidir an comhad "$1" a athainmnigh mar "$2".',
'filedeleteerror'      => 'Ní féidir an comhad "$1" a scriosaigh amach.',
'directorycreateerror' => 'Ní féidir an chomhadlann "$1" a chruth.',
'filenotfound'         => 'Ní bhfuarthas an comhad "$1".',
'unexpected'           => 'Luach gan súil leis: "$1"="$2".',
'formerror'            => 'Earráid: ní féidir an foirm a tabhair isteach',
'badarticleerror'      => 'Ní féidir an gníomh seo a dhéanamh ar an leathanach seo.',
'cannotdelete'         => "Ní féidir an leathanach nó comhad sonraithe a scriosaigh.
B'fhéidir gur scrios duine eile é cheana féin.",
'badtitle'             => 'Teideal neamhbhailí',
'badtitletext'         => "Bhí teideal an leathanaigh a d'iarr tú ar neamhbhailí, folamh, nó
teideal idirtheangach nó idirvicí nasctha go mícheart.",
'perfcached'           => 'Fuarthas na sonraí a leanas as taisce, agus is dócha go bhfuil siad as dáta.',
'wrong_wfQuery_params' => 'Paraiméadair mhíchearta don wfQuery()<br />
Feidhm: $1<br />
Iarratas: $2',
'viewsource'           => 'Féach ar fhoinse',
'viewsourcefor'        => 'le haghaidh $1',
'protectedpagetext'    => 'Tá an leathanach seo glasáilte chun coisc ar eagarthóireacht.',
'viewsourcetext'       => 'Is féidir foinse an leathanach seo a fheiceáil ná a cóipeáil:',
'editinginterface'     => "'''Rabhadh:''' Tá tú ag athrú leathanaigh a bhfuil téacs comhéadain do na bogearraí air. Cuirfear athruithe ar an leathanach seo i bhfeidhm ar an gcomhéadan úsáideora.
Más maith leat MediaWiki a aistriú, cuimhnigh ar [http://translatewiki.net/wiki/Main_Page?setlang=ga Betawiki] (tionscadal logánaithe MediaWiki) a úsáid.",
'sqlhidden'            => '(Iarratas SQL folaithe)',
'namespaceprotected'   => "Ní chead agat leathanaigh a chur in eagar san ainmspás '''$1'''.",
'customcssjsprotected' => 'Níl cead agat an leathanach seo a athrú, mar is sainroghanna úsáideora eile atá ann.',
'ns-specialprotected'  => 'Ní féidir leathanaigh speisialta a chur in eagar.',
'titleprotected'       => "Tá an teideal seo cosanta ar chruthú le [[User:$1|$1]].
An fáth ná ''$2''.",

# Virus scanner
'virus-scanfailed'     => 'theip an scan (cód $1)',
'virus-unknownscanner' => 'frithvíreas anaithnid:',

# Login and logout pages
'logouttitle'                => 'Logáil amach',
'logouttext'                 => '<strong>Tá tú logáilte amach anois.</strong>

Is féidir leat an {{SITENAME}} a úsáid fós gan ainm, nó is féidir leat [[Special:UserLogin|logáil isteach arís]] mar an úsáideoir céanna, nó mar úsáideoir eile.
Tabhair faoi deara go taispeáinfear roinnt leathanaigh mar atá tú logáilte isteach fós, go dtí go ghlanfá amach do taisce líonleitheora.',
'welcomecreation'            => '== Tá fáilte romhat, $1! ==

Cruthaíodh do chuntas. Ná déan dearmad athrú a dhéanamh ar do chuid [[Special:Preferences|sainroghanna {{GRAMMAR:genitive|{{SITENAME}}}}]].',
'loginpagetitle'             => 'Logáil isteach',
'yourname'                   => "D'ainm úsáideora",
'yourpassword'               => "D'fhocal faire",
'yourpasswordagain'          => "Athiontráil d'fhocal faire",
'remembermypassword'         => 'Cuimhnigh orm',
'yourdomainname'             => "D'fhearann",
'externaldberror'            => 'Bhí earráid bhunachair sonraí ann maidir le fíordheimhniú seachtrach, nóThere was either an external authentication database error or you are not allowed to update your external account.',
'login'                      => 'Logáil isteach',
'nav-login-createaccount'    => 'Logáil isteach',
'loginprompt'                => 'Tá sé riachtanach fianáin a chur i ngníomh chun logáil isteach a dhéanamh ag {{SITENAME}}.',
'userlogin'                  => 'Logáil isteach',
'logout'                     => 'Logáil amach',
'userlogout'                 => 'Logáil amach',
'notloggedin'                => 'Níl tú logáilte isteach',
'nologin'                    => 'Nach bhfuil logáil isteach agat? $1.',
'nologinlink'                => 'Cruthaigh cuntas',
'createaccount'              => 'Cruthaigh cuntas nua',
'gotaccount'                 => 'An bhfuil cuntas agat cheana féin? $1.',
'gotaccountlink'             => 'Logáil isteach',
'createaccountmail'          => 'le ríomhphost',
'badretype'                  => "D'iontráil tú dhá fhocal faire difriúla.",
'userexists'                 => 'Tá an ainm úsáideora sin in úsáid cheana féin.<br />
Roghnaigh ainm eile agus bain triail eile as.',
'youremail'                  => 'Do ríomhsheoladh:',
'username'                   => "D'ainm úsáideora:",
'uid'                        => 'D’uimhir úsáideora:',
'prefs-memberingroups'       => 'Comhalta {{PLURAL:$1|an ghrúpa|na ghrúpaí}}:',
'yourrealname'               => "D'fhíorainm **",
'yourlanguage'               => 'Teanga',
'yourvariant'                => 'Difríocht teanga:',
'yournick'                   => 'Do leasainm (mar a bheidh i sínithe)',
'badsig'                     => 'Amhsíniú neamhbhailí; scrúdaigh na clibeanna HTML.',
'badsiglength'               => 'Tá an síniú ró-fhada.<br />
Caithfidh sé bheith níos giorra ná {{PLURAL:$1|carachtar|carachtair}}.',
'yourgender'                 => 'Inscne:',
'email'                      => 'Ríomhphost',
'prefs-help-realname'        => '* <strong>Fíorainm</strong> (roghnach): má toghaíonn tú é sin a chur ar fáil, úsáidfear é chun
do chuid dreachtaí a chur i leith tusa.',
'loginerror'                 => 'Earráid leis an logáil isteach',
'prefs-help-email'           => '<strong>Ríomhphost</strong> (roghnach): Leis an tréith seo is féidir teagmháil a dhéanamh leat tríd do leathanach úsáideora nó leathanach phlé gan do sheoladh ríomhphost a thaispeáint.',
'prefs-help-email-required'  => 'Ní foláir seoladh ríomhpoist a thabhairt.',
'nocookiesnew'               => "Cruthaíodh an cuntas úsáideora, ach níl tú logáilte isteach.

Úsáideann {{SITENAME}} fianáin chun úsáideoirí a logáil isteach. 
Tá fianáin díchumasaithe agat. 
Cumasaigh iad le do thoil, agus ansin logáil isteach le d'ainm úsáideora agus d'fhocal faire úrnua.",
'nocookieslogin'             => 'Úsáideann {{SITENAME}} fianáin chun úsáideoirí a logáil isteach. 
Tá fianáin díchumasaithe agat. 
Cumasaigh iad agus bain triail eile as, le do thoil.',
'noname'                     => 'Níor thug tú ainm úsáideora bailí.',
'loginsuccesstitle'          => 'Logáladh isteach thú',
'loginsuccess'               => "'''Tá tú logáilte isteach anois sa {{SITENAME}} mar \"<nowiki>\$1</nowiki>\".'''",
'nosuchuser'                 => 'Níl aon úsáideoir ann leis an ainm "$1".
Cinntigh do litriú, nó [[Special:UserLogin/signup|bain úsáid as an foirm thíos]] chun cuntas úsáideora nua a chruthú.',
'nosuchusershort'            => 'Níl aon úsáideoir ann leis an ainm "<nowiki>$1</nowiki>". Cinntigh do litriú.',
'nouserspecified'            => 'Caithfidh ainm úsáideoir a shonrú.',
'wrongpassword'              => "D'iontráil tú focal faire mícheart.<br />
Bain triail eile as.",
'wrongpasswordempty'         => 'Níor iontráil tú focal faire. Bain triail eile as.',
'passwordtooshort'           => "Tá d'fhocal faire ró-ghearr.
Caithfidh go bhfuil {{PLURAL:$1|1 carachtar|$1 carachtair}} carachtar ann ar a laghad.",
'mailmypassword'             => "Seol m'fhocal faire chugam.",
'passwordremindertitle'      => 'Cuimneachán an fhocail faire ó {{SITENAME}}',
'passwordremindertext'       => 'D\'iarr duine éigin (tusa de réir cosúlachta, ón seoladh IP $1) go sheolfaimis focal faire {{GRAMMAR:genitive|{{SITENAME}}}} nua  ($4).
"$3" an focal faire don úsáideoir "$2" anois. Ba chóir duit lógail isteach anois agus d\'fhocal faire a athrú.

Rachaidh d\'fhocail faire sealadach as feidhm i gceann {{PLURAL:$5|lá amháin|$5 lae}}.',
'noemail'                    => 'Níl aon seoladh ríomhphoist i gcuntas don úsáideoir "$1".',
'passwordsent'               => 'Cuireadh focal nua faire chuig an ríomhsheoladh atá cláraithe do "$1".
Nuair atá sé agat, logáil isteach arís chun fíordheimhniu a dhéanamh.',
'eauthentsent'               => 'Cuireadh teachtaireacht ríomhphoist chuig an seoladh
chun fíordheimhniú a dhéanamh. Chun fíordheimhniú a dhéanamh gur leatsa an cuntas, caithfidh tú glac leis an teachtaireacht sin nó ní sheolfar aon rud eile chuig do chuntas.',
'throttled-mailpassword'     => 'Seoladh meabhrúchán fhocal faire cheana, níos lú ná {{PLURAL:$1|uair amháin|$1 uair}} ó shin.
Chun droch-úsáid a choscadh, ní sheolfar ach meabhrúchán fhocal faire amháin gach {{PLURAL:$1|uair|$1 uair}}.',
'mailerror'                  => 'Tharlaigh earráid leis an seoladh: $1',
'acct_creation_throttle_hit' => 'Gabh ár leithscéal, ach tá {{PLURAL:$1|cuntas amháin|$1 cuntais}} a chruthaigh tú cheana féin.
Ní féidir leat níos mó ná an méid sin a chruthú.',
'emailauthenticated'         => "D'fhíordheimhníodh do sheoladh ríomhphoist ar $2 ar $3.",
'emailnotauthenticated'      => 'Ní dhearna fíordheimhniú ar do sheoladh ríomhphoist fós, agu díchumasaítear na hardtréithe ríomhphoist go dtí go fíordheimhneofaí é (d.c.f.).
Chun fíordheimhniú a dhéanamh, logáil isteach leis an focal faire neamhbhuan atá seolta chugat, nó iarr ar ceann nua ar an leathanach logála istigh.',
'noemailprefs'               => 'Is gá do sheoladh r-phoist a chur isteach chun na gnéithe seo a úsáid.',
'emailconfirmlink'           => 'Deimhnigh do ríomhsheoladh',
'invalidemailaddress'        => 'Ní féidir an seoladh ríomhphoist a ghlacadh leis mar is dócha go bhfuil formáid neamhbhailí aige.
Iontráil seoladh dea-fhormáidte le do thoil, nó glan an réimse sin.',
'accountcreated'             => 'Cúntas cruthaithe',
'accountcreatedtext'         => 'Cruthaíodh cúntas úsáideora le haghaidh $1.',
'createaccount-title'        => 'Cuntas cruthú le {{SITENAME}}',
'createaccount-text'         => 'Chruthaigh duine éigin cuntas do do sheoladh ríomhphoist ar {{SITENAME}} ($4) leis an ainm "$2" agus pasfhocal "$3". Ba cheart duit logáil isteach agus do phasfhocal a athrú anois. Is féidir leat neamhaird a thabhairt don teachtaireacht seo má cruthaíodh trí earráid í.',
'loginlanguagelabel'         => 'Teanga: $1',

# Password reset dialog
'resetpass'                 => "Athshocraigh d'fhocail faire",
'resetpass_announce'        => "Tá tú logáilte isteach le cód sealadach a seoladh chugat i r-phost.
Chun d'iarratas logáil isteach a chríochnú, caithfidh tú focal faire nua a roghnú anseo:",
'resetpass_text'            => '<!-- Cur téacs anseo -->',
'resetpass_header'          => 'Athshocraigh pasfhocail chuntais',
'oldpassword'               => 'Focal faire reatha:',
'newpassword'               => 'Focal faire nua:',
'retypenew'                 => 'Athiontráil an focal nua faire:',
'resetpass_submit'          => 'Roghnaigh focal faire agus logáil isteach',
'resetpass_bad_temporary'   => "Níl an focal faire sealadach bailí.
B'fhéidir gur athraigh tú d'fhocal faire roimhe seo, nó gur iarr tú ar ceann nua.",
'resetpass_forbidden'       => 'Ní féidir focail faire a athrú',
'resetpass-no-info'         => 'Caithfidh tú bheith logáilte istigh chun teacht ar an leathanach seo go díreach.',
'resetpass-submit-loggedin' => "Athraigh d'fhocal faire",
'resetpass-temp-password'   => 'Focal faire sealadach:',

# Edit page toolbar
'bold_sample'     => 'Cló trom',
'bold_tip'        => 'Cló trom',
'italic_sample'   => 'Cló iodálach',
'italic_tip'      => 'Cló iodálach',
'link_sample'     => 'Teideal an naisc',
'link_tip'        => 'Nasc inmheánach',
'extlink_sample'  => 'http://www.example.com ainm naisc',
'extlink_tip'     => 'Nasc seachtrach (cuimhnigh an réimír http://)',
'headline_sample' => 'Cló ceannlíne',
'headline_tip'    => 'Ceannlíne Leibhéil 2',
'math_sample'     => 'Cuir foirmle isteach anseo',
'math_tip'        => 'Foirmle mhatamataice (LaTeX)',
'nowiki_sample'   => 'Cuir téacs neamhfhormáidithe anseo',
'nowiki_tip'      => 'Cuir vicífhormáidiú ar ceal',
'image_sample'    => 'Sámpla.jpg',
'image_tip'       => 'Íomhá leabaithe',
'media_sample'    => 'Sámpla.mp3',
'media_tip'       => 'Nasc do chomhad meáin',
'sig_tip'         => 'Do shíniú le stampa ama',
'hr_tip'          => 'Líne cothrománach (inúsáidte go coigilteach)',

# Edit pages
'summary'                => 'Achoimriú:',
'subject'                => 'Ábhar/ceannlíne:',
'minoredit'              => 'Mionathrú é seo',
'watchthis'              => 'Déan faire ar an lch seo',
'savearticle'            => 'Sábháil an lch',
'preview'                => 'Réamhamharc',
'showpreview'            => 'Taispeáin réamhamharc',
'showlivepreview'        => 'Réamhamharc beo',
'showdiff'               => 'Taispeáin athruithe',
'anoneditwarning'        => "'''Rabhadh:''' Níl tú logáilte isteach. Cuirfear do sheoladh IP i stair eagarthóireachta an leathanaigh seo.",
'missingsummary'         => "'''Cuimhneachán:''' Níor thug tú achoimriú don athrú. Má chliceáileann tú Sábháil arís, sábhálfar an t-athrú gan é a hachoimriú.",
'missingcommenttext'     => 'Cuir nóta tráchta isteach faoi seo, le do thoil.',
'summary-preview'        => 'Réamhamharc an achoimriú:',
'blockedtitle'           => 'Tá an úsáideoir seo faoi chosc',
'blockedtext'            => "<big>'''Chuir \$1 cosc ar d’ainm úsáideora nó ar do sheoladh IP.'''</big>

Is í seo an chúis a thugadh:<br />''\$2''.<p>Is féidir leat teagmháil a dhéanamh le \$1 nó le duine eile de na [[{{MediaWiki:Grouppage-sysop}}|riarthóirí]] chun an cosc a phléigh.

* Tús an chosc: \$8
* Dul as feidhm: \$6
* Sprioc an chosc: \$7
<br />
Tabhair faoi deara nach féidir leat an gné \"cuir ríomhphost chuig an úsáideoir seo\" a úsáid mura bhfuil seoladh ríomhphoist bailí cláraithe i do [[Special:Preferences|shocruithe úsáideora]]. 

Is é \$3 do sheoladh IP agus #\$5 do ID coisc. Déan tagairt don seoladh seo le gach ceist a chuirfeá.

==Nóta do úsáideoirí AOL==
De bhrí ghníomhartha leanúnacha creachadóireachta a dhéanann aon úsáideoir AOL áirithe, is minic a coisceann {{SITENAME}} ar friothálaithe AOL. Faraor, áfach, is féidir le 
go leor úsáídeoirí AOL an friothálaí céanna a úsáid, agus mar sin is minic a coiscaítear úsáideoirí AOL neamhchiontacha. Gabh ár leithscéal d'aon trioblóid. 

Dá dtarlódh an scéal seo duit, cuir ríomhphost chuig riarthóir le seoladh ríomhphoist AOL. Bheith cinnte tagairt a dhéanamh leis an seoladh IP seo thuas.",
'blockednoreason'        => 'níl chúis a thugadh',
'blockedoriginalsource'  => "Tá an foinse '''$1''' le feiceáil a leanas:",
'whitelistedittitle'     => 'Logáil isteach chun athrú a dhéanamh',
'whitelistedittext'      => 'Ní mór duit $1 chun ailt a athrú.',
'loginreqtitle'          => 'Tá logáil isteach de dhíth ort',
'loginreqlink'           => 'logáil isteach',
'loginreqpagetext'       => 'Caithfidh tú $1 chun leathanaigh a amharc.',
'accmailtitle'           => 'Seoladh an focal faire.',
'accmailtext'            => "Seoladh focal faire don úsáideoir '$1' go dtí '$2'.",
'newarticle'             => '(Nua)',
'newarticletext'         => "Lean tú nasc chuig leathanach nach bhfuil ann fós.
Chun an leathanach a chruthú, tosaigh ag clóscríobh sa bhosca thíos
(féach ar an [[{{MediaWiki:Helppage}}|leathanach cabhrach]] chun a thuilleadh eolais a fháil).
Má tháinig tú anseo as dearmad, brúigh ar cnaipe '''ar ais''' ar do bhrabhsálaí.",
'anontalkpagetext'       => "---- ''Leathanach plé é seo a bhaineann le húsáideoir gan ainm nár chruthaigh cuntas fós, nó nach bhfuil ag úsáid an cuntas aige. Dá bhrí sin, caithfimid an seoladh IP a úsáid chun é/í a hionannú. Is féidir le níos mó ná úsáideoir amháin an seoladh IP céanna a úsáid. Má tá tú i d'úsáideoir gan ainm agus má tá sé do thuairim go rinneadh léiriuithe neamhfheidhmeacha fút, [[Special:UserLogin|cruthaigh cuntas]] nó [[Special:UserLogin|logáil isteach]] chun mearbhall le húsáideoirí eile gan ainmneacha a héalú amach anseo.''",
'noarticletext'          => 'Níl aon téacs ar an leathanach seo faoi láthair.  Is féidir [[Special:Search/{{PAGENAME}}|cuardach a dhéanamh le haghaidh an teidil seo]] i leathanaigh eile nó [{{fullurl:{{FULLPAGENAME}}|action=edit}} an leathanach seo a athrú].',
'clearyourcache'         => "'''Tugtar faoi deara:''' Tar éis duit an t-inneachar a shábháil, caithfear gabháil thar thaisce an bhrabhsálaí chun na hathruithe a fheiceáil.
'''Mozilla/Safari/Konqueror:''' cliceáil ar ''Athlódáil'', agus ''Iomlaoid'' á bhrú agat (nó brúigh ''Ctrl-Iomlaoid-R''), '''IE:''' brúigh ''Ctrl-F5'', '''Opera:''' brúigh ''F5''.",
'usercssjsyoucanpreview' => "<strong>Leid:</strong> Sula sábhálaím tú, úsáid an cnaipe
'Réamhamharc' chun do CSS/JS nua a tástáil.",
'usercsspreview'         => "'''Cuimhnigh nach bhfuil seo ach réamhamharc do CSS úsáideora -
níor sábháladh é go fóill!'''",
'userjspreview'          => "'''Cuimhnigh nach bhfuil seo ach réamhamharc do JavaScript úsáideora
- níor sábháladh é go fóill!'''",
'userinvalidcssjstitle'  => "'''AIRE:''' Níl craiceann ar bith darbh ainm \"\$1\".
Cuimhnigh go úsáideann leathanaigh saincheaptha .css agus .js teideal i gcás íochtar, m.sh. úsaidtear {{ns:user}}:Foo/monobook.css in ann {{ns:user}}:Foo/Monobook.css.",
'updated'                => '(Leasaithe)',
'note'                   => '<strong>Tabhair faoi deara:</strong>',
'previewnote'            => '<strong>Cuimhnigh nach bhfuil ach réamhamharc sa leathanach seo, agus nach sábháladh fós é!</strong>',
'previewconflict'        => 'San réamhamharc seo, feachann tú an téacs dé réir an eagarbhosca
thuas mar a taispeáinfear é má sábháilfear é.',
'editing'                => 'Ag athrú $1',
'editingsection'         => 'Ag athrú $1 (mir)',
'editingcomment'         => 'Ag athrú $1 (tuairisc)',
'editconflict'           => 'Coimhlint athraithe: $1',
'explainconflict'        => 'D\'athraigh duine eile an leathanach seo ó shin a thosaigh tú ag athrú é.
Sa bhosca seo thuas feiceann tú téacs an leathanaigh mar atá sé faoi láthair.
Tá do chuid athruithe sa bhosca thíos.
Caithfidh tú do chuid athruithe a chumasadh leis an leagan láithreach.
Nuair a brúann tú ar an cnaipe "Sábháil an leathanach", ní shábhálfar aon rud <b>ach
amháin</b> an téacs sa bhosca thuas.',
'yourtext'               => 'Do chuid téacs',
'storedversion'          => 'Eagrán sábháilte',
'editingold'             => '<strong>AIRE: Tá tú ag athrú eagrán an leathanaigh atá as dáta.
Dá shábhálfá é, caillfear aon athrú a rinneadh ó shin an eagrán seo.</strong>',
'yourdiff'               => 'Difríochtaí',
'copyrightwarning'       => 'Tabhair faoi deara go dtuigtear go bhfuil gach dréacht do {{SITENAME}} eisithe faoi $2 (féach ar $1 le haghaidh tuilleadh eolais). 
Murar mian leat go gcuirfí do chuid scríbhinne in eagar go héadrócaireach agus go n-athdálfaí gan teorainn í, ná cuir isteach anseo í.<br /> 
Ina theannta sin, geallann tú gur scríobh tú féin an dréacht seo, nó gur chóipeáil tú é ó fhoinse san fhearann poiblí nó acmhainn eile saor ó chóipcheart (féach ar $1 le haghaidh tuilleadh eolais). 
<strong>NÁ CUIR ISTEACH OBAIR LE CÓIPCHEART GAN CHEAD!</strong>',
'copyrightwarning2'      => 'Tabhair faoi deara gur féidir le heagarthóirí eile gach dréacht do {{SITENAME}} a chur in eagar, a athrú agus a scriosadh. 
Murar mian leat go gcuirfí do chuid scríbhinne in eagar go héadrócaireach, ná cuir isteach anseo í.<br /> 
Ina theannta sin, geallann tú gur scríobh tú féin an dréacht seo, nó gur chóipeáil tú é ó fhoinse san fhearann poiblí nó acmhainn eile saor ó chóipcheart (féach ar $1 le haghaidh tuilleadh eolais). 
<strong>NÁ CUIR ISTEACH OBAIR LE CÓIPCHEART GAN CHEAD!</strong>',
'longpagewarning'        => '<strong>AIRE: Tá an leathanach seo $1 cilibheart i bhfad;
ní féidir le roinnt brabhsálaithe leathanaigh a athrú má tá siad breis agus 32 KiB, nó níos fada ná sin.
Más féidir, giotaigh an leathanach i gcodanna níos bige.</strong>',
'longpageerror'          => '<strong>EARRÁID: Tá an téacs a chuir isteach $1 cilibheart ar fad, sin níos faide ná $2 cilibheart, an uasmhéid.
Ní féidir é a shábháil.</strong>',
'readonlywarning'        => "<strong>AIRE: Glasáladh an bunachar sonraí, agus mar sin ní féidir leat do chuid athruithe a shábháil díreach anois.
B'fhéidir gur mhaith leat an téacs a ghearr is ghreamú i gcomhad téacs agus é a úsáid níos déanaí.</strong>

An fáth a thabhairt an riarthóir a ghlasadh nach: $1",
'protectedpagewarning'   => '<strong>AIRE: Glasáladh an leathanach seo, agus ní féidir le duine ar bith é a athrú ach amhaín na húsáideoirí le pribhléidí oibreora córais. Bí cinnte go leanann tú na treoirlínte do leathanaigh glasáilte.</strong>',
'templatesused'          => 'Teimpléid atá á úsáid ar an lch seo:',
'templatesusedpreview'   => 'Teimpléid in úsáid sa réamhamharc alt seo:',
'templatesusedsection'   => 'Teimpléid in úsáid san alt seo:',
'template-protected'     => '(ghlasáil)',
'template-semiprotected' => '(leath-ghlasáil)',
'edittools'              => '<!-- Taispeánfar an téacs seo faoi foirmeacha eagarthóireachta agus uaslódála. -->',
'nocreatetext'           => 'Tá srianadh ar {{SITENAME}} faoin leathanaigh nua a cruthaidh.
Is féidir leat dul ar ais chun leathanach láithreach a athrú, nó [[Special:UserLogin|log isteach nó cruthaigh cuntas nua]].',
'nocreate-loggedin'      => 'Níl cead agat leathanaigh nua a chruthú.',
'permissionserrors'      => 'Cead rochtana earráidí',
'recreate-deleted-warn'  => "'''Rabhadh: Tá tú ag athchruthú leathanach ina bhfuil scriostha roimhe.'''

Bhreithneoidh tú cibé go bhfuil sé oiriúnach chun lean an leathanach seo a cur in eagar.<br />
Tá an log scriosta ar fáil anseo mar áis:",
'deletelog-fulllog'      => 'Feach ar log lán',

# Account creation failure
'cantcreateaccounttitle' => 'Ní féidir cuntas a chruthú',

# History pages
'viewpagelogs'           => 'Féach ar logaí faoin leathanach seo',
'nohistory'              => 'Níl aon stáir athraithe ag an leathanach seo.',
'currentrev'             => 'Leagan reatha',
'currentrev-asof'        => 'Leagan reatha ó $1',
'revisionasof'           => 'Leagan ó $1',
'revision-info'          => 'Leagan ó $1 le $2', # Additionally available: $3: revision id
'previousrevision'       => '← An leasú roimhe seo',
'nextrevision'           => 'An chéad leasú eile →',
'currentrevisionlink'    => 'Leagan reatha',
'cur'                    => 'rth',
'next'                   => 'i ndiadh',
'last'                   => 'rmh',
'page_first'             => 'céad',
'page_last'              => 'deireanach',
'histlegend'             => 'Chun difríochtaí a roghnú, marcáil na cnaipíní de na heagráin atá tú ag iarraidh comparáid a dhéanamh astu, agus brúigh Iontráil nó an cnaipe ag bun an leathanaigh.<br />
Treoir: (rth) = difríocht ón leagan reatha, (rmh) = difríocht ón leagan roimhe, <b>m</b> = mionathrú.',
'history-fieldset-title' => 'Brabhsáil an stáir',
'deletedrev'             => '[scriosta]',
'histfirst'              => 'An ceann is luaithe',
'histlast'               => 'An ceann is déanaí',
'historysize'            => '({{PLURAL:$1|Beart amháin|$1 bearta}})',
'historyempty'           => '(folamh)',

# Revision feed
'history-feed-title'          => 'Stáir leasú',
'history-feed-description'    => 'Stair leasú an leathanach seo ar an vicí',
'history-feed-item-nocomment' => '$1 ag $2', # user at time

# Revision deletion
'rev-deleted-user'      => '(ainm úsáideora dealaithe)',
'rev-delundel'          => 'taispeáin/folaigh',
'revisiondelete'        => 'Scrios/díscrios leagain',
'revdelete-selected'    => "'''{{PLURAL:$2|Leagan roghnaithe|Leagain roghnaithe}} [[:$1]]:'''",
'logdelete-selected'    => "'''{{PLURAL:$1|Teagmhas log roghnaithe|Teagmhais log roghnaithe}}:'''",
'revdelete-hide-text'   => 'Folaigh leagan téacs',
'pagehist'              => 'Stair leathanach',
'deletedhist'           => 'Stair scriosta',
'revdelete-uname'       => 'ainm úsáideora',
'revdelete-log-message' => '$1 le $2 {{PLURAL:$2|leagan|leagain}}',

# History merging
'mergehistory-from' => 'Leathanach fhoinse:',

# Diffs
'history-title'           => 'Stair leasú "$1"',
'difference'              => '(Difríochtaí idir leaganacha)',
'lineno'                  => 'Líne $1:',
'compareselectedversions' => 'Cuir na leagain roghnaithe i gcomparáid',
'wikicodecomparison'      => 'Comparáid Vicítéacs',
'editundo'                => 'cealaigh',
'diff-multi'              => '({{PLURAL:$1|Leasú idirmheánach amháin|$1 leasú idirmheánach}} nach thaispeántar.)',
'diff-movedto'            => 'a athrú go $1',
'diff-changedfrom'        => 'a athrú as $1',
'diff-src'                => 'foinse',
'diff-width'              => 'leithead',
'diff-height'             => 'airde',
'diff-p'                  => "'''alt'''",
'diff-a'                  => "'''nasc'''",
'diff-big'                => "'''mór'''",
'diff-del'                => "'''scriosta'''",

# Search results
'searchresults'                  => 'Torthaí an chuardaigh',
'searchresulttext'               => 'Féach ar [[{{MediaWiki:Helppage}}|{{int:help}}]] chun a thuilleadh eolais a fháil maidir le cuardaigh {{GRAMMAR:genitive|{{SITENAME}}}}.',
'searchsubtitle'                 => 'Don iarratas "[[:$1]]"',
'searchsubtitleinvalid'          => 'Don iarratas "$1"',
'noexactmatch'                   => "'''Níl aon leathanach ann leis an teideal \"\$1\".''' Is féidir leat é a [[:\$1|cruthú]].",
'titlematches'                   => 'Tá macasamhla teidil alt ann',
'notitlematches'                 => 'Níl macasamhla teidil alt ann',
'textmatches'                    => 'Tá macasamhla téacs alt ann',
'notextmatches'                  => 'Níl macasamhla téacs alt ann',
'prevn'                          => 'na $1 cinn roimhe seo',
'nextn'                          => 'an $1 i ndiadh',
'viewprevnext'                   => 'Taispeáin ($1) ($2) ($3).',
'searchmenu-legend'              => 'Sainroghanna cuardaithe',
'searchmenu-new'                 => "'''Cruthaigh an leathanach \"[[:\$1]]\" ar an vicí seo!'''",
'searchhelp-url'                 => 'Help:Clár_ábhair',
'searchprofile-project'          => 'Leathanaigh thionscadail',
'searchprofile-images'           => 'Comhaid',
'searchprofile-everything'       => 'Gach rud',
'searchprofile-articles-tooltip' => 'Cuardaigh i $1',
'searchprofile-project-tooltip'  => 'Cuardaigh i $1',
'searchprofile-images-tooltip'   => 'Cuardaigh le comhaid',
'search-result-size'             => '$1 ({{PLURAL:$2|focal amháin|$2 focail}})',
'search-section'                 => '(gearradh $1)',
'search-interwiki-default'       => '$1 torthaí:',
'search-interwiki-more'          => '(níos mó)',
'search-relatedarticle'          => 'Gaolmhar',
'searchrelated'                  => 'gaolmhara',
'searchall'                      => 'an t-iomlán',
'showingresults'                 => "Ag taispeáint thíos {{PLURAL:$1|'''toradh amháin'''|'''$1''' torthaí}}, ag tosú le #'''$2'''.",
'showingresultsnum'              => "Ag taispeáint thíos {{PLURAL:$3|'''toradh amháin'''|'''$3''' torthaí}}, ag tosú le #'''$2'''.",
'nonefound'                      => '<strong>Tabhair faoi deara</strong>: go minic, ní éiríonn cuardaigh nuair a cuardaítear focail an-coiteanta, m.sh., "ag" is "an",
a nach bhfuil innéacsaítear, nó nuair a ceisteann tú níos mó ná téarma amháin (ní
taispeáintear sna toraidh ach na leathanaigh ina bhfuil go leoir na téarmaí cuardaigh).',
'search-nonefound'               => 'Ní bhfuarthas tortha ar bith.',
'powersearch'                    => 'Cuardaigh',
'powersearch-field'              => 'Cuardaigh le',
'searchdisabled'                 => "Tá brón orainn! Mhíchumasaíodh an cuardach téacs iomlán go sealadach chun luas an tsuímh a chosaint. Idir an dá linn, is féidir leat an cuardach Google anseo thíos a úsáid - b'fhéidir go bhfuil sé as dáta.",

# Preferences page
'preferences'               => 'Sainroghanna',
'mypreferences'             => 'Mo shainroghanna',
'prefsnologin'              => 'Níl tú logáilte isteach',
'prefsnologintext'          => 'Ní mór duit <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} logáil isteach]</span> chun do chuid sainroghanna phearsanta a shocrú.',
'prefsreset'                => "D'athraíodh do chuid sainroghanna ar ais chuig an leagan bunúsach ón stóras.",
'qbsettings'                => 'Sainroghanna an bosca uirlisí',
'qbsettings-none'           => 'Faic',
'qbsettings-fixedleft'      => 'Greamaithe ar chlé',
'qbsettings-fixedright'     => 'Greamaithe ar dheis',
'qbsettings-floatingleft'   => 'Ag faoileáil ar chlé',
'qbsettings-floatingright'  => 'Ag faoileáil ar dheis',
'changepassword'            => "Athraigh d'fhocal faire",
'skin'                      => 'Craiceann',
'skin-preview'              => 'Réamhamharc',
'math'                      => 'Matamaitice',
'dateformat'                => 'Formáid dáta',
'datedefault'               => 'Is cuma liom',
'datetime'                  => 'Dáta agus am',
'math_failure'              => 'Theip ó anailís na foirmle',
'math_unknown_error'        => 'earráid anaithnid',
'math_unknown_function'     => 'foirmle anaithnid',
'math_lexing_error'         => 'Theip ó anailís an fhoclóra',
'math_syntax_error'         => 'earráid comhréire',
'math_image_error'          => 'Theip ó aistriú an PNG; tástáil má tá na ríomh-oidis latex, dvips, gs, agus convert i suite go maith.',
'math_bad_tmpdir'           => 'Ní féidir scríobh chuig an fillteán mata sealadach, nó é a chruthú',
'math_bad_output'           => 'Ní féidir scríobh chuig an fillteán mata aschomhaid, nó é a chruthú',
'math_notexvc'              => 'Níl an ríomhchlár texvc ann; féach ar mata/EOLAIS chun é a sainathrú.',
'prefs-personal'            => 'Sonraí úsáideora',
'prefs-rc'                  => 'Athruithe is déanaí',
'prefs-watchlist'           => 'Liosta faire',
'prefs-watchlist-days'      => 'Líon na laethanta le taispeáint sa liosta faire:',
'prefs-watchlist-edits'     => 'Líon na n-athruithe le taispeáint sa liosta leathnaithe faire:',
'prefs-watchlist-edits-max' => '(uasmhéid: 1000)',
'prefs-misc'                => 'Éagsúla',
'prefs-resetpass'           => 'Athraigh focal faire',
'saveprefs'                 => 'Sábháil',
'resetprefs'                => 'Athshocraigh sainroghanna',
'textboxsize'               => 'Eagarthóireacht',
'rows'                      => 'Sraitheanna',
'columns'                   => 'Colúin',
'searchresultshead'         => 'Cuardaigh',
'resultsperpage'            => 'Cuairt le taispeáint ar gach leathanach',
'contextlines'              => 'Línte le taispeáint do gach cuairt',
'contextchars'              => 'Litreacha chomhthéacs ar gach líne',
'recentchangescount'        => 'Méid teideal sna hathruithe is déanaí',
'savedprefs'                => 'Sábháladh do chuid sainroghanna.',
'timezonelegend'            => 'Crios ama',
'timezonetext'              => 'Iontráil an méid uaireanta a difríonn do am áitiúil
den am an freastalaí (UTC).',
'localtime'                 => 'An t-am áitiúil',
'timezoneoffset'            => 'Difear',
'servertime'                => 'Am an freastalaí anois',
'guesstimezone'             => 'Líon ón líonléitheoir',
'allowemail'                => "Tabhair cead d'úsáideoirí eile ríomhphost a sheoladh chugat.",
'prefs-namespaces'          => 'Ainmspáis',
'defaultns'                 => 'Cuardaigh sna ranna seo a los éagmaise:',
'default'                   => 'réamhshocrú',
'files'                     => 'Comhaid',

# User rights
'userrights'               => 'Bainistíocht cearta úsáideora', # Not used as normal message but as header for the special page itself
'userrights-user-editname' => 'Iontráil ainm úsáideora:',
'editusergroup'            => 'Cuir Grúpái Úsáideoirí In Eagar',
'editinguser'              => "Ag athrú ceart don úsáideoir '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'Cuir grúpaí na n-úsáideoirí in eagar',
'saveusergroups'           => 'Sabháil cnuasach úsáideora',
'userrights-groupsmember'  => 'Ball de:',
'userrights-notallowed'    => 'Níl cead ag do chuntas ceartanna úsáideora a athrú.',

# Groups
'group'            => 'Grúpa:',
'group-user'       => 'Úsáideoirí',
'group-bot'        => 'Róbónna',
'group-sysop'      => 'Riarthóirí',
'group-bureaucrat' => 'Maorlathaigh',
'group-all'        => '(iad uile)',

'group-user-member'       => 'Úsáideoir',
'group-bot-member'        => 'Róbó',
'group-sysop-member'      => 'Riarthóir',
'group-bureaucrat-member' => 'Maorlathach',

'grouppage-bot'        => '{{ns:project}}:Róbónna',
'grouppage-sysop'      => '{{ns:project}}:Riarthóirí',
'grouppage-bureaucrat' => '{{ns:project}}:Maorlathaigh',

# Rights
'right-upload'     => 'Uaslódáil comhaid',
'right-delete'     => 'Scrios leathanaigh',
'right-undelete'   => 'Díscrios leathanach',
'right-userrights' => 'Cur gach cearta usáideoira in eagar',

# User rights log
'rightslog' => 'Log cearta úsáideoira',

# Associated actions - in the sentence "You do not have permission to X"
'action-createpage'    => 'cruthaigh leathanaigh',
'action-createaccount' => 'an cuntas seo a chruthú',
'action-minoredit'     => 'an athrú seo a mharcáil mar mionathrú',
'action-upload'        => 'uaslódáil an comhad',

# Recent changes
'nchanges'                          => '{{PLURAL:$1|Athrú amháin|$1 athruithe}}',
'recentchanges'                     => 'Athruithe is déanaí',
'recentchanges-legend'              => 'Roghanna do na hathruithe is déanaí',
'recentchangestext'                 => 'Déan faire ar na hathruithe is déanaí sa vicí ar an leathanach seo.',
'recentchanges-feed-description'    => 'Rianaigh na n-athruite vicí is déanaí sa fotha seo.',
'rcnote'                            => "Is {{PLURAL:$1|é seo a leanas <strong>an t-athrú amháin</strong>|iad seo a leanas na <strong>$1</strong> athruithe is déanaí}} {{PLURAL:$2|ar feadh an lae dheireanaigh|ar feadh na '''$2''' lá deireanacha}}, as $5, $4.",
'rcnotefrom'                        => 'Is iad seo a leanas na hathruithe ó <b>$2</b> (go dti <b>$1</b> taispeánaithe).',
'rclistfrom'                        => 'Taispeáin athruithe nua ó $1 anuas.',
'rcshowhideminor'                   => '$1 mionathruithe',
'rcshowhidebots'                    => '$1 róbónna',
'rcshowhideliu'                     => '$1 úsáideoirí atá logáilte isteach',
'rcshowhideanons'                   => '$1 úsáideoirí gan ainm',
'rcshowhidepatr'                    => '$1 athruithe faoi phatról',
'rcshowhidemine'                    => '$1 mo chuid athruithe',
'rclinks'                           => 'Taispeáin an $1 athrú is déanaí sa $2 lá seo caite<br />$3',
'diff'                              => 'difr',
'hist'                              => 'stair',
'hide'                              => 'Folaigh',
'show'                              => 'Taispeáin',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'r',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|úsáideoir amháin|$1 úsáideoirí}} ag faire]',
'rc_categories_any'                 => 'Aon chatagóir',
'newsectionsummary'                 => '/* $1 */ mír nua',

# Recent changes linked
'recentchangeslinked'          => 'Athruithe gaolmhara',
'recentchangeslinked-title'    => 'Athruithe gaolmhara le "$1"',
'recentchangeslinked-noresult' => 'Níl aon athraithe ar na leathanaigh naiscthe le linn an tréimhse tugtha.',
'recentchangeslinked-summary'  => "Seo liosta na n-athruithe atá deanta is déanaí le leathanaigh atá naiscthe as leathanach sonraithe (nó baill an chatagóir sonraithe).
Tá na leathanaigh ar do [[Special:Watchlist|liosta faire]] i '''gcló trom'''.",

# Upload
'upload'               => 'Uaslódaigh comhad',
'uploadbtn'            => 'Uaslódaigh comhad',
'reupload'             => 'Athuaslódáil',
'reuploaddesc'         => 'Dul ar ais chuig an fhoirm uaslódála.',
'uploadnologin'        => 'Nil tú logáilte isteach',
'uploadnologintext'    => 'Ní mór duit [[Special:UserLogin|logáil isteach]] chun comhaid a huaslódáil.',
'uploaderror'          => 'Earráid uaslódála',
'uploadtext'           => "Bain úsáid as an bhfoirm thíos chun comhaid a uaslódáil.
Chun comhaid atá ann cheana a fheiceáil nó a chuardach téigh chuig an [[Special:FileList|liosta comhad uaslódáilte]]. Gheobhaidh tú liosta de chomhaid uaslódáilte sa [[Special:Log/upload|loga uaslódála]] agus liosta de chomhaid scriosta sa [[Special:Log/delete|loga scriosta]] freisin.

Chun comhad a úsáid ar leathanach, cuir isteach nasc mar seo:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:comhad.jpg]]</nowiki></tt>''' chun leagan iomlán an chomhad a úsáid
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:comhad.png|200px|thumb|left|téacs eile]]</nowiki></tt>''' chun comhad le 200 picteillín ar leithead i mbosca san imeall clé le 'téacs eile' mar tuairisc
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:comhad.ogg]]</nowiki></tt>''' más comhad fuaime atá i gceist",
'upload-permitted'     => 'Cineálacha comhaid ceadaithe: $1.',
'uploadlog'            => 'Stair uaslódála',
'uploadlogpage'        => 'Stair_uaslódála',
'uploadlogpagetext'    => 'Is liosta é seo a leanas de na uaslódáil comhad is deanaí.
Is am an freastalaí iad na hamanna atá anseo thíos.',
'filename'             => 'Comhadainm',
'filedesc'             => 'Achoimriú',
'fileuploadsummary'    => 'Achoimre:',
'filestatus'           => 'Stádas cóipchirt:',
'filesource'           => 'Foinse:',
'uploadedfiles'        => 'Comhaid uaslódáilte',
'ignorewarning'        => 'Déan neamhaird den rabhadh agus sábháil an comhad ar an chor',
'ignorewarnings'       => 'Déan neamhaird aon rabhaidh',
'illegalfilename'      => 'Tá litreacha san comhadainm  "$1" nach ceadaítear in ainm leathanaigh. Athainmnigh
an comhad agus déan athiarracht, más é do thoil é.',
'badfilename'          => 'D\'athraíodh an ainm íomhá bheith "$1".',
'filetype-badmime'     => 'Ní ceadaítear comhaid den saghas MIME "$1" a uaslódáil.',
'emptyfile'            => "De réir a chuma, ní aon rud san chomhad a d'uaslódáil tú ach comhad folamh. Is dócha gur
míchruinneas é seo san ainm chomhaid. Seiceáil más é an comhad seo atá le huaslódáil agat.",
'fileexists-forbidden' => 'Tá comhad eile leis an ainm seo ann.
Má theastáilann uait do chomhad a uaslódáil fós, téigh ar ais agus úsáid ainm nua, le do thoil.
[[File:$1|thumb|center|$1]]',
'successfulupload'     => "D'éirigh leis an uaslódáil",
'uploadwarning'        => 'Rabhadh suaslódála',
'savefile'             => 'Sábháil comhad',
'uploadedimage'        => 'uaslódáladh "[[$1]]"',
'uploaddisabled'       => 'Tá brón orainn, ní féidir aon rud a uaslódáil faoi láthair.',
'uploaddisabledtext'   => 'Tá cosc ar uaslódáileanna chomhad.',
'uploadcorrupt'        => 'Tá an comhad truaillithe nó tá iarmhír comhadainm neamhbhailí aige. Scrúdaigh an comhad agus
uaslódáil é arís, le do thoil.',
'uploadvirus'          => 'Tá víreas ann sa comhad seo! Eolas: $1',
'sourcefilename'       => 'Comhadainm foinse:',
'destfilename'         => 'Comhadainm sprice:',
'upload-maxfilesize'   => 'Méad comhad is mó: $1',
'watchthisupload'      => 'Déan faire ar an leathanach seo',

'upload-proto-error' => 'Prótacal mícheart',
'upload-file-error'  => 'Earráid inmheánach',

'license'            => 'Ceadúnas:',
'nolicense'          => 'Níl aon cheann roghnaithe',
'upload_source_url'  => ' (URL bailí is féidir a rochtain go poiblí)',
'upload_source_file' => ' (comhad ar do riomhaire)',

# Special:ListFiles
'listfiles_search_for'  => 'Cuardaigh le íomhá ab ainm:',
'imgfile'               => 'comhad',
'listfiles'             => 'Liosta íomhánna',
'listfiles_date'        => 'Dáta',
'listfiles_name'        => 'Ainm',
'listfiles_user'        => 'Úsáideoir',
'listfiles_size'        => 'Méid',
'listfiles_description' => 'Tuairisc',

# File description page
'filehist'                       => 'Stair comhad',
'filehist-help'                  => 'Clic ar dáta/am chun an comhad a radharc mar a bhí sé ar an am.',
'filehist-deleteone'             => 'scrios',
'filehist-current'               => 'reatha',
'filehist-datetime'              => 'Dáta/Am',
'filehist-thumbtext'             => 'Mionsamhail do leagan ó $1',
'filehist-user'                  => 'Úsáideoir',
'filehist-dimensions'            => 'Toisí',
'filehist-filesize'              => 'Méid an comhad',
'filehist-comment'               => 'Nóta tráchta',
'imagelinks'                     => 'Naisc íomhá',
'linkstoimage'                   => 'Tá nasc chuig an gcomhad seo ar {{PLURAL:$1|na leathanaigh|$1 an leathanach}} seo a leanas:',
'nolinkstoimage'                 => 'Níl nasc ó aon leathanach eile don íomhá seo.',
'sharedupload'                   => 'Is uaslodáil roinnte atá ann sa comhad seo, agus is féidir le tionscadail eile é a úsáid.',
'shareduploadwiki'               => 'Féach ar an [leathanach cur síos don comhad $1] le tuilleadh eolais.',
'shareduploadwiki-linktext'      => 'leathanach tuairisc comhad',
'shareduploadduplicate'          => 'Tá an comhad seo dúblach $1 as comhstór.',
'shareduploadduplicate-linktext' => 'comhad eile',
'shareduploadconflict'           => 'Tá ainm dúblach ar an comhad seo le $1 as an comhstór.',
'shareduploadconflict-linktext'  => 'comhad eile',
'noimage'                        => 'Níl aon chomhad ann leis an ainm seo, ba féidir leat $1',
'noimage-linktext'               => 'uaslódaigh ceann',
'uploadnewversion-linktext'      => 'Uaslódáil leagan nua den comhad seo',

# File reversion
'filerevert'                => 'Fill $1 ar ais',
'filerevert-legend'         => 'Fill comhad ar ais',
'filerevert-comment'        => 'Nóta tráchta:',
'filerevert-defaultcomment' => 'Filleadh ar ais go leagan ó $2, $1',
'filerevert-submit'         => 'Athúsáid',
'filerevert-success'        => "Filleadh '''[[Media:$1|$1]]''' go leagan [$4 ó $3, $2].",

# File deletion
'filedelete'                  => 'Scrios $1',
'filedelete-legend'           => 'Scrios comhad',
'filedelete-submit'           => 'Scrios',
'filedelete-success'          => "'''$1''' a bheith scriosta.",
'filedelete-success-old'      => "An leagan '''[[Media:$1|$1]]''' as $3, $2 a bheith scriosta.",
'filedelete-reason-otherlist' => 'Fáth eile',

# MIME search
'mimesearch' => 'Cuardaigh MIME',
'download'   => 'íoslódáil',

# Unwatched pages
'unwatchedpages' => 'Leathanaigh gan faire',

# List redirects
'listredirects' => 'Liostaigh na athsheolaí',

# Unused templates
'unusedtemplates'    => 'Teimpléid gan úsáidtear',
'unusedtemplateswlh' => 'naisc eile',

# Random page
'randompage'         => 'Leathanach fánach',
'randompage-nopages' => 'Níl aon leathanaigh san ainmspás "$1".',

# Random redirect
'randomredirect' => 'Atreorú randamach',

# Statistics
'statistics'              => 'Staidrimh',
'statistics-header-users' => 'Staidreamh úsáideora',
'statistics-pages'        => 'Leathanaigh',

'disambiguations'     => 'Leathanaigh idirdhealaithe',
'disambiguationspage' => '{{ns:project}}:Naisc_go_leathanaigh_idirdhealaithe',

'doubleredirects'     => 'Athsheolaidh dúbailte',
'doubleredirectstext' => '<b>Tabhair faoi deara:</b> B\'fheidir go bhfuil toraidh bréagacha ar an liosta seo.
De ghnáth cíallaíonn sé sin go bhfuil téacs breise le naisc thíos sa chéad #REDIRECT no #ATHSHEOLADH.<br />
 Sa
gach sraith tá náisc chuig an chéad is an dara athsheoladh, chomh maith le chéad líne an dara téacs athsheolaidh. De
ghnáth tugann sé sin an sprioc-alt "fíor".',

'brokenredirects'        => 'Atreoruithe briste',
'brokenredirectstext'    => 'Is iad na athsheolaidh seo a leanas a nascaíonn go ailt nach bhfuil ann fós.',
'brokenredirects-edit'   => '(athraigh)',
'brokenredirects-delete' => '(scrios)',

'withoutinterwiki'        => 'Leathanaigh gan naisc idirvicí',
'withoutinterwiki-legend' => 'Réimír',
'withoutinterwiki-submit' => 'Taispeáin',

'fewestrevisions' => 'Leathanaigh leis na leasaithe is lú',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bheart amháin|bearta}}',
'ncategories'             => '$1 {{PLURAL:$1|chatagóir amháin|catagóirí}}',
'nlinks'                  => '{{PLURAL:$1|nasc amháin|$1 naisc}}',
'nmembers'                => '{{PLURAL:$1|ball amháin|$1 baill}}',
'nrevisions'              => '{{PLURAL:$1|Leagan amháin|$1 leagain}}',
'nviews'                  => '{{PLURAL:$1|radharc amháin|$1 radhairc}}',
'lonelypages'             => 'Leathanaigh aonair',
'uncategorizedpages'      => 'Leathanaigh gan catagóir',
'uncategorizedcategories' => 'Catagóirí gan catagórú',
'uncategorizedimages'     => 'Íomhánna gan catagóir',
'uncategorizedtemplates'  => 'Teimpléid gan catagóir',
'unusedcategories'        => 'Catagóirí nach úsáidtear',
'unusedimages'            => 'Íomhánna nach úsáidtear',
'popularpages'            => 'Leathanaigh coitianta',
'wantedcategories'        => 'Catagóirí agus iarraidh ag gabháil leis',
'wantedpages'             => 'Leathanaigh de dhíth',
'mostlinked'              => 'Na leathanaigh naiscthe is mó',
'mostlinkedcategories'    => 'Na chatagóirí naiscthe is mó',
'mostlinkedtemplates'     => 'Na teimpléid naiscthe is mó',
'mostcategories'          => 'Leathanaigh leis na chatagóir is mó',
'mostimages'              => 'Na comhaid naiscthe is mó',
'mostrevisions'           => 'Leathanaigh leis na leasaithe is mó',
'prefixindex'             => 'Innéacs réimír',
'shortpages'              => 'Leathanaigh gearra',
'longpages'               => 'Leathanaigh fada',
'deadendpages'            => 'Leathanaigh caocha',
'protectedpages'          => 'Leathanaigh cosanta',
'protectedtitles'         => 'Teideail cosanta',
'listusers'               => 'Liosta úsáideoirí',
'newpages'                => 'Leathanaigh nua',
'newpages-username'       => 'Ainm úsáideora:',
'ancientpages'            => 'Na leathanaigh is sine',
'move'                    => 'Athainmnigh',
'movethispage'            => 'Athainmnigh an leathanach seo',
'unusedimagestext'        => '<p>Tabhair faoi deara gur féidir le shuímh
eile naisc a dhéanamh leis an íomha le URL díreach,
agus mar sin bheadh siad ar an liosta seo fós cé go bhfuil siad
in úsáid faoi láthair.',
'unusedcategoriestext'    => 'Tá na leathanaigh catagóire seo a leanas ann, cé nach úsáidtear
iad in aon alt eile nó in aon chatagóir eile.',
'notargettitle'           => 'Níl aon cuspóir ann',
'notargettext'            => 'Níor thug tú leathanach nó úsáideoir sprice
chun an gníomh seo a dhéanamh ar.',
'pager-newer-n'           => '{{PLURAL:$1|1 níos nuaí|$1 níos nuaí}}',
'pager-older-n'           => '{{PLURAL:$1|1 níos sine|$1 níos sine}}',

# Book sources
'booksources'               => 'Leabharfhoinsí',
'booksources-search-legend' => 'Cuardaigh le foinsí leabhar',

# Special:Log
'specialloguserlabel'  => 'Úsáideoir:',
'speciallogtitlelabel' => 'Teideal:',
'log'                  => 'Loganna',
'all-logs-page'        => 'Gach logaí',
'alllogstext'          => 'Taispeántas comhcheangaltha de logaí as {{SITENAME}} a bhaineann le huaslódáil, scriosadh, glasáil, coisc,
agus oibreoirí córais. Is féidir leat an taispeántas a ghéarú - roghnaigh an saghas loga, an ainm úsáideora, nó an
leathanach atá i gceist agat.',

# Special:AllPages
'allpages'          => 'Gach leathanach',
'alphaindexline'    => '$1 go $2',
'nextpage'          => 'An leathanach a leanas ($1)',
'prevpage'          => 'Leathanach roimhe sin ($1)',
'allpagesfrom'      => 'Taispeáin leathanaigh, le tosú ag:',
'allarticles'       => 'Gach alt',
'allinnamespace'    => 'Gach leathanach (ainmspás $1)',
'allnotinnamespace' => 'Gach leathanach (lasmuigh den ainmspás $1)',
'allpagesprev'      => 'Siar',
'allpagesnext'      => 'Ar aghaidh',
'allpagessubmit'    => 'Gabh',
'allpagesprefix'    => 'Taispeáin leathanaigh leis an réimír:',
'allpages-bad-ns'   => 'Níl an ainmspás "$1" ar {{SITENAME}}',

# Special:Categories
'categories'         => 'Catagóirí',
'categoriespagetext' => 'Tá na catagóiri seo a leanas ann sa vicí.
Níl na [[Special:UnusedCategories|catagóiri gan úsáid]] ar fáil anseo.
Féach freisin ar [[Special:WantedCategories|catagóirí agus iarraidh ag gabháil leis]].',

# Special:DeletedContributions
'deletedcontributions'       => 'Dréachtaí úsáideora scriosta',
'deletedcontributions-title' => 'Dréachtaí úsáideora scriosta',

# Special:LinkSearch
'linksearch-ns'   => 'Ainmspás:',
'linksearch-ok'   => 'Cuardaigh',
'linksearch-line' => '$1 naiscthe as $2',

# Special:ListUsers
'listusers-submit' => 'Taispeáin',

# Special:Log/newusers
'newuserlog-create-entry'  => 'Úsáideoir nua',
'newuserlog-create2-entry' => 'cuntas cruthú le $1',

# Special:ListGroupRights
'listgrouprights-group'  => 'Ghrúpa',
'listgrouprights-rights' => 'Cearta',

# E-mail user
'mailnologin'     => 'Níl aon seoladh maith ann',
'mailnologintext' => 'Ní mór duit bheith  [[Special:UserLogin|logáilte isteach]]
agus bheith le seoladh ríomhphoist bhailí i do chuid [[Special:Preferences|sainroghanna]]
más mian leat ríomhphost a sheoladh chuig úsáideoirí eile.',
'emailuser'       => 'Cuir ríomhphost chuig an úsáideoir seo',
'emailpage'       => 'Seol ríomhphost',
'emailpagetext'   => 'Má d\'iontráil an úsáideoir seo seoladh ríomhphoist bhailí ina chuid sainroghanna úsáideora, cuirfidh an foirm anseo thíos teachtaireacht amháin do.
Beidh do seoladh ríomhphoist a d\'iontráil tú i [[Special:Preferences|do chuid sainroghanna úsáideora]] sa bhosca "Seoltóir" an riomhphoist, agus mar sin ba féidir léis an faighteoir ríomhphost eile a chur leatsa.',
'usermailererror' => 'Earráid leis an píosa ríomhphoist:',
'defemailsubject' => 'Ríomhphost {{GRAMMAR:genitive|{{SITENAME}}}}',
'noemailtitle'    => 'Níl aon seoladh ríomhphoist ann',
'noemailtext'     => 'Níor thug an úsáideoir seo seoladh ríomhphoist bhailí, nó shocraigh sé nach
mian leis ríomhphost a fháil ón úsáideoirí eile.',
'emailfrom'       => 'Seoltóir:',
'emailto'         => 'Chuig:',
'emailsubject'    => 'Ábhar:',
'emailmessage'    => 'Teachtaireacht:',
'emailsend'       => 'Seol',
'emailsent'       => 'Ríomhphost seolta',
'emailsenttext'   => 'Seoladh do theachtaireacht ríomhphoist go ráthúil.',

# Watchlist
'watchlist'            => 'Mo liosta faire',
'mywatchlist'          => 'Mo liosta faire',
'watchlistfor'         => "(le '''$1''')",
'nowatchlist'          => 'Níl aon rud ar do liosta faire.',
'watchlistanontext'    => "$1, le d'thoil, chun míreanna ar do liosta faire a fheiceáil ná a athrú.",
'watchnologin'         => 'Níl tú logáilte isteach',
'watchnologintext'     => 'Tá ort a bheith [[Special:UserLogin|logáilte isteach]] chun do liosta faire a athrú.',
'addedwatch'           => 'Curtha ar an liosta faire',
'addedwatchtext'       => "Cuireadh an leathanach \"<nowiki>\$1</nowiki>\" le do [[Special:Watchlist|liosta faire]].
Amach anseo liostálfar athruithe don leathanach seo agus dá leathanach plé ansin,
agus beidh '''cló trom''' ar a theideal san [[Special:RecentChanges|liosta de na hathruithe is déanaí]] sa chaoi go bhfeicfeá iad go héasca.",
'removedwatch'         => 'Bainte den liosta faire',
'removedwatchtext'     => 'Baineadh an leathanach "<nowiki>$1</nowiki>" as [[Special:Watchlist|do liosta faire]].',
'watch'                => 'Déan faire',
'watchthispage'        => 'Déan faire ar an leathanach seo',
'unwatch'              => 'Ná fair',
'unwatchthispage'      => 'Ná fair fós',
'notanarticle'         => 'Níl alt ann',
'notvisiblerev'        => 'Leagan a bheith scriosta',
'watchnochange'        => 'Níor athraíodh ceann ar bith de na leathanaigh atá ar do liosta faire,
taobh istigh den tréimhse atá roghnaithe agat.',
'watchlist-details'    => 'Tá tú ag faire ar {{PLURAL:$1|leathanach amháin|$1 leathanaigh}}, gan leathanaigh phlé a chur san áireamh.',
'wlheader-enotif'      => '* Cumasaíodh fógraí riomhphoist.',
'wlheader-showupdated' => "* Tá '''cló trom''' ar leathanaigh a athraíodh ón uair is deireanaí a d'fhéach tú orthu.",
'watchmethod-recent'   => 'ag seiceáil na athruithe deireanacha ar do chuid leathanaigh faire',
'watchmethod-list'     => 'ag seiceáil na leathanaigh faire ar do chuid athruithe deireanacha',
'watchlistcontains'    => 'Tá {{PLURAL:$1|leathanach amháin|$1 leathanaigh}} ar do liosta faire.',
'iteminvalidname'      => "Fadhb leis an mír '$1', ainm neamhbhailí...",
'wlnote'               => "Is {{PLURAL:$1|é seo thíos an t-athrú deireanach|iad seo thíos na '''$1''' athruithe deireanacha}} {{PLURAL:$2|san uair deireanach|sna '''$2''' uaire deireanacha}}.",
'wlshowlast'           => 'Líon na n-uair is déanaí le taispeáint: $1. Líon na laethanta is déanaí le taispeáint: $2. Taispeáin $3.',
'watchlist-options'    => 'Roghanna don liosta faire',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ag faire...',
'unwatching' => 'Á bhaint de do liosta faire...',

'enotif_mailer'                => 'Fógrasheoltóir as {{SITENAME}}',
'enotif_reset'                 => 'Marcáil gach leathanach bheith tadhlaithe',
'enotif_newpagetext'           => 'Is leathanach nua é seo.',
'enotif_impersonal_salutation' => 'úsáideoir {{SITENAME}}',
'changed'                      => "D'athraigh",
'created'                      => 'Cruthaigh',
'enotif_subject'               => '  $CHANGEDORCREATED $PAGEEDITOR an leathanach $PAGETITLE ag {{SITENAME}}.',
'enotif_lastvisited'           => 'Féach ar $1 le haghaidh gach athrú a rinneadh ó thús na cuairte seo caite a rinne tú.',
'enotif_anon_editor'           => 'úsáideoir gan ainm $1',
'enotif_body'                  => 'A $WATCHINGUSERNAME, a chara,

$CHANGEDORCREATED $PAGEEDITOR an leathanach $PAGETITLE  ag {{SITENAME}} ar $PAGEEDITDATE, féach ar $PAGETITLE_URL chun an leagan reatha a fháil.

$NEWPAGE

Athchoimriú an úsáideora a rinne é: $PAGESUMMARY $PAGEMINOREDIT

Sonraí teagmhála an úsáideora:
r-phost: $PAGEEDITOR_EMAIL
vicí: $PAGEEDITOR_WIKI

I gcás athruithe eile, ní bheidh aon fhógra eile muna dtéann tú go dtí an leathanach seo. Is féidir freisin na bratacha fógartha a athrú do gach leathanach ar do liosta faire.

	     Is mise le meas,
	     Fógrachóras cairdiúil {{GRAMMAR:genitive|{{SITENAME}}}}

--
Chun socruithe do liosta faire a athrú, tabhair cuairt ar
{{fullurl:Special:Watchlist/edit}}

Aiseolas agus a thuilleadh cabhrach:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'            => 'Scrios an leathanach',
'confirm'               => 'Cinntigh',
'excontent'             => "téacs an lch: '$1'",
'excontentauthor'       => "seo a bhí an t-inneachar: '$1' (agus ba é '[[Special:Contributions/$2|$2]]' an t-aon dhréachtóir)",
'exbeforeblank'         => "is é seo a raibh an ábhar roimh an folmhadh: '$1'",
'exblank'               => 'bhí an leathanach folamh',
'delete-confirm'        => 'Scrios "$1"',
'delete-legend'         => 'Scrios',
'historywarning'        => 'Aire: Ta stair ag an leathanach a bhfuil tú ar tí é a scriosadh:',
'confirmdeletetext'     => 'Tá tú ar tí leathanach, agus a chuid staire, a scriosadh.
Deimhnigh, le do thoil, gur mhian leat é seo a dhéanamh, go dtuigeann tú torthaí an ghnímh seo agus go bhfuil tú dá dhéanamh de réir [[{{MediaWiki:Policy-url}}|an pholasaí]].',
'actioncomplete'        => 'Gníomh críochnaithe',
'deletedtext'           => 'scriosadh "<nowiki>$1</nowiki>".
Féach ar $2 chun cuntas na scriosiadh deireanacha a fháil.',
'deletedarticle'        => 'scriosadh "[[$1]]"',
'dellogpage'            => 'Loga scriosta',
'dellogpagetext'        => 'Seo é liosta de na scriosaidh is déanaí.',
'deletionlog'           => 'cuntas scriosaidh',
'reverted'              => 'Tá eagrán níos luaithe in úsáid anois',
'deletecomment'         => 'Cúis don scriosadh',
'deleteotherreason'     => 'Fáth eile/breise:',
'deletereasonotherlist' => 'Fáth eile',
'deletereason-dropdown' => '*Fáthanna coitianta scriosta
** Iarratas ón údar
** Sárú cóipchirt
** Loitiméireacht',

# Rollback
'rollback'       => 'Athruithe a rolladh siar',
'rollback_short' => 'Roll siar',
'rollbacklink'   => 'roll siar',
'rollbackfailed' => 'Theip an rolladh siar',
'cantrollback'   => 'Ní féidir an athrú a athúsáid; ba é údar an ailt an t-aon duine a rinne athrú dó.',
'alreadyrolled'  => "Ní féidir eagrán níos luaí an leathanaigh [[:$1]]
le [[User:$2|$2]] ([[User talk:$2|Plé]]) a athúsáid; d'athraigh duine eile é cheana fein, nó
d'athúsáid duine eile eagrán níos luaí cheana féin.

[[User:$3|$3]] ([[User talk:$3|Plé]]) an té a rinne an athrú is déanaí.",
'editcomment'    => 'Seo a raibh an mínithe athraithe: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'     => "Filleadh eagarthóireachtaí le [[Special:Contributions/$2|$2]] ([[User talk:$2|Plé]]); d'athúsáideadh an athrú seo caite le [[User:$1|$1]]", # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from

# Protect
'protectlogpage'              => 'Log cosanta',
'protectlogtext'              => 'Seo é liosta de glais a cuireadh ar / baineadh de leathanaigh.
Féach ar [[Special:ProtectedPages|Leathanach glasáilte]] chun a thuilleadh eolais a fháil.',
'protectedarticle'            => 'glasáladh "[[$1]]"',
'unprotectedarticle'          => 'díghlasáladh "[[$1]]"',
'protect-title'               => 'Ag glasáil "$1"',
'prot_1movedto2'              => 'Athainmníodh $1 mar $2',
'protect-legend'              => 'Cinntigh an glasáil',
'protectcomment'              => 'Cúis don glasáil:',
'protectexpiry'               => 'As feidhm:',
'protect_expiry_invalid'      => 'Am éaga neamhbhailí.',
'protect_expiry_old'          => 'Am éaga san am atá thart.',
'protect-unchain'             => 'Díghlasáil an cead athainmithe',
'protect-text'                => 'Is féidir leat an leibhéal glasála a athrú anseo don leathanach <strong><nowiki>$1</nowiki></strong>.',
'protect-locked-access'       => 'Ní chead ag do chuntas chun athraigh leibhéal cosaint an leathanach.
Seo iad na socruithe reatha faoin leathanach <strong>$1</strong>:',
'protect-cascadeon'           => 'Tá an leathanach seo ghlasáil le athrú mar tá se iniata ar {{PLURAL:$1|an leathanach seo|na leathanaigh seo}} a leanas, agus iad ghlasáil le glasáil cascáideach.
Is féidir an leibhéal glasála a athrú, ach ní féidir cur isteach ar an ghlasáil cascáideach.',
'protect-default'             => '(réamhshocrú)',
'protect-fallback'            => 'Ceadúnas "$1" riachtanach',
'protect-level-autoconfirmed' => 'Bac úsáideoirí neamhchláraithe',
'protect-level-sysop'         => 'Oibreoirí chórais amháin',
'protect-summary-cascade'     => 'cascáidithe',
'protect-expiring'            => 'as feidhm $1 (UTC)',
'protect-expiry-indefinite'   => 'gan teora',
'protect-cascade'             => 'Coisc leathanaigh san áireamh an leathanach seo (cosanta cascáideach)',
'protect-cantedit'            => 'Ní féidir leat na leibhéil cosanta a athrú faoin leathanach seo, mar níl cead agat é a cur in eagar.',
'protect-othertime'           => 'Am eile:',
'protect-othertime-op'        => 'am eile',
'protect-expiry-options'      => 'uair amháin:1 hour,1 lá amháin:1 day,seachtain amháin:1 week,2 sheachtain:2 weeks,mí amháin:1 month,3 mhí:3 months,6 mhí:6 months,bliain amháin:1 year,gan teora:infinite', # display1:time1,display2:time2,...
'restriction-type'            => 'Ceadúnas:',
'restriction-level'           => 'Leibhéal srianadh:',
'pagesize'                    => '(bearta)',

# Restrictions (nouns)
'restriction-create' => 'Cruthaigh',
'restriction-upload' => 'Uaslódaigh',

# Restriction levels
'restriction-level-autoconfirmed' => 'leathghlasáil',

# Undelete
'undelete'                 => 'Díscrios leathanach scriosta',
'undeletepage'             => 'Féach ar leathanaigh scriosta agus díscrios iad',
'viewdeletedpage'          => 'Féach ar leathanaigh scriosta',
'undeletepagetext'         => 'Scriosaíodh {{PLURAL:$1|an leathanach|na $1 leathanaigh}} seo a leanas cheana féin, ach tá síad sa cartlann fós agus is féidir iad a dhíscrios.
Ó am go ham, is féidir an cartlann a fholmhú.',
'undelete-fieldset-title'  => 'Díscrios leagain',
'undeleterevisions'        => 'Cuireadh {{PLURAL:$1|leagan amháin|$1 leagain}} sa chartlann',
'undeletehistory'          => 'Dá díscriosfá an leathanach, díscriosfar gach leasú i stair an leathanaigh.
Dá gcruthaíodh leathanach nua leis an teideal céanna ó shin an scriosadh, taispeáinfear na sean-athruithe san stair roimhe seo, agus ní athshuífear leagan láithreach an leathanaigh go huathoibríoch.',
'undeletehistorynoadmin'   => 'Tá an leathanach seo scriosta.
An fáth scriosadh ná a leanas san achoimre faoi bhun, agus le sonraí na úsáideoirí é a chur in eagar roimh scriosadh.
Is an téacs as na leagan scriosta seo ar fáil do riarthóirí amháin.',
'undelete-revision'        => 'Leagan scriosta $1 (ó $4, ar $5) le $3:',
'undeletebtn'              => 'Díscrios!',
'undeletelink'             => 'díscrios',
'undeletereset'            => 'Athshocraigh',
'undeleteinvert'           => 'Cuir an roghnú bun os cionn',
'undeletecomment'          => 'Tuairisc:',
'undeletedarticle'         => 'Díscriosadh "$1" ar ais',
'undeletedrevisions'       => '{{PLURAL:$1|Leagan amháin|$1 leagain}} díscriosta',
'undeletedrevisions-files' => '{{PLURAL:$1|Leagan amháin|$1 leagain}} agus {{PLURAL:$2|comhad amháin|$2 comhaid}} a chur ar ais',
'undeletedfiles'           => '{{PLURAL:$1|Comhad amháin|$1 comhaid}} díscriosta',
'undelete-search-box'      => 'Cuardaigh leathanaigh scriosta',
'undelete-search-submit'   => 'Cuardaigh',

# Namespace form on various pages
'namespace'      => 'Ainmspás:',
'invert'         => 'Iompaigh rogha bunoscionn',
'blanknamespace' => '(Gnáth)',

# Contributions
'contributions'       => 'Dréachtaí úsáideora',
'contributions-title' => 'Dréachtaí úsáideora do $1',
'mycontris'           => 'Mo chuid dréachtaí',
'contribsub2'         => 'Do $1 ($2)',
'nocontribs'          => 'Níor bhfuarthas aon athrú a raibh cosúil le na crítéir seo.',
'uctop'               => ' (barr)',
'month'               => 'Ón mhí seo (agus níos luaithe):',
'year'                => 'Ón bhliain seo (agus níos luaithe):',

'sp-contributions-newbies'       => 'Taispeáin dréachtaí ó chuntais nua amháin',
'sp-contributions-newbies-sub'   => 'Le cuntais nua',
'sp-contributions-newbies-title' => 'Dréachtaí úsáideora do chuntasaí nua',
'sp-contributions-blocklog'      => 'Log coisc',
'sp-contributions-search'        => 'Cuardaigh dréachtaí',
'sp-contributions-username'      => 'Seoladh IP nó ainm úsáideora:',
'sp-contributions-submit'        => 'Cuardaigh',

# What links here
'whatlinkshere'            => 'Naisc don lch seo',
'whatlinkshere-title'      => 'Naisc chuig $1',
'whatlinkshere-page'       => 'Leathanach:',
'linkshere'                => "Tá nasc chuig '''[[:$1]]''' ar na leathanaigh seo a leanas:",
'nolinkshere'              => "Níl leathanach ar bith ann a bhfuil nasc chuig '''[[:$1]]''' air.",
'nolinkshere-ns'           => "Níl leathanach ar bith ann san ainmspás roghnaithe a bhfuil nasc chuig '''[[:$1]]''' air.",
'isredirect'               => 'Leathanach athsheolaidh',
'istemplate'               => 'iniamh',
'isimage'                  => 'nasc íomhá',
'whatlinkshere-prev'       => '{{PLURAL:$1|roimhe|$1 roimhe}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ar aghaidh|$1 ar aghaidh}}',
'whatlinkshere-links'      => '← naisc',
'whatlinkshere-hidetrans'  => '$1 trasiamh',
'whatlinkshere-hidelinks'  => '$1 nasc',
'whatlinkshere-hideimages' => '$1 nasc íomhá',

# Block/unblock
'blockip'                 => 'Coisc úsáideoir',
'blockip-legend'          => 'Cosc úsáideoir',
'blockiptext'             => 'Úsáid an foirm anseo thíos chun bealach scríofa a chosc ó
seoladh IP nó ainm úsáideora áirithe.
Is féidir leat an rud seo a dhéanamh amháin chun an chreachadóireacht a chosc, de réir
mar a deirtear sa [[{{MediaWiki:Policy-url}}|polasaí {{GRAMMAR:genitive|{{SITENAME}}}}]].
Líonaigh cúis áirithe anseo thíos (mar shampla, is féidir leat a luaigh
leathanaigh áirithe a rinne an duine damáiste ar).',
'ipaddress'               => 'Seoladh IP / ainm úsáideora',
'ipadressorusername'      => 'Seoladh IP nó ainm úsáideora:',
'ipbexpiry'               => 'Am éaga',
'ipbreason'               => 'Cúis',
'ipbreasonotherlist'      => 'Fáth eile',
'ipbreason-dropdown'      => '*Fáthanna coitianta
** Loitiméaracht
** Naisc turscar
** Fadhbanna cóipcheart
** Ag iarraidh ciapadh daoine eile
** Drochúsáid as cuntais iolrach
** Fadhbanna idirvicí
** Feallaire
** Seachfhreastalaí Oscailte',
'ipbsubmit'               => 'Coisc an úsáideoir seo',
'ipbother'                => 'Méid eile ama',
'ipboptions'              => '2 uair:2 hours,1 lá amháin:1 day,3 lá:3 days,1 sheachtain amháin:1 week,2 sheachtain:2 weeks,1 mhí amháin:1 month,3 mhí:3 months,6 mhí:6 months,1 bhliain amháin:1 year,gan teorainn:infinite', # display1:time1,display2:time2,...
'ipbotheroption'          => 'eile',
'badipaddress'            => 'Níl aon úsáideoir ann leis an ainm seo.',
'blockipsuccesssub'       => "D'éirigh leis an cosc",
'blockipsuccesstext'      => 'Choisceadh [[Special:Contributions/$1|$1]].
<br />Féach ar an g[[Special:IPBlockList|liosta coisc IP]] chun coisc a athbhreithniú.',
'ipb-unblock-addr'        => 'Díchoisc $1',
'ipb-unblock'             => 'Díchosc ainm úsáideora ná seoladh IP',
'unblockip'               => 'Díchoisc úsáideoir',
'unblockiptext'           => 'Úsáid an foirm anseo thíos chun bealach scríofa a thabhairt ar ais do seoladh
IP nó ainm úsáideora a raibh faoi chosc roimhe seo.',
'ipusubmit'               => 'Díchoisc an seoladh seo',
'unblocked'               => 'Díchoisceadh [[User:$1|$1]]',
'ipblocklist'             => 'Liosta seoltaí IP agus ainmneacha úsáideoirí coiscthe',
'ipblocklist-legend'      => 'Aimsigh úsáideoir coiscthe',
'ipblocklist-username'    => 'Ainm úsáideora ná seoladh IP:',
'ipblocklist-submit'      => 'Cuardaigh',
'blocklistline'           => '$1, $2 a choisc $3 (am éaga $4)',
'infiniteblock'           => 'gan teora',
'anononlyblock'           => 'úsáideoirí gan ainm agus iad amháin',
'ipblocklist-empty'       => 'Tá an liosta coisc folamh.',
'blocklink'               => 'Cosc',
'unblocklink'             => 'bain an cosc',
'contribslink'            => 'dréachtaí',
'autoblocker'             => 'Coisceadh go huathoibríoch thú dá bharr gur úsáideadh do sheoladh IP ag an úsáideoir "[[User:$1|$1]]". Is é seo an cúis don cosc ar $1: "$2".',
'blocklogpage'            => 'Cuntas_coisc',
'blocklogentry'           => 'coisceadh [[$1]]; is é $2 an am éaga $3',
'blocklogtext'            => 'Seo é cuntas de gníomhartha coisc úsáideoirí agus míchoisc úsáideoirí. Ní cuirtear
seoltaí IP a raibh coiscthe go huathoibríoch ar an liosta seo. Féach ar an
[[Special:IPBlockList|Liosta coisc IP]] chun
liosta a fháil de coisc atá i bhfeidhm faoi láthair.',
'unblocklogentry'         => 'díchoisceadh $1',
'block-log-flags-noemail' => 'cosc ar ríomhphost',
'range_block_disabled'    => 'Faoi láthair, míchumasaítear an cumas riarthóra chun réimsechoisc a dhéanamh.',
'ipb_expiry_invalid'      => 'Am éaga neamhbhailí.',
'ipb_already_blocked'     => 'Tá cosc ar "$1" cheana féin',
'ip_range_invalid'        => 'Réimse IP neamhbhailí.',
'proxyblocker'            => 'Cosc ar seachfhreastalaithe',
'proxyblockreason'        => "Coisceadh do sheoladh IP dá bharr gur seachfhreastalaí
neamhshlándála is ea é. Déan teagmháil le do chomhlacht idirlín nó le do lucht cabhrach teicneolaíochta
go mbeidh 'fhios acu faoin fadhb slándála tábhachtach seo.",
'proxyblocksuccess'       => 'Rinneadh.',
'sorbsreason'             => 'Liostalaítear do sheoladh IP mar sheachfhreastalaí oscailte sa DNSBL.',

# Developer tools
'lockdb'              => 'Glasáil an bunachar sonraí',
'unlockdb'            => 'Díghlasáil bunachar sonraí',
'lockdbtext'          => "Dá nglasálfá an bunachar sonraí, ní beidh cead ar aon úsáideoir
leathanaigh a chur in eagar, a socruithe a athrú, a liostaí faire a athrú, nó rudaí eile a thrachtann le
athruithe san bunachar sonraí.
Cinntigh go bhfuil an scéal seo d'intinn agat, is go díghlasálfaidh tú an bunachar sonraí nuair a bhfuil
do chuid cothabháile críochnaithe.",
'unlockdbtext'        => "Dá díghlasálfá an bunachar sonraí, beidh ceat ag gach úsáideoirí aris
na leathanaigh a chur in eagar, a sainroghanna a athrú, a liostaí faire a athrú, agus rudaí eile
a dhéanamh a thrachtann le athruithe san bunachar sonraí.
Cinntigh go bhfuil an scéal seo d'intinn agat.",
'lockconfirm'         => 'Sea, is mian liom an bunachar sonraí a ghlasáil.',
'unlockconfirm'       => 'Sea, is mian liom an bunachar sonraí a dhíghlasáil.',
'lockbtn'             => 'Glasáil an bunachar sonraí',
'unlockbtn'           => 'Díghlasáil an bunachar sonraí',
'locknoconfirm'       => 'Níor mharcáil tú an bosca daingnithe.',
'lockdbsuccesssub'    => "D'éirigh le glasáil an bhunachair sonraí",
'unlockdbsuccesssub'  => "D'éirigh le díghlasáil an bhunachair sonraí",
'lockdbsuccesstext'   => 'Glasáladh an bunachar sonraí {{GRAMMAR:genitive|{{SITENAME}}}}.
<br />Cuimhnigh nach mór duit é a dhíghlasáil tar éis do chuid cothabháil.',
'unlockdbsuccesstext' => 'Díghlasáladh an bunachar sonraí {{GRAMMAR:genitive|{{SITENAME}}}}.',
'databasenotlocked'   => 'Níl an bunachar sonraí faoi ghlas.',

# Move page
'move-page'               => 'Athainmnigh $1',
'move-page-legend'        => 'Athainmnigh an leathanach',
'movepagetext'            => "Úsáid an fhoirm seo thíos chun leathanach a athainmniú. Aistreofar a chuid staire go léir chuig an teideal nua.
Déanfar leathanach atreoraithe den sean-teideal chuig an teideal nua.
Ní athrófar naisc chuig sean-teideal an leathanaigh; 
bí cinnte go ndéanfá cuardach ar atreoruithe [[Special:DoubleRedirects|dúbailte]] nó [[Special:BrokenRedirects|briste]].
Tá dualgas ort bheith cinnte go rachaidh na naisc chuig an áit is ceart fós.

Tabhair faoi deara '''nach''' n-athainmneofar an leathanach má tá leathanach ann cheana féin faoin teideal nua, ach amháin más folamh nó atreorú é nó mura bhfuil aon stair athraithe aige cheana.
Mar sin, is féidir leathanach a athainmniú ar ais chuig an teideal a raibh air roimhe má tá botún déanta agat, agus ní féidir leathanach atá ann cheana a fhorscríobh.

<font color=\"red\">'''AIRE!'''</font>
Is féidir gur dianbheart gan choinne é athrú a dhéanamh ar leathanach móréilimh;
cinntigh go dtuigeann tú na hiarmhairtí go léir roimh dul ar aghaigh.",
'movepagetalktext'        => "Aistreofar an leathanach phlé leis, má tá sin ann:
*'''muna''' bhfuil tú ag aistriú an leathanach trasna ainmspásanna,
*'''muna''' bhfuil leathanach phlé neamhfholamh ann leis an teideal nua, nó
*'''muna''' bhaineann tú an marc den bosca anseo thíos.

Sna scéil sin, caithfidh tú an leathanach a aistrigh nó a báigh leis na lámha má tá sin an rud atá uait.",
'movearticle'             => 'Athainmnigh an leathanach',
'movenologin'             => 'Ní  logáilte isteach tú',
'movenologintext'         => "Ní mór duit bheith i d'úsáideoir cláraithe agus [[Special:UserLogin|logáilte isteach]] chun leathanach a hathainmniú.",
'movenotallowed'          => 'Níl cead agat leathanaigh a athainmniú.',
'newtitle'                => 'Go teideal nua',
'move-watch'              => 'Déan faire an leathanach seo',
'movepagebtn'             => 'Athainmnigh an leathanach',
'pagemovedsub'            => "D'éirigh leis an athainmniú",
'movepage-moved'          => '<big>\'\'\'Athainmníodh "$1" mar "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Tá leathanach leis an teideal seo ann cheana féin, nó níl an teideal a roghnaigh tú ina theideal bailí. Roghnaigh teideal eile le do thoil.',
'talkexists'              => "'''D’athainmníodh an leathanach é féin go rathúil, ach ní raibh sé ar a chumas an leathanach phlé a hathainmniú dá bharr go bhfuil ceann ann cheana féin ag an teideal nua.'''<br />
'''Báigh tusa féin iad.'''",
'movedto'                 => 'athainmnithe bheith',
'movetalk'                => 'Athainmnigh an leathanach plé freisin.',
'1movedto2'               => 'Athainmníodh $1 mar $2',
'1movedto2_redir'         => 'Rinneadh athsheoladh de $1 go $2.',
'movelogpage'             => 'Log athainmnithe',
'movelogpagetext'         => 'Liosta is ea seo thíos de leathanaigh athainmnithe.',
'movereason'              => 'Cúis',
'revertmove'              => 'athúsáid',
'delete_and_move'         => 'Scrios agus athainmnigh',
'delete_and_move_text'    => '==Tá scriosadh riachtanach==
Tá an leathanach sprice ("[[:$1]]") ann cheana féin.
Ar mhaith leat é a scriosadh chun áit a dhéanamh don athainmniú?',
'delete_and_move_confirm' => 'Tá, scrios an leathanach',
'delete_and_move_reason'  => "Scriosta chun áit a dhéanamh d'athainmniú",
'selfmove'                => 'Tá an ainm céanna ag an bhfoinse mar atá ar an ainm sprice; ní féidir leathanach a athainmniú bheith é féin.',

# Export
'export'          => 'Easportáil leathanaigh',
'exporttext'      => 'Is féidir leat an téacs agus stair athraithe de leathanach áirithe a heasportáil,
fillte i bpíosa XML; is féidir leat ansin é a iompórtáil isteach vicí eile atá le na bogearraí MediaWiki
air, nó is féidir leat é a coinniú do do chuid shiamsa féin.',
'exportcuronly'   => 'Ná cuir san áireamh ach an leagan láithreach; ná cuir an stair iomlán ann',
'export-submit'   => 'Easportáil',
'export-download' => 'Sábháil mar comhad',

# Namespace 8 related
'allmessages'               => 'Teachtaireachtaí córais',
'allmessagesname'           => 'Ainm',
'allmessagesdefault'        => 'Téacs réamhshocraithe',
'allmessagescurrent'        => 'Téacs reatha',
'allmessagestext'           => 'Liosta is ea seo de theachtaireachtaí córais atá le fáil san ainmspás MediaWiki: .',
'allmessagesnotsupportedDB' => "Ní féidir an leathanach seo a úsáid dá bharr gur díchumasaíodh '''\$wgUseDatabaseMessages'''.",
'allmessagesfilter'         => "Scagaire teachtaireacht d'ainm:",

# Thumbnails
'thumbnail-more'  => 'Méadaigh',
'filemissing'     => 'Comhad ar iarraidh',
'thumbnail_error' => 'Earráid mionsamhail a crutháil: $1',

# Special:Import
'import'                  => 'Iompórtáil leathanaigh',
'importinterwiki'         => 'Iompórtáil trasna vicithe',
'import-interwiki-submit' => 'iompórtáil',
'importtext'              => 'Easportáil an comhad ón vici-fhoinse (le húsáid na [[Special:Export|tréithe easportáil]]), sábháil ar do dhíosca é agus uaslódáil anseo é.',
'import-revision-count'   => '{{PLURAL:$1|Leagan amháin|$1 leagain}}',
'importnopages'           => 'Níl aon leathanaigh chun iompórtáil',
'importfailed'            => 'Theip ar an iompórtáil: $1',
'importnotext'            => 'Folamh nó gan téacs',
'importsuccess'           => "D'eirigh leis an iompórtáil!",
'importhistoryconflict'   => 'Tá stair athraithe contrártha ann cheana féin (is dócha go
uaslódáladh an leathanach seo roimh ré)',
'importnosources'         => "Níl aon fhoinse curtha i leith d'iompórtáil trasna vicíonna, agus
ní féidir uaslódála staire díreacha a dhéanamh faoi láthair.",
'xml-error-string'        => '$1 ag líne $2, col $3 ($4 bearta): $5',
'import-upload'           => 'Uaslódaigh sonraí XML',
'import-token-mismatch'   => 'Sonraí seisiún a bheith caillte. Déan iarracht arís.',

# Import log
'importlogpage'             => 'Log iompórtáil',
'import-logentry-interwiki' => 'traisvicithe $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mo leathanach úsáideora',
'tooltip-pt-anonuserpage'         => 'Leathanach úsáideora don IP ina dhéanann tú do chuid athruithe',
'tooltip-pt-mytalk'               => 'Mo leathanach plé',
'tooltip-pt-anontalk'             => 'Plé maidir le na hathruithe a dhéantar ón seoladh IP seo',
'tooltip-pt-preferences'          => 'Mo chuid sainroghanna',
'tooltip-pt-watchlist'            => 'Liosta de na leathanaigh a bhfuil tú á bhfaire ar athruithe',
'tooltip-pt-mycontris'            => 'Liosta de mo chuid dréachtaí',
'tooltip-pt-login'                => 'Moltar duit logáil isteach, ach níl sé riachtanach.',
'tooltip-pt-anonlogin'            => 'Moltar duit logáil isteach, ach níl sé riachtanach.',
'tooltip-pt-logout'               => 'Logáil amach',
'tooltip-ca-talk'                 => 'Plé maidir leis an leathanach ábhair',
'tooltip-ca-edit'                 => 'Is féidir leat an leathanach seo a athrú. Más é do thoil é, bain úsáid as an cnaipe réamhamhairc roimh sábháil a dhéanamh.',
'tooltip-ca-addsection'           => 'Cuir trácht leis an plé seo..',
'tooltip-ca-viewsource'           => 'Tá an leathanach seo glasáilte. Is féidir leat a fhoinse a fheiceáil.',
'tooltip-ca-history'              => 'Leagain stairiúla den leathanach seo.',
'tooltip-ca-protect'              => 'Glasáil an leathanach seo',
'tooltip-ca-delete'               => 'Scrios an leathanach seo',
'tooltip-ca-undelete'             => 'Díscrios na hathruithe a rinneadh don leathanach seo roimh a scriosadh é',
'tooltip-ca-move'                 => 'Athainmnigh an leathanach',
'tooltip-ca-watch'                => 'Cuir an leathanach seo le do liosta faire',
'tooltip-ca-unwatch'              => 'Bain an leathanach seo de do liosta faire',
'tooltip-search'                  => 'Cuardaigh sa vicí seo',
'tooltip-p-logo'                  => 'Príomhleathanach',
'tooltip-n-mainpage'              => 'Tabhair cuairt ar an bPríomhleathanach',
'tooltip-n-portal'                => 'Maidir leis an tionscadal, cad is féidir leat a dhéanamh, conas achmhainní a fháil',
'tooltip-n-currentevents'         => 'Faigh eolas cúlrach maidir le chursaí reatha',
'tooltip-n-recentchanges'         => 'Liosta de na hathruithe is déanaí sa vicí.',
'tooltip-n-randompage'            => 'Lódáil leathanach fánach',
'tooltip-n-help'                  => 'An áit chun cabhair a fháil.',
'tooltip-t-whatlinkshere'         => 'Liosta de gach leathanach sa vicí ina bhfuil nasc chuig an leathanach seo',
'tooltip-t-recentchangeslinked'   => 'Na hathruithe is déanaí ar leathanaigh a nascaíonn chuig an leathanach seo',
'tooltip-feed-rss'                => 'Fotha RSS don leathanach seo',
'tooltip-feed-atom'               => 'Fotha Atom don leathanach seo',
'tooltip-t-contributions'         => 'Féach ar an liosta dréachtaí a rinne an t-úsáideoir seo',
'tooltip-t-emailuser'             => 'Cuir teachtaireacht chuig an úsáideoir seo',
'tooltip-t-upload'                => 'Comhaid íomhá nó meáin a uaslódáil',
'tooltip-t-specialpages'          => 'Liosta de gach leathanach speisialta',
'tooltip-ca-nstab-main'           => 'Féach ar an leathanach ábhair',
'tooltip-ca-nstab-user'           => 'Féach ar an leathanach úsáideora',
'tooltip-ca-nstab-media'          => 'Féach ar an leathanach meáin',
'tooltip-ca-nstab-special'        => 'Is leathanach speisialta é seo, ní féidir leat an leathanach é fhéin a athrú.',
'tooltip-ca-nstab-project'        => 'Féach ar an leathanach thionscadail',
'tooltip-ca-nstab-image'          => 'Féach ar an leathanach íomhá',
'tooltip-ca-nstab-mediawiki'      => 'Féach ar an teachtaireacht córais',
'tooltip-ca-nstab-template'       => 'Féach ar an teimpléad',
'tooltip-ca-nstab-help'           => 'Féach ar an leathanach cabhrach',
'tooltip-ca-nstab-category'       => 'Féach ar an leathanach catagóire',
'tooltip-minoredit'               => 'Déan mionathrú den athrú seo',
'tooltip-save'                    => 'Sábháil do chuid athruithe',
'tooltip-preview'                 => 'Réamhamharc ar do chuid athruithe; úsáid an gné seo roimh a shábhálaíonn tú!',
'tooltip-diff'                    => 'Taispeáin na difríochtaí áirithe a rinne tú don téacs',
'tooltip-compareselectedversions' => 'Féach na difríochtaí idir an dhá leagain roghnaithe den leathanach seo.',
'tooltip-watch'                   => 'Cuir an leathanach seo le do liosta faire',

# Stylesheets
'monobook.css' => '/* athraigh an comhad seo chun an craiceann MonoBook a athrú don suíomh ar fad */',

# Metadata
'nodublincore'      => 'Míchumasaítear meitea-shonraí Dublin Core RDF ar an freastalaí seo.',
'nocreativecommons' => 'Míchumasaítear meitea-shonraí Creative Commons RDF ar an freastalaí seo.',
'notacceptable'     => 'Ní féidir leis an freastalaí vicí na sonraí a chur ar fáil i bhformáid atá inléite ag do chliant.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Úsáideoir|Úsáideoirí}} gan ainm ar {{SITENAME}}',
'siteuser'         => 'Úsáideoir $1 ag {{SITENAME}}',
'lastmodifiedatby' => 'Leasaigh $3 an leathanach seo go déanaí ag $2, $1.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Bunaithe ar saothair le $1.',
'others'           => 'daoine eile',
'siteusers'        => '{{PLURAL:$2|Úsáideoirí|Úsáideoir}} ag {{SITENAME}} $1',
'creditspage'      => 'Creidiúintí leathanaigh',
'nocredits'        => 'Níl aon eolas creidiúna le fáil don leathanach seo.',

# Spam protection
'spamprotectiontitle' => 'Scagaire in aghaidh ríomhphost dramhála',
'spamprotectiontext'  => 'Chuir an scagaire dramhála bac ar an leathanach a raibh tú ar
iarradh sábháil. Is dócha gur nasc chuig suíomh seachtrach ba chúis leis.',
'spamprotectionmatch' => 'Truicear ár scagaire dramhála ag an téacs seo a leanas: $1',
'spambot_username'    => 'MediaWiki turscar glanadh',

# Info page
'infosubtitle'   => 'Eolas don leathanach',
'numedits'       => 'Méid athruithe (alt): $1',
'numtalkedits'   => 'Méid athruithe (leathanach phlé): $1',
'numwatchers'    => 'Méid féachnóirí: $1',
'numauthors'     => 'Méid údair ar leith (alt): $1',
'numtalkauthors' => 'Méid údair ar leith (leathanach phlé): $1',

# Skin names
'skinname-standard'    => 'Gnáth',
'skinname-nostalgia'   => 'Sean-nós',
'skinname-cologneblue' => 'Gorm na Colóna',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',

# Math options
'mw_math_png'    => 'Déan PNG-íomhá gach uair',
'mw_math_simple' => 'Déan HTML má tá sin an-easca, nó PNG ar mhodh eile',
'mw_math_html'   => 'Déan HTML más féidir, nó PNG ar mhodh eile',
'mw_math_source' => 'Fág mar cló TeX (do teacsleitheoirí)',
'mw_math_modern' => 'Inmholta do bhrabhsálaithe nua',
'mw_math_mathml' => 'MathML más féidir (turgnamhach)',

# Patrolling
'markaspatrolleddiff'   => 'Comharthaigh mar patrólta.',
'markaspatrolledtext'   => 'Comharthaigh an t-alt seo mar patrólta',
'markedaspatrolled'     => 'Comharthaithe mar patrólta',
'markedaspatrolledtext' => 'Marcáladh an athrú áirithe seo bheith patrólaithe.',
'rcpatroldisabled'      => 'Mhíchumasaíodh Patról na n-Athruithe is Déanaí',
'rcpatroldisabledtext'  => 'Tá an tréith Patról na n-Athruithe is Déanaí míchumasaithe faoi láthair.',

# Patrol log
'patrol-log-page'      => 'Log phatról',
'patrol-log-auto'      => '(uathoibríoch)',
'log-show-hide-patrol' => '$1 log phatról',

# Image deletion
'deletedrevision'       => 'Scriosadh an sean-leagan $1',
'filedeleteerror-short' => 'Earráid comhad a scriosadh: $1',

# Browsing diffs
'previousdiff' => '← Gabh chuig an difear roimhe seo',
'nextdiff'     => 'An chéad dhifear eile →',

# Media information
'mediawarning'         => "'''Aire''': Tá seans ann go bhfuil cód mailíseach sa comhad seo - b'fheidir go gcuirfear do chóras i gcontúirt dá rithfeá é.
<hr />",
'imagemaxsize'         => 'Cuir an teorann seo ar na íomhánna atá le fáil ar leathanaigh cuir síos íomhánna:',
'thumbsize'            => 'Méid an mionsamhail:',
'file-info'            => '(méid comhad : $1, saghas MIME: $2)',
'file-info-size'       => '($1 × $2 picteilín, méid comhaid: $3, cineál MIME: $4)',
'file-nohires'         => '<small>Níl aon taifeach is mó ar fáil.</small>',
'svg-long-desc'        => '(Comhad SVG, ainmniúil $1 × $2 picteilíni, méid comhaid: $3)',
'show-big-image'       => 'Taispeáin leagan ardtaifigh den íomhá',
'show-big-image-thumb' => '<small>Méid an réamhamhairc seo: $1 × $2 picteilín</small>',

# Special:NewFiles
'newimages'             => 'Gailearaí na n-íomhánna nua',
'imagelisttext'         => 'Tá liosta thíos de {{PLURAL:$1|comhad amháin|$1 comhaid $2}}.',
'newimages-label'       => 'Comhadainm (nó cuid de):',
'showhidebots'          => '($1 róbónna)',
'noimages'              => 'Tada le feiceáil.',
'ilsubmit'              => 'Cuardaigh',
'bydate'                => 'de réir dáta',
'sp-newimages-showfrom' => 'Taispeáin íomhánna nua as $2, $1',

# Bad image list
'bad_image_list' => 'An formáid ná a leanas:

Míreanna liosta amháin (líonta a tosú le *) atá eisithe.
Tá ar an chead nasc ar líne, naiscthe le drochchomhad.
Aon naisc a leanas ar an líne céanna atá eisithe mar eisceachtaí; leathanaigh ina tarlaigh an comhad inlíne.',

# Metadata
'metadata'          => 'Meiteasonraí',
'metadata-help'     => "Tá breis eolais sa comhad seo, curtha, is dócha, as ceamara digiteach ná scanóir a chruthaigh ná a digitigh é.
Má tá an comhad mionathraithe as an bunleagan, b'fhéidir nach mbeidh ceann de na sonraí fágtha sa comhad atá athruithe.",
'metadata-expand'   => 'Taispeáin sonraí síneadh',
'metadata-collapse' => 'Folaigh sonraí síneadh',
'metadata-fields'   => 'Beidh meiteasonraí EXIF atá liosta sa teachtaireacht seo san áireamh ar an leathanach íomhá nuair ata an clár meiteasonraí ceilte.
Beidh na cinn eile ceilte de réir réamhshocraithe.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Leithead',
'exif-imagelength'                 => 'Airde',
'exif-bitspersample'               => 'Gíotáin sa chomhpháirt',
'exif-compression'                 => 'Scéim comhbhrúite',
'exif-photometricinterpretation'   => 'Comhbhrú picteilíní',
'exif-orientation'                 => 'Treoshuíomh',
'exif-samplesperpixel'             => 'Líon na gcomhpháirt',
'exif-planarconfiguration'         => 'Eagar na sonraí',
'exif-ycbcrsubsampling'            => 'Cóimheas foshamplála de Y i gcoinne C',
'exif-ycbcrpositioning'            => 'Suí Y agus C',
'exif-xresolution'                 => 'Taifeach íomhá i dtreo an leithid',
'exif-yresolution'                 => 'Taifeach íomhá i dtreo an airde',
'exif-resolutionunit'              => 'Aonad an taifigh X agus Y',
'exif-stripoffsets'                => 'Suíomh na sonraí íomhá',
'exif-rowsperstrip'                => 'Líon na rónna sa stráice',
'exif-stripbytecounts'             => 'Bearta sa stráice comhbhrúite',
'exif-jpeginterchangeformat'       => 'Aischló don SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Bearta sonraí JPEG',
'exif-transferfunction'            => 'Feidhm aistrithe',
'exif-whitepoint'                  => 'Crómatacht na bpointí bán',
'exif-primarychromaticities'       => 'Crómatachta na bpríomhacht',
'exif-ycbcrcoefficients'           => 'Comhéifeachtaí mhaitrís trasfhoirmithe an dathspáis',
'exif-referenceblackwhite'         => 'Péire luachanna tagartha don dubh is don bán',
'exif-datetime'                    => 'Dáta agus am athrú an chomhaid',
'exif-imagedescription'            => 'Íomhátheideal',
'exif-make'                        => 'Déantóir an ceamara',
'exif-model'                       => 'Déanamh an ceamara',
'exif-software'                    => 'Na bogearraí a úsáideadh',
'exif-artist'                      => 'Údar',
'exif-copyright'                   => 'Úinéir an chóipchirt',
'exif-exifversion'                 => 'Leagan EXIF',
'exif-flashpixversion'             => 'Leagan Flashpix atá á thacú',
'exif-colorspace'                  => 'Dathspás',
'exif-componentsconfiguration'     => 'Ciall le gach giota',
'exif-compressedbitsperpixel'      => 'Modh chomhbhrú na n-íomhánna',
'exif-pixelydimension'             => 'Leithead bailí don íomhá',
'exif-pixelxdimension'             => 'Airde bailí don íomhá',
'exif-makernote'                   => 'Nótaí an déantóra',
'exif-usercomment'                 => 'Nótaí an úsáideora',
'exif-relatedsoundfile'            => 'comhad gaolmhara fuaime',
'exif-datetimeoriginal'            => 'Dáta agus am ghiniúint na sonraí',
'exif-datetimedigitized'           => 'Dáta agus am digitithe',
'exif-subsectime'                  => 'Foshoicindí DateTime',
'exif-subsectimeoriginal'          => 'Foshoicindí DateTimeOriginal',
'exif-subsectimedigitized'         => 'Foshoicindí DateTimeDigitized',
'exif-exposuretime'                => 'Am nochta',
'exif-exposuretime-format'         => '$1 soic ($2)',
'exif-fnumber'                     => 'Uimhir F',
'exif-exposureprogram'             => 'Clár nochta',
'exif-spectralsensitivity'         => 'Íogaireacht an speictrim',
'exif-isospeedratings'             => 'Grádú ISO luais',
'exif-oecf'                        => 'Fachtóir optaileictreonach tiontaithe',
'exif-shutterspeedvalue'           => 'Luas nochta',
'exif-aperturevalue'               => 'Cró',
'exif-brightnessvalue'             => 'Gile',
'exif-exposurebiasvalue'           => 'Laobh nochta',
'exif-maxaperturevalue'            => 'Cró tíre uasmhéideach',
'exif-subjectdistance'             => 'Fad ón ábhar',
'exif-meteringmode'                => 'Modh meadarachta',
'exif-lightsource'                 => 'Foinse solais',
'exif-flash'                       => 'Splanc',
'exif-focallength'                 => 'Fad fócasach an lionsa',
'exif-subjectarea'                 => 'Achar an ábhair',
'exif-flashenergy'                 => 'Splancfhuinneamh',
'exif-spatialfrequencyresponse'    => 'Freagairt minicíochta spáis',
'exif-focalplanexresolution'       => 'Taifeach an plána fócasaigh X',
'exif-focalplaneyresolution'       => 'Taifeach an plána fócasaigh Y',
'exif-focalplaneresolutionunit'    => 'Aonad taifigh an plána fócasaigh',
'exif-subjectlocation'             => 'Suíomh an ábhair',
'exif-exposureindex'               => 'Innéacs nochta',
'exif-sensingmethod'               => 'Modh braite',
'exif-filesource'                  => 'Foinse comhaid',
'exif-scenetype'                   => 'Cineál radhairc',
'exif-cfapattern'                  => 'Patrún CFA',
'exif-customrendered'              => 'Íomháphróiseáil saincheaptha',
'exif-exposuremode'                => 'Modh nochta',
'exif-whitebalance'                => 'Bánchothromaíocht',
'exif-digitalzoomratio'            => 'Cóimheas zúmála digiteaí',
'exif-focallengthin35mmfilm'       => 'Fad fócasach i scannán 35 mm',
'exif-scenecapturetype'            => 'Cineál gabhála radhairc',
'exif-gaincontrol'                 => 'Rialú radhairc',
'exif-contrast'                    => 'Codarsnacht',
'exif-saturation'                  => 'Sáithiú',
'exif-sharpness'                   => 'Géire',
'exif-devicesettingdescription'    => 'Cur síos ar socruithe gléis',
'exif-subjectdistancerange'        => 'Raon fada ón ábhar',
'exif-imageuniqueid'               => 'Aitheantas uathúil an íomhá',
'exif-gpsversionid'                => 'Leagan clibe GPS',
'exif-gpslatituderef'              => 'Domhan-leithead Thuaidh no Theas',
'exif-gpslatitude'                 => 'Domhan-leithead',
'exif-gpslongituderef'             => 'Domhanfhad Thoir nó Thiar',
'exif-gpslongitude'                => 'Domhanfhad',
'exif-gpsaltituderef'              => 'Tagairt airde',
'exif-gpsaltitude'                 => 'Airde',
'exif-gpstimestamp'                => 'Am GPS (clog adamhach)',
'exif-gpssatellites'               => 'Satailítí úsáidte don tomhas',
'exif-gpsstatus'                   => 'Stádas an ghlacadóra',
'exif-gpsmeasuremode'              => 'Modh tomhais',
'exif-gpsdop'                      => 'Beachtas tomhais',
'exif-gpsspeedref'                 => 'Aonad luais',
'exif-gpsspeed'                    => 'Luas an ghlacadóra GPS',
'exif-gpstrackref'                 => 'Tagairt don treo gluaiseachta',
'exif-gpstrack'                    => 'Treo gluaiseachta',
'exif-gpsimgdirectionref'          => 'Tagairt do treo an íomhá',
'exif-gpsimgdirection'             => 'Treo an íomhá',
'exif-gpsmapdatum'                 => 'Sonraí suirbhéireachta geodasaí a úsáideadh',
'exif-gpsdestlatituderef'          => 'Tagairt don domhan-leithead sprice',
'exif-gpsdestlatitude'             => 'Domhan-leithead sprice',
'exif-gpsdestlongituderef'         => 'Tagairt don domhanfhad sprice',
'exif-gpsdestlongitude'            => 'Domhanfhad sprice',
'exif-gpsdestbearingref'           => 'Tagairt don treo-uillinn sprice',
'exif-gpsdestbearing'              => 'Treo-uillinn sprice',
'exif-gpsdestdistanceref'          => 'Tagairt don fad ón áit sprice',
'exif-gpsdestdistance'             => 'Fad ón áit sprice',
'exif-gpsprocessingmethod'         => 'Ainm an modha próiseála GPS',
'exif-gpsareainformation'          => 'Ainm an cheantair GPS',
'exif-gpsdatestamp'                => 'Dáta GPS',
'exif-gpsdifferential'             => 'Ceartú difreálach GPS',

# EXIF attributes
'exif-compression-1' => 'Neamh-chomhbhrúite',

'exif-unknowndate' => 'Dáta anaithnid',

'exif-orientation-1' => 'Gnáth', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Iompaithe go cothrománach', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rothlaithe trí 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Iompaithe go hingearach', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rothlaithe trí 90° CCW agus iompaithe go hingearach', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rothlaithe trí 90° CW', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rothlaithe trí 90° CW agus iompaithe go hingearach', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rothlaithe trí 90° CCW', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Formáid shmutánach',
'exif-planarconfiguration-2' => 'Formáid phlánach',

'exif-componentsconfiguration-0' => 'níl a leithéid ann',

'exif-exposureprogram-0' => 'Gan sainiú',
'exif-exposureprogram-1' => 'Leis na lámha',
'exif-exposureprogram-2' => 'Gnáthchlár',
'exif-exposureprogram-3' => 'Tosaíocht nochta',
'exif-exposureprogram-4' => 'Tosaíocht cró',
'exif-exposureprogram-5' => 'Clár cúise (laofa do doimhneacht réimse)',
'exif-exposureprogram-6' => 'Clár gnímh (laofa do cróluas tapaidh)',
'exif-exposureprogram-7' => 'Modh portráide (do grianghraif i ngar don ábhar,
le cúlra as fócas)',
'exif-exposureprogram-8' => 'Modh tírdhreacha (do grianghraif tírdhreacha le
cúlra i bhfócas)',

'exif-subjectdistance-value' => '$1 méadair',

'exif-meteringmode-0'   => 'Anaithnid',
'exif-meteringmode-1'   => 'Meán',
'exif-meteringmode-2'   => 'MeánUalaitheDonLár',
'exif-meteringmode-3'   => 'Spota',
'exif-meteringmode-4'   => 'Ilspotach',
'exif-meteringmode-5'   => 'Patrún',
'exif-meteringmode-6'   => 'Páirteach',
'exif-meteringmode-255' => 'Eile',

'exif-lightsource-0'   => 'Anaithnid',
'exif-lightsource-1'   => 'Solas lae',
'exif-lightsource-2'   => 'Fluaraiseach',
'exif-lightsource-3'   => 'Tungstan (solas gealbhruthach)',
'exif-lightsource-4'   => 'Splanc',
'exif-lightsource-9'   => 'Aimsir breá',
'exif-lightsource-10'  => 'Aimsir scamallach',
'exif-lightsource-11'  => 'Scáth',
'exif-lightsource-12'  => 'Solas lae fluaraiseach (D 5700 â€“ 7100K)',
'exif-lightsource-13'  => 'Solas bán lae fluaraiseach (N 4600 â€“ 5400K)',
'exif-lightsource-14'  => 'Solas fuar bán fluaraiseach (W 3900 â€“ 4500K)',
'exif-lightsource-15'  => 'Solas bán fluaraiseach (WW 3200 â€“ 3700K)',
'exif-lightsource-17'  => 'Gnáthsholas A',
'exif-lightsource-18'  => 'Gnáthsholas B',
'exif-lightsource-19'  => 'Gnáthsholas C',
'exif-lightsource-24'  => 'Tungstan stiúideó ISO',
'exif-lightsource-255' => 'Foinse eile solais',

'exif-focalplaneresolutionunit-2' => 'orlaigh',

'exif-sensingmethod-1' => 'Gan sainiú',
'exif-sensingmethod-2' => 'Braiteoir aonshliseach ceantair datha',
'exif-sensingmethod-3' => 'Braiteoir dháshliseach ceantair datha',
'exif-sensingmethod-4' => 'Braiteoir tríshliseach ceantair datha',
'exif-sensingmethod-5' => 'Braiteoir dathsheicheamhach ceantair',
'exif-sensingmethod-7' => 'Braiteoir trílíneach',
'exif-sensingmethod-8' => 'Braiteoir dathsheicheamhach línte',

'exif-scenetype-1' => 'Grianghraf a rinneadh go díreach',

'exif-customrendered-0' => 'Gnáthphróiseas',
'exif-customrendered-1' => 'Próiseas saincheaptha',

'exif-exposuremode-0' => 'Nochtadh uathoibríoch',
'exif-exposuremode-1' => 'Nochtadh láimhe',
'exif-exposuremode-2' => 'Brac uathoibríoch',

'exif-whitebalance-0' => 'Bánchothromaíocht uathoibríoch',
'exif-whitebalance-1' => 'Bánchothromaíocht láimhe',

'exif-scenecapturetype-0' => 'Gnáth',
'exif-scenecapturetype-1' => 'Tírdhreach',
'exif-scenecapturetype-2' => 'Portráid',
'exif-scenecapturetype-3' => 'Radharc oíche',

'exif-gaincontrol-0' => 'Dada',
'exif-gaincontrol-1' => 'Íosneartúchán suas',
'exif-gaincontrol-2' => 'Uasneartúchán suas',
'exif-gaincontrol-3' => 'Íosneartúchán síos',
'exif-gaincontrol-4' => 'Uasneartúchán síos',

'exif-contrast-0' => 'Gnáth',
'exif-contrast-1' => 'Bog',
'exif-contrast-2' => 'Crua',

'exif-saturation-0' => 'Gnáth',
'exif-saturation-1' => 'Sáithiúchán íseal',
'exif-saturation-2' => 'Ard-sáithiúchán',

'exif-sharpness-0' => 'Gnáth',
'exif-sharpness-1' => 'Bog',
'exif-sharpness-2' => 'Crua',

'exif-subjectdistancerange-0' => 'Anaithnid',
'exif-subjectdistancerange-1' => 'Macra',
'exif-subjectdistancerange-2' => 'Radharc teann',
'exif-subjectdistancerange-3' => 'Cianradharc',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Domhan-leithead thuaidh',
'exif-gpslatitude-s' => 'Domhan-leithead theas',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Domhanfhad Thoir',
'exif-gpslongitude-w' => 'Domhanfhad Thiar',

'exif-gpsstatus-a' => 'Tomhas ar siúl',
'exif-gpsstatus-v' => 'Tomhas dodhéanta',

'exif-gpsmeasuremode-2' => 'Tomhas déthoiseach',
'exif-gpsmeasuremode-3' => 'Tomhas tríthoiseach',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Ciliméadair san uair',
'exif-gpsspeed-m' => 'Mílte san uair',
'exif-gpsspeed-n' => 'Muirmhílte',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Fíorthreo',
'exif-gpsdirection-m' => 'Treo maighnéadach',

# External editor support
'edit-externally'      => 'Athraigh an comhad seo le feidhmchlár seachtrach',
'edit-externally-help' => '(Féach ar na [http://www.mediawiki.org/wiki/Manual:External_editors treoracha cumraíochta] as Béarla le tuilleadh eolais)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'iad uile',
'imagelistall'     => 'iad uile',
'watchlistall2'    => 'an t-iomlán',
'namespacesall'    => 'iad uile',
'monthsall'        => 'gach mí',

# E-mail address confirmation
'confirmemail'            => 'Deimhnigh do ríomhsheoladh',
'confirmemail_text'       => 'Tá sé de dhíth an an vicí seo do ríomhsheoladh a bhailíochtú sula n-úsáideann tú na gnéithe ríomhphoist. Brúigh an cnaipe seo thíos chun teachtaireacht deimhnithe a sheoladh chuig do chuntas ríomhphoist. Beidh nasc ann sa chomhad ina mbeidh cód áirithe; lódáil an nasc i do bhrabhsálaí chun deimhniú go bhfuil do ríomhsheoladh bailí.',
'confirmemail_send'       => 'Seol cód deimhnithe',
'confirmemail_sent'       => 'Teachtaireacht deimhnithe seolta chugat.',
'confirmemail_sendfailed' => "Ní féidir {{SITENAME}} do theachtaireacht deimhnithe a sheoladh. 
Féach an bhfuil carachtair neamh-bhailí ann sa seoladh.

D'fhreagair an clár ríomhphoist: $1",
'confirmemail_invalid'    => "Cód deimhnithe neamh-bhailí. B'fhéidir gur chuaidh an cód as feidhm.",
'confirmemail_success'    => 'Deimhníodh do ríomhsheoladh. Is féidir leat logáil isteach anois agus sult a bhaint as an vicí!',
'confirmemail_loggedin'   => 'Deimhníodh do sheoladh ríomhphoist.',
'confirmemail_error'      => 'Tharlaigh botún éigin le sabháil do dheimhniú.',
'confirmemail_subject'    => 'Deimhniú do ríomhsheoladh ar an {{SITENAME}}',
'confirmemail_body'       => 'Chláraigh duine éigin (tusa is dócha) an cuntas "$2" ar {{SITENAME}}
agus rinneadh é seo ón seoladh IP $1, ag úsáid an ríomhsheolta seo.

Chun deimhniú gur leatsa an cuntas seo, agus chun gnéithe ríomhphoist 
a chur i ngníomh ag {{SITENAME}}, oscail an nasc seo i do bhrabhsálaí:

$3

<nowiki>*</nowiki>Mura* tusa a chláraigh an cuntas, lean an nasc seo chun 
deimhniú an ríomhsheolta a chur ar cheal:

$5

Rachaidh an cód deimhnithe seo as feidhm ag $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Díchumasaíodh trasáireamh idir vicíonna]',
'scarytranscludefailed'   => '[Theip leis an iarradh teimpléid do $1]',
'scarytranscludetoolong'  => '[Tá an URL ró-fhada]',

# Trackbacks
'trackbackremove' => ' ([$1 Scrios])',

# Delete conflict
'deletedwhileediting' => "'''Aire''': scriosadh an leathanach seo nuair a bhí tu ag athrú é!",
'recreate'            => 'Athchruthaigh',

# action=purge
'confirm_purge_button' => 'Tá',
'confirm-purge-top'    => 'An bhfuil tú cinnte go dteastaíonn uait taisce an leathanaigh seo a bhánú?',

# Multipage image navigation
'imgmultipageprev' => "'← leathanach roimhe sin",
'imgmultipagenext' => 'leathanach a leanas →',
'imgmultigoto'     => 'Téigh go leathanach $1',

# Table pager
'table_pager_next'  => 'Leathanach a leanas',
'table_pager_prev'  => 'Leathanach roimhe',
'table_pager_first' => 'Céad leathanach',
'table_pager_last'  => 'Deireadh leathanach',
'table_pager_empty' => 'Folamh',

# Auto-summaries
'autoredircomment' => 'Ag athdhíriú go [[$1]]',
'autosumm-new'     => 'Leathanach nua: $1',

# Live preview
'livepreview-loading' => 'Ag lódáil…',
'livepreview-ready'   => 'Ag lódáil… Réidh!',

# Watchlist editor
'watchlistedit-numitems'      => 'Tá {{PLURAL:$1|teideal amháin|$1 teideail}} i do liosta faire, gan leathanaigh phlé a chur san áireamh.',
'watchlistedit-noitems'       => 'Níl aon teideail ar do liosta faire.',
'watchlistedit-normal-title'  => 'Athraigh do liosta faire',
'watchlistedit-normal-legend' => 'Bain teideail as do liosta faire',
'watchlistedit-normal-submit' => 'Bain Teideail as',
'watchlistedit-raw-title'     => 'Athraigh do amhliosta faire',
'watchlistedit-raw-legend'    => 'Athraigh do amhliosta faire',
'watchlistedit-raw-titles'    => 'Teideail:',
'watchlistedit-raw-submit'    => 'Nuashonraigh do liosta faire',
'watchlistedit-raw-done'      => 'Tá do liosta faire nuashonraithe.',

# Watchlist editing tools
'watchlisttools-view' => 'Féach ar na hathruithe ábhartha',
'watchlisttools-edit' => 'Féach ar do liosta faire ná cuir in eagar é',
'watchlisttools-raw'  => 'Cuir do amhliosta faire in eagar',

# Special:Version
'version'                  => 'Leagan', # Not used as normal message but as header for the special page itself
'version-other'            => 'Eile',
'version-version'          => 'Leagan',
'version-license'          => 'Ceadúnas',
'version-software'         => 'Bogearraí suiteáilte',
'version-software-version' => 'Leagan',

# Special:FilePath
'filepath'        => 'Cosán comhaid',
'filepath-page'   => 'Comhad:',
'filepath-submit' => 'Cosán',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Ainm comhaid:',
'fileduplicatesearch-submit'   => 'Cuardaigh',
'fileduplicatesearch-info'     => '$1 × $2 picteillín<br />Méid comhad: $3<br />Saghas MIME: $4',

# Special:SpecialPages
'specialpages'                 => 'Leathanaigh speisialta',
'specialpages-group-other'     => 'Leathanaigh speisialta eile',
'specialpages-group-login'     => 'Logáil isteach / cruthaigh cuntas',
'specialpages-group-changes'   => 'Athruithe is déanaí agus logaí',
'specialpages-group-users'     => 'Úsáideoirí agus cearta',
'specialpages-group-pages'     => 'Liosta leathanaigh',
'specialpages-group-pagetools' => 'Uirslí leathanach',
'specialpages-group-wiki'      => 'Sonraí vicí agus uirslí',
'specialpages-group-spam'      => 'Uirlisí turscar',

# Special:BlankPage
'blankpage' => 'Leathanach bán',

);
