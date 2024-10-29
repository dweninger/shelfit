<template>
    <popup-modal title="Register" :isVisible="isVisible" @close="hideRegisterModal">
        <div class="p-4 md:p-5">
            <form @submit.prevent="submitForm" class="space-y-4">
                <form-input v-model="name" field="name" label="Your name" placeholder="John Doe" required/>
                <form-input v-model="email" field="email" label="Your email" type="email" placeholder="jdoe@example.com" required/>
                <form-input v-model="password" field="password" label="Your password" type="password" placeholder="••••••••" required/>
                <form-input v-model="password_confirmation" field="password_confirmation" label="Confirm password" type="password" placeholder="••••••••" required/>
                <button type="submit" class="w-full text-white focus:ring-4 focus:outline-none font-medium rounded-lg text-sm px-5 py-2.5 text-center bg-blue-600 hover:bg-blue-700 focus:ring-blue-800">Register</button>
            </form>
        </div>
    </popup-modal>
</template>

<script setup>
import axios from 'axios';
import {ref} from "vue";
import PopupModal from "./PopupModal.vue";
import FormInput from "./FormInput.vue";

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
