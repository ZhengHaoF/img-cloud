<template>
  <el-main>
    <h1 style="text-align: center">
      imgCloud安装向导
    </h1>

    <el-row justify="center">
      <el-col :xs="24" :sm="24" :md="24" :lg="12" :xl="6">
        <el-row justify="center" :gutter="40">
          <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="6">
            <el-card shadow="never" header="环境检测" style="padding-bottom: 20px">
              <el-skeleton v-show="check === {}" />
            <div style="padding-bottom: 10px" v-for="item in check['data']" v-bind:key="item">
              <el-row>
                <el-col :span="20">
                  {{item['name']}}
                </el-col>
                <el-col :span="4" v-bind:class="{'check_green':item['status']==='true','check_red':!(item['status']==='true')}">
                  {{item['status']==="true"?"通过":"未通过"}}
                </el-col>
              </el-row>
            </div>

            </el-card>
          </el-col>
          <el-col :xs="24" :sm="12" :md="12" :lg="12" :xl="6">
            <el-card shadow="never" header="安装向导">
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

                <el-form-item  size="large" type="flex">
                  <el-button type="primary" @click="imgInstall">
                    提交
                  </el-button>
                </el-form-item>
              </el-form>
            </el-card>
          </el-col>
        </el-row>
      </el-col>
    </el-row>

  </el-main>
</template>

<script>
import axios from "axios";
import {ElMessage, ElMessageBox} from "element-plus";


export default {
  name: "img-install",
  data(){
    return{
      // eslint-disable-next-line no-undef
      serve_state:serve_state,
      from:{
        admin_name:"",
        admin_email:"",
        admin_pwd:"",
        admin_pwd2:"",
      },
      check:{},
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
    imgInstallCheck:function (){
      axios.get(this.serve_state['dataServer'] + "index.php/imgInstallCheck").then((res)=>{
        this.check = res.data;
        console.log(this.check['data'])
      })
    },
    imgInstall:function (){
      console.log(this.serve_state)
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
    }

  },
  mounted() {
    this.imgInstallCheck()
  }
}
</script>

<style scoped>
.check_red{
  color: #f23d3d;
}
.check_green{
  color: #62a169;
}
</style>