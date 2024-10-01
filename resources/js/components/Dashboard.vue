<template>
    <div class="dashboard">
        <layout></layout>
        <h1 class="text-4xl font-semibold my-4 text-center">Your Shelved Books</h1>
        <div class="w-fit mx-auto">
            <ul class="overflow-y-auto h-96 rounded-xl border-2 border-gray-400 p-4" v-if="books && books.length">
                <li v-for="(book, index) in books" :key="index">
                    <div class="flex flex-col md:flex-row items-center border border-gray-400 my-2 mx-auto rounded-lg shadow max-w-screen-xl bg-stone-300">
                        <!-- Book Image -->
                        <img class="object-cover w-full rounded-t-lg h-96 md:h-auto md:w-24 md:rounded-none md:rounded-l-lg"
                             :src="book.cover_image" alt="">

                        <!-- Book Info Section -->
                        <div class="flex flex-col justify-between p-4 leading-normal w-full min-w-[32rem]">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 w-full overflow-hidden whitespace-nowrap text-ellipsis">{{ book.title }}</h5>
                            <p class="mb-3 font-normal text-gray-700 h-12 overflow-y-auto line-clamp-2">{{ book.pivot.comment }}</p>
                        </div>

                        <!-- Rating, Edit Button, and Date Section -->
                        <div class="flex flex-col justify-between p-4 leading-normal w-full md:w-1/3 relative">
                            <!-- Edit Button -->
                            <button class="absolute top-0 right-2">
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
                            <div class="flex mx-auto">
                                <input type="date"
                                       v-model="book.pivot.started_reading_at"
                                       class="p-1 text-sm text-center text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">

                                <span class="font-bold text-center my-auto mx-2"> - </span>

                                <input type="date"
                                       v-model="book.pivot.finished_reading_at"
                                       class="p-1 text-sm text-center text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <p v-else class="text-center text-xl">No books available</p>
            <div class="flex justify-end">
                <button @click="showAddBookModal" class="font-bold mt-2 bg-stone-200 rounded border-2 border-stone-800 hover:bg-stone-300">
                    <svg class="h-8 w-8"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="12" y1="5" x2="12" y2="19" />  <line x1="5" y1="12" x2="19" y2="12" /></svg>
                </button>
            </div>
        </div>

        <AddBookToShelfModal :isVisible="isAddBookModalVisible" @close="hideAddBookModal" @book-added="handleBookAdded"/>
    </div>
</template>

<script>
import Layout from "./Layout.vue";
import axios from "axios";
import AddBookToShelfModal from "./AddBookToShelfModal.vue";
import StarRating from "./StarRating.vue";

export default {
    name: 'Dashboard',
    components: {StarRating, AddBookToShelfModal, Layout },
    data() {
        return {
            books: [],
            isAddBookModalVisible: false,
        };
    },
    mounted() {
        this.getBooks();
    },
    computed: {

    },
    methods: {
        statusColor(status) {
            switch (status){
                case 'Completed':
                    return 'text-green-800';
                case 'Did Not Finish':
                    return 'text-red-800';
                default:
                    return 'text-yellow-800';
            }
        },
        async getBooks() {
            try {
                const response = await axios.get("/books");
                this.books = response.data;
            } catch (error) {
                console.error("Error fetching books:", error);
                this.books = null;
            }
        },
        hideAddBookModal() {
            this.isAddBookModalVisible = false;
        },
        showAddBookModal() {
            this.isAddBookModalVisible = true;
        },
        handleBookAdded() {
            this.getBooks();
            this.hideAddBookModal();
        }
    }
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

</style>
