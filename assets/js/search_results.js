// Fonction pour charger les communes en fonction de la province sélectionnée
function fetchCommunes(provinceId) {
    fetch('fetch_communes.php?province=' + provinceId)
        .then(response => response.json())
        .then(data => {
            let communeSelect = document.getElementById('commune');
            communeSelect.innerHTML = '<option value="">Sélectionnez une commune</option>';
            data.forEach(function(commune) {
                communeSelect.innerHTML += '<option value="' + commune.id_commune + '">' + commune.nom_commune + '</option>';
            });
        });
}

// Fonction pour charger les zones en fonction de la commune sélectionnée
function fetchZones(communeId) {
    fetch('fetch_zones.php?commune=' + communeId)
        .then(response => response.json())
        .then(data => {
            let zoneSelect = document.getElementById('zone');
            zoneSelect.innerHTML = '<option value="">Sélectionnez une zone</option>';
            data.forEach(function(zone) {
                zoneSelect.innerHTML += '<option value="' + zone.id_zone + '">' + zone.nom_zone + '</option>';
            });
        });
}

// Fonction pour charger les quartiers en fonction de la zone sélectionnée
function fetchQuartiers(zoneId) {
    fetch('fetch_quartiers.php?zone=' + zoneId)
        .then(response => response.json())
        .then(data => {
            let quartierSelect = document.getElementById('quartier');
            quartierSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
            data.forEach(function(quartier) {
                quartierSelect.innerHTML += '<option value="' + quartier.id_quartier + '">' + quartier.nom_quartier + '</option>';
            });
        });
}