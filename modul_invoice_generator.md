Rancangan Modul: System-Generated Invoice

1. Deskripsi ModulModul ini berfungsi untuk mengotomatisasi penerbitan invoice pelunasan berdasarkan data SPK (Surat Perintah Kerja) yang tersimpan di dalam sistem. Sistem akan menghitung sisa tagihan secara otomatis setelah dikurangi termin uang muka (DP) yang telah dibayarkan sebelumnya.

2. Arsitektur Data (Schema)
Berikut adalah referensi kolom utama yang diperlukan untuk menghasilkan invoice pelunasan:

Field,Tipe Data,Deskripsi
invoice_id,String (Unique),Format: INV/YYYY/MM/Sequence 
spk_ref,String,"Referensi nomor SPK (e.g., Neo.001/SPK/XI/2025) "
client_id,Foreign Key,"Relasi ke tabel Client (e.g., CV. AFZEL STAINLESS) "
total_contract,Decimal,"Total biaya SPK (e.g., 9,000,000) "
dp_amount,Decimal,"Jumlah DP 30% yang sudah diterima (e.g., 2,700,000) "
final_bill,Decimal,"Sisa tagihan 70% (e.g., 6,300,000) "
status,Enum,"Draft, Sent, Paid, Overdue"

3. Logika Bisnis (Business Logic)Validasi Termin: Sistem memeriksa apakah termin sebelumnya (DP 30%) sudah berstatus Paid.Kalkulasi Otomatis:$$Sisa\ Tagihan = Total\ Biaya\ SPK - DP\ Received$$Generator Nomor: Nomor invoice dihasilkan secara berurutan berdasarkan bulan dan tahun berjalan.Locking System: Setelah invoice pelunasan berstatus Paid, sistem secara otomatis mengunci data proyek agar tidak dapat diubah (Finalized).

4. Komponen Output (Template System)Invoice yang dihasilkan oleh sistem harus memuat komponen berikut secara dinamis:Metadata Header: Logo PT. Neo One Global Inovasi dan timestamp cetak.Informasi Vendor: Detail kontak Arif Hidayatulloh sebagai Project Manager.Informasi Client: Nama Pimpinan (e.g., Suwondo Risdianto) dan alamat lengkap.Payment Gateway Info: Instruksi transfer ke Bank Mandiri a.n Arif Hidayatulloh.System Footer: Catatan otomatis mengenai Pasal 4 Sistem Pembayaran SPK.

