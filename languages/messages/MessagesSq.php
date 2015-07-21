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

