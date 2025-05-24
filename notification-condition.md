| **Model**     | **Tipe Relasi**                | **Keterangan**                                                       |
|---------------|--------------------------------|----------------------------------------------------------------------|
| `User`        | hasMany `UserPackage`          | User bisa memiliki banyak paket aktif/riwayat                        |
| `User`        | hasMany `UserBill`             | User bisa memiliki banyak tagihan                                    |
| `User`        | hasMany `Ticket`               | User bisa mengirim banyak tiket                                      |
| `UserPackage` | belongsTo `User`               | Paket ini dimiliki oleh satu user                                    |
| `UserPackage` | belongsTo `Package`            | Paket ini adalah snapshot dari satu entri `packages`                 |
| `UserBill`    | belongsTo `User`               | Tagihan ini milik user tertentu                                      |
| `UserBill`    | hasMany `BillItem`             | Satu tagihan bulanan bisa terdiri dari banyak paket (di-snapshot)    |
| `UserBill`    | hasOne `Invoice`               | Satu tagihan = satu invoice                                          |
| `UserBill`    | hasOne `Income`                | Pemasukan berasal dari pembayaran tagihan                            |
| `BillItem`    | belongsTo `UserBill`           | Item ini adalah rincian dari 1 tagihan user                          |
| `BillItem`    | belongsTo `Package` (nullable) | Referensi ke `packages` asli (opsional)                              |
| `Package`     | hasMany `UserPackage`          | Paket ini dipilih oleh banyak user                                   |
| `Package`     | hasMany `BillItem`             | Paket ini pernah ditagihkan (jika `package_id` disimpan di snapshot) |
| `Invoice`     | belongsTo `UserBill`           | Bukti pembayaran untuk tagihan tertentu                              |
| `Income`      | belongsTo `UserBill`           | Pemasukan berdasarkan 1 tagihan                                      |
| `Expense`     | (independen)                   | Pengeluaran sistem, tidak terhubung ke user/bill                     |
| `Ticket`      | belongsTo `User`               | Tiket dibuat oleh user                                               |
| `Ticket`      | hasMany `TicketReply`          | Setiap tiket bisa punya banyak balasan                               |
| `TicketReply` | belongsTo `Ticket`             | Balasan ini termasuk dalam satu tiket                                |
| `TicketReply` | belongsTo `User`               | Balasan dikirim oleh user/admin                                      |
