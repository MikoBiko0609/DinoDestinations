// In resultPage.js
require('dotenv').config();
const apiKey = process.env.GOOGLE_MAPS_API_KEY;

// Use the apiKey variable to load the Google Maps API
const script = document.createElement('script');
script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initMap`;
script.async = true;
document.body.appendChild(script);