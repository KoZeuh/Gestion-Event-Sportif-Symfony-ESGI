document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const longitude = form.querySelector('#participant_longitude');
    const latitude = form.querySelector('#participant_latitude');
    const distance = form.querySelector('#distance');
    const event = form.querySelector('#participant_event');

    document.getElementById('btn-calcul-distance').addEventListener('click', function() {
        if (!latitude.value || !longitude.value) {
            return alert('Veuillez renseigner la longitude et la latitude du participant.');
        }

        const userLat = parseFloat(latitude.value);
        const userLon = parseFloat(longitude.value);
        const eventId = event.value;

        const xhttp = new XMLHttpRequest();
        xhttp.onload = function() {
            if (this.status === 200) {
                const response = JSON.parse(this.responseText);
                const distanceInKm = response.distance_km;

                distance.style.display = 'block';

                distance.innerHTML = `Distance entre le participant et l'évènement : ${distanceInKm} km`;
            } else {
                alert('Erreur lors du calcul de la distance.');
            }
        };

        const url = `/event/${eventId}/calculate-distance/${userLat}/${userLon}`;
        xhttp.open("GET", url, true);
        xhttp.send();
    });
});
