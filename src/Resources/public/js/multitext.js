/** MultiTextWizard row editing; no MooTools dependency. */
(() => {
    if (window.MultiText?.native) return;
    function edit(element, command, id) {
        const table = id ? document.getElementById(id) : element.closest('[data-multitext-name]');
        const row = element.closest('tr');
        if (!table || !row || row.parentElement !== table.tBodies[0]) return;
        const body = row.parentElement;
        let focus = null;
        switch (command) {
            case 'rnew':
            case 'rcopy': {
                const clone = row.cloneNode(true);
                clone.querySelectorAll('input[data-multitext-column]').forEach((input, col) => {
                    input.value = command === 'rnew' ? '' : row.querySelectorAll('input[data-multitext-column]')[col].value;
                    input.removeAttribute('aria-invalid');
                });
                clone.querySelectorAll('.tl_error').forEach(error => error.remove());
                row.after(clone);
                focus = clone.querySelector('input');
                break;
            }
            case 'rup':
                if (row.previousElementSibling) body.insertBefore(row, row.previousElementSibling);
                else body.append(row);
                break;
            case 'rdown':
                if (row.nextElementSibling) row.nextElementSibling.after(row);
                else body.prepend(row);
                break;
            case 'rdelete':
                if (body.rows.length <= 1) return;
                focus = (row.nextElementSibling || row.previousElementSibling).querySelector('input');
                row.remove();
                break;
            default: return;
        }
        Array.from(body.rows).forEach((item, index) => {
            item.querySelectorAll('input[data-multitext-column]').forEach(input => {
                const col = input.dataset.multitextColumn;
                input.name = `${table.dataset.multitextName}[${index}][${col}]`;
                input.id = `${table.id}_${index}_${col}`;
            });
        });
        table.dispatchEvent(new Event('input', {bubbles: true}));
        table.dispatchEvent(new Event('change', {bubbles: true}));
        if (focus) { focus.focus(); focus.select(); }
    }
    window.MultiText = {native: true, multitextWizard: edit};
    document.addEventListener('click', event => {
        const button = event.target.closest('[data-multitext-action]');
        if (!button) return;
        event.preventDefault();
        edit(button, button.dataset.multitextAction);
    });
})();
