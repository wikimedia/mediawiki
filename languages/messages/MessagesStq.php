<?php
/** Seeltersk (Seeltersk)
 *
 * @addtogroup Language
 *
 * @author Maartenvdbent
 * @author Nike
 * @author SPQRobin
 */

$fallback = 'fy';

$messages = array(
# Dates
'sunday'    => 'Sundai',
'monday'    => 'Moundai',
'tuesday'   => 'Täisdai',
'wednesday' => 'Midwiek',
'thursday'  => 'Tuunsdai',
'friday'    => 'Fräindai',
'saturday'  => 'Snäiwende',
'sun'       => 'Sun',
'mon'       => 'Mou',
'tue'       => 'Täi',
'wed'       => 'Mid',
'thu'       => 'Tuu',
'fri'       => 'Frä',
'sat'       => 'Snä',
'january'   => 'Januoar',
'february'  => 'Feebermound',
'march'     => 'Meerte',
'april'     => 'April',
'may_long'  => 'Moai',
'august'    => 'August',
'may'       => 'Moa',

# Bits of text used by many pages
'categories'      => 'Kategorien',
'category_header' => 'Artikkel in de Kategorie "$1"',

'article'  => 'Inhoold Siede',
'cancel'   => 'Oubreeke',
'anontalk' => 'Diskussionssiede foar dissen IP',

'errorpagetitle' => 'Failer',
'edit'           => 'Siede beoarbaidje',
'editthispage'   => 'Siede beoarbaidje',
'delete'         => 'Läskje',
'deletethispage' => 'Disse Siede läskje',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'bugreports'        => 'Kontakt Wikipedia',
'copyright'         => 'Inhoold is ferföichboar unner de $1.',
'currentevents'     => 'Aktuälle Geböärnisse',
'currentevents-url' => 'Aktuälle Geböärnisse',
'disclaimers'       => 'Begriepskläärenge',
'disclaimerpage'    => 'Project:Siede tou Begriepskläärenge',
'edithelp'          => 'Beoarbaidengshälpe',
'edithelppage'      => 'Help:Beoarbaidengshälpe',

'editsection' => 'Beoarbaidje',
'editold'     => 'Beoarbaidje',

# General errors
'error'           => 'Failer',
'dberrortext'     => 'Dät roat n Syntaxfailer in dän Doatenboankoufroage. Ju lääste Doatenboankoufroage lutte:
<blockquote><tt>$1</tt></blockquote> uut de Funktion "<tt>$2</tt>". MySQL mäldede dän Failer "<tt>$3: $4</tt>".',
'cachederror'     => 'Dät Foulgjende is ne Kopie uut de Cache un is fielicht ferallerd.',
'enterlockreason' => 'Reeke jädden n Gruund ien, wieruum ju Doatenboank speerd wäide schuul un ne Ouschätsenge uur ju Duur fon ju Speerenge',
'badarticleerror' => 'Disse Honnelenge kon ap disse Siede nit moaked wäide.',
'cannotdelete'    => 'Kon spezifizierde Siede of Artikkel nit läskje. Fielicht is ju al läsked wuuden.',
'badtitle'        => 'Uungultige Tittel.',
'badtitletext'    => 'Die anfräigede Tittel waas uungultich, loos, of n uungultige Sproaklink fon n uur Wiki.',

# Login and logout pages
'createaccount'              => 'Benutserkonto anlääse',
'createaccountmail'          => 'Uur Email',
'badretype'                  => 'Do bee Paaswoude stimme nit uureen.',
'badsig'                     => 'Signatursyntax is uungultich; HTML uurpröiwje.',
'eauthentsent'               => 'Ne Bestäätigengs-Email wuude an ju anroate Adrässe fersoand. Aleer n Email fon uur
Benutsere uur ju Wikipedia-Mailfunktion ämpfangd wäide kon, mout ju Adrässe un hiere
wuddelke Touheeregaid tou dit Benutserkonto eerste bestäätiged wäide. Befoulgje jädden do
Waiwiese in ju Bestätigengs-E-Mail.',
'acct_creation_throttle_hit' => 'Du hääst al $1 Benutserkonten anlaid. Du koast fääre neen moor anlääse.',
'emailauthenticated'         => 'Jou Email-Adrässe wuude bestäätiged: $1.',
'emailnotauthenticated'      => 'Jou Email-Adrässe wuude <strong>noch nit bestäätiged</strong>. Deeruum is bit nu neen E-
Mail-Fersoand un Ämpfang foar do foulgjende Funktionen muugelk.',
'emailconfirmlink'           => 'Bestäätigje Jou Email-Adrässe',

# Edit page toolbar
'bold_sample' => 'Fatte Text',
'bold_tip'    => 'Fatte Text',

# Edit pages
'blockedtitle'     => 'Benutser is blokkierd',
'blockedtext'      => "Dien Benutsernoome of dien IP-Adrässe wuude fon $1 speerd. As Gruund wuude anroat ''$2''.

Ju Duur fon ju Speerenge fint sik in $4. Deer IP-Adrässen bie fuul Providere dynamisk ferroat wäide, kon sun Speerenge oafte uk Uunscheeldige träffe, t.B. wan die bie dän Ienwoal ju IP-Adrässe fon wäl touwiesd wuude, die eer in Wikipedia Uunfuuch anstoald häd. Fals ju speerde IP-Adrässe n Proxy fon AOL is, koast du as AOL-Benutser ju Speerenge uumgunge, truch n uur [[Browser]] tou ferweenden as dän AOL-Browser. Wan du ju Meenenge bäst, dät ju Speerenge uungjuchtfäidich waas, weende die dan jädden mäd Angoawe fon ju IP-Adrässe ($3) of dän Benutsernoome, dän Speergruund un ne Beschrieuwenge fon dien Beoarbaidengen uur Email an (email-address) Uum ju Oarbaidsbeläästenge foar do Fräiwillige, do sik uum sukke Fälle kummerje, gering tou hoolden, weende die deerum jädden bloot bie laangere Speerengen an disse Adrässe. Speerengen weegen Vandalismus fon dän Benutser, die eer ju beträffende IP-Adrässe bruukt häd, schuulen ätter kuute Tied ouloope.",
'accmailtitle'     => 'Paaswoud wuude fersoand.',
'accmailtext'      => 'Dät Paaswoud fon "$1" wuude an $2 soand.',
'anontalkpagetext' => "----''Dit is ju Diskussionssiede fon n uunbekoanden Benutser, die sik nit anmälded häd. Wail naan Noome deer is, wäd ju nuumeriske [[IP-Adrässe]] tou Identifizierenge ferwoand. Man oafte wäd sunne Adrässe fon moorere Benutsere ferwoand. Wan du n uunbekoanden Benutser bääst un du toankst dät du Kommentare krichst do nit foar die meend sunt, dan koast du ap bääste dien [[Special:Userlogin|anmäldje]], uum sukke Fertuusengen tou fermieden.''",
'clearyourcache'   => "'''Bemäärkenge:''' Ätter dät Fäästlääsen kon dät nöödich weese, dän Browser-Cache loostoumoakjen, uum do Annerengen sjo tou konnen.",
'editing'          => 'Beoarbaidjen fon $1',
'editinguser'      => 'Beoarbaidje fon Benutser <b>$1</b>',
'editingsection'   => 'Beoarbaidje fon $1 (Apsats)',
'editingcomment'   => 'Beoarbaidjen fon $1 (Kommentoar)',
'editconflict'     => 'Beoarbaidengs-Konflikt: "$1"',
'editingold'       => '<strong>OACHTENGE: Jie beoarbaidje ne oolde Version fon disse Artikkel. Wan Jie spiekerje, wäide alle näiere Versione uurschrieuwen.</strong>',
'copyrightwarning' => 'Aal Biedraage tou dän {{SITENAME}} wäide betrachted as stoundend unner ju GNU Fräie Dokumentationslizenz (sjuch fääre: "$1"). Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".<br />
Iek fersicherje hiermäd, dät iek dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset un willigje ien, him unner dän [[Wikipedia:Lizenzbestimmungen|GNU-Lizenz für freie Dokumentation]] tou fereepentlikjen.',
'edittools'        => '<!-- Text hier stoant unner Beoarbaidengsfäildere un Hoochleedefäildere. -->',

# History pages
'currentrevisionlink' => 'Aktuälle Version ounsjo',
'cur'                 => 'Aktuäl',
'deletedrev'          => '[läsked]',

# Diffs
'difference'              => '(Unnerschied twiske Versionen)',
'editcurrent'             => 'Ju aktuälle Version fon disse Artikkel beoarbaidje',
'compareselectedversions' => 'Wäälde Versione ferglieke',

# Preferences page
'changepassword' => 'Paaswoud annerje',
'dateformat'     => 'Doatumsformoat',
'datedefault'    => 'Neen Preferenz',
'datetime'       => 'Doatum un Tied',
'contextlines'   => 'Teekene pro Träffer:',
'contextchars'   => 'Teekene pro Riege:',
'allowemail'     => 'Emails fon uur Benutsere kriegen',
'defaultns'      => 'In disse Noomensruume schäl standoardmäitich soacht wäide:',

# User rights
'editusergroup' => 'Beoarbaidede Benutsergjuchte',

# Recent changes
'diff' => 'Unnerschied',

# Upload
'badfilename' => 'Die Datäi-Noome is automatisk annerd tou "$1".',
'emptyfile'   => 'Ju hoochleedene Doatäi is loos. Die Gruund kon n Typfailer in dän Doatäinoome weese. Kontrollierje jädden, of du ju Doatäi wuddelk hoochleede wolt.',

# Image list
'byname' => 'ätter Noome',
'bydate' => 'ätter Doatum',
'bysize' => 'ätter Grööte',

# MIME search
'download' => 'Deelleede',

'disambiguations'     => 'Begriepskläärengssieden',
'disambiguationspage' => 'Template:Begriepskläärenge',

'doubleredirects'     => 'Dubbelde Fääre-Laitengen',
'doubleredirectstext' => '<b>Oachtenge:</b> Disse Lieste kon "falske Positive" änthoolde. Dät is dan dän Fal, wan aan
Redirect buute dän Redirect-Ferwies noch wiedere Text mäd uur Ferwiesen änthaalt. Doo
Lääste schällen dan wächhoald wäide.',

'brokenredirects'     => 'Ferkierde Truchferwiese',
'brokenredirectstext' => 'Disse Truchferwiese laitje tou nit existierjende Artikkel:',

# Miscellaneous special pages
'allpages'     => 'Aal Artikkele',
'deadendpages' => 'Siede sunner Ferwiese',

'categoriespagetext' => 'Do foulgjende Kategorien existierje in de Wiki.',
'data'               => 'Failer in dän Doatenboank',

# Special:Log
'alllogstext' => 'Dit is ne kombinierde Anwiesenge fon aal Logs fon {{SITENAME}}.',

# Special:Allpages
'allpagesfrom'      => 'Sieden wiese fon:',
'allarticles'       => 'Aal do Artikkele',
'allinnamespace'    => 'Aal Sieden in $1 Noomenruum',
'allnotinnamespace' => 'Aal Sieden, bute in dän $1 Noomenruum',
'allpagesprev'      => 'Foargungende',
'allpagesnext'      => 'Naiste',
'allpagessubmit'    => 'Anweende',

# E-mail user
'emailuser'     => 'Seende E-Mail an disse Benutser',
'emailpage'     => 'E-mail an Benutser',
'emailpagetext' => 'Wan disse Benutser ne gultige Email-Adrässe anroat häd, konnen Jie him mäd dän unnerstoundene Formuloar ne E-mail seende. As Ouseender wäd ju E-mail-Adrässe uut Jou Ienstaalengen iendrain, deermäd die Benutser Jou oantwoudje kon.',
'emailfrom'     => 'Fon',
'emailto'       => 'An',
'emailsubject'  => 'Beträf',
'emailmessage'  => 'Ättergjucht',
'emailsend'     => 'Seende',
'emailsent'     => 'Begjucht fersoand',
'emailsenttext' => 'Jou Begjucht is soand wuuden.',

# Watchlist
'addedwatch'     => 'An Foulgelieste touföiged.',
'addedwatchtext' => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:Recentchanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat. 

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",

'enotif_mailer'                => '{{SITENAME}} tält Bescheed uur Email',
'enotif_reset'                 => 'Markier aal besoachte Sieden',
'enotif_newpagetext'           => 'Dit is ne näie Siede.',
'enotif_impersonal_salutation' => '{{SITENAME}} Benutser',
'changed'                      => 'annerd',
'created'                      => 'näi anlaid',
'enotif_subject'               => '{{SITENAME}} Siede $PAGETITLE wuude $CHANGEDORCREATED fon $PAGEEDITOR',
'enotif_lastdiff'              => '$1 wiest alle Annerengen mäd aan Glap.',
'enotif_body'                  => 'Ljoowe $WATCHINGUSERNAME, 

ju {{SITENAME}} Siede $PAGETITLE wuude fon $PAGEEDITOR an dän $PAGEEDITDATE $CHANGEDORCREATED, ju aktuälle Version is: $PAGETITLE_URL 

$NEWPAGE 

Touhoopefoatenge fon dän Editor: $PAGESUMMARY $PAGEMINOREDIT 

Kontakt toun Editor: 
Mail $PAGEEDITOR_EMAIL 
Wiki $PAGEEDITOR_WIKI 

Deer wäide soloange neen wiedere Mails toun Bescheed soand, bit Jie ju Siede wier besäike. Ap Jou Beooboachtengssiede konnen Jie aal Markere toun Bescheed touhoope touräächsätte. 

Jou früntelke {{SITENAME}} Becheedtälsystem 

--- 
Jou Beooboachtengslieste 
{{SERVER}}{{localurl:Special:Watchlist/edit}} 

Hälpe tou ju Benutsenge rakt 
{{SERVER}}{{localurl:WikiHelpdesk}}',

# Delete/protect/revert
'deletepage'        => 'Siede läskje',
'confirm'           => 'Bestäätigje',
'excontent'         => "Oolde Inhoold: '$1'",
'excontentauthor'   => "Inhoold waas: '$1' (eensige Benutser: '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'     => "Inhoold foar dät Loosmoakjen fon de Siede: '$1'",
'exblank'           => 'Siede waas loos',
'confirmdelete'     => 'Läskenge bestäätigje',
'deletesub'         => '(Läskje "$1")',
'confirmdeletetext' => 'Jie sunt deerbie, n Artikkel of ne Bielde un aal allere Versione foar altied uut dän Doatenboank tou läskjen. Bitte bestäätigje Jie Jou Apsicht, dät tou dwoon, dät Jie Jou do Konsekwänsen bewust sunt, un dät Jie in Uureenstämmenge mäd uus [[{{MediaWiki:policy-url}}]] honnelje.',
'deletedtext'       => '"$1" wuude läsked. 
In $2 fiende Jie ne Lieste fon do lääste Läskengen.',
'deletedarticle'    => '"$1" wuude läsked',
'dellogpage'        => 'Läsk-Logbouk',
'dellogpagetext'    => 'Hier is ne Lieste fon do lääste Läskengen.',
'deletionlog'       => 'Läsk-Logbouk',
'deletecomment'     => 'Gruund foar ju Läskenge',
'cantrollback'      => 'Disse Annerenge kon nit touräächstoald wäide; deer et naan fröieren Autor rakt.',
'editcomment'       => 'Ju Annerengskommentoar waas: "<i>$1</i>".', # only shown if there is an edit comment

# Contributions
'contributions' => 'Benutserbiedraage',

# Block/unblock
'blockip'            => 'Blokkierje Benutser',
'badipaddress'       => 'Dissen Benutser bestoant nit, d.h. die Noome is falsk',
'blockipsuccesssub'  => 'Blokkoade geloangen',
'blockipsuccesstext' => 'Ju IP-Adrässe [[Special:Contributions/$1|$1]] wuude blokkierd.
<br />[[Special:Ipblocklist|Lieste fon Blokkoaden]].',
'blocklistline'      => '$1, $2 blokkierde $3 ($4)',
'blocklink'          => 'blokkierje',
'contribslink'       => 'Biedraage',
'autoblocker'        => 'Du wierst blokkierd, deer du eene IP-Adrässe mäd "[[User:$1|$1]]" benutsjen dääst. Foar ju Blokkierenge fon dän Benutser waas as Gruund anroat: "$2".',
'blocklogpage'       => 'Benutserblokkoaden-Logbouk',
'blocklogentry'      => '[[$1]] blokkierd foar n Tiedruum fon: $2 $3',
'blocklogtext'       => 'Dit is n Logbouk fon Speerengen un Äntspeerengen fon Benutsere. Ju Sunnersiede fiert aal aktuäl speerde Benutsere ap, iensluutend automatisk blokkierde IP-Adrässe.',

# Move page
'articleexists'           => 'Dät rakt al n Siede mäd disse Noome, of uurs is die Noome dän du anroat hääst, nit toulät.
Fersäik jädden n uur Noome.',
'delete_and_move'         => 'Läskje un ferschuuwe',
'delete_and_move_text'    => '==Sielartikkel is al deer, läskje?== 

Die Artikkel "[[$1]]" existiert al. Moatest du him foar ju Ferschuuwenge läskje?',
'delete_and_move_confirm' => 'Jee, Sielartikkel foar ju Ferschuuwenge läskje',
'delete_and_move_reason'  => 'Läsked uum Plats tou moakjen foar Ferschuuwenge',

# Namespace 8 related
'allmessages'        => 'Aal Ättergjuchte',
'allmessagesname'    => 'Noome',
'allmessagesdefault' => 'Standardtext',
'allmessagescurrent' => 'Disse Text',
'allmessagestext'    => 'Dit is ne Lieste fon aal System-Ättergjuchte do in dän MediaWiki-Noomenruum tou Ferföigenge stounde.',

# Attribution
'creditspage' => 'Siedenstatistik',

# Spam protection
'categoryarticlecount' => 'Tou disse Kategorie heere $1 Artikkele.',
'category-media-count' => 'Tou disse Kategorie heere $1 Artikkele.',

# Image deletion
'deletedrevision' => 'Oolde Version $1 läsked',

# EXIF tags
'exif-bitspersample'            => 'Bits pro Faawenkomponente',
'exif-compression'              => 'Oard fon ju Kompression',
'exif-datetime'                 => 'Spiekertiedpunkt',
'exif-artist'                   => 'Photograph',
'exif-copyright'                => 'Uurheebergjuchte',
'exif-exifversion'              => 'Exif-Version',
'exif-flashpixversion'          => 'unnerstöände Flashpix-Version',
'exif-colorspace'               => 'Faawenruum',
'exif-componentsconfiguration'  => 'Betjuudenge fon älke Komponente',
'exif-compressedbitsperpixel'   => 'Komprimierde Bits pro Pixel',
'exif-datetimeoriginal'         => 'Ärfoatengstiedpunkt',
'exif-datetimedigitized'        => 'Digitalisierengstiedpunkt',
'exif-exposuretime'             => 'Beljoachtengsduur',
'exif-exposureprogram'          => 'Beljuchtengsprogram',
'exif-aperturevalue'            => 'Bländenwäid',
'exif-brightnessvalue'          => 'Ljoachtegaidswäid',
'exif-exposurebiasvalue'        => 'Beljuchtengsfoargoawe',
'exif-flash'                    => 'Blits (Loai!)',
'exif-flashenergy'              => 'Blitsstäärke',
'exif-exposureindex'            => 'Beljuchtengsindex',
'exif-filesource'               => 'Wälle fon ju Doatäi',
'exif-cfapattern'               => 'CFA-Muster',
'exif-customrendered'           => 'Benutserdefinierde Bieldeferoarbaidenge',
'exif-exposuremode'             => 'Beljuchtengsmodus',
'exif-digitalzoomratio'         => 'Digitoalzoom',
'exif-contrast'                 => 'Kontrast',
'exif-devicesettingdescription' => 'Reewen-Ienstaalenge',

# EXIF attributes
'exif-compression-1' => 'Uunkomprimierd',

'exif-componentsconfiguration-0' => 'Bestoant nit',

'exif-exposureprogram-0' => 'Uunbekoand',
'exif-exposureprogram-1' => 'Manuäl',
'exif-exposureprogram-2' => 'Standoardprogram',
'exif-exposureprogram-3' => 'Tiedautomatik',
'exif-exposureprogram-4' => 'Bländenautomatik',
'exif-exposureprogram-5' => 'Kreativprogram mäd Befoarluukenge fon ne hooge Schäärpendjupte',
'exif-exposureprogram-6' => 'Aktion-Program mäd Befoarluukenge fon ne kute Beljoachtengstied',
'exif-exposureprogram-7' => 'Portrait-Program',
'exif-exposureprogram-8' => 'Londskupsapnoamen',

'exif-customrendered-0' => 'Standoard',
'exif-customrendered-1' => 'Benutserdefinierd',

'exif-contrast-0' => 'Normoal',
'exif-contrast-1' => 'Swäk',
'exif-contrast-2' => 'Stäärk',

# External editor support
'edit-externally'      => 'Disse Doatäi mäd n extern Program beoarbaidje',
'edit-externally-help' => 'Sjuch [http://meta.wikimedia.org/wiki/Hilfe:Externe_Editoren Installations-Anweisungen] foar
wiedere Informatione.',

# Delete conflict
'deletedwhileediting' => 'Oachtenge: Disse Siede wuude al läsked ätter dät du anfangd hiedest, hier tou beoarbaidjen!
Wan du disse Siede spiekerst, wäd ju deeruum näi anlaid.',

# action=purge
'confirm_purge' => 'Dän Cache fon disse Siede loosmoakje?

$1',

);
