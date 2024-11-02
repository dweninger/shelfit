<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative rounded-lg shadow bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                    <h3 class="text-xl font-semibold text-white">
                        {{ title }}
                    </h3>
                    <button
                        @click="hideModal"
                        class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                    >
                        <x-icon/>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
import XIcon from "../icons/XIcon.vue";

const props = defineProps({
    isVisible: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Modal',
    }
});

const emit = defineEmits(['close']);

const hideModal = () => {
    emit('close');
}
</script>
