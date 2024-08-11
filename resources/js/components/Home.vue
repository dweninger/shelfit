<template>
    <NavBar @open-register-modal="showRegisterModal" />
    <RegisterModal :isVisible="isModalVisible" @close="hideRegisterModal" />
</template>

<script setup>
import { ref } from 'vue';
import NavBar from './NavBar.vue';
import RegisterModal from './RegisterModal.vue';

const isModalVisible = ref(false);

function showRegisterModal() {
    isModalVisible.value = true;
}

function hideRegisterModal() {
    isModalVisible.value = false;
}
</script>
