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
            // Define any dashboard data or state here
        };
    },
    mounted() {
        // Any API calls or actions when the component is mounted
    }
};
</script>

<style scoped>

</style>
