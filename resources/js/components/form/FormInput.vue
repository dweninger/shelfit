<template>
    <div>
        <label :for="field" class="block mb-2 text-sm font-medium text-white">
            {{label}}
            <span v-if="$attrs['required'] !== undefined" class="required-asterisk">*</span>
        </label>
        <input
            v-bind="$attrs"
            v-model="model"
            :id="field"
            class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
            :placeholder="placeholder"
        />
    </div>
</template>

<script lang="ts" setup>
const model = defineModel();
type Props = {
    field: string;
    label: string;
    placeholder: string;
}
const props = defineProps<Props>();

</script>

<style scoped>
.required-asterisk{
    color: firebrick;
}
</style>
