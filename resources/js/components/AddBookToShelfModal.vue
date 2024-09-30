<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        Add Book to Shelf
                    </h3>
                    <button @click="hideAddBookModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form @submit.prevent="submitForm" class="space-y-4">

                        <!-- Search -->
                        <div>
                            <label for="book" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Search for a Book</label>
                            <input
                                v-model="bookTitle"
                                @input="searchBooks"
                                id="book"
                                type="text"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white"
                                placeholder="Enter book title"
                                required
                            />
                        </div>

                        <!-- Display search results -->
                        <div v-if="searchResults.length">
                            <ul class="border border-gray-300 rounded-lg max-h-40 overflow-y-auto">
                                <li
                                    v-for="(result, index) in searchResults"
                                    :key="index"
                                    @click="selectBook(result)"
                                    class="p-2 text-white hover:text-black hover:bg-gray-200 cursor-pointer"
                                >
                                    {{ result.title }}
                                </li>
                            </ul>
                        </div>

                        <div v-if="selectedBook">
                            <p class="text-gray-700 dark:text-white"><strong>Selected Book:</strong> {{ selectedBook.title }}</p>
                        </div>

                        <div>
                            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
                            <textarea v-model="comment" id="comment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="I liked the book a lot. The twist was very good." />
                        </div>

                        <div>
                            <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                            <select v-model="selectedStatus" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                            </select>
                        </div>
                        <button
                            type="submit"
                            class="w-full text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
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

export default {
    props: {
        isVisible: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            bookTitle: '',
            comment: '',
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            searchResults: [],
            selectedBook: null,
            statuses: [],
            selectedStatus: 'Want to Read',
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

            }
        },
        async searchBooks() {
          if (this.bookTitle.length >= 2) {
              try {
                  const response = await axios.get(`/books/search?title=${this.bookTitle}`);
                  this.searchResults = response.data;
              } catch (e) {

              }
          } else {
              this.searchResults = [];
          }
        },
        selectBook(book) {
          this.selectedBook = book;
          this.bookTitle = book.title;
          this.searchResults = [];
        },
        async submitForm() {
            try {
                await axios.post('/book-user', {
                    book_id: this.selectedBook.id,
                    comment: this.comment,
                    status: this.selectedStatus,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                this.$emit('book-added', this.selectedBook);
                this.bookTitle = '';
                this.comment = '';
                this.searchResults = [];
                this.selectedBook = null;
                this.selectedStatus = 'Want to Read';

            } catch (error) {
                console.error('Error adding book:', error.response?.data || error.message);
            }
        },
        hideAddBookModal() {
            this.$emit('close');
        },
    },
};
</script>
