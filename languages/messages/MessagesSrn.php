<?php
/** Sranan Tongo (Sranantongo)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Adfokati
 * @author Jordi
 * @author Kaganer
 * @author Ooswesthoesbes
 * @author Stretsh
 * @author Urhixidur
 */

$fallback = 'nl';

$namespaceNames = array(
	NS_SPECIAL          => 'Spesyal',
	NS_TALK             => 'Taki',
	NS_USER             => 'Masyin',
	NS_USER_TALK        => 'Taki_fu_masyin',
	NS_PROJECT_TALK     => 'Taki_fu_$1',
	NS_FILE             => 'Gefre',
	NS_FILE_TALK        => 'Taki_fu_gefre',
	NS_MEDIAWIKI_TALK   => 'Taki_fu_MediaWiki',
	NS_TEMPLATE         => 'Ankra',
	NS_TEMPLATE_TALK    => 'Taki_fu_ankra',
	NS_HELP             => 'Yepi',
	NS_HELP_TALK        => 'Taki_fu_yepi',
	NS_CATEGORY         => 'Guru',
	NS_CATEGORY_TALK    => 'Taki_fu_guru',
);

$namespaceAliases = array(
	'Speciaal' => NS_SPECIAL,
	'Overleg' => NS_TALK,
	'Gebruiker' => NS_USER,
	'Overleg_gebruiker' => NS_USER_TALK,
	'Overleg_$1' => NS_PROJECT_TALK,
	'Afbeelding' => NS_FILE,
	'Overleg_afbeelding' => NS_FILE_TALK,
	'Overleg_MediaWiki' => NS_MEDIAWIKI_TALK,
	'Sjabloon' => NS_TEMPLATE,
	'Overleg_sjabloon' => NS_TEMPLATE_TALK,
	'Help' => NS_HELP,
	'Overleg_help' => NS_HELP_TALK,
	'Categorie' => NS_CATEGORY,
	'Overleg_categorie' => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Boskopu' ),
	'Allpages'                  => array( 'AlaPeprewoysi' ),
	'Ancientpages'              => array( 'PasaOwruPeprewoysi' ),
	'BrokenRedirects'           => array( 'BrokoStirpeprewoysi' ),
	'Categories'                => array( 'Guru' ),
	'Contributions'             => array( 'Kenki' ),
	'Deadendpages'              => array( 'NoSkakiPeprewoysi' ),
	'DoubleRedirects'           => array( 'Tustirpeprewoysi' ),
	'Emailuser'                 => array( 'EmailMasyin' ),
	'Fewestrevisions'           => array( 'MenaKenki' ),
	'Listadmins'                => array( 'Sesopurey' ),
	'Listbots'                  => array( 'Botrey' ),
	'Listfiles'                 => array( 'Gefrerey' ),
	'Listredirects'             => array( 'Stirpeprewoysirey' ),
	'Listusers'                 => array( 'Masyinrey' ),
	'Log'                       => array( 'Buku' ),
	'Lonelypages'               => array( 'WawanPeprewoysi' ),
	'Longpages'                 => array( 'LangaPeprewoysi' ),
	'MIMEsearch'                => array( 'MIMEsuku' ),
	'Mostcategories'            => array( 'PasaGuru' ),
	'Mostimages'                => array( 'PasaGefre' ),
	'Mostlinked'                => array( 'PasatekiPeprewoysi' ),
	'Mostlinkedcategories'      => array( 'PasatekiGuru' ),
	'Mostlinkedtemplates'       => array( 'PasatekiAnkra' ),
	'Mostrevisions'             => array( 'PasaKenki' ),
	'Movepage'                  => array( 'PapiraDribi' ),
	'Mycontributions'           => array( 'MiKenki' ),
	'Mypage'                    => array( 'MiPapira' ),
	'Mytalk'                    => array( 'MiTaki' ),
	'Newimages'                 => array( 'NyunGefre' ),
	'Newpages'                  => array( 'NyunPeprewoysi' ),
	'Protectedpages'            => array( 'TapuPeprewoysi' ),
	'Randompage'                => array( 'SomaPapira' ),
	'Randomredirect'            => array( 'SomaStirpapira' ),
	'Recentchanges'             => array( 'BakaseywanKenki' ),
	'Search'                    => array( 'Suku' ),
	'Shortpages'                => array( 'SyartuPeprewoysi' ),
	'Specialpages'              => array( 'SpesyalPeprewoysi' ),
	'Uncategorizedcategories'   => array( 'OguruGuru' ),
	'Uncategorizedimages'       => array( 'OguruGefre' ),
	'Uncategorizedpages'        => array( 'OguruPeprewoysi' ),
	'Uncategorizedtemplates'    => array( 'OguruAnkra' ),
	'Undelete'                  => array( 'Otrowe' ),
	'Unusedcategories'          => array( 'OtekiGuru' ),
	'Unusedimages'              => array( 'OtekiGefre' ),
	'Unusedtemplates'           => array( 'OtekiAnkra' ),
	'Upload'                    => array( 'Uploti' ),
	'Userlogin'                 => array( 'Kon' ),
	'Userlogout'                => array( 'Gwe' ),
	'Userrights'                => array( 'Masyinlesi' ),
	'Version'                   => array( 'Si' ),
	'Wantedcategories'          => array( 'WinsiGuru' ),
	'Wantedpages'               => array( 'WinsiPeprewoysi' ),
	'Watchlist'                 => array( 'Sirey' ),
	'Withoutinterwiki'          => array( 'NoInterwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#STIR', '#DOORVERWIJZING', '#REDIRECT' ),
	'notoc'                     => array( '0', '__NOINOT__', '__GEENINHOUD__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__NOPIKTURAMA__', '__GEEN_GALERIJ__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__INOTDWENGI__', '__INHOUD_DWINGEN__', '__FORCEERINHOUD__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__INOT__', '__INHOUD__', '__TOC__' ),
	'noeditsection'             => array( '0', '__NOKENKISKAKI__', '__NIETBEWERKBARESECTIE__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'CURRENTMOTNH', 'DISIMUN', 'HUIDIGEMAAND', 'HUIDIGEMAAND2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'          => array( '1', 'DISIMUNNEN', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'DISIMUNNENGEN', 'HUIDIGEMAANDGEN', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'DISIMUNSH', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'DISIDEY', 'HUIDIGEDAG', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DISIDEY2', 'HUIDIGEDAG2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'DISIDEYNEN', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'DISIYARI', 'HUIDIGJAAR', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'DISITEN', 'HUIDIGETIJD', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'DISIYURU', 'HUIDIGUUR', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'PRESIMUN', 'PLAATSELIJKEMAAND', 'LOKALEMAAND', 'LOKALEMAAND2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'PRESIMUNNEN', 'PLAATSELIJKEMAANDNAAM', 'LOKALEMAANDNAAM', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'PRESIMUNNENGEN', 'PLAATSELIJKEMAANDNAAMGEN', 'LOKALEMAANDNAAMGEN', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'PRESIMUNSH', 'PLAATSELIJKEMAANDAFK', 'LOKALEMAANDAFK', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'PRESIDEY', 'PLAATSELIJKEDAG', 'LOKALEDAG', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'PRESIDEY2', 'PLAATSELIJKEDAG2', 'LOKALEDAG2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'PRESIDEYNEN', 'PLAATSELIJKEDAGNAAM', 'LOKALEDAGNAAM', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'PRESIYARI', 'PLAATSELIJKJAAR', 'LOKAALJAAR', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'PRESITEN', 'PLAATSELIJKETIJD', 'LOKALETIJD', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'PRESIYURU', 'PLAATSELIJKUUR', 'LOKAALUUR', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'PAPIRANUMRO', 'AANTALPAGINAS', 'AANTALPAGINA\'S', 'AANTALPAGINA’S', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'PAPIRALEGIMNUMRO', 'AANTALARTIKELEN', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'GEFRENUMRO', 'AANTALBESTANDEN', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'MASYINNUMRO', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ),
	'numberofedits'             => array( '1', 'KENKINUMRO', 'AANTALBEWERKINGEN', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'PAPIRANEN', 'PAGINANAAM', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'PAPIRANENE', 'PAGINANAAME', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'NENPREKI', 'NAAMRUIMTE', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'NENPREKIE', 'NAAMRUIMTEE', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'TAKIPREKI', 'OVERLEGRUIMTE', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'TAKIPREKIE', 'OVERLEGRUIMTEE', 'TALKSPACEE' ),
	'special'                   => array( '0', 'spesyal', 'speciaal', 'special' ),
);

