<?php
/** Seeltersk (Seeltersk)
 *
 * @addtogroup Language
 *
 * @author Maartenvdbent
 * @author Nike
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

'delete'         => 'Läskje',
'deletethispage' => 'Disse Siede läskje',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'bugreports'        => 'Kontakt Wikipedia',
'copyright'         => 'Inhoold is ferföichboar unner de $1.',
'currentevents'     => 'Aktuälle Geböärnisse',
'currentevents-url' => 'Aktuälle Geböärnisse',
'disclaimerpage'    => 'Project:Siede tou Begriepskläärenge',

# General errors
'dberrortext'     => 'Dät roat n Syntaxfailer in dän Doatenboankoufroage. Ju lääste Doatenboankoufroage lutte:
<blockquote><tt>$1</tt></blockquote> uut de Funktion "<tt>$2</tt>". MySQL mäldede dän Failer "<tt>$3: $4</tt>".',
'cachederror'     => 'Dät Foulgjende is ne Kopie uut de Cache un is fielicht ferallerd.',
'badarticleerror' => 'Disse Honnelenge kon ap disse Siede nit moaked wäide.',
'cannotdelete'    => 'Kon spezifizierde Siede of Artikkel nit läskje. Fielicht is ju al läsked wuuden.',
'badtitle'        => 'Uungultige Tittel.',
'badtitletext'    => 'Die anfräigede Tittel waas uungultich, loos, of n uungultige Sproaklink fon n uur Wiki.',

# Login and logout pages
'createaccount'              => 'Benutserkonto anlääse',
'createaccountmail'          => 'Uur Email',
'badretype'                  => 'Do bee Paaswoude stimme nit uureen.',
'badsig'                     => 'Signatursyntax is uungultich; HTML uurpröiwje.',
'acct_creation_throttle_hit' => 'Du hääst al $1 Benutserkonten anlaid. Du koast fääre neen moor anlääse.',

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
'copyrightwarning' => 'Aal Biedraage tou dän {{SITENAME}} wäide betrachted as stoundend unner ju GNU Fräie Dokumentationslizenz (sjuch fääre: "$1"). Fals Jie nit moaten dät Jou Oarbaid hier fon uur Ljuude ferannerd un fersprat wäd, dan drukke Jie nit ap "Spiekerje".<br />
Iek fersicherje hiermäd, dät iek dän Biedraach sälwen ferfoated hääbe blw. dät hie neen froamd Gjucht ferlätset un willigje ien, him unner dän [[Wikipedia:Lizenzbestimmungen|GNU-Lizenz für freie Dokumentation]] tou fereepentlikjen.',

# History pages
'currentrevisionlink' => 'Aktuälle Version ounsjo',
'cur'                 => 'Aktuäl',
'deletedrev'          => '[läsked]',

# Diffs
'difference'              => '(Unnerschied twiske Versionen)',
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

# Recent changes
'diff' => 'Unnerschied',

# Upload
'badfilename' => 'Die Datäi-Noome is automatisk annerd tou "$1".',

# Image list
'byname' => 'ätter Noome',
'bydate' => 'ätter Doatum',
'bysize' => 'ätter Grööte',

'disambiguations'     => 'Begriepskläärengssieden',
'disambiguationspage' => 'Foarloage:Begriepskläärenge',

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

# Watchlist
'addedwatch'     => 'An Foulgelieste touföiged.',
'addedwatchtext' => "Die Artikkel \"[[:\$1]]\" wuude an dien [[Special:Watchlist|Foulgelieste]] touföiged.
Leetere Annerengen an dissen Artikkel un ju touheerende Diskussionssiede wäide deer liested
un die Artikkel wäd in ju [[Special:Recentchanges|fon do lääste Annerengen]] in '''Fatschrift''' anroat. 

Wan du die Artikkel wier fon ju Foulgelieste ou hoalje moatest, klik ap ju Siede ap \"Ferjeet disse Siede\".",

'changed' => 'annerd',
'created' => 'näi anlaid',

# Delete/protect/revert
'deletepage'        => 'Siede läskje',
'confirm'           => 'Bestäätigje',
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

# Delete conflict
'deletedwhileediting' => 'Oachtenge: Disse Siede wuude al läsked ätter dät du anfangd hiedest, hier tou beoarbaidjen!
Wan du disse Siede spiekerst, wäd ju deeruum näi anlaid.',

# action=purge
'confirm_purge' => 'Dän Cache fon disse Siede loosmoakje?

$1',

);
