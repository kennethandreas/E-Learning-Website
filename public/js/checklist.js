document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('checklist-root');
    if (!root) return;

    const pendingJson = root.getAttribute('data-pending') || '[]';
    let pending = [];
    try {
        pending = JSON.parse(pendingJson);
    } catch (e) {
        console.error('Invalid pending JSON for checklist', e);
        pending = [];
    }

    const completeRoute = root.getAttribute('data-complete-route');
    const pendingCount = Array.isArray(pending) ? pending.length : 0;

    const modal = document.getElementById('checklistModal');
    const closeBtn = document.getElementById('checklistClose');

    function showModal() {
        if (!modal) return;
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function hideModal() {
        if (!modal) return;
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    if (pendingCount > 0) {
        setTimeout(showModal, 400);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', function () {
            hideModal();
        });
    }

    document.querySelectorAll('.mark-done').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const key = this.getAttribute('data-key');
            const tokenMeta = document.querySelector('meta[name="csrf-token"]');
            const token = tokenMeta ? tokenMeta.getAttribute('content') : '';
            if (!completeRoute) {
                console.error('Checklist complete route not provided');
                return;
            }
            fetch(completeRoute, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ key: key })
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    const card = btn.closest('.checklist-card');
                    if (card) card.remove();

                    const remaining = document.querySelectorAll('#checklistContainer .checklist-card').length;
                    if (remaining === 0) hideModal();
                }
            }).catch(err => {
                console.error('Checklist save error', err);
            });
        });
    });
});
