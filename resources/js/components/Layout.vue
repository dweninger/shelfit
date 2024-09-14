<template>
    <NavBar @open-register-modal="showRegisterModal" @open-login-modal="showLoginModal" />
    <RegisterModal :isVisible="isModalVisible" @close="hideRegisterModal" />
    <LoginModal :is-visible="isLoginModalVisible" @close="hideLoginModal" />
</template>

<script setup>
import { ref } from 'vue';
import NavBar from './NavBar.vue';
import RegisterModal from './RegisterModal.vue';
import LoginModal from './LoginModal.vue';

const isModalVisible = ref(false);
const isLoginModalVisible = ref(false);

function showRegisterModal() {
    isModalVisible.value = true;
}

function hideRegisterModal() {
    isModalVisible.value = false;
}

function showLoginModal() {
    isLoginModalVisible.value = true;
}

function hideLoginModal() {
    isLoginModalVisible.value = false;
}
</script>
