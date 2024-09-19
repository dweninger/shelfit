<!-- resources/js/components/Dashboard.vue -->
<template>
    <div class="dashboard">
        <layout></layout>
        <h1>Welcome to the Dashboard</h1>
        <!-- Add dashboard-related content here -->
    </div>
</template>

<script>
import Layout from "./Layout.vue";

export default {
    name: 'Dashboard',
    components: {Layout},
    data() {
        return {

        };
    },
    mounted() {

    }
};
</script>

<style scoped>

</style>
