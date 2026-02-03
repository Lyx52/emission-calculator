<script setup>
import {useTripStore} from "../stores/tripStore.js";
const tripStore = useTripStore();
</script>

<template>
    <div v-if="tripStore?.currentTrip" class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden border border-gray-100 dark:border-gray-700">
        <div class="bg-blue-600 p-4 text-white flex justify-between items-start">
            <div>
                <div class="text-xs font-bold uppercase tracking-wider opacity-80 mb-1">
                    Kods {{ tripStore.routeCode }}
                </div>
                <h2 class="text-xl font-bold leading-tight">
                    {{ tripStore.routeName }}
                </h2>
            </div>
            <div class="text-right">
                <div class="text-lg font-bold">
                    {{ tripStore.fromStop?.departure_time }} - {{ tripStore.toStop?.arrival_time }}
                </div>
                <div class="text-xs opacity-80">Attiet - Pienāk</div>
            </div>
        </div>
    </div>
</template>
