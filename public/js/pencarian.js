// Saran judul publikasi (versi Laravel dari page11A_suggestion.js)
(function () {
    var input = document.getElementById('search');
    var hint = document.getElementById('txtHint');
    if (!input || !hint) return;

    input.addEventListener('keyup', function () {
        var kata = input.value;

        if (kata.length === 0) {
            hint.textContent = '';
            return;
        }

        fetch(input.dataset.url + '?keyword=' + encodeURIComponent(kata), {
            headers: { 'Accept': 'application/json' }
        })
            .then(function (res) {
                if (!res.ok) throw new Error('HTTP ' + res.status);
                return res.json();
            })
            .then(function (data) {
                hint.textContent = '';
                if (data.length === 0) {
                    hint.textContent = 'Tidak ada publikasi yang ditemukan.';
                    return;
                }
                data.forEach(function (judul, i) {
                    if (i > 0) hint.appendChild(document.createElement('br'));
                    hint.appendChild(document.createTextNode(judul));
                });
            })
            .catch(function () {
                hint.textContent = 'Server error, coba lagi.';
            });
    });
})();
