<?php
require '../includes/database_handler.php';

//read all
function getCustomerList(){
    global $conn;
    $query = "SELECT * FROM customers";
    $query_run = mysqli_query($conn, $query);

    if($query_run){
        if(mysqli_num_rows($query_run) >0){
            $res = mysqli_fetch_all($query_run, MYSQLI_ASSOC);
            $data = [
                'status' => 200,
                'message' => 'Customer List Fetched',
                'data' => $res
                ];
                header("HTTP/1.0 200 OK");
                return json_encode($data);
        }
        else{
            $data = [
                'status' => 404,
                'message' => 'No Customer Found',
                ];
                //apare si in status message
                header("HTTP/1.0 404 No Customer Found");
                return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
    }
}

//create
function storeCustomer($customerInput){
    global $conn;
    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if(empty(trim($name))){
        //422 = input request validation
        return error422('Enter your name');
    }
    elseif(empty(trim($email))){
        return error422('Enter your email');
    }
    elseif(empty(trim($phone))){
        return error422('Enter your phone');
    }
    else{
        $query = "INSERT INTO customers (name,email,phone) VALUES ('$name','$email','$phone')";
        $result = mysqli_query($conn, $query);

        if($result){
            $data = [
                'status' => 200,
                'message' => 'Customer Created',
                ];
                header("HTTP/1.0 201 Created");
                return json_encode($data);
        }
        else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
                ];
                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
        }
    }
}

//error return
function error422($message){
    $data = [
        'status' => 422,
        'message' => $message,
        ];
        header("HTTP/1.0 422 Unproccesable entity");
        echo json_encode($data);
        exit();
}

//read 1 ID
function getCustomer($customerParams){
    global $conn;
    if($customerParams['id'] ==null){
        //422 = input request validation
        return error422('Enter customer id');
    }

    $customerId = mysqli_real_escape_string($conn, $customerParams['id']);
    $query = "SELECT * FROM customers WHERE id='$customerId' LIMIT 1";
    $result =mysqli_query($conn, $query);

    if($result){
        if(mysqli_num_rows($result) ==1){
            $res = mysqli_fetch_assoc($result);

            $data = [
                'status' => 200,
                'message' => 'Customer Fetched',
                'data' => $res
                ];
                header("HTTP/1.0 200 OK");
                return json_encode($data);
        }
        else{
            $data = [
                'status' => 404,
                'message' => 'No customer Found',
                ];
                header("HTTP/1.0 404 No customer Found");
                return json_encode($data);
        }
    }
    else{
        $data = [
            'status' => 500,
            'message' => 'Internal Server Error',
            ];
            header("HTTP/1.0 500 Internal Server Error");
            return json_encode($data);
    }
}

//update 
function updateCustomer($customerInput, $customerParams){
    global $conn;
    if(!isset($customerParams['id'])){
        return error422('Customer id not found in URL');
    }
    elseif($customerParams['id'] == null){
        return error422('Enter customer id');
    }
    $customerId =mysqli_real_escape_string($conn, $customerParams['id']);
    $name = mysqli_real_escape_string($conn, $customerInput['name']);
    $email = mysqli_real_escape_string($conn, $customerInput['email']);
    $phone = mysqli_real_escape_string($conn, $customerInput['phone']);

    if(empty(trim($name))){
        return error422('Enter your name');
    }
    elseif(empty(trim($email))){
        return error422('Enter your email');
    }
    elseif(empty(trim($phone))){
        return error422('Enter your phone');
    }
    else{
        $query = "UPDATE customers SET name = '$name',email = '$email',phone = '$phone' 
        WHERE id='$customerId' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){
            $data = [
                'status' => 200,
                'message' => 'Customer Updated',
                ];
                header("HTTP/1.0 200 Updated");
                return json_encode($data);
        }
        else{
            $data = [
                'status' => 500,
                'message' => 'Internal Server Error',
                ];
                header("HTTP/1.0 500 Internal Server Error");
                return json_encode($data);
        }
    }
}

//delete
function DeleteCustomer($customerParams){
    global $conn;
    if(!isset($customerParams['id'])){
        return error422('Customer id not found in URL');
    }
    elseif($customerParams['id'] == null){
        return error422('Enter customer id');
    }
    $customerId =mysqli_real_escape_string($conn, $customerParams['id']);

    $query = "DELETE FROM customers WHERE id= '$customerId' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if($result){
        $data = [
            'status' => 200,
            'message' => 'Customer deleted',
            ];
            header("HTTP/1.0 200 OK");
            return json_encode($data);
    }
    else{
        $data = [
            'status' => 404,
            'message' => 'Customer not Found',
            ];
            header("HTTP/1.0 404 not Found");
            return json_encode($data);
    }

}
?>