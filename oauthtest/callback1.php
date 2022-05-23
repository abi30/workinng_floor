<?php
$client_id = '805b2cf4-f4c7-441c-a45c-17f6a719eeed';
$client_secret = 'l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt';
$redirect_uri = "http://localhost:8080/callback.php";
$authorization_code = $_GET['code'];
$url = 'https://auth.dionera.com/oauth2/token';
//('https://connect.squareup.com/oauth2/token');

if (!$authorization_code) {
    die('something went wrong!');
}

$data = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'redirect_uri' => $redirect_uri,
    'code' => $authorization_code
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);
$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

var_dump($result);