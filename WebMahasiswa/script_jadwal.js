document.addEventListener('DOMContentLoaded', () => {
    loadData();
    loadDosenList();
    loadMatkulList();
});

const mModal = new bootstrap.Modal(document.getElementById('jadwalModal'));

function loadData() {
    fetch('api_jadwal.php?action=list')
        .then(response => response.json())
        .then(data => {
            let html = '';
            if (data.length === 0) {
                html = `<tr><td colspan="7" class="text-center py-4">Belum ada data jadwal. Silakan tambah data!</td></tr>`;
            } else {
                data.forEach((jadwal, index) => {
                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${jadwal.nama_dosen}</td>
                            <td>${jadwal.nama_matkul}</td>
                            <td><span class="sks-badge">${jadwal.sks} SKS</span></td>
                            <td><span class="waktu-badge"><i class="fas fa-clock"></i> ${jadwal.waktu}</span></td>
                            <td><span class="ruang-badge"><i class="fas fa-door-open"></i> ${jadwal.ruang}</span></td>
                            <td style="text-align:center">
                                <button class="btn-edit" onclick="siapkanEdit(${jadwal.id})">Edit</button>
                                <button class="btn-delete" onclick="hapusData(${jadwal.id})">Hapus</button>
                            </td>
                        </tr>
                    `;
                });
            }
            document.getElementById('tempat-data-jadwal').innerHTML = html;
        })
        .catch(err => console.error("Error:", err));
}

function loadDosenList() {
    fetch('api_jadwal.php?action=get_dosen_list')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('id_dosen');
            select.innerHTML = '<option value="">-- Pilih Dosen --</option>';
            data.forEach(dosen => {
                select.innerHTML += `<option value="${dosen.id}">${dosen.nama}</option>`;
            });
        });
}

function loadMatkulList() {
    fetch('api_jadwal.php?action=get_matkul_list')
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('id_matkul');
            select.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
            data.forEach(matkul => {
                select.innerHTML += `<option value="${matkul.id}">${matkul.nama_matkul}</option>`;
            });
        });
}

function siapkanTambah() {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-calendar-plus"></i> Tambah Jadwal';
    document.getElementById('formJadwal').reset();
    document.getElementById('jadwal_id').value = '';
    mModal.show();
}

function siapkanEdit(id) {
    document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Jadwal';
    fetch(`api_jadwal.php?action=get_single&id=${id}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('jadwal_id').value = data.id;
            document.getElementById('id_dosen').value = data.id_dosen;
            document.getElementById('id_matkul').value = data.id_matkul;
            document.getElementById('waktu').value = data.waktu;
            document.getElementById('ruang').value = data.ruang;
            mModal.show();
        });
}

function simpanData(event) {
    event.preventDefault();
    const formData = new FormData(document.getElementById('formJadwal'));
    fetch('api_jadwal.php?action=save', { method: 'POST', body: formData })
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
    if (confirm('Yakin hapus jadwal ini?')) {
        const formData = new FormData();
        formData.append('id', id);
        fetch('api_jadwal.php?action=delete', { method: 'POST', body: formData })
            .then(response => response.json())
            .then(res => {
                if (res.status === 'success') {
                    alert('Data berhasil dihapus!');
                    loadData();
                }
            });
    }
}