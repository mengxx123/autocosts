<?php

//scripts that creates several HTML language layout files, stored in /countries
include('./createLangFiles.php');

$htmlStr = "";
$fileName = "index.html";
file_put_contents($fileName, $htmlStr);
ob_start();

$GLOBALS['country'] = "UK"; //test

include_once("../php/functions.php");
include_once("../countries/_list.php");
//loads the correspondent country file
include('../countries/' . $GLOBALS['country'] . '.php');

//removes XX from array
unset($avail_CT['XX']);
$language=mb_substr($lang_CT[$GLOBALS['country']], 0, 2);

//some initializations
$is_logo = false;
$currency_logo = "";
?><!DOCTYPE html>

<head>  
    <meta charset="UTF-8">
    <!--gets the first sentence of variable $INITIAL_TEXT-->
	<meta name="description" content="<?php echo meta_description($INITIAL_TEXT); ?>">
    <meta name="viewport" content="width=device-width">
    <meta name="author" content="Autocosts Org">
    
    <title><?php echo adapt_title($WEB_PAGE_TITLE); ?></title>

    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/flags.css">
    <style>
    #banner_top{
        position: static !important;
    }
    </style>
    
</head>

<body>
    <div id="main_div">
        <div id="banner_top">
            <div id="left_top_div">&nbsp;</div>
            <!--#####################-->
            <div id="header_main_title">
                <div id="main_title">
                    <header>
                        <h1>
                            <?php echo $MAIN_TITLE ?>
                        </h1>
                    </header>
                </div>
            </div>
            <!--## Select country box ##-->
            <div id="country_box">
                <div id="country_box_inline">
                    <div id="banner_flag_div">
                        <div id="banner_flag" class="<?php echo strtolower($GLOBALS['country']) ?> flag"></div>
                    </div>
                    <div id="country_select_div">
                        <select name="country_select" id="country_select" onchange="valueselect(this.value);">
                            <?php 
                                foreach ($avail_CT as $key => $value) {
                                    echo '<option value="'.$key.'"'. 
                                         ($key==$GLOBALS['country']?' selected="selected"':'').'>'.
                                         $value.'</option>';
                                }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div id="container">
            <div id="container_table">

                <!-- div2 = CENTRE layout column-->
                <div id="div2_td">
                    <div id="div2">
                        <form class="roundCorner" id="main_form" enctype="application/x-www-form-urlencoded"
                              action="javascript:void(0);" name="custo">
                            <div id="input_div">
                            </div>
                        </form>
                    </div>
                    
                    <!-- ************* PRINTING divs ***********************
                    ******************************************************-->
                    
                    <!-- ************* Main Table  Section ****************** -->                           
                    <div class="result_section" id="main_table_section">
                        <div class="result_div" id="main_table"></div>
                    </div>
                    <!-- ************* Monthly Costs section **************** -->
                    <div class="result_section" id="monthly_costs_section">
                        <div class="result_section_title" id="monthly_costs_title">
                            <b><?php echo mb_convert_case($AVERAGE_COSTS_PER_TYPE, MB_CASE_UPPER, "UTF-8"); ?>
                            <?php echo ' '.'('.$CURR_NAME_BIG_PLURAL.')'; ?></b>
                        </div>

                        <!-- results tables -->
                        <div class="result_div" id="monthly_costs"></div>
                    </div>
                    <!-- ************* Financial Effort section************** -->
                    <div class="result_section" id="fin_effort_section">
                        <div class="result_section_title" id="fin_effort_title">
                            <b><?php echo mb_convert_case($FINANCIAL_EFFORT, MB_CASE_UPPER, "UTF-8"); ?></b>
                        </div>

                        <div class="result_div" id="fin_effort"></div>
                    </div>
                    <!-- ********* Alternative Costs to Car Costs section **************** -->
                    <div class="result_section" id="alternative_to_carcosts_section">
                        <div class="result_section_title" id="alternative_to_carcosts_title">
                            <b><?php echo mb_convert_case($PUBL_TRA_EQUIV, MB_CASE_UPPER, "UTF-8"); ?></b>
                        </div>
                        <div class="result_div" id="alternative_to_carcosts"></div>
                    </div>
                    <!-- ************* Buttons ****************** -->
                    <div class="result_section" id="exten_costs_section">
                        <div class="result_div" id="extern_costs"></div>
                    </div>
                    <!-- ************* Buttons ****************** -->
                    <div class="result_section" id="buttons_section">
                        <div class="result_div" id="result_buttons_div">
                            <input type="submit" class="button" value="<?php echo $BUTTON_RERUN; ?>" onclick="reload();"/>
                        </div>                               
                    </div>
                    <!-- ************* ********* ************* -->
                    <br>
                </div>
                <!--#######################################################################################-->
            </div>
        </div>
        <br>
    </div>
    <!--jquery.js-->
    <script src="js/jquery/jquery.min.js"></script>

    <!--Define GLOBAL Javascript variables-->
    <script>    
        var Country = '<?php echo $GLOBALS["country"]; ?>';
        //Language code according to ISO_639-1 codes
        var Language = '<?php echo $lang_CT[$GLOBALS['country']]; ?>';
        var Domain_list = <?php echo json_encode($domain_CT); ?>;
        var frame_witdh, public_transp_bool, fin_effort_bool, extern_costs_bool;
        var ResultIsShowing, DescriptionHTML, CalculatedData;
        var RunButtonStr = '<?php echo $BUTTON_RUN; ?>';
        //Google global variables for each service availability
        var IsGoogleCharts = false; //variable that says whether Google Charts JS files are available
        var IsGoogleCaptcha = false; //variable that says whether Google Captcha JS files are available  
        var IsGoogleAnalytics = false; //variable that says whether Google Analytics JS files are available  
    </script>

    <script src="js/coreFunctions.js"></script>
    <script src="js/conversionFunctions.js"></script>
    <script src="js/get_data.js"></script>
    <script src="js/documentFunctions.js"></script>
    <script src="js/initialize.js"></script>
    
    <script>
        $.getScript("countries/PT_validateForm.js");
        $.getScript("countries/PT_print_results.js");
        
        function Run1(){ Run2();}
        
    </script>
    
</body>

</html>

<?php 
//  Return the contents of the output buffer
$htmlStr = ob_get_contents();
// Clean (erase) the output buffer and turn off output buffering
ob_end_clean(); 
// Write final string to file
file_put_contents($fileName, $htmlStr);
?>