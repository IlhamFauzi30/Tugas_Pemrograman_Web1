document.addEventListener('DOMContentLoaded', loadData);

const mModal = new bootstrap.Modal(document.getElementById('mahasiswaModal'));

function loadData() {
    fetch('api.php?action=list')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = '<tr><td colspan="6" class="text-center">Belum ada data</td></tr>';
            } else {
                data.forEach((mhs, index) => {
                    html += `
                        <tr>
                            <td class="ps-3">${index + 1}</td>
                            <td>${mhs.nim}</td>
                            <td>${mhs.nama}</td>
                            <td>${mhs.jurusan}</td>
                            <td>${mhs.email}</td>
                            <td class="text-center">
                                <button class="btn btn-warning btn-sm" onclick="siapkanEdit(${mhs.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="hapusData(${mhs.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tempat-data-mahasiswa').innerHTML = html;
        });
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerText = 'Tambah Mahasiswa';
    document.getElementById('formMahasiswa').reset();
    document.getElementById('mahasiswa_id').value = '';
    mModal.show();
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerText = 'Edit Mahasiswa';
    fetch(`api.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('mahasiswa_id').value = data.id;
            document.getElementById('nim').value = data.nim;
            document.getElementById('nama').value = data.nama;
            document.getElementById('jurusan').value = data.jurusan;
            document.getElementById('email').value = data.email;
            mModal.show();
        });
}

function simpanData(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formMahasiswa'));
    fetch('api.php?action=save', { method: 'POST', body: formData })
        .then(response => response.json())
        .then(res => {
            if (res.status === 'success') {
                alert('Data tersimpan!');
                mModal.hide();
                loadData();
            }
        });
}

function hapusData(id) {
    if (confirm('Yakin hapus data ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('api.php?action=delete', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    alert('Data terhapus!');
                    loadData();
                }
            });
    }
}