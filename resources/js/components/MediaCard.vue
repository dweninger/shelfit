<template>
    <div
        class="flex flex-col md:flex-row items-stretch border border-gray-400 my-3 mx-auto rounded-lg shadow max-w-screen-xl bg-slate-300">
        <!-- Book Image -->
        <img class="w-24 h-auto rounded-l-lg object-cover"
             :src="book.cover_image" :alt="book.title + ' cover'">

        <!-- Book Info Section -->
        <div class="flex flex-col justify-between p-4 leading-normal w-full min-w-[32rem]">
            <h5 class="text-2xl font-bold tracking-tight text-gray-900 w-full overflow-hidden whitespace-nowrap text-ellipsis">
                {{ book.title }}
            </h5>
            <p class="mb-2">{{ book.author }}</p>
            <p class="ml-10 mb-0 font-normal text-gray-700 h-12 overflow-y-auto line-clamp-2">
                {{ book.pivot.comment }}
            </p>
        </div>

        <div class="flex flex-col justify-between p-4 leading-normal w-full md:w-1/3 relative">
            <button class="absolute top-1 right-2" @click="emitEditEvent(book)">
                <edit-icon/>
            </button>

            <div class="flex justify-center mb-1">
                <star-rating :modelValue="book.pivot.rating"
                             @update:modelValue="emitUpdateEvent(book, 'rating', $event)"/>
            </div>

            <p :class="[statusColor(book.pivot.status), 'font-bold', 'text-center', 'mb-2']">
                {{ book.pivot.status ?? "Want to Read" }}
            </p>

            <date-range-picker
                label=""
                :startDate="book.pivot.started_reading_at"
                :endDate="book.pivot.finished_reading_at"
                @update:startDate="(value) => emitUpdateEvent(book, 'started_reading_at', value)"
                @update:endDate="(value) => emitUpdateEvent(book, 'finished_reading_at', value)"
                :dark="false"
            />
        </div>
    </div>
</template>

<script setup>
import EditIcon from "./icons/EditIcon.vue";
import StarRating from "./StarRating.vue";
import DateRangePicker from "./DateRangePicker.vue";

const emit = defineEmits(['edit', 'update']);
const props = defineProps({
    book: {
        type: Object
    },
});

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

const emitEditEvent = (book) => {
    emit('edit', book);
};

const emitUpdateEvent = (book, field, value) => {
    emit('update', book, field, value);
};
</script>
