<template>

    <img-cloud-nav></img-cloud-nav>
    <el-footer style="text-align: center;color: #666666">
      {{copy_right}}
    </el-footer>
</template>

<script>

import imgCloudNav from './components/img-cloud-nav.vue'
import {ElMessage} from "element-plus";
import "./assets/public.css"
export default {
  name: 'App',
  data(){
    return{
      // eslint-disable-next-line no-undef
      serve_state:serve_state,
      copy_right:this.$store.state.web_info['copy_right']
    }
  },
  components: {
    imgCloudNav,
  },
  methods:{
    userCheck:function (){
      if (localStorage.getItem("uuid") === null) return; //如果是空，就取消执行
      this.axios.post(this.serve_state['dataServer'] + "index.php/userCheck",{
        "uuid":localStorage.getItem("uuid"),
        "token":localStorage.getItem("token"),
      }).then(res=>{
        //验证通过
        ElMessage({
          message:res.data["msg"],
          type: 'success',
        })
        this.$store.state.user['uid'] =  localStorage.getItem("uuid");
        this.$store.state.user['userGroup'] = res.data['userGroup']
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
#app{
  background-color: white;
}
</style>
