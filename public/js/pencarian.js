// Pencarian & saran judul publikasi interaktif
(function () {
    var input = document.getElementById('search');
    var hint = document.getElementById('txtHint');
    var clearBtn = document.getElementById('searchClearBtn');
    var tableBody = document.getElementById('tbodyPublikasi');
    if (!input || !hint) return;

    var rows = tableBody ? Array.from(tableBody.querySelectorAll('tr.publikasi-row')) : [];
    var debounceTimer = null;

    function getNoMatchRow() {
        var existing = document.getElementById('noMatchRow');
        if (existing) return existing;
        var tr = document.createElement('tr');
        tr.id = 'noMatchRow';
        tr.className = 'no-match-row';
        tr.innerHTML = '<td colspan="6" style="padding: 24px; text-align: center; color: #666; font-style: italic;">Tidak ada publikasi yang cocok dengan pencarian Anda.</td>';
        return tr;
    }

    // Filter baris tabel secara real-time
    function filterTable(keyword) {
        if (!tableBody || rows.length === 0) return;
        var q = keyword.trim().toLowerCase();
        var matchCount = 0;

        rows.forEach(function (row) {
            var judul = row.getAttribute('data-judul') || '';
            var abstraksi = row.getAttribute('data-abstraksi') || '';
            var text = row.textContent.toLowerCase();

            if (q === '' || judul.includes(q) || abstraksi.includes(q) || text.includes(q)) {
                row.style.display = '';
                matchCount++;
            } else {
                row.style.display = 'none';
            }
        });

        var noMatch = getNoMatchRow();
        if (matchCount === 0 && q !== '') {
            if (!tableBody.contains(noMatch)) {
                tableBody.appendChild(noMatch);
            }
        } else {
            if (tableBody.contains(noMatch)) {
                tableBody.removeChild(noMatch);
            }
        }
    }

    function escapeHtml(text) {
        var div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Ambil saran judul dari backend API
    function fetchSuggestions(keyword) {
        var q = keyword.trim();
        if (q.length === 0) {
            hint.innerHTML = '';
            hint.style.display = 'none';
            return;
        }

        fetch(input.dataset.url + '?keyword=' + encodeURIComponent(q), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                hint.innerHTML = '';

                if (!Array.isArray(data) || data.length === 0) {
                    var emptyDiv = document.createElement('div');
                    emptyDiv.className = 'hint-empty';
                    emptyDiv.innerHTML = '<svg class="hint-item-icon" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg><span>Tidak ada publikasi yang ditemukan.</span>';
                    hint.appendChild(emptyDiv);
                    hint.style.display = 'block';
                    return;
                }

                var headerDiv = document.createElement('div');
                headerDiv.className = 'hint-header';
                headerDiv.textContent = 'Saran Judul Publikasi (' + data.length + '):';
                hint.appendChild(headerDiv);

                var listDiv = document.createElement('div');
                listDiv.className = 'hint-list';

                data.forEach(function (judul) {
                    var item = document.createElement('div');
                    item.className = 'hint-item';
                    item.innerHTML = '<svg class="hint-item-icon" viewBox="0 0 20 20" fill="currentColor"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v10A7.969 7.969 0 015.5 14c1.669 0 3.218.51 4.5 1.385A7.962 7.962 0 0114.5 14c1.255 0 2.443.29 3.5.804v-10A7.968 7.968 0 0014.5 4c-1.255 0-2.443.29-3.5.804V12a1 1 0 11-2 0V4.804z"/></svg><span class="hint-item-text">' + escapeHtml(judul) + '</span>';

                    item.addEventListener('click', function () {
                        input.value = judul;
                        filterTable(judul);
                        if (clearBtn) clearBtn.style.display = 'inline-block';
                        hint.innerHTML = '';
                        hint.style.display = 'none';
                    });

                    listDiv.appendChild(item);
                });

                hint.appendChild(listDiv);
                hint.style.display = 'block';
            })
            .catch(function () {
                hint.innerHTML = '<div class="hint-empty">Server error, coba lagi.</div>';
                hint.style.display = 'block';
            });
    }

    // Input typing event
    input.addEventListener('input', function () {
        var val = input.value;
        if (clearBtn) {
            clearBtn.style.display = val.length > 0 ? 'inline-block' : 'none';
        }

        filterTable(val);

        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            fetchSuggestions(val);
        }, 160);
    });

    // Clear button event
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            input.value = '';
            clearBtn.style.display = 'none';
            hint.innerHTML = '';
            hint.style.display = 'none';
            filterTable('');
            input.focus();
        });
    }

    // Klik di luar search-container menutup hint
    document.addEventListener('click', function (e) {
        var container = input.closest('.search-container');
        if (container && !container.contains(e.target)) {
            hint.style.display = 'none';
        }
    });

    // Buka kembali jika input difokuskan dan memiliki teks
    input.addEventListener('focus', function () {
        if (input.value.trim().length > 0 && hint.children.length > 0) {
            hint.style.display = 'block';
        }
    });

    // Tekan tombol Escape untuk menutup dropdown hint
    input.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            hint.style.display = 'none';
        }
    });
})();
