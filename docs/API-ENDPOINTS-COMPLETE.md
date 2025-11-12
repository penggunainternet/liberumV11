# Dokumentasi API Endpoints

## Regular API Endpoints

| No  | Endpoint             | Method | Deskripsi               | Response                        |
| --- | -------------------- | ------ | ----------------------- | ------------------------------- |
| 1   | /api/register        | POST   | Registrasi user baru    | Token dan data user baru        |
| 2   | /api/login           | POST   | Autentikasi pengguna    | Token dan data user             |
| 3   | /api/logout          | POST   | Logout pengguna         | Status logout                   |
| 4   | /api/me              | GET    | Data profil user        | Data user yang login            |
| 5   | /api/threads         | GET    | Mengambil daftar thread | JSON array threads + pagination |
| 6   | /api/threads         | POST   | Membuat thread baru     | Status dan data thread          |
| 7   | /api/threads/{id}    | GET    | Detail thread           | Data lengkap thread             |
| 8   | /api/threads/{id}    | PUT    | Update thread           | Status dan data update          |
| 9   | /api/threads/{id}    | DELETE | Hapus thread            | Status hapus                    |
| 10  | /api/reply           | GET    | Daftar semua reply      | JSON array replies              |
| 11  | /api/reply           | POST   | Membuat reply baru      | Status dan data reply           |
| 12  | /api/reply/{id}      | PUT    | Update reply            | Status dan data update          |
| 13  | /api/reply/{id}      | DELETE | Hapus reply             | Status hapus                    |
| 14  | /api/categories      | GET    | Daftar kategori         | JSON array categories           |
| 15  | /api/categories      | POST   | Tambah kategori baru    | Status dan data kategori        |
| 16  | /api/categories/{id} | GET    | Detail kategori         | Data kategori + threads         |
| 17  | /api/categories/{id} | PUT    | Update kategori         | Status dan data update          |
| 18  | /api/categories/{id} | DELETE | Hapus kategori          | Status hapus                    |

## Admin API Endpoints

| No  | Endpoint                        | Method | Deskripsi                      | Response                    |
| --- | ------------------------------- | ------ | ------------------------------ | --------------------------- |
| 1   | /api/admin/dashboard            | GET    | Statistik dashboard admin      | Data statistik & ringkasan  |
| 2   | /api/admin/users/active         | GET    | Daftar user aktif              | JSON array users aktif      |
| 3   | /api/admin/users/{id}/promote   | POST   | Promosi user menjadi admin     | Status dan data update      |
| 4   | /api/admin/users/{id}/demote    | POST   | Menurunkan role admin ke user  | Status dan data update      |
| 5   | /api/admin/users/{id}/delete    | DELETE | Hapus user                     | Status hapus                |
| 6   | /api/admin/threads/pending      | GET    | Daftar thread pending moderasi | JSON array threads pending  |
| 7   | /api/admin/threads/approved     | GET    | Daftar thread yang disetujui   | JSON array threads approved |
| 8   | /api/admin/threads/rejected     | GET    | Daftar thread yang ditolak     | JSON array threads rejected |
| 9   | /api/admin/threads/{id}         | GET    | Detail thread untuk moderasi   | Data lengkap thread         |
| 10  | /api/admin/threads/{id}/approve | POST   | Menyetujui thread              | Status dan data approve     |
| 11  | /api/admin/threads/{id}/reject  | POST   | Menolak thread                 | Status dan data reject      |
| 12  | /api/admin/categories           | GET    | Manajemen kategori             | JSON array categories       |
| 13  | /api/admin/categories/create    | GET    | Form tambah kategori           | Form data kategori          |
| 14  | /api/admin/categories           | POST   | Simpan kategori baru           | Status dan data kategori    |
| 15  | /api/admin/categories/{id}/edit | GET    | Form edit kategori             | Form data kategori          |
| 16  | /api/admin/categories/{id}      | PUT    | Update kategori                | Status dan data update      |
| 17  | /api/admin/categories/{id}      | DELETE | Hapus kategori                 | Status hapus                |

## Social Features API Endpoints

| No  | Endpoint                     | Method | Deskripsi                | Response                   |
| --- | ---------------------------- | ------ | ------------------------ | -------------------------- |
| 1   | /api/follow/{user}           | POST   | Follow/unfollow user     | Status follow/unfollow     |
| 2   | /api/like/{thread}           | POST   | Like/unlike thread       | Status like/unlike         |
| 3   | /api/notifications           | GET    | Daftar notifikasi        | JSON array notifications   |
| 4   | /api/notifications/mark-read | POST   | Tandai notifikasi dibaca | Status update              |
| 5   | /api/user/{id}/followers     | GET    | Daftar follower user     | JSON array followers       |
| 6   | /api/user/{id}/following     | GET    | Daftar following user    | JSON array following       |
| 7   | /api/thread/{id}/likes       | GET    | Daftar like thread       | JSON array users yang like |
| 8   | /api/thread/{id}/views       | GET    | Jumlah view thread       | Data statistik view        |

Catatan:

-   Semua endpoint kecuali /register dan /login memerlukan authentication token
-   Endpoint admin memerlukan role admin & middleware isAdmin
-   Token harus disertakan dalam header: `Authorization: Bearer {token}`
-   Response error akan mengembalikan status code yang sesuai (400, 401, 403, 404, 500)
-   Pagination tersedia untuk endpoints yang mengembalikan list/array
