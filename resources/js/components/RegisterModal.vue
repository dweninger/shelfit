<template>
    <popup-modal title="Register" :isVisible="isVisible" @close="hideRegisterModal">
        <div class="p-4 md:p-5">
            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-white">Your name</label>
                    <input v-model="name" id="name" type="text" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white" placeholder="John Doe" required />
                </div>
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-white">Your email</label>
                    <input v-model="email" id="email" type="email" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white" placeholder="name@company.com" required />
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-white">Your password</label>
                    <input v-model="password" id="password" type="password" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white" placeholder="••••••••" required />
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-white">Confirm password</label>
                    <input v-model="password_confirmation" id="password_confirmation" type="password" class="border text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 bg-gray-600 border-gray-500 placeholder-gray-400 text-white" placeholder="••••••••" required />
                </div>
                <button type="submit" class="w-full text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Register</button>
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

const emit = defineEmits(['registered', 'close']);

const name = ref('');
const email = ref('');
const password = ref('');
const password_confirmation = ref('');
const csrfToken = ref(document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

const submitForm = async () => {
    try {
        const response = await axios.post('/register', {
            name: name.value,
            email: email.value,
            password: password.value,
            password_confirmation: password_confirmation.value,
        }, {
            headers: {
                'X-CSRF-TOKEN': csrfToken.value,
            },
        });
        name.value = '';
        email.value = '';
        password.value = '';
        password_confirmation.value = '';
        emit('registered', response.data);
    } catch (error) {
        console.error('Registration error:', error.response?.data || error.message);
    }
}

const hideRegisterModal = () => {
    emit('close');
}
</script>
