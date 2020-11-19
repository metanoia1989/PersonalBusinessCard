<template>
  <div class="app-container">
    <el-row :gutter="10">
      <el-col :xs="24" :sm="14" :md="12" :lg="12" :xl="12">

        <el-form ref="form" :model="form" label-width="120px">
          <el-form-item label="姓名">
            <el-input v-model="form.name" />
          </el-form-item>
          <el-form-item label="头像">
            <el-upload
              class="avatar-uploader"
              :action="upload_url"
              :show-file-list="false"
              :on-success="handleAvatarSuccess"
              :before-upload="beforeAvatarUpload">
              <img v-if="form.avatar" :src="avatar" class="avatar">
              <i v-else class="el-icon-plus avatar-uploader-icon"></i>
            </el-upload>
          </el-form-item>
          <el-form-item label="职位">
            <el-input v-model="form.position" />
          </el-form-item>
          <el-form-item label="个人标签">
            <el-tag
              :key="tag"
              v-for="tag in form.tags"
              closable
              :disable-transitions="false"
              @close="handleTagClose(tag)">
              {{tag}}
            </el-tag>
            <el-input
              class="input-new-tag"
              v-if="tagInputVisible"
              v-model="tagInputValue"
              ref="saveTagInput"
              size="small"
              @keyup.enter.native="handleTagInputConfirm"
              @blur="handleTagInputConfirm"
            >
            </el-input>
            <el-button v-else class="button-new-tag" size="small" @click="showTagInput">+ 新标签</el-button>
          </el-form-item>
          <el-form-item label="个人描述">
            <el-input type="textarea" v-model="form.description"></el-input>
          </el-form-item>
          <el-form-item label="手机号">
            <el-input v-model="form.phone" />
          </el-form-item>
          <el-form-item label="邮箱">
            <el-input v-model="form.email" />
          </el-form-item>
          <el-form-item label="网址">
            <el-input v-model="form.website" />
          </el-form-item>
          <el-form-item label="公司">
            <el-input v-model="form.company" />
          </el-form-item>
          <el-form-item label="地址">
            <el-input v-model="form.address" />
          </el-form-item>
          <el-form-item label="纬度">
            <el-input v-model="form.latitude" />
          </el-form-item>
          <el-form-item label="经度">
            <el-input v-model="form.longitude" />
          </el-form-item>
          <el-form-item label="主题颜色">
            <el-color-picker
              v-model="form.theme_color"
              :predefine="predefineColors">
            </el-color-picker>
          </el-form-item>
          <el-form-item>
            <el-button type="primary" @click="onUpdate">更新</el-button>
          </el-form-item>
        </el-form>

      </el-col>
    </el-row>
  </div>
</template>

<script>
import { upload_url } from '@/settings'
import { mapGetters } from 'vuex'
import { fetchProfile, updateProfile } from '@/api/user'

export default {
  data() {
    return {
      form: {
        name: '',
        avatar: '',
        position: '',
        tags: [],
        description: '',
        phone: '',
        email: '',
        company: '',
        website: '',
        address: '',
        theme_color: '',
        latitude: 0,
        longitude: 0,
      },
      upload_url, // 图片上传地址
      tagInputVisible: false, // tag输入框
      tagInputValue: '', // tag输入框值
      predefineColors: [
        '#37538f',
        '#ff4500',
        '#ff8c00',
        '#ffd700',
        '#90ee90',
        '#00ced1',
        '#1e90ff',
        '#c71585',
        'rgba(255, 69, 0, 0.68)',
        'rgb(255, 120, 0)',
        'hsv(51, 100, 98)',
        'hsva(120, 40, 94, 0.5)',
        'hsl(181, 100%, 37%)',
        'hsla(209, 100%, 56%, 0.73)',
        '#c7158577'
      ]
    }
  },
  computed: {
    ...mapGetters(['avatar'])
  },
  created() {
    this.getProfile()
  },
  methods: {
    onUpdate() {
      updateProfile(this.form).then(res => {
        this.$message({
          message: '更新成功!',
          type: 'success'
        })
      });
    },
    /**
     * 获取名片详细信息
     */
    getProfile() {
      const loading = this.$loading({
        lock: true,
        text: 'Loading',
        spinner: 'el-icon-loading',
        background: 'rgba(0, 0, 0, 0.7)'
      });
      fetchProfile().then(res=> {
        this.form = res.data;
        loading.close();
      }).catch(error => {
        loading.close();
      })
    },

    /**
     * 图像上传成功
     */
    handleAvatarSuccess(res, file) {
      this.imageUrl = URL.createObjectURL(file.raw);
    },

    /**
     * 图像上传之前的校验操作
     */
    beforeAvatarUpload(file) {
      const isJPG = file.type === 'image/jpeg';
      const isLt2M = file.size / 1024 / 1024 < 2;

      if (!isJPG) {
        this.$message.error('上传头像图片只能是 JPG 格式!');
      }
      if (!isLt2M) {
        this.$message.error('上传头像图片大小不能超过 2MB!');
      }
      return isJPG && isLt2M;
    },
    /**
     * 标签相关输入操作
     */
    handleTagClose(tag) {
      this.form.tags.splice(this.form.tags.indexOf(tag), 1);
    },
    showTagInput() {
      this.tagInputVisible = true;
      this.$nextTick(_ => {
        this.$refs.saveTagInput.$refs.input.focus();
      });
    },
    handleTagInputConfirm() {
      let inputValue = this.tagInputValue;
      if (inputValue) {
        this.form.tags.push(inputValue);
      }
      this.tagInputVisible = false;
      this.tagInputValue = '';
    }
  }
}
</script>

<style scoped>

  /* 头像样式 */
  .avatar-uploader .el-upload {
    border: 1px dashed #d9d9d9;
    border-radius: 6px;
    cursor: pointer;
    position: relative;
    overflow: hidden;
  }
  .avatar-uploader .el-upload:hover {
    border-color: #409EFF;
  }
  .avatar-uploader-icon {
    font-size: 28px;
    color: #8c939d;
    width: 178px;
    height: 178px;
    line-height: 178px;
    text-align: center;
  }
  .avatar {
    width: 178px;
    height: 178px;
    display: block;
  }

  /* 个人标签样式 */
  .el-tag + .el-tag {
    margin-left: 10px;
  }
  .button-new-tag {
    margin-left: 10px;
    height: 32px;
    line-height: 30px;
    padding-top: 0;
    padding-bottom: 0;
  }
  .input-new-tag {
    width: 90px;
    margin-left: 10px;
    vertical-align: bottom;
  }
</style>

