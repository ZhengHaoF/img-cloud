import { createStore } from 'vuex'

export default createStore({
    state: {
        user:{
            uid:"",
            userGroup:""
        },
        web_info:{
            "web_name":"",
            "web_text":"",
            "web_logo":"",
            "web_copy_right":"",
        }
    },
    mutations: {
        // 进行数据更新，改变数据状态

    },
    actions: {
        //执行动作，数据请求

    },
    getters: {
        // 获取到最终的数据结果

    }
})
