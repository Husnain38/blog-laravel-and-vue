<template>
    <div class="row my-4">
        <div class="col-6 offset-3">
            <div class="card">
                <div class="card-header d-flex justify-content-between">

                    <router-link class="btn btn-primary" to="/">Back</router-link>
                </div>
                <div class="card-body">
                    <div class="blog-detail" v-if="!loading">
                        <h1>{{ blogPost.title }}</h1>
                        <img :src="blogPost.image" class="w-50" alt="Blog image" />
                        <p>{{ blogPost.description }}</p>
                        <h4>Comments ({{ blogPost.comments?.length }})</h4>

                        <!-- Display Comments -->
                        <div v-for="comment in blogPost.comments" :key="comment.id" class="comment">
                            <h6>{{comment.user.name}}</h6>
                            <p>{{ comment.content }}</p>
                            <small>{{comment.created_at}}</small>
                        </div>
                        <div class="reply-form" v-if="isAuthenticated">
                            <textarea v-model="newComment" placeholder="Write your reply..." rows="3" class="form-control"></textarea>
                            <button @click="submitReply(blogPost.id)" class="btn btn-primary mt-2 float-end">Save</button>
                        </div>
                    </div>
                    <div v-else class="blog-detail">
                        Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
import axios from 'axios';

export default {
    name: 'BlogDetail',
    data() {
        return {
            blogPost: {},
            isAuthenticated: this.$store.getters.isAuthenticated, // Track if the user is logged in
            newComment : ""
        };
    },
    async mounted() {

        await this.getBlog();
        this.isAuthenticated = this.$store.getters.isAuthenticated;
    },
    methods: {

        async getBlog() {
            this.$store.commit('SET_LOADING', true);
            const response = await axios.get(`/api/blogs/${this.$route.params.id}`);
            this.$store.commit('SET_LOADING', false);
            this.blogPost = response.data.data;
        },

        async submitReply(postId) {
            if (this.newComment.trim() === '') {
                this.$toast.error('Please write a reply!');
                return;
            }
            try {
                this.$store.commit('SET_LOADING', true);
                // Send the reply to the server
                const response = await axios.post(`/api/blogs/${postId}/comment`, {
                    comment: this.newComment,
                }, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });
                await this.getBlog();
                this.newComment = "";
            } catch (error) {
                this.$toast.error('Error submitting the reply.');
            }finally {
                this.$store.commit('SET_LOADING', false);
            }
        },
    },
};
</script>

<style scoped>
.blog-post img {
    max-width: 100%;
    height: auto;
}

.comment {
    margin-bottom: 20px;
}

.reply-form {
    margin-top: 10px;
}

.replies {
    margin-top: 15px;
    padding-left: 20px;
    border-left: 2px solid #ddd;
}

.reply {
    margin-top: 5px;
}

button {
    cursor: pointer;
}
a{
    text-decoration: none;
    color: white;
}
</style>


