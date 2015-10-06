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
		$this->assertEquals( '<p>Төркия 
</p><p>Төркия телерадиокомпаниясенең рәсми веб сайты. Бу сәхифәдә "Anadolu" агентлыгы (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" агентлыкларының автор хокукларына кергән материаллары урнашкан. Материаллар һәм хәбәрләр рөхсәтсез кулланылмас, копияланмас. ТРТ тышкы бәйләнешле сайтларның эчтәлегеннән җаваплы түгел.
</p><p><br />
</p><p>Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк:"Төркия белән Көньяк Корея арасындагы мөнәсәбәтләр стратегик уртаклык дәрәҗәсендә"
Төркия Бөек Милләт Мәҗлесе башлыгы Җәмил Чичәк Көньяк Корея –Төркия дуслык төркеме башлыгы Hankoo Lee һәм янындагы вәкиллекне кабул итте.
</p><p><br />
Җәмил Чичәк очрашуда ике илнең географик яктан бер-берсеннән бик ерак булса да мөнәсәбәтләр җәһәтеннән якын булуын белдерде.
</p><p><br />
Төркия белән Көньяк Корея арасындагы мөнәсәбәтләрнең стратегик уртаклык дәрәҗәсендә булуына басым ясаучы Җәмил Чичәк “2017 нче йлда мөнәсәбәтләребезнең 60 еллыгын билгеләп үтәчәкбез. Бу исә элемтәләрне тагын камилләштерәчәк һәм халыкларны да үзара якынлаштырачак”диде.
</p>',
			$this->convertToCyrillic( '<p>Törkiä 
</p><p>Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "-{-{Anadolu}-}-" agentlığı (-{AA}-), "-{Agence France-Presse}-" (-{AFP}-), "-{Associated Press}-" (-{AP}-), "-{Reuters}-", "-{Deutsche Press Agentur}-" (-{DPA}-), -{ATSH}-, -{EFE}-, -{MENA}-, -{ITAR TASS}-, "-{XINHUA}-" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.
</p><p><br />
</p><p>Törkiya Böyek Millät Mäclese başlığı Cämil Çiçäk:"Törkiyä belän Könyaq Koreya arasındağı mönäsäbätlär strategik urtaqlıq däräcäsendä"
Törkiya Böyek Millät Mäclese başlığı Cämil Çiçäk Könyaq Koreya –Törkiyä duslıq törkeme başlığı -{Hankoo Lee}- häm yanındağı wäkillekne qabul itte.
</p><p><br />
Cämil Çiçäk oçraşuda ike ilneñ geografik yaqtan ber-bersennän bik yıraq bulsa da mönäsäbätlär cähätennän yaqın buluın belderde.
</p><p><br />
Törkiyä belän Könyaq Koreya arasındağı mönäsäbätlärneñ strategik urtaqlıq däräcäsendä buluına basım yasawçı Cämil Çiçäk “2017 nçe ylda mönäsäbätlärebezneñ 60 yıllığın bilgeläp ütäçäkbez. Bu isä elemtälärne tağın kamilläşteräçäk häm xalıqlarnı da üzara yaqınlaştıraçaq”dide.
</p>' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( '<p>“Гадәләт һәм калкыну” партиясеннән халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алганнан соң Фидан кабат милли күзләү оешмасы киңәшчесе итеп билгеләнде
</p><p>Һакан Фидан “Гадәләт һәм калкыну” партиясеннән халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алды.
</p><p><br />
Бу сәбәпле Фидан премьер-министр Әхмәт Давытоглу тарафыннан элекке эше булган милли күзләү оешмасы киңәшчесе вазыйфасына кабат билгеләнде.
</p><p><br />
Премьер-министр урынбасары һәм хөкүмәт сүзчесе Бүләнт Арынч министрлар шурасыннан соң үткәрелгән матбугат очрашуында “Премьер-министр Һакан Фиданны кабат милли күзләү оешмасы киңәшчесе итеп билгеләде” диде.
</p><p><br />
Һакан Фидан халык ышанычлысы кандидат намзәтлеге мөрәҗәгатен кире алганнан соң болай дип белдерү ясады:” Илемә һәм милләтемә хезмәт итү юлында , бүгенге көнгә кадәр булганы кебек моннан соң да үземә бирелгән вазыйфаны җиренә җиткереп башкару өчен тырышачакмын. Миңа яклау күрсәткән һәм ышанган илбашыбыз һәм премьер-министрга , газиз халкыма рәхмәтләремне җиткерәм”. 
</p>',
			$this->convertToCyrillic( '<p>“Ğadälät häm qalqınu” partiyäsennän xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire alğannan soñ Fidan qabat milli küzläw oyışması kiñäşçese itep bilgelände
</p><p>Hakan Fidan “Ğadälät häm qalqınu” partiyäsennän xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire aldı.
</p><p><br />
Bu säbäple Fidan premyer-ministr Äxmät Dawıtoğlu tarafınnan elekke eşe bulğan milli küzläw oyışması kiñäşçese wazıyfasına qabat bilgelände.
</p><p><br />
Premyer-ministr urınbasarı häm xökümät süzçese Bülänt Arınç ministrlar şurasınnan soñ ütkärelgän matbuğat oçraşuında “Premyer-ministr Hakan Fidannı qabat milli küzläw oyışması kiñäşçese itep bilgeläde” dide.
</p><p><br />
Hakan Fidan xalıq ışanıçlısı kandidat namzätlege möräcäğäten kire alğannan soñ bolay dip belderü yasadı:” İlemä häm millätemä xezmät itü yulında , bügenge köngä qadär bulğanı kebek monnan soñ da üzemä birelgän wazıyfanı cirenä citkerep başqaru öçen tırışaçaqmın. Miña yaqlaw kürsätkän häm ışanğan ilbaşıbız häm premyer-ministrğa , ğaziz xalqıma räxmätläremne citkeräm”. 
</p>' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( '<p>Төркиянең көньяк-көнчыгышында үсеш планы
Урнаштыру 09.03.2015 Яңарту 10.03.2015
А А
Төркия Премьер-министры Әхмәт Давытоглу Көньяк-көнчыгыш Анадолу проектының (GAP) гамәл планын ачыклады.
Давытоглу яңарыш яклы, иктисади һәм иҗтимагый үсешне тизләтүче, эш белән тәэмин итүне арттыручы, җитештерүчәнлеккә юнәлгән, һуманитар калкыну-үсешне кызыксындыручы, тәшвик итүче яңа сәясәт алып барачакларын белдерде.
</p><p>Гамәл планының 5 төп өлкәдән торуын ассызыклаучы Давытоглу бу планга 2018нче ел ахырына хәтле үзәк идарә бюджетыннан якынча 27 миллиард лира күләмендә инвестиция аерылып куелачагын әйтте.
</p><p>План нәтиҗәсендә GAP Көньяк-көнчыгыш Анадолу проекты кысаларында туплам 1 миллион 100 мең гектар мәйданның сугарылуы өчен саклагыч аскормаларының тәмамланачагын, су челтәре төзелешенең дә мөһим күләмдә бетереләчәгенә игътибарны юнәлтүче Премьер-министр натурал игенчелек белән шөгыйлләнүче игенчеләрнең салымнарына финанс ярдәм күрсәтеләчәген белдерде.
</p><p>"Гамәл планы” азагы булган 2018нче елда эшсезлек күләмен киметүда тәвәккәлбез”, - дип әйтүче Давытоглу төбәктәге чик буе капкаларын да көчәйтәчәкләрен ассызыклап әйтеп узды.
</p><p>Давытоглу мәгариф, туризм һәм сәламәтлек саклау өлкәсенда дә мөһим адымнар ясалачагын әйтте.
</p>',
			$this->convertToCyrillic( '<p>Törkiyäneñ könyaq-könçığışında üseş planı
Urnaştıru 09.03.2015 Yañartu 10.03.2015
A A
Törkiyä Premyer-ministrı Äxmät Dawıtoğlu Könyaq-könçığış Anadolu proyektınıñ (-{GAP}-) ğämäl planın açıqladı.
Dawıtoğlu yañarış yaqlı, iqtisadi häm ictimaği üseşne tizlätüçe, eş belän tä’min itüne arttıruçı, citeşterüçänlekkä yünälgän, humanitar qalqınu-üseşne qızıqsındıruçı, täşviq itüçe yaña säyäsät alıp baraçaqların belderde.
</p><p>Ğämäl planınıñ 5 töp ölkädän toruın assızıqlawçı Dawıtoğlu bu planğa 2018nçe yıl axırına xätle üzäk idarä byudjetınnan yaqınça 27 milliard lira külämendä investitsiya ayırılıp quyılaçağın äytte.
</p><p>Plan näticäsendä -{GAP}- Könyaq-könçığış Anadolu proyektı qısalarında tuplam 1 million 100 meñ gektar mäydannıñ suğarıluı öçen saqlağıç asqormalarınıñ tämamlanaçağın, su çeltäre tözeleşeneñ dä möhim külämdä betereläçägenä iğtibarnı yünältüçe Premyer-ministr natural igençelek belän şöğillänüçe igençelärneñ salımnarına finans yärdäm kürsäteläçägen belderde.
</p><p>"Ğämäl planı” azağı bulğan 2018nçe yılda eşsezlek külämen kimetüda täwäkkälbez”, - dip äytüçe Dawıtoğlu töbäktäge çik buyı qapqaların da köçäytäçäklären assızıqlap äytep uzdı.
</p><p>Dawıtoğlu mäğärif, turizm häm sälamätlek saqlaw ölkäsenda dä möhim adımnar yasalaçağın äytte.
</p>' )
		);
		// A convertion of Latin to Cyrillic
		$this->assertEquals( '<p>Министрлар шурасы җыелышы
Министрлар шурасы җыелышы
Урнаштыру 09.03.2015 Яңарту 09.03.2015
А А
Җыелышны илбашы Рәҗәп Таййип Әрдоган җитәкли
Министрлар шурасы илбашы идарәсе сараенда җыелды.
</p><p><br />
Төркия илбашы Рәҗәп Таййип Әрдоган икенче тапкыр министрлар шурасын җитәкли.
</p><p><br />
Шурада ил эчендә иминлек пакеты, валюта курсындагы хәрәкәтлелек, илдә террорны бетерү өчен алып барлган чишелеш барышы, хатын-кыз проблемалары , Сүрия һәм Гыйрак чик буйларындагы тәрәккыятьләр карала.
</p><p><br />
Төркия илбашы Рәҗәп Таййип Әрдоган илбашы булып сайланганнан соң Төп Канунның үзенә биргән вәкаләтне кулланып министрлар шурасын беренче тапкыр 19 нчы гыйнварда җитәкләгән иде.
</p>',
			$this->convertToCyrillic( '<p>Ministrlar şurası cıyılışı
Ministrlar şurası cıyılışı
Urnaştıru 09.03.2015 Yañartu 09.03.2015
A A
Cıyılışnı ilbaşı Räcäp Tayyip Ärdoğan citäkli
Ministrlar şurası ilbaşı idaräse sarayında cıyıldı.
</p><p><br />
Törkiyä ilbaşı Räcäp Tayyip Ärdoğan ikençe tapqır ministrlar şurasın citäkli.
</p><p><br />
Şurada il eçendä iminlek paketı, valyuta kursındağı xäräkätlelek, ildä terrornı beterü öçen alıp barlğan çişeleş barışı, xatın-qız problemaları , Süriyä häm Ğiraq çik buylarındağı täräqqiyätlär qarala.
</p><p><br />
Törkiyä ilbaşı Räcäp Tayyip Ärdoğan ilbaşı bulıp saylanğannan soñ Töp Qanunnıñ üzenä birgän wäqalätne qullanıp ministrlar şurasın berençe tapqır 19 nçı ğinwarda citäklägän ide.
</p>' )
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
		// A simple convertion of Latin to Latin
		$this->assertEquals( 'Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.',
			$this->convertToLatin( 'Törkiyä teleradiokompaniyäseneñ räsmi web saytı. Bu säxifädä "Anadolu" agentlığı (AA), "Agence France-Presse" (AFP), "Associated Press" (AP), "Reuters", "Deutsche Press Agentur" (DPA), ATSH, EFE, MENA, ITAR TASS, "XINHUA" agentlıqlarınıñ avtor xoquqlarına kergän materialları urnaşqan. Materiallar häm xäbärlär röxsätsez qullanılmas, kopialanmas. TRT tışqı bäyläneşle saytlarnıñ eçtälegennän cawaplı tügel.' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( '<p>Elek yazuçılar awıllarğa yış kilə həm hərwaqıt xalıq arasında bula ide. Monnan yegerme-utız yıl elek rayon, awıl cirlegendə eşləp kilüçe ədəbi berləşmələr belən hərwaqıt tığız elemtə buldı. Elek bolay ide, tegeləy ide, digən fikerlərne işetkəç, awız çite belən genə yılmayıp quyarğa yaratabız. Bügen zaman ikençe, şuña kürə icadi eşləw ısulları da başqa tɵrle, dibez.
</p><p>aqyeget ir-yeget atel\'ye
</p>',
			$this->convertToLatin( '<p>Элек язучылар авылларга еш килә һәм һәрвакыт халык арасында була иде. Моннан егерме-утыз ел элек район, авыл җирлегендә эшләп килүче әдәби берләшмәләр белән һәрвакыт тыгыз элемтә булды. Элек болай иде, тегеләй иде, дигән фикерләрне ишеткәч, авыз чите белән генә елмаеп куярга яратабыз. Бүген заман икенче, шуңа күрә иҗади эшләү ысуллары да башка төрле, дибез.
</p><p>акъегет ир-егет ателье
</p>' )
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
vagon waq ğayət tayaq ğəyər ğəyəre ğəyep ğəyepləde ğəyebe ğəyere çəye bayı gəp güləp gɵber goa gɵlbaqça
 gitara gül gel ügetləw qəber qəleb *qayıq qayıq qayınsar qayış aqayıp tuqayıbız qayıru bayıp sağayıp
 qarağayım qoyırıq qıyın quyın tuyın tɵyen kɵyenü qayum ayu tayu qoyu oyu qıyu tıyu tuyu çɵyü kiyü tiyü
 çekerəyü keçkenəyü şikər qayan ğayan quyan qıyaq qoyaş toya oya taraya bəyən keçkenəyə kɵyə çiə kiə iə
 biyə tiyə güyə riya riyağa riyasız qawın ğər ğəcəp ğalim [gaz]gaz go goğa goda gobi ğıylem ğömer ğömər
 ğöref gorilla ğömum ğömumən ğömumi gol golsız golğa ğa gəp gramm ğarıq tağu ağu qağılu tugrik tuğrı
 bağın buğın çuğın uğrı uğlan iğlan tuğlan bağlan bağ brig şpig signal var'ag
 dog şpaga vigvam zigzag ğıyraq qatğıy baqıy ğırıldaw yeget yefək yıraq yolka
 qələm qəleb qabil kamil kümer aq qatıq katoktağı [kamağa]kamağa [kukmarağa]kukmarağa tsetner şçotka
 şçotka sçotqa sçotqa yüeş yükə yüri yün yuan yərəş yəki yəş yəşləre yəşlek yan awır [avatar]avatar
 revolütsiyələr revol'utsiyalar evol'utsiyağa evolütsiyəgə ambitsiyəgə ambitsiyağa qawiyə rawiyə
 mɵxəmmədiyə avariya [avia]avia psixologiyə geologiyə fiziologiyə iyə biyə yuliyə yuliyələr
 yuliyalar siyü siyüne siyügə xakimiyət mədəniyət fizika
 bie ekologqa vo tovarğa ovallarğa volga volgağa qorbanov yəşəw eşləw buyınça
 vyet yum'ya feya tə'sir ma'may breyk krek koen məs'ələn qör'ən dɵnya xikəyə
 yaqlar-tɵbəklər lel'vij yanil yuğarı qomar çoqırça plaksixa uqıtuçılıq el'vira kirpeç
<br /><br />
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
<br /><br />
EOD
)
		);
		
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
ximiyə astronomiyə matematika noqrat kizner qunaq Kukmara ezləw
<span>ayıq</span>
ayıq
<span>qıyıq</span>
qıyıq
<span>aq</span>
aq
<span>uyıq</span>
uyıq
yaq çɵyek oyıq belek çilək çiləge ayığı qanığu <span>yıq</span> yıq yığu bik qoyı qoyığa
 qənəğət qənəğəte qənəğətkə <span>un  un</span> urta balıqlı balıq xoquq cəmğıyət cəmğıyəte
 cəmğıyətkə cəmğısı cəmğese
 taqıya robağıy tərəqqiyət tərəqqiyəte məğlümat məğlümati tərəqqiyəti
 tərəqqiyəwi tərəqqiy tərəqqie tərəqqiya <span>tərəqqiya</span>
 qaya aqaya sənəğət sənğət ua qua uıp quıp yuan yuın çuyın quyın muyın ürtələw
 ürtə qıyldı qıldı çıyıldap mɵstəqil ixtilaf ixtıylaf qıyın cıyın bıyıl iqtisadıy tıydı tıy cinayət
 cıyma barıyq qılıyq qıylıyq kürik sɵylik qıysa fağilət şıyğıy şiğıy fatıyma camıyaq çəynek çinayaq
 çınayaq fiğel ğilfan şiğer şifır şifr şof'or şofer misır latıyf rasıyx qıyssa ğıysyan ğıylem
 ğiffət şağir bəğer bagira tagil vrungel' qurıq tuqran çuqraq uqu aqsu boyıq
 uyın yɵrik yəşik tarıyq tarıyq tarik tariq bəliğ
 səğət yəm səyəsət ***** səyəsi sə­ya­si ***** lɵğət bəha bəla bəlase elektriçkada elektriçkağa
 lel'vij kuşket kurkino kuzmes' yum'ya siner'
<br /><br />
EOD
,
			$this->convertToLatin( <<<'EOD'
химия астрономия математика нократ кизнер кунак Кукмара эзләү
<span>аек</span>
аек
<span>кыек</span>
кыек
<span>ак</span>
ак
<span>уек</span>
уек
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
 лельвиж кушкет куркино кузмесь юмья синерь
<br /><br />
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
karyer l'ugdon kompyuter kommunal' el'vira fizika konstruktor buşuyev
 festival' munitsipal' nəğıymov wəliev stantsiyəse şow bowling mawgli awu aw awsız ayawsızğa ayaw Yün
 imamievağa qıyamovağa kienüe uquı Uına qua iü
 yuq disklarım quqmorskiyğa minskiğa arttağı anttağı asttağı qayber iyəgə iyə çiyə çiyə
 vlast' qapqın karawatqa ğıynwar başqortqa qort AQŞ ssılqa yəki yaq məğarif krasivıy
 vrağ vse vzrıw bawlı ənwər tramvay awıl ştutğard veps qart aqt
 variant versiyə noyaberdən məşəqət ğata'ullin yagfarovağa yağafarovağa yağfarovağa yagfar
 yağafar yağfar ğəyetləre ğəyet qör'ən [muzıqa]muzıqa nihayət telefonğa web yüXİDİ taswir
 taswirlaw <span style="text-transform: uppercase;">ə</span> [qarta]qarta vizit Biektaw
 tɵbəkara pauza mɵğayen mɵğayen mɵlayem [poyezd]poyezd yaqupov ğıylemxanovqa ğaynanov tə'essorat
 riza'etdin niyazğa əxmətwəlievağa sadriyevqa vergazovqa qorbanovqa soltanğaliyevqa miñnebaeva
 qotluğ <a rel="nofollow" class="external text" href="http://example.com/">blog yasaw</a>
 [kazbek]kazbek yubileyındağı aqyeget nikax sufiyən
 [zakaz]zakaz qotoçqıç [ukaz]ukaz ministrlığında yazarğa yomğaqlayaçaq yomğaqlayaçaq Rusiyə
 Yekaterina aqqoş ye Ye
<br /><br />
EOD
,
			$this->convertToLatin( <<<'EOD'
карьер люгдон компьютер коммуналь эльвира физика конструктор бушуев
 фестиваль муниципаль нәгыймов вәлиев станциясе шоу боулинг маугли аву ау аусыз аяусызга аяу Юнь
 имамиевага кыямовага киенүе укуы Уына куа ию
 юк дискларым кукморскийга минскига арттагы анттагы асттагы кайбер иягә ия чия чийә
 власть капкын караватка гыйнвар башкортка корт АКШ ссылка яки як мәгариф красивый
 враг все взрыв бавлы әнвәр трамвай авыл штутгард вепс карт акт
 вариант версия ноябрьдән мәшәкать гатауллин ягфаровага ягафаровага ягъфаровага ягфар
 ягафар ягъфар гаетләре гает коръән [музыка]музыка ниһаять телефонга веб ЮХИДИ тасвир
 тасвирлау <span style="text-transform: uppercase;">ә</span> [карта]карта визит Биектау
 төбәкара пауза мөгаен мөгаен мөлаем [поезд]поезд якупов гыйлемхановка гайнанов тәэссорат
 ризаэтдин ниязга әхмәтвәлиевага садриевка вергазовка корбановка солтангалиевка миңнебаева
 котлугъ <a rel="nofollow" class="external text" href="http://example.com/">блог ясау</a>
 [казбек]казбек юбилеендагы акъегет никях суфиян
 [заказ]заказ коточкыч [указ]указ министрлыгында язарга йомгаклаячак йомгаклaячак Русия
 Екатерина аккош е Е
<br /><br />
EOD
)
		);
		// A convertion of Cyrillic to Latin
		$this->assertEquals( <<<'EOD'
[ligası]ligası wasiyət wasiyəte vasıyəwi ğömer Ənqara ğöref cəmğıyət ğöşer ğöləma gobelen Gogen
 Gomel' gomeopatiyə gomeostaz telegramma yıraq yılğa grammğa grafqa grafiktağı Ğali
 Naciyə iyə cinayət Mɵlekov wəzğıyət muzeyında
 <a rel="nofollow" class="external text" href="http://example.com/">Bu bitneñ latinçasına
 sıltama</a> tənqit qıyl qıylğan baqıy tərəqqiya qıya ğağauz
 ğağauzça Əxmətşin Axmetşin Şina nijneqamskşina akhmetshin
EOD
,
			$this->convertToLatin( <<<'EOD'
[лигасы]лигасы васыять васыяте васыяви гомер Әнкара гореф җәмгыять гошер голәма гобелен Гоген
 Гомель гомеопатия гомеостаз телеграмма ерак елга граммга графка графиктагы Гали
 Наҗия ия җинаять Мөлеков вәзгыять музеенда
 <a rel="nofollow" class="external text" href="http://example.com/">Бу битнең латинчасына
 сылтама</a> тәнкыйть кыйл кыйлган бакый тәрәккыя кыя гагауз
 гагаузча Әхмәтшин Ахметшин Шина нижнекамскшина akhmetshin
EOD
)
		);
/*		// A convertion of Cyrillic to Latin. All capitals and words with some letters capital
		$this->assertEquals( <<<'EOD'
 QIYAMOVA YÜN YIRAQ YUAN ĞƏYƏR ĞƏR CƏYƏ İMAMİYEVA TAQIYA EVOL'UTSİYAĞA EVOLÜTSİYƏGƏ EVOLÜTSİYƏ
 YUM'YA YUMYA FEYA QUAsı QUa QIYa QIYaSI QIYası kARAwATlar
 KİENÜE KİENÜEndə KİENÜendə UQUI UQUı UQUIn QUA
EOD
,
			$this->convertToLatin( <<<'EOD'
 КЫЯМОВА ЮНЬ ЕРАК ЮАН ГАЯРЬ ГАРЬ ҖӘЯ ИМАМИЕВА ТАКЫЯ ЭВОЛЮЦИЯГА ЭВОЛЮЦИЯГӘ ЭВОЛЮЦИЯ
 ЮМЬЯ ЮМЪЯ ФЕЯ КУАсы КУа КЫЯ КЫЯСЫ КЫЯсы кАРАвАТлар
 КИЕНҮЕ КИЕНҮЕндә КИЕНҮендә УКУЫ УКУы УКУЫн КУА
EOD
)
		);*/
/*		// A convertion of Cyrillic to Latin; hypothetical words and russian words that may appear by new usage, or by some mistake, for example, in place names without -{ }- markers
		$this->assertEquals( <<<'EOD'
 * revol'utsiyu *siyuga *revol'utsiye *revol'utsieda *revol'utsiedə *biegə *bieda * nail'a * t'uk
 * v'yuk * ut'os *ut'yos *utyos bel'ya *belya * bel'a * bel'yu *belyu * bel'u * bel'ye *belye *bele
 * bel'yo *belyo *bel'o * feye * feyu *feyo * fei * izyan *iz'yan *iz'an *iz'-an *iz-an *izan
 * kukmor'ane * soverşayet * postits'a * postit's'a * v * knige vrag goden
 tigra tigr aktsiz küəm Yolqa YoLqa Yo YeRE *baqçə *kiyenüwe *bvg *bv *abv
 *tuğrı *ğu *gɵa *uğrı *uğlan *qıyraq * kompyuter kamennıy kl'uç TYaG
 *cəmğıyətkə *cəmğıyatı *cəmğınət *cəmğıyu *cəmğıyuda *cəmğıyüdə *cəmğıyüt *qənəğətkə * yojikovıy
 * yoj * pervıy *camğıyu *tərəqqiyatı kor'aga drug drugqa poçinok kuçuk
 *tərəqqinət *tərəqqiynət *tərəqqiyət *tərəqqine *tərəqqinə *tərəqqini *tərəqqin' *tərəqqin
 *taraqqıya cəmğıya *camğıya *əqıya *iqıya *ğətqıya *əqaya *sənagət ogo pervıy kukmorskiy
 * yıqıya *ƏQIYA *ıqıya *ğatqıya *nqıya *əğıya *iğıya *ğətğıya *yığıya *ağıya *ığıya *ğatğıya *nğıya
 *yɵreyk *yəşiyk *yəşiyk *ayk şeyk *tuyk *bɵyk *səğa * kukmor'ane * vmeste * kamennıy
 * veduşçem * vedu * djannatu * djannatov * v * kabardino kabardinov * qawardino * balkarii
 * uniçtojen * uniçtojiv * yanvar'a * predlagayem * kaçestvennıye * şemordan * luçşeye * pervoye
 * kompyuter * interesuyet * odejdı * priglaşayem * verxniy *əƏ *ƏTSƏ yuN' *ƏFEYo *ƏYoJZ
 * AYeA! * YeA! * Yea * yeA * AYe!! * aYe!! * Ayı * AYea * AyıA * Ayıa * aYeA! * aYea * ayıA * ayıa
 * kiyəwe * riyaw *üendə *uquwı *üə *üe *KLYo *KL'Yo fevğa yevğa fovğa fofovğa
 *koldun *kolwun *tənqıy *tənğıy *tənğıya *gore [*wazğıyət]*wazğıyət yadk'are
EOD
,
			$this->convertToLatin( <<<'EOD'
 * революцию *сиюга *революцие *революциеда *революциедә *биегә *биеда * наиля * тюк
 * вьюк * утёс *утьёс *утъёс белья *белъя * беля * белью *белъю * белю * белье *белъе *беле
 * бельё *белъё *белё * фее * фею *феё * феи * изъян *изьян *изян *изьан *изъан *изан
 * кукморяне * совершает * постится * поститься * в * книге враг годен
 тигра тигр акциз күәм Ёлка ЁЛка Ё ЕРЭ *бакчә *кийенүве *бвг *бв *абв
 *тугры *гу *гөа *угры *углан *кыйрак * компъютер каменный ключ ТЯГ
 *җәмгыяткә *җәмгыяты *җәмгынәт *җәмгыю *җәмгыюда *җәмгыюдә *җәмгыють *канәгаткә * ёжиковый
 * ёж * первый *җамгыю *тәрәккыяты коряга друг другка починок кучук
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
