<script setup>
import Autocomplete from "./components/Autocomplete.vue";
import {useStopStore} from "./stores/stopStore.js";
import TripCard from "./components/TripCard.vue";
import MapRoute from "./components/MapRoute.vue";
import BaseTable from "./components/BaseTable.vue";
import {computed} from "vue";
import {useDirectionStore} from "./stores/directionStore.js";
import {useTripStore} from "./stores/tripStore.js";
const stopStore = useStopStore();
const directionStore = useDirectionStore();
const tripStore = useTripStore();
const tableColumns = [
    { key: 'address', label: 'Adrese' },
    { key: 'stop_name', label: 'Pietura' },
    { key: 'distance', label: 'Distance (km)' },
    { key: 'arrival', label: 'Pienāk' }
]

const tableData = computed(() => directionStore.legs.length <= 0 ? [] :
    [
        {
            address: directionStore.legs[0].start_address,
            distance: 0,
            arrival: tripStore.stops[0].departure_time,
            stop_name: tripStore.stops[0].stop_name
        },
        ...directionStore.legs.map((leg, i) => ({
            address: leg.end_address,
            distance: Math.round(directionStore.legs.reduce((res, value, idx) => {
                return idx > i ? res : res + (value.distance.value / 1000)
            }, 0) * 100) / 100,
            arrival: tripStore.stops[i + 1]?.arrival_time,
            stop_name: tripStore.stops[i + 1].stop_name
        }))
    ]
)
</script>

<template>
    <div class="flex flex-col gap-4">
        <div class="flex gap-4">
            <Autocomplete
                label="Pietura no"
                :items="stopStore.results"
                :loading="stopStore.loading"
                :filter-local="false"
                display-key="name"
                placeholder="Meklēt pieturu..."
                @query="stopStore.searchStops"
                @change="stopStore.selectedFrom"
            />

            <Autocomplete
                label="Pietura līdz"
                :items="stopStore.results"
                :loading="stopStore.loading"
                :filter-local="false"
                display-key="name"
                placeholder="Meklēt pieturu..."
                @query="stopStore.searchStops"
                @change="stopStore.selectedTo"
            />
        </div>
        <TripCard />
        <MapRoute api-key="AIzaSyBoQsKqiMJhllqGjArSU-G_nBWHLD2B71A" encoded-polyline="" />
        <BaseTable :columns="tableColumns" :rows="tableData" />
    </div>
</template>

<style scoped>

</style>
