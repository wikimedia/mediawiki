<?php
/** Western Frisian (Frysk)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$datePreferences = [
	'default',
	'fy normal',
	'ISO 8601',
];

$defaultDateFormat = 'fy normal';

$dateFormats = [
	'fy normal time' => 'H.i',
	'fy normal date' => 'j M Y',
	'fy normal both' => 'j M Y, H.i',
];

$datePreferenceMigrationMap = [
	'default',
	'fy normal',
	'fy normal',
	'fy normal',
];

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Wiki',
	NS_TALK             => 'Oerlis',
	NS_USER             => 'Meidogger',
	NS_USER_TALK        => 'Meidogger_oerlis',
	NS_PROJECT_TALK     => '$1_oerlis',
	NS_FILE             => 'Ofbyld',
	NS_FILE_TALK        => 'Ofbyld_oerlis',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_oerlis',
	NS_TEMPLATE         => 'Berjocht',
	NS_TEMPLATE_TALK    => 'Berjocht_oerlis',
	NS_HELP             => 'Hulp',
	NS_HELP_TALK        => 'Hulp_oerlis',
	NS_CATEGORY         => 'Kategory',
	NS_CATEGORY_TALK    => 'Kategory_oerlis',
];

$namespaceAliases = [
	'Brûker' => NS_USER,
	'Brûker_oerlis' => NS_USER_TALK,
];

$specialPageAliases = [
	'Allmessages'               => [ 'Alle wikiberjochten' ],
	'Allpages'                  => [ 'Alle titels', 'Alle siden' ],
	'Ancientpages'              => [ 'Alde siden', 'Âlde siden', 'Siden dy\'t lang net feroare binne' ],
	'Block'                     => [ 'Slút brûker út', 'Slút meidogger út' ],
	'Booksources'               => [ 'Boekynformaasje' ],
	'BrokenRedirects'           => [ 'Misse trochferwizings', 'Missetrochferwizings' ],
	'Categories'                => [ 'Kategoryen', 'Rubriken' ],
	'Confirmemail'              => [ 'Befêstigjen netpostadres' ],
	'Contributions'             => [ 'Meidogger-bydragen', 'Meidogger bydragen', 'Bydragen', 'Brûker bydragen' ],
	'CreateAccount'             => [ 'Nije ynstellings oanmeitsje', 'Nijeynstellingsoanmeitsje' ],
	'Deadendpages'              => [ 'Siden sûnder keppelings', 'Siden sûnder ferwizings', 'Siden sûnder links' ],
	'DoubleRedirects'           => [ 'Dûbele trochferwizings', 'Dûbeletrochferwizings' ],
	'Emailuser'                 => [ 'Skriuw meidogger', 'Skriuw dizze brûker', 'Skriuw dizze meidogger' ],
	'Export'                    => [ 'Eksportearje' ],
	'Fewestrevisions'           => [ 'Siden mei de minste bewurkings', 'Siden mei de minste ferzjes', 'Siden mei de minste wizigings' ],
	'Filepath'                  => [ 'Triempad' ],
	'Import'                    => [ 'Ymport' ],
	'BlockList'                 => [ 'List fan útsletten Ynternet-adressen en brûkersnammen', 'List fan útsletten ynternet-adressen en meidochnammen', 'Útslette brûkers', 'Utslette brûkers', 'Útsletten meidoggers', 'Utsletten meidoggers' ],
	'Listadmins'                => [ 'Meidoggerlist Behearders' ],
	'Listbots'                  => [ 'Meidoggerlist Bots' ],
	'Listfiles'                 => [ 'Ofbyld list', 'Ofbyldlist' ],
	'Listredirects'             => [ 'List fan trochferwizings' ],
	'Listusers'                 => [ 'Meidoggerlist', 'Brûkerlist' ],
	'Lockdb'                    => [ 'Meitsje de database \'Net-skriuwe\'', 'Meitsje de databank \'Net-skriuwe\'' ],
	'Log'                       => [ 'Loch', 'Logboek', 'Logboeken', 'Lochs' ],
	'Lonelypages'               => [ 'Lossteande siden' ],
	'Longpages'                 => [ 'Lange siden' ],
	'MIMEsearch'                => [ 'Sykje op MIME-type' ],
	'Mostcategories'            => [ 'Siden mei de measte rubriken', 'Siden mei de measte kategoryen' ],
	'Mostimages'                => [ 'Ofbylden dy\'t it meast brûkt wurde', 'Meast brûkte ôfbyldings' ],
	'Mostlinked'                => [ 'Siden wêr it meast mei keppele is', 'Siden dêr\'t it meast nei ferwiisd wurdt' ],
	'Mostlinkedcategories'      => [ 'Kategoryen dy\'t it meast brûkt wurde', 'Kategoryen dêr\'t it meast nei ferwiisd wurdt' ],
	'Mostlinkedtemplates'       => [ 'Meast brûkte sjabloanen', 'Meast brûkte berjochten' ],
	'Mostrevisions'             => [ 'Siden mei de measte wizigings', 'Siden mei de measte bewurkings' ],
	'Movepage'                  => [ 'Werneam side' ],
	'Mycontributions'           => [ 'Myn bydragen' ],
	'Mypage'                    => [ 'Myn side' ],
	'Mytalk'                    => [ 'Myn oerlis' ],
	'Newimages'                 => [ 'Nije ôfbylden', 'Nije ôfbyldings', 'Nije ôfbyldingen', 'List mei nije ôfbylden', 'Nije Ofbylden' ],
	'Newpages'                  => [ 'Nije siden' ],
	'Preferences'               => [ 'Ynstellings', 'Ynsteld' ],
	'Prefixindex'               => [ 'Alle siden neffens foarheaksel' ],
	'Protectedpages'            => [ 'Befeilige siden', 'Skoattele siden' ],
	'Randompage'                => [ 'Samar in side' ],
	'Randomredirect'            => [ 'Samar in trochferwizing' ],
	'Recentchanges'             => [ 'Koartlyn feroare', 'Koarts feroare' ],
	'Recentchangeslinked'       => [ 'Folgje keppelings' ],
	'Search'                    => [ 'Sykje' ],
	'Shortpages'                => [ 'Koarte siden' ],
	'Specialpages'              => [ 'Bysûndere siden' ],
	'Statistics'                => [ 'Statistyk' ],
	'Uncategorizedcategories'   => [ 'Kategoryen sûnder kategory', 'Rubriken sûnder rubryk', 'Net-kategorisearre kategoryen' ],
	'Uncategorizedimages'       => [ 'Net-kategorisearre ôfbyldings', 'Ofbylden sûnder kategory', 'Ofbylden sûnder rubryk' ],
	'Uncategorizedpages'        => [ 'Siden sûnder rubryk', 'Siden sûnder kategory', 'Net-kategorisearre siden' ],
	'Uncategorizedtemplates'    => [ 'Net-kategorisearre sjabloanen', 'Net-kategorisearre berjochten', 'Berjochten sûnder rubryk', 'Berjochten sûnder kategory' ],
	'Undelete'                  => [ 'Side werom set' ],
	'Unlockdb'                  => [ 'Meitsje de databank skriuwber' ],
	'Unusedcategories'          => [ 'Net-brûkte kategoryen', 'Lege kategoryen' ],
	'Unusedimages'              => [ 'Lossteande ôfbylden' ],
	'Unusedtemplates'           => [ 'Net brûkte sjabloanen', 'Net brûkte berjochten' ],
	'Unwatchedpages'            => [ 'Siden dy\'t net op in folchlist steane' ],
	'Upload'                    => [ 'Bied triem oan', 'Oanbied', 'Bied bestân oan' ],
	'Userlogin'                 => [ 'Oanmelde', 'Oanmeld' ],
	'Userlogout'                => [ 'Ofmelde', 'Ofmeld', 'Ôfmelde', 'Ôfmeld' ],
	'Userrights'                => [ 'Meidoggerrjochten', 'Behear fan meidoggerrjochten' ],
	'Version'                   => [ 'Ferzje', 'Programmatuerferzje' ],
	'Wantedcategories'          => [ 'Nedige kategoryen', 'Net-besteande kategoryen dêr\'t it meast nei ferwiisd wurdt' ],
	'Wantedpages'               => [ 'Nedige siden' ],
	'Watchlist'                 => [ 'Folchlist', 'Jo Folchlist' ],
	'Whatlinkshere'             => [ 'Wat is hjirmei keppele', 'Wat is hjirmei keppele?', 'List fan alle siden dy\'t nei dizze side ferwize' ],
	'Withoutinterwiki'          => [ 'Siden sûnder links nei oare talen', 'Siden sûnder ferwizings nei oare talen', 'Siden sûnder keppelings nei oare talen' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';
