console.log('Executing resultPage.js');

// Check if the API key is available
const apiKey = process.env.GOOGLE_MAPS_API_KEY;
if (apiKey) {
  console.log('Google Maps API Key:', apiKey);
} else {
  console.error('Google Maps API Key not found');
}

// Load the Google Maps API
try {
  const script = document.createElement('script');
  script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initMap`;
  script.async = true;
  document.body.appendChild(script);
  console.log('Google Maps API script loaded');
} catch (error) {
  console.error('Error loading Google Maps API:', error);
}