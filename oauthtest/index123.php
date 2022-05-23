<?php
$client_id = '805b2cf4-f4c7-441c-a45c-17f6a719eeed';
$client_secret = 'l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt';
$redirect_uri = 'https://connect.arisecur.com/oauth/callback';
$scope = 'ameise/mitarbeiterwebservice';
$state = "abc123456";

$url = "https://auth.dionera.com/oauth2/auth?client_id=$client_id&state=$state&redirect_uri=$redirect_uri&scope=$scope&response_type=code&token_endpoint_auth_method=client_secret_basic";

// client_secret=$client_secret&
if (!isset($_GET['code'])) {
    echo '<a href="' . $url . '">Login ameise</a>';
} else {
    $code = $_GET['code'];

    $apiData = array(
        'client_id'       => $client_id,
        'client_secret'   => $client_secret,
        'grant_type'      => 'client_credentials',
        'redirect_uri'    => $redirect_uri,
        'code'            => $code,
        'State'           => 'abc123456'
    );

    $apiHost = 'https://auth.dionera.com/oauth2/token';

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $apiHost);
    curl_setopt($ch, CURLOPT_POST, count($apiData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($apiData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    $jsonData = curl_exec($ch);
    curl_close($ch);

    var_dump($jsonData);
    $user = @json_decode($jsonData);

    echo '<pre>';
    print_r($user);
    exit;
}