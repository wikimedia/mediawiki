<?php
/** Gagauz (Gagauz)
 *
 * @addtogroup Language
 *
 * @author Cuman
 * @author Nike
 */

$fallback = 'tr';

$messages = array(
# Dates
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

# Bits of text used by many pages
'categories'            => '{{PLURAL:$1|Kategoriya|Kategoriyalar}}',
'category_header'       => '"$1" kategoriyasındaki sayfalar',
'subcategories'         => 'Alt kategoriyalar',
'category-media-header' => '"$1" kategoryasındaki media',
'category-empty'        => "''Bu kategoriyada henez bulunmêêr bir yazı yaki mediya.''",

'about'     => 'Uurunda',
'newwindow' => '(eni bir pencerädä açılêr)',
'cancel'    => 'Ret',
'qbfind'    => 'Bul',
'qbedit'    => 'Diiştir',
'mytalk'    => 'Sözleşmäk sayfam',

'errorpagetitle'   => 'Yannış',
'returnto'         => '$1 dön.',
'tagline'          => '{{SITENAME}} saydından',
'help'             => 'Yardım',
'search'           => 'Ara',
'searchbutton'     => 'Ara',
'searcharticle'    => 'Git',
'history'          => 'Sayfanın istoriyası',
'history_short'    => 'İstoriya',
'printableversion' => 'Tiparlanacêk versiya',
'permalink'        => 'Bitki haline baalantı',
'edit'             => 'Diiştir',
'editthispage'     => 'Sayfayı diiştir',
'delete'           => 'Sil',
'protect'          => 'Korunmak altına al',
'newpage'          => 'Eni sayfa',
'talkpage'         => 'Sayfayı diskussiya et',
'talkpagelinktext' => 'Konuşmaa',
'personaltools'    => 'Personal instrumentlär',
'talk'             => 'Diskussiya',
'views'            => 'Görünüşler',
'toolbox'          => 'İnstrumentlär',
'redirectedfrom'   => '($1 sayfasınnan yönnendirildi)',
'redirectpagesub'  => 'Yönnendirme sayfası',
'jumpto'           => 'Git hem:',
'jumptonavigation' => 'kullan',
'jumptosearch'     => 'ara',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => '{{SITENAME}} için',
'aboutpage'         => 'Project:Uurunda',
'bugreports'        => 'Yannışnık raportları',
'bugreportspage'    => 'Project:Yannışnık raportları',
'copyrightpage'     => '{{ns:project}}:Avtorluk hakları',
'currentevents'     => 'Hergünkü sluçaylar',
'currentevents-url' => 'Project:Hergünkü sluçaylar',
'disclaimers'       => 'Cuvapçılık reti',
'disclaimerpage'    => 'Project:Genel cuvapçılık reti',
'edithelp'          => 'Nesoy var nicä diiştirmää?',
'edithelppage'      => 'Help:Nesoy var nicä sayfa diiştirmää',
'helppage'          => 'Help:İçindekilär',
'mainpage'          => 'Baş yaprak',
'portal'            => 'Topluluk portalı',
'portal-url'        => 'Project:Topluluk portalı',
'privacy'           => 'Saklamaa politikası',
'privacypage'       => 'Project:Saklamaa politikası',
'sitesupport'       => 'Baaşişlär',
'sitesupport-url'   => 'Project:Baaşiş',

'retrievedfrom'       => 'Alındı "$1"dän',
'youhavenewmessages'  => 'Var eni <u>$1</u>. ($2)',
'newmessageslink'     => 'eni mesajlar',
'newmessagesdifflink' => 'Bitki diişmäk',
'editsection'         => 'diiştir',
'editold'             => 'diiştir',
'editsectionhint'     => 'Diiştirilen bölüm: $1',
'toc'                 => 'İçindekilär',
'showtoc'             => 'göster',
'hidetoc'             => 'sakla',
'site-rss-feed'       => '$1 RSS Feed',
'site-atom-feed'      => '$1 Atom Feed',
'page-rss-feed'       => '"$1" RSS lenta',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-user'     => 'kullanıcı sayfası',
'nstab-project'  => 'Proekt sayfası',
'nstab-image'    => 'Fayl',
'nstab-template' => 'Şablon',
'nstab-category' => 'Kategoriya',

# General errors
'badtitle'       => 'Yannış yazı adı',
'badtitletext'   => 'Girilen sayfa adı beki yannış beki de boş, yaki geçersiz neçin ki diller arası baalantı yaki vikiler arası baalantı içerer. Var nicä içindä olsun bir yaki taa çok nışan angıları yasak başlıklarda kullanılsın.',
'viewsource'     => 'Geliniri gör',
'viewsourcefor'  => '$1 için',
'viewsourcetext' => 'Var nicä görmää bu yapraın gelinirini hem kopiya etmää:',

# Login and logout pages
'yourname'              => 'Kullanıcı adınız',
'yourpassword'          => 'Parol',
'remembermypassword'    => 'Parolu an.',
'login'                 => 'Gir',
'loginprompt'           => "Bak: {{SITENAME}} saytında sessiya açmaa için tarayıcınızda läazım cookies aktivat olsun. <br />
Kullanıcı adınız '''var nicä içersin'''gagauzça nışan, boşluk . Savaşın kullanıcı adınıza e-mail adresi '''girmemää'''.",
'userlogin'             => 'Gir / esap yarat',
'logout'                => 'Sessiyanı kapat',
'userlogout'            => 'Oturmaa kapat',
'nologin'               => 'Henez aza olmadınız? $1.',
'nologinlink'           => 'Esap yarat',
'createaccount'         => 'Eni esap aç',
'gotaccount'            => 'Taa ilerdä esap açtınızmı? $1.',
'gotaccountlink'        => 'Herliim ilerdän esap açtıysanız girin bu baalantıdan.',
'yourrealname'          => 'Haliz adınız:',
'prefs-help-realname'   => '* Aslı ad (istemää baalı): herliim seçersäniz aslı adı vermää, işinize görä sizin için kullanılacêk.',
'loginsuccesstitle'     => 'Sessiya başarılan açıldı',
'loginsuccess'          => '{{SITENAME}} saytında "$1" kullanıcı adılan sessiya açtınız.',
'nosuchuser'            => 'Burada "$1" adlı kullanıcı yok. Yokla bir taa nesoy yazdın, yaki eni esap yarat.',
'nosuchusershort'       => 'Burada "$1" adlı kullanıcı yok. Yoklayın ani ad nesoy yazıldı.',
'nouserspecified'       => 'Läazım bir kullanıcı adı göstermää.',
'wrongpassword'         => 'Parolu yannış girdiniz. Yalvarerêz tekrar denämää.',
'wrongpasswordempty'    => 'Boş parol girdiniz. Yalvarerez tekrar denämää.',
'passwordtooshort'      => 'Parolunuz çok kısa. En az $1 bukva hem/yaki țifra läazım olsun.',
'mailmypassword'        => 'Gönder bana e-maillän eni bir parol',
'passwordremindertitle' => '{{SITENAME}} saytından parol hatırlatıcısı.',
'noemail'               => '"$1" adlı kullanıcı için registrat olmuş e-mail adresi yok.',
'passwordsent'          => '"$1" adına registrat olmuş e-mail adresine eni bir parol gönderildi. Lütfen, läazım açmaa oturmaa ne zaman bunu aldınız.',

# Edit page toolbar
'bold_sample'     => 'Kalın tekst',
'bold_tip'        => 'Kalın tekst',
'italic_sample'   => 'İtalik tekst',
'italic_tip'      => 'İtalik tekst',
'link_sample'     => 'Sayfanın adı',
'link_tip'        => 'İç baalantı',
'extlink_sample'  => '{{SERVER}} adres adı',
'extlink_tip'     => 'Dış baalantı (Unutmayın adresin önüne http:// koymaa)',
'headline_sample' => 'Başlık teksti',
'headline_tip'    => '2. düzän başlık',
'math_sample'     => 'Matematik-formulanı-koyun',
'math_tip'        => 'Matematik formula (LaTeX formatında)',
'nowiki_sample'   => 'Serbest format yazınızı buraya yazınız',
'nowiki_tip'      => 'Wiki formatlamasını ignor et',
'image_tip'       => 'Pätret eklemää',
'media_tip'       => 'Mediya faylına baalantı',
'sig_tip'         => 'İmzanız hem data',
'hr_tip'          => 'Gorizontal liniya (çok sık kullanmayın)',

# Edit pages
'summary'                => 'Kısaca',
'subject'                => 'Konu/başlık',
'minoredit'              => 'Küçük diişmäklär',
'watchthis'              => 'Bak bu sayfaa',
'savearticle'            => 'Sayfayı registrat et',
'preview'                => 'Ön siir',
'showpreview'            => 'Ön siiri göster',
'showdiff'               => 'Diişmekläri göster',
'anoneditwarning'        => 'Sessiya açmadınız deyni yazının diişmäk istoriyasına diil nik, IP adresiniz registrat olunacêk.',
'summary-preview'        => 'Ön siir özeti',
'newarticle'             => '(Eni)',
'previewnote'            => 'Bu saadä bir ön siir, hem diişmäkler henez korunmadı!',
'editing'                => '"$1" sayfasın diiştirersiniz',
'editingsection'         => '"$1" sayfasında bölüm diiştirersiniz',
'templatesused'          => 'Bu sayfada kullanılan şablonlar:',
'templatesusedpreview'   => 'Şablonnar ani bu ön siirdä kullanıldı:',
'template-protected'     => '(korumaa)',
'template-semiprotected' => '(yarı-korunmaa)',
'nocreatetext'           => '{{SITENAME}} eni yazılar yaratmaa yasaklandı.
Sizä yakışêr geeri dönmää hem düzmää var olan yapraa, yaki [[Special:Userlogin|sessiya açmaa yaki esap yaratmaa]].',

# History pages
'viewpagelogs'        => 'Bu yaprak için jurnalları göster',
'currentrev'          => 'Şindiki versiya',
'revisionasof'        => 'Sayfanın $1 datasındaki hali',
'revision-info'       => '$1; $2 datalı versiya',
'previousrevision'    => '← İlerki hali',
'nextrevision'        => 'Geerki hali →',
'currentrevisionlink' => 'en bitki halini göster',
'cur'                 => 'fark',
'last'                => 'bitki',
'page_first'          => 'ilk',
'page_last'           => 'bitki',
'histfirst'           => 'En eski',
'histlast'            => 'En eni',

# Revision feed
'history-feed-item-nocomment' => ' $2de $1', # user at time

# Diffs
'history-title'           => '"$1" yapraın istoriyası',
'difference'              => '(Versiyalar arası farklar)',
'lineno'                  => '$1. liniya:',
'compareselectedversions' => 'Karşılaştır versiyaları ani seçildi',
'editundo'                => 'geeri al',
'diff-multi'              => '({{PLURAL:$1|Ara versiya|$1 ara versiyalar}} gösterilmedi.)',

# Search results
'noexactmatch' => " Başlaa bu olan bir yazı bulunamadı. Bu yazını var nicä [[:$1|'''siz çeketmää''']].",
'prevn'        => 'ilerki $1',
'nextn'        => 'geeriki $1',
'viewprevnext' => '($1) ($2) ($3).',
'powersearch'  => 'Ara',

# Preferences page
'preferences'   => 'Seçimner',
'mypreferences' => 'Seçimnerim',
'retypenew'     => 'Eni parolu tekrar girin',

'grouppage-sysop' => '{{ns:project}}:Önderciler',

# User rights log
'rightslog' => 'Kullanıcı hakları jurnalı',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|diiştir|diiştir}}',
'recentchanges'                  => 'Bitki diişikmäklär',
'recentchanges-feed-description' => 'Bu lentalan en bitki diişmäkleri vikiyä yaz.',
'rcnote'                         => '$3 (UTC) datasında bitki <strong>$2</strong> gündä yapılan <strong>$1</strong> diişmäk:',
'rcnotefrom'                     => '<b>$2</b> datasınnan büüne kadar yapılan diişmäkler aşaada (en çok <b>$1</b> yazı gösteriler).',
'rclistfrom'                     => 'Göster diişmäkleri ani $1 datasından beeri yapıldı',
'rcshowhideminor'                => 'küçük diişmäkläri $1',
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

# Recent changes linked
'recentchangeslinked'          => 'İlgili diişmäklär',
'recentchangeslinked-title'    => '$1 ilgili diişmäklär',
'recentchangeslinked-noresult' => 'Baalantılı sayfalarda verilmiş devirde diişmäk olmadı.',

# Upload
'upload'        => 'Fayl ükle',
'uploadbtn'     => 'Fayl ükle',
'uploadlogpage' => 'Fayl üklemäk jurnalları',
'uploadedimage' => 'Üklenen: "[[$1]]"',

# Image list
'imagelist'                 => 'Pätret listası',
'filehist'                  => 'Fayl istoriyası',
'filehist-help'             => 'Fayl istoriyasın görmää deyni Gün/Zaman bölümündeki dataları tıklayınız.',
'filehist-current'          => 'Şindiki',
'filehist-datetime'         => 'Gün/Zaman',
'filehist-user'             => 'Kullanıcı',
'filehist-dimensions'       => 'Masştablar',
'filehist-filesize'         => 'Fayl ölçüleri',
'filehist-comment'          => 'Kommentariya',
'imagelinks'                => 'Sayfalar angıları kullanıldı',
'linkstoimage'              => 'Bu fayla baalantısı olan sayfalar:',
'nolinkstoimage'            => 'Yok sayfalar ani bu fayla baalı.',
'sharedupload'              => 'Bu fayl üklendi ortak kullanmak erinä hem var nicä kullanılsın übür proektlärdä.',
'noimage'                   => 'Bu adda fayl yok. Siz $1.',
'noimage-linktext'          => 'var nicä üklemää',
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
'statistics' => 'Statistikalar',

'disambiguations' => 'Maana aydınnatmak yaprakları',

'doubleredirects' => 'İki kerä yönnendirmeler',

'brokenredirects' => 'Var olmayan yazıya yapılmış yönnendirmeler',

'withoutinterwiki' => 'Başka dillerä baalantısı olmayan sayfalar',

'fewestrevisions' => 'En az düzennemäk  yapılmış sayfalar',

# Miscellaneous special pages
'nbytes'                  => '$1 bayt',
'nlinks'                  => '$1 baalantı',
'nmembers'                => '$1 aza',
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
'allpages'                => 'Hepsi sayfalar',
'prefixindex'             => 'Prefiks indeks',
'shortpages'              => 'Kısa sayfalar',
'longpages'               => 'Uzun sayfalar',
'deadendpages'            => 'Başka sayfalara baalantısız sayfalar',
'protectedpages'          => 'Korunma altındaki sayfalar',
'listusers'               => 'Kullanıcı listası',
'specialpages'            => 'Maasus sayfalar',
'newpages'                => 'Eni sayfalar',
'ancientpages'            => 'En bitki diişmäk datası en eski olan yazılar',
'move'                    => 'Aadını diiştir',
'movethispage'            => 'Sayfayı taşı',

# Book sources
'booksources' => 'Kaynak kiyatlar',

'alphaindexline' => '$1den $2e',
'version'        => 'Versiya',

# Special:Log
'specialloguserlabel'  => 'Kullanıcı:',
'speciallogtitlelabel' => 'Yazı adı:',
'log'                  => 'Jurnallar',
'all-logs-page'        => 'Hepsi jurnallar',

# Special:Allpages
'nextpage'       => 'Geeriki sayfa ($1)',
'prevpage'       => 'İlerki sayfa ($1)',
'allpagesfrom'   => 'Listaya düzmää başlanılacêk bukvalar:',
'allarticles'    => 'Hepsi yazılar',
'allpagessubmit' => 'Git',
'allpagesprefix' => 'Gösterin sayfaları angıları çekeder bukvalarlan ani buraya yazdınız:',

# E-mail user
'emailuser' => 'Gönder bu kullanıcıya bir e-mail',

# Watchlist
'watchlist'            => 'Bakmaa listam',
'mywatchlist'          => 'Bakmaa listam',
'watchlistfor'         => "('''$1''' için)",
'addedwatch'           => 'Bakmaa listasına registrat edildi.',
'removedwatch'         => 'Bakmaa listanızdan silindi',
'removedwatchtext'     => '"$1" yapraı siir listanızdan silindi.',
'watch'                => 'Bak',
'watchthispage'        => 'Bak bu sayfaya',
'unwatch'              => 'Durgun sayfa izlemää',
'watchlist-details'    => 'Diil konuşmaa sayfaları $1 sayfa bakmaa listanızda.',
'wlshowlast'           => 'Bitki $1 saati $2 günü göster $3',
'watchlist-hide-bots'  => 'Bot diişmäklerini sakla',
'watchlist-hide-own'   => 'Benim diişmäklerimi sakla',
'watchlist-hide-minor' => 'Küçük diişmäkleri sakla',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Bakılêr...',
'unwatching' => 'Durgundurulêr...',

# Delete/protect/revert
'deletepage'                  => 'Sayfayı sil',
'confirmdelete'               => 'Doorula silmää',
'deletesub'                   => '("$1" siliner)',
'historywarning'              => 'Bak: O sayfa angısını isteersiniz silmää istoriyası var:',
'actioncomplete'              => 'İşlik tamannandı.',
'deletedtext'                 => '"$1" silindi.
Yakın zamanda silinenleri görmää deyni: $2.',
'deletedarticle'              => '"$1" silindi',
'dellogpage'                  => 'Silmää jurnalı',
'deletecomment'               => 'Silmää sebep',
'deleteotherreason'           => 'Başka/ek sebep:',
'deletereasonotherlist'       => 'Başka sebep',
'rollbacklink'                => 'eski halinä dön',
'protectlogpage'              => 'Korunmak jurnalı',
'confirmprotect'              => 'Korunmaa doorula',
'protectcomment'              => 'Korunma altına almaa sebep:',
'protectexpiry'               => 'Bitmää datası:',
'protect_expiry_invalid'      => 'Yannış bitmää datası.',
'protect_expiry_old'          => 'Bitmää datası geçti.',
'protect-unchain'             => 'Taşıma kilidini kaldır',
'protect-text'                => 'Var nicä görmää hem diiştirmää buradan [[$1]] sayfasın korunmaa düzeyini.',
'protect-locked-access'       => 'Sizin esapın yok izni yazının korunmak düzeyini diiştirmää.
Burada bitki seçimner <strong>$1</strong> yazı diiştirmää deyni:',
'protect-cascadeon'           => 'Bu sayfa şindi korunêr onuştan ani girer {{PLURAL:$1|aşaadaki sayfaa, angısına|||aşaadaki sayfalara, angılarına}} konuldu kaskad korunmak. Sizä yakışêr diiştirin bu sayfanın korunmak düzeyin, ama bu etkilemez kaskad korunmaa.',
'protect-default'             => '(standart)',
'protect-fallback'            => ' "$1" izin iste',
'protect-level-autoconfirmed' => 'Registrat olmamış kullanıcıları köstekle',
'protect-level-sysop'         => 'sadäcä önderciler',
'protect-summary-cascade'     => 'kaskad',
'protect-expiring'            => 'bitmää datası $1 (UTC)',
'protect-cascade'             => 'Bu sayfaya girän sayfaları koru (kaskad korunmaa)',
'protect-cantedit'            => 'Siz bu yazının korunmak düzeyin bilmärsiniz diiştirmää, neçin ki sizin onu düzmää izniniz yok.',
'restriction-type'            => 'İzin:',
'restriction-level'           => 'Yasaklama düzeyi:',

# Undelete
'undeletebtn' => 'Geeri getir!',

# Namespace form on various pages
'namespace'      => 'Er adı:',
'invert'         => 'Seçilmiş dışındakileri göster',
'blanknamespace' => '(Baş)',

# Contributions
'contributions' => 'Kullanıcının katılmakları',
'mycontris'     => 'Katılmaklarım',
'contribsub2'   => '$1 ($2)',
'uctop'         => '(bitki)',
'month'         => 'Ay:',
'year'          => 'Yıl:',

'sp-contributions-newbies-sub' => 'Eni kullanıcılara deyni',
'sp-contributions-blocklog'    => 'Köstek jurnalı',

# What links here
'whatlinkshere'       => 'Baalantılar sayfaa',
'whatlinkshere-title' => '$1 baalantısı olan sayfalar',
'linklistsub'         => '(Baalantı listası)',
'linkshere'           => 'Buraya baalantısı var olan sayfalar:',
'nolinkshere'         => 'Yok buraya baalanan sayfa.',
'isredirect'          => 'yönnendirmäk sayfası',
'istemplate'          => 'eklemää',
'whatlinkshere-prev'  => '{{PLURAL:$1|ilerki|ilerki $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ilerki|ilerki $1}}',
'whatlinkshere-links' => '← links',

# Block/unblock
'blockip'       => 'Bu kullanıcıya köstek ol',
'ipboptions'    => '15 minut:15 minutes,1 saat:1 hour,3 saat:3 hours,24 saat:24 hours,48 saat:48 hours,1 afta:1 week,1 ay:1 month,zamansız:infinite', # display1:time1,display2:time2,...
'ipblocklist'   => 'Köstekli kullanıcılar hem IP adresleri listası',
'blocklink'     => 'köstek ol',
'unblocklink'   => 'köstek kaldır',
'contribslink'  => 'yardımnar',
'blocklogpage'  => 'Köstek jurnalı',
'blocklogentry' => '"[[$1]]" $2 durduruldu. Sebep',

# Move page
'movepage'         => 'Ad diişmäklii',
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
'movepage-moved'   => '<big>"$1" sayfasın eni adı: "$2"</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Bu adda bir sayfa bulunêr yaki o ad geçersiz angısını seçtiniz.
Yalvarêrêz başka bir ad seçmää.',
'talkexists'       => "'''Bu sayfa kendisi başarılan aktarıldı, ama konuşmaa sayfası aktarılamadı neçin ki eni ad altında bulunêr taa birisi. Yalvarêrêz onnarı ellän birleştirmää.'''",
'movedto'          => 'taşındı:',
'movetalk'         => 'Varsa hem aktar "konuşmaa" sayfasını.',
'talkpagemoved'    => 'İlgili konuşmaa sayfası da aktarıldı.',
'talkpagenotmoved' => 'İlgili konuşmaa sayfası <strong>aktarılmadı</strong>.',
'1movedto2'        => '[[$1]] sayfasın eni adı: [[$2]]',
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
'tooltip-ca-addsection'           => 'Bu diskussiya için kommentariya ekleyin.',
'tooltip-ca-viewsource'           => 'Bu sayfa korunmak altında. Gelinir kodunu sadä var nicä görünüz. Yok nicä diiştirmää.',
'tooltip-ca-protect'              => 'Bu sayfayı kolla',
'tooltip-ca-delete'               => 'Sayfayı sil',
'tooltip-ca-move'                 => 'Sayfanın adını diiştir',
'tooltip-ca-watch'                => 'Bu sayfanı ekle bakmaa listasına',
'tooltip-ca-unwatch'              => 'Brakın bu sayfaa bakmaa',
'tooltip-search'                  => '{{SITENAME}} içindä ara',
'tooltip-n-mainpage'              => 'Dönün baş yapraa',
'tooltip-n-portal'                => 'Proyekt uurunda, ne nändä, nelär var nicä yapmaa',
'tooltip-n-currentevents'         => 'Şindiki sluçaylar uurunda bitki bilgiler',
'tooltip-n-recentchanges'         => 'Bitki diişmäklär listası angıları Vikidä yapıldı.',
'tooltip-n-randompage'            => 'Razgele bir yazıya gidin',
'tooltip-n-help'                  => 'Yardım almaa deyni',
'tooltip-n-sitesupport'           => 'Material destek',
'tooltip-t-whatlinkshere'         => 'Başka viki sayfaların listası angıları bu sayfaa baalantı verdi',
'tooltip-t-contributions'         => 'Kullanıcının katılmak listasını gör',
'tooltip-t-emailuser'             => 'Bu kullanıcı için e-mail gönder',
'tooltip-t-upload'                => 'Pätret yaki media faylları ükle',
'tooltip-t-specialpages'          => 'Hepsi maasus yaprakların listasını göster',
'tooltip-ca-nstab-user'           => 'Kullanıcı sayfasın göster',
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

# Spam protection
'subcategorycount'       => 'Bu kategoriyada var $1 alt kategoriya.',
'categoryarticlecount'   => 'Bu kategoriyada $1 yazı var.',
'category-media-count'   => 'Bu kategoriyada {{PLURAL:$1|bir|$1 fayl}} var.',
'listingcontinuesabbrev' => '(devam)',

# Browsing diffs
'previousdiff' => '← İlerki versiyalan aradaki fark',
'nextdiff'     => 'Geerki versiyalan aradaki fark →',

# Media information
'file-info-size'       => '($1 × $2 piksel, fayl ölçüsü: $3, MIME tipi: $4)',
'svg-long-desc'        => '(SVG faylı, nominal $1 × $2 piksel, fayl ölçüsü: $3)',
'show-big-image-thumb' => '<small>Ön siir ölçüsü: $1 × $2 piksel</small>',

# Special:Newimages
'newimages' => 'Eni pätretler',

# Metadata
'metadata'          => 'Pätret detalları',
'metadata-expand'   => 'Detalları göster',
'metadata-collapse' => 'Detalları gösterme',

# External editor support
'edit-externally'      => 'Kompyuterinizdäki uygulamaklarlan faylı düz',
'edit-externally-help' => 'Taa çok bilgi için var nicä bakmaa metadaki [http://meta.wikimedia.org/wiki/Help:External_editors dış uygulama instrumentläri] (angliyça) sayfasına.',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'Hepsini göster',
'namespacesall' => 'Hepsi',
'monthsall'     => 'hepsi',

# Watchlist editing tools
'watchlisttools-view' => 'İlgili diişmäkleri göster',
'watchlisttools-edit' => 'Siir listasını gör hem düzelt',
'watchlisttools-raw'  => 'Ham siir listasını düz',

);
