<?php
/** Gagauz (Gagauz)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 */

$fallback = 'tr';

$namespaceNames = array(
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
);

$namespaceAliases = array(
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
);

$specialPageAliases = array(
	'Allpages'                  => array( 'HepsiYazılar', 'HepsiSayfalar', 'HepsiYapraklar' ),
	'Ancientpages'              => array( 'EskiYazılar', 'EskiSayfalar', 'EskiYapraklar' ),
	'Categories'                => array( 'Kategoriyalar' ),
	'Contributions'             => array( 'Katılmaklar' ),
	'CreateAccount'             => array( 'EsapYarat', 'EsapAç' ),
	'Deadendpages'              => array( 'BaalantısızYazılar', 'BaalantısızSayfalar', 'BaalantısızYapraklar' ),
	'DoubleRedirects'           => array( 'İkiKeräYönnendirmäler', 'İkiKeräYönnendirmeler' ),
	'Listadmins'                => array( 'İzmetliListası' ),
	'Listbots'                  => array( 'BotListası' ),
	'Listfiles'                 => array( 'DosyeListası', 'PätretListası' ),
	'Listredirects'             => array( 'YönnedirmeListası', 'YönndermäListası' ),
	'Listusers'                 => array( 'KullanıcıListası' ),
	'Mycontributions'           => array( 'Katılmaklarım' ),
	'Mytalk'                    => array( 'SözleşmäkSayfam', 'SözleşmäkYapraım' ),
	'Newimages'                 => array( 'EniDosyeler', 'EniPätretler' ),
	'Newpages'                  => array( 'EniYazılar', 'EniSayfalar', 'EniYapraklar' ),
	'Popularpages'              => array( 'EnAnılmışSayfalar', 'EnAnılmışYazılar' ),
	'Preferences'               => array( 'Seçimner' ),
	'Prefixindex'               => array( 'Prefiksİndeksi' ),
	'Randompage'                => array( 'Razgele', 'RazgeleYazı', 'RazgeleSayfa', 'RazgeleYaprak' ),
	'Randomredirect'            => array( 'RazgeleYönnendirme', 'RazgeleYönndermä' ),
	'Recentchanges'             => array( 'BitkiDiişikmäklär' ),
	'Search'                    => array( 'Ara' ),
	'Specialpages'              => array( 'MaasusSayfalar', 'MaasusYazılar', 'MaasusYapraklar' ),
	'Statistics'                => array( 'İstatistikalar' ),
	'Uncategorizedcategories'   => array( 'KategorizațiyasızKategoriyalar' ),
	'Uncategorizedimages'       => array( 'KategorizațiyasızDosyeler', 'KategorizațiyasızPätretler' ),
	'Uncategorizedpages'        => array( 'KategorizațiyasızYazılar', 'KategorizațiyasızSayfalar', 'KategorizațiyasızYapraklar' ),
	'Uncategorizedtemplates'    => array( 'KategorizațiyasızŞablonnar' ),
	'Unusedcategories'          => array( 'KullanılmayanKategoriyalar' ),
	'Unusedimages'              => array( 'KullanılmayanDosyeler', 'KullanılmayanPätretler' ),
	'Upload'                    => array( 'Ükle' ),
	'Watchlist'                 => array( 'SiirListası', 'BakmaaListası' ),
	'Withoutinterwiki'          => array( 'İntervikisiz' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#YÖNNENDİRMÄKLER', '#YÖNNENDİR', '#YÖNNENDİRMÄ', '#YÖNLENDİRME', '#YÖNLENDİR', '#REDIRECT' ),
);

