import { createRouter,createWebHashHistory} from "vue-router";
const userCount = () => import("../components/user-console.vue")
const imgUpload = () => import("../components/img-upload.vue")

const routes = [
    {
        path: "/",
        redirect: "/imgUpload",
    },
    {
        path: "/userCount",
        name: "userCount",
        component: userCount
    },
    {
        path: "/imgUpload",
        name: "imgUpload",
        component: imgUpload
    }
]

export const router = createRouter({
    history: createWebHashHistory(),
    routes: routes
})