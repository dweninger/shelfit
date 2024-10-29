<template>
<popup-modal title="Login" :isVisible="isVisible" @close="onCloseModal">
    <div class="p-4 md:p-5">
        <form @submit.prevent="submitForm" class="space-y-4">
            <div>
                <label for="email" class="block mb-2 text-sm font-medium text-white">Your email</label>
                <input
                    v-model="email"
                    id="email"
                    type="email"
                    class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
                    placeholder="name@company.com"
                    required
                />
            </div>
            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                <input
                    v-model="password"
                    id="password"
                    type="password"
                    class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white"
                    placeholder="••••••••"
                    required
                />
            </div>
            <button
                type="submit"
                class="w-full text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800"
            >
                Login
            </button>
        </form>
    </div>
</popup-modal>
</template>

<script setup>
import axios from 'axios';
import {ref} from "vue";
import PopupModal from "./PopupModal.vue";

const props = defineProps({
    isVisible: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['logged-in', 'close']);

const email = ref('');
const password = ref('');
const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

const submitForm = async () => {
    try {
        const response = await axios.post('/login', {
            email: email.value,
            password: password.value,
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken.value
            }
        });
        password.value = '';
        emit('logged-in', response?.data?.user);
    } catch (error) {
        console.error('Login error:', error.response?.data || error.message);
    }
};

const onCloseModal = () => {
    emit('close');
}
</script>
