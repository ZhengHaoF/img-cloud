import { createStore } from 'vuex'

export default createStore({
    state: {
        user:{
            uid:"",
            username:"",
        },
        serve_state:{
            dataServer:"https://api.tu.zhfblog.top/",//当前数据服务器
            //dataServer:"http://localhost:8006/",//当前数据服务器
            storageServer:"https://api.tu.zhfblog.top/",//当前存储服务器
            //storageServer:"http://localhost:8006/",//当前存储服务器
            uploadUrl: "index.php/upload",//当前上传接口
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
