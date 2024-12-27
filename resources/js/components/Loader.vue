<template>
    <div v-if="isLoading" class="screen-loader">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>
</template>

<script>
export default {
    name: "Loader",
    computed: {
        isLoading() {
            return this.$store.state.loading;
        }
    }
};
</script>

<style scoped>
.screen-loader {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    color: white;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
    z-index: 9999;
}

.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid white;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
