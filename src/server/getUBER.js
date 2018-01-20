/* This detects the region of the user by his locale and tries to return,
if applicable, a cost per unit distance of the correspondent 
UBER service in that region from which the user is accessing the site.
To manage tokens, visit: https://developer.uber.com/dashboard
user:info@autocosts.info | pass: V************* */

const fs       = require('fs');
const geoIP    = require('geoip-lite');
const request  = require('request');
const url      = require(__dirname + '/url');

module.exports = function(req, res, GlobData) {
    
    var CC = req.params.CC;
    
    var p1 = readCCFileAsync(GlobData.SRC_DIR + 'countries/' + CC + '.json');
    var p2 = makeUberRequest(req, GlobData);

    res.set('Content-Type', 'application/json');    
    
    Promise.all([p1, p2]).then(function(values){
        //console.log("Uber response: ", values);                                
        if(!values[0] || !values[1]){            
            res.send(null);
            return;
        }
        
        let currencyCode = values[0].curr_code;
        let UBERproducts = values[1].products;

        //creates a new array with UBER products that have the same currency
        var UBERprod = []; var i;
        for(i=0; i<UBERproducts.length; i++){
            if(UBERproducts[i].price_details.currency_code == currencyCode){
                UBERprod.push(UBERproducts[i]);
            }
        }        

        //from here UBERprod has only relevant products (the same currency)        
        if (UBERprod.length == 0){
            res.send(null);
            return;
        }
        //for every relevant UBER car gets the cheapest

        var minPrice = UBERprod[0].price_details.cost_per_distance;
        var minProduct = UBERprod[0];
        for(i=0; i<UBERprod.length; i++){
            if (UBERprod[i].price_details.cost_per_distance < minPrice){
                minPrice = UBERprod[i].price_details.cost_per_distance;
                minProduct = UBERprod[i];
            }
        }

        var objResult = {
            "cost_per_distance" : minProduct.price_details.cost_per_distance,
            "cost_per_minute" : minProduct.price_details.cost_per_minute,
            "currency_code" : minProduct.price_details.currency_code,
            "distance_unit" : minProduct.price_details.distance_unit                            
        }

        res.send(JSON.stringify(objResult));
        
    });
}

var readCCFileAsync = async function (path){    
    
    var resPromise = function(){
        return new Promise(function(resolve, reject){    
            fs.readFile(path, 'utf8', //async read file
                function(err, data){
                    if(err){
                        console.log("Error loading the file: ", err);
                        resolve(null);
                    }
                    else{
                        resolve(JSON.parse(data)); //return promise with result JSON.parse(data)
                    }
                }
            );
        });
    };
    
    var result = await resPromise();
    
    return result;
};

var makeUberRequest = async function (req, GlobData){

    var debug; //put 0 for PROD; 1 for Lisbon, 2 for London
    if (url.isThisLocalhost(req)){
        debug = 1 + (Math.random() >= 0.5); //random, it gives either 1 or 2        
    }
    else{
        debug = 0;
    }
    console.log("debug: ", debug);
    
    //debug=1;
    if(debug==1){//Lisbon coordinates
        lat=38.722252;
        long=-9.139337;
    }
    else if(debug==2){ //London coordinates
        lat=51.507351;
        long=-0.127758;  
    }
    else{//PROD or .work
        //tries to get IP from user
        var ip = req.headers['x-forwarded-for'].split(',').pop() || 
                 req.connection.remoteAddress || 
                 req.socket.remoteAddress || 
                 req.connection.socket.remoteAddress;

        var geo = geoIP.lookup(ip);
        var lat  = geo.ll[0];
        var long = geo.ll[1];
    }
    console.log("lat: " + lat + "; long: " +long);

    //get uber token  
    var uber_token = JSON.parse(fs.readFileSync(GlobData.ROOT_DIR + 'keys/' + GlobData.REL + '/uber_token.json'));
    console.log("uber_token", uber_token);
    var uber_API_url = "https://api.uber.com/v1.2/products?latitude=" + 
                        lat + "&longitude=" + long + "&server_token=" + uber_token.token;        
    console.log("uber_API_url", uber_API_url);

    //HTTP Header request
    var options = {
        url: uber_API_url,
        headers: {
            'Accept-Language': 'en_US',
            'Content-Type': 'application/json; charset=utf-8'
        }
    };
    
    //make the HTTP request to UBER API
    var resPromise = function (){
        return new Promise(function(resolve, reject){         
            request(options, function(error, response, body){
                if (!error && response.statusCode == 200) {
                    resolve(JSON.parse(body));           
                }
                else{
                    console.log("error making the HTTP request to UBER API: ", error);
                    resolve(null);
                }
            });
        });
    };
    
    var result = await resPromise();    
    return result;
};


