<? Header("content-type: application/x-javascript");

include('../country files/' . $_GET['country'] . '.php');

$def_cty = $_GET['country'];

?>


//********************
//normalizing functions

//converts chosen fuel consumption to l/100km
function convert_to_fuel_eff_l100km(fuel_eff){

    var fuel_eff_temp=parseFloat(fuel_eff);

    switch (<? echo $fuel_efficiency_std_option; ?>){
        case 1:
            return fuel_eff_temp;
        case 2:
            return 100/fuel_eff_temp;  //km/l -> l/100km
        case 3:
            return (100*4.54609188)/(1.609344 *fuel_eff_temp); //mpg(imp) -> l/100km
        case 4:
            return (100*3.78541178)/(1.609344 *fuel_eff_temp); //mpg(US) -> l/100km
        case 5:
            return 10*fuel_eff_temp; //l/mil -> l/100km (1 mil = 10km)
    }
}

//converts chosen fuel price to CURRENCY_unit/litre
function convert_to_fuel_price_CURRpLitre(fuel_price){

var fuel_price_temp=parseFloat(fuel_price);

    switch (<? echo $fuel_price_volume_std; ?>){
        case 1:
            return fuel_price_temp; // CURRENCY_unit/litre to CURRENCY_unit/litre
        case 2:
            return fuel_price_temp/4.54609188; //currency/(imp gallon) -> currency/litre
        case 3:
            return fuel_price_temp/3.78541178; //currency/(US gallon) -> currency/litre
    }
}

//converts chosen distances to km
function convert_std_dist_to_km(dist){

    var dist_t=parseFloat(dist);

    switch (<? echo $distance_std_option; ?>){
        case 1:
            return dist_t;
        case 2:
            return dist_t*1.609344; //miles to km
        case 3:
            return dist_t*10; //mil(10km) to km
    }
}

//converts km to chosen distances
function convert_km_to_std_dist(dist){

    var dist_t=parseFloat(dist);

    switch (<? echo $distance_std_option; ?>){
        case 1:
            return dist_t;
        case 2:
            return dist_t/1.609344; //km to miles
        case 3:
            return dist_t/10; //km to mil(10km)
    }
}
//end of normalizing functions


//******************************************************************************************************************
//***************************************************** CALCULATE FUNCTION *****************************************

function calcula_custos_auto(){

//var isToSubmitData = document.getElementById('submitData').checked;

frame_witdh=document.getElementById('input_div').offsetWidth;

//seguro automóvel
var tipo_seguro_auto=getCheckedValue(custo.tipo_seguro);
var val_seguro_por_mes;
var seguro_text;

if(!isNumber(document.custo.seguro_val.value)){
    alert("<?echo $INSURANCE?> - <?echo $ERROR_INVALID_INSU_VALUE?>!");
    return;
}

if(tipo_seguro_auto == ""){
    alert("<?echo $INSURANCE?> - <?echo $ERROR_INSU_PERIOD?>!");
    return;
}

switch(tipo_seguro_auto)
{
    case "semestral":
        val_seguro_por_mes=document.custo.seguro_val.value/6;
        seguro_text=document.custo.seguro_val.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
        break;
    case "anual":
        val_seguro_por_mes=document.custo.seguro_val.value/12;
        seguro_text=document.custo.seguro_val.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?>";
        break;
    case "mensal":
        val_seguro_por_mes=parseFloat(document.custo.seguro_val.value);
        seguro_text=val_seguro_por_mes + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $MONTH?>";
        break;
    case "trimestral":
        val_seguro_por_mes=document.custo.seguro_val.value/3;
        seguro_text=document.custo.seguro_val.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
        break;
}

//************* FUEL COSTS ********************************
//*********************************************************

//normalizing functions
//---
//convert_to_fuel_eff_l100km
//convert_to_fuel_price_CURRpLitre
//convert_std_dist_to_km
//convert_km_to_std_dist

var val_combustiveis_por_mes;
var km_por_mes;
var combustiveis_text;

var tipo_calc_combustiveis=getCheckedValue(custo.calc_combustiveis);
var leva_auto_job;

if(tipo_calc_combustiveis == ""){
alert("<?echo $FUEL?> - <?echo $ERROR_FUEL_CURR_DIST?>!");
return;
}

switch(tipo_calc_combustiveis)
{
case "km": //fuel calculations made considering distance travelled by month

//normalizes converting fuel consumption to l/100km
var fuel_eff_l100km=document.custo.consumo_auto.value;
fuel_eff_l100km=convert_to_fuel_eff_l100km(fuel_eff_l100km);

//normalizes converting fuel price to CURRENCY per litres
var fuel_price_CURRpLitre=document.custo.fuel_price.value;
fuel_price_CURRpLitre=convert_to_fuel_price_CURRpLitre(fuel_price_CURRpLitre);

if(!isNumber(document.custo.consumo_auto.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_FUEL_CAR_EFF?>!");
return;
}
if(!isNumber(document.custo.fuel_price.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_FUEL_PRICE?>!");
return;
}

leva_auto_job=getCheckedValue(document.custo.carro_emprego);

if(leva_auto_job == ""){
alert("<?echo $FUEL?> - <?echo $ERROR_CAR_JOB?>!");
return;
}

if (leva_auto_job=="nao"){

var temp=document.custo.combustivel_period_km;
var fuel_period_km = temp.options[temp.selectedIndex].value;

if(!isNumber(document.custo.km_por_mes.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_FUEL_DIST?>!");
return;
}

switch(fuel_period_km)
{
case "1":
km_por_mes=document.custo.km_por_mes.value;
combustiveis_text=document.custo.km_por_mes.value + " <?echo $STD_DIST?> <?echo $WORD_PER?> <?echo $MONTH?>";
break;
case "2":
km_por_mes=document.custo.km_por_mes.value/2;
combustiveis_text=document.custo.km_por_mes.value + " <?echo $DIST_EACH_TWO_MONTHS?>";
break;
case "3":
km_por_mes=document.custo.km_por_mes.value/3;
combustiveis_text=document.custo.km_por_mes.value + " <?echo $STD_DIST?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
break;
case "4":
km_por_mes=document.custo.km_por_mes.value/6;
combustiveis_text=document.custo.km_por_mes.value + " <?echo $STD_DIST?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
break;
case "5":
km_por_mes=document.custo.km_por_mes.value/12;
combustiveis_text=document.custo.km_por_mes.value + " <?echo $STD_DIST?> <?echo $WORD_PER?> <?echo $YEAR?>";
break;
}

//if miles were chosen must convert input to kilometres
km_por_mes=convert_std_dist_to_km(km_por_mes);

val_combustiveis_por_mes=fuel_eff_l100km*km_por_mes*fuel_price_CURRpLitre/100;

combustiveis_text=combustiveis_text + "<br>" + "<?echo $FUEL_CAR_EFF?>: " + document.custo.consumo_auto.value + " <?echo $STD_FUEL_CALC?>&nbsp;";
combustiveis_text=combustiveis_text + "<br>" + "<?echo $FUEL_PRICE1?>: " + document.custo.fuel_price.value + " <?echo $CURR_SYMBOL?>/<?echo $STD_VOLUME_SHORT?>&nbsp;&nbsp;";
}
else{//make calculation considering the user takes his car to work on a daily basis

var dias_por_semana_carro, km_casa_emprego, km_fds, km_totais;

if(!isNumber(document.custo.dias_por_semana.value) || (document.custo.dias_por_semana.value)>7){
alert("<?echo $FUEL?> - <?echo $ERROR_DAYS_PER_WEEK?>!");
return;
}
if(!isNumber(document.custo.km_entre_casa_trabalho.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_DIST_HOME_WORK?>!");
return;
}
if(!isNumber(document.custo.km_fds.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_DIST_NO_JOB?>!");
return;
}

//if miles were chosen must convert input to kilometres
var km_entre_casa_trabalho=convert_std_dist_to_km(document.custo.km_entre_casa_trabalho.value);
var km_fds_value=convert_std_dist_to_km(document.custo.km_fds.value);

km_totais=((2*km_entre_casa_trabalho*parseInt(document.custo.dias_por_semana.value,10))+km_fds_value)*(30.4375/7);

//alert(km_entre_casa_trabalho);
//alert(km_fds_value);
//alert(parseInt(document.custo.dias_por_semana.value,10));
//alert(km_totais);

combustiveis_text=document.custo.dias_por_semana.value + " <?echo $FUEL_JOB_CALC1?> <br>";
combustiveis_text=combustiveis_text + "<?echo $YOU_DRIVE?> " + document.custo.km_entre_casa_trabalho.value + " <?echo $FUEL_DIST_HOME_JOB1?> <br>";
combustiveis_text=combustiveis_text + "<?echo $YOU_DRIVE?> " + document.custo.km_fds.value + " <?echo $FUEL_DIST_NO_JOB1?>&nbsp;<br>";
combustiveis_text=combustiveis_text + "<?echo $YOU_DRIVE_TOTTALY_AVG?> " + convert_km_to_std_dist(km_totais).toFixed(1) + " <?echo $STD_DIST?> <?echo $WORD_PER?> <?echo $MONTH?> (~30.5 <?echo $DAYS?>) <br>";

val_combustiveis_por_mes=fuel_eff_l100km*km_totais*fuel_price_CURRpLitre/100;

combustiveis_text=combustiveis_text + "<?echo $FUEL_CAR_EFF?>: " + document.custo.consumo_auto.value + " <?echo $STD_FUEL_CALC?>";
combustiveis_text=combustiveis_text + "<br>" + "<?echo $FUEL_PRICE?>: " + document.custo.fuel_price.value + " <?echo $CURR_SYMBOL?>/<?echo $STD_VOLUME_SHORT?>";

km_por_mes=km_totais;
}

break;
case "euros":

if(!isNumber(document.custo.combustiveis_euro.value)){
alert("<?echo $FUEL?> - <?echo $ERROR_CURRENCY?>!");
return;
}

var price_mes;
temp=document.custo.combustiveis_periodo_euro;

var fuel_cost_period = temp.options[temp.selectedIndex].value;

switch(fuel_cost_period)
{
case "1":
price_mes = parseFloat(document.custo.combustiveis_euro.value);
combustiveis_text=document.custo.combustiveis_euro.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $MONTH?>";
break;
case "2":
price_mes=document.custo.combustiveis_euro.value/2;
combustiveis_text=document.custo.combustiveis_euro.value + " <?echo $DIST_EACH_TWO_MONTHS?>";
break;
case "3":
price_mes=document.custo.combustiveis_euro.value/3;
combustiveis_text=document.custo.combustiveis_euro.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
break;
case "4":
price_mes=document.custo.combustiveis_euro.value/6;
combustiveis_text=document.custo.combustiveis_euro.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
break;
case "5":
price_mes=document.custo.combustiveis_euro.value/12;
combustiveis_text=document.custo.combustiveis_euro.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?>";
break;
}
val_combustiveis_por_mes=price_mes;

break;
}


//******************* DEPRECIATION ************************
//*********************************************************

var desva_auto_por_mes;
var desva_text;
var auto_mes=document.custo.auto_mes.value;
var auto_ano=document.custo.auto_ano.value;

if(!isNumber(auto_mes) || !(parseFloat(auto_mes) == parseInt(auto_mes, 10)) || auto_mes>12 || auto_mes<=0){
    alert("<?echo $DEPRECIATION?> - <?echo $ERROR_DEPRECIATION_MONTH?>!");
    return;
}
if(!isNumber(document.custo.auto_ano.value) || !(parseFloat(document.custo.auto_ano.value) == parseInt(document.custo.auto_ano.value, 10))){
    alert("<?echo $DEPRECIATION?> - <?echo $ERROR_DEPRECIATION_YEAR?>!");
    return;
}
if(!isNumber(document.custo.auto_val_inicial.value)){
    alert("<?echo $DEPRECIATION?> - <?echo $ERROR_DEPRECIATION_VALUE?>!");
    return;
}
if(!isNumber(document.custo.auto_val_final.value)){
    alert("<?echo $DEPRECIATION?> - <?echo $ERROR_DEPRECIATION_VALUE_TODAY?>!");
    return;
}

var today = new Date();
var date_auto= new Date(document.custo.auto_ano.value,document.custo.auto_mes.value-1);

var meses=date_diff(date_auto,today);

if(!meses){
    alert("<?echo $DEPRECIATION?> - <?echo $ERROR_DEPRECIATION_DATE?>!");
    return;
}

if (meses==0){
    cred_desva_auto_por_mes=0;
    desva_text="<?echo $ERROR_DEPRECIATION_NEW_CAR?>&nbsp;&nbsp;";
} else {
    cred_desva_auto_por_mes=(document.custo.auto_val_inicial.value-document.custo.auto_val_final.value)/meses;
    desva_text="<b><span class=\"p3\"><?echo $DEPRECIATION?><\/span><\/b>&nbsp;&nbsp;<br><span class=\"p2\"><?echo $AQ_VALUE?>: "
    +document.custo.auto_val_inicial.value+"<?echo $CURR_SYMBOL?><br><?echo $FINAL_VALUE?>: "
    +document.custo.auto_val_final.value+"<?echo $CURR_SYMBOL?><br><?echo $PERIOD_OWN?>: "
    + meses +" <?echo $MONTHS?><br>("
    +document.custo.auto_val_inicial.value+"<?echo $CURR_SYMBOL?>-"
    +document.custo.auto_val_final.value+"<?echo $CURR_SYMBOL?>)/"
    +meses+" <?echo $MONTHS?><\/span>";
}


//*********************  CREDIT  **************************
//*********************************************************

var juros_auto_por_mes=0;
var juros_totais;
var juros_text="<b><span class=\"p3\"><?echo $CREDIT_INTERESTS?><\/span><\/b>&nbsp;&nbsp;";
var cred_auto_s_n=getCheckedValue(custo.cred_auto);

if(cred_auto_s_n == ""){
    alert("<?echo $ERROR_CREDIT_QUESTION?>!");
    return;
}

if(cred_auto_s_n == "true") {
    if(!isNumber(document.custo.cred_auto_montante.value)) {
        alert("<?echo $CREDIT?> - <?echo $ERROR_CREDIT_LOAN_VALUE?>!");
        return;
    }
    if(!isNumber(document.custo.cred_auto_period.value)) {
        alert("<?echo $CREDIT?> - <?echo $ERROR_CREDIT_PERIOD?>!");
        return;
    }
    if(!isNumber(document.custo.cred_auto_val_mes.value)) {
        alert("<?echo $CREDIT?> - <?echo $ERROR_CREDIT_INSTALMENT?>!");
        return;
    }
    if(!isNumber(document.custo.cred_auto_valresidual.value)) {
        alert("<?echo $CREDIT?> - <?echo $ERROR_CREDIT_RESIDUAL_VALUE?>!");
        return;
    }

    juros_text="<b><span class=\"p3\"><?echo $CREDIT_INTERESTS?><\/span><\/b>&nbsp;&nbsp;<br><span class=\"p2\"><?echo $CREDIT_LOAN2?>: "
    +document.custo.cred_auto_montante.value
    +"<?echo $CURR_SYMBOL?><br><?echo $CREDIT_PERIOD?>: "
    +document.custo.cred_auto_period.value
    +" <?echo $MONTHS?><br><?echo $CREDIT_INSTALMENT?>: "
    +document.custo.cred_auto_val_mes.value
    +"<?echo $CURR_SYMBOL?><br><?echo $CREDIT_RESIDUAL_VALUE1?>: "
    +document.custo.cred_auto_valresidual.value
    +"<?echo $CURR_SYMBOL?><br>";

    var meses_cred=parseFloat(document.custo.cred_auto_period.value);
    juros_totais=((meses_cred*parseFloat(document.custo.cred_auto_val_mes.value))+parseFloat(document.custo.cred_auto_valresidual.value))-parseFloat(document.custo.cred_auto_montante.value);

    if(juros_totais<0){
        juros_totais=0;
    }

    juros_text+="<?echo $CREDIT_TOTAL_INTERESTS?>: "+juros_totais+"<?echo $CURR_SYMBOL?><br>("+meses_cred+"*"+document.custo.cred_auto_val_mes.value+")+"+document.custo.cred_auto_valresidual.value+"-"+document.custo.cred_auto_montante.value;

    if(meses>=meses_cred) {
        juros_auto_por_mes=juros_totais/meses;

        juros_text+="<br><?echo $CREDIT_INTERESTS_MONTH?>: "+juros_auto_por_mes.toFixed(2)+"<?echo $CURR_SYMBOL?>";
    } else {
        juros_auto_por_mes=parseFloat(juros_totais/meses_cred);
    }
    juros_text+="<\/span>";
}

//INSPECTION
if(!isNumber(document.custo.nr_vezes_inspecao.value)) {
    alert("<?echo $INSPECTION?> - <?echo $ERROR_INSPECTION_NTIMES?>!");
    return;
}
if(!isNumber(document.custo.preco_inspecao.value)) {
    alert("<?echo $INSPECTION?> - <?echo $ERROR_INSPECTION_COSTS?>!");
    return;
}

var inspecao_por_mes=(document.custo.nr_vezes_inspecao.value*document.custo.preco_inspecao.value)/meses;
var inspecao_text="<b><span class=\"p3\"><?echo $INSPECTION?><\/span><\/b><br><span class=\"p2\">"
+document.custo.nr_vezes_inspecao.value
+" <?echo $TIMES_COSTING?> "
+document.custo.preco_inspecao.value
+" <?echo $CURR_SYMBOL?> <?echo $EACH_ONE_DURING?> "
+meses+" <?echo $MONTHS?>&nbsp;<\/span>";

//Revisões
if(!isNumber(document.custo.revisoes.value)) {
    alert("<?echo $MAINTENANCE?> - <?echo $INVALID_AMOUNT?>!");
    return;
}

var revisoes_por_mes=document.custo.revisoes.value/12;
var revisoes_text="<b><span class=\"p3\"><?echo $MAINTENANCE?><\/span><\/b><br><span class=\"p2\">"+document.custo.revisoes.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?><\/span>";

//Reparações
if(!isNumber(document.custo.reparacoes.value)) {
    alert("<?echo $REP_IMPROV?> - <?echo $INVALID_AMOUNT?>!");
    return;
}

var reparacoes_por_mes=document.custo.reparacoes.value/12;
var reparacoes_text="<b><span class=\"p3\"><?echo $REP_IMPROV?><\/span><\/b><span class=\"p2\"><br>"+document.custo.reparacoes.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?><\/span>";

//IUC
if(!isNumber(document.custo.IUC.value)) {
    alert("<?echo $ROAD_TAXES?> - <?echo $INVALID_AMOUNT?>!");
    return;
}

var IUC_por_mes=document.custo.IUC.value/12;
var IUC_text="<b><span class=\"p3\"><?echo $ROAD_TAXES?><\/span><\/b><br><span class=\"p2\">"+document.custo.IUC.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?><\/span>";


//parquemanto
if(!isNumber(document.custo.parqueamento.value)){
    alert("<?echo $PARKING?> - <?echo $INVALID_AMOUNT?>!");
    return;
}
var parqueamento_por_mes = parseFloat(document.custo.parqueamento.value);


//********************
//*****PORTAGENS******

var portagens_por_mes;
var portagens_text="<b><span class=\"p3\"><?echo $TOLLS?><\/span><\/b><br><span class=\"p2\">";

var tipo_calc_portagens=getCheckedValue(document.custo.portagens_ao_dia);

if(tipo_calc_portagens=="nao") {
    if(!isNumber(document.custo.portagens.value)) {
        alert("<?echo $TOLLS?> - <?echo $INVALID_AMOUNT?>!");
        return;
    }

    temp=document.custo.portagens_select;
    var portagens_period = temp.options[temp.selectedIndex].value;

    switch(portagens_period) {
        case "1":
            portagens_por_mes = parseFloat(document.custo.portagens.value);
            portagens_text+=document.custo.portagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $MONTH?>";
            break;
        case "2":
            portagens_por_mes=document.custo.portagens.value/2;
            portagens_text+=document.custo.portagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORDS_PER_EACH?> <?echo $TWO_MONTHS?>";
            break;
        case "3":
            portagens_por_mes=document.custo.portagens.value/3;
            portagens_text+=document.custo.portagens.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
            break;
        case "4":
            portagens_por_mes=document.custo.portagens.value/6;
            portagens_text+=document.custo.portagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
            break;
        case "5":
            portagens_por_mes=document.custo.portagens.value/12;
            portagens_text+=document.custo.portagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?>";
            break;
    }
} else {//cálculo de portagens ao dia
    if(!isNumber(document.custo.preco_portagens_por_dia.value)) {
        alert("Portagens - Preço por dia inválido!");
        return;
    }
    if(!isNumber(document.custo.dias_portagens_por_mes.value) || document.custo.dias_portagens_por_mes.value>31) {
        alert("Portagens - Número de dias inválido!");
        return;
    }

    portagens_por_mes=document.custo.preco_portagens_por_dia.value*document.custo.dias_portagens_por_mes.value;
    portagens_text+=document.custo.preco_portagens_por_dia.value + " <?echo $CURR_NAME_PLURAL?> <?echo $DURING?> " + document.custo.dias_portagens_por_mes.value + " <?echo $MONTH?>";
}

portagens_text+="<\/span>";


//*************** FINES *********
//*******************************

if(!isNumber(document.custo.multas.value)){
    alert("<?echo $FINES?> - <?echo $INVALID_AMOUNT?>!");
    return;
}

var multas_por_mes;

var multas_text="<b><span class=\"p3\"><?echo $FINES?><\/span><\/b><br><span class=\"p2\">";

temp=document.custo.multas_select;
var multas_period = temp.options[temp.selectedIndex].value;

switch(multas_period) {
    case "1":
        multas_por_mes = parseFloat(document.custo.multas.value);
        multas_text+=document.custo.multas.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $MONTH?>";
        break;
    case "2":
        multas_por_mes=document.custo.multas.value/2;
        multas_text+=document.custo.multas.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORDS_PER_EACH?> <?echo $TWO_MONTHS?>";
        break;
    case "3":
        multas_por_mes=document.custo.multas.value/3;
        multas_text+=document.custo.multas.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
        break;
    case "4":
        multas_por_mes=document.custo.multas.value/6;
        multas_text+=document.custo.multas.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
        break;
    case "5":
        multas_por_mes=document.custo.multas.value/12;
        multas_text+=document.custo.multas.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?>";
        break;
    }
multas_text+="<\/span>";

//*************** WASHINGS *********
//**********************************

if(!isNumber(document.custo.lavagens.value)){
    alert("<?echo $WASHING?> - <?echo $INVALID_AMOUNT?>!");
    return;
}

var lavagens_por_mes;
var lavagens_text="<b><span class=\"p3\"><?echo $WASHING?><\/span><\/b><br><span class=\"p2\">";

temp=document.custo.lavagens_select;
var lavagens_period = temp.options[temp.selectedIndex].value;

switch(lavagens_period) {
    case "1":
        lavagens_por_mes = parseFloat(document.custo.lavagens.value);
        lavagens_text+=document.custo.lavagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $MONTH?>";
        break;
    case "2":
        lavagens_por_mes=document.custo.lavagens.value/2;
        lavagens_text+=document.custo.lavagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORDS_PER_EACH?> <?echo $TWO_MONTHS?>";
        break;
    case "3":
        lavagens_por_mes=document.custo.lavagens.value/3;
        lavagens_text+=document.custo.lavagens.value+" <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $TRIMESTER?>";
        break;
    case "4":
        lavagens_por_mes=document.custo.lavagens.value/6;
        lavagens_text+=document.custo.lavagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $SEMESTER?>";
        break;
    case "5":
        lavagens_por_mes=document.custo.lavagens.value/12;
        lavagens_text+=document.custo.lavagens.value + " <?echo $CURR_NAME_PLURAL?> <?echo $WORD_PER?> <?echo $YEAR?>";
        break;
    }
lavagens_text+="<\/span>";

//TOTAIS parciais
var custos_fixos=val_seguro_por_mes + cred_desva_auto_por_mes + inspecao_por_mes + 0.5*revisoes_por_mes + IUC_por_mes;
var custos_fixos_text="<b><span class=\"p3\"><?echo $TOTAL_FIXED?><\/span><\/b><br><span class=\"p2\"><i><?echo $TOTAL_FIXED_DESCR?>:<\/i><br><?echo $TOTAL_FIXED_DESCR2?><\/span>";

var custos_variav=val_combustiveis_por_mes + 0.5*revisoes_por_mes + reparacoes_por_mes + parqueamento_por_mes + portagens_por_mes + multas_por_mes + lavagens_por_mes;
var custos_variav_text="<b><span class=\"p3\"><?echo $TOTAL_VARIABLE?><\/span><\/b><br><span class=\"p2\"><i><?echo $TOTAL_VARIABLE_DESCR?>:<\/i><br><?echo $TOTAL_VARIABLE_DESCR2?><\/span>";

//TOTAL
var total=val_seguro_por_mes + val_combustiveis_por_mes + cred_desva_auto_por_mes + inspecao_por_mes + revisoes_por_mes + reparacoes_por_mes + IUC_por_mes + parqueamento_por_mes + portagens_por_mes + multas_por_mes + lavagens_por_mes;


//CUSTOS EXTERNOS

//caso se saiba o número de km calcula os custos externos (apenas para o caso de Portugal por enquanto)

if(<?if ($def_cty=="PT") echo 'tipo_calc_combustiveis=="km"'; else echo "false";?>) {

    var handbook_extern_URL="http:\/\/ec.europa.eu\/transport\/themes\/sustainable\/doc\/2008_costs_handbook.pdf"; //anteceder uma barra '/' por uma barra '\'

    var epa=0.005; //Emissões de poluentes atmosféricos em €/km
    var egee=0.007; //Emissões de gases de efeito de estufa em €/km
    var ruido=0.004; //Ruído em €/km
    var sr=0.03; //sinistralidade rodoviária em €/km
    var cgstn=0.1; //congestionamento em €/km
    var ifr_estr=0.001; //custos externos de desgaste da infra-estrutura em €/km

    var epa_text="<b><span class=\"p3\">Emissões de poluentes atmosféricos<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + epa + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var egee_text="<b><span class=\"p3\">Emissões de gases de efeito de estufa<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + egee + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var ruido_text="<b><span class=\"p3\">Poluição sonora<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + ruido + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var sr_text="<b><span class=\"p3\">Sinistralidade rodoviária<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + sr + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var cgstn_text="<b><span class=\"p3\">Congestionamento<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + cgstn + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var ifr_estr_text="<b><span class=\"p3\">Desgaste das infraestruturas rodoviárias<\/span><\/b><br><span class=\"p2\">Valor aproximado: " + ifr_estr + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
    var fonte_text="<b><span class=\"p2\">Fonte dos dados:<\/span><\/b><br><span class=\"p2\"><i><a href=\"" + handbook_extern_URL + "\">Handbook on estimation of external costs in the transport sector<\/a>, <\/i>Comissão Europeia<\/span>";
    var total_exter=(epa+egee+ruido+sr+cgstn+ifr_estr)*km_por_mes;
}

//*************** EXTRA DATA - PUBLIC TRANSPORTS ************
//***********************************************************

var racio_car_tp=0.9;     //rácio (preço total dos Transportes públicos)/(custo total do carro), inferior ao qual mostra alternativa de TP
//ratio (total price of public transports)/(total price of car) under which it shows the alternatives of public transports

var racio_outros_tp=0.6;  //rácio (preço total dos Transportes públicos)/(custo total do carro),
//inferior ao qual mostra outras alternativas de TP, para lá do passe mensal (rede expresso, longo curso, etc.)

var percent_taxi=0.2;     //caso a condição acima se aplique, qual a percentagem do valor total que se aloca para viagens de táxi
//in case above condition is met, the budget percentage alocated to taxi, as alternative to car

var taxi_price_per_km=<?echo $TAXI_PRICE_PER_DIST?>;  //preço médio dos táxis por unidade de distância
//average price of taxi per unit distance

var n_pess_familia=document.custo.pessoas_agregado.value;

if(!isNumber(n_pess_familia) || !(parseFloat(n_pess_familia) == parseInt(n_pess_familia, 10)) || n_pess_familia<=0){
    alert("<?echo $EXTRA_DATA1?> - <?echo $INVALID_NBR_PP?>!");
    return;
}

var pmpmpc=document.custo.preco_passe.value; //Preço Médio do Passe Mensal Per Capita

if(!isNumber(pmpmpc) || pmpmpc<0){
    alert("<?echo $EXTRA_DATA1?> - <?echo $ERROR_PASS_AMOUNT?>!");
    return;
}

var display_tp;//variável que dita se mostra alternativa de transportes públicos

if(pmpmpc*n_pess_familia<racio_car_tp*total && pmpmpc!=0) {
    display_tp=true;
} else {
    display_tp=false;
}

if(display_tp) {
    preco_total_tp=pmpmpc*n_pess_familia; //preço total de passes
    var total_altern=preco_total_tp;

    var tp_text, outros_tp_text, taxi_text;

    tp_text="<b><span class=\"p3\"><?echo $PUB_TRANS_TEXT?><\/span><\/b><br><span class=\"p2\"><?echo $FAM_NBR?>: " + n_pess_familia + " <?echo $PERSON_OR_PEOPLE?>"
                + "<br><?echo $PASS_MONTH_AVG?>: " + pmpmpc + "<?echo $CURR_SYMBOL?><\/span>";

    var n_km_taxi, custo_taxi;
    var display_outros_tp; //indica se mostra outros TP para além do passe mensal

    var racio_custocar_caustotp=preco_total_tp/total;
    if(racio_custocar_caustotp>racio_outros_tp) {//caso se mostre outros TP além do passe mensal
        display_outros_tp=false;
        custo_taxi=total-preco_total_tp;
        n_km_taxi=custo_taxi/taxi_price_per_km; //número de km possíveis de fazer de táxi

        total_altern+=custo_taxi;
    } else {
        display_outros_tp=true;
        custo_taxi=total*(1-racio_custocar_caustotp)/2;
        n_km_taxi=custo_taxi/taxi_price_per_km;
        var outros_tp=total*(1-racio_custocar_caustotp)/2; //valor alocado a outros TP, excetuando passe mensal

        total_altern+=custo_taxi+outros_tp;

        outros_tp_text="<b><span class=\"p3\"><?echo $OTHER_PUB_TRANS?><\/span><\/b><br><span class=\"p2\"><?echo $OTHER_PUB_TRANS_DESC?> <\/span>";
    }

        taxi_text="<b><span class=\"p3\"><?echo $TAXI_DESL?><\/span><\/b><br><span class=\"p2\">" + n_km_taxi.toFixed(1) + " <?echo $STD_DIST?> <?echo $ON_TAXI_PAYING?> " + taxi_price_per_km.toFixed(1) + "<?echo $CURR_SYMBOL?>/<?echo $STD_DIST?><\/span>";
}


//imprime

input_object.style.display='none';

var varResult="";
varResult+="<center><table style=\"background:rgb(250, 250, 250);border:solid 1px rgb(180, 180, 180);margin:1;width:98%border-spacing:0px;border-collapse:collapse;\" border=\"1\" cellpadding=\"4\">";

varResult+="<tr><td align=\"center\"><b><span class=\"p3\"><?echo $PRIVATE_COSTS?><\/span><\/b><br><\/td><td width=\"20%\" align=\"center\"><b><span class=\"p3\"><?echo $MONTHLY_AMOUNT?><\/span><\/b><\/td><\/tr>";

varResult+="<tr><td align=\"left\">"+desva_text+"&nbsp;<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+cred_desva_auto_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\"><b><span class=\"p3\"><?echo $INSURANCE?><\/span><\/b><br><span class=\"p2\">"+seguro_text+"<\/span><\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+val_seguro_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+juros_text+"&nbsp;<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+juros_auto_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+inspecao_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+inspecao_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+IUC_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+IUC_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";

varResult+="<tr><td align=\"left\"><b><span class=\"p3\"><?echo $FUEL?><\/span><\/b><br><span class=\"p2\">"+combustiveis_text+"<\/span><\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+val_combustiveis_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+revisoes_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+revisoes_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+reparacoes_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+reparacoes_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\"><b><span class=\"p3\"><?echo $PARKING?><\/span><\/b><\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+parqueamento_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+portagens_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+portagens_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+multas_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+multas_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+lavagens_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+lavagens_por_mes.toFixed(1)+"<\/span><\/td><\/tr>";

varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"left\">"+custos_fixos_text+"<\/td><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\">&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+custos_fixos.toFixed(1)+"<\/span><\/td><\/tr>";
varResult+="<tr><td align=\"left\">"+custos_variav_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+custos_variav.toFixed(1)+"<\/span><\/td><\/tr>";

if(tipo_calc_combustiveis=="km" && (km_por_mes!=0)){
    varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"left\"><b><span class=\"p3\"><?echo $RUN_CP_DIST?><\/span><\/b><\/td><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\"><span class=\"p2\">&nbsp;<?echo $CURR_SYMBOL?>&nbsp;"+(custos_variav/km_por_mes).toFixed(2)+"/<?echo $STD_DIST?> <\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\"><b><span class=\"p3\"><?echo $TOTAL_CP_DIST?><\/span><\/b><\/td><td><span class=\"p2\">&nbsp;<?echo $CURR_SYMBOL?>&nbsp;"+(total/km_por_mes).toFixed(2)+"/<?echo $STD_DIST?> <\/span><\/td><\/tr>";
}

varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"right\"><b><span class=\"p3\"><?echo $WORD_TOTAL_CAP?><\/span><\/b><\/td><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\"><center><b><span class=\"p2\"><?echo $CURR_SYMBOL?>&nbsp;"+total.toFixed(0)+"/<?echo $MONTH?><\/span><\/b><\/center><\/td><\/tr><\/table><\/center>";

//MOSTRA CUSTOS EXTERNOS
if(<?if ($def_cty=="PT") echo 'tipo_calc_combustiveis=="km"'; else echo "false";?>){
    varResult+="<br><center><table style=\"background:rgb(250, 250, 250);border:solid 1px rgb(180, 180, 180);margin:1;width:98%border-spacing:0px;border-collapse:collapse;\" border=\"1\" cellpadding=\"4\">";

    varResult+="<tr><td align=\"center\"><b><span class=\"p3\">Custos externos para o país<\/span><\/b><br><span class=\"p2\">Percorre "+(1*km_por_mes).toFixed(1)+" <?echo $STD_DIST?>/<?echo $MONTH?><\/span><\/td><td width=\"20%\" align=\"center\"><b><span class=\"p3\"><?echo $MONTHLY_AMOUNT?><\/span><\/b><\/td><\/tr>";

    varResult+="<tr><td align=\"left\">"+epa_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(epa*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+egee_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(egee*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+ruido_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(ruido*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+sr_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(sr*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+cgstn_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(cgstn*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+ifr_estr_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+(ifr_estr*km_por_mes).toFixed(1)+"<\/span><\/td><\/tr>";

    varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"right\"><b><span class=\"p3\"><?echo $WORD_TOTAL_CAP?><\/span><\/b><\/td><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\"><center><b><span class=\"p2\"><?echo $CURR_SYMBOL?>&nbsp;"+total_exter.toFixed(0)+"/<?echo $MONTH?><\/span><\/b><\/center><\/td><\/tr>";

    varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"left\" colspan=\"2\">"+ fonte_text +"<\/td><\/tr><\/table><\/center>";
}


//mostra alternativas com TP caso se justifique
if(display_tp) {
    varResult+="<br><center><table style=\"background:rgb(250, 250, 250);border:solid 1px rgb(180, 180, 180);margin:1;width:98%border-spacing:0px;border-collapse:collapse;\" border=\"1\" cellpadding=\"4\">";

    varResult+="<tr><td align=\"center\"><b><span class=\"p3\"><?echo $PUBL_TRA_EQUIV?><\/span><\/b><br><\/td><td width=\"20%\" align=\"center\"><b><span class=\"p3\"><?echo $MONTHLY_AMOUNT?><\/span><\/b><\/td><\/tr>";

    varResult+="<tr><td align=\"left\">"+tp_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+preco_total_tp.toFixed(1)+"<\/span><\/td><\/tr>";
    varResult+="<tr><td align=\"left\">"+taxi_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+custo_taxi.toFixed(1)+"<\/span><\/td><\/tr>";
    if(display_outros_tp) {
        varResult+="<tr><td align=\"left\">"+outros_tp_text+"<\/td><td>&nbsp;<span class=\"p2\"><?echo $CURR_SYMBOL?> "+outros_tp.toFixed(1)+"<\/span><\/td><\/tr>";
    }

    varResult+="<tr><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\" align=\"right\"><b><span class=\"p3\"><?echo $WORD_TOTAL_CAP?><\/span><\/b><\/td><td style=\"border-top-width:2px;border-top-style:solid;border-top-color:black;\"><center><b><span class=\"p2\"><?echo $CURR_SYMBOL?>&nbsp;"+total_altern.toFixed(0)+"/<?echo $MONTH?><\/span><\/b><\/center><\/td><\/tr><\/table><\/center>";
}

result_object.innerHTML=varResult;
result_object.style.display='block';

//evita impressão de gráficos em dispositivos móveis
var temp_width=document.documentElement.clientWidth;
if (temp_width>500) {
    //verifica que a desvalorização é maior ou igual que zero, para imprimir gráfico
    var desvalor_temp;
    if(cred_desva_auto_por_mes<0) {
        desvalor_temp=0;
    } else {
        desvalor_temp=cred_desva_auto_por_mes; }

        chart_width=parseInt(frame_witdh*0.85);
        chart_height=parseInt(chart_width*4/6);

        drawChart(parseFloat(val_seguro_por_mes.toFixed(1)),
        parseFloat(val_combustiveis_por_mes.toFixed(1)),
        parseFloat(desvalor_temp.toFixed(1)),
        parseFloat(juros_auto_por_mes.toFixed(1)),
        parseFloat(inspecao_por_mes.toFixed(1)),
        parseFloat(revisoes_por_mes.toFixed(1)),
        parseFloat(reparacoes_por_mes.toFixed(1)),
        parseFloat(IUC_por_mes.toFixed(1)),
        parseFloat(parqueamento_por_mes.toFixed(1)),
        parseFloat(portagens_por_mes.toFixed(1)),
        parseFloat(multas_por_mes.toFixed(1)),
        parseFloat(lavagens_por_mes.toFixed(1)),
        chart_width,
        chart_height
        );


        chart_width=parseInt(frame_witdh*0.8);
        chart_height=parseInt(chart_width*22/50);

        drawVisualization(parseFloat(custos_fixos.toFixed(1)), parseFloat(custos_variav.toFixed(1)),chart_width,chart_height);

        graph_object.style.display='block';
        chart_object.style.display='block';
    }



    reload_object.style.display='block';
    submit_object.style.display='none';

    if(total >= 150 && meses >6) {
        var text_msg="<div style=\"border-top:rgb(180, 180, 180) 3px solid;\"><br><span class=\"p3\"><?echo $YOUR_CAR_COSTS_YOU?> <b>"+(total*12/100).toFixed(0)*100 + " <?echo $CURR_NAME_PLURAL?><\/b> <?echo $WORD_PER?> <?echo $YEAR?>.<br>";
        text_msg+="<?echo $WITH_THIS_LEVEL_OF_COSTS?> " + meses + " <?echo $MONTHS_POSS?><br><br><center><div style=\"float: center;display: inline-block;padding:2%;font-size:350%;font-weight:bold; width:auto; font-family:Impact; color:red; border-style:solid; border-width:5px\">" + numberWithSpaces((meses*total/100).toFixed(0)*100) + " <?echo $CURR_NAME_BIG_PLURAL?><\/div><\/center><\/span><\/div><br>";
        text_object.innerHTML=text_msg;
        text_object.style.display='block';
    }
}