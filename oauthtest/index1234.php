<?php

$token_url = "https://auth.dionera.com/oauth2/token";
$auth_url =  "https: //auth.dionera.com/oauth2/auth";

$test_api_url = "https://www.maklerinfo.biz/";

//    client (application) credentials on apim.byu.edu
$client_id = "805b2cf4-f4c7-441c-a45c-17f6a719eeed";
$client_secret = "l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt";


$access_token = getAccessToken();
echo $access_token;
$resource = getResource($access_token);
echo $resource;

//    step A, B - single call with client credentials as the basic auth header
//        will return access_token
function getAccessToken()
{
    global $auth_url, $token_url, $client_id, $client_secret;

    $content = "grant_type=client_credentials&scope=ameise/mitarbeiterwebservice";
    $authorization =base64_encode("$client_id:$client_secret");

    $header = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");

    $curl = curl_init();


    curl_setopt_array($curl, array(
        CURLOPT_URL            => $token_url,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $content,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);



    curl_close($curl);
    if ($err) {
        echo "cURL Error #:" . $err;
    } else {
        echo $response;
    }

    return json_decode($response)->access_token;
    // return json_decode($response);
}

//    step B - with the returned access_token we can make as many calls as we want
function getResource($access_token)
{
    global $test_api_url;

    $header = array("Authorization: Bearer {$access_token}");

    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL            => $test_api_url,
        CURLOPT_HTTPHEADER     => $header,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_RETURNTRANSFER => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    return json_decode($response, true);
}