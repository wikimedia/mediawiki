<?php
/** Uzbek (O'zbek)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abdulla
 * @author Behzod Saidov <behzodsaidov@gmail.com>
 * @author Urhixidur
 */

$fallback8bitEncoding = 'windows-1252';

$linkPrefixExtension = true;

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Maxsus',
	NS_TALK             => 'Munozara',
	NS_USER             => 'Foydalanuvchi',
	NS_USER_TALK        => 'Foydalanuvchi_munozarasi',
	NS_PROJECT_TALK     => '$1_munozarasi',
	NS_FILE             => 'Tasvir',
	NS_FILE_TALK        => 'Tasvir_munozarasi',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_munozarasi',
	NS_TEMPLATE         => 'Andoza',
	NS_TEMPLATE_TALK    => 'Andoza_munozarasi',
	NS_HELP             => 'Yordam',
	NS_HELP_TALK        => 'Yordam_munozarasi',
	NS_CATEGORY         => 'Turkum',
	NS_CATEGORY_TALK    => 'Turkum_munozarasi',
);
	
$namespaceAliases = array(
	'Mediya'                => NS_MEDIA,
	'MediyaViki'            => NS_MEDIAWIKI,
	'MediyaViki_munozarasi' => NS_MEDIAWIKI_TALK,
	'Shablon'               => NS_TEMPLATE,
	'Shablon_munozarasi'    => NS_TEMPLATE_TALK,
	'Kategoriya'            => NS_CATEGORY,
	'Kategoriya_munozarasi' => NS_CATEGORY_TALK,
);

$linkTrail = '/^([a-zʻʼ“»]+)(.*)$/sDu';

$messages = array(
# Dates
'sunday'        => 'Yakshanba',
'monday'        => 'Dushanba',
'tuesday'       => 'Seshanba',
'wednesday'     => 'Chorshanba',
'thursday'      => 'Payshanba',
'friday'        => 'Juma',
'saturday'      => 'Shanba',
'sun'           => 'Yak',
'mon'           => 'Dsh',
'tue'           => 'Ssh',
'wed'           => 'Chr',
'thu'           => 'Pay',
'fri'           => 'Jum',
'sat'           => 'Shn',
'january'       => 'yanvar',
'february'      => 'fevral',
'march'         => 'mart',
'april'         => 'aprel',
'may_long'      => 'may',
'june'          => 'iyun',
'july'          => 'iyul',
'august'        => 'avgust',
'september'     => 'sentabr',
'october'       => 'oktabr',
'november'      => 'noyabr',
'december'      => 'dekabr',
'january-gen'   => 'yanvarning',
'february-gen'  => 'fevralning',
'march-gen'     => 'martning',
'april-gen'     => 'aprelning',
'may-gen'       => 'mayning',
'june-gen'      => 'iyunning',
'july-gen'      => 'iyulning',
'august-gen'    => 'avgustning',
'september-gen' => 'sentabrning',
'october-gen'   => 'oktabrning',
'november-gen'  => 'noyabrning',
'december-gen'  => 'dekabrning',
'jan'           => 'yan',
'feb'           => 'fev',
'mar'           => 'mar',
'apr'           => 'apr',
'may'           => 'may',
'jun'           => 'iyn',
'jul'           => 'iyl',
'aug'           => 'avg',
'sep'           => 'sen',
'oct'           => 'okt',
'nov'           => 'noy',
'dec'           => 'dek',

# Categories related messages
'pagecategories'         => '{{PLURAL:$1|Turkum|Turkumlar}}',
'category_header'        => '"$1" turkumidagi maqolalar.',
'subcategories'          => 'Ostturkumlar',
'category-empty'         => "''Ushbu turkumda hozircha sahifa yoki fayllar yoʻq.''",
'category-subcat-count'  => '{{PLURAL:$2|Ushbu turkumda faqat bitta ostturkum mavjud.|Ushbu turkumda quyidagi {{PLURAL:$1|ostturkum|$1 ostturkumlar}}, hammasi boʻlib $2 ta ostturkum mavjud.}}',
'category-article-count' => '{{PLURAL:$2|Ushbu turkumda faqat bitta sahifa mavjud.|Ushbu turkumda quyidagi {{PLURAL:$1|sahifa|$1 sahifalar}}, hammasi boʻlib $2 ta sahifa mavjud.}}',
'listingcontinuesabbrev' => 'davomi',

'linkprefix'        => '/^(.*?)([a-zA-Z\\x80-\\xffʻʼ«„]+)$/sDu',
'mainpagetext'      => "'''MediaWiki muvaffaqiyatli o'rnatildi.'''",
'mainpagedocfooter' => "Wiki dasturini ishlatish haqida ma'lumot olish uchun  [http://meta.wikimedia.org/wiki/Help:Contents Foydalanuvchi qo'llanmasi] sahifasiga murojaat qiling.

== Dastlabki qadamlar ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Moslamalar ro'yxati]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki haqida ko'p so'raladigan savollar]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki yangi versiyasi chiqqanda xabar berish ro'yxati]",

'about'      => 'Haqida',
'newwindow'  => '(yangi oynada ochiladi)',
'cancel'     => 'Voz kechish',
'mytalk'     => 'Suhbatim',
'anontalk'   => 'Bu IP uchun suhbat',
'navigation' => 'Saytda harakatlanish',
'and'        => '&#32;va',

# Cologne Blue skin
'qbedit'         => 'Tahrirlash',
'qbspecialpages' => 'Maxsus sahifalar',

'errorpagetitle'   => 'Xato',
'returnto'         => '$1 sahifasiga qaytish.',
'tagline'          => '{{SITENAME}} dan',
'help'             => 'Yordam',
'search'           => 'Qidirish',
'searchbutton'     => 'Qidirish',
'go'               => "O'tish",
'searcharticle'    => "O'tish",
'history'          => 'Sahifa tarixi',
'history_short'    => 'Tarix',
'printableversion' => 'Bosma uchun versiya',
'permalink'        => "Doimiy bog'",
'edit'             => 'Tahrirlash',
'create'           => 'Yarat',
'editthispage'     => 'Sahifani tahrirla',
'delete'           => "O'chirish",
'protect'          => 'Himoyalash',
'protectthispage'  => 'Bu sahifani himoyala',
'unprotect'        => 'Himoyadan chiqarish',
'newpage'          => 'Yangi sahifa',
'talkpage'         => 'Bu sahifa haqida munozara',
'talkpagelinktext' => 'Munozara',
'specialpage'      => 'Maxsus sahifa',
'personaltools'    => 'Shaxsiy uskunalar',
'talk'             => 'Munozara',
'views'            => "Ko'rinishlar",
'toolbox'          => 'Asboblar',
'otherlanguages'   => 'Boshqa tillarda',
'redirectedfrom'   => '($1dan yoʻnaltirildi)',
'redirectpagesub'  => 'Yoʻnaltiruvchi sahifa',
'lastmodifiedat'   => 'Bu sahifa oxirgi marta $2, $1 sanasida tahrirlangan.',
'viewcount'        => 'Bu sahifaga {{PLURAL:$1|bir marta|$1 marta}} murojaat qilingan.',
'jumpto'           => 'Oʻtish:',
'jumptonavigation' => 'foydalanish',
'jumptosearch'     => 'Qidir',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} haqida',
'aboutpage'            => 'Project:Haqida',
'copyright'            => 'Kontent $1 ostidadir.',
'currentevents'        => 'Joriy hodisalar',
'currentevents-url'    => 'Project:Joriy hodisalar',
'disclaimers'          => 'Ogohlantirishlar',
'disclaimerpage'       => 'Project:Umumiy ogohlatirish',
'edithelp'             => 'Tahrirlash yordami',
'edithelppage'         => 'Help:Tahrirlash',
'helppage'             => 'Help:Mundarija',
'mainpage'             => 'Bosh sahifa',
'mainpage-description' => 'Bosh sahifa',
'portal'               => 'Jamoa portali',
'portal-url'           => 'Project:Jamoa portali',
'privacy'              => 'Konfidensiallik siyosati',
'privacypage'          => 'Project:Konfidensiallik siyosati',

'retrievedfrom'       => ' "$1" dan olindi',
'youhavenewmessages'  => 'Sizga $1 keldi ($2).',
'newmessageslink'     => 'yangi xabarlar',
'newmessagesdifflink' => 'soʻnggi oʻzgarish',
'editsection'         => 'tahrirlash',
'editold'             => 'tahrir',
'viewsourcelink'      => 'manbasini koʻr',
'editsectionhint'     => 'Boʻlimni tahrirlash: $1',
'toc'                 => 'Mundarija',
'showtoc'             => "Ko'rsatish",
'hidetoc'             => 'yashirish',
'red-link-title'      => '$1 (sahifa yaratilmagan)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Maqola',
'nstab-user'     => 'Foydalanuvchi sahifasi',
'nstab-special'  => 'Maxsus sahifa',
'nstab-project'  => 'Loyiha sahifasi',
'nstab-image'    => 'Fayl',
'nstab-template' => 'Andoza',
'nstab-help'     => 'Yordam sahifasi',
'nstab-category' => 'Turkum',

# General errors
'badtitle'          => 'Notoʻgʻri sarlavha',
'viewsource'        => "Ko'rib chiqish",
'viewsourcefor'     => '$1 uchun',
'protectedpagetext' => 'Bu sahifa tahrirlashdan saqlanish maqsadida qulflangan.',
'viewsourcetext'    => "Siz bu sahifaning manbasini ko'rishingiz va uni nusxasini olishingiz mumkin:",

# Login and logout pages
'logouttext'              => "'''Siz saytdan muvaffaqiyatli chiqdingiz.'''

{{SITENAME}} saytidan anonim holda foydalanishda davom etishindiz mumkin. Yoki siz yana hozirgi yoki boshqa foydalanuvchi nomi bilan qaytadan tizimga kirishingiz mumkin.
Shuni e'tiborga olingki, ayrim sahifalar siz brauzeringiz keshini tozalamaguningizga qadar xuddi tizimga kirganingizdagidek ko'rinishda davom etaverishi mumkin.",
'yourname'                => 'Foydalanuvchi nomi',
'yourpassword'            => "Maxfiy so'z",
'yourpasswordagain'       => "Maxfiy so'zni qayta kiriting",
'remembermypassword'      => "Hisob ma'lumotlarini shu kompyuterda eslab qolish",
'login'                   => 'Kirish',
'nav-login-createaccount' => 'Kirish / Hisob yaratish',
'loginprompt'             => "{{SITENAME}}ga kirish uchun kukilar yoqilgan bo'lishi kerak.",
'userlogin'               => 'Kirish / Hisob yaratish',
'logout'                  => 'Chiqish',
'userlogout'              => 'Chiqish',
'nologin'                 => "Hisobingiz yoʻqmi? '''$1'''.",
'nologinlink'             => 'Hisob yaratish',
'createaccount'           => 'Hisob yaratish',
'gotaccount'              => "Hisobingiz bormi? '''$1'''.",
'gotaccountlink'          => 'Kirish',
'loginsuccesstitle'       => 'Kirish muvaffaqiyatli amalga oshdi',
'loginsuccess'            => "'''{{SITENAME}}ga \"\$1\" foydalanuvchi nomi bilan kirdingiz.'''",
'wrongpassword'           => 'Kiritgan mahfiy soʻzingiz notoʻgʻri. Iltimos, qaytadan kiritib koʻring.',

# Password reset dialog
'retypenew' => 'Yangi mahfiy soʻzni qayta tering:',

# Edit page toolbar
'bold_sample'     => 'Qalin matn',
'bold_tip'        => 'Qalin matn',
'italic_sample'   => 'Kursiv',
'italic_tip'      => 'Kursiv',
'link_sample'     => 'Ishorat nomi',
'link_tip'        => 'Ichki ishorat',
'extlink_sample'  => 'http://www.example.com ishorat nomi',
'extlink_tip'     => 'Tashqi ishorat (http:// prefiksini unutmang)',
'headline_sample' => 'Sarlavha',
'math_sample'     => 'Formula qoʻying',
'math_tip'        => 'Matematik formula (LaTeX)',
'image_tip'       => 'Qoʻshilgan tasvir',
'media_tip'       => 'Faylga ishorat',
'sig_tip'         => 'Imzoingiz va sana',

# Edit pages
'summary'                    => 'Qisqa izoh:',
'minoredit'                  => 'Bu kichik tahrir',
'watchthis'                  => 'Sahifani kuzatish',
'savearticle'                => 'Saqlash',
'preview'                    => "Ko'rib chiqish",
'showpreview'                => "Ko'rib chiqish",
'showdiff'                   => "O'zgarishlarni ko'rsatish",
'anoneditwarning'            => "'''Diqqat:''' Siz tizimga kirmagansiz. Ushbu sahifa tarixida Sizning IP manzilingiz yozib qolinadi.",
'blockedtext'                => "'''Siz (foydalanuvchi ismingiz yoki IP manzilingiz) tahrir qilishdan chetlashtirildingiz.'''

Sizni $1 chetlashtirdi. Bunga sabab: ''$2''.

* Chetlashtirish muddati boshi: $8
* Chetlashtirish muddati yakuni: $6

Siz $1 yoki boshqa [[{{MediaWiki:Grouppage-sysop}}|administrator]] bilan bogʻlanib, arz qilishingiz mumkin.
Sizning hozirgi IP manzilingiz - $3, chetlashtirish raqamingiz - #$5. Arizaga bularni ilova qilishingiz mumkin.",
'newarticle'                 => '(Yangi)',
'newarticletext'             => "Bu sahifa hali mavjud emas.
Sahifani yaratish uchun quyida matn kiritishingiz mumkin (qo'shimcha axborot uchun [[{{MediaWiki:Helppage}}|yordam sahifasini]] ko'ring).
Agar bu sahifaga xatolik sabab kelgan bo'lsangiz brauzeringizning '''orqaga''' tugmasini bosing.",
'noarticletext'              => 'Bu sahifada hozircha hech qanday matn yoʻq. Siz bu sarlavhani boshqa sahifalardan [[Special:Search/{{PAGENAME}}|qidirishingiz]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tegishli loglarga qarashingiz] yoki bu sahifani [{{fullurl:{{FULLPAGENAME}}|action=edit}} tahrirlashingiz]</span> mumkin.',
'clearyourcache'             => "'''Etibor bering:''' O'zgartirishlaringiz ko'rish uchun, yangi moslamalaringizning saqlashdan keyin, brauser keshini tozalash kerak:<br />
'''Mozilla / Firefox:''' ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari:''' ''Cmd+Shift+R'', '''Konqueror:''' ''F5'', '''Opera:''' ''Tools → Preferences'' orqali keshni tozalang.",
'previewnote'                => "'''Bu shunchaki ko'rib chiqish. O'zgarishlar hali saqlangani yo'q!'''",
'editing'                    => '$1 tahrirlanmoqda',
'editingsection'             => '$1 (boʻlim) tahrirlanmoqda',
'copyrightwarning'           => "Iltimos, {{SITENAME}}ga yuklangan har qanday axborot $2 ostida tarqatilishiga diqqat qiling (batafsil ma'lumot uchun $1ni ko'ring).
Agar yozganlaringiz keyinchalik tahrir qilinishi va qayta tarqatilishiga rozi bo'lmasangiz, u holda bu yerga yozmang.<br />
Siz shuningdek bu yozganlaringiz sizniki yoki erkin litsenziya ostida ekanligini va'da qilmoqdasiz.
'''MUALLIFLIK HUQUQLARI BILAN HIMOYALANGAN ISHLARNI ZINHOR BERUXSAT YUBORMANG!'''",
'copyrightwarning2'          => "Iltimos, shuni esda tutingki, {{SITENAME}} sahifalaridagi barcha matnlar boshqa foydalanuvchilar tomonidan tahrirlanishi, almashtirilishi yoki o'chirilishi mumkin. Agar siz yozgan ma'lumotlaringizni bunday tartibda tahrirlanishiga rozi bo'lmasangiz, unda uni bu yerga joylashtirmang.<br />
Bundan tashqari, siz ushbu ma'lumotlarni o'zingiz yozgan bo'lishingiz yoki ruxsat berilgan internet manzilidan yoki shu kabi erkin resursdan nusxa olgan bo'lishingiz lozim (Qo'shimcha ma'lumotlar ushun $1 sahifasiga murojaat qiling).
'''MUALLIFLIK HUQUQI QO'YILGAN ISHLARNI RUXSATSIZ BU YERGA JOYLASHTIRMANG!'''",
'longpagewarning'            => "'''DIQQAT: Ushbu sahifa hajmi $1 kilobaytdir; ayrim browser'lar 32 kb dan oshiq hajmli sahifalarni tahrirlashda muammolarga duch kelishi mumkin.
Agar sizda shunday muammolar yuzaga kelsa, iltimos, sahifani butunlay emas, qismlab tahrirlang.'''",
'templatesused'              => 'Ushbu sahifada ishlatilgan andozalar:',
'template-protected'         => '(himoyalangan)',
'template-semiprotected'     => '(yarim-himoyalangan)',
'nocreatetext'               => 'Ushbu sayt yangi sahifa yaratishni taqiqlagan.
Ortga qaytib, mavjud sahifani tahrirlashingiz yoki [[Special:UserLogin|tizimga kirishingiz]] mumkin.',
'recreate-moveddeleted-warn' => "'''Diqqat: Siz avval yoʻqotilgan sahifani yana yaratmoqchisiz.'''

Bu sahifani yaratishda davom etishdan avval uning nega avval yoʻqotilgani bilan qiziqib koʻring.
Qulaylik uchun quyida yoʻqotilish qaydlari keltirilgan:",

# History pages
'viewpagelogs'        => 'Ushbu sahifaga doir qaydlarni koʻrsat',
'currentrev'          => 'Hozirgi koʻrinishi',
'currentrev-asof'     => '$1dagi, joriy koʻrinishi',
'revisionasof'        => '$1 paytdagi koʻrinishi',
'previousrevision'    => '←Avvalgi koʻrinishi',
'nextrevision'        => 'Yangiroq koʻrinishi→',
'currentrevisionlink' => 'Hozirgi koʻrinishi',
'cur'                 => 'joriy',
'next'                => 'keyingi',
'last'                => 'oxirgi',
'histlegend'          => 'Farqlar: solishtirish uchun kerakli radiobokslarni belgilang va pastdagi tugmani yoki Enterni bosing.<br />
Bu yerda: (joriy) = hozirgi koʻrinish bilan farq,
(oxirgi) = avvalgi koʻrinish bilan farq, k = kichkina tahrir.',
'histfirst'           => 'Eng avvalgi',
'histlast'            => 'Eng soʻnggi',

# Revision deletion
'rev-delundel' => 'koʻrsat/yashir',

# Diffs
'history-title'           => '"$1"ning tarixi',
'difference'              => '(Koʻrinishlar orasidagi farq)',
'lineno'                  => 'Qator $1:',
'compareselectedversions' => 'Tanlangan versiyalarni solishtir',
'editundo'                => 'qaytar',

# Search results
'searchresults'            => 'Qidiruv natijalari',
'searchresults-title'      => '"$1" uchun qidiruv natijalari',
'searchresulttext'         => "{{SITENAME}}da qidirish haqida qo'shimcha ma'lumotga ega bo'lishini xoxlasangiz, [[{{MediaWiki:Helppage}}|{{SITENAME}}da qidiruv]] sahifasini o'qing.",
'searchsubtitle'           => '\'\'\'[[:$1]]\'\'\'ni qidirdingiz ([[Special:Prefixindex/$1|"$1" bilan boshlanadigan sahifalar]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"ga bogʻlangan sahifalar]])',
'searchsubtitleinvalid'    => "'''$1'''ni qidirdingiz",
'notitlematches'           => 'Bunday sarlavha topilmadi',
'notextmatches'            => 'Bunday matn topilmadi',
'prevn'                    => 'oldingi $1',
'nextn'                    => 'keyingi {{PLURAL:$1|$1}}',
'viewprevnext'             => "Ko'rish ($1 {{int:pipe-separator}} $2) ($3).",
'searchhelp-url'           => 'Help:Mundarija',
'search-result-size'       => '$1 ({{PLURAL:$2|1 soʻz|$2 soʻz}})',
'search-redirect'          => '(yoʻnaltirish $1)',
'search-section'           => '($1 boʻlimi)',
'search-suggest'           => 'Balki buni nazarda tutgandirsiz: $1',
'search-interwiki-default' => '$1 natijalar:',
'showingresults'           => "#<b>$2</b> boshlanayotgan <b>$1</b> natijalar ko'rsatilyapti.",
'powersearch'              => 'Qidiruv',
'powersearch-ns'           => 'Bu nom-fazolarda izla:',
'powersearch-redir'        => 'Yoʻnaltirishlarni koʻrsat',
'powersearch-field'        => 'Qidir',

# Preferences page
'preferences'       => 'Moslamalar',
'mypreferences'     => 'Moslamalarim',
'prefs-skin'        => "Tashqi ko'rinish",
'prefs-math'        => 'Formulalar',
'prefs-datetime'    => 'Sana va vaqt',
'prefs-personal'    => "Shaxsiy ma'lumotlar",
'prefs-rc'          => "Yangi o'zgartirishlar",
'prefs-watchlist'   => "Kuzatuv ro'yxati",
'prefs-misc'        => 'Boshqa moslamalar',
'saveprefs'         => 'Saqlash',
'resetprefs'        => 'Bekor qilish',
'prefs-editing'     => 'Tahrirlash',
'searchresultshead' => 'Qidiruv natijalari',
'prefs-files'       => 'Fayllar',
'yourrealname'      => 'Haqiqiy ism *:',

# Groups
'group-sysop' => 'Administratorlar',

'grouppage-sysop' => '{{ns:project}}:Administratorlar',

# Recent changes
'recentchanges'     => "Yangi o'zgartirishlar",
'recentchangestext' => "Bu sahifada siz oxirgi o'zgartirishlarni ko'rishingiz mumkin.",
'rcnote'            => "Quyida $5, $4ga koʻra oxirgi {{PLURAL:$2|kun|'''$2''' kun}} davomida sodir boʻlgan {{PLURAL:$1|'''1''' oʻzgartirish|'''$1''' oʻzgartirishlar}} koʻrsatilgan.",
'rclistfrom'        => "$1dan boshlab yangi o'zgartirishlarni ko'rsat.",
'rcshowhideminor'   => 'Kichik tahrirlarni $1',
'rcshowhidebots'    => 'Botlarni $1',
'rcshowhideliu'     => "Ro'yxatdan o'tgan foydalanuvchilarni $1",
'rcshowhideanons'   => 'Anonim foydalanuvchilarni $1',
'rcshowhidepatr'    => 'Tekshirilgan tahrirlarni $1',
'rcshowhidemine'    => "O'z tahrirlarimni $1",
'rclinks'           => "Oxirgi $2 kun davomida sodir bo'lgan $1 o'zgartirishlarni ko'rsat.<br />$3",
'diff'              => 'farq',
'hist'              => 'tarix',
'hide'              => 'yashirish',
'show'              => "ko'rsat",
'minoreditletter'   => 'k',
'newpageletter'     => 'Y',

# Recent changes linked
'recentchangeslinked'          => "Bog'langan o'zgarishlar",
'recentchangeslinked-title'    => '"$1"ga aloqador oʻzgarishlar',
'recentchangeslinked-noresult' => 'Berilgan davrda bogʻlangan sahifalarda oʻzgarishlar boʻlmagan.',
'recentchangeslinked-summary'  => "Ushbu maxsus sahifa unga bogʻlangan sahifalardagi soʻnggi oʻzgarishlarni koʻrsatadi. [[Special:Watchlist|Kuzatuv roʻyxatingizdagi]] sahifalar '''qalin''' qilib koʻrsatilgan.",

# Upload
'upload'        => 'Fayl yuklash',
'uploadbtn'     => 'Fayl yukla',
'uploadlogpage' => 'Yuklash qaydlari',
'uploadedimage' => '"[[$1]]" yuklandi',

# Special:ListFiles
'listfiles' => 'Fayllar roʻyxati',

# File description page
'file-anchor-link'          => 'Fayl',
'filehist'                  => 'Fayl tarixi',
'filehist-help'             => 'Faylning biror paytdagi holatini koʻrish uchun tegishli sana/vaqtga bosingiz.',
'filehist-current'          => 'joriy',
'filehist-datetime'         => 'Sana/Vaqt',
'filehist-user'             => 'Foydalanuvchi',
'filehist-dimensions'       => 'Oʻlchamlari',
'filehist-filesize'         => 'Fayl hajmi',
'filehist-comment'          => 'Izoh',
'imagelinks'                => 'Fayllarga ishoratlar',
'linkstoimage'              => 'Bu faylga quyidagi {{PLURAL:$1|sahifa|$1 sahifalar}} bogʻlangan:',
'nolinkstoimage'            => 'Bu faylga bogʻlangan sahifalar yoʻq.',
'sharedupload'              => 'Ushbu fayl umumiy joyga yuklangandir va boshqa loyihalarda ishlatilishi mumkin.',
'uploadnewversion-linktext' => 'Bu faylning yangi versiyasini yukla',

# Unused templates
'unusedtemplates' => 'Ishlatilinmagan andozalar',

# Random page
'randompage' => 'Tasodifiy sahifa',

# Statistics
'statistics'              => 'Statistika',
'statistics-header-users' => 'Foydalanuvchilar statistikasi',

'disambiguationspage' => '{{ns:template}}:Disambig',

# Miscellaneous special pages
'ncategories'             => '$1 {{PLURAL:$1|turkum|turkumlar}}',
'lonelypages'             => 'Yetim sahifalar',
'uncategorizedpages'      => 'Turkumlashtirilmagan sahifalar',
'uncategorizedcategories' => 'Turkumlashtirilmagan turkumlar',
'uncategorizedimages'     => 'Kategoriyasiz tasvirlar',
'uncategorizedtemplates'  => 'Turkumlashtirilmagan andozalar',
'unusedcategories'        => 'Ishlatilinmagan turkumlar',
'unusedimages'            => 'Ishlatilinmagan fayllar',
'wantedcategories'        => 'Talab qilinayotgan turkumlar',
'mostcategories'          => 'Eng koʻp turkumli sahifalar',
'protectedpages'          => 'Himoyalangan sahifalar',
'listusers'               => 'Foydalanuvchilar roʻyxati',
'newpages'                => 'Yangi sahifalar',
'move'                    => "Ko'chirish",
'movethispage'            => 'Bu sahifani koʻchir',
'pager-newer-n'           => '{{PLURAL:$1|yangiroq 1|yangiroq $1}}',
'pager-older-n'           => '{{PLURAL:$1|eskiroq 1|eskiroq $1}}',

# Special:Log
'log'           => 'Qaydlar',
'all-logs-page' => 'Barcha qaydlar',

# Special:AllPages
'allpages'       => 'Barcha sahifalar',
'alphaindexline' => '$1 dan $2 ga',
'nextpage'       => 'Keyingi sahifa ($1)',
'prevpage'       => 'Avvalgi sahifa ($1)',
'allpagesfrom'   => 'Sahifalarni koʻrsat:',
'allarticles'    => 'Barcha sahifalar',
'allpagesnext'   => 'Keyingi',
'allpagessubmit' => "O'tish",
'allpagesprefix' => 'Bunday prefiksli sahifalarni koʻrsat:',

# Special:Categories
'categories'         => 'Turkumlar',
'categoriespagetext' => 'Ushbu turkumlarda sahifa yoki fayllar mavjud.',

# Special:Log/newusers
'newuserlog-create-entry' => 'Yangi foydalanuvchi',

# E-mail user
'emailuser' => 'Bu foydalanuvchiga e-maktub joʻnat',

# Watchlist
'watchlist'        => 'Kuzatuv roʻyxatim',
'mywatchlist'      => 'Kuzatuv roʻyxatim',
'watchlistfor'     => "('''$1''' uchun)",
'nowatchlist'      => "Kuzatuv ro'yxatingizda hech narsa yo'q.",
'addedwatch'       => "Kuzatuv ro'yxatiga qo'shildi",
'addedwatchtext'   => "\"[[:\$1]]\" sahifasi sizning [[Special:Watchlist|kuzatuv ro'yxatingizga]] qo'shildi. Bu sahifada va unga mos munozara sahifasida bo'ladigan kelajakdagi o'zgarishlar bu yerda ro'yxatga olinadi, hamda bu sahifa topish qulay bo'lishi uchun [[Special:RecentChanges|yangi o'zgarishlar ro'yxati]]da '''qalin''' harflar bilan ko'rsatiladi.

Agar siz bu sahifani kuzatuv ro'yxatingizdan o'chirmoqchi bo'lsangiz \"Kuzatmaslik\" yozuvini bosing.",
'removedwatch'     => "Kuzatuv ro'yxatidan o'chirildi",
'removedwatchtext' => '"[[:$1]]" sahifasi kuzatuv ro\'yxatingizdan o\'chirildi.',
'watch'            => 'kuzatish',
'watchthispage'    => 'Sahifani kuzatish',
'unwatch'          => 'kuzatmaslik',
'wlnote'           => "Pastda oxirgi '''$2''' soatda sodir bo'lgan $1 o'zgartirishlar ko'rsatilgan.",
'wlshowlast'       => "Oxirgi $1 soatdagi $2 kundagi tahrirlarni ko'rsatish. $3 tahrirlarni ko'rsatish",

# Delete
'actioncomplete'        => 'Bajarildi',
'deletedtext'           => '"<nowiki>$1</nowiki>" yoʻqotildi.
Yaqinda sodir etilgan yoʻqotishlar uchun $2ni koʻring.',
'deletedarticle'        => '"[[$1]]" yoʻqotildi',
'dellogpage'            => 'Yoʻqotish qaydlari',
'deletecomment'         => "O'chirish sababi",
'deleteotherreason'     => 'Boshqa/qoʻshimcha sabab:',
'deletereasonotherlist' => 'Boshqa sabab',

# Rollback
'rollbacklink' => 'eski holiga keltir',

# Protect
'protectlogpage'         => 'Himoyalash qaydlari',
'protect-level-sysop'    => 'Faqat administratorlar uchun',
'protect-expiry-options' => '2 soat:2 hours,1 kun:1 day,1 hafta:1 week,2 hafta:2 weeks,1 oy:1 month,3 oy:3 months,6 oy:6 months,1 yil:1 year,cheksiz:infinite',

# Restrictions (nouns)
'restriction-edit' => 'Tahrirlash',

# Undelete
'undeletebtn'  => 'Qayta tikla',
'undeletelink' => 'tikla',

# Namespace form on various pages
'namespace' => 'Soha:',
'invert'    => 'Tanlash tartibini almashtirish',

# Contributions
'contributions' => 'Foydalanuvchining hissasi',
'mycontris'     => 'hissam',
'contribsub2'   => '$1 uchun ($2)',

'sp-contributions-newbies'  => 'Faqatgina yangi foydalanuvchilarning hissalarini koʻrsat',
'sp-contributions-blocklog' => 'Chetlashtirish qaydlari',
'sp-contributions-talk'     => 'Munozara',
'sp-contributions-search'   => 'Hissalarni qidir',
'sp-contributions-username' => 'IP manzil yoki foydalanuvchi ismi:',
'sp-contributions-submit'   => 'Qidir',

# What links here
'whatlinkshere'       => "Bu sahifaga bog'langan sahifalar",
'whatlinkshere-title' => '"$1"ga bogʻlangan sahifalar',
'linkshere'           => "Quyidagi sahifalar '''[[:$1]]''' sahifasiga bog'langan:",
'nolinkshere'         => "'''[[:$1]]''' sahifasiga hech qaysi sahifa bog'lanmagan.",
'isredirect'          => 'yoʻnaltiruvchi sahifa',
'istemplate'          => 'qoʻshimcha',
'whatlinkshere-links' => '← ishoratlar',

# Block/unblock
'blockip'      => 'Foydalanuvchini chetlashtir',
'ipboptions'   => '2 soat:2 hours,1 kun:1 day,3 kun:3 days,1 hafta:1 week,2 hafta:2 weeks,1 oy:1 month,3 oy:3 months,6 oy:6 months,1 yil:1 year,cheksiz:infinite',
'ipblocklist'  => 'Chetlashtirilgan IP manzillari va foydalanuvchilar',
'blocklink'    => 'chetlashtir',
'contribslink' => 'hissasi',
'blocklogpage' => 'Chetlashtirish qaydlari',

# Move page
'movearticle'    => "Sahifani ko'chirish",
'movepagebtn'    => 'Sahifani koʻchir',
'pagemovedsub'   => 'Koʻchirildi',
'movepage-moved' => '\'\'\'"$1" nomli sahifa "$2" nomli sahifaga koʻchirildi\'\'\'',
'1movedto2'      => "[[$1]] [[$2]]ga ko'chirildi",
'movelogpage'    => 'Koʻchirish qaydlari',
'movereason'     => 'Sabab:',
'revertmove'     => 'qaytar',

# Thumbnails
'thumbnail-more' => 'Kattalashtir',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Foydalanuvchi sahifangiz',
'tooltip-pt-anonuserpage'         => 'Siznig ip manzilingiz foydalanuvchi sahifasi',
'tooltip-pt-mytalk'               => 'Suhbat sahifangiz',
'tooltip-pt-anontalk'             => 'Bu ip manzildan amalga oshirilgan tahrirlar munozarasi',
'tooltip-pt-preferences'          => 'Moslamalaringiz',
'tooltip-pt-watchlist'            => "Siz kuzatib borayotgan sahifalar ro'yxati.",
'tooltip-pt-mycontris'            => 'Hissalaringiz roʻyxati',
'tooltip-pt-login'                => "Bu majburiyat bo'lmasada, kirishingiz taklif qilinadi.",
'tooltip-pt-anonlogin'            => "Bu majburiyat bo'lmasada, kirishingiz taklif qilinadi.",
'tooltip-pt-logout'               => 'Chiqish',
'tooltip-ca-talk'                 => 'Sahifa matni borasida munozara',
'tooltip-ca-edit'                 => "Siz bu sahifani tahrirlashingiz mumkin. Iltimos, saqlashdan oldim ko'rib chiqish tugmasidan foydalaning.",
'tooltip-ca-addsection'           => 'Yangi boʻlim och',
'tooltip-ca-viewsource'           => "Bu sahifa himoyalangan. Siz uning manbasini ko'rishingiz mumkin.",
'tooltip-ca-history'              => 'Bu sahifaning oldingi versiyalari.',
'tooltip-ca-protect'              => 'Bu sahifani himoyalash',
'tooltip-ca-delete'               => "Bu sahifani o'chirish",
'tooltip-ca-undelete'             => "Bu sahifa o'chirilmasdan oldin qilingan tahrirlarni tiklash",
'tooltip-ca-move'                 => "Bu sahifani ko'chirish",
'tooltip-ca-watch'                => "Bu sahifani kuzatuv ro'yxatingizga qo'shish",
'tooltip-ca-unwatch'              => "Bu sahifani kuzatuv ro'yxatingizga o'chirish",
'tooltip-search'                  => '{{SITENAME}}da qidirish',
'tooltip-search-go'               => 'Xuddi shu nomli sahifa bor boʻlsa, uni och',
'tooltip-search-fulltext'         => 'Sahifalarda shu matnni izla',
'tooltip-p-logo'                  => 'Bosh sahifa',
'tooltip-n-mainpage'              => "Bosh sahifaga o'tish",
'tooltip-n-portal'                => 'Loyiha haqida, nimalar qilishingiz mumkin, nimalarni qayerdan topish mumkin',
'tooltip-n-currentevents'         => "Joriy hodisalar haqida ma'lumot olish",
'tooltip-n-recentchanges'         => "Yangi o'zgarishlar ro'yxati.",
'tooltip-n-randompage'            => 'Tasodifiy sahifani yuklash',
'tooltip-n-help'                  => "O'rganish uchun manzil.",
'tooltip-t-whatlinkshere'         => "Bu sahifaga bog'langan sahifalar ro'yxati",
'tooltip-t-recentchangeslinked'   => "Bu sahifa bog'langan sahifalardagi yangi o'zgarishlar",
'tooltip-feed-rss'                => "Bu sahifa uchun RSS ta'minot",
'tooltip-feed-atom'               => "Bu sahifa uchun Atom ta'minot",
'tooltip-t-contributions'         => "Bu foydalanuvchinig qo'shgan hissasini ko'rish",
'tooltip-t-emailuser'             => "Bu foydalanuvchiga xat jo'natish",
'tooltip-t-upload'                => 'Rasmlar yoki media fayllar yuklash',
'tooltip-t-specialpages'          => "Maxsus sahifalar ro'yxati",
'tooltip-t-print'                 => 'Ushbu sahifaning bosma uchun versiyasi',
'tooltip-t-permalink'             => 'Sahifaning ushbu versiyasiga doimiy ishorat',
'tooltip-ca-nstab-main'           => "Sahifani ko'rish",
'tooltip-ca-nstab-user'           => "Foydalanuvchi sahifasini ko'rish",
'tooltip-ca-nstab-media'          => "Media sahifasini ko'rish",
'tooltip-ca-nstab-special'        => 'Bu maxsus sahifa, uni tahrirlay olmaysiz.',
'tooltip-ca-nstab-project'        => "Loyiha sahifasini ko'rish",
'tooltip-ca-nstab-image'          => "Rasm sahifasini ko'rish",
'tooltip-ca-nstab-mediawiki'      => "Tizim xabarini ko'rish",
'tooltip-ca-nstab-template'       => 'Andozani koʻrish',
'tooltip-ca-nstab-help'           => "Yordam sahifasini ko'rish",
'tooltip-ca-nstab-category'       => 'Turkum sahifasini koʻrish',
'tooltip-minoredit'               => "Kichik o'zgarish sifatida belgilash",
'tooltip-save'                    => "O'zgarishlarni saqlash",
'tooltip-preview'                 => "O'zgarishlarni saqlash. Iltimos saqlashdan oldin uni ishlating!",
'tooltip-diff'                    => "Matnga qanday o'zgarishlar kiritganligingizni ko'rish.",
'tooltip-compareselectedversions' => "Bu sahifaning ikki tanlangan versiyalari o'rtasidagi farqni ko'rish.",
'tooltip-watch'                   => "Bu sahifani kuzatuv ro'yxatingizga qo'shish",
'tooltip-recreate'                => "Bu sahifani u o'chirilgan bo'lishiga qaramasdan qayta yaratish",

# Browsing diffs
'previousdiff' => '← Avvalgi tahrir',
'nextdiff'     => 'Keyingi tahrir →',

# Media information
'imagemaxsize'         => "Tasvir ta'rifi sahifasidagi tasvirning kattaligi:",
'thumbsize'            => 'Tasvirning kichiklashtirilgan versiyasining kattaligi:',
'file-info-size'       => '($1 × $2 piksel, fayl hajmi: $3, MIME tipi: $4)',
'file-nohires'         => '<small>Bundan kattaroq tasvir yoʻq.</small>',
'svg-long-desc'        => '(SVG fayl, asl oʻlchamlari $1 × $2 piksel, fayl hajmi: $3)',
'show-big-image'       => 'Asl hajmdagi tasvir',
'show-big-image-thumb' => '<small>Ushbu koʻrinish oʻlchamlari: $1 × $2 piksel</small>',

# Special:NewFiles
'ilsubmit' => 'Qidirish',

# Metadata
'metadata-expand'   => 'Batafsil axborot koʻrsat',
'metadata-collapse' => 'Batafsil axborotni yashir',

# External editor support
'edit-externally'      => 'Bu faylni tashqi dasturiy ilovalar yordamida tahrirla',
'edit-externally-help' => "(Batafsil ma'lumotlar uchun [http://www.mediawiki.org/wiki/Manual:External_editors bu yerga] qarang)",

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Barchasi',
'imagelistall'     => 'Barchasi',
'watchlistall2'    => 'Barcha',
'namespacesall'    => 'Barchasi',

'unit-pixel' => 'piksel',

# Special:SpecialPages
'specialpages' => 'Maxsus sahifalar',

);
