import {defineStore} from "pinia";

export const useDirectionStore = defineStore('direction', {
    state: () => ({
        waypoints: [],
        points: [],
        legs: [],
        polylines: [],
        loading: false,
        error: null
    }),
    actions: {
        async fetch(tripId) {
            if (!tripId) {
                this.waypoints = [];
                this.legs = [];
                this.points = [];
                this.polylines = [];
                return
            }

            this.loading = true;
            this.error = null;

            try {
                const data = await fetch(`/api/trips/directions/${tripId}`);
                const response = await data.json();
                if (response.error) {
                    this.error = response.error;
                } else {
                    this.waypoints = response?.waypoints ?? [];
                    this.legs = response?.legs ?? [];

                    this.points = [];
                    if (this.legs.length) {
                        this.points.push(this.legs[0].start_location);

                        this.legs.forEach(leg => {
                            this.points.push(leg.end_location);
                        });
                    }

                    this.polylines = response.polylines?.map(path => google.maps.geometry.encoding.decodePath(path)) ?? [];
                }
            } catch (err) {
                this.error = 'Failed to fetch trip directions'
                console.error(err)
            } finally {
                this.loading = false
            }
        }
    },
})
