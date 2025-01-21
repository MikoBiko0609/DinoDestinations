console.log("Executing resultPage.js");

// Load API Key from `config.js`
console.log("Google Maps API Key:", GOOGLE_MAPS_API_KEY); // Debugging

function initMap() {
    const urlParams = new URLSearchParams(window.location.search);
    const city = urlParams.get('city');

    if (!city) {
        showError('No city specified. Please enter a city and try again.');
        return;
    }

    // Load Google Maps dynamically with the API Key
    const script = document.createElement("script");
    script.src = `https://maps.googleapis.com/maps/api/js?key=${GOOGLE_MAPS_API_KEY}&libraries=places&callback=initMap`;
    script.async = true;
    document.body.appendChild(script);
    script.onload = () => {
        console.log("Google Maps API script loaded");
        startMap(city);
    };
    script.onerror = () => {
        console.error("Error loading Google Maps API");
        showError("An error occurred while loading the map.");
    };
}

function startMap(city) {
    console.log("Initializing map for city:", city);
    const geocoder = new google.maps.Geocoder();
    geocoder.geocode({ address: city }, (results, status) => {
        if (status === "OK" && results[0]) {
            const location = results[0].geometry.location;
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: location,
            });

            const service = new google.maps.places.PlacesService(map);
            const request = {
                location: location,
                radius: 50000,
                keyword: "dinosaur",
            };

            service.nearbySearch(request, (results, status) => {
                console.log("Nearby search results:", results, status);
                if (status === google.maps.places.PlacesServiceStatus.OK && results.length > 0) {
                    document.getElementById("infoBoxInner").innerHTML = "";
                    results.forEach((place) => {
                        new google.maps.Marker({
                            map: map,
                            position: place.geometry.location,
                            title: place.name,
                        });
                        addPlaceDetails(place);
                    });
                    map.setCenter(results[0].geometry.location);
                } else {
                    showError(`No dinosaur-related attractions found in ${city}.`);
                }
            });
        } else {
            showError(`Could not locate the city "${city}". Please try another.`);
        }
    });
}

function addPlaceDetails(place) {
    const infoBoxInner = document.getElementById("infoBoxInner");
    const placeDetails = `
        <div class="place-details">
            <strong>${place.name}</strong>
            <p>${place.formatted_address}</p>
            ${place.rating ? `<p>Rating: ${place.rating}/5 (${place.user_ratings_total || 0} reviews)</p>` : ""}
            <a href="https://www.google.com/maps/dir/?api=1&destination=${encodeURIComponent(
                place.formatted_address
            )}" target="_blank">Get Directions</a>
        </div>
    `;
    infoBoxInner.innerHTML += placeDetails;
}

function showError(message) {
    document.getElementById("infoBoxInner").innerHTML = `<p class="error">${message}</p>`;
}

// Initialize the map when the page loads
document.addEventListener("DOMContentLoaded", initMap);
