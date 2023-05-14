<?php
//hide errors
error_reporting(0);
//gives the site access to server resources
header('Access-Control-Allow-Origin:*');
//send as json format only
header('Content-Type: application/json');
//type of request method allowed
header('Access-Control-Allow-Method: PUT');
//all together
header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Request-With');
include('functions.php');

$requestMethod = $_SERVER["REQUEST_METHOD"];

//initialize client url (cURL)
$curl = curl_init();

//setarile requestului
curl_setopt_array($curl, [
    CURLOPT_URL => "http://localhost/php-api/customers/update.php",
    CURLOPT_SSL_VERIFYPEER=>false,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "PUT",
]);



//Postman 
if($requestMethod == 'PUT'){
    $inputData = json_decode(file_get_contents("php://input"), true);
    if(empty($inputData)){

        //through form-data
        $updateCustomer = updateCustomer($_POST,$_GET);
    }
    else{
        //through raw data
        $updateCustomer = updateCustomer($inputData, $_GET);
        
    }
    echo $updateCustomer;
}
else{
    $data = [
        'status' => 405,
        'message' => $requestMethod. ' Method not Allowed',
        ];
        header("HTTP/1.0 405 Method not Allowed");
        echo json_encode($data);
}
?>