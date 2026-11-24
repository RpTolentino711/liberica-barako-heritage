(() => {
    const rowsContainer = document.getElementById('journeyStepRows');
    const status = document.getElementById('journeyOrderStatus');
    if (!rowsContainer || !status) return;

    let draggedRow = null;
    let dragCompleted = false;
    let saving = false;
    let savedOrder = [...rowsContainer.querySelectorAll('[data-step-row]')].map((row) => row.dataset.stepId);

    const rows = () => [...rowsContainer.querySelectorAll('[data-step-row]')];

    const applyOrder = (ids) => {
        const rowMap = new Map(rows().map((row) => [row.dataset.stepId, row]));
        ids.forEach((id) => {
            const row = rowMap.get(String(id));
            if (row) rowsContainer.append(row);
        });
        rows().forEach((row, index) => {
            const order = row.querySelector('[data-step-order]');
            if (order) order.textContent = String(index + 1);
        });
    };

    const saveOrder = async () => {
        if (saving) return;
        const order = rows().map((row) => row.dataset.stepId);
        if (order.join(',') === savedOrder.join(',')) return;
        saving = true;
        rowsContainer.querySelectorAll('button').forEach((button) => { button.disabled = true; });
        status.textContent = 'Saving step order…';
        try {
            const response = await fetch(rowsContainer.dataset.reorderUrl, {
                method: 'PATCH',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                },
                body: JSON.stringify({ steps: order }),
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Could not save this order.');
            savedOrder = order;
            status.textContent = 'Step order saved.';
        } catch (error) {
            applyOrder(savedOrder);
            status.textContent = error.message || 'Could not save this order. Please try again.';
        } finally {
            saving = false;
            rowsContainer.querySelectorAll('button').forEach((button) => { button.disabled = false; });
        }
    };

    const moveRow = (row, direction) => {
        const currentRows = rows();
        const index = currentRows.indexOf(row);
        const target = direction === 'up' ? currentRows[index - 1] : currentRows[index + 1];
        if (!target) return;
        if (direction === 'up') rowsContainer.insertBefore(row, target);
        else rowsContainer.insertBefore(target, row);
        applyOrder(rows().map((item) => item.dataset.stepId));
        saveOrder();
    };

    rowsContainer.addEventListener('click', (event) => {
        const button = event.target.closest('[data-move-step]');
        if (!button || saving) return;
        const row = button.closest('[data-step-row]');
        if (row) moveRow(row, button.dataset.moveStep);
    });

    rowsContainer.addEventListener('keydown', (event) => {
        const handle = event.target.closest('[data-drag-handle]');
        if (!handle || saving || !['ArrowUp', 'ArrowDown'].includes(event.key)) return;
        event.preventDefault();
        const row = handle.closest('[data-step-row]');
        if (row) moveRow(row, event.key === 'ArrowUp' ? 'up' : 'down');
    });

    rowsContainer.addEventListener('dragstart', (event) => {
        const handle = event.target.closest('[data-drag-handle]');
        if (!handle || saving) { event.preventDefault(); return; }
        draggedRow = handle.closest('[data-step-row]');
        dragCompleted = false;
        if (!draggedRow) return;
        draggedRow.classList.add('is-dragging');
        event.dataTransfer.effectAllowed = 'move';
        event.dataTransfer.setData('text/plain', draggedRow.dataset.stepId);
    });

    rowsContainer.addEventListener('dragover', (event) => {
        if (!draggedRow || saving) return;
        event.preventDefault();
        const targetRow = event.target.closest('[data-step-row]');
        if (!targetRow || targetRow === draggedRow) return;
        rowsContainer.querySelectorAll('.is-drop-target').forEach((row) => row.classList.remove('is-drop-target'));
        targetRow.classList.add('is-drop-target');
        const bounds = targetRow.getBoundingClientRect();
        const insertAfter = event.clientY > bounds.top + bounds.height / 2;
        rowsContainer.insertBefore(draggedRow, insertAfter ? targetRow.nextSibling : targetRow);
    });

    rowsContainer.addEventListener('drop', (event) => {
        if (!draggedRow) return;
        event.preventDefault();
        dragCompleted = true;
        draggedRow.classList.remove('is-dragging');
        rowsContainer.querySelectorAll('.is-drop-target').forEach((row) => row.classList.remove('is-drop-target'));
        applyOrder(rows().map((row) => row.dataset.stepId));
        saveOrder();
    });

    rowsContainer.addEventListener('dragend', () => {
        if (!draggedRow) return;
        draggedRow.classList.remove('is-dragging');
        rowsContainer.querySelectorAll('.is-drop-target').forEach((row) => row.classList.remove('is-drop-target'));
        if (!dragCompleted) applyOrder(savedOrder);
        draggedRow = null;
    });
})();
