/* Read-only enhancement; native list and editor remain owned by Advanced Scripts. */
(() => {
	'use strict';
	const config = window.ASQNSearch;
	const aside = document.getElementById('advanced-scripts-list');
	const wrap = aside && aside.querySelector(':scope > .app-list-wrap');
	const nativeList = wrap && wrap.querySelector(':scope > .list-container');
	if (!config || !wrap || !nativeList || document.getElementById('asqn-search')) return;
	const panel = document.createElement('section');
	panel.id = 'asqn-search';
	panel.setAttribute('aria-label', config.label);
	const label = document.createElement('label');
	label.htmlFor = 'asqn-search-query'; label.textContent = config.label;
	const controls = document.createElement('div'); controls.className = 'asqn-search-controls';
	const input = document.createElement('input');
	input.type = 'search'; input.id = 'asqn-search-query'; input.maxLength = 100;
	input.autocomplete = 'off'; input.setAttribute('aria-describedby', 'asqn-search-help');
	input.setAttribute('aria-controls', 'asqn-search-results');
	const clear = document.createElement('button'); clear.type = 'button';
	clear.className = 'button'; clear.textContent = config.clear; clear.hidden = true;
	controls.append(input, clear);
	const filters = document.createElement('div'); filters.className = 'asqn-search-filters';
	/** Build a labeled native filter. @param {string} id Control ID. @param {string} caption Label. @param {Object} options Value/text map. @returns {HTMLSelectElement} Filter. */
	function selectFilter(id, caption, options) {
		const holder = document.createElement('label'); holder.htmlFor = id; holder.textContent = caption;
		const select = document.createElement('select'); select.id = id;
		for (const [value, text] of Object.entries(options)) { const option = document.createElement('option'); option.value = value; option.textContent = text; select.append(option); }
		holder.append(select); filters.append(holder); return select;
	}
	const type = selectFilter('asqn-search-type', config.typeLabel, config.types);
	const state = selectFilter('asqn-search-state', config.statusLabel, config.states);
	const help = document.createElement('p'); help.id = 'asqn-search-help'; help.textContent = config.help;
	const status = document.createElement('p'); status.className = 'asqn-search-status';
	status.setAttribute('role', 'status'); status.setAttribute('aria-live', 'polite');
	const list = document.createElement('ul'); list.id = 'asqn-search-results'; list.hidden = true;
	panel.append(label, controls, filters, help, status, list); wrap.insertBefore(panel, nativeList);
	let timer; let controller; let generation = 0; let writing = false; let pendingFocus = null; let announcement = '';

	/** Restore native navigation without changing upstream state. */
	function restore() {
		aside.classList.remove('asqn-searching'); list.hidden = true;
		list.replaceChildren(); status.textContent = ''; panel.removeAttribute('aria-busy');
	}
	/** Clear query and invalidate responses, including already completed requests. */
	function reset() {
		input.value = ''; type.value = 'all'; state.value = 'all'; generation++; clearTimeout(timer);
		if (controller) controller.abort(); clear.hidden = true; restore(); input.focus();
	}
	/** Render fresh metadata for the newest query. @param {string} query Search words. @param {number} current Request generation. @returns {Promise<void>} */
	async function search(query, current) {
		controller = new AbortController();
		panel.setAttribute('aria-busy', 'true'); status.textContent = config.loading;
		try {
			const response = await fetch(config.url, {
				method: 'POST', credentials: 'same-origin', signal: controller.signal,
				body: new URLSearchParams({ action: 'asqn_search_scripts', nonce: config.nonce, query, type: type.value, state: state.value })
			});
			const result = await response.json();
			if (current !== generation) return;
			if (!response.ok || !result.success || !Array.isArray(result.data.items)) throw new Error('Search unavailable');
			const fragment = document.createDocumentFragment();
			for (const item of result.data.items) {
				const url = new URL(item.url, window.location.href);
				if (url.origin !== window.location.origin || !/^https?:$/.test(url.protocol)) continue;
				const row = document.createElement('li'); const link = document.createElement('a');
				link.href = url.href; link.textContent = item.title;
				const meta = document.createElement('span'); meta.textContent = [item.type, item.status, item.path].filter(Boolean).join(' · ');
				const star = document.createElement('button'); star.type = 'button'; star.className = 'asqn-search-star';
				star.dataset.id = String(item.id); star.disabled = writing;
				const selected = Boolean(item.favorite);
				star.textContent = selected ? '★' : '☆'; star.setAttribute('aria-pressed', String(selected));
				star.setAttribute('aria-label', `${selected ? config.removeFavorite : config.addFavorite}: ${item.title}`);
				star.title = selected ? config.removeFavorite : config.addFavorite;
				star.addEventListener('click', () => saveFavorite(star, item, !selected));
				const text = document.createElement('div'); text.append(link, meta);
				row.append(text, star); fragment.append(row);
			}
			list.replaceChildren(fragment); list.hidden = result.data.items.length === 0;
			aside.classList.add('asqn-searching');
			status.textContent = result.data.total ? config.count.replace('%d', String(result.data.total)) : config.empty;
			if (result.data.total > 50) status.textContent += ' ' + config.limited;
			if (announcement) { status.textContent = announcement + ' ' + status.textContent; announcement = ''; }
			if (pendingFocus !== null) { const star = list.querySelector(`[data-id="${Number(pendingFocus)}"]`); if (star) star.focus(); pendingFocus = null; }
		} catch (error) {
			if (current !== generation || error.name === 'AbortError') return;
			restore(); status.textContent = config.error;
		} finally {
			if (current === generation) panel.removeAttribute('aria-busy');
		}
	}
	/** Debounce reads and invalidate stale responses. @param {number} delay Debounce milliseconds. @param {boolean} preserveFocus Retain a pending favorite button focus. @returns {void} */
	function refresh(delay = 250, preserveFocus = false) {
		if (!preserveFocus) { pendingFocus = null; announcement = ''; }
		generation++; clearTimeout(timer); if (controller) controller.abort();
		const query = input.value.trim(); clear.hidden = !input.value && type.value === 'all' && state.value === 'all';
		restore();
		if (query || type.value !== 'all' || state.value !== 'all') { const current = generation; timer = setTimeout(() => search(query, current), delay); }
	}
	/** Save an explicit personal state. @param {HTMLButtonElement} button Trigger. @param {Object} item Search record. @param {boolean} selected Desired state. @returns {Promise<void>} */
	async function saveFavorite(button, item, selected) {
		if (writing) return;
		const focusButton = document.activeElement === button; const view = [input.value, type.value, state.value].join('\n');
		let failureMessage = config.favoriteError;
		writing = true; list.querySelectorAll('button').forEach(star => { star.disabled = true; });
		try {
			const response = await fetch(config.url, {
				method: 'POST', credentials: 'same-origin',
				body: new URLSearchParams({ action: 'asqn_search_favorite', nonce: config.favoriteNonce, id: String(item.id), selected: selected ? '1' : '0' })
			});
			const result = await response.json();
			if (!response.ok || !result.success) {
				if (result.data && typeof result.data.message === 'string') failureMessage = result.data.message;
				throw new Error('Favorite unavailable');
			}
			if (focusButton && view === [input.value, type.value, state.value].join('\n')) pendingFocus = item.id;
			announcement = config.favoriteSaved; refresh(0, true);
		} catch (_) { status.textContent = failureMessage; }
		finally { writing = false; list.querySelectorAll('button').forEach(star => { star.disabled = false; }); }
	}
	input.addEventListener('input', () => refresh());
	type.addEventListener('change', () => refresh(0)); state.addEventListener('change', () => refresh(0));

	panel.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') { event.preventDefault(); event.stopPropagation(); reset(); }
		if (event.key === 'Enter' && event.target === input) event.preventDefault();
		if (event.key === 'ArrowDown' && event.target === input) {
			const first = list.querySelector('a'); if (first) { event.preventDefault(); first.focus(); }
		}
	});
	clear.addEventListener('click', reset);
	if (window.location.hash === '#asqn-search-query') {
		/** Reveal the native list through its own toggle, then focus the new search. @returns {void} */
		const focusFromPalette = () => {
			const app = document.getElementById('advanced-scripts-app');
			if (app && app.classList.contains('list-hidden')) app.querySelector('[data-action="toggleScriptsList"]')?.click();
			input.focus();
		};
		if (document.readyState === 'complete') focusFromPalette();
		else window.addEventListener('load', focusFromPalette, { once: true });
	}
})();
