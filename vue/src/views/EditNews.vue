<template>
    <div>
        <MainLayoutForListNews/>
        <router-view></router-view>
  
        <h4 class="title">Edit News</h4>
        <div class="form">
        <form action="" @submit.prevent="submit()">
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" aria-label="Default select example" id="category" v-model="news.value.category_id">
                    <option selected>Choose item</option>
                    <option value="1">Adventure travel</option>
                    <option value="2">Beach</option>
                    <option value="3">Explore world</option>
                    <option value="4">Family holidays</option>
                    <option value="5">Art and culture</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input class="form-control" id="title" placeholder="Enter title" v-model="news.value.title">
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input class="form-control" id="slug" placeholder="Enter slug" v-model="news.value.slug">
            </div>
            <div class="mb-3">
                <label for="img_upload" class="form-label">Image</label> <br>
                <input
                type="file"
                id="image"
                name="image_uploads"
                accept=".jpg, .jpeg, .png"
                @change="handleFileUpload"
                multiple
                />
                <div v-if="previewUrl">
                <img :src="previewUrl" alt="File Preview" style="max-width: 80px; max-height: 50px;">
                </div>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" rows="3" v-model="news.value.content"></textarea>
            </div> 
        
            <div class="checkbox">
                <div>Status:</div>
                <input type="radio" id="isPublished" v-model="news.value.status" value=1 name="status">Published
                <input type="radio" id="isUnPublished" v-model="news.value.status" value=2 name = "status">Unpublished
            </div>
            <div class="button_box">
                <button class="cancel">Cancel</button>
                <button type="button" class="draft" @click="saveDraft">Draft</button>
                <button type="submit" class="btn btn-primary" @click="save()">Save</button>
            </div>
        </form>
      </div>
    </div>
  </template>
  
<script setup>
import { ref, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import api from '../api';
import MainLayoutForListNews from '../layouts/MainLayoutForListNews.vue';

const router = useRouter();
const route = useRoute();
const previewUrl = ref(null);
const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    previewUrl.value = URL.createObjectURL(file);
    news.value.image = file;
  }
};
const saveTime = ref();

const save = () => {
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  
  saveTime.value = `${year}-${month}-${day}`;
};
const news = ref({
  id: null,
  category_id: '',
  title: '',
  slug: '',
  image: '',
  content: '',
  status: ''
});

const fetchNews = async (id) => {
  try {
    const response = await api.get(`/posts/${id}`);
    news.value = response.data;
    previewUrl.value = response.data.image;
  } catch (error) {
    console.error('Error fetching news:', error);
  }
};
onMounted(() => {
  const id = route.params.id;
  fetchNews(id);
});
const submit = async () => {
  const formData = new FormData();
  formData.append('category_id', news.value.category_id);
  formData.append('title', news.value.title);
  formData.append('slug', news.value.slug);
  formData.append('image', news.value.image);
  formData.append('content', news.value.content);
  formData.append('status', news.value.status);

  try {
    await api.put('/posts/${news.value.id}', formData);
    router.push('/listnews');
  } catch (error) {
    console.error('Error saving post:', error);
  }
};

const saveDraft = async () => {
  const draftData = {
    category_id: news.value.category_id,
    title: news.value.title,
    slug: news.value.slug,
    image: previewUrl.value,
    content: news.value.content,
    status: 0,
  };

  try {
    await api.post('/posts/draft', draftData);
    router.push('/listnews');
  } catch (error) {
    console.error('Error saving draft:', error);
  }
};
  </script>
  
  <style scoped>
  .luna {
      position: absolute;
      top: 300px;
      left: 300px;
  }
  .title {
      position: absolute;
      left: 315px;
      top: 150px;
      font-weight: 700;
  }
  .form {
      position: absolute;
      width: 800px;
      left: 345px;
      top: 250px;
  }
  .checkbox {
      width: 350px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 20px 0 20px 0;
  }
  .button_box {
      display: flex;
      /* width: 800px; */
      align-items: center;
      justify-content: space-around;
      padding: 20px;
  }
  </style>