<?php
/** Gagauz (Gagauz)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Andrijko Z.
 * @author Cuman
 * @author Emperyan
 * @author Reedy
 */

$fallback = 'tr';

$messages = array(
# User preference toggles
'tog-underline'               => 'Baalantıların altını çiz',
'tog-highlightbroken'         => 'Boş baalantıları <a href="" class="new">bu formada</a> (alternativa: bu formada<a href="" class="internal">?</a>) göster.',
'tog-justify'                 => 'Paragrafları düz',
'tog-hideminor'               => 'Küçük diişmäkleri "Bitki diişmäkler" sayfasında sakla',
'tog-extendwatchlist'         => 'İlerlemiş bakmaa listası',
'tog-usenewrc'                => 'İlerlemiş bitki diişmäkler listası (JavaScript uymêêr)',
'tog-numberheadings'          => 'Başlıklara avtomatik nomer yaz',
'tog-showtoolbar'             => 'Diişmäk yapar känä yardımcı tuşları göster. (JavaScript)',
'tog-editondblclick'          => 'Sayfayı çift tuşlayarak diiştirmää başla (JavaScript)',
'tog-editsection'             => 'Bölümleri [diiştir] baalantılarılan diiştirmää hakkı ver',
'tog-editsectiononrightclick' => 'Bölüm başlıına saa tuşla basarak bölümü düzmää izin ver.(JavaScript)',
'tog-showtoc'                 => 'İçindäkiler tablițasını düz<br />(3-tän çok başlıı olan sayfalar için)',
'tog-rememberpassword'        => 'Parolu hatırla (en fazla $1 {{PLURAL:$1|gün|gün}})',
'tog-watchcreations'          => 'Yarattıım sayfaları bakmaa listama ekle',
'tog-watchdefault'            => 'Diişmäk yapılan sayfayı bakmaa listasına ekle',
'tog-watchmoves'              => 'Bakmaa listama ekle o sayfaları angılarını taşıdım',
'tog-watchdeletion'           => 'Sildiim sayfaları bakmaa listama ekle',
'tog-minordefault'            => "Hepsi diişmäkleri 'küçük diişmäk' olarak nışanna",
'tog-previewontop'            => 'Öni siiri diiştirmää penceräsi üstünde göster',
'tog-previewonfirst'          => 'İlk kerä diiştirär känä ön siiri göster',
'tog-nocache'                 => 'Sayfaları keş etmää yasakla',
'tog-enotifwatchlistpages'    => 'Sayfa diişär känä bana e-mail gönder',
'tog-enotifusertalkpages'     => 'Kullanıcı sayfamda diişmäk olar kana bana e-mail gönder',
'tog-enotifminoredits'        => 'Sayfalardaki küçük diişmäklerdä dä bana e-mail gönder',
'tog-enotifrevealaddr'        => 'Bildirmää maillerinde e-mail adresimi göster.',
'tog-shownumberswatching'     => 'İzlään kullanıcı sayısın göster',
'tog-fancysig'                => 'Çii imza (İmzanız görüner nesoy onu yukarda belirttiniz. Sayfanıza avtomatik baalantı yaratılmaycêk)',
'tog-externaleditor'          => 'Düzmää başka editor programmasılan yap',
'tog-externaldiff'            => 'Karşılaştırmakları dış programmalan yap.',
'tog-showjumplinks'           => '"Git" baalantısın işlet',
'tog-uselivepreview'          => 'Tez cannı ön siiri kullan (JavaScript) (êksperimental)',
'tog-forceeditsummary'        => 'Bana haber ver ne zaman ani kısa annatmanı boş braacam',
'tog-watchlisthideown'        => 'Bakmaa listamdan benim diişmäklerimi sakla',
'tog-watchlisthidebots'       => 'Bakmaa listamdan bot diişmäklerini sakla',
'tog-watchlisthideminor'      => 'Bakmaa listamdan küçük diişmäkleri sakla',
'tog-ccmeonemails'            => 'Bana da gönder o e-maillerin kopiyalarını angılarını übür kullanıcılara gönderdim',
'tog-diffonly'                => 'Sayfanın içersindäkini diil läazım göstermää iki versiyanı karşılaştırarak',

'underline-always'  => 'Dayma',
'underline-never'   => 'Hiç bir zaman',
'underline-default' => 'Brauzer karar kabletsin',

# Dates
'sunday'        => 'Pazar',
'monday'        => 'Pazertesi',
'tuesday'       => 'Salı',
'wednesday'     => 'Çarşamba',
'thursday'      => 'Perşembä',
'friday'        => 'Cumaa',
'saturday'      => 'Cumartesi',
'sun'           => 'Paz',
'mon'           => 'Pzt',
'tue'           => 'Sal',
'wed'           => 'Çar',
'thu'           => 'Per',
'fri'           => 'Cumaa',
'sat'           => 'Cts',
'january'       => 'Yanvar',
'february'      => 'Fevral',
'march'         => 'Marta',
'april'         => 'Aprel',
'may_long'      => 'May',
'june'          => 'İyün',
'july'          => 'İyül',
'august'        => 'Avgust',
'september'     => 'Sentäbri',
'october'       => 'Oktäbri',
'november'      => 'Noyabri',
'december'      => 'Dekabri',
'january-gen'   => 'Büük ay',
'february-gen'  => 'Küçük ay',
'march-gen'     => 'Baba Marta',
'april-gen'     => 'Çiçek ay',
'may-gen'       => 'Hederlez',
'june-gen'      => 'Kirez ay',
'july-gen'      => 'Orak ay',
'august-gen'    => 'Harman ay',
'september-gen' => 'Ceviz ay',
'october-gen'   => 'Canavar ay',
'november-gen'  => 'Kasım ay',
'december-gen'  => 'Kırım ay',
'jan'           => 'Yan',
'feb'           => 'Fev',
'mar'           => 'Mar',
'apr'           => 'Apr',
'may'           => 'May',
'jun'           => 'İyn',
'jul'           => 'İyl',
'aug'           => 'Avg',
'sep'           => 'Sen',
'oct'           => 'Okt',
'nov'           => 'Noy',
'dec'           => 'Dek',

# Categories related messages
'pagecategories'         => 'Sayfa {{PLURAL:$1|kategoriyası|kategoriyaları}}',
'category_header'        => '"$1" kategoriyasındaki sayfalar',
'subcategories'          => 'Alt kategoriyalar',
'category-media-header'  => '"$1" kategoriyasındaki media',
'category-empty'         => "''Bu kategoriyada henez bulunmêêr bir yazı yaki media.''",
'hidden-categories'      => '{{PLURAL:$1|Saklı kategoriyalar|Saklı kategoriyalar}}',
'category-subcat-count'  => '{{PLURAL:$2|Bu kategoriyaa girer sadä aşaadaki alt kategoriya.|Bu kategoriya hepsi $2 kategoriyadan {{PLURAL:$1|alt kategoriya|$1 alt kategoriya}}a saab}}',
'category-article-count' => '{{PLURAL:$2|Bu kategoriyaa girer sadä aşaadaki sayfa.|Hepsi $2 den, aşaadaki {{PLURAL:$1|sayfa|$1 sayfa}} bu kategoriyadan.}}',
'listingcontinuesabbrev' => '(devam)',

'mainpagetext'      => "'''MediaWiki başarılan kuruldu.'''",
'mainpagedocfooter' => "Vikilän iş uurunda bilgi almaa için [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] sayfasına bakınız

== Eni başlayanlar için ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'         => 'Uurunda',
'article'       => 'Yazı',
'newwindow'     => '(eni bir pencerädä açılêr)',
'cancel'        => 'Ret',
'moredotdotdot' => 'Taa...',
'mypage'        => 'Benim sayfam',
'mytalk'        => 'Sözleşmäk sayfam',
'anontalk'      => 'Bu IP-nin konuşmaları',
'navigation'    => 'Saytda yol bulmaa',
'and'           => '&#32;hem',

# Cologne Blue skin
'qbfind'         => 'Bul',
'qbbrowse'       => 'Taramaa',
'qbedit'         => 'Diiştir',
'qbpageoptions'  => 'Bu sayfa',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Sayfalarım',
'qbspecialpages' => 'Maasus sayfalar',
'faq'            => 'SSS',
'faqpage'        => 'Project:SSS',

# Vector skin
'vector-view-edit' => 'Diiştir',

'errorpagetitle'    => 'Yannış',
'returnto'          => '$1 dön.',
'tagline'           => '{{SITENAME}} saydından',
'help'              => 'Yardım',
'search'            => 'Ara',
'searchbutton'      => 'Ara',
'go'                => 'Git',
'searcharticle'     => 'Git',
'history'           => 'Sayfanın istoriyası',
'history_short'     => 'İstoriya',
'updatedmarker'     => 'bitki gelişimdän sora enilenmiş',
'info_short'        => 'Bilgi',
'printableversion'  => 'Tiparlanacêk versiya',
'permalink'         => 'Bitki haline baalantı',
'print'             => 'Tiparla',
'edit'              => 'Diiştir',
'create'            => 'Eni yazı yarat',
'editthispage'      => 'Sayfayı diiştir',
'delete'            => 'Sil',
'deletethispage'    => 'Sayfayı sil',
'undelete_short'    => '$1 diişmäk geeri gelsin',
'protect'           => 'Korunmak altına al',
'protect_change'    => 'Diiştir',
'protectthispage'   => 'Sayfayı korunmak altına al',
'unprotect'         => 'Korunmayı kaldır',
'unprotectthispage' => 'Sayfa korunmaanı kaldır',
'newpage'           => 'Eni sayfa',
'talkpage'          => 'Sayfayı diskussiya et',
'talkpagelinktext'  => 'Konuşmaa',
'specialpage'       => 'Maasus Sayfa',
'personaltools'     => 'Personal instrumentlär',
'postcomment'       => 'Yorum ekle',
'articlepage'       => 'Yazıya bak',
'talk'              => 'Dartışma',
'views'             => 'Görünüşler',
'toolbox'           => 'İnstrumentlär',
'userpage'          => 'Kullanıcı sayfasını göster',
'projectpage'       => 'Proekt sayfasına bak',
'imagepage'         => 'Resim sayfasın göster',
'mediawikipage'     => 'Mesaj sayfasını göster',
'templatepage'      => 'Şablon sayfasın göster',
'viewhelppage'      => 'Yardım sayfasına bak',
'categorypage'      => 'Kategoriya sayfasını göster',
'viewtalkpage'      => 'Konuşmaa sayfasına git',
'otherlanguages'    => 'Übür diller',
'redirectedfrom'    => '($1 sayfasınnan yönnendirildi)',
'redirectpagesub'   => 'Yönnendirme sayfası',
'lastmodifiedat'    => 'Bu sayfa bitki kerä $2, $1 datasında enilendi.',
'viewcount'         => 'Bu sayfaya {{PLURAL:$1|bir|$1 }} kerä girildi.',
'protectedpage'     => 'Korunmaklı sayfa',
'jumpto'            => 'Git hem:',
'jumptonavigation'  => 'kullan',
'jumptosearch'      => 'ara',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => '{{SITENAME}} için',
'aboutpage'            => 'Project:Uurunda',
'copyright'            => 'İçersindeki $1 altında.',
'copyrightpage'        => '{{ns:project}}:Avtorluk hakları',
'currentevents'        => 'Hergünkü sluçaylar',
'currentevents-url'    => 'Project:Hergünkü sluçaylar',
'disclaimers'          => 'Cuvapçılık reti',
'disclaimerpage'       => 'Project:Genel cuvapçılık reti',
'edithelp'             => 'Nesoy var nicä diiştirmää?',
'edithelppage'         => 'Help:Nesoy var nicä sayfa diiştirmää',
'helppage'             => 'Help:İçindekilär',
'mainpage'             => 'Baş yaprak',
'mainpage-description' => 'Baş yaprak',
'policy-url'           => 'Project:Politika',
'portal'               => 'Topluluk portalı',
'portal-url'           => 'Project:Topluluk portalı',
'privacy'              => 'Saklamaa politikası',
'privacypage'          => 'Project:Saklamaa politikası',

'badaccess'        => 'İzin kusurluu',
'badaccess-group0' => 'Bu işlemi yapmaa kuvediniz yok.',
'badaccess-groups' => 'O işlem ani yapmaa neetlendiniz var nicä yapılsın sadä {{PLURAL:$2|gruppa|gruppalarınnan}} birinin kullanıcıları tarafınnan: $1.',

'versionrequired'     => 'MediaWiki-nin $1 versiyası läazım',
'versionrequiredtext' => 'MediaWiki-nin $1 versiyası läazım bu sayfayı kullanmaa deyni. Bak [[Special:Version|versiya sayfası]].',

'ok'                      => 'TAMAN',
'retrievedfrom'           => 'Alındı "$1"dän',
'youhavenewmessages'      => 'Var eni <u>$1</u>. ($2)',
'newmessageslink'         => 'eni mesajlar',
'newmessagesdifflink'     => 'Bitki diişmäk',
'youhavenewmessagesmulti' => "$1'de eni mesajınız var.",
'editsection'             => 'diiştir',
'editold'                 => 'diiştir',
'editlink'                => 'diiştir',
'viewsourcelink'          => 'Geliniri gör',
'editsectionhint'         => 'Diiştirilen bölüm: $1',
'toc'                     => 'İçindekilär',
'showtoc'                 => 'göster',
'hidetoc'                 => 'sakla',
'thisisdeleted'           => '$1 görmää yaki geeri getirmää isteermisiniz?',
'viewdeleted'             => '$1 gör?',
'restorelink'             => 'silinmiş $1 diişmäk',
'feedlinks'               => 'Beslemää:',
'feed-invalid'            => 'Yannış beslemää tipi.',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS lenta',
'page-atom-feed'          => '"$1" Atom beslemää',
'red-link-title'          => '$1 (sayfa yok)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Yazı',
'nstab-user'      => 'kullanıcı sayfası',
'nstab-media'     => 'Media',
'nstab-special'   => 'Maasus yaprak',
'nstab-project'   => 'Proekt sayfası',
'nstab-image'     => 'Fayl',
'nstab-mediawiki' => 'Mesaj',
'nstab-template'  => 'Şablon',
'nstab-help'      => 'Yardım',
'nstab-category'  => 'Kategoriya',

# Main script and global functions
'nosuchaction'      => 'Bölä bir işlem yok',
'nosuchactiontext'  => 'URL tarafınnan tanınan işlem Viki tarafınnan kabledilmedi.',
'nosuchspecialpage' => 'Bu adda bir maasus sayfa yok',
'nospecialpagetext' => 'Bir maasus sayfaya girdiniz angısı bulunmêêr. Var olan hepsi maasus sayfaları yakışêr sizä görmää [[Special:SpecialPages|{{int:specialpages}}]] sayfasında.',

# General errors
'error'              => 'Kusurluk',
'databaseerror'      => 'Data bazası kusurluu',
'readonly'           => 'Data bazası kilitlendi',
'missing-article'    => 'Yok nicä bulunsun "$1" $2 adlı sayfadan teksti angısı istenerdi bulunsun.

Bu hal var nasıl peydalansın açan sayfa olêr geçmiş reviziyası o sayfanın angısı silindi.

Herliim sebep diilsä bu, bekim karşılaştınız bir yannışlan angısı yapıldı açan programma yazıldı.
Yalvarêrız benneyiniz URL - i hem raport ediniz bunu bir [[Special:ListUsers/sysop|izmetliyä]]',
'missingarticle-rev' => '(reviziya#: $1)',
'internalerror'      => 'İç yannış',
'internalerror_info' => 'İç yannış: $1',
'filecopyerror'      => '"$1"  "$2" faylına kopiyalanameer.',
'filerenameerror'    => '"$1" faylın adı "$2" adına diiştirilemeer.',
'filedeleteerror'    => '"$1" faylı silinemedi.',
'filenotfound'       => '"$1" faylı bulunamadı.',
'badtitle'           => 'Yannış yazı adı',
'badtitletext'       => 'Girilen sayfa adı beki yannış beki de boş, yaki geçersiz neçin ki diller arası baalantı yaki vikiler arası baalantı içerer. Var nicä içindä olsun bir yaki taa çok nışan angıları yasak başlıklarda kullanılsın.',
'viewsource'         => 'Geliniri gör',
'viewsourcefor'      => '$1 için',
'protectedpagetext'  => 'Bu sayfa diiştirmämää deyni kilitlendi.',
'viewsourcetext'     => 'Var nicä görmää hem kopiya etmää bu yapraa gelinirini:',

# Login and logout pages
'logouttext'                 => 'Sessiyayı kapattınız.
Şindi var nicä devam etmää kullanmaa {{SITENAME}} saytını kimlik göstermedän yaki [[Special:UserLogin|enidän sessiya açmaa]] (ister hep o kullanıcı adıylan, ister başka bir kullanıcı adıylan). O zamana kadar ani web brauzerinizin keşi temizlenecek bir takım sayfalar var nicä görünsün sansın sessiya hep açık.',
'welcomecreation'            => '== Hoş geldiniz $1! ==

Esapınız açıldı. Unutmayın [[Special:Preferences|{{SITENAME}} preferences]] seçimnerin diiştirmää.',
'yourname'                   => 'Kullanıcı adınız',
'yourpassword'               => 'Parol',
'yourpasswordagain'          => 'Parolu enidän yaz',
'remembermypassword'         => 'Parolu hatırla (en fazla $1 {{PLURAL:$1|gün|gün}} için)',
'yourdomainname'             => 'Domen adınız',
'login'                      => 'Gir',
'nav-login-createaccount'    => 'Gir / esap yarat',
'loginprompt'                => "Bak: {{SITENAME}} saytında sessiya açmaa için tarayıcınızda läazım cookies aktivat olsun. <br />
Kullanıcı adınız '''var nicä içersin'''gagauzça nışan, boşluk . Savaşın kullanıcı adınıza e-mail adresi '''girmemää'''.",
'userlogin'                  => 'Gir / esap yarat',
'logout'                     => 'Sessiyanı kapat',
'userlogout'                 => 'Oturmaa kapat',
'notloggedin'                => 'Sessiya diil açık',
'nologin'                    => "Henez aza olmadınız? '''$1'''.",
'nologinlink'                => 'Esap yarat',
'createaccount'              => 'Eni esap aç',
'gotaccount'                 => "Taa ilerdä esap açtınızmı? '''$1'''.",
'gotaccountlink'             => 'Herliim ilerdän esap açtıysanız girin bu baalantıdan.',
'createaccountmail'          => 'e-maillan',
'badretype'                  => 'Parollar angılarını girdiniz uymêêr.',
'userexists'                 => 'Kullanıcı adı ani girdiniz kullanılêr. Yalvarêrız farklı bir kullanıcı adı seçin.',
'loginerror'                 => 'Sessiya açmaa yannışı.',
'noname'                     => 'Geçerli bir kullanıcı adı girmediniz.',
'loginsuccesstitle'          => 'Sessiya başarılan açıldı',
'loginsuccess'               => '{{SITENAME}} saytında "$1" kullanıcı adılan sessiya açtınız.',
'nosuchuser'                 => 'Burada "$1" adlı kullanıcı yok. Yokla bir taa nesoy yazdın, yaki eni esap yarat.',
'nosuchusershort'            => 'Burada "<nowiki>$1</nowiki>" adlı kullanıcı yok. Yoklayın ani ad nesoy yazıldı.',
'nouserspecified'            => 'Läazım bir kullanıcı adı göstermää.',
'wrongpassword'              => 'Parolu yannış girdiniz. Yalvarerêz tekrar denämää.',
'wrongpasswordempty'         => 'Boş parol girdiniz. Yalvarerez tekrar denämää.',
'passwordtooshort'           => 'Parolunuz çok kısa. En az $1 bukva hem/yaki țifra läazım olsun.',
'mailmypassword'             => 'Gönder bana e-maillän eni bir parol',
'passwordremindertitle'      => '{{SITENAME}} saytından parol hatırlatıcısı.',
'passwordremindertext'       => '$1 IP adresinnän (beki siz) istendi {{SERVERNAME}} için eni bir {{SITENAME}} ($4) parolu göndermää.
"$2" nikli kullanıcının eni parolu: "$3"
Läazım sessiya açmaa hem parolu diiştirmää.

Herliim istemeersiniz parolu diiştirmää, yaki vaz geçtiniz neçin ki parolu hatırladınız bu haberi ignor edin hem devam edin kullanmaa eski parolu.',
'noemail'                    => '"$1" adlı kullanıcı için registrat olmuş e-mail adresi yok.',
'passwordsent'               => '"$1" adına registrat olmuş e-mail adresine eni bir parol gönderildi. Lütfen, läazım açmaa oturmaa ne zaman bunu aldınız.',
'blocked-mailpassword'       => 'Neçin ki İP adresiniz kösteklendi, eni parol gönderilmäk işlemi yapılmêêr.',
'eauthentsent'               => 'Registrat olunan adresa doorulamak kodlan e-mail gönderildi.
O zamana kadar ani e-maildaki instrukțiyalar yapılmaycêk hem doorulanmaycêk ki o adres sizin, başka e-mail gönderilmeycek.',
'mailerror'                  => 'E-mail göndermäk yannışı: $1',
'acct_creation_throttle_hit' => '$1 kullanıcı esap açtınız. Taa çok yok nicä açasınız.',
'emailauthenticated'         => 'E-mail adresiniz $2 $3 datasında doorulandı.',
'emailconfirmlink'           => 'E-mail adresinizi doorulayın',
'accountcreated'             => 'Esap açıldı',
'accountcreatedtext'         => '$1 için bir kullanıcı esapı açıldı.',
'createaccount-title'        => '{{SITENAME}} için esap açılışı',
'loginlanguagelabel'         => 'Dil: $1',

# Password reset dialog
'retypenew'           => 'Eni parolu tekrar girin',
'resetpass_forbidden' => 'Saytında parol yok nicä diiştirilsin',

# Edit page toolbar
'bold_sample'     => 'Kalın tekst',
'bold_tip'        => 'Kalın tekst',
'italic_sample'   => 'İtalik tekst',
'italic_tip'      => 'İtalik tekst',
'link_sample'     => 'Sayfanın adı',
'link_tip'        => 'İç baalantı',
'extlink_sample'  => 'http://www.example.com adres adı',
'extlink_tip'     => 'Dış baalantı (Unutmayın adresin önüne http:// koymaa)',
'headline_sample' => 'Başlık teksti',
'headline_tip'    => '2. düzey başlık',
'math_sample'     => 'Matematik-formulanı-koyun',
'math_tip'        => 'Matematik formula (LaTeX formatında)',
'nowiki_sample'   => 'Serbest format yazınızı buraya yazınız',
'nowiki_tip'      => 'Wiki formatlamasını ignor et',
'image_tip'       => 'Pätret eklemää',
'media_tip'       => 'Faylına baalantı',
'sig_tip'         => 'İmzanız hem data',
'hr_tip'          => 'Gorizontal liniya (çok sık kullanmayın)',

# Edit pages
'summary'                          => 'Kısaca:',
'subject'                          => 'Konu/başlık:',
'minoredit'                        => 'Küçük diişilmäkler',
'watchthis'                        => 'Bak bu sayfaa',
'savearticle'                      => 'Sayfayı registrat et',
'preview'                          => 'Ön siir',
'showpreview'                      => 'Ön siiri göster',
'showlivepreview'                  => 'Cannı ön siir',
'showdiff'                         => 'Diişilmäkleri göster',
'anoneditwarning'                  => 'Sessiya açmadınız deyni yazının diişmäk istoriyasına diil nik, IP adresiniz registrat olunacêk.',
'summary-preview'                  => 'Ön siir özeti:',
'subject-preview'                  => 'Konu/başlık ön siiri:',
'blockedtitle'                     => 'Kullanıcı kösteklendi.',
'blockedtext'                      => 'Kullanıcı adınız yaki parolunuz $1 tarafından kösteklendi.

Sizi köstek edän önderci: $1. Köstek sebebi: \'\'$2\'\'.

Eer düşünürsünüz ani köstek diil dooru o sebeptän angısı belirtildi, var nicä konuşmaa bu situațiyanı $1lan yaki başka bir [[{{MediaWiki:Grouppage-sysop}}|önderci]]län.

Herliim girmediniz [[Special:Preferences|seçimner]] bölümünde geçerli bir e-mail adresi, yok nicä kullanmaa "Kullanıcıya e-mail gönder" seçimini.

Şindi IP adresiniz $3. Yalvarêrêz bu adresi belirtmää her angı bir sorgu yapar kana.',
'blockednoreason'                  => 'hiç bir sebep belirtilmedi',
'blockedoriginalsource'            => "'''$1''' sayfasın kaynak teksti aşaada:",
'whitelistedittitle'               => 'Lääzım açmaa sessiya diişmäk yapmaa deyni',
'whitelistedittext'                => 'Diişmäk yapmaa için $1.',
'nosuchsectiontitle'               => 'Bölä bölüm yok',
'loginreqtitle'                    => 'Lääazım sessiya açmaa',
'loginreqlink'                     => 'sessiya aç',
'loginreqpagetext'                 => 'Lääzım $1 görmää übür sayfaları.',
'accmailtitle'                     => 'Parol gönderildi.',
'accmailtext'                      => '[[User talk:$1|$1]] kullanıcısın parolu $2 adresine gönderildi.',
'newarticle'                       => '(Eni)',
'newarticletext'                   => "Henez var olmayan bir sayfaya konulmuş baalantıya tuşladınız. Bu sayfayı yaratmaa deyni aşaadaki tekst kutusunu kullanınız. Bilgi için [[{{MediaWiki:Helppage}}|yardım sayfasına]] bakınız. Herliim buraya yannış geldiniz, läazım tuşlamaa programınızın '''Geeri''' tuşuna.",
'noarticletext'                    => 'Bu sayfa boş.
Bu başlıı [[Special:Search/{{PAGENAME}}|var nicä aramaa]] übür sayfalarda yaki bu sayfayı siz <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ilgili günlükleri arayabilir], ya [{{fullurl:{{FULLPAGENAME}}|action=edit}} var nicä yazmaa]</span>.',
'updated'                          => '(Enilendi)',
'previewnote'                      => "'''Bu saadä bir ön siir, hem diişmäkler henez korunmadı!'''",
'editing'                          => '"$1" sayfasın diiştirersiniz',
'editingsection'                   => '"$1" sayfasında bölüm diiştirersiniz',
'editingcomment'                   => '$1 sayfasına yorum ekleersiniz.',
'editconflict'                     => 'Diişmäk konflikti: $1',
'yourtext'                         => 'Sizin tekstiniz',
'storedversion'                    => 'Saklanmış tekst',
'yourdiff'                         => 'Farklar',
'copyrightwarning'                 => "'''Bakınız:''' {{SITENAME}} saytına yapılan hepsi eklemäkler hem diişmäkler läazım olsun  <i>$2</i>
lițenziyası şartları içindä (detallar için $1'a bakınız).
Herliim istemeersiniz ani sizin tekstlär serbest yayılsın hem diiştirilsin übür kullanıcılar tarafınnan, onnarı erleştirmeyniz buraya.<br />
Hem siz garantiyada bulunêrsiniz ani eklemäklerin avtorusunuz, yaki onnarı kopiya ettiniz kaynaktan angısı izin verer teksti serbest yaymaa hem diiştirmää.<br />
'''<center>AVTORLUK KORUNMAK HAKKILAN KORUNMAYAN MATERİALLAR EKLEMEYNİZ!</center>'''",
'templatesused'                    => 'Bu sayfada kullanılan {{PLURAL:$1|şablon|şablonlar}}:',
'templatesusedpreview'             => '{{PLURAL:$1|Şablon|Şablonnar}} ani bu ön siirdä kullanıldı:',
'templatesusedsection'             => 'Bu bölümde kullanılan {{PLURAL:$1|şablon|şablonlar}}:',
'template-protected'               => '(korumaa)',
'template-semiprotected'           => '(yarı-korunmaa)',
'hiddencategories'                 => 'Bu sayfa {{PLURAL:$1|1 saklı kategoriyaya|$1 saklı kategoriyaya}} baalı:',
'nocreatetext'                     => '{{SITENAME}} eni yazılar yaratmaa yasaklandı.
Sizä yakışêr geeri dönmää hem düzmää var olan yapraa, yaki [[Special:UserLogin|sessiya açmaa yaki esap yaratmaa]].',
'permissionserrors'                => 'İzin yannışları',
'permissionserrorstext-withaction' => 'Aşaadaki {{PLURAL:$1|sebep|sebepler}}ä deyni yok $2 kuvediniz:',
'recreate-moveddeleted-warn'       => "'''Bak: Siz yarattınız o sayfayı angısı ilerdän silindi.'''

Läazım düşünmää bu sayfayı redaktat etmää devam etmää deyni.
Sayfanın silmää jurnalı raatlık için yazılêr burada:",

# Account creation failure
'cantcreateaccounttitle' => 'Yok nicä esap yaratılsın',

# History pages
'viewpagelogs'           => 'Bu yaprak için jurnalları göster',
'currentrev'             => 'Şindiki versiya',
'currentrev-asof'        => '$1 sayfanın şindiki halı',
'revisionasof'           => 'Sayfanın $1 datasındaki hali',
'revision-info'          => '$1; $2 datalı versiya',
'previousrevision'       => '← İlerki hali',
'nextrevision'           => 'Geerki hali →',
'currentrevisionlink'    => 'en bitki halini göster',
'cur'                    => 'fark',
'next'                   => 'geeriki',
'last'                   => 'bitki',
'page_first'             => 'ilk',
'page_last'              => 'bitki',
'histlegend'             => "Fark seçimi: 2 versiyanın angısını isteersiniz karşılaştırmaa, önündeki kutucaa tuşlayıp, enter'a basın yaki tuşlayın butona angısı sayfanın en altında bulunêr.<br />
Nışannar: (bitki) = şindiki versiyalan aradaki fark,
(ilerki) = bir ilerki versiyalan aradaki fark, K = küçük diişmäk",
'history-fieldset-title' => 'Geçmişä bak',
'histfirst'              => 'En eski',
'histlast'               => 'En eni',
'historysize'            => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'           => '(boş)',

# Revision feed
'history-feed-title'          => 'Diişmäk istoriyası',
'history-feed-item-nocomment' => ' $2de $1',

# Revision deletion
'rev-deleted-comment' => '(yorum silindi)',
'rev-deleted-user'    => '(kullanıcı adı silindi)',
'rev-deleted-event'   => '(giriş silindi)',
'rev-delundel'        => 'göster/sakla',
'revdel-restore'      => 'Görümü diiştir',

# History merging
'mergehistory-from' => 'Kaynak sayfası:',
'mergehistory-into' => 'Yön sayfası:',

# Merge log
'revertmerge' => 'Ayır',

# Diffs
'history-title'           => '"$1" yapraın istoriyası',
'difference'              => '(Versiyalar arası farklar)',
'lineno'                  => '$1. liniya:',
'compareselectedversions' => 'Karşılaştır versiyaları ani seçildi',
'editundo'                => 'geeri al',
'diff-multi'              => '({{PLURAL:$1|Ara versiya|$1 ara versiyalar}} gösterilmedi.)',

# Search results
'searchresults'             => 'Aaramak rezultatları',
'searchresults-title'       => '"$1" için aaramak rezultatları',
'searchresulttext'          => '{{SITENAME}} içindä aaramaa deyni bilgi almaa için var nicä bakmaa [[{{MediaWiki:Helppage}}|{{int:help}}]] sayfasına.',
'searchsubtitle'            => '\'\'\'[[:$1]]\'\'\' için aaradınız. ([[Special:Prefixindex/$1|hepsi sayfalar angıları başlêêr "$1"]], [[Special:WhatLinksHere/$1|hepsi sayfalar angıları baalı "$1"]])',
'searchsubtitleinvalid'     => 'Aranêr: "$1"',
'notitlematches'            => 'Hiç bir başlıkta yok nicä bulunsun',
'notextmatches'             => ' Hiç bir başlıkta yok nicä bulunsun',
'prevn'                     => 'ilerki {{PLURAL:$1|$1}}',
'nextn'                     => 'geeriki {{PLURAL:$1|$1}}',
'viewprevnext'              => '($1 {{int:pipe-separator}} $2) ($3).',
'searchhelp-url'            => 'Help:İçindekilär',
'search-result-size'        => '$1 ({{PLURAL:$2|1 laf|$2 laf}})',
'search-redirect'           => '(göndermää $1)',
'search-section'            => '(bölüm $1)',
'search-suggest'            => '↓ İstediniz demää: $1',
'search-interwiki-caption'  => 'Kardaş proyektlär',
'search-interwiki-default'  => '$1 rezultatlar:',
'search-interwiki-more'     => '(taa çok)',
'search-mwsuggest-enabled'  => 'tekliflerlän',
'search-mwsuggest-disabled' => 'tekliflersiz',
'nonefound'                 => "'''Bennemäk''': Sadä kimi ad erleri sessizcä aaranêr.
Aaramaanızın önünä ''all:'' prefiksini koyun da deneyin hepsi içlii aaramaa deyni (sözleşmäk sayfaları, şablonlar h.b.pay alarak), yaki kullanınız beenilän prefiksi sansın er adı.",
'powersearch'               => 'Gelişmiş arama',
'powersearch-legend'        => 'Gelişmiş arama',
'powersearch-ns'            => 'Ad erlerindä aara:',
'powersearch-redir'         => 'Yönnendirmäkler listası',
'powersearch-field'         => 'Aara',

# Preferences page
'preferences'               => 'Seçimner',
'mypreferences'             => 'Seçimnerim',
'skin-preview'              => 'Ön siir',
'youremail'                 => 'E-mail adresiniz*',
'username'                  => 'Kullanıcı adı:',
'uid'                       => 'Registrațiya nomeri:',
'yourrealname'              => 'Haliz adınız:',
'yourlanguage'              => 'Dil:',
'yournick'                  => 'Nik',
'badsig'                    => 'Geçersiz çii imza; HTML etiketlerini yoklayın.',
'badsiglength'              => 'Kullanıcı adı çok uzun; lääzım olsun $1 simvol altında.',
'email'                     => 'E-mail',
'prefs-help-realname'       => '* Aslı ad (istemää baalı): herliim seçersäniz aslı adı vermää, işinize görä sizin için kullanılacêk.',
'prefs-help-email-required' => 'E-mail adres istenildi.',

# User rights
'editinguser' => "'''[[User:$1|$1]]''' sayfasını diiştirersiniz ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",

# Groups
'group-sysop' => 'Administratorlar',

'grouppage-sysop' => '{{ns:project}}:Önderciler',

# User rights log
'rightslog' => 'Kullanıcı hakları jurnalı',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'Sayfaa diiştir',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|diiştir|diiştir}}',
'recentchanges'                  => 'Bitki diişikmäklär',
'recentchanges-legend'           => 'Bitki diişlär opţiyaları',
'recentchanges-feed-description' => 'Bu lentalan en bitki diişmäkleri vikiyä yaz.',
'rcnote'                         => "$4 datası hem saat $5 için bitki {{PLURAL:$2|1 gündä|'''$2''' gündä}} yapılan, {{PLURAL:$1|'''1'''diiş|'''$1''' diiş}}",
'rcnotefrom'                     => "'''$2''' datasınnan büüne kadar yapılan diişmäkler aşaada (en çok '''$1''' yazı gösteriler).",
'rclistfrom'                     => 'Göster diişmäkleri ani $1 datasından beeri yapıldı',
'rcshowhideminor'                => 'küçük diişilmäkläri $1',
'rcshowhidebots'                 => 'botları $1',
'rcshowhideliu'                  => 'registrat olmuş kullanıcıları $1',
'rcshowhideanons'                => 'anonim kullanıcıları $1',
'rcshowhidepatr'                 => 'bakılmış diişmäkleri $1',
'rcshowhidemine'                 => 'diişmäklerimi $1',
'rclinks'                        => 'Göster bitki $1 diişmäklii ani yapıldı $2 gündä;<br /> $3',
'diff'                           => 'fark',
'hist'                           => 'geçmiş',
'hide'                           => 'sakla',
'show'                           => 'Göster',
'minoreditletter'                => 'K',
'newpageletter'                  => 'E',
'boteditletter'                  => 'b',
'rc-enhanced-expand'             => 'Detalları göster (JavaScript lääzım)',
'rc-enhanced-hide'               => 'Detalları sakla',

# Recent changes linked
'recentchangeslinked'          => 'İlgili diişilmäklär',
'recentchangeslinked-feed'     => 'İlgili diişilmäklär',
'recentchangeslinked-toolbox'  => 'İlgili diişilmäklär',
'recentchangeslinked-title'    => '"$1" ilgili diişmäklär',
'recentchangeslinked-noresult' => 'Baalantılı sayfalarda verilmiş devirde diişmäk olmadı.',
'recentchangeslinked-summary'  => "Bu maasus sayfa baalantılı sayfalardaki diişmäkleri sayêr.
Sizin bakmaa [[Special:Watchlist|listasındaki]] sayfalar verildi '''kalın''' bukvalarnan.",
'recentchangeslinked-page'     => 'Yaprak adı:',
'recentchangeslinked-to'       => 'Bu sayfa erinä verilen sayfaa baalı sayfaları göster',

# Upload
'upload'        => 'Fayl ükle',
'uploadbtn'     => 'Fayl ükle',
'uploadlogpage' => 'Fayl üklemäk jurnalları',
'uploadedimage' => 'Üklenen: "[[$1]]"',

# Special:ListFiles
'listfiles' => 'Pätret listası',

# File description page
'file-anchor-link'          => 'Fayl',
'filehist'                  => 'Fayl istoriyası',
'filehist-help'             => 'Fayl istoriyasın görmää deyni Gün/Zaman bölümündeki dataları tıklayınız.',
'filehist-current'          => 'Şindiki',
'filehist-datetime'         => 'Gün/Zaman',
'filehist-thumb'            => 'Küçük resim',
'filehist-thumbtext'        => '$1 versiyası için küçültülmüş halı',
'filehist-user'             => 'Kullanıcı',
'filehist-dimensions'       => 'Masştablar',
'filehist-filesize'         => 'Fayl ölçüleri',
'filehist-comment'          => 'Kommentariya',
'imagelinks'                => 'Mediya faylına baalantı',
'linkstoimage'              => 'Bu fayla {{PLURAL:$1|page links|$1 pages link}} baalantısı olan sayfalar:',
'nolinkstoimage'            => 'Yok sayfalar ani bu fayla baalı.',
'sharedupload'              => 'Bu fayl $1 üklendi ortak kullanmak erinä hem var nicä kullanılsın übür proektlärdä.',
'uploadnewversion-linktext' => 'Eni fayl ükle',

# MIME search
'mimesearch' => 'MIME arayışı',

# List redirects
'listredirects' => 'Yönnendirmäkler listası',

# Unused templates
'unusedtemplates' => 'Kullanılmayan şablonlar',

# Random page
'randompage' => 'Razgele sayfa',

# Random redirect
'randomredirect' => 'Razgele yönnendirmäk',

# Statistics
'statistics'                   => 'İstatistikalar',
'statistics-header-pages'      => 'Sayfa istatistikaları',
'statistics-header-edits'      => 'Diişmäkleri istatistikaları',
'statistics-header-views'      => 'Resim istatikaları',
'statistics-header-users'      => 'Kullanıcı istatistikaları',
'statistics-header-hooks'      => 'Başka istatistakalar',
'statistics-articles'          => 'Yazılar',
'statistics-pages'             => 'Yapraklar',
'statistics-pages-desc'        => 'Vikipediyadaki hepsi sayfalar, dartışma sayfaları, uur sayfaları',
'statistics-files'             => 'Üklenmiş dosyeler',
'statistics-edits'             => '{{SITENAME}} kurulmaa beeri yapmaa sayfa diişmäkleri',
'statistics-edits-average'     => 'Her yapraktaki diişmäklerin sayısı',
'statistics-views-total'       => 'Hepsi resimlär',
'statistics-views-total-desc'  => 'Var nica olmadı hem maasus yapraklarından resim eklenmee',
'statistics-views-peredit'     => 'Diişmäk başına resimlär',
'statistics-users'             => 'Registratlı [[Special:ListUsers|kullanıcılar]]',
'statistics-users-active'      => 'Aktivli kullanıcılar',
'statistics-users-active-desc' => 'Bitki {{PLURAL:$1|gün|$1 günde}} çalışmaa yapmaa kullanıcılar',
'statistics-mostpopular'       => 'En anılmış yazılar',

'disambiguations' => 'Maana aydınnatmak yaprakları',

'doubleredirects' => 'İki kerä yönnendirmeler',

'brokenredirects' => 'Var olmayan yazıya yapılmış yönnendirmeler',

'withoutinterwiki' => 'Başka dillerä baalantısı olmayan sayfalar',

'fewestrevisions' => 'En az düzennemäk  yapılmış sayfalar',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|bayt|baytı}}',
'nlinks'                  => '$1 {{PLURAL:$1|baalantı|baalantı}}',
'nmembers'                => '$1 {{PLURAL:$1|aza|azaler}}',
'lonelypages'             => 'Sayfalar ani yok kendisinä hiç baalantı',
'uncategorizedpages'      => 'Kategorizațiya olunmamıș sayfalar',
'uncategorizedcategories' => 'Kategorizațiya olunmamış kategoriyalar',
'uncategorizedimages'     => 'Her angı bir kategoriyada olmayan pätretler',
'uncategorizedtemplates'  => 'Şablonnar angıları kategorizațiya olunmadı',
'unusedcategories'        => 'Kullanılmayan kategoriyalar',
'unusedimages'            => 'Kullanılmayan pätretler',
'wantedcategories'        => 'Kategoriyalar ani istener',
'wantedpages'             => 'İstenen sayfalar',
'mostlinked'              => 'En fazlı baalantı verilmiş sayfalar',
'mostlinkedcategories'    => 'Kategoriyalar angılarında var en çok yazı',
'mostlinkedtemplates'     => 'En çok kullanılan şablonlar',
'mostcategories'          => 'En çok kategoriyalı sayfalar',
'mostimages'              => 'En çok kullanılan pätretler',
'mostrevisions'           => 'Yapraklar ani en çok diiştirildi',
'prefixindex'             => 'Prefiks indeks yapraklar',
'shortpages'              => 'Kısa sayfalar',
'longpages'               => 'Uzun sayfalar',
'deadendpages'            => 'Başka sayfalara baalantısız sayfalar',
'protectedpages'          => 'Korunma altındaki sayfalar',
'listusers'               => 'Kullanıcı listası',
'newpages'                => 'Eni sayfalar',
'ancientpages'            => 'En bitki diişmäk datası en eski olan yazılar',
'move'                    => 'Aadını diiştir',
'movethispage'            => 'Sayfayı taşı',
'pager-newer-n'           => '{{PLURAL:$1|1 taa eni|$1 taa eni}}',
'pager-older-n'           => '{{PLURAL:$1|1 taa eski|$1 taa eski}}',

# Book sources
'booksources'               => 'Kaynak kiyatlar',
'booksources-search-legend' => 'Kiyat kaynaklarını aara',
'booksources-go'            => 'Git',

# Special:Log
'specialloguserlabel'  => 'Kullanıcı:',
'speciallogtitlelabel' => 'Yazı adı:',
'log'                  => 'Jurnallar',
'all-logs-page'        => 'Hepsi jurnallar',

# Special:AllPages
'allpages'       => 'Hepsi sayfalar',
'alphaindexline' => '$1den $2e',
'nextpage'       => 'Geeriki sayfa ($1)',
'prevpage'       => 'İlerki sayfa ($1)',
'allpagesfrom'   => 'Listaya düzmää başlanılacêk bukvalar:',
'allpagesto'     => 'Listaya düzmää başlanılacêk bukvalar:',
'allarticles'    => 'Hepsi yazılar',
'allpagessubmit' => 'Git',
'allpagesprefix' => 'Gösterin sayfaları angıları çekeder bukvalarlan ani buraya yazdınız:',

# Special:Categories
'categories' => 'Kategoriyalar',

# Special:LinkSearch
'linksearch' => 'İç baalantlar',

# Special:Log/newusers
'newuserlogpage'          => 'Eni kullanıcı bennemäkleri',
'newuserlog-create-entry' => 'Eni kullanıcı esabı',

# Special:ListGroupRights
'listgrouprights-members' => '(azaların listası)',

# E-mail user
'emailuser' => 'Gönder bu kullanıcıya bir e-mail',

# Watchlist
'watchlist'         => 'Bakmaa listam',
'mywatchlist'       => 'Bakmaa listam',
'addedwatch'        => 'Bakmaa listasına registrat edildi.',
'addedwatchtext'    => '"<nowiki>$1</nowiki>" adlı sayfa [[Special:Watchlist|bakmaa listanıza]] registrat olundu.

Gelecektä, bu sayfaya hem ilgili konuşmaa sayfasına yapılacêk diişmäkler burada yazılacêk.

[[Special:RecentChanges|Bitki diişmäkler listası]] başlıı altında yazılacêk kalın bukvalarnan neçin ki kolayca seçilsin.

Ne zaman neetlendiniz sayfayı bakmaa listasınnan çıkarmaa tuşlayın "sayfaya bakmaa durgun" baalantısına.',
'removedwatch'      => 'Bakmaa listanızdan silindi',
'removedwatchtext'  => '"[[:$1]]" yapraı siir [[Special:Watchlist|listanızdan]] silindi.',
'watch'             => 'Bak',
'watchthispage'     => 'Bak bu sayfaya',
'unwatch'           => 'Durgun sayfa izlemää',
'watchlist-details' => 'Diil konuşmaa sayfaları {{PLURAL:$1|$1 sayfa|$1 sayfa}} bakmaa listanızda.',
'wlshowlast'        => 'Bitki $1 saati $2 günü göster $3',
'watchlist-options' => 'İzlemäk listası opţiyaları',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Bakılêr...',
'unwatching' => 'Durgundurulêr...',

# Delete
'deletepage'            => 'Sayfayı sil',
'historywarning'        => 'Bak: O sayfa angısını isteersiniz silmää istoriyası var:',
'confirmdeletetext'     => 'Bu sayfayı yaki faylı silersiniz hepsi istoriyasılan bilä.
Lütfen doorulayın ani siz neetlenersiniz bunu yapmaa, annêêrsiniz onun rezultatlarını hem inanêrsiniz ani bu [[{{MediaWiki:Policy-url}}|Silmää kanonnarına]] uyêr.',
'actioncomplete'        => 'İşlik tamannandı.',
'deletedtext'           => '"<nowiki>$1</nowiki>" silindi.
Yakın zamanda silinenleri görmää deyni: $2.',
'deletedarticle'        => '"[[$1]]" silindi',
'dellogpage'            => 'Silmää jurnalı',
'deletecomment'         => 'Sebep',
'deleteotherreason'     => 'Başka/ek sebep:',
'deletereasonotherlist' => 'Başka sebep',

# Rollback
'rollbacklink' => 'eski halinä dön',

# Protect
'protectlogpage'              => 'Korunmak jurnalı',
'protectedarticle'            => '"[[$1]]" korunmak altına alındı',
'modifiedarticleprotection'   => '"[[$1]]" için korumak yolu diiştirildi',
'prot_1movedto2'              => '[[$1]] sayfasın eni adı: [[$2]]',
'protect-legend'              => 'Korunmaa doorula',
'protectcomment'              => 'Sebep',
'protectexpiry'               => 'Bitmää datası:',
'protect_expiry_invalid'      => 'Yannış bitmää datası.',
'protect_expiry_old'          => 'Bitmää datası geçti.',
'protect-text'                => "Var nicä görmää hem diiştirmää buradan '''<nowiki>$1</nowiki>''' sayfasın korunmaa düzeyini.",
'protect-locked-access'       => "Sizin esapın yok izni yazının korunmak düzeyini diiştirmää.
Burada bitki seçimner '''$1''' yazı diiştirmää deyni:",
'protect-cascadeon'           => 'Bu sayfa şindi korunêr onuştan ani girer {{PLURAL:$1|aşaadaki sayfaa, angısına|||aşaadaki sayfalara, angılarına}} konuldu kaskad korunmak. Sizä yakışêr diiştirin bu sayfanın korunmak düzeyin, ama bu etkilemez kaskad korunmaa.',
'protect-default'             => 'Kabletmää hepsi kullanıcıları',
'protect-fallback'            => ' "$1" izin iste',
'protect-level-autoconfirmed' => 'Registrat olmamış kullanıcıları köstekle',
'protect-level-sysop'         => 'sadäcä önderciler',
'protect-summary-cascade'     => 'kaskad',
'protect-expiring'            => 'bitmää datası $1 (UTC)',
'protect-cascade'             => 'Bu sayfaya girän sayfaları koru (kaskad korunmaa)',
'protect-cantedit'            => 'Siz bu yazının korunmak düzeyin bilmärsiniz diiştirmää, neçin ki sizin onu düzmää izniniz yok.',
'protect-expiry-options'      => '1 saat:1 hour,1 gün:1 day,1 afta:1 week,2 afta:2 weeks,1 ay:1 month,3 ay:3 months,6 ay:6 months,1 yıl:1 year,zamansız:infinite',
'restriction-type'            => 'İzin:',
'restriction-level'           => 'Yasaklama düzeyi:',

# Undelete
'undeletebtn'      => 'Geeri getir!',
'undeletelink'     => 'Göster/geeri getir',
'undeletedarticle' => '[[$1]] geeri getirildi.',

# Namespace form on various pages
'namespace'      => 'Er adı:',
'invert'         => 'Seçilmiş dışındakileri göster',
'blanknamespace' => '(Baş)',

# Contributions
'contributions'       => 'Kullanıcının katılmakları',
'contributions-title' => '$1 için kullanıcı katılmakları',
'mycontris'           => 'Katılmaklarım',
'contribsub2'         => '$1 ($2)',
'uctop'               => '(bitki)',
'month'               => 'Ay:',
'year'                => 'Yıl:',

'sp-contributions-newbies'     => 'Sadä eni esap açan kullanıcıların katılmaklarını göster',
'sp-contributions-newbies-sub' => 'Eni kullanıcılara deyni',
'sp-contributions-blocklog'    => 'Köstek jurnalı',
'sp-contributions-talk'        => 'Konuşmaa',
'sp-contributions-search'      => 'Katılmakları aara',
'sp-contributions-username'    => 'IP adres yaki kullanıcı adı',
'sp-contributions-submit'      => 'Ara',

# What links here
'whatlinkshere'            => 'Baalantılar sayfaa',
'whatlinkshere-title'      => '$1 baalantısı olan sayfalar',
'whatlinkshere-page'       => 'Yaprak:',
'linkshere'                => "Buraya baalantısı var olan sayfalar '''[[:$1]]''':",
'nolinkshere'              => "Yok buraya baalanan sayfa '''[[:$1]]'''.",
'isredirect'               => 'yönnendirmäk sayfası',
'istemplate'               => 'eklemää',
'isimage'                  => 'fayl baalantısı',
'whatlinkshere-prev'       => '{{PLURAL:$1|ilerki|ilerki $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|ilerki|ilerki $1}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => 'enidän göndermää $1',
'whatlinkshere-hidetrans'  => 'Eklemää $1',
'whatlinkshere-hidelinks'  => 'baalantıları $1',
'whatlinkshere-filters'    => 'Filtralar',

# Block/unblock
'blockip'                  => 'Bu kullanıcıya köstek ol',
'ipboptions'               => '2 saat:2 hours,1 gün:1 day,3 gün:3 days, 1 afta:1 week, 2 afta:2 weeks, 1 ay:1 month, 3 ay:3 months, 6 ay:6 months, 1 yıl:1 year, zamansız:infinite',
'ipblocklist'              => 'Köstekli kullanıcılar hem IP adresleri listası',
'blocklink'                => 'köstek ol',
'unblocklink'              => 'köstek kaldır',
'change-blocklink'         => 'köstää diiştir',
'contribslink'             => 'yardımnar',
'blocklogpage'             => 'Köstek jurnalı',
'blocklogentry'            => '[[$1]] sebep $2 $3 durduruldu',
'unblocklogentry'          => '$1 kullanıcıya köstek kaldırıldı',
'block-log-flags-nocreate' => 'esap yaratmaa kösteklendi',

# Move page
'move-page-legend' => 'Ad diişmäklii',
'movepagetext'     => "Aşaadaki formayı kullanılarak var nicä sayfanın adın diiştirin, onnan bilä hepsi diiştirmää jurnalı eni ada aktarılacêk.
Eski ad eni ada yönnendirmäk olacêk.
Eski başlaa baalantılar  [[Special:DoubleRedirects|diişmeycek]] çift yaki yannış  [[Special:BrokenRedirects|yönnendirmäkleri]].
Läazım inanmaa ani baalantılar genä dä gösterer orayı nerä läazım göstersin.

Herliim ilerdän eni adda sayfa vardı, ad diişmää '''yapılmaycêk'''.
Bu o maana verer ani eer yannış olarak adını diiştirdiniz siz var nicä döndürün sayfayı eski adına.

'''BAK!'''
Bu ad diişmää var nicä duursun masştablı hem beklenmeyän rezultatlar ''populyar'' sayfalar için ;
onuştan devam etmedän ileri läazım inanmaa ani annêêrsiniz hepsi olacêk rezultatları.",
'movepagetalktext' => "Birleştirilmiş konuşmaa sayfasın, herliim varsa,
avtomatik adı diiştirilecek, '''o haller dışında, ne zaman:'''

*Eni adda konuşmaa sayfası taa varsa,
*Alttaki kutucuu seçmedinizsä .

Bu hallerdä läazım kendiniz ellän sayfaları aktarmaa yaki birleştirmää.",
'movearticle'      => 'Eski ad',
'newtitle'         => 'Eni ad',
'move-watch'       => 'Bak bu sayfaya',
'movepagebtn'      => 'Adı diiştir',
'pagemovedsub'     => 'Ad diişmäk tamannandı.',
'movepage-moved'   => '\'\'\'"$1",  "$2" sayfasına taşındı\'\'\'',
'articleexists'    => 'Bu adda bir sayfa bulunêr yaki o ad geçersiz angısını seçtiniz.
Yalvarêrêz başka bir ad seçmää.',
'talkexists'       => "'''Bu sayfa kendisi başarılan aktarıldı, ama konuşmaa sayfası aktarılamadı neçin ki eni ad altında bulunêr taa birisi. Yalvarêrêz onnarı ellän birleştirmää.'''",
'movedto'          => 'taşındı:',
'movetalk'         => 'Varsa hem aktar "konuşmaa" sayfasını.',
'1movedto2'        => '[[$1]] sayfasın eni adı: [[$2]]',
'1movedto2_redir'  => '[[$1]] başlaa [[$2]] sayfasına gönderildi',
'movelogpage'      => 'Ad diişmäk jurnalı',
'movereason'       => 'Sebep',
'revertmove'       => 'geeri al',

# Export
'export' => 'Sayfa registrat et',

# Namespace 8 related
'allmessages' => 'Sistema tekstleri',

# Thumbnails
'thumbnail-more'  => 'Büüt',
'thumbnail_error' => 'Ön siir yaratılar kana yannış: $1',

# Import log
'importlogpage' => 'Fayl aktarmaa jurnalı',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Kullanıcı sayfam',
'tooltip-pt-mytalk'               => 'Sözleşmäk sayfam',
'tooltip-pt-preferences'          => 'Seçimnerim',
'tooltip-pt-watchlist'            => 'İzlemää aldıım sayfalar',
'tooltip-pt-mycontris'            => 'Yaptıım katılmakların listası',
'tooltip-pt-login'                => 'Sessiya açmanız islää, ama diil zorlu.',
'tooltip-pt-logout'               => 'Sistemadan çık',
'tooltip-ca-talk'                 => 'İçindekilärlän ilgili düşünmäk sölä',
'tooltip-ca-edit'                 => 'Bu yapraı var nicä diiştirin. Kaydetmeden ileri ön siir etmää unutmayın.',
'tooltip-ca-addsection'           => 'Bu diskussiya için kommentariya ekleyin',
'tooltip-ca-viewsource'           => 'Bu sayfa korunmak altında. Gelinir kodunu sadä var nicä görünüz. Yok nicä diiştirmää.',
'tooltip-ca-history'              => 'Bu sayfanın geçmiş versiyaları.',
'tooltip-ca-protect'              => 'Bu sayfayı kolla',
'tooltip-ca-delete'               => 'Sayfayı sil',
'tooltip-ca-move'                 => 'Sayfanın adını diiştir',
'tooltip-ca-watch'                => 'Bu sayfanı ekle bakmaa listasına',
'tooltip-ca-unwatch'              => 'Brakın bu sayfaa bakmaa',
'tooltip-search'                  => '{{SITENAME}} içindä ara',
'tooltip-search-go'               => 'Herliim varsa, git salt bu adlı bir sayfaa',
'tooltip-search-fulltext'         => 'Bu tekst için sayfaları aara',
'tooltip-n-mainpage'              => 'Dönün baş yapraa',
'tooltip-n-mainpage-description'  => 'Dönün baş yapraa',
'tooltip-n-portal'                => 'Proyekt uurunda, ne nändä, nelär var nicä yapmaa',
'tooltip-n-currentevents'         => 'Şindiki sluçaylar uurunda bitki bilgiler',
'tooltip-n-recentchanges'         => 'Bitki diişmäklär listası angıları Vikidä yapıldı.',
'tooltip-n-randompage'            => 'Razgele bir yazıya gidin',
'tooltip-n-help'                  => 'Yardım almaa deyni',
'tooltip-t-whatlinkshere'         => 'Başka viki sayfaların listası angıları bu sayfaa baalantı verdi',
'tooltip-t-recentchangeslinked'   => 'Bu sayfaa baalı sayfalardaki bitki diişler',
'tooltip-feed-rss'                => 'Bu sayfa için RSS beslemää',
'tooltip-feed-atom'               => 'Bu sayfa için atom beslemää',
'tooltip-t-contributions'         => 'Kullanıcının katılmak listasını gör',
'tooltip-t-emailuser'             => 'Bu kullanıcı için e-mail gönder',
'tooltip-t-upload'                => 'Faylları ükle',
'tooltip-t-specialpages'          => 'Hepsi maasus yaprakların listasını göster',
'tooltip-t-print'                 => 'Bu sayfanın tiparlanmaa uygun versiyası',
'tooltip-t-permalink'             => 'Sayfanın bu versiyasına deyni dayma baalantı',
'tooltip-ca-nstab-main'           => 'Yazıya bak',
'tooltip-ca-nstab-user'           => 'Kullanıcı sayfasın göster',
'tooltip-ca-nstab-special'        => 'Bu maasus sayfa olduuna deyni yok nasıl yapmaa diişler.',
'tooltip-ca-nstab-project'        => 'Proekt sayfasın göster',
'tooltip-ca-nstab-image'          => 'Pätret sayfasın göster',
'tooltip-ca-nstab-template'       => 'Şablonu göster',
'tooltip-ca-nstab-help'           => 'Tıkla yardım sayfasın görmää',
'tooltip-ca-nstab-category'       => 'Kategoriya sayfasın göster',
'tooltip-minoredit'               => 'Küçük diişmäk olarak nışanna',
'tooltip-save'                    => 'Diişmäkläri registrat et',
'tooltip-preview'                 => 'Ön siir; korunmaa vermedän bunu kullanın hem gözden geçirin!',
'tooltip-diff'                    => 'Diişmekläri gösterer ani tekstä yaptınız.',
'tooltip-compareselectedversions' => 'Seçilmiş iki versiya arasındaki farkları göster.',
'tooltip-watch'                   => 'Sayfayı bakmaa listana ekle',
'tooltip-rollback'                => '"Geeri dönüm" tek tıklamaylan bu sayfaa son katım yapanın diişlerini geeri döndürer',
'tooltip-undo'                    => '"Geeri git" bu diişi var nasıl geeri döndürsün hem açsın diiş formunu ileri izlemää modunda.
Özet için bir sebep eklemää izin verer',

# Browsing diffs
'previousdiff' => '← İlerki versiyalan aradaki fark',
'nextdiff'     => 'Geerki versiyalan aradaki fark →',

# Media information
'file-info-size' => '$1 × $2 piksel, fayl ölçüsü: $3, MIME tipi: $4',
'file-nohires'   => '<small>Taa üüksek aydınnıklı versiya bulunmêêr.</small>',
'svg-long-desc'  => 'SVG faylı, nominal $1 × $2 piksel, fayl ölçüsü: $3',
'show-big-image' => 'Taman aydınnık',

# Special:NewFiles
'newimages' => 'Eni pätretler',

# Bad image list
'bad_image_list' => 'Bu format läazım olsun sansın aşaada:

Sadä listadaki êlementlarä (* nışannan çekedän liniyalar) bakılacêk. 
Liniyadaki seftä baalantı läazım olsun koymaa yasak resim için baalantı. 
Hep o liniyadaki übür baalantılara bakılacêk sansın bir maasus hal, ani onnar o yazılar angısına resim var nicä koymaa.',

# Metadata
'metadata'          => 'Pätret detalları',
'metadata-help'     => 'Fayla girer çok vakit țifralı kamera yaki skanerlän eklenän ek bilgiler.
Herliim fayl diiştirildi yaratılıştan sora, bir takım parametrlär var nicä kalsın bu resimdän farklı.',
'metadata-expand'   => 'Detalları göster',
'metadata-collapse' => 'Detalları gösterme',
'metadata-fields'   => 'Bu listadaki meta bilgilerin eri, resim sayfasında sormadan gösterilecek, übürleri saklanacêk.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# External editor support
'edit-externally'      => 'Kompyuterinizdäki uygulamaklarlan faylı düz',
'edit-externally-help' => 'Taa çok bilgi için var nicä bakmaa metadaki [http://www.mediawiki.org/wiki/Manual:External_editors dış uygulama instrumentläri] (angliyça) sayfasına.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Hepsini göster',
'namespacesall' => 'Hepsi',
'monthsall'     => 'hepsi',

# Watchlist editing tools
'watchlisttools-view' => 'İlgili diişmäkleri göster',
'watchlisttools-edit' => 'Siir listasını gör hem düzelt',
'watchlisttools-raw'  => 'Ham siir listasını düz',

# Special:Version
'version' => 'Versiya',

# Special:SpecialPages
'specialpages' => 'Maasus sayfalar',

);
