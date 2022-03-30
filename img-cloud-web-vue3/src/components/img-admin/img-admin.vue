<template>
  <el-main v-if="this.$store.state.user.uid !== ''">
    <el-row :gutter="20">
      <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
        <el-card class="box-card">
          <template #header>
            <div class="card-header">
              <span>用户信息</span>
            </div>
          </template>
          <h1>
            {{ serverInfo['userCount'] }} 位
          </h1>
          <router-link to="/admin/userManagement" style="text-decoration: none">
            <el-button>
              用户管理
            </el-button>
          </router-link>
        </el-card>
      </el-col>

      <el-col :xs="12" :sm="12" :md="12" :lg="12" :xl="12">
        <el-card class="box-card">
          <template #header>
            <div class="card-header">
              <span>存储信息</span>
            </div>
          </template>
          <h1>
            {{ Math.floor(serverInfo['dataCount'] / 1024 / 1024) }}MB /
            {{ serverInfo['imgCount'] }}张
          </h1>
          <router-link to="/admin/imgManagement" style="text-decoration: none">
            <el-button>
              存储管理
            </el-button>
          </router-link>
        </el-card>
      </el-col>


      <el-col style="padding-top: 50px">
        <router-view></router-view>
      </el-col>
    </el-row>
  </el-main>
</template>

<script>
import axios from "axios";
import {ElMessage} from "element-plus";


export default {
  name: "img-admin",
  data() {
    return {
      serverInfo: {
        // "userCount": 9,
        // "imgCount": 20,
        // "dataCount": 43501146
      }
    }
  },
  methods: {
    getServerInfo: function () {
      axios.get(this.$store.state.serve_state['dataServer'] + "index.php/getServerInfo?=" + Math.floor(Math.random()*10000)).then((res) => {
        this.serverInfo = res.data;
      }).catch(function (error) {
        //请求错误
        if (error.response) {
          // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围
          ElMessage({
            message: error.response.data["msg"],
            type: "warning",
          })
        } else if (error.request) {
          // 请求已经成功发起，但没有收到响应
          ElMessage({
            message: "服务器未响应" + error,
            type: 'error',
          })
        } else {
          // 发送请求时出了点问题
          ElMessage({
            message: "请求发送出错" + error,
            type: 'error',
          })
        }
        console.log(error);
      })
    }
  },
  mounted() {
    this.getServerInfo();
  }
}
</script>

<style scoped>

</style>