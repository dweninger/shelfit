<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative rounded-lg shadow bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t border-gray-600">
                    <h3 class="text-xl font-semibold text-white">
                        Add Book to Shelf
                    </h3>
                    <button @click="hideAddBookModal" class="text-gray-400 bg-transparent rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center hover:bg-gray-600 hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
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
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import StarRating from './StarRating.vue';
import BookSearch from './BookSearch.vue';
import DateRangePicker from "./DateRangePicker.vue";

export default {
    components: {
        DateRangePicker,
        StarRating,
        BookSearch,
    },
    props: {
        isVisible: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            form: {
                bookTitle: '',
                selectedBook: null,
                comment: '',
                selectedStatus: 'Want to Read',
                rating: null,
                started_reading: null,
                finished_reading: null,
            },
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            statuses: [],
        };
    },
    mounted() {
        this.getStatuses();
    },
    methods: {
        async getStatuses() {
            try {
                const response = await axios.get(`/book-user/statuses`);
                this.statuses = response.data.statuses;
            } catch (e) {
                console.error('Error fetching statuses: ', e);
            }
        },
        onBookSelected(book) {
          this.form.selectedBook = book;
        },
        async submitForm() {
            try {
                await axios.post('/book-user', {
                    book_id: this.form.selectedBook.id,
                    comment: this.form.comment,
                    status: this.form.selectedStatus,
                    rating: this.form.rating,
                    started_reading_at: this.form.started_reading,
                    finished_reading_at: this.form.finished_reading,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                this.$emit('book-added', this.form.selectedBook);
                this.resetForm();
            } catch (error) {
                console.error('Error adding book:', error.response?.data || error.message);
            }
        },
        resetForm() {
            this.form = {
                bookTitle: '',
                comment: '',
                selectedBook: null,
                selectedStatus: 'Want to Read',
                rating: null,
                started_reading: null,
                finished_reading: null,
            };
        },
        hideAddBookModal() {
            this.$emit('close');
            this.resetForm();
        },
    },
};
</script>


