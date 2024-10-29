<template>
    <popup-modal title="Add Book to Shelf" :isVisible="isVisible" @close="hideAddBookModal">
        <div class="p-4 md:p-5">
            <form @submit.prevent="submitForm" class="space-y-4">

                <BookSearch :selectedBook="form.selectedBook" @book-selected="onBookSelected" />

                <div v-if="form.selectedBook">
                    <p class="text-white"><strong>Selected Book:</strong> {{ form.selectedBook.title }}</p>
                </div>

                <div>
                    <label for="comment" class="block mb-2 text-sm font-medium text-white">Comment</label>
                    <textarea v-model="form.comment" id="comment" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white" placeholder="I liked the book a lot. The twist was very good." />
                </div>

                <div class="flex w-full justify-between items-center">
                    <div class="w-fit px-4">
                        <label for="status" class="block mb-2 text-sm font-medium text-white">Status</label>
                        <select v-model="form.selectedStatus" id="status" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white">
                            <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
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

                <button
                    type="submit"
                    class="w-full text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
                >
                    Add Book
                </button>
            </form>
        </div>
    </popup-modal>

</template>

<script setup>
import axios from 'axios';
import StarRating from './StarRating.vue';
import BookSearch from './BookSearch.vue';
import DateRangePicker from "./DateRangePicker.vue";
import {onMounted, ref} from "vue";
import PopupModal from "./PopupModal.vue";

const props = defineProps({
        isVisible: {
            type: Boolean,
            default: false,
        },
});

const emit = defineEmits(['book-added', 'close']);

const form = ref({
    bookTitle: '',
    selectedBook: null,
    comment: '',
    selectedStatus: 'Want to Read',
    rating: null,
    started_reading: null,
    finished_reading: null,
});

const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
const statuses = ref([]);

onMounted(() => {
    getStatuses();
});

const getStatuses = async () => {
    try {
        const response = await axios.get(`/book-user/statuses`);
        statuses.value = response.data.statuses;
    } catch (e) {
        console.error('Error fetching statuses: ', e);
    }
};

const onBookSelected = (book) => {
  form.value.selectedBook = book;
};

const submitForm = async () => {
    try {
        await axios.post('/book-user', {
            book_id: form.value.selectedBook.id,
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
        emit('book-added', form.value.selectedBook);
        resetForm();
    } catch (error) {
        console.error('Error adding book:', error.response?.data || error.message);
    }
};

const resetForm = () => {
    form.value = {
        bookTitle: '',
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
</script>


