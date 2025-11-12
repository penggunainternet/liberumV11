# Dokumentasi API Endpoints

| No  | Endpoint                     | Method | Deskripsi                | Response                        |
| --- | ---------------------------- | ------ | ------------------------ | ------------------------------- |
| 1   | /api/register                | POST   | Registrasi user baru     | Token dan data user baru        |
| 2   | /api/login                   | POST   | Autentikasi pengguna     | Token dan data user             |
| 3   | /api/logout                  | POST   | Logout pengguna          | Status logout                   |
| 4   | /api/me                      | GET    | Data profil user         | Data user yang login            |
| 5   | /api/threads                 | GET    | Mengambil daftar thread  | JSON array threads + pagination |
| 6   | /api/threads                 | POST   | Membuat thread baru      | Status dan data thread          |
| 7   | /api/threads/{id}            | GET    | Detail thread            | Data lengkap thread             |
| 8   | /api/threads/{id}            | PUT    | Update thread            | Status dan data update          |
| 9   | /api/threads/{id}            | DELETE | Hapus thread             | Status hapus                    |
| 10  | /api/reply                   | GET    | Daftar semua reply       | JSON array replies              |
| 11  | /api/reply                   | POST   | Membuat reply baru       | Status dan data reply           |
| 12  | /api/reply/{id}              | PUT    | Update reply             | Status dan data update          |
| 13  | /api/reply/{id}              | DELETE | Hapus reply              | Status hapus                    |
| 14  | /api/categories              | GET    | Daftar kategori          | JSON array categories           |
| 15  | /api/categories              | POST   | Tambah kategori baru     | Status dan data kategori        |
| 16  | /api/categories/{id}         | GET    | Detail kategori          | Data kategori + threads         |
| 17  | /api/categories/{id}         | PUT    | Update kategori          | Status dan data update          |
| 18  | /api/categories/{id}         | DELETE | Hapus kategori           | Status hapus                    |
| 19  | /api/follow/{user}           | POST   | Follow/unfollow user     | Status follow/unfollow          |
| 20  | /api/like/{thread}           | POST   | Like/unlike thread       | Status like/unlike              |
| 21  | /api/notifications           | GET    | Daftar notifikasi        | JSON array notifications        |
| 22  | /api/notifications/mark-read | POST   | Tandai notifikasi dibaca | Status update                   |
| 23  | /api/search/threads          | GET    | Cari thread              | JSON array hasil pencarian      |
| 24  | /api/user/{id}/threads       | GET    | Thread by user           | JSON array thread user          |
| 25  | /api/user/{id}/replies       | GET    | Reply by user            | JSON array reply user           |
| 26  | /api/user/{id}/followers     | GET    | Daftar follower user     | JSON array followers            |
| 27  | /api/user/{id}/following     | GET    | Daftar following user    | JSON array following            |
| 28  | /api/thread/{id}/likes       | GET    | Daftar like thread       | JSON array users yang like      |
| 29  | /api/upload/image            | POST   | Upload gambar            | URL dan data gambar             |
| 30  | /api/thread/{id}/views       | GET    | Jumlah view thread       | Data statistik view             |

Catatan:

-   Semua endpoint kecuali /register dan /login memerlukan authentication token
-   Token harus disertakan dalam header: `Authorization: Bearer {token}`
-   Response error akan mengembalikan status code yang sesuai (400, 401, 403, 404, 500)
-   Pagination tersedia untuk endpoints yang mengembalikan list/array
-   Upload gambar mendukung format: jpg, jpeg, png, gif
-   Semua response dalam format JSON
