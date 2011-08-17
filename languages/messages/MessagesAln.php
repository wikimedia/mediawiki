<?php
/** Gheg Albanian (Gegë)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bresta
 * @author Cradel
 * @author Dardan
 * @author Mdupont
 */

$fallback = 'sq';

$namespaceNames = array(
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Diskutim',
	NS_USER             => 'Përdorues',
	NS_USER_TALK        => 'Përdoruesi_diskutim',
	NS_PROJECT_TALK     => '$1_diskutim',
	NS_FILE             => 'Skeda',
	NS_FILE_TALK        => 'Skeda_diskutim',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskutim',
	NS_TEMPLATE         => 'Stampa',
	NS_TEMPLATE_TALK    => 'Stampa_diskutim',
	NS_HELP             => 'Ndihmë',
	NS_HELP_TALK        => 'Ndihmë_diskutim',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Kategoria_diskutim',
);

$namespaceAliases = array(
	'Perdoruesi'          => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
	'Përdoruesi'          => NS_USER,
	'Përdoruesi_diskutim' => NS_USER_TALK,
	'Figura'              => NS_FILE,
	'Figura_diskutim'     => NS_FILE_TALK,
	'Kategori'            => NS_CATEGORY,
	'Kategori_Diskutim'   => NS_CATEGORY_TALK
);

$specialPageAliases = array(
	'Popularpages'              => array( 'Faqe të famshme' ),
	'Search'                    => array( 'Kërko' ),
);

$magicWords = array(
	'currentmonth'          => array( '1', 'MUEJIAKTUAL', 'MUEJIAKTUAL2', 'MUAJIMOMENTAL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'MUEJIAKTUAL1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'EMNIMUEJITAKTUAL', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ),
	'currenttime'           => array( '1', 'KOHATASH', 'KOHATANI', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ORATASH', 'ORATANI', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MUEJILOKAL', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'img_center'            => array( '1', 'qendër', 'qendrore', 'qëndër', 'qëndrore', 'center', 'centre' ),
	'img_baseline'          => array( '1', 'vijabazë', 'linjabazë', 'baseline' ),
	'servername'            => array( '0', 'EMNISERVERIT', 'EMRIISERVERIT', 'SERVERNAME' ),
	'currentweek'           => array( '1', 'JAVAAKTUALE', 'JAVAMOMENTALE', 'CURRENTWEEK' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Nënvizoji vegzat',
'tog-highlightbroken'         => 'Shkrueji vegzat për në faqe të zbrazëta <a href="" class="new">kështu </a> (ndryshe: kështu<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Drejto kryerreshtat',
'tog-hideminor'               => 'Mshef redaktimet e vogla të bâme së voni',
'tog-hidepatrolled'           => 'Mshef redaktimet e mbikëqyruna në ndryshimet e fundit',
'tog-newpageshidepatrolled'   => 'Mshef redaktimet e mbikëqyruna prej listës së faqeve të reja',
'tog-extendwatchlist'         => 'Zgjâno listën e mbikëqyrjeve që me i pa tâna ndryshimet, jo veç ato mâ të fresktat',
'tog-usenewrc'                => 'Përdor ndryshimet e freskëta me vegla të përparueme (lyp JavaScript)',
'tog-numberheadings'          => 'Numëro automatikisht mbititujt',
'tog-showtoolbar'             => 'Trego butonat për redaktim (JavaScript)',
'tog-editondblclick'          => 'Redakto faqet me klikim të dyfishtë (JavaScript)',
'tog-editsection'             => 'Lejo redaktimin e seksioneve me opcionin [redakto]',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve tue klikue me të djathtë mbi titull (JavaScript)',
'tog-showtoc'                 => 'Trego përmbajtjen<br />(për faqet me mâ shum se 3 tituj)',
'tog-rememberpassword'        => 'Ruej fjalëkalimin tem në këtë shfletues (për $1 {{PLURAL:$1|ditë|ditë}})',
'tog-watchcreations'          => 'Shtoji në listë mbikëqyrëse faqet që i krijoj vetë',
'tog-watchdefault'            => 'Shtoji në listë mbikëqyrëse faqet që i redaktoj',
'tog-watchmoves'              => 'Shtoji në listë mbikëqyrëse faqet që i zhvendosi',
'tog-watchdeletion'           => 'Shtoji në listë mbikëqyrëse faqet që i fshij',
'tog-minordefault'            => 'Shêjoji fillimisht tâna redaktimet si të vogla',
'tog-previewontop'            => 'Vendose parapamjen përpara kutisë redaktuese',
'tog-previewonfirst'          => 'Shfaqe parapamjen në redaktimin e parë',
'tog-nocache'                 => 'Mos ruej kopje të faqeve',
'tog-enotifwatchlistpages'    => 'Njoftomë me email, kur ndryshojnë faqet e mbikëqyruna',
'tog-enotifusertalkpages'     => 'Njoftomë me email kur ndryshon faqja ime e diskutimit',
'tog-enotifminoredits'        => 'Njoftomë me email për redaktime të vogla të faqeve',
'tog-enotifrevealaddr'        => 'Shfaqe adresën time në emailat njoftues',
'tog-shownumberswatching'     => 'Shfaqe numrin e përdoruesve mbikëqyrës',
'tog-oldsig'                  => 'Parapamja e nënshkrimit ekzistues:',
'tog-fancysig'                => 'Trajto nënshkrimin si tekst (pa vegëz automatike)',
'tog-externaleditor'          => 'Përdor program të jashtëm për redaktim (vetëm për eksperta, lyp përcaktime speciale në kompjuterin tuej)',
'tog-externaldiff'            => 'Përdor program të jashtëm për dallime (vetëm për eksperta, lyp përcaktime speciale në kompjuterin tuej)',
'tog-showjumplinks'           => 'Lejo lidhjet é afrueshmerisë "kapërce tek"',
'tog-uselivepreview'          => 'Trego parapamjén meniheré (JavaScript) (Eksperimentale)',
'tog-forceeditsummary'        => 'Pyetem kur e le përmbledhjen e redaktimit zbrazt',
'tog-watchlisthideown'        => "M'sheh redaktimet e mia nga lista mbikqyrëse",
'tog-watchlisthidebots'       => 'Mshef redaktimet e robotave nga lista e vrojtimit',
'tog-watchlisthideminor'      => 'Mshef redaktimet e vogla nga lista e vrojtimit',
'tog-watchlisthideliu'        => "Mshef redaktimet e përdoruesve t'kyçun prej listës së vrojtimit",
'tog-watchlisthideanons'      => 'Mshef redaktimet e anonimëve prej listës së vrojtimit',
'tog-watchlisthidepatrolled'  => 'Mshef redaktimet e mbikëqyruna prej listës së vrojtimit',
'tog-nolangconversion'        => 'Mos lejo konvertimin e variantëve',
'tog-ccmeonemails'            => 'Më ço kopje të mesazheve qi ua dërgoj të tjerëve',
'tog-diffonly'                => 'Mos e trego përmbajtjen e faqes nën ndryshimin',
'tog-showhiddencats'          => 'Trego kategoritë e mshefta',
'tog-noconvertlink'           => 'Mos lejo konvertimin e titullit vegëz',
'tog-norollbackdiff'          => 'Trego ndryshimin mbas procedurës së kthimit mbrapa',

'underline-always'  => 'gjithmonë',
'underline-never'   => 'kurrë',
'underline-default' => 'njisoj si shfletuesi',

# Font style option in Special:Preferences
'editfont-style'     => 'Redakto stilin e fontit të fushës:',
'editfont-default'   => 'Parazgjedhja e browserit',
'editfont-monospace' => "Font me hapësinë t'njitrajtshme",
'editfont-sansserif' => 'Font pa serifa',
'editfont-serif'     => 'Font me serifa',

# Dates
'sunday'        => 'E diel',
'monday'        => 'E hâne',
'tuesday'       => 'E marte',
'wednesday'     => 'E mërkure',
'thursday'      => 'E êjte',
'friday'        => 'E premte',
'saturday'      => 'E shtune',
'sun'           => 'Dje',
'mon'           => 'Hân',
'tue'           => 'Mar',
'wed'           => 'Mër',
'thu'           => 'Êjt',
'fri'           => 'Pre',
'sat'           => 'Sht',
'january'       => 'kallnor',
'february'      => 'shkurt',
'march'         => 'mars',
'april'         => 'prill',
'may_long'      => 'maj',
'june'          => 'qershor',
'july'          => 'korrik',
'august'        => 'gusht',
'september'     => 'shtator',
'october'       => 'tetor',
'november'      => 'nandor',
'december'      => 'dhetor',
'january-gen'   => 'kallnorit',
'february-gen'  => 'shkurtit',
'march-gen'     => 'marsit',
'april-gen'     => 'prillit',
'may-gen'       => 'majit',
'june-gen'      => 'qershorit',
'july-gen'      => 'korrikut',
'august-gen'    => 'gushtit',
'september-gen' => 'shtatorit',
'october-gen'   => 'tetorit',
'november-gen'  => 'nândorit',
'december-gen'  => 'dhetorit',
'jan'           => 'Kall',
'feb'           => 'Shk',
'mar'           => 'Mar',
'apr'           => 'Pri',
'may'           => 'Maj',
'jun'           => 'Qer',
'jul'           => 'Korr',
'aug'           => 'Gush',
'sep'           => 'Sht',
'oct'           => 'Tet',
'nov'           => 'Nan',
'dec'           => 'Dhe',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategoritë}}',
'category_header'                => 'Artikuj në kategorinë "$1"',
'subcategories'                  => 'Nënkategori',
'category-media-header'          => 'Media në kategori "$1"',
'category-empty'                 => "''Kjo kategori tashpërtash nuk përmban asnji faqe apo media.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori e msheftë|Kategori të mshefta}}',
'hidden-category-category'       => 'Kategori të mshefta',
'category-subcat-count'          => '{{PLURAL:$2|Kjo kategori ka vetëm këtë nënkategori.|Kjo kategori ka {{PLURAL:$1|këtë nënkategori|$1 këto nënkategori}}, nga gjithsejt $2.}}',
'category-subcat-count-limited'  => 'Kjo kategori ka {{PLURAL:$1|këtë nënkategori|$1 këto nënkategori}}.',
'category-article-count'         => '{{PLURAL:$2|Kjo kategori ka vetëm këtë faqe.|Kjo kategori ka {{PLURAL:$1|këtë faqe|$1 faqe}} nga gjithsejt $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|Faqja âsht|$1 faqe janë}} në këtë kategori.',
'category-file-count'            => '{{PLURAL:$2|Kjo kategori ka vetëm këtë skedë.|{{PLURAL:$1|kjo skedë âsht|$1 skeda janë}} në këtë kategori, prej gjithsejt $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Kjo skedë âsht|$1 skeda janë}} në këtë kategori.',
'listingcontinuesabbrev'         => 'vazh.',
'index-category'                 => 'Faqe e indeksume',
'noindex-category'               => 'Faqe e paindeksume',

'mainpagetext'      => "'''MediaWiki software u instalue me sukses.'''",
'mainpagedocfooter' => 'Për mâ shumë informata rreth përdorimit të softwareit wiki, ju lutem shikoni [http://meta.wikimedia.org/wiki/Help:Contents dokumentacionin].


== Për fillim ==

* [http://www.mediawiki.org/wiki/Help:Configuration_settings Konfigurimi i MediaWikit]
* [http://www.mediawiki.org/wiki/Help:FAQ Pyetjet e shpeshta rreth MediaWikit]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Njoftime rreth MediaWikit]',

'about'         => 'Rreth',
'article'       => 'Artikulli',
'newwindow'     => '(çelet në nji dritare të re)',
'cancel'        => 'Harroje',
'moredotdotdot' => 'Mâ shumë...',
'mypage'        => 'Faqja jeme',
'mytalk'        => 'Diskutimet e mija',
'anontalk'      => 'Diskutimet për këtë IP',
'navigation'    => 'Lundrimi',
'and'           => '&#32;dhe',

# Cologne Blue skin
'qbfind'         => 'Kërko',
'qbbrowse'       => 'Shfleto',
'qbedit'         => 'Redakto',
'qbpageoptions'  => 'Kjo faqe',
'qbpageinfo'     => 'Konteksti',
'qbmyoptions'    => 'Faqet e mija',
'qbspecialpages' => 'Faqet speciale',
'faq'            => 'Pyetjet e shpeshta',
'faqpage'        => 'Project:Pyetjet e shpeshta',

# Vector skin
'vector-action-addsection'       => 'Shto temë',
'vector-action-delete'           => 'Fshij',
'vector-action-move'             => 'Zhvendos',
'vector-action-protect'          => 'Mbroj',
'vector-action-undelete'         => 'Kthe fshimjen mbrapsht',
'vector-action-unprotect'        => 'Hiq mbrojtjen',
'vector-simplesearch-preference' => 'Aktivizo sugjerime të avancueme në kërkim (vetëm për Vector skin)',
'vector-view-create'             => 'Krijo',
'vector-view-edit'               => 'Redakto',
'vector-view-history'            => 'Shih historinë',
'vector-view-view'               => 'Lexo',
'vector-view-viewsource'         => 'Shih kodin',
'actions'                        => 'Veprimet',
'namespaces'                     => 'Hapësinat',
'variants'                       => 'Variantet',

'errorpagetitle'    => 'Gabim',
'returnto'          => 'Kthehu te $1.',
'tagline'           => 'Nga {{SITENAME}}',
'help'              => 'Ndihmë',
'search'            => 'Lyp',
'searchbutton'      => 'Lype',
'go'                => 'Shko',
'searcharticle'     => 'Çele',
'history'           => 'Historiku i faqes',
'history_short'     => 'Të përparshmet',
'updatedmarker'     => 'ndryshue nga vizita jeme e fundit',
'info_short'        => 'Informacion',
'printableversion'  => 'Version për shtyp',
'permalink'         => 'Vegëz e përhershme',
'print'             => 'Shtyp',
'edit'              => 'Redakto',
'create'            => 'Krijo',
'editthispage'      => 'Redaktoje kët faqe',
'create-this-page'  => 'Krijo këtë faqe',
'delete'            => 'Fshij',
'deletethispage'    => 'Fshije këtë faqe',
'undelete_short'    => 'Kthe {{PLURAL:$1|redaktimin e fshimë|$1 redaktime të fshime}}',
'protect'           => 'Mbroj',
'protect_change'    => 'ndrysho',
'protectthispage'   => 'Mbroje këtë faqe',
'unprotect'         => 'Hiq mbrojtjen',
'unprotectthispage' => 'Hiq mbrojtjen nga kjo faqe',
'newpage'           => 'Faqe e re',
'talkpage'          => 'Diskuto këtë faqe',
'talkpagelinktext'  => 'Bisedo',
'specialpage'       => 'Faqe speciale',
'personaltools'     => 'Vegla vetjake',
'postcomment'       => 'Sekcion i ri',
'articlepage'       => 'Shiko artikullin',
'talk'              => 'Diskutimi',
'views'             => 'Paraqitje',
'toolbox'           => 'Veglat',
'userpage'          => 'Shiko faqen e përdoruesit',
'projectpage'       => 'Shiko faqen e projektit',
'imagepage'         => 'Shiko faqen e skedës',
'mediawikipage'     => 'Shiko faqen e mesazheve',
'templatepage'      => 'Shiko faqen e shabllonit',
'viewhelppage'      => 'Shiko faqen për ndihmë',
'categorypage'      => 'Shiko faqen e kategorisë',
'viewtalkpage'      => 'Shiko diskutimin',
'otherlanguages'    => 'Në gjuhë tjera',
'redirectedfrom'    => '(Përcjellë nga $1)',
'redirectpagesub'   => 'Faqe përcjellëse',
'lastmodifiedat'    => 'Kjo faqe âsht ndryshue për herë të fundit me $2, $1.',
'viewcount'         => 'Kjo faqe âsht pâ {{PLURAL:$1|nji|$1}} herë.',
'protectedpage'     => 'Faqe e mbrojtun',
'jumpto'            => 'Kce te:',
'jumptonavigation'  => 'lundrim',
'jumptosearch'      => 'lyp',
'view-pool-error'   => 'Na vjen keq, serverat janë të stërngarkuem momentalisht.
Tepër shumë përdorues po tentojnë me pâ këtë faqe.
Ju lutem pritni pak para se me tentue me iu qasë faqes prap.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Rreth {{SITENAME}}',
'aboutpage'            => 'Project:Rreth',
'copyright'            => 'Përmbajtja mundësohet simbas $1.',
'copyrightpage'        => '{{ns:project}}:Të drejtat autoriale',
'currentevents'        => 'Ndodhitë aktuale',
'currentevents-url'    => 'Project:Ndodhitë aktuale',
'disclaimers'          => 'Shfajsime',
'disclaimerpage'       => 'Project:Shfajsimet e përgjithshme',
'edithelp'             => 'Ndihmë për redaktim',
'edithelppage'         => 'Help:Redaktimi',
'helppage'             => 'Help:Përmbajtja',
'mainpage'             => 'Faqja kryesore',
'mainpage-description' => 'Faqja Kryesore',
'policy-url'           => 'Project:Politika',
'portal'               => 'Portali i komunitetit',
'portal-url'           => 'Project:Portali i komunitetit',
'privacy'              => 'Politika e privatsisë',
'privacypage'          => 'Project:Politika e privatsisë',

'badaccess'        => 'Gabim tagri',
'badaccess-group0' => 'Nuk keni tagër me ekzekutue veprimin e kërkuem.',
'badaccess-groups' => 'Në veprimin e kërkuem kanë tagër vetëm përdoruesit nga {{PLURAL:$2|grupi|grupet}}: $1.',

'versionrequired'     => 'Nevojitet verzioni $1 i MediaWikit',
'versionrequiredtext' => 'Lypet verzioni $1 i MediaWikit për me përdorë këtë faqe.
Shih [[Special:Version|faqen e verzionit]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Marrë nga "$1"',
'youhavenewmessages'      => 'Keni $1 ($2).',
'newmessageslink'         => 'mesazhe të reja',
'newmessagesdifflink'     => 'ndryshimi i fundit',
'youhavenewmessagesmulti' => 'Keni mesazhe të reja në $1',
'editsection'             => 'redakto',
'editold'                 => 'redakto',
'viewsourceold'           => 'shih kodin',
'editlink'                => 'redakto',
'viewsourcelink'          => 'shih kodin',
'editsectionhint'         => 'Redakto sekcionin: $1',
'toc'                     => 'Përmbajtja',
'showtoc'                 => 'trego',
'hidetoc'                 => 'mshef',
'thisisdeleted'           => 'Shiko ose rikthe $1?',
'viewdeleted'             => 'Shiko $1?',
'restorelink'             => '{{PLURAL:$1|nji redaktim i fshimë|$1 redaktime të fshime}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Lloji i abonimit të feedit âsht i gabuem.',
'feed-unavailable'        => 'Feedsat Sindikal nuk pranohen',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (faqja nuk ekziston)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Faqja',
'nstab-user'      => 'Faqja e përdoruesit',
'nstab-media'     => 'Faqja e mediave',
'nstab-special'   => 'Faqja speciale',
'nstab-project'   => 'Faqja e projektit',
'nstab-image'     => 'Skeda',
'nstab-mediawiki' => 'Mesazhet',
'nstab-template'  => 'Shablloni',
'nstab-help'      => 'Faqja e ndihmës',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Ky veprim nuk ekziston',
'nosuchactiontext'  => "Veprimi i kërkuem me URL nuk âsht valid.
Ndoshta keni shkrue gabim URL'ën, ose keni përcjellë vegëz të gabueme.
Kjo gjithashtu mundet me tregue gabim në softwarein e {{SITENAME}}.",
'nosuchspecialpage' => 'Nuk ekziston kjo faqe speciale',
'nospecialpagetext' => '<strong>Keni kërkue nji faqe speciale jovalide.</strong>

Lista e faqeve speciale valide gjindet te [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Gabim',
'databaseerror'        => 'Gabim në databazë',
'dberrortext'          => 'Ka ndodh nji gabim sintaksor në kërkesën në databazë.
Kjo mundet me tregue gabim në software.
Kërkesa e fundit në databazë ishte:
<blockquote><tt>$1</tt></blockquote>
mbrenda funksionit "<tt>$2</tt>".
Databaza ktheu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ka ndodh nji gabim sintaksor në kërkesën në databazë.
Kërkesa e fundit në databazë ishte:
"$1"
mbrenda funksionit "$2".
Databaza ktheu gabimin "$3: $4".',
'laggedslavemode'      => "'''Kujdes:''' Kjo faqe mundet mos me përmbajtë ndryshime të reja.",
'readonly'             => 'Databaza âsht e bllokueme',
'enterlockreason'      => 'Futni një arsye për bllokimin, gjithashtu futni edhe kohën se kur pritet të çbllokohet',
'readonlytext'         => 'Databaza e {{SITENAME}} âsht e bllokueme dhe nuk lejon redaktime, me gjasë për mirëmbajtje rutinore, mbas së cillës do të kthehet në gjendje normale.

Administruesi, i cilli e ka bllokue dha këtë arsye: $1',
'missing-article'      => 'Databaza nuk e gjeti tekstin e faqes me emën "$1" $2.

Kjo zakonisht ndodh nga përcjellja e nji ndryshimi të vjetëruem apo të nji vegze në faqe të fshime.

Nëse nuk âsht kështu, mund ta keni gjetë nji gabim në software. Ju lutemi, njoftoni nji [[Special:ListUsers/sysop|administrues]], për këtë, tue tregue URL\'ën.',
'missingarticle-rev'   => '(rishikimi#: $1)',
'missingarticle-diff'  => '(Ndryshimi: $1, $2)',
'readonly_lag'         => 'Databaza âsht bllokue automatikisht përderisa serverat e mvarun të skinkronizohen me kryesorin.',
'internalerror'        => 'Gabim i mbrendshëm',
'internalerror_info'   => 'Gabimi i mbrendshëm: $1',
'fileappenderrorread'  => ' "$1" nuk mujti m\'u lexu gjatë bashkangjitjes.',
'fileappenderror'      => '"$1" nuk mujti me i\'u bashkangjitë "$2".',
'filecopyerror'        => 'Nuk mujta me kopjue skedën "$1" te "$2".',
'filerenameerror'      => 'Nuk mujta me ndërrue emnin e skedës "$1" në "$2".',
'filedeleteerror'      => 'Nuk mujta me fshî skedën "$1".',
'directorycreateerror' => 'Nuk mujta me krijue direktorinë "$1".',
'filenotfound'         => 'Nuk mujta me gjetë skedën "$1".',
'fileexistserror'      => 'Nuk mujta me shkrue në skedën "$1": Kjo skedë ekziston',
'unexpected'           => 'Vlerë e papritun: "$1"="$2".',
'formerror'            => 'Gabim: nuk mujta me dërgue formularin',
'badarticleerror'      => 'Ky veprim nuk mundet me u ekzekutue në këtë faqe.',
'cannotdelete'         => 'Nuk mujta me fshi faqen apo skedën "$1".
Ndodh që âsht fshi prej dikujt tjetër.',
'badtitle'             => 'Titull i keq',
'badtitletext'         => 'Titulli i faqes që kërkuet ishte jovalid, bosh, apo ishte nji vegëz gabim e lidhun ndërgjuhesisht apo ndër-wiki.
Ndodh që ka shêja që nuk munden me u përdorë në titull.',
'perfcached'           => 'Informacioni i mâposhtëm âsht kopje e memorizueme, por mundet mos me qenë verzioni i fundit:',
'perfcachedts'         => 'Shenimi i mâposhtëm âsht kopje e memorizueme dhe âsht rifreskue së fundit me $1.',
'querypage-no-updates' => 'Redaktimi i kësaj faqeje âsht ndalue për momentin.
Shenimet këtu nuk do të rifreskohen.',
'wrong_wfQuery_params' => 'Parametra gabim te wfQuery()<br />
Funksioni: $1<br />
Kërkesa: $2',
'viewsource'           => 'Shih kodin',
'viewsourcefor'        => 'e $1',
'actionthrottled'      => 'Veprimi âsht i kufizuem',
'actionthrottledtext'  => 'Si masë kunder spamit, jeni të kufizuem me kry këtë veprim shumë herë për nji kohë shumë të shkurtë, dhe e keni tejkalue këtë kufizim.
Ju lutemi provoni prap mbas disa minutave.',
'protectedpagetext'    => 'Kjo faqe âsht mbyllë për redaktim.',
'viewsourcetext'       => 'Mundeni me pâ dhe kopjue kodin burimor të kësaj faqeje:',
'protectedinterface'   => 'Kjo faqe përmban tekst të interfaceit të softwareit dhe âsht e mbrojtun për me pengue keqpërdorimin.',
'editinginterface'     => "'''Kujdes:''' Po redaktoni nji faqe që përdoret për me ofrue tekst të interfaceit të softwareit.
Ndryshimet në këtë faqe do të prekin pamjen e interfaceit për të gjithë përdoruesit tjerë.
Për përkthim, konsideroni ju lutem përdorimin e [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], projektin e MediaWiki për përshtatje gjuhësore.",
'sqlhidden'            => '(Kërkesa SQL e msheftë)',
'cascadeprotected'     => 'Kjo faqe âsht e mbrojtun prej redaktimit, për shkak se âsht e përfshime në {{PLURAL:$1|faqen, e cila âsht e mbrojtun|faqet, të cilat janë të mbrojtuna}} me opcionin "zinxhir" të zgjedhun:
$2',
'namespaceprotected'   => "Nuk keni tagër me redaktue faqe në hapësinën '''$1'''.",
'customcssjsprotected' => 'Nuk keni tagër me redaktue këtë faqe, sepse përmban përcaktime personale të nji përdoruesi tjetër.',
'ns-specialprotected'  => 'Faqet speciale nuk mujnë me u redaktue.',
'titleprotected'       => 'Ky titull âsht i mbrojtun për krijim prej përdoruesit [[User:$1|$1]].
Arsyeja e dhânë âsht "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Konfigurim i keq: scanner i panjoftun virusash: ''$1''",
'virus-scanfailed'     => 'scanimi dështoi (code $1)',
'virus-unknownscanner' => 'antivirus i panjoftun:',

# Login and logout pages
'logouttext'                 => "'''Jeni çlajmërue.'''

Mundeni me vazhdue me shfrytëzue {{SITENAME}} në mënyrë anonime, apo mundeni [[Special:UserLogin|me u kyçë]] si përdoruesi i njêjtë apo si nji tjetër.
Disa faqe mujnë me u paraqitë prap si t'kishit qenë t'kyçun, derisa ta pastroni memorizimin e shfletuesit.",
'welcomecreation'            => '== Mirësevini, $1! ==

Llogaria juej âsht krijue.
Mos harroni me i ndryshue [[Special:Preferences|parapëlqimet për {{SITENAME}}]].',
'yourname'                   => 'Nofka:',
'yourpassword'               => 'Fjalëkalimi:',
'yourpasswordagain'          => 'Fjalëkalimi përsëdyti:',
'remembermypassword'         => 'Mbaj mend fjalëkalimin tem në këtë shfletues (për $1 {{PLURAL:$1|ditë|ditë}})',
'yourdomainname'             => 'Domena juej:',
'externaldberror'            => 'Ose pat gabim në databazën e autentifikimit, ose nuk lejoheni me ndryshue llogarinë tuej të jashtme.',
'login'                      => 'Kyçu',
'nav-login-createaccount'    => 'Kyçu / çel llogari',
'loginprompt'                => 'Shfletuesi duhet me pranue keksa (cookies) për me mujtë me u kyçë në {{SITENAME}}.',
'userlogin'                  => 'Kyçu / çel llogari',
'userloginnocreate'          => 'Kyçu',
'logout'                     => 'Çkyçu',
'userlogout'                 => 'Çkyçu',
'notloggedin'                => 'Nuk je i kyçun',
'nologin'                    => "Nuk ke llogari? '''$1'''.",
'nologinlink'                => 'Krijo llogari',
'createaccount'              => 'Krijo llogari',
'gotaccount'                 => "Ke llogari? '''$1'''.",
'gotaccountlink'             => 'Kyçu',
'createaccountmail'          => 'me email',
'createaccountreason'        => 'Arsyeja:',
'badretype'                  => 'Fjalëkalimet nuk janë të njêjta.',
'userexists'                 => 'Nofka keni zgjedhë âsht në përdorim.
Zgjedh nji emën tjetër.',
'loginerror'                 => 'Gabim në kyçje',
'createaccounterror'         => 'Nuk mund të hapni një llogari $1',
'nocookiesnew'               => 'Llogaria e përdoruesit u krijue, por ende nuk je i kyçun.
{{SITENAME}} përdor keksa (cookies) për kyçje.
Keksat i ke jasht funksionit.
Futi keksat në funksion, mandej kyçu me nofkën dhe fjalëkalimin tând të ri.',
'nocookieslogin'             => '{{SITENAME}} përdor keksa (cookies) për kyçje.
Keksat i ke jasht funksionit.
Të lutem aktivizoji dhe provo prap.',
'noname'                     => 'Nuk ke dhânë nofkë valide.',
'loginsuccesstitle'          => 'Kyçje e suksesshme',
'loginsuccess'               => "'''Je kyçë në {{SITENAME}} si \"\$1\".'''",
'nosuchuser'                 => 'Nuk ka përdorues me emnin "$1".
Emnat janë senzitiv në madhësi të germës.
Kontrollo drejtshkrimin ose [[Special:UserLogin/signup|krijo llogari]].',
'nosuchusershort'            => 'Nuk ka përdorues me emnin "<nowiki>$1</nowiki>".
Kontrollo drejtshkrimin.',
'nouserspecified'            => 'Duhesh me dhânë nji nofkë.',
'login-userblocked'          => 'Ky përdorues është bllokuar. Identifikohu nuk lejohet',
'wrongpassword'              => 'Fjalëkalimi i pasaktë.
Provo prap.',
'wrongpasswordempty'         => 'Fjalëkalimi ishte i shprazët.
Provo prap.',
'passwordtooshort'           => 'Fjalëkalimi juej duhet me i pasë së paku {{PLURAL:$1|1 shêjë|$1 shêja}}.',
'password-name-match'        => 'Fjalëkalimi duhet me qenë i ndryshëm prej nofkës.',
'mailmypassword'             => 'Çoma fjalëkalimin me postel',
'passwordremindertitle'      => 'Fjalëkalim i ri i përkohshëm për {{SITENAME}}',
'passwordremindertext'       => 'Dikush (me giasë ju vetë, nga adresa IP $1) ka lypë nji
fjalëkalim për {{SITENAME}} ($4). Nji fjalëkalim i përkohshëm për
"$2" âsht krijue si vijon "$3". Nëse kjo ka qenë qëllimi juej
atëhere duheni me u kyçë tash dhe me zgjedhë nji fjalëkalim të ri.
Fjalëkalimi juej i përkohshëm skadon për {{PLURAL:$5|nji ditë|$5 ditë}}.

Nëse dikush tjetër e ka bâ këtë kërkesë, ose ju âsht kujtue fjalëkalimi,
dhe nuk dëshironi me ndërrue fjalëkalimin, mundeni me injorue këtë mesazh
dhe me vazhdue me përdorë fajlëkalimin e vjetër.',
'noemail'                    => 'Nuk ka adresë emaili për përdoruesin "$1".',
'noemailcreate'              => 'Ju duhet të sigurojë një adresë të saktë-mail',
'passwordsent'               => 'Nji fjalëkalim i ri u dërgue në adresën e emailit të "$1".
Ju lutem kyçuni mbasi ta keni marrë atê.',
'blocked-mailpassword'       => 'Adresa juej IP âsht bllokue për redaktim, dhe nuk lejohet me përdorë funksionin e mëkâmbjes së fjalëkalimit për me parandalue keqpërdorimin.',
'eauthentsent'               => 'Nji email konfirmues u dërgue në adresën e emnueme.
Para se me u dërgue çfarëdo emaili tjetër në këtë llogari, duheni me i përcjellë udhëzimet në atë email, për me konfirmue se kjo llogari âsht e jueja.',
'throttled-mailpassword'     => 'Nji përkujtim i fjalëkalimit âsht dërgue, para {{PLURAL:$1|nji ore|$1 orësh}}.
Për me parandalue keqpërdorimin, mundet me u dërgue vetëm nji përkujtim i fjalëkalimit mbrenda {{PLURAL:$1|nji ore|$1 orësh}}.',
'mailerror'                  => 'Gabim në dërgimin e emailit: $1',
'acct_creation_throttle_hit' => 'Vizitorët e këtij wiki që shfrytëzojnë adresën IP kanë krijue {{PLURAL:$1|1 llogari|$1 llogari}} gjatë ditës së fundit, që âsht maksimumi për këtë periudhë kohore.
Si rezultat, vizitorët nga kjo IP nuk mujnë me krijue llogari tjera tashpërtash.',
'emailauthenticated'         => 'Adresa juej e emailit âsht vërtetue me $2 në ora $3.',
'emailnotauthenticated'      => 'Adresa juej e emailit nuk âsht vërtetue ende.
Nuk ka me u dërgue mâ asnji email për shërbimet në vazhdim.',
'noemailprefs'               => "Specifikoni nji adresë emaili në parapëlqimet tueja në mënyrë që t'funksionojnë shërbimet.",
'emailconfirmlink'           => 'Konfirmonin adresën tuej te emailit',
'invalidemailaddress'        => 'Adresa e emailit nuk pranohet meqenëse duket se ka format jovalid.
Ju lutemi jepeni nji adresë të formatueme mirë ose leni atë fushë të shprazët.',
'accountcreated'             => 'Llogaria u krijue',
'accountcreatedtext'         => 'Llogaria e përdoruesit për $1 u krijue',
'createaccount-title'        => 'Krijimi i llogarive për {{SITENAME}}',
'createaccount-text'         => 'Dikush ka krijue llogari me adresën tuej të emailit në {{SITENAME}} ($4) me nofkën "$2", dhe fjalëkalimin "$3".
Kyçuni tash dhe ndërroni fjalëkalimin.

Nëse kjo llogari âsht krijue gabimisht, mundeni me injorue këtë email.',
'usernamehasherror'          => 'Emri nuk mund të përmbajë karaktere hash',
'login-throttled'            => 'Keni bâ shumë tentime frik me u kyçë në kët llogari.
Ju lutemi pritni pak për me provue prap.',
'loginlanguagelabel'         => 'Gjuha: $1',
'suspicious-userlogout'      => 'Kërkesa juaj për të shkëputet u mohua sepse duket sikur është dërguar nga një shfletues të thyer ose caching proxy',

# Password reset dialog
'resetpass'                 => 'Ndrysho fjalëkalimin',
'resetpass_announce'        => 'Jeni kyçë me nji kod të përkohshëm të dërguem me email.
Për me krye kyçjen, specifikoni fjalëkalimin e ri këtu:',
'resetpass_text'            => '<!-- Shto tekst këtu -->',
'resetpass_header'          => 'Ndrysho fjalëkalimin',
'oldpassword'               => 'Fjalëkalimi i vjetër:',
'newpassword'               => 'Fajlëkalimi i ri:',
'retypenew'                 => 'Fjalëkalimi i ri përsëdyti',
'resetpass_submit'          => 'Vendos fjalëkalimin dhe kyçu',
'resetpass_success'         => 'Fjalëkalimi juej u ndryshue me sukses! Tash po kyçeni...',
'resetpass_forbidden'       => 'Fjalëkalimet nuk mujnë me u ndryshue',
'resetpass-no-info'         => 'Duheni me qenë të kyçun për me iu qasë kësaj faqeje direkt.',
'resetpass-submit-loggedin' => 'Ndrysho fjalëkalimin',
'resetpass-submit-cancel'   => 'Harroji',
'resetpass-wrong-oldpass'   => 'Fjalëkalimi i përkohshëm apo ai aktual invalid.
Ndoshta tashmâ e keni ndryshue fjalëkalimin me sukses, apo keni kërkue nji fjalëkalim të përkohshëm.',
'resetpass-temp-password'   => 'Fjalëkalimi i përkohshëm:',

# Edit page toolbar
'bold_sample'     => 'Tekst i trashë',
'bold_tip'        => 'Tekst i nximë',
'italic_sample'   => 'Tekst i pjerrët',
'italic_tip'      => 'Tekst kurziv',
'link_sample'     => 'Titulli i vegzës',
'link_tip'        => 'Vegëz e mbrendshme',
'extlink_sample'  => 'http://www.example.com Titulli i vegzës',
'extlink_tip'     => 'Vegëz e jashtme (mos e harro prefiksin http://)',
'headline_sample' => 'Teksti i kryetitullit',
'headline_tip'    => 'Kryetitull i nivelit 2',
'math_sample'     => 'Vendos formulën këtu',
'math_tip'        => 'Formulë matematikore (LaTeX)',
'nowiki_sample'   => 'Vendos tekst të paformatueshëm këtu',
'nowiki_tip'      => 'Shpërfille formatimin wiki',
'image_sample'    => 'Shembull.jpg',
'image_tip'       => 'Skedë e ndërthurrun',
'media_sample'    => 'Shembull.ogg',
'media_tip'       => 'Vegëz në skedë',
'sig_tip'         => 'Nënshkrimi juej me gjithë kohën',
'hr_tip'          => 'Vijë horizontale (përdoreni rallë)',

# Edit pages
'summary'                          => 'Përmbledhje:',
'subject'                          => 'Tema/kryetitulli:',
'minoredit'                        => 'Ky asht redaktim i vogël',
'watchthis'                        => 'Mbikëqyre kët faqe',
'savearticle'                      => 'Regjistro faqen',
'preview'                          => 'Parapâmje',
'showpreview'                      => 'Trego parapâmjen',
'showlivepreview'                  => 'Parapâmje e menjiherëshme',
'showdiff'                         => 'Trego ndryshimet',
'anoneditwarning'                  => "'''Kujdes:''' Ju nuk jeni i kyçun.
Adresa juej IP do të regjistrohet në historikun e redaktimit të kësaj faqeje.",
'anonpreviewwarning'               => "''Nuk jeni i kyçun. Regjistrimi ka me marrë shenim IP adresën tuej në histori të faqes.''",
'missingsummary'                   => "'''Vini re:''' Nuk keni specifikue përmbledhje të redaktimit.
Nëse klikoni prap në regjistro, redaktimi do të ruhet pa tê.",
'missingcommenttext'               => 'Ju lutemi shtoni nji koment mâ poshtë.',
'missingcommentheader'             => "'''Vini re:''' Nuk keni dhânë temë/kryetitull për këtë koment.
Nëse klikoni në regjistro prap, redaktimi juej do të ruhet pa tê.",
'summary-preview'                  => 'Parapamja e përmbledhjes:',
'subject-preview'                  => 'Parapâmja e temës/kryetitullit:',
'blockedtitle'                     => 'Përdoruesi âsht i bllokuem',
'blockedtext'                      => "'''Llogaria juej apo adresa IP âsht bllokue.'''

Bllokim âsht bâ prej $1.
Arsyeja e dhânë âsht ''$2''.

* Fillimi i bllokimit: $8
* Përfundimi i bllokimit: $6
* I bllokuemi i synuem: $7

Mundeni me kontaktue $1 ose ndonji [[{{MediaWiki:Grouppage-sysop}}|administrator]] për me diskutue bllokimin.
Nuk mundeni me shfrytëzue funksionin 'dërgo email këtij përdoruesi' përveç nëse keni specifikue adresë emaili në [[Special:Preferences|parapëlqimet e llogarisë]] dhe nuk jeni bllokue.
Adresa juej aktuale e IP âsht $3, dhe ID e bllokimit âsht #$5.
Ju lutemi përfshini këto shenime në të gjitha shkresat që i bâni.",
'autoblockedtext'                  => "Adresa juej e IPs âsht bllokue automatikisht meqenëse âsht përdorë prej nji përdoruesi tjetër, i cili âsht bllokue prej $1.
Arsyeja e dhânë âsht kjo:

:''$2''

* Fillimi i bllokimit: $8
* Përfundimi i bllokimit: $6
* I bllokuemi i synuem: $7

Mundeni me kontaktue $1 ose ndonji [[{{MediaWiki:Grouppage-sysop}}|administrator]] për me diskutue bllokimin.

Nuk mundeni me shfrytëzue funksionin 'dërgo email këtij përdoruesi' përveç nëse keni specifikue adresë emaili në [[Special:Preferences|parapëlqimet e llogarisë]] dhe nuk jeni bllokue.

Adresa juej aktuale e IP âsht $3, dhe ID e bllokimit âsht #$5.
Ju lutemi përfshini këto shenime në të gjitha shkresat që i bâni.",
'blockednoreason'                  => 'nuk âsht dhânë arsye',
'blockedoriginalsource'            => "Kodi burimor i '''$1''' âsht mâ poshtë:",
'blockededitsource'                => "Teksti i '''redaktimeve tueja''' të '''$1''' âsht mâ poshtë:",
'whitelistedittitle'               => 'Lypet kyçje për me mujtë me redaktue',
'whitelistedittext'                => 'Duheni me u $1 për me redaktue artikuj.',
'confirmedittext'                  => 'Duheni me vërtetue adresën tuej të emailit para se me redaktue.
Ju lutemi vërtetoni adresën tuej të emailit përmjet [[Special:Preferences|parapëlqimeve]] tueja.',
'nosuchsectiontitle'               => 'Nuk ka kësi sekcioni',
'nosuchsectiontext'                => 'Keni tentue me redaktue nji sekcion që nuk ekziston.',
'loginreqtitle'                    => 'Lypet kyçje',
'loginreqlink'                     => 'kyçë',
'loginreqpagetext'                 => 'Duheni me u $1 për me i pâ faqet tjera.',
'accmailtitle'                     => 'Fjalëkalimi u dërgue.',
'accmailtext'                      => "Nji fjalëkalim i krijuem rastësisht për [[User talk:$1|$1]] u dërgue në $2.

Fjalëkalimi për këtë llogari mundet me u ndryshue në faqen ''[[Special:ChangePassword|ndrysho fjalëkalimin]]'' mbas kyçjes.",
'newarticle'                       => '(I ri)',
'newarticletext'                   => "Ke ndjekë nji vegëz për te nji faqe që nuk ekziston.
Me krijue kët faqe, shkruej në kutinë ma poshtë (shih [[{{MediaWiki:Helppage}}|faqen e ndihmës]] për ma shum udhzime).
Nëse ke hy këtu gabimisht, klikoje sustën '''mbrapa''' në shfletues.",
'anontalkpagetext'                 => "----''Kjo âsht faqe diskutimi e nji përdoruesi anonim, i cili nuk ka krijue llogari, apo nuk e përdor atê.
Prandej përdoret adresa numerike IP e tij për me identifikue.
Adresa IP mundet me u shfrytëzue prej disa përdoruesve.
Nëse jeni përdorues anonim dhe keni përshtypjen se po ju drejtohen komente jorelevante, ju lutemi [[Special:UserLogin/signup|krijoni nji llogari]] apo [[Special:UserLogin|kyçuni]] për me iu shmângë ngatërrimit me përdorues tjerë anonim.''",
'noarticletext'                    => 'Momentalisht nuk ka tekst në këtë faqe.
Ju mundeni [[Special:Search/{{PAGENAME}}|me kërkue këtë titull]] në faqe tjera,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} me kërkue në regjistrat tematikisht të afërm],
apo [{{fullurl:{{FULLPAGENAME}}|action=edit}} me redaktue këtë faqe]</span>.',
'userpage-userdoesnotexist'        => 'Llogaria e përdoruesit "$1" nuk âsht regjistrue.
Ju lutemi kontrolloni nëse doni me krijue/redaktue këtë faqe.',
'clearyourcache'                   => "'''Shenim - Mbas ruejtjes, ka mundësi që duheni me shmângë memorizimin në cache për me i pâ ndryshimet.'''
'''Mozilla / Firefox / Safari:''' mbani ''Shift'' tue klikue në ''Reload'', ose trusni ''Ctrl-F5'' ose ''Ctrl-R'' (''Command-R'' në Mac);
'''Konqueror: '''klikoni ''Reload'' ose trusni ''F5'';
'''Opera:''' fshini cachein në ''Tools → Preferences'';
'''Internet Explorer:''' mbani ''Ctrl'' tue klikue në ''Refresh,'' ose trusni ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Këshillë:''' Përdorni butonin 'Trego parapâmjen' për me testue CSS para se me i regjistrue ndryshimet.",
'userjsyoucanpreview'              => "'''Këshillë:''' Përdorni butonin 'Trego parapâmjen' për me testue JS para se me i regjistrue ndryshimet.",
'usercsspreview'                   => "'''Vini re, jeni tue pâ veç parapâmjen e CSSit tuej.'''
'''Ende nuk e keni ruejtë!'''",
'userjspreview'                    => "'''Vini re, jeni tue testue/pâ veç parapâmjen e JavaScriptit tuej.'''
'''Ende nuk e keni ruejtë!'''",
'userinvalidcssjstitle'            => "'''Kujdes:''' Nuk ka pâmje me emën \"\$1\".
Vini re që faqet .css dhe .js përdorin vetëm titull me germa të vogla, psh. {{ns:user}}:Foo/vector.css për dallim prej {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(E ndryshueme)',
'note'                             => "'''Shenim:'''",
'previewnote'                      => "'''Kjo asht vetëm parapamje.'''
Ndryshimet e tua nuk janë ruejtë ende!",
'previewconflict'                  => 'Kjo parapâmje pasqyron tekstin në kutinë e sipërme të redaktimit, njashtu si do të duket nëse e rueni.',
'session_fail_preview'             => "'''Na vjen keq! Nuk mujtëm me ruejtë redaktimin tuej për shkak të hupjes së sesionit.'''
Ju lutemi provoni prap.
Nëse prap nuk funksionon, provoni me u [[Special:UserLogout|çkyçë]] dhe me u kyçë prap.",
'session_fail_preview_html'        => "'''Na vjen keq! Nuk mujtëm me i ruejtë ndryshimet tueja për shkak të hupjes së sesionit.'''

''Tue qenë se {{SITENAME}} ka të aktivizuem HTML të papërpunuem, parapâmja âsht e msheftë si preventivë kundër sulmeve me JavaScript.''

'''Nëse kjo ishte redaktim legjitim, ju lutemi provoni prap.'''
Nëse prap nuk funksionon, provoni me u [[Special:UserLogout|çkyçë]] edhe me u kyçë prap.",
'token_suffix_mismatch'            => "'''Redaktimi juej u refuzue meqenëse shfletuesi juej i ka përzî shêjat e pikësimit në tekstin e redaktuem.'''
Redaktimi âsht refuzue për me parandalue korruptimin e tekstit.
Kjo ndodh nganjiherë, kur jeni tue përdorë shërbime ndërmjetësash anonimizues që përmbajnë gabime.",
'editing'                          => 'Tue redaktue $1',
'editingsection'                   => 'Tue redaktue (sekcionin) $1',
'editingcomment'                   => 'Tue redaktue (sekcionin e ri) $1',
'editconflict'                     => 'Konflikt redaktues: $1',
'explainconflict'                  => "Dikush tjetër e ka ndryshue këtë faqe derisa e redaktojshit ju.
Kutia e sipërme tregon tekstin aktual të faqes.
Ndryshimet tueja gjinden në kutinë e poshtme redaktuese.
Ju duheni me i bashkue ndryshimet tueja në tekstin aktual.
'''Vetëmse''' nëse shtypni \"{{int:savearticle}}\" ka me u ruejtë teksti në kutinë e sipërme redaktuese.",
'yourtext'                         => 'Teksti juej',
'storedversion'                    => 'Rishikim i ruejtun',
'nonunicodebrowser'                => "'''Kujdes: Shfletuesi juej nuk e përkrah unicodein.'''
Për me ju lejue me redaktue faqen pa gabime aplikohet nji opcion shtesë: germat jashta ASCII kodit paraqiten me kod heksadecimal.",
'editingold'                       => "'''Kujdes: Jeni tue redaktue nji verzion të vjetër të faqes.'''
Nëse e rueni, tâna rishikimet e mâvonshme të faqes kanë me hupë.",
'yourdiff'                         => 'Dallimet',
'copyrightwarning'                 => "Ju lutemi vini re se tâna kontributet në {{SITENAME}} konsiderohen me qenë të lidhuna me licencën $2 (shih $1 për detaje).
Nëse nuk doni që shkrimet tueja me u redaktue pamëshirshëm dhe me u shpërnda arbitrarisht, atëherë mâ mirë mos publikoni këtu.<br />
Gjithashtu po premtoni se këtë e keni shkrue vetë, ose e keni kopjue prej domenës publike apo ndonji burimi tjetër të lirë.
'''Mos publikoni vepra që janë e drejtë autoriale pa leje!'''",
'copyrightwarning2'                => "Ju lutemi vini re se tâna kontributet në {{SITENAME}} mujnë me u rishkrue, ndryshue, apo fshi prej kontribuusve tjerë.
Nëse nuk doni që shkrimet tueja me u redaktue pamëshirshëm dhe me u shpërnda arbitrarisht, atëherë mâ mirë mos publikoni këtu.<br />
Gjithashtu po premtoni se këtë e keni shkrue vetë, ose e keni kopjue prej domenës publike apo ndonji burimi tjetër të lirë (shih $1 për detaje).
'''Mos publikoni vepra që janë e drejtë autoriale pa leje!'''",
'longpageerror'                    => "'''Gabim: Teksti që po redaktoni i ka $1 kilobyte, që âsht mâ shumë se maksimumi prej $2 kilobytësh.'''
Nuk mundet me u ruejtë.",
'readonlywarning'                  => "'''Kujdes: Baza e të dhânave âsht mshelë për mirëmbajtje, kështuqë tashpërtash nuk keni me mujtë me i ruejtë redaktimet tueja.'''
Mundeni me kopju dhe ruejtë tekstin në nji skedë për mâ vonë.

Administruesi që e ka mshelë e ka dhânë këtë shpjegim: $1",
'protectedpagewarning'             => "'''Kujdes: Kjo faqe âsht mshelë ashtu që vetëm përdoruesit me tagër administrues mujnë me redaktue.'''",
'semiprotectedpagewarning'         => "'''Shenim:''' Kjo faqe âsht e mshelun dhe mundet me u redaktue vetëm prej përdoruesve të regjistruem.",
'cascadeprotectedwarning'          => "'''Veni re:''' Kjo faqe âsht e mshelun dhe vetëm përdoruesit me tagër administruesi munden me e redaktue, tue qenë se âsht e përfshime në mbrojtje mvarësie në {{PLURAL:$1|faqen e|faqet e}} mâposhtme:",
'titleprotectedwarning'            => "'''Veni re:  Kjo faqe âsht e mshelun dhe vetëm përdorues me [[Special:ListGroupRights|tagër të veçantë]] munden me e krijue.'''",
'templatesused'                    => '{{PLURAL:$1|Stamp|Stampa}} janë përdorë në kët faqe:',
'templatesusedpreview'             => '{{PLURAL:$1|Shabllon|Shabllonet}} e përdoruna në kët parapamje:',
'templatesusedsection'             => '{{PLURAL:$1|Stamp|Stampa}} e përdoruna në këtë sekcion:',
'template-protected'               => '(e mbrojtme)',
'template-semiprotected'           => '(gjysë-mbrojtun)',
'hiddencategories'                 => 'Kjo faqe bân pjesë në {{PLURAL:$1|1 kategori të msheftë|$1 kategori të mshefta}}:',
'nocreatetitle'                    => 'Krijimi i faqeve âsht i kufizuem.',
'nocreatetext'                     => '{{SITENAME}} ka kufizue mundësinë e krijimit të faqeve të reja.
Mundeni me u kthy mbrapa edhe me redaktue faqen ekzistuese, apo [[Special:UserLogin|me u kyçë a me krijue nji llogari]].',
'nocreate-loggedin'                => 'Nuk keni tagër me krijue faqe të reja.',
'permissionserrors'                => 'Gabim tagri',
'permissionserrorstext'            => 'Nuk keni tagër me bâ atë veprim, për {{PLURAL:$1|arsyen|arsyet}} vijuese:',
'permissionserrorstext-withaction' => 'Nuk keni tagër në $2, për {{PLURAL:$1|arsyen|arsyet}} vijuese:',
'recreate-moveddeleted-warn'       => "'''Veni re: Jeni tue rikrijue nji faqe, e cila âsht fshi mâ herët.'''

Mendohuni nëse âsht e udhës me vazhdue me këtë veprim.
Regjistrin e fshimjes dhe zhvendosjes e keni në vijim:",
'moveddeleted-notice'              => 'Kjo faqe është grisur. Të dhënat e grisjes për këtë faqe gjenden më poshtë, për referencë.',
'edit-gone-missing'                => 'Faqja nuk mund t freskohet.
Duket se është grisur.',
'edit-conflict'                    => 'Konflikt në redaktim.',
'edit-no-change'                   => 'Redaktimi juaj është anashkaluar pasi që asnjë ndryshim nuk u bë në tekst.',
'edit-already-exists'              => 'Faqja nuk mundej të hapet.
Ajo tanimë ekziston.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Kujdes: Kjo faqe ka shumë kërkesa që kërkojnë analizë gramatikore të kushtueshme për sistemin.

Duhet të ketë më pakë se $2, {{PLURAL:$2|kërkesë|kërkesa}}, kurse tani {{PLURAL:$1|është $1 kërkesë|janë $1 kërkesa}}.',
'expensive-parserfunction-category'       => 'Faqe me shumë shprehje të kushtueshmë për analizë gramatikore',
'post-expand-template-inclusion-warning'  => "Vini re: Stampa e përfshirë është shumë e madhe.
Disa stampa s'do të përfshihen.",
'post-expand-template-inclusion-category' => 'Faqe ku stampat e përfshira kalojnë kufirin',
'post-expand-template-argument-warning'   => "Vini re: Kjo faqe ka të paktën një parametër stampe që është shumë i madh për t'u shpalosur.
Këto parametra nuk janë përfshirë.",
'post-expand-template-argument-category'  => 'Faqe që kanë parametra stampe të papërfshira',

# "Undo" feature
'undo-success' => 'Redaktimi nuk mund të kthehej. Ju lutem kontrolloni ndryshimet e mëposhtëme për të vërtetuar dëshirën e veprimit dhe pastaj kryeni ndryshimet për të plotësuar kthimin e redaktimit.',
'undo-failure' => 'Redaktimi nuk mund të kthehej për shkak të përplasjeve të ndërmjetshme.',
'undo-norev'   => "S'mund të zhbëja këtë redaktim pasi nuk ekziston ose është grisur.",
'undo-summary' => 'U kthye versioni $1 i bërë nga [[Special:Contributions/$2]] ([[User talk:$2]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nuk mundet të krijohet llogaria',
'cantcreateaccount-text' => "Hapja e llogarive nga kjo adresë IP ('''$1''') është bllokuar nga [[User:$3|$3]].

Arsyeja e dhënë nga $3 është ''$2''.",

# History pages
'viewpagelogs'           => 'Shih regjistrat për këtë faqe',
'nohistory'              => 'Nuk ka histori redaktimesh për këtë faqe.',
'currentrev'             => 'Versioni i tanishëm',
'currentrev-asof'        => 'Redaktimi aktual i datës $1',
'revisionasof'           => 'Versioni i $1',
'revision-info'          => 'Versioni me $1 nga $2',
'previousrevision'       => '← Verzion ma i vjetër',
'nextrevision'           => 'Redaktimi mâ i ri →',
'currentrevisionlink'    => 'Verzioni aktual',
'cur'                    => 'tash',
'next'                   => 'mbas',
'last'                   => 'fund',
'page_first'             => 'Së pari',
'page_last'              => 'Së fundmi',
'histlegend'             => "Përzgjedhja e dallimeve: shêjo kutijat rrethore të verzioneve që do me i krahasue dhe shtyp enter ose butonin në fund.<br />
Legjenda: '''({{int:cur}})''' = dallimi me verzionin aktual,
'''({{int:last}})''' = dallimi me verzionin para këtij, '''{{int:minoreditletter}}''' = redaktim i vogël.",
'history-fieldset-title' => 'Shfleto historikun',
'histfirst'              => 'Mâ të hershmet',
'histlast'               => 'Mâ të freskëtat',
'historysize'            => '({{PLURAL:$1|1 B|$1 B}})',
'historyempty'           => '(bosh)',

# Revision feed
'history-feed-title'          => 'Historiku i versioneve',
'history-feed-description'    => 'Historiku i versioneve për këtë faqe në wiki',
'history-feed-item-nocomment' => '$1 tek $2',
'history-feed-empty'          => 'Faqja që kërkuat nuk ekziston. Ajo mund të jetë grisur nga wiki ose mund të jetë zhvendosur nën një emër tjetër. Mund të provoni ta gjeni duke e [[Special:Search|kërkuar]].',

# Revision deletion
'rev-deleted-comment'         => '(kometi u largua)',
'rev-deleted-user'            => '(përdoruesi u largua)',
'rev-deleted-event'           => '(veprimi në regjistër është hequr)',
'rev-deleted-text-permission' => 'Ky version i faqes është shlyer nga arkivi publik i faqes.
Shiko tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve], ndoshta gjendet atje më shumë informacion rreth kësaj faqeje.',
'rev-deleted-text-view'       => 'Ky version i faqes është shlyer nga arkivi publik i faqes. Ju si Administrator mund ta shikoni akoma këtë.
Shiko tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.',
'rev-delundel'                => 'trego/mshef',
'rev-showdeleted'             => 'Trego',
'revisiondelete'              => 'Fshij/kthe verzionet',
'revdelete-nooldid-title'     => 'Version i dëshiruar i pavfleshëm',
'revdelete-nooldid-text'      => 'Ose nuk keni përcaktuar një version(e) të dëshiruar për veprimin, ose versioni nuk ekziston, ose po mundoheni të fshihni versionin e tanishëm.',
'revdelete-nologtype-title'   => 'Nuk është dhënë asnjë lloj i të dhënave',
'revdelete-nologtype-text'    => 'Nuk keni caktuar llojin e të dhënave për të realizuar veprimin.',
'revdelete-no-file'           => 'Skeda e dhënë nuk ekziston.',
'revdelete-selected'          => "'''{{PLURAL:$2|Versioni i zgjedhur i|Versionet e zgjedhura të}} [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Veprimi i zgjedhur në regjistër|Veprimet e zgjedhura në regjistër}}:'''",
'revdelete-text'              => "'''Përmbajtja dhe pjesët e tjera nuk janë të dukshme për të gjithë, por figurojnë në historikun e versioneve.''' Administratorët munden përmbajtjen e larguar ta shikojnë dhe restaurojnë, përveç në rastet kur një gjë e tillë është ndaluar ekstra.",
'revdelete-legend'            => 'Vendosni kufizimet për versionin:',
'revdelete-hide-text'         => 'Fshihe tekstin e versionit',
'revdelete-hide-image'        => 'Fshih përmbajtjen skedare',
'revdelete-hide-name'         => 'Fshihe veprimin dhe shënjestrën',
'revdelete-hide-comment'      => 'fshih komentin e redaktimit',
'revdelete-hide-user'         => 'Fshihe emrin/IP-në të redaktuesit',
'revdelete-hide-restricted'   => 'Këto përkufizme vlejnë edhe për Admintratorët (jo vetëm për përdoruesit "normal")',
'revdelete-suppress'          => 'Ndalo të dhëna nga administrues si dhe të tjerë',
'revdelete-unsuppress'        => 'Hiq kufizimet nga versionet e restauruara',
'revdelete-log'               => 'Arsyeja:',
'revdelete-submit'            => 'Apliko te versionet e zgjedhura',
'revdelete-logentry'          => 'Pamja e versionit u ndryshua për [[$1]]',
'logdelete-logentry'          => 'u ndryshua dukshmëria e ngjarjes së [[$1]]',
'revdelete-success'           => "'''Dukshmëria e versioneve u vendos me sukses.'''",
'logdelete-success'           => "'''Dukshmëria e regjistrave u vendos me sukses.'''",
'revdel-restore'              => 'ndrro dukshmëninë',
'pagehist'                    => 'Historiku i faqes',
'deletedhist'                 => 'Historiku i grisjeve',
'revdelete-content'           => 'përmbajtja',
'revdelete-summary'           => 'përmbledhja redaktimit',
'revdelete-uname'             => 'përdoruesi',
'revdelete-restricted'        => 'u vendosën kufizime për administruesit',
'revdelete-unrestricted'      => 'u hoqën kufizimet për administruesit',
'revdelete-hid'               => 'u fsheh $1',
'revdelete-unhid'             => 'u tregua $1',
'revdelete-log-message'       => '$1 për $2 {{PLURAL:$2|version|versione}}',
'logdelete-log-message'       => '$1 për $2 {{PLURAL:$2|ngjarje|ngjarje}}',
'revdelete-edit-reasonlist'   => 'Arsye grisjeje për redaktimet',

# Suppression log
'suppressionlog'     => 'Regjistri i ndalimeve',
'suppressionlogtext' => 'Më poshtë jepet një listë grisjesh dhe bllokimesh që kanë të bëjnë me përmbatje të fshehur nga administruesit. Shikoni [[Special:IPBlockList|listën e IP bllokimeve]] për një listë të bllokimeve dhe përzënieve në fuqi.',

# History merging
'mergehistory'                     => 'Bashko historikët e faqeve',
'mergehistory-header'              => 'Kjo faqe ju lejon bashkimin e versionet e historikut të një faqeje "burim" në një faqe "mbledhje".
Sigurohuni që ky ndryshim do të ruajë rrjedhshmërinë e historikut të faqes.',
'mergehistory-box'                 => 'Bashkoni versionet e dy faqeve:',
'mergehistory-from'                => 'Faqja burim:',
'mergehistory-into'                => 'Faqja mbledhëse:',
'mergehistory-list'                => 'Historik redaktimi i bashkueshëm',
'mergehistory-merge'               => 'Versionet vijuese të [[:$1]] mund të bashkohen në [[:$2]].
Zgjidhni butonin rrethor në kolonë për të bashkuar vetëm versionet e krijuara aty dhe më parë kohës së përzgjedhur.
Kini kujdes se përdorimi i lidhjeve të shfletimit do të ndryshojë përzgjedhjen tuaj.',
'mergehistory-go'                  => 'Trego redaktimet e bashkueshme',
'mergehistory-submit'              => 'Bashko versionet',
'mergehistory-empty'               => 'Nuk ka versione të bashkueshme.',
'mergehistory-success'             => '$3 {{PLURAL:$3|version|versione}} të [[:$1]] janë bashkuar me sukses në [[:$2]].',
'mergehistory-fail'                => 'Nuk munda të bashkoj historikun, ju lutem kontrolloni përzgjedhjen e faqes dhe të kohës.',
'mergehistory-no-source'           => 'Faqja e burimit $1 nuk ekziston.',
'mergehistory-no-destination'      => 'Faqja mbledhëse $1 nuk ekzsiton.',
'mergehistory-invalid-source'      => 'Faqja e burimit duhet të ketë titull të vlefshëm.',
'mergehistory-invalid-destination' => 'Faqja mbledhëse duhet të ketë titull të vlefshëm.',
'mergehistory-autocomment'         => 'U bashkua [[:$1]] në [[:$2]]',
'mergehistory-comment'             => 'U bashkua [[:$1]] në [[:$2]]: $3',
'mergehistory-same-destination'    => 'Burimi dhe faqja e përcaktimit nuk mund të jenë të njëjta',

# Merge log
'mergelog'           => 'Regjistri i bashkimeve',
'pagemerge-logentry' => 'u bashkua [[$1]] në [[$2]] (versione deri më $3)',
'revertmerge'        => 'Çkape',
'mergelogpagetext'   => 'Më poshtë jepet një listë e bashkimeve së fundmi nga historiku i një faqeje në historikun e një faqeje tjetër.',

# Diffs
'history-title'           => 'Historiku i redaktimeve për "$1"',
'difference'              => '(Dallimet midis verzioneve)',
'lineno'                  => 'Rreshti $1:',
'compareselectedversions' => 'Krahasoni versionet e zgjedhme',
'editundo'                => 'ktheje',
'diff-multi'              => '({{PLURAL:$1|Një redaktim ndërmjet nuk është|$1 redaktime ndërmjet nuk janë}} treguar.)',

# Search results
'searchresults'                    => 'Rezultatet e kërkimit',
'searchresults-title'              => 'Rezultatet e kërkimit për "$1"',
'searchresulttext'                 => 'Për mâ shumë informata rreth kërkimit në {{SITENAME}} shih [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Keni kërkue \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|tâna faqet që nisin me "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|tâna faqet që lidhen me "$1"]])',
'searchsubtitleinvalid'            => "Keni kërkue '''$1'''",
'toomanymatches'                   => 'Ka tepër shumë përputhje, provoni nji kërkesë mâ të ngushtë',
'titlematches'                     => 'Tituj që përputhen',
'notitlematches'                   => 'Nuk ka përputhje në tituj',
'textmatches'                      => 'Përputhje në tekst',
'notextmatches'                    => 'Nuk ka përputhje tekstuale në asnji faqe',
'prevn'                            => 'e përparme {{PLURAL:$1|$1}}',
'nextn'                            => 'e ardhshme {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|rezultat|rezultate}} të mâhershme',
'nextn-title'                      => '$1 {{PLURAL:$1|rezultat|rezultate}} të ardhshme',
'shown-title'                      => 'Trego $1 {{PLURAL:$1|rezultat|rezultate}} për faqe',
'viewprevnext'                     => 'Shih ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opcionet e kërkimit',
'searchmenu-exists'                => "'''Në këtë wiki âsht nji faqe me titullin \"[[:\$1]]\"'''",
'searchmenu-new'                   => "'''Krijo faqen \"[[:\$1]]\" në këtë wiki!'''",
'searchhelp-url'                   => 'Help:Përmbajtja',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Shfleto faqet me këtë prefiks]]',
'searchprofile-articles'           => 'Faqet me përmbajtje',
'searchprofile-project'            => 'Faqet e ndihmës dhe projekteve',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Gjithçka',
'searchprofile-advanced'           => 'Detajshëm',
'searchprofile-articles-tooltip'   => 'Kërko në $1',
'searchprofile-project-tooltip'    => 'Kërko në $1',
'searchprofile-images-tooltip'     => 'Kërko skedarë',
'searchprofile-everything-tooltip' => 'Kërko krejt përmbajtjen (përfshi edhe faqet e diskutimit)',
'searchprofile-advanced-tooltip'   => 'Kërkimi në hapësina',
'search-result-size'               => '$1 ({{PLURAL:$2|1 fjalë|$2 fjalë}})',
'search-result-score'              => 'Relevanca: $1%',
'search-redirect'                  => '(përcjellje $1)',
'search-section'                   => '(sekcioni $1)',
'search-suggest'                   => 'Mos menduet: $1',
'search-interwiki-caption'         => 'Projektet simotra',
'search-interwiki-default'         => '$1 rezultate:',
'search-interwiki-more'            => '(mâ shumë)',
'search-mwsuggest-enabled'         => 'me parahedhje',
'search-mwsuggest-disabled'        => "s'ka parahedhje",
'search-relatedarticle'            => 'Të ngjajshme',
'mwsuggest-disable'                => 'Deaktivizo sygjerimet me AJAX',
'searcheverything-enable'          => 'Kërko në tâna hapësinat',
'searchrelated'                    => 'të ngjajshme',
'searchall'                        => 'tâna',
'showingresults'                   => "Mâ poshtë {{PLURAL:$1|tregohet '''1''' rezultat|tregohen '''$1''' rezultate}} që nisin me #'''$2'''.",
'showingresultsnum'                => "Mâ poshtë {{PLURAL:$3|tregohet '''1''' rezultat|tregohen '''$3''' rezultate}} që nisin me #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultati '''$1''' prej '''$3'''|Rezultatet '''$1 - $2''' prej '''$3'''}} për '''$4'''",
'nonefound'                        => "'''Shenim''': Vetëm disa hapësina kërkohen me t'lême.
Provoni me ia parashtue kërkesës tuej ''tâna:'' që me lypë tânë përmbajtjen (përfshî edhe diskutimet, shabllonat, etj.), ose përdorni hapësinën e dëshirueme si parashtesë.",
'search-nonefound'                 => 'Nuk ka rezultate që përputhen me kërkesën.',
'powersearch'                      => 'Kërkimi i detajshëm',
'powersearch-legend'               => 'Kërkimi i detajshëm',
'powersearch-ns'                   => 'Kërkimi në hapësina:',
'powersearch-redir'                => 'Listo përcjelljet',
'powersearch-field'                => 'Kërko',
'powersearch-togglelabel'          => 'Zgjedh:',
'powersearch-toggleall'            => 'Tâna',
'powersearch-togglenone'           => 'Asnji',
'search-external'                  => 'Kërkim jashtë',
'searchdisabled'                   => '{{SITENAME}} kërkimi âsht deaktivue.
Ndërkohë mundeni me lypë me Google.
Vini re se indeksat e tyne të përmbajtjes së {{SITENAME}} munden me qenë të vjetëruem.',

# Quickbar
'qbsettings'               => 'Vegla të shpejta',
'qbsettings-none'          => 'Asnji',
'qbsettings-fixedleft'     => 'Lidhun majtas',
'qbsettings-fixedright'    => 'Lidhun djathtas',
'qbsettings-floatingleft'  => 'Pezull majtas',
'qbsettings-floatingright' => 'Pezull djathtas',

# Preferences page
'preferences'                   => 'Parapëlqimet',
'mypreferences'                 => 'Parapëlqimet e mija',
'prefs-edits'                   => 'Numri i redaktimeve:',
'prefsnologin'                  => 'Nuk jeni kyçë',
'prefsnologintext'              => 'Duheni me qenë <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} i kyçun]</span> për me i caktue parapëlqimet.',
'changepassword'                => 'Ndërrimi i fjalëkalimit',
'prefs-skin'                    => 'Doka',
'skin-preview'                  => 'Parapâmja',
'prefs-math'                    => 'Formulë',
'datedefault'                   => "S'ka parapëlqim",
'prefs-datetime'                => 'Data dhe ora',
'prefs-personal'                => 'Profili i përdoruesit',
'prefs-rc'                      => 'Ndryshimet e freskëta',
'prefs-watchlist'               => 'Lista e mbikëqyrjeve',
'prefs-watchlist-days'          => 'Numri i ditëve me i tregue në listën e mbikëqyrjeve:',
'prefs-watchlist-days-max'      => 'Maksimalisht 7 ditë',
'prefs-watchlist-edits'         => 'Numri maksimal i ndryshimeve që tregohen në listën e zgjânueme të mbikëqyrjes:',
'prefs-watchlist-edits-max'     => 'Numri maksimal: 1000',
'prefs-misc'                    => 'Të ndryshme',
'prefs-resetpass'               => 'Ndryshimi i fjalëkalimit',
'prefs-email'                   => 'Opcionet për email',
'prefs-rendering'               => 'Pâmja',
'saveprefs'                     => 'Regjistro',
'resetprefs'                    => 'Fshij ndryshimet e paruejtuna',
'restoreprefs'                  => 'Kthe tâna përcaktimet si në fillim',
'prefs-editing'                 => 'Tue redaktue',
'prefs-edit-boxsize'            => 'Madhësia e dritares redaktuese.',
'rows'                          => 'Rreshta:',
'columns'                       => 'Kolona:',
'searchresultshead'             => 'Kërkimi',
'resultsperpage'                => 'Gjetje për faqe:',
'contextlines'                  => 'Rreshta për gjetje:',
'contextchars'                  => 'Konteksti për rresht:',
'stub-threshold'                => 'Pragu për formatimin e <a href="#" class="stub">vegzave të cungueme</a> në (byte):',
'recentchangesdays'             => 'Numri i ditëve për me i tregue te ndryshimet e freskëta:',
'recentchangesdays-max'         => '(maksimum $1 {{PLURAL:$1|ditë|ditë}})',
'recentchangescount'            => 'Numri i redaktimeve me u tregue:',
'prefs-help-recentchangescount' => 'Kjo përfshin ndryshimet e freskëta, historikun e faqes dhe regjistrat.',
'savedprefs'                    => 'Parapëlqimet tueja janë ruejtë.',
'timezonelegend'                => 'Zona kohore:',
'localtime'                     => 'Ora lokale:',
'timezoneuseserverdefault'      => 'Përdor të paracaktuemen e serverit',
'timezoneuseoffset'             => 'Tjetër (specifiko kcimin)',
'timezoneoffset'                => 'Kcimi¹:',
'servertime'                    => 'Ora e serverit:',
'guesstimezone'                 => 'Mbush prej shfletuesit:',
'timezoneregion-africa'         => 'Afrikë',
'timezoneregion-america'        => 'Amerikë',
'timezoneregion-antarctica'     => 'Antarktik',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Azi',
'timezoneregion-atlantic'       => 'Oqeani Atlantik',
'timezoneregion-australia'      => 'Australi',
'timezoneregion-europe'         => 'Europë',
'timezoneregion-indian'         => 'Oqeani Indian',
'timezoneregion-pacific'        => 'Oqeani Paqësor',
'allowemail'                    => 'Lejo emaila prej përdoruesve tjerë',
'prefs-searchoptions'           => 'Opcionet e kërkimit',
'prefs-namespaces'              => 'Hapësinat',
'defaultns'                     => 'Përndryshe kërko në këto hapësina:',
'default'                       => 'e paracaktueme',
'prefs-files'                   => 'Skedat',
'prefs-custom-css'              => 'CSS i përpunuem',
'prefs-custom-js'               => 'JavaScripti i përpunuem',
'prefs-reset-intro'             => 'Mundeni me përdorë këtë faqe për me i kthy parapëlqimet tueja në ato të paracaktuemet e faqes.
Kjo nuk mundet me u zhbâ.',
'prefs-emailconfirm-label'      => 'Konfirmimi i emailit:',
'youremail'                     => 'Adresa e email-it*',
'username'                      => 'Nofka e përdoruesit:',
'uid'                           => 'Nr. i identifikimit:',
'prefs-memberingroups'          => 'Anëtar i {{PLURAL:$1|grupit|grupeve}}:',
'yourrealname'                  => 'Emri juej i vërtetë*',
'yourlanguage'                  => 'Ndërfaqja gjuhësore',
'yournick'                      => 'Nofka :',
'badsig'                        => 'Sintaksa e nënshkrimit asht e pavlefshme, kontrolloni HTML-n.',
'badsiglength'                  => 'Emri i zgjedhun asht shumë i gjatë; duhet me pas ma pak se $1 shkronja',
'yourgender'                    => 'Gjinia:',
'gender-unknown'                => 'Pacaktue',
'gender-male'                   => 'Mashkull',
'gender-female'                 => 'Femën',
'prefs-help-gender'             => 'Opcionale: përdoret për adresim korrekt në relacion me gjininë nga softwarei.
Kjo informatë del publike.',
'email'                         => 'Email',
'prefs-help-realname'           => 'Emni i vërtetë âsht opcional.
Nëse zgjedhni me e dhânë, ky do të përdoret për me jua atribuue punën.',
'prefs-help-email'              => 'Emaili âsht opcional, por mundëson që fjalëkalimi i ri me mujtë me ju dërgue me email nëse e harroni.
Mundeni me zgjedhe që të tjerët me ju kontaktue përmjet faqe së diskutimit pa e zbulue identitetin tuej të vërtetë.',
'prefs-help-email-required'     => 'Adresa e emailit âsht e domosdoshme.',
'prefs-info'                    => 'Informatat bazike',
'prefs-i18n'                    => 'Internacionalizimi',
'prefs-signature'               => 'Firma',

# User rights
'userrights'                  => 'Ndrysho privilegjet e përdoruesve',
'userrights-lookup-user'      => 'Ndrysho grupet e përdoruesit',
'userrights-user-editname'    => 'Fusni emrin e përdoruesit:',
'editusergroup'               => 'Redakto grupet e përdoruesve',
'editinguser'                 => "Duke ndryshuar privilegjet e përdoruesit '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Anëtarësimi tek grupet',
'saveusergroups'              => 'Ruaj Grupin e Përdoruesve',
'userrights-groupsmember'     => 'Anëtar i:',
'userrights-groups-help'      => 'Mund të ndryshoni anëtarësimin e këtij përdoruesi në grupe:
* Kutia e zgjedhur shënon që përdoruesi është anëtar në atë grup
* Kutia e pazgjedhur shënon që përdoruesi nuk është anëtar në atë grup
* Një * shënon që nuk mund ta hiqni grupin pasi ta keni shtuar (dhe anasjelltas).',
'userrights-reason'           => 'Arsyeja:',
'userrights-no-interwiki'     => 'Nuk keni leje për të ndryshuar privilegjet e përdoruesve në wiki të tjera.',
'userrights-nodatabase'       => 'Regjistri $1 nuk ekziston ose nuk është vendor.',
'userrights-nologin'          => 'Duhet të [[Special:UserLogin|hyni brenda]] me një llogari administrative për të ndryshuar privilegjet e përdoruesve.',
'userrights-notallowed'       => 'Llogaria juaj nuk ju lejon të ndryshoni privilegjet e përdoruesve.',
'userrights-changeable-col'   => 'Grupe që mund të ndryshoni',
'userrights-unchangeable-col' => "Grupe që s'mund të ndryshoni",

# Groups
'group'               => 'Grupi:',
'group-user'          => 'Përdorues',
'group-autoconfirmed' => 'Përdorues të vërtetuar automatikisht',
'group-bot'           => 'Robot',
'group-sysop'         => 'Administruesit',
'group-bureaucrat'    => 'Burokrat',
'group-suppress'      => 'Kujdestari',
'group-all'           => '(të gjitha)',

'group-user-member'          => 'Përdorues',
'group-autoconfirmed-member' => 'Përdorues i vërtetuar automatikisht',
'group-bot-member'           => 'Robot',
'group-sysop-member'         => 'Administrues',
'group-bureaucrat-member'    => 'Burokrat',
'group-suppress-member'      => 'Kujdestari',

'grouppage-user'          => '{{ns:project}}:Përdorues',
'grouppage-autoconfirmed' => '{{ns:project}}:Përdorues të vërtetuar automatikisht',
'grouppage-bot'           => '{{ns:project}}:Robotë',
'grouppage-sysop'         => '{{ns:project}}:Administruesit',
'grouppage-bureaucrat'    => '{{ns:project}}:Burokratë',
'grouppage-suppress'      => '{{ns:project}}:Kujdestari',

# Rights
'right-read'             => 'Lexo faqe',
'right-edit'             => 'Redakto faqet',
'right-createpage'       => 'Hap faqe (që nuk janë faqe diskutimi)',
'right-createtalk'       => 'Hap faqe diskutimi',
'right-createaccount'    => 'Hap llogari të re',
'right-minoredit'        => 'Shëno redaktimet si të vogla',
'right-move'             => 'Lëviz faqet',
'right-move-subpages'    => 'Lëviz faqet me nënfaqet e tyre',
'right-movefile'         => 'Lëviz skedarët',
'right-suppressredirect' => 'Mos krijo zhvendosje nga emri i vjetër kur lëvizë një faqe',
'right-upload'           => 'Ngarko skedarë',
'right-reupload'         => 'Ringarko skedën ekzistuese',
'right-reupload-own'     => 'Ringarko skedën ekzistuese të ngarkuar vetë',
'right-upload_by_url'    => 'Ngarko skedë nga ndonjë URL',
'right-autoconfirmed'    => 'Redakto faqet gjysmë të mbrojtura',
'right-bot'              => 'Trajtohu si një proces automatik',
'right-delete'           => 'Gris faqet',
'right-bigdelete'        => 'Gris faqet me histori të gjata',
'right-deleterevision'   => 'Grisi dhe riktheji revizionet specifike të faqeve',
'right-browsearchive'    => 'Kërko faqe të grisura',
'right-undelete'         => 'Rikthe faqen',
'right-block'            => 'Blloko përdoruesit tjerë nga editimi',
'right-blockemail'       => 'Blloko përdoruesin që të mos dërgojë postë elektronike',
'right-hideuser'         => 'Blloko përdorues, duke fshehur nga publiku',
'right-protect'          => 'Ndrysho nivelin mbrojtës dhe redakto faqet e mbrojtura',
'right-editprotected'    => 'Redakto faqet e mbrojtura (pa ndryshuar mbrojtjen)',
'right-editinterface'    => 'Ndrysho parapamjen e përdoruesit',
'right-editusercssjs'    => 'Redakto skedat CSS dhe JS të përdoruesve tjerë',
'right-editusercss'      => 'Redakto skedat CSS të përdoruesve tjerë',
'right-edituserjs'       => 'Redakto skedat JS të përdoruesve tjerë',
'right-noratelimit'      => 'Mos u prek nga kufizimet e vlerësimit',
'right-import'           => 'Importo faqe nga wiki tjera',
'right-unwatchedpages'   => 'Shiko listën e faqeve të pa vëzhguara',
'right-mergehistory'     => 'Bashko historinë e faqeve',
'right-userrights'       => 'Redakto të gjitha të drejtat e përdoruesit',

# User rights log
'rightslog'      => 'Regjsitri i tagrit të përdoruesve',
'rightslogtext'  => 'Ky është një regjistër për ndryshimet e titujve të përdoruesve.',
'rightslogentry' => 'të drejtat e $1 u ndryshuan prej $2 në $3',
'rightsnone'     => '(asgjë)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'lexo këtë faqe',
'action-edit'                 => 'redakto këtë faqe',
'action-createpage'           => 'hapë faqe',
'action-createtalk'           => 'hap faqe diskutimi',
'action-createaccount'        => 'hapë këtë llogari',
'action-minoredit'            => 'shëno këtë redaktim si të vogël',
'action-move'                 => 'lëviz këtë faqe',
'action-move-subpages'        => 'zhvendos këtë faqe dhe nënfaqet që ka',
'action-movefile'             => 'lëviz këtë skedë',
'action-upload'               => 'ngarko këtë skedë',
'action-reupload'             => 'rishkruaj këtë skedë ekzistuese',
'action-upload_by_url'        => 'ngarko këtë skedë nga një URL',
'action-delete'               => 'grise këtë faqe',
'action-deleterevision'       => 'grise këtë revizion',
'action-deletedhistory'       => 'shiko historinë e kësaj faqeje të grisur',
'action-browsearchive'        => 'kërko faqe të grisura',
'action-undelete'             => 'Restauro këtë faqe',
'action-suppressrevision'     => 'rishiko dhe rikthe këtë revizion të fshehur',
'action-block'                => 'blloko përdoruesin',
'action-protect'              => 'ndrysho nivelin e mbrojtjes për këtë faqe',
'action-import'               => 'importo këtë faqe nga një wiki tjetër',
'action-patrol'               => 'shëno redaktimin e tjerëve si të patrulluar',
'action-autopatrol'           => 'shëno redaktimet tua si të patrulluara',
'action-unwatchedpages'       => 'shiko listën e faqeve të pa vrojtuara',
'action-mergehistory'         => 'bashko historikun e kësaj faqeje',
'action-userrights'           => 'ndrysho të gjitha të drejtat e përdoruesit',
'action-userrights-interwiki' => 'ndrysho të drejtat e përdoruesve në wiki-t tjera',
'action-siteadmin'            => 'mbyll ose hap bazën e të dhënave',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|ndryshim|ndryshime}}',
'recentchanges'                  => 'Ndryshimet e fundit',
'recentchanges-legend'           => 'Opcionet e ndryshimeve të reja',
'recentchangestext'              => 'Ndiqni ndryshime së fundmi tek kjo faqe.',
'recentchanges-feed-description' => 'Përcjelli ndryshimet mâ të reja të këtij wiki në këtë feed.',
'recentchanges-label-newpage'    => 'Ky redaktim krijoi një faqe të re',
'rcnote'                         => "Mâ poshtë {{PLURAL:$1|âsht '''1''' ndryshim|janë '''$1''' ndryshimet e fundit}} në {{PLURAL:$2|ditën|'''$2''' ditët}} e fundit, prej $5, $4.",
'rclistfrom'                     => 'Trego ndryshimet e reja tue fillue prej $1',
'rcshowhideminor'                => '$1 redaktimet e vogla',
'rcshowhidebots'                 => 'botat në $1',
'rcshowhideliu'                  => '$1 përdorues të kyçun',
'rcshowhideanons'                => '$1 përdorues anonim',
'rcshowhidepatr'                 => '$1 redaktime të patrullueme',
'rcshowhidemine'                 => '$1 redaktimet e mija',
'rclinks'                        => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'diff'                           => 'ndrysh',
'hist'                           => 'hist',
'hide'                           => 'msheh',
'show'                           => 'kallzo',
'minoreditletter'                => 'v',
'newpageletter'                  => 'R',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Trego detajet (lyp JavaScript)',
'rc-enhanced-hide'               => 'Mshef detajet',

# Recent changes linked
'recentchangeslinked'         => 'Ndryshimet e ndërlidhuna',
'recentchangeslinked-feed'    => 'Ndryshimet fqinje',
'recentchangeslinked-toolbox' => 'Ndryshimet fqinje',
'recentchangeslinked-title'   => 'Ndryshimet në lidhje me "$1"',
'recentchangeslinked-summary' => "Kjo âsht nji listë e ndryshimeve të fundit në faqe që lidhen në artikullin e dhânë (apo që janë në kategorinë e dhânë).
Faqet në [[Special:Watchlist|listën tuej të mbikëqyrjes]] janë '''të theksueme'''.",
'recentchangeslinked-page'    => 'Emni i faqes:',
'recentchangeslinked-to'      => 'Trego ndryshimet në faqet që lidhen në këtë faqe',

# Upload
'upload'        => 'Ngarkoni skeda',
'uploadlogpage' => 'Regjistri i ngarkimeve',
'uploadedimage' => 'ngarkue "[[$1]]"',

# File description page
'file-anchor-link'          => 'Figura',
'filehist'                  => 'Historiku i dosjes',
'filehist-help'             => 'Kliko në datë/orë për me pa skedën si âsht dukë në atë kohë.',
'filehist-current'          => 'aktualisht',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Pamja e vogël',
'filehist-thumbtext'        => 'Pâmja e vogël e verzionit të $1',
'filehist-user'             => 'Përdoruesi',
'filehist-dimensions'       => 'Përmasat',
'filehist-filesize'         => 'Madhësia e figurës/skedës',
'filehist-comment'          => 'Koment',
'imagelinks'                => 'Vegzat e skedave',
'linkstoimage'              => '{{PLURAL:$1|vegzat|$1 vegza}} në vijim lidhen me këtë skedë:',
'sharedupload'              => 'Kjo skedë âsht preh $1 dhe ka mundësi që përdoret prej projekteve tjera.',
'uploadnewversion-linktext' => 'Ngarko verzion të ri të kësaj skede',

# File deletion
'filedelete-reason-otherlist' => 'Arsyje tjera',

# MIME search
'download' => 'shkarkim',

# Random page
'randompage' => 'Artikull i rastit',

# Statistics
'statistics' => 'Statistika',

'withoutinterwiki' => 'Artikuj pa lidhje interwiki',

# Miscellaneous special pages
'nbytes'        => '$1 {{PLURAL:$1|byte|byte}}',
'nlinks'        => '$1 lidhje',
'nmembers'      => '$1 {{PLURAL:$1|antar|antarë}}',
'prefixindex'   => 'Tâna faqet me prefiks',
'newpages'      => 'Faqe të reja',
'move'          => 'Zhvendose',
'movethispage'  => 'Zhvendos këtë faqe',
'pager-newer-n' => '{{PLURAL:$1|mâ e re 1|mâ të reja $1}}',
'pager-older-n' => '{{PLURAL:$1|mâ e vjetër 1|mâ të vjetra $1}}',

# Book sources
'booksources'               => 'Burime librash',
'booksources-search-legend' => 'Kërkim në burime librash',
'booksources-go'            => 'Shko',

# Special:Log
'log' => 'Regjistrat',

# Special:AllPages
'allpages'       => 'Tâna faqet',
'alphaindexline' => '$1 deri në $2',
'prevpage'       => 'Faqja paraprake ($1)',
'allpagesfrom'   => 'Trego faqet tue fillue me:',
'allpagesto'     => 'Trego faqet që mbarojnë me:',
'allarticles'    => 'Tâna faqet',
'allpagessubmit' => 'Shko',

# Special:Categories
'categories' => 'Kategori',

# Special:LinkSearch
'linksearch' => 'Vegzat e jashtme',

# Special:Log/newusers
'newuserlogpage'          => 'Regjistri i krijimit të përdoruesve',
'newuserlog-create-entry' => 'Përdorues i ri',

# Special:ListGroupRights
'listgrouprights-members' => '(lista e antarëve)',

# E-mail user
'emailuser' => 'Çoji postel këtij përdoruesi',

# Watchlist
'watchlist'         => 'Mbikëqyrjet e mija',
'mywatchlist'       => 'Lista mbikqyrëse',
'addedwatch'        => 'U shtu te lista mbikqyrëse',
'addedwatchtext'    => "Faqja \"[[:\$1]]\" iu shtue [[Special:Watchlist|listës suej të mbikëqyrjes]].
Ndryshimet e ardhshme të kësaj faqeje dhe të faqes gjegjëse të diskutimit kanë me u listue këte, dhe faqja do të tregohet '''e theksueme''' në [[Special:RecentChanges|listën e ndryshimeve të fundit]] për me râ në sy.",
'removedwatch'      => 'U hjek nga lista mibkqyrëse',
'removedwatchtext'  => 'Faqja "[[:$1]]" âsht hjekë prej [[Special:Watchlist|listës së mbikëqyrjes]].',
'watch'             => 'Mbikqyre',
'watchthispage'     => 'Mbikëqyr këtë faqe',
'unwatch'           => 'Çmbikqyre',
'watchlist-details' => '{{PLURAL:$1|$1 faqe|$1 faqe}} në listën tuej të mbikëqyrjes, pa i numrue faqet e diskutimit.',
'wlshowlast'        => 'Trego $1 orët $2 ditët $3 e fundit',
'watchlist-options' => 'Opcionet e listës së mbikëqyrjes',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Tuj mbikqyrë...',
'unwatching' => 'Tuj çmbikqyrë...',

# Delete
'deletepage'            => 'Fshij faqen',
'confirmdeletetext'     => 'Jeni tue fshi nji faqe bashkë me krejt historikun e saj.
Ju lutemi konfirmoni që kjo âsht ajo që deshtët me bâ, që i keni të njoftuna konsekuencat, dhe që këtë jeni tue e bâ në përputhje me [[{{MediaWiki:Policy-url}}|politikat]].',
'actioncomplete'        => 'Veprimi u kry',
'deletedtext'           => '"<nowiki>$1</nowiki>" âsht fshi.
Shih $2 për regjistrin e fshimjeve të fundit.',
'deletedarticle'        => 'grisi "[[$1]]"',
'dellogpage'            => 'Regjistri i fshimjeve',
'deletecomment'         => 'Arsyeja:',
'deleteotherreason'     => 'Arsyet tjera/shtesë:',
'deletereasonotherlist' => 'Arsye tjetër',

# Rollback
'rollbacklink' => 'kthe mbrapsht',

# Protect
'protectlogpage'              => 'Regjistri i mbrojtjeve',
'protectedarticle'            => '"[[$1]]" i mbrojtun',
'modifiedarticleprotection'   => 'ndryshue nivelin e mbrojtjes të "[[$1]]"',
'protect-legend'              => 'Konfirmoni',
'protectcomment'              => 'Arsyeja:',
'protectexpiry'               => 'Afáti',
'protect_expiry_invalid'      => 'Data e skadimit asht e pasaktë.',
'protect_expiry_old'          => 'Data e skadimit asht në kohën kalueme.',
'protect-text'                => "Këtu muneni me shiku dhe me ndryshu nivelin e mbrojtjes për faqen '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Llogaria juej nuk ka privilegjet e nevojitme për me ndryshu nivelin e mbrojtjes. Kufizimet e kësaj faqe janë '''$1''':",
'protect-cascadeon'           => 'Kjo faqe aktualisht âsht e mbrojtun sepse përfshihet në {{PLURAL:$1|faqen që ka|faqet, të cilat kanë}} mbrojtje të përfshimjes.
Mundeni me ndryshue nivelin e mbrojtjes për këtë faqe, por kjo nuk ka me prekë mbrojtjen e përfshimjes.',
'protect-default'             => 'Lejo krejt përdoruesit',
'protect-fallback'            => 'Kushtëzo tagrin "$1"',
'protect-level-autoconfirmed' => 'Blloko përdoruesit e rij dhe të pakyçun',
'protect-level-sysop'         => 'Lejo veç administruesit',
'protect-summary-cascade'     => 'të mvaruna',
'protect-expiring'            => 'skadon me $1 (UTC)',
'protect-cascade'             => 'Mbrojtje e ndërlidhme - mbroj çdo faqe që përfshihet në këtë faqe.',
'protect-cantedit'            => "Nuk mund t'ia ndryshosh nivelin e mbrojtjes kësaj faqeje, sepse nuk ke leje me e redaktue.",
'restriction-type'            => 'Lejet:',
'restriction-level'           => 'Mbrojtjet:',

# Undelete
'undeletelink'     => 'shih/kthe',
'undeleteviewlink' => 'shih',
'undeletedarticle' => 'u rikthye "[[$1]]"',

# Namespace form on various pages
'namespace'      => 'Hapësira:',
'invert'         => 'inverzo zgjedhjen',
'blanknamespace' => '(Artikujt)',

# Contributions
'contributions'       => 'Kontributet',
'contributions-title' => 'Kontributet e përdoruesit në $1',
'mycontris'           => 'Redaktimet e mia',
'contribsub2'         => 'Për $1 ($2)',
'uctop'               => '(në krye)',
'month'               => 'Prej muejit (e mâ herët):',
'year'                => 'Prej vjetit (e mâ herët):',

'sp-contributions-newbies'  => 'Trego sall kontributet e përdoruesve të rij',
'sp-contributions-blocklog' => 'regjistri i bllokimeve',
'sp-contributions-talk'     => 'Diskuto',
'sp-contributions-search'   => 'Kërko te kontributet',
'sp-contributions-username' => 'Adresa IP ose përdoruesi:',
'sp-contributions-submit'   => 'Lyp',

# What links here
'whatlinkshere'            => 'Vegzat me këtu',
'whatlinkshere-title'      => 'Faqe që lidhen me "$1"',
'whatlinkshere-page'       => 'Faqja:',
'linkshere'                => "Faqet e mâposhtme lidhen në '''[[:$1]]''':",
'isredirect'               => 'faqe përcjellëse',
'istemplate'               => 'përfshirë',
'isimage'                  => 'vegëz në figurë',
'whatlinkshere-prev'       => '$1 {{PLURAL:$1|e përparme|të përparme}}',
'whatlinkshere-next'       => '$1 {{PLURAL:$1|e ardhshme|të ardhshme}}',
'whatlinkshere-links'      => '← lidhje',
'whatlinkshere-hideredirs' => '$1 përcjelljet',
'whatlinkshere-hidetrans'  => '$1 përfshimjet',
'whatlinkshere-hidelinks'  => '$1 vegzat',
'whatlinkshere-filters'    => 'Filterat',

# Block/unblock
'blockip'                  => 'Ndaloje përdoruesin',
'ipboptions'               => '2 orë:2 hours,1 ditë:1 day,3 ditë:3 days,1 javë:1 week,2 javë:2 weeks,1 muej:1 month,3 muej:3 months,6 muej:6 months,1 vjet:1 year,pa kufi:infinite',
'ipblocklist'              => 'Përdoruesit dhe adresat IP të bllokueme',
'blocklink'                => 'bllokoje',
'unblocklink'              => 'çblloko',
'change-blocklink'         => 'ndrro bllokun',
'contribslink'             => 'Ndihmesa',
'blocklogpage'             => 'Regjistri i bllokimeve',
'blocklogentry'            => 'bllokue [[$1]] për kohëzgjatje prej $2 $3',
'unblocklogentry'          => 'zhbllokue $1',
'block-log-flags-nocreate' => 'krijimi i llogarive âsht pamundësue',

# Move page
'movepagetext'     => "Me formularin e maposhtëm mund ta ndërrosh titullin e faqes, tue zhvendosë krejt prapavijën e saj te titulli i ri.

Titulli i vjetër do të bahet faqe përcjellëse te titulli e ri.

Mundesh t'i freskosh automatikisht përcjelljet që tregojnë në titullin e kryehershëm.

Nëse zgjedhë mos me i freskue, sigurohu që i ke kontrollue [[Special:DoubleRedirects|përcjelljet e dyfishta]] apo [[Special:BrokenRedirects|të damtueme]].

Ti je përgjegjës që vegzat me çue atje ku duhet.


Veneroje që faqja '''nuk''' do të zhvendoset nëse nji faqe me të njejtin titull tashma ekziston, përveç nëse asht e shprazët apo vetëm përcjellëse dhe nuk ka histori të redaktimit.

Kjo domethanë që mund ta rikthesh titullin e faqes aty ku ka qenë nëse ke ba gabim dhe nuk mund ta mbishkruesh nji faqe ekzistuese.


'''Kujdes!'''

Ky mund të jetë ndryshim drastik dhe i papritun për nji faqe të popullarizueme;

të lutem, sigurohu që i ke parasysh pasojat para se të vazhdosh.",
'movepagetalktext' => "Faqja e diskutimit që lidhet me këtë faqe do të zhvendoset automatikisht '''përveç:'''
*Nëse ekziston nji faqe e diskutimit nën titullin e ri, apo
*E shêjoni të pazgjedhun kutinë e mâposhtme.

Në këto raste, duheni me i bashkue manualisht këto faqe nëse dëshironi.",
'movearticle'      => 'Zhvendose faqen',
'newtitle'         => 'Te titulli i ri',
'move-watch'       => 'Mbikqyre kët faqe',
'movepagebtn'      => 'Zhvendose faqen',
'pagemovedsub'     => 'Zhvendosja u kry',
'movepage-moved'   => '\'\'\'"$1" âsht zhvendosë te "$2"\'\'\'',
'articleexists'    => 'Nji faqe me këtë titull tashmâ ekziston, apo keni zgjedhë nji titull të pavlefshëm.
Ju lutemi zgjedhni nji titull tjetër.',
'talkexists'       => "'''Vetë faqja u zhvendos me sukses, por faqja e diskutimit nuk mujti me u zhvendosë sepse tashmâ ekziston te titulli i ri.
Ju lutemi bashkoni manualisht.'''",
'movedto'          => 'zhvendosur te',
'movetalk'         => 'Zhvendos faqen gjegjëse të diskutimeve',
'1movedto2'        => '[[$1]] u zhvendos në [[$2]]',
'1movedto2_redir'  => '[[$1]] u zhvendos te [[$2]] përmjet përcjelljes',
'movelogpage'      => 'Regjistri i zhvendosjeve',
'movereason'       => 'Arsyeja:',
'revertmove'       => 'kthe mbrapsht',

# Export
'export' => 'Eksporto faqet',

# Thumbnails
'thumbnail-more'  => 'Zmadho',
'thumbnail_error' => 'Gabim gjatë krijimit të figurës përmbledhëse: $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Faqja juej e përdoruesit',
'tooltip-pt-mytalk'               => 'Faqja juej e diskutimeve',
'tooltip-pt-preferences'          => 'Parapëlqimet tuaja',
'tooltip-pt-watchlist'            => 'Lista e faqeve nën mbikqyrjen tuej.',
'tooltip-pt-mycontris'            => 'Lista e kontributeve tueja',
'tooltip-pt-login'                => 'Të këshillojmë me u kyçë; mirëpo, nuk asht e detyrueshme',
'tooltip-pt-logout'               => 'Dalje',
'tooltip-ca-talk'                 => 'Diskuto për përmbajtjen e faqes',
'tooltip-ca-edit'                 => "Mund ta redaktosh kët faqe. Përdore pullën >>Shfaqe parapamjen<< para se t'i krysh ndryshimet.",
'tooltip-ca-addsection'           => 'Nis nji sekcion të ri.',
'tooltip-ca-viewsource'           => 'Kjo faqe asht e mbrojtun. Mundesh veç me pa burimin e tekstit.',
'tooltip-ca-history'              => 'Verzionet e mâhershme të këtij artikulli',
'tooltip-ca-protect'              => 'Mbroje këtë faqe',
'tooltip-ca-delete'               => 'Fshije këtë faqe',
'tooltip-ca-undelete'             => 'Ktheji mbrapsht redaktimet e këtij artikulli para fshimjes',
'tooltip-ca-move'                 => 'Zhvendose faqen',
'tooltip-ca-watch'                => 'Shtoje këtë faqe në lisën e mbikëqyrjes',
'tooltip-ca-unwatch'              => 'Hiqe këtë faqe prej listës së mbikëqyrjes',
'tooltip-search'                  => 'Kërko në {{SITENAME}}',
'tooltip-search-go'               => 'Shko te faqja me emën të njejtë nëse ekziston',
'tooltip-search-fulltext'         => 'Lypi faqet me kët tekst',
'tooltip-p-logo'                  => 'Shko te faqja kryesore',
'tooltip-n-mainpage'              => 'Shko te faqja kryesore',
'tooltip-n-mainpage-description'  => 'Shihe faqen kryesore',
'tooltip-n-portal'                => 'Rreth projektit, çka mundesh me ba, ku me i gjetë gjanat',
'tooltip-n-currentevents'         => 'Informacion mâ i thukët rreth ndodhive aktuale',
'tooltip-n-recentchanges'         => 'Lista e ndryshimeve të freskëta në wiki',
'tooltip-n-randompage'            => 'Çele nji faqe rastësisht',
'tooltip-n-help'                  => 'Vendi ku mundeni me gjetë ndihmë.',
'tooltip-t-whatlinkshere'         => 'Lista e faqeve të wikit që lidhen këtu',
'tooltip-t-recentchangeslinked'   => 'Ndryshimet e freskëta në faqet që lidhen nga kjo faqe',
'tooltip-feed-rss'                => 'RSS feed për këtë faqe',
'tooltip-feed-atom'               => 'Atom feed për këtë faqe',
'tooltip-t-contributions'         => 'Shiko listën e kontributeve të këtij përdoruesi',
'tooltip-t-emailuser'             => 'Çoji postel këtij përdoruesi',
'tooltip-t-upload'                => 'Ngarko skeda',
'tooltip-t-specialpages'          => 'Lista e tana faqeve të posaçme',
'tooltip-t-print'                 => 'Version për shtyp i qësaj faqeje',
'tooltip-t-permalink'             => 'Vegza e përhershme te ky verzion i faqes',
'tooltip-ca-nstab-main'           => 'Shihe përmbajtjen e atikullit',
'tooltip-ca-nstab-user'           => 'Shih faqen e përdoruesit',
'tooltip-ca-nstab-media'          => 'Shih faqen e mediave',
'tooltip-ca-nstab-special'        => 'Kjo âsht faqe speciale. Nuk mundeni me redaktue këtë faqe',
'tooltip-ca-nstab-project'        => 'Shih faqen e projektit',
'tooltip-ca-nstab-image'          => 'Shih faqen e skedës',
'tooltip-ca-nstab-mediawiki'      => 'Shih mesazhin e sistemit',
'tooltip-ca-nstab-template'       => 'Shih stampën',
'tooltip-ca-nstab-help'           => 'Shih faqen e ndihmës',
'tooltip-ca-nstab-category'       => 'Shih faqen e kategorisë',
'tooltip-minoredit'               => 'Shêjoje si redaktim të vogël',
'tooltip-save'                    => 'Rueji ndryshimet',
'tooltip-preview'                 => 'Shih parapâmjen e ndryshimeve, ju lutemi përdoreni këtë para se me i ruejtë ndryshimet!',
'tooltip-diff'                    => 'Trego ndryshimet që i keni bâ Ju në tekst.',
'tooltip-compareselectedversions' => 'Shih dallimet midis dy verzioneve të zgjedhuna të kësaj faqeje.',
'tooltip-watch'                   => 'Mbikqyre këtë faqe',
'tooltip-recreate'                => 'Rikrijo faqen edhe nëse âsht fshi mâ herët',
'tooltip-upload'                  => 'Nis ngarkimin',
'tooltip-rollback'                => '"Kthe mbrapa" i kthen mbrapsht redaktimet e faqes aktuale prej kontribuuesit të fundit me nji klik',
'tooltip-undo'                    => '"Zhbâne" kthen mbrapsht këtë redaktim dhe çelë formularin e redaktimit në parapâmje.
Lejon dhânien e arsyes në përmbledhje.',

# Stylesheets
'common.css'      => '/* CSSi i vendosun këtu ka me u zbatue në tâna dukjet */',
'standard.css'    => '/* CSSi i vendosun këtu ka me i prekë përdoruesit e dukjes standarde */',
'nostalgia.css'   => '/* CSS i vendosun këtu ka me i prekë shfrytëzuesit e dukjes Nostalgia */',
'cologneblue.css' => '/* CSS i vendosun këtu ka me i prekë shfrytëzuesit e dukjes Cologne Blue */',
'monobook.css'    => '/* CSS i vednosun këtu ka me i prekë shfrytëzuesit e dukjes Monobook */',
'myskin.css'      => '/* CSSi i vednosun këtu ka me i prekë shfrytëzuesit e dukjes MySkin */',
'chick.css'       => '/* CSSi i vednosun këtu ka me i prekë shfrytëzuesit e dukjes Chick */',
'simple.css'      => '/* CSSi i vednosun këtu ka me i prekë shfrytëzuesit e dukjes Simple */',
'modern.css'      => '/* CSSi i vednosun këtu ka me i prekë shfrytëzuesit e dukjes Modern */',
'vector.css'      => '/* CSSi i vednosun këtu ka me i prekë shfrytëzuesit e dukjes Vector */',
'print.css'       => '/* CSSi i vednosun këtu ka me e prekë pamjen e shtypjes */',
'handheld.css'    => '/* CSSi i vednosun këtu ka me i prekë shfletuesit mobil (të dorës) në dukje e konfigurueme në $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Çdo JavaScript këtu ka me u ngarkue për të gjithë përdoruesit në secilën thirrje të faqes. */',
'standard.js'    => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Standard */',
'nostalgia.js'   => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Nostalgia */',
'cologneblue.js' => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Cologne Blue */',
'monobook.js'    => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen MonoBook */',
'myskin.js'      => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen MySkin */',
'chick.js'       => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Chick */',
'simple.js'      => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Simple */',
'modern.js'      => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Modern */',
'vector.js'      => '/* Çdo JavaScript këtu ka me u ngarkue për shfrytëzuesit që përdorin dukjen Vector */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata nuk janë aktivizue në këtë server.',
'nocreativecommons' => 'Creative Commons RDF metadata nuk janë aktivizue në këtë server.',
'notacceptable'     => 'Serveri i wikit nuk mundet me i ofrue të dhânat në formatin që kish mujtë me i lexue klienti juej.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Përdorues|Përdorues}} anonim të {{SITENAME}}',
'siteuser'         => 'Përdoruesi $1 i {{SITENAME}}',
'lastmodifiedatby' => 'Kjo faqe âsht redaktue së fundi me $2, $1 prej $3.',
'othercontribs'    => 'Bazue në punën e $1.',
'others'           => 'tjerët',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|përdorues|përdorues}} $1',
'creditspage'      => 'Kreditat e faqes',

# Browsing diffs
'previousdiff' => '← Redaktim mâ i vjetër',
'nextdiff'     => 'Redaktim ma i ri →',

# Media information
'file-info-size'       => '$1 × $2 pixela, madhësia e skedës: $3, tipi MIME: $4',
'file-nohires'         => '<small>Rezolucioni i plotë.</small>',
'svg-long-desc'        => 'skeda SVG, $1 × $2 pixela, madhësia: $3',
'show-big-image'       => 'Rezolucion i plotë',
'show-big-image-thumb' => '<small>Madhësia e parapâmjes: $1 × $2 pixela</small>',

# Bad image list
'bad_image_list' => 'Formati asht si vijon:

Merren parasysh vetëm elementet e radhituna (rreshtat që fillojnë me *).
Vegza e parë në rresht lidhet patjetër me nji skedë të keqe.
Krejt vegzat tjera në të njejtin rresht do të bajnë përjashtim, d.m.th. faqe ku skeda paraqitet mbrenda rreshtit.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Kjo skedë përmban hollësira tjera të cilat munen qi jan shtue nga kamera ose skaneri dixhital që është përdorur për ta krijuar. Nëse se skeda asht ndryshue nga gjendja origjinale, disa hollësira munen mos me pasqyru skedën e tashme.',
'metadata-expand'   => 'Tregoji detajet',
'metadata-collapse' => 'Mshefi detajet',
'metadata-fields'   => 'Fushat EXIF metadata që listohen në këtë mesazh do të përfshihen në faqen e figurës kur palohet tabela e metadatave.
Tjerat kanë me mbetë të mshefuna.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Ndryshoni kët figurë/skedë me një mjet të jashtëm',
'edit-externally-help' => '(Shih [http://www.mediawiki.org/wiki/Manual:External_editors udhëzimet e instalimit] për mâ shumë informata)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'krejt',
'namespacesall' => 'krejt',
'monthsall'     => 'krejt',

# Watchlist editing tools
'watchlisttools-view' => 'Shihi ndryshimet përkatëse',
'watchlisttools-edit' => 'Shih dhe redakto listën e mbikëqyrjes.',
'watchlisttools-raw'  => 'Redakto listën e mbikëqyrjes në kod',

# Special:SpecialPages
'specialpages' => 'Faqet e posaçme',

);
