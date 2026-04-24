document.addEventListener('DOMContentLoaded', () => {
    const el = document.getElementById('task-list');

    new Sortable(el, {
        animation: 150,
        onEnd: function () {
            let order = [];

            document.querySelectorAll('.task-item').forEach((el, index) => {
                order.push({
                    id: el.dataset.id,
                    priority: index + 1
                });
            });

            fetch('/tasks/reorder', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(order)
            });
        }
    });
});