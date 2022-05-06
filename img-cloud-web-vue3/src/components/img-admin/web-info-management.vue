<template>
  <el-card>
    <template #header>
      站点信息
    </template>
    <el-form>
      <el-form-item label="站点名称">
        <el-input v-model="web_info['web_name']"></el-input>
      </el-form-item>
      <el-form-item label="站点说明">
        <el-input v-model="web_info['web_text']"></el-input>
      </el-form-item>
      <el-form-item label="站点Logo">
        <el-input v-model="web_info['web_logo']"></el-input>
      </el-form-item>
      <el-form-item label="版权信息">
        <el-input v-model="web_info['web_copy_right']"></el-input>
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="setWebInfo">更新</el-button>
      </el-form-item>
    </el-form>
  </el-card>

  <el-card style="margin-top: 20px">
    <template #header>
      存储位置
    </template>
    <el-form>
      <div v-for="item in storage_info" :key="item['storage_name']">
        <el-form-item label="存储名称">
          <el-input v-model="item['storage_name']"></el-input>
        </el-form-item>
        <el-form-item label="存储说明">
          <el-input v-model="item['storage_text']"></el-input>
        </el-form-item>
        <el-form-item>
          <el-button type="primary">启用</el-button>
        </el-form-item>
      </div>
    </el-form>
  </el-card>
</template>

<script>
import axios from "axios";
import {ElMessage} from "element-plus";

export default {
  name: "web-info-management",
  data(){
    return{
      // eslint-disable-next-line no-undef
      serve_state:serve_state,
      web_info:{
        "web_name": this.$store.state.web_info['web_name'],
        "web_text":this.$store.state.web_info['web_text'],
        "web_logo":this.$store.state.web_info['web_logo'],
        "web_copy_right":this.$store.state.web_info['web_copy_right'],
      },
      "storage_info":[
        {
          "storage_name":"local",
          "storage_text":"存储在本地的图床"
        }
      ]
    }
  },
  methods:{
    setWebInfo:function (){
      axios.post(this.serve_state.dataServer + "index.php/setWebInfo",{
        "web_name":this.web_info.web_name,
        "web_text":this.web_info.web_text,
        "web_logo":this.web_info.web_logo,
        "web_copy_right":this.web_info.web_copy_right,
        "uuid":localStorage.getItem("uuid"),
        "token":localStorage.getItem("token")
      }).then((res)=>{
        ElMessage({
          message: res.data["msg"],
          type: "success",
        })
        location.reload();
      })
    }
  }
}
</script>

<style scoped>

</style>