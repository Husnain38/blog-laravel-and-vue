<template>
    <div v-if="isVisible" class="modal-overlay">
        <div class="modal-content">
            <p>{{ message }}</p>
            <div class="modal-actions">
                <button @click="confirmAction" class="btn btn-danger">Yes, Delete</button>
                <button @click="cancelAction" class="btn btn-secondary">Cancel</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DeleteModal',
    props: {
        message: {
            type: String,
            required: true
        },
        action: {
            type: Function,
            required: true
        }
    },
    data() {
        return {
            isVisible: false
        };
    },
    methods: {
        // This is the method to open the modal
        openModal() {
            this.isVisible = true;
        },
        // This method hides the modal
        closeModal() {
            this.isVisible = false;
        },
        // This triggers the action passed as prop
        confirmAction() {
            this.action();
            this.closeModal();
        },
        cancelAction() {
            this.closeModal();
        }
    }
};
</script>

<style scoped>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    width: 300px;
    text-align: center;
}

.modal-actions button {
    margin: 5px;
}
</style>
