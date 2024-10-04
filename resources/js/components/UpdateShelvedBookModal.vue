<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{form.selectedBook.title}}
                    </h3>
                    <button @click="hideAddBookModal" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <div class="p-4 md:p-5">
                    <form @submit.prevent="updateShelvedBook" class="space-y-4">

                        <div>
                            <label for="comment" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Comment</label>
                            <textarea v-model="form.comment" id="comment" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" placeholder="I liked the book a lot. The twist was very good." />
                        </div>

                        <div class="flex w-full justify-between items-center">
                            <div class="w-fit px-4">
                                <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select v-model="form.selectedStatus" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                    <option v-for="status in statuses" :key="status" :value="status">{{ status }}</option>
                                </select>
                            </div>

                            <div class="w-fit px-4">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Rating</label>
                                <StarRating v-model="form.rating" />
                            </div>
                        </div>

                        <div class="w-fit mx-auto">
                            <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Started - Finished</label>
                            <div class="flex mx-auto">
                                <input type="date"
                                       class="p-1 text-sm text-center bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white appearance-none"
                                       v-model="form.started_reading">

                                <span class="font-bold text-center my-auto mx-2 text-white"> - </span>

                                <input type="date"
                                       class="p-1 text-sm text-center bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white appearance-none"
                                       v-model="form.finished_reading">
                            </div>
                        </div>

                        <hr />
                        <div class="w-full flex justify-between">
                            <button
                                type="submit"
                                class="mx-4 px-16 text-white bg-blue-600 hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm py-2.5 text-center"
                            >
                                Update
                            </button>

                            <button
                                @click="removeBookFromShelf"
                                class="mx-4 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center"
                            >
                                Remove
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import StarRating from './StarRating.vue';

export default {
    components: {
        StarRating
    },
    props: {
        isVisible: {
            type: Boolean,
            default: false,
        },
        selectedBook: {
            type: Object,
            required: true,
        },
    },
    data() {
        return {
            form: {
                selectedBook: this.selectedBook,
                comment: this.selectedBook.pivot?.comment || '',
                selectedStatus: this.selectedBook.pivot?.status || 'Want to Read',
                rating: this.selectedBook.pivot?.rating || null,
                started_reading: this.selectedBook.pivot?.started_reading_at || null,
                finished_reading: this.selectedBook.pivot?.finished_reading_at || null,
            },
            csrfToken: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            statuses: [],
        };
    },
    mounted() {
        this.getStatuses();
    },
    watch: {
        selectedBook: {
            immediate: true,
            handler(newValue) {
                if (newValue) {
                    this.initializeForm();
                }
            }
        }
    },
    methods: {
        initializeForm() {
            this.form = {
                selectedBook: this.selectedBook,
                comment: this.selectedBook.pivot?.comment || '',
                selectedStatus: this.selectedBook.pivot?.status || 'Want to Read',
                rating: this.selectedBook.pivot?.rating || null,
                started_reading: this.selectedBook.pivot?.started_reading_at || null,
                finished_reading: this.selectedBook.pivot?.finished_reading_at || null,
            };
        },
        async getStatuses() {
            try {
                const response = await axios.get(`/book-user/statuses`);
                this.statuses = response.data.statuses;
            } catch (e) {
                console.error('Error fetching statuses: ', e);
            }
        },
        async updateShelvedBook() {
            try {
                await axios.put(`/book-user/${this.form.selectedBook.id}`, {
                    comment: this.form.comment,
                    status: this.form.selectedStatus,
                    rating: this.form.rating,
                    started_reading_at: this.form.started_reading,
                    finished_reading_at: this.form.finished_reading,
                }, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                this.$emit('book-updated');
                this.resetForm();
            } catch (error) {
                console.error('Error updating shelved book:', error.response?.data || error.message);
            }
        },
        resetForm() {
            this.form = {
                comment: '',
                selectedBook: null,
                selectedStatus: 'Want to Read',
                rating: null,
                started_reading: null,
                finished_reading: null,
            };
        },
        hideAddBookModal() {
            this.$emit('close');
            this.resetForm();
        },
        async removeBookFromShelf() {
            try {
                await axios.delete(`/book-user/${this.form.selectedBook.id}`, {
                }, {
                    headers: {
                        'X-CSRF-TOKEN': this.csrfToken
                    }
                });
                this.$emit('book-updated');
                this.resetForm();
            } catch (error) {
                console.error('Error removing book from shelf:', error.response?.data || error.message);
            }
        },
    },
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
