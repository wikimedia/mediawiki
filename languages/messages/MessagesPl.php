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

$namespaceNames = [
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
];

$namespaceAliases = [
	'Grafika' => NS_FILE,
	'Dyskusja_grafiki' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Użytkownik', 'female' => 'Użytkowniczka' ],
	NS_USER_TALK => [ 'male' => 'Dyskusja_użytkownika', 'female' => 'Dyskusja_użytkowniczki' ],
];

$dateFormats = [
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
];

$fallback8bitEncoding = 'iso-8859-2';

$separatorTransformTable = [
	',' => "\u{00A0}", // T4749
	'.' => ','
];
$minimumGroupingDigits = 2;

$linkTrail = '/^([a-zęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+)(.*)$/sDu';

$specialPageAliases = [
	'Activeusers'               => [ 'Aktywni_użytkownicy' ],
	'Allmessages'               => [ 'Wszystkie_komunikaty' ],
	'Allpages'                  => [ 'Wszystkie_strony' ],
	'Ancientpages'              => [ 'Stare_strony' ],
	'Badtitle'                  => [ 'Zły_tytuł' ],
	'Blankpage'                 => [ 'Pusta_strona' ],
	'Block'                     => [ 'Blokuj' ],
	'Booksources'               => [ 'Książki' ],
	'BrokenRedirects'           => [ 'Zerwane_przekierowania' ],
	'Categories'                => [ 'Kategorie' ],
	'ChangeEmail'               => [ 'Zmień_e-mail' ],
	'ChangePassword'            => [ 'Zmień_hasło', 'Resetuj_hasło' ],
	'ComparePages'              => [ 'Porównywanie_stron' ],
	'Confirmemail'              => [ 'Potwierdź_e-mail' ],
	'Contributions'             => [ 'Wkład' ],
	'CreateAccount'             => [ 'Utwórz_konto', 'Stwórz_konto' ],
	'Deadendpages'              => [ 'Bez_linków' ],
	'DeletedContributions'      => [ 'Usunięty_wkład' ],
	'DoubleRedirects'           => [ 'Podwójne_przekierowania' ],
	'EditWatchlist'             => [ 'Edytuj_obserwowane' ],
	'Emailuser'                 => [ 'E-mail' ],
	'ExpandTemplates'           => [ 'Rozwijanie_szablonów' ],
	'Export'                    => [ 'Eksport' ],
	'Fewestrevisions'           => [ 'Najmniej_edycji' ],
	'FileDuplicateSearch'       => [ 'Szukaj_duplikatu_pliku' ],
	'Filepath'                  => [ 'Ścieżka_do_pliku' ],
	'Invalidateemail'           => [ 'Anuluj_e-mail' ],
	'JavaScriptTest'            => [ 'Test_JavaScriptu' ],
	'BlockList'                 => [ 'Zablokowani' ],
	'LinkSearch'                => [ 'Wyszukiwarka_linków' ],
	'Listadmins'                => [ 'Administratorzy' ],
	'Listbots'                  => [ 'Boty' ],
	'Listfiles'                 => [ 'Pliki' ],
	'Listgrouprights'           => [ 'Grupy_użytkowników', 'Uprawnienia_grup_użytkowników' ],
	'Listredirects'             => [ 'Przekierowania' ],
	'Listusers'                 => [ 'Użytkownicy' ],
	'Lockdb'                    => [ 'Zablokuj_bazę' ],
	'Log'                       => [ 'Rejestr', 'Logi' ],
	'Lonelypages'               => [ 'Porzucone_strony' ],
	'Longpages'                 => [ 'Najdłuższe_strony' ],
	'MergeHistory'              => [ 'Połącz_historie' ],
	'MIMEsearch'                => [ 'Wyszukiwanie_MIME' ],
	'Mostcategories'            => [ 'Najwięcej_kategorii' ],
	'Mostimages'                => [ 'Najczęściej_linkowane_pliki' ],
	'Mostinterwikis'            => [ 'Najwięcej_interwiki' ],
	'Mostlinked'                => [ 'Najczęściej_linkowane' ],
	'Mostlinkedcategories'      => [ 'Najczęściej_linkowane_kategorie' ],
	'Mostlinkedtemplates'       => [ 'Najczęściej_linkowane_szablony' ],
	'Mostrevisions'             => [ 'Najwięcej_edycji', 'Najczęściej_edytowane' ],
	'Movepage'                  => [ 'Przenieś' ],
	'Mycontributions'           => [ 'Mój_wkład' ],
	'MyLanguage'                => [ 'Mój_język' ],
	'Mypage'                    => [ 'Moja_strona' ],
	'Mytalk'                    => [ 'Moja_dyskusja' ],
	'Myuploads'                 => [ 'Moje_pliki' ],
	'Newimages'                 => [ 'Nowe_pliki' ],
	'Newpages'                  => [ 'Nowe_strony' ],
	'PagesWithProp'             => [ 'Strony_z_własnością' ],
	'PasswordReset'             => [ 'Wyczyść_hasło' ],
	'PermanentLink'             => [ 'Niezmienny_link' ],
	'Preferences'               => [ 'Preferencje' ],
	'Prefixindex'               => [ 'Strony_według_prefiksu' ],
	'Protectedpages'            => [ 'Zabezpieczone_strony' ],
	'Protectedtitles'           => [ 'Zabezpieczone_nazwy_stron' ],
	'Randompage'                => [ 'Losowa_strona', 'Losowa' ],
	'RandomInCategory'          => [ 'Losowa_w_kategorii', 'Losowa_strona_w_kategorii' ],
	'Randomredirect'            => [ 'Losowe_przekierowanie' ],
	'Recentchanges'             => [ 'Ostatnie_zmiany', 'OZ' ],
	'Recentchangeslinked'       => [ 'Zmiany_w_linkowanych', 'Zmiany_w_linkujących' ],
	'Redirect'                  => [ 'Przekieruj' ],
	'ResetTokens'               => [ 'Resetuj_tokeny' ],
	'Revisiondelete'            => [ 'Usuń_wersję' ],
	'Search'                    => [ 'Szukaj' ],
	'Shortpages'                => [ 'Najkrótsze_strony' ],
	'Specialpages'              => [ 'Strony_specjalne' ],
	'Statistics'                => [ 'Statystyka', 'Statystyki' ],
	'Tags'                      => [ 'Znaczniki' ],
	'Unblock'                   => [ 'Odblokuj' ],
	'Uncategorizedcategories'   => [ 'Nieskategoryzowane_kategorie' ],
	'Uncategorizedimages'       => [ 'Nieskategoryzowane_pliki' ],
	'Uncategorizedpages'        => [ 'Nieskategoryzowane_strony' ],
	'Uncategorizedtemplates'    => [ 'Nieskategoryzowane_szablony' ],
	'Undelete'                  => [ 'Odtwórz' ],
	'Unlockdb'                  => [ 'Odblokuj_bazę' ],
	'Unusedcategories'          => [ 'Nieużywane_kategorie' ],
	'Unusedimages'              => [ 'Nieużywane_pliki' ],
	'Unusedtemplates'           => [ 'Nieużywane_szablony' ],
	'Unwatchedpages'            => [ 'Nieobserwowane_strony' ],
	'Upload'                    => [ 'Prześlij' ],
	'UploadStash'               => [ 'Schowane_pliki' ],
	'Userlogin'                 => [ 'Zaloguj' ],
	'Userlogout'                => [ 'Wyloguj' ],
	'Userrights'                => [ 'Uprawnienia', 'Uprawnienia_użytkowników', 'Prawa_użytkowników' ],
	'Version'                   => [ 'Wersja' ],
	'Wantedcategories'          => [ 'Potrzebne_kategorie' ],
	'Wantedfiles'               => [ 'Potrzebne_pliki' ],
	'Wantedpages'               => [ 'Potrzebne_strony' ],
	'Wantedtemplates'           => [ 'Potrzebne_szablony' ],
	'Watchlist'                 => [ 'Obserwowane' ],
	'Whatlinkshere'             => [ 'Linkujące' ],
	'Withoutinterwiki'          => [ 'Strony_bez_interwiki' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#PATRZ', '#PRZEKIERUJ', '#TAM', '#REDIRECT' ],
	'notoc'                     => [ '0', '__BEZSPISU__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__BEZGALERII__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__ZESPISEM__', '__WYMUŚSPIS__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__SPIS__', '__TOC__' ],
	'noeditsection'             => [ '0', '__BEZEDYCJISEKCJI__', '__NOEDITSECTION__' ],
	'currentday'                => [ '1', 'AKTUALNYDZIEŃ', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'NAZWADNIA', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'AKTUALNYROK', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'AKTUALNYCZAS', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'AKTUALNAGODZINA', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MIESIĄC', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthname'            => [ '1', 'MIESIĄCNAZWA', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'MIESIĄCNAZWAD', 'LOCALMONTHNAMEGEN' ],
	'localmonthabbrev'          => [ '1', 'MIESIĄCNAZWASKR', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'DZIEŃ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DZIEŃ2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'DZIEŃTYGODNIA', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ROK', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'CZAS', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'GODZINA', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'STRON', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'ARTYKUŁÓW', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'PLIKÓW', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'UŻYTKOWNIKÓW', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'LICZBAAKTYWNYCHUŻYTKOWNIKÓW', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'EDYCJI', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'NAZWASTRONY', 'PAGENAME' ],
	'namespace'                 => [ '1', 'NAZWAPRZESTRZENI', 'NAMESPACE' ],
	'talkspace'                 => [ '1', 'DYSKUSJA', 'TALKSPACE' ],
	'fullpagename'              => [ '1', 'PELNANAZWASTRONY', 'FULLPAGENAME' ],
	'subpagename'               => [ '1', 'NAZWAPODSTRONY', 'SUBPAGENAME' ],
	'basepagename'              => [ '1', 'BAZOWANAZWASTRONY', 'BASEPAGENAME' ],
	'talkpagename'              => [ '1', 'NAZWASTRONYDYSKUSJI', 'TALKPAGENAME' ],
	'subst'                     => [ '0', 'podst:', 'SUBST:' ],
	'img_thumbnail'             => [ '1', 'mały', 'thumb', 'thumbnail' ],
	'img_manualthumb'           => [ '1', 'mały=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'prawo', 'right' ],
	'img_left'                  => [ '1', 'lewo', 'left' ],
	'img_none'                  => [ '1', 'brak', 'none' ],
	'img_center'                => [ '1', 'centruj', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ramka', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'bezramki', 'bez_ramki', 'frameless' ],
	'img_page'                  => [ '1', 'strona=$1', 'page=$1', 'page $1' ],
	'img_border'                => [ '1', 'tło', 'border' ],
	'img_top'                   => [ '1', 'góra', 'top' ],
	'img_middle'                => [ '1', 'środek', 'middle' ],
	'img_bottom'                => [ '1', 'dół', 'bottom' ],
	'sitename'                  => [ '1', 'PROJEKT', 'SITENAME' ],
	'ns'                        => [ '0', 'PN:', 'NS:' ],
	'articlepath'               => [ '0', 'ŚCIEŻKAARTYKUŁÓW', 'ARTICLEPATH' ],
	'server'                    => [ '0', 'SERWER', 'SERVER' ],
	'servername'                => [ '0', 'NAZWASERWERA', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'ŚCIEŻKASKRYPTU', 'SCRIPTPATH' ],
	'grammar'                   => [ '0', 'ODMIANA:', 'GRAMMAR:' ],
	'gender'                    => [ '0', 'PŁEĆ:', 'GENDER:' ],
	'currentweek'               => [ '1', 'AKTUALNYTYDZIEŃ', 'CURRENTWEEK' ],
	'localweek'                 => [ '1', 'TYDZIEŃROKU', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'DZIEŃTYGODNIANR', 'LOCALDOW' ],
	'plural'                    => [ '0', 'MNOGA:', 'PLURAL:' ],
	'fullurl'                   => [ '0', 'PEŁNYURL', 'FULLURL:' ],
	'lcfirst'                   => [ '0', 'ZMAŁEJ:', 'ODMAŁEJ:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'ZWIELKIEJ:', 'ZDUŻEJ:', 'ODWIELKIEJ:', 'ODDUŻEJ:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'MAŁE:', 'LC:' ],
	'uc'                        => [ '0', 'WIELKIE:', 'DUŻE:', 'UC:' ],
	'displaytitle'              => [ '1', 'WYŚWIETLANYTYTUŁ', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__LINKNOWEJSEKCJI__', '__NEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'AKTUALNAWERSJA', 'CURRENTVERSION' ],
	'language'                  => [ '0', '#JĘZYK:', '#LANGUAGE:' ],
	'numberofadmins'            => [ '1', 'ADMINISTRATORÓW', 'NUMBEROFADMINS' ],
	'padleft'                   => [ '0', 'DOLEWEJ', 'PADLEFT' ],
	'padright'                  => [ '0', 'DOPRAWEJ', 'PADRIGHT' ],
	'special'                   => [ '0', 'specjalna', 'special' ],
	'defaultsort'               => [ '1', 'SORTUJ', 'DOMYŚLNIESORTUJ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'ŚCIEŻKAPLIKU', 'FILEPATH:' ],
	'hiddencat'                 => [ '1', '__KATEGORIAUKRYTA__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'STRONYWKATEGORII', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'ROZMIARSTRONY', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEKSUJ__', '__INDEX__' ],
	'noindex'                   => [ '1', '__NIEINDEKSUJ__', '__NOINDEX__' ],
	'protectionlevel'           => [ '1', '__POZIOMZABEZPIECZEŃ__', 'PROTECTIONLEVEL' ],
	'url_path'                  => [ '0', 'ŚCIEŻKA', 'PATH' ],
	'url_query'                 => [ '0', 'ZAPYTANIE', 'QUERY' ],
	'pagesincategory_pages'     => [ '0', 'strony', 'pages' ],
	'pagesincategory_files'     => [ '0', 'pliki', 'files' ],
];
