<template>
    <nav class="d-flex justify-content-between align-items-center">
        <ul>
            <li ><router-link to="/">Blogs</router-link></li>
            <li v-if="isAuthenticated"><router-link to="/add-post">Add Post</router-link></li>


        </ul>
        <ul>
            <li v-if="!isAuthenticated"><router-link to="/login">Login</router-link></li>
            <li v-if="!isAuthenticated"><router-link to="/register">Register</router-link></li>
            <li v-if="isAuthenticated">
                <button @click="logout">Logout</button>
            </li>
        </ul>
    </nav>
</template>

<script>
import { mapState } from 'vuex';

export default {
    computed: {
        ...mapState(['isAuthenticated']),
    },
    mounted() {
        const token = localStorage.getItem('token');
        const user = localStorage.getItem('user');
        if (token) {
            this.$store.commit('setCurrentUser',JSON.parse(user));
            this.$store.commit('setToken', token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
            this.$router.push('/');
        }
    },
    methods: {
        logout: async function () {
            this.$store.dispatch('logout')
                .then(() => {
                    this.$router.push('/login');
                })
                .catch((error) => {
                    // Handle login error
                    this.$toast.error('Login failed');
                });
        }
    },
};
</script>

<style scoped>
nav {
    background-color: #333;
    padding: 10px;
}
ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
    display: flex;
    gap: 15px;
}
li {
    color: white;
}
button {
    background-color: transparent;
    border: none;
    color: white;
    cursor: pointer;
}
a{
    text-decoration: none;
    color: white;
}
</style>
