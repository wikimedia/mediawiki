<?php
/** Albanian (shqip)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Amire80
 * @author Andejkendej
 * @author Cradel
 * @author Dashohoxha
 * @author Dasius
 * @author Dori
 * @author Eagleal
 * @author Ergon
 * @author Euriditi
 * @author FatosMorina
 * @author GretaDoci
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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Perdoruesi' => NS_USER,
	'Perdoruesi_diskutim' => NS_USER_TALK,
	'Figura' => NS_FILE,
	'Figura_diskutim' => NS_FILE_TALK,
	'Kategori' => NS_CATEGORY,
	'Kategori_Diskutim' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Përdoruesi', 'female' => 'Përdoruesja' ],
	NS_USER_TALK => [ 'male' => 'Përdoruesi_diskutim', 'female' => 'Përdoruesja_diskutim' ],
];

$specialPageAliases = [
	'Activeusers'               => [ 'PërdoruesitAktivë' ],
	'Allmessages'               => [ 'TëgjithaMesazhet' ],
	'Allpages'                  => [ 'TëgjithaFaqet' ],
	'Ancientpages'              => [ 'FaqetAntike' ],
	'Blankpage'                 => [ 'FaqeBosh' ],
	'Block'                     => [ 'BllokoIP' ],
	'Booksources'               => [ 'BurimeteLibrave' ],
	'Categories'                => [ 'Kategori' ],
	'ChangeEmail'               => [ 'NdryshoEmail' ],
	'ChangePassword'            => [ 'NdryshoFjalëkalimin' ],
	'ComparePages'              => [ 'KrahasoFaqet' ],
	'Confirmemail'              => [ 'KonfirmoEmail' ],
	'Contributions'             => [ 'Kontributet' ],
	'CreateAccount'             => [ 'HapLlogari' ],
	'DeletedContributions'      => [ 'GrisKontributet' ],
	'Emailuser'                 => [ 'EmailPërdoruesit' ],
	'Export'                    => [ 'Eksporto' ],
	'Import'                    => [ 'Importo' ],
	'Listadmins'                => [ 'RreshtoAdmin' ],
	'Listbots'                  => [ 'RreshtoBotët' ],
	'Listfiles'                 => [ 'ListaSkedave' ],
	'Listusers'                 => [ 'RreshtoPërdoruesit' ],
	'Lockdb'                    => [ 'MbyllDB' ],
	'Longpages'                 => [ 'FaqeteGjata' ],
	'Movepage'                  => [ 'LëvizFaqe' ],
	'Mycontributions'           => [ 'KontributetëMiat' ],
	'Mypage'                    => [ 'FaqjaIme' ],
	'Mytalk'                    => [ 'DiskutimiImë' ],
	'Myuploads'                 => [ 'NgarkimeteMia' ],
	'Newimages'                 => [ 'SkedaTëReja' ],
	'Newpages'                  => [ 'FaqeteReja' ],
	'Preferences'               => [ 'Preferencat' ],
	'Protectedpages'            => [ 'FaqeteMbrojtura' ],
	'Protectedtitles'           => [ 'TitujteMbrojtur' ],
	'Randompage'                => [ 'Rastësishme', 'FaqeRastësishme' ],
	'Recentchanges'             => [ 'NdryshimeSëFundmi' ],
	'Search'                    => [ 'Kërkim' ],
	'Shortpages'                => [ 'FasheteShkurta' ],
	'Specialpages'              => [ 'FaqetSpeciale' ],
	'Statistics'                => [ 'Statistika' ],
	'Unblock'                   => [ 'Zhblloko' ],
	'Uncategorizedcategories'   => [ 'KategoriTëpakategorizuara' ],
	'Uncategorizedimages'       => [ 'SkedaTëpakategorizuara' ],
	'Uncategorizedpages'        => [ 'FaqeTëpakategorizuara' ],
	'Uncategorizedtemplates'    => [ 'StampaTëpakategorizuara' ],
	'Undelete'                  => [ 'Rikthe' ],
	'Unlockdb'                  => [ 'HapDB' ],
	'Unusedcategories'          => [ 'KategoriTëpapërdorura' ],
	'Unusedimages'              => [ 'SkedaTëpapërdorura' ],
	'Upload'                    => [ 'Ngarko' ],
	'Userlogin'                 => [ 'HyrjePërdoruesi' ],
	'Userlogout'                => [ 'DaljePërdoruesi' ],
	'Version'                   => [ 'Verzioni' ],
	'Wantedcategories'          => [ 'KaetgoritëeDëshiruara' ],
	'Wantedfiles'               => [ 'SkedateDëshiruara' ],
	'Wantedpages'               => [ 'FaqeteDëshiruara' ],
	'Wantedtemplates'           => [ 'StampateDëshiruara' ],
	'Whatlinkshere'             => [ 'LidhjetKëtu' ],
	'Withoutinterwiki'          => [ 'PaInterwiki' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#RIDREJTO', '#REDIRECT' ],
	'notoc'                     => [ '0', '__JOTP__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__JOGALERI__', '__NOGALLERY__' ],
	'toc'                       => [ '0', '__TP__', '__TOC__' ],
	'noeditsection'             => [ '0', '__JOREDAKTIMSEKSIONI__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MUAJIMOMENTAL', 'MUAJIMOMENTAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MUAJIMOMENTAL1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ],
	'currentday'                => [ '1', 'DITASOT', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DITASOT2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'EMRIIDITËSOT', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'SIVJET', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'KOHATANI', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'ORATANI', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'EMRIIMUAJITLOKAL', 'LOCALMONTHNAME' ],
	'localday'                  => [ '1', 'DITALOKALE', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DITALOKALE2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'EMRIIDITËSLOKALE', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'VITILOKAL', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'KOHALOKALE', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'ORALOKALE', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'NUMRIFAQEVE', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'NUMRIIARTIKUJVE', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'NUMRIISKEDAVE', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NUMRIIPËRDORUESVE', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'NUMRIIPËRDORUESVEAKTIVË', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'NUMRIREDAKTIMEVE', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'EMRIFAQES', 'PAGENAME' ],
	'namespace'                 => [ '1', 'HAPËSIRA', 'NAMESPACE' ],
	'fullpagename'              => [ '1', 'EMRIIPLOTËIFAQES', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'EMRIIPLOTËIFAQESE', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'EMRIINËNFAQES', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'EMRIINËNFAQESE', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'EMRIIFAQESBAZË', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'EMRIIFAQESBAZËE', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'EMRIIFAQESSËDISKUTIMIT', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'EMRIIFAQESSËDISKUTIMITE', 'TALKPAGENAMEE' ],
	'subst'                     => [ '0', 'ZËVN', 'SUBST:' ],
	'img_thumbnail'             => [ '1', 'parapamje', 'pamje', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'parapamje=$1', 'pamje=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'djathtas', 'right' ],
	'img_left'                  => [ '1', 'majtas', 'left' ],
	'img_none'                  => [ '1', 's\'ka', 'none' ],
	'img_center'                => [ '1', 'qendër', 'qendrore', 'center', 'centre' ],
	'img_framed'                => [ '1', 'i_kornizuar', 'pa_kornizë', 'kornizë', 'frame', 'framed', 'enframed' ],
	'img_page'                  => [ '1', 'faqja=$1', 'faqja $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'lartdjathtas', 'lartdjathtas=$1', 'lartdjathtas $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'kufi', 'border' ],
	'img_baseline'              => [ '1', 'linjabazë', 'baseline' ],
	'img_sub'                   => [ '1', 'nën', 'sub' ],
	'img_text_top'              => [ '1', 'tekst-top', 'text-top' ],
	'img_middle'                => [ '1', 'mes', 'middle' ],
	'img_bottom'                => [ '1', 'fund', 'bottom' ],
	'img_text_bottom'           => [ '1', 'tekst-fund', 'text-bottom' ],
	'img_link'                  => [ '1', 'lidhje=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'EMRIISAJTIT', 'SITENAME' ],
	'localurl'                  => [ '0', 'URLLOKALE', 'LOCALURL:' ],
	'server'                    => [ '0', 'SERVERI', 'SERVER' ],
	'servername'                => [ '0', 'EMRIISERVERIT', 'SERVERNAME' ],
	'grammar'                   => [ '0', 'GRAMATIKA:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'GJINIA:', 'GENDER:' ],
	'currentweek'               => [ '1', 'JAVAMOMENTALE', 'CURRENTWEEK' ],
	'plural'                    => [ '0', 'SHUMËS:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'URLEPLOTË', 'FULLURL:' ],
	'language'                  => [ '0', '#GJUHA:', '#LANGUAGE:' ],
	'numberofadmins'            => [ '1', 'NUMRIIADMINISTRUESVE', 'NUMBEROFADMINS' ],
	'hiddencat'                 => [ '1', '__KATEGORIEFSHEHUR__', '__HIDDENCAT__' ],
	'pagesize'                  => [ '1', 'MADHËSIAEFAQES', 'PAGESIZE' ],
];

$datePreferences = [
	'default',
	'dmy',
	'ISO 8601',
];
$defaultDateFormat = 'dmy';
$dateFormats = [
	'dmy time' => 'H:i',
	'dmy date' => 'j F Y',
	'dmy both' => 'j F Y H:i',
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
