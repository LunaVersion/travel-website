<template>
  <div id="listnews">
    <MainLayoutForListNews/>
    <router-view></router-view>
    <h4 class="title">News Management</h4>
    <div class="main">
        <div class="bar">
            <div class="filters_bar">
                <div class="left">
                    <button type="button" class="btn btn-light" @click="$event => showActionFilterBar = !showActionFilterBar">Filters</button>
                    <button type="button" class="btn btn-light">
                        <router-link to="/createnews">+ Add News</router-link>
                    </button>
                    <div v-if="showSelected"> {{ numberOfSelected }} selected</div>
                </div>
                <div class="right">
                    <input v-model="search" class="form-control" list="dataList" placeholder="Search..." />
                    <datalist id="dataList">
                        <option v-for="(item, index) in items" :key="index">{{ item.title }}</option>
                    </datalist>
                </div>
            </div>
            <div class="action_filter_bar" v-if="showActionFilterBar">
                <div>Status:</div>
                <div>
                    <input type="checkbox" id="published" v-model="selectedFilters.published">
                    <label for="published">Published</label>
                </div>
                <div>
                    <input type="checkbox" id="unpublished" v-model="selectedFilters.unpublished">
                    <label for="unpublished">Unpublished</label>
                </div>
                <div>
                    <input type="checkbox" id="isDraft" v-model="selectedFilters.isDraft">
                    <label for="isDraft">Is draft</label>
                </div>
                <div>
                    <label for="datepicker">Create At: </label>
                    <input type="date" id="datepicker" placeholder="YYYY-mm-dd">
                </div>
                <button type="button" @click="showActionFilterBar = !showActionFilterBar">Apply</button>
            </div>

            <div class="action_select_bar" v-if="showActionSelectBar">
                <button type="button" class="btn btn-light" @click="confirmDeleteMultiple">
                    <i class='bx bx-trash' id="delete"></i>
                    <label for="delete">Delete</label>
                </button>
                <button type="button" class="btn btn-light">
                    <i class='bx bx-globe' id="published"></i>
                    <label for="published">Published</label>
                </button>
                <button type="button" class="btn btn-light">
                    <i class='bx bx-lock' id="unpublished"></i>
                    <label for="unpublished">Unpublished</label>
                </button>
            </div>
        </div>
        <div class="container">
            <div class="tablenews">
                <table class="table">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col col-checkbox">
                                <div class="form-check">
                                    <input 
                                    type="checkbox" 
                                    @change="handleCheckboxChange"
                                    :checked="isAnySelected"
                                    class="form-check-input" 
                                    >
                                </div>
                            </th>
                            <th scope="col col-image">Image</th>
                            <th scope="col col-title">Title</th>
                            <th scope="col col-create">Create At</th>
                            <th scope="col col-status">Status</th>
                            <th scope="col col-deleteandedit"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, index) in paginatedItems" :key="index">
                            <th scope="row">
                                <input 
                                    type="checkbox" 
                                    class="form-check-input" 
                                    v-model="item.selected"
                                    @change="updateIsAnySelected"
                                    >
                            </th>
                            <td>
                                <img :src="item.image" alt="img" width="80" height="50" />
                            </td>
                            <td>{{item.title}}</td>
                            <td>{{ item.createAt }}</td>
                            <td>
                                <div class="publish">{{ item.status === 1 ? 'Published' : 'Unpublished' }}</div>
                                <div class="draft" v-if="item.isDraft">Draft</div>
                            </td>
                            <td>
                                <router-link :to="'/editnews/' + item.id"><i class='bx bx-pencil' ></i></router-link>
                                <button class="delete_news" @click="confirmDelete(index)"><i class='bx bx-trash'></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="pagination">
        <button :disabled="isDisablePrevious()" @click="pageDown">Previous</button>
        <p class="pagecount">Page {{ countPage }} of {{ page() }}</p>
        <button :disabled="isDisableNext()" @click="pageUp">Next</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import api from '../api';

import MainLayoutForListNews from '../layouts/MainLayoutForListNews.vue';

const items = ref([]); 

const fetchItems = async () => {
    try {
        const response = await api.get('/posts')  
        items.value = response.data
    } catch (error) {
        console.error('Error fetching posts:', error)
    }
};

onMounted(() => {
    fetchItems();
});

const confirmDeleteMultiple = () => {
  const confirmation = window.confirm("Are you sure you want to delete selected news items?");
  if (confirmation) {
    items.value = items.value.filter(item => !item.selected); // Xóa các bài viết đã chọn
  } else {
    console.log("Deletion canceled");
  }
};

const itemsPerPage = 5;
const countPage = ref(1);
const page = () => Math.ceil(filteredItems.value.length / itemsPerPage);

const search = ref('');
const selectedFilters = ref({
    published: false,
    unpublished: false,
    isDraft: false,
});

const filteredItems = computed(() => {
    return items.value.filter(item => {
        const matchesSearch = item.title.toLowerCase().includes(search.value.toLowerCase());
        const matchesStatus = (!selectedFilters.value.published && !selectedFilters.value.unpublished) ||
                                (selectedFilters.value.published && item.status === 1) ||
                                (selectedFilters.value.unpublished && item.status === 0);
        const matchesDraft = (!selectedFilters.value.isDraft || item.draft);

        return matchesSearch && matchesStatus && matchesDraft;
    });
});


const paginatedItems = computed(() => {
    const start = (countPage.value - 1) * itemsPerPage;
    return filteredItems.value.slice(start, start + itemsPerPage);
});

const showSelected = ref(false);
const countSelectedItem = computed(() => items.value.filter(item => item.selected).length);

watch(countSelectedItem, (newValue) => {
    showSelected.value = newValue > 0;
});

const numberOfSelected = computed(() => countSelectedItem.value + ' row');

const pageUp = () => {
    if (!isDisableNext()) {
        countPage.value++;
    }
};

const pageDown = () => {
    if (!isDisablePrevious()) {
        countPage.value--;
    }
};

const isDisablePrevious = () => countPage.value <= 1;
const isDisableNext = () => countPage.value >= page();

const isAnySelected = computed(() => items.value.some(item => item.selected));

const toggleAll = (event) => {
    const value = event.target.checked;
    items.value.forEach(item => {
        item.selected = value;
    });
};

const handleCheckboxChange = (event) => {
    toggleAll(event);
    showActionSelectBar.value = isAnySelected.value;
};

const updateIsAnySelected = () => {
    showActionSelectBar.value = isAnySelected.value;
};

const showActionFilterBar = ref(false);
const showActionSelectBar = ref(false);

// Xóa bài viết
const confirmDelete = (index) => {
  const confirmation = window.confirm("Are you sure you want to delete this news item?");
  if (confirmation) {
    console.log("Item deleted:", items.value[index].title);
    items.value.splice(index, 1);
  } else {
    console.log("Deletion canceled");
  }
};

// const updateFilter = (filterName, value) => {em co
//     selectedFilters.value[filterName] = value;
// };

</script>

<style scoped>
#listnews {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: left;
  color: #2c3e50;
  margin-top: 60px;
}
.title {
    position: absolute;
    left: 315px;
    top: 150px;
    font-weight: 700;
}
.delete_news {
    border: none;
    background-color: #fff;
}
/* .tablenews {
    position: absolute;
    top: 294px;
    left: 315px;
    width: 65%;
    border-collapse: collapse;
} */

.img {
    width: 80px;
    height: 50px;
    background-size: 80px 50px;
    /* background-image: url(../assets/imgoflistnews.png); */
}
.main {
    position: absolute;
    width: 65%;
    height: 50px;
    left: 315px;
    top: 250px;
}
.left {
    display: flex;
    justify-content: space-between;
}
.filters_bar {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.pagination {
    position: absolute;
    width: 65%;
    height: 50px;
    left: 315px;
    top: 710px;
    display: flex;
    justify-content: space-between;
}
.action_filter_bar {
    display: flex;
    justify-content: space-between;
}
.action_select_bar {
    display: flex;
    justify-content: left;
}
</style>