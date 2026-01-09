function load(type, el) {
    fetch(`api/posts.php?type=${type}`)
        .then(r => r.json())
        .then(data => {
            el.innerHTML = '';
            data.forEach(p => {
                el.innerHTML += `
                <div class="card">
                    <b>${p.nama_barang}</b>
                    <span class="badge ${type}">${type}</span>
                    <p>${p.deskripsi}</p>
                    <small>Kontak: ${p.kontak}</small>
                    ${p.foto ? `<br><img src="uploads/${p.foto}">` : ''}
                </div>
            `;
            });
        });
}

load('temuan', document.getElementById('temuan'));
load('kehilangan', document.getElementById('kehilangan'));
