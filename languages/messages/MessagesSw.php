<?php
/** Swahili (Kiswahili)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ikiwaner
 * @author Jagwar
 * @author Kaganer
 * @author Lloffiwr
 * @author Malangali
 * @author Marcos
 * @author Muddyb Blast Producer
 * @author Nemo bis
 * @author Robert Ullmann
 * @author Stephenwanjau
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Faili',
	NS_SPECIAL          => 'Maalum',
	NS_TALK             => 'Majadiliano',
	NS_USER             => 'Mtumiaji',
	NS_USER_TALK        => 'Majadiliano_ya_mtumiaji',
	NS_PROJECT_TALK     => 'Majadiliano_ya_$1',
	NS_FILE             => 'Picha',
	NS_FILE_TALK        => 'Majadiliano_ya_faili',
	NS_MEDIAWIKI_TALK   => 'Majadiliano_ya_MediaWiki',
	NS_TEMPLATE         => 'Kigezo',
	NS_TEMPLATE_TALK    => 'Majadiliano_ya_kigezo',
	NS_HELP             => 'Msaada',
	NS_HELP_TALK        => 'Majadiliano_ya_msaada',
	NS_CATEGORY         => 'Jamii',
	NS_CATEGORY_TALK    => 'Majadiliano_ya_jamii',
);

$namespaceAliases = array(
	'$1_majadiliano'        => NS_PROJECT_TALK,
	'Majadiliano_faili'     => NS_FILE_TALK,
	'MediaWiki_majadiliano' => NS_MEDIAWIKI_TALK,
	'Kigezo_majadiliano'    => NS_TEMPLATE_TALK,
	'Msaada_majadiliano'    => NS_HELP_TALK,
	'Jamii_majadiliano'     => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'UjumbeZote' ),
	'Allpages'                  => array( 'KurasaZote' ),
	'Ancientpages'              => array( 'KurasazaZamani' ),
	'Blankpage'                 => array( 'KurasaTupu' ),
	'Block'                     => array( 'Zui', 'ZuiaIP', 'ZuiaMtumiaji' ),
	'Booksources'               => array( 'ZuiaChanzo' ),
	'BrokenRedirects'           => array( 'ElekezoIliovunjika' ),
	'Categories'                => array( 'Jamii' ),
	'Confirmemail'              => array( 'ThibitishaBaruapepe' ),
	'Contributions'             => array( 'Michango' ),
	'CreateAccount'             => array( 'SajiliAkaunti' ),
	'Deadendpages'              => array( 'KurasaZilizoondoshwa' ),
	'DeletedContributions'      => array( 'MichangoIliyofutwa' ),
	'DoubleRedirects'           => array( 'ElekezoMbili' ),
	'Emailuser'                 => array( 'BaruapepeyaMtumiaji' ),
	'Export'                    => array( 'Toa' ),
	'Fewestrevisions'           => array( 'MarejeoMadogo' ),
	'Import'                    => array( 'Ingiza' ),
	'BlockList'                 => array( 'OrodhayaIPZilizozuiliwa' ),
	'LinkSearch'                => array( 'TafutaKiungo' ),
	'Listadmins'                => array( 'OrodhayaWakabidhi' ),
	'Listbots'                  => array( 'OrodhayaVikaragosi' ),
	'Listfiles'                 => array( 'OrodhayaFali', 'OrodhayaPicha' ),
	'Listgrouprights'           => array( 'OrodhayaWasimamizi' ),
	'Listusers'                 => array( 'OrodhayaWatumiaji', 'OrodhayaMtumiaji' ),
	'Lockdb'                    => array( 'FungaDB' ),
	'Log'                       => array( 'Kumbukumbu' ),
	'Lonelypages'               => array( 'KurasaPweke' ),
	'Longpages'                 => array( 'KurasaNdefu' ),
	'MIMEsearch'                => array( 'TafutaMIME' ),
	'Mostcategories'            => array( 'JamiiZaidi' ),
	'Mostimages'                => array( 'FailiZilizoungwasana', 'PichaZilizoungwasana' ),
	'Mostlinked'                => array( 'KurasaZilizoungwasana' ),
	'Mostlinkedcategories'      => array( 'JamiiZilizoungwasana' ),
	'Mostlinkedtemplates'       => array( 'VigezoVilivyoungwasana' ),
	'Mostrevisions'             => array( 'MarejeoZaidi' ),
	'Movepage'                  => array( 'HamishaKurasa' ),
	'Mycontributions'           => array( 'MichangoYangu' ),
	'Mypage'                    => array( 'KurasaYangu' ),
	'Mytalk'                    => array( 'MajadilianoYangu' ),
	'Newimages'                 => array( 'FailiMpya', 'FailimpyazaPicha' ),
	'Newpages'                  => array( 'KurasaMpya' ),

	'Preferences'               => array( 'Mapendekezo' ),
	'Prefixindex'               => array( 'KurasaKuu' ),
	'Protectedpages'            => array( 'KurasaZilizolindwa' ),
	'Protectedtitles'           => array( 'JinaLililolindwa' ),
	'Randompage'                => array( 'UkurasawaBahati' ),
	'Recentchanges'             => array( 'MabadalikoyaKaribuni' ),
	'Search'                    => array( 'Tafuta' ),
	'Shortpages'                => array( 'KurasaFupi' ),
	'Specialpages'              => array( 'KurasaMaalum' ),
	'Statistics'                => array( 'Takwimu' ),
	'Uncategorizedcategories'   => array( 'JamiiZisizopangwa' ),
	'Uncategorizedimages'       => array( 'FailiZisizonajamii' ),
	'Uncategorizedpages'        => array( 'KurasaZisizonajamii' ),
	'Uncategorizedtemplates'    => array( 'VigezoVisivyonajamii' ),
	'Undelete'                  => array( 'Usifute' ),
	'Unlockdb'                  => array( 'FunguaDB' ),
	'Unusedcategories'          => array( 'JamiiZisizotumika' ),
	'Unusedimages'              => array( 'FailiZisizotumika', 'PichaZisizotumika' ),
	'Upload'                    => array( 'Pakia' ),
	'Userlogin'                 => array( 'IngiaMtumiaji' ),
	'Userlogout'                => array( 'TokaMtumiaji' ),
	'Userrights'                => array( 'HakizaMtumiaji' ),
	'Version'                   => array( 'Toleo' ),
	'Wantedcategories'          => array( 'JamiiZinazotakikana' ),
	'Wantedfiles'               => array( 'FailiZinazotakikana' ),
	'Wantedpages'               => array( 'KurasaZinazotakikana', 'ViungoVilivyovunjika' ),
	'Wantedtemplates'           => array( 'VigezoVinavyotakikana' ),
	'Watchlist'                 => array( 'Maangalizi' ),
	'Whatlinkshere'             => array( 'VingoViungavyoUkurasahuu' ),
);

