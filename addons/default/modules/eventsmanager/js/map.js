$(document).ready(function(){
    // Google Maps Javascript API

    centerMap = function(lat, lng) {
        latlng = new google.maps.LatLng(lat, lng);
        map = new google.maps.Map(canvas.get(0), {
            zoom: 14,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.SMALL
            },
            streetViewControl: true
        });

        marker = new google.maps.Marker({
            map: map,
            position: latlng
        });
    };

    geocodePlace = function(place) {
        geocoder.geocode({
            address: place
        }, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                latlng = results[0].geometry.location;
                $('input[name=pos_lat]').val(latlng.lat());
                $('input[name=pos_lng]').val(latlng.lng());
                centerMap(latlng.lat(), latlng.lng());
            }
        });
    };

    refreshMap = function() {
        // Automatic mode
        if ($('input[name=pos_method]:checked').val() === '0') {
            place = $('input[name=place_clone]').val();
            if(typeof place=='undefined'){
                place = $('input[name=place]').val();
            }
            geocodePlace(place);
        }
        // Latitude/Longitude mode
        else {
            var lat = $('input[name=pos_lat]').val();
            var lng = $('input[name=pos_lng]').val();
            centerMap(lat, lng);
        }
    };

    var canvas = $('div#map_canvas');
    canvas.css('width', '100%');
    canvas.css('height', '300px');
    var geocoder = new google.maps.Geocoder();
    refreshMap(MAP_PLACE);

    $('a#map-refresh').click(function() {
        refreshMap();
    });

});

