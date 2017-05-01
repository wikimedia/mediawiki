<?php
/** Sranan Tongo (Sranantongo)
 *
 * To improve a translation please visit https://translatewiki.net
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

$namespaceNames = [
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
];

$namespaceAliases = [
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
];

$specialPageAliases = [
	'Allmessages'               => [ 'Boskopu' ],
	'Allpages'                  => [ 'AlaPeprewoysi' ],
	'Ancientpages'              => [ 'PasaOwruPeprewoysi' ],
	'BrokenRedirects'           => [ 'BrokoStirpeprewoysi' ],
	'Categories'                => [ 'Guru' ],
	'Contributions'             => [ 'Kenki' ],
	'Deadendpages'              => [ 'NoSkakiPeprewoysi' ],
	'DoubleRedirects'           => [ 'Tustirpeprewoysi' ],
	'Emailuser'                 => [ 'EmailMasyin' ],
	'Fewestrevisions'           => [ 'MenaKenki' ],
	'Listadmins'                => [ 'Sesopurey' ],
	'Listbots'                  => [ 'Botrey' ],
	'Listfiles'                 => [ 'Gefrerey' ],
	'Listredirects'             => [ 'Stirpeprewoysirey' ],
	'Listusers'                 => [ 'Masyinrey' ],
	'Log'                       => [ 'Buku' ],
	'Lonelypages'               => [ 'WawanPeprewoysi' ],
	'Longpages'                 => [ 'LangaPeprewoysi' ],
	'MIMEsearch'                => [ 'MIMEsuku' ],
	'Mostcategories'            => [ 'PasaGuru' ],
	'Mostimages'                => [ 'PasaGefre' ],
	'Mostlinked'                => [ 'PasatekiPeprewoysi' ],
	'Mostlinkedcategories'      => [ 'PasatekiGuru' ],
	'Mostlinkedtemplates'       => [ 'PasatekiAnkra' ],
	'Mostrevisions'             => [ 'PasaKenki' ],
	'Movepage'                  => [ 'PapiraDribi' ],
	'Mycontributions'           => [ 'MiKenki' ],
	'Mypage'                    => [ 'MiPapira' ],
	'Mytalk'                    => [ 'MiTaki' ],
	'Newimages'                 => [ 'NyunGefre' ],
	'Newpages'                  => [ 'NyunPeprewoysi' ],
	'Protectedpages'            => [ 'TapuPeprewoysi' ],
	'Randompage'                => [ 'SomaPapira' ],
	'Randomredirect'            => [ 'SomaStirpapira' ],
	'Recentchanges'             => [ 'BakaseywanKenki' ],
	'Search'                    => [ 'Suku' ],
	'Shortpages'                => [ 'SyartuPeprewoysi' ],
	'Specialpages'              => [ 'SpesyalPeprewoysi' ],
	'Uncategorizedcategories'   => [ 'OguruGuru' ],
	'Uncategorizedimages'       => [ 'OguruGefre' ],
	'Uncategorizedpages'        => [ 'OguruPeprewoysi' ],
	'Uncategorizedtemplates'    => [ 'OguruAnkra' ],
	'Undelete'                  => [ 'Otrowe' ],
	'Unusedcategories'          => [ 'OtekiGuru' ],
	'Unusedimages'              => [ 'OtekiGefre' ],
	'Unusedtemplates'           => [ 'OtekiAnkra' ],
	'Upload'                    => [ 'Uploti' ],
	'Userlogin'                 => [ 'Kon' ],
	'Userlogout'                => [ 'Gwe' ],
	'Userrights'                => [ 'Masyinlesi' ],
	'Version'                   => [ 'Si' ],
	'Wantedcategories'          => [ 'WinsiGuru' ],
	'Wantedpages'               => [ 'WinsiPeprewoysi' ],
	'Watchlist'                 => [ 'Sirey' ],
	'Withoutinterwiki'          => [ 'NoInterwiki' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#STIR', '#DOORVERWIJZING', '#REDIRECT' ],
	'notoc'                     => [ '0', '__NOINOT__', '__GEENINHOUD__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__NOPIKTURAMA__', '__GEEN_GALERIJ__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__INOTDWENGI__', '__INHOUD_DWINGEN__', '__FORCEERINHOUD__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__INOT__', '__INHOUD__', '__TOC__' ],
	'noeditsection'             => [ '0', '__NOKENKISKAKI__', '__NIETBEWERKBARESECTIE__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'CURRENTMOTNH', 'DISIMUN', 'HUIDIGEMAAND', 'HUIDIGEMAAND2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthname'          => [ '1', 'DISIMUNNEN', 'HUIDIGEMAANDNAAM', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'DISIMUNNENGEN', 'HUIDIGEMAANDGEN', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'DISIMUNSH', 'HUIDIGEMAANDAFK', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'DISIDEY', 'HUIDIGEDAG', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DISIDEY2', 'HUIDIGEDAG2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'DISIDEYNEN', 'HUIDIGEDAGNAAM', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'DISIYARI', 'HUIDIGJAAR', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'DISITEN', 'HUIDIGETIJD', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'DISIYURU', 'HUIDIGUUR', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'PRESIMUN', 'PLAATSELIJKEMAAND', 'LOKALEMAAND', 'LOKALEMAAND2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'PRESIMUNNEN', 'PLAATSELIJKEMAANDNAAM', 'LOKALEMAANDNAAM', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'PRESIMUNNENGEN', 'PLAATSELIJKEMAANDNAAMGEN', 'LOKALEMAANDNAAMGEN', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'PRESIMUNSH', 'PLAATSELIJKEMAANDAFK', 'LOKALEMAANDAFK', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'PRESIDEY', 'PLAATSELIJKEDAG', 'LOKALEDAG', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'PRESIDEY2', 'PLAATSELIJKEDAG2', 'LOKALEDAG2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'PRESIDEYNEN', 'PLAATSELIJKEDAGNAAM', 'LOKALEDAGNAAM', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'PRESIYARI', 'PLAATSELIJKJAAR', 'LOKAALJAAR', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'PRESITEN', 'PLAATSELIJKETIJD', 'LOKALETIJD', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'PRESIYURU', 'PLAATSELIJKUUR', 'LOKAALUUR', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'PAPIRANUMRO', 'AANTALPAGINAS', 'AANTALPAGINA\'S', 'AANTALPAGINA’S', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'PAPIRALEGIMNUMRO', 'AANTALARTIKELEN', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'GEFRENUMRO', 'AANTALBESTANDEN', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'MASYINNUMRO', 'AANTALGEBRUIKERS', 'NUMBEROFUSERS' ],
	'numberofedits'             => [ '1', 'KENKINUMRO', 'AANTALBEWERKINGEN', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'PAPIRANEN', 'PAGINANAAM', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'PAPIRANENE', 'PAGINANAAME', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'NENPREKI', 'NAAMRUIMTE', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'NENPREKIE', 'NAAMRUIMTEE', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'TAKIPREKI', 'OVERLEGRUIMTE', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'TAKIPREKIE', 'OVERLEGRUIMTEE', 'TALKSPACEE' ],
	'special'                   => [ '0', 'spesyal', 'speciaal', 'special' ],
];

