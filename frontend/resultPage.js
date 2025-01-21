console.log("Executing resultPage.js");

// Function to dynamically load Google Maps API script
function loadGoogleMapsScript(callback) {
    if (window.google && window.google.maps) {
        console.log("✅ Google Maps API already loaded.");
        callback();
        return;
    }

    const apiKey = window.GOOGLE_MAPS_API_KEY;  // ✅ Fix: Use the key from resultPage.html
    if (!apiKey) {
        console.error("❌ Google Maps API Key is missing.");
        showError("Google Maps API key is missing. Please contact support.");
        return;
    }

    const script = document.createElement("script");
    script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&libraries=places&callback=initMap`;
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);

    script.onload = () => {
        console.log("✅ Google Maps API script loaded.");
        callback();
    };

    script.onerror = () => {
        console.error("❌ Error loading Google Maps API.");
        showError("Failed to load Google Maps. Please refresh the page.");
    };
}

function initMap() {
    console.log("Initializing Google Map...");

    if (!window.google || !window.google.maps) {
        console.error("❌ Google Maps API not loaded yet.");
        showError("Google Maps failed to load. Please try again.");
        return;
    }

    const urlParams = new URLSearchParams(window.location.search);
    const city = urlParams.get("city");

    if (!city) {
        showError("No city specified. Please enter a city and try again.");
        return;
    }

    console.log(`🔍 Searching for: ${city}`);

    fetch(`/api/maps?city=${encodeURIComponent(city)}`)
        .then((response) => response.json())
        .then((data) => {
            console.log("📦 API Response:", data);

            if (!data.results || data.results.length === 0) {
                showError(`No dinosaur-related attractions found in ${city}.`);
                return;
            }

            // Initialize Google Maps
            const firstResult = data.results[0];
            if (!firstResult.geometry || !firstResult.geometry.location) {
                showError("No valid location data found.");
                return;
            }

            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 12,
                center: firstResult.geometry.location,
            });

            data.results.forEach((place) => {
                if (!place.geometry || !place.geometry.location) {
                    console.warn("Skipping place with no location:", place);
                    return;
                }

                new google.maps.Marker({
                    map: map,
                    position: place.geometry.location,
                    title: place.name,
                });

                addPlaceDetails(place);
            });
        })
        .catch((error) => {
            console.error("❌ Error fetching data:", error);
            showError("An error occurred while loading the map.");
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

// ✅ Load Google Maps API before running initMap()
document.addEventListener("DOMContentLoaded", () => {
    loadGoogleMapsScript(initMap);
});
