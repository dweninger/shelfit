<template>
    <div class="w-fit px-4">
        <label
            for="field"
            class="block mb-2 text-sm font-medium text-white"
        >
            {{ label }}
        </label>
        <select
            v-model="model"
            id="status"
            class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
        >
            <option v-for="option in options" :key="option" :value="option">{{ option }}</option>
        </select>
    </div>
</template>

<script setup>
const model = defineModel();
const props = defineProps({
    options: {
        default: []
    },
    label: {
        type: String
    },
    field: {
        type: String
    }
});
</script>

<style scoped>

</style>
