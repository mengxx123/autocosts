//Node file which runs server side with a cron functions 
//the file populating the average DB table

//includes
var fs = require('fs');
eval(fs.readFileSync('../js/conversionFunctions.js')+'');
eval(fs.readFileSync('../js/coreFunctions.js')+'');
eval(fs.readFileSync('../js/get_data.js')+'');
eval(fs.readFileSync('./statsFunctions.js')+'');
//include credentials object
eval(fs.readFileSync('./credentials.js')+'');
var login_UserInputDB = get_DBcredentials();

//module to allow to execute the queries in series
var async      = require('async');
//module to get info from DB
var mysql      = require('mysql');

//database variable
var db; 

var countries = [];       //array of objects with countries information
var unique_users = [];    //array of objects having unique_users IDs and respective countries 
var AllUserInputDb = [];  //array of objects with all the data from the inputs users DB
var queryInsert = "";     //string to where all the average data will be inserted

//string with the date DD/MM/YYYY
var d = new Date();
var date_string = "'"+d.getFullYear().toString()+"-"+(d.getMonth()+1).toString()+"-"+d.getDate().toString()+"'";
//console.log(date_string);

//method that forces several methods to run synchronously
async.series([

    //get the set of different countries and set them in array countries[]
    function(callback) {
        console.log("Get the set of different countries");
        db = mysql.createConnection(login_UserInputDB);
        db.connect();
        db.query('SELECT * FROM country_specs', function(err, results, fields) {
            if (err) return callback(err);
            
            //Check that a user was found
            for (var i=0; i<results.length; i++){
                if (results[i].Country){
                    countries.push(results[i]);
                }
            }
            //console.log(countries);
            db.end();        
            callback();
        });
    },
    
    //Get users unique ID
    function(callback) {        
        console.log("Get users unique IDs");
        db = mysql.createConnection(login_UserInputDB);
        db.connect();
        db.query('SELECT DISTINCT uuid_client, country FROM users_insertions', function(err, results, fields) {
            if (err) return callback(err);
            for (var i=0; i<results.length; i++){
                unique_users.push(results[i]);
            }
            //console.log(unique_users);
            db.end();
            callback();
        });
    },
    
    //Get all data from users input DB
    function(callback) {        
        console.log("Get all data from users input DB");
        db = mysql.createConnection(login_UserInputDB);
        db.connect();
        db.query('SELECT * FROM users_insertions', function(err, results, fields) {
            if (err) return callback(err);

            for (var i=0; i<results.length; i++){
                AllUserInputDb.push(results[i]);
            }
            //console.log(AllUserInputDb);
            db.end();
            callback();
        });
    },
    
    //calculate data and builds query
    function(callback) {
        console.log("Calculate data and builds query");
        var country_users = []; //array with unique users for one specific country
        var country_data  = []; //array where all data for one specific country is inserted
                
        //query header
        queryInsert += "INSERT INTO monthly_costs_statistics (\
            country,\
            Date_of_calculation,\
            \
            Depreciation,\
            Insurance,\
            Loan_interests,\
            Inspection,\
            Car_tax,\
            Maintenance,\
            Fuel,\
            Repairs,\
            Parking,\
            Tolls,\
            Fines,\
            Washing,\
            \
            standing_costs,\
            running_costs,\
            total_costs,\
            \
            running_costs_dist,\
            total_costs_dist,\
            \
            kinetic_speed,\
            virtual_speed,\
            \
            total_costs_year,\
            \
            users_counter)\
            \
            VALUES ";
        
        //builds the query to insert all the vaules for each country
        //sql query:... VALUES (PT, value1, value2,...),(BR, value1, value2,...),etc.
        for (var i=0; i<countries.length; i++){ //
            //console.log("Country: " + countries[i].Country);

            country_users = []; //array will be empty
            country_data  = []; //array will be empty
        
            for (var j=0; j<unique_users.length; j++){                
                    if (unique_users[j].country==countries[i].Country)
                        country_users.push(unique_users[j]);
                }
            //console.log(country_users);
            for (var j=0; j<AllUserInputDb.length; j++){                
                    if (AllUserInputDb[j].country==countries[i].Country)
                        country_data.push(AllUserInputDb[j]);
                }
            //console.log(country_data);

            var country_object = {
                code: countries[i].Country,
                currency: countries[i].currency,
                distance_std: countries[i].distance_std,
                fuel_efficiency_std: countries[i].fuel_efficiency_std,
                fuel_price_volume_std: countries[i].fuel_price_volume_std							
            };
            //console.log(country_object);
            
            var stats_results = CalculateStatistics(country_users, country_data, country_object);
            console.log("\n" + countries[i].Country + " - total costs: " + stats_results.totCos);

            queryInsert += "('" + countries[i].Country + "', "
                + date_string            + ", "                 
                + stats_results.dep.toFixed(1)      + ", "
                + stats_results.ins.toFixed(1)      + ", " 
                + stats_results.cred.toFixed(1)     + ", "  
                + stats_results.insp.toFixed(1)     + ", " 
                + stats_results.carTax.toFixed(1)   + ", " 
                + stats_results.maint.toFixed(1)    + ", " 
                + stats_results.fuel.toFixed(1)     + ", " 
                + stats_results.rep.toFixed(1)      + ", " 
                + stats_results.park.toFixed(1)     + ", " 
                + stats_results.tolls.toFixed(1)    + ", " 
                + stats_results.fines.toFixed(1)    + ", " 
                + stats_results.wash.toFixed(1)     + ", "                    
                + stats_results.standCos.toFixed(0) + ", "                   
                + stats_results.runnCos.toFixed(0)  + ", " 
                + stats_results.totCos.toFixed(0)   + ", "                                           
                + stats_results.runCostsProDist.toFixed(2) + ", "
                + stats_results.totCostsProDist.toFixed(2) + ", "                   
                + stats_results.kinetic_speed.toFixed(0)   + ", "
                + stats_results.virtual_speed.toFixed(0)   + ", "                    
                + (((stats_results.totCostsPerYear/100).toFixed(0))*100) + ", "            
                + stats_results.users_counter + " )";
            
            if (i!=countries.length-1)//doesn't add "," on the last set of values
                queryInsert +=", ";
            //console.log(countries[i].Country);
        }
        //console.log(queryInsert);
        callback();
    },
    
    //erase content of monthly_costs_statistics table DB
    function(callback) {        
        console.log("Erase previous data from DB");
        db = mysql.createConnection(login_UserInputDB);
        db.connect();
        db.query('DELETE FROM monthly_costs_statistics', function(err, results, fields) {
            if (err){console.log(err); return callback(err);}
            db.end();
            callback();
        });
    },
    
    //insert data into DB
    function(callback) {        
        console.log("Insert new calculated data into DB");
        db = mysql.createConnection(login_UserInputDB);
        db.connect(); 
        db.query(queryInsert, function(err, results, fields) {
            if (err){console.log(err); return callback(err);}
            console.log("All data calculated!");
            db.end();
            callback();
        });  
    }
]);



