<template>
  <div>
    <el-menu :router="true" class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <img :src=" this.$store.state.web_info['web_logo']" style="height: 60px" alt="">
      <el-menu-item index="/imgUpload">上传</el-menu-item>
      <el-menu-item index="/userCount">用户中心</el-menu-item>
      <el-menu-item index="/admin/userManagement" v-show="this.$store.state.user.userGroup === 'admin' ">管理后台</el-menu-item>
      <el-menu-item index="" @click="loginOut" v-show="this.$store.state.user.uid !== ''">登出</el-menu-item>
      <el-menu-item index="#" @click="bt3Click">开源</el-menu-item>
    </el-menu>
  </div>

  <router-view></router-view>


</template>

<script>
import axios from "axios";

export default {
  name: "img-cloud-nav",
  data() {
    return {
      // eslint-disable-next-line no-undef
      serve_state:serve_state,
    };
  },
  methods: {
    handleSelect(key, keyPath) {
      console.log(key,keyPath)
    },
    bt3Click(){
      window.open('https://gitee.com/ZHFHZ/img-cloud');
    },
    loginOut(){
      localStorage.clear();
      location.reload();//刷新
    },
    getWebInfo:function (){
      axios.get(this.serve_state.dataServer + "index.php/getWebInfo").then((res)=>{
        this.$store.state.web_info['web_name'] = res.data['web_name'];
        this.$store.state.web_info['web_text'] = res.data['web_text'];
        this.$store.state.web_info['web_logo'] = res.data['web_logo'];
        this.$store.state.web_info['web_copy_right'] = res.data['web_copy_right'];
        document.title =  this.$store.state.web_info['web_name']
      })
    }
  },mounted(){
    this.getWebInfo()
    let zf =  "🐖️版 本 号：2022.05.02 v1.0.1-beta\n" +
        "☀️开源：https://gitee.com/ZHFHZ/img-cloud"
    console.log("%c" + zf,"font-size:18px")
    document.title =  this.$store.state.web_info['web_name']
  }
}

</script>

<style scoped>

</style>