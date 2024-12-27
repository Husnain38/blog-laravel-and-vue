<template>
    <div class="post-form my-5">
        <h2>{{ isEditMode ? 'Edit Post' : 'Add New Post' }}</h2>
        <form >
            <div class="form-group">
                <label for="title">Title</label>
                <input
                    type="text"
                    id="title"
                    v-model="post.title"
                    class="form-control"
                    placeholder="Enter post title"
                    required
                />
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt</label>
                <input
                    type="text"
                    id="excerpt"
                    v-model="post.excerpt"
                    class="form-control"
                    placeholder="Enter excerpt"
                    required
                />
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea
                    id="description"
                    v-model="post.description"
                    class="form-control"
                    placeholder="Enter post description"
                    required
                ></textarea>
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <input type="file" class="form-control" @change="handleImageUpload" />
            </div>

            <div class="form-group">
                <label for="keywords">Keywords</label>
                <div class="keywords-input">
                    <!-- Display Tags -->
                    <div class="tags">
                <span
                    v-for="(keyword, index) in post.keywords"
                    :key="index"
                    class="tag"
                >
                    {{ keyword }}
                    <button @click="removeKeyword(index)" class="remove-btn">x</button>
                </span>
                    </div>

                    <!-- Input for Adding New Tag -->
                    <input
                        type="text"
                        id="keywords"
                        v-model="inputKeyword"
                        class="form-control"
                        placeholder="Enter a keyword"
                        @keydown.enter="addKeyword"
                        @blur="addKeyword"
                    />
                </div>
                <small class="form-text text-muted">
                    Press Tab to add a keyword.
                </small>
            </div>
            <div class="form-group">
                <label for="meta_title">Meta Title</label>
                <input
                    type="text"
                    id="meta_title"
                    v-model="post.meta_title"
                    class="form-control"
                    placeholder="Enter meta title"
                />
            </div>

            <div class="form-group">
                <label for="meta_description">Meta Description</label>
                <input
                    type="text"
                    id="meta_description"
                    v-model="post.meta_description"
                    class="form-control"
                    placeholder="Enter meta description"
                />
            </div>

            <div class="form-group">
                <label for="published_at">Published At</label>
                <input
                    type="datetime-local"
                    id="published_at"
                    v-model="post.published_at"
                    class="form-control"
                    required
                />
            </div>
            <div class="d-flex float-end mb-5">
                <button type="button" class="btn btn-primary        mx-2" @click="submitForm">
                    {{ isEditMode ? 'Update' : 'Save' }}
                </button>
                <button type="button" class="btn btn-secondary mx-2" @click="blogs">
                    Cancel
                </button>
            </div>

        </form>
    </div>
</template>

<script>
import { mapState } from 'vuex';

export default {
    name: 'PostForm',
    props: {
        postId: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            inputKeyword: '',
            post: {
                id: '',
                title: '',
                excerpt: '',
                description: '',
                image: '',
                keywords: [],
                meta_title: '',
                meta_description: '',
                published_at: '',
            },
        };
    },
    computed: {
        ...mapState(['user']),
        isEditMode() {
            return this.postId !== null;
        },
    },
    async mounted() {
        if (this.isEditMode) {
            await this.loadPost(this.postId);
        }
    },
    methods: {

        blogs(){
            this.$router.push("/");
        },
        handleImageUpload(event) {
            this.image = event.target.files[0];
        },
        addKeyword() {
            const keyword = this.inputKeyword.trim();
            if (keyword && !this.post.keywords.includes(keyword)) {
                this.post.keywords.push(keyword);
            }
            this.inputKeyword = '';
        },

        removeKeyword(index) {
            this.post.keywords.splice(index, 1);
        },
        async loadPost(postId) {
            try {
                const response = await this.$store.dispatch('fetchBlogPost', postId);
                this.post = response.data; // Assuming the response contains the post data
                this.post.keywords = response.data.keywords.split(",");
            } catch (error) {
            }
        },

        async submitForm() {
            const formData = new FormData();
            formData.append('title', this.post.title);
            formData.append('excerpt', this.post.excerpt);
            formData.append('description', this.post.description);
            formData.append('keywords', this.post.keywords);
            formData.append('meta_title', this.post.meta_title);
            formData.append('meta_description', this.post.meta_description);
            formData.append('published_at', this.post.published_at);
            if (this.image) {
                formData.append('image', this.image);
            }
            if (this.isEditMode) {
                formData.append('id', this.post.id);
                await this.updatePost(formData);
            } else {
                await this.addPost(formData);
            }
        },

        async addPost(formData) {
            try {
                await this.$store.dispatch('createBlogPost', formData);
                this.$router.push('/');
                this.$toast.success('Post added successfully!');
            } catch (error) {
                this.$toast.error('Failed to add post.');
            }
        },

        async updatePost(formData) {
            try {
                await this.$store.dispatch('updateBlogPost', formData);
                this.$toast.success('Post updated successfully!');
                this.$router.push('/');
            } catch (error) {
                this.$toast.error('Failed to update post. Please try again.');
            }
        },
    },
};
</script>

<style scoped>
/* Styling for the form */
.post-form {
    max-width: 600px;
    margin: 20px auto;
}

.form-group {
    margin-bottom: 15px;
}

.form-control {
    width: 100%;
    padding: 10px;
    margin-top: 5px;
}

button {
    margin-top: 20px;
}
.current-keywords ul {
    list-style-type: none;
    padding: 0;
}

.current-keywords li {
    display: inline-block;
    background-color: #f0f0f0;
    margin: 5px;
    padding: 5px 10px;
    border-radius: 5px;
}

.current-keywords button {
    margin-left: 5px;
    padding: 3px 8px;
}
</style>
