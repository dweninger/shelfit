<template>
    <div>
        <label
            for="comment"
            class="block mb-2 text-sm font-medium text-white"
        >
            {{label}}
        </label>
        <textarea
            v-bind="$attrs"
            v-model="model"
            :id="field"
            class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
            :placeholder="placeholder"
        />
    </div>
</template>

<script setup>
const model = defineModel();
const props = defineProps({
    field: {
        type: String
    },
    label: {
        type: String
    },
    placeholder: {
        type: String
    },
});
</script>

<style scoped>

</style>
