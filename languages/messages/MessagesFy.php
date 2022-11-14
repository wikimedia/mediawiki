<?php
/** Western Frisian (Frysk)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Alle_wikiberjochten' ],
	'Allpages'                  => [ 'Alle_titels', 'Alle_siden' ],
	'Ancientpages'              => [ 'Alde_siden', 'Âlde_siden', 'Siden_dy\'t_lang_net_feroare_binne' ],
	'Block'                     => [ 'Slút_brûker_út', 'Slút_meidogger_út' ],
	'BlockList'                 => [ 'List_fan_útsletten_Ynternet-adressen_en_brûkersnammen', 'List_fan_útsletten_ynternet-adressen_en_meidochnammen', 'Útslette_brûkers', 'Utslette_brûkers', 'Útsletten_meidoggers', 'Utsletten_meidoggers' ],
	'Booksources'               => [ 'Boekynformaasje' ],
	'BrokenRedirects'           => [ 'Misse_trochferwizings', 'Missetrochferwizings' ],
	'Categories'                => [ 'Kategoryen', 'Rubriken' ],
	'Confirmemail'              => [ 'Befêstigjen_netpostadres' ],
	'Contributions'             => [ 'Meidogger-bydragen', 'Meidogger_bydragen', 'Bydragen', 'Brûker_bydragen' ],
	'CreateAccount'             => [ 'Nije_ynstellings_oanmeitsje', 'Nijeynstellingsoanmeitsje' ],
	'Deadendpages'              => [ 'Siden_sûnder_keppelings', 'Siden_sûnder_ferwizings', 'Siden_sûnder_links' ],
	'DoubleRedirects'           => [ 'Dûbele_trochferwizings', 'Dûbeletrochferwizings' ],
	'Emailuser'                 => [ 'Skriuw_meidogger', 'Skriuw_dizze_brûker', 'Skriuw_dizze_meidogger' ],
	'Export'                    => [ 'Eksportearje' ],
	'Fewestrevisions'           => [ 'Siden_mei_de_minste_bewurkings', 'Siden_mei_de_minste_ferzjes', 'Siden_mei_de_minste_wizigings' ],
	'Filepath'                  => [ 'Triempad' ],
	'Import'                    => [ 'Ymport' ],
	'Listadmins'                => [ 'Meidoggerlist_Behearders' ],
	'Listbots'                  => [ 'Meidoggerlist_Bots' ],
	'Listfiles'                 => [ 'Ofbyld_list', 'Ofbyldlist' ],
	'Listredirects'             => [ 'List_fan_trochferwizings' ],
	'Listusers'                 => [ 'Meidoggerlist', 'Brûkerlist' ],
	'Lockdb'                    => [ 'Meitsje_de_database_\'Net-skriuwe\'', 'Meitsje_de_databank_\'Net-skriuwe\'' ],
	'Log'                       => [ 'Loch', 'Logboek', 'Logboeken', 'Lochs' ],
	'Lonelypages'               => [ 'Lossteande_siden' ],
	'Longpages'                 => [ 'Lange_siden' ],
	'MIMEsearch'                => [ 'Sykje_op_MIME-type' ],
	'Mostcategories'            => [ 'Siden_mei_de_measte_rubriken', 'Siden_mei_de_measte_kategoryen' ],
	'Mostimages'                => [ 'Ofbylden_dy\'t_it_meast_brûkt_wurde', 'Meast_brûkte_ôfbyldings' ],
	'Mostlinked'                => [ 'Siden_wêr_it_meast_mei_keppele_is', 'Siden_dêr\'t_it_meast_nei_ferwiisd_wurdt' ],
	'Mostlinkedcategories'      => [ 'Kategoryen_dy\'t_it_meast_brûkt_wurde', 'Kategoryen_dêr\'t_it_meast_nei_ferwiisd_wurdt' ],
	'Mostlinkedtemplates'       => [ 'Meast_brûkte_sjabloanen', 'Meast_brûkte_berjochten' ],
	'Mostrevisions'             => [ 'Siden_mei_de_measte_wizigings', 'Siden_mei_de_measte_bewurkings' ],
	'Movepage'                  => [ 'Werneam_side' ],
	'Mycontributions'           => [ 'Myn_bydragen' ],
	'Mypage'                    => [ 'Myn_side' ],
	'Mytalk'                    => [ 'Myn_oerlis' ],
	'Newimages'                 => [ 'Nije_ôfbylden', 'Nije_ôfbyldings', 'Nije_ôfbyldingen', 'List_mei_nije_ôfbylden', 'Nije_Ofbylden' ],
	'Newpages'                  => [ 'Nije_siden' ],
	'Preferences'               => [ 'Ynstellings', 'Ynsteld' ],
	'Prefixindex'               => [ 'Alle_siden_neffens_foarheaksel' ],
	'Protectedpages'            => [ 'Befeilige_siden', 'Skoattele_siden' ],
	'Randompage'                => [ 'Samar_in_side' ],
	'Randomredirect'            => [ 'Samar_in_trochferwizing' ],
	'Recentchanges'             => [ 'Koartlyn_feroare', 'Koarts_feroare' ],
	'Recentchangeslinked'       => [ 'Folgje_keppelings' ],
	'Search'                    => [ 'Sykje' ],
	'Shortpages'                => [ 'Koarte_siden' ],
	'Specialpages'              => [ 'Bysûndere_siden' ],
	'Statistics'                => [ 'Statistyk' ],
	'Uncategorizedcategories'   => [ 'Kategoryen_sûnder_kategory', 'Rubriken_sûnder_rubryk', 'Net-kategorisearre_kategoryen' ],
	'Uncategorizedimages'       => [ 'Net-kategorisearre_ôfbyldings', 'Ofbylden_sûnder_kategory', 'Ofbylden_sûnder_rubryk' ],
	'Uncategorizedpages'        => [ 'Siden_sûnder_rubryk', 'Siden_sûnder_kategory', 'Net-kategorisearre_siden' ],
	'Uncategorizedtemplates'    => [ 'Net-kategorisearre_sjabloanen', 'Net-kategorisearre_berjochten', 'Berjochten_sûnder_rubryk', 'Berjochten_sûnder_kategory' ],
	'Undelete'                  => [ 'Side_werom_set' ],
	'Unlockdb'                  => [ 'Meitsje_de_databank_skriuwber' ],
	'Unusedcategories'          => [ 'Net-brûkte_kategoryen', 'Lege_kategoryen' ],
	'Unusedimages'              => [ 'Lossteande_ôfbylden' ],
	'Unusedtemplates'           => [ 'Net_brûkte_sjabloanen', 'Net_brûkte_berjochten' ],
	'Unwatchedpages'            => [ 'Siden_dy\'t_net_op_in_folchlist_steane' ],
	'Upload'                    => [ 'Bied_triem_oan', 'Oanbied', 'Bied_bestân_oan' ],
	'Userlogin'                 => [ 'Oanmelde', 'Oanmeld' ],
	'Userlogout'                => [ 'Ofmelde', 'Ofmeld', 'Ôfmelde', 'Ôfmeld' ],
	'Userrights'                => [ 'Meidoggerrjochten', 'Behear_fan_meidoggerrjochten' ],
	'Version'                   => [ 'Ferzje', 'Programmatuerferzje' ],
	'Wantedcategories'          => [ 'Nedige_kategoryen', 'Net-besteande_kategoryen_dêr\'t_it_meast_nei_ferwiisd_wurdt' ],
	'Wantedpages'               => [ 'Nedige_siden' ],
	'Watchlist'                 => [ 'Folchlist', 'Jo_Folchlist' ],
	'Whatlinkshere'             => [ 'Wat_is_hjirmei_keppele', 'Wat_is_hjirmei_keppele?', 'List_fan_alle_siden_dy\'t_nei_dizze_side_ferwize' ],
	'Withoutinterwiki'          => [ 'Siden_sûnder_links_nei_oare_talen', 'Siden_sûnder_ferwizings_nei_oare_talen', 'Siden_sûnder_keppelings_nei_oare_talen' ],
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zàáèéìíòóùúâêîôûäëïöü]+)(.*)$/sDu';
