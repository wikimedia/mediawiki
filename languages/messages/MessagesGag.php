<?php
/** Gagauz (Gagauz)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'tr';

$namespaceNames = [
	NS_MEDIA            => 'Mediya',
	NS_SPECIAL          => 'Maasus',
	NS_TALK             => 'Dartışma',
	NS_USER             => 'Kullanıcı',
	NS_USER_TALK        => 'Kullanıcı_dartışma',
	NS_PROJECT_TALK     => '$1_dartışma',
	NS_FILE             => 'Dosye',
	NS_FILE_TALK        => 'Dosye_dartışma',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_dartışma',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_dartışma',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_dartışma',
	NS_CATEGORY         => 'Kategoriya',
	NS_CATEGORY_TALK    => 'Kategoriya_dartışma',
];

$namespaceAliases = [
	# Turkish namespaces
	'Medya'              => NS_MEDIA,
	'Özel'               => NS_SPECIAL,
	'Tartışma'           => NS_TALK,
	'Kullanıcı'          => NS_USER,
	'Kullanıcı_mesaj'    => NS_USER_TALK,
	'$1_tartışma'        => NS_PROJECT_TALK,
	'Dosya'              => NS_FILE,
	'Dosya_tartışma'     => NS_FILE_TALK,
	'MediaWiki'          => NS_MEDIAWIKI,
	'MediaWiki_tartışma' => NS_MEDIAWIKI_TALK,
	'Şablon'             => NS_TEMPLATE,
	'Şablon_tartışma'    => NS_TEMPLATE_TALK,
	'Yardım'             => NS_HELP,
	'Yardım_tartışma'    => NS_HELP_TALK,
	'Kategori'           => NS_CATEGORY,
	'Kategori_tartışma'  => NS_CATEGORY_TALK,
];

$specialPageAliases = [
	'Allpages'                  => [ 'HepsiYazılar', 'HepsiSayfalar', 'HepsiYapraklar' ],
	'Ancientpages'              => [ 'EskiYazılar', 'EskiSayfalar', 'EskiYapraklar' ],
	'Categories'                => [ 'Kategoriyalar' ],
	'Contributions'             => [ 'Katılmaklar' ],
	'CreateAccount'             => [ 'EsapYarat', 'EsapAç' ],
	'Deadendpages'              => [ 'BaalantısızYazılar', 'BaalantısızSayfalar', 'BaalantısızYapraklar' ],
	'DoubleRedirects'           => [ 'İkiKeräYönnendirmäler', 'İkiKeräYönnendirmeler' ],
	'Listadmins'                => [ 'İzmetliListası' ],
	'Listbots'                  => [ 'BotListası' ],
	'Listfiles'                 => [ 'DosyeListası', 'PätretListası' ],
	'Listredirects'             => [ 'YönnedirmeListası', 'YönndermäListası' ],
	'Listusers'                 => [ 'KullanıcıListası' ],
	'Mycontributions'           => [ 'Katılmaklarım' ],
	'Mytalk'                    => [ 'SözleşmäkSayfam', 'SözleşmäkYapraım' ],
	'Newimages'                 => [ 'EniDosyeler', 'EniPätretler' ],
	'Newpages'                  => [ 'EniYazılar', 'EniSayfalar', 'EniYapraklar' ],
	'Preferences'               => [ 'Seçimner' ],
	'Prefixindex'               => [ 'Prefiksİndeksi' ],
	'Randompage'                => [ 'Razgele', 'RazgeleYazı', 'RazgeleSayfa', 'RazgeleYaprak' ],
	'Randomredirect'            => [ 'RazgeleYönnendirme', 'RazgeleYönndermä' ],
	'Recentchanges'             => [ 'BitkiDiişikmäklär' ],
	'Search'                    => [ 'Ara' ],
	'Specialpages'              => [ 'MaasusSayfalar', 'MaasusYazılar', 'MaasusYapraklar' ],
	'Statistics'                => [ 'İstatistikalar' ],
	'Uncategorizedcategories'   => [ 'KategorizațiyasızKategoriyalar' ],
	'Uncategorizedimages'       => [ 'KategorizațiyasızDosyeler', 'KategorizațiyasızPätretler' ],
	'Uncategorizedpages'        => [ 'KategorizațiyasızYazılar', 'KategorizațiyasızSayfalar', 'KategorizațiyasızYapraklar' ],
	'Uncategorizedtemplates'    => [ 'KategorizațiyasızŞablonnar' ],
	'Unusedcategories'          => [ 'KullanılmayanKategoriyalar' ],
	'Unusedimages'              => [ 'KullanılmayanDosyeler', 'KullanılmayanPätretler' ],
	'Upload'                    => [ 'Ükle' ],
	'Watchlist'                 => [ 'SiirListası', 'BakmaaListası' ],
	'Withoutinterwiki'          => [ 'İntervikisiz' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#YÖNNENDİRMÄKLER', '#YÖNNENDİR', '#YÖNNENDİRMÄ', '#YÖNLENDİRME', '#YÖNLENDİR', '#REDIRECT' ],
];
