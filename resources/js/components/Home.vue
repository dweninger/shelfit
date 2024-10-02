<template>
    <Layout />
    <div class="min-h-screen bg-gray-700">
        <div class="bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-700 text-white py-10">
            <div class="container mx-auto text-center">
                <!-- Heading -->
                <h1 class="text-5xl font-bold mb-4">Welcome to Shelfit</h1>
                <p class="text-xl mb-8">Your personal space to track, organize, and share the books you've read!</p>

                <!-- Image of books -->
                <img src="/favicon.ico" alt="Books" class="mx-auto w-32 rounded-lg shadow-lg mb-8">
            </div>
        </div>
    </div>
</template>

<script setup>

import Layout from "./Layout.vue";

</script>
