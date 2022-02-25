<template>
  <div>
    <el-menu :router="true" class="el-menu-demo" mode="horizontal" @select="handleSelect">
      <img src="../assets/av579-n41zg.png" style="height: 60px" alt="">
      <el-menu-item index="imgUpload">上传</el-menu-item>
      <el-menu-item index="userCount">用户中心</el-menu-item>
    </el-menu>
  </div>
    <router-view></router-view>
</template>

<script>
export default {
  name: "img-cloud-nav",
  data() {
    return {

    };
  },
  methods: {
    handleSelect(key, keyPath) {
      console.log(key,keyPath)
    }
  }
}

</script>

<style scoped>

</style>