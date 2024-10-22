<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative rounded-lg shadow bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                    <h3 class="text-xl font-semibold text-white">
                        {{form.selectedBook.title}}
                    </h3>
                    <button
                        @click="hideAddBookModal"
                        class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center hover:bg-gray-600 hover:text-white">
                        <svg
                            class="w-3 h-3"
                            aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 14 14">
                            <path
                                stroke="currentColor"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form @submit.prevent="updateShelvedBook" class="space-y-4">

                        <div>
                            <label for="comment" class="block mb-2 text-sm font-medium text-white">Comment</label>
                            <textarea
                                v-model="form.comment"
                                id="comment"
                                class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
                                placeholder="I liked the book a lot. The twist was very good." />
                        </div>

                        <div class="flex w-full justify-between items-center">
                            <div class="w-fit px-4">
                                <label for="status" class="block mb-2 text-sm font-medium text-white">Status</label>
                                <select
                                    v-model="form.selectedStatus"
                                    id="status"
                                    class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white">
                                    <option
                                        v-for="status in statuses"
                                        :key="status"
                                        :value="status">{{ status }}</option>
                                </select>
                            </div>

                            <div class="w-fit px-4">
                                <label class="block mb-2 text-sm font-medium text-white">Rating</label>
                                <StarRating v-model="form.rating" />
                            </div>
                        </div>

                        <DateRangePicker
                            label="Started - Finished"
                            :startDate="form.started_reading"
                            :endDate="form.finished_reading"
                            @update:startDate="form.started_reading = $event"
                            @update:endDate="form.finished_reading = $event"
                            :dark="true"
                        />

                        <hr />
                        <div class="w-full flex justify-between">
                            <button
                                type="submit"
                                class="mx-4 px-16 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm py-2.5 text-center"
                            >
                                Update
                            </button>

                            <button
                                @click="removeBookFromShelf"
                                class="mx-4 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                                Remove
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import StarRating from './StarRating.vue';
import DateRangePicker from "./DateRangePicker.vue";

const props = defineProps({
    isVisible: {
        type: Boolean,
        default: false,
    },
    selectedBook: {
        type: Object,
        required: true,
    }
});

const emit = defineEmits(['close', 'book-updated']);

const form = ref({
    selectedBook: props.selectedBook,
    comment: props.selectedBook.pivot?.comment || '',
    selectedStatus: props.selectedBook.pivot?.status || 'Want to Read',
    rating: props.selectedBook.pivot?.rating || null,
    started_reading: props.selectedBook.pivot?.started_reading_at || null,
    finished_reading: props.selectedBook.pivot?.finished_reading_at || null,
});

const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

const statuses = ref([]);

const initializeForm = () => {
    form.value = {
        selectedBook: props.selectedBook,
        comment: props.selectedBook.pivot?.comment || '',
        selectedStatus: props.selectedBook.pivot?.status || 'Want to Read',
        rating: props.selectedBook.pivot?.rating || null,
        started_reading: props.selectedBook.pivot?.started_reading_at || null,
        finished_reading: props.selectedBook.pivot?.finished_reading_at || null,
    };
};

const getStatuses = async () => {
    try {
        const response = await axios.get(`/book-user/statuses`);
        statuses.value = response.data.statuses;
    } catch (e) {
        console.error('Error fetching statuses: ', e);
    }
};

const updateShelvedBook = async () => {
    try {
        await axios.put(`/book-user/${form.value.selectedBook.id}`, {
            comment: form.value.comment,
            status: form.value.selectedStatus,
            rating: form.value.rating,
            started_reading_at: form.value.started_reading,
            finished_reading_at: form.value.finished_reading,
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken.value
            }
        });
        emit('book-updated');
        resetForm();
    } catch (error) {
        console.error('Error updating shelved book:', error.response?.data || error.message);
    }
};

const resetForm = () => {
    form.value = {
        comment: '',
        selectedBook: null,
        selectedStatus: 'Want to Read',
        rating: null,
        started_reading: null,
        finished_reading: null,
    };
};

const hideAddBookModal = () => {
    emit('close');
    resetForm();
};

const removeBookFromShelf = async () => {
    try {
        await axios.delete(`/book-user/${form.value.selectedBook.id}`, {
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken.value
            }
        });
        emit('book-updated');
        resetForm();
    } catch (error) {
        console.error('Error removing book from shelf:', error.response?.data || error.message);
    }
};

watch(() => props.selectedBook, (newValue) => {
    if (newValue) {
        initializeForm();
    }
}, {immediate: true});

onMounted(() => {
    getStatuses();
});
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
