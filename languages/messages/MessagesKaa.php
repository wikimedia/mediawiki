<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * @file
 * @ingroup Languages
 *
 * @author Jimmy Collins
 * @author Nurlan
 * @author Amir E. Aharoni
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
	NS_TALK             => 'Talqılaw',
	NS_USER             => 'Paydalanıwshı',
	NS_USER_TALK        => 'Paydalanıwshı_talqılawı',
	NS_PROJECT_TALK     => '$1_talqılawı',
	NS_FILE             => 'Fayl',
	NS_FILE_TALK        => 'Fayl_talqılawı',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_talqılawı',
	NS_TEMPLATE         => 'Úlgi',
	NS_TEMPLATE_TALK    => 'Úlgi_talqılawı',
	NS_HELP             => 'Járdem',
	NS_HELP_TALK        => 'Járdem_talqılawı',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_talqılawı',
];

$namespaceAliases = [
	'Sa\'wbet'                => NS_TALK,
	'Paydalanıwshı_sa\'wbeti' => NS_USER_TALK,
	'$1_sa\'wbeti'            => NS_PROJECT_TALK,
	'Su\'wret'                => NS_FILE,
	'Su\'wret_sa\'wbeti'      => NS_FILE_TALK,
	'MediaWiki_sa\'wbeti'     => NS_MEDIAWIKI_TALK,
	'Shablon'                 => NS_TEMPLATE,
	'Shablon_sa\'wbeti'       => NS_TEMPLATE_TALK,
	'Anıqlama'                => NS_HELP,
	'Anıqlama_sa\'wbeti'      => NS_HELP_TALK,
	'Kategoriya_sa\'wbeti'    => NS_CATEGORY_TALK,
];

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Allmessages'               => [ 'Hámme_xabarlar', 'Ha\'mme_xabarlar' ],
	'Allpages'                  => [ 'Hámme_betler', 'Ha\'mme_betler' ],
	'Ancientpages'              => [ 'Eski_betler' ],
	'BrokenRedirects'           => [ 'Jaramsız_baǵdarlawlar', 'Jaramsız_burıwshılar' ],
	'Categories'                => [ 'Kategoriyalar' ],
	'Contributions'             => [ 'Paydalanıwshı_úlesi', 'Paydalanıwshı_u\'lesi' ],
	'Deadendpages'              => [ 'Hesh_betke_siltemeytuǵın_betler', 'Hesh_betke_siltemeytug\'ın_betler' ],
	'DoubleRedirects'           => [ 'Qos_baǵdarlawlar', 'Qos_burıwshılar' ],
	'Emailuser'                 => [ 'Xat_jiberiw' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Az_dúzetilgenler', 'Az_du\'zetilgenler' ],
	'Listadmins'                => [ 'Administratorlar' ],
	'Listfiles'                 => [ 'Fayllar_dizimi', 'Súwretler_dizimi', 'Su\'wretler_dizimi' ],
	'Listredirects'             => [ 'Baǵdarlawlar_dizimi', 'Burıwshılar_dizimi' ],
	'Listusers'                 => [ 'Paydalanıwshılar', 'Paydalanıwshı_dizimi' ],
	'Log'                       => [ 'Jurnal', 'Jurnallar' ],
	'Lonelypages'               => [ 'Hesh_betten_siltelmegen_betler' ],
	'Longpages'                 => [ 'Uzın_betler' ],
	'MIMEsearch'                => [ 'MIME_izlew' ],
	'Mostcategories'            => [ 'Kóp_kategoriyalanǵanlar', 'Ko\'p_kategoriyalang\'anlar' ],
	'Mostimages'                => [ 'Kóp_paydalanılǵan_súwretler', 'Ko\'p_paydalanılg\'an_su\'wretler' ],
	'Mostlinked'                => [ 'Kóp_siltelgenler', 'Ko\'p_siltelgenler' ],
	'Mostlinkedcategories'      => [ 'Kóp_paydalanılǵan_kategoriyalar', 'Ko\'p_paydalanılg\'an_kategoriyalar' ],
	'Mostlinkedtemplates'       => [ 'Kóp_paydalanılǵan_úlgiler', 'Ko\'p_paydalanılg\'an_shablonlar' ],
	'Mostrevisions'             => [ 'Kóp_dúzetilgenler', 'Ko\'p_du\'zetilgenler' ],
	'Movepage'                  => [ 'Betti_kóshiriw', 'Betti_ko\'shiriw' ],
	'Mycontributions'           => [ 'Meniń_úlesim', 'Menin\'_u\'lesim' ],
	'Mypage'                    => [ 'Meniń_betim', 'Menin\'_betim' ],
	'Mytalk'                    => [ 'Meniń_talqılawım', 'Meniń_sáwbetim', 'Menin\'_sa\'wbetim' ],
	'Newimages'                 => [ 'Taza_súwretler', 'Taza_su\'wretler' ],
	'Newpages'                  => [ 'Taza_betler' ],
	'Preferences'               => [ 'Sazlawlar' ],
	'Protectedpages'            => [ 'Qorǵalǵan_betler', 'Qorg\'alg\'an_betler' ],
	'Randompage'                => [ 'Tosınnanlı', 'Tosınnanlı_bet', 'Qálegen', 'Qálegen_bet', 'Qa\'legen', 'Qa\'legen_bet' ],
	'Randomredirect'            => [ 'Tosınnanlı_baǵdarlaw', 'Qálegen_burıwshı', 'Qa\'legen_burıwshı' ],
	'Recentchanges'             => [ 'Aqırǵı_ózgerisler', 'Aqırg\'ı_o\'zgerisler' ],
	'Recentchangeslinked'       => [ 'Baylanıslı_aqırǵı_ózgerisler', 'Baylanıslı_aqırg\'ı_o\'zgerisler' ],
	'Revisiondelete'            => [ 'Nusqanı_óshiriw', 'Nusqanı_o\'shiriw' ],
	'Search'                    => [ 'Izlew', 'İzlew' ],
	'Shortpages'                => [ 'Qısqa_betler', 'Qqısqa_betler' ],
	'Specialpages'              => [ 'Arnawlı_betler' ],
	'Statistics'                => [ 'Statistika' ],
	'Uncategorizedcategories'   => [ 'Kategoriyasız_kategoriyalar' ],
	'Uncategorizedimages'       => [ 'Kategoriyasız_súwretler', 'Kategoriyasız_su\'wretler' ],
	'Uncategorizedpages'        => [ 'Kategoriyasız_betler' ],
	'Uncategorizedtemplates'    => [ 'Kategoriyasız_úlgiler', 'Kategoriyasız_shablonlar' ],
	'Unusedcategories'          => [ 'Paydalanılmaǵan_kategoriyalar', 'Paydalanılmag\'an_kategoriyalar' ],
	'Unusedimages'              => [ 'Paydalanılmaǵan_fayllar', 'Paydalanılmaǵan_súwretler', 'Paydalanılmag\'an_fayllar', 'Paydalanılmag\'an_su\'wretler' ],
	'Unusedtemplates'           => [ 'Paydalanılmaǵan_úlgiler', 'Paydalanılmag\'an_shablonlar' ],
	'Unwatchedpages'            => [ 'Baqlanılmaǵan_betler', 'Baqlanılmag\'an_betler' ],
	'Userlogin'                 => [ 'Kiriw', 'Paydalanıwshı_kiriw' ],
	'Userlogout'                => [ 'Shıǵıw', 'Paydalanıwshı_shıǵıw', 'Shıg\'ıw', 'Paydalanıwshı_shıg\'ıw' ],
	'Userrights'                => [ 'Paydalanıwshı_huqıqları' ],
	'Version'                   => [ 'Versiya' ],
	'Wantedcategories'          => [ 'Talap_qılınǵan_kategoriyalar', 'Talap_qılıng\'an_kategoriyalar' ],
	'Wantedpages'               => [ 'Talap_qılınǵan_betler', 'Talap_qılıng\'an_betler', 'Jaramsız_sıltewler' ],
	'Watchlist'                 => [ 'Baqlaw_dizimi' ],
	'Whatlinkshere'             => [ 'Siltelgen_betler' ],
	'Withoutinterwiki'          => [ 'Hesh_tilge_siltemeytuǵın_betler', 'Hesh_tilge_siltemeytug\'ın_betler' ],
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

$linkTrail = "/^((?:[a-zıÁáǴǵŃńÓóÚúÍıİʼ’“»]|'(?!'))+)(.*)$/sDu";
$linkPrefixCharset = 'a-zıA-ZÁáǴǵŃńÓóÚúÍıİ\\x80-\\xff';
