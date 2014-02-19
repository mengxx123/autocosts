﻿<?php

// COUNTRY: NETHERLAND
// LANGAUAGE: NETHERLANDS

//***********************************************
//											   **
//      Translation for AUTOCOSTS.ORG          **
//      the automobile costs simulator		   **
// 											   **
//      made by João Pimentel Ferreira         **
//       under Creative Commons BY-SA          **
//	  										   **
//***********************************************

// IMPORTANT: Preserve always the same standards, BE CHOERENT between the text variables and the standard options

//Fuel efficiency for car engine standard
$fuel_efficiency_std_option = 1;
//1 - 1/100 km – liters per 100 kilometer
//2 - km/l – kilometer per liter

//Standard distance
$distance_std_option = 1;
//1 – kilometer

//Standard volume for the price of fuels, ex: Currency($,£,€,etc.)/(Litre, Imp gallon, US gallon) 
$fuel_price_volume_std = 1;
//1 - liters

//standards TEXT VERSION
//IMPORTANT: BE COHERENT with the above standards
$CURR_NAME = 'Euro';
$CURR_NAME_PLURAL = 'Euro';
$CURR_NAME_BIG_PLURAL = 'EURO';
$CURR_SYMBOL = '&euro;';
$STD_DIST = 'km'; //short text version you'd like to apply 
$STD_DIST_FULL = 'kilometer';
$STD_FUEL_CALC = 'l/100km'; //text version you'd like to apply: mijlen per Engelse gallon
$STD_VOLUME_SHORT = 'l'; //short text version you'd like to apply for fuel price per volume unit (litres, imperial gallons or US gallons, be coherent)

//simple words
$WORD_PER = 'per';     //ex: 4 km _per_ day
$WORDS_PER_EACH = 'bij elke';   //ex: 4 miles _ per each_ two months
$WORD_TIMES = 'keer'; //ex: 4 times per week
$DURING = 'gedurende';   //spent in tolls 3€ per day _during_ 22 days per month
$WORD_PEOPLE = 'mensen';   //plural, 3 _people_ 
$YES = 'ja';
$NO = 'nee';

$BUTTON_RUN = 'Laten lopen'; //run simulator button 
$BUTTON_RERUN = 'Opnieuw laten lopen'; //run simulator button 

//WEB PAGE
$WEB_PAGE_TITLE = 'Autokostensimulator';
$MAIN_TITLE = 'AUTOKOSTENSIMULATOR';
$INITIAL_TEXT = 
"Deze simulator zal u toestaan de werkelijke kost te vinden om een auto te bezitten in <b>Nederland</b>. Het kan u een goede schatting geven van wat u echt moet uitgeven om een auto te kunnen bezitten.
Gezien uw rekeningen op verschillende tijdsstippen van het jaar binnenkomen, is het vaak moeilijk om uw totale uitgaven van uw auto werkelijk in te schatten.
<br>
<br>
Wees realistisch met de ingevoerde waarden. Voor onverwachte rekeningen, zoals ongevalreparaties of boetes, denk na over hoeveel u op die posten hebt uitgegeven in de afgelopen jaren. Als standaard worden deze berekeningen maandelijks ingesteld. Gebruik het puntsymbool voor decimale aantekening, bijvoorbeeld <span style=\"color:rgb(255,0,0);\">8.7</span> kilometer tussen thuis en het werk.<br>";

$HELP_PROJECT = 'Help dit project' ;
$AC_MOBILE = 'AUTOKOSTEN<br>voor mobiele apparaten';
$AC_HEADER = '<big><u>WWW.AUTOCOSTS.ORG</u></big><br><b>AUTOKOSTENSIMULATOR</b>';


//time words
$DAYLY = 'dagelijks';
$WEEKLY = 'wekelijks';
$MONTHLY = 'maandelijks';
$TRIMESTERLY = 'driemaandelijks';
$SEMESTERLY = 'halfjaarlijks';
$YEARLY = 'jaarlijks';

$DAY = 'dag';
$DAYS = 'dagen';
$WEEK = 'week';
$MONTH = 'maand';
$MONTHS = 'maanden';
$TWO_MONTHS = 'twee maanden';
$DIST_EACH_TWO_MONTHS = 'kilometer voor iedere twee maanden';
$TRIMESTER = 'trimester';
$SEMESTER = 'semester';
$YEAR = 'jaar';

$DAYS_PER_WEEK_SHORT= 'dagen/week';

//simulator words
$COSTS= "Kosten";
$FIXED_COSTS = 'Vaste kosten';
$FIXED_COSTS_HEADER_1= 'VASTE KOSTEN'; //capital letters
$FIXED_COSTS_HEADER_2= "Kosten die niet afhangen van de afgelegde afstand en kosten om de auto rijklaar te hebben"; 
$DAYS_PER = "dagen per";

$RUNNING_COSTS = 'Exploitatiekosten';
$RUNNING_COSTS_HEADER_1 = 'EXPLOITATIEKOSTEN'; //capital letters
$RUNNING_COSTS_HEADER_2 = 'Kosten die afhangen van de afgelegde afstand';

$PRIVATE_COSTS = 'Private kosten';
$MONTHLY_AMOUNT = 'Maandelijks bedrag';
$RUN_CP_DIST = 'Exploitatiekosten per kilometer'; //running costs per unit distance
$TOTAL_CP_DIST = 'Totale kosten per kilometer'; //total costs per unit distance
$PUBL_TRA_EQUIV= "Equivalente transportkosten, in acht nemend dat u geen auto bezit";
$WORD_TOTAL_CAP = 'TOTAAL'; //capital word for total

//depreciation
$DEPRECIATION = 'Afschrijving van de auto';
$AQ_DATE = 'Aankoopdatum van de auto';
$COM_VALUE = 'Commerciële waarde van de auto toen u die kocht<br><i>indien nieuw, de prijs die u betaalde voor de auto<br>indien tweedehands, de commerciële waarde van de auto toen u die kocht</i>';
$COM_VALUE_TODAY = 'Commerciële waarde van de auto vandaag<br><i>als u die nu verkoopt, hoeveel zou u ervoor krijgen?</i>';
$PERIOD_OWN = 'Periode van bezit';
$FINAL_VALUE = 'Eindwaarde';
$AQ_VALUE = 'Aankoopwaarde';

//insurance
$INSURANCE = 'Autoverzekering en pechhulpverleningsdienst';
$INSURANCE_SHORT = 'Verzekering en pechhulpverleningsdienst';

//credit
$CREDIT = 'Autofinanciering';
$CREDIT_PERIOD = 'Periode';
$CREDIT_INTERESTS = 'Leningrente';
$CREDIT_INTERESTS_MONTH = 'Maandelijks rentebedrag';
$CREDIT_TOTAL_INTERESTS = 'Totaal rentebedrag';
$CREDIT_QUESTION = 'Hebt u autofinanciering gebruikt om de auto te kopen?';
$CREDIT_LOAN = 'Gefinancierd bedrag:<br><i>Hoeveel hebt u geleend?</i>';
$CREDIT_LOAN2 = 'Gefinancierd bedrag';
$CREDIT_PERIOD = 'Krediettermijn / aantal kredietafleveringen';
$CREDIT_AVERAGE_VALUE = 'Gemiddeld bedrag voor elke aflevering';
$CREDIT_RESIDUAL_VALUE = 'Restwaarde:<br><i>Aan het einde van de krediettermijn, hoeveel moet u nog betalen of hoeveel hebt u betaald?</i>';
$CREDIT_RESIDUAL_VALUE1 = 'Restwaarde';
$CREDIT_INSTALMENT = 'Maandelijkse gemiddelde waarde';

//inspection
$INSPECTION = 'Autokeuring (Algemene Periodieke Keuring)';
$INSPECTION_SHORT = 'Keuring';
$INSPECTION_NBMR_TIMES = 'Hoeveel maal hebt u uw auto laten keuren?';
$INSPECTION_PRICE =  'Gemiddelde kost voor elke autokeuring';
$EACH_ONE_DURING = 'per keer, tijdens'; //5 times costing 15€ *each one during* 20 months (inspection)
$TIMES_COSTING = 'keren met een kost van';     //5 *times costing* 15€ each one during 20 months (inspection)

//road taxes
$ROAD_TAXES = 'Motorrijtuigenbelasting (Autobelasting)';
$ROAD_TAXES_SHORT = 'Autobelasting';
$ROAD_TAXES_VALUE = 'Autobelasting voor uw auto:<br><i>betaling overgemaakt aan de staat</i>';

//fuel
$FUEL = 'Brandstof';
$FUEL_DESC = 'Benzine, diesel, LPG, electriciteit';
$FUEL_CALC = 'Berekeningen gebaseerd op';
$FUEL_JOB_CALC = 'Er van uitgaande dat u naar het werk rijdt?';
$FUEL_JOB_CALC1 = 'dag(en) per week die u naar het werk rijdt';
$FUEL_DAYS = 'Dag(en) per week die u naar het werk rijdt';
$FUEL_DIST_HOME_JOB = 'Kilometers die u tussen thuis en het werk rijdt (alleen enkele reis)'; //$CURR_DIST=km, miles, etc.
$FUEL_DIST_HOME_JOB1 = 'kilometers tussen thuis en het werk'; //you do 7 km between home and job
$FUEL_DIST_NO_JOB = "Kilometers die u gemiddeld rijdt tijdens de dagen die u niet naar het werk rijdt:<br><i>alleen enkele reis</i>";
$FUEL_DIST_NO_JOB1 = "gemiddelde kilometer tijdens de dagen die u niet naar het werk rijdt"; // you do 5 km per week....
$FUEL_DIST = 'Kilometers die u rijdt';
$FUEL_CAR_EFF = 'Brandstofefficiëntie van uw voertuig';
$FUEL_PRICE = 'Gemiddelde prijs die u betaalt voor de benzine';
$FUEL_PRICE1 = 'Gemiddelde benzineprijs';
$YOU_DRIVE_TOTTALY_AVG = 'U rijdt volledig tegen een gemiddelde van'; //__You drive totally on average of__ 5 km per day
$YOU_DRIVE = 'U rijdt'; //__You drive__ 5 km per day

//MAINTENANCE
$MAINTENANCE = 'Onderhoud';
$MAINTENANCE_DESC = 'Gemiddelde kost voor onderhoud en pechhulp:<br><i>vervanging van motorolie, filters, lichten, banden, remmen, airconditioning, stuuruitlijning, enz.</i>';

//REPAIRS AND IMPROVEMENTS
$REP_IMPROV = 'Herstellingen en verbeteringen';
$REP_IMPROV_DESC = 'Gemiddelde kost voor herstellingen en verbeteringen:<br><i>auto-onderdelen, wijzigingen, herstellen van storingen, deuken, botsingen, afstemming, enz.</i>';

//PARKING
$PARKING = 'Parkeren';
$PARKING_DESC = 'Gemiddelde kost voor het parkeren van de auto:<br><i>parkeermeters in stad, huren van een parkeerruimte, ondergrondse of bovengrondse parkeerruimten in publieke gebouwen, winkelcentra, luchthavens, bus- of treinstations of eender welke andere infrastructuur.</i>';

//TOLLS
$TOLLS = 'Tol';
$TOLLS_DESC = 'Gemiddelde kost uitgegeven aan tol<br><i>bruggen, tunnels, autosnelwegen en wegen met rekeningrijden om toegang te krijgen tot het stadscentrum</i>';
$TOLLS_DAY_CALC = 'Berekening op basis van dag?';
$TOLLS_DAY_CALC1 = 'Dagelijks bedrag dat u uitgaf aan tol';
$TOLLS_DAY_CALC_DESC = 'Denk gewoon na over de zeldzame uitstapjes die u maakt buiten uw stad of naar het platteland of over de kwitanties voor elke vorm van elektronische tolheffing';

//FINES
$FINES = 'Verkeersboetes';
$FINES_DESC = 'Gemiddelde kost uitgegeven aan verkeersboetes<br><i>Hoeveel hebt u in de laatste jaren uitgegeven aan verkeersboetes? (illegaal parkeren, overtreding van de snelheidslimiet, mobiele telefoon, enz.)</i>';

//WASHING
$WASHING = 'Wassen en schoonmaken';
$WASHING_DESC = 'Gemiddelde was- en dienstrekening:<br><i>in tankstations en andere plaatsen</i>';

//TOTAL
$TOTAL_FIXED = 'TOTAAL – Vaste kosten';
$TOTAL_FIXED_DESCR = "Kosten die niet afhankelijk zijn van de afgelegde afstand en waar men ook moet betalen als de auto niet rijdt";
$TOTAL_FIXED_DESCR2 = 'Afschrijvingen, verzekering, financiële rente, belastigen, keuring en 50% voor het parkeren en het onderhoud';

$TOTAL_VARIABLE = 'TOTAAL - Exploitatiekosten';
$TOTAL_VARIABLE_DESCR = 'Kosten die afhangen van het aantal gereden kilometers';
$TOTAL_VARIABLE_DESCR2 = 'Brandstoffen, herstellen en verbeteringen, parkeren (er van uitgaande dat u enkel betaalt waneer u de auto gebruikt), tol, verkeersboetes, wassen en 50% voor het onderhoud';


//EXTRA DATA
$EXTRA_DATA = 'AANVULLENDE GEGEVENS';
$EXTRA_DATA1 = 'Aanvullende gegevens';
$EXTRA_DATA_FAMILY_NBR = 'Hoeveel personen ouder dan 4 jaar zijn er in uw familie (inclusief uzelf)';
$EXTRA_DATA_PRICE_PASS = "Wat is de gemiddelde prijs per persoon van het maandabonnement met openbaar vervoer, voor uw normale dagelijkse leven<br><i>als openbaar vervoer geen optie is, 0 invoeren</i>";

//PUBLIC TRANSPORTS
$PUB_TRANS_TEXT = 'Openbaar vervoer voor uw dagelijks gezinsleven';
$FAM_NBR = 'Aantal gezinsleden ouder dan 4 jaar';
$PERSON_OR_PEOPLE = 'personen';
$PASS_MONTH_AVG = 'Gemiddeld bedrag van het maandelijks abonnement per persoon';
$OTHER_PUB_TRANS = 'Ander openbaar vervoer';
$OTHER_PUB_TRANS_DESC = "Bedrag dat nog open stond voor ander openbaar vervoer, bijvoorbeeld vanuit uw woongebied, zoals lange treinreizen of lange busreizen";
$TAXI_DESL = "Taxivervoer";
$ON_TAXI_PAYING = "met een betaalde taxi"; //ex: 4 km __on taxi paying__ 5€ per km


//**************************************************
//GRAPHICS
$PARCEL = 'Perceel';
$COSTS = 'Kosten';


//****************************************************
//ERROR MESSAGES
$ERROR_INVALID_INSU_VALUE = 'Ongeldig verzekeringsbedrag';
$ERROR_INSU_PERIOD = 'Periodiciteit van de verzekering invoeren';

$ERROR_FUEL_CURR_DIST = 'U moet raadplegen als u berekeningen wilt maken op basis van het euro of op kilometer';
$ERROR_FUEL_CAR_EFF = 'Ongeldig brandstofefficiëntiebedrag';
$ERROR_FUEL_PRICE = 'Ongeldige brandstofprijs';
$ERROR_CAR_JOB = 'Gelieve te melden als u met uw auto naar het werk rijdt';
$ERROR_FUEL_DIST = 'Ongeldig bedrag van gereisde mijlen per maand';
$ERROR_DAYS_PER_WEEK = 'Ongeldig aantal dagen per week';
$ERROR_DIST_HOME_WORK = 'Ongeldig aantal kilometer tussen thuis en het werk';
$ERROR_DIST_NO_JOB = 'Ongeldig aantal kilometer gedurende de dagen waarbij u uw auto niet gebruikt om naar het werk te rijden';
$ERROR_CURRENCY = 'Ongeldige euro per maand';

$ERROR_DEPRECIATION_MONTH = 'Ongeldige verwervingsmaand';
$ERROR_DEPRECIATION_YEAR = 'Ongeldig verwervingsjaar';
$ERROR_DEPRECIATION_VALUE = 'Ongeldig verwervingsbedrag';
$ERROR_DEPRECIATION_VALUE_TODAY = 'Ongeldige momnetane voertuigwaarde';
$ERROR_DEPRECIATION_DATE = 'Ongeldige aanwervingsdatum';
$ERROR_DEPRECIATION_NEW_CAR =  'De afschrijving is niet van toepassing omdat dit voertuig nieuw is';

$ERROR_CREDIT_QUESTION = 'Gelieve te verwijzen als u een autofinanciering heb gebruikt';
$ERROR_CREDIT_LOAN_VALUE = 'Ongeldig gefinancierd bedrag';
$ERROR_CREDIT_PERIOD = 'Ongeldige kredietperiodet, aantal termijnen';
$ERROR_CREDIT_INSTALMENT = 'Ongeldig termijnbedrag';
$ERROR_CREDIT_RESIDUAL_VALUE = 'Ongeldige restwaarde';

$ERROR_INSPECTION_NTIMES = 'Ongeldig aantal keren';
$ERROR_INSPECTION_COSTS = 'Ongeldige keuringskost';

$INVALID_AMOUNT = 'Ongeldig bedrag';

$INVALID_NBR_PP = 'Ongeldig aantal mensen';
$ERROR_PASS_AMOUNT= 'Ongeldig maandkaart-bedrag';

//FINAL RESULT
$YOUR_CAR_COSTS_YOU = 'Uw auto kost';
$WITH_THIS_LEVEL_OF_COSTS = 'Met dit niveau van kosten heeft uw auto, tijdens de'; //ex: __"With this level of costs, you car during the"__ 15 months of possession....
$MONTHS_POSS = 'maanden van bezit, reeds zoveel gekost';   //ex: With this level of costs, you car during the 15 ___"months of possession has already costed"___ 14000 Euros


$TAXI_PRICE_PER_DIST=1.5; //price paid for taxi in chosen currency per chosen unit distance

//*****************************************
//STANDARD COMMON AVERAGE DEFAULT values that apear on the start page
//these values are to be changed by the user but you shall put values that are reasonable
//keep in mind your chosen standard Currency and your volume and fuel efficiency standards

$STD_ACQ_MONTH = ''; //month of acquisition 
$STD_ACQ_YEAR = ''; //year of acquisition 
$STD_PRICE_PAID = ''; //price paid for the car
$STD_PRICE_TODAY = ''; //the price the car has today

$STD_INSURANCE_SEM = ''; //price paid for insurance by semester

$STD_LOAN = ''; //amount asked for credit
$STD_PERIOD_OF_CREDIT = ''; //period of the credit in months
$STD_MONTHLY_PAY = ''; //monthly payment
$STD_RESIDUAL_VALUE = ''; //residual value must be paid after credit

$STD_NBR_INSPECTION = ''; //number of times car went to inspection
$STD_INSPECTION_PRICE = ''; //normal inspection price

$STD_ROAD_TAX = ''; //price paid for road taxes per year

$STD_FUEL_PAID_PER_MONTH = ''; //money spent per month on fuels
$STD_DAYS_PER_WEEK = ''; //days per week one takes their car to work
$STD_JORNEY_2WORK = ''; //(standard distance, km or miles) made from home to work (just one way) 
$STD_JORNEY_WEEKEND = ''; //(standard distance, km or miles) during the other days, for example weekends
$STD_KM_PER_MONTH = ''; //(standard distance, km or miles) made per month
$STD_CAR_FUEL_EFFICIENCY = ''; //(standard fuel efficiency, km/l l/100km mpg(US) or mpg(imp)) fuel efficiency in the chosen standard
$STD_FUEL_PRICE = ''; //price paid for fuel on chosen currency

$STD_MAINTENANCE_PER_YEAR = ''; //amount paid for maintenance per year

$STD_REPAIRS = ''; //repairs and improvements paid per year on average

$STD_PARKING = ''; //parking paid per month

$STD_TOLLS = ''; //amount paid in tolls per trimestre 
$STD_TOLLS_DAY = ''; //amount paid in tolls per day
$STD_TOLLS_DAYS_PER_MONTH = ''; //number of days per month the car crosses a tolled way

$STD_FINES = ''; //fines paid on average per trimestre

$STD_WASHING = ''; //amount paid in washings per trimestre

?>