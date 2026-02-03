<template>
    <div ref="mapRef" style="width: 100%; height: 500px; border-radius: 8px;"></div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { storeToRefs } from 'pinia';
import {useDirectionStore} from "../stores/directionStore.js";

const mapRef = ref(null);
let map = null;
let markers = [];
let lines = [];

const directionStore = useDirectionStore();
const { polylines, points } = storeToRefs(directionStore);

const initMap = () => {
    map = new google.maps.Map(mapRef.value, {
        center: { lat: 56.8796, lng: 24.6032 },
        zoom: 7,
    });
};

const clearLines = () => {
    lines.forEach(l => l.setMap(null));
    lines = [];
}

const clearMarkers = () => {
    markers.forEach(m => m.setMap(null));
    markers = [];
};

const addLine = (path) => {
    const line = new google.maps.Polyline({
        path: path,
        strokeColor: "#4285F4",
        strokeWeight: 5,
    });

    line.setMap(map);

    lines.push(line);

    const bounds = new google.maps.LatLngBounds();
    path.forEach(coords => bounds.extend(coords));
    map.fitBounds(bounds);
}

const addMarker = (coordinates, options) => {
    const marker = new google.maps.Marker({
        position: coordinates,
        map: map,
        icon: {
            path: google.maps.SymbolPath.CIRCLE,
            scale: 5,
            fillColor: "#FFFFFF",
            fillOpacity: 1,
            strokeWeight: 2,
            strokeColor: "#4285F4"
        },
    });

    markers.push(marker);
}

watch([polylines, points], ([newPolylines, newCoordinates]) => {
    if (!map) {
        return;
    }

    clearLines();
    for (const polyline of newPolylines) {
        addLine(polyline);
    }

    clearMarkers();
    for (const coordinates of newCoordinates) {
        addMarker(coordinates);
    }
}, { deep: true });


onMounted(() => {
    if (window.google) {
        initMap();
    }
});
</script>
