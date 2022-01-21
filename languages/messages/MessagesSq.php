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
 * @author Klein Muçi
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
	'Ndihme' => NS_HELP,
	'Ndihme_diskutim' => NS_HELP_TALK,
	'Kategori' => NS_CATEGORY,
	'Kategori_Diskutim' => NS_CATEGORY_TALK,
];

$namespaceGenderAliases = [
	NS_USER      => [ 'male' => 'Përdoruesi', 'female' => 'Përdoruesja' ],
	NS_USER_TALK => [ 'male' => 'Përdoruesi_diskutim', 'female' => 'Përdoruesja_diskutim' ],
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'PërdoruesitAktivë' ],
	'Allmessages'               => [ 'Mesazhet', 'TëgjithaMesazhet' ],
	'Allpages'                  => [ 'Faqet', 'TëgjithaFaqet' ],
	'Ancientpages'              => [ 'FaqeTëVjetra', 'FaqetAntike' ],
	'Blankpage'                 => [ 'FaqeBosh' ],
	'Block'                     => [ 'Blloko', 'BllokoIP' ],
	'Booksources'               => [ 'BurimetELibrave', 'BurimeteLibrave' ],
	'Categories'                => [ 'Kategoritë', 'Kategori' ],
	'ChangeEmail'               => [ 'NdryshoEmail' ],
	'ChangePassword'            => [ 'NdryshoFjalëkalimin' ],
	'ComparePages'              => [ 'KrahasoFaqet' ],
	'Confirmemail'              => [ 'KonfirmoEmail' ],
	'Contributions'             => [ 'Kontributet' ],
	'CreateAccount'             => [ 'HapLlogari' ],
	'DeletedContributions'      => [ 'KontributeTëGrisura', 'GrisKontributet' ],
	'Emailuser'                 => [ 'DërgoEmail', 'EmailPërdoruesit' ],
	'Export'                    => [ 'Eksporto' ],
	'Import'                    => [ 'Importo' ],
	'Listadmins'                => [ 'RadhitAdminët', 'RreshtoAdmin' ],
	'Listbots'                  => [ 'RadhitRobotët', 'RreshtoBotët' ],
	'Listfiles'                 => [ 'RadhitSkedat', 'ListaSkedave' ],
	'Listusers'                 => [ 'RadhitPërdoruesit', 'RreshtoPërdoruesit' ],
	'Lockdb'                    => [ 'MbyllDB' ],
	'Longpages'                 => [ 'FaqeTëGjata', 'FaqeteGjata' ],
	'Movepage'                  => [ 'LëvizFaqen', 'LëvizFaqe' ],
	'Mycontributions'           => [ 'KontributetVetjake', 'KontributetëMiat' ],
	'Mypage'                    => [ 'FaqjaVetjake', 'FaqjaIme' ],
	'Mytalk'                    => [ 'DiskutimetVetjake', 'DiskutimiImë' ],
	'Myuploads'                 => [ 'NgarkimetVetjake', 'NgarkimeteMia' ],
	'Newimages'                 => [ 'SkedaTëReja' ],
	'Newpages'                  => [ 'FaqeTëReja', 'FaqeteReja' ],
	'Preferences'               => [ 'Parapëlqimet', 'Preferencat' ],
	'Protectedpages'            => [ 'FaqeTëMbrojtura', 'FaqeteMbrojtura' ],
	'Protectedtitles'           => [ 'TitujTëMbrojtur', 'TitujteMbrojtur' ],
	'Randompage'                => [ 'ArtikullIRastësishëm', 'Rastësishme', 'FaqeRastësishme' ],
	'Recentchanges'             => [ 'NdryshimeSëFundmi' ],
	'Search'                    => [ 'Kërko', 'Kërkim' ],
	'Shortpages'                => [ 'FaqeTëShkurtra', 'FasheteShkurta' ],
	'Specialpages'              => [ 'FaqetSpeciale' ],
	'Statistics'                => [ 'Statistikat', 'Statistika' ],
	'Unblock'                   => [ 'Zhblloko' ],
	'Uncategorizedcategories'   => [ 'KategoriTëPakategorizuara', 'KategoriTëpakategorizuara' ],
	'Uncategorizedimages'       => [ 'SkedaTëPakategorizuara', 'SkedaTëpakategorizuara' ],
	'Uncategorizedpages'        => [ 'FaqeTëPakategorizuara', 'FaqeTëpakategorizuara' ],
	'Uncategorizedtemplates'    => [ 'StampaTëPakategorizuara', 'StampaTëpakategorizuara' ],
	'Undelete'                  => [ 'Rikthe' ],
	'Unlockdb'                  => [ 'HapDB' ],
	'Unusedcategories'          => [ 'KategoriTëPapërdorura', 'KategoriTëpapërdorura' ],
	'Unusedimages'              => [ 'SkedaTëPapërdorura', 'SkedaTëpapërdorura' ],
	'Upload'                    => [ 'Ngarko' ],
	'Userlogin'                 => [ 'Hyrja', 'HyrjePërdoruesi' ],
	'Userlogout'                => [ 'Dalja', 'DaljePërdoruesi' ],
	'Version'                   => [ 'Versioni', 'Verzioni' ],
	'Wantedcategories'          => [ 'KategoriTëDëshiruara', 'KaetgoritëeDëshiruara' ],
	'Wantedfiles'               => [ 'SkedaTëDëshiruara', 'SkedateDëshiruara' ],
	'Wantedpages'               => [ 'FaqeTëDëshiruara', 'FaqeteDëshiruara' ],
	'Wantedtemplates'           => [ 'StampaTëDëshiruara', 'StampateDëshiruara' ],
	'Whatlinkshere'             => [ 'LidhjetKëtu' ],
	'Withoutinterwiki'          => [ 'PaLidhje', 'PaInterwiki' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'EMRIIFAQESBAZË', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'EMRIIFAQESBAZËE', 'BASEPAGENAMEE' ],
	'currentday'                => [ '1', 'DITASOT', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DITASOT2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'EMRIIDITËSOT', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'ORATANI', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MUAJIAKTUAL', 'MUAJIAKTUAL2', 'MUAJIMOMENTAL', 'MUAJIMOMENTAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MUAJIAKTUAL1', 'MUAJIMOMENTAL1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'EMRIIMUAJITAKTUAL', 'EMRIIMUAJITMOMENTAL', 'CURRENTMONTHNAME' ],
	'currenttime'               => [ '1', 'KOHATANI', 'CURRENTTIME' ],
	'currentweek'               => [ '1', 'JAVAAKTUALE', 'JAVAMOMENTALE', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'SIVJET', 'CURRENTYEAR' ],
	'fullpagename'              => [ '1', 'EMRIIPLOTËIFAQES', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'EMRIIPLOTËIFAQESE', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'URLEPLOTË', 'FULLURL:' ],
	'gender'                    => [ '0', 'GJINIA:', 'GENDER:' ],
	'grammar'                   => [ '0', 'GRAMATIKA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__KATEGORIEFSHEHUR__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'linjabazë', 'baseline' ],
	'img_border'                => [ '1', 'kufi', 'border' ],
	'img_bottom'                => [ '1', 'fund', 'bottom' ],
	'img_center'                => [ '1', 'qendër', 'qendrore', 'center', 'centre' ],
	'img_framed'                => [ '1', 'i_kornizuar', 'pa_kornizë', 'kornizë', 'frame', 'framed', 'enframed' ],
	'img_left'                  => [ '1', 'majtas', 'left' ],
	'img_link'                  => [ '1', 'lidhja=$1', 'lidhje=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'parapamje=$1', 'pamje=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'mes', 'middle' ],
	'img_none'                  => [ '1', 'asnjë', 's\'ka', 'none' ],
	'img_page'                  => [ '1', 'faqja=$1', 'faqja $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'djathtas', 'right' ],
	'img_sub'                   => [ '1', 'nën', 'sub' ],
	'img_text_bottom'           => [ '1', 'tekst-fund', 'text-bottom' ],
	'img_text_top'              => [ '1', 'tekst-majë', 'tekst-top', 'text-top' ],
	'img_thumbnail'             => [ '1', 'parapamje', 'pamje', 'thumb', 'thumbnail' ],
	'img_upright'               => [ '1', 'vertikale', 'vertikale=$1', 'vertikale $1', 'lartdjathtas', 'lartdjathtas=$1', 'lartdjathtas $1', 'upright', 'upright=$1', 'upright $1' ],
	'language'                  => [ '0', '#GJUHA:', '#LANGUAGE:' ],
	'localday'                  => [ '1', 'DITALOKALE', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DITALOKALE2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'EMRIIDITËSLOKALE', 'LOCALDAYNAME' ],
	'localhour'                 => [ '1', 'ORALOKALE', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'MUAJILOKAL', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'EMRIIMUAJITLOKAL', 'LOCALMONTHNAME' ],
	'localtime'                 => [ '1', 'KOHALOKALE', 'LOCALTIME' ],
	'localurl'                  => [ '0', 'URLLOKALE', 'LOCALURL:' ],
	'localyear'                 => [ '1', 'VITILOKAL', 'LOCALYEAR' ],
	'namespace'                 => [ '1', 'HAPËSIRA', 'NAMESPACE' ],
	'noeditsection'             => [ '0', '__PAREDAKTIMPJESE__', '__JOREDAKTIMSEKSIONI__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__PAGALERI__', '__JOGALERI__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__PATP__', '__JOTP__', '__NOTOC__' ],
	'numberofactiveusers'       => [ '1', 'NUMRIIPËRDORUESVEAKTIVË', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'NUMRIIADMINISTRATORËVE', 'NUMRIIADMINISTRUESVE', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'NUMRIIARTIKUJVE', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'NUMRIREDAKTIMEVE', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'NUMRIISKEDAVE', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'NUMRIFAQEVE', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'NUMRIIPËRDORUESVE', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'EMRIFAQES', 'PAGENAME' ],
	'pagesize'                  => [ '1', 'MADHËSIAEFAQES', 'PAGESIZE' ],
	'plural'                    => [ '0', 'SHUMËS:', 'PLURAL:' ],
	'redirect'                  => [ '0', '#RIDREJTO', '#REDIRECT' ],
	'server'                    => [ '0', 'SERVERI', 'SERVER' ],
	'servername'                => [ '0', 'EMRIISERVERIT', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'EMRIISITIT', 'EMRIISAJTIT', 'SITENAME' ],
	'subpagename'               => [ '1', 'EMRIINËNFAQES', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'EMRIINËNFAQESE', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'ZËVN', 'SUBST:' ],
	'talkpagename'              => [ '1', 'EMRIIFAQESSËDISKUTIMIT', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'EMRIIFAQESSËDISKUTIMITE', 'TALKPAGENAMEE' ],
	'toc'                       => [ '0', '__TP__', '__TOC__' ],
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
