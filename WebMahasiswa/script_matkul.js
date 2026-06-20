document.addEventListener('DOMContentLoaded', loadData);

const mModal = new bootstrap.Modal(document.getElementById('matkulModal'));

function loadData() {
    fetch('api_matkul.php?action=list')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="5" class="text-center py-4">Belum ada data mata kuliah. Silakan tambah data!</td></tr>`;
            } else {
                data.forEach((matkul, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${matkul.kode_matkul}</td>
                            <td>${matkul.nama_matkul}</td>
                            <td><span class="sks-badge">${matkul.sks} SKS</span></td>
                            <td style="text-align:center">
                                <button class="btn-edit" onclick="siapkanEdit(${matkul.id})">Edit</button>
                                <button class="btn-delete" onclick="hapusData(${matkul.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tempat-data-matkul').innerHTML = html;
        })
        .catch(err => console.error("Error:", err));
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-book"></i> Tambah Mata Kuliah';
    document.getElementById('formMatkul').reset();
    document.getElementById('matkul_id').value = '';
    mModal.show();
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Mata Kuliah';
    fetch(`api_matkul.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('matkul_id').value = data.id;
            document.getElementById('kode_matkul').value = data.kode_matkul;
            document.getElementById('nama_matkul').value = data.nama_matkul;
            document.getElementById('sks').value = data.sks;
            mModal.show();
        });
}

function simpanData(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formMatkul'));
    fetch('api_matkul.php?action=save', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Data berhasil disimpan!');
                mModal.hide();
                loadData();
            } else {
                alert('Error: ' + res.message);
            }
        });
}

function hapusData(id) {
    if (confirm('Yakin hapus data ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('api_matkul.php?action=delete', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    alert('Data berhasil dihapus!');
                    loadData();
                }
            });
    }
}