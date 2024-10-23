<template>
    <div class="flex space-x-2">
        <button
            v-for="star in 5"
            :key="star"
            @click="setRating(star)"
            @mouseover="hoverRating(star)"
            @mouseleave="resetHover"
            type="button">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="currentColor"
                viewBox="0 0 24 24"
                class="h-5 w-5"
                :class="{
                  'text-yellow-400': star <= (hoveredRating || currentRating),
                  'text-gray-400': star > (hoveredRating || currentRating)
                }"
                stroke="black"
                stroke-width="1"
            >
                <path
                    d="M12 17.27l6.18 3.73-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73-1.64 7.03z"
                />
            </svg>
        </button>
    </div>
</template>

<script setup>

import {ref, watch} from "vue";

const props = defineProps({
      modelValue: {
          type: Number,
          default: 0,
      }
});

const emit = defineEmits(['update:modelValue']);

const currentRating = ref(props.modelValue);
const hoveredRating = ref(null);

const setRating = (star) => {
    currentRating.value = star;
    emit('update:modelValue', star);
};

const hoverRating = (star) => {
    hoveredRating.value = star;
};

const resetHover = () => {
    hoveredRating.value = null;
};

watch( () => props.modelValue, (newRating) => {
        currentRating.value = newRating;
});

</script>
