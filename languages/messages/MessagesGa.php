<?php
/** Irish (Gaeilge)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alison
 * @author D.de.loinsigh
 * @author Evertype
 * @author Kwekubo
 * @author Moilleadóir
 * @author Moydow
 * @author Spacebirdy
 * @author Stifle
 * @author Tameamseo
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$magicWords = array(
	'redirect'              => array( '0', '#athsheoladh', '#REDIRECT' ),
	'notoc'                 => array( '0', '__GANCÁ__', '__NOTOC__' ),
	'forcetoc'              => array( '0', '__CÁGACHUAIR__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__CÁ__', '__TOC__' ),
	'noeditsection'         => array( '0', '__GANMHÍRATHRÚ__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'MÍLÁITHREACH', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'AINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'   => array( '1', 'GINAINMNAMÍOSALÁITHREAÍ', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'    => array( '1', 'GIORRÚNAMÍOSALÁITHREAÍ', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'LÁLÁITHREACH', 'CURRENTDAY' ),
	'currentdayname'        => array( '1', 'AINMANLAELÁITHRIGH', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'BLIAINLÁITHREACH', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'AMLÁITHREACH', 'CURRENTTIME' ),
	'numberofarticles'      => array( '1', 'LÍONNANALT', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'LÍONNAGCOMHAD', 'NUMBEROFFILES' ),
	'pagename'              => array( '1', 'AINMANLGH', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'AINMANLGHB', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'AINMSPÁS', 'NAMESPACE' ),
	'msg'                   => array( '0', 'TCHT:', 'MSG:' ),
	'subst'                 => array( '0', 'IONAD:', 'SUBST:' ),
	'msgnw'                 => array( '0', 'TCHTFS:', 'MSGNW:' ),
	'img_thumbnail'         => array( '1', 'mionsamhail', 'mion', 'thumbnail', 'thumb' ),
	'img_right'             => array( '1', 'deas', 'right' ),
	'img_left'              => array( '1', 'clé', 'left' ),
	'img_none'              => array( '1', 'faic', 'none' ),
	'img_center'            => array( '1', 'lár', 'center', 'centre' ),
	'img_framed'            => array( '1', 'fráma', 'frámaithe', 'framed', 'enframed', 'frame' ),
	'int'                   => array( '0', 'INMH:', 'INT:' ),
	'sitename'              => array( '1', 'AINMANTSUÍMH', 'SITENAME' ),
	'ns'                    => array( '0', 'AS:', 'NS:' ),
	'localurl'              => array( '0', 'URLÁITIÚIL', 'LOCALURL:' ),
	'localurle'             => array( '0', 'URLÁITIÚILB', 'LOCALURLE:' ),
	'server'                => array( '0', 'FREASTALAÍ', 'SERVER' ),
	'servername'            => array( '0', 'AINMANFHREASTALAÍ', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'SCRIPTCHOSÁN', 'SCRIPTPATH' ),
	'grammar'               => array( '0', 'GRAMADACH:', 'GRAMMAR:' ),
	'notitleconvert'        => array( '0', '__GANTIONTÚNADTEIDEAL__', '__GANTT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__GANTIONTÚNANÁBHAIR__', '__GANTA__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'SEACHTAINLÁITHREACH', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'LÁLÁITHREACHNAS', 'CURRENTDOW' ),
	'revisionid'            => array( '1', 'IDANLEASAITHE', 'REVISIONID' ),
);

$namespaceNames = array(
	NS_MEDIA            => 'Meán',
	NS_SPECIAL          => 'Speisialta',
	NS_TALK             => 'Plé',
	NS_USER             => 'Úsáideoir',
	NS_USER_TALK        => 'Plé_úsáideora',
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
	NS_CATEGORY_TALK    => 'Plé_catagóire',
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
'tog-hideminor'               => 'Ná taispeáin mionathruithe i measc na n-athruithe is déanaí',
'tog-hidepatrolled'           => 'Folaigh giotaí eagartha smachtaithe sna athruithe is déanaí',
'tog-newpageshidepatrolled'   => 'Folaigh leathanaigh smachtaithe ó liosta leathanaigh úire',
'tog-extendwatchlist'         => 'Leathnaigh an liosta faire chun gach athrú cuí a thaispeáint',
'tog-usenewrc'                => 'Stíl nua do na hathruithe is déanaí (JavaScript riachtanach)',
'tog-numberheadings'          => 'Uimhrigh ceannteidil go huathoibríoch',
'tog-showtoolbar'             => 'Taispeáin an barra uirlisí eagair (JavaScript)',
'tog-editondblclick'          => 'Déghliogáil chun leathanaigh a chur in eagar (JavaScript)',
'tog-editsection'             => 'Cumasaigh mír-eagarthóireacht le naisc mar seo: [athrú]',
'tog-editsectiononrightclick' => 'Cumasaigh mír-eagarthóireacht le deaschliceáil<br /> ar ceannteidil (JavaScript)',
'tog-showtoc'                 => "Taispeáin an clár ábhair (d'ailt le níos mó ná 3 ceannteidil)",
'tog-rememberpassword'        => "Cuimhnigh ar m'fhocal faire ar an ríomhaire seo (ar feadh uastréimhse de $1 {{PLURAL:$1|lá|lá}})",
'tog-watchcreations'          => 'Cuir ar mo liosta faire leathanaigh a chruthaím',
'tog-watchdefault'            => 'Déan faire ar leathanaigh a athraím',
'tog-watchmoves'              => 'Cuir ar mo liosta faire leathanaigh a athainmnaím',
'tog-watchdeletion'           => 'Cuir ar mo liosta faire leathanaigh a scriosaim',
'tog-minordefault'            => 'Déan mionathruithe de gach aon athrú, mar réamhshocrú',
'tog-previewontop'            => 'Cuir an réamhamharc os cionn an bhosca eagair, <br />agus ná cuir é taobh thíos de',
'tog-previewonfirst'          => 'Taispeáin réamhamharc don chéad athrú',
'tog-nocache'                 => 'Ciorraigh taisce na leathanach',
'tog-enotifwatchlistpages'    => 'Cuir ríomhphost chugam nuair a athraítear leathanaigh',
'tog-enotifusertalkpages'     => 'Cuir ríomhphost chugam nuair a athraítear mo leathanach phlé úsáideora',
'tog-enotifminoredits'        => 'Cuir ríomhphost chugam nuair a dhéantar mionathruithe chomh maith',
'tog-enotifrevealaddr'        => 'Taispeáin mo sheoladh ríomhphoist i dteachtaireachtaí fógra',
'tog-shownumberswatching'     => 'Taispeán an méid úsáideoirí atá ag faire',
'tog-fancysig'                => 'Sínithe bunúsacha mar vicítéacs (gan nasc uathoibríoch)',
'tog-externaleditor'          => 'Bain úsáid as eagarthóir seachtrach, mar réamhshocrú',
'tog-externaldiff'            => 'Bain úsáid as difríocht sheachtrach, mar réamhshocrú',
'tog-showjumplinks'           => 'Cumasaigh naisc insroichteachta “léim go dtí”',
'tog-uselivepreview'          => 'Bain úsáid as réamhamharc beo (JavaScript) (Turgnamhach)',
'tog-forceeditsummary'        => 'Cuir in iúl dom nuair a chuirim isteach achoimre eagair folamh',
'tog-watchlisthideown'        => 'Folaigh mo chuid athruithe ón liosta faire',
'tog-watchlisthidebots'       => 'Folaigh athruithe de chuid róbat ón liosta faire',
'tog-watchlisthideminor'      => 'Folaigh mionathruithe ón liosta faire',
'tog-watchlisthideliu'        => 'Folaigh athruithe ó úsáideoirí logáilte isteach ón liosta faire',
'tog-watchlisthideanons'      => 'Folaigh athruithe ó úsáideoirí gan ainm ón liosta faire',
'tog-watchlisthidepatrolled'  => 'Folaigh athruithe patrólta ón liosta faire',
'tog-ccmeonemails'            => 'Cuir cóip chugam de gach teactaireacht r-phoist a chuirim chuig úsáideoirí eile',
'tog-diffonly'                => 'Ná taispeáin inneachar an leathanaigh faoi difríochteanna',
'tog-showhiddencats'          => 'Taispeáin chatagóirí folaithe',
'tog-norollbackdiff'          => 'Fág an difr ar lár tar éis athruithe a rolladh siar',

'underline-always'  => 'Ar siúl i gcónaí',
'underline-never'   => 'Múchta',
'underline-default' => 'Mar atá réamhshocraithe sa bhrabhsálaí',

# Font style option in Special:Preferences
'editfont-sansserif' => 'Cló gan trasmhíreanna',
'editfont-serif'     => 'Cló le trasmhíreanna',

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
'hidden-category-category'       => 'Catagóirí folaithe',
'category-subcat-count'          => '{{PLURAL:$2| Níl ach an fo-chatagóir seo a leanas ag an gcatagóir seo.|Tá {{PLURAL:$1|fo-chatagóir|fo-chatagóirí}} ag an gcatagóir seo, as $2 san iomlán.}}',
'category-subcat-count-limited'  => 'Is {{PLURAL:$1|é an líon fochatagóir|$1 iad na líon fochatagóirí}} atá ag an gcatagóir seo ná: $1.',
'category-article-count'         => '{{PLURAL:$2|Níl sa chatagóir seo ach an leathanach seo a leanas.|Tá {{PLURAL:$1|$1 leathanach|$1 leathanaigh}} sa chatagóir seo, as iomlán de $2.}}',
'category-article-count-limited' => 'Tá {{PLURAL:$1|an leathanach|na $1 leathanaigh}} seo a leanas sa chatagóir reatha.',
'category-file-count'            => '{{PLURAL:$2|Tá ach an comhad a leanas sa chatagóir seo|Tá {{PLURAL:$1|an comhad seo|$1 na comhaid seo}} a leanas sa chatagóir seo, as $2 san iomlán.}}',
'category-file-count-limited'    => 'Tá {{PLURAL:$1|an comhad seo|$1 na comhaid seo}} a leanas sa chatagóir reatha.',
'listingcontinuesabbrev'         => 'ar lean.',

'mainpagetext'      => "'''D'éirigh le suiteáil MediaWiki.'''",
'mainpagedocfooter' => 'Féach ar [http://meta.wikimedia.org/wiki/MediaWiki_localisation doiciméid um conas an chomhéadán a athrú]
agus an [http://meta.wikimedia.org/wiki/MediaWiki_User%27s_Guide Lámhleabhar úsáideora] chun cabhair úsáide agus fíoraíochta a fháil.',

'about'         => 'Maidir leis',
'article'       => 'Leathanach ábhair',
'newwindow'     => '(a osclófar i bhfuinneog nua)',
'cancel'        => 'Cealaigh',
'moredotdotdot' => 'Tuilleadh...',
'mypage'        => 'Mo leathanach',
'mytalk'        => 'Mo chuid phlé',
'anontalk'      => 'Plé don seoladh IP seo',
'navigation'    => 'Nascleanúint',
'and'           => '&#32;agus',

# Cologne Blue skin
'qbfind'         => 'Aimsigh',
'qbbrowse'       => 'Brabhsáil',
'qbedit'         => 'Cuir in eagar',
'qbpageoptions'  => 'An leathanach seo',
'qbpageinfo'     => 'Comhthéacs',
'qbmyoptions'    => 'Mo chuid leathanaigh',
'qbspecialpages' => 'Leathanaigh speisialta',
'faq'            => 'Ceisteanna Coiteanta',
'faqpage'        => 'Project:Ceisteanna_Coiteanta',

# Vector skin
'vector-action-delete'  => 'Scrios',
'vector-action-move'    => 'Athainmnigh',
'vector-action-protect' => 'Glasáil',
'vector-view-create'    => 'Cruthaigh',
'vector-view-edit'      => 'Athraigh an lch seo',
'vector-view-view'      => 'Léigh',

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
'postcomment'       => 'Alt nua',
'articlepage'       => 'Féach ar an alt',
'talk'              => 'Plé',
'views'             => 'Radhairc',
'toolbox'           => 'Bosca uirlisí',
'userpage'          => 'Féach ar lch úsáideora',
'projectpage'       => 'Féach ar lch thionscadail',
'imagepage'         => 'Féach ar lch comhaid',
'mediawikipage'     => 'Féach ar lch teachtaireacht',
'templatepage'      => 'Féach ar leathanach an teimpléad',
'viewhelppage'      => 'Féach ar lch chabhair',
'categorypage'      => 'Féach ar lch chatagóir',
'viewtalkpage'      => 'Féach ar phlé',
'otherlanguages'    => 'I dteangacha eile',
'redirectedfrom'    => '(Athsheolta ó $1)',
'redirectpagesub'   => 'Lch athdhírithe',
'lastmodifiedat'    => 'Athraíodh an leathanach seo ag $2, $1.',
'viewcount'         => 'Rochtainíodh an leathanach seo {{PLURAL:$1|uair amháin|$1 uaire}}.',
'protectedpage'     => 'Leathanach glasáilte',
'jumpto'            => 'Léim go:',
'jumptonavigation'  => 'nascleanúint',
'jumptosearch'      => 'cuardaigh',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Maidir leis an {{SITENAME}}',
'aboutpage'            => 'Project:Maidir leis',
'copyright'            => 'Tá an t-ábhar le fáil faoin $1.',
'copyrightpage'        => '{{ns:project}}:Cóipchearta',
'currentevents'        => 'Cúrsaí reatha',
'currentevents-url'    => 'Project:Cúrsaí reatha',
'disclaimers'          => 'Séanadh',
'disclaimerpage'       => 'Project:Séanadh_ginearálta',
'edithelp'             => 'Cabhair eagarthóireachta',
'edithelppage'         => 'Help:Eagarthóireacht',
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
'red-link-title'          => '$1 (níl an leathanach ann)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Alt',
'nstab-user'      => 'Lch úsáideora',
'nstab-media'     => 'Lch meáin',
'nstab-special'   => 'Leathanach speisialta',
'nstab-project'   => 'Tionscadal',
'nstab-image'     => 'Comhad',
'nstab-mediawiki' => 'Teachtaireacht',
'nstab-template'  => 'Teimpléad',
'nstab-help'      => 'Cabhair',
'nstab-category'  => 'Catagóir',

# Main script and global functions
'nosuchaction'      => 'Níl a leithéid de ghníomh ann',
'nosuchactiontext'  => 'Níl aithníonn an vicí an gníomh atá ann san URL.
An ndearna tú botún san URL, no ar lean tú nasc mícheart?
An bhfuil fadhb sna bogearraí atá in usáid ar {{SITENAME}}?',
'nosuchspecialpage' => 'Níl a leithéid de leathanach speisialta ann',
'nospecialpagetext' => '<strong>Tá tú tar éis iarradh ar leathanach speisialta neamhbhailí.</strong>

Tá liosta de leathanaigh speisialta bhailí ar fáil ag [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Earráid',
'databaseerror'        => 'Earráid sa bhunachar sonraí',
'dberrortext'          => 'Tharla earráid chomhréire in iarratas chuig an mbunachar sonraí.
B\'fhéidir gur fabht sa bhogearraí é seo.
Seo é an t-iarratas deireanach chuig an mbunachar sonrai:
<blockquote><tt>$1</tt></blockquote>
ón bhfeidhm "<tt>$2</tt>".
Thug MySQL an earráid seo: "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Tharla earráid chomhréire in iarratas chuig an bhunachar sonraí.
"$1",
ón bhfeidhm "$2",
ab ea an t-iarratas deireanach chuig an mbunachar sonrai.
Thug MySQL an earráid seo: "$3: $4".',
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
'fileexistserror'      => 'Unable to write to file  file exists
Ní-abálta scríobh chuif comhad "$1": is ann don chomhad',
'unexpected'           => 'Luach gan súil leis: "$1"="$2".',
'formerror'            => 'Earráid: ní féidir an foirm a tabhair isteach',
'badarticleerror'      => 'Ní féidir an gníomh seo a dhéanamh ar an leathanach seo.',
'cannotdelete'         => 'Ní féidir an leathanach nó comhad "$1" a scriosadh.
B\'fhéidir gur scrios duine eile é cheana féin.',
'badtitle'             => 'Teideal neamhbhailí',
'badtitletext'         => "Bhí teideal an leathanaigh a d'iarr tú ar neamhbhailí, folamh, nó
teideal idirtheangach nó idirvicí nasctha go mícheart.",
'perfcached'           => 'Fuarthas na sonraí a leanas as taisce, agus is dócha go bhfuil siad as dáta.',
'wrong_wfQuery_params' => 'Paraiméadair mhíchearta don wfQuery()<br />
Feidhm: $1<br />
Iarratas: $2',
'viewsource'           => 'Féach ar fhoinse',
'viewsourcefor'        => 'le haghaidh $1',
'actionthrottled'      => 'Gníomh scóigthe',
'actionthrottledtext'  => 'Mar theicníc frithurscair, ní féidir lear an gníomh seo a dhéanamh barraíocht taobh istigh de thréimhse ghairid ama, agus tá an méid sáraithe agat.
Bain trial arís as i gcionn cúpla bomaite más é do thoil é.',
'protectedpagetext'    => 'Tá an leathanach seo glasáilte chun coisc ar eagarthóireacht.',
'viewsourcetext'       => 'Is féidir foinse an leathanach seo a fheiceáil ná a cóipeáil:',
'editinginterface'     => "'''Rabhadh:''' Tá tú ag athrú leathanaigh a bhfuil téacs comhéadain do na bogearraí air. Cuirfear athruithe ar an leathanach seo i bhfeidhm ar an gcomhéadan úsáideora.
Más maith leat MediaWiki a aistriú, cuimhnigh ar [http://translatewiki.net/wiki/Main_Page?setlang=ga translatewiki.net] (tionscadal logánaithe MediaWiki) a úsáid.",
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
'logouttext'                 => "'''Tá tú logáilte amach anois.'''

Is féidir leat an {{SITENAME}} a úsáid fós gan ainm, nó is féidir leat [[Special:UserLogin|logáil isteach arís]] mar an úsáideoir céanna, nó mar úsáideoir eile.
Tabhair faoi deara go taispeáinfear roinnt leathanaigh mar atá tú logáilte isteach fós, go dtí go ghlanfá amach do taisce líonleitheora.",
'welcomecreation'            => '== Tá fáilte romhat, $1! ==

Cruthaíodh do chuntas. Ná déan dearmad athrú a dhéanamh ar do chuid [[Special:Preferences|sainroghanna {{GRAMMAR:genitive|{{SITENAME}}}}]].',
'yourname'                   => "D'ainm úsáideora",
'yourpassword'               => "D'fhocal faire",
'yourpasswordagain'          => "Athiontráil d'fhocal faire",
'remembermypassword'         => "Cuimhnigh ar m'fhocal faire ar an ríomhaire seo (ar feadh uastréimhse de $1 {{PLURAL:$1|lá|lá}})",
'yourdomainname'             => "D'fhearann",
'externaldberror'            => 'Bhí earráid bhunachair sonraí ann maidir le fíordheimhniú seachtrach, nóThere was either an external authentication database error or you are not allowed to update your external account.',
'login'                      => 'Logáil isteach',
'nav-login-createaccount'    => 'Logáil isteach',
'loginprompt'                => 'Tá sé riachtanach fianáin a chur i ngníomh chun logáil isteach a dhéanamh ag {{SITENAME}}.',
'userlogin'                  => 'Logáil isteach / cruthaigh cuntas',
'userloginnocreate'          => 'Logáil isteach',
'logout'                     => 'Logáil amach',
'userlogout'                 => 'Logáil amach',
'notloggedin'                => 'Níl tú logáilte isteach',
'nologin'                    => "Nach bhfuil logáil isteach agat? '''$1'''.",
'nologinlink'                => 'Cruthaigh cuntas',
'createaccount'              => 'Cruthaigh cuntas nua',
'gotaccount'                 => "An bhfuil cuntas agat cheana féin? '''$1'''.",
'gotaccountlink'             => 'Logáil isteach',
'createaccountmail'          => 'le ríomhphost',
'badretype'                  => "D'iontráil tú dhá fhocal faire difriúla.",
'userexists'                 => 'Tá an ainm úsáideora sin in úsáid cheana féin.<br />
Roghnaigh ainm eile agus bain triail eile as.',
'loginerror'                 => 'Earráid leis an logáil isteach',
'createaccounterror'         => 'Theip ar an cuntas a chruthú: $1',
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
Tá ainmneacha úsáideoir cásíogair.
Cinntigh do litriú, nó [[Special:UserLogin/signup|bain úsáid as an foirm thíos]] chun cuntas úsáideora nua a chruthú.',
'nosuchusershort'            => 'Níl aon úsáideoir ann leis an ainm "<nowiki>$1</nowiki>". Cinntigh do litriú.',
'nouserspecified'            => 'Caithfidh ainm úsáideoir a shonrú.',
'wrongpassword'              => "D'iontráil tú focal faire mícheart.<br />
Bain triail eile as.",
'wrongpasswordempty'         => 'Níor iontráil tú focal faire. Bain triail eile as.',
'passwordtooshort'           => 'Is riachtanach go bhfuil {{PLURAL:$1|carachtar amháin|$1 carachtair}} ann ar a laghad i bhfocal faire.',
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
'emailauthenticated'         => 'Fíordheimhníodh do sheoladh ríomhphoist ar an $2 ar $3.',
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
'resetpass'                 => "Athraigh d'fhocal faire",
'resetpass_announce'        => "Tá tú logáilte isteach le cód sealadach a seoladh chugat i r-phost.
Chun d'iarratas logáil isteach a chríochnú, caithfidh tú focal faire nua a roghnú anseo:",
'resetpass_text'            => '<!-- Cur téacs anseo -->',
'resetpass_header'          => 'Athshocraigh pasfhocail chuntais',
'oldpassword'               => 'Focal faire reatha:',
'newpassword'               => 'Focal faire nua:',
'retypenew'                 => 'Athiontráil an focal nua faire:',
'resetpass_submit'          => 'Roghnaigh focal faire agus logáil isteach',
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
'media_sample'    => 'Sámpla.ogg',
'media_tip'       => 'Nasc do chomhad meáin',
'sig_tip'         => 'Do shíniú le stampa ama',
'hr_tip'          => 'Líne cothrománach (inúsáidte go coigilteach)',

# Edit pages
'summary'                          => 'Achoimriú:',
'subject'                          => 'Ábhar/ceannlíne:',
'minoredit'                        => 'Mionathrú é seo',
'watchthis'                        => 'Déan faire ar an lch seo',
'savearticle'                      => 'Sábháil an lch',
'preview'                          => 'Réamhamharc',
'showpreview'                      => 'Taispeáin réamhamharc',
'showlivepreview'                  => 'Réamhamharc beo',
'showdiff'                         => 'Taispeáin athruithe',
'anoneditwarning'                  => "'''Rabhadh:''' Níl tú logáilte isteach. Cuirfear do sheoladh IP i stair eagarthóireachta an leathanaigh seo.",
'missingsummary'                   => "'''Cuimhneachán:''' Níor thug tú achoimriú don athrú. Má chliceáileann tú Sábháil arís, sábhálfar an t-athrú gan é a hachoimriú.",
'missingcommenttext'               => 'Cuir nóta tráchta isteach faoi seo, le do thoil.',
'summary-preview'                  => 'Réamhamharc an achoimriú:',
'blockedtitle'                     => 'Tá an úsáideoir seo faoi chosc',
'blockedtext'                      => '\'\'\'Chuir $1 cosc ar d’ainm úsáideora nó ar do sheoladh IP.\'\'\'

Is í seo an chúis a thugadh:<br />\'\'$2\'\'.<p>Is féidir leat teagmháil a dhéanamh le $1 nó le duine eile de na [[{{MediaWiki:Grouppage-sysop}}|riarthóirí]] chun an cosc a phlé.

* Tús an choisc: $8
* Dul as feidhm: $6
* Sprioc an choisc: $7
<br />
Tabhair faoi deara nach féidir leat an gné "cuir ríomhphost chuig an úsáideoir seo" a úsáid mura bhfuil seoladh ríomhphoist bailí cláraithe i do [[Special:Preferences|shocruithe úsáideora]].

Is é $3 do sheoladh IP agus #$5 do ID coisc. Déan tagairt don seoladh seo le gach ceist a chuirfeá.',
'blockednoreason'                  => 'níl chúis a thugadh',
'blockedoriginalsource'            => "Tá an foinse '''$1''' le feiceáil a leanas:",
'whitelistedittitle'               => 'Logáil isteach chun athrú a dhéanamh',
'whitelistedittext'                => 'Ní mór duit $1 chun ailt a athrú.',
'nosuchsectiontitle'               => 'Níl a leithéad de shliocht tofa ann',
'loginreqtitle'                    => 'Tá logáil isteach de dhíth ort',
'loginreqlink'                     => 'logáil isteach',
'loginreqpagetext'                 => 'Caithfidh tú $1 chun leathanaigh a amharc.',
'accmailtitle'                     => 'Seoladh an focal faire.',
'accmailtext'                      => "Seoladh focal faire don úsáideoir '$1' go dtí '$2'.",
'newarticle'                       => '(Nua)',
'newarticletext'                   => "Lean tú nasc chuig leathanach nach bhfuil ann fós.
Chun an leathanach a chruthú, tosaigh ag clóscríobh sa bhosca thíos
(féach ar an [[{{MediaWiki:Helppage}}|leathanach cabhrach]] chun a thuilleadh eolais a fháil).
Má tháinig tú anseo as dearmad, brúigh an cnaipe '''ar ais''' ar do bhrabhsálaí.",
'anontalkpagetext'                 => "---- ''Leathanach plé é seo a bhaineann le húsáideoir gan ainm nár chruthaigh cuntas fós, nó nach bhfuil ag úsáid an chuntais. Dá bhrí sin, caithfimid an seoladh IP a úsáid chun é/í a (h)ionannú. Is féidir le níos mó ná úsáideoir amháin an seoladh IP céanna a úsáid. Má tá tú i d'úsáideoir gan ainm agus má cheapann tú go bhfuil teachtaireachtaí nach mbaineann leat seolta chugat, [[Special:UserLogin|cruthaigh cuntas]] nó [[Special:UserLogin|logáil isteach]] chun mearbhall le húsáideoirí eile gan ainmneacha a éalú amach anseo.''",
'noarticletext'                    => 'Níl aon téacs ar an leathanach seo faoi láthair.
Is féidir [[Special:Search/{{PAGENAME}}|cuardach a dhéanamh le haghaidh an teidil seo]] i leathanaigh eile, nó <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} cuardach a dhéanamh sna logaí gaolmhara],
nó [{{fullurl:{{FULLPAGENAME}}|action=edit}} an leathanach seo a chur in eagar].</span>',
'clearyourcache'                   => "'''Tugtar faoi deara:''' Tar éis duit athruithe a shábháil, caithfear gabháil thar thaisce do bhrabhsálaí chun iad a fheiceáil. '''Internet Explorer:''' cliceáil ar an gcnaipe ''Athnuaigh'' nó ''Athlódáil'', agus an eochair ''Ctrl'' á bhrú agat. '''Firefox:''' cliceáil ar ''Athlódáil'', agus an eochair ''Iomlaoid'' á bhrú agat (nó brúigh ''Ctrl-Iomlaoid-R''). '''Opera:''' caithfear d'úsáideoirí a dtaiscí a ghlanadh trí ''Uirlisí→Sainroghanna''. Ní mór d'úsáideoirí '''Konqueror''' nó '''Safari''' ach cliceáil ar an gcnaipe ''Athlódáil''.",
'usercssyoucanpreview'             => "'''Leid:''' Sula sábhálaím tú, úsáid an cnaipe
'Réamhamharc' chun do CSS nua a tástáil.",
'userjsyoucanpreview'              => "'''Leid:''' Sula sábhálaím tú, úsáid an cnaipe
'Réamhamharc' chun do JS nua a tástáil.",
'usercsspreview'                   => "'''Cuimhnigh nach bhfuil seo ach réamhamharc do CSS úsáideora -
níor sábháladh é go fóill!'''",
'userjspreview'                    => "'''Cuimhnigh nach bhfuil seo ach réamhamharc do JavaScript úsáideora
- níor sábháladh é go fóill!'''",
'userinvalidcssjstitle'            => "'''Rabhadh:''' Níl an craiceann \"\$1\" ann.
Cuimhnigh go n-úsáideann leathanaigh shaincheaptha .css agus .js teideal i gcás íochtar, m.sh. {{ns:user}}:Foo/monobook.css i leapa {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Leasaithe)',
'note'                             => "'''Tabhair faoi deara:'''",
'previewnote'                      => "'''Cuimhnigh nach bhfuil ach réamhamharc sa leathanach seo, agus nach sábháladh fós é!'''",
'previewconflict'                  => 'San réamhamharc seo, feachann tú an téacs dé réir an eagarbhosca
thuas mar a taispeáinfear é má sábháilfear é.',
'editing'                          => 'Ag athrú $1',
'editingsection'                   => 'Ag athrú $1 (mir)',
'editingcomment'                   => 'Ag athrú $1 (tuairisc nua)',
'editconflict'                     => 'Coimhlint athraithe: $1',
'explainconflict'                  => "D'athraigh duine eile an leathanach seo ó thosaigh tú ar a athrú.
Tá téacs an leathanaigh mar atá sé faoi láthair le feiceáil thuas.
Tá do chuid athruithe le feiceáil thíos.
Caithfidh tú do chuid athruithe a chumasc leis an leagan reatha.
Ní shábhálfar '''ach''' an téacs thuas nuair a bhrúnn tú ar an gcnaipe \"{{int:savearticle}}\".",
'yourtext'                         => 'Do chuid téacs',
'storedversion'                    => 'Eagrán sábháilte',
'editingold'                       => "'''Rabhadh: Tá tú ag athrú leagan an leathanaigh seo atá as dáta.
Má shábhálfar é, caillfear gach athrú a rinneadh i ndiaidh an leagain seo.'''",
'yourdiff'                         => 'Difríochtaí',
'copyrightwarning'                 => "Tabhair faoi deara go dtuigtear go bhfuil gach dréacht do {{SITENAME}} eisithe faoi $2 (féach ar $1 le haghaidh tuilleadh eolais).
Murar mian leat go gcuirfí do chuid scríbhinne in eagar go héadrócaireach agus go n-athdálfaí gan teorainn í, ná cuir isteach anseo í.<br />
Ina theannta sin, geallann tú gur scríobh tú féin an dréacht seo, nó gur chóipeáil tú é ó fhoinse san fhearann poiblí nó acmhainn eile saor ó chóipcheart (féach ar $1 le haghaidh tuilleadh eolais).
'''NÁ CUIR ISTEACH OBAIR LE CÓIPCHEART GAN CHEAD!'''",
'copyrightwarning2'                => "Tabhair faoi deara gur féidir le heagarthóirí eile gach dréacht do {{SITENAME}} a chur in eagar, a athrú agus a scriosadh.
Murar mian leat go gcuirfí do chuid scríbhinne in eagar go héadrócaireach, ná cuir isteach anseo í.<br />
Ina theannta sin, geallann tú gur scríobh tú féin an dréacht seo, nó gur chóipeáil tú é ó fhoinse san fhearann poiblí nó acmhainn eile saor ó chóipcheart (féach ar $1 le haghaidh tuilleadh eolais).
'''NÁ CUIR ISTEACH OBAIR LE CÓIPCHEART GAN CHEAD!'''",
'longpageerror'                    => "'''EARRÁID: Tá an téacs a chuir isteach $1 cilibheart ar fad, sin níos faide ná $2 cilibheart, an uasmhéid.
Ní féidir é a shábháil.'''",
'readonlywarning'                  => "'''Rabhadh: Glasáladh an bunachar sonraí chun cothabháil a dhéanamh, agus mar sin ní féidir leat do chuid athruithe a shábháil go díreach anois.
B'fhéidir gur mhaith leat an téacs a ghearradh is ghreamú isteach i gcomhad téacs agus é a úsáid níos déanaí.'''

An fáth ar thug an riarthóir a ghlasáil é: $1",
'protectedpagewarning'             => "'''Rabhadh: Glasáladh an leathanach seo. Is féidir le riarthóirí amháin é a athrú.'''",
'templatesused'                    => '{{PLURAL:$1|Teimpléad|Teimpléid}} a úsáidtear ar an leathanach seo:',
'templatesusedpreview'             => '{{PLURAL:$1|Teimpléad|Teimpléid}} a úsáidtear sa réamhamharc seo:',
'templatesusedsection'             => 'Teimpléid in úsáid san alt seo:',
'template-protected'               => '(ghlasáil)',
'template-semiprotected'           => '(leath-ghlasáil)',
'edittools'                        => '<!-- Taispeánfar an téacs seo faoi foirmeacha eagarthóireachta agus uaslódála. -->',
'nocreatetext'                     => 'Tá srianadh ar {{SITENAME}} faoin leathanaigh nua a cruthaidh.
Is féidir leat dul ar ais chun leathanach láithreach a athrú, nó [[Special:UserLogin|log isteach nó cruthaigh cuntas nua]].',
'nocreate-loggedin'                => 'Níl cead agat leathanaigh nua a chruthú.',
'permissionserrors'                => 'Cead rochtana earráidí',
'permissionserrorstext-withaction' => 'Níl cead agat $2, mar gheall ar {{PLURAL:$1|an fáth|na fáthanna}} seo a leanas:',
'recreate-moveddeleted-warn'       => "'''Rabhadh: Tá tú ag athchruthú leathanaigh a scriosadh cheana.'''

An bhfuil tú cinnte go bhfuil sé oiriúnach an leathanach seo a cur in eagar?
Cuirtear an loga scriosta ar fáil anseo mar áis:",

# "Undo" feature
'undo-summary' => 'Cealaíodh athrú $1 le [[Special:Contributions/$2|$2]] ([[User talk:$2|plé]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ní féidir cuntas a chruthú',

# History pages
'viewpagelogs'           => 'Féach ar logaí faoin leathanach seo',
'nohistory'              => 'Níl aon stáir athraithe ag an leathanach seo.',
'currentrev'             => 'Leagan reatha',
'currentrev-asof'        => 'Leagan reatha ó $1',
'revisionasof'           => 'Leagan ó $1',
'revision-info'          => 'Leagan ó $1 le $2',
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
'histfirst'              => 'An ceann is luaithe',
'histlast'               => 'An ceann is déanaí',
'historysize'            => '({{PLURAL:$1|Beart amháin|$1 bearta}})',
'historyempty'           => '(folamh)',

# Revision feed
'history-feed-title'          => 'Stáir leasú',
'history-feed-description'    => 'Stair leasú an leathanach seo ar an vicí',
'history-feed-item-nocomment' => '$1 ag $2',

# Revision deletion
'rev-deleted-user'            => '(ainm úsáideora dealaithe)',
'rev-delundel'                => 'taispeáin/folaigh',
'revisiondelete'              => 'Scrios/díscrios leagain',
'revdelete-show-file-confirm' => 'An bhfuil tú cinnte gur mhaith leat féach ar leasú scriosta don chomhad "<nowiki>$1</nowiki>" ó $2 ag $3?',
'revdelete-show-file-submit'  => 'Tá',
'revdelete-selected'          => "'''{{PLURAL:$2|Leagan roghnaithe|Leagain roghnaithe}} [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Teagmhas log roghnaithe|Teagmhais log roghnaithe}}:'''",
'revdelete-hide-text'         => 'Folaigh leagan téacs',
'revdel-restore'              => 'athraigh infheictheacht',
'pagehist'                    => 'Stair leathanach',
'deletedhist'                 => 'Stair scriosta',
'revdelete-uname'             => 'ainm úsáideora',
'revdelete-log-message'       => '$1 le $2 {{PLURAL:$2|leagan|leagain}}',

# History merging
'mergehistory-from'   => 'Leathanach fhoinse:',
'mergehistory-reason' => 'Fáth:',

# Merge log
'revertmerge' => 'Díchumaisc',

# Diffs
'history-title'           => 'Stair leasú "$1"',
'difference'              => '(Difríochtaí idir leaganacha)',
'lineno'                  => 'Líne $1:',
'compareselectedversions' => 'Cuir na leagain roghnaithe i gcomparáid',
'editundo'                => 'cealaigh',
'diff-multi'              => '({{PLURAL:$1|Leasú idirmheánach amháin|$1 leasú idirmheánach}} nach thaispeántar.)',

# Search results
'searchresults'                  => 'Torthaí an chuardaigh',
'searchresults-title'            => 'Torthaí an chuardaigh do "$1"',
'searchresulttext'               => 'Féach ar [[{{MediaWiki:Helppage}}|{{int:help}}]] chun a thuilleadh eolais a fháil maidir le cuardaigh {{GRAMMAR:genitive|{{SITENAME}}}}.',
'searchsubtitle'                 => 'Cuardaigh le \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|gach leathanaigh ag tosú le "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|gach leathanaigh naiscthe le "$1"]])',
'searchsubtitleinvalid'          => 'Don iarratas "$1"',
'titlematches'                   => 'Fuarthas leathanaigh faoin teideal seo',
'notitlematches'                 => 'Ní bhfuarthas leathanach faoin teideal seo',
'textmatches'                    => 'Fuarthas an téacs ar leathanaigh',
'notextmatches'                  => 'Ní bhfuarthas an téacs ar leathanach ar bith',
'prevn'                          => 'na {{PLURAL:$1|$1}} cinn roimhe seo',
'nextn'                          => 'an {{PLURAL:$1|$1}} i ndiadh',
'viewprevnext'                   => 'Taispeáin ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'              => 'Sainroghanna cuardaithe',
'searchmenu-new'                 => "'''Cruthaigh an leathanach \"[[:\$1]]\" ar an vicí seo!'''",
'searchhelp-url'                 => 'Help:Clár_ábhair',
'searchprofile-project'          => 'Leathanaigh thionscadail',
'searchprofile-images'           => 'Ilmheáin',
'searchprofile-everything'       => 'Gach rud',
'searchprofile-articles-tooltip' => 'Cuardaigh i $1',
'searchprofile-project-tooltip'  => 'Cuardaigh i $1',
'searchprofile-images-tooltip'   => 'Cuardaigh le comhaid',
'search-result-size'             => '$1 ({{PLURAL:$2|focal amháin|$2 focail}})',
'search-redirect'                => '(athsheoladh $1)',
'search-section'                 => '(gearradh $1)',
'search-suggest'                 => 'An raibh $1 á lorg agat?',
'search-interwiki-caption'       => 'Comhthionscadail',
'search-interwiki-default'       => '$1 torthaí:',
'search-interwiki-more'          => '(níos mó)',
'search-mwsuggest-enabled'       => 'le moltaí',
'search-mwsuggest-disabled'      => 'gan mholtaí',
'search-relatedarticle'          => 'Gaolmhar',
'mwsuggest-disable'              => 'Díchumasaigh moltaí AJAX',
'searchrelated'                  => 'gaolmhara',
'searchall'                      => 'an t-iomlán',
'showingresults'                 => "Ag taispeáint thíos {{PLURAL:$1|'''toradh amháin'''|'''$1''' torthaí}}, ag tosú le #'''$2'''.",
'showingresultsnum'              => "Ag taispeáint thíos {{PLURAL:$3|'''toradh amháin'''|'''$3''' torthaí}}, ag tosú le #'''$2'''.",
'nonefound'                      => "<strong>Tabhair faoi deara:</strong> Ní chuardaítear ach ainmspásanna áirithe de réir réamhshocraithe.
Bain triail as ''all:'' a chur roimh d'iarratas chun an t-inneachar ar fad (leathanaigh phlé, teimpléid, srl. san áireamh) a chuardach, nó cuir isteach réimír an ainmspáis.",
'search-nonefound'               => 'Ní bhfuarthas toradh ar bith ar an iarratas.',
'powersearch'                    => 'Cuardaigh',
'powersearch-legend'             => 'Cuardach casta',
'powersearch-ns'                 => 'Cuardaigh in ainmspásanna:',
'powersearch-redir'              => 'Liosta athsheoltaí',
'powersearch-field'              => 'Cuardaigh le',
'searchdisabled'                 => "Tá brón orainn! Mhíchumasaíodh an cuardach téacs iomlán go sealadach chun luas an tsuímh a chosaint. Idir an dá linn, is féidir leat an cuardach Google anseo thíos a úsáid - b'fhéidir go bhfuil sé as dáta.",

# Quickbar
'qbsettings'               => 'Sainroghanna an bosca uirlisí',
'qbsettings-none'          => 'Faic',
'qbsettings-fixedleft'     => 'Greamaithe ar chlé',
'qbsettings-fixedright'    => 'Greamaithe ar dheis',
'qbsettings-floatingleft'  => 'Ag faoileáil ar chlé',
'qbsettings-floatingright' => 'Ag faoileáil ar dheis',

# Preferences page
'preferences'               => 'Sainroghanna',
'mypreferences'             => 'Mo shainroghanna',
'prefsnologin'              => 'Níl tú logáilte isteach',
'prefsnologintext'          => 'Ní mór duit <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} logáil isteach]</span> chun do chuid sainroghanna phearsanta a shocrú.',
'changepassword'            => "Athraigh d'fhocal faire",
'prefs-skin'                => 'Craiceann',
'skin-preview'              => 'Réamhamharc',
'prefs-math'                => 'Matamaitice',
'datedefault'               => 'Is cuma liom',
'prefs-datetime'            => 'Dáta agus am',
'prefs-personal'            => 'Sonraí úsáideora',
'prefs-rc'                  => 'Athruithe is déanaí',
'prefs-watchlist'           => 'Liosta faire',
'prefs-watchlist-days'      => 'Líon na laethanta le taispeáint sa liosta faire:',
'prefs-watchlist-days-max'  => '(uasmhéid 7 lá)',
'prefs-watchlist-edits'     => 'Líon na n-athruithe le taispeáint sa liosta leathnaithe faire:',
'prefs-watchlist-edits-max' => '(uasmhéid: 1000)',
'prefs-misc'                => 'Éagsúla',
'prefs-resetpass'           => 'Athraigh focal faire',
'saveprefs'                 => 'Sábháil',
'resetprefs'                => 'Athshocraigh sainroghanna',
'restoreprefs'              => 'Cuir ar ais gach sainrogha réamhshocraithe',
'prefs-editing'             => 'Eagarthóireacht',
'rows'                      => 'Sraitheanna',
'columns'                   => 'Colúin',
'searchresultshead'         => 'Cuardaigh',
'resultsperpage'            => 'Torthaí le taispeáint ó leathanach:',
'contextlines'              => 'Línte le taispeáint do gach toradh:',
'contextchars'              => 'Litreacha chomhthéacs ar gach líne:',
'recentchangesdays'         => 'Méid laethanta le taispeáint sna hathruithe is déanaí:',
'recentchangesdays-max'     => '(uasmhéid $1 {{PLURAL:$1|lá|lá}})',
'recentchangescount'        => 'Méid athrú le taispeáint:',
'savedprefs'                => 'Sábháladh do chuid sainroghanna.',
'timezonelegend'            => 'Crios ama:',
'localtime'                 => 'An t-am áitiúil:',
'timezoneuseserverdefault'  => 'Úsáid am réamhshocraithe an fhreastalaí',
'timezoneuseoffset'         => 'Eile (cuir isteach an difear)',
'timezoneoffset'            => 'Difear¹:',
'servertime'                => 'Am an fhreastalaí:',
'guesstimezone'             => 'Líon ón mbrabhsálaí',
'timezoneregion-africa'     => 'An Afraic',
'timezoneregion-america'    => 'Meiriceá',
'timezoneregion-antarctica' => 'Antartaice',
'timezoneregion-arctic'     => 'Artach',
'timezoneregion-asia'       => 'an Áise',
'timezoneregion-atlantic'   => 'An tAigéan Atlantach',
'timezoneregion-australia'  => 'An Astráil',
'timezoneregion-europe'     => 'An Eoraip',
'timezoneregion-indian'     => 'An tAigéan Indiach',
'timezoneregion-pacific'    => 'An tAigéan Ciúin',
'allowemail'                => "Tabhair cead d'úsáideoirí eile ríomhphost a sheoladh chugat.",
'prefs-namespaces'          => 'Ainmspáis',
'defaultns'                 => 'Cuardaigh sna ranna seo a los éagmaise:',
'default'                   => 'réamhshocrú',
'prefs-files'               => 'Comhaid',
'youremail'                 => 'Do ríomhsheoladh:',
'username'                  => "D'ainm úsáideora:",
'uid'                       => 'D’uimhir úsáideora:',
'prefs-memberingroups'      => 'Comhalta {{PLURAL:$1|an ghrúpa|na ghrúpaí}}:',
'yourrealname'              => "D'fhíorainm **",
'yourlanguage'              => 'Teanga',
'yourvariant'               => 'Difríocht teanga:',
'yournick'                  => 'Do leasainm (mar a bheidh i sínithe)',
'badsig'                    => 'Amhsíniú neamhbhailí; scrúdaigh na clibeanna HTML.',
'badsiglength'              => 'Tá do shíniú ró-fhada.<br />
Caithfidh sé bheith níos giorra ná {{PLURAL:$1|carachtar|carachtair}}.',
'yourgender'                => 'Inscne:',
'gender-male'               => 'Fireann',
'gender-female'             => 'Baineann',
'prefs-help-gender'         => 'Roghnach: úsáidtear an rogha seo chun an t-inscne cheart a úsáid agus na bogearraí ag tagairt don úsaideoir.
Beidh an t-eolas seo poiblí.',
'email'                     => 'Ríomhphost',
'prefs-help-realname'       => '* <strong>Fíorainm</strong> (roghnach): má toghaíonn tú é sin a chur ar fáil, úsáidfear é chun
do chuid dreachtaí a chur i leith tusa.',
'prefs-help-email'          => '<strong>Ríomhphost</strong> (roghnach): Leis an tréith seo is féidir teagmháil a dhéanamh leat tríd do leathanach úsáideora nó leathanach phlé gan do sheoladh ríomhphost a thaispeáint.',
'prefs-help-email-required' => 'Ní foláir seoladh ríomhpoist a thabhairt.',
'prefs-dateformat'          => 'Formáid dáta',
'prefs-timeoffset'          => 'Difear ama',

# User rights
'userrights'               => 'Bainistíocht cearta úsáideora',
'userrights-user-editname' => 'Iontráil ainm úsáideora:',
'editusergroup'            => 'Cuir Grúpái Úsáideoirí In Eagar',
'editinguser'              => "Ag athrú ceart don úsáideoir '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
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
'action-edit'          => 'an leathanach seo a athrú',
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
'recentchanges-label-bot'           => 'Chomhlíon róbó an t-athrú seo',
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
'rc-enhanced-expand'                => 'Taispeáin mionsonraithe (JavaScript riachtanach)',
'rc-enhanced-hide'                  => 'Folaigh shonraí',

# Recent changes linked
'recentchangeslinked'          => 'Athruithe gaolmhara',
'recentchangeslinked-feed'     => 'Athruithe gaolmhara',
'recentchangeslinked-toolbox'  => 'Athruithe gaolmhara',
'recentchangeslinked-title'    => 'Athruithe gaolmhara le "$1"',
'recentchangeslinked-noresult' => 'Níl aon athraithe ar na leathanaigh naiscthe le linn an tréimhse tugtha.',
'recentchangeslinked-summary'  => "Seo liosta na n-athruithe atá deanta is déanaí le leathanaigh atá naiscthe as leathanach sonraithe (nó baill an chatagóir sonraithe).
Tá na leathanaigh ar do [[Special:Watchlist|liosta faire]] i '''gcló trom'''.",
'recentchangeslinked-page'     => 'Ainm leathanaigh:',
'recentchangeslinked-to'       => 'Taispeáin athruithe do leathanaigh nasctha leis an leathanach áirithe sin ina áit.',

# Upload
'upload'               => 'Uaslódaigh comhad',
'uploadbtn'            => 'Uaslódaigh comhad',
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
'fileexists-forbidden' => 'Tá comhad eile leis an ainm seo ann fós, agus ní féidie é a forscríobh.
Má theastáilann uait do chomhad a uaslódáil fós, téigh ar ais agus úsáid ainm nua, le do thoil. [[File:$1|thumb|center|$1]]',
'uploadwarning'        => 'Rabhadh suaslódála',
'savefile'             => 'Sábháil comhad',
'uploadedimage'        => 'uaslódáladh "[[$1]]"',
'uploaddisabled'       => 'Tá brón orainn, ní féidir aon rud a uaslódáil faoi láthair.',
'uploaddisabledtext'   => 'Tá cosc ar uaslódáil comhad.',
'uploadvirus'          => 'Tá víreas ann sa comhad seo! Eolas: $1',
'sourcefilename'       => 'Comhadainm foinse:',
'destfilename'         => 'Comhadainm sprice:',
'upload-maxfilesize'   => 'Méad comhad is mó: $1',
'watchthisupload'      => 'Déan faire ar an leathanach seo',
'upload-success-subj'  => "D'éirigh leis an uaslódáil",

'upload-proto-error' => 'Prótacal mícheart',
'upload-file-error'  => 'Earráid inmheánach',

'license'            => 'Ceadúnas:',
'license-header'     => 'Ceadúnú',
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
'file-anchor-link'                  => 'Comhad',
'filehist'                          => 'Stair comhad',
'filehist-help'                     => 'Clic ar dáta/am chun an comhad a radharc mar a bhí sé ar an am.',
'filehist-deleteone'                => 'scrios',
'filehist-current'                  => 'reatha',
'filehist-datetime'                 => 'Dáta/Am',
'filehist-thumb'                    => 'Mionsamhail',
'filehist-thumbtext'                => 'Mionsamhail do leagan ó $1',
'filehist-user'                     => 'Úsáideoir',
'filehist-dimensions'               => 'Toisí',
'filehist-filesize'                 => 'Méid an comhad',
'filehist-comment'                  => 'Nóta tráchta',
'imagelinks'                        => 'Naisc comhaid',
'linkstoimage'                      => 'Tá nasc chuig an gcomhad seo ar {{PLURAL:$1|na leathanaigh|$1 an leathanach}} seo a leanas:',
'nolinkstoimage'                    => 'Níl nasc ó aon leathanach eile don íomhá seo.',
'sharedupload'                      => 'Is uaslodáil roinnte atá ann sa comhad seo, as $1, agus is féidir le tionscadail eile é a úsáid.',
'uploadnewversion-linktext'         => 'Uaslódáil leagan nua den comhad seo',
'shared-repo'                       => 'comhstóráil',
'shared-repo-name-wikimediacommons' => 'an Cómhaoin Vicíméid',

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
'listredirects' => 'Liosta athsheoltaí',

# Unused templates
'unusedtemplates'    => 'Teimpléid nach n-úsáidtear',
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

'doubleredirects'     => 'Athsheoltaí dúbailte',
'doubleredirectstext' => '<b>Tabhair faoi deara:</b> B\'fheidir go bhfuil toraidh bréagacha ar an liosta seo.
De ghnáth cíallaíonn sé sin go bhfuil téacs breise le naisc thíos sa chéad #REDIRECT no #ATHSHEOLADH.<br />
 Sa
gach sraith tá náisc chuig an chéad is an dara athsheoladh, chomh maith le chéad líne an dara téacs athsheolaidh. De
ghnáth tugann sé sin an sprioc-alt "fíor".',

'brokenredirects'        => 'Atreoruithe briste',
'brokenredirectstext'    => 'Is iad na athsheolaidh seo a leanas a nascaíonn go ailt nach bhfuil ann fós:',
'brokenredirects-edit'   => 'athraigh',
'brokenredirects-delete' => 'scrios',

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
'uncategorizedpages'      => 'Leathanaigh gan chatagóir',
'uncategorizedcategories' => 'Catagóirí gan chatagórú',
'uncategorizedimages'     => 'Íomhánna gan chatagóir',
'uncategorizedtemplates'  => 'Teimpléid gan chatagóir',
'unusedcategories'        => 'Catagóirí nach n-úsáidtear',
'unusedimages'            => 'Íomhánna nach n-úsáidtear',
'popularpages'            => 'Leathanaigh is mó cuairteanna orthu',
'wantedcategories'        => 'Catagóirí agus iarraidh ag gabháil leis',
'wantedpages'             => 'Leathanaigh de dhíth',
'mostlinked'              => 'Leathanaigh is mó naisc chucu',
'mostlinkedcategories'    => 'Catagóirí is mó naisc chucu',
'mostlinkedtemplates'     => 'Na teimpléid naiscthe is mó',
'mostcategories'          => 'Leathanaigh is mó catagóirí acu',
'mostimages'              => 'Na comhaid naiscthe is mó',
'mostrevisions'           => 'Leathanaigh leis na leasaithe is mó',
'prefixindex'             => 'Gach leathanach le réimír',
'shortpages'              => 'Leathanaigh ghearra',
'longpages'               => 'Leathanaigh fhada',
'deadendpages'            => 'Leathanaigh chaocha',
'protectedpages'          => 'Leathanaigh chosanta',
'protectedtitles'         => 'Teidil chosanta',
'listusers'               => 'Liosta úsáideoirí',
'newpages'                => 'Leathanaigh nua',
'newpages-username'       => 'Ainm úsáideora:',
'ancientpages'            => 'Na leathanaigh is sine',
'move'                    => 'Athainmnigh',
'movethispage'            => 'Athainmnigh an leathanach seo',
'unusedimagestext'        => 'Tá na comhaid a leanas ann ach níl siad leabaithe i leathanach ar bith.
Tabhair faoi deara gur féidir le suímh eile nasc a dhéanamh le comhad trí URL díreach, agus mar sin bheadh siad ar an liosta seo fós cé go bhfuil siad in úsáid faoi láthair.',
'unusedcategoriestext'    => 'Tá na leathanaigh catagóire seo a leanas ann, cé nach n-úsáidtear iad in aon alt eile nó in aon chatagóir eile.',
'notargettitle'           => 'Níl aon cuspóir ann',
'notargettext'            => 'Níor thug tú leathanach nó úsáideoir sprice
chun an gníomh seo a dhéanamh ar.',
'pager-newer-n'           => '{{PLURAL:$1|1 níos nuaí|$1 níos nuaí}}',
'pager-older-n'           => '{{PLURAL:$1|1 níos sine|$1 níos sine}}',

# Book sources
'booksources'               => 'Leabharfhoinsí',
'booksources-search-legend' => 'Cuardaigh le foinsí leabhar',
'booksources-go'            => 'Gabh',

# Special:Log
'specialloguserlabel'  => 'Úsáideoir:',
'speciallogtitlelabel' => 'Teideal:',
'log'                  => 'Loganna',
'all-logs-page'        => 'Gach loga poiblí',
'alllogstext'          => 'Bailiúchán cuimsitheach de gach loga {{SITENAME}}.
Is féidir leat an méid ar taispeáint a chúngú trí roghnú an saghas loga, an t-ainm úsáideora (cásíogair), nó an leathanach (cásíogair freisin) atá i gceist agat.',

# Special:AllPages
'allpages'          => 'Gach leathanach',
'alphaindexline'    => '$1 go $2',
'nextpage'          => 'An leathanach a leanas ($1)',
'prevpage'          => 'Leathanach roimhe sin ($1)',
'allpagesfrom'      => 'Taispeáin leathanaigh ó:',
'allpagesto'        => 'Go:',
'allarticles'       => 'Gach alt',
'allinnamespace'    => 'Gach leathanach (ainmspás $1)',
'allnotinnamespace' => 'Gach leathanach (lasmuigh den ainmspás $1)',
'allpagesprev'      => 'Siar',
'allpagesnext'      => 'Ar aghaidh',
'allpagessubmit'    => 'Gabh',
'allpagesprefix'    => 'Taispeáin leathanaigh leis an réimír:',
'allpages-bad-ns'   => 'Níl an t-ainmspás "$1" ar {{SITENAME}}',

# Special:Categories
'categories'         => 'Catagóirí',
'categoriespagetext' => 'Tá leathanaigh nó meáin {{PLURAL:$1|sa chatagóir|sna catagóirí}} seo a leanas.
Ní thaispeántar [[Special:UnusedCategories|catagóiri neamhúsáidte]] anseo.
Féach freisin ar [[Special:WantedCategories|catagóirí faoi iarraidh]].',

# Special:DeletedContributions
'deletedcontributions'       => 'Dréachtaí úsáideora scriosta',
'deletedcontributions-title' => 'Dréachtaí úsáideora scriosta',

# Special:LinkSearch
'linksearch'      => 'Naisc eachtraigh',
'linksearch-ns'   => 'Ainmspás:',
'linksearch-ok'   => 'Cuardaigh',
'linksearch-line' => '$1 naiscthe as $2',

# Special:ListUsers
'listusers-submit' => 'Taispeáin',

# Special:Log/newusers
'newuserlogpage'           => 'Log cruthú úsáideoira',
'newuserlog-create-entry'  => 'Úsáideoir nua',
'newuserlog-create2-entry' => 'cuntas cruthú le $1',

# Special:ListGroupRights
'listgrouprights-group'   => 'Ghrúpa',
'listgrouprights-rights'  => 'Cearta',
'listgrouprights-members' => '(liostaigh baill)',

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
'noemailtext'     => 'Níor thug an úsáideoir seo seoladh ríomhphoist bhailí.',
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
'nowatchlist'          => 'Níl aon rud ar do liosta faire.',
'watchlistanontext'    => "$1, le d'thoil, chun míreanna ar do liosta faire a fheiceáil ná a athrú.",
'watchnologin'         => 'Níl tú logáilte isteach',
'watchnologintext'     => 'Tá ort a bheith [[Special:UserLogin|logáilte isteach]] chun do liosta faire a athrú.',
'addedwatch'           => 'Curtha ar an liosta faire',
'addedwatchtext'       => "Cuireadh an leathanach \"<nowiki>\$1</nowiki>\" le do [[Special:Watchlist|liosta faire]].
Amach anseo liostálfar athruithe don leathanach seo agus dá leathanach plé ansin,
agus beidh '''cló trom''' ar a theideal san [[Special:RecentChanges|liosta de na hathruithe is déanaí]] sa chaoi go bhfeicfeá iad go héasca.",
'removedwatch'         => 'Bainte den liosta faire',
'removedwatchtext'     => 'Baineadh an leathanach "[[:$1]]" as [[Special:Watchlist|do liosta faire]].',
'watch'                => 'Déan faire',
'watchthispage'        => 'Déan faire ar an leathanach seo',
'unwatch'              => 'Ná fair',
'unwatchthispage'      => 'Ná fair fós',
'notanarticle'         => 'Níl alt ann',
'notvisiblerev'        => 'Scriosadh an leagan',
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
'created'                      => 'Chruthaigh',
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

I gcás athruithe eile, ní bheidh aon fhógra eile muna dtéann tú go dtí an leathanach seo.
Is féidir freisin na bratacha fógartha a athrú do gach leathanach ar do liosta faire.

	     Is mise le meas,
	     Fógrachóras cairdiúil {{GRAMMAR:genitive|{{SITENAME}}}}

--
Chun socruithe do liosta faire a athrú, tabhair cuairt ar
{{fullurl:Special:Watchlist/edit}}

Chun an leathanach a bhaint de do liosta faire, tabhair cuairt ar
$UNWATCHURL

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
'historywarning'        => "'''Rabhadh:''' Tá stair (tuairim is {{PLURAL:$1|leagan amháin|$1 leaganacha}}) ag an leathanach a bhfuil tú ar tí é a scriosadh:",
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
'deletecomment'         => 'Fáth:',
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
'alreadyrolled'  => "Ní féidir eagrán níos luaí an leathanaigh [[:$1]] le [[User:$2|$2]] ([[User talk:$2|Plé]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]) a athúsáid; d'athraigh duine eile é cheana fein, nó d'athúsáid duine eile eagrán níos luaí cheana féin.

[[User:$3|$3]] ([[User talk:$3|Plé]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]) an té a rinne an athrú is déanaí.",
'editcomment'    => "Seo a raibh an achoimre eagarthóireacht: \"''\$1''\".",
'revertpage'     => 'Cealaíodh athruithe [[Special:Contributions/$2|$2]] ([[User talk:$2|Plé]]); ar ais chuig leagan le [[User:$1|$1]]',

# Protect
'protectlogpage'              => 'Log cosanta',
'protectlogtext'              => 'Seo é liosta de glais a cuireadh ar / baineadh de leathanaigh.
Féach ar [[Special:ProtectedPages|Leathanach glasáilte]] chun a thuilleadh eolais a fháil.',
'protectedarticle'            => 'glasáladh "[[$1]]"',
'modifiedarticleprotection'   => 'tar éis an leibhéal cosanta a athrú do "[[$1]]"',
'unprotectedarticle'          => 'díghlasáladh "[[$1]]"',
'protect-title'               => 'Ag glasáil "$1"',
'prot_1movedto2'              => 'Athainmníodh $1 mar $2',
'protect-legend'              => 'Cinntigh an glasáil',
'protectcomment'              => 'Fáth:',
'protectexpiry'               => 'As feidhm:',
'protect_expiry_invalid'      => 'Am éaga neamhbhailí.',
'protect_expiry_old'          => 'Am éaga san am atá thart.',
'protect-text'                => "Is féidir leat an leibhéal glasála a athrú anseo don leathanach '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Ní chead ag do chuntas chun athraigh leibhéal cosaint an leathanach.
Seo iad na socruithe reatha faoin leathanach '''$1''':",
'protect-cascadeon'           => 'Tá an leathanach seo ghlasáil le athrú mar tá se iniata ar {{PLURAL:$1|an leathanach seo|na leathanaigh seo}} a leanas, agus iad ghlasáil le glasáil cascáideach.
Is féidir an leibhéal glasála a athrú, ach ní féidir cur isteach ar an ghlasáil cascáideach.',
'protect-default'             => 'Ceadaigh gach úsáideoir',
'protect-fallback'            => 'Ceadúnas "$1" riachtanach',
'protect-level-autoconfirmed' => 'Cuir cosc ar úsáideoirí neamhchláraithe/nua',
'protect-level-sysop'         => 'Riarthóirí amháin',
'protect-summary-cascade'     => 'cascáidithe',
'protect-expiring'            => 'as feidhm $1 (UTC)',
'protect-expiry-indefinite'   => 'gan teorainn',
'protect-cascade'             => 'Coisc leathanaigh san áireamh an leathanach seo (cosanta cascáideach)',
'protect-cantedit'            => 'Ní féidir leat na leibhéil cosanta a athrú faoin leathanach seo, mar níl cead agat é a cur in eagar.',
'protect-othertime'           => 'Am eile:',
'protect-othertime-op'        => 'am eile',
'protect-expiry-options'      => 'uair amháin:1 hour,lá amháin:1 day,seachtain amháin:1 week,2 sheachtain:2 weeks,mí amháin:1 month,3 mhí:3 months,6 mhí:6 months,bliain amháin:1 year,gan teorainn:infinite',
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
'undeletelink'             => 'féach/díscrios',
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
'nocontribs'          => 'Ní bhfuarthas aon athrú a bhí cosúil le na crítéir seo.',
'uctop'               => ' (barr)',
'month'               => 'Ón mhí seo (agus níos luaithe):',
'year'                => 'Ón bhliain seo (agus níos luaithe):',

'sp-contributions-newbies'       => 'Taispeáin dréachtaí ó chuntais nua amháin',
'sp-contributions-newbies-sub'   => 'Le cuntais nua',
'sp-contributions-newbies-title' => 'Dréachtaí úsáideora do chuntasaí nua',
'sp-contributions-blocklog'      => 'Log coisc',
'sp-contributions-deleted'       => 'dréachtaí úsáideora scriosta',
'sp-contributions-talk'          => 'plé',
'sp-contributions-userrights'    => 'bainistíocht cearta úsáideora',
'sp-contributions-search'        => 'Cuardaigh dréachtaí',
'sp-contributions-username'      => 'Seoladh IP nó ainm úsáideora:',
'sp-contributions-submit'        => 'Cuardaigh',

# What links here
'whatlinkshere'            => 'Naisc go dtí an lch seo',
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
'whatlinkshere-hideredirs' => '$1 athsheolaidh',
'whatlinkshere-hidetrans'  => '$1 trasiamh',
'whatlinkshere-hidelinks'  => '$1 nasc',
'whatlinkshere-hideimages' => '$1 nasc íomhá',
'whatlinkshere-filters'    => 'Scagairí',

# Block/unblock
'blockip'                  => 'Coisc úsáideoir',
'blockip-legend'           => 'Coisc úsáideoir',
'blockiptext'              => 'Úsáid an foirm anseo thíos chun bealach scríofa a chosc ó
seoladh IP nó ainm úsáideora áirithe.
Is féidir leat an rud seo a dhéanamh amháin chun an chreachadóireacht a chosc, de réir
mar a deirtear sa [[{{MediaWiki:Policy-url}}|polasaí {{GRAMMAR:genitive|{{SITENAME}}}}]].
Líonaigh cúis áirithe anseo thíos (mar shampla, is féidir leat a luaigh
leathanaigh áirithe a rinne an duine damáiste ar).',
'ipaddress'                => 'Seoladh IP / ainm úsáideora',
'ipadressorusername'       => 'Seoladh IP nó ainm úsáideora:',
'ipbexpiry'                => 'Am éaga',
'ipbreason'                => 'Fáth:',
'ipbreasonotherlist'       => 'Fáth eile',
'ipbreason-dropdown'       => '*Fáthanna coitianta
** Loitiméaracht
** Naisc turscar
** Fadhbanna cóipcheart
** Ag iarraidh ciapadh daoine eile
** Drochúsáid as cuntais iolrach
** Fadhbanna idirvicí
** Feallaire
** Seachfhreastalaí Oscailte',
'ipbsubmit'                => 'Coisc an úsáideoir seo',
'ipbother'                 => 'Méid eile ama',
'ipboptions'               => '2 uair:2 hours,1 lá amháin:1 day,3 lá:3 days,1 sheachtain amháin:1 week,2 sheachtain:2 weeks,1 mhí amháin:1 month,3 mhí:3 months,6 mhí:6 months,1 bhliain amháin:1 year,gan teorainn:infinite',
'ipbotheroption'           => 'eile',
'badipaddress'             => 'Níl aon úsáideoir ann leis an ainm seo.',
'blockipsuccesssub'        => "D'éirigh leis an cosc",
'blockipsuccesstext'       => 'Choisceadh [[Special:Contributions/$1|$1]].
<br />Féach ar an g[[Special:IPBlockList|liosta coisc IP]] chun coisc a athbhreithniú.',
'ipb-unblock-addr'         => 'Díchoisc $1',
'ipb-unblock'              => 'Díchosc ainm úsáideora ná seoladh IP',
'unblockip'                => 'Díchoisc úsáideoir',
'unblockiptext'            => 'Úsáid an foirm anseo thíos chun bealach scríofa a thabhairt ar ais do seoladh
IP nó ainm úsáideora a raibh faoi chosc roimhe seo.',
'ipusubmit'                => 'Bain an chosc seo',
'unblocked'                => 'Díchoisceadh [[User:$1|$1]]',
'ipblocklist'              => 'Liosta seoltaí IP agus ainmneacha úsáideoirí coiscthe',
'ipblocklist-legend'       => 'Aimsigh úsáideoir coiscthe',
'ipblocklist-username'     => 'Ainm úsáideora ná seoladh IP:',
'ipblocklist-submit'       => 'Cuardaigh',
'blocklistline'            => '$1, $2 a choisc $3 (am éaga $4)',
'infiniteblock'            => 'gan teorainn',
'anononlyblock'            => 'úsáideoirí gan ainm agus iad amháin',
'ipblocklist-empty'        => 'Tá an liosta coisc folamh.',
'blocklink'                => 'coisc',
'unblocklink'              => 'bain an cosc',
'change-blocklink'         => 'athraigh cosc',
'contribslink'             => 'dréachtaí',
'autoblocker'              => 'Coisceadh go huathoibríoch thú dá bharr gur úsáid an t-úsáideoir "[[User:$1|$1]]" do sheoladh IP le déanaí.
Is é seo an chúis don chosc ar $1: "$2".',
'blocklogpage'             => 'Cuntas_coisc',
'blocklogentry'            => 'coisceadh [[$1]]; am éaga $2. $3',
'blocklogtext'             => 'Seo é cuntas de gníomhartha coisc úsáideoirí agus míchoisc úsáideoirí. Ní cuirtear
seoltaí IP a raibh coiscthe go huathoibríoch ar an liosta seo. Féach ar an
[[Special:IPBlockList|Liosta coisc IP]] chun
liosta a fháil de coisc atá i bhfeidhm faoi láthair.',
'unblocklogentry'          => 'díchoisceadh $1',
'block-log-flags-nocreate' => 'cuntas chruthú díchumasaithe',
'block-log-flags-noemail'  => 'cosc ar ríomhphost',
'range_block_disabled'     => 'Faoi láthair, míchumasaítear an cumas riarthóra chun réimsechoisc a dhéanamh.',
'ipb_expiry_invalid'       => 'Am éaga neamhbhailí.',
'ipb_already_blocked'      => 'Tá cosc ar "$1" cheana féin',
'ip_range_invalid'         => 'Réimse IP neamhbhailí.',
'proxyblocker'             => 'Cosc ar seachfhreastalaithe',
'proxyblockreason'         => "Coisceadh do sheoladh IP dá bharr gur seachfhreastalaí
neamhshlándála is ea é. Déan teagmháil le do chomhlacht idirlín nó le do lucht cabhrach teicneolaíochta
go mbeidh 'fhios acu faoin fadhb slándála tábhachtach seo.",
'proxyblocksuccess'        => 'Rinneadh.',
'sorbsreason'              => 'Liostalaítear do sheoladh IP mar sheachfhreastalaí oscailte sa DNSBL.',

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
Is féidir atreoruithe don bhunteideal a nuashonrú go huathoibríoch.
Mura ndéanfaidh tú sin, bí cinnte go ndéanfaidh tú cuardach ar atreoruithe [[Special:DoubleRedirects|dúbailte]] nó [[Special:BrokenRedirects|briste]].
Tá dualgas ort bheith cinnte go rachaidh na naisc chuig an áit is ceart.

Tabhair faoi deara '''nach''' n-athainmneofar an leathanach má tá leathanach ann cheana féin faoin teideal nua, ach amháin más folamh nó atreorú é nó mura bhfuil aon stair athraithe aige cheana.
Mar sin, is féidir leathanach a athainmniú ar ais chuig an teideal a raibh air roimhe má tá botún déanta agat, agus ní féidir leathanach atá ann cheana a fhorscríobh.

<font color=\"red\">'''Rabhadh!'''</font>
Is féidir gur dianbheart gan choinne é athrú a dhéanamh ar leathanach móréilimh;
cinntigh go dtuigeann tú na hiarmhairtí go léir roimh dul ar aghaigh.",
'movepagetalktext'        => "Aistreofar an leathanach plé go huathoibríoch '''ach ní tharlófar sin''':
* má tá leathanach plé neamhfholamh ann cheana leis an teideal nua, nó
* má bhaineann tú an tic den bhosca thíos.

Sna cásanna sin, caithfidh tú an leathanach a aistrigh nó a chumasc tú féin más maith leat.",
'movearticle'             => 'Athainmnigh an leathanach',
'movenologin'             => 'Níl tú logáilte isteach',
'movenologintext'         => "Ní mór duit bheith i d'úsáideoir cláraithe agus [[Special:UserLogin|logáilte isteach]] chun leathanach a hathainmniú.",
'movenotallowed'          => 'Níl cead agat leathanaigh a athainmniú.',
'newtitle'                => 'Go teideal nua',
'move-watch'              => 'Déan faire an leathanach seo',
'movepagebtn'             => 'Athainmnigh an leathanach',
'pagemovedsub'            => "D'éirigh leis an athainmniú",
'movepage-moved'          => '\'\'\'Athainmníodh "$1" mar "$2"\'\'\'',
'articleexists'           => 'Tá leathanach leis an teideal seo ann cheana féin, nó níl an teideal a roghnaigh tú ina theideal bailí. Roghnaigh teideal eile le do thoil.',
'talkexists'              => "'''D’athainmníodh an leathanach é féin go rathúil, ach ní raibh sé ar a chumas an leathanach phlé a hathainmniú dá bharr go bhfuil ceann ann cheana féin ag an teideal nua.'''<br />
'''Báigh tusa féin iad.'''",
'movedto'                 => 'athainmnithe bheith',
'movetalk'                => 'Athainmnigh an leathanach plé freisin.',
'1movedto2'               => 'tar éis [[$1]] a athainmniú mar [[$2]]',
'1movedto2_redir'         => 'rinneadh athsheoladh de [[$1]] go [[$2]]',
'movelogpage'             => 'Log athainmnithe',
'movelogpagetext'         => 'Liosta is ea seo thíos de leathanaigh athainmnithe.',
'movereason'              => 'Fáth:',
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
'exporttext'      => 'Is féidir an téacs agus an stair athraithe de leathanach áirithe nó sraith leathanach a easpórtáil, fillte i bpíosa XML.
Is féidir é seo a iompórtáil i vicí eile MediaWiki trí úsáid an [[Special:Import|leathanach iompórtála]].

Chun leathanaigh a easpórtáil, cuir isteach na teidil sa bhosca thíos, gach teideal ar a líne féin, agus roghnaigh an leagan reatha in éineacht leis na sean-leaganacha agus stair an leathanaigh, nó an leagan reatha in éineacht le faisnéis faoin athrú deireanach.

Sa dara cás, is féidir leat nasc a úsáid, mar shampla [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] le haghaidh an leathanaigh "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'   => 'Ná cuir san áireamh ach an leagan láithreach; ná cuir an stair iomlán ann',
'export-submit'   => 'Easportáil',
'export-download' => 'Sábháil mar comhad',

# Namespace 8 related
'allmessages'               => 'Teachtaireachtaí córais',
'allmessagesname'           => 'Ainm',
'allmessagesdefault'        => 'Téacs réamhshocraithe',
'allmessagescurrent'        => 'Téacs reatha',
'allmessagestext'           => 'Is liosta é seo de theachtaireachtaí córais atá le fáil san ainmspás MediaWiki.
Tabhair cuairt ar [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] agus [http://translatewiki.net translatewiki.net] le do thoil más mian leat cur leis an logánú ginearálta MediaWiki.',
'allmessagesnotsupportedDB' => "Ní féidir an leathanach seo a úsáid dá bharr gur díchumasaíodh '''\$wgUseDatabaseMessages'''.",

# Thumbnails
'thumbnail-more'  => 'Méadaigh',
'filemissing'     => 'Comhad ar iarraidh',
'thumbnail_error' => 'Earráid agus mionsamhail a chruthú: $1',

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
'tooltip-pt-userpage'             => 'Do leathanach úsáideora',
'tooltip-pt-anonuserpage'         => 'Leathanach úsáideora don IP ina dhéanann tú do chuid athruithe',
'tooltip-pt-mytalk'               => 'Do leathanach phlé',
'tooltip-pt-anontalk'             => 'Plé maidir le na hathruithe a dhéantar ón seoladh IP seo',
'tooltip-pt-preferences'          => 'Mo chuid sainroghanna',
'tooltip-pt-watchlist'            => 'Liosta de na leathanaigh a bhfuil tú á bhfaire ar athruithe',
'tooltip-pt-mycontris'            => 'Liosta do chuid dréachtaí',
'tooltip-pt-login'                => 'Moltar duit logáil isteach, ach níl sé riachtanach.',
'tooltip-pt-anonlogin'            => 'Moltar duit logáil isteach, ach níl sé riachtanach.',
'tooltip-pt-logout'               => 'Logáil amach',
'tooltip-ca-talk'                 => 'Plé maidir leis an leathanach ábhair',
'tooltip-ca-edit'                 => 'Is féidir leat an leathanach seo a athrú. Más é do thoil é, bain úsáid as an cnaipe réamhamhairc roimh sábháil a dhéanamh.',
'tooltip-ca-addsection'           => 'Cur tús le alt nua',
'tooltip-ca-viewsource'           => 'Tá an leathanach seo glasáilte. Is féidir leat a fhoinse a fheiceáil.',
'tooltip-ca-history'              => 'Leagain stairiúla den leathanach seo.',
'tooltip-ca-protect'              => 'Glasáil an leathanach seo',
'tooltip-ca-delete'               => 'Scrios an leathanach seo',
'tooltip-ca-undelete'             => 'Díscrios na hathruithe a rinneadh don leathanach seo roimh a scriosadh é',
'tooltip-ca-move'                 => 'Athainmnigh an leathanach',
'tooltip-ca-watch'                => 'Cuir an leathanach seo le do liosta faire',
'tooltip-ca-unwatch'              => 'Bain an leathanach seo de do liosta faire',
'tooltip-search'                  => 'Cuardaigh sa vicí seo',
'tooltip-search-go'               => 'Téigh chuig leathanach den ainm cruinn seo má tá sé ann',
'tooltip-search-fulltext'         => 'Cuardaigh an téacs seo sna leathanaigh',
'tooltip-p-logo'                  => 'Príomhleathanach',
'tooltip-n-mainpage'              => 'Tabhair cuairt ar an bPríomhleathanach',
'tooltip-n-mainpage-description'  => 'Tabhair cuairt ar an bpríomhleathanach',
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
'tooltip-t-print'                 => 'Leagan inphriontáilte an leathanaigh seo',
'tooltip-t-permalink'             => 'Nasc buan do leasú seo an leathanaigh',
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
'tooltip-undo'                    => 'Cuirtear "Cealaigh" an t-athrú seo ar cheal agus osclaítear an fhoirm eagair i mód réamhamhairc. Is féidir cúis na hathruithe a chur san achoimre.',

# Stylesheets
'monobook.css' => '/* athraigh an comhad seo chun an craiceann MonoBook a athrú don suíomh ar fad */',

# Metadata
'nodublincore'      => 'Míchumasaítear meitea-shonraí Dublin Core RDF ar an freastalaí seo.',
'nocreativecommons' => 'Míchumasaítear meitea-shonraí Creative Commons RDF ar an freastalaí seo.',
'notacceptable'     => 'Ní féidir leis an freastalaí vicí na sonraí a chur ar fáil i bhformáid atá inléite ag do chliant.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Úsáideoir|Úsáideoirí}} gan ainm ar {{SITENAME}}',
'siteuser'         => 'Úsáideoir $1 ag {{SITENAME}}',
'lastmodifiedatby' => 'Leasaigh $3 an leathanach seo go déanaí ag $2, $1.',
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
'skinname-standard'    => 'Clasaiceach',
'skinname-nostalgia'   => 'Sean-nós',
'skinname-cologneblue' => 'Gorm Köln',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MoChraiceann',
'skinname-chick'       => 'Sicín',
'skinname-simple'      => 'Simplí',
'skinname-modern'      => 'Nua-aimseartha',
'skinname-vector'      => 'Veicteoir',

# Math options
'mw_math_png'    => 'Déan PNG-íomhá gach uair',
'mw_math_simple' => 'Déan HTML má tá sin an-easca, nó PNG ar mhodh eile',
'mw_math_html'   => 'Déan HTML más féidir, nó PNG ar mhodh eile',
'mw_math_source' => 'Fág mar cló TeX (do teacsleitheoirí)',
'mw_math_modern' => 'Inmholta do bhrabhsálaithe nua',
'mw_math_mathml' => 'MathML más féidir (turgnamhach)',

# Math errors
'math_failure'          => 'Theip ó anailís na foirmle',
'math_unknown_error'    => 'earráid anaithnid',
'math_unknown_function' => 'foirmle anaithnid',
'math_lexing_error'     => 'Theip ó anailís an fhoclóra',
'math_syntax_error'     => 'earráid comhréire',
'math_image_error'      => 'Theip ó aistriú an PNG; tástáil má tá na ríomh-oidis latex, dvips, gs, agus convert i suite go maith.',
'math_bad_tmpdir'       => 'Ní féidir scríobh chuig an fillteán mata sealadach, nó é a chruthú',
'math_bad_output'       => 'Ní féidir scríobh chuig an fillteán mata aschomhaid, nó é a chruthú',
'math_notexvc'          => 'Níl an ríomhchlár texvc ann; féach ar mata/EOLAIS chun é a sainathrú.',

# Patrolling
'markaspatrolleddiff'   => 'Comharthaigh mar patrólta.',
'markaspatrolledtext'   => 'Comharthaigh an t-alt seo mar patrólta',
'markedaspatrolled'     => 'Comharthaithe mar patrólta',
'markedaspatrolledtext' => 'Marcáladh an t-athrú roghnaithe de [[:$1]] "patrólaithe".',
'rcpatroldisabled'      => 'Mhíchumasaíodh Patról na n-Athruithe is Déanaí',
'rcpatroldisabledtext'  => 'Tá an tréith Patról na n-Athruithe is Déanaí míchumasaithe faoi láthair.',

# Patrol log
'patrol-log-page'      => 'Log phatról',
'patrol-log-auto'      => '(uathoibríoch)',
'log-show-hide-patrol' => '$1 log phatról',

# Image deletion
'deletedrevision'       => 'Scriosadh an seanleagan $1',
'filedeleteerror-short' => 'Earráid comhad a scriosadh: $1',

# Browsing diffs
'previousdiff' => '← Gabh chuig an difear roimhe seo',
'nextdiff'     => 'An chéad dhifear eile →',

# Media information
'mediawarning'         => "'''Rabhadh''': Tá seans ann go bhfuil cód mailíseach sa chineál comhaid seo.
B'fheidir go gcuirfear do chóras i gcontúirt dá rithfeá é.",
'imagemaxsize'         => "Teorainn mhéid íomhá:<br />''(leathanaigh thuarascáil chomhaid)''",
'thumbsize'            => 'Méid mionsamhlacha:',
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
* isospeedratings
* focallength',

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

'exif-orientation-1' => 'Gnáth',
'exif-orientation-2' => 'Iompaithe go cothrománach',
'exif-orientation-3' => 'Rothlaithe trí 180°',
'exif-orientation-4' => 'Iompaithe go hingearach',
'exif-orientation-5' => 'Rothlaithe trí 90° CCW agus iompaithe go hingearach',
'exif-orientation-6' => 'Rothlaithe trí 90° CW',
'exif-orientation-7' => 'Rothlaithe trí 90° CW agus iompaithe go hingearach',
'exif-orientation-8' => 'Rothlaithe trí 90° CCW',

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

# Pseudotags used for GPSSpeedRef
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
'trackbackremove' => '([$1 Scrios])',

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
'autosumm-new'     => "Leathanach cruthaithe le '$1'",

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
'version'                  => 'Leagan',
'version-other'            => 'Eile',
'version-version'          => '(Leagan $1)',
'version-license'          => 'Ceadúnas',
'version-software'         => 'Bogearraí suiteáilte',
'version-software-version' => 'Leagan',

# Special:FilePath
'filepath'        => 'Cosán comhaid',
'filepath-page'   => 'Comhad:',
'filepath-submit' => 'Gabh',

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
'specialpages-group-pages'     => 'Liostaí leathanaigh',
'specialpages-group-pagetools' => 'Uirslí leathanach',
'specialpages-group-wiki'      => 'Sonraí vicí agus uirslí',
'specialpages-group-spam'      => 'Uirlisí turscar',

# Special:BlankPage
'blankpage' => 'Leathanach bán',

);
