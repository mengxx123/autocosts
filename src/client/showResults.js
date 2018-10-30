/************************************************
**                                             **
**              AUTOCOSTS.INFO                 **
**      the automobile costs calculator        **
**                                             **
************************************************/

/*File with functions that are used to print the final result*/

//function that is run when user clicks "run/calculate"
function Run2(callback){

    //test if the form user inputs are correct
    if (!isReadyToCalc()){ 
        return false;
    }        
    
    //for each form part gets object with content
    var f1 = get_form_part1();
    var f2 = get_form_part2();
    var f3 = get_form_part3();

    //country object with country specific variables
    var countryObj = {
        currency: WORDS.curr_code,
        distance_std: WORDS.distance_std_option,
        fuel_efficiency_std: WORDS.fuel_efficiency_std_option,
        fuel_price_volume_std: WORDS.fuel_price_volume_std,
        taxi_price: WORDS.taxi_price_per_dist
    };

    //calculate costs
    var calculatedData = calculate_costs(f1, f2, f3, countryObj);
        
    //get Uber data if applicable
    if(calculatedData.alternative_to_car_costs_calculated && SWITCHES.uber){
        calculatedData.uber = get_uber(UBER_API, calculatedData, countryObj); 
    } 
    
    CALCULATED_DATA = calculatedData; //assigns to global variable
    
    //from complex object with hierarchies, flattens to simple object
    //see for more info: https://github.com/hughsk/flat
    var flattenedData = flatten(calculatedData, {delimiter:"_"}); 
    
    //console.log(flattenedData);       

    showResults(f1, f2, f3, calculatedData, flattenedData, countryObj);
        
    $("*").promise().done(function(){    
        
        //global variable indicating the results are being shown
        DISPLAY.result.isShowing = true;        
        
        //calls the callback() if it's a function
        if (typeof callback === 'function'){
            callback();
        }
    });

    return true;
}

function showResults(f1, f2, f3, calculatedData, flattenedData, countryObj){
    //console.log(JSON.stringify(calculatedData, null, 4));
    
    $("#form").hide(); 
    
    //The first three boxes on the top
    //if financial effort was not calculated, does not show doughnut chart
    //on the third box, and adapt the three boxes css classes
    if(calculatedData.fin_effort_calculated && SWITCHES.charts){ 
        drawDoughnutFinEffortChart(calculatedData);
        //shows third box where the financial effort doughnut chart appears
        $("#results #info-boxes .info-box.box-3").show();
        $("#results #info-boxes .info-box").removeClass("two-boxes").addClass("three-boxes");        
    }
    else{
        //hides third box where the financial effort doughnut chart appears
        $("#results #info-boxes .info-box.box-3").hide();
        $("#results #info-boxes .info-box").removeClass("three-boxes").addClass("two-boxes");       
    } 
                     
    //it needs to show also 1/2 of Maintenance Costs
    flattenedData.monthly_costs_halfOfMaintenance = flattenedData.monthly_costs_maintenance / 2;

    setCalculatedDataToHTML(flattenedData);                
    
    setPeriodicCosts(calculatedData, "month");
    setPeriodicCostsDetails(f1, f2, f3, calculatedData); //the details on the dropdown boxes                         
    
    //SWITCHES are frozen/const object in Globals.js, so no need to show elements when SWITCHES.charts is true
    //since these elements are set tp be shown in css by default, just need to hide in case is false
    if(SWITCHES.charts){            
        drawCostsBarsChart(calculatedData, "month");
        drawCostsDoughnutChart(calculatedData, "month");
    }
    else {
        $("#results .costs-doughnut-chart, #results .costs-bars-chart-stats, #results .stats-references").hide();             
    }

    //Financial Effort 
    if(calculatedData.fin_effort_calculated){            
        setFinancialEffortDetails(f1, f2, f3, calculatedData);
        
        //shows financial effort section 
        $("#results #financial-effort").show();
        DISPLAY.result.fin_effort = true; //global variable 
        
        if(SWITCHES.charts){                
            drawFinEffortChart(calculatedData);
        }
        else{
            $("#financial-effort .graph").hide();
            $("#financial-effort .values.box").css("width", "40%").css("float", "none");
        }
    }
    else {
        //hides financial effort section
        $("#results #financial-effort").hide();
        DISPLAY.result.fin_effort = false;     
    } 

    //Equivalent transport costs
    if(calculatedData.alternative_to_car_costs_calculated){            
        setEquivTransportCostsDetails(f1, f2, f3, calculatedData);
        
        $("#results #equivalent-transport-costs").show();
        DISPLAY.result.public_transports = DISPLAY.result.uber = true;
        
        if(SWITCHES.charts){
            drawAlterToCarChart(calculatedData);
        }
        else{
            $("#equivalent-transport-costs .graph").hide();
            $("#equivalent-transport-costs .values.box").css("margin", "auto 2%").css("float", "none");            
        }
    }
    else {
        $("#results #equivalent-transport-costs").hide();
        DISPLAY.result.public_transports = DISPLAY.result.uber = false;
    } 

    setClassAccordionHandler();
        
    $("#results").show();
    
}

//scans all calculatedData flattened to flattenedData and assigns each result value to respective HTML element  
function setCalculatedDataToHTML(flattenedData){
    
    for (var key in flattenedData){
        var $i = $("#results ." + key);  
        //check that the element with that class exists in the html page
        //and that the element is valid in the array of calculated data
        if(flattenedData.hasOwnProperty(key) && $i.length &&  flattenedData[key]){

            //organising text or adding extra text according to classes: toFixedN, currency, hours, distance, percentage
            toFixedN = getToFixedNumFromClasses($i); 
            amount = flattenedData[key].toFixed(toFixedN);

            if($i.hasClass("currency")){
                numToShow = currencyShow(amount);
            }
            else if($i.hasClass("hours")){
                numToShow = amount + " " + WORDS.hour_abbr; 
            }
            else if($i.hasClass("distance")){
                numToShow = amount + " " + getDistanceOptStrShort(); 
            }
            else if($i.hasClass("percentage")){
                numToShow = amount + "&#37;"; //percentage symbol 
            }                
            else{
                numToShow = amount;
            }

            $i.html(numToShow);
        }
    }
}

//The first section of results page, showing the monthly/trimester/semester/yearly costs
function setPeriodicCosts(calculatedData, period){
    
    var numMonths, strPeriod;
    var currSymb = WORDS.curr_symbol;
    
    switch(period){
        case "month" :
            numMonths = 1;
            strPeriod = WORDS.month;
            break;
        case "trimester" :
            numMonths = 3;
            strPeriod = WORDS.trimester;
            break;
        case "semester" :
            numMonths = 6;
            strPeriod = WORDS.semester;
            break;
        case "year" :
            numMonths = 12;
            strPeriod = WORDS.year;
            break;
        default:
            console.error("Period not valid " + period);
    }

    //main info box total costs    
    $("#results #info-boxes .total_costs_per_period").html((calculatedData.total_costs_month*numMonths).toFixed(0));
    
    //section h2 title
    $("#results #avg-periodic-cost .costs_per_type").html(WORDS.costs + " " + WORDS.word_per + strPeriod);
    
    var $htmlEl = $("#results #avg-periodic-cost .three-boxes");
    
    $htmlEl.find(".average_costs_per_type").html(WORDS.word_per + strPeriod);
    
    //sets the periodic costs according to period on span elements with class starting with "periodic_costs"
    $htmlEl.find("span").each(function(){
        var classNames = $(this).attr("class");
        //check if there is any class that contains expression "periodic_costs"
        if (classNames && classNames.indexOf("periodic_costs") >= 0){
            var classesArr = classNames.split(" ");
            for (var i=0; i<classesArr.length; i++){
                var className, costItem, val;
                if (classesArr[i].indexOf("periodic_costs") >= 0){
                    className = classesArr[i];
                    costItem = className.replace("periodic_costs_", "");
                    val = calculatedData.monthly_costs[costItem] * numMonths;
                    $(this).html(currSymb + " " + val.toFixed(1));
                }
            }
        }
                              
    });
    
    //extra items
    $htmlEl.find(".periodic_costs_halfOfMaintenance").html(currSymb + " " + (calculatedData.monthly_costs.maintenance/2*numMonths).toFixed(1));
    
    $htmlEl.find(".periodic_costs_total_standing_costs").html(currSymb + " " + (calculatedData.total_standing_costs_month*numMonths).toFixed(2));
    $htmlEl.find(".periodic_costs_total_running_costs").html(currSymb + " " + (calculatedData.total_running_costs_month*numMonths).toFixed(2));
    $htmlEl.find(".periodic_costs_total_costs").html(currSymb + " " + (calculatedData.total_costs_month*numMonths).toFixed(2));
    
}

function setPeriodicCostsDetails(f1, f2, f3, calculatedData){

    //html element in which the costs details will be added 
    var htmlEl = "#results #avg-periodic-cost .three-boxes";
    
    //remove existing <ul> if they exist, to add new ones
    $(htmlEl + " ul").remove();
    
    //add Cost <ul> for each cost details, for example add <ul> to div with class "fuel_details"
    for (var cost in calculatedData.monthly_costs) {
        if (!calculatedData.monthly_costs.hasOwnProperty(cost)) {
            continue;
        }
        
        if($(htmlEl + " ." + cost + "_details").length){            
            $(htmlEl + " ." + cost + "_details").append($("<ul>"));
        }
    }
    
    var addLiElm = function(costItem, text, text2){
        var $item = $(htmlEl + " ." + costItem + "_details ul");
        if(typeof text2 === 'undefined' || text2 === null){
            $item.append($("<li>").text(text));
        }
        else{
            $item.append($("<li>").text(text + ": " + text2));
        }    
    };
    
    //Depreciation
    if (calculatedData.age_months === 0) {
        addLiElm("depreciation", WORDS.error_depreciation_new_car);
    } 
    else {        
        addLiElm("depreciation", WORDS.aq_value, currencyShow(f1.auto_initial_cost));
        addLiElm("depreciation", WORDS.final_value, currencyShow(f1.auto_final_cost));
        addLiElm("depreciation", WORDS.period_own, calculatedData.age_months + " " + WORDS.months);
        addLiElm("depreciation", "(" + currencyShow(f1.auto_initial_cost) + "-" + 
                                       currencyShow(f1.auto_final_cost) + ")/" + calculatedData.age_months + " " + WORDS.months);
    }

    //Insurance
    switch(f1.insurance_type){

        case "mensal":
            addLiElm("insurance", calculatedData.monthly_costs.insurance + " " + WORDS.curr_name_plural + " " + 
                                  WORDS.word_per + " " + WORDS.month);
            break;
        case "trimestral":
            addLiElm("insurance", f1.insurance_value + " " + WORDS.curr_name_plural + " " + 
                                  WORDS.word_per + " " + WORDS.trimester);
            break;
        case "semestral":
            addLiElm("insurance", f1.insurance_value + " " + WORDS.curr_name_plural + " " + 
                                  WORDS.word_per + " " + WORDS.semester);
            break;
        case "anual":
            addLiElm("insurance", f1.insurance_value + " " + WORDS.curr_name_plural + " " + 
                                  WORDS.word_per + " " + WORDS.year);
            break;
    }

    //Credit interests
    if(f1.cred_auto_s_n == "true") {
        
        addLiElm("credit", WORDS.credit_loan2, currencyShow(f1.credit_amount));
        addLiElm("credit", WORDS.credit_period, f1.credit_period + " " + WORDS.months);
        addLiElm("credit", WORDS.credit_instalment, currencyShow(f1.credit_value_p_month));
        addLiElm("credit", WORDS.credit_residual_value1, currencyShow(f1.credit_residual_value));

        addLiElm("credit", WORDS.credit_total_interests, currencyShow(calculatedData.total_interests)); 
        addLiElm("credit", "(" + calculatedData.month_cred + "*" + f1.credit_value_p_month + ")+" + 
                           f1.credit_residual_value + "-" + f1.credit_amount);

        if(calculatedData.age_months >= calculatedData.month_cred){
            addLiElm("credit", WORDS.credit_interests_month + ": " +
                               currencyShow(calculatedData.monthly_costs.credit.toFixed(2)));
        }        
    }

    //Inspection
    if (f1.nmr_times_inspec !== 0){        
        addLiElm("inspection", f1.nmr_times_inspec + " " + WORDS.times_costing + " " + f1.inspec_price + 
                               " " + WORDS.curr_name_plural + " " + WORDS.each_one_during + " " + 
                               calculatedData.age_months + " " + WORDS.months);
    }
    
    //Taxes
    addLiElm("car_tax", f1.car_tax + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);
    
    //Fuel    
    switch(f2.type_calc_fuel){
        case "km":
            if (f2.take_car_to_job == "false"){
                switch(calculatedData.fuel_period_km)
                {
                    case "1":
                        addLiElm("fuel", f2.distance + " " + WORDS.std_dist + " " + WORDS.word_per + " " + WORDS.month);
                        break;
                    case "2":
                        addLiElm("fuel", f2.distance + " " + WORDS.dist_each_two_months);
                        break;
                    case "3":
                        addLiElm("fuel", f2.distance + " " + WORDS.std_dist + " " + WORDS.word_per + " " + WORDS.trimester);
                        break;
                    case "4":
                        addLiElm("fuel", f2.distance + " " + WORDS.std_dist + " " + WORDS.word_per + " " + WORDS.semester);
                        break;
                    case "5":
                        addLiElm("fuel", f2.distance + " " + WORDS.std_dist + " " + WORDS.word_per + " " + WORDS.year);
                        break;
                }
                addLiElm("fuel", WORDS.fuel_car_eff, f2.car_consumption + " " + WORDS.std_fuel_calc);
                addLiElm("fuel", WORDS.fuel_price1, currencyShow(f2.fuel_price) + "/" + WORDS.std_volume_short);
            }
            else{
                addLiElm("fuel", f2.days_p_week + " " + WORDS.fuel_job_calc1);
                addLiElm("fuel", WORDS.you_drive + " " + f2.distance_home2job + " " + WORDS.fuel_dist_home_job1);
                addLiElm("fuel", WORDS.you_drive + " " + f2.distance_weekend + " " + WORDS.fuel_dist_no_job1);
                addLiElm("fuel", WORDS.you_drive_tottaly_avg + " " + calculatedData.distance_per_month.toFixed(1) + " " + 
                                 WORDS.std_dist + " " + WORDS.word_per + " " + WORDS.month + " (~30.5 " + WORDS.days + ")");
                addLiElm("fuel", WORDS.fuel_car_eff, f2.car_consumption + " " + WORDS.std_fuel_calc);
                addLiElm("fuel", WORDS.fuel_price, currencyShow(f2.fuel_price) + "/" + WORDS.std_volume_short);
            }
            break;
            
        case "euros":
            switch(calculatedData.fuel_cost_period)
            {
                case "1":
                    addLiElm("fuel", f2.fuel_money + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.month);
                    break;
                case "2":
                    addLiElm("fuel", f2.fuel_money + " " + WORDS.dist_each_two_months);
                    break;
                case "3":
                    addLiElm("fuel", f2.fuel_money + " " + WORDS.curr_name_plural + " " + 
                             WORDS.word_per + " " + WORDS.trimester);
                    break;
                case "4":
                    addLiElm("fuel", f2.fuel_money + " " + WORDS.curr_name_plural + " " + 
                             WORDS.word_per + " " + WORDS.semester);
                    break;
                case "5":
                    addLiElm("fuel", f2.fuel_money + " " + WORDS.curr_name_plural + " " + 
                             WORDS.word_per + " " + WORDS.year);
                    break;
            }
            break;
    }
    
    //Maintenance    
    addLiElm("maintenance", f2.maintenance + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);

    //Repairs
    addLiElm("repairs_improv", f2.repairs + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);

    //Tolls
    if(f2.type_calc_tolls == "false") {
        switch(calculatedData.tolls_period) {
            case "1":
                addLiElm("tolls", f2.tolls + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.month);
                break;
            case "2":
                addLiElm("tolls", f2.tolls + " " + WORDS.curr_name_plural + " " + 
                                  WORDS.words_per_each + " " + WORDS.two_months);
                break;
            case "3":
                addLiElm("tolls", f2.tolls + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.trimester);
                break;
            case "4":
                addLiElm("tolls", f2.tolls + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.semester);
                break;
            case "5":
                addLiElm("tolls", f2.tolls + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);
                break;
        }
    }
    else{
        addLiElm("tolls", f2.price_tolls_p_day + " " + WORDS.curr_name_plural + " " + 
                          WORDS.during + " " + f2.tolls_days_p_month + " " + WORDS.days + " " + 
                          WORDS.word_per + " " + WORDS.month);
    }    

    //Fines
    switch(calculatedData.fines_period) {
        case "1":
            addLiElm("fines", f2.fines + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.month);
            break;
        case "2":
            addLiElm("fines", f2.fines + " " + WORDS.curr_name_plural + " " + WORDS.words_per_each + " " + WORDS.two_months);
            break;
        case "3":
            addLiElm("fines", f2.fines + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.trimester);
            break;
        case "4":
            addLiElm("fines", f2.fines + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.semester);
            break;
        case "5":
            addLiElm("fines", f2.fines + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);
            break;
        }

    //Washing    
    switch(calculatedData.washing_period) {
        case "1":
            addLiElm("washing", f2.washing + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.month);
            break;
        case "2":
            addLiElm("washing", f2.washing + " " + WORDS.curr_name_plural + " " + 
                                WORDS.words_per_each + " " + WORDS.two_months);
            break;
        case "3":
            addLiElm("washing", f2.washing + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.trimester);
            break;
        case "4":
            addLiElm("washing", f2.washing + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.semester);
            break;
        case "5":
            addLiElm("washing", f2.washing + " " + WORDS.curr_name_plural + " " + WORDS.word_per + " " + WORDS.year);
            break;
        }    
}

function setFinancialEffortDetails(f1, f2, f3, calculatedData){

    //html element in which the costs details will be added 
    var htmlEl = "#results #financial-effort .values"; 
    
    //remove existing <ul> if they exist, to add new ones
    $(htmlEl + " ul").remove();
    
    //add <ul> for each item details in financial effort
    if(!$(htmlEl + " .panel ul").length){
        $(htmlEl + " .panel").append($("<ul>"));
    }
    
    var addLiElm = function(item, text, text2){
        var $item = $(htmlEl + " ." + item + "_details ul");
        if(typeof text2 === 'undefined' || text2 === null){
            $item.append($("<li>").text(text));
        }
        else{
            $item.append($("<li>").text(text + ": " + text2));
        }
    };  
    
    
    //income
    switch(f3.income_type){
        case 'year':
            addLiElm("income", WORDS.net_income_per + " " + WORDS.year, currencyShow(calculatedData.fin_effort.income));
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.month, currencyShow(calculatedData.fin_effort.aver_income_per_month.toFixed(1)));
            break;
        
        case 'month':
            addLiElm("income", WORDS.net_income_per + " " + WORDS.month, currencyShow(calculatedData.fin_effort.income));
            addLiElm("income", WORDS.number_of_months, calculatedData.fin_effort.income_per_type);
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.month, currencyShow(calculatedData.fin_effort.aver_income_per_month.toFixed(1)));
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.year, currencyShow(calculatedData.fin_effort.income_per_year.toFixed(1)));            
            break;
        
        case 'week':
            addLiElm("income", WORDS.net_income_per + " " + WORDS.week, currencyShow(calculatedData.fin_effort.income));
            addLiElm("income", WORDS.number_of_weeks, calculatedData.fin_effort.income_per_type);
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.month, currencyShow(calculatedData.fin_effort.aver_income_per_month.toFixed(1)));
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.year, currencyShow(calculatedData.fin_effort.income_per_year.toFixed(1)));            
            break;
        
        case 'hour':
            addLiElm("income", WORDS.net_income_per + " " + WORDS.hour, currencyShow(calculatedData.fin_effort.income));
            addLiElm("income", WORDS.number_of_hours, calculatedData.fin_effort.income_hours_per_week + " " + WORDS.hour_abbr);
            addLiElm("income", WORDS.number_of_weeks, calculatedData.fin_effort.income_per_type);
            addLiElm("income", WORDS.average_net_income + " " + WORDS.month, currencyShow(calculatedData.fin_effort.aver_income_per_month.toFixed(1)));
            addLiElm("income", WORDS.average_net_income_per + " " + WORDS.year, currencyShow(calculatedData.fin_effort.income_per_year.toFixed(1)));                     
            break;
    }
    
    
    //working time
    if(f3.income_type != 'hour'){
        if(f3.is_working_time == 'true'){
            
            addLiElm("working_time", WORDS.hours_per + " " + WORDS.week, calculatedData.fin_effort.time_hours_per_week + " " + WORDS.hour_abbr);
            addLiElm("working_time", WORDS.months_per + " " + WORDS.year, calculatedData.fin_effort.time_month_per_year);
            addLiElm("working_time", WORDS.average_working_hours_per + " " + WORDS.month, 
                     calculatedData.fin_effort.aver_work_time_per_m.toFixed(1) + " " + WORDS.hour_abbr);
            addLiElm("working_time", WORDS.working_hours_per + " " + WORDS.year, 
                     calculatedData.fin_effort.work_hours_per_y.toFixed(1) + " " + WORDS.hour_abbr);            
        }
        else{
            addLiElm("working_time",  WORDS.working_time_message);            
        }
    }
    addLiElm("working_time", WORDS.average_net_income_per + " " + WORDS.hour, currencyShow(calculatedData.fin_effort.aver_income_per_hour.toFixed(1)));
    
    
    //distance    
    if((f2.type_calc_fuel != 'km' && f3.drive_to_work == 'true') || (f2.type_calc_fuel != 'km' && f2.take_car_to_job == 'true')){
        
        addLiElm("distance", WORDS.dist_home_job, parseInt(f3.dist_home_job).toFixed(1) + " " + WORDS.std_dist);
        addLiElm("distance", WORDS.days_drive_job, f3.drive_to_work_days_per_week + " " + WORDS.days);
        addLiElm("distance", WORDS.dist_jorney_weekend, parseInt(f3.journey_weekend).toFixed(1) + " " + WORDS.std_dist);
        addLiElm("distance", WORDS.average_dist_per_week, calculatedData.driving_distance.aver_drive_per_week.toFixed(1) + " " + WORDS.std_dist);                
    }
    
    addLiElm("distance", WORDS.you_drive_per + " " + WORDS.month, calculatedData.distance_per_month.toFixed(1) + " " + WORDS.std_dist);
    addLiElm("distance", WORDS.you_drive_per + " " + WORDS.year, calculatedData.driving_distance.drive_per_year.toFixed(1) + " " + WORDS.std_dist);             

    
    //time spent in driving
    if(f3.drive_to_work == 'true' || f2.take_car_to_job == 'true'){
        addLiElm("time_spent_in_driving", WORDS.minutes_home_job, f3.time_home_job + " " + WORDS.min);
        addLiElm("time_spent_in_driving", WORDS.days_drive_to_job, f3.drive_to_work_days_per_week + " " + WORDS.days);
        addLiElm("time_spent_in_driving", WORDS.time_drive_weekend, f3.time_weekend + " " + WORDS.min);
        addLiElm("time_spent_in_driving", WORDS.minutes_drive_per + " " + WORDS.week, calculatedData.time_spent_driving.min_drive_per_week + " " + WORDS.min);        
    }
    else{
        addLiElm("time_spent_in_driving", WORDS.minutes_drive_per + " " + WORDS.day,  f3.min_drive_per_day + " " + WORDS.min);
        addLiElm("time_spent_in_driving", WORDS.days_drive_per_month, f3.days_drive_per_month + " " + WORDS.days);
    }

    addLiElm("time_spent_in_driving", WORDS.hours_drive_per + " " + WORDS.month, 
             calculatedData.time_spent_driving.hours_drive_per_month.toFixed(1) + " " + WORDS.hour_abbr);
    addLiElm("time_spent_in_driving", WORDS.hours_drive_per + " " + WORDS.year, 
             calculatedData.time_spent_driving.hours_drive_per_year.toFixed(1) + " " + WORDS.hour_abbr);   
    
    
    //financial effort
    addLiElm("financial_effort", WORDS.total_costs_per_year, currencyShow(calculatedData.fin_effort.total_costs_year.toFixed(1)));
    addLiElm("financial_effort", WORDS.hours_to_afford_car, calculatedData.fin_effort.hours_per_year_to_afford_car.toFixed(1) + " " + WORDS.hour_abbr);
    addLiElm("financial_effort", WORDS.months_to_afford_car, calculatedData.fin_effort.month_per_year_to_afford_car.toFixed(2));
    addLiElm("financial_effort", WORDS.days_car_paid, Math.ceil(calculatedData.fin_effort.days_car_paid) + " " + WORDS.days);
    addLiElm("financial_effort", WORDS.aver_yearly + " " + WORDS.kinetic_speed, calculatedData.kinetic_speed.toFixed(1) + " " + WORDS.std_dist);
    addLiElm("financial_effort", WORDS.aver_yearly + WORDS.virtual_speed, calculatedData.virtual_speed.toFixed(1) + " " + WORDS.std_dist);
    
}

function setEquivTransportCostsDetails(f1, f2, f3, calculatedData){        

    //html element in which the costs details will be added 
    var htmlEl = "#results #equivalent-transport-costs .values";     
    
    //remove existing <ul> if they exist, to add new ones
    $(htmlEl + " ul").remove();
    
    //add <ul> for each item details in  equivalent transport costs
    if (!$(htmlEl + " .panel ul").length){
        $(htmlEl + " .panel").append($("<ul>"));
    }
    
    var addLiElm = function(item, text, text2){
        var $item = $(htmlEl + " ." + item + "_details ul");
        if(typeof text2 === 'undefined' || text2 === null){
            $item.append($("<li>").text(text));
        }
        else{
            $item.append($("<li>").text(text + ": " + text2));
        }
    };  
     
    //Public transports more taxi
    if(calculatedData.public_transports.display_pt()) {

        DISPLAY.result.public_transports = true; //global variable
        
        addLiElm("public_transports", WORDS.fam_nbr, f3.n_pess_familia + " " + WORDS.person_or_people);              
        addLiElm("public_transports", WORDS.pass_month_avg, currencyShow(f3.monthly_pass_cost));        

        addLiElm("taxi", calculatedData.public_transports.km_by_taxi.toFixed(1) + " " + WORDS.std_dist + " " + WORDS.on_taxi_paying + " " + 
            currencyShow(calculatedData.public_transports.taxi_price_per_km.toFixed(1)) + "/" + WORDS.std_dist); 
        
        if(calculatedData.public_transports.display_other_pt){
            addLiElm("other_pub_trans", WORDS.other_pub_trans_desc); 
        }
    }
    else{
        DISPLAY.result.public_transports = false; //global variable
    }

    //UBER
    var calculatedUber = calculatedData.uber;
    
    if(SWITCHES.uber && calculatedUber && !$.isEmptyObject(calculatedUber)){
        
        $("#equivalent-transport-costs .uber").show();
        DISPLAY.result.uber = true; //says uber table is to be printed; global variable                

        //in which driver can replace every km by uber
        //the remaining money is applied to public transport
        if(calculatedUber.result_type == 1){                         
            
            addLiElm("uber", "UBER - " + WORDS.costs + " " + WORDS.word_per + " " +  WORDS.std_dist_full, 
                    currencyShow(calculatedUber.ucd.toFixed(2)) + "/" + WORDS.std_dist);
            addLiElm("uber", "UBER - " + WORDS.costs + " " + WORDS.word_per + " " +  WORDS.minutes, 
                    currencyShow(calculatedUber.ucm.toFixed(2)) + "/" + WORDS.min);
            addLiElm("uber", WORDS.fuel_dist + " " + WORDS.word_per + " " + WORDS.month, calculatedUber.dpm.toFixed(0) + " " + WORDS.std_dist_full);
            addLiElm("uber", WORDS.minutes_drive_per + " " + WORDS.month, calculatedUber.mdpm.toFixed(0) + " " + WORDS.minutes);                         
            
            addLiElm("other_pub_trans_for_uber", WORDS.other_pub_trans_desc); 
        }

        //the case where uber equivalent is more expensive
        //the driver shall spend the equivalent car money in public transports and the remaining in uber
        else if(calculatedUber.result_type == 2){
                        
            addLiElm("uber", "UBER - " + WORDS.costs + " " + WORDS.word_per + " " + WORDS.std_dist_full,
                    currencyShow(calculatedUber.ucd.toFixed(2)) + "/" + WORDS.std_dist);
            addLiElm("uber", "UBER - " + WORDS.costs + " " + WORDS.word_per + " " + WORDS.minutes,
                    currencyShow(calculatedUber.ucm.toFixed(2)) + "/" + WORDS.min);
            addLiElm("uber", WORDS.kinetic_speed_title, calculatedData.kinetic_speed.toFixed(2) + " " + WORDS.std_dist + "/" + WORDS.hour_abbr);
            addLiElm("uber", "UBER - " + WORDS.std_dist_full + " " + WORDS.word_per + " " + WORDS.month, 
                    calculatedUber.dist_uber.toFixed(0) + " " + WORDS.std_dist_full);
            addLiElm("uber", "UBER: " + WORDS.costs + " - " + WORDS.word_total_cap, currencyShow(calculatedUber.delta.toFixed(0))); 
                        
            addLiElm("other_pub_trans_for_uber", WORDS.fam_nbr, f3.n_pess_familia + " " + WORDS.person_or_people);
            addLiElm("other_pub_trans_for_uber", WORDS.pass_month_avg, currencyShow(f3.monthly_pass_cost)); 
        }
    }
    else{
        $("#equivalent-transport-costs .uber").hide();
        DISPLAY.result.uber = false; //says uber table is not to be printed; global variable
    }
    
}

//flatten object, that is, from an Object composed by elements in a Object's tree, returns simple list Object
//i.e., from complex object with hierarchies, flattens to simple list Object
function flatten(target, opts) {
    opts = opts || {};

    var delimiter = opts.delimiter || '.';
    var maxDepth = opts.maxDepth;
    var output = {};

    function step (object, prev, currentDepth) {
        currentDepth = currentDepth || 1;
        Object.keys(object).forEach(function (key) {
            var value = object[key];
            var isarray = opts.safe && Array.isArray(value);
            var type = Object.prototype.toString.call(value);
            var isbuffer = isBuffer(value);
            var isobject = (type === '[object Object]' || type === '[object Array]');

            var newKey = prev ? prev + delimiter + key : key;

            if (!isarray && !isbuffer && isobject && Object.keys(value).length &&
                (!opts.maxDepth || currentDepth < maxDepth)) {

                return step(value, newKey, currentDepth + 1);
            }

            output[newKey] = value;
        });
    }
    
    function isBuffer (obj) {
        return obj != null && obj.constructor != null &&
               typeof obj.constructor.isBuffer === 'function' && 
               obj.constructor.isBuffer(obj);
    }    

    step(target);

    return output;
}

//get from element with class in the set of toFixed0, toFixed1, toFixed2, etc.
//respectively the integers 0, 1, 2, etc.  
function getToFixedNumFromClasses($this){        
    
    if(!$this.length){
        return 0;
    }
    
    var toFixedN;
    
    //gets all classes from $fieldHead 
    var classList = $this.attr('class').split(/\s+/);
    $.each(classList, function(index, item) {
        //if there is a class which contains the string "field"?
        if (item.indexOf("toFixed") >= 0) {
            toFixedN = item;
        }
    });
    
    if(!toFixedN){
        return 0; //default
    }

    return parseInt(toFixedN.replace("toFixed", ""), 10);
}

//Click handlers for class "accordion", which has further details  of costs items
var setClassAccordionHandler = (function(){
    
    var wasSet = false;
    
    var exec = function(){         
        if(!wasSet){
            var i, acc = document.getElementsByClassName("accordion");

            for (i = 0; i < acc.length; i++) {
                acc[i].addEventListener("click", function() {
                    this.classList.toggle("active");
                    var panel = this.nextElementSibling;

                    if (panel.style.maxHeight){
                        panel.style.maxHeight = null;
                    } 
                    else {
                        panel.style.maxHeight = panel.scrollHeight + "px";
                    } 
                });
            }    

            wasSet = true;
        }
    };
    
    return exec;
}());