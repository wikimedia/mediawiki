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

	/**
	 * @author Nikola Smolenski
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToCyrillic() {
		// A convertion of Latin to Cyrillic
		$this->assertEquals( 'Төркия
 Төркия телерадиокомпаниясенең рәсми веб сайты. Бу сәхифәдә "Anadolu" агентлыгы (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" агентлыкларының автор хокукларына кергән материаллары урнашкан. Материаллар һәм хәбәрләр рөхсәтсез кулланылмас, копияланмас. ТРТ тышкы бәйләнешле сайтларның эчтәлегеннән җаваплы түгел.
Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк:"Төркия белән Көньяк Корея арасындагы мөнәсәбәтләр стратегик уртаклык дәрәҗәсендә"
Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк Көньяк Корея –Төркия дуслык төркеме башлыгы Hankoo Lee һәм янындагы вәкиллекне кабул итте.
Җәмил Чичәк очрашуда ике илнең географик яктан бер-берсеннән бик ерак булса да мөнәсәбәтләр җәһәтеннән якын булуын белдерде.
Төркия белән Көньяк Корея арасындагы мөнәсәбәтләрнең стратегик уртаклык дәрәҗәсендә булуына басым ясаучы Җәмил Чичәк “2017 нче йлда мөнәсәбәтләребезнең 60 еллыгын билгеләп үтәчәкбез. Бу исә элемтәләрне тагын камилләштерәчәк һәм халыкларны да үзара якынлаштырачак”диде.',
			$this->convertToCyrillic( 'Törkiä
 Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "-{-{Anadolu}-}-" agentlığı (-{AA}-), "-{Agence France-Presse}-" (-{AFP}-), "-{Associated Press}-" (-{AP}-), "-{Reuters}-", "-{Deutsche Press Agentur}-" (-{DPA}-), -{ATSH}-, -{EFE}-, -{MENA}-, -{ITAR TASS}-, "-{XINHUA}-" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.
Törkiya Böyek Millät Mäclese başlığı Cämil Çiçäk:"Törkiyä belän Könyaq Koreya arasındağı mönäsäbätlär strategik urtaqlıq däräcäsendä"
Törkiya Böyek Millät Mäclese başlığı Cämil Çiçäk Könyaq Koreya –Törkiyä duslıq törkeme başlığı -{Hankoo Lee}- häm yanındağı wäkillekne qabul itte.
Cämil Çiçäk oçraşuda ike ilneñ geografik yaqtan ber-bersennän bik yıraq bulsa da mönäsäbätlär cähätennän yaqın buluın belderde.
Törkiyä belän Könyaq Koreya arasındağı mönäsäbätlärneñ strategik urtaqlıq däräcäsendä buluına basım yasawçı Cämil Çiçäk “2017 nçe ylda mönäsäbätlärebezneñ 60 yıllığın bilgeläp ütäçäkbez. Bu isä elemtälärne tağın kamilläşteräçäk häm xalıqlarnı da üzara yaqınlaştıraçaq”dide.' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( '“Гадәләт һәм калкыну” партиясеннән халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алганнан соң Фидан кабат милли күзләү оешмасы киңәшчесе итеп билгеләнде
Һакан Фидан “Гадәләт һәм калкыну” партиясеннән халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алды.
Бу сәбәпле Фидан премьер-министр Әхмәт Давытоглу тарафыннан элекке эше булган милли күзләү оешмасы киңәшчесе вазыйфасына кабат билгеләнде.
Премьер-министр урынбасары һәм хөкүмәт сүзчесе Бүләнт Арынч министрлар шурасыннан соң үткәрелгән матбугат очрашуында “Премьер-министр Һакан Фиданны кабат милли күзләү оешмасы киңәшчесе итеп билгеләде” диде.
Һакан Фидан халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алганнан соң болай дип белдерү ясады:” Илемә һәм милләтемә хезмәт итү юлында , бүгенге көнгә кадәр булганы кебек моннан соң да үземә бирелгән вазыйфаны җиренә җиткереп башкару өчен тырышачакмын. Миңа яклау күрсәткән һәм ышанган илбашыбыз һәм премьер-министрга , газиз халкыма рәхмәтләремне җиткерәм”.',
			$this->convertToCyrillic( '“Ğadälät häm qalqınu” partiyäsennän xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire alğannan soñ Fidan qabat milli küzläw oyışması kiñäşçese itep bilgelände
Hakan Fidan “Ğadälät häm qalqınu” partiyäsennän xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire aldı.
Bu säbäple Fidan premyer-ministr Äxmät Dawıtoğlu tarafınnan elekke eşe bulğan milli küzläw oyışması kiñäşçese wazıyfasına qabat bilgelände.
Premyer-ministr urınbasarı häm xökümät süzçese Bülänt Arınç ministrlar şurasınnan soñ ütkärelgän matbuğat oçraşuında “Premyer-ministr Hakan Fidannı qabat milli küzläw oyışması kiñäşçese itep bilgeläde” dide.
Hakan Fidan xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire alğannan soñ bolay dip belderü yasadı:” İlemä häm millätemä xezmät itü yulında , bügenge köngä qadär bulğanı kebek monnan soñ da üzemä birelgän wazıyfanı cirenä citkerep başqaru öçen tırışaçaqmın. Miña yaqlaw kürsätkän häm ışanğan ilbaşıbız häm premyer-ministrğa , ğaziz xalqıma räxmätläremne citkeräm”.' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( 'Төркиянең көньяк-көнчыгышында үсеш планы
Урнаштыру 09.03.2015 Яңарту 10.03.2015
А А
Төркия Премьер-министры Әхмәт Давытоглу Көньяк-көнчыгыш Анадолу проектының (GAP) гамәл планын ачыклады.
Давытоглу яңарыш яклы, иктисади һәм иҗтимагый үсешне тизләтүче, эш белән тәэмин итүне арттыручы, җитештерүчәнлеккә юнәлгән, һуманитар калкыну-үсешне кызыксындыручы, тәшвик итүче яңа сәясәт алып барачакларын белдерде.
Гамәл планының 5 төп өлкәдән торуын ассызыклаучы Давытоглу бу планга 2018нче ел ахырына хәтле үзәк идарә бюджетыннан якынча 27 миллиард лира күләмендә инвестиция аерылып куелачагын әйтте.
План нәтиҗәсендә GAP Көньяк-көнчыгыш Анадолу проекты кысаларында туплам 1 миллион 100 мең гектар мәйданның сугарылуы өчен саклагыч аскормаларының тәмамланачагын, су челтәре төзелешенең дә мөһим күләмдә бетереләчәгенә игътибарны юнәлтүче Премьер-министр натурал игенчелек белән шөгыйлләнүче игенчеләрнең салымнарына финанс ярдәм күрсәтеләчәген белдерде.
"Гамәл планы” азагы булган 2018нче елда эшсезлек күләмен киметүда тәвәккәлбез”, - дип әйтүче Давытоглу төбәктәге чик буе капкаларын да көчәйтәчәкләрен ассызыклап әйтеп узды.
Давытоглу мәгариф, туризм һәм сәламәтлек саклау өлкәсенда дә мөһим адымнар ясалачагын әйтте.',
			$this->convertToCyrillic( 'Törkiyäneñ könyaq-könçığışında üseş planı
Urnaştıru 09.03.2015 Yañartu 10.03.2015
A A
Törkiyä Premyer-ministrı Äxmät Dawıtoğlu Könyaq-könçığış Anadolu proyektınıñ (-{GAP}-) ğämäl planın açıqladı.
Dawıtoğlu yañarış yaqlı, iqtisadi häm ictimaği üseşne tizlätüçe, eş belän tä’min itüne arttıruçı, citeşterüçänlekkä yünälgän, humanitar qalqınu-üseşne qızıqsındıruçı, täşviq itüçe yaña säyäsät alıp baraçaqların belderde.
Ğämäl planınıñ 5 töp ölkädän toruın assızıqlawçı Dawıtoğlu bu planğa 2018nçe yıl axırına xätle üzäk idarä byudjetınnan yaqınça 27 milliard lira külämendä investitsiya ayırılıp quyılaçağın äytte.
Plan näticäsendä -{GAP}- Könyaq-könçığış Anadolu proyektı qısalarında tuplam 1 million 100 meñ gektar mäydannıñ suğarıluı öçen saqlağıç asqormalarınıñ tämamlanaçağın, su çeltäre tözeleşeneñ dä möhim külämdä betereläçägenä iğtibarnı yünältüçe Premyer-ministr natural igençelek belän şöğillänüçe igençelärneñ salımnarına finans yärdäm kürsäteläçägen belderde.
"Ğämäl planı” azağı bulğan 2018nçe yılda eşsezlek külämen kimetüda täwäkkälbez”, - dip äytüçe Dawıtoğlu töbäktäge çik buyı qapqaların da köçäytäçäklären assızıqlap äytep uzdı.
Dawıtoğlu mäğärif, turizm häm sälamätlek saqlaw ölkäsenda dä möhim adımnar yasalaçağın äytte.' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( 'Министрлар шурасы җыелышы
Министрлар шурасы җыелышы
Урнаштыру 09.03.2015 Яңарту 09.03.2015
А А
Җыелышны илбашы Рәҗәп Таййип Әрдоган җитәкли
Министрлар шурасы илбашы идарәсе сараенда җыелды.
Төркия илбашы Рәҗәп Таййип Әрдоган икенче тапкыр министрлар шурасын җитәкли.
Шурада ил эчендә иминлек пакеты, валюта курсындагы хәрәкәтлелек, илдә террорны бетерү өчен алып барлган чишелеш барышы, хатын-кыз проблемалары , Сүрия һәм Гыйрак чик буйларындагы тәрәккыятьләр карала.
Төркия илбашы Рәҗәп Таййип Әрдоган илбашы булып сайланганнан соң Төп Канунның үзенә биргән вәкаләтне кулланып министрлар шурасын беренче тапкыр 19 нчы гыйнварда җитәкләгән иде.',
			$this->convertToCyrillic( 'Ministrlar şurası cıyılışı
Ministrlar şurası cıyılışı
Urnaştıru 09.03.2015 Yañartu 09.03.2015
A A
Cıyılışnı ilbaşı Räcäp Tayyip Ärdoğan citäkli
Ministrlar şurası ilbaşı idaräse sarayında cıyıldı.
Törkiyä ilbaşı Räcäp Tayyip Ärdoğan ikençe tapqır ministrlar şurasın citäkli.
Şurada il eçendä iminlek paketı, valyuta kursındağı xäräkätlelek, ildä terrornı beterü öçen alıp barlğan çişeleş barışı, xatın-qız problemaları , Süriyä häm Ğiraq çik buylarındağı täräqqiyätlär qarala.
Törkiyä ilbaşı Räcäp Tayyip Ärdoğan ilbaşı bulıp saylanğannan soñ Töp Qanunnıñ üzenä birgän wäqalätne qullanıp ministrlar şurasın berençe tapqır 19 nçı ğinwarda citäklägän ide.' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdb',
			$this->convertToCyrillic( '-{lj}-ab-{nj}-vg-{db}-' )
		);
		// A simple convertion of Cyrillic to Cyrillic
		$this->assertEquals( 'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади эшләү ысуллары да башка төрле, дибез.',
			$this->convertToCyrillic( 'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади эшләү ысуллары да башка төрле, дибез.' )
		);
		// Same as above, but assert that -{}-s must be removed and not converted
		$this->assertEquals( 'ljабnjвгdaž',
			$this->convertToCyrillic( '-{lj}-аб-{nj}-вг-{da}-ž' )
		);
	}

	/**
	 * @covers LanguageConverter::convertTo
	 */
	public function testConversionToLatin() {
		// A convertion of Cyrillic to Latin. Mixed case abbreviations
		$this->assertEquals( <<<'EOD'
KamAZ YeRE Ye A Yu
EOD
,
			$this->convertToLatin( <<<'EOD'
КамАЗ ЕРЭ Е А Ю
EOD
)
		);
		// A simple convertion of Latin to Latin
		$this->assertEquals( 'Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.',
			$this->convertToLatin( 'Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( 'Elek yazuçılar awıllarğa yış kilä häm härwaqıt xalıq arasında bula ide. Monnan yegerme-utız yıl elek rayon, awıl cirlegendä eşläp kilüçe ädäbi berläşmälär belän härwaqıt tığız elemtä buldı. Elek bolay ide, tegeläy ide, digän fikerlärne işetkäç, awız çite belän genä yılmayıp quyarğa yaratabız. Bügen zaman ikençe, şuña kürä icadi eşläw ısulları da başqa törle, dibez.
aqyeget ir-yeget atel\'ye музее .',
			$this->convertToLatin( 'Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади эшләү ысуллары да башка төрле, дибез.
акъегет ир-егет ателье -{музее}- .' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
vagon waq ğayät tayaq ğäyär ğäyäre ğäyep ğäyepläde ğäyebe ğäyere çäye bayı gäp güläp göber goa gölbaqça
 gitara gül gel ügetläw qäber qäleb *qayıq qayıq qayınsar qayış aqayıp tuqayıbız qayıru bayıp sağayıp
 qarağayım qoyırıq qıyın quyın tuyın töyen köyenü qayum ayu tayu qoyu oyu qıyu tıyu tuyu çöyü kiü tiü
 çekeräyü keçkenäyü şikär qayan ğayan quyan qıyaq qoyaş toya oya taraya bäyän keçkenäyä köyä çiä kiä iä
 biä tiä güyä riya riyağa riyasız qawın ğär ğäcäp ğalim [gaz]gaz go goğa goda gobi ğıylem ğömer ğömär
 ğöref gorilla ğömum ğömumän ğömumi gol golsız golğa ğa gäp gramm ğarıq tağu ağu qağılu tugrik tuğrı
 bağın buğın çuğın uğrı uğlan iğlan tuğlan bağlan bağ brig şpig signal var'ag
 dog şpaga vigvam zigzag ğıyraq qatğıy baqıy ğırıldaw yeget yefäk yıraq yolka
 qäläm qäleb qabil kamil kümer aq qatıq katoktağı [kamağa]kamağa [kukmarağa]kukmarağa tsetner şçotka
 şçotka sçotqa sçotqa yüeş yükä yüri yün yuan yäräş yäki yäş yäşläre yäşlek yan awır [avatar]avatar
 revolütsiälär revolyutsiyalar evolyutsiyağa evolütsiägä ambitsiägä ambitsiyağa qawiä rawiä
 möxämmädiä avariya [avia]avia psixologiä geologiä fiziologiä iä biä yuliä yuliälär
 yuliyalar siü siüne siügä xakimiät mädäniät fizika
 bie ekologqa vo tovarğa ovallarğa volga volgağa qorbanov yäşäw eşläw buyınça
 vyet yum'ya feya tä'sir ma'may breyk krek koen mäs'älän qör'än dönya xikäyä
 yaqlar-töbäklär lel'vij yanil yuğarı qomar çoqırça plaksixa uqıtuçılıq el'vira kirpeç
EOD
,
			$this->convertToLatin( <<<'EOD'
вагон вак гаять таяк гаярь гаяре гаеп гаепләде гаебе гаере чәе бае гәп гүләп гөбер гоа гөлбакча
 гитара гүл гел үгетләү кабер калеб *кайык каек каенсар каеш акаеп тукаебыз каеру баеп сагаеп
 карагаем коерык кыен куен туен төен көенү каюм аю таю кою ою кыю тыю тую чөю кию тию
 чекерәю кечкенәю шикәр каян гаян куян кыяк кояш тоя оя тарая бәян кечкенәя көя чия кия ия
 бия тия гүя рия рияга риясыз кавын гарь гаҗәп галим [газ]газ го гога года гоби гыйлем гомер гомәр
 гореф горилла гомум гомумән гомуми гол голсыз голга га гәп грамм гарык тагу агу кагылу тугрик тугъры
 багын бугын чугын угъры угълан игълан туглан баглан баг бриг шпиг сигнал варяг
 дог шпага вигвам зигзаг гыйрак катгый бакый гырылдау егет ефәк ерак ёлка
 каләм калеб кабил камил күмер ак катык катоктагы [камага]камага [кукмарага]кукмарага цетнер щетка
 щётка счетка счётка юеш юкә юри юнь юан ярәш яки яшь яшьләре яшьлек ян авыр [аватар]аватар
 революцияләр революциялар эволюцияга эволюциягә амбициягә амбицияга кавия равия
 мөхәммәдия авария [авиа]авиа психология геология физиология ия бия юлия юлияләр
 юлиялар сию сиюне сиюгә хакимият мәдәният физика
 бие экологка во товарга овалларга волга волгага корбанов яшәү эшләү буенча
 вьет юмья фея тәэсир маэмай брэйк крэк коэн мәсьәлән коръән дөнья хикәя
 яклар-төбәкләр лельвиж янил югары комар чокырча плаксиха укытучылык эльвира кирпеч
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
ximiä astronomiä matematika noqrat kizner qunaq Kukmara ezläw ayıq qıyıq aq uyıq
yaq çöyek oyıq belek çiläk çiläge ayığı qanığu <span>yıq</span> yıq yığu bik qoyı qoyığa
 qänäğät qänäğäte qänäğätkä <span>un  un</span> urta balıqlı balıq xoquq cämğıyät cämğıyäte
 cämğıyätkä cämğısı cämğese
 taqıya robağıy täräqqiyät täräqqiyäte mäğlümat mäğlümati täräqqiyäti
 täräqqiyäwi täräqqiy täräqqie täräqqiya <span>täräqqiya</span>
 qaya aqaya sänäğät sänğät ua qua uıp quıp yuan yuın çuyın quyın muyın ürtäläw
 ürtä qıyldı qıldı çıyıldap möstäqil ixtilaf ixtıylaf qıyın cıyın bıyıl iqtisadıy tıydı tıy cinayät
 cıyma barıyq qılıyq qıylıyq kürik söylik qıysa fağilät şıyğıy şiğıy fatıyma camıyaq çäynek çinayaq
 çınayaq fiğel ğilfan şiğer şifır şifr şofyor şofer misır latıyf rasıyx qıyssa ğıysyan ğıylem
 ğiffät şağir bäğer bagira tagil vrungel' qurıq tuqran çuqraq uqu aqsu boyıq
 uyın yörik yäşik tarıyq tarıyq tarik tariq bäliğ
 säğät yäm säyäsät ***** säyäsi sä­ya­si ***** löğät bäha bäla bälase elektriçkada elektriçkağa
 kuşket kurkino kuzmes' yum'ya siner'
EOD
,
			$this->convertToLatin( <<<'EOD'
химия астрономия математика нократ кизнер кунак Кукмара эзләү аек кыек ак уек
як чөек оек белек чиләк чиләге аегы каныгу <span>ек</span> ек егу бик кое коега
 канәгать канәгате канәгатькә <span>ун  ун</span> урта балыклы балык хокук җәмгыять җәмгыяте
 җәмгыятькә җәмгысы җәмгысе
 такыя робагый тәрәккыять тәрәккыяте мәгълүмат мәгълүмати тәрәккыяти
 тәрәккыяви тәрәккый тәрәккые тәрәккыя <span>тәрәккыя</span>
 кая акая сәнәгать сәнгать уа куа уып куып юан юын чуен куен муен үртәләү
 үртә кыйлды кылды чыелдап мөстәкыйль ихтилаф ихтыйлаф кыен җыен быел икътисадый тыйды тый җинаять
 җыйма барыйк кылыйк кыйлыйк күрик сөйлик кыйса фагыйләт шыйгый шигый фатыйма җамыяк чәйнек чинаяк
 чынаяк фигыль гыйльфан шигырь шифыр шифр шофёр шофер мисыр латыйф расыйх кыйсса гыйсъян гыйлем
 гыйффәт шагыйрь бәгырь багира тагил врунгель курык тукран чукрак уку аксу боек
 уен йөрик яшик тарыйк тарыйкъ тарик тарикъ бәлигъ
 сәгать ямь сәясәт ***** сәяси сә­я­си ***** лөгать бәһа бәла бәласе электричкада электричкага
 кушкет куркино кузмесь юмья синерь
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
karyer lyugdon kompyuter kommunal' fizika konstruktor buşuyev
 festival' munitsipal' näğıymov wäliev stantsiäse şow bowling mawgli awu aw awsız ayawsızğa ayaw Yün
 imamievağa qıyamovağa kienüe uquı Uına qua iü taswirlaw ä [qarta]qarta vizit Biektaw
 yuq disklarım minskiğa arttağı anttağı asttağı qayber iägä iä çiä çiyä
 vlast' qapqın karawatqa ğıynwar başqortqa qort AQŞ ssılka yäki yaq mäğarif krasivıy
 vrağ vse vzrıw bawlı änwär tramvay awıl ştutğard veps qart aqt
 variant versiä noyaberdän mäşäqät ğataullin yagfarovağa yağafarovağa yağfarovağa yagfar
 yağafar yağfar ğäyetläre ğäyet qör'än muzıka nihayät telefonğa web YuXİDİ taswir
 töbäkara pauza möğayen möğayen mölayem [poyezd]poyezd yaqupov ğıylemxanovqa ğaynanov tä'essorat
 riza'etdin niyazğa äxmätwälievağa sadrievqa vergazovqa qorbanovqa soltanğalievqa miñnebayeva
 qotluğ blog yasaw [kazbek]kazbek yubileyındağı aqyeget nikax sufiyan Yekaterina aqqoş ye Ye
 [zakaz]zakaz qotoçqıç [ukaz]ukaz ministrlığında yazarğa yomğaqlayaçaq Rusiä
EOD
,
			$this->convertToLatin( <<<'EOD'
карьер люгдон компьютер коммуналь физика конструктор бушуев
 фестиваль муниципаль нәгыймов вәлиев станциясе шоу боулинг маугли аву ау аусыз аяусызга аяу Юнь
 имамиевага кыямовага киенүе укуы Уына куа ию тасвирлау ә [карта]карта визит Биектау
 юк дискларым минскига арттагы анттагы асттагы кайбер иягә ия чия чийә
 власть капкын караватка гыйнвар башкортка корт АКШ ссылка яки як мәгариф красивый
 враг все взрыв бавлы әнвәр трамвай авыл штутгард вепс карт акт
 вариант версия ноябрьдән мәшәкать гатауллин ягфаровага ягафаровага ягъфаровага ягфар
 ягафар ягъфар гаетләре гает коръән музыка ниһаять телефонга веб ЮХИДИ тасвир
 төбәкара пауза мөгаен мөгаен мөлаем [поезд]поезд якупов гыйлемхановка гайнанов тәэссорат
 ризаэтдин ниязга әхмәтвәлиевага садриевка вергазовка корбановка солтангалиевка миңнебаева
 котлугъ блог ясау [казбек]казбек юбилеендагы акъегет никях суфиян Екатерина аккош е Е
 [заказ]заказ коточкыч [указ]указ министрлыгында язарга йомгаклаячак Русия
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
[ligası]ligası wasiyät wasiyäte wasiyäwi ğömer Änqara ğöref cämğıyät ğöşer ğöläma gobelen Gogen
 Gomel' gomeopatiä gomeostaz telegramma yıraq yılğa grammğa grafqa grafiktağı Ğali
 Naciä iä cinayät Mölekov wäzğıyät muzeyında dekaber
Bu bitneñ latinçasına sıltama tänqit qıyl qıylğan baqıy täräqqiya qıya ğağauz
 ğağauzça Äxmätşin Şina nijneqamskşina akhmetshin
EOD
,
			$this->convertToLatin( <<<'EOD'
[лигасы]лигасы васыять васыяте васыяви гомер Әнкара гореф җәмгыять гошер голәма гобелен Гоген
 Гомель гомеопатия гомеостаз телеграмма ерак елга граммга графка графиктагы Гали
 Наҗия ия җинаять Мөлеков вәзгыять музеенда декабрь
Бу битнең латинчасына сылтама тәнкыйть кыйл кыйлган бакый тәрәккыя кыя гагауз
 гагаузча Әхмәтшин Шина нижнекамскшина akhmetshin
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
Gömbälär (lat. Fungi yäki Mycota) — üsemleklärneñ häm xaywannarnıñ qayber sıyfatların berläştergän eukariot orğanizmnardan torğan möstäqil patşalıq.

Gömbälär patşalığın mikologiä öyränä. Elek gömbälärne üsemleklärgä kertkängä mikologiä xäzer dä biologiäneñ bülege dip sanala.

Gömbälär ğayät küptörlelek belän ayırılıp toralar. Böten ekologik sistemalarda da gömbälär ayırılğısız elementlar. Cir şarında törle tikşerülär buyınça 100 meñnän 250 meñgä yaqın, ä qayber sanawlar buyınça 1,5 millionnan artıq gömbälär töre bar.

Gömbälär — geterotroflar, yağni alarnıñ fotosintez protsessın tä'min itüçe pigmentı — xlorofilı yuq.

Gömbälärneñ qayber sıyfatları xaywannar belän urtaq: mäsälän,

matdälär almaşı produktları arasında moçevina bulu;

küzänäk tışçasında (buıntığayaqlılarnıñ tän yapmasındağı kebek) kümersular — xitin;

zapas produkt (üsemleklärdäge kebek kraxmal tügel) glikogen bulu.

Ä qayber başqa sıyfatları belän isä alar üsemleklärgä yaqın: mäsälän, alarnıñ tuqlanu ısulı (azıqnı yotıp tügel, ä suırıp tuqlanalar), çiklänmägän üsüe häm xäräkätlänmäwe.
EOD
,
			$this->convertToLatin( <<<'EOD'
Гөмбәләр (лат. Fungi яки Mycota) — үсемлекләрнең һәм хайваннарның кайбер сыйфатларын берләштергән эукариот организмнардан торган мөстәкыйль патшалык.

Гөмбәләр патшалыгын микология өйрәнә. Элек гөмбәләрне үсемлекләргә керткәнгә микология хәзер дә биологиянең бүлеге дип санала.

Гөмбәләр гаять күптөрлелек белән аерылып торалар. Бөтен экологик системаларда да гөмбәләр аерылгысыз элементлар. Җир шарында төрле тикшерүләр буенча 100 меңнән 250 меңгә якын, ә кайбер санаулар буенча 1,5 миллионнан артык гөмбәләр төре бар.

Гөмбәләр — гетеротрофлар, ягъни аларның фотосинтез процессын тәэмин итүче пигменты — хлорофилы юк.

Гөмбәләрнең кайбер сыйфатлары хайваннар белән уртак: мәсәлән,

матдәләр алмашы продуктлары арасында мочевина булу;

күзәнәк тышчасында (буынтыгаяклыларның тән япмасындагы кебек) күмерсулар — хитин;

запас продукт (үсемлекләрдәге кебек крахмал түгел) гликоген булу.

Ә кайбер башка сыйфатлары белән исә алар үсемлекләргә якын: мәсәлән, аларның туклану ысулы (азыкны йотып түгел, ә суырып тукланалар), чикләнмәгән үсүе һәм хәрәкәтләнмәве.
EOD
)
		);
		/*// A convertion of Cyrillic to Latin. Some letters capital
		// i comment this out because style of writing all with upper case while
		// it is not an abbreviation is probably not used in wikipedia
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
		$this->assertEquals( <<<'EOD'
 QIYAMOVA YÜN YIRAQ YUAN ĞÄYÄR ĞÄR CÄYÄ İMAMİEVA TAQIYA EVOL'UTSİYAĞA EVOLÜTSİÄGÄ EVOLÜTSİÄ
 YUM'YA YUMYA FEYA QIYA QIYASI KİENÜE DÖNYA UQUI QUA
EOD
,
			$this->convertToLatin( <<<'EOD'
 КЫЯМОВА ЮНЬ ЕРАК ЮАН ГАЯРЬ ГАРЬ ҖӘЯ ИМАМИЕВА ТАКЫЯ ЭВОЛЮЦИЯГА ЭВОЛЮЦИЯГӘ ЭВОЛЮЦИЯ
 ЮМЬЯ ЮМЪЯ ФЕЯ КЫЯ КЫЯСЫ КИЕНҮЕ ДӨНЬЯ УКУЫ КУА
EOD
)
		);*/
/*		// A convertion of Cyrillic to Latin; hypothetical words and russian
		// words that may appear by new usage, or by some mistake, for example,
		// in place names without -{ }- markers
		// this test is commented out because it is not passed by converter for now
		$this->assertEquals( <<<'EOD'
 * revol'utsiyu *siyuga *revol'utsiye *revol'utsieda *revol'utsiedä *biegä *bieda * nail'a * t'uk
 * v'yuk * utyos *ut'yos *utyos bel'ya *belya * bel'a * bel'yu *belyu * bel'u * bel'ye *belye *bele
 * bel'yo *belyo *belyo * feye * feyu *feyo * fei * izyan *iz'yan *iz'an *iz'-an *iz-an *izan
 kukmorskiyğa * kukmor'ane * soverşayet * postits'a * postit's'a * v * knige vrag goden
 tigra tigr aktsiz küäm Yolqa YOLka Yo YeRE *baqçä *kiyenüwe *bvg *bv *abv
 *tuğrı *ğu *göa *uğrı *uğlan *qıyraq * kompyuter kamennıy kl'uç TYaG yomğaqlayaçaq
 *cämğıyätkä *cämğıyatı *cämğınät *cämğıyu *cämğıyuda *cämğıyüdä *cämğıyüt *qänäğätkä * yojikovıy
 * yoj * pervıy *camğıyu *täräqqiyatı kor'aga drug drugqa poçinok kuçuk Axmetşin
 *täräqqinät *täräqqiynät *täräqqiyät *täräqqine *täräqqinä *täräqqini *täräqqin' *täräqqin
 *taraqqıya cämğıya *camğıya *äqıya *iqıya *ğätqıya *äqaya *sänagät ogo pervıy kukmorskiy
 * yıqıya *ÄQIYA *ıqıya *ğatqıya *nqıya *äğıya *iğıya *ğätğıya *yığıya *ağıya *ığıya *ğatğıya *nğıya
 *yöreyk *yäşiyk *yäşiyk *ayk şeyk *tuyk *böyk *säğa * kukmor'ane * vmeste * kamennıy
 * veduşçem * vedu * djannatu * djannatov * v * kabardino kabardinov * qawardino * balkarii
 * uniçtojen * uniçtojiv * yanvar'a * predlagayem * kaçestvennıye * şemordan * luçşeye * pervoye
 * kompyuter * interesuyet * odejdı * priglaşayem * verxniy *äÄ *ÄTSÄ yuN' *ÄFEYO *ÄYOJZ
 * AYeA! * YeA! * Yea * yeA * AYe!! * aYe!! * Ayı * AYea * AyıA * Ayıa * aYeA! * aYea * ayıA * ayıa
 * kiyäwe * riyaw *üendä *uquwı *üä *üe *KLYO *KL'Yo fevğa yevğa fovğa fofovğa
 *koldun *kolwun *tänqıy *tänğıy *tänğıya *gore [*wazğıyät]*wazğıyät yadk'are
EOD
,
			$this->convertToLatin( <<<'EOD'
 * революцию *сиюга *революцие *революциеда *революциедә *биегә *биеда * наиля * тюк
 * вьюк * утёс *утьёс *утъёс белья *белъя * беля * белью *белъю * белю * белье *белъе *беле
 * бельё *белъё *белё * фее * фею *феё * феи * изъян *изьян *изян *изьан *изъан *изан
 кукморскийга * кукморяне * совершает * постится * поститься * в * книге враг годен
 тигра тигр акциз күәм Ёлка ЁЛка Ё ЕРЭ *бакчә *кийенүве *бвг *бв *абв
 *тугры *гу *гөа *угры *углан *кыйрак * компъютер каменный ключ ТЯГ йомгаклaячак
 *җәмгыяткә *җәмгыяты *җәмгынәт *җәмгыю *җәмгыюда *җәмгыюдә *җәмгыють *канәгаткә * ёжиковый
 * ёж * первый *җамгыю *тәрәккыяты коряга друг другка починок кучук Ахметшин
 *тәрәккынәт *тәрәккыйнәт *тәрәккыйәт *тәрәккыне *тәрәккынә *тәрәккыни *тәрәккынь *тәрәккын
 *тараккыя җәмгыя *җамгыя *әкыя *икыя *гатькыя *әкая *сәнагәть ого первый кукморский
 * екыя *ӘКЫЯ *ыкыя *гаткыя *нкыя *әгыя *игыя *гатьгыя *егыя *агыя *ыгыя *гатгыя *нгыя
 *йөрейк *яшийк *йәшийк *айк шейк *туйк *бөйк *сәга * кукморяне * вместе * каменный
 * ведущем * веду * джаннату * джаннатов * в * кабардино кабардинов * кавардино * балкарии
 * уничтожен * уничтожив * января * предлагаем * качественные * шемордан * лучшее * первое
 * интересует * одежды * приглашаем * верхний *әӘ *ӘЦӘ юНЬ *ӘФЕЁ *ӘЁЖЗ
 * АЕА! * ЕА! * Еа * еА * АЕ!! * аЕ!! * Ае * АЕа * АеА * Аеа * аЕА! * аЕа * аеА * аеа
 * кийәве * рийав *Үендә *укувы *үә *үе *КЛЁ *КЛЬЁ февга евга фовга фофовга
 *колдун *колвун *тәнкый *тәнгый *тәнгыя *горе [*вазгыять]*вазгыять ядкяре
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
		$this->assertConverted( $text, 'tt-latn', $msg );
	}

	/**
	 * Verifiy the given Latin text is not converted when using
	 * using the Latin variant and converted to Cyrillic when using
	 * the Cyrillic variant.
	 * @param string $text Text to convert
	 * @param string $msg Optional message
	 */
	protected function assertLatin( $text, $msg = '' ) {
		$this->assertUnConverted( $text, 'tt-latn', $msg );
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
		return $this->convertTo( $text, 'tt-latn' );
	}
}
