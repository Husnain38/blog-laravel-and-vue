import BlogList from "../views/BlogList.vue";
import BlogDetail from "../views/BlogDetail.vue";
import PostForm from "../components/PostForm.vue";

const BlogRoute = [
    {
        path: '/',
        component: BlogList,
        meta: { auth: true },
    },
    {
        path: '/blog/:id',
        component: BlogDetail,
        meta: { auth: true },
    },
    {
        path: '/add-post',
        name: 'add-post',
        component: PostForm,
        meta: { auth: true },
    },
    {
        path: '/edit-post/:postId',
        name: 'edit-post',
        component: PostForm,
        props: true,
        meta: { auth: true },
    },
];

export default BlogRoute;
