<?php
require_once( "LanguageUtf8.php" );

/* private */ $wgNamespaceNamesTr = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Özel',
	NS_MAIN             => '',
	NS_TALK             => 'Tartışma',
	NS_USER             => 'Kullanıcı',
	NS_USER_TALK        => 'Kullanıcı_mesaj',
	NS_PROJECT          => $wgMetaNamespace,
	NS_PROJECT_TALK     => $wgMetaNamespace . '_tartışma',
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
) + $wgNamespaceNamesEn;

# Whether to use user or default setting in Language::date()

/* private */ $wgDateFormatsTr = array(
	MW_DATE_DEFAULT => 'Tercih yok',
	MW_DATE_MDY => '16:12, Ocak 15, 2001',
	MW_DATE_DMY => '16:12, 15 Ocak 2001',
	MW_DATE_YMD => '16:12, 2001 Ocak 15',
	MW_DATE_ISO => '2001-01-15 16:12:34'
);

/* private */ $wgAllMessagesTr = array(

# User preference toggles
'tog-editondblclick' => 'Makaleyi çift tıklayarak değiştirmeya başla (JavaScript)',
'tog-editsection' => 'Bölümleri [değiştir] bağlantıları ile değiştirme hakkı ver',
'tog-editsectiononrightclick' => 'Bölüm başlığına sağ tıklayarak bölümde değişikliğe izin ver.(JavaScript)',
'tog-editwidth' => 'Yazma alanı tam genişlikte olsun',
'tog-enotifminoredits' => 'Send me an email also for minor edits of pages',
'tog-enotifusertalkpages' => 'Send me an email when my user talk page is changed',
'tog-enotifwatchlistpages' => 'Send me an email on page changes',
'tog-externaldiff' => 'Karşılaştırmaları dış programa yaptır.',
'tog-externaleditor' => 'Değişiklikleri başka editör programı ile yap',
'tog-fancysig' => 'Ham imza (İmzanız yukarda belirttiğiniz gibi görünür. Sayfanıza otomatik bağlantı yaratılmaz)',
'tog-hideminor' => 'Küçük değişiklikleri Son değişiklikler sayfasında gizle',
'tog-highlightbroken' => 'Boş bağlantıları <a href="" class="new">bu şekilde</a> (alternatif: bu şekilde<a href="" class="internal">?</a>) göster.',
'tog-justify' => 'Paragraf satır genişliğini ayarla',
'tog-minordefault' => 'Değişikliği \'küçük değişiklik\' olarak seçili getir',
'tog-nocache' => 'Sayfaları bellekleme',
'tog-numberheadings' => 'Başlıkları otomatik numaralandır',
'tog-previewonfirst' => 'Değiştirmede önizlemeyi göster',
'tog-previewontop' => 'Önizlemeyi yazma alanın üstünde göster',
'tog-rememberpassword' => 'Parolayı hatırla',
'tog-showtoc' => 'İçindekiler tablosunu oluştur<br />(3 taneden fazla başlığı olan makaleler için)',
'tog-showtoolbar' => 'Değişiklik yaparken yardımcı düğmeleri göster. (JavaScript)',
'tog-underline' => 'Bağlatıların altını çiz',
'tog-usenewrc' => 'Gelişmiş son değişiklikler listesi (her tarayıcı için uygun değil)',
'tog-watchdefault' => 'Değişiklik yapılan makaleyi izleme listesine ekle',

'underline-always' => 'Daima',
'underline-default' => 'Tarayıcı karar versin',
'underline-never' => 'Hiçbir zaman',

'skinpreview' => '(Önizleme)',

# dates
'monday' => 'Pazartesi',
'tuesday' => 'Salı',
'wednesday' => 'Çarşamba',
'thursday' => 'Perşembe',
'friday' => 'Cuma',
'saturday' => 'Cumartesi',
'sunday' => 'Pazar',
'january' => 'Ocak',
'february' => 'Şubat',
'march' => 'Mart',
'april' => 'Nisan',
'may_long' => 'Mayıs',
'june' => 'Haziran',
'july' => 'Temmuz',
'august' => 'Ağustos',
'september' => 'Eylül',
'october' => 'Ekim',
'november' => 'Kasım',
'december' => 'Aralık',
'jan' => 'Ocak',
'feb' => 'Şubat',
'mar' => 'Mart',
'apr' => 'Nisan',
'may' => 'Mayıs',
'jun' => 'Haziran',
'jul' => 'Temmuz',
'aug' => 'Ağustos',
'sep' => 'Eylül',
'oct' => 'Ekim',
'nov' => 'Kasım',
'dec' => 'Aralık',
# Bits of text used by many pages:
#
'categories' => 'Sayfa Kategorileri',
'category' => 'kategori',
'category_header' => '"$1" kategorisindeki makaleler',
'subcategories' => 'Alt Kategoriler',

'mainpage' => 'Ana Sayfa',

'portal' => 'Topluluk portalı',
'portal-url' => '{{ns:4}}:Topluluk portalı',
'about' => 'Hakkında',
'aboutpage' => '{{ns:4}}:Hakkında',
'aboutsite' => '{{SITENAME}} Hakkında',
'article' => 'Makale',
'help' => 'Yardım',
'helppage' => 'Yardım:İçindekiler',
'sitesupport' => 'Bağışlar',
'sitesupport-url' => 'http://wikimediafoundation.org/wiki/Fundraising',
'faq' => 'SSS',
'faqpage' => '{{ns:4}}:SSS',
'edithelp' => 'Nasıl değiştirilir?',
'newwindow' => '(yeni bir pencerede açılır)',
'edithelppage' => 'Yardım:Sayfa nasıl değiştirilir',
'cancel' => 'İptal',
'qbfind' => 'Bul',
'qbbrowse' => 'Tara',
'qbedit' => 'Değiştir',
'qbpageoptions' => 'Bu sayfa',
'qbpageinfo' => 'Bağlam',
'qbmyoptions' => 'Sayfalarım',
'qbspecialpages' => 'Özel sayfalar',
'mypage' => 'Sayfam',
'mytalk' => 'Mesaj sayfam',
'navigation' => 'Sitede yol bulma',

# Metadata in edit box
'metadata_page' => '{{ns:4}}:Metadata',

'currentevents' => 'Güncel olaylar',
'currentevents-url' => 'Güncel olaylar',

'disclaimers' => 'Feragatname',
'errorpagetitle' => 'Hata',
'returnto' => '$1.',
'whatlinkshere' => 'Sayfaya bağlantılar',
'go' => 'Git',
'search' => 'Ara',
'history' => 'Sayfanın geçmişi',
'history_short' => 'Geçmiş',
'printableversion' => 'Basılmaya uygun görünüm',
'permalink' => 'Son haline bağlantı',
'edit' => 'Değiştir',
'editthispage' => 'Sayfayı değiştir',
'delete' => 'Sil',
'deletethispage' => 'Sayfayı sil',
'protect' => 'Korumaya al',
'protectthispage' => 'Sayfayı koruma altına al',
'unprotect' => 'Korumayı kaldır',
'unprotectthispage' => 'Sayfa korumasını kaldır',
'newpage' => 'Yeni sayfa',
'talkpage' => 'Sayfayı tartış',
'specialpage' => 'Özel Sayfa',
'postcomment' => 'Yorum ekle',
'articlepage' => 'Makaleye git',
'talk' => 'Tartışma',
'toolbox' => 'Araçlar',
'userpage' => 'Kullanıcı sayfasını görüntüle',
'viewtalkpage' => 'Tartışma sayfasına git',
'otherlanguages' => 'Diğer diller',
'redirectedfrom' => '($1 sayfasından yönlendirildi)',
'lastmodified' => 'Bu sayfa son olarak $1 tarihinde güncellenmiştir.',
'copyright' => 'İçerik $1 altındadır.',
'protectedpage' => 'Korumalı sayfa',
'administrators' => '{{ns:4}}:Yöneticiler',

'nbytes' => '$1 bayt',
'ok' => 'TAMAM',
'sitetitle'		=> '{{SITENAME}}',
'pagetitle'		=> '$1 - {{SITENAME}}',
'sitesubtitle' => 'Sitemiz',
'newmessages' => '$1.',
'newmessageslink' => 'Yeni mesajınız var!',
'editingsection' => '"$1" sayfasında bölüm değiştirmekteseniz',
'toc' => 'Konu başlıkları',
'showtoc' => 'göster',
'hidetoc' => 'gizle',

# Short words for each namespace, by default used in the 'article' tab in monobook
'nstab-category' => 'Kategori',
'nstab-help' => 'yardım',
'nstab-main' => 'Makale',
'nstab-mediawiki' => 'Mesaj',
'nstab-special' => 'Özel',
'nstab-template' => 'şablon',
'nstab-user' => 'kullanıcı sayfası',
'nstab-wp' => 'hakkında',

# Main script and global functions
#

# General errors
#
'error' => 'Hata',
'databaseerror' => 'Veritabanı hatası',
'cachederror' => 'Aşağıdaki, istediğiniz sayfanın önbellekteki kopyasıdır ve güncel olmayabilir.',
'badarticleerror' => 'Yapmak istediğiniz işlem geçersizdir.',
'cannotdelete' => 'Belirtilen sayfa ya da görüntü silinemedi. (başka bir kullanıcı tarafından silinmiş olabilir).',
'badtitle' => 'Geçersiz başlık',
'perfcached' => 'Veriler daha önceden hazırlanmış olabilir. Bu sebeple güncel olmayabilir!',
'perfdisabled' => 'Özür dileriz! Bu özellik, veritabanını kullanılamayacak derecede yavaşlattığı için, geçici olarak kullanımdan çıkarıldı.',
'viewsource' => 'Kaynağı gör',
'protectedtext' => 'Bu sayfa değiştirilmemesi için \'\'\'koruma altına alınmıştır\'\'\'. Bunun bir çok değişik sebebi olabilir. [[{{ns:4}}:Koruma altına alınmış sayfa|Koruma altına alınma sebepleri]] ile ilgili sayfaya gözatınız. 

Bu sayfanın kaynak koduna bakıp kopyalayabilirsiniz:',

# Login and logout pages
#
'logouttext' => 'Oturumu kapattınız.
Şimdi kimliğinizi belirtmeksizin {{SITENAME}} sitesini kullanmaya devam edebilirsiniz, ya da yeniden oturum açabilirsiniz (ister aynı kullanıcı adıyla, ister başka bir kullanıcı adıyla). Web tarayıcınızın önbelleğini temizleyene kadar bazı sayfalar sanki hala oturumunuz açıkmış gibi görünebilir.',
'logouttitle' => 'Oturumu kapat',
'welcomecreation' => '== Hoşgeldin, $1! ==

Artık \'\'\'kayıtlı bir kullanıcısınız\'\'\'. 

Hemen \'\'\'makale yazmaya/değiştirmeye\'\'\' başlayabilirsiniz. 

Soldaki [[Yardım:İçindekiler|yardıma]] tıklayarak işe başlayın. Kolay gelsin. [[resim:Teeth.png]]',
'loginpagetitle' => 'Oturum aç',
'yourname' => 'Kullanıcı adınız',
'yourpassword' => 'Parolanız',
'yourpasswordagain' => 'Parolayı yeniden yaz',
'newusersonly' => ' (sadece yeni kullanıcılar)',
'remembermypassword' => 'Parolayı hatırla.',
'alreadyloggedin' => '<strong>$1 rumuzlu kullanıcı, halen açık bir oturum var!</strong><br />',

'login' => 'Oturum aç',
'loginprompt' => 'Dikkat: {{SITENAME}} sitesinde oturum açabilmek için tarayıcınızda çerezlerin (cookies) aktifleştirilmiş olması gerekmektedir.',
'logout' => 'Oturumu kapat',
'userlogin' => 'Oturum aç ya da yeni hesap edin',
'userlogout' => 'Oturumu kapat',
'notloggedin' => 'Oturum açık değil',
'createaccount' => 'Yeni hesap aç',
'createaccountmail' => 'e-posta ile',
'badretype' => 'Girdiğiniz parolalar birbirini tutmuyor.',
'userexists' => 'Girdiğiniz kullanıcı adı kullanımda. Lütfen farklı bir kullanıcı adı seçin.',
'username' => 'Kullanıcı adı:',
'uid' => 'Kayıt numarası:',
'youremail' => 'E-posta adresiniz*',
'yourlanguage' => 'Arayüz dili',
'yournick' => 'İmzalarda gözükmesini istediğiniz isim',
'yourrealname' => 'Gerçek isminiz*',
'email' => 'Eposta',
'emailforlost' => 'Yıldız (*) ile belirtilmiş alanlar zorunlu değildir. E-posta adresinizi vermeniz, insanların sizinle Web sitesi aracılığı ile \'\'\'adresinizi görmeden\'\'\' haberleşmelerini sağlar, ve parolanızı unuttuğunuzda size yeni bir parola gönderilmesini de mümkün kılar.',
'prefs-help-email' => '* E-posta (isteğe bağlı): Diğer kullanıcıların kullanıcı sayfanız aracılığıyla <strong>adresinizi bilmeksizin</strong> sizle iletişim kurmasını sağlar.',
'loginsuccess' => '{{SITENAME}} sitesinde "$1" kullanıcı adıyla oturum açmış bulunmaktasınız.',
'loginsuccesstitle' => 'Oturum açıldı',
'wrongpassword' => 'Parolayı yanlış girdiniz. Lütfen tekrar deneyiniz.',
'mailmypassword' => 'Bana e-posta ile yeni bir parola gönder',
'noemail' => '"$1" adlı kullanıcıya kayıtlı bir e-posta adresi yok.',

# Edit page toolbar

'bold_sample' => 'Kalın yazı',
'bold_tip' => 'Kalın yazı',
'italic_sample' => 'İtalik yazı',
'italic_tip' => 'İtalik yazı',
'link_sample' => 'Sayfanın başlığı',
'link_tip' => 'İç bağlantı',
'extlink_sample' => 'http://tr.wikipedia.org adres açıklaması',
'extlink_tip' => 'Dış bağlantı (Adresin önüne http:// koymayı unutmayın)',
'headline_sample' => 'Başlık yazısı',
'headline_tip' => '2. seviye başlık',
'math_sample' => 'Matematiksel-ifadeyi-girin',
'math_tip' => 'Matematik formülü (LaTeX formatında)',
'nowiki_sample' => 'Serbest format yazınızı buraya yazınız',
'nowiki_tip' => 'wiki formatlamasını devre dışı bırak',
'image_sample' => 'Örnek.jpg',
'image_tip' => 'Resim ekleme',
'media_sample' => 'Örnek.ogg',
'media_tip' => 'Medya dosyasına bağlantı',
'sig_tip' => 'İmzanız ve tarih',
'hr_tip' => 'Yatay çizgi (çok sık kullanmayın)',


# Edit pages
#

######rest should be sorted - Dbl2010

'1movedto2' => '$1 sayfasının yeni adı: $2',
'1movedto2_redir' => '$1 $2\'ye yönlendirildi',
'Monobook.css' => 'monobook temasının ayarlarını değiştirmek için burayı değiştirin. Tüm sitede etkili olur.',
'Monobook.js' => '/*
<pre>
*/

/* Kısa yol tuşları ve yardım balonları */ 
ta = new Object(); 
ta[\'pt-userpage\'] = new Array(\'.\',\'Kişisel sayfam\'); 
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'The user page for the ip you\'re editing as\'); 
ta[\'pt-mytalk\'] = new Array(\'n\',\'Mesaj sayfam\'); 
ta[\'pt-anontalk\'] = new Array(\'n\',\'Bu IP adresinden yapılmış değişiklikleri tartış\'); 
ta[\'pt-preferences\'] = new Array(\'\',\'Ayarlarım\'); 
ta[\'pt-watchlist\'] = new Array(\'l\',\'İzlemeye aldığım makaleler\'); 
ta[\'pt-mycontris\'] = new Array(\'y\',\'Yaptığım katkıların listesi\'); 
ta[\'pt-login\'] = new Array(\'o\',\'Oturum açmanız tavsiye olunur ama mecbur değilsiniz.\'); 
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Oturum açmanız tavsiye olunur ama mecbur değilsiniz.\'); 
ta[\'pt-logout\'] = new Array(\'o\',\'Sistemden çık\'); 
ta[\'ca-talk\'] = new Array(\'t\',\'İçerik ile ilgili görüş belirt\'); 
ta[\'ca-edit\'] = new Array(\'e\',\'Bu sayfayı değiştirebilirsiniz. Kaydetmeden önce önizleme yapmayı unutmayın.\'); 
ta[\'ca-addsection\'] = new Array(\'+\',\'Bu tartışmaya yorum ekleyin.\'); 
ta[\'ca-viewsource\'] = new Array(\'e\',\'Bu sayfa kormu altında. Kaynak kodunu sadece görebilirsiniz. Değiştiremezsiniz.\'); 
ta[\'ca-history\'] = new Array(\'h\',\'Bu sayfanın geçmiş versiyonları.\'); 
ta[\'ca-protect\'] = new Array(\'=\',\'Bu sayfayı koru\'); 
ta[\'ca-delete\'] = new Array(\'d\',\'Sayfayı sil\'); 
ta[\'ca-undelete\'] = new Array(\'d\',\'Sayfayı silinmeden önceki haline geri getirin\'); 
ta[\'ca-move\'] = new Array(\'m\',\'Makalenin adını değiştir\'); 
ta[\'ca-nomove\'] = new Array(\'\',\'Bu sayfanın adını değiştirmeye yetkiniz yok\'); 
ta[\'ca-watch\'] = new Array(\'w\',\'Bu sayfayı izlemeye al\'); 
ta[\'ca-unwatch\'] = new Array(\'w\',\'Bu sayfayı izlemeyi bırakın\'); 
ta[\'search\'] = new Array(\'f\',\'Bu vikide arama yap\'); 
ta[\'p-logo\'] = new Array(\'\',\'Ana sayfa\'); 
ta[\'n-mainpage\'] = new Array(\'z\',\'Başlangıç sayfasına dönün\'); 
ta[\'n-portal\'] = new Array(\'\',\'Proje üzerine, ne nerdedir, neler yapılabilir\'); 
ta[\'n-currentevents\'] = new Array(\'\',\'Güncel olaylarla ilgili son bilgiler\'); 
ta[\'n-recentchanges\'] = new Array(\'r\',\'Vikide yapılmış son değişikliklerin listesi.\'); 
ta[\'n-randompage\'] = new Array(\'x\',\'Rastgele bir makaleye gidin\'); 
ta[\'n-help\'] = new Array(\'\',\'Yardım almak için.\'); 
ta[\'n-sitesupport\'] = new Array(\'\',\'Maddi destek\'); 
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Bu sayfaya bağlantı vermiş diğer viki sayfalarının listesi\'); 
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Bu sayfaya bağlantı veren sayfalardaki son değişiklikler\'); 
ta[\'feed-rss\'] = new Array(\'\',\'Bu sayfa için RSS beslemesi\'); 
ta[\'feed-atom\'] = new Array(\'\',\'Bu sayfa için atom beslemesi\'); 
ta[\'t-contributions\'] = new Array(\'\',\'Kullanıcının katkı listesini gör\'); 
ta[\'t-emailuser\'] = new Array(\'\',\'Kullanıcıya e-posta gönder\'); 
ta[\'t-upload\'] = new Array(\'u\',\'Sisteme resim ya da medya dosyaları yükleyin\'); 
ta[\'t-specialpages\'] = new Array(\'q\',\'Tüm özel sayfaların listesini göster\'); 
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Makaleyi göster\'); 
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Kullanıcı sayfasını göster\'); 
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Medya sayfasını göster\'); 
ta[\'ca-nstab-special\'] = new Array(\'\',\'Bu özel sayfa olduğu için değişiklik yapamazsınız.\'); 
ta[\'ca-nstab-wp\'] = new Array(\'a\',\'Proje sayfasını göster\'); 
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Resim sayfasını göster\'); 
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Sistem mesajını göster\'); 
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Şablonu göster\'); 
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Yardım sayfasını görmek için tıklayın\'); 
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Kategori sayfasını göster\');

/*
</pre>
*/',

'accmailtext' => '\'$1\' kullanıcısına ait parola $2 adresine gönderildi.',
'accmailtitle' => 'Parola gönderildi.',
'acct_creation_throttle_hit' => '$1 tane kullanıcı hesabı açtırmış durumdasınız. Daha fazla açtıramazsınız.',
'actioncomplete' => 'İşlem tamamlandı.',
'addedwatch' => 'İzleme listesine kaydedildi.',
'addedwatchtext' => '"$1" adlı sayfa [[Special:Watchlist|izleme listenize]] kaydedildi.

Gelecekte, bu sayfaya ve ilgili tartışma sayfasına yapılacak değişiklikler burada listelenecektir.

Kolayca seçilebilmeleri için de [[Special:Recentchanges|son değişiklikler listesi]] başlığı altında koyu harflerle listeleneceklerdir.

Sayfayı izleme listenizden çıkarmak istediğinizde "sayfayı izlemeyi durdur" bağlantısına tıklayabilirsiniz.',
'addgroup' => 'Grup Ekle',
'allarticles' => 'Tüm makaleler',
'allinnamespace' => 'Tüm sayfalar ($1 sayfaları)',
'alllogstext' => 'Yükleme, silme, koruma altına alma, erişim engelleme ve yönetici hareketlerinin tümünün kayıtları. 
Kayıt tipini, kullanıcı ismini, sayfa ismini girerek listeyi daraltabilirsiniz.',
'allmessages' => 'Tüm sistem mesajları',
'allmessagescurrent' => 'Kullanımdaki metin',
'allmessagesdefault' => 'Orjinal metin',
'allmessagesname' => 'İsim',
'allmessagestext' => 'Bu liste  MediaWiki\'de mevcut olan tüm terimlerin listesidir',
'allowemail' => 'Diğer kullanıcılar size eposta atabilsin',
'allpages' => 'Tüm sayfalar',
'allpagesfrom' => 'Listelemeye başlanılacak harfler:',
'allpagesnext' => 'Sonraki sayfa',
'allpagessubmit' => 'Getir',
'ancientpages' => 'En eski makaleler',
'and' => 've',
'articleexists' => 'Bu isimde bir sayfa bulunmakta veya seçmiş olduğunuz isim geçersizdir.
Lütfen başka bir isim deneyiniz.',
'badfilename' => 'Görüntü dosyasının ismi "$1" olarak değiştirildi.',
'badfiletype' => '".$1" önerilen bir görüntü formatı değildir.',
'badipaddress' => 'Geçersiz IP adresi',
'blanknamespace' => '(Ana)',
'blockedtext' => 'Erişiminiz $1 tarafından durdurulmuştur.
Sebep:<br />\'\'$2\'\'<br />$1 ya da başka bir [[{{ns:4}}:Yöneticiler|yönetici]] ile bu durumu görüşebilirsiniz.

Eğer [[Özel:Preferences|tercihler]] kısmında geçerli bir e-posta adresi girmediyseniz "Kullanıcıya e-posta gönder" özelliğini kullanamazsınız.


IP addresiniz $3. Konuyla ilgili yapacağınız başvuruda lütfen bu adresi de yazın.',
'blockedtitle' => 'Kullanıcı erişimi engellendi.',
'blockip' => 'Bu IP\'den erişimi engelle',
'blockipsuccesssub' => 'IP adresi engelleme işlemi başarılı oldu',
'blockipsuccesstext' => '"$1" engellendi.
<br /> [[Special:Ipblocklist|IP adresi engellenenler]] listesine bakınız .',
'blocklink' => 'kullanıcının erişimini engelle',
'blocklogentry' => '"$1" erişimi $2 durduruldu. Sebep',
'blocklogpage' => 'Erişim engelleme kayıtları',
'blocklogtext' => 'Burada kullanıcı erişimine yönelik engelleme ya da engelleme kaldırma kayıtları listelenmektedir. Otomatik  IP adresi engellemeleri listeye dahil değildir. Şu anda erişimi durdurulmuş kullanıcıları [[Special:Ipblocklist|IP engelleme listesi]] sayfasından görebilirsiniz.',
'booksources' => 'Kaynak kitaplar',
'brokenredirects' => 'Boş makaleye yönlendirmeler',
'brokenredirectstext' => 'Aşağıdaki yönlendirme, mevcut olmayan bir sayfaya işaret ediyor.',
'bugreports' => 'Hata Raporları',
'bugreportspage' => '{{ns:4}}:Hata raporları',
'bureaucratlog' => 'Bürokrat kayıtları',
'bydate' => 'kronolojik sırayla',
'byname' => 'alfabetik sırayla',
'bysize' => 'boyut sırasıyla',
'categoriespagetext' => 'Vikide aşağıdaki kategoriler mevcuttur.',
'categoryarticlecount' => 'Bu kategoride $1 makale var.',
'categoryarticlecount1' => 'Bu kategoride $1 makale bulunmaktadır.',
'changepassword' => 'Parola değiştir',
'changes' => 'değişiklik',
'clearyourcache' => '\'\'\'Not:\'\'\' Ayarlarınızı kaydettikten sonra, tarayıcınızın belleğini de temizlemeniz gerekmektedir: \'\'\'Mozilla / Firefox / Safari:\'\'\' \'\'Shift\'\' e basılıyken safyayı yeniden yükleyerek veya \'\'Ctrl-Shift-R\'\' yaparak (Apple Mac için \'\'Cmd-Shift-R\'\');, \'\'\'IE:\'\'\' \'\'Ctrl-F5\'\', \'\'\'Konqueror:\'\'\' Sadece sayfayı yeniden yükle tuşuna basarak.',
'columns' => 'Sütun',
'compareselectedversions' => 'Seçilen sürümleri karşılaştır',
'confirm' => 'Onayla',
'confirmdelete' => 'Silme işlemini onayla',
'confirmemail_body' => 'Someone, probably you from IP address $1, has registered an
account "$2" with this e-mail address on {{SITENAME}}.

To confirm that this account really does belong to you and activate
e-mail features on {{SITENAME}}, open this link in your browser:

$3

If this is *not* you, don\'t follow the link. This confirmation code
will expire at $4.',
'contextchars' => 'Satırdaki karakter sayısı',
'contextlines' => 'Bulunan madde için ayrılan satır sayısı',
'contribslink' => 'Katkılar',
'contribsub' => '$1',
'contributions' => 'Kullanıcının katkıları',
'copyrightpage' => '{{ns:4}}:Telif hakları',
'copyrightpagename' => '{{SITENAME}} ({{ns:4}}) telif hakları',
'copyrightwarning' => '
<strong>Lütfen dikkat:</strong> {{ns:4}}\'ye yapılan bütün katkılar <i>$2</i>
sözleşmesi kapsamındadır (ayrıntılar için $1\'a bakınız).
Yaptığınız katkının başka katılımcılarca acımasızca değiştirilmesini ya da özgürce ve sınırsızca başka yerlere dağıtılmasını istemiyorsanız, katkıda bulunmayınız.<br />
Ayrıca, buraya katkıda bulunarak, bu katkının kendiniz tarafından yazıldığına, ya da kamuya açık bir kaynaktan ya da başka bir özgür kaynaktan kopyalandığına güvence vermiş oluyorsunuz.<br />
<strong><center>TELİF HAKKI İLE KORUNAN HİÇBİR ÇALIŞMAYI BURAYA EKLEMEYİNİZ!</center></strong>',
'createarticle' => 'Makaleyi oluştur',
'cur' => 'fark',
'currentrev' => 'Güncel sürüm',
'currentrevisionlink' => 'en güncel halini göster',
'dateformat' => 'Tarih gösterimi',
'datetime' => 'Tarih ve saat',
'deadendpages' => 'Başka sayfalara bağlantısı olmayan sayfalar',
'default' => 'orjinal',
'defaultns' => 'Aramayı aşağıdaki seçili alanlarda yap.',
'delete_and_move_reason' => 'İsim değişikliğinin gerçekleşmesi için silindi.',
'deletecomment' => 'Silme nedeni',
'deletedarticle' => '"$1" silindi',
'deletedtext' => '"$1" silindi.
yakın zamanda silinenleri görmek için: $2.',
'deleteimg' => 'sil',
'deleteimgcompletely' => 'Dosyayı tamamen silin',
'deletepage' => 'Sayfayı sil',
'deletionlog' => 'silme kayıtları',
'dellogpage' => 'Silme kayıtları',
'destfilename' => '{{ns:4}}\'deki dosya adı',
'diff' => 'fark',
'difference' => '(Sürümler arası farklar)',
'disambiguations' => 'Anlam ayrım sayfaları',
'disambiguationspage' => 'Şablon:Anlam ayrım',
'disambiguationstext' => 'Aşağıdaki makaleler <i>anlam ayrım sayfaları</i>na bağlıdırlar. Onun yerine uygun başlığa yönlendirilmeliler.<br />Sayfalar, $1\'den bağlanılması halinde "anlam ayrım" sayfası olarak sınıflandırılıyor.<br />Diğer alan adlarına ait bağlantılar listelen<b>me</b>miştir:',
'doubleredirects' => 'Yönlendirmeye olan yönlendirmeler',
'edit-externally' => 'Dosya üzerinde bilgisayarınızda bulunan uygulamalar ile değişiklikler yapın',
'edit-externally-help' => 'Daha fazla bilgi için metadaki [http://meta.wikimedia.org/wiki/Help:External_editors dış uygulama ayarları] (İngilizce) sayfasına bakabilirsiniz.',
'editcomment' => 'Değiştirme notu: "<i>$1</i>" idi.',
'editconflict' => 'Değişiklik çakışması: $1',
'editing' => '"$1" makalesini değiştirmektesiniz',
'editingcomment' => '$1 sayfasına mesaj eklemektesiniz.',
'editingold' => '\'\'\'Dikkat: Makalenin eski bir sürümünde değişiklik yapmaktasınız. Kaydettiğinizde bu tarihli sürümden günümüze kadar olan değişiklikler yok olacaktır.\'\'\'',
'editsection' => 'değiştir',
'emailfrom' => 'Kimden',
'emailmessage' => 'E-posta',
'emailpage' => 'Kullanıcıya e-posta gönder',
'emailsend' => 'Gönder',
'emailsent' => 'E-posta gönderildi',
'emailsenttext' => 'e-postanız gönderildi.',
'emailsubject' => 'Konu',
'emailto' => 'Kime',
'emailuser' => 'Kullanıcıya e-posta gönder',
'exbeforeblank' => 'Silinmeden önceki içerik:',
'exblank' => 'sayfa içeriği boş',
'excontent' => 'eski içerik:',
'excontentauthor' => 'eski içerik: \'$1\' (\'$2\' katkıda bulunmuş olan tek kullanıcı)',
'exif-lightsource-10' => 'Clody weather',
'filedesc' => 'Dosya ile ilgili açıklama',
'fileexists' => 'Bu isimde bir dosya mevcut. Eğer değiştirmekten emin değilseniz ilk önce $1 dosyasına bir gözatın.',
'filename' => 'Dosya',
'files' => 'Dosyalar',
'fileuploaded' => '$1 dosyası başarı ile yüklendi.

Lütfen $2 bağlantısını takip ederek dosya ile ilgili açıklama yazısı yazınız. Dosya nerden geldi, kim tarafından ne zaman oluşturuldu ya da hakında bildiğiniz diğer bilgiler gibi.  

Eğer bu bir resim ise <tt><nowiki>[[Resim:$1|thumb|açıklama]]</nowiki></tt> şeklinde makaleye yerleştirebilirsiniz. (açıklama yerine resim ile ilgili yazı yazınız)',
'fileuploadsummary' => 'Açıklama:',
'guesstimezone' => 'Tarayıcınız sizin yerinize doldursun',
'hide' => 'gizle',
'hist' => 'geçmiş',
'histfirst' => 'En eski',
'histlast' => 'En yeni',
'histlegend' => '(fark)  = güncel sürümle aradaki fark,
(son)  = önceki sürümle aradaki fark, K= küçük değişiklik',
'ignorewarning' => 'Uyarıyı önemsemeyip dosyayı yükle',
'imagelinks' => 'Kullanıldığı sayfalar',
'imagelist' => 'Dosya listesi',
'imagelistall' => 'Tümü',
'imagemaxsize' => 'Resim açıklamalar sayfalarındaki resmin en büyük boyutu:',
'imgdesc' => 'tanım',
'imghistory' => 'Dosya geçmişi',
'imglegend' => 'Gösterim: (tanım) = Dosyanın açıklamasını göster ya da değiştir.',
'invert' => 'Seçili haricindekileri göster',
'ip_range_invalid' => 'Invalid IP range.',
'ipblocklist' => 'Erişimi durdurulmuş kullanıcılar ve IP adresleri listesi',
'ipblocklistempty' => 'Erişimi engellenmiş kimse yok.',
'last' => 'son',
'lineno' => '$1. satır:',
'linkshere' => 'Buraya bağlantısı olan sayfalar:',
'linkstoimage' => 'Bu görüntü dosyasına bağlantısı olan sayfalar:',
'listform' => 'liste',
'listingcontinuesabbrev' => ' (devam)',
'listusers' => 'Kullanıcı listesi',
'localtime' => 'Şu an sizin saatiniz',
'log' => 'Kayıtlar',
'lonelypages' => 'Kendisine hiç bağlantı olmayan sayfalar',
'longpages' => 'Uzun sayfalar',
'longpagewarning' => '<strong>UYARI: Bu sayfa $1 kilobytes büyüklüğündedir; bazı tarayıcılar değişiklik yaparken 32kb ve üstü büyüklüklerde sorunlar yaşayabilir. Sayfayı bölümlere ayırmaya çalışın.</strong>',
'lucenepowersearchtext' => '<b>Aramaya dahil edilecek alanlar:</b> $1 <b><br />Aranan metin:</b> $3 $9',
'maintenance' => 'Bakım sayfası',
'math' => 'Matematiksel semboller',
'minoredit' => 'Küçük değişiklik',
'minoreditletter' => 'K',
'moredotdotdot' => 'Daha...',
'mostlinked' => 'Kendisine en fazla bağlantı verilmiş sayfalar',
'mostrevisions' => 'En çok değişikliğe uğramış sayfalar',
'move' => 'Adını değiştir',
'movearticle' => 'Eski isim',
'movelogpage' => 'İsim değişikliği kayıtları',
'movenologin' => 'Sistemde değilsiniz.',
'movenologintext' => 'Sayfanın adını değiştirebilmek için kayıtlı ve [[Special:Userlogin|sisteme]] giriş yapmış olmanız gerekmektedir.',
'movepage' => 'İsim değişikliği',
'movepagebtn' => 'İsmi değiştir',
'movepagetext' => 'Aşağıdaki form kullanılarak makalenin adı değiştirilir. Beraberinde tüm geçmiş kayıtları da yeni isme aktarılır. Eski isim yeni isme yönlendirme haline dönüşür. Eski başlığa dogru olan bağlantılar olduğu gibi kalır; çift veya geçersiz yönlendirmeleri [[Special:Maintenance|kontol ediniz.]] Yapacağınız bu değişikllike tüm bağlantıların olması gerektiği gibi çalıştığından sizin sorumlu olduğunuzu unutmayınız.

Eğer yeni isimde bir isim zaten mevcutsa, isim değişikliği \'\'\'yapılmayacaktır\'\'\', ancak varolan makale içerik olarak boş ise veya sadece yönlendirme ise ve hiç geçmiş hali yoksa isim değişikliği mümkün olacaktır. Bu yanı zamanda demektir ki, yaptığınız isim değişikliğini ilk ismine değiştirerek geri alabilirsiniz ve hiç bir başka sayfaya da dokunmamış olursunuz.

<b>UYARI!</b>
Bu değişim popüler bir sayfa için beklenmeyen sonuçlar doğurabilir; lütfen değişikliği yapmadan önce olabilecekleri göz önüne alın.',
'movereason' => 'Sebep',
'movetalk' => 'Varsa "tartışma" sayfasını da aktar.',
'movethispage' => 'Sayfayı taşı',
'mw_math_html' => 'Mümkünse HTML, değilse PNG',
'mw_math_mathml' => 'Mümkünse MathML (daha deneme aşamasında)',
'mw_math_modern' => 'Modern tarayıcılar için tavsiye edilen',
'mw_math_png' => 'Daima PNG resim formatına çevir',
'mw_math_simple' => 'Çok basitse HTML, değilse PNG',
'mw_math_source' => 'Değiştirmeden TeX olarak bırak  (metin tabanlı tarayıcılar için)',
'mycontris' => 'Katkılarım',
'namespace' => 'Alan adı:',
'namespacesall' => 'Hepsi',
'nchanges' => '$1 değişiklik',
'newarticle' => '(Yeni)',
'newarticletext' => 'Henüz varolmayan bir sayfaya konulmuş bir bağlantıya tıkladınız. Bu sayfayı yaratmak için aşağıdaki metin kutusunu kullanınız. Bilgi için [[Yardım:İçindekiler|yardım sayfasına]] bakınız. Buraya yanlışlıkla geldiyseniz, programınızın \'\'\'Geri\'\'\' tuşuna tıklayınız.',
'newbies' => 'yeni başlayanlar',
'newimages' => 'Yeni resimler',
'newpageletter' => 'Y',
'newpages' => 'Yeni sayfalar',
'newpassword' => 'Yeni parola',
'newtitle' => 'Yeni isim',
'newuserloglog' => '[[User:$1|$1]] kaydoldu. ([[User talk:$1|$2]] | [[Special:Contributions/$1|$3]])',
'newuserlogpage' => 'Yeni kullanıcı kayıtları',
'newuserlogpagetext' => 'En son kaydolan kullanıcı kayıtları',
'next' => 'sonraki',
'nextdiff' => 'Sonraki sürümle aradaki fark →',
'nextn' => 'sonraki $1',
'nextpage' => 'Sonraki sayfa ($1)',
'nextrevision' => 'Sonraki hali →',
'noarticletext' => 'Bu sayfa boştur. Bu başlığı diğer sayfalarda [[{{ns:special}}:Search/{{PAGENAME}}|arayabilir]] veya bu sayfayı siz  [{{fullurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} yazabilirsiniz].',
'noemailtext' => 'Kullanıcı e-posta adresi belirtmemiş ya da diğer kullanıcılardan posta almak istemiyor.',
'noemailtitle' => 'e-posta adresi yok',
'nogomatch' => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Başlığı bu olan bir makale bulunamadı.</span> <span style="display: block; margin: 1.5em 2em"> Bu makalenin yazılmasını [[$1|\'\'\'siz başlatabilirsiniz\'\'\']], ya da bu makalenin yazılması isteğini [[Project:Makale istekleri|istenen makaleler listesine]] ekleyebilirsiniz.<span style="display:block; font-size: 89%; margin-left:.2em">Yeni bir makale yaratmadan önce lütfen site içinde arama yapınız. İstediğiniz makale başka bir adla zaten var olabilir.</span> </span>',
'noimage' => 'Bu isimde dosya yok. Siz $1.',
'noimage-linktext' => 'yükleyebilirsiniz',
'nolinkshere' => 'Buraya bağlanan sayfa yok.',
'nolinkstoimage' => 'Bu görüntü dosyasına bağlanan sayfa yok.',
'note' => '<strong>Not: </strong>',
'oldpassword' => 'Eski parola',
'orig' => 'asıl',
'pagemovedsub' => 'İsim değişikliği tamamlandı.',
'pagemovedtext' => '"[[$1]]" sayfası "[[$2]]" sayfasına aktarıldı.',

'powersearch' => 'Ara',
'powersearchtext' => 'Arama yapılacak alanları seçin :<br />
$1<br />
$2 yönlendirmeleri listele &nbsp; Aranacak: $3 $9',
'preferences' => 'Tercihler',
'prefs-misc' => 'Diğer ayarlar',
'prefs-personal' => 'Kullanıcı bilgileri',
'prefs-rc' => 'Son değişiklikler',
'prefsnologin' => 'Oturum açık değil',
'preview' => 'Önizleme',
'previewnote' => 'Bu yalnızca bir önizlemedir, ve değişiklikleriniz henüz kaydedilmemiştir!',
'previousdiff' => '← Önceki sürümle aradaki fark',
'previousrevision' => '← Önceki hali',
'prevn' => 'önceki $1',
'protectedpagewarning' => 'UYARI: Bu sayfa koruma altına alınmıştır ve yalnızca yönetici olanlar tarafından değiştirilebilir. Bu sayfayı değiştirirken lütfen [[Project:Protected_page_guidelines|korumalı sayfa kurallarını]] uygulayınız.',
'protectlogpage' => 'Koruma kayıtları',
'protectlogtext' => 'Korumaya alma/kaldırma ile ilgili değişiklikleri görmektesiniz.
Daha fazla bilgi için [[{{ns:4}}:Koruma altına alınmış sayfa]] sayfasına bakabilirsiniz.',
'qbsettings' => 'Hızlı erişim sütun ayarları',
'randompage' => 'Rastgele sayfa',
'randompage-url' => 'Özel:Random',
'rclinks' => 'Son $2 günde yapılan son $1 değişikliği göster;<br /> $3',
'rclistfrom' => '$1 tarihinden beri yapılan değişiklikleri göster',
'rcloaderr' => 'Son değişiklikler yükleniyor',
'rclsub' => '("$1" sayfasına bağlanan sayfalarda)',
'rcnote' => 'Son <strong>$2</strong> günde yapılan <strong>$1</strong> değişiklik:',
'rcnotefrom' => '<b>$2</b> tarihinden itibaren yapılan değişiklikler aşağıdadır (en fazla <b>$1</b> adet madde gösterilmektedir).',
'recentchanges' => 'Son değişiklikler',
'recentchangescount' => 'Son değişiklikler sayfasındaki madde sayısı',
'recentchangeslinked' => 'İlgili değişiklikler',
'recentchangestext' => 'Yapılan en son değişiklikleri bu sayfadan izleyin.',
'removechecked' => 'İşaretli sayfaları izleme listesinden sil',
'removedwatch' => 'İzleme listenizden silindi',
'removedwatchtext' => '"$1" sayfası izleme listenizden silinmiştir.',
'removingchecked' => 'İşaretlenen sayfalar izleme listesinden siliniyor...',
'resetprefs' => 'Ayarları ilk durumuna getir',
'restrictedpheading' => 'Yöneticilerin yetkileri ile ilgili özel sayfalar',
'resultsperpage' => 'Sayfada gösterilecek bulunan madde sayısı',
'retypenew' => 'Yeni parolayı tekrar girin',
'reupload' => 'Yeniden yükle',
'reuploaddesc' => 'Yükleme formuna geri dön.',
'revertmove' => 'geriye al',
'revertpage' => '$2 tarafından yapılan değişiklikler geri alınarak, $1 tarafından değiştirilmiş önceki sürüm geri getirildi.',
'revhistory' => 'Sürüm geçmişi',
'revisionasof' => 'Makalenin $1 tarihindeki hali',
'revisionasofwithlink' => '$1 tarihindeki hali; $2<br />$3 | $4',
'rollback' => 'değişiklikleri geri al',
'rollbacklink' => 'eski haline getir',
'rows' => 'Satır',
'savearticle' => 'Sayfayı kaydet',
'savedprefs' => 'Ayarlar kaydedildi.',
'savefile' => 'Dosyayı kaydet',
'saveprefs' => 'Değişiklikleri kaydet',
'searchdisabled' => 'Arama yapma geçici olarak durdurulmuştur. Bu arada Google veya Yahoo! kullanarak arama yapabilirsiniz. İndekslemelerinin biraz eski kalmış olabileceğini göz önünde bulundurunuz.',
'searchnext' => '<span style=\'font-size: small\'>Sonraki sayfa</span> &#x00BB;',
'searchnumber' => '<strong>Arama sonuçları $1-$2 </strong>(Toplam bulunan sayfa $3)',
'searchprev' => '&#x00AB; <span style=\'font-size: small\'>Önceki sayfa </span>',
'searchquery' => 'Aranan: "$1"',
'searchresultshead' => 'Arama',
'searchresulttext' => '{{SITENAME}} içinde arama yapmak ile ilgili bilgi almak için [[Project:Arama|"{{SITENAME}} içinde arama"]] sayfasına bakabilirsiniz.',
'searchscore' => 'İsabet: $1',
'selfmove' => 'Olmasını istediğiniz isim ile mevcut isim aynı. Değişiklik mümkün değil.',
'servertime' => 'Viki sunucusunda şu anki saat',
'sharedupload' => 'Bu dosya ortak alana yüklenmiştir ve diğer projelerde de kullanılıyor olabilir.',
'shortpages' => 'Kısa sayfalar',
'show' => 'göster',
'showdiff' => 'Değişiklikleri göster',
'showhideminor' => 'Küçük değişiklikleri $1 | Botları $2 | Sistemdeki kullanıcıları $3',
'showingresults' => '<b>$2</b>#dan başlayarak <b>$1</b> sonuç aşağıdadır:',
'showingresultsnum' => '<b>$2</b>#dan başlayarak <b>$3</b> sonuç aşağıdadır:',
'showlast' => 'En son $1 dosyayı $2 göster.',
'showpreview' => 'Önizlemeyi göster',
'sitematrix' => 'Tüm Wikimedia Wikilerin listesi',
'sitestats' => 'Site istatistikleri',
'sitestatstext' => '<p style="font-size:125%;margin-bottom:0">Şu anda \'\'\'$2\'\'\' makale var.</p>

<p style="margin-top:0">Bu sayıya Tartışma:, Resim: tanım, Kullanıcı:, Yardım:, Proje:, Şablon: sayfaları ile içerisinde diğer sayfalara bağlantı olmayan sayfalar ve yönlendirme sayfaları \'\'\'dahil değildir\'\'\'. Onlar da dahil edildiğinde toplam \'\'\'$1\'\'\' sayfamız mevcut.</p>

Kullanıcılar başlangıçtan de bu yana sayfalarda \'\'\'$4\'\'\' defa değişiklik yapmış. (Sayfa başına \'\'\'$5\'\'\' değişiklik)

\'\'\'$4\'\'\' defa sayfalara bakılmış.',
'skin' => 'Tema',
'sourcefilename' => 'Yüklemek istediğiniz dosya',
'speciallogtitlelabel' => 'Başlık:',
'specialloguserlabel' => 'Kullanıcı:',
'specialpages' => 'Özel sayfalar',
'spheading' => 'Tüm kullanıcıları ilgilendirebilecek özel sayfalar',
'statistics' => 'İstatistikler',
'stubthreshold' => 'Taslak olarak sınıflandırılabilmek için alt sınır',
'subcategorycount' => 'Bu kategoride $1 altkategori var.',
'subcategorycount1' => 'Bu kategoride $1 altkategori var.',
'subject' => 'Konu/başlık',
'summary' => 'Özet',
'tableform' => 'tablo',
'talkpagemoved' => 'İlgili tartışma sayfası da aktarıldı.',
'talkpagenotmoved' => 'İlgili tartışma sayfası <strong>aktarılmadı</strong>.',
'textboxsize' => 'Makale yazma alanı',
'thumbsize' => 'Küçük boyut:',
'timezonelegend' => 'Saat dilimi',
'timezoneoffset' => 'Saat farkı',
'timezonetext' => 'Viki sunucusu(UTC/GMT) ile aranızdaki saat farkı.(Türkiye için +02:00)',
'tooltip-minoredit' => 'Küçük değişiklik olarak işaretle [alt-i]',
'tooltip-preview' => 'Önizleme; kaydetmeden önce bu özelliği kullanarak değişikliklerinizi gözden geçirin! [alt-p]',
'uctop' => ' (ilk)',
'uncategorizedcategories' => 'Herhangi bir kategoride olmayan kategoriler',
'uncategorizedpages' => 'Herhangi bir kategoride olmayan sayfalar',
'undelete' => 'Silinmiş sayfaları göster',
'undeletedarticle' => '"$1" geri getirildi.',
'undeletedrevisions' => 'Toplam $1 kayıt geri getirildi.',
'unusedcategories' => 'Kullanılmayan kategoriler',
'unusedimages' => 'Kullanılmayan görüntüler',
'unwatch' => 'Sayfa izlemeyi durdur',
'unwatchthispage' => 'Sayfa izlemeyi durdur',
'upload' => 'Dosya yükle',
'uploadbtn' => 'Dosya yükle',
'uploadedimage' => 'Yüklenen: "[[$1]]"',
'uploaderror' => 'Yükleme hatası',
'uploadlink' => 'Görüntü yükle',
'uploadlog' => 'yükleme kaydı',
'uploadlogpage' => 'Dosya yükleme kayıtları',
'uploadnewversion' => '[$1 Dosyanın yenisini yükleyin]',
'uploadnologin' => 'Oturum açık değil',
'uploadnologintext' => 'Dosya yükleyebilmek için [[Special:Userlogin|oturum aç]]manız gerekiyor.',
'uploadtext' => "
Dosya yüklemek için aşağıdaki formu kullanın,
Önceden yüklenmiş resimleri görmek için  [[Special:Imagelist|resim listesine]] bakın,
yüklenenler ve silinmişler [[Special:Log/upload|yükleme kaydısayfasında da]] görülebilir.

Sayfaya resim koymak için 
'''<nowiki>[[{{ns:6}}:Örnek.jpg]]</nowiki>''',
'''<nowiki>[[{{ns:6}}:Örnek.png|açıklama]]</nowiki>'''  veya doğrudan bağlantı için
'''<nowiki>[[{{ns:-2}}:Örnek.ogg]]</nowiki>''' .
",
'uploadvirus' => 'Bu dosya virüslüdür! Detayları: $1',
'uploadwarning' => 'Yükleme uyarısı',
'userrights-user-editname' => 'Enter a username:',
'userstats' => 'Kullanıcı istatistikleri',
'userstatstext' => 'Şu anda \'\'\'$1\'\'\' kayıtlı kullanıcımız var. Bunlardan <b>$2</b> tanesi (ya da %$4) yöneticidir. (bakın $3)',
'version' => 'Sürüm',
'viewprevnext' => '($1) ($2) ($3).',
'wantedpages' => 'İstenen sayfalar',
'watch' => 'İzlemeye al',
'watchdetails' => '* Tartışma sayfaları hariç $1 sayfa izleme listenizdedir  
* [[Special:Watchlist/edit|İzleme listesinin tamamını göster ve yapılandır]]',
'watcheditlist' => 'İzlediğiniz sayfaların alfabetik listesi aşağıdadır. 
Sayfaları izleme listesinden çıkarmak için yanlarındaki
kutucukları işaretleyip sayfanın altındaki \'işaretlenenleri sil\' 
düğmesini tıklayınız.',
'watchlist' => 'İzleme listem',
'watchlistcontains' => 'İzleme listenizde $1 sayfa var.',
'watchlistsub' => '("$1")',
'watchmethod-list' => 'izleme listenizdeki sayfalar kontrol ediliyor',
'watchmethod-recent' => 'son değişiklikler arasında izledğiniz sayfalar aranıyor',
'watchnochange' => 'Gösterilen zaman aralığında izleme listenizdeki sayfaların hiçbiri güncellenmemiş.',
'watchnologin' => 'Oturum açık değil.',
'watchthis' => 'Sayfayı izle',
'watchthispage' => 'Sayfayı izle',
'wlhide' => 'gizle',
'wlhideshowown' => 'Kendi değişikliklerimi $1.',
'wlnote' => 'Son <b>$2</b> saatte yapılan $1 değişiklik aşağıdadır.',
'wlshow' => 'göster',
'wlshowlast' => 'Son $1 saati $2 günü göster $3',
);




class LanguageTr extends LanguageUtf8 {
	function getNamespaces() {
		global $wgNamespaceNamesTr;
		return $wgNamespaceNamesTr;
	}

	function formatNum( $number, $year = false ) {
		return $year ? $number : strtr($this->commafy($number), '.,', ',.' );
	}

	function ucfirst ( $string ) {
		if ( $string[0] == 'i' ) {
			return 'İ' . substr( $string, 1 );
		} else {
			return parent::ucfirst( $string );
		}
	}

	function getMessage( $key ) {
		global $wgAllMessagesTr, $wgAllMessagesEn;
		if( isset( $wgAllMessagesTr[$key] ) ) {
			return $wgAllMessagesTr[$key];
		} else {
			return parent::getMessage( $key );
		}
	}

        function getDateFormats() {
		global $wgDateFormatsTr;
		return $wgDateFormatsTr;
	}

}


?>

