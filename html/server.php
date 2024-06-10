<?php
session_start();

$dh_params = openssl_pkey_get_private(file_get_contents('dhparam.pem'));

$server_key = openssl_pkey_new(array(
    "dh" => $dh_params
));

$server_details = openssl_pkey_get_details($server_key);
$server_pub_key = $server_details['dh']['pub_key'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode(array('server_pub_key' => base64_encode($server_pub_key)));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_pub_key = base64_decode($_POST['client_pub_key']);

    openssl_pkey_derive($server_key, $client_pub_key, $server_shared_secret);

    $_SESSION['shared_secret'] = base64_encode($server_shared_secret);
}
?>
