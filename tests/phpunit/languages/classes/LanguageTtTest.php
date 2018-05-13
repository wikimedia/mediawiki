<?php
/**
 * PHPUnit tests for the Tatar language.
 * The language can be represented using two scripts:
 *  - Latin (tt-latn)
 *  - Cyrillic (tt-cyrl)
 *
 * @author Dinar Qurbanov
 * this file is created by copying LanguageUzTest.php
 *
 *
 */

/** Tests for MediaWiki languages/LanguageTt.php */
class LanguageTtTest extends LanguageClassesTestCase {

	public $textPairs = [
		/*
		// temporary tests at first, for particular cases
		[
'җәмгыять җәмгыяте җәмгыятькә җәмгысе',
'cämğiät cämğiäte cämğiätkä cämğese'
		],
		[
// apostrophe test
'юмья тәэсир маэмай   мәсьәлән коръән эльвира натураль',
'yum\'ya tä\'sir ma\'may   mäs\'älän qör\'än el\'vira natural\''
		],
		[
// common orthography, some of them are incorrect;
'2018нче никях ассимиляция җәмгыять җәмгыяте җәмгыятькә җәмгысы җәмгысе
ихтияҗның галерея галереясе',
'2018nçe nikax assimilätsiä cämğıyät cämğıyäte cämğıyätkä cämğısı cämğese
ihtiacnıñ galereyä galereyäse'
		],
		[
// words with russian root
'телерадиокомпаниясенең сайты агентлыгы агентлыкларының автор материаллары',
''
		],
		[
// words with arabic root
'Төркия рәсми сәхифәдә хокукларына хәбәрләр',
''
		],
		[
// words consisting of two tatar words
'бераз акъегет чукмарбашның Мүксыманнарның берничә күпьеллык
суүсемнәренең бердәнбер буынтыгаяклыларның гөлбакча аскормаларының көньяк-көнчыгышында
илбашы беррәттән мүксыманнар җепсыман',
'beraz aqyeget çuqmarbaşnıñ Müksımannarnıñ berniçä küpyıllıq
suüsemnäreneñ berdänber buıntığayaqlılarnıñ gölbaqça asqormalarınıñ könyaq-könçığışında
ilbaşı berrättän müksımannar cepsıman'
		],
		*/
		// 0
		[
'Төркия
Төркия телерадиокомпаниясенең рәсми веб сайты.
Бу сәхифәдә "Anadolu" агентлыгы (AA),
"Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters",
"Deutsche Press Agentur" (DPA), ATSH,
EFE, MENA, ITAR TASS, "XINHUA" агентлыкларының
автор хокукларына кергән материаллары урнашкан. Материаллар һәм хәбәрләр рөхсәтсез
кулланылмас, копияланмас. ТРТ тышкы бәйләнешле сайтларның эчтәлегеннән җаваплы түгел.
Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк:"Төркия белән Көньяк Корея арасындагы
мөнәсәбәтләр стратегик уртаклык дәрәҗәсендә"
Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк Көньяк Корея –Төркия дуслык төркеме
башлыгы Hankoo Lee һәм янындагы вәкиллекне кабул итте.
Җәмил Чичәк очрашуда ике илнең географик яктан бер-берсеннән бик ерак булса да
мөнәсәбәтләр җәһәтеннән якын булуын белдерде.
Төркия белән Көньяк Корея арасындагы мөнәсәбәтләрнең стратегик уртаклык дәрәҗәсендә
булуына басым ясаучы Җәмил Чичәк “2017 нче елда мөнәсәбәтләребезнең 60 еллыгын
билгеләп үтәчәкбез. Бу исә элемтәләрне тагын камилләштерәчәк
һәм халыкларны да үзара якынлаштырачак” диде.',
// http://www.trt.net.tr/tatarca/t%C3%B6rkiy%C3%A4/2015/03/10/
// %C3%A7i%C3%A7%C3%A4k-k%C3%B6nyaq-koreya-%E2%80%93t%C3%B6rkiy%C3%A4
// -dusl%C4%B1-t%C3%B6rkeme-w%C3%A4killegen-qabul-itt-181766
// uı, üe according to http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/16.jpg .
// though other rule is in http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/02.jpg ,
// 04.jpg , 05.jpg , as i understand 16.jpg etc is final rule.
// see also
// https://tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_06.jpg
// teleradiokompaniä, not teleradiokompania is according to examples in the rules
'Törkiä
Törkiä teleradiokompaniäseneñ räsmi web saytı.
Bu säxifädä "-{Anadolu}-" agentlığı (-{AA}-),
"-{Agence France-Presse}-" (-{AFP}-), "-{Associated Press}-" (-{AP}-), "-{Reuters}-",
"-{Deutsche Press Agentur}-" (-{DPA}-), -{ATSH}-,
-{EFE}-, -{MENA}-, -{ITAR TASS}-, "-{XINHUA}-" agentlıqlarınıñ
avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez
qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.
Törkiä Böyek Millät Mäclese başlığı Cämil Çiçäk:"Törkiä belän Könyaq Koreya arasındağı
mönäsäbätlär strategik urtaqlıq däräcäsendä"
Törkiä Böyek Millät Mäclese başlığı Cämil Çiçäk Könyaq Koreya –Törkiä duslıq törkeme
başlığı -{Hankoo Lee}- häm yanındağı wäkillekne qabul itte.
Cämil Çiçäk oçraşuda ike ilneñ geografik yaqtan ber-bersennän bik yıraq bulsa da
mönäsäbätlär cähätennän yaqın buluın belderde.
Törkiä belän Könyaq Koreya arasındağı mönäsäbätlärneñ strategik urtaqlıq däräcäsendä
buluına basım yasawçı Cämil Çiçäk “2017 nçe yılda mönäsäbätlärebezneñ 60 yıllığın
bilgeläp ütäçäkbez. Bu isä elemtälärne tağın kamilläşteräçäk
häm xalıqlarnı da üzara yaqınlaştıraçaq” dide.'
		],
		// 1
		[
'“Гадәләт һәм калкыну” партиясеннән халык ышанычлысы
кандидат намзәтлеге мөрәҗәгатен
кире алганнан соң Фидан кабат милли күзләү оешмасы киңәшчесе итеп билгеләнде
Хакан Фидан “Гадәләт һәм калкыну” партиясеннән халык
ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алды.
Бу сәбәпле Фидан премьер-министр Әхмәт Давытоглу тарафыннан элекке
эше булган милли күзләү оешмасы киңәшчесе вазыйфасына кабат билгеләнде.
Премьер-министр урынбасары һәм хөкүмәт сүзчесе Бүләнт
Арынч министрлар шурасыннан соң үткәрелгән матбугат
очрашуында “Премьер-министр Хакан Фиданны кабат милли
күзләү оешмасы киңәшчесе итеп билгеләде” диде.
Хакан Фидан халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алганнан соң болай дип
белдерү ясады: ” Илемә һәм милләтемә хезмәт
итү юлында , бүгенге көнгә кадәр булганы кебек
моннан соң да үземә бирелгән вазыйфаны җиренә җиткереп башкару өчен тырышачакмын.
Миңа яклау күрсәткән һәм ышанган илбашыбыз һәм
премьер-министрга , газиз халкыма рәхмәтләремне җиткерәм”.',
// http://www.trt.net.tr/tatarca/t%C3%B6rkiy%C3%A4/2015/03/10/
// hakan-fidan-qabat-%C3%BCz-waz%C4%B1yfas%C4%B1nda-181748
'“Ğädälät häm qalqınu” partiäsennän xalıq ışanıçlısı
kandidat namzätlege möräcäğäten
kire alğannan soñ Fidan qabat milli küzläw oyışması kiñäşçese itep bilgelände
Xaqan Fidan “Ğädälät häm qalqınu” partiäsennän xalıq
ışanıçlısı kandidat namzätlege möräcäğäten kire aldı.
Bu säbäple Fidan premyer-ministr Äxmät Dawıtoğlu tarafınnan elekke
eşe bulğan milli küzläw oyışması kiñäşçese wazıyfasına qabat bilgelände.
Premyer-ministr urınbasarı häm xökümät süzçese Bülänt
Arınç ministrlar şurasınnan soñ ütkärelgän matbuğat
oçraşuında “Premyer-ministr Xaqan Fidannı qabat milli
küzläw oyışması kiñäşçese itep bilgeläde” dide.
Xaqan Fidan xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire alğannan soñ bolay dip
belderü yasadı: ” İlemä häm millätemä xezmät
itü yulında , bügenge köngä qädär bulğanı kebek
monnan soñ da üzemä birelgän wazıyfanı cirenä citkerep başqaru öçen tırışaçaqmın.
Miña yaqlaw kürsätkän häm ışanğan ilbaşıbız häm
premyer-ministrğa , ğaziz xalqıma räxmätläremne citkeräm”.'
		],
		// 2
		[
'Төркиянең көньяк-көнчыгышында үсеш планы
Урнаштыру 09.03.2015 Яңарту 10.03.2015
Төркия Премьер-министры Әхмәт Давытоглу
Көньяк-көнчыгыш Анадолу проектының (GAP) гамәл планын ачыклады.
Давытоглу яңарыш яклы, икътисади һәм иҗтимагый үсешне тизләтүче, эш белән
тәэмин итүне арттыручы, җитештерүчәнлеккә юнәлгән, һуманитар калкыну-үсешне
кызыксындыручы, тәшвикъ итүче яңа сәясәт алып барачакларын белдерде.
Гамәл планының 5 төп өлкәдән торуын ассызыклаучы Давытоглу бу планга 2018нче
ел ахырына хәтле үзәк идарә бюджетыннан якынча 27
миллиард лира күләмендә инвестиция аерылып куелачагын әйтте.
План нәтиҗәсендә GAP Көньяк-көнчыгыш Анадолу проекты кысаларында туплам
1 миллион 100 мең гектар мәйданның сугарылуы өчен саклагыч аскормаларының
тәмамланачагын, су челтәре төзелешенең дә мөһим күләмдә бетереләчәгенә
игътибарны юнәлтүче Премьер-министр натураль игенчелек белән шөгыльләнүче
игенчеләрнең салымнарына финанс ярдәм күрсәтеләчәген белдерде.
"Гамәл планы” азагы булган 2018 нче елда эшсезлек күләмен
киметүдә тәвәккәлбез”, - дип әйтүче Давытоглу төбәктәге чик
буе капкаларын да көчәйтәчәкләрен ассызыклап әйтеп узды.
Давытоглу мәгариф, туризм һәм сәламәтлек саклау
өлкәсендә дә мөһим адымнар ясалачагын әйтте.',
// ictimaği : see
// tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_10.jpg
// http://www.trt.net.tr/tatarca/t%C3%B6rkiy%C3%A4/2015/03/09/
// t%C3%B6rkiy%C3%A4ne%C3%B1-k%C3%B6nyaq-k%C3%B6n%C3%A7%C4%B1%C4%9F%C4%B1%C5%9F%C4%B1nda
// -%C3%BCse%C5%9F-plan%C4%B1-180981
'Törkiäneñ könyaq-könçığışında üseş planı
Urnaştıru 09.03.2015 Yañartu 10.03.2015
Törkiä Premyer-ministrı Äxmät Dawıtoğlu
Könyaq-könçığış Anadolu proyektınıñ (-{GAP}-) ğämäl planın açıqladı.
Dawıtoğlu yañarış yaqlı, iqtisadi häm ictimaği üseşne tizlätüçe, eş belän
tä\'min itüne arttıruçı, citeşterüçänlekkä yünälgän, humanitar qalqınu-üseşne
qızıqsındıruçı, täşwiq itüçe yaña säyäsät alıp baraçaqların belderde.
Ğämäl planınıñ 5 töp ölkädän toruın assızıqlawçı Dawıtoğlu bu planğa 2018nçe
yıl axırına xätle üzäk idärä byudjetınnan yaqınça 27
milliard lira külämendä investitsiä ayırılıp quyılaçağın äytte.
Plan näticäsendä -{GAP}- Könyaq-könçığış Anadolu proyektı qısalarında tuplam
1 million 100 meñ gektar mäydannıñ suğarıluı öçen saqlağıç asqormalarınıñ
tämamlanaçağın, su çeltäre tözeleşeneñ dä möhim külämdä betereläçägenä
iğtibarnı yünältüçe Premyer-ministr natural\' igençelek belän şöğellänüçe
igençelärneñ salımnarına finans yärdäm kürsäteläçägen belderde.
"Ğämäl planı” azağı bulğan 2018 nçe yılda eşsezlek külämen
kimetüdä täwäkkälbez”, - dip äytüçe Dawıtoğlu töbäktäge çik
buyı qapqaların da köçäytäçäklären assızıqlap äytep uzdı.
Dawıtoğlu mäğärif, turizm häm sälämätlek saqlaw
ölkäsendä dä möhim adımnar yasalaçağın äytte.'
		],
		// 3
		[
			'Министрлар шурасы җыелышы
Министрлар шурасы җыелышы
Урнаштыру 09.03.2015 Яңарту 09.03.2015
А А
Җыелышны илбашы Рәҗәп Таййип Әрдоган җитәкли
Министрлар шурасы илбашы идарәсе сараенда җыелды.
Төркия илбашы Рәҗәп Таййип Әрдоган икенче тапкыр министрлар шурасын җитәкли.
Шурада ил эчендә иминлек пакеты, валюта курсындагы
хәрәкәтлелек, илдә террорны бетерү өчен алып барылган чишелеш
барышы, хатын-кыз проблемалары , Сүрия һәм Гыйрак чик буйларындагы тәрәккыятьләр карала.
Төркия илбашы Рәҗәп Таййип Әрдоган илбашы булып
сайланганнан соң Төп Канунның үзенә биргән вәкаләтне кулланып министрлар шурасын
беренче тапкыр 19 нчы гыйнварда җитәкләгән иде.',
// http://www.trt.net.tr/tatarca/t%C3%B6rkiy%C3%A4/2015/03/09/
// ministrlar-%C5%9Furas%C4%B1-c%C4%B1y%C4%B1l%C4%B1%C5%9F%C4%B1-180953
			'Ministrlar şurası cıyılışı
Ministrlar şurası cıyılışı
Urnaştıru 09.03.2015 Yañartu 09.03.2015
A A
Cıyılışnı ilbaşı Räcäp Tayyip Ärdoğan citäkli
Ministrlar şurası ilbaşı idäräse sarayında cıyıldı.
Törkiä ilbaşı Räcäp Tayyip Ärdoğan ikençe tapqır ministrlar şurasın citäkli.
Şurada il eçendä iminlek paketı, valyuta kursındağı
xäräkätlelek, ildä terrornı beterü öçen alıp barılğan çişeleş
barışı, xatın-qız problemaları , Süriä häm Ğıyraq çik buylarındağı täräqqiätlär qarala.
Törkiä ilbaşı Räcäp Tayyip Ärdoğan ilbaşı bulıp
saylanğannan soñ Töp Qanunnıñ üzenä birgän wäkälätne qullanıp ministrlar şurasın
berençe tapqır 19 nçı ğinwarda citäklägän ide.'
		],
		// 4
		[
'Бу мәкаләнең кирилл әлифбасындагы игезәге бар.
Гарәп әлифбасын кулланучы илләр:
бердән-бер рәсми әлифба
башка(сы/лары) белән беррәттән.
Хәзерге гарәп әлифбасында язылган китапның ачылыш бите.
Гарәп әлифбасы бүгенге дөньяда аеруча киң
таралган язулардан берсе. Кайбер мәгълүматларга
караганда, ул дөньяда барлык халыкның якынча йөздән унына хезмәт итә. Беренче нәүбәттә
ул – Гарәп теле өчен. Моннан тыш, Гарәп әлифбасы Иранда Фарсы теле, Пакьстанда Урду
теле, Әфганстанда Пушту вә Дари теле өчен, өлешчә Һиндстанда милли әлифба буларак
кабул ителгән. Ул шулай ук Ислам дине киң таралган башка илләрдә дә (Бангладеш,
Хәбәшстан, Филиппиннар, Индонезия, Малайзия, Мозамбик, Нигерия һ.б.) актив кулланылышта
йөри. XX гасырның 30 елларда тулысынча Латин әлифбасы белән алыштырылганча, Советлар
Берлегендә яшәүче төрки халыкларның, шулай ук Госман империясендәге төрекләренең
милли язулары да шушы әлифбага нигезләнгән иде.
1 Тарих
2 Әлифба тозелеше
2.1 Төп хәрефләр
2.1.1 Тартыклар
2.2 Хәрәкәләр
2.2.1 Сузыклар
2.2.2 Тәнвин хәрәкәләре
2.2.3 Башка хәрәкәләр
2.2.3.1 Тәшдид
2.2.3.2 Сүкүн/Сәкен
2.2.3.3 Һәмзә
2.2.3.4 Грамматик род билгесе (Мәрбүтә)
2.3 Башка хәрефләр
2.4 Ассимиляция
3 Саннарның язылышы
4 Гарәп әлифбасы башка телләрдә
5 Гарәп теле вә санак
6 Моны да кара
7 Пәрәвездә',
// https://tt.wikipedia.org/wiki/%C4%9E%C3%A4r%C3%A4p_%C3%A4lifbas%C4%B1
// should be mäğlümätlärgä, but let me count it as it borrowed as mäğlümat,
// and "thick" endings prove that.
// "mäğlümatlarğa" makes conversion easier.
'Bu mäqäläneñ kirill älifbasındağı igezäge bar.
Ğäräp älifbasın qullanuçı illär:
berdän-ber räsmi älifba
başqa(sı/ları) belän berrättän.
Xäzerge ğäräp älifbasında yazılğan kitapnıñ açılış bite.
Ğäräp älifbası bügenge dönyada ayıruça kiñ
taralğan yazulardan berse. Qayber mäğlümatlarğa
qarağanda, ul dönyada barlıq xalıqnıñ yaqınça yözdän unına xezmät itä. Berençe näwbättä
ul – Ğäräp tele öçen. Monnan tış, Ğäräp älifbası İranda Farsı tele, Pakstanda Urdu
tele, Äfğänstanda Puştu wä Dari tele öçen, öleşçä Hindstanda milli älifba bularaq
qabul itelgän. Ul şulay uq İslam dine kiñ taralğan başqa illärdä dä (Bangladeş,
Xäbäşstan, Filippinnar, İndoneziä, Malayziä, Mozambik, Nigeriä h.b.) aktiv qullanılışta
yöri. XX ğasırnıñ 30 yıllarda tulısınça Latin älifbası belän alıştırılğança, Sovetlar
Berlegendä yäşäwçe törki xalıqlarnıñ, şulay uq Ğosman imperiäsendäge törekläreneñ
milli yazuları da şuşı älifbağa nigezlängän ide.
1 Tarix
2 Älifba tozeleşe
2.1 Töp xäreflär
2.1.1 Tartıqlar
2.2 Xäräkälär
2.2.1 Suzıqlar
2.2.2 Tänwin xäräkäläre
2.2.3 Başqa xäräkälär
2.2.3.1 Täşdid
2.2.3.2 Sükün/Säken
2.2.3.3 Hämzä
2.2.3.4 Grammatik rod bilgese (Märbütä)
2.3 Başqa xäreflär
2.4 Assimilyatsiä
3 Sannarnıñ yazılışı
4 Ğäräp älifbası başqa tellärdä
5 Ğäräp tele wä sanaq
6 Monı da qara
7 Päräwezdä'
		],
		// 5
		[
			'Тарих
Гарәп язуының борынгы ватаны – Үзәк Гарәбстан. Аның беренче үрнәкләре (Лихьяни, Самуди,
Сафи кебек ташъязмалар) Гарәбстан ярымутравы җирендә һәм Сүриянең Көньяк төбәкләрендә
табылган. Аларның язылу вакытлары 1.–4. йөзләргә карый.
Үзенең үсеш юлында Гарәп язуы төрле үзгәрешләр кичергән. Аның чишмә башында борынгы
Финики әлифбасы тора. Әмма Гарәп язуының камилләштерүенә Набати вә Арами
язуларының да зур йогынты ясаганлыгы мәгълүм.
Хәзерге вакытта галимнәр Гарәп язуының иң борынгы үрнәге итеп Куфи язуын саныйлар. Ул
Ефрат елгасы буенда урнашкан Гарәп хәлифәте заманында (7.-9. йөзләр) башкала хезмәтен
үтәгән әл-Куфа шәһәрендә табылган. Соңгырак чорларда, хаттатлар тырышлыгы белән, Гарәп
язуы һаман камилләшә барган, аның күп төрле
шәкелләре барлыкка килгән. Галимнәр раславынча,
аларның саны йөз егермегә җитә. Әмма бүгенге
көндә бу өлгеләрнең алты-җиде төре генә актив
кулланыла. Аларның Гарәпчә атамалары: Куфи,
Нәсх, Сөлс, Рөкъга, Нәстәгъликъ, Диван, Шикәстә.',
// https://tt.wikipedia.org/wiki/%C4%9E%C3%A4r%C3%A4p_%C3%A4lifbas%C4%B1
			'Tarix
Ğäräp yazuınıñ borınğı watanı – Üzäk Ğäräbstan. Anıñ berençe ürnäkläre (Lixyani, Samudi,
Safi kebek taşyazmalar) Ğäräbstan yarımutrawı cirendä häm Süriäneñ Könyaq töbäklärendä
tabılğan. Alarnıñ yazılu waqıtları 1.–4. yözlärgä qarıy.
Üzeneñ üseş yulında Ğäräp yazuı törle üzgäreşlär kiçergän. Anıñ çişmä başında borınğı
Finiki älifbası tora. Ämma Ğäräp yazuınıñ kamilläşterüenä Nabati wä Arami
yazularınıñ da zur yoğıntı yasağanlığı mäğlüm.
Xäzerge waqıtta ğalimnär Ğäräp yazuınıñ iñ borınğı ürnäge itep Kufi yazuın sanıylar. Ul
Yefrat yılğası buyında urnaşqan Ğäräp xälifäte zamanında (7.-9. yözlär) başqala xezmäten
ütägän äl-Kufa şähärendä tabılğan. Soñğıraq çorlarda, xattatlar tırışlığı belän, Ğäräp
yazuı haman kamilläşä barğan, anıñ küp törle
şäkelläre barlıqqa kilgän. Ğalimnär raslawınça,
alarnıñ sanı yöz yegermegä citä. Ämma bügenge
köndä bu ölgelärneñ altı-cide töre genä aktiv
qullanıla. Alarnıñ Ğäräpçä atamaları: Kufi,
Näsx, Söls, Röqğä, Nästäğliq, Diwan, Şikästä.'
		],
		// 6
		[
			'Әлифба тозелеше
Гарәп әлифбасының төп хәрефләре
Гарәп әлифбасы 28 хәрефтән тора, алар барысы да тартыкларны билгеләү өчен.
Гарәпәлиф белән язылган сүзләр уңнан сулга таба языла вә
бер юлдан икенче юлга сүз күчерү нәрсә юк.
Хәрефләр барлык очракларда да бер генә биеклектә языла,
ягъни Гарәп әлифбасында баш хәрефләр юк.
Гарәп сүзләре һәрчак тартыктан гына башлана, әлиф хәрефе дә һәмзә белән башлана.
Гарәп әлифбасында хәрефләрнең дүрт төрле язылу рәвеше кулланыла:
аерым язылышы,
сүз башында,
сүз уртасында,
сүз азагында.',
// https://tt.wikipedia.org/wiki/%C4%9E%C3%A4r%C3%A4p_%C3%A4lifbas%C4%B1
			'Älifba tozeleşe
Ğäräp älifbasınıñ töp xärefläre
Ğäräp älifbası 28 xäreftän tora, alar barısı da tartıqlarnı bilgeläw öçen.
Ğäräpälif belän yazılğan süzlär uñnan sulğa taba yazıla wä
ber yuldan ikençe yulğa süz küçerü närsä yuq.
Xäreflär barlıq oçraqlarda da ber genä bieklektä yazıla,
yäğni Ğäräp älifbasında baş xäreflär yuq.
Ğäräp süzläre härçaq tartıqtan ğına başlana, älif xärefe dä hämzä belän başlana.
Ğäräp älifbasında xäreflärneñ dürt törle yazılu räweşe qullanıla:
ayırım yazılışı,
süz başında,
süz urtasında,
süz azağında.'
		],
		// 7
		[
'Дирижабль (французча dirigeable — идарә ителүче)
- һавадан җиңелрәк булган куәт җайланмасы
белән йөртелә торган һава аппараты. Һава агымнарына
бәйсез, теләгән юнәлешкә хәрәкәт итә ала.
Дирижабль уртача тыгызлыгы һавадан кечерәк, Архимед
кануны буенча аппарат "өскә тартыла" - күтәрелә.
Заманча Рәсәй дирижабле Au-30
Гадәттә дирижабль тышчасы һавадан җиңелрәк булучы газ (гелий, сутудыргыч - водород)
белән тутырыла. Күтәрүчәнлек тышчаның күләменә туры пропорциональ.
Заманча дирижабльда гравитацион һәм аэродинамик тотрыклык системасы урнаштырыла.
Утыру махсус арканнар ярдәмендә бара, һәм причал мачталары кулланыла.
Йөрткечләр - һава роторлары, кайчакта турборотор йөрткечләре файдаланыла.
Пилот (1 яки 2) тангаж белән идарә итеп, аппарат курсын йөртә.
Төрләр
Тышчалар төрләре - йомшак, каты, ярым каты
Куәт җайланмасы - бу йөрткече, бензин, электр, газ-турбиналы, дизель йөрткечләре
Максат - пассажир, йөк, хәрби
Архимед көчен булдыру ысулы - эссе газ, җиңел газ ярдәмендә һ.б.',
// https://tt.wikipedia.org/wiki/Dirijabl
'Dirijabl\' (fransuzça -{dirigeable}- — idärä itelüçe)
- hawadan ciñelräk bulğan qüät caylanması
belän yörtelä torğan hawa apparatı. Hawa ağımnarına
bäysez, telägän yünäleşkä xäräkät itä ala.
Dirijabl\' urtaça tığızlığı hawadan keçeräk, Arximed
qanunı buyınça apparat "öskä tartıla" - kütärelä.
Zamança Räsäy dirijable -{Au-30}-
Ğädättä dirijabl\' tışçası hawadan ciñelräk buluçı gaz (geliy, sutudırğıç - vodorod)
belän tutırıla. Kütärüçänlek tışçanıñ külämenä turı proportsional\'.
Zamança dirijabl\'da gravitatsion häm aerodinamik totrıqlıq sisteması urnaştırıla.
Utıru maxsus arqannar yärdämendä bara, häm priçal maçtaları qullanıla.
Yörtkeçlär - hawa rotorları, qayçaqta turborotor yörtkeçläre faydalanıla.
Pilot (1 yäki 2) tangaj belän idärä itep, apparat kursın yörtä.
Törlär
Tışçalar törläre - yomşaq, qatı, yarım qatı
Qüät caylanması - bu yörtkeçe, benzin, elektr, gaz-turbinalı, dizel\' yörtkeçläre
Maqsat - passajir, yök, xärbi
Arximed köçen buldıru ısulı - esse gaz, ciñel gaz yärdämendä h.b.'
		],
		// 8
		[
'Планёр (французча planeur, planum - яссылык, инглизчә
Glider (sailplane)) - һавадан авыррак моторсыз (чыгарма - мотопланёр)
очу аппараты, канатка исүче һава агымы күтәрү көчен булдыру нәтиҗәсендә планёр оча.
Очкыч планёрларны тизләтә
Махсус катлаулы чыгыр планёрны тизләтә
Шулай ук планер - очу аппаратының нигез конструкциясе дип атала.
Гадәттә белгечләр ике төшенчәне аера:
Планёр - очырлык (салмак кына очып түбәнәя барырлык) аппарат.
Планер - очу аппаратының нигез конструкциясе.
Планёр мисаллары - кәгазь очкычы, кәгазь күгәрчене.
Ирекле очу ноктасына планёр очкыч яки махсус катлаулы чыгыр ярдәмендә китерелә,
аннан соң планёр салмак кына очып түбәнәя бара.',
// https://tt.wikipedia.org/wiki/Planyor
'Planyor (fransuzça -{planeur}-, -{planum}- - yassılıq, inglizçä
-{Glider (sailplane)}-) - hawadan awırraq motorsız (çığarma - motoplanyor)
oçu apparatı, qanatqa isüçe hawa ağımı kütärü köçen buldıru näticäsendä planyor oça.
Oçqıç planyorlarnı tizlätä
Maxsus qatlawlı çığır planyornı tizlätä
Şulay uq planer - oçu apparatınıñ nigez konstruksiäse dip atala.
Ğädättä belgeçlär ike töşençäne ayıra:
Planyor - oçırlıq (salmaq qına oçıp tübänäyä barırlıq) apparat.
Planer - oçu apparatınıñ nigez konstruksiäse.
Planyor misalları - käğäz oçqıçı, käğäz kügärçene.
İrekle oçu noqtasına planyor oçqıç yäki maxsus qatlawlı çığır yärdämendä kiterelä,
annan soñ planyor salmaq qına oçıp tübänäyä bara.'
		],
		// 9
		[
'Автожир боралак кебек күтәрү көчен булдырыр өчен күтәрүче винтка ия, ләкин
автожирда винт аэродинамик көчләр тәэсире нәтиҗәсендә ирекле әйләнә. Ул күтәрү
көчен генә булдыра, винт уңай һөҗүм почмагына ия.
Боралак винты тискәре һөҗүм почмагына ия.
Күтәрүче ротордан тыш автожирда этәрү винты бар, ул автожирның ятма тизлеген булдыра.
Автожир төрләре:
Тартучы винтлы автожирлар (хәзер иң таралган). Үзенчәлеге: кабинадан яхшы күренеш.
Этәрүче винтлы автожирлар. Үзенчәлеге: двигательне
яхшырак суытылу, иминлерәк булып санала.',
// https://tt.wikipedia.org/wiki/Avtojir
'Avtojir boralaq kebek kütärü köçen buldırır öçen kütärüçe vintqa iä, läkin
avtojirda vint aerodinamik köçlär tä\'sire näticäsendä irekle äylänä. Ul kütärü
köçen genä buldıra, vint uñay höcüm poçmağına iä.
Boralaq vintı tiskäre höcüm poçmağına iä.
Kütärüçe rotordan tış avtojirda etärü vintı bar, ul avtojirnıñ yatma tizlegen buldıra.
Avtojir törläre:
Tartuçı vintlı avtojirlar (xäzer iñ taralğan). Üzençälege: kabinadan yaxşı küreneş.
Etärüçe vintlı avtojirlar. Üzençälege: dvigatel\'ne
yaxşıraq suıtılu, iminleräk bulıp sanala.'
		],
		// 10
		[
'Канатлы боралак яки жиродин (инглизчә Gyrodyne, русча винтокрыл) - канатлы
роторлы очучы аппарат, күтәрелгәндә, утырганда, очып торганда роторлар
кулланыла, ә горизонталь очуы канатлар ярдәмендә тәэмин ителә.
Авыр канатлы боралак -{Ka-22}-
Канатлы боралак күтәреп баручы боралак сыман роторны куллана, ә
горизонталь тарту өчен канат һәм йөрткечне очкыч кебек файдалана.
Күтәреп баручы роторлар ярдәмендә аппарат күтәрелә һәм утыра, горизонталь
режимда роторлар һәм канатлар ярдәмендә күтәрү көчен булдыра һәм
роторның йөкләнешен канатка тапшырыла.
Тасвир
Канатлы боралакта -
боралактан аермалы буларак "очкыч канаты" һәм өстәмә йөрткеч бар, шуңа
күрә горизонталь тизлеге югарырак була
очкычтан аермалы буларак боралак сыман ротор бар, шуңа күрә
вертикаль күтәрелү һәм утыру мөмкинлеге бар
автожирдан аермалы буларак боралак сыман йөртелүче ротор бар, шуңа күрә аппарат
бер урында асылынып тора ала һәм вертикаль рәвештә күтәрелә һәм утыра ала.',
// https://tt.wikipedia.org/wiki/Qanatl%C4%B1_boralaq
'Qanatlı boralaq yäki jirodin (inglizçä -{Gyrodyne}-, rusça vintokrıl) - qanatlı
rotorlı oçuçı apparat, kütärelgändä, utırğanda, oçıp torğanda rotorlar
qullanıla, ä gorizontal\' oçuı qanatlar yärdämendä tä\'min itelä.
Awır qanatlı boralaq -{Ka-22}-
Qanatlı boralaq kütärep baruçı boralaq sıman rotornı qullana, ä
gorizontal\' tartu öçen qanat häm yörtkeçne oçqıç kebek faydalana.
Kütärep baruçı rotorlar yärdämendä apparat kütärelä häm utıra, gorizontal\'
rejimda rotorlar häm qanatlar yärdämendä kütärü köçen buldıra häm
rotornıñ yökläneşen qanatqa tapşırıla.
Taswir
Qanatlı boralaqta -
boralaqtan ayırmalı bularaq "oçqıç qanatı" häm östämä yörtkeç bar, şuña
kürä gorizontal\' tizlege yuğarıraq bula
oçqıçtan ayırmalı bularaq boralaq sıman rotor bar, şuña kürä
vertikal\' kütärelü häm utıru mömkinlege bar
avtojirdan ayırmalı bularaq boralaq sıman yörtelüçe rotor bar, şuña kürä apparat
ber urında asılınıp tora ala häm vertikal\' räweştä kütärelä häm utıra ala.'
		],
		// 11
		[
'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан
егерме-утыз ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән
һәрвакыт тыгыз элемтә булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч,
авыз чите белән генә елмаеп куярга яратабыз. Бүген заман икенче,
шуңа күрә иҗади эшләү ысуллары да башка төрле, дибез.',
// http://www.vatantat.ru/index.php?a2pg=3618
'Elek yazuçılar awıllarğa yış kilä häm härwaqıt xalıq arasında bula ide. Monnan
yegerme-utız yıl elek rayon, awıl cirlegendä eşläp kilüçe ädäbi berläşmälär belän
härwaqıt tığız elemtä buldı. Elek bolay ide, tegeläy ide, digän fikerlärne işetkäç,
awız çite belän genä yılmayıp quyarğa yaratabız. Bügen zaman ikençe,
şuña kürä icadi eşläw ısulları da başqa törle, dibez.'
		],
		// 12
		[
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
'вагон вак гаять таяк гаярь гаяре гаеп гаепләде гаебе гайре чәе бае гәп гүләп
гөбер гоа гөлбакча гитара гүл гел үгетләү кабер калеб каек каенсар каеш
акаеп тукаебыз каеру баеп сагаеп карагаем коерык кыен куен туен төен көенү
каюм аю таю кою ою кыю тыю тую чөю кию тию чекерәю кечкенәю шикәр каян
гаян куян кыяк кояш тоя оя тарая бәян кечкенәя көя чия кия ия бия тия гүя
рия рияга риясыз кавын гарь гаҗәп галим газ го гога года гоби гыйлем гомер гомәр
гореф горилла гомум гомумән гомуми гол голсыз голга га гәп грамм гарык тагу шпиг
агу кагылу тугрик тугры багын бугын чугын угры углан игълан туглан баглан баг бриг
сигнал варяг дог шпага вигвам зигзаг гыйрак катгый бакый гырылдау егет ефәк ерак
каләм калеб кабил камил күмер ак катык катоктагы камага ёлка
кукмарага цетнер щётка счётка юеш юкә юри юнь юан хикәя
ярәш яки яшь яшьләре яшьлек ян авыр аватар революцияләр революциялар
эволюцияга эволюциягә амбициягә амбицияга кавия равия мөхәммәдия авария
авиа психология геология физиология ия бия юлия юлияләр юлиялар сию сиюне сиюгә
хакимият мәдәният физика бие экологка во товарга овалларга волга волгага корбанов яшәү
эшләү буенча вьет юмья фея тәэсир маэмай брэйк крэк коэн мәсьәлән коръән дөнья
яклар-төбәкләр лельвиж янил югары комар чокырча плаксиха укытучылык эльвира кирпеч',
// iya should be ia according to http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/18.jpg .
// i am going to fix them all later. fixed.
// see also
// https://tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_06.jpg
// though it is only about iä, not about ia.
'vagon waq ğäyät tayaq ğäyär ğäyäre ğäyep ğäyepläde ğäyebe ğäyre çäye bayı gäp güläp
göber goa gölbaqça gitara gül gel ügetläw qäber qäleb qayıq qayınsar qayış
aqayıp tuqayıbız qayıru bayıp sağayıp qarağayım qoyırıq qıyın quyın tuyın töyen köyenü
qayum ayu tayu qoyu oyu qıyu tıyu tuyu çöyü kiü tiü çekeräyü keçkenäyü şikär qayan
ğayan quyan qıyaq qoyaş toya oya taraya bäyän keçkenäyä köyä çiä kiä iä biä tiä güyä
ria riağa riasız qawın ğär ğäcäp ğalim gaz go goğa goda gobi ğilem ğömer ğömär
ğöref gorilla ğomum ğomumän ğomumi gol golsız golğa ğa gäp gramm ğarıq tağu şpig
ağu qağılu tugrik tuğrı bağın buğın çuğın uğrı uğlan iğlan tuğlan bağlan bağ brig
signal varyag dog şpaga vigvam zigzag ğıyraq qatğıy baqıy ğırıldaw yeget yefäk yıraq
qäläm qäleb qabil kamil kümer aq qatıq katoktağı kamağa yolka
kukmarağa tsetner şçotka sçotqa yüeş yükä yüri yün yuan xikäyä
yäräş yäki yäş yäşläre yäşlek yan awır avatar revolyutsiälär revolyutsialar
evolyutsiağa evolyutsiägä ambitsiägä ambitsiağa qawiä rawiä möxämmädiä avariä
avia psixologiä geologiä fiziologiä iä biä yuliä yuliälär yulialar siü siüne siügä
xakimiät mädäniät fizika bie ekologqa vo tovarğa ovallarğa volga volgağa qorbanov yäşäw
eşläw buyınça vyet yumya feya tä\'sir ma\'may breyk krek koen mäs\'älän qör\'än dönya
yaqlar-töbäklär lel\'vij yanil yuğarı qomar çoqırça plaksixa uqıtuçılıq el\'vira kirpeç'
		],
		// 13
		[
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
'химия астрономия математика нократ кизнер кунак Кукмара эзләү аек кыек ак уек
як чөек оек белек чиләк чиләге аегы каныгу <span>ек</span> ек егу бик кое коега
канәгать канәгате канәгатькә <span>ун  ун</span> урта балыклы балык хокук сүрия
җәмгыять җәмгыяте җәмгыятькә җәмгысе кыяфәт пыяла вакыйга кыяр вилаять зарурият
такыя робагый тәрәккыять тәрәккыяте мәгълүмат мәгълүмати тәрәккыяти хөсәения рәмиев
тәрәккыяви тәрәккый тәрәккые тәрәккыя разыя лөгать көллият зыян бияләй хыялый васыять
кая акая сәнагать сәнгать уа куа уып куып юан юын чуен куен муен үртәләү әдәбият
үртә кыйлды кылды чыелдап мөстәкыйль ихтилаф кыен җыен быел тыйды тый җинаять
җыйма барыйк кылыйк кыйлыйк күрик сөйлик кыйса фагыйләт шигый фатыйма җамыяк
чынаяк фигыль гыйльфан шигырь шифыр шифр шофёр шофер мисыр латыйф расыйх кыйсса гыйсъян
гыйффәт шагыйрь бәгырь багира тагил врунгель курык тукран чукрак уку аксу боек
уен йөрик яшик тарыйк бәлигъ әдәби мәдәни мәдәние
сәгать ямь сәясәт ***** сәяси ***** лөгать бәһа бәла бәласе электричкада
кушкет куркино кузмесь юмья синерь икътисади бәләгать мөхәммәдия чәйнек
чинаяк гыйлем электричкага ваемсыз',
// following 3 links are to rules which was not accepted by government.
// xıyalıy should be xıyali according to http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/13.jpg
// http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/10.jpg
// and http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/19.jpg ,
// but it is very strange, though iqtisadi instead of iqtisadıy etc sound ok
// so i am going to leave it as ıy for now.
// also seems it contradicts to proposition of teachers
// at http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/09.jpg .
// wikipedians were going to use authoritative source, so the 1999 - 2001 rules,
// but really they are using 2012 law for now, which has not orthograpy rules,
// but only alphabet, so maybe wikipedians will use slightly other rules.
// ıy - i (using both) also was proposed in earlier version of rules:
// http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/05.jpg .
// as i see from http://docs.cntd.ru/document/463301135 the 2001 version of rules
// was only proposition by academy of sciences of tatarstan, it was not accepted by
// cabinet of ministers.
// so i use for now
// https://tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_10.jpg
// which was accepted by resolutions 612 and/or 882 ( http://zakon-region3.ru/2/187755/ ).
// and this scan is from a book printed at that time, as i know.
'ximiä astronomiä matematika noqrat kizner qunaq Kukmara ezläw ayıq qıyıq aq uyıq
yaq çöyek oyıq belek çiläk çiläge ayığı qanığu <span>yıq</span> yıq yığu bik qoyı qoyığa
qänäğät qänäğäte qänäğätkä <span>un  un</span> urta balıqlı balıq xoquq süriä
cämğiät cämğiäte cämğiätkä cämğese qiäfät pıyala waqiğa qıyar wiläyät zaruriät
taqıya robağıy täräqqiät täräqqiäte mäğlümät mäğlümäti täräqqiäti xösäyeniä rämiev
täräqqiäwi täräqqi täräqqie täräqqiä razıya löğät kölliät zıyan biäläy xıyalıy wasiät
qaya aqaya sänäğät sänğät ua qua uıp quıp yuan yuın çuyın quyın muyın ürtäläw ädäbiät
ürtä qıyldı qıldı çıyıldap möstäqil ixtilaf qıyın cıyın bıyıl tıydı tıy cinäyät
cıyma barıyq qılıyq qıylıyq kürik söylik qıysa fağilät şiği fatıyma camıyaq
çınayaq fiğel ğilfan şiğer şifır şifr şofyor şofer misır latıyf rasix qıyssa ğıysyan
ğiffät şağir bäğer bagira tagil vrungel\' qurıq tuqran çuqraq uqu aqsu boyıq
uyın yörik yäşik tarıyq bäliğ ädäbi mädäni mädänie
säğät yäm säyäsät ***** säyäsi ***** löğät bähä bälä bäläse elektriçkada
kuşket kurkino kuzmes\' yumya siner\' iqtisadi bäläğät möxämmädiä çäynek
çinayaq ğilem elektriçkağa wayımsız'
		],
		// 14
		[
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
'карьер люгдон компьютер коммуналь физика конструктор бушуев
фестиваль муниципаль нәгыймов вәлиев
станциясе шоу боулинг маугли аву ау аусыз аяусызга
имамиевага кыямовага киенүе укуы Уына куа ию тасвирлау ә карта визит Биектау
юк дискларым минскига арттагы анттагы асттагы кайбер иягә ия чия катеры вышкасы
власть капкын караватка гыйнвар башкортка корт ссылка яки як мәгариф
әнвәр трамвай авыл штутгард вепс карт акт аяу Юнь китабындагы әхлак сабые
вариант версия ноябрьдән мәшәкать гатауллин ягъфәровага ягъфәр ягафәр
ягафар ягъфар гаетләре гает коръән музыка ниһаять телефонга веб тасвир
төбәкара пауза мөгаен мөлаем поезд якупов гыйлемхановка гайнанов тәэссорат
ризаэтдин ниязга әхмәтвәлиевага садриевка
вергазовка корбановка солтангалиевка миңнебаева
котлуг блог ясау казбек юбилеендагы акъегет никах суфиян Екатерина аккош е Е
заказ коточкыч указ министрлыгында язарга йомгаклаячак Русия поэма аэрация
валдае пуэр конгруэнт ремонтта комиссарлары ВЛКСМ',
'karyer lyugdon kompyuter kommunal\' fizika konstruktor buşuyev
festival\' munitsipal\' näğimov wäliev
stansiäse şow bowling mawgli awu aw awsız ayawsızğa
imamievağa qıyamovağa kienüe uquı Uına qua iü taswirlaw ä qarta vizit Biektaw
yuq disklarım minskiğa arttağı anttağı asttağı qayber iägä iä çiä katerı vışkası
vlast\' qapqın karawatqa ğinwar başqortqa qort ssılka yäki yaq mäğärif
änwär tramvay awıl ştutgard veps qart akt ayaw Yün kitabındağı äxläq sabıyı
variant versiä noyäberdän mäşäqät ğataullin yäğfärovağa yäğfär yäğäfär
yağafar yağfar ğäyetläre ğäyet qör\'än muzıka nihäyät telefonğa web taswir
töbäkara pauza möğayen mölayem poyezd yaqupov ğilemxanovqa ğaynanov tä\'essorat
riza\'etdin niazğa äxmätwälievağa sadrievqa
vergazovqa qorbanovqa soltanğalievqa miñnebayeva
qotluğ blog yasaw kazbek yubileyındağı aqyeget nikax sufian Yekaterina aqqoş ye Ye
zakaz qotoçqıç ukaz ministrlığında yazarğa yomğaqlayaçaq Rusiä poema aeratsiä
valdayı puer kongruent remontta komissarları VLKSM'
		],
		// 15
		[
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
'лигасы васыять васыяте васыяви гомер Бөекбритания
Әнкара гореф җәмгыять гошер голәма гобелен Гоген графка графиктагы графында быргы
Гомель гомеопатия гомеостаз телеграмма ерак елга граммга Гали
Наҗия ия җинаять Мөлеков вәзгыять музеенда декабрь
Бу битнең латинчасына сылтама тәнкыйть кыйл кыйлган бакый тәрәккыя кыя гагауз
гагаузча Әхмәтшин Әхмәтшинга Шина нижнекамскшина Викиҗыентыкта Константин
Габделхәев Яков Шкетан драматургы Иван солтангалиевче
Намибия океан португал төньяграк Гана Гвинея Бисау Ливия
Мадагаскар Уганда Елена Канар
акъегет ир-егет ателье "-{музее}-" КамАЗ ЕРЭ Е А Ю АКШ ЮХИДИ.',
'ligası wasiät wasiäte wasiäwi ğömer Böyekbritaniä
Änqara ğöref cämğiät ğöşer ğölämä gobelen Gogen grafqa grafiktağı grafında bırğı
Gomel\' gomeopatiä gomeostaz telegramma yıraq yılğa grammğa Ğali
Naciä iä cinäyät Mölekov wäzğiät muzeyında dekaber
Bu bitneñ latinçasına sıltama tänqit qıyl qıylğan baqıy täräqqiä qıya ğağauz
ğağauzça Äxmätşin Äxmätşinğa Şina nijnekamskşina Wikicıyıntıqta Konstantin
Ğäbdelxäyev Yakov Şketan dramaturgı İvan soltanğalievçe
Namibiä okean portugal tönyağraq Gana Gvineya Bisaw Liviä
Madagaskar Uğanda Yelena Qanar
aqyeget ir-yeget atelye "музее" KamAZ YeRE Ye A Yu AQŞ YuXİDİ.'
		],
		// 16
		[
// https://tt.wikipedia.org/wiki/%D0%93%D3%A9%D0%BC%D0%B1%D3%99%D0%BB%D3%99%D1%80
'Гөмбәләр (лат. -{Fungi}- яки -{Mycota}-) — үсемлекләрнең һәм хайваннарның кайбер
сыйфатларын берләштергән эукариот организмнардан торган мөстәкыйль патшалык.
Гөмбәләр патшалыгын микология өйрәнә. Элек гөмбәләрне үсемлекләргә
керткәнгә микология хәзер дә биологиянең бүлеге дип санала.
Гөмбәләр гаять күптөрлелек белән аерылып торалар. Бөтен экологик
системаларда да гөмбәләр аерылгысыз элементлар. Җир шарында төрле тикшерүләр буенча
100 меңнән 250 меңгә якын, ә кайбер
санаулар буенча 1,5 миллионнан артык гөмбәләр төре бар.
Гөмбәләр — гетеротрофлар, ягъни аларның фотосинтез
процессын тәэмин итүче пигменты — хлорофилы юк.
Гөмбәләрнең кайбер сыйфатлары хайваннар белән уртак: мәсәлән,
матдәләр алмашы продуктлары арасында мочевина булу;
күзәнәк тышчасында (буынтыгаяклыларның тән япмасындагы кебек) күмерсулар — хитин;
запас продукт (үсемлекләрдәге кебек крахмал түгел) гликоген булу.
Ә кайбер башка сыйфатлары белән исә алар үсемлекләргә якын: мәсәлән, аларның туклану
ысулы (азыкны йотып түгел, ә суырып тукланалар), чикләнмәгән үсүе һәм хәрәкәтләнмәве.',
'Gömbälär (lat. -{Fungi}- yäki -{Mycota}-) — üsemleklärneñ häm xaywannarnıñ qayber
sıyfatların berläştergän eukariot organizmnardan torğan möstäqil patşalıq.
Gömbälär patşalığın mikologiä öyränä. Elek gömbälärne üsemleklärgä
kertkängä mikologiä xäzer dä biologiäneñ bülege dip sanala.
Gömbälär ğäyät küptörlelek belän ayırılıp toralar. Böten ekologik
sistemalarda da gömbälär ayırılğısız elementlar. Cir şarında törle tikşerülär buyınça
100 meñnän 250 meñgä yaqın, ä qayber
sanawlar buyınça 1,5 millionnan artıq gömbälär töre bar.
Gömbälär — geterotroflar, yäğni alarnıñ fotosintez
protsessın tä\'min itüçe pigmentı — xlorofilı yuq.
Gömbälärneñ qayber sıyfatları xaywannar belän urtaq: mäsälän,
matdälär almaşı produktları arasında moçevina bulu;
küzänäk tışçasında (buıntığayaqlılarnıñ tän yapmasındağı kebek) kümersular — xitin;
zapas produkt (üsemleklärdäge kebek kraxmal tügel) glikogen bulu.
Ä qayber başqa sıyfatları belän isä alar üsemleklärgä yaqın: mäsälän, alarnıñ tuqlanu
ısulı (azıqnı yotıp tügel, ä suırıp tuqlanalar), çiklänmägän üsüe häm xäräkätlänmäwe.'
		],
		// 17
		[
		// 200 most frequent words from http://corpus.tatfolk.ru/
'һәм белән да дә бу ул дип өчен бер иде аның
буенча ә татар генә бик инде шул бар түгел
гына шулай Татарстан үз ук турында алар булган мин булып ел зур кеше алып иң яңа итеп
дәүләт соң кирәк әлеге беренче торган күп ТР без әле итә Казан дигән бүген ике аны юк
була икән елда безнең башка бит авыл кебек булды кадәр менә эш ди халык тагын тә килә
Россия хезмәт хәзер төрле балалар арасында тора килеп тиеш әмма анда диде та мең аларның
район республика узган булса елның вакытта итү килгән туган һәр яхшы үзе шушы яшь каршы
исә ала кабул югары икенче хәбәр ярдәм мөмкин ләкин сүз тик аңа шуңа минем президенты
соңгы итте милли бөтен чөнки нинди сум ни ничек туры юл бара башлыгы артык төп баш көн
шундый кенә көнне быел күрә акча үзенең син барлык мондый шәһәр бүгенге тәкъдим Казанда
иткән эшли нче аларны рәисе кына өч бала хәл алган җитәкчесе спорт тапкыр Рөстәм белем
булачак берсе үк якын берничә федераль булырга районы әллә урынбасары яки буларак
караганда дәвам нәрсә матур уку тарафыннан кешеләр елларда миңа аннан су буе
элек калган алды итәргә бергә дөнья белдерде
биредә муниципаль республикасы яшьләр тыш эшләр',
'häm belän da dä bu ul dip öçen ber ide anıñ
buyınça ä tatar genä bik inde şul bar tügel
ğına şulay Tatarstan üz uq turında alar bulğan min bulıp yıl zur keşe alıp iñ yaña itep
däwlät soñ kiräk älege berençe torğan küp TR bez äle itä Qazan digän bügen ike anı yuq
bula ikän yılda bezneñ başqa bit awıl kebek buldı qädär menä eş di xalıq tağın tä kilä
Rossiä xezmät xäzer törle balalar arasında tora kilep tieş ämma anda dide ta meñ alarnıñ
rayon respublika uzğan bulsa yılnıñ waqıtta itü kilgän tuğan här yaxşı üze şuşı yäş qarşı
isä ala qabul yuğarı ikençe xäbär yärdäm mömkin läkin süz tik aña şuña minem prezidentı
soñğı itte milli böten çönki nindi sum ni niçek turı yul bara başlığı artıq töp baş kön
şundıy kenä könne bıyıl kürä aqça üzeneñ sin barlıq mondıy şähär bügenge täqdim Qazanda
itkän eşli nçe alarnı räise qına öç bala xäl alğan citäkçese sport tapqır Röstäm belem
bulaçaq berse ük yaqın berniçä federal\' bulırğa rayonı ällä urınbasarı yäki bularaq
qarağanda däwam närsä matur uqu tarafınnan keşelär yıllarda miña annan su buyı
elek qalğan aldı itärgä bergä dönya belderde
biredä munitsipal\' respublikası yäşlär tış eşlär'
		],
		// 18
		[
		// http://tatar.org.ru/kurs/9-algebra/cyr_8.html
'Функция — иң мөһим математик төшенчәләрнең берсе,
x үзгәрешлесенең һәр кыйммәтенә y үзгәрешлесенең
бердәнбер кыйммәте тиңдәш булган x үзгәрешлесеннән
y үзгәрешлесенең бәйлелеген функция дип атыйлар.
x үзгәрешлесен бәйсез үзгәрешле яки аргумент
дип атыйлар, y үзгәрешлесен бәйле үзгәрешле дип
атыйлар. Шулай ук y үзгәрешлесен x үзгәрешлесеннән функция була дип тә
әйтәләр. Бәйле үзгәрешленең кыйммәтләрен функциянең кыйммәтләре дип атыйлар.
Әгәр x үзгәрешлесеннән y үзгәрешлесенең
бәйлелеге функция булса, аны кыскача болай
язалар: y = f(x). (Болай укыйлар:
y x тан f ка тигез.) f(x) символы белән x ка тигез
булган аргументның кыйммәтенә тиңдәш функциянең кыйммәтен тамгалыйлар.',
'Funksiä — iñ möhim matematik töşençälärneñ berse,
-{x}- üzgäreşleseneñ här qimmätenä -{y}- üzgäreşleseneñ
berdänber qimmäte tiñdäş bulğan -{x}- üzgäreşlesennän
-{y}- üzgäreşleseneñ bäylelegen funksiä dip atıylar.
-{x}- üzgäreşlesen bäysez üzgäreşle yäki argument
dip atıylar, -{y}- üzgäreşlesen bäyle üzgäreşle dip
atıylar. Şulay uq -{y}- üzgäreşlesen -{x}- üzgäreşlesennän funksiä bula dip tä
äytälär. Bäyle üzgäreşleneñ qimmätlären funksiäneñ qimmätläre dip atıylar.
Ägär -{x}- üzgäreşlesennän -{y}- üzgäreşleseneñ
bäylelege funksiä bulsa, anı qısqaça bolay
yazalar: -{y = f(x)}-. (Bolay uqıylar:
-{y}- -{x}- tan -{f}- qa tigez.) -{f(x)}- simvolı belän -{x}- qa tigez
bulğan argumentnıñ qimmätenä tiñdäş funksiäneñ qimmäten tamğalıylar.'
		],
		// 19
		[
		// http://tatar.org.ru/_educ/virt-gimn/books/8himcyr/1_1.html
'Башлангыч классларда сез табигать белемен өйрәндегез, физика һәм биологияне өйрәнүгә
керештегез, «физик җисем» һәм «матдә» төшенчәләре белән таныштыгыз. «Физик җисем» һәм
«матдә» төшенчәләренең нәрсә белән аерылып торуын ачыклау өчен, табигать белеме һәм
ботаника курсларыннан физик җисемнәрнең, мәсәлән, гранит һәм иген бөртегенең составы
турындагы мәгълүматларны хәтергә төшерегез. Гранит кисәге дә, бөртек тә — физик җисемнәр,
әмма аларның составлары төрле. Ботаника
дәресләрендә сез бөртек составында крахмал, аксым,
үсемлек майлары булуын ачыкладыгыз, ә
гранит исә кварцтан, слюдадан һәм кыр шпатыннан тора.
Кварц, слюда, кыр шпаты, крахмал, аксым, үсемлек майлары — болар матдәләр. Бер үк
предметларны еш кына төрле матдәләрдән ясыйлар. Мәсәлән, бер үк формадагы трубаларны
бакырдан да, пыяладан да эшләп була һәм, киресенчә, төрле предметлар, мәсәлән, төрле
савытлар эшләп чыгарганда бер үк матдә — пыяла кулланалар (1 нче рәсем). Димәк, физик
җисемнәрне матдәләр тәшкил итәләр. Матдәләр бик күп. Җиде миллионнан артык матдә барлыгы
билгеле һәм аларның барысы да билгеле бер үзлекләр белән характерлана. Матдәләрнең үзара
аерымлык яки охшашлык билгеләре матдәләрнең
үзлекләре дип атала. Физика курсыннан мәгълүм
булганча, һәр матдә билгеле бер физик үзлекләргә ия (1 нче схема). Химиянең бурычларыннан
берсе — матдәләрне, аларның үзлекләрен өйрәнү һәм матдәләрне халык хуҗалыгында куллануны
прогнозлау. Мәсәлән, һәркемгә билгеле булган алюминий матдәсенә мондый характеристика
бирергә була.',
'Başlanğıç klasslarda sez tabiğät belemen öyrändegez, fizika häm biologiäne öyränügä
kereştegez, «fizik cisem» häm «matdä» töşençäläre belän tanıştığız. «Fizik cisem» häm
«matdä» töşençäläreneñ närsä belän ayırılıp toruın açıqlaw öçen, tabiğät beleme häm
botanika kurslarınnan fizik cisemnärneñ, mäsälän, granit häm igen börtegeneñ sostavı
turındağı mäğlümatlarnı xätergä töşeregez. Granit kisäge dä, börtek tä — fizik cisemnär,
ämma alarnıñ sostavları törle. Botanika
däreslärendä sez börtek sostavında kraxmal, aqsım,
üsemlek mayları buluın açıqladığız, ä
granit isä kvartstan, slyudadan häm qır şpatınnan tora.
Kvarts, slyuda, qır şpatı, kraxmal, aqsım, üsemlek mayları — bolar matdälär. Ber ük
predmetlarnı yış qına törle matdälärdän yasıylar. Mäsälän, ber ük formadağı trubalarnı
baqırdan da, pıyaladan da eşläp bula häm, kiresençä, törle predmetlar, mäsälän, törle
sawıtlar eşläp çığarğanda ber ük matdä — pıyala qullanalar (1 nçe räsem). Dimäk, fizik
cisemnärne matdälär täşkil itälär. Matdälär bik küp. Cide millionnan artıq matdä barlığı
bilgele häm alarnıñ barısı da bilgele ber üzleklär belän xarakterlana. Matdälärneñ üzara
ayırımlıq yäki oxşaşlıq bilgeläre matdälärneñ
üzlekläre dip atala. Fizika kursınnan mäğlüm
bulğança, här matdä bilgele ber fizik üzleklärgä iä (1 nçe sxema). Ximiäneñ burıçlarınnan
berse — matdälärne, alarnıñ üzleklären öyränü häm matdälärne xalıq xucalığında qullanunı
prognozlaw. Mäsälän, härkemgä bilgele bulğan alyuminiy matdäsenä mondıy xarakteristika
birergä bula.'
		],
		// 20
		[
		// http://tatar.org.ru/_educ/virt-gimn/books/8himcyr/1_6.html
			'Әлеге чагыштырмача күп булмаган
сандагы элементларның атомнарыннан чиксез күп төрле матдәләр
барлыкка килә. Шулай итеп, күпчелек очракларда
исемнәре бер-берсенә туры килсәләр дә, «гади
матдә» һәм «химик элемент» төшенчәләрен аера
белергә кирәк. Шунлыктан «кислород», «водород»,
«тимер», «күкерт» һәм башка шундый сүзләрне
кулланганда нәрсә турында — гади матдә яки химик
элемент турындамы — сүз баруын аерата белү
мөһим. «Кислород — суда аз эрүчән газ. Суда эрегән
кислородны балыклар сулыйлар», «Тимер магнитка
тартыла торган металл»,— дип әйтсәләр, мәсәлән,
кислород һәм тимер билгеле бер үзлекләргә
ия булган гади матдәләр буларак күздә тотылалар.
Кислород яки тимер ниндидер катлаулы яки
гади матдә составына керә дип әйтелсә, кислород һәм
тимер химик элементлар буларак күздә тотылалар. «Химик элемент» төшенчәсен кулланып, гади
һәм катлаулы матдәләргә мондый билгеләмәләр бирергә мөмкин:',
			'Älege çağıştırmaça küp bulmağan
sandağı elementlarnıñ atomnarınnan çiksez küp törle matdälär
barlıqqa kilä. Şulay itep, küpçelek oçraqlarda
isemnäre ber-bersenä turı kilsälär dä, «ğadi
matdä» häm «ximik element» töşençälären ayıra
belergä kiräk. Şunlıqtan «kislorod», «vodorod»,
«timer», «kükert» häm başqa şundıy süzlärne
qullanğanda närsä turında — ğadi matdä yäki ximik
element turındamı — süz baruın ayırata belü
möhim. «Kislorod — suda az erüçän gaz. Suda eregän
kislorodnı balıqlar sulıylar», «Timer magnitqa
tartıla torğan metall»,— dip äytsälär, mäsälän,
kislorod häm timer bilgele ber üzleklärgä
iä bulğan ğadi matdälär bularaq küzdä totılalar.
Kislorod yäki timer nindider qatlawlı yäki
ğadi matdä sostavına kerä dip äytelsä, kislorod häm
timer ximik elementlar bularaq küzdä totılalar. «Ximik element» töşençäsen qullanıp, ğadi
häm qatlawlı matdälärgä mondıy bilgelämälär birergä mömkin:'
		],
		// 21
		[
		// http://tatar.org.ru/_educ/virt-gimn/books/8himcyr/1_6.html
'Роберт Бойль (1627—1691) Инглиз галиме.
1661 елда үзенең «Химик-скептик» исемле китабында
элементларны «башлангыч гади җисемнәр» дип
билгеләгән. Бер химик элемент атомнарыннан торучы
матдәләрне гади матдәләр дип атыйлар. Төрле химик элемент атомнарыннан торучы матдәләрне
катлаулы матдәләр дип атыйлар. Алдарак химия
курсында химик элемент турындагы төшенчә тагын
да ачыкланачак һәм киңәйтеләчәк (122 нче бит). 14—15 нче сорауларга җавап бирегез (23 нче
бит). ... XIII гасырга кадәр бары тик 13
химик элемент кына билгеле булган. XVIII гасырда
аларның 30 ы билгеле булган. 50 елдан сон тагын
28 өстәлгән. Хәзерге вакытта 108 элемент билгеле.',
'Robert Boyl\' (1627—1691) İngliz ğalime.
1661 yılda üzeneñ «Ximik-skeptik» isemle kitabında
elementlarnı «başlanğıç ğadi cisemnär» dip
bilgelägän. Ber ximik element atomnarınnan toruçı
matdälärne ğadi matdälär dip atıylar. Törle ximik element atomnarınnan toruçı matdälärne
qatlawlı matdälär dip atıylar. Aldaraq ximiä
kursında ximik element turındağı töşençä tağın
da açıqlanaçaq häm kiñäyteläçäk (122 nçe bit). 14—15 nçe sorawlarğa cawap biregez (23 nçe
bit). ... -{XIII}- ğasırğa qädär barı tik 13
ximik element qına bilgele bulğan. -{XVIII}- ğasırda
alarnıñ 30 ı bilgele bulğan. 50 yıldan son tağın
28 östälgän. Xäzerge waqıtta 108 element bilgele.'
		],
		// 22
		[
		// http://tatar.org.ru/kurs/7-biologiya/cyr_46.html
'Югары төзелешле үсемлекләр арасында мүксыманнар
үсешнең аерымланган һәм эволюцион яктан
тукталып калган тармагы булып тора. Алар 350 млн еллар элек беренче коры җир
үсемлекләреннән — яр буе суүсемнәренең нәселе булган псилофитлардан килеп чыкканнар.
Мүксыманнар — гадәттә тәбәнәк, күпьеллык үсемлекләр, аларның зурлыгы миллиметрдан алып
берничә сантиметрга җитә. Мүкләрнең кайбер төркемнәренең, мәсәлән бавырчыл мүкләрнең,
вегетатив тәне түшәлеп үсә торган таллом рәвешендә. Башкаларының тәне өлешләргә
бүлгәләнгән, һәм яшькелт коңгырт сабагы тар яшел яфраклар белән капланган; мүкләрнең
тамыры юк. Алар дымлы тирәлектә үсәргә
җайлашканнар. Мүкләрнең эчке төзелеше чагыштырмача
гади. Аларның тәнендә хлоропластлы ассимиляцион (төп) тукыманы, шулай ук башка югары
төзелешле үсемлекләрнеке белән чагыштырганда начаррак үскән үткәргеч, механик, туплаучы
һәм япма тукымаларны күрергә мөмкин. Япма тукыма мүкләрнең барлык систематик
төркемнәрендә очрамый.',
'Yuğarı tözeleşle üsemleklär arasında müksımannar
üseşneñ ayırımlanğan häm evolyutsion yaqtan
tuqtalıp qalğan tarmağı bulıp tora. Alar 350 mln yıllar elek berençe qorı cir
üsemleklärennän — yar buyı suüsemnäreneñ näsele bulğan psilofitlardan kilep çıqqannar.
Müksımannar — ğädättä täbänäk, küpyıllıq üsemleklär, alarnıñ zurlığı millimetrdan alıp
berniçä santimetrğa citä. Müklärneñ qayber törkemnäreneñ, mäsälän bawırçıl müklärneñ,
vegetativ täne tüşälep üsä torğan tallom räweşendä. Başqalarınıñ täne öleşlärgä
bülgälängän, häm yäşkelt qoñğırt sabağı tar yäşel yafraqlar belän qaplanğan; müklärneñ
tamırı yuq. Alar dımlı tirälektä üsärgä
caylaşqannar. Müklärneñ eçke tözeleşe çağıştırmaça
ğadi. Alarnıñ tänendä xloroplastlı assimilyatsion (töp) tuqımanı, şulay uq başqa yuğarı
tözeleşle üsemleklärneke belän çağıştırğanda naçarraq üskän ütkärgeç, mexanik, tuplawçı
häm yapma tuqımalarnı kürergä mömkin. Yapma tuqıma müklärneñ barlıq sistematik
törkemnärendä oçramıy.'
		],
		// 23
		[
		// http://tatar.org.ru/kurs/7-biologiya/cyr_46.html
'Мүк туфракка сабагының түбәнге өлешендә урнашкан нечкә җепсыман
бер күзәнәкле яки күп күзәнәкле үсентеләр — ризоидлар ярдәмендә берегә, алар ярдәмендә
туфрактан туклыклы матдәләрне суыра. Чын
тамырдан аермалы буларак, күп күзәнәкле ризоидлар
бер төрле күзәнәкләрдән тора, һәм аларның
үткәргеч тукымасы булмый. Мүксыманнарның төзелешендә
аларны хәзерге барлык коры җир үсемлекләреннән ачык аерып торган үзенчәлекле билге бар:
җенси буын — яфраклы сабактан торучы һәм җенес күзәнәкләрен (гаметаларны) барлыкка
китерүче гаметофит, споралар өлгертүче спорофитка караганда, яхшырак үсеш алган.',
'Mük tufraqqa sabağınıñ tübänge öleşendä urnaşqan neçkä cepsıman
ber küzänäkle yäki küp küzänäkle üsentelär — rizoidlar yärdämendä beregä, alar yärdämendä
tufraqtan tuqlıqlı matdälärne suıra. Çın
tamırdan ayırmalı bularaq, küp küzänäkle rizoidlar
ber törle küzänäklärdän tora, häm alarnıñ
ütkärgeç tuqıması bulmıy. Müksımannarnıñ tözeleşendä
alarnı xäzerge barlıq qorı cir üsemleklärennän açıq ayırıp torğan üzençälekle bilge bar:
censi buın — yafraqlı sabaqtan toruçı häm cenes küzänäklären (gametalarnı) barlıqqa
kiterüçe gametofit, sporalar ölgertüçe sporofitqa qarağanda, yaxşıraq üseş alğan.'
		],
		// 24
		[
		// http://tatar.org.ru/kurs/6-biologiya/cyr_13.html
'Цитоплазмада күзәнәкнең 30—50 % ын тәшкил
итүче күп санлы вак каналчыклар һәм куышлыклар
бар. Бу — эндоплазматик челтәр. Ул күзәнәкнең барлык өлешләрен плазматик мембрана белән
тоташтыра, төрле органик матдәләрне барлыкка китерүдә һәм күчерүдә катнаша. Гольджи
аппаратының функцияләре эндоплазматик челтәрнекенә охшаш. Аның катлаулы көпшәчекләр һәм
куыкчыклар системасында күзәнәкнең әһәмиятле матдәләре — аксымнар, майлар, углеводлар
туплана, соңыннан алар цитоплазмага күчә. Лизосомалар — күзәнәкнең иң кечкенә
органоидларыннан берсе. Ләкин кечкенә булу — кирәксез дигән мәгънәне аңлатмый. Исегезгә
төшерегез әле, чукмарбашның койрыгы белән нәрсә була: берникадәр вакыттан соң ул
эрегән кебек була һәм юкка чыга, бу — лизосомаларның «эше». Бу органоидлар күзәнәк
эчендәге азык кисәкләрен, күзәнәкнең үлгән өлешләрен эшкәртүдә катнашалар. Кечкенә
түгәрәк тәнчекләр — рибосомалар барлык күзәнәкләрдә дә бар, аларда катлаулы аксым
молекулалары ясала.',
'Tsitoplazmada küzänäkneñ 30—50 % ın täşkil
itüçe küp sanlı waq kanalçıqlar häm quışlıqlar
bar. Bu — endoplazmatik çeltär. Ul küzänäkneñ barlıq öleşlären plazmatik membrana belän
totaştıra, törle organik matdälärne barlıqqa kiterüdä häm küçerüdä qatnaşa. Gol\'dji
apparatınıñ funksiäläre endoplazmatik çeltärnekenä oxşaş. Anıñ qatlawlı köpşäçeklär häm
quıqçıqlar sistemasında küzänäkneñ ähämiätle matdäläre — aqsımnar, maylar, uglevodlar
tuplana, soñınnan alar tsitoplazmağa küçä. Lizosomalar — küzänäkneñ iñ keçkenä
organoidlarınnan berse. Läkin keçkenä bulu — kiräksez digän mäğnäne añlatmıy. İsegezgä
töşeregez äle, çuqmarbaşnıñ qoyrığı belän närsä bula: berniqädär waqıttan soñ ul
eregän kebek bula häm yuqqa çığa, bu — lizosomalarnıñ «eşe». Bu organoidlar küzänäk
eçendäge azıq kisäklären, küzänäkneñ ülgän öleşlären eşkärtüdä qatnaşalar. Keçkenä
tügäräk tänçeklär — ribosomalar barlıq küzänäklärdä dä bar, alarda qatlawlı aqsım
molekulaları yasala.'
		],
		// 25
		[
		// http://tatar.org.ru/kurs/6-biologiya/cyr_13.html
'Ә менә тереклек эшчәнлеге өчен кирәкле энергия митохондрияләрдә
ясала һәм туплана. Ул күзәнәккә кергән туклыклы матдәләр таркалганда аерылып чыга.
Үсемлек күзәнәкләрендә аларга гына хас булган органоидлар — пластидлар була. Аларны
өч төргә бүләләр. Төссезләрендә, мәсәлән бәрәңге бүлбесендә, запас туклыклы матдәләр
туплана. Кызгылт сарыларында җимешләрнең, чәчәкләрнең төрле төсләрен билгели торган
кушылмалар була. Яшел пластидлар, яки хлоропластларда хлорофилл пигменты бар, ул
үсемлекләргә яшел төс бирә. Хлорофилл фотосинтез процессында органик матдәләр ясалуда
бик әһәмиятле роль уйный. Үсемлек
күзәнәкләре өчен вакуольләр дә характерлы. Алар — күзәнәк
сыекчасы белән тулган үтә күренмәле куыкчыклар. Күзәнәкләр цитоплазмасында төш
янында күзәнәк үзәге урнашкан. Хайваннар һәм түбән төзелешле үсемлекләрдә аның
составына центриольләр керә. Күзәнәк үзәге күзәнәк бүленүдә катнаша.',
'Ä menä tereklek eşçänlege öçen kiräkle energiä mitoxondriälärdä
yasala häm tuplana. Ul küzänäkkä kergän tuqlıqlı matdälär tarqalğanda ayırılıp çığa.
Üsemlek küzänäklärendä alarğa ğına xas bulğan organoidlar — plastidlar bula. Alarnı
öç törgä bülälär. Tössezlärendä, mäsälän bäräñge bülbesendä, zapas tuqlıqlı matdälär
tuplana. Qızğılt sarılarında cimeşlärneñ, çäçäklärneñ törle töslären bilgeli torğan
quşılmalar bula. Yäşel plastidlar, yäki xloroplastlarda xlorofill pigmentı bar, ul
üsemleklärgä yäşel tös birä. Xlorofill fotosintez protsessında organik matdälär yasaluda
bik ähämiätle rol\' uynıy. Üsemlek
küzänäkläre öçen vakuol\'lär dä xarakterlı. Alar — küzänäk
sıyıqçası belän tulğan ütä kürenmäle quıqçıqlar. Küzänäklär tsitoplazmasında töş
yanında küzänäk üzäge urnaşqan. Xaywannar häm tübän tözeleşle üsemleklärdä anıñ
sostavına tsentriol\'lär kerä. Küzänäk üzäge küzänäk bülenüdä qatnaşa.'
		],
		// 26
		[
		// http://tatar.org.ru/_educ/virt-gimn/books/3donyacyr2/41.html
'Файдалы казылмаларны ни өчен табалар
Кешеләр өйне, фабрика һәм заводларны кирпечсез,
бетонсыз сала аламы? Юк, билгеле. Әмма,
кирпеч һәм бетон ясау өчен, кешеләргә табигатьтән
ком, балчык, известьташ табарга туры килә.
Икътисад күмерсез, табигый газсыз, бензинсыз эш
итә аламы? Эш итә алмый; чөнки болар ягулык төрләре. Күмер һәм газны табигатьтән табалар.
Ә бензинны шулай ук табигать биргән нефтьтән
ясыйлар. Тирә-юнебездә никадәр металл әйберләр:
кайчы, кашык, кәстрүл, чиләкләр... Заводтагы
станоклар, самолётлар һәм автомобильләр, рельс
өстендәге поездлар һәм рельслар үзләре дә
металлдан эшләнгән. Ә металл табигатьтән алынган
тимер рудасыннан (мәгъдәннән) коела. Аш тозы — үзе бер гади генә матдә кебек. Ашка тоз
салганда, син, мөгаен, аның табынга кайдан килгәнен уйламыйсыңдыр да. Ә ул җир асты
шахталарыннан яки тозлы күл төпләреннән чыгарылган. Тозны бик күп чыгарырга туры килә.
Ул ризыкка салу өчен генә кулланылмый. Тоздан башка әле сабын һәм пыяла, күп кенә буяу
һәм даруларны да ясап булмый икән. Файдалы казылмалар — Җирнең гаять зур, бәя
биреп бетергесез байлыгы.',
'Faydalı qazılmalarnı ni öçen tabalar
Keşelär öyne, fabrika häm zavodlarnı kirpeçsez,
betonsız sala alamı? Yuq, bilgele. Ämma,
kirpeç häm beton yasaw öçen, keşelärgä tabiğättän
qom, balçıq, izvest\'taş tabarğa turı kilä.
İqtisad kümersez, tabiği gazsız, benzinsız eş
itä alamı? Eş itä almıy; çönki bolar yağulıq törläre. Kümer häm gaznı tabiğättän tabalar.
Ä benzinnı şulay uq tabiğät birgän neft\'tän
yasıylar. Tirä-yünebezdä niqädär metall äyberlär:
qayçı, qaşıq, kästrül, çiläklär... Zavodtağı
stanoklar, samolyotlar häm avtomobil\'lär, rel\'s
östendäge poyezdlar häm rel\'slar üzläre dä
metalldan eşlängän. Ä metall tabiğättän alınğan
timer rudasınnan (mäğdännän) qoyıla. Aş tozı — üze ber ğadi genä matdä kebek. Aşqa toz
salğanda, sin, möğayen, anıñ tabınğa qaydan kilgänen uylamıysıñdır da. Ä ul cir astı
şaxtalarınnan yäki tozlı kül töplärennän çığarılğan. Toznı bik küp çığarırğa turı kilä.
Ul rizıqqa salu öçen genä qullanılmıy. Tozdan başqa äle sabın häm pıyala, küp kenä buyaw
häm darularnı da yasap bulmıy ikän. Faydalı qazılmalar — Cirneñ ğäyät zur, bäyä
birep betergesez baylığı.'
		],
		// 27
		[
		// http://tatar.org.ru/kurs/10-11-himiya/cyr_77.html
'Бензолның молекуляр формуласы C6H6. Моннан
бензолның нык туендырылмаган кушылма икәне
күренә: чикле углеводородларның состав формуласына җиткерү өчен, аның молекуласында
сигез водород атомы җитми. Әгәр дә бензолны бромлы суга яки калий перманганаты эремәсенә
салып болгатсак, чиксез кушылмаларга хас булган реакцияләр бармавын күреп гаҗәпкә
калабыз. Моның сәбәбен матдәнең химик төзелешеннән чыгып аңлатырга кирәктер. Бензолның
төзелешен ачыклауны аның синтезыннан башлый алабыз. Бензолны 650 °C ка кадәр җылытылган,
активлаштырылган күмер тутырылган көпшә аша ацетилен уздырып табарга мөмкин.',
'Benzolnıñ molekulyar formulası -{C6H6}-. Monnan
benzolnıñ nıq tuyındırılmağan quşılma ikäne
kürenä: çikle uglevodorodlarnıñ sostav formulasına citkerü öçen, anıñ molekulasında
sigez vodorod atomı citmi. Ägär dä benzolnı bromlı suğa yäki kaliy permanganatı eremäsenä
salıp bolğatsaq, çiksez quşılmalarğa xas bulğan reaksiälär barmawın kürep ğäcäpkä
qalabız. Monıñ säbäben matdäneñ ximik tözeleşennän çığıp añlatırğa kiräkter. Benzolnıñ
tözeleşen açıqlawnı anıñ sintezınnan başlıy alabız. Benzolnı 650 °-{C}- qa qädär cılıtılğan,
aktivlaştırılğan kümer tutırılğan köpşä aşa atsetilen uzdırıp tabarğa mömkin.'
		],
		// 28
		[
		// http://tatar.org.ru/kurs/9-adabiyat/cyr_109.html
'Мәдрәсәне тәмамлагач, Каюм Насыйри Духовное училищеда, аннары Духовная семинариядә
татар теле укыта. Бер үк вакытта Казан университетында ирекле тыңлаучы рәвешендә
лекцияләргә йөри. Шулай итеп, 15 ел вакыт
үтеп тә китә. Шуннан соң әдип, татар балаларына
рус телен өйрәтер өчен, махсус мәктәп ача.
1879 елдан алып Каюм Насыйри гомерен язучылык
эшенә багышлый. Татарча өйрәнергә теләгән руслар өчен татар грамматикасы, русча өйрәнүче
татарлар өчен рус грамматикасы, татарларга дөньяви фәннәр өйрәтү максатыннан чыгып хисап,
география, табигать белеме атамаларын ачыклап хезмәтләр яза. Татар теленең аңлатмалы
сүзлеген, русча-татарча, татарча-русча сүзлекләр төзи. Татар халкының тарихына караган
материаллар, халык иҗаты үрнәкләрен җыеп һәм аларны фәнни эшкәртеп бастыра.',
'Mädräsäne tämamlağaç, Qayum Nasıyri Duxovnoye uçilişçeda, annarı Duxovnaya seminariädä
tatar tele uqıta. Ber ük waqıtta Qazan universitetında irekle tıñlawçı räweşendä
leksiälärgä yöri. Şulay itep, 15 yıl waqıt
ütep tä kitä. Şunnan soñ ädip, tatar balalarına
rus telen öyräter öçen, maxsus mäktäp aça.
1879 yıldan alıp Qayum Nasıyri ğömeren yazuçılıq
eşenä bağışlıy. Tatarça öyränergä telägän ruslar öçen tatar grammatikası, rusça öyränüçe
tatarlar öçen rus grammatikası, tatarlarğa dönyawi fännär öyrätü maqsatınnan çığıp xisap,
geografiä, tabiğät beleme atamaların açıqlap xezmätlär yaza. Tatar teleneñ añlatmalı
süzlegen, rusça-tatarça, tatarça-rusça süzleklär tözi. Tatar xalqınıñ tarixına qarağan
materiallar, xalıq icatı ürnäklären cıyıp häm alarnı fänni eşkärtep bastıra.'
		],
		// 29
		// spelling of июль and июнь in latin
		// in tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
		// contradicts rule in tt.wikipedia.org/wiki/Файл:Ию.jpg
		// same contradiction is in 2001 rules:
		// http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/18.jpg
		// http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/19.jpg
		[
'Сократ б.э.к. табигатьне объектив беркатлылыгы тиранлык
гаделлек гаделсезлек гадел
Ильяс Чыганаклар Ярослав июнь июль июнендә июлендә октябрендә
Унъеллык стенкасы клетчаткага клетчатка
стенкасыннан гыйбарәт газын беркайчан да чыганаклары коды
Кәүҗияк Колмәт Күктау Юлтимер Оскар Рәфыйков Мелвин Иоганн карабодай
комбинаты Аккирәй Лагерка Кармалка Кади Девичья Саминовка Сосновка
Казанка Шешминка Курасанов Александр Менделеевск Алексеевск
Стәрлетамак каучук ревизия командасын бертуган Якшыгол
декреты Башкордистан Башкордистанның эфирга Баязит мэр ТВсе картасында
Гафури Карл Коммунарлар ов Кутузов Ливәшәү Стәрлетамакта Вәгыйзов Айгөл
Җигәнтамак Золотоношка һәлак һәлаге
Йөрәктау Кантүк Константиноградовка
Новониколаевка Октябрьское Петропавловка
Покровка Николаевский
Казадаевка Кузьминовка Максимовка Наумовка Николаевка Петровка Маршановка Саратовка
Любовка Преображеновка Рязановка Соколовка Стрелковка Талалаевка Отрадовка',
'Sokrat b.e.q. tabiğätne obyektiv berqatlılığı tiranlıq
ğädellek ğädelsezlek ğädel
İlyas Çığanaqlar Yaroslav iyün iyül iyünendä iyülendä oktäberendä
Unyıllıq stenkası kletçatkağa kletçatka
stenkasınnan ğibärät gazın berqayçan da çığanaqları kodı
Käwciäk Qolmät Küktaw Yultimer Oskar Räfiqov Melvin İogann qaraboday
kombinatı Aqkiräy Lagerka Karmalka Qadi Deviçya Saminovka Sosnovka
Kazanka Şeşminka Kurasanov Aleksandr Mendeleyevsk Alekseyevsk
Stärletamaq kawçuk reviziä komandasın bertuğan Yaqşığol
dekretı Başqordistan Başqordistannıñ efirğa Bayazit mer TVse qartasında
Ğafuri Karl Kommunarlar ov Kutuzov Liväşäw Stärletamaqta Wäğizov Aygöl
Cigäntamaq Zolotonoşka häläk häläge
Yöräktaw Kantük Konstantinogradovka
Novonikolayevka Oktyabr\'skoye Petropavlovka
Pokrovka Nikolayevskiy
Kazadayevqa Kuz\'minovqa Maksimovqa Naumovqa Nikolayevqa Petrovqa Marşanovqa Saratovqa
Lyubovka Preobrajenovqa Ryazanovqa Sokolovqa Strelkovqa Talalayevqa Otradovqa'
		],
		// 30
		[
'клуб гамьле гаме гамь беркайдан да викимәгълүмат мәкалә дәһлигә карамзин
гданьск википедияны википедиясе тугандаш викисүзлек викисәфәр вики-проектлары
голландия гыйбадәтханәсе гыйбадәт фирдәүс гаук табигате карлар вики-текстны
б. э. к. меңьеллык баварлар утызъеллык бавар франк литваны делегациясе
апрель октябрь ноябрь сентябрь гыйнвар югославия актын һамбург дуйсбург шверин
шлезвиг югалтса аугуст мәшгуль 1 га ядкарь ядкарьлекләре
югалтмаган шонгауэр кауфман картиналар википедиясенең болгария латвия литва
македония молдавия хорватия канада алфавит фиркасе фирка декабрь',
'klub ğämle ğäme ğäm berqaydan da wikimäğlümät mäqälä dähligä karamzin
gdan\'sk wikipedianı wikipediäse tuğandaş wikisüzlek wikisäfär wiki-proyektları
gollandiä ğibädätxänäse ğibädät firdäüs gawk tabiğäte qarlar wiki-tekstnı
b. e. q. meñyıllıq bavarlar utızyıllıq bavar frank litvanı delegatsiäse
aprel oktäber noyäber sentäber ğinwar yugoslaviä aktın hamburg duysburg şverin
şlezvig yuğaltsa awgust mäşğül 1 ğa yädkär yädkärlekläre
yuğaltmağan şongawer kawfman kartinalar wikipediäseneñ bolgariä latviä litva
makedoniä moldaviä xorvatiä kanada alfavit firqäse firqä dekaber'
		],
		// 31
		// кафтан may be understood as каф+тан
		[
'культуралы оливка омега организм Мәүлүдова камзул казаки
организм организмының организмына майка куртка бурка гамаш
кафтан кожан кофта телогрейка фуфайка кепка косынка кубанка фуражка
шәльяулык картуз ботинка перчатка тәгаенләнүен калдыкларыннан газлардан
лавсан лайкра камбий камбийның пояста камбие саванналарда кораб матдәсе төяү
халәтендәге халәттә Йошкар-Ола Харьков Ижау Окт Ноя чуашлар православ
синагогасы риваятьләр риваять кайсыбер датировкага Вацлав утыртканнар мануфактуралар
адмираллык Василий камалыш Савин предприятие капиталы Канаш Нижгар Чабаксар
Каспий конка Горки ЮУЙларның Бервакытта ЮУЙлар Аксаков Балакирев гуманитар аграр
танк юридик Кариев Боратынский Горький Янка Купала Качалов консуллыклары
сервис Ростелеком хоккее Тимербулатов дирижёр газимов газыймов гафиятуллин Әүхәдиева
Мөэмин Константиновка Колсәет Чабакса Пермь Барнаул гафиятулла',
'kul\'turalı olivka omega organizm Mäwlüdova kamzul kazaki
organizm organizmınıñ organizmına mayka kurtka burka gamaş
qaftan kojan kofta telogreyka fufayka kepka kosınka kubanka furajka
şälyawlıq kartuz botinka perçatka täğäyenlänüen qaldıqlarınnan gazlardan
lavsan laykra kambiy kambiynıñ poyasta kambiyı savannalarda korab matdäse töyäw
xälätendäge xälättä Yoşkar-Ola Xar\'kov İjaw Okt Noyä çuaşlar pravoslav
sinagogası riwäyätlär riwäyät qaysıber datirovkağa Vatslav utırtqannar manufakturalar
admirallıq Vasiliy qamalış Savin predpriatie kapitalı Kanaş Nijgar Çabaksar
Kaspiy konka Gorki YUYlarnıñ Berwaqıtta YUYlar Aksakov Balakirev gumanitar agrar
tank yuridik Qariev Boratınskiy Gor\'kiy Yanka Kupala Kaçalov konsullıqları
servis Rostelekom xokkeyı Timerbulatov dirijyor gazimov ğazimov ğafiatullin Äwxädieva
Mö\'min Konstantinovqa Qolsäyet Çabaksa Perm\' Barnaul ğafiatulla'
		],
		// 32
		[
'мәкальләре йолдызнамә комбайн ганиева галимов дәвалаячаклар Г. Камал кушнаренко
һәлакәтендә идарәсе ризык кадерле гөлниса газыймҗановны чукоткаларны ситдыйк вузларда
әхлак интеллектуаль ток токны тогы дискуссия коллективына рафис вилаятен
әрдоган банк гарантияләргә гайнетдин аурупалы подкаст шагыйре эстәрлебаш вазыйфаларын
Гатаулла Гыйниятулла Гарифулла эластик гевея согын сокны Колумбия физик
Боливия Гарсия Хавьер Сапа Инка Парагвай Кукуруза маниока кока Кондорканка Боливар
Веласко Коста Уаскаран Сьерра Амазонка Титикака гумми гуммиластик билбаулары Сингапур
Шри-Ланка Гамбия Кот-д\'Ивуар Галмудуг нерв Маргарита ризыклары сервер
күзаллана Марк суперкулланучы гигабайт кодировкаларда кодировка кодлы Кармик Коала
азкөчле Дугласның Галактика автотукталышлар фольгадан тимерчыбыкны физигы банкаларның
вакуум обкладкалы обкладка обкладкалары константасы шкала эффекты корпус Шкаланың',
'mäqälläre yoldıznämä kombayn ğanieva ğalimov däwalayaçaqlar Ğ. Kamal kuşnarenko
häläkätendä idäräse rizıq qäderle gölnisa ğazimcanovnı çukotkalarnı sitdiq vuzlarda
äxläq intellektual\' tok toknı togı diskussiä kollektivına rafis wiläyäten
ärdoğan bank garantiälärgä ğäynetdin awrupalı podkast şağire estärlebaş wazıyfaların
Ğataulla Ğiniätulla Ğarifulla elastik geveya sogın soknı Kolumbiä fizik
Boliviä Garsiä Xavyer Sapa İnka Paragvay Kukuruza manioka koka Kondorkanka Bolivar
Velasko Kosta Waskaran Syerra Amazonka Titikaka gummi gummilastik bilbawları Singapur
Şri-Lanka Gambiä Kot-d\'İvwar Galmudug nerv Margarita rizıqları server
küzallana Mark superqullanuçı gigabayt kodirovkalarda kodirovka kodlı Karmik Koala
azköçle Duglasnıñ Galaktika avtotuqtalışlar fol\'gadan timerçıbıqnı fizigı bankalarnıñ
vakuum obkladkalı obkladka obkladkaları konstantası şkala effektı korpus Şkalanıñ'
		],
		// 33
		// i think әхлагы - äxläğe , әхлагындә - äxläğendä would be more correct, but
		// i write it the way it is really written, take in account that these tests
		// are to work in both directions
		[
'5енче 5 энче 5-энче Кох Пугачев Пугачёв Агалега округының командиры Дивизия субъекты округында
объектының Калуга Калугастат Балабаново Белоусово Жуков Юхнов Бабынинский
әйе байегет майя сәнгать банкы богданова камал колмәмәт даутов кардәш социаль
тыйбб круз друз әхлагы әхлагында әхлагындә тибы славяннар кириллица Перешчепкино Коми Эпикур
Локк индивид Габиния Галлия Гай Калигула өйрәтүе строе резервы Каһиннәр
колониаль әгъза Мөбарәк артъягыннан сәнагате юлъязма викториан һәрьяклап хәрабәләре
җинаять юлиан грегориан сулъяк персональ конституция һәлак потенциаль
цунами гыйсъянчылар Территориаль берьюлы Хрущёв документсыз Хьюстон берьеллык
Нәзарәте хакимияттә мәгълүматләр йортсыз сәркатибе провинциаль пианист пианино сүрә
Владимир I-нең АКШ-та Красная Поляна-га Сәгъди-нең Ачеһ-тә Ачеһ-тән Ачеһ-кә
Ачеһ-ле Ачеһ-леләр Ачеһ-не Ачеһ Тамиянг-та Мк-Кинли Вирджиния-да Грод-ка Кубрат-ка
',
'5ençe 5 ençe 5\'ençe Kox Pugaçev Pugaçov Agalega okrugınıñ komandirı Diviziä subyektı okrugında
obyektınıñ Qaluğa Kalugastat Balabanovo Belousovo Jukov Yuxnov Babıninskiy
äyye bayyeget mayya sänğät bankı bogdanova qamal qolmämät dautov qärdäş sotsial\'
tıybb kruz druz äxläğe äxlağında äxläğendä tibı slavyannar kirillitsa Pereşçepkino Komi Epikur
Lokk individ Gabiniä Galliä Gay Kaligula öyrätüe stroyı rezervı Qahinnär
kolonial\' äğzä Möbäräk artyağınnan sänäğäte yulyazma viktorian häryaqlap xäräbäläre
cinäyät yulian gregorian sulyaq personal\' konstitutsiä häläk potentsial\'
tsunami ğıysyançılar Territorial\' beryulı Xruşçov dokumentsız Xyuston beryıllıq
Näzäräte xakimiättä mäğlümätlär yortsız särkätibe provintsial\' pianist pianino sürä
Vladimir I\'neñ AQŞ\'ta Krasnaya Polyana\'ğa Säğdi\'neñ Açeh\'tä Açeh\'tän Açeh\'kä
Açeh\'le Açeh\'lelär Açeh\'ne Açeh Tamiang\'ta Mk\'Kinli Virdjiniä\'da Grod\'qa Qubrat\'qa
'
		],
		// 34
		// should be Әфганьстан
		// but popular and only used spelling is Әфганстан.
		// i see Äfğän is used in wikipedia so have chosen it for now for latin
		// Quba is Qup+a
		[
'энциклопедия астъязуы энциклопедиябез мускул фолликуллар Губанов Поварёнка мотоукчылар
Әфганстанда вакыйгалар Казакъстан Казакъ продразверстка Какуринны Гарри Колбурн архипелаг
ә VI—VIII йөзләрдә төньяк-көнчыгыш Кытайда Нью-Йорк умарта кортлары маркасында вассалы
хорватлар Гаити Куба Багамалар Кайман рабфагында Рабфакны Карл Фукс Петропавел ткр.
Бәкбау юрист ихтыяҗлары Шәрыкле отрядлар Пьетро геноцид Нәкъ мөһаҗирләр Донецк
канцлеры Меркель принцибы транзакция акция функциясе зәгыйфьлеге дисциплинар Аньелли
цифрлы Вице-президент индеец булуына фьючерс мөстәкыйльлеген Циклон цементобетон
концертында яшүсмерләр шәһәр думасы гласные Дарья Бондюг парашют Иркутскидән Иркутск
Кагъбә Шәфигый Коръән тәмугка тәмуг франция кулъязма д\'аргент Ильич Ульянов
Башкортстан елъязмаларында раюн мәэмүри Софья инкыйлаб периоды тәкъвимдәге фидакяр
Ананьино яман диалектлары конференция Христианлык фильм штатски
интервью сурәт медиа иҗтимагыйлык шәрехләрне Л.Р. Л. Р. L Р ЛР комиссариатын',
// ц always ts according to http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/09.jpg ,
// 10.jpg , 13.jpg . (though in case of funktsiä funksiä would be ok).
// - that was wrong idea, it was not accepted by government of tatarstan.
// ц is sometimes s according to
// https://tt.wikipedia.org/wiki/%D0%A4%D0%B0%D0%B9%D0%BB:Tatar_telenen_orfografiase_10.jpg
// ictimağilıq should be better ictimağilek, but let me think that "thick" suffixes are
// allowed after arabic "i" suffix, there is nothing written about it.
// "ictimağilıq" makes conversion easier
'ensiklopediä astyazuı ensiklopediäbez muskul follikullar Gubanov Povaryonka motouqçılar
Äfğänstanda waqiğalar Qazaqstan Qazaq prodrazverstka Kakurinnı Garri Kolburn arxipelag
ä VI—VIII yözlärdä tönyaq-könçığış Qıtayda Nyu-York umarta qortları markasında vassalı
xorvatlar Gaiti Quba Bagamalar Kayman rabfagında Rabfaknı Karl Fuks Petropavel tqr.
Bäkbaw yurist ixtıyacları Şäreqle otryadlar Pyetro genotsid Näq möhäcirlär Donetsk
kantslerı Merkel\' printsibı tranzaktsiä aktsiä funksiäse zäğiflege distsiplinar Anyelli
tsifrlı Vitse-prezident indeyets buluına fyuçers möstäqillegen Tsiklon tsementobeton
konsertında yäşüsmerlär şähär duması glasnıyı Dar\'ya Bondyug paraşyut İrkutskidän İrkutsk
Qäğbä Şäfiği Qör\'än tämuğqa tämuğ fransiä qulyazma d\'argent İl\'yiç Ul\'yanov
Başqortstan yılyazmalarında rayun mä\'müri Sofya inqıylab periodı täqwimdäge fidakär
Anan\'yino yaman dialektları konferentsiä Xristianlıq fil\'m ştatski
intervyu sürät media ictimağilıq şärexlärne L.R. L. R. L R LR komissariatın'
		],
		// 35
		// Qama is verb
		[
'миллиард компакт Кристиан әгъзасе әгъзасы дозировкада сегменты аквариум Аляска Арканзас Гавайлар
Делавэр Канзас Мичиган Небраска пластинка гаепсез Хакасия калуына карнавал Гофман капитан
Гардин Куинси полк Закари Гарфилд Мак-Кинли Уоррен Гардинг Калвин Уокер Барак Ауразия
әдәбияты әдәбияте Вануату Бразилияда Бразилиядә Бразилия Австралияда Австралиядә Австралия
вулкан Кук материгы Тасманияның Тасманиянең Тасмания Уэльс 27%-ы икепулатлы копия еллыгына
Уоттс 19-ынчы Тонга Тувалу Гуам Гаваялар Мидуэй Ниуэ Уоллис Уэйк космик куб святослав
каһарманнары олугъ болгарский гыйззәтуллина касыйм маһисәрвәр габдулла габдрахман гаиләдән
комачау кыямәт галәмәтләр лаек байрак җәендә вафатлар лалә майнау утравындагы аллеясы аллеясе
классигы җәмәгать авыргазы елгы теркәү иганә викиханә кортни телеканал чагында ябыкты
дәү кайсы гомосексуалист яраткан гашыйк уилик кайткан чакырылган уиллис уайл уильямс уизерспун
болдуин гари вентреска рамка шулкадәр кәефе чакта тавыш хакында кулга тәүге Бикбулатов туктый
редакция Гыйльметдин сөешүләр тавы ныгытма яткан Садыйков Исмәгыйлева Галләмов аенда
яулый Кыйтга Билалетдинов кубогы Рига Кама аяктубы Санкт-Петербург дәвере оттырганда Раменское
каталогы Ягъкуб репертуарында Яугирләр кушымтасы горур әүвәл Равил Илфак Мактау балкышы корал
Кайбыч большевикларныкы бригадасы бульвар ялгызларны',
'milliard kompakt Kristian äğzäse äğzası dozirovkada segmentı akvarium Alyaska Arkanzas Gavaylar
Delaver Kanzas Miçigan Nebraska plastinka ğäyepsez Xakasiä qaluına karnaval Gofman kapitan
Gardin Kwinsi polk Zakari Garfild Mak\'Kinli Worren Garding Kalvin Woker Barak Awraziä
ädäbiatı ädäbiäte Vanuatu Braziliada Braziliädä Braziliä Avstraliada Avstraliädä Avstraliä
vulkan Kuk materigı Tasmanianıñ Tasmaniäneñ Tasmaniä Wel\'s 27%\'ı ikepulatlı kopiä yıllığına
Wotts 19\'ınçı Tonga Tuvalu Guam Gavayalar Midwey Niwe Wollis Weyk kosmik kub svyatoslav
qaharmannarı oluğ bolgarskiy ğizzätullina qasıym mahisärwär ğabdulla ğabdraxman ğailädän
qomaçaw qıyämät ğälämätlär layıq bayraq cäyendä wafatlar lalä maynaw utrawındağı alleyası alleyäse
klassigı cämäğät awırğazı yılğı terkäw iğänä wikixänä kortni telekanal çağında yabıqtı
däw qaysı gomoseksualist yaratqan ğaşiq wilik qaytqan çaqırılğan willis wayl wilyams wizerspun
boldwin gari ventreska ramka şulqädär käyefe çaqta tawış xaqında qulğa täwge Bikbulatov tuqtıy
redaksiä Ğilmetdin söyeşülär tawı nığıtma yatqan Sadiqov İsmäğileva Ğallämov ayında
yawlıy Qıytğa Bilaletdinov kubogı Riga Qama ayaqtubı Sankt-Peterburg däwere ottırğanda Ramenskoye
katalogı Yağqub repertuarında Yawgirlär quşımtası ğorur äwwäl Rawil İlfaq Maqtaw balqışı qoral
Qaybıç bol\'şeviklarnıqı brigadası bul\'var yalğızlarnı'
		],
		// 36
		[
'куркума яктылык казан гидротөененнән кошаева налимка танайка урсалинка утчанка телевидение
карлыгач күпсанлы байтак тиен ярты кассир вёрстка Җәүдәт Агыйдел Галикәева Абдрафыйков Попков
Ремель Марсель нәшрият нәшрияты нәшрияте тәгълиматына рухание күпкырлы
субстанция субстанциягә субстанцияга гөнаһка колбиләүчелекне кофи Вангари Гор Сатьяртхи
Боһемия Галиция Каринтия Моравия Градишка ЮТК Яхт-клуб автопаркында Красное
Наука Икар Матков Автоваз Түбәнкамашин Чугунов Чагин Каргинов Дакар Каһирә Голуб Якубовлар
данә Фәрукшин Октябрьск Кадычкин Зуйков Дубровка Клементьев Караськин конкурсы Нурмөхәммәтов
Ермаков Якутияда көнбакты ясмык петрушка әфьюн көнбагыш тайгалар гумус Кок-сагыз Тимофеевка
Камалов',
'kurkuma yaqtılıq qazan gidrotöyenennän koşayeva nalimka tanayka ursalinka utçanka televidenie
qarlığaç küpsanlı baytaq tien yartı kassir vyorstka Cäwdät Ağidel Ğälikäyeva Abdrafiqov Popkov
Remel\' Marsel\' näşriät näşriatı näşriäte täğlimatına ruxaniyı küpqırlı
substantsiä substantsiägä substantsiağa gönahqa qolbiläwçelekne kofi Vangari Gor Satyartxi
Bohemiä Galitsiä Karintiä Moraviä Gradişka YuTQ Yaxt-klub avtoparkında Krasnoye
Nauka İkar Matkov Avtovaz Tübänkamaşin Çugunov Çagin Karginov Dakar Qahirä Golub Yaqubovlar
dänä Färüqşin Oktyabr\'sk Kadıçkin Zuykov Dubrovka Klementyev Karas\'kin konkursı Nurmöxämmätov
Yermakov Yakutiada könbaqtı yasmıq petruşka äfyün könbağış taygalar gumus Kok-sağız Timofeyevka
Kamalov'
		],
	];
	private function oneConversionToCyrillic( $e ) {
		$this->assertEquals(
			preg_replace( '/-{|}-/', '', $e[0] ),
			$this->convertToCyrillic( $e[1] )
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToCyrillic() {
		foreach ( $this->textPairs as $e ) {
			$this->oneConversionToCyrillic( $e );
		}
		// $this->oneConversionToCyrillic( $this->textPairs[34] );
		// alternative spellings in latin, but should be converted correctly.
		// i could but do not add Yeltsin, Çarlz etc here, also do not add them
		// to converter, so Yeltsin will become Елцин etc.
		// i added some words (3 words) like ultimatum, but i do not add folklor.
		// and seems i am not going to add such words further.
		// i think ' can be used in wikipedia, to write fol'klor etc, so, additional
		// rules re not needed. maybe i will add them in comments.
		// disadvantages of the ' (using it for palatalisation):
		// 1) using ' is not supported by 2012 tatarstan law 2) search engines may
		// split words in at them, but in tatar latin this is not morpheme border,
		// only phonetic palatalisation and hamza sound sign
		// according to 2000's resolution:
		// tt.wikipedia.org/wiki/Файл:Tatar_telenen_orfografiase_10.jpg
		// (also in 2001's rules: http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/19.jpg )
		// (it is also
		// used in places of morpheme border in words like d'Artanyan:
		// http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/23.jpg ,
		// , and it is usually used
		// between foreign language roots and tatar suffixes, for example Windows'ta
		// but that case is not shown in 2000's rules, but in case of abbreviations
		// seems "-" should be used: http://qdb.tmf.org.ru/skanlangan/m.zakiyev.latin/25.jpg ,
		// so i can conclude that after foreign roots also "-" should be used like that,
		// but it also only about using "-" when word is divided when it comes to end of string,
		// suffixes are expected to be written conjointly, and i can say that case of foreign
		// words is not considered there.
		// for now tests here are for using of ', see test #33 above,
		// and ' is used in wikipedia, as i see, let it be used so).
		// disadvantages of not showing palatalisation:
		// 1) it is rude phonetically 2) in some places lack of ' makes
		// meaning difference, though it is possible
		// to know out right meaning from context: feodal vs feodal'.
		// other cases that i have found in tatar wikipedia: Fundamental, konsultant,
		// medal, sentimental, Filadelfiya,
		// i add İtalyan because it is converted more incorrectly.
		// Konstantinopol - there is a problem with words like this:
		// Konstantinopol'ğa should be Konstantinopol''ğa if to use ' for palatalisation,
		// one ' for palatalisation, and one for separation of suffix from foreign root.
		// kult, kolonial, ultrafiolet, spektakl, Skulptor, federal, Poznan, Polşa, muzıkal,
		// model, stil, fakultet,
		// also similar situation with words when palatalisation y is not used:
		// Finlandiä,
		// i do not add xäkimiät (ie i do not make it converted to хакимият,
		// because it is xäkimiät is some tatarisation, it is not usual form;
		// let it stay tatarised, modified also when converted to cyrillic).
		// also i do not add Möselmännär, şäğir, Şäğbän, Milädi.
		$this->assertEquals(
'Әфганстанда риваяте Риваятьләрдә Нью-Йорк конференция транзакция акция ультиматумын
Большевик этнокультур кагазь җинаять Итальян гомумиләштерергә',
			$this->convertToCyrillic(
'Äfğänstanda riwayäte Riwayätlärdä N\'yu-York konferensiä tranzaksiä aksiä ultimatumın
Bolşevik etnokultur qäğäz cinayät İtalyan ğömümiläşterergä'
			)
		);
		// erroneus spellings in latin, but should be converted correctly
		// these are erroneus according to rules but could be used if rules were appropriate
		$this->assertEquals(
'тәнкыйть булуына эләгүе алуы үсүенә мәдәният Сүрия ия иелмичә Массачусеттс
Француз кыяфәтен',
			$this->convertToCyrillic(
'tänqiyt buluwına elägüwe aluwı üsüwenä mädäniyät Süriyä iyä iyelmiçä Massaçusetts
Frantsuz qıyäfäten'
			)
		);
		// erroneus spellings, but i would like converter works appropriately
		// i do not add Al'aska here
		$this->assertEquals(
'Кенъяга көзгыдәй йилдан Коръән вәзгыятне',
			$this->convertToCyrillic(
'Kenyağa közğedäy yildan Qor\'än wäzğıyätne'
			)
		);
		/*
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdb',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{db}-' )
		);
		// A simple convertion of Cyrillic to Cyrillic
		$this->assertEquals(
'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз
ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә
булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп
куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади
эшләү ысуллары да башка төрле, дибез.',
			$this->convertToCyrillic(
'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз
ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә
булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп
куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади
эшләү ысуллары да башка төрле, дибез.' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdaž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{da}-ž' )
		);
		*/
	}

	private function oneConversionToLatin( $e ) {
		$this->assertEquals(
			preg_replace( '/-{|}-/', '', $e[1] ),
			$this->convertToLatin( $e[0] )
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 *===================================================================================
	 */
	public function testConversionToLatin() {
		foreach ( $this->textPairs as $e ) {
			$this->oneConversionToLatin( $e );
		}
		// $this->oneConversionToLatin( $this->textPairs[33] );
		// words to convert only to one direction, cyrl to lat
		// i think режиссер should be written режиссёр and i need not
		// to fix it if it was written режиссер, etc, i was going to add
		// that here, but i do not add it. i change my mind and add
		// here and to converter only some words
		$this->assertEquals(
'Namibiä berniçä rejissyor rejissyor flyora flyora men men gimnastyorka
Artyom kozıryoklı volontyorlar Tuberkulyoz iqtisadi ezläwçelär aktyor
Muravyov prizyorı ğazimcanovnı vyorstka Fyodor bayrağı',
// iqtisadie iqtisadiendä
			$this->convertToLatin(
'Нами́бия бер­ничә режиссёр режиссер флера флёра мен мэн гимнастерка
Артем козыреклы волонтерлар Туберкулез икътисадый эзләучеләр актер
Муравьев призеры газимҗановны верстка Федор байрағы'
// икътисадые икътисадыенда
			)
		);
		/*
		// A simple convertion of Latin to Latin
		// i comment this out because there are probably no problems
		$this->assertEquals(
'Törkiä teleradiokompaniäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA),
"Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press
Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına
kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas,
kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.',
			$this->convertToLatin(
'Törkiä teleradiokompaniäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA),
"Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press
Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına
kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas,
kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.' )
		);
		*/
		/*// A convertion of Cyrillic to Latin. Some letters capital
		// i comment this out because style of writing all with upper case while
		// it is not an abbreviation is probably not used in wikipedia
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
		$this->assertEquals( <<<'EOD'
 QUAsı QUa QIYAsı kARAwATlar EVOLyutsİYAğA KİENÜEndä KİENÜendä UQUı UQUIn KamAZ.
 möstäqİl YeRE Ye A Yu YEFäk
EOD
,
			$this->convertToLatin( <<<'EOD'
 КУАсы КУа КЫЯсы кАРАвАТлар ЭВОЛюцИЯгА КИЕНҮЕндә КИЕНҮендә УКУы УКУЫн КамАЗ.
 мөстәкЫЙль ЕРЭ Е А Ю ЕФәк
EOD
)
		);*/
		/*// A convertion of Cyrillic to Latin. All capitals
		// i comment this out because style of writing all with upper case while
		// it is not an abbreviation is probably not used in wikipedia
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
		$this->assertEquals( <<<'EOD'
QIYAMOVA YÜN YIRAQ YUAN ĞÄYÄR ĞÄR CÄYÄ İMAMİEVA
TAQIYA EVOL'UTSİYAĞA EVOLÜTSİÄGÄ EVOLÜTSİÄ
YUMYA YUMYA FEYA QIYA QIYASI KİENÜE DÖNYA UQUI QUA
EOD
,
			$this->convertToLatin( <<<'EOD'
КЫЯМОВА ЮНЬ ЕРАК ЮАН ГАЯРЬ ГАРЬ ҖӘЯ ИМАМИЕВА
ТАКЫЯ ЭВОЛЮЦИЯГА ЭВОЛЮЦИЯГӘ ЭВОЛЮЦИЯ
ЮМЬЯ ЮМЪЯ ФЕЯ КЫЯ КЫЯСЫ КИЕНҮЕ ДӨНЬЯ УКУЫ КУА
EOD
)
		);*/
/*		// A convertion of Cyrillic to Latin; hypothetical words and russian
		// words that may appear by new usage, or by some mistake, for example,
		// in place names without -{ }- markers
		// i commented this out because it is not passed by converter for now
// this was a test with tricky words for my converter http://aylandirow.tmf.org.ru/
// and also for other converters
		$this->assertEquals( <<<'EOD'
revol'utsiyu siyuga revol'utsiye revol'utsieda revol'utsiedä biegä bieda  nail'a  t'uk
v'yuk  utyos ut'yos utyos bel'ya belya  bel'a  bel'yu belyu  bel'u  bel'ye belye bele
bel'yo belyo belyo  feye  feyu feyo  fei  izyan iz'yan iz'an iz'-an iz-an izan
kukmorskiyğa  kukmor'ane  soverşayet  postits'a  postit's'a  v  knige vrag goden
tigra tigr aktsiz küäm Yolqa YOLka Yo YeRE baqçä
kiyenüwe bvg bv abv Fya vrağ vse vzrıw bawlı
tuğrı ğu göa uğrı uğlan qıyraq  kompyuter kamennıy kl'uç TYaG yomğaqlayaçaq
cämğıyätkä cämğıyatı cämğınät cämğıyu cämğıyuda cämğıyüdä cämğıyüt qänäğätkä  yojikovıy
yoj  pervıy camğıyu täräqqiatı kor'aga drug drugqa poçinok kuçuk Axmetşin
täräqqinät täräqqiynät täräqqiät täräqqine täräqqinä täräqqini täräqqin' täräqqin
taraqqıya cämğıya camğıya äqıya iqıya ğätqıya äqaya sänagät ogo pervıy kukmorskiy
yıqıya ÄQIYA ıqıya ğatqıya nqıya äğıya iğıya ğätğıya yığıya ağıya ığıya ğatğıya nğıya
yöreyk yäşiyk yäşiyk ayk şeyk tuyk böyk säğa  kukmor'ane  vmeste  kamennıy
veduşçem  vedu  djannatu  djannatov  v  kabardino kabardinov  qawardino  balkarii
uniçtojen  uniçtojiv  yanvar'a  predlagayem  kaçestvennıye  şemordan  luçşeye  pervoye
kompyuter  interesuyet  odejdı  priglaşayem  verxniy äÄ ÄTSÄ yuN' ÄFEYO ÄYOJZ qayıq
AYeA!  YeA!  Yea  yeA  AYe!!  aYe!!  Ayı  AYea  AyıA  Ayıa  aYeA!  aYea  ayıA
kiäwe  riaw üendä uquwı üä üe KLYO KL'Yo fevğa yevğa fovğa fofovğa çiä tarik ayıa
koldun kolwun tänqıy tänğıy tänğıya gore [wazğıyät]wazğıyät yadk'are ğäyere krasivıy
EOD
,
			$this->convertToLatin( <<<'EOD'
революцию сиюга революцие революциеда революциедә биегә биеда  наиля  тюк
вьюк  утёс утьёс утъёс белья белъя  беля  белью белъю  белю  белье белъе беле
бельё белъё белё  фее  фею феё  феи  изъян изьян изян изьан изъан изан
кукморскийга  кукморяне  совершает  постится  поститься  в  книге враг годен
тигра тигр акциз күәм Ёлка ЁЛка Ё ЕРЭ бакчә
кийенүве бвг бв абв Фя враг все взрыв бавлы
тугры гу гөа угры углан кыйрак  компъютер каменный ключ ТЯГ йомгаклaячак
җәмгыяткә җәмгыяты җәмгынәт җәмгыю җәмгыюда җәмгыюдә җәмгыють канәгаткә  ёжиковый
ёж  первый җамгыю тәрәккыяты коряга друг другка починок кучук Ахметшин
тәрәккынәт тәрәккыйнәт тәрәккыйәт тәрәккыне тәрәккынә тәрәккыни тәрәккынь тәрәккын
тараккыя җәмгыя җамгыя әкыя икыя гатькыя әкая сәнагәть ого первый кукморский
екыя ӘКЫЯ ыкыя гаткыя нкыя әгыя игыя гатьгыя егыя агыя ыгыя гатгыя нгыя
йөрейк яшийк йәшийк айк шейк туйк бөйк сәга  кукморяне  вместе  каменный
ведущем  веду  джаннату  джаннатов  в  кабардино кабардинов  кавардино  балкарии
уничтожен  уничтожив  января  предлагаем  качественные  шемордан  лучшее  первое
интересует  одежды  приглашаем  верхний әӘ ӘЦӘ юНЬ ӘФЕЁ ӘЁЖЗ кайык
АЕА!  ЕА!  Еа  еА  АЕ!!  аЕ!!  Ае  АЕа  АеА  Аеа  аЕА!  аЕа  аеА
кийәве  рийав Үендә укувы үә үе КЛЁ КЛЬЁ февга евга фовга фофовга чийә тарик аеа
колдун колвун тәнкый тәнгый тәнгыя горе [вазгыять]вазгыять ядкяре гаере красивый
EOD
)
		);*/
	}

	# #### HELPERS #####################################################
	/**
	 * Wrapper to verify text stay the same after applying conversion
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'tt-cyrl' or 'tt-latn'
	 * @param string $msg Optional message
	 */
	protected function assertUnConverted( $text, $variant, $msg = '' ) {
		$this->assertEquals(
			$text,
			$this->convertTo( $text, $variant ),
			$msg
		);
	}

	/**
	 * Wrapper to verify a text is different once converted to a variant.
	 * @param string $text Text to convert
	 * @param string $variant Language variant 'tt-cyrl' or 'tt-latn'
	 * @param string $msg Optional message
	 */
	protected function assertConverted( $text, $variant, $msg = '' ) {
		$this->assertNotEquals(
			$text,
			$this->convertTo( $text, $variant ),
			$msg
		);
	}

	/**
	 * Verifiy the given Cyrillic text is not converted when using
	 * using the cyrillic variant and converted to Latin when using
	 * the Latin variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertCyrillic( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'tt-cyrl', $msg );
		$this->assertConverted( $text, 'tt-latn-x-2000', $msg );
	}

	/**
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'tt-latn-x-2000', $msg );
		$this->assertConverted( $text, 'tt-cyrl', $msg );
	}

	/** Wrapper for converter::convertTo() method*/
	protected function convertTo( $text, $variant ) {
		return $this->getLang()->mConverter->convertTo( $text, $variant );
	}

	protected function convertToCyrillic( $text ) {
		return $this->convertTo( $text, 'tt-cyrl' );
	}

	protected function convertToLatin( $text ) {
		return $this->convertTo( $text, 'tt-latn-x-2000' );
	}
}
