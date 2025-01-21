console.log("Executing resultPage.js");

async function initMap() {
    const urlParams = new URLSearchParams(window.location.search);
    const city = urlParams.get("city");

    if (!city) {
        showError("No city specified. Please enter a city and try again.");
        return;
    }

    try {
        // ✅ Call the backend function instead of exposing the API key
        const response = await fetch(`/api/maps?city=${encodeURIComponent(city)}`);
        const data = await response.json();

        if (!data.results || data.results.length === 0) {
            showError(`No dinosaur-related attractions found in ${city}.`);
            return;
        }

        // Initialize Google Maps
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: data.results[0].geometry.location,
        });

        data.results.forEach((place) => {
            new google.maps.Marker({
                map: map,
                position: place.geometry.location,
                title: place.name,
            });
            addPlaceDetails(place);
        });
    } catch (error) {
        console.error("Error fetching data:", error);
        showError("An error occurred while loading the map.");
    }
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

document.addEventListener("DOMContentLoaded", initMap);
