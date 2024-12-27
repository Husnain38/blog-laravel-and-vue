<!-- src/App.vue -->
<template>
    <div id="app">
        <Loader />
        <Navbar />
        <router-view />
    </div>
</template>

<script>
import Navbar from './components/Navbar.vue';
import Loader from './components/Loader.vue';
export default {
    components: {
        Loader,
        Navbar,
    },
};
</script>

<style>
#app {
    font-family: 'Avenir', Helvetica, Arial, sans-serif;
}
</style>
