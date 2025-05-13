| **Model**     | **Kondisi**                          | **Notifikasi**                                | **Target Notifikasi** |
| ------------- | ------------------------------------ | --------------------------------------------- | --------------------- |
| `UserBill`    | User mengirim konfirmasi pembayaran  | `UserBillPaymentSubmittedNotification`        | Admin                 |
| `UserBill`    | Admin menyetujui pembayaran          | `UserBillPaymentApprovedNotification`         | User                  |
| `UserBill`    | Admin menolak pembayaran             | `UserBillPaymentRejectedNotification`         | User                  |
| `UserBill`    | Tagihan baru dibuat                  | `UserBillCreatedNotification`                 | User                  |
| `Invoice`     | Invoice tersedia untuk diunduh       | `InvoiceGeneratedNotification`                | User                  |
| `Ticket`      | User membuat tiket bantuan           | `TicketCreatedNotification`                   | Admin                 |
| `Ticket`      | Admin membalas tiket                 | `TicketRepliedByAdminNotification`            | User                  |
| `Ticket`      | User membalas tiket                  | `TicketRepliedByUserNotification`             | Admin                 |
| `Ticket`      | Tiket diselesaikan (status resolved) | `TicketResolvedNotification`                  | User                  |
| `UserPackage` | Paket user diubah oleh admin         | `UserPackageUpdatedNotification`              | User                  |
| `User`        | Profil user diperbarui               | `UserProfileUpdatedNotification` *(optional)* | User                  |
| `User`        | Akun user dinonaktifkan              | `UserDeactivatedNotification` *(optional)*    | User                  |
| `Income`      | Admin mencatat pemasukan manual      | `IncomeRecordedManuallyNotification` *(ops)*  | Admin                 |
