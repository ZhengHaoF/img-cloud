<template>
  <div>
    <el-menu :router="true" class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <img src="../assets/av579-n41zg.png" style="height: 60px" alt="">
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
    }
  },mounted(){

  }
}

</script>

<style scoped>

</style>