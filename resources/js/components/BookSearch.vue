<template>
    <div>
        <label for="book" class="block mb-2 text-sm font-medium text-white">Search for a Book <span
            class="text-red-600">*</span></label>
        <input
            v-model="bookTitle"
            @input="searchBooks"
            id="book"
            type="text"
            class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
            placeholder="Enter book title"
            required
        />
    </div>

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
    <div v-else-if="!searchResults.length && bookTitle.length >= 2 && !selectedBook">
        <ul class="border border-gray-300 rounded-lg max-h-40 overflow-y-auto">
            <li class="p-2 text-white">
                No results
            </li>
        </ul>
    </div>

</template>


<script setup>
import {debounce} from 'lodash';
import axios from 'axios';
import {ref} from "vue";

const props = defineProps({
    selectedBook: {
        type: Object,
        default: null,
    }
});
const emit = defineEmits(['book-selected']);

const bookTitle = ref('')
const searchResults = ref([]);
const googleApiKey = import.meta.env.VITE_GOOGLE_BOOKS_API_KEY;

const searchBooks = debounce(async () => {
    if (bookTitle.value.length >= 2) {
        try {
            const response = await axios.get(`/books/search?title=${bookTitle.value}`);
            searchResults.value = response.data;
            if (searchResults.value.length === 0) {
                const googleResponse = await axios.get(`/search-books?title=${bookTitle.value}`);
                const googleBooks = googleResponse.data.items || [];

                const formattedBooks = googleBooks.map(book => {
                    const publishedDateStr = book.volumeInfo.publishedDate || null;
                    let publishedAt = null;

                    if (publishedDateStr) {
                        if (/^\d{4}$/.test(publishedDateStr)) {
                            publishedAt = new Date(`${publishedDateStr}-01-01`);
                        } else if (/^\d{4}-\d{2}$/.test(publishedDateStr)) {
                            publishedAt = new Date(`${publishedDateStr}-01`);
                        } else {
                            publishedAt = new Date(publishedDateStr).toISOString().slice(0, 10);
                        }
                    }

                    return {
                        title: book.volumeInfo.title || 'Unknown Title',
                        author: (book.volumeInfo.authors && book.volumeInfo.authors[0]) || 'Unknown Author',
                        cover_image: (book.volumeInfo.imageLinks && book.volumeInfo.imageLinks.thumbnail) || null,
                        genre: (book.volumeInfo.categories && book.volumeInfo.categories[0]) || 'Unknown Genre',
                        published_at: publishedAt // Now a Date object or null
                    };
                });

                const uniqueBooks = Array.from(
                    new Map(formattedBooks.reverse().map(book => [book.title, book])).values()
                );

                searchResults.value = uniqueBooks.reverse();
            }
        } catch (e) {
            console.error('Error searching for books: ', e);
        }
    } else {
        searchResults.value = [];
    }
}, 100);

const selectBook = (book) => {
    emit('book-selected', book);
    bookTitle.value = book.title;
    searchResults.value = [];
};
</script>
