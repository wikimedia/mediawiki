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

$separatorTransformTable = array(
	',' => "\xc2\xa0",
	'.' => ',',
);

$fallback8bitEncoding = 'windows-1254';

$linkPrefixExtension = true;

$namespaceNames = array(
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
);

$specialPageAliases = array(
	'Allmessages'               => array( 'Ha\'mme xabarlar' ),
	'Allpages'                  => array( 'Ha\'mme betler' ),
	'Ancientpages'              => array( 'Eski betler' ),
	'BrokenRedirects'           => array( 'Jaramsız burıwshılar' ),
	'Categories'                => array( 'Kategoriyalar' ),
	'Contributions'             => array( 'Paydalanıwshı u\'lesi' ),
	'Deadendpages'              => array( 'Hesh betke siltemeytug\'ın betler' ),
	'DoubleRedirects'           => array( 'Qos burıwshılar' ),
	'Emailuser'                 => array( 'Xat jiberiw' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Az du\'zetilgenler' ),
	'Listadmins'                => array( 'Administratorlar' ),
	'Listfiles'                 => array( 'Su\'wretler dizimi' ),
	'Listredirects'             => array( 'Burıwshılar dizimi' ),
	'Listusers'                 => array( 'Paydalanıwshılar', 'Paydalanıwshı dizimi' ),
	'Log'                       => array( 'Jurnal', 'Jurnallar' ),
	'Lonelypages'               => array( 'Hesh betten siltelmegen betler' ),
	'Longpages'                 => array( 'Uzın betler' ),
	'MIMEsearch'                => array( 'MIME izlew' ),
	'Mostcategories'            => array( 'Ko\'p kategoriyalang\'anlar' ),
	'Mostimages'                => array( 'Ko\'p paydalanılg\'an su\'wretler' ),
	'Mostlinked'                => array( 'Ko\'p siltelgenler' ),
	'Mostlinkedcategories'      => array( 'Ko\'p paydalanılg\'an kategoriyalar' ),
	'Mostlinkedtemplates'       => array( 'Ko\'p paydalanılg\'an shablonlar' ),
	'Mostrevisions'             => array( 'Ko\'p du\'zetilgenler' ),
	'Movepage'                  => array( 'Betti ko\'shiriw' ),
	'Mycontributions'           => array( 'Menin\' u\'lesim' ),
	'Mypage'                    => array( 'Menin\' betim' ),
	'Mytalk'                    => array( 'Menin\' sa\'wbetim' ),
	'Newimages'                 => array( 'Taza su\'wretler' ),
	'Newpages'                  => array( 'Taza betler' ),
	'Preferences'               => array( 'Sazlawlar' ),
	'Protectedpages'            => array( 'Qorg\'alg\'an betler' ),
	'Randompage'                => array( 'Qa\'legen', 'Qa\'legen bet' ),
	'Randomredirect'            => array( 'Qa\'legen burıwshı' ),
	'Recentchanges'             => array( 'Aqırg\'ı o\'zgerisler' ),
	'Recentchangeslinked'       => array( 'Baylanıslı aqırg\'ı o\'zgerisler' ),
	'Revisiondelete'            => array( 'Nusqanı o\'shiriw' ),
	'Search'                    => array( 'İzlew' ),
	'Shortpages'                => array( 'Qqısqa betler' ),
	'Specialpages'              => array( 'Arnawlı betler' ),
	'Statistics'                => array( 'Statistika' ),
	'Uncategorizedcategories'   => array( 'Kategoriyasız kategoriyalar' ),
	'Uncategorizedimages'       => array( 'Kategoriyasız su\'wretler' ),
	'Uncategorizedpages'        => array( 'Kategoriyasız betler' ),
	'Uncategorizedtemplates'    => array( 'Kategoriyasız shablonlar' ),
	'Unusedcategories'          => array( 'Paydalanılmag\'an kategoriyalar' ),
	'Unusedimages'              => array( 'Paydalanılmag\'an fayllar', 'Paydalanılmag\'an su\'wretler' ),
	'Unusedtemplates'           => array( 'Paydalanılmag\'an shablonlar' ),
	'Unwatchedpages'            => array( 'Baqlanılmag\'an betler' ),
	'Userlogin'                 => array( 'Kiriw', 'Paydalanıwshı kiriw' ),
	'Userlogout'                => array( 'Shıg\'ıw', 'Paydalanıwshı shıg\'ıw' ),
	'Userrights'                => array( 'Paydalanıwshı huqıqları' ),
	'Version'                   => array( 'Versiya' ),
	'Wantedcategories'          => array( 'Talap qılıng\'an kategoriyalar' ),
	'Wantedpages'               => array( 'Talap qılıng\'an betler', 'Jaramsız sıltewler' ),
	'Watchlist'                 => array( 'Baqlaw dizimi' ),
	'Whatlinkshere'             => array( 'Siltelgen betler' ),
	'Withoutinterwiki'          => array( 'Hesh tilge siltemeytug\'ın betler' ),
);

$datePreferences = array(
	'default',
	'mdy',
	'dmy',
	'ymd',
	'yyyy-mm-dd',
	'ISO 8601',
);

$defaultDateFormat = 'ymd';

$datePreferenceMigrationMap = array(
	'default',
	'mdy',
	'dmy',
	'ymd'
);

$dateFormats = array(
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
);

$linkTrail = "/^((?:[a-zıʼ’“»]|'(?!'))+)(.*)$/sDu";
$linkPrefixCharset = 'a-zıA-Zİ\\x80-\\xff';

