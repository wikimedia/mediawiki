<?php
/** Haitian (Kreyòl ayisyen)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Jvm
 * @author Masterches
 * @author Urhixidur
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_MEDIA          => 'Medya',
	NS_SPECIAL        => 'Espesyal',
	NS_MAIN           => '',
	NS_TALK           => 'Diskite',
	NS_USER           => 'Itilizatè',
	NS_USER_TALK      => 'Diskisyon_Itilizatè',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Diskisyon_$1',
	NS_FILE           => 'Imaj',
	NS_FILE_TALK      => 'Diskisyon_Imaj',
	NS_MEDIAWIKI      => 'MedyaWiki',
	NS_MEDIAWIKI_TALK => 'Diskisyon_MedyaWiki',
	NS_TEMPLATE       => 'Modèl',
	NS_TEMPLATE_TALK  => 'Diskisyon_Modèl',
	NS_HELP           => 'Èd',
	NS_HELP_TALK      => 'Diskisyon_Èd',
	NS_CATEGORY       => 'Kategori',
	NS_CATEGORY_TALK  => 'Diskisyon_Kategori'
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'RedireksyonDouble' ),
	'BrokenRedirects'           => array( 'RedireksyonKase' ),
	'Disambiguations'           => array( 'Disanbigyasyon', 'Klarifikasyon' ),
	'Userlogin'                 => array( 'Login', 'AntreLòg' ),
	'Userlogout'                => array( 'Dekoneksyon', 'ExitLòg' ),
	'CreateAccount'             => array( 'KreyeKonpt' ),
	'Preferences'               => array( 'Preferans' ),
	'Watchlist'                 => array( 'LisSwivi' ),
	'Recentchanges'             => array( 'ChanjmanResan' ),
	'Upload'                    => array( 'Telechaje' ),
	'Listfiles'                 => array( 'LisImaj' ),
	'Newimages'                 => array( 'NouvoImaj' ),
	'Listusers'                 => array( 'LisItilizatè', 'LisYonItilizatè' ),
	'Listgrouprights'           => array( 'LisDwaGroup' ),
	'Statistics'                => array( 'Estatistik' ),
	'Randompage'                => array( 'Randonm', 'PajRandonm' ),
	'Lonelypages'               => array( 'PajSolitè', 'PajOfelen' ),
	'Uncategorizedpages'        => array( 'PajEnkategorize' ),
	'Uncategorizedcategories'   => array( 'KategoriEnkategorize' ),
	'Uncategorizedimages'       => array( 'ImageEnkategorize' ),
	'Uncategorizedtemplates'    => array( 'TenmplaEnkategorize' ),
	'Unusedcategories'          => array( 'KategoriPaItilize' ),
	'Unusedimages'              => array( 'ImajePaItilize' ),
	'Wantedpages'               => array( 'PajBezwen', 'LyenKase' ),
	'Wantedcategories'          => array( 'KategoriBezwen' ),
	'Mostlinked'                => array( 'PlisLenke' ),
	'Mostlinkedcategories'      => array( 'KategoriPlisLenke', 'KategoriPlisItilize' ),
	'Mostlinkedtemplates'       => array( 'TenmplaPlisLenke', 'TenmplaPlisItilize' ),
	'Mostimages'                => array( 'PlisImaj' ),
	'Mostcategories'            => array( 'PlisKategori' ),
	'Mostrevisions'             => array( 'PlisRevizyon' ),
	'Fewestrevisions'           => array( 'MwensRevizyon' ),
	'Shortpages'                => array( 'PajKout' ),
	'Longpages'                 => array( 'PajLong' ),
	'Newpages'                  => array( 'PajNouvo' ),
	'Ancientpages'              => array( 'PajAnsyen' ),
	'Deadendpages'              => array( 'PajMòtèl' ),
	'Protectedpages'            => array( 'PajProteje' ),
	'Protectedtitles'           => array( 'TitProteje' ),
	'Allpages'                  => array( 'ToutPaj' ),
	'Prefixindex'               => array( 'PrefixEndèx' ),
	'Ipblocklist'               => array( 'LisIPBloke' ),
	'Specialpages'              => array( 'PajEspesyal' ),
	'Contributions'             => array( 'Kontribisyon' ),
	'Emailuser'                 => array( 'EmèlItilizatè' ),
	'Confirmemail'              => array( 'VerifyeEmèl' ),
	'Whatlinkshere'             => array( 'SakLenkeLa' ),
	'Recentchangeslinked'       => array( 'LyenChajmanResan', 'Chanjman Relativ' ),
	'Movepage'                  => array( 'DeplasePaj' ),
	'Blockme'                   => array( 'Bloke-m' ),
	'Booksources'               => array( 'SousLiv' ),
	'Categories'                => array( 'Kategori' ),
	'Export'                    => array( 'Expòt' ),
	'Version'                   => array( 'Vèsyon' ),
	'Allmessages'               => array( 'ToutMesaj' ),
	'Log'                       => array( 'Jounal', 'Lòg yo' ),
	'Blockip'                   => array( 'BlokeIP' ),
	'Undelete'                  => array( 'Restore' ),
	'Import'                    => array( 'Enpòt' ),
	'Lockdb'                    => array( 'KadnaseDB' ),
	'Unlockdb'                  => array( 'DekadnaseDB' ),
	'Userrights'                => array( 'DwaItilizatè' ),
	'MIMEsearch'                => array( 'BouskayMIME' ),
	'FileDuplicateSearch'       => array( 'BouskeFilDouplike' ),
	'Unwatchedpages'            => array( 'PajPaSiveye' ),
	'Listredirects'             => array( 'RedireksyonLis' ),
	'Revisiondelete'            => array( 'RevizyonSiprime' ),
	'Unusedtemplates'           => array( 'TanmplaVyèj' ),
	'Randomredirect'            => array( 'RedireksyonRandonm' ),
	'Mypage'                    => array( 'PajMwen' ),
	'Mytalk'                    => array( 'Diskisyon-m' ),
	'Mycontributions'           => array( 'Kontribisyon-m' ),
	'Listadmins'                => array( 'AdminLis' ),
	'Listbots'                  => array( 'RoboLis' ),
	'Popularpages'              => array( 'PajPopilè' ),
	'Search'                    => array( 'Chache', 'Fouye' ),
	'Resetpass'                 => array( 'ResètMoPas' ),
	'Withoutinterwiki'          => array( 'SanEntèwiki' ),
	'MergeHistory'              => array( 'MeleIstwa' ),
	'Filepath'                  => array( 'ChemenFil' ),
	'Invalidateemail'           => array( 'EnvalideEmèl' ),
	'Blankpage'                 => array( 'PajVid' ),
	'LinkSearch'                => array( 'BouskeLyen' ),
	'DeletedContributions'      => array( 'SiprimeKontribisyon' ),
);

$linkTrail = '/^([a-zàèòÀÈÒ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Souliyen lyen yo :',
'tog-highlightbroken'         => 'Afiche <a href="" class="new">nan koulè wouj</a> lyen yo ki ap mene nan paj ki pa egziste (oubyen : tankou <a href="" class="internal">?</a>)',
'tog-justify'                 => 'Aliyen paragraf yo',
'tog-hideminor'               => 'Kache tout modifikasyon resan yo ki pa enpòtan',
'tog-hidepatrolled'           => 'Kache modifikasyon yo ki fèk fèt pou moun ki ap veye yo',
'tog-extendwatchlist'         => 'Etann lis swivi pou ou kapab wè tout chanjman yo, pa sèlman sa ki fèk fèt yo',
'tog-usenewrc'                => 'Itilize lis swivi ki miyò a (JavaScript)',
'tog-numberheadings'          => 'Nimewote otomatiman tit yo',
'tog-showtoolbar'             => 'Montre panèl meni modifikasyon an',
'tog-editondblclick'          => 'Klike de fwa pou modifye yon paj (JavaScript)',
'tog-editsection'             => 'Pemèt edite yon seksyon via [edite] lyen yo',
'tog-editsectiononrightclick' => 'Pemèt edite yon seksyon pa klike a dwat tit seksyon an (JavaScrip)',
'tog-showtoc'                 => 'Montre tab de matyè yo (pou tout paj ki gen plis ke twa tit)',
'tog-rememberpassword'        => 'Sonje login mwen nan òdinatè sa',
'tog-editwidth'               => 'Montre fenèt pou modifikasyon yo nan mitan paj lan',
'tog-watchcreations'          => 'Ajoute paj yo ke mwen ap kreye nan lis swivi mwen.',
'tog-watchdefault'            => 'Mete paj mwen edite yo nan lis veye m',
'tog-watchmoves'              => 'Mete paj mwen deplase nan lis veye m',
'tog-watchdeletion'           => 'Mete paj mwen delete nan lis veye m',
'tog-minordefault'            => 'Make tout edit yo minè pa defo',
'tog-previewontop'            => 'Montre previzializasyon anvan bwat edite',
'tog-previewonfirst'          => 'Montre previzializasyon sou premye edit',
'tog-nocache'                 => 'Dezame paj kapte',
'tog-enotifwatchlistpages'    => 'Notifye m pa e-mèl kan youn nan paj m’ap swiv yo chanje',
'tog-enotifusertalkpages'     => 'E-mèl mwen kan paj itilizatè m nan chanje',
'tog-enotifminoredits'        => 'E-mèl mwen tou pou edit minè paj yo',
'tog-enotifrevealaddr'        => 'Montre adrès e-mèl mwen nan kominikasyon notifikasyon yo',
'tog-shownumberswatching'     => 'Montre kantite itlizatè k’ap swiv',
'tog-fancysig'                => 'Signati kri (san lyen otomatik)',
'tog-externaleditor'          => 'Itilize editè ki pa nan sistèm wikimedya pa defo',
'tog-externaldiff'            => 'Itilize yon konparatè ki pa nan sitsèm wikimedya pa defo',
'tog-showjumplinks'           => 'Demare « jonpe a » asesabilite lyen',
'tog-uselivepreview'          => 'Itilize previzializasyon kouran (JavaScrip) (Experimantal)',
'tog-forceeditsummary'        => 'Notifye m lè m’ap antre yon  sonmè edite vid',
'tog-watchlisthideown'        => 'Kache edisyon m yo nan lis siveye-a',
'tog-watchlisthidebots'       => 'Kache edisyon bot nan lis siveye-a',
'tog-watchlisthideminor'      => 'Kache edisyon minè yo nan lis siveye-a',
'tog-ccmeonemails'            => 'Voye yon kopi e-mèl mwen voye ba lòt ban mwen',
'tog-diffonly'                => 'Piga moutre enfòmsyon yon paj piba diffs',
'tog-showhiddencats'          => 'Moutre kategori kache yo',

'underline-always'  => 'Toujou',
'underline-never'   => 'Jamè',
'underline-default' => 'Brozè defo',

# Dates
'sunday'        => 'dimanch',
'monday'        => 'lendi',
'tuesday'       => 'madi',
'wednesday'     => 'mèkredi',
'thursday'      => 'jedi',
'friday'        => 'vandredi',
'saturday'      => 'samdi',
'sun'           => 'dim',
'mon'           => 'len',
'tue'           => 'mad',
'wed'           => 'mè',
'thu'           => 'jed',
'fri'           => 'van',
'sat'           => 'sam',
'january'       => 'janvye',
'february'      => 'fevriye',
'march'         => 'mas',
'april'         => 'avril',
'may_long'      => 'me',
'june'          => 'jen',
'july'          => 'jiyè',
'august'        => 'out',
'september'     => 'septanm',
'october'       => 'oktòb',
'november'      => 'novanm',
'december'      => 'desanm',
'january-gen'   => 'janvye',
'february-gen'  => 'fevriye',
'march-gen'     => 'mas',
'april-gen'     => 'avril',
'may-gen'       => 'me',
'june-gen'      => 'jen',
'july-gen'      => 'jiyè',
'august-gen'    => 'out, awou',
'september-gen' => 'septanm',
'october-gen'   => 'oktòb',
'november-gen'  => 'novanm',
'december-gen'  => 'desanm',
'jan'           => 'jan',
'feb'           => 'fev',
'mar'           => 'mas',
'apr'           => 'avr',
'may'           => 'me',
'jun'           => 'jen',
'jul'           => 'jiy',
'aug'           => 'out',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'nov',
'dec'           => 'des',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategori|Kategori yo}}',
'category_header'                => 'Paj yo ki nann  kategori « $1 »',
'subcategories'                  => 'Pitit kategori yo',
'category-media-header'          => 'Fichye miltimedya nan kategori « $1 »',
'category-empty'                 => "''Kategori sa a pa genyen atik andedan li, ni sou-kategori, ni menm yon fichye miltimedya.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori sere|Kategori sere yo}}',
'hidden-category-category'       => 'Kategori ki kache yo',
'category-subcat-count'          => '{{PLURAL:$2|Kategori sa gen sèlman subkategori swivan.|Kategori sa gen swivan {{PLURAL:$1|subkategori|$1 subkategori sa yo}}, sou $2 total.}}',
'category-subcat-count-limited'  => 'Kategori sa gen swivan {{PLURAL:$1|subkategori|$1 subkategori sa yo}}.',
'category-article-count'         => '{{PLURAL:$2|Kategori sa gen sèlman paj swivan.|Swivan {{PLURAL:$1|paj sa|$1 paj sa yo}} nan kategori sa, sou $2 total.}}',
'category-article-count-limited' => 'Swivan {{PLURAL:$1|paj sa|$1 paj sa yo}} nan kategori kouran.',
'category-file-count'            => '{{PLURAL:$2|Kategori sa gen sèlman dokiman swivan.|Swivan {{PLURAL:$1|dokiman sa|$1 dokimans sa yo}} nan kategori sa, sou $2 total.}}',
'category-file-count-limited'    => 'Swivan {{PLURAL:$1|dokiman sa|$1 dokiman sa yo}} nan kategori kouran.',
'listingcontinuesabbrev'         => '(kontinye)',

'mainpagetext'      => "<big>'''MedyaWiki byen enstale l.'''</big>",
'mainpagedocfooter' => 'Konsilte [http://meta.wikimedia.org/wiki/Help:Konteni Gid Itilizatè] pou enfòmasyon sou kijan pou w itilize logisye wiki-a.

== Kijan kòmanse ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lis paramèt yo pou konfigirazyon]
* [http://www.mediawiki.org/wiki/Manyèl:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lis diskisyon pou chak ki parèt sou MediaWiki]',

'about'         => 'Apwopo',
'article'       => 'Atik',
'newwindow'     => '(Ouvè nan yon lòt fenèt)',
'cancel'        => 'Anile',
'moredotdotdot' => 'Pi plis …',
'mypage'        => 'Paj mwen',
'mytalk'        => 'Paj diskisyon mwen an',
'anontalk'      => 'Diskite avèk adrès IP sa',
'navigation'    => 'Navigasyon',
'and'           => '&#32;epi',

# Cologne Blue skin
'qbfind'         => 'Chache',
'qbbrowse'       => 'Bouske',
'qbedit'         => 'Modifye',
'qbpageoptions'  => 'Opsyon Paj sa a',
'qbpageinfo'     => 'Kontèks',
'qbmyoptions'    => 'Paj mwen yo',
'qbspecialpages' => 'Paj espesyal',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Erè',
'returnto'          => 'Ritounen nan paj $1.',
'tagline'           => 'Yon atik de {{SITENAME}}.',
'help'              => 'Èd',
'search'            => 'Chache',
'searchbutton'      => 'Fouye',
'go'                => 'Ale',
'searcharticle'     => 'Wè',
'history'           => 'Paj istwa',
'history_short'     => 'Istwa',
'updatedmarker'     => 'Aktyalize depi dènyè visit mwen',
'info_short'        => 'Enfòmasyon',
'printableversion'  => 'Vèsyon ou kapab enprime',
'permalink'         => 'Lyen pou tout tan',
'print'             => 'Prente',
'edit'              => 'Modifye',
'create'            => 'Kreye',
'editthispage'      => 'Modifye paj sa a',
'create-this-page'  => 'Kreye paj sa',
'delete'            => 'Efase',
'deletethispage'    => 'Delete paj sa',
'undelete_short'    => 'Restore {{PLURAL:$1|1 yon modifikasyon| $1 modifikasyon yo}}',
'protect'           => 'Pwoteje',
'protect_change'    => 'Chanje pwoteksyon paj sa',
'protectthispage'   => 'Pwoteje paj sa',
'unprotect'         => 'Depwoteje',
'unprotectthispage' => 'Depwoteje paj sa',
'newpage'           => 'Nouvo paj',
'talkpage'          => 'Diskite paj sa a',
'talkpagelinktext'  => 'Diskite',
'specialpage'       => 'Paj Espesyal',
'personaltools'     => 'Zouti pèsonèl yo',
'postcomment'       => 'Poste yon kòmantè',
'articlepage'       => 'Wè paj konteni',
'talk'              => 'Diskisyon',
'views'             => 'Afichay yo',
'toolbox'           => 'Bwat zouti yo',
'userpage'          => 'Wè paj itilizatè',
'projectpage'       => 'Wè paj pwojè',
'imagepage'         => 'Wè maj medya',
'mediawikipage'     => 'Wè paj mesaj',
'templatepage'      => 'Wè paj modèl',
'viewhelppage'      => 'Wè paj èd',
'categorypage'      => 'Wè paj kategori',
'viewtalkpage'      => 'Wè paj diskisyon',
'otherlanguages'    => 'Nan lòt langaj yo',
'redirectedfrom'    => '(Redirije depi $1)',
'redirectpagesub'   => 'Paj pou redireksyon',
'lastmodifiedat'    => 'Paj sa te modifye pou dènye fwa $1 à $2.<br />',
'viewcount'         => 'Paj sa te konsilte {{PLURAL:$1|yon fwa|$1 fwa}}.',
'protectedpage'     => 'Paj pwoteje',
'jumpto'            => 'Ale nan:',
'jumptonavigation'  => 'Navigasyon',
'jumptosearch'      => 'Fouye',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Apwopo {{SITENAME}}',
'aboutpage'            => 'Project:Apwopo',
'copyright'            => 'Konteni avalab anba $1.',
'copyrightpagename'    => '{{SITENAME}} dwa rezève',
'copyrightpage'        => '{{ns:project}}:Dwa rezève',
'currentevents'        => 'Aktyalite yo',
'currentevents-url'    => 'Project:Aktyalite yo',
'disclaimers'          => 'Avètisman',
'disclaimerpage'       => 'Project:Avètisman jeneral yo',
'edithelp'             => 'Edite paj èd la',
'edithelppage'         => 'Help:kòman ou ka modifye yon paj',
'helppage'             => 'Help:Èd',
'mainpage'             => 'Paj prensipal',
'mainpage-description' => 'Paj prensipal',
'policy-url'           => 'Project:Polisi',
'portal'               => 'Pòtay kominote',
'portal-url'           => 'Project:Akèy',
'privacy'              => 'Politik konfidansyalite',
'privacypage'          => 'Project:Konfidansyalite',

'badaccess'        => 'Pèmisyon erè',
'badaccess-group0' => 'Ou pa genyen pèmisyon pou ou ekzekite demand sa.',
'badaccess-groups' => 'Aksyon ke w vle reyalize a limite sèlman pou itilizatè ki nan {{PLURAL:$2|gwoup sa |yonn nan gwoup sa yo}}: $1.',

'versionrequired'     => 'Vèzion $1 de MediaWiki nesesè',
'versionrequiredtext' => 'Vèzion $1 de MediaWiki nesesè pou itilize paj sa. Wè [[Special:Version|version page]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Rekipere depi « $1 »',
'youhavenewmessages'      => 'Ou genyen $1 ($2).',
'newmessageslink'         => 'Ou genyen nouvo mesaj',
'newmessagesdifflink'     => 'dènye chanjman',
'youhavenewmessagesmulti' => 'Ou genyen nouvo mesaj sou $1.',
'editsection'             => 'modifye',
'editold'                 => 'modifye',
'viewsourceold'           => 'Wè kòd paj an',
'editlink'                => 'modifye',
'viewsourcelink'          => 'wè kòd tèks sa',
'editsectionhint'         => 'Modifye seksyon : $1',
'toc'                     => 'Kontni yo',
'showtoc'                 => 'montre',
'hidetoc'                 => 'kache',
'thisisdeleted'           => 'Ou vle wè ubien restore $1 ?',
'viewdeleted'             => 'Wè $1 ?',
'restorelink'             => '{{PLURAL:$1|yon revizion efase|$1 revizion efase yo}}',
'feedlinks'               => 'Nouri:',
'feed-invalid'            => 'Souskripsyon tip nouri envalid.',
'feed-unavailable'        => 'Flo sendikasyon yo pa disponib',
'site-rss-feed'           => 'Flow RSS depi $1',
'site-atom-feed'          => 'Flow Atom depi $1',
'page-rss-feed'           => 'Flow RSS pou "$1"',
'page-atom-feed'          => '"$1" Nouri Atom',
'red-link-title'          => '$1 (paj sa pa egziste nan sistèm an)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Paj',
'nstab-user'      => 'Paj itilizatè',
'nstab-media'     => 'Paj Medya',
'nstab-special'   => 'Paj espesyal',
'nstab-project'   => 'Paj pwojè a',
'nstab-image'     => 'Fichye',
'nstab-mediawiki' => 'Mesaj',
'nstab-template'  => 'Modèl',
'nstab-help'      => 'Paj èd',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Pa gen bagay konsa',
'nosuchactiontext'  => 'Wiki-a pa rekonèt Aksyon ki espesifye pa URL la',
'nosuchspecialpage' => 'Pa gen paj especial konsa',
'nospecialpagetext' => "<big>'''Paj espesial ou demande-a envalid.'''</big>

Ou ka jwenn yon lis paj espesial ki valid yo la [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Erè',
'databaseerror'        => '
Erè nan bazdata.',
'dberrortext'          => 'Yon demann oubyen rekèt nan baz done a bay yon erè.
Sa kapab vle di ke genyen yon erè ki nan lojisyèl an.
Dènye esè a te :
<blockquote><tt>$1</tt></blockquote>
depi fonksyon sa « <tt>$2</tt> ».
MySQL ritounen erè sa « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Yon demann nan baz done a bay yon erè.
Dènye esè nan baz done a te: « $1 » fèt pa fonksyon sa « $2 ». MySQL ritounen mesaj sa « $3 : $4 ».',
'laggedslavemode'      => 'Pòte atansyon, paj sa a pa kapab anrejistre modifikasyon ki fèk fèt yo.',
'readonly'             => 'Baz done a fème toutbon.',
'enterlockreason'      => 'Bay yon rezon pou fème baz done a epitou yon estimasyon pou tan sa ap pran w pou l ouvri ankò',
'readonlytext'         => 'Baz done a fème kounye a; nou pa kapab ajoute pyès done anndan l. Li sanble se pou pèmèt jesyon l; apre sa, l ap reprann sèvis li.

Administratè a ki te fème l bay rezon sa a : $1',
'missing-article'      => 'Bazdone an pa twouve tèks yon paj li te dwèt twouve toutbon; non li se « $1 » $2.

Li sanble ke ou swiv yon lyen ki pa egziste ankò depi chanjman ki te fèt nan paj sa oubyen istorik ou swiv yon paj ki te ja efase.

Si rezon sa yo pa koresponn ak sityasyon ou an, ou gen dwa twouve kèk erè nan lojisyèl nou an.

Souple, kontakte yon [[Special:ListUsers/sysop|administratè]], ba l adrès sa, lyen tout bagay pou li kapab twouve erè sa.',
'missingarticle-rev'   => '(chanjman # : $1)',
'missingarticle-diff'  => '(Diferans : $1, $2)',
'readonly_lag'         => 'Bazdone a bloke otomatikman pandan lòt sèvè segondè yo ap travay pou bay lanmen nan sèvè prensipal an.',
'internalerror'        => 'Erè nan sistèm an.',
'internalerror_info'   => 'Erè nan sistèm an : $1',
'filecopyerror'        => 'Nou pa kapab kopye fichye  « $1 » sa nan « $2 ».',
'filerenameerror'      => 'Nou pa kapab bay lòt non « $2 » pou fichye « $1 » sa.',
'filedeleteerror'      => 'Nou pa kapab efase fichye « $1 » sa.',
'directorycreateerror' => 'Nou pa kapab kreye dosye « $1 » sa.',
'filenotfound'         => 'Nou pa kapab twouve fichye « $1 » sa.',
'fileexistserror'      => 'Nou pa kapab ekri nan dosye « $1 » : fichye a egziste deja',
'unexpected'           => 'Valè sa pa koresponn ak sa nou genyen nan sistèm an : « $1 » = « $2 ».',
'formerror'            => 'Erè : nou pa kapab anrejistre fòmilè sa',
'badarticleerror'      => 'Ou pa kapab fè aksyon sa sou paj sa.',
'cannotdelete'         => 'Nou pa kapab efase paj oubyen fichye ou bay an. (Yon lòt moun gen dwa fè l anvan ou.)',
'badtitle'             => 'Tit ou bay an pa bon, li pa koresponn nan sistèm an, eseye byen ekri li',
'badtitletext'         => 'Tit, sijè paj ou mande a pa korèk oubyen li pa egziste oubyen li nan yon lòt pwojè wiki yo (gade nan lòt pwojè wiki yo pou wè toutbon). Li mèt genyen tou kèk karaktè ki pa rekonèt nan sistèm an, eseye itilize bon karaktè yo nan tit ou yo.',
'perfcached'           => 'Sa se yon vèsyon sòti nan sistèm kach ou an.Li gen dwa pa a jou.',
'perfcachedts'         => 'Done sa yo nan sistèm cach an, yo gen dwa pa fèk fèt. Dènye fwa nou mete l a jou sete $1.',
'querypage-no-updates' => 'Nou pa kapab mete paj sa yo a jou paske fonksyon l dezaktive. Done ou ap twouve pli ba pa fèk mete.',
'wrong_wfQuery_params' => 'Paramèt sa yo pa bon sou wfQuery()<br />
Fonksyon : $1<br />
Demann : $2',
'viewsource'           => 'Wè kòd tèks sa a',
'viewsourcefor'        => 'pou $1',
'actionthrottled'      => 'Aksyon sa limite',
'actionthrottledtext'  => 'Nan batay kont "spam" yo, aksyon sa ou ta pral fè limite nan kantite itilizasyon l pandan yon tan nou bay ki kout kout. Li sanble ou depase tan sa. Eseye ankò nan kèk minit.',
'protectedpagetext'    => 'Paj sa pwoteje pou anpeche tout modifikasyon nou ta kapab fè sou li. Gade paj diskisyon sou li pito.',
'viewsourcetext'       => 'Ou kapab gade epitou modifye kontni atik sa a pou ou travay anlè li :',
'protectedinterface'   => 'Paj sa ap bay tèks pou entèfas lojisyèl an e li pwoteje pou anpeche move itilizasyon nou ta kapab fè ak li.',
'editinginterface'     => "'''Pòte atansyon :''' ou ap modifye yon paj ki itilize nan kreyasyon tèks entèfas lojisyèl an. Chanjman yo ap ritounen, li ap depann de kèk sityasyon, nan tout paj ke lòt itilizatè yo kapab wè tou. Pou tradiksyon yo, nap envite w itilize pwojè MediaWiki pou mesaj entènasyonal yo (tradiksyon) nan paj sa [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(Demann SQL an kache)',
'cascadeprotected'     => 'Paj sa pwoteje kounye a paske l nan {{PLURAL:$1|paj ki douvan l|paj yo ki douvan l}}, paske {{PLURAL:$1|l te pwoteje|yo te pwoteje}} ak opsyon « pwoteksyon pou tout paj ki nan premye paj an - kaskad » aktive :
$2',
'namespaceprotected'   => "Ou pa gen dwa modifye paj nan espas non « '''$1''' ».",
'customcssjsprotected' => 'Ou pa kapab modifye paj sa paske li manke w kèk otorizasyon; li genyen preferans yon lòt itilizatè.',
'ns-specialprotected'  => 'Paj yon ki nan espas non « {{ns:special}} » pa kapab modifye.',
'titleprotected'       => "Tit, sijè sa pwoteje pandan kreyasyon l pa [[User:$1|$1]].
Rezon li bay yo se « ''$2'' ».",

# Virus scanner
'virus-badscanner'     => "Move konfigirasyon : eskanè viris sa, nou pa konenn l : ''$1''",
'virus-scanfailed'     => 'Rechèch an pa ritounen pyès rezilta (kòd $1)',
'virus-unknownscanner' => 'antiviris nou pa konnen :',

# Login and logout pages
'logouttext'                 => "'''Monchè oubyen machè, ou dekonekte kounye a.'''

Ou mèt kontinye itilize {{SITENAME}} san ou pa bezwen konekte w, oubyen si ou [[Special:UserLogin|rekonekte]] w ankò ak menm non an oubyen yon lòt.",
'welcomecreation'            => '== Byenvini, $1 ! ==

Kont ou an kreye. Pa bliye pèsonalize l nan  [[Special:Preferences|preferans ou an sou paj sa {{SITENAME}}]].',
'yourname'                   => 'Non itilizatè ou an :',
'yourpassword'               => 'Mopas ou an :',
'yourpasswordagain'          => 'Mete mopas ou an ankò :',
'remembermypassword'         => 'Anrejistre mopas mwen an nan òdinatè mwen an',
'yourdomainname'             => 'Domèn ou an',
'externaldberror'            => 'Li sanble ke yon erè pwodui ak bazdone a pou idantifikasyon ki pa nan sistèm an, oubyen ou pa otorize pou mete a jou kont ou genyen nan lòt sistèm yo.',
'login'                      => 'Idantifikasyon',
'nav-login-createaccount'    => 'Kreye yon kont oubyen konekte ou',
'loginprompt'                => 'Ou dwèt aksepte (aktive) koukiz (cookies) yopou ou kapab konekte nan {{SITENAME}}.',
'userlogin'                  => 'Kreye yon kont oubyen konekte ou',
'logout'                     => 'Dekonekte ou',
'userlogout'                 => 'Dekoneksyon',
'notloggedin'                => 'Ou pa konekte',
'nologin'                    => 'Ou pa genyen yon kont ? $1.',
'nologinlink'                => 'Kreye yon kont',
'createaccount'              => 'Kreye yon kont',
'gotaccount'                 => 'Ou ja genyen yon kont ? $1.',
'gotaccountlink'             => 'Idantifye ou',
'createaccountmail'          => 'pa imèl',
'badretype'                  => 'Mopas ou bay yo pa parèy ditou.',
'userexists'                 => 'Non itilizatè ou bay an deja itilize pa yon lòt moun. Chwazi yon lòt souple.',
'loginerror'                 => 'Erè nan idantifikasyon ou an',
'nocookiesnew'               => "Kont itilizatè a kreye, men ou pa konekte. {{SITENAME}} ap itilize koukiz (''cookies'') pou konekte l.Li sanble ou dezaktive fonksyon sa. Tanpri, aktive fonksyon sa epi rekonekte ou ak menm non epi mopas ou yo.",
'nocookieslogin'             => "{{SITENAME}} ap itilize koukiz (''cookies'') pou li kapab konekte kò l. Men li sanble ou dezaktive l; tanpri, aktive fonksyon sa epi rekonekte w.",
'noname'                     => 'Ou pa bay sistèm an yon non itilizatè ki bon.',
'loginsuccesstitle'          => 'Ou byen idantifye nan sistèm an',
'loginsuccess'               => 'Ou konekte kounye a nan {{SITENAME}} epi idantifyan sa a « $1 ».',
'nosuchuser'                 => 'Itilizatè "$1" sa pa egziste.
Byen gade ke ou byen ekri non ou, oubyen [[Special:UserLogin/signup|kreye yon nouvo kont sou non ou]].',
'nosuchusershort'            => 'Pa genyen kontribitè ak non « <nowiki>$1</nowiki> » sa a. Byen gade lòtograf ou an.',
'nouserspecified'            => 'Ou dwèt mete non itilizatè ou an.',
'wrongpassword'              => 'Mopas an pa korèk. Eseye ankò.',
'wrongpasswordempty'         => 'Ou pa antre mopas ou an. Eseye ankò.',
'passwordtooshort'           => 'Mopas ou an two kout. Li dwèt kontni $1 karaktè{{PLURAL:$1||}} oubyen plis epitou li dwèt diferan de non itilizatè ou an.',
'mailmypassword'             => 'Voye mwen yon nouvo mopas pa imèl',
'passwordremindertitle'      => 'Nouvo mopas tanporè, li pap dire (yon kout tan) pou pajwèb sa a {{SITENAME}}',
'passwordremindertext'       => 'Kèk moun (ou menm oubyen yon moun ki genyen adrès IP sa a $1) mande ke nou voye ou yon nouvo mopas pou {{SITENAME}} ($4).
Mopas itilizatè "$2" se kounye a "$3".

Nou konseye ou konekte ou epi modifye mopas sa a rapidman, si posib kounye a.

Si se pa ou menm ki mande modifye mopas ou an oubyen si ou konnen mopas ou an e ke ou pa ta vle modifye li, pa konsidere mesaj sa a epi kontinye ak mopas ou a.',
'noemail'                    => 'Pa genyen pyès adrès imèl ki anrejistre pou itilizatè sa a « $1 ».',
'passwordsent'               => 'Yon nouvo mopas voye ba imèl sa a pou itilizatè « $1 ». Souple, konekte ou lè ou resevwa mesaj an.',
'blocked-mailpassword'       => 'Adrès IP ou an bloke pou edisyon, fonksyon rapèl mopas dezaktive toutbon pou anpeche move itilizasyon ki kapab fèt ak li.',
'eauthentsent'               => 'Nou voye yon imèl pou konfimasyon nan adrès imèl an.
Anvan yon lòt imèl voye, swiv komand ki nan mesaj imèl an epi konfime ke kont an se byen kont ou an.',
'throttled-mailpassword'     => 'Yon imèl ki genyen anndan l mopas ou an pou rapèl voye pandan {{PLURAL:$1|dènye lè a|dènye $1 zè sa yo}}. Pou anpeche pwofitè ak kèk move itilizasyon, yon sèl imèl ap voye nan {{PLURAL:$1|è sa|entèval $1 zè sa yo}}.',
'mailerror'                  => 'Erè ki vini lè nap voye imèl an : $1',
'acct_creation_throttle_hit' => 'Eskize nou, ou te deja kreye {{PLURAL:$1|1 kont|$1 kont}}. Ou pa kapab kreye dòt ankò.',
'emailauthenticated'         => 'Adrès imèl ou an idantifye nan sistèm nou an depi $2 nan $3.',
'emailnotauthenticated'      => '<strong>Nou pa kapab idantifye</strong> adrès imèl ou an. Pyès mesaj imèl ke voyen pou chak fonksyon sa yo.',
'noemailprefs'               => 'Ou pa bay pyès adrès imèl nan mesaj preferans ou an, fonksyon sa yo pe ke disponib.',
'emailconfirmlink'           => 'Konfime adrès imèl ou an',
'invalidemailaddress'        => 'Nou pa kapab aksepte adrès imèl sa paske li sanble fòma l pa bon ditou. Tanpri, mete yon adrès ki nan yon bon fòma oubyen pa ranpli seksyon sa.',
'accountcreated'             => 'Kont ou an kreye',
'accountcreatedtext'         => 'Kont itilizatè $1 an kreye.',
'createaccount-title'        => 'Kreyasyon yon kont pou {{SITENAME}}',
'createaccount-text'         => 'Yon moun kreye yon kont pou adrès imèl ou an sou paj sa {{SITENAME}} ($4), non l se « $2 », mopas an se « $3 ». Ou ta dwèt ouvè yon sesyon pou chanje, kounye a mopas sa.

Pa pòte atansyon pou mesaj sa si kont sa kreye pa erè.',
'login-throttled'            => 'Ou fè twòp tantativ pou konekte w ak mopas ou an. Souple, atann yon ti moman anvan ou eseye ankò.',
'loginlanguagelabel'         => 'Lang : $1',

# Password reset dialog
'resetpass'                 => 'Efase mopas ou an pou konfigire yon lòt oubyen chanje l',
'resetpass_announce'        => 'Ou anrejistre ou ak yon mopas ki valab yon moman; mopas sa te voye pa imèl. Pou ou kapab fini anrejistreman sa, ou dwèt mete yon nouvo mopas kote sit :',
'resetpass_header'          => 'Mopas ou an reyinisyalize',
'retypenew'                 => 'Konfime nouvo mopas an :',
'resetpass_submit'          => 'Chanje mopas epitou anrejistre',
'resetpass_success'         => 'Nou chanje mopas ou an ak siksè ! Nap anrejistre ou kounye a...',
'resetpass_forbidden'       => 'Nou pa kapab chanje mopas yo nan sistèm sa',
'resetpass-no-info'         => 'Ou dwèt konekte pou ou kapab vini nan paj sa.',
'resetpass-submit-loggedin' => 'Modifye mopas sa',
'resetpass-wrong-oldpass'   => 'Mopas sa pa bon ditou;i te mèt mopas ou an kounye a oubyen yonn ki pa ap dire lontan.
Ou dwèt te deja modifye l oubyen mande yon nouvo mopas ki tap pèmèt ou chanje l.',
'resetpass-temp-password'   => 'Mopas yo ba ou an (li pap rete lontan):',

# Edit page toolbar
'bold_sample'     => 'Tèks fonse',
'bold_tip'        => 'Tèks fonse',
'italic_sample'   => 'Tèks italik',
'italic_tip'      => 'Tèks italik',
'link_sample'     => 'Lyen pou tit an',
'link_tip'        => 'Lyen andidan',
'extlink_sample'  => 'http://www.example.com yon tit pou lyen an',
'extlink_tip'     => 'Lyen dewò (pa blye prefiks http:// an)',
'headline_sample' => 'Tèks pou tit',
'headline_tip'    => 'Sou-tit nivo 2',
'math_sample'     => 'Antre fòmil ou an isit',
'math_tip'        => 'Fòmil matematik (LaTeX)',
'nowiki_sample'   => 'Antre tèks ki pa fòmate a',
'nowiki_tip'      => 'Pa konte sentaks wiki an',
'image_tip'       => 'Fichye anndan paj sa',
'media_tip'       => 'Lyen pou dosye sa',
'sig_tip'         => 'Siyati ou ak dat an',
'hr_tip'          => 'Liy orizontal (pa abize)',

# Edit pages
'summary'                          => 'Somè&nbsp;:',
'subject'                          => 'Sijè/tit:',
'minoredit'                        => 'Modifikasyon sa a pa enpòtan',
'watchthis'                        => 'Swiv paj sa a',
'savearticle'                      => 'Anrejistre',
'preview'                          => 'Gade pou wè',
'showpreview'                      => 'Gade pou wè',
'showlivepreview'                  => 'Gade pou wè (Kout zye rapid)',
'showdiff'                         => 'Montre chanjman yo',
'anoneditwarning'                  => "'''Pòte atansyon :''' ou pa idantifye nan sistèm an. Adrès IP ou a ap anrejistre nan istorik paj sa a.",
'missingsummary'                   => "'''Souple :''' ou poko bay rezimz modifikasyon ou fè an
Si ou klike sou \"Pibliye\", piblikasyon sa ke ap fèt san pyès avètisman.",
'missingcommenttext'               => 'Souple, ekri komantè ou an pli ba nan paj sa.',
'missingcommentheader'             => "'''Pòte atansyon :''' ou pa bay komantè ou an yon sijè/tit .
Si ou klike sou \"Pibliye\", edisyon ou an pap genyen yon tit.",
'summary-preview'                  => 'Kout zye nan rezime an anvan li anrejistre:',
'subject-preview'                  => 'Yon kout zye sou sijè/tit atik kontni sa:',
'blockedtitle'                     => 'itilizatè a bloke.',
'blockedtext'                      => "<big>'''Kont itilizatè ou an (oubyen adrès IP ou an) bloke.'''</big>

Blokaj an fèt pa $1.
Rezon li bay se : ''$2''.


* Komansman blokaj an : $8
* Dat pou blokaj an fini : $6
* Kont bloke a : $7.

Ou mèt kontakte $1 oubyen yon lòt [[{{MediaWiki:Grouppage-sysop}}|administratè]] pou diskite plis. Ou pa kapab itilize fonksyon  « Voye yon imèl ba itilizatè sa a » eksepte si ou mete yon adrès imèl nan paj  [[Special:Preferences|preferans ou an]]. Adrès IP ou an kounye a se $3 e idantifyan blokaj ou an se #$5. Mete souple referans adrès sa a nan demann ou yo.",
'autoblockedtext'                  => 'Adrès IP ou an bloke otomatikman pa yon lòt itilizatè, li menm men bloke pa l pa $1.

Rezon bagay sa yo :

:\'\'$2\'\'

* Komansman blokaj an : $8
* Tan li pral fini : $6
* Moun ki te bloke a : $7

Ou mèt kontakte $1 oubyen yonn nan [[{{MediaWiki:Grouppage-sysop}}|administratè yo]] pou diskite sityasyon blokaj sa.

Si toutfwa ou te bay yon adrès imèl ki te bon nan preferans ou yo ( [[Special:Preferences|préférences]]) epitou ou kapab itilize l, ou mèt itilize fonksyon "voye yon mesaj ba itilizatè sa" pou ou kontakte administratè a.

Adrès IP ou an kounye a se $3. Idantifyan, non ou an nan kilès ou bloke a se $5. Ou dwèt mete enfòmasyon sa yo nan demann ou an.',
'blockednoreason'                  => 'Li pa bay pyès rezon pou aksyon sa',
'blockedoriginalsource'            => "Wè kòd sous '''$1''' pli ba :",
'blockededitsource'                => "Kontni '''modifikasyon ou yo''' nan '''$1''' ekri pli ba :",
'whitelistedittitle'               => 'Ou dwèt konekte w pou ou kapab edite epi modifye tèks sa, kontni sa',
'whitelistedittext'                => 'Ou dwèt gen fonksyon sa $1 pou ou kapab genyen dwa pou modifye kontni sa.',
'confirmedittext'                  => 'Ou dwèt konfime adrès imèl ou an anvan ou modifye paj {{SITENAME}} sa. Antre epi valide adrès elektwonik ou an ak èd ou kapab twouve nan paj sa [[Special:Preferences|preferans]].',
'nosuchsectiontitle'               => 'Seksyon sa pa gen anyen sou li',
'nosuchsectiontext'                => 'Ou eseye modifye yon seksyon ki pa egziste nan sitèm an.
Paske sistèm an pa gen seksyon $1 sa, nou pa twouve pyès lòt kote pou pibliye modifikasyon ou fè nan paj sa.',
'loginreqtitle'                    => 'Koneksyon an nesesè',
'loginreqlink'                     => 'konekete ou',
'loginreqpagetext'                 => 'Ou dwèt $1 pou ou kapab wè lòt paj yo.',
'accmailtitle'                     => 'Mopas an voye.',
'accmailtext'                      => 'Mopas pou « $1 » voye nan adrès $2.',
'newarticle'                       => '(Nouvo)',
'newarticletext'                   => "Ou swiv yon paj ki poko egziste nan sistèm sa a.
Pou ou kapab kreye paj sa a, komanse ap ekri nan bwat sa a ki anba (gade [[{{MediaWiki:Helppage}}|paj èd an]] pou konnen plis, pou plis enfòmasyon).

Si se paske ou komèt yon erè ke ou ap twouve ou nan paj sa a, klike anlè bouton '''ritounen''' nan bwozè ou a.",
'anontalkpagetext'                 => "---- ''Ou nan paj diskisyon yon itilizatè anonim, ki pa gen non, ki poko kreye yon kont oubyen ki pa itilize pyès kont nan sistèm sa. Pou rezon sa, nou dwèt itilize adrès IP l pou nou kapab lokalize l, sitye l, montre kote l rete, idantifye l. Yon adrès IP kapab pataje ant plizyè moun, plizyè itilizatè. Si ou se yon itilizatè anonim e si ou wè ke ou resevwa komantè ki pa te pou ou, ou mèt [[Special:UserLogin|kreye yon kont oubyen konekte w]] pou ou kapab anpeche difikilte sa yo, move bagay sa yo ant lòt itilizatè yo, kontribitè anonim yo.''",
'noarticletext'                    => 'Poko genyen tèks nan paj sa a, ou mèt [[Special:Search/{{PAGENAME}}|fè yon rechèch, fouye ak non paj sa a]] nan lòt paj, oubyen [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifye li].',
'userpage-userdoesnotexist'        => 'Kont itilizatè « $1 » sa pa anrejistre. Verifye toutbon ke ou vle kreye paj sa.',
'clearyourcache'                   => "'''Note bagay sa:''' depi ou pibliye paj sa, ou dwèt fòse chajman, rafrechi paj an; ou mèt bliye kontni kach sistèm bwozè (navigatè entènèt ou an) kounye a pou ou kapab wè chanjman yo : '''Mozilla / Firefox / Konqueror / Safari :''' mentni touch ''lèt kapital'' ak klike sou bouton ''Rafrechi/Aktyalize'' oubyen peze ''Maj-Ctrl-R'' (''Maj-Cmd-R'' sou sistèm Apple Mac) ; '''Internet Explorer / Opera :''' mentni touch ''Ctrl'' pandan ou ap prese bouton ''Rafrechi/Aktyalize'' oubyen peze ''Ctrl-F5''.",
'usercssjsyoucanpreview'           => "'''Bagay ki ap sèvi w :''' Itilize bouton « Voye kout zye » pou teste nouvo fèy CSS/JS anvan ou anrejistre l.",
'usercsspreview'                   => "'''Sonje ke ou ap voye yon kout zye sou sa w ekri nan fèy CSS sa.'''
''Li poko anrejistre !'''",
'userjspreview'                    => "'''Sonje ke ou ap voye kout zye sou fèy JavaScript ou ekri an, li poko anrejistre !'''",
'userinvalidcssjstitle'            => "'''Pòte atnasyon :''' estil \"\$1\" sa pa egziste. Raple ou ke paj pèsonèl ou yo ak ekstansyon .css epi .js ap itilize tit/sijè nan lèt miniskil, pa egzanp  {{ns:user}}:Foo/monobook.css se pa {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Li gen dènye vèsyon sou li)',
'note'                             => "'''Nòt :'''",
'previewnote'                      => "'''Atansyon, tèks sa a se yon previzyalizasyon, li poko anrejistre !'''",
'previewconflict'                  => 'Kout zye sa ap montre tèks ki nan bwat anwo pou ou wè modifikasyon ou an jan l ap parèt lè li ke pibliye.',
'session_fail_preview'             => "'''Ekskize nou ! Nou pa kapab anrejistre modifikasyon ou an paske nou sanble pèdi kèk enfòmasyon koneksyon sou kont ou an, sou sesyon ou an. Eseye yon fwa ankò. Si li pa mache, dekonekte ou, apre ou ap konekte ou ankò.'''",
'session_fail_preview_html'        => "'''Eskize nou ! Nou pa kapab anrejistre modifikasyon ou an paske nou pèdi yon pati nan enfomasyon sou sesyon ou an.'''

''HTML san foma, jan l ye a aktive nan wiki sa {{SITENAME}} , bouton pou gade sa lap bay an kache pou anpeche atak pa JavaScript.''

'''Si ou panse ke modifikasyon ou an bon toutbon, ou mèt eseye anko. Si sistèm an pa aksepte l fwa la s, dekonekte w, rekonekte w anko.'''",
'token_suffix_mismatch'            => "'''Modifikasyon ou fè an sanble pa aksepte paske navigatè, bwozè ou a melanje karaktè ponktyasyon yo nan idantifyan pou tèks sa. Modofikasyon pa aksepte pou li pa jenere kèk pwoblèm nan tèks ki te nan paj an. Pwoblèm sa kapab rive lè ou ap pase pa yon sèvè pwoksi (proxy).'''",
'editing'                          => 'Modifikasyon pou $1',
'editingsection'                   => 'Modifikasyon pou $1 (seksyon)',
'editingcomment'                   => 'Modifikasyon pou $1 (komantè)',
'editconflict'                     => 'Batay ant modifikasyon : $1',
'explainconflict'                  => '<b>Paj sa te anrejistre apre ou te komanse modifye l.Zòn modifikasyon an nan pati ki sitye anwo, jan l anrejistre nan sistèm nou an kounye a. Modifikasyon ou pòte yo ap parèt anba. Ou dwèt fè modifikasyon ou fè yo nan tèks ki te deja anrejistre a. Sèl tèks ki nan pati siperyè a, anwo ke ap anrejistre toutbon.</b><br />',
'yourtext'                         => 'Tèks ou an',
'storedversion'                    => 'Vèsyon ki anrejistre',
'yourdiff'                         => 'Diferans',
'copyrightwarning'                 => "Souple, raple ou ke tout piblikasyon ki fèt nan {{SITENAME}} piblye anba kontra $2 an (wè $1 pou konnen plis). Si ou pa ta vle ke sa ou ekri pataje oubyen  modifye, ou pa dwèt soumèt yo isit.<br />
Ou ap pwomèt tou ke sa ou ap ekri a se ou menm menm ki ekri li oubyen ke ou kopye li de yon sous ki nan domèn piblik, ou byen you sous ki lib. '''PA ITILIZE TRAVAY MOUN KI PA BAY OTORIZASYON PA LI TOUTBON !'''",
'longpagewarning'                  => "'''AVÈTISMAN : paj sa a genyen yon gwosè ki pase $1 Kio ;
Kèk bwozè (firefox,ie,opera,safari...) pa kapab afiche byen byen modifikasyon ki parèt nan paj ki genyen plis oubyen près 32 Kio. Oumèt dekoupe paj an nan 2 mòso oubyen ak seksyon pli piti.'''",
'longpageerror'                    => "'''ERÈ : Tèks ou anrejistre a ap fè $1 Ko, tay sa a depase kapasite limit nou kapab aksepte kounye a: $2 Ko. Nou pa kapab anrejistre tèks sa. Eseye ritounen nan paj ou te ye anvan pou kopye modifikasyon ou yo.'''",
'protectedpagewarning'             => "'''Pote atansyon : paj sa a pwoteje.
Sèl itilizatè yo ki genyen estati administratè kapab modifye l.'''",
'templatesused'                    => 'Modèl ki itilize nan paj sa a :',
'templatesusedpreview'             => 'Modèl ki itilize nan kout zye sa a (previzyalizasyon):',
'templatesusedsection'             => 'Modèl yo ki itilize nan seksyon sa :',
'template-protected'               => '(pwoteje)',
'template-semiprotected'           => '(semi-pwoteje)',
'hiddencategories'                 => 'Paj sa ap fè pati {{PLURAL:$1|Kategori kache|Kategori yo ki kache}} :',
'nocreatetitle'                    => 'Kreyasyon paj yo limite',
'nocreatetext'                     => 'Pajwèb sa a anpeche kreyasyon nouvo paj sou li. Ou mèt ritounen nan brozè ou epi modifye yon paj ki deja egziste oubyen  [[Special:UserLogin|konekte ou oubyen kreye yon kont]].',
'nocreate-loggedin'                => 'Ou gen lè pa gen pèmisyon pou ou kapab kreye paj nan wiki sa.',
'permissionserrors'                => 'Erè nan pèmisyon yo',
'permissionserrorstext'            => 'Ou pa gen otorizasyon pou fè operasyon ke ou mande a pou {{PLURAL:$1|rezon sa|rezon sa yo}} :',
'permissionserrorstext-withaction' => 'Ou pa otorize pou $2, pou {{PLURAL:$1|rezon sa|rezon sa yo}} :',
'recreate-moveddeleted-warn'       => "'''Atansyon : ou ap kreye yon pak ki te efase deja.'''

Mande ou byen si ou ap byen fè kreye li ankò toutbon (gade jounal paj sa a pou konnene poukisa efasman yo te fèt anba) :s :",
'moveddeleted-notice'              => 'Paj sa efase.
Sistèm an ap montre, anba paj sa, jounal tout vèsyon paj an ki efase pou sèvi tankou referans.',

# Parser/template warnings
'post-expand-template-inclusion-category' => 'Paj yo ki genyen twop modèl anndan yo',

# Account creation failure
'cantcreateaccounttitle' => 'Ou pa kapab kreye yon kont.',

# History pages
'viewpagelogs'           => 'gade jounal paj sa a',
'nohistory'              => 'Istorik pou paj sa pa egziste ditou.',
'currentrev'             => 'Vèsyon kounye a',
'currentrev-asof'        => 'Vèsyon kounye a ki date de $1',
'revisionasof'           => 'Vèsyon jou $1',
'revision-info'          => 'Vèsyon pou $1 pa $2',
'previousrevision'       => '← Vèsyon presedan',
'nextrevision'           => 'Vèsyon swivan →',
'currentrevisionlink'    => 'Vèsyon kounye a',
'cur'                    => 'kounye a',
'next'                   => 'pli douvan',
'last'                   => 'dènye',
'page_first'             => 'premye',
'page_last'              => 'dènye',
'histlegend'             => 'Lejand : ({{MediaWiki:Cur}}) = diferans ak vèsyon kounye a, ({{MediaWiki:Last}}) = diferans ak vèsyon anvan, <b>m</b> = modifikasyon ki pa enpòtan',
'history-fieldset-title' => 'Navige nan istorik paj sa',
'histfirst'              => 'Premye kontribisyon yo',
'histlast'               => 'Dènye kontribisyon yo',
'historysize'            => '({{PLURAL:$1|$1 okte|$1 okte yo}})',
'historyempty'           => '(vid, pa gen anyen)',

# Revision feed
'history-feed-title'          => 'Istorik vèsyon yo',
'history-feed-description'    => 'Istorik pou paj sa anlè wiki a',
'history-feed-item-nocomment' => '$1, lè li te ye $2',

# Revision deletion
'rev-deleted-comment'       => '(komantè efase)',
'rev-deleted-user'          => '(non itilizatè efase)',
'rev-deleted-event'         => '(antre, sijè sa efase)',
'rev-delundel'              => 'montre/kache',
'revisiondelete'            => 'Efase/Restore, remèt vèsyon sa',
'revdelete-nooldid-title'   => 'Pa genyen sib, destinasyon pou revizyon sa',
'revdelete-selected'        => "'''{{PLURAL:$2|Vèsyon ou seleksyone|Vèsyon ou seleksyone yo}} de $1 :'''",
'revdelete-legend'          => 'Mete restriksyon nan vizibilite yo :',
'revdelete-hide-text'       => 'Kache tèks anba vèsyon sa',
'revdelete-hide-name'       => 'Kache aksyon an ak sib li',
'revdelete-hide-comment'    => 'Kache komantè sou modifikasyon an',
'revdelete-hide-user'       => 'Kache idantifyan, non itilizatè oubyen adrès IP kontribitè an.',
'revdelete-hide-restricted' => 'Aplike restriksyon sa yo pou administratè yo epi lòt itilizatè yo',
'revdelete-suppress'        => 'Kache revizyon yo tou pou administratè yo',
'revdelete-hide-image'      => 'Kache kontni fichye a',
'revdelete-unsuppress'      => 'Anlve restriksyon yo sou vèsyon yo ki restore',
'revdelete-log'             => 'Komantè pou istorik paj sa :',
'revdelete-submit'          => 'Aplike sou vèsyon ki seleksyone a',
'revdelete-logentry'        => 'Vizibilite pou vèsyon sa modifye pou [[$1]]',
'revdel-restore'            => 'Modifye, chanje vizibilite a',
'pagehist'                  => 'Istorik paj sa',
'deletedhist'               => 'Istorik sipresyon yo',
'revdelete-content'         => 'kontni',
'revdelete-summary'         => 'modifye somè a',
'revdelete-uname'           => 'non itilizatè',
'revdelete-restricted'      => 'aplike restriksyon sa yo pou administratè yo',
'revdelete-hid'             => 'kache $1',
'revdelete-unhid'           => 'montre $1',

# Merge log
'revertmerge' => 'Separe',

# Diffs
'history-title'           => 'Istorik pou vèsyon « $1 » yo',
'difference'              => '(Diferans ant vèsyon yo)',
'lineno'                  => 'Liy $1 :',
'compareselectedversions' => 'Konpare vèsyon ki seleksyone yo',
'editundo'                => 'Defè, anile',
'diff-multi'              => '({{PLURAL:$1|Yon revizyon nan mitan evolisyon ki kache|$1 revizyon yo nan mitan evolisyon ki kache}})',

# Search results
'searchresults'             => 'Rezilta yo pou rechèch an',
'searchresults-title'       => 'Rezilta rechèch yo pou « $1 »',
'searchresulttext'          => 'Pou oukapab konenn plis sou rechèch nan {{SITENAME}}, gade [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => "Ou chache « '''[[:$1]]''' » ([[Special:Prefixindex/$1|tout paj yo ki komanse pa« $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tout paj yo ki genyen lyen vè « $1 »]])",
'searchsubtitleinvalid'     => "Ou chache « '''$1''' »",
'noexactmatch'              => "'''Pa genyen pyès paj ki genyen non sa a « $1 ».''' Ou mèt [[:$1|kreye atik sa a]].",
'noexactmatch-nocreate'     => "'''Pa genyen pyès paj ki rele « $1 ».'''",
'notitlematches'            => 'Pa gen paj nan sistèm ki genyen tit ou bay nan rechèch ou an.',
'notextmatches'             => 'Pa genyen pyès tèks nan paj yo ki ap koresponn ak rechèch ou fè a',
'prevn'                     => '$1 anvan yo',
'nextn'                     => '$1 swivan yo',
'viewprevnext'              => 'Wè ($1) ($2) ($3).',
'searchhelp-url'            => 'Help:Èd',
'search-result-size'        => '$1 ({{PLURAL:$2| mo|$2 mo yo}})',
'search-redirect'           => '(redireksyon depi $1)',
'search-section'            => '(seksyon $1)',
'search-suggest'            => 'Eseye ak òtograf sa : $1',
'search-interwiki-caption'  => 'Pwojè frè, ki ansanm oubyen ki ap deplwaye ansanm',
'search-interwiki-default'  => 'Rezilta yo pou $1 :',
'search-interwiki-more'     => '(plis)',
'search-mwsuggest-enabled'  => 'ak sigjesyon, kèk lide',
'search-mwsuggest-disabled' => 'san lide, san endikasyon',
'showingresultstotal'       => "Ap montre {{PLURAL:$4|rezilta '''$1'''|rezilta '''$1 – $2'''}} anlè '''$3'''",
'nonefound'                 => "'''Remak''' : sèl kèk espas non chache nan sityasyon nòmal.
Eseye pou wè si ak prefiks ''all:'' pou chache nan tout kontni a (anndann nou ap twouve paj diskisyon yo, modèl yo, etc.) oubyen itilize espas non ou ta renmen pou prefiks.",
'powersearch'               => 'Fouye fon',
'powersearch-legend'        => 'Rechèch avanse',
'powersearch-ns'            => 'Chache nan espas non sa yo:',
'powersearch-redir'         => 'Montre redireksyon yo',
'powersearch-field'         => 'Chache',

# Preferences page
'preferences'               => 'Preferans yo',
'mypreferences'             => 'Preferans yo',
'skin-preview'              => 'Voye kout zye',
'youremail'                 => 'Adrès imèl :',
'username'                  => 'Non itilizatè a:',
'uid'                       => 'Nimewo ID itilizatè a:',
'prefs-memberingroups'      => 'Manm {{PLURAL:$1|nan gwoup sa|nan gwoup sa yo }} :',
'yourrealname'              => 'Vre non ou:',
'yourlanguage'              => 'Langaj:',
'yournick'                  => 'Siyati pou espas diskisyon :',
'badsig'                    => 'Premye siyati ou an pa bon; tcheke l nan baliz HTML ou yo.',
'badsiglength'              => 'Siyati ou an two long monchè oubyen machè: pi gwo longè li kapab genyen se $1 karaktè{{PLURAL:$1||}}.',
'gender-male'               => 'Maskilen',
'email'                     => 'Imèl',
'prefs-help-realname'       => '(pa enpòtan) : si ou mete li, li ke posib pou nou ba ou rekonpans pou kèk kontrisyon ou yo.',
'prefs-help-email'          => 'Adrès imèl pa nesesè, men li ap pèmèt lòt itilizatè yo kontakte w pa imèl (lyen an nan paj itilizatè ou yo); moun sa a pa kapab wè imèl ou an. Imèl sa sèvi tou pou voye mopas ou an lè li rive ou bliye l.',
'prefs-help-email-required' => 'Nou bezwen ou bay yon adrès imèl. Souple, chache yonn.',

# Groups
'group-sysop' => 'Administratè yo',

'grouppage-sysop' => '{{ns:project}}:Administratè',

# User rights log
'rightslog' => 'Istorik modifikasyon estati itilizatè yo',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'modifye paj sa',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|modifikasyon|modifikasyon}}',
'recentchanges'                  => 'Modifikasyon yo ki fèk fèt',
'recentchanges-legend'           => 'Opsyon pou modifikasyon ki fèk fèt',
'recentchanges-feed-description' => 'Swvi dènye modifikasyon pou wiki sa a nan flo sa a (RSS,Atom...)',
'rcnote'                         => "Men {{PLURAL:$1|dènye modifikasyon an|dènye '''$1''' modifikasyon yo}} depi {{PLURAL:$2|dènye jou a|<b>$2</b> dènye jou yo}}, tankou $5,$4.",
'rcnotefrom'                     => "Men modifikasyon yo ki fèt depi '''$2''' ('''$1''' dènye).",
'rclistfrom'                     => 'Afiche nouvo modifikasyon yo depi $1.',
'rcshowhideminor'                => '$1 modifiksayon yo ki pa enpòtan',
'rcshowhidebots'                 => '$1 wobo',
'rcshowhideliu'                  => '$1 itilizatè ki anrejistre',
'rcshowhideanons'                => '$1 itilizatè anonim',
'rcshowhidepatr'                 => '$1 edisyon ki ap veye',
'rcshowhidemine'                 => '$1 kontribisyon mwen yo',
'rclinks'                        => 'Afiche dènye $1 modifikasyon ki fèt nan $2 dènye jou sa yo<br />$3.',
'diff'                           => 'diferans',
'hist'                           => 'istorik',
'hide'                           => 'Kache',
'show'                           => 'afiche',
'minoreditletter'                => 'm',
'newpageletter'                  => 'N',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Wè detay yo (li nesesite ou genyen JavaScript)',
'rc-enhanced-hide'               => 'Kache detay yo',

# Recent changes linked
'recentchangeslinked'          => 'Swivi pou lyen yo',
'recentchangeslinked-title'    => 'Chanjman ki an relasyon ak "$1"',
'recentchangeslinked-noresult' => 'Pa genyen pyès chanjman nan paj sa yo pou peryòd ou chwazi an.e.',
'recentchangeslinked-summary'  => "Paj espesyal sa a ap montre dènye chanjman nan paj ki genyen lyen sou yo. Paj yo ki nan [[Special:Watchlist|lis swivi]] ou an ap ekri '''fonse'''",
'recentchangeslinked-page'     => 'Non paj an :',
'recentchangeslinked-to'       => 'Afiche modifikasyon yo ki genyen yon lyen vè paj yo ba ou a plito',

# Upload
'upload'        => 'Chaje yon fichye',
'uploadbtn'     => 'Chaje fichye a',
'uploadlogpage' => 'Istorik chajman pou fichye miltimedya',
'uploadedimage' => 'chaje « [[$1]] »',

# Special:ListFiles
'listfiles' => 'Lis fichye yo',

# File description page
'file-anchor-link'          => 'Fichye',
'filehist'                  => 'Istorik fichye a',
'filehist-help'             => 'Klike anlè yon dat epi yon lè pou fichye a jan li te ye nan moman sa a.',
'filehist-current'          => 'Kounye a',
'filehist-datetime'         => 'Dat ak lè',
'filehist-thumb'            => 'Minyati',
'filehist-thumbtext'        => 'Imajèt pou vèsyon $1',
'filehist-user'             => 'Itilizatè',
'filehist-dimensions'       => 'Grandè yo',
'filehist-filesize'         => 'Lajè fichye a',
'filehist-comment'          => 'Komantè',
'imagelinks'                => 'Lyen nan paj an',
'linkstoimage'              => '{{PLURAL:$1|Paj ki ap swiv an|Paj yo ki ap swiv}} genyen imaj sa a :',
'nolinkstoimage'            => 'Pyès paj pa genyen imaj sa a.',
'sharedupload'              => 'Fichye sa a kapab pataje, li sòti depi $1 e li kapab itilize pa lòt pwojè yo.',
'uploadnewversion-linktext' => 'Kopye yon nouvo vèsyon pou fichye sa a',

# MIME search
'mimesearch' => 'Chache ak tip MIME',

# List redirects
'listredirects' => 'Lis tout redireksyon yo',

# Unused templates
'unusedtemplates' => 'Modèl yo ki pa itilize',

# Random page
'randompage' => 'Yon paj o aza',

# Random redirect
'randomredirect' => 'Yon paj redireksyon o aza',

# Statistics
'statistics' => 'Estatistik',

'disambiguations' => 'Paj yo ki genyen menm non',

'doubleredirects' => 'Redireksyon de fwa',

'brokenredirects' => 'redireksyon ki pa mache yo',

'withoutinterwiki' => 'Paj yo ki pa genyen lyen ak lòt wiki nan lòt lang yo',

'fewestrevisions' => 'Paj yo ki pa genyen anpil modifikasyon menm',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|okte|okte}}',
'nlinks'                  => '$1 {{PLURAL:$1|lyen|lyen}}',
'nmembers'                => '$1 {{PLURAL:$1|manm|manm yo}} andidan',
'lonelypages'             => 'Paj ki pa genyen manman',
'uncategorizedpages'      => 'Paj yo ki pa genyen kategori',
'uncategorizedcategories' => 'Kategori yo ki pa genyen tit/kategori',
'uncategorizedimages'     => 'Dosye ki pa kategorize',
'uncategorizedtemplates'  => 'Modèl yo ki pa genyen kategori',
'unusedcategories'        => 'Kategori yo ki pa itilize',
'unusedimages'            => 'Imaj yo ki pa genyen manman',
'wantedcategories'        => 'Kategori yo ke moun mande plis',
'wantedpages'             => 'Paj yo ki plis mande',
'mostlinked'              => 'Paj yo ki genyen plis lyen nan lòt paj yo',
'mostlinkedcategories'    => 'Kategori yo ki plis itilize',
'mostlinkedtemplates'     => 'Modèl yo ki plis itilize',
'mostcategories'          => 'Paj yo ki genyen plis kategori',
'mostimages'              => 'Plis mare avèk dosye',
'mostrevisions'           => 'Paj yo ki plis modifye',
'prefixindex'             => 'Tout paj yo ak prefiks',
'shortpages'              => 'Paj kout yo',
'longpages'               => 'Paj ki long',
'deadendpages'            => 'Paj ki ap fini',
'protectedpages'          => 'Paj ki pwoteje yo',
'listusers'               => 'Lis kontribitè yo',
'newpages'                => 'Nouvo paj yo',
'ancientpages'            => 'Atik ki pli vye yo',
'move'                    => 'Renonmen',
'movethispage'            => 'Renonmen paj an (bay yon lòt non)',
'pager-newer-n'           => '{{PLURAL:$1|ki fèk fèt|$1 ki fèk fèt yo}}',
'pager-older-n'           => '{{PLURAL:$1|pi vye|$1 pi vye yo}}',

# Book sources
'booksources'               => 'Ouvraj referans yo',
'booksources-search-legend' => 'Chache nan lis ouvraj ki sèvi pou referans',
'booksources-go'            => 'Liste',

# Special:Log
'specialloguserlabel'  => 'itilizatè :',
'speciallogtitlelabel' => 'Tit :',
'log'                  => 'Jounal yo',
'all-logs-page'        => 'Tout jounal yo (istorik yo)',

# Special:AllPages
'allpages'       => 'Tout paj yo',
'alphaindexline' => '$1 jiska $2',
'nextpage'       => 'Paj swivan ($1)',
'prevpage'       => 'Paj anvan ($1)',
'allpagesfrom'   => 'Afiche paj yo depi :',
'allpagesto'     => 'Montre paj yo jiska :',
'allarticles'    => 'tout atik yo',
'allpagessubmit' => 'Ale',
'allpagesprefix' => 'Montre paj yo ki ap komanse pa prefiks sa a :',

# Special:Categories
'categories'                    => 'Kategori yo',
'categoriespagetext'            => 'Kategori ki swiv yo gen lòt paj oubien medya nan yo.',
'special-categories-sort-count' => 'klase pa valè',
'special-categories-sort-abc'   => 'klase alfabetikalman',

# Special:LinkSearch
'linksearch' => 'Lyen pou ale pli lwen',

# Special:Log/newusers
'newuserlogpage'              => 'Istorik pou kreyasyon kont itilizatè yo',
'newuserlogpagetext'          => 'Men jounal, istorik kreyasyon kont itilizatè yo.',
'newuserlog-byemail'          => 'mopas an voye pa imèl',
'newuserlog-create-entry'     => 'Nouvo itilizatè',
'newuserlog-create2-entry'    => 'kont kreye pou $1',
'newuserlog-autocreate-entry' => 'Kont sa kreye otomatikman',

# Special:ListGroupRights
'listgrouprights-members' => '(lis manm yo)',

# E-mail user
'emailuser' => 'Voye yon mesaj (imèl) pou itilizatè sa a',

# Watchlist
'watchlist'         => 'Lis swivi',
'mywatchlist'       => 'Lis swivi',
'watchlistfor'      => "(pou itilizatè '''$1''')",
'addedwatch'        => 'Ajoute nan lis swivi',
'addedwatchtext'    => 'Paj « <nowiki>$1</nowiki> » an byen ajoute nan [[Special:Watchlist|lis swivi ou an]].
Pwochen modifikasyon nan paj sa a ke make na lis swivi ou an, paj an ke parèt <b>fonse </b> nan [[Special:RecentChanges|chanjman ki fèk fèt]] pou ou kapab wè yo pli fasilman.',
'removedwatch'      => 'Retire nan lis swivi',
'removedwatchtext'  => 'Paj "[[:$1]]" byen retire nan [[Special:Watchlist|lis swivi ou an]].',
'watch'             => 'Swiv',
'watchthispage'     => 'Swiv paj sa a',
'unwatch'           => 'Pa swiv ankò',
'watchlist-details' => 'Ou ap swiv {{PLURAL:$1|paj|paj}}, san konte paj diskisyon yo.',
'wlshowlast'        => 'Montre dènye $1 zè yo, dènye $2 jou yo, oubyen $3.',
'watchlist-options' => 'Opsyon pou lis swivi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Swiv...',
'unwatching' => 'Fini swiv paj sa a...',

# Delete
'deletepage'            => 'Efase yon paj',
'historywarning'        => 'Atansyon, paj ou ap efase an genyen yon istorik :',
'confirmdeletetext'     => 'Ou ap efase pou tout bon nan bazdone a yon paj oubyen yon imaj epi tout vèsyon li yo. Souple, konfime aksyon enpòtan sa a, ke ou konprann sa ou ap fè, nan dwa ak [[{{MediaWiki:Policy-url}}|lwa medyawiki a]].',
'actioncomplete'        => 'Aksyon an fèt',
'deletedtext'           => '« <nowiki>$1</nowiki> » efase.
Gade $2 pou wè yon lis efasman resan.',
'deletedarticle'        => 'efase « [[$1]] »',
'dellogpage'            => 'Istorik efasman yo',
'deletecomment'         => 'Rezon pou kilès la ou efase :',
'deleteotherreason'     => 'Rezon an plis :',
'deletereasonotherlist' => 'Lòt rezon',

# Rollback
'rollbacklink' => 'anlve',

# Protect
'protectlogpage'              => 'Istorik pwoteksyon yo',
'protectedarticle'            => 'pwoteje « [[$1]] »',
'modifiedarticleprotection'   => 'modifye nivo pwoteksyon « [[$1]] »',
'prot_1movedto2'              => '[[$1]] renonmen, li kounye a [[$2]]',
'protect-legend'              => 'Konfime pwoteksyon an',
'protectcomment'              => 'Poukisa ou pwoteje li:',
'protectexpiry'               => 'Ekspirasyon(Paj an pe ke ekspire si ou pa mete anyen)',
'protect_expiry_invalid'      => 'Dat ou mete a pou li ekspire pa bon',
'protect_expiry_old'          => 'dat ekspirasyon an ja pase;',
'protect-unchain'             => 'Debloàke pèmisyon yo pou renonmen, deplase',
'protect-text'                => "Ou mèt konsilte epi modifye nivo pwoteksyon paj sa a '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Ou pa genyen dwa ki ap pèmèt ou modifye pwoteksyon paj sa a.
Men reglaj pou paj '''$1''' an kounye a:",
'protect-cascadeon'           => 'paj sa a pwoteje kounye a paske li nan {{PLURAL:$1|paj swivan|paj swivan yo}}, {{PLURAL:$1|ki li menm menm te pwoteje|ki yo menm menm te pwoteje}} epi opsyon pwoteksyon "enbrike" aktif. Ou mèt chanje nivo pwoteksyon paj sa a san ke li modifye pwoteksyon enbrike an.',
'protect-default'             => 'Otorize tout itilizatè yo',
'protect-fallback'            => 'Li bezwen pèmisyon "$1"',
'protect-level-autoconfirmed' => 'Bloke itilizatè nouvo yo ak sa ki pa anrejistre nan sistèm an',
'protect-level-sysop'         => 'Administratè sèlman',
'protect-summary-cascade'     => 'pwoteksyon enbrike',
'protect-expiring'            => 'ap ekspire $1',
'protect-cascade'             => 'Pwoteksyon enbrike - ap pwoteje tout paj ki andidan paj sa a.',
'protect-cantedit'            => 'Ou pa kapab modifye nivo pwoteksyon paj sa a paske ou pa gen dwa pou edite li.',
'protect-expiry-options'      => '2 zè:2 hours,1 jou:1 day,3 jou:3 days,1 semèn:1 week,2 semèn:2 weeks,1 mwa:1 month,3 mwa:3 months,6 mwa:6 months,1 lane:1 year,ki pap janm fini:infinite',
'restriction-type'            => 'Pèmisyon:',
'restriction-level'           => 'Nivo kontrent, restriksyon:',

# Undelete
'undeletebtn'      => 'Restore',
'undeletelink'     => 'gade/retabli',
'undeletedarticle' => 'retabli « [[$1]] »',

# Namespace form on various pages
'namespace'      => 'Espas non :',
'invert'         => 'Envèse seleksyon an',
'blanknamespace' => '(Prensipal)',

# Contributions
'contributions'       => 'Kontribisyon itilizatè sa a',
'contributions-title' => 'Lis tout kontribisyon itilizatè ki rele $1',
'mycontris'           => 'Kontribisyon mwen yo',
'contribsub2'         => 'Lis kontribisyon $1 ($2). Paj yo ki te efase pa afiche non.',
'uctop'               => '(komansman)',
'month'               => 'depi mwa (ak mwa anvan yo) :',
'year'                => 'Depi lane (ak anvan tou) :',

'sp-contributions-newbies'     => 'Montre sèlman kontribisyon nouvo itilizatè yo',
'sp-contributions-newbies-sub' => 'Lis kontribisyon pou nouvo itilizatè yo. Paj ki efase pe ke ap montre.',
'sp-contributions-blocklog'    => 'jounal blokaj yo',
'sp-contributions-talk'        => 'Diskite',
'sp-contributions-search'      => 'Chache kontribisyon yo',
'sp-contributions-username'    => 'Adrès IP oubyen non itilizatè:',
'sp-contributions-submit'      => 'Chache',

# What links here
'whatlinkshere'            => 'Paj ki lye nan paj sa a',
'whatlinkshere-title'      => 'Paj ki genyen lyen ki ap mennen nan "$1"',
'whatlinkshere-page'       => 'Paj :',
'linkshere'                => 'Paj yo ki anba ap mene nan <b>[[:$1]]</b> :',
'nolinkshere'              => 'Pyès paj genyen lyen pou paj sa a <b>[[:$1]]</b>.',
'isredirect'               => 'Paj redireksyon',
'istemplate'               => 'andidan',
'isimage'                  => 'fichye ki gen relasyon',
'whatlinkshere-prev'       => '{{PLURAL:$1|anvant|$1 anvan yo}}',
'whatlinkshere-next'       => '{{PLURAL:$1|swivan|$1 swivan yo}}',
'whatlinkshere-links'      => '← lyen yo',
'whatlinkshere-hideredirs' => '$1 redireksyon yo',
'whatlinkshere-hidetrans'  => '$1 kontni anndan li',
'whatlinkshere-hidelinks'  => '$1 lyen yo',
'whatlinkshere-filters'    => 'Filtè yo',

# Block/unblock
'blockip'                  => 'Bloke yon adrès IP oubyen yon itilizatè',
'ipboptions'               => '2 zè:2 hours,1 jou:1 day,3 jou:3 days,1 semèn:1 week,2 semèn:2 weeks,1 mwa:1 month,3 mwa:3 months,6 mwa:6 months,1 lane:1 year,ki pap janm fini:infinite',
'ipblocklist'              => 'Lis IP itilizatè yo ki bloke',
'blocklink'                => 'Bloke',
'unblocklink'              => 'Debloke',
'change-blocklink'         => 'Modifye blokaj an',
'contribslink'             => 'Kontribisyon yo',
'blocklogpage'             => 'Istorik blokaj yo',
'blocklogentry'            => 'bloke « [[$1]] » - dire : $2 $3',
'unblocklogentry'          => 'debloke $1',
'block-log-flags-nocreate' => 'kreyasyon kont sa pa otorize',

# Move page
'move-page-legend' => 'Renonmen yon paj',
'movepagetext'     => "Itilize fòmilè a pou renonmen yon paj, li ap deplase tout istorik li nan nouvo non an.
Tit anvan ke yon paj redireksyon pou ale nan nouvo paj an. Lyen vè tit anvan an pe ke chanje;
souple, gade byen ke deplasman sa a pa kreye yon redireksyon de fwa.
Ou dwèt asire ou ke lyen yo korèk, ke yo genyen yon bon destinasyon sou yo nan [[Special:DoubleRedirects|de fwa]] oubyen [[Special:BrokenRedirects|redireksyon ki pa bon]]..

Yon paj pe ke deplase si nouvo paj an egziste deja, eksepte si paj sa vid ou byen ke li menm se yon lèot redireksyon (fo pa li genyen yon istorik na li tou).

'''Pòte Atansyon !'''
Sa ou ap fè a kapab pwovoke yon gwo chanjman nan òganizasyon lòt paj yo.
Byen gade ke ou mezire tout konsekans aksyon ke ou ap fè a.",
'movepagetalktext' => 'Paj diskisyon ki asosye, si li egziste, ke otomatikman renonmen tou, <b>eksepte: </b>
*Ou ap renonmen yon paj nan direksyon yon lòt espas
*yon paj diskisyon ja egziste ak nouvo non an
*Ou pa seleksyone bouton ki sitye anlè mesaj sa a

Nan pozisyon sa a, ou ke dwèt renonmen oubyen fizyone ou menm menm paj an si ou vle.',
'movearticle'      => 'Deplase, renonmen paj an :',
'newtitle'         => 'Nouvo tit, non:',
'move-watch'       => 'Swiv paj sa a',
'movepagebtn'      => 'Deplase paj an',
'pagemovedsub'     => 'Deplasman an fèt',
'movepage-moved'   => '<big>\'\'\'"$1" deplase nan "$2" alè kile\'\'\'</big>',
'articleexists'    => 'Nou ja genyen yon atik ak non sa a oubyen non ke ou chwazi an pa valab ankò. Chwazi yon lòt.',
'talkexists'       => 'Paj an men byen deplase. Mè paj diskisyon an pa deplase paske li te ja egziste yonn anlè nouvo paj an. Souple, fizyone de diskisyon sa yo, mete yo ansanmm anlè nouvo paj an.',
'movedto'          => 'deplase nan',
'movetalk'         => 'Renonmen ak deplase paj diskisyon an tou',
'1movedto2'        => '[[$1]] renonmen, li kounye a [[$2]]',
'1movedto2_redir'  => 'deplase [[$1]] vè [[$2]], li ekraze nan menm tan sa, redireksyon li',
'movelogpage'      => 'Istorik renomaj yo',
'movereason'       => 'Poukisa nou deplase li :',
'revertmove'       => 'anile',

# Export
'export' => 'Ekspòte paj yo',

# Namespace 8 related
'allmessages' => 'Lis mesaj sistèm yo',

# Thumbnails
'thumbnail-more'  => 'Agrandi',
'thumbnail_error' => 'Erè nan kreyasyon minyati : $1',

# Import log
'importlogpage' => 'Istorik chajman pou paj sa a',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Paj itilizatè ou an',
'tooltip-pt-mytalk'               => 'Paj diskisyon ou an',
'tooltip-pt-preferences'          => 'Preferans mwen yo',
'tooltip-pt-watchlist'            => 'Lis paj ou ap swiv yo',
'tooltip-pt-mycontris'            => 'Lis kontribisyon ou yo',
'tooltip-pt-login'                => 'Ou mèt idantifye ou mè tande ke li pa nesesè.',
'tooltip-pt-logout'               => 'Dekonekte ou',
'tooltip-ca-talk'                 => 'Diskisyon apwopo kontni paj sa a',
'tooltip-ca-edit'                 => 'Ou mèt modifye paj sa a. Souple, previzyalize mesaj ou an anvan ou anrejistre li.',
'tooltip-ca-addsection'           => 'Komanse yon nouvo seksyon',
'tooltip-ca-viewsource'           => 'Paj sa a pwoteje. Ou kapab wè kòd sous li.',
'tooltip-ca-history'              => 'Vèsyon ki ansyen pou paj sa (ak tout kontribitè ki te travay sou li)',
'tooltip-ca-protect'              => 'Pwoteje paj sa a',
'tooltip-ca-delete'               => 'Efase paj sa a',
'tooltip-ca-move'                 => 'Renonmen paj sa a',
'tooltip-ca-watch'                => 'Ajoute paj sa a nan lis swivi ou a',
'tooltip-ca-unwatch'              => 'Retire paj sa a nan lis swivi ou an',
'tooltip-search'                  => 'Fouye nan wiki sa a',
'tooltip-search-go'               => 'Ale sou yon paj ki pòte egzateman non sa si li egziste',
'tooltip-search-fulltext'         => 'Chache paj yo ki genyen tèks sa.',
'tooltip-n-mainpage'              => 'Vizite paj prensipal an',
'tooltip-n-portal'                => 'Apwopo pwojè a, sa ou kapab fè, ki kote ou mèt twouve kèk bagay',
'tooltip-n-currentevents'         => 'Twouve enfòmasyon yo anlè evènman ki ap fèt kounye a',
'tooltip-n-recentchanges'         => 'Lis modifikasyon ki fèk fèt nan wiki a',
'tooltip-n-randompage'            => 'Afiche yon paj o aza',
'tooltip-n-help'                  => 'Èd',
'tooltip-t-whatlinkshere'         => 'Lis paj yo ki lye ak paj sa a',
'tooltip-t-recentchangeslinked'   => 'Lis modifikasyon ki fèk fèt pou paj yo ki genyen relasyon ak paj sa',
'tooltip-feed-rss'                => 'Flow RSS pou paj sa',
'tooltip-feed-atom'               => 'Flow Atom pou paj sa',
'tooltip-t-contributions'         => 'Wè lis kontribisyon itilizatè sa a',
'tooltip-t-emailuser'             => 'Voye yon imèl pou itilizatè sa a',
'tooltip-t-upload'                => 'Chaje dosye',
'tooltip-t-specialpages'          => 'Lis tout paj espesyal yo',
'tooltip-t-print'                 => 'Vèsyon ou kapab enprime pou paj sa',
'tooltip-t-permalink'             => 'Lyen pèmanan ki ap mennen ou nan vèsyon sa pou paj sa',
'tooltip-ca-nstab-main'           => 'Wè paj kontni sa',
'tooltip-ca-nstab-user'           => 'Wè paj itilizatè',
'tooltip-ca-nstab-special'        => 'Paj sa se yon paj espesyal, ou pa kapab modifye li.',
'tooltip-ca-nstab-project'        => 'Wè paj pwojè a',
'tooltip-ca-nstab-image'          => 'Gade paj dokiman sa',
'tooltip-ca-nstab-template'       => 'Wè modèl an',
'tooltip-ca-nstab-help'           => 'Wè paj èd an',
'tooltip-ca-nstab-category'       => 'Wè paj kategori a',
'tooltip-minoredit'               => 'make modifikasyon sa a pa enpòtan',
'tooltip-save'                    => 'Sove modifikasyon ou yo',
'tooltip-preview'                 => 'Souple, gade paj ou an (previzyalize li) anvan ou anrejistre li',
'tooltip-diff'                    => 'Montre ki chanjman ou fè nan tèks an.',
'tooltip-compareselectedversions' => 'Afiche diferans ant de vèsyon paj sa a ou seleksyone.',
'tooltip-watch'                   => 'Ajoute paj sa a nan lis swivi ou an',
'tooltip-rollback'                => '« Revoke » ap anile modifikasyon dènyè kontribitè te fè sou paj sa',
'tooltip-undo'                    => '« retire » ap efase modifikasyon sar epi li ap ouvri fenèt modifikasyon an nan mòd ou kapab wè sa sa ou fè a ap bay.
Li ap pèmèt retabli vèsyon ki te anvan li epi ajoute yon rezon ki ap eksplike poukisa ou fè modifikasyon sa nan bwat rezime a.',

# Browsing diffs
'previousdiff' => '← Diferans anvan',
'nextdiff'     => 'Diferans apre →',

# Media information
'file-info-size'       => '($1 × $2 piksèl, lajè fichye a : $3, tip MIME li ye : $4)',
'file-nohires'         => '<small>Pa genyen rezolisyon ki pli wo ki disponib.</small>',
'svg-long-desc'        => '(Fichye SVG, rezolisyon pou de $1 × $2 piksèl, lajè : $3)',
'show-big-image'       => 'Imaj pli gwo, pli fin',
'show-big-image-thumb' => '<small>Lajè kout zye sa a : $1 × $2 piksèl</small>',

# Special:NewFiles
'newimages' => 'Galri pou nouvo fichye yo',

# Bad image list
'bad_image_list' => 'Fòmat la se konsa :

Se lis sèlman (lign ki kòmanse ak *) ki konsidere.  Premye lyen nan yon lign sipoze yon lyen pou yon move dosye.
Nenpòt lòt lyen nan menm lign nan konsidere kòm yon eksèpsyon, i.e. paj kote yon dosye ka parèt nan lign.',

# Metadata
'metadata'          => 'Metadone',
'metadata-help'     => 'Dosye sa genyen enfòmasyon adisyonèl, petèt adisyone soti nan yon kamera dijital oubyen yon eskennè itilize pou kreye oubyen dijitalize li.  Si dosye sa se yon modifikasyon, kèk detay ka pa menm avèk original la.',
'metadata-expand'   => 'Montre tout enfòmasyon',
'metadata-collapse' => 'Kache enfòmasyon ak tout detay sa yo',
'metadata-fields'   => 'Chan metadone sa yo pou EXIF ki liste nan mesaj sa a ke nan paj deskripsyon imaj an lè tab metadone a ke pli piti. Lòt chan yo ke ap kache.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Modifye fichye sa a epi yon aplikasyon pa ou (ki pa nan sistèm an, sou machin ou pa egzanp).',
'edit-externally-help' => '(Gade [http://www.mediawiki.org/wiki/Manual:External_editors komannd ak enstriksyon yo] pou plis enfòmasyon oubyen pou konnen plis)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tout',
'namespacesall' => 'Tout',
'monthsall'     => 'tout',

# Watchlist editing tools
'watchlisttools-view' => 'Lis swivi',
'watchlisttools-edit' => 'Wè epi modifye tou lis swivi',
'watchlisttools-raw'  => 'Modifye lis swivi (mòd bazik)',

# Special:Version
'version' => 'Vèsyon',

# Special:SpecialPages
'specialpages' => 'Paj espesyal yo',

);
