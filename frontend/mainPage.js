let map;
let service;
let infowindow;

// Initialize the map with a default location
function initMap() {
  const defaultLocation = { lat: -34.397, lng: 150.644 };

  map = new google.maps.Map(document.getElementById("map"), {
    zoom: 12,
    center: defaultLocation,
  });

  infowindow = new google.maps.InfoWindow();
}

// Handle the search for museums in a given city
function searchMuseums() {
  const city = document.getElementById("city").value;
  window.location.href = `resultPage.html?city=${encodeURIComponent(city)}`;
}

// Create a marker for a given place on the map
function createMarker(place) {
  const marker = new google.maps.Marker({
    map: map,
    position: place.geometry.location,
  });

  // Add a click event listener to the marker to display information in the infobox
  google.maps.event.addListener(marker, "click", () => {
    const infoContent = `<strong>${place.name}</strong><br>${place.formatted_address}`;
    document.getElementById('infoBox').innerHTML = infoContent;
  });
}