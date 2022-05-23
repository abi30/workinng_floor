<?php

// $authorize_url = "https://api.byu.edu/authorize";
// $token_url = "https://api.byu.edu/token";

//    callback URL specified when the application was defined--has to match what the application says
// $callback_uri = "<<redirect_uri>>";

// $test_api_url = "<<your API>>";

//    client (application) credentials - located at apim.byu.edu
// $client_id = "<<client_id>>";
// $client_secret = "<<client_secret>>";


// --------------------

error_reporting(0);


$token_url = "https://auth.dionera.com/oauth2/token";
$authorize_url = "https://auth.dionera.com/oauth2/auth";

$test_api_url = "https://www.maklerinfo.biz/";
$callback_uri = "https://connect.arisecur.com/oauth/callback";

//    client (application) credentials on apim.byu.edu
$client_id = "805b2cf4-f4c7-441c-a45c-17f6a719eeed";
// eeed
$client_secret = "l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt";

// --------------------



if ($_POST["authorization_code"]) {

    //    what to do if there's an authorization code
    $access_token = getAccessToken($_POST["authorization_code"]);
    $resource = getResource($access_token);
    echo $resource;
} elseif ($_GET["code"]) {
    $access_token = getAccessToken($_GET["code"]);
    $resource = getResource($access_token);

    echo $resource;
} else {

    //    what to do if there's no authorization code
    getAuthorizationCode();
}

//    step A - simulate a request from a browser on the authorize_url
//        will return an authorization code after the user is prompted for credentials
function getAuthorizationCode()
{


    $scope = 'ameise/mitarbeiterwebservice';
    // $scope = 'openid';
    $state = "abc123456";

    // &scope=$scope
    global $authorize_url, $client_id, $callback_uri;

    $authorization_redirect_url = $authorize_url . "?response_type=code&client_id=" . $client_id . "&redirect_uri=" . $callback_uri . "&scope=" . $scope . "&state=" . $state;

    header("Location: " . $authorization_redirect_url);

    //    if you don't want to redirect
    // echo "Go <a href='$authorization_redirect_url'>here</a>, copy the code, and paste it into the box below.<br /><form action=" . $_SERVER["PHP_SELF"] . " method = 'post'><input type='text' name='authorization_code' /><br /><input type='submit'></form>";
}

//    step I, J - turn the authorization code into an access token, etc.
function getAccessToken($authorization_code)
{
    echo "i am here accessToken";
    echo "--/" . $_GET['code'] . "/--";
    echo "<br />";
    echo $_POST['authorization_code'] . "-<-<";

    global $token_url, $client_id, $client_secret, $callback_uri;

    $authorization = base64_encode("$client_id:$client_secret");
    $header = array("Authorization: Basic {$authorization}", "Content-Type: application/x-www-form-urlencoded");
    $content = "grant_type=authorization_code&code=$authorization_code&redirect_uri=$callback_uri";
    // authorization_code
    // client_credentials
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
    curl_close($curl);

    if ($response === false) {
        echo "Failed";
        echo curl_error($curl);
        echo "Failed";
    } elseif (json_decode($response)->error) {
        echo "Error:<br />";
        echo $authorization_code;
        echo $response;
    }


    // return json_decode($response)->access_token;
    echo "<hr>";
    // print_r(json_decode($response)->access_token);
    print_r(json_decode($response));
    echo "<hr>";
}

//    we can now use the access_token as much as we want to access protected resources
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
    print_r($response);
    exit;

    return json_decode($response, true);
}


// i9M1pQH-iKyHWl_LMo1_GxFhjxebSlpzwFddgPhU_x4.uiOjJAlbp5AOXL5fLIj91-gCE5Q3dC3TIIwz1LxTXyQ
// i9M1pQH-iKyHWl_LMo1_GxFhjxebSlpzwFddgPhU_x4.uiOjJAlbp5AOXL5fLIj91-gCE5Q3dC3TIIwz1LxTXyQ