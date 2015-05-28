<?php
/** Polish (polski)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Ankry
 * @author Bartek50003
 * @author BdgwksxD
 * @author Beau
 * @author BeginaFelicysym
 * @author Chrumps
 * @author Clamira
 * @author Cysioland
 * @author Dalis
 * @author Debeet
 * @author Derbeth
 * @author Equadus
 * @author Fizykaa
 * @author Geitost
 * @author Herr Kriss
 * @author Holek
 * @author Jacenty359
 * @author Jwitos
 * @author Kaganer
 * @author Kaligula
 * @author Karol007
 * @author Lajsikonik
 * @author Lampak
 * @author Lazowik
 * @author Leinad
 * @author Maikking
 * @author Marcin Łukasz Kiejzik
 * @author Masti
 * @author Matma Rex
 * @author McMonster
 * @author Mikołka
 * @author Nux
 * @author Odder
 * @author Odie2
 * @author Olgak85
 * @author Peter Bowman
 * @author Pio387
 * @author Przemub
 * @author Reedy
 * @author Remedios44
 * @author Remember the dot
 * @author Rezonansowy
 * @author Rzuwig
 * @author Saper
 * @author Sovq
 * @author Sp5uhe
 * @author Stanko
 * @author Stlmch
 * @author Stv
 * @author Szczepan1990
 * @author Tar Lócesilion
 * @author Timpul
 * @author ToSter
 * @author Tsca
 * @author Ty221
 * @author WTM
 * @author Woytecr
 * @author Wpedzich
 * @author Ymar
 * @author Žekřil71pl
 * @author לערי ריינהארט
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Specjalna',
	NS_TALK             => 'Dyskusja',
	NS_USER             => 'Użytkownik',
	NS_USER_TALK        => 'Dyskusja_użytkownika',
	NS_PROJECT_TALK     => 'Dyskusja_$1',
	NS_FILE             => 'Plik',
	NS_FILE_TALK        => 'Dyskusja_pliku',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Dyskusja_MediaWiki',
	NS_TEMPLATE         => 'Szablon',
	NS_TEMPLATE_TALK    => 'Dyskusja_szablonu',
	NS_HELP             => 'Pomoc',
	NS_HELP_TALK        => 'Dyskusja_pomocy',
	NS_CATEGORY         => 'Kategoria',
	NS_CATEGORY_TALK    => 'Dyskusja_kategorii',
);

$namespaceAliases = array(
	'Grafika' => NS_FILE,
	'Dyskusja_grafiki' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER => array( 'male' => 'Użytkownik', 'female' => 'Użytkowniczka' ),
	NS_USER_TALK => array( 'male' => 'Dyskusja_użytkownika', 'female' => 'Dyskusja_użytkowniczki' ),
);

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy monthonly' => 'F Y',
	'mdy both' => 'H:i, M j, Y',
	'mdy pretty' => 'j xg',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy monthonly' => 'F Y',
	'dmy both' => 'H:i, j M Y',
	'dmy pretty' => 'j xg',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd monthonly' => 'Y F',
	'ymd both' => 'H:i, Y M j',
	'ymd pretty' => 'j xg',
);

$fallback8bitEncoding = 'iso-8859-2';
$separatorTransformTable = array(
	',' => "\xc2\xa0", // @bug 2749
	'.' => ','
);

$linkTrail = '/^([a-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+)(.*)$/sDu';

$specialPageAliases = array(
	'Activeusers'               => array( 'Aktywni_użytkownicy' ),
	'Allmessages'               => array( 'Wszystkie_komunikaty' ),
	'Allpages'                  => array( 'Wszystkie_strony' ),
	'Ancientpages'              => array( 'Stare_strony' ),
	'Badtitle'                  => array( 'Zły_tytuł' ),
	'Blankpage'                 => array( 'Pusta_strona' ),
	'Block'                     => array( 'Blokuj' ),
	'Booksources'               => array( 'Książki' ),
	'BrokenRedirects'           => array( 'Zerwane_przekierowania' ),
	'Categories'                => array( 'Kategorie' ),
	'ChangeEmail'               => array( 'Zmień_e-mail' ),
	'ChangePassword'            => array( 'Zmień_hasło', 'Resetuj_hasło' ),
	'ComparePages'              => array( 'Porównywanie_stron' ),
	'Confirmemail'              => array( 'Potwierdź_e-mail' ),
	'Contributions'             => array( 'Wkład' ),
	'CreateAccount'             => array( 'Utwórz_konto', 'Stwórz_konto' ),
	'Deadendpages'              => array( 'Bez_linków' ),
	'DeletedContributions'      => array( 'Usunięty_wkład' ),
	'DoubleRedirects'           => array( 'Podwójne_przekierowania' ),
	'EditWatchlist'             => array( 'Edytuj_obserwowane' ),
	'Emailuser'                 => array( 'E-mail' ),
	'ExpandTemplates'           => array( 'Rozwijanie_szablonów' ),
	'Export'                    => array( 'Eksport' ),
	'Fewestrevisions'           => array( 'Najmniej_edycji' ),
	'FileDuplicateSearch'       => array( 'Szukaj_duplikatu_pliku' ),
	'Filepath'                  => array( 'Ścieżka_do_pliku' ),
	'Invalidateemail'           => array( 'Anuluj_e-mail' ),
	'JavaScriptTest'            => array( 'Test_JavaScriptu' ),
	'BlockList'                 => array( 'Zablokowani' ),
	'LinkSearch'                => array( 'Wyszukiwarka_linków' ),
	'Listadmins'                => array( 'Administratorzy' ),
	'Listbots'                  => array( 'Boty' ),
	'Listfiles'                 => array( 'Pliki' ),
	'Listgrouprights'           => array( 'Grupy_użytkowników', 'Uprawnienia_grup_użytkowników' ),
	'Listredirects'             => array( 'Przekierowania' ),
	'Listusers'                 => array( 'Użytkownicy' ),
	'Lockdb'                    => array( 'Zablokuj_bazę' ),
	'Log'                       => array( 'Rejestr', 'Logi' ),
	'Lonelypages'               => array( 'Porzucone_strony' ),
	'Longpages'                 => array( 'Najdłuższe_strony' ),
	'MergeHistory'              => array( 'Połącz_historie' ),
	'MIMEsearch'                => array( 'Wyszukiwanie_MIME' ),
	'Mostcategories'            => array( 'Najwięcej_kategorii' ),
	'Mostimages'                => array( 'Najczęściej_linkowane_pliki' ),
	'Mostinterwikis'            => array( 'Najwięcej_interwiki' ),
	'Mostlinked'                => array( 'Najczęściej_linkowane' ),
	'Mostlinkedcategories'      => array( 'Najczęściej_linkowane_kategorie' ),
	'Mostlinkedtemplates'       => array( 'Najczęściej_linkowane_szablony' ),
	'Mostrevisions'             => array( 'Najwięcej_edycji', 'Najczęściej_edytowane' ),
	'Movepage'                  => array( 'Przenieś' ),
	'Mycontributions'           => array( 'Mój_wkład' ),
	'MyLanguage'                => array( 'Mój_język' ),
	'Mypage'                    => array( 'Moja_strona' ),
	'Mytalk'                    => array( 'Moja_dyskusja' ),
	'Myuploads'                 => array( 'Moje_pliki' ),
	'Newimages'                 => array( 'Nowe_pliki' ),
	'Newpages'                  => array( 'Nowe_strony' ),
	'PagesWithProp'             => array( 'Strony_z_własnością' ),
	'PasswordReset'             => array( 'Wyczyść_hasło' ),
	'PermanentLink'             => array( 'Niezmienny_link' ),

	'Preferences'               => array( 'Preferencje' ),
	'Prefixindex'               => array( 'Strony_według_prefiksu' ),
	'Protectedpages'            => array( 'Zabezpieczone_strony' ),
	'Protectedtitles'           => array( 'Zabezpieczone_nazwy_stron' ),
	'Randompage'                => array( 'Losowa_strona', 'Losowa' ),
	'RandomInCategory'          => array( 'Losowa_w_kategorii', 'Losowa_strona_w_kategorii' ),
	'Randomredirect'            => array( 'Losowe_przekierowanie' ),
	'Recentchanges'             => array( 'Ostatnie_zmiany', 'OZ' ),
	'Recentchangeslinked'       => array( 'Zmiany_w_linkowanych', 'Zmiany_w_linkujących' ),
	'Redirect'                  => array( 'Przekieruj' ),
	'ResetTokens'               => array( 'Resetuj_tokeny' ),
	'Revisiondelete'            => array( 'Usuń_wersję' ),
	'Search'                    => array( 'Szukaj' ),
	'Shortpages'                => array( 'Najkrótsze_strony' ),
	'Specialpages'              => array( 'Strony_specjalne' ),
	'Statistics'                => array( 'Statystyka', 'Statystyki' ),
	'Tags'                      => array( 'Znaczniki' ),
	'Unblock'                   => array( 'Odblokuj' ),
	'Uncategorizedcategories'   => array( 'Nieskategoryzowane_kategorie' ),
	'Uncategorizedimages'       => array( 'Nieskategoryzowane_pliki' ),
	'Uncategorizedpages'        => array( 'Nieskategoryzowane_strony' ),
	'Uncategorizedtemplates'    => array( 'Nieskategoryzowane_szablony' ),
	'Undelete'                  => array( 'Odtwórz' ),
	'Unlockdb'                  => array( 'Odblokuj_bazę' ),
	'Unusedcategories'          => array( 'Nieużywane_kategorie' ),
	'Unusedimages'              => array( 'Nieużywane_pliki' ),
	'Unusedtemplates'           => array( 'Nieużywane_szablony' ),
	'Unwatchedpages'            => array( 'Nieobserwowane_strony' ),
	'Upload'                    => array( 'Prześlij' ),
	'UploadStash'               => array( 'Schowane_pliki' ),
	'Userlogin'                 => array( 'Zaloguj' ),
	'Userlogout'                => array( 'Wyloguj' ),
	'Userrights'                => array( 'Uprawnienia', 'Uprawnienia_użytkowników', 'Prawa_użytkowników' ),
	'Version'                   => array( 'Wersja' ),
	'Wantedcategories'          => array( 'Potrzebne_kategorie' ),
	'Wantedfiles'               => array( 'Potrzebne_pliki' ),
	'Wantedpages'               => array( 'Potrzebne_strony' ),
	'Wantedtemplates'           => array( 'Potrzebne_szablony' ),
	'Watchlist'                 => array( 'Obserwowane' ),
	'Whatlinkshere'             => array( 'Linkujące' ),
	'Withoutinterwiki'          => array( 'Strony_bez_interwiki' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#PATRZ', '#PRZEKIERUJ', '#TAM', '#REDIRECT' ),
	'notoc'                     => array( '0', '__BEZSPISU__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__BEZGALERII__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__ZESPISEM__', '__WYMUŚSPIS__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__SPIS__', '__TOC__' ),
	'noeditsection'             => array( '0', '__BEZEDYCJISEKCJI__', '__NOEDITSECTION__' ),
	'currentday'                => array( '1', 'AKTUALNYDZIEŃ', 'CURRENTDAY' ),
	'currentdayname'            => array( '1', 'NAZWADNIA', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'AKTUALNYROK', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'AKTUALNYCZAS', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'AKTUALNAGODZINA', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MIESIĄC', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'            => array( '1', 'MIESIĄCNAZWA', 'LOCALMONTHNAME' ),
	'localmonthnamegen'         => array( '1', 'MIESIĄCNAZWAD', 'LOCALMONTHNAMEGEN' ),
	'localmonthabbrev'          => array( '1', 'MIESIĄCNAZWASKR', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'DZIEŃ', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'DZIEŃ2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'DZIEŃTYGODNIA', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ROK', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'CZAS', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'GODZINA', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'STRON', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'ARTYKUŁÓW', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'PLIKÓW', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'UŻYTKOWNIKÓW', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'LICZBAAKTYWNYCHUŻYTKOWNIKÓW', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'EDYCJI', 'NUMBEROFEDITS' ),
	'pagename'                  => array( '1', 'NAZWASTRONY', 'PAGENAME' ),
	'namespace'                 => array( '1', 'NAZWAPRZESTRZENI', 'NAMESPACE' ),
	'talkspace'                 => array( '1', 'DYSKUSJA', 'TALKSPACE' ),
	'fullpagename'              => array( '1', 'PELNANAZWASTRONY', 'FULLPAGENAME' ),
	'subpagename'               => array( '1', 'NAZWAPODSTRONY', 'SUBPAGENAME' ),
	'basepagename'              => array( '1', 'BAZOWANAZWASTRONY', 'BASEPAGENAME' ),
	'talkpagename'              => array( '1', 'NAZWASTRONYDYSKUSJI', 'TALKPAGENAME' ),
	'subst'                     => array( '0', 'podst:', 'SUBST:' ),
	'img_thumbnail'             => array( '1', 'mały', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'mały=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'prawo', 'right' ),
	'img_left'                  => array( '1', 'lewo', 'left' ),
	'img_none'                  => array( '1', 'brak', 'none' ),
	'img_center'                => array( '1', 'centruj', 'center', 'centre' ),
	'img_framed'                => array( '1', 'ramka', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'bezramki', 'bez_ramki', 'frameless' ),
	'img_page'                  => array( '1', 'strona=$1', 'page=$1', 'page $1' ),
	'img_border'                => array( '1', 'tło', 'border' ),
	'img_top'                   => array( '1', 'góra', 'top' ),
	'img_middle'                => array( '1', 'środek', 'middle' ),
	'img_bottom'                => array( '1', 'dół', 'bottom' ),
	'sitename'                  => array( '1', 'PROJEKT', 'SITENAME' ),
	'ns'                        => array( '0', 'PN:', 'NS:' ),
	'articlepath'               => array( '0', 'ŚCIEŻKAARTYKUŁÓW', 'ARTICLEPATH' ),
	'server'                    => array( '0', 'SERWER', 'SERVER' ),
	'servername'                => array( '0', 'NAZWASERWERA', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'ŚCIEŻKASKRYPTU', 'SCRIPTPATH' ),
	'grammar'                   => array( '0', 'ODMIANA:', 'GRAMMAR:' ),
	'gender'                    => array( '0', 'PŁEĆ:', 'GENDER:' ),
	'currentweek'               => array( '1', 'AKTUALNYTYDZIEŃ', 'CURRENTWEEK' ),
	'localweek'                 => array( '1', 'TYDZIEŃROKU', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'DZIEŃTYGODNIANR', 'LOCALDOW' ),
	'plural'                    => array( '0', 'MNOGA:', 'PLURAL:' ),
	'fullurl'                   => array( '0', 'PEŁNYURL', 'FULLURL:' ),
	'lcfirst'                   => array( '0', 'ZMAŁEJ:', 'ODMAŁEJ:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'ZWIELKIEJ:', 'ZDUŻEJ:', 'ODWIELKIEJ:', 'ODDUŻEJ:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'MAŁE:', 'LC:' ),
	'uc'                        => array( '0', 'WIELKIE:', 'DUŻE:', 'UC:' ),
	'displaytitle'              => array( '1', 'WYŚWIETLANYTYTUŁ', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__LINKNOWEJSEKCJI__', '__NEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'AKTUALNAWERSJA', 'CURRENTVERSION' ),
	'language'                  => array( '0', '#JĘZYK:', '#LANGUAGE:' ),
	'numberofadmins'            => array( '1', 'ADMINISTRATORÓW', 'NUMBEROFADMINS' ),
	'padleft'                   => array( '0', 'DOLEWEJ', 'PADLEFT' ),
	'padright'                  => array( '0', 'DOPRAWEJ', 'PADRIGHT' ),
	'special'                   => array( '0', 'specjalna', 'special' ),
	'defaultsort'               => array( '1', 'SORTUJ', 'DOMYŚLNIESORTUJ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'ŚCIEŻKAPLIKU', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__KATEGORIAUKRYTA__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'STRONYWKATEGORII', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'ROZMIARSTRONY', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEKSUJ__', '__INDEX__' ),
	'noindex'                   => array( '1', '__NIEINDEKSUJ__', '__NOINDEX__' ),
	'protectionlevel'           => array( '1', '__POZIOMZABEZPIECZEŃ__', 'PROTECTIONLEVEL' ),
	'url_path'                  => array( '0', 'ŚCIEŻKA', 'PATH' ),
	'url_query'                 => array( '0', 'ZAPYTANIE', 'QUERY' ),
	'pagesincategory_pages'     => array( '0', 'strony', 'pages' ),
	'pagesincategory_files'     => array( '0', 'pliki', 'files' ),
);

