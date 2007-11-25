<?php
/** Turkish (Türkçe)
 *
 * @addtogroup Language
 *
 * @author Uğur Başak
 * @author Dbl2010
 * @author G - ג
 * @author Katpatuka
 * @author SPQRobin
 * @author Siebrand
 * @author Nike
 * @author Jon Harald Søby
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Özel',
	NS_MAIN             => '',
	NS_TALK             => 'Tartışma',
	NS_USER             => 'Kullanıcı',
	NS_USER_TALK        => 'Kullanıcı_mesaj',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_tartışma',
	NS_IMAGE            => 'Resim',
	NS_IMAGE_TALK       => 'Resim_tartışma',
	NS_MEDIAWIKI        => 'MedyaViki',
	NS_MEDIAWIKI_TALK   => 'MedyaViki_tartışma',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_tartışma',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_tartışma',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategori_tartışma',
);

$separatorTransformTable = array(',' => '.', '.' => ',' );
$linkTrail = '/^([a-zÇĞçğİıÖöŞşÜüÂâÎîÛû]+)(.*)$/sDu';

$messages = array(
# User preference toggles
'tog-underline'               => 'Bağlatıların altını çiz',
'tog-highlightbroken'         => 'Boş bağlantıları <a href="" class="new">bu şekilde</a> (alternatif: bu şekilde<a href="" class="internal">?</a>) göster.',
'tog-justify'                 => 'Paragraf iki yana yaslayarak ayarla',
'tog-hideminor'               => 'Küçük değişiklikleri "Son değişiklikler" sayfasında gizle',
'tog-extendwatchlist'         => 'Gelişmiş izleme listesi',
'tog-usenewrc'                => 'Gelişmiş son değişiklikler listesi (her tarayıcı için uygun değil)',
'tog-numberheadings'          => 'Başlıkları otomatik numaralandır',
'tog-showtoolbar'             => 'Değişiklik yaparken yardımcı düğmeleri göster. (JavaScript)',
'tog-editondblclick'          => 'Sayfayı çift tıklayarak değiştirmeye başla (JavaScript)',
'tog-editsection'             => 'Bölümleri [değiştir] bağlantıları ile değiştirme hakkı ver',
'tog-editsectiononrightclick' => 'Bölüm başlığına sağ tıklayarak bölümde değişikliğe izin ver.(JavaScript)',
'tog-showtoc'                 => 'İçindekiler tablosunu oluştur<br />(3 taneden fazla başlığı olan sayfalar için)',
'tog-rememberpassword'        => 'Parolayı hatırla',
'tog-editwidth'               => 'Yazma alanı tam genişlikte olsun',
'tog-watchcreations'          => 'Yaratmış olduğum sayfaları izleme listeme ekle',
'tog-watchdefault'            => 'Değişiklik yapılan sayfayı izleme listesine ekle',
'tog-watchmoves'              => 'Taşıdığım sayfaları izleme listeme ekle',
'tog-watchdeletion'           => 'Sildiğim sayfaları izleme listeme ekle',
'tog-minordefault'            => "Değişikliği 'küçük değişiklik' olarak seçili getir",
'tog-previewontop'            => 'Önizlemeyi yazma alanın üstünde göster',
'tog-previewonfirst'          => 'Değiştirmede önizlemeyi göster',
'tog-nocache'                 => 'Sayfaları bellekleme',
'tog-enotifwatchlistpages'    => 'Sayfa değişikliklerinde bana e-posta gönder',
'tog-enotifusertalkpages'     => 'Kullanıcı sayfamda değişiklik olduğunda bana e-posta gönder',
'tog-enotifminoredits'        => 'Sayfalardaki küçük değişikliklerde de bana e-posta gönder',
'tog-shownumberswatching'     => 'İzleyen kullanıcı sayısını göster',
'tog-fancysig'                => 'Ham imza (İmzanız yukarda belirttiğiniz gibi görünür. Sayfanıza otomatik bağlantı yaratılmaz)',
'tog-externaleditor'          => 'Değişiklikleri başka editör programı ile yap',
'tog-externaldiff'            => 'Karşılaştırmaları dış programa yaptır.',
'tog-showjumplinks'           => '"Git" bağlantısı etkinleştir',
'tog-uselivepreview'          => 'Canlı önizleme özelliğini kullan (JavaScript) (daha deneme aşamasında)',
'tog-forceeditsummary'        => 'Özeti boş bıraktığımda beni uyar',
'tog-watchlisthideown'        => 'İzleme listemden benim değişikliklerimi gizle',
'tog-watchlisthidebots'       => 'İzleme listemden bot değişikliklerini gizle',
'tog-watchlisthideminor'      => 'İzleme listemden küçük değişiklikleri gizle',

'underline-always'  => 'Daima',
'underline-never'   => 'Asla',
'underline-default' => 'Tarayıcı karar versin',

'skinpreview' => '(Önizleme)',

# Dates
'sunday'       => 'Pazar',
'monday'       => 'Pazartesi',
'tuesday'      => 'Salı',
'wednesday'    => 'Çarşamba',
'thursday'     => 'Perşembe',
'friday'       => 'Cuma',
'saturday'     => 'Cumartesi',
'fri'          => 'Cuma',
'january'      => 'Ocak',
'february'     => 'Şubat',
'march'        => 'Mart',
'april'        => 'Nisan',
'may_long'     => 'Mayıs',
'june'         => 'Haziran',
'july'         => 'Temmuz',
'august'       => 'Ağustos',
'september'    => 'Eylül',
'october'      => 'Ekim',
'november'     => 'Kasım',
'december'     => 'Aralık',
'february-gen' => 'Şubat',
'april-gen'    => 'Nisan',
'august-gen'   => 'Ağustos',
'december-gen' => 'Aralık',
'jan'          => 'Ocak',
'feb'          => 'Şubat',
'mar'          => 'Mart',
'apr'          => 'Nisan',
'may'          => 'Mayıs',
'jun'          => 'Haziran',
'jul'          => 'Temmuz',
'aug'          => 'Ağustos',
'sep'          => 'Eylül',
'oct'          => 'Ekim',
'nov'          => 'Kasım',
'dec'          => 'Aralık',

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategori|Kategoriler}}',
'pagecategories'        => 'Sayfa {{PLURAL:$1|kategorisi|kategorileri}}',
'category_header'       => '"$1" kategorisindeki sayfalar',
'subcategories'         => 'Alt Kategoriler',
'category-media-header' => '"$1" kategorisindeki medya',

'mainpagetext' => "<big>'''MediaWiki başarı ile kuruldu.'''</big>",

'about'          => 'Hakkında',
'article'        => 'Madde',
'newwindow'      => '(yeni bir pencerede açılır)',
'cancel'         => 'İptal',
'qbfind'         => 'Bul',
'qbbrowse'       => 'Tara',
'qbedit'         => 'Değiştir',
'qbpageoptions'  => 'Bu sayfa',
'qbpageinfo'     => 'Bağlam',
'qbmyoptions'    => 'Sayfalarım',
'qbspecialpages' => 'Özel sayfalar',
'moredotdotdot'  => 'Daha...',
'mypage'         => 'Sayfam',
'mytalk'         => 'Mesaj Sayfam',
'anontalk'       => "Bu IP'nin mesajları",
'navigation'     => 'Sitede yol bulma',

# Metadata in edit box
'metadata_help' => 'Metadata:',

'errorpagetitle'    => 'Hata',
'returnto'          => '$1.',
'tagline'           => '{{SITENAME}} sitesinden',
'help'              => 'Yardım',
'search'            => 'Ara',
'searchbutton'      => 'Ara',
'go'                => 'Git',
'searcharticle'     => 'Git',
'history'           => 'Sayfanın geçmişi',
'history_short'     => 'Geçmiş',
'updatedmarker'     => 'son ziyaretimden sonra güncellenmiş',
'info_short'        => 'Bilgi',
'printableversion'  => 'Basılmaya uygun görünüm',
'permalink'         => 'Son haline bağlantı',
'print'             => 'Bastır',
'edit'              => 'Değiştir',
'editthispage'      => 'Sayfayı değiştir',
'delete'            => 'Sil',
'deletethispage'    => 'Sayfayı sil',
'undelete_short'    => '$1 değişikliği geri getir',
'protect'           => 'Korumaya al',
'protectthispage'   => 'Sayfayı koruma altına al',
'unprotect'         => 'Korumayı kaldır',
'unprotectthispage' => 'Sayfa korumasını kaldır',
'newpage'           => 'Yeni sayfa',
'talkpage'          => 'Sayfayı tartış',
'specialpage'       => 'Özel Sayfa',
'personaltools'     => 'Kişisel aletler',
'postcomment'       => 'Yorum ekle',
'articlepage'       => 'Maddeye git',
'talk'              => 'Tartışma',
'views'             => 'Görünümler',
'toolbox'           => 'Araçlar',
'userpage'          => 'Kullanıcı sayfasını görüntüle',
'projectpage'       => 'Proje sayfasına bak',
'imagepage'         => 'Resim sayfasını görüntüle',
'categorypage'      => 'Kategori sayfasını göster',
'viewtalkpage'      => 'Tartışma sayfasına git',
'otherlanguages'    => 'Diğer diller',
'redirectedfrom'    => '($1 sayfasından yönlendirildi)',
'redirectpagesub'   => 'Yönlendirme sayfası',
'lastmodifiedat'    => 'Bu sayfa son olarak $2, $1 tarihinde güncellenmiştir.', # $1 date, $2 time
'viewcount'         => 'Bu sayfaya $1 defa erişilmiş.',
'protectedpage'     => 'Korumalı sayfa',
'jumpto'            => 'Git ve:',
'jumptonavigation'  => 'kullan',
'jumptosearch'      => 'ara',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} Hakkında',
'aboutpage'         => 'Project:Hakkında',
'bugreports'        => 'Hata Raporları',
'bugreportspage'    => '{{ns:project}}:Hata raporları',
'copyright'         => 'İçerik $1 altındadır.',
'copyrightpagename' => '{{SITENAME}} telif hakları',
'copyrightpage'     => 'Project:Telif hakları',
'currentevents'     => 'Güncel olaylar',
'currentevents-url' => 'Güncel olaylar',
'disclaimers'       => 'Sorumluluk reddi',
'disclaimerpage'    => '{{ns:project}}:Genel_sorumluluk_reddi',
'edithelp'          => 'Nasıl değiştirilir?',
'edithelppage'      => 'Yardım:Sayfa nasıl değiştirilir',
'faq'               => 'SSS',
'faqpage'           => 'Project:SSS',
'helppage'          => 'Yardım:İçindekiler',
'mainpage'          => 'Ana Sayfa',
'portal'            => 'Topluluk portalı',
'portal-url'        => 'Project:Topluluk portalı',
'privacy'           => 'Gizlilik ilkesi',
'privacypage'       => 'Project:Gizlilik_ilkesi',
'sitesupport'       => 'Bağışlar',
'sitesupport-url'   => 'Project:Bağış',

'badaccess'        => 'İzin hatası',
'badaccess-group0' => 'Bu işlemi yapma yetkiniz yok.',
'badaccess-group1' => 'Yapmak istediğiniz işlem ancak $1 grubundaki kullanıcılar tarafından yapılabilir.',
'badaccess-group2' => 'Yapmak istediğiniz işlem, sadece $1 grubundaki kullanıcılardan biri tarafından yapılabilir.',
'badaccess-groups' => 'Yapmak istediğiniz işlem, sadece $1 grubundaki kullanıcılardan biri tarafından yapılabilir.',

'versionrequired' => "MediaWiki'nin $1 sürümü gerekiyor",

'ok'                      => 'TAMAM',
'retrievedfrom'           => '"$1"\'dan alındı',
'youhavenewmessages'      => 'Yeni <u>$1</u> var. ($2)',
'newmessageslink'         => 'mesajınız',
'newmessagesdifflink'     => 'Bir önceki sürüme göre eklenen yazı farkı',
'youhavenewmessagesmulti' => "$1'de yeni mesajınız var.",
'editsection'             => 'değiştir',
'editold'                 => 'değiştir',
'editsectionhint'         => 'Değiştirilen bölüm: $1',
'toc'                     => 'Konu başlıkları',
'showtoc'                 => 'göster',
'hidetoc'                 => 'gizle',
'thisisdeleted'           => '$1 görmek veya geri getirmek istermisin?',
'viewdeleted'             => '$1 gör?',
'restorelink'             => 'silinmiş $1 değişikliği',
'feedlinks'               => 'Besleme:',
'feed-invalid'            => 'Hatalı besleme tipi.',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Madde',
'nstab-user'      => 'kullanıcı sayfası',
'nstab-media'     => 'Medya',
'nstab-special'   => 'Özel',
'nstab-project'   => 'Proje sayfası',
'nstab-image'     => 'Dosya',
'nstab-mediawiki' => 'arayüz metni',
'nstab-template'  => 'şablon',
'nstab-help'      => 'yardım',
'nstab-category'  => 'Kategori',

# Main script and global functions
'nosuchaction'      => 'Böyle bir eylem yok',
'nosuchactiontext'  => 'URL tarafından tanımlanan eylem Viki tarafından algılanamadı.',
'nosuchspecialpage' => 'Bu isimde bir özel sayfa yok',
'nospecialpagetext' => 'Bulunmayan bir özel sayfaya girdiniz. Varolan tüm özel sayfaları [[Special:Specialpages]] sayfasında görebilirsiniz.',

# General errors
'error'            => 'Hata',
'databaseerror'    => 'Veritabanı hatası',
'dberrortext'      => 'Veritabanı hatası.
Bu bir yazılım hatası olabilir.
"<tt>$2</tt>" işlevinden denenen son sorgulama:
<blockquote><tt>$1</tt></blockquote>.

MySQL\'in rapor ettiği hata "<tt>$3: $4</tt>".',
'dberrortextcl'    => 'Veritabanı komut hatası.
Son yapılan veritabanı erişim komutu:
"$1"
Kullanılan fonksiyon "$2".
MySQL\'in verdiği hata mesajı "$3: $4"',
'cachederror'      => 'Aşağıdaki, istediğiniz sayfanın önbellekteki kopyasıdır ve güncel olmayabilir.',
'readonly'         => 'Veritabanı kilitlendi',
'enterlockreason'  => 'Koruma için bir neden belirtin. Korumanın ne zaman kaldırılacağına dair tahmini bir tarih eklemeyi unutmayın.',
'readonlytext'     => 'Veritabanı olağan bakım/onarım çalışmaları sebebiyle, geçici olarak giriş ve değişiklik yapmaya kapatılmıştır. Kısa süre sonra normale dönecektir.

Veritabanını kilitleyen operatörün açıklaması: $1',
'internalerror'    => 'Yazılım hatası',
'filecopyerror'    => '"$1"  "$2" dosyasına kopyalanamıyor.',
'filerenameerror'  => '"$1" dosyasının adı "$2" ismine değiştirilemiyor.',
'filedeleteerror'  => '"$1" dosyası silinemedi.',
'filenotfound'     => '"$1" dosyası bulunamadı.',
'unexpected'       => 'beklenmeyen değer: "$1"="$2".',
'formerror'        => 'Hata: Form gönderilemiyor',
'badarticleerror'  => 'Yapmak istediğiniz işlem geçersizdir.',
'cannotdelete'     => 'Belirtilen sayfa ya da görüntü silinemedi. (başka bir kullanıcı tarafından silinmiş olabilir).',
'badtitle'         => 'Geçersiz başlık',
'badtitletext'     => 'Girilen sayfa ismi ya hatalı ya boş ya da diller arası bağlantı veya vikiler arası bağlantı içerdiğinden geçerli değil. Başlıklarda kullanılması yasak olan bir ya da daha çok karakter içeriyor olabilir.',
'perfdisabled'     => 'Özür dileriz! Bu özellik, veritabanını kullanılamayacak derecede yavaşlattığı için, geçici olarak kullanımdan çıkarıldı.',
'perfcached'       => 'Veriler daha önceden hazırlanmış olabilir. Bu sebeple güncel olmayabilir!',
'perfcachedts'     => 'Aşağıda saklanmış bilgiler bulunmaktadır, son güncelleme zamanı: $1.',
'viewsource'       => 'Kaynağı gör',
'viewsourcefor'    => '$1 için',
'editinginterface' => "'''UYARI:''' Yazılım için arayüz sağlamakta kullanılan bir sayfayı değiştirmektesiniz. Bu sayfadaki değişiklikler kullanıcı arayüzünü diğer kullanıcılar için de değiştirecektir.",

# Login and logout pages
'logouttitle'                => 'Oturumu kapat',
'logouttext'                 => 'Oturumu kapattınız.
Şimdi kimliğinizi belirtmeksizin {{SITENAME}} sitesini kullanmaya devam edebilirsiniz, ya da yeniden oturum açabilirsiniz (ister aynı kullanıcı adıyla, ister başka bir kullanıcı adıyla). Web tarayıcınızın önbelleğini temizleyene kadar bazı sayfalar sanki hala oturumunuz açıkmış gibi görünebilir.',
'welcomecreation'            => '== Hoşgeldiniz $1! ==

Hesabınız açıldı. {{SITENAME}} tercihlerinizi değiştirmeyi unutmayın.',
'loginpagetitle'             => 'Oturum aç',
'yourname'                   => 'Kullanıcı adınız',
'yourpassword'               => 'Parolanız',
'yourpasswordagain'          => 'Parolayı yeniden yaz',
'remembermypassword'         => 'Parolayı hatırla.',
'yourdomainname'             => 'Alan adınız',
'login'                      => 'Oturum aç',
'loginprompt'                => "Dikkat: {{SITENAME}} sitesinde oturum açabilmek için tarayıcınızda çerezlerin (cookies) aktifleştirilmiş olması gerekmektedir.<br />
Kullanıcı adınız Türkçe karakter, boşluk '''içerebilir'''. Kullanıcı adınıza e-posta adresi '''girmemeniz''' tavsiye edilir.",
'userlogin'                  => 'Oturum aç ya da yeni hesap edin',
'logout'                     => 'Oturumu kapat',
'userlogout'                 => 'Oturumu kapat',
'notloggedin'                => 'Oturum açık değil',
'nologin'                    => 'Daha üye değil misiniz? $1.',
'nologinlink'                => 'Eğer şimdiye kadar kayıt olmadıysanız bu bağlantıyı takip edin.',
'createaccount'              => 'Yeni hesap aç',
'gotaccount'                 => 'Daha önceden kayıt oldunuz mu? $1.',
'gotaccountlink'             => 'Eğer önceden hesap açtırdıysanız bu bağlantıdan giriş yapınız.',
'createaccountmail'          => 'e-posta ile',
'badretype'                  => 'Girdiğiniz parolalar birbirini tutmuyor.',
'userexists'                 => 'Girdiğiniz kullanıcı adı kullanımda. Lütfen farklı bir kullanıcı adı seçin.',
'youremail'                  => 'E-posta adresiniz*',
'username'                   => 'Kullanıcı adı:',
'uid'                        => 'Kayıt numarası:',
'yourrealname'               => 'Gerçek isminiz *',
'yourlanguage'               => 'Arayüz dili',
'yourvariant'                => 'Sizce',
'yournick'                   => 'İmzalarda gözükmesini istediğiniz isim',
'badsig'                     => 'Geçersiz ham imza; HTML etiketlerini kontorl edin.',
'email'                      => 'E-posta',
'prefs-help-realname'        => '* Gerçek isim (isteğe bağlı): eğer gerçek isminizi vermeyi seçerseniz, çalışmanızı size atfederken kullanılacaktır.',
'loginerror'                 => 'Oturum açma hatası.',
'prefs-help-email'           => '* E-posta (isteğe bağlı): Diğer kullanıcıların kullanıcı sayfanız aracılığıyla <strong>adresinizi bilmeksizin</strong> sizle iletişim kurmasını sağlar.',
'nocookieslogin'             => '{{SITENAME}} sitesinde oturum açabilmek için çerezlerinizin açık olması gerekiyor. Sizin çerezleriniz kapalı. Lütfen açınız ve bir daha deneyiniz.',
'noname'                     => 'Geçerli bir kullanıcı adı girmediniz.',
'loginsuccesstitle'          => 'Oturum açıldı',
'loginsuccess'               => '{{SITENAME}} sitesinde "$1" kullanıcı adıyla oturum açmış bulunmaktasınız.',
'nouserspecified'            => 'Bir kullanıcı adı belirtmek zorundasınız.',
'wrongpassword'              => 'Parolayı yanlış girdiniz. Lütfen tekrar deneyiniz.',
'wrongpasswordempty'         => 'Boş parola girdiniz. Lütfen tekrar deneyiniz.',
'passwordtooshort'           => 'Parolanız çok kısa. En az $1 harf ve/veya rakam içermeli.',
'mailmypassword'             => 'Bana e-posta ile yeni bir parola gönder',
'passwordremindertitle'      => '{{SITENAME}} sitesinden şifre hatırlatıcısı.',
'passwordremindertext'       => '$1 IP adresinden (muhtemelen siz) {{SERVERNAME}} için yeni bir {{SITENAME}} ($4) parolası gönderilmesi istendi.
"$2" rumuzlu kullanıcının yeni parolası: "$3"
Oturum açıp parolanızı değiştirmelisiniz.

Parola değişimini siz istemediyseniz, ya da parolanızı hatırlayıp değiştirmekten vazgeçtiyseniz bu iletiyi görmezden gelip eski parolanızı kullanmaya devam edebilirsiniz.',
'noemail'                    => '"$1" adlı kullanıcıya kayıtlı bir e-posta adresi yok.',
'passwordsent'               => '"$1" adına kayıtlı e-posta adresine yeni bir parola gönderildi. Oturumu, lütfen, iletiyi aldıktan sonra açın.',
'blocked-mailpassword'       => 'Siteye erişiminiz engellenmiş olduğundan, yeni şifre gönderilme işlemi yapılamamaktadır.',
'eauthentsent'               => 'Kaydedilen adrese onay kodu içeren bir e-posta gönderildi.
E-postadaki yönerge uygulanıp adresin size ait olduğu onaylanmadıkça başka e-posta gönderilmeyecek.',
'mailerror'                  => 'E-posta gönderim hatası: $1',
'acct_creation_throttle_hit' => '$1 tane kullanıcı hesabı açtırmış durumdasınız. Daha fazla açtıramazsınız.',
'emailauthenticated'         => 'E-posta adresiniz $1 tarihinde doğrulanmıştı.',
'emailnotauthenticated'      => 'E-posta adresiniz henüz onaylanmadı.
Aşağıdaki işlevlerin hiçbiri için e-posta gönderilmeyecektir.',
'emailconfirmlink'           => 'E-posta adresinizi doğrulayın',
'invalidemailaddress'        => 'E-posta adresi geçersizdir. Lütfen geçerli bir adres yazın ya da metin kutusunun içeriğini silin.',
'accountcreated'             => 'Hesap açıldı',
'accountcreatedtext'         => '$1 için bir kullanıcı hesabı açıldı.',

# Edit page toolbar
'bold_sample'     => 'Kalın yazı',
'bold_tip'        => 'Kalın yazı',
'italic_sample'   => 'İtalik yazı',
'italic_tip'      => 'İtalik yazı',
'link_sample'     => 'Sayfanın başlığı',
'link_tip'        => 'İç bağlantı',
'extlink_sample'  => '{{SERVER}} adres açıklaması',
'extlink_tip'     => 'Dış bağlantı (Adresin önüne http:// koymayı unutmayın)',
'headline_sample' => 'Başlık yazısı',
'headline_tip'    => '2. seviye başlık',
'math_sample'     => 'Matematiksel-ifadeyi-girin',
'math_tip'        => 'Matematik formülü (LaTeX formatında)',
'nowiki_sample'   => 'Serbest format yazınızı buraya yazınız',
'nowiki_tip'      => 'wiki formatlamasını devre dışı bırak',
'image_sample'    => 'Örnek.jpg',
'image_tip'       => 'Resim ekleme',
'media_sample'    => 'Örnek.ogg',
'media_tip'       => 'Medya dosyasına bağlantı',
'sig_tip'         => 'İmzanız ve tarih',
'hr_tip'          => 'Yatay çizgi (çok sık kullanmayın)',

# Edit pages
'summary'                  => 'Özet',
'subject'                  => 'Konu/başlık',
'minoredit'                => 'Küçük değişiklik',
'watchthis'                => 'Sayfayı izle',
'savearticle'              => 'Sayfayı kaydet',
'preview'                  => 'Önizleme',
'showpreview'              => 'Önizlemeyi göster',
'showlivepreview'          => 'Canlı önizleme',
'showdiff'                 => 'Değişiklikleri göster',
'anoneditwarning'          => 'Oturum açmadığınızdan maddenin değişiklik kayıtlarına rumuzunuz yerine IP adresiniz kaydedilecektir.',
'missingsummary'           => "'''Uyarı:''' Herhangi bir özet yazmadın. Kaydet tuşu bir daha bastığında sayfayı özetsiz kaydetilecek.",
'missingcommenttext'       => 'Lütfen aşağıda bir açıklama yazınız.',
'blockedtitle'             => 'Kullanıcı erişimi engellendi.',
'blockedtext'              => '<big>Erişiminiz $1 tarafından durdurulmuştur.</big>

Sizi engelleyen yönetici: $1. Engelleme sebebi: \'\'$2\'\'.

Belirtilen sebeb göre engellenmenizin uygun olmadığını düşünüyorsanız, $1 ile ya da başka bir [[{{ns:project}}:Yöneticiler|yönetici]] ile bu durumu görüşebilirsiniz.

Eğer [[Special:Preferences|tercihler]] kısmında geçerli bir e-posta adresi girmediyseniz "Kullanıcıya e-posta gönder" özelliğini kullanamazsınız.

Şu anda IP adresiniz $3. Lütfen sorgu yaparken bu adresi belirtiniz.',
'blockedoriginalsource'    => "'''$1''' sayfasının kaynak metni aşağıdır:",
'blockededitsource'        => "'''$1''' sayfasında '''yaptığınız değişikliğe''' ait metin aşağıdadır:",
'whitelistedittitle'       => 'Değişiklik yapmak için oturum açmalısınız',
'whitelistedittext'        => 'Değişiklik yapabilmek için $1.',
'whitelistreadtitle'       => 'Okumak için oturum açmalısınız',
'whitelistreadtext'        => 'Sayfaları okuyabilmek için [[Special:Userlogin|oturum açmalısınız]].',
'whitelistacctitle'        => 'Hesap açma izniniz yok.',
'confirmedittitle'         => 'Değişiklik yapmak için e-posta onaylaması gerekiyor',
'confirmedittext'          => 'Sayfa değiştirmeden önce e-posta adresinizi onaylamalısınız. Lütfen [[Special:Preferences|tercihler]] kısmından e-postanızı ekleyin ve onaylayın.',
'loginreqtitle'            => 'Oturum açmanız gerekiyor',
'loginreqlink'             => 'oturum aç',
'accmailtitle'             => 'Parola gönderildi.',
'accmailtext'              => "'$1' kullanıcısına ait parola $2 adresine gönderildi.",
'newarticle'               => '(Yeni)',
'newarticletext'           => "Henüz varolmayan bir sayfaya konulmuş bir bağlantıya tıkladınız. Bu sayfayı yaratmak için aşağıdaki metin kutusunu kullanınız. Bilgi için [[Help:İçindekiler|yardım sayfasına]] bakınız. Buraya yanlışlıkla geldiyseniz, programınızın '''Geri''' tuşuna tıklayınız.",
'anontalkpagetext'         => "----
''Bu kayıtlı olmayan ya da kayıtlı adıyla sisteme giriş yapmamış bir kullanıcının mesaj sayfasıdır. Bu sebeple kimliği IP adresi ile gösterilmektedir. Bu tür IP adresleri diğer kişiler tarafından payşılabilir. Eğer siz de bir anonim kullanıcı iseniz ve yöneltilen yorumlar sizle ilgili değilse, [[Special:Userlogin|kayıt olun ya da sisteme girin ki]] ileride başka yanlış anlaşılma olmasın.''",
'noarticletext'            => 'Bu sayfa boştur. Bu başlığı diğer sayfalarda [[Special:Search/{{PAGENAME}}|arayabilir]] veya bu sayfayı siz  [{{fullurl:{{FULLPAGENAME}}|action=edit}} yazabilirsiniz].',
'clearyourcache'           => "'''Not:''' Ayarlarınızı kaydettikten sonra, tarayıcınızın belleğini de temizlemeniz gerekmektedir: '''Mozilla / Firefox / Safari:''' ''Shift'' e basılıyken safyayı yeniden yükleyerek veya ''Ctrl-Shift-R'' yaparak (Apple Mac için ''Cmd-Shift-R'');, '''IE:''' ''Ctrl-F5'', '''Konqueror:''' Sadece sayfayı yeniden yükle tuşuna basarak.",
'usercssjsyoucanpreview'   => "<strong>İpucu:</strong> Sayfayı kaydetmeden önce <font style=\"border: 1px solid #0; background: #EEEEEE; padding : 2px\">'''önizlemeyi göster'''</font>'e tıklayarak yaptığınız yeni sayfayı gözden geçirin.",
'usercsspreview'           => "'''Sadece test ediyorsun ya da önizleme görüyorsun - kullanıcı CSS dosyası henüz kaydolmadı.'''",
'userjspreview'            => "'''Sadece test ediyorsun ya da önizleme görüyorsun - kullanıcı JavaScript'i henüz kaydolmadı.'''",
'userinvalidcssjstitle'    => "''Uyarı:''' \"\$1\" adıyla bir tema yoktur. tema-adı.css ve .js dosyalarının adları küçük harf ile yazması gerek, yani User:Temel/'''M'''onobook.css değil, User:Temel/'''m'''onobook.css.",
'updated'                  => '(Güncellendi)',
'note'                     => '<strong>Not: </strong>',
'previewnote'              => 'Bu yalnızca bir önizlemedir, ve değişiklikleriniz henüz kaydedilmemiştir!',
'session_fail_preview'     => 'Özür dileriz. Oturum açılması ile ilgili veri kaybından kaynaklı değişikliğinizi kaydedemedik. Lütfen tekrar deneyiniz. Eğer bu yöntem işe yaramazsa oturumu kapatıp tekrar sisteme geri giriş yapınız.',
'editing'                  => '"$1" sayfasını değiştirmektesiniz',
'editinguser'              => '"$1" sayfasını değiştirmektesiniz',
'editingsection'           => '"$1" sayfasında bölüm değiştirmektesiniz',
'editingcomment'           => '$1 sayfasına mesaj eklemektesiniz.',
'editconflict'             => 'Değişiklik çakışması: $1',
'explainconflict'          => 'Siz sayfayı değiştirirken başka biri de değişiklik yaptı.
Yukarıdaki yazı sayfanın şu anki halini göstermektedir.
Sizin değişiklikleriniz alta gösterilmiştir. Son değişiklerinizi yazının içine eklemeniz gerekecektir. "Sayfayı kaydet"e bastığınızda <b>sadece</b> yukarıdaki yazı kaydedilecektir. <br />',
'yourtext'                 => 'Sizin metniniz',
'storedversion'            => 'Kaydedilmiş metin',
'editingold'               => '<strong>DİKKAT: Sayfanın eski bir sürümünde değişiklik yapmaktasınız. 
Kaydettiğinizde bu tarihli sürümden günümüze kadar olan değişiklikler yok olacaktır.</strong>',
'yourdiff'                 => 'Karşılaştırma',
'copyrightwarning'         => "<strong>Lütfen dikkat:</strong> {{SITENAME}} sitesine yapılan bütün katkılar <i>$2</i>
sözleşmesi kapsamındadır (ayrıntılar için $1'a bakınız).
Yaptığınız katkının başka katılımcılarca acımasızca değiştirilmesini ya da özgürce ve sınırsızca başka yerlere dağıtılmasını istemiyorsanız, katkıda bulunmayınız.<br />
Ayrıca, buraya katkıda bulunarak, bu katkının kendiniz tarafından yazıldığına, ya da kamuya açık bir kaynaktan ya da başka bir özgür kaynaktan kopyalandığına güvence vermiş oluyorsunuz.<br />
<strong><center>TELİF HAKKI İLE KORUNAN HİÇBİR ÇALIŞMAYI BURAYA EKLEMEYİNİZ!</center></strong>",
'copyrightwarning2'        => 'Lütfen, {{SITENAME}} sitesinea bulunacağınız tüm katkıların diğer üyeler tarafından düzenlenebileceğini, değiştirilebileceğini ya da silinebileceğini hatırlayın. Yazılarınızın merhametsizce değiştirilebilmesine rıza göstermiyorsanız buraya katkıda bulunmayın. <br />
Ayrıca bu ekleyeceğiniz yazıyı sizin yazdığınızı ya da serbest kopyalama izni veren bir kaynaktan kopyaladığınızı bize taahhüt etmektesiniz (ayrıntılar için referans: $1).',
'longpagewarning'          => '<strong>UYARI: Bu sayfa $1 kilobayt büyüklüğündedir; bazı tarayıcılar değişiklik yaparken 32kb ve üstü büyüklüklerde sorunlar yaşayabilir. Sayfayı bölümlere ayırmaya çalışın.</strong>',
'readonlywarning'          => '<strong>DİKKAT: Bakım nedeni ile veritabanı şu anda kilitlidir. Bu sebeple değişiklikleriniz şu anda kaydedilememektedir. Yazdıklarınızı başka bir editöre alıp saklayabilir ve daha sonra tekrar buraya getirip kaydedebilirsiniz</strong>',
'protectedpagewarning'     => 'UYARI: Bu sayfa koruma altına alınmıştır ve yalnızca yönetici olanlar tarafından değiştirilebilir. Bu sayfayı değiştirirken lütfen [[Project:Koruma altına alınmış sayfa|korumalı sayfa kurallarını]] uygulayınız.',
'semiprotectedpagewarning' => "'''Uyarı''': Bu sayfa sadece kayıtlı kullanıcı olanlar tarafından değiştirilebilir.",
'templatesused'            => 'Bu sayfada kullanılan şablonlar:',

# Account creation failure
'cantcreateaccounttitle' => 'Hesap oluşturulamıyor',

# History pages
'revhistory'          => 'Sürüm geçmişi',
'viewpagelogs'        => 'Bu sayfa ile ilgili kayıtları göster',
'nohistory'           => 'Bu sayfanın geçmiş sürümü yok.',
'revnotfound'         => 'Sürüm bulunmadı',
'loadhist'            => 'Sayfa geçmişi yükleniyor',
'currentrev'          => 'Güncel sürüm',
'revisionasof'        => 'Sayfanın $1 tarihindeki hali',
'previousrevision'    => '← Önceki hali',
'nextrevision'        => 'Sonraki hali →',
'currentrevisionlink' => 'en güncel halini göster',
'cur'                 => 'fark',
'next'                => 'sonraki',
'last'                => 'son',
'orig'                => 'asıl',
'histlegend'          => "Fark seçimi: karşılaştımayı istediğiniz 2 sürümün önündeki dairelere taıkayıp, enter'a basın ya da sayfanın en atında bulunan düğmeye basın.<br />
Tanımlar: (güncel) = güncel sürümle aradaki fark,
(önceki) = bir önceki sürümle aradaki fark, K = küçük değişiklik",
'deletedrev'          => '[silindi]',
'histfirst'           => 'En eski',
'histlast'            => 'En yeni',
'historysize'         => '({{PLURAL:$1|1 bayt|$1 bayt}})',
'historyempty'        => '(boş)',

# Revision feed
'history-feed-title' => 'Değişiklik geçmişis',

# Revision deletion
'rev-deleted-comment'       => '(yorum silindi)',
'rev-deleted-user'          => '(kullanıcı adı silindi)',
'rev-delundel'              => 'göster/gizle',
'revisiondelete'            => 'Sürümleri sil/geri getir',
'revdelete-hide-comment'    => 'Özeti gösterme',
'revdelete-hide-user'       => "Değişikliği yapan kullanıcı adını/IP'i gizle",
'revdelete-hide-restricted' => 'Bu kısıtlamaları yöneticilere ve kullanıcılara uygula',
'revdelete-submit'          => 'Seçilen sürüme uygula',

# Diffs
'difference'              => '(Sürümler arası farklar)',
'lineno'                  => '$1. satır:',
'editcurrent'             => 'Sayfanın şu anki sürümünü değiştir',
'compareselectedversions' => 'Seçilen sürümleri karşılaştır',
'editundo'                => 'geriye al',

# Search results
'searchresults'         => 'Arama sonuçları',
'searchresulttext'      => '{{SITENAME}} içinde arama yapmak konusunda bilgi almak için [[Project:Arama|"{{SITENAME}} içinde arama"]] sayfasına bakabilirsiniz.',
'searchsubtitle'        => 'Aranan: "[[:$1]]" [[Special:Allpages/$1|&#x5B;Indeks&#x5D;]]',
'searchsubtitleinvalid' => 'Aranan: "$1"',
'noexactmatch'          => "Başlığı bu olan bir madde bulunamadı. Bu maddenin yazılmasını [[:$1|'''siz başlatabilirsiniz''']], ya da bu maddenin yazılması isteğini [[Project:Madde istekleri|istenen maddeler listesine]] ekleyebilirsiniz. Yeni bir madde yaratmadan önce lütfen site içinde deatylı arama yapınız. İstediğiniz madde başka bir adla zaten var olabilir.",
'titlematches'          => 'Madde adı eşleşiyor',
'notitlematches'        => 'Hiçbir başlıkta bulunamadı',
'textmatches'           => 'Sayfa metni eşleşiyor',
'notextmatches'         => 'Hiçbir sayfada bulunamadı',
'prevn'                 => 'önceki $1',
'nextn'                 => 'sonraki $1',
'viewprevnext'          => '($1) ($2) ($3).',
'showingresults'        => '<b>$2.</b> sonuçtan başlayarak <b>$1</b> sonuç aşağıdadır:',
'showingresultsnum'     => '<b>$2.</b> sonuçtan başlayarak <b>$3</b> sonuç aşağıdadır:',
'powersearch'           => 'Ara',
'powersearchtext'       => 'Arama yapılacak alanları seçin :<br />
$1<br />
$2 yönlendirmeleri listele &nbsp; Aranacak: $3 $9',
'searchdisabled'        => '{{SITENAME}} sitesinde arama yapma geçici olarak durdurulmuştur. Bu arada Google kullanarak {{SITENAME}} içinde arama yapabilirsiniz. Arama sitelerinde indekslemelerinin biraz eski kalmış olabileceğini göz önünde bulundurunuz.',

# Preferences page
'preferences'           => 'Tercihler',
'prefsnologin'          => 'Oturum açık değil',
'qbsettings'            => 'Hızlı erişim sütun ayarları',
'changepassword'        => 'Şifre değiştir',
'skin'                  => 'Tema',
'math'                  => 'Matematiksel semboller',
'dateformat'            => 'Tarih gösterimi',
'datedefault'           => 'Tercih yok',
'datetime'              => 'Tarih ve saat',
'math_unknown_error'    => 'bilinmeyen hata',
'prefs-personal'        => 'Kullanıcı bilgileri',
'prefs-rc'              => 'Son değişiklikler',
'prefs-watchlist'       => 'İzleme listesi',
'prefs-watchlist-days'  => 'İzleme listesinde görüntülenecek gün sayısı:',
'prefs-watchlist-edits' => 'Genişletilmiş izleme listesinde gösterilecek değişiklik sayısı:',
'prefs-misc'            => 'Diğer ayarlar',
'saveprefs'             => 'Değişiklikleri kaydet',
'resetprefs'            => 'Ayarları ilk durumuna getir',
'oldpassword'           => 'Eski parola',
'newpassword'           => 'Yeni parola',
'retypenew'             => 'Yeni parolayı tekrar girin',
'textboxsize'           => 'Sayfa yazma alanı',
'rows'                  => 'Satır',
'columns'               => 'Sütun',
'searchresultshead'     => 'Arama',
'resultsperpage'        => 'Sayfada gösterilecek bulunan madde sayısı',
'contextlines'          => 'Bulunan madde için ayrılan satır sayısı',
'contextchars'          => 'Satırdaki karakter sayısı',
'recentchangescount'    => 'Son değişiklikler sayfasındaki madde sayısı',
'savedprefs'            => 'Ayarlar kaydedildi.',
'timezonelegend'        => 'Saat dilimi',
'timezonetext'          => 'Viki sunucusu (UTC/GMT) ile aranızdaki saat farkı. (Türkiye için +02:00)',
'localtime'             => 'Şu an sizin saatiniz',
'timezoneoffset'        => 'Saat farkı',
'servertime'            => 'Viki sunucusunda şu anki saat',
'guesstimezone'         => 'Tarayıcınız sizin yerinize doldursun',
'allowemail'            => 'Diğer kullanıcılar size e-posta atabilsin',
'defaultns'             => 'Aramayı aşağıdaki seçili alanlarda yap.',
'default'               => 'orijinal',
'files'                 => 'Dosyalar',

# User rights
'userrights-lookup-user'     => 'Kullanıcı gruplarını düzenle',
'userrights-user-editname'   => 'Kullanıcı adı giriniz:',
'editusergroup'              => 'Kullanıcı grupları düzenle',
'userrights-editusergroup'   => 'Kullanıcı grupları düzenle',
'userrights-groupsmember'    => 'İçinde olduğu gruplar:',
'userrights-groupsavailable' => 'Varolan gruplar:',

# Groups
'group'            => 'Grup:',
'group-bot'        => 'Botlar',
'group-sysop'      => 'Yöneticiler',
'group-bureaucrat' => 'Bürokratlar',
'group-all'        => '(hepsi)',

'group-sysop-member'      => 'Yönetici',
'group-bureaucrat-member' => 'Bürokrat',

'grouppage-bot'        => 'Project:Botlar',
'grouppage-sysop'      => 'Project:Yöneticiler',
'grouppage-bureaucrat' => '{{ns:project}}:Bürokratlar',

# Recent changes
'recentchanges'     => 'Son değişiklikler',
'recentchangestext' => 'Yapılan en son değişiklikleri bu sayfadan izleyin.',
'rcnote'            => '$3 (UTC) tarihinde son <strong>$2</strong> günde yapılan <strong>$1</strong> değişiklik:',
'rcnotefrom'        => '<b>$2</b> tarihinden itibaren yapılan değişiklikler aşağıdadır (en fazla <b>$1</b> adet madde gösterilmektedir).',
'rclistfrom'        => '$1 tarihinden beri yapılan değişiklikleri göster',
'rcshowhideminor'   => 'küçük değişiklikleri $1',
'rcshowhidebots'    => 'botları $1',
'rcshowhideliu'     => 'kayıtlı kullanıcıları $1',
'rcshowhideanons'   => 'anonim kullanıcıları $1',
'rcshowhidepatr'    => 'izlenmiş değişiklikleri $1',
'rcshowhidemine'    => 'değişikliklerimi $1',
'rclinks'           => 'Son $2 günde yapılan son $1 değişikliği göster;<br /> $3',
'diff'              => 'fark',
'hist'              => 'geçmiş',
'hide'              => 'gizle',
'show'              => 'göster',
'minoreditletter'   => 'K',
'newpageletter'     => 'Y',
'newsectionsummary' => '/* $1 */ yeni başlık',

# Recent changes linked
'recentchangeslinked' => 'İlgili değişiklikler',

# Upload
'upload'                      => 'Dosya yükle',
'uploadbtn'                   => 'Dosya yükle',
'reupload'                    => 'Yeniden yükle',
'reuploaddesc'                => 'Yükleme formuna geri dön.',
'uploadnologin'               => 'Oturum açık değil',
'uploadnologintext'           => 'Dosya yükleyebilmek için [[Special:Userlogin|oturum aç]]manız gerekiyor.',
'upload_directory_read_only'  => 'Dosya yükleme dizinine ($1) web sunucusunun yazma izni yok.',
'uploaderror'                 => 'Yükleme hatası',
'uploadtext'                  => "Dosya yüklemek için aşağıdaki formu kullanın,
Önceden yüklenmiş resimleri görmek için  [[Special:Imagelist|resim listesine]] bakın,
yüklenenler ve silinmişler [[Special:Log/upload|yükleme kaydı sayfasında da]] görülebilir.

Sayfaya resim koymak için;
*'''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Örnek.jpg]]</nowiki>'''
*'''<nowiki>[[</nowiki>{{ns:Image}}<nowiki>:Örnek.png|açıklama]]</nowiki>'''
veya doğrudan bağlantı için
*'''<nowiki>[[</nowiki>{{ns:Media}}<nowiki>:Örnek.ogg]]</nowiki>'''",
'uploadlog'                   => 'yükleme kaydı',
'uploadlogpage'               => 'Dosya yükleme kayıtları',
'uploadlogpagetext'           => 'Aşağıda en son eklenen dosyaların bir listesi bulunmaktadır.',
'filename'                    => 'Dosya',
'filedesc'                    => 'Dosya ile ilgili açıklama',
'fileuploadsummary'           => 'Açıklama:',
'filestatus'                  => 'Telif hakkı durumu',
'filesource'                  => 'Kaynak',
'uploadedfiles'               => 'Yüklenen dosyalar',
'ignorewarning'               => 'Uyarıyı önemsemeyip dosyayı yükle',
'ignorewarnings'              => 'Uyarıyı önemseme',
'badfilename'                 => 'Görüntü dosyasının ismi "$1" olarak değiştirildi.',
'largefileserver'             => 'Bu dosyanın uzunluğu sunucuda izin verilenden daha büyüktür.',
'emptyfile'                   => 'Yüklediğiniz dosya boş görünüyor. Bunun sebebi dosya adındaki bir yazım hatası olabilir. Lütfen dosyayı gerçekten tyüklemek isteyip istemediğinizden emin olun.',
'fileexists'                  => 'Bu isimde bir dosya mevcut. Eğer değiştirmekten emin değilseniz ilk önce $1 dosyasına bir gözatın.',
'fileexists-forbidden'        => 'Bu isimde zaten dosya var; lütfen farklı bir isimle yeniden yükleyin. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Bu isimde bir dosya ortak havuzda zaten mevcut; lütfen geri gidip dosyayı yeni bir isimle yükleyiniz. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Yükleme başarılı',
'uploadwarning'               => 'Yükleme uyarısı',
'savefile'                    => 'Dosyayı kaydet',
'uploadedimage'               => 'Yüklenen: "[[$1]]"',
'uploaddisabled'              => 'Geçici olarak şu anda herhangi bir dosya yüklenmez. Biraz sonra bir daha deneyiniz.',
'uploaddisabledtext'          => 'Bu wikide dosya yükleme özelliği iptal edilmiştir.',
'uploadcorrupt'               => 'Bu dosya ya bozuk ya da uzantısı yanlış. Dosyayı kontrol edip, tekrar yüklemeyi deneyin.',
'uploadvirus'                 => 'Bu dosya virüslüdür! Detayları: $1',
'sourcefilename'              => 'Yüklemek istediğiniz dosya',
'destfilename'                => '{{SITENAME}} sitesindeki dosya adı',
'filewasdeleted'              => 'Bu isimde bir dosya yakın zamanda yüklendi ve ardından yöneticiler tarafından silindi. Dosyayı yüklemeden önce, $1 sayfasına bir göz atınız.',

'nolicense' => 'Hiçbirini seçme',

# Image list
'imagelist'                 => 'Resim listesi',
'imagelisttext'             => "Aşağıdaki liste '''$2''' göre dizilmiş '''$1''' adet dosyayı göstermektedir.",
'getimagelist'              => 'dosya listesi hazırlanıyor',
'ilsubmit'                  => 'Ara',
'showlast'                  => 'En son $1 dosyayı $2 göster.',
'byname'                    => 'alfabetik sırayla',
'bydate'                    => 'kronolojik sırayla',
'bysize'                    => 'boyut sırasıyla',
'imgdelete'                 => 'sil',
'imgdesc'                   => 'tanım',
'imagelinks'                => 'Kullanıldığı sayfalar',
'linkstoimage'              => 'Bu görüntü dosyasına bağlantısı olan sayfalar:',
'nolinkstoimage'            => 'Bu görüntü dosyasına bağlanan sayfa yok.',
'sharedupload'              => 'Bu dosya ortak alana yüklenmiştir ve diğer projelerde de kullanılıyor olabilir.',
'shareduploadwiki-linktext' => 'dosya açıklama sayfası',
'noimage'                   => 'Bu isimde dosya yok. Siz $1.',
'noimage-linktext'          => 'yükleyebilirsiniz',
'uploadnewversion-linktext' => 'Dosyanın yenisini yükleyin',

# MIME search
'mimesearch' => 'MIME araması',
'mimetype'   => 'MIME tipi:',
'download'   => 'yükle',

# Unwatched pages
'unwatchedpages' => 'İzlenmeyen sayfalar',

# List redirects
'listredirects' => 'Yönlendirmeleri listele',

# Unused templates
'unusedtemplates'     => 'Kullanılmayan şablonlar',
'unusedtemplatestext' => 'Bu sayfa şablon alan adında bulunan ve diğer sayfalara eklenmemiş olan şablonları göstermektedir. Şablonlara olan diğer bağlantıları da kontrol etmeden silmeyiniz.',
'unusedtemplateswlh'  => 'diğer bağlantılar',

# Random page
'randompage' => 'Rastgele sayfa',

# Random redirect
'randomredirect' => 'Rastgele yönlendirme',

# Statistics
'statistics'    => 'İstatistikler',
'sitestats'     => '{{SITENAME}} sitesi istatistikleri',
'userstats'     => 'Kullanıcı istatistikleri',
'sitestatstext' => "{{SITENAME}} sitesinde şu anda '''\$2''' geçerli sayfa mevcuttur.

Bu sayıya; \"yönlendirme\", \"tartışma\", \"resim\", \"kullanıcı\", \"yardım\", \"{{SITENAME}}\", \"şablon\" alanlarındakiler ve iç bağlantı içermeyen maddeler dahil değildir. Geçerli madde sayısına bu sayfaların sayısı eklendiğinde ise toplam '''\$1''' sayfa mevcuttur.

\$8 tane dosya yüklenmiştir.

Site kurulduğundan bu güne kadar toplam '''\$4''' sayfa değişikliği ve sayfa başına ortalama '''\$5''' katkı olmuştur.

Toplam sayfa görüntülenme sayısı '''\$3''', değişiklik başına görüntüleme sayısı '''\$6''' olmuştur.

Şu andaki [http://meta.wikimedia.org/wiki/Help:Job_queue iş kuyruğu] sayısı '''\$7'''.",
'userstatstext' => "Şu anda '''$1''' kayıtlı kullanıcımız var. Bunlardan <b>$2</b> tanesi (ya da %$4) yöneticidir. (bakın $3)",

'disambiguations'     => 'Anlam ayrım sayfaları',
'disambiguationspage' => 'Template:Anlam ayrımı',

'doubleredirects'     => 'Yönlendirmeye olan yönlendirmeler',
'doubleredirectstext' => 'Her satır, ikinci yönlendirme metninin ilk satırının (genellikle ikinci yönlendirmenin de işaret etmesi gereken "asıl" hedefin) yanısıra ilk ve ikinci yönlendirmeye bağlantılar içerir.',

'brokenredirects'     => 'Varolmayan maddeye yapılmış yönlendirmeler',
'brokenredirectstext' => 'Bu sayfa mevcut olmayan sayfalara yönlendirme içeren bozuk sayfaları listeler.',

# Miscellaneous special pages
'nbytes'                  => '$1 bayt',
'ncategories'             => '$1 kategori',
'nlinks'                  => '$1 bağlantı',
'nmembers'                => '$1 üye',
'nrevisions'              => '$1 değişiklik',
'nviews'                  => '$1 görünüm',
'lonelypages'             => 'Kendisine hiç bağlantı olmayan sayfalar',
'uncategorizedpages'      => 'Herhangi bir kategoride olmayan sayfalar',
'uncategorizedcategories' => 'Herhangi bir kategoride olmayan kategoriler',
'uncategorizedimages'     => 'Herhangi bir kategoride olmayan resimler',
'unusedcategories'        => 'Kullanılmayan kategoriler',
'unusedimages'            => 'Kullanılmayan resimler',
'popularpages'            => 'Popüler sayfalar',
'wantedcategories'        => 'İstenen kategoriler',
'wantedpages'             => 'İstenen sayfalar',
'mostlinked'              => 'Kendisine en fazla bağlantı verilmiş sayfalar',
'mostlinkedcategories'    => 'En çok maddeye sahip kategoriler',
'mostcategories'          => 'En fazla kategoriye bağlanmış sayfalar',
'mostimages'              => 'En çok kullanılan resimler',
'mostrevisions'           => 'En çok değişikliğe uğramış sayfalar',
'allpages'                => 'Tüm sayfalar',
'allpages-summary'        => 'Bu sayfa sitedeki bütün sayfaları listeler.',
'shortpages'              => 'Kısa sayfalar',
'longpages'               => 'Uzun sayfalar',
'deadendpages'            => 'Başka sayfalara bağlantısı olmayan sayfalar',
'deadendpagestext'        => 'Bu sayfa, diğer sayfalara bağlantısı olmayan sayfaları listeler.',
'listusers'               => 'Kullanıcı listesi',
'specialpages'            => 'Özel sayfalar',
'spheading'               => 'Tüm kullanıcıları ilgilendirebilecek özel sayfalar',
'restrictedpheading'      => 'Yöneticilerin yetkileri ile ilgili özel sayfalar',
'rclsub'                  => '("$1" sayfasına bağlanan sayfalarda)',
'newpages'                => 'Yeni sayfalar',
'ancientpages'            => 'En son değişiklik tarihi en eski olan maddeler',
'ancientpages-summary'    => 'En son değişiklik yapıldığından bu yana en fazla zaman geçmiş, geliştirilmesi unutulmuş sayfaların listesi.',
'intl'                    => 'Diller arası bağlantılar',
'move'                    => 'Adını değiştir',
'movethispage'            => 'Sayfayı taşı',
'unusedcategoriestext'    => 'Aşağıda bulunan kategoriler mevcut olduğu halde, hiçbir madde ya da kategori tarafından kullanılmıyor.',
'notargettitle'           => 'Hedef yok',

# Book sources
'booksources'         => 'Kaynak kitaplar',
'booksources-summary' => 'Bu sayfada bir ISBN numarası girip fazla bilgi alabilirsiniz.',

'categoriespagetext' => 'Vikide aşağıdaki kategoriler mevcuttur.',
'data'               => 'Veri',
'userrights'         => 'Kullanıcı hakları yönetimi.',
'groups'             => 'Kullanıcı grupları',
'alphaindexline'     => '$1 sayfası ile $2 sayfası arasındaki sayfaların listesi',
'version'            => 'Sürüm',

# Special:Log
'specialloguserlabel'  => 'Kullanıcı:',
'speciallogtitlelabel' => 'Başlık:',
'log'                  => 'Kayıtlar',
'all-logs-page'        => 'Tüm kayıtlar',
'alllogstext'          => '[[Special:Log/upload|Yükleme]], [[Special:Log/delete|silme]], [[Special:Log/move|taşıma]], [[Special:Log/protect|koruma altına alma]], [[Special:Log/newusers|yeni kullanıcı]], [[Special:Log/renameuser|kullanıcıların yeniden adlandırmaları]], [[Special:Log/block|erişim engelleme]], [[Special:Log/rights|yönetici hareketlerinin]] ve [[Special:Log/makebot|botların durumunun]] tümünün kayıtları. 

Kayıt tipini, kullanıcı ismini, sayfa ismini girerek listeyi daraltabilirsiniz.',
'logempty'             => 'Kayıtlarda eşleşen bilgi yok.',

# Special:Allpages
'nextpage'          => 'Sonraki sayfa ($1)',
'allpagesfrom'      => 'Listelemeye başlanılacak harfler:',
'allarticles'       => 'Tüm maddeler',
'allinnamespace'    => 'Tüm sayfalar ($1 sayfaları)',
'allnotinnamespace' => 'Tüm sayfalar ($1 alanında olmayanlar)',
'allpagesprev'      => 'Önceki',
'allpagesnext'      => 'Sonraki sayfa',
'allpagessubmit'    => 'Getir',
'allpagesprefix'    => 'Buraya yazdığınız harflerle başlayan sayfaları listeleyin:',
'allpagesbadtitle'  => 'Girilen sayfa ismi diller arası bağlantı ya da vikiler arası bağlantı içerdiğinden geçerli değil. Başlıklarda kullanılması yasak olan bir ya da daha çok karakter içeriyor olabilir.',

# E-mail user
'mailnologin'     => 'Gönderi adresi yok.',
'mailnologintext' => 'Diğer kullanıcılara e-posta gönderebilmeniz için [[Special:Userlogin|oturum aç]]malısınız ve [[Special:Preferences|tercihler]] sayfasında geçerli bir e-posta adresiniz olmalı.',
'emailuser'       => 'Kullanıcıya e-posta gönder',
'emailpage'       => 'Kullanıcıya e-posta gönder',
'emailpagetext'   => 'Aşağıdaki form kullanıcı hesabıyla ilişkilendirilmiş geçerli bir e-posta 
adresi olduğu takdirde ilgili kişiye bir e-posta gönderecek.
 
Yanıt alabilmeniz için "From" (Kimden) kısmına tercih formunda belirttiğiniz e-posta adresi eklenecek.',
'usermailererror' => 'Eposta hizmeti hata verdi:',
'defemailsubject' => '{{SITENAME}} e-posta',
'noemailtitle'    => 'e-posta adresi yok',
'noemailtext'     => 'Kullanıcı e-posta adresi belirtmemiş ya da diğer kullanıcılardan posta almak istemiyor.',
'emailfrom'       => 'Kimden',
'emailto'         => 'Kime',
'emailsubject'    => 'Konu',
'emailmessage'    => 'E-posta',
'emailsend'       => 'Gönder',
'emailccme'       => 'Mesajın bir kopyasını da bana gönder.',
'emailccsubject'  => "Mesajınızın bir kopyasını $1'e gönderin: $2",
'emailsent'       => 'E-posta gönderildi',
'emailsenttext'   => 'E-postanız gönderildi.',

# Watchlist
'watchlist'            => 'İzleme listem',
'mywatchlist'          => 'İzleme listem',
'watchlistfor'         => "('''$1''' için)",
'nowatchlist'          => 'İzleme listesinde hiçbir madde bulunmuyor.',
'watchlistanontext'    => 'Lütfen izleme listenizdeki maddeleri görmek yada değiştirmek için $1.',
'watchnologin'         => 'Oturum açık değil.',
'watchnologintext'     => 'İzleme listenizi değiştirebilmek için [[Special:Userlogin|oturum açmalısınız]].',
'addedwatch'           => 'İzleme listesine kaydedildi.',
'addedwatchtext'       => '"$1" adlı sayfa [[Special:Watchlist|izleme listenize]] kaydedildi.

Gelecekte, bu sayfaya ve ilgili tartışma sayfasına yapılacak değişiklikler burada listelenecektir.

Kolayca seçilebilmeleri için de [[Special:Recentchanges|son değişiklikler listesi]] başlığı altında koyu harflerle listeleneceklerdir.

Sayfayı izleme listenizden çıkarmak istediğinizde "sayfayı izlemeyi durdur" bağlantısına tıklayabilirsiniz.',
'removedwatch'         => 'İzleme listenizden silindi',
'removedwatchtext'     => '"$1" sayfası izleme listenizden silinmiştir.',
'watch'                => 'İzlemeye al',
'watchthispage'        => 'Sayfayı izle',
'unwatch'              => 'Sayfa izlemeyi durdur',
'unwatchthispage'      => 'Sayfa izlemeyi durdur',
'notanarticle'         => 'İçerik sayfası değil',
'watchnochange'        => 'Gösterilen zaman aralığında izleme listenizdeki sayfaların hiçbiri güncellenmemiş.',
'watchlist-details'    => 'Tartışma sayfaları hariç $1 sayfa izleme listenizdedir.',
'wlheader-enotif'      => '* E-mail ile haber verme açılmıştır.',
'wlheader-showupdated' => "* Son ziyaretinizden sonraki sayfa değişikleri '''kalın''' olarak gösterilmiştir.",
'watchmethod-recent'   => 'son değişiklikler arasında izledğiniz sayfalar aranıyor',
'watchmethod-list'     => 'izleme listenizdeki sayfalar kontrol ediliyor',
'watchlistcontains'    => 'İzleme listenizde $1 sayfa var.',
'wlnote'               => '{{CURRENTTIME}} {{CURRENTMONTHNAME}} {{CURRENTDAY}} (UTC) tarihinde son <b>$2</b> saatte yapılan $1 değişiklik aşağıdadır.',
'wlshowlast'           => 'Son $1 saati $2 günü göster $3',

'enotif_mailer'      => '{{SITENAME}} Bildirim Postası',
'enotif_reset'       => 'Tüm sayfaları ziyaret edilmiş olarak işaretle',
'enotif_newpagetext' => 'Yeni bir sayfa.',
'changed'            => 'değiştirildi',
'created'            => 'oluşturuldu',
'enotif_lastvisited' => "Son ziyaretinizden bu yana olan tüm değişiklikleri görmek için $1'e bakın.",
'enotif_body'        => 'Sayın $WATCHINGUSERNAME,

{{SITENAME}} sitesindeki $PAGETITLE başlıklı sayfa $PAGEEDITDATE tarihinde $PAGEEDITOR tarafından $CHANGEDORCREATED. Sayfanın son haline $PAGETITLE_URL adresinden ulaşabilirsiniz.

$NEWPAGE

Değişikliği yapan kullanıcının açıklaması: $PAGESUMMARY $PAGEMINOREDIT

Sayfayı değiştiren kullanıcıya erişim bilgileri:
e-posta: $PAGEEDITOR_EMAIL
viki: $PAGEEDITOR_WIKI

Bahsi geçen sayfayı ziyaret edinceye kadar sayfayla ilgili başka değişiklik bildirimi gönderilmeyecektir. İzleme listenizdeki tüm sayfalar bildirim durumlarını sıfırlayabilirsiniz.

              {{SITENAME}} sitesinin uyarı sistemi.

--
Ayarları değiştirmek için:
{{fullurl:Special:Watchlist/edit}}

Yardım ve öneriler için:
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Sayfayı sil',
'confirm'                     => 'Onayla',
'excontent'                   => "eski içerik: '$1'",
'excontentauthor'             => "eski içerik: '$1' ('$2' katkıda bulunmuş olan tek kullanıcı)",
'exbeforeblank'               => "Silinmeden önceki içerik: '$1'",
'exblank'                     => 'sayfa içeriği boş',
'confirmdelete'               => 'Silme işlemini onayla',
'deletesub'                   => '("$1" siliniyor)',
'historywarning'              => 'Uyarı: Silmek üzere olduğunuz sayfanın geçmişi vardır:',
'confirmdeletetext'           => 'Bu sayfayı veya dosyayı tüm geçmişi ile birlikte veritabanından kalıcı olarak silmek üzeresiniz.
Bu işlemden kaynaklı doğabilecek sonuçların farkında iseniz ve işlemin [[{{ns:project}}:Silme kuralları|Silme kurallarına]] uygun olduğuna eminseniz, işlemi onaylayın.',
'actioncomplete'              => 'İşlem tamamlandı.',
'deletedtext'                 => '"$1" silindi.
Yakın zamanda silinenleri görmek için: $2.',
'deletedarticle'              => '"$1" silindi',
'dellogpage'                  => 'Silme kayıtları',
'dellogpagetext'              => 'Aşağıdaki liste son silme kayıtlarıdır.',
'deletionlog'                 => 'silme kayıtları',
'reverted'                    => 'Önceki sürüm geri getirildi',
'deletecomment'               => 'Silme nedeni',
'rollback'                    => 'değişiklikleri geri al',
'rollback_short'              => 'geri al',
'rollbacklink'                => 'eski haline getir',
'rollbackfailed'              => 'geri alma işlemi başarısız',
'cantrollback'                => 'Sayfaya son katkıda bulunan kullanıcı, sayfaya katkıda bulunmuş tek kişi olduğu için, değişiklikler geri alınamıyor.',
'alreadyrolled'               => '[[User:$2|$2]] ([[User talk:$2|Talk]]) tarafından [[:$1]] sayfasında yapılmış son değişiklik geriye alınamıyor çünkü bu esnada başka biri sayfada değişiklik yaptı ya da başka biri sayfayı geriye aldı.

Son değişikliği yapan: [[User:$3|$3]] ([[User talk:$3|Talk]]).',
'editcomment'                 => 'Değiştirme notu: "<i>$1</i>" idi.', # only shown if there is an edit comment
'revertpage'                  => '[[User:$2|$2]] tarafından yapılan değişiklikler geri alınarak, [[User:$1|$1]] tarafından değiştirilmiş önceki sürüm geri getirildi.',
'protectlogpage'              => 'Koruma kayıtları',
'protectlogtext'              => 'Korumaya alma/kaldırma ile ilgili değişiklikleri görmektesiniz.
Daha fazla bilgi için [[Project:Koruma altına alınmış sayfa]] sayfasına bakabilirsiniz.',
'protectedarticle'            => '"[[$1]]" koruma altında alındı',
'unprotectedarticle'          => 'koruma kaldırıldı: "[[$1]]"',
'confirmprotect'              => 'Korumayı onayla',
'protectcomment'              => 'Koruma altına alma nedeni',
'unprotectsub'                => '(koruma kaldırılır "$1")',
'protect-text'                => '[[$1]] sayfasının koruma durumunu buradan görebilir ve değiştirebilirsiniz. Lütfen [[Project:Koruma politikası|koruma politikasına]] uygun hareket ettiğinizden emin olunuz.',
'protect-default'             => '(standart)',
'protect-level-autoconfirmed' => 'kayıtlı olmayan değiştirmesin',
'protect-level-sysop'         => 'sadece yöneticiler',

# Restrictions (nouns)
'restriction-edit' => 'Düzenle',
'restriction-move' => 'Taşı',

# Undelete
'undelete'           => 'Silinmiş sayfaları göster',
'undeletepage'       => 'Sayfanın silinmiş sürümlerine göz at ve geri getir.',
'viewdeletedpage'    => 'Silinen sayfalara bak',
'undeletebtn'        => 'Geri getir!',
'undeletereset'      => 'Vazgeç',
'undeletecomment'    => 'Neden:',
'undeletedarticle'   => '"$1" geri getirildi.',
'undeletedrevisions' => 'Toplam $1 kayıt geri getirildi.',
'cannotundelete'     => 'Sayfayı ya da medyayı sizden önce bir başka kullanıcı geri getirdiğinden dolayı sizin geri getirme işleminiz geçersiz.',

# Namespace form on various pages
'namespace'      => 'Alan adı:',
'invert'         => 'Seçili haricindekileri göster',
'blanknamespace' => '(Ana)',

# Contributions
'contributions' => 'Kullanıcının katkıları',
'mycontris'     => 'Katkılarım',
'contribsub2'   => '$1 ($2)',
'nocontribs'    => 'Bu kriterlere uyan değişiklik bulunamadı',
'uctop'         => '(son)',

'sp-contributions-newest'      => 'En yeni',
'sp-contributions-oldest'      => 'En eski',
'sp-contributions-newer'       => 'Sonraki $1',
'sp-contributions-older'       => 'Önceki $1',
'sp-contributions-newbies-sub' => 'Yeni kullanıcılar için',

# What links here
'whatlinkshere'      => 'Sayfaya bağlantılar',
'whatlinkshere-page' => 'Sayfa:',
'linklistsub'        => '(Bağlantı listesi)',
'linkshere'          => 'Buraya bağlantısı olan sayfalar:',
'nolinkshere'        => 'Buraya bağlanan sayfa yok.',
'isredirect'         => 'yönlendirme sayfası',
'istemplate'         => 'ekleme',

# Block/unblock
'blockip'            => "Bu IP'den erişimi engelle",
'blockiptext'        => "Aşağıdaki formu kullanarak belli bir IP'nin veya kayıtlı kullanıcının değişiklik yapmasını engelleyebilirsiniz. Bu sadece vandalizmi engellemek için ve [[{{MediaWiki:policy-url}}|kurallara]] uygun olarak yapılmalı. Aşağıya mutlaka engelleme ile ilgili bir açıklama yazınız. (örnek: -Şu- sayfalarda vandalizm yapmıştır).",
'ipaddress'          => 'IP Adresi',
'ipadressorusername' => 'IP adresi veya kullanıcı adı',
'ipbexpiry'          => 'Bitiş süresi',
'ipbreason'          => 'Sebep',
'ipbsubmit'          => 'Bu kullanıcıyı engelle',
'ipbother'           => 'Farklı zaman',
'ipboptions'         => '15 dakika:15 minutes,1 saat:1 hour,3 saat:3 hours,24 saat:24 hours,48 saat:48 hours,1 hafta:1 week,1 ay:1 month,süresiz:infinite',
'ipbotheroption'     => 'farklı',
'badipaddress'       => 'Geçersiz IP adresi',
'blockipsuccesssub'  => 'IP adresi engelleme işlemi başarılı oldu',
'blockipsuccesstext' => '"$1" engellendi.
<br />[[Special:Ipblocklist|IP adresi engellenenler]] listesine bakınız.',
'unblockip'          => 'Kullanıcının engellemesini kaldır',
'ipusubmit'          => 'Bu adresin engellemesini kaldır',
'ipblocklist'        => 'Erişimi durdurulmuş kullanıcılar ve IP adresleri listesi',
'blocklistline'      => '$1, $2 blok etti: $3 ($4)',
'infiniteblock'      => 'süresiz',
'expiringblock'      => '$1 tarihinde doluyor',
'anononlyblock'      => 'sadece anonim',
'createaccountblock' => 'hesap yaratımı engellendi',
'blocklink'          => 'engelle',
'unblocklink'        => 'engellemeyi kaldır',
'contribslink'       => 'Katkılar',
'autoblocker'        => 'Otomatik olarak engellendiniz çünkü yakın zamanda IP adresiniz "[[User:$1|$1]]" kullanıcısı tarafından  kullanılmıştır. $1 isimli kullanıcının engellenmesi için verilen sebep: "\'\'\'$2\'\'\'"',
'blocklogpage'       => 'Erişim engelleme kayıtları',
'blocklogentry'      => '"[[$1]]" erişimi $2 durduruldu. Sebep',
'blocklogtext'       => 'Burada kullanıcı erişimine yönelik engelleme ya da engelleme kaldırma kayıtları listelenmektedir. Otomatik  IP adresi engellemeleri listeye dahil değildir. Şu anda erişimi durdurulmuş kullanıcıları [[Special:Ipblocklist|IP engelleme listesi]] sayfasından görebilirsiniz.',
'unblocklogentry'    => '$1 kullanıcının engellemesi kaldırıldı',
'ipb_expiry_invalid' => 'Geçersiz bitiş zamanı.',
'ip_range_invalid'   => 'Geçersiz IP aralığı.',

# Developer tools
'lockdb'              => 'Veritabanı kilitli',
'unlockdb'            => 'Veritabanı kilitini aç',
'unlockconfirm'       => 'Evet, veritabanının kilidini açmak istediğimden eminim.',
'lockbtn'             => 'Veritabanı kilitli',
'unlockdbsuccesssub'  => 'Veritabanı kiliti açıldı.',
'unlockdbsuccesstext' => 'Veritanı kilidi açıldı.',
'databasenotlocked'   => 'Veritabanı kilitli değil.',

# Move page
'movepage'                => 'İsim değişikliği',
'movepagetext'            => "Aşağıdaki form kullanılarak sayfanın adı değiştirilir. Beraberinde tüm geçmiş kayıtları da yeni isme aktarılır. Eski isim yeni isme yönlendirme haline dönüşür. Eski başlığa dogru olan bağlantılar olduğu gibi kalır; çift veya geçersiz yönlendirmeleri [[Special:Maintenance|kontrol ediniz.]] Yapacağınız bu değişikllike tüm bağlantıların olması gerektiği gibi çalıştığından sizin sorumlu olduğunuzu unutmayınız.

Eğer yeni isimde bir isim zaten mevcutsa, isim değişikliği '''yapılmayacaktır''', ancak varolan sayfa içerik olarak boş ise veya sadece yönlendirme ise ve hiç geçmiş hali yoksa isim değişikliği mümkün olacaktır. Bu yanı zamanda demektir ki, yaptığınız isim değişikliğini ilk ismine değiştirerek geri alabilirsiniz ve hiç bir başka sayfaya da dokunmamış olursunuz.

<b>UYARI!</b>
Bu değişim popüler bir sayfa için beklenmeyen sonuçlar doğurabilir; lütfen değişikliği yapmadan önce olabilecekleri göz önüne alın.",
'movepagetalktext'        => "İlişikteki tartışma sayfası da (eğer varsa) otomatik olarak yeni isme taşınacaktır. Ama şu durumlarda '''taşınmaz''':

*Alanlar arası bir taşıma ise, (örnek: \"Project:\" --> \"Help:\")
*Yeni isimde bir tartışma sayfası zaten var ise,
*Alttaki kutucuğu seçmediyseniz.

Bu durumlarda sayfayı kendiniz aktarmalısınız.",
'movearticle'             => 'Eski isim',
'movenologin'             => 'Sistemde değilsiniz.',
'movenologintext'         => 'Sayfanın adını değiştirebilmek için kayıtlı ve [[Special:Userlogin|sisteme]] giriş yapmış olmanız gerekmektedir.',
'newtitle'                => 'Yeni isim',
'movepagebtn'             => 'İsmi değiştir',
'pagemovedsub'            => 'İsim değişikliği tamamlandı.',
'articleexists'           => 'Bu isimde bir sayfa bulunmakta veya seçmiş olduğunuz isim geçersizdir.
Lütfen başka bir isim deneyiniz.',
'movedto'                 => 'taşındı:',
'movetalk'                => 'Varsa "tartışma" sayfasını da aktar.',
'talkpagemoved'           => 'İlgili tartışma sayfası da aktarıldı.',
'talkpagenotmoved'        => 'İlgili tartışma sayfası <strong>aktarılmadı</strong>.',
'1movedto2'               => '[[$1]] sayfasının yeni adı: [[$2]]',
'1movedto2_redir'         => '[[$1]] başlığı [[$2]] sayfasına yönlendirildi',
'movelogpage'             => 'İsim değişikliği kayıtları',
'movelogpagetext'         => 'Aşağıda bulunan liste adı değiştirilmiş sayfaları gösterir.',
'movereason'              => 'Sebep',
'revertmove'              => 'geriye al',
'delete_and_move'         => 'Sil ve taşı',
'delete_and_move_text'    => '==Silinmesi gerekiyor==

"[[$1]]" isimli bir sayfa zaten mevcut. O sayfayı silerek, isim değişikliğini gerçekleştirmeye devam etmek istiyor musunuz?',
'delete_and_move_confirm' => 'Evet, sayfayı sil',
'delete_and_move_reason'  => 'İsim değişikliğinin gerçekleşmesi için silindi.',
'selfmove'                => 'Olmasını istediğiniz isim ile mevcut isim aynı. Değişiklik mümkün değil.',

# Export
'export'        => 'Sayfa kaydet',
'exporttext'    => 'You can export the text and editing history of a particular page or
set of pages wrapped in some XML. This can be imported into another wiki using MediaWiki
via the Special:Import page.

To export pages, enter the titles in the text box below, one title per line, and
select whether you want the current version as well as all old versions, with the page
history lines, or just the current version with the info about the last edit.

In the latter case you can also use a link, e.g. [[Special:Export/{{int:Mainpage}}]] for the page {{int:Mainpage}}.',
'exportcuronly' => 'Geçmiş sürümleri almadan sadece son sürümü al',

# Namespace 8 related
'allmessages'               => 'Viki arayüz metinleri',
'allmessagesname'           => 'İsim',
'allmessagesdefault'        => 'Orjinal metin',
'allmessagescurrent'        => 'Kullanımdaki metin',
'allmessagestext'           => "Bu liste  MediaWiki'de mevcut olan tüm terimlerin listesidir",
'allmessagesnotsupportedDB' => "'''\$wgUseDatabaseMessages''' kapalı olduğu için '''Special:Allmessages''' kullanıma açık değil.",
'allmessagesfilter'         => 'Metin ayrıştırıcı filtresi:',
'allmessagesmodified'       => 'Sadece değiştirilmişleri göster',

# Thumbnails
'thumbnail-more' => 'Büyüt',
'missingimage'   => '<b>Bulunmayan resim</b><br /><i>$1</i>',
'filemissing'    => 'Dosya bulunmadı',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Kişisel sayfam',
'tooltip-pt-anonuserpage'         => 'The user page for the ip you',
'tooltip-pt-mytalk'               => 'Mesaj sayfam',
'tooltip-pt-anontalk'             => 'Bu IP adresinden yapılmış değişiklikleri tartış',
'tooltip-pt-preferences'          => 'Ayarlarım',
'tooltip-pt-watchlist'            => 'İzlemeye aldığım sayfalar',
'tooltip-pt-mycontris'            => 'Yaptığım katkıların listesi',
'tooltip-pt-login'                => 'Oturum açmanız tavsiye olunur ama mecbur değilsiniz.',
'tooltip-pt-anonlogin'            => 'Oturum açmanız tavsiye olunur ama mecbur değilsiniz.',
'tooltip-pt-logout'               => 'Sistemden çık',
'tooltip-ca-talk'                 => 'İçerik ile ilgili görüş belirt',
'tooltip-ca-edit'                 => 'Bu sayfayı değiştirebilirsiniz. Kaydetmeden önce önizleme yapmayı unutmayın.',
'tooltip-ca-addsection'           => 'Bu tartışmaya yorum ekleyin.',
'tooltip-ca-viewsource'           => 'Bu sayfa kormu altında. Kaynak kodunu sadece görebilirsiniz. Değiştiremezsiniz.',
'tooltip-ca-history'              => 'Bu sayfanın geçmiş versiyonları.',
'tooltip-ca-protect'              => 'Bu sayfayı koru',
'tooltip-ca-delete'               => 'Sayfayı sil',
'tooltip-ca-undelete'             => 'Sayfayı silinmeden önceki haline geri getirin',
'tooltip-ca-move'                 => 'Sayfanın adını değiştir',
'tooltip-ca-watch'                => 'Bu sayfayı izlemeye al',
'tooltip-ca-unwatch'              => 'Bu sayfayı izlemeyi bırakın',
'tooltip-search'                  => '{{SITENAME}} içinde ara',
'tooltip-p-logo'                  => 'Ana sayfa',
'tooltip-n-mainpage'              => 'Başlangıç sayfasına dönün',
'tooltip-n-portal'                => 'Proje üzerine, ne nerdedir, neler yapılabilir',
'tooltip-n-currentevents'         => 'Güncel olaylarla ilgili son bilgiler',
'tooltip-n-recentchanges'         => 'Vikide yapılmış son değişikliklerin listesi.',
'tooltip-n-randompage'            => 'Rastgele bir maddeye gidin',
'tooltip-n-help'                  => 'Yardım almak için.',
'tooltip-n-sitesupport'           => 'Maddi destek',
'tooltip-t-whatlinkshere'         => 'Bu sayfaya bağlantı vermiş diğer viki sayfalarının listesi',
'tooltip-t-recentchangeslinked'   => 'Bu sayfaya bağlantı veren sayfalardaki son değişiklikler',
'tooltip-feed-rss'                => 'Bu sayfa için RSS beslemesi',
'tooltip-feed-atom'               => 'Bu sayfa için atom beslemesi',
'tooltip-t-contributions'         => 'Kullanıcının katkı listesini gör',
'tooltip-t-emailuser'             => 'Kullanıcıya e-posta gönder',
'tooltip-t-upload'                => 'Sisteme resim ya da medya dosyaları yükleyin',
'tooltip-t-specialpages'          => 'Tüm özel sayfaların listesini göster',
'tooltip-ca-nstab-main'           => 'Sayfayı göster',
'tooltip-ca-nstab-user'           => 'Kullanıcı sayfasını göster',
'tooltip-ca-nstab-media'          => 'Medya sayfasını göster',
'tooltip-ca-nstab-special'        => 'Bu özel sayfa olduğu için değişiklik yapamazsınız.',
'tooltip-ca-nstab-project'        => 'Proje sayfasını göster',
'tooltip-ca-nstab-image'          => 'Resim sayfasını göster',
'tooltip-ca-nstab-mediawiki'      => 'Sistem mesajını göster',
'tooltip-ca-nstab-template'       => 'Şablonu göster',
'tooltip-ca-nstab-help'           => 'Yardım sayfasını görmek için tıklayın',
'tooltip-ca-nstab-category'       => 'Kategori sayfasını göster',
'tooltip-minoredit'               => 'Küçük değişiklik olarak işaretle',
'tooltip-save'                    => 'Değişiklikleri kaydet',
'tooltip-preview'                 => 'Önizleme; kaydetmeden önce bu özelliği kullanarak değişikliklerinizi gözden geçirin!',
'tooltip-diff'                    => 'Metine yaptığınız değişiklikleri gösterir.',
'tooltip-compareselectedversions' => 'Seçilmiş iki sürüm arasındaki farkları göster.',
'tooltip-watch'                   => 'Sayfayı izleme listene ekle',

# Stylesheets
'common.css'   => '/* Buraya konulacak CSS kodu tüm temalarda etkin olur */',
'monobook.css' => '/* Buraya konulacak CSS kodu tüm Monobook teması kullanan tüm kullanıcılarda etkin olur */',

# Scripts
'common.js' => '/* Buraya konulacak JavaScript kodu sitedeki her kullanıcı için her sayfa yüklendiğinde çalışacaktır */',

# Attribution
'anonymous'        => '{{SITENAME}} sitesinin anonim kullanıcıları',
'siteuser'         => '{{SITENAME}} kullanıcı $1',
'lastmodifiedatby' => 'Sayfa en son $3 tarafından $2, $1 tarihinde değiştirildi.', # $1 date, $2 time, $3 user
'and'              => 've',
'others'           => 'diğerleri',
'siteusers'        => '{{SITENAME}} kullanıcılar $1',

# Spam protection
'spamprotectiontitle'    => 'Spam karşı koruma filtresi',
'spamprotectiontext'     => 'Kaydetmek istediğiniz sayfa spam filtresi tarafından blok edildi. Büyük ihtimalle bir dış bağlantıdan kaynaklanmaktadır.',
'subcategorycount'       => 'Bu kategoride $1 altkategori var.',
'categoryarticlecount'   => 'Bu kategoride $1 madde var.',
'category-media-count'   => 'Bu kategoride $1 dosya mevcuttur.',
'listingcontinuesabbrev' => '(devam)',

# Info page
'numwatchers' => 'izleyici sayısı: $1',

# Math options
'mw_math_png'    => 'Daima PNG resim formatına çevir',
'mw_math_simple' => 'Çok basitse HTML, değilse PNG',
'mw_math_html'   => 'Mümkünse HTML, değilse PNG',
'mw_math_source' => 'Değiştirmeden TeX olarak bırak  (metin tabanlı tarayıcılar için)',
'mw_math_modern' => 'Modern tarayıcılar için tavsiye edilen',
'mw_math_mathml' => 'Mümkünse MathML (daha deneme aşamasında)',

# Image deletion
'deletedrevision' => '$1 sayılı eski sürüm silindi.',

# Browsing diffs
'previousdiff' => '← Önceki sürümle aradaki fark',
'nextdiff'     => 'Sonraki sürümle aradaki fark →',

# Media information
'mediawarning' => "'''DİKKAT!''': Bu dosyada kötü amaçlı (virüs gibi) kısım bulunabilir ve işletim sisteminize zarar verebilir.
<hr>",
'imagemaxsize' => 'Resim açıklamalar sayfalarındaki resmin en büyük boyutu:',
'thumbsize'    => 'Küçük boyut:',

# Special:Newimages
'newimages'    => 'Yeni resimler',
'showhidebots' => '(botları $1)',
'noimages'     => 'Görecek bir şey yok.',

# Metadata
'metadata'          => 'Resim detayları',
'metadata-help'     => 'Bu dosyada, muhtemelen fotoğraf makinası ya da tarayıcı tarafından eklenmiş ek bilgiler mevcuttur. Eğer dosyada sonradan değişiklik yapıldıysa, bazı bilgiler yeni değişikliğe göre eski kalmış olabilir.',
'metadata-expand'   => 'Ayrıntıları göster',
'metadata-collapse' => 'Ayrıntıları gösterme',

# EXIF tags
'exif-imagewidth'               => 'Genişlik',
'exif-imagelength'              => 'Yükseklik',
'exif-compression'              => 'Sıkıştırma modeli',
'exif-datetime'                 => 'Dosyanın değişme tarih ve saati',
'exif-imagedescription'         => 'Resim başlığı',
'exif-make'                     => 'Kamera markası',
'exif-model'                    => 'Kamera modeli',
'exif-artist'                   => 'Yaratıcısı',
'exif-copyright'                => 'Telif hakkı sahibi',
'exif-exifversion'              => 'Exif sürümü',
'exif-flashpixversion'          => 'Desteklenen Flashpix sürümü',
'exif-colorspace'               => 'Renk aralığı',
'exif-componentsconfiguration'  => 'Her bir bileşenin anlamı',
'exif-compressedbitsperpixel'   => 'Resim sıkıştırma modu',
'exif-datetimeoriginal'         => 'Çekim saati ve tarihi',
'exif-datetimedigitized'        => 'Dijital işleme tarih ve saati',
'exif-exposuretime'             => 'Çekim süresi',
'exif-exposuretime-format'      => '$1 saniye ($2)',
'exif-fnumber'                  => 'F numarası',
'exif-spectralsensitivity'      => 'Spektral duyarlılık',
'exif-isospeedratings'          => 'ISO hızı',
'exif-aperturevalue'            => 'Diyafram açıklığı',
'exif-brightnessvalue'          => 'parlaklık',
'exif-meteringmode'             => 'Ölçüm modu',
'exif-lightsource'              => 'Işık durumu',
'exif-flash'                    => 'Flaş',
'exif-focallength'              => 'Mercek odak uzaklığı',
'exif-flashenergy'              => 'Flaş enerjisi',
'exif-exposureindex'            => 'Poz dizini',
'exif-filesource'               => 'Dosya kaynağı',
'exif-scenetype'                => 'Çekim tipi',
'exif-cfapattern'               => 'CFA modeli',
'exif-customrendered'           => 'İsteğe göre resim işleme',
'exif-digitalzoomratio'         => 'Yakınlaştırma oranı',
'exif-focallengthin35mmfilm'    => "35 mm'lik filmde odak uzaklığı",
'exif-contrast'                 => 'Karşıtlık',
'exif-saturation'               => 'Doygunluk',
'exif-sharpness'                => 'Netlik',
'exif-devicesettingdescription' => 'Aygıt ayar tanımları',
'exif-gpslatitude'              => 'Enlem',
'exif-gpslongitude'             => 'Boylam',
'exif-gpsaltituderef'           => 'Yükseklik referansı',
'exif-gpsaltitude'              => 'Yükseklik',
'exif-gpstimestamp'             => 'GPS saati (atom saati)',
'exif-gpssatellites'            => 'Ölçmek için kullandığı uydular',
'exif-gpsstatus'                => 'Alıcının durumu',
'exif-gpsspeedref'              => 'Sürat birimi',
'exif-gpstrack'                 => 'Hareket yönü',
'exif-gpsareainformation'       => 'GPS alanının adı',
'exif-gpsdatestamp'             => 'GPS tarihi',

# EXIF attributes
'exif-compression-1' => 'Sıkıştırılmamış',

'exif-orientation-3' => '180° döndürülmüş', # 0th row: bottom; 0th column: right

'exif-componentsconfiguration-0' => 'yok',

'exif-exposureprogram-0' => 'Tanımlanmamış',
'exif-exposureprogram-1' => 'Elle',

'exif-subjectdistance-value' => '$1 metre',

'exif-meteringmode-0'   => 'Bilinmiyor',
'exif-meteringmode-1'   => 'Orta',
'exif-meteringmode-255' => 'Diğer',

'exif-lightsource-0'  => 'Bilinmiyor',
'exif-lightsource-1'  => 'Gün ışığı',
'exif-lightsource-2'  => 'Floresan',
'exif-lightsource-4'  => 'Flaş',
'exif-lightsource-9'  => 'Açık',
'exif-lightsource-10' => 'Kapalı',
'exif-lightsource-11' => 'Gölge',
'exif-lightsource-15' => 'Beyaz floresan (WW 3200 – 3700K)',
'exif-lightsource-17' => 'A tipi standart ışık',
'exif-lightsource-18' => 'B tipi standart ışık',
'exif-lightsource-19' => 'C tipi standart ışık',

'exif-focalplaneresolutionunit-2' => 'inç',

'exif-sensingmethod-1' => 'Tanımsız',

'exif-customrendered-0' => 'Normal işlem',
'exif-customrendered-1' => 'İsteğe özel işleme',

'exif-scenecapturetype-0' => 'Standart',
'exif-scenecapturetype-2' => 'Portre',
'exif-scenecapturetype-3' => 'Gece çekimi',

'exif-gaincontrol-0' => 'Hiçbiri',

'exif-contrast-1' => 'Yumuşak',
'exif-contrast-2' => 'Sert',

'exif-subjectdistancerange-0' => 'Bilinmiyor',
'exif-subjectdistancerange-1' => 'Makro',

'exif-gpsstatus-a' => 'Ölçüm devam ediyor',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'km/s',

# External editor support
'edit-externally'      => 'Dosya üzerinde bilgisayarınızda bulunan uygulamalar ile değişiklikler yapın',
'edit-externally-help' => 'Daha fazla bilgi için metadaki [http://meta.wikimedia.org/wiki/Help:External_editors dış uygulama ayarları] (İngilizce) sayfasına bakabilirsiniz.',

# 'all' in various places, this might be different for inflected languages
'imagelistall'  => 'Tümü',
'watchlistall2' => 'Hepsini göster',
'namespacesall' => 'Hepsi',

# E-mail address confirmation
'confirmemail'            => 'E-posta adresini onayla',
'confirmemail_noemail'    => '[[Special:Preferences|Kullanıcı tercihlerinizde]] tanımlanmış uygun bir e-posta adresiniz yok.',
'confirmemail_text'       => "Viki'nin e-posta işlevlerini kullanmabilmek için, önce e-posta adresinizin
doğrulanması gerekiyor. Adresinize onay e-postası göndermek için aşağıdaki
butonu tıklayın. Gönderilecek iletide adresinizi onaylamak için tarayıcınızla 
erişebileceğiniz, onay kodu içeren bir bağlantı olacak.",
'confirmemail_send'       => 'Onay kodu gönder',
'confirmemail_sent'       => 'Onay e-postası gönderildi.',
'confirmemail_sendfailed' => 'Onay kodu gönderilemedi. Adreste geçersiz harf ya da işaret olmadığından emin misiniz?',
'confirmemail_invalid'    => 'Geçersiz onay kodu. Onay kodunun son kullanma tarihi geçmiş olabilir.',
'confirmemail_needlogin'  => 'E-posta adresinizi onaylamak için önce $1 yapmalısınız.',
'confirmemail_success'    => "E-posta adresiniz onaylandı. Oturum açıp Viki'nin tadını çıkarabilirsiniz.",
'confirmemail_loggedin'   => 'E-posta adresiniz onaylandı.',
'confirmemail_error'      => 'Onayınız bilinmeyen bir hata nedeniyle kaydedilemedi.',
'confirmemail_subject'    => '{{SITENAME}} e-posta adres onayı.',
'confirmemail_body'       => '$1 internet adresinden yapılan erişimle {{SITENAME}} sitesinde 
bu e-posta adresi ile ilişkilendirilen $2 kullanıcı hesabı 
açıldı.  

Bu e-posta adresinin bahsi geçen kullanıcı hesabına ait olduğunu
onaylamak ve {{SITENAME}} sitesindeki e-posta işlevlerini aktif hale 
getirmek için aşağıdakı bağlantıyı tıklayın.

$3

Bahsi geçen kullanıcı hesabı size ait değilse yapmanız gereken
birşey yok.

Bu onay kodu $4 tarihine kadar geçerli olacak.',

# Delete conflict
'deletedwhileediting' => 'Uyarı: Bu sayfa siz değişiklik yapmaya başladıktan sonra silinmiş!',
'confirmrecreate'     => "Bu sayfayı [[User:$1|$1]] ([[User talk:$1|mesaj]]) kullanıcısı siz sayfada değişiklik yaparken silmiştir, nedeni:
: ''$2''
Sayfayı baştan açmak isityorsanız, lütfen onaylayın.",

# HTML dump
'redirectingto' => 'Yönlendirme [[$1]]...',

# action=purge
'confirm_purge'        => 'Sayfa önbelleği temizlensin mi? $1',
'confirm_purge_button' => 'Tamam',

# AJAX search
'articletitles' => "''$1'' ile başlayan maddeler",

# Table pager
'ascending_abbrev' => 'küçükten büyüğe',

# Auto-summaries
'autosumm-blank'   => 'Sayfa boşaltıldı',
'autosumm-replace' => "Sayfanın içeriği '$1' ile değiştirildi",
'autoredircomment' => '[[$1]] sayfasına yönlendirildi',
'autosumm-new'     => 'Yeni sayfa: $1',

);
