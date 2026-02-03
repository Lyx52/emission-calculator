<template>
    <div ref="mapRef" style="width: 100%; height: 500px; border-radius: 8px;"></div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { storeToRefs } from 'pinia';
import {useDirectionStore} from "../stores/directionStore.js";

const mapRef = ref(null);
const directionStore = useDirectionStore();
const { polyline, points } = storeToRefs(directionStore);

let map = null;
let polylinePath = null;
let markers = [];

const initMap = () => {
    map = new google.maps.Map(mapRef.value, {
        center: { lat: 56.8796, lng: 24.6032 },
        zoom: 7,
    });
};

const drawRoute = (encodedString) => {
    if (!encodedString || !window.google) {
        if (polylinePath) polylinePath.setMap(null);
        return;
    }

    if (polylinePath) polylinePath.setMap(null);

    const path = google.maps.geometry.encoding.decodePath(encodedString);
    polylinePath = new google.maps.Polyline({
        path: path,
        strokeColor: "#4285F4",
        strokeWeight: 5,
    });

    polylinePath.setMap(map);

    const bounds = new google.maps.LatLngBounds();
    path.forEach(coord => bounds.extend(coord));
    map.fitBounds(bounds);
};

const clearMarkers = () => {
    markers.forEach(m => m.setMap(null));
    markers = [];
};

const drawWaypoints = (coords) => {
    clearMarkers();
    if (!coords.length || !map) return;

    coords.forEach((coord, index) => {
        const isOrigin = index === 0;
        const isDestination = index === coords.length - 1;

        const marker = new google.maps.Marker({
            position: coord,
            map: map,
            label: isOrigin ? 'A' : (isDestination ? 'B' : ''),
            title: isOrigin ? 'Origin' : (isDestination ? 'Destination' : `Waypoint ${index}`),
            icon: isOrigin || isDestination ? null : {
                path: google.maps.SymbolPath.CIRCLE,
                scale: 5,
                fillColor: "#FFFFFF",
                fillOpacity: 1,
                strokeWeight: 2,
                strokeColor: "#4285F4"
            }
        });
        markers.push(marker);
    });
};

watch([polyline, points], ([newPoly, newCoords]) => {
    if (newPoly && map) {
        drawRoute(newPoly);
        drawWaypoints(newCoords);
    }
}, { deep: true });


onMounted(() => {
    if (window.google) {
        initMap();
        if (polyline.value) drawRoute(polyline.value);
    }
});
</script>
