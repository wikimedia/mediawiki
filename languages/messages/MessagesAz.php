<?php
/** Azerbaijani (Azərbaycanca)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Cekli829
 * @author Don Alessandro
 * @author Emperyan
 * @author Gulmammad
 * @author PPerviz
 * @author PrinceValiant
 * @author Sortilegus
 * @author Sysops of az.wikipedia.org (imported 2008-08-31)
 * @author Vago
 * @author Vugar 1981
 * @author Wertuose
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_SPECIAL          => 'Xüsusi',
	NS_MAIN             => '',
	NS_TALK             => 'Müzakirə',
	NS_USER             => 'İstifadəçi',
	NS_USER_TALK        => 'İstifadəçi_müzakirəsi',
	NS_PROJECT_TALK     => '$1_müzakirəsi',
	NS_FILE             => 'Şəkil',
	NS_FILE_TALK        => 'Şəkil_müzakirəsi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
);

$namespaceAliases = array(
	'Mediya'                 => NS_MEDIA,
	'MediyaViki'             => NS_MEDIAWIKI,
	'MediyaViki_müzakirəsi'  => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktivİstifadəçilər' ),
	'Allpages'                  => array( 'BütünSəhifələr' ),
	'Contributions'             => array( 'Fəaliyyətlər' ),
	'CreateAccount'             => array( 'HesabAç' ),
	'Longpages'                 => array( 'UzunSəhifələr' ),
	'Mycontributions'           => array( 'MənimFəaliyyətlərim' ),
	'Mypage'                    => array( 'MənimSəhifəm' ),
	'Mytalk'                    => array( 'MənimDanışıqlarım' ),
	'Newpages'                  => array( 'YeniSəhifələr' ),
	'Preferences'               => array( 'Nizamlamalar' ),
	'Recentchanges'             => array( 'SonDəyişikliklər' ),
	'Search'                    => array( 'Axtar' ),
	'Shortpages'                => array( 'QısaSəhifələr' ),
	'Specialpages'              => array( 'XüsusiSəhifələr' ),
	'Statistics'                => array( 'Statistika' ),
	'Undelete'                  => array( 'Pozma' ),
	'Version'                   => array( 'Versiya' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#İSTİQAMƏTLƏNDİRMƏ', '#İSTİQAMƏTLƏNDİR', '#REDIRECT' ),
	'notoc'                 => array( '0', '__MÜNDƏRİCATYOX__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__QALEREYAYOX__', '__NOGALLERY__' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Keçidlərin altını xətlə:',
'tog-highlightbroken'         => 'Keçidsiz linkləri <a href="" class="new">bunun kimi</a> (alternativ: bunun kimi<a href="" class="internal">?</a>) işarələ.',
'tog-justify'                 => 'Mətni səhifə boyu payla',
'tog-hideminor'               => 'Son dəyişikliklərdə kiçik redaktələri gizlə',
'tog-hidepatrolled'           => 'Yoxlanılmış redaktələri son dəyişikliklərdə göstərmə',
'tog-newpageshidepatrolled'   => 'Yoxlanılmış səhifələri yeni səhifə siyahısında göstərmə',
'tog-extendwatchlist'         => 'Təkmil izləmə siyahısı',
'tog-usenewrc'                => 'Son dəyişikliklərin təkmil versiyası (JavaScript)',
'tog-numberheadings'          => 'Başlıqların avto-nömrələnməsi',
'tog-showtoolbar'             => 'Redaktə zamanı alətlər qutusunu göstər (JavaScript)',
'tog-editondblclick'          => 'Səhifələri iki kliklə redaktə etməyə başla (JavaScript)',
'tog-editsection'             => 'Hər bir bölmə üçün [redaktə]ni mümkün et',
'tog-editsectiononrightclick' => 'Bölmələrin redaktəsini başlıqların üzərində sağ klik etməklə mümkün et (JavaScript)',
'tog-showtoc'                 => 'Mündəricat siyahısını göstər (3 başlıqdan artıq olan səhifələrdə)',
'tog-rememberpassword'        => 'Məni bu kompüterdə xatırla (maksimum $1 {{PLURAL:$1|gün|gün}})',
'tog-watchcreations'          => 'Yaratdığım səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchdefault'            => 'Redaktə etdiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchmoves'              => 'Adlarını dəyişdiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchdeletion'           => 'Sildiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-minordefault'            => 'Default olaraq bütün redaktələri kiçik redaktə kimi nişanla',
'tog-previewontop'            => 'Sınaq göstərişi yazma sahəsindən əvvəl göstər',
'tog-previewonfirst'          => 'İlkin redaktədə sınaq göstərişi',
'tog-nocache'                 => 'Səhifələri keşdə saxlama',
'tog-enotifwatchlistpages'    => 'İzləmə siyahısında olan məqalə redaktə olunsa, mənə e-məktub göndər',
'tog-enotifusertalkpages'     => 'Müzakirə səhifəm redaktə olunsa, mənə e-məktub göndər',
'tog-enotifminoredits'        => 'Səhifələrdə kiçik dəyişikliklər olsa belə, mənə e-məktub göndər',
'tog-enotifrevealaddr'        => 'Xəbərdarlıq e-məktublarında mənim e-poçt ünvanımı göstər',
'tog-shownumberswatching'     => 'İzləyən istifadəçilərin sayını göstər',
'tog-oldsig'                  => 'Hazırkı imza:',
'tog-fancysig'                => 'Vikimətn şəklində imza (avtomatik keçid yaratmadan)',
'tog-externaleditor'          => 'Susmaya görə xarici müqayisə proqramlarından istifadə et',
'tog-externaldiff'            => 'Susmaya görə xarici müqayisə proqramlarından istifadə et',
'tog-showjumplinks'           => '"Keçid et:" linklərini aktivləşdir',
'tog-uselivepreview'          => 'Canlı sınaq baxışı xüsusiyyətini istifadə et (JavaScript, sınaq mərhələsində)',
'tog-forceeditsummary'        => 'Qısa məzmunu boş saxladıqda mənə bildir',
'tog-watchlisthideown'        => 'Mənim redaktələrimi izləmə siyahısında gizlət',
'tog-watchlisthidebots'       => 'Bot redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthideminor'      => 'İzləmə səhifəmdə kiçik redaktələri gizlət',
'tog-watchlisthideliu'        => 'Qeydiyyatlı istifadəçilərin redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthideanons'      => 'Qeydiyyatdan keçməmiş istifadəçilərin redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthidepatrolled'  => 'Yoxlanılmış redaktələri izləmə siyahısında gizlət',
'tog-ccmeonemails'            => 'Göndərdiyim e-məktubun nüsxələrini mənə göndər',
'tog-diffonly'                => 'Versiyaların müqayisəsi zamanı səhifənin məzmununu göstərmə',
'tog-showhiddencats'          => 'Gizli kateqoriyaları göstər',
'tog-norollbackdiff'          => 'Geri qaytardıqdan sonra, edilmiş dəyişikikləri dəyişikliklər siyahısından sil',

'underline-always'  => 'Həmişə',
'underline-never'   => 'Heç vaxt',
'underline-default' => 'Susmaya görə brouzer',

# Font style option in Special:Preferences
'editfont-style'     => 'Redaktə sahəsinin şrift növü:',
'editfont-default'   => 'Brauzerin tənzimləmələrində təsdiq olunmuş şrift',
'editfont-monospace' => 'Bərabərenli şrift',
'editfont-sansserif' => 'Sans-serif şrifti',
'editfont-serif'     => 'Serif şrifti',

# Dates
'sunday'        => 'Bazar',
'monday'        => 'Bazar ertəsi',
'tuesday'       => 'Çərşənbə axşamı',
'wednesday'     => 'Çərşənbə',
'thursday'      => 'Cümə axşamı',
'friday'        => 'Cümə',
'saturday'      => 'Şənbə',
'sun'           => 'Bazar',
'mon'           => 'Bazar ertəsi',
'tue'           => 'Çərşənbə axşamı',
'wed'           => 'Çərşənbə',
'thu'           => 'Cümə axşamı',
'fri'           => 'Cümə',
'sat'           => 'Şənbə',
'january'       => 'yanvar',
'february'      => 'fevral',
'march'         => 'mart',
'april'         => 'aprel',
'may_long'      => 'may',
'june'          => 'iyun',
'july'          => 'iyul',
'august'        => 'avqust',
'september'     => 'sentyabr',
'october'       => 'oktyabr',
'november'      => 'noyabr',
'december'      => 'dekabr',
'january-gen'   => 'yanvar',
'february-gen'  => 'fevral',
'march-gen'     => 'mart',
'april-gen'     => 'aprel',
'may-gen'       => 'may',
'june-gen'      => 'iyun',
'july-gen'      => 'iyul',
'august-gen'    => 'avqust',
'september-gen' => 'sentyabr',
'october-gen'   => 'oktyabr',
'november-gen'  => 'noyabr',
'december-gen'  => 'dekabr',
'jan'           => 'Yanvar',
'feb'           => 'Fevral',
'mar'           => 'Mart',
'apr'           => 'Aprel',
'may'           => 'May',
'jun'           => 'İyun',
'jul'           => 'İyul',
'aug'           => 'Avqust',
'sep'           => 'Sentyabr',
'oct'           => 'Oktyabr',
'nov'           => 'Noyabr',
'dec'           => 'Dekabr',

# Categories related messages
'pagecategories'                 => '$1 {{PLURAL:$1|Kateqoriya|Kateqoriya}}',
'category_header'                => '"$1" kateqoriyasındakı məqalələr',
'subcategories'                  => 'Alt kateqoriyalar',
'category-media-header'          => '"$1" kateqoriyasında mediya',
'category-empty'                 => "''Bu kateqoriyanın tərkibi hal-hazırda boşdur.''",
'hidden-categories'              => '{{PLURAL:$1|Gizli kateqoriya|Gizli kateqoriyalar}}',
'hidden-category-category'       => 'Gizli kateqoriyalar',
'category-subcat-count'          => '{{PLURAL:$2|Bu kateqoriya yalnız aşağıdakı altkateqoriyadan ibarətdir.|Cəmi $2 kateqoriyadan {{PLURAL:$1|altkateqoriya|$1 altkateqoriya}} göstərilmişdir.}}',
'category-subcat-count-limited'  => 'Bu kateqoriyada {{PLURAL:$1|$1 alt kateqoriya}} var.',
'category-article-count'         => '{{PLURAL:$2|Bu kateqoriya yalnız aşağıdakı səhifədən ibarətdir.|Cəmi $2 səhifədən aşağıdakı {{PLURAL:$1|səhifə|$1 səhifə}} bu kateqoriyadadır.}}',
'category-article-count-limited' => 'Bu kateqoriyada {{PLURAL:$1|$1 səhifə}} var.',
'category-file-count'            => '{{PLURAL:$2|Bu kateqoriya yalnız aşağıdakı fayldan ibarətdir.|Cəmi $2 fayldan {{PLURAL:$1|fayl|$1 fayl}} bu kateqoriyadadır.}}',
'category-file-count-limited'    => 'Bu kateqoriyada {{PLURAL:$1|$1 fayl}} var.',
'listingcontinuesabbrev'         => '(davam)',
'index-category'                 => 'İndeksləşdirilmiş səhifələr',
'noindex-category'               => 'İndeksləşdirilməyən səhifələr',
'broken-file-category'           => 'İşləməyən fayl keçidləri olan səhifələr',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xff]+)$/sD',

'about'         => 'İzah',
'article'       => 'Mündəricat',
'newwindow'     => '(yeni pəncərədə açılır)',
'cancel'        => 'Ləğv et',
'moredotdotdot' => 'Daha...',
'mypage'        => 'Mənim səhifəm',
'mytalk'        => 'Danışıqlarım',
'anontalk'      => 'Bu IP-yə aid müzakirə',
'navigation'    => 'Naviqasiya',
'and'           => '&#32;və',

# Cologne Blue skin
'qbfind'         => 'Tap',
'qbbrowse'       => 'Gözdən keçir',
'qbedit'         => 'Redaktə',
'qbpageoptions'  => 'Bu səhifə',
'qbpageinfo'     => 'Məzmun',
'qbmyoptions'    => 'Mənim səhifələrim',
'qbspecialpages' => 'Xüsusi səhifələr',
'faq'            => 'TSS',
'faqpage'        => 'Project:TSS',

# Vector skin
'vector-action-addsection'       => 'Mövzu əlavə et',
'vector-action-delete'           => 'Sil',
'vector-action-move'             => 'Adını dəyişdir',
'vector-action-protect'          => 'Mühafizə et',
'vector-action-undelete'         => 'Bərpa et',
'vector-action-unprotect'        => 'Mühafizəni kənarlaşdır',
'vector-simplesearch-preference' => 'İnkişaf etmiş axtarma təkliflərini gətir (yalnız Vector görünüşü üçün)',
'vector-view-create'             => 'Yarat',
'vector-view-edit'               => 'Redaktə',
'vector-view-history'            => 'Tarixçə',
'vector-view-view'               => 'Oxu',
'vector-view-viewsource'         => 'Mənbəyə bax',
'actions'                        => 'Hərəkətlər',
'namespaces'                     => 'Adlar fəzası',
'variants'                       => 'Variantlar',

'errorpagetitle'    => 'Xəta',
'returnto'          => '$1 səhifəsinə qayıt.',
'tagline'           => '{{SITENAME}} saytından',
'help'              => 'Kömək',
'search'            => 'Axtar',
'searchbutton'      => 'Axtar',
'go'                => 'Keç',
'searcharticle'     => 'Keç',
'history'           => 'Səhifənin tarixçəsi',
'history_short'     => 'Tarixçə',
'updatedmarker'     => 'son dəfə mən nəzərdən keçirəndən sonra yenilənib',
'printableversion'  => 'Çap variantı',
'permalink'         => 'Daimi bağlantı',
'print'             => 'Çap',
'view'              => 'Görünüş',
'edit'              => 'Redaktə',
'create'            => 'Yarat',
'editthispage'      => 'Bu səhifəni redaktə et',
'create-this-page'  => 'Bu səhifəni yarat',
'delete'            => 'Sil',
'deletethispage'    => 'Bu səhifəni sil',
'undelete_short'    => '$1 {{PLURAL:$1|dəyişikliyi|dəyişiklikləri}} bərpa et',
'viewdeleted_short' => '{{PLURAL:$1|bir silinmiş redaktəyə|$1 silinmiş redaktəyə}}',
'protect'           => 'Mühafizə et',
'protect_change'    => 'dəyiş',
'protectthispage'   => 'Bu səhifəni mühafizə et',
'unprotect'         => 'Mühafizəni kənarlaşdır',
'unprotectthispage' => 'Bu səhifənin mühafizəsini kənarlaşdır',
'newpage'           => 'Yeni səhifə',
'talkpage'          => 'Bu səhifəni müzakirə et',
'talkpagelinktext'  => 'Müzakirə',
'specialpage'       => 'Xüsusi səhifə',
'personaltools'     => 'Şəxsi alətlər',
'postcomment'       => 'Yeni bölmə',
'articlepage'       => 'Məqaləni nəzərdən keçir',
'talk'              => 'Müzakirə',
'views'             => 'Görünüş',
'toolbox'           => 'Alətlər qutusu',
'userpage'          => 'İstifadəçi səhifəsini göstər',
'projectpage'       => 'Layihə səhifəsini göstər',
'imagepage'         => 'Fayl səhifəsini göstər',
'mediawikipage'     => 'Mesaj səhifəsini göstər',
'templatepage'      => 'Şablon səhifəsini göstər',
'viewhelppage'      => 'Kömək səhifəsini göstər',
'categorypage'      => 'Kateqoriya səhifəsini göstər',
'viewtalkpage'      => 'Müzakirəni göstər',
'otherlanguages'    => 'Başqa dillərdə',
'redirectedfrom'    => '($1 səhifəsindən yönləndirilmişdir)',
'redirectpagesub'   => 'Yönləndirmə səhifəsi',
'lastmodifiedat'    => 'Bu səhifə sonuncu dəfə $2, $1 tarixində redaktə edilib.',
'viewcount'         => 'Bu səhifəyə $1 {{PLURAL:$1|dəfə}} müraciət olunub.',
'protectedpage'     => 'Mühafizəli səhifə',
'jumpto'            => 'Keçid et:',
'jumptonavigation'  => 'naviqasiya',
'jumptosearch'      => 'axtar',
'view-pool-error'   => 'Üzr istəyirik, hazırda serverlər artıq yüklənməyə məruz qalmışdır.
Bu səhifəyə baxmaq üçün həddən artıq müraciət daxil olmuşdur.
Zəhmət olmasa, bir müddət sonra yenidən cəhd edin.

$1',
'pool-timeout'      => 'Blokun gözləmə müddəti bitdi',
'pool-queuefull'    => 'Çıxarış səhifəsi doludur',
'pool-errorunknown' => 'naməlum xəta',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} haqqında',
'aboutpage'            => 'Project:İzah',
'copyright'            => 'Bu məzmun $1 əhatəsindədir.',
'copyrightpage'        => '{{ns:project}}:Müəllif',
'currentevents'        => 'Güncəl hadisələr',
'currentevents-url'    => 'Project:Güncəl hadisələr',
'disclaimers'          => 'Məsuliyyətdən imtina',
'disclaimerpage'       => 'Project:Məsuliyyətdən imtina',
'edithelp'             => 'Redaktə kömək',
'edithelppage'         => 'Help:Redaktə',
'helppage'             => 'Help:Mündəricat',
'mainpage'             => 'Ana Səhifə',
'mainpage-description' => 'Ana Səhifə',
'policy-url'           => 'Project:Qaydalar',
'portal'               => 'Kənd meydanı',
'portal-url'           => 'Project:Kənd meydanı',
'privacy'              => 'Gizlilik prinsipi',
'privacypage'          => 'Project:Gizlilik prinsipi',

'badaccess'        => 'İcazə xətası',
'badaccess-group0' => 'Bu fəaliyyəti icra etmək səlahiyyətiniz yoxdur.',
'badaccess-groups' => ' Bu fəaliyyəti, yalnız $1 {{PLURAL:$2|qrupundakı|qruplarındakı}} istifadəçilər icra edə bilərlər.',

'versionrequired'     => 'MediyaViki $1 versiyası lazımdır',
'versionrequiredtext' => 'Bu səhifəni istifadə etmək üçün MediaWikinin $1 versiyası tələb olunur.
Bax: [[Special:Version|Versiyalar]].',

'ok'                      => 'OK',
'pagetitle'               => '$1 - {{SITENAME}}',
'pagetitle-view-mainpage' => '{{SITENAME}}',
'retrievedfrom'           => 'Mənbə — "$1"',
'youhavenewmessages'      => 'Hal-hazırda $1 var. ($2)',
'newmessageslink'         => 'yeni ismarıclar',
'newmessagesdifflink'     => 'Sonuncu və əvvəlki versiya arasındakı fərq',
'youhavenewmessagesmulti' => '"$1"da yeni mesajınız var.',
'editsection'             => 'redaktə',
'editsection-brackets'    => '[$1]',
'editold'                 => 'redaktə',
'viewsourceold'           => 'başlanğıc kodu nəzərdən keçir',
'editlink'                => 'redaktə',
'viewsourcelink'          => 'başlanğıc kodu nəzərdən keçir',
'editsectionhint'         => '$1 bölməsini redaktə et',
'toc'                     => 'Mündəricat',
'showtoc'                 => 'göstər',
'hidetoc'                 => 'gizlə',
'collapsible-collapse'    => 'Gizlə',
'collapsible-expand'      => 'Göstər',
'thisisdeleted'           => '$1 bax və ya bərpa et?',
'viewdeleted'             => '$1 göstərilsin?',
'restorelink'             => '{{PLURAL:$1|bir silinmiş redaktəyə|$1 silinmiş redaktəyə}}',
'feedlinks'               => 'Kanal növü:',
'feed-invalid'            => 'Yanlış qeydiyyat kanalı növü.',
'feed-unavailable'        => 'Sindikasiya xətləri etibarsızdır',
'site-rss-feed'           => '$1 — RSS-lent',
'site-atom-feed'          => '$1 — Atom-lent',
'page-rss-feed'           => '"$1" — RSS-lent',
'page-atom-feed'          => '"$1" — Atom-lent',
'feed-atom'               => 'Atom',
'feed-rss'                => 'RSS',
'red-link-title'          => '$1 (səhifə mövcud deyil)',
'sort-descending'         => 'Azalan ardıcıllıq',
'sort-ascending'          => 'Artan ardıcıllıq',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Məqalə',
'nstab-user'      => 'İstifadəçi səhifəsi',
'nstab-media'     => 'Media səhifəsi',
'nstab-special'   => 'Xüsusi səhifə',
'nstab-project'   => 'Layihə səhifəsi',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Məlumat',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Kömək',
'nstab-category'  => 'Kateqoriya',

# Main script and global functions
'nosuchaction'      => 'Bu cür fəaliyyət mövcud deyil.',
'nosuchactiontext'  => 'URL-də göstərilən əməliyyat düzgün deyil.
Ola bilsin ki, URL-i səhv daxil etmisiniz və ya düzgün olmayan keçiddən istifadə etmisiniz.
Bu həmçinin, {{SITENAME}} saytındakı xətanın göstəricisi ola bilər.',
'nosuchspecialpage' => 'Bu adda xüsusi səhifə mövcud deyil',
'nospecialpagetext' => '<strong>Axtardığınız xüsusi səhifə mövcud deyil.</strong>

Mövcud xüsusi səhifələrin siyahısı: [[Special:SpecialPages|Xüsusi səhifələr]].',

# General errors
'error'                => 'Xəta',
'databaseerror'        => 'Verilənlər bazası xətası',
'dberrortext'          => 'Verilənlər bazası sorğusunda sintaksis xətası yarandı.
Bu proqram təminatındakı xəta ilə əlaqədar ola bilər.
Verilənlər bazasına sonuncu sorğu "<tt>$2</tt>" funksiyasından 
yaranan <blockquote><tt>$1</tt></blockquote>.
Verilənlər bazasının göstərdiyi xəta "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Verilənlər bazası sorğusunda sintaksis xətası yarandı.
Verilənlər bazasına sonuncu sorğu:
"$1"
"$2" funksiyasından yaranmışdır.
Verilənlər bazasının göstərdiyi xəta "$3: $4"',
'laggedslavemode'      => "'''Xəbərdarlıq:''' Səhifə son əlavələri əks etdirməyə bilər.",
'readonly'             => 'Verilənlər bazası bloklanıb',
'enterlockreason'      => 'Bloklamanın səbəbini və nəzərdə tutulan müddətini qeyd edin',
'readonlytext'         => 'Verilənlər bazası ehtimal ki, adi təmir işləri ilə əlaqədar müvəqqəti olaraq yeni məqalələr və dəyişikliklər üçün bağlanmışdır.
Verilənlər bazasını bloklayan operatorun izahatı: $1',
'missing-article'      => 'Məlumat bazası, tapılması istənən "$1" $2 adlı səhifəyə aid mətni tapa bilmədi.

Bu vəziyyət səhifənin, silinmiş bir səhifənin keçmiş versiyası olmasından qaynaqlana bilər.

Əgər niyə bu deyilsə, proqramda bir səhv ilə qarşılaşmış ola bilərsiniz.
Xahiş edirik bunu bir [[Special:ListUsers/sysop|İdarəçilərə]], URL not edərək göndərin.',
'missingarticle-rev'   => '(təftiş № $1)',
'missingarticle-diff'  => '(fərq: $1, $2)',
'readonly_lag'         => 'Məlumatlar bazasının ikinci dərəcəli serveri əsas serverlə əlaqə yaradanadək məlumatlar bazası avtomatik olaraq bloklanmışdır',
'internalerror'        => 'Daxili xəta',
'internalerror_info'   => 'Daxili xəta: $1',
'fileappenderrorread'  => 'Daxil olmuş edilərkən "$1" oxuna bilmir.',
'fileappenderror'      => '"$1" faylı "$2" faylına əlavə edilə bilmir.',
'filecopyerror'        => '"$1" faylı "$2" faylına kopiyalanmır.',
'filerenameerror'      => '«$1» faylının adını «$2» dəyişmək mümkün deyil',
'filedeleteerror'      => '"$1" fayılını silə bilmədi.',
'directorycreateerror' => '"$1" direktoriyasını yaratmaq mümkün deyil',
'filenotfound'         => '"$1" faylını tapa bilmədi.',
'fileexistserror'      => '"$1" faylına yazmaq mümkün deyil: fayl artıq mövcuddur.',
'unexpected'           => 'Uyğunsuzluq: "$1"="$2".',
'formerror'            => 'Xəta: Formanın məlumatlarını əks etdirmək qeydi-mümkündür',
'badarticleerror'      => 'Yerinə yetirmək istədiyiniz fəaliyyətin icrası bu səhifədə mümkün deyil.',
'cannotdelete'         => 'İstədiyiniz "$1" səhifə və ya faylını silmək mümkün deyil.
Bu səhifə və ya fayl başqa bir istifadəçi tərəfindən silinmiş ola bilər.',
'badtitle'             => 'Səhv başlıq',
'badtitletext'         => 'Axtarılan səhifə adı səhvdir və ya boşdur, ya da düzgün olmayan dillərarası, yaxud vikilərarası keçid istifadə edilib.
Başlıqlarda istifadə edilməsi qadağan olunan bir və ya daha çox simvol istifadə edilmiş ola bilər.',
'perfcached'           => 'Aşağıdakı məlumatlar keş yaddaşdan götürülmüşdür və bu səbəbdən aktual olmaya bilər.',
'perfcachedts'         => 'Aşağıdakı məlumatlar keş yaddaşdan götürülmüşdür və sonuncu dəfə $1 tarixində yenilənmişdir.',
'querypage-no-updates' => 'Bu an üçün güncəlləmələr sıradan çıxdı. Buradakı məlumat dərhal yenilənməyəcək.',
'wrong_wfQuery_params' => 'wfQuery() funksiyası üçün qəbuledilməz parametrlər <br />
Funksiya: $1<br />
Sorğu: $2',
'viewsource'           => 'Mənbə göstər',
'viewsourcefor'        => '$1 üçün',
'actionthrottled'      => 'Sürət məhdudiyyəti',
'actionthrottledtext'  => 'Anti-spam hərəkətləri səbəbilə, bir hərəkəti qısa bir zaman aralığında çoxetməniz əngəlləndi, və siz həddi aşmısınız. Lütfən bir neçə dəqiqə sonra yenidən yoxlayın.',
'protectedpagetext'    => 'Bu səhifə redaktə üçün bağlıdır.',
'viewsourcetext'       => 'Siz bu səhifənin məzmununu görə və köçürə bilərsiniz:',
'protectedinterface'   => 'Bu səhifədə proqram təminatı üçün sistem məlumatları var və sui-istifadənin qarşısını almaq üçün mühafizə olunmalıdır.',
'editinginterface'     => "'''Diqqət!''' Siz proqram təminatı interfeysinin mətn olan səhifəsini redaktə edirsiniz.
Onun dəyişdirilməsi digər istifadəçilərin interfeysinin xarici görünüşünə təsir göstərir.
Tərcümə üçün daha yaxşı olar ki, MediaWiki-nin lokallaşması üçün olan [//translatewiki.net/wiki/Main_Page?setlang=az translatewiki.net]  layihəsindən istifadə edəsiniz.",
'sqlhidden'            => '(SQL gizli sorğu)',
'cascadeprotected'     => 'Səhifə mühafizə olunub, çünki o kaskad mühafizə olunan {{PLURAL:$1|növbəti səhifəyə|növbəti səhifələrə}} qoşulub:
$2',
'namespaceprotected'   => 'Sizin adlarında $1 olan məqalələrdə redaktə etməyə icazəniz yoxdur.',
'ns-specialprotected'  => 'Xüsusi səhifələr redaktə oluna bilməz.',
'titleprotected'       => 'Bu adda səhifənin yaradılması istifadəçi [[User:$1|$1]] tərəfindən qadağan edilmişdir.
Göstərilən səbəb: "\'\'$2\'\'".',

# Virus scanner
'virus-badscanner'     => "Düzgün olmayan konfiqurasiya: naməlum ''$1'' virus yoxlayanı",
'virus-scanfailed'     => 'Yoxlama başa çatmadı (kod $1)',
'virus-unknownscanner' => 'naməlum antivirus',

# Login and logout pages
'logouttext'                 => "'''Sistemdən çıxdınız.'''

Siz {{SITENAME}}nı anonim olaraq istifadə etməyə davam edə bilər və ya eyni, yaxud başqa istifadəçi adı ilə [[Special:UserLogin|yenidən daxil ola]] bilərsiniz. Veb-brauzerin keş yaddaşını təmizləyənədək bəzi səhifələr hələ də sistemdə imişsiniz kimi görünə bilər.",
'welcomecreation'            => '== $1, xoş gəlmişsiniz! ==
Hesabınız yaradıldı.
[[Special:Preferences|{{SITENAME}} nizamlamalarınızı]] dəyişdirməyi unutmayın.',
'yourname'                   => 'İstifadəçi adı',
'yourpassword'               => 'Parol:',
'yourpasswordagain'          => 'Parolu təkrar yazın:',
'remembermypassword'         => 'Məni bu kompüterdə xatırla (maksimum $1 {{PLURAL:$1|gün|gün}})',
'securelogin-stick-https'    => 'Loqini daxil etdikdən sonra HTTPS-lə əlaqədə qal',
'yourdomainname'             => 'Sizin domain',
'externaldberror'            => 'Ya verilənlər bazasının doğruluğunu yoxlamada xəta baş verib, yaxud da siz xarici akkauntu yeniləməyi bacarmırsınız.',
'login'                      => 'Daxil ol',
'nav-login-createaccount'    => 'Daxil ol / hesab yarat',
'loginprompt'                => '{{SITENAME}}-ya daxil olmaq üçün "veb kökələrinin" (cookies) istifadəsinə icazə verilməlidir.',
'userlogin'                  => 'Daxil ol və ya istifadəçi yarat',
'userloginnocreate'          => 'Daxil ol',
'logout'                     => 'Çıxış',
'userlogout'                 => 'Çıxış',
'notloggedin'                => 'Daxil olmamısınız',
'nologin'                    => "İstifadəçi hesabınız yoxdur? '''$1'''.",
'nologinlink'                => 'hesab açın',
'createaccount'              => 'Hesab aç',
'gotaccount'                 => "Giriş hesabınız varsa '''$1'''.",
'gotaccountlink'             => 'daxil olun',
'userlogin-resetlink'        => 'Daxilolma məlumatlarınızı unutmusunuz?',
'createaccountmail'          => 'e-məktub ilə',
'createaccountreason'        => 'Səbəb:',
'badretype'                  => 'Daxil etdiyiniz parol uyğun gəlmir.',
'userexists'                 => 'Daxil edilmiş ad artıq istifadədədir.
Lütfən başqa ad seçin.',
'loginerror'                 => 'Daxil olunma xətası',
'createaccounterror'         => '$1 Hesab açılmadı',
'nocookiesnew'               => 'İstifadəçi qeydiyyatı yaradıldı, lakin daxil ola bilmədiniz.
{{SITENAME}} iştirakçıların təqdim olunması üçün "cookie"lərdən istifadə edir.
Siz "cookie"lərin qəbuluna qadağa qoymusunuz.
Lütfən, onları qəbul etməyə icazə verdikdən sonra yeni istifadəçi adı və parolunuzla daxil olun.',
'nocookieslogin'             => '{{SITENAME}} istifadəçilərin daxil ola bilməsi üçün "cookie"lərdən istifadə edir. Siz "cookie"lərin qəbuluna qadağa qoymusunuz. Lütfən, onların qəbuluna icazə verin və bir daha daxil olmağa cəhd edin.',
'nocookiesfornew'            => 'İstifadəçinin akkauntu yaradılmayıb, ona görə də biz onun mənbəsini təsdiqləyə bilmədik.
Kukların qoşulmasına əmin olduqdan sonra səhifəni yeniləyib bir daha sınayın.',
'nocookiesforlogin'          => '{{int:nocookieslogin}}',
'noname'                     => 'Siz mövcud olan istifadəçi adı daxil etməmisiniz.',
'loginsuccesstitle'          => 'Daxil oldunuz',
'loginsuccess'               => "'''\"\$1\" adı ilə {{SITENAME}}ya daxil oldunuz.'''",
'nosuchuser'                 => '"$1" adında istifadəçi mövcud deyil.
İstifadəçi adları hərflərin böyük və ya kiçik olmasına həssasdırlar.
Düzgün yazdığına əmin ol, yaxud [[Special:UserLogin/signup|yeni hesab aç]].',
'nosuchusershort'            => '"$1" adında istifadəçi mövcud deyil.
Düzgün yazdığına əmin ol.',
'nouserspecified'            => 'İstifadəçi adı daxil etməlisiniz.',
'login-userblocked'          => 'Bu istifadəçi bloklanıb. Sistemə giriş üçün icazə verilmir.',
'wrongpassword'              => 'Səhv parol. Təkrar yazın.',
'wrongpasswordempty'         => 'Parol boş. Təkrar yazın.',
'passwordtooshort'           => 'Parolda ən azı {{PLURAL:$1|1 hərf yaxud simvol|$1 hərf yaxud simvol}} olmalıdır.',
'password-name-match'        => 'Parol adınızdan fərqli olmalıdır.',
'password-login-forbidden'   => 'Bu istifadəçi adından və paroldan istifadə qadağan olunub.',
'mailmypassword'             => 'E-mail ilə yeni parol göndər',
'passwordremindertitle'      => '{{SITENAME}} parol xatırladıcı',
'noemail'                    => '"$1" adlı istifadəçi e-poçt ünvanını qeyd etməmişdir.',
'noemailcreate'              => 'Düzgün e-poçt ünvanı qeyd etməlisiniz',
'passwordsent'               => 'Yeni parol "$1" üçün qeydiyyata alınan e-poçt ünvanına göndərilmişdir.
Xahiş edirik, e-məktubu aldıqdan sonra yenidən daxil olasınız.',
'blocked-mailpassword'       => 'İP ünvanınız bloklu olduğuna görə, yeni parol göndərmə mümkün deyil.',
'mailerror'                  => 'Məktub göndərmə xətası: $1',
'acct_creation_throttle_hit' => 'Sizin IP ünvanınızdan bu viki-də son bir gün ərzində {{PLURAL:$1|1 hesab|$1 hesab}} açılmışdır. Bu bir gün ərzində icazə verilən maksimum say olduğu üçün, indiki anda daha çox hesab aça bilməzsiniz.',
'emailauthenticated'         => 'E-poçt ünvanınız $2 saat $3 tarixində təsdiq edilib.',
'emailnotauthenticated'      => 'E-poçt ünvanınız təsdiq edilməyib.
Aşağıdakı xidmətlərin heç biri üçün Sizə e-məktub göndərilməyəcək.',
'noemailprefs'               => 'Bu xidmətlərdən yararlanmaq üçün nizamlamalarında E-məktub ünvanını göstər.',
'emailconfirmlink'           => 'E-poçt ünvanını təsdiq et',
'invalidemailaddress'        => 'E-poçt ünvanınızı qeyri-düzgün formatda olduğu üçün qəbul edə bilmirik.
Xahiş edirik düzgün formatlı ünvan daxil edin və ya bu sahəni boş qoyun.',
'accountcreated'             => 'Hesab yaradıldı',
'accountcreatedtext'         => '$1 üçün istifadəçi hesabı yaradıldı.',
'createaccount-title'        => '{{SITENAME}} hesabın yaradılması',
'usernamehasherror'          => 'İstifadəçi adında "diyez" simvolunun istifadəsi mümkün deyil',
'login-throttled'            => 'Sistemə daxil olmaq üçün həddən artıq cəhd etmisiniz.
Yeni cəhd etməzdən əvvəl bir qədər gözləyin.',
'login-abort-generic'        => 'Giriş uğursuz alındı - Rədd',
'loginlanguagelabel'         => 'Dil: $1',
'suspicious-userlogout'      => 'Sizin çıxış üçün cəhdiniz uğursuz alındı. Bu, brouzerin yaxud proksi-keşləmənin düzgün işləməməsindən qaynaqlanır.',

# E-mail sending
'php-mail-error-unknown' => 'PHP-nin mail() funksiyasında naməlum xəta',

# Change password dialog
'resetpass'                 => 'Parolu dəyiş',
'resetpass_announce'        => 'Siz sistemə müvəqqəti elektron poçt kodu ilə daxil olmusunuz.
Sistemə daxil olmanı yekunlaşdırmaq üçün yeni parolu bura yazmalısınız:',
'resetpass_text'            => '<!-- Şərhinizi bura daxil edin -->',
'resetpass_header'          => 'İstifadəçi parolunu dəyiş',
'oldpassword'               => 'Köhnə parol:',
'newpassword'               => 'Yeni parol:',
'retypenew'                 => 'Yeni parolu təkrar yazın:',
'resetpass_submit'          => 'Parol yaradın və sistemə daxil olun',
'resetpass_success'         => 'Parolunuz dəyişdirldi! Hazırda sistemə daxil olursunuz...',
'resetpass_forbidden'       => 'Parolu dəyişmək mümkün deyil',
'resetpass-no-info'         => 'Bu səhifəni birbaşa açmaq üçün sistemə daxil olmalısınız.',
'resetpass-submit-loggedin' => 'Parolu dəyiş',
'resetpass-submit-cancel'   => 'Ləğv et',
'resetpass-wrong-oldpass'   => 'Müvəqqəti və ya daimi parolda yanlışlıq var.
Ola bilər siz parolu müvəffəqiyyətlə dəyişmisiniz yaxud müvəqqəti parol üçün müraciət etmisiniz.',
'resetpass-temp-password'   => 'Müvəqqəti parol:',

# Special:PasswordReset
'passwordreset'              => 'Parolu yenilə',
'passwordreset-text'         => 'Akkauntunuz haqqında xatırlatmanı elektron poçt vasitəsilə ala bilməniz üçün bu formanı doldurun.',
'passwordreset-legend'       => 'Parolu yenilə',
'passwordreset-disabled'     => 'Yenidən qurulma parolu bu vikidə işləmir',
'passwordreset-pretext'      => '{{PLURAL:$1||Enter one of the pieces of data below}}',
'passwordreset-username'     => 'İstifadəçi adı:',
'passwordreset-email'        => 'E-mail ünvanı:',
'passwordreset-emailtitle'   => '{{SITENAME}} hesabın yaradılması',
'passwordreset-emailelement' => 'İstifadəçi adı: $1
Müvəqqəti parol: $2',
'passwordreset-emailsent'    => 'Xəbərdarlıq məktubu e-maillə göndərildi.',

# Edit page toolbar
'bold_sample'     => 'Qalın mətn',
'bold_tip'        => 'Qalın mətn',
'italic_sample'   => 'Kursiv mətn',
'italic_tip'      => 'Kursiv mətn',
'link_sample'     => 'Bağlantı başlığı',
'link_tip'        => 'Daxili bağlantı',
'extlink_sample'  => 'http://www.example.com başlıq',
'extlink_tip'     => 'Xarici səhifə (http:// ekini unutma)',
'headline_sample' => 'Başlıq mətni',
'headline_tip'    => '2. səviyyə başlıq',
'nowiki_sample'   => 'Formatlaşdırılmamış mətni bura daxil edin',
'nowiki_tip'      => 'Viki formatını sayma',
'image_sample'    => 'Misal.jpg',
'image_tip'       => 'Şəkil əlavə etmə',
'media_sample'    => 'Misal.ogg',
'media_tip'       => 'Media-fayla keçid',
'sig_tip'         => 'İmza və vaxt',
'hr_tip'          => 'Horizontal cizgi',

# Edit pages
'summary'                          => 'Qısa məzmun:',
'subject'                          => 'Mövzu/başlıq:',
'minoredit'                        => 'Kiçik redaktə',
'watchthis'                        => 'Bu səhifəni izlə',
'savearticle'                      => 'Səhifəni qeyd et',
'preview'                          => 'Sınaq göstərişi',
'showpreview'                      => 'Sınaq göstərişi',
'showlivepreview'                  => 'Canlı sınaq göstərişi',
'showdiff'                         => 'Dəyişiklikləri göstər',
'anoneditwarning'                  => "'''DİQQƏT!''' Siz özünüzü sistemə təqdim etməmisiniz. Sizin IP ünvanınız bu səhifənin tarixçəsinə qeyd olunacaq.",
'anonpreviewwarning'               => 'Sistemə daxil olmamısınız. "Səhifəni qeyd et" düyməsini bassanız IP ünvanınız səhifənin tarixçəsində qeyd olunacaq.',
'missingsummary'                   => "'''Xatırlatma.''' Siz dəyişikliklərin qısa şərhini verməmisiniz. \"Səhifəni qeyd et\" düyməsinə təkrar basandan sonra sizin dəyişiklikləriniz şərhsiz qeyd olunacaq.",
'missingcommenttext'               => 'Zəhmət olmasa, aşağıda şərhinizi yazın.',
'summary-preview'                  => 'Xülasə baxış',
'subject-preview'                  => 'Sərlövhə belə olacaq:',
'blockedtitle'                     => 'İstifadəçi bloklanıb',
'blockednoreason'                  => 'səbəb göstərilməyib',
'blockedoriginalsource'            => "'''$1''' mənbəyi aşağıda göstərilib:",
'blockededitsource'                => "Aşağıda '''$1''' səhifəsində etdiyiniz '''dəyişikliklərin''' mətni göstərilib:",
'whitelistedittitle'               => 'Redaktə üçün daxil olmalısınız',
'whitelistedittext'                => 'Dəyişiklik edə bilmək üçün $1.',
'confirmedittext'                  => 'Siz elektron ünvanınızı səhifədə dəyişiklik etməzdən əvvəl göstərməlisiniz.
Zəhmət olmasa elektron ünvanınızı [[Special:Preferences|istifadəçi nizamlaması]] səhifənizdə göstərib təsdiq ediniz.',
'nosuchsectiontitle'               => 'Belə bölmə yoxdur.',
'nosuchsectiontext'                => 'Siz mövcud olmayan alt səhifəni redaktə etməyə çalışırsınız.
Həmin alt səhifə ola bilsin siz səhifəyə baxan zaman silinib.',
'loginreqtitle'                    => 'Daxil olmalısınız',
'loginreqlink'                     => 'daxil olmalısınız',
'loginreqpagetext'                 => 'Digər səhifələri görmək üçün $1 olmalısınız.',
'accmailtitle'                     => 'Parol göndərildi.',
'accmailtext'                      => "[[User talk:$1|$1]] üçün təsadüfi yolla yaradılmış parol $2 ünvanına göndərildi.
Hesabınıza daxil olduqdan sonra, parolunuzu ''[[Special:ChangePassword|parolu dəyiş]]'' səhifəsində dəyişdirə bilərsiniz.",
'newarticle'                       => '(Yeni)',
'newarticletext'                   => "Mövcud olmayan səhifəyə olan keçidi izlədiniz. Aşağıdakı sahəyə məzmununu yazaraq bu səhifəni '''siz''' yarada bilərsiniz. (əlavə məlumat üçün [[{{MediaWiki:Helppage}}|kömək səhifəsinə]] baxın). Əgər bu səhifəyə səhvən gəlmisinizsə sadəcə olaraq brauzerin '''geri''' düyməsinə vurun.",
'anontalkpagetext'                 => "----''Bu səhifə qeydiyyatdan keçməmiş və ya daxil olmamış anonim istifadəçiyə aid müzakirə səhifəsidir.
Ona görə bu istifadəçini rəqəmlərdən ibarət IP ünvanı ilə müəyyən etmək məcburiyyətindəyik.
Belə IP ünvan bir neçə fərd tərəfindən istifadədə ola bilər.
Əgər siz anonim istifadəçisinizsə və bu mesajın sizə aid olmadığını düşünürsünüzsə, onda  [[Special:UserLogin/signup|qeydiyyatdan keçin]] və ya [[Special:UserLogin|daxi olun]].''",
'noarticletext'                    => 'Hal-hazırda bu səhifə boşdur. Başqa səhifələrdə eyni adda səhifəni [[Special:Search/{{PAGENAME}}| axtara]], əlaqəli qeydlərə
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} baxa],
və ya səhifəni [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaktə]</span> edə bilərsiniz.',
'noarticletext-nopermission'       => 'Hal-hazırda bu səhifə boşdur. Başqa səhifələrdə eyni adda səhifəni [[Special:Search/{{PAGENAME}}| axtara]], əlaqəli qeydlərə
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} baxa],
və ya səhifəni [{{fullurl:{{FULLPAGENAME}}|action=edit}} redaktə]</span> edə bilərsiniz.',
'userpage-userdoesnotexist'        => '"<nowiki>$1</nowiki>" istifadəçi adı qeydiyyata alınmayıb.
Əgər siz bu səhifəni yaratmaq/redaktə etmək istəyirsinizsə, xahiş edirik bunu yoxlayın.',
'userpage-userdoesnotexist-view'   => '"$1" istifadəçi hesabı qeydiyyatda deyil',
'blocked-notice-logextract'        => 'Bu istifadəçi hal-hazırda bloklanmışdır.
Bloklama qeydlərinin sonuncusu aşağıda göstərilmişdir:',
'usercssyoucanpreview'             => "'''İpucu:''' Qeyd etmədən əvvəl \"{{int:showpreview}}\"ə klikləyərək yeni CSSinizi yoxlayın.",
'userjsyoucanpreview'              => "'''İpucu:''' Qeyd etmədən əvvəl \"{{int:showpreview}}\"ə klikləyərək yeni JavaScriptinizi yoxlayın.",
'usercsspreview'                   => "''Xatırladırıq ki, siz yalnız CSS-də sınaq göstərişi etmisiniz.'''
'''Bu hələ yaddaşda saxlanılmayıb!'''",
'userjspreview'                    => "''Xatırladırıq ki, siz yalnız JavaScript-də test/sınaq göstərişi etmisiniz.'''
'''Bu hələ yaddaşda saxlanılmayıb!'''",
'sitecsspreview'                   => "''Xatırladırıq ki, siz yalnız CSS-də sınaq göstərişi etmisiniz.'''
'''Bu hələ yaddaşda saxlanılmayıb!'''",
'sitejspreview'                    => "''Xatırladırıq ki, siz yalnız JavaScript kodunda sınaq göstərişi etmisiniz.'''
'''Bu hələ yaddaşda saxlanılmayıb!'''",
'updated'                          => '(yeniləndi)',
'note'                             => "'''Qeyd:'''",
'previewnote'                      => "'''Bu yalnız sınaq göstərişidir; dəyişikliklər hal-hazırda qeyd edilməmişdir!'''",
'previewconflict'                  => 'Bu sınaq göstərişidir və yaddaşda saxlayacağınız təqdirdə mətnin redaktə səhifəsinin yuxarı hissəsində nəticənin necə olacağını göstərir.',
'session_fail_preview'             => "'''Üzr istəyirik! Sizin redaktəniz saxlanılmadı. Serverdə identifikasiyanızla bağlı problemlər yaranmışdır. Lütfən bir daha təkrar edin. Problem həll olunmazsa hesabınızdan çıxın və yenidən daxil olun.'''",
'editing'                          => 'Redaktə $1',
'editingsection'                   => 'Redaktə $1 (bölmə)',
'editingcomment'                   => 'Redaktə et $1 (yeni bölmə)',
'editconflict'                     => 'Eyni vaxtda redaktə: $1',
'yourtext'                         => 'Mətniniz',
'storedversion'                    => 'Qeyd edilmiş versiya',
'editingold'                       => "'''DİQQƏT! Siz bu səhifənin köhnə versiyasını redaktə edirsiniz. Məqaləni yaddaşda saxlayacağınız halda bu versiyadan sonra edilmiş hər bir dəyişiklik itiriləcək.'''",
'yourdiff'                         => 'Fərqlər',
'copyrightwarning'                 => 'Xahiş olunur diqqətə alasınız ki, {{SITENAME}}dakı bütün fəaliyyətləriniz $2 lisenziyasına tabe olduğu hesab edilir (təfərrüat üçün bax: $1). Əgər yazdıqlarınızın əsaslı şəkildə redaktə edilməsini və istənildiyi vaxt başqa yerə ötürülməsini istəmirsinizsə, yazılarınızı burada dərc etməyin.
<br />
Siz eyni zamanda söz verirsiniz ki, bu yazıları siz özünüz yazmısınız və ya onları hamıya açıq mühitdən ya da buna bənzər mənbədən köçürmüsünüz.

----

<div style="font-weight: bold; font-size: 110%; color:red;">MÜƏLLİF HÜQUQLARI İLƏ QORUNMUŞ HEÇ BİR İŞİ İCAZƏSİZ DƏRC ETMƏYİN!</div>',
'semiprotectedpagewarning'         => "'''Qeyd:''' Bu səhifə mühafizəli olduğu üçün yalnız qeydiyyatdan keçmiş istifadəçilər redaktə edə bilərlər.",
'titleprotectedwarning'            => "'''DİQQƏT! Bu səhifə mühafizəlidir, yalnız [[Special:ListGroupRights|icazəsi olan]] istifadəçilər onu redaktə edə bilərlər.'''",
'templatesused'                    => 'Bu səhifədə istifadə edilmiş {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedpreview'             => 'Bu sınaq göstərişində istifadə edilmiş {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedsection'             => 'Bu bölmədə istifadə edilmiş {{PLURAL:$1|şablon|şablonlar}}',
'template-protected'               => '(mühafizə)',
'template-semiprotected'           => '(yarım-mühafizə)',
'hiddencategories'                 => 'Bu səhifə {{PLURAL:$1|1 gizli kateqoriyaya|$1 gizli kateqoriyaya}} aiddir:',
'edittools-upload'                 => '-',
'nocreatetitle'                    => 'Səhifə yaratma məhdudlaşdırılıb.',
'nocreatetext'                     => '{{SITENAME}} saytında yeni səhifələrin yaradılması imkanları məhdudlaşdırılıb.
Siz geri qayıdıb mövcud səhifəni və ya  [[Special:UserLogin|sistemə təqdim olunma və ya yeni hesab açmaq]] səhifəsini redaktə edə bilərsiniz.',
'nocreate-loggedin'                => 'Sizin yeni səhifələr yaratmaq üçün icazəniz yoxdur.',
'sectioneditnotsupported-title'    => 'Bölüm redaktəsi dəstəklənmir',
'sectioneditnotsupported-text'     => 'Bölüm redaktəsi bu səhifədə dəstəklənmir.',
'permissionserrors'                => 'İcazə xətası',
'permissionserrorstext'            => 'Siz, bunu aşağıdakı {{PLURAL:$1|səbəbə|səbəblərə}} görə edə bilməzsiniz:',
'permissionserrorstext-withaction' => 'Aşağıdakı {{PLURAL:$1|səbəbə|səbəblərə}} görə $2 hüququnuz yoxdur:',
'recreate-moveddeleted-warn'       => "'''Diqqət! Siz əvvəllər silinmiş səhifəni bərpa etmək istəyirsiz.'''

Bu səhifəni yenidən yaratmağın nə qədər zəruri olduğunu bir daha yoxlayın.
Bu səhifə üçün silmə qeydləri aşağıda göstərilmişdir:",
'moveddeleted-notice'              => 'Bu səhifə silinmişdir.
Məlumat üçün aşağıda bu səhifənin tarixçəsindən müvafiq silmə qeydləri göstərilmişdir.',
'log-fulllog'                      => 'Bütöv məlumatı göstər',
'edit-hook-aborted'                => 'Düzəlişlər qarmaq-prosedur tərəfindən geri qaytarılıb.
Əlavə izahat verilməyib.',
'edit-gone-missing'                => 'Səhifəni yeniləmək mümkün deyil.
Çox güman ki, səhifə silinmişdir.',
'edit-conflict'                    => 'Düzəlişlər münaqişəsi',
'edit-no-change'                   => 'Sizin redaktələr qeydə alınmamışdır. Belə ki, mətndə heç bir düzəliş edilməmişdir.',
'edit-already-exists'              => 'Yeni səhifəni yaratmaq mümkün deyil.
Belə ki, bu adda səhifə artıq mövcuddur.',

# Parser/template warnings
'expensive-parserfunction-category'       => 'Kifayət qədər böyük sayda genişresurslu funksiyaların müraciət olunduğu səhifələr',
'post-expand-template-inclusion-warning'  => "'''DİQQƏT!''' Daxil edilən şablonların həcmi həddindən artıq böyükdür.
Bəzi şablonlar əlavə olunmayacaq.",
'post-expand-template-inclusion-category' => 'Şablonun daxil olduğu səhifələrin ölçüsü böyükdür.',
'post-expand-template-argument-category'  => 'Şablonlarda buraxılmış arqumentlərin mövcud olduğu səhifələr',
'parser-template-loop-warning'            => '[[$1]]: Şablonda düyün tapıldı',
'parser-template-recursion-depth-warning' => '($1) Şablonda dərinlik limiti keçildi',
'language-converter-depth-warning'        => '($1) Dil konvertorunun limiti keçildi',

# "Undo" feature
'undo-failure' => 'Dəyişikliklərin toqquşması nəticəsində geriyə qaytarma işi uğursuz oldu.',
'undo-norev'   => 'Düzəlişlər geri qaytarıla bilinmir, çünki onlar ya mövcüd deyil, ya da silinib.',
'undo-summary' => '$1 dəyişikliyi [[Special:Contributions/$2|$2]] ([[User talk:$2|Müzakirə]]) tərəfindən geri alındı.',

# Account creation failure
'cantcreateaccounttitle' => 'Hesab açılmır.',
'cantcreateaccount-text' => "Bu IP ünvanından ('''$1''') istifadəçi hesabı yaradılması [[User:$3|$3]] tərəfindən əngəllənmişdir.

$3 tərəfindən verilən səbəb ''$2''",

# History pages
'viewpagelogs'           => 'Bu səhifə ilə bağlı qeydlərə bax',
'nohistory'              => 'Bu səhifənin dəyişikliklər tarixçəsi mövcud deyil.',
'currentrev'             => 'Hal-hazırkı versiya',
'currentrev-asof'        => 'Səhifəsinin $1 tarixinə olan son halı',
'revisionasof'           => '$1 versiyası',
'revision-info'          => '$2 tərəfindən yaradılmış $1 tarixli dəyişiklik',
'previousrevision'       => '←Əvvəlki versiya',
'nextrevision'           => 'Sonrakı versiya→',
'currentrevisionlink'    => 'Hal-hazırkı versiyanı göstər',
'cur'                    => 'hh',
'next'                   => 'sonrakı',
'last'                   => 'son',
'page_first'             => 'birinci',
'page_last'              => 'sonuncu',
'histlegend'             => "Fərqə bax: müqayisə etmək istədiyiniz versiyaların yanındakı dairələri işarələyin və \"Enter\"ə və ya \"müqayisə et\" düyməsinə basın.<br />
Açıqlama: '''(hh)''' — hal-hazırkı versiya ilə aradakı fərq, '''(son)''' — əvvəlki versiya ilə aradakı fərq, '''k''' — kiçik redaktə.",
'history-fieldset-title' => 'Tarixçəni nəzərdən keçir',
'history-show-deleted'   => 'Yalnız silinənlər',
'histfirst'              => 'Ən əvvəlki',
'histlast'               => 'Ən sonuncu',
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(boş)',

# Revision feed
'history-feed-title'          => 'Redaktə tarixçəsi',
'history-feed-description'    => 'Vikidə bu səhifənin dəyişikliklər tarixçəsi',
'history-feed-item-nocomment' => '$1-dən $2-yə',
'history-feed-empty'          => 'Axtardığınız səhifə mövcud deyil.
Çox guman ki, bu səhifə silinib və ya onun adı dəyişdirilib.
Vikidə buna bənzər səhifələri [[Special:Search|axtarmağa]] cəhd edin.',

# Revision deletion
'rev-deleted-comment'         => '(şərhlər silindi)',
'rev-deleted-user'            => '(İstifadəçi adı silindi)',
'rev-deleted-event'           => '(qeyd silindi)',
'rev-deleted-user-contribs'   => '[istifadəçi adı və ya IP ünvanı silindi - dəyişiklik fəaliyyətlərdən çıxarıldı]',
'rev-deleted-text-permission' => "Səhifənin bu versiyası''' silinib'''.
Mümkündür ki, bunun səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-suppressed-text-unhide'  => "Səhifənin bu versiyası''' silinib'''.
Mümkündür ki, bunun səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.
Siz idarəçi olduğunuza görə silinən [$1 bu versiyanı] nəzərdən keçirə bilərsiniz.",
'rev-deleted-text-view'       => "Səhifənin bu versiyası''' silinib'''.
Siz idarəçi olduğunuza görə silinən bu versiyanı nəzərdən keçirə bilərsiniz. Mümkündür ki, silinmənin səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-deleted-no-diff'         => "Siz versiyalar arasındakı fərqi nəzərdən keçirə bilməzsiniz. Belə ki, versiyalardan biri '''silinib'''.
Mümkündür ki, bununla bağlı təfərrüatlar [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-suppressed-no-diff'      => "Əvvəlki redaktələrin biri '''silinmiş''' fərqi görə bilməzsiniz.",
'rev-delundel'                => 'göstər/gizlət',
'rev-showdeleted'             => 'Göstər',
'revisiondelete'              => 'Səhifənin versiyalarını sil/bərpa et',
'revdelete-nooldid-title'     => 'Hədəf dəyişikliyi keçərsizdir',
'revdelete-nologtype-title'   => 'Heç bir qeyd tipi verilmədi',
'revdelete-nologid-title'     => 'Yanlış jurnal yazısı',
'revdelete-no-file'           => 'Axtarılan fayl mövcud deyil',
'revdelete-show-file-confirm' => '"<nowiki>$1</nowiki>" faylının $2 $3 tarixli silinmiş bir redaktəsini görmək istədiyinizdən əminsizinizmi?',
'revdelete-show-file-submit'  => 'Bəli',
'revdelete-selected'          => "'''[[:$1]] səhifəsinin {{PLURAL:$2|seçilmiş versiyası|seçilmiş versiyaları}}:'''",
'logdelete-selected'          => "'''Jurnalın {{PLURAL:$1|seçilmiş qeydi|seçilmiş qeydləri}}:'''",
'revdelete-legend'            => 'Məhdudiyyətləri müəyyənləşdir:',
'revdelete-hide-text'         => 'Səhifənin bu versiyasının mətnini gizlə',
'revdelete-hide-image'        => 'Faylın məzmununu gizlə',
'revdelete-hide-name'         => 'Hərəkəti və məqsədi gizləmək',
'revdelete-hide-comment'      => 'Dəyişikliklərin şərhini gizlə',
'revdelete-hide-user'         => 'Redaktə müəllifinin istifadəçi adını/IP ünvanını gizlə',
'revdelete-hide-restricted'   => 'Məlumatları idarəçilərdən də gizlə',
'revdelete-radio-same'        => '(dəyişdirmə)',
'revdelete-radio-set'         => 'Bəli',
'revdelete-radio-unset'       => 'Xeyr',
'revdelete-suppress'          => 'Məlumatları idarəçilərdən də gizlə',
'revdelete-unsuppress'        => 'Bərpa olunan versiyalar üzərindən məhdudiyyətləri qaldır',
'revdelete-log'               => 'Səbəb:',
'revdelete-submit'            => 'Seçilmiş {{PLURAL:$1|versiyaya|versiyayalara}} tətbiq et',
'revdelete-logentry'          => '[[$1]] səhifəsinin versiyasının görünüşünü dəyişdirdi',
'logdelete-logentry'          => '[[$1]] səhifəsinin versiyasının görünüşünü dəyişdirdi',
'revdelete-success'           => "'''Versiyanın görünüşü yeniləndi.'''",
'revdelete-failure'           => "'''Versiyanın görünüşü dəyişdirilə bilməz:'''
$1",
'logdelete-success'           => "''' Gündəlik görünüşü uğurla tamamlandı.'''",
'logdelete-failure'           => "'''Jurnalın görünüşü tənzimlənməyib:'''
$1",
'revdel-restore'              => 'Görünüşü dəyiş',
'revdel-restore-deleted'      => 'Silinmiş redaktələr',
'revdel-restore-visible'      => 'görünən düzəlişlər',
'pagehist'                    => 'Səhifənin tarixçəsi',
'deletedhist'                 => 'Silmə qeydləri',
'revdelete-content'           => 'məzmun',
'revdelete-summary'           => 'redaktə xülasəsi',
'revdelete-uname'             => 'istifadəçi adı',
'revdelete-restricted'        => 'məhdudiyyətlər idarəçilərə tətbiq olunur',
'revdelete-unrestricted'      => 'idarəçilər üçün götürülmüş məhdudiyyətlər',
'revdelete-hid'               => 'gizlət $1',
'revdelete-unhid'             => 'göstər $1',
'revdelete-log-message'       => '$2 {{PLURAL:$2|dəyişiklik|dəyişiklik}} üçün $1',
'logdelete-log-message'       => '$2 {{PLURAL:$2|hadisə|hadisə}} üçün $1',
'revdelete-reason-dropdown'   => '*Ümumi silmə səbəbləri
** Müəllif hüquqları pozuntusu
** Uyğunsuz şəxsi məlumat',
'revdelete-otherreason'       => 'Digər/əlavə səbəb:',
'revdelete-reasonotherlist'   => 'Digər səbəb',
'revdelete-edit-reasonlist'   => 'Silmə səbəblərini redaktə et',
'revdelete-offender'          => 'Təftiş müəllifi:',

# Suppression log
'suppressionlog' => 'Qadağa qedi',

# History merging
'mergehistory'                     => 'Səhifə tarixçələrinin birləşdirilməsi',
'mergehistory-box'                 => 'İki səhifənin tarixçəsini birləşdir',
'mergehistory-from'                => 'Mənbə səhifəsi:',
'mergehistory-into'                => 'Hədəf səhifəsi:',
'mergehistory-list'                => 'Birləşdirilə bilən redaktə keçmişi',
'mergehistory-go'                  => 'Birləşdirilə bilən redaktələri göstər',
'mergehistory-submit'              => 'Qarışıq düzəlişlər',
'mergehistory-empty'               => 'Birləşdiriləcək redaktələr tapılmamışdır.',
'mergehistory-success'             => '[[:$1]] səhifəsinin $3 {{PLURAL:$3|revizyonu|dəyişikliyi}} uğurla [[:$2]] -yə birləşdirildi.',
'mergehistory-no-source'           => 'Mənbə $1 yoxdur.',
'mergehistory-no-destination'      => 'Mənbə səhifəsi $1 mövcud deyil.',
'mergehistory-invalid-source'      => 'Mənbənin düzgün başlığı olmalıdır.',
'mergehistory-invalid-destination' => 'Hədəf səhifəsinin düzgün başlığı olmalıdır.',
'mergehistory-autocomment'         => '[[:$1]] səhifəsi [[:$2]] səhifəsinə birləşdirildi',
'mergehistory-comment'             => '$3 [[:$1]] səhifəsi [[:$2]] səhifəsinə birləşdirildi',
'mergehistory-same-destination'    => 'Mənbə və hədəf səhifələri eyni ola bilməz',
'mergehistory-reason'              => 'Səbəb:',

# Merge log
'mergelog'           => 'Birləşdirmə qeydi',
'pagemerge-logentry' => '[[$1]] [[$2]]-yə birləşdirildi ($3-ə qədər)',
'revertmerge'        => 'Ayır',

# Diffs
'history-title'            => '"$1" səhifəsinin tarixçəsi',
'difference'               => '(Versiyalar arasındakı fərq)',
'difference-multipage'     => '(Səhifələr arasında fərq)',
'lineno'                   => 'Sətir $1:',
'compareselectedversions'  => 'Seçilən versiyaları müqayisə et',
'showhideselectedversions' => 'Seçilən versiyaları göstər/gizlə',
'editundo'                 => 'əvvəlki halına qaytar',
'diff-multi'               => '({{PLURAL:$2|Bir istifadəçi|$2 istifadəçi}} tərəfindən edilən {{PLURAL:$1|bir ara redaktə|$1 ara redaktə}} göstərilmir)',
'diff-multi-manyusers'     => '({{PLURAL:$2|Bir istifadəçi|$2 istifadəçi}} tərəfindən edilən {{PLURAL:$1|bir ara redaktə|$1 ara redaktə}} göstərilmir)',

# Search results
'searchresults'                    => 'Axtarış nəticələri',
'searchresults-title'              => "''$1'' üçün axtarış nəticələri",
'searchresulttext'                 => '{{SITENAME}}-nı axtarmaqla bağlı ətraflı məlumat üçün [[{{MediaWiki:Helppage}}|{{int:kömək}}]] səhifəsinə baş çək.',
'searchsubtitle'                   => '"[[:$1]]" üçün axtarış ([[Special:Prefixindex/$1|"$1" ilə başlayan bütün səhifələr]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" səhifəsi ilə əlaqəli olan bütün səhifələr]])',
'searchsubtitleinvalid'            => 'Axtarılan: "$1"',
'toomanymatches'                   => 'Üst-üstə düşən çoxlu hal tapılıb, lütfən yeni sorğu göndərin',
'titlematches'                     => 'Səhifə adı eynidir',
'notitlematches'                   => 'Uyğun gələn səhifə adı tapılmadı',
'textmatches'                      => 'Mətn eynidir',
'notextmatches'                    => 'Məqalələrdə uyğun məzmun tapılmadı',
'prevn'                            => 'əvvəlki {{PLURAL:$1|$1}}',
'nextn'                            => 'sonrakı {{PLURAL:$1|$1}}',
'prevn-title'                      => 'Əvvəlki bir $1 {{PLURAL:$1|nəticə|nəticə}}',
'nextn-title'                      => 'Əvvəlki bir $1 {{PLURAL:$1|nəticə|nəticə}}',
'shown-title'                      => 'Səhifə üçün $1 {{PLURAL:$1|nəticə|nəticəyə}} bax',
'viewprevnext'                     => 'Göstər ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Axtarış kriteriyaları',
'searchmenu-exists'                => "'''Bu vikidə \"[[:\$1]]\" adında səhifə mövcuddur'''",
'searchmenu-new'                   => "'''Bu vikidə \"[[:\$1]]\" səhifəsini yarat!'''",
'searchhelp-url'                   => 'Help:Mündəricət',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Bu cür prefiksli səhifələri göstər]]',
'searchprofile-articles'           => 'Məqalələr',
'searchprofile-project'            => 'Kömək və Layihə səhifələri',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Hər şey',
'searchprofile-advanced'           => 'Ətraflı',
'searchprofile-articles-tooltip'   => 'Axtarış $1',
'searchprofile-project-tooltip'    => 'Axtarış $1',
'searchprofile-images-tooltip'     => 'Fayllar üçün axtarış',
'searchprofile-everything-tooltip' => 'Bütün daxili axtar (müzakirə səhifəsi daxil olmaqla)',
'searchprofile-advanced-tooltip'   => 'Ad aralığında axtar',
'search-result-size'               => '$1 ({{PLURAL:$2|1 söz|$2 söz}})',
'search-result-category-size'      => '{{PLURAL:$1|$1 element|$1 elementlər}} ({{PLURAL:$2|$2 alt kateqoriya|$2 alt kateqoriyalar}}, {{PLURAL:$3|$3 fayl|$3 fayllar}})',
'search-result-score'              => 'Uyğunluq: $1%',
'search-redirect'                  => '(yönləndirmə $1)',
'search-section'                   => '(bölmə $1)',
'search-suggest'                   => 'Bəlkə, bunu nəzərdə tuturdunuz: $1',
'search-interwiki-caption'         => 'Qardaş layihələr',
'search-interwiki-default'         => '$1 nəticə:',
'search-interwiki-more'            => '(yenə)',
'search-mwsuggest-enabled'         => 'təkliflərlə',
'search-mwsuggest-disabled'        => 'təklif yoxdur',
'search-relatedarticle'            => 'əlaqədar',
'mwsuggest-disable'                => 'AJAX təkliflərini rədd et',
'searcheverything-enable'          => 'Ad aralığında axtar:',
'searchrelated'                    => 'əlaqədar',
'searchall'                        => 'bütün',
'showingresults'                   => "Aşağıda #'''$2''' ilə başlayan {{PLURAL:$1|'''$1'''-ə qədər}} nəticə göstərilib.",
'showingresultsnum'                => "Aşağıda #'''$2''' ilə başlayan {{PLURAL:$3|'''$3'''}} nəticə göstərilib.",
'showingresultsheader'             => "'''$4''' üçün {{PLURAL:$5|'''$3'''-dən '''$1''' nəticə|'''$3'''-dən '''$1 - $2''' nəticə}}",
'nonefound'                        => "'''Qeyd''': Əksər uğursuz axtarışlara səbəb indeksləşdirilməyən, geniş işlənən \"var\", \"və\" tipli sözlər və ya axtarışa bir sözdən artıq ifadələrin verilməsidir. Çalışıb axtardığınız ifadənin qarşısında ''all:'' (bütün) yazın. Bu halda axtarışınız istifadəçi səhifələrini, şablonları və s. da əhatə edəcək.",
'search-nonefound'                 => 'Sorğunuza uyğun nəticə tapılmadı.',
'powersearch'                      => 'Axtar',
'powersearch-legend'               => 'Təkmil axtarış',
'powersearch-ns'                   => 'Ad aralığında axtar:',
'powersearch-redir'                => 'Yönləndirmələri göstər',
'powersearch-field'                => 'Axtar:',
'powersearch-togglelabel'          => 'Yoxla:',
'powersearch-toggleall'            => 'Hamısı',
'powersearch-togglenone'           => 'Heç biri',
'search-external'                  => 'Xarici axtarış',

# Quickbar
'qbsettings'               => 'Naviqasiya paneli',
'qbsettings-none'          => 'Heç biri',
'qbsettings-fixedleft'     => 'Sola sabitləndi',
'qbsettings-fixedright'    => 'Sağa sabitləndi',
'qbsettings-floatingleft'  => 'Sola əyilir',
'qbsettings-floatingright' => 'Sağa əyilir',

# Preferences page
'preferences'                   => 'Nizamlamalar',
'mypreferences'                 => 'Nizamlamalarım',
'prefs-edits'                   => 'Redaktələrin sayı:',
'prefsnologin'                  => 'Daxil olmamısınız',
'prefsnologintext'              => 'Nizamlamaları dəyişmək üçün <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} daxil olmaq]</span> zəruridir.',
'changepassword'                => 'Parolu dəyiş',
'prefs-skin'                    => 'Cild',
'skin-preview'                  => 'Sınaq göstərişi',
'datedefault'                   => 'Seçim yoxdur',
'prefs-beta'                    => 'Beta xüsusiyyətlər',
'prefs-datetime'                => 'Tarix və vaxt',
'prefs-personal'                => 'İstifadəçi profili',
'prefs-rc'                      => 'Son dəyişikliklər',
'prefs-watchlist'               => 'İzləmə siyahısı',
'prefs-watchlist-days'          => 'İzləmə siyahısında göstərilən maksimal günlərin sayı:',
'prefs-watchlist-days-max'      => 'Maksimum 7 gün',
'prefs-watchlist-edits'         => 'İzləmə siyahısında göstərilən maksimal redaktələrin sayı:',
'prefs-watchlist-edits-max'     => 'Maksimum say: 1000',
'prefs-watchlist-token'         => 'İzləmə siyahısı nişanı:',
'prefs-misc'                    => 'Digər seçimlər',
'prefs-resetpass'               => 'Parolu dəyiş',
'prefs-email'                   => 'E-mailin parametrləri',
'prefs-rendering'               => 'Görünüş',
'saveprefs'                     => 'Qeyd et',
'resetprefs'                    => 'Yarat',
'restoreprefs'                  => 'Bütün nizamlamaları bərpa et',
'prefs-editing'                 => 'Redaktə',
'prefs-edit-boxsize'            => 'Redaktə pəncərəsinin həcmi',
'rows'                          => 'Sıralar:',
'columns'                       => 'Sütunlar:',
'searchresultshead'             => 'Axtar',
'resultsperpage'                => 'Səhifəyə aid tapılmış nəticələr:',
'stub-threshold'                => '<a href="#" class="stub">Keçidsiz linki</a> format etmək üçün hüdud (baytlarla):',
'stub-threshold-disabled'       => 'Kənarlaşdırılıb',
'recentchangesdays'             => 'Son dəyişiklərdə göstərilən günlərin miqdarı:',
'recentchangesdays-max'         => 'Maksimum $1 {{PLURAL:$1|gün|gün}}',
'recentchangescount'            => 'Son dəyişikliklərdə başlıq sayı:',
'prefs-help-recentchangescount' => 'Buraya yeni dəyişikliklər, səhifələrin və jurnalların tarixçəsi daxildir.',
'prefs-help-watchlist-token'    => 'Bu sahəni gizli parolla doldurmağınız sizin izləmə siyahınız üçün RSS yayım kanalı yaradacaqdır.
Bu parolu bilən hər kəs izləmə siyahınızı oxuya bilər, bu səbəbdən etibarlı parol seçin.
Təsadüfi yolla seçilmiş bu paroldan istifadə edə bilərsiniz: $1',
'savedprefs'                    => 'Seçiminiz qeyd edildi.',
'timezonelegend'                => 'Vaxt zonası:',
'localtime'                     => 'Yerli vaxt:',
'timezoneuseserverdefault'      => 'Susmaya görə serverdən istifadə ($1)',
'timezoneuseoffset'             => 'Digər (fərqi göstərmək)',
'timezoneoffset'                => 'Vaxt fərqi¹:',
'servertime'                    => 'Server vaxtı:',
'guesstimezone'                 => 'Brouzerdən götür',
'timezoneregion-africa'         => 'Afrika',
'timezoneregion-america'        => 'Amerika',
'timezoneregion-antarctica'     => 'Antarktika',
'timezoneregion-arctic'         => 'Arktik',
'timezoneregion-asia'           => 'Asiya',
'timezoneregion-atlantic'       => 'Atlantik Okean',
'timezoneregion-australia'      => 'Avstraliya',
'timezoneregion-europe'         => 'Avropa',
'timezoneregion-indian'         => 'Hind Okeanı',
'timezoneregion-pacific'        => 'Sakit Okean',
'allowemail'                    => 'Digər istifadəçilər mənə e-məktub göndərə bilər',
'prefs-searchoptions'           => 'Axtarış kriteriyaları',
'prefs-namespaces'              => 'Adlar fəzası',
'defaultns'                     => 'Yaxud bu adlar fəzasında axtar:',
'default'                       => 'boş',
'prefs-files'                   => 'Fayllar',
'prefs-custom-css'              => 'Xüsusi CSS',
'prefs-custom-js'               => 'Xüsusi JavaScript',
'prefs-common-css-js'           => 'Bütün skinlər üçün ümumi CSS/JavaScript:',
'prefs-emailconfirm-label'      => 'E-poçtun təsdiqlənməsi:',
'prefs-textboxsize'             => 'Redaktə pəncərəsinin ölçüsü',
'youremail'                     => 'E-məktub *',
'username'                      => 'İstifadəçi adı:',
'uid'                           => 'İstifadəçi ID:',
'prefs-memberingroups'          => 'Üzvü olduğu {{PLURAL:$1|qrup|qrup}}:',
'prefs-memberingroups-type'     => '$1',
'prefs-registration'            => 'Qeydiyyat vaxtı:',
'prefs-registration-date-time'  => '$1',
'yourrealname'                  => 'Əsl adınız:',
'yourlanguage'                  => 'Dil:',
'yourvariant'                   => 'Dil variant;:',
'yournick'                      => 'Ləqəb:',
'badsig'                        => 'Səhv xam imza.
HTML kodu yoxla.',
'badsiglength'                  => 'İmzanız çox uzundur. İmza $1 {{PLURAL:$1|simvoldan|simvoldan}} uzun olmamalıdır.',
'yourgender'                    => 'Cins:',
'gender-unknown'                => 'göstərmə',
'gender-male'                   => 'kişi',
'gender-female'                 => 'qadın',
'email'                         => 'E-məktub',
'prefs-help-realname'           => 'Həqiqi adınızı daxil etmək məcburi deyil.
Bu seçimi etdiyiniz halda, adınız redaktələrinizə görə müəlliflik hüququnuzun tanınması üçün istifadə ediləcək.',
'prefs-help-email'              => 'E-poçt ünvanınızı daxil etmək məcburi deyil.
Bu parolunuzu unutduğunuz halda Sizə yeni parol göndərməyə imkan verir.
Həmçinin kimliyinizi gostərmədən belə, başqalarının sizinlə istifadəçi və ya istifadəçi müzakirəsi səhifələriniz vasitəsi ilə əlaqə yaratmalarını seçə bilərsiniz.',
'prefs-help-email-required'     => 'Elektron ünvan tələb olunur.',
'prefs-info'                    => 'Əsas məlumatlar',
'prefs-i18n'                    => 'Beynəlxalqlaşdırma',
'prefs-signature'               => 'İmza',
'prefs-dateformat'              => 'Tarix formatı',
'prefs-timeoffset'              => 'Saat qurşağının fərqi',
'prefs-advancedediting'         => 'Ətraflı variantlar',
'prefs-advancedrc'              => 'Ətraflı variantlar',
'prefs-advancedrendering'       => 'Ətraflı variantlar',
'prefs-advancedsearchoptions'   => 'Ətraflı variantlar',
'prefs-advancedwatchlist'       => 'Ətraflı variantlar',
'prefs-displayrc'               => 'Görüntü variantları',
'prefs-displaysearchoptions'    => 'Görüntü variantları',
'prefs-displaywatchlist'        => 'Görüntü variantları',
'prefs-diffs'                   => 'Fərqlər',

# User preference: e-mail validation using jQuery
'email-address-validity-valid'   => 'E-poçt ünvanı düzgündür',
'email-address-validity-invalid' => 'Düzgün e-poçt ünvanı daxil edin',

# User rights
'userrights'                     => 'İstifadəçi hüququ idarəsi',
'userrights-lookup-user'         => 'İstifadəçi qruplarını idarə et',
'userrights-user-editname'       => 'İstifadəçi adınızı yazın:',
'editusergroup'                  => 'İstifadəçi qruplarını redaktə et',
'editinguser'                    => "Redaktə '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'       => 'İstifadəçi qruplarını redaktə et',
'saveusergroups'                 => 'İstifadəçi qrupunu qeyd et',
'userrights-groupsmember'        => 'Daxil olduğu qruplar:',
'userrights-groupsmember-auto'   => 'Güman edilən üzv:',
'userrights-reason'              => 'Səbəb:',
'userrights-no-interwiki'        => 'Sizə başqa vikilayihələrdəki istifadəçilərin statusunu dəyişməyə icazə verilməyib',
'userrights-nodatabase'          => '$1 verilənlər bazası ya mövcud deyil, ya da lokal deyil.',
'userrights-nologin'             => 'Siz  istifadəçilərin hüquqlarını dəyişmək üçün idarəçi olaraq sistemə [[Special:UserLogin|Daxil olmalısınız]].',
'userrights-notallowed'          => 'Sizin istifadəçi hesabınıza digər istifadəçilərə hüquqlar vermək və ya almağa icazə verilməyib.',
'userrights-changeable-col'      => 'Dəyişdirə bildiyiniz qruplar',
'userrights-unchangeable-col'    => 'Dəyişdirə bilmədiyiniz qruplar',
'userrights-irreversible-marker' => '$1*',

# Groups
'group'               => 'Qrup:',
'group-user'          => 'İstifadəçilər',
'group-autoconfirmed' => 'Avtotəsdiqlənmiş istifadəçilər',
'group-bot'           => 'Botlar',
'group-sysop'         => 'İdarəçilər',
'group-bureaucrat'    => 'Bürokratlar',
'group-suppress'      => 'Müfəttişlər',
'group-all'           => '(bütün)',

'group-user-member'          => '{{GENDER:$1|istifadəçi}}',
'group-autoconfirmed-member' => 'Avtotəsdiqlənmiş istifadəçilər',
'group-bot-member'           => '{{GENDER:$1|bot}}',
'group-sysop-member'         => '{{GENDER:$1|idarəçi}}',
'group-bureaucrat-member'    => '{{GENDER:$1|bürokrat}}',
'group-suppress-member'      => '{{GENDER:$1|oversight}}',

'grouppage-user'          => '{{ns:project}}:İstifadəçilər',
'grouppage-autoconfirmed' => '{{ns:project}}:Avtotəsdiqlənmiş istifadəçilər',
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:İdarəçilər',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokratlar',
'grouppage-suppress'      => '{{ns:project}}:Müfəttişlər',

# Rights
'right-read'                 => 'Səhifələrin oxunması',
'right-edit'                 => 'Səhifələrin redaktəsi',
'right-createpage'           => 'Səhifələr yaratmaq (müzakirə səhifələrindən əlavə səhifələr nəzərdə tutulur)',
'right-createtalk'           => 'Müzakirə səhifələri yaratmaq',
'right-createaccount'        => 'Yeni istifadəçi hesabları açmaq',
'right-minoredit'            => 'Redaktələri kiçik redaktə kimi nişanlamaq',
'right-move'                 => 'Səhifənin adını dəyişdir',
'right-move-subpages'        => 'Səhifənin adının onların alt səhifələrin adı ilə dəyişdirilməsi',
'right-move-rootuserpages'   => 'əsas istifadəçi səhifələrinin adını dəyişmək',
'right-movefile'             => 'Faylın adını dəyişdir',
'right-suppressredirect'     => 'Səhifənin adını dəyişən zaman kohnə addan istiqamətlənmə yaradıla bilinmir',
'right-upload'               => 'Fayl yüklə',
'right-reupload'             => 'Mövcud faylın yeni versiyasının yüklənməsi',
'right-reupload-own'         => 'Mövcud faylın yeni versiyasının həmin istifadəçi tərəfindən yüklənməsi',
'right-reupload-shared'      => 'ümumi anbarda olan faylın adının lokal adla dəyişdirilməsi',
'right-upload_by_url'        => 'URL-dən fayl yüklə',
'right-autoconfirmed'        => 'Yarım mühafizə edilmiş səhifənin redaktəsi',
'right-bot'                  => 'Avtomatik proses hesab edilir',
'right-apihighlimits'        => 'API sorğularında yüksək həddən istifadə et',
'right-writeapi'             => 'Redaktələrdən zamanı API-dən (İnterfeys proqramlaşdıran proqram) istifadə',
'right-delete'               => 'Səhifələrin silinməsi',
'right-bigdelete'            => 'Uzun tarixçəsi olan səhifələrin silinməsi',
'right-deleterevision'       => 'səhifənin konkret versiyasının silinməsi və bərpası',
'right-deletedhistory'       => 'silinmiş mətnə daxil olmadan silinmiş səhifələrin tarixçələrinə baxma',
'right-browsearchive'        => 'Silinmiş səhifələri axtar',
'right-undelete'             => 'Silinmiş səhifələrin bərpası',
'right-suppressrevision'     => 'İdarəçilərdən gizlənmiş dəyişikliklərə bax və geri yüklə',
'right-suppressionlog'       => 'Şəxsi qeydlərə bax',
'right-block'                => 'Digər istifadəçilərin redaktə etməsinə qadağa qoy',
'right-blockemail'           => 'İstifadəçinin e-poçt göndərməsinə qadağa qoy',
'right-hideuser'             => 'İstifadəçi adına qadağa qoy və adın görünməsinin qarşısını al',
'right-ipblock-exempt'       => 'IP bloklanmalarını, avtobloklanmalarını və diapazon bloklanmalarını keç',
'right-proxyunbannable'      => 'Proksilərin avtomatik bloklanmalarını keç',
'right-unblockself'          => 'Özünün blokunun qaldırılması',
'right-protect'              => 'Mühafizə səviyyəsi dəyiş və mühafizə altında olan səhifəni redaktə et',
'right-editprotected'        => 'Mühafizə olunmuş səhifələri redaktə (kaskad mühafizə daxil olmaqla)',
'right-editinterface'        => 'İstifadəçi interfeysini dəyişmək',
'right-editusercssjs'        => 'Digər istifadəçilərin CSS və JavaScript fayllarını redaktə',
'right-editusercss'          => 'Digər istifadəçilərin CSS faylını redaktə',
'right-edituserjs'           => 'Digər istifadəçilərin JavaScript faylını redaktə',
'right-markbotedits'         => 'Geri qaytarılan dəyişikliklərin bot dəyişiklikləri kimi işarələnməsi',
'right-noratelimit'          => 'Sürət limiti yoxdur',
'right-import'               => 'Digər vikilərdən səhifələrin idxalı',
'right-importupload'         => 'fayl yükləmə vasitəsilə səhifələrin idxalı',
'right-patrol'               => 'Digərlərinin dəyişikliklərini patrullanmış olaraq işarələ',
'right-autopatrol'           => 'Dəyişikliklər avtomatik patrullanmış kimi işarələnir',
'right-patrolmarks'          => 'Bütün patrullanmış son dəyişikliklərə bax',
'right-unwatchedpages'       => 'Müşahidə olunmayan səhifələrin siyahısına baxış',
'right-trackback'            => 'Trackback göndər',
'right-mergehistory'         => 'Səhifələrin tarixini birləşdirmək',
'right-userrights'           => 'Bütün istifadəçi hüquqlarının redaktə edilməsi',
'right-userrights-interwiki' => 'Digər vikilərdəki istifadəçilərin istifadəçi hüquqlarını dəyişdir',
'right-siteadmin'            => 'Məlumatlar bazasının bloklanması və blokun götürülməsi',
'right-sendemail'            => 'Digər istifadəçilərə elektron poçt göndər',

# User rights log
'rightslog'      => 'İstifadəçi hüquqları qeydləri',
'rightslogtext'  => 'İstifadəçi hüquqları dəyişikliyi qeydləri.',
'rightslogentry' => '$1 adlı istifadəçinin istifadəçi qruplarındakı üzvlüyü dəyişdirildi: $2 ► $3',
'rightsnone'     => '(heç biri)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'bu səhifənin oxunması',
'action-edit'                 => 'bu səhifəni redaktə etmək',
'action-createpage'           => 'səhifələrin yaradılması',
'action-createtalk'           => 'müzakirə səhifələrinin yaradılması',
'action-createaccount'        => 'bu istifadəçi hesabının yaradılması',
'action-minoredit'            => 'bunu kiçik redaktə kimi nişanla',
'action-move'                 => 'bu səhifənin adını dəyişmək',
'action-move-subpages'        => 'bu səhifənin və onun altsəhifələrinin adını dəyişmək',
'action-move-rootuserpages'   => 'əsas istifadəçi səhifələrinin adını dəyişmək',
'action-movefile'             => 'bu faylın adını dəyişmək',
'action-upload'               => 'bu faylı yüklə',
'action-reupload'             => 'Mövcud faylın yeni versiyasının yüklənməsi',
'action-upload_by_url'        => 'URL ünvanından bu faylı yükləmək',
'action-writeapi'             => 'API yazıdan istifadə',
'action-delete'               => 'bu səhifəni sil',
'action-deleterevision'       => 'bu yoxlamaı ləğv et',
'action-deletedhistory'       => 'səhifənin silinmə tarixinə bax',
'action-browsearchive'        => 'Silinmiş səhifələri axtar',
'action-undelete'             => 'bu səhifəni silmə',
'action-suppressrevision'     => 'bu gizli redaktəyə bax və bərpa et',
'action-suppressionlog'       => 'xüsusi gündəliyə baxış',
'action-block'                => 'istifadəçinin redaktə etməsini əngəlləmək',
'action-protect'              => 'bu səhifənin mühafizə səviyyəsini dəyişmək',
'action-import'               => 'bu səhifəni başqa vikidən götürmək',
'action-importupload'         => 'fayl yükləmə vasitəsilə səhifələrin idxalı',
'action-patrol'               => 'Digərlərinin dəyişikliklərini patrullanmış olaraq işarələ',
'action-autopatrol'           => 'öz redaktələrinizi patrullanmış olarq işarələmək',
'action-unwatchedpages'       => 'müşahidə olunmayan səhifələrin siyahısına baxış',
'action-trackback'            => 'Trackback göndər',
'action-mergehistory'         => 'Bu səhifənin tarixini birləşdirmək',
'action-userrights'           => 'Bütün istifadəçi hüquqlarını redaktə et',
'action-userrights-interwiki' => 'Digər vikilərdəki istifadəçilərin istifadəçi hüquqlarını dəyişdir',
'action-siteadmin'            => 'Məlumatlar bazasının bloklanması və blokun götürülməsi',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|dəyişiklik|dəyişiklik}}',
'recentchanges'                     => 'Son dəyişikliklər',
'recentchanges-legend'              => 'Son dəyişiklik seçimləri',
'recentchangestext'                 => "'''Ən son dəyişiklikləri bu səhifədən izləyin:'''",
'recentchanges-feed-description'    => 'Vikidəki ən son dəyişiklikləri bu yayım kanalından izləyin.',
'recentchanges-label-newpage'       => 'Bu dəyişiklik yeni səhifə yaratdı',
'recentchanges-label-minor'         => 'Bu kiçik redaktədir',
'recentchanges-label-bot'           => 'Bu redaktə bot tərəfindən edilmişdir',
'recentchanges-label-unpatrolled'   => 'Bu redaktə hələ nəzərdən keçirilməmişdir',
'rcnote'                            => "Aşağıdakı {{PLURAL:$1|'''1''' dəyişiklik|'''$1''' dəyişiklik}} saat $5, $4 tarixinə qədər son {{PLURAL:$2|gün|'''$2''' gün}} ərzində edilmişdir.",
'rcnotefrom'                        => "Aşağıda '''$2'''-dən ('''$1'''-ə qədər) dəyişikliklər sadalanmışdır.",
'rclistfrom'                        => '$1 vaxtından başlayaraq yeni dəyişiklikləri göstər',
'rcshowhideminor'                   => 'Kiçik redaktələri $1',
'rcshowhidebots'                    => 'Botları $1',
'rcshowhideliu'                     => 'Qeydiyyatlı istifadəçiləri $1',
'rcshowhideanons'                   => 'Anonim istifadəçiləri $1',
'rcshowhidepatr'                    => 'Nəzarət edilən redaktələri $1',
'rcshowhidemine'                    => 'Mənim redaktələrimi $1',
'rclinks'                           => 'Son $2 gün ərzindəki son $1 dəyişikliyi göstər <br />$3',
'diff'                              => 'fərq',
'hist'                              => 'tarixçə',
'hide'                              => 'Gizlət',
'show'                              => 'Göstər',
'minoreditletter'                   => 'k',
'newpageletter'                     => 'Y',
'boteditletter'                     => 'b',
'sectionlink'                       => '→',
'number_of_watching_users_pageview' => '[$1 izləyən istifadəçi]',
'rc_categories'                     => 'Kateqoriyalara limit qoy ("|" ilə ayır)',
'rc_categories_any'                 => 'Hər',
'rc-change-size'                    => '$1',
'newsectionsummary'                 => '/* $1 */ yeni bölmə',
'rc-enhanced-expand'                => 'Detalları göstər (JavaScript istifadə edir)',
'rc-enhanced-hide'                  => 'Redaktələri gizlət',

# Recent changes linked
'recentchangeslinked'          => 'Əlaqəli redaktələr',
'recentchangeslinked-feed'     => 'Əlaqəli redaktələr',
'recentchangeslinked-toolbox'  => 'Əlaqəli redaktələr',
'recentchangeslinked-title'    => "''$1'' ilə əlaqəli dəyişikliklər",
'recentchangeslinked-backlink' => '← $1',
'recentchangeslinked-noresult' => 'Qeyd olunan dövrdə əlaqədar səhifələrdə heç bir dəyişiklik yoxdur.',
'recentchangeslinked-summary'  => "Aşağıdakı siyahı, qeyd olunan səhifəyə (və ya qeyd olunan kateqoriyadakı səhifələrə) daxili keçid verən səhifələrdə edilmiş son dəyişikliklərin siyahısıdır.
[[Special:Watchlist|İzləmə siyahınızdakı]] səhifələr '''qalın''' şriftlə göstərilmişdir.",
'recentchangeslinked-page'     => 'Səhifə adı:',
'recentchangeslinked-to'       => 'Qeyd olunan səhifədəki deyil, ona daxili keçid verən səhifələrdəki dəyişiklikləri göstər',

# Upload
'upload'                     => 'Fayl yüklə',
'uploadbtn'                  => 'Sənəd yüklə',
'reuploaddesc'               => 'Return to the upload form.',
'upload-tryagain'            => 'Dəyşdirilmiş fayl izahını göndər',
'uploadnologin'              => 'Daxil olmamısınız',
'uploadnologintext'          => 'Fayl yükləmək üçün [[Special:UserLogin|daxil olmalısınız]].',
'upload_directory_missing'   => '($1) yükləmə qaydası axtarılır və vebserverdə yaradılması qeyri-mümkündür.',
'upload_directory_read_only' => '"$1" kataloqunun arxivi veb-server yazıları üçün qapalıdır.',
'uploaderror'                => 'Yükləmə xətası',
'upload-recreate-warning'    => "'''Diqqət: Bu adda fayl silinib, yaxud adı dəyişdirilib.'''

Bu səhifənin silinmə və addəyişmə jurnalı aşağıda göstərilmişdir:",
'uploadtext'                 => "Fayl yükləmək üçün aşağıdakı formadan istifadə edin.
Əvvəllər yüklənmiş fayllara baxmaq üçün [[Special:FileList|yüklənmiş fayllar siyahısına]] keçin, həmçinin (təkrar) yüklənmiş fayllara [[Special:Log/upload|yükləmə jurnalında]], silinmiş fayllara [[Special:Log/delete|silinmə jurnalında]] baxa bilərsiniz.

Məqaləyə fayl yerləşdirmək üçün aşağıdaki formalardan birini istifadə edin:
* Faylın tam versiyasını yerləşdirmək üçün: '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''';
* Faylın 200 pikselədək kiçildilmiş versiyasını mətndən solda, altında izahla yerləşdirmək üçün: '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|təsvir]]</nowiki></tt>''';
* Səhifədə faylın özünü göstərmədən ona birbaşa keçid yerləşdirmək üçün: '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>'''.",
'upload-permitted'           => 'İcazə verilən fayl tipləri: $1.',
'upload-preferred'           => 'İcazə verilən fayl tipləri: $1.',
'upload-prohibited'          => 'İcazə verilməyən fayl tipləri: $1.',
'uploadlog'                  => 'yükləmə qeydi',
'uploadlogpage'              => 'Yükləmə qeydi',
'uploadlogpagetext'          => 'Aşağıda ən yeni yükləmə jurnal qeydləri verilmişdir.',
'filename'                   => 'Fayl adı',
'filedesc'                   => 'Xülasə',
'fileuploadsummary'          => 'İzahat:',
'filereuploadsummary'        => 'Fayl dəyişiklikləri:',
'filestatus'                 => 'Müəllif statusu:',
'filesource'                 => 'Mənbə:',
'uploadedfiles'              => 'Yüklənmiş fayllar',
'ignorewarning'              => 'Xəbərdarlıqlara əhəmiyyət vermə və faylı saxla',
'ignorewarnings'             => 'Bütün xəbərdarlıqlara məhəl qoymamaq',
'minlength1'                 => 'Fayl adı ən azı bir hərfdən ibarət olmalıdır.',
'illegalfilename'            => '"$1" fayl adında səhifə adlarında istifadəsinə yol verilməyən simvollar var. Lütfən faylın adını dəyişin və yenidən yükləməyə cəhd edin.',
'badfilename'                => 'Faylın adı dəyişildi. Yeni adı: "$1".',
'filetype-mime-mismatch'     => '".$1" fayl uzantısı faylın MIME tipinə ($2) uyğun gəlmir.',
'filetype-badmime'           => '"$1" MIME tipindəki faylların yüklənməsinə icazə verilmir.',
'filetype-missing'           => 'Faylın heç bir uzantısı yoxdur (məsələn, ".jpg").',
'empty-file'                 => 'Göndərdiyiniz fayl boşdur.',
'file-too-large'             => 'Göndərdiyiniz fayl çox böyükdür.',
'filename-tooshort'          => 'Fayl adı qısadır.',
'filetype-banned'            => 'Bu tip fayllar qadağandır.',
'verification-error'         => 'Fayl təsdiqi baş tutmadı.',
'illegal-filename'           => 'Fayl adına icazə yoxdur.',
'unknown-error'              => 'Bilinməyən bir xəta yarandı.',
'tmp-create-error'           => 'Müvəqqəti fayl yaradıla bilmədi.',
'tmp-write-error'            => 'Müvəqqəti fayl yazılarkən xəta.',
'largefileserver'            => 'Faylın ölçüsü yol verilən həddi aşır.',
'emptyfile'                  => 'Yüklədiyiniz fayl boşdur. Bu faylın adında olan hərf səhvi ilə bağlı ola bilər. Xahiş olunur ki, doğurdan da bu faylı yükləmək istədiyinizi yoxlayasınız.',
'fileexists'                 => "Yükləmək istədiyiniz adda fayl mövcuddur.
Lütfən '''<tt>[[:$1]]</tt>''' keçidini yoxlayın və bu faylı yükləmək istədiyinizdən əmin olun.
[[$1|thumb]]",
'uploadwarning'              => 'Yükləmə xəbərdarlığı',
'savefile'                   => 'Faylı qeyd et',
'uploadedimage'              => 'yükləndi "[[$1]]"',
'overwroteimage'             => '"[[$1]]"-in yeni versiyası yükləndi',
'uploaddisabled'             => 'Yükləmə baş tutmadı',
'copyuploaddisabled'         => 'URL-dən yükləmə baş tutmadı.',
'uploadfromurl-queued'       => 'Yükləməniz növbə gözləyir',
'uploaddisabledtext'         => 'Fayl yüklənməsi baş tutmadı.',
'uploadvirus'                => 'Faylda virus var! 
Detallar: $1',
'upload-source'              => 'Mənbə faylı',
'sourcefilename'             => 'Fayl adı mənbələri',
'sourceurl'                  => 'URL mənbəsi:',
'destfilename'               => 'Fayl adı',
'upload-maxfilesize'         => 'Faylın maksimum həcmi: $1',
'upload-description'         => 'Faylın izahı',
'upload-options'             => 'Yükləmə parametrləri',
'watchthisupload'            => 'Bu faylı izlə',
'filename-prefix-blacklist'  => ' #<!-- Bu sətrə toxunmayın --> <pre>
# Sintaksis aşağıdakı kimi görünür:
#   * "#" simvolundan sətrin sonuna kimi yazılar şərhdir
#   * Tipik fayl adları üçün olan prefiksdəki hər bir boş olmayan sətir rəqəmli kamera trəfindən avtomatik qeydə alınır
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # digər mobil telefonlar
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- Bu sətrə toxunmayın -->',
'upload-success-subj'        => 'Yükləmə tamamlandı',
'upload-failure-subj'        => 'Yükləmə problemi',
'upload-failure-msg'         => 'Yüklədiyiniz [$2] forması ilə bağlı problem yaranıb:

$1',
'upload-warning-subj'        => 'Yükləmə xəbərdarlığı',

'upload-proto-error'        => 'Yanlış protokol',
'upload-file-error'         => 'Daxili xəta',
'upload-misc-error'         => 'Naməlum yükləmə xətası',
'upload-too-many-redirects' => 'URL-də xeyli yönləndirmə var',
'upload-unknown-size'       => 'Naməlum həcm',
'upload-http-error'         => ' HTTP xətası var : $1',

# Special:UploadStash
'uploadstash'         => 'Gizli yükləmə',
'uploadstash-clear'   => 'Müvəqqəti faylları təmizlə',
'uploadstash-refresh' => 'Fayl siyahısını yenilə',

# img_auth script messages
'img-auth-accessdenied' => 'Giriş qadağandır',
'img-auth-nofile'       => 'Fayl "$1" mövcud deyil.',
'img-auth-streaming'    => '"$1" axını.',
'img-auth-noread'       => 'İstifadəçinin "$1"i oxumaq hüququ yoxdur.',

# HTTP errors
'http-invalid-url'      => 'Səhv URL: $1',
'http-read-error'       => 'HTTP oxuma xətası',
'http-timed-out'        => 'HTTP istəyinin vaxtı bitdi.',
'http-host-unreachable' => 'URL-ə çatmaq olmadı.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'  => 'URL-ə çatmaq olmadı',
'upload-curl-error28' => 'Yükləmə vaxtı bitdi',

'license'            => 'Lisenziya',
'license-header'     => 'Lisenziya',
'nolicense'          => 'Heç biri seçilməmişdir',
'upload_source_url'  => ' (keçərli, hər kəsin daxil ola biləcəyi bir URL)',
'upload_source_file' => ' (kompyuterinizdəki bir fayl)',

# Special:ListFiles
'listfiles_search_for'  => 'Media adı üçün axtar:',
'imgfile'               => 'fayl',
'listfiles'             => 'Fayl siyahısı',
'listfiles_thumb'       => 'Kiçik şəkil',
'listfiles_date'        => 'Tarix',
'listfiles_name'        => 'Ad',
'listfiles_user'        => 'İstifadəçi',
'listfiles_size'        => 'Həcm',
'listfiles_description' => 'İzah',
'listfiles_count'       => 'Versiya',

# File description page
'file-anchor-link'                  => 'Fayl',
'filehist'                          => 'Faylın tarixçəsi',
'filehist-help'                     => 'Faylın əvvəlki versiyasını görmək üçün gün/tarix bölməsindəki tarixləri tıqlayın.',
'filehist-deleteall'                => 'hamısını sil',
'filehist-deleteone'                => 'sil',
'filehist-revert'                   => 'əvvəlki vəziyyətinə',
'filehist-current'                  => 'indiki',
'filehist-datetime'                 => 'Tarix/Vaxt',
'filehist-thumb'                    => 'Kiçik şəkil',
'filehist-thumbtext'                => '$1 tarixindəki versiyanın kiçildilmiş görüntüsü',
'filehist-nothumb'                  => 'Miniatür yoxdur',
'filehist-user'                     => 'İstifadəçi',
'filehist-dimensions'               => 'Ölçülər',
'filehist-filesize'                 => 'Faylın həcmi',
'filehist-comment'                  => 'Şərh',
'filehist-missing'                  => 'Fayl çatışmır',
'imagelinks'                        => 'Fayl keçidləri',
'linkstoimage'                      => '{{PLURAL:$1|səhifə|$1 səhifə}} bu fayla istinad edir:',
'nolinkstoimage'                    => 'Bu fayla keçid verən səhifə yoxdur.',
'linkstoimage-redirect'             => '$1 (fayl istiqamətləndirilir) $2',
'sharedupload'                      => 'Bu fayl $1-dandır və ola bilsin ki, başqa layihələrdə də istifadə edilir.',
'uploadnewversion-linktext'         => 'Bu faylın yeni versiyasını yüklə',
'shared-repo-from'                  => '$1-dən',
'shared-repo-name-wikimediacommons' => 'Wikimedia Commons',

# File reversion
'filerevert'          => '$1 faylını əvvəlki vəziyyətinə qaytar',
'filerevert-backlink' => '← $1',
'filerevert-legend'   => 'Faylı əvvəlki vəziyyətinə qaytar',
'filerevert-comment'  => 'Səbəb:',
'filerevert-submit'   => 'Əvvəlki vəziyyətinə',

# File deletion
'filedelete'                  => '$1 adlı faylı sil',
'filedelete-backlink'         => '← $1',
'filedelete-legend'           => 'Faylı sil',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' faylını və onunla bağlı bütün tarixçəni silmək ərəfəsindəsiniz.",
'filedelete-comment'          => 'Səbəb:',
'filedelete-submit'           => 'Sil',
'filedelete-success'          => "'''$1''' silinmişdir.",
'filedelete-success-old'      => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\'-nin  $3 və $2 versiyaları silinmişdir.</span>',
'filedelete-nofile'           => "'''$1''' mövcud deyil.",
'filedelete-otherreason'      => 'Başqa/əlavə səbəb:',
'filedelete-reason-otherlist' => 'Başqa səbəb',
'filedelete-reason-dropdown'  => '*Əsas silmə səbəbi
** Müəllif hüququ pozuntusu
** Dublikat fayl',
'filedelete-edit-reasonlist'  => 'Silmə səbəblərini redaktə et',

# MIME search
'mimesearch' => 'MIME axtar',
'mimetype'   => 'MIME tipi:',
'download'   => 'Yüklə',

# Unwatched pages
'unwatchedpages' => 'İzlənməyən səhifələr',

# List redirects
'listredirects' => 'İstiqamətləndirmə siyahısı',

# Unused templates
'unusedtemplates'    => 'İstifadəsiz şablonlar',
'unusedtemplateswlh' => 'digər keçidlər',

# Random page
'randompage' => 'İxtiyari səhifə',

# Random redirect
'randomredirect' => 'İxtiyari istiqamətləndirmə',

# Statistics
'statistics'                   => 'Statistika',
'statistics-header-pages'      => 'Səhifə statistikası',
'statistics-header-edits'      => 'Redaktə statistikası',
'statistics-header-views'      => 'Statistikaya bax',
'statistics-header-users'      => 'İstifadəçi statistika',
'statistics-header-hooks'      => 'Digər statistikalar',
'statistics-articles'          => 'Məqalələr',
'statistics-pages'             => 'Səhifələr',
'statistics-pages-desc'        => 'Vikidə olan bütün səhifələr, istifadəçi müzakirələri, istiqamətləndirmə səhifələri və s. daxil olmaqla',
'statistics-files'             => 'Yüklənmiş fayllar',
'statistics-edits'             => '{{SITENAME}} yaranandan bəri edilən səhifə dəyişiklikləri',
'statistics-edits-average'     => 'Hər səhifədəki orta hesabla dəyişiklik',
'statistics-views-total'       => 'Cəmi göstərmə',
'statistics-views-total-desc'  => 'Mövcud olmayan və xüsusi səhifələrin göstərilmələri daxil edilməmişdir.',
'statistics-views-peredit'     => 'Redaktə başına göstərmə',
'statistics-users'             => 'Qeydiyyatdan keçmiş [[Special:ListUsers|istifadəçilər]]',
'statistics-users-active'      => 'Aktiv istifadəçilər',
'statistics-users-active-desc' => 'Son {{PLURAL:$1|gün|$1 gündə}} iş görən istifadəçilər',
'statistics-mostpopular'       => 'Ən çox baxılan səhifələr',

'disambiguations'      => 'Dəqiqləşdirmə səhifələri',
'disambiguationspage'  => 'Template:dəqiqləşdirmə',
'disambiguations-text' => "Aşağıdakı səhifələr '''dəqiqləşdirmə səhifələrinə''' keçid verir. Bunun əvəzinə onlar çox guman ki, müvafiq konkret bir məqaləni göstərməlidirlər.
<br />Səhifə o zaman dəqiqləşdirmə səhifəsi hesab edilir ki, onda  [[MediaWiki:Disambiguationspage]]-dən keçid verilmiş şablon istifadə edilir.",

'doubleredirects'                   => 'İkiqat istiqamətləndirmələr',
'double-redirect-fixed-move'        => '[[$1]] dəyişdirilib.
Hazırda [[$2]]-yə istiqamətlənib.',
'double-redirect-fixed-maintenance' => '[[$1]]-dən [[$2]]-yə ikiqat istiqamətlənmə düzəldilir.',
'double-redirect-fixer'             => 'Yönləndirmə səhvdir',

'brokenredirects'        => 'Xətalı istiqamətləndirmə',
'brokenredirectstext'    => 'Aşağıdakı istiqamətləndirmələr mövcud olmayan səhifələrə keçid verir:',
'brokenredirects-edit'   => 'redaktə',
'brokenredirects-delete' => 'sil',

'withoutinterwiki'        => 'Dil keçidləri olmayan səhifələr',
'withoutinterwiki-legend' => 'Prefiks',
'withoutinterwiki-submit' => 'Göstər',

'fewestrevisions' => 'Az dəyişiklik edilmiş məqalələr',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt|bayt}}',
'ncategories'             => '$1 {{PLURAL:$1|kateqoriya|kateqoriya}}',
'nlinks'                  => '$1 {{PLURAL:$1|keçid|keçidlər}}',
'nmembers'                => '$1 {{PLURAL:$1|üzv|üzv}}',
'nrevisions'              => '$1 dəyişiklik',
'nviews'                  => '$1 baxış',
'nimagelinks'             => '$1 səhifədə istifadə olunmur',
'ntransclusions'          => '$1 səhifədə istifadə olunur',
'specialpage-empty'       => 'Bu səhifə boşdur.',
'lonelypages'             => 'Yetim səhifələr',
'uncategorizedpages'      => 'Kateqoriyasız səhifələr',
'uncategorizedcategories' => 'Kateqoriyasız kateqoriyalar',
'uncategorizedimages'     => 'Kateqoriyasız şəkillər',
'uncategorizedtemplates'  => 'Kateqoriyasız şablonlar',
'unusedcategories'        => 'İstifadə edilməmiş kateqoriyalar',
'unusedimages'            => 'İstifadə edilməmiş fayllar',
'popularpages'            => 'Məşhur səhifələr',
'wantedcategories'        => 'Təlabat olunan kateqoriyalar',
'wantedpages'             => 'Tələb olunan səhifələr',
'wantedpages-badtitle'    => 'Müraciət zamantı yanlış başlıq: $1',
'wantedfiles'             => 'Tələb olunan fayllar',
'wantedtemplates'         => 'Tələb olunan şablonlar',
'mostlinked'              => 'Ən çox keçidlənən səhifələr',
'mostlinkedcategories'    => 'Ən çox məqaləsi olan kateqoriyalar',
'mostlinkedtemplates'     => 'Ən çox istifadə olunan şablonlar',
'mostcategories'          => 'Kateqoriyası ən çox olan məqalələr',
'mostimages'              => 'Ən çox istifadə edilmiş şəkillər',
'mostrevisions'           => 'Ən çox nəzərdən keçirilmiş (versiyalı) məqalələr',
'prefixindex'             => 'Prefiks indeksli bütün səhifələr',
'shortpages'              => 'Qısa səhifələr',
'longpages'               => 'Uzun səhifələr',
'deadendpages'            => 'Keçid verməyən səhifələr',
'deadendpagestext'        => 'Aşağıdakı səhifələrdən bu Vikipediyadakı digər səhifələrə heç bir keçid yoxdur.',
'protectedpages'          => 'Mühafizəli səhifələr',
'protectedpages-indef'    => 'Yalnız müddətsiz mühafizələr',
'protectedpages-cascade'  => 'Yalnız kaskad mühafizələr',
'protectedpagestext'      => 'Aşağıdakı səhifələr ad dəyişiminə və redaktəyə bağlıdır',
'protectedpagesempty'     => 'Hal-hazırda bu parametrə uyğun heç bir mühafizəli səhifə yoxdur',
'protectedtitles'         => 'Mühafizəli başlıqlar',
'listusers'               => 'İstifadəçi siyahısı',
'listusers-editsonly'     => 'Yalnız redaktələri olan istifadəçiləri göstər',
'listusers-creationsort'  => 'Yaranma tarixinə görə sırala',
'usereditcount'           => '$1 {{PLURAL:$1|redaktə}}',
'usercreated'             => '$1 $2 vaxtda yaradılıb',
'newpages'                => 'Yeni səhifələr',
'newpages-username'       => 'İstifadəçi adı:',
'ancientpages'            => 'Ən köhnə səhifələr',
'move'                    => 'Adını dəyiş',
'movethispage'            => 'Bu səhifənin adını dəyiş',
'notargettitle'           => 'Verilməyib',
'nopagetitle'             => 'Belə hədəf səhifəsi yoxdur',
'pager-newer-n'           => '{{PLURAL:$1|1 daha yeni|$1 daha yeni}}',
'pager-older-n'           => '{{PLURAL:$1|1 daha köhnə|$1 daha köhnə}}',
'suppress'                => 'Təftişçi',

# Book sources
'booksources'               => 'Kitab mənbələri',
'booksources-search-legend' => 'Kitab mənbələri axtar',
'booksources-isbn'          => 'ISBN:',
'booksources-go'            => 'Seç',
'booksources-text'          => 'Aşağıda yeni və işlənmiş kitablar satan xarici keçidlərdə siz axtardığınız kitab haqqında əlavə məlumat ala bilərsiz:',

# Special:Log
'specialloguserlabel'  => 'İstifadəçi:',
'speciallogtitlelabel' => 'Başlıq:',
'log'                  => 'Loglar',
'all-logs-page'        => 'Bütün ictimai qeydlər',
'alllogstext'          => '{{SITENAME}} üçün bütün mövcud qeydlərin birgə göstərişi.
Qeyd növü, istifadəçi adı və ya təsir edilmiş səhifəni seçməklə daha spesifik ola bilərsiniz.',
'logempty'             => 'Jurnalda uyğun qeyd tapılmadı.',
'log-title-wildcard'   => 'Bu mətnlə başlayan başlıqları axtar',

# Special:AllPages
'allpages'          => 'Bütün səhifələr',
'alphaindexline'    => '$1 məqaləsindən $2 məqaləsinə kimi',
'nextpage'          => 'Sonrakı səhifə ($1)',
'prevpage'          => 'Əvvəlki səhifə ($1)',
'allpagesfrom'      => 'Bu hərflə başlayan səhifələri göstər:',
'allpagesto'        => 'Bu hərflə başlayan səhifələrədək göstər:',
'allarticles'       => 'Bütün məqalələr',
'allinnamespace'    => 'Bütün səhifələr ($1 səhifələri)',
'allnotinnamespace' => 'Bütün səhifələr (not in $1 namespace)',
'allpagesprev'      => 'Əvvəlki',
'allpagesnext'      => 'Sonrakı',
'allpagessubmit'    => 'Seç',
'allpagesprefix'    => 'Bu prefiksli səhifələri göstər:',

# Special:Categories
'categories'                    => 'Kateqoriyalar',
'categoriespagetext'            => 'Aşağıdakı {{PLURAL:$1|kateqoriyada|kateqoriyalarda}} səhifələr, yaxud media-fayllar var.
[[Special:UnusedCategories|İstifadə olunmayan kateqoriyalar]] burada göstərilməyib.
Həmçinin, [[Special:WantedCategories|tələb olunan kateqoriyalara]] baxın.',
'special-categories-sort-count' => 'miqdara görə tənzimlə',
'special-categories-sort-abc'   => 'əlifba sırası ilə düz',

# Special:DeletedContributions
'deletedcontributions'             => 'Silinmiş istifadəçi fəaliyyətləri',
'deletedcontributions-title'       => 'Silinmiş istifadəçi fəaliyyətləri',
'sp-deletedcontributions-contribs' => 'fəaliyyət',

# Special:LinkSearch
'linksearch'      => 'Xarici keçidlər',
'linksearch-pat'  => 'Axtarış sxemi:',
'linksearch-ns'   => 'Adlar fəzası:',
'linksearch-ok'   => 'Axtar',
'linksearch-line' => '$2-dən $1 keçid verilib',

# Special:ListUsers
'listusers-submit'   => 'Göstər',
'listusers-noresult' => 'İstifadəçi tapılmadı.',
'listusers-blocked'  => '(bloklandı)',

# Special:ActiveUsers
'activeusers'            => 'Aktiv istifadəçilərin siyahısı',
'activeusers-count'      => '$1 {{PLURAL:$1|edit|redaktə}} son {{PLURAL:$3|day|$3 gün}}',
'activeusers-hidebots'   => 'Botları gizlə',
'activeusers-hidesysops' => 'İdarəçiləri gizlə',
'activeusers-noresult'   => 'İstifadəçi tapılmadı.',

# Special:Log/newusers
'newuserlogpage'              => 'Yeni istifadəçilərin qeydiyyatı',
'newuserlogpagetext'          => 'Yeni qeydiyyatdan keçmiş istifadəçilərin siyahısı.',
'newuserlog-byemail'          => 'parol e-maillə göndərildi',
'newuserlog-create-entry'     => 'Yeni istifadəçi hesabı',
'newuserlog-create2-entry'    => 'Yeri $1 hesabı açıldı',
'newuserlog-autocreate-entry' => 'Hesab avtomatik olaraq yaradıldı',

# Special:ListGroupRights
'listgrouprights'                      => 'İstifadəçi qruplarının hüquqları',
'listgrouprights-summary'              => 'Bu vikidə olan istifadəçi siyahıları və onların hüquqları aşağıda göstərilmişdir.
Fərdi hüquqlar haqqında əlavə məlumatı [[{{MediaWiki:Listgrouprights-helppage}}]] səhifəsində tapa bilərsiniz',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Verilmiş hüquqlar</span>
* <span class="listgrouprights-revoked">Ləğv edilmiş hüquqlar</span>',
'listgrouprights-group'                => 'Qrup',
'listgrouprights-rights'               => 'Hüquqlar',
'listgrouprights-helppage'             => 'Help:Qrup hüquqları',
'listgrouprights-members'              => '(üzvləri)',
'listgrouprights-right-display'        => '<span class="listgrouprights-granted">$1 <tt>($2)</tt></span>',
'listgrouprights-right-revoked'        => '<span class="listgrouprights-revoked">$1 <tt>($2)</tt></span>',
'listgrouprights-addgroup'             => '{{PLURAL:$2|Qrupu}} əlavə et: $1',
'listgrouprights-removegroup'          => '{{PLURAL:$2|Qrupu}} sil: $1',
'listgrouprights-addgroup-all'         => 'Bütün qrupları əlavə et',
'listgrouprights-removegroup-all'      => 'Bütün qrupları sil',
'listgrouprights-addgroup-self'        => 'Öz hesabına $1 {{PLURAL:$2|qrupunu|qruplarını}} əlavə et',
'listgrouprights-removegroup-self'     => 'Öz hesabından $1 {{PLURAL:$2|qrupunu|qruplarını}} sil',
'listgrouprights-addgroup-self-all'    => 'Bütün qrupları öz hesabına əlavə edə bilər',
'listgrouprights-removegroup-self-all' => 'Bütün qrupları öz hesabından çıxara bilər',

# E-mail user
'mailnologin'         => 'Ünvan yoxdur',
'emailuser'           => 'İstifadəçiyə e-məktub yolla',
'emailpage'           => 'İstifadəçiyə e-məktub yolla',
'usermailererror'     => 'Elektron poçtla məlumat göndərilən zaman xəta baş vermişdir:',
'defemailsubject'     => '{{SITENAME}} e-məktub',
'usermaildisabled'    => 'İstifadəçi e-maili işləmir',
'noemailtitle'        => 'E-məktub ünvanı yoxdur',
'noemailtext'         => 'Bu istifadəçi işlək e-məktub ünvanını qeyd etməmişdir.',
'nowikiemailtitle'    => 'E-poçtlara icazə verilmir',
'emailtarget'         => 'Qəbul edən istifadəçinin adını daxil edin',
'emailusername'       => 'İstifadəçi adı:',
'emailusernamesubmit' => 'Göndər',
'email-legend'        => 'Digər {{SITENAME}} istifadəçilərinə ismarıc yollamaq',
'emailfrom'           => 'Kimdən:',
'emailto'             => 'Kimə',
'emailsubject'        => 'Mövzu:',
'emailmessage'        => 'Mesaj:',
'emailsend'           => 'Göndər',
'emailccme'           => 'Məktubun surətini elektron ünvanıma göndər.',
'emailccsubject'      => ' $1: $2-yə olan ismarıclarınızın surəti',
'emailsent'           => 'E-məktub göndərildi',
'emailsenttext'       => 'E-məktub mesajınız göndərildi.',

# User Messenger
'usermessage-summary'  => 'Sistem mesajı qoyun.',
'usermessage-editor'   => 'Sistem məlumatları',
'usermessage-template' => 'MediaWiki:İstifadəçi müzakirəsi',

# Watchlist
'watchlist'            => 'İzlədiyim səhifələr',
'mywatchlist'          => 'İzlədiyim səhifələr',
'watchlistfor2'        => '$1 $2 üçün',
'nowatchlist'          => 'İzləmə siyahınız böşdur.',
'watchnologin'         => 'Daxil olmamısınız',
'watchnologintext'     => 'İzləmə siyahınızda dəyişiklik aparmaq üçün [[Special:UserLogin|daxil olmalısınız]].',
'addwatch'             => 'İzləmə siyahısına əlavə et',
'addedwatchtext'       => '"[[:$1]]" səhifəsi [[Special:Watchlist|izlədiyiniz səhifələr]] siyahısına əlavə edildi. Bu səhifədə və əlaqəli müzakirə səhifəsindəki bütün dəyişikliklər orada göstəriləcək və səhifə asanlıqla seçiləbilmək üçün [[Special:RecentChanges|son dəyişikliklərdə]] qalın şriftlərlə görünəcəkdir. <p> Səhifəni izləmə siyahınızdan çıxarmaq üçün yan lövhədəki "izləmə" düyməsinə vurun.',
'removedwatchtext'     => '"[[:$1]]" səhifəsi [[Special:Watchlist|izləmə siyahınızdan]] çıxarıldı.',
'watch'                => 'İzlə',
'watchthispage'        => 'Bu səhifəni izlə',
'unwatch'              => 'İzləmə',
'unwatchthispage'      => 'İzləmə',
'notanarticle'         => 'Səhifə boşdur',
'notvisiblerev'        => 'Başqa istifadıçinin son dəyişikliyi silinib',
'watchnochange'        => 'Verilən vaxt ərzində heç bir izlədiyiniz səhifə redaktə edilməmişdir.',
'watchlist-details'    => 'Müzakirə səhifələrini çıxmaq şərtilə {{PLURAL:$1|$1 səhifəni|$1 səhifəni}} izləyirsiniz.',
'wlheader-enotif'      => '*  E-məktubla bildiriş aktivdir.',
'wlheader-showupdated' => "* Son ziyarətinizdən sonra edilən dəyişikliklər '''qalın şriftlərlə''' göstərilmişdir.",
'watchmethod-recent'   => 'yeni dəyişikliklər izlənilən səhifələr üçün yoxlanılır',
'watchmethod-list'     => 'izlənilən səhifələr yeni dəyişikliklər üçün yoxlanılır',
'watchlistcontains'    => 'İzləmə siyahınızda $1 {{PLURAL:$1|səhifə|səhifə}} var.',
'iteminvalidname'      => "'$1' ilə bağlı problem, adı düzgün deyil...",
'wlnote'               => "Aşağıdakı {{PLURAL:$1|'''$1''' dəyişiklik|'''$1''' dəyişiklik}} son {{PLURAL:$2|saatda|'''$2''' saatda}} edilmişdir.",
'wlshowlast'           => 'Bunları göstər: son $1 saatı $2 günü $3',
'watchlist-options'    => 'İzlədiyim səhifələrin nizamlamaları',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'İzlənilir...',
'unwatching' => 'İzlənilmir...',

'enotif_mailer'                => '{{SITENAME}} Bildiriş Xidməti',
'enotif_reset'                 => 'Baxılmış bütün səhifələri işarələ.',
'enotif_newpagetext'           => 'Bu səhifə yeni səhifədir.',
'enotif_impersonal_salutation' => '{{SITENAME}} istifadəçisi',
'changed'                      => 'dəyişdi',
'created'                      => 'yaradıldı',
'enotif_subject'               => '{{SITENAME}} saytındakı $PAGETITLE səhifəsi $PAGEEDITOR tərəfindən $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Sonuncu ziyarətinizdən indiyədək olan bütün dəyişiklikləri görmək üçün baxın: $1.',
'enotif_lastdiff'              => 'Bu dəyişikliyi görmək üçün $1 səhifəsinə baxın.',
'enotif_anon_editor'           => 'qeydiyyatsız istifadəçi $1',
'enotif_body'                  => 'Hörmətli $WATCHINGUSERNAME,

{{SITENAME}} veb-saytındakı $PAGETITLE adlı səhifə $PAGEEDITDATE tarixində $PAGEEDITOR tərəfindən $CHANGEDORCREATED. Səhifənin sonuncu versiyasına baxmaq üçün $PAGETITLE_URL keçidindən istifadə edin.

$NEWPAGE

Dəyişikliyi edən istifadəçinin izahı: $PAGESUMMARY $PAGEMINOREDIT

Səhifəni dəyişdirən istifadəçinin əlaqə məlumatları:
e-poçt: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Siz haqqında söhbət gedən səhifəyə baxanadək səhifədəki digər dəyişikliklərlə bağlı başqa bildiriş məktubu almayacaqsınız. Siz həmçinin, izləmə siyahınızdakı bütün səhifələrlə bağlı bildiriş məlumatlarını silə bilərsiniz.

               {{SITENAME}} saytının xəbərdarlıq sistemi.

--
İzləmə siyahısının tənzimləmələrini dəyişmək üçün:
{{canonicalurl:Special:Watchlist/edit}}

Yardım və təklifləriniz üçün:
{{canonicalurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Səhifəni sil',
'confirm'                => 'Təsdiq et',
'excontent'              => "Köhnə məzmun: '$1'",
'excontentauthor'        => "tərkib: '$1' (və '[[Special:Contributions/$2|$2]]' tarixçədə fəaliyyəti qeyd edilən yeganə istifadəçidir)",
'exbeforeblank'          => "Silinmədən əvvəlki məzmun: '$1'",
'exblank'                => 'səhifə boş',
'delete-confirm'         => 'Silinən səhifə: "$1"',
'delete-backlink'        => '← $1',
'delete-legend'          => 'Sil',
'historywarning'         => "'''Xəbərdarlıq:''' Silinəcək səhifənin tarixçəsində qeyd olunmuş $1 {{PLURAL:$1|redaktə|redaktə}} var:",
'confirmdeletetext'      => 'Bu səhifə və ya fayl bütün tarixçəsi ilə birlikdə birdəfəlik silinəcək. Bunu [[{{MediaWiki:Policy-url}}|qaydalara]] uyğun etdiyinizi və əməliyyatın nəticələrini başa düşdüyünüzü təsdiq edin.',
'actioncomplete'         => 'Fəaliyyət tamamlandı',
'actionfailed'           => 'Əməliyyat yerinə yetirilmədi',
'deletedtext'            => '"$1" silindi.
Sonuncu silinmələrə bax: $2.',
'deletedarticle'         => '"[[$1]]" silindi',
'suppressedarticle'      => '"[[$1]]" gizlədildi',
'dellogpage'             => 'Silmə qeydləri',
'dellogpagetext'         => 'Ən son silinmiş səhifələrin siyahısı.',
'deletionlog'            => 'Silmə jurnal qeydləri',
'reverted'               => 'Daha əvvəlki versiya bərpa edildi',
'deletecomment'          => 'Səbəb:',
'deleteotherreason'      => 'Digər/əlavə səbəb:',
'deletereasonotherlist'  => 'Digər səbəb',
'deletereason-dropdown'  => '*Əsas silmə səbəbi
** Müəllif istəyi
** Müəllif hüququ pozuntusu
** Vandalizm',
'delete-edit-reasonlist' => 'Silmə səbəblərinin redaktəsi',

# Rollback
'rollback'          => 'Əvvəlki versiya',
'rollback_short'    => 'əvvəlki halına qaytar',
'rollbacklink'      => 'əvvəlki halına qaytar',
'rollbackfailed'    => 'Geri qaytarma uğursuzdur',
'cantrollback'      => 'Redaktə geri qaytarıla bilməz; axırıncı redaktə səhifədə olan yeganə fəaliyyətdir.',
'revertpage'        => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Müzakirə]]) tərəfindən edilmiş dəyişikliklər [[User:$1|$1]] tərəfindən edilmiş dəyişikliklərə qaytarıldı.',
'revertpage-nouser' => '(istifadəçi adı çıxarılmış) tərəfindən edilən dəyişikliklər [[User:$1|$1]] tərəfindən edilən son dəyişikliyə geri alındı',
'rollback-success'  => '$1 tərəfindən edilmiş redaktələr geri qaytarıldı; $2 tərəfindən yaradılmış son versiya bərpa olundu.',

# Edit tokens
'sessionfailure-title' => 'Giriş səhvi',

# Protect
'protectlogpage'              => 'Mühafizə etmə qeydləri',
'protectedarticle'            => 'mühafizə edildi "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]" səhifəsi üçün mühafizə səviyyəsi dəyişildi',
'unprotectedarticle'          => 'mühafizə kənarlaşdırıldı "[[$1]]"',
'protect-title'               => '"$1" üçün mühafizə səviyyəsinin dəyişdirilməsi',
'prot_1movedto2'              => '[[$1]] adı dəyişildi. Yeni adı: [[$2]]',
'protect-backlink'            => '← $1',
'protect-legend'              => 'Qorumayı təsdiq et',
'protectcomment'              => 'Səbəb:',
'protectexpiry'               => 'Vaxtı bitib',
'protect_expiry_invalid'      => 'Mühafizənin bitmə vaxtı səhvdir.',
'protect_expiry_old'          => 'Bitmə vaxtı keçmişdir.',
'protect-unchain-permissions' => 'Mühafizənin əlavə parametrlərini açmaq',
'protect-text'                => "Siz '''$1''' səhifəsinin mühafizə səviyyəsini görə və dəyişə bilərsiniz.",
'protect-locked-blocked'      => "Səhifənin bloklu olduğu müddətdə siz mühafizə səviyyəsini dəyişə bilməzsiniz.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-locked-dblock'       => "Verilənlər bazası kilidli olduğu üçün mühafizə səviyyəsi dəyişilə bilməz.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-locked-access'       => "Sizin hesabınızın mühafizə səviyyəsini dəyişməyə ixtiyarı yoxdur.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-cascadeon'           => 'Bu səhifə mühafizəlidir, çünki bu səhifə {{PLURAL:$1|başqa bir|başqa bir}} səhifədən kaskad mühafizə edilmişdir. Siz bu səhifənin mühafizə səviyyəsini dəyişdirə bilərsiniz, bu kaskad mühafizəyə təsir etməyəcək.',
'protect-default'             => 'Bütün istifadəçilərə icazə ver',
'protect-fallback'            => '"$1" icazəsi tələb olunur',
'protect-level-autoconfirmed' => 'Yeni və anonim istifadəçiləri blokla',
'protect-level-sysop'         => 'Yalnız idarəçilər',
'protect-summary-cascade'     => 'kaskad mühafizə',
'protect-expiring'            => '$1 (UTC)- tarixində vaxtı bitir',
'protect-expiry-indefinite'   => 'müddətsiz',
'protect-cascade'             => 'Kaskad mühafizəsi - bu səhifəyə daxil bütün səhifələri mühafizə et',
'protect-cantedit'            => 'Bu səhifənin mühafizə dərəcəsini dəyişdirə bilməzsiniz, çünki bu dəyişiklik üçün hüququnuz yoxdur.',
'protect-othertime'           => 'Başqa vaxt:',
'protect-othertime-op'        => 'Başqa vaxt',
'protect-existing-expiry'     => 'Mövcud bitiş zamanı: $3, $2',
'protect-otherreason'         => 'Digər/əlavə səbəb:',
'protect-otherreason-op'      => 'Digər səbəb',
'protect-edit-reasonlist'     => 'Mühafizə səbəblərinin redaktəsi',
'protect-expiry-options'      => '1 saat:1 hour,1 gün:1 day,1 həftə:1 week,2 həftə:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 il:1 year,Müddətsiz:infinite',
'restriction-type'            => 'Hüquqlar:',
'restriction-level'           => 'Məhdudiyyət dərəcəsi:',
'minimum-size'                => 'Minimum həcm',
'maximum-size'                => 'Maksimum həcm',
'pagesize'                    => '(baytlar)',

# Restrictions (nouns)
'restriction-edit'   => 'Redaktə',
'restriction-move'   => 'Adını dəyiş',
'restriction-create' => 'Yarat',
'restriction-upload' => 'Yüklə',

# Restriction levels
'restriction-level-sysop'         => 'tam mühafizə',
'restriction-level-autoconfirmed' => '(yarım-mühafizə)',
'restriction-level-all'           => 'istənilən səviyyə',

# Undelete
'undelete'                     => 'Silinmiş səhifələri göstər',
'undeletepage'                 => 'Silinmiş səhifələri göstər və ya bərpa et',
'undeletepagetitle'            => "'''Aşağıdakı, [[:$1|$1]] səhifəsinin silinmiş dəyişikliklərindən ibarətdir'''.",
'viewdeletedpage'              => 'Silinmiş səhifələri göstər',
'undelete-fieldset-title'      => 'Dəyişiklikləri geri yüklə',
'undelete-revision'            => '$3 tərəfindən $1 səhifəsinin silinmiş redaktəsi ($4 tarixinden bəri, $5 saatda):',
'undelete-nodiff'              => 'Əvvəlki redaktə tapılmadı.',
'undeletebtn'                  => 'Bərpa et',
'undeletelink'                 => 'bax/bərpa et',
'undeleteviewlink'             => 'görünüş',
'undeletereset'                => 'Qur',
'undeleteinvert'               => 'Seçilən xaricindəkiləri',
'undeletecomment'              => 'Səbəb:',
'undeletedarticle'             => '"[[$1]]" məqaləsi bərpa edilmişdir',
'undeletedrevisions'           => 'Cəmi {{PLURAL:$1|1 redaktə|$1 redaktə}} geri qaytarıldı.',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 versiya|$1 versiya}} və {{PLURAL:$2|1 fayl|$2 fayl}} bərpa edildi',
'undeletedfiles'               => '{{PLURAL:$1|1 fayl|$1 fayl}} bərpa olundu',
'cannotundelete'               => 'Bərpaetmə xətası. Başqa istifadəçi sizdən əvvəl səhifəni bərpa edib.',
'undeletedpage'                => "'''$1 bərpa edildi'''

Məqalələrin bərpa edilməsi və silinməsi haqqında son dəyişiklikləri nəzərdən keçirmək üçün [[Special:Log/delete|silmə qeydlərinə]] baxın.",
'undelete-header'              => 'Son silinmiş səhifələrə baxmaq üçün [[Special:Log/delete|silmə qeydlərinə]] bax.',
'undelete-search-box'          => 'Silinmiş səhifələri axtar.',
'undelete-search-prefix'       => 'Bununla başlayan səhifəliri göstər:',
'undelete-search-submit'       => 'Axtar',
'undelete-no-results'          => 'Silmə arxivində birbiriylə örtüşən heç bir səhifə tapılmadı.',
'undelete-filename-mismatch'   => 'Faylın $1 tarixli versiyasını bərpa etmək mümkün deyil: faylın adında uyğunsuzluq',
'undelete-bad-store-key'       => 'Faylın $1 tarixli versiyasını bərpa etmək mümkün deyil: fayl silinməzdən əvvəl mövcud deyildi.',
'undelete-cleanup-error'       => 'İstifadəsiz "$1" arxiv faylının silinmə xətası.',
'undelete-missing-filearchive' => '$1 nömrəli arxiv faylını bərpa etmək mümkün deyil, çünki o məlumat bazasında yoxdur. Ola bilər fayl artıq bərpa olunub.',
'undelete-error-short'         => 'Fayl silinərkən xəta: $1',
'undelete-error-long'          => 'Fayl silinərkən üzə çıxan xətalar:

$1',
'undelete-show-file-confirm'   => '"<nowiki>$1</nowiki>" faylının $2 $3 tarixli silinmiş bir redaktəsini görmək istədiyinizdən əminsizinizmi?',
'undelete-show-file-submit'    => 'Bəli',

# Namespace form on various pages
'namespace'             => 'Adlar fəzası:',
'invert'                => 'Seçilən xaricindəkiləri',
'namespace_association' => 'Əlaqəli ad sahəsi',
'blanknamespace'        => '(Ana)',

# Contributions
'contributions'       => 'İstifadəçi fəaliyyəti',
'contributions-title' => '$1 istifadəçi fəaliyyətləri',
'mycontris'           => 'Fəaliyyətim',
'contribsub2'         => '$1 ($2)',
'nocontribs'          => 'Bu kriteriyaya uyğun redaktələr tapılmadı',
'uctop'               => '(son)',
'month'               => 'Ay',
'year'                => 'Axtarışa bu tarixdən etibarən başla:',

'sp-contributions-newbies'             => 'Ancaq yeni istifadəçilərin fəaliyyətlərini göstər',
'sp-contributions-newbies-sub'         => 'Yeni istifadəçilər üçün',
'sp-contributions-newbies-title'       => 'Yeni hesablar üçün istifadəçi fəaliyyətləri',
'sp-contributions-blocklog'            => 'Bloklama qeydləri',
'sp-contributions-deleted'             => 'silinmiş istifadəçi fəaliyyətləri',
'sp-contributions-uploads'             => 'yüklənənlər',
'sp-contributions-logs'                => 'Loqlar',
'sp-contributions-talk'                => 'Müzakirə',
'sp-contributions-userrights'          => 'istifadəçi hüquqları idarəsi',
'sp-contributions-blocked-notice'      => 'Bu istifadəçi hal-hazırda bloklanmışdır.
Bloklama qeydlərinin sonuncusu aşağıda göstərilmişdir:',
'sp-contributions-blocked-notice-anon' => 'Bu IP-ünvan hal-hazırda bloklanmışdır.
Bloklama qeydlərinin sonuncusu aşağıda göstərilmişdir:',
'sp-contributions-search'              => 'Fəaliyyətləri axtar',
'sp-contributions-username'            => 'IP-ünvanı və ya istifadəçi adı:',
'sp-contributions-toponly'             => 'Yalnız ən son dəyişiklikləri göstər',
'sp-contributions-submit'              => 'Axtar',

# What links here
'whatlinkshere'            => 'Bu səhifəyə bağlantılar',
'whatlinkshere-title'      => '"$1" məqaləsinə keçid verən səhifələr',
'whatlinkshere-page'       => 'Səhifə:',
'whatlinkshere-backlink'   => '← $1',
'linkshere'                => "'''[[:$1]]''' səhifəsinə istinad edən səhifələr:",
'nolinkshere'              => "'''[[:$1]]''' səhifəsinə keçid verən səhifə yoxdur.",
'nolinkshere-ns'           => "Seçilmiş ad aralığında heç bir səhifə '''[[:$1]]''' səhifəsinə keçid vermir.",
'isredirect'               => 'İstiqamətləndirmə səhifəsi',
'istemplate'               => 'daxil olmuş',
'isimage'                  => 'şəkil üçün keçid',
'whatlinkshere-prev'       => '{{PLURAL:$1|əvvəlki|əvvəlki $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|növbəti|növbəti $1}}',
'whatlinkshere-links'      => '← keçidlər',
'whatlinkshere-hideredirs' => 'yönləndirmələri $1',
'whatlinkshere-hidetrans'  => 'Əlavələri $1',
'whatlinkshere-hidelinks'  => 'keçidləri $1',
'whatlinkshere-hideimages' => 'Şəkillərə keçidləri $1',
'whatlinkshere-filters'    => 'Filtrlər',

# Block/unblock
'autoblockid'                     => 'Avtoblok #$1',
'block'                           => 'İstifadəçini blokla',
'unblock'                         => 'İstifadəçinin blokunu götür',
'blockip'                         => 'İstifadəçini blokla',
'blockip-title'                   => 'İstifadəçini blokla',
'blockip-legend'                  => 'İstifadəçinin bloklanması',
'ipadressorusername'              => 'IP-ünvanı və ya istifadəçi adı',
'ipbexpiry'                       => 'Bitmə müddəti:',
'ipbreason'                       => 'Səbəb:',
'ipbreasonotherlist'              => 'Digər səbəb',
'ipbreason-dropdown'              => '*Bloklama səbəbləri:
** Yalan məlumatların əlavə edilməsi
** Səhifənin məzmununun silinməsi
** Xarici saytlara spam-keçidlər
** Səhifəyə mənasız və yararsız əlavələrə görə
** Hədə və təqiblərə görə
** Təhqirə görə
** Çoxsaylı hesabdan sui istifadəyə görə
** Qadağan olunmuş istifadəçi adına görə',
'ipbcreateaccount'                => 'Hesab açmanı məhdudlaşdır',
'ipbsubmit'                       => 'Bu istifadəçini blokla',
'ipbother'                        => 'Başqa vaxt',
'ipboptions'                      => '2 saat:2 hours,1 gün:1 day,3 gün:3 days,1 həftə:1 week,2 həftə:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 il:1 year,müddətsiz:infinite',
'ipbotheroption'                  => 'başqa',
'ipbotherreason'                  => 'Başqa/əlavə səbəb:',
'ipbhidename'                     => 'İstifadəçi adını redaktələrdə və siyahılarda gizlət',
'ipbwatchuser'                    => 'Bu istifadəçinin müzakirə və istifadəçi səhifəsini izlə',
'ipb-disableusertalk'             => 'Bu istifadəçi bloklu olarkən öz müzakirə səhifəsində redaktə etməsini əngəllə',
'ipb-change-block'                => 'Bu səbəblərlə istifadəçini yenidən blokla',
'ipb-confirm'                     => 'Bloku təsdiqlə',
'badipaddress'                    => 'Səhv IP',
'blockipsuccesssub'               => 'bloklandı',
'blockipsuccesstext'              => '[[Special:Contributions/$1| $1]]bloklanıb..<br />
Bax [[Special:IPBlockList|IP blok siyahısı]] bloklanmış IP-lər.',
'ipb-blockingself'                => 'Özünü bloklayacaqsınız.! Bunu etmək istədiyinizdən əminsinizmi?',
'ipb-confirmhideuser'             => 'İstifadəçini bloklamaq və redaktə siyahısından onun adını silmək üzərəsiniz. Bunu etmək istədiyinizdən əminsinizmi?',
'ipb-edit-dropdown'               => 'Bloklama səbəblərini redaktə et',
'ipb-unblock-addr'                => '$1 üzərindəki blok götürüldü',
'ipb-unblock'                     => 'Bloku götür',
'ipb-blocklist'                   => 'Mövcud blokları göstər',
'ipb-blocklist-contribs'          => '$1 istifadəçi fəaliyyətləri',
'unblockip'                       => 'İstifadəçinin blokunu götür',
'unblockiptext'                   => 'Əvvəlcədən bloklanmış bir IP ünvanına və ya istifadəçi adına yazma geri vermek için aşağıdakı formadan istifadə edin.',
'ipusubmit'                       => 'Bu bloku götür',
'unblocked'                       => '[[User:$1|$1]] - nin bloku götürüldü',
'unblocked-range'                 => '$1-nin bloku götürüldü',
'unblocked-id'                    => '$1-nin bloku götürüldü',
'blocklist'                       => 'Bloklanmış istifadəçilər',
'ipblocklist'                     => 'Bloklanmış istifadəçilər',
'ipblocklist-legend'              => 'Bloklanmış istifadəçini axtar',
'blocklist-userblocks'            => 'Hesab bloklarını gizlət',
'blocklist-tempblocks'            => 'Müvəqqəti blokları gizlə',
'blocklist-addressblocks'         => 'Tək IP bloklarını gizlə',
'blocklist-timestamp'             => 'Vaxt',
'blocklist-target'                => 'Hədəf',
'blocklist-expiry'                => 'Bitiş tarixi',
'blocklist-by'                    => 'Bloklamış idarəçi',
'blocklist-params'                => 'Blok parametrləri',
'blocklist-reason'                => 'Səbəb',
'ipblocklist-submit'              => 'Axtar',
'ipblocklist-localblock'          => 'Yerli blok',
'ipblocklist-otherblocks'         => 'Başqa {{PLURAL:$1|bloklama|bloklamalar}}',
'infiniteblock'                   => 'müddətsiz',
'expiringblock'                   => 'son tarix $1 saat $2',
'anononlyblock'                   => 'yalnız anonim istifadəçi',
'noautoblockblock'                => 'avtobloklama qeyri-mümkündür',
'createaccountblock'              => 'Yeni hesab yaratma bloklanıb',
'emailblock'                      => 'E-mail bloklanıb',
'blocklist-nousertalk'            => 'Müzakirə səhifəsini redaktə edə bilməz.',
'ipblocklist-empty'               => 'Blok siyahısı boşdur.',
'ipblocklist-no-results'          => 'Tələb olunan IP ünvanı və ya istifadəçi bloklanmadı.',
'blocklink'                       => 'blokla',
'unblocklink'                     => 'bloklamanı kənarlaşdır',
'change-blocklink'                => 'bloklamanı dəyişdir',
'contribslink'                    => 'Köməklər',
'autoblocker'                     => 'Avtomatik olaraq bloklanmısınız. Çünki, qısa müddət əvvəl sizin IP-ünvanınız "[[User:$1|$1]]" tərəfindən istifadə edilmişdir.
$1 adlı istifadəçinin bloklanma səbəbi: "$2"',
'blocklogpage'                    => 'Bloklama qeydləri',
'blocklog-showlog'                => 'Bu istifadəçi daha əvvəl bloklanmışdır. Bloklama gündəliyi referans üçün aşağıda göstərilib:',
'blocklog-showsuppresslog'        => 'Bu istifadəçi daha əvvəl bloklanmışdır. Bloklama gündəliyi referans üçün aşağıda göstərilib:',
'blocklogentry'                   => 'tərəfindən [[$1]] bloklandı, blok müddəti: $2 $3',
'reblock-logentry'                => '[[$1]] üçün son tarixi $2 $3 olmaq üzərə blok parametrləri dəyişdirildi',
'blocklogtext'                    => 'İstifadəçilərin bloklanması və blokun götürülməsi siyahısı.
Avtomatik bloklanmış IP-ünvanlar burada göstərilmir.
Hal-hazırkı [[Special:BlockList|qadağaların və bloklamaların siyahısı]]na bax.',
'unblocklogentry'                 => '$1 üzərindəki blok götürüldü',
'block-log-flags-anononly'        => 'yalnız qeydiyyatsız istifadəçilər',
'block-log-flags-nocreate'        => 'Yeni hesab yaratma bloklanıb',
'block-log-flags-noautoblock'     => 'avtobloklama qeyri-mümkündür',
'block-log-flags-noemail'         => 'E-mail bloklanıb',
'block-log-flags-nousertalk'      => 'Müzakirə səhifəsini redaktə edə bilməz.',
'block-log-flags-angry-autoblock' => 'Avtoblok qüvvədədir',
'block-log-flags-hiddenname'      => 'İstifadəçi adı gizli',
'range_block_disabled'            => 'İdarəçilərə diapazonu bloklamaq qadağandır.',
'ipb_expiry_invalid'              => 'Bitmə vaxtı səhvdir',
'ipb_expiry_temp'                 => 'Gizli istifadəçi adı bloklamaları müddətsiz olmalıdır.',
'ipb_hide_invalid'                => 'İstifadəçi hesabınln gizlədilməsi qeyri-mümkündür; həddən çox redaktəsi var.',
'ipb_already_blocked'             => '"$1" artıq bloklanıb',
'ipb-needreblock'                 => '$1 artıq bloklanıb.
Bloklama şərtlərini dəyişmək istəyirsiniz?',
'ipb-otherblocks-header'          => 'Başqa {{PLURAL:$1|bloklama|bloklamalar}}',
'unblock-hideuser'                => 'İstifadəçi adı gizli olduğu üçün, bi bloku götürə bilməzsiniz.',
'ipb_cant_unblock'                => 'Xəta: Bloklama IDsi $1 tapılmadı. Bloklamanın götürülməsi mümkündür.',
'ip_range_invalid'                => 'Yanlış IP',
'blockme'                         => 'Məni blokla',
'proxyblocker'                    => 'Proksi bloklayıcı',
'proxyblocker-disabled'           => 'Bu funksiya əngəlləndi.',
'proxyblocksuccess'               => 'Oldu.',
'sorbs'                           => 'DNSBL',

# Developer tools
'lockdb'              => 'Verilənlər bazasını blokla',
'unlockdb'            => 'Verilənlər bazasından bloku götür',
'lockconfirm'         => 'Bəli, mən həqiqətən verilənlər bazasının bağlamaq istəyirəm.',
'unlockconfirm'       => 'Bəli, mən həqiqətən verilənlər bazasının blokunu açmaq istəyirəm.',
'lockbtn'             => 'Verilənlər bazasını blokla',
'unlockbtn'           => 'Verilənlər bazasından bloku götür',
'lockdbsuccesssub'    => 'Verilənlər bazası bloklanıb',
'unlockdbsuccesssub'  => 'Bloklanma verilənlər bazası silinib',
'unlockdbsuccesstext' => 'Verilənlər bazası bağlanmış aola bilər.',
'databasenotlocked'   => 'Verilənlər bazası bloklanmayıb.',
'lockedbyandtime'     => '(by {{GENDER:$1|$1}} on $2 at $3)',

# Move page
'move-page'                    => 'Dəyişdir $1',
'move-page-legend'             => 'Səhifənin adını dəyiş',
'movepagetext'                 => "Aşağıdakı formadan istifədə etmə səhifənin adını, bütün tarixçəsini də köçürməklə yeni başlığa dəyişəcək.
Əvvəlki başlıq yeni başlığa istiqamətləndirmə səhifəsinə çevriləcək.
Köhnə səhifəyə keçidləri avtomatik olaraq dəyişə bilərsiniz.
Bu seçimi etmədiyiniz halda, [[Special:DoubleRedirects|təkrarlanan]] və ya [[Special:BrokenRedirects|qırıq istiqamətləndirmələri]] yoxlamağı yaddan çıxarmayın.
Keçidlərin lazımi yerə istiqamətləndirilməsini təmin etmək sizin məsuliyyətinizdədir.

Nəzərə alın ki, hədəf başlığı altında bir səhifə mövcuddursa yerdəyişmə '''baş tutmayacaq'''. Buna həmin səhifənin boş olması və ya istiqamətləndirmə səhifəsi olması və keçmişdə redaktə edilməməsi halları istisnadır. Bu o deməkdir ki, səhvən adını dəyişdiyiniz səhifələri geri qaytara bilər, bununla yanaşı artıq mövcud olan səhifənin üzərinə başqa səhifə yaza bilməzsiniz.

'''XƏBƏRDARLIQ!'''
Bu yerdəyişmə populiyar səhifə üçün əsaslı və gözlənilməz ola bilər, ona görə də bu dəyişikliyi yerinə yetirməzdən əvvəl, bunun mümkün nəticələrini başa düşdüyünüzdən əmin olun.",
'movepagetalktext'             => "Uyğun müzakirə səhifəsi avtomatik hərəkət edəcək '''əgər:'''
* boş olmayan müzakirə səhifəsi yeni adla artıq mövcuddursa, və ya
* Siz bayrağı aşağıdan götürsəniz.

Həmin hallarda , ehtiyac yaranarsa siz səhifələri əllə birləşdirmək məcburiyyətində qalacaqsınız",
'movearticle'                  => 'Səhifənin adını dəyişdir',
'movenologin'                  => 'Sistemdə deyilsiniz',
'movenotallowed'               => 'Siz səhifələrin adını dəyişə bilməzsiniz.',
'movenotallowedfile'           => 'Siz faylların adını dəyişə bilməzsiniz.',
'cant-move-user-page'          => 'İstifadəçi səhifələrinin adını dəyişə bilməzsiniz (başlıqlardan başqa).',
'newtitle'                     => 'Yeni başlıq',
'move-watch'                   => 'Bu səhifəni izlə',
'movepagebtn'                  => 'Səhifənin adını dəyiş',
'pagemovedsub'                 => 'Yerdəyişmə edilmişdir',
'movepage-moved'               => '\'\'\'"$1" səhifəsi "$2" səhifəsinə yerləşdirilmişdir\'\'\'',
'movepage-moved-redirect'      => 'Yönləndirmə yaradıldı.',
'movepage-moved-noredirect'    => 'Yönləndirmənin yaradılmasının qarşııs alındı.',
'articleexists'                => 'Bu adda səhifə artıq mövcuddur və ya sizin seçdiyiniz ad uyğun deyil.
Zəhmət olmasa başqa ad seçin.',
'talkexists'                   => "'''Səhifənin adı dəyişdi, lakin müzakirə səhifəsi yeni adla olduğu üçün dəyişə bilmir. Zəhmət olmasa, onun adını özünüz dəyişin.'''",
'movedto'                      => 'dəyişdi',
'movetalk'                     => 'Bu səhifənin müzakirə səhifəsinin də adını dəyişdir.',
'move-subpages'                => 'Yarımsəhifələri köçür ($1-ə qədər)',
'move-talk-subpages'           => 'Müzakirə səhifələrinin alt səhifələrini köçür ($1-ə qədər)',
'movepage-page-moved'          => '$1 səhifəsi $2 səhifəsinə köçürülüb.',
'movepage-page-unmoved'        => '$1 səhifəsi $2 səhifəsinə köçürülə bilinmir.',
'1movedto2'                    => '[[$1]] adı dəyişildi. Yeni adı: [[$2]]',
'1movedto2_redir'              => '[[$1]] adı və istiqamətləndirmə dəyişildi: [[$2]]',
'move-redirect-suppressed'     => 'yönləndirmənin qarşısı alındı',
'movelogpage'                  => 'Yerdəyişmə qeydləri',
'movesubpage'                  => '{{PLURAL:$1|alt səhifə}}',
'movenosubpage'                => 'Bu səhifənin altsəhifəsi yoxdur.',
'movereason'                   => 'Səbəb:',
'revertmove'                   => 'Əvvəlki vəziyyətinə',
'delete_and_move'              => 'Sil və apar',
'delete_and_move_text'         => '==Hazırki məqalənin silinməsi lazımdır==

"[[$1]]" məqaləsi mövcuddur. Bu dəyişikliyin yerinə yetirilə bilməsi üçün həmin məqalənin silinməsini istəyirsinizmi?',
'delete_and_move_confirm'      => 'Bəli, səhifəni sil',
'delete_and_move_reason'       => 'Ad dəyişməyə yer açmaq üçün silinmişdir',
'selfmove'                     => 'Səhifənin hazırkı adı ilə dəyişmək istənilən ad eynidir. Bu əməliyyat yerinə yetirilə bilməz.',
'immobile-source-namespace'    => '"$1" ad aralığında səhifə adları dəyişmir.',
'immobile-target-namespace'    => 'Səhilərin "$1" ad aralığına daşınması qeyri-mümkündür',
'immobile-target-namespace-iw' => 'İntervikilər səhifə adının dəyişməsi üçün əsas ola bilməz.',
'immobile-source-page'         => 'Bu səhifənin adı dəyişdirilə bilməz.',
'immobile-target-page'         => 'Bu hədəf başlığına daşınmır.',
'imageinvalidfilename'         => 'Seçilmiş fayl adı keçərsizdir.',
'protectedpagemovewarning'     => "'''Xəbərdarlıq:''' Bu səhifə mühafizə edildiyi üçün onun adını yalnız idarəçilər dəyişə bilərlər.",

# Export
'export'            => 'Səhifələri ixrac et',
'exportcuronly'     => 'Bütün tarixçəni deyil, yalnız hal-hazırkı versiyanı daxil et',
'export-submit'     => 'İxrac',
'export-addcattext' => 'Səhifələri bu kateqoriyadan əlavə et:',
'export-addcat'     => 'Əlavə et',
'export-addnstext'  => 'Səhifələri adlar fəzasından əlavə et:',
'export-addns'      => 'Əlavə et',
'export-download'   => 'Faylı qeyd et',

# Namespace 8 related
'allmessages'                   => 'Sistem məlumatları',
'allmessagesname'               => 'Ad',
'allmessagesdefault'            => 'İlkin mətn',
'allmessagescurrent'            => 'İndiki mətn',
'allmessagestext'               => 'Bu MediaWiki-də olan sistem mesajlarının siyahısıdır. Əgər MediaWiki-ni lokallaşdırmaq işində kömək etmək isəyirsinizsə, lütfən [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] və [//translatewiki.net translatewiki.net]-ə baş çəkin.',
'allmessages-filter-legend'     => 'Filtr',
'allmessages-filter-unmodified' => 'Dəyişdirilməmiş',
'allmessages-filter-all'        => 'Hamısı',
'allmessages-filter-modified'   => 'Dəyişdirilmiş',
'allmessages-language'          => 'Dil:',
'allmessages-filter-submit'     => 'Keç',

# Thumbnails
'thumbnail-more'          => 'Böyüt',
'filemissing'             => 'Şəkil gözlənilir',
'thumbnail_error'         => 'Kiçik şəkil yaratma xətası: $1',
'djvu_page_error'         => 'DjVu səhifəsi əlçatmazdır',
'djvu_no_xml'             => 'DjVu üçün XML faylı almaq mümkün deyil.',
'thumbnail_image-missing' => 'Belə görünür ki, $1 faylı yoxdur',

# Special:Import
'import'                     => 'Səhifələri idxal et',
'importinterwiki'            => 'Vikilərarası çıxarma',
'import-interwiki-source'    => 'Mənbə viki/səhifə:',
'import-interwiki-history'   => 'Səhifənin dəyişmə tarixçələrinin hamısını köçür',
'import-interwiki-templates' => 'Bütün şablonlarla birlikdə',
'import-interwiki-submit'    => 'İdxal',
'import-upload-filename'     => 'Fayl adı:',
'import-comment'             => 'Şərh:',
'importstart'                => 'Çıxarılacaq səhifələr...',
'import-revision-count'      => '$1 {{PLURAL:$1|revision|dəyişiklik}}',
'importnopages'              => 'Çıxarılacaq səhifə yoxdur.',
'importfailed'               => 'Uğursuz çıxarış: <nowiki>$1</nowiki>',
'importcantopen'             => 'Çıxarılacaq fayl açılmadı',
'importbadinterwiki'         => 'Yanlış interviki keçidi',
'importnotext'               => 'Boş və ya mətn yoxdur',
'importsuccess'              => 'Çıxarma başa çatdı!',
'import-noarticle'           => 'Çıxarılacaq səhifə yoxdur!',
'xml-error-string'           => '$1 $2 sətrində, $3 sütünunda ($4 bayt): $5',
'import-upload'              => 'XML-veriləni yüklə',
'import-token-mismatch'      => 'Seans məlumatlarının itirilməsi. Lütfən, yenidən cəhd edin.',
'import-invalid-interwiki'   => 'Göstərilən vikidən köçürmək mümkün deyil',

# Import log
'importlogpage'                    => 'Çıxarılma gündəliyi',
'importlogpagetext'                => 'Səhifələrin idarəçilər tərəfindən digər vikilərdən dəyişiklik tarixçəsi ilə birlikdə köçürülməsi',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revision|dəyişiklik}}',
'import-logentry-interwiki'        => 'vikilərarası idxal $1',
'import-logentry-interwiki-detail' => '$2-dən $1 {{PLURAL:$1|dəyişiklik|dəyişikliklər}}',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'İstifadəçi səhifəniz',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Danışıq səhifəm',
'tooltip-pt-anontalk'             => 'Bu IP ünvanından redaktə olunmuş danışıqlar',
'tooltip-pt-preferences'          => 'Mənim nizamlamalarım',
'tooltip-pt-watchlist'            => 'İzləməyə götürdüyüm səhifələr',
'tooltip-pt-mycontris'            => 'Etdiyim dəyişikliklərin siyahısı',
'tooltip-pt-login'                => 'Daxil olmanız tövsiyə olunur, amma bu məcburi tələb deyil.',
'tooltip-pt-anonlogin'            => 'Daxil olmanız tövsiyə olunur, amma tələb olunmur.',
'tooltip-pt-logout'               => 'Sistemdən çıx',
'tooltip-ca-talk'                 => 'Məqalə haqqındə müzakirə edib, münasibətivi bildir',
'tooltip-ca-edit'                 => 'Bu səhifəni redaktə edə bilərsiniz. Lütfən əvvəlcə sınaq gostərişi edin.',
'tooltip-ca-addsection'           => 'Yeni bölmə yarat',
'tooltip-ca-viewsource'           => 'Bu səhifə dəyişikliklərdən mühafizə olunur. Amma siz onun mətninə baxa və mətnin surətini köçürə bilərsiniz.',
'tooltip-ca-history'              => 'Bu səhifənin keçmiş nüsxələri.',
'tooltip-ca-protect'              => 'Bu səhifəni mühafizə et',
'tooltip-ca-unprotect'            => 'Bu səhifənin mühafizəsini kənarlaşdır',
'tooltip-ca-delete'               => 'Bu səhifəni sil',
'tooltip-ca-undelete'             => 'Bu səhifəni silinmədən əvvəlki halına qaytarın',
'tooltip-ca-move'                 => 'Bu səhifənin adını dəyiş',
'tooltip-ca-watch'                => 'Bu səhifəni izlə',
'tooltip-ca-unwatch'              => 'Bu səhifənin izlənməsini bitir',
'tooltip-search'                  => '{{SITENAME}} səhifəsində axtar',
'tooltip-search-go'               => 'Əgər varsa, eyni adı daşıyan səhifəyə keç',
'tooltip-search-fulltext'         => 'Bu mətnin olduğu səhifələri axtar',
'tooltip-p-logo'                  => 'Ana səhifə',
'tooltip-n-mainpage'              => 'Ana səhifəni ziyarət edin',
'tooltip-n-mainpage-description'  => 'Ana Səhifəyə keç',
'tooltip-n-portal'                => 'Layihə haqqında, nələr edəbilərsiniz, axtardıqlarınızı harda tapmaq olar',
'tooltip-n-currentevents'         => 'Gündəlik xəbərlər ilə əlaqəli bilgilər',
'tooltip-n-recentchanges'         => 'Vikidəki son dəyişikliklər siyahısı.',
'tooltip-n-randompage'            => 'Təsadüfi məqaləyə keç',
'tooltip-n-help'                  => 'Yardım almaq üçün.',
'tooltip-t-whatlinkshere'         => 'Vikidə bu məqaləyə bağlantılar',
'tooltip-t-recentchangeslinked'   => 'Bu məqaləyə aid başqa səhifələrdə yeni dəyişikliklər',
'tooltip-feed-rss'                => 'Bu səhifə üçün RSS yayımı',
'tooltip-feed-atom'               => 'Bu səhifə üçün Atom yayımı',
'tooltip-t-contributions'         => 'Bu istifadəçinin redaktə etdiyi səhifələrin siyahısı',
'tooltip-t-emailuser'             => 'Bu istifadəçiyə e-məktub yolla',
'tooltip-t-upload'                => 'Yeni şəkil və ya multimedia faylı yüklə',
'tooltip-t-specialpages'          => 'Xüsusi səhifələrin siyahısı',
'tooltip-t-print'                 => 'Səhifənin çap versiyası',
'tooltip-t-permalink'             => 'Səhifənin bu versiyasına daimi keçid',
'tooltip-ca-nstab-main'           => 'Məqalənin məzmununu göstər',
'tooltip-ca-nstab-user'           => 'İstifadəçi səhifəsinə bax',
'tooltip-ca-nstab-media'          => 'Media-fayl',
'tooltip-ca-nstab-special'        => 'Bu xüsusi səhifə olduğu üçün redaktə edilə bilməz',
'tooltip-ca-nstab-project'        => 'Layihə səhifəsinə bax',
'tooltip-ca-nstab-image'          => 'Faylın səhifəsinə bax',
'tooltip-ca-nstab-mediawiki'      => 'Sistem məlumatına bax',
'tooltip-ca-nstab-template'       => 'Şablona bax',
'tooltip-ca-nstab-help'           => 'Kömək səhifəsi',
'tooltip-ca-nstab-category'       => 'Kateqoriya səhifəsini göstər',
'tooltip-minoredit'               => 'Bu dəyişikliyi kiçik redaktə kimi qeyd et',
'tooltip-save'                    => 'Dəyişiklikləri qeyd et [alt-s]',
'tooltip-preview'                 => 'Səhifəni qeyd etməzdən əvvəl bunu istifadə edib dəyişikliklərinizə baxın!',
'tooltip-diff'                    => 'Məqalədə etdiyiniz dəyişikliklərə baxın',
'tooltip-compareselectedversions' => 'Səhifənin seçilmiş iki versiyası arasındakı fərqi göstər',
'tooltip-watch'                   => 'Bu səhifəni izlədiyiniz səhifələrə əlavə et [alt-w]',
'tooltip-recreate'                => 'Əvvəl silinməsinə baxmayaraq səhifəni bərpa et',
'tooltip-upload'                  => 'Yükləməni başlat',
'tooltip-rollback'                => 'Sonuncu istifadəçi tərəfindən edilmiş bütün dəyişiklikləri bir dəfəyə geri qaytar',
'tooltip-undo'                    => 'Edilmiş dəyişikliyi geri qaytar və geri qaytarma səbəbini qeyd etmək üçün sınaq göstərişini aç',
'tooltip-preferences-save'        => 'Nizamlamaları saxla',
'tooltip-summary'                 => 'Qısa rezyume daxil edin',

# Stylesheets
'common.css'              => '/* Burada yerləşən CSS bütün skinlərə tətbiq olunur */',
'standard.css'            => '/* Burada yerləşən CSS Standard skin istifadəçilərinə tətbiq olunur */',
'nostalgia.css'           => '/* Burada yerləşən CSS Nostalgia skin istifadəçilərinə tətbiq olunur */',
'cologneblue.css'         => '/* Burada yerləşən CSS Cologne Blue skin istifadəçilərinə tətbiq olunur */',
'monobook.css'            => '/* Burada yerləşən CSS Monobook skin istifadəçilərinə tətbiq olunur */',
'myskin.css'              => '/* Burada yerləşən CSS MySkin skin istifadəçilərinə tətbiq olunur */',
'chick.css'               => '/* Burada yerləşən CSS Chick skin istifadəçilərinə tətbiq olunur */',
'simple.css'              => '/* Burada yerləşən CSS Simple skin istifadəçilərinə tətbiq olunur */',
'modern.css'              => '/* Burada yerləşən CSS Modern skin istifadəçilərinə tətbiq olunur */',
'vector.css'              => '/* Burada yerləşən CSS Vector istifadəçilərinə tətbiq olunur */',
'group-autoconfirmed.css' => '/* Burada yerləşən CSS yalnız avtotəsdiqlənmiş istifadəçilərə şamil olunur */',
'group-bot.css'           => '/* Burada yerləşən CSS yalnız botlara şamil olunur */',
'group-sysop.css'         => '/* Burada yerləşən CSS yalnız idarəçilərə şamil olunur */',
'group-bureaucrat.css'    => '/* Burada yerləşən CSS yalnız bürokratlara şamil olunur */',

# Scripts
'common.js'              => '/* Burada istifadəçilərin hamısı üçün bütün səhifələrdə istənilən JavaScript yüklənəcək */',
'standard.js'            => '/* Burada Standard skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'nostalgia.js'           => '/* Burada Nostalgia skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'cologneblue.js'         => '/* Burada Cologne Blue skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'monobook.js'            => '/* Burada MonoBook skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'myskin.js'              => '/* Burada MySkin skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'chick.js'               => '/* Burada Chick skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'simple.js'              => '/* Burada Simple skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'modern.js'              => '/* Burada Modern skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'vector.js'              => '/* Burada Vector skin istifadəçiləri üçün istənilən JavaScript yüklənəcək */',
'group-autoconfirmed.js' => '/* Burada istənilən JavaScript yalnız avtotəsdiqlənmiş istifadəçilər üçün yüklənəcək */',
'group-bot.js'           => '/* Burada istənilən JavaScript yalnız botlar üçün yüklənəcək */',
'group-sysop.js'         => '/* Burada istənilən JavaScript yalnız idarəçilər üçün yüklənəcək */',
'group-bureaucrat.js'    => '/* Burada istənilən JavaScript yalnız bürokratlar üçün yüklənəcək */',

# Metadata
'notacceptable' => 'Viki-server məlumatları brauzerinizin oxuya biləcəyi formatda təqdim edə bilmir.',

# Attribution
'anonymous'     => '{{SITENAME}} saytının anonim {{PLURAL:$1|istifadəçisi|istifadəçiləri}}',
'siteuser'      => '{{SITENAME}} istifadəçisi $1',
'anonuser'      => '{{SITENAME}} anonim istifadəçisi $1',
'othercontribs' => '$1-in işinə əsaslanıb.',
'others'        => 'digərləri',
'siteusers'     => '{{SITENAME}} {{PLURAL:$2|user|istifadəçi}} $1',
'anonusers'     => '{{SITENAME}} anonim {{PLURAL:$2|user|istifadəçi}} $1',
'creditspage'   => 'Səhifə kreditleri',

# Spam protection
'spamprotectiontitle' => 'Spam qoruma süzgəci',
'spambot_username'    => 'MediaViki spam təmizləməsi',

# Info page
'pageinfo-title'            => '"$1" üçün məlumat',
'pageinfo-header-edits'     => 'Redaktələr',
'pageinfo-header-watchlist' => 'İzləmə siyahısı',
'pageinfo-header-views'     => 'Görünüş',
'pageinfo-subjectpage'      => 'Səhifə',
'pageinfo-talkpage'         => 'Müzakirə səhifəsi',
'pageinfo-watchers'         => 'Baxış sayı',
'pageinfo-edits'            => 'Redaktələrin sayı',
'pageinfo-views'            => 'Göstərmə səhifəsi',
'pageinfo-viewsperedit'     => 'Redaktə başına göstərmə',

# Skin names
'skinname-standard'    => 'Classic',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Cologne Blue',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chick',
'skinname-simple'      => 'Simple',
'skinname-modern'      => 'Modern',
'skinname-vector'      => 'Vector',

# Patrolling
'markaspatrolleddiff'                 => 'Yoxlanıldı',
'markaspatrolledtext'                 => 'Səhifəni patrullanmış kimi işarələ',
'markedaspatrolled'                   => 'Yoxlanıldı',
'markedaspatrolledtext'               => '[[:$1]] üçün seçilmiş versiya gözdən keçirilərək işarələndi.',
'rcpatroldisabled'                    => 'Son dəyişikliklərin patrullanması qadağandır',
'rcpatroldisabledtext'                => 'Son dəyişikliklərin Yoxlanılması hal-hazırda mümkün deyil.',
'markedaspatrollederror'              => 'Yoxlanmadı',
'markedaspatrollederror-noautopatrol' => 'Öz dəyişikliklərinizi yoxlayıb işarələyə bilməzsiniz.',

# Patrol log
'patrol-log-page'      => 'Patrul gündəliyi',
'patrol-log-header'    => 'Bu yoxlanmış dəyişikliklərin gündəliyidir.',
'patrol-log-line'      => '$3 yoxlanılaraq $2 $1 versiyası işarələndi',
'patrol-log-auto'      => '(avtomatik)',
'patrol-log-diff'      => 'təftiş $1',
'log-show-hide-patrol' => '$1 patrul gündəliyi',

# Image deletion
'deletedrevision'                 => 'Köhnə versiyaları silindi $1.',
'filedeleteerror-short'           => 'Fayl silinərkən xəta: $1',
'filedeleteerror-long'            => 'Fayl silinərkən üzə çıxan xətalar:

$1',
'filedelete-missing'              => '"$1" faylı mövcud olmadığından silinə bilinmir.',
'filedelete-current-unregistered' => 'Göstərilən "$1" faylı verilənlər bazasında yoxdur.',
'filedelete-archive-read-only'    => '"$1" kataloqunun arxivi veb-server yazıları üçün qapalıdır.',

# Browsing diffs
'previousdiff' => '← Əvvəlki redaktə',
'nextdiff'     => 'Sonrakı redaktə →',

# Media information
'imagemaxsize'         => "Şəkilin maksimal tutumu:<br />''(faylın təsviri səhifələri üçün)''",
'thumbsize'            => 'Kiçik ölçü:',
'widthheight'          => '$1×$2',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|səhifə|səhifələr}}',
'file-info'            => 'faylın ölçüsü: $1, MIME tipi: $2',
'file-info-size'       => '$1 × $2 piksel, fayl həcmi: $3, MIME növü: $4',
'file-nohires'         => '<small>Daha dəqiq versiyası yoxdur.</small>',
'svg-long-desc'        => 'SVG fayl, nominal olaraq $1 × $2 piksel, faylın ölçüsü: $3',
'show-big-image'       => 'Daha yüksək keyfiyyətli şəkil',
'show-big-image-size'  => '$1 × $2 piksel',
'file-info-gif-looped' => 'ilmələnib',
'file-info-gif-frames' => '$1 {{PLURAL:$1|frame|çərçivə}}',
'file-info-png-looped' => 'ilmələnib',
'file-info-png-frames' => '$1 {{PLURAL:$1|frame|çərçivə}}',

# Special:NewFiles
'newimages'        => 'Yeni faylların siyahısı',
'newimages-legend' => 'Filtrlər',
'newimages-label'  => 'Faylın (və ya onun bir hissəsinin) adı:',
'showhidebots'     => '($1 bot redaktə)',
'noimages'         => 'Heç nəyi görməmək.',
'ilsubmit'         => 'Axtar',
'bydate'           => 'tarixə görə',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'video-dims'     => '$1, $2×$3',
'seconds-abbrev' => 'san',
'minutes-abbrev' => 'd',
'hours-abbrev'   => 'saat',

# Bad image list
'bad_image_list' => 'Format bu şəkildə olmalıdır:

Yalnız siyahı bəndləri (* işarəsi ilə başlayan sətirlər) nəzərə alınır.
Sətirdəki ilk keçid, əlavə olunması qadağan olunmuş şəkilə keçid olmalıdır.
Həmin sətirdəki növbəti keçidlər istisna hesab olunur. Məsələn, fayl məqalədə sətrin içində görünə bilər.',

/*
Short names for language variants used for language conversion links.
To disable showing a particular link, set it to 'disable', e.g.
'variantname-zh-sg' => 'disable',
Variants for Chinese language
*/
'variantname-zh-hans' => 'hans',
'variantname-zh-hant' => 'hant',
'variantname-zh-cn'   => 'cn',
'variantname-zh-tw'   => 'tw',
'variantname-zh-hk'   => 'hk',
'variantname-zh-mo'   => 'mo',
'variantname-zh-sg'   => 'sg',
'variantname-zh'      => 'zh',

# Variants for Gan language
'variantname-gan-hans' => 'hans',
'variantname-gan-hant' => 'hant',
'variantname-gan'      => 'gan',

# Variants for Kazakh language
'variantname-kk-tr' => 'kk-tr',
'variantname-kk-cn' => 'kk-cn',

# Variants for Kurdish language
'variantname-ku-arab' => 'ku-Arab',

# Metadata
'metadata'          => 'Metaməlumatlar',
'metadata-help'     => 'Bu faylda fotoaparat və ya skanerlə əlavə olunmuş məlumatlar var. Əgər fayl sonradan redaktə olunubsa, bəzi parametrlər bu şəkildə göstərilənlərdən fərqli ola bilər.',
'metadata-expand'   => 'Ətraflı məlumatları göstər',
'metadata-collapse' => 'Ətraflı məlumatları gizlə',
'metadata-fields'   => 'Bu səhifədə sıralanan EXIF metadata sahələri şəkil görünüş səhifələrində metadata cədvəli çökdüyündə istifadə edilir. Digərləri varsayılan olaraq gizlənəcəkdir.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# EXIF tags
'exif-imagewidth'                  => 'Genişlik',
'exif-imagelength'                 => 'Hündürlük',
'exif-compression'                 => 'Sıxılmamış',
'exif-orientation'                 => 'Orientasiya',
'exif-samplesperpixel'             => 'Rəng komponentlərinin sayı',
'exif-ycbcrpositioning'            => 'Y və C komponetlərinə görə yerləşmə sırası',
'exif-xresolution'                 => 'Üfiqi xətt',
'exif-yresolution'                 => 'Şaquli xətt',
'exif-rowsperstrip'                => 'Hər blokdakı sətirlərin sayı',
'exif-jpeginterchangeformatlength' => 'JPEG məlumat bazasının baytları',
'exif-datetime'                    => 'Faylın dəyişməsi tarixi və vaxtı',
'exif-imagedescription'            => 'Şəkil başlığı',
'exif-make'                        => 'Kamera istehsalçısı',
'exif-model'                       => 'Kamera modeli',
'exif-software'                    => 'Proqram təminatı',
'exif-artist'                      => 'Müəllif',
'exif-copyright'                   => 'Sahibinin müəlliflik hüququ',
'exif-exifversion'                 => 'Exif versiyası',
'exif-colorspace'                  => 'Rəng sahəsi',
'exif-pixelydimension'             => 'Şəkilin eni',
'exif-pixelxdimension'             => 'Şəkilin hündürlüyü',
'exif-usercomment'                 => 'İstifadəçi şərhi',
'exif-exposuretime-format'         => '$1 saniyə ($2)',
'exif-fnumber'                     => 'F nömrəsi',
'exif-exposureprogram'             => 'Ekspozisiya proqramı',
'exif-spectralsensitivity'         => 'Spektral həssaslıq',
'exif-isospeedratings'             => 'ISO sürət reytinqi',
'exif-shutterspeedvalue'           => 'APEX shutter speed',
'exif-aperturevalue'               => 'Obyektiv gözü',
'exif-brightnessvalue'             => 'Parlaqlıq',
'exif-exposurebiasvalue'           => 'APEX exposure bias',
'exif-subjectdistance'             => 'Obyektə qədər məsafə',
'exif-lightsource'                 => 'İşıq mənbəsi',
'exif-flash'                       => 'Flaş',
'exif-focallength'                 => 'Fokus məsafəsi',
'exif-subjectarea'                 => 'Subyekt sahəsi',
'exif-flashenergy'                 => 'Flaş enerjisi',
'exif-subjectlocation'             => 'Subyekt yeri',
'exif-filesource'                  => 'Fayl mənbəsi',
'exif-scenetype'                   => 'Səhnə tipi',
'exif-whitebalance'                => 'Ağ balansı',
'exif-gaincontrol'                 => 'Səhnə idarəsi',
'exif-contrast'                    => 'Kontrast',
'exif-saturation'                  => 'Doymuşluq',
'exif-sharpness'                   => 'Kəskinlik',
'exif-gpsversionid'                => 'GPS etiket versiyası',
'exif-gpslatituderef'              => 'Şimal və ya cənub en dairəsi',
'exif-gpslatitude'                 => 'En dairəsi',
'exif-gpslongituderef'             => 'Şəq və ya qərb uzunluq dairəsi',
'exif-gpslongitude'                => 'Uzunluq dairəsi',
'exif-gpsaltitude'                 => 'Yüksəklik',
'exif-gpstimestamp'                => 'GPS vaxtı (atom saatı)',
'exif-gpsstatus'                   => 'Statusu qəbul edən',
'exif-gpsmeasuremode'              => 'Ölçmə üsulu',
'exif-gpsdop'                      => 'Ölçünün dəqiqliyi',
'exif-gpsspeedref'                 => 'Sürət uniti',
'exif-gpsspeed'                    => 'GPS qəbuledicinin sürəti',
'exif-gpstrack'                    => 'İdarəetmə mexanizmi',
'exif-gpsimgdirection'             => 'Şəklin istiqaməti',
'exif-gpsdestlatitude'             => 'En dairəsinin təyin olunması',
'exif-gpsdestlongitude'            => 'Uzunluq dairəsinin təyin olunması',
'exif-gpsdestbearing'              => 'Obyektin pelenqi',
'exif-gpsdestdistance'             => 'Məsafə',
'exif-gpsprocessingmethod'         => 'GPS prosesinin adı',
'exif-gpsareainformation'          => 'GPS sahənin adı',
'exif-gpsdatestamp'                => 'Zaman',
'exif-gpsdifferential'             => 'GPS diferensial korreksiya',
'exif-jpegfilecomment'             => ' JPEG şəkil şərhi',
'exif-keywords'                    => 'Açar sözlər',
'exif-countrycreated'              => 'Şəklin çəkildiyi ölkə',
'exif-provinceorstatecreated'      => 'Şəklin çəkildiyi əyalət, yaxud ştat',
'exif-worldregiondest'             => 'Təsvir edilmiş dünya bölgəsi',
'exif-countrydest'                 => 'Təsvir olunmuş ölkə',
'exif-countrycodedest'             => 'Təsvir edilmiş ölkənin kodu',
'exif-provinceorstatedest'         => 'Təsvir edilmiş bölgə yaxud ştat',
'exif-citydest'                    => 'Təsvir edilmiş şəhər',
'exif-objectname'                  => 'Qısa başlıq',
'exif-specialinstructions'         => 'Xüsusi instruksiyalar',
'exif-headline'                    => 'Başlıq',
'exif-credit'                      => 'Credit/Provider',
'exif-source'                      => 'Mənbə',
'exif-urgency'                     => 'Zərurilik',
'exif-fixtureidentifier'           => 'Sütun adı',
'exif-locationdest'                => 'Təsvir edilmiş ərazi',
'exif-contact'                     => 'Əlaqə məlumatı',
'exif-writer'                      => 'Yazıçı',
'exif-languagecode'                => 'Dil',
'exif-iimversion'                  => 'IIM versiya',
'exif-iimcategory'                 => 'Kateqoriya',
'exif-datetimeexpires'             => 'Sonra işlətmə',
'exif-datetimereleased'            => 'Çıxış tarixi',
'exif-identifier'                  => 'İdentifikator',
'exif-lens'                        => 'İstifadə olunmuş obyektiv',
'exif-serialnumber'                => 'Kameranın serial nömrəsi',
'exif-cameraownername'             => 'Kameranın sahibi',
'exif-label'                       => 'Nişan',
'exif-rating'                      => 'Qiymət (5 üzərində)',
'exif-copyrighted'                 => 'Statusun müəlliflik hüququ',
'exif-copyrightowner'              => 'Sahibinin müəlliflik hüququ',
'exif-usageterms'                  => 'İstifadə qaydası',
'exif-webstatement'                => 'İnternetdə müəlliflik hüquqları qaydaları',
'exif-licenseurl'                  => 'Müəllif hüququ lisenziyası üçün URL',
'exif-morepermissionsurl'          => 'Alternativ lisenziya məlumatı',
'exif-pngfilecomment'              => 'PNG şəkil şərhi',
'exif-disclaimer'                  => 'Məsuliyyətdən imtina',
'exif-contentwarning'              => 'Mətn xəbərdarlığı',
'exif-giffilecomment'              => 'GIF şəkil şərhi',
'exif-intellectualgenre'           => 'Obyektin tipi',
'exif-subjectnewscode'             => 'Mövzunun kodu',
'exif-scenecode'                   => 'IPTC səhnə kodu',

# EXIF attributes
'exif-compression-1' => 'Sıxılmış',

'exif-copyrighted-true'  => 'Müəlliflik hüququ ilə qorunur',
'exif-copyrighted-false' => 'İctimai istifadə',

'exif-unknowndate' => 'Naməlum tarix',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Üfüqi çevrilib',
'exif-orientation-3' => '180° döndərilib',
'exif-orientation-4' => 'Şaquli çevrilib',
'exif-orientation-5' => 'Saat əqrəbinin əksi istiqamətində 90° döndərilib və şaquli çevrilib',
'exif-orientation-6' => 'Saat əqrəbinin əksi istiqamətində 90° döndərilib',
'exif-orientation-7' => 'Saat əqrəbi istiqamətində 90° döndərilib və şaquli çevrilib',
'exif-orientation-8' => 'Saat əqrəbi istiqamətində 90° döndərilib',

'exif-planarconfiguration-1' => 'chunky format',
'exif-planarconfiguration-2' => 'planar format',

'exif-colorspace-65535' => 'Fotoşəkildə rəng seçimi edilməmişdir.',

'exif-componentsconfiguration-0' => 'mövcud deyil',

'exif-exposureprogram-0' => 'Tanınmadı',
'exif-exposureprogram-1' => 'Əl ilə',
'exif-exposureprogram-2' => 'Normal proqram',
'exif-exposureprogram-3' => 'Açıqlıq üstünlüyü',

'exif-subjectdistance-value' => '$1 metr',

'exif-meteringmode-0'   => 'Naməlum',
'exif-meteringmode-1'   => 'Orta',
'exif-meteringmode-3'   => 'Nöqtəli',
'exif-meteringmode-4'   => 'Multi-Spot',
'exif-meteringmode-5'   => 'Dizaynlı',
'exif-meteringmode-6'   => 'Qismi',
'exif-meteringmode-255' => 'Digər',

'exif-lightsource-0'   => 'Naməlum',
'exif-lightsource-1'   => 'Sübh',
'exif-lightsource-2'   => 'Flüorosensiya',
'exif-lightsource-4'   => 'Flaş',
'exif-lightsource-9'   => 'Gözəl hava',
'exif-lightsource-10'  => 'Buludlu hava',
'exif-lightsource-11'  => 'Kölgə',
'exif-lightsource-12'  => 'D tipli gündüz işığı lampası (5700 − 7100K)',
'exif-lightsource-13'  => 'N tipli gündüz işığı lampası (4600 – 5400K)',
'exif-lightsource-14'  => 'W tipli gündüz işığı lampası (3900 – 4500K)',
'exif-lightsource-15'  => 'WW tipli gündüz işığı lampası (3200 – 3700K)',
'exif-lightsource-17'  => 'A tipi standart işıq',
'exif-lightsource-18'  => 'B tipi standart işıq',
'exif-lightsource-19'  => 'C tipi standart işıq',
'exif-lightsource-24'  => 'ISO studiya lampası',
'exif-lightsource-255' => 'Digər işıq mənbəyi',

# Flash modes
'exif-flash-fired-0'    => 'Flaş yandırılmadı',
'exif-flash-fired-1'    => 'Flaş yandırıldı',
'exif-flash-mode-3'     => 'avtomatik rejim',
'exif-flash-function-1' => 'Fləş bağlıdır',

'exif-focalplaneresolutionunit-2' => 'düymlər',

'exif-sensingmethod-1' => 'Tanınmadı',
'exif-sensingmethod-2' => 'Birkristallı matrisli rəngli sensor',
'exif-sensingmethod-3' => 'İkikristallı matrisli rəngli sensor',
'exif-sensingmethod-4' => 'Üçkristallı matrisli rəngli sensor',
'exif-sensingmethod-5' => 'Rəngin ardıcıl ölçülməsilə birlikdə matrisli sensor',
'exif-sensingmethod-7' => 'Üçrəngli xətti sensor',
'exif-sensingmethod-8' => 'Rəngin ardıcıl ölçülməsilə birlikdə xətti sensor',

'exif-filesource-3' => 'Rəqəmsal fotoapparat',

'exif-scenetype-1' => 'Foto-təsvir birbaşa çəkilmişdir',

'exif-customrendered-0' => 'Normal proses',
'exif-customrendered-1' => 'Xüsusi proses',

'exif-exposuremode-0' => 'Avtomatik ekspozisiya',
'exif-exposuremode-1' => 'Əl ekspozisiyası',
'exif-exposuremode-2' => 'Avtomatik breketinq',

'exif-whitebalance-0' => 'Bəyaz balansın avtomatik tənzimlənməsi',
'exif-whitebalance-1' => 'Bəyaz balansın əllə tənzimlənməsi',

'exif-scenecapturetype-0' => 'Standart',
'exif-scenecapturetype-1' => 'Mənzərə',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Gecə görünüşü',

'exif-gaincontrol-0' => 'Heç biri',
'exif-gaincontrol-1' => 'Bir az artırma',
'exif-gaincontrol-2' => 'Kəskin artırma',
'exif-gaincontrol-3' => ' Bir az azaltma',
'exif-gaincontrol-4' => 'Kəskin azaltma',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Yumşaq',
'exif-contrast-2' => 'Ağır',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Aşağı doyma dərəcəsi',
'exif-saturation-2' => 'Yuxarı doyma dərəcəsi',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Yumşaq',
'exif-sharpness-2' => 'Ağır',

'exif-subjectdistancerange-0' => 'Naməlum',
'exif-subjectdistancerange-1' => 'Makro',
'exif-subjectdistancerange-2' => 'Bağlı görünüş',
'exif-subjectdistancerange-3' => 'Uzaq məsafədən çəkiliş',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Şimal en dairəsi',
'exif-gpslatitude-s' => 'Cənub en dairəsi',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Qərb uzunluq dairəsi',
'exif-gpslongitude-w' => 'Şərq uzunluq dairəsi',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|meter|metr}} dəniz səviyyəsindən yüksəkdə',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|meter|metr}} dəniz səviyyəsindən aşağıda',

'exif-gpsstatus-a' => 'Ölçmə yekunlaşmayıb',
'exif-gpsstatus-v' => 'Verilənləri ötürmək üçün hazırdır',

'exif-gpsmeasuremode-2' => '2-ölçülü koordinat',
'exif-gpsmeasuremode-3' => '3-ölçülü koordinat',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'km/saat',
'exif-gpsspeed-m' => 'Saatda mil',
'exif-gpsspeed-n' => 'Dəniz mili',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometr',
'exif-gpsdestdistance-m' => 'Millər',
'exif-gpsdestdistance-n' => 'Dəniz mili',

'exif-gpsdop-excellent' => 'Əla ($1)',
'exif-gpsdop-good'      => 'Yaxşı ($1)',
'exif-gpsdop-moderate'  => 'Zəif ($1)',
'exif-gpsdop-fair'      => 'Ədalətli ($1)',
'exif-gpsdop-poor'      => 'Pis ($1)',

'exif-objectcycle-a' => 'Ancaq səhər',
'exif-objectcycle-p' => 'Ancaq axşam',
'exif-objectcycle-b' => 'Gündüzlər və axşamlar',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Doğru istiqamət',
'exif-gpsdirection-m' => 'Maqnit istiqaməti',

'exif-ycbcrpositioning-1' => 'Mərkəzləşdirilmiş',
'exif-ycbcrpositioning-2' => 'Co-sited',

'exif-dc-contributor' => 'Həmmüəlliflər',
'exif-dc-coverage'    => 'Mediyanın məkan və zaman çərçivəsi',
'exif-dc-date'        => 'Tarix(lər)',
'exif-dc-publisher'   => 'Naşir',
'exif-dc-relation'    => 'Əlaqəli media',
'exif-dc-rights'      => 'Hüquqlar',
'exif-dc-source'      => 'İlkin media',
'exif-dc-type'        => 'Medianın tipi',

'exif-rating-rejected' => 'Rədd edildi',

'exif-isospeedratings-overflow' => '65535-dən böyükdür',

'exif-iimcategory-ace' => 'İncəsənət, mədəniyyət və əyləncə',
'exif-iimcategory-clj' => 'Kriminal və qanun',
'exif-iimcategory-dis' => 'Faciə və qəzalar',
'exif-iimcategory-fin' => 'İqtisaqdiyyat və biznes',
'exif-iimcategory-edu' => 'Təhsil',
'exif-iimcategory-evn' => 'Ətraf mühit',
'exif-iimcategory-hth' => 'Səhiyyə',
'exif-iimcategory-hum' => 'İnsan maraqları',
'exif-iimcategory-lab' => 'Əmək',
'exif-iimcategory-lif' => 'Həyat tərzi və əyləncə',
'exif-iimcategory-pol' => 'Siyasət',
'exif-iimcategory-rel' => 'Din və iman',
'exif-iimcategory-sci' => 'Elm və texnologiya',
'exif-iimcategory-soi' => 'Sosial məsələlər',
'exif-iimcategory-spo' => 'İdman',
'exif-iimcategory-war' => 'Müharibə, münaqişə və iğtişaşlar',
'exif-iimcategory-wea' => 'Hava',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low'    => 'Aşağı ($1)',
'exif-urgency-high'   => 'Yüksək ($1)',
'exif-urgency-other'  => 'İstifadəçi tərəfindən müəyyən olunmuş birincilik ($1)',

# External editor support
'edit-externally'      => 'Bu faylı kənar proqram vasitəsilə redaktə et.',
'edit-externally-help' => '(Daha ətraflı məlumat üçün [//www.mediawiki.org/wiki/Manual:External_editors tətbiqetmə qaydalarına] baxa bilərsiniz)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'hamısını',
'namespacesall' => 'bütün',
'monthsall'     => 'hamısı',
'limitall'      => 'bütün',

# E-mail address confirmation
'confirmemail'             => 'E-məktubunu təsdiq et',
'confirmemail_send'        => 'Təsdiq kodu göndər',
'confirmemail_sent'        => 'Təsdiq e-məktubu göndərildi.',
'confirmemail_invalid'     => 'Səhv təsdiqləmə kodu. Kodun vaxtı keçmiş ola bilər.',
'confirmemail_needlogin'   => 'E-poçt ünvanınızın təsdiqlənməsi üçün $1 lazımdır.',
'confirmemail_success'     => 'E-poçt ünvanınız indi təsdiq edildi. Siz indi [[Special:UserLogin|hesab açaraq]] vikidən həzz ala bilərsiz.',
'confirmemail_loggedin'    => 'E-məktubunuz indi təsdiq edildi.',
'confirmemail_subject'     => '{{SITENAME}} e-məktub təsdiq etmə',
'confirmemail_invalidated' => 'E-mail təsdiqlənməsi dayandırıldı',
'invalidateemail'          => 'E-mail təsdiqlənməsindən imtina',

# Scary transclusion
'scarytranscludedisabled' => '[«Interwiki transcluding»dən çıxılmışdır]',
'scarytranscludetoolong'  => '[URL uzundur]',

# Trackbacks
'trackbackbox'      => 'Bu səhifə üçün geri izləmələr:<br />
$1',
'trackbackremove'   => '([$1 Sil])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Geri izləmə uğurla silindi.',

# Delete conflict
'deletedwhileediting' => "'''Diqqət!''' Bu səhifə siz redaktə etməyə başladıqdan sonra silinmişdir!",
'recreate'            => 'Yeniləmək',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Bu səhifə keşdən (cache) silinsin?',

# action=watch/unwatch
'confirm-watch-button'   => 'OK',
'confirm-unwatch-button' => 'OK',

# Multipage image navigation
'imgmultipageprev' => '&larr; əvvəlki səhifə',
'imgmultipagenext' => 'sonrakı səhifə &rarr;',
'imgmultigo'       => 'Seç!',
'imgmultigoto'     => '$1 səhifəyə get',

# Table pager
'ascending_abbrev'         => 'artma sırasına görə',
'descending_abbrev'        => 'azalma sırasına görə',
'table_pager_next'         => 'Sonrakı səhifə',
'table_pager_prev'         => 'Əvvəlki səhifə',
'table_pager_first'        => 'İlk səhifə',
'table_pager_last'         => 'Son səhifə',
'table_pager_limit'        => 'Səhifədə $1 mövqe sərgilə',
'table_pager_limit_label'  => 'Səhifədəki mövqelər:',
'table_pager_limit_submit' => 'Seç',
'table_pager_empty'        => 'Nəticə yoxdur',

# Auto-summaries
'autosumm-blank'   => 'Səhifənin məzmunu silindi',
'autosumm-replace' => "Səhifənin məzmunu '$1' yazısı ilə dəyişdirildi",
'autoredircomment' => '[[$1]] səhifəsinə istiqamətləndirilir',
'autosumm-new'     => "Səhifəni '$1' ilə yarat",

# Live preview
'livepreview-loading' => 'Yüklənir…',
'livepreview-ready'   => 'Gözlə... Hazırdır!',

# Watchlist editor
'watchlistedit-normal-title'  => 'İzlədiyim səhifələri redaktə et',
'watchlistedit-normal-legend' => 'İzləmə siyahısından başlıqların silinməsi',
'watchlistedit-normal-submit' => 'Başlığın silinməsi',
'watchlistedit-normal-done'   => '{{PLURAL:$1|$1 səhifə}} izləmə səhifələrinizdən silindi:',
'watchlistedit-raw-title'     => 'Mətn kimi redaktə et',
'watchlistedit-raw-legend'    => 'Mətn kimi redaktə et',
'watchlistedit-raw-titles'    => 'Başlıqlar:',
'watchlistedit-raw-submit'    => 'İzlədiyim səhifələrin siyahısının yenilənməsi',
'watchlistedit-raw-done'      => 'İzləmə səhifələriniz qeyd olundu.',
'watchlistedit-raw-added'     => '{{PLURAL:$1|1 title was|$1 başlıq}} əlavə edildi:',
'watchlistedit-raw-removed'   => '{{PLURAL:$1|1 title was|$1 başlıq}} çıxarıldı:',

# Watchlist editing tools
'watchlisttools-view' => 'Siyahıdakı səhifələrdə edilən dəyişikliklər',
'watchlisttools-edit' => 'İzlədiyim səhifələri göstər və redaktə et',
'watchlisttools-raw'  => 'Mətn kimi redaktə et',

# Core parser functions
'unknown_extension_tag' => '"$1" Naməlum ayırma teqi',
'duplicate-defaultsort' => '\'\'\'Diqqət:\'\'\' Ehtimal edilən "$2" klassifikasiya açarı əvvəlki "$1" klassifikasiya açarını keçərsiz edir.',

# Special:Version
'version'                     => 'Versiya',
'version-extensions'          => 'NIzamlanmış genişlənmələr',
'version-specialpages'        => 'Xüsusi səhifələr',
'version-parserhooks'         => 'Parser hooks',
'version-variables'           => 'Dəyişkənlər',
'version-antispam'            => 'Spam önləmə',
'version-skins'               => 'Üzlük',
'version-other'               => 'Digər',
'version-hooks'               => 'Çəngəllər',
'version-extension-functions' => 'Əlavə fubksiyalar',
'version-hook-name'           => 'Çəngəlin adı',
'version-hook-subscribedby'   => 'Abunə olan',
'version-version'             => '(Versiya $1)',
'version-license'             => 'Lisenziya',
'version-poweredby-credits'   => "Bu wiki '''[//www.mediawiki.org/ MediaWiki]''' proqramı istifadə edilərək yaradılmışdır, müəlliflik © 2001-$1 $2.",
'version-poweredby-others'    => 'digərləri',
'version-software-product'    => 'Məhsul',
'version-software-version'    => 'Versiya',

# Special:FilePath
'filepath'        => 'Fayl yolu',
'filepath-page'   => 'Fayl:',
'filepath-submit' => 'Get',

# Special:FileDuplicateSearch
'fileduplicatesearch'           => 'Dublikat fayl axtarışı',
'fileduplicatesearch-legend'    => 'Dublikatı axtar',
'fileduplicatesearch-filename'  => 'Fayl adı:',
'fileduplicatesearch-submit'    => 'Axtar',
'fileduplicatesearch-noresults' => '"$1" adında fayl tapılmadı.',

# Special:SpecialPages
'specialpages'                   => 'Xüsusi səhifələr',
'specialpages-group-maintenance' => 'Cari məruzələr',
'specialpages-group-other'       => 'Digər xüsusi səhifələr',
'specialpages-group-login'       => 'Daxil ol/ hesab yarat',
'specialpages-group-changes'     => 'Son dəyişikliklər və qeydlər',
'specialpages-group-media'       => 'Media məruzələri və yükləmələr',
'specialpages-group-users'       => 'İstifadəçilər və hüquqlar',
'specialpages-group-highuse'     => 'Ən çox istifadə edilən səhifələr',
'specialpages-group-pages'       => 'Səhifələrin siyahıları',
'specialpages-group-pagetools'   => 'Səhifə alətləri',
'specialpages-group-wiki'        => 'Viki məlumatları və alətləri',
'specialpages-group-redirects'   => 'Xüsusi istiqamətləndirmə səhifələri',
'specialpages-group-spam'        => 'Spam alətləri',

# Special:BlankPage
'blankpage'              => 'Boş səhifə',
'intentionallyblankpage' => 'Bu səhifə xüsusilə boşdur.',

# Special:Tags
'tags'                    => 'Mümkün dəyişiklik etiketləri',
'tag-filter'              => '[[Special:Tags|Etiket]] süzgəci:',
'tag-filter-submit'       => 'Filtrlər',
'tags-title'              => 'Etiketlər',
'tags-tag'                => 'Etiket adı',
'tags-description-header' => 'Anlamının tam açıqlaması',
'tags-hitcount-header'    => 'Etiketli dəyişikliklər',
'tags-edit'               => 'redaktə',
'tags-hitcount'           => '$1 {{PLURAL:$1|dəyişiklik|dəyişiklik}}',

# Special:ComparePages
'comparepages'     => 'Səhifələri qarşılaşdır',
'compare-selector' => 'Səhifə redaktələrini qarşılaşdır',
'compare-page1'    => 'Səhifə 1',
'compare-page2'    => 'Səhifə 2',
'compare-rev1'     => 'Dəyişiklik 1',
'compare-rev2'     => 'Dəyişiklik 2',
'compare-submit'   => 'Qarşılaşdır',

# Database error messages
'dberr-header'   => 'Bu vikidə problem var',
'dberr-problems' => 'Üzr istəyirik!
Bu saytda texniki problemlər var.',
'dberr-info'     => '($1: Məlumat bazası ilə əlaqə yoxdur)',

# HTML forms
'htmlform-invalid-input'       => 'Girişinizin bir qismilə əlaqədəar problemlər var',
'htmlform-select-badoption'    => 'İşarə etdiyiniz xüsus keçərli deyil.',
'htmlform-int-invalid'         => 'Göstərdiyiniz ifadə tam ədəd deyil.',
'htmlform-float-invalid'       => 'Göstərdiyiniz ifadə ədəd deyil.',
'htmlform-int-toolow'          => 'Göstərdiyiniz $1 ifadəsi minimaldan aşağıdır.',
'htmlform-int-toohigh'         => 'Göstərdiyiniz $1 ifadəsi maksimumdan yuxarıdır.',
'htmlform-required'            => 'Bu məlumat əhəmiyyətlidir',
'htmlform-submit'              => 'Təsdiq et',
'htmlform-reset'               => 'Dəyişiklikləri geri qaytar',
'htmlform-selectorother-other' => 'Digər',

# SQLite database support
'sqlite-has-fts' => '$1 tam mətn axtarma ilə',
'sqlite-no-fts'  => '$1 tam mətn axtarma olmadan',

);
