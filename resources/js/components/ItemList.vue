<template>
  <!-- HEADER -->
  <div class="heading">
    <h1>Records</h1>
  </div>

  <!-- MAIN WRAPPER -->
  <div class="main-wrapper">

    <!-- LEFT STATUS TABS -->
    <aside class="status-panel">
      <div
        class="status-tab"
        :class="{ active: filter === '' }"
        @click="setStatus('')"
      >
        All
      </div>

      <div
        class="status-tab"
        :class="{ active: filter === 'active' }"
        @click="setStatus('active')"
      >
        Active
      </div>

      <div
        class="status-tab"
        :class="{ active: filter === 'inactive' }"
        @click="setStatus('inactive')"
      >
        Inactive
      </div>
    </aside>

    <!-- RIGHT RECORDS SECTION -->
    <section class="records-section position-relative">

      <!-- CREATE BUTTON -->
      <div class="d-flex justify-content-end mb-3">
        <button class="btn btn-success" @click="create">
          <i class="bi bi-plus-circle"></i> Create Record
        </button>
      </div>

      <!-- TABLE -->
      <table class="table table-bordered">
        <thead class="text-center">
          <tr>
            <th>UUID</th>
            <th>Name</th>
            <th>Code</th>
            <th>Status</th>
            <th>Description</th>
            <th colspan="2">Actions</th>
          </tr>
        </thead>

        <tbody>
          <tr v-for="item in items" :key="item.uuid">
            <td>{{ item.uuid }}</td>
            <td>{{ item.name }}</td>
            <td>{{ item.code }}</td>
            <td>{{ item.status }}</td>
            <td>{{ item.description }}</td>

            <td class="action-cell">
              <button class="btn btn-sm btn-primary" @click="edit(item)">
                <i class="bi bi-pencil-square"></i> Edit
              </button>
            </td>

            <td class="action-cell">
              <button class="btn btn-sm btn-danger" @click="confirmDelete(item)">
                <i class="bi bi-trash"></i> Delete
              </button>
            </td>
          </tr>
        </tbody>
      </table>

      <!-- FLOATING FORM -->
      <div
        v-if="showForm"
        class="floating-form card shadow"
        :style="{ top: position.y + 'px', left: position.x + 'px' }"
      >
        <div
          class="card-header d-flex justify-content-between cursor-move"
          @mousedown="startDrag"
        >
        <strong>
          <i class="bi" :class="{
            'bi-plus-circle text-success': mode === 'create',
            'bi-pencil-square text-primary': mode === 'edit',
            'bi-trash text-danger': mode === 'delete'
          }"></i>
          {{
            mode === 'create'
              ? 'Create Item'
              : mode === 'edit'
                ? 'Edit Item'
                : 'Delete Item'
          }}
        </strong>
          
          <button class="btn-close" @click="close"></button>
        </div>

        <div class="card-body">
          <div v-if="mode === 'delete'" class="alert alert-danger">
            Are you sure you want to delete this item?
          </div>

          <input v-model="form.name" class="form-control mb-2" :disabled="mode === 'delete'" />
          <input v-model="form.code" class="form-control mb-2" :disabled="mode === 'delete'" />

          <select v-model="form.status" class="form-select mb-2" :disabled="mode === 'delete'">
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
          </select>

          <textarea v-model="form.description" class="form-control mb-3" :disabled="mode === 'delete'"></textarea>

          <div class="d-flex justify-content-between">
            <button class="btn btn-secondary" @click="close">Cancel</button>
            <button v-if="mode !== 'delete'" class="btn btn-primary" @click="save">Save</button>
            <button v-if="mode === 'delete'" class="btn btn-danger" @click="deleteItem">Delete</button>
          </div>
        </div>
      </div>

    </section>
  </div>
</template>

<script>
export default {
  data() {
    return {
      items: [],
      filter: '',
      showForm: false,
      mode: 'create',
      form: {
        uuid: null,
        name: '',
        code: '',
        status: 'active',
        description: ''
      },
      position: { x: 400, y: 120 },
      dragging: false,
      offset: { x: 0, y: 0 }
    }
  },

  computed: {
    modeTitle() {
      return this.mode === 'create'
        ? 'Create Record'
        : this.mode === 'edit'
        ? 'Edit Record'
        : 'Delete Record'
    }
  },

  mounted() {
    this.fetchItems()
    window.addEventListener('mousemove', this.onDrag)
    window.addEventListener('mouseup', this.stopDrag)
  },

  methods: {
    setStatus(status) {
      this.filter = status
      this.fetchItems()
    },

    fetchItems() {
      this.$axios
        .get('/api/items', { params: { status: this.filter } })
        .then(res => (this.items = res.data))
    },

    create() {
      this.resetForm()
      this.mode = 'create'
      this.showForm = true
    },

    edit(item) {
      this.form = { ...item }
      this.mode = 'edit'
      this.showForm = true
    },

    confirmDelete(item) {
      this.form = { ...item }
      this.mode = 'delete'
      this.showForm = true
    },

    close() {
      this.showForm = false
      this.resetForm()
    },

    save() {
      const req = this.form.uuid
        ? this.$axios.put(`/api/items/${this.form.uuid}`, this.form)
        : this.$axios.post('/api/items', this.form)

      req.then(() => {
        this.fetchItems()
        this.close()
      })
    },

    deleteItem() {
      this.$axios.delete(`/api/items/${this.form.uuid}`).then(() => {
        this.fetchItems()
        this.close()
      })
    },

    resetForm() {
      this.form = {
        uuid: null,
        name: '',
        code: '',
        status: 'active',
        description: ''
      }
    },

    startDrag(e) {
      this.dragging = true
      this.offset.x = e.clientX - this.position.x
      this.offset.y = e.clientY - this.position.y
    },

    onDrag(e) {
      if (!this.dragging) return
      this.position.x = e.clientX - this.offset.x
      this.position.y = e.clientY - this.offset.y
    },

    stopDrag() {
      this.dragging = false
    }
  }
}
</script>

<style scoped>
/* HEADER */
.heading {
  height: 80px;
  background: #1e293b;
  color: #ffff;
  display: flex;
  justify-content: center;
  align-items: center;
}

/* MAIN LAYOUT */
.main-wrapper {
  display: flex;
  min-height: calc(100vh - 80px);
}

/* LEFT STATUS PANEL */
.status-panel {
  width: 150px;
  background-color: #1e293b;
  padding: 15px;
}

/* STATUS TAB */
.status-tab {
  background-color: #334155;
  color: white;
  padding: 12px;
  margin-bottom: 12px;
  text-align: center;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.status-tab.active {
  background-color: #2563eb;
}

/* RIGHT CONTENT */
.records-section {
  flex: 1;
  padding: 20px;
}

/* ACTION COLUMN */
.action-cell {
  width: 120px;
  text-align: center;
}

/* FLOATING FORM */
.floating-form {
  position: absolute;
  width: 500px;
  z-index: 1000;
}

.cursor-move {
  cursor: move;
}

.btn-primary,.btn-danger,.btn-secondary{
  border-radius: 20px;
  width: 80px;
  height: 40px;
}
.btn-success{
  border-radius: 20px;
  width: 150px;
  height: 40px;
}

table{
  border-radius: 10px;
  border: 2px solid ;
  background-color: transparent;
  
}

th,td {
  border: 1 solid;
  background-color: transparent;
}
</style>
