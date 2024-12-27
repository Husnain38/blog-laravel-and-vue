import Login from "../views/Login.vue";
import Register from "../views/Register.vue";

const AuthRoute = [
    {
        name: "auth.login",
        path: '/login',
        component: Login,
        meta: { auth: false }
    },
    {
        name: "auth.register",
        path: '/register',
        component: Register,
        meta: { auth: false },
    },
];

export default AuthRoute;
