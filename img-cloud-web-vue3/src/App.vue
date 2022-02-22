<template>
  <div id="app">
    <img-cloud-nav></img-cloud-nav>
  </div>
</template>

<script>

import imgCloudNav from './components/img-cloud-nav.vue'
import {ElMessage} from "element-plus";
export default {
  name: 'App',
  components: {
    imgCloudNav,
  },
  methods:{
    userCheck:function (){
      if (localStorage.getItem("uuid") === null) return; //如果是空，就取消执行
      this.axios.post(this.$store.state.serve_state['dataServer'] + "index.php/userCheck",{
        "uuid":localStorage.getItem("uuid"),
        "token":localStorage.getItem("token"),
      }).then(res=>{
          if (res.data['status'] === 200){
            ElMessage({
              message:"用户已登录",
              type: 'success',
            })
            this.$store.state.user['uid'] =  localStorage.getItem("uuid");
          }else {
            //用户验证错误
            ElMessage({
              message:res.data['msg'],
              type: 'warning',
            })
            localStorage.clear();//清楚缓存，防止一直弹
          }
      }).catch(function (error){
        ElMessage({
          message:error,
          type: 'error',
        })
      })
    }
  },
  mounted(){
    this.userCheck();
  }
}
</script>

<style>
#app {

}
</style>
