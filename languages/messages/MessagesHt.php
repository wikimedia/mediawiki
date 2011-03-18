<?php
/** Haitian (Kreyòl ayisyen)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Boukman
 * @author Internoob
 * @author Jvm
 * @author Masterches
 * @author Urhixidur
 */

$fallback = 'fr';

$namespaceNames = array(
	NS_MEDIA            => 'Medya',
	NS_SPECIAL          => 'Espesyal',
	NS_TALK             => 'Diskite',
	NS_USER             => 'Itilizatè',
	NS_USER_TALK        => 'Diskisyon_Itilizatè',
	NS_PROJECT_TALK     => 'Diskisyon_$1',
	NS_FILE             => 'Fichye',
	NS_FILE_TALK        => 'Diskisyon_Fichye',
	NS_MEDIAWIKI        => 'MedyaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskisyon_MedyaWiki',
	NS_TEMPLATE         => 'Modèl',
	NS_TEMPLATE_TALK    => 'Diskisyon_Modèl',
	NS_HELP             => 'Èd',
	NS_HELP_TALK        => 'Diskisyon_Èd',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Diskisyon_Kategori',
);

$namespaceAliases = array(
	'Imaj'           => NS_USER,
	'Diskisyon_Imaj' => NS_USER_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'RedireksyonDoub' ),
	'BrokenRedirects'           => array( 'RedireksyonKase' ),
	'Disambiguations'           => array( 'Tokay' ),
	'Userlogin'                 => array( 'Koneksyon' ),
	'Userlogout'                => array( 'Dekoneksyon' ),
	'CreateAccount'             => array( 'KreyeKont' ),
	'Preferences'               => array( 'Preferans' ),
	'Watchlist'                 => array( 'LisSwivi' ),
	'Recentchanges'             => array( 'ChanjmanResan' ),
	'Upload'                    => array( 'Chaje' ),
	'UploadStash'               => array( 'ChajePil' ),
	'Listfiles'                 => array( 'LisFichye', 'Lis_Fichye', 'LisImaj' ),
	'Newimages'                 => array( 'NouvoImaj' ),
	'Listusers'                 => array( 'LisItilizatè' ),
	'Listgrouprights'           => array( 'LisDwaGwoup' ),
	'Statistics'                => array( 'Estatistik' ),
	'Randompage'                => array( 'Oaza', 'PajOaza' ),
	'Lonelypages'               => array( 'PajPoukontli', 'PajOfelen' ),
	'Uncategorizedpages'        => array( 'PajPakategorize' ),
	'Uncategorizedcategories'   => array( 'KategoriPakategorize' ),
	'Uncategorizedimages'       => array( 'ImajPakategorize' ),
	'Uncategorizedtemplates'    => array( 'ModèlPakategorize' ),
	'Unusedcategories'          => array( 'KategoriPaItilize' ),
	'Unusedimages'              => array( 'FichyePaItilize', 'ImajPaItilize' ),
	'Wantedpages'               => array( 'PajNouBezwen', 'LyenKase' ),
	'Wantedcategories'          => array( 'KategoriNouBezwen' ),
	'Wantedfiles'               => array( 'FichyeNouBezwen' ),
	'Wantedtemplates'           => array( 'ModèlNouBezwen' ),
	'Mostlinked'                => array( 'PajPlisLye', 'PlisLye' ),
	'Mostlinkedcategories'      => array( 'KategoriPlisLye', 'KategoriPlisItilize' ),
	'Mostlinkedtemplates'       => array( 'ModèlPlisLye', 'ModèlPlisItilize' ),
	'Mostimages'                => array( 'ImajPlisLye', 'PlisFichye', 'PlisImaj' ),
	'Mostcategories'            => array( 'PlisKategori' ),
	'Mostrevisions'             => array( 'PlisRevizyon' ),
	'Fewestrevisions'           => array( 'MwensRevizyon' ),
	'Shortpages'                => array( 'PajKout' ),
	'Longpages'                 => array( 'PajLong' ),
	'Newpages'                  => array( 'PajNouvo' ),
	'Ancientpages'              => array( 'PajAnsyen' ),
	'Deadendpages'              => array( 'PajEnpas' ),
	'Protectedpages'            => array( 'PajPwoteje' ),
	'Protectedtitles'           => array( 'TitPwoteje' ),
	'Allpages'                  => array( 'ToutPaj' ),
	'Prefixindex'               => array( 'EndèksPrefiks' ),
	'BlockList'                 => array( 'LisBloke', 'LisIPBloke' ),
	'Unblock'                   => array( 'Debloke' ),
	'Specialpages'              => array( 'PajEspesyal' ),
	'Contributions'             => array( 'Kontribisyon', 'Kontrib' ),
	'Emailuser'                 => array( 'ImèlItilizatè' ),
	'Confirmemail'              => array( 'VerifyeImèl' ),
	'Whatlinkshere'             => array( 'SakLye' ),
	'Recentchangeslinked'       => array( 'LyenChanjmanResan', 'ChanjmanAk' ),
	'Movepage'                  => array( 'DeplasePaj' ),
	'Blockme'                   => array( 'BlokeM' ),
	'Booksources'               => array( 'SousLiv' ),
	'Categories'                => array( 'Kategori' ),
	'Export'                    => array( 'Ekspòte' ),
	'Version'                   => array( 'Vèsyon' ),
	'Allmessages'               => array( 'ToutMesaj' ),
	'Log'                       => array( 'Jounal' ),
	'Block'                     => array( 'Bloke', 'BlokeIP', 'BlokeItilizatè' ),
	'Undelete'                  => array( 'Restore' ),
	'Import'                    => array( 'Enpòte' ),
	'Lockdb'                    => array( 'KadnaseDB' ),
	'Unlockdb'                  => array( 'DekadnaseDB' ),
	'Userrights'                => array( 'DwaItilizatè', 'FèSysop', 'FèBot' ),
	'MIMEsearch'                => array( 'ChacheMIME' ),
	'FileDuplicateSearch'       => array( 'ChacheFichyeDoub' ),
	'Unwatchedpages'            => array( 'PajPaSiveye' ),
	'Listredirects'             => array( 'LisRedireksyon' ),
	'Revisiondelete'            => array( 'RevizyonSiprime' ),
	'Unusedtemplates'           => array( 'ModèlVyèj' ),
	'Randomredirect'            => array( 'RedireksyonOaza' ),
	'Mypage'                    => array( 'PajMwen' ),
	'Mytalk'                    => array( 'DiskisyonM' ),
	'Mycontributions'           => array( 'KontribisyonM' ),
	'Myuploads'                 => array( 'ChajmanM' ),
	'PermanentLink'             => array( 'LyenPouToutTan' ),
	'Listadmins'                => array( 'LisAdmin' ),
	'Listbots'                  => array( 'LisWobo' ),
	'Popularpages'              => array( 'PajPopilè' ),
	'Search'                    => array( 'Chache', 'Fouye' ),
	'Resetpass'                 => array( 'ChanjeMopas', 'ResètMopas' ),
	'Withoutinterwiki'          => array( 'SanEntèwiki' ),
	'MergeHistory'              => array( 'FizyoneIstwa' ),
	'Filepath'                  => array( 'ChemenFichye' ),
	'Invalidateemail'           => array( 'EnvalideImèl' ),
	'Blankpage'                 => array( 'PajVid' ),
	'LinkSearch'                => array( 'ChacheLyen' ),
	'DeletedContributions'      => array( 'SiprimeKontribisyon' ),
	'Tags'                      => array( 'Etikèt' ),
	'Activeusers'               => array( 'ItilizatèAktif' ),
	'RevisionMove'              => array( 'DeplaseRevizyon' ),
	'ComparePages'              => array( 'KonparePaj' ),
	'Badtitle'                  => array( 'MovèTit' ),
	'DisableAccount'            => array( 'DeaktiveKont' ),
);

$linkTrail = '/^([a-zàèòÀÈÒ]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Souliyen lyen yo :',
'tog-highlightbroken'         => 'Afiche <a href="" class="new">nan koulè wouj</a> lyen yo ki ap mene nan paj ki pa egziste (oubyen : tankou <a href="" class="internal">?</a>)',
'tog-justify'                 => 'Aliyen paragraf yo',
'tog-hideminor'               => 'Kache tout modifikasyon resan yo ki tou piti',
'tog-hidepatrolled'           => 'Kache modifikasyon yo ki fèk fèt pou moun ki ap veye yo',
'tog-newpageshidepatrolled'   => 'Kache paj ki siveye yo nan mitan lis nouvo paj yo',
'tog-extendwatchlist'         => 'Etann lis swivi pou ou kapab wè tout chanjman yo, pa sèlman sa ki fèk fèt yo',
'tog-usenewrc'                => 'Itilize modifikasyon ki fèk fèt yo ki alemye (sa mande JavaScript)',
'tog-numberheadings'          => 'Nimewote otomatikman tit yo',
'tog-showtoolbar'             => 'Montre meni modifikasyon an (sa mande JavaScript)',
'tog-editondblclick'          => 'Klike de fwa pou modifye yon paj (sa mande JavaScript)',
'tog-editsection'             => 'Pemèt modifye yon seksyon grasa lyen [modifye] yo',
'tog-editsectiononrightclick' => 'Pemèt modifye yon seksyon lè ou klike a dwat sou tit seksyon an (sa mande JavaScript)',
'tog-showtoc'                 => 'Montre tab de matyè yo (pou tout paj ki gen plis pase 3 tit)',
'tog-rememberpassword'        => 'Sonje mopas mwen nan òdinatè sa (pou $1 {{PLURAL:$1|jou|jou}} maximum)',
'tog-watchcreations'          => 'Mete paj mwen kreye yo nan lis swivi mwen.',
'tog-watchdefault'            => 'Mete paj mwen edite yo nan lis swivi mwen',
'tog-watchmoves'              => 'Mete paj mwen deplase yo nan lis swivi mwen',
'tog-watchdeletion'           => 'Mete paj mwen efase yo nan lis swivi mwen',
'tog-minordefault'            => 'Make tout modifikasyon mwen yo "tou piti" pa defo',
'tog-previewontop'            => 'Montre kout je anvan zòn modifikasyon',
'tog-previewonfirst'          => 'Montre kout je pou chak premye modifikasyon',
'tog-nocache'                 => 'Dezame kach pou paj yo nan òdinatè mwen',
'tog-enotifwatchlistpages'    => 'Voye m imèl lè youn nan paj m ap swiv yo chanje',
'tog-enotifusertalkpages'     => 'Voye m imèl lè paj itilizatè m nan chanje',
'tog-enotifminoredits'        => 'Voye m imèl tou pou modifikasyon paj yo ki tou piti',
'tog-enotifrevealaddr'        => 'Montre adrès imèl mwen nan kominikasyon notifikasyon yo',
'tog-shownumberswatching'     => 'Montre kantite itlizatè k’ap swiv',
'tog-oldsig'                  => 'Gade pou wè siyati ki egziste deja:',
'tog-fancysig'                => 'Konsidere siyati sa tankou yon wikitèks (san lyen ki ta otomatik)',
'tog-externaleditor'          => 'Itilize editè ki pa nan sistèm wikimedya pa defo',
'tog-externaldiff'            => 'Itilize yon konparatè ki pa nan sitsèm wikimedya pa defo',
'tog-showjumplinks'           => 'Pèmèt lyen aksesibilite « ale nan »',
'tog-uselivepreview'          => 'Itilize kout je an dirèk (sa mande JavaScrip) (Esperimantal)',
'tog-forceeditsummary'        => 'Notifye m lè m ap antre yon somè modifikasyon vid',
'tog-watchlisthideown'        => 'Kache modifikasyon m yo nan lis swivi mwen a',
'tog-watchlisthidebots'       => 'Kache modifikasyon wobo nan lis swivi mwen a',
'tog-watchlisthideminor'      => 'Kache modifikasyon ki tou piti yo nan lis swivi mwen a',
'tog-watchlisthideliu'        => 'Kache modifikasyon yo ki fèt pa itilizatè yo ki enskri nan lis swivi mwen',
'tog-watchlisthideanons'      => 'Kache modifikasyon anònim nan lis swivi mwen',
'tog-watchlisthidepatrolled'  => 'Kache modifikasyon ki siveye yo nan lis swivi mwen',
'tog-ccmeonemails'            => 'Voye yon kopi imèl mwen voye ba lòt ban mwen',
'tog-diffonly'                => 'Pa montre enfòmasyon yon paj ki anba chanjman yo montre nan konparezon',
'tog-showhiddencats'          => 'Montre kategori kache yo',
'tog-norollbackdiff'          => 'Pa montre chanjman yo lè mwen fè yon revokasyon',

'underline-always'  => 'Toujou',
'underline-never'   => 'Jamè',
'underline-default' => 'Selon paramèt navigatè',

# Font style option in Special:Preferences
'editfont-style'     => 'Estil karaktè yo nan zòn modifikasyon:',
'editfont-default'   => 'Selon paramèt navigatè',
'editfont-monospace' => 'Estil karaktè Monospaced (espas fiks)',
'editfont-sansserif' => 'Estil karaktè Sans-serif',
'editfont-serif'     => 'Estil karaktè Serif',

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
'wed'           => 'mèk',
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
'august-gen'    => 'out',
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
'category_header'                => 'Paj yo ki nan kategori « $1 »',
'subcategories'                  => 'Soukategori yo',
'category-media-header'          => 'Fichye miltimedya nan kategori « $1 »',
'category-empty'                 => "''Kategori sa a pa genyen atik ladan l, ni sou-kategori, ni menm yon fichye miltimedya.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori kache|Kategori kache yo}}',
'hidden-category-category'       => 'Kategori ki kache yo',
'category-subcat-count'          => '{{PLURAL:$2|Kategori sa gen sèlman soukategori swivan ladan l.|Kategori sa gen {{PLURAL:$1|soukategori sa|$1 soukategori sa yo}} ladan l, sou $2 total.}}',
'category-subcat-count-limited'  => 'Kategori sa gen {{PLURAL:$1|yon soukategori|$1 soukategori yo}} ladan l.',
'category-article-count'         => '{{PLURAL:$2|Kategori sa gen sèlman paj swivan ladan l.|{{PLURAL:$1|Paj sa|$1 Paj sa yo}} nan kategori sa, sou $2 total.}}',
'category-article-count-limited' => '{{PLURAL:$1|Paj sa|$1 paj sa yo}} nan kategori kouran.',
'category-file-count'            => '{{PLURAL:$2|Kategori sa gen sèlman dokiman swivan ladan l.|{{PLURAL:$1|Dokiman sa|$1 dokiman sa yo}} nan kategori sa, sou $2 total.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Dokiman sa|$1 dokiman sa yo}} nan kategori kouran.',
'listingcontinuesabbrev'         => '(kontinye)',
'index-category'                 => 'Paj endèkse yo',
'noindex-category'               => 'Paj ki pa endèkse yo',

'mainpagetext'      => "'''MedyaWiki byen enstale l.'''",
'mainpagedocfooter' => 'Konsilte [http://meta.wikimedia.org/wiki/Help:Konteni Gid Itilizatè] pou enfòmasyon sou kijan pou w itilize logisyèl wiki a.

== Kijan pou kòmanse ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lis paramèt yo pou konfigirasyon]
* [http://www.mediawiki.org/wiki/Manyèl:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lis diskisyon ki parèt sou MediaWiki]',

'about'         => 'Apwopo',
'article'       => 'Atik',
'newwindow'     => '(Ouvè nan yon lòt fenèt)',
'cancel'        => 'Anile',
'moredotdotdot' => 'Pi plis …',
'mypage'        => 'Paj mwen',
'mytalk'        => 'Paj diskisyon mwen an',
'anontalk'      => 'Paj diskisyon pou adrès IP sa',
'navigation'    => 'Navigasyon',
'and'           => '&#32;epi',

# Cologne Blue skin
'qbfind'         => 'Chache',
'qbbrowse'       => 'Bouske',
'qbedit'         => 'Modifye',
'qbpageoptions'  => 'Paj sa a',
'qbpageinfo'     => 'Kontèks',
'qbmyoptions'    => 'Paj mwen yo',
'qbspecialpages' => 'Paj espesyal',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'       => 'Ajoute yon sijè',
'vector-action-delete'           => 'Efase',
'vector-action-move'             => 'Chanje non',
'vector-action-protect'          => 'Pwoteje',
'vector-action-undelete'         => 'Retabli',
'vector-action-unprotect'        => 'Pa pwoteje',
'vector-simplesearch-preference' => 'Aktive sijèsyon rechèch ranfòse yo (pou estil Vector sèlman)',
'vector-view-create'             => 'Kreye',
'vector-view-edit'               => 'Modifye',
'vector-view-history'            => 'Gade istorik',
'vector-view-view'               => 'Li',
'vector-view-viewsource'         => 'Wè kòd tèks sa a',
'actions'                        => 'Aksyon yo',
'namespaces'                     => 'Espas non yo',
'variants'                       => 'Varyant yo',

'errorpagetitle'    => 'Erè',
'returnto'          => 'Ritounen nan paj $1.',
'tagline'           => 'Yon atik de {{SITENAME}}.',
'help'              => 'Èd',
'search'            => 'Chache',
'searchbutton'      => 'Fouye',
'go'                => 'Ale',
'searcharticle'     => 'Ale',
'history'           => 'Istorik paj la',
'history_short'     => 'Istorik',
'updatedmarker'     => 'Aktyalize depi dènyè visit mwen',
'info_short'        => 'Enfòmasyon',
'printableversion'  => 'Vèsyon ou kapab enprime',
'permalink'         => 'Lyen pou tout tan',
'print'             => 'Enprime',
'view'              => 'Gade',
'edit'              => 'Modifye',
'create'            => 'Kreye',
'editthispage'      => 'Modifye paj sa a',
'create-this-page'  => 'Kreye paj sa',
'delete'            => 'Efase',
'deletethispage'    => 'Efase paj sa',
'undelete_short'    => 'Restore {{PLURAL:$1|Yon modifikasyon| $1 modifikasyon yo}}',
'viewdeleted_short' => 'Gade {{PLURAL:$1|yon modifikasyon ki te efase|$1 modifikasyon yo ki te efase}}',
'protect'           => 'Pwoteje',
'protect_change'    => 'Chanje pwoteksyon paj sa',
'protectthispage'   => 'Pwoteje paj sa',
'unprotect'         => 'Pa pwoteje',
'unprotectthispage' => 'Depwoteje paj sa',
'newpage'           => 'Nouvo paj',
'talkpage'          => 'Diskite paj sa a',
'talkpagelinktext'  => 'Diskite',
'specialpage'       => 'Paj Espesyal',
'personaltools'     => 'Zouti pèsonèl yo',
'postcomment'       => 'Nouvo seksyon',
'articlepage'       => 'Wè paj atik',
'talk'              => 'Diskisyon',
'views'             => 'Afichay yo',
'toolbox'           => 'Bwat zouti',
'userpage'          => 'Wè paj itilizatè',
'projectpage'       => 'Wè paj pwojè',
'imagepage'         => 'Wè paj fichye',
'mediawikipage'     => 'Wè paj mesaj',
'templatepage'      => 'Wè paj modèl',
'viewhelppage'      => 'Wè paj èd',
'categorypage'      => 'Wè paj kategori',
'viewtalkpage'      => 'Wè paj diskisyon',
'otherlanguages'    => 'Nan lòt lang yo',
'redirectedfrom'    => '(Redirije depi $1)',
'redirectpagesub'   => 'Paj pou redireksyon',
'lastmodifiedat'    => 'Paj sa te modifye pou dènye fwa $1 a $2.<br />',
'viewcount'         => 'Paj sa te konsilte {{PLURAL:$1|yon fwa|$1 fwa}}.',
'protectedpage'     => 'Paj pwoteje',
'jumpto'            => 'Ale nan:',
'jumptonavigation'  => 'Navigasyon',
'jumptosearch'      => 'Fouye',
'view-pool-error'   => 'Padone nou, men sèvè yo genyen trop travay kounye a.
Genyen trop itilizatè k ap eseye gade paj sa.
Tanpri tann yon tikras tan anvan ou eseye gade paj sa ankò.

$1',
'pool-timeout'      => 'Tan ekoule pou defè seri a',
'pool-queuefull'    => 'Fil pou travay la plen',
'pool-errorunknown' => 'Erè nou pa konnen',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Apwopo {{SITENAME}}',
'aboutpage'            => 'Project:Apwopo',
'copyright'            => 'Kontni disponib anba $1.',
'copyrightpage'        => '{{ns:project}}:Dwa rezève',
'currentevents'        => 'Aktyalite yo',
'currentevents-url'    => 'Project:Aktyalite yo',
'disclaimers'          => 'Avètisman',
'disclaimerpage'       => 'Project:Avètisman jeneral yo',
'edithelp'             => 'Èd pou modifye paj',
'edithelppage'         => 'Help:Modifye yon paj',
'helppage'             => 'Help:Èd',
'mainpage'             => 'Paj prensipal',
'mainpage-description' => 'Paj prensipal',
'policy-url'           => 'Project:Règleman',
'portal'               => 'Pòtay kominote',
'portal-url'           => 'Project:Akèy',
'privacy'              => 'Politik konfidansyalite',
'privacypage'          => 'Project:Konfidansyalite',

'badaccess'        => 'Erè nan pèmisyon',
'badaccess-group0' => 'Ou pa genyen pèmisyon pou ou ekzekite demand sa.',
'badaccess-groups' => 'Aksyon ke w vle reyalize a limite sèlman pou itilizatè ki nan {{PLURAL:$2|gwoup sa |yonn nan gwoup sa yo}}: $1.',

'versionrequired'     => 'Vèsion $1 de MediaWiki nesesè',
'versionrequiredtext' => 'Vèzion $1 de MediaWiki nesesè pou itilize paj sa. Wè [[Special:Version|version page]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Rekipere depi « $1 »',
'youhavenewmessages'      => 'Ou genyen $1 ($2).',
'newmessageslink'         => 'nouvo mesaj',
'newmessagesdifflink'     => 'dènye chanjman',
'youhavenewmessagesmulti' => 'Ou genyen nouvo mesaj sou $1.',
'editsection'             => 'modifye',
'editold'                 => 'modifye',
'viewsourceold'           => 'Wè kòd paj la',
'editlink'                => 'modifye',
'viewsourcelink'          => 'wè kòd paj la',
'editsectionhint'         => 'Modifye seksyon : $1',
'toc'                     => 'Kontni yo',
'showtoc'                 => 'montre',
'hidetoc'                 => 'kache',
'collapsible-collapse'    => 'Redui',
'collapsible-expand'      => 'Etann',
'thisisdeleted'           => 'Ou vle wè oubyen restore $1 ?',
'viewdeleted'             => 'Wè $1 ?',
'restorelink'             => '{{PLURAL:$1|yon revizion efase|$1 revizion efase yo}}',
'feedlinks'               => 'Fil:',
'feed-invalid'            => 'Kalite fil sa envalid.',
'feed-unavailable'        => 'Fil sendikasyon yo pa disponib',
'site-rss-feed'           => 'Fil RSS depi $1',
'site-atom-feed'          => 'Fil Atom depi $1',
'page-rss-feed'           => 'Fil RSS pou "$1"',
'page-atom-feed'          => '"$1" fil Atom',
'red-link-title'          => '$1 (paj sa pa ekziste nan sistèm nan)',

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
'nosuchactiontext'  => 'Wiki a pa rekonèt aksyon ki espesifye pa URL la.
Ou gendwa mal ekri URL la oubyen ou te swiv yon movè lyen.
Sa kapab di tou gen yon erè nan lojisyèl ki itilize nan sit {{SITENAME}}.',
'nosuchspecialpage' => 'Pa gen paj especial konsa',
'nospecialpagetext' => '<strong>Paj espesial ou demande-a envalid.</strong>

Ou ka jwenn yon lis paj espesial ki valid yo la [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Erè',
'databaseerror'        => 'Erè nan bazdone.',
'dberrortext'          => 'Yon rekèt nan bazdone a bay yon erè.
Sa kapab vle di genyen yon erè nan lojisyèl nan.
Dènye esè a te :
<blockquote><tt>$1</tt></blockquote>
depi fonksyon sa « <tt>$2</tt> ».
Bazdone ritounen erè sa « <tt>$3 : $4</tt> ».',
'dberrortextcl'        => 'Yon rekèt nan bazdone a bay yon erè.
Dènye esè nan baz done a te:
« $1 »
depi fonksyon sa « $2 ».
Bazdone a te bay mesaj erè sa « $3 : $4 ».',
'laggedslavemode'      => "'''Atansyon:''' paj sa a kapab pa anrejistre modifikasyon ki fèk fèt yo.",
'readonly'             => 'Bazdone a fèmen toutbon.',
'enterlockreason'      => 'Bay yon rezon pou fème bazdone a ak yon estimasyon ki lè w ap ouvri l ankò',
'readonlytext'         => 'Bazdone a fèmen kounye a, nou pa kapab ajoute pyès done ladan l. Sanble se pou pèmèt jesyon l; apre sa, l ap reprann sèvis li.

Administratè a ki te fèmen l bay rezon sa a : $1',
'missing-article'      => 'Bazdone an pa t jwenn tèks yon paj li te dwe jwenn toutbon; non li se « $1 » $2.

Nòmalman, sa rive lè ou swiv yon lyen ki pa egziste ankò pou chanjman ki te fèt nan paj sa oubyen ou swiv yon lyen nan istwa pou yon paj ki te deja efase.

Si rezon sa yo pa koresponn ak sityasyon ou an, gendwa se jwenn ou jwenn kèk erè nan lojisyèl nan.
Souple, kontakte yon [[Special:ListUsers/sysop|administratè]], epi ba li lyen adrès sa a.',
'missingarticle-rev'   => '(chanjman # : $1)',
'missingarticle-diff'  => '(Diferans : $1, $2)',
'readonly_lag'         => 'Bazdone a bloke otomatikman pandan lòt sèvè segondè yo ap travay pou bay lanmen nan sèvè prensipal la.',
'internalerror'        => 'Erè nan sistèm la.',
'internalerror_info'   => 'Erè nan sistèm la : $1',
'fileappenderrorread'  => 'Pa kapab li $1 pandan n ap ajoute sou do.',
'fileappenderror'      => 'Pa kapab ajoute « $1 » sou do « $2 ».',
'filecopyerror'        => 'Nou pa kapab kopye fichye  « $1 » nan « $2 ».',
'filerenameerror'      => 'Nou pa kapab bay lòt non « $2 » pou fichye « $1 ».',
'filedeleteerror'      => 'Nou pa kapab efase fichye « $1 ».',
'directorycreateerror' => 'Nou pa kapab kreye dosye « $1 ».',
'filenotfound'         => 'Nou pa kapab jwenn fichye « $1 ».',
'fileexistserror'      => 'Nou pa kapab ekri nan dosye « $1 » : fichye a egziste deja',
'unexpected'           => 'Valè sa pa koresponn ak sa nou genyen nan sistèm an : « $1 » = « $2 ».',
'formerror'            => 'Erè : nou pa kapab anrejistre fòmilè sa',
'badarticleerror'      => 'Ou pa kapab fè aksyon sa sou paj sa.',
'cannotdelete'         => 'Nou pa t kapab efase paj oubyen fichye « $1 ».
Yon lòt moun te gendwa efase l anvan ou.',
'badtitle'             => 'Tit ou bay an pa bon, li pa koresponn nan sistèm an, eseye byen ekri li',
'badtitletext'         => 'Tit, sijè paj ou mande a pa korèk oubyen li pa egziste oubyen li nan yon lòt pwojè wiki yo (gade nan lòt pwojè wiki yo pou wè toutbon). Li mèt genyen tou kèk karaktè ki pa rekonèt nan sistèm an, eseye itilize bon karaktè yo nan tit ou yo.',
'perfcached'           => 'Sa se yon vèsyon ki sòti nan kach sistèm nou an. Li gendwa pa a jou.',
'perfcachedts'         => 'Done sa yo sòti nan sistèm kach la, yo gendwa pa a jou. Dènye fwa nou mete yo a jou se te $1.',
'querypage-no-updates' => 'Nou pa kapab mete paj sa yo a jou paske fonksyon mizajou dezaktive. Done w ap jwenn pi ba pap rafrechi.',
'wrong_wfQuery_params' => 'Paramèt sa yo pa bon sou wfQuery()<br />
Fonksyon : $1<br />
Demann : $2',
'viewsource'           => 'Wè kòd paj la',
'viewsourcefor'        => 'pou $1',
'actionthrottled'      => 'Aksyon sa limite',
'actionthrottledtext'  => 'Nan batay kont pouryèl, aksyon sa ou tapral fè limite nan kantite itilizasyon l pandan yon tan ki kout. Li sanble ou depase kantite sa. Eseye ankò nan kèk minit.',
'protectedpagetext'    => 'Paj sa pwoteje pou anpeche tout modifikasyon nou ta kapab fè sou li. Gade paj diskisyon sou li pito.',
'viewsourcetext'       => 'Ou kapab gade epitou modifye kontni atik sa a pou ou travay anlè li :',
'protectedinterface'   => 'Paj sa ap bay tèks pou entèfas lojisyèl an e li pwoteje pou anpeche move itilizasyon nou ta kapab fè ak li.',
'editinginterface'     => "'''Pòte atansyon :''' ou ap modifye yon paj ki itilize nan kreyasyon tèks entèfas lojisyèl an. Chanjman yo ap ritounen, li ap depann de kèk sityasyon, nan tout paj ke lòt itilizatè yo kapab wè tou. Pou tradiksyon yo, nap envite w itilize pwojè MediaWiki pou mesaj entènasyonal yo (tradiksyon) nan paj sa [http://translatewiki.net/wiki/Main_Page?setlang=fr translatewiki.net].",
'sqlhidden'            => '(Demann SQL an kache)',
'cascadeprotected'     => 'Paj sa pwoteje kounye a paske l nan {{PLURAL:$1|paj ki douvan l|paj yo ki douvan l}}, paske {{PLURAL:$1|l te pwoteje|yo te pwoteje}} ak opsyon « pwoteksyon pou tout paj ki nan premye paj an - kaskad » aktive :
$2',
'namespaceprotected'   => "Ou pa gen dwa modifye paj nan espas non « '''$1''' ».",
'customcssjsprotected' => 'Ou pa kapab modifye paj sa paske li manke w kèk otorizasyon; li genyen preferans yon lòt itilizatè.',
'ns-specialprotected'  => 'Paj yo ki nan espas non « {{ns:special}} » pa kapab modifye.',
'titleprotected'       => "Tit, sijè sa pwoteje pandan kreyasyon l pa [[User:$1|$1]].
Rezon li bay yo se « ''$2'' ».",

# Virus scanner
'virus-badscanner'     => "Move konfigirasyon : eskanè viris sa, nou pa konenn l : ''$1''",
'virus-scanfailed'     => 'Rechèch an pa ritounen pyès rezilta (kòd $1)',
'virus-unknownscanner' => 'antiviris nou pa konnen :',

# Login and logout pages
'logouttext'                 => "'''Ou dekonekte kounye a.'''

Ou mèt kontinye itilize {{SITENAME}} san ou pa idantifye, oubyen ou ka [[Special:UserLogin|rekonekte]] w ankò ak menm non an oubyen yon lòt.
Note ke kèk paj gendwa afiche tankou ou te toujou konekte tank ou pa efase kach nan navigatè ou.",
'welcomecreation'            => '== Byenvini, $1 ! ==

Kont ou an kreye. Pa bliye pèsonalize l nan  [[Special:Preferences|preferans ou an sou paj sa {{SITENAME}}]].',
'yourname'                   => 'Non itilizatè ou an :',
'yourpassword'               => 'Mopas ou an :',
'yourpasswordagain'          => 'Mete mopas ou an ankò :',
'remembermypassword'         => 'Sonje mopas mwen an nan òdinatè mwen an (pou yon maximum de $1 {{PLURAL:$1|jou|jou}})',
'securelogin-stick-https'    => 'Kontinye itilize HTTPS toujou apre koneksyon',
'yourdomainname'             => 'Domèn ou an',
'externaldberror'            => 'Li sanble ke yon erè pwodui ak bazdone a pou idantifikasyon ki pa nan sistèm an, oubyen ou pa otorize pou mete a jou kont ou genyen nan lòt sistèm yo.',
'login'                      => 'Konekte ou',
'nav-login-createaccount'    => 'Kreye yon kont oubyen konekte ou',
'loginprompt'                => 'Ou dwe aksepte (aktive) koukiz (cookies) yo pou ou kapab konekte nan {{SITENAME}}.',
'userlogin'                  => 'Kreye yon kont oubyen konekte ou',
'userloginnocreate'          => 'Konekte ou',
'logout'                     => 'Dekonekte ou',
'userlogout'                 => 'Dekoneksyon',
'notloggedin'                => 'Ou pa konekte',
'nologin'                    => "Ou pa genyen yon kont ? '''$1'''.",
'nologinlink'                => 'Kreye yon kont',
'createaccount'              => 'Kreye yon kont',
'gotaccount'                 => "Ou deja genyen yon kont ? '''$1'''.",
'gotaccountlink'             => 'Idantifye ou',
'createaccountmail'          => 'pa imèl',
'createaccountreason'        => 'Rezon:',
'badretype'                  => 'Mopas ou bay yo pa parèy ditou.',
'userexists'                 => 'Non itilizatè ou bay an deja itilize pa yon lòt moun. Chwazi yon lòt souple.',
'loginerror'                 => 'Erè nan idantifikasyon ou an',
'createaccounterror'         => 'Pa kapab kreye kont: $1',
'nocookiesnew'               => "Kont itilizatè a kreye, men ou pa konekte. {{SITENAME}} ap itilize koukiz (''cookies'') pou konekte l.Li sanble ou dezaktive fonksyon sa. Tanpri, aktive fonksyon sa epi rekonekte ou ak menm non epi mopas ou yo.",
'nocookieslogin'             => "{{SITENAME}} ap itilize koukiz (''cookies'') pou li kapab konekte kò l. Men li sanble ou dezaktive l; tanpri, aktive fonksyon sa epi rekonekte w.",
'nocookiesfornew'            => 'Kont itilizatè pa t kreye poutèt nou pa kapab konnen sous li.
Asire w koukiz (cookies) yo aktive nan navigatè w, chaje paj la ankò epi eseye ankò.',
'noname'                     => 'Ou pa bay sistèm an yon non itilizatè ki bon.',
'loginsuccesstitle'          => 'Ou byen konekte nan sistèm la',
'loginsuccess'               => 'Ou konekte kounye a nan {{SITENAME}} ak idantifyan sa a « $1 ».',
'nosuchuser'                 => 'Itilizatè "$1" pa ekziste.
Majiskil ak miniskil chanje non itilizatè.
Byen gade ke ou te byen ekri non ou, oubyen [[Special:UserLogin/signup|kreye yon nouvo kont]].',
'nosuchusershort'            => 'Pa genyen itilizatè ak non « <nowiki>$1</nowiki> » sa a. Byen gade lòtograf ou an.',
'nouserspecified'            => 'Ou dwe mete non itilizatè ou an.',
'login-userblocked'          => 'Itilizatè sa bloke.  Li pa gendwa konekte.',
'wrongpassword'              => 'Mopas an pa korèk. Eseye ankò.',
'wrongpasswordempty'         => 'Ou pa antre mopas ou an. Eseye ankò.',
'passwordtooshort'           => 'Mopas ou an twò kout. Li dwe genyen omwens {{PLURAL:$1|1 karaktè|$1 karaktè}}.',
'password-name-match'        => 'Mopas ou dwe diferan ak non itilizatè ou.',
'password-login-forbidden'   => 'Nou pa gendwa pran non itilizatè ak mopas sa yo.',
'mailmypassword'             => 'Voye mwen yon nouvo mopas pa imèl',
'passwordremindertitle'      => 'Nouvo mopas tanporè, li pap dire (yon kout tan) pou pajwèb sa a {{SITENAME}}',
'passwordremindertext'       => 'Kèk moun (ou menm oubyen yon moun ki genyen adrès IP sa a $1) mande pou nou voye w yon nouvo mopas pou {{SITENAME}} ($4).
Mopas tanporè itilizatè "$2" kounye a se "$3". Si se sa ou te vle, nou konseye ou konekte ou epi modifye mopas sa a rapidman, si posib kounye a.  Mopas tanporè sa a pral ekspire nan {{PLURAL:$5|jou|jou}}.

Si se pa ou menm ki mande modifye mopas ou an oubyen si ou konnen mopas ou an e ke ou pa ta vle modifye li, pa konsidere mesaj sa a epi kontinye ak mopas ou a.',
'noemail'                    => 'Pa genyen pyès adrès imèl ki anrejistre pou itilizatè sa a « $1 ».',
'noemailcreate'              => 'Ou bezwen bay yon adrès imèl ki valab',
'passwordsent'               => 'Yon nouvo mopas voye ba imèl sa a pou itilizatè « $1 ». Souple, konekte ou lè ou resevwa mesaj an.',
'blocked-mailpassword'       => 'Adrès IP ou an bloke pou edisyon, fonksyon rapèl mopas dezaktive toutbon pou anpeche move itilizasyon ki kapab fèt ak li.',
'eauthentsent'               => 'Nou voye yon imèl pou konfimasyon nan adrès imèl an.
Anvan yon lòt imèl voye, swiv komand ki nan mesaj imèl an epi konfime ke kont an se byen kont ou an.',
'throttled-mailpassword'     => 'Yon imèl ki genyen ladan l yon rapèl mopas ou an te voye pandan {{PLURAL:$1|dènye lè a|dènye $1 lè sa yo}}. Pou anpeche pwofitè ak kèk move itilizasyon, yon sèl imèl ap voye pou chak {{PLURAL:$1|lè sa|entèval $1 lè sa yo}}.',
'mailerror'                  => 'Erè ki vini lè nap voye imèl an : $1',
'acct_creation_throttle_hit' => 'Anpil vizitè nan wiki sa a ki te itilize menm IP avèk ou te deja kreye {{PLURAL:$1|1 kont|$1 kont}} nan dènye jou sa, e se maximum ki kapab fèt nan yon sèl jounen. Se sak fè moun ki soti nan IP sa pa kapab kreye lòt kont ankò kounye a.',
'emailauthenticated'         => 'Adrès imèl ou an te kore nan sistèm nou an depi $2 nan $3.',
'emailnotauthenticated'      => 'Adrès imèl ou an <strong>poko kore</strong>. Pa gen pyès mesaj imèl ki ap voye pou fonksyon sa yo.',
'noemailprefs'               => 'Mete yon adrès imèl nan preferans ou yo pou fonksyon sa yo ka disponib.',
'emailconfirmlink'           => 'Konfime adrès imèl ou an',
'invalidemailaddress'        => 'Nou pa kapab aksepte adrès imèl sa paske li sanble fòma l pa bon ditou. Tanpri, mete yon adrès ki nan yon bon fòma oubyen pa ranpli seksyon sa.',
'accountcreated'             => 'Kont ou an kreye',
'accountcreatedtext'         => 'Kont itilizatè $1 an kreye.',
'createaccount-title'        => 'Kreyasyon yon kont pou {{SITENAME}}',
'createaccount-text'         => 'Yon moun kreye yon kont pou adrès imèl ou an sou paj sa {{SITENAME}} ($4), non l se « $2 », mopas an se « $3 ». Ou ta dwe ouvè yon sesyon pou chanje kounye a mopas ou.

Pa pòte atansyon pou mesaj sa si kont sa kreye pa erè.',
'usernamehasherror'          => 'Non itilizatè pa gendwa genyen karaktè achaj ladan l',
'login-throttled'            => 'Ou fè twòp tantativ pou konekte w ak mopas ou an. Tanpri, tann yon ti moman anvan ou eseye ankò.',
'loginlanguagelabel'         => 'Lang : $1',
'suspicious-userlogout'      => 'Demand ou te fè pou dekonekte w te refize paske sanble li te voye pa yon navigatè ki fè erè oubyen li soti nan yon proksi pou kach.',

# E-mail sending
'php-mail-error-unknown' => 'Erè nou pa konnen nan fonksyon mail() PHP a.',

# JavaScript password checks
'password-strength'            => 'Fòs mopas la: $1',
'password-strength-bad'        => 'MOVE',
'password-strength-mediocre'   => 'pa bon menm',
'password-strength-acceptable' => 'akseptab',
'password-strength-good'       => 'bon',
'password-retype'              => 'Mete mopas ou an ankò :',
'password-retype-mismatch'     => 'Mopas yo pa koresponn',

# Password reset dialog
'resetpass'                 => 'Chanje mopas ou an',
'resetpass_announce'        => 'Ou konekte ou ak yon mopas ki valab yon moman; mopas sa te voye pa imèl. Pou ou kapab fini anrejistreman an, ou dwe mete yon nouvo mopas la :',
'resetpass_header'          => 'Chanje mopas kont ou an',
'oldpassword'               => 'Ansyen mopas:',
'newpassword'               => 'Nouvo mopas:',
'retypenew'                 => 'Konfime nouvo mopas an :',
'resetpass_submit'          => 'Chanje mopas epitou konekte',
'resetpass_success'         => 'Nou chanje mopas ou an avèk siksè ! Nap konekte ou kounye a...',
'resetpass_forbidden'       => 'Nou pa kapab chanje mopas yo nan sistèm sa',
'resetpass-no-info'         => 'Ou dwe konekte ou pou ou kapab jwenn paj sa.',
'resetpass-submit-loggedin' => 'Modifye mopas sa',
'resetpass-submit-cancel'   => 'Anile',
'resetpass-wrong-oldpass'   => 'Mopas sa pa bon ditou; li te mèt mopas ou an kounye a oubyen yon mopas tanporè.
Gendwa ou te deja modifye li oubyen ou te mande yon nouvo mopas tanporè.',
'resetpass-temp-password'   => 'Mopas tanporè yo ba ou an:',

# Edit page toolbar
'bold_sample'     => 'Tèks fonse',
'bold_tip'        => 'Tèks fonse',
'italic_sample'   => 'Tèks italik',
'italic_tip'      => 'Tèks italik',
'link_sample'     => 'Lyen pou tit an',
'link_tip'        => 'Lyen anndan',
'extlink_sample'  => 'http://www.example.com yon tit pou lyen an',
'extlink_tip'     => 'Lyen andeyò (pa blye prefiks http:// an)',
'headline_sample' => 'Tèks pou tit',
'headline_tip'    => 'Sou-tit nivo 2',
'math_sample'     => 'Antre fòmil ou an isit',
'math_tip'        => 'Fòmil matematik (LaTeX)',
'nowiki_sample'   => 'Antre tèks ki pa fòmate a',
'nowiki_tip'      => 'Pa konte sentaks wiki an',
'image_tip'       => 'Fichye anndan paj sa',
'media_tip'       => 'Lyen pou fichye sa',
'sig_tip'         => 'Siyati ou ak dat an',
'hr_tip'          => 'Liy orizontal (pa abize)',

# Edit pages
'summary'                          => 'Somè:',
'subject'                          => 'Sijè/tit:',
'minoredit'                        => 'Modifikasyon sa a tou piti',
'watchthis'                        => 'Swiv paj sa a',
'savearticle'                      => 'Anrejistre',
'preview'                          => 'Gade pou wè',
'showpreview'                      => 'Gade pou wè',
'showlivepreview'                  => 'Gade pou wè (Kout je rapid)',
'showdiff'                         => 'Montre chanjman yo',
'anoneditwarning'                  => "'''Pòte atansyon :''' ou pa konekte nan sistèm nan. Adrès IP ou a ap anrejistre nan istorik paj sa a.",
'anonpreviewwarning'               => "''Ou pa konekte.  Anrejistre ap kenbe adrès IP ou a nan istorik paj sa a.''",
'missingsummary'                   => "'''Souple :''' ou poko bay rezime modifikasyon ou fè an
Si ou klike sou \"{{int:savearticle}}\" ankò, piblikasyon sa ap fèt san li bay lòt avètisman.",
'missingcommenttext'               => 'Souple, ekri komantè ou an pli ba nan paj sa.',
'missingcommentheader'             => "'''Pòte atansyon :''' ou pa bay komantè ou an yon sijè/tit .
Si ou klike sou \"{{int:savearticle}}\", modifikasyon ou an pap genyen yon tit.",
'summary-preview'                  => 'Kout je nan rezime an anvan li anrejistre:',
'subject-preview'                  => 'Yon kout je sou sijè/tit sa:',
'blockedtitle'                     => 'itilizatè a bloke.',
'blockedtext'                      => "'''Kont itilizatè ou an (oubyen adrès IP ou an) bloke.'''

Blokaj an fèt pa $1.
Rezon li bay se : ''$2''.


* Komansman blokaj an : $8
* Dat pou blokaj an fini : $6
* Kont bloke a : $7.

Ou mèt kontakte $1 oubyen yon lòt [[{{MediaWiki:Grouppage-sysop}}|administratè]] pou diskite plis. Ou pa kapab itilize fonksyon  « Voye yon imèl ba itilizatè sa a » eksepte si ou mete yon adrès imèl nan paj  [[Special:Preferences|preferans ou an]]. Adrès IP ou an kounye a se $3 e idantifyan blokaj ou an se #$5. Mete souple referans adrès sa a nan demann ou yo.",
'autoblockedtext'                  => 'Adrès IP ou an bloke otomatikman paske li te itilize pa yon lòt itilizatè ki te bloke pa $1.

Rezon ki te bay se :

:\'\'$2\'\'

* Komansman blokaj an : $8
* Tan li pral fini : $6
* Moun ki te bloke a : $7

Ou mèt kontakte $1 oubyen yonn nan [[{{MediaWiki:Grouppage-sysop}}|administratè yo]] pou diskite sityasyon blokaj sa.

Si toutfwa ou te bay yon bon adrès imèl nan preferans ou yo ( [[Special:Preferences|préférences]]) ou mèt itilize fonksyon "voye yon mesaj ba itilizatè sa" pou ou kontakte administratè a.

Adrès IP ou an kounye a se $3. Idantifyan pou blokaj la se $5. Ou dwe mete enfòmasyon sa yo nan demann ou an.',
'blockednoreason'                  => 'Li pa bay pyès rezon pou aksyon sa',
'blockedoriginalsource'            => "Wè kòd sous '''$1''' pli ba :",
'blockededitsource'                => "Teks '''modifikasyon ou yo''' sou '''$1''' parèt pi ba :",
'whitelistedittitle'               => 'Ou dwe konekte w pou ou kapab modifye tèks sa',
'whitelistedittext'                => 'Ou dwe $1 pou ou kapab genyen dwa pou modifye paj sa.',
'confirmedittext'                  => 'Ou dwe konfime adrès imèl ou an anvan ou kapab fè modifikasyon. Antre epi valide adrès imèl ou an nan paj [[Special:Preferences|preferans]] ou.',
'nosuchsectiontitle'               => 'Nou pa ka jwenn seksyon sa a',
'nosuchsectiontext'                => 'Ou eseye modifye yon seksyon ki pa ekziste.
Petèt li te bouje oubyen efase pandan ou tap gade paj la.',
'loginreqtitle'                    => 'Koneksyon an nesesè',
'loginreqlink'                     => 'konekte ou',
'loginreqpagetext'                 => 'Ou dwe $1 pou ou kapab wè lòt paj yo.',
'accmailtitle'                     => 'Mopas an voye.',
'accmailtext'                      => "Nou fè yon mopas o aza pou [[User talk:$1|$1]] epi nou voye l nan adrès $2.
Ou ka chanje mopas pou kont sa a nan paj ''[[Special:ChangePassword|chanje mopas]]'' aprè ou konekte ou.",
'newarticle'                       => '(Nouvo)',
'newarticletext'                   => "Ou swiv on lyen pou yon paj ki poko egziste.
Pou ou kapab kreye paj sa a, komanse ekri nan bwat ki anba (gade [[{{MediaWiki:Helppage}}|paj èd nan]] pou konnen plis, pou plis enfòmasyon).
Si se paske ou fè yon erè ke ou rive nan paj sa a, klike anlè bouton '''fè back''' nan navigatè ou a.",
'anontalkpagetext'                 => "---- ''Ou nan paj diskisyon yon itilizatè anonim, ki pa gen non, ki poko kreye yon kont oubyen ki pa itilize pyès kont nan sistèm sa. Pou rezon sa, nou dwe itilize adrès IP l pou nou kapab lokalize l, sitye l, montre kote l rete, idantifye l. Yon adrès IP kapab pataje ant plizyè moun, plizyè itilizatè. Si ou se yon itilizatè anonim e si ou wè ke ou resevwa komantè ki pa t pou ou, ou mèt [[Special:UserLogin/signup|kreye yon kont]] oubyen [[Special:UserLogin|konekte ou]] pou ou kapab anpeche konfizyon ak kontribitè anonim yo.''",
'noarticletext'                    => 'Poko genyen tèks nan paj sa a.
Ou mèt [[Special:Search/{{PAGENAME}}|fè yon rechèch, fouye ak non paj sa a]] nan lòt paj yo, oubyen <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} chache jounal modifikasyon yo ki an relasyon ak paj sa] oubyen tou [{{fullurl:{{FULLPAGENAME}}|action=edit}} modifye paj sa]</span>.',
'noarticletext-nopermission'       => 'Poko genyen tèks nan paj sa a.
Ou mèt [[Special:Search/{{PAGENAME}}|fè yon rechèch, fouye ak non paj sa a]] nan lòt paj yo, oubyen <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} chache jounal modifikasyon yo ki an relasyon ak paj sa].',
'userpage-userdoesnotexist'        => 'Kont itilizatè « $1 » sa pa anrejistre. Verifye toutbon ke ou vle kreye paj sa.',
'userpage-userdoesnotexist-view'   => 'Itilizatè "$1" pa ekziste.',
'blocked-notice-logextract'        => 'Itilizatè sa a bloke kounye a.
Dènye jounal pou blokaj yo parèt anba kòm referans:',
'clearyourcache'                   => "'''Note bagay sa:''' apre ou pibliye paj sa, ou gendwa oblije fòse chajman, rafrechi paj nan kach navigatè entènèt ou an pou ou kapab wè chanjman yo : '''Mozilla / Firefox / Konqueror / Safari :''' kenbe touch ''lèt kapital'' toutpandan w ap klike sou bouton ''Rafrechi/Aktyalize'' oubyen swa ou peze ''Maj-Ctrl-F5'', swa ou peze ''Maj-Ctrl-R'' (''Maj-Cmd-R'' sou sistèm Apple Mac) ; '''Internet Explorer / Opera :''' kenbe touch ''Ctrl'' toutpandan w ap klike sou bouton ''Rafrechi/Aktyalize'', oubyen peze ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Bagay ki ap sèvi w :''' Itilize bouton « {{int:showpreview}} » pou teste nouvo fèy CSS anvan ou anrejistre l.",
'userjsyoucanpreview'              => "'''Bagay ki ap sèvi w :''' Itilize bouton « {{int:showpreview}} » pou teste nouvo fèy JavaScript anvan ou anrejistre l.",
'usercsspreview'                   => "'''Sonje ke w ap voye yon kout je sou sa w ekri nan fèy CSS pa ou sa.'''
''Li poko anrejistre !'''",
'userjspreview'                    => "'''Sonje ke ou ap voye kout je sou fèy JavaScript ou ekri a, li poko anrejistre !'''",
'sitecsspreview'                   => "'''Sonje ke w ap voye yon kout je sou sa w ekri nan fèy CSS sa a.'''
'''Li poko anrejistre !'''",
'sitejspreview'                    => "'''Sonje ke w ap voye yon kout je sou kòd JavaScript sa a.'''
'''Li poko anrejistre !'''",
'userinvalidcssjstitle'            => "'''Pòte atansyon :''' estil \"\$1\" pa egziste. Paj pèsonalize ak ekstansyon .css epi .js yo ap itilize tit/sijè nan lèt miniskil, pa egzanp {{ns:user}}:Foo/vector.css se pa {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(Li gen dènye vèsyon sou li)',
'note'                             => "'''Nòt :'''",
'previewnote'                      => "'''Atansyon, tèks sa a se yon kout je, li poko anrejistre !'''",
'previewconflict'                  => 'Kout je sa ap montre tèks ki nan bwat modifikasyon anwo pou ou wè l jan l ap parèt lè ou deside pou ou pibliye l.',
'session_fail_preview'             => "'''Ekskize nou ! Nou pa kapab anrejistre modifikasyon ou an paske nou sanble pèdi kèk enfòmasyon koneksyon sou kont ou an, sou sesyon ou an. Eseye yon fwa ankò. Si li pa mache, eseye [[Special:UserLogout|dekonekte ou]], apre w ap konekte ou ankò.'''",
'session_fail_preview_html'        => "'''Eskize nou ! Nou pa kapab anrejistre modifikasyon ou an paske nou pèdi yon pati nan enfomasyon sou sesyon ou an.'''

''Bouton pou fè kout je an kache pou anpeche atak pa JavaScript paske {{SITENAME}} pèmèt HTML brit la''

'''Si ou panse ke modifikasyon ou an bon toutbon, ou mèt eseye anko. Si sistèm an pa aksepte toujou, eseye [[Special:UserLogout|dekonekte ou]], epi rekonekte w ankò.'''",
'token_suffix_mismatch'            => "'''Modifikasyon ou fè a pa t aksepte paske navigatè ou a melanje karaktè ponktyasyon yo nan idantifyan pou tèks sa. Modifikasyon pa aksepte pou li pa jenere kèk pwoblèm nan tèks ki te nan paj la. Pwoblèm sa kapab rive lè w ap pase pa yon sèvè pwoksi (proxy) ki gen erè ladan l.'''",
'editing'                          => 'Modifikasyon pou $1',
'editingsection'                   => 'Modifikasyon pou $1 (seksyon)',
'editingcomment'                   => 'Modifikasyon pou $1 (nouvo seksyon)',
'editconflict'                     => 'Batay ant modifikasyon : $1',
'explainconflict'                  => 'Yon lòt moun te anrejistre paj sa apre ou te komanse modifye l.
W ap jwenn teks jan li ye kounye a nan zòn modifikasyon an ki anlè.
Modifikasyon ou pòte yo parèt anba.
Ou dwe fè modifikasyon ou yo nan tèks ki te deja anrejistre a ki anlè.
Se tèks ki nan pati anlè a sèlman k ap anrejistre toutbon lè ou klike sou « {{int:savearticle}} ».',
'yourtext'                         => 'Tèks ou an',
'storedversion'                    => 'Vèsyon ki anrejistre',
'nonunicodebrowser'                => "'''Atansyon: Navigatè ou an pa ka mache ak Unicode lan.'''
Nou fè yon jan pou pèmèt ou fè modifikasyon nan paj yo: karaktè ki pa nan ASCII yo pral ekri ak kòd ekzadesimal.",
'editingold'                       => "'''Avètisman : Ou ap edite yon vye vèsyon paj sa a.''' 
Si ou anrejistre li, tout chanjman yo depi vèsyon sa a pral pèdi.",
'yourdiff'                         => 'Diferans',
'copyrightwarning'                 => "Tanpri sonje tout piblikasyon ki fèt nan {{SITENAME}} piblye anba kontra $2 an (wè $1 pou konnen plis). Si ou pa vle sa ou ekri pataje oubyen modifye, ou pa dwe soumèt yo isit.<br />
W ap pwomèt tou ke sa w ap ekri a se ou menm menm ki ekri li oubyen ke ou kopye li de yon sous ki nan domèn piblik, ou byen you sous ki lib. '''PA ITILIZE TRAVAY KI ANBA DWA DOTÈ SI OTÈ PA T BAY OTORIZASYON LI TOUTBON !'''",
'copyrightwarning2'                => "Tanpri, konnen ke tout kontribisyon yo nan {{SITENAME}} kapab modifye, change oubyen retire pa lòt itilizatè yo.
Si ou pa vle pou sa ou ekri pataje oubyen modifye, ou pa dwe soumèt li isit.<br />
W ap pwomèt tou ke sa w ap ekri a se ou menm menm ki ekri li oubyen ke ou kopye li de yon sous ki nan domèn piblik, ou byen you sous ki lib (gade $1 pou konnen pi plis).
'''PA ITILIZE TRAVAY KI ANBA DWA DOTÈ SI OTÈ PA T BAY OTORIZASYON LI TOUTBON !'''",
'longpageerror'                    => "'''ERÈ : Tèks ou anrejistre a ap fè $1 Ko, tay sa a depase kapasite limit nou kapab aksepte kounye a: $2 Ko. Nou pa kapab anrejistre tèks sa. Eseye ritounen nan paj ou te ye anvan pou kopye modifikasyon ou yo.'''",
'readonlywarning'                  => "'''Atansyon: Bazdone a bare pou fè travay sou li, kidonk ou pap kapab anrejistre modifikasyon ou yo kounye a.'''
Petèt ou ta renmen kopye-kole teks sa a nan yon fichye teks epi anrejistre l pou pita.

Administratè ki te bare bazdone a te bay rezon sa a: $1",
'protectedpagewarning'             => "'''Pote atansyon : paj sa a pwoteje. Se sèl itilizatè yo ki genyen estati administratè ki kapab modifye l.'''
Dènye jounal la parèt anba kòm referans.",
'semiprotectedpagewarning'         => "'''Note:'' Paj sa a pwoteje e se sèlman itilizatè ki anrejistre ki gendwa modifye li.
Dènye ekriti nan jounal parèt pi ba kòm referans:",
'cascadeprotectedwarning'          => "'''Atansyon:''' Paj sa pwoteje e se sèlman administratè yo ki gendwa modifye l, paske li nan {{PLURAL:$1|paj|paj yo}} ki gen pwoteksyon kaskad aktive sou {{PLURAL:$1|li|yo}}.",
'titleprotectedwarning'            => "'''Atansyon: Paj sa pwoteje e ou bezwen [[Special:ListGroupRights|dwa espesyal]] pou kreye li.'''
Dènye ekriti nan jounal la parèt pi bas kòm referans:",
'templatesused'                    => '{{PLURAL:$1|Modèl|Modèl yo}} ki itilize nan paj sa a :',
'templatesusedpreview'             => '{{PLURAL:$1|Modèl|Modèl yo}} ki itilize nan kout je sa a (previzyalizasyon):',
'templatesusedsection'             => '{{PLURAL:$1|Modèl|Modèl yo}} ki itilize nan seksyon sa :',
'template-protected'               => '(pwoteje)',
'template-semiprotected'           => '(semi-pwoteje)',
'hiddencategories'                 => 'Paj sa ap fè pati {{PLURAL:$1|Kategori kache|Kategori yo ki kache}} :',
'nocreatetitle'                    => 'Kreyasyon paj yo limite',
'nocreatetext'                     => '{{SITENAME}} anpeche kreyasyon nouvo paj sou li. Ou mèt ritounen nan navigatè ou epi modifye yon paj ki deja egziste oubyen [[Special:UserLogin|konekte ou oubyen kreye yon kont]].',
'nocreate-loggedin'                => 'Ou pa gen pèmisyon pou ou kapab kreye nouvo paj nan wiki sa.',
'sectioneditnotsupported-title'    => 'Modifikasyon seksyon pa kapab fèt',
'sectioneditnotsupported-text'     => 'Modifikasyon seksyon pa kapab fèt nan paj sa a.',
'permissionserrors'                => 'Erè nan pèmisyon yo',
'permissionserrorstext'            => 'Ou pa gen otorizasyon pou fè operasyon ke ou mande a pou {{PLURAL:$1|rezon sa|rezon sa yo}} :',
'permissionserrorstext-withaction' => 'Ou pa otorize pou $2, pou {{PLURAL:$1|rezon sa|rezon sa yo}} :',
'recreate-moveddeleted-warn'       => "'''Atansyon : w ap kreye yon pak ki te efase deja.'''

Mande ou byen si ou ap byen fè kreye li ankò.  Gade jounal paj sa a pou konnen poukisa efasman sa te fèt anba:",
'moveddeleted-notice'              => 'Paj sa efase.
Jounal pou efasman oubyen deplasman pou paj sa parèt anba pou sèvi referans.',
'log-fulllog'                      => 'Gade tout jounal la',
'edit-hook-aborted'                => 'Modifikasyon pa ekstansyon pa t reyisi.
Li pa bay rezon pou sa.',
'edit-gone-missing'                => 'Pa kapab mete paj la a jou.
Sanble li te efase.',
'edit-conflict'                    => 'Konfli nan modifikasyon.',
'edit-no-change'                   => 'Modifikasyon ou pa t fèt paske ou pa t fè okenn chanjman nan tèks la.',
'edit-already-exists'              => 'Pa kapab kreye nouvo paj la.
Li ekziste deja.',

# Parser/template warnings
'expensive-parserfunction-warning'        => "'''Atansyon:''' paj sa a gen ladan l twòp apèl ki chè pou fonksyon analizè. 

Li ta dwe gen mwens pase $2 {{PLURAL:$2|apèl|apèl yo}}, aloske kounye a li gen $1.",
'expensive-parserfunction-category'       => 'Paj yo ki gen twòp apèl pou fonksyon analizè ki chè',
'post-expand-template-inclusion-warning'  => "'''Atansyon:''' Genyen twòp modèl ki antre nan paj sa.
Kèk modèl yo pap enkli.",
'post-expand-template-inclusion-category' => 'Paj yo ki genyen twop modèl anndan yo',
'post-expand-template-argument-warning'   => "'''Atansyon:''' Paj sa a gen ladan l omwens youn nan agiman modèl la ki gen yon gwosè ekspansyon ki twòp. 
 Agiman sa yo pa t enkli.",
'post-expand-template-argument-category'  => 'Paj ki genyen agiman pou modèl ki manke',
'parser-template-loop-warning'            => 'Tounen an won te detekte nan modèl la: [[$1]]',
'parser-template-recursion-depth-warning' => 'Limit depase pou kantite fwa yon modèl ka rele tèt li ($1)',
'language-converter-depth-warning'        => 'Limit sou pwofondè konvètisè lang yo depase ($1)',

# "Undo" feature
'undo-summary' => 'Revoke revizyon $1 ki te fèt pa [[Special:Contributions/$2|$2]] ([[User talk:$2|diskite]])',

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
'next'                   => 'pwochen',
'last'                   => 'anvan',
'page_first'             => 'premye',
'page_last'              => 'dènye',
'histlegend'             => 'Seleksyon pou diferans: mete mak nan bouton pou revizyon nou vle konpare epi apiye sou enter oubyen klike sou bouton ki anba.
Lejand : ({{MediaWiki:Cur}}) = diferans ak vèsyon kounye a, ({{MediaWiki:Last}}) = diferans ak vèsyon anvan, <b>m</b> = modifikasyon ki tou piti.',
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
'rev-deleted-comment'        => '(komantè efase)',
'rev-deleted-user'           => '(non itilizatè efase)',
'rev-deleted-event'          => '(antre sa nan jounal efase)',
'rev-delundel'               => 'montre/kache',
'revisiondelete'             => 'Efase/Restore, remèt vèsyon sa',
'revdelete-nooldid-title'    => 'Pa genyen sib, destinasyon pou revizyon sa',
'revdelete-show-file-submit' => 'Wi',
'revdelete-selected'         => "'''{{PLURAL:$2|Vèsyon ou seleksyone|Vèsyon ou seleksyone yo}} de $1 :'''",
'revdelete-legend'           => 'Mete restriksyon nan vizibilite yo :',
'revdelete-hide-text'        => 'Kache tèks ki te modifye',
'revdelete-hide-image'       => 'Kache kontni fichye a',
'revdelete-hide-name'        => 'Kache aksyon an ak sib li',
'revdelete-hide-comment'     => 'Kache komantè sou modifikasyon an',
'revdelete-hide-user'        => 'Kache idantifyan, non itilizatè oubyen adrès IP kontribitè an.',
'revdelete-hide-restricted'  => 'Aplike restriksyon sou done sa yo pou administratè yo epi lòt itilizatè yo tou',
'revdelete-radio-same'       => '(pa chanje)',
'revdelete-radio-set'        => 'Wi',
'revdelete-radio-unset'      => 'Non',
'revdelete-suppress'         => 'Kache revizyon yo tou pou administratè yo',
'revdelete-unsuppress'       => 'Anlve restriksyon yo sou vèsyon yo ki restore',
'revdelete-log'              => 'Poukisa:',
'revdelete-submit'           => 'Aplike sou vèsyon ki seleksyone {{PLURAL:$1|a|yo}}',
'revdelete-logentry'         => 'Vizibilite pou vèsyon sa modifye pou [[$1]]',
'revdel-restore'             => 'Modifye, chanje vizibilite a',
'pagehist'                   => 'Istorik paj sa',
'deletedhist'                => 'Istorik efase',
'revdelete-content'          => 'kontni',
'revdelete-summary'          => 'somè pou modifikasyon',
'revdelete-uname'            => 'non itilizatè',
'revdelete-restricted'       => 'aplike restriksyon sa yo pou administratè yo',
'revdelete-hid'              => 'kache $1',
'revdelete-unhid'            => 'montre $1',

# Merge log
'revertmerge' => 'Separe',

# Diffs
'history-title'           => 'Istorik pou vèsyon « $1 » yo',
'difference'              => '(Diferans ant vèsyon yo)',
'lineno'                  => 'Liy $1 :',
'compareselectedversions' => 'Konpare vèsyon ki seleksyone yo',
'editundo'                => 'Revoke',
'diff-multi'              => '(Genyen {{PLURAL:$1|yon revizyon|$1 revizyon yo}} ki te fèt pa {{PLURAL:$2|yon itilizatè|$2 itilizatè yo}} nan mitan evolisyon ki kache)',

# Search results
'searchresults'             => 'Rezilta yo pou rechèch la',
'searchresults-title'       => 'Rezilta rechèch yo pou « $1 »',
'searchresulttext'          => 'Pou ou kapab konenn plis sou rechèch nan {{SITENAME}}, gade [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'            => "Ou chache « '''[[:$1]]''' » ([[Special:Prefixindex/$1|tout paj yo ki komanse pa« $1 »]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tout paj yo ki genyen lyen vè « $1 »]])",
'searchsubtitleinvalid'     => "Ou chache « '''$1''' »",
'notitlematches'            => 'Pa gen paj nan sistèm ki genyen tit ou bay nan rechèch ou an.',
'notextmatches'             => 'Pa genyen pyès tèks nan paj yo ki ap koresponn ak rechèch ou fè a',
'prevn'                     => '{{PLURAL:$1|$1}} anvan yo',
'nextn'                     => '{{PLURAL:$1|$1}} swivan yo',
'viewprevnext'              => 'Wè ($1 {{int:pipe-separator}} $2) ($3).',
'searchhelp-url'            => 'Help:Èd',
'searchprofile-everything'  => 'Tout',
'search-result-size'        => '$1 ({{PLURAL:$2| mo|$2 mo yo}})',
'search-redirect'           => '(redireksyon depi $1)',
'search-section'            => '(seksyon $1)',
'search-suggest'            => 'Eseye ak òtograf sa : $1',
'search-interwiki-caption'  => 'Pwojè frè, ki ansanm oubyen ki ap deplwaye ansanm',
'search-interwiki-default'  => 'Rezilta yo pou $1 :',
'search-interwiki-more'     => '(plis)',
'search-mwsuggest-enabled'  => 'ak sijesyon, kèk lide',
'search-mwsuggest-disabled' => 'san lide, san endikasyon',
'nonefound'                 => "'''Remak''' : sèl kèk espas non chache nan sityasyon nòmal.
Eseye mete prefiks ''all:'' devan tèm rechèche ou an pou chache nan tout kontni a (sa conprann paj diskisyon yo, modèl yo, etc.) oubyen itilize espas non ou ta renmen pou prefiks.",
'powersearch'               => 'Fouye fon',
'powersearch-legend'        => 'Fouye fon',
'powersearch-ns'            => 'Chache nan espas non sa yo:',
'powersearch-redir'         => 'Montre redireksyon yo',
'powersearch-field'         => 'Chache',

# Preferences page
'preferences'               => 'Preferans yo',
'mypreferences'             => 'Preferans mwen yo',
'skin-preview'              => 'Voye kout je',
'youremail'                 => 'Adrès imèl :',
'username'                  => 'Non itilizatè a:',
'uid'                       => 'Nimewo ID itilizatè a:',
'prefs-memberingroups'      => 'Manm {{PLURAL:$1|nan gwoup sa|nan gwoup sa yo }} :',
'yourrealname'              => 'Vre non ou:',
'yourlanguage'              => 'Lang:',
'yournick'                  => 'Siyati pou espas diskisyon :',
'badsig'                    => 'Siyati ou an pa bon; tcheke baliz HTML ou yo.',
'badsiglength'              => 'Siyati ou an twò long: li pa dwe pi long pase $1 {{PLURAL:$1|karaktè|karaktè}}.',
'gender-male'               => 'Maskilen',
'email'                     => 'Imèl',
'prefs-help-realname'       => 'Vrè non an opsyonèl.
Si ou mete li, n ap itilize li pou nou ka nonmen ou pou kontribisyon ou yo.',
'prefs-help-email'          => 'Adrès imèl pa nesesè, men li ap pèmèt lòt itilizatè yo kontakte w pa imèl (lyen an nan paj itilizatè ou yo); moun sa a pa kapab wè imèl ou an. Imèl sa sèvi tou pou voye mopas ou an lè li rive ou bliye l.',
'prefs-help-email-required' => 'Nou bezwen ou bay yon adrès imèl. Souple, chache yonn.',

# Groups
'group-sysop' => 'Administratè yo',

'grouppage-sysop' => '{{ns:project}}:Administratè',

# User rights log
'rightslog' => 'Jounal modifikasyon estati itilizatè yo',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'modifye paj sa',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|modifikasyon|modifikasyon}}',
'recentchanges'                  => 'Modifikasyon yo ki fèk fèt',
'recentchanges-legend'           => 'Opsyon pou modifikasyon ki fèk fèt',
'recentchanges-feed-description' => 'Swiv dènye modifikasyon pou wiki sa a nan fil sa a (RSS,Atom...)',
'rcnote'                         => "Men {{PLURAL:$1|dènye modifikasyon an|dènye '''$1''' modifikasyon yo}} depi {{PLURAL:$2|dènye jou a|<b>$2</b> dènye jou yo}}, pou jounen $5,$4.",
'rcnotefrom'                     => "Men modifikasyon yo ki fèt depi '''$2''' ('''$1''' dènye).",
'rclistfrom'                     => 'Afiche nouvo modifikasyon yo depi $1.',
'rcshowhideminor'                => '$1 modifiksayon yo ki tou piti',
'rcshowhidebots'                 => '$1 wobo',
'rcshowhideliu'                  => '$1 itilizatè ki konekte',
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
'rc-enhanced-expand'             => 'Montre detay yo (sa mande JavaScript)',
'rc-enhanced-hide'               => 'Kache detay yo',

# Recent changes linked
'recentchangeslinked'          => 'Swivi pou lyen yo',
'recentchangeslinked-feed'     => 'Swivi pou lyen yo',
'recentchangeslinked-toolbox'  => 'Swivi pou lyen yo',
'recentchangeslinked-title'    => 'Chanjman ki an relasyon ak "$1"',
'recentchangeslinked-noresult' => 'Pa genyen pyès chanjman nan paj lye sa yo pou peryòd ou bay la.',
'recentchangeslinked-summary'  => "Paj espesyal sa a ap montre dènye chanjman nan paj ki genyen lyen depi yon paj spesifie (oubyen pou manm you kategori spesifie) yo. Paj yo ki nan [[Special:Watchlist|lis swivi]] ou an ap ekri '''fonse'''",
'recentchangeslinked-page'     => 'Non paj la :',
'recentchangeslinked-to'       => 'Afiche modifikasyon yo ki genyen yon lyen vè paj yo ba ou a plito',

# Upload
'upload'        => 'Chaje yon fichye',
'uploadbtn'     => 'Chaje yon fichye',
'uploadlogpage' => 'Jounal chajman pou fichye yo',
'uploadedimage' => 'chaje « [[$1]] »',

# Special:ListFiles
'listfiles' => 'Lis fichye yo',

# File description page
'file-anchor-link'          => 'Fichye',
'filehist'                  => 'Istorik fichye a',
'filehist-help'             => 'Klike sou yon dat/yon lè pou wè fichye a jan li te ye nan moman sa a.',
'filehist-current'          => 'Kounye a',
'filehist-datetime'         => 'Dat ak lè',
'filehist-thumb'            => 'Minyati',
'filehist-thumbtext'        => 'Minyati pou vèsyon $1',
'filehist-user'             => 'Itilizatè',
'filehist-dimensions'       => 'Grandè yo',
'filehist-filesize'         => 'Gwosè fichye a',
'filehist-comment'          => 'Komantè',
'imagelinks'                => 'Lyen pou fichye sa',
'linkstoimage'              => '{{PLURAL:$1|Paj ki ap swiv la|Paj yo ki ap swiv}} genyen yon lyen pou fichye sa a :',
'nolinkstoimage'            => 'Pa gen pyès paj ki gen yon lyen pou imaj sa a.',
'sharedupload'              => 'Fichye sa a kapab pataje, li sòti depi $1 e li kapab itilize pa lòt pwojè yo.',
'uploadnewversion-linktext' => 'Chaje yon nouvo vèsyon pou fichye sa a',

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

'fewestrevisions' => 'Paj yo ki genyen mwens modifikasyon yo',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|okte|okte}}',
'nlinks'                  => '$1 {{PLURAL:$1|lyen|lyen yo}}',
'nmembers'                => '$1 {{PLURAL:$1|manm|manm yo}}',
'lonelypages'             => 'Paj ki òfelen',
'uncategorizedpages'      => 'Paj yo ki pa genyen kategori',
'uncategorizedcategories' => 'Kategori yo ki pa genyen tit/kategori',
'uncategorizedimages'     => 'Fichye ki pa kategorize',
'uncategorizedtemplates'  => 'Modèl yo ki pa genyen kategori',
'unusedcategories'        => 'Kategori yo ki pa itilize',
'unusedimages'            => 'Fichye yo ki pa itilize',
'wantedcategories'        => 'Kategori yo ke moun mande plis',
'wantedpages'             => 'Paj yo ki plis mande',
'mostlinked'              => 'Paj yo ki genyen plis lyen nan lòt paj yo',
'mostlinkedcategories'    => 'Kategori yo ki plis itilize',
'mostlinkedtemplates'     => 'Modèl yo ki plis itilize',
'mostcategories'          => 'Paj yo ki genyen plis kategori',
'mostimages'              => 'Fichye yo ki plis itilize',
'mostrevisions'           => 'Paj yo ki plis modifye',
'prefixindex'             => 'Tout paj yo ak prefiks',
'shortpages'              => 'Paj kout yo',
'longpages'               => 'Paj ki long',
'deadendpages'            => 'Paj yo ki pa gen lyen nan yo',
'protectedpages'          => 'Paj ki pwoteje yo',
'listusers'               => 'Lis itilizatè yo',
'newpages'                => 'Nouvo paj yo',
'ancientpages'            => 'Atik ki pli vye yo',
'move'                    => 'Deplase',
'movethispage'            => 'Deplase paj sa a',
'pager-newer-n'           => '{{PLURAL:$1|ki fèk fèt|$1 ki fèk fèt yo}}',
'pager-older-n'           => '{{PLURAL:$1|pi vye|$1 pi vye yo}}',

# Book sources
'booksources'               => 'Ouvraj referans yo',
'booksources-search-legend' => 'Chache nan lis ouvraj ki sèvi pou referans',
'booksources-go'            => 'Ale',

# Special:Log
'specialloguserlabel'  => 'itilizatè :',
'speciallogtitlelabel' => 'Tit :',
'log'                  => 'Jounal yo',
'all-logs-page'        => 'Tout jounal yo (istorik yo)',

# Special:AllPages
'allpages'       => 'Tout paj yo',
'alphaindexline' => '$1 jiska $2',
'nextpage'       => 'Paj swivan ($1)',
'prevpage'       => 'Paj presedan ($1)',
'allpagesfrom'   => 'Afiche paj yo depi :',
'allpagesto'     => 'Montre paj yo jiska :',
'allarticles'    => 'tout atik yo',
'allpagessubmit' => 'Ale',
'allpagesprefix' => 'Montre paj yo ki ap komanse pa prefiks sa a :',

# Special:Categories
'categories'                    => 'Kategori yo',
'categoriespagetext'            => 'Kategori ki ap swiv {{PLURAL:$1|la|yo}} gen lòt paj oubien medya nan yo.
[[Special:UnusedCategories|Kategori ki pa itilize]] pa parèt la.
Gade tou [[Special:WantedCategories|kategori moun mande]].',
'special-categories-sort-count' => 'klase pa valè',
'special-categories-sort-abc'   => 'klase alfabetikalman',

# Special:LinkSearch
'linksearch' => 'Lyen andeyò',

# Special:Log/newusers
'newuserlogpage'              => 'Jounal pou kreyasyon kont itilizatè yo',
'newuserlogpagetext'          => 'Men jounal, istorik kreyasyon kont itilizatè yo.',
'newuserlog-byemail'          => 'mopas an voye pa imèl',
'newuserlog-create-entry'     => 'Nouvo kont itilizatè',
'newuserlog-create2-entry'    => 'te kreye kont $1',
'newuserlog-autocreate-entry' => 'Kont sa kreye otomatikman',

# Special:ListGroupRights
'listgrouprights-members' => '(lis manm yo)',

# E-mail user
'emailuser' => 'Voye yon mesaj (imèl) pou itilizatè sa a',

# Watchlist
'watchlist'         => 'Lis swivi mwen',
'mywatchlist'       => 'Lis swivi mwen',
'addedwatch'        => 'Ajoute nan lis swivi',
'addedwatchtext'    => 'Paj « [[:$1]] » te byen ajoute nan [[Special:Watchlist|lis swivi ou an]].
Depi kounye a, tout modifikasyon nan paj sa a ak nan paj diskisyon li pral parèt <b>fonse</b> nan [[Special:RecentChanges|lis chanjman ki fèk fèt]] pou ou ka wè yo pi byen.',
'removedwatch'      => 'Retire nan lis swivi',
'removedwatchtext'  => 'Paj "[[:$1]]" byen retire nan [[Special:Watchlist|lis swivi ou an]].',
'watch'             => 'Swiv',
'watchthispage'     => 'Swiv paj sa a',
'unwatch'           => 'Pa swiv ankò',
'watchlist-details' => 'W ap swiv {{PLURAL:$1|paj|paj}}, san konte paj diskisyon yo.',
'wlshowlast'        => 'Montre dènye $1 è yo, dènye $2 jou yo, oubyen $3.',
'watchlist-options' => 'Opsyon pou lis swivi',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Swiv...',
'unwatching' => 'Fini swiv paj sa a...',

# Delete
'deletepage'            => 'Efase yon paj',
'historywarning'        => "'''Atansyon:''' paj w ap efase a genyen yon istorik ki genyen $1 {{PLURAL:$1|revizyon|revizyon yo}} ladan l:",
'confirmdeletetext'     => 'W ap efase yon paj oubyen yon imaj epi tout vèsyon li yo toutbon nan bazdone a. Tanpri, konfime aksyon enpòtan sa a, ke ou konprann sa w ap fè, epi ke ou fè l nan dwa ak [[{{MediaWiki:Policy-url}}|lwa medyawiki a]].',
'actioncomplete'        => 'Aksyon an fèt',
'deletedtext'           => '« <nowiki>$1</nowiki> » efase.
Gade $2 pou wè yon lis efasman resan.',
'deletedarticle'        => 'efase « [[$1]] »',
'dellogpage'            => 'Jounal efasman yo',
'deletecomment'         => 'Rezon:',
'deleteotherreason'     => 'Rezon an plis :',
'deletereasonotherlist' => 'Lòt rezon',

# Rollback
'rollbacklink' => 'revoke',

# Protect
'protectlogpage'              => 'Jounal pwoteksyon yo',
'protectedarticle'            => 'pwoteje « [[$1]] »',
'modifiedarticleprotection'   => 'te modifye nivo pwoteksyon pou « [[$1]] »',
'prot_1movedto2'              => '[[$1]] te deplase vè [[$2]]',
'protect-legend'              => 'Konfime pwoteksyon an',
'protectcomment'              => 'Rezon:',
'protectexpiry'               => 'Ekspirasyon:',
'protect_expiry_invalid'      => 'Dat ou mete a pou li ekspire pa bon',
'protect_expiry_old'          => 'Dat ekspirasyon an deja pase.',
'protect-text'                => "Ou mèt konsilte epi modifye nivo pwoteksyon paj '''<nowiki>$1</nowiki>''' isit.",
'protect-locked-access'       => "Ou pa genyen dwa ki ap pèmèt ou modifye pwoteksyon paj sa a.
Men reglaj pou paj '''$1''' an kounye a:",
'protect-cascadeon'           => 'Paj sa a pwoteje kounye a paske li nan {{PLURAL:$1|paj|paj yo}}, ki gen opsyon pwoteksyon "enbrike" aktif. Ou mèt chanje nivo pwoteksyon paj sa a men li pap modifye pwoteksyon enbrike an.',
'protect-default'             => 'Otorize tout itilizatè yo',
'protect-fallback'            => 'Li bezwen pèmisyon "$1"',
'protect-level-autoconfirmed' => 'Bloke itilizatè nouvo yo ak sa ki pa anrejistre nan sistèm an',
'protect-level-sysop'         => 'Administratè sèlman',
'protect-summary-cascade'     => 'pwoteksyon enbrike',
'protect-expiring'            => 'ap ekspire $1',
'protect-cascade'             => 'Mete pwoteksyon enbrike pou tout paj ki anndan paj sa a.',
'protect-cantedit'            => 'Ou pa kapab modifye nivo pwoteksyon paj sa a paske ou pa gen dwa pou edite li.',
'protect-expiry-options'      => '1 èdtan:1 hour,1 jou:1 day,1 semèn:1 week,2 semèn:2 weeks,1 mwa:1 month,3 mwa:3 months,6 mwa:6 months,1 lane:1 year,ki pap janm fini:infinite',
'restriction-type'            => 'Pèmisyon:',
'restriction-level'           => 'Nivo kontrent, restriksyon:',

# Undelete
'undeletebtn'      => 'Retabli',
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
'contribsub2'         => 'Lis kontribisyon $1 ($2).',
'uctop'               => '(tèt)',
'month'               => 'depi mwa (ak mwa anvan yo) :',
'year'                => 'Depi lane (ak anvan tou) :',

'sp-contributions-newbies'     => 'Montre sèlman kontribisyon nouvo itilizatè yo',
'sp-contributions-newbies-sub' => 'Pou nouvo kont yo',
'sp-contributions-blocklog'    => 'jounal blokaj yo',
'sp-contributions-talk'        => 'Diskite',
'sp-contributions-search'      => 'Chache kontribisyon yo',
'sp-contributions-username'    => 'Adrès IP oubyen non itilizatè:',
'sp-contributions-submit'      => 'Chache',

# What links here
'whatlinkshere'            => 'Paj ki gen lyen vè paj sa a',
'whatlinkshere-title'      => 'Paj ki genyen lyen ki ap mennen nan "$1"',
'whatlinkshere-page'       => 'Paj :',
'linkshere'                => 'Paj yo ki anba ap mene nan <b>[[:$1]]</b> :',
'nolinkshere'              => 'Pyès paj genyen lyen pou paj sa a <b>[[:$1]]</b>.',
'isredirect'               => 'Paj redireksyon',
'istemplate'               => 'anndan',
'isimage'                  => 'lyen pou fichye imaj sa',
'whatlinkshere-prev'       => '{{PLURAL:$1|presedan|$1 presedan yo}}',
'whatlinkshere-next'       => '{{PLURAL:$1|swivan|$1 swivan yo}}',
'whatlinkshere-links'      => '← lyen yo',
'whatlinkshere-hideredirs' => '$1 redireksyon yo',
'whatlinkshere-hidetrans'  => '$1 kontni anndan li',
'whatlinkshere-hidelinks'  => '$1 lyen yo',
'whatlinkshere-filters'    => 'Filt yo',

# Block/unblock
'blockip'                  => 'Bloke yon adrès IP oubyen yon itilizatè',
'ipboptions'               => '2 èdtan:2 hours,1 jou:1 day,3 jou:3 days,1 semèn:1 week,2 semèn:2 weeks,1 mwa:1 month,3 mwa:3 months,6 mwa:6 months,1 lane:1 year,ki pap janm fini:infinite',
'ipblocklist'              => 'Lis IP ak itilizatè yo ki bloke',
'blocklink'                => 'Bloke',
'unblocklink'              => 'Debloke',
'change-blocklink'         => 'Modifye blokaj nan',
'contribslink'             => 'Kontribisyon yo',
'blocklogpage'             => 'Istorik blokaj yo',
'blocklogentry'            => 'te bloke « [[$1]] » - rive : $2 $3',
'unblocklogentry'          => 'debloke $1',
'block-log-flags-nocreate' => 'kreyasyon kont pa otorize',

# Move page
'move-page-legend' => 'Deplase paj sa',
'movepagetext'     => "Itilize fòmilè sa a pou renonmen yon paj epi deplase tout istorik li nan nouvo non an.
Tit anvan pral vin yon paj redireksyon pou ale nan nouvo paj nan.
Ou kapab mete a jou lyen yo ki t ap voye vè tit anvan otomatikman.
Si nou pa vle fè sa, gade byen [[Special:DoubleRedirects|de fwa]] ak [[Special:BrokenRedirects|redireksyon ki pa bon]] pou nou sèten nou pa kreye redireksyon de fwa oubyen yon lòt kalite redireksyon ki pa bon.
Se ou menm ki responsab pou lyen yo toujou kontinye ale nan paj kote yo sipoze a.

Yon paj pa ka deplase si nouvo paj an egziste deja, eksepte si paj sa vid oubyen ke li se yon paj redireksyon (fok li pa genyen yon istorik nan li tou).  Sa vle di ke ou kapab renonme yon paj nan tit orijinal li si nou te fè yon erè, men nou pa ka ekraze yon paj ki deja ekziste.

'''Pòte Atansyon !'''
Sa w ap fè a kapab pwovoke yon gwo chanjman pou yon paj ki popilè.
Se pou nou byen konprann e nou byen mezire tout konsekans aksyon sa ke n ap fè a.",
'movepagetalktext' => 'Paj diskisyon ki asosye, si li egziste, pral otomatikman renonmen tou, <b>eksepte si: </b>
*W ap renonmen yon paj nan direksyon yon lòt espas
*yon paj diskisyon ki pa vid deja ekziste nan nouvo non an
*Ou deseleksyone bouton ki anba mesaj sa a

Nan ka sa yo, ou dwe renonmen oubyen fizyone paj la ou menm si ou vle.',
'movearticle'      => 'Deplase paj sa :',
'newtitle'         => 'Nouvo tit, non:',
'move-watch'       => 'Swiv paj orijinal ak nouvo paj yo',
'movepagebtn'      => 'Deplase paj sa',
'pagemovedsub'     => 'Deplasman an fèt',
'movepage-moved'   => '\'\'\'"$1" te deplase nan "$2"\'\'\'',
'articleexists'    => 'Deja genyen yon atik ak non sa a oubyen non ou chwazi a pa valab. Chwazi yon lòt non.',
'talkexists'       => 'Paj nan te byen deplase, men paj diskisyon an pa t deplase paske te deja ekziste youn anlè nouvo paj la. Tanpri, fizyone de paj diskisyon sa yo ou menm.',
'movedto'          => 'deplase nan',
'movetalk'         => 'Renonmen ak deplase paj diskisyon an tou',
'1movedto2'        => 'te deplase [[$1]] vè [[$2]]',
'1movedto2_redir'  => 'te deplase [[$1]] vè [[$2]], nan menm moman, li ekraze redireksyon li',
'movelogpage'      => 'Jounal deplasman yo',
'movereason'       => 'Rezon:',
'revertmove'       => 'revoke',

# Export
'export' => 'Ekspòte paj yo',

# Namespace 8 related
'allmessages' => 'Mesaj sistèm yo',

# Thumbnails
'thumbnail-more'  => 'Agrandi',
'thumbnail_error' => 'Erè nan kreyasyon minyati : $1',

# Import log
'importlogpage' => 'Jounal pou enpòtasyon yo',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Paj itilizatè ou an',
'tooltip-pt-mytalk'               => 'Paj diskisyon ou an',
'tooltip-pt-preferences'          => 'Preferans ou yo',
'tooltip-pt-watchlist'            => 'Lis paj w ap swiv yo',
'tooltip-pt-mycontris'            => 'Lis kontribisyon ou yo',
'tooltip-pt-login'                => 'Nou ankourage ou pou ou konèkte; men ou pa oblije.',
'tooltip-pt-logout'               => 'Dekonekte ou',
'tooltip-ca-talk'                 => 'Diskisyon apwopo kontni paj sa a',
'tooltip-ca-edit'                 => 'Ou mèt modifye paj sa a. Tanpri, itilize bouton "Kout je" anvan ou anrejistre.',
'tooltip-ca-addsection'           => 'Komanse yon nouvo seksyon',
'tooltip-ca-viewsource'           => 'Paj sa a pwoteje. Ou kapab wè kòd sous li.',
'tooltip-ca-history'              => 'Vèsyon ki ansyen pou paj sa (ak tout kontribitè ki te travay sou li)',
'tooltip-ca-protect'              => 'Pwoteje paj sa a',
'tooltip-ca-delete'               => 'Efase paj sa a',
'tooltip-ca-move'                 => 'Deplase paj sa a',
'tooltip-ca-watch'                => 'Ajoute paj sa a nan lis swivi ou a',
'tooltip-ca-unwatch'              => 'Retire paj sa a nan lis swivi ou an',
'tooltip-search'                  => 'Fouye nan {{SITENAME}}',
'tooltip-search-go'               => 'Ale sou yon paj ki pòte egzateman non sa si li egziste',
'tooltip-search-fulltext'         => 'Chache paj yo ki genyen tèks sa.',
'tooltip-n-mainpage'              => 'Vizite paj prensipal la',
'tooltip-n-mainpage-description'  => 'Vizite paj prensipal la',
'tooltip-n-portal'                => 'Apwopo pwojè a, sa ou kapab fè, ki kote ou mèt jwenn kèk bagay',
'tooltip-n-currentevents'         => 'Jwenn enfòmasyon de baz sou evènman ki ap fèt kounye a',
'tooltip-n-recentchanges'         => 'Lis modifikasyon ki fèk fèt nan wiki a',
'tooltip-n-randompage'            => 'Afiche yon paj o aza',
'tooltip-n-help'                  => 'Èd',
'tooltip-t-whatlinkshere'         => 'Lis tout paj ki gen lyen vè paj sa a',
'tooltip-t-recentchangeslinked'   => 'Lis modifikasyon ki fèk fèt pou paj yo ki genyen lyen nan paj sa a',
'tooltip-feed-rss'                => 'Fil RSS pou paj sa a',
'tooltip-feed-atom'               => 'Fil Atom pou paj sa a',
'tooltip-t-contributions'         => 'Wè lis kontribisyon itilizatè sa a',
'tooltip-t-emailuser'             => 'Voye yon imèl pou itilizatè sa a',
'tooltip-t-upload'                => 'Chaje yon fichye',
'tooltip-t-specialpages'          => 'Lis tout paj espesyal yo',
'tooltip-t-print'                 => 'Vèsyon ou kapab enprime pou paj sa',
'tooltip-t-permalink'             => 'Lyen pèmanan ki ap mennen ou nan vèsyon sa pou paj sa',
'tooltip-ca-nstab-main'           => 'Wè paj kontni a',
'tooltip-ca-nstab-user'           => 'Wè paj itilizatè',
'tooltip-ca-nstab-special'        => 'Paj sa se yon paj espesyal, ou pa kapab modifye li.',
'tooltip-ca-nstab-project'        => 'Wè paj pwojè a',
'tooltip-ca-nstab-image'          => 'Gade paj fichye a',
'tooltip-ca-nstab-template'       => 'Wè modèl an',
'tooltip-ca-nstab-help'           => 'Wè paj èd an',
'tooltip-ca-nstab-category'       => 'Wè paj kategori a',
'tooltip-minoredit'               => 'make modifikasyon sa a tou piti',
'tooltip-save'                    => 'Sove modifikasyon ou yo',
'tooltip-preview'                 => 'Tanpri, fè yon kout je sou paj ou an (previzyalize li) anvan ou anrejistre li!',
'tooltip-diff'                    => 'Montre ki chanjman ou fè nan tèks an.',
'tooltip-compareselectedversions' => 'Afiche diferans ant de vèsyon paj sa a ou seleksyone.',
'tooltip-watch'                   => 'Ajoute paj sa a nan lis swivi ou an',
'tooltip-rollback'                => '« Revoke » ap anile ak yon sèl klik modifikasyon dènye kontribitè te fè sou paj sa a',
'tooltip-undo'                    => '« Revoke » ap efase modifikasyon sa epi li ap ouvri fenèt modifikasyon an nan mòd kote ou kapab wè sa sa ou fè a ap bay.
Li pèmèt retabli vèsyon ki te anvan li epi ajoute yon rezon ki esplike poukisa ou revoke modifikasyon sa nan bwat rezime a.',

# Browsing diffs
'previousdiff' => '← Modifikasyon presedan',
'nextdiff'     => 'Modifikasyon swivan →',

# Media information
'file-info-size' => '$1 × $2 piksèl, gwosè fichye a : $3, tip MIME li ye : $4',
'file-nohires'   => '<small>Pa genyen rezolisyon ki pi wo ki disponib.</small>',
'svg-long-desc'  => 'Fichye SVG, rezolisyon de $1 × $2 piksèl, gwosè fichye : $3',
'show-big-image' => 'Pi bon rezolisyon',

# Special:NewFiles
'newimages' => 'Galri pou nouvo fichye yo',

# Bad image list
'bad_image_list' => 'Fòma la se konsa :

Se itèm ki nan lis sèlman (liy ki kòmanse ak *) ki konsidere.
Premye lyen nan yon liy sipoze yon lyen pou yon move dosye.
Nenpòt lòt lyen nan menm liy nan konsidere kòm yon eksèpsyon, i.e. paj kote yon dosye ka parèt nan lign.',

# Metadata
'metadata'          => 'Metadone',
'metadata-help'     => 'Fichye sa genyen enfòmasyon adisyonèl, petèt ki soti nan yon kamera dijital oubyen yon nimerizè itilize pou kreye oubyen dijitalize li.  Si fichye sa te modifye depi kreyasyon li, kèk detay ka pa menm avèk original la.',
'metadata-expand'   => 'Montre detay konplè yo',
'metadata-collapse' => 'Kache enfòmasyon ak tout detay yo',
'metadata-fields'   => 'Chan metadone EXIF ki liste nan mesaj sa a ap parèt nan paj deskripsyon imaj la lè tab metadone a ap pi piti. Lòt chan yo ap kache pa defo.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Modifye fichye sa a nan aplikasyon pa ou (ki pa nan sistèm an, sou machin ou pa egzanp).',
'edit-externally-help' => '(Gade [http://www.mediawiki.org/wiki/Manual:External_editors komand ak enstriksyon yo] pou plis enfòmasyon oubyen pou konnen plis)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'tout',
'namespacesall' => 'Tout',
'monthsall'     => 'tout',

# Watchlist editing tools
'watchlisttools-view' => 'Wè chanjman enpòtan yo',
'watchlisttools-edit' => 'Wè epi modifye tou lis swivi',
'watchlisttools-raw'  => 'Modifye lis swivi (mòd bazik)',

# Special:Version
'version' => 'Vèsyon',

# Special:SpecialPages
'specialpages' => 'Paj espesyal yo',

# HTML forms
'htmlform-reset' => 'Revoke chanjman yo',

);
