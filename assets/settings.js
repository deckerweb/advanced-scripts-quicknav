(() => {
  'use strict';
  const input = document.getElementById('asqn-favorite-filter');
  const rows = Array.from(document.querySelectorAll('[data-asqn-search]'));
  const normalize = value => value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').toLocaleLowerCase();
  if (input) input.addEventListener('input', () => {
    const query = normalize(input.value.trim());
    rows.forEach(row => { row.hidden = !normalize(row.dataset.asqnSearch).includes(query); });
    const status = document.getElementById('asqn-filter-status');
    if (status) status.textContent = `${rows.filter(row => !row.hidden).length} / ${rows.length}`;
  });
  const cards = new Map(Array.from(document.querySelectorAll('[data-asqn-favorite-card]')).map(card => [card.dataset.asqnFavoriteCard, card]));
  const synchronizeFavorites = () => {
    let count = 0;
    rows.forEach(row => {
      const selected = row.querySelector('input[type="checkbox"]').checked;
      const card = cards.get(row.dataset.asqnId);
      if (card) card.hidden = !selected;
      if (selected) count++;
    });
    const counter = document.getElementById('asqn-selected-count');
    const empty = document.getElementById('asqn-selected-empty');
    if (counter) counter.textContent = count;
    if (empty) empty.hidden = count > 0;
  };
  rows.forEach(row => row.querySelector('input[type="checkbox"]').addEventListener('change', synchronizeFavorites));
  synchronizeFavorites();
  const dialog = document.getElementById('asqn-changelog');
  const trigger = document.querySelector('[data-asqn-changelog]');
  const nativeDialog = window.HTMLDialogElement && window.HTMLDialogElement.prototype;
  if (dialog && trigger && nativeDialog && typeof nativeDialog.showModal === 'function' && !dialog.dataset.asqnController) {
    dialog.dataset.asqnController = '1';
    trigger.addEventListener('click', event => {
      if (event.defaultPrevented) return;
      try {
        dialog.hidden = false;
        if (!dialog.open) nativeDialog.showModal.call(dialog);
        if (dialog.open) event.preventDefault();
        else dialog.hidden = true;
      } catch (_) { dialog.hidden = true; }
    }, true);
    dialog.querySelector('[data-asqn-close]').addEventListener('click', event => {
      try { nativeDialog.close.call(dialog); event.preventDefault(); }
      catch (_) { /* The native method=dialog form remains available. */ }
    });
    dialog.addEventListener('close', () => { dialog.hidden = true; trigger.focus(); });
    dialog.addEventListener('click', event => {
      if (event.target !== dialog) return;
      const box = dialog.getBoundingClientRect();
      if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) nativeDialog.close.call(dialog);
    });
  }
})();
