import { createStore } from 'vuex';
import axios from "axios";


export default createStore({
    state: {
        loading:false,
        blogPosts: [],
        currentUser: null,
        isAuthenticated: false,
        token: null,
        hasMoreBlogs: true
    },
    mutations: {
        SET_LOADING(state, isLoading) {
            state.loading = isLoading;
        },
        setBlogPosts(state, posts) {
            state.blogPosts = posts;
        },
        setCurrentUser(state, user) {
            state.currentUser = user;
        },
        setToken(state, token) {
            state.token = token;
            state.isAuthenticated = !!(token);
        },
        setMoreBlogs(state, hasMoreBlogs) {
            state.hasMoreBlogs = hasMoreBlogs;

        },
    },
    actions: {
        setLoading({ commit }, isLoading) {
            commit('SET_LOADING', isLoading);
        },
        async login({ commit }, { email, password }) {
            try {
                commit('SET_LOADING', true);
                // Call your API to log in
                const response = await axios.post('/api/login', { email, password });

                const { user, token } = response.data;

                commit('setCurrentUser', user);
                commit('setToken', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                localStorage.setItem('token', token);
                localStorage.setItem('user', JSON.stringify(user));

                return user;
            } catch (error) {
                throw new Error('Login failed. Please check your credentials.');
            }finally {
                commit('SET_LOADING', false);
            }
        },
        async register({ commit }, userData) {
            try {
                commit('SET_LOADING', true);
                const response = await axios.post('/api/register', userData);
                const { user, token } = response.data;
                commit('setCurrentUser', user);
                localStorage.setItem('token', token);
                localStorage.setItem('user', user);
                commit('setToken', token);
                axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            } catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }
        },
        async logout({ commit }) {
            try{
                commit('SET_LOADING', true);
                const response = await axios.post('/api/logout', {});
                commit('setCurrentUser', null);
                commit('setToken', null);
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                return response;
            }catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }

        },
        async fetchBlogPosts({ commit },{page}) {
            try{
                const response = await axios.get('/api/blogs?page='+page);
                if (page > 1){
                    commit('setBlogPosts', [...this.state.blogPosts,...response.data.data]);
                }else{
                    commit('setMoreBlogs', response.data?.data?.length > 0);
                    commit('setBlogPosts', response.data.data);
                }
            }catch(error){

            }finally {
                commit('SET_LOADING', false);
            }


        },
        async fetchBlogPost({ commit },id) {
            try{
                commit('SET_LOADING', true);
                const response = await axios.get(`/api/blogs/${id}`);
                return response.data;
            }catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }

        },
        // Create a new blog post
        async createBlogPost({ commit }, postData) {
            try {
                commit('SET_LOADING', true);
                await axios.post('/api/blogs', postData);
            } catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }
        },

        // Update an existing blog post
        async updateBlogPost({ commit }, postData) {
            try {
                commit('SET_LOADING', true);
                await axios.post(`/api/blogs/update/${postData.get("id")}`, postData);
            } catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }
        },
        async deleteBlogPost({ commit },postId) {
            try {
                commit('SET_LOADING', true);
                const response = await axios.delete('/api/blogs/'+postId);
                return response.data;
            } catch (error) {
                throw error;
            }finally {
                commit('SET_LOADING', false);
            }
        },
    },
    getters: {
        getBlogPosts: (state) => state.blogPosts,
        getCurrentUser: (state) => state.currentUser,
        isAuthenticated: (state) => state.isAuthenticated,
        isLoading: (state) => state.loading,
    },
});
