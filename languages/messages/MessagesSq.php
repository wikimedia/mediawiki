<?php
/** Albanian (shqip)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andejkendej
 * @author Cradel
 * @author Dashohoxha
 * @author Dasius
 * @author Dori
 * @author Eagleal
 * @author Ergon
 * @author Euriditi
 * @author FatosMorina
 * @author Kaganer
 * @author Marinari
 * @author Mdupont
 * @author MicroBoy
 * @author Mikullovci11
 * @author Olsi
 * @author Puntori
 * @author Techlik
 * @author The Evil IP address
 * @author Urhixidur
 * @author Vinie007
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

$namespaceGenderAliases = array(
	NS_USER      => array( 'male' => 'Përdoruesi', 'female' => 'Përdoruesja' ),
	NS_USER_TALK => array( 'male' => 'Përdoruesi_diskutim', 'female' => 'Përdoruesja_diskutim' ),
);

$specialPageAliases = array(
	'Activeusers'               => array( 'PërdoruesitAktivë' ),
	'Allmessages'               => array( 'TëgjithaMesazhet' ),
	'Allpages'                  => array( 'TëgjithaFaqet' ),
	'Ancientpages'              => array( 'FaqetAntike' ),
	'Blankpage'                 => array( 'FaqeBosh' ),
	'Block'                     => array( 'BllokoIP' ),
	'Blockme'                   => array( 'BllokomMua' ),
	'Booksources'               => array( 'BurimeteLibrave' ),
	'Categories'                => array( 'Kategori' ),
	'ChangeEmail'               => array( 'NdryshoEmail' ),
	'ChangePassword'            => array( 'NdryshoFjalëkalimin' ),
	'ComparePages'              => array( 'KrahasoFaqet' ),
	'Confirmemail'              => array( 'KonfirmoEmail' ),
	'Contributions'             => array( 'Kontributet' ),
	'CreateAccount'             => array( 'HapLlogari' ),
	'DeletedContributions'      => array( 'GrisKontributet' ),
	'Emailuser'                 => array( 'EmailPërdoruesit' ),
	'Export'                    => array( 'Eksporto' ),
	'Import'                    => array( 'Importo' ),
	'Listadmins'                => array( 'RreshtoAdmin' ),
	'Listbots'                  => array( 'RreshtoBotët' ),
	'Listfiles'                 => array( 'ListaSkedave' ),
	'Listusers'                 => array( 'RreshtoPërdoruesit' ),
	'Lockdb'                    => array( 'MbyllDB' ),
	'Longpages'                 => array( 'FaqeteGjata' ),
	'Movepage'                  => array( 'LëvizFaqe' ),
	'Mycontributions'           => array( 'KontributetëMiat' ),
	'Mypage'                    => array( 'FaqjaIme' ),
	'Mytalk'                    => array( 'DiskutimiImë' ),
	'Myuploads'                 => array( 'NgarkimeteMia' ),
	'Newimages'                 => array( 'SkedaTëReja' ),
	'Newpages'                  => array( 'FaqeteReja' ),
	'Popularpages'              => array( 'FaqetëFamshme' ),
	'Preferences'               => array( 'Preferencat' ),
	'Protectedpages'            => array( 'FaqeteMbrojtura' ),
	'Protectedtitles'           => array( 'TitujteMbrojtur' ),
	'Randompage'                => array( 'Rastësishme', 'FaqeRastësishme' ),
	'Recentchanges'             => array( 'NdryshimeSëFundmi' ),
	'Search'                    => array( 'Kërkim' ),
	'Shortpages'                => array( 'FasheteShkurta' ),
	'Specialpages'              => array( 'FaqetSpeciale' ),
	'Statistics'                => array( 'Statistika' ),
	'Unblock'                   => array( 'Zhblloko' ),
	'Uncategorizedcategories'   => array( 'KategoriTëpakategorizuara' ),
	'Uncategorizedimages'       => array( 'SkedaTëpakategorizuara' ),
	'Uncategorizedpages'        => array( 'FaqeTëpakategorizuara' ),
	'Uncategorizedtemplates'    => array( 'StampaTëpakategorizuara' ),
	'Undelete'                  => array( 'Rikthe' ),
	'Unlockdb'                  => array( 'HapDB' ),
	'Unusedcategories'          => array( 'KategoriTëpapërdorura' ),
	'Unusedimages'              => array( 'SkedaTëpapërdorura' ),
	'Upload'                    => array( 'Ngarko' ),
	'Userlogin'                 => array( 'HyrjePërdoruesi' ),
	'Userlogout'                => array( 'DaljePërdoruesi' ),
	'Version'                   => array( 'Verzioni' ),
	'Wantedcategories'          => array( 'KaetgoritëeDëshiruara' ),
	'Wantedfiles'               => array( 'SkedateDëshiruara' ),
	'Wantedpages'               => array( 'FaqeteDëshiruara' ),
	'Wantedtemplates'           => array( 'StampateDëshiruara' ),
	'Whatlinkshere'             => array( 'LidhjetKëtu' ),
	'Withoutinterwiki'          => array( 'PaInterwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#RIDREJTO', '#REDIRECT' ),
	'notoc'                     => array( '0', '__JOTP__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__JOGALERI__', '__NOGALLERY__' ),
	'toc'                       => array( '0', '__TP__', '__TOC__' ),
	'noeditsection'             => array( '0', '__JOREDAKTIMSEKSIONI__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MUAJIMOMENTAL', 'MUAJIMOMENTAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'MUAJIMOMENTAL1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ),
	'currentday'                => array( '1', 'DITASOT', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DITASOT2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'EMRIIDITËSOT', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'SIVJET', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'KOHATANI', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'ORATANI', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'EMRIIMUAJITLOKAL', 'LOCALMONTHNAME' ),
	'localday'                  => array( '1', 'DITALOKALE', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'DITALOKALE2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'EMRIIDITËSLOKALE', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'VITILOKAL', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'KOHALOKALE', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'ORALOKALE', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'NUMRIFAQEVE', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NUMRIIARTIKUJVE', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NUMRIISKEDAVE', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NUMRIIPËRDORUESVE', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'NUMRIIPËRDORUESVEAKTIVË', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'NUMRIREDAKTIMEVE', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'NUMRIISHIKIMEVE', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'EMRIFAQES', 'PAGENAME' ),
	'namespace'                 => array( '1', 'HAPËSIRA', 'NAMESPACE' ),
	'fullpagename'              => array( '1', 'EMRIIPLOTËIFAQES', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'EMRIIPLOTËIFAQESE', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'EMRIINËNFAQES', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'EMRIINËNFAQESE', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'EMRIIFAQESBAZË', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'EMRIIFAQESBAZËE', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'EMRIIFAQESSËDISKUTIMIT', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'EMRIIFAQESSËDISKUTIMITE', 'TALKPAGENAMEE' ),
	'subst'                     => array( '0', 'ZËVN', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'parapamje', 'pamje', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'parapamje=$1', 'pamje=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'djathtas', 'right' ),
	'img_left'                  => array( '1', 'majtas', 'left' ),
	'img_none'                  => array( '1', 's\'ka', 'none' ),
	'img_center'                => array( '1', 'qendër', 'qendrore', 'center', 'centre' ),
	'img_framed'                => array( '1', 'i_kornizuar', 'pa_kornizë', 'kornizë', 'framed', 'enframed', 'frame' ),
	'img_page'                  => array( '1', 'faqja=$1', 'faqja $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'lartdjathtas', 'lartdjathtas=$1', 'lartdjathtas $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'kufi', 'border' ),
	'img_baseline'              => array( '1', 'linjabazë', 'baseline' ),
	'img_sub'                   => array( '1', 'nën', 'sub' ),
	'img_text_top'              => array( '1', 'tekst-top', 'text-top' ),
	'img_middle'                => array( '1', 'mes', 'middle' ),
	'img_bottom'                => array( '1', 'fund', 'bottom' ),
	'img_text_bottom'           => array( '1', 'tekst-fund', 'text-bottom' ),
	'img_link'                  => array( '1', 'lidhje=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'EMRIISAJTIT', 'SITENAME' ),
	'localurl'                  => array( '0', 'URLLOKALE', 'LOCALURL:' ),
	'server'                    => array( '0', 'SERVERI', 'SERVER' ),
	'servername'                => array( '0', 'EMRIISERVERIT', 'SERVERNAME' ),
	'grammar'                   => array( '0', 'GRAMATIKA:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'GJINIA:', 'GENDER:' ),
	'currentweek'               => array( '1', 'JAVAMOMENTALE', 'CURRENTWEEK' ),
	'plural'                    => array( '0', 'SHUMËS:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'URLEPLOTË', 'FULLURL:' ),
	'language'                  => array( '0', '#GJUHA:', '#LANGUAGE:' ),
	'numberofadmins'            => array( '1', 'NUMRIIADMINISTRUESVE', 'NUMBEROFADMINS' ),
	'special'                   => array( '0', 'speciale', 'special' ),
	'hiddencat'                 => array( '1', '__KATEGORIEFSHEHUR__', '__HIDDENCAT__' ),
	'pagesize'                  => array( '1', 'MADHËSIAEFAQES', 'PAGESIZE' ),
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
'tog-underline' => 'Nënvizo lidhjet:',
'tog-justify' => 'Rregullo paragrafët',
'tog-hideminor' => 'Fshih redaktimet e vogla në ndryshimet e fundit',
'tog-hidepatrolled' => 'Fshih redaktimet e vrojtuara në ndryshimet e fundit',
'tog-newpageshidepatrolled' => 'Fshih faqet e vrojtuara nga lista e faqeve të reja',
'tog-extendwatchlist' => "Zgjero listën e faqeve të vëzhguara që t'i tregojë të gjitha ndryshimet, jo vetëm më të fundit.",
'tog-usenewrc' => 'Përdor ndryshimet e fundit në mënyrë të zgjeruar (kërkon JavaScript)',
'tog-numberheadings' => 'Numëro automatikish titujt',
'tog-showtoolbar' => 'Trego mjetet e redaktimit (kërkon JavaScript)',
'tog-editondblclick' => 'Redakto faqet me dopio-klik (kërkon JavaScript)',
'tog-editsection' => 'Lejo redaktimin e seksioneve me anë të lidhjeve [redakto]',
'tog-editsectiononrightclick' => 'Lejo redaktimin e seksioneve duke klikuar me të djathtën mbi titullin e seksionit (kërkon JavaScript)',
'tog-showtoc' => 'Trego tabelën e përmbajtjes (për faqet me më shume se 3 tituj)',
'tog-rememberpassword' => 'Mbaj mend fjalëkalimin tim në këtë shfletues (më së shumti për $1 {{PLURAL:$1|ditë|ditë}})',
'tog-watchcreations' => 'Shtoi faqet e krijuara prej meje tek lista e faqeve që unë vëzhgoj',
'tog-watchdefault' => 'Shto faqet e redaktuara prej meje tek lista e faqeve që unë vëzhgoj',
'tog-watchmoves' => 'Shto faqet e zhvendosura prej meje tek lista e faqeve që unë vëzhgoj',
'tog-watchdeletion' => 'Shto faqet e fshira prej meje tek lista e faqeve që unë vëzhgoj',
'tog-minordefault' => 'Shëno të gjitha redaktimet si të vogla automatikisht',
'tog-previewontop' => 'Vendose kutinë e bocetit sipër kutisë së redaktimeve',
'tog-previewonfirst' => 'Tregoje bocetin në redaktimin e parë',
'tog-nocache' => "Ç'aktivizo ruajtjen e faqeve të vizituara",
'tog-enotifwatchlistpages' => 'Më njofto me e-mail kur ndryshohet një faqe nga lista ime e faqeve të vëzhguara',
'tog-enotifusertalkpages' => 'Më njofto me e-mail kur faqja ime e dikutimeve të përdoruesit ndryshohet',
'tog-enotifminoredits' => 'Më njofto me e-mail edhe kur ka redaktime të vogla në faqe',
'tog-enotifrevealaddr' => 'Tregoje adresën time të e-mail-it në e-mail-et njoftuese',
'tog-shownumberswatching' => 'Trego numrin e përdoruesve që vëzhgojnë këtë faqe',
'tog-oldsig' => 'Nënshkrimi ekzistues:',
'tog-fancysig' => 'Mbaje nënshkrimin si wikitekst (pa lidhje automatike)',
'tog-externaleditor' => 'Përdor si rregull program të jashtëm redaktimi (vetëm për ekspertë, kërkon regjistrime speciale të kompjuterit. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-externaldiff' => 'Përdor si rregull program të jashtëm diff (vetëm për ekspertë, kërkon regjistrime speciale të kompjuterit. [//www.mediawiki.org/wiki/Manual:External_editors More information.])',
'tog-showjumplinks' => 'Lejo lidhje "shko tek"',
'tog-uselivepreview' => 'Trego bocetin në mënyrë të drejtëpërdrejtë (kërkon JavaScript) (eksperimentale)',
'tog-forceeditsummary' => 'Më njofto kur e lë përmbledhjen e redaktimit bosh',
'tog-watchlisthideown' => 'Fshih redaktimet e mia nga lista e faqeve të vëzhguara',
'tog-watchlisthidebots' => 'Fshih redaktimet e robotëve nga lista e faqeve të vëzhguara',
'tog-watchlisthideminor' => 'Fshih redaktimet e vogla nga lista e faqeve të vëzhguara',
'tog-watchlisthideliu' => 'Fshih redaktimet e përdoruesve nga lista e faqeve të vëzhguara',
'tog-watchlisthideanons' => 'Fshih redaktimet përdoruesve anonim nga lista e faqeve të vëzhguara',
'tog-watchlisthidepatrolled' => 'Fshih redaktimet e vrojtuara nga lista e faqeve të vëzhguara',
'tog-ccmeonemails' => 'Më dërgo kopje të mesazheve që u dërgoj të tjerëve',
'tog-diffonly' => 'Mos trego përmbajtjen e faqes nën diff-e',
'tog-showhiddencats' => 'Trego kategoritë e fshehura',
'tog-norollbackdiff' => 'Ndryshimi pas rikthimit do të fshihet',

'underline-always' => 'Gjithmonë',
'underline-never' => 'Asnjëherë',
'underline-default' => 'Sipas rregullit në shfletues',

# Font style option in Special:Preferences
'editfont-style' => 'Zgjidh stilin e gërmave të hapsirës:',
'editfont-default' => 'Sipas rregullit në shfletues',
'editfont-monospace' => 'Gërma monospace',
'editfont-sansserif' => 'Germa Sans-serif',
'editfont-serif' => 'Gërma serif',

# Dates
'sunday' => 'E diel',
'monday' => 'E hënë',
'tuesday' => 'E martë',
'wednesday' => 'E mërkurë',
'thursday' => 'E enjte',
'friday' => 'E premte',
'saturday' => 'E shtunë',
'sun' => 'Di',
'mon' => 'Hë',
'tue' => 'Ma',
'wed' => 'Më',
'thu' => 'Enj',
'fri' => 'Pr',
'sat' => 'Sht',
'january' => 'janar',
'february' => 'shkurt',
'march' => 'mars',
'april' => 'prill',
'may_long' => 'maj',
'june' => 'qershor',
'july' => 'korrik',
'august' => 'gusht',
'september' => 'shtator',
'october' => 'tetor',
'november' => 'nëntor',
'december' => 'dhjetor',
'january-gen' => 'janar',
'february-gen' => 'shkurt',
'march-gen' => 'mars',
'april-gen' => 'prill',
'may-gen' => 'maj',
'june-gen' => 'qershor',
'july-gen' => 'korrik',
'august-gen' => 'gusht',
'september-gen' => 'shtator',
'october-gen' => 'tetor',
'november-gen' => 'nëntor',
'december-gen' => 'dhjetor',
'jan' => 'Jan',
'feb' => 'Shku',
'mar' => 'Mar',
'apr' => 'Pri',
'may' => 'Maj',
'jun' => 'Qer',
'jul' => 'Korr',
'aug' => 'Gush',
'sep' => 'Shta',
'oct' => 'Tet',
'nov' => 'Nën',
'dec' => 'Dhje',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Kategoria|Kategoritë}}',
'category_header' => 'Artikuj në kategorinë "$1"',
'subcategories' => 'Nën-kategori',
'category-media-header' => 'Skeda në kategori "$1"',
'category-empty' => "''Kjo kategori aktualisht nuk përmban asnjë faqe apo media.''",
'hidden-categories' => '{{PLURAL:$1|Kategori e fshehur|Kategori të fshehura}}',
'hidden-category-category' => 'Kategori të fshehura',
'category-subcat-count' => '{{PLURAL:$2|Kjo kategori ka vetëm këtë nën-kategori.|Kjo kategori ka {{PLURAL:$1|këtë nën-kategori|$1 këto nën-kategori}}, nga $2 gjithësej.}}',
'category-subcat-count-limited' => 'Kjo kategori ka {{PLURAL:$1|këtë nën-kategori|$1 këto nën-kategori}}.',
'category-article-count' => '{{PLURAL:$2|Kjo kategori ka vetëm këtë faqe.|Kjo kategori ka {{PLURAL:$1|këtë faqe|$1 faqe}} nga $2 gjithësej.}}',
'category-article-count-limited' => '{{PLURAL:$1|Kjo faqe është|$1 faqe janë}} në këtë kategori.',
'category-file-count' => '{{PLURAL:$2|Kjo kategori ka vetëm këtë skedë.|{{PLURAL:$1|Kjo skedë është|$1 skeda janë}} në këtë kategori, nga $2 gjithësej.}}',
'category-file-count-limited' => '{{PLURAL:$1|Kjo skedë është|$1 skeda janë}} në këtë kategori.',
'listingcontinuesabbrev' => 'vazh.',
'index-category' => 'Faqe të indeksuara',
'noindex-category' => 'Faqe jo të indeksuara',
'broken-file-category' => 'Faqet me lidhjet file thyer',

'about' => 'Rreth',
'article' => 'Artikulli',
'newwindow' => '(hapet në një dritare të re)',
'cancel' => 'Anulo',
'moredotdotdot' => 'Më shumë...',
'mypage' => 'Faqja ime',
'mytalk' => 'diskutimet',
'anontalk' => 'Diskutimet për këtë IP',
'navigation' => 'Shfleto',
'and' => '&#32;dhe',

# Cologne Blue skin
'qbfind' => 'Kërko',
'qbbrowse' => 'Shfletoni',
'qbedit' => 'Redaktoni',
'qbpageoptions' => 'Kjo faqe',
'qbpageinfo' => 'Kontekst',
'qbmyoptions' => 'Faqet e mia',
'qbspecialpages' => 'Faqet speciale',
'faq' => 'Pyetje që bëhen shpesh',
'faqpage' => 'Project: Pyetje që bëhen shpesh',

# Vector skin
'vector-action-addsection' => 'Shto një temë',
'vector-action-delete' => 'Grise',
'vector-action-move' => 'Zhvendose',
'vector-action-protect' => 'Mbroje',
'vector-action-undelete' => 'Anullo fshirjen',
'vector-action-unprotect' => 'Ndrysho mbrojtjen',
'vector-simplesearch-preference' => 'Aktivizo kërkimin e zgjeruar (vetëm për veshjen Vector)',
'vector-view-create' => 'Krijo',
'vector-view-edit' => 'Redakto',
'vector-view-history' => 'Shiko historikun',
'vector-view-view' => 'Lexo',
'vector-view-viewsource' => 'Shiko tekstin',
'actions' => 'Veprimet',
'namespaces' => 'Hapsirat e emrit',
'variants' => 'Variante',

'errorpagetitle' => 'Gabim',
'returnto' => 'Kthehuni tek $1',
'tagline' => 'Nga {{SITENAME}}',
'help' => 'Ndihmë',
'search' => 'Kërko',
'searchbutton' => 'Kërko',
'go' => 'Shko',
'searcharticle' => 'Shko',
'history' => 'Historiku i faqes',
'history_short' => 'Historiku',
'updatedmarker' => 'përditësuar që nga vizita ime e fundit',
'printableversion' => 'Version për printer',
'permalink' => 'Lidhje e përhershme',
'print' => 'Printo',
'view' => 'Shiko',
'edit' => 'Redakto',
'create' => 'Krijo',
'editthispage' => 'Redakto këtë faqe',
'create-this-page' => 'Krijoje këtë faqe',
'delete' => 'Grise',
'deletethispage' => 'Grise këtë faqe',
'undelete_short' => 'Anullo fshirjen {{PLURAL:$1|një redaktim|$1 redaktime}}',
'viewdeleted_short' => 'Shiko {{PLURAL:$1|një redaktim të fshirë|$1 redaktime të fshira}}',
'protect' => 'Mbroje',
'protect_change' => 'ndrysho',
'protectthispage' => 'Mbroje këtë faqe',
'unprotect' => 'Ndrysho mbrojtjen',
'unprotectthispage' => 'Ndrysho mbrojtjen e kësaj faqeje',
'newpage' => 'Faqe e re',
'talkpage' => 'Diskuto rreth kësaj faqeje',
'talkpagelinktext' => 'Diskuto',
'specialpage' => 'Faqe speciale',
'personaltools' => 'Mjetet e mia',
'postcomment' => 'Seksion i ri',
'articlepage' => 'Shiko faqen me përmbajtje',
'talk' => 'Diskutimet',
'views' => 'Shikime',
'toolbox' => 'Mjete',
'userpage' => 'Shiko faqen e përdoruesit',
'projectpage' => 'Shiko projekt-faqen',
'imagepage' => 'Shikoni faqen e skedës',
'mediawikipage' => 'Shiko faqen e mesazhit',
'templatepage' => 'Shiko faqen e shabllonit',
'viewhelppage' => 'Shiko faqen për ndihmë',
'categorypage' => 'Shiko faqen e kategorive',
'viewtalkpage' => 'Shiko diskutimet',
'otherlanguages' => 'Në gjuhë të tjera',
'redirectedfrom' => '(Përcjellë nga $1)',
'redirectpagesub' => 'Faqe përcjellëse',
'lastmodifiedat' => 'Kjo faqe është ndryshuar për herë te fundit më $1, në orën $2.',
'viewcount' => 'Kjo faqe është shikuar {{PLURAL:$1|një|$1 herë}} .',
'protectedpage' => 'Faqe e mbrojtur',
'jumpto' => 'Shko tek:',
'jumptonavigation' => 'lundrim',
'jumptosearch' => 'kërko',
'view-pool-error' => "Ju kërkojmë ndjesë, serverët janë të mbingarkuar për momentin.
Këtë faqe po përpiqen t'i shikojnë më shumë njerëz nga ç'është e mundur.
Ju lutemi prisni pak para se ta hapni sërish këtë faqe.

$1",
'pool-timeout' => 'Mbaroi koha duke pritur për kyçin',
'pool-queuefull' => 'Radha e proceseve është mbushur',
'pool-errorunknown' => 'Gabim i panjohur',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite' => 'Rreth {{SITENAME}}',
'aboutpage' => 'Project:Rreth',
'copyright' => 'Përmbajtja është në disponim nëpërmjet licencës $1.',
'copyrightpage' => '{{ns:project}}:Të drejtat e autorit',
'currentevents' => 'Ngjarjet aktuale',
'currentevents-url' => 'Project:Ngjarjet aktuale',
'disclaimers' => 'Shfajësimet',
'disclaimerpage' => 'Project:Shfajësimet e përgjithshme',
'edithelp' => 'Ndihmë për redaktim',
'edithelppage' => 'Help:Redaktimi',
'helppage' => 'Help:Përmbajtje',
'mainpage' => 'Faqja kryesore',
'mainpage-description' => 'Faqja kryesore',
'policy-url' => 'Project:Politika e rregullave',
'portal' => 'Portali i komunitetit',
'portal-url' => 'Project:Portali i komunitetit',
'privacy' => 'Politika e anonimitetit',
'privacypage' => 'Project:Politika e anonimitetit',

'badaccess' => 'Leje: gabim',
'badaccess-group0' => 'Nuk ju lejohet veprimi i kërkuar',
'badaccess-groups' => 'Veprimi që kërkuat lejohet vetëm nga përdorues të {{PLURAL:$2|grupit|grupeve}}: $1.',

'versionrequired' => 'Nevojitet versioni $1 i MediaWiki-it',
'versionrequiredtext' => 'Nevojitet versioni $1 i MediaWiki-it për përdorimin e kësaj faqeje. Shikoni [[Special:Version|versionin]] tuaj.',

'ok' => 'Ok',
'retrievedfrom' => 'Marrë nga "$1"',
'youhavenewmessages' => 'Ju keni $1 ($2).',
'newmessageslink' => 'mesazhe të reja',
'newmessagesdifflink' => 'ndryshimi i fundit',
'youhavenewmessagesfromusers' => 'Ju keni $1 nga {{Shumës:$3|përdorues tjetër|përdoruesit $3}} ($2).',
'youhavenewmessagesmanyusers' => 'Ju keni 1$ nga shumë përdorues (2$).',
'newmessageslinkplural' => '{{SHUMËS:1$|një porosi e re|porosi të reja}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|ndryshimi i|ndryshimet e}} fundit',
'youhavenewmessagesmulti' => 'Ju keni mesazhe të reja në $1',
'editsection' => 'redakto',
'editold' => 'redakto',
'viewsourceold' => 'shiko tekstin',
'editlink' => 'redakto',
'viewsourcelink' => 'Shiko tekstin',
'editsectionhint' => 'Redaktoni seksionin:
Edit section: $1',
'toc' => 'Përmbajtje',
'showtoc' => 'trego',
'hidetoc' => 'fshih',
'collapsible-collapse' => 'Ngushtoje',
'collapsible-expand' => 'Zgjeroje',
'thisisdeleted' => 'Shiko ose rikthe $1?',
'viewdeleted' => 'Do ta shikosh $1?',
'restorelink' => '{{PLURAL:$1|një redaktim i fshirë|$1 redaktime të fshira}}',
'feedlinks' => 'Feed:',
'feed-invalid' => 'Tipi i feed-it është i pavlefshëm.',
'feed-unavailable' => 'Syndication feeds nuk janë të mundshme',
'site-rss-feed' => '$1 RSS Feed',
'site-atom-feed' => '$1 Atom feed',
'page-rss-feed' => '"$1" RSS feed',
'page-atom-feed' => '"$1" Atom feed',
'red-link-title' => '$1 (faqja nuk ekziston)',
'sort-descending' => 'Radhit në zbritje',
'sort-ascending' => 'Radhit në ngjitje',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Artikulli',
'nstab-user' => 'Faqja e përdoruesit',
'nstab-media' => 'Media-faqe',
'nstab-special' => 'Faqe speciale',
'nstab-project' => 'Projekt-faqe',
'nstab-image' => 'Skedë',
'nstab-mediawiki' => 'Mesazh',
'nstab-template' => 'Stampa',
'nstab-help' => 'Ndihmë',
'nstab-category' => 'Kategoria',

# Main script and global functions
'nosuchaction' => 'Nuk ekziston ky veprim',
'nosuchactiontext' => 'Veprimi i specifikuar nga URL është i pavlefshëm.
Ju mund të keni bërë një gabim në shkrimin e URL-së, ose keni ndjekur një lidhje të pasaktë.
Kjo mund të vijë edhe si rezultat i një gabimi në programin e përdorur nga {{SITENAME}}.',
'nosuchspecialpage' => 'Nuk ekziston kjo faqe speciale',
'nospecialpagetext' => '<strong>Ju keni kërkuar një faqe speciale të pavlefshme.</strong> 

 Një listë e faqeve speciale të vlefshme mund të gjendet në [[Special:SpecialPages|{{int: specialpages }}]].',

# General errors
'error' => 'Gabim',
'databaseerror' => 'Gabim në databazë',
'dberrortext' => 'Ka ndodhur një gabim me pyetjen e regjistrit.
Kjo mund të ndodhi n.q.s. pyetja nuk është e vlehshme,
ose mund të jetë një yçkël e softuerit.
Pyetja e fundit që i keni bërë regjistrit ishte:
<blockquote><tt>$1</tt></blockquote>
nga funksioni "<tt>$2</tt>".
MySQL kthehu gabimin "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Ka ndodhur një gabim me sintaksën query në databazë. 
Query e fundit që i keni bërë regjistrit ishte:
"$1"
nga funksioni "$2".
MySQL kthehu gabimin "$3: $4".',
'laggedslavemode' => "'''Kujdes:''' Kjo faqe nuk mund të ketë përditësime të kohëve të fundit.",
'readonly' => 'Databaza e kyçur',
'enterlockreason' => 'Shëno arsyen e kyçjes, gjithashtu shëno se kur mund të hapet.',
'readonlytext' => 'Databaza është për momentin e kyçur, nuk lejohen ndryshime ose modifikime, ndoshta për shkak të rutinës së mirëmbajtjes. Pas përfundimit të mirëmbajtjes databaza do të hapet përsëri.

Administratori që e kyçi dha këtë arsye: $1',
'missing-article' => 'Databaza nuk gjeti dot tekstin e faqes që duhet të kishte gjetur me emër "$1" $2.

Kjo zakonisht vjen si rezultat i një diff-i të vjetëruar ose të ndonjë lidhjeje në historikun e një faqeje që është fshirë.

Nëse nuk është kjo arsyeja, ateherë ju mund të keni gjetur një gabim në program. Ju lutemi, njoftoni një [[Special:ListUsers/sysop|administrator]], tregojini URL-në problematike.',
'missingarticle-rev' => '(rishikim#: $1)',
'missingarticle-diff' => '(Diff: $1, $2)',
'readonly_lag' => "Databaza është kyçur automatikisht për t'i dhënë kohë serverëve databazë slave që të arrijnë në një nivel me serverin databazë master",
'internalerror' => 'Gabim i brendshëm',
'internalerror_info' => 'Gabim i brendshëm: $1',
'fileappenderrorread' => 'I pamundur leximi "$1" gjatë procesit append.',
'fileappenderror' => 'E pamundur kryerja e procesit append "$1" tek "$2.',
'filecopyerror' => 'I pamundur kopjimi i skedës "$1" tek "$2".',
'filerenameerror' => 'I pamundur riemërtimi i skedës "$1" në "$2".',
'filedeleteerror' => 'E pamundur fshirja e skedës "$1".',
'directorycreateerror' => 'I pamundur krijimi i direktorisë "$1".',
'filenotfound' => 'E pamundur gjetja e skedës "$1".',
'fileexistserror' => 'Skeda "$1" nuk mund të shkruhet : Skeda ekziston.',
'unexpected' => 'Vlerë e papritur: "$1"="$2".',
'formerror' => 'Gabim: Formulari nuk mund të dërgohet.',
'badarticleerror' => 'Ky veprim nuk mund të bëhet në këtë faqe.',
'cannotdelete' => 'Faqja ose skeda $1 nuk mund të fshihej.
Mund të jetë fshirë nga dikush tjetër.',
'cannotdelete-title' => 'Faqja "$1" nuk mund të fshihet',
'delete-hook-aborted' => 'Fshirja u anulua nga togëza.
Nuk jipet shpjegim.',
'badtitle' => 'Titull i pasaktë',
'badtitletext' => 'Titulli i faqes që kërkuat nuk ishte i saktë, ishte bosh, ose ishte një titull ndër-gjuhësor/inter-wiki me lidhje të pasaktë.
Mund të përmbajë një ose më shumë germa, të cilat nuk mund të përdoren në tituj.',
'perfcached' => 'nformacioni i mëposhtëm është kopje e ruajtur dhe mund të mos jetë i përditësuar. E shumta  {{PLURAL:$1|një rezultat është|$1 rezultate janë}} ruajtur në kopje.',
'perfcachedts' => 'Informacioni i mëposhtëm është një kopje e rifreskuar më $1. E shumta  {{PLURAL:$4|një rezultat është|$4 rezultate janë}} ruajtur në kopje.',
'querypage-no-updates' => "Përditësimet për këtë faqe për momentin janë të ç'aktivizuara.
Këtu informacioni nuk do të jetë i përditësuar.",
'wrong_wfQuery_params' => 'Parametrat gabim tek wfQuery()<br />
Funksioni: $1<br />
Query: $2',
'viewsource' => 'Shiko tekstin',
'viewsource-title' => 'Shiko tekstin për $1',
'actionthrottled' => 'Veprim u ndalua',
'actionthrottledtext' => 'Si masë sigurie anti-spam, është e ndaluar kryerja e shpeshtë e një veprimi brenda një hapësire kohore shumë të shkurtër. Ju kryet shumë herë të njëjtin veprim brenda një kohe shumë të shkurtër.
Ju lutemi, provojeni përsëri pas disa minutash.',
'protectedpagetext' => 'Kjo faqe është e mbrojtur dhe nuk mund të redaktohet.',
'viewsourcetext' => 'Ju mund të shikoni dhe kopjoni tekstin e kësaj faqeje:',
'viewyourtext' => "Ju mund të shikoni dhe të kopjoni tekstin e '''ndryshimeve tuaja''' tek kjo faqe:",
'protectedinterface' => 'Kjo faqe përmban tekstin e dritares së programit, për këtë arsye mbrohet për të shmangur abuzimet.',
'editinginterface' => "'''Kujdes:''' Po redaktoni një faqe që përdoret për tekstin dritares së programit. 
Ndryshimet në këtë faqe do të ndikojnë pamjen e dritares për përdoruesit e tjerë.
Për përkthime, ju lutem konsideroni përdorimin e [//translatewiki.net/wiki/Main_Page?setlang=en translatewiki.net], projektin e lokalizimit MediaWiki.",
'sqlhidden' => '(Query SQL e fshehur)',
'cascadeprotected' => 'Kjo faqe është mbrojtur nga redaktimi pasi është përfshirë në {{PLURAL:$1|faqen|faqet}} e mëposhtme që {{PLURAL:$1|është|janë}} mbrojtur sipas metodës "cascading":
$2',
'namespaceprotected' => "Nuk ju lejohet redaktimi i faqeve në hapsirën '''$1'''.",
'customcssprotected' => "Ju nuk keni leje për të redaktuar këtë faqe CSS, sepse ai përmban cilësimet personale tjetër user's.",
'customjsprotected' => "Ju nuk keni leje për të redaktuar këtë faqe JavaScript, sepse ai përmban cilësimet personale tjetër user's.",
'ns-specialprotected' => 'Faqet speciale nuk mund të redaktohen.',
'titleprotected' => "Ky titull është mbrojtur nga [[User:$1|$1]] dhe nuk mund të krijohet.
Arsyeja e dhënë është ''$2''.",
'filereadonlyerror' => 'Nuk është në gjendje që të ndryshojë skedarin "$1" sepse depoja e skedarit "$2" është në formën vetëm-lexim.

Administratori i cili e mbylli atë e dha këtë shpjegim: "$3".',
'invalidtitle-knownnamespace' => 'Titull jo i vlefshëm me hapësirën "$2" dhe teksti "$3"',
'invalidtitle-unknownnamespace' => 'Titull jo i vlefshëm me numrin e panjohur të hapësirës së emrit $1 dhe tekstit "$2"',
'exception-nologin' => 'I paqasur',
'exception-nologin-text' => 'Kjo faqe ose ky veprim ju kërkon që të qaseni në këtë wiki.',

# Virus scanner
'virus-badscanner' => "Konfiguracion i parregullt: Skaner i panjohur virusesh: ''$1''",
'virus-scanfailed' => 'skani dështoi (code $1)',
'virus-unknownscanner' => 'antivirus i pa njohur:',

# Login and logout pages
'logouttext' => "'''Ju keni dalë jashtë.''' 

 Ju mund të vazhdoni të përdorni {{SITENAME}} në mënyrë anonime, ose mund të [[Special:UserLogin|identifikoheni përsëri]] si përdoruesi i mëparshëm ose si një përdorues tjetër. 
 Kini parasysh që disa faqe mund të shfaqen sikur të ishit i identifikuar derisa të fshini ''cache''-in e shfletuesit tuaj.",
'welcomecreation' => '== Mirësevini, $1! == 
 Llogaria juaj është krijuar. 
 Mos harroni të ndryshoni [[Special:Preferences|{{SITENAME}} preferencat]] tuaja.',
'yourname' => 'Fusni nofkën tuaj',
'yourpassword' => 'Fusni fjalëkalimin tuaj',
'yourpasswordagain' => 'Fusni fjalëkalimin përsëri',
'remembermypassword' => 'Mbaj mënd fjalëkalimin tim për tërë vizitat e ardhshme (për një kohë maksimale prej $1 {{PLURAL:$1|dite|ditësh}})',
'securelogin-stick-https' => 'Qëndro i lidhur me HTTPS pas hyrjes me emrin përkatës',
'yourdomainname' => 'Faqja juaj',
'password-change-forbidden' => 'Ju nuk mund të ndryshoni fjalëkalimet në këtë wiki.',
'externaldberror' => 'Ose kishte një gabim tek regjistri i identifikimit të jashtëm, ose nuk ju lejohet të përtërini llogarinë tuaje të jashtme.',
'login' => 'Hyni',
'nav-login-createaccount' => 'Hyni ose hapni një llogari',
'loginprompt' => 'Ju duhet të mundësoni lejimin e "cookies" për të hyrë brënda në {{SITENAME}}.',
'userlogin' => 'Hyni / hapni llogari',
'userloginnocreate' => 'Hyni',
'logout' => 'Dalje',
'userlogout' => 'Dalje',
'notloggedin' => 'Nuk keni hyrë brenda',
'nologin' => "Nuk keni një llogari? '''$1'''.",
'nologinlink' => 'Hapeni',
'createaccount' => 'Hap një llogari',
'gotaccount' => "Keni një llogari? '''$1'''.",
'gotaccountlink' => 'Hyni',
'userlogin-resetlink' => 'Keni harruar të dhënat tuaja të identifikimit?',
'createaccountmail' => 'me email',
'createaccountreason' => 'Arsyeja:',
'badretype' => 'Fjalëkalimet nuk janë njësoj.',
'userexists' => 'Emri i përdoruesit që kërkuat është në përdorim. 
Zgjidhni një emër tjetër.',
'loginerror' => 'Gabim gjatë identifikimit',
'createaccounterror' => 'I pamundur krijimi i llogarisë: $1',
'nocookiesnew' => 'Llogaria e përdoruesit u krijua por ju nuk jeni identifikuar ende.
{{SITENAME}} shfrytëzon "cookies" për të identifikuar përdoruesit.
Ju nuk mundësoni lejimin e "cookies".
Ju lutemi, mundësojini ato, pastaj identifikohuni me anë të të dhënave tuaja të reja: emri i përdoruesit dhe fjalëkalimi.',
'nocookieslogin' => '{{SITENAME}} shfrytëzon "cookies" për identifikimin e përdoruesve.
You nuk lejoni shfrytëzimin e "cookies".
Ju lutemi, lejoni shfrytëzimin e "cookies" dhe provojeni përsëri.',
'nocookiesfornew' => 'Llogaria e përdoruesit nuk u krijua, pasi ne nuk mund të konfirmojmë burimin e tij.
Sigurohuni që ju lejoni shfrytëzimin e "cookies", rifreskoni këtë faqe dhe provojen përsëri.',
'noname' => 'Nuk keni dhënë një emër përdoruesi të pranueshëm.',
'loginsuccesstitle' => 'Identifikim i suksesshëm',
'loginsuccess' => "'''Ju tani jeni identifikuar tek {{SITENAME}} si \"\$1\".'''",
'nosuchuser' => 'Nuk ka ndonjë përdorues me emrin "$1".
Kontrolloni shkrimin ose [[Special:UserLogin/signup|hapni një llogari të re]].',
'nosuchusershort' => 'Nuk ka asnjë përdorues me emrin "$1".',
'nouserspecified' => 'Ju duhet të jepni një nofkë',
'login-userblocked' => 'Ky përdorues është bllokuar. Identifikimi nuk lejohet.',
'wrongpassword' => 'Fjalëkalimi që futët nuk është i saktë. Provoni përsëri!',
'wrongpasswordempty' => 'Fjalëkalimi juaj ishte bosh. Ju lutemi provoni përsëri.',
'passwordtooshort' => 'Fjalëkalimi juaj është i pavlefshëm ose tepër i shkurtër. Ai duhet të ketë së paku {{PLURAL:$1|1 shkronjë|$1 shkronja}} dhe duhet të jetë i ndryshëm nga emri i përdoruesit.',
'password-name-match' => 'Fjalëkalimi juaj duhet të jetë i ndryshëm nga emri juaj.',
'password-login-forbidden' => 'Përdorimi i kësaj nofke dhe fjalëkalimi është i ndaluar.',
'mailmypassword' => 'Më dërgo një fjalëkalim të ri tek adresa ime',
'passwordremindertitle' => 'Kërkesë për fjalëkalim të ri tek {{SITENAME}}',
'passwordremindertext' => 'Dikush (sigurisht ju, nga adresa IP adresa $1) kërkoi një fjalëkalim të ri për hyrje tek {{SITENAME}} ($4). U krijua fjalëkalimi i përkohshëm për përdoruesin "$2" dhe u dërgua tek "$3". Nëse ky ishte tentimi juaj duhet që të kyçeni dhe ndërroni fjalëkalimin tani. Fjalëkalimi juaj i përkohshëm do të skadojë {{PLURAL:$5|një dite|$5 ditësh}}.

Nëse ndokush tjetër ka bërë këtë kërkesë, ose nëse ju kujtohet fjalëkalimin dhe nuk doni që ta ndërroni, mund të e injoroni këtë porosi dhe të vazhdoni të përdorni  fjalëkalimin e vjetër.',
'noemail' => 'Regjistri nuk ka adresë për përdoruesin "$1".',
'noemailcreate' => 'Ju duhet të sigurojë një adresë e e-mailit të saktë.',
'passwordsent' => 'Një fjalëkalim i ri është dërguar tek adresa e regjistruar për "$1". Provojeni përsëri hyrjen mbasi ta keni marrë fjalëkalimin.',
'blocked-mailpassword' => 'IP adresa juaj është bllokuar , si e tillë nuk lejohet të përdor funksionin pë rikthim të fjalkalimit , në mënyrë që të parandalohet abuzimi.',
'eauthentsent' => 'Një eMail konfirmues u dërgua te adresa e dhënë.
Para se të pranohen eMail nga përdoruesit e tjerë, duhet që adressa e juaj të vërtetohet.
Ju lutemi ndiqni këshillat në eMailin e pranuar.',
'throttled-mailpassword' => "Një kujtesë e fjalëkalimit është dërguar gjatë {{PLURAL:$1|orës|$1 orëve}} të kaluara. Për t'u mbrojtur nga abuzime vetëm një kujtesë dërgohet çdo {{PLURAL:$1|orë|$1 orë}}.",
'mailerror' => 'Gabim duke dërguar postën: $1',
'acct_creation_throttle_hit' => 'Nuk lejoheni të krijoni më llogari pasi keni krijuar {{PLURAL:$1|1|$1}}.',
'emailauthenticated' => 'Adresa juaj është vërtetuar më $2 $3.',
'emailnotauthenticated' => 'Adresa juaj <strong>nuk është vërtetuar</strong> akoma prandaj nuk mund të merrni e-mail.',
'noemailprefs' => 'Detyrohet një adresë email-i për të përdorur këtë mjet.',
'emailconfirmlink' => 'Vërtetoni adresën tuaj',
'invalidemailaddress' => 'Posta elektronike nuk mund të pranohet kështu si është pasi ka format jo valid. Ju lutemi, vendoni një postë mirë të formatuar, ose zbrazeni fushën.',
'cannotchangeemail' => 'Adresat e-mail të llogarive nuk mund të ndryshohen në këtë wiki.',
'emaildisabled' => 'Kjo faqe nuk mund të dërgojë e-maila.',
'accountcreated' => 'Llogarija e Përdoruesit u krijua',
'accountcreatedtext' => 'Llogarija e Përdoruesit për $1 u krijua',
'createaccount-title' => 'Hapja e llogarive për {{SITENAME}}',
'createaccount-text' => 'Dikush ka përdorur adresën tuaj për të hapur një llogari tek {{SITENAME}} ($4) të quajtur "$2" me fjalëkalimin "$3".
Duhet të hyni brenda dhe të ndërroni fjalëkalimin tani nëse ky person jeni ju. Përndryshe shpërfilleni këtë mesazh.',
'usernamehasherror' => 'Emri i përdoruesit nuk mund të përmbajë karaktere',
'login-throttled' => 'Keni bërë shumë tentime të njëpasnjëshme në fjalëkalimin e kësaj llogarie. Ju lutemi prisni para tentimit përsëri.',
'login-abort-generic' => 'login juaj ishte i pasuksesshëm - Ndërpre',
'loginlanguagelabel' => 'Gjuha: $1',
'suspicious-userlogout' => 'Kërkesa juaj për të shkëputet u mohua sepse duket sikur është dërguar nga një shfletues të thyer ose caching proxy.',

# E-mail sending
'php-mail-error-unknown' => 'Gabim i panjohur në funksionin e postës PHP ()',
'user-mail-no-addy' => 'Provuat të dërgoni një korrespondencë pa adresë elektronike',

# Change password dialog
'resetpass' => 'Ndrysho fjalëkalimin',
'resetpass_announce' => 'Ju keni hyrë me një kod të përkohshëm.
Për të hyrë tërësisht duhet të vendosni një fjalëkalim të ri këtu:',
'resetpass_header' => 'Ndrysho fjalëkalimin e llogarisë',
'oldpassword' => 'I vjetri',
'newpassword' => 'I riu',
'retypenew' => 'I riu përsëri',
'resetpass_submit' => 'Ndrysho fjalëkalimin dhe hyni brenda',
'resetpass_success' => 'Fjalëkalimi juaj është ndryshuar me sukses! Mund të hyni brenda...',
'resetpass_forbidden' => 'Fjalëkalimet nuk mund të ndryshohen',
'resetpass-no-info' => 'Duhet të jeni i kyçur që të keni qasje direkte në këtë faqe.',
'resetpass-submit-loggedin' => 'Ndrysho fjalëkalimin',
'resetpass-submit-cancel' => 'Anulo',
'resetpass-wrong-oldpass' => 'Fjalëkalimi momental ose i përkohshëm nuk është i vlefshëm. Ndoshta tanimë me sukses keni ndërruar fjalëkalimin, ose keni kërkuar fjalëkalim të përkohshëm.',
'resetpass-temp-password' => 'Fjalëkalimi i përkohshëm:',

# Special:PasswordReset
'passwordreset' => 'Ndrysho fjalkalimin',
'passwordreset-text' => 'Plotësoni këtë formular për të marrë një këshillë e-mail të dhënat e llogarisë suaj.',
'passwordreset-legend' => 'Ndrysho fjalkalimin',
'passwordreset-disabled' => 'Rivendosja e fjalëkalimit është deaktivizuar në këtë wiki.',
'passwordreset-pretext' => '{{PLURAL:$1| | Shkruani një nga pjesët e të dhënave më poshtë}}',
'passwordreset-username' => 'Nofka:',
'passwordreset-domain' => 'Domain:',
'passwordreset-capture' => 'Dëshiron të shikosh e-mail-in që rezulton?',
'passwordreset-capture-help' => "Nëse shënoni këtë kuti, e-mail-i (dhe fjalekalimi i përkohshëm) që do t'i dërgohen përdoruesit, do të të tregohen edhe ty.",
'passwordreset-email' => 'Posta elektronike',
'passwordreset-emailtitle' => 'Detajet e llogarisë në {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Dikush (ndoshta ju, nga IP adresa $1) kërkoi një kujtesë për 
detajet e llogarisë suaj {{SITENAME}} ($4).Përdoruesi në vijim {{PLURAL:$3|llogari është|llogaritë janë}} të lidhur me këtë postë elektronike:

$2

{{PLURAL:$3|Ky fjalëkalim i përkohshëm|Këto fjalëkalime të përkohshme}} do të përfundojë për {{PLURAL:$5|një ditë|$5 ditë}}.

Ju duhet të kyçeni dhe të zgjidhni një fjalëkalim të ri tani. Nëse dikush tjetër e ka bërë këtë kërkesës, ose në qoftë se ju mbani mend fjalëkalimin tuaj origjinal, dhe ju nuk dëshirojni të ndryshoni atë, ju mund të injoroni këtë mesazh dhe do të vazhdoni përdorimin e fjalëkalimit tuaj të vjetër.',
'passwordreset-emailtext-user' => 'Përdoruesi  $1 në {{SITENAME }} ka kërkuar një kujtesë për të dhënat e llogarisë suaj për {{SITENAME }} ($4). Përdoruesi në vijim {{PLURAL: $3 | llogaria është | llogaritë janë}} të lidhur me këtë postë elektronike: 

$2

{{PLURAL: $3 | Ky fjalëkalim i përkohshëm | Këto fjalëkalime të përkohshme}} do të përfundojë në {{PLURAL: $5 | një ditë | $5 ditë}}.
Ju duhet të kyçeni dhe të zgjidhni një fjalëkalim të ri tani. Nëse dikush tjetër e ka bërë këtë kërkesës, ose në qoftë se ju mbani mend fjalëkalimin tuaj origjinal, dhe ju nuk dëshirojni të ndryshoni atë, ju mund të injoroni këtë mesazh dhe do të vazhdoni përdorimin e fjalëkalimit tuaj të vjetër.',
'passwordreset-emailelement' => 'Nofka: $1
Fjalëkalimi i përkohshëm: $2',
'passwordreset-emailsent' => 'Një korrespondencë kujtese është dërguar',
'passwordreset-emailsent-capture' => 'U dërgua një e-mail kujtesë, i cili tregohet më poshtë.',
'passwordreset-emailerror-capture' => 'U dërgua një e-mail kujtesë, i cili tregohet më poshtë, por dërgesa për tek përdoruesi qe e pamundur: $1',

# Special:ChangeEmail
'changeemail' => 'Ndrysho postën elektronike',
'changeemail-header' => 'Ndrysho llogarinë e adresës së postës elektronike',
'changeemail-text' => 'Plotësoni këtë formular për të ndryshuar adresën tuaj të postës elektronike. Ju duhet të shkruani fjalëkalimin tuaj për të konfirmuar këtë ndryshim.',
'changeemail-no-info' => 'Ju duhet të identifikoheni në mënyrë që të keni të drejtë hyrjeje në këtë faqe.',
'changeemail-oldemail' => 'Posta elektronike e aktuale:',
'changeemail-newemail' => 'Posta elektronike e re:',
'changeemail-none' => '(asgjë)',
'changeemail-submit' => 'Ndrysho postën elektronike',
'changeemail-cancel' => 'Anulo',

# Edit page toolbar
'bold_sample' => 'Stil i theksuar i tekstit',
'bold_tip' => 'Stil i theksuar i tekstit',
'italic_sample' => 'Tekst i pjerrët',
'italic_tip' => 'Tekst i pjerrët',
'link_sample' => 'Titulli i lidhjes',
'link_tip' => 'Lidhje e brendshme',
'extlink_sample' => 'http://www.example.com titulli i lidhjes',
'extlink_tip' => 'Lidhje e jashtme (most harro prefiksin http://)',
'headline_sample' => 'Titulli',
'headline_tip' => 'Titull i nivelit 2',
'nowiki_sample' => 'Vendos tekst që nuk duhet të formatohet',
'nowiki_tip' => 'Mos përdor format wiki',
'image_sample' => 'Shembull.jpg',
'image_tip' => 'Vendos një figurë',
'media_sample' => 'Shembull.ogg',
'media_tip' => 'Lidhje media-skedash',
'sig_tip' => 'Firma juaj me gjithë kohë',
'hr_tip' => 'vijë horizontale (përdoreni rallë)',

# Edit pages
'summary' => 'Përmbledhje:',
'subject' => 'Subjekt/titull:',
'minoredit' => 'Ky është një redaktim i vogël',
'watchthis' => 'Vëzhgoje këtë faqe',
'savearticle' => 'Kryej ndryshimet',
'preview' => 'Shqyrto',
'showpreview' => 'Shfaq për shqyrtim',
'showlivepreview' => 'Shqyrtim i menjëhershëm',
'showdiff' => 'Trego ndryshimet',
'anoneditwarning' => "'''Kujdes:''' Ju nuk jeni identifikuar. 
Adresa juaj IP do të regjistrohet në historinë e redaktimeve të kësaj faqeje.",
'anonpreviewwarning' => '"Ju nuk jeni identifikuar. Ruajtja e ndryshimeve do të bëjë që adresa juaj IP të regjistrohet në historikun e redaktimeve të kësaj faqeje."',
'missingsummary' => "'''Vërejtje:''' Ju nuk keni lënë shënim për redaktimet e kryera.
Nëse klikoni \"{{int:savearticle}}\" përsëri, redaktimet tuaja do të ruhen pa shënim.",
'missingcommenttext' => 'Ju lutemi bëni një koment më poshtë.',
'missingcommentheader' => "'''Kujdes:''' Ju nuk keni dhënë një titull për këtë koment.
Nëse kryeni ndryshimet redaktimi juaj do të ruhet pa titull.",
'summary-preview' => 'Shqyrto përmbledhjen:',
'subject-preview' => 'Shqyrto titullin/subjektin:',
'blockedtitle' => 'Përdoruesi është bllokuar',
'blockedtext' => "'''Llogaria juaj ose adresa e IP është bllokuar'''

Bllokimi u bë nga $1 dhe arsyeja e dhënë ishte '''$2'''.

*Fillimi i bllokimit: $8
*Skadimi i bllokimit: $6
*I bllokuari i shënjestruar: $7

Mund të kontaktoni $1 ose një nga [[{{MediaWiki:Grouppage-sysop}}|administruesit]] e tjerë për të diskutuar bllokimin.

Vini re se nuk mund t'i dërgoni email përdoruesit nëse nuk keni një adresë të saktë të dhënë tek [[Special:Preferences|parapëlqimet e përdoruesit]] ose nëse kjo është një nga mundësitë që ju është bllokuar.

Adresa e IP-së që keni është $3 dhe numri i identifikimit të bllokimit është #$5. Përfshini këto dy të dhëna në çdo ankesë.",
'autoblockedtext' => 'IP adresa juaj është bllokuar automatikisht sepse ishte përdorur nga një përdorues tjetër i cili ishte bllokuar nga $1.
Arsyeja e dhënë për këtë është:

:\'\'$2\'\'

* Fillimi i bllokimit: $8
* Kalimi i kohës së bllokimit: $6
* Zgjatja e bllokimit: $7

Ju mund të kontaktoni $1 ose një tjetër [[{{MediaWiki:Grouppage-sysop}}|administrues]] për ta diskutuar bllokimin.

Vini re : që nuk mund ta përdorni mundësinë "dërgo porosi elektronike" përveç nëse keni një postë elektronike të vlefshme të regjistruar në [[Special:Preferences|preferencat tuaja]] dhe nuk jeni bllokuar nga përdorimi i saj.

IP adresa juaj e tanishme është $3 dhe ID e bllokimit është #$5.
Ju lutemi përfshini këto detaje në të gjitha kërkesat që i bëni.',
'blockednoreason' => 'nuk është dhënë ësnje arsye',
'whitelistedittext' => 'Ju duhet të $1 për të redaktuar faqet.',
'confirmedittext' => 'Ju duhet së pari ta vërtetoni e-mail adresen para se të redaktoni. Ju lutem plotësoni dhe vërtetoni e-mailin tuaj  te [[Special:Preferences|parapëlqimet]] e juaja.',
'nosuchsectiontitle' => 'Paragrafi nuk mund të gjendet',
'nosuchsectiontext' => 'Ju po kërkoni të redaktoni një paragraf që nuk ekziston.
Mund të jetë zhvendosur ose fshirë ndërkohë që ju ishit duke parë këtë faqe.',
'loginreqtitle' => 'Kërkohet identifikim',
'loginreqlink' => 'Identifikohuni',
'loginreqpagetext' => 'Ju duhet $1 për të parë faqet e tjera.',
'accmailtitle' => 'Fjalëkalimi u dërgua.',
'accmailtext' => "Një fjalëkalim i krijuar në mënyrë të rastësishme për [[User talk:$1|$1]] u dërgua në $2.

Fjalëkalimi për këtë llogari mund të ndryshohet në faqen ''[[Special:ChangePassword|ndrysho fjalëkalimin]]'' pasi të jeni identifikuar.",
'newarticle' => '(I ri)',
'newarticletext' => "Ju keni ndjekur nje lidhje drejt një faqeje që nuk ekziston.
Për ta krijuar këtë faqe ju mund të shkruani në kutinë e mëposhtme (shih [[{{MediaWiki:Helppage}}|faqen e ndihmës]] për më shumë informacion).
Nëse ju keni mbërritur këtu gabimisht, atëherë klikoni butonin '''pas''' të shfletuesit tuaj.",
'anontalkpagetext' => "----'' Kjo është një faqe diskutimi për një përdorues anonim i cili nuk ka krijuar akoma një llogari, ose qe nuk e përdor atë. 
 Prandaj, ne duhet të përdorim adresën IP numerike për identifikimin e tij. 
Kjo adresë IP mund të përdoret nga disa përdorues.
 Në qoftë se jeni një përdorues anonim dhe mendoni se ndaj jush janë bërë komente të parëndësishme, ju lutem [[Special:UserLogin/signup|krijoni një llogari]] ose [[Special:UserLogin|identifikohuni]] për të shmangur konfuzionin në të ardhmen me përdorues të tjerë anonim .''",
'noarticletext' => 'Momentalisht nuk ka tekst në këtë faqe.
Ju mund [[Special:Search/{{PAGENAME}}|ta kërkoni këtë titull]] në faqe tjera,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} të kërkoni ngjarjet e ngjashme në regjistër],
ose [{{fullurl:{{FULLPAGENAME}}|action=edit}} të redaktoni këtë faqe]</span>.',
'noarticletext-nopermission' => 'Për momentin faqja e kërkuar është bosh.
Ju mund të [[Special:Search/{{PAGENAME}}|kërkoni këtë titiull]] në faqet e tjera, ose të <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} këtkoni regjistrat e ngjashëm]</span>, por ju nuk mundeni ta krijoni këtë faqe.',
'missing-revision' => 'Inspektimi #$1 i faqes me emrin "{{PAGENAME}}" nuk ekziston.

Kjo zakonisht shkaktuar duke ndjekur një lidhje të vjetër tek një faqe që është fshirë. Hollësitë mund të gjenden në [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} regjistrin e fshirjeve].',
'userpage-userdoesnotexist' => 'Llogaria e përdoruesit "<nowiki>$1</nowiki>" nuk është e regjistruar. 
Ju lutem kontrolloni nëse dëshironi të krijoni/redaktoni këtë faqe.',
'userpage-userdoesnotexist-view' => 'Llogaria i përdoruesit "$1" nuk është e regjistruar.',
'blocked-notice-logextract' => "Ky përdorues është  aktualisht i bllokuar.
Më poshtë mund t'i referoheni shënimit të regjistruar për bllokimin e fundit:",
'clearyourcache' => "''Shënim:''' Pas ruajtjes, juve mund t'iu duhet të anashkaloni \"cache-in\" e shfletuesit tuaj për të parë ndryshimet. 
* '''Firefox / Safari:''' Mbaj të shtypur ''Shift'' ndërkohë që klikon ''Reload'', ose shtyp ''Ctrl-F5'' ose ''Ctrl-R'' (''⌘-R'' në Mac)
* '''Google Chrome:''' Shtyp ''Ctrl-Shift-R'' (''''⌘-R'''' në Mac)
* '''Internet Explorer:''' Mbaj të shtypur ''Ctrl''  ndërkohë që klikon ''Refresh'', ose shtyp ''Ctrl-F5''
* '''Konqueror:''' Kliko ''Reload'' ose shtyp ''F5''
* '''Opera:''' Zbrazni \"cache-in\" tek ''Tools → Preferences''",
'usercssyoucanpreview' => "'''Këshillë:''' Përdorni butonin '{{int:showpreview}}' për të testuar CSS-në e re para se të ruani ndryshimet e kryera.",
'userjsyoucanpreview' => "'''Këshillë:''' Përdorni butonin '{{int:showpreview}}' për të testuar JavaScripting e ri para se të ruani ndryshimet e kryera.",
'usercsspreview' => "'''Vini re! Ju jeni duke inspektuar CSS-në si përdorues!'''
'''Nuk është ruajtur ende!'''",
'userjspreview' => "'''Vini re se kjo është vetëm një provë ose parapamje e faqes tuaj JavaScript, ajo nuk është ruajtur akoma!'''",
'sitecsspreview' => "'''Vini re! Ju jeni duke inspektuar CSS-në !'''
'''Nuk është ruajtur ende!'''",
'sitejspreview' => "'''Vini re! Ju jeni duke inspektuar këtë kod JavaScript.''' 
'''Nuk është ruajtur ende!'''",
'userinvalidcssjstitle' => "'''Kujdes:''' Nuk ka pamje të quajtur \"\$1\". Vini re se faqet .css dhe .js përdorin titull me gërma të vogla, p.sh. {{ns:user}}:Foo/vector.css, jo {{ns:user}}:Foo/Vector.css.",
'updated' => '(E ndryshuar)',
'note' => "'''Shënim:'''",
'previewnote' => "'''Vini re! Kjo faqe është vetëm për shqyrtim.'''
Ndryshimet tuaja nuk janë ruajtur ende!",
'continue-editing' => 'Vazhdo ndryshimin',
'previewconflict' => 'Kjo parapamje reflekton tekstin sipër kutisë së redaktimit siç do të duket kur të kryeni ndryshimin.',
'session_fail_preview' => "'''Ju kërkojmë ndjesë! Redaktimi juaj nuk mund të perpunohej për shkak të humbjes së të dhënave të seancës.'''
Ju lutemi, provojeni përsëri.
Nëse përsëri nuk punon, provoni të [[Special:UserLogout|dilni nga faqja]] dhe të identifikoheni serish.",
'session_fail_preview_html' => "'''Ju kërkojmë ndjesë! I pamundur përpunimi i redaktimeve tuaja për shkak të humbjes së të dhënave të seancës.'''
'' Për shkak se {{SITENAME}} ka të aktivizuar përdorimin e HTML-së së papërpunuar, teksti për shqyrtim është fshehur si masë parandaluese kundër sulmeve JavaScript.''
'''Nëse kjo është një përpjekje e sinqertë për redaktim, ju lutemi, provojeni përsëri.'''
Nëse përsëri nuk funksiono, provoni [[Special:UserLogout|të dilni nga faqja]] dhe të identifikoheni sërish.",
'token_suffix_mismatch' => "'''Redaktimi juaj nuk u pranuar pasi shërbimi juaj server  ka keqinterpretuar shenjat e pikësimit të simbolikës së redaktimit.'''
Redaktimi nuk u pranua për të parandaluar korruptimin e tekstit në faqe.
Kjo ndodh ndonjëherë kur përdoret server anonim dytësor me gabime.",
'edit_form_incomplete' => "'''Disa pjesë të formularit të redaktimit nuk arritën në server; kontrolloni edhe një herë nëse redaktimet tuaja janë të paprekura dhe provojeni përsëri.'''",
'editing' => 'Duke redaktuar $1',
'creating' => 'Duke krijuar $1',
'editingsection' => 'Duke redaktuar $1 (paragraf)',
'editingcomment' => 'Duke redaktuar (paragraf i ri) $1',
'editconflict' => 'Konflikt redaktimi: $1',
'explainconflict' => "Dikush tjetër ka ndryshuar këtë faqe që kur ju filluat redaktimin.
Hpasira e sipërme tregon tekstin e faqes siç është aktualisht.
Ndryshimet tuaja janë shfaqur në hapsirën e poshtme.
Ju duhet t'ia bashkangjisni ndryshimet tuaja teksit ekzistues.
'''Vetëm''' teksti në hapsirën e sipërme do të ruhet kur të shtypni \"{{int:savearticle}}\".",
'yourtext' => 'Teksti juaj',
'storedversion' => 'Rishikim i ruajtur',
'nonunicodebrowser' => "'''Kujdes: Shfletuesi juaj ka mospërputhje me standartin unicode.'''
Ekziston një zgjidhje për redaktimin e sigurt të faqeve: Shkronjat jo-ASCII do të duken në kutinë e redaktimit si kod heksadecimal.",
'editingold' => "'''Kujdes: Po redaktoni një version të vjetër të kësaj faqeje.'''
Në qoftë se e ruani, çdo ndryshim i bërë deri tani do të humbasë.'''",
'yourdiff' => 'Ndryshimet',
'copyrightwarning' => "Ju lutemi, vini re! Të gjitha kontributet në {{SITENAME}} jepen për publikim sipas $2 (shiko $1 për më shumë detaje).
Nëse ju nuk dëshironi që shkrimet tuaja të redaktohen pa mëshirë dhe të shpërndahen sipas dëshirës, atëherë mos i vendosni këtu.<br />
Gjithashtu, ju po na premtoni ne që gjithçka e keni shkruar vetë, ose e keni kopjuar nga një domain publik ose nga burime të tjera  te hapura.
'''Mos vendosni material të mbrojtur nga e drejta e autorit pa leje!'''",
'copyrightwarning2' => "Ju lutemi, vini re! Të gjitha kontributet në {{SITENAME}} mund të redaktohen, ndryshohen ose hiqen nga përdorues të tjerë  (shiko $1 për më shumë detaje). 
Nëse ju nuk dëshironi që shkrimet tuaja të redaktohen pa mëshirë dhe të shpërndahen sipas dëshirës, atëherë mos i vendosni këtu<br />
Gjithashtu, ju po na premtoni ne që gjithçka e keni shkruar vetë, ose e keni kopjuar nga një domain publik ose nga burime të tjera  te hapura.
'''Mos vendosni material të mbrojtur nga e drejta e autorit pa leje!'''",
'longpageerror' => "'''Gabim: Teksti që shkruat është  {{PLURAL:$1|një kilobajt|$1 kilobajt}} i gjatë, që është mëtepër se maksimumi i lejuar prej  {{PLURAL:$2|një kilobajt|$2 kilobajtësh}} .'''
Nuk mund të ruhet.",
'readonlywarning' => "'''Kujdes: Baza e të dhënave është mbyllur për mirëmbajtje, prandaj ju nuk do të mund të ruani redaktimin tuaj për momentin.'''
Ju mund të kopjoni tekstin dhe ta ruani për më vonë në një dokument tjetër.'''

Administruesi që e bllokoi ka dhënë këtë sqarim: $1.",
'protectedpagewarning' => "'''KUJDES: Kjo faqe është e mbrotjur dhe mund të redaktohet nga përdorues me të drejta administratori.'''
Shënimi i fundit në regjistër është paraqitur më poshtë për reference:",
'semiprotectedpagewarning' => "'''Shënim:''' Kjo faqe është e mbrojtur dhe mund të redaktohet vetëm nga përdorues të regjistruar.
Shënimi i fundit në regjistër është paraqitur më poshtë për reference:",
'cascadeprotectedwarning' => "'''Vini re:''' Kjo faqe është e mbrojtur dhe vetëm përdoruesit me privilegje administrative mund ta redaktojnë pasi është përfshirë në mbrotjen \"ujëvarë\" të {{PLURAL:\$1|faqes së|faqeve të}} mëposhtme:",
'titleprotectedwarning' => "'''Kujdes:  Kjo faqe është e mbrojtur dhe vetëm [[Special:ListGroupRights|disa përdorues]] mund ta krijojnë.'''
Regjistri më i vonshëm i hyrjeve është poshtë për referncë:",
'templatesused' => '{{PLURAL:$1|Stamp|Stampa}} të përdorura në këtë faqe:',
'templatesusedpreview' => '{{PLURAL:$1|Stamp|Stampa}} të përdorë në këtë parapâmje:',
'templatesusedsection' => '{{PLURAL:$1|Stamp|Stampa}} e përdoruna në këtë sekcion:',
'template-protected' => '(mbrojtur)',
'template-semiprotected' => '(gjysëm-mbrojtur)',
'hiddencategories' => 'Kjo faqe është nën {{PLURAL:$1|një kategori të fshehur|$1 kategori të fshehura}}:',
'edittools' => '<!-- Teksti këtu do të tregohet poshtë kutive të redaktimit dhe ngarkimit të skedave. -->',
'nocreatetitle' => 'Krijimi i faqeve të reja është i kufizuar.',
'nocreatetext' => 'Mundësia për të krijuar faqe të reja është kufizuar. Duhet të [[Special:UserLogin|hyni ose të hapni një llogari]] për të krijuar faqe të reja, ose mund të ktheheni mbrapsh dhe të redaktoni një faqe ekzistuese.',
'nocreate-loggedin' => 'Nuk ju lejohet të krijoni faqe të reja.',
'sectioneditnotsupported-title' => 'Redaktimi i pjesës nuk është i mbështetur',
'sectioneditnotsupported-text' => 'Redaktimi nuk është i mbështetur në këtë faqe.',
'permissionserrors' => 'Gabime privilegjesh',
'permissionserrorstext' => 'Nuk keni leje për të bërë këtë veprim për {{PLURAL:$1|këtë arsye|këto arsye}}:',
'permissionserrorstext-withaction' => 'Ju nuk keni leje për $2, për {{PLURAL:$1|këtë arsye|këto arsye}}:',
'recreate-moveddeleted-warn' => "'''Kujdes: Po rikrijoni një faqe që është grisur më parë.'''

Mendohuni nëse dëshironi të vazhdoni me veprimin tuaj në këtë faqe.
Regjistri i grisjes për këtë faqe jepet më poshtë:",
'moveddeleted-notice' => 'Kjo faqe është grisur. Të dhënat e grisjes për këtë faqe gjenden më poshtë, për referencë.',
'log-fulllog' => 'Shihe ditaret të plota',
'edit-hook-aborted' => 'Redaktimi u ndërpre nga një goditje.
Nuk dha asnjë shpjegim.',
'edit-gone-missing' => 'Faqja nuk mund t freskohet.
Duket se është grisur.',
'edit-conflict' => 'Konflikt në redaktim.',
'edit-no-change' => 'Redaktimi juaj është anashkaluar pasi që asnjë ndryshim nuk u bë në tekst.',
'edit-already-exists' => 'Faqja nuk mundej të hapet.
Ajo tanimë ekziston.',
'defaultmessagetext' => 'Teksti i porosisë së parazgjedhur',

# Parser/template warnings
'expensive-parserfunction-warning' => 'Kujdes: Kjo faqe ka shumë kërkesa që kërkojnë analizë gramatikore të kushtueshme për sistemin.

Duhet të ketë më pakë se $2, {{PLURAL:$2|kërkesë|kërkesa}}, kurse tani {{PLURAL:$1|është $1 kërkesë|janë $1 kërkesa}}.',
'expensive-parserfunction-category' => 'Faqe me shumë shprehje të kushtueshmë për analizë gramatikore',
'post-expand-template-inclusion-warning' => "'''Kujdes''': Numri i shablloneve që perfshihen është shumë i madh.
Disa shabllone nuk do të përfshihen.",
'post-expand-template-inclusion-category' => 'Faqe ku stampat e përfshira kalojnë kufirin',
'post-expand-template-argument-warning' => "Vini re: Kjo faqe ka të paktën një parametër stampe që është shumë i madh për t'u shpalosur.
Këto parametra nuk janë përfshirë.",
'post-expand-template-argument-category' => 'Faqe që kanë parametra stampe të papërfshira',
'parser-template-loop-warning' => 'Shablloni nuk mund të therrasë vetveten: [[$1]]',
'parser-template-recursion-depth-warning' => 'Ka kaluar kufiri i herëve që shablloni mund të thërrasë vetveten: ($1)',
'language-converter-depth-warning' => 'Konvertimi i gjuhës ka kaluar limitin e lejuar: ($1)',

# "Undo" feature
'undo-success' => 'Redaktimi nuk mund të zhbëhet. 
Ju lutemi, kontrolloni krahasimin e mëposhtëm për të vërtetuar nëse kjo është ajo që dëshironi dhe pastaj kryeni ndryshimet për të plotësuar zhbërjen e redaktimit.',
'undo-failure' => 'Redaktimi nuk mund të zhbëhet për shkak të redaktimeve konfliktuese të ndërmjetshme.',
'undo-norev' => 'Redaktimi nuk mund të zhbëhet sepse nuk ekziston ose është fshirë.',
'undo-summary' => 'Zhbëje versionin $1 i bërë nga [[Special:Contributions/$2|$2]] ([[User talk:$2|ligjëratë]])',

# Account creation failure
'cantcreateaccounttitle' => 'Nuk mundet të krijohet llogaria',
'cantcreateaccount-text' => "Hapja e llogarive nga kjo adresë IP ('''$1''') është bllokuar nga [[User:$3|$3]].

Arsyeja e dhënë nga $3 është ''$2''.",

# History pages
'viewpagelogs' => 'Shiko regjistrat për këtë faqe',
'nohistory' => 'Nuk ka histori redaktimesh për këtë faqe.',
'currentrev' => 'Versioni i tanishëm',
'currentrev-asof' => 'Versioni momental që nga $1',
'revisionasof' => 'Versioni i $1',
'revision-info' => 'Versioni më $1 nga $2',
'previousrevision' => '← Version më i vjetër',
'nextrevision' => 'Version më i ri →',
'currentrevisionlink' => 'shikoni versionin e tanishëm',
'cur' => 'tani',
'next' => 'Vijo',
'last' => 'mëparshme',
'page_first' => 'I parë',
'page_last' => 'Së fundmi',
'histlegend' => 'Legjenda: (tani) = ndryshimet me versionin e tanishëm,
(fund) = ndryshimet me versionin e parardhshëm, V = redaktim i vogël',
'history-fieldset-title' => 'Shfleto historikun',
'history-show-deleted' => 'Vetëm versionet të grisur',
'histfirst' => 'Së pari',
'histlast' => 'Së fundmi',
'historysize' => '({{PLURAL:$1|1 B|$1 B}})',
'historyempty' => '(bosh)',

# Revision feed
'history-feed-title' => 'Historiku i versioneve',
'history-feed-description' => 'Historiku i versioneve për këtë faqe në wiki',
'history-feed-item-nocomment' => '$1 tek $2',
'history-feed-empty' => 'Faqja që kërkuat nuk ekziston. Ajo mund të jetë grisur nga wiki ose mund të jetë zhvendosur nën një emër tjetër. Mund të provoni ta gjeni duke e [[Special:Search|kërkuar]].',

# Revision deletion
'rev-deleted-comment' => '(Edit përmbledhje larguar)',
'rev-deleted-user' => '(përdoruesi u largua)',
'rev-deleted-event' => '(veprimi në regjistër është hequr)',
'rev-deleted-user-contribs' => '[Përdoruesi ose adresa IP u hoq - redaktimet e  fshehura nga kontribuesit]',
'rev-deleted-text-permission' => "Versioni i kësaj faqeje është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete={{FULLPAGENAME}}}} regjistri i grisjeve].",
'rev-deleted-text-unhide' => "Ky version i faqes është '''grisur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-suppressed-text-unhide' => "Ky version i faqes është '''grisur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-deleted-text-view' => "Ky version i faqes është '''grisur'''. 
Ju mund ta shikoni; detajet mund të gjenden te [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i grisjeve].",
'rev-suppressed-text-view' => "Ky version i faqes është '''shtypur'''. 
Ju mund ta shikoni; detajet mund të gjenden te [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i shtypjeve].",
'rev-deleted-no-diff' => "Ju nuk mund ta shikoni këtë ndryshim sepse një nga versionet është '''fshirë'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete={{FULLPAGENAME}}}} regjistri i grisjeve].",
'rev-suppressed-no-diff' => "Ju nuk mund ta shikoni këtë ndryshim sepse një nga versionet është '''grisur'''.",
'rev-deleted-unhide-diff' => "Një nga versionet e këtij ndryshimi është '''grisur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAME}}}} regjistri i grisjeve].
Ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-suppressed-unhide-diff' => "Një nga versionet e këtij ndryshimi është '''shtypur'''.
Detajet mund të gjenden tek [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAME}}}} regjistri i shtypjeve].
Ju akoma mund ta [$1 shikoni këtë version] nëse doni të vazhdoni.",
'rev-deleted-diff-view' => "Një nga versionet e këtij ndryshimi është '''grisur'''.
Ju mund ta shikoni këtë ndryshim; detajet mund të gjenden te [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} regjistri i grisjeve].",
'rev-suppressed-diff-view' => "Një nga versionet e këtij ndryshimi është '''shtypur'''.
Ju mund ta shikoni këtë ndryshim; detajet mund të gjenden te [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} regjistri i shtypjeve].",
'rev-delundel' => 'trego/fshih',
'rev-showdeleted' => 'Trego',
'revisiondelete' => 'Shlyj/Reparo versionet',
'revdelete-nooldid-title' => 'Version i dëshiruar i pavfleshëm',
'revdelete-nooldid-text' => 'Ose nuk keni përcaktuar një version(e) të dëshiruar për veprimin, ose versioni nuk ekziston, ose po mundoheni të fshihni versionin e tanishëm.',
'revdelete-nologtype-title' => 'Nuk është dhënë asnjë lloj i të dhënave',
'revdelete-nologtype-text' => 'Nuk keni caktuar llojin e të dhënave për të realizuar veprimin.',
'revdelete-nologid-title' => 'Regjistër i pavlefshëm',
'revdelete-nologid-text' => 'Ju ose nuk keni specifikuar një ngjarje target kyçje për të kryer këtë funksion ose hyrja e specifikuar nuk ekziston.',
'revdelete-no-file' => 'Skeda e dhënë nuk ekziston.',
'revdelete-show-file-confirm' => 'Jeni i/e sigurt se dëshironi të shikoni një version të grisur të skedës "<nowiki>$1</nowiki>" nga $2 tek $3?',
'revdelete-show-file-submit' => 'Po',
'revdelete-selected' => "'''{{PLURAL:$2|Versioni i zgjedhur i|Versionet e zgjedhura të}} [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Veprimi i zgjedhur në regjistër|Veprimet e zgjedhura në regjistër}}:'''",
'revdelete-text' => "'''Përmbajtja dhe pjesët e tjera nuk janë të dukshme për të gjithë, por figurojnë në historikun e versioneve.''' Administratorët munden përmbajtjen e larguar ta shikojnë dhe restaurojnë, përveç në rastet kur një gjë e tillë është ndaluar ekstra.",
'revdelete-confirm' => 'Ju lutem konfirmoni që keni ndër mënd ta bëni këtë, që i kuptoni pasojat, dhe që ju po veproni në përputhje me [[{{MediaWiki:Policy-url}}|politiken]].',
'revdelete-suppress-text' => "Shuarje duhet'''vetëm'''të përdoret për rastet e mëposhtme: 
 * Potencialisht e informacionit shpifës 
 * Informacion i pa kriter personal 
 *: Adresat në shtëpi''dhe numrat e telefonit, numrat e sigurimeve shoqërore, etj''",
'revdelete-legend' => 'Vendosni kufizimet për versionin:',
'revdelete-hide-text' => 'Fshihe tekstin e versionit',
'revdelete-hide-image' => 'Fshih përmbajtjen skedës',
'revdelete-hide-name' => 'Fshihe veprimin dhe shënjestrën',
'revdelete-hide-comment' => 'fshih komentin e redaktimit',
'revdelete-hide-user' => 'Fshihe emrin/IP-në të redaktuesit',
'revdelete-hide-restricted' => 'Ndalo të dhëna nga administrues si dhe të tjerë',
'revdelete-radio-same' => '(Mos ndryshoni)',
'revdelete-radio-set' => 'Po',
'revdelete-radio-unset' => 'Jo',
'revdelete-suppress' => 'Ndalo të dhëna nga administrues si dhe të tjerë',
'revdelete-unsuppress' => 'Hiq kufizimet nga versionet e restauruara',
'revdelete-log' => 'Arsyeja:',
'revdelete-submit' => 'Aplikoni tek {{PLURAL:$1|revision|versionet}} e zgjedhura',
'revdelete-success' => "'''Dukshmëria e versioneve u vendos me sukses.'''",
'revdelete-failure' => "' ' 'Dukshmëria e rivizionit nuk mund të përditëohet\"
\$1",
'logdelete-success' => "'''Dukshmëria e regjistrave u vendos me sukses.'''",
'logdelete-failure' => "'''Dukshmëria nuk u vendos:'''
$1",
'revdel-restore' => 'Ndrysho dukshmërinë',
'revdel-restore-deleted' => 'fshij rivizonet',
'revdel-restore-visible' => 'rivizionet e dukshme',
'pagehist' => 'Historiku i faqes',
'deletedhist' => 'Historiku i grisjeve',
'revdelete-hide-current' => 'Gabim në fshehje të pikës me datë $2, $1: ky është rivizioni i tanishëm. 
Nuk mund të fshihet',
'revdelete-show-no-access' => 'Gabim gjatë shfaqjes së artikullit të datës $2, $1: ky artikull ka qenë i shënuar si "i kufizuar".
Ju nuk keni akses.',
'revdelete-modify-no-access' => 'Gabim gjatë modifikimit të artikullit të datës $2, $1: ky artikull ka qenë i shënuar si "i kufizuar".
Ju nuk keni akses.',
'revdelete-modify-missing' => 'Gabim gjatë modifikimit të artikullit ID $1: ai nuk është në bazën e të dhënave!',
'revdelete-no-change' => "'''Kujdes:''' artikulli i datës $2, $1 e ka kërkesën e parametrit të dukshmërisë.",
'revdelete-concurrent-change' => 'Gabim gjatë modifikimit të artikullit të datës $2, $1: statusi i tij duket të jetë ndryshuar nga dikush tjetër kur ju po provonit ta modifikonit.
Ju lutemi kontrolloni regjistrat.',
'revdelete-only-restricted' => 'Gabim gjatë fshehjes së artikullit të datës $2, $1: ju nuk mund të fshihni artikuj nga pamja e administratorëve pa zgjedhur gjithashtu një nga opsionet e tjera të dukshmërisë.',
'revdelete-reason-dropdown' => '* Arsye grisjeje e përbashkët
** Shkelje të të drejtave të autorit
** Informacion pa  kriter personal
** Potencialisht informacion shfipës',
'revdelete-otherreason' => 'Arsye tjetër/shtesë:',
'revdelete-reasonotherlist' => 'Arsye tjetër',
'revdelete-edit-reasonlist' => 'Arsye grisjeje për redaktimet',
'revdelete-offender' => 'Versioni i autorit',

# Suppression log
'suppressionlog' => 'Regjistri i ndalimeve',
'suppressionlogtext' => 'Më poshtë është një listë e grisjeve dhe bllokimeve duke përfshirë përmbajtjen e fshehur nga administratorët.
Shiko [[Special:BlockList|listën e bllokimeve IP]] për listën e përjashtimeve operacionale dhe bllokimeve aktuale.',

# History merging
'mergehistory' => 'Bashko historikët e faqeve',
'mergehistory-header' => 'Kjo faqe ju lejon bashkimin e versionet e historikut të një faqeje "burim" në një faqe "mbledhje".
Sigurohuni që ky ndryshim do të ruajë rrjedhshmërinë e historikut të faqes.',
'mergehistory-box' => 'Bashkoni versionet e dy faqeve:',
'mergehistory-from' => 'Faqja burim:',
'mergehistory-into' => 'Faqja mbledhëse:',
'mergehistory-list' => 'Historik redaktimi i bashkueshëm',
'mergehistory-merge' => 'Versionet vijuese të [[:$1]] mund të bashkohen në [[:$2]].
Zgjidhni butonin rrethor në kolonë për të bashkuar vetëm versionet e krijuara aty dhe më parë kohës së përzgjedhur.
Kini kujdes se përdorimi i lidhjeve të shfletimit do të ndryshojë përzgjedhjen tuaj.',
'mergehistory-go' => 'Trego redaktimet e bashkueshme',
'mergehistory-submit' => 'Bashko versionet',
'mergehistory-empty' => 'Nuk ka versione të bashkueshme.',
'mergehistory-success' => '$3 {{PLURAL:$3|version|versione}} të [[:$1]] janë bashkuar me sukses në [[:$2]].',
'mergehistory-fail' => 'Nuk munda të bashkoj historikun, ju lutem kontrolloni përzgjedhjen e faqes dhe të kohës.',
'mergehistory-no-source' => 'Faqja e burimit $1 nuk ekziston.',
'mergehistory-no-destination' => 'Faqja mbledhëse $1 nuk ekzsiton.',
'mergehistory-invalid-source' => 'Faqja e burimit duhet të ketë titull të vlefshëm.',
'mergehistory-invalid-destination' => 'Faqja mbledhëse duhet të ketë titull të vlefshëm.',
'mergehistory-autocomment' => 'U bashkua [[:$1]] në [[:$2]]',
'mergehistory-comment' => 'U bashkua [[:$1]] në [[:$2]]: $3',
'mergehistory-same-destination' => 'Burimi dhe faqja e përcaktimit nuk mund të jenë të njëjta',
'mergehistory-reason' => 'Arsyeja:',

# Merge log
'mergelog' => 'Regjistri i bashkimeve',
'pagemerge-logentry' => 'u bashkua [[$1]] në [[$2]] (versione deri më $3)',
'revertmerge' => 'Ndaj',
'mergelogpagetext' => 'Më poshtë jepet një listë e bashkimeve së fundmi nga historiku i një faqeje në historikun e një faqeje tjetër.',

# Diffs
'history-title' => 'Historiku i redaktimeve të "$1"',
'difference-title' => 'Ndryshimi mes inspektimeve të "$1"',
'difference-title-multipage' => 'Ndryshimi mes faqeve "$1" dhe "$2"',
'difference-multipage' => '(Ndryshimi midis faqeve)',
'lineno' => 'Rreshti $1:',
'compareselectedversions' => 'Krahasoni versionet e zgjedhura',
'showhideselectedversions' => 'Shfaq/fshih versionet e zgjedhura',
'editundo' => 'zhbëje',
'diff-multi' => '({{PLURAL:$1|Një version i ndërmjetshëm|$1 versione të ndërmjetshme}} nga {{PLURAL:$2|një përdorues|$2 përdorues}} i/të pashfaqur)',
'diff-multi-manyusers' => '({{PLURAL:$1|Një versioni i ndërmjetshëm|$1 versione të ndërmjetshme}} nga më shumë se $2 {{PLURAL:$2|përdorues|përdorues}} i/të pashfaqur)',

# Search results
'searchresults' => 'Rezultatet e kërkimit',
'searchresults-title' => 'Rezultatet e kërkimit për "$1"',
'searchresulttext' => 'Për më shumë informacion rreth kërkimit në {{SITENAME}} shikoni [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Kërkuat për "[[$1]]" ([[Special:Prefixindex/$1|të gjitha faqet që fillojnë me "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|të gjitha faqet që lidhen me"$1"]])',
'searchsubtitleinvalid' => 'Kërkim për "$1"',
'toomanymatches' => 'Ky kërkim ka shumë përfundime, provoni një pyetje tjetër më përcaktuese',
'titlematches' => 'Tituj faqesh që përputhen',
'notitlematches' => 'Nuk ka asnjë titull faqeje që përputhet',
'textmatches' => 'Tekst faqesh që përputhet',
'notextmatches' => 'Nuk ka asnjë tekst faqeje që përputhet',
'prevn' => '{{PLURAL:$1|$1}} më para',
'nextn' => '{{PLURAL:$1|$1}} më pas',
'prevn-title' => '$1 i mëparshëm {{PLURAL:$1|rezultat|rezultate}}',
'nextn-title' => '$1 në vazhdim {{PLURAL:$1|rezultat|rezultate}}',
'shown-title' => 'Trego $1 {{PLURAL:$1|rezultat|rezultate}} për faqe',
'viewprevnext' => 'Shikoni ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Parazgjedhjet e kërkimit',
'searchmenu-exists' => "'''Në këtë wiki kjo faqe është emëruar \"[[:\$1]]\"'''",
'searchmenu-new' => "'''Hapë faqen \"[[:\$1]]\" në këtë wiki!'''",
'searchhelp-url' => 'Help:Ndihmë',
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Shfletoi faqet me këtë parashtesë]]',
'searchprofile-articles' => 'Përmbajtja e faqeve',
'searchprofile-project' => 'Ndihmë dhe faqet e Projektit',
'searchprofile-images' => 'Multimedia',
'searchprofile-everything' => 'Gjithçka',
'searchprofile-advanced' => 'Avancuar',
'searchprofile-articles-tooltip' => 'Kërko në $1',
'searchprofile-project-tooltip' => 'Kërko në $1',
'searchprofile-images-tooltip' => 'Kërko skedarë',
'searchprofile-everything-tooltip' => 'Kërko gjithë përmbajtjen (duke përfshirë edhe faqet e diskutimit)',
'searchprofile-advanced-tooltip' => 'Kërkimi në hapësina',
'search-result-size' => '$1 ({{PLURAL:$2|1 fjalë|$2 fjalë}})',
'search-result-category-size' => '{{PLURAL:$1|1 anëtar|$1 anëtarë}} ({{PLURAL:$2|1 nën-kategori|$2 nën-kategori}}, {{PLURAL:$3|1 skedë|$3 skeda}})',
'search-result-score' => 'Përkatësia: $1%',
'search-redirect' => '(përcjellim $1)',
'search-section' => '(seksioni $1)',
'search-suggest' => 'Mos kishit në mendje: $1',
'search-interwiki-caption' => 'Projekte simotra',
'search-interwiki-default' => '$1 përfundime:',
'search-interwiki-more' => '(më shumë)',
'search-relatedarticle' => 'Të ngjashme',
'mwsuggest-disable' => 'Çmundësoi sugjerimet AJAX',
'searcheverything-enable' => 'Kërko në të gjitha hapësirat',
'searchrelated' => 'të ngjashme',
'searchall' => 'të gjitha',
'showingresults' => "Më poshtë tregohen {{PLURAL:$1|'''1''' përfundim|'''$1''' përfundime}} duke filluar nga #'''$2'''.",
'showingresultsnum' => "Më poshtë tregohen {{PLURAL:$3|'''1''' përfundim|'''$3''' përfundime}} duke filluar nga #'''$2'''.",
'showingresultsheader' => "{{PLURAL:$5|Rezultati '''$1''' nga '''$3'''|Rezultatet '''$1 - $2''' nga '''$3'''}} për '''$4'''",
'nonefound' => "'''Shënim''': Kërkimet pa rezultate ndodhin kur kërkoni për fjalë që rastisen shpesh si \"ke\" dhe \"nga\", të cilat nuk janë të futura në regjistër, ose duke dhënë më shumë se një fjalë (vetëm faqet që i kanë të gjitha ato fjalë do të tregohen si rezultate).",
'search-nonefound' => 'Nuk ka rezultate që përputhen me kërkesën.',
'powersearch' => 'Kërko',
'powersearch-legend' => 'Kërkim i përparuar',
'powersearch-ns' => 'Kërkim në hapësira:',
'powersearch-redir' => 'Trego përcjellimet',
'powersearch-field' => 'Kërko për',
'powersearch-togglelabel' => 'Zgjedh:',
'powersearch-toggleall' => 'Tâna',
'powersearch-togglenone' => 'Asnji',
'search-external' => 'Kërkim i jashtëm',
'searchdisabled' => '<p>Kërkimi me tekst të plotë është bllokuar tani për tani ngaqë shërbyesi është shumë i ngarkuar; shpresojmë ta nxjerrim prapë në gjendje normale pas disa punimeve. Deri atëherë mund të përdorni Google-in për kërkime:</p>',

# Quickbar
'qbsettings' => 'Vendime të shpejta',
'qbsettings-none' => 'Asnjë',
'qbsettings-fixedleft' => 'Lidhur majtas',
'qbsettings-fixedright' => 'Lidhur djathtas',
'qbsettings-floatingleft' => 'Pezull majtas',
'qbsettings-floatingright' => 'Pezull djathtas',
'qbsettings-directionality' => 'Fikse, në varësi të skriptës së drejtuar në gjuhën tuaj',

# Preferences page
'preferences' => 'Parapëlqimet',
'mypreferences' => 'Parapëlqimet',
'prefs-edits' => 'Numri i redaktimeve:',
'prefsnologin' => 'Nuk keni hyrë brenda',
'prefsnologintext' => 'Duhet të jeni <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} të kyçur]</span> për të caktuar parapëlqimet e përdoruesit.',
'changepassword' => 'Ndërroni fjalëkalimin',
'prefs-skin' => 'Pamja',
'skin-preview' => 'Parapamje',
'datedefault' => 'Parazgjedhje',
'prefs-beta' => 'Karakteristikat Beta',
'prefs-datetime' => 'Data dhe Ora',
'prefs-labs' => 'Karakteristikat laboratorik',
'prefs-user-pages' => 'Faqet e përdoruesit',
'prefs-personal' => 'Përdoruesi',
'prefs-rc' => 'Ndryshime së fundmi',
'prefs-watchlist' => 'Lista mbikqyrëse',
'prefs-watchlist-days' => 'Numri i ditëve të treguara tek lista mbikqyrëse:',
'prefs-watchlist-days-max' => 'Maksimumi $1 ditë',
'prefs-watchlist-edits' => 'Numri i redaktimeve të treguara tek lista mbikqyrëse e zgjeruar:',
'prefs-watchlist-edits-max' => 'Numri maksimal: 1000',
'prefs-watchlist-token' => 'Lista mbikqyrëse shenjë:',
'prefs-misc' => 'Të ndryshme',
'prefs-resetpass' => 'Ndrysho fjalëkalimin',
'prefs-changeemail' => 'Ndrysho postën elektronike',
'prefs-setemail' => 'Vendos adresën e postës elektronike',
'prefs-email' => 'Opsionet E-mail',
'prefs-rendering' => 'Dukja',
'saveprefs' => 'Ruaj parapëlqimet',
'resetprefs' => 'Rikthe parapëlqimet',
'restoreprefs' => 'Rikthe të gjitha të dhënat e mëparshme',
'prefs-editing' => 'Redaktimi',
'prefs-edit-boxsize' => 'Madhësia e dritares së redaktimit.',
'rows' => 'Rreshta:',
'columns' => 'Kollona:',
'searchresultshead' => 'Kërkimi',
'resultsperpage' => 'Sa përputhje të tregohen për faqe:',
'stub-threshold' => 'Kufiri për formatin e <a href="#" class="stub">lidhjeve cung</a> (B):',
'stub-threshold-disabled' => 'Çaktivizuar',
'recentchangesdays' => 'Numri i ditëve të treguara në ndryshime së fundmi:',
'recentchangesdays-max' => '(maksimum $1 {{PLURAL:$1|dit|ditë}})',
'recentchangescount' => 'Numri i redaktimeve për të treguar:',
'prefs-help-recentchangescount' => 'Kjo përfshin ndryshimet e freskëta, historikun e faqes dhe regjistrat.',
'prefs-help-watchlist-token' => 'Plotësimi në këtë fushë me një kyç të fshehtë do të gjenerojë një RSS për të listës mbikqyrëse tuaj. 
 Kushdo që e di të rëndësishme në këtë fushë do të jetë në gjendje për të lexuar lista mbikqyrëse e juaj, kështu që zgjidhni një vlerë të sigurt. 
 Këtu ka një vlerë të rastësishme-generated ju mund të përdorni: $1',
'savedprefs' => 'Parapëlqimet tuaja janë ruajtur.',
'timezonelegend' => 'Zona kohore:',
'localtime' => 'Ora lokale:',
'timezoneuseserverdefault' => 'wiki default Përdorimi ( $1 )',
'timezoneuseoffset' => 'Tjera (zgjidh rajonin)',
'timezoneoffset' => 'Ofset¹:',
'servertime' => 'Ora e shërbyesit:',
'guesstimezone' => 'Gjeje nga shfletuesi',
'timezoneregion-africa' => 'Afrikë',
'timezoneregion-america' => 'Amerikë',
'timezoneregion-antarctica' => 'Antarktik',
'timezoneregion-arctic' => 'Arktik',
'timezoneregion-asia' => 'Azi',
'timezoneregion-atlantic' => 'Oqeani Atlantik',
'timezoneregion-australia' => 'Australi',
'timezoneregion-europe' => 'Europë',
'timezoneregion-indian' => 'Oqeani Indian',
'timezoneregion-pacific' => 'Oqeani Paqësor',
'allowemail' => 'Lejo përdoruesit të më dërgojnë email',
'prefs-searchoptions' => 'Mundësi kërkimi',
'prefs-namespaces' => 'Hapësirat',
'defaultns' => 'Kërko automatikisht vetëm në këto hapësira:',
'default' => 'parazgjedhje',
'prefs-files' => 'Figura',
'prefs-custom-css' => 'CSS i përpunuem',
'prefs-custom-js' => 'JavaScripti i përpunuar',
'prefs-common-css-js' => 'CSS/Javascript të përbashkët për të gjitha pamjet:',
'prefs-reset-intro' => 'Mundeni me përdorë këtë faqe për me i kthy parapëlqimet tueja në ato të paracaktuemet e faqes.
Kjo nuk mundet me u zhbâ.',
'prefs-emailconfirm-label' => 'Konfirmimi i emailit:',
'prefs-textboxsize' => 'Madhësia e dritares së redakrimit',
'youremail' => 'Adresa e email-it*',
'username' => 'Nofka e përdoruesit:',
'uid' => 'Nr. i identifikimit:',
'prefs-memberingroups' => 'Anëtar i {{PLURAL:$1|grupit|grupeve}}:',
'prefs-registration' => 'Koha e regjistrimit:',
'yourrealname' => 'Emri juaj i vërtetë*',
'yourlanguage' => 'Ndërfaqja gjuhësore',
'yourvariant' => 'Varianti i gjuhës së përmbajtjes:',
'prefs-help-variant' => 'Varianti ose ortografia juaj e preferuar për të shfaqur përmbajtjen e faqeve në këtë wiki.',
'yournick' => 'Nënshkrimi',
'prefs-help-signature' => 'Komentet në faqet e diskutimit duhet të nënshkruhen me "<nowiki>~~~~</nowiki>" të cilat do të konvertohen me emrin tuaj të përdoruesit dhe kohën.',
'badsig' => 'Sintaksa e signaturës është e pavlefshme, kontrolloni HTML-in.',
'badsiglength' => 'Nënshkrimi është tepër i gjatë.
Nuk duhet të jetë më i gjatë se $1 {{PLURAL:$1|karakter|karaktere}}.',
'yourgender' => 'Gjinia:',
'gender-unknown' => 'e pacaktuar',
'gender-male' => 'Mashkull',
'gender-female' => 'Femër',
'prefs-help-gender' => 'Sipas dëshirës: përdoret për adresim korrekt në relacion me gjininë nga software-i.
Kjo informatë është publike.',
'email' => 'Email',
'prefs-help-realname' => '* Emri i vërtetë nuk është i domosdoshëm: Nëse e jipni do të përmendeni si kontribues për punën që ke bërë.',
'prefs-help-email' => "Posta elektronike është zgjedhore, por ju mundëson që fjalëkalimi i ri të ju dërgohet nëse e harroni atë. Gjithashtu mund të zgjidhni nëse doni të tjerët t'ju shkruajnë ose jo përmes faqes suaj të diskutimit pa patur nevojë të zbulojnë identitetin tuaj.",
'prefs-help-email-others' => 'Mundeni gjithashtu të zgjidhni të kontaktoheni nga të tjerët përmes faqeve tuaja të diskutimit ose përdoruesit pa e treguar identitetin.',
'prefs-help-email-required' => 'Nevojitet e-mail adresa .',
'prefs-info' => 'Informatat bazike',
'prefs-i18n' => 'Internacionalizimi',
'prefs-signature' => 'Firma',
'prefs-dateformat' => 'Data i formatit',
'prefs-timeoffset' => 'Kohë të kompensuar',
'prefs-advancedediting' => 'Opsionet e avancuar',
'prefs-advancedrc' => 'Opsionet e avancuar',
'prefs-advancedrendering' => 'Opsionet e avancuar',
'prefs-advancedsearchoptions' => 'Opsionet e avancuar',
'prefs-advancedwatchlist' => 'Opsionet e avancuar',
'prefs-displayrc' => 'Shfaq opsionet',
'prefs-displaysearchoptions' => 'Shfaq opsionet',
'prefs-displaywatchlist' => 'Shfaq opsionet',
'prefs-diffs' => 'Ndryshimet',

# User preference: e-mail validation using jQuery
'email-address-validity-valid' => 'E-mail adresa është e vlefshme.',
'email-address-validity-invalid' => 'Futni një e-mali adresë të vlefshme.',

# User rights
'userrights' => 'Ndrysho privilegjet e përdoruesve',
'userrights-lookup-user' => 'Ndrysho grupet e përdoruesit',
'userrights-user-editname' => 'Fusni emrin e përdoruesit:',
'editusergroup' => 'Redakto grupet e përdoruesve',
'editinguser' => "Duke ndryshuar privilegjet e përdoruesit '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Anëtarësimi tek grupet',
'saveusergroups' => 'Ruaj Grupin e Përdoruesve',
'userrights-groupsmember' => 'Anëtar i:',
'userrights-groupsmember-auto' => 'Anëtar implicit i:',
'userrights-groups-help' => 'Mund të ndryshoni anëtarësimin e këtij përdoruesi në grupe:
* Kutia e zgjedhur shënon që përdoruesi është anëtar në atë grup
* Kutia e pazgjedhur shënon që përdoruesi nuk është anëtar në atë grup
* Një * shënon që nuk mund ta hiqni grupin pasi ta keni shtuar (dhe anasjelltas).',
'userrights-reason' => 'Arsyeja:',
'userrights-no-interwiki' => 'Nuk keni leje për të ndryshuar privilegjet e përdoruesve në wiki të tjera.',
'userrights-nodatabase' => 'Regjistri $1 nuk ekziston ose nuk është vendor.',
'userrights-nologin' => 'Duhet të [[Special:UserLogin|hyni brenda]] me një llogari administrative për të ndryshuar privilegjet e përdoruesve.',
'userrights-notallowed' => 'llogaria juaj nuk ka leje për të shtuar ose hequr privilegjet e përdoruesve.',
'userrights-changeable-col' => 'Grupe që mund të ndryshoni',
'userrights-unchangeable-col' => "Grupe që s'mund të ndryshoni",

# Groups
'group' => 'Grupi:',
'group-user' => 'Përdorues',
'group-autoconfirmed' => 'Përdorues të vërtetuar automatikisht',
'group-bot' => 'Robot',
'group-sysop' => 'Administrues',
'group-bureaucrat' => 'Burokrat',
'group-suppress' => 'Kujdestari',
'group-all' => '(të gjitha)',

'group-user-member' => '{{GENDER:$1|përdorues|përdoruese}}',
'group-autoconfirmed-member' => '{{GENDER:$1|përdorues i vërtetuar automatikisht|përdoruese e vërtetuar automatikisht}}',
'group-bot-member' => '{{GENDER:$1|robot|robote}}',
'group-sysop-member' => '{{GENDER:$1|administrues|administruese}}',
'group-bureaucrat-member' => '{{GENDER:$1|burokrat|burokrate}}',
'group-suppress-member' => '{{GENDER:$1|kujdestar|kujdestare}}',

'grouppage-user' => '{{ns:project}}:Përdorues',
'grouppage-autoconfirmed' => '{{ns:project}}:Përdorues të vërtetuar automatikisht',
'grouppage-bot' => '{{ns:project}}:Robotë',
'grouppage-sysop' => '{{ns:project}}:Administruesit',
'grouppage-bureaucrat' => '{{ns:project}}:Burokratë',
'grouppage-suppress' => '{{ns:project}}:Kujdestari',

# Rights
'right-read' => 'Lexo faqe',
'right-edit' => 'Redakto faqet',
'right-createpage' => 'Hap faqe (që nuk janë faqe diskutimi)',
'right-createtalk' => 'Hap faqe diskutimi',
'right-createaccount' => 'Hap llogari të re',
'right-minoredit' => 'Shëno redaktimet si të vogla',
'right-move' => 'Lëviz faqet',
'right-move-subpages' => 'Lëviz faqet me nënfaqet e tyre',
'right-move-rootuserpages' => 'Lëviz burimin e faqes së përdoruesit',
'right-movefile' => 'Lëviz skedarët',
'right-suppressredirect' => 'Mos krijo zhvendosje nga emri i vjetër kur lëvizë një faqe',
'right-upload' => 'Ngarko skedarë',
'right-reupload' => 'Ringarko skedën ekzistuese',
'right-reupload-own' => 'Ringarko skedën ekzistuese të ngarkuar vetë',
'right-reupload-shared' => 'Mos pranoni skeda në media magazinën e përbashkët në nivel lokal',
'right-upload_by_url' => 'Ngarko skedë nga ndonjë URL',
'right-purge' => 'Pastro "cache" e site-it për një faqe pa konfirmim',
'right-autoconfirmed' => 'Redakto faqet gjysmë të mbrojtura',
'right-bot' => 'Trajtohu si një proces automatik',
'right-nominornewtalk' => 'Nuk kanë redaktimet e vogla për faqet e diskutimit të shkaktuar mesazhe të reja e shpejtë',
'right-apihighlimits' => 'Vendosni kufijtë më të lartë në pyetjet API',
'right-writeapi' => 'Përdorimi i shkrimit API',
'right-delete' => 'Gris faqet',
'right-bigdelete' => 'Gris faqet me histori të gjata',
'right-deletelogentry' => 'Fshij dhe mos i fshij shënimet në regjistrat e veçantë',
'right-deleterevision' => 'Grisi dhe riktheji revizionet specifike të faqeve',
'right-deletedhistory' => 'Shiko shënimet e grisura të historikut, pa tekstet e tyre të shoqëruara',
'right-deletedtext' => 'Shiko tekstin dhe ndryshimet e grisura ndërmjet versioneve të grisura',
'right-browsearchive' => 'Kërko faqe të grisura',
'right-undelete' => 'Rikthe faqen',
'right-suppressrevision' => 'Rishiko dhe rikthe versionet e fshehura nga administratorët',
'right-suppressionlog' => 'Shiko hyrjet private',
'right-block' => 'Blloko përdoruesit tjerë nga editimi',
'right-blockemail' => 'Blloko përdoruesin që të mos dërgojë postë elektronike',
'right-hideuser' => 'Blloko përdorues, duke fshehur nga publiku',
'right-ipblock-exempt' => 'Anashkalo bllokimet e IP-ve, auto-bllokimet dhe linjën e bllokimeve',
'right-proxyunbannable' => 'Anashkalo bllokimet automatike të ndërmjetësve',
'right-unblockself' => 'Zhblloko veten',
'right-protect' => 'Ndrysho nivelin mbrojtës dhe redakto faqet e mbrojtura',
'right-editprotected' => 'Redakto faqet e mbrojtura (pa ndryshuar mbrojtjen)',
'right-editinterface' => 'Ndrysho parapamjen e përdoruesit',
'right-editusercssjs' => 'Redakto skedat CSS dhe JS të përdoruesve tjerë',
'right-editusercss' => 'Redakto skedat CSS të përdoruesve tjerë',
'right-edituserjs' => 'Redakto skedat JS të përdoruesve tjerë',
'right-rollback' => 'Rikthen shpejt redaktimet  e pedaktuesit të fundit',
'right-markbotedits' => 'Shëno rikthimet si redaktime robotësh',
'right-noratelimit' => 'Mos u prek nga kufizimet e vlerësimit',
'right-import' => 'Importo faqe nga wiki tjera',
'right-importupload' => 'Importo faqet nga një ngarkim skede',
'right-patrol' => 'Shëno redaktimin e tjerëve si të patrulluar',
'right-autopatrol' => 'A e vet redaktimet e shënuar automatikisht patrulluar',
'right-patrolmarks' => 'Shiko ndryshimet e fundit shënon patrullë',
'right-unwatchedpages' => 'Shiko listën e faqeve të pa vëzhguara',
'right-mergehistory' => 'Bashko historinë e faqeve',
'right-userrights' => 'Redakto të gjitha të drejtat e përdoruesit',
'right-userrights-interwiki' => 'Ndrysho të drejtat e përdoruesve në wiki të tjera',
'right-siteadmin' => 'Mbyll ose hap bazën e të dhënave',
'right-override-export-depth' => 'Eksoprto faqet duke përfshirë e lidhura deri në një thellësi prej 5',
'right-sendemail' => 'Dërgo e-mail tek përdoruesit e tjerë',
'right-passwordreset' => 'Shiko e-mail-et e rivendosjes së fjalëkalimit',

# User rights log
'rightslog' => 'Regjistri i privilegjeve të përdoruesit',
'rightslogtext' => 'Ky është një regjistër për ndryshimet e privilegjeve të përdoruesit.',
'rightslogentry' => 'u ndryshua anëtarësimi i grupit për $1 nga $2 tek $3',
'rightslogentry-autopromote' => 'automatikisht u rrit në datyrë nga $2 në $3',
'rightsnone' => '(asgjë)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'lexo këtë faqe',
'action-edit' => 'redakto këtë faqe',
'action-createpage' => 'krijo faqe',
'action-createtalk' => 'krijo faqe diskutimi',
'action-createaccount' => 'krijo këtë llogari përdoruesi',
'action-minoredit' => 'shëno këtë redaktim si të vogël',
'action-move' => 'zhvendos këtë faqe',
'action-move-subpages' => 'zhvendos këtë faqe dhe nënfaqet e saj',
'action-move-rootuserpages' => 'lëviz rrënjët e faqeve të përdoruesve',
'action-movefile' => 'lëviz këtë skedë',
'action-upload' => 'ngarko këtë skedë',
'action-reupload' => 'rishkruaj këtë skedë ekzistuese',
'action-reupload-shared' => 'mbishkruaj këtë skedarë në një magazinë të përbashkët',
'action-upload_by_url' => 'ngarko këtë skedë nga një URL',
'action-writeapi' => 'përdor API-në e shkrimit',
'action-delete' => 'grise këtë faqe',
'action-deleterevision' => 'grise këtë revizion',
'action-deletedhistory' => 'shiko historinë e kësaj faqeje të grisur',
'action-browsearchive' => 'kërko faqe të grisura',
'action-undelete' => 'Restauro këtë faqe',
'action-suppressrevision' => 'rishiko dhe rikthe këtë revizion të fshehur',
'action-suppressionlog' => 'shiko këtë regjistër privat',
'action-block' => 'blloko përdoruesin',
'action-protect' => 'ndrysho nivelin e mbrojtjes për këtë faqe',
'action-rollback' => 'ritkthen shpejt redaktimet e përdoruesit të fundit që redaktoi një faqe të veçantë',
'action-import' => 'importo këtë faqe nga një wiki tjetër',
'action-importupload' => 'importo këtë faqe nga një ngarkim i një skedari',
'action-patrol' => 'shëno redaktimin e tjerëve si të patrulluar',
'action-autopatrol' => 'shëno redaktimet tua si të patrulluara',
'action-unwatchedpages' => 'shiko listën e faqeve të pa vrojtuara',
'action-mergehistory' => 'bashko historikun e kësaj faqeje',
'action-userrights' => 'ndrysho të gjitha të drejtat e përdoruesit',
'action-userrights-interwiki' => 'ndrysho të drejtat e përdoruesve në wiki-t tjera',
'action-siteadmin' => 'mbyll ose hap bazën e të dhënave',
'action-sendemail' => 'dërgo e-maile',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|ndryshim|ndryshime}}',
'recentchanges' => 'Ndryshime së fundmi',
'recentchanges-legend' => 'Zgjedhjet e ndryshimeve momentale',
'recentchanges-summary' => 'Ndiqni ndryshime së fundmi tek kjo faqe.',
'recentchanges-feed-description' => 'Ndjek ndryshimet më të fundit në wiki tek kjo fushë.',
'recentchanges-label-newpage' => 'Ky redaktim krijoi një faqe të re',
'recentchanges-label-minor' => 'Ky është një editim i vogël',
'recentchanges-label-bot' => 'Ky editim është kryer nga një bot',
'recentchanges-label-unpatrolled' => 'Ky editim ende nuk është patrolluar',
'rcnote' => "Më poshtë {{PLURAL:$1|është '''1''' ndryshim| janë '''$1''' ndryshime}} së fundmi gjatë <strong>$2</strong> ditëve sipas të dhënave nga $4, $5.",
'rcnotefrom' => 'Më poshtë janë ndryshime së fundmi nga <b>$2</b> (treguar deri në <b>$1</b>).',
'rclistfrom' => 'Tregon ndryshime së fundmi duke filluar nga $1',
'rcshowhideminor' => '$1 redaktimet e vogla',
'rcshowhidebots' => '$1 robotët',
'rcshowhideliu' => '$1 përdoruesit e regjistruar',
'rcshowhideanons' => '$1 përdoruesit anonim',
'rcshowhidepatr' => '$1 redaktime të patrulluara',
'rcshowhidemine' => '$1 redaktimet e mia',
'rclinks' => 'Trego $1 ndryshime gjatë $2 ditëve<br />$3',
'diff' => 'ndrysh',
'hist' => 'hist',
'hide' => 'fshih',
'show' => 'trego',
'minoreditletter' => 'v',
'newpageletter' => 'R',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[$1 duke u mbikqyrur nga {{PLURAL:$1|përdorues|përdorues}}]',
'rc_categories' => 'Kufizimi i kategorive (të ndara me "|")',
'rc_categories_any' => 'Të gjitha',
'rc-change-size-new' => '$1 {{PLURAL:$1|bajt|bajtë}} pas ndryshimit',
'newsectionsummary' => '/* $1 */ seksion i ri',
'rc-enhanced-expand' => 'Trego detajet (kërkon JavaScript)',
'rc-enhanced-hide' => 'Fshih detajet',
'rc-old-title' => 'fillimisht i krijuar si "$1"',

# Recent changes linked
'recentchangeslinked' => 'Ndryshime të ndërvarura',
'recentchangeslinked-feed' => 'Ndryshime të ndërvarura',
'recentchangeslinked-toolbox' => 'Ndryshime të ndërvarura',
'recentchangeslinked-title' => 'Ndryshime që kanë lidhje me "$1"',
'recentchangeslinked-noresult' => 'Nuk ka pasur ndryshime tek faqet e lidhura gjatë kohës së dhënë.',
'recentchangeslinked-summary' => "Kjo është një listë e ndryshimeve së fundmi të faqeve të lidhura nga faqja e dhënë (ose bëjnë pjesë tek kategoria e dhënë).
Faqet [[Special:Watchlist|nën mbikqyrjen tuaj]] duken të '''theksuara'''.",
'recentchangeslinked-page' => 'Emri i faqes:',
'recentchangeslinked-to' => 'Trego ndryshimet e faqeve që lidhen tek faqja e dhënë',

# Upload
'upload' => 'Ngarkoni skeda',
'uploadbtn' => 'Ngarkoje',
'reuploaddesc' => 'Kthehu tek formulari i dhënies.',
'upload-tryagain' => 'Dërgo përshkrimin e modifikuar të skedarit',
'uploadnologin' => 'Nuk keni hyrë brënda',
'uploadnologintext' => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] për të dhënë skeda.',
'upload_directory_missing' => 'Direktoriumi ($1) i ngarkimit po mungon dhe nuk është arritur që të krijohet nga webserveri.',
'upload_directory_read_only' => 'Skedari i ngarkimit ($1) nuk mund të shkruhet nga shërbyesi.',
'uploaderror' => 'Gabim dhënie',
'upload-recreate-warning' => "'''Kujdes: Një skedarë me atë emër është fshirë apo lëvizur.'''

Regjistri i fshirjes dhe lëvizjes për këtë faqe për lehtësim ofrohen këtu:",
'uploadtext' => "Përdorni formularin e mëposhtëm për të ngarkuar skeda.
Për të parë ose kërkuar skeda të ngarkuara më parë, shkoni tek [[Special:FileList|lista e ngarkimeve të skedave]],
(ri)ngarkimet janë gjithashtu të regjistruara tek [[Special:Log/upload|regjistri i ngarkimeve]], grisjet tek [[Special:Log/delete|regjistri i grisjeve]].

Për të përfshirë një skedë në një faqe, përdorni një nga format e mëposhtme:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skeda.jpg]]</nowiki></code>''' për të përdorur versionin e plotë të skedës
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Skeda.png|200px|thumb|left|alt text]]</nowiki></code>''' për të përdorur nje interpretim prej 200 piksel në të majtë me 'alt tekst' si përshkrim
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Skeda.ogg]]</nowiki></code>''' për të lidhur skedën direkt, pa e shfaqur atë",
'upload-permitted' => 'Llojet e lejuara të skedave: $1.',
'upload-preferred' => 'Llojet e parapëlqyera të skedave: $1.',
'upload-prohibited' => 'Llojet e ndaluara të skedave: $1.',
'uploadlog' => 'regjistër dhënjesh',
'uploadlogpage' => 'Regjistri i ngarkimeve',
'uploadlogpagetext' => 'Më poshtë është një listë e skedave më të reja që janë ngarkuar.
Të gjithë orët janë me orën e shërbyesit.',
'filename' => 'Emri i skedës',
'filedesc' => 'Përmbledhje',
'fileuploadsummary' => 'Përshkrimi:',
'filereuploadsummary' => 'Ndryshimet e skedës:',
'filestatus' => 'Gjendja e të drejtave të autorit:',
'filesource' => 'Burimi:',
'uploadedfiles' => 'Ngarkoni skeda',
'ignorewarning' => 'Shpërfille paralajmërimin dhe ruaje skedën.',
'ignorewarnings' => 'Shpërfill çdo paralajmërim',
'minlength1' => 'Emri i dosjes duhet të jetë së paku një fjalë',
'illegalfilename' => 'Skeda "$1" përmban gërma që nuk lejohen tek titujt e faqeve. Ju lutem ndërrojani emrin dhe provoni ta ngarkoni përsëri.',
'filename-toolong' => 'Emrat e skedave nuk mund të jenë më të gjata se 240 bajt.',
'badfilename' => 'Emri i skedës është ndërruar në "$1".',
'filetype-mime-mismatch' => 'Prapashtesa .$1 e skedarit ($2) nuk përshtatet me tipin MIME.',
'filetype-badmime' => 'Skedat e llojit MIME "$1" nuk lejohen për ngarkim.',
'filetype-bad-ie-mime' => 'Nuk mund të ngarkoni këtë skedë sepse Internet Explorer do ta zbulonte si "$1", që është një lloj skede e papranuar dhe potencialisht e rrezikshme.',
'filetype-unwanted-type' => "'''\".\$1\"''' është një lloj skede i padëshiruar.
Parapëlqehet {{PLURAL:\$3|skeda të jetë e |skedat të jenë të}} llojit \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|nuk është një lloj i skedës së lejuar|nuk janë lloje të lejuara të skedave}}.
{{PLURAL:$3|Lloji i lejuar i skedës është|Llojet e lejuara të skedave janë}} $2.',
'filetype-missing' => 'Skeda nuk ka mbaresë (si p.sh. ".jpg").',
'empty-file' => 'Skeda që paraqitët ishte bosh.',
'file-too-large' => 'Skeda që paraqitët ishte shumë e madhe.',
'filename-tooshort' => 'Emri i skedës është shumë i shkurtër.',
'filetype-banned' => 'Kjo lloji i skedës është e ndalur.',
'verification-error' => 'Kjo skedë nuk e kaloi verifikimin e skedave.',
'hookaborted' => 'Modifikimi që ju provuat ta bëni u ndërpre nga një goditje shtesë.',
'illegal-filename' => 'Emri i skedarit nuk lejohet.',
'overwrite' => 'Mbishkrimi i një skede ekzistuese nuk lejohet.',
'unknown-error' => 'Një gabim i panjohur.',
'tmp-create-error' => 'Nuk mund të krijohej skeda e përkohëshme.',
'tmp-write-error' => 'Gabim gjatë shkrimit të skedës së përkohshme.',
'large-file' => 'Është e këshillueshme që skedat të jenë jo më shumë se $1;
kjo skedë është $2.',
'largefileserver' => 'Skeda është më e madhe se sa serveri e lejon këtë.',
'emptyfile' => 'Skeda që keni dhënë është bosh ose mbi madhësinë e lejushme. Kjo gjë mund të ndodhi nëse shtypni emrin gabim, prandaj kontrolloni nëse dëshironi të jepni skedën me këtë emër.',
'windows-nonascii-filename' => 'Ky wiki nuk e mbështet emrin e dokumentit me karaktere të veçanta.',
'fileexists' => 'Ekziston një skedë me atë emër, ju lutem kontrolloni <strong>[[:$1]]</strong> në qoftë se nuk jeni të sigurt nëse dëshironi ta zëvendësoni.
[[$1|thumb]]',
'filepageexists' => 'Përshkrimi i faqes për këtë skedë është krijuar tek <strong>[[:$1]]</strong>, por asnjë skedë me këtë emër nuk ekziston.
Përmbledhja që shkruat nuk do të shfaqet në përshkrimin e faqes.
Për ta bërë përmbledhjen tuaj të dukshme atje, ju duhet ta redaktoni automatikisht.
[[$1|thumb]]',
'fileexists-extension' => 'Ekziston një skedë me emër të ngjashëm: [[$2|thumb]]
* Emri i skedës në ngarkim: <strong>[[:$1]]</strong>
* Emri i skedës ekzistuese: <strong>[[:$2]]</strong>
Ju lutem zgjidhni një emër tjetër.',
'fileexists-thumbnail-yes' => "Kjo skedë duket se është një figurë me madhësi të zvogëluar ''(figurë përmbledhëse)''. [[$1|thumb]]
Ju lutem kontrolloni skedën <strong>[[:$1]]</strong>.
Nëse skeda e kontrolluar është e së njëjtës madhësi me origjinalen atëherë nuk ka nevojë të ngarkoni një figurë përmbledhëse.",
'file-thumbnail-no' => "Emri i skedës fillon me <strong>$1</strong>.
Duket se është një figurë me madhësi të zvogëluar ''(thumbnail)''.
Nëse keni këtë figurë me madhësi të plotë ju lutem të ngarkoni atë, përndryshe ju lutem të ndryshoni emrin e skedës.",
'fileexists-forbidden' => 'Ekziston një skedë me të njëjtin emër. Ju lutemi kthehuni mbrapsht dhe ngarkoni këtë skedë me një emër të ri. 
[[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ekziston një skedë me të njëjtin emër në magazinën e përbashkët. Ju lutem kthehuni mbrapsht dhe ngarkojeni këtë skedë me një emër të ri. 
 [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Kjo skedë është dyfish i {{PLURAL:$1|skedës|skedave}} në vijim:',
'file-deleted-duplicate' => 'Një skedë identike më këtë skedë ([[:$1]]) është grisur më përpara.
Ju duhet të kontrolloni historikun e grisjes të asaj skede përpara se ta ri-ngarkoni atë.',
'uploadwarning' => 'Kujdes dhënie',
'uploadwarning-text' => 'Ju lutemi modifikoni përshkrimin e skedës dhe provojen përsëri.',
'savefile' => 'Ruaj skedën',
'uploadedimage' => 'dha "[[$1]]"',
'overwroteimage' => 'dha dhe zëvendësoi me një version të ri të "[[$1]]"',
'uploaddisabled' => 'Ndjesë, dhëniet janë bllokuar në këtë shërbyes dhe nuk është gabimi juaj.',
'copyuploaddisabled' => 'Ngarkimi nga URL-ja u çaktivizua.',
'uploadfromurl-queued' => 'Ngarkimi juaj ka qenë në radhë.',
'uploaddisabledtext' => 'Ngarkimi i skedave është i ndaluar.',
'php-uploaddisabledtext' => 'Ngarkimet e skedave në PHP janë të çaktivizuara.
Ju lutemi kontrolloni parametrat e ngarkimeve të skedave.',
'uploadscripted' => 'Skeda përmban HTML ose kode të tjera që mund të interpretohen gabimisht nga një shfletues.',
'uploadvirus' => 'Skeda përmban një virus! Detaje: $1',
'uploadjava' => 'Dokumenti është në formatin ZIP i cili përmban Java. class dokumente.
Ngarkimi i Java dokumenteve nuk është i lejuar, sepse ato mund të shkaktojnë kufizime të sigurisë për ti anashkaluar.',
'upload-source' => 'Burimi i skedës',
'sourcefilename' => 'Emri i skedës:',
'sourceurl' => 'Burimi URL:',
'destfilename' => 'Emri mbas dhënies:',
'upload-maxfilesize' => 'Madhësia maksimale e skedave: $1',
'upload-description' => 'Përshkrimi i skedës',
'upload-options' => 'Opsionet e ngarkimit',
'watchthisupload' => 'Mbikqyre këtë skedë',
'filewasdeleted' => 'Një skedë më këtë emër është ngarkuar një here dhe pastaj është grisur. Duhet të shikoni $1 përpara se ta ngarkoni përsëri.',
'filename-bad-prefix' => "Emri i skedës që po ngarkoni fillon me '''\"\$1\"''' dhe nuk është veçantisht përshkrues pasi përdoret nga shumë kamera.
Ju lutem zgjidhni një emër më përshkrues për skedën tuaj.",
'upload-success-subj' => 'Dhënie e sukseshme',
'upload-success-msg' => 'Ngarkimi juaj nga [$2] ishte i suksesshëm. Mund të gjendet këtu: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problem gjatë ngarkimit',
'upload-failure-msg' => 'Kishte një problem me ngarkimin tuaj nga [$2]:

$1',
'upload-warning-subj' => 'Paralajmërim për ngarkimin',
'upload-warning-msg' => 'Kishte një problem me ngarkimin tuaj nga [$2]. Ju mund të ktheheni tek [[Special:Upload/stash/$1|forma e ngarkimit]] për të korrgjuar këtë problem.',

'upload-proto-error' => 'Protokoll i gabuar',
'upload-proto-error-text' => 'Ngarkimet nga rrjeti kërkojnë që adresa URL të fillojë me <code>http://</code> ose <code>ftp://</code>.',
'upload-file-error' => 'Gabim i brendshëm',
'upload-file-error-text' => 'Ka ndodhur një gabim i brendshëm gjatë krijimit të skedës së përkohshme nga shërbyesi.
Ju lutemi kontaktoni një [[Special:ListUsers/sysop|administrator]].',
'upload-misc-error' => 'Gabim i panjohur ngarkimi',
'upload-misc-error-text' => 'Një gabim i panjohur ka ndodhur gjatë ngarkimit.
Ju lutemi kontrolloni që adresa URL të jetë e vlefshme dhe e kapshme dhe provoni përsëri.
Nëse problemi vazhdon atëherë kontaktoni një [[Special:ListUsers/sysop|administrator]].',
'upload-too-many-redirects' => 'Adresa URL përmbante shumë përcjellime.',
'upload-unknown-size' => 'Madhësia e panjohur',
'upload-http-error' => 'Ndodhi një gabim HTTP: $1',
'upload-copy-upload-invalid-domain' => 'Ngarkesat e kopjimit nuk janë në dispozicion nga ky domein.',

# File backend
'backend-fail-stream' => 'Nuk mund të kalojë skedën $1.',
'backend-fail-backup' => 'Nuk mund të rezervojë skedën $1.',
'backend-fail-notexists' => 'Skeda $1 nuk ekziston.',
'backend-fail-hashes' => 'Nuk mund të marrë rrëmujat e skedave për krahasim.',
'backend-fail-notsame' => 'Një skedë joidentike ekziston tashmë tek $1.',
'backend-fail-invalidpath' => '$1 nuk është një rrugë e vlefshme ruajtjeje.',
'backend-fail-delete' => 'Nuk mund të grisë skedën $1.',
'backend-fail-alreadyexists' => 'Skeda $1 ekziston tashmë.',
'backend-fail-store' => 'Nuk mund të ruajë skedën $1 tek $2.',
'backend-fail-copy' => 'Nuk mund të kopjojë skedën $1 tek $2.',
'backend-fail-move' => 'Nuk mund të zhvendosë skedën $1 tek $2.',
'backend-fail-opentemp' => 'Nuk mund të hapë skedën e përkohshme.',
'backend-fail-writetemp' => 'Nuk mund të shkruajë te skeda e përkohshme.',
'backend-fail-closetemp' => 'Nuk mund të mbyllë skedën e përkohshme.',
'backend-fail-read' => 'Nuk mund të lexojë skedën $1.',
'backend-fail-create' => 'Nuk mund të krijojë skedën $1.',
'backend-fail-maxsize' => 'Nuk mund të shkruante skedarin "$1" sepse ai është më i madh se {{SHUMËS:$2|një bajt|$2 bajtë}}',
'backend-fail-readonly' => 'Shërbimi i depos "$1" është për momentin vetëm-për-lexim. Arsyeja e dhënë është: "\'\'$2\'\'"',
'backend-fail-synced' => 'Skedari "$1" është në një gjendje të parregullt brenda proceseve të depos së brendshme',
'backend-fail-connect' => 'Nuk u arrit lidhja me shërbimin e depos "$1".',
'backend-fail-internal' => 'Një problem i panjohur ndodhi në shërbimin e depos "$1".',
'backend-fail-contenttype' => 'Nuk mundi të përcaktojë llojin e përmbajtjes së skedarit për ta ruajtur në "$1".',

# Lock manager
'lockmanager-notlocked' => 'Nuk mund të zhbllokojë "$1"; nuk është e bllokuar.',
'lockmanager-fail-closelock' => 'Nuk mund të mbyllë kyçjen e skedës për "$1".',
'lockmanager-fail-deletelock' => 'Nuk mund të grisë kyçjen e skedës për "$1".',
'lockmanager-fail-acquirelock' => 'Nuk mund të sigurojë kyçjen për "$1".',
'lockmanager-fail-openlock' => 'Nuk mund të hapë kyçjen e skedës për "$1".',
'lockmanager-fail-releaselock' => 'Nuk mund të lëshojë kyçjen për "$1".',
'lockmanager-fail-db-bucket' => 'Nuk mund të kontaktojë mjaftueshëm baza të dhënash kyçjes në kovën $1.',
'lockmanager-fail-db-release' => 'Nuk mund të lëshojë kyçjet në bazën e të dhënave $1.',
'lockmanager-fail-svr-release' => 'Nuk mund të lëshojë kyçjet në serverin $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Një gabim është hasur gjatë hapjes së dokumentit për ZIP kontrollimin.',
'zip-wrong-format' => 'Dokumenti i specifikuar nuk ishte ZIP dokument.',
'zip-bad' => 'Dokumenti është i korruptuar ose përndryshe dokument ZIP i palexueshëm.
Ajo nuk mund të kontrollohet siç duhet për sigurinë',
'zip-unsupported' => 'Dokumenti është ZIP format i cili përdorë ZIP karakteristikat që nuk mbështeten nga MediaWiki.
Ajo nuk mund të kontrollohet siç duhet për sigurinë.',

# Special:UploadStash
'uploadstash' => 'Fshehje ngarkimi',
'uploadstash-summary' => 'Kjo faqe ofron qasje tek skedat të cilat janë ngarkuar (ose janë në proçes ngarkimi) por që nuk janë publikuat akoma në wiki. Këto skeda nuk janë të dukshme për këdo përveç për përdoruesin që i ka ngarkuar ato.',
'uploadstash-clear' => 'Spastro skedat e fshehura',
'uploadstash-nofiles' => 'Ju nuk keni skeda të fshehura.',
'uploadstash-badtoken' => 'Kryerja e këtij veprimi ishte e pasuksesshme, ndoshta sepse kredencialet redaktuese tuaja kanë skaduar. Provoni përsëri.',
'uploadstash-errclear' => 'Spastrimi i skedave ishte i pasuksesshëm.',
'uploadstash-refresh' => 'Rifreskoni listën e skedave',
'invalid-chunk-offset' => 'Kompensim cope i pavlefshëm',

# img_auth script messages
'img-auth-accessdenied' => 'Refuzohet hyrja',
'img-auth-nopathinfo' => 'Mungon PATH_INFO.
Shërbyesi juaj nuk e kalon këtë informacion.
Mund të jetë CGI-bazuar dhe nuk mund të mbështesë img_auth.
Shiko https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'Kërkesa nuk është në drejtorinë e ngarkimeve të konfiguruara.',
'img-auth-badtitle' => 'Nuk mund të krihohej një titull i vlefshëm nga "$1".',
'img-auth-nologinnWL' => 'Ju nuk jeni i regjistruar dhe "$1" nuk është në listën e bardhë.',
'img-auth-nofile' => 'Skeda "$1" nuk ekziston.',
'img-auth-isdir' => 'Ju po përpiqeni të hyni në një drejtori "$1".
Vetëm  qasja e skedës është e lejuar.',
'img-auth-streaming' => 'Duke rrejdhur "$1"',
'img-auth-public' => 'Funksioni i img_auth.php është për të larguar skedat nga një wiki privat.
Ky wiki është i konfiguruar si një wiki publik.
Për siguri optimale, img_auth.php është çaktivizuar.',
'img-auth-noread' => 'Përdoruesi nuk ka qasje për të lexuar "$1".',
'img-auth-bad-query-string' => 'URL ka një varg të pavlefshme pyetje.',

# HTTP errors
'http-invalid-url' => 'Adresë URL e pavlefshme: $1',
'http-invalid-scheme' => 'Adresat URL me skemën "$1" nuk mbështeten.',
'http-request-error' => 'Kërkesa HTTP dështoi për shkak të një gabimi të panjohur.',
'http-read-error' => 'Gabim në leximin e HTTP.',
'http-timed-out' => 'Kërkesës HTTP i kaloi koha.',
'http-curl-error' => 'Gabim gjatë gjetjes së URL-së: $1',
'http-host-unreachable' => 'Nuk mund të lidheni me adresën URL.',
'http-bad-status' => 'Ndodhi një problem gjatë kërkesës HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => "S'munda të lidhem me adresën URL",
'upload-curl-error6-text' => 'Adresa e dhënë URL nuk mund të arrihej.
Ju lutem kontrollojeni nëse është e saktë dhe nëse faqja punon.',
'upload-curl-error28' => 'Mbaroi koha e ngarkimit',
'upload-curl-error28-text' => 'Ka kaluar shumë kohë pa përgjigje.
Ju lutem kontrolloni nëse faqja është në rrjet, prisni pak dhe provojeni përsëri.
Këshillohet që ta provoni kur të jetë më pak e zënë.',

'license' => 'Licencimi:',
'license-header' => 'Licencimi:',
'nolicense' => 'Asnjë nuk është zgjedhur',
'license-nopreview' => '(Nuk ka parapamje)',
'upload_source_url' => ' (URL e vlefshme, publikisht e përdorshme)',
'upload_source_file' => ' (skeda në kompjuterin tuaj)',

# Special:ListFiles
'listfiles-summary' => 'Kjo faqe speciale tregon tërë skedat e ngarkuara.
Fillimisht skedat e ngarkuara së fundmi jepen më sipër.
Shtypni kolonat e tjera për të ndryshuar radhitjen.',
'listfiles_search_for' => 'Kërko për emrin e figurës:',
'imgfile' => 'skeda',
'listfiles' => 'Lista e figurave',
'listfiles_thumb' => 'Parapamje',
'listfiles_date' => 'Data',
'listfiles_name' => 'Emri',
'listfiles_user' => 'Përdoruesi',
'listfiles_size' => 'Madhësia (bytes)',
'listfiles_description' => 'Përshkrimi',
'listfiles_count' => 'Versionet',

# File description page
'file-anchor-link' => 'Figura',
'filehist' => 'Historiku i dosjes',
'filehist-help' => 'Shtypni një datë/kohë për ta parë skedën ashtu si dukej në atë kohë.',
'filehist-deleteall' => 'grisi të tëra',
'filehist-deleteone' => 'grise këtë',
'filehist-revert' => 'riktheje',
'filehist-current' => 'e tanishme',
'filehist-datetime' => 'Data/Ora',
'filehist-thumb' => 'Thumbnail',
'filehist-thumbtext' => 'Thumbnail për versionin duke filluar nga $1',
'filehist-nothumb' => "S'ka parapamje",
'filehist-user' => 'Përdoruesi',
'filehist-dimensions' => 'Dimensionet',
'filehist-filesize' => 'Madhësia e skedës',
'filehist-comment' => 'Koment',
'filehist-missing' => 'Mungon skeda',
'imagelinks' => 'Përdorimi i skedës',
'linkstoimage' => '{{PLURAL:$1|faqe lidhet|$1 faqe lidhen}} tek kjo skedë:',
'linkstoimage-more' => 'Më shumë se $1 {{PLURAL:$1|lidhje faqeje|lidhje faqesh}} tek kjo skedë.
Lista e mëposhtme tregon {{PLURAL:$1|lidhjen e parë të faqes|lidhjet e para $1 të faqeve}} vetëm tek kjo skedë.
Një [[Special:WhatLinksHere/$2|listë e plotë]] është e mundur.',
'nolinkstoimage' => 'Asnjë faqe nuk lidhet tek kjo skedë.',
'morelinkstoimage' => 'Shikoni [[Special:WhatLinksHere/$1|më shumë lidhje]] tek kjo skedë.',
'linkstoimage-redirect' => '$1 (dokument përcjellës) $2',
'duplicatesoffile' => 'Në vijim {{PLURAL:$1|skeda është identike|$1 janë idnetike}} me këtë skedë
([[Special:FileDuplicateSearch/$2|më shumë detaje]]):',
'sharedupload' => 'Kjo skedë është nga $1 dhe mund të përdoret në projekte të tjera.',
'sharedupload-desc-there' => 'Kjo skedë është nga $1 dhe mund të përdoret nga projektet e tjera.
Ju lutemi shikoni [$2 faqen e përshkrimit] për informacion të mëtejshëm.',
'sharedupload-desc-here' => 'Kjo skedë është nga $1 dhe mund të përdoret nga projektet e tjera.
Përshkrimi në [$2 faqen përshkruese të skedës] është treguar më poshtë.',
'filepage-nofile' => 'Asnjë faqe nuk lidhet tek kjo skedë.',
'filepage-nofile-link' => 'Një skedë me këtë emër nuk ekziston akoma, ju mundeni ta [$1 ngarkoni atë].',
'uploadnewversion-linktext' => 'Ngarkoni një version të ri për këtë skedë',
'shared-repo-from' => 'nga $1',
'shared-repo' => 'një magazinë e përbashkët',

# File reversion
'filerevert' => 'Rikthe $1',
'filerevert-legend' => 'Rikthe skedën',
'filerevert-intro' => "Po ktheni '''[[Media:$1|$1]]''' tek [versioni $4 i $3, $2].",
'filerevert-comment' => 'Arsyeja:',
'filerevert-defaultcomment' => 'U rikthye tek versioni i $2, $1',
'filerevert-submit' => 'Riktheje',
'filerevert-success' => "'''[[Media:$1|$1]]''' është kthyer tek [versioni $4 i $3, $2].",
'filerevert-badversion' => 'Nuk ka version vendor tjetër të kësaj skede në kohën e dhënë.',

# File deletion
'filedelete' => 'Grise $1',
'filedelete-legend' => 'Grise skedën',
'filedelete-intro' => "Jeni duke grisur '''[[Media:$1|$1]]''' së baashku me të gjithë historikun e saj.",
'filedelete-intro-old' => "Po grisni versionin e '''[[Media:$1|$1]]''' të [$4 $3, $2].",
'filedelete-comment' => 'Arsyeja:',
'filedelete-submit' => 'Grise',
'filedelete-success' => "'''$1''' është grisur.",
'filedelete-success-old' => "Versioni i '''[[Media:$1|$1]]''' që nga $3, $2 është grisur.",
'filedelete-nofile' => "'''$1''' nuk ekziston.",
'filedelete-nofile-old' => "Nuk ka version të arkivuar të '''$1''' me të dhënat e kërkuara.",
'filedelete-otherreason' => 'Arsye tjetër / shtesë:',
'filedelete-reason-otherlist' => 'Arsye tjetër',
'filedelete-reason-dropdown' => '*Arsye të shpeshpërdorura për grisje:
** Kundër të drejtave të autorit
** Skedë kopje',
'filedelete-edit-reasonlist' => 'Arsye grisjeje për redaktimet',
'filedelete-maintenance' => 'Grisja dhe restaurimi i skedave është çaktivizuar përkohësisht gjatë mirëmbajtjes.',
'filedelete-maintenance-title' => 'Nuk mund të grisë skedën',

# MIME search
'mimesearch' => 'Kërkime MIME',
'mimesearch-summary' => 'Kjo faqe lejon kërkimin e skedave sipas llojit MIME. Kërkimi duhet të jetë i llojit: contenttype/subtype, p.sh. <code>image/jpeg</code>.',
'mimetype' => 'Lloji MIME:',
'download' => 'shkarkim',

# Unwatched pages
'unwatchedpages' => 'Faqe të pambikqyrura',

# List redirects
'listredirects' => 'Lista e përcjellimeve',

# Unused templates
'unusedtemplates' => 'Stampa të papërdorura',
'unusedtemplatestext' => 'Kjo faqe radhitë të gjitha faqet në {{ns:template}} që nuk janë të përfshira në faqe tjera.
Mos harroni të shihni nyje tjera të stampave para grisjes së tyre.',
'unusedtemplateswlh' => 'lidhje',

# Random page
'randompage' => 'Faqe e rastit',
'randompage-nopages' => 'Nuk ka faqe në {{PLURLA:$2|hapësirën|hapësirat}} në vijim: $1',

# Random redirect
'randomredirect' => 'Përcjellim i rastit',
'randomredirect-nopages' => 'Nuk ka përcjellim në "$1".',

# Statistics
'statistics' => 'Statistika',
'statistics-header-pages' => 'Statistikat e faqes',
'statistics-header-edits' => 'Statistikat e redaktimit',
'statistics-header-views' => 'Statistikat e shikimit',
'statistics-header-users' => 'Statistikat e përdoruesve',
'statistics-header-hooks' => 'Statistikat të tjera',
'statistics-articles' => 'Përmbajtja e faqeve',
'statistics-pages' => 'Faqet',
'statistics-pages-desc' => 'Të gjitha faqet në wiki, duke përfshitë edhe faqet e diskutimit, zhvendosjet, etj.',
'statistics-files' => 'Skedat e ngarkuara',
'statistics-edits' => 'Redaktimet e faqes që kur {{SITENAME}} u regjistrua',
'statistics-edits-average' => 'Ndryshime mesatare për faqe',
'statistics-views-total' => 'Shikimet gjithsej',
'statistics-views-total-desc' => 'Shikimet tek faqet joekzistuese dhe faqet speciale nuk janë të përfshira',
'statistics-views-peredit' => 'Shikimet për redaktim',
'statistics-users' => '[[Special:ListUsers|Përdoruesit]] e regjistruar',
'statistics-users-active' => 'Përdoruesit aktiv',
'statistics-users-active-desc' => 'Përdoruesit që kanë së paku një veprim në {{PLURAL:$1|ditën|$1 ditët}} e fundit',
'statistics-mostpopular' => 'Faqet më të shikuara',

'disambiguations' => 'Faqet që lidhen te faqet kthjelluese',
'disambiguationspage' => 'Template:Kthjellim',
'disambiguations-text' => "Faqet e mëposhtme lidhen tek një '''faqe kthjelluese'''.
Ato duhet të kenë lidhje të drejtpërdrejtë tek artikujt e nevojshëm.<br />
Një faqe trajtohet si faqe kthjelluese nëse përdor stampat e lidhura nga [[MediaWiki:Disambiguationspage]]",

'doubleredirects' => 'Përcjellime dopjo',
'doubleredirectstext' => "Kjo faqe liston faqet përcjellëse tek faqet e tjera përcjellëse.
Secili rresht përmban lidhjet tek përcjellimi i parë dhe përcjellimi i dytë, gjithashtu synimin e përcjellimit të dytë, që është zakonisht faqja synuese '''e vërtetë''', që faqja w parë duhej të ishte përcjellëse e kësaj faqeje.
<del>Kalimet nga</del> hyrjet janë zgjidhur.",
'double-redirect-fixed-move' => '[[$1]] u zhvendos, tani është gjendet në [[$2]]',
'double-redirect-fixed-maintenance' => 'Duke zgjidhur përcjellimin e dyfishtë nga [[$1]] tek [[$2]].',
'double-redirect-fixer' => 'Rregullues zhvendosjesh',

'brokenredirects' => 'Përcjellime të prishura',
'brokenredirectstext' => "Përcjellimet që vijojnë lidhen tek një artikull që s'ekziston:",
'brokenredirects-edit' => 'redakto',
'brokenredirects-delete' => 'grise',

'withoutinterwiki' => 'Artikuj pa lidhje interwiki',
'withoutinterwiki-summary' => 'Artikujt në vazhdim nuk kanë asnjë lidhje te wikit në gjuhët tjera:',
'withoutinterwiki-legend' => 'Parashtesa',
'withoutinterwiki-submit' => 'Trego',

'fewestrevisions' => 'Artikuj më të paredaktuar',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|byte}}',
'ncategories' => '$1 {{PLURAL:$1|kategori|kategori}}',
'nlinks' => '$1 {{PLURAL:$1|lidhje|lidhje}}',
'nmembers' => '$1 {{PLURAL:$1|antar|antarë}}',
'nrevisions' => '$1 {{PLURAL:$1|version|versione}}',
'nviews' => '$1 {{PLURAL:$1|shikim|shikime}}',
'nimagelinks' => 'Përdorur në $1 {{PLURAL:$1|faqe|faqe}}',
'ntransclusions' => 'përdorur në $1 {{PLURAL:$1|faqe|faqe}}',
'specialpage-empty' => 'Kjo faqe është boshe.',
'lonelypages' => 'Artikuj të palidhur',
'lonelypagestext' => 'Faqet në vijim nuk janë të lidhura ose nuk janë të përfshira në faqet tjera në {{SITENAME}}.',
'uncategorizedpages' => 'Artikuj të pakategorizuar',
'uncategorizedcategories' => 'Kategori të pakategorizuara',
'uncategorizedimages' => 'Figura pa kategori',
'uncategorizedtemplates' => 'Stampa të pakategorizuara',
'unusedcategories' => 'Kategori të papërdorura',
'unusedimages' => 'Figura të papërdorura',
'popularpages' => 'Artikuj të frekuentuar shpesh',
'wantedcategories' => 'Kategori më të dëshiruara',
'wantedpages' => 'Artikuj më të dëshiruar',
'wantedpages-badtitle' => 'Titull i pavlefshëm në vendosjen e rezultateve: $1',
'wantedfiles' => 'Skedat e dëshiruara',
'wantedfiletext-cat' => 'Skedarët vijues janë përdorur por nuk ekzistojnë. Skedarët nga depot e panjohura mund të listohen megjithëse nuk ekzistojnë. Ndonjë gjë pozitive e pavërtetë e tillë do të <del>largohet</del>. Për më tepër, faqet që vendosin skedarë që nuk ekzistojnë janë listuar në [[:$1]].',
'wantedfiletext-nocat' => 'Skedarët vijues janë përdorur por nuk ekzistojnë. Skedarët nga depot e panjohura mund të listohen megjithëse nuk ekzistojnë. Ndonjë gjë pozitive e pavërtetë e tillë do të <del>largohet</del>.',
'wantedtemplates' => 'Stampat e dëshiruara',
'mostlinked' => 'Artikuj më të lidhur',
'mostlinkedcategories' => 'Kategori më të lidhura',
'mostlinkedtemplates' => 'Stampa më të lidhur',
'mostcategories' => 'Artikuj më të kategorizuar',
'mostimages' => 'Figura më të lidhura',
'mostrevisions' => 'Artikuj më të redaktuar',
'prefixindex' => 'Të gjitha faqet me parashtesa',
'prefixindex-namespace' => 'Të gjitha faqet me parashtesë (hapësira $1)',
'shortpages' => 'Artikuj të shkurtër',
'longpages' => 'Artikuj të gjatë',
'deadendpages' => 'Artikuj pa rrugëdalje',
'deadendpagestext' => 'Artikujt në vijim nuk kanë asnjë lidhje me artikuj e tjerë në këtë wiki.',
'protectedpages' => 'Faqe të mbrojtura',
'protectedpages-indef' => 'Vetëm mbrojtjet pa afat',
'protectedpages-cascade' => 'Vetëm mbrojtjet',
'protectedpagestext' => 'Faqet e mëposhtme janë të mbrojtura nga zhvendosja apo redaktimi',
'protectedpagesempty' => 'Nuk ka faqe të mbrojtura me të dhënat e kërkuara.',
'protectedtitles' => 'Titujt e mbrojtur',
'protectedtitlestext' => 'Krijimi i këtyre titujve është i mbrojtur',
'protectedtitlesempty' => 'Asnjë titull i mbrojtur nuk u gjet në këtë hapësirë.',
'listusers' => 'Lista e përdoruesve',
'listusers-editsonly' => 'Trego vetëm përdoruesit me redaktime',
'listusers-creationsort' => 'Radhiti sipas datës së krijimit',
'usereditcount' => '$1 {{PLURAL:$1|redaktim|redaktime}}',
'usercreated' => '{{GENDER:$3|Krijuar}} më $1 në $2',
'newpages' => 'Artikuj të rinj',
'newpages-username' => 'Përdoruesi:',
'ancientpages' => 'Artikuj më të vjetër',
'move' => 'Zhvendose',
'movethispage' => 'Zhvendose faqen',
'unusedimagestext' => 'Skedat e mëposhtme ekzistojnë por nuk janë të përdorura në një ndonjë faqe.
Ju lutemi, vini re se faqe të tjera në rrjet si mund të lidhin një figurë me një URL në mënyrë direkte, kështuqë ka mundësi që këto figura të rreshtohen këtu megjithëse janë në përdorim.',
'unusedcategoriestext' => 'Kategoritë në vazhdim ekzistojnë edhe pse asnjë artikull ose kategori nuk i përdor ato.',
'notargettitle' => 'Asnjë artikull',
'notargettext' => 'Nuk keni dhënë asnjë artikull ose përdorues mbi të cilin të përdor këtë funksion.',
'nopagetitle' => 'Faqja e kërkuar nuk ekziston',
'nopagetext' => 'Faqja e kërkuar nuk ekziston.',
'pager-newer-n' => '{{PLURAL:$1|1 më i reja|$1 më të reja}}',
'pager-older-n' => '{{PLURAL:$1|1 më i vjetër|$1 më të vjetra}}',
'suppress' => 'Kujdestari',
'querypage-disabled' => 'Kjo faqe speciale është çaktivizuar për arsye të performancës.',

# Book sources
'booksources' => 'Burime librash',
'booksources-search-legend' => 'Kërkim burimor librash',
'booksources-go' => 'Shko',
'booksources-text' => 'Më posht është një listë me lidhje të cilët shesin ose përdorin libra dhe munden të kenë informacione për librat që kërkoni ju:',
'booksources-invalid-isbn' => 'ISBN-ja e dhënë nuk duket të jetë e vlefshme; kontrolloni oër gabime gjatë kopjimit nga burimi origjinal.',

# Special:Log
'specialloguserlabel' => 'Performuesi:',
'speciallogtitlelabel' => 'Objektivi (titulli ose përdoruesi):',
'log' => 'Regjistrat',
'all-logs-page' => 'Të gjitha regjistrat',
'alllogstext' => 'Kjo faqe tregon të gjithë regjistrat e mundshëm të {{SITENAME}}.
Ju mund të kufizoni pamje sipas tipit të regjistrit, emrit të përdoruesit (shumë i ndjeshëm), dhe faqes në çështje (edhe rastet e ndjeshme)',
'logempty' => 'Nuk ka asnjë përputhje në regjistër.',
'log-title-wildcard' => 'Kërko tituj që fillojnë me këtë tekst',
'showhideselectedlogentries' => 'Paraqit/fshih shënimet e përzgjedhura të regjistruara.',

# Special:AllPages
'allpages' => 'Të gjitha faqet',
'alphaindexline' => '$1 deri në $2',
'nextpage' => 'Faqja më pas ($1)',
'prevpage' => 'Faqja më parë ($1)',
'allpagesfrom' => 'Trego faqet duke filluar nga:',
'allpagesto' => 'Shfaq faqet që mbarojnë në:',
'allarticles' => 'Të gjithë artikujt',
'allinnamespace' => 'Të gjitha faqet (hapësira $1)',
'allnotinnamespace' => 'Të gjitha faqet (jo në hapësirën $1)',
'allpagesprev' => 'Më para',
'allpagesnext' => 'Më pas',
'allpagessubmit' => 'Shko',
'allpagesprefix' => 'Trego faqet me parashtesë:',
'allpagesbadtitle' => 'Titulli i dhënë ishte i pavlefshë ose kishte një parashtesë ndër-gjuhe ose ndër-wiki.
Mund të përmbajë një ose më shumë karktere të cilat nuk mund të përdoren në tituj.',
'allpages-bad-ns' => '{{SITENAME}} nuk ka hapësirë "$1".',
'allpages-hide-redirects' => 'Fshih përcjelljet',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Ju jeni duke e parë një version të ruajtur të kësaj faqe, që mund të jetë deri $1 e vjetër',
'cachedspecial-viewing-cached-ts' => 'Ju jeni duke e parë një version të ruajtur të kësaj faqe, që mund të mos jetë tërësisht e pranishme.',
'cachedspecial-refresh-now' => 'Shikoni të fundit.',

# Special:Categories
'categories' => 'Kategori',
'categoriespagetext' => '{{PLURAL:$1|kategoria në vijim përmban|kategoritë në vikim përmbajnë}} faqe ose media.
[[Special:UnusedCategories|Kategoritë e pa përdorura]] nuk janë të paraqitura këtu.
Shikoni edhe [[Special:WantedCategories|kategoritë e dëshiruara]].',
'categoriesfrom' => 'Paraqit kategoritë duke filluar në:',
'special-categories-sort-count' => 'radhit sipas numrit',
'special-categories-sort-abc' => 'radhiti sipas alfabetit',

# Special:DeletedContributions
'deletedcontributions' => 'Kontribute të grisura',
'deletedcontributions-title' => 'Kontribute të grisura',
'sp-deletedcontributions-contribs' => 'kontributet',

# Special:LinkSearch
'linksearch' => 'Kërkim lidhjesh të jashtme',
'linksearch-pat' => 'Motivi kërkimor:',
'linksearch-ns' => 'Hapësira:',
'linksearch-ok' => 'Kërko',
'linksearch-text' => 'Ylli zëvëndësues mund të përdoret si p.sh. "*.wikipedia.org".
Duhet një domen top-nivel, si p.sh. "*.org.<br />
Protokolle të mbështetura: <code>$1<code> (mos shtoni ndonjërin nga këta në kërkimin tuaj).',
'linksearch-line' => '$1 lidhur nga $2',
'linksearch-error' => 'Ylli mund të përdoret vetëm në fillim të emrit',

# Special:ListUsers
'listusersfrom' => 'Trego përdoruesit duke filluar prej te:',
'listusers-submit' => 'Trego',
'listusers-noresult' => "Asnjë përdorues s'u gjet.",
'listusers-blocked' => '(Bllokuar)',

# Special:ActiveUsers
'activeusers' => 'Lista e përdoruesve aktivë',
'activeusers-intro' => 'Kjo është një listë e përdoruesve që kanë qenë aktivë për $1 {{PLURAL:$1|ditë|ditë}}.',
'activeusers-count' => '$1 {{PLURAL:$1|redaktim|redaktime}} në {{PLURAL:$3|ditën|$3 ditët}} e fundit',
'activeusers-from' => 'Trego përdoruesit duke filluar prej te:',
'activeusers-hidebots' => 'Fshih robotët',
'activeusers-hidesysops' => 'Fshih administratorët',
'activeusers-noresult' => 'Asnjë përdorues nuk u gjet.',

# Special:Log/newusers
'newuserlogpage' => 'Regjistri i llogarive',
'newuserlogpagetext' => 'Ky është një regjistër i llogarive të fundit që janë hapur',

# Special:ListGroupRights
'listgrouprights' => 'Grupime përdoruesish me privilegje',
'listgrouprights-summary' => 'Më poshtë jepet grupimi i përdoruesve sipas privilegjeve që ju janë dhënë në këtë wiki. Më shumë informacion rreth privilegjeve në veçanti mund të gjendet [[{{MediaWiki:Listgrouprights-helppage}}|këtu]].',
'listgrouprights-key' => '* <span class="listgrouprights-granted">E drejtë e garantuar</span>
* <span class="listgrouprights-revoked">E drejtë e revokuar</span>',
'listgrouprights-group' => 'Grupi',
'listgrouprights-rights' => 'Privilegjet',
'listgrouprights-helppage' => 'Help:Grupime privilegjesh',
'listgrouprights-members' => '(lista e anëtarëve)',
'listgrouprights-addgroup' => 'Mund të vendosë {{PLURAL:$2|grup|grupe}}: $1',
'listgrouprights-removegroup' => 'Mund të {{PLURAL:$2|lëvizet grupi|lëvizen grupet}}: $1',
'listgrouprights-addgroup-all' => 'Mund të vendos të gjitha grupet',
'listgrouprights-removegroup-all' => 'Mund të largojë të gjitha grupet',
'listgrouprights-addgroup-self' => 'Shtoni {{PLURAL:$2|grupin|grupet}} tek llogaria: $1',
'listgrouprights-removegroup-self' => 'Hiqni {{PLURAL:$2|grupin|grupet}} nga llogaria: $1',
'listgrouprights-addgroup-self-all' => 'Shtoni të gjitha grupet tek llogaria',
'listgrouprights-removegroup-self-all' => 'Hiq të gjitha grupet nga llogaria',

# E-mail user
'mailnologin' => "S'ka adresë dërgimi",
'mailnologintext' => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] dhe të keni një adresë të saktë në [[Special:Preferences|parapëlqimet]] tuaja për tu dërguar email përdoruesve të tjerë.',
'emailuser' => 'Email përdoruesit',
'emailpage' => 'Dërgo email përdoruesve',
'emailpagetext' => 'Mund të përdorni formularin e mëposhtëm për të dërguar e-mail tek ky përdorues.
Adresa e email-it që shkruat tek [[Special:Preferences|preferencat tuaja]] do të duket si "Nga" adresa e email-it, pra marrësi do të ketë mundësinë t\'ju përgjigjet direkt.',
'usermailererror' => 'Objekti postal ktheu gabimin:',
'defemailsubject' => '{{SITENAME}} posta elektronike nga përdoruesi "$1"',
'usermaildisabled' => 'Email-i i përdoruesit çaktivizua',
'usermaildisabledtext' => 'Ju nuk mund të dërgoni e-mail tek përdoruesit e tjerë në këtë wiki',
'noemailtitle' => "S'ka adresë email-i",
'noemailtext' => 'Ky përdorues nuk ka përcaktuar një adresë të vlefshme e-mail.',
'nowikiemailtitle' => 'Nuk lejohet postë elektronike',
'nowikiemailtext' => 'Ky përdorues ka zgjedhur të mos pranojë porosi elektronike nga përdoruesit tjerë.',
'emailnotarget' => 'Nofka jo ekzistuese ose e pavlefshme për marrësin',
'emailtarget' => 'Shkruani nofkën e marrësit',
'emailusername' => 'Nofka:',
'emailusernamesubmit' => 'Paraqit',
'email-legend' => 'Dërgoni porosi elektronike një përdoruesi të {{SITENAME}}',
'emailfrom' => 'Nga:',
'emailto' => 'Për:',
'emailsubject' => 'Subjekti:',
'emailmessage' => 'Porosia:',
'emailsend' => 'Dërgo',
'emailccme' => 'Ma dërgo edhe mua një kopje të këtij emaili.',
'emailccsubject' => 'Kopje e emailit tuaj për $1: $2',
'emailsent' => 'Email-i u dërgua',
'emailsenttext' => 'Email-i është dërguar.',
'emailuserfooter' => 'Kjo porosi elektronike u dërgua nga $1 tek $2 nga "Dërgoi postë elektronike përdoruesit" funksion në {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Lënia e mesazhit të sistemit.',
'usermessage-editor' => 'I dërguari i sistemit',

# Watchlist
'watchlist' => 'Lista mbikqyrëse',
'mywatchlist' => 'Lista mbikqyrëse',
'watchlistfor2' => 'Për $1 $2',
'nowatchlist' => 'Nuk keni asnjë faqe në listën mbikqyrëse.',
'watchlistanontext' => 'Ju lutemi $1 për të parë redaktimet e artikujve në listë tuaj mbikqyrëse.',
'watchnologin' => 'Nuk keni hyrë brënda',
'watchnologintext' => 'Duhet të keni [[Special:UserLogin|hyrë brenda]] për të ndryshuar listën mbikqyrëse.',
'addwatch' => 'Shto tek lista mbikqyrëse',
'addedwatchtext' => "Faqja \"[[:\$1]]\"  i është shtuar [[Special:Watchlist|listës mbikqyrëse]] tuaj. Ndryshimet e ardhshme të kësaj faqeje dhe faqes së diskutimit të saj do të jepen më poshtë, dhe emri i faqes do të duket i '''trashë''' në [[Special:RecentChanges|listën e ndryshimeve së fundmi]] për t'i dalluar më kollaj.

Në qoftë se dëshironi të hiqni një faqe nga lista mbikqyrëse më vonë, shtypni \"çmbikqyre\" në tabelën e sipërme.",
'removewatch' => 'Largo nga lista mbikqyrëse',
'removedwatchtext' => 'Faqja "[[:$1]]" është hequr nga [[Special:Watchlist|lista mbikqyrëse e juaj]].',
'watch' => 'Mbikqyre',
'watchthispage' => 'Mbikqyre këtë faqe',
'unwatch' => 'Çmbikqyre',
'unwatchthispage' => 'Mos e mbikqyr',
'notanarticle' => 'Nuk është artikull',
'notvisiblerev' => 'Revizioni është grisur',
'watchnochange' => 'Asnjë nga artikujt nën mbikqyrje nuk është redaktuar gjatë kohës së dhënë.',
'watchlist-details' => '{{PLURAL:$1|$1 faqe|$1 faqe}} nën mbikqyrje duke mos numëruar faqet e diskutimit.',
'wlheader-enotif' => '* Njoftimi me email është lejuar.',
'wlheader-showupdated' => "* Faqet që kanë ndryshuar nga vizita juaj e fundit do të tregohen të '''trasha'''",
'watchmethod-recent' => 'duke parë ndryshimet e fundit për faqet nën mbikqyrje',
'watchmethod-list' => 'duke parë faqet nën mbikqyrje për ndryshimet e fundit',
'watchlistcontains' => 'Lista mbikqyrëse e juaj ka $1 {{PLURAL:$1|faqe|faqe}}.',
'iteminvalidname' => "Problem me artikullin '$1', titull jo i saktë...",
'wlnote' => "Më poshtë {{PLURAL:$1|është ndryshimi i fundit|janë '''$1''' ndryshimet e fundit}} në {{PLUARAL:$2:orën e fundit|'''$2''' orët e fundit}}, që nga $3, $4.",
'wlshowlast' => 'Trego $1 orët $2 ditët $3',
'watchlist-options' => 'Mundësitë e listës mbikqyrëse',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'Duke mbikqyrur...',
'unwatching' => 'Duke çmbikqyrur...',
'watcherrortext' => 'Është paraqitur një gabim përderisa ndryshuat parametrat e listës suaj mbikqyrëse për "$1".',

'enotif_mailer' => 'Postieri Njoftues i {{SITENAME}}',
'enotif_reset' => 'Shëno të gjitha faqet e vizituara',
'enotif_newpagetext' => 'Kjo është një faqe e re.',
'enotif_impersonal_salutation' => 'Përdorues i {{SITENAME}}',
'changed' => 'ndryshuar',
'created' => 'u krijua',
'enotif_subject' => '{{SITENAME}} faqja $PAGETITLE u $CHANGEDORCREATED prej $PAGEEDITOR',
'enotif_lastvisited' => 'Shikoni $1 për të gjitha ndryshimet që prej vizitës tuaj të fundit.',
'enotif_lastdiff' => 'Shikoni $1 për ndryshime.',
'enotif_anon_editor' => 'përdorues anonim $1',
'enotif_body' => 'I Nderuar $WATCHINGUSERNAME,


Kjo {{SITENAME}} faqe $PAGETITLE eshte $CHANGEDORCREATED on $PAGEEDITDATE by $PAGEEDITOR, see $PAGETITLE_URL per versioni mo i ri.

$NEWPAGE

Editor\'s summary: $PAGESUMMARY $PAGEMINOREDIT
Kontakto:
mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

There will be no other notifications in case of further changes unless you visit this page.
You could also reset the notification flags for all your watched pages on your watchlist.

Your friendly {{SITENAME}} notification system

--
To change your email notification settings, visit
{{canonicalurl:{{#special:Preferences}}}}

To change your watchlist settings, visit
{{canonicalurl:{{#special:EditWatchlist}}}}

To delete the page from your watchlist, visit
$UNWATCHURL

Feedback and further assistance:
{{canonicalurl:{{MediaWiki:Helppage}}}}

Faqja $PAGETITLE tek {{SITENAME}} është $CHANGEDORCREATED më $PAGEEDITDATE nga $PAGEEDITOR, shikoni $PAGETITLE_URL për versionin e tanishëm.

$NEWPAGE

Përmbledhja e redaktorit: $PAGESUMMARY $PAGEMINOREDIT

Mund të lidheni me redaktorin nëpërmjet:
email: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Nuk do të ketë njoftime të tjera për ndryshimet e ardhshme përveç nëse e vizitoni faqen. Gjithashtu mund të ktheni gjendjen e njoftimeve për të gjitha faqet nën mbikqyrje.

             Miku juaj njoftues nga {{SITENAME}}

--
Për të ndryshuar parapëlqimet e mbikqyrjes shikoni {{canonicalurl:Special:Watchlist/edit}}

Për të larguar faqen nga lista juaj mbikqyrëse, shikoni 
$UNWATCHURL

Për të na dhënë përshtypjet tuaja ose për ndihmë të mëtejshme:
{{canonicalurl:{{MediaWiki:Helpage}}}}',

# Delete
'deletepage' => 'Grise faqen',
'confirm' => 'Konfirmoni',
'excontent' => "përmbajtja ishte: '$1'",
'excontentauthor' => "përmbajtja ishte: '$1' (dhe i vetmi redaktor ishte '$2')",
'exbeforeblank' => "përmbajtja para boshatisjes ishte: '$1'",
'exblank' => 'faqja është bosh',
'delete-confirm' => 'Grise "$1"',
'delete-legend' => 'Grise',
'historywarning' => "'''Kujdes:''' Kjo faqe të cilën po e grisni ka histori me rreth $1 
{{PLURAL:$1|version|redaktime}}:",
'confirmdeletetext' => 'Jeni duke grisur një faqe me tërë historinë e saj. Ju lutemi konfirmoni që po e bëni qëllimisht, që i kuptoni pasojat, dhe që po veproni në përputhje me [[{{MediaWiki:Policy-url}}]].',
'actioncomplete' => 'Veprimi u krye',
'actionfailed' => 'Veprimi dështoi',
'deletedtext' => '"$1" është grisur nga regjistri. Shikoni $2 për një pasqyrë të grisjeve së fundmi.',
'dellogpage' => 'Regjistri i grisjeve',
'dellogpagetext' => 'Më poshtë është një listë e grisjeve më të fundit.',
'deletionlog' => 'regjistrin e grisjeve',
'reverted' => 'Kthehu tek një version i vjetër',
'deletecomment' => 'Arsyeja:',
'deleteotherreason' => 'Arsye tjetër:',
'deletereasonotherlist' => 'Arsyeja tjetër',
'deletereason-dropdown' => '*Arsye për grisje:
** Pa të drejtë autori
** Kërkesë nga autori
** Vandalizëm',
'delete-edit-reasonlist' => 'Ndrysho arsyet e grisjes',
'delete-toobig' => 'Kjo faqe ka një historik të madh redaktimesh, më shumë se $1 {{PLURAL:$1|version|versione}}.
Grisja e faqeve të tilla ka qenë kufizuar për të parandaluar përçarjen aksidentale të {{SITENAME}}.',
'delete-warning-toobig' => 'Kjo faqe ka një historik të madh redaktimesh, më shumë se $1 {{PLURAL:$1|version|versione}}.
Grisja e saj mund të ndërpresë operacionet e bazës së të dhënave të {{SITENAME}};
vazhdoni me kujdes.',

# Rollback
'rollback' => 'Riktheji mbrapsh redaktimet',
'rollback_short' => 'Riktheje',
'rollbacklink' => 'riktheje',
'rollbacklinkcount' => 'riktheni $1 {{PLURAL:$1|ndryshimin|ndryshiemt}}',
'rollbacklinkcount-morethan' => 'riktheni më tepër $1 {{PLURAL:$1|ndryshim|ndryshime}}',
'rollbackfailed' => 'Rikthimi dështoi',
'cantrollback' => 'Redaktimi nuk mund të kthehej;
redaktori i fundit është i vetmi autor i këtij artikulli.',
'alreadyrolled' => 'Nuk mund të rikthehej redaktimi i fundit i [[:$1]] nga [[User:$2|$2]] ([[User talk:$2|diskuto]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); dikush tjetër e ka redaktuar ose rikthyer këtë faqe tashmë.

Redaktimi i fundit është bërë nga [[User:$3|$3]] ([[User talk:$3|diskuto]]{{nt:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "Përmbledhja e redaktimit ishte: \"''\$1''\".",
'revertpage' => 'Ndryshimet e [[Special:Contributions/$2|$2]] ([[User talk:$2|diskutimet]]) u kthyen mbrapsht, artikulli tani ndodhet në versionin e fundit nga [[User:$1|$1]].',
'revertpage-nouser' => 'U rikthyen redaktimet nga (përdoruesi i larguar) në versionin e fundit nga [[User:$1|$1]]',
'rollback-success' => 'Ndryshimet e $1 u kthyen mbrapsh; artikulli ndodhet tek verzioni i $2.',

# Edit tokens
'sessionfailure-title' => 'Dështim sesioni',
'sessionfailure' => 'Duket se ka një problem me seancën tuaj hyrëse; ky veprim është anuluar për tu mbrojtur nga ndonjë veprim dashakeq kundrejt shfletimit tuaj. Ju lutemi kthehuni mbrapsh, rifreskoni faqen prej nga erdhët dhe provojeni përsëri veprimin.',

# Protect
'protectlogpage' => 'Regjistri i mbrojtjeve',
'protectlogtext' => 'Më poshtë është lista e kyçjeve dhe çkyçjeve të faqes.
Shih listën e [[Special:ProtectedPages|faqeve të mbrojtura]] nga lista e mbrojtjeve të faqeve tani në veprim.',
'protectedarticle' => 'mbrojti [[$1]]',
'modifiedarticleprotection' => 'u ndryshua mbrojtja e faqes "[[$1]]"',
'unprotectedarticle' => 'Largo mbrojtjen nga " [[$1]] "',
'movedarticleprotection' => 'u bartën të dhënat e mbrojtjes nga "[[$2]]" në "[[$1]]"',
'protect-title' => 'Ndryshoni nivelin e mbrojtjes së "$1"',
'protect-title-notallowed' => 'Shiko nivelin e mbrojtjes së "$1"',
'prot_1movedto2' => '[[$1]] u zhvendos tek [[$2]]',
'protect-badnamespace-title' => 'Hapësirë e pambrojtshme',
'protect-badnamespace-text' => 'Faqet në këtë hapësirë nuk mund të mbrohen.',
'protect-legend' => 'Konfirmoni',
'protectcomment' => 'Arsyeja:',
'protectexpiry' => 'Afati',
'protect_expiry_invalid' => 'Data e skadimit është e gabuar.',
'protect_expiry_old' => 'Data e skadencës është në të shkuarën.',
'protect-unchain-permissions' => 'Zhbllokoni opsionet e mëtejshme të mbrojtjes',
'protect-text' => "Këtu mund të shikoni dhe ndryshoni nivelin e mbrojtjes për faqen '''$1'''.",
'protect-locked-blocked' => "Nuk mund të ndryshoni nivelet e mbrojtjes duke qenë i bllokuar. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-locked-dblock' => "Nivelet e mbrojtjes nuk mund të ndryshohen pasi regjistri është i bllokuar. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-locked-access' => "Llogaria juaj nuk ka privilegjet e nevojitura për të ndryshuar nivelin e mbrojtjes. Kufizimet e kësaj faqeje janë '''$1''':",
'protect-cascadeon' => 'Kjo faqe është e mbrojtur pasi është përfshirë {{PLURAL:$1|këtë faqe që është|këto faqe që janë}} nën mbrojtje "ujëvarë".
Mund të ndryshoni nivelin e mbrojtjes të kësaj faqeje por kjo nuk do të ndryshojë mbrojtjen "ujëvarë".',
'protect-default' => 'Lejoni të gjithë përdoruesit',
'protect-fallback' => 'Kërko leje "$1"',
'protect-level-autoconfirmed' => 'Blloko përdoruesit e rinj dhe ata pa llogari',
'protect-level-sysop' => 'Lejo vetëm administruesit',
'protect-summary-cascade' => 'të varura',
'protect-expiring' => 'skadon me $1 (UTC)',
'protect-expiring-local' => 'Skadon $1',
'protect-expiry-indefinite' => 'i pacaktuar',
'protect-cascade' => 'Mbrojtje e ndërlidhur - mbro çdo faqe që përfshihet në këtë faqe.',
'protect-cantedit' => 'Nuk mund ta ndryshoni nivelin e mbrojtjes të kësaj faqeje sepse nuk keni leje për këtë.',
'protect-othertime' => 'Kohë tjetër:',
'protect-othertime-op' => 'kohë tjetër',
'protect-existing-expiry' => 'Koha ekzistuese e skadimit: $3, $2',
'protect-otherreason' => 'Arsye tjera/shtesë:',
'protect-otherreason-op' => 'Arsyeja tjetër',
'protect-dropdown' => '*Arsyet e përbashkëta të mbrojtjes
**Vandalizëm i tepërt
**Spam i tepërt
**Counter-productive edit warning
**Faqe e lartë trafiku',
'protect-edit-reasonlist' => 'Redakto arsyet e mbrojtjes',
'protect-expiry-options' => '1 Orë:1 hour,1 Ditë:1 day,1 Javë:1 week,2 Javë:2 weeks,1 Muaj:1 month,3 Muaj:3 months,6 Muaj:6 months,1 Vjet:1 year,Pa kufi:infinite',
'restriction-type' => 'Lejet:',
'restriction-level' => 'Mbrojtjet:',
'minimum-size' => 'Madhësia minimale',
'maximum-size' => 'Madhësia maksimale',
'pagesize' => '(B)',

# Restrictions (nouns)
'restriction-edit' => 'Redaktimi',
'restriction-move' => 'Zhvendosja',
'restriction-create' => 'Krijo',
'restriction-upload' => 'Ngarko',

# Restriction levels
'restriction-level-sysop' => 'mbrojtje e plotë',
'restriction-level-autoconfirmed' => 'gjysëm mbrojtje',
'restriction-level-all' => 'çdo nivel',

# Undelete
'undelete' => 'Restauroni faqet e grisura',
'undeletepage' => 'Shikoni ose restauroni faqet e grisura',
'undeletepagetitle' => "'''Në vazhdim janë versionet e grisura të [[:$1|$1]]'''.",
'viewdeletedpage' => 'Shikoni faqet e grisura',
'undeletepagetext' => '{{PLURAL:$1|Faqja në vazhdim është grisur, por akoma është|$1 Faqet në vazhdim janë grisur, por akoma janë}} në arkiv dhe mund të rikthehen.
Arkivi, kohëpaskohe është e mundur të pastrohet.',
'undelete-fieldset-title' => 'Rikthe revizionet',
'undeleteextrahelp' => "Per tu rregeluar histori, zbardh gjith kutit '''''{{int:undeletebtn}}'''''.
To perform a selective restoration, check the boxes corresponding to the revisions to be restored, and click '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|version u fut|versione u futën}} në arkiv',
'undeletehistory' => 'Nëse restauroni një faqe, të gjitha versionet do të restaurohen në histori.
Nëse një faqe e re me të njëjtin titull është krijuar pas grisjes, versionet e restauruara do të paraqiten më mbrapa në histori.',
'undeleterevdel' => 'Restaurimi nuk do të performohet n.q.s. do të rezultojë në majë të versioneve të faqes apo skedës duke u grisur pjesërisht.
Në raste të tilla, ju duhet të çzgjidhni ose shfaqni versionet më të reja të grisura.',
'undeletehistorynoadmin' => 'Kjo faqe është grisur. Arsyeja për grisjen është dhënë tek përmbledhja më poshtë bashkë me hollësitë e përdoruesve që e kanë redaktuar.',
'undelete-revision' => 'Revizioni i grisur i $1 (nga $4, në $5) nga $3:',
'undeleterevision-missing' => 'Version i humbur ose i pavlefshëm.
Ju mund të keni një lidhje të keqe, ose versioni mund të jetë restauruar ose larguar nga arkivi.',
'undelete-nodiff' => 'Nuk u gjetën revizione të mëparshme.',
'undeletebtn' => 'Restauro!',
'undeletelink' => 'shiko/rikthe',
'undeleteviewlink' => 'Pamje',
'undeletereset' => 'Boshatis',
'undeleteinvert' => 'Selektim anasjelltas',
'undeletecomment' => 'Arsyeja:',
'undeletedrevisions' => '$1 {{PLURAL:$1|version u restaurua|versione u restauruan}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|version|versione}} dhe $2 {{PLURAL:$2|skedë|skeda}} janë restauruar',
'undeletedfiles' => '$1 {{PLURAL:$1|skedë u restaurua|skeda u restauruan}}',
'cannotundelete' => 'Restaurimi dështoi; dikush tjetër mund ta ketë restauruar faqen para jush.',
'undeletedpage' => "'''$1 është restauruar'''

Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për grisjet dhe restaurimet së fundmi.",
'undelete-header' => 'Shikoni [[Special:Log/delete|regjistrin e grisjeve]] për faqet e grisura së fundmi.',
'undelete-search-title' => 'Kërko faqet e grisura',
'undelete-search-box' => 'Kërko faqet e grisura',
'undelete-search-prefix' => 'Trego faqet duke filluar nga:',
'undelete-search-submit' => 'Kërko',
'undelete-no-results' => 'Nuk u gjet asnjë faqe përputhëse tek arkivi i grisjeve.',
'undelete-filename-mismatch' => 'Nuk mund të restauroni skeda me timestamp $1: filename mismatch',
'undelete-bad-store-key' => 'Nuk mund të restauroni versionin e skedës me timestamp $1: skeda mungonte para grisjes.',
'undelete-cleanup-error' => 'Gabim në grisjen e skedës "$1" të pa përdorur të arkivit .',
'undelete-missing-filearchive' => 'Nuk mund të restaurohet arkivi ID i skedës $1 sepse nuk është në bazën të dhënave.
Mund të jetë restauruar një herë.',
'undelete-error' => 'Gabim gjatë restaurimit të faqes',
'undelete-error-short' => 'Gabim në rikthimin e skedës: $1',
'undelete-error-long' => 'U hasën gabime gjatë restaurimit të skedës:

$1',
'undelete-show-file-confirm' => 'Jeni të sigurt se dëshironi të shihni redaktimin e grisur të skedës "<nowiki>$1</nowiki>" nga $2 në $3?',
'undelete-show-file-submit' => 'Po',

# Namespace form on various pages
'namespace' => 'Hapësira:',
'invert' => 'Kundër zgjedhjes',
'tooltip-invert' => 'Shëno këtë kuti për të fshehur ndryshimet në faqet përbrenfa hapsirës së selektuar (dhe hapsirës së lidhur nëse e shënuar)',
'namespace_association' => 'Hapsira e lidhur',
'tooltip-namespace_association' => 'Shëno këtë kuti për të përfshijnë gjithashtu diskutimin apo hapsirën e subjektit e lidhur me hapësirën e zgjedhur',
'blanknamespace' => '(Artikujt)',

# Contributions
'contributions' => 'Kontributet',
'contributions-title' => 'Kontributet e përdoruesit për $1',
'mycontris' => 'Kontributet',
'contribsub2' => 'Për $1 ($2)',
'nocontribs' => 'Nuk ka asnjë ndryshim që përputhet me këto kritere.',
'uctop' => ' (sipër)',
'month' => 'Nga muaji (dhe më herët):',
'year' => 'Nga viti (dhe më herët):',

'sp-contributions-newbies' => 'Trego vetëm redaktimet e llogarive të reja',
'sp-contributions-newbies-sub' => 'Për newbies',
'sp-contributions-newbies-title' => 'Kontributet e përdoruesit për kontot e reja',
'sp-contributions-blocklog' => 'Regjistri i bllokimeve',
'sp-contributions-deleted' => 'kontributet e grisura',
'sp-contributions-uploads' => 'ngarkimet',
'sp-contributions-logs' => 'Regjistrat',
'sp-contributions-talk' => 'Diskutoni',
'sp-contributions-userrights' => 'menaxhimi i të drejtave të përdoruesit',
'sp-contributions-blocked-notice' => 'Ky përdorues është i bllokuar.
Bllokimi i fundit është shfaqur më poshtë për referencë:',
'sp-contributions-blocked-notice-anon' => 'Kjo adresë IP është e bllokuar aktualisht.
Bllokimi i funditë është më poshtë për referencë:',
'sp-contributions-search' => 'Kërko tek kontributet',
'sp-contributions-username' => 'IP Addresa ose Përdoruesi:',
'sp-contributions-toponly' => 'Trego vetëm redaktimet që janë versionet më të fundit',
'sp-contributions-submit' => 'Kërko',

# What links here
'whatlinkshere' => 'Lidhjet këtu',
'whatlinkshere-title' => 'Faqe që lidhen tek $1',
'whatlinkshere-page' => 'Faqja:',
'linkshere' => "Faqet e mëposhtme lidhen këtu '''[[:$1]]''':",
'nolinkshere' => "Asnjë faqe nuk lidhet tek '''[[:$1]]'''.",
'nolinkshere-ns' => "Nuk ka faqe në hapësirën e zgjedhur që lidhen tek '''[[:$1]]'''.",
'isredirect' => 'faqe përcjellëse',
'istemplate' => 'përfshirë',
'isimage' => 'Lidhja e dokumentit',
'whatlinkshere-prev' => '{{PLURAL:$1|e kaluara|të kaluarat $1}}',
'whatlinkshere-next' => '{{PLURAL:$1|tjetra|tjerat $1}}',
'whatlinkshere-links' => '← lidhje',
'whatlinkshere-hideredirs' => '$1 përcjellimet',
'whatlinkshere-hidetrans' => '$1 përfshirjet',
'whatlinkshere-hidelinks' => '$1 lidhjet',
'whatlinkshere-hideimages' => '$1 lidhjet me skedat',
'whatlinkshere-filters' => 'Filtra',

# Block/unblock
'autoblockid' => 'Autobllokim #$1',
'block' => 'Blloko përdoruesin',
'unblock' => 'Zhblloko përdoruesin',
'blockip' => 'Blloko përdorues',
'blockip-title' => 'Përdorues i Bllokuar',
'blockip-legend' => 'Blloko përdoruesin',
'blockiptext' => 'Përdorni formularin e mëposhtëm për të hequr lejen e shkrimit për një përdorues ose IP specifike.
Kjo duhet bërë vetëm në raste vandalizmi, dhe në përputhje me [[{{MediaWiki:Policy-url}}|rregullat e {{SITENAME}}-s]].
Plotësoni arsyen specifike më poshtë (p.sh., tregoni faqet specifike që u vandalizuan).',
'ipadressorusername' => 'Adresë IP ose emër përdoruesi',
'ipbexpiry' => 'Afati',
'ipbreason' => 'Arsyeja:',
'ipbreasonotherlist' => 'Arsye tjetër',
'ipbreason-dropdown' => '*Arsyet më të shpeshta të bllokimit
** Postimi i informacioneve të rreme
** Largimi i përmbajtjes së faqes
** Futja e lidhjeve "spam"
** Futja e informatave pa kuptim në faqe
** Sjellje arrogante/perverze
** Përdorimi i më shumë llogarive të përdoruesve
** Nofkë të papranueshme',
'ipb-hardblock' => 'Parandalo përdoruesit e kyçur për të redaktuar nga kjo IP adresë',
'ipbcreateaccount' => 'Mbroje krijimin e llogarive',
'ipbemailban' => 'Pa mundëso dërgimin  e porosive elektronike nga përdoruesit',
'ipbenableautoblock' => 'Blloko edhe IP adresën që ka përdor ky përdorues deri tash, si dhe të gjitha subadresat nga të cilat mundohet ky përdorues të editoj.',
'ipbsubmit' => 'Blloko këtë përdorues',
'ipbother' => 'Kohë tjetër',
'ipboptions' => '2 Orë:2 hours,1 Ditë:1 day,3 Ditë:3 days,1 Javë:1 week,2 Javë:2 weeks,1 Muaj:1 month,3 Muaj:3 months,6 Muaj:6 months,1 Vjet:1 year,Pa kufi:infinite',
'ipbotheroption' => 'tjetër',
'ipbotherreason' => 'Arsye tjetër/shtesë',
'ipbhidename' => 'Fshih emrat e përdorueseve nga redaktimet dhe listat',
'ipbwatchuser' => 'Shiko faqen e prezantimit dhe diskutimit të këtij përdoruesi',
'ipb-disableusertalk' => 'Parandalo këtë përdorues për të redaktuar faqe-diskutimin e tyre përderisa janë të bllokkuar',
'ipb-change-block' => 'Ri-blloko përdorues me këta parametra',
'ipb-confirm' => 'Konfirmo bllokimin',
'badipaddress' => 'Nuk ka asnjë përdorues me atë emër',
'blockipsuccesssub' => 'Bllokimi u bë me sukses',
'blockipsuccesstext' => 'Përdoruesi/IP-Adresa [[Special:Contributions/$1|$1]] u bllokua.<br />
Shiko te [[Special:BlockList|Lista e përdoruesve dhe e IP adresave të bllokuara]] për të çbllokuar Përdorues/IP.',
'ipb-blockingself' => 'Ju jeni duke bllokuar vetëveten ! Jeni te sigurte qe doni te bëni këtë?',
'ipb-confirmhideuser' => 'Ju jeni gati për të bllokuar një përdorues me "përdorues të fshehur" të aktivizuar. Kjo do të shtypur emrin e përdoruesit në të gjitha listat dhe aktivitetet hyrëse. Jeni te sigurte qe doni ta bëni këtë ?',
'ipb-edit-dropdown' => 'Redakto arsyet e bllokimit',
'ipb-unblock-addr' => 'Çblloko $1',
'ipb-unblock' => 'Çblloko përdorues dhe IP të bllokuara',
'ipb-blocklist' => 'Përdorues dhe IP adresa të bllokuara',
'ipb-blocklist-contribs' => 'Kontributet për $1',
'unblockip' => 'Çblloko përdoruesin',
'unblockiptext' => "Përdor formularin e më poshtëm për t'i ridhënë leje shkrimi
një përdoruesi ose IP adreseje të bllokuar.",
'ipusubmit' => 'Hiqni këtë bllokim',
'unblocked' => '[[User:$1|$1]] është çbllokuar',
'unblocked-range' => '$1 është zhbllokuar',
'unblocked-id' => 'Bllokimi $1 është hequr',
'blocklist' => 'Përdorues i Bllokuar',
'ipblocklist' => 'Përdorues i Bllokuar',
'ipblocklist-legend' => 'Gjej një përdorues të bllokuar',
'blocklist-userblocks' => 'Fsheh bllokimin e llogarisë',
'blocklist-tempblocks' => 'Fsheh bllokimin e përkohshëm',
'blocklist-addressblocks' => 'Fsheh bllokimin e IP vetanake',
'blocklist-rangeblocks' => 'Fsheh varg bllokimet',
'blocklist-timestamp' => 'Kohë-caktimi',
'blocklist-target' => 'Objektivi',
'blocklist-expiry' => 'Skadon',
'blocklist-by' => 'Administratori i bllokimit',
'blocklist-params' => 'Parametrat e Bllokimit',
'blocklist-reason' => 'Arsyeja',
'ipblocklist-submit' => 'Kërko',
'ipblocklist-localblock' => 'Bllokim lokal',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Bllokim tjetër|Bllokime të tjera}}',
'infiniteblock' => 'pakufi',
'expiringblock' => 'skadon më $1 në $2',
'anononlyblock' => 'vetëm anonimët',
'noautoblockblock' => 'autobllokimi është çaktivizuar',
'createaccountblock' => 'hapja e lloggarive është bllokuar',
'emailblock' => 'email është bllokuar',
'blocklist-nousertalk' => 'nuk mund të editohet faqja personale e diskutimit',
'ipblocklist-empty' => 'Lista e të bllokimeve është e zbrazët.',
'ipblocklist-no-results' => 'Adresa IP ose përdoruesi i kërkuar nuk është i bllokuar.',
'blocklink' => 'blloko',
'unblocklink' => 'çblloko',
'change-blocklink' => 'ndryshoje bllokun',
'contribslink' => 'kontribute',
'emaillink' => 'dërgo e-mail',
'autoblocker' => 'Bllokuar automatikisht sepse adresa juaj IP është përdorur së fundmi nga "[[User:$1|$1]]".
Arsyeja e dhënë për bllokimin e $1 është: "$2"',
'blocklogpage' => 'Regjistri i bllokimeve',
'blocklog-showlog' => 'Ky përdorues ka qenë bllokuar më parë.
Regjistri i bllokimeve është poshtë për referncë:',
'blocklog-showsuppresslog' => 'Ky përdorues ka qenë i bllokuar dhe i fshehur më parë.
Regjistri i bllokimeve është poshtë për referncë:',
'blocklogentry' => 'bllokoi [[$1]] për një kohë prej: $2 $3',
'reblock-logentry' => 'ndryshoi parametrat e bllokimit për [[$1]] me një kohë prej $2 $3',
'blocklogtext' => 'Ky është një regjistër bllokimesh dhe çbllokimesh të përdoruesve. IP-të e bllokuara automatikisht nuk janë të dhëna. Shikoni dhe [[Special:BlockList|listën e IP-ve të bllokuara]] për një listë të bllokimeve të tanishme.',
'unblocklogentry' => 'çbllokoi "$1"',
'block-log-flags-anononly' => 'vetëm anonimët',
'block-log-flags-nocreate' => 'krijimi i llogarive është pamundësuar',
'block-log-flags-noautoblock' => 'vetëbllokimi është pamundësuar',
'block-log-flags-noemail' => 'posta elektronike është e bllokuar',
'block-log-flags-nousertalk' => 'nuk mund të redaktojë faqen e tij të diskutimit',
'block-log-flags-angry-autoblock' => 'Autobllokimi i zgjeruar u aktivizua',
'block-log-flags-hiddenname' => 'emri i përdoruesit i fshehur',
'range_block_disabled' => 'Mundësia e administruesve për të bllokuar me shtrirje është çaktivizuar.',
'ipb_expiry_invalid' => 'Afati i kohës është gabim.',
'ipb_expiry_temp' => 'Bllokimet e përdoruesve të fshehur duhet të jenë të përhershme.',
'ipb_hide_invalid' => 'Nuk mund ta prishni këtë llogari; mund të ketë shumë redaktime.',
'ipb_already_blocked' => '"$1" është i bllokuar',
'ipb-needreblock' => "$1 është i bllokuar.
Dëshironi t'i ndryshoni parametrat?",
'ipb-otherblocks-header' => '{{PLURAL:$1|Bllokim tjetër|Bllokime të tjera}}',
'unblock-hideuser' => 'Ju nuk mund të zhbllokoni këtë përdorues, përderisa nofka e tij është fshehur.',
'ipb_cant_unblock' => 'Gabim: Bllokimi ID $1 nuk u gjet.
Mund të jetë zhbllokuar.',
'ipb_blocked_as_range' => 'Gabim: Adresa IP $1 nuk është bllokuar direkt dhe nuk mund të zhbllokohet.
Ajo është, megjithatë, e bllokuar si pjesë e rangut $2, që nuk mund të zhbllokohet.',
'ip_range_invalid' => 'Shtrirje IP gabim.',
'ip_range_toolarge' => 'Radhitja e bllokimeve më të mëdha se /$1 nuk lejohet.',
'blockme' => 'Më blloko',
'proxyblocker' => 'Bllokuesi i ndërmjetëseve',
'proxyblocker-disabled' => 'Ky funksion është pamundësuar.',
'proxyblockreason' => 'IP adresa juaj është bllokuar sepse është një ndërmjetëse e hapur. Ju lutem lidhuni me kompaninë e shërbimeve të Internetit që përdorni dhe i informoni për këtë problem sigurije.',
'proxyblocksuccess' => 'Mbaruar.',
'sorbsreason' => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL.',
'sorbs_create_account_reason' => 'Adresa IP e juaj është radhitur si ndërmjetëse e hapur tek lista DNSBL që përdoret nga {{SITENAME}}. Nuk ju lejohet të hapni një llogari.',
'cant-block-while-blocked' => 'Ju nuk mund të bllokoni përdorues të tjerë ndërkohë që jeni i bllokuar.',
'cant-see-hidden-user' => 'Përdoruesi që po përpiqeni të bllokoni është i bllokuar dhe i fshehur.
Përderisa ju nuk keni të drejtën e fshehjes së përdoruesve, ju nuk mund të shikoni ose redaktoni bllokimet e përdoruesit.',
'ipbblocked' => 'Ju nuk mund të bllokoni ose zhbllokoni përdoruesit e tjerë, sepse jeni për vete i bllokuar',
'ipbnounblockself' => 'Ju nuk mund të zhbllokoni veten tuaj',

# Developer tools
'lockdb' => 'Blloko regjistrin',
'unlockdb' => 'Çblloko regjistrin',
'lockdbtext' => 'Bllokimi i regjistrit do të ndërpresi mundësinë e përdoruesve për të redaktuar faqet, për të ndryshuar parapëlqimet, për të ndryshuar listat mbikqyrëse të tyre, dhe për gjëra të tjera për të cilat nevojiten shkrime në regjistër.
Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim, dhe se do të çbllokoni regjistrin
kur të mbaroni së kryeri mirëmbajtjen.',
'unlockdbtext' => 'Çbllokimi i regjistrit do të lejojë mundësinë e të gjithë përdoruesve për të redaktuar faqe, për të ndryshuar parapëlqimet e tyre, për të ndryshuar listat mbikqyrëse të tyre, dhe gjëra të tjera për të cilat nevojiten shkrime në regjistër. Ju lutem konfirmoni që dëshironi me të vërtetë të kryeni këtë veprim.',
'lockconfirm' => 'Po, dëshiroj me të vërtetë të bllokoj regjistrin.',
'unlockconfirm' => 'Po, dëshiroj me të vërtetë të çbllokoj regjistrin',
'lockbtn' => 'Blloko regjistrin',
'unlockbtn' => 'Çblloko regjistrin',
'locknoconfirm' => 'Nuk vendose kryqin tek kutia konfirmuese.',
'lockdbsuccesssub' => 'Regjistri u bllokua me sukses',
'unlockdbsuccesssub' => 'Regjistri u çbllokua me sukses',
'lockdbsuccesstext' => 'Regjistri është bllokuar.<br />
Kujtohuni ta [[Special:UnlockDB|çbllokoni]] pasi të keni mbaruar mirëmbajtjen.',
'unlockdbsuccesstext' => 'Regjistri i {{SITENAME}} është çbllokuar.',
'lockfilenotwritable' => "Skeda për bllokimin e regjistrit s'mund të shkruhet.
Shërbyesi i rrjetit duhet të jetë në gjendje të shkruaj këtë skedë për të bllokuar ose çbllokuar regjistrin.",
'databasenotlocked' => 'Regjistri nuk është bllokuar.',
'lockedbyandtime' => '(nga {{GENDER:$1|$1}} më $2 në $3)',

# Move page
'move-page' => 'Zhvendose $1',
'move-page-legend' => 'Zhvendose faqen',
'movepagetext' => "Duke përdorur formularin e mëposhtëm do të ndërroni titullin e një faqeje, duke zhvendosur gjithë historkun e saj tek titulli i ri.
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
'movepagetalktext' => "Faqja a bashkangjitur e diskutimit, n.q.s. ekziston, do të zhvendoset automatikisht '''përveçse''' kur:
*Zhvendosni një faqe midis hapësirave të ndryshme,
*Një faqe diskutimi jo-boshe ekziston nën titullin e ri, ose
*Nuk zgjidhni kutinë më poshtë.

Në ato raste, duhet ta zhvendosni ose përpuqni faqen vetë n.q.s. dëshironi.",
'movearticle' => 'Zhvendose faqen',
'moveuserpage-warning' => "'''Kujdes:''' Ju po zhvendosni një faqe përdoruesi. Ju lutemi, kujtoni se vetëm faqja do të zhvendoset dhe përdoruesi ''nuk'' do të ndryshojë emrin.",
'movenologin' => 'Nuk keni hyrë brenda',
'movenologintext' => 'Duhet të keni hapur një llogari dhe të keni [[Special:UserLogin|hyrë brenda]] për të zhvendosur një faqe.',
'movenotallowed' => 'Nuk ju lejohet të zhvendosni faqe.',
'movenotallowedfile' => 'Nuk keni leje për të lëvizur skeda.',
'cant-move-user-page' => 'Ju nuk keni të drejat për të lzhvendosur faqet e përdoruesve (përveç nën-faqeve).',
'cant-move-to-user-page' => 'Ju nuk keni të drejta për të zhvendosur një faqe tek një faqe përdoruesi (përvç tek një nën-faqe përdoruesi).',
'newtitle' => 'Tek titulli i ri',
'move-watch' => 'Mbikqyre këtë faqe',
'movepagebtn' => 'Zhvendose faqen',
'pagemovedsub' => 'Zhvendosja doli me sukses',
'movepage-moved' => '\'\'\'"$1" u zhvendos tek "$2"\'\'\'',
'movepage-moved-redirect' => 'Një përcjellim është krijuar.',
'movepage-moved-noredirect' => 'Krijimi i një përcjellimi është prishur.',
'articleexists' => 'Një faqe me atë titull ekziston, ose titulli që zgjodhët nuk është i saktë. Ju lutem zgjidhni një tjetër.',
'cantmove-titleprotected' => 'Nuk mund të zhvendosni një faqe në këtë titull pasi ky titull është mbrojtur kundrejt krijimit',
'talkexists' => 'Faqja për vete u zhvendos, ndërsa faqja e diskutimit nuk u zhvendos sepse një e tillë ekziston tek titulli i ri. Ju lutem, përpuqini vetë.',
'movedto' => 'zhvendosur tek',
'movetalk' => 'Zhvendos edhe faqen e diskutimeve, në qoftë se është e mundur.',
'move-subpages' => 'Zhvendosni nën-faqet (deri në $1)',
'move-talk-subpages' => 'Zhvendosni nën-faqet e faqes së diskutimit (deri në $1)',
'movepage-page-exists' => "Faqja $1 ekziston prandaj s'mund ta mbivendos automatikisht",
'movepage-page-moved' => 'Faqja $1 është zhvendosur tek $2.',
'movepage-page-unmoved' => "Faqja $1 s'mund të zhvendosej tek $2.",
'movepage-max-pages' => 'Maksimumi prej $1 {{PLURAL:$1|faqeje|faqesh}} është zhvendosur dhe nuk do të zhvendoset më automatikisht.',
'movelogpage' => 'Regjistri i zhvendosjeve',
'movelogpagetext' => 'Më poshtë është një listë e faqeve të zhvendosura',
'movesubpage' => '$1 nën-faqe',
'movesubpagetext' => 'Kjo faqe ka $1 nën-faqe treguar më poshtë.',
'movenosubpage' => 'Kjo faqe nuk ka nën-faqe.',
'movereason' => 'Arsyeja:',
'revertmove' => 'ktheje',
'delete_and_move' => 'Grise dhe zhvendose',
'delete_and_move_text' => '==Nevojitet grisje==

Faqja "[[:$1]]" ekziston, dëshironi ta grisni për të mundësuar zhvendosjen?',
'delete_and_move_confirm' => 'Po, grise faqen',
'delete_and_move_reason' => 'U gris për të liruar vendin për përcjellim të "[[$1]]"',
'selfmove' => 'Nuk munda ta zhvendos faqen sepse titulli i ri është i njëjtë me të vjetrin.',
'immobile-source-namespace' => 'Nuk mund të lëvizet faqja tek "$1"',
'immobile-target-namespace' => 'Nuk mund të lëvizen faqet tek "$1"',
'immobile-target-namespace-iw' => 'Lidhja ndër-wiki nuk është një objektiv i vlefshëm për zhvendosjen e faqes.',
'immobile-source-page' => 'Kjo faqe është e pa lëvizshme.',
'immobile-target-page' => 'Nuk mund të zhvendoset tek titulli i destinuar.',
'imagenocrossnamespace' => 'Nuk mund të lëvizet skeda tek hapësira e jo-skedës',
'nonfile-cannot-move-to-file' => 'Nuk mund të lëvizet jo-skeda tek hapësira e skedës',
'imagetypemismatch' => 'Skeda e re nuk përputhet me llojin e vet',
'imageinvalidfilename' => 'Emri i skedës së synuar është i pavlefshëm',
'fix-double-redirects' => 'Përditësoni çdo përcjellim që tregon titullin origjinal',
'move-leave-redirect' => 'Lini një përcjellim prapa',
'protectedpagemovewarning' => "'''Kujdes''': Kjo faqe është mbrojtur, kështu që vetëm përdoruesit me privilegje administratorësh mund ta zhvendosin atë.
Veprimi i fundit mbi këtë faqe është poshtë për referncë:",
'semiprotectedpagemovewarning' => "'''Kujdes''': Kjo faqe është mbrojtur, kështu që vetëm përdoruesit e regjistruar mund ta zhvendosin atë.
Veprimi i fundit mbi këtë faqe është poshtë për referncë:",
'move-over-sharedrepo' => '== Skeda ekziston ==
[[:$1]] ekziston në një magazinë të përbashkët. Zhvendosja e një skede tek ky titull do të prishë skedën e përbashkët.',
'file-exists-sharedrepo' => 'Emri i zgjedhur i skedës është në përdorim në një magazinë të përbashkët.
Ju lutemi zgjidhni në emët tjetër.',

# Export
'export' => 'Eksportoni faqe',
'exporttext' => 'Mund të eksportoni tekstin dhe historinë e redaktimit e një faqeje ose disa faqesh të mbështjesha në XML; kjo mund të importohet në një wiki tjetër që përdor softuerin MediaWiki (tani për tani, ky opsion nuk është përfshirë tek {{SITENAME}}).

Për të eksportuar faqe, thjesht shtypni një emër për çdo rresht, ose krijoni lidhje të tipit [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] si [[{{MediaWiki:Mainpage}}]].',
'exportall' => 'Eksportoni të gjitha faqet',
'exportcuronly' => 'Përfshi vetëm versionin e fundit, jo të gjithë historinë',
'exportnohistory' => "'''Shënim:''' Eksportimi i historisë së faqes për shkaqe të rendimentit nuk është e mundshme.",
'exportlistauthors' => 'Përfshij një listë të plotë të kontribuesve për secilën faqe',
'export-submit' => 'Eksporto',
'export-addcattext' => 'Shto faqe nga kategoria:',
'export-addcat' => 'Shto',
'export-addnstext' => 'Shtoni faqet nga hapësirat:',
'export-addns' => 'Shto',
'export-download' => 'Ruaje si skedë',
'export-templates' => 'Përfshinë stampa',
'export-pagelinks' => 'Përfshini faqet e lidhura në një thellësi prej:',

# Namespace 8 related
'allmessages' => 'Mesazhet e sistemit',
'allmessagesname' => 'Emri',
'allmessagesdefault' => 'Teksti i parazgjedhur',
'allmessagescurrent' => 'Teksti i tanishëshm',
'allmessagestext' => 'Kjo është një listë e të gjitha faqeve në hapësirën MediaWiki:
Ju lutemi vizitoni [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] dhe [//translatewiki.net translatewiki.net] nëse dëshironi të kontribuoni në lokalizimin e përgjithshëm MediaWiki',
'allmessagesnotsupportedDB' => "Kjo faqe nuk mund të përdoret sepse '''\$wgUseDatabaseMessages''' është çaktivizuar.",
'allmessages-filter-legend' => 'Filtër',
'allmessages-filter' => 'Filtroni nga shteti',
'allmessages-filter-unmodified' => 'E pandryshuar',
'allmessages-filter-all' => 'Të gjithë',
'allmessages-filter-modified' => 'E ndryshuar',
'allmessages-prefix' => 'Filtroni nga parashtesat:',
'allmessages-language' => 'Gjuha:',
'allmessages-filter-submit' => 'Shko',

# Thumbnails
'thumbnail-more' => 'Zmadho',
'filemissing' => 'Mungon skeda',
'thumbnail_error' => 'Gabim gjatë krijimit të figurës përmbledhëse: $1',
'djvu_page_error' => 'Faqja DjVu jashtë renditjes',
'djvu_no_xml' => 'Nuk mund të gjendet XML për skedën DjVu',
'thumbnail-temp-create' => 'Nuk mund të krijohej parapamja e përkohshme e skedës',
'thumbnail-dest-create' => 'Nuk mund të ruhej parapamja tek destinacioni',
'thumbnail_invalid_params' => 'Parametrat thumbnail të pavlefshme',
'thumbnail_dest_directory' => 'Në pamundësi për të krijuar dosjen e destinacionit',
'thumbnail_image-type' => 'Lloji i fotografisë nuk mbështetet',
'thumbnail_gd-library' => 'Konfigurim librarie GD i paplotë: mungon funksoni $1',
'thumbnail_image-missing' => 'Skeda duket se mungon: $1',

# Special:Import
'import' => 'Importoni faqe',
'importinterwiki' => 'Import ndër-wiki',
'import-interwiki-text' => 'Zgjidhni një wiki dhe titull faqeje për të importuar.
Datat e versioneve dhe emrat e redaktuesve do të ruhen.
Të gjitha veprimet e importit transwiki janë të regjistruara tek [[Special:Log/import|registri i importimeve]].',
'import-interwiki-source' => 'Burimi wiki/faqe',
'import-interwiki-history' => 'Kopjo të gjitha versionet e historisë për këtë faqe',
'import-interwiki-templates' => 'Përfshini të gjitha stampat',
'import-interwiki-submit' => 'Importo',
'import-interwiki-namespace' => 'Hapësira e destinuar:',
'import-upload-filename' => 'Emri i skedës:',
'import-comment' => 'Arsyeja:',
'importtext' => 'Ju lutem eksportoni këtë skedë nga burimi wiki duke përdorur  [[Special:Export|export utility]].! XAU Save atë në kompjuterin tuaj dhe ngarkoni këtu.',
'importstart' => 'Duke importuar faqet...',
'import-revision-count' => '$1 {{PLURAL:$1|version|versione}}',
'importnopages' => "S'ka faqe për tu importuar.",
'imported-log-entries' => 'Importuar $1 {{PLURAL:$1|hyrje|hyrje}}',
'importfailed' => 'Importimi dështoi: $1',
'importunknownsource' => 'Lloj burimi importi i panjohur',
'importcantopen' => 'Nuk mund të hapë skedën e importuar',
'importbadinterwiki' => 'Lidhje e prishur interwiki',
'importnotext' => 'Bosh ose pa tekst',
'importsuccess' => 'Importim i sukseshëm!',
'importhistoryconflict' => 'Ekzistojnë versione historiku në konflikt (kjo faqe mund të jetë importuar më parë)',
'importnosources' => 'Nuk ka asnjë burim importi të përcaktuar dhe ngarkimet historike të drejtpërdrejta janë ndaluar.',
'importnofile' => 'Nuk u ngarkua asnjë skedë importi.',
'importuploaderrorsize' => 'Ngarkimi ose importimi i skedës dështoi.
Skeda është më e madhe se madhësia e lejuar.',
'importuploaderrorpartial' => 'Ngarkimi ose importimi i skedës dështoi.
Skeda u ngarkua vetëm pjesërisht.',
'importuploaderrortemp' => 'Ngarkimi ose importimi i skedës dështoi.
Një dosje e përkohëshme mungon.',
'import-parse-failure' => 'Dështim i analizës së importit XML',
'import-noarticle' => "S'ka faqe për tu importuar!",
'import-nonewrevisions' => 'Të gjitha versionet kanë qenë të importuara më parë.',
'xml-error-string' => '$1 në vijën $2, kol $3 (bite $4): $5',
'import-upload' => 'Ngarko të dhëna XML',
'import-token-mismatch' => 'Humbje e të dhënave të sesionit.
Ju lutemi provoni përsëri.',
'import-invalid-interwiki' => 'Nuk mund të importohet nga wiki i specifikuar.',
'import-error-edit' => 'Faqja "$1" nuk është importuar sepse ju nuk lejoheni ta redaktoni atë.',
'import-error-create' => 'Faqja "$1" nuk është importuar sepse ju nuk lejoheni ta krijoni atë.',
'import-error-interwiki' => 'Faqja "$1" nuk është importuar sepse emri i saj është rezervuar për lidhje të jashtme (interwiki)',
'import-error-special' => 'Faqja "$1" nuk është importuar sepse ajo i përket një hapësire të veçantë që nuk i lejon faqet.',
'import-error-invalid' => 'Faqja "$1" nuk është importuar sepse emri i saj është i palejueshëm.',

# Import log
'importlogpage' => 'Regjistri i importeve',
'importlogpagetext' => 'Importimet administrative të faqeve me historik redaktimi nga wiki-t e tjera.',
'import-logentry-upload' => 'importoi [[$1]] nëpërmjet ngarkimit të skedave',
'import-logentry-upload-detail' => '$1 {{PLURAL:$1|version|versione}}',
'import-logentry-interwiki' => 'transwikoji $1',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$!1|version|versione}} nga $2',

# JavaScriptTest
'javascripttest' => 'Duke testuar JavaScript',
'javascripttest-disabled' => 'Ky funksion nuk është mundësuar në këtë wiki.',
'javascripttest-title' => 'Duke kryer testet $1',
'javascripttest-pagetext-noframework' => 'Kjo faqe është rezervuar për kryerjen e testimeve JavaScript.',
'javascripttest-pagetext-unknownframework' => 'Kornizë pune e panjohur testuese "$1".',
'javascripttest-pagetext-frameworks' => 'Ju lutemi zgjidhni njërën nga kornizat vijuese punuese të testimit: $1',
'javascripttest-pagetext-skins' => "Zgjidhni një mostër për t'i kryer testimet:",
'javascripttest-qunit-intro' => 'Shiko [$1 dokumentacionin e testimit] në mediawiki.org.',
'javascripttest-qunit-heading' => 'Platforma testuese JavaScript QUnit',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'Faqja juaj e përdoruesit',
'tooltip-pt-anonuserpage' => 'Faqja e përdoruesve anonim nga kjo adresë IP',
'tooltip-pt-mytalk' => 'Faqja juaj e diskutimeve',
'tooltip-pt-anontalk' => 'Faqja e diskutimeve të përdoruesve anonim për këtë adresë IP',
'tooltip-pt-preferences' => 'Parapëlqimet tuaja',
'tooltip-pt-watchlist' => 'Lista e faqeve nën mbikqyrjen tuaj.',
'tooltip-pt-mycontris' => 'Lista e kontributeve tuaja',
'tooltip-pt-login' => 'Identifikimi nuk është i detyrueshëm, megjithatë ne jua rekomandojmë.',
'tooltip-pt-anonlogin' => 'Të hysh brenda nuk është e detyrueshme, por ka shumë përparësi.',
'tooltip-pt-logout' => 'Dalje',
'tooltip-ca-talk' => 'Diskutim për faqen përmbajtje',
'tooltip-ca-edit' => 'Ju mund ta redaktoni këtë faqe. Përdorni butonin Trego parapamjen para se të ruani ndryshimet.',
'tooltip-ca-addsection' => 'Fillo një temë të re diskutimi.',
'tooltip-ca-viewsource' => 'Kjo faqe është e mbrojtur. Ju mundeni vetëm ta shikoni burimin e tekstit.',
'tooltip-ca-history' => 'Versione të mëparshme të artikullit.',
'tooltip-ca-protect' => 'Mbroje këtë faqe',
'tooltip-ca-unprotect' => 'Liroje mbrojtjen e kësaj faqeje',
'tooltip-ca-delete' => 'Grise këtë faqe',
'tooltip-ca-undelete' => 'Faqja u restaurua',
'tooltip-ca-move' => 'Me anë të zhvendosjes mund ta ndryshoni titullin e artikullit',
'tooltip-ca-watch' => 'Shtoje faqen në lisën e faqeve nën mbikqyrje',
'tooltip-ca-unwatch' => 'Hiqe faqen nga lista e faqeve nën mbikqyrje.',
'tooltip-search' => 'Kërko {{SITENAME}}',
'tooltip-search-go' => 'Shko tek faqja me këtë emër nëse ekziston',
'tooltip-search-fulltext' => 'Kërko faqet për këtë tekst',
'tooltip-p-logo' => 'Vizito faqen kryesore',
'tooltip-n-mainpage' => 'Vizitoni Faqen kryesore',
'tooltip-n-mainpage-description' => 'Vizito faqen kryesore',
'tooltip-n-portal' => 'Rreth projektit, çfarë mund të bëni dhe ku gjenden gjërat',
'tooltip-n-currentevents' => 'Kërko informacion të mëparshëm për ngjarjet aktuale.',
'tooltip-n-recentchanges' => 'Lista e ndryshimeve më të fundit në wiki',
'tooltip-n-randompage' => 'Shikoni një artikull të rastit',
'tooltip-n-help' => 'Vendi ku mund të gjeni ndihmë',
'tooltip-t-whatlinkshere' => 'Lista e të gjitha faqeve wiki që lidhen tek kjo faqe',
'tooltip-t-recentchangeslinked' => 'Lista e ndryshimeve të faqeve që lidhen tek kjo faqe',
'tooltip-feed-rss' => 'Burimi ushqyes "RSS" për këtë faqe',
'tooltip-feed-atom' => 'Burimi ushqyes "Atom" për këtë faqe',
'tooltip-t-contributions' => 'Shiko listën e kontributeve për përdoruesin në fjalë',
'tooltip-t-emailuser' => 'Dërgoni një email përdoruesit',
'tooltip-t-upload' => 'Ngarko skeda',
'tooltip-t-specialpages' => 'Lista e të gjitha faqeve speciale.',
'tooltip-t-print' => 'Version i shtypshëm i kësaj faqeje',
'tooltip-t-permalink' => 'Lidhja e përhershme tek ky version i faqes',
'tooltip-ca-nstab-main' => 'Shikoni përmbajtjen e atikullit.',
'tooltip-ca-nstab-user' => 'Shikoni faqen e përdoruesit',
'tooltip-ca-nstab-media' => 'Shikoni faqen e skedës',
'tooltip-ca-nstab-special' => 'Kjo është një faqe speciale. Ju nuk mundeni ta redaktoni këtë faqe',
'tooltip-ca-nstab-project' => 'Shikoni faqen e projektit',
'tooltip-ca-nstab-image' => 'Shikoni faqen e figurës',
'tooltip-ca-nstab-mediawiki' => 'Shikoni mesazhet e sistemit',
'tooltip-ca-nstab-template' => 'Shikoni stampën',
'tooltip-ca-nstab-help' => 'Shikoni faqet ndihmëse',
'tooltip-ca-nstab-category' => 'Shikoni faqen e kategorisë',
'tooltip-minoredit' => 'Shënoje këtë redaktim të vogël',
'tooltip-save' => 'Kryej ndryshimet',
'tooltip-preview' => 'Shqyrtoni ndryshimet tuaj, ju lutemi, bëjeni këtë para se të ruani ndryshimet!',
'tooltip-diff' => 'Trego ndryshimet që Ju i keni bërë tekstit.',
'tooltip-compareselectedversions' => 'Shikoni krahasimin midis dy versioneve të zgjedhura të kësaj faqeje.',
'tooltip-watch' => 'Mbikqyre këtë faqe',
'tooltip-watchlistedit-normal-submit' => 'Largo titujt',
'tooltip-watchlistedit-raw-submit' => 'Aktualizo listën mbikqyrëse',
'tooltip-recreate' => 'Rikrijoje faqen edhe nëse është grisur më parë',
'tooltip-upload' => 'Fillo ngarkimin',
'tooltip-rollback' => '"Rikthimi" rikthen ndryshimet tek kjo faqe nga redaktuesi i fundit vetëm me një klikim.',
'tooltip-undo' => '"Zhbëje" rikthen këtë ndryshim dhe hap modulin e redaktimit për shqyrtim. Lejon që të jepni një arsye tek përmbledhja.',
'tooltip-preferences-save' => 'Ruaj parapëlqimet',
'tooltip-summary' => 'Fusni një përmbledhje të shkurtër',

# Stylesheets
'monobook.css' => '/* redaktoni këtë faqe për të përshtatur pamjen Monobook për tëra faqet tuaja */',

# Metadata
'notacceptable' => 'Wiki server nuk mundet ti përgatit të dhënat për klintin tuaj.',

# Attribution
'anonymous' => '{{PLURAL:$1|Përdoruesi anonim|Përdoruesit anonimë}} të {{SITENAME}}',
'siteuser' => 'Përdoruesi $1 nga {{SITENAME}}',
'anonuser' => 'Përdorues anonim i {{SITENAME}} $1',
'lastmodifiedatby' => 'Kjo faqe është redaktuar së fundit më $2, $1 nga $3.',
'othercontribs' => 'Bazuar në punën e: $1',
'others' => 'të tjerë',
'siteusers' => '{{SITENAME}} {{PLURAL:$2|përdorues|përdorues}} $1',
'anonusers' => '{{SITENAME}} {{PLURAL:$2|përdoruesi anonim|përdoruesit anonimë}} $1',
'creditspage' => 'Statistika e faqes',
'nocredits' => 'Për këtë faqe nuk ka informacione.',

# Spam protection
'spamprotectiontitle' => 'Mbrojtje ndaj teksteve të padëshiruara',
'spamprotectiontext' => 'Faqja që dëshironit të ruani është bllokuar nga filtri i teksteve të padëshiruara. Ka mundësi që kjo të ketë ndodhur për shkak të ndonjë lidhjeje të jashtme.',
'spamprotectionmatch' => 'Teksti në vijim është cilësuar i padëshiruar nga softueri: $1',
'spambot_username' => 'MediaWiki spam-pastrues',
'spam_reverting' => "U kthye tek versioni i fundit që s'ka lidhje tek $1",
'spam_blanking' => 'U boshatis sepse të gjitha versionet kanë lidhje tek $1',
'spam_deleting' => 'Të gjitha inspektimet përmbanin lidhje në $1, duke fshirë',

# Info page
'pageinfo-title' => 'Informacion për " $1 "',
'pageinfo-header-edits' => 'Redaktimet',
'pageinfo-views' => 'Numri i shikimeve',
'pageinfo-watchers' => 'Numri i mbikqyrësve',
'pageinfo-edits' => 'Numri i redaktimeve',
'pageinfo-authors' => 'Numri i autorëve të veçantë',

# Skin names
'skinname-standard' => 'Standarte',
'skinname-nostalgia' => 'Nostalgjike',
'skinname-cologneblue' => 'Kolonjë Blu',

# Patrolling
'markaspatrolleddiff' => 'Shënoje si të patrulluar',
'markaspatrolledtext' => 'Shënoje këtë artikull të patrulluar',
'markedaspatrolled' => 'Shënoje të patrulluar',
'markedaspatrolledtext' => 'Versioni i zgjedhur i [[:$1]] është shënuar si i patrolluar.',
'rcpatroldisabled' => 'Kontrollimi i ndryshimeve së fundmi është bllokuar',
'rcpatroldisabledtext' => 'Kontrollimi i ndryshimeve së fundmi nuk është i mundshëm për momentin.',
'markedaspatrollederror' => 'Nuk munda ta shënoj të patrulluar',
'markedaspatrollederrortext' => 'Duhet të përcaktoni versionin për tu shënuar i patrulluar.',
'markedaspatrollederror-noautopatrol' => 'Ju nuk lejoheni të shënoni ndryshimet tuaj si të patrolluara.',

# Patrol log
'patrol-log-page' => 'Regjistri i patrollimeve',
'patrol-log-header' => 'Këto janë të dhëna të revizioneve të patrulluara.',
'log-show-hide-patrol' => '$1 regjistri i patrollimeve',

# Image deletion
'deletedrevision' => 'Gris versionin e vjetër $1',
'filedeleteerror-short' => 'Gabim gjatë grisjes së skedës: $1',
'filedeleteerror-long' => 'U hasën gabime gjatë grisjes së skedës:

$1',
'filedelete-missing' => 'Skeda "$1" nuk mund të griset pasi nuk ekziston.',
'filedelete-old-unregistered' => 'Versioni i skedës që keni zgjedhur "$1" nuk ndodhet në regjistër.',
'filedelete-current-unregistered' => 'Skeda e zgjedhur "$1" nuk ndodhet në regjistër.',
'filedelete-archive-read-only' => 'Skedari i arkivimit "$1" nuk mund të ndryshohet nga shëbyesi.',

# Browsing diffs
'previousdiff' => '← Ndryshimi më para',
'nextdiff' => 'Ndryshimi më pas →',

# Media information
'mediawarning' => "''Kujdes''': Kjo skedë mund të ketë përmbajtje të dëmshme. 
Duke e përdorur sistemi juaj mund të rrezikohet.",
'imagemaxsize' => "Kufizoni madhësinë e fotos:<br />''(për faqet e përshkrimit të skedave)''",
'thumbsize' => 'Madhësia fotove përmbledhëse:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|faqe|faqe}}',
'file-info' => 'madhësia skedës: $1, lloji MIME: $2',
'file-info-size' => '$1 × $2 pixela, madhësia e skedës: $3, tipi MIME: $4',
'file-info-size-pages' => '$1 × $2 pixel, madhësia e dokumentit: $3 , MIME tipi: $4 , $5 {{PLURAL:$5| faqe | faqet}}',
'file-nohires' => 'Nuk ka rezolucion më të madh.',
'svg-long-desc' => 'skedë SVG, fillimisht $1 × $2 pixel, madhësia e skedës: $3',
'show-big-image' => 'Rezolucion i plotë',
'show-big-image-preview' => 'Madhësia e këtij shikimi: $1.',
'show-big-image-other' => '{{PLURAL:$2|Rezolucion tjetër|Rezolucione të tjera}}: $1.',
'show-big-image-size' => '$1 × $2 pixel',
'file-info-gif-looped' => 'kthyer',
'file-info-gif-frames' => '$1 {{PLURAL:$1|kornizë|korniza}}',
'file-info-png-looped' => 'kthyer',
'file-info-png-repeat' => 'luajtur $1 herë',
'file-info-png-frames' => '$1 {{PLURAL:$1|kornizë|korniza}}',

# Special:NewFiles
'newimages' => 'Galeria e figurave të reja',
'imagelisttext' => 'Më poshtë është një listë e $1 {{PLURAL:$1|skedës të renditur|skedave të renditura}} sipas $2.',
'newimages-summary' => 'Kjo faqe speciale tregon skedat e ngarkuara së fundmi.',
'newimages-legend' => 'Filtrues',
'newimages-label' => 'Emri i skedës (ose një pjesë e tij):',
'showhidebots' => '($1 robotët)',
'noimages' => "S'ka gjë për të parë.",
'ilsubmit' => 'Kërko',
'bydate' => 'datës',
'sp-newimages-showfrom' => 'Trego skedat e reja duke filluar nga $2, $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|$1 sekondë|$1 sekonda}}',
'minutes' => '{{PLURAL:$1|$1 minutë|$1 minuta}}',
'hours' => '{{PLURAL:$1|$1 orë|$1 orë}}',
'days' => '{{PLURAL:$1|$1 ditë|$1 ditë}}',
'ago' => '$1 më parë',

# Bad image list
'bad_image_list' => 'Formati është si vijon:

Vetëm elementët listë ( rreshtat duhet të fillojnë me * ) merren parasysh.
Lidhja e parë në një rresht duhet të lidhet me një skedë të prishur.
Çdo lidhje pasuese në rreshtin e njëjtë konsiderohet si përjashtim, p.sh. faqe në të cilat skeda mund të shfaqet në të njëjtin rresht.',

# Metadata
'metadata' => 'Metadata',
'metadata-help' => 'Kjo skedë përmban hollësira të tjera të cilat mund të jenë shtuar nga kamera ose skaneri dixhital që është përdorur për ta krijuar.
Në qoftë se skeda është ndryshuar nga gjendja origjinale, disa hollësira mund të mos pasqyrojnë versionin e tanishëm.',
'metadata-expand' => 'Trego detajet',
'metadata-collapse' => 'Fshih detajet',
'metadata-fields' => 'Imetadata fusha Image të listuara në këtë mesazh do të përfshihen në faqen shfaqur imazhin kur tryezë metadata është shembur
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth' => 'Gjerësia',
'exif-imagelength' => 'Gjatësia',
'exif-bitspersample' => 'Bit për komponent',
'exif-compression' => 'Lloji i ngjeshjes',
'exif-photometricinterpretation' => 'Përbërja pixel',
'exif-orientation' => 'Orientimi',
'exif-samplesperpixel' => 'Numri i përbërësve',
'exif-planarconfiguration' => 'Përpunimi i të dhënave',
'exif-ycbcrsubsampling' => 'Duke krahasuar raportin e Y tek C',
'exif-ycbcrpositioning' => 'Pozicioni Y dhe C',
'exif-xresolution' => 'Rezolucioni horizontal',
'exif-yresolution' => 'Rezolucioni vertikal',
'exif-stripoffsets' => 'Vendi i figurave',
'exif-rowsperstrip' => 'Numri i rreshtave për shirit',
'exif-stripbytecounts' => 'Bajt për shirit të ngjeshur',
'exif-jpeginterchangeformat' => 'Çvendos tek JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Bajtët të dhënave JPEG',
'exif-whitepoint' => 'Pikët e bardha kromatike',
'exif-primarychromaticities' => 'Kromatikët e primareve',
'exif-ycbcrcoefficients' => 'Koeficentët e transformimit të hapësirave të ngjyrave të matricës',
'exif-referenceblackwhite' => 'Çift vlerash me refernca bardhë dhe zi',
'exif-datetime' => 'Data dhe ora e ndryshimit të skedës',
'exif-imagedescription' => 'Titulli i figurës',
'exif-make' => 'Prodhuesi i kamerës',
'exif-model' => 'Modeli i kamerës',
'exif-software' => 'Softueri i përdorur',
'exif-artist' => 'Autor',
'exif-copyright' => 'Mbajtësi i të drejtave të autorit',
'exif-exifversion' => 'Versioni Exif-it',
'exif-flashpixversion' => 'Versioni Flahpix i mbështetur',
'exif-colorspace' => 'Hapësira e ngjyrave',
'exif-componentsconfiguration' => 'Kuptimi i secilit komponent',
'exif-compressedbitsperpixel' => 'Lloji i ngjeshjes së figurës',
'exif-pixelydimension' => 'Gjerësia Image',
'exif-pixelxdimension' => 'lartësi Image',
'exif-usercomment' => 'Vërejtjet e përdoruesit',
'exif-relatedsoundfile' => 'Skeda audio shoqëruese',
'exif-datetimeoriginal' => 'Data dhe koha e prodhimit të të dhënave',
'exif-datetimedigitized' => 'Data dhe ora e digjitalizimit',
'exif-subsectime' => 'Nën-sekondat DataKoha',
'exif-subsectimeoriginal' => 'Nën-sekondat DataKohaOrigjinale',
'exif-subsectimedigitized' => 'Nën-sekondat DataKohaOrigjinale',
'exif-exposuretime' => 'Kohëzgjatja e ekspozimit',
'exif-exposuretime-format' => '$1 sek ($2)',
'exif-fnumber' => 'Numri F',
'exif-exposureprogram' => 'Zbuloni programin',
'exif-spectralsensitivity' => 'Ndjeshmëria spektrale',
'exif-isospeedratings' => 'Vlerësimi i shpejtësisë ISO',
'exif-shutterspeedvalue' => 'shpejtësi APEX qepen',
'exif-aperturevalue' => 'aperture APEX',
'exif-brightnessvalue' => 'shkëlqim APEX',
'exif-exposurebiasvalue' => 'zbuloni vijat e pjerrëta',
'exif-maxaperturevalue' => 'Hapje maksimale e tokës',
'exif-subjectdistance' => 'Largësia e subjektit',
'exif-meteringmode' => 'Mënyra e matjes',
'exif-lightsource' => 'Burimi i dritës',
'exif-flash' => 'Blici',
'exif-focallength' => 'Gjatësia e vatrës',
'exif-subjectarea' => 'Hapësira e subjektit',
'exif-flashenergy' => 'Energjia e blicit',
'exif-focalplanexresolution' => 'Rezelucioni i planit fokal X',
'exif-focalplaneyresolution' => 'Rezelucioni i planit fokal Y',
'exif-focalplaneresolutionunit' => 'Rezolucioni i njësisë së planit fokal',
'exif-subjectlocation' => 'Vendndodhja e subjektit',
'exif-exposureindex' => 'Indeksi i ekspozimit',
'exif-sensingmethod' => 'Metoda Sensing',
'exif-filesource' => 'Burimi i skedës',
'exif-scenetype' => 'Lloji Scene',
'exif-customrendered' => 'Përpunim i fotografisë Costum',
'exif-exposuremode' => 'Mënyra e ekspozimit',
'exif-whitebalance' => 'Balanca e bardhë',
'exif-digitalzoomratio' => 'Zmadhim dixhital',
'exif-focallengthin35mmfilm' => 'Gjatësia fokale në 35 mm film',
'exif-scenecapturetype' => 'Shtrirja e largësisë',
'exif-gaincontrol' => 'Kontrolli i skenës',
'exif-contrast' => 'Kontrasti',
'exif-saturation' => 'Mbushja',
'exif-sharpness' => 'Ashpërsia',
'exif-devicesettingdescription' => 'Përshkrimi i parametrave të pajisjes',
'exif-subjectdistancerange' => 'Shtrirja e largësisë së subjektit',
'exif-imageuniqueid' => 'ID unike e fotografisë',
'exif-gpsversionid' => 'Versioni i etiketës GPS',
'exif-gpslatituderef' => 'Gjerësi veriore ose jugore',
'exif-gpslatitude' => 'Gjerësia gjeografike',
'exif-gpslongituderef' => 'Gjatësi lindore ose perëndimore',
'exif-gpslongitude' => 'Gjatësia gjeografike',
'exif-gpsaltituderef' => 'Lartësia orientuese',
'exif-gpsaltitude' => 'Lartësia',
'exif-gpstimestamp' => 'Koha GPS (ora atomike)',
'exif-gpssatellites' => 'Janë përdorur satelitë për matjen',
'exif-gpsstatus' => 'Statusi i marrësit',
'exif-gpsmeasuremode' => 'Mënyra e matjes',
'exif-gpsdop' => 'Saktësia e matjes',
'exif-gpsspeedref' => 'Njësia e shpejtësisë',
'exif-gpsspeed' => 'Shpejtësia e marrësit GPS',
'exif-gpstrackref' => 'Referenca për drejtimin e lëvizjes',
'exif-gpstrack' => 'Drejtimi i lëvizjes',
'exif-gpsimgdirectionref' => 'Referenca për drejtimin e imazhit',
'exif-gpsimgdirection' => 'Orientimi i figurës',
'exif-gpsmapdatum' => 'Anketa e të dhënave gjeodezike të përdorura',
'exif-gpsdestlatituderef' => 'Referenca për gjerësinë e destinacionit',
'exif-gpsdestlatitude' => 'Destinacioni i gjerësisë',
'exif-gpsdestlongituderef' => 'Referenca për gjatësinë e destinacionit',
'exif-gpsdestlongitude' => 'Gjatësia e destinacionit',
'exif-gpsdestbearingref' => 'Referenca për qëndrimin e destinacionit',
'exif-gpsdestbearing' => 'Qëndrimi i destinacionit',
'exif-gpsdestdistanceref' => 'Referenca për distancën e destinacionit',
'exif-gpsdestdistance' => 'Distanca tek destinacioni',
'exif-gpsprocessingmethod' => 'Emri i metodës së përpunimit GPS',
'exif-gpsareainformation' => 'Emri i zonës GPS',
'exif-gpsdatestamp' => 'E dhënë GPS',
'exif-gpsdifferential' => 'Korrigjim diferencial i GPS',
'exif-jpegfilecomment' => 'Komenti i JPEG dokumentit',
'exif-keywords' => 'Fjalët kyçe',
'exif-worldregioncreated' => 'Rajoni botërorë ku është marrë fotografia',
'exif-countrycreated' => 'Shteti ku është marrë fotografia',
'exif-countrycodecreated' => 'Kodi për shtetin ku është marrë fotografia',
'exif-provinceorstatecreated' => 'Provinca apo shteti ku është marrë fotografia',
'exif-citycreated' => 'Qyteti ku është marrë fotografia',
'exif-sublocationcreated' => 'Nën-Lokacioni i qytetit ku është marrë fotografia',
'exif-worldregiondest' => 'Rajoni botërorë i treguar',
'exif-countrydest' => 'Shteti i treguar',
'exif-countrycodedest' => 'Kodi për vendin e treguar',
'exif-provinceorstatedest' => 'Provinca ose të shteti i treguar',
'exif-citydest' => 'Qyteti i treguar',
'exif-sublocationdest' => 'Nën-Lokacioni i qytetit të treguar',
'exif-objectname' => 'Titull i shkurtër',
'exif-specialinstructions' => 'Udhëzime të veçanta',
'exif-headline' => 'Mbishkrimi',
'exif-credit' => 'Atribues / Furnizues',
'exif-source' => 'Burimi',
'exif-editstatus' => 'Statusi editorial i fotografisë',
'exif-urgency' => 'Urgjencë',
'exif-fixtureidentifier' => 'Emri i shtojcës',
'exif-locationdest' => 'Vendndodhja e përshkruar',
'exif-locationdestcode' => 'Kodi i lokacionit të përshkruar',
'exif-objectcycle' => 'Koha e ditës që media është menduar për',
'exif-contact' => 'Informatat e kontaktit',
'exif-writer' => 'Shkrimtari',
'exif-languagecode' => 'Gjuha',
'exif-iimversion' => 'IIM versioni',
'exif-iimcategory' => 'Kategoria',
'exif-iimsupplementalcategory' => 'Kategoritë plotësuese',
'exif-datetimeexpires' => 'Mos përdorni më pas',
'exif-datetimereleased' => 'Lëshuar më',
'exif-originaltransmissionref' => 'Kodi origjinal i vendit të transmetimit',
'exif-identifier' => 'Identifikuesi',
'exif-lens' => 'Lentja e përdorur',
'exif-serialnumber' => 'Numri serik i kamerës',
'exif-cameraownername' => 'Pronari i kamerës',
'exif-label' => 'Etiketa',
'exif-datetimemetadata' => 'Data e ndryshimit të fundit të të dhënave',
'exif-nickname' => 'Emri joformal i fotografisë',
'exif-rating' => 'Vlerësimi (nga 5)',
'exif-rightscertificate' => 'Certifikatë e të drejtave të menaxhmentit',
'exif-copyrighted' => 'Statusi i të drejtës së autorit',
'exif-copyrightowner' => 'Pronari i të drejtës së autorit',
'exif-usageterms' => 'Mënyra e përdorimit',
'exif-webstatement' => 'Deklarata e të drejtës së autorit në-linjë',
'exif-originaldocumentid' => 'ID-ja unike e dokumentit origjinal',
'exif-licenseurl' => 'URL-ja për licencën e të drejtës së autorit',
'exif-morepermissionsurl' => 'Informacion alternativ i licencimit',
'exif-attributionurl' => 'Kur ri-shfrytëzoni këtë punë, ju lutem lidheni tek',
'exif-preferredattributionname' => 'Kur ri-shfrytëzoni këtë punë, ju lutem atribuoni',
'exif-pngfilecomment' => 'Komenti i PGN dokumentit',
'exif-disclaimer' => 'Shfajësimet',
'exif-contentwarning' => 'Paralajmërim i përmbajtjes',
'exif-giffilecomment' => 'Komenti i GIF dokumentit',
'exif-intellectualgenre' => 'Lloji i artikullit',
'exif-subjectnewscode' => 'Kodi i subjektit',
'exif-scenecode' => 'Kodi i IPTC skenës',
'exif-event' => 'Ngjarja e përshkruar',
'exif-organisationinimage' => 'Organizata e përshkruar',
'exif-personinimage' => 'Personi i përshkruar',
'exif-originalimageheight' => 'Lartësia e fotografisë para se të shkurtohej',
'exif-originalimagewidth' => 'Gjerësia e fotografisë para se të shkurtohej',

# EXIF attributes
'exif-compression-1' => 'E pangjeshur',
'exif-compression-2' => 'CCITT Grupi 3 1-Dimensional Kodimi i Modifikuar Huffman i linjës së gjatësisë',
'exif-compression-3' => 'CCITT Grupi 3 faks kodimi',
'exif-compression-4' => 'CCITT Grupi 4 faks kodimi',

'exif-copyrighted-true' => 'E drejtë e autorit',
'exif-copyrighted-false' => 'Sferë publike',

'exif-unknowndate' => 'E dhënë e pa njohur',

'exif-orientation-1' => 'Normale',
'exif-orientation-2' => 'E kthyer horizontalisht',
'exif-orientation-3' => 'E rrotulluar 180°',
'exif-orientation-4' => 'E kthyer vertikalisht',
'exif-orientation-5' => 'E rrotulluar 90° kundër orës dhe e kthyer vertikalisht',
'exif-orientation-6' => 'Rrotulluar 90° kundër akrepave të orës',
'exif-orientation-7' => 'E rrotulluar 90° sipas orës dhe e kthyer vertikalisht',
'exif-orientation-8' => 'Rrotulluar 90° sipas akrepave të orës',

'exif-planarconfiguration-1' => 'formati copë',
'exif-planarconfiguration-2' => 'formati planar',

'exif-colorspace-65535' => 'e pa kalibruar',

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

'exif-meteringmode-0' => 'E panjohur',
'exif-meteringmode-1' => 'Mesatare',
'exif-meteringmode-2' => 'QendraPeshësMesatare',
'exif-meteringmode-3' => 'Spot',
'exif-meteringmode-4' => 'MultiSpot',
'exif-meteringmode-5' => 'Model',
'exif-meteringmode-6' => 'E pjesshme',
'exif-meteringmode-255' => 'Tjetër',

'exif-lightsource-0' => 'I panjohur',
'exif-lightsource-1' => 'Ditë',
'exif-lightsource-2' => 'Fluoreshent',
'exif-lightsource-3' => 'Tungsten (dritë e flaktë)',
'exif-lightsource-4' => 'Blic',
'exif-lightsource-9' => 'Kohë e mirë',
'exif-lightsource-10' => 'Kohë e vrenjtur',
'exif-lightsource-11' => 'Hije',
'exif-lightsource-12' => 'Fluoreshent dite (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluoreshent i badhë dite (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluoreshent i badhë i fresket (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fluoreshent i bardhe (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Dritë standarde A',
'exif-lightsource-18' => 'Dritë standarde B',
'exif-lightsource-19' => 'Dritë standarde C',
'exif-lightsource-24' => 'Studio ISO tungsten',
'exif-lightsource-255' => 'Tjetër burim drite',

# Flash modes
'exif-flash-fired-0' => 'Flashi nuk u ndez',
'exif-flash-fired-1' => 'Flashi u ndez',
'exif-flash-return-0' => "s'ka funksion zbulimi prapa",
'exif-flash-return-2' => 'kthimi i dritës nuk u vërejt',
'exif-flash-return-3' => 'kthimi i dritës flash u vërejt',
'exif-flash-mode-1' => 'flashi po ndizet',
'exif-flash-mode-2' => 'shuarje e detyrueshme e flashit',
'exif-flash-mode-3' => 'auto mode',
'exif-flash-function-1' => "S'ka funksion të çastit",
'exif-flash-redeye-1' => 'menyra e reduktimit red-eye',

'exif-focalplaneresolutionunit-2' => 'inç',

'exif-sensingmethod-1' => 'e padefinuar',
'exif-sensingmethod-2' => 'Zona e sensorit one-chip kolor',
'exif-sensingmethod-3' => 'Zona e sensorit two-chip kolor',
'exif-sensingmethod-4' => 'Zona e sensorit three-chip kolor',
'exif-sensingmethod-5' => 'Sensori i zones kolor sequential',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensori linear kolor sequential',

'exif-filesource-3' => 'Digital ende kamera',

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

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metër|metra}} mbi nivelin detar',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metër|metra}} nën nivelin detar',

'exif-gpsstatus-a' => 'Duke bërë matje',
'exif-gpsstatus-v' => 'Matja e nderveprimit',

'exif-gpsmeasuremode-2' => 'matje në 2 madhësi',
'exif-gpsmeasuremode-3' => 'matje në 3 madhësi',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometra në orë',
'exif-gpsspeed-m' => 'Milje në orë',
'exif-gpsspeed-n' => 'Nyje',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometra',
'exif-gpsdestdistance-m' => 'Milje',
'exif-gpsdestdistance-n' => 'Milje detare',

'exif-gpsdop-excellent' => 'Shkëlqyeshëm ($1)',
'exif-gpsdop-good' => 'Mirë ( $1 )',
'exif-gpsdop-moderate' => 'Mesatar ( $1 )',
'exif-gpsdop-fair' => 'Mjaftueshëm ( $1 )',
'exif-gpsdop-poor' => 'Dobët ( $1 )',

'exif-objectcycle-a' => 'Vetëm në Mëngjes',
'exif-objectcycle-p' => 'Vetëm në Mbrëmje',
'exif-objectcycle-b' => 'Të dy në mëngjes dhe në mbrëmje',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Drejtimi i vërtetë',
'exif-gpsdirection-m' => 'Drejtimi magnetik',

'exif-ycbcrpositioning-1' => 'Qendër',
'exif-ycbcrpositioning-2' => 'Bashkë-Faqet',

'exif-dc-contributor' => 'Kontribuesit',
'exif-dc-coverage' => 'Shtrirje hapsinore apo e përkohshme e mediave',
'exif-dc-date' => 'Datë (at)',
'exif-dc-publisher' => 'Botuesi',
'exif-dc-relation' => 'Mediat e lidhura',
'exif-dc-rights' => 'Privilegjet',
'exif-dc-source' => 'Burimi i medias',
'exif-dc-type' => 'Lloji i mediave',

'exif-rating-rejected' => 'Refuzuar',

'exif-isospeedratings-overflow' => 'Më e madhe se 65.535',

'exif-iimcategory-ace' => 'Art, kulturë dhe argëtim',
'exif-iimcategory-clj' => 'Krimi dhe Ligji',
'exif-iimcategory-dis' => 'Fatkeqësit dhe aksidentet',
'exif-iimcategory-fin' => 'Ekonomi dhe biznes',
'exif-iimcategory-edu' => 'Arsim',
'exif-iimcategory-evn' => 'Mjedis',
'exif-iimcategory-hth' => 'Shëndetësi',
'exif-iimcategory-hum' => 'Interes njerëzor',
'exif-iimcategory-lab' => 'Punë',
'exif-iimcategory-lif' => 'Stil-Jete dhe kohë e lirë',
'exif-iimcategory-pol' => 'Politikë',
'exif-iimcategory-rel' => 'Religjioni dhe besimi',
'exif-iimcategory-sci' => 'Shkencë dhe teknologji',
'exif-iimcategory-soi' => 'Çështje sociale',
'exif-iimcategory-spo' => 'Sporti',
'exif-iimcategory-war' => 'Lufta, konflikte dhe trazira',
'exif-iimcategory-wea' => 'Moti',

'exif-urgency-normal' => 'Normale ( $1 )',
'exif-urgency-low' => 'Ulët ( $1 )',
'exif-urgency-high' => 'E Lartë ( $1 )',
'exif-urgency-other' => 'Prioritet i përcaktuar nga përdoruesi ( $1 )',

# External editor support
'edit-externally' => 'Ndryshoni këtë skedë me një mjet të jashtëm',
'edit-externally-help' => '(Shikoni [//www.mediawiki.org/wiki/Manual:External_editors udhëzimet e instalimit] për më shumë informacion)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'të gjitha',
'namespacesall' => 'të gjitha',
'monthsall' => 'të gjitha',
'limitall' => 'Të gjitha',

# E-mail address confirmation
'confirmemail' => 'Vërtetoni adresën tuaj',
'confirmemail_noemail' => 'Ju nuk keni dhënë email të sakt te [[Special:Preferences|parapëlqimet e juaja]].',
'confirmemail_text' => 'Për të marrë email duhet të vërtetoni adresen tuaj. Shtypni butonin e mëposhtëm për të dërguar një email vërtetimi tek adresa juaj. Email-i do të përmbajë një lidhje me kod të shifruar. Duke ndjekur lidhjen nëpërmjet shfletuesit tuaj do të vërtetoni adresën.',
'confirmemail_pending' => "Një kod vërtetimi ju është dërguar më parë. Nëse sapo hapët llogarinë tuaj prisni disa minuta deri sa t'iu arrijë mesazhi përpara se të kërkoni një kod të ri.",
'confirmemail_send' => 'Dërgo vërtetimin',
'confirmemail_sent' => 'Email-i për vërtetim është dërguar.',
'confirmemail_oncreate' => 'Një kod vërtetimi është dërguar tek adresa juaj e email-it.
Ky kod nuk kërkohet për të hyrë brenda në llogarinë tuaj, por nevojitet për të mundësuar mjetet që përdorin email në këtë wiki.',
'confirmemail_sendfailed' => '{{SITENAME}} nuk mundi ta çojë email-in tuaj konfirmues.
Ju lutemi kontrolloni adresen e emial-it tuaj per gabime ne shkrim.

Postieri u kthye: $1',
'confirmemail_invalid' => 'Kodi i shifrimit të vërtetimit është gabim ose ka skaduar.',
'confirmemail_needlogin' => 'Ju duhet të $1 për ta konfirmuar email-adresën',
'confirmemail_success' => 'Adresa juaj është vërtetuar. Mund të hyni brënda dhe të përdorni wiki-n.',
'confirmemail_loggedin' => 'Adresa juaj është vërtetuar.',
'confirmemail_error' => 'Pati gabim gjatë ruajtjes së vërtetimit tuaj.',
'confirmemail_subject' => 'Vërtetim adrese nga {{SITENAME}}',
'confirmemail_body' => 'Dikush, me gjasë ju, nga IP adresa $1,
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
'confirmemail_body_set' => 'Dikush, me gjasë ju, nga IP adresa $1,
ka ndryshuar e-mail adresën e llogarisë "$2" me këtë adresë në {{SITENAME}}.

Për të konfirmuar se kjo llogari ju përket me të vërtetë dhe për të riaktivizuar
funksionet e \'\'e-mail\'\'-it në {{SITENAME}}, hapni këtë lidhje në shfletuesin tuaj:

$3

Nëse llogaria *nuk* ju përket, ndiqni këtë lidhje
për të anuluar konfirmimin e e-mail adresës:

$5

Ky kod i konfirmimit skadon me $4.',
'confirmemail_invalidated' => 'Vërtetimi i adresës së email-it është tërhequr',
'invalidateemail' => 'Tërhiq vërtetimin e email-it',

# Scary transclusion
'scarytranscludedisabled' => '[Lidhja Interwiki nuk është i mundshëm]',
'scarytranscludefailed' => '[Gjetja e stampes deshtoi per $1]',
'scarytranscludetoolong' => '[Adresa URL eshte teper e gjate]',

# Delete conflict
'deletedwhileediting' => 'Kujdes! Kjo faqe është grisur pasi keni filluar redaktimin!',
'confirmrecreate' => "Përdoruesi [[User:$1|$1]] ([[User talk:$1|diskutime]]) grisi këtë artikull mbasi ju filluat ta redaktoni për arsyen:
: ''$2''
Ju lutem konfirmoni nëse dëshironi me të vertetë ta rikrijoni këtë artikull.",
'confirmrecreate-noreason' => 'Përdoruesi [[User:$1|$1]] ([[User talk:$1|talk]]) ka fshirë këtë faqe pasi ju filluat ta redaktoni. Ju lutem konfirmoni që ju vërtet doni të ri-krijoni këtë faqe.',
'recreate' => 'Rikrijo',

# action=purge
'confirm_purge_button' => 'Shko',
'confirm-purge-top' => "Pastro ''cache''-in për këtë faqe?",
'confirm-purge-bottom' => "Spastrimi i një faqeje pastron ''cache''-in dhe detyron shfaqjen e verzionit më të fundit të faqes.",

# action=watch/unwatch
'confirm-watch-button' => 'Në rregull',
'confirm-watch-top' => 'Shto këtë faqe në listën mbikqyrëse tuajen?',
'confirm-unwatch-button' => 'Në rregull',
'confirm-unwatch-top' => 'Largo këtë faqe nga lista juaj mbikqyrëse ?',

# Multipage image navigation
'imgmultipageprev' => '← faqja e kaluar',
'imgmultipagenext' => 'faqja tjetër →',
'imgmultigo' => 'Shko!',
'imgmultigoto' => 'Shko tek faqja $1',

# Table pager
'ascending_abbrev' => 'ngritje',
'descending_abbrev' => 'zbritje',
'table_pager_next' => 'Faqja më pas',
'table_pager_prev' => 'Faqja më parë',
'table_pager_first' => 'Faqja e parë',
'table_pager_last' => 'Faqja e fundit',
'table_pager_limit' => 'Trego $1 rreshta për faqe',
'table_pager_limit_label' => 'Artikuj per faqe:',
'table_pager_limit_submit' => 'Shko',
'table_pager_empty' => "S'ka rezultate",

# Auto-summaries
'autosumm-blank' => 'U boshatis faqja',
'autosumm-replace' => "Faqja u zëvendësua me '$1'",
'autoredircomment' => 'Përcjellim te [[$1]]',
'autosumm-new' => 'Krijoi faqen me "$1"',

# Live preview
'livepreview-loading' => 'Duke punuar…',
'livepreview-ready' => 'Duke punuar… Gati!',
'livepreview-failed' => 'Parapamja e menjëhershme dështoi! Provoni parapamjen e zakonshme.',
'livepreview-error' => 'Nuk mund të kryhej lidhja: $1 "$2". Provoni parapamjen e zakonshme.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Ndryshimet më të reja se $1 {{PLURAL:$1|sekond|sekonda}} mund të mos tregohen në këtë listë.',
'lag-warn-high' => 'Për shkak të vonesës së regjistrit ndryshimet më të reja se $1 {{PLURAL:$1|sekond|sekonda}} mund të mos tregohen në këtë listë.',

# Watchlist editor
'watchlistedit-numitems' => 'Lista mbikëqyrëse e juaj përmban {{PLURAL:$1|1 titull|$1 tituj}}, pa faqet e diskutimit.',
'watchlistedit-noitems' => 'Lista juaj mbikqyrëse nuk ka titull.',
'watchlistedit-normal-title' => 'Redakto listën mbikqyrëse',
'watchlistedit-normal-legend' => 'Largo titujt nga lista mbikqyrëse',
'watchlistedit-normal-explain' => 'Titujt në listën mbikëqyrëse janë treguar poshtë.
Largo titullin duke shënuar kutizën dhe pastaj shtype butonin Largoj titujt.
Ju gjithashtu mundeni ta redaktoni listën [[Special:EditWatchlist/raw|këtu]].',
'watchlistedit-normal-submit' => 'Largo Titujt',
'watchlistedit-normal-done' => '{{PLURAL:$1|1 titull u larguan|$1 tituj u larguan}} u larguan nga lista mbikëqyrëse e juaj:',
'watchlistedit-raw-title' => 'Redakto listën mbikëqyrëse të papërpunuar',
'watchlistedit-raw-legend' => 'Redakto listën mbikëqyrëse të papërpunuar',
'watchlistedit-raw-explain' => 'Titujt në listën tuaj mbikqyrëse janë të treguar poshtë dhe mund të redaktohen duke i shtuar ose duke i hequr nga lista; një titull pë rresht.
Kur të mbaroni, klikoni "{{int:Watchlistedit-raw-submit}}".
Ju gjithashtu mund [[Special:EditWatchlist|të përdorni redaktuesin standart]].',
'watchlistedit-raw-titles' => 'Titujt:',
'watchlistedit-raw-submit' => 'Aktualizoje listën',
'watchlistedit-raw-done' => 'Lista mbikëqyrëse u aktualizua.',
'watchlistedit-raw-added' => '{{PLURAL:$1|1 titull u shtua|$1 tituj u shtuan}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|1 titull u largua|$1 tituj u larguan}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Shih ndryshimet e rëndësishme',
'watchlisttools-edit' => 'Shih dhe redakto listën mbikqyrëse.',
'watchlisttools-raw' => 'Redaktoje drejtpërdrejt listën',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|diskutimet]])',

# Core parser functions
'unknown_extension_tag' => 'Etiketë shtesë e panjohur "$1"',
'duplicate-defaultsort' => '\'\'\'Kujdes:\'\'\' Renditja kryesore e çelësit "$2" refuzon renditjen e mëparshme kryesore të çelësit "$1".',

# Special:Version
'version' => 'Versioni',
'version-extensions' => 'Zgjerime të instaluara',
'version-specialpages' => 'Faqe speciale',
'version-parserhooks' => 'Parser goditje',
'version-variables' => 'Variabël',
'version-antispam' => 'Spam',
'version-skins' => 'Pamjet',
'version-other' => 'Të tjera',
'version-mediahandlers' => 'Mbajtesit e Media-s',
'version-hooks' => 'Goditjet',
'version-extension-functions' => 'Funksionet shtese',
'version-parser-extensiontags' => 'Parser etiketat shtese',
'version-parser-function-hooks' => 'Parser goditjet e funksionit',
'version-hook-name' => 'Emri i goditjes',
'version-hook-subscribedby' => 'Abonuar nga',
'version-version' => '(Versioni $1)',
'version-license' => 'Licensa',
'version-poweredby-credits' => "Ky wiki është mundësuar nga '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'të tjerë',
'version-license-info' => 'MediaWiki është një softuer i lirë; ju mund ta shpërndani dhe redakatoni atë nën kushtet GNU General Public License si e publikuar nga fondacioni Free Software; ose versioni 2 i licensës, ose çdo version më i vonshëm.

MediaWiki është shpërndarë me shpresën se do të jetë i dobishëm, por PA ASNJË GARANCI; as garancinë e shprehur të SHITJES apo PËRDORIMIT PËR NJË QËLLIM TË CAKTUAR. Shikoni GNU General Public License  për më shumë detaje.

Ju duhet të keni marrë [{{SERVER}}{{SCRIPTPATH}}/COPYING një kopje të GNU General Public License] së bashku me këtë program; nëse jo, shkruani tek Free Software Foundation, Inc., 51 Rruga Franklin, Kati i pestë, Boston, MA 02110-1301, ShBA ose [//www.gnu.org/licenses/old-licenses/gpl-2.0.html lexojeni atë online].',
'version-software' => 'Softuerët e instaluar',
'version-software-product' => 'Produkti',
'version-software-version' => 'Versioni',
'version-entrypoints-header-url' => 'URL',

# Special:FilePath
'filepath' => 'Vendndodhja e skedave',
'filepath-page' => 'Skeda:',
'filepath-submit' => 'Shko',
'filepath-summary' => 'Kjo faqe speciale jep vendndodhjen e plotë të një skede.
Figurat tregohen me madhësi të plotë, skedat e tjera hapen me programet përkatëse.',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Kërkoni për skeda të dyfishta',
'fileduplicatesearch-summary' => 'Kërkoni për dyfishime të skedave në bazë të vlerës përmbledhëse («hash»).',
'fileduplicatesearch-legend' => 'Kërko për dyfishime',
'fileduplicatesearch-filename' => 'Emri i skedës:',
'fileduplicatesearch-submit' => 'Kërko',
'fileduplicatesearch-info' => '$1 × $2 pixel<br />Madhësia e skedës: $3<br />Lloji MIME: $4',
'fileduplicatesearch-result-1' => 'Skeda "$1" nuk ka kopje të njëjta',
'fileduplicatesearch-result-n' => 'Skeda "$1" ka {{PLURAL:$2|1 dyfishim|$2 dyfishime}}.',
'fileduplicatesearch-noresults' => 'Nuk u gjet asnjë skedë me emrin "$1".',

# Special:SpecialPages
'specialpages' => 'Faqet speciale',
'specialpages-note' => '* Faqet speciale normale.
* <strong class="mw-specialpagerestricted">Faqet speciale të kufizuara.</strong>
* <span class="mw-specialpagecached">Faqet speciale të fshehtat (mund të jenë vjetëruar).</span>',
'specialpages-group-maintenance' => 'Përmbledhje mirëmbajtjeje',
'specialpages-group-other' => 'Faqe speciale të tjera',
'specialpages-group-login' => 'Hyrje dhe hapje llogarie',
'specialpages-group-changes' => 'Ndryshime së fundmi dhe regjistra',
'specialpages-group-media' => 'Përmbledhje media dhe ngarkime',
'specialpages-group-users' => 'Përdoruesit dhe privilegjet',
'specialpages-group-highuse' => 'Faqe të shumëpërdorura',
'specialpages-group-pages' => 'Lista e faqeve',
'specialpages-group-pagetools' => 'Mjetet e faqes',
'specialpages-group-wiki' => 'Mjetet dhe të dhënat wiki',
'specialpages-group-redirects' => 'Përcjellime tek faqet speciale',
'specialpages-group-spam' => 'Mjetet për spam',

# Special:BlankPage
'blankpage' => 'Faqe e zbrazët',
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
'tags' => 'Etiketat e ndryshimeve të pavlefshme',
'tag-filter' => '[[Special:Tags|Etiketa]] filter:',
'tag-filter-submit' => 'Filtër',
'tags-title' => 'Etiketat',
'tags-intro' => "Kjo faqe liston etiketat që softueri mund t'i shënojë me një redaktim, dhe kuptimin e tyre.",
'tags-tag' => 'Emri i etiketës',
'tags-display-header' => 'Dukja në listat e ndryshimeve',
'tags-description-header' => 'Përshkrimi i plotë i kuptimit',
'tags-hitcount-header' => 'Ndrzshimet e etikuara',
'tags-edit' => 'redakto',
'tags-hitcount' => '$1 {{PLURAL:$1|ndryshim|ndryshime}}',

# Special:ComparePages
'comparepages' => 'Krahasoni faqet',
'compare-selector' => 'Krahasoni versionet e faqeve',
'compare-page1' => 'Faqe 1',
'compare-page2' => 'Faqe 2',
'compare-rev1' => 'Version 1',
'compare-rev2' => 'Version 2',
'compare-submit' => 'Krahasimi',
'compare-invalid-title' => 'Titulli që keni specifikuar është i pavlefshëm',
'compare-title-not-exists' => 'Titulli që keni specifikuar nuk ekziston.',
'compare-revision-not-exists' => 'Rishikimi që ju specifikuat nuk ekziston',

# Database error messages
'dberr-header' => 'Kjo wiki ka një problem',
'dberr-problems' => 'Na vjen keq! 
Kjo faqe është duke përjetuar vështirësi teknike.',
'dberr-again' => 'Pritni disa minuta dhe provoni të ringarkoni faqen.',
'dberr-info' => '(Nuk mund të lidhet me serverin bazë e të dhënave : $1)',
'dberr-usegoogle' => 'Ju mund të provoni të kërkoni përmes Googles në ndërkohë.',
'dberr-outofdate' => 'Vini re se indekset e tyre të përmbajtjes tona mund të jetë e vjetëruar.',
'dberr-cachederror' => 'Kjo është një kopje e faqes së kërkuar dhe mund të jetë e vjetëruar.',

# HTML forms
'htmlform-invalid-input' => 'Ka probleme me disa kontribute tuaja',
'htmlform-select-badoption' => 'Vlera që ju e specifikuat nuk është një alternativë e vlefshme.',
'htmlform-int-invalid' => 'Vlera që ju e specifikuat nuk është numër i plotë.',
'htmlform-float-invalid' => 'Vlera që ju e specifikuat nuk është numër.',
'htmlform-int-toolow' => 'Vlera që ju e përcaktuat është nën minimumin e $1',
'htmlform-int-toohigh' => 'Vlera që ju e përcaktuat është mbi maksimumin e $1',
'htmlform-required' => 'Kjo vlerë është e nevojshme',
'htmlform-submit' => 'Dërgo',
'htmlform-reset' => 'Zhbëj ndryshimin',
'htmlform-selectorother-other' => 'Gjitha',

# SQLite database support
'sqlite-has-fts' => '$1 me mbështetje të kërkimit me teskt të plotë',
'sqlite-no-fts' => '$1 pa mbështetje të kërkimit me teskt të plotë',

# New logging system
'logentry-delete-delete' => '$1 grisi faqen $3',
'logentry-delete-restore' => '$1 riktheu faqen $3',
'logentry-delete-event' => '$1 ndryshoi dukshmërinë e {{PLURAL:$5|një ngjarje regjistri|$5 ngjarjeve regjistri}} në $3: $4',
'logentry-delete-revision' => '$1 ndryshoi dukshmërinë e {{PLURAL:$5|një versioni|$5 versioneve}} në $3: $4',
'logentry-delete-event-legacy' => '$1 ndryshoi dukshmërinë e ngjarjeve të regjistrit në $3',
'logentry-delete-revision-legacy' => '$1 ndryshoi dukshmërinë e versioneve në $3',
'logentry-suppress-delete' => '$1 shtypi faqen $3',
'logentry-suppress-event' => '$1 në mënyrë sekrete ndryshoi dukshmërinë e {{PLURAL:$5|një ngjarje regjistri|$5 ngjarjeve regjistri}} në $3: $4',
'logentry-suppress-revision' => '$1 në mënyrë sekrete ndryshoi dukshmërinë e {{PLURAL:$5|një versioni|$5 versioneve}} në $3: $4',
'logentry-suppress-event-legacy' => '$1 në mënyrë sekrete ndryshoi dukshmërinë e ngjarjeve të regjistrit në $3',
'logentry-suppress-revision-legacy' => '$1 në mënyrë sekrete ndryshoi dukshmërinë e versioneve në faqen $3',
'revdelete-content-hid' => 'përmbajtja u fsheh',
'revdelete-summary-hid' => 'redaktimi i përmbledhjes i fshehur',
'revdelete-uname-hid' => 'emri i përdoruesit i fshehur',
'revdelete-content-unhid' => 'përmbajtje jo e fshehur',
'revdelete-summary-unhid' => 'redaktimi i përmbledhjes jo i fshehur',
'revdelete-uname-unhid' => 'emri i përdoruesit jo i fshehur',
'revdelete-restricted' => 'u vendosën kufizime për administruesit',
'revdelete-unrestricted' => 'u hoqën kufizimet për administruesit',
'logentry-move-move' => '$1 zhvendosi faqen $3 te $4',
'logentry-move-move-noredirect' => '$1 zhvendosi faqen $3 te $4 pa lënë një përcjellim',
'logentry-move-move_redir' => '$1 zhvendosi faqen $3 te $4 nëpërmjet përcjellimit',
'logentry-move-move_redir-noredirect' => '$1 zhvendosi faqen $3 te $4 nëpërmjet një përcjellimi pa lënë një përcjellim',
'logentry-patrol-patrol' => '$1 shënoi versionin $4 të faqes $3 të patrolluar',
'logentry-patrol-patrol-auto' => '$1 automatikisht shënoi versionin $4 të faqes $3 të patrolluar',
'logentry-newusers-newusers' => '$1 krijoi një llogari',
'logentry-newusers-create' => '$1 krijoi një llogari',
'logentry-newusers-create2' => '$1 krijoi një llogari $3',
'logentry-newusers-autocreate' => 'Llogaria $1 u krijua automatikisht',
'newuserlog-byemail' => 'fjalëkalimi u dërgua në postën elektronike',

# Feedback
'feedback-bugornote' => 'Nëse jeni gati për të përshkruar një problem teknik me detaje ju lutemi [$1 raportoni një problem].
Përndryshe, ju mund të formularin e thjeshtë më poshtë. Komenti juaj do të shtohet te faqja "[$3 $2]"", së bashku me emrin tuaj të përdoruesit dhe shfletuesin të cilin jeni duke përdorur.',
'feedback-subject' => 'Subjekti:',
'feedback-message' => 'Mesazhi:',
'feedback-cancel' => 'Anulo',
'feedback-submit' => 'Paraqit përshtypjet',
'feedback-adding' => 'Duke shtuar përshtypjen te faqja...',
'feedback-error1' => 'Gabim: Rezultat i panjohur nga API',
'feedback-error2' => 'Gabim: Redaktimi dështoi',
'feedback-error3' => 'Gabim: Nuk ka përgjigje nga API',
'feedback-thanks' => 'Faleminderit! Përshtypja juaj është postuar në faqen "[$2 $1]".',
'feedback-close' => 'Përfunduar',
'feedback-bugcheck' => 'Shumë mirë! Thjesht kontrolloni që nuk është një nga [$1 problemet e njohura].',
'feedback-bugnew' => 'E kontrollova. Raporto një problem të ri',

# Search suggestions
'searchsuggest-search' => 'Kërko',
'searchsuggest-containing' => 'përmban ...',

# API errors
'api-error-badaccess-groups' => 'Ju nuk lejoheni të ngarkoni skeda në këtë wiki.',
'api-error-badtoken' => 'Gabim i brendshëm: Shenjë e keqe.',
'api-error-copyuploaddisabled' => 'Ngarkimi nga URL-ja është çaktivizuar në këtë server.',
'api-error-duplicate' => '{{PLURAL:$1|Ekziston [$2 një skedë tjetër]|Ekzistojnë [$2 disa skeda të tjera]}} me të njëjtën përmbajtje.',
'api-error-duplicate-archive' => '{{Ekzistonte [$2 një skedë tjetër]|Ekzistonin [$2 disa skeda të tjera]}} me të njëjtën përmbajtje, por {{PLURAL:$1|u gris|u grisën}}.',
'api-error-duplicate-archive-popup-title' => 'Dublo {{PLURAL:$1|skedë|skeda}} që janë grisur tashmë',
'api-error-duplicate-popup-title' => 'Dublo {{PLURAL:$1|skedë|skeda}}',
'api-error-empty-file' => 'Skeda që paraqitët ishte bosh.',
'api-error-emptypage' => 'Nuk lejohet krijimi i faqeve të reja bosh.',
'api-error-fetchfileerror' => 'Gabim i brendshëm: Diçka shkoi keq gjatë marrjes së skedës.',
'api-error-fileexists-forbidden' => 'Një skedar me emrin "$1" tashmë ekziston dhe nuk mund të mbishkruhet.',
'api-error-fileexists-shared-forbidden' => 'Një skedar me emrin "$1" tashmë ekziston në depon për skedarët e shpërndarë dhe nuk mund të mbishkruhet.',
'api-error-file-too-large' => 'Skeda që paraqitët ishte shumë e madhe.',
'api-error-filename-tooshort' => 'Emri i skedës është shumë i shkurtër.',
'api-error-filetype-banned' => 'Ky lloj i skedës është përjashtuar.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|nuk është një lloj i skedës së lejuar|nuk janë lloje të lejuara të skedave}}. {{PLURAL:$3|Lloji i lejuar i skedës është|Llojet e lejuara të skedave janë}} $2.',
'api-error-filetype-missing' => 'Skedës i mungon një shtesë.',
'api-error-hookaborted' => 'Modifikimi që provuat të bëni u ndërpre nga një goditje shtese.',
'api-error-http' => 'Gabim i brendshëm: Nuk mund të lidhet me serverin.',
'api-error-illegal-filename' => 'Emri i skedës nuk lejohet.',
'api-error-internal-error' => 'Gabim i brendshëm: Diçka shkoi keq me procesimin e ngarkimit tuaj në wiki.',
'api-error-invalid-file-key' => 'Gabim i brendshëm: Skeda nuk u gjet në ruajtjen e përkohshme.',
'api-error-missingparam' => 'Gabim i brendshëm: Mungesë e parametrave në kërkesë.',
'api-error-missingresult' => 'Gabim i brendshëm: Nuk mund të përcaktohet nëse kopjimi doli me sukses.',
'api-error-mustbeloggedin' => 'Ju duhet të identifikoheni për të ngarkuar skeda.',
'api-error-mustbeposted' => 'Gabim i brendshëm: Kërkesa kërkon HTTP POST.',
'api-error-noimageinfo' => 'Ngarkimi u krye me sukses, por serveri nuk na dha ndonjë informacion për këtë skedë.',
'api-error-nomodule' => 'Gabim i brendshëm: Nuk ka modul ngarkimi të vendosur.',
'api-error-ok-but-empty' => 'Gabim i brendshëm: Nuk ka përgjigje nga serveri.',
'api-error-overwrite' => 'Mbishkrimi i një skede ekzistuese nuk lejohet.',
'api-error-stashfailed' => 'Gabim i brendshëm: Serveri nuk arriti të ruajë skedën e përkohshme.',
'api-error-timeout' => 'Serveri nuk u përgjigj gjatës kohës që pritej.',
'api-error-unclassified' => 'Një gabim i panjohur ndodhi.',
'api-error-unknown-code' => 'Gabim i panjohur: "$1"',
'api-error-unknown-error' => 'Gabim i brendshëm: Diçka shkoi gabim kur provuat të ngarkoni skedën tuaj.',
'api-error-unknown-warning' => 'Paralajmërim i panjohur: $1',
'api-error-unknownerror' => 'Gabim i papërcaktuar: "$1".',
'api-error-uploaddisabled' => 'Ngarkimi është i çaktivizuar në këte wiki.',
'api-error-verification-error' => 'Skeda mund të jetë e korruptuar ose ka shtesë të gabuar.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|sekondë|sekonda}}',
'duration-minutes' => '$1 {{PLURAL:$1|minutë|minuta}}',
'duration-hours' => '$1 {{PLURAL:$1|orë|orë}}',
'duration-days' => '$1 {{PLURAL:$1|ditë|ditë}}',
'duration-weeks' => '$1 {{PLURAL:$1|javë|javë}}',
'duration-years' => '$1 {{PLURAL:$1|vit|vite}}',
'duration-decades' => '$1 {{PLURAL:$1|dekadë|dekada}}',
'duration-centuries' => '$1 {{PLURAL:$1|shekull|shekuj}}',
'duration-millennia' => '$1 {{PLURAL:$1|milennium|mileniume}}',

);
