<?php
/** Western Frisian (Frysk)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$datePreferences = array(
	'default',
	'fy normal',
	'ISO 8601',
);

$defaultDateFormat = 'fy normal';

$dateFormats = array(
	'fy normal time' => 'H.i',
	'fy normal date' => 'j M Y',
	'fy normal both' => 'j M Y, H.i',
);

$datePreferenceMigrationMap = array(
	'default',
	'fy normal',
	'fy normal',
	'fy normal',
);

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Brûker' => NS_USER,
	'Brûker_oerlis' => NS_USER_TALK,
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Alle wikiberjochten' ),
	'Allpages'                  => array( 'Alle titels', 'Alle siden' ),
	'Ancientpages'              => array( 'Alde siden', 'Âlde siden', 'Siden dy\'t lang net feroare binne' ),
	'Block'                     => array( 'Slút brûker út', 'Slút meidogger út' ),
	'Booksources'               => array( 'Boekynformaasje' ),
	'BrokenRedirects'           => array( 'Misse trochferwizings', 'Missetrochferwizings' ),
	'Categories'                => array( 'Kategoryen', 'Rubriken' ),
	'Confirmemail'              => array( 'Befêstigjen netpostadres' ),
	'Contributions'             => array( 'Meidogger-bydragen', 'Meidogger bydragen', 'Bydragen', 'Brûker bydragen' ),
	'CreateAccount'             => array( 'Nije ynstellings oanmeitsje', 'Nijeynstellingsoanmeitsje' ),
	'Deadendpages'              => array( 'Siden sûnder keppelings', 'Siden sûnder ferwizings', 'Siden sûnder links' ),
	'DoubleRedirects'           => array( 'Dûbele trochferwizings', 'Dûbeletrochferwizings' ),
	'Emailuser'                 => array( 'Skriuw meidogger', 'Skriuw dizze brûker', 'Skriuw dizze meidogger' ),
	'Export'                    => array( 'Eksportearje' ),
	'Fewestrevisions'           => array( 'Siden mei de minste bewurkings', 'Siden mei de minste ferzjes', 'Siden mei de minste wizigings' ),
	'Filepath'                  => array( 'Triempad' ),
	'Import'                    => array( 'Ymport' ),
	'BlockList'                 => array( 'List fan útsletten Ynternet-adressen en brûkersnammen', 'List fan útsletten ynternet-adressen en meidochnammen', 'Útslette brûkers', 'Utslette brûkers', 'Útsletten meidoggers', 'Utsletten meidoggers' ),
	'Listadmins'                => array( 'Meidoggerlist Behearders' ),
	'Listbots'                  => array( 'Meidoggerlist Bots' ),
	'Listfiles'                 => array( 'Ofbyld list', 'Ofbyldlist' ),
	'Listredirects'             => array( 'List fan trochferwizings' ),
	'Listusers'                 => array( 'Meidoggerlist', 'Brûkerlist' ),
	'Lockdb'                    => array( 'Meitsje de database \'Net-skriuwe\'', 'Meitsje de databank \'Net-skriuwe\'' ),
	'Log'                       => array( 'Loch', 'Logboek', 'Logboeken', 'Lochs' ),
	'Lonelypages'               => array( 'Lossteande siden' ),
	'Longpages'                 => array( 'Lange siden' ),
	'MIMEsearch'                => array( 'Sykje op MIME-type' ),
	'Mostcategories'            => array( 'Siden mei de measte rubriken', 'Siden mei de measte kategoryen' ),
	'Mostimages'                => array( 'Ofbylden dy\'t it meast brûkt wurde', 'Meast brûkte ôfbyldings' ),
	'Mostlinked'                => array( 'Siden wêr it meast mei keppele is', 'Siden dêr\'t it meast nei ferwiisd wurdt' ),
	'Mostlinkedcategories'      => array( 'Kategoryen dy\'t it meast brûkt wurde', 'Kategoryen dêr\'t it meast nei ferwiisd wurdt' ),
	'Mostlinkedtemplates'       => array( 'Meast brûkte sjabloanen', 'Meast brûkte berjochten' ),
	'Mostrevisions'             => array( 'Siden mei de measte wizigings', 'Siden mei de measte bewurkings' ),
	'Movepage'                  => array( 'Werneam side' ),
	'Mycontributions'           => array( 'Myn bydragen' ),
	'Mypage'                    => array( 'Myn side' ),
	'Mytalk'                    => array( 'Myn oerlis' ),
	'Newimages'                 => array( 'Nije ôfbylden', 'Nije ôfbyldings', 'Nije ôfbyldingen', 'List mei nije ôfbylden', 'Nije Ofbylden' ),
	'Newpages'                  => array( 'Nije siden' ),

	'Preferences'               => array( 'Ynstellings', 'Ynsteld' ),
	'Prefixindex'               => array( 'Alle siden neffens foarheaksel' ),
	'Protectedpages'            => array( 'Befeilige siden', 'Skoattele siden' ),
	'Randompage'                => array( 'Samar in side' ),
	'Randomredirect'            => array( 'Samar in trochferwizing' ),
	'Recentchanges'             => array( 'Koartlyn feroare', 'Koarts feroare' ),
	'Recentchangeslinked'       => array( 'Folgje keppelings' ),
	'Search'                    => array( 'Sykje' ),
	'Shortpages'                => array( 'Koarte siden' ),
	'Specialpages'              => array( 'Bysûndere siden' ),
	'Statistics'                => array( 'Statistyk' ),
	'Uncategorizedcategories'   => array( 'Kategoryen sûnder kategory', 'Rubriken sûnder rubryk', 'Net-kategorisearre kategoryen' ),
	'Uncategorizedimages'       => array( 'Net-kategorisearre ôfbyldings', 'Ofbylden sûnder kategory', 'Ofbylden sûnder rubryk' ),
	'Uncategorizedpages'        => array( 'Siden sûnder rubryk', 'Siden sûnder kategory', 'Net-kategorisearre siden' ),
	'Uncategorizedtemplates'    => array( 'Net-kategorisearre sjabloanen', 'Net-kategorisearre berjochten', 'Berjochten sûnder rubryk', 'Berjochten sûnder kategory' ),
	'Undelete'                  => array( 'Side werom set' ),
	'Unlockdb'                  => array( 'Meitsje de databank skriuwber' ),
	'Unusedcategories'          => array( 'Net-brûkte kategoryen', 'Lege kategoryen' ),
	'Unusedimages'              => array( 'Lossteande ôfbylden' ),
	'Unusedtemplates'           => array( 'Net brûkte sjabloanen', 'Net brûkte berjochten' ),
	'Unwatchedpages'            => array( 'Siden dy\'t net op in folchlist steane' ),
	'Upload'                    => array( 'Bied triem oan', 'Oanbied', 'Bied bestân oan' ),
	'Userlogin'                 => array( 'Oanmelde', 'Oanmeld' ),
	'Userlogout'                => array( 'Ofmelde', 'Ofmeld', 'Ôfmelde', 'Ôfmeld' ),
	'Userrights'                => array( 'Meidoggerrjochten', 'Behear fan meidoggerrjochten' ),
	'Version'                   => array( 'Ferzje', 'Programmatuerferzje' ),
	'Wantedcategories'          => array( 'Nedige kategoryen', 'Net-besteande kategoryen dêr\'t it meast nei ferwiisd wurdt' ),
	'Wantedpages'               => array( 'Nedige siden' ),
	'Watchlist'                 => array( 'Folchlist', 'Jo Folchlist' ),
	'Whatlinkshere'             => array( 'Wat is hjirmei keppele', 'Wat is hjirmei keppele?', 'List fan alle siden dy\'t nei dizze side ferwize' ),
	'Withoutinterwiki'          => array( 'Siden sûnder links nei oare talen', 'Siden sûnder ferwizings nei oare talen', 'Siden sûnder keppelings nei oare talen' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkTrail = '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';

