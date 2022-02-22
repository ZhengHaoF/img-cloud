<template>
  <div>
    <el-main>
        <el-row justify="center"  v-if="(this.$store.state.user['uid'] === '')">
<!--          用户登录-->
          <el-col :xs="24" :sm="16" :md="16" :lg="10" :xl="10">
            <el-card class="box-card">
              <template #header>
                <div class="card-header">
                  <h2>
                    用户登录
                  </h2>
                </div>
              </template>
                <el-row>
                  <el-col :span="24" class="login-input">
                    <el-input placeholder="用户名" v-model="username" />
                  </el-col>
                  <el-col :span="24" class="login-input">
                    <el-input type="password" show-password placeholder="密码" v-model="pwd" />
                  </el-col>
                  <el-col :span="24">
                    <el-button type="primary" @click="userLogin">登录</el-button>
                    <el-button  @click="registeredView = true">注册</el-button>
                  </el-col>
                </el-row>
            </el-card>
          </el-col>
        </el-row>
      <el-row v-if="!(this.$store.state.user['uid'] === '')">
      <!--        用户管理界面-->
        用户管理界面
      </el-row>
      <el-drawer v-model="registeredView" title="用户注册" direction="btt" size="50%" :with-header="false">
          <el-row justify="center">
            <el-col :xs="24" :sm="16" :md="16" :lg="10" :xl="10">
              <el-row>
                <el-col>
                  <h3>
                    用户注册
                  </h3>
                </el-col>
                <el-col :span="24" class="login-input">
                  <el-input placeholder="用户名" v-model="username" />
                </el-col>
                <el-col :span="24" class="login-input">
                  <el-input type="password" show-password placeholder="密码" v-model="pwd" />
                </el-col>
                <el-col :span="24" class="login-input">
                  <el-input type="password" show-password placeholder="确认密码" v-model="determinePwd"/>
                </el-col>
                <el-col :span="24">
                  <el-button type="primary" @click="userRegistered">注册</el-button>
                </el-col>
              </el-row>
            </el-col>
          </el-row>
      </el-drawer>
    </el-main>
    </div>
</template>

<script>
import {ElMessage} from "element-plus";

export default {
  name: "user-console",
  data(){
    return{
      username:"",
      pwd:"",
      determinePwd:"",//密码二次验证
      registeredView:false, //注册组件
      uid:this.$store.state.user['uid']
    }
  },
  methods:{
    userLogin:function (){
      //用户登录
      this.axios.post(this.$store.state.serve_state['dataServer'] + "index.php/UserLogin",{
        "username":this.username,
        "pwd":this.pwd
      }).then(res => {
        if(res.data["status"] === 200){
          //登录成功
          ElMessage({
            message: res.data["msg"],
            type: 'success',
          })
          this.$store.state.user['username'] = this.username;
          this.$store.state.user['uid'] = res.data["uid"];

          localStorage.setItem("uid",res.data["uid"]);
          localStorage.setItem("uuid",res.data["uuid"]);
          localStorage.setItem("token",res.data["token"]);
          location.reload(); //刷新网页
        }else {
          ElMessage({
            message:res.data['msg'],
            type: 'warning',
          })
        }
      }).catch(function (error){
        //请求错误
        if (error.response) {
          // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围
          ElMessage({
            message:"服务器内部错误" + error,
            type: 'error',
          })
        } else if (error.request) {
          // 请求已经成功发起，但没有收到响应
          ElMessage({
            message:"服务器未响应" + error,
            type: 'error',
          })
        } else {
          // 发送请求时出了点问题
          ElMessage({
            message:"请求发送出错" + error,
            type: 'error',
          })
        }
        console.log(error);
      })

    },
    userRegistered:function (){
      if (this.determinePwd === this.pwd){
        this.axios.post(this.$store.state.serve_state['dataServer'] + "index.php/userRegistered", {
          "username":this.username,
          "pwd":this.pwd
        }).then(res=>{
          if (res.data['status'] === 200){
            //注册成功
            ElMessage({
              message: res.data['msg'],
              type: 'success',
            })
            this.registeredView = false;
          }else{
            //注册失败
            ElMessage({
              message: res.data['msg'],
              type: 'warning',
            })
          }
        }).catch(function (error){
          //请求错误
          if (error.response) {
            // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围
            ElMessage({
              message:"服务器内部错误" + error,
              type: 'error',
            })
          } else if (error.request) {
            // 请求已经成功发起，但没有收到响应
            ElMessage({
              message:"服务器未响应" + error,
              type: 'error',
            })
          } else {
            // 发送请求时出了点问题
            ElMessage({
              message:"请求发送出错" + error,
              type: 'error',
            })
          }
          console.log(error);
        })
      }else {
        ElMessage({
          message: "两次输入的密码不一致",
          type: 'error',
        })
      }
    }
  },
  mounted(){

  }
}
</script>

<style scoped>
  .login-input{
    margin-bottom: 10px;
  }
</style>