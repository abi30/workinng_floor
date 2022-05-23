<?php
session_start();

if ( !isset( $_GET['code'] ) ) {
    // Check if we need to show the "Sign In" link
    $params = array(
        // 'audience'      => '{AUDIENCE}',
        'scope'         => 'openid',
        'response_type' => 'code',
        'client_id'     => '805b2cf4-f4c7-441c-a45c-17f6a719eeed',
        'state'         => 'abc123456',
        'redirect_uri'  => 'https://connect.arisecur.com/oauth/callback',
    );

    $_SESSION['oauth2state'] = $params['state'];
    $str_params = '';
    foreach ( $params as $key => $value ) {
        $str_params .= $key . "=" . urlencode( $value ) . "&";
    }
    ?>

    <a href="https://auth.dionera.com/oauth2/auth?<?php echo $str_params; ?>">
      Sign In
    </a>
<?php
} elseif ( empty( $_GET['state'] ) || ( isset( $_SESSION['oauth2state'] ) && $_GET['state'] !== $_SESSION['oauth2state'] ) ) {
    // If the "state" var is present in the $_GET, let's validate it
    if ( isset( $_SESSION['oauth2state'] ) ) {
        unset( $_SESSION['oauth2state'] );
    }

    exit( 'Invalid state' );

} elseif ( isset( $_GET['code'] ) && !empty( $_GET['code'] ) ) {
    // If the auth "code" is present in the $_GET
    // let's exchange it for the access token
    $params = array(
        'grant_type'    => 'authorization_code',
        'client_id'     => '805b2cf4-f4c7-441c-a45c-17f6a719eeed',
        'client_secret' => 'l7K3xVl_IeK7xnxBKihO04P8LR5saySx4tx2_kCI40JMxA0e1sFHFbf1VzrqAIcu3ZLdOjNaVKl16Ujt',
        'code'          => $_GET['code'],
        'redirect_uri'  => 'https://connect.arisecur.com/oauth/callback',
    );

    $str_params = '';
    foreach ( $params as $key => $value ) {
        $str_params .= $key . "=" . urlencode( $value ) . "&";
    }

    $curl = curl_init();

    curl_setopt_array( $curl, array(
        CURLOPT_URL            => "https://auth.dionera.com/oauth2/token",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => "POST",
        CURLOPT_POSTFIELDS     => $str_params,
    ) );

    $curl_response = curl_exec( $curl );
    $curl_error = curl_error( $curl );

    curl_close( $curl );

    if ( $curl_error ) {
        echo "Error in the CURL response:" . $curl_error;
    } else {
        $arr_json_data = json_decode( $curl_response );

        if ( isset( $arr_json_data->access_token ) ) {
            $access_token = $arr_json_data->access_token;
            print_r($access_token);
            exit;
            $curl = curl_init();
            curl_setopt_array( $curl, array(
                CURLOPT_URL            => "http://{YOUR_API_DOMAIN}/demo_api_server.php",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST  => "GET",
                CURLOPT_HTTPHEADER     => array(
                    "Authorization: Bearer {$access_token}",
                ),
            ) );

            $curl_response = curl_exec( $curl );
            $curl_error = curl_error( $curl );

            curl_close( $curl );

            if ( $curl_error ) {
                echo "Error in the CURL response from DEMO API:" . $curl_error;
            } else {
                echo "Demo API Response:" . $curl_response;
            }
        } else {
            echo 'Invalid response, no access token was found.';
        }
    }
}