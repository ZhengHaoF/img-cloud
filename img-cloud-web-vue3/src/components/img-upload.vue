<template>
  <div>
    <el-main>
      <el-row type="flex" class="row-bg" justify="center">
        <el-col :xs="24" :sm="12" :md="12" :lg="6" :xl="6">
          <el-space style="padding: 10px">
            <el-upload
                drag
                :action="storageServer + uploadUrl"
                multiple
                :data="data"
                name="image"
                :on-preview="preview"
                :on-success="uploadSuccess"
                :on-error="uploadError"
                :file-list="imgList"
                :limit="20"
                :on-exceed="beyondUploadMax"
                accept=".jpg,.jpeg,.png,.gif,.bmp,.pdf,.JPG,.JPEG,PNG,.GIF,.BMP"
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
          </el-space>
        </el-col>
      </el-row>
    </el-main>
  </div>
</template>

<script>

import {UploadFilled} from "@element-plus/icons-vue";
import {ElMessage, ElNotification} from "element-plus";

export default {
  name: "img-upload",
  data() {
    return {
      // eslint-disable-next-line no-undef
      storageServer:serve_state['storageServer'],//当前存储服务器
      // eslint-disable-next-line no-undef
      uploadUrl:serve_state['uploadUrl'],//当前上传接口
      imgList:[],
      data:{
        "uid":localStorage.getItem("uid"),
        "uuid":localStorage.getItem("uuid"),
        "token":localStorage.getItem("token")
      }
    };
  },
  methods:{
    uploadSuccess:function (response,file){
      console.log(response)
       ElNotification({
        title: '成功',
        message: file.name + " 上传完成",
        type: 'success',
      })
    },
    uploadError:function (err){
      console.log(err)
      ElNotification({
        title: '失败',
        message: err,
        type: 'error',
      })
    },
    beyondUploadMax:function (){
      ElNotification({
        title: '失败',
        message: "单次上传不超过20张图片",
        type: 'error',
      })
    },
    preview:function (file){
      console.log(file.response)
      navigator.clipboard.writeText(this.storageServer + "storage/" + file.response["imgUrl"]).then(()=>{
        ElMessage({
          message: "成功复制到剪贴板",
          type: 'success',
        })
      });
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
  *{
    margin: 0;
    padding: 0;
  }
</style>