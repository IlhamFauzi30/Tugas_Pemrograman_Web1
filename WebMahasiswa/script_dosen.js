document.addEventListener('DOMContentLoaded', loadData);

const mModal = new bootstrap.Modal(document.getElementById('dosenModal'));

function loadData() {
    console.log("Memuat data dosen...");
    fetch('api_dosen.php?action=list')
        .then(response => {
            console.log("Response status:", response.status);
            return response.json();
        })
        .then(data => {
            console.log("Data diterima:", data);
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="5" class="text-center py-4">Belum ada data dosen. Silakan tambah data!</td></tr>`;
            } else {
                data.forEach((dosen, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${dosen.nidn}</td>
                            <td>${dosen.nama}</td>
                            <td>${dosen.alamat}</td>
                            <td style="text-align:center">
                                <button class="btn btn-warning btn-sm btn-edit" onclick="siapkanEdit(${dosen.id})">Edit</button>
                                <button class="btn btn-danger btn-sm btn-delete" onclick="hapusData(${dosen.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tempat-data-dosen').innerHTML = html;
        })
        .catch(err => {
            console.error("Gagal memuat data: ", err);
            document.getElementById('tempat-data-dosen').innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Error memuat data: ${err.message}</td></tr>`;
        });
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-plus"></i> Tambah Dosen';
    document.getElementById('formDosen').reset();
    document.getElementById('dosen_id').value = '';
    mModal.show();
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-user-edit"></i> Edit Dosen';
    document.getElementById('formDosen').reset();

    fetch(`api_dosen.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('dosen_id').value = data.id;
            document.getElementById('nidn').value = data.nidn;
            document.getElementById('nama').value = data.nama;
            document.getElementById('alamat').value = data.alamat;
            mModal.show();
        })
        .catch(err => console.error("Gagal mengambil data: ", err));
}

function simpanData(event) {
    event.preventDefault();

    const form = document.getElementById('formDosen');
    const formData = new FormData(form);

    fetch('api_dosen.php?action=save', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(res => {
        if (res.status === 'success') {
            alert('Data berhasil disimpan!');
            mModal.hide();
            loadData();
        } else {
            alert('Error: ' + res.message);
        }
    })
    .catch(err => console.error("Gagal mengirim data: ", err));
}

function hapusData(id) {
    if (confirm('Apakah Anda yakin ingin menghapus data dosen ini?')) {
        const formData = new FormData();
        formData.append('id', id);

        fetch('api_dosen.php?action=delete', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Data berhasil dihapus!');
                loadData();
            } else {
                alert('Error: ' + res.message);
            }
        })
        .catch(err => console.error("Gagal menghapus data: ", err));
    }
}