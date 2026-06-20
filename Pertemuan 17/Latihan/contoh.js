// Contoh lengkap class, instance, inheritance
class Hewan {
    constructor(nama) {
        this.nama = nama;
    }
    
    bersuara() {
        console.log(`${this.nama} bersuara`);
    }
}

class Kucing extends Hewan {
    constructor(nama, warna) {
        super(nama);
        this.warna = warna;
    }
    
    bersuara() {
        console.log(`${this.nama} (${this.warna}) mengeong: Meong!`);
    }
}

// Membuat instance
const kucing1 = new Kucing("Kitty", "Putih");
const kucing2 = new Kucing("Tom", "Oren");

// Menampilkan hasil
function jalankanContoh() {
    kucing1.bersuara();
    kucing2.bersuara();
    console.log(kucing1 instanceof Kucing);
}