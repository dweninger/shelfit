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
                             src="https://m.media-amazon.com/images/I/81TVqiv-ctL._AC_UF1000,1000_QL80_.jpg" alt="">

                        <!-- Book Info Section -->
                        <div class="flex flex-col justify-between p-4 leading-normal w-full md:w-2/3">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900">{{ book.title }}</h5>
                            <p class="mb-3 font-normal text-gray-700">{{ book.pivot.comment }}</p>
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
                                <div class="flex items-center">
                                    <svg v-for="n in book.pivot.rating" :key="n" class="w-4 h-4 text-blue-700" xmlns="http://www.w3.org/2000/svg"
                                         fill="currentColor" viewBox="0 0 22 20" aria-hidden="true">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                    <svg v-for="n in 5 - book.pivot.rating" :key="'empty-' + n" class="w-4 h-4 text-gray-400"
                                         xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 22 20" aria-hidden="true">
                                        <path
                                            d="M20.924 7.625a1.523 1.523 0 0 0-1.238-1.044l-5.051-.734-2.259-4.577a1.534 1.534 0 0 0-2.752 0L7.365 5.847l-5.051.734A1.535 1.535 0 0 0 1.463 9.2l3.656 3.563-.863 5.031a1.532 1.532 0 0 0 2.226 1.616L11 17.033l4.518 2.375a1.534 1.534 0 0 0 2.226-1.617l-.863-5.03L20.537 9.2a1.523 1.523 0 0 0 .387-1.575Z" />
                                    </svg>
                                </div>
                            </div>

                            <!-- Status -->
                            <p :class="[statusColor(book.pivot.status), 'font-bold', 'text-center', 'mb-2']">{{ book.pivot.status }}</p>

                            <!-- Date Pickers -->
                            <div class="flex mx-auto">
                                <input type="date"
                                       class="p-1 text-sm text-center text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">

                                <span class="font-bold text-center my-auto mx-2"> - </span>

                                <input type="date"
                                       class="p-1 text-sm text-center text-gray-900 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <p v-else class="text-center text-xl">No books available</p>
            <div class="flex justify-end">
                <button class="">
                    <svg class="h-8 w-8"  width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">  <path stroke="none" d="M0 0h24v24H0z"/>  <line x1="12" y1="5" x2="12" y2="19" />  <line x1="5" y1="12" x2="19" y2="12" /></svg>                </button>
            </div>
        </div>
    </div>
</template>

<script>
import Layout from "./Layout.vue";
import axios from "axios";

export default {
    name: 'Dashboard',
    components: {Layout},
    data() {
        return {
            books: [],
        };
    },
    mounted() {
        this.getBooks();
    },
    computed: {

    },
    methods: {
        async getBooks() {
            try {
                const response = await axios.get("/books");
                this.books = response.data;
            } catch (error) {
                console.error("Error fetching books:", error);
                this.books = null;
            }
        },
        statusColor(status) {
            return status === 'Completed' ? 'text-green-800' : 'text-red-800'
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
