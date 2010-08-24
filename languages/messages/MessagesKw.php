<?php
/** Cornish (Kernewek)
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
	NS_TALK             => 'Keskows',
	NS_USER             => 'Devnydhyer',
	NS_USER_TALK        => 'Keskows_Devnydhyer',
	NS_PROJECT_TALK     => 'Keskows_$1',
	NS_FILE             => 'Restren',
	NS_FILE_TALK        => 'Keskows_Restren',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Keskows_MediaWiki',
	NS_TEMPLATE         => 'Skantlyn',
	NS_TEMPLATE_TALK    => 'Keskows_Skantlyn',
	NS_HELP             => 'Gweres',
	NS_HELP_TALK        => 'Keskows_Gweres',
	NS_CATEGORY         => 'Klass',
	NS_CATEGORY_TALK    => 'Keskows_Klass',
);

$namespaceAliases = array(
	'Arbednek'           => NS_SPECIAL,
	'Cows'               => NS_TALK,
	'Kescows'            => NS_TALK,
	'Cows_Devnydhyer'    => NS_USER_TALK,
	'Kescows_Devnydhyer' => NS_USER_TALK,
	'Cows_$1'            => NS_PROJECT_TALK,
	'Kescows_$1'         => NS_PROJECT_TALK,
	'Cows_Restren'       => NS_FILE_TALK,
	'Kescows_Restren'    => NS_FILE_TALK,
	'Cows_MediaWiki'     => NS_MEDIAWIKI_TALK,
	'Cows_MediaWiki'     => NS_MEDIAWIKI_TALK,
	'Cows_Scantlyn'      => NS_TEMPLATE_TALK,
	'Scantlyn'           => NS_TEMPLATE,
	'Kescows_Scantlyn'   => NS_TEMPLATE_TALK,
	'Cows_Gweres'        => NS_HELP_TALK,
	'Kescows_Gweres'     => NS_HELP_TALK,
	'Cows_Class'         => NS_CATEGORY_TALK,
	'Class'              => NS_CATEGORY,
	'Kescows_Class'      => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Watchlist'                 => array( 'Rol_golyas' ),
	'Recentchanges'             => array( 'Chanjyow_a-dhiwedhes' ),
	'Upload'                    => array( 'Ughcarga' ),
	'Randompage'                => array( 'FolenDreJons' ),
	'Allpages'                  => array( 'OllFolennow' ),
	'Specialpages'              => array( 'FolennowArbennek' ),
	'Contributions'             => array( 'Kevrohow' ),
	'Emailuser'                 => array( 'EbostyaDevnydhyer' ),
	'Movepage'                  => array( 'RemovyaFolen' ),
	'Categories'                => array( 'Classys' ),
	'Export'                    => array( 'Esperthy' ),
	'Version'                   => array( 'Versyon' ),
	'Allmessages'               => array( 'OllMessajow' ),
	'Blockip'                   => array( 'Let' ),
	'Import'                    => array( 'Ymperthy' ),
	'Mypage'                    => array( 'OwFolen' ),
	'Mytalk'                    => array( 'OwHows' ),
	'Mycontributions'           => array( 'OwHevrohow' ),
	'Search'                    => array( 'Whilans' ),
);

$messages = array(
# User preference toggles
'tog-hideminor' => 'Cudha chanjyow bian yn chanjyow a-dhiwedhes',

'underline-never' => 'Nevra',

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
'february'      => 'Whevrel',
'march'         => 'Meurth',
'april'         => 'Ebrel',
'may_long'      => 'Me',
'june'          => 'Metheven',
'july'          => 'Gortheren',
'august'        => 'Est',
'september'     => 'Gwynngala',
'october'       => 'Hedra',
'november'      => 'Du',
'december'      => 'Kevardhu',
'january-gen'   => 'Genver',
'february-gen'  => 'Whevrel',
'march-gen'     => 'Meurth',
'april-gen'     => 'Ebrel',
'may-gen'       => 'Me',
'june-gen'      => 'Metheven',
'july-gen'      => 'Gortheren',
'august-gen'    => 'Est',
'september-gen' => 'Gwenngala',
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
'sep'           => 'Gwy',
'oct'           => 'Hed',
'nov'           => 'Du',
'dec'           => 'Kev',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Class|Class}}',
'category_header'          => 'Folennow e\'n class "$1"',
'subcategories'            => 'Is-classys',
'category-media-header'    => 'Media y\'n klass "$1"',
'hidden-categories'        => '{{PLURAL:$1|Class covys|Class covys}}',
'hidden-category-category' => 'Classys covys',
'listingcontinuesabbrev'   => 'pes.',

'about'         => 'A-dro dhe',
'cancel'        => 'Hedhy',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Ow folen',
'mytalk'        => 'Ow hows',
'anontalk'      => 'Kescows rag an trigva IP-ma',
'navigation'    => 'Lewyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Whila',
'qbbrowse'       => 'Peuri',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen ma',
'qbmyoptions'    => 'Ow folennow',
'qbspecialpages' => 'Folennow arbennek',

# Vector skin
'vector-action-addsection'   => 'Keworra mater',
'vector-action-delete'       => 'Dilea',
'vector-action-move'         => 'Gwaya',
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
'vector-namespace-special'   => 'Folen arbennek',
'vector-namespace-talk'      => 'Kescows',
'vector-namespace-template'  => 'Scantlyn',
'vector-namespace-user'      => 'Folen devnydhyer',
'vector-view-create'         => 'Gwruthyl',
'vector-view-edit'           => 'Chanjya',
'vector-view-history'        => 'Gweles istory an folen',
'vector-view-view'           => 'Redya',
'vector-view-viewsource'     => 'Gweles pennfenten',

'errorpagetitle'    => 'Gwall',
'tagline'           => 'Dhyworth {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Whilans',
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
'create'            => 'Gwruthyl',
'editthispage'      => 'Chanjya an folen-ma',
'create-this-page'  => 'Gwruthyl an folen-ma',
'delete'            => 'Dilea',
'deletethispage'    => 'Dilea an folen-ma',
'undelete_short'    => 'Disdhilea $1 chanj',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen-ma',
'unprotect'         => 'Disdhifres',
'unprotectthispage' => 'Disdhifres an folen-ma',
'newpage'           => 'Folen nowyth',
'talkpage'          => "Dadhelva a-dro dhe'n folen-ma",
'talkpagelinktext'  => 'kescows',
'specialpage'       => 'Folen arbennek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Rann nowyth',
'talk'              => 'Kescows',
'views'             => 'Gwelyow',
'toolbox'           => 'Box toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajow',
'templatepage'      => 'Gweles folen an scantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an class',
'viewtalkpage'      => 'Gweles an kescows',
'otherlanguages'    => 'Yethow erel',
'lastmodifiedat'    => 'An folen-ma a veu kens chanjys an $1, dhe $2.',
'jumpto'            => 'Lamma dhe:',
'jumptonavigation'  => 'lewyans',
'jumptosearch'      => 'whilans',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Derivadow',
'copyright'            => 'Cavadow yw an dalgh yn-dann $1.',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lemmyn',
'currentevents-url'    => 'Project:Darvosow a-lemmyn',
'disclaimers'          => 'Avisyansow',
'disclaimerpage'       => 'Project:Avisyans ollgemmyn',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Pennfolen',
'mainpage-description' => 'Pennfolen',
'policy-url'           => 'Project:Policy',
'portal'               => 'Porth an gemeneth',
'portal-url'           => 'Project:Porth an gemmynieth',
'privacy'              => 'Policy privetter',
'privacypage'          => 'Project:Policy privetter',

'ok'                      => 'Sur',
'retrievedfrom'           => 'Daskevys dhort "$1"',
'youhavenewmessages'      => 'Yma $1 genowgh ($2).',
'newmessageslink'         => 'messajys nowyth',
'newmessagesdifflink'     => 'chanj kens',
'youhavenewmessagesmulti' => 'Yma messajow nowyth genowgh war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an pennfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an fenten',
'editsectionhint'         => 'Chanjya an rann: $1',
'toc'                     => 'Synsys',
'showtoc'                 => 'disqwedhes',
'hidetoc'                 => 'cudha',
'viewdeleted'             => 'Gweles $1?',
'red-link-title'          => '$1 (nyns eus folen henwys yndelma na whath)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygel',
'nstab-user'      => 'Folen devnydhyer',
'nstab-media'     => 'Folen media',
'nstab-special'   => 'Folen arbennek',
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
'yourpasswordagain'       => 'Jynscrif agas ger-tremena arta:',
'yourdomainname'          => 'Agas diredh:',
'login'                   => 'Omgelmy',
'nav-login-createaccount' => 'Omgelmy / Formya acont nowyth',
'loginprompt'             => 'Why a res galosegy cookies rag omgelmy orth {{SITENAME}}.',
'userlogin'               => 'Omgelmy / formya acont nowyth',
'logout'                  => 'Digelmy',
'userlogout'              => 'Digelmy',
'notloggedin'             => 'Digelmys',
'nologin'                 => "Nyns eus acont dhewgh? '''$1'''.",
'nologinlink'             => 'Form acont',
'createaccount'           => 'Form acont nowyth',
'gotaccount'              => "Eus akont genowgh seulabrys? '''$1'''.",
'gotaccountlink'          => 'Omgelmy',
'createaccountmail'       => 'der e-bost',
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
'link_sample'    => 'Titel an gevren',
'link_tip'       => 'Kevren bervedhel',
'extlink_sample' => 'http://www.example.com titel an gevren',
'extlink_tip'    => 'Kevren a-mes (remembra an rager http://)',
'image_tip'      => 'Restren neythys',
'media_tip'      => 'Kevren restren',

# Edit pages
'summary'            => 'Derivas cot:',
'minoredit'          => 'Hemm yw chanj bian',
'watchthis'          => 'Golya an folen-ma',
'savearticle'        => 'Gwitha',
'preview'            => 'Ragwel',
'showpreview'        => 'Ragweles',
'showdiff'           => 'Disqwedhes an chanjyow',
'loginreqlink'       => 'omgelmy',
'newarticle'         => '(Nowyth)',
'note'               => "'''Noten:'''",
'previewnote'        => "'''Gwra remembra, nyns yw hemm ma's ragwel.''' Nyns yw dha janjyow gwithys whath!",
'editing'            => 'ow chanjya $1',
'editingsection'     => 'ow chanjya $1 (rann)',
'editingcomment'     => 'ow chanjya $1 (rann nowyth)',
'yourtext'           => 'Agas text',
'yourdiff'           => 'Dyffransow',
'template-protected' => '(gwithys)',

# History pages
'previousrevision'       => '← Amendyans cottha',
'nextrevision'           => 'Daswel nowyttha →',
'cur'                    => 'lem',
'next'                   => 'nessa',
'last'                   => 'kens',
'page_first'             => 'kensa',
'page_last'              => 'kens',
'history-fieldset-title' => 'Peury an istory',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision deletion
'rev-delundel'    => 'disqwedhes/cudha',
'pagehist'        => 'Istory an folen',
'revdelete-uname' => 'hanow-usyer',

# History merging
'mergehistory-reason' => 'Acheson:',

# Diffs
'history-title' => 'Istory an folen "$1"',
'lineno'        => 'Linen $1:',
'editundo'      => 'diswul',

# Search results
'searchresults'                  => 'Sewyansow an whilans',
'searchresults-title'            => 'Sewyansow an whilans rag "$1"',
'searchresulttext'               => 'Rag derivadow pella war whilas en {{SITENAME}}, gwra gweles [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                 => 'Why a wrug whilas \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|oll folennow ow talleth gen "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|oll folennow ow kevrenna dhe "$1"]])',
'prevn'                          => 'kens {{PLURAL:$1|$1}}',
'nextn'                          => 'nessa {{PLURAL:$1|$1}}',
'viewprevnext'                   => 'Gweles ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'              => 'Dewisyansow whilans',
'searchmenu-exists'              => "'''Yma folen henwys \"[[:\$1]]\" war an wiki-ma'''",
'searchmenu-new'                 => "'''Gwruthyl an folen \"[[:\$1]]\" war an wiki-ma!'''",
'searchhelp-url'                 => 'Help:Gweres',
'searchprofile-images'           => 'Liesmedia',
'searchprofile-everything'       => 'Puptra',
'searchprofile-advanced'         => 'Avoncys',
'searchprofile-articles-tooltip' => 'Whila en $1',
'searchprofile-project-tooltip'  => 'Whila en $1',
'searchprofile-images-tooltip'   => 'Whila restrennow',
'search-result-size'             => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-section'                 => '(rann $1)',
'search-suggest'                 => "A wrugo'why menya: $1",
'search-interwiki-caption'       => 'Towlow whor',
'search-interwiki-default'       => '$1 sewyansow:',
'search-interwiki-more'          => '(moy)',
'search-mwsuggest-enabled'       => 'gen profyansow',
'search-mwsuggest-disabled'      => 'profyansow vyth',
'searchall'                      => 'oll',
'powersearch'                    => 'Whilans avoncys',
'powersearch-legend'             => 'Whilans avoncys',
'powersearch-ns'                 => 'Whila en spasys-hanow:',
'powersearch-field'              => 'Whila',
'powersearch-toggleall'          => 'Oll',

# Preferences page
'preferences'                 => 'Dewisyansow',
'mypreferences'               => 'Ow dewisyansow',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Crohen',
'prefs-datetime'              => 'Dedhyans hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Dewisyansow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Whilans',
'timezoneregion-africa'       => 'Africa',
'timezoneregion-america'      => 'America',
'timezoneregion-antarctica'   => 'Antarctica',
'timezoneregion-arctic'       => 'Arctek',
'timezoneregion-asia'         => 'Asia',
'timezoneregion-atlantic'     => 'Mor Atlantek',
'timezoneregion-australia'    => 'Awstralya',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Mor Eyndek',
'timezoneregion-pacific'      => 'Mor Cosel',
'prefs-searchoptions'         => 'Dewisyansow whilans',
'prefs-files'                 => 'Restrennow',
'youremail'                   => 'E-bost:',
'username'                    => 'Hanow-usyer:',
'uid'                         => 'ID devnydhyer:',
'prefs-memberingroups'        => "Esel a'n {{PLURAL:$1|bagas|bagasow}}:",
'yourrealname'                => 'Hanow gwir:',
'yourlanguage'                => 'Yeth:',
'yournick'                    => 'Sinans nowyth:',
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
'userrights-reason'       => 'Acheson:',

# Groups
'group'       => 'Bagas:',
'group-user'  => 'Devnydhyoryon',
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
'right-createtalk'    => 'Gwruthyl folennow kescows',
'right-createaccount' => 'Form acontow devnydhyer nowyth',
'right-move'          => 'Gwaya folennow',
'right-movefile'      => 'Gwaya restrennow',
'right-upload'        => 'Ughcarga restrennow',
'right-delete'        => 'Dilea folennow',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'chanjya an folen-ma',
'action-move'     => 'gwaya an folen ma',
'action-movefile' => 'gwaya an restren ma',
'action-upload'   => 'ughcarga an restren-ma',
'action-delete'   => 'dilea an folen ma',

# Recent changes
'nchanges'                     => '$1 {{PLURAL:$1|chanj|chanj}}',
'recentchanges'                => 'Chanjyow a-dhiwedhes',
'recentchanges-legend'         => 'Dewisyansow an chanjyow a-dhiwedhes',
'recentchangestext'            => "Sewya an chanjyow diwettha eus dhe'n wiki war'n folen-ma.",
'recentchanges-label-legend'   => 'Alwhedh: $1.',
'recentchanges-legend-newpage' => '$1 - folen nowyth',
'recentchanges-legend-minor'   => '$1 - chanj bian',
'recentchanges-label-minor'    => 'Hemm yw chanj bian',
'recentchanges-legend-bot'     => '$1 - chanj gans bot',
'rclistfrom'                   => 'Disqwedhes chanjyow nowyth ow talleth a-dhia $1.',
'rcshowhideminor'              => '$1 chanjyow bian',
'rcshowhidebots'               => '$1 botow',
'rcshowhideliu'                => '$1 devnydhoryon omgelmys',
'rcshowhideanons'              => '$1 devnydhyoryon dihanow',
'rcshowhidemine'               => '$1 ow chanjyow',
'rclinks'                      => "Disqwedhes an $1 chanj a-dhiwedhes gwres e'n $2 dedh a-dhiwedhes<br />$3",
'diff'                         => 'dyffrans',
'hist'                         => 'ist',
'hide'                         => 'Cudha',
'show'                         => 'Disqwedhes',
'minoreditletter'              => 'B',
'newpageletter'                => 'N',
'boteditletter'                => 'bot',
'newsectionsummary'            => '/* $1 */ rann nowyth',
'rc-enhanced-hide'             => 'Cudha manylyon',

# Recent changes linked
'recentchangeslinked'         => 'Chanjyow dhe folennow kevahal',
'recentchangeslinked-feed'    => 'Chanjyow dhe folennow kevahal',
'recentchangeslinked-toolbox' => 'Chanjyow dhe folennow kevahal',
'recentchangeslinked-summary' => "↓ Hemm yw rol a janjyow a-dhiwedhes gwres war folennow kevrennys dhort folen ragavysyes (po dhe esely class ragavysyes).
Yth yw folennow eus war dha [[Special:Watchlist|rol golyas]] en '''tew'''.",
'recentchangeslinked-page'    => 'Hanow an folen:',

# Upload
'upload'          => 'Ughcarga restren',
'uploadbtn'       => 'Ughcarga restren',
'filename'        => 'Hanow-restren',
'filesource'      => 'Pennfenten:',
'savefile'        => 'Gwitha restren',
'uploadedimage'   => '"[[$1]]" ughkergys',
'watchthisupload' => 'Golya an folen-ma',

# Special:ListFiles
'imgfile'        => 'restren',
'listfiles_date' => 'Dedhyas',
'listfiles_name' => 'Hanow',
'listfiles_user' => 'Devnydhyer',

# File description page
'file-anchor-link'          => 'Restren',
'filehist'                  => 'Istory an folen',
'filehist-deleteall'        => 'dilea oll',
'filehist-deleteone'        => 'dilea',
'filehist-current'          => 'a-lemmyn',
'filehist-datetime'         => 'Dedhyans/Eur',
'filehist-user'             => 'Devnydhyer',
'filehist-dimensions'       => 'Mynsow',
'filehist-comment'          => 'Ger',
'imagelinks'                => "Kevrennow dhe'n restren-ma",
'uploadnewversion-linktext' => "Ughcarga versyon nowyth a'n restren-ma",

# File deletion
'filedelete'        => 'Dilea $1',
'filedelete-legend' => 'Dilea an restren',
'filedelete-submit' => 'Dilea',

# MIME search
'download' => 'iscarga',

# Unused templates
'unusedtemplateswlh' => 'kevrennow erel',

# Random page
'randompage' => 'Folen dre jons',

# Statistics
'statistics-pages' => 'Folennow',

'brokenredirects-edit'   => 'chanjya',
'brokenredirects-delete' => 'dilea',

'withoutinterwiki-submit' => 'Disqwedhes',

# Miscellaneous special pages
'nmembers'          => '$1 {{PLURAL:$1|esel|esel}}',
'newpages'          => 'Folennow nowyth',
'newpages-username' => 'Hanow-usyer:',
'move'              => 'Gwaya',
'movethispage'      => 'Gwaya an folen-ma',
'pager-newer-n'     => '{{PLURAL:$1|1 nowyttha|$1 nowyttha}}',
'pager-older-n'     => '{{PLURAL:$1|1 cottha|$1 cottha}}',

# Book sources
'booksources'    => 'Penn-fentynyow lyver',
'booksources-go' => 'Ke',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',

# Special:AllPages
'allpages'       => 'Oll folennow',
'alphaindexline' => '$1 dhe $2',
'prevpage'       => 'Folen gens ($1)',
'allarticles'    => 'Oll folennow',
'allpagesprev'   => 'Kens',
'allpagesnext'   => 'Nessa',
'allpagessubmit' => 'Ke',

# Special:Categories
'categories' => 'Classys',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch'    => 'Kevrennow a-mes',
'linksearch-ok' => 'Whila',

# Special:ListUsers
'listusers-submit' => 'Disqwedhes',

# Special:Log/newusers
'newuserlog-create-entry' => 'Devnydhyer nowyth',

# Special:ListGroupRights
'listgrouprights-members' => '(rol an esely)',

# E-mail user
'emailuser'       => 'E-bostya an devnydhyer-ma',
'emailpage'       => 'E-bostya devnydhyer',
'defemailsubject' => 'E-bost {{SITENAME}}',
'emailfrom'       => 'A-dhia:',
'emailto'         => 'Dhe:',
'emailmessage'    => 'Messaj:',
'emailsend'       => 'Danvon',

# Watchlist
'watchlist'         => 'Ow rol golyas',
'mywatchlist'       => 'Ow rol golyas',
'addedwatch'        => 'Keworrys dhe rol golyas',
'watch'             => 'Golya',
'watchthispage'     => 'Golya an folen-ma',
'unwatch'           => 'Diswolya',
'watchlist-options' => 'Dewisyansow an rol golyas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ow colya...',
'unwatching' => 'Ow tisgolya...',

# Delete
'deletepage'            => 'Dilea an folen',
'delete-confirm'        => 'Dilea "$1"',
'delete-legend'         => 'Dilea',
'deletedarticle'        => 'a dhileys "[[$1]]"',
'deletecomment'         => 'Acheson:',
'deleteotherreason'     => 'Acheson aral/keworransel:',
'deletereasonotherlist' => 'Acheson aral',

# Protect
'protectcomment'      => 'Acheson:',
'protect-level-sysop' => 'Menysteryon hepken',
'restriction-type'    => 'Cubmyas:',
'pagesize'            => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Chanjya',
'restriction-move'   => 'Gwaya',
'restriction-create' => 'Gwruthyl',
'restriction-upload' => 'Ughcarga',

# Undelete
'undeletelink'              => 'gweles/daswul',
'undelete-search-submit'    => 'Whila',
'undelete-show-file-submit' => 'Ea',

# Namespace form on various pages
'namespace'      => 'Spas-hanow:',
'blanknamespace' => '(Penn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow an devnydhyer rag $1',
'mycontris'           => 'Ow hevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'A-dhia mis (ha moy a-varr):',
'year'                => 'A-dhia bledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Disqwedhes hepken kevrohow an acontow nowyth',
'sp-contributions-talk'     => 'kescows',
'sp-contributions-search'   => 'Whila kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-submit'   => 'Whila',

# What links here
'whatlinkshere'           => 'Pyth eus ow kevrenna omma',
'whatlinkshere-title'     => 'Folennow ow kevrenna bys "$1"',
'whatlinkshere-page'      => 'Folen:',
'linkshere'               => "Yma'n folennow a-sew ow kevrenna dhe '''[[:$1]]''':",
'isimage'                 => 'kevren an imaj',
'whatlinkshere-prev'      => '{{PLURAL:$1|kens|kens $1}}',
'whatlinkshere-next'      => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'     => '← kevrennow',
'whatlinkshere-hidelinks' => '$1 kevrennow',
'whatlinkshere-filters'   => 'Sidhlow',

# Block/unblock
'blockip'                    => 'Let devnydhyer',
'ipaddress'                  => 'Trigva IP:',
'ipadressorusername'         => 'Trigva IP po hanow-usyer:',
'ipbreason'                  => 'Acheson:',
'ipbreasonotherlist'         => 'Acheson aral',
'ipb-blocklist-contribs'     => 'Kevrohow rag $1',
'ipblocklist-submit'         => 'Whila',
'blocklink'                  => 'lettya',
'contribslink'               => 'kevrohow',
'block-log-flags-anononly'   => 'devnydhyoryon dihanow hepken',
'block-log-flags-hiddenname' => 'hanow-usyer covys',

# Move page
'move-page'        => 'Gwaya $1',
'move-page-legend' => 'Gwaya folen',
'movearticle'      => 'Movya an folen:',
'newtitle'         => 'Dhe titel nowyth:',
'move-watch'       => 'Golya an folen-ma',
'movepagebtn'      => 'Gwaya an folen',
'movepage-moved'   => '\'\'\'Gwayes yw "$1" war-tu "$2"\'\'\'',
'movedto'          => 'gwayes war-tu',
'1movedto2'        => '[[$1]] gwayes war-tu [[$2]]',
'movereason'       => 'Acheson:',

# Export
'export'        => 'Esperthy folennow',
'export-addcat' => 'Keworra',
'export-addns'  => 'Keworra',

# Namespace 8 related
'allmessagesname' => 'Hanow',

# Thumbnails
'thumbnail-more' => 'Brashe',

# Special:Import
'import'                  => 'Ymperthy folennow',
'import-interwiki-submit' => 'Ymperthy',
'import-upload-filename'  => 'Hanow-restren:',
'importstart'             => 'Owth ymperthy folennow...',
'import-noarticle'        => 'Folen vyth dhe ymperthy!',

# Tooltip help for the actions
'tooltip-pt-userpage'            => 'Agas folen devnydhyer',
'tooltip-pt-mytalk'              => 'Agas folen gows',
'tooltip-pt-preferences'         => 'Ow dewisyansow',
'tooltip-pt-watchlist'           => 'An rol a folennow eros jy ow colya',
'tooltip-pt-mycontris'           => "Rol a'gas kevrohow",
'tooltip-pt-login'               => 'Gwell via dhis mar teu hag omgelmy, mes nyns yw besy',
'tooltip-pt-logout'              => 'Omdenna',
'tooltip-ca-talk'                => "Dadhelva a-dro dhe'n dalgh",
'tooltip-ca-edit'                => 'Why a ell chanjya an folen-ma. Mar pleg, gwrewgh usya an boton ragwel kens gwitha.',
'tooltip-ca-addsection'          => 'Dalleth rann nowyth',
'tooltip-ca-viewsource'          => 'Alwhedhys yw an folen-ma.
Ty a ell gweles hy fennfenten.',
'tooltip-ca-protect'             => 'Difres an folen-ma',
'tooltip-ca-delete'              => 'Dilea an folen-ma',
'tooltip-ca-move'                => 'Gwaya an folen-ma',
'tooltip-ca-watch'               => "Keworra an folen-ma dh'agas rol golyas",
'tooltip-ca-unwatch'             => 'Dilea an folen-ma dhort agas rol golyas',
'tooltip-search'                 => 'Whila en {{SITENAME}}',
'tooltip-search-fulltext'        => "Whila an text-ma e'n folennow",
'tooltip-n-mainpage'             => 'Disqwedhes an pennfolen',
'tooltip-n-mainpage-description' => 'Godriga an pennfolen',
'tooltip-n-portal'               => "A-dro dhe'n ragdres, an pyth a ellys gwul, ple cavos taclow",
'tooltip-n-recentchanges'        => "Rol an chanjyow a-dhiwedhes e'n wiki",
'tooltip-n-randompage'           => 'Carga folen dre jons',
'tooltip-n-help'                 => 'Gweres',
'tooltip-t-whatlinkshere'        => 'Rol a bub folennow wiki ow kevrenna bys omma',
'tooltip-t-recentchangeslinked'  => 'Chanjyow a-dhiwedhes en folennow eus kevrennys orth an folen-ma',
'tooltip-t-contributions'        => 'Gweles rol kevrohow an devnydhyer-ma',
'tooltip-t-emailuser'            => "Danvon e-bost dhe'n devnydhyer-ma",
'tooltip-t-upload'               => 'Ughcarga restrennow',
'tooltip-t-specialpages'         => 'Rol a bub folen arbennek',
'tooltip-t-print'                => 'Versyon pryntyadow an folen-ma',
'tooltip-t-permalink'            => "Kevren fast dhe'n amendyans-ma a'n folen",
'tooltip-ca-nstab-main'          => 'Gweles an folen dalgh',
'tooltip-ca-nstab-user'          => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-special'       => 'Hemm yw folen arbennek, ny ellowgh why chanjya an folen hy honan.',
'tooltip-ca-nstab-project'       => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'         => 'Gweles folen an restren',
'tooltip-ca-nstab-template'      => 'Gweles an scantlyn',
'tooltip-ca-nstab-category'      => 'Gweles folen an class',
'tooltip-save'                   => 'Gwitha agas chanjyow',
'tooltip-preview'                => 'Ragweles dha janjyow; gwra usya hemma kens gwitha mar pleg!',
'tooltip-diff'                   => "Disqwedhes an chanjyow eus gwres genes dhe'n text",

# Attribution
'siteuser'         => 'devnydhyer {{SITENAME}} $1',
'lastmodifiedatby' => 'An folen-ma a veu kens chanjys dhe $2, $1 gans $3.',
'siteusers'        => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Chanj cottha',
'nextdiff'     => 'Chanj nowyttha →',

# Media information
'show-big-image' => 'Clerder leun',

# Special:NewFiles
'ilsubmit' => 'Whila',

# Metadata
'metadata' => 'Metadata',

# EXIF tags
'exif-imagewidth'  => 'Les',
'exif-imagelength' => 'Uhelder',
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
'imgmultipageprev' => '← folen kens',
'imgmultipagenext' => 'folen nessa →',
'imgmultigo'       => 'Ke!',

# Table pager
'table_pager_limit_submit' => 'Ke',

# Auto-summaries
'autosumm-new' => "Formas folen gen: '$1'",

# Watchlist editing tools
'watchlisttools-edit' => 'Gweles ha chanjya an rol golyas',
'watchlisttools-raw'  => 'Chanjya restren an rol golyas',

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
'specialpages' => 'Folennow arbennek',

# Special:Tags
'tags-edit' => 'chanjya',

);
