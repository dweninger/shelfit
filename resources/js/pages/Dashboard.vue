<template>
    <div class="dashboard min-h-screen bg-gray-700">
        <layout></layout>
        <div class="w-fit mx-auto">
            <bookshelf-top-bar :books="filteredBooks" @search="handleSearch"/>
            <div class="scrollable-ul overflow-y-auto max-h-[60vh] min-h-50 p-4"
                v-if="filteredBooks && filteredBooks.length">
                <div v-if="searchQuery" v-for="book in filteredBooks">
                    <media-card :book="book" @edit="onEditBookButtonPressed" @update="updateBookField"/>
                </div>
                <draggable v-else v-model="books" item-key="id" class="drag-item" @end="updateSortOrder">
                    <template #item="{element: book, index}">
                        <media-card :book="book" @edit="onEditBookButtonPressed" @update="updateBookField"/>
                    </template>
                </draggable>
            </div>
            <p v-else class="text-center text-xl">No books available</p>
            <div class="flex justify-end pb-12">
                <button @click="showAddBookModal"
                        class="font-bold mt-4 text-white bg-blue-600 hover:brightness-90 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg text-sm p-0.5 text-center">
                    <plus-icon/>
                </button>
            </div>
        </div>

        <add-book-to-shelf-modal :isVisible="isAddBookModalVisible" @close="hideAddBookModal"
                                 @book-added="handleBookAdded"/>
        <update-shelved-book-modal :isVisible="isUpdateModalVisible" :selectedBook="selectedBook"
                                   @close="hideUpdateModal" @book-updated="handleBookUpdated"/>
    </div>
</template>

<script setup>
import {computed, onBeforeMount, ref} from "vue";
import axios from "axios";
import Layout from "../components/Layout.vue";
import AddBookToShelfModal from "../components/modals/AddBookToShelfModal.vue";
import UpdateShelvedBookModal from "../components/modals/UpdateShelvedBookModal.vue";
import PlusIcon from "../components/icons/PlusIcon.vue";
import modalVisibility from "../composables/modalVisibility";
import draggable from "vuedraggable";
import BookshelfTopBar from "../components/BookshelfTopBar.vue";
import {debounce} from "lodash";
import MediaCard from "../components/MediaCard.vue";

const books = ref([]);
const selectedBook = ref({});
const searchQuery = ref('');

const {
    isModalVisible: isAddBookModalVisible,
    showModal: showAddBookModal,
    hideModal: hideAddBookModal
} = modalVisibility();
const {
    isModalVisible: isUpdateModalVisible,
    showModal: showUpdateModal,
    hideModal: hideUpdateModal
} = modalVisibility();

onBeforeMount(() => {
    getBooks();
})

const statusColor = (status) => {
    switch (status) {
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

const updateBookField = debounce(async (book, field, newValue) => {
    try {
        await axios.put(`/book-user/${book.id}`, {
            [field]: newValue
        });
        book.pivot[field] = newValue;
    } catch (error) {
        console.error("Failed to update book date:", error);
    }
}, 500);

const updateSortOrder = async () => {
    const sortedBooks = books.value.map((book, index) => ({id: book.id, sort_order: index + 1}));
    await axios.post("/book-user/update-order", {books: sortedBooks});
};

const handleBookAdded = () => {
    getBooks();
    hideAddBookModal();
};

const handleBookUpdated = () => {
    selectedBook.value = {};
    getBooks();
    hideUpdateModal();
};

const onEditBookButtonPressed = (book) => {
    selectedBook.value = book;
    showUpdateModal()
};

const filteredBooks = computed(() => {
    const query = searchQuery.value.toLowerCase();
    return books.value.filter(book =>
        book.title.toLowerCase().includes(query) ||
        book.author.toLowerCase().includes(query) ||
        book.pivot.comment?.toLowerCase().includes(query) ||
        book.pivot.started_reading_at?.toLowerCase().includes(query) ||
        book.pivot.finished_reading_at?.toLowerCase().includes(query) ||
        book.pivot.status?.toLowerCase().includes(query)
    );
});

const handleSearch = (query) => {
    searchQuery.value = query;
};
</script>

<style scoped>
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

.drag-item:hover {
    cursor: move;
}
</style>
