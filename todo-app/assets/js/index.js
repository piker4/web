document.addEventListener('DOMContentLoaded', function () {
    const addForm = document.getElementById('addTaskForm');
    const tasksList = document.getElementById('tasksList');
    if (addForm) {
        addForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const title = document.getElementById('taskTitle').value.trim();
            const desc = document.getElementById('taskDescription').value.trim();

            const res = await fetch('/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'add_task',
                    title: title,
                    description: desc
                })
            });
            const data = await res.json();
            if (data.success) {
                location.reload();
            } else {
                alert('Ошибка: ' + (data.error || 'не удалось добавить задачу'));
            }
        });
    }
    document.querySelectorAll('.toggle-task').forEach(btn => {
        btn.addEventListener('click', async function () {
            const id = this.dataset.id;
            const res = await fetch('/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'toggle_task', id: id })
            });
            const data = await res.json();
            if (data.success) {
                location.reload();
            }
        });
    });
    document.querySelectorAll('.delete-task').forEach(btn => {
        btn.addEventListener('click', async function () {
            if (!confirm('Удалить задачу?')) return;
            const id = this.dataset.id;
            const res = await fetch('/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({ action: 'delete_task', id: id })
            });
            const data = await res.json();
            if (data.success) {
                location.reload();
            }
        });
    });
});