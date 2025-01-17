<?php
$apiKey = getenv('GOOGLE_MAPS_API_KEY');
$url = 'https://maps.googleapis.com/maps/api/js?key=' . $apiKey . '&libraries=places&callback=initMap';
$response = file_get_contents($url);
echo $response;
?>