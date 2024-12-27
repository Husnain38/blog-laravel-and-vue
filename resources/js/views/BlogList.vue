<template>
    <div class="blog-list row">
        <!-- Blog Posts -->
        <div v-if="blogPosts.length > 0" class="col-8 col-md-6 offset-md-3">
            <div
                v-for="post in blogPosts"
                :key="post.id"
                class="blog-post card mb-4"
            >
                <!-- Blog Image -->
                <img :src="post.image" alt="Blog image" class="blog-image w-25" />

                <!-- Blog Content -->
                <div class="blog-content p-3">
                    <h3>{{ post.title }}</h3>
                    <p class="excerpt">{{ post.excerpt }}</p>
                    <p class="published">
                        <strong>Published:</strong> {{ post.published_at }}
                    </p>

                    <div v-if="isOwnPost(post)" class="more-options">
                        <button
                            @click="toggleOptions(post.id)"
                            class="btn-options"
                            :aria-expanded="isOptionsVisible(post.id) ? 'true' : 'false'"
                        >
                            ⋮
                        </button>
                        <div v-if="isOptionsVisible(post.id)" class="options-dropdown">
                            <button @click="editPost(post.id)" class="btn btn-edit">Edit</button>
                            <button @click="openDeleteModal(post.id)" class="btn btn-delete">Delete</button>
                        </div>
                    </div>

                    <!-- Read More -->
                    <router-link :to="'/blog/' + post.id" class="btn btn-read-more">Read More</router-link>
                </div>
            </div>

            <!-- Loading Indicator -->
            <div v-if="loading" class="loading">Loading...</div>

            <!-- Infinite Scroll Trigger -->
            <div v-if="!loading && hasMore" class="load-more" @click="loadMoreBlogs">
                Load More
            </div>
        </div>

        <!-- No Posts Found Message -->
        <div v-else class="col-12">
            <p>No posts found.</p>
        </div>

        <!-- Modal for confirmation -->
        <delete-modal
            ref="deleteModal"
            :message="'Are you sure you want to delete this post?'"
            :action="() => deletePost(postToDelete)"
        />
    </div>
</template>

<script>
import { mapState } from 'vuex';
import DeleteModal from "@/components/DeleteModal.vue";

export default {
    name: 'BlogList',
    components: {
        DeleteModal,
    },
    data() {
        return {
            loading: false,
            page: 1,
            hasMore: true,
            activeOptionsPostId: null,
            postToDelete: null,
        };
    },
    computed: {
        ...mapState(['blogPosts', 'user','loading']),
    },
    mounted() {

        this.$store.commit('SET_LOADING', true);
        this.loadBlogs();
        this.addScrollListener();
    },
    methods: {

        isOwnPost(post) {
            let user = localStorage.getItem('user');
            user = user ? JSON.parse(user) : null;
            return post.user_id === user?.id;
        },

        // Toggle visibility of the options dropdown
        toggleOptions(postId) {
            this.activeOptionsPostId = this.activeOptionsPostId === postId ? null : postId;
        },

        // Check if options are visible for this post
        isOptionsVisible(postId) {
            return this.activeOptionsPostId === postId;
        },

        // Fetch blog posts and update the list
        async loadBlogs() {
            this.loading = true;
            try {
                await this.$store.dispatch('fetchBlogPosts', { page: this.page });
                this.hasMore = this.$store.state.hasMoreBlogs; // Update this based on your API response
            } catch (error) {
            } finally {
                this.loading = false;
                this.page++;
            }
        },

        // Load more blogs when the user scrolls to the bottom
        async loadMoreBlogs() {
            if (this.loading || !this.hasMore) return;
            this.loading = true;
            await this.loadBlogs();
        },

        // Edit post (Navigate to the edit page)
        editPost(postId) {
            this.$router.push(`/edit-post/${postId}`);
        },

        openDeleteModal(postId) {
            this.postToDelete = postId;
            this.$refs.deleteModal.openModal();
        },

        async deletePost(postId) {
            try {
                await this.$store.dispatch("deleteBlogPost", postId);
                this.page = 1;
                this.$toast.success('Post Deleted!.')
                this.loadBlogs(); // Reload blog list after deletion
            } catch (error) {
                this.$toast.error('Post not deleted!.')
            }
        },

        // Add scroll listener for infinite scrolling
        addScrollListener() {
            window.addEventListener('scroll', this.handleScroll);
        },

        // Handle scroll event for infinite scrolling
        handleScroll() {
            if (
                window.innerHeight + window.scrollY >= document.body.offsetHeight - 200 &&
                this.hasMore
            ) {
                this.loadMoreBlogs();
            }
        },
    },

    beforeDestroy() {
        window.removeEventListener('scroll', this.handleScroll);
    },
};
</script>

<style scoped>
.card {
    position: relative;
    margin-bottom: 20px;
}

.blog-list {
    margin-top: 20px;
}

.blog-post {
    display: flex;
    flex-direction: column;
    background: #fff;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    padding: 20px;
    border-radius: 8px;
    transition: box-shadow 0.3s ease;
}

.blog-post:hover {
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
}

.blog-image {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
}

.blog-content {
    padding: 10px 0;
}

h3 {
    font-size: 1.8em;
    font-weight: bold;
    color: #333;
    margin-bottom: 10px;
}

.excerpt {
    color: #555;
    margin-bottom: 10px;
}

.published {
    font-size: 0.9em;
    color: #999;
    margin-bottom: 15px;
}

.btn {
    padding: 10px 20px;
    font-size: 0.9em;
    cursor: pointer;
    border: none;
    border-radius: 5px;
    transition: background-color 0.3s;
}

.btn-read-more {
    background-color: #007bff;
    color: white;
    text-decoration: none;
}

.btn-read-more:hover {
    background-color: #0056b3;
}

.loading {
    text-align: center;
    font-size: 1.2em;
    margin: 20px 0;
}

.load-more {
    text-align: center;
    font-size: 1.1em;
    margin: 20px 0;
    padding: 10px;
    background-color: #f8f9fa;
    border-radius: 5px;
    cursor: pointer;
}

.load-more:hover {
    background-color: #e2e6ea;
}

.more-options {
    position: absolute;
    top: 15px;
    right: 15px;
}

.btn-options {
    background: none;
    border: none;
    font-size: 24px;
    color: #007bff;
    cursor: pointer;
}

.options-dropdown {
    position: absolute;
    top: 30px;
    right: 0;
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 10px;
    border-radius: 5px;
    width: 150px;
    display: flex;
    flex-direction: column;
}

.options-dropdown button {
    padding: 8px 12px;
    font-size: 14px;
    color: #333;
    background: none;
    border: none;
    text-align: left;
    cursor: pointer;
    transition: background-color 0.3s;
}

.options-dropdown button:hover {
    background-color: #f8f9fa;
}
</style>
