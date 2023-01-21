<?php
defined('BASEPATH') OR exit('No direct script access allowed');
define('DOMAIN','https://ictms.balco.in:5000/');
define('ASSETDOMAIN','https://ictms.balco.in:5000/index.php');
define('ROOT','/var/www/html/ictms/web/');
define('EXPRY_DATE','7');
define('ROUTE_CATEGORY','L3');
define('BALCO_LATITUDE','22.3942089');
define('BALCO_LONGTITUDE','82.7346878');
define('GOOGLE_SIGNATURE','gQdYhinqZ5dqgphaHbriMj68spQ=');
define('GOOGLE_CLIENT','gme-aniruddhatelemetry1');
define('LOG_PATH','/var/www/html/ictms/web/Log/');
define('UPLOAD_URL','https://ictms.balco.in:5000/balcoupload/');
define('UPLOAD_PATH','/var/www/html/ictms/web/balcoupload/');
define('SECRET_KEY', 'AtsTKT@124AtsTKT@124AtsTKT@124AtsTKT@124AtsTKT@124AtsTKT@1247890');
define('SECRET_IV','AtsTKT@124AtsTKT@124');
define('MAP_BOX_ACCESS_TOKEN','pk.eyJ1IjoiYXRzdGVjaCIsImEiOiJjaWl6cWhzZWkwMDVsdGxsd2drdnVtaDgwIn0.6OC4OQuUPOvJvQRMS-TyZA');

define("RFIDURL","http://10.101.71.216:5003/");
define("RFIDUSER","kemar");
define("RFIDPASSWORD","mumbai@123");
define("RFIDTOKEN","token 0ab6620464f75a0b368d97ddfc02f33196ce5e0b");
define("RFID_LISTENER_USER","sgigras");
define("RFID_LISTENER_PASSWORD","gigras@123");
define("RFID_APP_TYPE","DOTNET");   //  ITS VALUE WOULD BE PYTHON OR DOTNET ONLY

define("LABURL","http://10.101.115.14/lab.php?");

define("COAL_PARKING_READER","8");
define("COAL_MATERIAL_GATE_READER1","7");	//	COMMOMON MATERIAL ENTRY GATE
define("COAL_MATERIAL_GATE_READER2","");	//	PLANT ENTRY GATE
define("COAL_MATERIAL_GATE_READER3","6");	//	PARKING EXIT GATE
define("COAL_WEIGHT_BRIDGE_READER_21","13");		//	WEIGH BRIDGE 21
define("COAL_WEIGHT_BRIDGE_READER_22","12");		//	WEIGH BRIDGE 22
define("COAL_WEIGHT_BRIDGE_READER_23","11");		//	WEIGH BRIDGE 23
define("COAL_AUGUR1","14");	//	AUGER NO 21
define("COAL_AUGUR2","9");	//	AUGER NO 22
define("COAL_AUGUR3","10");	//	AUGER no 23
define("COAL_TARE_WEIGHT_READER_6","15");	
define("COAL_TARE_WEIGHT_READER_9","16");
define("COAL_EXIT_GATE_READER","17");
define("COAL_RFID_TAG_DATA","1234");
define("COAL_PALMVEIN_READER1","1");
define("COAL_PALMVEIN_READER2","2");
define("BREATHE_ANALYSER_SENSORID1","1");
define("BREATHE_ANALYSER_SENSORID2","2");
define("BREATHE_ANALYSER_SENSORID3","3");
define("RFID_URL","http://10.101.134.83:8001/");
//define("ITMS_URL","http://vhdevap.valjha.vedantaresource.local:8001/[APIFOLDER]/[APINAME]?sap-client=300");
//define("SAP_Cookie","/var/www/html/ictms/web/balcoupload/cookie.txt");
//define("SAP_USERNAME","ATSMM");
//define("SAP_USERNAME","ATSABAP");
//define("SAP_PASS","James007@1");
define("PALM_VEIN_ENROLL_URL","http://10.101.134.53/device.cgi/");// enroll
define("PALM_VEIN_VERIFY_URL","http://10.101.134.52/device.cgi/"); // verify
define("COAL_WEIGH_BRIDGE_21", array("READERID"=>13, "WEIGH_BRIDGEID"=>21, "BRIDGE_TYPE"=>"GROSS"));		//	WEIGH BRIDGE 21 
define("COAL_WEIGH_BRIDGE_22", array("READERID"=>12, "WEIGH_BRIDGEID"=>22, "BRIDGE_TYPE"=>"GROSS"));		//	WEIGH BRIDGE 22
define("COAL_WEIGH_BRIDGE_23", array("READERID"=>11, "WEIGH_BRIDGEID"=>23, "BRIDGE_TYPE"=>"GROSS"));		//	WEIGH BRIDGE 23
define("COAL_WEIGH_BRIDGE_6", array("READERID"=>15, "WEIGH_BRIDGEID"=>6, "BRIDGE_TYPE"=>"TARE"));                   //      WEIGH_BRIDGE 6
define("COAL_WEIGH_BRIDGE_9", array("READERID"=>16, "WEIGH_BRIDGEID"=>9, "BRIDGE_TYPE"=>"TARE"));                   //      WEIGH_BRIDGE 9

//QUALITY URL
//define("ITMS_URL","http://vhquaap.valjha.vedantaresource.local:8002/[APIFOLDER]/[APINAME]?sap-client=100");
//define("SAP_USERNAME","280001");
//define("SAP_PASS","vedanta@123");

//PROD URL
define("ITMS_URL","http://vhprd1ap.valjha.vedantaresource.local:8001/[APIFOLDER]/[APINAME]?sap-client=100");
define("SAP_USERNAME","300000");
define("SAP_PASS","suhana@3018");

define("SAP_Cookie","cookie.txt");


define("ICTMSMAIL","GPS.Alerts@vedanta.co.in");
define("SMTP","10.101.71.205");
define("SMTPPORT","587");
define("SMTPUSER",'gps.alerts');
define("SMTPPASS",'Xyz@12345');

define("DB_HOST","10.101.71.214"); 
define("DB_USER","ictms_user"); 
define("DB_PASS","@r1GAT0"); 
define("DB_NAME","balcodb"); 
define('UNLOAD_LOG_PATH','/var/www/html/ictms/web/Log/unloadlog');
define('GOOGLE_MAP_KEY','AIzaSyDgQ48jNVhItGn0nubQIvTQ8N34OLCnK8M');
define('AWS_BALCO','https://ictms.balco.in:5000/datasync/');
define('BALCO_AWS','http://ictms.aniruddhagps.com/datasync/');
define('API_ICTMS_ACCESS_KEY', 'AIzaSyDz1hPwQ9vP4j9mAmGcn2TqUQwG2Jv1-ZQ');//BALCO PROJECT 