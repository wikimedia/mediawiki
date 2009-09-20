<?php
/** Cornish (Kernowek)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kernoweger
 * @author Kw-Moon
 * @author MF-Warburg
 * @author Malafaya
 * @author Mongvras
 * @author Nicky.ker
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arbennek',
	NS_TALK             => 'Kows',
	NS_USER             => 'Devnydhyer',
	NS_USER_TALK        => 'Kows_Devnydhyer',
	NS_PROJECT_TALK     => 'Kows_$1',
	NS_FILE             => 'Restren',
	NS_FILE_TALK        => 'Kows_Restren',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kows_MediaWiki',
	NS_TEMPLATE         => 'Scantlyn',
	NS_TEMPLATE_TALK    => 'Kows_Scantlyn',
	NS_HELP             => 'Gweres',
	NS_HELP_TALK        => 'Kows_Gweres',
	NS_CATEGORY         => 'Class',
	NS_CATEGORY_TALK    => 'Kows_Class',
);

$namespaceAliases = array(
	'Arbednek' => NS_SPECIAL,
	'Cows' => NS_TALK,
	'Cows_Devnydhyer' => NS_USER_TALK,
	'Cows_$1' => NS_PROJECT_TALK,
	'Cows_Restren' => NS_FILE_TALK,
	'Cows_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Cows_Scantlyn' => NS_TEMPLATE_TALK,
	'Cows_Gweres' => NS_HELP_TALK,
	'Cows_Class' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Watchlist'                 => array( 'Rol golyas' ),
	'Recentchanges'             => array( 'Chanjyow a-dhiwedhes' ),
	'Upload'                    => array( 'Ughcarga' ),
	'Randompage'                => array( 'FolenDreJons' ),
	'Allpages'                  => array( 'OllFolednow' ),
	'Specialpages'              => array( 'FolednowArbednek' ),
	'Contributions'             => array( 'Kevrohow' ),
	'Emailuser'                 => array( 'EbostyaDevnydhyer' ),
	'Movepage'                  => array( 'RemovyaFolen' ),
	'Categories'                => array( 'Classys' ),
	'Export'                    => array( 'Esperthy' ),
	'Version'                   => array( 'Versyon' ),
	'Allmessages'               => array( 'OllMessajow' ),
	'Blockip'                   => array( 'Let' ),
	'Import'                    => array( 'Ymperthy' ),
	'Mypage'                    => array( 'FolenVe' ),
	'Mytalk'                    => array( 'CowsVe' ),
	'Mycontributions'           => array( 'KevrohowVe' ),
	'Search'                    => array( 'Whilas' ),
);

$messages = array(
'underline-never' => 'Jammes',

# Dates
'sunday'        => "De' Sul",
'monday'        => "De' Lun",
'tuesday'       => "De' Meurth",
'wednesday'     => "De' Merher",
'thursday'      => "De' Yow",
'friday'        => "De' Gwener",
'saturday'      => "De' Sadorn",
'sun'           => 'Sul',
'mon'           => 'Lun',
'tue'           => 'Meu',
'wed'           => 'Mer',
'thu'           => 'Yow',
'fri'           => 'Gwe',
'sat'           => 'Sad',
'january'       => 'Genver',
'february'      => 'Whevrel',
'march'         => 'Meurth',
'april'         => 'Ebryl',
'may_long'      => 'Me',
'june'          => 'Metheven',
'july'          => 'Gortheren',
'august'        => 'Est',
'september'     => 'Gwedngala',
'october'       => 'Hedra',
'november'      => 'Du',
'december'      => 'Kevardhu',
'january-gen'   => 'Genver',
'february-gen'  => 'Whevrel',
'march-gen'     => 'Meurth',
'april-gen'     => 'Ebryl',
'may-gen'       => 'Me',
'june-gen'      => 'Metheven',
'july-gen'      => 'Gortheren',
'august-gen'    => 'Est',
'september-gen' => 'Gwedngala',
'october-gen'   => 'Hedra',
'november-gen'  => 'Du',
'december-gen'  => 'Kevardhu',
'jan'           => 'Gen',
'feb'           => 'Whe',
'mar'           => 'Meu',
'apr'           => 'Ebr',
'may'           => 'Me',
'jun'           => 'Met',
'jul'           => 'Gor',
'aug'           => 'Est',
'sep'           => 'Gwe',
'oct'           => 'Hed',
'nov'           => 'Du',
'dec'           => 'Kev',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Class|Class}}',
'category_header'          => 'Folednow e\'n class "$1"',
'subcategories'            => 'Is-classys',
'category-media-header'    => 'Media e\'n class "$1"',
'hidden-categories'        => '{{PLURAL:$1|Class covys|Class covys}}',
'hidden-category-category' => 'Classys covys',
'listingcontinuesabbrev'   => 'pes.',

'about'         => 'A-dro dhe',
'cancel'        => 'Hedhy',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Ow folen',
'mytalk'        => 'Ow hows',
'anontalk'      => 'Cows rag an trigva IP ma',
'navigation'    => 'Lowyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Cawas',
'qbbrowse'       => 'Peury',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen ma',
'qbmyoptions'    => 'Ow folednow',
'qbspecialpages' => 'Folednow arbednek',

# Vector skin
'vector-action-addsection'   => 'Keworra mater',
'vector-action-delete'       => 'Dilea',
'vector-action-move'         => 'Removya',
'vector-action-protect'      => 'Difres',
'vector-action-undelete'     => 'Disdhilea',
'vector-action-unprotect'    => 'Disdhifres',
'vector-namespace-category'  => 'Class',
'vector-namespace-help'      => 'Gweres',
'vector-namespace-image'     => 'Restren',
'vector-namespace-main'      => 'Folen',
'vector-namespace-media'     => 'Folen media',
'vector-namespace-mediawiki' => 'Messaj',
'vector-namespace-project'   => 'Folen an towl',
'vector-namespace-special'   => 'Folen arbednek',
'vector-namespace-talk'      => 'Kescows',
'vector-namespace-template'  => 'Scantlyn',
'vector-namespace-user'      => 'Folen devnydhyer',
'vector-view-create'         => 'Gruthyl',
'vector-view-edit'           => 'Chanjya',
'vector-view-history'        => 'Gweles istory an folen',
'vector-view-view'           => 'Redya',
'vector-view-viewsource'     => 'Gweles pednfenten',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Gwall',
'tagline'           => 'Dhort {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Whilas',
'searchbutton'      => 'Whila',
'go'                => 'Ke',
'searcharticle'     => 'Ke',
'history'           => 'Istory an folen',
'history_short'     => 'Istory',
'info_short'        => 'Kedhlow',
'printableversion'  => 'Versyon pryntyadow',
'permalink'         => 'Kevren fast',
'print'             => 'Pryntya',
'edit'              => 'Chanjya',
'create'            => 'Gruthyl',
'editthispage'      => 'Chanjya an folen ma',
'create-this-page'  => 'Gruthyl an folen ma',
'delete'            => 'Dilea',
'deletethispage'    => 'Dilea an folen ma',
'undelete_short'    => 'Disdhilea $1 chanj',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen ma',
'unprotect'         => 'Disdhifres',
'unprotectthispage' => 'Disdhifres an folen ma',
'newpage'           => 'Folen nowyth',
'talkpage'          => "Dadhelva a-dro dhe'n folen ma",
'talkpagelinktext'  => 'kows',
'specialpage'       => 'Folen arbednek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Tregh nowyth',
'talk'              => 'Kescows',
'toolbox'           => 'Box toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajow',
'templatepage'      => 'Gweles folen an scantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an class',
'viewtalkpage'      => 'Gweles an cows',
'otherlanguages'    => 'Tavosow erel',
'jumpto'            => 'Labma dhe:',
'jumptonavigation'  => 'lowyans',
'jumptosearch'      => 'whilas',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Derivadow',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lebmyn',
'currentevents-url'    => 'Project:Darvosow a-lebmyn',
'disclaimers'          => 'Avisyansow',
'disclaimerpage'       => 'Project:Avisyans ollgebmyn',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Pednfolen',
'mainpage-description' => 'Pednfolen',
'policy-url'           => 'Project:Policy',
'portal'               => 'Porth kebmynieth',
'portal-url'           => 'Project:Porth kebmynieth',
'privacy'              => 'Policy privetter',
'privacypage'          => 'Project:Policy privetter',

'ok'                      => 'Sur',
'youhavenewmessages'      => 'Ma $1 genowgh ($2).',
'newmessageslink'         => 'messajys nowyth',
'newmessagesdifflink'     => 'chanj kyns',
'youhavenewmessagesmulti' => 'Ma messajow nowyth genowgh war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an pednfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an fenten',
'editsectionhint'         => 'Chanjya an tregh: $1',
'showtoc'                 => 'diskwedhes',
'hidetoc'                 => 'cudha',
'viewdeleted'             => 'Gweles $1?',
'red-link-title'          => '$1 (nag eus folen henwys yndelma na whath)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygel',
'nstab-user'      => 'Folen devnydhyer',
'nstab-media'     => 'Folen media',
'nstab-special'   => 'Folen arbednek',
'nstab-project'   => 'Folen towl',
'nstab-image'     => 'Restren',
'nstab-mediawiki' => 'Messaj',
'nstab-template'  => 'Scantlyn',
'nstab-help'      => 'Gweres',
'nstab-category'  => 'Class',

# General errors
'error'               => 'Gwall',
'missingarticle-diff' => '(Dyffrans: $1, $2)',
'badtitle'            => 'Titel drog',
'viewsource'          => 'Gweles fenten',
'viewsourcefor'       => 'rag $1',

# Login and logout pages
'yourname'                => 'Hanow-usyer:',
'yourpassword'            => 'Ger-tremena:',
'yourpasswordagain'       => 'Jynnscrifa agas ger-tremena arta:',
'yourdomainname'          => 'Agas diredh:',
'login'                   => 'Omgelmy',
'nav-login-createaccount' => 'Omgelmy / Formya acont nowyth',
'loginprompt'             => "Res porres gasa 'cookies' rag omgelmy worth {{SITENAME}}.",
'userlogin'               => 'Omgelmy / formya acont nowyth',
'logout'                  => 'Digelmy',
'userlogout'              => 'Digelmy',
'notloggedin'             => 'Digelmys',
'nologin'                 => 'Heb acont dhewgh? $1.',
'nologinlink'             => 'Form acont nowyth',
'createaccount'           => 'Form acont nowyth',
'gotaccount'              => 'Eus acont dhewgh seulabrys? $1.',
'gotaccountlink'          => 'Omgelmy',
'createaccountmail'       => 'dre e-bost',
'mailmypassword'          => 'E-bostya ger-tremena nowyth',
'loginlanguagelabel'      => 'Yeth: $1',

# Password reset dialog
'resetpass'                 => 'Chanjya ger-tremena',
'resetpass_header'          => 'Chanjya ger-tremena an acont',
'oldpassword'               => 'Ger-tremena coth:',
'newpassword'               => 'Ger-tremena nowyth:',
'resetpass-submit-loggedin' => 'Chanjya an ger-tremena',

# Edit page toolbar
'bold_sample'    => 'Text tew',
'bold_tip'       => 'Text tew',
'italic_sample'  => 'Text italek',
'italic_tip'     => 'Text italek',
'link_sample'    => 'Titel an kevren',
'link_tip'       => 'Kevren bervedhel',
'extlink_sample' => 'http://www.example.com titel an kevren',
'image_tip'      => 'Restren neythys',
'media_tip'      => 'Kevren restren',

# Edit pages
'minoredit'          => 'Hebm ew chanj bian',
'watchthis'          => 'Golya an folen ma',
'savearticle'        => 'Gwitha',
'preview'            => 'Ragwel',
'showpreview'        => 'Diskwedhes ragwel',
'showdiff'           => 'Diskwedhes chanjyow',
'loginreqlink'       => 'omgelmy',
'newarticle'         => '(Nowyth)',
'note'               => "'''Noten:'''",
'editing'            => 'Ow chanjya $1',
'editingsection'     => 'Ow chanjya $1 (tregh)',
'editingcomment'     => 'Ow chanjya $1 (tregh nowyth)',
'yourtext'           => 'Agas text',
'yourdiff'           => 'Dyffransow',
'template-protected' => '(gwithys)',

# History pages
'next'                   => 'nessa',
'last'                   => 'kyns',
'page_first'             => 'kynsa',
'page_last'              => 'kyns',
'history-fieldset-title' => 'Peury an istory',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision deletion
'rev-delundel'    => 'diskwedhes/cudha',
'pagehist'        => 'Istori an folen',
'revdelete-uname' => 'hanow-usyer',

# History merging
'mergehistory-reason' => 'Acheson:',

# Diffs
'history-title' => 'Istory an folen "$1"',
'lineno'        => 'Linen $1:',
'editundo'      => 'diswul',
'diff-movedto'  => 'removys dhe $1',
'diff-added'    => '$1 keworrys',
'diff-removed'  => '$1 dileys',
'diff-width'    => 'les',
'diff-height'   => 'ughelder',
'diff-b'        => "'''tew'''",
'diff-strong'   => "'''crev'''",
'diff-font'     => "'''font'''",
'diff-big'      => "'''broas'''",
'diff-sub'      => "'''isscrif'''",
'diff-sup'      => "'''gorscrif'''",
'diff-strike'   => "'''treuslinys'''",

# Search results
'searchresults'                  => 'Sewyansow whilans',
'searchresults-title'            => 'Sewyansow whilans rag "$1"',
'noexactmatch'                   => "'''Nag eus folen henwys \"\$1\".'''
Whei a ell [[:\$1|gruthyl an folen ma]].",
'noexactmatch-nocreate'          => "'''Nag eus folen henwys \"\$1\".'''",
'prevn'                          => 'kyns {{PLURAL:$1|$1}}',
'nextn'                          => 'nessa {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Gweles ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'              => 'Dewisyansow whilas',
'searchmenu-exists'              => "'''Ma folen henwys \"[[:\$1]]\" war an wiki ma'''",
'searchmenu-new'                 => "'''Gruthyl an folen \"[[:\$1]]\" war an wiki ma!'''",
'searchhelp-url'                 => 'Help:Gweres',
'searchprofile-images'           => 'Liesmedia',
'searchprofile-everything'       => 'Puptra',
'searchprofile-advanced'         => 'Avoncys',
'searchprofile-articles-tooltip' => 'Whila en $1',
'searchprofile-project-tooltip'  => 'Whila en $1',
'searchprofile-images-tooltip'   => 'Whila restrennow',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-section'                 => '(tregh $1)',
'search-interwiki-caption'       => 'Towlow whor',
'search-interwiki-default'       => '$1 sewyansow:',
'search-interwiki-more'          => '(moy)',
'searchall'                      => 'oll',
'powersearch'                    => 'Whilans avoncys',
'powersearch-legend'             => 'Whilans avoncys',
'powersearch-field'              => 'Whila',
'powersearch-toggleall'          => 'Oll',

# Preferences page
'preferences'                 => 'Teythi ow acont',
'mypreferences'               => 'Teythi ow acont',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Krohen',
'prefs-datetime'              => 'Dedh hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Dewisyansow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Whilas',
'timezoneregion-africa'       => 'Africa',
'timezoneregion-america'      => 'America',
'timezoneregion-antarctica'   => 'Antarctica',
'timezoneregion-arctic'       => 'Arctik',
'timezoneregion-asia'         => 'Asi',
'timezoneregion-atlantic'     => 'Keynvor Atlantek',
'timezoneregion-australia'    => 'Awstralya',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Keynvor Eyndek',
'timezoneregion-pacific'      => 'Keynvor Hebask',
'prefs-searchoptions'         => 'Dewisyansow whilas',
'prefs-files'                 => 'Restrednow',
'youremail'                   => 'E-bost:',
'username'                    => 'Hanow-usyer:',
'uid'                         => 'ID devnydhyer:',
'prefs-memberingroups'        => "Esel a'n {{PLURAL:$1|bagas|bagasow}}:",
'yourrealname'                => 'Hanow gwir:',
'yourlanguage'                => 'Yeth:',
'yournick'                    => 'Hanowscrif nowyth:',
'yourgender'                  => 'Reyth:',
'gender-male'                 => 'Gorow',
'gender-female'               => 'Benow',
'email'                       => 'E-bost',
'prefs-signature'             => 'Sinans',
'prefs-advancedediting'       => 'Dewisyansow avoncys',
'prefs-advancedrc'            => 'Dewisyansow avoncys',
'prefs-advancedrendering'     => 'Dewisyansow avoncys',
'prefs-advancedsearchoptions' => 'Dewisyansow avoncys',
'prefs-advancedwatchlist'     => 'Dewisyansow avoncys',

# User rights
'userrights-groupsmember' => 'Esel a:',
'userrights-reason'       => 'Acheson rag chanj:',

# Groups
'group'       => 'Bagas:',
'group-user'  => 'Devnydhyow',
'group-bot'   => 'Botow',
'group-sysop' => 'Menysteryon',
'group-all'   => '(oll)',

'group-user-member'  => 'Devnydhyer',
'group-bot-member'   => 'Bot',
'group-sysop-member' => 'Menyster',

'grouppage-user'  => '{{ns:project}}:Devnydhyoryon',
'grouppage-bot'   => '{{ns:project}}:Botow',
'grouppage-sysop' => '{{ns:project}}:Menysteryon',

# Rights
'right-read'          => 'Redya folednow',
'right-edit'          => 'Chanjya folednow',
'right-createtalk'    => 'Gruthyl folednow kescows',
'right-createaccount' => 'Gruthyl acontow devnydhyer nowyth',
'right-move'          => 'Removya folednow',
'right-movefile'      => 'Removya restrednow',
'right-upload'        => 'Ughcarga restrednow',
'right-delete'        => 'Dilea folednow',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'chanjya an folen ma',
'action-move'     => 'removya an folen ma',
'action-movefile' => 'removya an restren ma',
'action-upload'   => 'ughcarga an restren ma',
'action-delete'   => 'dilea an folen ma',

# Recent changes
'nchanges'                     => '$1 {{PLURAL:$1|chanj|chanj}}',
'recentchanges'                => 'Chanjyow a-dhiwedhes',
'recentchanges-legend'         => 'Dewisyansow an chanjyow a-dhiwedhes',
'recentchanges-legend-newpage' => '$1 - folen nowyth',
'recentchanges-legend-minor'   => '$1 - chanj bian',
'recentchanges-label-minor'    => 'Hebm ew chanj bian',
'recentchanges-legend-bot'     => '$1 - chanj gen bot',
'rclistfrom'                   => 'Diskwedhes chanjyow nowyth ow talleth dhort $1.',
'rcshowhideminor'              => '$1 chanjyow bian',
'rcshowhidebots'               => '$1 botow',
'rcshowhideanons'              => '$1 devnydhyoryon dyhanow',
'rcshowhidemine'               => '$1 ow chanjyow',
'diff'                         => 'dyffrans',
'hist'                         => 'ist',
'hide'                         => 'Cudha',
'show'                         => 'Diskwedhes',
'minoreditletter'              => 'b',
'newpageletter'                => 'N',
'boteditletter'                => 'bot',
'newsectionsummary'            => '/* $1 */ tregh nowyth',
'rc-enhanced-hide'             => 'Cudha manylyon',

# Recent changes linked
'recentchangeslinked'         => 'Chanjyow dhe folednow kevahal',
'recentchangeslinked-feed'    => 'Chanjyow dhe folednow kevahal',
'recentchangeslinked-toolbox' => 'Chanjyow dhe folednow kevahal',
'recentchangeslinked-page'    => 'Hanow an folen:',

# Upload
'upload'          => 'Ughcarga restren',
'uploadbtn'       => 'Ughcarga restren',
'filename'        => 'Hanow-restren',
'filesource'      => 'Pednfenten:',
'savefile'        => 'Gwitha restren',
'uploadedimage'   => '"[[$1]]" ughcargys',
'watchthisupload' => 'Golya an folen ma',

# Special:ListFiles
'imgfile'        => 'restren',
'listfiles_date' => 'Dedh',
'listfiles_name' => 'Hanow',
'listfiles_user' => 'Devnydhyer',

# File description page
'file-anchor-link'    => 'Restren',
'filehist'            => 'Istory an folen',
'filehist-deleteall'  => 'dilea oll',
'filehist-deleteone'  => 'dilea',
'filehist-datetime'   => 'Dedh/Eur',
'filehist-user'       => 'Devnydhyer',
'filehist-dimensions' => 'Mynsow',
'imagelinks'          => "Kevrednow dhe'n restren ma",

# File deletion
'filedelete'        => 'Dilea $1',
'filedelete-legend' => 'Dilea an restren',
'filedelete-submit' => 'Dilea',

# MIME search
'download' => 'iscarga',

# Unused templates
'unusedtemplateswlh' => 'kevrednow erel',

# Random page
'randompage' => 'Folen dre jons',

# Statistics
'statistics-pages' => 'Folednow',

'brokenredirects-edit'   => 'chanjya',
'brokenredirects-delete' => 'dilea',

'withoutinterwiki-submit' => 'Diskwedhes',

# Miscellaneous special pages
'nmembers'          => '$1 {{PLURAL:$1|esel|esel}}',
'newpages'          => 'Folednow nowyth',
'newpages-username' => 'Hanow-usyer:',
'move'              => 'Removya',
'movethispage'      => 'Removya an folen ma',
'pager-newer-n'     => '{{PLURAL:$1|1 nowyttha|$1 nowyttha}}',
'pager-older-n'     => '{{PLURAL:$1|1 cottha|$1 cottha}}',

# Book sources
'booksources'    => 'Pedn-fentynyow lever',
'booksources-go' => 'Ke',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',

# Special:AllPages
'allpages'       => 'Oll folednow',
'alphaindexline' => '$1 dhe $2',
'prevpage'       => 'Folen gyns ($1)',
'allarticles'    => 'Oll folednow',
'allpagesprev'   => 'Kyns',
'allpagesnext'   => 'Nessa',
'allpagessubmit' => 'Ke',

# Special:Categories
'categories' => 'Classys',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch-ok' => 'Whila',

# Special:ListUsers
'listusers-submit' => 'Diskwedhes',

# Special:Log/newusers
'newuserlog-create-entry' => 'Devnydhyer nowyth',

# E-mail user
'emailuser'       => 'E-bostya an devnydhyer ma',
'emailpage'       => 'E-bostya devnydhyer',
'defemailsubject' => 'E-bost {{SITENAME}}',
'emailfrom'       => 'Dhort:',
'emailto'         => 'Dhe:',
'emailmessage'    => 'Messaj:',
'emailsend'       => 'Danon',

# Watchlist
'watchlist'         => 'Ow rol golyas',
'mywatchlist'       => 'Ow rol golyas',
'watchlistfor'      => "(rag '''$1''')",
'addedwatch'        => 'Keworrys dhe rol golyas',
'watch'             => 'Golya',
'watchthispage'     => 'Golya an folen ma',
'unwatch'           => 'Diswolyas',
'watchlist-options' => 'Dewisyansow an rol golyas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ow kolya...',
'unwatching' => 'Ow tisgolya...',

# Delete
'deletepage'            => 'Dilea an folen',
'delete-confirm'        => 'Dilea "$1"',
'delete-legend'         => 'Dilea',
'deletedarticle'        => '"[[$1]]" dileys',
'deletereasonotherlist' => 'Acheson aral',

# Protect
'protect-level-sysop' => 'Menysteryon hepken',
'restriction-type'    => 'Cubmyas:',
'pagesize'            => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Chanjya',
'restriction-move'   => 'Removya',
'restriction-create' => 'Gruthyl',
'restriction-upload' => 'Ughcarga',

# Undelete
'undelete-search-submit' => 'Whila',

# Namespace form on various pages
'blanknamespace' => '(Pedn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow an devnydhyer rag $1',
'mycontris'           => 'Ow hevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'Dhort mis (ha moy a-varr):',
'year'                => 'Dhort bledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Diskwedhes hepken kevrohow an devnydhyoryon nowyth',
'sp-contributions-talk'     => 'cows',
'sp-contributions-search'   => 'Whila kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-submit'   => 'Whila',

# What links here
'whatlinkshere'           => 'Folednow ow kevredna bys obma',
'whatlinkshere-title'     => 'Folednow ow kevredna bys "$1"',
'whatlinkshere-page'      => 'Folen:',
'isimage'                 => 'kevren an imaj',
'whatlinkshere-prev'      => '{{PLURAL:$1|kyns|kyns $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'     => '← kevrednow',
'whatlinkshere-hidelinks' => '$1 kevrednow',
'whatlinkshere-filters'   => 'Sidhlow',

# Block/unblock
'blockip'                => 'Let devnydhyer',
'ipb-blocklist-contribs' => 'Kevrohow rag $1',
'ipblocklist-submit'     => 'Whila',
'blocklink'              => 'let',
'contribslink'           => 'kevrohow',

# Move page
'move-page'        => 'Removya $1',
'move-page-legend' => 'Removya an folen',
'movearticle'      => 'Removya an folen:',
'newtitle'         => 'Dhe titel nowyth:',
'move-watch'       => 'Golya an folen ma',
'movepagebtn'      => 'Removya an folen',
'movedto'          => 'removys dhe',
'1movedto2'        => '[[$1]] removys dhe [[$2]]',
'movereason'       => 'Acheson:',

# Export
'export' => 'Esperthy folednow',

# Thumbnails
'thumbnail-more' => 'Brashe',

# Special:Import
'import-interwiki-submit' => 'Ymperthy',
'import-upload-filename'  => 'Hanow-restren:',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Agas folen devnydhyer',
'tooltip-pt-mytalk'         => 'Agas folen gows',
'tooltip-pt-preferences'    => 'Teythi ow acont',
'tooltip-pt-mycontris'      => "Rol a'gas kevrohow",
'tooltip-pt-logout'         => 'Omdedna',
'tooltip-ca-addsection'     => 'Dalleth tregh nowyth',
'tooltip-ca-protect'        => 'Difres an folen ma',
'tooltip-ca-delete'         => 'Dilea an folen ma',
'tooltip-ca-move'           => 'Removya an folen ma',
'tooltip-ca-watch'          => "Keworra an folen ma dh'agas rol golyas",
'tooltip-search'            => 'Whila {{SITENAME}}',
'tooltip-search-fulltext'   => "Whila an text ma e'n folednow",
'tooltip-n-mainpage'        => 'Diskwedhes an pednfolen',
'tooltip-n-recentchanges'   => "Rol an chanjyow a-dhiwedhes e'n wiki",
'tooltip-n-randompage'      => 'Carga folen dre jons',
'tooltip-t-whatlinkshere'   => 'Rol a bub folednow wiki ow kevredna bys obma',
'tooltip-t-contributions'   => 'Gweles rol kevrohow an devnydhyer ma',
'tooltip-t-emailuser'       => 'Danon e-bost war an devnydhyer ma',
'tooltip-t-upload'          => 'Ughcarga restrednow',
'tooltip-t-specialpages'    => 'Rol a bub folen arbednek',
'tooltip-t-print'           => 'Versyon pryntyadow an folen ma',
'tooltip-ca-nstab-user'     => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-project'  => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'    => 'Gweles folen an restren',
'tooltip-ca-nstab-template' => 'Gweles an scantlyn',
'tooltip-ca-nstab-category' => 'Gweles folen an class',
'tooltip-save'              => 'Gwitha agas chanjyow',

# Attribution
'siteuser'  => 'devnydhyer {{SITENAME}} $1',
'siteusers' => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Media information
'show-big-image' => 'Clerder leun',

# Special:NewFiles
'ilsubmit' => 'Whila',

# Metadata
'metadata' => 'Metadata',

# EXIF tags
'exif-imagewidth'  => 'Les',
'exif-imagelength' => 'Ughelder',
'exif-artist'      => 'Awtour',

'exif-meteringmode-255' => 'Aral',

'exif-contrast-1' => 'Medhel',
'exif-contrast-2' => 'Cales',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'oll',
'imagelistall'     => 'oll',
'watchlistall2'    => 'oll',
'namespacesall'    => 'oll',
'monthsall'        => 'oll',

# Trackbacks
'trackbackremove' => '([$1 Dilea])',

# Multipage image navigation
'imgmultipageprev' => '← folen kyns',
'imgmultipagenext' => 'folen nessa →',
'imgmultigo'       => 'Ke!',

# Table pager
'table_pager_limit_submit' => 'Ke',

# Special:Version
'version'         => 'Versyon',
'version-other'   => 'Aral',
'version-version' => '(Versyon $1)',

# Special:FilePath
'filepath-page' => 'Restren:',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Hanow-restren:',
'fileduplicatesearch-submit'   => 'Whila',

# Special:SpecialPages
'specialpages' => 'Folednow arbednek',

# Special:Tags
'tags-edit' => 'chanjya',

);
