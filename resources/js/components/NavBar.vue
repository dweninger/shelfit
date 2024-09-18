<template>
    <nav class="bg-white border-gray-200 dark:bg-gray-900">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4">
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white">ShelfIt</span>
            </a>
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <div v-if="!isAuthenticated">
                    <button @click="showRegisterModal" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Register
                    </button>
                    <button @click="showLoginModal" type="button" class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                        Login
                    </button>
                </div>

                <div v-else class="relative inline-block">
                    <button
                        @click="toggleDropdown"
                        type="button"
                        class="text-white bg-gray-800 hover:bg-gray-700 focus:outline-none font-medium rounded text-sm px-5 py-2.5 text-center me-2 mb-2 dark:bg-gray-600 dark:hover:bg-gray-500 dark:focus:ring-gray-800"
                    >
                        {{ user.name }}
                    </button>
                    <div
                        v-if="dropdownOpen"
                        class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 dark:bg-gray-700"
                    >
                        <a href="/"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                            Profile
                        </a>
                        <a href="/"
                           @click="logout"
                           class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600">
                            Logout
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Register and Login Modals -->
        <RegisterModal :isVisible="isRegisterModalVisible" @close="hideRegisterModal" @registered="handleRegister" />
        <LoginModal :isVisible="isLoginModalVisible" @close="hideLoginModal" @logged-in="handleLogin" />
    </nav>
</template>

<script>
import LoginModal from "./LoginModal.vue";
import RegisterModal from "./RegisterModal.vue";
import axios from "axios";

export default {
    name: "NavBar",
    components: { RegisterModal, LoginModal },
    data() {
        return {
            dropdownOpen: false,
            user: null,
            isRegisterModalVisible: false,
            isLoginModalVisible: false,
        };
    },
    mounted() {
        this.getUser();
    },
    methods: {
        async getUser() {
            try {
                const response = await axios.get("/user");
                this.user = response.data.user;
            } catch (error) {
                this.user = null;
            }
        },
        toggleDropdown() {
            this.dropdownOpen = !this.dropdownOpen;
        },
        async logout() {
            try {
                await axios.post('/logout');
                this.getUser();
            } catch (error) {
                console.error('Logout failed:', error);
            }
        },
        showRegisterModal() {
            this.isRegisterModalVisible = true;
        },
        hideRegisterModal() {
            this.isRegisterModalVisible = false;
        },
        showLoginModal() {
            this.isLoginModalVisible = true;
        },
        hideLoginModal() {
            this.isLoginModalVisible = false;
        },
        handleLogin(user) {
            this.user = user;
            this.isLoginModalVisible = false;
            window.location.href = '/dashboard';
        },
        handleRegister(data) {
            this.user = data.user;
            this.isRegisterModalVisible = false;
        },
    },
    computed: {
        isAuthenticated() {
            return !!this.user;
        },
    },
};
</script>
