<?php
/** Toki Pona (toki pona)
 *
 * @ingroup Language
 * @file
 *
 * @author tbodt
 * @author TamzinHadasa
 */

$namespaceNames = [
	NS_SPECIAL          => 'ilo',
	NS_TALK             => 'toki',
	NS_USER             => 'jan',
	NS_USER_TALK        => 'jan_la_toki',
	NS_PROJECT_TALK     => '$1_la_toki',
	NS_FILE             => 'sitelen',
	NS_FILE_TALK        => 'sitelen_la_toki',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_la_toki',
	NS_TEMPLATE         => 'kipisi',
	NS_TEMPLATE_TALK    => 'kipisi_la_toki',
	NS_HELP             => 'nasin_ilo',
	NS_HELP_TALK        => 'nasin_ilo_la_toki',
	NS_CATEGORY         => 'kulupu',
	NS_CATEGORY_TALK    => 'kulupu_la_toki',
];

$namespaceAliases = [
	# ijo => sitelen
	'ijo' => NS_FILE,
	'toki_ijo' => NS_FILE_TALK,

	# toki_$1 => $1_la_toki
	'toki_jan' => NS_USER_TALK,
	'toki_sitelen' => NS_FILE_TALK,
	'toki_MediaWiki' => NS_MEDIAWIKI_TALK,
	'toki_kipisi' => NS_TEMPLATE_TALK,
	'toki_pi_nasin_ilo' => NS_HELP_TALK,
	'toki_kulupu' => NS_CATEGORY_TALK,
];

$datePreferences = [
	'ISO 8601',
];
$defaultDateFormat = 'ISO 8601';

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers' => [ 'jan_ni_li_pali_lon_tenpo_poka', 'jan_pali_pi_tenpo_poka', 'jan_pali_lon' ],
	'Allmessages' => [ 'toki_ale_pi_selo_ilo', 'toki_ilo_ale' ],
	'Allpages' => [ 'lipu_ale' ],
	'ApiSandbox' => [ 'ilo_API_la_ilo_pi_kama_sona', 'lipu_ken_API' ],
	'AutoblockList' => [ 'ilo_lawa_li_weka_e_ken_pali_tan_jan_ni', 'pakala_ilo' ],
	'BlockList' => [ 'lawa_li_weka_e_ken_pali_tan_jan_ni', 'jan_pakala' ],
	'Booksources' => [ 'o_alasa_e_lipu_kepeken_nanpa_esun', 'tan_lipu' ],
	'BotPasswords' => [ 'nanpa_len_la_ilo_li_kepeken_sijelo', 'nimi_nanpa_ilo' ],
	'BrokenRedirects' => [ 'lipu_tawa_pakala' ],
	'Categories' => [ 'kulupu_lipu' ],
	'ChangeCredentials' => [ 'o_ante_e_nasin_pi_kama_sijelo', 'ante_pi_nasin_awen' ],
	'ComparePages' => [ 'lipu_tu_la_ante', 'ante_lipu' ],
	'Contributions' => [ 'pali_jan', 'ante' ],
	'CreateAccount' => [ 'o_pali_e_sijelo', 'sijelo_sin' ],
	'Deadendpages' => [ 'lipu_ni_la_linja_ala_li_tan_ona', 'linja_ala' ],
	'Diff' => [ 'lipu_la_tenpo_tu_la_ante', 'ante_ante' ],
	'DoubleRedirects' => [ 'lipu_tawa_ni_li_tawa_lipu_tawa_ante', 'lipu_tawa_nasa' ],
	'EditPage' => [ 'o_ante_e_lipu', 'ante_lipu' ],
	'ExpandTemplates' => [ 'toki_ilo_li_kama_lipu', 'suli_kipisi' ],
	'Export' => [ 'o_pana_e_lipu_mute_tawa_ilo_sina', 'pana' ],
	'Fewestrevisions' => [ 'lipu_ni_li_kama_ante_pi_mute_lili', 'ante_pi_mute_lili' ],
	'FileDuplicateSearch' => [ 'o_alasa_e_sitelen_pi_sama_ale', 'alasa_pi_ijo_ike' ],
	'Import' => [ 'o_kama_e_lipu_mute_tan_ilo_sina' ],
	'LinkSearch' => [ 'o_alasa_e_linja_tawa_lipu_weka' ],
	'ListDuplicatedFiles' => [ 'sitelen_pi_sama_ale', 'ijo_ike' ],
	'Listfiles' => [ 'sitelen_ale', 'ijo_ale' ],
	'Listgrouprights' => [ 'kulupu_ale_jan_en_ken_ona', 'ken_kulupu' ],
	'Listredirects' => [ 'lipu_tawa_ale' ],
	'Listusers' => [ 'sijelo_ale' ],
	'Log' => [ 'pali_namako', 'pali' ],
	'Lonelypages' => [ 'lipu_ni_la_linja_ala_li_tawa_ona', 'lipu_weka' ],
	'Longpages' => [ 'lipu_ni_li_suli', 'lipu_suli' ],
	'MediaStatistics' => [ 'sitelen_seme_li_lon', 'sona_ijo' ],
	'MIMEsearch' => [ 'sitelen_ale_pi_nimi_MIME_wan', 'alasa_pi_nasin_MIME' ],
	'Mostcategories' => [ 'kulupu_ni_la_lipu_mute_li_lon', 'kulupu_mute' ],
	'Mostimages' => [ 'sitelen_ni_la_lipu_mute_li_kepeken', 'ijo_suli' ],
	'Mostinterwikis' => [ 'lipu_ni_la_linja_mute_li_tawa_lipu_pi_toki_ante', 'linja_mute_tawa_lipu_ante' ],
	'Mostlinked' => [ 'lipu_ni_la_linja_mute_li_tawa_ona', 'lipu_suli' ],
	'Mostlinkedcategories' => [ 'lipu_mute_li_toki_e_kulupu_lipu_ni', 'kulupu_suli' ],
	'Mostlinkedtemplates' => [ 'kipisi_ni_la_lipu_mute_li_kepeken', 'sama_mute' ],
	'Mostrevisions' => [ 'lipu_ni_li_kama_ante_mute', 'ante_mute' ],
	'Newimages' => [ 'sitelen_ni_li_sin', 'ijo_sin' ],
	'Newpages' => [ 'lipu_ni_li_sin', 'lipu_sin' ],
	'PageHistory' => [ 'lipu_la_tenpo_pini', 'pini_lipu' ],
	'PageInfo' => [ 'lipu_la_sona_namako', 'sona_lipu' ],
	'PagesWithProp' => [ 'o_alasa_e_lipu_kepeken_sona_namako', 'nasin_lipu' ],
	'PasswordPolicies' => [ 'lawa_pi_nimi_open_sijelo', 'lawa_pi_nimi_awen' ],
	'PermanentLink' => [ 'lipu_la_tenpo_pini_wan', 'linja_wawa' ],
	'Preferences' => [ 'wile_mi', 'wile' ],
	'Prefixindex' => [ 'open_nimi_la_lipu_ale_sama', 'open_pi_nimi_lipu' ],
	'Protectedpages' => [ 'lipu_awen' ],
	'Protectedtitles' => [ 'nimi_lipu_awen' ],
	'Purge' => [ 'o_sin_e_lipu', 'sin_lipu' ],
	'RandomInCategory' => [ 'lipu_wan_tan_kulupu_tan_nasa', 'lipu_pi_sona_ala_lon_kulupu' ],
	'Randompage' => [ 'lipu_wan_tan_nasa', 'lipu_pi_sona_ala' ],
	'Randomredirect' => [ 'lipu_tawa_wan_tan_nasa', 'lipu_tawa_pi_sona_ala' ],
	'Randomrootpage' => [ 'lipu_wan_tan_insa_ala_pi_lipu_ante_tan_nasa' ],
	'Recentchanges' => [ 'pali_pi_tenpo_poka', 'ante_pi_pini_poka' ],
	'Recentchangeslinked' => [ 'ante_pi_pini_poka_lon_lipu_linja' ],
	'Redirect' => [ 'tawa', 'lipu_tawa' ],
	'RemoveCredentials' => [ 'o_weka_e_nasin_pi_kama_sijelo', 'weka_pi_nasin_awen' ],
	'ResetTokens' => [ 'o_sin_e_nanpa_len', 'sin_pi_nanpa_awen' ],
	'Search' => [ 'alasa' ],
	'Shortpages' => [ 'lipu_ni_li_lili', 'lipu_lili' ],
	'Statistics' => [ 'ma_lipu_la_sona_namako', 'sona' ],
	'Tags' => [ 'nimi_sona_namako_pi_ante_lipu', 'toki_nasin' ],
	'Uncategorizedcategories' => [ 'kulupu_ni_li_lon_kulupu_ala', 'kulupu_pi_kulupu_ala' ],
	'Uncategorizedimages' => [ 'sitelen_ni_li_lon_kulupu_ala', 'ijo_pi_kulupu_ala' ],
	'Uncategorizedpages' => [ 'lipu_ni_li_lon_kulupu_ala', 'lipu_pi_kulupu_ala' ],
	'Uncategorizedtemplates' => [ 'kipisi_ni_li_lon_kulupu_ala', 'kipisi_pi_kulupu_ala' ],
	'Unusedcategories' => [ 'kulupu_ni_la_ala_li_kepeken', 'kulupu_weka' ],
	'Unusedimages' => [ 'sitelen_ni_la_ala_li_kepeken', 'ijo_weka' ],
	'Unusedtemplates' => [ 'kipisi_ni_la_ala_li_kepeken', 'kipisi_weka' ],
	'Userlogin' => [ 'o_kama_sijelo', 'kama_sijelo' ],
	'Userrights' => [ 'ken_pi_jan_ni', 'ken_jan' ],
	'Version' => [ 'nanpa_pi_sin_ilo' ],
	'Wantedcategories' => [ 'lipu_li_wile_lon_kulupu_ni', 'kulupu_wile' ],
	'Wantedfiles' => [ 'lipu_li_wile_e_sitelen_ni', 'ijo_wile' ],
	'Wantedpages' => [ 'lipu_li_wile_e_lipu_ni', 'lipu_wile' ],
	'Wantedtemplates' => [ 'lipu_li_wile_e_kipisi_ni', 'kipisi_wile' ],
	'Watchlist' => [ 'lipu_pi_wile_lukin_sina', 'lipu_lukin' ],
	'Whatlinkshere' => [ 'linja_tawa_lipu' ],
	'Withoutinterwiki' => [ 'lipu_ni_la_linja_ala_li_tawa_lipu_pi_toki_ante', 'linja_ala_tawa_lipu_pi_toki_ante' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'redirect' => [ '0', '#TAWA', '#REDIRECT' ],
];
