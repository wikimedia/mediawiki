<?php
/** Albanian (Shqip)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andejkendej
 * @author Cradel
 * @author Dori
 * @author Eagleal
 * @author Ergon
 * @author Mdupont
 * @author MicroBoy
 * @author Mikullovci11
 * @author Olsi
 * @author Puntori
 * @author Techlik
 * @author The Evil IP address
 * @author Urhixidur
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Speciale',
	NS_TALK             => 'Diskutim',
	NS_USER             => 'Përdoruesi',
	NS_USER_TALK        => 'Përdoruesi_diskutim',
	NS_PROJECT_TALK     => '$1_diskutim',
	NS_FILE             => 'Skeda',
	NS_FILE_TALK        => 'Skeda_diskutim',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_diskutim',
	NS_TEMPLATE         => 'Stampa',
	NS_TEMPLATE_TALK    => 'Stampa_diskutim',
	NS_HELP             => 'Ndihmë',
	NS_HELP_TALK        => 'Ndihmë_diskutim',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Kategoria_diskutim',
);

$namespaceAliases = array(
	'Perdoruesi' => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
	'Figura' => NS_FILE,
	'Figura_diskutim' => NS_FILE_TALK,
	'Kategori' => NS_CATEGORY,
	'Kategori_Diskutim' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Preferences'               => array( 'Preferencat' ),
	'Upload'                    => array( 'Ngarko' ),
	'Listfiles'                 => array( 'ListaSkedave' ),
	'Newimages'                 => array( 'SkedaTëReja' ),
	'Listusers'                 => array( 'RreshtoPërdoruesit' ),
	'Statistics'                => array( 'Statistika' ),
	'Randompage'                => array( 'Rastësishme', 'FaqeRastësishme' ),
	'Uncategorizedpages'        => array( 'FaqeTëpakategorizuara' ),
	'Uncategorizedcategories'   => array( 'KategoriTëpakategorizuara' ),
	'Uncategorizedimages'       => array( 'SkedaTëpakategorizuara' ),
	'Uncategorizedtemplates'    => array( 'StampaTëpakategorizuara' ),
	'Unusedcategories'          => array( 'KategoriTëpapërdorura' ),
	'Unusedimages'              => array( 'SkedaTëpapërdorura' ),
	'Wantedpages'               => array( 'FaqeteDëshiruara' ),
	'Wantedcategories'          => array( 'KaetgoritëeDëshiruara' ),
	'Wantedfiles'               => array( 'SkedateDëshiruara' ),
	'Wantedtemplates'           => array( 'StampateDëshiruara' ),
	'Shortpages'                => array( 'FasheteShkurta' ),
	'Longpages'                 => array( 'FaqeteGjata' ),
	'Newpages'                  => array( 'FaqeteReja' ),
	'Ancientpages'              => array( 'FaqetAntike' ),
	'Protectedpages'            => array( 'FaqeteMbrojtura' ),
	'Protectedtitles'           => array( 'TitujteMbrojtur' ),
	'Allpages'                  => array( 'TëgjithaFaqet' ),
	'Specialpages'              => array( 'FaqetSpeciale' ),
	'Contributions'             => array( 'Kontributet' ),
	'Emailuser'                 => array( 'EmailPërdoruesit' ),
	'Confirmemail'              => array( 'KonfirmoEmail' ),
	'Whatlinkshere'             => array( 'LidhjetKëtu' ),
	'Movepage'                  => array( 'LëvizFaqe' ),
	'Blockme'                   => array( 'BllokomMua' ),
	'Booksources'               => array( 'BurimeteLibrave' ),
	'Categories'                => array( 'Kategori' ),
	'Export'                    => array( 'Eksporto' ),
	'Version'                   => array( 'Verzioni' ),
	'Allmessages'               => array( 'TëgjithaMesazhet' ),
	'Blockip'                   => array( 'BllokoIP' ),
	'Undelete'                  => array( 'Rikthe' ),
	'Import'                    => array( 'Importo' ),
	'Lockdb'                    => array( 'MbyllDB' ),
	'Unlockdb'                  => array( 'HapDB' ),
	'Mypage'                    => array( 'FaqjaIme' ),
	'Mytalk'                    => array( 'DiskutimiImë' ),
	'Mycontributions'           => array( 'KontributetëMiat' ),
	'Listadmins'                => array( 'RreshtoAdmin' ),
	'Listbots'                  => array( 'RreshtoBotët' ),
	'Popularpages'              => array( 'FaqetëFamshme' ),
	'Search'                    => array( 'Kërkim' ),
	'Blankpage'                 => array( 'FaqeBosh' ),
	'DeletedContributions'      => array( 'GrisKontributet' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#RIDREJTO', '#REDIRECT' ),
	'currentmonth'          => array( '1', 'MUAJIMOMENTAL', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'DITASOT', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DITASOT2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'EMRIIDITËSOT', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'SIVJET', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'KOHATANI', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'ORATANI', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'EMRIIMUAJITLOKAL', 'LOCALMONTHNAME' ),
	'localday'              => array( '1', 'DITALOKALE', 'LOCALDAY' ),
	'localday2'             => array( '1', 'DITALOKALE2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'EMRIIDITËSLOKALE', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'VITILOKAL', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'KOHALOKALE', 'LOCALTIME' ),
	'localhour'             => array( '1', 'ORALOKALE', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMRIFAQEVE', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMRIIARTIKUJVE', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMRIISKEDAVE', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMRIPËRDORUESVE', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NUMRIREDAKTIMEVE', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NUMRIISHIKIMEVE', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'EMRIFAQES', 'PAGENAME' ),
	'fullpagename'          => array( '1', 'EMRIIPLOTËIFAQES', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'EMRIIPLOTËIFAQESE', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'EMRIINËNFAQES', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'EMRIINËNFAQESE', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'EMRIIFAQESBAZË', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'EMRIIFAQESBAZËE', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'EMRIIFAQESSËDISKUTIMIT', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'EMRIIFAQESSËDISKUTIMITE', 'TALKPAGENAMEE' ),
	'subst'                 => array( '0', 'ZËVN', 'SUBST:' ),
	'img_thumbnail'         => array( '1', 'parapamje', 'pamje', 'thumbnail', 'thumb' ),
	'img_manualthumb'       => array( '1', 'parapamje=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'djathtas', 'right' ),
	'img_left'              => array( '1', 'majtas', 'left' ),
	'img_none'              => array( '1', 's\'ka', 'none' ),
	'img_center'            => array( '1', 'qëndër', 'qëndrore', 'center', 'centre' ),
	'img_framed'            => array( '1', 'i kornizuar', 'pa kornizë', 'kornizë', 'framed', 'enframed', 'frame' ),
	'img_page'              => array( '1', 'faqja=$1', 'faqja $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'lartdjathtas', 'lartdjathtas=$1', 'lartdjathtas $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'kufi', 'border' ),
	'img_baseline'          => array( '1', 'linjabazë', 'baseline' ),
	'img_sub'               => array( '1', 'nën', 'sub' ),
	'img_text_top'          => array( '1', 'tekst-top', 'text-top' ),
	'img_middle'            => array( '1', 'mes', 'middle' ),
	'img_bottom'            => array( '1', 'fund', 'bottom' ),
	'img_text_bottom'       => array( '1', 'tekst-fund', 'text-bottom' ),
	'img_link'              => array( '1', 'lidhje=$1', 'link=$1' ),
	'sitename'              => array( '1', 'EMRIIFAQES', 'SITENAME' ),
	'localurl'              => array( '0', 'URLLOKALE', 'LOCALURL:' ),
	'server'                => array( '0', 'SERVERI', 'SERVER' ),
	'servername'            => array( '0', 'EMRIISERVERIT', 'SERVERNAME' ),
	'grammar'               => array( '0', 'GRAMATIKA:', 'GRAMMAR:' ),
	'currentweek'           => array( '1', 'JAVAMOMENTALE', 'CURRENTWEEK' ),
	'plural'                => array( '0', 'SHUMËS:', 'PLURAL:' ),
	'language'              => array( '0', '#GJUHA:', '#LANGUAGE:' ),
	'special'               => array( '0', 'speciale', 'special' ),
);

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);
$defaultDateFormat = 'dmy';
$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Nënvizo lidhjet',
'tog-highlightbroken'         => 'Formato lidhjet e prishura <a href="" class="new">si kjo </a> (zgjedhore: si kjo<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Rregullo paragrafët',
'tog-hideminor'               => 'Fshih redaktimet e vogla në ndryshimet e fundit',
'tog-hidepatrolled'           => 'Fshih redaktimet e vrojtuara në ndryshimet e fundit',
'tog-newpageshidepatrolled'   => 'Fshih faqet e vrojtuara nga lista e faqeve të reja',
'tog-extendwatchlist'         => 'Zgjero listën mbikqyrëse të tregojë të tëra ndryshimet përkatëse',
'tog-usenewrc'                => 'Tregoji me formatin e ri (jo për të gjithë shfletuesit)',
'tog-numberheadings'          => 'Numëro automatikish mbishkrimet',
'tog-showtoolbar'             => 'Trego butonat e redaktimit',
'tog-editondblclick'          => 'Redakto faqet me dopjo-shtypje (JavaScript)',
'tog-editsection'             => 'Lejo redaktimin e seksioneve me [redakto] lidhje',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve me djathtas-shtypje<br /> mbi emrin e seksionit (JavaScript)',
'tog-showtoc'                 => 'Trego tabelën e përmbajtjeve<br />(për faqet me më shume se 3 tituj)',
'tog-rememberpassword'        => 'Mbaj mënd fjalëkalimin për vizitën e ardhshme (më së shumti për $1 {{PLURAL:$1|ditë|ditë}})',
'tog-watchcreations'          => 'Shto faqet që krijoj tek lista mbikqyrëse',
'tog-watchdefault'            => 'Shto faqet që redaktoj tek lista mbikqyrëse',
'tog-watchmoves'              => 'Shto faqet që zhvendos tek lista mbikqyrëse',
'tog-watchdeletion'           => 'Shto faqet që gris tek lista mbikqyrëse',
'tog-minordefault'            => 'Shëno të gjitha redaktimet si të vogla paraprakisht',
'tog-previewontop'            => 'Trego parapamjen përpara kutisë redaktuese, jo mbas saj',
'tog-previewonfirst'          => 'Trego parapamje në redaktim të parë',
'tog-nocache'                 => 'Mos ruaj kopje te faqeve',
'tog-enotifwatchlistpages'    => 'Më ço email kur ndryshojnë faqet nga lista ime mbikqyrëse',
'tog-enotifusertalkpages'     => 'Më ço email kur ndryshon faqja ime e diskutimit',
'tog-enotifminoredits'        => 'Më ço email kur ka redaktime të vogla të faqeve',
'tog-enotifrevealaddr'        => 'Trego adresën time në email-et njoftuese',
'tog-shownumberswatching'     => 'Trego numrin e përdoruesve mbikqyrës',
'tog-oldsig'                  => 'Pamje e nënshkrimit ekzistues:',
'tog-fancysig'                => 'Mos e përpuno nënshkrimin për formatim',
'tog-externaleditor'          => 'Përdor program të jashtëm për redaktime',
'tog-externaldiff'            => 'Përdor program të jashtëm për të treguar ndryshimet',
'tog-showjumplinks'           => 'Lejo lidhjet e afrueshmërisë "kapërce tek"',
'tog-uselivepreview'          => 'Trego parapamjen e menjëhershme (JavaScript) (Eksperimentale)',
'tog-forceeditsummary'        => 'Më pyet kur e le përmbledhjen e redaktimit bosh',
'tog-watchlisthideown'        => 'Fshih redaktimet e mia nga lista mbikqyrëse',
'tog-watchlisthidebots'       => 'Fshih redaktimet e robotëve nga lista mbikqyrëse',
'tog-watchlisthideminor'      => 'Fshih redaktimet e vogla nga lista mbikqyrëse',
'tog-watchlisthideliu'        => 'Fshih redaktimet e përdoruesve nga lista e vëzhgimit',
'tog-watchlisthideanons'      => 'Fshih redaktimet e anonimëve nga lista e vëzhgimit',
'tog-watchlisthidepatrolled'  => 'Fshih redaktimet e vrojtuara nga lista e vrojtimit',
'tog-ccmeonemails'            => 'Më dërgo kopje të mesazheve që u dërgoj të tjerëve',
'tog-diffonly'                => 'Mos trego përmbajtjen e faqes nën ndryshimin',
'tog-showhiddencats'          => 'Trego kategoritë e fshehura',
'tog-norollbackdiff'          => 'Ndryshimi pas rikthimit do të fshihet',

'underline-always'  => 'gjithmonë',
'underline-never'   => 'asnjëherë',
'underline-default' => 'sipas shfletuesit',

# Font style option in Special:Preferences
'editfont-style'     => 'Stili i shkrimit në faqen redaktuese:',
'editfont-default'   => 'Sipas shfletuesit',
'editfont-monospace' => 'Shkrim me gjerësi fikse',
'editfont-sansserif' => 'Shkrim pa serifa',
'editfont-serif'     => 'Stili shkrimit me dhëmbëza',

# Dates
'sunday'        => 'E diel',
'monday'        => 'E hënë',
'tuesday'       => 'E martë',
'wednesday'     => 'E mërkurë',
'thursday'      => 'E enjte',
'friday'        => 'E premte',
'saturday'      => 'E shtunë',
'sun'           => 'Dje',
'mon'           => 'Hën',
'tue'           => 'Mar',
'wed'           => 'Mër',
'thu'           => 'Enj',
'fri'           => 'Pre',
'sat'           => 'Shtu',
'january'       => 'Janar',
'february'      => 'Shkurt',
'march'         => 'Mars',
'april'         => 'Prill',
'may_long'      => 'Maj',
'june'          => 'Qershor',
'july'          => 'Korrik',
'august'        => 'Gusht',
'september'     => 'Shtator',
'october'       => 'Tetor',
'november'      => 'Nëntor',
'december'      => 'Dhjetor',
'january-gen'   => 'Janar',
'february-gen'  => 'Shkurt',
'march-gen'     => 'Mars',
'april-gen'     => 'Prill',
'may-gen'       => 'Maj',
'june-gen'      => 'Qershor',
'july-gen'      => 'Korrik',
'august-gen'    => 'Gusht',
'september-gen' => 'Shtator',
'october-gen'   => 'Tetor',
'november-gen'  => 'Nëntor',
'december-gen'  => 'Dhjetor',
'jan'           => 'Jan',
'feb'           => 'Shku',
'mar'           => 'Mar',
'apr'           => 'Pri',
'may'           => 'Maj',
'jun'           => 'Qer',
'jul'           => 'Korr',
'aug'           => 'Gush',
'sep'           => 'Shta',
'oct'           => 'Tet',
'nov'           => 'Nën',
'dec'           => 'Dhje',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Kategoria|Kategoritë}}',
'category_header'                => 'Artikuj në kategorinë "$1"',
'subcategories'                  => 'Nën-kategori',
'category-media-header'          => 'Skeda në kategori "$1"',
'category-empty'                 => "''Kjo kategori aktualisht nuk përmban asnjë faqe apo media.''",
'hidden-categories'              => '{{PLURAL:$1|Kategori e fshehur|Kategori të fshehura}}',
'hidden-category-category'       => 'Kategori të fshehura',
'category-subcat-count'          => '{{PLURAL:$2|Kjo kategori ka vetëm këtë nën-kategori.|Kjo kategori ka {{PLURAL:$1|këtë nën-kategori|$1 këto nën-kategori}}, nga $2 gjithësej.}}',
'category-subcat-count-limited'  => 'Kjo kategori ka {{PLURAL:$1|këtë nën-kategori|$1 këto nën-kategori}}.',
'category-article-count'         => '{{PLURAL:$2|Kjo kategori ka vetëm këtë faqe.|Kjo kategori ka {{PLURAL:$1|këtë faqe|$1 faqe}} nga $2 gjithësej.}}',
'category-article-count-limited' => '{{PLURAL:$1|Kjo faqe është|$1 faqe janë}} në këtë kategori.',
'category-file-count'            => '{{PLURAL:$2|Kjo kategori ka vetëm këtë skedë.|{{PLURAL:$1|Kjo skedë është|$1 skeda janë}} në këtë kategori, nga $2 gjithësej.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Kjo skedë është|$1 skeda janë}} në këtë kategori.',
'listingcontinuesabbrev'         => 'vazh.',
'index-category'                 => 'Faqe të indeksuara',
'noindex-category'               => 'Faqe jo të indeksuara',

'mainpagetext'      => "'''MediaWiki software u instalua me sukses.'''",
'mainpagedocfooter' => 'Për më shumë informata rreth përdorimit të softwerit wiki , ju lutem shikoni [http://meta.wikimedia.org/wiki/Help:Contents dokumentacionin përkatës].

== Sa për fillim==
* [http://www.mediawiki.org/wiki/Help:Configuration_settings Parazgjedhjet e MediaWiki-t]
* [http://www.mediawiki.org/wiki/Help:FAQ Pyetjet e shpeshta rreth MediaWiki-t]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Njoftime rreth MediaWiki-t]',

'about'         => 'Rreth',
'article'       => 'Artikulli',
'newwindow'     => '(hapet në një dritare të re)',
'cancel'        => 'Anulo',
'moredotdotdot' => 'Më shumë...',
'mypage'        => 'Faqja ime',
'mytalk'        => 'Diskutimet e mia',
'anontalk'      => 'Diskutimet për këtë IP',
'navigation'    => 'Shfleto',
'and'           => '&#32;dhe',

# Cologne Blue skin
'qbfind'         => 'Kërko',
'qbbrowse'       => 'Shfletoni',
'qbedit'         => 'Redaktoni',
'qbpageoptions'  => 'Opsionet e faqes',
'qbpageinfo'     => 'Informacion mbi faqen',
'qbmyoptions'    => 'Opsionet e mia',
'qbspecialpages' => 'Faqet speciale',
'faq'            => 'Pyetje e Përgjigje',
'faqpage'        => 'Project:Pyetje e Përgjigje',

# Vector skin
'vector-action-addsection'       => 'Fillo një temë të re',
'vector-action-delete'           => 'Grise',
'vector-action-move'             => 'Zhvendose',
'vector-action-protect'          => 'Mbroje',
'vector-action-undelete'         => 'Jo e grisur',
'vector-action-unprotect'        => 'Liroje',
'vector-simplesearch-preference' => 'Aktivizoni kërkim të avancuar (vetëm për Vektor)',
'vector-view-create'             => 'Krijo',
'vector-view-edit'               => 'Redakto',
'vector-view-history'            => 'Shiko historikun',
'vector-view-view'               => 'Lexoni',
'vector-view-viewsource'         => 'Shikoni tekstin',
'actions'                        => 'Veprimet',
'namespaces'                     => 'Emri i hapësirës',
'variants'                       => 'Variante',

'errorpagetitle'    => 'Gabim',
'returnto'          => 'Kthehu tek $1.',
'tagline'           => 'Nga {{SITENAME}}',
'help'              => 'Ndihmë',
'search'            => 'Kërko',
'searchbutton'      => 'Kërko',
'go'                => 'Shko',
'searcharticle'     => 'Shko',
'history'           => 'Historiku i faqes',
'history_short'     => 'Historiku',
'updatedmarker'     => 'ndryshuar nga vizita e fundit',
'info_short'        => 'Informacion',
'printableversion'  => 'Version shtypi',
'permalink'         => 'Lidhja e përhershme',
'print'             => 'Shtype',
'view'              => 'Shiko',
'edit'              => 'Redaktoni',
'create'            => 'Krijo',
'editthispage'      => 'Redaktoni faqen',
'create-this-page'  => 'Filloje këtë faqe',
'delete'            => 'grise',
'deletethispage'    => 'Grise faqen',
'undelete_short'    => 'Restauro {{PLURAL:$1|një redaktim|$1 redaktime}}',
'protect'           => 'Mbroje',
'protect_change'    => 'ndrysho',
'protectthispage'   => 'Mbroje faqen',
'unprotect'         => 'Liroje',
'unprotectthispage' => 'Liroje faqen',
'newpage'           => 'Faqe e re',
'talkpage'          => 'Diskutoni faqen',
'talkpagelinktext'  => 'Diskuto',
'specialpage'       => 'Faqe speciale',
'personaltools'     => 'Mjete vetjake',
'postcomment'       => 'Shtoni koment',
'articlepage'       => 'Shikoni artikullin',
'talk'              => 'Diskutimet',
'views'             => 'Shikime',
'toolbox'           => 'Mjete',
'userpage'          => 'Shikoni faqen',
'projectpage'       => 'Shikoni projekt-faqen',
'imagepage'         => 'Shikoni faqen e figurës',
'mediawikipage'     => 'Shikoni faqen e mesazhit',
'templatepage'      => 'Shiko faqen e stampës',
'viewhelppage'      => 'Shiko faqen për ndihmë',
'categorypage'      => 'Shiko faqen e kategorisë',
'viewtalkpage'      => 'Shikoni diskutimet',
'otherlanguages'    => 'Në gjuhë të tjera',
'redirectedfrom'    => '(Përcjellë nga $1)',
'redirectpagesub'   => 'Faqe përcjellëse',
'lastmodifiedat'    => 'Kjo faqe është ndryshuar për herë te fundit më $2, $1.',
'viewcount'         => 'Kjo faqe është parë {{PLURAL:$1|një|$1}} herë.',
'protectedpage'     => 'Faqe e mbrojtur',
'jumpto'            => 'Shko te:',
'jumptonavigation'  => 'navigacion',
'jumptosearch'      => 'kërko',
'view-pool-error'   => 'Ju kërkojmë falje, serverët janë të mbingarkuar momentalisht.
Shumë përdorues po përpiqen të shikojnë këtë faqe.
Ju lutemi prisni pak para se të hapni sërish këtë faqe.

$1',
'pool-timeout'      => 'Koha duke pritur për të bllokoet',
'pool-queuefull'    => 'Radhja është e plotë',
'pool-errorunknown' => 'Gabim i panjohur',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Rreth {{SITENAME}}',
'aboutpage'            => 'Project:Rreth',
'copyright'            => 'Përmbajtja është në disponim nëpërmjet licencës $1.',
'copyrightpage'        => '{{ns:project}}:Të drejta autori',
'currentevents'        => 'Ngjarjet e tanishme',
'currentevents-url'    => 'Project:Ngjarjet e tanishme',
'disclaimers'          => 'Shfajësimet',
'disclaimerpage'       => 'Project:Shfajësimet e përgjithshme',
'edithelp'             => 'Ndihmë për redaktim',
'edithelppage'         => 'Help:Si redaktohet një faqe',
'helppage'             => 'Help:Ndihmë',
'mainpage'             => 'Faqja Kryesore',
'mainpage-description' => 'Faqja Kryesore',
'policy-url'           => 'Project:Rregullat',
'portal'               => 'Wikiportal',
'portal-url'           => 'Project:Wikiportal',
'privacy'              => 'Rreth të dhënave vetjake',
'privacypage'          => 'Project:Politika vetjake',

'badaccess'        => 'Gabim leje',
'badaccess-group0' => 'Nuk ju lejohet veprimi i kërkuar',
'badaccess-groups' => 'Veprimi që kërkuat lejohet vetëm nga përdorues të {{PLURAL:$2|grupit|grupeve}}: $1.',

'versionrequired'     => 'Nevojitet versioni $1 i MediaWiki-it',
'versionrequiredtext' => 'Nevojitet versioni $1 i MediaWiki-it për përdorimin e kësaj faqeje. Shikoni [[Special:Version|versionin]] tuaj.',

'ok'                      => 'Shkoni',
'retrievedfrom'           => 'Marrë nga "$1"',
'youhavenewmessages'      => 'Keni $1 ($2).',
'newmessageslink'         => 'mesazhe të reja',
'newmessagesdifflink'     => 'ndryshimi i fundit',
'youhavenewmessagesmulti' => 'Ju keni mesazh të ri në $1',
'editsection'             => 'redaktoni',
'editold'                 => 'redaktoni',
'viewsourceold'           => 'shikoni burimin',
'editlink'                => 'redakto',
'viewsourcelink'          => 'shih burimin',
'editsectionhint'         => 'Redaktoni seksionin:
Edit section: $1',
'toc'                     => 'Tabela e përmbajtjeve',
'showtoc'                 => 'trego',
'hidetoc'                 => 'fshih',
'collapsible-collapse'    => 'Ngushtoje',
'collapsible-expand'      => 'Zgjeroje',
'thisisdeleted'           => 'Shikoni ose restauroni $1?',
'viewdeleted'             => 'Do ta shikosh $1?',
'restorelink'             => '{{PLURAL:$1|një redaktim i grisur|$1 redaktime të grisura}}',
'feedlinks'               => 'Ushqyes:',
'feed-invalid'            => 'Lloji i burimit të pajtimit është i pavlefshëm.',
'feed-unavailable'        => 'Këso RSS nuk janë të lejuara',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Burim Atom',
'red-link-title'          => '$1 (faqja nuk ekziston)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artikulli',
'nstab-user'      => 'Përdoruesi',
'nstab-media'     => 'Media-faqe',
'nstab-special'   => 'Faqe speciale',
'nstab-project'   => 'Projekt-faqe',
'nstab-image'     => 'Figura',
'nstab-mediawiki' => 'Porosia',
'nstab-template'  => 'Stampa',
'nstab-help'      => 'Ndihmë',
'nstab-category'  => 'Kategoria',

# Main script and global functions
'nosuchaction'      => 'Nuk ekziston ky veprim',
'nosuchactiontext'  => 'Veprimi i caktuar nga URL nuk
njihet nga wiki software',
'nosuchspecialpage' => 'Nuk ekziston kjo faqe',
'nospecialpagetext' => '<strong>Ju keni kërkuar një faqe speciale të pavlefshme.</strong> 

 Një listë e faqeve të vlefshme të veçanta mund të gjenden në [[Special:SpecialPages|{{int: specialpages }}]].',

# General errors
'error'                => 'Gabim',
'databaseerror'        => 'Gabim regjistri',
'dberrortext'          => 'Ka ndodhur një gabim me pyetjen e regjistrit.
Kjo mund të ndodhi n.q.s. pyetja nuk është e vlehshme,
ose mund të jetë një yçkël e softuerit.
Pyetja e fundit që i keni bërë regjistrit ishte:
<blockquote><tt>$1</tt></blockquote>
nga funksioni "<tt>$2</tt>".
MySQL kthehu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ka ndodhur një gabim me formatin e pyetjes së regjistrit. Pyetja e fundit qe i keni bërë regjistrit ishte:
"$1"
nga funksioni "$2".
MySQL kthehu gabimin "$3: $4".',
'laggedslavemode'      => 'Kujdes: Kjo faqe mund të mos jetë përtërirë nga shërbyesi kryesorë dhe mund të ketë informacion të vjetër',
'readonly'             => 'Regjistri i bllokuar',
'enterlockreason'      => 'Fusni një arsye për bllokimin, gjithashtu fusni edhe kohën se kur pritet të çbllokohet',
'readonlytext'         => 'Regjistri i {{SITENAME}}-s është i bllokuar dhe nuk lejon redaktime dhe
artikuj të rinj. Ka mundësi të jetë bllokuar për mirëmbajtje,
dhe do të kthehet në gjëndje normale mbas mirëmbajtjes.

Mirëmbajtësi i cili e bllokoi dha këtë arsye: $1',
'missing-article'      => 'Baza e të dhënave se gjeti dot tekstin që duhet të faqes, të emëruar "$1" $2.

Kjo zakonisht shkaktohet nga përcjellja e një ndyshimi të vjetëruar ose të një nyje të historisë së faqes që është grisur.

Nëse nuk është kështu, mund të keni gjetur gabim në softuer. Ju lutemi, njoftoni një [[Special:ListUsers/sysop|administrues]], pëër këtë, duke shtuar URL-në.',
'missingarticle-rev'   => '(versioni#: $1)',
'missingarticle-diff'  => '(Ndryshimi: $1, $2)',
'readonly_lag'         => "Regjistri është bllokuar automatikisht për t'i dhënë kohë shërbyesve skllevër për të arritur kryesorin. Ju lutemi provojeni përsëri më vonë.",
'internalerror'        => 'Gabim i brendshëm',
'internalerror_info'   => 'Gabim i brendshëm: $1',
'fileappenderrorread'  => 'I pamundur leximi "$1" gjatë bashkangjitëjes.',
'fileappenderror'      => 'Nuk munda të shtoj "$1" tek "$2.',
'filecopyerror'        => 'Nuk munda të kopjojë skedën "$1" tek "$2".',
'filerenameerror'      => 'Nuk munda të ndërrojë emrin e skedës "$1" në "$2".',
'filedeleteerror'      => 'Nuk munda të gris skedën "$1".',
'directorycreateerror' => 'S\'munda të krijoj skedarin "$1".',
'filenotfound'         => 'Nuk munda të gjejë skedën "$1".',
'fileexistserror'      => 'Skeda "$1" nuk mund të shkruhet : skeda ekziston',
'unexpected'           => 'Vlerë e papritur: "$1"="$2".',
'formerror'            => 'Gabim: nuk mund të dërgohej formulari',
'badarticleerror'      => 'Ky veprim nuk mund të bëhet në këtë faqe.',
'cannotdelete'         => 'Faqja ose skedari $1 nuk u fshi.
Mund të jetë fshirë nga dikush tjetër.',
'badtitle'             => 'Titull i pasaktë',
'badtitletext'         => 'Titulli i faqes që kërkuat nuk ishte i saktë, ishte bosh, ose ishte një lidhje gabim me një titull wiki internacional.',
'perfcached'           => 'Informacioni i mëposhtëm është kopje e ruajtur dhe mund të mos jetë i freskët:',
'perfcachedts'         => 'Informacioni i mëposhtëm është një kopje e rifreskuar më $1.',
'querypage-no-updates' => 'Rifreskimi i kësaj faqeje tani për tani është ndaluar, prandaj dhe informacioni i mëposhtëm mund të jetë i vjetërsuar.',
'wrong_wfQuery_params' => 'Parametra gabim tek wfQuery()<br />
Funksioni: $1<br />
Pyetja: $2',
'viewsource'           => 'Shikoni tekstin',
'viewsourcefor'        => 'e $1',
'actionthrottled'      => 'Veprim i kufizuar',
'actionthrottledtext'  => 'Për tu ruajtur nga redaktime automatike "spam" të padëshiruara na duhet të kufizojmë veprimet tuaja pasi janë të shumta në një kohë të shkurtër.
Ju lutem provojeni përsëri pas disa minutash.',
'protectedpagetext'    => 'Kjo faqe është mbyllur për të ndaluar redaktimin.',
'viewsourcetext'       => 'Ju mund të shikoni dhe kopjoni tekstin burimor të kësaj faqe:',
'protectedinterface'   => 'Kjo faqe përmban tekst për pamjen gjuhësorë të softuerit dhe është e mbrojtur për të penguar keqpërdorimet.',
'editinginterface'     => "'''Kujdes:''' Po redaktoni një faqe që përdoret për tekstin ose pamjen e softuerit. Ndryshimet e kësaj faqeje do të prekin tekstin ose pamjen për të gjithë përdoruesit e tjerë. Për përkthime, ju lutem konsideroni përdorimin e [http://translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], projektit të përkthimit të MediaWiki-it.",
'sqlhidden'            => '(Pyetje SQL e fshehur)',
'cascadeprotected'     => 'Kjo faqe është mbrojtur nga redaktimi pasi është përfshirë në {{PLURAL:$1|faqen|faqet}} e mëposhtme që {{PLURAL:$1|është|janë}} mbrojtur sipas metodës "ujëvarë":
$2',
'namespaceprotected'   => "Nuk ju lejohet redaktimi i faqeve të hapësirës '''$1'''.",
'customcssjsprotected' => 'Nuk keni leje ta ndryshoni këtë faqe sepse përmban informata personale të një përdoruesi tjetër',
'ns-specialprotected'  => "Faqet speciale s'mund të redaktohen.",
'titleprotected'       => "Ky titull është mbrojtur nga [[User:$1|$1]] dhe s'mund të krijohet një faqe nën të.
Arsyeja e dhënë është ''$2''.",

# Virus scanner
'virus-badscanner'     => "Konfigurim i keq: skanues i panjohur virusesh: ''$1''",
'virus-scanfailed'     => 'Hetimi dështoi (code $1)',
'virus-unknownscanner' => 'antivirus i pa njohur:',

# Login and logout pages
'logouttext'                 => "'''Ju jeni tashmë jashtë.''' 

 Ju mund të vazhdoni të përdorni {{SITENAME}} anonimisht, ose mund të [[Special:UserLogin|hyni brënda përsëri]] si përdoruesi i mëparshëm ose si një përdorues tjetër. 
 Vini re se disa faqe mund të shfaqen sikur ju të ishit identifikuar derisa të fshini ''cache''-in e shfletuesit tuaj.",
'welcomecreation'            => '== Mirësevini, $1! == 
 Llogaria juaj është krijuar. 
 Mos harroni të ndryshoni [[Special:Preferences|{{SITENAME}} preferencat]].',
'yourname'                   => 'Fusni nofkën tuaj',
'yourpassword'               => 'Fusni fjalëkalimin tuaj',
'yourpasswordagain'          => 'Fusni fjalëkalimin përsëri',
'remembermypassword'         => 'Mbaj mënd fjalëkalimin tim për tërë vizitat e ardhshme (për një kohë maksimale prej $1 {{PLURAL:$1|dite|ditësh}})',
'securelogin-stick-https'    => 'Qëndro i lidhur me HTTPS pas hyrjes me emrin përkatës',
'yourdomainname'             => 'Faqja juaj',
'externaldberror'            => 'Ose kishte një gabim tek regjistri i identifikimit të jashtëm, ose nuk ju lejohet të përtërini llogarinë tuaje të jashtme.',
'login'                      => 'Hyni',
'nav-login-createaccount'    => 'Hyni ose hapni një llogari',
'loginprompt'                => 'Duhet të pranoni "biskota" për të hyrë brënda në {{SITENAME}}.',
'userlogin'                  => 'Hyni / hapni llogari',
'userloginnocreate'          => 'Hyni',
'logout'                     => 'Dalje',
'userlogout'                 => 'Dalje',
'notloggedin'                => 'Nuk keni hyrë brenda',
'nologin'                    => "Nuk keni një llogari? '''$1'''.",
'nologinlink'                => 'Hapeni',
'createaccount'              => 'Hap një llogari',
'gotaccount'                 => "Keni një llogari? '''$1'''.",
'gotaccountlink'             => 'Hyni',
'createaccountmail'          => 'me email',
'createaccountreason'        => 'Arsyeja:',
'badretype'                  => 'Fjalëkalimet nuk janë njësoj.',
'userexists'                 => 'Nofka që kërkuat është në përdorim. Zgjidhni një nofkë tjetër.',
'loginerror'                 => 'Gabim hyrje',
'createaccounterror'         => 'Nuk mund të hapni një llogari: $1',
'nocookiesnew'               => 'Llogaria e përdoruesit u hap, por nuk keni hyrë brenda akoma. {{SITENAME}} përdor "biskota" për të futur brenda përdoruesit. Prandaj, duhet të pranoni biskota dhe të provoni përsëri me nofkën dhe fjalëkalimin tuaj.',
'nocookieslogin'             => '{{SITENAME}} përdor "biskota" për të futur brenda përdoruesit. Prandaj, duhet të pranoni "biskota" dhe të provoni përsëri.',
'noname'                     => 'Nuk keni dhënë një emër të pranueshëm.',
'loginsuccesstitle'          => 'Hyrje me sukses',
'loginsuccess'               => 'Keni hyrë brënda në {{SITENAME}} si "$1".',
'nosuchuser'                 => 'Nuk ka ndonjë përdorues me emrin "$1".
Kontrolloni shkrimin ose [[Special:UserLogin/signup|hapni një llogari të re]].',
'nosuchusershort'            => 'Nuk ka asnjë përdorues me emrin "<nowiki>$1</nowiki>".',
'nouserspecified'            => 'Ju duhet të jepni një nofkë',
'login-userblocked'          => 'Ky përdorues është bllokuar. Identifikimi nuk lejohet.',
'wrongpassword'              => 'Fjalëkalimi që futët nuk është i saktë. Provoni përsëri!',
'wrongpasswordempty'         => 'Fjalëkalimi juaj ishte bosh. Ju lutemi provoni përsëri.',
'passwordtooshort'           => 'Fjalëkalimi juaj është i pavlefshëm ose tepër i shkurtër. Ai duhet të ketë së paku {{PLURAL:$1|1 shkronjë|$1 shkronja}} dhe duhet të jetë i ndryshëm nga emri i përdoruesit.',
'password-name-match'        => 'Fjalëkalimi juaj duhet të jetë i ndryshëm nga emri juaj.',
'password-login-forbidden'   => 'Përdorimi i kësaj nofke dhe fjalëkalimi është i ndaluar.',
'mailmypassword'             => 'Më dërgo një fjalëkalim të ri tek adresa ime',
'passwordremindertitle'      => 'Kërkesë për fjalëkalim të ri tek {{SITENAME}}',
'passwordremindertext'       => 'Dikush (sigurisht ju, nga adresa IP adresa $1) kërkoi një fjalëkalim të ri për hyrje tek {{SITENAME}} ($4). U krijua fjalëkalimi i përkohshëm për përdoruesin "$2" dhe u dërgua tek "$3". Nëse ky ishte tentimi juaj duhet që të kyçeni dhe ndërroni fjalëkalimin tani. Fjalëkalimi juaj i përkohshëm do të skadojë {{PLURAL:$5|një dite|$5 ditësh}}.

Nëse ndokush tjetër ka bërë këtë kërkesë, ose nëse ju kujtohet fjalëkalimin dhe nuk doni që ta ndërroni, mund të e injoroni këtë porosi dhe të vazhdoni të përdorni  fjalëkalimin e vjetër.',
'noemail'                    => 'Regjistri nuk ka adresë për përdoruesin "$1".',
'noemailcreate'              => 'Ju duhet të sigurojë një adresë e e-mailit të saktë.',
'passwordsent'               => 'Një fjalëkalim i ri është dërguar tek adresa e regjistruar për "$1". Provojeni përsëri hyrjen mbasi ta keni marrë fjalëkalimin.',
'blocked-mailpassword'       => 'IP adresa juaj është bllokuar , si e tillë nuk lejohet të përdor funksionin pë rikthim të fjalkalimit , në mënyrë që të parandalohet abuzimi.',
'eauthentsent'               => 'Një eMail konfirmues u dërgua te adresa e dhënë.
Para se të pranohen eMail nga përdoruesit e tjerë, duhet që adressa e juaj të vërtetohet.
Ju lutemi ndiqni këshillat në eMailin e pranuar.',
'throttled-mailpassword'     => "Një kujtesë e fjalëkalimit është dërguar gjatë {{PLURAL:$1|orës|$1 orëve}} të kaluara. Për t'u mbrojtur nga abuzime vetëm një kujtesë dërgohet çdo {{PLURAL:$1|orë|$1 orë}}.",
'mailerror'                  => 'Gabim duke dërguar postën: $1',
'acct_creation_throttle_hit' => 'Nuk lejoheni të krijoni më llogari pasi keni krijuar {{PLURAL:$1|1|$1}}.',
'emailauthenticated'         => 'Adresa juaj është vërtetuar më $2 $3.',
'emailnotauthenticated'      => 'Adresa juaj <strong>nuk është vërtetuar</strong> akoma prandaj nuk mund të merrni e-mail.',
'noemailprefs'               => 'Detyrohet një adresë email-i për të përdorur këtë mjet.',
'emailconfirmlink'           => 'Vërtetoni adresën tuaj',
'invalidemailaddress'        => 'Posta elektronike nuk mund të pranohet kështu si është pasi ka format jo valid. Ju lutemi, vendoni një postë mirë të formatuar, ose zbrazeni fushën.',
'accountcreated'             => 'Llogarija e Përdoruesit u krijua',
'accountcreatedtext'         => 'Llogarija e Përdoruesit për $1 u krijua',
'createaccount-title'        => 'Hapja e llogarive për {{SITENAME}}',
'createaccount-text'         => 'Dikush ka përdorur adresën tuaj për të hapur një llogari tek {{SITENAME}} ($4) të quajtur "$2" me fjalëkalimin "$3".
Duhet të hyni brenda dhe të ndërroni fjalëkalimin tani nëse ky person jeni ju. Përndryshe shpërfilleni këtë mesazh.',
'usernamehasherror'          => 'Emri i përdoruesit nuk mund të përmbajë karaktere',
'login-throttled'            => 'Keni bërë shumë tentime të njëpasnjëshme në fjalëkalimin e kësaj llogarie. Ju lutemi prisni para tentimit përsëri.',
'loginlanguagelabel'         => 'Gjuha: $1',
'suspicious-userlogout'      => 'Kërkesa juaj për të shkëputet u mohua sepse duket sikur është dërguar nga një shfletues të thyer ose caching proxy.',

# E-mail sending
'php-mail-error-unknown' => 'Gabim i panjohur në funksionin e postës PHP ()',

# JavaScript password checks
'password-strength'            => 'Forca e vlerësuar të fjalëkalimit: $1',
'password-strength-bad'        => 'KEQ',
'password-strength-mediocre'   => 'mesatar',
'password-strength-acceptable' => 'i pranueshëm',
'password-strength-good'       => 'mirë',
'password-retype'              => 'Fusni fjalëkalimin përsëri',
'password-retype-mismatch'     => 'Fjalëkalimi nuk përputhet',

# Password reset dialog
'resetpass'                 => 'Ndrysho fjalëkalimin',
'resetpass_announce'        => 'Ju keni hyrë me një kod të përkohshëm.
Për të hyrë tërësisht duhet të vendosni një fjalëkalim të ri këtu:',
'resetpass_header'          => 'Ndrysho fjalëkalimin e llogarisë',
'oldpassword'               => 'I vjetri',
'newpassword'               => 'I riu',
'retypenew'                 => 'I riu përsëri',
'resetpass_submit'          => 'Ndrysho fjalëkalimin dhe hyni brenda',
'resetpass_success'         => 'Fjalëkalimi juaj është ndryshuar me sukses! Mund të hyni brenda...',
'resetpass_forbidden'       => 'Fjalëkalimet nuk mund të ndryshohen',
'resetpass-no-info'         => 'Duhet të jeni i kyçur që të keni qasje direkte në këtë faqe.',
'resetpass-submit-loggedin' => 'Ndrysho fjalëkalimin',
'resetpass-submit-cancel'   => 'Anulo',
'resetpass-wrong-oldpass'   => 'Fjalëkalimi momental ose i përkohshëm nuk është i vlefshëm. Ndoshta tanimë me sukses keni ndërruar fjalëkalimin, ose keni kërkuar fjalëkalim të përkohshëm.',
'resetpass-temp-password'   => 'Fjalëkalimi i përkohshëm:',

# Edit page toolbar
'bold_sample'     => 'Tekst i trashë',
'bold_tip'        => 'Tekst i trashë',
'italic_sample'   => 'Tekst i pjerrët',
'italic_tip'      => 'Tekst i pjerrët',
'link_sample'     => 'Titulli i lidhjes',
'link_tip'        => 'Lidhje e brendshme',
'extlink_sample'  => 'http://www.example.com Titulli i lidhjes',
'extlink_tip'     => 'Lidhje e jashtme (most harro prefiksin http://)',
'headline_sample' => 'Titull shembull',
'headline_tip'    => 'Titull i nivelit 2',
'math_sample'     => 'Vendos formulen ketu',
'math_tip'        => 'Formulë matematike (LaTeX)',
'nowiki_sample'   => 'Vendos tekst që nuk duhet të formatohet',
'nowiki_tip'      => 'Mos përdor format wiki',
'image_sample'    => 'Shembull.jpg',
'image_tip'       => 'Vendos një figurë',
'media_sample'    => 'Shembull.ogg',
'media_tip'       => 'Lidhje media-skedash',
'sig_tip'         => 'Firma juaj me gjithë kohë',
'hr_tip'          => 'vijë horizontale (përdoreni rallë)',

# Edit pages
'summary'                          => 'Përmbledhje:',
'subject'                          => 'Subjekt/Titull:',
'minoredit'                        => 'Ky është një redaktim i vogël',
'watchthis'                        => 'Mbikqyre këtë faqe',
'savearticle'                      => 'Kryej ndryshimet',
'preview'                          => 'Parapamje',
'showpreview'                      => 'Trego parapamjen',
'showlivepreview'                  => 'Parapamje e menjëhershme',
'showdiff'                         => 'Trego ndryshimet',
'anoneditwarning'                  => "Ju nuk jeni regjistruar. IP adresa juaj do të regjistrohet në historinë e redaktimeve të kësaj faqe.
You are not logged in. Your IP address will be recorded in this page's edit history.",
'anonpreviewwarning'               => 'Nuk jeni futur me emrin tuaj duke u inçizuar të dhënat IP adresën tuaj në këtë faqe dhe është ndryshuar historia.',
'missingsummary'                   => "'''Vërejtje:'''  Ju nuk keni shtuar një përmbledhje për redaktimet.",
'missingcommenttext'               => 'Ju lutemi shtoni një koment në vazhdim.',
'missingcommentheader'             => "'''Kujdes:''' Ju nuk keni dhënë një titull për këtë koment.
Nëse kryeni ndryshimet redaktimi juaj do të ruhet pa titull.",
'summary-preview'                  => 'Parapamja e përmbledhjes:',
'subject-preview'                  => 'Parapamja e titullit:',
'blockedtitle'                     => 'Përdoruesi është bllokuar',
'blockedtext'                      => "'''Llogaria juaj ose adresa e IP-së është bllokuar'''

Bllokimi u bë nga $1 dhe arsyeja e dhënë ishte '''$2'''.

*Fillimi i bllokimit: $8
*Skadimi i bllokimit: $6
*I bllokuari i shënjestruar: $7

Mund të kontaktoni $1 ose një nga [[{{MediaWiki:Grouppage-sysop}}|administruesit]] e tjerë për të diskutuar bllokimin.

Vini re se nuk mund t'i dërgoni email përdoruesit nëse nuk keni një adresë të saktë të dhënë tek [[Special:Preferences|parapëlqimet e përdoruesit]] ose nëse kjo është një nga mundësitë që ju është bllokuar.

Adresa e IP-së që keni është $3 dhe numri i identifikimit të bllokimit është #$5. Përfshini këto dy të dhëna në çdo ankesë.",
'autoblockedtext'                  => 'IP adresa juaj është bllokuar automatikisht sepse ishte përdorur nga një përdorues tjetër i cili ishte bllokuar nga $1.
Arsyeja e dhënë për këtë është:

:\'\'$2\'\'

* Fillimi i bllokimit: $8
* Kalimi i kohës së bllokimit: $6
* Zgjatja e bllokimit: $7

Ju mund të kontaktoni $1 ose një tjetër [[{{MediaWiki:Grouppage-sysop}}|administrues]] për ta diskutuar bllokimin.

Vini re : që nuk mund ta përdorni mundësinë "dërgo porosi elektronike" përveç nëse keni një postë elektronike të vlefshme të regjistruar në [[Special:Preferences|preferencat tuaja]] dhe nuk jeni bllokuar nga përdorimi i saj.

IP adresa juaj e tanishme është $3 dhe ID e bllokimit është #$5.
Ju lutemi përfshini këto detaje në të gjitha kërkesat që i bëni.',
'blockednoreason'                  => 'nuk ka arsye',
'blockedoriginalsource'            => "Më poshtë tregohet burimi i '''$1''':",
'blockededitsource'                => "Më poshtë tregohet teksti i '''redaktimeve tuaja''' të '''$1''':",
'whitelistedittitle'               => 'Duhet të hyni brënda për të redaktuar',
'whitelistedittext'                => 'Duhet të $1 për të redaktuar artikuj.',
'confirmedittext'                  => 'Ju duhet së pari ta vërtetoni e-mail adresen para se të redaktoni. Ju lutem plotësoni dhe vërtetoni e-mailin tuaj  te [[Special:Preferences|parapëlqimet]] e juaja.',
'nosuchsectiontitle'               => '!Asnjë seksion i tillë nuk ekziston',
'nosuchsectiontext'                => "Keni provuar të redaktoni një seksion që s'ekziston.",
'loginreqtitle'                    => 'Detyrohet hyrja',
'loginreqlink'                     => 'hyni',
'loginreqpagetext'                 => 'Ju duhet $1 për të parë faqe e tjera.',
'accmailtitle'                     => 'Fjalëkalimi u dërgua.',
'accmailtext'                      => "Nji fjalëkalim i krijuem rastësisht për [[User talk:$1|$1]] u dërgue në $2.

Fjalëkalimi për këtë llogari mundet me u ndryshue në faqen ''[[Special:ChangePassword|ndrysho fjalëkalimin]]'' mbas kyçjes.",
'newarticle'                       => '(I Ri)',
'newarticletext'                   => "Keni përcjellë nji vegëz te nji faqe që nuk ekziston.
Për me krijue këtë faqe, shkrueni në kutinë mâ poshtë (shih [[{{MediaWiki:Helppage}}|faqja e ndihmës]] për mâ shumë informata).
Nëse keni hy këtu gabimisht klikoni butonin '''mbrapa''' të shfletuesit.",
'anontalkpagetext'                 => "----'' Kjo është një faqe diskutimi për një përdorues anonim i cili nuk ka krijuar akoma një account, ose të cilët nuk e përdorin atë. 
 Prandaj, ne duhet të përdorim adresën IP numerike për të identifikuar tij / saj. 
 Të tillë një adresë IP mund të ndahet nga disa përdorues. 
 Në qoftë se jeni një përdorues anonim dhe mendoni se komente të parëndësishme janë drejtuar ndaj jush, ju lutem [[Special:UserLogin/signup|krijoni një llogari]] ose [[Special:UserLogin|hyni brënda]] për të shmangur konfuzionin e ardhshme me përdorues të tjerë anonim .''",
'noarticletext'                    => 'Momentalisht nuk ka tekst në këtë faqe.
Ju mundeni [[Special:Search/{{PAGENAME}}|me kërkue këtë titull]] në faqe tjera,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} me kërkue në regjistrat tematikisht të afërm],
apo [{{fullurl:{{FULLPAGENAME}}|action=edit}} me redaktue këtë faqe]</span>.',
'noarticletext-nopermission'       => 'Momentalisht nuk ka tekst në këtë faqe.
Ju mundeni [[Special:Search/{{PAGENAME}}|me kërku këtë titull]] në faqe tjera,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} me kërku në regjistrat tematikisht të afërm],
apo [{{fullurl:{{FULLPAGENAME}}|action=edit}} me redaktu këtë faqe]</span>.',
'userpage-userdoesnotexist'        => 'Llogaria e përdoruesit "$1" nuk është hapur. Ju lutem mendohuni mirë nëse dëshironi të krijoni/redaktoni këtë faqe.',
'userpage-userdoesnotexist-view'   => 'Profili i përdoruesit "$1" nuk është i regjistruar.',
'blocked-notice-logextract'        => 'Ky përdorues është bllokuar aktualisht.
Regjistri i bllokuar hyrjen e fundit është më poshtë, për referencë:',
'clearyourcache'                   => "'''Shënim:''' Pasi të ruani parapëlqimet ose pasi të kryeni ndryshimet, duhet të pastroni ''cache''-në e shfletuesit tuaj për të parë ndryshimet: për '''Mozilla/Safari/Konqueror''' shtypni ''Ctrl+Shift+Reload'' (ose ''ctrl+shift+r''), për '''IE''' ''Ctrl+f5'', '''Opera''': ''F5''.",
'usercssyoucanpreview'             => "'''Këshillë:''' Përdorni butonin 'Trego parapamjen' për të provuar ndryshimet tuaja të faqeve css/js përpara se të kryeni ndryshimet.",
'userjsyoucanpreview'              => "'''Këshillë:''' Përdorni butonin 'Trego parapamjen' për të provuar ndryshimet tuaja të faqeve css/js përpara se të kryeni ndryshimet.",
'usercsspreview'                   => "'''Vini re! Kjo është vetëm një parapamje e faqes suaj CSS. Akoma nuk është ruajtur!'''",
'userjspreview'                    => "'''Vini re se kjo është vetëm një provë ose parapamje e faqes tuaj JavaScript, ajo nuk është ruajtur akoma!'''",
'sitecsspreview'                   => "'''Vini re! Kjo është vetëm një parapamje e faqes suaj CSS.'''
'''Akoma nuk është ruajtur!'''",
'sitejspreview'                    => "'''Vini re se kjo është vetëm një provë ose parapamje e faqes tuaj JavaScript.''' 
'''Nuk është ruajtur akoma!'''",
'userinvalidcssjstitle'            => "'''Kujdes:''' Nuk ka pamje të quajtur \"\$1\". Vini re se faqet .css dhe .js përdorin titull me gërma të vogla, p.sh. {{ns:user}}:Foo/vector.css, jo {{ns:user}}:Foo/Vector.css.",
'updated'                          => '(E ndryshuar)',
'note'                             => "'''Shënim:'''",
'previewnote'                      => "'''Kini kujdes se kjo është vetëm një parapamje, nuk është ruajtur akoma!'''",
'previewconflict'                  => 'Kjo parapamje reflekton tekstin sipër kutisë së redaktimit siç do të duket kur të kryeni ndryshimin.',
'session_fail_preview'             => "'''Ju kërkoj ndjesë. Nuk munda të kryej redaktimin tuaj sepse humba disa të dhëna. Provojeni përsëri dhe nëse nuk punon provoni të dilni dhe të hyni përsëri.'''",
'session_fail_preview_html'        => "'''Ju kërkoj ndjesë! Nuk munda të ruaj redaktimin tuaj për shkaqe teknike.'''

''{{SITENAME}} ka ndaluar përfshirjen e tekstit HTML të papërpunuar, parapamja s'është treguar për t'ju mbrojtur nga sulme të mundshme në JavaScript''

'''Nëse ky është një redaktim i vlefshëm, ju lutem provojeni përsëri. Nëse s'punon përsëri atëherë provoni të dilni dhe të hyni përsëri nga llogaria juaj.'''",
'token_suffix_mismatch'            => "'''Redaktimi s'është pranuar pasi shfletuesi juaj ka prishur përmbajtjen e shkronjave. Redaktimi është shpërfilluar për të ruajtur përmbajtjen e tekstit.
Kjo ndodh ndonjëherë kur përdoren shërbime ndërjmetësash anonim.'''",
'editing'                          => 'Duke redaktuar $1',
'editingsection'                   => 'Duke redaktuar $1 (seksion)',
'editingcomment'                   => 'Duke redaktuar (sekcionin e ri) $1',
'editconflict'                     => 'Konflikt redaktimi: $1',
'explainconflict'                  => "Dikush tjetër ka ndryshuar këtë faqe kur ju po e redaktonit.
Kutiza e redaktimit mësipërme tregon tekstin e faqes siç ekziston tani.
Ndryshimet juaja janë treguar poshtë kutisë së redaktimit.
Ju duhet të përputhni ndryshimet tuaja me tekstin ekzistues.
'''Vetëm''' teksti në kutinë e sipërme të redaktimit do të ruhet kur të shtypni \"{{int:savearticle}}\".",
'yourtext'                         => 'Teksti juaj',
'storedversion'                    => 'Versioni i ruajtur',
'nonunicodebrowser'                => "'''KUJDES: Shfletuesi juaj nuk përdor dot unikode, ju lutem ndryshoni shfletues para se të redaktoni artikuj.'''",
'editingold'                       => "'''KUJDES: Po redaktoni një version të vjetër të kësaj faqeje. Në qoftë se e ruani, çdo ndryshim i bërë deri tani do të humbet.'''",
'yourdiff'                         => 'Ndryshimet',
'copyrightwarning'                 => "Kontributet tek {{SITENAME}} janë të konsideruara të dhëna nën licensën $2 (shikoni $1 për hollësirat).<br />
'''NDALOHET DHËNIA E PUNIMEVE PA PASUR LEJE NGA AUTORI NË MOSPËRPUTHJE ME KËTË LICENSË!'''<br />",
'copyrightwarning2'                => "Ju lutem vini re se të gjitha kontributet tek {{SITENAME}} mund të redaktohen, ndryshohen apo fshihen nga përdorues të tjerë. Në qoftë se nuk dëshironi që shkrimet tuaja të redaktohen pa mëshirë mos i jepni këtu.<br />
Po na premtoni që ç'ka po jepni këtu e keni kontributin tuaj ose e keni kopjuar nga domeni publik apo nga burime të tjera të lira sipas ligjeve përkatëse (shikoni $1 për hollësirat).
'''NDALOHET DHËNIA E PUNIMEVE PA PASUR LEJE NGA AUTORI NË MOSPËRPUTHJE ME KËTË LICENSË!'''",
'longpageerror'                    => "'''GABIM: Tesksti që ju po e redaktoni është $1 KB i gjatë dhe është më i gjatë se maksimumi i lejuar prej $2 KB. Ndryshimet nuk mund të ruhen.'''",
'readonlywarning'                  => "'''KUJDES: Baza e të dhënave është mbyllur për mirëmbajtje, pra ju nuk do të mund të ruani redaktimin tuaj për momentin.
Ju ndoshta doni të kopjoni tekstin në një tekst dokument dhe të e ruani për më vonë.'''

Administruesi që ka bërë mbylljen ka dhënë këtë sqarim: $1.",
'protectedpagewarning'             => "'''KUJDES: Kjo faqe është mbyllur ashtu që vetëm përdoruesit me titullin administrator mund ta redaktojnë.'''",
'semiprotectedpagewarning'         => "'''Shënim:''' Redaktimi i kësaj faqeje mund të bëhet vetëm nga përdorues të regjistruar.",
'cascadeprotectedwarning'          => "'''Vini re:''' Kjo faqe është e mbrojtur dhe vetëm përdoruesit me privilegje administrative mund ta redaktojnë pasi është përfshirë në mbrotjen \"ujëvarë\" të {{PLURAL:\$1|faqes së|faqeve të}} mëposhtme:",
'titleprotectedwarning'            => "'''Kujdes:  Kjo faqe është e mbrojtur dhe vetëm [[Special:ListGroupRights|disa përdorues]] mund ta krijojnë.'''
Regjistri më i vonshëm i hyrjeve është poshtë për referncë:",
'templatesused'                    => '{{PLURAL:$1|Stamp|Stampa}} të përdorura në këtë faqe:',
'templatesusedpreview'             => '{{PLURAL:$1|Stamp|Stampa}} të përdorë në këtë parapâmje:',
'templatesusedsection'             => '{{PLURAL:$1|Stamp|Stampa}} e përdoruna në këtë sekcion:',
'template-protected'               => '(mbrojtur)',
'template-semiprotected'           => '(gjysëm-mbrojtur)',
'hiddencategories'                 => 'Kjo faqe është nën {{PLURAL:$1|një kategori të fshehur|$1 kategori të fshehura}}:',
'edittools'                        => '<!-- Teksti këtu do të tregohet poshtë kutive të redaktimit dhe ngarkimit të skedave. -->',
'nocreatetitle'                    => 'Krijimi i faqeve të reja është i kufizuar.',
'nocreatetext'                     => 'Mundësia për të krijuar faqe të reja është kufizuar. Duhet të [[Special:UserLogin|hyni ose të hapni një llogari]] për të krijuar faqe të reja, ose mund të ktheheni mbrapsh dhe të redaktoni një faqe ekzistuese.',
'nocreate-loggedin'                => 'Nuk ju lejohet të krijoni faqe të reja.',
'sectioneditnotsupported-title'    => 'Redaktimi i pjesës nuk është i mbështetur',
'sectioneditnotsupported-text'     => 'Redaktimi nuk është i mbështetur në këtë faqe.',
'permissionserrors'                => 'Gabime privilegjesh',
'permissionserrorstext'            => 'Nuk keni leje për të bërë këtë veprim për {{PLURAL:$1|këtë arsye|këto arsye}}:',
'permissionserrorstext-withaction' => 'Ju nuk keni leje për $2, për {{PLURAL:$1|këtë arsye|këto arsye}}:',
'recreate-moveddeleted-warn'       => "'''Kujdes: Po rikrijoni një faqe që është grisur më parë.'''

Mendohuni nëse dëshironi të vazhdoni me veprimin tuaj në këtë faqe.
Regjistri i grisjes për këtë faqe jepet më poshtë:",
'moveddeleted-notice'              => 'Kjo faqe është grisur. Të dhënat e grisjes për këtë faqe gjenden më poshtë, për referencë.',
'log-fulllog'                      => 'Shihe ditaret të plota',
'edit-hook-aborted'                => 'Redaktimi u ndërpre nga një goditje.
Nuk dha asnjë shpjegim.',
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
'parser-template-loop-warning'            => 'stampa u zbulua: [[$1]]',
'parser-template-recursion-depth-warning' => 'Stampa ka kalur limitin e lejuar: $1',
'language-converter-depth-warning'        => 'Konvertimi i gjuhës ka kaluar limitin e lejuar: ($1)',

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
'viewpagelogs'           => 'Shiko regjistrat për këtë faqe',
'nohistory'              => 'Nuk ka histori redaktimesh për këtë faqe.',
'currentrev'             => 'Versioni i tanishëm',
'currentrev-asof'        => 'Versioni momental që nga $1',
'revisionasof'           => 'Versioni i $1',
'revision-info'          => 'Versioni më $1 nga $2',
'previousrevision'       => '← Version më i vjetër',
'nextrevision'           => 'Version më i ri →',
'currentrevisionlink'    => 'shikoni versionin e tanishëm',
'cur'                    => 'tani',
'next'                   => 'mbas',
'last'                   => 'fund',
'page_first'             => 'Së pari',
'page_last'              => 'Së fundmi',
'histlegend'             => 'Legjenda: (tani) = ndryshimet me versionin e tanishëm,
(fund) = ndryshimet me versionin e parardhshëm, V = redaktim i vogël',
'history-fieldset-title' => 'Shfleto historikun',
'history-show-deleted'   => 'Vetëm versionet të grisur',
'histfirst'              => 'Së pari',
'histlast'               => 'Së fundmi',
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
'rev-deleted-user-contribs'   => '[Përdoruesi ose adresa IP u hoq - redaktimet e  fshehura nga kontribuesit]',
'rev-deleted-text-permission' => "Versioni i kësaj faqeje është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete={{FULLPAGENAME}}}} regjistri i grisjeve].",
'rev-deleted-text-unhide'     => "Ky version i faqes është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Si një administrator ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-suppressed-text-unhide'  => "Ky version i faqes është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Si një administrator ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-deleted-text-view'       => 'Ky version i faqes është shlyer nga arkivi publik i faqes. Ju si Administrator mund ta shikoni akoma këtë.
Shiko tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.',
'rev-suppressed-text-view'    => 'Ky version i faqes është shlyer nga arkivi publik i faqes. Ju si Administrator mund ta shikoni akoma këtë.
Shiko tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.',
'rev-deleted-no-diff'         => "Ju nuk mund ta shikoni këtë ndryshim sepse një nga versionet është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete={{FULLPAGENAME}}}} regjistri i grisjeve].",
'rev-suppressed-no-diff'      => "Ju nuk mund ta shikoni këtë ndryshim sepse një nga versionet është '''grisur'''.",
'rev-deleted-unhide-diff'     => "Një nga versionet e këtij ndryshimi është '''grisur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Si një administrator ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-suppressed-unhide-diff'  => "Një nga versionet e këtij ndryshimi është '''grisur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Si një administrator ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-deleted-diff-view'       => 'Ky version i faqes është shlyer nga arkivi publik i faqes. Ju si Administrator mund ta shikoni akoma këtë.
Shiko tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve], ndoshta gjenden atje më shumë informacione rreth kësaj.',
'rev-suppressed-diff-view'    => "Një nga versionet e këtij ndryshimi është '''grisur'''.
Si një administrator ju mund ta shikoni këtë ndryshim; detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve].",
'rev-delundel'                => 'trego/fshih',
'rev-showdeleted'             => 'Trego',
'revisiondelete'              => 'Shlyj/Reparo versionet',
'revdelete-nooldid-title'     => 'Version i dëshiruar i pavfleshëm',
'revdelete-nooldid-text'      => 'Ose nuk keni përcaktuar një version(e) të dëshiruar për veprimin, ose versioni nuk ekziston, ose po mundoheni të fshihni versionin e tanishëm.',
'revdelete-nologtype-title'   => 'Nuk është dhënë asnjë lloj i të dhënave',
'revdelete-nologtype-text'    => 'Nuk keni caktuar llojin e të dhënave për të realizuar veprimin.',
'revdelete-nologid-title'     => 'Regjistër i pavlefshëm',
'revdelete-nologid-text'      => 'Ju ose nuk keni specifikuar një ngjarje target kyçje për të kryer këtë funksion ose hyrja e specifikuar nuk ekziston.',
'revdelete-no-file'           => 'Skeda e dhënë nuk ekziston.',
'revdelete-show-file-confirm' => 'Jeni i/e sigurt se dëshironi të shikoni një version të grisur të skedës "<nowiki>$1</nowiki>" nga $2 tek $3?',
'revdelete-show-file-submit'  => 'Po',
'revdelete-selected'          => "'''{{PLURAL:$2|Versioni i zgjedhur i|Versionet e zgjedhura të}} [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Veprimi i zgjedhur në regjistër|Veprimet e zgjedhura në regjistër}}:'''",
'revdelete-text'              => "'''Përmbajtja dhe pjesët e tjera nuk janë të dukshme për të gjithë, por figurojnë në historikun e versioneve.''' Administratorët munden përmbajtjen e larguar ta shikojnë dhe restaurojnë, përveç në rastet kur një gjë e tillë është ndaluar ekstra.",
'revdelete-confirm'           => 'Ju lutem konfirmoni që keni ndër mënd ta bëni këtë, që i kuptoni pasojat, dhe që ju po veproni në përputhje me [[{{MediaWiki:Policy-url}}|politiken]].',
'revdelete-suppress-text'     => "Shuarje duhet'''vetëm'''të përdoret për rastet e mëposhtme: 
 * Potencialisht e informacionit shpifës 
 * Informacion i pa kriter personal 
 *: Adresat në shtëpi''dhe numrat e telefonit, numrat e sigurimeve shoqërore, etj''",
'revdelete-legend'            => 'Vendosni kufizimet për versionin:',
'revdelete-hide-text'         => 'Fshihe tekstin e versionit',
'revdelete-hide-image'        => 'Fshih përmbajtjen skedare',
'revdelete-hide-name'         => 'Fshihe veprimin dhe shënjestrën',
'revdelete-hide-comment'      => 'fshih komentin e redaktimit',
'revdelete-hide-user'         => 'Fshihe emrin/IP-në të redaktuesit',
'revdelete-hide-restricted'   => 'Ndalo të dhëna nga administrues si dhe të tjerë',
'revdelete-radio-same'        => '(Mos ndryshoni)',
'revdelete-radio-set'         => 'Po',
'revdelete-radio-unset'       => 'Jo',
'revdelete-suppress'          => 'Ndalo të dhëna nga administrues si dhe të tjerë',
'revdelete-unsuppress'        => 'Hiq kufizimet nga versionet e restauruara',
'revdelete-log'               => 'Arsyeja:',
'revdelete-submit'            => 'Aplikoni tek {{PLURAL:$1|revision|versionet}} e zgjedhura',
'revdelete-logentry'          => 'Pamja e versionit u ndryshua për [[$1]]',
'logdelete-logentry'          => 'u ndryshua dukshmëria e ngjarjes së [[$1]]',
'revdelete-success'           => "'''Dukshmëria e versioneve u vendos me sukses.'''",
'revdelete-failure'           => "' ' 'Dukshmëria e rivizionit nuk mund të përditëohet\"
\$1",
'logdelete-success'           => "'''Dukshmëria e regjistrave u vendos me sukses.'''",
'logdelete-failure'           => "'''Dukshmëria nuk u vendos:'''
$1",
'revdel-restore'              => 'Ndrysho dukshmërinë',
'revdel-restore-deleted'      => 'fshij rivizonet',
'revdel-restore-visible'      => 'rivizionet e dukshme',
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
'revdelete-hide-current'      => 'Gabim në fshehje të pikës me datë $2, $1: ky është rivizioni i tanishëm. 
Nuk mund të fshihet',
'revdelete-show-no-access'    => 'Gabim gjatë shfaqjes së artikullit të datës $2, $1: ky artikull ka qenë i shënuar si "i kufizuar".
Ju nuk keni akses.',
'revdelete-modify-no-access'  => 'Gabim gjatë modifikimit të artikullit të datës $2, $1: ky artikull ka qenë i shënuar si "i kufizuar".
Ju nuk keni akses.',
'revdelete-modify-missing'    => 'Gabim gjatë modifikimit të artikullit ID $1: ai nuk është në bazën e të dhënave!',
'revdelete-no-change'         => "'''Kujdes:''' artikulli i datës $2, $1 e ka kërkesën e parametrit të dukshmërisë.",
'revdelete-concurrent-change' => 'Gabim gjatë modifikimit të artikullit të datës $2, $1: statusi i tij duket të jetë ndryshuar nga dikush tjetër kur ju po provonit ta modifikonit.
Ju lutemi kontrolloni regjistrat.',
'revdelete-only-restricted'   => 'Gabim gjatë fshehjes së artikullit të datës $2, $1: ju nuk mund të fshihni artikuj nga pamja e administratorëve pa zgjedhur gjithashtu një nga opsionet e tjera të dukshmërisë.',
'revdelete-reason-dropdown'   => '* Arsye grisjeje e përbashkët
** Shkelje të të drejtave të autorit
** Informacion pa  kriter personal
** Potencialisht informacion shfipës',
'revdelete-otherreason'       => 'Arsye tjetër/shtesë:',
'revdelete-reasonotherlist'   => 'Arsye tjetër',
'revdelete-edit-reasonlist'   => 'Arsye grisjeje për redaktimet',
'revdelete-offender'          => 'Versioni i autorit',

# Suppression log
'suppressionlog'     => 'Regjistri i ndalimeve',
'suppressionlogtext' => 'Më poshtë jepet një listë grisjesh dhe bllokimesh që kanë të bëjnë me përmbatje të fshehur nga administruesit. Shikoni [[Special:IPBlockList|listën e IP bllokimeve]] për një listë të bllokimeve dhe përzënieve në fuqi.',

# Revision move
'moverevlogentry'              => 'lëvizi {{PLURAL:$3|one revision|$3 revisions}} nga $1 tek $2',
'revisionmove'                 => 'Lëviz versionet nga "$1"',
'revmove-explain'              => 'Versioni i mëposhtëm do të largohet nga $1 tek faqja e përcaktuar. N.q.s. faqja e përcaktuar nuk ekziston, ajo është krijuar. Përndryshe, këto versione do të bashkohen tek historiku i faqes.',
'revmove-legend'               => 'Vendosni faqen objektive dhe përmbledhjen',
'revmove-submit'               => 'Lëviz versionet tek faqja e zgjedhur',
'revisionmoveselectedversions' => 'Lëviz versionet e zgjedhura',
'revmove-reasonfield'          => 'Arsyeja:',
'revmove-titlefield'           => 'Faqja objektive:',
'revmove-badparam-title'       => 'Parametra të pavlefshëm',
'revmove-badparam'             => 'Kërkesa juaj përmban parametra të paligjshme ose të pamjaftueshme. 
Kthehuni mbrapa tek faqja e mëparshme dhe të provoni përsëri.',
'revmove-norevisions-title'    => 'Version i dëshiruar i pavfleshëm',
'revmove-norevisions'          => 'Nuk keni dhënë një ose më shumë versione të synuara për të performuar këtë funksion ose versioni i specifikuar nuk ekziston.',
'revmove-nullmove-title'       => 'Titull i pasaktë',
'revmove-nullmove'             => 'Faqja objektive nuk mund të jetë njëlloj si burimi i faqes.
Shkoni prapa tek faqja e mëparshme dhe zgjidhni një emër të ndryshëm nga "$1"',
'revmove-success-existing'     => '{{PLURAL:$1|Një version nga [[$2]] është|$1 versione nga [[$2]] janë}} lëvizur nga faqja ekzistuese [[$3]].',
'revmove-success-created'      => '{{PLURAL:$1|Një version nga [[$2]] është|$1 versione nga [[$2]] janë}} lëvizur tek faqja e re e krijuar [[$3]].',

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
'mergehistory-reason'              => 'Arsyeja:',

# Merge log
'mergelog'           => 'Regjistri i bashkimeve',
'pagemerge-logentry' => 'u bashkua [[$1]] në [[$2]] (versione deri më $3)',
'revertmerge'        => 'Ndaj',
'mergelogpagetext'   => 'Më poshtë jepet një listë e bashkimeve së fundmi nga historiku i një faqeje në historikun e një faqeje tjetër.',

# Diffs
'history-title'            => 'Historiku i redaktimeve te "$1"',
'difference'               => '(Ndryshime midis versioneve)',
'difference-multipage'     => '(Ndryshimi midis faqeve)',
'lineno'                   => 'Rreshti $1:',
'compareselectedversions'  => 'Krahasoni versionet e zgjedhura',
'showhideselectedversions' => 'Shfaq/fshih versionet e zgjedhura',
'editundo'                 => 'ktheje',
'diff-multi'               => '({{PLURAL:$1|Një version i ndërmjetshëm|$1 versione të ndërmjetshme}} nga {{PLURAL:$2|një përdorues|$2 përdorues}} i/të pashfaqur)',
'diff-multi-manyusers'     => '({{PLURAL:$1|Një versioni i ndërmjetshëm|$1 versione të ndërmjetshme}} nga më shumë se $2 {{PLURAL:$2|përdorues|përdorues}} i/të pashfaqur)',

# Search results
'searchresults'                    => 'Rezultatet e kërkimit',
'searchresults-title'              => 'Rezultatet e kërkimit për "$1"',
'searchresulttext'                 => 'Për më shumë informacion rreth kërkimit në {{SITENAME}} shikoni [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Kërkuat për "[[$1]]" ([[Special:Prefixindex/$1|të gjitha faqet që fillojnë me "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|të gjitha faqet që lidhen me"$1"]])',
'searchsubtitleinvalid'            => 'Kërkim për "$1"',
'toomanymatches'                   => 'Ky kërkim ka shumë përfundime, provoni një pyetje tjetër më përcaktuese',
'titlematches'                     => 'Tituj faqesh që përputhen',
'notitlematches'                   => 'Nuk ka asnjë titull faqeje që përputhet',
'textmatches'                      => 'Tekst faqesh që përputhet',
'notextmatches'                    => 'Nuk ka asnjë tekst faqeje që përputhet',
'prevn'                            => '{{PLURAL:$1|$1}} më para',
'nextn'                            => '{{PLURAL:$1|$1}} më pas',
'prevn-title'                      => '$1 i mëparshëm {{PLURAL:$1|rezultat|rezultate}}',
'nextn-title'                      => '$1 në vazhdim {{PLURAL:$1|rezultat|rezultate}}',
'shown-title'                      => 'Trego $1 {{PLURAL:$1|rezultat|rezultate}} për faqe',
'viewprevnext'                     => 'Shikoni ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Parazgjedhjet e kërkimit',
'searchmenu-exists'                => "'''Në këtë wiki kjo faqe është emëruar \"[[:\$1]]\"'''",
'searchmenu-new'                   => "'''Hapë faqen \"[[:\$1]]\" në këtë wiki!'''",
'searchmenu-new-nocreate'          => '"$1" nuk lejohet të përdoret si emër i artikullit.',
'searchhelp-url'                   => 'Help:Ndihmë',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Shfletoi faqet me këtë parashtesë]]',
'searchprofile-articles'           => 'Përmbajtja e faqeve',
'searchprofile-project'            => 'Ndihmë dhe faqet e Projektit',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Gjithçka',
'searchprofile-advanced'           => 'Avancuar',
'searchprofile-articles-tooltip'   => 'Kërko në $1',
'searchprofile-project-tooltip'    => 'Kërko në $1',
'searchprofile-images-tooltip'     => 'Kërko skedarë',
'searchprofile-everything-tooltip' => 'Kërko gjithë përmbajtjen (duke përfshirë edhe faqet e diskutimit)',
'searchprofile-advanced-tooltip'   => 'Kërkimi në hapësina',
'search-result-size'               => '$1 ({{PLURAL:$2|1 fjalë|$2 fjalë}})',
'search-result-category-size'      => '{{PLURAL:$1|1 anëtar|$1 anëtarë}} ({{PLURAL:$2|1 nën-kategori|$2 nën-kategori}}, {{PLURAL:$3|1 skedë|$3 skeda}})',
'search-result-score'              => 'Përkatësia: $1%',
'search-redirect'                  => '(përcjellim $1)',
'search-section'                   => '(seksioni $1)',
'search-suggest'                   => 'Mos kishit në mendje: $1',
'search-interwiki-caption'         => 'Projekte simotra',
'search-interwiki-default'         => '$1 përfundime:',
'search-interwiki-more'            => '(më shumë)',
'search-mwsuggest-enabled'         => 'me këshilla',
'search-mwsuggest-disabled'        => 'pa këshilla',
'search-relatedarticle'            => 'Të ngjashme',
'mwsuggest-disable'                => 'Çmundësoi sugjerimet AJAX',
'searcheverything-enable'          => 'Kërko në të gjitha hapësirat',
'searchrelated'                    => 'të ngjashme',
'searchall'                        => 'të gjitha',
'showingresults'                   => "Më poshtë tregohen {{PLURAL:$1|'''1''' përfundim|'''$1''' përfundime}} duke filluar nga #'''$2'''.",
'showingresultsnum'                => "Më poshtë tregohen {{PLURAL:$3|'''1''' përfundim|'''$3''' përfundime}} duke filluar nga #'''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Rezultati '''$1''' nga '''$3'''|Rezultatet '''$1 - $2''' nga '''$3'''}} për '''$4'''",
'nonefound'                        => "'''Shënim''': Kërkimet pa rezultate ndodhin kur kërkoni për fjalë që rastisen shpesh si \"ke\" dhe \"nga\", të cilat nuk janë të futura në regjistër, ose duke dhënë më shumë se një fjalë (vetëm faqet që i kanë të gjitha ato fjalë do të tregohen si rezultate).",
'search-nonefound'                 => 'Nuk ka rezultate që përputhen me kërkesën.',
'powersearch'                      => 'Kërko',
'powersearch-legend'               => 'Kërkim i përparuar',
'powersearch-ns'                   => 'Kërkim në hapësira:',
'powersearch-redir'                => 'Trego përcjellimet',
'powersearch-field'                => 'Kërko për',
'powersearch-togglelabel'          => 'Zgjedh:',
'powersearch-toggleall'            => 'Tâna',
'powersearch-togglenone'           => 'Asnji',
'search-external'                  => 'Kërkim i jashtëm',
'searchdisabled'                   => '<p>Kërkimi me tekst të plotë është bllokuar tani për tani ngaqë shërbyesi është shumë i ngarkuar; shpresojmë ta nxjerrim prapë në gjendje normale pas disa punimeve. Deri atëherë mund të përdorni Google-in për kërkime:</p>',

# Quickbar
'qbsettings'               => 'Vendime të shpejta',
'qbsettings-none'          => 'Asnjë',
'qbsettings-fixedleft'     => 'Lidhur majtas',
'qbsettings-fixedright'    => 'Lidhur djathtas',
'qbsettings-floatingleft'  => 'Pezull majtas',
'qbsettings-floatingright' => 'Pezull djathtas',

# Preferences page
'preferences'                   => 'Parapëlqimet',
'mypreferences'                 => 'Parapëlqimet',
'prefs-edits'                   => 'Numri i redaktimeve:',
'prefsnologin'                  => 'Nuk keni hyrë brenda',
'prefsnologintext'              => 'Duhet të jeni <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} të kyçur]</span> për të caktuar parapëlqimet e përdoruesit.',
'changepassword'                => 'Ndërroni fjalëkalimin',
'prefs-skin'                    => 'Pamja',
'skin-preview'                  => 'Parapamje',
'prefs-math'                    => 'Formula',
'datedefault'                   => 'Parazgjedhje',
'prefs-datetime'                => 'Data dhe Ora',
'prefs-personal'                => 'Përdoruesi',
'prefs-rc'                      => 'Ndryshime së fundmi',
'prefs-watchlist'               => 'Lista mbikqyrëse',
'prefs-watchlist-days'          => 'Numri i ditëve të treguara tek lista mbikqyrëse:',
'prefs-watchlist-days-max'      => '(maksimum 7 ditë)',
'prefs-watchlist-edits'         => 'Numri i redaktimeve të treguara tek lista mbikqyrëse e zgjeruar:',
'prefs-watchlist-edits-max'     => '(numri maksimal: 1000)',
'prefs-watchlist-token'         => 'Lista mbikqyrëse shenjë:',
'prefs-misc'                    => 'Të ndryshme',
'prefs-resetpass'               => 'Ndrysho fjalëkalimin',
'prefs-email'                   => 'Opsionet E-mail',
'prefs-rendering'               => 'Dukja',
'saveprefs'                     => 'Ruaj parapëlqimet',
'resetprefs'                    => 'Rikthe parapëlqimet',
'restoreprefs'                  => 'Rikthe të gjitha të dhënat e mëparshme',
'prefs-editing'                 => 'Redaktimi',
'prefs-edit-boxsize'            => 'Madhësia e dritares së redaktimit.',
'rows'                          => 'Rreshta:',
'columns'                       => 'Kollona:',
'searchresultshead'             => 'Kërkimi',
'resultsperpage'                => 'Sa përputhje të tregohen për faqe:',
'contextlines'                  => 'Sa rreshta të tregohen për përputhje:',
'contextchars'                  => 'Sa germa të tregohen për çdo rresht:',
'stub-threshold'                => 'Kufiri për formatin e <a href="#" class="stub">lidhjeve cung</a> (B):',
'stub-threshold-disabled'       => 'Çaktivizuar',
'recentchangesdays'             => 'Numri i ditëve të treguara në ndryshime së fundmi:',
'recentchangesdays-max'         => '(maksimum $1 {{PLURAL:$1|dit|ditë}})',
'recentchangescount'            => 'Numri i redaktimeve për të treguar:',
'prefs-help-recentchangescount' => 'Kjo përfshin ndryshimet e freskëta, historikun e faqes dhe regjistrat.',
'prefs-help-watchlist-token'    => 'Plotësimi në këtë fushë me një kyç të fshehtë do të gjenerojë një RSS për të listës mbikqyrëse tuaj. 
 Kushdo që e di të rëndësishme në këtë fushë do të jetë në gjendje për të lexuar lista mbikqyrëse e juaj, kështu që zgjidhni një vlerë të sigurt. 
 Këtu ka një vlerë të rastësishme-generated ju mund të përdorni: $1',
'savedprefs'                    => 'Parapëlqimet tuaja janë ruajtur.',
'timezonelegend'                => 'Zona kohore:',
'localtime'                     => 'Ora lokale:',
'timezoneuseserverdefault'      => 'Përdor serverin e parazgjedhur',
'timezoneuseoffset'             => 'Tjera (zgjidh rajonin)',
'timezoneoffset'                => 'Ofset¹:',
'servertime'                    => 'Ora e shërbyesit:',
'guesstimezone'                 => 'Gjeje nga shfletuesi',
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
'allowemail'                    => 'Lejo përdoruesit të më dërgojnë email',
'prefs-searchoptions'           => 'Mundësi kërkimi',
'prefs-namespaces'              => 'Hapësirat',
'defaultns'                     => 'Kërko automatikisht vetëm në këto hapësira:',
'default'                       => 'parazgjedhje',
'prefs-files'                   => 'Figura',
'prefs-custom-css'              => 'CSS i përpunuem',
'prefs-custom-js'               => 'JavaScripti i përpunuar',
'prefs-common-css-js'           => 'CSS/Javascript të përbashkët për të gjitha pamjet:',
'prefs-reset-intro'             => 'Mundeni me përdorë këtë faqe për me i kthy parapëlqimet tueja në ato të paracaktuemet e faqes.
Kjo nuk mundet me u zhbâ.',
'prefs-emailconfirm-label'      => 'Konfirmimi i emailit:',
'prefs-textboxsize'             => 'Madhësia e dritares së redakrimit',
'youremail'                     => 'Adresa e email-it*',
'username'                      => 'Nofka e përdoruesit:',
'uid'                           => 'Nr. i identifikimit:',
'prefs-memberingroups'          => 'Anëtar i {{PLURAL:$1|grupit|grupeve}}:',
'prefs-registration'            => 'Koha e regjistrimit:',
'yourrealname'                  => 'Emri juaj i vërtetë*',
'yourlanguage'                  => 'Ndërfaqja gjuhësore',
'yourvariant'                   => 'Varianti',
'yournick'                      => 'Nënshkrimi',
'prefs-help-signature'          => 'Komentet në faqet e diskutimit duhet të nënshkruhen me "<nowiki>~~~~</nowiki>" të cilat do të konvertohen me emrin tuaj të përdoruesit dhe kohën.',
'badsig'                        => 'Sintaksa e signaturës është e pavlefshme, kontrolloni HTML-in.',
'badsiglength'                  => 'Nënshkrimi është tepër i gjatë.
Nuk duhet të jetë më i gjatë se $1 {{PLURAL:$1|karakter|karaktere}}.',
'yourgender'                    => 'Gjinia:',
'gender-unknown'                => 'e pacaktuar',
'gender-male'                   => 'Mashkull',
'gender-female'                 => 'Femër',
'prefs-help-gender'             => 'Sipas dëshirës: përdoret për adresim korrekt në relacion me gjininë nga software-i.
Kjo informatë është publike.',
'email'                         => 'Email',
'prefs-help-realname'           => '* Emri i vërtetë nuk është i domosdoshëm: Nëse e jipni do të përmendeni si kontribues për punën që ke bërë.',
'prefs-help-email'              => "Posta elektronike është zgjedhore, por ju mundëson që fjalëkalimi i ri të ju dërgohet nëse e harroni atë. Gjithashtu mund të zgjidhni nëse doni të tjerët t'ju shkruajnë ose jo përmes faqes suaj të diskutimit pa patur nevojë të zbulojnë identitetin tuaj.",
'prefs-help-email-others'       => 'Mundeni gjithashtu të zgjidhni të kontaktoheni nga të tjerët përmes faqeve tuaja të diskutimit ose përdoruesit pa e treguar identitetin.',
'prefs-help-email-required'     => 'Nevojitet e-mail adresa .',
'prefs-info'                    => 'Informatat bazike',
'prefs-i18n'                    => 'Internacionalizimi',
'prefs-signature'               => 'Firma',
'prefs-dateformat'              => 'Data i formatit',
'prefs-timeoffset'              => 'Kohë të kompensuar',
'prefs-advancedediting'         => 'Opsionet e avancuar',
'prefs-advancedrc'              => 'Opsionet e avancuar',
'prefs-advancedrendering'       => 'Opsionet e avancuar',
'prefs-advancedsearchoptions'   => 'Opsionet e avancuar',
'prefs-advancedwatchlist'       => 'Opsionet e avancuar',
'prefs-displayrc'               => 'Shfaq opsionet',
'prefs-displaysearchoptions'    => 'Shfaq opsionet',
'prefs-displaywatchlist'        => 'Shfaq opsionet',
'prefs-diffs'                   => 'Ndryshimet',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-mail adresa është e vlefshme.',
'email-address-validity-invalid' => 'Futni një e-mali adresë të vlefshme.',

# User rights
'userrights'                   => 'Ndrysho privilegjet e përdoruesve',
'userrights-lookup-user'       => 'Ndrysho grupet e përdoruesit',
'userrights-user-editname'     => 'Fusni emrin e përdoruesit:',
'editusergroup'                => 'Redakto grupet e përdoruesve',
'editinguser'                  => "Duke ndryshuar privilegjet e përdoruesit '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Anëtarësimi tek grupet',
'saveusergroups'               => 'Ruaj Grupin e Përdoruesve',
'userrights-groupsmember'      => 'Anëtar i:',
'userrights-groupsmember-auto' => 'Anëtar implicit i:',
'userrights-groups-help'       => 'Mund të ndryshoni anëtarësimin e këtij përdoruesi në grupe:
* Kutia e zgjedhur shënon që përdoruesi është anëtar në atë grup
* Kutia e pazgjedhur shënon që përdoruesi nuk është anëtar në atë grup
* Një * shënon që nuk mund ta hiqni grupin pasi ta keni shtuar (dhe anasjelltas).',
'userrights-reason'            => 'Arsyeja:',
'userrights-no-interwiki'      => 'Nuk keni leje për të ndryshuar privilegjet e përdoruesve në wiki të tjera.',
'userrights-nodatabase'        => 'Regjistri $1 nuk ekziston ose nuk është vendor.',
'userrights-nologin'           => 'Duhet të [[Special:UserLogin|hyni brenda]] me një llogari administrative për të ndryshuar privilegjet e përdoruesve.',
'userrights-notallowed'        => 'Llogaria juaj nuk ju lejon të ndryshoni privilegjet e përdoruesve.',
'userrights-changeable-col'    => 'Grupe që mund të ndryshoni',
'userrights-unchangeable-col'  => "Grupe që s'mund të ndryshoni",

# Groups
'group'               => 'Grupi:',
'group-user'          => 'Përdorues',
'group-autoconfirmed' => 'Përdorues të vërtetuar automatikisht',
'group-bot'           => 'Robot',
'group-sysop'         => 'Administrues',
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
'right-read'                  => 'Lexo faqe',
'right-edit'                  => 'Redakto faqet',
'right-createpage'            => 'Hap faqe (që nuk janë faqe diskutimi)',
'right-createtalk'            => 'Hap faqe diskutimi',
'right-createaccount'         => 'Hap llogari të re',
'right-minoredit'             => 'Shëno redaktimet si të vogla',
'right-move'                  => 'Lëviz faqet',
'right-move-subpages'         => 'Lëviz faqet me nënfaqet e tyre',
'right-move-rootuserpages'    => 'Lëviz burimin e faqes së përdoruesit',
'right-movefile'              => 'Lëviz skedarët',
'right-suppressredirect'      => 'Mos krijo zhvendosje nga emri i vjetër kur lëvizë një faqe',
'right-upload'                => 'Ngarko skedarë',
'right-reupload'              => 'Ringarko skedën ekzistuese',
'right-reupload-own'          => 'Ringarko skedën ekzistuese të ngarkuar vetë',
'right-reupload-shared'       => 'Mos pranoni skeda në media magazinën e përbashkët në nivel lokal',
'right-upload_by_url'         => 'Ngarko skedë nga ndonjë URL',
'right-purge'                 => 'Pastro "cache" e site-it për një faqe pa konfirmim',
'right-autoconfirmed'         => 'Redakto faqet gjysmë të mbrojtura',
'right-bot'                   => 'Trajtohu si një proces automatik',
'right-nominornewtalk'        => 'Nuk kanë redaktimet e vogla për faqet e diskutimit të shkaktuar mesazhe të reja e shpejtë',
'right-apihighlimits'         => 'Vendosni kufijtë më të lartë në pyetjet API',
'right-writeapi'              => 'Përdorimi i shkrimit API',
'right-delete'                => 'Gris faqet',
'right-bigdelete'             => 'Gris faqet me histori të gjata',
'right-deleterevision'        => 'Grisi dhe riktheji revizionet specifike të faqeve',
'right-deletedhistory'        => 'Shiko shënimet e grisura të historikut, pa tekstet e tyre të shoqëruara',
'right-deletedtext'           => 'Shiko tekstin dhe ndryshimet e grisura ndërmjet versioneve të grisura',
'right-browsearchive'         => 'Kërko faqe të grisura',
'right-undelete'              => 'Rikthe faqen',
'right-suppressrevision'      => 'Rishiko dhe rikthe versionet e fshehura nga administratorët',
'right-suppressionlog'        => 'Shiko hyrjet private',
'right-block'                 => 'Blloko përdoruesit tjerë nga editimi',
'right-blockemail'            => 'Blloko përdoruesin që të mos dërgojë postë elektronike',
'right-hideuser'              => 'Blloko përdorues, duke fshehur nga publiku',
'right-ipblock-exempt'        => 'Anashkalo bllokimet e IP-ve, auto-bllokimet dhe linjën e bllokimeve',
'right-proxyunbannable'       => 'Anashkalo bllokimet automatike të ndërmjetësve',
'right-unblockself'           => 'Zhblloko veten',
'right-protect'               => 'Ndrysho nivelin mbrojtës dhe redakto faqet e mbrojtura',
'right-editprotected'         => 'Redakto faqet e mbrojtura (pa ndryshuar mbrojtjen)',
'right-editinterface'         => 'Ndrysho parapamjen e përdoruesit',
'right-editusercssjs'         => 'Redakto skedat CSS dhe JS të përdoruesve tjerë',
'right-editusercss'           => 'Redakto skedat CSS të përdoruesve tjerë',
'right-edituserjs'            => 'Redakto skedat JS të përdoruesve tjerë',
'right-rollback'              => 'Rikthen shpejt redaktimet  e pedaktuesit të fundit',
'right-markbotedits'          => 'Shëno rikthimet si redaktime robotësh',
'right-noratelimit'           => 'Mos u prek nga kufizimet e vlerësimit',
'right-import'                => 'Importo faqe nga wiki tjera',
'right-importupload'          => 'Importo faqet nga një ngarkim skede',
'right-patrol'                => 'Shëno redaktimin e tjerëve si të patrulluar',
'right-autopatrol'            => 'A e vet redaktimet e shënuar automatikisht patrulluar',
'right-patrolmarks'           => 'Shiko ndryshimet e fundit shënon patrullë',
'right-unwatchedpages'        => 'Shiko listën e faqeve të pa vëzhguara',
'right-trackback'             => 'Dërgoni një ndjekëse',
'right-mergehistory'          => 'Bashko historinë e faqeve',
'right-userrights'            => 'Redakto të gjitha të drejtat e përdoruesit',
'right-userrights-interwiki'  => 'Ndrysho të drejtat e përdoruesve në wiki të tjera',
'right-siteadmin'             => 'Mbyll ose hap bazën e të dhënave',
'right-reset-passwords'       => 'Rivendos fjalëkalimet e përdoruesit të tjerë',
'right-override-export-depth' => 'Eksoprto faqet duke përfshirë e lidhura deri në një thellësi prej 5',
'right-sendemail'             => 'Dërgo e-mail tek përdoruesit e tjerë',
'right-revisionmove'          => 'Lëviz versionet',
'right-disableaccount'        => 'Çaktivizo llogaritë',

# User rights log
'rightslog'      => 'Regjistri i privilegjeve',
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
'action-move-rootuserpages'   => 'lëviz rrënjët e faqeve të përdoruesve',
'action-movefile'             => 'lëviz këtë skedë',
'action-upload'               => 'ngarko këtë skedë',
'action-reupload'             => 'rishkruaj këtë skedë ekzistuese',
'action-reupload-shared'      => 'mbishkruaj këtë skedarë në një magazinë të përbashkët',
'action-upload_by_url'        => 'ngarko këtë skedë nga një URL',
'action-writeapi'             => 'përdor API-në e shkrimit',
'action-delete'               => 'grise këtë faqe',
'action-deleterevision'       => 'grise këtë revizion',
'action-deletedhistory'       => 'shiko historinë e kësaj faqeje të grisur',
'action-browsearchive'        => 'kërko faqe të grisura',
'action-undelete'             => 'Restauro këtë faqe',
'action-suppressrevision'     => 'rishiko dhe rikthe këtë revizion të fshehur',
'action-suppressionlog'       => 'shiko këtë regjistër privat',
'action-block'                => 'blloko përdoruesin',
'action-protect'              => 'ndrysho nivelin e mbrojtjes për këtë faqe',
'action-import'               => 'importo këtë faqe nga një wiki tjetër',
'action-importupload'         => 'importo këtë faqe nga një ngarkim i një skedari',
'action-patrol'               => 'shëno redaktimin e tjerëve si të patrulluar',
'action-autopatrol'           => 'shëno redaktimet tua si të patrulluara',
'action-unwatchedpages'       => 'shiko listën e faqeve të pa vrojtuara',
'action-trackback'            => 'dërgo një ndjekës',
'action-mergehistory'         => 'bashko historikun e kësaj faqeje',
'action-userrights'           => 'ndrysho të gjitha të drejtat e përdoruesit',
'action-userrights-interwiki' => 'ndrysho të drejtat e përdoruesve në wiki-t tjera',
'action-siteadmin'            => 'mbyll ose hap bazën e të dhënave',
'action-revisionmove'         => 'lëviz versionet',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|ndryshim|ndryshime}}',
'recentchanges'                     => 'Ndryshime së fundmi',
'recentchanges-legend'              => 'Zgjedhjet e ndryshimeve momentale',
'recentchangestext'                 => 'Ndiqni ndryshime së fundmi tek kjo faqe.',
'recentchanges-feed-description'    => 'Ndjek ndryshimet më të fundit në wiki tek kjo fushë.',
'recentchanges-label-newpage'       => 'Ky redaktim krijoi një faqe të re',
'recentchanges-label-minor'         => 'Ky është një editim i vogël',
'recentchanges-label-bot'           => 'Ky editim është kryer nga një bot',
'recentchanges-label-unpatrolled'   => 'Ky editim ende nuk është patrolluar',
'rcnote'                            => "Më poshtë {{PLURAL:$1|është '''1''' ndryshim| janë '''$1''' ndryshime}} së fundmi gjatë <strong>$2</strong> ditëve sipas të dhënave nga $4, $5.",
'rcnotefrom'                        => 'Më poshtë janë ndryshime së fundmi nga <b>$2</b> (treguar deri në <b>$1</b>).',
'rclistfrom'                        => 'Tregon ndryshime së fundmi duke filluar nga $1',
'rcshowhideminor'                   => '$1 redaktimet e vogla',
'rcshowhidebots'                    => '$1 robotët',
'rcshowhideliu'                     => '$1 përdoruesit e regjistruar',
'rcshowhideanons'                   => '$1 përdoruesit anonim',
'rcshowhidepatr'                    => '$1 redaktime të patrulluara',
'rcshowhidemine'                    => '$1 redaktimet e mia',
'rclinks'                           => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'diff'                              => 'ndrysh',
'hist'                              => 'hist',
'hide'                              => 'fshih',
'show'                              => 'trego',
'minoreditletter'                   => 'v',
'newpageletter'                     => 'R',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 duke u mbikqyrur nga {{PLURAL:$1|përdorues|përdorues}}]',
'rc_categories'                     => 'Kufizimi i kategorive (të ndara me "|")',
'rc_categories_any'                 => 'Të gjitha',
'newsectionsummary'                 => '/* $1 */ seksion i ri',
'rc-enhanced-expand'                => 'Trego detajet (kërkon JavaScript)',
'rc-enhanced-hide'                  => 'Fshih detajet',

# Recent changes linked
'recentchangeslinked'          => 'Ndryshimet fqinje',
'recentchangeslinked-feed'     => 'Ndryshimet fqinje',
'recentchangeslinked-toolbox'  => 'Ndryshimet fqinje',
'recentchangeslinked-title'    => 'Ndryshimet në lidhje me "$1"',
'recentchangeslinked-noresult' => 'Nuk ka pasur ndryshime tek faqet e lidhura gjatë kohës së dhënë.',
'recentchangeslinked-summary'  => "Kjo është një listë e ndryshimeve së fundmi të faqeve të lidhura nga faqja e dhënë (ose bëjnë pjesë tek kategoria e dhënë).
Faqet [[Special:Watchlist|nën mbikqyrjen tuaj]] duken të '''theksuara'''.",
'recentchangeslinked-page'     => 'Emri i faqes:',
'recentchangeslinked-to'       => 'Trego ndryshimet e faqeve që lidhen tek faqja e dhënë',

# Upload
'upload'                      => 'Ngarkoni skeda',
'uploadbtn'                   => 'Ngarkoje',
'reuploaddesc'                => 'Kthehu tek formulari i dhënies.',
'upload-tryagain'             => 'Dërgo përshkrimin e modifikuar të skedarit',
'uploadnologin'               => 'Nuk keni hyrë brënda',
'uploadnologintext'           => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] për të dhënë skeda.',
'upload_directory_missing'    => 'Direktoriumi ($1) i ngarkimit po mungon dhe nuk është arritur që të krijohet nga webserveri.',
'upload_directory_read_only'  => 'Skedari i ngarkimit ($1) nuk mund të shkruhet nga shërbyesi.',
'uploaderror'                 => 'Gabim dhënie',
'upload-recreate-warning'     => "'''Kujdes: Një skedarë me atë emër është fshirë apo lëvizur.'''

Regjistri i fshirjes dhe lëvizjes për këtë faqe për lehtësim ofrohen këtu:",
'uploadtext'                  => "Përdorni formularin e mëposhtëm për të ngarkuar skeda.
Për të parë ose kërkuar skeda të ngarkuara më parë, shkoni tek [[Special:FileList|lista e ngarkimeve të skedave]],
(ri)ngarkimet janë gjithashtu të regjistruara tek [[Special:Log/upload|regjistri i ngarkimeve]], grisjet tek [[Special:Log/delete|regjistri i grisjeve]].

Për të përfshirë një skedë në një faqe, përdorni një nga format e mëposhtme:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skeda.jpg]]</nowiki></tt>''' për të përdorur versionin e plotë të skedës
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skeda.png|200px|thumb|left|alt text]]</nowiki></tt>''' për të përdorur nje interpretim prej 200 piksel në të majtë me 'alt tekst' si përshkrim
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Skeda.ogg]]</nowiki></tt>''' për të lidhur skedën direkt, pa e shfaqur atë",
'upload-permitted'            => 'Llojet e lejuara të skedave: $1.',
'upload-preferred'            => 'Llojet e parapëlqyera të skedave: $1.',
'upload-prohibited'           => 'Llojet e ndaluara të skedave: $1.',
'uploadlog'                   => 'regjistër dhënjesh',
'uploadlogpage'               => 'Regjistri i ngarkimeve',
'uploadlogpagetext'           => 'Më poshtë është një listë e skedave më të reja që janë ngarkuar.
Të gjithë orët janë me orën e shërbyesit.',
'filename'                    => 'Emri i skedës',
'filedesc'                    => 'Përmbledhje',
'fileuploadsummary'           => 'Përshkrimi:',
'filereuploadsummary'         => 'Ndryshimet e skedës:',
'filestatus'                  => 'Gjendja e të drejtave të autorit:',
'filesource'                  => 'Burimi:',
'uploadedfiles'               => 'Ngarkoni skeda',
'ignorewarning'               => 'Shpërfille paralajmërimin dhe ruaje skedën.',
'ignorewarnings'              => 'Shpërfill çdo paralajmërim',
'minlength1'                  => 'Emri i dosjes duhet të jetë së paku një fjalë',
'illegalfilename'             => 'Skeda "$1" përmban gërma që nuk lejohen tek titujt e faqeve. Ju lutem ndërrojani emrin dhe provoni ta ngarkoni përsëri.',
'badfilename'                 => 'Emri i skedës është ndërruar në "$1".',
'filetype-mime-mismatch'      => 'Prapashtesa .$1 e skedarit ($2) nuk përshtatet me tipin MIME.',
'filetype-badmime'            => 'Skedat e llojit MIME "$1" nuk lejohen për ngarkim.',
'filetype-bad-ie-mime'        => 'Nuk mund të ngarkoni këtë skedë sepse Internet Explorer do ta zbulonte si "$1", që është një lloj skede e papranuar dhe potencialisht e rrezikshme.',
'filetype-unwanted-type'      => "'''\".\$1\"''' është një lloj skede i padëshiruar.
Parapëlqehet {{PLURAL:\$3|skeda të jetë e |skedat të jenë të}} llojit \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' nuk është lloj i lejuar i skedave.
Si {{PLURAL:\$3|i lejuar është lloji i skedës|të lejuara janë llojet e skedave}} \$2.",
'filetype-missing'            => 'Skeda nuk ka mbaresë (si p.sh. ".jpg").',
'empty-file'                  => 'Skeda që paraqitët ishte bosh.',
'file-too-large'              => 'Skeda që paraqitët ishte shumë e madhe.',
'filename-tooshort'           => 'Emri i skedës është shumë i shkurtër.',
'filetype-banned'             => 'Kjo lloji i skedës është e ndalur.',
'verification-error'          => 'Kjo skedë nuk e kaloi verifikimin e skedave.',
'hookaborted'                 => 'Modifikimi që ju provuat ta bëni u ndërpre nga një goditje shtesë.',
'illegal-filename'            => 'Emri i skedarit nuk lejohet.',
'overwrite'                   => 'Mbishkrimi i një skede ekzistuese nuk lejohet.',
'unknown-error'               => 'Një gabim i panjohur.',
'tmp-create-error'            => 'Nuk mund të krijohej skeda e përkohëshme.',
'tmp-write-error'             => 'Gabim gjatë shkrimit të skedës së përkohshme.',
'large-file'                  => 'Është e këshillueshme që skedat të jenë jo më shumë se $1;
kjo skedë është $2.',
'largefileserver'             => 'Skeda është më e madhe se sa serveri e lejon këtë.',
'emptyfile'                   => 'Skeda që keni dhënë është bosh ose mbi madhësinë e lejushme. Kjo gjë mund të ndodhi nëse shtypni emrin gabim, prandaj kontrolloni nëse dëshironi të jepni skedën me këtë emër.',
'fileexists'                  => "Ekziston një skedë me atë emër, ju lutem kontrolloni '''<tt>[[:$1]]</tt>''' në qoftë se nuk jeni të sigurt nëse dëshironi ta zëvendësoni.
[[$1|thumb]]",
'filepageexists'              => "Përshkrimi i faqes për këtë skedë është krijuar tek '''<tt>[[:$1]]</tt>''', por asnjë skedë me këtë emër nuk ekziston.
Përmbledhja që shkruat nuk do të shfaqet në përshkrimin e faqes.
Për ta bërë përmbledhjen tuaj të dukshme atje, ju duhet ta redaktoni automatikisht.
[[$1|thumb]]",
'fileexists-extension'        => "Ekziston një skedë me emër të ngjashëm: [[$2|thumb]]
* Emri i skedës në ngarkim: '''<tt>[[:$1]]</tt>'''
* Emri i skedës ekzistuese: '''<tt>[[:$2]]</tt>'''
Ju lutem zgjidhni një emër tjetër.",
'fileexists-thumbnail-yes'    => "Kjo skedë duket se është një figurë me madhësi të zvogëluar ''(figurë përmbledhëse)''. [[$1|thumb]]
Ju lutem kontrolloni skedën '''<tt>[[:$1]]</tt>'''.
Nëse skeda e kontrolluar është e së njëjtës madhësi me origjinalen atëherë nuk ka nevojë të ngarkoni një figurë përmbledhëse.",
'file-thumbnail-no'           => "Emri i skedës fillon me '''<tt>$1</tt>'''.
Duket se është një figurë me madhësi të zvogëluar ''(thumbnail)''.
Nëse keni këtë figurë me madhësi të plotë ju lutem të ngarkoni atë, përndryshe ju lutem të ndryshoni emrin e skedës.",
'fileexists-forbidden'        => 'Ekziston një skedë me të njëjtin emër. Ju lutemi kthehuni mbrapsht dhe ngarkoni këtë skedë me një emër të ri. 
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ekziston një skedë me të njëjtin emër në magazinën e përbashkët. Ju lutem kthehuni mbrapsht dhe ngarkojeni këtë skedë me një emër të ri. 
 [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Kjo skedë është dyfish i {{PLURAL:$1|skedës|skedave}} në vijim:',
'file-deleted-duplicate'      => 'Një skedë identike më këtë skedë ([[$1]]) është grisur më përpara.
Ju duhet të kontrolloni historikun e grisjes të asaj skede përpara se ta ri-ngarkoni atë.',
'uploadwarning'               => 'Kujdes dhënie',
'uploadwarning-text'          => 'Ju lutemi modifikoni përshkrimin e skedës dhe provojen përsëri.',
'savefile'                    => 'Ruaj skedën',
'uploadedimage'               => 'dha "[[$1]]"',
'overwroteimage'              => 'dha dhe zëvendësoi me një version të ri të "[[$1]]"',
'uploaddisabled'              => 'Ndjesë, dhëniet janë bllokuar në këtë shërbyes dhe nuk është gabimi juaj.',
'copyuploaddisabled'          => 'Ngarkimi nga URL-ja u çaktivizua.',
'uploadfromurl-queued'        => 'Ngarkimi juaj ka qenë në radhë.',
'uploaddisabledtext'          => 'Ngarkimi i skedave është i ndaluar.',
'php-uploaddisabledtext'      => 'Ngarkimet e skedave në PHP janë të çaktivizuara.
Ju lutemi kontrolloni parametrat e ngarkimeve të skedave.',
'uploadscripted'              => 'Skeda përmban HTML ose kode të tjera që mund të interpretohen gabimisht nga një shfletues.',
'uploadvirus'                 => 'Skeda përmban një virus! Detaje: $1',
'upload-source'               => 'Burimi i skedës',
'sourcefilename'              => 'Emri i skedës:',
'sourceurl'                   => 'Burimi URL:',
'destfilename'                => 'Emri mbas dhënies:',
'upload-maxfilesize'          => 'Madhësia maksimale e skedave: $1',
'upload-description'          => 'Përshkrimi i skedës',
'upload-options'              => 'Opsionet e ngarkimit',
'watchthisupload'             => 'Mbikqyre këtë skedë',
'filewasdeleted'              => 'Një skedë më këtë emër është ngarkuar një here dhe pastaj është grisur. Duhet të shikoni $1 përpara se ta ngarkoni përsëri.',
'upload-wasdeleted'           => "'''Vini re: Po ngarkoni një skedë që është grisur më parë.'''

Duhet të mendoheni nëse është e pranueshme ngarkimi i kësaj skede.
Regjistri i grisjes së skedës jepet më poshtë:",
'filename-bad-prefix'         => "Emri i skedës që po ngarkoni fillon me '''\"\$1\"''' dhe nuk është veçantisht përshkrues pasi përdoret nga shumë kamera.
Ju lutem zgjidhni një emër më përshkrues për skedën tuaj.",
'upload-success-subj'         => 'Dhënie e sukseshme',
'upload-success-msg'          => 'Ngarkimi juaj nga [$2] ishte i suksesshëm. Mund të gjendet këtu: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problem gjatë ngarkimit',
'upload-failure-msg'          => 'Kishte një problem me ngarkimin tuaj nga [$2]:

$1',
'upload-warning-subj'         => 'Paralajmërim për ngarkimin',
'upload-warning-msg'          => 'Kishte një problem me ngarkimin tuaj nga [$2]. Ju mund të ktheheni tek [[Special:Upload/stash/$1|forma e ngarkimit]] për të korrgjuar këtë problem.',

'upload-proto-error'        => 'Protokoll i gabuar',
'upload-proto-error-text'   => 'Ngarkimet nga rrjeti kërkojnë që adresa URL të fillojë me <code>http://</code> ose <code>ftp://</code>.',
'upload-file-error'         => 'Gabim i brendshëm',
'upload-file-error-text'    => 'Ka ndodhur një gabim i brendshëm gjatë krijimit të skedës së përkohshme nga shërbyesi.
Ju lutemi kontaktoni një [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error'         => 'Gabim i panjohur ngarkimi',
'upload-misc-error-text'    => 'Një gabim i panjohur ka ndodhur gjatë ngarkimit.
Ju lutemi kontrolloni që adresa URL të jetë e vlefshme dhe e kapshme dhe provoni përsëri.
Nëse problemi vazhdon atëherë kontaktoni një [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'Adresa URL përmbante shumë përcjellime.',
'upload-unknown-size'       => 'Madhësia e panjohur',
'upload-http-error'         => 'Ndodhi një gabim HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Refuzohet hyrja',
'img-auth-nopathinfo'   => 'Mungon PATH_INFO.
Shërbyesi juaj nuk e kalon këtë informacion.
Mund të jetë CGI-bazuar dhe nuk mund të mbështesë img_auth.
Shikoni http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'Kërkesa nuk është në drejtorinë e ngarkimeve të konfiguruara.',
'img-auth-badtitle'     => 'Nuk mund të krihohej një titull i vlefshëm nga "$1".',
'img-auth-nologinnWL'   => 'Ju nuk jeni i regjistruar dhe "$1" nuk është në listën e bardhë.',
'img-auth-nofile'       => 'Skeda "$1" nuk ekziston.',
'img-auth-isdir'        => 'Ju po përpiqeni të hyni në një drejtori "$1".
Vetëm  qasja e skedës është e lejuar.',
'img-auth-streaming'    => 'Duke rrejdhur "$1"',
'img-auth-public'       => 'Funksioni i img_auth.php është për të larguar skedat nga një wiki privat.
Ky wiki është i konfiguruar si një wiki publik.
Për siguri optimale, img_auth.php është çaktivizuar.',
'img-auth-noread'       => 'Përdoruesi nuk ka qasje për të lexuar "$1".',

# HTTP errors
'http-invalid-url'      => 'Adresë URL e pavlefshme: $1',
'http-invalid-scheme'   => 'Adresat URL me skemën "$1" nuk mbështeten.',
'http-request-error'    => 'Kërkesa HTTP dështoi për shkak të një gabimi të panjohur.',
'http-read-error'       => 'Gabim në leximin e HTTP.',
'http-timed-out'        => 'Kërkesës HTTP i kaloi koha.',
'http-curl-error'       => 'Gabim gjatë gjetjes së URL-së: $1',
'http-host-unreachable' => 'Nuk mund të lidheni me adresën URL.',
'http-bad-status'       => 'Ndodhi një problem gjatë kërkesës HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "S'munda të lidhem me adresën URL",
'upload-curl-error6-text'  => 'Adresa e dhënë URL nuk mund të arrihej.
Ju lutem kontrollojeni nëse është e saktë dhe nëse faqja punon.',
'upload-curl-error28'      => 'Mbaroi koha e ngarkimit',
'upload-curl-error28-text' => 'Ka kaluar shumë kohë pa përgjigje.
Ju lutem kontrolloni nëse faqja është në rrjet, prisni pak dhe provojeni përsëri.
Këshillohet që ta provoni kur të jetë më pak e zënë.',

'license'            => 'Licencimi:',
'license-header'     => 'Licencimi:',
'nolicense'          => 'Asnjë nuk është zgjedhur',
'license-nopreview'  => '(Nuk ka parapamje)',
'upload_source_url'  => ' (URL e vlefshme, publikisht e përdorshme)',
'upload_source_file' => ' (skeda në kompjuterin tuaj)',

# Special:ListFiles
'listfiles-summary'     => 'Kjo faqe speciale tregon tërë skedat e ngarkuara.
Fillimisht skedat e ngarkuara së fundmi jepen më sipër.
Shtypni kolonat e tjera për të ndryshuar radhitjen.',
'listfiles_search_for'  => 'Kërko për emrin e figurës:',
'imgfile'               => 'skeda',
'listfiles'             => 'Lista e figurave',
'listfiles_thumb'       => 'Parapamje',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Emri',
'listfiles_user'        => 'Përdoruesi',
'listfiles_size'        => 'Madhësia (bytes)',
'listfiles_description' => 'Përshkrimi',
'listfiles_count'       => 'Versionet',

# File description page
'file-anchor-link'          => 'Figura',
'filehist'                  => 'Historiku i dosjes',
'filehist-help'             => 'Shtypni një datë/kohë për ta parë skedën ashtu si dukej në atë kohë.',
'filehist-deleteall'        => 'grisi të tëra',
'filehist-deleteone'        => 'grise këtë',
'filehist-revert'           => 'riktheje',
'filehist-current'          => 'e tanishme',
'filehist-datetime'         => 'Data/Ora',
'filehist-thumb'            => 'Parapamje',
'filehist-thumbtext'        => 'Parapamja për versionin nga $1',
'filehist-nothumb'          => "S'ka parapamje",
'filehist-user'             => 'Përdoruesi',
'filehist-dimensions'       => 'Dimensionet',
'filehist-filesize'         => 'Madhësia e skedës',
'filehist-comment'          => 'Koment',
'filehist-missing'          => 'Mungon skeda',
'imagelinks'                => 'Lidhje skedash',
'linkstoimage'              => '{{PLURAL:$1|faqe lidhet|$1 faqe lidhen}} tek kjo skedë:',
'linkstoimage-more'         => 'Më shumë se $1 {{PLURAL:$1|lidhje faqeje|lidhje faqesh}} tek kjo skedë.
Lista e mëposhtme tregon {{PLURAL:$1|lidhjen e parë të faqes|lidhjet e para $1 të faqeve}} vetëm tek kjo skedë.
Një [[Special:WhatLinksHere/$2|listë e plotë]] është e mundur.',
'nolinkstoimage'            => 'Asnjë faqe nuk lidhet tek kjo skedë.',
'morelinkstoimage'          => 'Shikoni [[Special:WhatLinksHere/$1|më shumë lidhje]] tek kjo skedë.',
'redirectstofile'           => 'Skeda vijuese {{PLURAL:$1|file redirects|$1 ridrejtohet}} tek kjo skedë:',
'duplicatesoffile'          => 'Në vijim {{PLURAL:$1|skeda është identike|$1 janë idnetike}} me këtë skedë
([[Special:FileDuplicateSearch/$2|më shumë detaje]]):',
'sharedupload'              => 'Kjo skedë është nga $1 dhe mund të përdoret në projekte të tjera.',
'sharedupload-desc-there'   => 'Kjo skedë është nga $1 dhe mund të përdoret nga projektet e tjera.
Ju lutemi shikoni [$2 faqen e përshkrimit] për informacion të mëtejshëm.',
'sharedupload-desc-here'    => 'Kjo skedë është nga $1 dhe mund të përdoret nga projektet e tjera.
Përshkrimi në [$2 faqen përshkruese të skedës] është treguar më poshtë.',
'filepage-nofile'           => 'Asnjë faqe nuk lidhet tek kjo skedë.',
'filepage-nofile-link'      => 'Një skedë me këtë emër nuk ekziston akoma, ju mundeni ta [$1 ngarkoni atë].',
'uploadnewversion-linktext' => 'Ngarkoni një version të ri për këtë skedë',
'shared-repo-from'          => 'nga $1',
'shared-repo'               => 'një magazinë e përbashkët',

# File reversion
'filerevert'                => 'Rikthe $1',
'filerevert-legend'         => 'Rikthe skedën',
'filerevert-intro'          => "Po ktheni '''[[Media:$1|$1]]''' tek [versioni $4 i $3, $2].",
'filerevert-comment'        => 'Arsyeja:',
'filerevert-defaultcomment' => 'U rikthye tek versioni i $2, $1',
'filerevert-submit'         => 'Riktheje',
'filerevert-success'        => "'''[[Media:$1|$1]]''' është kthyer tek [versioni $4 i $3, $2].",
'filerevert-badversion'     => 'Nuk ka version vendor tjetër të kësaj skede në kohën e dhënë.',

# File deletion
'filedelete'                  => 'Grise $1',
'filedelete-legend'           => 'Grise skedën',
'filedelete-intro'            => "Jeni duke grisur '''[[Media:$1|$1]]''' së baashku me të gjithë historikun e saj.",
'filedelete-intro-old'        => "Po grisni versionin e '''[[Media:$1|$1]]''' të [$4 $3, $2].",
'filedelete-comment'          => 'Arsyeja:',
'filedelete-submit'           => 'Grise',
'filedelete-success'          => "'''$1''' është grisur.",
'filedelete-success-old'      => "Versioni i '''[[Media:$1|$1]]''' që nga $3, $2 është grisur.",
'filedelete-nofile'           => "'''$1''' nuk ekziston.",
'filedelete-nofile-old'       => "Nuk ka version të arkivuar të '''$1''' me të dhënat e kërkuara.",
'filedelete-otherreason'      => 'Arsye tjetër / shtesë:',
'filedelete-reason-otherlist' => 'Arsye tjetër',
'filedelete-reason-dropdown'  => '*Arsye të shpeshpërdorura për grisje:
** Kundër të drejtave të autorit
** Skedë kopje',
'filedelete-edit-reasonlist'  => 'Arsye grisjeje për redaktimet',
'filedelete-maintenance'      => 'Grisja dhe restaurimi i skedave është çaktivizuar përkohësisht gjatë mirëmbajtjes.',

# MIME search
'mimesearch'         => 'Kërkime MIME',
'mimesearch-summary' => 'Kjo faqe lejon kërkimin e skedave sipas llojit MIME. Kërkimi duhet të jetë i llojit: contenttype/subtype, p.sh. <tt>image/jpeg</tt>.',
'mimetype'           => 'Lloji MIME:',
'download'           => 'shkarkim',

# Unwatched pages
'unwatchedpages' => 'Faqe të pambikqyrura',

# List redirects
'listredirects' => 'Lista e përcjellimeve',

# Unused templates
'unusedtemplates'     => 'Stampa të papërdorura',
'unusedtemplatestext' => 'Kjo faqe radhitë të gjitha faqet në {{ns:template}} që nuk janë të përfshira në faqe tjera.
Mos harroni të shihni nyje tjera të stampave para grisjes së tyre.',
'unusedtemplateswlh'  => 'lidhje',

# Random page
'randompage'         => 'Artikull i rastit',
'randompage-nopages' => 'Nuk ka faqe në {{PLURLA:$2|hapësirën|hapësirat}} në vijim: $1',

# Random redirect
'randomredirect'         => 'Përcjellim i rastit',
'randomredirect-nopages' => 'Nuk ka përcjellim në "$1".',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Statistikat e faqes',
'statistics-header-edits'      => 'Statistikat e redaktimit',
'statistics-header-views'      => 'Statistikat e shikimit',
'statistics-header-users'      => 'Statistikat e përdoruesve',
'statistics-header-hooks'      => 'Statistikat të tjera',
'statistics-articles'          => 'Përmbajtja e faqeve',
'statistics-pages'             => 'Faqet',
'statistics-pages-desc'        => 'Të gjitha faqet në wiki, duke përfshitë edhe faqet e diskutimit, zhvendosjet, etj.',
'statistics-files'             => 'Skedat e ngarkuara',
'statistics-edits'             => 'Redaktimet e faqes që kur {{SITENAME}} u regjistrua',
'statistics-edits-average'     => 'Ndryshime mesatare për faqe',
'statistics-views-total'       => 'Shikimet gjithsej',
'statistics-views-total-desc'  => 'Shikimet tek faqet joekzistuese dhe faqet speciale nuk janë të përfshira',
'statistics-views-peredit'     => 'Shikimet për redaktim',
'statistics-users'             => '[[Special:ListUsers|Përdoruesit]] e regjistruar',
'statistics-users-active'      => 'Përdoruesit aktiv',
'statistics-users-active-desc' => 'Përdoruesit që kanë së paku një veprim në {{PLURAL:$1|ditën|$1 ditët}} e fundit',
'statistics-mostpopular'       => 'Faqet më të shikuara',

'disambiguations'      => 'Faqe kthjelluese',
'disambiguationspage'  => 'Template:Kthjellim',
'disambiguations-text' => "Faqet e mëposhtme lidhen tek një '''faqe kthjelluese'''.
Ato duhet të kenë lidhje të drejtpërdrejtë tek artikujt e nevojshëm.<br />
Një faqe trajtohet si faqe kthjelluese nëse përdor stampat e lidhura nga [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Përcjellime dopjo',
'doubleredirectstext'        => "Kjo faqe liston faqet përcjellëse tek faqet e tjera përcjellëse.
Secili rresht përmban lidhjet tek përcjellimi i parë dhe përcjellimi i dytë, gjithashtu synimin e përcjellimit të dytë, që është zakonisht faqja synuese '''e vërtetë''', që faqja w parë duhej të ishte përcjellëse e kësaj faqeje.
<del>Kalimet nga</del> hyrjet janë zgjidhur.",
'double-redirect-fixed-move' => '[[$1]] u zhvendos, tani është gjendet në [[$2]]',
'double-redirect-fixer'      => 'Rregullues zhvendosjesh',

'brokenredirects'        => 'Përcjellime të prishura',
'brokenredirectstext'    => "Përcjellimet që vijojnë lidhen tek një artikull që s'ekziston:",
'brokenredirects-edit'   => 'redakto',
'brokenredirects-delete' => 'grise',

'withoutinterwiki'         => 'Artikuj pa lidhje interwiki',
'withoutinterwiki-summary' => 'Artikujt në vazhdim nuk kanë asnjë lidhje te wikit në gjuhët tjera:',
'withoutinterwiki-legend'  => 'Parashtesa',
'withoutinterwiki-submit'  => 'Trego',

'fewestrevisions' => 'Artikuj më të paredaktuar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories'             => '$1 {{PLURAL:$1|kategori|kategori}}',
'nlinks'                  => '$1 {{PLURAL:$1|lidhje|lidhje}}',
'nmembers'                => '$1 {{PLURAL:$1|antar|antarë}}',
'nrevisions'              => '$1 {{PLURAL:$1|version|versione}}',
'nviews'                  => '$1 {{PLURAL:$1|shikim|shikime}}',
'nimagelinks'             => 'Përdorur në $1 {{PLURAL:$1|faqe|faqe}}',
'ntransclusions'          => 'përdorur në $1 {{PLURAL:$1|faqe|faqe}}',
'specialpage-empty'       => 'Kjo faqe është boshe.',
'lonelypages'             => 'Artikuj të palidhur',
'lonelypagestext'         => 'Faqet në vijim nuk janë të lidhura ose nuk janë të përfshira në faqet tjera në {{SITENAME}}.',
'uncategorizedpages'      => 'Artikuj të pakategorizuar',
'uncategorizedcategories' => 'Kategori të pakategorizuara',
'uncategorizedimages'     => 'Figura pa kategori',
'uncategorizedtemplates'  => 'Stampa të pakategorizuara',
'unusedcategories'        => 'Kategori të papërdorura',
'unusedimages'            => 'Figura të papërdorura',
'popularpages'            => 'Artikuj të frekuentuar shpesh',
'wantedcategories'        => 'Kategori më të dëshiruara',
'wantedpages'             => 'Artikuj më të dëshiruar',
'wantedpages-badtitle'    => 'Titull i pavlefshëm në vendosjen e rezultateve: $1',
'wantedfiles'             => 'Skedat e dëshiruara',
'wantedtemplates'         => 'Stampat e dëshiruara',
'mostlinked'              => 'Artikuj më të lidhur',
'mostlinkedcategories'    => 'Kategori më të lidhura',
'mostlinkedtemplates'     => 'Stampa më të lidhur',
'mostcategories'          => 'Artikuj më të kategorizuar',
'mostimages'              => 'Figura më të lidhura',
'mostrevisions'           => 'Artikuj më të redaktuar',
'prefixindex'             => 'Të gjitha faqet me parashtesa',
'shortpages'              => 'Artikuj të shkurtër',
'longpages'               => 'Artikuj të gjatë',
'deadendpages'            => 'Artikuj pa rrugëdalje',
'deadendpagestext'        => 'Artikujt në vijim nuk kanë asnjë lidhje me artikuj e tjerë në këtë wiki.',
'protectedpages'          => 'Faqe të mbrojtura',
'protectedpages-indef'    => 'Vetëm mbrojtjet pa afat',
'protectedpages-cascade'  => 'Vetëm mbrojtjet',
'protectedpagestext'      => 'Faqet e mëposhtme janë të mbrojtura nga zhvendosja apo redaktimi',
'protectedpagesempty'     => 'Nuk ka faqe të mbrojtura me të dhënat e kërkuara.',
'protectedtitles'         => 'Titujt e mbrojtur',
'protectedtitlestext'     => 'Krijimi i këtyre titujve është i mbrojtur',
'protectedtitlesempty'    => 'Asnjë titull i mbrojtur nuk u gjet në këtë hapësirë.',
'listusers'               => 'Lista e përdoruesve',
'listusers-editsonly'     => 'Trego vetëm përdoruesit me redaktime',
'listusers-creationsort'  => 'Radhiti sipas datës së krijimit',
'usereditcount'           => '$1 {{PLURAL:$1|redaktim|redaktime}}',
'usercreated'             => 'Krijuar më $1 në $2',
'newpages'                => 'Artikuj të rinj',
'newpages-username'       => 'Përdoruesi:',
'ancientpages'            => 'Artikuj më të vjetër',
'move'                    => 'Zhvendose',
'movethispage'            => 'Zhvendose faqen',
'unusedimagestext'        => 'Skedat e mëposhtme ekzistojnë por nuk janë të përdorura në një ndonjë faqe.
Ju lutemi, vini re se faqe të tjera në rrjet si mund të lidhin një figurë me një URL në mënyrë direkte, kështuqë ka mundësi që këto figura të rreshtohen këtu megjithëse janë në përdorim.',
'unusedcategoriestext'    => 'Kategoritë në vazhdim ekzistojnë edhe pse asnjë artikull ose kategori nuk i përdor ato.',
'notargettitle'           => 'Asnjë artikull',
'notargettext'            => 'Nuk keni dhënë asnjë artikull ose përdorues mbi të cilin të përdor këtë funksion.',
'nopagetitle'             => 'Faqja e kërkuar nuk ekziston',
'nopagetext'              => 'Faqja e kërkuar nuk ekziston.',
'pager-newer-n'           => '{{PLURAL:$1|1 më i reja|$1 më të reja}}',
'pager-older-n'           => '{{PLURAL:$1|1 më i vjetër|$1 më të vjetra}}',
'suppress'                => 'Kujdestari',

# Book sources
'booksources'               => 'Burime librash',
'booksources-search-legend' => 'Kërkim burimor librash',
'booksources-go'            => 'Shko',
'booksources-text'          => 'Më posht është një listë me lidhje të cilët shesin ose përdorin libra dhe munden të kenë informacione për librat që kërkoni ju:',
'booksources-invalid-isbn'  => 'ISBN-ja e dhënë nuk duket të jetë e vlefshme; kontrolloni oër gabime gjatë kopjimit nga burimi origjinal.',

# Special:Log
'specialloguserlabel'  => 'Përdoruesi:',
'speciallogtitlelabel' => 'Titulli:',
'log'                  => 'Regjistrat',
'all-logs-page'        => 'Të gjitha regjistrat',
'alllogstext'          => 'Kjo faqe tregon të gjithë regjistrat e mundshëm të {{SITENAME}}.
Ju mund të kufizoni pamje sipas tipit të regjistrit, emrit të përdoruesit (shumë i ndjeshëm), dhe faqes në çështje (edhe rastet e ndjeshme)',
'logempty'             => 'Nuk ka asnjë përputhje në regjistër.',
'log-title-wildcard'   => 'Kërko tituj që fillojnë me këtë tekst',

# Special:AllPages
'allpages'          => 'Të gjitha faqet',
'alphaindexline'    => '$1 deri në $2',
'nextpage'          => 'Faqja më pas ($1)',
'prevpage'          => 'Faqja më parë ($1)',
'allpagesfrom'      => 'Trego faqet duke filluar nga:',
'allpagesto'        => 'Shfaq faqet që mbarojnë në:',
'allarticles'       => 'Të gjithë artikujt',
'allinnamespace'    => 'Të gjitha faqet (hapësira $1)',
'allnotinnamespace' => 'Të gjitha faqet (jo në hapësirën $1)',
'allpagesprev'      => 'Më para',
'allpagesnext'      => 'Më pas',
'allpagessubmit'    => 'Shko',
'allpagesprefix'    => 'Trego faqet me parashtesë:',
'allpagesbadtitle'  => 'Titulli i dhënë ishte i pavlefshë ose kishte një parashtesë ndër-gjuhe ose ndër-wiki.
Mund të përmbajë një ose më shumë karktere të cilat nuk mund të përdoren në tituj.',
'allpages-bad-ns'   => '{{SITENAME}} nuk ka hapësirë "$1".',

# Special:Categories
'categories'                    => 'Kategori',
'categoriespagetext'            => '{{PLURAL:$1|kategoria në vijim përmban|kategoritë në vikim përmbajnë}} faqe ose media.
[[Special:UnusedCategories|Kategoritë e pa përdorura]] nuk janë të paraqitura këtu.
Shikoni edhe [[Special:WantedCategories|kategoritë e dëshiruara]].',
'categoriesfrom'                => 'Paraqit kategoritë duke filluar në:',
'special-categories-sort-count' => 'radhit sipas numrit',
'special-categories-sort-abc'   => 'radhiti sipas alfabetit',

# Special:DeletedContributions
'deletedcontributions'             => 'Kontribute të grisura',
'deletedcontributions-title'       => 'Kontribute të grisura',
'sp-deletedcontributions-contribs' => 'kontributet',

# Special:LinkSearch
'linksearch'       => 'Lidhje të jashtme',
'linksearch-pat'   => 'Motivi kërkimor:',
'linksearch-ns'    => 'Hapësira:',
'linksearch-ok'    => 'Kërko',
'linksearch-text'  => 'Ylli zëvëndësues mund të përdoret si p.sh. "*.wikipedia.org".<br />
Protokolle të mbështetura: <tt>$1</tt>',
'linksearch-line'  => '$1 lidhur nga $2',
'linksearch-error' => 'Ylli mund të përdoret vetëm në fillim të emrit',

# Special:ListUsers
'listusersfrom'      => 'Trego përdoruesit duke filluar prej te:',
'listusers-submit'   => 'Trego',
'listusers-noresult' => "Asnjë përdorues s'u gjet.",
'listusers-blocked'  => '(Bllokuar)',

# Special:ActiveUsers
'activeusers'            => 'Lista e përdoruesve aktivë',
'activeusers-intro'      => 'Kjo është një listë e përdoruesve që kanë qenë aktivë për $ {{PLURAL:$1|ditë|ditë}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|redaktim|redaktime}} në {{PLURAL:$3|ditën|$3 ditët}} e fundit',
'activeusers-from'       => 'Trego përdoruesit duke filluar prej te:',
'activeusers-hidebots'   => 'Fshih robotët',
'activeusers-hidesysops' => 'Fshih administratorët',
'activeusers-noresult'   => 'Asnjë përdorues nuk u gjet.',

# Special:Log/newusers
'newuserlogpage'              => 'Regjistri i llogarive',
'newuserlogpagetext'          => 'Ky është një regjistër i llogarive të fundit që janë hapur',
'newuserlog-byemail'          => 'fjalëkalimi u dërgua në postën elektronike',
'newuserlog-create-entry'     => 'Përdorues i ri',
'newuserlog-create2-entry'    => 'krijoi llofari të re $1',
'newuserlog-autocreate-entry' => 'Llogaria u hap automatikisht',

# Special:ListGroupRights
'listgrouprights'                      => 'Grupime përdoruesish me privilegje',
'listgrouprights-summary'              => 'Më poshtë jepet grupimi i përdoruesve sipas privilegjeve që ju janë dhënë në këtë wiki. Më shumë informacion rreth privilegjeve në veçanti mund të gjendet [[{{MediaWiki:Listgrouprights-helppage}}|këtu]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">E drejtë e garantuar</span>
* <span class="listgrouprights-revoked">E drejtë e revokuar</span>',
'listgrouprights-group'                => 'Grupi',
'listgrouprights-rights'               => 'Privilegjet',
'listgrouprights-helppage'             => 'Help:Grupime privilegjesh',
'listgrouprights-members'              => '(lista e anëtarëve)',
'listgrouprights-addgroup'             => 'Mund të vendosë {{PLURAL:$2|grup|grupe}}: $1',
'listgrouprights-removegroup'          => 'Mund të {{PLURAL:$2|lëvizet grupi|lëvizen grupet}}: $1',
'listgrouprights-addgroup-all'         => 'Mund të vendos të gjitha grupet',
'listgrouprights-removegroup-all'      => 'Mund të largojë të gjitha grupet',
'listgrouprights-addgroup-self'        => 'Shtoni {{PLURAL:$2|grupin|grupet}} tek llogaria: $1',
'listgrouprights-removegroup-self'     => 'Hiqni {{PLURAL:$2|grupin|grupet}} nga llogaria: $1',
'listgrouprights-addgroup-self-all'    => 'Shtoni të gjitha grupet tek llogaria',
'listgrouprights-removegroup-self-all' => 'Hiq të gjitha grupet nga llogaria',

# E-mail user
'mailnologin'          => "S'ka adresë dërgimi",
'mailnologintext'      => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] dhe të keni një adresë të saktë në [[Special:Preferences|parapëlqimet]] tuaja për tu dërguar email përdoruesve të tjerë.',
'emailuser'            => 'Email përdoruesit',
'emailpage'            => 'Dërgo email përdoruesve',
'emailpagetext'        => 'Mund të përdorni formularin e mëposhtëm për të dërguar e-mail tek ky përdorues.
Adresa e email-it që shkruat tek [[Special:Preferences|preferencat tuaja]] do të duket si "Nga" adresa e email-it, pra marrësi do të ketë mundësinë t\'ju përgjigjet direkt.',
'usermailererror'      => 'Objekti postal ktheu gabimin:',
'defemailsubject'      => '{{SITENAME}} email',
'usermaildisabled'     => 'Email-i i përdoruesit çaktivizua',
'usermaildisabledtext' => 'Ju nuk mund të dërgoni e-mail tek përdoruesit e tjerë në këtë wiki',
'noemailtitle'         => "S'ka adresë email-i",
'noemailtext'          => 'Ky përdorues nuk ka përcaktuar një adresë të vlefshme e-mail.',
'nowikiemailtitle'     => 'Nuk lejohet postë elektronike',
'nowikiemailtext'      => 'Ky përdorues ka zgjedhur të mos pranojë porosi elektronike nga përdoruesit tjerë.',
'email-legend'         => 'Dërgoni porosi elektronike një përdoruesi të {{SITENAME}}',
'emailfrom'            => 'Nga:',
'emailto'              => 'Për:',
'emailsubject'         => 'Subjekti:',
'emailmessage'         => 'Porosia:',
'emailsend'            => 'Dërgo',
'emailccme'            => 'Ma dërgo edhe mua një kopje të këtij emaili.',
'emailccsubject'       => 'Kopje e emailit tuaj për $1: $2',
'emailsent'            => 'Email-i u dërgua',
'emailsenttext'        => 'Email-i është dërguar.',
'emailuserfooter'      => 'Kjo porosi elektronike u dërgua nga $1 tek $2 nga "Dërgoi postë elektronike përdoruesit" funksion në {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Lënia e mesazhit të sistemit.',
'usermessage-editor'  => 'I dërguari i sistemit',

# Watchlist
'watchlist'            => 'Lista mbikqyrëse',
'mywatchlist'          => 'Lista mbikqyrëse',
'watchlistfor2'        => 'Për $1 $2',
'nowatchlist'          => 'Nuk keni asnjë faqe në listën mbikqyrëse.',
'watchlistanontext'    => 'Ju lutemi $1 për të parë redaktimet e artikujve në listë tuaj mbikqyrëse.',
'watchnologin'         => 'Nuk keni hyrë brënda',
'watchnologintext'     => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] për të ndryshuar listën mbikqyrëse.',
'addedwatch'           => 'U shtua tek lista mbikqyrëse',
'addedwatchtext'       => "Faqja \"[[:\$1]]\"  i është shtuar [[Special:Watchlist|listës mbikqyrëse]] tuaj. Ndryshimet e ardhshme të kësaj faqeje dhe faqes së diskutimit të saj do të jepen më poshtë, dhe emri i faqes do të duket i '''trashë''' në [[Special:RecentChanges|listën e ndryshimeve së fundmi]] për t'i dalluar më kollaj.

Në qoftë se dëshironi të hiqni një faqe nga lista mbikqyrëse më vonë, shtypni \"çmbikqyre\" në tabelën e sipërme.",
'removedwatch'         => 'U hoq nga lista mibkqyrëse',
'removedwatchtext'     => 'Faqja "[[:$1]]" është hequr nga [[Special:Watchlist|lista mbikqyrëse e juaj]].',
'watch'                => 'Mbikqyre',
'watchthispage'        => 'Mbikqyre këtë faqe',
'unwatch'              => 'Çmbikqyre',
'unwatchthispage'      => 'Mos e mbikqyr',
'notanarticle'         => 'Nuk është artikull',
'notvisiblerev'        => 'Revizioni është grisur',
'watchnochange'        => 'Asnjë nga artikujt nën mbikqyrje nuk është redaktuar gjatë kohës së dhënë.',
'watchlist-details'    => '{{PLURAL:$1|$1 faqe|$1 faqe}} nën mbikqyrje duke mos numëruar faqet e diskutimit.',
'wlheader-enotif'      => '* Njoftimi me email është lejuar.',
'wlheader-showupdated' => "* Faqet që kanë ndryshuar nga vizita juaj e fundit do të tregohen të '''trasha'''",
'watchmethod-recent'   => 'duke parë ndryshimet e fundit për faqet nën mbikqyrje',
'watchmethod-list'     => 'duke parë faqet nën mbikqyrje për ndryshimet e fundit',
'watchlistcontains'    => 'Lista mbikqyrëse e juaj ka $1 {{PLURAL:$1|faqe|faqe}}.',
'iteminvalidname'      => "Problem me artikullin '$1', titull jo i saktë...",
'wlnote'               => "Më poshtë {{PLURAL:$1|është $1 ndryshim i|janë $1 ndryshimet e}} {{PLURAL:$2|orës së fundit|'''$2''' orëve të fundit}}.",
'wlshowlast'           => 'Trego $1 orët $2 ditët $3',
'watchlist-options'    => 'Mundësitë e listës mbikqyrëse',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Duke mbikqyrur...',
'unwatching' => 'Duke çmbikqyrur...',

'enotif_mailer'                => 'Postieri Njoftues i {{SITENAME}}',
'enotif_reset'                 => 'Shëno të gjitha faqet e vizituara',
'enotif_newpagetext'           => 'Kjo është një faqe e re.',
'enotif_impersonal_salutation' => 'Përdorues i {{SITENAME}}',
'changed'                      => 'ndryshuar',
'created'                      => 'u krijua',
'enotif_subject'               => '{{SITENAME}} faqja $PAGETITLE u $CHANGEDORCREATED prej $PAGEEDITOR',
'enotif_lastvisited'           => 'Shikoni $1 për të gjitha ndryshimet që prej vizitës tuaj të fundit.',
'enotif_lastdiff'              => 'Shikoni $1 për ndryshime.',
'enotif_anon_editor'           => 'përdorues anonim $1',
'enotif_body'                  => 'I/E dashur $WATCHINGUSERNAME,

Faqja $PAGETITLE tek {{SITENAME}} është $CHANGEDORCREATED më $PAGEEDITDATE nga $PAGEEDITOR, shikoni $PAGETITLE_URL për versionin e tanishëm.

$NEWPAGE

Përmbledhja e redaktorit: $PAGESUMMARY $PAGEMINOREDIT

Mund të lidheni me redaktorin nëpërmjet:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nuk do të ketë njoftime të tjera për ndryshimet e ardhshme përveç nëse e vizitoni faqen. Gjithashtu mund të ktheni gjendjen e njoftimeve për të gjitha faqet nën mbikqyrje.

             Miku juaj njoftues nga {{SITENAME}}

--
Për të ndryshuar parapëlqimet e mbikqyrjes shikoni {{fullurl:Special:Watchlist/edit}}

Për të larguar faqen nga lista juaj mbikqyrëse, shikoni 
$UNWATCHURL

Për të na dhënë përshtypjet tuaja ose për ndihmë të mëtejshme:
{{fullurl:{{MediaWiki:Helpage}}}}',

# Delete
'deletepage'             => 'Grise faqen',
'confirm'                => 'Konfirmoni',
'excontent'              => "përmbajtja ishte: '$1'",
'excontentauthor'        => "përmbajtja ishte: '$1' (dhe i vetmi redaktor ishte '$2')",
'exbeforeblank'          => "përmbajtja para boshatisjes ishte: '$1'",
'exblank'                => 'faqja është bosh',
'delete-confirm'         => 'Grise "$1"',
'delete-legend'          => 'Grise',
'historywarning'         => "'''Kujdes:''' Kjo faqe të cilën po e grisni ka histori me rreth $1 
{{PLURAL:$1|version|redaktime}}:",
'confirmdeletetext'      => 'Jeni duke grisur një faqe me tërë historinë e saj. Ju lutemi konfirmoni që po e bëni qëllimisht, që i kuptoni pasojat, dhe që po veproni në përputhje me [[{{MediaWiki:Policy-url}}]].',
'actioncomplete'         => 'Veprimi u krye',
'actionfailed'           => 'Veprimi dështoi',
'deletedtext'            => '"<nowiki>$1</nowiki>" është grisur nga regjistri. Shikoni $2 për një pasqyrë të grisjeve së fundmi.',
'deletedarticle'         => 'u gris "[[$1]]"',
'suppressedarticle'      => '"[[$1]]" i shtypur',
'dellogpage'             => 'Regjistri i grisjeve',
'dellogpagetext'         => 'Më poshtë është një listë e grisjeve më të fundit.',
'deletionlog'            => 'regjistrin e grisjeve',
'reverted'               => 'Kthehu tek një version i vjetër',
'deletecomment'          => 'Arsyeja:',
'deleteotherreason'      => 'Arsye tjetër:',
'deletereasonotherlist'  => 'Arsyeja tjetër',
'deletereason-dropdown'  => '*Arsye për grisje:
** Pa të drejtë autori
** Kërkesë nga autori
** Vandalizëm',
'delete-edit-reasonlist' => 'Ndrysho arsyet e grisjes',
'delete-toobig'          => 'Kjo faqe ka një historik të madh redaktimesh, më shumë se $1 {{PLURAL:$1|version|versione}}.
Grisja e faqeve të tilla ka qenë kufizuar për të parandaluar përçarjen aksidentale të {{SITENAME}}.',
'delete-warning-toobig'  => 'Kjo faqe ka një historik të madh redaktimesh, më shumë se $1 {{PLURAL:$1|version|versione}}.
Grisja e saj mund të ndërpresë operacionet e bazës së të dhënave të {{SITENAME}};
vazhdoni me kujdes.',

# Rollback
'rollback'          => 'Riktheji mbrapsh redaktimet',
'rollback_short'    => 'Riktheje',
'rollbacklink'      => 'riktheje',
'rollbackfailed'    => 'Rikthimi dështoi',
'cantrollback'      => 'Redaktimi nuk mund të kthehej;
redaktori i fundit është i vetmi autor i këtij artikulli.',
'alreadyrolled'     => 'Nuk mund të rikthehej redaktimi i fundit i [[:$1]] nga [[User:$2|$2]] ([[User talk:$2|diskuto]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); dikush tjetër e ka redaktuar ose rikthyer këtë faqe tashmë.

Redaktimi i fundit është bërë nga [[User:$3|$3]] ([[User talk:$3|diskuto]]{{nt:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "Përmbledhja e redaktimit ishte: \"''\$1''\".",
'revertpage'        => 'Ndryshimet e [[Special:Contributions/$2|$2]] ([[User talk:$2|diskutimet]]) u kthyen mbrapsht, artikulli tani ndodhet në versionin e fundit nga [[User:$1|$1]].',
'revertpage-nouser' => 'U rikthyen redaktimet nga (përdoruesi i larguar) në versionin e fundit nga [[User:$1|$1]]',
'rollback-success'  => 'Ndryshimet e $1 u kthyen mbrapsh; artikulli ndodhet tek verzioni i $2.',

# Edit tokens
'sessionfailure-title' => 'Dështim sesioni',
'sessionfailure'       => 'Duket se ka një problem me seancën tuaj hyrëse; ky veprim është anuluar për tu mbrojtur nga ndonjë veprim dashakeq kundrejt shfletimit tuaj. Ju lutemi kthehuni mbrapsh, rifreskoni faqen prej nga erdhët dhe provojeni përsëri veprimin.',

# Protect
'protectlogpage'              => 'Regjistri i mbrojtjeve',
'protectlogtext'              => 'Më poshtë është lista e kyçjeve dhe çkyçjeve të faqes.
Shih listën e [[Special:ProtectedPages|faqeve të mbrojtura]] nga lista e mbrojtjeve të faqeve tani në veprim.',
'protectedarticle'            => 'u mbrojt [[$1]]',
'modifiedarticleprotection'   => 'u ndryshua mbrojtja e faqes "[[$1]]"',
'unprotectedarticle'          => 'u lirua [[$1]]',
'movedarticleprotection'      => 'u bartën të dhënat e mbrojtjes nga "[[$2]]" në "[[$1]]"',
'protect-title'               => 'Ndryshoni nivelin e mbrojtjes së "$1"',
'prot_1movedto2'              => '[[$1]] u zhvendos tek [[$2]]',
'protect-legend'              => 'Konfirmoni',
'protectcomment'              => 'Arsyeja:',
'protectexpiry'               => 'Afati',
'protect_expiry_invalid'      => 'Data e skadimit është e gabuar.',
'protect_expiry_old'          => 'Data e skadencës është në të shkuarën.',
'protect-unchain-permissions' => 'Zhbllokoni opsionet e mëtejshme të mbrojtjes',
'protect-text'                => "Këtu mund të shikoni dhe ndryshoni nivelin e mbrojtjes për faqen '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Nuk mund të ndryshoni nivelet e mbrojtjes duke qenë i bllokuar. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-locked-dblock'       => "Nivelet e mbrojtjes nuk mund të ndryshohen pasi regjistri është i bllokuar. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-locked-access'       => "Llogaria juaj nuk ka privilegjet e nevojitura për të ndryshuar nivelin e mbrojtjes. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-cascadeon'           => 'Kjo faqe është e mbrojtur pasi është përfshirë {{PLURAL:$1|këtë faqe që është|këto faqe që janë}} nën mbrojtje "ujëvarë".
Mund të ndryshoni nivelin e mbrojtjes të kësaj faqeje por kjo nuk do të ndryshojë mbrojtjen "ujëvarë".',
'protect-default'             => 'Lejoni të gjithë përdoruesit',
'protect-fallback'            => 'Kërko leje "$1"',
'protect-level-autoconfirmed' => 'Blloko përdoruesit e rinj dhe ata pa llogari',
'protect-level-sysop'         => 'Lejo vetëm administruesit',
'protect-summary-cascade'     => 'të varura',
'protect-expiring'            => 'skadon me $1 (UTC)',
'protect-expiry-indefinite'   => 'i pacaktuar',
'protect-cascade'             => 'Mbrojtje e ndërlidhur - mbro çdo faqe që përfshihet në këtë faqe.',
'protect-cantedit'            => 'Nuk mund ta ndryshoni nivelin e mbrojtjes të kësaj faqeje sepse nuk keni leje për këtë.',
'protect-othertime'           => 'Kohë tjetër:',
'protect-othertime-op'        => 'kohë tjetër',
'protect-existing-expiry'     => 'Koha ekzistuese e skadimit: $3, $2',
'protect-otherreason'         => 'Arsye tjera/shtesë:',
'protect-otherreason-op'      => 'Arsyeja tjetër',
'protect-dropdown'            => '*Arsyet e përbashkëta të mbrojtjes
**Vandalizëm i tepërt
**Spam i tepërt
**Counter-productive edit warning
**Faqe e lartë trafiku',
'protect-edit-reasonlist'     => 'Redakto arsyet e mbrojtjes',
'protect-expiry-options'      => '1 Orë:1 hour,1 Ditë:1 day,1 Javë:1 week,2 Javë:2 weeks,1 Muaj:1 month,3 Muaj:3 months,6 Muaj:6 months,1 Vjet:1 year,Pa kufi:infinite',
'restriction-type'            => 'Lejet:',
'restriction-level'           => 'Mbrojtjet:',
'minimum-size'                => 'Madhësia minimale',
'maximum-size'                => 'Madhësia maksimale',
'pagesize'                    => '(B)',

# Restrictions (nouns)
'restriction-edit'   => 'Redaktimi',
'restriction-move'   => 'Zhvendosja',
'restriction-create' => 'Krijo',
'restriction-upload' => 'Ngarko',

# Restriction levels
'restriction-level-sysop'         => 'mbrojtje e plotë',
'restriction-level-autoconfirmed' => 'gjysëm mbrojtje',
'restriction-level-all'           => 'çdo nivel',

# Undelete
'undelete'                     => 'Restauroni faqet e grisura',
'undeletepage'                 => 'Shikoni ose restauroni faqet e grisura',
'undeletepagetitle'            => "'''Në vazhdim janë versionet e grisura të [[:$1|$1]]'''.",
'viewdeletedpage'              => 'Shikoni faqet e grisura',
'undeletepagetext'             => '{{PLURAL:$1|Faqja në vazhdim është grisur, por akoma është|$1 Faqet në vazhdim janë grisur, por akoma janë}} në arkiv dhe mund të rikthehen.
Arkivi, kohëpaskohe është e mundur të pastrohet.',
'undelete-fieldset-title'      => 'Rikthe revizionet',
'undeleteextrahelp'            => "Për të restauruar të gjithë historikun e faqes, lërini të gjitha kutizat të paselektuara dhe klikoni
'''''{{int:undeletebtn}}'''''.
Për të bërë një restaurim të pjesshëm zgjidhni kutizat koresponduese të versioneve që doni të restauroni dhe klikoni '''''{{int:undeletebtn}}'''''.
Klikimi i '''''{{int:undeletereset}}''''' do të pastrojë fushat e komenteve dhe kutizat.",
'undeleterevisions'            => '$1 {{PLURAL:$1|version u fut|versione u futën}} në arkiv',
'undeletehistory'              => 'Nëse restauroni një faqe, të gjitha versionet do të restaurohen në histori.
Nëse një faqe e re me të njëjtin titull është krijuar pas grisjes, versionet e restauruara do të paraqiten më mbrapa në histori.',
'undeleterevdel'               => 'Restaurimi nuk do të performohet n.q.s. do të rezultojë në majë të versioneve të faqes apo skedës duke u grisur pjesërisht.
Në raste të tilla, ju duhet të çzgjidhni ose shfaqni versionet më të reja të grisura.',
'undeletehistorynoadmin'       => 'Kjo faqe është grisur. Arsyeja për grisjen është dhënë tek përmbledhja më poshtë bashkë me hollësitë e përdoruesve që e kanë redaktuar.',
'undelete-revision'            => 'Revizioni i grisur i $1 (nga $4, në $5) nga $3:',
'undeleterevision-missing'     => 'Version i humbur ose i pavlefshëm.
Ju mund të keni një lidhje të keqe, ose versioni mund të jetë restauruar ose larguar nga arkivi.',
'undelete-nodiff'              => 'Nuk u gjetën revizione të mëparshme.',
'undeletebtn'                  => 'Restauro!',
'undeletelink'                 => 'shiko/rikthe',
'undeleteviewlink'             => 'Pamje',
'undeletereset'                => 'Boshatis',
'undeleteinvert'               => 'Selektim anasjelltas',
'undeletecomment'              => 'Arsyeja:',
'undeletedarticle'             => 'u restaurua "$1"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|version u restaurua|versione u restauruan}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|version|versione}} dhe $2 {{PLURAL:$2|skedë|skeda}} janë restauruar',
'undeletedfiles'               => '$1 {{PLURAL:$1|skedë u restaurua|skeda u restauruan}}',
'cannotundelete'               => 'Restaurimi dështoi; dikush tjetër mund ta ketë restauruar faqen para jush.',
'undeletedpage'                => "'''$1 është restauruar'''

Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për grisjet dhe restaurimet së fundmi.",
'undelete-header'              => 'Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për faqet e grisura së fundmi.',
'undelete-search-box'          => 'Kërko faqet e grisura',
'undelete-search-prefix'       => 'Trego faqet duke filluar nga:',
'undelete-search-submit'       => 'Kërko',
'undelete-no-results'          => 'Nuk u gjet asnjë faqe përputhëse tek arkivi i grisjeve.',
'undelete-filename-mismatch'   => 'Nuk mund të restauroni skeda me timestamp $1: filename mismatch',
'undelete-bad-store-key'       => 'Nuk mund të restauroni versionin e skedës me timestamp $1: skeda mungonte para grisjes.',
'undelete-cleanup-error'       => 'Gabim në grisjen e skedës "$1" të pa përdorur të arkivit .',
'undelete-missing-filearchive' => 'Nuk mund të restaurohet arkivi ID i skedës $1 sepse nuk është në bazën të dhënave.
Mund të jetë restauruar një herë.',
'undelete-error-short'         => 'Gabim në rikthimin e skedës: $1',
'undelete-error-long'          => 'U hasën gabime gjatë restaurimit të skedës:

$1',
'undelete-show-file-confirm'   => 'Jeni të sigurt se dëshironi të shihni redaktimin e grisur të skedës "<nowiki>$1</nowiki>" nga $2 në $3?',
'undelete-show-file-submit'    => 'Po',

# Namespace form on various pages
'namespace'      => 'Hapësira:',
'invert'         => 'Kundër zgjedhjes',
'blanknamespace' => '(Artikujt)',

# Contributions
'contributions'       => 'Kontributet',
'contributions-title' => 'Kontributet e përdoruesit për $1',
'mycontris'           => 'Redaktimet e mia',
'contribsub2'         => 'Për $1 ($2)',
'nocontribs'          => 'Nuk ka asnjë ndryshim që përputhet me këto kritere.',
'uctop'               => ' (sipër)',
'month'               => 'Nga muaji (dhe më herët):',
'year'                => 'Nga viti (dhe më herët):',

'sp-contributions-newbies'             => 'Trego vetëm redaktimet e llogarive të reja',
'sp-contributions-newbies-sub'         => 'Për newbies',
'sp-contributions-newbies-title'       => 'Kontributet e përdoruesit për kontot e reja',
'sp-contributions-blocklog'            => 'Regjistri i bllokimeve',
'sp-contributions-deleted'             => 'kontributet e grisura',
'sp-contributions-uploads'             => 'ngarkimet',
'sp-contributions-logs'                => 'Regjistrat',
'sp-contributions-talk'                => 'Diskutoni',
'sp-contributions-userrights'          => 'menaxhimi i të drejtave të përdoruesit',
'sp-contributions-blocked-notice'      => 'Ky përdorues është i bllokuar.
Bllokimi i fundit është shfaqur më poshtë për referencë:',
'sp-contributions-blocked-notice-anon' => 'Kjo adresë IP është e bllokuar aktualisht.
Bllokimi i funditë është më poshtë për referencë:',
'sp-contributions-search'              => 'Kërko tek kontributet',
'sp-contributions-username'            => 'IP Addresa ose Përdoruesi:',
'sp-contributions-toponly'             => 'Trego vetëm redaktimet që janë versionet më të fundit',
'sp-contributions-submit'              => 'Kërko',

# What links here
'whatlinkshere'            => 'Lidhjet këtu',
'whatlinkshere-title'      => 'Faqe që lidhen tek $1',
'whatlinkshere-page'       => 'Faqja:',
'linkshere'                => "Faqet e mëposhtme lidhen këtu '''[[:$1]]''':",
'nolinkshere'              => "Asnjë faqe nuk lidhet tek '''[[:$1]]'''.",
'nolinkshere-ns'           => "Nuk ka faqe në hapësirën e zgjedhur që lidhen tek '''[[:$1]]'''.",
'isredirect'               => 'faqe përcjellëse',
'istemplate'               => 'përfshirë',
'isimage'                  => 'lidhje figure',
'whatlinkshere-prev'       => '{{PLURAL:$1|e kaluara|të kaluarat $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|tjetra|tjerat $1}}',
'whatlinkshere-links'      => '← lidhje',
'whatlinkshere-hideredirs' => '$1 përcjellimet',
'whatlinkshere-hidetrans'  => '$1 përfshirjet',
'whatlinkshere-hidelinks'  => '$1 lidhjet',
'whatlinkshere-hideimages' => '$1 lidhjet e figurave',
'whatlinkshere-filters'    => 'Filtra',

# Block/unblock
'blockip'                         => 'Blloko përdorues',
'blockip-title'                   => 'Përdorues i Bllokuar',
'blockip-legend'                  => 'Blloko përdoruesin',
'blockiptext'                     => 'Përdorni formularin e mëposhtëm për të hequr lejen e shkrimit për një përdorues ose IP specifike.
Kjo duhet bërë vetëm në raste vandalizmi, dhe në përputhje me [[{{MediaWiki:Policy-url}}|rregullat e {{SITENAME}}-s]].
Plotësoni arsyen specifike më poshtë (p.sh., tregoni faqet specifike që u vandalizuan).',
'ipaddress'                       => 'IP Adresë/përdorues',
'ipadressorusername'              => 'Adresë IP ose emër përdoruesi',
'ipbexpiry'                       => 'Afati',
'ipbreason'                       => 'Arsyeja:',
'ipbreasonotherlist'              => 'Arsye tjetër',
'ipbreason-dropdown'              => '*Arsyet më të shpeshta të bllokimit
** Postimi i informacioneve të rreme
** Largimi i përmbajtjes së faqes
** Futja e lidhjeve "spam"
** Futja e informatave pa kuptim në faqe
** Sjellje arrogante/perverze
** Përdorimi i më shumë llogarive të përdoruesve
** Nofkë të papranueshme',
'ipbanononly'                     => 'Blloko vetëm përdoruesin anonim',
'ipbcreateaccount'                => 'Mbroje krijimin e llogarive',
'ipbemailban'                     => 'Pa mundëso dërgimin  e porosive elektronike nga përdoruesit',
'ipbenableautoblock'              => 'Blloko edhe IP adresën që ka përdor ky përdorues deri tash, si dhe të gjitha subadresat nga të cilat mundohet ky përdorues të editoj.',
'ipbsubmit'                       => 'Blloko këtë përdorues',
'ipbother'                        => 'Kohë tjetër',
'ipboptions'                      => '2 Orë:2 hours,1 Ditë:1 day,3 Ditë:3 days,1 Javë:1 week,2 Javë:2 weeks,1 Muaj:1 month,3 Muaj:3 months,6 Muaj:6 months,1 Vjet:1 year,Pa kufi:infinite',
'ipbotheroption'                  => 'tjetër',
'ipbotherreason'                  => 'Arsye tjetër/shtesë',
'ipbhidename'                     => 'Fshih emrat e përdorueseve nga redaktimet dhe listat',
'ipbwatchuser'                    => 'Shiko faqen e prezantimit dhe diskutimit të këtij përdoruesi',
'ipballowusertalk'                => 'Lejoni këtë përdorues të redaktojë faqen e diskutimit të tij kur është i bllokuar',
'ipb-change-block'                => 'Ri-blloko përdorues me këta parametra',
'badipaddress'                    => 'Nuk ka asnjë përdorues me atë emër',
'blockipsuccesssub'               => 'Bllokimi u bë me sukses',
'blockipsuccesstext'              => 'Përdoruesi/IP-Adresa [[Special:Contributions/$1|$1]] u bllokua.<br />
Shiko te [[Special:IPBlockList|Lista e përdoruesve dhe e IP adresave të bllokuara]] për të çbllokuar Përdorues/IP.',
'ipb-edit-dropdown'               => 'Redakto arsyet e bllokimit',
'ipb-unblock-addr'                => 'Çblloko $1',
'ipb-unblock'                     => 'Çblloko përdorues dhe IP të bllokuara',
'ipb-blocklist'                   => 'Përdorues dhe IP adresa të bllokuara',
'ipb-blocklist-contribs'          => 'Kontributet për $1',
'unblockip'                       => 'Çblloko përdoruesin',
'unblockiptext'                   => "Përdor formularin e më poshtëm për t'i ridhënë leje shkrimi
një përdoruesi ose IP adreseje të bllokuar.",
'ipusubmit'                       => 'Hiqni këtë bllokim',
'unblocked'                       => '[[User:$1|$1]] është çbllokuar',
'unblocked-id'                    => 'Bllokimi $1 është hequr',
'ipblocklist'                     => 'Lista e përdoruesve dhe e IP adresave të bllokuara',
'ipblocklist-legend'              => 'Gjej një përdorues të bllokuar',
'ipblocklist-username'            => 'Adresa IP ose nofka e përdoruesit:',
'ipblocklist-sh-userblocks'       => '$1 bllokimet e llogarisë',
'ipblocklist-sh-tempblocks'       => '$1 bllokimet e përkohshme',
'ipblocklist-sh-addressblocks'    => '$1 bllokimet e IP',
'ipblocklist-submit'              => 'Kërko',
'ipblocklist-localblock'          => 'Bllokim lokal',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Bllokim tjetër|Bllokime të tjera}}',
'blocklistline'                   => '$1, $2 bllokoi $3 ($4)',
'infiniteblock'                   => 'pakufi',
'expiringblock'                   => 'skadon më $1 në $2',
'anononlyblock'                   => 'vetëm anonimët',
'noautoblockblock'                => 'autobllokimi është çaktivizuar',
'createaccountblock'              => 'hapja e lloggarive është bllokuar',
'emailblock'                      => 'email është bllokuar',
'blocklist-nousertalk'            => 'nuk mund të editohet faqja personale e diskutimit',
'ipblocklist-empty'               => 'Lista e të bllokimeve është e zbrazët.',
'ipblocklist-no-results'          => 'Adresa IP ose përdoruesi i kërkuar nuk është i bllokuar.',
'blocklink'                       => 'blloko',
'unblocklink'                     => 'çblloko',
'change-blocklink'                => 'ndryshoje bllokun',
'contribslink'                    => 'kontribute',
'autoblocker'                     => 'Bllokuar automatikisht sepse adresa juaj IP është përdorur së fundmi nga "[[User:$1|$1]]".
Arsyeja e dhënë për bllokimin e $1 është: "$2"',
'blocklogpage'                    => 'Regjistri i bllokimeve',
'blocklog-showlog'                => 'Ky përdorues ka qenë bllokuar më parë.
Regjistri i bllokimeve është poshtë për referncë:',
'blocklog-showsuppresslog'        => 'Ky përdorues ka qenë i bllokuar dhe i fshehur më parë.
Regjistri i bllokimeve është poshtë për referncë:',
'blocklogentry'                   => 'bllokoi [[$1]] për një kohë prej: $2 $3',
'reblock-logentry'                => 'ndryshoi parametrat e bllokimit për [[$1]] me një kohë prej $2 $3',
'blocklogtext'                    => 'Ky është një regjistër bllokimesh dhe çbllokimesh të përdoruesve. IP-të e bllokuara automatikisht nuk janë të dhëna. Shikoni dhe [[Special:IPBlockList|listën e IP-ve të bllokuara]] për një listë të bllokimeve të tanishme.',
'unblocklogentry'                 => 'çbllokoi "$1"',
'block-log-flags-anononly'        => 'vetëm anonimët',
'block-log-flags-nocreate'        => 'krijimi i kontove është pamundësuar',
'block-log-flags-noautoblock'     => 'vetëbllokimi është pamundësuar',
'block-log-flags-noemail'         => 'posta elektronike është e bllokuar',
'block-log-flags-nousertalk'      => 'nuk mund të redaktojë faqen e tij të diskutimit',
'block-log-flags-angry-autoblock' => 'Autobllokimi i zgjeruar u aktivizua',
'block-log-flags-hiddenname'      => 'emri i përdoruesit i fshehur',
'range_block_disabled'            => 'Mundësia e administruesve për të bllokuar me shtrirje është çaktivizuar.',
'ipb_expiry_invalid'              => 'Afati i kohës është gabim.',
'ipb_expiry_temp'                 => 'Bllokimet e përdoruesve të fshehur duhet të jenë të përhershme.',
'ipb_hide_invalid'                => 'Nuk mund ta prishni këtë llogari; mund të ketë shumë redaktime.',
'ipb_already_blocked'             => '"$1" është i bllokuar',
'ipb-needreblock'                 => "== I bllokuar ==
$1 është i bllokuar.
Dëshironi t'i ndryshoni parametrat?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Bllokim tjetër|Bllokime të tjera}}',
'ipb_cant_unblock'                => 'Gabim: Bllokimi ID $1 nuk u gjet.
Mund të jetë zhbllokuar.',
'ipb_blocked_as_range'            => 'Gabim: Adresa IP $1 nuk është bllokuar direkt dhe nuk mund të zhbllokohet.
Ajo është, megjithatë, e bllokuar si pjesë e rangut $2, që nuk mund të zhbllokohet.',
'ip_range_invalid'                => 'Shtrirje IP gabim.',
'ip_range_toolarge'               => 'Radhitja e bllokimeve më të mëdha se /$1 nuk lejohet.',
'blockme'                         => 'Më blloko',
'proxyblocker'                    => 'Bllokuesi i ndërmjetëseve',
'proxyblocker-disabled'           => 'Ky funksion është pamundësuar.',
'proxyblockreason'                => 'IP adresa juaj është bllokuar sepse është një ndërmjetëse e hapur. Ju lutem lidhuni me kompaninë e shërbimeve të Internetit që përdorni dhe i informoni për këtë problem sigurije.',
'proxyblocksuccess'               => 'Mbaruar.',
'sorbsreason'                     => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL.',
'sorbs_create_account_reason'     => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL që përdoret nga {{SITENAME}}. Nuk ju lejohet të hapni një llogari.',
'cant-block-while-blocked'        => 'Ju nuk mund të bllokoni përdorues të tjerë ndërkohë që jeni i bllokuar.',
'cant-see-hidden-user'            => 'Përdoruesi që po përpiqeni të bllokoni është i bllokuar dhe i fshehur.
Përderisa ju nuk keni të drejtën e fshehjes së përdoruesve, ju nuk mund të shikoni ose redaktoni bllokimet e përdoruesit.',
'ipbblocked'                      => 'Ju nuk mund të bllokoni ose zhbllokoni përdoruesit e tjerë, sepse jeni për vete i bllokuar',
'ipbnounblockself'                => 'Ju nuk mund të zhbllokoni veten tuaj',

# Developer tools
'lockdb'              => 'Blloko regjistrin',
'unlockdb'            => 'Çblloko regjistrin',
'lockdbtext'          => 'Bllokimi i regjistrit do të ndërpresi mundësinë e përdoruesve për të redaktuar faqet, për të ndryshuar parapëlqimet, për të ndryshuar listat mbikqyrëse të tyre, dhe për gjëra të tjera për të cilat nevojiten shkrime në regjistër.
Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim, dhe se do të çbllokoni regjistrin
kur të mbaroni së kryeri mirëmbajtjen.',
'unlockdbtext'        => 'Çbllokimi i regjistrit do të lejojë mundësinë e të gjithë përdoruesve për të redaktuar faqe, për të ndryshuar parapëlqimet e tyre, për të ndryshuar listat mbikqyrëse të tyre, dhe gjëra të tjera për të cilat nevojiten shkrime në regjistër. Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim.',
'lockconfirm'         => 'Po, dëshiroj me të vërtetë të bllokoj regjistrin.',
'unlockconfirm'       => 'Po, dëshiroj me të vërtetë të çbllokoj regjistrin',
'lockbtn'             => 'Blloko regjistrin',
'unlockbtn'           => 'Çblloko regjistrin',
'locknoconfirm'       => 'Nuk vendose kryqin tek kutia konfirmuese.',
'lockdbsuccesssub'    => 'Regjistri u bllokua me sukses',
'unlockdbsuccesssub'  => 'Regjistri u çbllokua me sukses',
'lockdbsuccesstext'   => 'Regjistri është bllokuar.<br />
Kujtohuni ta [[Special:UnlockDB|çbllokoni]] pasi të keni mbaruar mirëmbajtjen.',
'unlockdbsuccesstext' => 'Regjistri i {{SITENAME}} është çbllokuar.',
'lockfilenotwritable' => "Skeda për bllokimin e regjistrit s'mund të shkruhet.
Shërbyesi i rrjetit duhet të jetë në gjendje të shkruaj këtë skedë për të bllokuar ose çbllokuar regjistrin.",
'databasenotlocked'   => 'Regjistri nuk është bllokuar.',

# Move page
'move-page'                    => 'Zhvendose $1',
'move-page-legend'             => 'Zhvendose faqen',
'movepagetext'                 => "Duke përdorur formularin e mëposhtëm do të ndërroni titullin e një faqeje, duke zhvendosur gjithë historkun e saj tek titulli i ri.
Titulli i vjetër do të bëhet një faqe ridrejtuese tek titulli i ri.
Lidhjet tek faqja e vjetër nuk do të ndryshohen;
duhet të kontrolloni mirëmbajtjen për përcjellime të [[Special:DoubleRedirects|dyfishta]] ose të [[Special:BrokenRedirects|prishura]].
Keni përgjegjësinë për tu siguruar që lidhjet të vazhdojnë të jenë të sakta.

Kini parasysh se kjo faqe '''nuk''' do të zhvendoset n.q.s. ekziston një faqe me titullin e ri, përveçse nëse ajo është bosh ose një përcjellim dhe nuk ka historik të redaktimeve.
Kjo do të thotë se mund ta zhvendosni një faqe prapë tek emri i vjetër n.q.s. keni bërë një gabim, dhe s'mund ta prishësh një faqe që ekziston.

'''KUJDES!'''
Ky mund të jetë një ndryshim i madh dhe i papritur për një faqe të shumë-frekuentuar; ju lutem, kini kujdes dhe mendohuni mirë para se të përdorni këtë funksion.",
'movepagetext-noredirectfixer' => "Duke përdorur formularin e mëposhtëm do të ndërroni titullin e një faqeje, duke zhvendosur gjithë historinë përkatëse tek titulli i ri.
Titulli i vjetër do të bëhet një faqe përcjellëse tek titulli i ri.
Lidhjet tek faqja e vjetër nuk do të ndryshohen;
duhet të kontrolloni mirëmbajtjen për përcjellime të [[Special:DoubleRedirects|dyfishta]] ose të [[Special:BrokenRedirects|prishura]].
Keni përgjegjësinë për tu siguruar që lidhjet të vazhdojnë të jenë të sakta.

Vini re se kjo faqe '''nuk''' do të zhvendoset n.q.s. ekziston një faqe me titullin e ri, përveçse kur ajo të jetë bosh ose një përcjellim dhe të mos ketë një histori të vjetër.
Kjo do të thotë se mund ta zhvendosni një faqe prapë tek emri i vjetër n.q.s. keni bërë një gabim, dhe s'mund ta prishësh një faqe që ekziston.

'''KUJDES!'''
Ky mund të jetë një ndryshim i madh dhe gjëra të papritura mund të ndodhin për një faqe të shumë-frekuentuar; ju lutem, kini kujdes dhe mendohuni mirë para se të përdorni këtë funksion.",
'movepagetalktext'             => "Faqja a bashkangjitur e diskutimit, n.q.s. ekziston, do të zhvendoset automatikisht '''përveçse''' kur:
*Zhvendosni një faqe midis hapësirave të ndryshme,
*Një faqe diskutimi jo-boshe ekziston nën titullin e ri, ose
*Nuk zgjidhni kutinë më poshtë.

Në ato raste, duhet ta zhvendosni ose përpuqni faqen vetë n.q.s. dëshironi.",
'movearticle'                  => 'Zhvendose faqen',
'moveuserpage-warning'         => "'''Kujdes:''' Ju po zhvendosni një faqe përdoruesi. Ju lutemi, kujtoni se vetëm faqja do të zhvendoset dhe përdoruesi ''nuk'' do të ndryshojë emrin.",
'movenologin'                  => 'Nuk keni hyrë brenda',
'movenologintext'              => 'Duhet të keni hapur një llogari dhe të keni [[Special:UserLogin|hyrë brenda]] për të zhvendosur një faqe.',
'movenotallowed'               => 'Nuk ju lejohet të zhvendosni faqe.',
'movenotallowedfile'           => 'Nuk keni leje për të lëvizur skeda.',
'cant-move-user-page'          => 'Ju nuk keni të drejat për të lzhvendosur faqet e përdoruesve (përveç nën-faqeve).',
'cant-move-to-user-page'       => 'Ju nuk keni të drejta për të zhvendosur një faqe tek një faqe përdoruesi (përvç tek një nën-faqe përdoruesi).',
'newtitle'                     => 'Tek titulli i ri',
'move-watch'                   => 'Mbikqyre këtë faqe',
'movepagebtn'                  => 'Zhvendose faqen',
'pagemovedsub'                 => 'Zhvendosja doli me sukses',
'movepage-moved'               => '\'\'\'"$1" u zhvendos tek "$2"\'\'\'',
'movepage-moved-redirect'      => 'Një përcjellim është krijuar.',
'movepage-moved-noredirect'    => 'Krijimi i një përcjellimi është prishur.',
'articleexists'                => 'Një faqe me atë titull ekziston, ose titulli që zgjodhët nuk është i saktë. Ju lutem zgjidhni një tjetër.',
'cantmove-titleprotected'      => 'Nuk mund të zhvendosni një faqe në këtë titull pasi ky titull është mbrojtur kundrejt krijimit',
'talkexists'                   => 'Faqja për vete u zhvendos, ndërsa faqja e diskutimit nuk u zhvendos sepse një e tillë ekziston tek titulli i ri. Ju lutem, përpuqini vetë.',
'movedto'                      => 'zhvendosur tek',
'movetalk'                     => 'Zhvendos edhe faqen e diskutimeve, në qoftë se është e mundur.',
'move-subpages'                => 'Zhvendosni nën-faqet (deri në $1)',
'move-talk-subpages'           => 'Zhvendosni nën-faqet e faqes së diskutimit (deri në $1)',
'movepage-page-exists'         => "Faqja $1 ekziston prandaj s'mund ta mbivendos automatikisht",
'movepage-page-moved'          => 'Faqja $1 është zhvendosur tek $2.',
'movepage-page-unmoved'        => "Faqja $1 s'mund të zhvendosej tek $2.",
'movepage-max-pages'           => 'Maksimumi prej $1 {{PLURAL:$1|faqeje|faqesh}} është zhvendosur dhe nuk do të zhvendoset më automatikisht.',
'1movedto2'                    => '[[$1]] u zhvendos tek [[$2]]',
'1movedto2_redir'              => '[[$1]] u zhvendos tek [[$2]] dhe u krijua një faqe përcjellimi',
'move-redirect-suppressed'     => 'përcjellimi i prishur',
'movelogpage'                  => 'Regjistri i zhvendosjeve',
'movelogpagetext'              => 'Më poshtë është një listë e faqeve të zhvendosura',
'movesubpage'                  => '$1 nën-faqe',
'movesubpagetext'              => 'Kjo faqe ka $1 nën-faqe treguar më poshtë.',
'movenosubpage'                => 'Kjo faqe nuk ka nën-faqe.',
'movereason'                   => 'Arsyeja:',
'revertmove'                   => 'ktheje',
'delete_and_move'              => 'Grise dhe zhvendose',
'delete_and_move_text'         => '==Nevojitet grisje==

Faqja "[[:$1]]" ekziston, dëshironi ta grisni për të mundësuar zhvendosjen?',
'delete_and_move_confirm'      => 'Po, grise faqen',
'delete_and_move_reason'       => 'U gris për të liruar vendin për përcjellim',
'selfmove'                     => 'Nuk munda ta zhvendos faqen sepse titulli i ri është i njëjtë me të vjetrin.',
'immobile-source-namespace'    => 'Nuk mund të lëvizet faqja tek "$1"',
'immobile-target-namespace'    => 'Nuk mund të lëvizen faqet tek "$1"',
'immobile-target-namespace-iw' => 'Lidhja ndër-wiki nuk është një objektiv i vlefshëm për zhvendosjen e faqes.',
'immobile-source-page'         => 'Kjo faqe është e pa lëvizshme.',
'immobile-target-page'         => 'Nuk mund të zhvendoset tek titulli i destinuar.',
'imagenocrossnamespace'        => 'Nuk mund të lëvizet skeda tek hapësira e jo-skedës',
'nonfile-cannot-move-to-file'  => 'Nuk mund të lëvizet jo-skeda tek hapësira e skedës',
'imagetypemismatch'            => 'Skeda e re nuk përputhet me llojin e vet',
'imageinvalidfilename'         => 'Emri i skedës së synuar është i pavlefshëm',
'fix-double-redirects'         => 'Përditësoni çdo përcjellim që tregon titullin origjinal',
'move-leave-redirect'          => 'Lini një përcjellim prapa',
'protectedpagemovewarning'     => "'''Kujdes''': Kjo faqe është mbrojtur, kështu që vetëm përdoruesit me privilegje administratorësh mund ta zhvendosin atë.
Veprimi i fundit mbi këtë faqe është poshtë për referncë:",
'semiprotectedpagemovewarning' => "'''Kujdes''': Kjo faqe është mbrojtur, kështu që vetëm përdoruesit e regjistruar mund ta zhvendosin atë.
Veprimi i fundit mbi këtë faqe është poshtë për referncë:",
'move-over-sharedrepo'         => '== Skeda ekziston ==
[[:$1]] ekziston në një magazinë të përbashkët. Zhvendosja e një skede tek ky titull do të prishë skedën e përbashkët.',
'file-exists-sharedrepo'       => 'Emri i zgjedhur i skedës është në përdorim në një magazinë të përbashkët.
Ju lutemi zgjidhni në emët tjetër.',

# Export
'export'            => 'Eksportoni faqe',
'exporttext'        => 'Mund të eksportoni tekstin dhe historinë e redaktimit e një faqeje ose disa faqesh të mbështjesha në XML; kjo mund të importohet në një wiki tjetër që përdor softuerin MediaWiki (tani për tani, ky opsion nuk është përfshirë tek {{SITENAME}}).

Për të eksportuar faqe, thjesht shtypni një emër për çdo rresht, ose krijoni lidhje të tipit [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] si [[{{MediaWiki:Mainpage}}]].',
'exportcuronly'     => 'Përfshi vetëm versionin e fundit, jo të gjithë historinë',
'exportnohistory'   => "'''Shënim:''' Eksportimi i historisë së faqes për shkaqe të rendimentit nuk është e mundshme.",
'export-submit'     => 'Eksporto',
'export-addcattext' => 'Shto faqe nga kategoria:',
'export-addcat'     => 'Shto',
'export-addnstext'  => 'Shtoni faqet nga hapësirat:',
'export-addns'      => 'Shto',
'export-download'   => 'Ruaje si skedë',
'export-templates'  => 'Përfshinë stampa',
'export-pagelinks'  => 'Përfshini faqet e lidhura në një thellësi prej:',

# Namespace 8 related
'allmessages'                   => 'Mesazhet e sistemit',
'allmessagesname'               => 'Emri',
'allmessagesdefault'            => 'Teksti i parazgjedhur',
'allmessagescurrent'            => 'Teksti i tanishëshm',
'allmessagestext'               => 'Kjo është një listë e të gjitha faqeve në hapësirën MediaWiki:
Ju lutemi vizitoni [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] dhe [http://translatewiki.net translatewiki.net] nëse dëshironi të kontribuoni në lokalizimin e përgjithshëm MediaWiki',
'allmessagesnotsupportedDB'     => "Kjo faqe nuk mund të përdoret sepse '''\$wgUseDatabaseMessages''' është çaktivizuar.",
'allmessages-filter-legend'     => 'Filtër',
'allmessages-filter'            => 'Filtroni nga shteti',
'allmessages-filter-unmodified' => 'E pandryshuar',
'allmessages-filter-all'        => 'Të gjithë',
'allmessages-filter-modified'   => 'E ndryshuar',
'allmessages-prefix'            => 'Filtroni nga parashtesat:',
'allmessages-language'          => 'Gjuha:',
'allmessages-filter-submit'     => 'Shko',

# Thumbnails
'thumbnail-more'           => 'Zmadho',
'filemissing'              => 'Mungon skeda',
'thumbnail_error'          => 'Gabim gjatë krijimit të figurës përmbledhëse: $1',
'djvu_page_error'          => 'Faqja DjVu jashtë renditjes',
'djvu_no_xml'              => 'Nuk mund të gjendet XML për skedën DjVu',
'thumbnail_invalid_params' => 'Parametrat thumbnail të pavlefshme',
'thumbnail_dest_directory' => 'Në pamundësi për të krijuar dosjen e destinacionit',
'thumbnail_image-type'     => 'Lloji i fotografisë nuk mbështetet',
'thumbnail_gd-library'     => 'Konfigurim librarie GD i paplotë: mungon funksoni $1',
'thumbnail_image-missing'  => 'Skeda duket se mungon: $1',

# Special:Import
'import'                     => 'Importoni faqe',
'importinterwiki'            => 'Import ndër-wiki',
'import-interwiki-text'      => 'Zgjidhni një wiki dhe titull faqeje për të importuar.
Datat e versioneve dhe emrat e redaktuesve do të ruhen.
Të gjitha veprimet e importit transwiki janë të regjistruara tek [[Special:Log/import|registri i importimeve]].',
'import-interwiki-source'    => 'Burimi wiki/faqe',
'import-interwiki-history'   => 'Kopjo të gjitha versionet e historisë për këtë faqe',
'import-interwiki-templates' => 'Përfshini të gjitha stampat',
'import-interwiki-submit'    => 'Importo',
'import-interwiki-namespace' => 'Hapësira e destinuar:',
'import-upload-filename'     => 'Emri i skedës:',
'import-comment'             => 'Arsyeja:',
'importtext'                 => 'Ju lutem eksportoni këtë skedë nga burimi wiki duke përdorur mjetin Special:Export, ruajeni në diskun tuaj dhe ngarkojeni këtu.',
'importstart'                => 'Duke importuar faqet...',
'import-revision-count'      => '$1 {{PLURAL:$1|version|versione}}',
'importnopages'              => "S'ka faqe për tu importuar.",
'imported-log-entries'       => 'Importuar $1 {{PLURAL:$1|hyrje|hyrje}}',
'importfailed'               => 'Importimi dështoi: $1',
'importunknownsource'        => 'Lloj burimi importi i panjohur',
'importcantopen'             => 'Nuk mund të hapë skedën e importuar',
'importbadinterwiki'         => 'Lidhje e prishur interwiki',
'importnotext'               => 'Bosh ose pa tekst',
'importsuccess'              => 'Importim i sukseshëm!',
'importhistoryconflict'      => 'Ekzistojnë versione historiku në konflikt (kjo faqe mund të jetë importuar më parë)',
'importnosources'            => 'Nuk ka asnjë burim importi të përcaktuar dhe ngarkimet historike të drejtpërdrejta janë ndaluar.',
'importnofile'               => 'Nuk u ngarkua asnjë skedë importi.',
'importuploaderrorsize'      => 'Ngarkimi ose importimi i skedës dështoi.
Skeda është më e madhe se madhësia e lejuar.',
'importuploaderrorpartial'   => 'Ngarkimi ose importimi i skedës dështoi.
Skeda u ngarkua vetëm pjesërisht.',
'importuploaderrortemp'      => 'Ngarkimi ose importimi i skedës dështoi.
Një dosje e përkohëshme mungon.',
'import-parse-failure'       => 'Dështim i analizës së importit XML',
'import-noarticle'           => "S'ka faqe për tu importuar!",
'import-nonewrevisions'      => 'Të gjitha versionet kanë qenë të importuara më parë.',
'xml-error-string'           => '$1 në vijën $2, kol $3 (bite $4): $5',
'import-upload'              => 'Ngarko të dhëna XML',
'import-token-mismatch'      => 'Humbje e të dhënave të sesionit.
Ju lutemi provoni përsëri.',
'import-invalid-interwiki'   => 'Nuk mund të importohet nga wiki i specifikuar.',

# Import log
'importlogpage'                    => 'Regjistri i importeve',
'importlogpagetext'                => 'Importimet administrative të faqeve me historik redaktimi nga wiki-t e tjera.',
'import-logentry-upload'           => 'importoi [[$1]] nëpërmjet ngarkimit të skedave',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|version|versione}}',
'import-logentry-interwiki'        => 'transwikoji $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$!1|version|versione}} nga $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Faqja juaj e përdoruesit',
'tooltip-pt-anonuserpage'         => 'Faqja e përdoruesve anonim nga kjo adresë IP',
'tooltip-pt-mytalk'               => 'Faqja juaj e diskutimeve',
'tooltip-pt-anontalk'             => 'Faqja e diskutimeve të përdoruesve anonim për këtë adresë IP',
'tooltip-pt-preferences'          => 'Parapëlqimet tuaja',
'tooltip-pt-watchlist'            => 'Lista e faqeve nën mbikqyrjen tuaj.',
'tooltip-pt-mycontris'            => 'Lista e kontributeve tuaja',
'tooltip-pt-login'                => 'Të hysh brenda nuk është e detyrueshme, por ka shumë përparësi.',
'tooltip-pt-anonlogin'            => 'Të hysh brenda nuk është e detyrueshme, por ka shumë përparësi.',
'tooltip-pt-logout'               => 'Dalje',
'tooltip-ca-talk'                 => 'Diskuto për përmbajtjen e faqes',
'tooltip-ca-edit'                 => "Ju mund ta redaktoni këtë faqe. Përdorni butonin >>Trego parapamjen<< para se t'i kryeni ndryshimet.",
'tooltip-ca-addsection'           => 'Fillo një temë të re diskutimi.',
'tooltip-ca-viewsource'           => 'Kjo faqe është e mbrojtur. Ju mundeni vetëm ta shikoni burimin e tekstit.',
'tooltip-ca-history'              => 'Versione të mëparshme të artikullit.',
'tooltip-ca-protect'              => 'Mbroje këtë faqe',
'tooltip-ca-unprotect'            => 'Liroje faqen',
'tooltip-ca-delete'               => 'Grise këtë faqe',
'tooltip-ca-undelete'             => 'Faqja u restaurua',
'tooltip-ca-move'                 => 'Me anë të zhvendosjes mund ta ndryshoni titullin e artikullit',
'tooltip-ca-watch'                => 'Shtoje faqen në lisën e faqeve nën mbikqyrje',
'tooltip-ca-unwatch'              => 'Hiqe faqen nga lista e faqeve nën mbikqyrje.',
'tooltip-search'                  => 'Kërko në projekt',
'tooltip-search-go'               => 'Shko tek faqja me këtë emër nëse ekziston',
'tooltip-search-fulltext'         => 'Kërko faqet me këtë tekst',
'tooltip-p-logo'                  => 'Figura e Faqes Kryesore',
'tooltip-n-mainpage'              => 'Vizitoni Faqen kryesore',
'tooltip-n-mainpage-description'  => 'Vizito faqen kryesore',
'tooltip-n-portal'                => 'Mbi projektin, çfarë mund të bëni dhe ku gjenden gjërat.',
'tooltip-n-currentevents'         => 'Informacion rreth ngjarjeve aktuale.',
'tooltip-n-recentchanges'         => 'Lista e ndryshimeve së fundmi në projekt',
'tooltip-n-randompage'            => 'Shikoni një artikull të rastit.',
'tooltip-n-help'                  => 'Vendi ku mund të gjeni ndihmë.',
'tooltip-t-whatlinkshere'         => 'Lista e faqeve që lidhen tek kjo faqe',
'tooltip-t-recentchangeslinked'   => 'Lista e ndryshimeve të faqeve që lidhen tek kjo faqe',
'tooltip-feed-rss'                => 'Burimi ushqyes "RSS" për këtë faqe',
'tooltip-feed-atom'               => 'Burimi ushqyes "Atom" për këtë faqe',
'tooltip-t-contributions'         => 'Shiko listën e kontributeve për përdoruesin në fjalë',
'tooltip-t-emailuser'             => 'Dërgoni një email përdoruesit',
'tooltip-t-upload'                => 'Ngarkoni figura ose skeda të tjera',
'tooltip-t-specialpages'          => 'Lista e të gjitha faqeve speciale.',
'tooltip-t-print'                 => 'Version i shtypshëm i kësaj faqeje',
'tooltip-t-permalink'             => 'Lidhja e përhershme tek ky version i faqes',
'tooltip-ca-nstab-main'           => 'Shikoni përmbajtjen e atikullit.',
'tooltip-ca-nstab-user'           => 'Shikoni faqen e përdoruesit',
'tooltip-ca-nstab-media'          => 'Shikoni faqen e skedës',
'tooltip-ca-nstab-special'        => 'Kjo është një faqe speciale. Ju nuk mundeni ta redaktoni këtë faqe',
'tooltip-ca-nstab-project'        => 'Shikoni faqen e projektit',
'tooltip-ca-nstab-image'          => 'Shikoni faqen e figurës',
'tooltip-ca-nstab-mediawiki'      => 'Shikoni mesazhet e sistemit',
'tooltip-ca-nstab-template'       => 'Shikoni stampën',
'tooltip-ca-nstab-help'           => 'Shikoni faqet ndihmëse',
'tooltip-ca-nstab-category'       => 'Shikoni faqen e kategorisë',
'tooltip-minoredit'               => 'Shënoje këtë redaktim të vogël',
'tooltip-save'                    => 'Kryej ndryshimet',
'tooltip-preview'                 => 'Shiko parapamjen e ndryshimeve, përdoreni këtë para se të kryeni ndryshimet!',
'tooltip-diff'                    => 'Trego ndryshimet që Ju i keni bërë tekstit.',
'tooltip-compareselectedversions' => 'Shikoni krahasimin midis dy versioneve të zgjedhura të kësaj faqeje.',
'tooltip-watch'                   => 'Mbikqyre këtë faqe',
'tooltip-recreate'                => 'Rikrijoje faqen edhe nëse është grisur më parë',
'tooltip-upload'                  => 'Fillo ngarkimin',
'tooltip-rollback'                => '"Rikthimi" rikthen ndryshimet tek kjo faqe nga redaktuesi i fundit vetëm me një klikim.',
'tooltip-undo'                    => '"Zhbëj" rikthen këtë ndryshim deh hap formën e redaktimit në mënyrë parapamjeje. Lejon që të shtoni një arsye tek përmbledhja.',
'tooltip-preferences-save'        => 'Ruaj parapëlqimet',
'tooltip-summary'                 => 'Fusni një përmbledhje të shkurtër',

# Stylesheets
'monobook.css' => '/* redaktoni këtë faqe për të përshtatur pamjen Monobook për tëra faqet tuaja */',

# Metadata
'nodublincore'      => 'Dublin Core RDF metadata nuk është i mundshëm për këtë server.',
'nocreativecommons' => 'Creative Commons RDF metadata nuk është i mundshëm për këtë server.',
'notacceptable'     => 'Wiki server nuk mundet ti përgatit të dhënat për klintin tuaj.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Përdoruesi anonim|Përdoruesit anonimë}} të {{SITENAME}}',
'siteuser'         => 'Përdoruesi $1 nga {{SITENAME}}',
'anonuser'         => 'Përdorues anonim i {{SITENAME}} $1',
'lastmodifiedatby' => 'Kjo faqe është redaktuar së fundit më $2, $1 nga $3.',
'othercontribs'    => 'Bazuar në punën e: $1',
'others'           => 'të tjerë',
'siteusers'        => '{{SITENAME}} {{PLURAL:$2|përdorues|përdorues}} $1',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|përdoruesi anonim|përdoruesit anonimë}} $1',
'creditspage'      => 'Statistika e faqes',
'nocredits'        => 'Për këtë faqe nuk ka informacione.',

# Spam protection
'spamprotectiontitle' => 'Mbrojtje ndaj teksteve të padëshiruara',
'spamprotectiontext'  => 'Faqja që dëshironit të ruani është bllokuar nga filtri i teksteve të padëshiruara. Ka mundësi që kjo të ketë ndodhur për shkak të ndonjë lidhjeje të jashtme.',
'spamprotectionmatch' => 'Teksti në vijim është cilësuar i padëshiruar nga softueri: $1',
'spambot_username'    => 'MediaWiki spam-pastrues',
'spam_reverting'      => "U kthye tek versioni i fundit që s'ka lidhje tek $1",
'spam_blanking'       => 'U boshatis sepse të gjitha versionet kanë lidhje tek $1',

# Info page
'infosubtitle'   => 'Informacion për faqen',
'numedits'       => 'Numri i versioneve të artikullit: $1',
'numtalkedits'   => 'Numrii versioneve të diskutimit të artikullit: $1',
'numwatchers'    => 'Numri i mbikqyrësve: $1',
'numauthors'     => 'Numri i autorëve të artikullit: $1',
'numtalkauthors' => 'Numri i diskutuesve për artikullin: $1',

# Skin names
'skinname-standard'    => 'Standarte',
'skinname-nostalgia'   => 'Nostalgjike',
'skinname-cologneblue' => 'Kolonjë Blu',

# Math options
'mw_math_png'    => 'Gjithmonë PNG',
'mw_math_simple' => 'HTML në qoftë se është e thjeshtë ose ndryshe PNG',
'mw_math_html'   => 'HTML në qoftë se është e mundur ose ndryshe PNG',
'mw_math_source' => 'Lëre si TeX (për shfletuesit tekst)',
'mw_math_modern' => 'E rekomanduar për shfletuesit modern',
'mw_math_mathml' => 'MathML',

# Math errors
'math_failure'          => 'Nuk e kuptoj',
'math_unknown_error'    => 'gabim i panjohur',
'math_unknown_function' => 'funksion i panjohur',
'math_lexing_error'     => 'gabim leximi',
'math_syntax_error'     => 'Gabim sintakse',
'math_image_error'      => 'Konversioni PNG dështoi; kontrolloni për ndonjë gabim instalimi të latex-it, dvips-it, gs-it, dhe convert-it.',
'math_bad_tmpdir'       => 'Nuk munda të shkruaj ose krijoj dosjen e përkohshme për matematikë',
'math_bad_output'       => 'Nuk munda të shkruaj ose të krijoj prodhimin matematik në dosjen',
'math_notexvc'          => 'Mungon zbatuesi texvc; ju lutem shikoni math/README për konfigurimin.',

# Patrolling
'markaspatrolleddiff'                 => 'Shënoje si të patrulluar',
'markaspatrolledtext'                 => 'Shënoje këtë artikull të patrulluar',
'markedaspatrolled'                   => 'Shënoje të patrulluar',
'markedaspatrolledtext'               => 'Versioni i zgjedhur i [[:$1]] është shënuar si i patrolluar.',
'rcpatroldisabled'                    => 'Kontrollimi i ndryshimeve së fundmi është bllokuar',
'rcpatroldisabledtext'                => 'Kontrollimi i ndryshimeve së fundmi nuk është i mundshëm për momentin.',
'markedaspatrollederror'              => 'Nuk munda ta shënoj të patrulluar',
'markedaspatrollederrortext'          => 'Duhet të përcaktoni versionin për tu shënuar i patrulluar.',
'markedaspatrollederror-noautopatrol' => 'Ju nuk lejoheni të shënoni ndryshimet tuaj si të patrolluara.',

# Patrol log
'patrol-log-page'      => 'Regjistri i patrollimeve',
'patrol-log-header'    => 'Këto janë të dhëna të revizioneve të patrulluara.',
'patrol-log-line'      => 'shënoi $1 të $2 të patrulluar $3',
'patrol-log-auto'      => '(automatikisht)',
'patrol-log-diff'      => 'versioni $1',
'log-show-hide-patrol' => '$1 regjistri i patrollimeve',

# Image deletion
'deletedrevision'                 => 'Gris versionin e vjetër $1',
'filedeleteerror-short'           => 'Gabim gjatë grisjes së skedës: $1',
'filedeleteerror-long'            => 'U hasën gabime gjatë grisjes së skedës:

$1',
'filedelete-missing'              => 'Skeda "$1" nuk mund të griset pasi nuk ekziston.',
'filedelete-old-unregistered'     => 'Versioni i skedës që keni zgjedhur "$1" nuk ndodhet në regjistër.',
'filedelete-current-unregistered' => 'Skeda e zgjedhur "$1" nuk ndodhet në regjistër.',
'filedelete-archive-read-only'    => 'Skedari i arkivimit "$1" nuk mund të ndryshohet nga shëbyesi.',

# Browsing diffs
'previousdiff' => '← Ndryshimi më para',
'nextdiff'     => 'Ndryshimi më pas →',

# Media information
'mediawarning'         => "''Kujdes''': Kjo skedë mund të ketë përmbajtje të dëmshme. 
Duke e përdorur sistemi juaj mund të rrezikohet.",
'imagemaxsize'         => "Kufizoni madhësinë e fotos:<br />''(për faqet e përshkrimit të skedave)''",
'thumbsize'            => 'Madhësia fotove përmbledhëse:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|faqe|faqe}}',
'file-info'            => 'madhësia skedës: $1, lloji MIME: $2',
'file-info-size'       => '$1 × $2 pixela, madhësia e skedës: $3, tipi MIME: $4',
'file-nohires'         => '<small>Nuk ka rezolucion më të madh.</small>',
'svg-long-desc'        => 'skedë SVG, fillimisht $1 × $2 pixel, madhësia e skedës: $3',
'show-big-image'       => 'Rezolucion i plotë',
'show-big-image-thumb' => '<small>Madhësia e këtij shikimi: $1 × $2 pixel</small>',
'file-info-gif-looped' => 'kthyer',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kornizë|korniza}}',
'file-info-png-looped' => 'kthyer',
'file-info-png-repeat' => 'luajtur $1 herë',
'file-info-png-frames' => '$1 {{PLURAL:$1|kornizë|korniza}}',

# Special:NewFiles
'newimages'             => 'Galeria e figurave të reja',
'imagelisttext'         => 'Më poshtë është një listë e $1 {{PLURAL:$1|skedës të renditur|skedave të renditura}} sipas $2.',
'newimages-summary'     => 'Kjo faqe speciale tregon skedat e ngarkuara së fundmi.',
'newimages-legend'      => 'Filtrues',
'newimages-label'       => 'Emri i skedës (ose një pjesë e tij):',
'showhidebots'          => '($1 robotët)',
'noimages'              => "S'ka gjë për të parë.",
'ilsubmit'              => 'Kërko',
'bydate'                => 'datës',
'sp-newimages-showfrom' => 'Trego skedat e reja duke filluar nga $2, $1',

# Bad image list
'bad_image_list' => 'Formati është si vijon:<br /><br />

Vetëm elementet e listuara ( rreshtat duhet të fillojnë me * ) mirren parasysh.<br />
Lidhja e parë nërresht duhet të lidhet tek një skedë e keqe.<br />
Çdo lidhje pasuese në rreshtin e njëjtë konsiderohen si përjashtime.<br />',

# Metadata
'metadata'          => 'Metadata',
'metadata-help'     => 'Kjo skedë përmban hollësira të tjera të cilat mund të jenë shtuar nga kamera ose skaneri dixhital që është përdorur për ta krijuar.
Në qoftë se skeda është ndryshuar nga gjendja origjinale, disa hollësira mund të mos pasqyrojnë versionin e tanishëm.',
'metadata-expand'   => 'Trego detajet',
'metadata-collapse' => 'Fshih detajet',
'metadata-fields'   => 'Të dhënat EXIF që tregohen mëposhtë do të përfshihen tek faqja përshkruese e figurës kur tabela e të dhënave të jetë palosur.
Të tjerat do të fshihen.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Gjerësia',
'exif-imagelength'                 => 'Gjatësia',
'exif-bitspersample'               => 'Bit për komponent',
'exif-compression'                 => 'Lloji i ngjeshjes',
'exif-photometricinterpretation'   => 'Përbërja pixel',
'exif-orientation'                 => 'Orientimi',
'exif-samplesperpixel'             => 'Numri i përbërësve',
'exif-planarconfiguration'         => 'Përpunimi i të dhënave',
'exif-ycbcrsubsampling'            => 'Duke krahasuar raportin e Y tek C',
'exif-ycbcrpositioning'            => 'Pozicioni Y dhe C',
'exif-xresolution'                 => 'Rezolucioni horizontal',
'exif-yresolution'                 => 'Rezolucioni vertikal',
'exif-resolutionunit'              => 'Madhësia e njësisë se X dhe Y',
'exif-stripoffsets'                => 'Vendi i figurave',
'exif-rowsperstrip'                => 'Numri i rreshtave për shirit',
'exif-stripbytecounts'             => 'Bajt për shirit të ngjeshur',
'exif-jpeginterchangeformat'       => 'Çvendos tek JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bajtët të dhënave JPEG',
'exif-transferfunction'            => 'Funksioni i transferit',
'exif-whitepoint'                  => 'Pikët e bardha kromatike',
'exif-primarychromaticities'       => 'Kromatikët e primareve',
'exif-ycbcrcoefficients'           => 'Koeficentët e transformimit të hapësirave të ngjyrave të matricës',
'exif-referenceblackwhite'         => 'Çift vlerash me refernca bardhë dhe zi',
'exif-datetime'                    => 'Data dhe ora e ndryshimit të skedës',
'exif-imagedescription'            => 'Titulli i figurës',
'exif-make'                        => 'Prodhuesi i kamerës',
'exif-model'                       => 'Modeli i kamerës',
'exif-software'                    => 'Softueri i përdorur',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Mbajtësi i të drejtave të autorit',
'exif-exifversion'                 => 'Versioni Exif-it',
'exif-flashpixversion'             => 'Versioni Flahpix i mbështetur',
'exif-colorspace'                  => 'Hapësira e ngjyrave',
'exif-componentsconfiguration'     => 'Kuptimi i secilit komponent',
'exif-compressedbitsperpixel'      => 'Lloji i ngjeshjes së figurës',
'exif-pixelydimension'             => 'Gjerësia e vlefshme e figurës',
'exif-pixelxdimension'             => 'Valind image height',
'exif-makernote'                   => 'Shënimet e prodhuesit',
'exif-usercomment'                 => 'Vërejtjet e përdoruesit',
'exif-relatedsoundfile'            => 'Skeda audio shoqëruese',
'exif-datetimeoriginal'            => 'Data dhe koha e prodhimit të të dhënave',
'exif-datetimedigitized'           => 'Data dhe ora e digjitalizimit',
'exif-subsectime'                  => 'Nën-sekondat DataKoha',
'exif-subsectimeoriginal'          => 'Nën-sekondat DataKohaOrigjinale',
'exif-subsectimedigitized'         => 'Nën-sekondat DataKohaOrigjinale',
'exif-exposuretime'                => 'Kohëzgjatja e ekspozimit',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Numri F',
'exif-exposureprogram'             => 'Zbuloni programin',
'exif-spectralsensitivity'         => 'Ndjeshmëria spektrale',
'exif-isospeedratings'             => 'Vlerësimi i shpejtësisë ISO',
'exif-oecf'                        => 'Faktori i konvertimit optoelektronik',
'exif-shutterspeedvalue'           => 'Shpejtësia e mbyllësit',
'exif-aperturevalue'               => 'Apertura',
'exif-brightnessvalue'             => 'Ndriçimi',
'exif-exposurebiasvalue'           => 'zbuloni vijat e pjerrëta',
'exif-maxaperturevalue'            => 'Hapje maksimale e tokës',
'exif-subjectdistance'             => 'Largësia e subjektit',
'exif-meteringmode'                => 'Mënyra e matjes',
'exif-lightsource'                 => 'Burimi i dritës',
'exif-flash'                       => 'Blici',
'exif-focallength'                 => 'Gjatësia e vatrës',
'exif-subjectarea'                 => 'Hapësira e subjektit',
'exif-flashenergy'                 => 'Energjia e blicit',
'exif-spatialfrequencyresponse'    => 'Përgjigje e frekuencës hapësinore',
'exif-focalplanexresolution'       => 'Rezelucioni i planit fokal X',
'exif-focalplaneyresolution'       => 'Rezelucioni i planit fokal Y',
'exif-focalplaneresolutionunit'    => 'Rezolucioni i njësisë së planit fokal',
'exif-subjectlocation'             => 'Vendndodhja e subjektit',
'exif-exposureindex'               => 'Indeksi i ekspozimit',
'exif-sensingmethod'               => 'Metoda Sensing',
'exif-filesource'                  => 'Burimi i skedës',
'exif-scenetype'                   => 'Lloji Scene',
'exif-cfapattern'                  => 'Modeli CFA',
'exif-customrendered'              => 'Përpunim i fotografisë Costum',
'exif-exposuremode'                => 'Mënyra e ekspozimit',
'exif-whitebalance'                => 'Balanca e bardhë',
'exif-digitalzoomratio'            => 'Zmadhim dixhital',
'exif-focallengthin35mmfilm'       => 'Gjatësia fokale në 35 mm film',
'exif-scenecapturetype'            => 'Shtrirja e largësisë',
'exif-gaincontrol'                 => 'Kontrolli i skenës',
'exif-contrast'                    => 'Kontrasti',
'exif-saturation'                  => 'Mbushja',
'exif-sharpness'                   => 'Ashpërsia',
'exif-devicesettingdescription'    => 'Përshkrimi i parametrave të pajisjes',
'exif-subjectdistancerange'        => 'Shtrirja e largësisë së subjektit',
'exif-imageuniqueid'               => 'ID unike e fotografisë',
'exif-gpsversionid'                => 'Versioni i etiketës GPS',
'exif-gpslatituderef'              => 'Gjerësi veriore ose jugore',
'exif-gpslatitude'                 => 'Gjerësia gjeografike',
'exif-gpslongituderef'             => 'Gjatësi lindore ose perëndimore',
'exif-gpslongitude'                => 'Gjatësia gjeografike',
'exif-gpsaltituderef'              => 'Lartësia orientuese',
'exif-gpsaltitude'                 => 'Lartësia',
'exif-gpstimestamp'                => 'Koha GPS (ora atomike)',
'exif-gpssatellites'               => 'Janë përdorur satelitë për matjen',
'exif-gpsstatus'                   => 'Statusi i marrësit',
'exif-gpsmeasuremode'              => 'Mënyra e matjes',
'exif-gpsdop'                      => 'Saktësia e matjes',
'exif-gpsspeedref'                 => 'Njësia e shpejtësisë',
'exif-gpsspeed'                    => 'Shpejtësia e marrësit GPS',
'exif-gpstrackref'                 => 'Referenca për drejtimin e lëvizjes',
'exif-gpstrack'                    => 'Drejtimi i lëvizjes',
'exif-gpsimgdirectionref'          => 'Referenca për drejtimin e imazhit',
'exif-gpsimgdirection'             => 'Orientimi i figurës',
'exif-gpsmapdatum'                 => 'Anketa e të dhënave gjeodezike të përdorura',
'exif-gpsdestlatituderef'          => 'Referenca për gjerësinë e destinacionit',
'exif-gpsdestlatitude'             => 'Destinacioni i gjerësisë',
'exif-gpsdestlongituderef'         => 'Referenca për gjatësinë e destinacionit',
'exif-gpsdestlongitude'            => 'Gjatësia e destinacionit',
'exif-gpsdestbearingref'           => 'Referenca për qëndrimin e destinacionit',
'exif-gpsdestbearing'              => 'Qëndrimi i destinacionit',
'exif-gpsdestdistanceref'          => 'Referenca për distancën e destinacionit',
'exif-gpsdestdistance'             => 'Distanca tek destinacioni',
'exif-gpsprocessingmethod'         => 'Emri i metodës së përpunimit GPS',
'exif-gpsareainformation'          => 'Emri i zonës GPS',
'exif-gpsdatestamp'                => 'E dhënë GPS',
'exif-gpsdifferential'             => 'Korrigjim diferencial i GPS',

# EXIF attributes
'exif-compression-1' => 'E pangjeshur',

'exif-unknowndate' => 'E dhënë e pa njohur',

'exif-orientation-1' => 'Normale',
'exif-orientation-2' => 'E kthyer horizontalisht',
'exif-orientation-3' => 'E rrotulluar 180°',
'exif-orientation-4' => 'E kthyer vertikalisht',
'exif-orientation-5' => 'E rrotulluar 90° kundër orës dhe e kthyer vertikalisht',
'exif-orientation-6' => 'E rrotulluar 90° sipas orës',
'exif-orientation-7' => 'E rrotulluar 90° sipas orës dhe e kthyer vertikalisht',
'exif-orientation-8' => 'E rrotulluar 90° kundër orës',

'exif-planarconfiguration-1' => 'formati copë',
'exif-planarconfiguration-2' => 'formati planar',

'exif-componentsconfiguration-0' => 'nuk ekziston',

'exif-exposureprogram-0' => 'e padefinuar',
'exif-exposureprogram-1' => 'Doracak',
'exif-exposureprogram-2' => 'Program normal',
'exif-exposureprogram-3' => 'Prioriteti i hapjes (Aperture priority)',
'exif-exposureprogram-4' => 'Përparësia e mbyllësit (Shutter priority)',
'exif-exposureprogram-5' => 'Program krijues',
'exif-exposureprogram-6' => 'Program veprimi',
'exif-exposureprogram-7' => 'Mënyra e portretit',
'exif-exposureprogram-8' => 'Mënyra landspace',

'exif-subjectdistance-value' => '$1 metra',

'exif-meteringmode-0'   => 'E panjohur',
'exif-meteringmode-1'   => 'Mesatare',
'exif-meteringmode-2'   => 'QendraPeshësMesatare',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Model',
'exif-meteringmode-6'   => 'E pjesshme',
'exif-meteringmode-255' => 'Tjetër',

'exif-lightsource-0'   => 'I panjohur',
'exif-lightsource-1'   => 'Ditë',
'exif-lightsource-2'   => 'Fluoreshent',
'exif-lightsource-3'   => 'Tungsten (dritë e flaktë)',
'exif-lightsource-4'   => 'Blic',
'exif-lightsource-9'   => 'Kohë e mirë',
'exif-lightsource-10'  => 'Kohë e vrenjtur',
'exif-lightsource-11'  => 'Hije',
'exif-lightsource-12'  => 'Fluoreshent dite (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluoreshent i badhë dite (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluoreshent i badhë i fresket (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluoreshent i bardhe (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Dritë standarde A',
'exif-lightsource-18'  => 'Dritë standarde B',
'exif-lightsource-19'  => 'Dritë standarde C',
'exif-lightsource-24'  => 'Studio ISO tungsten',
'exif-lightsource-255' => 'Tjetër burim drite',

# Flash modes
'exif-flash-fired-0'    => 'Flashi nuk u ndez',
'exif-flash-fired-1'    => 'Flashi u ndez',
'exif-flash-return-0'   => "s'ka funksion zbulimi prapa",
'exif-flash-return-2'   => 'kthimi i dritës nuk u vërejt',
'exif-flash-return-3'   => 'kthimi i dritës flash u vërejt',
'exif-flash-mode-1'     => 'flashi po ndizet',
'exif-flash-mode-2'     => 'shuarje e detyrueshme e flashit',
'exif-flash-mode-3'     => 'auto mode',
'exif-flash-function-1' => "S'ka funksion të çastit",
'exif-flash-redeye-1'   => 'menyra e reduktimit red-eye',

'exif-focalplaneresolutionunit-2' => 'inç',

'exif-sensingmethod-1' => 'e padefinuar',
'exif-sensingmethod-2' => 'Zona e sensorit one-chip kolor',
'exif-sensingmethod-3' => 'Zona e sensorit two-chip kolor',
'exif-sensingmethod-4' => 'Zona e sensorit three-chip kolor',
'exif-sensingmethod-5' => 'Sensori i zones kolor sequential',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensori linear kolor sequential',

'exif-scenetype-1' => 'Nje fotografi e fotografuar direkt',

'exif-customrendered-0' => 'Proces normal',
'exif-customrendered-1' => 'Proces i zakonshëm',

'exif-exposuremode-0' => 'Ekspozim automatik',
'exif-exposuremode-1' => 'Ekspozim manual',
'exif-exposuremode-2' => 'Grupim atutomatik',

'exif-whitebalance-0' => 'Balance e bardhe automatike',
'exif-whitebalance-1' => 'Balance e bardhe manuale',

'exif-scenecapturetype-0' => 'Standarte',
'exif-scenecapturetype-1' => 'Peizazh',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Pamje nate',

'exif-gaincontrol-0' => 'Asnjë',
'exif-gaincontrol-1' => 'Pak me shume ndricim',
'exif-gaincontrol-2' => 'Shume me shume ndricim',
'exif-gaincontrol-3' => 'Disi me pak ndricim',
'exif-gaincontrol-4' => 'Shume me pak ndricim',

'exif-contrast-0' => 'Normale',
'exif-contrast-1' => 'I dobët',
'exif-contrast-2' => 'I fortë',

'exif-saturation-0' => 'Normale',
'exif-saturation-1' => 'mbushje e pakët',
'exif-saturation-2' => 'mbushje e shumtë',

'exif-sharpness-0' => 'Normale',
'exif-sharpness-1' => 'E butë',
'exif-sharpness-2' => 'E fortë',

'exif-subjectdistancerange-0' => 'E panjohur',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Pamje nga afër',
'exif-subjectdistancerange-3' => 'Pamje nga larg',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Gjerësi veriore',
'exif-gpslatitude-s' => 'Gjerësi jugore',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Gjatësi lindore',
'exif-gpslongitude-w' => 'Gjatësi perëndimore',

'exif-gpsstatus-a' => 'Duke bërë matje',
'exif-gpsstatus-v' => 'Matja e nderveprimit',

'exif-gpsmeasuremode-2' => 'matje në 2 madhësi',
'exif-gpsmeasuremode-3' => 'matje në 3 madhësi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometra në orë',
'exif-gpsspeed-m' => 'Milje në orë',
'exif-gpsspeed-n' => 'Nyje',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Drejtimi i vërtetë',
'exif-gpsdirection-m' => 'Drejtimi magnetik',

# External editor support
'edit-externally'      => 'Ndryshoni këtë skedë me një mjet të jashtëm',
'edit-externally-help' => '(Shikoni [http://www.mediawiki.org/wiki/Manual:External_editors udhëzimet e instalimit] për më shumë informacion)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'të gjitha',
'imagelistall'     => 'të gjitha',
'watchlistall2'    => 'të gjitha',
'namespacesall'    => 'të gjitha',
'monthsall'        => 'të gjitha',
'limitall'         => 'Të gjitha',

# E-mail address confirmation
'confirmemail'              => 'Vërtetoni adresën tuaj',
'confirmemail_noemail'      => 'Ju nuk keni dhënë email të sakt te [[Special:Preferences|parapëlqimet e juaja]].',
'confirmemail_text'         => 'Për të marrë email duhet të vërtetoni adresen tuaj. Shtypni butonin e mëposhtëm për të dërguar një email vërtetimi tek adresa juaj. Email-i do të përmbajë një lidhje me kod të shifruar. Duke ndjekur lidhjen nëpërmjet shfletuesit tuaj do të vërtetoni adresën.',
'confirmemail_pending'      => "Një kod vërtetimi ju është dërguar më parë. Nëse sapo hapët llogarinë tuaj prisni disa minuta deri sa t'iu arrijë mesazhi përpara se të kërkoni një kod të ri.",
'confirmemail_send'         => 'Dërgo vërtetimin',
'confirmemail_sent'         => 'Email-i për vërtetim është dërguar.',
'confirmemail_oncreate'     => 'Një kod vërtetimi është dërguar tek adresa juaj e email-it.
Ky kod nuk kërkohet për të hyrë brenda në llogarinë tuaj, por nevojitet për të mundësuar mjetet që përdorin email në këtë wiki.',
'confirmemail_sendfailed'   => '{{SITENAME}} nuk mundi ta çojë email-in tuaj konfirmues.
Ju lutemi kontrolloni adresen e emial-it tuaj per gabime ne shkrim.

Postieri u kthye: $1',
'confirmemail_invalid'      => 'Kodi i shifrimit të vërtetimit është gabim ose ka skaduar.',
'confirmemail_needlogin'    => 'Ju duhet të $1 për ta konfirmuar email-adresën',
'confirmemail_success'      => 'Adresa juaj është vërtetuar. Mund të hyni brënda dhe të përdorni wiki-n.',
'confirmemail_loggedin'     => 'Adresa juaj është vërtetuar.',
'confirmemail_error'        => 'Pati gabim gjatë ruajtjes së vërtetimit tuaj.',
'confirmemail_subject'      => 'Vërtetim adrese nga {{SITENAME}}',
'confirmemail_body'         => 'Dikush, me gjasë ju, nga IP adresa $1,
ka regjistruar një llogari "$2" me këtë e-mail adresë në {{SITENAME}}.

Për të konfirmuar se kjo llogari ju përket me të vërtetë dhe për të aktivizuar
funksionet e \'\'e-mail\'\'-it në {{SITENAME}}, hapni këtë lidhje në shfletuesin tuaj:

$3

Nëse llogaria *nuk* ju përket, ndiqni këtë lidhje
për të anuluar konfirmimin e e-mail adresës:

$5

Ky kod i konfirmimit skadon me $4.',
'confirmemail_body_changed' => 'Dikush, me gjasë ju, nga IP adresa $1,
ka ndryshuar e-mail adresën e llogarisë "$2" me këtë adresë në {{SITENAME}}.

Për të konfirmuar se kjo llogari ju përket me të vërtetë dhe për të rizaktivizuar
funksionet e \'\'e-mail\'\'-it në {{SITENAME}}, hapni këtë lidhje në shfletuesin tuaj:

$3

Nëse llogaria *nuk* ju përket, ndiqni këtë lidhje
për të anuluar konfirmimin e e-mail adresës:

$5

Ky kod i konfirmimit skadon me $4.',
'confirmemail_body_set'     => 'Dikush, me gjasë ju, nga IP adresa $1,
ka ndryshuar e-mail adresën e llogarisë "$2" me këtë adresë në {{SITENAME}}.

Për të konfirmuar se kjo llogari ju përket me të vërtetë dhe për të riaktivizuar
funksionet e \'\'e-mail\'\'-it në {{SITENAME}}, hapni këtë lidhje në shfletuesin tuaj:

$3

Nëse llogaria *nuk* ju përket, ndiqni këtë lidhje
për të anuluar konfirmimin e e-mail adresës:

$5

Ky kod i konfirmimit skadon me $4.',
'confirmemail_invalidated'  => 'Vërtetimi i adresës së email-it është tërhequr',
'invalidateemail'           => 'Tërhiq vërtetimin e email-it',

# Scary transclusion
'scarytranscludedisabled' => '[Lidhja Interwiki nuk është i mundshëm]',
'scarytranscludefailed'   => '[Gjetja e stampes deshtoi per $1]',
'scarytranscludetoolong'  => '[Adresa URL eshte teper e gjate]',

# Trackbacks
'trackbackbox'      => 'Lidhje ndjekëse për këtë faqe:<br />
$1',
'trackbackremove'   => '([$1 hiqe])',
'trackbacklink'     => 'Lidhje ndjekëse',
'trackbackdeleteok' => 'Lidhja ndjekëse u hoq.',

# Delete conflict
'deletedwhileediting' => 'Kujdes! Kjo faqe është grisur pasi keni filluar redaktimin!',
'confirmrecreate'     => "Përdoruesi [[User:$1|$1]] ([[User talk:$1|diskutime]]) grisi këtë artikull mbasi ju filluat ta redaktoni për arsyen:
: ''$2''
Ju lutem konfirmoni nëse dëshironi me të vertetë ta rikrijoni këtë artikull.",
'recreate'            => 'Rikrijo',

# action=purge
'confirm_purge_button' => 'Shko',
'confirm-purge-top'    => "Pastro ''cache''-in për këtë faqe?",
'confirm-purge-bottom' => "Spastrimi i një faqeje pastron ''cache''-in dhe detyron shfaqjen e verzionit më të fundit të faqes.",

# Multipage image navigation
'imgmultipageprev' => '← faqja e kaluar',
'imgmultipagenext' => 'faqja tjetër →',
'imgmultigo'       => 'Shko!',
'imgmultigoto'     => 'Shko tek faqja $1',

# Table pager
'ascending_abbrev'         => 'ngritje',
'descending_abbrev'        => 'zbritje',
'table_pager_next'         => 'Faqja më pas',
'table_pager_prev'         => 'Faqja më parë',
'table_pager_first'        => 'Faqja e parë',
'table_pager_last'         => 'Faqja e fundit',
'table_pager_limit'        => 'Trego $1 rreshta për faqe',
'table_pager_limit_label'  => 'Artikuj per faqe:',
'table_pager_limit_submit' => 'Shko',
'table_pager_empty'        => "S'ka rezultate",

# Auto-summaries
'autosumm-blank'   => 'U boshatis faqja',
'autosumm-replace' => "Faqja u zëvendësua me '$1'",
'autoredircomment' => 'Përcjellim te [[$1]]',
'autosumm-new'     => 'Krijoi faqen me "$1"',

# Live preview
'livepreview-loading' => 'Duke punuar…',
'livepreview-ready'   => 'Duke punuar… Gati!',
'livepreview-failed'  => 'Parapamja e menjëhershme dështoi! Provoni parapamjen e zakonshme.',
'livepreview-error'   => 'Nuk mund të kryhej lidhja: $1 "$2". Provoni parapamjen e zakonshme.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ndryshimet më të reja se $1 {{PLURAL:$1|sekond|sekonda}} mund të mos tregohen në këtë listë.',
'lag-warn-high'   => 'Për shkak të vonesës së regjistrit ndryshimet më të reja se $1 {{PLURAL:$1|sekond|sekonda}} mund të mos tregohen në këtë listë.',

# Watchlist editor
'watchlistedit-numitems'       => 'Lista mbikëqyrëse e juaj përmban {{PLURAL:$1|1 titull|$1 tituj}}, pa faqet e diskutimit.',
'watchlistedit-noitems'        => 'Lista juaj mbikqyrëse nuk ka titull.',
'watchlistedit-normal-title'   => 'Redakto listën mbikqyrëse',
'watchlistedit-normal-legend'  => 'Largo titujt nga lista mbikqyrëse',
'watchlistedit-normal-explain' => 'Titujt në listën mbikëqyrëse janë treguar poshtë.
Largo titullin duke shënuar kutizën dhe pastaj shtype butonin Largoj titujt.
Ju gjithashtu mundeni ta redaktoni listën [[Special:Watchlist/raw|këtu]].',
'watchlistedit-normal-submit'  => 'Largo Titujt',
'watchlistedit-normal-done'    => '{{PLURAL:$1|1 titull u larguan|$1 tituj u larguan}} u larguan nga lista mbikëqyrëse e juaj:',
'watchlistedit-raw-title'      => 'Redakto listën mbikëqyrëse të papërpunuar',
'watchlistedit-raw-legend'     => 'Redakto listën mbikëqyrëse të papërpunuar',
'watchlistedit-raw-explain'    => 'Titujt në listën tuaj mbikqyrëse janë të treguar poshtë dhe mund të redaktohen duke i shtuar ose duke i hequr nga lista; një titull pë rresht.
Kur të mbaroni, klikoni "{{int:Watchlistedit-raw-submit}}".
Ju gjithashtu mund [[Special:Watchlist/edit|të përdorni redaktuesin standart]].',
'watchlistedit-raw-titles'     => 'Titujt:',
'watchlistedit-raw-submit'     => 'Aktualizoje listën',
'watchlistedit-raw-done'       => 'Lista mbikëqyrëse u aktualizua.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 titull u shtua|$1 tituj u shtuan}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 titull u largua|$1 tituj u larguan}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Shih ndryshimet e rëndësishme',
'watchlisttools-edit' => 'Shih dhe redakto listën mbikqyrëse.',
'watchlisttools-raw'  => 'Redaktoje drejtpërdrejt listën',

# Core parser functions
'unknown_extension_tag' => 'Etiketë shtesë e panjohur "$1"',
'duplicate-defaultsort' => '\'\'\'Kujdes:\'\'\' Renditja kryesore e çelësit "$2" refuzon renditjen e mëparshme kryesore të çelësit "$1".',

# Special:Version
'version'                          => 'Versioni',
'version-extensions'               => 'Zgjerime të instaluara',
'version-specialpages'             => 'Faqe speciale',
'version-parserhooks'              => 'Parser goditje',
'version-variables'                => 'Variabël',
'version-antispam'                 => 'Spam',
'version-skins'                    => 'Pamjet',
'version-other'                    => 'Të tjera',
'version-mediahandlers'            => 'Mbajtesit e Media-s',
'version-hooks'                    => 'Goditjet',
'version-extension-functions'      => 'Funksionet shtese',
'version-parser-extensiontags'     => 'Parser etiketat shtese',
'version-parser-function-hooks'    => 'Parser goditjet e funksionit',
'version-skin-extension-functions' => 'Funksionet shtese te pamjes',
'version-hook-name'                => 'Emri i goditjes',
'version-hook-subscribedby'        => 'Abonuar nga',
'version-version'                  => '(Versioni $1)',
'version-license'                  => 'Licensa',
'version-poweredby-credits'        => "Ky wiki është mundësuar nga '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'të tjerë',
'version-license-info'             => 'MediaWiki është një softuer i lirë; ju mund ta shpërndani dhe redakatoni atë nën kushtet GNU General Public License si e publikuar nga fondacioni Free Software; ose versioni 2 i licensës, ose çdo version më i vonshëm.

MediaWiki është shpërndarë me shpresën se do të jetë i dobishëm, por PA ASNJË GARANCI; as garancinë e shprehur të SHITJES apo PËRDORIMIT PËR NJË QËLLIM TË CAKTUAR. Shikoni GNU General Public License  për më shumë detaje.

Ju duhet të keni marrë [{{SERVER}}{{SCRIPTPATH}}/COPYING një kopje të GNU General Public License] së bashku me këtë program; nëse jo, shkruani tek Free Software Foundation, Inc., 51 Rruga Franklin, Kati i pestë, Boston, MA 02110-1301, ShBA ose [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html lexojeni atë online].',
'version-software'                 => 'Softuerët e instaluar',
'version-software-product'         => 'Produkti',
'version-software-version'         => 'Versioni',

# Special:FilePath
'filepath'         => 'Vendndodhja e skedave',
'filepath-page'    => 'Skeda:',
'filepath-submit'  => 'Shko',
'filepath-summary' => 'Kjo faqe speciale jep vendndodhjen e plotë të një skede. Figurat tregohen me madhësi të plotë, skedat e tjera hapen me programet përkatëse.

Shtypni emrin e skedës pa parashtesën "Figura:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Kërkoni për skeda të dyfishta',
'fileduplicatesearch-summary'  => 'Kërkoni për dyfishime të skedave në bazë të vlerës përmbledhëse («hash»).

Vendosni emrin e skedës pa parashtesën "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Kërko për dyfishime',
'fileduplicatesearch-filename' => 'Emri i skedës:',
'fileduplicatesearch-submit'   => 'Kërko',
'fileduplicatesearch-info'     => '$1 × $2 pixel<br />Madhësia e skedës: $3<br />Lloji MIME: $4',
'fileduplicatesearch-result-1' => 'Skeda "$1" nuk ka kopje të njëjta',
'fileduplicatesearch-result-n' => 'Skeda "$1" ka {{PLURAL:$2|1 dyfishim|$2 dyfishime}}.',

# Special:SpecialPages
'specialpages'                   => 'Faqet speciale',
'specialpages-note'              => '---
Faqet speciale normale.
<strong class="mw-specialpagerestricted">Faqet speciale të kufizuara.</strong>',
'specialpages-group-maintenance' => 'Përmbledhje mirëmbajtjeje',
'specialpages-group-other'       => 'Faqe speciale të tjera',
'specialpages-group-login'       => 'Hyrje dhe hapje llogarie',
'specialpages-group-changes'     => 'Ndryshime së fundmi dhe regjistra',
'specialpages-group-media'       => 'Përmbledhje media dhe ngarkime',
'specialpages-group-users'       => 'Përdoruesit dhe privilegjet',
'specialpages-group-highuse'     => 'Faqe të shumëpërdorura',
'specialpages-group-pages'       => 'Lista e faqeve',
'specialpages-group-pagetools'   => 'Mjetet e faqes',
'specialpages-group-wiki'        => 'Mjetet dhe të dhënat wiki',
'specialpages-group-redirects'   => 'Përcjellime tek faqet speciale',
'specialpages-group-spam'        => 'Mjetet për spam',

# Special:BlankPage
'blankpage'              => 'Faqe e zbrazët',
'intentionallyblankpage' => 'Kjo faqe me qëllim është lënë e zbrazët',

# External image whitelist
'external_image_whitelist' => '#Lëreni këtë rresht ashtu siç është<pre>
#Vendosni shprehje fragmentesh të rregullta (vetëm pjesën që shkon ndërmjet //) poshtë
#Këto do të krahasohen me URL-të  e figurave të jashtme
#Ato që përputhen do të shfaqen si figura, të tjerat do të shfaqen vetëm si një lidhje
#Rreshtat që fillojnë me # trajtohen si komente
#Kjo është shumë e ndjeshme

#Vendosini të fragmentet sipër këtij rreshti. Lëreni këtë rresht ashtu siç është</pre>',

# Special:Tags
'tags'                    => 'Etiketat e ndryshimeve të pavlefshme',
'tag-filter'              => '[[Special:Tags|Etiketa]] filter:',
'tag-filter-submit'       => 'Filtër',
'tags-title'              => 'Etiketat',
'tags-intro'              => "Kjo faqe liston etiketat që softueri mund t'i shënojë me një redaktim, dhe kuptimin e tyre.",
'tags-tag'                => 'Emri i etiketës',
'tags-display-header'     => 'Dukja në listat e ndryshimeve',
'tags-description-header' => 'Përshkrimi i plotë i kuptimit',
'tags-hitcount-header'    => 'Ndrzshimet e etikuara',
'tags-edit'               => 'redakto',
'tags-hitcount'           => '$1 {{PLURAL:$1|ndryshim|ndryshime}}',

# Special:ComparePages
'comparepages'     => 'Krahasoni faqet',
'compare-selector' => 'Krahasoni versionet e faqeve',
'compare-page1'    => 'Faqe 1',
'compare-page2'    => 'Faqe 2',
'compare-rev1'     => 'Version 1',
'compare-rev2'     => 'Version 2',
'compare-submit'   => 'Krahasimi',

# Database error messages
'dberr-header'      => 'Kjo wiki ka një problem',
'dberr-problems'    => 'Na vjen keq! 
Kjo faqe është duke përjetuar vështirësi teknike.',
'dberr-again'       => 'Pritni disa minuta dhe provoni të ringarkoni faqen.',
'dberr-info'        => '(Nuk mund të lidhet me serverin bazë e të dhënave : $1)',
'dberr-usegoogle'   => 'Ju mund të provoni të kërkoni përmes Googles në ndërkohë.',
'dberr-outofdate'   => 'Vini re se indekset e tyre të përmbajtjes tona mund të jetë e vjetëruar.',
'dberr-cachederror' => 'Kjo është një kopje e faqes së kërkuar dhe mund të jetë e vjetëruar.',

# HTML forms
'htmlform-invalid-input'       => 'Ka probleme me disa kontribute tuaja',
'htmlform-select-badoption'    => 'Vlera që ju e specifikuat nuk është një alternativë e vlefshme.',
'htmlform-int-invalid'         => 'Vlera që ju e specifikuat nuk është numër i plotë.',
'htmlform-float-invalid'       => 'Vlera që ju e specifikuat nuk është numër.',
'htmlform-int-toolow'          => 'Vlera që ju e përcaktuat është nën minimumin e $1',
'htmlform-int-toohigh'         => 'Vlera që ju e përcaktuat është mbi maksimumin e $1',
'htmlform-required'            => 'Kjo vlerë është e nevojshme',
'htmlform-submit'              => 'Dërgo',
'htmlform-reset'               => 'Zhbëj ndryshimin',
'htmlform-selectorother-other' => 'Gjitha',

# SQLite database support
'sqlite-has-fts' => '$1 me mbështetje të kërkimit me teskt të plotë',
'sqlite-no-fts'  => '$1 pa mbështetje të kërkimit me teskt të plotë',

# Special:DisableAccount
'disableaccount'             => 'Çaktiviyoni një llogari përdoruesi',
'disableaccount-user'        => 'Përdoruesi:',
'disableaccount-reason'      => 'Arsyeja:',
'disableaccount-confirm'     => "Çaktivizoni këtë llogari të përdorusit.
Përdoruesi nuk do të mund të identifikohet, të rivendosë fjalëkalimin e tij, ose të marrë njoftime me e-mail.
Nëse përdoruesi është aktualisht i identifikuar ndokund, ai do të dalë menjëherë.
''Vini re se çaktivizimi i jë llogarie nuk mund të kthehet pa ndërhyrjen e një administratori të sistemit.''",
'disableaccount-mustconfirm' => 'Ju duhet të konfirmoni që dëshironi ta çaktivizoni këtë llogari.',
'disableaccount-nosuchuser'  => 'Llogaria e përdoruesit "$1" nuk ekziston.',
'disableaccount-success'     => 'Llogaria e përdoruesit "$1" është çaktiviyuar përgjithmonë.',
'disableaccount-logentry'    => 'u çaktivizua përgjithmonë llogaria e përdoruesit [[$1]]',

);
