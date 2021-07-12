<?php 
//========// Variable //============//
$Amount = $_POST['Amount'];
$TokenCode = ""; // your pay token in payping.it 

$back = "https://"; //Link after payment cancellation
if($_POST['Description'] != null){
    $desc = $_POST['Description'];
}else{
    $desc = "فاقد توضیحات";
}
$data = array(
    'Amount'        => $Amount,
    'Description'   => $desc,
    'returnUrl'     => $back
);

//==========// codes //===========//

if($Amount > 999 and $Amount <= 50000000){
$curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://api.payping.ir/v2/pay",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 45,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => array(
            "accept: application/json",
            "authorization: Bearer " . $TokenCode,
            "cache-control: no-cache",
            "content-type: application/json"
        ),
            )
    );
    $response = curl_exec( $curl );


    $header = curl_getinfo( $curl );
    $err = curl_error( $curl );
    curl_close( $curl );
    
                $json = json_decode($response,true);
                $responses = $json['code'];
                $error = $json['Error'];
                $errors = $json['ReturnUrl'];
                $go = 'https://api.payping.ir/v2/pay/gotoipg/' . $responses;
    
    //=======// errors //===========//
if($header['http_code'] == 200){
        header( 'Location: ' . $go );
    }
if($header['http_code'] == 400){
        echo $errors.$error;
    }
    if($header['http_code'] == 500){
        echo 'مشکلی در سرور رخ داده است';
    }
    if($header['http_code'] == 503){
        echo 'سرور در حال حاضر قادر به پاسخگویی نمی‌باشد';
    }
    if($header['http_code'] == 401){
        echo 'عدم دسترسی';
        
    }
    if($header['http_code'] == 403){
        echo 'دسترسی غیر مجاز';
    }
    if($header['http_code'] == 404){
        echo 'آیتم درخواستی مورد نظر موجود نمی‌باشد';
    }
        }else{
    echo 'مبلغ وارد شده نامعتبر است!!';
            }
