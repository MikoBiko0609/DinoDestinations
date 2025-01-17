<?php
$apiKey = trim(file_get_contents('/etc/secrets/google-maps-api-key.env'));

if (!$apiKey) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Google Maps API key not found'
    ]);
    exit();
}

$url = 'https://maps.googleapis.com/maps/api/js?key=' . $apiKey . '&libraries=places&callback=initMap';
$response = file_get_contents($url);

if ($response === false) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to fetch Google Maps API'
    ]);
    exit();
}

echo $response;
?>