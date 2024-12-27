import { createRouter, createWebHistory } from "vue-router";
import store from "../store/index.js";
import AuthRoute from "./AuthRoute.js";
import BlogRoute from "./BlogRoute.js";

// Combine Auth and Blog routes
const routes = [
    ...AuthRoute,
    ...BlogRoute
];

// Create the router instance
const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Global navigation guard for authentication
router.beforeEach((to, from, next) => {
    if (to.meta.auth === true) {
        if (!store.getters.isAuthenticated) {
            next({ name: "auth.login" });
        } else {
            next();
        }
    } else {
        next(); // No authentication required
    }
});

export default router;
