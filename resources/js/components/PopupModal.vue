<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative rounded-lg shadow bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                    <h3 class="text-xl font-semibold text-white">
                        {{title}}
                    </h3>
                    <button
                        @click="hideModal"
                        class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center hover:bg-gray-600 hover:text-white"
                    >
                        <svg
                            class="w-3 h-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14"
                        >
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                            />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>
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
