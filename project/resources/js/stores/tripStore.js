import {defineStore} from "pinia";
import {useDirectionStore} from "./directionStore.js";

export const useTripStore = defineStore('trips', {
    state: () => ({
        currentTrip: null,
        routeName: null,
        routeCode: null,
        stops: [],
        fromStop: null,
        toStop: null,
        loading: false,
        error: null
    }),
    actions: {
        async fetch(stopFromId, stopToId) {
            if (!stopFromId || !stopToId) {
                this.currentTrip = null;
                return
            }

            this.loading = true;
            this.error = null;

            try {
                const data = await fetch(`/api/trips/${stopFromId}/${stopToId}`);
                const response = await data.json();
                if (response.error) {
                    this.error = response.error;
                } else {
                    this.routeName = response?.route?.route_long_name;
                    this.routeCode = response?.route?.route_short_name;
                    this.fromStop = response?.stop_times?.length > 0 ? response.stop_times[0] : null;
                    this.toStop = response?.stop_times?.length > 0 ? response.stop_times[response.stop_times.length - 1] : null;
                    this.stops = response?.stop_times?.map((stop) => ({
                        ...stop,
                        ...stop.stop
                    })) ?? [];
                    this.currentTrip = response;
                    const directionStore = useDirectionStore();
                    await directionStore.fetch(response?.id);
                }
            } catch (err) {
                this.error = 'Failed to fetch trip'
                console.error(err)
            } finally {
                this.loading = false
            }
        }
    },
})
