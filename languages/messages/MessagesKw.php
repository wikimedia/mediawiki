<?php
/** Cornish (Kernewek)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Kw-Moon
 * @author MF-Warburg
 * @author Malafaya
 * @author Mongvras
 * @author Nicky.ker
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arbednek',
	NS_TALK             => 'Cows',
	NS_USER             => 'Devnydhyer',
	NS_USER_TALK        => 'Cows Devnydhyer',
	NS_PROJECT_TALK     => 'Cows_$1',
	NS_FILE             => 'Restren',
	NS_FILE_TALK        => 'Cows_Restren',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Cows_MediaWiki',
	NS_TEMPLATE         => 'Scantlyn',
	NS_TEMPLATE_TALK    => 'Cows_Scantlyn',
	NS_HELP             => 'Gweres',
	NS_HELP_TALK        => 'Cows_Gweres',
	NS_CATEGORY         => 'Class',
	NS_CATEGORY_TALK    => 'Cows_Class',
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
'sunday'        => "Dy' Sul",
'monday'        => "Dy' Lun",
'tuesday'       => "Dy' Meurth",
'wednesday'     => "Dy' Merher",
'thursday'      => "Dy' Yow",
'friday'        => "Dy' Gwener",
'saturday'      => "Dy' Sadorn",
'sun'           => 'Sul',
'mon'           => 'Lun',
'tue'           => 'Meu',
'wed'           => 'Mer',
'thu'           => 'Yow',
'fri'           => 'Gwe',
'sat'           => 'Sad',
'january'       => 'Genver',
'february'      => 'Hwevrer',
'march'         => 'Meurth',
'april'         => 'Ebryl',
'may_long'      => 'Me',
'june'          => 'Metheven',
'july'          => 'Gortheren',
'august'        => 'Est',
'september'     => 'Gwynngala',
'october'       => 'Hedra',
'november'      => 'Du',
'december'      => 'Kevardhu',
'january-gen'   => 'Genver',
'february-gen'  => 'Hwevrer',
'march-gen'     => 'Meurth',
'april-gen'     => 'Ebryl',
'may-gen'       => 'Me',
'june-gen'      => 'Metheven',
'july-gen'      => 'Gortheren',
'august-gen'    => 'Est',
'september-gen' => 'Gwynngala',
'october-gen'   => 'Hedra',
'november-gen'  => 'Du',
'december-gen'  => 'Kevardhu',
'jan'           => 'Gen',
'feb'           => 'Hwe',
'mar'           => 'Meu',
'apr'           => 'Ebr',
'may'           => 'Me',
'jun'           => 'Met',
'jul'           => 'Gor',
'aug'           => 'Est',
'sep'           => 'Gwy',
'oct'           => 'Hed',
'nov'           => 'Du',
'dec'           => 'Kev',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Klass|Klass}}',
'category_header'          => 'Folennow y\'n klass "$1"',
'subcategories'            => 'Is-klasyansow',
'category-media-header'    => 'Media y\'n klass "$1"',
'hidden-categories'        => '{{PLURAL:$1|Klass kovys|Klass kovys}}',
'hidden-category-category' => 'Klasyansow kudh',
'listingcontinuesabbrev'   => 'pes.',

'about'         => 'A-dro dhe',
'cancel'        => 'Hedhi',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Ow folen',
'mytalk'        => 'Ow hows',
'anontalk'      => 'Kows rag an trigva IP ma',
'navigation'    => 'Lewyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Kavos',
'qbbrowse'       => 'Peuri',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen ma',
'qbmyoptions'    => 'Ow folennow',
'qbspecialpages' => 'Folennow arbennek',

# Vector skin
'vector-action-addsection'   => 'Keworra mater',
'vector-action-delete'       => 'Dilea',
'vector-action-move'         => 'Removya',
'vector-action-protect'      => 'Difres',
'vector-action-undelete'     => 'Disdhilea',
'vector-action-unprotect'    => 'Disdhifres',
'vector-namespace-category'  => 'Klass',
'vector-namespace-help'      => 'Gweres',
'vector-namespace-image'     => 'Restren',
'vector-namespace-main'      => 'Folen',
'vector-namespace-media'     => 'Folen media',
'vector-namespace-mediawiki' => 'Messaj',
'vector-namespace-project'   => 'Folen an towl',
'vector-namespace-special'   => 'Folen arbennek',
'vector-namespace-talk'      => 'Keskows',
'vector-namespace-template'  => 'Skantlyn',
'vector-namespace-user'      => 'Folen devnydhyer',
'vector-view-create'         => 'Gwruthyl',
'vector-view-edit'           => 'Chanjya',
'vector-view-history'        => 'Gweles istori an folen',
'vector-view-view'           => 'Redya',
'vector-view-viewsource'     => 'Gweles pennfenten',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Gwall',
'tagline'           => 'Dhyworth {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Hwilas',
'searchbutton'      => 'Hwila',
'go'                => 'Ke',
'searcharticle'     => 'Ke',
'history'           => 'Istori an folen',
'history_short'     => 'Istori',
'info_short'        => 'Kedhlow',
'printableversion'  => 'Versyon pryntyadow',
'permalink'         => 'Kevren stag',
'print'             => 'Pryntya',
'edit'              => 'Chanjya',
'create'            => 'Gwruthyl',
'editthispage'      => 'Chanjya an folen ma',
'create-this-page'  => 'Gwruthyl an folen ma',
'delete'            => 'Dilea',
'deletethispage'    => 'Dilea an folen ma',
'undelete_short'    => 'Disdhilea {{PLURAL:$1|unn chanj|$1 chanj}}',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen ma',
'unprotect'         => 'Disdhifres',
'unprotectthispage' => 'Disdhifres an folen ma',
'newpage'           => 'Folen nowyth',
'talkpage'          => "Dadhelva a-dro dhe'n folen ma",
'talkpagelinktext'  => 'Kows',
'specialpage'       => 'Folen arbennek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Tregh nowyth',
'talk'              => 'Keskows',
'toolbox'           => 'Box toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajow',
'templatepage'      => 'Gweles folen an skantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an klass',
'viewtalkpage'      => 'Gweles an kows',
'otherlanguages'    => 'Tavosow erel',
'jumpto'            => 'Lamma dhe:',
'jumptonavigation'  => 'lewyans',
'jumptosearch'      => 'hwilas',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Derivadow',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lemmyn',
'currentevents-url'    => 'Project:Darvosow a-lemmyn',
'disclaimers'          => 'Avisyansow',
'disclaimerpage'       => 'Project:Avisyans ollgemmyn',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Pednfolen',
'mainpage-description' => 'Pednfolen',
'policy-url'           => 'Project:Polisi',
'portal'               => 'Porth kebmynieth',
'portal-url'           => 'Project:Porth kebmynieth',
'privacy'              => 'Polisi privetter',
'privacypage'          => 'Project:Polisi privetter',

'ok'                      => 'Sur',
'youhavenewmessages'      => 'Yma $1 dhis ($2).',
'newmessageslink'         => 'messajys nowyth',
'newmessagesdifflink'     => 'chanj kyns',
'youhavenewmessagesmulti' => 'Yma messajow nowyth dhis war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an pennfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an fenten',
'editsectionhint'         => 'Chanjya an tregh: $1',
'showtoc'                 => 'diskwedhes',
'hidetoc'                 => 'kudha',
'viewdeleted'             => 'Gweles $1?',
'red-link-title'          => '$1 (nyns eus folen henwys yndelma na hwath)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygel',
'nstab-user'      => 'Folen devnydhyer',
'nstab-media'     => 'Folen media',
'nstab-special'   => 'Folen arbennek',
'nstab-project'   => 'Folen towl',
'nstab-image'     => 'Restren',
'nstab-mediawiki' => 'Messaj',
'nstab-template'  => 'Skantlyn',
'nstab-help'      => 'Gweres',
'nstab-category'  => 'Klass',

# General errors
'error'               => 'Gwall',
'missingarticle-diff' => '(Dyffrans: $1, $2)',
'badtitle'            => 'Titel drog',
'viewsource'          => 'Gweles fenten',
'viewsourcefor'       => 'rag $1',

# Login and logout pages
'yourname'                => 'Hanow-usyer:',
'yourpassword'            => 'Ger-tremena:',
'yourpasswordagain'       => 'Jynnskrifa an ger-tremena arta:',
'yourdomainname'          => 'Dha diredh:',
'login'                   => 'Omgelmi',
'nav-login-createaccount' => 'Omgelmi / Formya akont nowyth',
'loginprompt'             => "Res porres gasa 'cookies' rag omgelmi worth {{SITENAME}}.",
'userlogin'               => 'Omgelmi / formya akont nowyth',
'logout'                  => 'Digelmi',
'userlogout'              => 'Digelmi',
'notloggedin'             => 'Digelmys',
'nologin'                 => 'Heb akont dhis? $1.',
'nologinlink'             => 'Form akont nowyth',
'createaccount'           => 'Form akont nowyth',
'gotaccount'              => 'Eus akont dhis seulabrys? $1.',
'gotaccountlink'          => 'Rag omgelmi',
'createaccountmail'       => 'dre e-bost',
'mailmypassword'          => 'E-bostya ger-tremena nowyth',
'loginlanguagelabel'      => 'Yeth: $1',

# Password reset dialog
'resetpass'                 => 'Chanjya ger-tremena',
'resetpass_header'          => 'Chanjya ger-tremena an akont',
'oldpassword'               => 'Ger-tremena koth:',
'newpassword'               => 'Ger-tremena nowyth:',
'resetpass-submit-loggedin' => 'Chanjya an ger-tremena',

# Edit page toolbar
'bold_sample'    => 'Tekst tew',
'bold_tip'       => 'Tekst tew',
'italic_sample'  => 'Tekst italek',
'italic_tip'     => 'Tekst italek',
'link_sample'    => 'Titel an kevren',
'link_tip'       => 'Kevren bervedhel',
'extlink_sample' => 'http://www.example.com titel an kevren',
'image_tip'      => 'Restren neythys',
'media_tip'      => 'Kevren restren',

# Edit pages
'minoredit'          => 'Hemm yw chanj bian',
'watchthis'          => 'Golya an folen ma',
'savearticle'        => 'Gwitha',
'preview'            => 'Ragwel',
'showpreview'        => 'Diskwedhes ragwel',
'showdiff'           => 'Diskwedhes chanjyow',
'loginreqlink'       => 'omgelmi',
'newarticle'         => '(Nowyth)',
'note'               => "'''Noten:'''",
'editing'            => 'Ow chanjya $1',
'editingsection'     => 'Ow chanjya $1 (tregh)',
'editingcomment'     => 'Ow chanjya $1 (tregh nowyth)',
'yourtext'           => 'Dha dekst',
'yourdiff'           => 'Dyffransow',
'template-protected' => '(gwithys)',

# History pages
'next'                   => 'nessa',
'last'                   => 'kyns',
'page_first'             => 'kynsa',
'page_last'              => 'kyns',
'history-fieldset-title' => 'Peuri an istori',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision deletion
'rev-delundel'    => 'diskwedhes/kudha',
'pagehist'        => 'Istori an folen',
'revdelete-uname' => 'hanow-usyer',

# History merging
'mergehistory-reason' => 'Acheson:',

# Diffs
'history-title' => 'Istori an folen "$1"',
'lineno'        => 'Linen $1:',
'editundo'      => 'diswul',
'diff-movedto'  => 'removys dhe $1',
'diff-added'    => '$1 keworrys',
'diff-removed'  => '$1 dileys',
'diff-width'    => 'les',
'diff-height'   => 'ughelder',
'diff-b'        => "'''tew'''",
'diff-strong'   => "'''krev'''",
'diff-font'     => "'''font'''",
'diff-big'      => "'''bras'''",
'diff-sub'      => "'''isskrif'''",
'diff-sup'      => "'''gorskrif'''",
'diff-strike'   => "'''treuslinys'''",

# Search results
'searchresults'                  => 'Sewyansow hwilans',
'searchresults-title'            => 'Sewyansow hwilans rag "$1"',
'noexactmatch'                   => "'''Nyns eus folen henwys \"\$1\".'''
Ty a yll [[:\$1|gwruthyl an folen ma]].",
'noexactmatch-nocreate'          => "'''Nyns eus folen henwys \"\$1\".'''",
'prevn'                          => 'kyns {{PLURAL:$1|$1}}',
'nextn'                          => 'nessa {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Gweles ($1) ($2) ($3)',
'searchmenu-legend'              => 'Dewisyansow hwilas',
'searchmenu-exists'              => "'''Yma folen henwys \"[[:\$1]]\" war an wiki ma'''",
'searchmenu-new'                 => "'''Gwruthyl an folen \"[[:\$1]]\" war an wiki ma!'''",
'searchhelp-url'                 => 'Help:Gweres',
'searchprofile-images'           => 'Liesmedia',
'searchprofile-everything'       => 'Puptra',
'searchprofile-advanced'         => 'Avoncys',
'searchprofile-articles-tooltip' => 'Hwila yn $1',
'searchprofile-project-tooltip'  => 'Hwila yn $1',
'searchprofile-images-tooltip'   => 'Hwila restrennow',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-section'                 => '(tregh $1)',
'search-interwiki-caption'       => 'Towlow hwor',
'search-interwiki-default'       => '$1 sywyansow:',
'search-interwiki-more'          => '(moy)',
'searchall'                      => 'oll',
'powersearch'                    => 'Hwilans avoncys',
'powersearch-legend'             => 'Hwilans avoncys',
'powersearch-field'              => 'Hwila rag',
'powersearch-toggleall'          => 'Oll',

# Preferences page
'preferences'                 => 'Teythi ow akont',
'mypreferences'               => 'Teythi ow akont',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Krohen',
'prefs-datetime'              => 'Dydh hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Dewisyansow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Hwilas',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktika',
'timezoneregion-arctic'       => 'Arktik',
'timezoneregion-asia'         => 'Asi',
'timezoneregion-atlantic'     => 'Keynvor Atlantek',
'timezoneregion-australia'    => 'Ostrali',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Keynvor Eyndek',
'timezoneregion-pacific'      => 'Keynvor Hebask',
'prefs-searchoptions'         => 'Dewisyansow hwilas',
'prefs-files'                 => 'Restrennow',
'youremail'                   => 'E-bost:',
'username'                    => 'Hanow-usyer:',
'uid'                         => 'ID devnydhyer:',
'prefs-memberingroups'        => 'Esel a {{PLURAL:$1|bagas|bagasow}}:',
'yourrealname'                => 'Hanow gwir:',
'yourlanguage'                => 'Yeth:',
'yournick'                    => 'Hanowskrif:',
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
'right-read'          => 'Redya folennow',
'right-edit'          => 'Chanjya folennow',
'right-createtalk'    => 'Gwruthyl folennow keskows',
'right-createaccount' => 'Gwruthyl akontow devnydhyer nowyth',
'right-move'          => 'Removya folennow',
'right-movefile'      => 'Removya restrennow',
'right-upload'        => 'Ughkarga restrennow',
'right-delete'        => 'Dilea folennow',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'chanjya an folen ma',
'action-move'     => 'removya an folen ma',
'action-movefile' => 'removya an restren ma',
'action-upload'   => 'ughkarga an restren ma',
'action-delete'   => 'dilea an folen ma',

# Recent changes
'nchanges'                     => '$1 {{PLURAL:$1|chanj|chanj}}',
'recentchanges'                => 'Chanjyow a-dhiwedhes',
'recentchanges-legend'         => 'Dewisyansow an chanjyow a-dhiwedhes',
'recentchanges-legend-newpage' => '$1 - folen nowyth',
'recentchanges-legend-minor'   => '$1 - chanj bian',
'recentchanges-label-minor'    => 'Hemm yw chanj bian',
'recentchanges-legend-bot'     => '$1 - chanj gans bot',
'rclistfrom'                   => 'Diskwedhes chanjyow nowyth ow talleth dhyworth $1.',
'rcshowhideminor'              => '$1 chanjyow bian',
'rcshowhidebots'               => '$1 botow',
'rcshowhideanons'              => '$1 devnydhyoryon dyhanow',
'rcshowhidemine'               => '$1 ow chanjyow',
'diff'                         => 'dyffrans',
'hist'                         => 'ist',
'hide'                         => 'Kudha',
'show'                         => 'Diskwedhes',
'minoreditletter'              => 'b',
'newpageletter'                => 'N',
'boteditletter'                => 'bot',
'newsectionsummary'            => '/* $1 */ tregh nowyth',
'rc-enhanced-hide'             => 'Kudha manylyon',

# Recent changes linked
'recentchangeslinked'         => 'Chanjyow dhe folednow kevahal',
'recentchangeslinked-feed'    => 'Chanjyow dhe folennow kevahal',
'recentchangeslinked-toolbox' => 'Chanjyow dhe folennow kevahal',
'recentchangeslinked-page'    => 'Hanow an folen:',

# Upload
'upload'          => 'Ughkarga restren',
'uploadbtn'       => 'Ughkarga restren',
'filename'        => 'Hanow-restren',
'filesource'      => 'Pennfenten:',
'savefile'        => 'Gwitha restren',
'uploadedimage'   => '"[[$1]]" ughkargys',
'watchthisupload' => 'Golya an folen ma',

# Special:ListFiles
'imgfile'        => 'restren',
'listfiles_date' => 'Dydh',
'listfiles_name' => 'Hanow',
'listfiles_user' => 'Devnydhyer',

# File description page
'file-anchor-link'    => 'Restren',
'filehist'            => 'Istori an folen',
'filehist-deleteall'  => 'dilea oll',
'filehist-deleteone'  => 'dilea',
'filehist-datetime'   => 'Dydh/Eur',
'filehist-user'       => 'Devnydhyer',
'filehist-dimensions' => 'Mynsow',
'imagelinks'          => "Kevrennow dhe'n restren ma",

# File deletion
'filedelete'        => 'Dilea $1',
'filedelete-legend' => 'Dilea an restren',
'filedelete-submit' => 'Dilea',

# MIME search
'download' => 'iskarga',

# Unused templates
'unusedtemplateswlh' => 'kevrennow erel',

# Random page
'randompage' => 'Folen dre jons',

# Statistics
'statistics-pages' => 'Folennow',

'brokenredirects-edit'   => 'chanjya',
'brokenredirects-delete' => 'dilea',

'withoutinterwiki-submit' => 'Diskwedhes',

# Miscellaneous special pages
'nmembers'          => '$1 {{PLURAL:$1|esel|esel}}',
'newpages'          => 'Folennow nowyth',
'newpages-username' => 'Hanow-usyer:',
'move'              => 'Removya',
'movethispage'      => 'Removya an folen ma',
'pager-newer-n'     => '{{PLURAL:$1|1 nowyttha|$1 nowyttha}}',
'pager-older-n'     => '{{PLURAL:$1|1 kottha|$1 kottha}}',

# Book sources
'booksources'    => 'Penn-fentynyow lyver',
'booksources-go' => 'Ke',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',

# Special:AllPages
'allpages'       => 'Oll folennow',
'alphaindexline' => '$1 dhe $2',
'prevpage'       => 'Folen gyns ($1)',
'allarticles'    => 'Oll folennow',
'allpagesprev'   => 'Kyns',
'allpagesnext'   => 'Nessa',
'allpagessubmit' => 'Ke',

# Special:Categories
'categories' => 'Klasyansow',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch-ok' => 'Hwila',

# Special:ListUsers
'listusers-submit' => 'Diskwedhes',

# Special:Log/newusers
'newuserlog-create-entry' => 'Devnydhyer nowyth',

# E-mail user
'emailuser'       => 'E-bostya an devnydhyer ma',
'emailpage'       => 'E-bostya devnydhyer',
'defemailsubject' => 'E-bost {{SITENAME}}',
'emailfrom'       => 'Dhyworth:',
'emailto'         => 'Dhe:',
'emailmessage'    => 'Messaj:',
'emailsend'       => 'Danvon',

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
'restriction-type'    => 'Kummyas:',
'pagesize'            => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Chanjya',
'restriction-move'   => 'Removya',
'restriction-create' => 'Gwruthyl',
'restriction-upload' => 'Ughkarga',

# Undelete
'undelete-search-submit' => 'Hwila',

# Namespace form on various pages
'blanknamespace' => '(Penn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow an devnydhyer rag $1',
'mycontris'           => 'Ow hevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'Dhyworth mis (ha moy a-varr):',
'year'                => 'Dhyworth bledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Diskwedhes hepken kevrohow an devnydhyoryon nowyth',
'sp-contributions-talk'     => 'kows',
'sp-contributions-search'   => 'Hwila kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-submit'   => 'Hwila',

# What links here
'whatlinkshere'           => 'Folennow ow kevrenna bys omma',
'whatlinkshere-title'     => 'Folennow ow kevrenna bys "$1"',
'whatlinkshere-page'      => 'Folen:',
'isimage'                 => 'kevren an imaj',
'whatlinkshere-prev'      => '{{PLURAL:$1|kyns|kyns $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'     => '← kevrennow',
'whatlinkshere-hidelinks' => '$1 kevrennow',
'whatlinkshere-filters'   => 'Sidhlow',

# Block/unblock
'blockip'                => 'Let devnydhyer',
'ipb-blocklist-contribs' => 'Kevrohow rag $1',
'ipblocklist-submit'     => 'Hwila',
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
'1movedto2'        => '[[$1]] movys dhe [[$2]]',
'movereason'       => 'Acheson:',

# Export
'export' => 'Esperthi folennow',

# Thumbnails
'thumbnail-more' => 'Brashe',

# Special:Import
'import-interwiki-submit' => 'Ymperthi',
'import-upload-filename'  => 'Hanow-restren:',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Dha folen devnydhyer',
'tooltip-pt-mytalk'         => 'Dha folen gows',
'tooltip-pt-preferences'    => 'Teythi ow akont',
'tooltip-pt-mycontris'      => 'Rol a dha gevrohow',
'tooltip-pt-logout'         => 'Omdedna',
'tooltip-ca-addsection'     => 'Dalleth tregh nowyth',
'tooltip-ca-protect'        => 'Difres an folen ma',
'tooltip-ca-delete'         => 'Dilea an folen ma',
'tooltip-ca-move'           => 'Removya an folen ma',
'tooltip-ca-watch'          => "Keworra an folen ma dhe'th rol golyas",
'tooltip-search'            => 'Hwila {{SITENAME}}',
'tooltip-n-mainpage'        => 'Diskwedhes an pednfolen',
'tooltip-n-randompage'      => 'Karga folen dre jons',
'tooltip-t-whatlinkshere'   => 'Rol a bub folennow wiki ow kevrenna bys omma',
'tooltip-t-contributions'   => 'Gweles rol kevrohow an devnydhyer ma',
'tooltip-t-emailuser'       => 'Danvon e-bost war an devnydhyer ma',
'tooltip-t-upload'          => 'Ughkarga restrennow',
'tooltip-t-specialpages'    => 'Rol a bub folen arbennek',
'tooltip-t-print'           => 'Versyon pryntyadow an folen ma',
'tooltip-ca-nstab-user'     => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-project'  => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'    => 'Gweles folen an restren',
'tooltip-ca-nstab-template' => 'Gweles an skantlyn',
'tooltip-ca-nstab-category' => 'Gweles folen an klass',
'tooltip-save'              => 'Gwitha dha janjyow',

# Attribution
'siteuser'  => 'devnydhyer {{SITENAME}} $1',
'siteusers' => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Media information
'show-big-image' => 'Klerder leun',

# Special:NewFiles
'ilsubmit' => 'Hwila',

# Metadata
'metadata' => 'Metadata',

# EXIF tags
'exif-imagewidth'  => 'Les',
'exif-imagelength' => 'Ughelder',
'exif-artist'      => 'Awtour',

'exif-meteringmode-255' => 'Aral',

'exif-contrast-1' => 'Medhel',
'exif-contrast-2' => 'Kales',

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
'fileduplicatesearch-submit'   => 'Hwila',

# Special:SpecialPages
'specialpages' => 'Folennow arbennek',

# Special:Tags
'tags-edit' => 'chanjya',

);
