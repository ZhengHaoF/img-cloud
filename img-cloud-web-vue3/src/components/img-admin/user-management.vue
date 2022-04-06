<template>
  <el-table
      :data="userData"
      style="width: 100%">
    <el-table-column prop="uid" label="uid"/>
    <el-table-column prop="username" label="用户名"/>
    <el-table-column prop="regdate" label="注册时间"/>
    <el-table-column prop="email" label="邮件"/>
    <el-table-column prop="userImgCount" label="图片数量"/>
    <el-table-column prop="userImgDataCount" label="空间大小(MB)"/>
    <el-table-column prop="userStatus" label="状态">
      <template #default="scope">
        <el-switch v-model="scope.row.userStatus"
                   @click="updateUserInfo(scope.row.uid,scope.row.userStatus)"></el-switch>
        {{ scope.row.userStatus ? '启用' : '禁用' }}
      </template>
    </el-table-column>
  </el-table>
</template>

<script>
import axios from "axios";
import {ElMessage} from "element-plus";

export default {
  name: "user-management",
  data() {
    return {
      // eslint-disable-next-line no-undef
      serve_state:serve_state,
      userData: [
        // {
        //   "uid": 1,
        //   "username": "ZHF",
        //   "email": "1715005995@qq.com",
        //   "regdate": null,
        //   "group": null,
        //   "userImgCount": 20,
        //   "userImgDataCount": 43501146
        // }
      ]
    }
  },
  methods: {
    getUserList: function () {
      //获取用户列表
      axios.post(this.serve_state['dataServer'] + "index.php/getUserList", {
        "uuid": localStorage.getItem("uuid"),
        "token": localStorage.getItem("token"),
      }).then((res) => {
        this.userData = res.data
        for (let index in this.userData) {
          this.userData[index]['userImgDataCount'] = Math.floor(this.userData[index]['userImgDataCount'] / 1024 / 1024);
          this.userData[index]['userStatus'] = (this.userData[index]['userStatus'] === "true"); //转换成布尔类型
        }
      }).catch(function (error) {
        //请求错误
        if (error.response) {
          console.log(error.response)
          // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围
          ElMessage({
            message:  error.response.data['msg'],
            type: 'error',
          })
        }
      })
    },
    updateUserInfo: function (uid, status) {
      //更新用户数据
      axios.post(this.serve_state['dataServer'] + "index.php/updateUserInfo", {
        "uuid": localStorage.getItem("uuid"),
        "token": localStorage.getItem("token"),
        "uid": uid,
        "userStatus": status.toString()
      }).then((res) => {
        ElMessage({
          message: res.data['msg'],
          type: "success",
        })
      }).catch(error=>{

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
        this.getUserList();

      })

    }
  },
  computed: {},
  mounted() {
    this.getUserList();
  },
  watch: {}

}
</script>

<style scoped>

</style>