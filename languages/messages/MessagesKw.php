<?php
/** Cornish (kernowek)
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
 * @author Nrowe
 * @author Scryfer
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arbennek',
	NS_TALK             => 'Kescows',
	NS_USER             => 'Devnydhyer',
	NS_USER_TALK        => 'Kescows_Devnydhyer',
	NS_PROJECT_TALK     => 'Kescows_$1',
	NS_FILE             => 'Restren',
	NS_FILE_TALK        => 'Kescows_Restren',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Kescows_MediaWiki',
	NS_TEMPLATE         => 'Scantlyn',
	NS_TEMPLATE_TALK    => 'Kescows_Scantlyn',
	NS_HELP             => 'Gweres',
	NS_HELP_TALK        => 'Kescows_Gweres',
	NS_CATEGORY         => 'Class',
	NS_CATEGORY_TALK    => 'Kescows_Class',
);

$namespaceAliases = array(
	'Arbednek'           => NS_SPECIAL,
	'Cows'               => NS_TALK,
	'Keskows'            => NS_TALK,
	'Cows_Devnydhyer'    => NS_USER_TALK,
	'Keskows_Devnydhyer' => NS_USER_TALK,
	'Cows_$1'            => NS_PROJECT_TALK,
	'Keskows_$1'         => NS_PROJECT_TALK,
	'Cows_Restren'       => NS_FILE_TALK,
	'Keskows_Restren'    => NS_FILE_TALK,
	'Cows_MediaWiki'     => NS_MEDIAWIKI_TALK,
	'Cows_MediaWiki'     => NS_MEDIAWIKI_TALK,
	'Keskows_MediaWiki'  => NS_MEDIAWIKI_TALK,
	'Cows_Scantlyn'      => NS_TEMPLATE_TALK,
	'Skantlyn'           => NS_TEMPLATE,
	'Keskows_Skantlyn'   => NS_TEMPLATE_TALK,
	'Cows_Gweres'        => NS_HELP_TALK,
	'Keskows_Gweres'     => NS_HELP_TALK,
	'Cows_Class'         => NS_CATEGORY_TALK,
	'Klass'              => NS_CATEGORY,
	'Keskows_Klass'      => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'OllMessajow' ),
	'Allpages'                  => array( 'OllFolennow' ),
	'Ancientpages'              => array( 'FolennowCoth' ),
	'Block'                     => array( 'Lettya' ),
	'Categories'                => array( 'Classys' ),
	'Contributions'             => array( 'Kevrohow' ),
	'Emailuser'                 => array( 'EbostyaDevnydhyer' ),
	'Export'                    => array( 'Esperthy' ),
	'Import'                    => array( 'Ymperthy' ),
	'Movepage'                  => array( 'GwayaFolen' ),
	'Mycontributions'           => array( 'OwHevrohow' ),
	'Mypage'                    => array( 'OwFolen' ),
	'Mytalk'                    => array( 'OwHows' ),
	'Newpages'                  => array( 'FolennowNowyth' ),
	'Preferences'               => array( 'Dewisyansow' ),
	'Randompage'                => array( 'FolenDreJons' ),
	'Recentchanges'             => array( 'Chanjyow_a-dhiwedhes' ),
	'Search'                    => array( 'Whilans' ),
	'Specialpages'              => array( 'FolennowArbennek' ),
	'Upload'                    => array( 'Ughcarga' ),
	'Version'                   => array( 'Versyon' ),
	'Wantedcategories'          => array( 'ClassysWhansus' ),
	'Wantedfiles'               => array( 'RestrennowWhansus' ),
	'Wantedpages'               => array( 'FolennowWhansus' ),
	'Wantedtemplates'           => array( 'ScantlynsWhansus' ),
	'Watchlist'                 => array( 'Rol_golyas' ),
);

$messages = array(
# User preference toggles
'tog-hideminor'      => 'Cudha chanjyow bian yn chanjyow a-dhiwedhes',
'tog-watchcreations' => "Keworra folennow gwruthys genev ha restrennow ughkergys genev dhe'm rol golyas",
'tog-watchdefault'   => "Keworra folennow ha restrennow chanjys genev dhe'm rol golyas",
'tog-watchmoves'     => "Keworra folennow ha restrennow gwayys genev dhe'm rol golyas",
'tog-watchdeletion'  => "Keworra folednow dileys genev dhe'm rol golyas",

'underline-always'  => 'Puppres',
'underline-never'   => 'Jammes',
'underline-default' => 'Defowt an beurel po an grohen',

# Font style option in Special:Preferences
'editfont-default' => 'Defowt an beurel',

# Dates
'sunday'        => "De'Sul",
'monday'        => "De'Lun",
'tuesday'       => "De'Meurth",
'wednesday'     => "De'Merher",
'thursday'      => "De'Yow",
'friday'        => "De'Gwener",
'saturday'      => "De'Sadorn",
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
'september-gen' => 'Gwynngala',
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
'pagecategories'                 => '{{PLURAL:$1|Class|Classys}}',
'category_header'                => 'Folennow y\'n class "$1"',
'subcategories'                  => 'Isglassys',
'category-media-header'          => 'Media y\'n class "$1"',
'category-empty'                 => "''Nyns eus na folennow na media y'n class-ma.''",
'hidden-categories'              => '{{PLURAL:$1|Class cudhys|Classys cudhys}}',
'hidden-category-category'       => 'Classys cudhys',
'category-subcat-count'          => "{{PLURAL:$2|Nyns eus dhe'n class-ma marnas an isglass a sew.|Yma dhe'n class-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew, dhyworth somm a $2.}}",
'category-subcat-count-limited'  => "Yma dhe'n class-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew.",
'category-article-count'         => "{{PLURAL:$2|Nyns eus dhe'n class-ma marnas an folen a sew.|Yma'n {{PLURAL:$1|folen|$1 folennow}} a sew y'n class-ma, dhyworth somm a $2.}}",
'category-article-count-limited' => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew y'n class-ma.",
'category-file-count'            => "{{PLURAL:$2|Nyns eus dhe'n class-ma an folen a sew.|Yma'n {{PLURAL:$1|folen|$1 folen}} a sew y'n class-ma, dhyworth somm a $2.}}",
'category-file-count-limited'    => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew y'n class-ma.",
'listingcontinuesabbrev'         => 'pes.',

'about'         => 'A-dro dhe',
'newwindow'     => '(y whra egery yn fenester nowyth)',
'cancel'        => 'Hedhy',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Folen',
'mytalk'        => 'Kescows',
'anontalk'      => 'Kescows rag an drigva IP-ma',
'navigation'    => 'Lewyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Cavos',
'qbbrowse'       => 'Peury',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen-ma',
'qbmyoptions'    => 'Ow folennow',
'qbspecialpages' => 'Folennow arbennek',
'faq'            => 'FAQ',

# Vector skin
'vector-action-addsection' => 'Keworra testen',
'vector-action-delete'     => 'Dilea',
'vector-action-move'       => 'Gwaya',
'vector-action-protect'    => 'Difres',
'vector-action-undelete'   => 'Disdhilea',
'vector-action-unprotect'  => 'Chanjya difresans',
'vector-view-create'       => 'Gwruthyl',
'vector-view-edit'         => 'Chanjya',
'vector-view-history'      => 'Gweles an istory',
'vector-view-view'         => 'Redya',
'vector-view-viewsource'   => 'Gweles an bennfenten',
'actions'                  => 'Gwriansow',
'namespaces'               => 'Spasys hanow',
'variants'                 => 'Dyffransow',

'errorpagetitle'    => 'Gwall',
'returnto'          => 'Dewheles dhe $1.',
'tagline'           => 'Dhyworth {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Whilas',
'searchbutton'      => 'Whilas',
'go'                => 'Ke',
'searcharticle'     => 'Mos',
'history'           => 'Istory an folen',
'history_short'     => 'Istory',
'printableversion'  => 'Versyon pryntyadow',
'permalink'         => 'Kevren fast',
'print'             => 'Pryntya',
'view'              => 'Gweles',
'edit'              => 'Chanjya',
'create'            => 'Gwruthyl',
'editthispage'      => 'Chanjya an folen-ma',
'create-this-page'  => 'Gwruthyl an folen-ma',
'delete'            => 'Dilea',
'deletethispage'    => 'Dilea an folen-ma',
'undelete_short'    => 'Disdhilea {{PLURAL:$1|unn janj|$1 chanj}}',
'viewdeleted_short' => 'Gweles {{PLURAL:$1|unn janj diles|$1 chanj diles}}',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen-ma',
'unprotect'         => 'Chanjya difresans',
'unprotectthispage' => 'Chanjya difresans an folen-ma',
'newpage'           => 'Folen nowyth',
'talkpage'          => "Dadhelva a-dro dhe'n folen-ma",
'talkpagelinktext'  => 'Kescows',
'specialpage'       => 'Folen arbennek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Rann noweth',
'talk'              => 'Kescows',
'views'             => 'Gwelow',
'toolbox'           => 'Box toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajys',
'templatepage'      => 'Gweles folen an scantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an class',
'viewtalkpage'      => 'Gweles an kescows',
'otherlanguages'    => 'Yn yethow erel',
'redirectedfrom'    => '(Daswedyes dhyworth $1)',
'redirectpagesub'   => 'Folen daswedyans',
'lastmodifiedat'    => 'An folen-ma a veu chanjys an $1, dhe $2.',
'jumpto'            => 'Lamma dhe:',
'jumptonavigation'  => 'lewyans',
'jumptosearch'      => 'whilas',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Kedhlow',
'copyright'            => 'Cavadow yw an dalgh yn-dann $1.',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lemmyn',
'currentevents-url'    => 'Project:Darvosow a-lemmyn',
'disclaimers'          => 'Avisyansow',
'disclaimerpage'       => 'Project:Avisyans ollgemmyn',
'edithelp'             => 'Gweres gans chanjya',
'edithelppage'         => 'Help:Chanjya',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Folen dre',
'mainpage-description' => 'Folen dre',
'policy-url'           => 'Project:Policy',
'portal'               => 'Porth an gemeneth',
'portal-url'           => 'Project:Porth an gemeneth',
'privacy'              => 'Policy privetter',
'privacypage'          => 'Project:Policy privetter',

'badaccess' => 'Gwall cummyes',

'ok'                      => 'Sur',
'retrievedfrom'           => 'Daskevys dhyworth "$1"',
'youhavenewmessages'      => 'Yma $1 genowgh ($2).',
'newmessageslink'         => 'messajys noweth',
'newmessagesdifflink'     => 'chanj diwettha',
'youhavenewmessagesmulti' => 'Yma messajys noweth genowgh war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an bennfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an bennfenten',
'editsectionhint'         => 'Chanjya an rann: $1',
'toc'                     => 'Rol an folen',
'showtoc'                 => 'disqwedhes',
'hidetoc'                 => 'cudha',
'collapsible-expand'      => 'Efany',
'thisisdeleted'           => 'Gweles po restorya $1?',
'viewdeleted'             => 'Gweles $1?',
'restorelink'             => '{{PLURAL:$1|unn janj diles|$1 chanj diles}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => '$1 RSS feed',
'site-atom-feed'          => '$1 Atom feed',
'page-rss-feed'           => '"$1" feed RSS',
'page-atom-feed'          => '"$1" feed Atom',
'red-link-title'          => '$1 (nyns eus folen henwys yndelma)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygel',
'nstab-user'      => 'Folen devnydhyer',
'nstab-media'     => 'Folen media',
'nstab-special'   => 'Folen arbennek',
'nstab-project'   => 'Folen ragdres',
'nstab-image'     => 'Restren',
'nstab-mediawiki' => 'Messach',
'nstab-template'  => 'Scantlyn',
'nstab-help'      => 'Gweres',
'nstab-category'  => 'Class',

# General errors
'error'               => 'Gwall',
'databaseerror'       => 'Gwall database',
'readonly'            => 'Alwhedhys yw an database',
'missingarticle-rev'  => '(amendyans#: $1)',
'missingarticle-diff' => '(Dyffrans: $1, $2)',
'internalerror'       => 'Gwall a-bervedh',
'internalerror_info'  => 'Gwall a-bervedh: $1',
'filecopyerror'       => 'Ny veu possybyl copia an restren "$1" dhe "$2".',
'filerenameerror'     => 'Ny veu possybyl dashenwel an restren "$1" dhe "$2".',
'filedeleteerror'     => 'Ny veu possybyl dilea an restren "$1".',
'filenotfound'        => 'Ny veu kevys an restren "$1".',
'badtitle'            => 'Titel drog',
'viewsource'          => 'Gweles an bennfenten',

# Login and logout pages
'welcomecreation'         => '== Dynnargh, $1! ==
Gwruthys yw agas acont.
Na wrewgh ankevy dhe janjya agas [[Special:Preferences|dowisyansow {{SITENAME}}]].',
'yourname'                => 'Hanow usyer:',
'yourpassword'            => 'Ger tremena:',
'yourpasswordagain'       => 'Jynnscrifowgh agas ger tremena arta:',
'remembermypassword'      => "Perthy cov a'm omgelmy war'n jynn amontya-ma (rag $1 {{PLURAL:$1|dedh}} dhe'n moyha)",
'securelogin-stick-https' => 'Gwitha junyes gans HTTPS wosa omgelmy',
'yourdomainname'          => 'Agas tiredh:',
'login'                   => 'Omgelmy',
'nav-login-createaccount' => 'Omgelmy / Formya acont noweth',
'loginprompt'             => 'Res yw dhywgh galosegy cookies rag omgelmy orth {{SITENAME}}.',
'userlogin'               => 'Omgelmy / formya acont noweth',
'userloginnocreate'       => 'Omgelmy',
'logout'                  => 'Digelmy',
'userlogout'              => 'Digelmy',
'notloggedin'             => 'Digelmys',
'nologin'                 => "A nyns eus acont dhywgh? '''$1'''.",
'nologinlink'             => 'Formyowgh acont',
'createaccount'           => 'Formya acont noweth',
'gotaccount'              => "Eus acont dhis seulabres? '''$1'''.",
'gotaccountlink'          => 'Omgelmy',
'userlogin-resetlink'     => 'Eus ankevys genowgh agas manylyon omgelmy?',
'createaccountmail'       => 'der e-bost',
'createaccountreason'     => 'Acheson:',
'badretype'               => 'Ny wra omdhesedhes an geryow-tremena entrys genowgh.',
'userexists'              => "Yma'n hanow usyer entrys genowgh ow pos usys seulabres.
Dowisowgh hanow aral mar pleg.",
'loginerror'              => 'Gwall omgelmy',
'createaccounterror'      => 'Ny veu possybyl formya an acont: $1',
'nocookiesnew'            => 'Formys yw an acont, mes nyns owgh why omgelmys.
Yma {{SITENAME}} owth usya cookies rag omgelmy devnydhyoryon.
Dialosegys yw cookies war agas jynn amontya.
Gwrewgh aga galosegy, hag omgelmowgh dre usya agas hanow usyer ha ger tremena noweth.',
'nocookieslogin'          => 'Yma {{SITENAME}} owth usya cookies rag omgelmi devnydhyoryon.
Dialosegys yw cookies war agas jynn amontya.
Gwrewgh aga galosegi hag assaya arta.',
'noname'                  => 'Ny wrussowgh why ry hanow usyer da.',
'loginsuccess'            => "'''Omgelmys owgh why lemmyn orth {{SITENAME}} avel \"\$1\".'''",
'nouserspecified'         => 'Res yw dhywgh ry hanow usyer.',
'wrongpassword'           => 'Camm o an ger tremena.
Assayowgh arta mar pleg.',
'wrongpasswordempty'      => 'Gwag o an ger-tremena res. Assayowgh arta mar pleg.',
'mailmypassword'          => 'E-bostya ger tremena nowyth',
'noemailcreate'           => 'Res yw dhewgh ry trigva ebost da',
'accountcreated'          => 'Acont formys',
'accountcreatedtext'      => 'Formys re beu an acont rag $1.',
'loginlanguagelabel'      => 'Yeth: $1',

# Change password dialog
'resetpass'                 => 'Chanjya ger-tremena',
'resetpass_header'          => 'Chanjya ger tremena an acont',
'oldpassword'               => 'Ger tremena coth:',
'newpassword'               => 'Ger tremena noweth:',
'resetpass-submit-loggedin' => 'Chanjya an ger-tremena',
'resetpass-submit-cancel'   => 'Hedhi',

# Special:PasswordReset
'passwordreset-username' => 'Hanow usyer:',
'passwordreset-email'    => 'Trigva ebost:',

# Edit page toolbar
'bold_sample'     => 'Text tew',
'bold_tip'        => 'Text tew',
'italic_sample'   => 'Text italek',
'italic_tip'      => 'Text italek',
'link_sample'     => 'Titel an gevren',
'link_tip'        => 'Kevren bervedhel',
'extlink_sample'  => 'http://www.example.com titel an gevren',
'extlink_tip'     => 'Kevren a-ves (na ankevowgh an rager http://)',
'headline_sample' => 'Text an titel',
'headline_tip'    => 'Pennlinen nivel 2',
'nowiki_sample'   => 'Keworrowgh text heb furvyans omma',
'nowiki_tip'      => 'Sconya aswon furvyans wiki',
'image_tip'       => 'Restren neythys',
'media_tip'       => 'Kevren restren',
'sig_tip'         => 'Agas sinans gans stampa-termyn',

# Edit pages
'summary'                          => 'Derivas cot:',
'subject'                          => 'Testen/Pennlinen:',
'minoredit'                        => 'Chanj bian yw hemma',
'watchthis'                        => 'Golyas an folen-ma',
'savearticle'                      => 'Gwitha',
'preview'                          => 'Ragwel',
'showpreview'                      => 'Ragweles',
'showdiff'                         => 'Disqwedhes an chanjyow',
'anoneditwarning'                  => "'''Gwarnyans:''' Nyns owgh why omgelmys.
Recordys a vedh agas trigva IP yn istory an folen-ma.",
'summary-preview'                  => "Ragwel a'n derivas kot:",
'loginreqlink'                     => 'omgelmy',
'accmailtitle'                     => 'Danvenys yw an ger-tremena.',
'newarticle'                       => '(Nowyth)',
'noarticletext'                    => 'Nyns eus text y\'n folen-ma.
Why a yll [[Special:Search/{{PAGENAME}}|whilas titel an folen-ma]] yn folennow erel,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} whilas y\'n covnotennow kelmys],
po [{{fullurl:{{FULLPAGENAME}}|action=edit}} chanjya an folen-ma]</span>.',
'updated'                          => '(Nowedhys)',
'note'                             => "'''Noten:'''",
'previewnote'                      => "Gwrewgh perthy cov, nyns yw hemma marnas ragwel.''' Nyns yw gwithys agas chanjyow whath!",
'editing'                          => 'Ow chanjya $1',
'editingsection'                   => 'ow chanjya $1 (rann)',
'editingcomment'                   => 'ow chanjya $1 (rann noweth)',
'yourtext'                         => 'Agas text',
'yourdiff'                         => 'Dyffransow',
'templatesused'                    => '{{PLURAL:$1|Scantlyn|Scantlyns}} usys war an folen-ma:',
'templatesusedpreview'             => "{{PLURAL:$1|Scantlyn|Scantlyns}} usys y'n ragwel-ma:",
'template-protected'               => '(gwithys)',
'template-semiprotected'           => '(hanter-difresys)',
'hiddencategories'                 => 'Esel a {{PLURAL:$1|1 glass cudhys|$1 class cudhys}} yw an folen-ma:',
'permissionserrorstext-withaction' => 'Nyns eus cummyes dhywgh dhe $2, rag an {{PLURAL:$1|acheson|achesonys}} a sew:',
'moveddeleted-notice'              => 'Diles yw an folen-ma.
Yma covnoten dhileans ha gwayans an folen a-woles.',
'log-fulllog'                      => 'Gweles an govnoten dien',

# "Undo" feature
'undo-summary' => 'Diswul amendyans $1 gans [[Special:Contributions/$2|$2]] ([[User talk:$2|kescows]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nyns yw possybyl formya an acont',

# History pages
'viewpagelogs'           => 'Gweles covnotennow an folen-ma',
'currentrev'             => 'Amendyans diwettha',
'currentrev-asof'        => 'An chanj diwettha a-ban $1',
'revisionasof'           => 'Versyon an folen a-ban $1',
'revision-info'          => 'Amendyans a-ban $1 gans $2',
'previousrevision'       => '← Amendyans cottha',
'nextrevision'           => 'Amendyans nowettha →',
'currentrevisionlink'    => 'An amendyans diwettha',
'cur'                    => 'lemmyn',
'next'                   => 'nessa',
'last'                   => 'kens',
'page_first'             => 'kensa',
'page_last'              => 'kens',
'histlegend'             => "Dowis dyffransow: Merkyowgh kistennow radyo a'n amendyansow dhe gehevely, ha gwascowgh 'entra' po an boton orth goles an folen.<br />
Alwhedh: '''({{int:cur}})''' = an dyffrans dhyworth an amendyans diwettha, '''({{int:last}})''' = an dyffrans dhyworth an amendyans kens, '''{{int:minoreditletter}}''' = chanj bian.",
'history-fieldset-title' => 'Peury an istory',
'history-show-deleted'   => 'Re diles yn unnik',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision feed
'history-feed-item-nocomment' => '$1 dhe $2',

# Revision deletion
'rev-delundel'           => 'disqwedhes/cudha',
'revdel-restore'         => 'chanjya an hewelder',
'revdel-restore-deleted' => 'amendyansow diles',
'revdel-restore-visible' => 'amendyansow gweladow',
'pagehist'               => 'Istory an folen',

# History merging
'mergehistory-reason' => 'Cheson:',

# Merge log
'revertmerge' => 'Disworunya',

# Diffs
'history-title'            => 'Istory an folen "$1"',
'difference'               => '(Dyffrans ynter an amendyansow)',
'difference-multipage'     => '(Dyffrans ynter an folennow)',
'lineno'                   => 'Linen $1:',
'compareselectedversions'  => 'Kehevely an amendyansow dowisyes',
'showhideselectedversions' => 'Disqwedhes/cudha amendyansow dowisyes',
'editundo'                 => 'diswul',

# Search results
'searchresults'                    => 'Sewyansow whilas',
'searchresults-title'              => 'Sewyansow whilas rag "$1"',
'searchresulttext'                 => 'Rag moy kedhlow a-dro dhe whilas yn {{SITENAME}}, gwelowgh [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Why a wrug whilas \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|keniver folen ow talleth gans "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|keniver folen ow kevrenna dhe "$1"]])',
'searchsubtitleinvalid'            => "Why a wrug whilas '''$1'''",
'notitlematches'                   => 'Nyns eus titel folen ow machya',
'notextmatches'                    => 'Nyns eus text folen ow machya',
'prevn'                            => 'kens {{PLURAL:$1|$1}}',
'nextn'                            => 'nessa {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|sewyans|sewyans}} kens',
'nextn-title'                      => '$1 {{PLURAL:$1|sewyans|sewyans}} nessa',
'viewprevnext'                     => 'Gweles ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Etholyow whilans',
'searchmenu-exists'                => "''Yma folen henwys \"[[:\$1]]\" war an wiki-ma'''",
'searchmenu-new'                   => "'''Gwruthyl an folen \"[[:\$1]]\" war an wiki-ma!'''",
'searchhelp-url'                   => 'Help:Gweres',
'searchprofile-articles'           => 'Folennow dhalhen',
'searchprofile-project'            => 'Folennow gweres ha ragdres',
'searchprofile-images'             => 'Liesmedia',
'searchprofile-everything'         => 'Puptra',
'searchprofile-advanced'           => 'Avonsys',
'searchprofile-articles-tooltip'   => 'Whilas yn $1',
'searchprofile-project-tooltip'    => 'Whilas yn $1',
'searchprofile-images-tooltip'     => 'Whilas restrennow',
'searchprofile-everything-tooltip' => 'Whilas yn pub teller (yn folennow kescows ynwedh)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-result-category-size'      => '{{PLURAL:$1|1 esel|$1 esel}} ({{PLURAL:$2|1 isglass|$2 isglass}}, {{PLURAL:$3|1 restren|$3 restren}})',
'search-redirect'                  => '(daswedyans $1)',
'search-section'                   => '(rann $1)',
'search-suggest'                   => 'A wrussowgh why menya: $1',
'search-interwiki-caption'         => 'Ragdresow whor',
'search-interwiki-default'         => '$1 sewyansow:',
'search-interwiki-more'            => '(moy)',
'search-mwsuggest-enabled'         => 'gans profyansow',
'search-mwsuggest-disabled'        => 'heb profyansow',
'search-relatedarticle'            => 'Kelmys',
'mwsuggest-disable'                => 'Dialosegy profyansow AJAX',
'searcheverything-enable'          => 'Whilas yn keniver spas-hanow',
'searchrelated'                    => 'kelmys',
'searchall'                        => 'oll',
'showingresultsheader'             => "{{PLURAL:$5|Sewyans '''$1''' dhyworth '''$3'''|Sewyansow '''$1 - $2''' dhyworth '''$3'''}} rag '''$4'''",
'nonefound'                        => "'''Noten''': Nyns yw marnas rann a'n spasys-hanow whilys dre dhefowt.
Gwrewgh assaya rag-gorra agas govyn gans ''all:'' rag whilas yn pub teller (ynnans an folennow kescows, scantlyns, etc), po usyowgh an spas-hanow whensys avel rag-gorrans.",
'search-nonefound'                 => 'Nyns esa sewyansow ow machya an govyn.',
'powersearch'                      => 'Whilans avonsys',
'powersearch-legend'               => 'Whilans avonsys',
'powersearch-ns'                   => 'Whilas yn spasys-hanow:',
'powersearch-redir'                => 'Gorra an daswedyansow yn rol',
'powersearch-field'                => 'Whilas',
'powersearch-togglelabel'          => 'Dowis:',
'powersearch-toggleall'            => 'Oll',
'powersearch-togglenone'           => 'Nagonen',
'search-external'                  => 'Whilans a-ves',

# Preferences page
'preferences'                 => 'Dowisyansow',
'mypreferences'               => 'Dowisyansow',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Crohen',
'prefs-datetime'              => 'Dedhyans hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Etholyow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Whilas',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktika',
'timezoneregion-arctic'       => 'Arktek',
'timezoneregion-asia'         => 'Asi',
'timezoneregion-atlantic'     => 'Mor Atlantek',
'timezoneregion-australia'    => 'Ostrali',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Mor Eyndek',
'timezoneregion-pacific'      => 'Mor Kosel',
'prefs-searchoptions'         => 'Etholyow hwilas',
'prefs-files'                 => 'Restrednow',
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
'prefs-help-email'            => 'A-dhowis yw ry trigva ebost, mes res yw y sensy rag dassettya agas ger tremena mars yw ankevys.',
'prefs-help-email-others'     => 'Why a yll dowis gasa dhe re erel kestava dhywgh der ebost yn unn glyckya kevren war agas folen devnydhyer po kescows.
Nyns yw disqwedhys agas trigva ebost pan wrella devnydhyoryon erel kestava dhywgh.',
'prefs-signature'             => 'Sinans',
'prefs-advancedediting'       => 'Etholyow avoncys',
'prefs-advancedrc'            => 'Etholyow avoncys',
'prefs-advancedrendering'     => 'Etholyow avoncys',
'prefs-advancedsearchoptions' => 'Etholyow avoncys',
'prefs-advancedwatchlist'     => 'Etholyow avoncys',

# User rights
'userrights-groupsmember' => 'Esel a:',
'userrights-reason'       => 'Cheson:',

# Groups
'group'       => 'Bagas:',
'group-user'  => 'Devnydhyoryon',
'group-bot'   => 'Botow',
'group-sysop' => 'Menystoryon',
'group-all'   => '(oll)',

'group-user-member'  => '{{GENDER:$1|Devnydhyer}}',
'group-bot-member'   => '{{GENDER:$1|bot}}',
'group-sysop-member' => '{{GENDER:$1|menyster}}',

'grouppage-user'  => '{{ns:project}}:Devnydhyoryon',
'grouppage-bot'   => '{{ns:project}}:Botow',
'grouppage-sysop' => '{{ns:project}}:Menystoryon',

# Rights
'right-read'          => 'Redya folednow',
'right-edit'          => 'Chanjya folednow',
'right-createtalk'    => 'Gwruthyl folednow keskows',
'right-createaccount' => 'Formya akontow devnydhyer nowyth',
'right-move'          => 'Gwaya folednow',
'right-movefile'      => 'Gwaya restrednow',
'right-upload'        => 'Ughkarga restrednow',
'right-delete'        => 'Dilea folednow',

# User rights log
'rightslog' => 'Kovnoten gwiryow an devnydhyer',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'chanjya an folen-ma',
'action-move'     => 'gwaya an folen ma',
'action-movefile' => 'gwaya an restren ma',
'action-upload'   => 'ughkarga an restren-ma',
'action-delete'   => 'dilea an folen-ma',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|chanj|chanj}}',
'recentchanges'                  => 'Chanjyow a-dhiwedhes',
'recentchanges-legend'           => 'Etholyow an chanjyow a-dhiwedhes',
'recentchangestext'              => "War'n folen-ma y hellowgh hwi sewya an chanjyow diwettha eus gwres dhe'n wiki.",
'recentchanges-feed-description' => "Helerhy an chanjyow diwettha dhe'n wiki y'n feed-ma.",
'recentchanges-label-newpage'    => 'Y feu gwres folen nowyth gans an chanj-ma',
'recentchanges-label-minor'      => 'Chanj bian yw hemma',
'recentchanges-label-bot'        => 'An chanj-ma a veu gwres gans bot',
'rcnote'                         => "A-woles yma'n {{PLURAL:$1|'''1''' chanj}} y'n {{PLURAL:$2|jedh|'''$2''' dedh}} diwettha, a-ban $5, $4.",
'rclistfrom'                     => 'Disqwedhes chanjyow nowyth ow talleth a-ban $1.',
'rcshowhideminor'                => '$1 chanjyow bian',
'rcshowhidebots'                 => '$1 botow',
'rcshowhideliu'                  => '$1 devnydhoryon omgelmys',
'rcshowhideanons'                => '$1 devnydhyoryon dihanow',
'rcshowhidemine'                 => '$1 ow chanjyow',
'rclinks'                        => "Disqwedhes an $1 chanj diwettha gwres y'n $2 dedh diwettha<br />$3",
'diff'                           => 'dyffrans',
'hist'                           => 'istory',
'hide'                           => 'Cudha',
'show'                           => 'Disqwedhes',
'minoreditletter'                => 'B',
'newpageletter'                  => 'N',
'boteditletter'                  => 'bot',
'newsectionsummary'              => '/* $1 */ radn nowyth',
'rc-enhanced-expand'             => 'Disqwedhes an manylyon (res yw JavaScript)',
'rc-enhanced-hide'               => 'Cudha manylyon',

# Recent changes linked
'recentchangeslinked'          => 'Chanjyow kelmys',
'recentchangeslinked-feed'     => 'Chanjyow kelmys',
'recentchangeslinked-toolbox'  => 'Chanjyow kelmys',
'recentchangeslinked-title'    => 'Chanjyow kelmys dhe "$1"',
'recentchangeslinked-noresult' => 'Nyns esa chanj veth war folennow kevrennys dres an termyn res.',
'recentchangeslinked-summary'  => "Hemm yw rol a janjyow a-dhiwedhes gwres dhe folennow yw kevrennys dhyworth folen res (po dhe esely a glass res).
'''Tew''' yw folennow eus war agas [[Special:Watchlist|rol golyas]].",
'recentchangeslinked-page'     => 'Hanow an folen:',
'recentchangeslinked-to'       => "Disqwedhes chanjyow dhe folennow kevennys dhe'n folen res yn le",

# Upload
'upload'          => 'Ughcarga restren',
'uploadbtn'       => 'Ughkarga restren',
'uploadlogpage'   => 'Covnoten ughcarga',
'filename'        => 'Hanow-restren',
'filedesc'        => 'Derivas cot',
'filesource'      => 'Pednfenten:',
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
'filehist'                  => 'Istory an restren',
'filehist-help'             => 'Clyckyowgh war dedhyans/eur rag gweles an folen del veu nena.',
'filehist-deleteall'        => 'dilea oll',
'filehist-deleteone'        => 'dilea',
'filehist-revert'           => 'trebuchya',
'filehist-current'          => 'a-lemmyn',
'filehist-datetime'         => 'Dedhyans/Eur',
'filehist-thumb'            => 'Skeusennik',
'filehist-thumbtext'        => 'Skeusennik rag an versyon a-ban $1',
'filehist-user'             => 'Devnydhyer',
'filehist-dimensions'       => 'Mensow',
'filehist-comment'          => 'Ger',
'imagelinks'                => 'Devnydh an restren',
'linkstoimage'              => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew ow kevrenna dhe'n restren-ma:",
'nolinkstoimage'            => "Nyns eus folen ow kevrenna dhe'n restren-ma.",
'sharedupload'              => 'Yma an folen-ma ow tos dhyworth $1 ha hi a ell bos usys gans ragdresow erel.',
'sharedupload-desc-here'    => "Yma'n restren-ma ow tos dhyworth $1 ha hi a alsa bos yn-dann devnydh gans ragdresow erel.
Yma'n descrifans war y [$2 folen dhescrifans] disqwedhys a-woles.",
'uploadnewversion-linktext' => "Ughkarga versyon nowyth a'n restren-ma",

# File deletion
'filedelete'        => 'Dilea $1',
'filedelete-legend' => 'Dilea an restren',
'filedelete-submit' => 'Dilea',

# MIME search
'download' => 'iskarga',

# Unwatched pages
'unwatchedpages' => 'Folednow nag eus ow pos golyes',

# List redirects
'listredirects' => 'Rol an daswedyansow',

# Unused templates
'unusedtemplates'    => 'Skantlyns heb devnydh',
'unusedtemplateswlh' => 'kevrednow erel',

# Random page
'randompage' => 'Folen dre jons',

# Statistics
'statistics'       => 'Statystygyon',
'statistics-pages' => 'Folednow',

'brokenredirects-edit'   => 'chanjya',
'brokenredirects-delete' => 'dilea',

'withoutinterwiki'        => 'Folednow heb kevrednow yeth',
'withoutinterwiki-submit' => 'Diskwedhes',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt|bayt}}',
'nmembers'                => '$1 {{PLURAL:$1|esel|esel}}',
'uncategorizedpages'      => 'Folednow heb klass',
'uncategorizedcategories' => 'Klassys heb klass',
'uncategorizedimages'     => 'Restrednow heb klass',
'uncategorizedtemplates'  => 'Skantlyns heb klass',
'unusedcategories'        => 'Klassys gwag',
'unusedimages'            => 'Restrednow heb devnydh',
'prefixindex'             => 'Keniver folen gans an rager',
'shortpages'              => 'Folednow berr',
'longpages'               => 'Folednow hir',
'protectedpages'          => 'Folednow difresys',
'protectedtitles'         => 'Titlys difresys',
'usercreated'             => '{{GENDER:$3|Formyes}} an $1 dhe $2',
'newpages'                => 'Folennow nowyth',
'newpages-username'       => 'Hanow-usyer:',
'ancientpages'            => 'Folednow kottha',
'move'                    => 'Gwaya',
'movethispage'            => 'Gwaya an folen-ma',
'pager-newer-n'           => '{{PLURAL:$1|1 nowettha|$1 nowettha}}',
'pager-older-n'           => '{{PLURAL:$1|1 cottha|$1 cottha}}',

# Book sources
'booksources'               => 'Pennfentynyow lyver',
'booksources-search-legend' => 'Whilas pennfentynyow lyver',
'booksources-go'            => 'Mos',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Covnotennow',

# Special:AllPages
'allpages'       => 'Keniver folen',
'alphaindexline' => '$1 dhe $2',
'prevpage'       => 'Folen gens ($1)',
'allpagesfrom'   => 'Diskwedhes folednow ow talleth orth:',
'allpagesto'     => 'Diskwedhes folednow ow tiwedha orth:',
'allarticles'    => 'Keniver folen',
'allpagesprev'   => 'Kens',
'allpagesnext'   => 'Nessa',
'allpagessubmit' => 'Mos',

# Special:Categories
'categories' => 'Classys',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch'      => 'Hwilas kevrednow a-ves',
'linksearch-ok'   => 'Hwilas',
'linksearch-line' => '$1 yw kevrennys dhyworth $2',

# Special:ListUsers
'listusers-submit' => 'Diskwedhes',

# Special:Log/newusers
'newuserlogpage' => 'Covnoten formya acontow devnydhyer',

# Special:ListGroupRights
'listgrouprights-members' => '(rol esely)',

# E-mail user
'emailuser'       => 'E-bostya an devnydhyer-ma',
'emailpage'       => 'E-bostya devnydhyer',
'defemailsubject' => 'Ebost danvenys dre {{SITENAME}} gans an devnydhyer "$1"',
'emailfrom'       => 'Dhyworth:',
'emailto'         => 'Dhe:',
'emailmessage'    => 'Messach:',
'emailsend'       => 'Danvon',

# Watchlist
'watchlist'         => 'Ow rol golyas',
'mywatchlist'       => 'Ow rol golyas',
'watchlistfor2'     => 'Rag $1 ($2)',
'watch'             => 'Golyas',
'watchthispage'     => 'Golyas an folen-ma',
'unwatch'           => 'Diswolyas',
'watchlist-details' => 'Yma {{PLURAL:$1|$1 folen}} war agas rol golyas, marnas folennow kescows.',
'wlshowlast'        => 'Disqwedhes an $1 our $2 dedh $3 diwettha',
'watchlist-options' => 'Etholyow an rol golyas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ow kolyas...',
'unwatching' => 'Ow tisgolyas...',

# Delete
'deletepage'            => 'Dilea an folen',
'delete-confirm'        => 'Dilea "$1"',
'delete-legend'         => 'Dilea',
'actioncomplete'        => 'Cowlwres yw an gwrians',
'actionfailed'          => 'An gwrians a fyllas',
'deletedtext'           => '"$1" yw dileys.
Gwelowgh $2 rag kovadh a dhileansow a-dhiwedhes.',
'dellogpage'            => 'Covnoten dhilea',
'deletecomment'         => 'Cheson:',
'deleteotherreason'     => 'Cheson aral/keworansel:',
'deletereasonotherlist' => 'Cheson aral',

# Rollback
'rollbacklink' => 'restorya',

# Protect
'protectlogpage'          => 'Covnoten dhifres',
'protectedarticle'        => 'a dhifresas "[[$1]]"',
'protectcomment'          => 'Cheson:',
'protectexpiry'           => 'Ow tiwedha:',
'protect_expiry_invalid'  => 'Drog yw termyn an diwedh.',
'protect_expiry_old'      => "Yma'n termyn diwedh e'n termyn eus passyes.",
'protect-level-sysop'     => 'Menystroryon hepken',
'protect-summary-cascade' => 'ow froslabma',
'protect-expiring'        => 'y hwra diwedha $1 (UTC)',
'restriction-type'        => 'Kubmyas:',
'pagesize'                => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Chanjya',
'restriction-move'   => 'Gwaya',
'restriction-create' => 'Gwruthyl',
'restriction-upload' => 'Ughkarga',

# Undelete
'undeletelink'              => 'gweles/restorya',
'undeleteviewlink'          => 'gweles',
'undelete-search-submit'    => 'Hwilas',
'undelete-show-file-submit' => 'Ya',

# Namespace form on various pages
'namespace'      => 'Spas-hanow:',
'invert'         => 'Trebuchya an dowisyans',
'blanknamespace' => '(Penn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow $1',
'mycontris'           => 'Kevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'Dhyworth an mis (ha moy a-varr):',
'year'                => 'Dhyworth an vledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Disqwedhes yn unnik kevrohow acontow noweth',
'sp-contributions-blocklog' => 'covnoten lettya',
'sp-contributions-uploads'  => 'ughcargansow',
'sp-contributions-logs'     => 'covnotennow',
'sp-contributions-talk'     => 'kescows',
'sp-contributions-search'   => 'Whilas kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-toponly'  => 'Disqwedhes yn unnik chanjyow yw amendyansow diwettha',
'sp-contributions-submit'   => 'Whilas',

# What links here
'whatlinkshere'            => "Pandr'eus ow kevrenna omma",
'whatlinkshere-title'      => 'Folennow ow kevrenna dhe "$1"',
'whatlinkshere-page'       => 'Folen:',
'linkshere'                => "Yma'n folennow a sew ow kevrenna dhe '''[[:$1]]''':",
'nolinkshere'              => "Nyns eus folen ow kevrenna dhe '''[[:$1]]'''.",
'isredirect'               => 'folen daswedyans',
'istemplate'               => 'treuscludyans',
'isimage'                  => 'kevren an restren',
'whatlinkshere-prev'       => '{{PLURAL:$1|kens|kens $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'      => '← kevrennow',
'whatlinkshere-hideredirs' => '$1 daswedyansow',
'whatlinkshere-hidetrans'  => '$1 treuscludyans',
'whatlinkshere-hidelinks'  => '$1 kevrennow',
'whatlinkshere-hideimages' => '$1 kevrennow restren',
'whatlinkshere-filters'    => 'Sidhlow',

# Block/unblock
'blockip'                    => 'Lettya devnydhyer',
'ipadressorusername'         => 'Trigva IP po hanow-usyer:',
'ipbreason'                  => 'Cheson:',
'ipbreasonotherlist'         => 'Cheson aral',
'ipboptions'                 => '2 our:2 hours,1 dhedh:1 day,3 dedh:3 days,1 seythen:1 week,2 seythen:2 weeks,1 vis:1 month,3 mis:3 months,6 mis:6 months,1 vledhen:1 year,heb diwedh:infinite',
'ipb-blocklist-contribs'     => 'Kevrohow rag $1',
'ipblocklist'                => 'Devnydhyoryon lettyes',
'ipblocklist-submit'         => 'Hwilas',
'blocklink'                  => 'lettya',
'unblocklink'                => 'dislettya',
'change-blocklink'           => 'chanjya an lettyans',
'contribslink'               => 'kevrohow',
'blocklogpage'               => 'Covnoten lettya',
'blocklogentry'              => 'a lettyas [[$1]], bys dhe $2 $3',
'unblocklogentry'            => 'dislettyas $1',
'block-log-flags-anononly'   => 'devnydhyoryon dihanow hepken',
'block-log-flags-nocreate'   => 'dialosegys yw formya akontow',
'block-log-flags-hiddenname' => 'hanow-usyer kovys',

# Move page
'move-page'        => 'Gwaya $1',
'move-page-legend' => 'Gwaya folen',
'movearticle'      => 'Gwaya an folen:',
'newtitle'         => 'Dhe ditel nowyth:',
'move-watch'       => 'Golya an folen-ma',
'movepagebtn'      => 'Gwaya an folen',
'pagemovedsub'     => 'An gwarnyans a sowenas',
'movepage-moved'   => '\'\'\'Gwayes yw "$1" war-tu "$2"\'\'\'',
'movedto'          => 'gwayes war-tu',
'movelogpage'      => 'Covnoten waya',
'movereason'       => 'Cheson:',
'revertmove'       => 'trebuchya',

# Export
'export'        => 'Esperthy folennow',
'export-addcat' => 'Keworra',
'export-addns'  => 'Keworra',

# Namespace 8 related
'allmessagesname'    => 'Hanow',
'allmessagesdefault' => 'Text messach defowt',

# Thumbnails
'thumbnail-more'  => 'Brashe',
'thumbnail_error' => 'Gwall ow formya skeusennik: $1',

# Special:Import
'import'                  => 'Ymperthi folednow',
'import-interwiki-submit' => 'Ymperthi',
'import-upload-filename'  => 'Hanow-restren:',
'importstart'             => 'Owth ymperthi folednow...',
'import-noarticle'        => 'Nyns eus folen veth dhe ymperthi!',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Agas folen dhevnydhyer',
'tooltip-pt-mytalk'               => 'Agas folen gows',
'tooltip-pt-preferences'          => 'Agas dowisyansow',
'tooltip-pt-watchlist'            => 'Rol a folennow esowgh why ow colyas rag chanjyow',
'tooltip-pt-mycontris'            => "Rol a'gas kevrohow",
'tooltip-pt-login'                => 'Gwell via dhywgh mar tewgh why hag omgelmy, mes nyns yw besy',
'tooltip-pt-logout'               => 'Digelmy',
'tooltip-ca-talk'                 => "Dadhel a-dro dhe'n folen",
'tooltip-ca-edit'                 => 'Why a yll chanjya an folen-ma. Gwrewgh usya an boton Ragweles kens gwitha mar pleg.',
'tooltip-ca-addsection'           => 'Dalleth rann nowyth',
'tooltip-ca-viewsource'           => 'Alwhedhys yw an folen-ma.
Why a yll gweles hy fennfenten.',
'tooltip-ca-history'              => "Amendyansow coth a'n folen-ma",
'tooltip-ca-protect'              => 'Difres an folen-ma',
'tooltip-ca-delete'               => 'Dilea an folen-ma',
'tooltip-ca-move'                 => 'Gwaya an folen-ma',
'tooltip-ca-watch'                => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-ca-unwatch'              => 'Dilea an folen-ma dhyworth agas rol golyas',
'tooltip-search'                  => 'Whilas yn {{SITENAME}}',
'tooltip-search-go'               => 'Mos dhe folen gans an keth hanow-ma, mars eus',
'tooltip-search-fulltext'         => "Whilas an text-ma y'n folennow",
'tooltip-p-logo'                  => "Mos dhe'n folen dre",
'tooltip-n-mainpage'              => "Mos dhe'n folen dre",
'tooltip-n-mainpage-description'  => "Mos dhe'n folen dre",
'tooltip-n-portal'                => "A-dro dhe'n ragdres, an peth a yllowgh why gwul, ple dhe gavos taclow",
'tooltip-n-currentevents'         => 'Cavos kedhlow a-dro dhe dharvosow a-lemmyn',
'tooltip-n-recentchanges'         => "Rol a janjyow a-dhiwedhes y'n wiki",
'tooltip-n-randompage'            => 'Carga folen dre jons',
'tooltip-n-help'                  => 'Gweres',
'tooltip-t-whatlinkshere'         => 'Rol a bub folen wiki ow kevrenna dhe omma',
'tooltip-t-recentchangeslinked'   => 'Chanjyow a-dhiwedhes yn folennow eus kevrennys dhyworth an folen-ma',
'tooltip-feed-rss'                => 'Feed RSS rag an folen-ma',
'tooltip-feed-atom'               => 'Feed Atom rag an folen-ma',
'tooltip-t-contributions'         => 'Gweles rol a gevrohow an devnydhyer-ma',
'tooltip-t-emailuser'             => "Danvon e-bost dhe'n devnydhyer-ma",
'tooltip-t-upload'                => 'Ughcarga restrennow',
'tooltip-t-specialpages'          => 'Rol a geniver folen arbennek',
'tooltip-t-print'                 => "Versyon pryntyadow a'n folen-ma",
'tooltip-t-permalink'             => "Kevren fast dhe'n amendyans-ma a'n folen",
'tooltip-ca-nstab-main'           => 'Gweles an folen',
'tooltip-ca-nstab-user'           => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-special'        => 'Folen arbennek yw hemma; ny yllowgh why chanjya an folen hy honan.',
'tooltip-ca-nstab-project'        => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'          => 'Gweles folen an restren',
'tooltip-ca-nstab-template'       => 'Gweles an scantlyn',
'tooltip-ca-nstab-category'       => 'Gweles folen an class',
'tooltip-minoredit'               => 'Merkya hemma avel chanj bian',
'tooltip-save'                    => 'Gwitha agas chanjyow',
'tooltip-preview'                 => 'Ragweles agas chanjyow; gwrewgh usya hemma kens gwitha mar pleg!',
'tooltip-diff'                    => "Disqwedhes an chanjyow eus gwres genowgh dhe'n text",
'tooltip-compareselectedversions' => "Gweles an dyffransow ynter an dhew janjyow dowisyes a'n folen-ma",
'tooltip-watch'                   => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-rollback'                => '"Restorya" a wra trebuchya chanjyow gwres dhe\'n folen-ma gans an diwettha devnydhyer yn unn glyck',
'tooltip-undo'                    => '"Diswul" a wra trebuchya an chanj-ma hag egery an furvlen janjya y\'n modh ragweles. Y hyllir keworra acheson y\'n derivas cot.',
'tooltip-summary'                 => 'Entrowgh derivas cot',

# Attribution
'siteuser'         => 'devnydhyer {{SITENAME}} $1',
'lastmodifiedatby' => 'An folen-ma a veu kens chanjys dhe $2, $1 gans $3.',
'siteusers'        => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Chanj cottha',
'nextdiff'     => 'Chanj nowettha →',

# Media information
'file-info-size' => '$1 × $2 pixel, mens an restren: $3, sort MIME: $4',
'file-nohires'   => 'Nyns eus clerder uhella cavadow.',
'svg-long-desc'  => 'Restren SVG, $1 × $2 pixel yn hanow, mens an restren: $3',
'show-big-image' => 'Clerder leun',

# Special:NewFiles
'ilsubmit' => 'Hwilas',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Yma dhe'n restren-ma kedhlow keworansel, dres lycklod keworrys dhyworth an camera besyel po an scanyer usys rag hy gwruthyl po hy besya. Mars yw chanjys an restren dhyworth hy studh gwredhek, possybyl yw na veu nebes manylyon nowedhys.",
'metadata-expand'   => 'Diskwedhes manylyon ystydnys',
'metadata-collapse' => 'Kudha manylyon ystydnys',

# EXIF tags
'exif-imagewidth'  => 'Les',
'exif-imagelength' => 'Uhelder',
'exif-artist'      => 'Awtour',

'exif-meteringmode-255' => 'Aral',

'exif-contrast-1' => 'Medhel',
'exif-contrast-2' => 'Kales',

# External editor support
'edit-externally' => 'Chanjya an restren-ma dre dowlen a-ves',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'oll',
'namespacesall' => 'oll',
'monthsall'     => 'oll',

# Multipage image navigation
'imgmultipageprev' => '← folen kens',
'imgmultipagenext' => 'folen nessa →',
'imgmultigo'       => 'Ke!',

# Table pager
'table_pager_limit_submit' => 'Mos',

# Auto-summaries
'autoredircomment' => 'Daswedyas an folen war-tu [[$1]]',
'autosumm-new'     => "Folen formyes gans: '$1'",

# Watchlist editing tools
'watchlisttools-view' => 'Gweles chanjyow longus',
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
'fileduplicatesearch-submit'   => 'Hwilas',

# Special:SpecialPages
'specialpages' => 'Folennow arbennek',

# Special:Tags
'tags-edit' => 'chanjya',

);
