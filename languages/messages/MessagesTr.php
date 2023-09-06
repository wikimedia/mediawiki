<?php
/** Turkish (Türkçe)
 *
 * @file
 * @ingroup Languages
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'               => [ 'AktifKullanıcılar', 'EtkinKullanıcılar' ],
	'Allmessages'               => [ 'Tümİletiler', 'TümMesajlar' ],
	'Allpages'                  => [ 'TümSayfalar' ],
	'Ancientpages'              => [ 'EskiSayfalar' ],
	'Badtitle'                  => [ 'KötüBaşlık', 'BozukBaşlık' ],
	'Blankpage'                 => [ 'BoşSayfa' ],
	'Block'                     => [ 'Engelle', 'IPEngelle' ],
	'BlockList'                 => [ 'EngelListesi', 'IPEngelListesi', 'EngelListele' ],
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
	'Diff'                      => [ 'Fark' ],
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
	'RandomInCategory'          => [ 'RastgeleKategori', 'RastgeleUlam' ],
	'Randompage'                => [ 'Rastgele', 'RastgeleSayfa' ],
	'Randomredirect'            => [ 'RastgeleYönlendirme' ],
	'Recentchanges'             => [ 'SonDeğişiklikler' ],
	'Recentchangeslinked'       => [ 'İlgiliDeğişiklikler' ],
	'Renameuser'                => [ 'KullanıcıAdınıDeğiştir', 'KullanıcıİsminiDeğiştir' ],
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

/** @phpcs-require-sorted-array */
$magicWords = [
	'anchorencode'              => [ '0', 'ÇENGELKODLAMA:', 'ANCHORENCODE' ],
	'articlepath'               => [ '0', 'MADDEYOLU', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'ÜSTSAYFAADI', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'ÜSTSAYFAADIU', 'BASEPAGENAMEE' ],
	'canonicalurl'              => [ '0', 'KURALLIURL:', 'CANONICALURL:' ],
	'canonicalurle'             => [ '0', 'KURALLIURLU:', 'CANONICALURLE:' ],
	'contentlanguage'           => [ '1', 'İÇERİKDİLİ', 'İÇERİKLİSANI', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'currentday'                => [ '1', 'MEVCUTGÜN', 'GÜNCELGÜN', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'MEVCUTGÜN2', 'GÜNCELGÜN2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'MEVCUTGÜNADI', 'GÜNCELGÜNADI', 'CURRENTDAYNAME' ],
	'currentdow'                => [ '1', 'MEVCUTHAFTANINGÜNÜ', 'GÜNCELHAFTANINGÜNÜ', 'CURRENTDOW' ],
	'currenthour'               => [ '1', 'MEVCUTSAAT', 'GÜNCELSAAT', 'CURRENTHOUR' ],
	'currentmonth'              => [ '1', 'MEVCUTAY', 'MEVCUTAY2', 'GÜNCELAY', 'GÜNCELAY2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MEVCUTAY1', 'GÜNCELAY1', 'CURRENTMONTH1' ],
	'currentmonthabbrev'        => [ '1', 'MEVCUTAYKISALTMASI', 'GÜNCELAYKISALTMASI', 'CURRENTMONTHABBREV' ],
	'currentmonthname'          => [ '1', 'MEVCUTAYADI', 'GÜNCELAYADI', 'CURRENTMONTHNAME' ],
	'currentmonthnamegen'       => [ '1', 'MEVCUTAYADIİYELİK', 'GÜNCELAYADIİYELİK', 'CURRENTMONTHNAMEGEN' ],
	'currenttime'               => [ '1', 'MEVCUTZAMAN', 'GÜNCELZAMAN', 'CURRENTTIME' ],
	'currenttimestamp'          => [ '1', 'MEVCUTZAMANBİLGİSİ', 'GÜNCELZAMANBİLGİSİ', 'CURRENTTIMESTAMP' ],
	'currentversion'            => [ '1', 'MEVCUTSÜRÜM', 'GÜNCELSÜRÜM', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'MEVCUTHAFTA', 'GÜNCELHAFTA', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'MEVCUTYIL', 'GÜNCELYIL', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'VARSAYILANSIRALA:', 'VARSAYILANSIRALAMAANAHTARI:', 'VARSAYILANKATEGORİSIRALA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'defaultsort_noerror'       => [ '0', 'hatayok', 'hatasız', 'noerror' ],
	'directionmark'             => [ '1', 'YÖNİŞARETİ:', 'DIRECTIONMARK', 'DIRMARK' ],
	'displaytitle'              => [ '1', 'BAŞLIKGÖSTER', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'DOSYAYOLU:', 'DOSYA_YOLU:', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__İÇİNDEKİLERZORUNLU__', '__FORCETOC__' ],
	'formatdate'                => [ '0', 'biçimtarih', 'tarihbiçimi', 'formattarihi', 'tarihformatı', 'formatdate', 'dateformat' ],
	'formatnum'                 => [ '0', 'BİÇİMNUM', 'FORMATNUM' ],
	'fullpagename'              => [ '1', 'TAMSAYFAADI', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'TAMSAYFAADIU', 'FULLPAGENAMEE' ],
	'fullurl'                   => [ '0', 'TAMURL:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'TAMURLU:', 'FULLURLE:' ],
	'gender'                    => [ '0', 'CİNSİYET:', 'GENDER:' ],
	'grammar'                   => [ '0', 'DİLBİLGİSİ:', 'GRAMER:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__GİZLİKAT__', '__GİZLİKATEGORİ__', '__HIDDENCAT__' ],
	'img_baseline'              => [ '1', 'tabançizgisi', 'altçizgi', 'baseline' ],
	'img_border'                => [ '1', 'sınır', 'border' ],
	'img_bottom'                => [ '1', 'taban', 'bottom' ],
	'img_center'                => [ '1', 'orta', 'center', 'centre' ],
	'img_class'                 => [ '1', 'sınıf=$1', 'class=$1' ],
	'img_framed'                => [ '1', 'çerçeveli', 'çerçeve', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'çerçevesiz', 'frameless' ],
	'img_left'                  => [ '1', 'sol', 'left' ],
	'img_link'                  => [ '1', 'bağlantı=$1', 'link=$1' ],
	'img_manualthumb'           => [ '1', 'küçükresim=$1', 'küçük=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'merkez', 'middle' ],
	'img_none'                  => [ '1', 'yok', 'none' ],
	'img_page'                  => [ '1', 'sayfa=$1', 'sayfa $1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'sağ', 'right' ],
	'img_sub'                   => [ '1', 'alt', 'sub' ],
	'img_super'                 => [ '1', 'üst', 'üs', 'super', 'sup' ],
	'img_text_bottom'           => [ '1', 'metin-taban', 'text-bottom' ],
	'img_text_top'              => [ '1', 'metin-tavan', 'metin-tepe', 'text-top' ],
	'img_thumbnail'             => [ '1', 'küçükresim', 'küçük', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'tepe', 'tavan', 'top' ],
	'img_upright'               => [ '1', 'dikey', 'dikey=$1', 'dikey $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_width'                 => [ '1', '$1pik', '$1piksel', '$1px' ],
	'index'                     => [ '1', '__DİZİN__', '__ENDEKS__', '__INDEX__' ],
	'int'                       => [ '0', 'İNT:', 'INT:' ],
	'language'                  => [ '0', '#DİL:', '#LİSAN:', '#LANGUAGE:' ],
	'lc'                        => [ '0', 'KH:', 'LC:' ],
	'lcfirst'                   => [ '0', 'KHİLK:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'YERELGÜN', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'YERELGÜN2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'YERELGÜNADI', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'YERELHAFTANINGÜNÜ', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'YERELSAAT', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'YERELAY', 'YERELAY2', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'YERELAY1', 'LOCALMONTH1' ],
	'localmonthabbrev'          => [ '1', 'YERELAYKISALTMASI', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'YERELAYADI', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'YERELAYADIİYELİK', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'YERELZAMAN', 'LOCALTIME' ],
	'localtimestamp'            => [ '1', 'YERELZAMANBİLGİSİ', 'LOCALTIMESTAMP' ],
	'localurl'                  => [ '0', 'YERELURL:', 'LOCALURL:' ],
	'localurle'                 => [ '0', 'YERELURLU:', 'LOCALURLE:' ],
	'localweek'                 => [ '1', 'YERELHAFTA', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'YERELYIL', 'LOCALYEAR' ],
	'msg'                       => [ '0', 'MSJ:', 'İLT:', 'MSG:' ],
	'msgnw'                     => [ '0', 'MSJYN:', 'İLTYN:', 'MSGNW:' ],
	'namespace'                 => [ '1', 'ADALANI', 'İSİMALANI', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'ADALANIU', 'İSİMALANIU', 'NAMESPACEE' ],
	'namespacenumber'           => [ '1', 'ADALANINUMARASI', 'NAMESPACENUMBER' ],
	'newsectionlink'            => [ '1', '__YENİBAŞLIKBAĞLANTISI__', '__NEWSECTIONLINK__' ],
	'nocontentconvert'          => [ '0', '__İÇERİKDÖNÜŞÜMÜYOK__', '__İDY__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'noeditsection'             => [ '0', '__DEĞİŞTİRYOK__', '__DÜZENLEMEYOK__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__GALERİYOK__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__DİZİNYOK__', '__ENDEKSYOK__', '__NOINDEX__' ],
	'nonewsectionlink'          => [ '1', '__YENİBAŞLIKBAĞLANTISIYOK__', '__NONEWSECTIONLINK__' ],
	'notitleconvert'            => [ '0', '__BAŞLIKDÖNÜŞÜMÜYOK__', '__BDY__', '__NOTITLECONVERT__', '__NOTC__' ],
	'notoc'                     => [ '0', '__İÇİNDEKİLERYOK__', '__NOTOC__' ],
	'ns'                        => [ '0', 'AA:', 'AB:', 'NS:' ],
	'nse'                       => [ '0', 'AAU:', 'ABU:', 'NSE:' ],
	'numberingroup'             => [ '1', 'GRUPTAKİSAYI', 'GRUBUNSAYISI', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'numberofactiveusers'       => [ '1', 'AKTİFKULLANICISAYISI', 'ETKİNKULLANICISAYISI', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'HİZMETLİSAYISI', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'MADDESAYISI', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'DEĞİŞİKLİKSAYISI', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'DOSYASAYISI', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'SAYFASAYISI', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'KULLANICISAYISI', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'DOLSOL', 'PADLEFT' ],
	'padright'                  => [ '0', 'DOLSAĞ', 'PADRIGHT' ],
	'pageid'                    => [ '0', 'SAYFANO', 'PAGEID' ],
	'pagename'                  => [ '1', 'SAYFAADI', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'SAYFAADIU', 'PAGENAMEE' ],
	'pagesincategory'           => [ '1', 'KATEGORİDEKİSAYFALAR', 'KATTAKİSAYFALAR', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_all'       => [ '0', 'tüm', 'all' ],
	'pagesincategory_files'     => [ '0', 'dosyalar', 'files' ],
	'pagesincategory_pages'     => [ '0', 'sayfalar', 'pages' ],
	'pagesincategory_subcats'   => [ '0', 'altkategoriler', 'subcats' ],
	'pagesinnamespace'          => [ '1', 'İSİMALANINDAKİSAYFALAR', 'İADAKİSAYFALAR', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'pagesize'                  => [ '1', 'SAYFABOYUTU', 'PAGESIZE' ],
	'plural'                    => [ '0', 'ÇOĞUL:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', 'KORUMASEVİYESİ', 'PROTECTIONLEVEL' ],
	'raw'                       => [ '0', 'HAM:', 'RAW:' ],
	'redirect'                  => [ '0', '#YÖNLENDİRME', '#YÖNLENDİR', '#yönlendirme', '#yönlendir', '#REDIRECT' ],
	'revisionday'               => [ '1', 'SÜRÜMGÜNÜ', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'SÜRÜMGÜNÜ2', 'REVISIONDAY2' ],
	'revisionid'                => [ '1', 'SÜRÜMNU', 'SÜRÜMNO', 'REVİZYONNU', 'REVİZYONNO', 'REVISIONID' ],
	'revisionmonth'             => [ '1', 'SÜRÜMAYI', 'REVISIONMONTH' ],
	'revisionmonth1'            => [ '1', 'SÜRÜMAYI1', 'REVISIONMONTH1' ],
	'revisiontimestamp'         => [ '1', 'SÜRÜMZAMANBİLGİSİ', 'REVISIONTIMESTAMP' ],
	'revisionuser'              => [ '1', 'SÜRÜMKULLANICI', 'REVISIONUSER' ],
	'revisionyear'              => [ '1', 'SÜRÜMYILI', 'REVISIONYEAR' ],
	'safesubst'                 => [ '0', 'GÜVENLİYERİNEKOY:', 'GÜVENLİKOPYALA:', 'GÜVENLİAKTAR:', 'GÜVENLİYAPIŞTIR:', 'SAFESUBST:' ],
	'scriptpath'                => [ '0', 'BETİKYOLU', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'SUNUCU', 'SERVER' ],
	'servername'                => [ '0', 'SUNUCUADI', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'SİTEADI', 'SITENAME' ],
	'special'                   => [ '0', 'özel', 'special' ],
	'speciale'                  => [ '0', 'özelu', 'speciale' ],
	'staticredirect'            => [ '1', '__STATİKYÖNLENDİRME__', '__SABİTYÖNLENDİRME__', '__STATICREDIRECT__' ],
	'stylepath'                 => [ '0', 'BİÇEMYOLU', 'STYLEPATH' ],
	'subjectpagename'           => [ '1', 'KONUSAYFASIADI', 'MADDESAYFASIADI', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'KONUSAYFASIADIU', 'MADDESAYFASIADIU', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'subjectspace'              => [ '1', 'KONUALANI', 'MADDEALANI', 'KONUBOŞLUĞU', 'MADDEBOŞLUĞU', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'KONUALANIU', 'MADDEALANIU', 'KONUBOŞLUĞUU', 'MADDEBOŞLUĞUU', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'subpagename'               => [ '1', 'ALTSAYFAADI', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'ALTSAYFAADIU', 'SUBPAGENAMEE' ],
	'subst'                     => [ '0', 'YK:', 'YERİNEKOY:', 'KOPYALA:', 'AKTAR:', 'YAPIŞTIR:', 'SUBST:' ],
	'tag'                       => [ '0', 'etiket', 'tag' ],
	'talkpagename'              => [ '1', 'TARTIŞMASAYFASIADI', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'TARTIŞMASAYFASIADIU', 'TALKPAGENAMEE' ],
	'talkspace'                 => [ '1', 'TARTIŞMAALANI', 'TARTIŞMABOŞLUĞU', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'TARTIŞMAALANIU', 'TARTIŞMABOŞLUĞUU', 'TALKSPACEE' ],
	'toc'                       => [ '0', '__İÇİNDEKİLER__', '__TOC__' ],
	'uc'                        => [ '0', 'BH:', 'UC:' ],
	'ucfirst'                   => [ '0', 'BHİLK:', 'UCFIRST:' ],
	'urlencode'                 => [ '0', 'URLKODLAMA:', 'URLENCODE:' ],
	'url_path'                  => [ '0', 'YOL', 'PATH' ],
	'url_query'                 => [ '0', 'SORGU', 'QUERY' ],
	'url_wiki'                  => [ '0', 'VİKİ', 'WIKI' ],
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
