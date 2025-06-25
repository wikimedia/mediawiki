<?php
/** Polish (polski)
 *
 * @file
 * @ingroup Languages
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
 * @author Msz2001
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
 * @author Rail
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

/** @phpcs-require-sorted-array */
$specialPageAliases = [
	'Activeusers'                   => [ 'Aktywni_użytkownicy' ],
	'Allmessages'                   => [ 'Wszystkie_komunikaty' ],
	'AllMyUploads'                  => [ 'Przesłane_przeze_mnie_pliki' ],
	'Allpages'                      => [ 'Wszystkie_strony' ],
	'Ancientpages'                  => [ 'Stare_strony' ],
	'ApiHelp'                       => [ 'Pomoc_API' ],
	'ApiSandbox'                    => [ 'Środowisko_testowe_API' ],
	'AuthenticationPopupSuccess'    => [ 'Pomyślnie_zalogowano_w_popupie' ],
	'AutoblockList'                 => [ 'Zablokowani_automatycznie', 'Automatycznie_zablokowani' ],
	'Badtitle'                      => [ 'Zły_tytuł' ],
	'Blankpage'                     => [ 'Pusta_strona' ],
	'Block'                         => [ 'Blokuj' ],
	'BlockList'                     => [ 'Zablokowani' ],
	'Booksources'                   => [ 'Książki' ],
	'BotPasswords'                  => [ 'Hasła_botów' ],
	'BrokenRedirects'               => [ 'Zerwane_przekierowania' ],
	'Categories'                    => [ 'Kategorie' ],
	'ChangeContentModel'            => [ 'Zmiana_modelu_zawartości', 'Zmień_model_zawartości' ],
	'ChangeCredentials'             => [ 'Zmiana_poświadczeń', 'Zmień_poświadczenia' ],
	'ChangeEmail'                   => [ 'Zmień_e-mail' ],
	'ChangePassword'                => [ 'Zmień_hasło', 'Resetuj_hasło' ],
	'ComparePages'                  => [ 'Porównywanie_stron' ],
	'Confirmemail'                  => [ 'Potwierdź_e-mail' ],
	'Contribute'                    => [ 'Współtwórz' ],
	'Contributions'                 => [ 'Wkład' ],
	'CreateAccount'                 => [ 'Utwórz_konto', 'Stwórz_konto' ],
	'Deadendpages'                  => [ 'Bez_linków' ],
	'DeletedContributions'          => [ 'Usunięty_wkład' ],
	'DeletePage'                    => [ 'Usuń_stronę' ],
	'Diff'                          => [ 'Różnica' ],
	'DoubleRedirects'               => [ 'Podwójne_przekierowania' ],
	'EditPage'                      => [ 'Edytuj_stronę', 'Edytuj' ],
	'EditRecovery'                  => [ 'Odzyskiwanie_edycji' ],
	'EditTags'                      => [ 'Edytuj_znaczniki' ],
	'EditWatchlist'                 => [ 'Edytuj_obserwowane' ],
	'Emailuser'                     => [ 'E-mail' ],
	'ExpandTemplates'               => [ 'Rozwijanie_szablonów' ],
	'Export'                        => [ 'Eksport' ],
	'Fewestrevisions'               => [ 'Najmniej_edycji' ],
	'FileDuplicateSearch'           => [ 'Szukaj_duplikatu_pliku' ],
	'Filepath'                      => [ 'Ścieżka_do_pliku' ],
	'GoToInterwiki'                 => [ 'Przejdź_do_interwiki' ],
	'Import'                        => [ 'Import' ],
	'Invalidateemail'               => [ 'Anuluj_e-mail' ],
	'JavaScriptTest'                => [ 'Test_JavaScriptu' ],
	'LinkAccounts'                  => [ 'Przyłącz_konta' ],
	'LinkSearch'                    => [ 'Wyszukiwarka_linków' ],
	'Listadmins'                    => [ 'Administratorzy' ],
	'Listbots'                      => [ 'Boty' ],
	'ListDuplicatedFiles'           => [ 'Zduplikowane_pliki' ],
	'Listfiles'                     => [ 'Pliki' ],
	'Listgrants'                    => [ 'Dostępy_użytkowników' ],
	'Listgrouprights'               => [ 'Grupy_użytkowników', 'Uprawnienia_grup_użytkowników' ],
	'Listredirects'                 => [ 'Przekierowania' ],
	'Listusers'                     => [ 'Użytkownicy' ],
	'Lockdb'                        => [ 'Zablokuj_bazę' ],
	'Log'                           => [ 'Rejestr', 'Logi' ],
	'Lonelypages'                   => [ 'Porzucone_strony' ],
	'Longpages'                     => [ 'Najdłuższe_strony' ],
	'MediaStatistics'               => [ 'Statystyki_mediów' ],
	'MergeHistory'                  => [ 'Połącz_historie' ],
	'MIMEsearch'                    => [ 'Wyszukiwanie_MIME' ],
	'Mostcategories'                => [ 'Najwięcej_kategorii' ],
	'Mostimages'                    => [ 'Najczęściej_linkowane_pliki' ],
	'Mostinterwikis'                => [ 'Najwięcej_interwiki' ],
	'Mostlinked'                    => [ 'Najczęściej_linkowane' ],
	'Mostlinkedcategories'          => [ 'Najczęściej_linkowane_kategorie' ],
	'Mostlinkedtemplates'           => [ 'Najczęściej_linkowane_szablony' ],
	'Mostrevisions'                 => [ 'Najwięcej_edycji', 'Najczęściej_edytowane' ],
	'Movepage'                      => [ 'Przenieś' ],
	'Mute'                          => [ 'Ignoruj' ],
	'Mycontributions'               => [ 'Mój_wkład' ],
	'MyLanguage'                    => [ 'Mój_język' ],
	'Mylog'                         => [ 'Mój_rejestr', 'Mój_log' ],
	'Mypage'                        => [ 'Moja_strona' ],
	'Mytalk'                        => [ 'Moja_dyskusja' ],
	'Myuploads'                     => [ 'Moje_pliki' ],
	'NamespaceInfo'                 => [ 'Przestrzenie_nazw' ],
	'Newimages'                     => [ 'Nowe_pliki' ],
	'Newpages'                      => [ 'Nowe_strony' ],
	'NewSection'                    => [ 'Nowa_sekcja' ],
	'PageData'                      => [ 'Dane_ze_strony' ],
	'PageHistory'                   => [ 'Historia_strony', 'Historia' ],
	'PageInfo'                      => [ 'Informacje_o_stronie', 'Informacje' ],
	'PageLanguage'                  => [ 'Język_strony' ],
	'PagesWithProp'                 => [ 'Strony_z_własnością' ],
	'PasswordPolicies'              => [ 'Zasady_haseł' ],
	'PasswordReset'                 => [ 'Wyczyść_hasło' ],
	'PermanentLink'                 => [ 'Niezmienny_link' ],
	'Preferences'                   => [ 'Preferencje' ],
	'Prefixindex'                   => [ 'Strony_według_prefiksu' ],
	'Protectedpages'                => [ 'Zabezpieczone_strony' ],
	'Protectedtitles'               => [ 'Zabezpieczone_nazwy_stron' ],
	'ProtectPage'                   => [ 'Zabezpiecz_stronę', 'Zabezpiecz' ],
	'Purge'                         => [ 'Odśwież' ],
	'RandomInCategory'              => [ 'Losowa_w_kategorii', 'Losowa_strona_w_kategorii' ],
	'Randompage'                    => [ 'Losowa_strona', 'Losowa' ],
	'Randomredirect'                => [ 'Losowe_przekierowanie' ],
	'Randomrootpage'                => [ 'Losowa_bez_podstron' ],
	'Recentchanges'                 => [ 'Ostatnie_zmiany', 'OZ' ],
	'Recentchangeslinked'           => [ 'Zmiany_w_linkowanych', 'Zmiany_w_linkujących' ],
	'Redirect'                      => [ 'Przekieruj' ],
	'RemoveCredentials'             => [ 'Usuwanie_poświadczeń', 'Usuń_poświadczenia' ],
	'Renameuser'                    => [ 'Zmiana_nazwy_użytkownika' ],
	'ResetTokens'                   => [ 'Resetuj_tokeny' ],
	'Revisiondelete'                => [ 'Usuń_wersję' ],
	'RunJobs'                       => [ 'Uruchom_zadania' ],
	'Search'                        => [ 'Szukaj' ],
	'Shortpages'                    => [ 'Najkrótsze_strony' ],
	'Specialpages'                  => [ 'Strony_specjalne' ],
	'Statistics'                    => [ 'Statystyka', 'Statystyki' ],
	'Tags'                          => [ 'Znaczniki' ],
	'TalkPage'                      => [ 'Dyskusja' ],
	'TrackingCategories'            => [ 'Kategorie_śledzące' ],
	'Unblock'                       => [ 'Odblokuj' ],
	'Uncategorizedcategories'       => [ 'Nieskategoryzowane_kategorie' ],
	'Uncategorizedimages'           => [ 'Nieskategoryzowane_pliki' ],
	'Uncategorizedpages'            => [ 'Nieskategoryzowane_strony' ],
	'Uncategorizedtemplates'        => [ 'Nieskategoryzowane_szablony' ],
	'Undelete'                      => [ 'Odtwórz' ],
	'UnlinkAccounts'                => [ 'Rozłącz_konta' ],
	'Unlockdb'                      => [ 'Odblokuj_bazę' ],
	'Unusedcategories'              => [ 'Nieużywane_kategorie' ],
	'Unusedimages'                  => [ 'Nieużywane_pliki' ],
	'Unusedtemplates'               => [ 'Nieużywane_szablony' ],
	'Unwatchedpages'                => [ 'Nieobserwowane_strony' ],
	'Upload'                        => [ 'Prześlij' ],
	'UploadStash'                   => [ 'Schowane_pliki' ],
	'Userlogin'                     => [ 'Zaloguj' ],
	'Userlogout'                    => [ 'Wyloguj' ],
	'Userrights'                    => [ 'Uprawnienia', 'Uprawnienia_użytkowników', 'Prawa_użytkowników' ],
	'Version'                       => [ 'Wersja' ],
	'Wantedcategories'              => [ 'Potrzebne_kategorie' ],
	'Wantedfiles'                   => [ 'Potrzebne_pliki' ],
	'Wantedpages'                   => [ 'Potrzebne_strony' ],
	'Wantedtemplates'               => [ 'Potrzebne_szablony' ],
	'Watchlist'                     => [ 'Obserwowane' ],
	'Whatlinkshere'                 => [ 'Linkujące' ],
	'Withoutinterwiki'              => [ 'Strony_bez_interwiki' ],
];

/** @phpcs-require-sorted-array */
$magicWords = [
	'articlepath'               => [ '0', 'ŚCIEŻKAARTYKUŁÓW', 'ARTICLEPATH' ],
	'basepagename'              => [ '1', 'BAZOWANAZWASTRONY', 'BASEPAGENAME' ],
	'currentday'                => [ '1', 'AKTUALNYDZIEŃ', 'CURRENTDAY' ],
	'currentdayname'            => [ '1', 'NAZWADNIA', 'CURRENTDAYNAME' ],
	'currenthour'               => [ '1', 'AKTUALNAGODZINA', 'CURRENTHOUR' ],
	'currenttime'               => [ '1', 'AKTUALNYCZAS', 'CURRENTTIME' ],
	'currentversion'            => [ '1', 'AKTUALNAWERSJA', 'CURRENTVERSION' ],
	'currentweek'               => [ '1', 'AKTUALNYTYDZIEŃ', 'CURRENTWEEK' ],
	'currentyear'               => [ '1', 'AKTUALNYROK', 'CURRENTYEAR' ],
	'defaultsort'               => [ '1', 'SORTUJ', 'DOMYŚLNIESORTUJ', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'displaytitle'              => [ '1', 'WYŚWIETLANYTYTUŁ', 'DISPLAYTITLE' ],
	'filepath'                  => [ '0', 'ŚCIEŻKAPLIKU', 'FILEPATH:' ],
	'forcetoc'                  => [ '0', '__ZESPISEM__', '__WYMUŚSPIS__', '__FORCETOC__' ],
	'fullpagename'              => [ '1', 'PELNANAZWASTRONY', 'FULLPAGENAME' ],
	'fullurl'                   => [ '0', 'PEŁNYURL', 'FULLURL:' ],
	'gender'                    => [ '0', 'PŁEĆ:', 'GENDER:' ],
	'grammar'                   => [ '0', 'ODMIANA:', 'GRAMMAR:' ],
	'hiddencat'                 => [ '1', '__KATEGORIAUKRYTA__', '__HIDDENCAT__' ],
	'img_border'                => [ '1', 'tło', 'border' ],
	'img_bottom'                => [ '1', 'dół', 'bottom' ],
	'img_center'                => [ '1', 'centruj', 'center', 'centre' ],
	'img_framed'                => [ '1', 'ramka', 'frame', 'framed', 'enframed' ],
	'img_frameless'             => [ '1', 'bezramki', 'bez_ramki', 'frameless' ],
	'img_left'                  => [ '1', 'lewo', 'left' ],
	'img_manualthumb'           => [ '1', 'mały=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_middle'                => [ '1', 'środek', 'middle' ],
	'img_none'                  => [ '1', 'brak', 'none' ],
	'img_page'                  => [ '1', 'strona=$1', 'page=$1', 'page $1' ],
	'img_right'                 => [ '1', 'prawo', 'right' ],
	'img_thumbnail'             => [ '1', 'mały', 'thumb', 'thumbnail' ],
	'img_top'                   => [ '1', 'góra', 'top' ],
	'img_upright'               => [ '1', 'skala', 'skala=$1', 'skala $1', 'skaluj', 'skaluj=$1', 'skaluj $1', 'upright', 'upright=$1', 'upright $1' ],
	'index'                     => [ '1', '__INDEKSUJ__', '__INDEX__' ],
	'language'                  => [ '0', '#JĘZYK', '#LANGUAGE' ],
	'lc'                        => [ '0', 'MAŁE:', 'LC:' ],
	'lcfirst'                   => [ '0', 'ZMAŁEJ:', 'ODMAŁEJ:', 'LCFIRST:' ],
	'localday'                  => [ '1', 'DZIEŃ', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DZIEŃ2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'DZIEŃTYGODNIA', 'LOCALDAYNAME' ],
	'localdow'                  => [ '1', 'DZIEŃTYGODNIANR', 'LOCALDOW' ],
	'localhour'                 => [ '1', 'GODZINA', 'LOCALHOUR' ],
	'localmonth'                => [ '1', 'MIESIĄC', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonthabbrev'          => [ '1', 'MIESIĄCNAZWASKR', 'LOCALMONTHABBREV' ],
	'localmonthname'            => [ '1', 'MIESIĄCNAZWA', 'LOCALMONTHNAME' ],
	'localmonthnamegen'         => [ '1', 'MIESIĄCNAZWAD', 'LOCALMONTHNAMEGEN' ],
	'localtime'                 => [ '1', 'CZAS', 'LOCALTIME' ],
	'localweek'                 => [ '1', 'TYDZIEŃROKU', 'LOCALWEEK' ],
	'localyear'                 => [ '1', 'ROK', 'LOCALYEAR' ],
	'namespace'                 => [ '1', 'NAZWAPRZESTRZENI', 'NAMESPACE' ],
	'newsectionlink'            => [ '1', '__LINKNOWEJSEKCJI__', '__NEWSECTIONLINK__' ],
	'noeditsection'             => [ '0', '__BEZEDYCJISEKCJI__', '__NOEDITSECTION__' ],
	'nogallery'                 => [ '0', '__BEZGALERII__', '__NOGALLERY__' ],
	'noindex'                   => [ '1', '__NIEINDEKSUJ__', '__NOINDEX__' ],
	'notoc'                     => [ '0', '__BEZSPISU__', '__NOTOC__' ],
	'ns'                        => [ '0', 'PN:', 'NS:' ],
	'numberofactiveusers'       => [ '1', 'LICZBAAKTYWNYCHUŻYTKOWNIKÓW', 'NUMBEROFACTIVEUSERS' ],
	'numberofadmins'            => [ '1', 'ADMINISTRATORÓW', 'NUMBEROFADMINS' ],
	'numberofarticles'          => [ '1', 'ARTYKUŁÓW', 'NUMBEROFARTICLES' ],
	'numberofedits'             => [ '1', 'EDYCJI', 'NUMBEROFEDITS' ],
	'numberoffiles'             => [ '1', 'PLIKÓW', 'NUMBEROFFILES' ],
	'numberofpages'             => [ '1', 'STRON', 'NUMBEROFPAGES' ],
	'numberofusers'             => [ '1', 'UŻYTKOWNIKÓW', 'NUMBEROFUSERS' ],
	'padleft'                   => [ '0', 'DOLEWEJ', 'PADLEFT' ],
	'padright'                  => [ '0', 'DOPRAWEJ', 'PADRIGHT' ],
	'pagename'                  => [ '1', 'NAZWASTRONY', 'PAGENAME' ],
	'pagesincategory'           => [ '1', 'STRONYWKATEGORII', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesincategory_files'     => [ '0', 'pliki', 'files' ],
	'pagesincategory_pages'     => [ '0', 'strony', 'pages' ],
	'pagesize'                  => [ '1', 'ROZMIARSTRONY', 'PAGESIZE' ],
	'plural'                    => [ '0', 'MNOGA:', 'PLURAL:' ],
	'protectionlevel'           => [ '1', '__POZIOMZABEZPIECZEŃ__', 'PROTECTIONLEVEL' ],
	'redirect'                  => [ '0', '#PATRZ', '#PRZEKIERUJ', '#TAM', '#REDIRECT' ],
	'scriptpath'                => [ '0', 'ŚCIEŻKASKRYPTU', 'SCRIPTPATH' ],
	'server'                    => [ '0', 'SERWER', 'SERVER' ],
	'servername'                => [ '0', 'NAZWASERWERA', 'SERVERNAME' ],
	'sitename'                  => [ '1', 'PROJEKT', 'SITENAME' ],
	'special'                   => [ '0', 'specjalna', 'special' ],
	'subpagename'               => [ '1', 'NAZWAPODSTRONY', 'SUBPAGENAME' ],
	'subst'                     => [ '0', 'podst:', 'SUBST:' ],
	'talkpagename'              => [ '1', 'NAZWASTRONYDYSKUSJI', 'TALKPAGENAME' ],
	'talkspace'                 => [ '1', 'DYSKUSJA', 'TALKSPACE' ],
	'toc'                       => [ '0', '__SPIS__', '__TOC__' ],
	'uc'                        => [ '0', 'WIELKIE:', 'DUŻE:', 'UC:' ],
	'ucfirst'                   => [ '0', 'ZWIELKIEJ:', 'ZDUŻEJ:', 'ODWIELKIEJ:', 'ODDUŻEJ:', 'UCFIRST:' ],
	'url_path'                  => [ '0', 'ŚCIEŻKA', 'PATH' ],
	'url_query'                 => [ '0', 'ZAPYTANIE', 'QUERY' ],
];
