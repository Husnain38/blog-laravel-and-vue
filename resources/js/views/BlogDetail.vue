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
                            <p>{{ comment.content }}</p>
                        </div>
                        <div class="reply-form">
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
            isAuthenticated: this.$store.isAuthenticated, // Track if the user is logged in
            newComment : ""
        };
    },
    async mounted() {
        this.$store.commit('SET_LOADING', true);
        const response = await axios.get(`/api/blogs/${this.$route.params.id}`);
        this.$store.commit('SET_LOADING', false);
        this.blogPost = response.data.data;

        this.isAuthenticated = this.$store.isAuthenticated !== null;
    },
    methods: {

        // Submit a reply for a specific comment
        async submitReply(commentId) {
            const comment = this.blogPost.comments.find(c => c.id === commentId);

            if (!comment.reply || comment.reply.trim() === '') {
                this.$toast.error('Please write a reply!');
                return;
            }

            try {
                // Send the reply to the server
                const response = await axios.post(`/api/comments/${commentId}/reply`, {
                    content: comment.reply,
                }, {
                    headers: {
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });

                // Add the reply to the comment's replies array
                comment.replies.push(response.data.data);
                comment.reply = ''; // Clear the reply input
                comment.showReplyForm = false; // Hide the reply form
            } catch (error) {
                this.$toast.error('Error submitting the reply.');
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


