import {defineStore} from "pinia";
import {useTripStore} from "./tripStore.js";

export const useStopStore = defineStore('stops', {
    state: () => ({
        selectedStopFrom: null,
        selectedStopTo: null,
        results: [],
        loading: false,
        error: null,
        debounceTimer: null
    }),
    actions: {
        async searchStops(query) {
            if (query?.length < 3) {
                this.results = []
                return
            }

            if (this.debounceTimer) clearTimeout(this.debounceTimer)
            this.loading = true;
            this.error = null;

            this.debounceTimer = setTimeout(async () => {
                try {
                    const params = new URLSearchParams();
                    params.set('query', query);
                    if (this.selectedStopFrom?.id) {
                        params.set('from', this.selectedStopFrom.id);
                    }

                    const data = await fetch(`/api/stops/query?${params.toString()}`);
                    const response = await data.json();
                    if (response.error) {
                        this.error = response.error;
                    } else {
                        this.results = Object.entries(response).map(([id, name]) => ({
                            id,
                            name
                        }));
                        console.log(this.results);
                    }
                } catch (err) {
                    this.error = 'Failed to fetch stops'
                    console.error(err)
                } finally {
                    this.loading = false
                }
            }, 300)
        },
        async selectedFrom(item) {
            this.selectedStopFrom = item;
            this.results = [];

            await this.fetchTrip();
        },
        async selectedTo(item) {
            this.selectedStopTo = item;
            this.results = [];

            await this.fetchTrip();
        },
        async fetchTrip() {
            console.log(this.selectedStopFrom?.id, this.selectedStopTo?.id)
            if (this.selectedStopFrom?.id && this.selectedStopTo?.id) {
                const tripStore = useTripStore();
                await tripStore.fetch(this.selectedStopFrom?.id, this.selectedStopTo?.id)
            }
        }
    },
})
