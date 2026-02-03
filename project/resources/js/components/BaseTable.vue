<script setup>
defineProps({
    columns: {
        type: Array,
        required: true,
    },
    rows: {
        type: Array,
        required: true,
    }
})
</script>

<template>
    <div class="overflow-x-auto border rounded-lg shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">

            <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
                <th
                    v-for="col in columns"
                    :key="col.key"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"
                >
                    {{ col.label }}
                </th>
            </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="(row, i) in rows" :key="i" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <td
                    v-for="col in columns"
                    :key="col.key"
                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-700 dark:text-gray-300"
                >
                    {{ row[col.key] }}
                </td>
            </tr>

            <tr v-if="rows.length === 0">
                <td :colspan="columns.length" class="px-6 py-4 text-center text-sm text-gray-500">
                    No data found.
                </td>
            </tr>
            </tbody>

        </table>
    </div>
</template>
