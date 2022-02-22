<template>
  <div>
    <el-main>
      <el-row type="flex" class="row-bg" justify="center">
        <el-col :xs="24" :sm="12" :md="12" :lg="6" :xl="6">
          <el-upload
              class="upload-demo"
              drag
              :action="storageServer + uploadUrl"
              multiple
              :data="data"
              :on-preview="preview"
              :on-success="uploadSuccess"
              :on-error="uploadError"
          >
            <el-icon class="el-icon--upload"><upload-filled /></el-icon>
            <div class="el-upload__text">
              目前状态：{{ getUserState }}<br>
              拖拽文件到此处或<em>点击此处上传</em>
            </div>
            <template #tip>
              <div class="el-upload__tip">
                仅能上传 jpg/png/heif/bmp 格式的文件，上传完成后点击查看直链
              </div>
            </template>
          </el-upload>
        </el-col>
      </el-row>
    </el-main>
  </div>
</template>

<script>

import {UploadFilled} from "@element-plus/icons-vue";
import {ElNotification} from "element-plus";

export default {
  name: "img-upload",
  data() {
    return {
      dataServer:this.$store.state.serve_state['dataServer'],//当前数据服务器
      storageServer:this.$store.state.serve_state['storageServer'],//当前存储服务器
      uploadUrl:this.$store.state.serve_state['uploadUrl'],//当前上传接口
      fileList:[],
      data:{
        "uid":localStorage.getItem("uid"),
        "uuid":localStorage.getItem("uuid"),
        "token":localStorage.getItem("token")
      }
    };
  },
  methods:{
    uploadSuccess:function (response,file){
      console.log(this.fileList)
      console.log(response)
      ElNotification({
        title: '成功',
        message: file.name + " 上传完成",
        type: 'success',
      })
    },
    uploadError:function (){

    },
    preview:function (file){
      console.log(file.response)
      alert(this.storageServer + "storage/" + file.response['imgUrl'])
    }
  },
  computed:{
    getUserState:function (){
      if (localStorage.getItem("uuid") != null){
        return "用户上传";
      }else {
        return "游客上传"
      }
    }
  },
  components: {
    UploadFilled
  },
}
</script>
<style scoped>

</style>