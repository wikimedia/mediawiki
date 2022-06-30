<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @file
 * @ingroup Languages
 */

$fallback = 'kk-latn, kk-cyrl';

$separatorTransformTable = [
	',' => "\u{00A0}",
	'.' => ',',
];
$minimumGroupingDigits = 2;

$fallback8bitEncoding = 'windows-1254';

$linkPrefixExtension = true;

$namespaceNames = [
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Arnawlı',
	NS_TALK             => 'Sa\'wbet',
	NS_USER             => 'Paydalanıwshı',
	NS_USER_TALK        => 'Paydalanıwshı_sa\'wbeti',
	NS_PROJECT_TALK     => '$1_sa\'wbeti',
	NS_FILE             => 'Su\'wret',
	NS_FILE_TALK        => 'Su\'wret_sa\'wbeti',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_sa\'wbeti',
	NS_TEMPLATE         => 'Shablon',
	NS_TEMPLATE_TALK    => 'Shablon_sa\'wbeti',
	NS_HELP             => 'Anıqlama',
	NS_HELP_TALK        => 'Anıqlama_sa\'wbeti',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_sa\'wbeti',
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Ha\'mme_xabarlar' ],
	'Allpages'                  => [ 'Ha\'mme_betler' ],
	'Ancientpages'              => [ 'Eski_betler' ],
	'BrokenRedirects'           => [ 'Jaramsız_burıwshılar' ],
	'Categories'                => [ 'Kategoriyalar' ],
	'Contributions'             => [ 'Paydalanıwshı_u\'lesi' ],
	'Deadendpages'              => [ 'Hesh_betke_siltemeytug\'ın_betler' ],
	'DoubleRedirects'           => [ 'Qos_burıwshılar' ],
	'Emailuser'                 => [ 'Xat_jiberiw' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Az_du\'zetilgenler' ],
	'Listadmins'                => [ 'Administratorlar' ],
	'Listfiles'                 => [ 'Su\'wretler_dizimi' ],
	'Listredirects'             => [ 'Burıwshılar_dizimi' ],
	'Listusers'                 => [ 'Paydalanıwshılar', 'Paydalanıwshı_dizimi' ],
	'Log'                       => [ 'Jurnal', 'Jurnallar' ],
	'Lonelypages'               => [ 'Hesh_betten_siltelmegen_betler' ],
	'Longpages'                 => [ 'Uzın_betler' ],
	'MIMEsearch'                => [ 'MIME_izlew' ],
	'Mostcategories'            => [ 'Ko\'p_kategoriyalang\'anlar' ],
	'Mostimages'                => [ 'Ko\'p_paydalanılg\'an_su\'wretler' ],
	'Mostlinked'                => [ 'Ko\'p_siltelgenler' ],
	'Mostlinkedcategories'      => [ 'Ko\'p_paydalanılg\'an_kategoriyalar' ],
	'Mostlinkedtemplates'       => [ 'Ko\'p_paydalanılg\'an_shablonlar' ],
	'Mostrevisions'             => [ 'Ko\'p_du\'zetilgenler' ],
	'Movepage'                  => [ 'Betti_ko\'shiriw' ],
	'Mycontributions'           => [ 'Menin\'_u\'lesim' ],
	'Mypage'                    => [ 'Menin\'_betim' ],
	'Mytalk'                    => [ 'Menin\'_sa\'wbetim' ],
	'Newimages'                 => [ 'Taza_su\'wretler' ],
	'Newpages'                  => [ 'Taza_betler' ],
	'Preferences'               => [ 'Sazlawlar' ],
	'Protectedpages'            => [ 'Qorg\'alg\'an_betler' ],
	'Randompage'                => [ 'Qa\'legen', 'Qa\'legen_bet' ],
	'Randomredirect'            => [ 'Qa\'legen_burıwshı' ],
	'Recentchanges'             => [ 'Aqırg\'ı_o\'zgerisler' ],
	'Recentchangeslinked'       => [ 'Baylanıslı_aqırg\'ı_o\'zgerisler' ],
	'Revisiondelete'            => [ 'Nusqanı_o\'shiriw' ],
	'Search'                    => [ 'İzlew' ],
	'Shortpages'                => [ 'Qqısqa_betler' ],
	'Specialpages'              => [ 'Arnawlı_betler' ],
	'Statistics'                => [ 'Statistika' ],
	'Uncategorizedcategories'   => [ 'Kategoriyasız_kategoriyalar' ],
	'Uncategorizedimages'       => [ 'Kategoriyasız_su\'wretler' ],
	'Uncategorizedpages'        => [ 'Kategoriyasız_betler' ],
	'Uncategorizedtemplates'    => [ 'Kategoriyasız_shablonlar' ],
	'Unusedcategories'          => [ 'Paydalanılmag\'an_kategoriyalar' ],
	'Unusedimages'              => [ 'Paydalanılmag\'an_fayllar', 'Paydalanılmag\'an_su\'wretler' ],
	'Unusedtemplates'           => [ 'Paydalanılmag\'an_shablonlar' ],
	'Unwatchedpages'            => [ 'Baqlanılmag\'an_betler' ],
	'Userlogin'                 => [ 'Kiriw', 'Paydalanıwshı_kiriw' ],
	'Userlogout'                => [ 'Shıg\'ıw', 'Paydalanıwshı_shıg\'ıw' ],
	'Userrights'                => [ 'Paydalanıwshı_huqıqları' ],
	'Version'                   => [ 'Versiya' ],
	'Wantedcategories'          => [ 'Talap_qılıng\'an_kategoriyalar' ],
	'Wantedpages'               => [ 'Talap_qılıng\'an_betler', 'Jaramsız_sıltewler' ],
	'Watchlist'                 => [ 'Baqlaw_dizimi' ],
	'Whatlinkshere'             => [ 'Siltelgen_betler' ],
	'Withoutinterwiki'          => [ 'Hesh_tilge_siltemeytug\'ın_betler' ],
];

$datePreferences = [
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
	'ISO 8601',
];

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = [
	'default',
	'mdy',
	'dmy',
	'ymd'
];

$dateFormats = [
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y "j."',
	'mdy both' => 'H:i, xg j, Y "j."',

	'dmy time' => 'H:i',
	'dmy date' => 'j F, Y "j."',
	'dmy both' => 'H:i, j F, Y "j."',

	'ymd time' => 'H:i',
	'ymd date' => 'Y "j." xg j',
	'ymd both' => 'H:i, Y "j." xg j',

	'yyyy-mm-dd time' => 'xnH:xni:xns',
	'yyyy-mm-dd date' => 'xnY-xnm-xnd',
	'yyyy-mm-dd both' => 'xnH:xni:xns, xnY-xnm-xnd',

	'ISO 8601 time' => 'xnH:xni:xns',
	'ISO 8601 date' => 'xnY-xnm-xnd',
	'ISO 8601 both' => 'xnY-xnm-xnd"T"xnH:xni:xns',
];

$linkTrail = "/^((?:[a-zıʼ’“»]|'(?!'))+)(.*)$/sDu";
$linkPrefixCharset = 'a-zıA-Zİ\\x80-\\xff';
