@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-darker-gray rounded-lg border border-neon-purple/30 p-6 mb-6 shadow-lg shadow-neon-purple/10">
        <h2 class="text-xl font-semibold mb-4 text-neon-cyan">Новая задача</h2>
        <form id="createTaskForm" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-neon-green mb-2">Название</label>
                <input type="text" id="title" name="title" required 
                       class="w-full bg-dark-gray border border-neon-blue/30 rounded-lg px-4 py-3 text-white 
                              focus:outline-none focus:border-neon-cyan focus:ring-2 focus:ring-neon-cyan/20
                              transition duration-200 placeholder-gray-500">
            </div>
            
            <div>
                <label for="description" class="block text-sm font-medium text-neon-green mb-2">Описание</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full bg-dark-gray border border-neon-blue/30 rounded-lg px-4 py-3 text-white 
                                 focus:outline-none focus:border-neon-cyan focus:ring-2 focus:ring-neon-cyan/20
                                 transition duration-200 placeholder-gray-500 resize-none"></textarea>
            </div>
            
            <button type="submit" 
                    class="bg-gradient-to-r from-neon-purple to-neon-pink hover:from-neon-pink hover:to-neon-purple 
                           text-white font-bold py-3 px-6 rounded-lg transition-all duration-300 
                           transform hover:scale-105 shadow-lg shadow-neon-purple/20">
                Создать задачу
            </button>
        </form>
    </div>

    <div class="bg-darker-gray rounded-lg border border-neon-purple/30 p-6 shadow-lg shadow-neon-purple/10">
        <h2 class="text-xl font-semibold mb-4 text-neon-cyan">Список задач</h2>

        <div class="bg-dark-gray rounded-lg border border-neon-blue/20 p-4 mb-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-neon-green">Сортировка</h3>
                <div class="flex space-x-3">
                    <button onclick="setSortOrder('DESC')" 
                            class="bg-neon-purple hover:bg-neon-purple/80 text-white px-4 py-2 rounded-lg 
                                   transition duration-200 border border-neon-purple/50 font-medium">
                        Новые сначала
                    </button>
                    <button onclick="setSortOrder('ASC')" 
                            class="bg-neon-cyan hover:bg-neon-cyan/80 text-dark px-4 py-2 rounded-lg 
                                   transition duration-200 border border-neon-cyan/50 font-medium">
                        Старые сначала
                    </button>
                </div>
            </div>
        </div>

        <div id="tasksList" class="space-y-4">
            <div class="text-center text-neon-blue/70 py-8">Загрузка задач...</div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    const API_BASE = '/api/_v1/tasks';
    let currentSortOrder = 'DESC';

    const STATUS = {
        CANCELED: 0,
        PENDING: 1, 
        DONE: 2,
        LOST: 3
    };

    const STATUS_COLORS = {
        [STATUS.CANCELED]: 'neon-blue',
        [STATUS.PENDING]: 'neon-cyan', 
        [STATUS.DONE]: 'neon-green',
        [STATUS.LOST]: 'neon-pink'
    };

    const STATUS_LABELS = {
        [STATUS.CANCELED]: 'Отменена',
        [STATUS.PENDING]: 'В ожидании',
        [STATUS.DONE]: 'Выполнена',
        [STATUS.LOST]: 'Просрочена'
    };

    document.addEventListener('DOMContentLoaded', function() {
        loadTasks();
        highlightActiveSortButton();
    });

    document.getElementById('createTaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = {
            title: document.getElementById('title').value,
            description: document.getElementById('description').value
        };

        try {
            await axios.post(API_BASE, formData);
            document.getElementById('createTaskForm').reset();
            loadTasks();
        } catch (error) {
            alert('Ошибка создания задачи: ' + error.response?.data?.message);
        }
    });

    function setSortOrder(order) {
        currentSortOrder = order;
        loadTasks();
        highlightActiveSortButton();
    }

    function highlightActiveSortButton() {
        document.querySelectorAll('[onclick^="setSortOrder"]').forEach(btn => {
            btn.classList.remove('ring-2', 'ring-neon-cyan');
        });
        
        const activeBtn = document.querySelector(`[onclick="setSortOrder('${currentSortOrder}')"]`);
        if (activeBtn) {
            activeBtn.classList.add('ring-2', 'ring-neon-cyan');
        }
    }

    function displayTasks(tasks) {
        const tasksList = document.getElementById('tasksList');
        
        if (tasks.length === 0) {
            tasksList.innerHTML = `
                <div class="text-center text-neon-blue/70 py-8 border border-neon-blue/20 rounded-lg">
                    Нет задач
                </div>`;
            return;
        }

        tasksList.innerHTML = tasks.map(task => {
            const statusColor = STATUS_COLORS[task.status] || 'neon-cyan';
            const statusLabel = STATUS_LABELS[task.status] || 'Неизвестно';
            const isDone = task.status === STATUS.DONE;
            const isPending = task.status === STATUS.PENDING;
            const isCanceled = task.status === STATUS.CANCELED;
            const isLost = task.status === STATUS.LOST;

            return `
            <div class="border border-${statusColor}/30 bg-dark-gray rounded-lg p-5 
                        transition-all duration-300 hover:shadow-lg hover:shadow-${statusColor}/10" 
                 id="task-${task.id}">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-lg ${isDone ? 'line-through text-neon-green' : 'text-white'}">
                            ${task.title}
                        </h3>
                        ${task.description ? `
                            <p class="text-gray-300 mt-2 leading-relaxed">${task.description}</p>
                        ` : ''}
                        <div class="flex items-center space-x-4 mt-3">
                            <span class="text-sm text-${statusColor} font-medium">
                                ${statusLabel}
                            </span>
                            <span class="text-sm text-neon-blue/70">
                                📅 ${new Date(task.created_at).toLocaleDateString('ru-RU')}
                            </span>
                        </div>
                    </div>
                    
                    <div class="grid grid-rows-2 grid-cols-3 gap-y-[8px] justify-end">
                        ${!isDone ? `
                            <button onclick="updateTaskStatus(${task.id}, ${STATUS.DONE})" 
                                    class="bg-neon-green hover:bg-neon-green/80 text-dark font-medium px-4 py-2 rounded-lg 
                                           transition duration-200 border border-neon-green/50 text-sm">
                                Выполнить
                            </button>
                        ` : ''}
                        
                        ${!isPending ? `
                            <button onclick="updateTaskStatus(${task.id}, ${STATUS.PENDING})" 
                                    class="bg-neon-cyan hover:bg-neon-cyan/80 text-dark font-medium px-4 py-2 rounded-lg 
                                           transition duration-200 border border-neon-cyan/50 text-sm">
                                В ожидание
                            </button>
                        ` : ''}
                        
                        ${!isCanceled ? `
                            <button onclick="updateTaskStatus(${task.id}, ${STATUS.CANCELED})" 
                                    class="bg-neon-blue hover:bg-neon-blue/80 text-white font-medium px-4 py-2 rounded-lg 
                                           transition duration-200 border border-neon-blue/50 text-sm">
                                Отменить
                            </button>
                        ` : ''}
                        
                        ${!isLost ? `
                            <button onclick="updateTaskStatus(${task.id}, ${STATUS.LOST})" 
                                    class="bg-neon-pink hover:bg-neon-pink/80 text-white font-medium px-4 py-2 rounded-lg 
                                           transition duration-200 border border-neon-pink/50 text-sm">
                                Просрочить
                            </button>
                        ` : ''}
                        
                        <button onclick="editTask(${task.id})" 
                                class="bg-gray-600 hover:bg-gray-500 text-white font-medium px-4 py-2 rounded-lg 
                                       transition duration-200 border border-gray-500 text-sm">
                            Редактировать
                        </button>
                        
                        <button onclick="deleteTask(${task.id})" 
                                class="bg-red-600 hover:bg-red-500 text-white font-medium px-4 py-2 rounded-lg 
                                       transition duration-200 border border-red-500 text-sm">
                            Удалить
                        </button>
                    </div>
                </div>
                
                <!-- Форма редактирования -->
                <div id="edit-form-${task.id}" class="hidden mt-4 p-4 bg-dark border border-neon-blue/20 rounded-lg">
                    <h4 class="font-medium text-neon-cyan mb-3">Редактирование задачи</h4>
                    <form onsubmit="updateTask(event, ${task.id})" class="space-y-3">
                        <input type="text" id="edit-title-${task.id}" value="${task.title}" 
                               class="w-full bg-dark-gray border border-neon-blue/30 rounded-lg px-3 py-2 text-white 
                                      focus:outline-none focus:border-neon-cyan">
                        <textarea id="edit-description-${task.id}" rows="3"
                                  class="w-full bg-dark-gray border border-neon-blue/30 rounded-lg px-3 py-2 text-white 
                                         focus:outline-none focus:border-neon-cyan resize-none">${task.description || ''}</textarea>
                        <div class="flex space-x-2">
                            <button type="submit" 
                                    class="bg-neon-green hover:bg-neon-green/80 text-dark font-medium px-4 py-2 rounded-lg 
                                           transition duration-200">
                                Сохранить
                            </button>
                            <button type="button" onclick="cancelEdit(${task.id})" 
                                    class="bg-gray-600 hover:bg-gray-500 text-white font-medium px-4 py-2 rounded-lg 
                                           transition duration-200">
                                Отмена
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            `;
        }).join('');
    }

    function editTask(taskId) {
        document.getElementById(`edit-form-${taskId}`).classList.toggle('hidden');
    }

    function cancelEdit(taskId) {
        document.getElementById(`edit-form-${taskId}`).classList.add('hidden');
    }

    async function loadTasks() {
        try {
            const response = await axios.get(`${API_BASE}?order=${currentSortOrder}`);
            displayTasks(response.data.data || response.data);
        } catch (error) {
            console.error('Ошибка загрузки задач:', error);
            document.getElementById('tasksList').innerHTML = 
                '<div class="text-center text-neon-pink py-4 border border-neon-pink/30 rounded-lg">Ошибка загрузки задач</div>';
        }
    }

    async function updateTaskStatus(taskId, newStatus) {
        try {
            await axios.put(`${API_BASE}/${taskId}`, {
                status: newStatus
            });
            loadTasks();
        } catch (error) {
            alert('Ошибка обновления статуса задачи');
        }
    }

    async function updateTask(e, taskId) {
        e.preventDefault();
        
        const formData = {
            title: document.getElementById(`edit-title-${taskId}`).value,
            description: document.getElementById(`edit-description-${taskId}`).value
        };

        try {
            await axios.put(`${API_BASE}/${taskId}`, formData);
            loadTasks();
        } catch (error) {
            alert('Ошибка обновления задачи');
        }
    }

    async function deleteTask(taskId) {
        if (!confirm('Удалить задачу?')) return;
        
        try {
            await axios.delete(`${API_BASE}/${taskId}`);
            loadTasks();
        } catch (error) {
            alert('Ошибка удаления задачи');
        }
    }
    
</script>
@endpush
@endsection