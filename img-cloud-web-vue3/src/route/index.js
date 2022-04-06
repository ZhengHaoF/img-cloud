import { createRouter,createWebHashHistory} from "vue-router";
// const userCount = () => import("../components/user-console.vue")
// const imgUpload = () => import("../components/img-upload.vue")
import userCount from "../components/user-console.vue"
import imgUpload from "../components/img-upload.vue"
import admin from "../components/img-admin/img-admin.vue"
import userManagement from  "../components/img-admin/user-management.vue"
import imgManagement from  "../components/img-admin/img-management.vue"
import imgInstall from "../components/img-install/img-install.vue"
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
    },
    {
        path: "/install",
        name: "imgInstall",
        component: imgInstall
    },
    {
        path: "/admin",
        name: "admin",
        component: admin,
        children:[
            {
                path:"/admin/userManagement",
                component:userManagement
            },
            {
                path:"/admin/imgManagement",
                component:imgManagement
            }
        ]
    }
]

export const router = createRouter({
    history: createWebHashHistory(),
    routes: routes
})