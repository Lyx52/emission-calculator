import {defineStore} from "pinia";

export const useDirectionStore = defineStore('direction', {
    state: () => ({
        waypoints: [],
        points: [],
        legs: [],
        polyline: null,
        directions: null,
        loading: false,
        error: null
    }),
    actions: {
        async fetch(tripId) {
            if (!tripId) {
                this.directions = null;
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
                    const route = response?.routes?.length <= 0 ? null : response.routes[0];
                    this.polyline = route?.overview_polyline?.points;
                    this.legs = route?.legs ?? [];
                    this.points = [];
                    if (this.legs.length) {
                        this.points.push(this.legs[0].start_location);

                        this.legs.forEach(leg => {
                            this.points.push(leg.end_location);
                        });
                    }
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
