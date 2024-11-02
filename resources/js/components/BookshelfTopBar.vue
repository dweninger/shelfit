<template>
    <div class="my-5">
        <div class="flex justify-between text-white">
            <div class="flex items-end">
                <h1 class="text-5xl">Books</h1>
                <p class="ml-4 text-gray-200">{{books.length}} Items</p>
            </div>
            <div></div>
            <div></div>
            <div class="flex items-end">
                <form @submit.prevent="sendSearchQuery" class="max-w-lg mx-12">
                    <div class="flex">
                        <div class="relative w-full">
                            <input v-model="searchText" type="search" id="search-dropdown" class="block p-2.5 w-full z-20 text-sm text-wite rounded rounded-e-lg border focus:ring-blue-500 focus:border-blue-500 bg-gray-700 border-slate-200 placeholder-gray-300" placeholder="Search Books" />
                            <button type="submit" class="absolute top-0 end-0 p-2.5 text-sm font-medium h-full text-white bg-blue-700 rounded-e-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                <svg class="w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                                <span class="sr-only">Search</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="items-end">
                <label for="sort" class="block mb-2 text-sm font-medium text-white">Sort by</label>
                <select
                    id="sort"
                    v-model="sortOption"
                    @change="sendSortOrder"
                    class="block p-2.5 w-full z-20 text-sm text-wite rounded rounded-e-lg border focus:ring-blue-500 focus:border-blue-500 bg-gray-700 border-slate-200 placeholder-gray-300">
                    <option selected value="your-order">Your Order</option>
                    <option value="title-a">Title A-Z</option>
                    <option value="title-z">Title Z-A</option>
                    <option value="author-a">Author A-Z</option>
                    <option value="author-z">Author Z-A</option>
                    <option value="rating-high">Rating High-Low</option>
                    <option value="rating-low">Rating Low-High</option>
                    <option value="started-recent">Started Recent</option>
                    <option value="started-oldest">Started Oldest</option>
                    <option value="finished-recent">Finished Recent</option>
                    <option value="finished-oldest">Finished Oldest</option>
                    <option value="status-a">Status A-Z</option>
                    <option value="status-z">Status Z-A</option>
                </select>
            </div>
        </div>
        <hr class="mt-2" />
    </div>
</template>

<script setup>
import {ref} from "vue";

const emit = defineEmits(['search', 'sort']);
const searchText = ref('');
const sortOption = ref('your-order');

const props = defineProps({
   books: {
        type: Array
   }
});

const sendSearchQuery = () => {
    emit('search', searchText.value);
};

const sendSortOrder = () => {
    emit('sort', sortOption.value)
}
</script>

<style scoped>

</style>
