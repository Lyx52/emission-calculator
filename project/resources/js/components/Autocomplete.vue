<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    modelValue: {
        type: [String, Number, Object],
        default: ''
    },
    items: {
        type: Array,
        default: () => []
    },
    displayKey: {
        type: String,
        default: 'label'
    },
    loading: {
        type: Boolean,
        default: false
    },
    filterLocal: {
        type: Boolean,
        default: true
    },
    placeholder: {
        type: String,
        default: 'Search...'
    }
})

const emit = defineEmits(['update:modelValue', 'change', 'query'])

const searchQuery = ref('')
const isOpen = ref(false)
const rootRef = ref(null)

const getItemLabel = (item) => {
    if (typeof item === 'object' && item !== null) {
        return item[props.displayKey]
    }
    return item
}

watch(() => props.modelValue, (newVal) => {
    if (newVal) {
        searchQuery.value = getItemLabel(newVal)
    }
}, { immediate: true })

const displayedItems = computed(() => {
    if (!props.filterLocal) {
        return props.items
    }

    const query = searchQuery.value.toLowerCase()
    if (!query) return props.items

    return props.items.filter(item => {
        const label = String(getItemLabel(item)).toLowerCase()
        return label.includes(query)
    })
})

const onInput = (event) => {
    const val = event.target.value
    searchQuery.value = val
    isOpen.value = true

    emit('query', val)

    if (val === '') {
        emit('update:modelValue', null)
    }
}

const selectItem = (item) => {
    searchQuery.value = getItemLabel(item)
    emit('update:modelValue', item)
    emit('change', item)
    isOpen.value = false
}

const handleClickOutside = (event) => {
    if (rootRef.value && !rootRef.value.contains(event.target)) {
        isOpen.value = false
    }
}

onMounted(() => document.addEventListener('click', handleClickOutside))
onUnmounted(() => document.removeEventListener('click', handleClickOutside))
</script>

<template>
    <div ref="rootRef" class="relative w-full">
        <label
            v-if="label"
            class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300"
        >
            {{ label }}
        </label>

        <div class="relative">
            <input
                type="text"
                :value="searchQuery"
                @input="onInput"
                @focus="isOpen = true"
                :placeholder="placeholder"
                class="w-full rounded-md border py-2 pl-3 pr-10 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 sm:text-sm transition-colors duration-200
               border-gray-300 bg-white text-gray-900 placeholder-gray-400
               dark:border-gray-600 dark:bg-gray-800 dark:text-white dark:placeholder-gray-500"
            />

            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                <svg v-if="loading" class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <svg v-else class="h-4 w-4 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>

        <ul
            v-if="isOpen && (displayedItems.length > 0 || loading)"
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm
             bg-white dark:bg-gray-800 dark:ring-gray-700"
        >
            <li
                v-if="loading && displayedItems.length === 0"
                class="py-2 px-3 italic text-gray-500 dark:text-gray-400"
            >
                Searching...
            </li>

            <li
                v-for="(item, index) in displayedItems"
                :key="index"
                @click="selectItem(item)"
                class="cursor-pointer select-none py-2 pl-3 pr-9 transition-colors
               text-gray-900 hover:bg-blue-100
               dark:text-gray-100 dark:hover:bg-blue-900/40"
            >
        <span class="block truncate">
          {{ getItemLabel(item) }}
        </span>
            </li>
        </ul>

        <div
            v-else-if="isOpen && searchQuery && !loading && displayedItems.length === 0"
            class="absolute z-10 mt-1 w-full rounded-md py-2 px-3 text-sm shadow-lg border
             bg-white text-gray-500 border-gray-100
             dark:bg-gray-800 dark:text-gray-400 dark:border-gray-700"
        >
            No results found.
        </div>
    </div>
</template>
