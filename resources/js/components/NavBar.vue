<template>
    <nav class="sticky top-0 border-gray-200 bg-gray-900 z-10">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap text-white">ShelfIt</span>
            </a>
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <div v-if="!isAuthenticated">
                    <button @click="showRegisterModal" type="button" class="text-white focus:outline-none focus:ring-4 font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 bg-green-600 hover:bg-green-700 focus:ring-green-800">
                        Register
                    </button>
                    <button @click="showLoginModal" type="button" class="text-white focus:outline-none focus:ring-4 font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 bg-green-600 hover:bg-green-700 focus:ring-green-800">
                        Login
                    </button>
                </div>

                <div v-else class="relative inline-block">
                    <button
                        @click="toggleDropdown"
                        type="button"
                        class="text-white focus:outline-none font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 bg-gray-600 hover:bg-gray-500 focus:ring-gray-800"
                    >
                        {{ user?.name }}
                    </button>
                    <div
                        v-if="dropdownOpen"
                        class="absolute left-0 mt-2 w-48 bg-gray-500 rounded-md shadow-lg py-1"
                    >
                        <a href="/dashboard"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">
                            Dashboard
                        </a>
                        <a href="/"
                           @click="logout"
                           class="block px-4 py-2 text-sm text-gray-300 hover:bg-gray-600">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register and Login Modals -->
        <register-modal :isVisible="isRegisterModalVisible" @close="hideRegisterModal" @registered="handleRegister" />
        <login-modal :isVisible="isLoginModalVisible" @close="hideLoginModal" @logged-in="handleLogin" />
    </nav>
</template>

<script setup>
import LoginModal from "./modals/LoginModal.vue";
import RegisterModal from "./modals/RegisterModal.vue";
import axios from "axios";
import {computed, onMounted, ref} from "vue";
import modalVisibility from "../composables/modalVisibility";

const dropdownOpen = ref(false);
const user = ref(null);

const { isModalVisible: isLoginModalVisible, showModal: showLoginModal, hideModal: hideLoginModal } = modalVisibility();
const { isModalVisible: isRegisterModalVisible, showModal: showRegisterModal, hideModal: hideRegisterModal } = modalVisibility();

onMounted(() => {
    getUser();
});

const getUser = async () => {
    try {
        const response = await axios.get("/user");
        user.value = response?.data?.user;
    } catch (error) {
        user.value = null;
    }
};

const toggleDropdown = () => {
    dropdownOpen.value = !dropdownOpen.value;
};

const logout = async () => {
    try {
        await axios.post('/logout');
        getUser();
    } catch (error) {
        console.error('Logout failed:', error);
    }
};

const handleLogin = (user) => {
    user.value = user;
    window.location.href = '/dashboard';
};

const handleRegister = (data) => {
    user.value = data.user;
    window.location.href = '/dashboard';
};

const isAuthenticated = computed(() => !!user.value);

</script>
