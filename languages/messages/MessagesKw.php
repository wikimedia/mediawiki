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
'tog-hideminor'      => 'Kudha chanjyow bian en chanjyow a-dhiwedhes',
'tog-watchcreations' => "Keworra folednow gwruthys genev dhe'm rol golyas",
'tog-watchdefault'   => "Keworra folednow chanjys genev dhe'm rol golyas",
'tog-watchmoves'     => "Keworra folednow gwayes genev dhe'm rol golyas",
'tog-watchdeletion'  => "Keworra folednow dileys genev dhe'm rol golyas",

'underline-always'  => 'Pub pres',
'underline-never'   => 'Nevra',
'underline-default' => 'Defowt an beurel',

# Font style option in Special:Preferences
'editfont-default' => 'Defowt an beurel',

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
'february'      => 'Hwevrel',
'march'         => 'Meurth',
'april'         => 'Ebrel',
'may_long'      => 'Me',
'june'          => 'Metheven',
'july'          => 'Gortheren',
'august'        => 'Est',
'september'     => 'Gwydngala',
'october'       => 'Hedra',
'november'      => 'Du',
'december'      => 'Kevardhu',
'january-gen'   => 'Genver',
'february-gen'  => 'Hwevrel',
'march-gen'     => 'Meurth',
'april-gen'     => 'Ebrel',
'may-gen'       => 'Me',
'june-gen'      => 'Metheven',
'july-gen'      => 'Gortheren',
'august-gen'    => 'Est',
'september-gen' => 'Gwydngala',
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
'pagecategories'                 => '{{PLURAL:$1|Klass|Klassys}}',
'category_header'                => 'Folednow e\'n klass "$1"',
'subcategories'                  => 'Isglassys',
'category-media-header'          => 'Media e\'n klass "$1"',
'category-empty'                 => "''Nyns eus na folednow na media e'n klass-ma.''",
'hidden-categories'              => '{{PLURAL:$1|Klass kovys|Klass kovys}}',
'hidden-category-category'       => 'Klassys kovys',
'category-subcat-count'          => "{{PLURAL:$2|Nyns eus dhe'n klass-ma marnas an isglass a sew.|Yma dhe'n klass-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew, dhyworth sobm a $2.}}",
'category-subcat-count-limited'  => "Yma dhe'n klass-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew.",
'category-article-count'         => "{{PLURAL:$2|Nyns eus dhe'n klass-ma marnas an folen a sew.|Yma'n {{PLURAL:$1|folen|$1 folednow}} a sew e'n klass-ma, dhyworth sobm a $2.}}",
'category-article-count-limited' => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n klass-ma.",
'category-file-count'            => "{{PLURAL:$2|Nyns eus dhe'n klass-ma marnas an folen a sew.|Yma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n klass-ma, dhyworth sobm a $2.}}",
'category-file-count-limited'    => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n klass-ma.",
'listingcontinuesabbrev'         => 'pes.',

'about'         => 'A-dro dhe',
'newwindow'     => '(y hwra egeri en fenester nowyth)',
'cancel'        => 'Hedhi',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Ow folen',
'mytalk'        => 'Ow hows',
'anontalk'      => 'Keskows rag an drigva IP-ma',
'navigation'    => 'Lewyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Kavos',
'qbbrowse'       => 'Peuri',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen-ma',
'qbmyoptions'    => 'Ow folednow',
'qbspecialpages' => 'Folednow arbednek',
'faq'            => 'FAQ',

# Vector skin
'vector-action-addsection' => 'Keworra mater',
'vector-action-delete'     => 'Dilea',
'vector-action-move'       => 'Gwaya',
'vector-action-protect'    => 'Difres',
'vector-action-undelete'   => 'Disdhilea',
'vector-action-unprotect'  => 'Chanjya difresans',
'vector-view-create'       => 'Gwruthyl',
'vector-view-edit'         => 'Chanjya',
'vector-view-history'      => 'Gweles an istori',
'vector-view-view'         => 'Redya',
'vector-view-viewsource'   => 'Gweles an bednfenten',
'actions'                  => 'Gwriansow',
'namespaces'               => 'Spasys hanow',

'errorpagetitle'    => 'Gwall',
'returnto'          => 'Dehweles dhe $1.',
'tagline'           => 'Dhyworth {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Hwilas',
'searchbutton'      => 'Hwilas',
'go'                => 'Ke',
'searcharticle'     => 'Ke',
'history'           => 'Istori an folen',
'history_short'     => 'Istori',
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
'undelete_short'    => 'Disdhilea {{PLURAL:$1|udn janj|$1 chanj}}',
'viewdeleted_short' => 'Gweles {{PLURAL:$1|udn janj diles|$1 chanj diles}}',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen-ma',
'unprotect'         => 'Chanjya difresans',
'unprotectthispage' => 'Chanjya difresans an folen-ma',
'newpage'           => 'Folen nowyth',
'talkpage'          => "Dadhelva a-dro dhe'n folen-ma",
'talkpagelinktext'  => 'keskows',
'specialpage'       => 'Folen arbednek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Radn nowyth',
'talk'              => 'Keskows',
'views'             => 'Gwelow',
'toolbox'           => 'Boks toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajys',
'templatepage'      => 'Gweles folen an skantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an klass',
'viewtalkpage'      => 'Gweles an keskows',
'otherlanguages'    => 'En yethow erel',
'redirectedfrom'    => '(Daswedyes dhyworth $1)',
'redirectpagesub'   => 'Folen daswedyans',
'lastmodifiedat'    => 'An folen-ma a veu kens chanjys an $1, dhe $2.',
'jumpto'            => 'Labma dhe:',
'jumptonavigation'  => 'lewyans',
'jumptosearch'      => 'hwilas',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Kedhlow',
'copyright'            => 'Kavadow yw an dalgh en-dadn $1.',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lebmyn',
'currentevents-url'    => 'Project:Darvosow a-lebmyn',
'disclaimers'          => 'Avisyansow',
'disclaimerpage'       => 'Project:Avisyans ollgebmyn',
'edithelp'             => 'Gweres gans chanjya',
'edithelppage'         => 'Help:Chanjya',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Folen dre',
'mainpage-description' => 'Folen dre',
'policy-url'           => 'Project:Polici',
'portal'               => 'Porth an gemeneth',
'portal-url'           => 'Project:Porth an gemeneth',
'privacy'              => 'Polici privetter',
'privacypage'          => 'Project:Polici privetter',

'badaccess' => 'Gwall kubmyes',

'ok'                      => 'Sur',
'retrievedfrom'           => 'Daskevys dhyworth "$1"',
'youhavenewmessages'      => 'Yma $1 genowgh ($2).',
'newmessageslink'         => 'messajys nowyth',
'newmessagesdifflink'     => 'chanj kens',
'youhavenewmessagesmulti' => 'Yma messajys nowyth genowgh war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an bednfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an bednfenten',
'editsectionhint'         => 'Chanjya an radn: $1',
'toc'                     => 'Rol an folen',
'showtoc'                 => 'diskwedhes',
'hidetoc'                 => 'kudha',
'collapsible-expand'      => 'Efani',
'thisisdeleted'           => 'Gweles po restorya $1?',
'viewdeleted'             => 'Gweles $1?',
'restorelink'             => '{{PLURAL:$1|udn janj diles|$1 chanj diles}}',
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
'nstab-special'   => 'Folen arbednek',
'nstab-project'   => 'Folen towl',
'nstab-image'     => 'Restren',
'nstab-mediawiki' => 'Messach',
'nstab-template'  => 'Skantlyn',
'nstab-help'      => 'Gweres',
'nstab-category'  => 'Klass',

# General errors
'error'               => 'Gwall',
'databaseerror'       => 'Gwall database',
'readonly'            => 'Alhwedhys yw an database',
'missingarticle-rev'  => '(amendyans#: $1)',
'missingarticle-diff' => '(Dyffrans: $1, $2)',
'internalerror'       => 'Gwall a-bervedh',
'internalerror_info'  => 'Gwall a-bervedh: $1',
'filecopyerror'       => 'Ny ylles kopia an restren "$1" war-tu "$2".',
'filerenameerror'     => 'Ny ylles dashenwel an restren "$1" dhe "$2".',
'filedeleteerror'     => 'Ny ylles dilea an restren "$1".',
'filenotfound'        => 'Ny ylles kavos an restren "$1".',
'badtitle'            => 'Titel drog',
'viewsource'          => 'Gweles an bednfenten',

# Login and logout pages
'welcomecreation'         => '== Dynnargh, $1! ==
Gwruthys yw agas akont.
Na wrewgh ankevi dhe janjya agas [[Special:Preferences|dowisyansow {{SITENAME}}]].',
'yourname'                => 'Hanow usyer:',
'yourpassword'            => 'Ger tremena:',
'yourpasswordagain'       => 'Jynnskrifowgh agas ger tremena arta:',
'remembermypassword'      => "Porth kov a'm ger tremena war'n amontyer-ma (rag $1 {{PLURAL:$1|dydh|dydh}} dhe'n moyha)",
'securelogin-stick-https' => 'Gwitha junyes gans HTTPS wosa omgelmi',
'yourdomainname'          => 'Agas tiredh:',
'login'                   => 'Omgelmi',
'nav-login-createaccount' => 'Omgelmi / Formya akont nowyth',
'loginprompt'             => 'Res yw dhewgh galosegi cookies rag omgelmi orth {{SITENAME}}.',
'userlogin'               => 'Omgelmi / formya akont nowyth',
'userloginnocreate'       => 'Omgelmi',
'logout'                  => 'Digelmi',
'userlogout'              => 'Digelmi',
'notloggedin'             => 'Digelmys',
'nologin'                 => "A nyns eus akont dhewgh? '''$1'''.",
'nologinlink'             => 'Formyowgh akont',
'createaccount'           => 'Formya akont nowyth',
'gotaccount'              => "Eus akont genowgh seulabres? '''$1'''.",
'gotaccountlink'          => 'Omgelmi',
'userlogin-resetlink'     => 'Eus ankevys genowgh agas manylyon omgelmi?',
'createaccountmail'       => 'der e-bost',
'createaccountreason'     => 'Cheson:',
'badretype'               => 'Ny wra parya an geryow-tremena an eyl gans y gila.',
'userexists'              => "Yma'n hanow usyer entrys genowgh ow pos usys seulabres.
Dowisowgh hanow aral mar pleg.",
'loginerror'              => 'Gwall omgelmi',
'createaccounterror'      => 'Ny ylles formya an akont: $1',
'nocookiesnew'            => 'An akont yw formys, mes nyns owgh hwi omgelmys.
Yma {{SITENAME}} owth usya cookies rag omgelmi devnydhyoryon.
Dialosegys yw cookies war agas jynn amontya.
Gwrewgh aga galosegi, hag omgelmi dre usya agas hanow usyer ha ger tremena nowyth.',
'nocookieslogin'          => 'Yma {{SITENAME}} owth usya cookies rag omgelmi devnydhyoryon.
Dialosegys yw cookies war agas jynn amontya.
Gwrewgh aga galosegi hag assaya arta.',
'noname'                  => 'Ny wrussowgh hwi ri hanow usyer da.',
'loginsuccess'            => "'''Omgelmys owgh hwi lebmyn orth {{SITENAME}} avel \"\$1\".'''",
'nouserspecified'         => 'Res yw dhewgh ri hanow usyer.',
'wrongpassword'           => 'Kabm o an ger tremena.
Assayowgh arta mar pleg.',
'wrongpasswordempty'      => 'Gwag o an ger-tremena res. Assayowgh arta mar pleg.',
'mailmypassword'          => 'E-bostya ger tremena nowyth',
'noemailcreate'           => 'Res yw dhewgh ri trigva ebost da',
'accountcreated'          => 'Formys yw an akont',
'accountcreatedtext'      => 'Formys yw an akont rag $1.',
'loginlanguagelabel'      => 'Yeth: $1',

# Change password dialog
'resetpass'                 => 'Chanjya ger-tremena',
'resetpass_header'          => 'Chanjya ger tremena an akont',
'oldpassword'               => 'Ger tremena koth:',
'newpassword'               => 'Ger tremena nowyth:',
'resetpass-submit-loggedin' => 'Chanjya an ger-tremena',
'resetpass-submit-cancel'   => 'Hedhi',

# Special:PasswordReset
'passwordreset-username' => 'Hanow usyer:',
'passwordreset-email'    => 'Trigva ebost:',

# Edit page toolbar
'bold_sample'     => 'Tekst tew',
'bold_tip'        => 'Tekst tew',
'italic_sample'   => 'Tekst italek',
'italic_tip'      => 'Tekst italek',
'link_sample'     => 'Titel an gevren',
'link_tip'        => 'Kevren bervedhel',
'extlink_sample'  => 'http://www.example.com titel an gevren',
'extlink_tip'     => 'Kevren a-ves (na ankevowgh an rager http://)',
'headline_sample' => 'Tekst an titel',
'headline_tip'    => 'Pednlinen nivel 2',
'nowiki_sample'   => 'Keworrowgh tekst heb furvyans obma',
'nowiki_tip'      => 'Skonya ajon furvyans wiki',
'image_tip'       => 'Restren neythys',
'media_tip'       => 'Kevren restren',
'sig_tip'         => 'Agas sinans gans stampa-termyn',

# Edit pages
'summary'                          => 'Derivas kot:',
'subject'                          => 'Testen/Pednlinen:',
'minoredit'                        => 'Chanj bian yw hebma',
'watchthis'                        => 'Golyas an folen-ma',
'savearticle'                      => 'Gwitha',
'preview'                          => 'Ragwel',
'showpreview'                      => 'Ragweles',
'showdiff'                         => 'Diskwedhes an chanjyow',
'anoneditwarning'                  => "'''Gwarnyans:''' Nyns owgh hwi omgelmys.
Agas trigva IP a vedh rekordys en istori chanjyow an folen-ma.",
'summary-preview'                  => "Ragwel a'n derivas kot:",
'loginreqlink'                     => 'omgelmi',
'accmailtitle'                     => 'Danvenys yw an ger-tremena.',
'newarticle'                       => '(Nowyth)',
'noarticletext'                    => 'Nyns eus tekst war an folen-ma.
Hwi a ell [[Special:Search/{{PAGENAME}}|hwilas titel an folen-ma]] en folednow erel,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} hwilas e\'n kovnotednow kelmys],
po [{{fullurl:{{FULLPAGENAME}}|action=edit}} chanjya an folen-ma]</span>.',
'updated'                          => '(Nowedhys)',
'note'                             => "'''Noten:'''",
'previewnote'                      => "'''Gwrewgh perthi kov, nyns yw hebma marnas ragwel.''' Nyns yw agas chanjyow gwithys hwath!",
'editing'                          => 'ow chanjya $1',
'editingsection'                   => 'ow chanjya $1 (radn)',
'editingcomment'                   => 'ow chanjya $1 (radn nowyth)',
'yourtext'                         => 'Agas tekst',
'yourdiff'                         => 'Dyffransow',
'templatesused'                    => '{{PLURAL:$1|Skantlyn|Skantlyns}} usys war an folen-ma:',
'templatesusedpreview'             => "{{PLURAL:$1|Skantlyn|Skantlyns}} usys e'n ragwel-ma:",
'template-protected'               => '(gwithys)',
'template-semiprotected'           => '(hanter-difresys)',
'hiddencategories'                 => 'Esel a {{PLURAL:$1|1 glass kovys|$1 klass kovys}} yw an folen-ma:',
'permissionserrorstext-withaction' => 'Nyns eus kubmyes dhewgh dhe $2, rag an {{PLURAL:$1|cheson|chesonys}} a sew:',
'log-fulllog'                      => 'Gweles an govnoten dien',

# "Undo" feature
'undo-summary' => 'Diswul amendyans $1 gans [[Special:Contributions/$2|$2]] ([[User talk:$2|keskows]])',

# Account creation failure
'cantcreateaccounttitle' => 'Ny ellir formya an akont',

# History pages
'viewpagelogs'           => 'Gweles kovnotednow an folen-ma',
'currentrev'             => 'Amendyans diwettha',
'currentrev-asof'        => 'An chanj diwettha dhyworth $1',
'revisionasof'           => 'Amendyans wosa $1',
'revision-info'          => 'Amendyans wosa $1 gans $2',
'previousrevision'       => '← Amendyans kottha',
'nextrevision'           => 'Amendyans nowyttha →',
'currentrevisionlink'    => 'An amendyans diwettha',
'cur'                    => 'lebmyn',
'next'                   => 'nessa',
'last'                   => 'kens',
'page_first'             => 'kensa',
'page_last'              => 'kens',
'histlegend'             => "Dowis dyffransow: Merkyowgh kistednow radyo an amendyansow dhe geheveli, ha gwaskowgh 'entra' po an boton orth goles an folen.<br />
Alhwedh: '''({{int:cur}})''' = dyffrans gans an amendyans diwettha, '''({{int:last}})''' = dyffrans gans amendyans kens, '''{{int:minoreditletter}}''' = chanj bian.",
'history-fieldset-title' => 'Peuri an istori',
'history-show-deleted'   => 'Diles hep ken',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision feed
'history-feed-item-nocomment' => '$1 dhe $2',

# Revision deletion
'rev-delundel'           => 'diskwedhes/kudha',
'revdel-restore'         => 'chanjya an hewelder',
'revdel-restore-deleted' => 'amendyansow diles',
'revdel-restore-visible' => 'amendyansow gweladow',
'pagehist'               => 'Istori an folen',

# History merging
'mergehistory-reason' => 'Cheson:',

# Merge log
'revertmerge' => 'Disworunya',

# Diffs
'history-title'            => 'Istori an folen "$1"',
'difference'               => '(Dyffrans ynter an amendyansow)',
'difference-multipage'     => '(Dyffrans ynter an folednow)',
'lineno'                   => 'Linen $1:',
'compareselectedversions'  => 'Keheveli an amendyansow dowisyes',
'showhideselectedversions' => 'Diskwedhes/kudha amendyansow dowisyes',
'editundo'                 => 'diswul',

# Search results
'searchresults'                    => 'Sewyansow an hwilans',
'searchresults-title'              => 'Sewyansow an hwilans rag "$1"',
'searchresulttext'                 => 'Rag moy kedhlow a-dro dhe hwilas en {{SITENAME}}, gwelowgh [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Hwi a wrug hwilas \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|keniver folen ow talleth gans "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|keniver folen ow kevredna dhe "$1"]])',
'searchsubtitleinvalid'            => "Hwi a wrug hwilas '''$1'''",
'notitlematches'                   => 'Nyns eus titel folen ow machya',
'notextmatches'                    => 'Nyns eus tekst folen ow machya',
'prevn'                            => 'kens {{PLURAL:$1|$1}}',
'nextn'                            => 'nessa {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|sewyans|sewyans}} kens',
'nextn-title'                      => '$1 {{PLURAL:$1|sewyans|sewyans}} nessa',
'viewprevnext'                     => 'Gweles ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Etholyow hwilans',
'searchmenu-exists'                => "''Yma folen henwys \"[[:\$1]]\" war an wiki-ma'''",
'searchmenu-new'                   => "'''Gwruthyl an folen \"[[:\$1]]\" war an wiki-ma!'''",
'searchhelp-url'                   => 'Help:Gweres',
'searchprofile-articles'           => 'Folednow dalhen',
'searchprofile-project'            => 'Folednow gweres ha ragdres',
'searchprofile-images'             => 'Liesmedia',
'searchprofile-everything'         => 'Puptra',
'searchprofile-advanced'           => 'Avoncys',
'searchprofile-articles-tooltip'   => 'Hwilas en $1',
'searchprofile-project-tooltip'    => 'Hwilas en $1',
'searchprofile-images-tooltip'     => 'Hwilas restrednow',
'searchprofile-everything-tooltip' => 'Hwilas en pub teller (en folednow keskows ynwedh)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-result-category-size'      => '{{PLURAL:$1|1 esel|$1 esel}} ({{PLURAL:$2|1 isglass|$2 isglass}}, {{PLURAL:$3|1 restren|$3 restren}})',
'search-redirect'                  => '(daswedyans $1)',
'search-section'                   => '(radn $1)',
'search-suggest'                   => 'A wrussowgh hwi menya: $1',
'search-interwiki-caption'         => 'Ragdresow hwor',
'search-interwiki-default'         => '$1 sewyansow:',
'search-interwiki-more'            => '(moy)',
'search-mwsuggest-enabled'         => 'gans profyansow',
'search-mwsuggest-disabled'        => 'heb profyansow',
'search-relatedarticle'            => 'Kelmys',
'mwsuggest-disable'                => 'Dialosegi profyansow AJAX',
'searcheverything-enable'          => 'Hwilas en keniver spas-hanow',
'searchrelated'                    => 'kelmys',
'searchall'                        => 'oll',
'showingresultsheader'             => "{{PLURAL:$5|Sewyans '''$1''' dhyworth '''$3'''|Sewyansow '''$1 - $2''' dhyworth '''$3'''}} rag '''$4'''",
'nonefound'                        => "'''Noten''': Nyns yw hwilys marnas radn a'n spasys-hanow dre dhefowt.
Gwrewgh assaya rag-gorra agas govyn gans ''all:'' rag hwilas en pub teller (a-barth an folednow keskows, skantlyns, etc), po usyowgh an spas-hanow hwensys avel rag-gorrans.",
'search-nonefound'                 => 'Nyns esa sewyansow ow machya an govyn.',
'powersearch'                      => 'Hwilans avoncys',
'powersearch-legend'               => 'Hwilans avoncys',
'powersearch-ns'                   => 'Hwilas en spasys-hanow:',
'powersearch-redir'                => 'Gorra an daswedyansow en rol',
'powersearch-field'                => 'Hwilas',
'powersearch-togglelabel'          => 'Dowis:',
'powersearch-toggleall'            => 'Oll',
'powersearch-togglenone'           => 'Nagonen',
'search-external'                  => 'Hwilans a-ves',

# Preferences page
'preferences'                 => 'Dowisyansow',
'mypreferences'               => 'Ow dowisyansow',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Krohen',
'prefs-datetime'              => 'Dedhyans hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Etholyow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Hwilas',
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
'recentchanges-feed-description' => "Sewya an chanjyow diwettha dhe'n wiki e'n feed-ma.",
'recentchanges-label-newpage'    => 'An chanj-ma a wrug gwruthyl folen nowyth',
'recentchanges-label-minor'      => 'Chanj bian yw hebma',
'recentchanges-label-bot'        => 'An chanj-ma a veu gwres gans bot',
'rclistfrom'                     => 'Diskwedhes chanjyow nowyth ow talleth a-dhia $1.',
'rcshowhideminor'                => '$1 chanjyow bian',
'rcshowhidebots'                 => '$1 botow',
'rcshowhideliu'                  => '$1 devnydhoryon omgelmys',
'rcshowhideanons'                => '$1 devnydhyoryon dihanow',
'rcshowhidemine'                 => '$1 ow chanjyow',
'rclinks'                        => "Diskwedhes an $1 chanj a-dhiwedhes gwres e'n $2 dydh a-dhiwedhes<br />$3",
'diff'                           => 'dyffrans',
'hist'                           => 'istori',
'hide'                           => 'Kudha',
'show'                           => 'Diskwedhes',
'minoreditletter'                => 'B',
'newpageletter'                  => 'N',
'boteditletter'                  => 'bot',
'newsectionsummary'              => '/* $1 */ radn nowyth',
'rc-enhanced-expand'             => 'Diskwedhes an manylyon (res yw JavaScript)',
'rc-enhanced-hide'               => 'Kudha manylyon',

# Recent changes linked
'recentchangeslinked'         => 'Chanjyow kelmys',
'recentchangeslinked-feed'    => 'Chanjyow kelmys',
'recentchangeslinked-toolbox' => 'Chanjyow kelmys',
'recentchangeslinked-title'   => 'Chanjyow kelmys dhe "$1"',
'recentchangeslinked-summary' => "Hemm yw rol a janjyow a-dhiwedhes gwres war folednow kevrednys dhyworth folen res (po dhe eseli a glass res).
'''Tew''' yw folednow eus war agas [[Special:Watchlist|rol golyas]].",
'recentchangeslinked-page'    => 'Hanow an folen:',

# Upload
'upload'          => 'Ughkarga restren',
'uploadbtn'       => 'Ughkarga restren',
'uploadlogpage'   => 'Kovnoten ughkarga',
'filename'        => 'Hanow-restren',
'filedesc'        => 'Derivas kot',
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
'filehist'                  => 'Istori an folen',
'filehist-help'             => 'Klyckyowgh war dedhyans/eur rag gweles an folen del veu hi nena.',
'filehist-deleteall'        => 'dilea oll',
'filehist-deleteone'        => 'dilea',
'filehist-current'          => 'a-lebmyn',
'filehist-datetime'         => 'Dedhyans/Eur',
'filehist-thumb'            => 'Skeusednik',
'filehist-thumbtext'        => 'Skeusednik rag an versyon wosa $1',
'filehist-user'             => 'Devnydhyer',
'filehist-dimensions'       => 'Mensow',
'filehist-comment'          => 'Ger',
'imagelinks'                => 'Devnydh an restren',
'linkstoimage'              => "Yma'n {{PLURAL:$1|folen|$1 folen}} a sew ow kevredna dhe'n restren-ma:",
'nolinkstoimage'            => "Nyns eus folen ow kevredna dhe'n restren-ma.",
'sharedupload'              => 'Yma an folen-ma ow tos dhyworth $1 ha hi a ell bos usys gans ragdresow erel.',
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
'shortpages'              => 'Folednow berr',
'longpages'               => 'Folednow hir',
'protectedpages'          => 'Folednow difresys',
'protectedtitles'         => 'Titlys difresys',
'usercreated'             => '{{GENDER:$3|Formyes}} war $1 dhe $2',
'newpages'                => 'Folednow nowyth',
'newpages-username'       => 'Hanow-usyer:',
'ancientpages'            => 'Folednow kottha',
'move'                    => 'Gwaya',
'movethispage'            => 'Gwaya an folen-ma',
'pager-newer-n'           => '{{PLURAL:$1|1 nowyttha|$1 nowyttha}}',
'pager-older-n'           => '{{PLURAL:$1|1 kottha|$1 kottha}}',

# Book sources
'booksources'               => 'Pednfentynyow lyver',
'booksources-search-legend' => 'Hwilas pednfentynyow lyver',
'booksources-go'            => 'Mos',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Kovnotednow',

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
'categories' => 'Klassys',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch'      => 'Hwilas kevrednow a-ves',
'linksearch-ok'   => 'Hwilas',
'linksearch-line' => '$1 yw kevrednys dhyworth $2',

# Special:ListUsers
'listusers-submit' => 'Diskwedhes',

# Special:Log/newusers
'newuserlogpage' => 'Kovnoten formya akontow devnydhyer',

# Special:ListGroupRights
'listgrouprights-members' => '(rol an eseli)',

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
'watchlist-details' => 'Yma {{PLURAL:$1|$1 folen|$1 folen}} war agas rol golyas, heb ynkludya folednow kows.',
'watchlist-options' => 'Etholyow an rol golyas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ow kolyas...',
'unwatching' => 'Ow tisgolyas...',

# Delete
'deletepage'            => 'Dilea an folen',
'delete-confirm'        => 'Dilea "$1"',
'delete-legend'         => 'Dilea',
'actioncomplete'        => 'Kowlwres yw an gwrians',
'actionfailed'          => 'An gwrians a fyllas',
'deletedtext'           => '"$1" yw dileys.
Gwelowgh $2 rag kovadh a dhileansow a-dhiwedhes.',
'dellogpage'            => 'Kovnoten dhilea',
'deletecomment'         => 'Cheson:',
'deleteotherreason'     => 'Cheson aral/keworansel:',
'deletereasonotherlist' => 'Cheson aral',

# Rollback
'rollbacklink' => 'restorya',

# Protect
'protectlogpage'          => 'Kovnoten difres',
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
'undeletelink'              => 'gweles/daswul',
'undeleteviewlink'          => 'gweles',
'undelete-search-submit'    => 'Hwilas',
'undelete-show-file-submit' => 'Ya',

# Namespace form on various pages
'namespace'      => 'Spas-hanow:',
'invert'         => 'Trebuchya an dowisyans',
'blanknamespace' => '(Pedn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow $1',
'mycontris'           => 'Ow hevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'A-dhia mis (ha moy a-varr):',
'year'                => 'A-dhia bledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Diskwedhes hepken kevrohow akontow nowyth',
'sp-contributions-blocklog' => 'kovnoten lettya',
'sp-contributions-uploads'  => 'ughkargansow',
'sp-contributions-logs'     => 'kovnotednow',
'sp-contributions-talk'     => 'keskows',
'sp-contributions-search'   => 'Hwilas kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-submit'   => 'Hwilas',

# What links here
'whatlinkshere'            => "Pandr'eus ow kevredna obma",
'whatlinkshere-title'      => 'Folednow ow kevredna bys "$1"',
'whatlinkshere-page'       => 'Folen:',
'linkshere'                => "Yma'n folednow a sew ow kevredna dhe '''[[:$1]]''':",
'nolinkshere'              => "Nyns eus folen ow kevredna dhe '''[[:$1]]'''.",
'isredirect'               => 'folen daswedyans',
'istemplate'               => 'treuskludyans',
'isimage'                  => 'kevren an restren',
'whatlinkshere-prev'       => '{{PLURAL:$1|kens|kens $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'      => '← kevrednow',
'whatlinkshere-hideredirs' => '$1 daswedyansow',
'whatlinkshere-hidetrans'  => '$1 treuskludyans',
'whatlinkshere-hidelinks'  => '$1 kevrednow',
'whatlinkshere-hideimages' => '$1 kevrednow imach',
'whatlinkshere-filters'    => 'Sidhlow',

# Block/unblock
'blockip'                    => 'Lettya devnydhyer',
'ipadressorusername'         => 'Trigva IP po hanow-usyer:',
'ipbreason'                  => 'Cheson:',
'ipbreasonotherlist'         => 'Cheson aral',
'ipb-blocklist-contribs'     => 'Kevrohow rag $1',
'ipblocklist'                => 'Devnydhyoryon lettyes',
'ipblocklist-submit'         => 'Hwilas',
'blocklink'                  => 'lettya',
'unblocklink'                => 'dislettya',
'change-blocklink'           => 'chanjya an lettyans',
'contribslink'               => 'kevrohow',
'blocklogpage'               => 'Kovnoten lettya',
'blocklogentry'              => 'a lettyas [[$1]], $2 $3 y/hy termyn diwedh',
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
'movelogpage'      => 'Kovnoten gwaya',
'movereason'       => 'Cheson:',
'revertmove'       => 'trebuchya',

# Export
'export'        => 'Esperthi folednow',
'export-addcat' => 'Keworra',
'export-addns'  => 'Keworra',

# Namespace 8 related
'allmessagesname' => 'Hanow',

# Thumbnails
'thumbnail-more'  => 'Brashe',
'thumbnail_error' => 'Gwall ow formya skeusednik: $1',

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
'tooltip-pt-watchlist'            => 'Rol a folednow erowgh hwi ow kolyas',
'tooltip-pt-mycontris'            => "Rol a'gas kevrohow",
'tooltip-pt-login'                => 'Gwell via dhewgh mar tewgh hag omgelmi, mes nyns yw besi',
'tooltip-pt-logout'               => 'Digelmi',
'tooltip-ca-talk'                 => "Dadhelva a-dro dhe'n dalgh",
'tooltip-ca-edit'                 => 'Hwi a ell chanjya an folen-ma. Gwrewgh usya an boton ragwel kens gwitha mar pleg.',
'tooltip-ca-addsection'           => 'Dalleth radn nowyth',
'tooltip-ca-viewsource'           => 'Alhwedhys yw an folen-ma.
Y hellir gweles hy fednfenten.',
'tooltip-ca-history'              => 'Amendyansow koth an folen-ma',
'tooltip-ca-protect'              => 'Difres an folen-ma',
'tooltip-ca-delete'               => 'Dilea an folen-ma',
'tooltip-ca-move'                 => 'Gwaya an folen-ma',
'tooltip-ca-watch'                => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-ca-unwatch'              => 'Dilea an folen-ma dhyworth agas rol golyas',
'tooltip-search'                  => 'Hwilas en {{SITENAME}}',
'tooltip-search-go'               => 'Mos dhe folen gans an keth hanow-ma, mars eus',
'tooltip-search-fulltext'         => "Hwilas an tekst-ma e'n folednow",
'tooltip-p-logo'                  => "Mos dhe'n folen dre",
'tooltip-n-mainpage'              => "Mos dhe'n folen dre",
'tooltip-n-mainpage-description'  => "Mos dhe'n folen dre",
'tooltip-n-portal'                => "A-dro dhe'n ragdres, an peth a ellowgh hwi gwul, ple kavos taklow",
'tooltip-n-currentevents'         => 'Kavos kedhlow a-dro dhe dharvosow a-lebmyn',
'tooltip-n-recentchanges'         => "Rol an chanjyow a-dhiwedhes e'n wiki",
'tooltip-n-randompage'            => 'Karga folen dre jons',
'tooltip-n-help'                  => 'Gweres',
'tooltip-t-whatlinkshere'         => 'Rol a bub folen wiki ow kevredna bys obma',
'tooltip-t-recentchangeslinked'   => 'Chanjyow a-dhiwedhes en folednow eus kevrednys dhyworth an folen-ma',
'tooltip-feed-rss'                => 'Feed RSS rag an folen-ma',
'tooltip-feed-atom'               => 'Feed Atom rag an folen-ma',
'tooltip-t-contributions'         => 'Gweles rol a gevrohow an devnydhyer-ma',
'tooltip-t-emailuser'             => "Danvon e-bost dhe'n devnydhyer-ma",
'tooltip-t-upload'                => 'Ughkarga restrednow',
'tooltip-t-specialpages'          => 'Rol a geniver folen arbednek',
'tooltip-t-print'                 => "Versyon pryntyadow a'n folen-ma",
'tooltip-t-permalink'             => "Kevren fast dhe'n amendyans-ma a'n folen",
'tooltip-ca-nstab-main'           => 'Gweles an folen',
'tooltip-ca-nstab-user'           => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-special'        => 'Folen arbednek yw hebma; ny ellowgh hwi chanjya an folen hy honen.',
'tooltip-ca-nstab-project'        => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'          => 'Gweles folen an restren',
'tooltip-ca-nstab-template'       => 'Gweles an skantlyn',
'tooltip-ca-nstab-category'       => 'Gweles folen an klass',
'tooltip-minoredit'               => 'Merkya hebma avel chanj bian',
'tooltip-save'                    => 'Gwitha agas chanjyow',
'tooltip-preview'                 => 'Ragweles agas chanjyow; gwrewgh usya hebma kens gwitha mar pleg!',
'tooltip-diff'                    => "Diskwedhes an chanjyow eus gwres genowgh dhe'n tekst",
'tooltip-compareselectedversions' => 'Gweles an dyffransow ynter dew janjyow dowisyes an folen-ma',
'tooltip-watch'                   => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-rollback'                => '"Restorya" a wra trebuchya chanjyow gwres dhe\'n folen-ma gans an kens devnydhyer dre udn glyck',
'tooltip-undo'                    => '"Diswul" a wra trebuchya an chanj-ma hag egeri an furvlen chanjya e\'n modh ragweles. Cheson a ell bos keworrys e\'n derivas kot.',
'tooltip-summary'                 => 'Entrowgh derivas kot',

# Attribution
'siteuser'         => 'devnydhyer {{SITENAME}} $1',
'lastmodifiedatby' => 'An folen-ma a veu kens chanjys dhe $2, $1 gans $3.',
'siteusers'        => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Chanj kottha',
'nextdiff'     => 'Chanj nowyttha →',

# Media information
'file-info-size' => '$1 × $2 piksel, mens an restren: $3, sort MIME : $4',
'file-nohires'   => 'Nyns eus klerder uhella kavadow.',
'svg-long-desc'  => 'Restren SVG, $1 × $2 piksel en hanow, mens an restren: $3',
'show-big-image' => 'Klerder leun',

# Special:NewFiles
'ilsubmit' => 'Hwilas',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Yma dhe'n restren-ma kedhlow keworansel, dres lycklod keworrys dhyworth an kamera besyel po an skanyer usys rag hy gwruthyl po hy besya. Mar veu an folen chanjys dhyworth hy studh gwredhek, possybyl yw na veu radn a'n manylyon-ma nowedhys.",
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
'specialpages' => 'Folednow arbednek',

# Special:Tags
'tags-edit' => 'chanjya',

);
