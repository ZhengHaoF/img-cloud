<template>
  <el-main>
    <el-row>
      <el-col>
        <div v-for="(url,index) in imgInfos" :key="url['basename']" style="float:left;">
          <el-popover
            placement="top-start"
            :width="250"
            trigger="hover"
        >
          <template #reference>
            <el-image
                style="width: 150px;height: 150px;margin: 8px"
                fit="cover"
                :src="this.$store.state.serve_state['storageServer'] + url['thumb'] + '/'  +  url['basename']"
            >
            </el-image>
          </template>
          <el-button type="primary" @click="showImage(index)">查看</el-button>
          <el-button type="success" @click="copyImageUrl(index)">禁用图片</el-button>
          <el-button type="danger" @click="delAdminImage(index)">删除</el-button>
        </el-popover>
        </div>
      </el-col>
      <el-image-viewer
          v-if = img_viewer_show
          :url-list = preview_src_list
          @close="img_viewer_show=false"
          :hide-on-click-modal = true
      />
    </el-row>
  </el-main>
  <el-footer>
    <el-pagination
        layout="prev, pager, next,jumper"
        :total="total"
        :page-size="page_sizes"
        :small="true"
        :current-page="current_page"
        @current-change="currentChange"
    ></el-pagination>
  </el-footer>
</template>

<script>
import {ElMessage} from "element-plus";

export default {
  name: "img-management",
  data(){
    return{
      imgInfos: [],
      urls: [],
      initial_index: 0,
      total: 0,
      page_sizes: 0,
      current_page: 0,
      preview_src_list: [], //当前预览图片列表
      img_viewer_show:false //是否显示预览图片
    }
  },
  methods:{
    currentChange: function (e) {
      //切换页面
      this.getAdminImgs(e);
      this.current_page = e;
      console.log(e)
    },
    showImage:function (index){
      //图片查看
      this.preview_src_list = [];
      this.preview_src_list.push(this.urls[index]);
      this.img_viewer_show = true;
    },
    getAdminImgs: function (page) {
      //获取用户图片
      this.axios.post(this.$store.state.serve_state['dataServer'] + "index.php/getAdminImgList", {
        "uuid": localStorage.getItem("uuid"),
        "token": localStorage.getItem("token"),
        "page": page
      }).then(res => {
        this.total = res.data['total']; //条目总数
        this.page_sizes = res.data['per_page']; //每页显示条目
        this.current_page = res.data['current_page']; //当前页
        this.imgInfos = res.data['data']; //数据
        this.urls = [];
        for (const re of this.imgInfos) {
          this.urls.push(this.$store.state.serve_state['storageServer'] + "storage/" + re["dirname"] + "/" + re["basename"])
        }
      }).catch(function (error) {
        //请求错误
        if (error.response) {
          console.log(error.response)
          // 请求成功发出且服务器也响应了状态码，但状态代码超出了 2xx 的范围
          ElMessage({
            message: error.response.data['msg'],
            type: 'error',
          })
        }
      })
    },
    delAdminImage:function (initial_index){
      //删除图片
      this.axios.post(this.$store.state.serve_state['dataServer'] + "index.php/delAdminImage", {
        "uuid": localStorage.getItem("uuid"),
        "token": localStorage.getItem("token"),
        "filename":this.imgInfos[initial_index]["filename"]
      }).then(res=>{
        console.log(res)
        ElMessage({
          message: res.data['msg'],
          type: 'success',
        })
        this.imgInfos.splice(initial_index,1)
      }).catch(function (error){
        ElMessage({
          message: error.response.data['msg'],
          type: 'error',
        })
      })
    }
  },
  mounted() {
    if (localStorage.getItem("uuid") != null) {
      this.getAdminImgs()
    }
  }
}
</script>

<style scoped>

</style>