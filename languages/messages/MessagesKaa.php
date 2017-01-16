<?php
/** Kara-Kalpak (Qaraqalpaqsha)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'kk-latn, kk-cyrl';

$separatorTransformTable = [
	',' => "\xc2\xa0",
	'.' => ',',
];

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

$specialPageAliases = [
	'Allmessages'               => [ 'Ha\'mme xabarlar' ],
	'Allpages'                  => [ 'Ha\'mme betler' ],
	'Ancientpages'              => [ 'Eski betler' ],
	'BrokenRedirects'           => [ 'Jaramsız burıwshılar' ],
	'Categories'                => [ 'Kategoriyalar' ],
	'Contributions'             => [ 'Paydalanıwshı u\'lesi' ],
	'Deadendpages'              => [ 'Hesh betke siltemeytug\'ın betler' ],
	'DoubleRedirects'           => [ 'Qos burıwshılar' ],
	'Emailuser'                 => [ 'Xat jiberiw' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Az du\'zetilgenler' ],
	'Listadmins'                => [ 'Administratorlar' ],
	'Listfiles'                 => [ 'Su\'wretler dizimi' ],
	'Listredirects'             => [ 'Burıwshılar dizimi' ],
	'Listusers'                 => [ 'Paydalanıwshılar', 'Paydalanıwshı dizimi' ],
	'Log'                       => [ 'Jurnal', 'Jurnallar' ],
	'Lonelypages'               => [ 'Hesh betten siltelmegen betler' ],
	'Longpages'                 => [ 'Uzın betler' ],
	'MIMEsearch'                => [ 'MIME izlew' ],
	'Mostcategories'            => [ 'Ko\'p kategoriyalang\'anlar' ],
	'Mostimages'                => [ 'Ko\'p paydalanılg\'an su\'wretler' ],
	'Mostlinked'                => [ 'Ko\'p siltelgenler' ],
	'Mostlinkedcategories'      => [ 'Ko\'p paydalanılg\'an kategoriyalar' ],
	'Mostlinkedtemplates'       => [ 'Ko\'p paydalanılg\'an shablonlar' ],
	'Mostrevisions'             => [ 'Ko\'p du\'zetilgenler' ],
	'Movepage'                  => [ 'Betti ko\'shiriw' ],
	'Mycontributions'           => [ 'Menin\' u\'lesim' ],
	'Mypage'                    => [ 'Menin\' betim' ],
	'Mytalk'                    => [ 'Menin\' sa\'wbetim' ],
	'Newimages'                 => [ 'Taza su\'wretler' ],
	'Newpages'                  => [ 'Taza betler' ],
	'Preferences'               => [ 'Sazlawlar' ],
	'Protectedpages'            => [ 'Qorg\'alg\'an betler' ],
	'Randompage'                => [ 'Qa\'legen', 'Qa\'legen bet' ],
	'Randomredirect'            => [ 'Qa\'legen burıwshı' ],
	'Recentchanges'             => [ 'Aqırg\'ı o\'zgerisler' ],
	'Recentchangeslinked'       => [ 'Baylanıslı aqırg\'ı o\'zgerisler' ],
	'Revisiondelete'            => [ 'Nusqanı o\'shiriw' ],
	'Search'                    => [ 'İzlew' ],
	'Shortpages'                => [ 'Qqısqa betler' ],
	'Specialpages'              => [ 'Arnawlı betler' ],
	'Statistics'                => [ 'Statistika' ],
	'Uncategorizedcategories'   => [ 'Kategoriyasız kategoriyalar' ],
	'Uncategorizedimages'       => [ 'Kategoriyasız su\'wretler' ],
	'Uncategorizedpages'        => [ 'Kategoriyasız betler' ],
	'Uncategorizedtemplates'    => [ 'Kategoriyasız shablonlar' ],
	'Unusedcategories'          => [ 'Paydalanılmag\'an kategoriyalar' ],
	'Unusedimages'              => [ 'Paydalanılmag\'an fayllar', 'Paydalanılmag\'an su\'wretler' ],
	'Unusedtemplates'           => [ 'Paydalanılmag\'an shablonlar' ],
	'Unwatchedpages'            => [ 'Baqlanılmag\'an betler' ],
	'Userlogin'                 => [ 'Kiriw', 'Paydalanıwshı kiriw' ],
	'Userlogout'                => [ 'Shıg\'ıw', 'Paydalanıwshı shıg\'ıw' ],
	'Userrights'                => [ 'Paydalanıwshı huqıqları' ],
	'Version'                   => [ 'Versiya' ],
	'Wantedcategories'          => [ 'Talap qılıng\'an kategoriyalar' ],
	'Wantedpages'               => [ 'Talap qılıng\'an betler', 'Jaramsız sıltewler' ],
	'Watchlist'                 => [ 'Baqlaw dizimi' ],
	'Whatlinkshere'             => [ 'Siltelgen betler' ],
	'Withoutinterwiki'          => [ 'Hesh tilge siltemeytug\'ın betler' ],
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
