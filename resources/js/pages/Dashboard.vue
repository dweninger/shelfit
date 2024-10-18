<template>
    <div class="dashboard min-h-screen bg-gray-700">
        <layout></layout>
        <h1 class="text-4xl font-semibold my-4 text-white text-center">Your Shelved Books</h1>
        <div class="w-fit mx-auto">
            <ul class="scrollable-ul overflow-y-auto h-[40rem] shadow-2xl rounded-xl border-2 border-gray-400 p-4" v-if="books && books.length">
                <li v-for="(book, index) in books" :key="index">
                    <div class="flex flex-col md:flex-row items-stretch border border-gray-400 my-3 mx-auto rounded-lg shadow max-w-screen-xl bg-stone-300">
                        <!-- Book Image -->
                        <img class="w-24 h-auto rounded-l-lg object-cover"
                             :src="book.cover_image" :alt="book.title + ' cover'">

                        <!-- Book Info Section -->
                        <div class="flex flex-col justify-between p-4 leading-normal w-full min-w-[32rem]">
                            <h5 class="text-2xl font-bold tracking-tight text-gray-900 w-full overflow-hidden whitespace-nowrap text-ellipsis">{{ book.title }}</h5>
                            <p class="mb-2">{{book.author}} | {{book.published_at.split('-')[0]}}</p>
                            <p class="mb-0 font-normal text-gray-700 h-12 overflow-y-auto line-clamp-2">{{ book.pivot.comment }}</p>
                        </div>

                        <!-- Rating, Edit Button, and Date Section -->
                        <div class="flex flex-col justify-between p-4 leading-normal w-full md:w-1/3 relative">
                            <!-- Edit Button -->
                            <button class="absolute top-1 right-2" @click="showUpdateModal(book)">
                                <svg class="h-8 w-8 text-gray-700" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" />
                                    <path d="M9 7 h-3a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-3" />
                                    <path d="M9 15h3l8.5 -8.5a1.5 1.5 0 0 0 -3 -3l-8.5 8.5v3" />
                                    <line x1="16" y1="5" x2="19" y2="8" />
                                </svg>
                            </button>

                            <!-- Star Rating -->
                            <div class="flex justify-center mb-1">
                                <StarRating :modelValue="book.pivot.rating" />
                            </div>

                            <!-- Status -->
                            <p :class="[statusColor(book.pivot.status), 'font-bold', 'text-center', 'mb-2']">{{ book.pivot.status ?? "Want to Read" }}</p>

                            <!-- Date Pickers -->
                            <DateRangePicker
                                label=""
                                :startDate="book.pivot.started_reading_at"
                                :endDate="book.pivot.finished_reading_at"
                                @update:startDate="(value) => updateBookDate(book, 'started_reading_at', value)"
                                @update:endDate="(value) => updateBookDate(book, 'finished_reading_at', value)"
                                :dark="false"
                            />
                        </div>
                    </div>
                </li>
            </ul>
            <p v-else class="text-center text-xl">No books available</p>
            <div class="flex justify-end pb-12">
                <button @click="showAddBookModal" class="font-bold mt-2 text-white bg-blue-600 hover:brightness-90 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm p-0.5 text-center">
                    <svg class="h-8 w-8"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="12" y1="5" x2="12" y2="19" />  <line x1="5" y1="12" x2="19" y2="12" /></svg>
                </button>
            </div>
        </div>

        <AddBookToShelfModal :isVisible="isAddBookModalVisible" @close="hideAddBookModal" @book-added="handleBookAdded"/>
        <UpdateShelvedBookModal :isVisible="isUpdateModalVisible" :selectedBook="selectedBook" @close="hideUpdateModal" @book-updated="handleBookUpdated"/>
    </div>
</template>

<script setup>
import {ref, onMounted} from "vue";
import Layout from "../components/Layout.vue";
import axios from "axios";
import AddBookToShelfModal from "../components/AddBookToShelfModal.vue";
import StarRating from "../components/StarRating.vue";
import UpdateShelvedBookModal from "../components/UpdateShelvedBookModal.vue";
import DateRangePicker from "../components/DateRangePicker.vue";

const books = ref([])
const isAddBookModalVisible = ref(false)
const isUpdateModalVisible = ref(false)
const selectedBook = ref({})

onMounted(() => {
    getBooks();
});
const statusColor = (status) => {
    switch (status){
        case 'Completed':
            return 'text-green-800';
        case 'Did Not Finish':
            return 'text-red-800';
        default:
            return 'text-yellow-800';
    }
};

const getBooks = async () => {
    try {
        const response = await axios.get("/books");
        books.value = response.data;
    } catch (error) {
        console.error("Error fetching books:", error);
        books.value = null;
    }
};

const hideAddBookModal = () => {
    isAddBookModalVisible.value = false;
};

const showAddBookModal = () => {
    isAddBookModalVisible.value = true;
};

const handleBookAdded = () => {
    getBooks();
    hideAddBookModal();
};

const hideUpdateModal = () => {
    selectedBook.value = {};
    isUpdateModalVisible.value = false;
};

const showUpdateModal = (book) => {
    selectedBook.value = book;
    isUpdateModalVisible.value = true;
};

const handleBookUpdated = () => {
    selectedBook.value = {};
    getBooks();
    hideUpdateModal();
};
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

.scrollable-ul::-webkit-scrollbar {
    width: 8px;
}

.scrollable-ul::-webkit-scrollbar-track {
    background: none;
    margin-right: 2px;
}

.scrollable-ul::-webkit-scrollbar-thumb {
    background-color: gray;
    border-radius: 10px;
    border: 1px solid darkslategray;
}

.scrollable-ul::-webkit-scrollbar-thumb:hover {
    background-color: darkgray;
}

</style>
