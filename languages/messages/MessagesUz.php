<?php
/** Uzbek (oʻzbekcha)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Abdulla
 * @author Behzod Saidov <behzodsaidov@gmail.com>
 * @author Casual
 * @author Lyncos
 * @author Sociologist
 * @author Urhixidur
 * @author Xexdof
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
# User preference toggles
'tog-hideminor'            => 'Yangi oʻzgarishlardagi kichik tahrirlani yashir',
'tog-rememberpassword'     => 'Hisob ma’lumotlarini ushbu kompyuterda eslab qolish (eng ko‘pi bilan $1 {{PLURAL:$1|kunga|kunga}})',
'tog-watchcreations'       => 'Men yaratgan sahifalarni va yuklagan fayllarni kuzatuv roʻyxatimga qoʻsh',
'tog-watchdefault'         => 'Men tahrirlagan sahifa va fayllarni kuzatuv roʻyxatimga qoʻsh',
'tog-watchmoves'           => 'Men koʻchirgan sahifa va fayllarni kuzatuv roʻyxatimga qoʻsh',
'tog-watchdeletion'        => 'Men yoʻqotgan sahifa va fayllarni kuzatuv roʻyxatimga qoʻsh',
'tog-enotifwatchlistpages' => 'Kuzatuv roʻyxatimdagi sahifa yoki fayllar oʻzgartirilsa, e-pochtamga bu haqda xat yuborilsin',
'tog-enotifusertalkpages'  => 'Munozara sahifam oʻzgartirilsa, e-pochtamga bu haqda xat yuborilsin',
'tog-oldsig'               => 'Mavjud imzo:',
'tog-fancysig'             => 'Imzoni wikimatn sifatida qara (avtomatik ishoratsiz)',
'tog-ccmeonemails'         => 'Men boshqa foydalanuvchilarga yuborayotgan xatnig nusxasi oʻzimning e-pochtamga ham yuborilsin',
'tog-showhiddencats'       => 'Yashirin turkumlarni koʻrsat',

'underline-always'  => 'Har doim',
'underline-never'   => 'Hech qachon',
'underline-default' => 'Brauzer moslamari boʻyicha',

# Font style option in Special:Preferences
'editfont-default' => 'Brauzer moslamari boʻyicha',

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
'pagecategories'           => '{{PLURAL:$1|Turkum|Turkumlar}}',
'category_header'          => '"$1" turkumidagi maqolalar.',
'subcategories'            => 'Ostturkumlar',
'category-empty'           => "''Ushbu turkumda hozircha sahifa yoki fayllar yoʻq.''",
'hidden-categories'        => '{{PLURAL:$1|Yashirin turkum|Yashirin turkumlar}}',
'hidden-category-category' => 'Yashirin turkumlar',
'category-subcat-count'    => '{{PLURAL:$2|Ushbu turkumda faqat bitta ostturkum mavjud.|Ushbu turkumda quyidagi {{PLURAL:$1|ostturkum|$1 ostturkumlar}}, hammasi boʻlib $2 ta ostturkum mavjud.}}',
'category-article-count'   => '{{PLURAL:$2|Ushbu turkumda faqat bitta sahifa mavjud.|Ushbu turkumda quyidagi {{PLURAL:$1|sahifa|$1 sahifalar}}, hammasi boʻlib $2 ta sahifa mavjud.}}',
'listingcontinuesabbrev'   => 'davomi',

'linkprefix' => '/^(.*?)([a-zA-Z\\x80-\\xffʻʼ«„]+)$/sDu',

'about'         => 'Haqida',
'newwindow'     => '(yangi oynada ochiladi)',
'cancel'        => 'Voz kechish',
'moredotdotdot' => 'Batafsil...',
'mytalk'        => 'Suhbatim',
'anontalk'      => 'Bu IP uchun suhbat',
'navigation'    => 'Saytda harakatlanish',
'and'           => '&#32;va',

# Cologne Blue skin
'qbedit'         => 'Tahrirlash',
'qbspecialpages' => 'Maxsus sahifalar',
'faq'            => 'TSS',

# Vector skin
'vector-action-addsection' => 'Mavzuni qoʻsh',
'vector-action-delete'     => 'O‘chirish',
'vector-action-move'       => 'Ko‘chirish',
'vector-view-create'       => 'Yarat',
'vector-view-edit'         => 'Tahrirla',
'vector-view-history'      => 'Tarix',
'vector-view-view'         => 'Mutolaa',
'vector-view-viewsource'   => 'Manbasini koʻrsat',
'actions'                  => 'Amallar',
'namespaces'               => 'Nomfazolar',
'variants'                 => 'Variantlar',

'errorpagetitle'   => 'Xato',
'returnto'         => '$1 sahifasiga qaytish.',
'tagline'          => '{{SITENAME}} dan',
'help'             => 'Yordam',
'search'           => 'Qidirish',
'searchbutton'     => 'Qidirish',
'go'               => "O'tish",
'searcharticle'    => 'O‘tish',
'history'          => 'Sahifa tarixi',
'history_short'    => 'Tarix',
'printableversion' => 'Bosma uchun versiya',
'permalink'        => 'Doimiy ishorat',
'print'            => 'Chop et',
'view'             => 'Koʻrish',
'edit'             => 'Tahrirlash',
'create'           => 'Yaratish',
'editthispage'     => 'Sahifani tahrirlash',
'create-this-page' => 'Bu sahifani yarat',
'delete'           => 'O‘chirish',
'deletethispage'   => 'Bu sahifani oʻchir',
'protect'          => 'Himoyalash',
'protect_change'   => 'o‘zgartirish',
'protectthispage'  => 'Ushbu sahifani himoyalash',
'unprotect'        => 'Himoyadan chiqarish',
'newpage'          => 'Yangi sahifa',
'talkpage'         => 'Bu sahifa haqida munozara',
'talkpagelinktext' => 'Munozara',
'specialpage'      => 'Maxsus sahifa',
'personaltools'    => 'Shaxsiy uskunalar',
'postcomment'      => 'Yangi boʻlim',
'talk'             => 'Munozara',
'views'            => 'Ko‘rinishlar',
'toolbox'          => 'Asboblar',
'categorypage'     => 'Turkum sahifasi',
'viewtalkpage'     => 'Munozara',
'otherlanguages'   => 'Boshqa tillarda',
'redirectedfrom'   => '($1dan yoʻnaltirildi)',
'redirectpagesub'  => 'Yoʻnaltiruvchi sahifa',
'lastmodifiedat'   => 'Bu sahifa oxirgi marta $2, $1 sanasida tahrirlangan.',
'viewcount'        => 'Bu sahifaga {{PLURAL:$1|bir marta|$1 marta}} murojaat qilingan.',
'protectedpage'    => 'Himoyalangan sahifa',
'jumpto'           => 'Oʻtish:',
'jumptonavigation' => 'foydalanish',
'jumptosearch'     => 'Qidir',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} haqida',
'aboutpage'            => 'Project:Haqida',
'copyright'            => 'Kontent $1 ostidadir.',
'copyrightpage'        => '{{ns:project}}:Mualliflik huquqlari',
'currentevents'        => 'Joriy hodisalar',
'currentevents-url'    => 'Project:Joriy hodisalar',
'disclaimers'          => 'Ogohlantirishlar',
'disclaimerpage'       => 'Project:Umumiy ogohlantirish',
'edithelp'             => 'Tahrirlash yordami',
'edithelppage'         => 'Help:Tahrirlash',
'helppage'             => 'Help:Mundarija',
'mainpage'             => 'Bosh sahifa',
'mainpage-description' => 'Bosh sahifa',
'portal'               => 'Jamoa portali',
'portal-url'           => 'Project:Jamoa portali',
'privacy'              => 'Konfidensiallik siyosati',
'privacypage'          => 'Project:Konfidensiallik siyosati',

'ok'                      => 'OK',
'retrievedfrom'           => ' "$1" dan olindi',
'youhavenewmessages'      => 'Sizga $1 keldi ($2).',
'newmessageslink'         => 'yangi xabarlar',
'newmessagesdifflink'     => 'soʻnggi oʻzgarish',
'youhavenewmessagesmulti' => 'Sizga yangi xat keldi: $1',
'editsection'             => 'tahrirlash',
'editold'                 => 'tahrir',
'editlink'                => 'tahrirla',
'viewsourcelink'          => 'manbasini koʻr',
'editsectionhint'         => 'Boʻlimni tahrirlash: $1',
'toc'                     => 'Mundarija',
'showtoc'                 => 'koʻrsatish',
'hidetoc'                 => 'yashirish',
'collapsible-collapse'    => 'Yashir',
'collapsible-expand'      => 'Koʻrsat',
'page-atom-feed'          => '«$1» — Atom-lenta',
'red-link-title'          => '$1 (sahifa yaratilmagan)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Maqola',
'nstab-user'      => 'Foydalanuvchi sahifasi',
'nstab-special'   => 'Maxsus sahifa',
'nstab-project'   => 'Vikipediya',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Xabar',
'nstab-template'  => 'Andoza',
'nstab-help'      => 'Yordam sahifasi',
'nstab-category'  => 'Turkum',

# General errors
'error'              => 'Xato',
'badtitle'           => 'Notoʻgʻri sarlavha',
'viewsource'         => 'Manbasini koʻrish',
'protectedpagetext'  => 'Bu sahifa tahrirlashdan saqlanish maqsadida qulflangan.',
'viewsourcetext'     => "Siz bu sahifaning manbasini ko'rishingiz va uni nusxasini olishingiz mumkin:",
'namespaceprotected' => "Sizda '''$1''' nomfazosi sahifalarini tahrirlash huquqi yoʻq",
'customcssprotected' => 'Sizda uchbu CSS sahifani tahrirlash huquqi yoʻq, chunki bu yerda boshqa foydalanuvchining shaxsiy moslamalari saqlanadi.',
'customjsprotected'  => 'Sizda uchbu JavaScript sahifani tahrirlash huquqi yoʻq, chunki bu yerda boshqa foydalanuvchining shaxsiy moslamalari saqlanadi.',

# Login and logout pages
'logouttext'              => "'''Siz saytdan muvaffaqiyatli chiqdingiz.'''

{{SITENAME}} saytidan anonim holda foydalanishda davom etishindiz mumkin. Yoki siz yana hozirgi yoki boshqa foydalanuvchi nomi bilan qaytadan tizimga kirishingiz mumkin.
Shuni e'tiborga olingki, ayrim sahifalar siz brauzeringiz keshini tozalamaguningizga qadar xuddi tizimga kirganingizdagidek ko'rinishda davom etaverishi mumkin.",
'welcomecreation'         => '== Xush kelibsiz, $1! ==
Siz yangi hisob yaratdingiz.
[[Special:Preferences|{{SITENAME}}dagi shaxsiy moslamalaringizni]] oʻzgartirish yodingizdan chiqmasin.',
'yourname'                => 'Foydalanuvchi nomi',
'yourpassword'            => 'Maxfiy soʻz',
'yourpasswordagain'       => 'Maxfiy so‘zni qayta kiriting:',
'remembermypassword'      => 'Hisob ma’lumotlarini ushbu kompyuterda eslab qolish (eng ko‘pi bilan $1 {{PLURAL:$1|kun|kun}} uchun)',
'login'                   => 'Kirish',
'nav-login-createaccount' => 'Kirish / Hisob yaratish',
'loginprompt'             => "{{SITENAME}}ga kirish uchun kukilar yoqilgan bo'lishi kerak.",
'userlogin'               => 'Kirish / Hisob yaratish',
'userloginnocreate'       => 'Kirish',
'logout'                  => 'Chiqish',
'userlogout'              => 'Chiqish',
'notloggedin'             => 'Kirish amalga oshirilmadi',
'nologin'                 => "Hisobingiz yoʻqmi? '''$1'''.",
'nologinlink'             => 'Hisob yaratish',
'createaccount'           => 'Hisob yaratish',
'gotaccount'              => "Hisobingiz bormi? '''$1'''.",
'gotaccountlink'          => 'Kirish',
'userlogin-resetlink'     => 'Kirish maʻlumotlaringiz esdan chiqdimi?',
'loginsuccesstitle'       => 'Kirish muvaffaqiyatli amalga oshdi',
'loginsuccess'            => "'''{{SITENAME}}ga \"\$1\" foydalanuvchi nomi bilan kirdingiz.'''",
'nosuchusershort'         => '"$1" ismli ishtirokchi yoʻq.
Xatosiz yozishga urinib koʻring.',
'wrongpassword'           => 'Kiritgan mahfiy soʻzingiz notoʻgʻri. Iltimos, qaytadan kiritib koʻring.',
'mailmypassword'          => 'Yangi parolni e-mail qil',
'emailauthenticated'      => 'Sizning e-mail manzilingiz $2, $3 da tasdiqlangan.',
'loginlanguagelabel'      => 'Til: $1',

# Change password dialog
'resetpass'                 => 'Maxfiy soʻzni oʻzgartirish',
'retypenew'                 => 'Yangi mahfiy soʻzni qayta tering:',
'resetpass-submit-loggedin' => 'Maxfiy soʻzni oʻzgartirish',

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
'image_tip'       => 'Qoʻshilgan tasvir',
'media_tip'       => 'Faylga ishorat',
'sig_tip'         => 'Imzoingiz va sana',

# Edit pages
'summary'                    => 'Qisqa izoh:',
'minoredit'                  => 'Bu kichik tahrir',
'watchthis'                  => 'Sahifani kuzatish',
'savearticle'                => 'Saqlash',
'preview'                    => 'Ko‘rib chiqish',
'showpreview'                => 'Ko‘rib chiqish',
'showdiff'                   => 'O‘zgarishlarni ko‘rsatish',
'anoneditwarning'            => "'''Diqqat:''' Siz tizimga kirmagansiz. Ushbu sahifa tarixida Sizning IP manzilingiz yozib qolinadi.",
'blockedtext'                => "'''Siz (foydalanuvchi ismingiz yoki IP manzilingiz) tahrir qilishdan chetlashtirildingiz.'''

Sizni $1 chetlashtirdi. Bunga sabab: ''$2''.

* Chetlashtirish muddati boshi: $8
* Chetlashtirish muddati yakuni: $6

Siz $1 yoki boshqa [[{{MediaWiki:Grouppage-sysop}}|administrator]] bilan bogʻlanib, arz qilishingiz mumkin.
You cannot use the 'e-mail this user' feature unless a valid e-mail address is specified in your [[Special:Preferences|account preferences]] and you have not been blocked from using it.
Sizning hozirgi IP manzilingiz - $3, chetlashtirish raqamingiz - #$5. Arizaga bularni ilova qilishingiz mumkin.",
'newarticle'                 => '(Yangi)',
'newarticletext'             => "Bu sahifa hali mavjud emas.
Sahifani yaratish uchun quyida matn kiritishingiz mumkin (qo'shimcha axborot uchun [[{{MediaWiki:Helppage}}|yordam sahifasini]] ko'ring).
Agar bu sahifaga xatolik sabab kelgan bo'lsangiz brauzeringizning '''orqaga''' tugmasini bosing.",
'noarticletext'              => 'Bu sahifada hozircha hech qanday matn yoʻq. Siz bu sarlavhani boshqa sahifalardan [[Special:Search/{{PAGENAME}}|qidirishingiz]], <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} tegishli loglarga qarashingiz] yoki bu sahifani [{{fullurl:{{FULLPAGENAME}}|action=edit}} tahrirlashingiz]</span> mumkin.',
'clearyourcache'             => "'''Etibor bering:''' O'zgartirishlaringiz ko'rish uchun, yangi moslamalaringizning saqlashdan keyin, brauser keshini tozalash kerak:<br />
'''Mozilla / Firefox:''' ''Ctrl+Shift+R'', '''IE:''' ''Ctrl+F5'', '''Safari:''' ''Cmd+Shift+R'', '''Konqueror:''' ''F5'', '''Opera:''' ''Tools → Preferences'' orqali keshni tozalang.",
'previewnote'                => "'''Bu shunchaki ko‘rib chiqish. O‘zgartirishlar hali saqlangani yo‘q!'''",
'editing'                    => '$1 tahrirlanmoqda',
'editingsection'             => '$1 (boʻlim) tahrirlanmoqda',
'copyrightwarning'           => "Iltimos, {{SITENAME}}ga yuklangan har qanday axborot $2 ostida tarqatilishiga diqqat qiling (batafsil ma'lumot uchun $1ni ko'ring).
Agar yozganlaringiz keyinchalik tahrir qilinishi va qayta tarqatilishiga rozi bo'lmasangiz, u holda bu yerga yozmang.<br />
Siz shuningdek bu yozganlaringiz sizniki yoki erkin litsenziya ostida ekanligini va'da qilmoqdasiz.
'''MUALLIFLIK HUQUQLARI BILAN HIMOYALANGAN ISHLARNI ZINHOR BERUXSAT YUBORMANG!'''",
'copyrightwarning2'          => "Iltimos, shuni esda tutingki, {{SITENAME}} sahifalaridagi barcha matnlar boshqa foydalanuvchilar tomonidan tahrirlanishi, almashtirilishi yoki o'chirilishi mumkin. Agar siz yozgan ma'lumotlaringizni bunday tartibda tahrirlanishiga rozi bo'lmasangiz, unda uni bu yerga joylashtirmang.<br />
Bundan tashqari, siz ushbu ma'lumotlarni o'zingiz yozgan bo'lishingiz yoki ruxsat berilgan internet manzilidan yoki shu kabi erkin resursdan nusxa olgan bo'lishingiz lozim (Qo'shimcha ma'lumotlar ushun $1 sahifasiga murojaat qiling).
'''MUALLIFLIK HUQUQI QO'YILGAN ISHLARNI RUXSATSIZ BU YERGA JOYLASHTIRMANG!'''",
'templatesused'              => 'Ushbu sahifada foydalanilgan {{PLURAL:$1|andoza|andozalar}}:',
'template-protected'         => '(himoyalangan)',
'template-semiprotected'     => '(yarim-himoyalangan)',
'nocreatetext'               => 'Ushbu sayt yangi sahifa yaratishni taqiqlagan.
Ortga qaytib, mavjud sahifani tahrirlashingiz yoki [[Special:UserLogin|tizimga kirishingiz]] mumkin.',
'recreate-moveddeleted-warn' => "'''Diqqat: Siz avval yoʻqotilgan sahifani yana yaratmoqchisiz.'''

Bu sahifani yaratishda davom etishdan avval uning nega avval yoʻqotilgani bilan qiziqib koʻring.
Qulaylik uchun quyida yoʻqotilish qaydlari keltirilgan:",

# History pages
'viewpagelogs'           => 'Ushbu sahifaga doir qaydlarni koʻrsat',
'currentrev'             => 'Hozirgi koʻrinishi',
'currentrev-asof'        => '$1dagi, joriy koʻrinishi',
'revisionasof'           => '$1 paytdagi koʻrinishi',
'previousrevision'       => '←Avvalgi koʻrinishi',
'nextrevision'           => 'Yangiroq koʻrinishi→',
'currentrevisionlink'    => 'Hozirgi koʻrinishi',
'cur'                    => 'joriy',
'next'                   => 'keyingi',
'last'                   => 'oxirgi',
'page_first'             => 'birinchi',
'page_last'              => 'oxirgi',
'histlegend'             => 'Farqlar: solishtirish uchun kerakli radiobokslarni belgilang va pastdagi tugmani yoki Enterni bosing.<br />
Bu yerda: (joriy) = hozirgi koʻrinish bilan farq,
(oxirgi) = avvalgi koʻrinish bilan farq, k = kichkina tahrir.',
'history-fieldset-title' => 'Tarixni koʻr',
'history-show-deleted'   => 'Faqat o‘chirilganlari',
'histfirst'              => 'Eng avvalgi',
'histlast'               => 'Eng soʻnggi',
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(boʻsh)',

# Revision feed
'history-feed-item-nocomment' => '$1 $2 da',

# Revision deletion
'rev-delundel'              => 'koʻrsat/yashir',
'rev-showdeleted'           => 'koʻrsatish',
'revdelete-log'             => 'Sabab:',
'revdelete-otherreason'     => 'Boshqa/qoʻshimcha sabab:',
'revdelete-reasonotherlist' => 'Boshqa sabab',

# Diffs
'history-title'           => '"$1"ning tarixi',
'difference'              => '(Koʻrinishlar orasidagi farq)',
'lineno'                  => 'Qator $1:',
'compareselectedversions' => 'Tanlangan versiyalarni solishtir',
'editundo'                => 'qaytar',

# Search results
'searchresults'                  => 'Qidiruv natijalari',
'searchresults-title'            => '"$1" uchun qidiruv natijalari',
'searchresulttext'               => "{{SITENAME}}da qidirish haqida qo'shimcha ma'lumotga ega bo'lishini xoxlasangiz, [[{{MediaWiki:Helppage}}|{{SITENAME}}da qidiruv]] sahifasini o'qing.",
'searchsubtitle'                 => '\'\'\'[[:$1]]\'\'\'ni qidirdingiz ([[Special:Prefixindex/$1|"$1" bilan boshlanadigan sahifalar]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|"$1"ga bogʻlangan sahifalar]])',
'searchsubtitleinvalid'          => "'''$1'''ni qidirdingiz",
'notitlematches'                 => 'Bunday sarlavha topilmadi',
'notextmatches'                  => 'Bunday matn topilmadi',
'prevn'                          => 'oldingi $1',
'nextn'                          => 'keyingi {{PLURAL:$1|$1}}',
'prevn-title'                    => 'Avvalgi $1 {{PLURAL:$1|natija|natijalar}}',
'nextn-title'                    => 'Keyingi $1 {{PLURAL:$1|natija|natijalar}}',
'shown-title'                    => 'Har sahifada $1 natija koʻrsat',
'viewprevnext'                   => 'Koʻrish ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'              => 'Qidiruv shartlari',
'searchmenu-exists'              => "'''Ushbu vikida \"[[:\$1]]\" nomli sahifa mavjud.'''",
'searchmenu-new'                 => "'''Ushbu vikida \"[[:\$1]]\" sahifani yarat!'''",
'searchhelp-url'                 => 'Help:Mundarija',
'searchprofile-articles'         => 'Asosiy sahifalar',
'searchprofile-project'          => 'Yordam va proekt sahifalari.',
'searchprofile-images'           => 'Multimediya',
'searchprofile-everything'       => 'Har yerda',
'searchprofile-advanced'         => 'Kengaytirilgan',
'searchprofile-articles-tooltip' => '$1da qidir',
'searchprofile-project-tooltip'  => '$1da qidir',
'searchprofile-images-tooltip'   => 'Fayllarni qidir',
'search-result-size'             => '$1 ({{PLURAL:$2|1 soʻz|$2 soʻz}})',
'search-redirect'                => '(yoʻnaltirish $1)',
'search-section'                 => '($1 boʻlimi)',
'search-suggest'                 => 'Balki buni nazarda tutgandirsiz: $1',
'search-interwiki-default'       => '$1 natijalar:',
'searcheverything-enable'        => 'Barcha nomfazolarda qidir',
'searchall'                      => 'barchasi',
'showingresults'                 => "#<b>$2</b> boshlanayotgan <b>$1</b> natijalar ko'rsatilyapti.",
'showingresultsheader'           => "$4 uchun {{PLURAL:$5|'''$3'''dan '''$1''' natija|'''$3'''dan '''$1 - $2''' natijalar}}",
'search-nonefound'               => 'Talabga javob beradigan natija topilmadi.',
'powersearch'                    => 'Qidiruv',
'powersearch-legend'             => 'Kengaytirilgan qidiruv',
'powersearch-ns'                 => 'Bu nom-fazolarda izla:',
'powersearch-redir'              => 'Yoʻnaltirishlarni koʻrsat',
'powersearch-field'              => 'Qidir',

# Preferences page
'preferences'                 => 'Moslamalar',
'mypreferences'               => 'Moslamalarim',
'prefs-edits'                 => 'Tahrirlar soni',
'changepassword'              => 'Maxfiy soʻzni oʻzgartirish',
'prefs-skin'                  => 'Tashqi ko‘rinishi',
'datedefault'                 => 'Farqi yoʻq',
'prefs-datetime'              => 'Sana va vaqt',
'prefs-personal'              => 'Shaxsiy ma’lumotlar',
'prefs-rc'                    => 'Yangi o‘zgartirishlar',
'prefs-watchlist'             => "Kuzatuv ro'yxati",
'prefs-watchlist-days-max'    => 'Eng ko‘pi $1 {{PLURAL:$1|kun|kun}}',
'prefs-misc'                  => 'Boshqa moslamalar',
'prefs-resetpass'             => 'Maxfiy soʻzni oʻzgartirish',
'prefs-changeemail'           => 'E-mail manzilingizni o‘zgartirish',
'prefs-rendering'             => 'Tashqi ko‘rinishi',
'saveprefs'                   => 'Saqlash',
'resetprefs'                  => 'Bekor qilish',
'restoreprefs'                => 'Barcha moslamalarni dastlabki holiga qaytar',
'prefs-editing'               => 'Tahrirlash',
'prefs-edit-boxsize'          => 'Tahrir oynasining oʻlchami',
'rows'                        => 'Qatorlar soni:',
'columns'                     => 'Ustunlar soni:',
'searchresultshead'           => 'Qidiruv natijalari',
'recentchangesdays-max'       => 'Eng koʻpi $1 kun',
'recentchangescount'          => 'Sukut boʻyicha koʻrsatiladigan tahrirlar soni',
'timezonelegend'              => 'Vaqt mintaqangiz:',
'localtime'                   => 'Mahalliy vaqt:',
'servertime'                  => 'Server vaqti:',
'timezoneregion-africa'       => 'Afrika',
'timezoneregion-america'      => 'Amerika',
'timezoneregion-antarctica'   => 'Antarktika',
'timezoneregion-arctic'       => 'Arktika',
'timezoneregion-asia'         => 'Osiyo',
'timezoneregion-atlantic'     => 'Atlantika okeani',
'timezoneregion-australia'    => 'Avstraliya',
'timezoneregion-europe'       => 'Yevropa',
'timezoneregion-indian'       => 'Hind okeani',
'timezoneregion-pacific'      => 'Tinch okeani',
'allowemail'                  => 'Boshqa foydalanuvchilardan elektron xat olishga ruxsat berish',
'prefs-searchoptions'         => 'Qidiruv shartlari',
'prefs-namespaces'            => 'Nomfazolar',
'defaultns'                   => 'Aks holda quyidagi nomfazolarda qidir:',
'prefs-files'                 => 'Fayllar',
'prefs-emailconfirm-label'    => 'Elektron pochta manzilini tasdiqlash:',
'prefs-textboxsize'           => 'Tahrir oynasining oʻlchami',
'youremail'                   => 'E-mail:',
'username'                    => 'Foydalanuvchi nomi',
'uid'                         => 'Identifikator:',
'prefs-memberingroups'        => 'Qaysi {{PLURAL:$1|guruh|guruhlar}} aʼzosi:',
'prefs-registration'          => 'Hisob ochilgan vaqt',
'yourrealname'                => 'Haqiqiy ism *:',
'yourlanguage'                => 'Til:',
'yournick'                    => 'Yangi imzo',
'prefs-help-signature'        => 'Munozara sahifalarida imzo "<nowiki>~~~~</nowiki>" orqali qoʻyiladi (u sizning imzoingiz va joriy vaqtga aylantiriladi).',
'yourgender'                  => 'Jinsi:',
'gender-unknown'              => 'Koʻrsatilmagan',
'gender-male'                 => 'Erkak',
'gender-female'               => 'Ayol',
'email'                       => 'E-mail:',
'prefs-help-email-required'   => 'E-mail manzilni koʻrsatish shart emas',
'prefs-info'                  => 'Asosiy maʼlumot',
'prefs-signature'             => 'Imzo',
'prefs-dateformat'            => 'Sana formati',
'prefs-timeoffset'            => 'Vaqt farqi',
'prefs-advancedediting'       => 'Qoʻshimcha moslamalar',
'prefs-advancedrc'            => 'Qoʻshimcha moslamalar',
'prefs-advancedrendering'     => 'Qoʻshimcha moslamalar',
'prefs-advancedsearchoptions' => 'Qoʻshimcha moslamalar',
'prefs-advancedwatchlist'     => 'Qoʻshimcha moslamalar',

# User rights
'userrights-groupsmember' => 'Aʼzolik:',
'userrights-reason'       => 'Sabab:',

# Groups
'group'               => 'Guruh',
'group-user'          => 'Foydalanuvchilar',
'group-autoconfirmed' => 'Tasdiqlangan foydalanuvchilar',
'group-bot'           => 'Botlar',
'group-sysop'         => 'Administratorlar',
'group-bureaucrat'    => 'Rasmiyatchilar',
'group-suppress'      => 'Tekshiruvchilar',

'grouppage-user'          => '{{ns:project}}:Foydalanuvchilar',
'grouppage-autoconfirmed' => '{{ns:project}}:Tasdiqlangan foydalanuvchilar',
'grouppage-bot'           => '{{ns:project}}:Botlar',
'grouppage-sysop'         => '{{ns:project}}:Administratorlar',
'grouppage-bureaucrat'    => '{{ns:project}}:Rasmiyatchilar',
'grouppage-suppress'      => '{{ns:project}}:Tekshiruvchilar',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit'          => 'ushbu sahifani tahrirlash',
'action-move'          => 'bu sahifani koʻchir',
'action-move-subpages' => 'Bu sahifani va uning ostsahifalarini koʻchir',

# Recent changes
'recentchanges'                   => 'Yangi o‘zgartirishlar',
'recentchangestext'               => 'Ushbu sahifada siz eng so‘nggi o‘zgartirishlarni ko‘rishingiz mumkin.',
'recentchanges-label-newpage'     => 'Bu tahrir orqali yangi sahifa yaratildi',
'recentchanges-label-minor'       => 'Bu kichik tahrir',
'recentchanges-label-bot'         => 'Bu tahrirni bot bajardi',
'recentchanges-label-unpatrolled' => 'Bu tahrir hali tekshirilmagan',
'rcnote'                          => "Quyida $5, $4ga koʻra oxirgi {{PLURAL:$2|kun|'''$2''' kun}} davomida sodir boʻlgan {{PLURAL:$1|'''1''' oʻzgartirish|'''$1''' oʻzgartirishlar}} koʻrsatilgan.",
'rclistfrom'                      => "$1dan boshlab yangi o'zgartirishlarni ko'rsat.",
'rcshowhideminor'                 => 'Kichik tahrirlarni $1',
'rcshowhidebots'                  => '$1 ta bot',
'rcshowhideliu'                   => 'Ro‘yxatdan o‘tgan foydalanuvchilar: $1 ta',
'rcshowhideanons'                 => 'Anonim foydalanuvchilar: $1 ta',
'rcshowhidepatr'                  => 'Tekshirilgan tahrirlarni $1',
'rcshowhidemine'                  => "O'z tahrirlarimni $1",
'rclinks'                         => "Oxirgi $2 kun davomida sodir bo'lgan $1 o'zgartirishlarni ko'rsat.<br />$3",
'diff'                            => 'farq',
'hist'                            => 'tarix',
'hide'                            => 'yashirish',
'show'                            => 'koʻrsat',
'minoreditletter'                 => 'k',
'newpageletter'                   => 'Y',
'rc-enhanced-expand'              => 'Tasfilotlarni koʻrsat (JavaScript talab qilinadi)',
'rc-enhanced-hide'                => 'Tafsilotlolarni yashir',

# Recent changes linked
'recentchangeslinked'          => 'Bogʻlangan oʻzgarishlar',
'recentchangeslinked-feed'     => 'Bogʻliq oʻzgarishlar',
'recentchangeslinked-toolbox'  => 'Bogʻliq oʻzgarishlar',
'recentchangeslinked-title'    => '"$1"ga aloqador oʻzgarishlar',
'recentchangeslinked-noresult' => 'Berilgan davrda bogʻlangan sahifalarda oʻzgarishlar boʻlmagan.',
'recentchangeslinked-summary'  => "Ushbu maxsus sahifa unga bogʻlangan sahifalardagi soʻnggi oʻzgarishlarni koʻrsatadi. [[Special:Watchlist|Kuzatuv roʻyxatingizdagi]] sahifalar '''qalin''' qilib koʻrsatilgan.",
'recentchangeslinked-page'     => 'Sahifa nomi:',

# Upload
'upload'              => 'Fayl yuklash',
'uploadbtn'           => 'Fayl yukla',
'uploaderror'         => 'Yuklashda xatolik',
'uploadlogpage'       => 'Yuklash qaydlari',
'filedesc'            => 'Qisqa izoh',
'filereuploadsummary' => 'Fayldagi oʻzgarishlar:',
'filesource'          => 'Manba:',
'uploadedimage'       => '"[[$1]]" yuklandi',

# Special:ListFiles
'imgfile'               => 'fayl',
'listfiles'             => 'Fayllar roʻyxati',
'listfiles_date'        => 'Sana',
'listfiles_user'        => 'Foydalanuvchi',
'listfiles_size'        => 'Oʻlchami',
'listfiles_description' => 'Taʻrif',

# File description page
'file-anchor-link'          => 'Fayl',
'filehist'                  => 'Fayl tarixi',
'filehist-help'             => 'Faylning biror paytdagi holatini koʻrish uchun tegishli sana/vaqtga bosingiz.',
'filehist-deleteone'        => 'o‘chirish',
'filehist-revert'           => 'qaytarish',
'filehist-current'          => 'joriy',
'filehist-datetime'         => 'Sana/Vaqt',
'filehist-thumb'            => 'Miniatyura',
'filehist-user'             => 'Foydalanuvchi',
'filehist-dimensions'       => 'Oʻlchamlari',
'filehist-filesize'         => 'Fayl hajmi',
'filehist-comment'          => 'Izoh',
'imagelinks'                => 'Fayllarga ishoratlar',
'linkstoimage'              => 'Bu faylga quyidagi {{PLURAL:$1|sahifa|$1 sahifalar}} bogʻlangan:',
'nolinkstoimage'            => 'Bu faylga bogʻlangan sahifalar yoʻq.',
'sharedupload'              => 'This file is from $1 and may be used by other projects.',
'sharedupload-desc-here'    => 'Ushbu fayl $1dan boʻlib, boshqa loyihalarda ham ishlatilishi mumkin.
Uning [$2 fayl tavsifi sahifasidan] olingan tavsifi quyida keltirilgan.',
'uploadnewversion-linktext' => 'Bu faylning yangi versiyasini yukla',

# File reversion
'filerevert-comment' => 'Sabab:',

# File deletion
'filedelete-comment' => 'Sabab:',

# Unused templates
'unusedtemplates' => 'Ishlatilinmagan andozalar',

# Random page
'randompage' => 'Tasodifiy sahifa',

# Statistics
'statistics'              => 'Statistika',
'statistics-header-users' => 'Foydalanuvchilar statistikasi',

'disambiguationspage' => '{{ns:template}}:Disambig',

# Miscellaneous special pages
'nbytes'                  => '$1 bayt',
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
'move'                    => 'Ko‘chirish',
'movethispage'            => 'Bu sahifani koʻchir',
'pager-newer-n'           => '{{PLURAL:$1|yangiroq 1|yangiroq $1}}',
'pager-older-n'           => '{{PLURAL:$1|eskiroq 1|eskiroq $1}}',

# Book sources
'booksources'    => 'Kitob manbaʻlar',
'booksources-go' => 'O‘tish',

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
'allpagessubmit' => 'Oʻt',
'allpagesprefix' => 'Bunday prefiksli sahifalarni koʻrsat:',

# Special:Categories
'categories'         => 'Turkumlar',
'categoriespagetext' => 'The following {{PLURAL:$1|category contains|categories contain}} pages or media.
[[Special:UnusedCategories|Unused categories]] are not shown here.
Also see [[Special:WantedCategories|wanted categories]].',

# Special:DeletedContributions
'sp-deletedcontributions-contribs' => 'hissa',

# Special:LinkSearch
'linksearch-ns' => 'Nomfazo:',
'linksearch-ok' => 'Qidirish',

# Special:ListUsers
'listusers-submit' => 'Koʻrsat',

# Special:ListGroupRights
'listgrouprights-group'   => 'Guruh',
'listgrouprights-rights'  => 'Huquqlar',
'listgrouprights-members' => '(a’zolar ro‘yxati)',

# E-mail user
'emailuser'   => 'Bu foydalanuvchiga e-maktub joʻnat',
'noemailtext' => "Bu foydalanuvchi e-mail manzil ko'rsatgani yo'q.",
'emailsend'   => 'Joʻnatish',

# Watchlist
'watchlist'        => 'Kuzatuv roʻyxatim',
'mywatchlist'      => 'Kuzatuv roʻyxatim',
'nowatchlist'      => "Kuzatuv ro'yxatingizda hech narsa yo'q.",
'addedwatchtext'   => "\"[[:\$1]]\" sahifasi sizning [[Special:Watchlist|kuzatuv ro'yxatingizga]] qo'shildi. Bu sahifada va unga mos munozara sahifasida bo'ladigan kelajakdagi o'zgarishlar bu yerda ro'yxatga olinadi, hamda bu sahifa topish qulay bo'lishi uchun [[Special:RecentChanges|yangi o'zgarishlar ro'yxati]]da '''qalin''' harflar bilan ko'rsatiladi.

Agar siz bu sahifani kuzatuv ro'yxatingizdan o'chirmoqchi bo'lsangiz \"Kuzatmaslik\" yozuvini bosing.",
'removedwatchtext' => '"[[:$1]]" sahifasi kuzatuv ro\'yxatingizdan o\'chirildi.',
'watch'            => 'kuzatish',
'watchthispage'    => 'Sahifani kuzatish',
'unwatch'          => 'kuzatmaslik',
'wlnote'           => "Below {{PLURAL:$1|is the last change|are the last '''$1''' changes}} in the last {{PLURAL:$2|hour|'''$2''' hours}}, as of $3, $4.",
'wlshowlast'       => 'Oxirgi $1 soatdagi $2 kundagi tahrirlarni ko‘rsatish. $3 tahrirlarni ko‘rsatish',

# Delete
'actioncomplete'        => 'Bajarildi',
'actionfailed'          => 'Jarayon amalga oshmadi',
'deletedtext'           => '"$1" yoʻqotildi.
Yaqinda sodir etilgan yoʻqotishlar uchun $2ni koʻring.',
'dellogpage'            => 'Yoʻqotish qaydlari',
'deletecomment'         => 'Sabab:',
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
'undeletebtn'      => 'Qayta tikla',
'undeletelink'     => 'ko‘rib chiqish/tiklash',
'undeleteviewlink' => "ko'rib chiqish",

# Namespace form on various pages
'namespace'      => 'Soha:',
'invert'         => 'Tanlash tartibini almashtirish',
'blanknamespace' => '(asosiy)',

# Contributions
'contributions' => 'Foydalanuvchining hissasi',
'mycontris'     => 'Hissam',
'contribsub2'   => '$1 uchun ($2)',

'sp-contributions-newbies'  => 'Faqatgina yangi foydalanuvchilarning hissalarini koʻrsat',
'sp-contributions-blocklog' => 'Chetlashtirish qaydlari',
'sp-contributions-talk'     => 'munozara',
'sp-contributions-search'   => 'Hissalarni qidir',
'sp-contributions-username' => 'IP manzil yoki foydalanuvchi ismi:',
'sp-contributions-submit'   => 'Qidir',

# What links here
'whatlinkshere'         => "Bu sahifaga bog'langan sahifalar",
'whatlinkshere-title'   => '"$1"ga bogʻlangan sahifalar',
'whatlinkshere-page'    => 'Sahifa:',
'linkshere'             => "Quyidagi sahifalar '''[[:$1]]''' sahifasiga bog'langan:",
'nolinkshere'           => "'''[[:$1]]''' sahifasiga hech qaysi sahifa bog‘lanmagan.",
'isredirect'            => 'yoʻnaltiruvchi sahifa',
'istemplate'            => 'qoʻshimcha',
'whatlinkshere-links'   => '← ishoratlar',
'whatlinkshere-filters' => 'Filtrlar',

# Block/unblock
'blockip'                  => 'Foydalanuvchini chetlashtir',
'ipboptions'               => '2 soat:2 hours,1 kun:1 day,3 kun:3 days,1 hafta:1 week,2 hafta:2 weeks,1 oy:1 month,3 oy:3 months,6 oy:6 months,1 yil:1 year,cheksiz:infinite',
'ipblocklist'              => 'Chetlashtirilgan IP manzillari va foydalanuvchilar',
'blocklink'                => 'chetlashtir',
'contribslink'             => 'hissasi',
'blocklogpage'             => 'Chetlashtirish qaydlari',
'block-log-flags-nocreate' => 'hisob ochish toʻxtatilgan',

# Move page
'movearticle'    => "Sahifani ko'chirish",
'movepagebtn'    => 'Sahifani koʻchir',
'pagemovedsub'   => 'Koʻchirildi',
'movepage-moved' => '\'\'\'"$1" nomli sahifa "$2" nomli sahifaga koʻchirildi\'\'\'',
'movelogpage'    => 'Koʻchirish qaydlari',
'movereason'     => 'Sabab:',
'revertmove'     => 'qaytar',

# Export
'export' => 'Sahifalar eksporti',

# Namespace 8 related
'allmessagesname' => 'Ism',

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
'tooltip-pt-login'                => 'Bu majburiyat mavjud bo‘lmasa-da, kirishingiz taklif qilinadi.',
'tooltip-pt-anonlogin'            => "Bu majburiyat bo'lmasada, kirishingiz taklif qilinadi.",
'tooltip-pt-logout'               => 'Chiqish',
'tooltip-ca-talk'                 => 'Sahifa matni borasida munozara',
'tooltip-ca-edit'                 => "Siz bu sahifani tahrirlashingiz mumkin. Iltimos, saqlashdan oldim ko'rib chiqish tugmasidan foydalaning.",
'tooltip-ca-addsection'           => 'Yangi boʻlim och',
'tooltip-ca-viewsource'           => "Bu sahifa himoyalangan. Siz uning manbasini ko'rishingiz mumkin.",
'tooltip-ca-history'              => 'Bu sahifaning oldingi versiyalari.',
'tooltip-ca-protect'              => 'Bu sahifani himoyalash',
'tooltip-ca-delete'               => 'Ushbu sahifani o‘chirib tashlash',
'tooltip-ca-undelete'             => "Bu sahifa o'chirilmasdan oldin qilingan tahrirlarni tiklash",
'tooltip-ca-move'                 => 'Bu sahifani koʻchir',
'tooltip-ca-watch'                => "Bu sahifani kuzatuv ro'yxatingizga qo'shish",
'tooltip-ca-unwatch'              => "Bu sahifani kuzatuv ro'yxatingizga o'chirish",
'tooltip-search'                  => '{{SITENAME}}da qidirish',
'tooltip-search-go'               => 'Xuddi shu nomli sahifa bor boʻlsa, uni och',
'tooltip-search-fulltext'         => 'Sahifalarda ushbu matnni izlash',
'tooltip-p-logo'                  => 'Bosh sahifaga o‘tish',
'tooltip-n-mainpage'              => 'Bosh sahifaga oʻtish',
'tooltip-n-mainpage-description'  => 'Bosh sahifaga o‘tish',
'tooltip-n-portal'                => 'Loyiha haqida, nimalar qilishingiz mumkin, nimalarni qayerdan topish mumkin',
'tooltip-n-currentevents'         => 'Joriy hodisalar haqida ma’lumot olish',
'tooltip-n-recentchanges'         => 'Wikidagi eng so‘nggi o‘zgartirishlar ro‘yxati',
'tooltip-n-randompage'            => 'Tasodifiy sahifani yuklash',
'tooltip-n-help'                  => 'O‘rganish uchun manzil',
'tooltip-t-whatlinkshere'         => "Bu sahifaga bog'langan sahifalar ro'yxati",
'tooltip-t-recentchangeslinked'   => "Bu sahifa bog'langan sahifalardagi yangi o'zgarishlar",
'tooltip-feed-rss'                => "Bu sahifa uchun RSS ta'minot",
'tooltip-feed-atom'               => "Bu sahifa uchun Atom ta'minot",
'tooltip-t-contributions'         => "Bu foydalanuvchinig qo'shgan hissasini ko'rish",
'tooltip-t-emailuser'             => 'Ushbu foydalanuvchiga xat jo‘natish',
'tooltip-t-upload'                => 'Rasmlar yoki media fayllar yuklash',
'tooltip-t-specialpages'          => 'Maxsus sahifalar ro‘yxati',
'tooltip-t-print'                 => 'Ushbu sahifaning bosma uchun versiyasi',
'tooltip-t-permalink'             => 'Sahifaning ushbu versiyasiga doimiy ishorat',
'tooltip-ca-nstab-main'           => 'Sahifani ko‘rish',
'tooltip-ca-nstab-user'           => "Foydalanuvchi sahifasini ko'rish",
'tooltip-ca-nstab-media'          => "Media sahifasini ko'rish",
'tooltip-ca-nstab-special'        => 'Bu maxsus sahifa, uni tahrirlay olmaysiz.',
'tooltip-ca-nstab-project'        => "Loyiha sahifasini ko'rish",
'tooltip-ca-nstab-image'          => "Rasm sahifasini ko'rish",
'tooltip-ca-nstab-mediawiki'      => "Tizim xabarini ko'rish",
'tooltip-ca-nstab-template'       => 'Andozani koʻrish',
'tooltip-ca-nstab-help'           => "Yordam sahifasini ko'rish",
'tooltip-ca-nstab-category'       => 'Turkum sahifasini koʻrish',
'tooltip-minoredit'               => 'Kichik o‘zgartirish sifatida belgilash',
'tooltip-save'                    => "O'zgarishlarni saqlash",
'tooltip-preview'                 => "O'zgarishlarni saqlash. Iltimos saqlashdan oldin uni ishlating!",
'tooltip-diff'                    => "Matnga qanday o'zgarishlar kiritganligingizni ko'rish.",
'tooltip-compareselectedversions' => "Bu sahifaning ikki tanlangan versiyalari o'rtasidagi farqni ko'rish.",
'tooltip-watch'                   => 'Ushbu sahifani kuzatuv ro‘yxatingizga qo‘shish',
'tooltip-recreate'                => "Bu sahifani u o'chirilgan bo'lishiga qaramasdan qayta yaratish",
'tooltip-summary'                 => 'Qisqa mazmun kiriting',

# Attribution
'others' => 'boshqalar',

# Info page
'pageinfo-title'            => '"$1" sahifasi haqida maʼlumot',
'pageinfo-header-edits'     => 'Tahrirlar',
'pageinfo-header-watchlist' => 'Kuzatuv roʻyxati',
'pageinfo-subjectpage'      => 'Sahifa:',
'pageinfo-talkpage'         => 'Munozara sahifasi',
'pageinfo-watchers'         => 'Kuzatuvchilar soni',
'pageinfo-edits'            => 'Tahrirlar soni',

# Browsing diffs
'previousdiff' => '← Avvalgi tahrir',
'nextdiff'     => 'Keyingi tahrir →',

# Media information
'imagemaxsize'   => 'Tasvir taʼrifi sahifasidagi tasvirning oʻlchami:',
'thumbsize'      => 'Tasvirning kichiklashtirilgan versiyasining oʻlchami:',
'file-info-size' => '$1 × $2 piksel, fayl hajmi: $3, MIME tipi: $4',
'file-nohires'   => 'Bundan kattaroq tasvir yoʻq.',
'svg-long-desc'  => 'SVG fayl, asl oʻlchamlari $1 × $2 piksel, fayl hajmi: $3',
'show-big-image' => 'Asl hajmdagi tasvir',

# Special:NewFiles
'ilsubmit' => 'Qidirish',

# Metadata
'metadata'          => 'Metama’lumot',
'metadata-expand'   => 'Batafsil axborot koʻrsat',
'metadata-collapse' => 'Batafsil axborotni yashir',

# EXIF tags
'exif-imagewidth'  => 'Eni',
'exif-imagelength' => 'Boʻyi',
'exif-artist'      => 'Muallif',
'exif-source'      => 'Manba',
'exif-iimcategory' => 'Turkum',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Shimoliy kenglik',
'exif-gpslatitude-s' => 'Janubiy kenglik',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Sharqiy uzunlik',
'exif-gpslongitude-w' => 'Gʻarbiy uzunlik',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometr',
'exif-gpsdestdistance-m' => 'Mil',

'exif-iimcategory-clj' => 'Jinoyat va qonun',
'exif-iimcategory-dis' => 'Halokatlar',
'exif-iimcategory-fin' => 'Iqtisodiyot va biznes',
'exif-iimcategory-edu' => 'Maʼrifat',
'exif-iimcategory-evn' => 'Atrofimizdagi olam',
'exif-iimcategory-hum' => 'Inson huquqlari',
'exif-iimcategory-lab' => 'Mehnat',
'exif-iimcategory-lif' => 'Turmush tarzi va hordiq',
'exif-iimcategory-pol' => 'Siyosat',
'exif-iimcategory-rel' => 'Din va imon',
'exif-iimcategory-sci' => 'Fan va texnologiyalar',
'exif-iimcategory-spo' => 'Sport',
'exif-iimcategory-wea' => 'Ob-havo',

# External editor support
'edit-externally'      => 'Bu faylni tashqi dasturiy ilovalar yordamida tahrirla',
'edit-externally-help' => "(Batafsil ma'lumotlar uchun [//www.mediawiki.org/wiki/Manual:External_editors bu yerga] qarang)",

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Barcha',
'namespacesall' => 'Barchasi',
'monthsall'     => 'barchasi',

'unit-pixel' => 'piksel',

# Multipage image navigation
'imgmultipageprev' => '← oldingi sahifa',
'imgmultipagenext' => 'keyingi sahifa →',
'imgmultigoto'     => '$1 sahifasiga oʻtish',

# Table pager
'table_pager_next'  => 'Keyingi sahifa',
'table_pager_prev'  => 'Oldingi sahifa',
'table_pager_first' => 'Birinchi sahifa',
'table_pager_last'  => 'Oxirgi sahifa',

# Auto-summaries
'autoredircomment' => '[[$1]]ga yoʻnaltirildi',
'autosumm-new'     => '"$1" yozuvi orqali yangi sahifa yaratildi',

# Watchlist editing tools
'watchlisttools-edit' => 'Kuzatuv roʻyxatimni koʻrish/oʻzgartirish',
'watchlisttools-raw'  => 'Kuzatuv roʻyxatimni tahrirlash',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|munozara]])',

# Special:Version
'version-specialpages' => 'Maxsus sahifalar',

# Special:SpecialPages
'specialpages' => 'Maxsus sahifalar',

# HTML forms
'htmlform-reset' => 'Oʻzgarishlarni bekor qilish',

# New logging system
'logentry-move-move' => '$1 $3 sahifasini $4ga koʻchirdi',

# Feedback
'feedback-close' => 'Bajarildi',

# API errors
'api-error-unknown-code' => 'Noaniq xato: "$1".',
'api-error-unknownerror' => 'Noaniq xato: "$1".',

);
