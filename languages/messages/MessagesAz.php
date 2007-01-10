<?php
/** Azerbaijani (Azərbaycan)
  *
  * @package MediaWiki
  * @subpackage Language
  */

$namespaceNames = array(
	NS_MEDIA            => 'Mediya',
	NS_SPECIAL          => 'Xüsusi',
	NS_MAIN             => '',
	NS_TALK             => 'Müzakirə',
	NS_USER             => 'İstifadəçi',
	NS_USER_TALK        => 'İstifadəçi_müzakirəsi',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_müzakirəsi',
	NS_IMAGE            => 'Şəkil',
	NS_IMAGE_TALK       => 'Şəkil_müzakirəsi',
	NS_MEDIAWIKI        => 'MediyaViki',
	NS_MEDIAWIKI_TALK   => 'MediyaViki_müzakirəsi',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_müzakirəsi',
	NS_HELP             => 'Kömək',
	NS_HELP_TALK        => 'Kömək_müzakirəsi',
	NS_CATEGORY         => 'Kateqoriya',
	NS_CATEGORY_TALK    => 'Kateqoriya_müzakirəsi',
);

$separatorTransformTable = array(',' => '.', '.' => ',' );


$messages = array(

# User preference toggles  # Kullanıcı seçenekleri
'tog-fancysig' => 'Xam imza (daxili bağlantı yaratmaz)',
'tog-hideminor' => 'Son dəyişikliklərdə kiçik redaktələri gizlə',
'tog-rememberpassword' => 'Parolu xatırla',
'tog-showtoc' => 'Mündərəcat siyhəsin göstər (3 başliqdan artix ola səhifələrdə)',

'underline-always' => 'Həmişə',
'underline-default' => 'Browser default',
'underline-never' => 'Həç zaman',

'skinpreview' => '(Sınaq göstərişi)',

# dates # zaman bilgileri
'monday' => 'Bazar ertǝsi',
'tuesday' => 'Çǝrşenbǝ axşamı',
'wednesday' => 'Çǝrşenbǝ',
'thursday' => 'Cümǝ axşamı',
'friday' => 'Cümǝ',
'saturday' => 'Şǝnbǝ',
'sunday' => 'Bazar',
'january' => 'Yanvar',
'february' => 'Fevral',
'march' => 'Mart',
'april' => 'Aprel',
'may_long' => 'May',
'june' => 'Iyun',
'july' => 'Iyul',
'august' => 'Avqust',
'september' => 'Sentyabr',
'october' => 'Oktyabr',
'november' => 'Noyabr',
'december' => 'Dekabr',
'jan' => 'Yanvar',
'feb' => 'Fevral',
'mar' => 'Mart',
'apr' => 'Aprel',
'may' => 'May',
'jun' => 'Iyun',
'jul' => 'Iyul',
'aug' => 'Avqust',
'sep' => 'Sentyabr',
'oct' => 'Oktyabr',
'nov' => 'Noyabr',
'dec' => 'Dekabr',
# Bits of text used by many pages: # Birçok sayfada geçen metinler
#
'categories' => 'Kateqoriyalar',
'pagecategories' => 'Kateqoriyalar',
'category_header' => '"$1" kategoriyasındaki məqalələr',
'subcategories' => 'Alt kategoriyalar',

'mainpage' => 'Ana Səhifə',

'portal' => 'Kənd Meydani',
'portal-url' => 'Project:Kənd Meydani',
'about' => 'İzah',
'aboutpage' => 'Project:İzah',
'aboutsite' => '{{SITENAME}} haqqında',
'article' => 'Mündəricat Səhifəsi',
'help' => 'Kömək',
'helppage' => 'Help:Mündəricət',
'bugreports' => 'Xəta mə\'ruzəsı',
'bugreportspage' => 'Project:Xəta_mə\'ruzəsı',
'sitesupport' => 'Bağışlar',
'faq' => 'FAQ',
'faqpage' => 'Project:FAQ',
'edithelp' => 'Redaktə kömək',
'newwindow' => '(Yeni pəncərədə açılır)',
'edithelppage' => 'Help:Redaktə',
'cancel' => 'Ləğv et',
'qbfind' => 'Tap',
'qbbrowse' => 'Browse',
'qbedit' => 'Redaktə',
'qbpageoptions' => 'Bu səhifə',
'qbpageinfo' => 'Məzmun',
'qbmyoptions' => 'Mənim səhifələrim',
'qbspecialpages' => 'Xüsusi səhifələr',
'moredotdotdot' => 'More...',
'mypage' => 'Mənim səhifəm',
'mytalk' => 'Danişiqlarım',
'navigation' => 'Rəhbər',

# Metadata in edit box

'currentevents' => 'Güncəl hadisələr',
'currentevents-url' => 'Project:Güncəl Hadisələr',
'disclaimers' => 'İmtina etmə',
'errorpagetitle' => 'Xəta',
'returnto' => '$1 səhifəsinə qayıt.',
'go' => 'Gətir',
'searcharticle' => 'Gətir',
'search' => 'Axtar',
'searchbutton' => 'Axtar',
'history' => 'Səhifənin tarixçəsi',
'history_short' => 'Tarixçə',
'printableversion' => 'Çap variantı',
'permalink' => 'Daimi bağlantı',
'edit' => 'Redaktə',
'editthispage' => 'Bu səhifəni redaktə edin',
'delete' => 'Sil',
'deletethispage' => 'Bu səhifəni sil',
'protect' => 'Qoru',
'protectthispage' => 'Bu səhifəni qoru',
'unprotect' => 'Qorumanı bitir',
'unprotectthispage' => 'Bu səhifəni qoruma',
'newpage' => 'Yeni səhifə',
'talkpage' => 'Bu səhifəyi müzakirə et',
'specialpage' => 'Xüsusi səhifə',
'postcomment' => 'Post a comment',
'articlepage' => 'Məqaləyə get',
'talk' => 'Müzakirə',
'toolbox' => 'Alətlər Sandıqı',
'userpage' => 'İstifadəçi səhifəsini göstər',
'projectpage' => 'Layihə səhifəsini göstər',
'viewtalkpage' => 'View discussion',
'otherlanguages' => 'Başqa dillərdə',
'redirectedfrom' => '($1 səhifəsindən istiqamətləndirilmişdir)',
'redirectpagesub' => 'İstiqamətləndirmə səhifəsi',
'lastmodifiedat' => 'Bu səhifə sonuncu dəfə $2, $1 tarixində redaktə edilib.',
'copyright' => 'Bu məzmun $1 əhatəsindədir.',

'nbytes' => '$1 bayt',
'youhavenewmessages' => 'Hal-hazırda $1 var. ($2)',
'newmessageslink' => 'yeni mesajlar!',
'editsection' => 'redaktə',
'editold' => 'redaktə',
'toc' => 'Mündəricat',
'showtoc' => 'göstər',
'hidetoc' => 'gizlə',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-main' => 'Məqalə',
'nstab-user' => 'İstifadəçi səhifəsi',
'nstab-special' => 'Xüsusi',
'nstab-project' => 'Layihə səhifəsi',
'nstab-image' => 'Fayl',
'nstab-mediawiki' => 'Mə\'lumat',
'nstab-template' => 'Şablon',
'nstab-help' => 'Kömək',
'nstab-category' => 'Kateqoriya',

# General errors
#
'error' => 'Xəta',
'databaseerror' => 'Verilənlər bazası xətası',
'readonly' => 'Verilənlər bazası kilidli',
'internalerror' => 'Daxili xəta',
'badtitle' => 'Yanlış başlıq',
'viewsource' => 'Mənbə göstər',

# Login and logout pages
#
'logouttext' => '<strong>Sistemdən çıxdınız.</strong><br /> Vikipediyanı anonim olaraq istifadə etməyə davam edəbilər, və ya eyni yaxud başqa istifadəçi adı ilə yenidən daxil ola bilərsiniz. Diqqətinizə çatdırırıq ki, ön yaddaşı (browser cache) təmizləyənə qədər bə\'zi səhifələr sistemdən çıxdığınız halda da göstərilə bilər.',
'logouttitle' => 'İstifadəçi çıxış',
'welcomecreation' => '== $1, xoş gəlmişsiniz! == Hesabınız yaradıldı. {{SITENAME}} nizamlamalarını dəyişdirməyi unutmayın.',
'loginpagetitle' => 'İstifadəçi Giriş Səhifəsi',
'yourname' => 'İstifadəçi adı',
'yourpassword' => 'Parol',
'yourpasswordagain' => 'Parolu təkrar yazın',
'remembermypassword' => 'Məni xatırla',
'alreadyloggedin' => '<strong>User $1, Siz onsuz da daxil olmusunuz!</strong><br />',

'login' => 'Daxil ol',
'loginprompt' => '{{SITENAME}}-ya daxil olmaq üçün "veb kökələrinin" (cookies) istifadəsinə icazə verilməlidir.',
'logout' => 'Çıxış',
'userlogin' => 'Daxil ol və ya istifadəçi yarat',
'userlogout' => 'Çıxış',
'nologin' => 'İstifadəçi adınız yoxdursa, $1.',
'nologinlink' => 'hesab açın',
'createaccount' => 'Yeni hesab aç',
'gotaccount' => 'Giriş hesabınız varsa $1.',
'gotaccountlink' => 'daxil olun',
'createaccountmail' => 'e-məktub ilə',
'username' => 'İstifadəçi adı:',
'uid' => 'İstifadəçi ID:',
'youremail' => 'E-məktub *',
'yourlanguage' => 'Dil:',
'yournick' => 'Ləqəb:',
'yourrealname' => 'Həqiqi adınız *',
'email' => 'E-məktub',
'prefs-help-email' => '* E-məktub (qeyri-məcburi): Enables others to contact you through your user or user_talk page without the need of revealing your identity.',
'loginsuccess' => '\'\'\'"$1" olaraq {{SITENAME}}-ya daxil oldunuz.\'\'\'',
'loginsuccesstitle' => 'Daxil olundu',
'wrongpassword' => 'Yanlış parol. Təkrar yaz.',
'wrongpasswordempty' => 'Parol boş. Təkrar yaz.',
'mailmypassword' => 'Parolu unutmuşam',
'noemail' => '"$1" adlı istifadəçi e-məktub ünvanı qeyd edmemişdir.',
'acct_creation_throttle_hit' => 'Siz artıq $1 hesab açmısınız. Daha çox hesab açabilmərsiniz.',
'emailauthenticated' => 'E-məktub ünvanınız $1 tarixində təsdiq edilib.',
'emailnotauthenticated' => 'Your e-mail address is not yet authenticated. No e-mail will be sent for any of the following features.',
'emailconfirmlink' => 'E-məktubunu təsdiq et',
'invalidemailaddress' => 'E-məktub ünvanını qeyri düzgün formatda olduğu üçün qəbul edə bilmirik. Xahiş edirik düzgün formatlı ünvan daxil edin və ya bu sahəni boş qoyun.',

# Edit page toolbar

'bold_sample' => 'Qalın mətn',
'bold_tip' => 'Qalın mətn',
'italic_sample' => 'Kursiv mətn',
'italic_tip' => 'Kursiv mətn',
'link_sample' => 'Bağlantı başlığı',
'link_tip' => 'Daxili bağlantı',
'extlink_sample' => 'http://www.misal.com başlıq',
'extlink_tip' => 'Xarici səhifə (http:// ekini unutma)',
'headline_sample' => 'Başlıq metni',
'headline_tip' => '2. səviyyə başlıq',
'image_sample' => 'Misal.jpg',
'media_sample' => 'Misal.ogg',
'hr_tip' => 'Horizontal cizgi',

# Edit pages
#
'summary' => 'Qısa məzmun',
'subject' => 'Mövzu/başlıq',
'minoredit' => 'Kiçik redaktə',
'watchthis' => 'Bu səhifəni izlə',
'savearticle' => 'Səhifəni qeyd et',
'preview' => 'Sınaq göstərişi',
'showpreview' => 'Sınaq göstərişi',
'showdiff' => 'Dəyişiklikləri göstər',
'blockedtitle' => 'İstifadəçi bloklanıb',
'whitelistacctitle' => 'You are not allowed to create an account',
'whitelistedittitle' => 'Redaktə üçün daxil olmalısınız',
'whitelistreadtitle' => 'Oxumaq üçün daxil olmalısınız',
'accmailtext' => '"$1" üçün parol göndərildi bu ünvana : $2.',
'accmailtitle' => 'Parol göndərildi.',
'newarticle' => '(Yeni)',
'newarticletext' => 'Mövcud olmayan səhifəyə olan keçidi izlədiniz. Aşağıdakı sahəyə məzmununu yazaraq bu səhifəni \'\'\'siz\'\'\' yarada bilərsiniz. (əlavə məlumat üçün [[Project:Help|kömək səhifəsinə]] baxın). Əgər bu səhifəyə səhvən gəlmisinizsə sadəcə olaraq brauzerin \'\'\'geri\'\'\' düyməsinə vurun.',
'noarticletext' => 'Hal-hazırda bu səhifə boşdur. Başqa səhifələrdə [[{{ns:special}}:Search/{{PAGENAME}}|bu səhifənin adını axtara]] bilər və ya \'\'\'[{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} səhifəni siz redaktə edəbilərsiniz]\'\'\'.',
'previewnote' => '<strong>Bu yalnız sınaq göstərişidir; dəyişikliklər hal-hazırda qeyd edilmemişdir!</strong>',
'editing' => 'Redaktə $1',
'editinguser' => 'Redaktə $1',
'yourtext' => 'Metniniz',
'yourdiff' => 'Fərqlər',
'templatesused' => 'Bu səhifədə istifadə edilmiş şablonlar:',


# History pages
#
'revhistory' => 'Versiya tarixçəsi',
'currentrev' => 'Hal-hazırkı versiya',
'revisionasof' => '$1 versiyası',
'previousrevision' => '←Əvvəlki versiya',
'nextrevision' => 'Sonrakı versiya→',
'currentrevisionlink' => 'Hal-hazırkı versiyanı göstər',
'cur' => 'hh',
'next' => 'sonrakı',
'last' => 'son',
'orig' => 'orig',
'histfirst' => 'Ən əvvəlki',
'histlast' => 'Ən sonuncu',
'histlegend' => 'Fərqləri seçmə və göstərmə: müqaisə etmək istədiyiniz versiyaların yanındakı radio qutularına işarə qoyun və daxil etmə düyməsinə(enter-a) və ya "müqaisə et" düyməsinə vurun.<br />
Açıqlama: (hh) = hal-hazırkı versiya ilə olan fərqlər,
(son) = əvvəlki versiya ilə olan fərqlər, K = kiçik redaktə.',

# Diffs
#
'difference' => '(Versiyalar arasındakı fərq)',
'lineno' => 'Sətir $1:',
'editcurrent' => 'Bu səhifənin hal-hazırkı versiyanı redaktə et',
'compareselectedversions' => 'Seçilən versiyaları müqaisə et',

# Search results
#
'prevn' => 'əvvəlki $1',
'nextn' => 'sonrakı $1',
'viewprevnext' => 'Göstər ($1) ($2) ($3).',
'powersearch' => 'Axtar',
'blanknamespace' => '(Ana)',

# Preferences page
#
'preferences' => 'Nizamlamalar',
'changepassword' => 'Parol dəyiş',
'math' => 'Riyaziyyat',
'dateformat' => 'Tarix formatı',
'datedefault' => 'Tərcih yox',
'datetime' => 'Tarix və vaxt',
'prefs-misc' => 'Digər tərcihlər',
'prefs-personal' => 'İstifadəçi profili',
'prefs-rc' => 'Son dəyişikliklər',
'saveprefs' => 'Qeyd et',
'resetprefs' => 'Reset',
'oldpassword' => 'Köhne parol:',
'newpassword' => 'Yeni parol:',
'retypenew' => 'Yeni parolu təkrar yazın:',
'textboxsize' => 'Redaktə',
'searchresultshead' => 'Axtar',
'recentchangescount' => 'Son dəyişikliklərdə başlıq sayı:',
'savedprefs' => 'Tərcihlər qeyd edildi.',
'timezonelegend' => 'Saat qurşağı',
'timezonetext' => 'Server ilə vaxt fərqı. (Azərbaycan üçün +04:00)',
'localtime' => 'Məhəlli vaxt',
'timezoneoffset' => 'Vaxt fərqı¹',
'servertime' => 'Server vaxtı',
'allowemail' => 'Digər istifadəçilər mənə e-məktub göndərəbilir',
'defaultns' => 'Search in these namespaces by default:',
'default' => 'default',
'files' => 'Fayllar',

# User levels special page
#

# switching pan
'userrights-lookup-user' => 'İstifadəçi qruplarını idarə et',
'userrights-user-editname' => 'İstifadəçi adınızı yazın:',


# Recent changes
#
'changes' => 'dəyişiklik',
'recentchanges' => 'Son dəyişikliklər',
'recentchangestext' => '\'\'\'Ən son dəyişiklikləri bu səhifədən izləyin.\'\'\'',
'rcnote' => 'Aşağıdakı son <strong>$1</strong> dəyişiklik son <strong>$2</strong> gün ərzində edilmişdir.',
'rcnotefrom' => 'Below are the changes since <b>$2</b> (up to <b>$1</b> shown).',
'rclistfrom' => '$1 vaxtından başlayaraq yeni dəyişiklikləri göstər',
'rclinks' => 'Son $2 gün ərzindəki son $1 dəyişikliyi göstər <br />$3',
'diff' => 'fərq',
'hist' => 'tarixçə',
'hide' => 'gizlət',
'show' => 'göstər',
'minoreditletter' => 'k',
'newpageletter' => 'Y',

# Upload
#
'upload' => 'Qarşıya yüklə',
'uploadbtn' => 'Sənəd yüklə',
'reupload' => 'Təkrar yüklə',
'reuploaddesc' => 'Return to the upload form.',
'uploaderror' => 'Yükləyiş xətası',
'uploadlog' => 'yükleme kaydı',
'filename' => 'Fayl adı',
'fileuploadsummary' => 'İzahat:',
'filestatus' => 'Müəllif statusu',
'filesource' => 'Mənbə',
'copyrightpage' => 'Project:Müəllif',
'copyrightpagename' => '{{SITENAME}} müəllif',
'uploadwarning' => 'Yükləyiş xəbərdarlıqı',
'savefile' => 'Faylı qeyd et',

# Image list
#
'imagelist' => 'Fayl siyahısı',
'showlast' => 'Show last $1 files sorted $2.',
'bydate' => 'tarixe görə',
'byname' => 'ada görə',
'bysize' => 'ölçüye görə',
'imgdesc' => 'desc',
'imglegend' => 'Legend: (desc) = show/edit file description.',
'imghistory' => 'Faylın tarixçəsi',
'deleteimg' => 'sil',
'noimage-linktext' => 'faylı yüklə',

# Mime search
#
'mimesearch' => 'MIME axtar',
'mimetype' => 'MIME type:',

# Unwatchedpages
#
'unwatchedpages' => 'İzlənməyən səhifələr',

# Statistics
#
'statistics' => 'Statistika',
'sitestats' => '{{SITENAME}} statistika',
'sitestatstext' => '<p style="font-size:125%;margin-bottom:0">Wikipedia-da hal-hazırda məqalələrin sayı: \'\'\'$2\'\'\'</p>

Verilənlər bazasında yekun \'\'\'$1\'\'\' səhifə var. Buna müzakirələr, istifadəçi səhifələri, köməklər, wikipedia lahiye səhifələri, xüsusi səhifələr, istiqamətləndirmə səhifələri, boş səhifələr ilə fayllar v əşablonlar daxildir.

There have been a total of \'\'\'$3\'\'\' page views, and \'\'\'$4\'\'\' page edits
since the wiki was setup.
That comes to \'\'\'$5\'\'\' average edits per page, and \'\'\'$6\'\'\' views per edit.

Hal-hazırda [http://meta.wikimedia.org/wiki/Help:Job_queue job queue] sayı: \'\'\'$7\'\'\'.',
'userstats' => 'İstifadəçi statistika',
'userstatstext' => 'Hal-hazırda \'\'\'$1\'\'\' istifadəçi, \'\'\'2\'\'\' (və ya \'\'\'4%\'\'\') tanesi idarəçi. (baxınız $3).',


'doubleredirects' => 'İkiqat istiqamətləndirmələr',
'brokenredirects' => 'Xətalı istiqamətləndirmə',
'brokenredirectstext' => 'The following redirects link to non-existent pages:',

# Miscellaneous special pages
#
'lonelypages' => 'Yetim səhifələr',
'uncategorizedcategories' => 'Kateqoriyasız kateqoriyalar',
'uncategorizedpages' => 'Kateqoriyasız səhifələr',
'unusedcategories' => 'İstifadə edilməmiş kateqoriyalar',
'unusedimages' => 'İstifadə edilməmiş fayllar',
'wantedcategories' => 'Təlabat olunan kateqoriyalar',
'wantedpages' => 'Təlabat olunan səhifələr',
'mostcategories' => 'Kateqoriyası ən çox olan məqalələr',
'mostrevisions' => 'Ən çox nəzərdən keçirilmiş (versiyalı) məqalələr',
'nlinks' => '$1 bağlantı',
'allpages' => 'Bütün səhifələr',
'randompage' => 'İxtiyari səhifə',
'shortpages' => 'Qısa səhifələr',
'longpages' => 'Uzun səhifələr',
'listusers' => 'İstifadəçi siyahı',
'specialpages' => 'Xüsusi səhifələr',
'spheading' => 'İstifadəçilər üçün xüsusi səhifələr',
'restrictedpheading' => 'İdarəçilər üçün xüsusi səhifələr',
'recentchangeslinked' => 'Əlaqəli redaktələr',
'newpages' => 'Yeni səhifələr',
'ancientpages' => 'Ən köhnə səhifələr',
'move' => 'Adını dəyişdir',
'movethispage' => 'Bu səhifənin adını dəyiş',
'booksources' => 'Kitab mənbələri',
'categoriespagetext' => 'Wikide aşağıdaki kateqoriyalar var.',
'version' => 'Versiya',
'log' => 'Loglar',
'alllogstext' => 'Qarşıya yükləmə, silmə, qoruma, bloklama ve sistem operatoru loqlarının birləşdirilmiş göstərməsi. Log növü, istifadəçi adı veya tə\'sir edilən səhifəni seçib görüntünü kiçildə bilərsiniz.',

# Special:Allpages
'nextpage' => 'Sonrakı səhifə ($1)',
'allpagesfrom' => 'Bu mövqedən başlayan səhifeleri göstər:',
'allarticles' => 'Bütün məqalələr',
'allinnamespace' => 'Bütün səhifələr ($1 səhifələri)',
'allpagesnext' => 'Sonrakı',
'allpagesprev' => 'Əvvəlki',
'allpagessubmit' => 'Gətir',

# E this user
#
'emailuser' => 'İstifadəçiyə e-məktub yolla',
'emailpage' => 'İstifadəçiyə e-məktub yolla',
'defemailsubject' => '{{SITENAME}} e-məktub',
'noemailtitle' => 'E-məktub ünvanı yox',
'emailfrom' => 'Kimdən',
'emailsubject' => 'Mövzu',
'emailmessage' => 'Mesaj',
'emailsend' => 'Göndər',
'emailsent' => 'E-məktub göndərildi',

# Watchlist
#
'watchlist' => 'İzlədiyim səhifələr',
'watchnologin' => 'Daxil olmamısınız',
'addedwatch' => 'İzləmə siyahısına əlavə edildi.',
'addedwatchtext' => '"$1" səhifəsi [[Special:Watchlist|izlədiyiniz səhifələrə]] əlavə edildi. Bu səhifədə və əlaqəli müzakirə səhifəsində olacaq dəyişikliklər orada göstəriləcək və səhifə asanlıqla seçiləbilmək üçün [[Special:Recentchanges|son dəyişikliklər]]-də qalın şriftlərlə görsənəcəkdir. <p> Səhifəni izləmə sıyahınızdan çıxarmaq üçün yan lovhədəki "izləmə" düyməsinə vurun.',
'removedwatch' => 'İzləmə siyahısından çıxardılıb',
'removedwatchtext' => '"$1" səhifəsi izləmə siyahınızdan çıxardıldı.',
'watch' => 'İzlə',
'watchthispage' => 'Bu səhifəni izlə',
'unwatch' => 'İzləmə',
'unwatchthispage' => 'İzləmə',
'watchnochange' => 'Verilən vaxt ərzində heç bir izlədiyiniz səhifə redaktə edilməmişdir.',
'watchdetails' => '* müzakirə səhifələri çıxmaq şərtilə $1 səhifəni izləyirsiniz
* [[Special:Watchlist/edit|İzlədiyiniz səhifələrin tam siyahısının göstərilməsi və redaktəsi]]',
'wlheader-showupdated' => '* Son ziyarətinizdən sonra edilən dəyişikliklər \'\'\'qalın şriftlərlə\'\'\' göstərilmişdir.',
'watchmethod-recent' => 'yeni dəyişikliklər izlənilən səhifələr üçün yoxlanılır',
'watchmethod-list' => 'izlənilən səhifələr yeni dəyişikliklər üçün yoxlanılır',
'removechecked' => 'İşarələnənləri izləmə siyahısından çıxart',
'watchlistcontains' => 'İzləmə siyahınızda $1 səhifə var.',
'watcheditlist' => 'Bunlar izlədiyiniz səhifələrin əlifba sırasına görə siyahısıdır. Siyahıdan çıxartmaq istədiyiniz səhifələrin yanındakı qutuları işarələləyin və ekranın altındakı \'işarələnənləri sıyahıdan çıxart düyməsinə\' vurun(məzmun səhifəsini çıxartdıqda əlaqəli müzakirə səhifəsi də (və tərsinə) çıxardılacaqdır).',
'removingchecked' => 'İstədikləriniz izləmə siyahısından çıxardılır...',
'wlnote' => 'Aşağıdakılar son <b>$2</b> saatdakı son $1 dəyişiklikdir.',
'wlshowlast' => 'Bunları göstər: son $1 saatı $2 günü $3',
'wlsaved' => 'Bu izləmə siyahınızın qeyd edilmiş halıdır.',
'wlhideshowown' => 'Mənim redaktələrimi $1.',
'wlhideshowbots' => 'Bot redaktələrini $1.',

# Delete/protect/revert
#
'deletepage' => 'Səhifəni sil',
'confirm' => 'Təsdiq et',
'exblank' => 'səhifə boş',
'confirmdelete' => 'Silmeyi təsdiq et',
'actioncomplete' => 'Fəaliyyət tamamlandı',
'deletedtext' => '"$1" has been deleted. See $2 for a record of recent deletions.',
'deletedarticle' => 'silindi "[[$1]]"',
'rollback' => 'Əvvəlki versiya',
'rollbacklink' => 'əvvəlki halına qaytar',

'confirmprotect' => 'Qorumayı təsdiq et',

# Undelete
'undelete' => 'Silinmiş səhifələri göstər',
'viewdeletedpage' => 'Silinmiş səhifələri göstər',


# Namespace form on various pages
'namespace' => 'Adlar fəzası:',
'invert' => 'Seçilən xaricindəkiləri',

# Contributions
#
'contributions' => 'İstifadəçi köməkləri',
'mycontris' => 'Köməklərim',
'contribsub' => 'For $1',
'uctop' => '(son)',

# What links here
#
'whatlinkshere' => 'Bu səhifəyə bağlantılar',
'linklistsub'	=> '(Bağlantılar siyahı)',

# Block/unblock IP
#
'blockip' => 'İstifadəçiyi blokla',
'ipbreason' => 'Səbəb',
'ipbsubmit' => 'Bu istifadəçiyi əngəllə',
'badipaddress' => 'Yanlış IP',
'blockipsuccesssub' => 'bloklandi',
'blockipsuccesstext' => '[[{{ns:Special}}:Contributions/$1| $1]]bloklanıb. <br />See[[{{ns:Special}}:Ipblocklist|IP blok siyahisi]] bloklanmış IP lər.',
'ipblocklist' => 'Əngəllənmiş istifadəçilər siyahı',
'blocklink' => 'blokla',
'contribslink' => 'Köməklər',
'blocklogpage' => 'Blok qeydı',

# Developer tools
#

# Make sysop
'already_sysop' => 'Bu istifadəçi hazirdə idarəçidir',
'already_bureaucrat' => 'Bu istifadəçi hazirdə bürokratdı',

# Move page
#
'movepage' => 'Səhifənin adını dəyiş',
'movearticle' => 'Səhifənin adını dəyişdir',
'newtitle' => 'Yeni başlıq',
'movepagebtn' => 'Səhifənin adını dəyiş',
'movetalk' => 'Bu səhifənin müzakirə səhifəsinin de adını dəyişdir.',
'1movedto2' => '[[$1]] adı dəyişildi. Yeni adı: [[$2]]',
'1movedto2_redir' => '[[$1]] adı və məsiri dəyişildi : [[$2]]',
'movereason' => 'Səbəb',
'revertmove' => 'Əvvəlki vəziyyətinə',
'delete_and_move' => 'Sil və apar',

# Export
'export' => 'Səhifələri ixrac et',

# Namespace 8 related
'allmessages' => 'Sistem mə\'lumatları',
'allmessagesname' => 'Ad',
'allmessagesdefault' => 'İlkin mətn',
'allmessagescurrent' => 'İndiki mətn',
'allmessagestext' => 'Sistem mə\'lumatların siyahısı MediaWiki: namespace.',


# Metadata

# Attribution
'and' => 'və',

# Spam protection
'subcategorycount' => 'Bu kategoriyada $1 alt kategoriya var.',
'categoryarticlecount' => 'Bu kategoriyada $1 məqalə var.',
'listingcontinuesabbrev' => '(davam)',

# Monobook.js: tooltips and access keys for monobook
'monobook.js' => '/* Deprecated; use Common.js */',

/* qisa yol tuşlari və kömək balunları */

'accesskey-userpage' => '.',
'tooltip-userpage' => 'Öz Səhifəm',
'accesskey-anonuserpage' => '.',
'tooltip-anonuserpage' => 'The user page for the ip you',
'accesskey-mytalk' => 'n',
'tooltip-mytalk' => 'Danişiq Səhifəm',
'accesskey-anontalk' => 'n',
'tooltip-anontalk' => 'Bu IP ünvanindan redaktə olunmuş danışıqlar',
'accesskey-preferences' => '',
'tooltip-preferences' => 'Mənim Tərcihlərim',
'accesskey-watchlist' => 'l',
'tooltip-watchlist' => 'İzləməyə aldığım məqalələr.',
'accesskey-mycontris' => 'y',
'tooltip-mycontris' => 'Mən redakə etdiğim məqalələr siyahəsi',
'accesskey-login' => 'o',
'tooltip-login' => 'Hesab açmaniz tövsiə olur, ama icbar yoxdu .',
'accesskey-anonlogin' => 'o',
'tooltip-anonlogin' => 'Hesab açib girişiniz tövsiyə olur, ama məndatlı dəyil.',
'accesskey-logout' => '',
'tooltip-logout' => 'Çixiş',
'accesskey-talk' => 't',
'tooltip-talk' => 'Məqalə həqqində müzakirə edib, nəzərivi bildir',
'accesskey-edit' => 'e',
'tooltip-edit' => 'Bu səhifani redaktə edə bilərsiz. Lütfən avvəl sinaq gostəriş edin.',
'accesskey-addsection' => '+',
'tooltip-addsection' => 'Bu müzakirə səhifəsində iştirak edin.',
'accesskey-viewsource' => 'e',
'tooltip-viewsource' => 'Bu səhifə qorun altindadir. Mənbəsinə baxabilərsiz.',
'accesskey-history' => 'h',
'tooltip-history' => 'Bu səhifənin geçmiş nüsxələri.',
'accesskey-protect' => '=',
'tooltip-protect' => 'Bu səhifəni qoru',
'accesskey-delete' => 'd',
'tooltip-delete' => 'Bu səhifəni sil',
'accesskey-undelete' => 'd',
'tooltip-undelete' => 'Bu səhifəni silinmədən oncəki halına qaytarın',
'accesskey-move' => 'm',
'tooltip-move' => 'Bu məqalənin adını dəyışin',
'accesskey-watch' => 'w',
'tooltip-watch' => 'Bu səhifəni izlə',
'accesskey-unwatch' => 'w',
'tooltip-unwatch' => 'Bu səhifənin izlənmasini bitir',
'accesskey-search' => 'f',
'tooltip-search' => 'Bu vikini axtarin',
'accesskey-logo' => '',
'tooltip-logo' => 'Ana Səhifə',
'accesskey-mainpage' => 'z',
'tooltip-mainpage' => 'Ana səhifəni görüş edin',
'accesskey-portal' => '',
'tooltip-portal' => 'Projə həqqində, nələr edəbilərsiz, harda şeyləri tapa bilərsiz',
'accesskey-currentevents' => '',
'tooltip-currentevents' => 'Gündəki xəbərlər ilə əlaqəli bilgilər',
'accesskey-recentchanges' => 'r',
'tooltip-recentchanges' => 'Bu Wikidə Son dəyişikliklər siyahəsi.',
'accesskey-randompage' => 'x',
'tooltip-randompage' => 'Bir təsadufi, necə gəldi, məqaləyə baxin',
'accesskey-help' => '',
'tooltip-help' => 'Yardım almaq üçün.',
'accesskey-sitesupport' => '',
'tooltip-sitesupport' => 'Maddi kömək',
'accesskey-whatlinkshere' => 'j',
'tooltip-whatlinkshere' => 'Wikidə bu məqaləyə bağlantilar',
'accesskey-recentchangeslinked' => 'k',
'tooltip-recentchangeslinked' => 'Bu məqaləyə ayid başqa səhifələrdə yeni dəyişikliklər ',
'accesskey-feed-rss' => '',
'tooltip-feed-rss' => 'RSS feed for this page',
'accesskey-feed-atom' => '',
'tooltip-feed-atom' => 'Atom feed for this page',
'accesskey-contributions' => '',
'tooltip-contributions' => 'Bu üzvin redaktə etmiş məqalələr siyahəsi',
'accesskey-emailuser' => '',
'tooltip-emailuser' => 'Bu istifadəçiyə bir e-məktub yolla',
'accesskey-upload' => 'u',
'tooltip-upload' => 'Yeni FILE lar Wikiyə yüklə.',
'accesskey-specialpages' => 'q',
'tooltip-specialpages' => 'Xüsusi səhifələrin siyahəsi',
'accesskey-main' => 'c',
'tooltip-main' => 'View the content page',
'accesskey-user' => 'c',
'tooltip-user' => 'View the user page',
'accesskey-media' => 'c',
'tooltip-media' => 'View the media page',
'accesskey-special' => '',
'tooltip-special' => 'This is a special page, you can',
'accesskey-project' => 'a',
'tooltip-project' => 'View the project page',
'accesskey-image' => 'c',
'tooltip-image' => 'View the image page',
'accesskey-mediawiki' => 'c',
'tooltip-mediawiki' => 'View the system message',
'accesskey-template' => 'c',
'tooltip-template' => 'View the template',
'accesskey-help' => 'c',
'tooltip-help' => 'Kömək səhifəsi ',
'accesskey-category' => 'c',
'tooltip-category' => 'View the category page',

# image deletion

# browsing diffs
'previousdiff' => '← Əvvəlki fərq',
'nextdiff' => 'Sonrakı fərq →',

'imagemaxsize' => 'Limit images on image description pages to:',
'thumbsize' => 'Kiçik ölçü:',

'newimages' => 'Yeni faylların siyahısı',


# 'all' in various places, this might be different for inflected languages
'imagelistall' => 'bütün',
'watchlistall1' => 'hamısını',
'watchlistall2' => 'hamısını',
'namespacesall' => 'bütün',

# E-mail address confirmation
'confirmemail' => 'E-məktubunu təsdiq et',
'confirmemail_send' => 'Təsdiq kodu göndər',
'confirmemail_sent' => 'Təsdiq e-məktubu göndərildi.',
'confirmemail_success' => 'E-məktub ünvanınız indi təsdiq edildi.',
'confirmemail_loggedin' => 'E-məktubunuz indi təsdiq edildi.',
'confirmemail_subject' => '{{SITENAME}} e-məktub təsdiq etme',

# Inputbox extension, may be useful in other contexts as well
'createarticle' => 'Məqalə yarat',

);
?>
