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
'tog-hideminor'      => 'Cudha chanjyow bian en chanjyow a-dhiwedhes',
'tog-watchcreations' => "Keworra folednow gwruthys genam dhe'm rol golyas",
'tog-watchdefault'   => "Keworra folednow chanjys genam dhe'm rol golyas",
'tog-watchmoves'     => "Keworra folednow gwayes genam dhe'm rol golyas",
'tog-watchdeletion'  => "Keworra folednow dileys genam dhe'm rol golyas",

'underline-always'  => 'Puppres',
'underline-never'   => 'Nevra',
'underline-default' => 'Defowt an beurel',

# Font style option in Special:Preferences
'editfont-default' => 'Defowt an beurel',

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
'february-gen'  => 'Whevrel',
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
'category_header'                => 'Folednow e\'n class "$1"',
'subcategories'                  => 'Isglassys',
'category-media-header'          => 'Media e\'n class "$1"',
'category-empty'                 => "''Nag eus a-lebmen folednow na media e'n class-ma.''",
'hidden-categories'              => '{{PLURAL:$1|Class kovys|Class kovys}}',
'hidden-category-category'       => 'Classys covys',
'category-subcat-count'          => "{{PLURAL:$2|Nag eus dhe'n class-ma bes an isglass a sew.|Ma dhe'n class-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew, dhort sobm a $2.}}",
'category-subcat-count-limited'  => "Ma dhe'n class-ma an {{PLURAL:$1|isglass|$1 isglass}} a sew.",
'category-article-count'         => "{{PLURAL:$2|Nag eus dhe'n class-ma bes an folen a sew.|Ma'n {{PLURAL:$1|folen|$1 folednow}} a sew e'n class-ma, dhort sobm a $2.}}",
'category-article-count-limited' => "Ma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n class kesres.",
'category-file-count'            => "{{PLURAL:$2|Nag eus dhe'n class-ma bes an folen a-sew.|Ma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n class-ma, dhort sobm a $2.}}",
'category-file-count-limited'    => "Ma'n {{PLURAL:$1|folen|$1 folen}} a sew e'n class kesres.",
'listingcontinuesabbrev'         => 'pes.',

'about'         => 'A-dro dhe',
'newwindow'     => '(y whra egery en fenester noweth)',
'cancel'        => 'Hedhy',
'moredotdotdot' => 'Moy...',
'mypage'        => 'Ow folen',
'mytalk'        => 'Ow hows',
'anontalk'      => 'Kescows rag an drigva IP-ma',
'navigation'    => 'Lewyans',
'and'           => '&#32;ha(g)',

# Cologne Blue skin
'qbfind'         => 'Cavos',
'qbbrowse'       => 'Peury',
'qbedit'         => 'Chanjya',
'qbpageoptions'  => 'An folen-ma',
'qbmyoptions'    => 'Ow folednow',
'qbspecialpages' => 'Folednow arbednek',

# Vector skin
'vector-action-addsection' => 'Keworra mater',
'vector-action-delete'     => 'Dilea',
'vector-action-move'       => 'Gwaya',
'vector-action-protect'    => 'Difres',
'vector-action-undelete'   => 'Disdhilea',
'vector-action-unprotect'  => 'Disdhifres',
'vector-view-create'       => 'Gwruthyl',
'vector-view-edit'         => 'Chanjya',
'vector-view-history'      => 'Gweles istory an folen',
'vector-view-view'         => 'Redya',
'vector-view-viewsource'   => 'Gweles an bednfenten',

'errorpagetitle'    => 'Gwall',
'returnto'          => 'Dewheles dhe $1.',
'tagline'           => 'Dhort {{SITENAME}}',
'help'              => 'Gweres',
'search'            => 'Whilans',
'searchbutton'      => 'Whila',
'go'                => 'Ke',
'searcharticle'     => 'Ke',
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
'undelete_short'    => 'Disdhilea {{PLURAL:$1|udn janj|$1 chanj}}',
'viewdeleted_short' => 'Gweles {{PLURAL:$1|udn janj dileys|$1 chanj dileys}}',
'protect'           => 'Difres',
'protect_change'    => 'chanjya',
'protectthispage'   => 'Difres an folen-ma',
'unprotect'         => 'Disdhifres',
'unprotectthispage' => 'Disdhifres an folen-ma',
'newpage'           => 'Folen noweth',
'talkpage'          => "Dadhelva a-dro dhe'n folen-ma",
'talkpagelinktext'  => 'kescows',
'specialpage'       => 'Folen arbednek',
'personaltools'     => 'Toulys personel',
'postcomment'       => 'Radn noweth',
'talk'              => 'Kescows',
'views'             => 'Gwelyow',
'toolbox'           => 'Box toulys',
'userpage'          => 'Folen devnydhyer',
'projectpage'       => 'Folen meta',
'imagepage'         => 'Gweles folen an restren',
'mediawikipage'     => 'Gweles folen an messajys',
'templatepage'      => 'Gweles folen an scantlyn',
'viewhelppage'      => 'Gweles an folen gweres',
'categorypage'      => 'Gweles folen an class',
'viewtalkpage'      => 'Gweles an kescows',
'otherlanguages'    => 'En tavosow erel',
'redirectedfrom'    => '(Daswedyes dhort $1)',
'redirectpagesub'   => 'Folen daswedyans',
'lastmodifiedat'    => 'An folen-ma a veu kens chanjys an $1, dhe $2.',
'jumpto'            => 'Labma dhe:',
'jumptonavigation'  => 'lewyans',
'jumptosearch'      => 'whilans',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A-dro dhe {{SITENAME}}',
'aboutpage'            => 'Project:Derivadow',
'copyright'            => 'Cavadow ew an dalgh en-dadn $1.',
'copyrightpage'        => '{{ns:project}}:Gwirbryntyansow',
'currentevents'        => 'Darvosow a-lebmen',
'currentevents-url'    => 'Project:Darvosow a-lebmen',
'disclaimers'          => 'Avisyanjow',
'disclaimerpage'       => 'Project:Avisyans ollgebmen',
'edithelp'             => 'Gweres gen chanjya',
'edithelppage'         => 'Help:Chanjya',
'helppage'             => 'Help:Gweres',
'mainpage'             => 'Folen dre',
'mainpage-description' => 'Folen dre',
'policy-url'           => 'Project:Policy',
'portal'               => 'Porth an gemeneth',
'portal-url'           => 'Project:Porth an gemeneth',
'privacy'              => 'Policy privetter',
'privacypage'          => 'Project:Policy privetter',

'badaccess' => 'Gwall cubmyes',

'ok'                      => 'Sur',
'retrievedfrom'           => 'Daskevys dhort "$1"',
'youhavenewmessages'      => 'Ma $1 genowgh ($2).',
'newmessageslink'         => 'messajys noweth',
'newmessagesdifflink'     => 'chanj kens',
'youhavenewmessagesmulti' => 'Ma messajys noweth genowgh war $1',
'editsection'             => 'chanjya',
'editold'                 => 'chanjya',
'viewsourceold'           => 'gweles an bednfenten',
'editlink'                => 'chanjya',
'viewsourcelink'          => 'gweles an fenten',
'editsectionhint'         => 'Chanjya an radn: $1',
'toc'                     => 'Synsys',
'showtoc'                 => 'disqwedhes',
'hidetoc'                 => 'cudha',
'collapsible-expand'      => 'Efany',
'thisisdeleted'           => 'Gweles po restorya $1?',
'viewdeleted'             => 'Gweles $1?',
'restorelink'             => '{{PLURAL:$1|udn janj dileys|$1 chanj dileys}}',
'feedlinks'               => 'Feed:',
'site-rss-feed'           => '$1 RSS feed',
'site-atom-feed'          => '$1 Atom feed',
'page-rss-feed'           => '"$1" feed RSS',
'page-atom-feed'          => '"$1" feed Atom',
'red-link-title'          => '$1 (nag eus folen henwys yndelma)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Erthygel',
'nstab-user'      => 'Folen devnydhyer',
'nstab-media'     => 'Folen media',
'nstab-special'   => 'Folen arbednek',
'nstab-project'   => 'Folen towl',
'nstab-image'     => 'Restren',
'nstab-mediawiki' => 'Messach',
'nstab-template'  => 'Scantlyn',
'nstab-help'      => 'Gweres',
'nstab-category'  => 'Class',

# General errors
'error'               => 'Gwall',
'databaseerror'       => 'Gwall database',
'readonly'            => 'An database ew alhwedhys',
'missingarticle-rev'  => '(amendyans#: $1)',
'missingarticle-diff' => '(Dyffrans: $1, $2)',
'internalerror'       => 'Gwall a-bervedh',
'internalerror_info'  => 'Gwall a-bervedh: $1',
'filecopyerror'       => 'Nag alje\'ma copia an restren "$1" war-tu "$2".',
'filerenameerror'     => 'Nag alje\'ma dashenwel an restren "$1" dhe "$2".',
'filedeleteerror'     => 'Nag aljem dilea an restren "$1".',
'filenotfound'        => 'Nag aljem cavos an restren "$1".',
'badtitle'            => 'Titel drog',
'viewsource'          => 'Gweles an bednfenten',
'viewsourcefor'       => 'rag $1',

# Login and logout pages
'welcomecreation'         => '== Dynnargh, $1! ==
Gwruthys ew agas acont.
Na wrewgh nakevy dhe janjya agas [[Special:Preferences|dowisyanjow rag {{SITENAME}}]].',
'yourname'                => 'Hanow-usyer:',
'yourpassword'            => 'Ger-tremena:',
'yourpasswordagain'       => 'Jynnscrifowgh agas ger-tremena arta:',
'remembermypassword'      => "Porth cov a'm ger-tremena war'n amontyer-ma (rag $1 {{PLURAL:$1|dedh|dedh}} dhe'n moyha)",
'securelogin-stick-https' => 'Gwitha junyes gen HTTPS woja omgelmy',
'yourdomainname'          => 'Agas tiredh:',
'login'                   => 'Omgelmy',
'nav-login-createaccount' => 'Omgelmy / Formya acont noweth',
'loginprompt'             => 'Whi a res galosegy cookies rag omgelmy orth {{SITENAME}}.',
'userlogin'               => 'Omgelmy / formya acont noweth',
'userloginnocreate'       => 'Omgelmy',
'logout'                  => 'Digelmy',
'userlogout'              => 'Digelmy',
'notloggedin'             => 'Digelmys',
'nologin'                 => "A nag eus acont dhewgh? '''$1'''.",
'nologinlink'             => 'Formyowgh acont',
'createaccount'           => 'Formya acont noweth',
'gotaccount'              => "Eus acont genowgh seulabres? '''$1'''.",
'gotaccountlink'          => 'Omgelmy',
'userlogin-resetlink'     => 'Eus nakevys genowgh agas kedhlow omgelmy?',
'createaccountmail'       => 'der e-bost',
'createaccountreason'     => 'Acheson:',
'badretype'               => 'Na wra parya an geryow-tremena an eyl gen y gila.',
'userexists'              => "Ma'n hanow-usyer entrys genowgh ow bos usys seulabres.
Gwrewgh dowis hanow aral.",
'loginerror'              => 'Gwall omgelmy',
'createaccounterror'      => "Nag alje'ma formya an acont: $1",
'nocookiesnew'            => 'An acont ew formys, bes nag owgh whi omgelmys.
{{SITENAME}} a wra usya cookies rag omgelmy devnydhyoryon.
Dialosegys ew cookies war agas amontyer.
Gwrewgh aga galosegy, ena omgelmowgh gen agas hanow-user ha ger-tremena noweth.',
'nocookieslogin'          => '{{SITENAME}} a wra usya cookies rag omgelmy devnydhyoryon.
Dialosegys ew cookies war agas amontyer.
Gwrewgh aga galosegy hag assayowgh arta.',
'noname'                  => "Na wrugo'whi ri hanow-user da.",
'loginsuccess'            => "'''Omgelmys owgh whi lebmen orth {{SITENAME}} avel \"\$1\".'''",
'nouserspecified'         => 'Whi a res ri hanow-usyer.',
'wrongpassword'           => 'Ger-tremena cabm.
Assayowgh arta.',
'wrongpasswordempty'      => 'An ger-tremena res o gwag. Assayowgh arta.',
'mailmypassword'          => 'E-bostya ger-tremena noweth',
'noemailcreate'           => 'Whi a res ri trigva ebost dha',
'accountcreated'          => 'Formys ew an acont',
'accountcreatedtext'      => 'Formys ew an acont rag $1.',
'loginlanguagelabel'      => 'Yeth: $1',

# Change password dialog
'resetpass'                 => 'Chanjya ger-tremena',
'resetpass_header'          => 'Chanjya ger-tremena an acont',
'oldpassword'               => 'Ger-tremena coth:',
'newpassword'               => 'Ger-tremena noweth:',
'resetpass-submit-loggedin' => 'Chanjya an ger-tremena',
'resetpass-submit-cancel'   => 'Hedhy',

# Special:PasswordReset
'passwordreset-username' => 'Hanow-usyer:',
'passwordreset-email'    => 'Trigva ebost:',

# Edit page toolbar
'bold_sample'     => 'Text tew',
'bold_tip'        => 'Text tew',
'italic_sample'   => 'Text italek',
'italic_tip'      => 'Text italek',
'link_sample'     => 'Titel an gevren',
'link_tip'        => 'Kevren bervedhel',
'extlink_sample'  => 'http://www.example.com titel an gevren',
'extlink_tip'     => 'Kevren a-mes (na nakevowgh an rager http://)',
'headline_sample' => 'Text an titel',
'headline_tip'    => 'Pednlinen nivel 2',
'image_tip'       => 'Restren neythys',
'media_tip'       => 'Kevren restren',
'sig_tip'         => 'Agas sinans gen stampa-termen',

# Edit pages
'summary'                          => 'Derivas cot:',
'subject'                          => 'Testen/Pednlinen:',
'minoredit'                        => 'Chanj bian ew hebma',
'watchthis'                        => 'Golyas an folen-ma',
'savearticle'                      => 'Gwitha',
'preview'                          => 'Ragwel',
'showpreview'                      => 'Ragweles',
'showdiff'                         => 'Disqwedhes an chanjyow',
'anoneditwarning'                  => "'''Gwarnyans:''' Nag owgh whi omgelmys.
Agas trigva IP a vedh recordys en istory chanjyow an folen-ma.",
'summary-preview'                  => "Ragwel a'n derivas cot:",
'loginreqlink'                     => 'omgelmy',
'accmailtitle'                     => 'Danonys ew an ger-tremena.',
'newarticle'                       => '(Noweth)',
'noarticletext'                    => 'Nag eus text war an folen-ma a-lebmen.
Whi a ell [[Special:Search/{{PAGENAME}}|whilas titel an folen-ma]] en folednow erel,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} whilas e\'n covnotednow kelmys],
po [{{fullurl:{{FULLPAGENAME}}|action=edit}} chanjya an folen-ma]</span>.',
'updated'                          => '(Nowedhys)',
'note'                             => "'''Noten:'''",
'previewnote'                      => "'''Gwrewgh perthy cov, nag ew hebma bes ragwel.''' Nag ew agas chanjyow gwithys whath!",
'editing'                          => 'ow chanjya $1',
'editingsection'                   => 'ow chanjya $1 (radn)',
'editingcomment'                   => 'ow chanjya $1 (radn noweth)',
'yourtext'                         => 'Agas text',
'yourdiff'                         => 'Dyffranjow',
'templatesused'                    => '{{PLURAL:$1|Scantlyn|Scantlyns}} usys war an folen-ma:',
'templatesusedpreview'             => "{{PLURAL:$1|Scantlyn|Scantlyns}} usys e'n ragwel-ma:",
'template-protected'               => '(gwithys)',
'template-semiprotected'           => '(hanter-difresys)',
'hiddencategories'                 => 'Esel a {{PLURAL:$1|1 class covys|$1 class covys}} ew an folen-ma:',
'permissionserrorstext-withaction' => 'Nag eus cubmyes dhewgh dhe $2, rag an {{PLURAL:$1|acheson|achesonys}} a-sew:',
'log-fulllog'                      => 'Gweles an govnoten dien',

# "Undo" feature
'undo-summary' => 'Diswras amendyans $1 gen [[Special:Contributions/$2|$2]] ([[User talk:$2|kescows]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nag ellam formya an acont',

# History pages
'viewpagelogs'           => 'Gweles covnotednow an folen-ma',
'currentrev'             => 'Amendyans diwettha',
'currentrev-asof'        => 'An chanj diwettha dhort $1',
'revisionasof'           => 'Amendyans woja $1',
'previousrevision'       => '← Amendyans cottha',
'nextrevision'           => 'Daswel nowettha →',
'currentrevisionlink'    => 'An amendyans diwettha',
'cur'                    => 'lebmen',
'next'                   => 'nessa',
'last'                   => 'kens',
'page_first'             => 'kensa',
'page_last'              => 'kens',
'histlegend'             => "Dowisyans an dyffranjow: merkyowgh an kistednow radyo a'n amendyanjow rag kehevely ha sqwatyowgh 'entra' po an boton orth goles an folen.<br />
Alwhedh: '''({{int:cur}})''' = dyffrans gen an amendyans diwettha, '''({{int:last}})''' = dyffrans gen amendyans kens, '''{{int:minoreditletter}}''' = chanj bian.",
'history-fieldset-title' => 'Peury an istory',
'histfirst'              => 'An moyha a-varr',
'histlast'               => 'An diwettha',
'historyempty'           => '(gwag)',

# Revision feed
'history-feed-item-nocomment' => '$1 dhe $2',

# Revision deletion
'rev-delundel'    => 'disqwedhes/cudha',
'revdel-restore'  => 'chanjya an hewelder',
'pagehist'        => 'Istory an folen',
'revdelete-uname' => 'hanow-usyer',

# History merging
'mergehistory-reason' => 'Acheson:',

# Merge log
'revertmerge' => 'Disworunya',

# Diffs
'history-title'            => 'Istory an folen "$1"',
'difference'               => '(Dyffrans ynter an amendyanjow)',
'difference-multipage'     => '(Dyffrans ynter an folednow)',
'lineno'                   => 'Linen $1:',
'compareselectedversions'  => 'Kehevely an amendyanjow dowisyes',
'showhideselectedversions' => 'Disqwedhes/cudha amendyanjow dowisyes',
'editundo'                 => 'diswul',

# Search results
'searchresults'                    => 'Sewyanjow an whilans',
'searchresults-title'              => 'Sewyanjow an whilans rag "$1"',
'searchresulttext'                 => 'Rag kedhlow moy a-dro dhe whilas en {{SITENAME}}, gwelowgh [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Whi a wrug whilas \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|oll folednow ow talleth gen "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|oll folednow ow kevredna dhe "$1"]])',
'searchsubtitleinvalid'            => "Whi a wrug whilas '''$1'''",
'notitlematches'                   => 'Nag eus titel folen ow machya',
'notextmatches'                    => 'Nag eus text folen ow machya',
'prevn'                            => 'kens {{PLURAL:$1|$1}}',
'nextn'                            => 'nessa {{PLURAL:$1|$1}}',
'viewprevnext'                     => 'Gweles ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Etholyow whilans',
'searchmenu-exists'                => "''Ma folen henwys \"[[:\$1]]\" war an wiki-ma'''",
'searchmenu-new'                   => "'''Gwruthyl an folen \"[[:\$1]]\" war an wiki-ma!'''",
'searchhelp-url'                   => 'Help:Gweres',
'searchprofile-articles'           => 'Folednow dalhen',
'searchprofile-project'            => 'Folednow gweres ha ragdres',
'searchprofile-images'             => 'Liesmedia',
'searchprofile-everything'         => 'Puptra',
'searchprofile-advanced'           => 'Avoncys',
'searchprofile-articles-tooltip'   => 'Whilas en $1',
'searchprofile-project-tooltip'    => 'Whilas en $1',
'searchprofile-images-tooltip'     => 'Whilas restrednow',
'searchprofile-everything-tooltip' => 'Whilas en oll an dalhen (folednow kescows ywedh)',
'search-result-size'               => '$1 ({{PLURAL:$2|1 ger|$2 ger}})',
'search-result-category-size'      => '{{PLURAL:$1|1 esel|$1 esel}} ({{PLURAL:$2|1 isglass|$2 isglass}}, {{PLURAL:$3|1 restren|$3 restren}})',
'search-redirect'                  => '(daswedyans $1)',
'search-section'                   => '(radn $1)',
'search-suggest'                   => "A wrugo'whi menya: $1",
'search-interwiki-caption'         => 'Ragdresow whor',
'search-interwiki-default'         => '$1 sewyanjow:',
'search-interwiki-more'            => '(moy)',
'search-mwsuggest-enabled'         => 'gen profyanjow',
'search-mwsuggest-disabled'        => 'profyanjow veth',
'search-relatedarticle'            => 'Kelmys',
'mwsuggest-disable'                => 'Galosegy profyanjow AJAX',
'searcheverything-enable'          => 'Whilas en keniver spas-hanow',
'searchrelated'                    => 'kelmys',
'searchall'                        => 'oll',
'showingresultsheader'             => "{{PLURAL:$5|Sewyans '''$1''' dhort '''$3'''|Sewyanjow '''$1 - $2''' dhort '''$3'''}} rag '''$4'''",
'nonefound'                        => "'''Noten''': Nag ew bes radn a'n spasys-hanow whilys dre dhefowt.
Gwrewgh assaya dhe rag-gorra agas govyn gen ''all:'' rag whilas en pub le (a-barth an folednow kescows, scantlyns, etc), po usyowgh an spas-hanow whensys avel rag-gorrans.",
'search-nonefound'                 => 'Nag esa sewyanjow a wrug machya an govyn.',
'powersearch'                      => 'Whilans avoncys',
'powersearch-legend'               => 'Whilans avoncys',
'powersearch-ns'                   => 'Whila en spasys-hanow:',
'powersearch-redir'                => 'Gorra an daswedyanjow en rol',
'powersearch-field'                => 'Whila',
'powersearch-togglelabel'          => 'Dowis:',
'powersearch-toggleall'            => 'Oll',
'powersearch-togglenone'           => 'Nagonen',
'search-external'                  => 'Whilans a-ves',

# Preferences page
'preferences'                 => 'Dowisyanjow',
'mypreferences'               => 'Ow dowisyanjow',
'changepassword'              => 'Chanjya an ger-tremena',
'prefs-skin'                  => 'Crohen',
'prefs-datetime'              => 'Dedhyans hag eur',
'prefs-rc'                    => 'Chanjyow a-dhiwedhes',
'prefs-watchlist'             => 'Rol golyas',
'prefs-resetpass'             => 'Chanjya ger-tremena',
'prefs-email'                 => 'Etholyow e-bost',
'saveprefs'                   => 'Gwitha',
'searchresultshead'           => 'Whilans',
'timezoneregion-africa'       => 'Africa',
'timezoneregion-america'      => 'America',
'timezoneregion-antarctica'   => 'Antarctica',
'timezoneregion-arctic'       => 'Arctec',
'timezoneregion-asia'         => 'Asya',
'timezoneregion-atlantic'     => 'Mor Atlantek',
'timezoneregion-australia'    => 'Awstralya',
'timezoneregion-europe'       => 'Europa',
'timezoneregion-indian'       => 'Mor Eyndek',
'timezoneregion-pacific'      => 'Mor Cosel',
'prefs-searchoptions'         => 'Etholyow whilans',
'prefs-files'                 => 'Restrednow',
'youremail'                   => 'E-bost:',
'username'                    => 'Hanow-usyer:',
'uid'                         => 'ID devnydhyer:',
'prefs-memberingroups'        => "Esel a'n {{PLURAL:$1|bagas|bagasow}}:",
'yourrealname'                => 'Hanow gwir:',
'yourlanguage'                => 'Yeth:',
'yournick'                    => 'Sinans noweth:',
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
'right-read'          => 'Redya folednow',
'right-edit'          => 'Chanjya folednow',
'right-createtalk'    => 'Gwruthyl folednow kescows',
'right-createaccount' => 'Formya acontow devnydhyer noweth',
'right-move'          => 'Gwaya folednow',
'right-movefile'      => 'Gwaya restrednow',
'right-upload'        => 'Ughcarga restrednow',
'right-delete'        => 'Dilea folednow',

# User rights log
'rightslog' => 'Covnoten gwiryow devnydhyer',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'     => 'chanjya an folen-ma',
'action-move'     => 'gwaya an folen ma',
'action-movefile' => 'gwaya an restren ma',
'action-upload'   => 'ughcarga an restren-ma',
'action-delete'   => 'dilea an folen-ma',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|chanj|chanj}}',
'recentchanges'                  => 'Chanjyow a-dhiwedhes',
'recentchanges-legend'           => 'Etholyow an chanjyow a-dhiwedhes',
'recentchangestext'              => "War'n folen-ma whi a ell sewya an chanjyow diwettha eus gwres dhe'n wiki.",
'recentchanges-feed-description' => "Sewya an chanjyow diwettha dhe'n wiki e'n feed-ma.",
'recentchanges-label-minor'      => 'Chanj bian ew hebma',
'rclistfrom'                     => 'Disqwedhes chanjyow noweth ow talleth a-dhia $1.',
'rcshowhideminor'                => '$1 chanjyow bian',
'rcshowhidebots'                 => '$1 botow',
'rcshowhideliu'                  => '$1 devnydhoryon omgelmys',
'rcshowhideanons'                => '$1 devnydhyoryon dihanow',
'rcshowhidemine'                 => '$1 ow chanjyow',
'rclinks'                        => "Disqwedhes an $1 chanj a-dhiwedhes gwres e'n $2 dedh a-dhiwedhes<br />$3",
'diff'                           => 'dyffrans',
'hist'                           => 'ist',
'hide'                           => 'Cudha',
'show'                           => 'Disqwedhes',
'minoreditletter'                => 'B',
'newpageletter'                  => 'N',
'boteditletter'                  => 'bot',
'newsectionsummary'              => '/* $1 */ radn noweth',
'rc-enhanced-expand'             => 'Disqwedhes an manylyon (JavaScript gorholys)',
'rc-enhanced-hide'               => 'Cudha manylyon',

# Recent changes linked
'recentchangeslinked'         => 'Chanjyow kelmys',
'recentchangeslinked-feed'    => 'Chanjyow kelmys',
'recentchangeslinked-toolbox' => 'Chanjyow kelmys',
'recentchangeslinked-title'   => 'Chanjyow kelmys dhe "$1"',
'recentchangeslinked-summary' => "Hemm ew rol a janjyow a-dhiwedhes gwres war folednow kevrednys dhort folen ragavysyes (po dhe esely a glass ragavysyes).
En '''tew''' ew folednow eus war agas [[Special:Watchlist|rol golyas]].",
'recentchangeslinked-page'    => 'Hanow an folen:',

# Upload
'upload'          => 'Ughcarga restren',
'uploadbtn'       => 'Ughcarga restren',
'uploadlogpage'   => 'Covnoten ughcargans',
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
'filehist'                  => 'Istory an folen',
'filehist-help'             => 'Clyckyowgh war dedhyans/eur rag gweles an folen del veu hi nena.',
'filehist-deleteall'        => 'dilea oll',
'filehist-deleteone'        => 'dilea',
'filehist-current'          => 'a-lebmen',
'filehist-datetime'         => 'Dedhyans/Eur',
'filehist-thumb'            => 'Skeusednik',
'filehist-thumbtext'        => 'Skeusednik rag an versyon dhort $1',
'filehist-user'             => 'Devnydhyer',
'filehist-dimensions'       => 'Mensow',
'filehist-comment'          => 'Ger',
'imagelinks'                => 'Kevrednow an restren',
'linkstoimage'              => "Ma'n {{PLURAL:$1|folen|$1 folen}} a sew ow kevredna dhe'n restren-ma:",
'sharedupload'              => 'Ma an folen-ma ow tos dhort $1 ha hi a ell bos usys gen ragdresow erel.',
'uploadnewversion-linktext' => "Ughcarga versyon noweth a'n restren-ma",

# File deletion
'filedelete'        => 'Dilea $1',
'filedelete-legend' => 'Dilea an restren',
'filedelete-submit' => 'Dilea',

# MIME search
'download' => 'iscarga',

# Unwatched pages
'unwatchedpages' => 'Folednow nag eus ow pos golyes',

# List redirects
'listredirects' => 'Rol an daswedyansow',

# Unused templates
'unusedtemplates'    => 'Scantlyns heb devnydh',
'unusedtemplateswlh' => 'kevrednow erel',

# Random page
'randompage' => 'Folen dre jons',

# Statistics
'statistics-pages' => 'Folednow',

'brokenredirects-edit'   => 'chanjya',
'brokenredirects-delete' => 'dilea',

'withoutinterwiki'        => 'Folednow heb kevrednow yeth',
'withoutinterwiki-submit' => 'Disqwedhes',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt|bayt}}',
'nmembers'                => '$1 {{PLURAL:$1|esel|esel}}',
'uncategorizedpages'      => 'Folednow heb class',
'uncategorizedcategories' => 'Classys heb class',
'uncategorizedimages'     => 'Restrednow heb class',
'uncategorizedtemplates'  => 'Scantlyns heb class',
'unusedcategories'        => 'Classys gwag',
'unusedimages'            => 'Restrednow heb devnydh',
'shortpages'              => 'Folednow berr',
'longpages'               => 'Folednow hir',
'protectedpages'          => 'Folednow difresys',
'protectedtitles'         => 'Titlys difresys',
'newpages'                => 'Folednow noweth',
'newpages-username'       => 'Hanow-usyer:',
'ancientpages'            => 'Folednow cottha',
'move'                    => 'Gwaya',
'movethispage'            => 'Gwaya an folen-ma',
'pager-newer-n'           => '{{PLURAL:$1|1 nowettha|$1 nowettha}}',
'pager-older-n'           => '{{PLURAL:$1|1 cottha|$1 cottha}}',

# Book sources
'booksources'               => 'Pednfentednow lever',
'booksources-search-legend' => 'Whilas pednfentednow lever',
'booksources-go'            => 'Ke',

# Special:Log
'specialloguserlabel'  => 'Devnydhyer:',
'speciallogtitlelabel' => 'Titel:',
'log'                  => 'Covnotednow',

# Special:AllPages
'allpages'       => 'Oll folednow',
'alphaindexline' => '$1 dhe $2',
'prevpage'       => 'Folen gens ($1)',
'allpagesfrom'   => 'Disqwedhes folednow ow talleth orth:',
'allpagesto'     => 'Disqwedhes folednow ow tiwedha orth:',
'allarticles'    => 'Keniver folen',
'allpagesprev'   => 'Kens',
'allpagesnext'   => 'Nessa',
'allpagessubmit' => 'Ke',

# Special:Categories
'categories' => 'Classys',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'kevrohow',

# Special:LinkSearch
'linksearch'    => 'Kevrednow a-mes',
'linksearch-ok' => 'Whila',

# Special:ListUsers
'listusers-submit' => 'Disqwedhes',

# Special:Log/newusers
'newuserlogpage'          => 'Covnoten gwryans devnydhyoryon',
'newuserlog-create-entry' => 'Devnydhyer noweth',

# Special:ListGroupRights
'listgrouprights-members' => '(rol an esely)',

# E-mail user
'emailuser'       => 'E-bostya an devnydhyer-ma',
'emailpage'       => 'E-bostya devnydhyer',
'defemailsubject' => 'E-bost {{SITENAME}}',
'emailfrom'       => 'A-dhia:',
'emailto'         => 'Dhe:',
'emailmessage'    => 'Messach:',
'emailsend'       => 'Danon',

# Watchlist
'watchlist'         => 'Ow rol golyas',
'mywatchlist'       => 'Ow rol golyas',
'watchlistfor2'     => 'Rag $1 ($2)',
'watch'             => 'Golyas',
'watchthispage'     => 'Golyas an folen-ma',
'unwatch'           => 'Diswolyas',
'watchlist-options' => 'Etholyow an rol golyas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ow colyas...',
'unwatching' => 'Ow tisgolyas...',

# Delete
'deletepage'            => 'Dilea an folen',
'delete-confirm'        => 'Dilea "$1"',
'delete-legend'         => 'Dilea',
'actioncomplete'        => 'An gwryans ew cowlwres',
'deletedtext'           => '"$1" yw dileys.
Gwelowgh $2 rag covadh a dhileanjow a-dhiwedhes.',
'deletedarticle'        => 'a dhileas "[[$1]]"',
'dellogpage'            => 'Covnoten dilea',
'deletecomment'         => 'Acheson:',
'deleteotherreason'     => 'Acheson aral/keworansel:',
'deletereasonotherlist' => 'Acheson aral',

# Rollback
'rollbacklink' => 'restorya',

# Protect
'protectlogpage'          => 'Covnoten difres',
'protectedarticle'        => 'a dhifresas "[[$1]]"',
'protectcomment'          => 'Acheson:',
'protectexpiry'           => 'Ow tiwedha:',
'protect_expiry_invalid'  => 'Drog ew termen an diwedh.',
'protect_expiry_old'      => "Ma'n termen diwedh e'n termen eus passyes.",
'protect-level-sysop'     => 'Menystroryon hepken',
'protect-summary-cascade' => 'ow froslabma',
'protect-expiring'        => 'y whra diwedha $1 (UTC)',
'restriction-type'        => 'Cubmyas:',
'pagesize'                => '(bayt)',

# Restrictions (nouns)
'restriction-edit'   => 'Chanjya',
'restriction-move'   => 'Gwaya',
'restriction-create' => 'Gwruthyl',
'restriction-upload' => 'Ughcarga',

# Undelete
'undeletelink'              => 'gweles/daswul',
'undeleteviewlink'          => 'gweles',
'undeletedarticle'          => 'a wrug restorya "[[$1]]"',
'undelete-search-submit'    => 'Whila',
'undelete-show-file-submit' => 'Ea',

# Namespace form on various pages
'namespace'      => 'Spas-hanow:',
'invert'         => 'Trebuchya an dowisyans',
'blanknamespace' => '(Pedn)',

# Contributions
'contributions'       => 'Kevrohow an devnydhyer',
'contributions-title' => 'Kevrohow an devnydhyer rag $1',
'mycontris'           => 'Ow hevrohow',
'contribsub2'         => 'Rag $1 ($2)',
'uctop'               => '(gwartha)',
'month'               => 'A-dhia mis (ha moy a-varr):',
'year'                => 'A-dhia bledhen (ha moy a-varr):',

'sp-contributions-newbies'  => 'Disqwedhes hepken kevrohow an acontow noweth',
'sp-contributions-blocklog' => 'covnoten lettya',
'sp-contributions-uploads'  => 'ughcarganjow',
'sp-contributions-logs'     => 'covnotednow',
'sp-contributions-talk'     => 'kescows',
'sp-contributions-search'   => 'Whilas kevrohow',
'sp-contributions-username' => 'Trigva IP po hanow-usyer:',
'sp-contributions-submit'   => 'Whila',

# What links here
'whatlinkshere'            => "Pandr'eus ow kevredna obma",
'whatlinkshere-title'      => 'Folednow ow kevredna bys "$1"',
'whatlinkshere-page'       => 'Folen:',
'linkshere'                => "Ma'n folednow a sew ow kevredna dhe '''[[:$1]]''':",
'nolinkshere'              => "Nag eus folen ow kevredna dhe '''[[:$1]]'''.",
'isredirect'               => 'folen daswedyans',
'istemplate'               => 'treuscludyans',
'isimage'                  => 'kevren an imach',
'whatlinkshere-prev'       => '{{PLURAL:$1|kens|kens $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|nessa|nessa $1}}',
'whatlinkshere-links'      => '← kevrednow',
'whatlinkshere-hideredirs' => '$1 daswedyanjow',
'whatlinkshere-hidetrans'  => '$1 treuscludyans',
'whatlinkshere-hidelinks'  => '$1 kevrednow',
'whatlinkshere-filters'    => 'Sidhlow',

# Block/unblock
'blockip'                    => 'Lettya devnydhyer',
'ipadressorusername'         => 'Trigva IP po hanow-usyer:',
'ipbreason'                  => 'Acheson:',
'ipbreasonotherlist'         => 'Acheson aral',
'ipb-blocklist-contribs'     => 'Kevrohow rag $1',
'ipblocklist-submit'         => 'Whila',
'blocklink'                  => 'lettya',
'unblocklink'                => 'dislettya',
'change-blocklink'           => 'chanjya an let',
'contribslink'               => 'kevrohow',
'blocklogpage'               => 'Covnoten lettya',
'blocklogentry'              => 'a lettyas [[$1]], $2 $3 y/hy termen diwedh',
'unblocklogentry'            => 'dislettyas $1',
'block-log-flags-anononly'   => 'devnydhyoryon dihanow hepken',
'block-log-flags-hiddenname' => 'hanow-usyer covys',

# Move page
'move-page'        => 'Gwaya $1',
'move-page-legend' => 'Gwaya folen',
'movearticle'      => 'Movya an folen:',
'newtitle'         => 'Dhe ditel noweth:',
'move-watch'       => 'Golya an folen-ma',
'movepagebtn'      => 'Gwaya an folen',
'pagemovedsub'     => 'An gwarnyans a sowenas',
'movepage-moved'   => '\'\'\'Gwayes ew "$1" war-tu "$2"\'\'\'',
'movedto'          => 'gwayes war-tu',
'1movedto2'        => '[[$1]] gwayes war-tu [[$2]]',
'1movedto2_redir'  => 'a wayas [[$1]] war-tu [[$2]] dres daswedyans',
'movelogpage'      => 'Covnoten gwaya',
'movereason'       => 'Acheson:',
'revertmove'       => 'trebuchya',

# Export
'export'        => 'Esperthy folednow',
'export-addcat' => 'Keworra',
'export-addns'  => 'Keworra',

# Namespace 8 related
'allmessagesname' => 'Hanow',

# Thumbnails
'thumbnail-more' => 'Brashe',

# Special:Import
'import'                  => 'Ymperthy folednow',
'import-interwiki-submit' => 'Ymperthy',
'import-upload-filename'  => 'Hanow-restren:',
'importstart'             => 'Owth ymperthy folednow...',
'import-noarticle'        => 'Nag eus folen veth dhe ymperthy!',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Agas folen dhevnydhyer',
'tooltip-pt-mytalk'               => 'Agas folen gows',
'tooltip-pt-preferences'          => 'Agas dowisyanjow',
'tooltip-pt-watchlist'            => "An rol a folednow ero'whi ow colyas",
'tooltip-pt-mycontris'            => "Rol a'gas kevrohow",
'tooltip-pt-login'                => 'Gwell via dhewgh mar tewgh hag omgelmy, mes nag ew besy',
'tooltip-pt-logout'               => 'Digelmy',
'tooltip-ca-talk'                 => "Dadhelva a-dro dhe'n dalgh",
'tooltip-ca-edit'                 => 'Whi a ell chanjya an folen-ma. Mar pleg, gwrewgh usya an boton ragwel kens gwitha.',
'tooltip-ca-addsection'           => 'Dalleth radn noweth',
'tooltip-ca-viewsource'           => 'Alwhedhys ew an folen-ma.
Whi a ell gweles hy fednfenten.',
'tooltip-ca-history'              => 'Amendyanjow tremenys an folen-ma',
'tooltip-ca-protect'              => 'Difres an folen-ma',
'tooltip-ca-delete'               => 'Dilea an folen-ma',
'tooltip-ca-move'                 => 'Gwaya an folen-ma',
'tooltip-ca-watch'                => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-ca-unwatch'              => 'Dilea an folen-ma dhort agas rol golyas',
'tooltip-search'                  => 'Whila en {{SITENAME}}',
'tooltip-search-go'               => 'Mos dhe folen gen an keth hanow-ma, mars eus',
'tooltip-search-fulltext'         => "Whilas an text-ma e'n folednow",
'tooltip-p-logo'                  => 'Godriga an folen dre',
'tooltip-n-mainpage'              => 'Godriga an folen dre',
'tooltip-n-mainpage-description'  => 'Godriga an pennfolen',
'tooltip-n-portal'                => "A-dro dhe'n ragdres, an peth a ello'whi gwul, ple cavos taclow",
'tooltip-n-currentevents'         => 'Cavos derivadow kylva war darvosow a-lebmen',
'tooltip-n-recentchanges'         => "Rol an chanjyow a-dhiwedhes e'n wiki",
'tooltip-n-randompage'            => 'Carga folen dre jons',
'tooltip-n-help'                  => 'Gweres',
'tooltip-t-whatlinkshere'         => 'Rol a bub folednow wiki ow kevredna bys obma',
'tooltip-t-recentchangeslinked'   => 'Chanjyow a-dhiwedhes en folednow eus kevrednys orth an folen-ma',
'tooltip-feed-rss'                => 'Feed RSS rag an folen-ma',
'tooltip-feed-atom'               => 'Feed Atom rag an folen-ma',
'tooltip-t-contributions'         => 'Gweles rol kevrohow an devnydhyer-ma',
'tooltip-t-emailuser'             => "Danon e-bost dhe'n devnydhyer-ma",
'tooltip-t-upload'                => 'Ughcarga restrednow',
'tooltip-t-specialpages'          => 'Rol a geniver folen arbednek',
'tooltip-t-print'                 => 'Versyon pryntyadow an folen-ma',
'tooltip-t-permalink'             => "Kevren fast dhe'n amendyans-ma a'n folen",
'tooltip-ca-nstab-main'           => 'Gweles an folen dalhen',
'tooltip-ca-nstab-user'           => 'Gweles an folen devnydhyer',
'tooltip-ca-nstab-special'        => "Folen arbednek ew hebma, nag ello'whi chanjya an folen hy honen.",
'tooltip-ca-nstab-project'        => 'Gweles folen an wiki',
'tooltip-ca-nstab-image'          => 'Gweles folen an restren',
'tooltip-ca-nstab-template'       => 'Gweles an scantlyn',
'tooltip-ca-nstab-category'       => 'Gweles folen an class',
'tooltip-minoredit'               => 'Merkya hebma avel chanj bian',
'tooltip-save'                    => 'Gwitha agas chanjyow',
'tooltip-preview'                 => 'Ragweles agas chanjyow; gwrewgh usya hebma kens gwitha mar pleg!',
'tooltip-diff'                    => "Disqwedhes an chanjyow eus gwres genowgh dhe'n text",
'tooltip-compareselectedversions' => 'Gweles an dyffranjow ynter dew janjyow dowisyes an folen-ma',
'tooltip-watch'                   => "Keworra an folen-ma dhe'gas rol golyas",
'tooltip-rollback'                => '"Restorya" a wra trebuchya chanjyow gwres dhe\'n folen-ma gen an kens devnydhyer en udn glyck',
'tooltip-undo'                    => '"Diswul" a wra trebuchya an chanj-ma hag egery an furvlen chanjya en modh ragweles. Acheson a ell bos keworrys e\'n derivas cot.',
'tooltip-summary'                 => 'Entrowgh derivas cot',

# Attribution
'siteuser'         => 'devnydhyer {{SITENAME}} $1',
'lastmodifiedatby' => 'An folen-ma a veu kens chanjys dhe $2, $1 gen $3.',
'siteusers'        => '{{PLURAL:$2|devnydhyer|devnydhyoryon}} {{SITENAME}} $1',

# Browsing diffs
'previousdiff' => '← Chanj cottha',
'nextdiff'     => 'Chanj nowettha →',

# Media information
'file-info-size' => '$1 × $2 pixel, mens an restren: $3, sort MIME : $4',
'file-nohires'   => '<small>Nag eus clerder uhella cavadow.</small>',
'svg-long-desc'  => 'Restren SVG, $1 × $2 pixel en hanow, mens an restren: $3',
'show-big-image' => 'Clerder leun',

# Special:NewFiles
'ilsubmit' => 'Whila',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => "Ma dhe'n restren-ma kedhlow keworansel, dres lycklod keworrys dhort an camera besyel po an scanyer usys rag hy gwruthyl po besya. Mars ew an folen chanjys dhort hy studh gwredhek, martesen nag alja neb manylyon bos a-dro dhe'n folen janjys.",
'metadata-expand'   => 'Disqwedhes manylyon ystydnys',
'metadata-collapse' => 'Cudha manylyon ystydnys',

# EXIF tags
'exif-imagewidth'  => 'Les',
'exif-imagelength' => 'Uhelder',
'exif-artist'      => 'Awtour',

'exif-meteringmode-255' => 'Aral',

'exif-contrast-1' => 'Medhel',
'exif-contrast-2' => 'Cales',

# External editor support
'edit-externally' => 'Chanjya an restren-ma dre towlen a-ves',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'oll',
'namespacesall' => 'oll',
'monthsall'     => 'oll',

# Trackbacks
'trackbackremove' => '([$1 Dilea])',

# Multipage image navigation
'imgmultipageprev' => '← folen kens',
'imgmultipagenext' => 'folen nessa →',
'imgmultigo'       => 'Ke!',

# Table pager
'table_pager_limit_submit' => 'Ke',

# Auto-summaries
'autoredircomment' => 'Daswedyas an folen war-tu [[$1]]',
'autosumm-new'     => "Formyas folen gen: '$1'",

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
'fileduplicatesearch-submit'   => 'Whila',

# Special:SpecialPages
'specialpages' => 'Folednow arbednek',

# Special:Tags
'tags-edit' => 'chanjya',

);
