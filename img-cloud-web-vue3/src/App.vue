<template>
  <div id="app">
    <img-cloud-nav></img-cloud-nav>
    <el-row>
      <el-col style="text-align: center;color: #656266">
        Copyright © 2022. ZHF
      </el-col>
    </el-row>
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
        //验证通过
        ElMessage({
          message:res.data["msg"],
          type: 'success',
        })
        this.$store.state.user['uid'] =  localStorage.getItem("uuid");
      }).catch(function (error){
        ElMessage({
          message:error,
          type: 'error',
        })
        localStorage.clear();//清除缓存，防止一直弹
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
