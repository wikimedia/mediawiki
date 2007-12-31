<?php
/** Lower Sorbian (Dolnoserbski)
 *
 * @addtogroup Language
 *
 * @author Pe7er
 * @author Qualia
 * @author Nepl1
 * @author Dunak
 * @author Murjarik
 * @author Tlustulimu Nepl1
 * @author Dundak
 * @author Michawiki
 * @author Nike
 * @author Siebrand
 * @author Tlustulimu
 * @author SPQRobin
 */

$fallback = 'de';

$datePreferences = array(
	'default',
	'dmy',
	'ISO 8601',
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Wótkaze pódšmarnuś:',
'tog-highlightbroken'         => 'Wótkaze na njeeksistěrujuce boki formatěrowaś',
'tog-justify'                 => 'Tekst do bloka zrownaś',
'tog-hideminor'               => 'Małe změny schowaś',
'tog-extendwatchlist'         => 'Rozšyrjona wobglědowańska lisćina',
'tog-usenewrc'                => 'Rozšyrjona lisćina aktualnych změnow (JavaScript trěbny)',
'tog-numberheadings'          => 'Nadpisma awtomatiski numerěrowaś',
'tog-showtoolbar'             => 'Wobźěłańsku lejstwu pokazaś (JavaScript)',
'tog-editondblclick'          => 'Boki z dwójnym kliknjenim wobźěłaś (JavaScript)',
'tog-editsection'             => 'Wobźěłanje wótstawkow pśez wótkaze [wobźěłaś] zmóžniś',
'tog-editsectiononrightclick' => 'Wobźěłanje wótstawkow pśez kliknjenje z pšaweju tastu myški zmóžniś (JavaScript)',
'tog-showtoc'                 => 'Wopśimjeśe pokazaś, jolic ma bok wěcej nježli 3 nadpisma',
'tog-rememberpassword'        => 'Se stawnje na toś tom computerje pśizjawiś',
'tog-editwidth'               => 'Zapódaśowe tekstowe pólo na połnu šyrokosć stajiś',
'tog-watchcreations'          => 'Boki, kótarež załožyjom, awtomatiski wobglědowaś',
'tog-watchdefault'            => 'Boki, kótarež změnijom, awtomatiski wobglědowaś',
'tog-watchmoves'              => 'Boki, kótarež som pśesunuł, awtomatiski wobglědowaś',
'tog-watchdeletion'           => 'Boki, kótarež som wulašował, awtomatiski wobglědowaś',
'tog-minordefault'            => 'Wšykne móje změny ako małe markěrowaś',
'tog-previewontop'            => 'Zespominanje wušej wobźěłowańskego póla pokazaś',
'tog-previewonfirst'          => 'Pśi prědnem wobźěłanju pśecej zespominanje pokazaś',
'tog-nocache'                 => 'Cache bokow znjemóžniś',
'tog-enotifwatchlistpages'    => 'E-mail pósłaś, změniju-lic se wobglědowane boki',
'tog-enotifusertalkpages'     => 'E-mail pósłaś, změnijo-lic se mój diskusijny bok',
'tog-enotifminoredits'        => 'E-mail teke małych změnow dla pósłaś',
'tog-enotifrevealaddr'        => 'Móju e-mailowu adresu w e-mailowych pówěźeńkach pokazaś',
'tog-shownumberswatching'     => 'Licbu wobglědujucych wužywarjow pokazaś',
'tog-fancysig'                => 'Signaturu mimo awtomatiskego wótkaza na diskusijny bok',
'tog-externaleditor'          => 'Eksterny editor ako standard wužywaś',
'tog-externaldiff'            => 'Eksterny diff-program ako standard wužywaś',
'tog-showjumplinks'           => 'Wótkaze typa „źi do” zmóžniś',
'tog-uselivepreview'          => 'Live-pśeglěd wužywaś (JavaScript) (eksperimentelnje)',
'tog-forceeditsummary'        => 'Warnowaś, gaž pśi składowanju zespominanje felujo',
'tog-watchlisthideown'        => 'Móje změny na wobglědowańskej lisćinje schowaś',
'tog-watchlisthidebots'       => 'Změny awtomatiskich programow (botow) na wobglědowańskej lisćinje schowaś',
'tog-watchlisthideminor'      => 'Małe změny na wobglědowańskej lisćinje schowaś',
'tog-nolangconversion'        => 'Konwertěrowanje rěcnych wariantow znjemóžniś',
'tog-ccmeonemails'            => 'Kopije e-mailow dostaś, kótarež drugim wužywarjam pósćelom',
'tog-diffonly'                => 'Pśi pśirownowanju wersijow jano rozdźěle pokazaś',

'underline-always'  => 'pśecej',
'underline-never'   => 'žednje',
'underline-default' => 'pó standarźe browsera',

'skinpreview' => '(Pśeglěd)',

# Dates
'sunday'        => 'Njeźela',
'monday'        => 'Pónjeźele',
'tuesday'       => 'Wałtora',
'wednesday'     => 'Srjoda',
'thursday'      => 'Stwórtk',
'friday'        => 'Pětk',
'saturday'      => 'Sobota',
'sun'           => 'Nje',
'mon'           => 'Pón',
'tue'           => 'Wał',
'wed'           => 'Srj',
'thu'           => 'Stw',
'fri'           => 'Pět',
'sat'           => 'Sob',
'january'       => 'januar',
'february'      => 'februar',
'march'         => 'měrc',
'april'         => 'apryl',
'may_long'      => 'maj',
'june'          => 'junij',
'july'          => ' julij',
'august'        => 'awgust',
'september'     => 'september',
'october'       => 'oktober',
'november'      => 'nowember',
'december'      => 'december',
'january-gen'   => 'januara',
'february-gen'  => 'februara',
'march-gen'     => 'měrca',
'april-gen'     => 'apryla',
'may-gen'       => 'maja',
'june-gen'      => 'junija',
'july-gen'      => 'julija',
'august-gen'    => 'awgusta',
'september-gen' => 'septembra',
'october-gen'   => 'oktobra',
'november-gen'  => 'nowembra',
'december-gen'  => 'decembra',
'jan'           => 'jan',
'feb'           => 'feb',
'mar'           => 'měr',
'apr'           => 'apr',
'may'           => 'maja',
'jun'           => 'jun',
'jul'           => 'jul',
'aug'           => 'awg',
'sep'           => 'sep',
'oct'           => 'okt',
'nov'           => 'now',
'dec'           => 'dec',

# Bits of text used by many pages
'categories'            => 'Kategorije',
'pagecategories'        => '{{PLURAL:$1|Kategorija|Kategoriji|Kategorije}}',
'category_header'       => 'Nastawki w kategoriji „$1“',
'subcategories'         => 'Pódkategorije',
'category-media-header' => 'Dataje w kategoriji „$1“',
'category-empty'        => "''W toś tej kategoriji njejsu něnto žedne nastawki abo medije.''",

'mainpagetext'      => "<big>'''MediaWiki jo se wuspěšnje instalěrowało.'''</big>",
'mainpagedocfooter' => "Pomoc pśi wužywanju softwary wiki namakajoš pód [http://meta.wikimedia.org/wiki/Help:Contents User's Guide].

== Na zachopjenje ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Konfiguracija lisćiny połoženjow]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ (pšašanja a wótegrona)]
* [http://list.wikimedia.org/mailman/listinfo/mediawiki-announce Lisćina e-mailowych nakładow MediaWiki]",

'about'          => 'Wó',
'article'        => 'Nastawk',
'newwindow'      => '(se wótcynijo w nowem woknje)',
'cancel'         => 'Pśetergnuś',
'qbfind'         => 'Namakaś',
'qbbrowse'       => 'Pśeběraś',
'qbedit'         => 'Pśeměniś',
'qbpageoptions'  => 'Toś ten bok',
'qbpageinfo'     => 'Kontekst',
'qbmyoptions'    => 'Móje boki',
'qbspecialpages' => 'Specialne boki',
'moredotdotdot'  => 'Wěcej…',
'mypage'         => 'Mój bok',
'mytalk'         => 'mója diskusija',
'anontalk'       => 'Diskusija z toś teju IP',
'navigation'     => 'Nawigacija',

# Metadata in edit box
'metadata_help' => 'Metadaty:',

'errorpagetitle'    => 'Zmólka',
'returnto'          => 'Slědk k boku $1.',
'tagline'           => 'Z {{GRAMMAR:genitiw|{{SITENAME}}}}',
'help'              => 'Pomoc',
'search'            => 'Pytaś',
'searchbutton'      => 'Pytaś',
'go'                => 'Nastawk',
'searcharticle'     => 'Nastawk',
'history'           => 'wersije',
'history_short'     => 'Wersije a awtory',
'updatedmarker'     => 'Změny wót mójogo slědnego woglěda',
'info_short'        => 'Informacija',
'printableversion'  => 'Wersija za śišć',
'permalink'         => 'Wobstawny wótkaz',
'print'             => 'Śišćaś',
'edit'              => 'wobźěłaś',
'editthispage'      => 'Bok wobźěłaś',
'delete'            => 'Wulašowaś',
'deletethispage'    => 'Toś ten bok wulašowaś',
'undelete_short'    => '{{PLURAL:$1|1 wersiju|$1 wersiji|$1 wersije}} nawrośiś.',
'protect'           => 'Šćitaś',
'protect_change'    => 'Šćitanje pśeměniś',
'protectthispage'   => 'Bok šćitaś',
'unprotect'         => 'Šćitanje wótpóraś',
'unprotectthispage' => 'Šćitanje wótpóraś',
'newpage'           => 'Nowy bok',
'talkpage'          => 'Diskusija',
'talkpagelinktext'  => 'diskusija',
'specialpage'       => 'Specialny bok',
'personaltools'     => 'Wósobinske pomocne srědki',
'postcomment'       => 'Komentar pśidaś',
'articlepage'       => 'Nastawk',
'talk'              => 'Diskusija',
'views'             => 'Naglědy',
'toolbox'           => 'Pomocne srědki',
'userpage'          => 'Wužywaŕski bok pokazaś',
'projectpage'       => 'Projektowy bok pokazaś',
'imagepage'         => 'Wobrazowy bok pokazaś',
'mediawikipage'     => 'Nastawk pokazaś',
'templatepage'      => 'Šablonu pokazaś',
'viewhelppage'      => 'Pomocny bok pokazaś',
'categorypage'      => 'Kategoriju pokazaś',
'viewtalkpage'      => 'Diskusija',
'otherlanguages'    => 'W drugich rěcach',
'redirectedfrom'    => '(pósrědnjone z boka „$1”)',
'redirectpagesub'   => 'Dalejpósrědnjenje',
'lastmodifiedat'    => 'Slědna změna boka: $1 w $2 goź.', # $1 date, $2 time
'viewcount'         => 'Toś ten bok jo był woglědany {{PLURAL:$1|jaden raz|$1 raza|$1 raze}}.',
'protectedpage'     => 'Śćitany bok',
'jumpto'            => 'Źi na bok:',
'jumptonavigation'  => 'Nawigacija',
'jumptosearch'      => 'Pytaś',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'Wó {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'aboutpage'         => 'Project:Wó_{{GRAMMAR:lokatiw|{{SITENAME}}}}',
'bugreports'        => 'Raporty wó zmólkach',
'bugreportspage'    => 'Project:Raporty wó zmólkach',
'copyright'         => 'Wopśimjeśe stoj pód $1.',
'copyrightpagename' => '{{SITENAME}} stwóriśelske pšawo',
'copyrightpage'     => '{{ns:project}}:Stwóriśelske pšawo',
'currentevents'     => 'Aktualne tšojenja',
'currentevents-url' => 'Project:Aktualne tšojenja',
'disclaimers'       => 'Impresum',
'disclaimerpage'    => 'Project:impresum',
'edithelp'          => 'Pomoc pśi wobźěłanju',
'edithelppage'      => 'Help:Pomoc pśi wobźěłanju',
'faq'               => 'FAQ (pšašanja a wótegrona)',
'faqpage'           => 'Project:FAQ (pšašanja a wótegrona)',
'helppage'          => 'Help:Pomoc',
'mainpage'          => 'Głowny bok',
'policy-url'        => 'Project:Směrnice',
'portal'            => 'Portal {{GRAMMAR:genitiw|{{SITENAME}}}}',
'portal-url'        => 'Project:portal',
'privacy'           => 'Šćit datow',
'privacypage'       => 'Project:Šćit datow',
'sitesupport'       => 'Dary',
'sitesupport-url'   => 'Project:Dary',

'badaccess'        => 'Njamaš trěbnu dowólnosć.',
'badaccess-group0' => 'Njamaš trěbnu dowólnosć za toś tu akciju.',
'badaccess-group1' => 'Jano wužywarje kupki $1 maju pšawo toś tu akciju wuwjasć.',
'badaccess-group2' => 'Jano wužywarje kupkow(u) $1 maju pšawo toś tu akciju wuwjasć.',
'badaccess-groups' => 'Jano wužywarje kupkow(u) $1 maju pšawo toś tu akciju wuwjasć.',

'versionrequired'     => 'Wersija $1 softwary MediaWiki trěbna',
'versionrequiredtext' => 'Wersija $1 softwary MediaWiki jo trěbna, aby toś ten bok se mógał wužywaś. Glědaj [[{{ns:special}}:Version]]',

'ok'                      => 'Pytaś',
'retrievedfrom'           => 'Z {{GRAMMAR:genitiw|$1}}',
'youhavenewmessages'      => 'Maš $1 ($2).',
'newmessageslink'         => 'nowe powěsći',
'newmessagesdifflink'     => 'slědna změna',
'youhavenewmessagesmulti' => 'Maš nowe powěsći: $1',
'editsection'             => 'wobźěłaś',
'editold'                 => 'wobźěłaś',
'editsectionhint'         => 'Wótrězk wobźěłaś: $1',
'toc'                     => 'Wopśimjeśe',
'showtoc'                 => 'pokazaś',
'hidetoc'                 => 'schowaś',
'thisisdeleted'           => '$1 woglědaś abo wobnowiś?',
'viewdeleted'             => '$1 pokazaś?',
'restorelink'             => '{{PLURAL:$1|1 wulašowana wersija|$1 wulašowanej wersiji|$1 wulašowane wersije}}',
'feedlinks'               => 'Nowosći:',
'feed-invalid'            => 'Njepłaśecy typ abonementa.',
'site-rss-feed'           => '$1 RSS Feed',
'site-atom-feed'          => '$1 Atom Feed',
'page-rss-feed'           => '"$1" RSS Feed',
'page-atom-feed'          => '"$1" Atom Feed',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Nastawk',
'nstab-user'      => 'Wužywaŕski bok',
'nstab-media'     => 'Medije',
'nstab-special'   => 'specialny bok',
'nstab-project'   => 'Projektowy bok',
'nstab-image'     => 'Dataja',
'nstab-mediawiki' => 'Powěźeńka',
'nstab-template'  => 'Šablona',
'nstab-help'      => 'Pomoc',
'nstab-category'  => 'Kategorija',

# Main script and global functions
'nosuchaction'      => 'Toś tu akciju njedajo',
'nosuchactiontext'  => 'Akcija, kótaruž URL pódajo, se wót wikija njepódpěrujo.',
'nosuchspecialpage' => 'Toś ten specialny bok njeeksistěrujo',
'nospecialpagetext' => 'Toś ten specialny bok w toś tom wikiju njeeksistěrujo; lisćina płaśecych specialnych bokow namakajo se pód [[{{ns:special}}:Specialpages]].',

# General errors
'error'                => 'Zmólka',
'databaseerror'        => 'Zmólka w datowej bance',
'dberrortext'          => 'Syntaktiska zmólka pśi wótpšašowanju datoweje banki.
Slědne wótpšašowanje jo było: <blockquote><tt>$1</tt></blockquote> z funkcije „<tt>$2</tt>“.
MySQL jo zmólku „<tt>$3: $4</tt>“ wrośił.',
'dberrortextcl'        => 'Syntaktiska zmólka pśi wótpšašowanju datoweje banki.
Slědne wótpšašowanje jo było: <blockquote><tt>$1</tt></blockquote> z funkcije „<tt>$2</tt>“.
MySQL jo zmólku „<tt>$3: $4</tt>“ wrośił.',
'noconnect'            => 'Njejo móžno zwisk z datoweju banku natwariś. <br />
$1',
'nodb'                 => 'Njejo móžno było, datowu banku $1 wuzwóliś.',
'cachederror'          => 'Slědujuce jo kopija z pufrowaka. Jo toś móžno, až wopśimjeśe jo zestarjone.',
'laggedslavemode'      => 'Glědaj: Jo móžno, až pokazany bok nejaktualnjejše změny njewopśimjejo.',
'readonly'             => 'Datowa banka jo zacynjona',
'enterlockreason'      => 'Pšosym zapódaj pśicynu za zacynjenje datoweje banki a informaciju, ga buźo zasej pśistupna',
'readonlytext'         => 'Datowa banka jo wochylu za nowe zapiski a druge změny zacynjona, nejskerjej dla wótwardowańskich źěłow. Pšosym wopytaj pózdźej hyšći raz.

Administrator, kenž jo ju zacynił, jo pódał toś tu pśicynu: $1',
'missingarticle'       => 'Datowa banka njejo tekst boka z mjenim „$1” namakała, rowno až dejała jen pópšawem namakaś.

Take zwětšego se stawa pśez zestarjony wótkaz na wulašowany abo pśesunjony bok.

Njejo-lic tak, sy snano zmólku w softwarje namakał. Pšosym zdźěl to [[{{MediaWiki:grouppage-sysop}}|administratoroju]] a pódaj wužyty URL.',
'readonly_lag'         => 'Datowa banka jo awtomatiski se zacyniła, aby wótwisne serwery se mógli z głownym serwerom wurownowaś.',
'internalerror'        => 'Interna zmólka',
'internalerror_info'   => 'Interna zmólka: $1',
'filecopyerror'        => 'Njejo było móžno dataju „$1” k „$2” kopěrowaś.',
'filerenameerror'      => 'Njejo było móžno dataju „$1” do „$2” pśemjenjowaś.',
'filedeleteerror'      => 'Njejo było móžno dataju „$1” wulašowaś.',
'directorycreateerror' => 'Njejo było móžno, zapis „$1“ wutwóriś.',
'filenotfound'         => 'Njejo było móžno dataju „$1” namakaś.',
'fileexistserror'      => 'Njejo było móžno do dataje "$1" pisaś: Wóna južo eksistěrujo.',
'unexpected'           => 'Njewócakowana gódnota: „$1“=„$2“.',
'formerror'            => 'Zmólka: Njejo móžno formular wótpósłaś.',
'badarticleerror'      => 'Akcija njedajo se na toś tom boku wuwjasć.',
'cannotdelete'         => 'Njejo móžno wuzwólony bok abo dataju wulašowaś. Snaź jo to južo něchten drugi cynił.',
'badtitle'             => 'Njepłaśecy nadpis',
'badtitletext'         => 'Nadpis pominanego boka jo był njepłaśecy, prozny abo njekorektny nadpis, póchadajucy z mjazyrěcnego abo interwikijowego wótkaza. Snaź wopśimjejo jadno abo wěcej znamuškow, kótarež njejsu w nadpisach dowólone.',
'perfdisabled'         => "'''Wódaj!''' Toś ta funkcija jo wochylu znjemóžnjona, dokulaž su serwery datoweje banki pśeliš wobśěžowane.",
'perfcached'           => 'Toś te daty póchadaju z pufrowaka a mógu toś njeaktualne byś.',
'perfcachedts'         => 'Toś te daty póchadaju z pufrowaka, slědna aktualizacija: $1',
'querypage-no-updates' => 'Aktualizěrowanje toś togo boka jo se znjemóžniło. Daty how se nejžpjerwjej raz njeaktualizěruju.',
'wrong_wfQuery_params' => 'Njedobre parametery za wfQuery()<br />
Funkcija: $1<br />
Wótpšašanje: $2',
'viewsource'           => 'Žrědłowy tekst wobglědaś',
'viewsourcefor'        => 'za $1',
'actionthrottled'      => 'Akcije limitowane',
'protectedpagetext'    => 'Wobźěłanje toś togo boka jo se znjemóžniło.',
'viewsourcetext'       => 'Žrědłowy tekst togo boka móžoš se woglědaś a kopěrowaś:',
'protectedinterface'   => 'Toś ten bok wopśimujo tekst za rěcny zwjerch softwary. Jogo wobźěłowanje jo se znjemóžniło, aby se znjewužywanjeju zadorało.',
'editinginterface'     => "'''Warnowanje:''' Wobźěłajośo bok, kótaryž wopśimujo tekst za software MediaWiki. Změny budu w rěcnem zwjerchu softwary za drugich wužywarjow wiźeś.",
'sqlhidden'            => '(Wótpšašanje SQL schowane)',
'cascadeprotected'     => 'Za toś ten bok jo se wobźěłowanje znjemóžniło, dokulaž jo zawězany do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, {{PLURAL:$1|kótaryž jo|kótarejž stej|kótarež su}} pśez kaskadowu opciju {{PLURAL:$1|šćitany|šćitanej|šćitane}}: $2',
'namespaceprotected'   => "Njejsy wopšawnjony, boki w rumje: '''$1''' wobźěłaś.",
'customcssjsprotected' => 'Toś te boki njesmějoš wobźěłaś, dokulaž wopśimjeju wósobinske dataje drugego wužywarja.',
'ns-specialprotected'  => 'Njejo móžno, boki w rumje {{ns:special}} wobźěłaś.',

# Login and logout pages
'logouttitle'                => 'Wužywarja wótzjawiś',
'logouttext'                 => '<strong>Sy se wótzjawił.</strong><br />
Móžoš bok {{SITENAME}} anomymnje wužywaś, abo wótnowotki ze samskim abo hynakšem wužywarskim mjenim se pśizjawiś.',
'welcomecreation'            => '== Witaj, $1! ==

Twójo wužywarske konto jo se zarědowało. Njezabydni, swóje {{SITENAME}} nastajenja změniś.',
'loginpagetitle'             => 'Wužywarja pśizjawiś',
'yourname'                   => 'mě wužywarja',
'yourpassword'               => 'šćitne gronidło:',
'yourpasswordagain'          => 'Šćitne gronidło hyšći raz zapódaś:',
'remembermypassword'         => 'Šćitne gronidło na toś tom computerje składowaś',
'yourdomainname'             => 'Twója domejna',
'externaldberror'            => 'Abo jo wustupiła eksterna zmólka awtentifikacije datoweje banki, abo njesmějoš swójo eksterne wužywarske konto aktualizěrowaś.',
'loginproblem'               => "'''Problem z pśizjawjenim.'''<br />Pšosym hyšći raz wopytaś !",
'login'                      => 'Pśizjawiś se',
'loginprompt'                => 'Za pśizjawjenje do boka {{SITENAME}} muse se cookije dopušćiś.',
'userlogin'                  => 'Pśizjawiś se',
'logout'                     => 'Wótzjawiś se',
'userlogout'                 => 'Wótzjawiś se',
'notloggedin'                => 'Njepśizjawjony(a)',
'nologin'                    => 'Njamaš wužywarske konto? $1.',
'nologinlink'                => 'Nowe wužywarske konto załožyś',
'createaccount'              => 'Wužywarske konto załožyś',
'gotaccount'                 => 'Maš južo wužywarske konto? $1.',
'gotaccountlink'             => 'Pśizjawiś se',
'createaccountmail'          => 'z e-mailku',
'badretype'                  => 'Šćitnej gronidle, kótarejž sy zapódał, se njemakajotej.',
'userexists'                 => 'Toś to wužywarske mě słuša južo drugemu wužywarjeju, pšosym wuzwól se druge.',
'youremail'                  => 'E-mail:',
'username'                   => 'Wužywarske mě:',
'uid'                        => 'ID wužywarja:',
'yourrealname'               => 'Realne mě *:',
'yourlanguage'               => 'Rěc:',
'yourvariant'                => 'Warianta:',
'yournick'                   => 'Signatura:',
'badsig'                     => 'Signatura njejo dobra; pšosym HTML pśekontrolěrowaś.',
'badsiglength'               => 'Wužywarske mě smějo nejwušej $1 pismikow měś.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'Realne mě jo opcionalne. Jolic až jo zapódajośo wužywa se za pódpisanje wašych pśinoskow.',
'loginerror'                 => 'Zmólka pśi pśizjawjenju',
'prefs-help-email'           => 'E-mailowa adresa jo opcionalna. Wóna zmóžnja drugim, aby z Wami pśez Waš wužywarski abo diskusijny bok se kontaktěrowali, bźez togo, až se Waša identita wótkšyjo. Mimo togo zmóžnja pósłanje wašogo zabytego šćitnego gronidła.',
'prefs-help-email-required'  => 'E-mailowa adresa trjebna.',
'nocookiesnew'               => 'Wužywarske konto jo se južo wutwóriło, ale wužywaŕ njejo pśizjawjony. {{SITENAME}} wužywa cookije za pśizjawjenja. Jo notne, cookije zmóžniś a se wótnowotki pśizjawiś.',
'nocookieslogin'             => '{{SITENAME}} wužywa cookije za pśizjawjenja. Jo notne, cookije zmóžniś a se wótnowotki pśizjawiś.',
'noname'                     => 'Njejsy žedno płaśece wužywarske mě zapódał.',
'loginsuccesstitle'          => 'Pśizjawjenje wuspěšne',
'loginsuccess'               => "'''Sy něnto ako „$1” w {{GRAMMAR:lokatiw|{{SITENAME}}}} pśizjawjony.'''",
'nosuchuser'                 => 'Wužywarske mě „$1“ njeeksistěrujo. Pśeglěduj pšawopis abo załož nowe konto.',
'nosuchusershort'            => 'Wužywarske mě „$1“ njeeksistěrujo. Pśeglěduj pšawopis.',
'nouserspecified'            => 'Pšosym pódaj wužywarske mě.',
'wrongpassword'              => 'Zapódane šćitne gronidło njejo pšawe. Pšosym wopytaj hyšći raz.',
'wrongpasswordempty'         => 'Šćitne gronidło jo było prozne. Pšosym zapódaj jo hyšći raz.',
'passwordtooshort'           => 'Twójo šćitne gronidło njejo korektne abo pśeliž krotke. Wóno dej nanejmjenjej $1 pismikow wopśimjeś a njesmějo se z wužywarskim mjenim makaś.',
'mailmypassword'             => 'Šćitne gronidło pśipósłaś',
'passwordremindertitle'      => 'Nowe nachylne pótajmne słowo za {{SITENAME}}',
'passwordremindertext'       => 'Něchten (nejskerjej ty z adresy $1) jo se wupšosył, aby jomu se pśipósłało nowe šćitne gronidło za bok{{SITENAME}} ($4). Šćitne gronidło za wužywarja "$2" jo něnto "$3". 
Ty móžoš se něnto pśizjawiś a swójo šćitne gronidło pśeměniś. 

Jolic jo něchten drugi wó nowe šćitne gronidło pšosył, abo sy se zasej domarkował a njocoš jo změniś, móžoš toś tu powěsć ignorěrowaś a swójo stare gronidło dalej wužywaś.',
'noemail'                    => 'Wužywaŕ „$1“ njejo e-mailowu adresu zapódał.',
'passwordsent'               => 'Nowe šćitne gronidło jo se wótpósłało na e-mailowu adresu wužywarja „$1“.
Pšosym pśizjaw se zasej, gaž jo dostanjoš.',
'blocked-mailpassword'       => 'Twója IP-adresa jo se za wobźěłowanje bokow blokěrowała a teke pśipósłanje nowego šćitnego gronidła jo se znjemóžniło, aby se znjewužywanjeju zadorało.',
'eauthentsent'               => 'Wobkšuśenje jo se na e-mailowu adresu wótposłało. 

Nježli až wótpósćelo se dalšna e-mail na to wužywarske konto, dejš slědowaś instrukcije w powěsći a tak wobkšuśiś, až konto jo wót wěrnosći twójo.',
'throttled-mailpassword'     => 'W běgu {{Plural:$1|slědneje $1 góźiny|slědnjeju $1 góźinowu|slědnych $1 góźinow}} jo se južo raz wó nowe šćitne gronidło pšosyło.  Wěstoty dla wótpósćelo se jano jadno šćitne gronidło w běgu {{Plural:$1|$1 góźiny|$1 góźinowu|$1 góźinow}}.',
'mailerror'                  => 'Zmólka pśi wótpósłanju e-maila: $1',
'acct_creation_throttle_hit' => 'Wódaj, ty sy južo wutwórił {{PLURAL:$1|$1 wužywarske konto|$1 wužiwarskej konśe|$1 wužywarske konta}}. Wěcej njejo móžno.',
'emailauthenticated'         => 'Twója e-mailowa adresa jo wobkšuśona: $1.',
'emailnotauthenticated'      => 'Twója e-mailowa adresa njejo hyšći wobkšuśona. E-mailowe funkcije móžoš aklej pó wuspěšnem wobkšuśenju wužywaś.',
'noemailprefs'               => 'Zapódaj e-mailowu adresu, aby toś te funkcije aktiwizěrował.',
'emailconfirmlink'           => 'Wobkšuś swóju e-mailowu adresu.',
'invalidemailaddress'        => 'Toś ta e-mailowa adresa njamóžo se akceptěrowaś, dokulaž ma njekorektny format. Pšošym zapódaj adresu w korektnem formaśe abo wulašuj to pólo.',
'accountcreated'             => 'Wužywarske konto jo se wutwóriło.',
'accountcreatedtext'         => 'Wužywarske konto $1 jo se wutwóriło.',
'createaccount-title'        => 'Wužywarske konto za {{SITENAME}} nawarjone',
'createaccount-text'         => 'Něchten ($1) za $2 jo na boku {{SITENAME}} ($4) wužywarske konto wutwórił.
Pótajmne słowo za "$2" jo "$3". Trjeba se pśizjawiś a swóje pótajmne słowo pśemjeniś.

Móžoš toś tom informacije zabyś kak to jano zmólka była.',
'loginlanguagelabel'         => 'Rěc: $1',

# Password reset dialog
'resetpass'               => 'Šćitne gronidło za konta nastajiś.',
'resetpass_announce'      => 'Sy z nachylnym e-mailowym šćitnym gronidłom pśizjawjony. Aby pśizjawjenje zakóńcył, zapódaj how nowe šćitne gronidło:',
'resetpass_text'          => '<!-- Dodaj how tekst -->',
'resetpass_header'        => 'Šćitne gronidło nastajiś',
'resetpass_submit'        => 'Šćitne gronidło nastajiś a se pśizjawiś',
'resetpass_success'       => 'Twójo nowe šćitne gronidło jo nastajone. Něnto se pśizjaw …',
'resetpass_bad_temporary' => 'Nachylne e-mailowe šćitne gronidło njejo korektne. Sy swójo šćitne gronidło južo pśeměnił(a) abo wó nowe nachylne gronidło pšošył(a).',
'resetpass_forbidden'     => 'Šćitne gronidło na toś tom wikiju njamóžo se změniś.',
'resetpass_missing'       => 'Prozny formular.',

# Edit page toolbar
'bold_sample'     => 'Tucny tekst',
'bold_tip'        => 'Tucny tekst',
'italic_sample'   => 'Kursiwny tekst',
'italic_tip'      => 'Kursiwny tekst',
'link_sample'     => 'Tekst wótkaza',
'link_tip'        => 'Interny wótkaz',
'extlink_sample'  => 'http://www.pśikład.com nadpismo wótkaza',
'extlink_tip'     => 'Eksterny wótkaz (źiwaś na http://)',
'headline_sample' => 'Nadpismo',
'headline_tip'    => 'Nadpismo rowniny 2',
'math_sample'     => 'Zapódaj how formulu',
'math_tip'        => 'Matematiska formula (LaTeX)',
'nowiki_sample'   => 'Zapódaj how njeformatěrowany tekst',
'nowiki_tip'      => 'Wiki-syntaksu ignorěrowaś',
'image_sample'    => 'Pokazka.jpg',
'image_tip'       => 'Wobrazowa pokazka',
'media_sample'    => 'pokazka.ogg',
'media_tip'       => 'Wótkaz k mediowej dataji',
'sig_tip'         => 'Twója signatura z casowym kołkom',
'hr_tip'          => 'Horicontalna linija (rědko wužywaś)',

# Edit pages
'summary'                   => 'Zespominanje',
'subject'                   => 'Tema/Nadpismo',
'minoredit'                 => 'Snadna změna',
'watchthis'                 => 'Toś ten bok wobglědowaś',
'savearticle'               => 'Bok składowaś',
'preview'                   => 'Pśeglěd',
'showpreview'               => 'Pśeglěd pokazaś',
'showlivepreview'           => 'Livepśeglěd',
'showdiff'                  => 'Pśeměnjenja pokazaś',
'anoneditwarning'           => "'''Warnowanje:''' Njejsy pśizjawjony. Změny w stawiznach togo boka składuju se z twójeju IP-adresu.",
'missingsummary'            => "'''Pokazka:''' Njejsy žedno zespominanje zapódał. Gaž kliknjoš na \"Składowaś\" składujo se bok bźez zespominanja.",
'missingcommenttext'        => 'Pšosym zespominanje zapódaś.',
'missingcommentheader'      => "'''WARNOWANJE:''' Njejsy žedno nadpismo zapódał. Gaž kliknjoš na \"Składowaś\", składujo se twójo wobźěłanje mimo nadpisma.",
'summary-preview'           => 'Pśeglěd zespominanja',
'subject-preview'           => 'Pśeglěd nadpisma',
'blockedtitle'              => 'Wužywaŕ jo se blokěrował',
'blockedtext'               => "<big>'''Twójo wužywarske mě abo IP-adresa stej se blokěrowałej.'''</big>

Zablokěrował(a) $1. Pśicyna: ''$2''.

* Zachopjeńk blokěrowanja: $8
* Kóńc blokěrowanja: $6
* Wótmyslenje blokěrowanja: $7

Móžoš wužywarja $1 abo [[{{MediaWiki:Grouppage-sysop}}|administratora]] kontaktěrowaś, aby wó blokěrowanju diskutěrował.
Njamóžoš e-mail wótpósłaś nježlic až njezapódajoš płaśecu adresu na boku
[[Special:Preferences|account preferences]] a nježlic blokěrowanje se njezwignjo. 
Twója IP-adresa jo $3, a ID blokěrowanja jo #$5. Pšosym pódaj ju w diskusiji.",
'autoblockedtext'           => "Twója IP-adresa jo awtomatiski se blokěrowała, dokulaž jo wót drugego wužywarja se wužywała, kótaryž jo był pśez $1 blokěrowany.
Pśicyna:

:''$2''

* Zachopjeńk blokěrowanja: $8
* Kóńc blokěrowanja: $6

Ty móžoš wužywarja $1 abo [[{{MediaWiki:Grouppage-sysop}}|administrator]] kontaktěrowaś, aby wó blokaźe diskutěrował.

Njamóžoš e-mail wótpósłaś nježlic až njezapódajoš płaśecu adresu na boku [[Special:Preferences|user preferences]] a nježlic až se blokěrowanje njezwignjo.

ID twójogo blokěrowanja jo $5. Pšosym pódaj ju w swójich pšašanjach.",
'blockednoreason'           => 'Pśicyna njejo dana',
'blockedoriginalsource'     => "Žrědłowy tekst boka '''$1''':",
'blockededitsource'         => "Žrědłowy tekst '''Twójich pśinoskow''' do '''$1''' jo:",
'whitelistedittitle'        => 'Za wobźěłanje dejš se pśizjawiś',
'whitelistedittext'         => 'Musyš se $1, aby mógał boki wobźěłowaś.',
'whitelistreadtitle'        => 'Za cytanje dejš se pśizjawiś',
'whitelistreadtext'         => 'Za cytanje bokow dejš se [[Special:Userlogin|how pśizjawiś]].',
'whitelistacctitle'         => 'Njamaš pšawo, wužywarske konto załožyś.',
'whitelistacctext'          => 'Aby mógał pód {{SITENAME}} wužywarjow załožyś, musyš [[Special:Userlogin|how se pśizjawiś]] a wótpowědujuce pšawa měś.',
'confirmedittitle'          => 'Za wobźěłanje jo wobkšuśenje e-mailki notne.',
'confirmedittext'           => 'Nježli až móžoš źěłaš, musyš swóju e-mailowu adresu wobkšuśiś. Pšosym dodaj a wobkšuś swóju e-mailowu adresu w [[Special:Preferences|nastajenjach]].',
'nosuchsectiontitle'        => 'Wótrězk njeeksistěrujo.',
'nosuchsectiontext'         => 'Sy wopytał wobźěłaś njeeksistěrujucy wótrězk $1. Dokulaž taki wótrězk njeeksistěrujo, njamóžoš swójo wobźěłanje niźi składowaś.',
'loginreqtitle'             => 'Pśizjawjenje trěbne',
'loginreqlink'              => 'se pśizjawiś',
'loginreqpagetext'          => 'Dejš se $1, aby mógł boki pšawje cytaś.',
'accmailtitle'              => 'Šćitne gronidło jo se wótpósłało.',
'accmailtext'               => 'Šćitne gronidło za wužywarja "$1" jo na adresu $2 se wótpósłało.',
'newarticle'                => '(Nowy nastawk)',
'newarticletext'            => 'Sy slědował wótkaz na bok, kótaryž hyšći njeeksistěrujo.
Aby bok wutwórił, ga napiš do kašćika spózy. (Dokradnjejše informacije pód: [[{{MediaWiki:Helppage}}|help page]].',
'anontalkpagetext'          => "---- ''Toś ten bok słužy za diskusiju z anonymnym wužywarjom, kótaryž njejo dotychměst žedno wužywarske konto załožył, abo swójo konto njewužywa. Togodla dejmy jogo numerisku ip-adresu wužywaś, aby jogo identificěrowali. Taka ip-adresa móžo wót wšakich wužywarjow se wužywaś. Jolic sy anonymny wužywaŕ a se mysliš, až su se njerelewantne komentary na tebje měrili, pšosym [[Special:Userlogin|załož konto abo pśizjaw se]], až njeby wěcej k takim zamólenjam došło.",
'noarticletext'             => 'Dotychměst njewopśimjejo toś ten bok hyšći žeden tekst. Móžoš w drugich bokach [[Special:Search/{{PAGENAME}}|za napismom togo boka pytaś]] abo [{{fullurl:{{FULLPAGENAME}}|action=edit}} toś ten bok wobźěłaś].',
'clearyourcache'            => "'''Pokazka:''' Pó składowanju jo móžno, až dejš cache swójogo browsera wuprozdniś, aby změny wiźeł.

'''Mozilla/Firefox:''' ''Shift-Strg-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''⌘-R'', '''Konqueror:''' ''Strg-R''.",
'usercssjsyoucanpreview'    => '<strong>Pokazka:</strong> Wužywaj tłocydło "Pśeglěd", aby swój nowy css/js testował, nježli až jen składujoš.',
'usercsspreview'            => "== Pśeglěd Twójogo wužywarskego CSS ==

'''Glědaj:''' Pó składowanju musyš browseroju kazaś, aby nowu wersiju pokazał: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userjspreview'             => "== Pśeglěd Wašogo wužywarskego JavaScripta ==
'''Glědaj:''' Pó składowanju musyš swójomu browseroju kazaś, aby nowu wersiju pokazał: '''Mozilla/Firefox:''' ''Strg-Shift-R'', '''Internet Explorer:''' ''Strg-F5'', '''Opera:''' ''F5'', '''Safari:''' ''Cmd-Shift-R'', '''Konqueror:''' ''F5''.",
'userinvalidcssjstitle'     => "'''Warnowanje:''' Njeeksistěrujo šat „$1“. Pšosym mysli na to, až wužywaju .css- a .js-boki mały pismik, na pś. ''{{ns:user}}:Pśikładowa/monobook.css'' město ''{{ns:user}}:Pśikładowa/Monobook.css''.",
'updated'                   => '(Zaktualizěrowane)',
'note'                      => '<strong>Pokazka:</strong>',
'previewnote'               => '<strong>To jo jano pśeglěd, bok njejo hyšći składowany!</strong>',
'previewconflict'           => 'Toś ten pśeglěd wótbłyšćujo tekst górjejcnego póla. Bok buźo tak wuglědaś, jolic jen něnto składujoš.',
'session_fail_preview'      => '<strong>Wódaj! Twójo wobźěłanje njejo se mógało składowaś, dokulaž su daty twójogo pósejźenja se zgubili. Pšosym wopytaj hyšći raz. Jolic až to pón pśecej hyšći njejźo, wopytaj se wótzjawiś a zasej pśizjawiś.</strong>',
'session_fail_preview_html' => "<strong>Wódaj! Twójo wobźěłanje njejo se mógało składowaś, dokulaž su daty twójogo pósejźenja se zgubili.</strong>

''Dokulaž jo w toś tom wikiju cysty html aktiwizěrowane, jo pśeglěd se zacynił - ako šćit pśeśiwo JavaScriptowym atakam.''

<strong>Jo-lic to legitiměrowane wobźěłanje, wopytaj hyšći raz. Gaž to zasej njejźo, wopytaj se wót- a zasej pśizjawiś.</strong>",
'token_suffix_mismatch'     => '<strong>Twójo wobźěłanje jo se wótpokazało, dokulaž jo twój browser znamuška we wobźěłańskem tokenje rozsekał. Składowanje by mógało wopśimjeśe boka znicyś. Take casy se źejo, gaž wužywaš web-bazěrowanu, zmólkatu, anonymnu proksy-słužbu.</strong>',
'editing'                   => 'Wobźěłanje boka $1',
'editinguser'               => 'Wobźěłujucy wužywaŕ <b>$1</b>',
'editingsection'            => 'Wobźěłanje boka $1 (wótrězk)',
'editingcomment'            => 'Wobźěłanje boka $1 (komentar)',
'editconflict'              => 'Wobźěłański konflikt: $1',
'explainconflict'           => 'Něchten drugi jo bok změnił, pó tym, až sy zachopił jen wobźěłaś.
Górjejcne tekstowe pólo wopśimjejo tekst boka, ako tuchylu eksistěrujo.
Twóje změny pokazuju se w dołojcnem tekstowem pólu.
Pšosym zapódaj twóje změny do górjejcnego tekstowego póla.
<b>Jano</b> wopśimjeśe górjejcnego tekstowego póla se składujo, gaž tłocyš na "składowaś"<br />',
'yourtext'                  => 'Twój tekst',
'storedversion'             => 'Składowana wersija',
'nonunicodebrowser'         => '<strong>Glědaj:</strong> Twój browser njamóžo unicodowe znamuška pšawje pśeźěłaś. Pšosym wužywaj hynakšy browser.',
'editingold'                => '<strong>Glědaj: Wobźěłajoš staru wersiju toś togo boka. Gaž składujoš, zgubiju se wšykne nowše wersije.</strong>',
'yourdiff'                  => 'Rozdźěle',
'copyrightwarning'          => 'Pšosym buź se togo wědobny, až wšykne pśinoski na {{SITENAME}} se wózjawiju pód $2 (za detajle glědaj $1). Jolic až njocoš, až twój tekst se mimo zmilnosći wobźěłujo a za spódobanim drugich redistribuěrujo, pón njeskładuj jen how.<br />
Ty teke wobkšuśijoš, až sy tekst sam napisał abo sy jen wót public domainy resp. wót pódobneje lichotneje resursy kopěrował.

<strong>NJEWÓZJAW WÓT COPYRIGHTA ŠĆITANE ŹĚŁA MIMO DOWÓLNOSĆI!</strong>',
'copyrightwarning2'         => 'Pšosym buź se togo wědobny, až wšykne pśinoski na {{SITENAME}} mógu wót drugich wužywarjow se wobźěłaś, narownaś abo wulašowaś. Jolic až njocoš, až twój tekst se mimo zmilnosći wobźěłujo, ga pón jen how njeskładuj.<br /> Ty teke wobkšuśijoš, až sy tekst sam napisał abo sy jen wót public domainy resp. wót pódobneje lichotneje resursy kopěrował (glědaj $1 za dalše detaile). <strong>NJEWÓZJAW WÓT COPYRIGHTA ŠĆITANE ŹĚŁA MIMO DOWÓLNOSĆI!</strong>',
'longpagewarning'           => '<strong>GLĚDAJ: Toś ten bok wopśimjejo $1 KB; Někotare browsery mógu měś problemy z wobźěłowanim bokow, kótarež su wětše ako 32 KB.
Pšosym pśemysli, lic njamóžo se bok na mjeńše wótrězki rozdźěliś.</strong>',
'longpageerror'             => '<strong>Zmólka: Tekst, kótaryž coš składowaś jo $1 KB wjeliki. To jo wěcej, ako dowólony maksimum ($2 KB). Składowanje njejo móžno.</strong>',
'readonlywarning'           => '<strong>WARNOWANJE: Datowa banka jo se za wótwardowanje zacyniła. Togodla njebuźo tebje tuchylu móžno, twóje wobźěłanja składowaś. Jolic až coš, ga móžoš tekst kopěrowaś a w tekstowej dataji składowaś, aby jen pózdźej how wózjawił.</strong>',
'protectedpagewarning'      => "'''Glědaj: Toś ten bok jo se zakazał, tak až jano sysopowe wužywarje mógu jen wobźěłaś.'''",
'semiprotectedpagewarning'  => "'''Markuj:''' Toś ten bok jo se zakazał, tak až jano registrěrowane wužywarje mógu jen wobźěłaś.",
'cascadeprotectedwarning'   => "'''Glědaj: Toś ten bok jo se zakazał, tak až jano wužywarje ze sysopowymi priwiliegijami mógu jen wobźěłaś, dokulaž jo zawězana do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, {{PLURAL:$1|kótaryž jo šćitany|kótarejž stej šćitanej|kótarež su šćitane}} z pomocu kaskadoweje zakazanskeje opcije.'''",
'templatesused'             => 'Za toś ten bok su se slědujuce šablony wužywali:',
'templatesusedpreview'      => 'Za toś ten pśeglěd su slědujuce šablony se wužywali:',
'templatesusedsection'      => 'W toś tom wótrězku su slědujuce šablony se wužywali:',
'template-protected'        => '(šćitane)',
'template-semiprotected'    => '(poł šćitane)',
'edittools'                 => '<!-- Tekst how buźo wiźeś pód wobźěłowańskimi a upload-formularami. -->',
'nocreatetitle'             => 'Załožowanje nowych bokow jo se wobgranicowało.',
'nocreatetext'              => 'Załoženje nowych bokow jo se wót serwera wobgranicowało. Móžoš hyś slědk a eksistěrujucy bok wobźěłaś, abo se [[Special:Userlogin|pśizjawiś]].',
'nocreate-loggedin'         => 'Njamaš pšawo, w toś tej wiki nowy bok załožyś.',
'permissionserrors'         => 'Problem z pšawami',
'permissionserrorstext'     => 'Njamaš pšawo to cyniś. {{PLURAL:$1|Pśicyna|Pśicynje|Pśicyny}}:',
'recreate-deleted-warn'     => "'''Glědaj: Ty wótžywijoš bok, kótaryž jo pjerwjej se wulašował.'''
 
Pšosym pśespytuj kradosćiwje, lic wótpowědujo dalšne wótnowjenje bokow směrnicam.
Aby se mógał informěrowaś, slědujo how wulašowanski log-zapis, w kótaremž namakajoš teke pśicyny wulašowanja.",

# "Undo" feature
'undo-success' => 'Wobźěłanje móžo se wótpóraś. Pšosym pśeglěduj dołojcne pśirownowanje aby se wěsty był, až to wót wěrnosći coš, a pón składuj změny, aby se wobźěłanje doskóńcnje wótpórało.',
'undo-failure' => '<span class="error">Změna njejo se mógała wótpóraś, dokulaž jo něchten pótrjefjony wótrězk mjaztym změnił.</span>',
'undo-summary' => 'Nawrośiś wersiju $1 wužywarja [[Special:Contributions/$2|$2]] ([[User talk:$2|Talk]])',

# Account creation failure
'cantcreateaccounttitle' => 'Njejo móžno wužywarske konto wutwóriś',
'cantcreateaccount-text' => "Wutwórjenje wužywarskego konta z toś teje IP adresy (<b>$1</b>) jo blokěrowane pśez [[User:$3|$3]].

Pśicyna, kótaruž $3 jo zapódał, jo ''$2''.",

# History pages
'viewpagelogs'        => 'Protokole boka pokazaś',
'nohistory'           => 'Stawizny wobźěłanja za toś ten bok njeeksistěruju.',
'revnotfound'         => 'Wersija njejo se namakała.',
'revnotfoundtext'     => 'Njejo móžno było, wersiju togo boka namakaś, za kótaremž sy pytał. Pšosym kontrolěruj zapódanu URL.',
'loadhist'            => 'Lodowanje stawiznow boka',
'currentrev'          => 'Aktualna wersija',
'revisionasof'        => 'Wersija wót $1',
'revision-info'       => 'Wersija wót $1 do $2',
'previousrevision'    => '← Zachadna rewizija',
'nextrevision'        => 'Pśiduca wersija →',
'currentrevisionlink' => 'Aktualna wersija',
'cur'                 => 'aktualny',
'next'                => 'pśiduce',
'last'                => 'zachadne',
'orig'                => 'original',
'page_first'          => 'zachopjeńk',
'page_last'           => 'kóńc',
'histlegend'          => 'Aby se změny pokazali, dejtej se pśirownanskej wersiji wuzwóliś. Pón dej se "enter" abo dołojcne tłocanko (button) tłocyś.<br />

Legenda:
* (Aktualne) = Rozdźěl k aktualnej wersiji, (pśedchadna) = rozdźěl k pśedchadnej wersiji
* Cas/datum = W toś tom casu aktualna wersija, wužywarske mě/IP-adresa wobźěłarja, D = drobna změna',
'deletedrev'          => '[wulašowane]',
'histfirst'           => 'nejstarše',
'histlast'            => 'nejnowše',
'historysize'         => '({{PLURAL:$1|1 byte|$1 byta|$1 byty}})',
'historyempty'        => '(prozne)',

# Revision feed
'history-feed-title'          => 'Stawizny wersijow',
'history-feed-description'    => 'Stawizny wersijow za toś ten bok w {{GRAMMAR:lokatiw|{{SITENAME}}}}',
'history-feed-item-nocomment' => '$1 na $2', # user at time
'history-feed-empty'          => 'Pominany bok njeeksistěrujo.
Snaź jo se z wiki wulašował abo hynac pómjenił.
[[Special:Search|Pśepytaj]] {{SITENAME}} za relewantnymi bokami.',

# Revision deletion
'rev-deleted-comment'         => '(Komentar wulašowany)',
'rev-deleted-user'            => '(Wužywarske mě wulašowane)',
'rev-deleted-event'           => '(zapis wulašowany)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Toś ta wersija jo ze zjawnych archiwow se wulašowała. Dalšne informacije wó wulašowanju a pśicynu wulašowanja namakaju se w [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} wulašowańskej log-lisćinje].</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks">Toś ta wersija jo ze zjawnych archiwow se wulašowała. Ako administrator móžoš je dalej wiźeś. Dalšne informacije wó wulašowanju a pśicyna wulašowanja namakaju se w [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} wulašowańskej lisćinje].</div>',
'rev-delundel'                => 'pokazaś/schowaś',
'revisiondelete'              => 'Wersije wulašowaś/wótnowiś',
'revdelete-nooldid-title'     => 'Wersija njejo pódana',
'revdelete-nooldid-text'      => 'Njejsy žednu wersiju pódał, na kótarejž dejała se akcija wuwjasć.',
'revdelete-selected'          => "{{PLURAL:$2|wuzwólona wersija|wuzwólonej wersiji|wuzwólone wersije}} wót '''$1:'''",
'logdelete-selected'          => "{{PLURAL:$2|wuzwólony zapis do log-lisćiny|wuzwólonej zapisa do log-lisćiny|wuzwólone zapise do log-lisćiny}} za '''$1:'''",
'revdelete-text'              => 'Wulašowane wersije budu dalej se wujawjowaś w stawiznach boka, ale jich wopśimjeśe njebuźo za zjawnosć wěcej wiźobna.

Dalšne administratory toś teje wiki mógu ale pśecej hyšći schowane wopśimjeśe wiźeś a mógu jo wótnowiś tak dłujko ako njepłaśe restrikcije teke za administratory.',
'revdelete-legend'            => 'wobgranicowanje póstajiś:',
'revdelete-hide-text'         => 'Tekst wersije schowaś',
'revdelete-hide-name'         => 'Akciju log-lisćiny schowaś',
'revdelete-hide-comment'      => 'Komentar wobźěłanja schowaś',
'revdelete-hide-user'         => 'mě/IP-adresu wobźěłarja schowaś',
'revdelete-hide-restricted'   => 'Toś te restrikcije deje teke za administratorow płaśiś.',
'revdelete-suppress'          => 'Pśicynu wulašowanja teke za administratorow schowaś',
'revdelete-hide-image'        => 'Wopśimjeśe dataje schowaś',
'revdelete-unsuppress'        => 'Wobgranicowanja za wótnowjone wersije zasej zwignuś.',
'revdelete-log'               => 'Komentar w log-lisćinje:',
'revdelete-submit'            => 'We wuzwólonej wersiji nałožyś',
'revdelete-logentry'          => 'Woglědanje wersije změnjone za [[$1]]',
'logdelete-logentry'          => 'wiźobnosć za [[$1]] změnjona.',
'revdelete-logaction'         => '$1 {{plural:$1|wersija sajźona|wersiji sajźonej|wersije sajźone}} na modus $2.',
'logdelete-logaction'         => '$1 {{plural:$1|zapis|zapisa|zapise}} za [[$3]] na modus $2 {{plural:$1|sajźony|sajźonej|sajźone}}.',
'revdelete-success'           => 'Wiźobnosć wersije jo se z wuspěchom změniła.',
'logdelete-success'           => 'Wiźobnosć log-lisćiny z wuspěchom změnjona.',

# Oversight log
'oversightlog'    => 'Pśeglědowa log-lisćina',
'overlogpagetext' => 'Dołojce jo wiźeś lisćina nejnowšych wulašowanjow a zakazow, kótarež su teke za administratory schowane. Glědaj na [[Special:Ipblocklist|zakazowu lisćinu]], aby wiźeł aktualne zakaze.',

# History merging
'mergehistory'                     => 'Zwězaś stawizny bokow',
'mergehistory-box'                 => 'Zwězaś wersjiowu toś teju bokowo:',
'mergehistory-from'                => 'Žrědłowy bok:',
'mergehistory-into'                => 'Celowy bok:',
'mergehistory-no-source'           => 'Žrědłowy bok $1 njeeksistěruje.',
'mergehistory-no-destination'      => 'Celowy bok $1 njeeksistěruje.',
'mergehistory-invalid-source'      => 'Žrědłowy bok musy měś dobre nadpismo.',
'mergehistory-invalid-destination' => 'Celowy bok musy měś dobre nadpismo.',

# Diffs
'history-title'           => 'Stawizny wersijow wót „$1“',
'difference'              => '(rozdźěle mjazy wersijoma/wersijami)',
'lineno'                  => 'Rědka $1:',
'compareselectedversions' => 'Wuzwólonej wersiji pśirownaś',
'editundo'                => 'wótwrośiś',
'diff-multi'              => "<span style='font-size: smaller'>(Pśirownanje wersijow(u) wopśimjejo teke {{plural:$1|mjaz tutyma lažecu wersiju|$1 mjaz tutyma lažecej wersiji|$1 mjaz tutyma lažece wersije}}.)</span>",

# Search results
'searchresults'         => 'Wuslědki pytanja',
'searchresulttext'      => 'Za wěcej informacijow wó pśepytowanju {{GRAMMAR:genitiw|{{SITENAME}}}} glědaj [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'        => 'Ty sy pytał „[[:$1]]“.',
'searchsubtitleinvalid' => 'Ty sy pytał „$1“.',
'noexactmatch'          => "'''Bok z napismom „$1“ njeeksistěrujo.'''

Wopytaj z połnotekstowym pytanim abo pśepytaj [[Special:Allpages|alfabetiski indeks]] za pódobnymi wurazami.
Móžoš bok ale teke [[$1|sam załožyś]].",
'noexactmatch-nocreate' => "'''Njama boka z nadpismom \"\$1\".'''",
'titlematches'          => 'boki z wótpowědujucym napismom',
'notitlematches'        => 'Boki z wótpowědujucym napismom njeeksistěruju.',
'textmatches'           => 'Boki z wótpowědujucym tekstom',
'notextmatches'         => 'Boki z wótpowědujucym tekstom njeeksistěruju.',
'prevn'                 => 'zachadne $1',
'nextn'                 => 'pśiduce $1',
'viewprevnext'          => 'Pokazaś ($1) ($2) ($3).',
'showingresults'        => "How {{PLURAL:|jo '''1''' wuslědk|stej '''$1''' wuslědka|su '''$1''' wuslědki}} wót cysła '''$2'''.",
'showingresultsnum'     => "How {{PLURAL:$3|jo '''1''' wuslědk|stej '''$3''' wuslědka|su '''$3''' wuslědki}} wót cysła '''$2'''.",
'nonefound'             => '<strong>Pokazka</strong>: Njewuspěšne wótpšašowanja zawinujo cesto pytanje za powšyknymi a togodla njeindicěrowanymi słowami ako "měś" abo "wót", ale teke pytanje za wěcej słowami. Pokazuju se jano boki, kótarež wopśimjeju wšykne pytane wuraze. W takem paźe wopytaj hyšći raz z mjenje pytanymi wurazami.',
'powersearch'           => 'Pytaś',
'powersearchtext'       => 'W mjenjowych rumach pytaś:<br />$1<br />$2 dalejpósrědnjenja pokazaś<br />Pytaś za: $3 $9',
'searchdisabled'        => 'Pytanje we {{SITENAME}} jo se deaktiwěrowało. Tak dłujko móžoš w googlu pytaś. Pšosym wobmysli, až móžo pytanski indeks za {{SITENAME}} njeaktualny byś.',

# Preferences page
'preferences'              => 'Nastajenja',
'preferences-summary'      => 'Na toś tom specialnem boku móžoš swóje pśizjawjenske daty změniś a wěste źěły pówjercha indiwiduelnje pśiměrjowaś.',
'mypreferences'            => 'móje nastajenja',
'prefs-edits'              => 'Licba wobźěłanjow:',
'prefsnologin'             => 'Njejsy pśizjawjony',
'prefsnologintext'         => 'Musyš se [[Special:Userlogin|pśizjawiś]], aby mógał swóje nastajenja změniś.',
'prefsreset'               => 'Nastajenja su ze składa se wótnowili. Twóje změny njejsu se składowali.',
'qbsettings'               => 'Bocna lejstwa',
'qbsettings-none'          => 'Žedne',
'qbsettings-fixedleft'     => 'nalěwo fiksěrowane',
'qbsettings-fixedright'    => 'napšawo fiksěrowane',
'qbsettings-floatingleft'  => 'nalěwo se znosujuce',
'qbsettings-floatingright' => 'napšawo se znosujuce',
'changepassword'           => 'Šćitne gronidło změniś',
'skin'                     => 'Šat',
'math'                     => 'Math',
'dateformat'               => 'Format datuma',
'datedefault'              => 'Standard',
'datetime'                 => 'Datum a cas',
'math_failure'             => 'Zmólka',
'math_unknown_error'       => 'njeznata zmólka',
'math_unknown_function'    => 'njeznata funkcija',
'math_lexing_error'        => 'leksikaliska zmólka',
'math_syntax_error'        => 'syntaktiska zmólka',
'math_image_error'         => 'PNG-konwertěrowanje njejo se raźiło. Glědaj, lic su latex, dvips gs abo konwertěruj pšawje instalěrowane.',
'math_bad_tmpdir'          => 'Njejo móžno temporarny zapisk za matematiske formule załožyś resp. do njogo pisaś.',
'math_bad_output'          => 'Njejo móžno celowy zapisk za matematiske formule załožyś resp. do njogo pisaś.',
'math_notexvc'             => 'Program texvc felujo. Pšosym glědaj do math/README.',
'prefs-personal'           => 'Wužywarski profil',
'prefs-rc'                 => 'Aktualne změny',
'prefs-watchlist'          => 'Wobglědowańka',
'prefs-watchlist-days'     => 'Maksimalna licba dnjow, kenž maju we wobglědowańkach se pokazaś:',
'prefs-watchlist-edits'    => 'Maksimalna licba změnow, kenž maju w rozšyrjonej lisćinje wobglědowańkow se pokazaś:',
'prefs-misc'               => 'Wšake nastajenja',
'saveprefs'                => 'Składowaś',
'resetprefs'               => 'Zapódaśa zachyśiś',
'oldpassword'              => 'Stare šćitne gronidło:',
'newpassword'              => 'Nowe šćitne gronidło:',
'retypenew'                => 'Nowe šćitne gronidło (hyšći raz):',
'textboxsize'              => 'Wobźěłaś',
'rows'                     => 'Rědki:',
'columns'                  => 'Słupy:',
'searchresultshead'        => 'Pytaś',
'resultsperpage'           => 'Wuslědki na bok:',
'contextlines'             => 'Rědki na wuslědk:',
'contextchars'             => 'Znamuška na rědku:',
'stub-threshold'           => 'Formatěrowanje  <a href="#" class="stub">wótkaza na zarodk</a> (w bytach):',
'recentchangesdays'        => 'Licba dnjow, kenž se pokazuju w "slědnych změnach":',
'recentchangescount'       => 'Licba wobźěłanjow w „Slědnych změnach“ a „Nowych nastawkach“:',
'savedprefs'               => 'Twóje nastajenja su se składowali.',
'timezonelegend'           => 'Casowa cona',
'timezonetext'             => 'Pódaj licbu góźinow, kótarež laže mjazy twójeju casoweju conu a UTC.',
'localtime'                => 'Městny cas:',
'timezoneoffset'           => 'Rozdźěl¹:',
'servertime'               => 'Aktualny cas na serwerje:',
'guesstimezone'            => 'Z browsera pśewześ',
'allowemail'               => 'Dostawanje e-mailow drugich wužywarjow zmóžniś.',
'defaultns'                => 'Standardnje ma se w toś tych mjenjowych rumach pytaś:',
'default'                  => 'Standard',
'files'                    => 'Dataje',

# User rights
'userrights-lookup-user'      => 'Wužywarske kupki zastojaś',
'userrights-user-editname'    => 'Wužywarske mě:',
'editusergroup'               => 'Wužywarske kupki wobźěłaś.',
'userrights-editusergroup'    => 'Pšawa wužywarskich kupkow wobźěłaś',
'saveusergroups'              => 'Wužywarske kupki składowaś',
'userrights-groupsmember'     => 'Cłonk kupki:',
'userrights-groupsavailable'  => 'Eksistěrujuce kupki:',
'userrights-groupshelp'       => 'Wuzwól kupki, z kótarychž coš wužywarja wulašowaś abo kótarymž coš wužywarja pśidaś. Njewubrane kupki se njezměniju. Demarkěrowaś móžoš kupku z CTRL (= STRG) + lěwym kliknjenim.',
'userrights-reason'           => 'Pśicyna změny:',
'userrights-available-none'   => 'Njamaš pšawo cłonkojstwo wužywarskeje kupki změniś.',
'userrights-available-add'    => 'Móžoš wužywarjow k $1 dodawaś.',
'userrights-available-remove' => 'Móžoš wužywarjow z kupkow $1 wulašowaś.',

# Groups
'group'               => 'Kupka:',
'group-autoconfirmed' => 'Wobkšuśone wužywarje',
'group-bot'           => 'awtomatiske programy (boty)',
'group-sysop'         => 'Administratory',
'group-bureaucrat'    => 'Běrokraty',
'group-all'           => '(wše)',

'group-autoconfirmed-member' => 'Wobkšuśony wužywaŕ',
'group-bot-member'           => 'awtomatiski program (bot)',
'group-sysop-member'         => 'administrator',
'group-bureaucrat-member'    => 'Běrokrat',

'grouppage-autoconfirmed' => '{{ns:project}}:Wobkšuśone wužywarje',
'grouppage-bot'           => '{{ns:project}}:awtomatiske programy (boty)',
'grouppage-sysop'         => '{{ns:project}}:Administratory',
'grouppage-bureaucrat'    => '{{ns:project}}:Běrokraty',

# User rights log
'rightslog'      => 'Log-lisćina wužywarskich pšawow',
'rightslogtext'  => 'To jo log-lisćina wužywarskich pšawow.',
'rightslogentry' => 'Pśisłušnosć ku kupce jo se za „[[$1]]“ změniła wót „$2“ na „$3“.',
'rightsnone'     => '(nic)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|změna|změnje|změny}}',
'recentchanges'                     => 'Aktualne změny',
'recentchangestext'                 => "How móžoš slědne změny w(e) '''{{SITENAME}}''' slědowaś.",
'recentchanges-feed-description'    => 'Slěduj z toś tym zapódaśim nejaktualnjejše změny w(e) {{SITENAME}}.',
'rcnote'                            => "Dołojce {{PLURAL:$1|jo '''1''' změna|stej slědnej '''$1''' změnje|su slědne '''$1''' změny}} w {{PLURAL:$2|slědnem dnju|slědnyma '''$2''' dnjoma|slědnych '''$2''' dnjach}}, staw wót $3.",
'rcnotefrom'                        => 'Dołojce pokazuju se změny wót <b>$2</b> (maks. <b>$1</b> zapisow).',
'rclistfrom'                        => 'Nowe změny wót $1 pokazaś.',
'rcshowhideminor'                   => 'Snadne změny $1',
'rcshowhidebots'                    => 'awtomatiske programy (boty) $1',
'rcshowhideliu'                     => 'pśizjawjone wužywarje $1',
'rcshowhideanons'                   => 'anonymne wužywarje $1',
'rcshowhidepatr'                    => 'kontrolěrowane změny $1',
'rcshowhidemine'                    => 'móje pśinoski $1',
'rclinks'                           => 'Slědne $1 změny slědnych $2 dnjow pokazaś<br />$3',
'diff'                              => 'rozdźěl',
'hist'                              => 'wersije',
'hide'                              => 'schowaś',
'show'                              => 'pokazaś',
'minoreditletter'                   => 'S',
'newpageletter'                     => 'N',
'boteditletter'                     => 'B',
'number_of_watching_users_pageview' => '[$1 {{Plural:$1|wobglědowaŕ|wobglědowarja|wobglědowarje}}]',
'rc_categories'                     => 'Jano boki z kategorijow (źělone z pomocu „|“):',
'rc_categories_any'                 => 'wše',
'newsectionsummary'                 => 'Nowy wótrězk /* $1 */',

# Recent changes linked
'recentchangeslinked'          => 'Změny w zwězanych bokach',
'recentchangeslinked-title'    => 'Změny na bokach, kótarež su z „$1“ zalinkowane',
'recentchangeslinked-noresult' => 'Zalinkowane boki njejsu we wuzwólonem casu se změnili.',
'recentchangeslinked-summary'  => "Toś ten specialny bok pódajo slědne změny zalinkowanych bokow. Boki z twójich wobglědowankow su '''tucnje''' pisane.",

# Upload
'upload'                      => 'Dataju pósłaś',
'uploadbtn'                   => 'Dataju pósłaś',
'reupload'                    => 'Dataju hyšći raz pósłaś.',
'reuploaddesc'                => 'Slědk k uploadowemu formularoju',
'uploadnologin'               => 'Njepśizjawjony(a)',
'uploadnologintext'           => 'Dejš se [[Special:Userlogin|pśizjawiś]], aby mógał dataje uploadowaś.',
'upload_directory_read_only'  => 'Seśowy serwer njamóžo do uploadowego zapisa ($1) pisaś.',
'uploaderror'                 => 'Zmólka pśi uploadowanju',
'uploadtext'                  => "Źi na [[Special:Imagelist|lisćinu uploadowanych datajow]], aby mógał eksistěrujuce dataje pytaś a pokazaś.

Wužywaj toś ten formular za uploadowanje nowych datajow. Tłoc na '''„Pśepytaś...“''', aby se dialog wócynił, w kótaremž móžoš dataje wuzwólowaś.
Jo-lic dataja se wuzwóliła, pokažo se mě dataje w tekstowem pólu '''žrědło'''.
Wobkšuś pón licencne dogrono a tłoc na '''dataju uploadowaś'''.
To móžo chylu traś, wósebnje jolic maš pómały zwisk ze seśu.

Coš-lic na jadnom boce '''wobraz''' wužywaś, ga piš na městnje wobraza na pś.:
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:dataja.jpg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:image}}:dataja.jpg|Tekst wótkaza<nowiki>]]</nowiki></tt>'''

Coš-lic '''medija-dataje''' zawězaś, ga piš na pś.:
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:dataja.ogg<nowiki>]]</nowiki></tt>'''
* '''<tt><nowiki>[[</nowiki>{{ns:media}}:dataja.ogg|Tekst wótkaza<nowiki>]]</nowiki></tt>'''

Pšosym mysli na to, až mógu druge wužywarje twóje dataje wulašowaś a změniś - rowno tak, ako pla normalnego wopśimjeśa boka.",
'uploadlog'                   => 'datajowy protokol',
'uploadlogpage'               => 'Datajowy protokol',
'uploadlogpagetext'           => 'Dołojce jo lisćina nejnowšych uploadow, glědaj teke how [[{{ns:special}}:Newimages]].',
'filename'                    => 'Mě dataje',
'filedesc'                    => 'Zespominanje',
'fileuploadsummary'           => 'Zespominanje:',
'filestatus'                  => 'Status copyrighta',
'filesource'                  => 'Žrědło',
'uploadedfiles'               => 'Uploadowane dataje',
'ignorewarning'               => 'Warnowanje ignorěrowaś a dataju składowaś.',
'ignorewarnings'              => 'Wše warnowanja ignorěrowaś',
'minlength1'                  => 'Mjenja datajow muse wopśimjeś nanejmjenjej jaden pismik.',
'illegalfilename'             => 'Datajowe mě „$1“ wopśimjejo njedowólone znamuška. Pšosym pśemjeni dataju a wopytaj ju wótnowotki uploadowaś.',
'badfilename'                 => 'Mě dataje jo se změniło na „$1“.',
'filetype-badmime'            => 'Dataje z MIME-typom „$1“ njesměju se uploadowaś.',
'filetype-missing'            => 'Dataja njama žedno rozšyrjenje (na pś. „.jpg“).',
'large-file'                  => 'Pó móžnosći njedejała dataja wětša byś ako $1. Toś ta dataja jo $2 wjelika.',
'largefileserver'             => 'Dataja jo wětša ako serwer dopušćijo.',
'emptyfile'                   => 'Dataja jo prozna. Pśicyna togo móžo byś zmólka w mjenju dataje. Kontrolěruj pšosym, lic coš dataju napšawdu uploadowaś.',
'fileexists'                  => 'Dataja z toś tym mjenim južo eksistěrujo. Tłocyš-lic na "Dataju składowaś", ga se dataja pśepišo. Pšosym kontrolěruj <strong><tt>$1</tt></strong>, gaž njejsy se kradu wěsty.',
'fileexists-extension'        => 'Eksistěrujo južo dataja z pódobnym mjenim:<br />
Mě dataje, kótaraž dej se uploadowaś: <strong><tt>$1</tt></strong><br />
Mě eksistěrujuceje dataje: <strong><tt>$2</tt></strong><br />
Wuzwól nowe mě, jolic až sy se wěsty, až dataji njejstej identiskej.',
'fileexists-thumb'            => "<center>'''Eksistěrujucy wobraz'''</center>",
'fileexists-thumbnail-yes'    => 'Zazdaśim ma wobraz reducěrowanu wjelikosć <i>(thumbnail)</i>. Kontrolěruj pšosym dataju <strong><tt>$1</tt></strong>.<br />
Jadna-lic se wó wobraz w originalnej wjelikosći, pón njejo notne, separatny pśeglědowy wobraz uploadowaś.',
'file-thumbnail-no'           => 'Mě dataje zachopijo z <strong><tt>$1</tt></strong>. To pokazujo na wobraz z reducěrowaneju wjelikosću. <i>(thumbnail)</i>.
Pšosym kontrolěruj, lic maš wobraz w połnem rozpušćenju a uploaduj jen, w drugem paźe změń pšosym mě dataje.',
'fileexists-forbidden'        => 'Dataja z toś tym mjenim južo eksistěrujo. Pšosym nawroś se a uploaduj dataju z hynakšym mjenim. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Dataja z toś tym mjenim južo eksistěrujo. Pšosym nawroś se uploaduj toś tu dataju z drugim mjenim. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Upload jo był wuspěšny.',
'uploadwarning'               => 'Warnowanje',
'savefile'                    => 'Dataju składowaś',
'uploadedimage'               => 'Dataja "[[$1]]" jo uploadowana.',
'overwroteimage'              => 'Nowa wersija "[[$1]]" jo se uploadowała.',
'uploaddisabled'              => 'Uploadowanje jo se znjemóžniło.',
'uploaddisabledtext'          => 'W toś tej wiki jo uploadowanje se znjemóžniło.',
'uploadscripted'              => 'Toś ta dataja wopśimjejo HTML abo script code, kótaryž móžo wót browsera se zamólnje wuwjasć.',
'uploadcorrupt'               => 'Dataja jo skóńcowana abo ma njekorektnu kóńcowku. Pšosym kontrolěruj dataju a uploaduj hyšći raz.',
'uploadvirus'                 => 'Toś ta dataja ma wirus! Nadrobnosći: $1',
'sourcefilename'              => 'Žrědłowa dataja',
'destfilename'                => 'Celowe mě',
'watchthisupload'             => 'Toś ten bok wobglědowaś',
'filewasdeleted'              => 'Dataja z toś tym mjenim jo była južo raz uploadowana a mjaztym zasej wulašowana. Pšosym kontrolěruj pjerwjej $1, nježli až dataju napšawdu składujoš.',
'upload-wasdeleted'           => "'''Glědaj: Uploadujoš dataju, kótaraž jo južo raz se wulašowała.'''
 
Pšosym kontrolěruj, lic wótpowědujo nowy upload směrnicam.
Aby se mógał informěrowaś jo how log-lisćina z pśicynu wulašowanja:",
'filename-bad-prefix'         => 'Mě dataje, kótaruž uploadujoš, zachopijo na <strong>„$1“</strong>. Take mě jo wót digitalneje kamery pśedpódane a toś wjele njewugroni. Pšosym pómjeni dataju tak, aby mě wěcej wó jeje wopśimjeśu wugroniło.',
'filename-prefix-blacklist'   => ' #<!-- Njezměń nic na toś tej rědce! --> <pre>
# Syntaksa jo slědujuca: 
#   * Wšykno wót "#" znamuška až ku kóńcoju rědki jo komentar.
#   * Kužda njeprozna smužka jo prefiks za typiske datajowe mjenja, kótarež se awtomatiski wót digitalnych kamerow dodawaju.
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobil phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- Njezměń nic na toś tej rědce! -->',

'upload-proto-error'      => 'Njekorektny protokol',
'upload-proto-error-text' => 'URL musy zachopiś z <code>http://</code> abo <code>ftp://</code>.',
'upload-file-error'       => 'Interna zmólka',
'upload-file-error-text'  => 'Pśi wutwórjowanju temporarneje dataje na serwerje jo došło k internej zmólce. Pšosym informěruj systemowego administratora.',
'upload-misc-error'       => 'Njeznata zmólka pśi uploadowanju.',
'upload-misc-error-text'  => 'Pśi uploadowanju jo došło k njeznatej zmólce. Kontrolěruj URL na zmólki a pśeglěduj online-status boka a wopytaj hyšći raz. Dejał-lic problem dalej eksistěrowaś, informuj systemowego administratora.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'URL njejo pśistupna.',
'upload-curl-error6-text'  => 'Pódana URL njejo pśistupna. Pśeglěduj URL na zmólki a kontrolěruj online-status boka.',
'upload-curl-error28'      => 'Pśi uploadowanju jo cas se pśekšocył.',
'upload-curl-error28-text' => 'Bok pśedłujko njejo wótegronił. Kontrolěruj, lic jo bok online, pócakaj wokognuśe a wopytaj pón hyšći raz. Móžo byś zmysłapołne, w drugem casu hyšći raz proběrowaś.',

'license'            => 'Licenca',
'nolicense'          => 'Nic njejo wuzwólone.',
'license-nopreview'  => '(Pśeglěd njejo móžny.)',
'upload_source_url'  => ' (płaśeca, zjawnje pśistupna URL)',
'upload_source_file' => ' (dataja na Twójom kompjuterje)',

# Image list
'imagelist'                 => 'Lisćina datajow',
'imagelist-summary'         => 'Toś ten specialny bok nalicyjo wšykne uploadowane dataje. Normalnje pokazuje se te dataje, ako su slědne se uploadowali, ako prědne w lisćinje. Tłocenje na napise špaltow změnijo sortěrowanje.',
'imagelisttext'             => "How jo lisćina '''$1''' {{PLURAL:$1|dataje|datajowu|datajow}}, sortěrowane $2.",
'getimagelist'              => 'Lisćinu datajow lodowaś.',
'ilsubmit'                  => 'Pytaś',
'showlast'                  => 'Pokazaś slědne $1 datajow, sortěrowane pó $2.',
'byname'                    => 'pó mjenju',
'bydate'                    => 'pó datumje',
'bysize'                    => 'pó wjelikosći',
'imgdelete'                 => 'wulašowaś',
'imgdesc'                   => 'Zespominanje',
'imgfile'                   => 'dataja',
'filehist'                  => 'Stawizny dataje',
'filehist-help'             => 'Tłoc na datum/cas aby tencasna wersija se lodowała.',
'filehist-deleteall'        => 'Wšykno wulašowaś',
'filehist-deleteone'        => 'Toś tu wersiju wulašowaś',
'filehist-revert'           => 'Slědk wześ',
'filehist-current'          => 'něntejšny',
'filehist-datetime'         => 'datum/cas',
'filehist-user'             => 'Wužywaŕ',
'filehist-dimensions'       => 'rozměry',
'filehist-filesize'         => 'Wjelikosć dataje',
'filehist-comment'          => 'Komentar',
'imagelinks'                => 'Wótkaze',
'linkstoimage'              => 'Boki, kótarež wótkazuju na toś tu dataju:',
'nolinkstoimage'            => 'Žedne boki njewótkazuju na toś tu dataju.',
'sharedupload'              => 'Toś ta dataja se gromaźe wužywa - snaź teke w drugich projektach.',
'shareduploadwiki'          => 'Za dalšne informacije glědaj $1.',
'shareduploadwiki-linktext' => 'Bok wopisanja dataje',
'noimage'                   => 'Dataja z takim mjenim njeeksistěrujo, móžoš ju ale $1.',
'noimage-linktext'          => 'dataju pósłaś',
'uploadnewversion-linktext' => 'Uploaduj nowu wersiju toś teje dataje.',
'imagelist_date'            => 'datum',
'imagelist_name'            => 'mě dataje',
'imagelist_user'            => 'wužywaŕ',
'imagelist_size'            => 'Wjelikosć (byte)',
'imagelist_description'     => 'Zespominanje',
'imagelist_search_for'      => 'Pytanje za dataju:',

# File reversion
'filerevert'                => 'Slědk wześ $1',
'filerevert-legend'         => 'Dataju nawrośiś',
'filerevert-intro'          => '<span class="plainlinks">Nawrośijoš dataju \'\'\'[[Media:$1|$1]]\'\'\' na [$4 wersiju wót $2, $3 góź.].</span>',
'filerevert-comment'        => 'Komentar:',
'filerevert-defaultcomment' => 'Nawrośona na wersiju wót $1, $2 góź.',
'filerevert-submit'         => 'Slědk wześ',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' jo se nawrośiło na [$4 wersiju wót $2, $3 góź.].</span>',
'filerevert-badversion'     => 'Za pódany cas njeeksistěrujo žedna wersija dataje.',

# File deletion
'filedelete'             => 'Wulašowaś $1',
'filedelete-legend'      => 'Wulašowaś dataje',
'filedelete-intro'       => "Ty wulašujoš '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Wulašujoš [$4 wersiju wót $2, $3 góź.] dataje \'\'\'„[[Media:$1|$1]]“\'\'\'.</span>',
'filedelete-comment'     => 'Komentar:',
'filedelete-submit'      => 'Wulašowaś',
'filedelete-success'     => "'''$1''' wulašowane.",
'filedelete-success-old' => '<span class="plainlinks">Wersija $2, $3 góź. dataje \'\'\'„[[Media:$1|$1]]“\'\'\' jo se wulašowała.</span>',
'filedelete-nofile'      => "Na toś tom boku '''$1''' njeekistěrujo.",
'filedelete-nofile-old'  => "Wót '''„$1“''' njeeksistěrujo žedna wersija wót $2, $3 góź.",
'filedelete-iscurrent'   => 'Wopytajoš aktualnu wersiju toś teje dataje wulašowaś. Pšosym aktiwěruj pśed tym staršu wersiju.',

# MIME search
'mimesearch'         => 'MIME-typ pytaś',
'mimesearch-summary' => 'Na toś tom specialnem boku mógu se dataje pó MIME-typu filtrowaś. Zapódaśe dej wopśimjeś stawnje typ medija a subtyp: <tt>image/jpeg</tt>.',
'mimetype'           => 'Typ MIME:',
'download'           => 'Ześěgnuś',

# Unwatched pages
'unwatchedpages'         => 'Njewobglědowane boki',
'unwatchedpages-summary' => 'Toś ten specialny bok pokazujo wšykne boki, kótarež njestoje na wobglědowańskej lisćinje žednego wužywarja.',

# List redirects
'listredirects'         => 'Lisćina dalejpósrědnjenjow',
'listredirects-summary' => 'Toś ten bok nalicujo dalejpósrědnjenja.',

# Unused templates
'unusedtemplates'         => 'Njewužywane šablony',
'unusedtemplates-summary' => 'Toś ten bok nalicujo wšykne pśedłogi, ako njejsu do drugich bokow zawězane. Pśeglěduj pšosym druge wótkaze na toś te pśedłogi, nježli až je wulašujoš.',
'unusedtemplatestext'     => 'Toś ten bok nalicujo wšykne boki w mjenjowom rumje šablonow, kótarež njejsu do žednego drugego boka zawězane. Pšosym kontrolěruj dalšne wótkaze, nježli až je wulašujoš.',
'unusedtemplateswlh'      => 'Druge wótkaze',

# Random page
'randompage'         => 'Pśipadny nastawk',
'randompage-nopages' => 'W toś tom rumje njejsu žedne boki.',

# Random redirect
'randomredirect'         => 'Pśipadne dalejpósrědnjenje',
'randomredirect-nopages' => 'W toś tom mjenjowem rumje njeeksistěruju žedne dalejpósrědnjenja.',

# Statistics
'statistics'             => 'Statistika',
'sitestats'              => 'Statistika {{SITENAME}}',
'userstats'              => 'Statistika wužywarjow',
'sitestatstext'          => "W datowej bance {{PLURAL:$1|jo dogromady '''1''' bok|stej dogromady '''$1''' boka|su dogromady '''$1''' boki}}. To wobpśimjejo teke diskusijne boki, boki wó {{SITENAME}}, małe boki, dalejpósrědnjenja a dalšne boki, kótarež njamógu se ewentuelnje ako boki gódnośiś.

Jolic toś te boki se wótlicuju, {{PLURAL:$2|jo '''1''' bok|stej '''$2''' boka|su '''$2''' boki}}, {{PLURAL:$2|kótaryž móžo|kótarejž móžotej|kótarež mógu}} se gódnośiś ako bok.

Dogromady jo se uploadowało '''$8''' {{PLURAL:$8|dataja|dataji|dataje}}.

Dogromady {{PLURAL:$3|běšo|běštej|běchu}} '''$3''' {{PLURAL:$3|wótwołanje|wótwołani|wótwołanja}} a '''$4''' {{PLURAL:$4|wobźěłanje|wobźěłani|wobźěłanja}} wót togo casa, až {{SITENAME}} jo se zarědował(o|a).

To wucynjujo '''$5''' {{PLURAL:$5|wobźěłanje|wobźěłani|wobźěłanja}} na bok a '''$6''' {{PLURAL:$6|wótwołanje|wótwołani|wótwołanja}} na wobźěłanje.

Dłujkosć [http://meta.wikimedia.org/wiki/Help:Job_queue „Job queue“]: '''$7'''",
'userstatstext'          => "Dajo '''$1''' {{PLURAL:$1|registrěrowanego|registrěrowaneju|registrěrowanych}} [[Special:Listusers|{{Plural:|wužywarja|wužywarjowu|wužywarjow}}]].
Wót togo {{PLURAL:$2|ma|matej|maju}} (=$4%) $5-pšawa.",
'statistics-mostpopular' => 'Nejwěcej woglědane boki',

'disambiguations'      => 'Rozjasnjenja zapśimjeśow',
'disambiguationspage'  => 'Šablona:Rozjasnjenje zapśimjeśow',
'disambiguations-text' => 'Slědujuce boki wótkazuju na bok za rozjasnjenje zapśimjeśow. Wótkazujśo lubjej na pótrjefjony bok.

<br />Bok wobjadnawa se ako bok wujasnjenja zapśimjeśa, gaž wótkazujo na nju [[MediaWiki:disambiguationspage]].<br />Wótkaze z mjenjowych rumow se how njenalicuju.',

'doubleredirects'         => 'Dwójne dalejpósrědnjenja',
'doubleredirects-summary' => 'Toś ta lisćina wopśimjejo dalejpósrědnjenja, kótarež wótkazuju na dalšne dalejpósrědnjenja. Kužda smužka wopśimjejo wótkaze k prědnemu a drugemu dalejpósrědnjenjoju a teke cyl drugego dalejpósrědnjenja, což w normalnem paźe jo žycony cylowy bok, na kótaryž dejał južo prědne dalejpósrědnjenje wótkazowaś.',
'doubleredirectstext'     => 'Toś ten bok nalicujo dalejpósrědnjenja, kótarež wótkazuju na druge dalejpósrědnjenja. Kužda smužka wopśimjejo wótkaze na prědne a druge dalejpósrědnjenje a teke na cyl drugego dalejpósrědnjenja, což jo w normalnem paźe wótmyslony cylowy bok, na kótaryž dejał južo prědne dalejpósrědnjenje wótkazowaś.',

'brokenredirects'         => 'Skóńcowane dalejpósrědnjenja',
'brokenredirects-summary' => 'Toś ten bok nalicujo dalejpósrědnjenja, kótarež wótkazuju na njeeksistěrujuce artikle.',
'brokenredirectstext'     => 'Slědujuce dalejpósrědnjenja wótkazuju na njeeksistěrujuce boki:',
'brokenredirects-edit'    => '(wobźěłaś)',
'brokenredirects-delete'  => '(wulašowaś)',

'withoutinterwiki'        => 'Boki na kótarychž njejsu žedne wótkaze na druge rěcy',
'withoutinterwiki-header' => 'Slědujuce boki njewótkazuju na druge rěcne wersije:',

'fewestrevisions'         => 'Boki z nejmjenjej wersijami',
'fewestrevisions-summary' => 'Toś ten specialny bok pokazujo lisćinu bokow z nejmjenjej wobźěłanjami.',

# Miscellaneous special pages
'nbytes'                          => '$1 {{PLURAL:$1|byte|byta|byty}}',
'ncategories'                     => '$1 {{PLURAL:$1|kategorija|kategoriji|kategorije}}',
'nlinks'                          => '$1 {{PLURAL:$1|wótkaz|wótkaza|wótkaze}}',
'nmembers'                        => '$1 {{PLURAL:$1|zapis|zapisa|zapise}}',
'nrevisions'                      => '$1 {{PLURAL:$1|wobźěłanje|wobźěłani|wobźěłanja}}',
'nviews'                          => '$1 {{PLURAL:$1|wótpšašanje|wótpšašani|wótpšašanja}}',
'specialpage-empty'               => 'Toś ten bok njewopśimjejo tuchylu žedne zapise.',
'lonelypages'                     => 'Wósyrośone boki',
'lonelypages-summary'             => 'Toś ten specialny bok pokazujo boki, na kótarež wót žednogo drugego boka se njewótkazujo. Toś te wósyrośone boki njejsu togodla žycone abo snaź teke njedowěrne, dokulaž njamgu se žednje pśez normalnu nawigaciju pśez {{SITENAME}} wótwołaś.',
'lonelypagestext'                 => 'Na slědujuce boki njeeksistěrujo žeden wótkaz wót drugich bokow toś teje wiki.',
'uncategorizedpages'              => 'Boki bźez kategorijow',
'uncategorizedpages-summary'      => 'Toś ten specialny bok pokazujo wšykne hyšći njekategorizěrowane boki.',
'uncategorizedcategories'         => 'Njekategorizěrowane kategorije',
'uncategorizedcategories-summary' => 'Toś ten specialny bok pokazujo wšykne hyšći njekategorizěrowane kategorije.',
'uncategorizedimages'             => 'Njekategorizěrowane dataje.',
'uncategorizedimages-summary'     => 'Toś ten specialny bok pokazujo wšykne hyšći njekategorizěrowane dataje.',
'uncategorizedtemplates'          => 'Njekategorizěrowane šablony',
'uncategorizedtemplates-summary'  => 'Toś ten specialny bok pokazujo wšykne njekategorizěrowane pśedłogi.',
'unusedcategories'                => 'Njewužywane kategorije',
'unusedimages'                    => 'Njewužywane dataje',
'popularpages'                    => 'Woblubowane boki',
'wantedcategories'                => 'Póžedane kategorije',
'wantedcategories-summary'        => 'Toś ten specialny bok nalicujo kategorije, kótarež w bokach se wužywaju, kótarež ale njejsu ako kategorije se załožyli.',
'wantedpages'                     => 'Póžedane boki',
'wantedpages-summary'             => 'Toś ten specialny bok nalicujo wšykne boki, kótarež hyšći njeeksistěruju, na kótarež ale wót eksistěrujucych bokow se wótkazuju.',
'mostlinked'                      => 'Nejcesćej zalinkowane boki',
'mostlinked-summary'              => 'Toś ten specialny bok pokazujo njewótwisujucy wót mjenjowego ruma wšykne wósebnje cesto zalinkowane boki.',
'mostlinkedcategories'            => 'Nejcesćej wužywane kategorije',
'mostlinkedcategories-summary'    => 'Toś ten specialny bok pokazujo nejcesćej wužywane kategorije.',
'mostlinkedtemplates'             => 'Nejcesćej wužywane šablony',
'mostlinkedtemplates-summary'     => 'Toś ten specialny bok wótkazujo na nejcesćej wužywane pśedłogi.',
'mostcategories'                  => 'Boki z nejwěcej kategorijami',
'mostcategories-summary'          => 'Toś ten specialny bok pokazujo wósebnje cesto kategorizěrowane boki.',
'mostimages'                      => 'Nejcesćej wužywane dataje',
'mostimages-summary'              => 'Toś ten specialny bok pokazujo lisćinu nejcesćej wužywanych datajow.',
'mostrevisions'                   => 'Boki z nejwěcej wersijami',
'mostrevisions-summary'           => 'Toś ten specialny bok pokazujo lisćinu bokow z nejwěcej wobźěłanjami.',
'allpages'                        => 'Wšykne boki',
'allpages-summary'                => 'Toś ten specialny bok nalicujo boki {{SITENAME}} wót A do Ź. Sortěrujo se alfabetiski, nejžpjerwjej licby, pón wjelike pismiki, małe pismiki a pón wósebne znamuška.',
'prefixindex'                     => 'Wšykne nastawki (z prefiksom)',
'prefixindex-summary'             => 'Toś ten specialny bok pokazujo wšykne boki ze zapódanym prefiksom. Output móžo se na jaden mjenjowy rum reducěrowaś.',
'shortpages'                      => 'Krotke nastawki',
'shortpages-summary'              => 'Toś ta lisćina pokazujo nejkrotše boki w głownem mjenjowem rumje. Lice se znamuška teksta, tak ako we wobźěłańskem woknyšku se pokažo, pótakem we wiki-syntaksy a mimo wopśimjeśa zawězanych pśedłogow. Zakład licenja jo pó UTF-8 koděrowany tekst, pó kótaremž płaśe na pś. serbske pismiki z diakritiskimi znamuškami ako dwa znamuška.',
'longpages'                       => 'Dłujke nastawki',
'longpages-summary'               => 'Toś ta lisćina pokazujo nejdlěše boki w głownem mjenjowem rumje. Lice se znamuška teksta, tak ako we wobźěłańskem woknyšku se pokažo, pótakem we wiki-syntaksy a mimo wopśimjeśa zawězanych pśedłogow. Zakład licenja jo pó UTF-8 koděrowany tekst, pó kótaremž płaśe na pś. serbske pismiki z diakritiskimi znamuškami ako dwa znamuška.',
'deadendpages'                    => 'Nastawki bźez wótkazow',
'deadendpages-summary'            => 'Toś ten specialny bok pokazujo lisćinu bokow, kótarež njewopśimjeju wótkaze na druge boki abo jano na hyšći njeeksistěrujuce boki.',
'deadendpagestext'                => 'Slědujuce boki njewótkazuju na druge boki toś teje wiki.',
'protectedpages'                  => 'Šćitane boki',
'protectedpages-summary'          => 'Toś ten specialny bok pokazujo wšykne boki, kótarež njamgu se mimo wósebnych pšawow wobźěłowaś a pśesuwaś.',
'protectedpagestext'              => 'Slědujuce boki njamgu se mimo wósebnych pšawow wobźěłaś resp. pśesuwaś',
'protectedpagesempty'             => 'Z toś tymi parametrami njejsu tuchylu žedne boki šćitane.',
'listusers'                       => 'Lisćina wužywarjow',
'listusers-summary'               => "Toś ten specialny bok pokazujo wšyknych registrěrowanych wužywarjow; cełkowna licba móžo [[Special:Statistics|how se wobglědowaś]]. Pśez wuzwólowańske pólo ''kupka'' jo móžno, wótpšašowanje na wěste wužywarske kupki wobgranicowaś.",
'specialpages'                    => 'Specialne boki',
'specialpages-summary'            => 'Toś ten bok pokazujo pśeglěd wšyknych specialnych bokow. Wóni awtomatiski se generěruju a njamgu se wobźěłowaś.',
'spheading'                       => 'Specialne boki za wšych wužywarjow',
'restrictedpheading'              => 'Specialne boki za administratorow',
'newpages'                        => 'Nowe boki',
'newpages-summary'                => 'Toś ten specialny bok nalicujo wšykne boki, kótarež su nastali w slědnych 30 dnjach. Jo móžno, output na jaden mjenjowy rum a/abo wužywarja wobgranicowaś.',
'newpages-username'               => 'Wužywarske mě:',
'ancientpages'                    => 'Nejstarše boki',
'ancientpages-summary'            => 'Toś ten specialny bok pokazujo lisćinu bokow, kótarež nejdlěj njejsu wěcej se wobźěłali.',
'intl'                            => 'Mjazyrěcne wótkaze',
'move'                            => 'Pśesunuś',
'movethispage'                    => 'Bok pśesunuś',
'unusedimagestext'                => 'Pšosym glědajśo na to, až ewtl. druge wikije někotare z toś tych datajow wužywaju.',
'unusedcategoriestext'            => 'Toś ten specialny bok pokazujo wšykne njekategorizěrowane kategorije.',
'notargettitle'                   => 'Žeden celowy bok njejo zapódany.',
'notargettext'                    => 'Njejsy zapódał celowy bok, źož dejała funkcija se wugbaś.',
'pager-newer-n'                   => '{{PLURAL:$1|nowšy 1|nowšej $1|nowše $1|nowšych $1}}',
'pager-older-n'                   => '{{PLURAL:$1|staršy 1|staršej $1|starše $1|staršych $1}}',

# Book sources
'booksources'               => 'Pytanje pó ISBN',
'booksources-summary'       => 'Na toś tom specialnem boce móžoš ISBN zapódaś a dostanjoš pón lisćinu z online-katalogami a móžnosćami, źož móžoš pytanu ISBN dostaś. Wězace smužki abo proznoty mjaz ciframi njegraju za pytanje žednu rolu.',
'booksources-search-legend' => 'Knigłowe žrědła pytaś',
'booksources-go'            => 'Pytaś',
'booksources-text'          => 'To jo lisćina z wótkazami na internetowe boki, kótarež pśedawaju nowe a trjebane knigły. Tam mógu teke dalšne informacije wó knigłach byś. {{SITENAME}} njezwisujo góspodarsce z žednym z toś tych póbitowarjow.',

'categoriespagetext' => 'Slědujuce kategorije eksistěrujuju w(e) {{SITENAME}}:',
'data'               => 'Daty',
'userrights'         => 'Zastojanje wužywarskich pšawow',
'groups'             => 'Wužywarske kupki',
'alphaindexline'     => '$1 do $2',
'version'            => 'Wersija',

# Special:Log
'specialloguserlabel'  => 'Wužywaŕ:',
'speciallogtitlelabel' => 'Nadpismo:',
'log'                  => 'Protokole',
'all-logs-page'        => 'Wšykne protokole',
'log-search-legend'    => 'Protokole pytaś',
'log-search-submit'    => 'Start',
'alllogstext'          => 'To jo kombiněrowana pokazka wšyknych w(e) {{SITENAME}} wjeźonych log-lisćinow. Pśez wuzwólenje typa log-lisćiny, wužywarja abo mjenja boka móžo se output wobgranicowaś.',
'logempty'             => 'Žedne se góźece zapise njeeksistěruju.',
'log-title-wildcard'   => 'Pytaś nadpismo, kótarež zachopijo z ...',

# Special:Allpages
'nextpage'          => 'Slědujucy bok ($1)',
'prevpage'          => 'Pśedchadny bok ($1)',
'allpagesfrom'      => 'Boki pokazaś wót:',
'allarticles'       => 'Wšykne nastawki',
'allinnamespace'    => 'Wšykne boki (mjenjowy rum: $1)',
'allnotinnamespace' => 'Wšykne boki (nic w mjenjowem rumje $1)',
'allpagesprev'      => 'Pśedchadne',
'allpagesnext'      => 'Slědujuce',
'allpagessubmit'    => 'Start',
'allpagesprefix'    => 'Boki pokazaś (z prefiksom):',
'allpagesbadtitle'  => 'Zapódane mě boka njejo płaśece: Jo móžno, až ma pśedstajonu rěcnu resp. interwikijowu krotceńku abo wopśimjejo jadno abo wěcej znamuškow, kótarež njamgu se za mjenja bokow wužywaś.',
'allpages-bad-ns'   => 'Mjenjowy rum „$1“ w {{SITENAME}} njeeksistěrujo.',

# Special:Listusers
'listusersfrom'      => 'Pokaž wužywarjow wót:',
'listusers-submit'   => 'Pokazaś',
'listusers-noresult' => 'Žeden wužywaŕ njejo se namakał.',

# E-mail user
'mailnologin'     => 'Njejo móžno e-mailku pósłaś.',
'mailnologintext' => 'Dejš byś [[{{ns:special}}:Userlogin|pśizjawjony]] a měś [[{{ns:special}}:Confirmemail|wobkšuśonu]] e-mailowu adresu, aby mógał drugim wužywarjam e-maile pósłaś.',
'emailuser'       => 'Toś tomu wužywarjeju e-mail pósłaś',
'emailpage'       => 'E-mail wužywarjeju',
'emailpagetext'   => 'Jo-lic toś ten wužywaŕ płaśecu e-mailowu adresu zapódał, móžoš jomu z pomocu dołojcnego formulara e-mail pósłaś. Ako wótpósłaŕ pódajo se e-mailowa adresa z twójich nastajenjow, aby mógał wužywaŕ wótegroniś.',
'usermailererror' => 'E-mailowy objekt jo zmólku wrośił.',
'defemailsubject' => '{{SITENAME}} e-mail',
'noemailtitle'    => 'E-mailowa adresa felujo.',
'noemailtext'     => 'Toś ten wužywaŕ njama aktualnu emailowu adresu, abo njoco dostawaś powěsći wót drugich wužywarjow.',
'emailfrom'       => 'Wót',
'emailto'         => 'Na',
'emailsubject'    => 'Nastupanje',
'emailmessage'    => 'Powěsć',
'emailsend'       => 'Wótpósłaś',
'emailccme'       => 'Pósćel mě kopiju e-maila.',
'emailccsubject'  => 'Kopija Twójeje powěsći na $1: $2',
'emailsent'       => 'e-mail wótposłany',
'emailsenttext'   => 'Twój e-mail jo se wótpósłał.',

# Watchlist
'watchlist'            => 'Wobglědowańka',
'mywatchlist'          => 'móje wobglědowańka',
'watchlistfor'         => "(za wužywarja '''$1''')",
'nowatchlist'          => 'Žedne zapise w Twójich wobglědowańkach.',
'watchlistanontext'    => 'Dejš se $1, aby mógał swóje wobglědowańka wiźeś abo zapise w nich wobźěłaś.',
'watchnologin'         => 'Njepśizjawjony(a)',
'watchnologintext'     => 'Musyš byś [[Special:Userlogin|pśizjawjony]], aby mógał swóje wobglědowańka wobźěłaś.',
'addedwatch'           => 'Jo k wobglědowańkam se dodało',
'addedwatchtext'       => 'Bok „$1“ jo k twójim [[Special:Watchlist|wobglědowańkam]] se dodał.

Pózdźejšne změny na toś tom boku a w pśisłušecej diskusiji se tam nalicuju a w pśeglěźe [[Special:Recentchanges|slědnych změnow]] tucnje wóznamjeniju. 

Coš-lic bok zasej z twójich wobglědowańkow wulašowaś, kliknij na wótpowědujucem boce na "dalej njewobglědowaś".',
'removedwatch'         => 'Jo z wobglědowańkow se wulašowało',
'removedwatchtext'     => 'Bok „$1“ jo z twójich wobglědowańkow wulašowany.',
'watch'                => 'Wobglědowaś',
'watchthispage'        => 'Bok wobglědowaś',
'unwatch'              => 'Dalej njewobglědowaś',
'unwatchthispage'      => 'Dalej njewobglědowaś',
'notanarticle'         => 'To njejo žeden nastawk',
'watchnochange'        => 'Žeden wót tebje wobglědowany bok njejo se we wótpowědujucem casu wobźěłał.',
'watchlist-details'    => 'Wobglědujoš {{PLURAL:$1|$1 bok|$1 boka|$1 boki}}.',
'wlheader-enotif'      => '* E-mailowe powěsće su aktiwizěrowane.',
'wlheader-showupdated' => "* Boki, kótarež su wót twójogo slědnego woglěda se změnili, pokazuju se '''tucnje'''.",
'watchmethod-recent'   => 'Kontrolěrowanje slědnych wobźěłanjow we wobglědowańkach',
'watchmethod-list'     => 'Pśepytanje wobglědowańkow za slědnymi wobźěłanjami',
'watchlistcontains'    => 'Twóje wobglědowańka wopśimjeju $1 {{PLURAL:$1|bok|boka|boki}}.',
'iteminvalidname'      => 'Problem ze zapisom „$1“, njepłaśece mě.',
'wlnote'               => "{{PLURAL:$1|Slědujo slědna změna|slědujotej '''$1''' slědnej změnje|slěduju slědne '''$1''' změny}} {{PLURAL:$2|slědneje góźiny|slědneju '''$2''' góźinowu|slědnych '''$2''' góźinow}}.",
'wlshowlast'           => 'Pokaž změny slědnych $1 góźinow, $2 dnjow abo $3 (w slědnych 30 dnjach).',
'watchlist-show-bots'  => 'Wobźěłanja awtomatiskich programow (botow) pokazaś',
'watchlist-hide-bots'  => 'Wobźěłanja awtomatiskich programow (botow) schowaś',
'watchlist-show-own'   => 'Móje wobźěłanja pokazaś',
'watchlist-hide-own'   => 'Móje wobźěłanja schowaś',
'watchlist-show-minor' => 'Pokazaś małe wobźěłanja',
'watchlist-hide-minor' => 'Schowaś małe wobźěłanja',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Wobglědowaś …',
'unwatching' => 'Njewobglědowaś …',

'enotif_mailer'                => '{{SITENAME}} e-mailowe powěsći',
'enotif_reset'                 => 'Wšykne boki ako woglědane markěrowaś',
'enotif_newpagetext'           => 'To jo nowy bok.',
'enotif_impersonal_salutation' => '{{SITENAME}}-wužywaŕ',
'changed'                      => 'změnjone',
'created'                      => 'wutwórjone',
'enotif_subject'               => '[{{SITENAME}}] Bok "$PAGETITLE" jo se wót $PAGEEDITOR $CHANGEDORCREATED',
'enotif_lastvisited'           => 'Wšykne změny na jadno póglědnjenje: $1',
'enotif_lastdiff'              => 'Za toś tu změnu glědaj w $1.',
'enotif_anon_editor'           => 'anonymny wužywaŕ $1',
'enotif_body'                  => 'Luby/a $WATCHINGUSERNAME,

{{SITENAME}} bok "$PAGETITLE" jo se wót $PAGEEDITOR $PAGEEDITDATE $CHANGEDORCREATED.

Aktualna wersija: $PAGETITLE_URL

$NEWPAGE

Zespominanje wobźěłarja: $PAGESUMMARY $PAGEMINOREDIT

Kontakt z wobźěłarjom:
E-Mail: $PAGEEDITOR_EMAIL
Wiki: $PAGEEDITOR_WIKI

Dalšne e-mailowe powěsći se tak dłujko njepósćelu, až njejsy bok zasej woglědał. W swójich wobglědowańkach móžoš wšykne powěsćowe markery zasej slědk stajiś.

             Twój pśijaśelny {{SITENAME}} powěsćowy system
-- 
Aby nastajenja twójich wobglědowańkow změnił, woglědaj: {{fullurl:Special:Watchlist/edit}}',

# Delete/protect/revert
'deletepage'                  => 'Bok wulašowaś',
'confirm'                     => 'Wobkšuśiś',
'excontent'                   => "wopśimjeśe jo było: '$1'",
'excontentauthor'             => "wopśimjeśe jo było: '$1' (a jadnučki wobźěłaŕ jo był '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'               => "Wopśimjeśe do wuprozdnjenja jo było: '$1'",
'exblank'                     => 'bok jo był prozny',
'confirmdelete'               => 'wulašowanje wobkšuśiś',
'deletesub'                   => '(„$1“ se wulašujo)',
'historywarning'              => 'Glědaj! Bok, kótaryž coš wulašowaś, ma stawizny:',
'confirmdeletetext'           => 'Coš bok abo dataju ze wšyknymi pśisłušnymi wersijami na pśecej wulašowaś. Pšosym wobkšuś, až sy se wědobny, kake konsekwency móžo to měś, a až jadnaš pó [[{{MediaWiki:Policy-url}}|směrnicach]].',
'actioncomplete'              => 'Akcija jo se wugbała.',
'deletedtext'                 => '„$1“ jo se wulašował(a/o). W $2 namakajoš lisćinu slědnych wulašowanjow.',
'deletedarticle'              => 'wulašowane "[[$1]]"',
'dellogpage'                  => 'log-lisćina wulašowanjow',
'dellogpagetext'              => 'How jo log-lisćina wulašowanych bokow a datajow.',
'deletionlog'                 => 'log-lisćina wulašowanjow',
'reverted'                    => 'Nawrośone na staršu wersiju',
'deletecomment'               => 'Pśicyna wulašowanja',
'deleteotherreason'           => 'Druga/pśidatna pśicyna:',
'deletereasonotherlist'       => 'Druga pśicyna',
'rollback'                    => 'Wobźěłanja slědk wześ',
'rollback_short'              => 'anulěrowaś',
'rollbacklink'                => 'anulěrowaś',
'rollbackfailed'              => 'Slědkwześe njejo se raźiło.',
'cantrollback'                => 'Njejo móžno změnu slědk wześ, slědny pśinosowaŕ jo jadnučki awtor boka.',
'alreadyrolled'               => "Njejo móžno slědnu změnu w nastawku [[:$1]] wót [[User:$2|$2]] <span style='font-size: smaller'>([[User_talk:$2|diskusija]], 
[[{{ns:special}}:Contributions/$2|pśinoski]])</span> slědk wześ, dokulaž jo mjaztym drugi wužywaŕ nastawk pśeměnił.<br />Slědnu změnu jo wugbał [[User:$3|$3]] <span style='font-size: smaller'>([[User_talk:$3|diskusija]])</span>.",
'editcomment'                 => 'Komentar ku slědnej změnje jo był: „<i>$1</i>“.', # only shown if there is an edit comment
'revertpage'                  => 'Změny wót [[User:$2|$2]] ([[{{ns:special}}:Contributions/$2|pśinoski]]) su se slědk wzeli a slědna wersija wót $1 jo se nawrośiła.',
'rollback-success'            => 'Změny wót $1 su se slědk wzeli a slědna wersija wót $2 jo zasej se nawrośiła.',
'sessionfailure'              => 'Problem z twójim wužywarskim pósejźenim jo se wujawił.
Wěstoty dla jo akcija se pśetergnuła, aby se zadorało wopacnemu pśirědowanjoju twójeje změny drugemu wužywarjeju.
Pšosym nawroś se na bok, wót kótaregož sy pśišeł a wopytaj hyšći raz.',
'protectlogpage'              => 'Log-lisćina šćitanych bokow.',
'protectlogtext'              => 'To jo log-lisćina šćitanych bokow. Glědaj do [[{{ns:special}}:Protectedpages|lisćiny šćitanych bokow]], aby wiźeł wšykne aktualnje šćitane boki.',
'protectedarticle'            => 'Bok „[[$1]]“ jo se šćitał.',
'modifiedarticleprotection'   => 'Šćitanska rownina za „[[$1]]“ jo se změniła.',
'unprotectedarticle'          => 'Šćit za „[[$1]]“ jo se wótpórał.',
'protectsub'                  => '(Šćit boka „$1“ změniś)',
'confirmprotect'              => 'Šćitanje wobkšuśiś',
'protectcomment'              => 'Komentar:',
'protectexpiry'               => 'cas wótběžy:',
'protect_expiry_invalid'      => 'Zapódany cas jo njekorektny.',
'protect_expiry_old'          => 'Zapódany cas jo wótběžał.',
'unprotectsub'                => '(Šćitanje boka „$1“ se wótpóra)',
'protect-unchain'             => 'Šćit pśed pśesuwanjom změniś',
'protect-text'                => "How móžoš status šćita boka '''$1''' wobglědowaś a jen změniś.",
'protect-locked-blocked'      => 'Njamóžoš status šćita togo boka změniś, dokulaž jo twójo wužywarske konto se blokěrowało. How su aktualne nastajenja šćita za bok <strong>„$1“:</strong>.',
'protect-locked-dblock'       => 'Datowa banka jo zamknjona a toś njejo móžno šćit boka změniś. How su aktualne nastajenja šćita za bok <strong>„$1“:</strong>.',
'protect-locked-access'       => 'Wašo wužywarske konto njama notne pšawa za změnu šćita toś togo boka. How su aktualne nastajenja šćita boka <strong>„$1“:</strong>.',
'protect-cascadeon'           => 'Toś ten bok jo tuchylu šćitany, dokulaž jo zawězany do {{PLURAL:$1|slědujucego boka|slědujuceju bokowu|slědujucych bokow}}, źož kaskadowy šćit jo aktiwěrowany. Status šćita móžo se za toś ten bok změniś, to ale njewówliwujo kaskadowy šćit:',
'protect-default'             => '(standard)',
'protect-fallback'            => 'Slědujuce pšawo jo notne: „$1“.',
'protect-level-autoconfirmed' => 'Za njeregistrěrowane wužywarje blokěrowaś',
'protect-level-sysop'         => 'Jano administratory',
'protect-summary-cascade'     => 'kaskaděrujucy',
'protect-expiring'            => 'kóńcy $1 (UTC)',
'protect-cascade'             => 'Kaskaděrujucy šćit – wšykne pśedłogi, kótarež su zawězane do toś togo boka, tejerownosći se zamknu.',
'restriction-type'            => 'Status šćita',
'restriction-level'           => 'Rownina šćita:',
'minimum-size'                => 'Minimalna wjelikosć (bytes)',
'maximum-size'                => 'maksimalna wjelikosć',
'pagesize'                    => '(byty)',

# Restrictions (nouns)
'restriction-edit'   => 'wobźěłaś',
'restriction-move'   => 'pśesunuś',
'restriction-create' => 'Natwóriś',

# Restriction levels
'restriction-level-sysop'         => 'połnje šćitane',
'restriction-level-autoconfirmed' => 'poł šćitane',
'restriction-level-all'           => 'wšykne',

# Undelete
'undelete'                     => 'Wulašowane boki nawrośiś',
'undeletepage'                 => 'Wulašowane boki pokazaś a nawrośiś.',
'viewdeletedpage'              => 'Wulašowane boki pokazaś',
'undeletepagetext'             => 'Slědujuce boki su se wulašowali a mógu wót administratorow zasej se nawrośiś:',
'undeleteextrahelp'            => '* Coš-lic bok ze wšyknymi wersijami nawrośiś, zapódaj pśicynu a tłoc na "nawrośiś".
* Coš-lic jano wěste wersije nawrośiś, wuzwól je, zapódaj pśicynu a tłoc na "nawrośiś".
* "Pśetergnuś" wuprozdnijo komentarowe pólo a wótpórajo wšykne markěrowanja pśi wersijach.',
'undeleterevisions'            => '$1 {{PLURAL:$1|wersija archiwěrowana|wersiji archiwěrowanej|wersije archiwěrowane}}',
'undeletehistory'              => 'Nawrośijoš-lic bok, nawrośiju se wšykne wersije do stawiznow.
Jo-lic se wót casa wulašowanja nowy bok ze samskim mjenim wutwórił, zarěduju se nawrośone wersije chronologiski do stawiznow a aktualna wersija se awtomatiski njenarownajo.
Mysli teke na to, až ewtl. wobgranicowanja datajowych wersijow pśi nawrośenju se zgubiju.',
'undeleterevdel'               => 'Nawrośenje njejo móžne, gaž jo aktualnjejša wersija (teke pó źěłach) schowana. W takich padach dejš nejnowše wulašowane wersije demarkěrowaś abo schowanje wótpóraś. Wersije datajow, za kótarež njamaš pšawa, aby je pokazał, se njenawrośiju.',
'undeletehistorynoadmin'       => 'Toś ten bok jo se wulašował. Pśicyna wulašowanja pokazujo se w zespominanju. Tam stoje teke nadrobnosći wó wužywarjach, kótarež su bok pśed wulašowanim wobźěłali. Aktualny tekst toś tych wulašowanych wersijow jo jano administratoram pśistupny.',
'undelete-revision'            => 'Wulašowane wersije wót $1 - $2, $3:',
'undeleterevision-missing'     => 'Njepłaśeca abo felujuca wersija. Snaź jo link wopacny abo wersija jo z archiwa se nawrośiła resp. wulašowała.',
'undelete-nodiff'              => 'Žedne něgajšne wersije',
'undeletebtn'                  => 'Wulašowaś',
'undeletereset'                => 'Slědk wześ',
'undeletecomment'              => 'Wobtwarźenje:',
'undeletedarticle'             => 'bok „[[$1]]“ nawrośony',
'undeletedrevisions'           => '{{PLURAL:$1|1 wersija jo se nawrośiła|$1 wersiji stej se nawrośiłej|$1 wersije su se nawrośili}}.',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 wersija|$1 wersiji|$1 wersije}} a {{PLURAL:$2|1 dataja|$2 dataji|$2 dataje}} {{PLURAL:$2|jo se nawrośiła|stej se nawrośiłej|su se nawrośili}}.',
'undeletedfiles'               => '{{PLURAL:$1|1 dataja jo se nawrośiła|$1 dataji stej se nawrośiłej|$1 dataje su se nawrośili}}.',
'cannotundelete'               => 'Nawrośenje njejo se zglucyło; něchten drugi jo bok južo nawrośił.',
'undeletedpage'                => "Bok '''$1''' jo se nawrośił.

W [[Special:Log/delete|log-lisćinje wulašowanjow]] namakajoš pśeglěd wulašowanych a nawrośonych bokow.",
'undelete-header'              => 'Gano wulašowane boki wiźiš w [[{{ns:special}}:Log/delete|log-lisćinje wulašowanjow]].',
'undelete-search-box'          => 'Wulašowane boki pytaś',
'undelete-search-prefix'       => 'Pokaž boki, kótarež zachopiju z:',
'undelete-search-submit'       => 'Pytaś',
'undelete-no-results'          => 'W archiwje wulašowanych bokow žeden bok pytanemu słowoju njewótpowědujo.',
'undelete-filename-mismatch'   => 'Njejo móžno było, datajowu wersiju z casowym kołkom $1 nawrośiś: Datajowej mjeni se njemakatej.',
'undelete-bad-store-key'       => 'Njejo móžno było, wersiju z casowym kołkom $1 nawrośiś: Dataja južo pśed wulašowanim njejo eksistěrowała.',
'undelete-cleanup-error'       => 'Zmólka pśi wulašowanju njewužywaneje archiwneje dataje $1.',
'undelete-missing-filearchive' => 'Njejo móžno, archiwnu dataju ID $1 nawrośiś. Wóna južo w datowej bance njejo. Snaź jo južo raz se nawrośiła.',
'undelete-error-short'         => 'Zmólka pśi nawrośenju dataje: $1',
'undelete-error-long'          => 'Zmólki pśi nawrośenju dataje:

$1',

# Namespace form on various pages
'namespace'      => 'Mjenjowy rum:',
'invert'         => 'Wuběr wobrośiś',
'blanknamespace' => '(Nastawki)',

# Contributions
'contributions' => 'Wužywarske pśinoski',
'mycontris'     => 'Móje pśinoski',
'contribsub2'   => 'Za $1 ($2)',
'nocontribs'    => 'Za toś te kriterije njejsu žedne změny se namakali.',
'ucnote'        => 'How su slědne <b>$1</b> změny wužywarja w slědnych <b>$2</b> dnjach.',
'uclinks'       => 'Glědaj slědne $1 změny; glědaj slědne $2 dny.',
'uctop'         => ' (aktualny)',
'month'         => 'wót mjaseca (a jěsnjej):',
'year'          => 'wót lěta (a jěsnjej):',

'sp-contributions-newbies'     => 'Pśinoski jano za nowych wužywarjow pokazaś',
'sp-contributions-newbies-sub' => 'Za nowackow',
'sp-contributions-blocklog'    => 'Log-lisćina blokěrowanjow',
'sp-contributions-search'      => 'Pśinoski pytaś',
'sp-contributions-username'    => 'IP-adresa abo wužywarske mě:',
'sp-contributions-submit'      => 'Pytaś',

'sp-newimages-showfrom' => 'Nowe wobraze wót $1 pokazaś',

# What links here
'whatlinkshere'       => 'Wótkaze na toś ten bok',
'whatlinkshere-title' => 'Boki, kótarež wótkazuju na $1',
'whatlinkshere-page'  => 'bok:',
'linklistsub'         => '(Lisćina wótkazow)',
'linkshere'           => "Toś te boki wótkazuju na '''„[[:$1]]“''':",
'nolinkshere'         => "Žedne boki njewótkazuju na '''[[:$1]]'''.",
'nolinkshere-ns'      => "Žedne boki we wubranem mjenjowem rumje njewótkazuju na '''[[:$1]]'''.",
'isredirect'          => 'dalejpósrědnjujucy bok',
'istemplate'          => 'zawězanje šablony',
'whatlinkshere-prev'  => '{{PLURAL:$1|zachadny|zachadnej|zachadne $1}}',
'whatlinkshere-next'  => '{{PLURAL:$1|pśiducy|pśiducej|pśiduce $1}}',
'whatlinkshere-links' => '← wótkaze',

# Block/unblock
'blockip'                     => 'wužywarja blokěrowaś',
'blockiptext'                 => 'Wužywaj slědujucy formular, jolic až coš wěstej IP-adresy abo konkretnemu wužywarjeju pśistup znjemóžniś. Take dejało se pó [[{{MediaWiki:Policy-url}}|směrnicach]] jano staś, aby se wandalizmoju zadorało. Pšosym zapódaj pśicynu za twójo blokěrowanje (na pś. mógu se citěrowaś konkretne boki, źo jo se wandalěrowało).',
'ipaddress'                   => 'IP-adresa',
'ipadressorusername'          => 'IP-adresa abo wužywarske mě',
'ipbexpiry'                   => 'Cas blokěrowanja:',
'ipbreason'                   => 'Pśicyna',
'ipbreasonotherlist'          => 'Druga pśicyna',
'ipbreason-dropdown'          => '*powšykne pśicyny blokěrowanja
** pódawanje njepšawych informacijow
** wulašowanje wopśimjeśa bokow
** pódawanje spamowych eksternych wótkazow  
** pisanje głuposćow na bokach
** pśestupjenje zasady "žedne wósobinske atakěrowanja"
** złowólne wužywanje wjele wužywarskich kontow
** njekorektne wužywarske mě',
'ipbanononly'                 => 'Jano anonymnych wužywarjow blokěrowaś',
'ipbcreateaccount'            => 'Twórjenje wužywarskich kontow znjemóžniś',
'ipbemailban'                 => 'pósłanje e-mailow znjemóžniś',
'ipbenableautoblock'          => 'Awtomatiske blokěrowanje slědneje wót togo wužywarja wužywaneje IP-adresy a wšyknych slědujucych adresow, wót kótarychž wopytajo boki wobźěłaś.',
'ipbsubmit'                   => 'Togo wužywarja blokěrowaś.',
'ipbother'                    => 'Drugi cas:',
'ipboptions'                  => '1 góźina:1 hour,2 góźinje:2 hours, 6 góźiny:6 hours,1 źeń:1 day,2 dnja:2 days,3 dny:3 days,1 tyźeń:1 week,2 tyźenja:2 weeks,3 tyźenje:3 weeks,1 mjasec:1 month,2 mjaseca:2 month,3 mjasece:3 months,6 mjasecy:6 months,1 lěto:1 year,na nimjer:indefinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'drugi',
'ipbotherreason'              => 'Hynakša/dalšna pśicyna:',
'ipbhidename'                 => 'Wužywarske mě/IP-adresu w log-lisćinje blokěrowanjow, lisćinje aktiwnych blokěrowanjow a zapisu wužywarjow schowaś',
'badipaddress'                => 'IP-adresa jo njekorektna',
'blockipsuccesssub'           => 'Wuspěšnje blokěrowane',
'blockipsuccesstext'          => 'Wužywaŕ/IP-adresa [[{{ns:special}}:Contributions/$1|$1]] jo se blokěrował(a) a akcija jo w [[{{ns:special}}:Log/block|log-lisćinje blokěrowanjow]] se protokolěrowała. Glědaj do [[{{ns:special}}:Ipblocklist|lisćiny aktiwnych blokěrowanjow]].',
'ipb-edit-dropdown'           => 'Pśicyny blokěrowanja wobźěłaś',
'ipb-unblock-addr'            => '$1 dopušćiś',
'ipb-unblock'                 => 'Wužywarske mě abo IP-adresu dopušćiś',
'ipb-blocklist-addr'          => 'Aktualne blokěrowanja za „$1“ pokazaś',
'ipb-blocklist'               => 'Wšykne aktualne blokěrowanja pokazaś',
'unblockip'                   => 'Wužywarja dopušćiś',
'unblockiptext'               => 'Z pomocu dołojcnego formulara móžotej IP-adresa abo wužywaŕ zasej se dopušćiś.',
'ipusubmit'                   => 'adresu dopušćiś',
'unblocked'                   => 'Wužywaŕ [[User:$1|$1]] jo zasej se dopušćił.',
'unblocked-id'                => '$1 jo se dopušćił(a).',
'ipblocklist'                 => 'Lisćina blokěrowanych IP-adresow a wužywarskich mjenjow',
'ipblocklist-legend'          => 'Blokěrowanego wužywarja pytaś',
'ipblocklist-username'        => 'Wužywarske mě abo IP-adresa:',
'ipblocklist-summary'         => 'Toś ten specialny bok zapisujo - dodatnje k log-lisćinje blokěrowanjow, kótaraž zapisujo wšykne manualne blokěrowanja a dopušćenja - "aktualnje" zawoborane wužywarje a IP-adresy, inklusiwnje awtomatiski blokěrowane IP-adresy w anonymizěrowanej formje.',
'ipblocklist-submit'          => 'Pytaś',
'blocklistline'               => '$1, $2 jo blokěrował $3 (až do $4)',
'infiniteblock'               => 'njewobgranicowany',
'expiringblock'               => 'kóńcy $1',
'anononlyblock'               => 'jano anonymne',
'noautoblockblock'            => 'awtomatiske blokěrowanje znjemóžnjone',
'createaccountblock'          => 'wutwórjenje wužywarskich kontow znjemóžnjone',
'emailblock'                  => 'Pósłanje e-mailow jo se blokěrowało.',
'ipblocklist-empty'           => 'Lisćina jo prozna.',
'ipblocklist-no-results'      => 'Póžedana IP-Adresa abo wužywarske mě njejstej blokěrowanej.',
'blocklink'                   => 'blokěrowaś',
'unblocklink'                 => 'dopušćiś',
'contribslink'                => 'pśinoski',
'autoblocker'                 => 'Twója IP-adresa jo awtomatiski se blokěrowała, dokulaž jo ju wužywał „$1“. Pśicyna blokěrowanja wužywarja „$1“ jo: „$2“.',
'blocklogpage'                => 'Log-lisćina blokěrowanjow',
'blocklogentry'               => '[[$1]] blokěrujo se na $2 $3',
'blocklogtext'                => 'To jo log-lisćina blokěrowanjow a dopušćenjow. IP-adresy, ako su awtomatiski se blokěrowali, se njepokažu. Na boce [[{{ns:special}}:Ipblocklist|{{int:ipblocklist}}]] jo móžno, akualne blokěrowanja pśeglědowaś.',
'unblocklogentry'             => 'jo $1 zasej dopušćił',
'block-log-flags-anononly'    => 'jano anonymne',
'block-log-flags-nocreate'    => 'stwórjenje konta jo se znjemóžniło',
'block-log-flags-noautoblock' => 'awtomatiske blokěrowanje jo deaktiwěrowane',
'block-log-flags-noemail'     => 'e-mailowanje jo blokěrowane',
'range_block_disabled'        => 'Móžnosć administratora, blokěrowaś cełe adresowe rumy, njejo aktiwěrowana.',
'ipb_expiry_invalid'          => 'Pódany cas jo njepłaśecy.',
'ipb_already_blocked'         => '"$1" jo južo blokěrowany/-a',
'ipb_cant_unblock'            => 'Zmólka: Blokěrowańska ID $1 njejo se namakała. Blokěrowanje jo było južo wótpórane.',
'ip_range_invalid'            => 'Njepłaśecy wobłuk IP-adresow.',
'blockme'                     => 'blokěruj mě',
'proxyblocker'                => 'Blokěrowanje proxy',
'proxyblocker-disabled'       => 'Toś ta funkcija jo znjemóžnjona.',
'proxyblockreason'            => 'Twója IP-adresa jo se blokěrowała, dokulaž jo wócynjony proxy. Pšosym kontaktěruj swójogo seśowego providera abo swóje systemowe administratory a informěruj je wó toś tom móžnem wěstotnem problemje.',
'proxyblocksuccess'           => 'Gótowe.',
'sorbsreason'                 => 'Twója IP-adresa jo w DNSBL w(e) {{SITENAME}} zapisana ako wócynjony proxy.',
'sorbs_create_account_reason' => 'Twója IP-adresa jo zapisana w DNSBL {{SITENAME}} ako wócynjony proxy. Njejo móžno, nowe wužywarske konta załožowaś.',

# Developer tools
'lockdb'              => 'Datowu banku zamknuś',
'unlockdb'            => 'Datowu banku zasej spśistupniś',
'lockdbtext'          => 'Zamknjenje datoweje banki znjemóžnijo wšyknym wužywarjam boki wobźěłaś, swóje nastajenja změnjaś, swóje wobglědowańka wobźěłaś a druge źěła wugbaś, kótarež pominaju změnu w datowej bance. Pšosym wobkšuś, až coš něnto datowu banku zamknuś a zasej dopušćiś, gaž sy swóje změny pśewjadł.',
'unlockdbtext'        => 'Spśistupnjenje datoweje banki zmóžnijo wšyknym wužywarjam boki wobźěłaś, swóje nastajenja změnjaś, swóje wobglědowańka wobźěłaś a druge źěła wugbaś, kótarež pominaju změnu w datowej bance. Pšosym wobkšuś, až coš datowu banku zasej spśistupniś.',
'lockconfirm'         => 'Jo, datowu banku com napšawdu zamknuś.',
'unlockconfirm'       => 'Jo, datowu banku com napšawdu zasej spśistupniś.',
'lockbtn'             => 'Datowu banku zamknuś',
'unlockbtn'           => 'Datowu banku zasej spśistupniś',
'locknoconfirm'       => 'Njejsy hyšći wobkšuśił.',
'lockdbsuccesssub'    => 'Datowa banka jo zamknjona.',
'unlockdbsuccesssub'  => 'Datowa banka jo zasej se spśistupniła.',
'lockdbsuccesstext'   => 'Datowa banka jo zamknjona.
<br />Njezabydń ju [[Special:Unlockdb|zasej spśistupniś]], gaž swójo zeźěłajoš.',
'unlockdbsuccesstext' => 'Datowa banka jo zasej pśistupna.',
'lockfilenotwritable' => 'Njejo móžno, blokěrowansku dataju datoweje banki změniś. Coš-lic datowu banku zamknuś abo zasej spśistupniś, dej webowy serwer měś pšawo, do njeje pisaś.',
'databasenotlocked'   => 'Datowa banka njejo zamknjona.',

# Move page
'movepage'                => 'Bok pśesunuś',
'movepagetext'            => 'Z toś tym formularom móžo se bok (ze wšyknymi wersijami) pśesunuś. Stary nadpis pósrědnijo dalej na nowy. Wótkaze na stary nadpis se njezměniju.',
'movepagetalktext'        => "Pśisłušny diskusijny bok se sobu pśesunjo, '''ale nic gaž:'''
* eksistěrujo južo diskusijny bok z toś tym mjenim, abo gaž
* wótwólijoš toś tu funkciju.

W toś tyma padoma dej wopśimjeśe boka manualnje se pśesunuś resp. gromadu wjasć, jolic až to coš.",
'movearticle'             => 'Bok pśesunuś',
'movenologin'             => 'Njepśizjawjony(a)',
'movenologintext'         => 'Aby mógał bok pśesunuś, dejš byś [[Special:Userlogin|pśizjawjony wužywaŕ]].',
'movenotallowed'          => 'Njamaš pšawo pśesuwaś boki w toś tom wikiju.',
'newtitle'                => 'nowy nadpis:',
'move-watch'              => 'Toś ten bok wobglědowaś',
'movepagebtn'             => 'Bok pśesunuś',
'pagemovedsub'            => 'Bok jo se pśesunuł.',
'movepage-moved'          => "<big>'''Bok „$1“ jo se pśesunuł na „$2“.'''</big>", # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => 'Bok z takim mjenim južo eksistěrujo abo mě, kótarež sćo wuwzólił jo njepłaśece. Pšosym wuzwól nowe mě.',
'talkexists'              => 'Samy bok jo se pśesunuł, ale pśisłušny diskusijny bok nic, dokulaž eksistěrujo južo taki bok z nowym mjenim. Pšosym pśirownaj wopśimjeśi manualnje.',
'movedto'                 => 'pśesunjony do',
'movetalk'                => 'Diskusijny bok sobu pśesunuś.',
'talkpagemoved'           => 'Pśisłušny diskusijny bok jo teke se pśesunuł.',
'talkpagenotmoved'        => 'Wótpowědny diskusijny bok <strong>njejo</strong> był pśesunjony.',
'1movedto2'               => '„[[$1]]“ pśesunjone na „[[$2]]“',
'1movedto2_redir'         => '„[[$1]]“ jo se pśesunuł(o/a) na „[[$2]]“. Pśi tom jo jadno dalejpósrědnjenje se pśepisało.',
'movelogpage'             => 'Protokol pśesunjenjow',
'movelogpagetext'         => 'How jo lisćina wšyknych pśesunjonych bokow.',
'movereason'              => 'Pśicyna',
'revertmove'              => 'nawrośiś',
'delete_and_move'         => 'Wulašowaś a pśesunuś',
'delete_and_move_text'    => '==Celowy bok eksistěrujo - wulašowaś??==

Bok „[[$1]]“ južo eksistěrujo. Coš jen wulašowaś, aby mógał toś ten bok pśesunuś?',
'delete_and_move_confirm' => 'Jo, toś ten bok wulašowaś',
'delete_and_move_reason'  => 'wulašowane, aby było městno za pśesunjenje',
'selfmove'                => 'Wuchadne a celowe mě stej identiskej; njejo móžno, bok na sam se pśesunuś.',
'immobile_namespace'      => 'Wuchadne abo celowe mě jo šćitane; njejo móžno, boki z togo resp. do togo mjenjowego ruma pśesuwaś.',

# Export
'export'            => 'Boki eksportěrowaś',
'exporttext'        => 'Móžoš tekst a stawizny boka abo skupiny bokow, kótarež su w XML zapisane, eksportěrowaś. Jo móžno je do drugeje wiki importěrowaś pśeź MediaWiki [[Special:Import|bok importěrowanja]].

Za eksportěrowanje bokow zapódaj nadpisma do dołojcnego tekstowogo póla, jadno nadpismo na smužku, a wuzwól nowe a stare wersije z wótkazami stawiznow boka abo jano aktualnu wersiju z informacijami wó slědnjej změnje.

W slědnem padźe móžoš teke wótkaz wužywaś, na pś. [[{{ns:special}}:Export/{{MediaWiki:Mainpage}}]] za bok "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Jano aktualne wersije, bźez stawiznow',
'exportnohistory'   => "----
'''Pokazka:''' Eksportěrowanje cełych stawiznow bokow pśez toś ten formular njejo dla performancowych pśicyn tuchylu móžne.",
'export-submit'     => 'Eksportěrowaś',
'export-addcattext' => 'Pśidaś boki z kategorije:',
'export-addcat'     => 'Dodaś',
'export-download'   => 'Ako XML-dataju składowaś',

# Namespace 8 related
'allmessages'               => 'Systemowe zdźělenja',
'allmessagesname'           => 'Mě',
'allmessagesdefault'        => 'Standardny tekst',
'allmessagescurrent'        => 'Aktualny tekst',
'allmessagestext'           => 'How jo lisćina systemowych tekstow MediaWiki.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' njejo tuchylu móžno, dokulaž jo datowa banka offline.",
'allmessagesfilter'         => 'Filter za mjenja powěsćow:',
'allmessagesmodified'       => 'Jano změnjone pokazaś',

# Thumbnails
'thumbnail-more'           => 'Pówětšyś',
'missingimage'             => '<b>wobraz felujo</b><br /><i>$1</i>',
'filemissing'              => 'Dataja felujo',
'thumbnail_error'          => 'Zmólka pśi stwórjenju pśeglěda: $1',
'djvu_page_error'          => 'DjVu-bok pśesegujo wobłuk.',
'djvu_no_xml'              => 'Njejo móžno, XML za DjVu-dataju wótwołaś.',
'thumbnail_invalid_params' => 'Njepłaśece parametry pśeglěda',
'thumbnail_dest_directory' => 'Njejo móžno celowy zapis stwóriś.',

# Special:Import
'import'                     => 'Boki importěrowaś',
'importinterwiki'            => 'Transwiki-importěrowanje',
'import-interwiki-text'      => 'Wuzwól wiki a bok za importěrowanje.
Datumy wersijow a wužywarske mjenja pśi tym se njezměniju.
Wšykne transwiki-importowe akcije protokolěruju se w [[Special:Log/import|log-lisćinje importow]].',
'import-interwiki-history'   => 'Importěruj wšykne wersije toś togo boka',
'import-interwiki-submit'    => 'Importěrowaś',
'import-interwiki-namespace' => 'Importěruj boki do mjenjowego ruma:',
'importtext'                 => 'Na toś tom specialnem boku mógu pśez [[{{ns:special}}:Eksport]] eksportěrowane boki do toś teje wiki se importěrowaś.',
'importstart'                => 'Importěrowanje bokow...',
'import-revision-count'      => '$1 {{PLURAL:$1|wersija|wersiji|wersije}}',
'importnopages'              => 'Boki za importěrowanje njeeksistěruju.',
'importfailed'               => 'Zmólka pśi importěrowanju: $1',
'importunknownsource'        => 'Njeznate źrědło importěrowanja.',
'importcantopen'             => 'Dataja za importěrowanje njejo se dała wócyniś.',
'importbadinterwiki'         => 'Njepłaśecy interwikijowy wótkaz',
'importnotext'               => 'Prozdne abo bźez teksta',
'importsuccess'              => 'Import wuspěšny!',
'importhistoryconflict'      => 'Konflikt wersijow (snaź jo toś ten bok južo raz se importěrował)',
'importnosources'            => 'Za transwikijowe importěrowanje njejsu žrědła definěrowane, direktne stawizny uploadowanja su znjemóžnjone.',
'importnofile'               => 'Žedna dataja za importěrowanje njejo se uploadowała.',
'importuploaderror'          => 'Uploadowanje dataje za importěrowanje njejo se zglucył, snaź jo dataja wětša ako dowólone.',

# Import log
'importlogpage'                    => 'Log-lisćinu importěrowaś',
'importlogpagetext'                => 'Administratiwne importěrowanje bokow ze stawiznami z drugich wikijow.',
'import-logentry-upload'           => 'Dataja [[$1]] jo pśez uploadowanje se importěrowała.',
'import-logentry-upload-detail'    => '{{Plural:$1|$1 wersija|$1 wersiji|$1 wersije}}',
'import-logentry-interwiki'        => 'Dataja $1 jo se importěrowała (transwiki).',
'import-logentry-interwiki-detail' => '{{Plural:$1|$1 wersija|$1 wersiji|$1 wersije}} wót $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Mój wužywarski bok',
'tooltip-pt-anonuserpage'         => 'Wužywarski bok za IP-adresu, z kótarejuž bok wobźěłajoš',
'tooltip-pt-mytalk'               => 'Mój diskusijny bok',
'tooltip-pt-anontalk'             => 'Diskusija wó změnach z tuteje IP-adresy.',
'tooltip-pt-preferences'          => 'Móje pśistajenja',
'tooltip-pt-watchlist'            => 'Lisćina wobglědowańkow',
'tooltip-pt-mycontris'            => 'Lisćina mójich pśinoskow',
'tooltip-pt-login'                => 'Pśizjawjenje njejo obligatoriske, ale lubje witane.',
'tooltip-pt-anonlogin'            => 'Pśizjawjenje njejo obligatoriske, ale lubje witane.',
'tooltip-pt-logout'               => 'Wótzjawiś',
'tooltip-ca-talk'                 => 'Diskusija wó wopśimjeśu boka',
'tooltip-ca-edit'                 => 'Móžoš bok wobźěłaś. Nježlic składujoš, wužywaj pšosym funkciju "pśeglěd".',
'tooltip-ca-addsection'           => 'Komentar k diskusiji pśidaś.',
'tooltip-ca-viewsource'           => 'Bok jo šćitany. Jo móžno, žrědłowy tekst woglědaś.',
'tooltip-ca-history'              => 'Něgajšne wersije togo boka.',
'tooltip-ca-protect'              => 'Toś ten bok šćitaś',
'tooltip-ca-delete'               => 'Toś ten bok wulašowaś',
'tooltip-ca-undelete'             => 'Zapise pśed wulašowanim boka nawrośiś.',
'tooltip-ca-move'                 => 'Toś ten bok pśesunuś',
'tooltip-ca-watch'                => 'Dodaj toś ten bok do swójeje wobglědowańskeje lisćiny.',
'tooltip-ca-unwatch'              => 'Bok z wobglědowańskeje lisćiny wulašowaś',
'tooltip-search'                  => 'Pśepytaś {{SITENAME}}',
'tooltip-search-go'               => 'Źi direktnje na bok z toś tym mjenim.',
'tooltip-search-fulltext'         => 'Toś ten tekst w bokach pytaś',
'tooltip-p-logo'                  => 'Głowny bok',
'tooltip-n-mainpage'              => 'Glowny bok pokazaś',
'tooltip-n-portal'                => 'Wó portalu, co móžoš cyniś, źo co namakajoš',
'tooltip-n-currentevents'         => 'Slězynowe informacije k aktualnym tšojenjam',
'tooltip-n-recentchanges'         => 'Lisćina aktualnych změnow w(e) {{SITENAME}}.',
'tooltip-n-randompage'            => 'Pśipadny bok',
'tooltip-n-help'                  => 'Pomocny bok pokazaś',
'tooltip-n-sitesupport'           => 'Pódpěraj nas',
'tooltip-t-whatlinkshere'         => 'Lisćina wšyknych wiki bokow, kótarež how wótkazuju',
'tooltip-t-recentchangeslinked'   => 'Aktualne změny w bokach, na kótarež toś ten bok wótkazujo',
'tooltip-feed-rss'                => 'RSS-feed za toś ten bok',
'tooltip-feed-atom'               => 'Atom-feed za toś ten bok',
'tooltip-t-contributions'         => 'Pśinoski togo wužywarja wobglědowaś',
'tooltip-t-emailuser'             => 'Wužywarjeju e-mail pósłaś',
'tooltip-t-upload'                => 'Dataje pósłaś',
'tooltip-t-specialpages'          => 'Lisćina wšyknych specialnych bokow',
'tooltip-t-print'                 => 'Śišćańska wersija boka',
'tooltip-t-permalink'             => 'Stawny wótkaz na toś tu wersiju boka',
'tooltip-ca-nstab-main'           => 'Wopśimjeśe pokazaś',
'tooltip-ca-nstab-user'           => 'Wužywarski bok pokazaś',
'tooltip-ca-nstab-media'          => 'Pokazaś bok medijow/datajow.',
'tooltip-ca-nstab-special'        => 'To jo specialny bok, kótaryž njedajo se wobźěłaś.',
'tooltip-ca-nstab-project'        => 'Portal pokazaś',
'tooltip-ca-nstab-image'          => 'Wobrazowy bok pokazaś',
'tooltip-ca-nstab-mediawiki'      => 'Systemowy tekst pokazaś',
'tooltip-ca-nstab-template'       => 'Šablonu pokazaś',
'tooltip-ca-nstab-help'           => 'Pomocny bok pokazaś',
'tooltip-ca-nstab-category'       => 'Bok kategorijow pokazaś',
'tooltip-minoredit'               => 'Změnu ako drobnu markěrowaś',
'tooltip-save'                    => 'Změny składowaś',
'tooltip-preview'                 => "Pšosym '''pśeglěd změnow''' wužywaś, nježlic až składujoš!",
'tooltip-diff'                    => 'Pokazujo změny teksta w tabelariskej formje.',
'tooltip-compareselectedversions' => 'Wuzwólonej wersiji boka pśirownowaś',
'tooltip-watch'                   => 'Toś ten bok wobglědowańkam dodaś',
'tooltip-recreate'                => 'Bok nawrośiś, lěcrowno jo był wulašowany',
'tooltip-upload'                  => 'Z uploadowanim zachopiś',

# Stylesheets
'common.css'   => '/** Na toś tom městnje wustatkujo se CSS na wšykne šaty. */',
'monobook.css' => '/* How zaměstnjony CSS wustatkujo se na wužywarje monobook-šata */',

# Scripts
'common.js'   => '/* Kuždy JavaScript how lodujo se za wšykne wužywarje na kuždem boce. */',
'monobook.js' => '/* Zestarjone; wužywaj město togo [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Metadaty Dublin Core RDF su za toś ten serwer deaktiwěrowane.',
'nocreativecommons' => 'Metadaty Creative Commons RDF su za toś ten serwer deaktiwěrowane.',
'notacceptable'     => 'Wiki-serwer njamóžo daty za twój klient wobźěłaś.',

# Attribution
'anonymous'        => '{{Plural:$|Anonymny wužywaŕ|Anonymnej wužywarja|Anonymne wužywarje}} na {{SITENAME}}',
'siteuser'         => '{{SITENAME}}-wužywaŕ $1',
'lastmodifiedatby' => 'Toś ten bok jo slědny raz se wobźěłał $2, $1 góź. wót wužywarja $3.', # $1 date, $2 time, $3 user
'and'              => 'a',
'othercontribs'    => 'Bazěrujo na źěle $1',
'others'           => 'druge',
'siteusers'        => '{{SITENAME}}-wužywaŕ $1',
'creditspage'      => 'Informacija wó boku',
'nocredits'        => 'Njeeksistěruju žedne informacije za toś ten bok.',

# Spam protection
'spamprotectiontitle'    => 'Spamowy filter',
'spamprotectiontext'     => 'Bok, kótaryž což składowaś, jo wót spamowego filtera se blokěrował. To zawinujo nejskerjej wótkaz na eksterny bok.',
'spamprotectionmatch'    => "'''Spamowy filter jo slědujucy tekst namakał: ''$1'''''",
'subcategorycount'       => '{{PLURAL:$1|Pokažo se $1 pódkategorija|Pokazujotej se $1 pódkategoriji|Pokazuju se $1 pódkategorije}}.',
'categoryarticlecount'   => '<small>W toś tej kategoriji {{PLURAL:$1|jo $1 bok|stej $1 boka|su $1 boki}}.</small>',
'category-media-count'   => '<small>W toś tej kategoriji {{PLURAL:$1|jo $1 dataja|stej $1 dataji|su $1 dataje}}.</small>',
'listingcontinuesabbrev' => 'dalej',
'spambot_username'       => 'MediaWikijowe spamowe rěšenje',
'spam_reverting'         => 'Nawrośijo se slědna wersija, kótaraž njejo wopśimjeła wótkaz na $1.',
'spam_blanking'          => 'Wšykne wersije su wopśimowali wótkaze na $1, do rěcha spórane.',

# Info page
'infosubtitle'   => 'Informacija wó boku',
'numedits'       => 'Licba změnow boka: $1',
'numtalkedits'   => 'Licba změnow diskusijnego boka: $1',
'numwatchers'    => 'Licba  wobglědowarjow: $1',
'numauthors'     => 'Licba awtorow: $1',
'numtalkauthors' => 'Licba diskutěrujucych: $1',

# Math options
'mw_math_png'    => 'Pśecej ako PNG zwobrazniś.',
'mw_math_simple' => 'Jadnory TeX ako HTML, howacej PNG',
'mw_math_html'   => 'Jo-lic móžno ako HTML, howacej PNG',
'mw_math_source' => 'Ako TeX wóstajiś (za tekstowe browsery)',
'mw_math_modern' => 'Pórucyjo se za moderne browsery',
'mw_math_mathml' => 'Jo-lic móžno - MathML (eksperimentelny)',

# Patrolling
'markaspatrolleddiff'                 => 'Ako kontrolěrowane markěrowaś',
'markaspatrolledtext'                 => 'Markěruj toś ten bok ako kontrolěrowany',
'markedaspatrolled'                   => 'jo se ako kontrolěrowany markěrował',
'markedaspatrolledtext'               => 'Wuzwólona wersija jo se markěrowała ako kontrolěrowana.',
'rcpatroldisabled'                    => 'Kontrolěrowanje slědnych změnow jo se znjemóžniło.',
'rcpatroldisabledtext'                => 'Kontrolěrowanje slědnych změnow jo tuchylu se znjemóžniło.',
'markedaspatrollederror'              => 'Markěrowanje ako "kontrolěrowane" njejo móžne.',
'markedaspatrollederrortext'          => 'Musyš wersiju wuzwóliś.',
'markedaspatrollederror-noautopatrol' => 'Njesmějoš swóje změny ako kontrolěrowane markěrowaś.',

# Patrol log
'patrol-log-page' => 'Log-lisćina kontrolow',
'patrol-log-line' => 'markěrował $1 wót $2 ako kontrolěrowane $3.',
'patrol-log-auto' => '(awtomatiski)',

# Image deletion
'deletedrevision'                 => 'wulašowana stara wersija: $1',
'filedeleteerror-short'           => 'Zmólka pśi wulašowanju dataje: $1',
'filedeleteerror-long'            => 'Pśi wulašowanju dataje su se zwěsćili zmólki:

$1',
'filedelete-missing'              => 'Dataja „$1“ njamóžo se wulašowaś, dokulaž njeeksistěrujo.',
'filedelete-old-unregistered'     => 'Pódana wersija „$1“ w datowej bance njeeksistěrujo.',
'filedelete-current-unregistered' => 'Pódana dataja „$1“ w datowej bance njeeksistěrujo.',
'filedelete-archive-read-only'    => 'Webserwer njamóžo do archiwowego zapisa „$1“ pisaś.',

# Browsing diffs
'previousdiff' => '← pśedchadny rozdźěl',
'nextdiff'     => 'pśiducy rozdźěl →',

# Media information
'mediawarning'         => "'''Glědaj!''' Toś ta sorta datajow móžo wopśimjeś złosny programowy kod. Ześěgnjo-lic a wócynijo-lic se dataja, móžo se Twój kompjuter wobškóźeś.<hr />",
'imagemaxsize'         => 'Maksimalna wjelikosć wobrazow na bokach za wopisowanje wobrazow:',
'thumbsize'            => 'Rozměra miniaturow:',
'widthheightpage'      => '$1×$2, $3 boki',
'file-info'            => '(wjelikosć dataje: $1, MIME-Typ: $2)',
'file-info-size'       => '($1 × $2 pikselow, wjelikosć dataje: $3, MIME-Typ: $4)',
'file-nohires'         => '<small>Wuše wótgranicowanje njeeksistěrujo.</small>',
'svg-long-desc'        => '(dataja SVG, nominalnje: $1 × $2 piksele, wjelikosć dataje: $3)',
'show-big-image'       => 'Połne optiske wótgranicowanje.',
'show-big-image-thumb' => '<small>wjelikosć pśeglěda: $1 × $2 pikselow</small>',

# Special:Newimages
'newimages'         => 'Nowe dataje',
'newimages-summary' => 'Toś ten specialny bok pokazujo wobraze a dataje, kótarež ako slědne su se uploadowali.',
'showhidebots'      => '(awtomatiske programy (boty) $1)',
'noimages'          => 'Žedne dataje njejsu se namakali.',

# Bad image list
'bad_image_list' => 'Format jo slědujucy:

Jano smužki, kótarež zachopiju z *, se wugódnośiju. Ako prědne musy pó * stojaś wótkaz na njekśěty wobraz. Slědujuce wótkaze na nastawki w samskej smužce definěruju wuwześa, w kótarychž móžo wobraz weto se pokazaś.',

# Metadata
'metadata'          => 'Metadaty',
'metadata-help'     => 'Dataja wopśimjejo pśidatne informacije, kótarež póchadaju w normalnem paźe wót digitalneje kamery abo scannera. Pózdźejšne wobźěłanja su mógli toś te detajle změniś.',
'metadata-expand'   => 'rozšyrjone detaile pokazaś',
'metadata-collapse' => 'rozšyrjone detaile schowaś',
'metadata-fields'   => 'Slědujuce póla EXIF-metadatow se pokazuju na bokach, kótarež wopisuju wobraze; dalšne detaile, kótarež normalnje su schowane, mógu se pśidatnje pokazaś.

* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'                  => 'Šyrokosć',
'exif-imagelength'                 => 'Wusokosć',
'exif-bitspersample'               => 'Bity na komponentu',
'exif-compression'                 => 'Wašnja kompriměrowanja',
'exif-photometricinterpretation'   => 'Zestajenje pikselow',
'exif-orientation'                 => 'Wusměrjenje kamery',
'exif-samplesperpixel'             => 'Licba komponentow',
'exif-planarconfiguration'         => 'Struktura datow',
'exif-ycbcrsubsampling'            => 'Subsamplingowa rata wót Y do C',
'exif-ycbcrpositioning'            => 'Pozicijoněrowanje Y a C',
'exif-xresolution'                 => 'Horicontalne optiske wótgranicowanje',
'exif-yresolution'                 => 'Wertikalne optiske wótgranicowanje',
'exif-resolutionunit'              => 'Měra optiskego wótgranicowanja',
'exif-stripoffsets'                => 'městnosć wobrazowych datow',
'exif-rowsperstrip'                => 'Licba smužkow na rědku',
'exif-stripbytecounts'             => 'Byty na kompriměrowanu rědku',
'exif-jpeginterchangeformat'       => 'Offset k JPEG SOI',
'exif-jpeginterchangeformatlength' => 'Byty JPEG-dataje',
'exif-transferfunction'            => 'Funkcija pśestajenja',
'exif-whitepoint'                  => 'kwalita barwy běłego dypka',
'exif-primarychromaticities'       => 'Kwalita barwy primarnych barwow.',
'exif-ycbcrcoefficients'           => 'YCbCr-koeficienty',
'exif-referenceblackwhite'         => 'Pórik carneje a běłeje referencneje gódnoty',
'exif-datetime'                    => 'Cas składowanja',
'exif-imagedescription'            => 'Mě wobraza',
'exif-make'                        => 'Zgótowaŕ kamery',
'exif-model'                       => 'Model kamery',
'exif-software'                    => 'Softwara',
'exif-artist'                      => 'Awtor',
'exif-copyright'                   => 'Wobsejźaŕ stwóriśelskich pšawow',
'exif-exifversion'                 => 'Wersija Exif',
'exif-flashpixversion'             => 'Pódpěrana wersija Flashpix',
'exif-colorspace'                  => 'Barwowy rum',
'exif-componentsconfiguration'     => 'Wóznam jadnotliwych komponentow',
'exif-compressedbitsperpixel'      => 'Kompriměrowane bity na piksel',
'exif-pixelydimension'             => 'Dopušćona šyrokosć wobraza',
'exif-pixelxdimension'             => 'Dopušćona wusokosć wobraza',
'exif-makernote'                   => 'Noticy zgótowarja',
'exif-usercomment'                 => 'Komentary wužywarja',
'exif-relatedsoundfile'            => 'Pśisłušna zukowa dataja',
'exif-datetimeoriginal'            => 'Datum a cas wutwórjenja datow',
'exif-datetimedigitized'           => 'Datum a cas digitalizěrowanja',
'exif-subsectime'                  => 'Źěły sekundow za datum a cas (1/100 s)',
'exif-subsectimeoriginal'          => 'Źěły sekundow za datum a cas wutwórjenja datow (1/100 s)',
'exif-subsectimedigitized'         => 'Źěły sekundow za datum a cas digitalizěrowanja (1/100 s)',
'exif-exposuretime'                => 'Cas wobswětlenja',
'exif-exposuretime-format'         => '$1 sek ($2)',
'exif-fnumber'                     => 'Blenda',
'exif-exposureprogram'             => 'Program wobswětlenja',
'exif-spectralsensitivity'         => 'Spektralna cuśiwosć',
'exif-isospeedratings'             => 'Cuśiwosć filma abo sensora (ISO)',
'exif-oecf'                        => 'Optoelektroniski pśelicowański faktor (OECF)',
'exif-shutterspeedvalue'           => 'Gódnota wobswětleńskego casa',
'exif-aperturevalue'               => 'Blenda',
'exif-brightnessvalue'             => 'Swětłosć',
'exif-exposurebiasvalue'           => 'Směrnica za wobswětlenje',
'exif-maxaperturevalue'            => 'Nejžwětša blenda',
'exif-subjectdistance'             => 'zdalonosć',
'exif-meteringmode'                => 'Wašnja měrjenja',
'exif-lightsource'                 => 'Žrědło swětła',
'exif-flash'                       => 'Błysk',
'exif-focallength'                 => 'Palna dalokosć',
'exif-subjectarea'                 => 'wobłuk',
'exif-flashenergy'                 => 'mócnosć błyska',
'exif-spatialfrequencyresponse'    => 'Cuśiwosć rumoweje frekwence',
'exif-focalplanexresolution'       => 'horicontalne optiske wótgranicowanje sensora',
'exif-focalplaneyresolution'       => 'wertikalne optiske wótgranicowanje sensora',
'exif-focalplaneresolutionunit'    => 'Jadnotka optiskego wótgranicowanja sensora',
'exif-subjectlocation'             => 'Městno motiwa',
'exif-exposureindex'               => 'Indeks wobswětlenja',
'exif-sensingmethod'               => 'wašnja měrjenja',
'exif-filesource'                  => 'Žrědło dataje',
'exif-scenetype'                   => 'Typ sceny',
'exif-cfapattern'                  => 'Muster CFA',
'exif-customrendered'              => 'Wót wužywarja definěrowane wobźěłanje wobraza',
'exif-exposuremode'                => 'Modus wobswětlenja',
'exif-whitebalance'                => 'Balansa běłosći',
'exif-digitalzoomratio'            => 'digitalne zoomowanje',
'exif-focallengthin35mmfilm'       => 'Palna dalokosć (wótpowědnik za małe wobraze)',
'exif-scenecapturetype'            => 'wašnja nagraśa',
'exif-gaincontrol'                 => 'Regulěrowanje sceny',
'exif-contrast'                    => 'kontrast',
'exif-saturation'                  => 'naseśenje',
'exif-sharpness'                   => 'wótšosć',
'exif-devicesettingdescription'    => 'Nastajenja aparata',
'exif-subjectdistancerange'        => 'Zdalonosć motiwa',
'exif-imageuniqueid'               => 'Jadnorazny ID wobraza',
'exif-gpsversionid'                => 'Wersija taga GPS',
'exif-gpslatituderef'              => 'Pódpołnocna abo pódpołdnjowa šyrina',
'exif-gpslatitude'                 => 'Šyrina',
'exif-gpslongituderef'             => 'Pódzajtšna abo pódwjacorna dliń',
'exif-gpslongitude'                => 'Dliń',
'exif-gpsaltituderef'              => 'Referencna wusokosć',
'exif-gpsaltitude'                 => 'Wusokosć',
'exif-gpstimestamp'                => 'GPS-cas',
'exif-gpssatellites'               => 'Za měrjenje wužywane satelity',
'exif-gpsstatus'                   => 'Status pśidostawaka',
'exif-gpsmeasuremode'              => 'wašnja měrjenja',
'exif-gpsdop'                      => 'dokradnosć měry',
'exif-gpsspeedref'                 => 'Jadnotka spěšnosći',
'exif-gpsspeed'                    => 'Spěšnosć GPS-pśidostawaka',
'exif-gpstrackref'                 => 'Referenca za směr pógibowanja',
'exif-gpstrack'                    => 'směr pógibowanja',
'exif-gpsimgdirectionref'          => 'Referenca směra wobraza',
'exif-gpsimgdirection'             => 'Směr wobraza',
'exif-gpsmapdatum'                 => 'Wužyte geodetiske dataje',
'exif-gpsdestlatituderef'          => 'Referenca šyriny celowego městna',
'exif-gpsdestlatitude'             => 'Šyrina celowego městna',
'exif-gpsdestlongituderef'         => 'Referenca dlini celowego městna',
'exif-gpsdestlongitude'            => 'Dliń abo celowe městno',
'exif-gpsdestbearingref'           => 'Referenca za wusměrjenje',
'exif-gpsdestbearing'              => 'Wusměrjenje',
'exif-gpsdestdistanceref'          => 'Referenca za distancu k celowemu městnu',
'exif-gpsdestdistance'             => 'Distanca k celowemu městnu',
'exif-gpsprocessingmethod'         => 'Mě metody pśeźěłanja GPS',
'exif-gpsareainformation'          => 'Mě wobcerka GPS',
'exif-gpsdatestamp'                => 'Datum GPS',
'exif-gpsdifferential'             => 'Diferencialna korektura GPS',

# EXIF attributes
'exif-compression-1' => 'Njekompriměrowany',

'exif-unknowndate' => 'Njeznaty datum',

'exif-orientation-1' => 'Normalny', # 0th row: top; 0th column: left
'exif-orientation-2' => 'horicontalnje wobrośony', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Pśewobrośony', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'wertikalnje wobrośony', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Wobrośony wó 90° nalěwo a wertikalnje', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Wobrośony wó 90° napšawo', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Wobrośony wó 90° napšawo a wertikalnje', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Wobrośony wó 90° nalěwo', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'gropny format',
'exif-planarconfiguration-2' => 'płony format',

'exif-xyresolution-i' => '$1 dpi (dypkow na col)',

'exif-componentsconfiguration-0' => 'njeeksistěrujo',

'exif-exposureprogram-0' => 'Njedefiněrowane',
'exif-exposureprogram-1' => 'manualnje',
'exif-exposureprogram-2' => 'Normalny program',
'exif-exposureprogram-3' => 'Priorita blendy',
'exif-exposureprogram-4' => 'Priorita blendy',
'exif-exposureprogram-5' => 'Kreatiwny program (wjelika dłym wótšosći)',
'exif-exposureprogram-6' => 'Aktiwny program (wjelika malsnosć momentoweje bildki)',
'exif-exposureprogram-7' => 'portretowy modus (za closeup-fotografije z njefokusěrowaneju slězynu)',
'exif-exposureprogram-8' => 'wobraze krajiny',

'exif-subjectdistance-value' => '{{Plural:$1|$1 meter|$1 metra|$1 metry}}',

'exif-meteringmode-0'   => 'Njeznaty',
'exif-meteringmode-1'   => 'Pśerězna gódnota',
'exif-meteringmode-2'   => 'srjejźa wusměrjone',
'exif-meteringmode-3'   => 'Spot',
'exif-meteringmode-4'   => 'MultiSpot',
'exif-meteringmode-5'   => 'Muster',
'exif-meteringmode-6'   => 'Źělny',
'exif-meteringmode-255' => 'Drugi',

'exif-lightsource-0'   => 'Njeznaty',
'exif-lightsource-1'   => 'Dnjowne swětło',
'exif-lightsource-2'   => 'Fluorescentny',
'exif-lightsource-3'   => 'Žaglawka',
'exif-lightsource-4'   => 'Błysk',
'exif-lightsource-9'   => 'Rědne wjedro',
'exif-lightsource-10'  => 'Mrokawe wjedro',
'exif-lightsource-11'  => 'Seń',
'exif-lightsource-12'  => 'Dnjowe swětło fluorescentne (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Dnjowoběły fluorescentny (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Zymny běły fluorescentny (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Běły fluorescentny (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Standardne swětło A',
'exif-lightsource-18'  => 'Standardne swětło B',
'exif-lightsource-19'  => 'Standardne swětło C',
'exif-lightsource-24'  => 'ISO studijowe swětło',
'exif-lightsource-255' => 'Druge žrědło swětła',

'exif-focalplaneresolutionunit-2' => 'cole',

'exif-sensingmethod-1' => 'Njedefiněrujobny',
'exif-sensingmethod-2' => 'Jadnochipowy barwowy sensor ruma',
'exif-sensingmethod-3' => 'Dwuchipowy barwowy sensor ruma',
'exif-sensingmethod-4' => 'Tśichipowy barwowy sensor ruma',
'exif-sensingmethod-5' => 'Sekwencielny barwowy sensor ruma',
'exif-sensingmethod-7' => 'Tśilinearny sensor',
'exif-sensingmethod-8' => 'Sekwencielny barwowy linearny sensor',

'exif-scenetype-1' => 'Direktnje fotografěrowany wobraz',

'exif-customrendered-0' => 'Normalne wobźěłanje',
'exif-customrendered-1' => 'Wužywarske wobźěłanje',

'exif-exposuremode-0' => 'Awtomatiske wobswětlenje',
'exif-exposuremode-1' => 'Manuelna blenda',
'exif-exposuremode-2' => 'Awtoblenda',

'exif-whitebalance-0' => 'Awtomatiska rownowaga běłosći',
'exif-whitebalance-1' => 'Manuelna rownowaga běłosći',

'exif-scenecapturetype-0' => 'Standard',
'exif-scenecapturetype-1' => 'Krajina',
'exif-scenecapturetype-2' => 'Portret',
'exif-scenecapturetype-3' => 'Nocna scena',

'exif-gaincontrol-0' => 'Žedne',
'exif-gaincontrol-1' => 'Małe zmócnjenje',
'exif-gaincontrol-2' => 'wjelike zmócnjenje',
'exif-gaincontrol-3' => 'małe wósłabjenje',
'exif-gaincontrol-4' => 'Wjelike wósłabjenje',

'exif-contrast-0' => 'Normalny',
'exif-contrast-1' => 'Słaby',
'exif-contrast-2' => 'Mócny',

'exif-saturation-0' => 'Normalny',
'exif-saturation-1' => 'małe naseśenje',
'exif-saturation-2' => 'wjelike naseśenje',

'exif-sharpness-0' => 'Normalny',
'exif-sharpness-1' => 'Słaby',
'exif-sharpness-2' => 'Mócny',

'exif-subjectdistancerange-0' => 'Njeznaty',
'exif-subjectdistancerange-1' => 'makro',
'exif-subjectdistancerange-2' => 'Bliski rozglěd',
'exif-subjectdistancerange-3' => 'Daloki rozglěd',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Pódpołnocna šyrina',
'exif-gpslatitude-s' => 'Pódpołdnjowa šyrina',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Pódzajtšna dliń',
'exif-gpslongitude-w' => 'Pódwjacorna dliń',

'exif-gpsstatus-a' => 'Měrjenje w běgu',
'exif-gpsstatus-v' => 'kompatibelnosć měry',

'exif-gpsmeasuremode-2' => '2-dimensionalne měrjenje',
'exif-gpsmeasuremode-3' => '3-dimensionalne měrjenje',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilometry na góźinu',
'exif-gpsspeed-m' => 'Mile na góźinu',
'exif-gpsspeed-n' => 'Suki',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Wopšawdny směr',
'exif-gpsdirection-m' => 'Magnetiski směr',

# External editor support
'edit-externally'      => 'Dataje z eksternym programom wobźěłaś',
'edit-externally-help' => 'Za dalšne informacije glědaj [http://meta.wikimedia.org/wiki/Help:External_editors setup instructions].',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'wšykne',
'imagelistall'     => 'wšykne',
'watchlistall2'    => 'wšykne',
'namespacesall'    => 'wšykne',
'monthsall'        => 'wšykne',

# E-mail address confirmation
'confirmemail'            => 'E-mailowu adresu wobkšuśiś.',
'confirmemail_noemail'    => 'W swójich [[Special:Preferences|nastajenjach]] njejsy płaśecu e-mailowu adresu zapódał.',
'confirmemail_text'       => '{{SITENAME}} pomina, až wobkšuśijoš swóju e-mailowu adresu, nježlic až móžoš e-mailowe funkcije wužywaś. Tłocyš-lic na tłocatko, dostanjoš e-mailku, w kótarejž jo wótkaz z wobkšuśenskim gronidłom. Tłocenje na wótkaz wobkšuśijo, až twója e-mailowa adresa jo korektna.',
'confirmemail_pending'    => '<div class="error">Tebje jo južo jadno wobkšuśeńske gronidło se pśimailowało. Sy-lic swójo wužywarske konto akle gano wutwórił, ga pócakaj hyšći žedne minuty na e-mail, nježlic až pominaš nowe gronidło.</div>',
'confirmemail_send'       => 'Wobkšuśeńske gronidło pósłaś',
'confirmemail_sent'       => 'Wobkšuśeńska e-mailka pósłana.',
'confirmemail_oncreate'   => 'Na Twóju adresu jo se wótpósłało wobkšuśeńske gronidło. Toś ten kod njejo notny za pśizjawjenje, ale za aktiwěrowanje e-mailowych funkcijow we wikiju.',
'confirmemail_sendfailed' => 'Wobkšuśeńska e-mailka njejo se mógła wótpósłaś. Pšosym kontrolěruj swóju e-mailowu adresu na njepłaśece znamuška.

Serwer wótegronijo:$1',
'confirmemail_invalid'    => 'Njepłaśece wobkšuśeńske gronidło. Snaź jo kod mjaztym płaśiwosć zgubił.',
'confirmemail_needlogin'  => 'Dejš $1 aby swóju e-mailowu adresu wobkšuśił.',
'confirmemail_success'    => 'Twója e-mailowa adresa jo wobkšuśona, móžoš se pśizjawiś.',
'confirmemail_loggedin'   => 'Twója e-mailowa adresa jo něnto wobkšuśona.',
'confirmemail_error'      => 'Zmólka pśi wobkšuśenju e-mailoweje adresy.',
'confirmemail_subject'    => '{{SITENAME}} - Wobkšuśenje e-mailoweje adrese',
'confirmemail_body'       => 'Něchten, nejskerjej ty z adresy $1, jo na boku {{SITENAME}} wužywarske konto "$2" z e-mailoweju adresu wutwórił.

Coš-lic to konto wobkšuśiś a wužywanje e-mailowych funkcijow zmóžniś na boce {{SITENAME}}, tłoc na wótkaz: $3

Jolic až *njejsy* to ty był, njetłoc. Toś to wobkšuśeńske gronidło płaśi do $4.',

# Scary transclusion
'scarytranscludedisabled' => '[Pśidawanje interwiki jo deaktiwěrowane]',
'scarytranscludefailed'   => '[Ześěgnjenje šablony za $1 njejo było wuspěšne]',
'scarytranscludetoolong'  => '[URL jo pśeliž dłujki; wódaj pšosym]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Trackbacki za toś ten bok:<br />
$1
</div>',
'trackbackremove'   => '([$1 wulašowaś])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'Trackback jo wuspěšnje wulašowany.',

# Delete conflict
'deletedwhileediting' => 'Warnowanje: Toś ten bok se wulašujo, gaž zachopijoš jen wobźěłaś.',
'confirmrecreate'     => 'Wužywaŕ [[User:$1|$1]] ([[User_talk:$1|diskusija]]) jo bok wulašował, nježli až sy zachopił jen wobźěłaś, pśicyna: : <i>$2</i> 
Pšosym wobkšuśiś, až napšawdu coš ten bok zasej wutwóriś.',
'recreate'            => 'Wótnowótki wutwóriś',

# HTML dump
'redirectingto' => 'Pśeadresěrowanje do [[$1]]...',

# action=purge
'confirm_purge'        => 'Wulašowaś cache togo boka? $1',
'confirm_purge_button' => 'W pórědku.',

# AJAX search
'searchcontaining' => "Nastawki pytaś, do kótarychž słuša tekst ''$1''.",
'searchnamed'      => "Pytaś nastawki z mjenim ''$1''",
'articletitles'    => "Nastawki, kótarež zachopiju z ''$1''",
'hideresults'      => 'rezultat schowaś',

# Multipage image navigation
'imgmultipageprev'   => '← slědny bok',
'imgmultipagenext'   => 'pśiducy bok →',
'imgmultigo'         => 'W pórědku',
'imgmultigotopre'    => 'Hyś na bok',
'imgmultiparseerror' => 'Wobraz jo defektny, toś njamóžo bok {{SITENAME}} lisćinu bokow wutwóriś.',

# Table pager
'ascending_abbrev'         => 'górjej',
'descending_abbrev'        => 'dołoj',
'table_pager_next'         => 'Pśiducy bok',
'table_pager_prev'         => 'Pjerwjejšny bok',
'table_pager_first'        => 'Prědny bok',
'table_pager_last'         => 'Slědny bok',
'table_pager_limit'        => 'Pokazaś {{Plural:$1|$1 objekt|$1 objekta|$1 objekty}} na bok',
'table_pager_limit_submit' => 'Start',
'table_pager_empty'        => 'Žedne wuslědki',

# Auto-summaries
'autosumm-blank'   => 'Bok se wulašujo.',
'autosumm-replace' => "Bok narownajo se z: '$1'",
'autoredircomment' => 'Pśesměrowanje na [[$1]]',
'autosumm-new'     => 'Nowy bok: $1',

# Live preview
'livepreview-loading' => 'Lodowanje …',
'livepreview-ready'   => 'Lodowanje … gótowe!',
'livepreview-failed'  => 'Live-pśeglěd njejo móžny. Pšosym normalny pśeglěd wužywaś.',
'livepreview-error'   => 'Kontaktowanje njejo se zglucyło: $1 "$2". Pšosym normalny pśeglěd wužywaś.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pśinoski, kótarež su nowše ako $1 sekundy, snaź na lisćinje hyšći se njejawje.',
'lag-warn-high'   => 'Dla dłujkego cakanja na wótegrono serwera jo móžno, až pśinoski, kótarež su nowše ako $1 sekundy snaź na toś tej liśćinje se njejawje.',

# Watchlist editor
'watchlistedit-numitems'       => 'Twóje wobglědowańka wopśimjeju {{PLURAL:$1|$1 zapisk|$1 zapiska|$1 zapiski}}, bźez diskusijnych bokow.',
'watchlistedit-noitems'        => 'Twóje wobglědowańka su prozne.',
'watchlistedit-normal-title'   => 'Zapise wobźěłaś',
'watchlistedit-normal-legend'  => 'Zapiski z wobglědowańkow wulašowaś',
'watchlistedit-normal-explain' => 'To su zapise w twójich wobglědowańkach. Coš-lic zapise wulašowaś, markěruj kašćik pódla zapisow a tłoc na "zapise wulašowaś". Móžoš swóje wobglědowańka teke w [[Special:Watchlist/raw|lisćinowem formaśe]] wobźěłaś.',
'watchlistedit-normal-submit'  => 'Zapise wulašowaś',
'watchlistedit-normal-done'    => '{{PLURAL:$1 zapis jo|$1 zapisa stej|$1 zapise su}} z twójich wobglědowańkow se {{Plural:wulašował|wulašowałej|wulašowali}}.',
'watchlistedit-raw-title'      => 'Same wobglědowańka wobźěłaś',
'watchlistedit-raw-legend'     => 'Same wobglědowańka wobźěłaś',
'watchlistedit-raw-explain'    => 'Zapise, kótarež namakaju se w twójich wobglědowańkach pokazuju se dołojce. Wóni mógu se wobźěłaś pśez to, až do lisćiny se dodawaju resp. z njeje se wulašuju (stawnje jaden zapis na smužku). Gaž sy gótowy, tłoc "Lisćinu aktualizěrowaś".

Móžoš teke [[Special:Watchlist/edit|standardny editor wužywaś]].',
'watchlistedit-raw-titles'     => 'Zapise:',
'watchlistedit-raw-submit'     => 'Lisćinu aktualizěrowaś',
'watchlistedit-raw-done'       => 'Twóje wobglědowańka su se zaktualizěrowali.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|1 zapis jo se dodał|$1 zapisa stej se dodałej|$1 zapise su se dodali}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|1 zapis jo se wulašował|$1 zapisa stej se wulašowałej|$1 zapise su se wulašowali}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Změny wobglědaś',
'watchlisttools-edit' => 'Woblědowańka pokazaś a wobźěłaś',
'watchlisttools-raw'  => 'Wobglědowańka wobźěłaś',

# Iranian month names
'iranian-calendar-m1'  => 'Prědny mjasec Jalāli',
'iranian-calendar-m2'  => 'Drugi mjasec Jalāli',
'iranian-calendar-m3'  => 'Tśeśi mjasec Jalāli',
'iranian-calendar-m4'  => 'Stwórty mjasec Jalāli',
'iranian-calendar-m5'  => 'Pěty mjasec Jalāli',
'iranian-calendar-m6'  => 'Šesty mjasec Jalāli',
'iranian-calendar-m7'  => 'Sedymy mjasec Jalāli',
'iranian-calendar-m8'  => 'Wósymy mjasec Jalāli',
'iranian-calendar-m9'  => 'Źewjety mjasec Jalāli',
'iranian-calendar-m10' => 'Źasety mjasec Jalāli',
'iranian-calendar-m11' => 'Jadenasty mjasec Jalāli',
'iranian-calendar-m12' => 'Dwanasty mjasec Jalāli',

);

