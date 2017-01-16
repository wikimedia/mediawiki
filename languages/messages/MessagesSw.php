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

$namespaceNames = [
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
];

$namespaceAliases = [
	'$1_majadiliano'        => NS_PROJECT_TALK,
	'Majadiliano_faili'     => NS_FILE_TALK,
	'MediaWiki_majadiliano' => NS_MEDIAWIKI_TALK,
	'Kigezo_majadiliano'    => NS_TEMPLATE_TALK,
	'Msaada_majadiliano'    => NS_HELP_TALK,
	'Jamii_majadiliano'     => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Allmessages'               => [ 'UjumbeZote' ],
	'Allpages'                  => [ 'KurasaZote' ],
	'Ancientpages'              => [ 'KurasazaZamani' ],
	'Blankpage'                 => [ 'KurasaTupu' ],
	'Block'                     => [ 'Zui', 'ZuiaIP', 'ZuiaMtumiaji' ],
	'Booksources'               => [ 'ZuiaChanzo' ],
	'BrokenRedirects'           => [ 'ElekezoIliovunjika' ],
	'Categories'                => [ 'Jamii' ],
	'Confirmemail'              => [ 'ThibitishaBaruapepe' ],
	'Contributions'             => [ 'Michango' ],
	'CreateAccount'             => [ 'SajiliAkaunti' ],
	'Deadendpages'              => [ 'KurasaZilizoondoshwa' ],
	'DeletedContributions'      => [ 'MichangoIliyofutwa' ],
	'DoubleRedirects'           => [ 'ElekezoMbili' ],
	'Emailuser'                 => [ 'BaruapepeyaMtumiaji' ],
	'Export'                    => [ 'Toa' ],
	'Fewestrevisions'           => [ 'MarejeoMadogo' ],
	'Import'                    => [ 'Ingiza' ],
	'BlockList'                 => [ 'OrodhayaIPZilizozuiliwa' ],
	'LinkSearch'                => [ 'TafutaKiungo' ],
	'Listadmins'                => [ 'OrodhayaWakabidhi' ],
	'Listbots'                  => [ 'OrodhayaVikaragosi' ],
	'Listfiles'                 => [ 'OrodhayaFali', 'OrodhayaPicha' ],
	'Listgrouprights'           => [ 'OrodhayaWasimamizi' ],
	'Listusers'                 => [ 'OrodhayaWatumiaji', 'OrodhayaMtumiaji' ],
	'Lockdb'                    => [ 'FungaDB' ],
	'Log'                       => [ 'Kumbukumbu' ],
	'Lonelypages'               => [ 'KurasaPweke' ],
	'Longpages'                 => [ 'KurasaNdefu' ],
	'MIMEsearch'                => [ 'TafutaMIME' ],
	'Mostcategories'            => [ 'JamiiZaidi' ],
	'Mostimages'                => [ 'FailiZilizoungwasana', 'PichaZilizoungwasana' ],
	'Mostlinked'                => [ 'KurasaZilizoungwasana' ],
	'Mostlinkedcategories'      => [ 'JamiiZilizoungwasana' ],
	'Mostlinkedtemplates'       => [ 'VigezoVilivyoungwasana' ],
	'Mostrevisions'             => [ 'MarejeoZaidi' ],
	'Movepage'                  => [ 'HamishaKurasa' ],
	'Mycontributions'           => [ 'MichangoYangu' ],
	'Mypage'                    => [ 'KurasaYangu' ],
	'Mytalk'                    => [ 'MajadilianoYangu' ],
	'Newimages'                 => [ 'FailiMpya', 'FailimpyazaPicha' ],
	'Newpages'                  => [ 'KurasaMpya' ],
	'Preferences'               => [ 'Mapendekezo' ],
	'Prefixindex'               => [ 'KurasaKuu' ],
	'Protectedpages'            => [ 'KurasaZilizolindwa' ],
	'Protectedtitles'           => [ 'JinaLililolindwa' ],
	'Randompage'                => [ 'UkurasawaBahati' ],
	'Recentchanges'             => [ 'MabadalikoyaKaribuni' ],
	'Search'                    => [ 'Tafuta' ],
	'Shortpages'                => [ 'KurasaFupi' ],
	'Specialpages'              => [ 'KurasaMaalum' ],
	'Statistics'                => [ 'Takwimu' ],
	'Uncategorizedcategories'   => [ 'JamiiZisizopangwa' ],
	'Uncategorizedimages'       => [ 'FailiZisizonajamii' ],
	'Uncategorizedpages'        => [ 'KurasaZisizonajamii' ],
	'Uncategorizedtemplates'    => [ 'VigezoVisivyonajamii' ],
	'Undelete'                  => [ 'Usifute' ],
	'Unlockdb'                  => [ 'FunguaDB' ],
	'Unusedcategories'          => [ 'JamiiZisizotumika' ],
	'Unusedimages'              => [ 'FailiZisizotumika', 'PichaZisizotumika' ],
	'Upload'                    => [ 'Pakia' ],
	'Userlogin'                 => [ 'IngiaMtumiaji' ],
	'Userlogout'                => [ 'TokaMtumiaji' ],
	'Userrights'                => [ 'HakizaMtumiaji' ],
	'Version'                   => [ 'Toleo' ],
	'Wantedcategories'          => [ 'JamiiZinazotakikana' ],
	'Wantedfiles'               => [ 'FailiZinazotakikana' ],
	'Wantedpages'               => [ 'KurasaZinazotakikana', 'ViungoVilivyovunjika' ],
	'Wantedtemplates'           => [ 'VigezoVinavyotakikana' ],
	'Watchlist'                 => [ 'Maangalizi' ],
	'Whatlinkshere'             => [ 'VingoViungavyoUkurasahuu' ],
];
