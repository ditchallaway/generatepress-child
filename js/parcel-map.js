/**
 * Google Maps + Regrid ArcGIS Parcel Boundaries Layer
 * (Migrated from Advanced Scripts: "maptest")
 */
(function( $ ) {

/**
 * initMap
 * Renders a Google Map onto the selected jQuery element
 */
function initMap( $el ) {
    var mapArgs = {
        mapId             : "cffa992fe3594e46",
        zoom              : $el.data('zoom') || 16,
        mapTypeId         : google.maps.MapTypeId.HYBRID,
        panControl        : false,
        zoomControl       : true,
        mapTypeControl    : false,
        scaleControl      : true,
        streetViewControl : false,
        overviewMapControl: false,
        rotateControl     : false,
        fullscreenControl : true
    };
    var map = new google.maps.Map( $el[0], mapArgs );

    var infoWindow = new google.maps.InfoWindow();

    // Init marker handlers
    initMarkers( map, infoWindow );

    // Center map to static location
    centerMap( map );

    // Add ArcGIS Tile Layer
    addArcGISTileLayer( map );

    // Return map instance.
    return map;
}

function addArcGISTileLayer( map ) {
    // Define the ArcGIS Tile Service URL pattern
    var arcgisTileURL = "https://tiles.arcgis.com/tiles/KzeiCaQsMoeCfoCq/arcgis/rest/services/Regrid_Nationwide_Parcel_Boundaries_v1/MapServer/tile/{z}/{y}/{x}";

    // Create a new ImageMapType for the ArcGIS tile layer
    var arcGISMapType = new google.maps.ImageMapType({
        getTileUrl: function(coord, zoom) {
            var maxAvailableZoom = 18;
            var x = coord.x;
            var y = coord.y;

            // If the user zooms past the service's max zoom, scale the tile coordinates
            if (zoom > maxAvailableZoom) {
                var zoomDiff = zoom - maxAvailableZoom;

                // Bitwise right-shift divides by 2^zoomDiff, dropping the remainder
                x = x >> zoomDiff;
                y = y >> zoomDiff;

                zoom = maxAvailableZoom;
            }

            return arcgisTileURL
                .replace('{z}', zoom)
                .replace('{x}', x)
                .replace('{y}', y);
        },
        tileSize: new google.maps.Size(256, 256),
        name: 'ArcGIS Parcel Boundaries',
        // Set this to a higher limit (e.g., 21 or 22) so Google Maps allows the user to keep zooming in
        maxZoom: 22
    });

    // Add the ArcGIS layer to the map's overlay map types
    map.overlayMapTypes.push(arcGISMapType);
}

function updateFields(lat, lng) {
    $("input[name='latInput']").val(lat.toFixed(6));
    $("input[name='lngInput']").val(lng.toFixed(6));
}

/**
 * initMarkers
 */
function initMarkers( map, infoWindow ) {
    var marker;
    google.maps.event.addListener(map, 'click', function(event) {
        placeMarker(event.latLng);
        updateFields(event.latLng.lat(), event.latLng.lng());
    });
    function placeMarker(location) {
        if (marker == null) {
            marker = new google.maps.Marker({
                position  : location,
                map       : map,
                infoWindow: infoWindow,
                draggable : true,
                animation : google.maps.Animation.DROP,
                title     : "Drag me!"
            });

            function handleDrag(event) {
                updateFields(event.latLng.lat(), event.latLng.lng());
            }

            marker.addListener("drag", handleDrag);
            marker.addListener("dragend", handleDrag);
        } else {
            marker.setPosition(location);
        }
    }

    const locationButton = document.createElement("button");
    locationButton.textContent = "Zoom To Current Location";
    locationButton.classList.add("custom-map-control-button");
    map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
    locationButton.addEventListener("click", (event) => {
        event.preventDefault();
        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    };
                    placeMarker(pos);
                    updateFields(pos.lat, pos.lng);
                    map.setCenter(pos);
                },
                () => {
                    if (typeof handleLocationError === "function") {
                        handleLocationError(true, map.getCenter());
                    }
                }
            );
        }
    });
}

/**
 * centerMap
 */
function centerMap( map ) {
    var location = {lat: 39.5, lng: -98.39};
    map.setCenter(location);
}

/** Render maps on page load */
$(document).ready(function() {
    if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
        return;
    }
    $('.the-map').each(function() {
        // Avoid double-initialization if Advanced Scripts' maptest is still active
        if ($(this).children().length > 0) {
            return;
        }
        initMap( $(this) );
    });
});

})(jQuery);
