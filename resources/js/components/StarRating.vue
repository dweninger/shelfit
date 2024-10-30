<template>
    <div class="flex space-x-2">
        <button
            v-for="star in 5"
            :key="star"
            @click="setRating(star)"
            @mouseover="hoverRating(star)"
            @mouseleave="resetHover"
            type="button">
            <star-icon
                :class="{
                  'text-yellow-400': star <= (hoveredRating || currentRating),
                  'text-gray-400': star > (hoveredRating || currentRating)
                }"
            />
        </button>
    </div>
</template>

<script setup>

import {ref, watch} from "vue";
import StarIcon from "./icons/StarIcon.vue";

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
