@php
    // Nilai awal tech tags: bisa berupa array (dari project) atau old('tech_tags') string/array
    $currentTags = [];
    if (old('tech_tags')) {
        $oldVal = old('tech_tags');
        $currentTags = is_array($oldVal) ? $oldVal : array_values(array_filter(array_map('trim', explode(',', (string) $oldVal))));
    } elseif (isset($project) && is_array($project->tech_tags)) {
        $currentTags = $project->tech_tags;
    }
@endphp

<div class="rounded-2xl border border-white/10 bg-slate-900/50 p-5 backdrop-blur-sm space-y-5" id="techPickerComponent">
    <!-- Header & Toggle Mode -->
    <div class="flex flex-wrap items-center justify-between gap-3">
        <div>
            <label class="block text-xs font-semibold text-white tracking-wide uppercase">
                Teknologi yang Digunakan (Tech Stack)
            </label>
            <p class="text-[11px] text-slate-400 mt-0.5">
                Pilih dari tech stack yang sudah ada di menu <span class="text-indigo-400 font-medium">Sertifikat & Skill</span>, atau buat baru secara otomatis beserta logonya.
            </p>
        </div>
        <button
            type="button"
            onclick="openQuickAddTechModal()"
            class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-3.5 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/20 transition-all hover:from-indigo-500 hover:to-blue-500 hover:scale-[1.02] active:scale-95"
        >
            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" />
            </svg>
            <span>+ Buat Tech Stack Baru</span>
        </button>
    </div>

    <!-- Hidden Input for Form Submission -->
    <input type="hidden" name="tech_tags" id="techTagsInput" value="{{ implode(', ', $currentTags) }}">

    <!-- Area 1: Selected Tech Badges (Yang Sudah Dipilih untuk Projek Ini) -->
    <div class="rounded-xl border border-white/10 bg-black/40 p-3.5">
        <div class="flex items-center justify-between mb-2">
            <span class="text-[11px] font-medium text-slate-400">
                Tech Stack Terpilih (<span id="selectedCount" class="text-indigo-400 font-bold font-mono">0</span>):
                <span class="text-[10px] text-slate-500 font-mono ml-1 hidden sm:inline">&#8212; Drag / geser badge untuk memindahkan urutan</span>
            </span>
            <button
                type="button"
                onclick="toggleManualTextMode()"
                class="text-[11px] text-slate-400 hover:text-indigo-300 underline cursor-pointer"
            >
                <span id="manualModeLabel">Edit Teks Manual</span>
            </button>
        </div>

        <!-- Badges Container -->
        <div id="selectedBadgesContainer" class="flex flex-wrap gap-2 min-h-[38px] items-center">
            <!-- Populated via JavaScript -->
        </div>

        <!-- Fallback Manual Text Input (Hidden by default, toggleable) -->
        <div id="manualTextContainer" class="hidden mt-3 pt-3 border-t border-white/10">
            <label class="block text-[11px] text-slate-400 mb-1">String Teks Pisahkan Koma:</label>
            <input
                type="text"
                id="manualTextInput"
                value="{{ implode(', ', $currentTags) }}"
                placeholder="Laravel, React, Inertia.js, Tailwind CSS, MySQL"
                class="h-10 w-full rounded-lg border border-white/10 bg-slate-900/90 px-3 text-xs font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
            >
        </div>
    </div>

    <!-- Area 2: Available Tech Stacks from Sertifikat & Skill -->
    <div class="space-y-3">
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="pointer-events-none absolute left-3 top-2.5 h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <input
                    type="text"
                    id="searchTechInput"
                    placeholder="Cari dari daftar tech stack yang ada..."
                    class="h-9 w-full rounded-xl border border-white/10 bg-slate-900/80 pl-9 pr-3 text-xs text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                >
            </div>
            <button
                type="button"
                id="filterAllBtn"
                onclick="resetTechFilter()"
                class="h-9 px-3 rounded-xl border border-white/10 bg-white/5 text-[11px] text-slate-300 hover:bg-white/10 transition-colors"
            >
                Reset Filter
            </button>
        </div>

        <!-- Chip Grid of Available Stacks -->
        <div
            id="availableChipsContainer"
            class="flex flex-wrap gap-2 max-h-48 overflow-y-auto pr-1 py-1 custom-scrollbar"
        >
            @forelse($availableTechStacks as $tech)
                <button
                    type="button"
                    data-title="{{ $tech->title }}"
                    data-icon="{{ $tech->image_or_icon ?? '' }}"
                    onclick="toggleTech('{{ addslashes($tech->title) }}', '{{ addslashes($tech->image_or_icon ?? '') }}')"
                    class="tech-chip group inline-flex cursor-pointer items-center gap-2 rounded-xl border px-3 py-1.5 text-xs font-medium transition-all duration-200 select-none border-white/10 bg-slate-900/80 text-slate-300 hover:border-white/25 hover:bg-slate-800 hover:text-white"
                >
                    <div class="flex h-4 w-4 shrink-0 items-center justify-center overflow-hidden rounded">
                        @if($tech->image_or_icon && (str_starts_with($tech->image_or_icon, '/') || str_starts_with($tech->image_or_icon, 'http')))
                            <img
                                src="{{ $tech->image_or_icon }}"
                                alt="{{ $tech->title }}"
                                class="h-full w-full object-contain"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"
                            >
                            <span class="hidden text-[9px] font-bold font-mono text-indigo-400">{{ substr($tech->title, 0, 2) }}</span>
                        @else
                            <span class="text-[9px] font-bold font-mono text-indigo-400">{{ substr($tech->title, 0, 2) }}</span>
                        @endif
                    </div>
                    <span>{{ $tech->title }}</span>
                    <span class="check-indicator hidden text-indigo-400 font-bold ml-0.5">✓</span>
                </button>
            @empty
                <div class="py-4 text-xs text-slate-500 font-mono">
                    Belum ada tech stack di database. Klik tombol "+ Buat Tech Stack Baru" di atas.
                </div>
            @endforelse
        </div>

        <p id="emptySearchMsg" class="hidden text-xs text-slate-400 font-mono py-2">
            Tidak ada teknologi yang cocok. <button type="button" onclick="openQuickAddWithSearch()" class="text-indigo-400 hover:underline font-semibold">Buat tech stack baru?</button>
        </p>
    </div>
</div>

<!-- Modal Quick-Add Tech Stack Baru (Otomatis Ambil Logo) -->
<div
    id="quickAddModal"
    class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/80 p-4 backdrop-blur-sm animate-fade-in"
>
    <div class="relative w-full max-w-lg rounded-2xl border border-white/15 bg-[#0e1424] p-6 shadow-2xl space-y-5">
        <!-- Modal Header -->
        <div class="flex items-center justify-between border-b border-white/10 pb-4">
            <div class="flex items-center gap-2.5">
                <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-400">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-display text-base font-bold text-white">Tambah Tech Stack Baru</h3>
                    <p class="text-[11px] text-slate-400">Nama dan logo SVG otomatis dicari dan disimpan ke database.</p>
                </div>
            </div>
            <button
                type="button"
                onclick="closeQuickAddTechModal()"
                class="rounded-lg p-1.5 text-slate-400 hover:bg-white/10 hover:text-white transition-colors"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Modal Body -->
        <div class="space-y-4">
            <!-- Input Nama Teknologi -->
            <div>
                <label class="block text-xs font-semibold text-slate-200 mb-1.5">
                    Nama Teknologi <span class="text-rose-400">*</span>
                </label>
                <input
                    type="text"
                    id="newTechTitleInput"
                    placeholder="Contoh: Capacitor, Docker, TypeScript, Android Studio, Prisma..."
                    class="h-11 w-full rounded-xl border border-white/15 bg-slate-900/90 px-4 text-sm text-white placeholder:text-slate-500 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 focus:outline-none"
                    autocomplete="off"
                >
            </div>

            <!-- Logo Selector / Auto-Preview -->
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label class="text-xs font-semibold text-slate-200">
                        Pratinjau Logo Otomatis
                    </label>
                    <span id="logoLookupStatus" class="text-[11px] text-slate-400 font-mono">Ketik nama untuk mencari logo...</span>
                </div>

                <!-- Preview Area -->
                <div class="rounded-xl border border-white/10 bg-black/40 p-3 min-h-[72px] flex items-center justify-center">
                    <div id="iconPreviewGrid" class="flex flex-wrap items-center gap-3 justify-center">
                        <span class="text-xs text-slate-500 font-mono">Logo resmi akan muncul otomatis di sini</span>
                    </div>
                </div>
                <input type="hidden" id="selectedIconIdentifier" value="">
            </div>

            <!-- Opsi Kategori / Bidang -->
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Level / Bidang</label>
                    <select
                        id="newTechLevelInput"
                        class="h-10 w-full rounded-xl border border-white/10 bg-slate-900 px-3 text-xs text-white focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="Tools">Tools</option>
                        <option value="Frontend">Frontend</option>
                        <option value="Backend">Backend</option>
                        <option value="Database">Database</option>
                        <option value="Deployment">Deployment</option>
                        <option value="Mobile">Mobile</option>
                        <option value="Tech Stack">Tech Stack</option>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-300 mb-1">Metode Logo</label>
                    <select
                        id="logoMethodSelect"
                        onchange="toggleLogoMethod(this.value)"
                        class="h-10 w-full rounded-xl border border-white/10 bg-slate-900 px-3 text-xs text-white focus:border-indigo-500 focus:outline-none"
                    >
                        <option value="auto">Otomatis (Iconify / Devicon)</option>
                        <option value="svg">Tempel SVG Manual</option>
                    </select>
                </div>
            </div>

            <!-- Manual SVG Input (Hidden by default) -->
            <div id="manualSvgGroup" class="hidden space-y-1">
                <label class="block text-xs font-semibold text-slate-300">Tempel Kode &lt;svg&gt;...</label>
                <textarea
                    id="manualSvgInput"
                    rows="2"
                    placeholder="<svg viewBox='0 0 128 128'>...</svg>"
                    class="w-full rounded-xl border border-white/10 bg-slate-900 p-2 text-xs font-mono text-white placeholder:text-slate-500 focus:border-indigo-500 focus:outline-none"
                ></textarea>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 border-t border-white/10 pt-4">
            <button
                type="button"
                onclick="closeQuickAddTechModal()"
                class="rounded-xl border border-white/10 px-4 py-2 text-xs font-semibold text-slate-300 hover:bg-white/5 hover:text-white transition-colors"
            >
                Batal
            </button>
            <button
                type="button"
                id="submitQuickAddBtn"
                onclick="submitQuickAddTech()"
                class="inline-flex cursor-pointer items-center gap-2 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-lg shadow-indigo-600/20 hover:from-indigo-500 hover:to-blue-500 transition-all disabled:opacity-50"
            >
                <svg id="submitLoadingIcon" class="hidden h-3.5 w-3.5 animate-spin" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <span id="submitBtnText">Simpan & Pasang ke Projek</span>
            </button>
        </div>
    </div>
</div>

<script>
    // State pengelolaan tech tags berbasis array urut (ordered list)
    let selectedTechList = []; // [ { title, icon } ]
    let lookupDebounceTimer = null;
    let draggedIndex = null;

    // Inisialisasi awal dari hidden input
    document.addEventListener('DOMContentLoaded', () => {
        initTechPicker();
    });

    function initTechPicker() {
        const rawVal = document.getElementById('techTagsInput').value || '';
        const initialTags = rawVal.split(',').map(t => t.trim()).filter(Boolean);

        // Ambil icon data dari chip yang sudah ada di HTML
        const availableButtons = document.querySelectorAll('.tech-chip');
        const iconCache = new Map();
        availableButtons.forEach(btn => {
            const title = btn.getAttribute('data-title');
            const icon = btn.getAttribute('data-icon');
            if (title) {
                iconCache.set(title.toLowerCase(), { title, icon });
            }
        });

        selectedTechList = [];
        initialTags.forEach(tag => {
            const cached = iconCache.get(tag.toLowerCase());
            selectedTechList.push({
                title: cached ? cached.title : tag,
                icon: cached ? cached.icon : ''
            });
        });

        syncHiddenInput();
        renderSelectedBadges();
        updateChipStates();

        // Listen search input
        const searchInput = document.getElementById('searchTechInput');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => filterTechChips(e.target.value));
        }

        // Listen manual text input
        const manualInput = document.getElementById('manualTextInput');
        if (manualInput) {
            manualInput.addEventListener('input', (e) => {
                const parsed = e.target.value.split(',').map(t => t.trim()).filter(Boolean);
                selectedTechList = [];
                parsed.forEach(tag => {
                    const cached = iconCache.get(tag.toLowerCase());
                    selectedTechList.push({
                        title: cached ? cached.title : tag,
                        icon: cached ? cached.icon : ''
                    });
                });
                syncHiddenInput();
                renderSelectedBadges();
                updateChipStates();
            });
        }

        // Listen modal title input for auto logo lookup
        const newTitleInput = document.getElementById('newTechTitleInput');
        if (newTitleInput) {
            newTitleInput.addEventListener('input', (e) => {
                clearTimeout(lookupDebounceTimer);
                const query = e.target.value.trim();
                if (query.length >= 2) {
                    document.getElementById('logoLookupStatus').textContent = 'Mencari logo resmi...';
                    lookupDebounceTimer = setTimeout(() => fetchIconPreviews(query), 350);
                } else {
                    document.getElementById('logoLookupStatus').textContent = 'Ketik nama untuk mencari logo...';
                    document.getElementById('iconPreviewGrid').innerHTML = '<span class="text-xs text-slate-500 font-mono">Logo resmi akan muncul otomatis di sini</span>';
                    document.getElementById('selectedIconIdentifier').value = '';
                }
            });
        }
    }

    function toggleTech(title, icon = '') {
        const idx = selectedTechList.findIndex(item => item.title.toLowerCase() === title.toLowerCase());
        if (idx >= 0) {
            selectedTechList.splice(idx, 1);
        } else {
            selectedTechList.push({ title, icon });
        }

        syncHiddenInput();
        renderSelectedBadges();
        updateChipStates();
    }

    function removeSelectedTech(title) {
        const idx = selectedTechList.findIndex(item => item.title.toLowerCase() === title.toLowerCase());
        if (idx >= 0) {
            selectedTechList.splice(idx, 1);
            syncHiddenInput();
            renderSelectedBadges();
            updateChipStates();
        }
    }

    function moveTech(index, direction) {
        const target = index + direction;
        if (target < 0 || target >= selectedTechList.length) return;
        const item = selectedTechList.splice(index, 1)[0];
        selectedTechList.splice(target, 0, item);
        syncHiddenInput();
        renderSelectedBadges();
    }

    function syncHiddenInput() {
        const titles = selectedTechList.map(item => item.title);
        const strVal = titles.join(', ');
        document.getElementById('techTagsInput').value = strVal;

        const manualInput = document.getElementById('manualTextInput');
        if (manualInput) {
            manualInput.value = strVal;
        }

        const countEl = document.getElementById('selectedCount');
        if (countEl) {
            countEl.textContent = titles.length;
        }
    }

    function renderSelectedBadges() {
        const container = document.getElementById('selectedBadgesContainer');
        if (!container) return;

        if (selectedTechList.length === 0) {
            container.innerHTML = '<span class="text-xs text-slate-500 font-mono italic">Belum ada teknologi yang dipilih. Pilih dari daftar di bawah atau klik "+ Buat Tech Stack Baru".</span>';
            return;
        }

        let html = '';
        selectedTechList.forEach((item, idx) => {
            const hasImg = item.icon && (item.icon.startsWith('/') || item.icon.startsWith('http'));
            html += `
                <div
                    draggable="true"
                    data-index="${idx}"
                    class="drag-badge group/badge inline-flex cursor-grab active:cursor-grabbing items-center gap-1.5 rounded-xl border border-indigo-500/30 bg-indigo-500/15 pl-2 pr-1.5 py-1 text-xs font-medium text-indigo-200 shadow-sm transition-all duration-150 hover:bg-indigo-500/25 hover:border-indigo-400 select-none"
                    title="Drag / geser badge untuk memindahkan urutan"
                >
                    <!-- Grip Handle -->
                    <span class="text-indigo-400/60 group-hover/badge:text-indigo-300 transition-colors cursor-grab active:cursor-grabbing">
                        <svg class="h-3.5 w-3.5 pointer-events-none" viewBox="0 0 24 24" fill="currentColor">
                            <circle cx="9" cy="6" r="1.5"/><circle cx="15" cy="6" r="1.5"/>
                            <circle cx="9" cy="12" r="1.5"/><circle cx="15" cy="12" r="1.5"/>
                            <circle cx="9" cy="18" r="1.5"/><circle cx="15" cy="18" r="1.5"/>
                        </svg>
                    </span>

                    <!-- Logo -->
                    ${hasImg
                        ? `<img src="${item.icon}" alt="${item.title}" class="h-3.5 w-3.5 object-contain rounded pointer-events-none" onerror="this.style.display='none'">`
                        : `<span class="flex h-3.5 w-3.5 items-center justify-center rounded bg-indigo-600/50 text-[8px] font-bold font-mono text-white pointer-events-none">${item.title.substring(0, 2).toUpperCase()}</span>`
                    }

                    <!-- Title -->
                    <span class="pointer-events-none font-medium">${item.title}</span>

                    <!-- Quick Arrow Buttons on hover -->
                    <div class="hidden sm:group-hover/badge:inline-flex items-center gap-0.5 ml-1">
                        ${idx > 0 ? `<button type="button" onclick="moveTech(${idx}, -1)" class="p-0.5 text-[10px] text-indigo-300 hover:text-white" title="Geser ke kiri">&#9664;</button>` : ''}
                        ${idx < selectedTechList.length - 1 ? `<button type="button" onclick="moveTech(${idx}, 1)" class="p-0.5 text-[10px] text-indigo-300 hover:text-white" title="Geser ke kanan">&#9654;</button>` : ''}
                    </div>

                    <!-- Remove Button -->
                    <button
                        type="button"
                        onclick="removeSelectedTech('${item.title.replace(/'/g, "\\'")}')"
                        class="flex h-4 w-4 items-center justify-center rounded-full hover:bg-rose-500/30 text-indigo-300 hover:text-rose-200 transition-colors ml-0.5"
                        title="Hapus"
                    >
                        &times;
                    </button>
                </div>
            `;
        });

        container.innerHTML = html;
        bindDragAndDropEvents();
    }

    function bindDragAndDropEvents() {
        const badges = document.querySelectorAll('.drag-badge');
        badges.forEach(badge => {
            badge.addEventListener('dragstart', handleDragStart);
            badge.addEventListener('dragover', handleDragOver);
            badge.addEventListener('dragleave', handleDragLeave);
            badge.addEventListener('drop', handleDrop);
            badge.addEventListener('dragend', handleDragEnd);
        });
    }

    function handleDragStart(e) {
        draggedIndex = Number(this.getAttribute('data-index'));
        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', draggedIndex);
        this.classList.add('opacity-40', 'scale-95', 'ring-2', 'ring-indigo-400');
    }

    function handleDragOver(e) {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';
        this.classList.add('border-indigo-400', 'bg-indigo-500/40');
    }

    function handleDragLeave(e) {
        this.classList.remove('border-indigo-400', 'bg-indigo-500/40');
    }

    function handleDrop(e) {
        e.preventDefault();
        this.classList.remove('border-indigo-400', 'bg-indigo-500/40');
        const targetIndex = Number(this.getAttribute('data-index'));
        if (draggedIndex !== null && draggedIndex !== targetIndex) {
            const itemToMove = selectedTechList.splice(draggedIndex, 1)[0];
            selectedTechList.splice(targetIndex, 0, itemToMove);
            syncHiddenInput();
            renderSelectedBadges();
        }
    }

    function handleDragEnd(e) {
        this.classList.remove('opacity-40', 'scale-95', 'ring-2', 'ring-indigo-400');
        draggedIndex = null;
    }

    function updateChipStates() {
        const chips = document.querySelectorAll('.tech-chip');
        chips.forEach(chip => {
            const title = chip.getAttribute('data-title');
            const isSelected = selectedTechList.some(item => item.title.toLowerCase() === title.toLowerCase());
            const check = chip.querySelector('.check-indicator');

            if (isSelected) {
                chip.classList.add('border-indigo-500', 'bg-indigo-600/25', 'text-white', 'shadow-sm', 'shadow-indigo-500/20');
                chip.classList.remove('border-white/10', 'bg-slate-900/80', 'text-slate-300');
                if (check) check.classList.remove('hidden');
            } else {
                chip.classList.remove('border-indigo-500', 'bg-indigo-600/25', 'text-white', 'shadow-sm', 'shadow-indigo-500/20');
                chip.classList.add('border-white/10', 'bg-slate-900/80', 'text-slate-300');
                if (check) check.classList.add('hidden');
            }
        });
    }

    function filterTechChips(query) {
        const q = query.trim().toLowerCase();
        const chips = document.querySelectorAll('.tech-chip');
        let visibleCount = 0;

        chips.forEach(chip => {
            const title = (chip.getAttribute('data-title') || '').toLowerCase();
            if (!q || title.includes(q)) {
                chip.classList.remove('hidden');
                visibleCount++;
            } else {
                chip.classList.add('hidden');
            }
        });

        const emptyMsg = document.getElementById('emptySearchMsg');
        if (emptyMsg) {
            emptyMsg.classList.toggle('hidden', visibleCount > 0);
        }
    }

    function resetTechFilter() {
        const searchInput = document.getElementById('searchTechInput');
        if (searchInput) {
            searchInput.value = '';
            filterTechChips('');
        }
    }

    function toggleManualTextMode() {
        const container = document.getElementById('manualTextContainer');
        const label = document.getElementById('manualModeLabel');
        if (container.classList.contains('hidden')) {
            container.classList.remove('hidden');
            label.textContent = 'Sembunyikan Teks Manual';
        } else {
            container.classList.add('hidden');
            label.textContent = 'Edit Teks Manual';
        }
    }

    // Modal Quick Add
    function openQuickAddTechModal() {
        const modal = document.getElementById('quickAddModal');
        modal.classList.remove('hidden');
        document.getElementById('newTechTitleInput').focus();
    }

    function openQuickAddWithSearch() {
        const searchVal = document.getElementById('searchTechInput').value.trim();
        openQuickAddTechModal();
        if (searchVal) {
            const input = document.getElementById('newTechTitleInput');
            input.value = searchVal;
            input.dispatchEvent(new Event('input'));
        }
    }

    function closeQuickAddTechModal() {
        document.getElementById('quickAddModal').classList.add('hidden');
    }

    function toggleLogoMethod(val) {
        document.getElementById('manualSvgGroup').classList.toggle('hidden', val !== 'svg');
    }

    async function fetchIconPreviews(query) {
        const statusEl = document.getElementById('logoLookupStatus');
        const grid = document.getElementById('iconPreviewGrid');

        try {
            const res = await fetch(`{{ route('admin.credentials.lookup-icon') }}?query=${encodeURIComponent(query)}`);
            const data = await res.json();

            if (!data.icons || data.icons.length === 0) {
                statusEl.textContent = 'Tidak ada logo resmi otomatis (akan pakai inisial).';
                grid.innerHTML = '<span class="text-xs text-amber-300 font-mono">Logo tidak ditemukan di Devicon/Iconify. Anda bisa tetap menyimpannya atau tempel SVG manual.</span>';
                document.getElementById('selectedIconIdentifier').value = '';
                return;
            }

            statusEl.textContent = `${data.icons.length} logo ditemukan. Klik untuk memilih:`;
            grid.innerHTML = data.icons.map((item, idx) => `
                <div
                    onclick="selectPreviewIcon('${item.identifier}', this)"
                    class="logo-preview-card cursor-pointer flex flex-col items-center justify-center p-2 rounded-xl border ${idx === 0 ? 'border-indigo-400 bg-indigo-500/20 shadow-md ring-2 ring-indigo-500/50' : 'border-white/10 bg-slate-900/80 hover:border-white/30'} transition-all hover:scale-105"
                    data-id="${item.identifier}"
                    title="${item.title} (${item.collection})"
                >
                    <img src="${item.url}" alt="${item.title}" class="h-7 w-7 object-contain mb-1">
                    <span class="text-[9px] font-mono text-slate-300 max-w-[60px] truncate text-center">${item.collection}</span>
                </div>
            `).join('');

            // Pilih logo pertama secara default
            selectPreviewIcon(data.icons[0].identifier, grid.firstElementChild);
        } catch (err) {
            statusEl.textContent = 'Gagal menghubungi server pencari logo.';
        }
    }

    function selectPreviewIcon(identifier, cardEl) {
        document.getElementById('selectedIconIdentifier').value = identifier;
        document.querySelectorAll('.logo-preview-card').forEach(card => {
            card.classList.remove('border-indigo-400', 'bg-indigo-500/20', 'shadow-md', 'ring-2', 'ring-indigo-500/50');
            card.classList.add('border-white/10', 'bg-slate-900/80');
        });
        if (cardEl) {
            cardEl.classList.add('border-indigo-400', 'bg-indigo-500/20', 'shadow-md', 'ring-2', 'ring-indigo-500/50');
            cardEl.classList.remove('border-white/10', 'bg-slate-900/80');
        }
    }

    async function submitQuickAddTech() {
        const titleInput = document.getElementById('newTechTitleInput');
        const title = titleInput.value.trim();
        if (!title) {
            alert('Silakan masukkan nama teknologi.');
            titleInput.focus();
            return;
        }

        const btn = document.getElementById('submitQuickAddBtn');
        const loadingIcon = document.getElementById('submitLoadingIcon');
        const btnText = document.getElementById('submitBtnText');

        btn.disabled = true;
        loadingIcon.classList.remove('hidden');
        btnText.textContent = 'Menyimpan...';

        const method = document.getElementById('logoMethodSelect').value;
        let imageOrIcon = '';
        if (method === 'svg') {
            imageOrIcon = document.getElementById('manualSvgInput').value.trim();
        } else {
            imageOrIcon = document.getElementById('selectedIconIdentifier').value;
        }

        const payload = {
            title: title,
            issuer_or_level: document.getElementById('newTechLevelInput').value,
            image_or_icon: imageOrIcon,
            _token: '{{ csrf_token() }}'
        };

        try {
            const resp = await fetch('{{ route('admin.credentials.quick-tech-stack') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(payload)
            });

            const result = await resp.json();

            if (!resp.ok) {
                throw new Error(result.message || 'Gagal menyimpan tech stack');
            }

            const item = result.item;

            // 1. Tambahkan tombol chip baru ke available list jika belum ada di DOM
            let existingChip = document.querySelector(`.tech-chip[data-title="${item.title}"]`);
            if (!existingChip) {
                const chipsContainer = document.getElementById('availableChipsContainer');
                const newChipBtn = document.createElement('button');
                newChipBtn.type = 'button';
                newChipBtn.setAttribute('data-title', item.title);
                newChipBtn.setAttribute('data-icon', item.image_or_icon || '');
                newChipBtn.className = 'tech-chip group inline-flex cursor-pointer items-center gap-2 rounded-xl border px-3 py-1.5 text-xs font-medium transition-all duration-200 select-none border-white/10 bg-slate-900/80 text-slate-300 hover:border-white/25 hover:bg-slate-800 hover:text-white';
                newChipBtn.onclick = () => toggleTech(item.title, item.image_or_icon || '');

                const hasImg = item.image_or_icon && (item.image_or_icon.startsWith('/') || item.image_or_icon.startsWith('http'));
                newChipBtn.innerHTML = `
                    <div class="flex h-4 w-4 shrink-0 items-center justify-center overflow-hidden rounded">
                        ${hasImg
                            ? `<img src="${item.image_or_icon}" alt="${item.title}" class="h-full w-full object-contain" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"><span class="hidden text-[9px] font-bold font-mono text-indigo-400">${item.title.substring(0, 2)}</span>`
                            : `<span class="text-[9px] font-bold font-mono text-indigo-400">${item.title.substring(0, 2)}</span>`
                        }
                    </div>
                    <span>${item.title}</span>
                    <span class="check-indicator hidden text-indigo-400 font-bold ml-0.5">✓</span>
                `;

                chipsContainer.prepend(newChipBtn);
            }

            // 2. Tandai langsung sebagai TERPILIH untuk projek ini
            toggleTech(item.title, item.image_or_icon || '');

            // 3. Reset form modal & tutup
            titleInput.value = '';
            document.getElementById('manualSvgInput').value = '';
            document.getElementById('selectedIconIdentifier').value = '';
            document.getElementById('iconPreviewGrid').innerHTML = '<span class="text-xs text-slate-500 font-mono">Logo resmi akan muncul otomatis di sini</span>';
            closeQuickAddTechModal();

            // 4. Alert sukses
            const toast = document.createElement('div');
            toast.className = 'fixed bottom-6 right-6 z-50 flex items-center gap-3 rounded-2xl border border-emerald-500/30 bg-emerald-950/90 px-4 py-3 text-xs font-medium text-emerald-200 shadow-2xl backdrop-blur-md animate-fade-in';
            toast.innerHTML = `
                <svg class="h-4 w-4 text-emerald-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <span>${result.message}</span>
            `;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 4000);

        } catch (err) {
            alert(err.message || 'Terjadi kesalahan saat menyimpan.');
        } finally {
            btn.disabled = false;
            loadingIcon.classList.add('hidden');
            btnText.textContent = 'Simpan & Pasang ke Projek';
        }
    }
</script>

