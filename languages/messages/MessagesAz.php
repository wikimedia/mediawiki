<?php
/** Azerbaijani (Azərbaycanca)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Don Alessandro
 * @author Gulmammad
 * @author PrinceValiant
 * @author Sortilegus
 * @author Sysops of az.wikipedia.org (imported 2008-08-31)
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
	NS_MEDIAWIKI        => 'MediyaViki',
	NS_MEDIAWIKI_TALK   => 'MediyaViki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
);

$namespaceAliases = array(
	'Mediya'      => NS_MEDIA,
);

$specialPageAliases = array(
	'CreateAccount'             => array( 'HesabAç' ),
	'Preferences'               => array( 'Nizamlamalar' ),
	'Recentchanges'             => array( 'SonDəyişikliklər' ),
	'Statistics'                => array( 'Statistika' ),
	'Shortpages'                => array( 'QısaSəhifələr' ),
	'Longpages'                 => array( 'UzunSəhifələr' ),
	'Newpages'                  => array( 'YeniSəhifələr' ),
	'Allpages'                  => array( 'BütünSəhifələr' ),
	'Specialpages'              => array( 'XüsusiSəhifələr' ),
	'Contributions'             => array( 'Fəaliyyətlər' ),
	'Version'                   => array( 'Versiya' ),
	'Undelete'                  => array( 'Pozma' ),
	'Mypage'                    => array( 'MənimSəhifəm' ),
	'Mytalk'                    => array( 'MənimDanışıqlarım' ),
	'Mycontributions'           => array( 'MənimFəaliyyətlərim' ),
	'Search'                    => array( 'Axtar' ),
	'Activeusers'               => array( 'Aktivİstifadəçilər' ),
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
'tog-rememberpassword'        => 'Parolu xatırla',
'tog-watchcreations'          => 'Yaratdığım səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchdefault'            => 'Redaktə etdiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchmoves'              => 'Adlarını dəyişdiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-watchdeletion'           => 'Sildiyim səhifələri izlədiyim səhifələrə əlavə et',
'tog-previewontop'            => 'Sınaq göstərişi yazma sahəsindən əvvəl göstər',
'tog-previewonfirst'          => 'İlkin redaktədə sınaq göstərişi',
'tog-nocache'                 => 'Səhifəni keşdə (cache) saxlama',
'tog-enotifwatchlistpages'    => 'İzləmə siyahısında olan məqalə redaktə olunsa, mənə e-məktub göndər',
'tog-enotifusertalkpages'     => 'Müzakirə səhifəm redaktə olunsa, mənə e-məktub göndər',
'tog-enotifminoredits'        => 'Hətta səhifələrdə kiçik dəyişikliklər olsa belə, mənə e-məktub göndər',
'tog-enotifrevealaddr'        => 'Elektron adresimi xəbərdarliq e-məktublarda göstər',
'tog-shownumberswatching'     => 'İzləyən istifadəçilərin sayını göstər',
'tog-oldsig'                  => 'Hazırkı imzanın sınaq göstərişi:',
'tog-fancysig'                => 'Xam imza (daxili bağlantı yaratmaz)',
'tog-externaleditor'          => 'Default olaraq xarici redaktə proqramlarından istifadə et (Ekspertlər üçün, kompyuterinizin parametrlərində xüsusi dəyişikliklər tələb olunur)',
'tog-externaldiff'            => 'Susmaya görə xarici müqayisə proqramlarından istifadə et',
'tog-showjumplinks'           => '"Gətir" ("jump to") linklərini aktivləşdir',
'tog-forceeditsummary'        => 'Qısa məzmunu boş saxladıqda mənə bildir',
'tog-watchlisthideown'        => 'Mənim redaktələrimi izləmə siyahısında gizlət',
'tog-watchlisthidebots'       => 'Bot redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthideminor'      => 'İzləmə səhifəmdə kiçik redaktələri gizlət',
'tog-watchlisthideliu'        => 'Daxil olmuş istifadəçilərin redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthideanons'      => 'Qeydiyyatdan keçməmiş istifadəçilərin redaktələrini izləmə siyahısında gizlət',
'tog-watchlisthidepatrolled'  => 'Yoxlanılmış redaktələri izləmə siyahısında gizlət',
'tog-ccmeonemails'            => 'Göndərdiyim e-məktubun nüsxələrini mənə göndər',
'tog-diffonly'                => 'Versiyaların müqayisəsi zamanı səhifənin məzmununu göstərmə',
'tog-showhiddencats'          => 'Gizli kateqoriyaları göstər',
'tog-norollbackdiff'          => 'Geri qaytardıqdan sonra, edilmiş dəyişikikləri dəyişikliklər siyahısından sil',

'underline-always'  => 'Həmişə',
'underline-never'   => 'Heç zaman',
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
'january'       => 'Yanvar',
'february'      => 'Fevral',
'march'         => 'Mart',
'april'         => 'Aprel',
'may_long'      => 'May',
'june'          => 'İyun',
'july'          => 'İyul',
'august'        => 'Avqust',
'september'     => 'Sentyabr',
'october'       => 'Oktyabr',
'november'      => 'Noyabr',
'december'      => 'Dekabr',
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
'pagecategories'                 => '$1 {{PLURAL:$1|Kateqoriya|Kateqoriyalar}}',
'category_header'                => '"$1" kategoriyasındaki məqalələr',
'subcategories'                  => 'Alt kategoriyalar',
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

'mainpagetext'      => "'''MediaWiki müvəffəqiyyətlə quraşdırıldı.'''",
'mainpagedocfooter' => 'Bu vikinin istifadəsi ilə bağlı məlumat almaq üçün [http://meta.wikimedia.org/wiki/Help:Contents İstifadəçi məlumat səhifəsinə] baxın.

== Faydalı keçidlər ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Tənzimləmələrin siyahısı]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki haqqında tez-tez soruşulan suallar]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki e-poçt siyahısı]',

'about'         => 'İzah',
'article'       => 'Mündəricat',
'newwindow'     => '(yeni pəncərədə açılır)',
'cancel'        => 'Ləğv et',
'moredotdotdot' => 'Daha...',
'mypage'        => 'Mənim səhifəm',
'mytalk'        => 'Danışıqlarım',
'anontalk'      => 'Bu IP-yə aid müzakirə',
'navigation'    => 'Rəhbər',
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
'vector-action-addsection'   => 'Mövzu əlavə et',
'vector-action-delete'       => 'Sil',
'vector-action-move'         => 'Adını dəyişdir',
'vector-action-protect'      => 'Mühafizə et',
'vector-action-undelete'     => 'Bərpa et',
'vector-action-unprotect'    => 'Mühafizəni kənarlaşdır',
'vector-namespace-category'  => 'Kateqoriya',
'vector-namespace-help'      => 'Kömək səhifəsi',
'vector-namespace-image'     => 'Fayl',
'vector-namespace-main'      => 'Səhifə',
'vector-namespace-media'     => 'Media səhifəsi',
'vector-namespace-mediawiki' => 'Mesaj',
'vector-namespace-project'   => 'Layihə haqqında',
'vector-namespace-special'   => 'Xüsusi səhifə',
'vector-namespace-talk'      => 'Müzakirə',
'vector-namespace-template'  => 'Şablon',
'vector-namespace-user'      => 'İstifadəçi səhifəsi',
'vector-view-create'         => 'Yarat',
'vector-view-edit'           => 'Redaktə',
'vector-view-history'        => 'Tarixçə',
'vector-view-view'           => 'Oxu',
'vector-view-viewsource'     => 'Mənbəyə bax',
'actions'                    => 'Hərəkətlər',
'namespaces'                 => 'Adlar fəzası',
'variants'                   => 'Variantlar',

'errorpagetitle'    => 'Xəta',
'returnto'          => '$1 səhifəsinə qayıt.',
'tagline'           => '{{SITENAME}} saytından',
'help'              => 'Kömək',
'search'            => 'Axtar',
'searchbutton'      => 'Axtar',
'go'                => 'Gətir',
'searcharticle'     => 'Gətir',
'history'           => 'Səhifənin tarixçəsi',
'history_short'     => 'Tarixçə',
'updatedmarker'     => 'son dəfə mən nəzərdən keçirəndən sonra yenilənib',
'info_short'        => 'Məlumat',
'printableversion'  => 'Çap variantı',
'permalink'         => 'Daimi bağlantı',
'print'             => 'Çap',
'edit'              => 'Redaktə',
'create'            => 'Yarat',
'editthispage'      => 'Bu səhifəni redaktə et',
'create-this-page'  => 'Bu səhifəni yarat',
'delete'            => 'Sil',
'deletethispage'    => 'Bu səhifəni sil',
'undelete_short'    => '$1 {{PLURAL:$1|dəyişikliyi|dəyişiklikləri}} bərpa et',
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
'retrievedfrom'           => 'Mənbə — "$1"',
'youhavenewmessages'      => 'Hal-hazırda $1 var. ($2)',
'newmessageslink'         => 'yeni ismarıclar',
'newmessagesdifflink'     => 'Sonuncu və əvvəlki versiya arasındakı fərq',
'youhavenewmessagesmulti' => '$1-də yeni mesajınız var.',
'editsection'             => 'redaktə',
'editold'                 => 'redaktə',
'viewsourceold'           => 'başlanğıc kodu nəzərdən keçir',
'editlink'                => 'redaktə',
'viewsourcelink'          => 'başlanğıc kodu nəzərdən keçir',
'editsectionhint'         => '$1 bölməsini redaktə et',
'toc'                     => 'Mündəricat',
'showtoc'                 => 'göstər',
'hidetoc'                 => 'gizlə',
'thisisdeleted'           => '$1 bax və ya bərpa et?',
'viewdeleted'             => 'Göstər $1?',
'restorelink'             => '{{PLURAL:$1|bir silinmiş redaktəyə|$1 silinmiş redaktəyə}}',
'feedlinks'               => 'Kanal növü:',
'feed-invalid'            => 'Yanlış qeydiyyat kanalı növü.',
'feed-unavailable'        => 'Sindikasiya xətləri etibarsızdır',
'site-rss-feed'           => '$1 — RSS-lent',
'site-atom-feed'          => '$1 — Atom-lent',
'page-rss-feed'           => '"$1" — RSS-lent',
'page-atom-feed'          => '"$1" — Atom-lent',
'red-link-title'          => '$1 (səhifə mövcud deyil)',

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
'laggedslavemode'      => "'''Xəbərdarlıq:''' Səhifə son əlavələri əks etdirməyə bilər.",
'readonly'             => 'Verilənlər bazası kilidli',
'enterlockreason'      => 'Bloklamanın səbəbini və nəzərdə tutulan müddətini qeyd edin',
'missingarticle-rev'   => '(təftiş#: $1)',
'missingarticle-diff'  => '(fərq: $1, $2)',
'readonly_lag'         => 'Məlumatlar bazasının ikinci dərəcəli serveri əsas serverlə əlaqə yaradanadək məlumatlar bazası avtomatik olaraq bloklanmışdır',
'internalerror'        => 'Daxili xəta',
'internalerror_info'   => 'Daxili xəta: $1',
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
'badtitle'             => 'Yanlış başlıq',
'wrong_wfQuery_params' => 'wfQuery() funksiyası üçün qəbuledilməz parametrlər <br />
Funksiya: $1<br />
Sorğu: $2',
'viewsource'           => 'Mənbə göstər',
'viewsourcefor'        => '$1 üçün',
'actionthrottled'      => 'Sürət məhdudiyyəti',
'protectedpagetext'    => 'Bu səhifə redaktə üçün bağlıdır.',
'viewsourcetext'       => 'Siz bu səhifənin məzmununu görə və köçürə bilərsiniz:',
'ns-specialprotected'  => 'Xüsusi səhifələr redaktə oluna bilməz.',
'titleprotected'       => 'İstifadəçi [[User:$1|$1]] bu başlıqla səhifə yarada bilməz.
Göstərilən səbəb odur ki, "\'\'$2\'\'".',

# Virus scanner
'virus-unknownscanner' => 'naməlum antivirus',

# Login and logout pages
'logouttext'                 => "'''Sistemdən çıxdınız.'''

{{Vikipediya}}nı anonim olaraq istifadə etməyə davam edəbilər, və ya eyni yaxud başqa istifadəçi adı ilə [[Special:UserLogin|yenidən daxil ol]]a bilərsiniz. Diqqətinizə çatdırırıq ki, ön yaddaşı (browser cache) təmizləyənə qədər bə'zi səhifələr sistemdən çıxdığınız halda da göstərilə bilər.",
'welcomecreation'            => '== $1, xoş gəlmişsiniz! ==
Hesabınız yaradıldı.
[[Special:Preferences|{{SITENAME}} nizamlamalarınızı]] dəyişdirməyi unutmayın.',
'yourname'                   => 'İstifadəçi adı',
'yourpassword'               => 'Parol',
'yourpasswordagain'          => 'Parolu təkrar yazın',
'remembermypassword'         => 'Məni xatırla',
'yourdomainname'             => 'Sizin domain',
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
'createaccountmail'          => 'e-məktub ilə',
'createaccountreason'        => 'Səbəb:',
'badretype'                  => 'Daxil etdiyiniz parol uyğun gəlmir.',
'userexists'                 => 'Daxil edilmiş ad istifadədədir.
Lütfən ayrı ad seçin.',
'loginerror'                 => 'Daxil olunma xətası',
'noname'                     => 'İşlək istifadəçi adı daxil etməmişdiniz.',
'loginsuccesstitle'          => 'Daxil olundu',
'loginsuccess'               => "'''\"\$1\" olaraq {{SITENAME}}-ya daxil oldunuz.'''",
'nosuchuser'                 => '"$1" adında istifadəçi mövcud deyil.
İstifadəçi adları hərflərin böyük və ya kiçik olmasına həssasdırlar.
Düzgün yazdığına əmin ol, yaxud [[Special:UserLogin/signup|yeni hesab aç]].',
'nosuchusershort'            => '"<nowiki>$1</nowiki>" adında istifadəçi mövcud deyil.
Düzgün yazdığına əmin ol.',
'nouserspecified'            => 'İstifadəçi adı daxil etməlisiniz.',
'login-userblocked'          => 'Bu istifadəçi bloklanıb. Sistemə giriş üçün icazə verilmir.',
'wrongpassword'              => 'Səhv parol. Təkrar yaz.',
'wrongpasswordempty'         => 'Parol boş. Təkrar yaz.',
'passwordtooshort'           => 'Parolda ən azı {{PLURAL:$1|1 hərf yaxud simvol|$1 hərf yaxud simvol}} olmalıdır.',
'password-name-match'        => 'Parol adınızdan fərqli olmalıdır.',
'mailmypassword'             => 'E-mail ilə yeni parol göndər',
'passwordremindertitle'      => '{{SITENAME}} parol xatırladıcı',
'noemail'                    => '"$1" adlı istifadəçi e-poçt ünvanını qeyd etməmişdir.',
'noemailcreate'              => 'Düzgün e-mail ünvanı qeyd etməlisiniz',
'passwordsent'               => 'Yeni parol "$1" üçün qeydiyyata alınan e-məktub ünvanına göndərilmişdir.
Xahiş edirik, e-məktubu aldıqdan sonra yenidən daxil olasınız.',
'mailerror'                  => 'Məktub göndərmə xətası: $1',
'acct_creation_throttle_hit' => 'Sizin IP adresinizdən bu viki-də son bir gün ərzində {{PLURAL:$1|1 hesab|$1 hesab}} açılmışdır. Bu bir gün ərzində icazə verilən maximal say olduğu üçün, indiki anda daha çox hesab aça bilməzsiniz.',
'emailauthenticated'         => 'E-məktub ünvanınız $2 saat $3 tarixində təsdiq edilib.',
'emailnotauthenticated'      => 'E-məktub ünvanınız təsdiq edilməyib.
Aşağıdakğı xidmətlərin heç biri üçün sizə e-məktub göndərilməyəcək.',
'noemailprefs'               => 'Bu xidmətlərdən yararlanmaq üçün nizamlamalarında E-məktub ünvanını göstər.',
'emailconfirmlink'           => 'E-məktub ünvanını təsdiq et',
'invalidemailaddress'        => 'E-məktub ünvanını qeyri düzgün formatda olduğu üçün qəbul edə bilmirik.
Xahiş edirik düzgün formatlı ünvan daxil edin və ya bu sahəni boş qoyun.',
'accountcreated'             => 'Hesab yaradıldı',
'accountcreatedtext'         => '$1 üçün istifadəçi hesabı yaradıldı.',
'createaccount-title'        => '{{SITENAME}} hesabın yaradılması',
'usernamehasherror'          => 'İstifadəçi adında "diyez" simvolunun istifadəsi mümkün deyil',
'login-throttled'            => 'Sistemə daxil olmaq üçün həddən artıq cəhd etmisiniz.
Yeni cəhd etməzdən əvvəl bir qədər gözləyin.',
'loginlanguagelabel'         => 'Dil: $1',

# JavaScript password checks
'password-retype' => 'Parolu təkrar yazın',

# Password reset dialog
'resetpass'                 => 'Parolu dəyiş',
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
'resetpass-temp-password'   => 'Müvəqqəti parol:',

# Edit page toolbar
'bold_sample'     => 'Qalın mətn',
'bold_tip'        => 'Qalın mətn',
'italic_sample'   => 'Kursiv mətn',
'italic_tip'      => 'Kursiv mətn',
'link_sample'     => 'Bağlantı başlığı',
'link_tip'        => 'Daxili bağlantı',
'extlink_sample'  => 'http://www.example.com başlıq',
'extlink_tip'     => 'Xarici səhifə (http:// ekini unutma)',
'headline_sample' => 'Başlıq metni',
'headline_tip'    => '2. səviyyə başlıq',
'math_sample'     => 'Riyazi formulu bura yazın',
'math_tip'        => 'Riyazi formul (LaTeX formatı)',
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
'anoneditwarning'                  => "'''Diqqət''': Siz özünüzü sistemə təqdim etməmisiniz. Sizin IP ünvanınız bu səhifənin tarixçəsinə qeyd olunacaq.",
'anonpreviewwarning'               => 'Sistemə daxil olmamısınız. "Səhifəni qeyd et" düyməsini bassanız IP ünvanınız səhifənin tarixçəsində qeyd olunacaq.',
'missingsummary'                   => "'''Xatırlatma.''' Siz dəyişikliklərin qısa şərhini verməmisiniz. \"Səhifəni qeyd et\" düyməsinə təkrar basandan sonra sizin dəyişiklikləriniz şərhsiz qeyd olunacaq.",
'missingcommenttext'               => 'Zəhmət olmasa, aşağıda şərhinizi yazın.',
'subject-preview'                  => 'Sərlövhə belə olacaq:',
'blockedtitle'                     => 'İstifadəçi bloklanıb',
'blockednoreason'                  => 'səbəb göstərilməyib',
'blockedoriginalsource'            => "'''$1''' mənbəyi aşağıda göstərilib:",
'blockededitsource'                => "Aşağıda '''$1''' səhifəsində etdiyiniz '''dəyişikliklərin''' mətni göstərilib:",
'whitelistedittitle'               => 'Redaktə üçün daxil olmalısınız',
'whitelistedittext'                => 'Dəyişiklik edə bilmək üçün $1.',
'nosuchsectiontitle'               => 'Belə bölmə yoxdur',
'loginreqtitle'                    => 'Daxil olmalısınız',
'loginreqlink'                     => 'daxil olmalısınız',
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
'note'                             => "'''Qeyd:'''",
'previewnote'                      => "'''Bu yalnız sınaq göstərişidir; dəyişikliklər hal-hazırda qeyd edilmemişdir!'''",
'session_fail_preview'             => "'''Üzr istəyirik! Sizin redaktəniz saxlanılmadı. Serverdə identifikasiyanızla bağlı problemlər yaranmışdır. Lütfən bir daha təkrar edin. Problem həll olunmazsa hesabınızdan çıxın və yenidən daxil olun.'''",
'editing'                          => 'Redaktə $1',
'editingsection'                   => 'Redaktə $1 (bölmə)',
'editingcomment'                   => 'Redaktə et $1 (yeni bölmə)',
'editconflict'                     => 'Eyni vaxtda redaktə: $1',
'yourtext'                         => 'Mətniniz',
'storedversion'                    => 'Qeyd edilmiş versiya',
'editingold'                       => "'''DİQQƏT:Siz bu səhifənin köhnə versiyasını redaktə edirsiniz. Məqaləni yaddaşda saxlayacağınız halda bu versiyadan sonra edilmiş hər bir dəyişiklik itiriləcək.'''",
'yourdiff'                         => 'Fərqlər',
'longpagewarning'                  => "'''DIQQƏT:Bu səhifənin həcmi $1 kb-dır; Həcmi 32 kb yaxın və ya daha artıq olan səhifələr bəzi brouzerlərdə redaktə ilə bağlı problemlər yarada bilər. Mümkünsə səhifəni daha kiçik bölmələrə bölün.'''",
'semiprotectedpagewarning'         => "'''Qeyd:''' Bu səhifə mühafizəli olduğu üçün yalnız qeydiyyatdan keçmiş istifadəçilər redaktə edə bilərlər.",
'titleprotectedwarning'            => "'''DİQQƏT:  Bu səhifə mühafizəlidir, yalnız [[Special:ListGroupRights|icazəsi olan]] istifadəçilər onu redaktə edə bilərlər.'''",
'templatesused'                    => 'Bu səhifədə istifadə edilmiş {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedsection'             => 'Bu bölmədə istifadə edilmiş {{PLURAL:$1|şablon|şablonlar}}',
'template-protected'               => '(mühafizə)',
'template-semiprotected'           => '(yarım-mühafizə)',
'nocreatetitle'                    => 'Səhifə yaratma məhdudlaşdırılıb.',
'nocreate-loggedin'                => 'Sizin yeni səhifələr yaratmaq üçün icazəniz yoxdur.',
'permissionserrorstext'            => 'Siz, bunu aşağıdakı {{PLURAL:$1|səbəbə|səbəblərə}} görə edə bilməzsiniz:',
'permissionserrorstext-withaction' => 'Aşağıdakı {{PLURAL:$1|səbəbə|səbəblərə}} görə $2 hüququnuz yoxdur:',
'recreate-moveddeleted-warn'       => "'''Diqqət: Siz əvvəllər silinmiş səhifəni bərpa etmək istəyirsiz.'''

Bu səhifəni yenidən yaratmağın nə qədər zəruri olduğunu bir daha yoxlayın.
Bu səhifə üçün silmə qeydləri aşağıda göstərilmişdir:",
'moveddeleted-notice'              => 'Bu səhifə silinmişdir.
Məlumat üçün aşağıda bu səhifənin tarixçəsindən müvafiq silmə qeydləri göstərilmişdir.',
'edit-gone-missing'                => 'Səhifəni yeniləmək mümkün deyil.
Çox güman ki, səhifə silinmişdir.',
'edit-conflict'                    => 'Düzəlişlər münaqişəsi',
'edit-no-change'                   => 'Sizin redaktələr qeydə alınmamışdır. Belə ki, mətndə heç bir düzəliş edilməmişdir.',
'edit-already-exists'              => 'Yeni səhifəni yaratmaq mümkün deyil.
Belə ki, bu adda səhifə artıq mövcuddur.',

# Parser/template warnings
'post-expand-template-inclusion-warning' => "'''Diqqət:''' Daxil edilən şablonların həcmi həddindən artıq böyükdür.
Bəzi şablonlar əlavə olunmayacaq.",

# History pages
'viewpagelogs'           => 'Bu səhifə ilə bağlı qeydlərə bax',
'nohistory'              => 'Bu səhifənin dəyişikliklər tarixçəsi mövcud deyil.',
'currentrev'             => 'Hal-hazırkı versiya',
'revisionasof'           => '$1 versiyası',
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
'histfirst'              => 'Ən əvvəlki',
'histlast'               => 'Ən sonuncu',
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(boş)',

# Revision feed
'history-feed-title'          => 'Redaktə tarixçəsi',
'history-feed-description'    => 'Vikidə bu səhifənin dəyişikliklər tarixçəsi',
'history-feed-item-nocomment' => '$1-dən $2-yə',
'history-feed-empty'          => 'Axtardığınız səhifə mövcud deyil.
Mümkündür ki, bu səhifə silinib və ya onun adı dəyişdirilib.
Vikidə buna bənzər səhifələr [[Special:Search|axtarmağa]] cəhd edin.',

# Revision deletion
'rev-deleted-comment'         => '(şərhlər silindi)',
'rev-deleted-user'            => '(istifadəçi adı silindi)',
'rev-deleted-event'           => '(qeyd silindi)',
'rev-deleted-text-permission' => "Səhifənin bu versiyası''' silinib'''.
Mümkündür ki, bunun səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-suppressed-text-unhide'  => "Səhifənin bu versiyası''' silinib'''.
Mümkündür ki, bunun səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.
Siz idarəçi olduğunuza görə silinən [$1 bu versiyanı] nəzərdən keçirə bilərsiniz.",
'rev-deleted-text-view'       => "Səhifənin bu versiyası''' silinib'''.
Siz idarəçi olduğunuza görə silinən bu versiyanı nəzərdən keçirə bilərsiniz. Mümkündür ki, silinmənin səbəbi [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-deleted-no-diff'         => "Siz versiyalar arasındakı fərqi nəzərdən keçirə bilməzsiniz. Belə ki, versiyalardan biri '''silinib'''.
Mümkündür ki, bununla bağlı təfərrüatlar [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} silmə qeydlərində] göstərilmişdir.",
'rev-delundel'                => 'göstər/gizlət',
'revisiondelete'              => 'Səhifənin versiyalarını sil/bərpa et',
'revdelete-no-file'           => 'Axtarılan fayl mövcud deyil',
'revdelete-show-file-submit'  => 'Bəli',
'revdelete-selected'          => "'''[[:$1]] səhifəsinin {{PLURAL:$2|seçilmiş versiyası|seçilmiş versiyaları}}:'''",
'logdelete-selected'          => "'''Jurnalın {{PLURAL:$1|seçilmiş qeydi|seçilmiş qeydləri}}:'''",
'revdelete-legend'            => 'Məhdudiyyətləri müəyyənləşdir:',
'revdelete-hide-text'         => 'Səhifənin bu versiyasının mətnini gizlə',
'revdelete-hide-image'        => 'Faylın məzmununu gizlə',
'revdelete-hide-comment'      => 'Dəyişikliklərin şərhini gizlə',
'revdelete-hide-user'         => 'Redaktə müəllifinin istifadəçi adını/IP ünvanını gizlə',
'revdelete-hide-restricted'   => 'Məlumatları idarəçilərdən də gizlə',
'revdelete-suppress'          => 'Məlumatları idarəçilərdən də gizlə',
'revdelete-unsuppress'        => 'Bərpa olunan versiyalar üzərindən məhdudiyyətləri qaldır',
'revdelete-log'               => 'Səbəb:',
'revdelete-submit'            => 'Seçilmiş {{PLURAL:$1|versiyaya|versiyayalara}} tətbiq et',
'revdelete-logentry'          => '[[$1]] səhifəsinin versiyasının görünüşünü dəyişdirdi',
'revdelete-success'           => "'''Versiyanın görünüşü yeniləndi.'''",
'revdel-restore'              => 'Görünüşü dəyiş',
'pagehist'                    => 'Səhifənin tarixçəsi',
'deletedhist'                 => 'Silmə qeydləri',
'revdelete-content'           => 'məzmun',
'revdelete-summary'           => 'redaktə xülasəsi',
'revdelete-uname'             => 'istifadəçi adı',
'revdelete-restricted'        => 'məhdudiyyətlər idarəçilərə tətbiq olunur',
'revdelete-hid'               => 'gizlət $1',
'revdelete-reasonotherlist'   => 'Digər səbəb',
'revdelete-edit-reasonlist'   => 'Silmə səbəblərini redaktə et',

# Suppression log
'suppressionlog' => 'Qadağa qedi',

# History merging
'mergehistory'                => 'Səhifə tarixçələrinin birləşdirilməsi',
'mergehistory-box'            => 'İki səhifənin tarixçəsini birləşdir',
'mergehistory-from'           => 'Mənbə səhifəsi:',
'mergehistory-no-destination' => 'Mənbə səhifəsi $1 mövcud deyil.',
'mergehistory-reason'         => 'Səbəb:',

# Merge log
'revertmerge' => 'Ayır',

# Diffs
'difference'              => '(Versiyalar arasındakı fərq)',
'lineno'                  => 'Sətir $1:',
'compareselectedversions' => 'Seçilən versiyaları müqaisə et',
'editundo'                => 'əvvəlki halına qaytar',
'diff-multi'              => '({{PLURAL:$1|bir aralıq dəyişiklik|$1 aralıq dəyişiklik}} göstərilməmişdir.)',

# Search results
'searchresults'             => 'Axtarış nəticələri',
'searchresults-title'       => "''$1'' üçün axtarış nəticələri",
'searchresulttext'          => '{{SITENAME}}-nı axtarmaqla bağlı ətraflı məlumat üçün [[{{MediaWiki:Helppage}}|{{int:kömək}}]] səhifəsinə baş çək.',
'searchsubtitle'            => '"[[:$1]]" üçün axtarış ([[Special:Prefixindex/$1|"$1" ilə başlayan bütün səhifələr]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1" səhifəsi ilə əlaqəli olan bütün səhifələr]])',
'searchsubtitleinvalid'     => 'Axtarılan: "$1"',
'notitlematches'            => 'Uyğun gələn səhifə adı tapılmadı',
'notextmatches'             => 'Məqalələrdə uyğun məzmun tapılmadı',
'prevn'                     => 'əvvəlki {{PLURAL:$1|$1}}',
'nextn'                     => 'sonrakı {{PLURAL:$1|$1}}',
'viewprevnext'              => 'Göstər ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-exists'         => "'''Bu vikidə \"[[:\$1]]\" adında səhifə mövcutdur'''",
'searchmenu-new'            => "'''Bu vikidə \"[[:\$1]]\" səhifəsini yarat!'''",
'searchhelp-url'            => 'Help:Mündəricət',
'searchprofile-images'      => 'Multimedia',
'search-result-size'        => '$1 ({{PLURAL:$2|1 söz|$2 sözlər}})',
'search-redirect'           => '(yönləndirmə $1)',
'search-section'            => '(bölmə $1)',
'search-suggest'            => 'Bəlkə, bunu nəzərdə tuturdunuz: $1',
'search-interwiki-caption'  => 'Qardaş layihələr',
'search-interwiki-default'  => '$1 nəticə:',
'search-interwiki-more'     => '(yenə)',
'search-mwsuggest-enabled'  => 'təkliflərlə',
'search-mwsuggest-disabled' => 'təklif yoxdur',
'searchall'                 => 'bütün',
'nonefound'                 => "'''Qeyd''': Əksər uğursuz axtarışlara səbəb indeksləşdirilməyən, geniş işlənən \"var\", \"və\" tipli sözlər və ya axtarışa bir sözdən artıq ifadələrin verilməsidir. Çalışıb axtardığınız ifadənin qarşısında ''all:'' (bütün) yazın. Bu halda axtarışınız istifadəçi səhifələrini, şablonları və s. da əhatə edəcək.",
'search-nonefound'          => 'Sorğunuza uyğun nəticə tapılmadı.',
'powersearch'               => 'Axtar',
'powersearch-legend'        => 'Təkmil axtarış',
'powersearch-ns'            => 'Ad aralığında axtar:',
'powersearch-redir'         => 'Yönləndirmələri göstər',
'powersearch-field'         => 'Axtar:',
'powersearch-togglelabel'   => 'Yoxla:',
'powersearch-toggleall'     => 'Hamısı',
'powersearch-togglenone'    => 'Heç biri',

# Quickbar
'qbsettings-none' => 'Heç biri',

# Preferences page
'preferences'                  => 'Nizamlamalar',
'mypreferences'                => 'Nizamlamalarım',
'prefs-edits'                  => 'Redaktələrin sayı:',
'prefsnologintext'             => 'Nizamlamaları dəyişmək üçün <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} daxil olmaq]</span> zəruridir.',
'changepassword'               => 'Parolu dəyiş',
'prefs-skin'                   => 'Üzlük',
'skin-preview'                 => 'Sınaq göstərişi',
'prefs-math'                   => 'Riyaziyyat',
'datedefault'                  => 'Tərcih yox',
'prefs-datetime'               => 'Tarix və vaxt',
'prefs-personal'               => 'İstifadəçi profili',
'prefs-rc'                     => 'Son dəyişikliklər',
'prefs-watchlist'              => 'İzləmə siyahısı',
'prefs-watchlist-days'         => 'İzləmə siyahısında göstərilən maksimal günlərin sayı:',
'prefs-watchlist-days-max'     => 'Maksimum 7 gün',
'prefs-watchlist-edits'        => 'İzləmə siyahısında göstərilən maksimal redaktələrin sayı:',
'prefs-watchlist-edits-max'    => 'Maksimum say: 1000',
'prefs-misc'                   => 'Digər tərcihlər',
'prefs-resetpass'              => 'Parolu dəyiş',
'saveprefs'                    => 'Qeyd et',
'resetprefs'                   => 'Reset',
'prefs-editing'                => 'Redaktə',
'rows'                         => 'Sıralar:',
'columns'                      => 'Sütunlar:',
'searchresultshead'            => 'Axtar',
'resultsperpage'               => 'Səhifəyə aid tapılmış nəticələr:',
'contextlines'                 => 'Nəticələrə aid sıralar:',
'contextchars'                 => 'Sıraya aid işarələr:',
'stub-threshold'               => '<a href="#" class="stub">Keçidsiz linki</a> format etmək üçün hüdud (baytlarla):',
'recentchangesdays'            => 'Son dəyişiklərdə göstərilən günlərin miqdarı:',
'recentchangesdays-max'        => 'Maksimum $1 {{PLURAL:$1|gün|gün}}',
'recentchangescount'           => 'Son dəyişikliklərdə başlıq sayı:',
'prefs-help-watchlist-token'   => 'Bu sahəni gizli parolla doldurmağınız sizin izləmə siyahınız üçün RSS yayım kanalı yaradacaqdır.
Bu parolu bilən hər kəs izləmə siyahınızı oxuya bilər, bu səbəbdən etibarlı parol seçin.
Təsadüfi yolla seçilmiş bu paroldan istifadə edə bilərsiniz: $1',
'savedprefs'                   => 'Tərcihlər qeyd edildi.',
'timezonelegend'               => 'Vaxt zonası:',
'localtime'                    => 'Yerli vaxt:',
'timezoneoffset'               => 'Vaxt fərqı¹:',
'servertime'                   => 'Server vaxtı:',
'guesstimezone'                => 'Brouzerdən götür',
'timezoneregion-africa'        => 'Afrika',
'timezoneregion-america'       => 'Amerika',
'timezoneregion-antarctica'    => 'Antarktika',
'timezoneregion-arctic'        => 'Arktik',
'timezoneregion-asia'          => 'Asiya',
'timezoneregion-atlantic'      => 'Atlantik Okean',
'timezoneregion-australia'     => 'Avstraliya',
'timezoneregion-europe'        => 'Avropa',
'timezoneregion-indian'        => 'Hind Okeanı',
'timezoneregion-pacific'       => 'Sakit Okean',
'allowemail'                   => 'Digər istifadəçilər mənə e-məktub göndərəbilir',
'defaultns'                    => 'Susmaya görə bu ad fəzalarında axtar:',
'prefs-files'                  => 'Fayllar',
'youremail'                    => 'E-məktub *',
'username'                     => 'İstifadəçi adı:',
'uid'                          => 'İstifadəçi ID:',
'prefs-memberingroups'         => 'Üzvü olduğu {{PLURAL:$1|qrup|qruplar}}:',
'prefs-registration'           => 'Qeydiyyat vaxtı:',
'prefs-registration-date-time' => '$1',
'yourrealname'                 => 'Həqiqi adınız:',
'yourlanguage'                 => 'Dil:',
'yournick'                     => 'Ləqəb:',
'badsig'                       => 'Səhv xam imza.
HTML kodu yoxla.',
'yourgender'                   => 'Cins:',
'gender-unknown'               => 'Göstərmə',
'gender-male'                  => 'Kişi',
'gender-female'                => 'Qadın',
'email'                        => 'E-məktub',
'prefs-help-realname'          => 'Həqiqi adınızı daxil etmək qeyri-məcburidir.
Bu seçimi etdiyiniz halda, adınız işinizə görə müəlliflik hüququnuzu tanımaq üçün istifadə ediləcək.',
'prefs-help-email'             => 'E-məktub ünvanınızı daxil etmək qeyri-məcburidir.
Bu parolunuzu unutduğunuz halda sizə yeni parol göndərməyə imkan verir.
Həmçinin kimliyinizi gostərmədən belə, başqalarının sizinlə istifadəçi və ya istifadəçi müzakirəsi səhifələriniz vasitəsi ilə əlaqə yaratmalarını seçə bilərsiniz.',
'prefs-help-email-required'    => 'Elektron ünvan tələb olunur.',
'prefs-i18n'                   => 'Beynəlxalqlaşdırma',
'prefs-signature'              => 'İmza',

# User rights
'userrights'               => 'İstifadəçi hüququ idarəsi',
'userrights-lookup-user'   => 'İstifadəçi qruplarını idarə et',
'userrights-user-editname' => 'İstifadəçi adınızı yazın:',
'editusergroup'            => 'Redaktə İstifadəçi Qrupları',
'editinguser'              => "Redaktə '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup' => 'İstifadəçi qruplarını redaktə et',
'saveusergroups'           => 'İstifadəçi qrupunu qeyd et',
'userrights-reason'        => 'Səbəb:',

# Groups
'group'               => 'Qrup:',
'group-user'          => 'İstifadəçilər',
'group-autoconfirmed' => 'Avtotəsdiqlənmiş istifadəçilər',
'group-bot'           => 'Botlar',
'group-sysop'         => 'İdarəçilər',
'group-bureaucrat'    => 'Bürokratlar',
'group-suppress'      => 'Təftişçilər',
'group-all'           => '(bütün)',

'group-user-member'          => 'İstifadəçi',
'group-autoconfirmed-member' => 'Avtotəsdiqlənmiş istifadəçilər',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'İdarəçi',
'group-bureaucrat-member'    => 'Bürokrat',
'group-suppress-member'      => 'Təftişçi',

'grouppage-autoconfirmed' => '{{ns:project}}:Avtotəsdiqlənmiş istifadəçilər',
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:İdarəçilər',
'grouppage-bureaucrat'    => '{{ns:project}}:Bürokratlar',

# Rights
'right-read'                 => 'Səhifələrin oxunması',
'right-edit'                 => 'Səhifələrin redaktəsi',
'right-createpage'           => 'Səhifələr yaratmaq (müzakirə səhifələrindən əlavə səhifələr nəzərdə tutulur)',
'right-createtalk'           => 'Müzakirə səhifələri yaratmaq',
'right-createaccount'        => 'Yeni istifadəçi hesabları açmaq',
'right-minoredit'            => 'Redaktələri kiçik redaktə kimi nişanlamaq',
'right-reupload'             => 'Mövcud faylın yeni versiyasının yüklənməsi',
'right-reupload-own'         => 'Mövcud faylın yeni versiyasının həmin istifadəçi tərəfindən yüklənməsi',
'right-writeapi'             => 'Redaktələrdən zamanı API-dən (İnterfeys proqramlaşdıran proqram) istifadə',
'right-delete'               => 'Səhifələri sil',
'right-bigdelete'            => 'Uzun tarixçəsi olan səhifələri sil',
'right-browsearchive'        => 'Silinmiş səhifələri axtar',
'right-undelete'             => 'Pozulmuş səhifənin bərpası',
'right-suppressionlog'       => 'Şəxsi qeydlərə bax',
'right-block'                => 'Digər istifadəçilərin redaktə etməsinə qadağa qoy',
'right-blockemail'           => 'İstifadəçinin e-poçt göndərməsinə qadağa qoy',
'right-hideuser'             => 'İstifadəçi adına qadağa qoy və adın görünməsinin qarşısını al',
'right-userrights'           => 'Bütün istifadəçi hüquqlarını redaktə et',
'right-userrights-interwiki' => 'Digər vikilərdəki istifadəçilərin istifadəçi hüquqlarını dəyişdir',
'right-siteadmin'            => 'Məlumatlar bazasının bloklanması və blokun götürülməsi',
'right-sendemail'            => 'Digər istifadəçilərə elektron poçt göndər',

# User rights log
'rightslog'      => 'İstifadəçi hüquqları qeydləri',
'rightslogtext'  => 'İstifadəçi hüquqları dəyişikliyi qeydləri.',
'rightslogentry' => '$1 adlı istifadəçinin istifadəçi qruplarındakı üzvlüyü dəyişdirildi: $2 ► $3',
'rightsnone'     => '(heç biri)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'               => 'bu səhifəni oxu',
'action-edit'               => 'bu səhifəni redaktə etmək',
'action-createpage'         => 'səhifələr yarat',
'action-createtalk'         => 'müzakirə səhifələri yarat',
'action-createaccount'      => 'bu istifadəçi hesabını yarat',
'action-minoredit'          => 'bunu kiçik redaktə kimi nişanla',
'action-move'               => 'bu səhifənin adını dəyişmək',
'action-move-subpages'      => 'bu səhifənin və onun altsəhifələrinin adını dəyişmək',
'action-move-rootuserpages' => 'əsas istifadəçi səhifələrinin adını dəyişmək',
'action-movefile'           => 'bu faylın adını dəyişmək',
'action-upload'             => 'bu faylı yüklə',
'action-delete'             => 'bu səhifəni sil',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|dəyişiklik|dəyişiklik}}',
'recentchanges'                     => 'Son dəyişikliklər',
'recentchanges-legend'              => 'Son dəyişiklik seçimləri',
'recentchangestext'                 => "'''Ən son dəyişiklikləri bu səhifədən izləyin.'''",
'recentchanges-feed-description'    => 'Vikidəki ən son dəyişiklikləri bu yayım kanalından izləyin.',
'recentchanges-label-legend'        => 'Şərh: $1.',
'recentchanges-legend-newpage'      => '$1 - yeni səhifə',
'recentchanges-label-newpage'       => 'Bu dəyişiklik yeni səhifə yaratdı',
'recentchanges-legend-minor'        => '$1 - kiçik redaktə',
'recentchanges-label-minor'         => 'Bu kiçik redaktədir',
'recentchanges-legend-bot'          => '$1 - bot redaktəsi',
'recentchanges-label-bot'           => 'Bu redaktə bot tərəfindən edilmişdir',
'recentchanges-legend-unpatrolled'  => '$1 - nəzərdən keçirilməmiş redaktə',
'recentchanges-label-unpatrolled'   => 'Bu redaktə nəzərdən keçirilməmişdir',
'rcnote'                            => "Aşağıdakı {{PLURAL:$1|'''1''' dəyişiklik|'''$1''' dəyişiklik}} saat $5, $4 tarixinə qədər son {{PLURAL:$2|gün|'''$2''' gün}} ərzində edilmişdir.",
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
'number_of_watching_users_pageview' => '[İzləmə siyahısında $1 istifadəçi]',
'newsectionsummary'                 => '/* $1 */ yeni bölmə',
'rc-enhanced-expand'                => 'Detalları göstər (JavaScript istifadə edir)',
'rc-enhanced-hide'                  => 'Redaktələri gizlət',

# Recent changes linked
'recentchangeslinked'         => 'Əlaqəli redaktələr',
'recentchangeslinked-feed'    => 'Əlaqəli redaktələr',
'recentchangeslinked-toolbox' => 'Əlaqəli redaktələr',
'recentchangeslinked-title'   => "''$1'' ilə əlaqəli dəyişikliklər",
'recentchangeslinked-summary' => "Aşağıdakı siyahı, qeyd olunan səhifəyə (və ya qeyd olunan kateqoriyadakı səhifələrə) daxili keçid verən səhifələrdə edilmiş son dəyişikliklərin siyahısıdır.
[[Special:Watchlist|İzləmə siyahınızdakı]] səhifələr '''qalın''' şriftlə göstərilmişdir.",
'recentchangeslinked-page'    => 'Səhifə adı:',
'recentchangeslinked-to'      => 'Qeyd olunan səhifədəki deyil, ona daxili keçid verən səhifələrdəki dəyişiklikləri göstər',

# Upload
'upload'              => 'Qarşıya yüklə',
'uploadbtn'           => 'Sənəd yüklə',
'reuploaddesc'        => 'Return to the upload form.',
'uploadnologintext'   => 'Fayl yükləmək üçün [[Special:UserLogin|daxil olmalısınız]].',
'uploaderror'         => 'Yükləmə xətası',
'uploadlog'           => 'yükləmə qeydi',
'uploadlogpage'       => 'Yükləmə qeydi',
'uploadlogpagetext'   => 'Aşağıda ən yeni yükləmə jurnal qeydləri verilmişdir.',
'filename'            => 'Fayl adı',
'filedesc'            => 'Xülasə',
'fileuploadsummary'   => 'İzahat:',
'filestatus'          => 'Müəllif statusu:',
'filesource'          => 'Mənbə:',
'uploadedfiles'       => 'Yüklənmiş fayllar',
'ignorewarning'       => 'Xəbərdarlıqlara əhəmiyyət vermə və faylı saxla',
'badfilename'         => 'Faylın adı dəyişildi. Yeni adı: "$1".',
'emptyfile'           => 'Yüklədiyiniz fayl boşdur. Bu faylın adında olan hərf səhvi ilə bağlı ola bilər. Xahiş olunur ki, doğurdan da bu faylı yükləmək istədiyinizi yoxlayasınız.',
'fileexists'          => "Yükləmək istədiyiniz adda fayl mövcutdur.
Lütfən '''<tt>[[:$1]]</tt>''' keçidini yoxlayın və bu faylı yükləmək istədiyinizdən əmin olun.
[[$1|thumb]]",
'uploadwarning'       => 'Yükləyiş xəbərdarlıqı',
'savefile'            => 'Faylı qeyd et',
'uploadedimage'       => 'yükləndi "[[$1]]"',
'sourcefilename'      => 'Fayl adı mənbələri',
'destfilename'        => 'Fayl adı',
'watchthisupload'     => 'Bu faylı izlə',
'upload-wasdeleted'   => "'''Diqqət:Siz əvvəl bu ad altında mövcud olmuş və silinmiş faylı yenidən yükləməkdəsiniz'''

Əvvəlcədən bu faylı yenidən yükləməyin nə dərəcədə lazımlı olduğunu müəyyənləşdirməniz məsləhətdir.
Bu səhifə üçün silmə qeydləri aşağıda göstərilmişdir:",
'upload-success-subj' => 'Yükləmə tamamlandı',

'license'        => 'Lisenziya',
'license-header' => 'Lisenziya',
'nolicense'      => 'Heç biri seçilməmişdir',

# Special:ListFiles
'listfiles'      => 'Fayl siyahısı',
'listfiles_date' => 'Tarix',
'listfiles_name' => 'Ad',
'listfiles_user' => 'İstifadəçi',

# File description page
'file-anchor-link'          => 'Fayl',
'filehist'                  => 'Faylın tarixçəsi',
'filehist-deleteone'        => 'sil',
'filehist-current'          => 'indiki',
'filehist-datetime'         => 'Tarix/Vaxt',
'filehist-thumb'            => 'Kiçik şəkil',
'filehist-user'             => 'İstifadəçi',
'filehist-dimensions'       => 'Ölçülər',
'filehist-comment'          => 'Şərh',
'imagelinks'                => 'Fayl keçidləri',
'uploadnewversion-linktext' => 'Bu faylın yeni versiyasını yüklə',

# File deletion
'filedelete'                  => '$1 adlı faylı sil',
'filedelete-legend'           => 'Faylı sil',
'filedelete-intro'            => "'''[[Media:$1|$1]]''' faylını və onunla bağlı bütün tarixçəni silmək ərəfəsindəsiniz.",
'filedelete-comment'          => 'Səbəb:',
'filedelete-success'          => "'''$1''' silinmişdir.",
'filedelete-success-old'      => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\'-nin  $3 və $2 versiyaları silinmişdir.</span>',
'filedelete-otherreason'      => 'Başqa/əlavə səbəb:',
'filedelete-reason-otherlist' => 'Başqa səbəb',
'filedelete-reason-dropdown'  => '*Əsas silmə səbəbi
** Müəllif hüququ pozuntusu
** Dublikat fayl',

# MIME search
'mimesearch' => 'MIME axtar',

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
'statistics'              => 'Statistika',
'statistics-header-pages' => 'Səhifə statistikası',
'statistics-header-edits' => 'Redaktə statistikası',
'statistics-header-views' => 'Statistikaya bax',
'statistics-header-users' => 'İstifadəçi statistika',
'statistics-header-hooks' => 'Digər statistikalar',
'statistics-pages'        => 'Səhifələr',
'statistics-pages-desc'   => 'Vikidə olan bütün səhifələr, istifadəçi müzakirələri, istiqamətləndirmə səhifələri və s. daxil olmaqla',
'statistics-users'        => 'Qeydiyyatdan keçmiş [[Special:ListUsers|istifadəçilər]]',
'statistics-users-active' => 'Aktiv istifadəçilər',
'statistics-mostpopular'  => 'Ən çox baxılmış səhifələr',

'disambiguations'      => 'Dəqiqləşdirmə səhifələri',
'disambiguationspage'  => 'Template:dəqiqləşdirmə',
'disambiguations-text' => "Aşağıdakı səhifələr '''dəqiqləşdirmə səhifələrinə''' keçid verir. Bunun əvəzinə onlar çox guman ki, müvafiq konkret bir məqaləni göstərməlidirlər.
<br />Səhifə o zaman dəqiqləşdirmə səhifəsi hesab edilir ki, onda  [[MediaWiki:Disambiguationspage]]-dən keçid verilmiş şablon istifadə edilir.",

'doubleredirects' => 'İkiqat istiqamətləndirmələr',

'brokenredirects'        => 'Xətalı istiqamətləndirmə',
'brokenredirectstext'    => 'Aşağıdakı istiqamətləndirmələr mövcud olmayan səhifələrə keçid verir:',
'brokenredirects-edit'   => 'redaktə',
'brokenredirects-delete' => 'sil',

'withoutinterwiki'        => 'Dil keçidləri olmayan səhifələr',
'withoutinterwiki-submit' => 'Göstər',

'fewestrevisions' => 'Az dəyişiklik edilmiş məqalələr',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt|bayt}}',
'ncategories'             => '$1 {{PLURAL:$1|kateqoriya|kateqoriyalar}}',
'nlinks'                  => '$1 {{PLURAL:$1|keçid|keçidlər}}',
'nmembers'                => '$1 {{PLURAL:$1|üzv|üzvlər}}',
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
'wantedpages'             => 'Tələbat olan səhifələr',
'wantedfiles'             => 'Təlabat olan fayllar',
'wantedtemplates'         => 'Təlabat olan şablonlar',
'mostlinked'              => 'Ən çox keçidlənən səhifələr',
'mostlinkedcategories'    => 'Ən çox məqaləsi olan kateqoriyalar',
'mostcategories'          => 'Kateqoriyası ən çox olan məqalələr',
'mostimages'              => 'Ən çox istifadə edilmiş şəkillər',
'mostrevisions'           => 'Ən çox nəzərdən keçirilmiş (versiyalı) məqalələr',
'prefixindex'             => 'Prefiks indeksli bütün səhifələr',
'shortpages'              => 'Qısa səhifələr',
'longpages'               => 'Uzun səhifələr',
'deadendpages'            => 'Keçid verməyən səhifələr',
'deadendpagestext'        => 'Aşağıdakı səhifələrdən bu Vikipediyadakı digər səhifələrə heç bir keçid yoxdur.',
'protectedpages'          => 'Mühafizəli səhifələr',
'protectedpagestext'      => 'Aşağıdakı səhifələr ad dəyişiminə və redaktəyə bağlıdır',
'protectedpagesempty'     => 'Hal-hazırda bu parametrə uyğun heç bir mühafizəli səhifə yoxdur',
'listusers'               => 'İstifadəçi siyahısı',
'newpages'                => 'Yeni səhifələr',
'newpages-username'       => 'İstifadəçi adı:',
'ancientpages'            => 'Ən köhnə səhifələr',
'move'                    => 'Adını dəyiş',
'movethispage'            => 'Bu səhifənin adını dəyiş',
'pager-newer-n'           => '{{PLURAL:$1|1 daha yeni|$1 daha yeni}}',
'pager-older-n'           => '{{PLURAL:$1|1 daha köhnə|$1 daha köhnə}}',

# Book sources
'booksources'               => 'Kitab mənbələri',
'booksources-search-legend' => 'Kitab mənbələri axtar',
'booksources-go'            => 'Gətir',
'booksources-text'          => 'Aşağıda yeni və işlənmiş kitablar satan xarici keçidlərdə siz axtardığınız kitab haqqında əlavə məlumat ala bilərsiz:',

# Special:Log
'specialloguserlabel'  => 'İstifadəçi:',
'speciallogtitlelabel' => 'Başlıq:',
'log'                  => 'Loglar',
'all-logs-page'        => 'Bütün ictimai qeydlər',
'alllogstext'          => '{{SITENAME}} üçün bütün mövcud qeydlərin birgə göstərişi.
Qeyd növü, istifadəçi adı və ya təsir edilmiş səhifəni seçməklə daha spesifik ola bilərsiniz.',
'logempty'             => 'Jurnalda uyğun qeyd tapılmadı.',

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
'allpagessubmit'    => 'Gətir',
'allpagesprefix'    => 'Bu prefiksli səhifələri göstər:',

# Special:Categories
'categories'         => 'Kateqoriyalar',
'categoriespagetext' => 'Aşağıdakı {{PLURAL:$1|kateqoriyada|kateqoriyalarda}} səhifələr yaxud mediya var.
[[Special:UnusedCategories|İstifadədə olmayan kateqoriyalar]] burda göstərilməyib.
Həmçinin [[Special:WantedCategories|xahiş edilmiş kateqoriyalara]] bax.',

# Special:LinkSearch
'linksearch'    => 'Xarici keçidlər',
'linksearch-ok' => 'Axtar',

# Special:ListUsers
'listusers-submit'   => 'Göstər',
'listusers-noresult' => 'İstifadəçi tapılmadı.',

# Special:ActiveUsers
'activeusers' => 'Aktiv istifadəçilərin siyahısı',

# Special:Log/newusers
'newuserlog-create-entry'     => 'Yeni istifadəçi hesabı',
'newuserlog-autocreate-entry' => 'Hesab avtomatik olaraq yaradıldı',

# Special:ListGroupRights
'listgrouprights'          => 'İstifadəçi qruplarının hüquqları',
'listgrouprights-summary'  => 'Bu vikidə olan istifadəçi siyahıları və onların hüquqları aşağıda göstərilmişdir.
Fərdi hüquqlar haqqında əlavə məlumatı [[{{MediaWiki:Listgrouprights-helppage}}]] səhifəsində tapa bilərsiniz',
'listgrouprights-group'    => 'Qrup',
'listgrouprights-rights'   => 'Hüquqlar',
'listgrouprights-helppage' => 'Help:Qrup hüquqları',
'listgrouprights-members'  => '(üzvləri)',

# E-mail user
'emailuser'       => 'İstifadəçiyə e-məktub yolla',
'emailpage'       => 'İstifadəçiyə e-məktub yolla',
'defemailsubject' => '{{SITENAME}} e-məktub',
'noemailtitle'    => 'E-məktub ünvanı yox',
'noemailtext'     => 'Bu istifadəçi işlək e-məktub ünvanı qeyd etməmişdir.',
'emailfrom'       => 'Kimdən:',
'emailto'         => 'Kimə',
'emailsubject'    => 'Mövzu:',
'emailmessage'    => 'Mesaj:',
'emailsend'       => 'Göndər',
'emailsent'       => 'E-məktub göndərildi',
'emailsenttext'   => 'E-məktub mesajınız göndərildi.',

# Watchlist
'watchlist'            => 'İzlədiyim səhifələr',
'mywatchlist'          => 'İzlədiyim səhifələr',
'watchlistfor'         => "('''$1''' üçün)",
'watchnologin'         => 'Daxil olmamısınız',
'watchnologintext'     => 'İzləmə siyahınızda dəyişiklik aparmaq üçün [[Special:UserLogin|daxil olmalısınız]].',
'addedwatch'           => 'İzləmə siyahısına əlavə edildi.',
'addedwatchtext'       => '"$1" səhifəsi [[Special:Watchlist|izlədiyiniz səhifələrə]] əlavə edildi. Bu səhifədə və əlaqəli müzakirə səhifəsində olacaq dəyişikliklər orada göstəriləcək və səhifə asanlıqla seçiləbilmək üçün [[Special:RecentChanges|son dəyişikliklər]]-də qalın şriftlərlə görsənəcəkdir.

Səhifəni izləmə sıyahınızdan çıxarmaq üçün yan lovhədəki "izləmə" düyməsinə vurun.',
'removedwatch'         => 'İzləmə siyahısından çıxardılıb',
'removedwatchtext'     => '"[[:$1]]" səhifəsi [[Special:Watchlist|izləmə siyahınızdan]] çıxardıldı.',
'watch'                => 'İzlə',
'watchthispage'        => 'Bu səhifəni izlə',
'unwatch'              => 'İzləmə',
'unwatchthispage'      => 'İzləmə',
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
{{fullurl:Special:Watchlist/edit}}

Yardım və təklifləriniz üçün:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Səhifəni sil',
'confirm'                => 'Təsdiq et',
'excontent'              => "Köhnə məzmun: '$1'",
'excontentauthor'        => "tərkib: '$1' (və '[[Special:Contributions/$2|$2]]' tarixçədə fəaliyyəti qeyd edilən yeganə istifadəçidir)",
'exbeforeblank'          => "Silinmədən əvvəlki məzmun: '$1'",
'exblank'                => 'səhifə boş',
'delete-confirm'         => 'Silinən səhifə: "$1"',
'delete-legend'          => 'Sil',
'historywarning'         => "'''Xəbərdarlıq:''' Silinəcək səhifənin tarixçəsində qeyd olunmuş $1 {{PLURAL:$1|redaktə|redaktə}} var:",
'confirmdeletetext'      => 'Bu səhifə və ya fayl bütün tarixçəsi ilə birlikdə birdəfəlik silinəcək. Bunu [[{{MediaWiki:Policy-url}}|qaydalara]] uyğun etdiyinizi və əməliyyatın nəticələrini başa düşdüyünüzü təsdiq edin.',
'actioncomplete'         => 'Fəaliyyət tamamlandı',
'actionfailed'           => 'Əməliyyat yerinə yetirilmədi',
'deletedtext'            => '"<nowiki>$1</nowiki>" silindi.
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
'rollback'         => 'Əvvəlki versiya',
'rollbacklink'     => 'əvvəlki halına qaytar',
'cantrollback'     => 'Redaktə geri qaytarıla bilməz; axırıncı redaktə səhifədə olan yeganə fəaliyyətdir.',
'revertpage'       => '[[Special:Contributions/$2|$2]] ([[User talk:$2|Müzakirə]]) tərəfindən edilmiş dəyişikliklər [[User:$1|$1]] tərəfindən edilmiş dəyişikliklərə qaytarıldı.',
'rollback-success' => '$1 tərəfindən edilmiş redaktələr geri qaytarıldı; $2 tərəfindən yaradılmış son versiya bərpa olundu.',

# Protect
'protectlogpage'              => 'Mühafizə etmə qeydləri',
'protectedarticle'            => 'mühafizə edildi "[[$1]]"',
'modifiedarticleprotection'   => '"[[$1]]" səhifəsi üçün qorunma səviyyəsi dəyişildi',
'unprotectedarticle'          => 'mühafizə kənarlaşdırdı "[[$1]]"',
'protect-title'               => '"$1" üçün mühafizə səviyyəsinin dəyişdirilməsi',
'prot_1movedto2'              => '[[$1]] adı dəyişildi. Yeni adı: [[$2]]',
'protect-legend'              => 'Qorumayı təsdiq et',
'protectcomment'              => 'Səbəb:',
'protectexpiry'               => 'Vaxtı bitib',
'protect_expiry_invalid'      => 'Bitmə vaxtı səhvdir',
'protect_expiry_old'          => 'Bitmə vaxtı keçmişdir.',
'protect-text'                => "Siz '''<nowiki>$1</nowiki>''' səhifəsinin mühafizə səviyyəsini görə və dəyişə bilərsiniz.",
'protect-locked-blocked'      => "Səhifənin bloklu olduğu müddətdə siz mühafizə səviyyəsini dəyişə bilməzsiniz.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-locked-dblock'       => "Verilənlər bazası kilidli olduğu üçün mühafizə səviyyəsi dəyişilə bilməz.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-locked-access'       => "Sizin hesabınızın mühafizə səviyyəsini dəyişməyə ixtiyarı yoxdur.
'''$1''' səhifəsində hal-hazırda edə biləcəyiniz əməliyyatlar bunlardır:",
'protect-cascadeon'           => 'Bu səhifə mühafizəlidir, çünki bu səhifə {{PLURAL:$1|başqa bir|başqa bir}} səhifədən kaskad mühafizə edilmişdir. Siz bu səhifənin mühafizə səviyyəsini dəyişdirə bilərsiniz, bu kaskad mühafizəyə təsir etməyəcək.',
'protect-level-autoconfirmed' => 'Yeni və anonim istifadəçiləri blok et',
'protect-level-sysop'         => 'Yalnız idarəçilər',
'protect-summary-cascade'     => 'kaskad mühafizə',
'protect-expiring'            => '$1 (UTC)- tarixində vaxtı bitir',
'protect-cascade'             => 'Kaskad mühafizəsi - bu səhifəyə daxil bütün səhifələri qoru',
'protect-expiry-options'      => '1 saat:1 hour,1 gün:1 day,1 həftə:1 week,2 həftə:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 il:1 year,sonsuz:infinite',
'restriction-type'            => 'Hüquqlar:',
'restriction-level'           => 'Məhdudiyyət dərəcəsi:',
'pagesize'                    => '(baytlar)',

# Restrictions (nouns)
'restriction-move' => 'Adını dəyiş',

# Restriction levels
'restriction-level-sysop' => 'tam mühafizə',

# Undelete
'undelete'               => 'Silinmiş səhifələri göstər',
'undeletepage'           => 'Silinmiş səhifələri göstər və ya bərpa et',
'viewdeletedpage'        => 'Silinmiş səhifələri göstər',
'undeletebtn'            => 'Bərpa et',
'undeletelink'           => 'bax/bərpa et',
'undeletecomment'        => 'Səbəb:',
'undeletedarticle'       => '"[[$1]]" məqaləsi bərpa edilmişdir',
'cannotundelete'         => 'Silməni ləğv etmə yetinə yetirilə bilmir; başqa birisi daha əvvəl səhifənin silinməsini ləğv etmiş ola bilər.',
'undeletedpage'          => "'''$1 bərpa edildi'''

Məqalələrin bərpa edilməsi və silinməsi haqqında son dəyişiklikləri nəzərdən keçirmək üçün [[Special:Log/delete|silmə qeydlərinə]] baxın.",
'undelete-header'        => 'Son silinmiş səhifələrə baxmaq üçün [[Special:Log/delete|silmə qeydlərinə]] bax.',
'undelete-search-box'    => 'Silinmiş səhifələri axtar.',
'undelete-search-submit' => 'Axtar',

# Namespace form on various pages
'namespace'      => 'Adlar fəzası:',
'invert'         => 'Seçilən xaricindəkiləri',
'blanknamespace' => '(Ana)',

# Contributions
'contributions'       => 'İstifadəçi köməkləri',
'contributions-title' => '$1 istifadəçi fəaliyyətləri',
'mycontris'           => 'Köməklərim',
'contribsub2'         => '$1 ($2)',
'nocontribs'          => 'Bu kriteriyaya uyğun redaktələr tapılmadı',
'uctop'               => '(son)',
'month'               => 'Ay',
'year'                => 'Axtarışa bu tarixdən etibarən başla:',

'sp-contributions-newbies'     => 'Ancaq yeni istifadəçilərin fəaliyyətlərini göstər',
'sp-contributions-newbies-sub' => 'Yeni istifadəçilər üçün',
'sp-contributions-blocklog'    => 'Bloklama qeydləri',
'sp-contributions-talk'        => 'Müzakirə',
'sp-contributions-userrights'  => 'istifadəçi hüquqları idarəsi',
'sp-contributions-search'      => 'Fəaliyyətləri axtar',
'sp-contributions-username'    => 'İP Ünvanı və ya istifadəçi adı:',
'sp-contributions-submit'      => 'Axtar',

# What links here
'whatlinkshere'            => 'Bu səhifəyə bağlantılar',
'whatlinkshere-title'      => '"$1" məqaləsinə keçid verən səhifələr',
'whatlinkshere-page'       => 'Səhifə:',
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

# Block/unblock
'blockip'                     => 'İstifadəçini blokla',
'blockip-title'               => 'İstifadəçini blokla',
'blockip-legend'              => 'İstifadəçinin bloklanması',
'ipaddress'                   => 'IP ünvanı',
'ipadressorusername'          => 'IP ünvanı və ya istifadəçi adı',
'ipbexpiry'                   => 'Bitmə müddəti:',
'ipbreason'                   => 'Səbəb:',
'ipbanononly'                 => 'Yalnız anonim istifadəçiləri blokla',
'ipbcreateaccount'            => 'Hesab açmanı məhdudlaşdır',
'ipbsubmit'                   => 'Bu istifadəçini blokla',
'ipbother'                    => 'Başqa vaxt',
'ipboptions'                  => '2 saat:2 hours,1 gün:1 day,3 gün:3 days,1 həftə:1 week,2 həftə:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 il:1 year,sonsuz:infinite',
'ipbotheroption'              => 'başqa',
'ipbotherreason'              => 'Başqa/əlavə səbəb:',
'ipbwatchuser'                => 'Bu istifadəçinin müzakirə və istifadəçi səhifəsini izlə',
'badipaddress'                => 'Yanlış IP',
'blockipsuccesssub'           => 'bloklandi',
'blockipsuccesstext'          => '[[Special:Contributions/$1| $1]]bloklanıb. <br />See[[Special:IPBlockList|IP blok siyahisi]] bloklanmış IP lər.',
'ipblocklist'                 => 'Bloklanmış İP ünvanları və istifadəçilər',
'ipblocklist-submit'          => 'Axtar',
'blocklistline'               => '$1, $2 bloklandı $3 ($4)',
'infiniteblock'               => 'qeyri-müəyyən müddətə',
'expiringblock'               => 'son tarix $1 saat $2',
'anononlyblock'               => 'yalnız anonim istifadəçi',
'createaccountblock'          => 'Yeni hesab yaratma bloklanıb',
'blocklink'                   => 'blokla',
'unblocklink'                 => 'bloklamanı kənarlaşdır',
'change-blocklink'            => 'bloklamanı dəyişdir',
'contribslink'                => 'Köməklər',
'autoblocker'                 => 'Avtomatik olaraq bloklanmısınız. Çünki, qısa müddət əvvəl sizin IP-ünvanınız "[[User:$1|$1]]" tərəfindən istifadə edilmişdir.
$1 adlı istifadəçinin bloklanma səbəbi: "$2"',
'blocklogpage'                => 'Bloklama qeydləri',
'blocklogentry'               => 'tərəfindən [[$1]] bloklandı, blok müddəti: $2 $3',
'blocklogtext'                => 'İstifadəçilərin bloklanması və blokun götürülməsi siyahısı.
Avtomatik bloklanmış IP-ünvanlar burada göstərilmir.
Hal-hazırkı [[Special:IPBlockList|qadağaların və bloklamaların siyahısı]]na bax.',
'unblocklogentry'             => 'tərəfindən $1 üzərindəki blok götürüldü',
'block-log-flags-anononly'    => 'yalnız qeydiyyatsız istifadəçilər',
'block-log-flags-nocreate'    => 'hesab yaradılması qeyri-mümkündür',
'block-log-flags-noautoblock' => 'avtobloklama qeyri-mümkündür',
'ipb_expiry_invalid'          => 'Bitmə vaxtı səhvdir',
'ipb_already_blocked'         => '"$1" artıq bloklanıb',
'ipb-needreblock'             => '== Artıq bloklanıb ==
$1 artıq bloklanıb.
Bloklama şərtlərini dəyişmək istəyirsiniz?',
'proxyblocker'                => 'Proksi bloklayıcı',

# Move page
'move-page-legend'         => 'Səhifənin adını dəyiş',
'movepagetext'             => "Aşağıdakı formadan istifədə etmə səhifənin adını, bütün tarixçəsini də köçürməklə yeni başlığa dəyişəcək.
Əvvəlki başlıq yeni başlığa istiqamətləndirmə səhifəsinə çevriləcək.
Köhnə səhifəyə keçidləri avtomatik olaraq dəyişə bilərsiniz.
Bu seçimi etmədiyiniz halda, [[Special:DoubleRedirects|təkrarlanan]] və ya [[Special:BrokenRedirects|qırıq istiqamətləndirmələri]] yoxlamağı yaddan çıxarmayın.
Keçidlərin lazımi yerə istiqamətləndirilməsini təmin etmək sizin məsuliyyətinizdədir.

Nəzərə alın ki, hədəf başlığı altında bir səhifə mövcuddursa yerdəyişmə '''baş tutmayacaq'''. Buna həmin səhifənin boş olması və ya istiqamətləndirmə səhifəsi olması və keçmişdə redaktə edilməməsi halları istisnadır. Bu o deməkdir ki, səhvən adını dəyişdiyiniz səhifələri geri qaytara bilər, bununla yanaşı artıq mövcud olan səhifənin üzərinə başqa səhifə yaza bilməzsiniz.

'''XƏBƏRDARLIQ!'''
Bu yerdəyişmə populiyar səhifə üçün əsaslı və gözlənilməz ola bilər, ona görə də bu dəyişikliyi yerinə yetirməzdən əvvəl, bunun mümkün nəticələrini başa düşdüyünüzdən əmin olun.",
'movearticle'              => 'Səhifənin adını dəyişdir',
'newtitle'                 => 'Yeni başlıq',
'move-watch'               => 'Bu səhifəni izlə',
'movepagebtn'              => 'Səhifənin adını dəyiş',
'pagemovedsub'             => 'Yerdəyişmə edilmişdir',
'movepage-moved'           => '\'\'\'"$1" səhifəsi "$2" səhifəsinə yerləşdirilmişdir\'\'\'',
'movedto'                  => 'dəyişdi',
'movetalk'                 => 'Bu səhifənin müzakirə səhifəsinin de adını dəyişdir.',
'1movedto2'                => '[[$1]] adı dəyişildi. Yeni adı: [[$2]]',
'1movedto2_redir'          => '[[$1]] adı və məsiri dəyişildi : [[$2]]',
'movelogpage'              => 'Yerdəyişmə qeydləri',
'movereason'               => 'Səbəb:',
'revertmove'               => 'Əvvəlki vəziyyətinə',
'delete_and_move'          => 'Sil və apar',
'delete_and_move_text'     => '==Hazırki məqalənin silinməsi lazımdır==

"[[$1]]" məqaləsi mövcuddur. Bu dəyişikliyin yerinə yetirilə bilməsi üçün həmin məqalənin silinməsini istəyirsinizmi?',
'delete_and_move_confirm'  => 'Bəli, səhifəni sil',
'delete_and_move_reason'   => 'Ad dəyişməyə yer açmaq üçün silinmişdir',
'selfmove'                 => 'Səhifənin hazırkı adı ilə dəyişmək istənilən ad eynidir. Bu əməliyyat yerinə yetirilə bilməz.',
'protectedpagemovewarning' => "'''Xəbərdarlıq:''' Bu səhifə mühafizə edildiyi üçün onun adını yalnız idarəçilər dəyişə bilərlər.",

# Export
'export'            => 'Səhifələri ixrac et',
'exportcuronly'     => 'Bütün tarixçəni deyil, yalnız hal-hazırkı versiyanı daxil et',
'export-submit'     => 'İxrac',
'export-addcattext' => 'Səhifələri bu kateqoriyadan əlavə et:',
'export-addcat'     => 'Əlavə et',
'export-addnstext'  => 'Səhifələri adlar fəzasından əlavə et:',
'export-addns'      => 'Əlavə et',

# Namespace 8 related
'allmessages'                 => "Sistem mə'lumatları",
'allmessagesname'             => 'Ad',
'allmessagesdefault'          => 'İlkin mətn',
'allmessagescurrent'          => 'İndiki mətn',
'allmessagestext'             => 'Bu MediaWiki-də olan sistem mesajlarının siyahısıdır. Əgər MediaWiki-ni lokallaşdırmaq işində kömək etmək isəyirsənsə, lütfən [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] və [http://translatewiki.net translatewiki.net]-ə baş çək.',
'allmessages-filter-all'      => 'Hamısı',
'allmessages-filter-modified' => 'Dəyişdirilmiş',
'allmessages-language'        => 'Dil:',
'allmessages-filter-submit'   => 'Keç',

# Thumbnails
'thumbnail-more'          => 'Böyüt',
'djvu_page_error'         => 'DjVu səhifəsi əlçatmazdır',
'djvu_no_xml'             => 'DjVu üçün XML faylı almaq mümkün deyil.',
'thumbnail_image-missing' => 'Belə görünür ki, $1 faylı yoxdur',

# Special:Import
'importnotext'             => 'Boş və ya mətn yoxdur',
'import-token-mismatch'    => 'Seans məlumatlarının itirilməsi. Lütfən, yenidən cəhd edin.',
'import-invalid-interwiki' => 'Göstərilən vikidən köçürmək mümkün deyil',

# Import log
'importlogpagetext' => 'Səhifələrin idarəçilər tərəfindən digər vikilərdən dəyişiklik tarixçəsi ilə birlikdə köçürülməsi',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'İstifadəçi səhifəniz',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Danışıq səhifəm',
'tooltip-pt-anontalk'             => 'Bu IP ünvanindan redaktə olunmuş danışıqlar',
'tooltip-pt-preferences'          => 'Mənim nizamlamalarım',
'tooltip-pt-watchlist'            => 'İzləməyə götürdüyüm səhifələr',
'tooltip-pt-mycontris'            => 'Etdiyim dəyişikliklərin siyahısı',
'tooltip-pt-login'                => 'Daxil olmanız tövsiyə olunur, amma tələb olunmur.',
'tooltip-pt-anonlogin'            => 'Daxil olmanız tövsiyə olunur, amma tələb olunmur.',
'tooltip-pt-logout'               => 'Sistemdən çıx',
'tooltip-ca-talk'                 => 'Məqalə həqqində müzakirə edib, nəzərivi bildir',
'tooltip-ca-edit'                 => 'Bu səhifəni redaktə edə bilərsiniz. Lütfən əvvəlcə sınaq gostərişi edin.',
'tooltip-ca-addsection'           => 'Yeni bölmə yarat',
'tooltip-ca-viewsource'           => 'Bu səhifə qorunma altındadır. Mənbəsinə baxa bilərsiniz.',
'tooltip-ca-history'              => 'Bu səhifənin geçmiş nüsxələri.',
'tooltip-ca-protect'              => 'Bu səhifəni qoru',
'tooltip-ca-unprotect'            => 'Bu səhifənin mühafizəsini kənarlaşdır',
'tooltip-ca-delete'               => 'Bu səhifəni sil',
'tooltip-ca-undelete'             => 'Bu səhifəni silinmədən oncəki halına qaytarın',
'tooltip-ca-move'                 => 'Bu səhifənin adını dəyiş',
'tooltip-ca-watch'                => 'Bu səhifəni izlə',
'tooltip-ca-unwatch'              => 'Bu səhifənin izlənmasini bitir',
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
'tooltip-t-whatlinkshere'         => 'Wikidə bu məqaləyə bağlantilar',
'tooltip-t-recentchangeslinked'   => 'Bu məqaləyə ayid başqa səhifələrdə yeni dəyişikliklər',
'tooltip-feed-rss'                => 'Bu səhifə üçün RSS yayımı',
'tooltip-feed-atom'               => 'Bu səhifə üçün Atom yayımı',
'tooltip-t-contributions'         => 'Bu üzvin redaktə etmiş məqalələr siyahəsi',
'tooltip-t-emailuser'             => 'Bu istifadəçiyə bir e-məktub yolla',
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

# Metadata
'nodublincore'      => 'Bu server üçün Dublin Core RDF metaməlumatları qadağandır.',
'nocreativecommons' => 'Bu server üçün Creative Commons RDF metaməlumatları qadağandır.',
'notacceptable'     => 'Viki-server məlumatları brauzerinizin oxuya biləcəyi formatda təqdim edə bilmir.',

# Attribution
'anonymous'   => '{{SITENAME}} saytının anonim {{PLURAL:$1|istifadəçisi|istifadəçiləri}}',
'siteuser'    => '{{SITENAME}} istifadəçisi $1',
'anonuser'    => '{{SITENAME}} anonim istifadəçisi $1',
'creditspage' => 'Səhifə kreditleri',

# Spam protection
'spamprotectiontitle' => 'Spam qoruma süzgəci',

# Math options
'mw_math_png'    => 'Həmişə PNG formatında göstər',
'mw_math_simple' => 'Sadə formullarda HTML, digərlərində PNG',
'mw_math_html'   => 'Mümkünsə HTML, digər hallarda PNG',
'mw_math_source' => 'TeX kimi saxla (mətn brouzerləri üçün)',
'mw_math_modern' => 'Müasir brouzerlər üçün məsləhətdir',
'mw_math_mathml' => 'Mümkünsə MathML (sınaq)',

# Math errors
'math_unknown_error'    => 'bilinməyən xəta',
'math_unknown_function' => 'bilinməyən funksiya',
'math_syntax_error'     => 'sintaksis xətası',

# Patrol log
'patrol-log-auto' => '(avtomatik)',

# Image deletion
'deletedrevision'       => 'Köhnə versiyaları silindi $1.',
'filedeleteerror-short' => 'Fayl silinərkən xəta: $1',
'filedeleteerror-long'  => 'Fayl silinərkən üzə çıxan xətalar:

$1',

# Browsing diffs
'previousdiff' => '← Əvvəlki redaktə',
'nextdiff'     => 'Sonrakı redaktə →',

# Media information
'imagemaxsize'   => "Şəkilin maksimal tutumu:<br />''(faylın təsviri səhifələri üçün)''",
'thumbsize'      => 'Kiçik ölçü:',
'file-info-size' => '($1 × $2 piksel, fayl həcmi: $3, MIME növü: $4)',
'file-nohires'   => '<small>Daha dəqiq versiyası yoxdur.</small>',
'svg-long-desc'  => '(SVG fayl, nominal olaraq $1 × $2 piksel, faylın ölçüsü: $3)',
'show-big-image' => 'Daha yüksək keyfiyyətli şəkil',

# Special:NewFiles
'newimages'    => 'Yeni faylların siyahısı',
'showhidebots' => '($1 bot redaktə)',
'ilsubmit'     => 'Axtar',
'bydate'       => 'tarixe görə',

# Bad image list
'bad_image_list' => 'Formatı bu şəkildə olmalıdır:

Yalnız siyahı bəndləri (* işarəsi ilə başlayan sətirlər) nəzərə alınır.
Sətirdəki ilk keçid əlavə olunması qadağan olunmuş şəkilə keçid olmalıdır.
Həmin sətirdəki sonrakı keçidlər istisnalar kimi qəbul edilir, yəni şəklin əlavə oluna biləcəyi məqalələr. Məsələn, fayl məqalədə sətrin içində görünə bilər.',

# Metadata
'metadata'        => 'Metaməlumatlar',
'metadata-expand' => 'Ətraflı məlumatları göstər',

# EXIF tags
'exif-artist'              => 'Müəllif',
'exif-exposuretime-format' => '$1 saniyə ($2)',
'exif-aperturevalue'       => 'Obyektiv gözü',
'exif-brightnessvalue'     => 'Parlaqlıq',
'exif-filesource'          => 'Fayl mənbəsi',
'exif-contrast'            => 'Kontrast',
'exif-gpsaltitude'         => 'Yüksəklik',

'exif-componentsconfiguration-0' => 'mövcud deyil',

'exif-exposureprogram-1' => 'Əl ilə',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'bütün',
'imagelistall'     => 'bütün',
'watchlistall2'    => 'hamısını',
'namespacesall'    => 'bütün',
'monthsall'        => 'hamısı',

# E-mail address confirmation
'confirmemail'           => 'E-məktubunu təsdiq et',
'confirmemail_send'      => 'Təsdiq kodu göndər',
'confirmemail_sent'      => 'Təsdiq e-məktubu göndərildi.',
'confirmemail_invalid'   => 'Səhv təsdiqləmə kodu. Kodun vaxtı keçmiş ola bilər.',
'confirmemail_needlogin' => 'E-məktub ünvanınızın təsdiqlənməsi üçün $1 lazımdır.',
'confirmemail_success'   => 'E-məktub ünvanınız indi təsdiq edildi.',
'confirmemail_loggedin'  => 'E-məktubunuz indi təsdiq edildi.',
'confirmemail_subject'   => '{{SITENAME}} e-məktub təsdiq etme',

# Trackbacks
'trackbackremove' => '([$1 Sil])',

# Delete conflict
'deletedwhileediting' => "'''Diqqət''': Bu səhifə siz redaktə etməyə başladıqdan sonra silinmişdir!",

# action=purge
'confirm-purge-top' => 'Bu səhifə keşdən (cache) silinsin?',

# Multipage image navigation
'imgmultipageprev' => '&larr; əvvəlki səhifə',
'imgmultipagenext' => 'sonrakı səhifə &rarr;',

# Table pager
'table_pager_next'         => 'Sonrakı səhifə',
'table_pager_prev'         => 'Əvvəlki səhifə',
'table_pager_first'        => 'İlk səhifə',
'table_pager_last'         => 'Son səhifə',
'table_pager_limit_submit' => 'Gətir',
'table_pager_empty'        => 'Nəticə yoxdur',

# Auto-summaries
'autosumm-blank'   => 'Səhifənin məzmunu silindi',
'autosumm-replace' => "Səhifənin məzmunu '$1' yazısı ilə dəyişdirildi",
'autoredircomment' => '[[$1]] səhifəsinə istiqamətləndirilir',
'autosumm-new'     => "Səhifəni '$1' ilə yarat",

# Live preview
'livepreview-loading' => 'Yüklənir…',

# Watchlist editor
'watchlistedit-normal-title' => 'İzlədiyim səhifələri redaktə et',
'watchlistedit-raw-titles'   => 'Başlıqlar:',

# Watchlist editing tools
'watchlisttools-edit' => 'İzlədiyim səhifələri göstər və redaktə et',

# Special:Version
'version'                  => 'Versiya',
'version-software-version' => 'Versiya',

# Special:FilePath
'filepath' => 'Fayl yolu',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Dublikat fayl axtarışı',

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

# Special:Tags
'tags-edit' => 'redaktə',

# HTML forms
'htmlform-reset'               => 'Dəyişiklikləri qaytar',
'htmlform-selectorother-other' => 'Digər',

);
