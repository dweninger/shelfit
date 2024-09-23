<template>
    <div class="dashboard">
        <layout></layout>
        <h1>Welcome to the Dashboard</h1>
        <ul v-if="books && books.length">
            <li v-for="(book, index) in books" :key="index">
                {{ book.title }}
            </li>
        </ul>
        <p v-else>No books available</p>
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
    methods: {
        async getBooks() {
            try {
                const response = await axios.get("/books");
                console.log(response);
                this.books = response.data;
            } catch (error) {
                console.error("Error fetching books:", error);
                this.books = null;
            }
        }
    }
};
</script>

<style scoped>

</style>
