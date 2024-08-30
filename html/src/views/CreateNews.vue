<template>
  <div>
    <MainLayoutForListNews/>
    <router-view></router-view>

    <h4 class="title">Create News</h4>
    <div class="form">
        <form action="" @submit.prevent="submit()">
            <div class="mb-3">
                <label for="category" class="form-label">Category</label>
                <select class="form-select" aria-label="Default select example" id="category" v-model="category">
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
                <input class="form-control" id="title" placeholder="Enter title" v-model="title">
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input class="form-control" id="slug" placeholder="Enter slug" v-model="slug">
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
                <textarea class="form-control" id="content" rows="3" v-model="content"></textarea>
            </div> 
        
        <div class="checkbox">
            <div>Status:</div>
            <input type="radio" id="isPublished" v-model="status" value=1 name="status">Published
            <input type="radio" id="isUnPublished" v-model="status" value=2 name = "status">Unpublished
        </div>
        <div class="button_box">
            <button class="cancel" @click.prevent="cancel">Cancel</button>
            <button type="button" class="draft" @click="saveDraft">Draft</button>
            <button type="submit" class="btn btn-primary" @click="save">Save</button>
        </div>
    </form>
    </div>
  </div>
</template>

<script setup>
import {ref} from 'vue';
import { useRouter } from 'vue-router';
import api from '../api';
import MainLayoutForListNews from '../layouts/MainLayoutForListNews.vue';

const previewUrl = ref(null);

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  if (file) {
    previewUrl.value = URL.createObjectURL(file);
    fileInput.value = file; // Store file for later use
  }
};

const save = () => {
  const now = new Date();
  const year = now.getFullYear();
  const month = String(now.getMonth() + 1).padStart(2, '0');
  const day = String(now.getDate()).padStart(2, '0');
  
  saveTime.value = `${year}-${month}-${day}`;
};

const router = useRouter();

const cancel = () => {
  router.push('/listnews');
};

const fileInput = ref(null);

const saveDraft = async () => {
  const formData = new FormData();
  // const draftData = {
  //   category_id: category.value,
  //   title: title.value,
  //   slug: slug.value,
  //   image: previewUrl.value,
  //   content: content.value,
  //   status: 0,
  // };
  formData.append('category_id', category.value);
  formData.append('title', title.value);
  formData.append('slug', slug.value);
  if (fileInput.value) {
    formData.append('image', fileInput.value);
  }
  formData.append('content', content.value);
  formData.append('status', 0); // Draft status
  // formData.append('draft', JSON.stringify(draftData)); // Add draft as JSON string

  try {
    await api.post('/posts/draft', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    router.push('/listnews');
  } catch (error) {
    console.error('Error saving draft:', error);
  }
};

const submit = async () => {
  const formData = new FormData();
  formData.append('category_id', category.value);
  formData.append('title', title.value);
  formData.append('slug', slug.value);
  if (fileInput.value) {
    formData.append('image', fileInput.value);
  }
  formData.append('content', content.value);
  formData.append('status', status.value);

  try {
    await api.post('/posts', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
    router.push('/listnews');
  } catch (error) {
    console.error('Error saving post:', error);
  }
};


const saveTime = ref();
const category = ref('');
const title = ref('');
const slug = ref('');
const content = ref('');
const status = ref(2);


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