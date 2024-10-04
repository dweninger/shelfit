<template>
    <!-- Search -->
    <div>
        <label for="book" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Search for a Book <span class="text-red-600">*</span></label>
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

</template>


<script>
import {debounce} from 'lodash';
import axios from 'axios';

export default {
    props: {
        selectedBook : {
            type: Object,
            default: null,
        }
    },
    emits: ['book-selected'],
    data() {
        return {
            bookTitle: '',
            searchResults: [],
        }
    },
    methods: {
        searchBooks: debounce(async function () {
            if (this.bookTitle.length >= 2) {
                try {
                    const response = await axios.get(`/books/search?title=${this.bookTitle}`);
                    this.searchResults = response.data;
                } catch (e) {
                    console.error('Error searching for books: ', e);
                }
            } else {
                this.searchResults = [];
            }
        }, 500),
        selectBook(book) {
            this.$emit('book-selected', book);
            this.bookTitle = book.title;
            this.searchResults = [];
        },
    },
}
</script>
