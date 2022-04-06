<template>
  <el-main>
    <h1 style="text-align: center">
      imgCloud安装向导
    </h1>
      <el-row justify="center">
        <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="6">
          <el-form :model="from" size="large"  label-position="top" :rules="rules">

            <el-form-item label="管理员用户名：" size="large" prop="admin_name">
              <el-input v-model="from.admin_name" />
            </el-form-item>

            <el-form-item label="管理员邮箱：" size="large" prop="admin_email">
              <el-input v-model="from.admin_email" />
            </el-form-item>

            <el-form-item label="管理员密码："  size="large" prop="admin_pwd">
              <el-input  v-model="from.admin_pwd" />
            </el-form-item>

            <el-form-item label="确认密码："  size="large" prop="admin_pwd2">
              <el-input  v-model="from.admin_pwd2" />
            </el-form-item>

            <el-form-item  size="large">
              <el-button type="primary" @click="imgInstall">
                提交
              </el-button>
            </el-form-item>
          </el-form>
        </el-col>
      </el-row>

  </el-main>
</template>

<script>
import axios from "axios";
import {ElMessage, ElMessageBox} from "element-plus";
import "../../../public/config.js"

export default {
  name: "img-install",
  data(){
    return{
      from:{
        // eslint-disable-next-line no-undef
        serve_state:serve_state,
        admin_name:"",
        admin_email:"",
        admin_pwd:"",
        admin_pwd2:"",
      },
      rules: {
        admin_name: [
          { required: true, message: "请输入管理员用户名", trigger: "blur" },
        ],
        admin_email: [
          { required: true, message: "请输入管理员邮箱", trigger: "blur" },
        ],
        admin_pwd: [
          { required: true, message: "请输入管理员密码", trigger: "blur" },
        ],
        admin_pwd2: [
          { required: true, message: "请输入管理员密码", trigger: "blur" },
        ],
      }
    }
  },
  methods:{
    imgInstall:function (){
      if (this.from.admin_name === "" || this.from.admin_pwd === "" || this.from.admin_pwd2 === "" || this.from.admin_email === ""){
        ElMessage({
          message: '请填写所有字段',
          type: 'warning',
        })
        return 0;
      }

      if (this.from.admin_pwd !== this.from.admin_pwd2){
        ElMessage({
          message: '两次输入的密码不相同',
          type: 'warning',
        })
        return 0;
      }

      axios.post(this.serve_state['dataServer'] + "index.php/imgInstall",{
        "admin_name":this.from.admin_name,
        "admin_pwd":this.from.admin_pwd,
        "admin_email":this.from.admin_email,
      }).then((res)=>{
        ElMessageBox.alert(res.data['msg'], '安装成功', {
          confirmButtonText: 'OK',
        })

        this.$router.push({path:'/userCount'})
      }).catch(function (error) {
        //请求错误
        if (error.response) {
          console.log(error.response)
          // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围

          ElMessageBox.alert(error.response.data['msg'], '错误', {
            confirmButtonText: 'OK',
          })
        }
      })
    },

  }
}
</script>

<style scoped>

</style>