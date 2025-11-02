<?php
/** Slovak (slovenčina)
 *
 * @file
 * @ingroup Languages
 *
 * @author Chiak
 * @author Danny B.
 * @author Geitost
 * @author Helix84
 * @author Kaganer
 * @author KuboF
 * @author Kusavica
 * @author Liso
 * @author Maros
 * @author Michawiki
 * @author Mormegil
 * @author Nemo bis
 * @author Palica
 * @author Pitr2311
 * @author Ragimiri
 * @author Reedy
 * @author Robertvazan
 * @author Rudko
 * @author Sp5uhe
 * @author Sudo77(new)
 * @author Tchoř
 * @author Teslaton
 * @author Urhixidur
 * @author Valasek
 * @author Wizzard
 * @author Zoranzoki21
 * @author לערי ריינהארט
 */

$fallback = 'cs';

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'AktívniPoužívatelia' ],
	'Allmessages'               => [ 'VšetkySprávy' ],
	'Allpages'                  => [ 'VšetkyStránky' ],
	'Ancientpages'              => [ 'StaréStránky' ],
	'ApiSandbox'                => [ 'APIPieskovisko' ],
	'AutoblockList'             => [ 'AutomatickéBlokovania' ],
	'Blankpage'                 => [ 'PrázdnaStránka' ],
	'Block'                     => [ 'Blokovanie' ],
	'BlockList'                 => [ 'ZoznamBlokovaní' ],
	'Booksources'               => [ 'KnižnéZdroje' ],
	'BotPasswords'              => [ 'HesláPreBotov' ],
	'BrokenRedirects'           => [ 'PokazenéPresmerovania' ],
	'Categories'                => [ 'Kategórie' ],
	'ChangeContentModel'        => [ 'ZmeniťModelObsahuStránky' ],
	'ChangeCredentials'         => [ 'ZmenaPrihlasovacíchÚdajov' ],
	'ChangeEmail'               => [ 'ZmenaEmailu' ],
	'ChangePassword'            => [ 'ZmenaHesla' ],
	'ComparePages'              => [ 'PorovnaťStránky' ],
	'Confirmemail'              => [ 'PotvrdiťEmail' ],
	'Contributions'             => [ 'Príspevky' ],
	'CreateAccount'             => [ 'VytvorenieÚčtu' ],
	'Deadendpages'              => [ 'StránkyBezOdkazov' ],
	'DeletedContributions'      => [ 'ZmazanéPríspevky' ],
	'Diff'                      => [ 'Rozdiel' ],
	'DoubleRedirects'           => [ 'DvojitéPresmerovania' ],
	'EditRecovery'              => [ 'ObnovenieÚpravy' ],
	'Emailuser'                 => [ 'EmailPoužívateľovi' ],
	'ExpandTemplates'           => [ 'SubstituovaťŠablóny' ],
	'Export'                    => [ 'ExportovaťStránky' ],
	'Fewestrevisions'           => [ 'NajmenejRevízií' ],
	'FileDuplicateSearch'       => [ 'HľadanieDuplicitnýchSúborov' ],
	'Filepath'                  => [ 'CestaKSúboru' ],
	'Invalidateemail'           => [ 'ZneplatniťEmail' ],
	'LinkSearch'                => [ 'HľadanieOdkazov' ],
	'Listadmins'                => [ 'ZoznamSprávcov' ],
	'Listbots'                  => [ 'ZoznamBotov' ],
	'ListDuplicatedFiles'       => [ 'ZoznamDuplicitnýchSúborov' ],
	'Listfiles'                 => [ 'ZoznamSúborov' ],
	'Listgrants'                => [ 'SkupinyOprávnení' ],
	'Listgrouprights'           => [ 'ZoznamSkupinovýchPráv' ],
	'Listredirects'             => [ 'ZoznamPresmerovaní' ],
	'Listusers'                 => [ 'ZoznamPoužívateľov' ],
	'Lockdb'                    => [ 'ZamknutieDB' ],
	'Log'                       => [ 'Záznamy' ],
	'Lonelypages'               => [ 'OsirotenéStránky' ],
	'Longpages'                 => [ 'DlhéStránky' ],
	'MediaStatistics'           => [ 'ŠtatistikaSúborov' ],
	'MergeHistory'              => [ 'HistóriaZlúčení' ],
	'MIMEsearch'                => [ 'HľadanieMIME' ],
	'Mostcategories'            => [ 'NajviacKategórií' ],
	'Mostimages'                => [ 'NajodkazovanejšieSúbory' ],
	'Mostinterwikis'            => [ 'NajviacInterwiki' ],
	'Mostlinked'                => [ 'NajodkazovanejšieStránky' ],
	'Mostlinkedcategories'      => [ 'NajodkazovanejšieKategórie' ],
	'Mostlinkedtemplates'       => [ 'NajodkazovanejšieŠablóny' ],
	'Mostrevisions'             => [ 'NajviacRevízií' ],
	'Movepage'                  => [ 'PresunúťStránku' ],
	'Mycontributions'           => [ 'MojePríspevky' ],
	'Mypage'                    => [ 'MojaStránka' ],
	'Mytalk'                    => [ 'MojaDiskusia' ],
	'Newimages'                 => [ 'NovéSúbory' ],
	'Newpages'                  => [ 'NovéStránky' ],
	'PageInfo'                  => [ 'InformácieOStránke' ],
	'PagesWithProp'             => [ 'StránkySVlastnosťou' ],
	'PasswordPolicies'          => [ 'PravidláPreHeslá' ],
	'PasswordReset'             => [ 'ObnovaHesla' ],
	'PermanentLink'             => [ 'TrvalýOdkaz' ],
	'Preferences'               => [ 'Nastavenia' ],
	'Prefixindex'               => [ 'StránkyZačínajúceNa', 'IndexPredpon' ],
	'Protectedpages'            => [ 'ZamknutéStránky' ],
	'Protectedtitles'           => [ 'ZamknutéNázvy' ],
	'RandomInCategory'          => [ 'NáhodnáVKategórii' ],
	'Randompage'                => [ 'Náhodná', 'NáhodnáStránka' ],
	'Randomredirect'            => [ 'NáhodnéPresmerovanie' ],
	'Randomrootpage'            => [ 'NáhodnáKoreňováStránka' ],
	'Recentchanges'             => [ 'PoslednéÚpravy' ],
	'Recentchangeslinked'       => [ 'SúvisiacePoslednéÚpravy' ],
	'Redirect'                  => [ 'Presmerovanie' ],
	'RemoveCredentials'         => [ 'OdstráneniePrihlasovacíchÚdajov' ],
	'Renameuser'                => [ 'PremenovaťPoužívateľa' ],
	'ResetTokens'               => [ 'ObnovaKľúčov' ],
	'Revisiondelete'            => [ 'ZmazaťRevíziu' ],
	'Search'                    => [ 'Hľadanie' ],
	'Shortpages'                => [ 'KrátkeStránky' ],
	'Specialpages'              => [ 'ŠpeciálneStránky' ],
	'Statistics'                => [ 'Štatistika' ],
	'Tags'                      => [ 'Značky' ],
	'TrackingCategories'        => [ 'SledovacieKategórie' ],
	'Unblock'                   => [ 'Odblokovanie' ],
	'Uncategorizedcategories'   => [ 'NekategorizovanéKategórie' ],
	'Uncategorizedimages'       => [ 'NekategorizovanéSúbory' ],
	'Uncategorizedpages'        => [ 'NekategorizovanéStránky' ],
	'Uncategorizedtemplates'    => [ 'NekategorizovanéŠablóny' ],
	'Undelete'                  => [ 'Obnovenie' ],
	'Unlockdb'                  => [ 'OdomknutieDB' ],
	'Unusedcategories'          => [ 'NepoužívanéKategórie' ],
	'Unusedimages'              => [ 'NepoužívanéSúbory' ],
	'Unusedtemplates'           => [ 'NepoužitéŠablóny' ],
	'Unwatchedpages'            => [ 'NesledovanéStránky' ],
	'Upload'                    => [ 'NahranieSúboru' ],
	'Userlogin'                 => [ 'PrihláseniePoužívateľa' ],
	'Userlogout'                => [ 'OdhláseniePoužívateľa' ],
	'Userrights'                => [ 'PrávaPoužívateľa' ],
	'Version'                   => [ 'Verzia' ],
	'Wantedcategories'          => [ 'ŽiadanéKategórie' ],
	'Wantedfiles'               => [ 'ŽiadanéSúbory' ],
	'Wantedpages'               => [ 'ŽiadanéStránky' ],
	'Wantedtemplates'           => [ 'ŽiadanéŠablóny' ],
	'Watchlist'                 => [ 'ZoznamSledovaných' ],
	'Whatlinkshere'             => [ 'ČoOdkazujeSem' ],
	'Withoutinterwiki'          => [ 'BezInterwiki' ],
];

$datePreferences = [
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short dmyt',
	'ISO 8601',
];

$datePreferenceMigrationMap = [
	'default',
	'dmyt',
	'short dmyt',
	'tdmy',
	'short tdmy',
];

$defaultDateFormat = 'dmy';

$dateFormats = [
	/*
	'Default',
	'15. január 2001 16:12',
	'15. jan. 2001 16:12',
	'16:12, 15. január 2001',
	'16:12, 15. jan. 2001',
	'ISO 8601' => '2001-01-15 16:12:34'*/

	'dmy time' => 'H:i',
	'dmy date' => 'j. F Y',
	'dmy both' => 'H:i, j. F Y',

	'dmyt time' => 'H:i',
	'dmyt date' => 'j. F Y',
	'dmyt both' => 'j. F Y H:i',

	'short dmyt time' => 'H:i',
	'short dmyt date' => 'j. M. Y',
	'short dmyt both' => 'j. M. Y H:i',

	'tdmy time' => 'H:i',
	'tdmy date' => 'j. F Y',
	'tdmy both' => 'H:i, j. F Y',

	'short tdmy time' => 'H:i',
	'short tdmy date' => 'j. M. Y',
	'short tdmy both' => 'H:i, j. M. Y'
];

$bookstoreList = [
	'Bibsys' => 'http://ask.bibsys.no/ask/action/result?cmd=&kilde=biblio&fid=isbn&term=$1',
	'BokBerit' => 'http://www.bokberit.no/annet_sted/bocker/$1.html',
	'Bokkilden' => 'http://www.bokkilden.no/ProductDetails.aspx?ProductId=$1',
	'Haugenbok' => 'http://www.haugenbok.no/searchresults.cfm?searchtype=simple&isbn=$1',
	'Akademika' => 'http://www.akademika.no/sok.php?isbn=$1',
	'Gnist' => 'http://www.gnist.no/sok.php?isbn=$1',
	'Amazon.co.uk' => 'https://www.amazon.co.uk/exec/obidos/ISBN=$1',
	'Amazon.de' => 'https://www.amazon.de/exec/obidos/ISBN=$1',
	'Amazon.com' => 'https://www.amazon.com/exec/obidos/ISBN=$1'
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'basepagename'              => [ '1', 'NÁZOVZÁKLADNEJSTRÁNKY', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'NÁZOVZÁKLADNEJSTRÁNKYE', 'BASEPAGENAMEE' ],
	'contentlanguage'           => [ '1', 'JAZYKOBSAHU', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'AKTUÁLNYDEŇ', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'AKTUÁLNYDEŇ2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NÁZOVAKTUÁLNEHODŇA', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'AKTUÁLNAHODINA', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'AKTUÁLNYMESIAC', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonthabbrev'        => [ '1', 'NÁZOVAKTUÁLNEHOMESIACASKRATKA', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'NÁZOVAKTUÁLNEHOMESIACA', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'NÁZOVAKTUÁLNEHOMESIACAGEN', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'AKTUÁLNYČAS', 'CURRENTTIME' ],
	'currentversion'            => [ '1', 'AKTUÁLNAVERZIA', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'AKTUÁLNYTÝŽDEŇ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'AKTUÁLNYROK', 'CURRENTYEAR' ],
	'filepath'                  => [ '0', 'CESTAKSÚBORU:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__VYNÚTIŤOBSAH__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'PLNÝNÁZOVSTRÁNKY', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'PLNÝNÁZOVSTRÁNKYE', 'FULLPAGENAMEE' ],
	'grammar'                   => [ '0', 'GRAMATIKA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__SKRYTÁKATEGÓRIA__', '__SKRYTÁKAT__', '__HIDDENCAT__' ],
	'img_border'                => [ '1', 'okraj', 'border' ],
	'img_center'                => [ '1', 'stred', 'center', 'centre' ],
	'img_framed'                => [ '1', 'rám', 'frame', 'framed', 'enframed' ],
	'img_left'                  => [ '1', 'vľavo', 'left' ],
	'img_none'                  => [ '1', 'žiadny', 'none' ],
	'img_right'                 => [ '1', 'vpravo', 'right' ],
	'img_thumbnail'             => [ '1', 'náhľad', 'náhľadobrázka', 'thumb', 'thumbnail' ],
	'img_width'                 => [ '1', '$1bod', '$1px' ],
	'language'                  => [ '0', '#JAZYK', '#LANGUAGE' ],
	'msg'                       => [ '0', 'SPRÁVA:', 'MSG:' ],
	'namespace'                 => [ '1', 'MENNÝPRIESTOR', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'MENNÝPRIESTORE', 'NAMESPACEE' ],
	'noeditsection'             => [ '0', '__NEUPRAVOVAŤSEKCIE__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__BEZGALÉRIE__', '__NOGALLERY__' ],
	'notoc'                     => [ '0', '__BEZOBSAHU__', '__NOTOC__' ],
	'ns'                        => [ '0', 'MP:', 'NS:' ],
	'numberofadmins'            => [ '1', 'POČETSPRÁVCOV', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'POČETČLÁNKOV', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'POČETÚPRAV', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'POČETSÚBOROV', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'POČETSTRÁNOK', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'POČETPOUŽÍVATEĽOV', 'NUMBEROFUSERS' ],
	'pagename'                  => [ '1', 'NÁZOVSTRÁNKY', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'NÁZOVSTRÁNKYE', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'STRÁNOKVKATEGÓRII', 'STRÁNOKVKAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'VEĽKOSŤSTRÁNKY', 'PAGESIZE' ],
	'redirect'                  => [ '0', '#presmeruj', '#REDIRECT' ],
	'scriptpath'                => [ '0', 'CESTAKUSKRIPTU', 'SCRIPTPATH' ],
	'servername'                => [ '0', 'NÁZOVSERVERA', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'NÁZOVLOKALITY', 'SITENAME' ],
	'subjectpagename'           => [ '1', 'NÁZOVČLÁNKU', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'NÁZOVČLÁNKUE', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'PRIESTORČLÁNKOV', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'PRIESTORČLÁNKOVE', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'NÁZOVPODSTRÁNKY', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'NÁZOVPODSTRÁNKYE', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'NAHR:', 'SUBST:' ],
	'talkpagename'              => [ '1', 'NÁZOVDISKUSNEJSTRÁNKY', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'NÁZOVDISKUSNEJSTRÁNKYE', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'DISKUSNÝPRIESTOR', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'DISKUSNÝPRIESTORE', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__OBSAH__', '__TOC__' ],
];

$namespaceNames = [
	NS_MEDIA            => 'Médiá',
	NS_SPECIAL          => 'Špeciálne',
	NS_TALK             => 'Diskusia',
	NS_USER             => 'Užívateľ',
	NS_USER_TALK        => 'Diskusia_s_užívateľom',
	NS_PROJECT_TALK     => 'Diskusia_k_{{GRAMMAR:datív|$1}}',
	NS_FILE             => 'Súbor',
	NS_FILE_TALK        => 'Diskusia_k_súboru',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Diskusia_k_MediaWiki',
	NS_TEMPLATE         => 'Šablóna',
	NS_TEMPLATE_TALK    => 'Diskusia_k_šablóne',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Diskusia_k_pomoci',
	NS_CATEGORY         => 'Kategória',
	NS_CATEGORY_TALK    => 'Diskusia_ku_kategórii',
];

$namespaceAliases = [
	"Komentár"               => NS_TALK,
	'Redaktor'               => NS_USER,
	'Diskusia_s_redaktorom'  => NS_USER_TALK,
	"Komentár_k_redaktorovi" => NS_USER_TALK,
	"Komentár_k_Wikipédii"   => NS_PROJECT_TALK,
	'Obrázok' => NS_FILE,
	'Diskusia_k_obrázku' => NS_FILE_TALK,
	"Komentár_k_obrázku"     => NS_FILE_TALK,
	"Komentár_k_MediaWiki"   => NS_MEDIAWIKI_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Užívateľ', 'female' => 'Užívateľka' ],
	NS_USER_TALK => [ 'male' => 'Diskusia_s_užívateľom', 'female' => 'Diskusia_s_užívateľkou' ],
];

$separatorTransformTable = [
	',' => "\u{00A0}",
	'.' => ','
];

$linkTrail = '/^([a-záäčďéíľĺňóôŕšťúýž]+)(.*)$/sDu';
