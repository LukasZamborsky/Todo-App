<script setup>
import '/resources/css/tasks.css';
import { ref, onMounted, watch } from 'vue';
import axios from 'axios';

const tasks = ref([]);
const tags = ref([]);
const search = ref('');
const selectedTag = ref('');
const status = ref('');
const sortOption = ref('');
const loading = ref(false);
const isAdmin = ref(localStorage.getItem('isAdmin') === 'true');
const showNewTagForm = ref(false);
const newTag = ref({ name: '' });

const logout = () => {
  localStorage.removeItem('isAdmin');
  isAdmin.value = false;
  window.location.reload();
};

const editingTask = ref(null);
const newTask = ref({ title: '', description: '', is_completed: false, tag_ids: [] });
const showNewTaskForm = ref(false);

const pagination = ref({
  current_page: 1,
  last_page: 1,
  next_page_url: null,
  prev_page_url: null
});

const fetchTasks = async (url = '/api/tasks') => {
  loading.value = true;
  const params = {
    search: search.value,
    status: status.value,
    tag_id: selectedTag.value,
    sort: sortOption.value,
  };
  try {
    const response = await axios.get(url, { params });
    tasks.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
      next_page_url: response.data.next_page_url,
      prev_page_url: response.data.prev_page_url
    };
  } catch (error) {
    console.error('Chyba pri načítaní úloh:', error);
  }
  loading.value = false;
};

const fetchTags = async () => {
  try {
    const response = await axios.get('/api/tags');
    tags.value = response.data;
  } catch (error) {
    console.error('Chyba pri načítaní tagov:', error);
  }
};
const createTag = async () => {
  try {
    await axios.post('/api/tags', newTag.value);
    newTag.value.name = '';
    showNewTagForm.value = false;
    await fetchTags(); 
    console.error('Chyba pri vytváraní tagu:', error);
  }
  catch (error) {
    console.error('Chyba pri vytváraní tagu:', error);
  }
};

const applyFilters = () => {
  fetchTasks();
};

const startEditing = (task) => {
  editingTask.value = {
    id: task.id,
    title: task.title,
    description: task.description,
    is_completed: Boolean(task.is_completed),
    tag_ids: task.tags.map(tag => tag.id)
  };
};

const cancelEdit = () => {
  editingTask.value = null;
};

const saveTask = async () => {
  try {
    await axios.put(`/api/tasks/${editingTask.value.id}`, editingTask.value);
    editingTask.value = null;
    fetchTasks();
  } catch (error) {
    console.error('Chyba pri ukladaní úlohy:', error);
  }
};

const deleteTask = async (id) => {
  if (!confirm('Naozaj chcete zmazať túto úlohu?')) return;
  try {
    await axios.delete(`/api/tasks/${id}`);
    fetchTasks();
  } catch (error) {
    console.error('Chyba pri odstraňovaní úlohy:', error);
  }
};

const createTask = async () => {
  try {
    await axios.post('/api/tasks', newTask.value);
    newTask.value = { title: '', description: '', is_completed: false, tag_ids: [] };
    showNewTaskForm.value = false;
    fetchTasks();
  } catch (error) {
    console.error('Chyba pri vytváraní úlohy:', error);
  }
};

const markAsCompleted = async (taskId) => {
  try {
    await axios.put(`/api/tasks/${taskId}`, { is_completed: true });
    fetchTasks();
  } catch (error) {
    console.error('Chyba pri označení úlohy ako dokončenej:', error);
  }
};

const loginAdmin = () => {
  const username = prompt('Zadajte meno:');
  const password = prompt('Zadajte heslo:');
  if (username === 'admin' && password === 'admin') {
    isAdmin.value = true;
    localStorage.setItem('isAdmin', 'true');
    alert('Úspešne prihlásený ako admin');
  } else {
    alert('Nesprávne prihlasovacie údaje');
  }
};

onMounted(() => {
  fetchTags();
  fetchTasks();
});

watch([search, selectedTag, status, sortOption], applyFilters);
</script>

<template>
  <div class="app-wrapper">
    
    <aside class="sidebar">
      <h2>☑️ Úlohy</h2>
      <ul>
        <li>
          <div class="filters">
            <input v-model="search" placeholder="🔍 Hľadať úlohu..." />
            <select v-model="status">
              <option value="">🗂 Všetky</option>
              <option value="active">⏳ Aktívne</option>
              <option value="completed">✅ Dokončené</option>
            </select>
            <select v-model="selectedTag">
              <option value="">🏷️ Všetky tagy</option>
              <option v-for="tag in tags" :key="tag.id" :value="tag.id">{{ tag.name }}</option>
            </select>
            <select v-model="sortOption">
              <option value="">🔃 Bez triedenia</option>
              <option value="title_asc">🔤 Názov (A → Z)</option>
              <option value="completed_first">✅ Dokončené → Nedokončené</option>
              <option value="incomplete_first">⏳ Nedokončené → Dokončené</option>
            </select>
          </div>
        </li>
        <li v-if="isAdmin" @click="showNewTagForm = true">🏷️ Pridať nový tag</li>

      </ul>


      <div v-if="isAdmin" class="new-task-wrapper">
        <button class="new-task-toggle" @click="showNewTaskForm = !showNewTaskForm">➕ Pridať novú úlohu</button>

        
        <div class="logout-wrapper">
          <button @click="logout" class="logout-button">🚪 Odhlásiť sa</button>
        </div>
      </div>

      <div v-if="!isAdmin" class="logout-wrapper">
        <button @click="loginAdmin" class="admin-btn">🔑 Prihlásiť sa ako admin</button>
      </div>
    </aside>

    
    <main class="main-content">
      <h1>Zoznam Úloh</h1>
      <div v-if="loading">Načítavam úlohy...</div>
      <ul v-else class="task-list">
        <div v-if="showNewTagForm" class="new-tag-form">
           <h3>🏷️ Nový tag</h3>
           <input v-model="newTag.name" placeholder="Názov tagu" />
          <div class="tag-buttons">
           <button @click="createTag">💾 Uložiť</button>
           <button @click="showNewTagForm = false">❌ Zrušiť</button>
          </div>
        </div>

  
        <li v-if="showNewTaskForm" class="task-item">
          <div class="edit-task-card">
            <input v-model="newTask.title" placeholder="Názov úlohy" />
            <textarea v-model="newTask.description" placeholder="Popis úlohy"></textarea>
            <select v-model="newTask.is_completed">
              <option :value="false">⏳ Nedokončená</option>
              <option :value="true">✅ Dokončená</option>
            </select>
            <div class="tag-checkboxes">
              <label v-for="tag in tags" :key="tag.id" class="tag-checkbox">
                <input type="checkbox" :value="tag.id" v-model="newTask.tag_ids" />
                {{ tag.name }}
              </label>
            </div>
            <div class="edit-buttons">
              <button @click="createTask">💾 Uložiť</button>
              <button @click="showNewTaskForm = false">❌ Zrušiť</button>
            </div>
          </div>
        </li>

        <li v-for="task in tasks" :key="task.id" class="task-item">
          <template v-if="editingTask && editingTask.id === task.id">
            <div class="edit-task-card">
              <input v-model="editingTask.title" placeholder="Názov úlohy" />
              <textarea v-model="editingTask.description" placeholder="Popis úlohy"></textarea>
              <select v-model="editingTask.is_completed">
                <option :value="false">⏳ Nedokončená</option>
                <option :value="true">✅ Dokončená</option>
              </select>
              <div class="tag-checkboxes">
                <label v-for="tag in tags" :key="tag.id" class="tag-checkbox">
                  <input type="checkbox" :value="tag.id" v-model="editingTask.tag_ids" />
                  {{ tag.name }}
                </label>
              </div>
              <div class="edit-buttons">
                <button @click="saveTask">💾 Uložiť</button>
                <button @click="cancelEdit">❌ Zrušiť</button>
              </div>
            </div>
          </template>

          <template v-else>
            <div class="task-top">
              <span class="status-icon">{{ task.is_completed ? '✅' : '⏳' }}</span>
              <div class="task-content">
                <strong>{{ task.title }}</strong>
                <p>{{ task.description }}</p>
                <div class="tags">
                  <span v-for="tag in task.tags" :key="tag.id" class="tag">{{ tag.name }}</span>
                </div>
              </div>
              <div v-if="isAdmin" class="admin-actions">
                <button @click="startEditing(task)">✏️</button>
                <button @click="deleteTask(task.id)">🗑️</button>
              </div>
            </div>
            <div v-if="!isAdmin && !task.is_completed" class="user-complete-btn">
              <button @click="markAsCompleted(task.id)">✅ Označiť ako dokončené</button>
            </div>
          </template>
        </li>
      </ul>


      
      <div v-if="pagination.last_page > 1" class="pagination">
        <button @click="fetchTasks(pagination.prev_page_url)" :disabled="!pagination.prev_page_url">⬅️</button>
        <span>Strana {{ pagination.current_page }} z {{ pagination.last_page }}</span>
        <button @click="fetchTasks(pagination.next_page_url)" :disabled="!pagination.next_page_url">➡️</button>
      </div>
    </main>
  </div>
</template>
