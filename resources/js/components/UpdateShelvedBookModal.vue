<template>
    <popup-modal :title="form.selectedBook.title" :isVisible="isVisible" @close="hideUpdateModal">
        <div class="p-4 md:p-5">
            <form @submit.prevent="updateShelvedBook" class="space-y-4">
                <form-text-area v-model="form.comment" field="comment" label="Comment" placeholder="I liked the book a lot. The twist was very good." />

                <div class="flex w-full justify-between items-center">

                    <form-select v-model="form.selectedStatus" :options="statuses" label="Status" field="status"/>

                    <div class="w-fit px-4">
                        <label class="block mb-2 text-sm font-medium text-white">Rating</label>
                        <star-rating v-model="form.rating" />
                    </div>
                </div>

                <date-range-picker
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
    </popup-modal>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';
import StarRating from './StarRating.vue';
import DateRangePicker from "./DateRangePicker.vue";
import PopupModal from "./PopupModal.vue";
import FormTextArea from "./FormTextArea.vue";
import FormSelect from "./FormSelect.vue";

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

const hideUpdateModal = () => {
    emit('close');
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
