<template>
    <div class="w-fit mx-auto">
        <label class="block mb-2 text-sm font-medium text-white">{{ props.label }}</label>
        <div class="flex mx-auto">
            <input type="date"
                   class="p-1 text-sm text-center border rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 appearance-none"
                   :class="{
                        'bg-gray-600 border-gray-500 text-white': props.dark,
                        'text-black bg-slate-200 border-gray-400': !props.dark,
                   }"
                   :value="props.startDate"
                   @input="updateStartDate($event.target.value)">
            <span
                class="font-bold text-center my-auto mx-2"
                :class="{
                    'text-white': props.dark,
                    'text-black': !props.dark
                }"
            > -
            </span>
            <input type="date"
                   class="p-1 text-sm text-center border rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 appearance-none"
                   :class="{
                        'bg-gray-600 border-gray-500 text-white': props.dark,
                        'text-black bg-slate-200 border-gray-400': !props.dark,
                   }"
                   :value="props.endDate"
                   @input="updateEndDate($event.target.value)">
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    label: {
        type: String,
        default: 'Date Range'
    },
    startDate: {
        type: String,
        default: null
    },
    endDate: {
        type: String,
        default: null
    },
    dark: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:startDate', 'update:endDate']);

const updateStartDate = (value) => {
    emit('update:startDate', value);
};

const updateEndDate = (value) => {
    emit('update:endDate', value);
};
</script>

<style scoped>

input[type="date"]::-webkit-calendar-picker-indicator {
    display: none;
    -webkit-appearance: none;
}

/* For Firefox */
input[type="date"] {
    -moz-appearance: textfield;
}

</style>
