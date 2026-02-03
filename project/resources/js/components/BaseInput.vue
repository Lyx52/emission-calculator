<script setup>
import { computed, useAttrs } from 'vue'
import {v4 as uuid} from 'uuid';
const props = defineProps({
    label: {
        type: String,
        default: '',
    },
    modelValue: {
        type: [String, Number],
        default: '',
    },
    error: {
        type: String,
        default: '',
    },
    id: {
        type: String,
        default: () => `input-${uuid()}`,
    },
})

const emit = defineEmits(['update:modelValue'])

const attrs = useAttrs()

const inputClasses = computed(() => {
    const layoutClasses = 'block w-full rounded-md shadow-sm border px-3 py-2 focus:outline-none transition-colors duration-200'

    if (attrs.disabled !== undefined && attrs.disabled !== false) {
        return `
            ${layoutClasses}
            cursor-not-allowed
            bg-gray-100 text-gray-500 border-gray-300
            dark:bg-gray-700 dark:text-gray-400 dark:border-gray-600
        `;
    }

    if (props.error) {
        return `
            ${layoutClasses}
            border-red-300 text-red-900 placeholder-red-300
            focus:border-red-500 focus:ring-2 focus:ring-red-500 focus:ring-opacity-50
            dark:border-red-500 dark:text-red-300 dark:placeholder-red-400
        `;
    }

    return `
        ${layoutClasses}
        bg-white border-gray-300 text-gray-900 placeholder-gray-400
        focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50
        dark:bg-gray-800 dark:border-gray-600 dark:text-white dark:placeholder-gray-500
    `;
})
</script>

<script>
export default {
    inheritAttrs: false,
}
</script>

<template>
    <div class="flex flex-col gap-1.5">
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
            {{ label }}
        </label>

        <input
            :id="id"
            :value="modelValue"
            @input="$emit('update:modelValue', $event.target.value)"
            v-bind="$attrs"
            :class="inputClasses"
            :aria-invalid="!!error"
            :aria-describedby="error ? `${id}-error` : null"
        />

        <p
            v-if="error"
            :id="`${id}-error`"
            class="text-sm text-red-600 dark:text-red-400 animate-pulse"
        >
            {{ error }}
        </p>
    </div>
</template>
