<template>
    <div class="login-container">
        <h2>Login</h2>
        <form @submit.prevent="login">
            <div>
                <label for="email">Email</label>
                <input v-model="email" type="email" id="email" class="form-control"  placeholder="Enter your email" required />
            </div>
            <div>
                <label for="password">Password</label>
                <input v-model="password" type="password" id="password" class="form-control" placeholder="Enter your password" required />
            </div>
            <button type="submit" class="btn btn-primary mb-2 w-100">Login</button>
        </form>
        <p>
            Don't have an account? <router-link to="/register">Register here</router-link>
        </p>
    </div>
</template>

<script>
import { mapActions } from 'vuex';

export default {
    data() {
        return {
            email: '',
            password: '',
            loading: false,
        };
    },
    methods: {
        async login() {
            if (this.$store.state.token) {
                this.$router.push('/');
                return;
            }
            this.$store.dispatch('login',{ email: this.email, password: this.password })
                .then(() => {
                    this.$toast.success('Login successful!')
                    this.$router.push('/');
                })
                .catch((error) => {
                    this.$toast.error('Login failed');
                });
        },
    },
};
</script>

<style scoped>
.login-container {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
}
form div {
    margin-bottom: 10px;
}
</style>
