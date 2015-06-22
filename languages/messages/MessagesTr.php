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

$namespaceNames = array(
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
);

$namespaceAliases = array(
	'Medya'              => NS_MEDIA,
	'Resim'              => NS_FILE,
	'Resim_tartışma'     => NS_FILE_TALK,
	'MedyaViki'          => NS_MEDIAWIKI,
	'MedyaViki_tartışma' => NS_MEDIAWIKI_TALK,
);

$specialPageAliases = array(
	'Activeusers'               => array( 'AktifKullanıcılar', 'EtkinKullanıcılar' ),
	'Allmessages'               => array( 'Tümİletiler', 'TümMesajlar' ),
	'Allpages'                  => array( 'TümSayfalar' ),
	'Ancientpages'              => array( 'EskiSayfalar' ),
	'Badtitle'                  => array( 'KötüBaşlık', 'BozukBaşlık' ),
	'Blankpage'                 => array( 'BoşSayfa' ),
	'Block'                     => array( 'Engelle', 'IPEngelle' ),
	'Booksources'               => array( 'KitapKaynakları' ),
	'BrokenRedirects'           => array( 'BozukYönlendirmeler' ),
	'Categories'                => array( 'Kategoriler', 'Ulamlar' ),
	'ChangeEmail'               => array( 'E-postaDeğiştir' ),
	'ChangePassword'            => array( 'ParolaDeğiştir', 'ParolaSıfırla' ),
	'ComparePages'              => array( 'SayfaKarşılaştır' ),
	'Confirmemail'              => array( 'E-postaDoğrula' ),
	'Contributions'             => array( 'Katkılar' ),
	'CreateAccount'             => array( 'HesapOluştur' ),
	'Deadendpages'              => array( 'BağlantısızSayfalar' ),
	'DeletedContributions'      => array( 'SilinenKatkılar' ),
	'DoubleRedirects'           => array( 'ÇiftYönlendirmeler' ),
	'EditWatchlist'             => array( 'İzlemeListesiDüzenle' ),
	'Emailuser'                 => array( 'E-postaGönder' ),
	'ExpandTemplates'           => array( 'ŞablonlarıGenişlet' ),
	'Export'                    => array( 'DışaAktar', 'DışarıAktar' ),
	'Fewestrevisions'           => array( 'EnAzRevizyon' ),
	'FileDuplicateSearch'       => array( 'KopyaDosyaArama', 'ÇiftDosyaArama' ),
	'Filepath'                  => array( 'DosyaYolu', 'DosyaKonumu' ),
	'Import'                    => array( 'İçeAktar', 'İçeriAktar' ),
	'Invalidateemail'           => array( 'E-postaDoğrulamaİptal' ),
	'JavaScriptTest'            => array( 'JavaScriptTesti' ),
	'BlockList'                 => array( 'EngelListesi', 'IPEngelListesi', 'EngelListele' ),
	'LinkSearch'                => array( 'BağArama', 'BağlantıArama' ),
	'Listadmins'                => array( 'HizmetliListele', 'YöneticiListele', 'HizmetliListesi', 'YöneticiListesi' ),
	'Listbots'                  => array( 'BotListele', 'BotListesi' ),
	'Listfiles'                 => array( 'DosyaListesi', 'DosyaListele', 'ResimListesi', 'ResimListele' ),
	'Listgrouprights'           => array( 'GrupHaklarıListesi', 'GrupHaklarıListele', 'KullanıcıGrupHakları' ),
	'Listredirects'             => array( 'YönlendirmeListesi', 'YönlendirmeListele' ),
	'Listusers'                 => array( 'KullanıcıListesi', 'KullanıcıListele' ),
	'Lockdb'                    => array( 'DBKilitle', 'VeritabanıKilitle' ),
	'Log'                       => array( 'Günlük', 'Günlükler', 'Kayıt', 'Kayıtlar' ),
	'Lonelypages'               => array( 'YalnızSayfalar', 'YetimSayfalar' ),
	'Longpages'                 => array( 'UzunSayfalar' ),
	'MergeHistory'              => array( 'GeçmişBirleştir' ),
	'MIMEsearch'                => array( 'MIMEArama' ),
	'Mostcategories'            => array( 'EnFazlaKategorili' ),
	'Mostimages'                => array( 'EnÇokBağlantıVerilenDosyalar' ),
	'Mostinterwikis'            => array( 'EnFazlaİnterviki' ),
	'Mostlinked'                => array( 'EnÇokBağlantıVerilenSayfalar' ),
	'Mostlinkedcategories'      => array( 'EnÇokBağlantıVerilenKategoriler' ),
	'Mostlinkedtemplates'       => array( 'EnÇokBağlantıVerilenŞablonlar' ),
	'Mostrevisions'             => array( 'EnÇokRevizyon' ),
	'Movepage'                  => array( 'SayfaTaşı' ),
	'Mycontributions'           => array( 'Katkılarım' ),
	'MyLanguage'                => array( 'Dilim', 'BenimDilim' ),
	'Mypage'                    => array( 'Sayfam', 'BenimSayfam', 'KullanıcıSayfam' ),
	'Mytalk'                    => array( 'MesajSayfam', 'İletiSayfam' ),
	'Myuploads'                 => array( 'Yüklemelerim' ),
	'Newimages'                 => array( 'YeniDosyalar', 'YeniResimler' ),
	'Newpages'                  => array( 'YeniSayfalar' ),
	'PasswordReset'             => array( 'ParolaSıfırlama' ),
	'PermanentLink'             => array( 'KalıcıBağ' ),

	'Preferences'               => array( 'Tercihler', 'Ayarlar' ),
	'Prefixindex'               => array( 'ÖnekDizini' ),
	'Protectedpages'            => array( 'KorunanSayfalar' ),
	'Protectedtitles'           => array( 'KorunanBaşlıklar' ),
	'Randompage'                => array( 'Rastgele', 'RastgeleSayfa' ),
	'RandomInCategory'          => array( 'RastgeleKategori', 'RastgeleUlam' ),
	'Randomredirect'            => array( 'RastgeleYönlendirme' ),
	'Recentchanges'             => array( 'SonDeğişiklikler' ),
	'Recentchangeslinked'       => array( 'İlgiliDeğişiklikler' ),
	'Revisiondelete'            => array( 'RevizyonSil' ),
	'Search'                    => array( 'Ara', 'Arama' ),
	'Shortpages'                => array( 'KısaSayfalar' ),
	'Specialpages'              => array( 'ÖzelSayfalar' ),
	'Statistics'                => array( 'İstatistikler' ),
	'Tags'                      => array( 'Etiketler' ),
	'Unblock'                   => array( 'EngeliKaldır', 'EngelKaldır' ),
	'Uncategorizedcategories'   => array( 'KategorisizKategoriler' ),
	'Uncategorizedimages'       => array( 'KategorisizDosyalar' ),
	'Uncategorizedpages'        => array( 'KategorisizSayfalar' ),
	'Uncategorizedtemplates'    => array( 'KategorisizŞablonlar' ),
	'Undelete'                  => array( 'GeriGetir' ),
	'Unlockdb'                  => array( 'DBKilidiAç', 'VeritabanıKilidiAç' ),
	'Unusedcategories'          => array( 'KullanılmayanKategoriler' ),
	'Unusedimages'              => array( 'KullanılmayanDosyalar' ),
	'Unusedtemplates'           => array( 'KullanılmayanŞablonlar' ),
	'Unwatchedpages'            => array( 'İzlenmeyenSayfalar' ),
	'Upload'                    => array( 'Yükle' ),
	'UploadStash'               => array( 'ZulaYükle', 'ZulaYükleme' ),
	'Userlogin'                 => array( 'KullanıcıOturumuAçma', 'KullanıcıGiriş' ),
	'Userlogout'                => array( 'KullanıcıOturumuKapatma', 'KullanıcıÇıkış' ),
	'Userrights'                => array( 'KullanıcıHakları' ),
	'Version'                   => array( 'Sürüm', 'Versiyon' ),
	'Wantedcategories'          => array( 'İstenenKategoriler' ),
	'Wantedfiles'               => array( 'İstenenDosyalar' ),
	'Wantedpages'               => array( 'İstenenSayfalar' ),
	'Wantedtemplates'           => array( 'İstenenŞablonlar' ),
	'Watchlist'                 => array( 'İzlemeListesi' ),
	'Whatlinkshere'             => array( 'SayfayaBağlantılar' ),
	'Withoutinterwiki'          => array( 'İntervikisiz' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#YÖNLENDİRME', '#YÖNLENDİR', '#REDIRECT' ),
	'notoc'                     => array( '0', '__İÇİNDEKİLERYOK__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__GALERİYOK__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__İÇİNDEKİLERZORUNLU__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__İÇİNDEKİLER__', '__TOC__' ),
	'noeditsection'             => array( '0', '__DEĞİŞTİRYOK__', '__DÜZENLEMEYOK__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MEVCUTAY', 'MEVCUTAY2', 'GÜNCELAY', 'GÜNCELAY2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'MEVCUTAY1', 'GÜNCELAY1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'MEVCUTAYADI', 'GÜNCELAYADI', 'CURRENTMONTHNAME' ),
	'currentmonthnamegen'       => array( '1', 'MEVCUTAYADIİYELİK', 'GÜNCELAYADIİYELİK', 'CURRENTMONTHNAMEGEN' ),
	'currentmonthabbrev'        => array( '1', 'MEVCUTAYKISALTMASI', 'GÜNCELAYKISALTMASI', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'MEVCUTGÜN', 'GÜNCELGÜN', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'MEVCUTGÜN2', 'GÜNCELGÜN2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'MEVCUTGÜNADI', 'GÜNCELGÜNADI', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'MEVCUTYIL', 'GÜNCELYIL', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'MEVCUTZAMAN', 'GÜNCELZAMAN', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'MEVCUTSAAT', 'GÜNCELSAAT', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'YERELAY', 'YERELAY2', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'YERELAY1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'YERELAYADI', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'YERELAYADIİYELİK', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'YERELAYKISALTMASI', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'YERELGÜN', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'YERELGÜN2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'YERELGÜNADI', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'YERELYIL', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'YERELZAMAN', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'YERELSAAT', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'SAYFASAYISI', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'MADDESAYISI', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'DOSYASAYISI', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'KULLANICISAYISI', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'AKTİFKULLANICISAYISI', 'ETKİNKULLANICISAYISI', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'DEĞİŞİKLİKSAYISI', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'SAYFAADI', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'SAYFAADIU', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'ADALANI', 'İSİMALANI', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'ADALANIU', 'İSİMALANIU', 'NAMESPACEE' ),
	'namespacenumber'           => array( '1', 'ADALANINUMARASI', 'NAMESPACENUMBER' ),
	'talkspace'                 => array( '1', 'TARTIŞMAALANI', 'TARTIŞMABOŞLUĞU', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'TARTIŞMAALANIU', 'TARTIŞMABOŞLUĞUU', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'KONUALANI', 'MADDEALANI', 'KONUBOŞLUĞU', 'MADDEBOŞLUĞU', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'KONUALANIU', 'MADDEALANIU', 'KONUBOŞLUĞUU', 'MADDEBOŞLUĞUU', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'TAMSAYFAADI', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'TAMSAYFAADIU', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'ALTSAYFAADI', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'ALTSAYFAADIU', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'ÜSTSAYFAADI', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'ÜSTSAYFAADIU', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'TARTIŞMASAYFASIADI', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'TARTIŞMASAYFASIADIU', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'KONUSAYFASIADI', 'MADDESAYFASIADI', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'KONUSAYFASIADIU', 'MADDESAYFASIADIU', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'msg'                       => array( '0', 'MSJ:', 'İLT:', 'MSG:' ),
	'subst'                     => array( '0', 'YK:', 'YERİNEKOY:', 'KOPYALA:', 'AKTAR:', 'YAPIŞTIR:', 'SUBST:' ),
	'safesubst'                 => array( '0', 'GÜVENLİYERİNEKOY:', 'GÜVENLİKOPYALA:', 'GÜVENLİAKTAR:', 'GÜVENLİYAPIŞTIR:', 'SAFESUBST:' ),
	'msgnw'                     => array( '0', 'MSJYN:', 'İLTYN:', 'MSGNW:' ),
	'img_thumbnail'             => array( '1', 'küçükresim', 'küçük', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'küçükresim=$1', 'küçük=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'sağ', 'right' ),
	'img_left'                  => array( '1', 'sol', 'left' ),
	'img_none'                  => array( '1', 'yok', 'none' ),
	'img_width'                 => array( '1', '$1pik', '$1piksel', '$1px' ),
	'img_center'                => array( '1', 'orta', 'center', 'centre' ),
	'img_framed'                => array( '1', 'çerçeveli', 'çerçeve', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'çerçevesiz', 'frameless' ),
	'img_page'                  => array( '1', 'sayfa=$1', 'sayfa $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'dikey', 'dikey=$1', 'dikey $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'sınır', 'border' ),
	'img_baseline'              => array( '1', 'tabançizgisi', 'altçizgi', 'baseline' ),
	'img_sub'                   => array( '1', 'alt', 'sub' ),
	'img_super'                 => array( '1', 'üst', 'üs', 'super', 'sup' ),
	'img_top'                   => array( '1', 'tavan', 'tepe', 'top' ),
	'img_text_top'              => array( '1', 'metin-tavan', 'metin-tepe', 'text-top' ),
	'img_middle'                => array( '1', 'merkez', 'middle' ),
	'img_bottom'                => array( '1', 'taban', 'bottom' ),
	'img_text_bottom'           => array( '1', 'metin-taban', 'text-bottom' ),
	'img_link'                  => array( '1', 'bağlantı=$1', 'link=$1' ),
	'img_class'                 => array( '1', 'sınıf=$1', 'class=$1' ),
	'int'                       => array( '0', 'İNT:', 'INT:' ),
	'sitename'                  => array( '1', 'SİTEADI', 'SITENAME' ),
	'ns'                        => array( '0', 'AA:', 'AB:', 'NS:' ),
	'nse'                       => array( '0', 'AAU:', 'ABU:', 'NSE:' ),
	'localurl'                  => array( '0', 'YERELURL:', 'LOCALURL:' ),
	'localurle'                 => array( '0', 'YERELURLU:', 'LOCALURLE:' ),
	'articlepath'               => array( '0', 'MADDEYOLU', 'ARTICLEPATH' ),
	'pageid'                    => array( '0', 'SAYFANO', 'PAGEID' ),
	'server'                    => array( '0', 'SUNUCU', 'SERVER' ),
	'servername'                => array( '0', 'SUNUCUADI', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'BETİKYOLU', 'SCRIPTPATH' ),
	'stylepath'                 => array( '0', 'BİÇEMYOLU', 'STYLEPATH' ),
	'grammar'                   => array( '0', 'DİLBİLGİSİ:', 'GRAMER:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'CİNSİYET:', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__BAŞLIKDÖNÜŞÜMÜYOK__', '__BDY__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__İÇERİKDÖNÜŞÜMÜYOK__', '__İDY__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'MEVCUTHAFTA', 'GÜNCELHAFTA', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'MEVCUTHAFTANINGÜNÜ', 'GÜNCELHAFTANINGÜNÜ', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'YERELHAFTA', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'YERELHAFTANINGÜNÜ', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'SÜRÜMNU', 'SÜRÜMNO', 'REVİZYONNU', 'REVİZYONNO', 'REVISIONID' ),
	'revisionday'               => array( '1', 'SÜRÜMGÜNÜ', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'SÜRÜMGÜNÜ2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'SÜRÜMAYI', 'REVISIONMONTH' ),
	'revisionmonth1'            => array( '1', 'SÜRÜMAYI1', 'REVISIONMONTH1' ),
	'revisionyear'              => array( '1', 'SÜRÜMYILI', 'REVISIONYEAR' ),
	'revisiontimestamp'         => array( '1', 'SÜRÜMZAMANBİLGİSİ', 'REVISIONTIMESTAMP' ),
	'revisionuser'              => array( '1', 'SÜRÜMKULLANICI', 'REVISIONUSER' ),
	'plural'                    => array( '0', 'ÇOĞUL:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'TAMURL:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'TAMURLU:', 'FULLURLE:' ),
	'canonicalurl'              => array( '0', 'KURALLIURL:', 'CANONICALURL:' ),
	'canonicalurle'             => array( '0', 'KURALLIURLU:', 'CANONICALURLE:' ),
	'lcfirst'                   => array( '0', 'KHİLK:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'BHİLK:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'KH:', 'LC:' ),
	'uc'                        => array( '0', 'BH:', 'UC:' ),
	'raw'                       => array( '0', 'HAM:', 'RAW:' ),
	'displaytitle'              => array( '1', 'BAŞLIKGÖSTER', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__YENİBAŞLIKBAĞLANTISI__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__YENİBAŞLIKBAĞLANTISIYOK__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'MEVCUTSÜRÜM', 'GÜNCELSÜRÜM', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'URLKODLAMA:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'ÇENGELKODLAMA:', 'ANCHORENCODE' ),
	'currenttimestamp'          => array( '1', 'MEVCUTZAMANBİLGİSİ', 'GÜNCELZAMANBİLGİSİ', 'CURRENTTIMESTAMP' ),
	'localtimestamp'            => array( '1', 'YERELZAMANBİLGİSİ', 'LOCALTIMESTAMP' ),
	'directionmark'             => array( '1', 'YÖNİŞARETİ:', 'DIRECTIONMARK', 'DIRMARK' ),
	'language'                  => array( '0', '#DİL:', '#LİSAN:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'İÇERİKDİLİ', 'İÇERİKLİSANI', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'İSİMALANINDAKİSAYFALAR', 'İADAKİSAYFALAR', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'HİZMETLİSAYISI', 'NUMBEROFADMINS' ),
	'formatnum'                 => array( '0', 'BİÇİMNUM', 'FORMATNUM' ),
	'padleft'                   => array( '0', 'DOLSOL', 'PADLEFT' ),
	'padright'                  => array( '0', 'DOLSAĞ', 'PADRIGHT' ),
	'special'                   => array( '0', 'özel', 'special' ),
	'speciale'                  => array( '0', 'özelu', 'speciale' ),
	'defaultsort'               => array( '1', 'VARSAYILANSIRALA:', 'VARSAYILANSIRALAMAANAHTARI:', 'VARSAYILANKATEGORİSIRALA:', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'DOSYAYOLU:', 'DOSYA_YOLU:', 'FILEPATH:' ),
	'tag'                       => array( '0', 'etiket', 'tag' ),
	'hiddencat'                 => array( '1', '__GİZLİKAT__', '__GİZLİKATEGORİ__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'KATEGORİDEKİSAYFALAR', 'KATTAKİSAYFALAR', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'SAYFABOYUTU', 'PAGESIZE' ),
	'index'                     => array( '1', '__DİZİN__', '__ENDEKS__', '__INDEX__' ),
	'noindex'                   => array( '1', '__DİZİNYOK__', '__ENDEKSYOK__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'GRUPTAKİSAYI', 'GRUBUNSAYISI', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__STATİKYÖNLENDİRME__', '__SABİTYÖNLENDİRME__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'KORUMASEVİYESİ', 'PROTECTIONLEVEL' ),
	'formatdate'                => array( '0', 'biçimtarih', 'tarihbiçimi', 'formattarihi', 'tarihformatı', 'formatdate', 'dateformat' ),
	'url_path'                  => array( '0', 'YOL', 'PATH' ),
	'url_wiki'                  => array( '0', 'VİKİ', 'WIKI' ),
	'url_query'                 => array( '0', 'SORGU', 'QUERY' ),
	'defaultsort_noerror'       => array( '0', 'hatayok', 'hatasız', 'noerror' ),
	'pagesincategory_all'       => array( '0', 'tüm', 'all' ),
	'pagesincategory_pages'     => array( '0', 'sayfalar', 'pages' ),
	'pagesincategory_subcats'   => array( '0', 'altkategoriler', 'subcats' ),
	'pagesincategory_files'     => array( '0', 'dosyalar', 'files' ),
);

$separatorTransformTable = array( ',' => '.', '.' => ',' );
$linkTrail = '/^([a-zÇĞçğİıÖöŞşÜüÂâÎîÛû]+)(.*)$/sDu';

