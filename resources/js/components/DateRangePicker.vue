<template>
    <div class="w-fit mx-auto">
        <label class="block mb-2 text-sm font-medium text-white">{{ label }}</label>
        <div class="flex mx-auto">
            <input type="date"
                   class="p-1 text-sm text-center border rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 appearance-none"
                   :class="{
                        'bg-gray-600 border-gray-500 text-white': dark,
                        'text-black bg-white border-gray-400': !dark,
                   }"
                   :value="startDate"
                   @input="updateStartDate($event.target.value)">
            <span
                class="font-bold text-center my-auto mx-2"
                :class="{
                    'text-white': dark,
                    'text-black': !dark
                }"
            > -
            </span>
            <input type="date"
                   class="p-1 text-sm text-center border rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400 appearance-none"
                   :class="{
                        'bg-gray-600 border-gray-500 text-white': dark,
                        'text-black bg-white border-gray-400': !dark,
                   }"
                   :value="endDate"
                   @input="updateEndDate($event.target.value)">
        </div>
    </div>
</template>

<script>
export default {
    name: 'DateRangePicker',
    props: {
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
    },
    methods: {
        updateStartDate(value) {
            this.$emit('update:startDate', value);
        },
        updateEndDate(value) {
            this.$emit('update:endDate', value);
        }
    }
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
