<?php
/** Turkish (Türkçe)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author 82-145
 * @author Bekiroflaz
 * @author BetelgeuSeginus
 * @author Bilalokms
 * @author Bombola
 * @author Cagrix
 * @author Cekli829
 * @author Coolland
 * @author Dbl2010
 * @author Don Alessandro
 * @author Emperyan
 * @author Erdemaslancan
 * @author Erkan Yilmaz
 * @author Fryed-peach
 * @author Geitost
 * @author Goktr001
 * @author Gorizon
 * @author Hanberke
 * @author Hcagri
 * @author Hedda Gabler
 * @author Ijon
 * @author Incelemeelemani
 * @author Joseph
 * @author Kaganer
 * @author Karduelis
 * @author Katpatuka
 * @author Khutuck
 * @author LuCKY
 * @author Mach
 * @author Manco Capac
 * @author Marmase
 * @author Meelo
 * @author Metal Militia
 * @author Mirzali
 * @author Mskyrider
 * @author Myildirim2007
 * @author Nazif İLBEK
 * @author Nemo bis
 * @author Rapsar
 * @author Reedy
 * @author Rhinestorm
 * @author Runningfridgesrule
 * @author Sadrettin
 * @author SiLveRLeaD
 * @author Srhat
 * @author Stultiwikia
 * @author Suelnur
 * @author Szoszv
 * @author Tarikozket
 * @author Tarkovsky
 * @author Trncmvsr
 * @author Universal Life
 * @author Urhixidur
 * @author Uğur Başak
 * @author Vito Genovese
 * @author Vugar 1981
 * @author לערי ריינהארט
 */

$namespaceNames = [
	NS_MEDIA            => 'Ortam',
	NS_SPECIAL          => 'Özel',
	NS_TALK             => 'Tartışma',
	NS_USER             => 'Kullanıcı',
	NS_USER_TALK        => 'Kullanıcı_mesaj',
	NS_PROJECT_TALK     => '$1_tartışma',
	NS_FILE             => 'Dosya',
	NS_FILE_TALK        => 'Dosya_tartışma',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_tartışma',
	NS_TEMPLATE         => 'Şablon',
	NS_TEMPLATE_TALK    => 'Şablon_tartışma',
	NS_HELP             => 'Yardım',
	NS_HELP_TALK        => 'Yardım_tartışma',
	NS_CATEGORY         => 'Kategori',
	NS_CATEGORY_TALK    => 'Kategori_tartışma',
];

$namespaceAliases = [
	'Medya'              => NS_MEDIA,
	'Resim'              => NS_FILE,
	'Resim_tartışma'     => NS_FILE_TALK,
	'MedyaViki'          => NS_MEDIAWIKI,
	'MedyaViki_tartışma' => NS_MEDIAWIKI_TALK,
];

$specialPageAliases = [
	'Activeusers'               => [ 'AktifKullanıcılar', 'EtkinKullanıcılar' ],
	'Allmessages'               => [ 'Tümİletiler', 'TümMesajlar' ],
	'Allpages'                  => [ 'TümSayfalar' ],
	'Ancientpages'              => [ 'EskiSayfalar' ],
	'Badtitle'                  => [ 'KötüBaşlık', 'BozukBaşlık' ],
	'Blankpage'                 => [ 'BoşSayfa' ],
	'Block'                     => [ 'Engelle', 'IPEngelle' ],
	'Booksources'               => [ 'KitapKaynakları' ],
	'BrokenRedirects'           => [ 'BozukYönlendirmeler' ],
	'Categories'                => [ 'Kategoriler', 'Ulamlar' ],
	'ChangeEmail'               => [ 'E-postaDeğiştir' ],
	'ChangePassword'            => [ 'ParolaDeğiştir', 'ParolaSıfırla' ],
	'ComparePages'              => [ 'SayfaKarşılaştır' ],
	'Confirmemail'              => [ 'E-postaDoğrula' ],
	'Contributions'             => [ 'Katkılar' ],
	'CreateAccount'             => [ 'HesapOluştur' ],
	'Deadendpages'              => [ 'BağlantısızSayfalar' ],
	'DeletedContributions'      => [ 'SilinenKatkılar' ],
	'DoubleRedirects'           => [ 'ÇiftYönlendirmeler' ],
	'EditWatchlist'             => [ 'İzlemeListesiDüzenle' ],
	'Emailuser'                 => [ 'E-postaGönder' ],
	'ExpandTemplates'           => [ 'ŞablonlarıGenişlet' ],
	'Export'                    => [ 'DışaAktar', 'DışarıAktar' ],
	'Fewestrevisions'           => [ 'EnAzRevizyon' ],
	'FileDuplicateSearch'       => [ 'KopyaDosyaArama', 'ÇiftDosyaArama' ],
	'Filepath'                  => [ 'DosyaYolu', 'DosyaKonumu' ],
	'Import'                    => [ 'İçeAktar', 'İçeriAktar' ],
	'Invalidateemail'           => [ 'E-postaDoğrulamaİptal' ],
	'JavaScriptTest'            => [ 'JavaScriptTesti' ],
	'BlockList'                 => [ 'EngelListesi', 'IPEngelListesi', 'EngelListele' ],
	'LinkSearch'                => [ 'BağArama', 'BağlantıArama' ],
	'Listadmins'                => [ 'HizmetliListele', 'YöneticiListele', 'HizmetliListesi', 'YöneticiListesi' ],
	'Listbots'                  => [ 'BotListele', 'BotListesi' ],
	'Listfiles'                 => [ 'DosyaListesi', 'DosyaListele', 'ResimListesi', 'ResimListele' ],
	'Listgrouprights'           => [ 'GrupHaklarıListesi', 'GrupHaklarıListele', 'KullanıcıGrupHakları' ],
	'Listredirects'             => [ 'YönlendirmeListesi', 'YönlendirmeListele' ],
	'Listusers'                 => [ 'KullanıcıListesi', 'KullanıcıListele' ],
	'Lockdb'                    => [ 'DBKilitle', 'VeritabanıKilitle' ],
	'Log'                       => [ 'Günlük', 'Günlükler', 'Kayıt', 'Kayıtlar' ],
	'Lonelypages'               => [ 'YalnızSayfalar', 'YetimSayfalar' ],
	'Longpages'                 => [ 'UzunSayfalar' ],
	'MergeHistory'              => [ 'GeçmişBirleştir' ],
	'MIMEsearch'                => [ 'MIMEArama' ],
	'Mostcategories'            => [ 'EnFazlaKategorili' ],
	'Mostimages'                => [ 'EnÇokBağlantıVerilenDosyalar' ],
	'Mostinterwikis'            => [ 'EnFazlaİnterviki' ],
	'Mostlinked'                => [ 'EnÇokBağlantıVerilenSayfalar' ],
	'Mostlinkedcategories'      => [ 'EnÇokBağlantıVerilenKategoriler' ],
	'Mostlinkedtemplates'       => [ 'EnÇokBağlantıVerilenŞablonlar' ],
	'Mostrevisions'             => [ 'EnÇokRevizyon' ],
	'Movepage'                  => [ 'SayfaTaşı' ],
	'Mycontributions'           => [ 'Katkılarım' ],
	'MyLanguage'                => [ 'Dilim', 'BenimDilim' ],
	'Mypage'                    => [ 'Sayfam', 'BenimSayfam', 'KullanıcıSayfam' ],
	'Mytalk'                    => [ 'MesajSayfam', 'İletiSayfam' ],
	'Myuploads'                 => [ 'Yüklemelerim' ],
	'Newimages'                 => [ 'YeniDosyalar', 'YeniResimler' ],
	'Newpages'                  => [ 'YeniSayfalar' ],
	'PasswordReset'             => [ 'ParolaSıfırlama' ],
	'PermanentLink'             => [ 'KalıcıBağ' ],
	'Preferences'               => [ 'Tercihler', 'Ayarlar' ],
	'Prefixindex'               => [ 'ÖnekDizini' ],
	'Protectedpages'            => [ 'KorunanSayfalar' ],
	'Protectedtitles'           => [ 'KorunanBaşlıklar' ],
	'Randompage'                => [ 'Rastgele', 'RastgeleSayfa' ],
	'RandomInCategory'          => [ 'RastgeleKategori', 'RastgeleUlam' ],
	'Randomredirect'            => [ 'RastgeleYönlendirme' ],
	'Recentchanges'             => [ 'SonDeğişiklikler' ],
	'Recentchangeslinked'       => [ 'İlgiliDeğişiklikler' ],
	'Revisiondelete'            => [ 'RevizyonSil' ],
	'Search'                    => [ 'Ara', 'Arama' ],
	'Shortpages'                => [ 'KısaSayfalar' ],
	'Specialpages'              => [ 'ÖzelSayfalar' ],
	'Statistics'                => [ 'İstatistikler' ],
	'Tags'                      => [ 'Etiketler' ],
	'Unblock'                   => [ 'EngeliKaldır', 'EngelKaldır' ],
	'Uncategorizedcategories'   => [ 'KategorisizKategoriler' ],
	'Uncategorizedimages'       => [ 'KategorisizDosyalar' ],
	'Uncategorizedpages'        => [ 'KategorisizSayfalar' ],
	'Uncategorizedtemplates'    => [ 'KategorisizŞablonlar' ],
	'Undelete'                  => [ 'GeriGetir' ],
	'Unlockdb'                  => [ 'DBKilidiAç', 'VeritabanıKilidiAç' ],
	'Unusedcategories'          => [ 'KullanılmayanKategoriler' ],
	'Unusedimages'              => [ 'KullanılmayanDosyalar' ],
	'Unusedtemplates'           => [ 'KullanılmayanŞablonlar' ],
	'Unwatchedpages'            => [ 'İzlenmeyenSayfalar' ],
	'Upload'                    => [ 'Yükle' ],
	'UploadStash'               => [ 'ZulaYükle', 'ZulaYükleme' ],
	'Userlogin'                 => [ 'KullanıcıOturumuAçma', 'KullanıcıGiriş' ],
	'Userlogout'                => [ 'KullanıcıOturumuKapatma', 'KullanıcıÇıkış' ],
	'Userrights'                => [ 'KullanıcıHakları' ],
	'Version'                   => [ 'Sürüm', 'Versiyon' ],
	'Wantedcategories'          => [ 'İstenenKategoriler' ],
	'Wantedfiles'               => [ 'İstenenDosyalar' ],
	'Wantedpages'               => [ 'İstenenSayfalar' ],
	'Wantedtemplates'           => [ 'İstenenŞablonlar' ],
	'Watchlist'                 => [ 'İzlemeListesi' ],
	'Whatlinkshere'             => [ 'SayfayaBağlantılar' ],
	'Withoutinterwiki'          => [ 'İntervikisiz' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#YÖNLENDİRME', '#YÖNLENDİR', '#REDIRECT' ],
	'notoc'                     => [ '0', '__İÇİNDEKİLERYOK__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__GALERİYOK__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__İÇİNDEKİLERZORUNLU__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__İÇİNDEKİLER__', '__TOC__' ],
	'noeditsection'             => [ '0', '__DEĞİŞTİRYOK__', '__DÜZENLEMEYOK__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MEVCUTAY', 'MEVCUTAY2', 'GÜNCELAY', 'GÜNCELAY2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MEVCUTAY1', 'GÜNCELAY1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'MEVCUTAYADI', 'GÜNCELAYADI', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'MEVCUTAYADIİYELİK', 'GÜNCELAYADIİYELİK', 'CURRENTMONTHNAMEGEN' ],
	'currentmonthabbrev'        => [ '1', 'MEVCUTAYKISALTMASI', 'GÜNCELAYKISALTMASI', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'MEVCUTGÜN', 'GÜNCELGÜN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'MEVCUTGÜN2', 'GÜNCELGÜN2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'MEVCUTGÜNADI', 'GÜNCELGÜNADI', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'MEVCUTYIL', 'GÜNCELYIL', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'MEVCUTZAMAN', 'GÜNCELZAMAN', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'MEVCUTSAAT', 'GÜNCELSAAT', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'YERELAY', 'YERELAY2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'YERELAY1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'YERELAYADI', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'YERELAYADIİYELİK', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'YERELAYKISALTMASI', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'YERELGÜN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'YERELGÜN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'YERELGÜNADI', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'YERELYIL', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'YERELZAMAN', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'YERELSAAT', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'SAYFASAYISI', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'MADDESAYISI', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'DOSYASAYISI', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'KULLANICISAYISI', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'AKTİFKULLANICISAYISI', 'ETKİNKULLANICISAYISI', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'DEĞİŞİKLİKSAYISI', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'SAYFAADI', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SAYFAADIU', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'ADALANI', 'İSİMALANI', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ADALANIU', 'İSİMALANIU', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'ADALANINUMARASI', 'NAMESPACENUMBER' ],
	'talkspace'                 => [ '1', 'TARTIŞMAALANI', 'TARTIŞMABOŞLUĞU', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'TARTIŞMAALANIU', 'TARTIŞMABOŞLUĞUU', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'KONUALANI', 'MADDEALANI', 'KONUBOŞLUĞU', 'MADDEBOŞLUĞU', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'KONUALANIU', 'MADDEALANIU', 'KONUBOŞLUĞUU', 'MADDEBOŞLUĞUU', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'TAMSAYFAADI', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'TAMSAYFAADIU', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'ALTSAYFAADI', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ALTSAYFAADIU', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'ÜSTSAYFAADI', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ÜSTSAYFAADIU', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'TARTIŞMASAYFASIADI', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'TARTIŞMASAYFASIADIU', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'KONUSAYFASIADI', 'MADDESAYFASIADI', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'KONUSAYFASIADIU', 'MADDESAYFASIADIU', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'msg'                       => [ '0', 'MSJ:', 'İLT:', 'MSG:' ],
	'subst'                     => [ '0', 'YK:', 'YERİNEKOY:', 'KOPYALA:', 'AKTAR:', 'YAPIŞTIR:', 'SUBST:' ],
	'safesubst'                 => [ '0', 'GÜVENLİYERİNEKOY:', 'GÜVENLİKOPYALA:', 'GÜVENLİAKTAR:', 'GÜVENLİYAPIŞTIR:', 'SAFESUBST:' ],
	'msgnw'                     => [ '0', 'MSJYN:', 'İLTYN:', 'MSGNW:' ],
	'img_thumbnail'             => [ '1', 'küçükresim', 'küçük', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'küçükresim=$1', 'küçük=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'sağ', 'right' ],
	'img_left'                  => [ '1', 'sol', 'left' ],
	'img_none'                  => [ '1', 'yok', 'none' ],
	'img_width'                 => [ '1', '$1pik', '$1piksel', '$1px' ],
	'img_center'                => [ '1', 'orta', 'center', 'centre' ],
	'img_framed'                => [ '1', 'çerçeveli', 'çerçeve', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'çerçevesiz', 'frameless' ],
	'img_page'                  => [ '1', 'sayfa=$1', 'sayfa $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'dikey', 'dikey=$1', 'dikey $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'sınır', 'border' ],
	'img_baseline'              => [ '1', 'tabançizgisi', 'altçizgi', 'baseline' ],
	'img_sub'                   => [ '1', 'alt', 'sub' ],
	'img_super'                 => [ '1', 'üst', 'üs', 'super', 'sup' ],
	'img_top'                   => [ '1', 'tepe', 'tavan', 'top' ],
	'img_text_top'              => [ '1', 'metin-tavan', 'metin-tepe', 'text-top' ],
	'img_middle'                => [ '1', 'merkez', 'middle' ],
	'img_bottom'                => [ '1', 'taban', 'bottom' ],
	'img_text_bottom'           => [ '1', 'metin-taban', 'text-bottom' ],
	'img_link'                  => [ '1', 'bağlantı=$1', 'link=$1' ],
	'img_class'                 => [ '1', 'sınıf=$1', 'class=$1' ],
	'int'                       => [ '0', 'İNT:', 'INT:' ],
	'sitename'                  => [ '1', 'SİTEADI', 'SITENAME' ],
	'ns'                        => [ '0', 'AA:', 'AB:', 'NS:' ],
	'nse'                       => [ '0', 'AAU:', 'ABU:', 'NSE:' ],
	'localurl'                  => [ '0', 'YERELURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'YERELURLU:', 'LOCALURLE:' ],
	'articlepath'               => [ '0', 'MADDEYOLU', 'ARTICLEPATH' ],
	'pageid'                    => [ '0', 'SAYFANO', 'PAGEID' ],
	'server'                    => [ '0', 'SUNUCU', 'SERVER' ],
	'servername'                => [ '0', 'SUNUCUADI', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'BETİKYOLU', 'SCRIPTPATH' ],
	'stylepath'                 => [ '0', 'BİÇEMYOLU', 'STYLEPATH' ],
	'grammar'                   => [ '0', 'DİLBİLGİSİ:', 'GRAMER:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'CİNSİYET:', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__BAŞLIKDÖNÜŞÜMÜYOK__', '__BDY__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__İÇERİKDÖNÜŞÜMÜYOK__', '__İDY__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'MEVCUTHAFTA', 'GÜNCELHAFTA', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'MEVCUTHAFTANINGÜNÜ', 'GÜNCELHAFTANINGÜNÜ', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'YERELHAFTA', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'YERELHAFTANINGÜNÜ', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'SÜRÜMNU', 'SÜRÜMNO', 'REVİZYONNU', 'REVİZYONNO', 'REVISIONID' ],
	'revisionday'               => [ '1', 'SÜRÜMGÜNÜ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'SÜRÜMGÜNÜ2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'SÜRÜMAYI', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'SÜRÜMAYI1', 'REVISIONMONTH1' ],
	'revisionyear'              => [ '1', 'SÜRÜMYILI', 'REVISIONYEAR' ],
	'revisiontimestamp'         => [ '1', 'SÜRÜMZAMANBİLGİSİ', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'SÜRÜMKULLANICI', 'REVISIONUSER' ],
	'plural'                    => [ '0', 'ÇOĞUL:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'TAMURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'TAMURLU:', 'FULLURLE:' ],
	'canonicalurl'              => [ '0', 'KURALLIURL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'KURALLIURLU:', 'CANONICALURLE:' ],
	'lcfirst'                   => [ '0', 'KHİLK:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'BHİLK:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'KH:', 'LC:' ],
	'uc'                        => [ '0', 'BH:', 'UC:' ],
	'raw'                       => [ '0', 'HAM:', 'RAW:' ],
	'displaytitle'              => [ '1', 'BAŞLIKGÖSTER', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__YENİBAŞLIKBAĞLANTISI__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__YENİBAŞLIKBAĞLANTISIYOK__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'MEVCUTSÜRÜM', 'GÜNCELSÜRÜM', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'URLKODLAMA:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'ÇENGELKODLAMA:', 'ANCHORENCODE' ],
	'currenttimestamp'          => [ '1', 'MEVCUTZAMANBİLGİSİ', 'GÜNCELZAMANBİLGİSİ', 'CURRENTTIMESTAMP' ],
	'localtimestamp'            => [ '1', 'YERELZAMANBİLGİSİ', 'LOCALTIMESTAMP' ],
	'directionmark'             => [ '1', 'YÖNİŞARETİ:', 'DIRECTIONMARK', 'DIRMARK' ],
	'language'                  => [ '0', '#DİL:', '#LİSAN:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'İÇERİKDİLİ', 'İÇERİKLİSANI', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'İSİMALANINDAKİSAYFALAR', 'İADAKİSAYFALAR', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'HİZMETLİSAYISI', 'NUMBEROFADMINS' ],
	'formatnum'                 => [ '0', 'BİÇİMNUM', 'FORMATNUM' ],
	'padleft'                   => [ '0', 'DOLSOL', 'PADLEFT' ],
	'padright'                  => [ '0', 'DOLSAĞ', 'PADRIGHT' ],
	'special'                   => [ '0', 'özel', 'special' ],
	'speciale'                  => [ '0', 'özelu', 'speciale' ],
	'defaultsort'               => [ '1', 'VARSAYILANSIRALA:', 'VARSAYILANSIRALAMAANAHTARI:', 'VARSAYILANKATEGORİSIRALA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'DOSYAYOLU:', 'DOSYA_YOLU:', 'FILEPATH:' ],
	'tag'                       => [ '0', 'etiket', 'tag' ],
	'hiddencat'                 => [ '1', '__GİZLİKAT__', '__GİZLİKATEGORİ__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'KATEGORİDEKİSAYFALAR', 'KATTAKİSAYFALAR', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'SAYFABOYUTU', 'PAGESIZE' ],
	'index'                     => [ '1', '__DİZİN__', '__ENDEKS__', '__INDEX__' ],
	'noindex'                   => [ '1', '__DİZİNYOK__', '__ENDEKSYOK__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'GRUPTAKİSAYI', 'GRUBUNSAYISI', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__STATİKYÖNLENDİRME__', '__SABİTYÖNLENDİRME__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'KORUMASEVİYESİ', 'PROTECTIONLEVEL' ],
	'formatdate'                => [ '0', 'biçimtarih', 'tarihbiçimi', 'formattarihi', 'tarihformatı', 'formatdate', 'dateformat' ],
	'url_path'                  => [ '0', 'YOL', 'PATH' ],
	'url_wiki'                  => [ '0', 'VİKİ', 'WIKI' ],
	'url_query'                 => [ '0', 'SORGU', 'QUERY' ],
	'defaultsort_noerror'       => [ '0', 'hatayok', 'hatasız', 'noerror' ],
	'pagesincategory_all'       => [ '0', 'tüm', 'all' ],
	'pagesincategory_pages'     => [ '0', 'sayfalar', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'altkategoriler', 'subcats' ],
	'pagesincategory_files'     => [ '0', 'dosyalar', 'files' ],
];

$dateFormats = [
	'mdy time' => 'H.i',
	'mdy both' => 'H.i, F j, Y',
	'dmy time' => 'H.i',
	'dmy both' => 'H.i, j F Y',
	'ymd time' => 'H.i',
	'ymd both' => 'H.i, Y F j',
];

$separatorTransformTable = [ ',' => '.', '.' => ',' ];
$linkTrail = '/^([a-zÇĞçğİıÖöŞşÜüÂâÎîÛû]+)(.*)$/sDu';
