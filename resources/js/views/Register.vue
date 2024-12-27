<template>
    <div class="register-container">
        <h2>Register</h2>
        <form @submit.prevent="register">
            <div>
                <label for="name">Name</label>
                <input v-model="name" type="text" id="name" placeholder="Enter your name" class="form-control" required />
            </div>
            <div>
                <label for="email">Email</label>
                <input v-model="email" type="email" id="email" placeholder="Enter your email" class="form-control" required />
            </div>
            <div>
                <label for="password">Password</label>
                <input v-model="password" type="password" id="password" placeholder="Enter your password" class="form-control" required />
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input v-model="password_confirmation" type="password" id="password_confirmation" placeholder="Confirm your password" class="form-control" required />
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-2">Register</button>
        </form>
        <p>
            Already have an account? <router-link to="/login">Login here</router-link>
        </p>
    </div>
</template>

<script>

export default {
    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
        };
    },
    methods: {
        async register() {
            try {
                this.$store.dispatch('register',{
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                })
                    .then(() => {
                        this.$toast.success('Registration successful!')
                        this.$router.push('/');
                    })
                    .catch((error) => {
                        this.$toast.error('Registration failed')
                        console.error('Registration failed', error);
                    });
            } catch (error) {
                console.error('Register failed', error);
                this.$toast.error('Registration failed. Please try again.')
            }
        },
    },
};
</script>

<style scoped>
.register-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}
form div {
    margin-bottom: 10px;
}
</style>
