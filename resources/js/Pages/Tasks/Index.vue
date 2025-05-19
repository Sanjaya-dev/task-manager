<template>
    <div class="max-w-2xl mx-auto p-4">
        <h1 class="text-2xl font-bold mb-4">Tasks</h1>
        <!-- form tambah task -->
         <form @submit.prevent="createTask" class="mb-6">
            <input v-model="form.title" type="text" placeholder="Task Title" class="border rounded p-2 w-full"/>
            <textarea v-model="form.description" placeholder="Task Description" class="border rounded p-2 w-full mt-2"></textarea>
            <button class="bg-blue-600 text-white px-4 py-2 rounded mt-2 hover:bg-blue-700">Add Task</button>
        </form>

        <!-- daftar task -->
         <ul>
            <li v-for="task in tasks" :key="task.id" class="flex justify-between items-center border-b py-2">
                <div>
                    <input type="checkbox" v-model="task.completed" @change="toggleCompleted(task)" class="mr-2"/>
                    <span :class="{ 'line-through text-gray-500': task.completed  }" class="ml-2">{{ task.title }}</span>
                    <p class="text-sm text-gray-600">{{ task.description }}</p>
                </div>
                <button @click="deleteTask(task)" class="bg-red-600 text-white px-4 py-2 rounded">Delete</button>
            </li>
         </ul>
    </div>
</template>
<script setup>
    import { ref } from 'vue';
    import { router } from '@inertiajs/vue3';
    import { usePage } from '@inertiajs/vue3';
    // Gunakan computed untuk selalu mendapatkan data terbaru
    import { computed } from 'vue';

    // Menggunakan computed property untuk selalu sync dengan props
    const tasks = computed(() => usePage().props.tasks);

    const form = ref({
        title: '',
        description: '',
    })

    function createTask() {
        router.post('/tasks', form.value, {
            onSuccess: () => {
                form.value.title = '';
                form.value.description = '';
            }
        })
    }

    function toggleCompleted(task) {
        router.put(`/tasks/${task.id}`, {
            completed: task.completed,
            title: task.title,
            description: task.description
        });
    }

    function deleteTask(task) {
        if (confirm('Are you sure you want to delete this task?')) {
            router.delete(`/tasks/${task.id}`);
        }
    }
</script>
<style scoped>
/* Bisa ditambah styling tambahan jika diperlukan */
</style>