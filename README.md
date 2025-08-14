# 🎓 Laravel Course API

API ini digunakan untuk mengelola kursus, kategori, pendaftaran, dan pengguna.  
Mendukung **caching dengan Redis** untuk meningkatkan performa, serta **logging** untuk memantau alur data.

---

## 🚀 Fitur

-   **Autentikasi JWT** (login, register, me, refresh token, logout)
-   **Manajemen User** (CRUD user, role-based access)
-   **Manajemen Category** (CRUD kategori kursus)
-   **Manajemen Course** (CRUD kursus, filter by instructor)
-   **Manajemen Course Item** (materi kursus)
-   **Enrollment System** (pendaftaran dan pembelian kursus)
-   **Redis Caching** untuk optimasi query
-   **Logging** untuk memantau request dan hit/miss cache

---

## 🛠️ Teknologi

-   **Laravel 12**
-   **MySQL**
-   **Redis** (untuk cache)
-   **JWT Auth** (`tymon/jwt-auth`)

---

## 🚀 Caching

Caching digunakan untuk menyimpan data yang sifatnya statis atau jarang berubah.  
Dengan caching, sistem tidak perlu mengambil data yang sama berulang kali dari database, sehingga dapat mengurangi beban query dan mempercepat respon API.  
Jika data sudah ada di cache (misalnya Redis), maka akan langsung digunakan. Jika belum ada, data akan diambil dari database lalu disimpan ke cache untuk permintaan berikutnya.

### 1. GET /api/categories

Mengambil semua data kategori.

**Skenario cache:**

-   Jika data tidak ada di Redis, ambil dari database lalu simpan ke Redis.
-   Jika data ada di Redis, langsung kirim data dari cache.
-   Jika ada penambahan, perubahan, atau hapus data maka cache akan langsung dihapus agar data menjadi konsisten.

**Kode:**

```php
 public function getAllCategories(): Collection
    {
        // cache selama 10 menit
        Log::info('Fetching all categories from cache.');
        return Cache::remember('categories_all', 600, function () {
            return $this->categoryRepository->getAll();
        });
    }
```

**Hapus jika ada penambahan, perubahan, hapus data:**

```php
// penambahan data
    public function createNewCategory(array $data): Category
    {
        $category = $this->categoryRepository->findByName($data['name']);
        if ($category) {
            throw new CategoryAlreadyExistsException();
        }
        Cache::forget('categories_all');
        return Category::create($data);
    }

    // update
    public function update(string $id, array $data): Category
    {
        $this->getCategoryById($id);
        Cache::forget('categories_all');
        return $this->categoryRepository->update($id, $data);
    }

    // delete
    public function delete(string $id): bool
    {
        $this->getCategoryById($id);
        Cache::forget('categories_all');
        return $this->categoryRepository->delete($id);
    }
```

---

### 2. GET /api/courses

Mengambil semua data kursus.

**Skenario cache:**

-   Jika data tidak ada di Redis, ambil dari database lalu simpan ke Redis.
-   Jika data ada di Redis, langsung kirim data dari cache.
-   Jika ada penambahan, perubahan, atau hapus data maka cache akan langsung dihapus agar data menjadi konsisten.

**Kode:**

```php
    public function getAllCourses(): Collection
    {
        // return $this->courseRepository->getAll();
        Log::info("fetching all courses from cache");
        return Cache::remember("all_courses", 600, function () {
            Log::info("fetching all courses from database");
            return $this->courseRepository->getAll();
        });
    }
```

**Hapus jika ada penambahan, perubahan, hapus data:**

```php
// penambahan data
    public function createCourse(array $data, User $user): Course
    {
        // Contoh: Logika bisnis di sini, misalnya validasi role user
        $instructor = $user->role;
        if ($instructor === "STUDENT") {
            throw new UnauthorizedException();
        }
        $course = $this->courseRepository->findByTitle($data['title']);
        if ($course) {
            throw new CourseAlreadyExistsException();
        }
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->create($data);
    }

    // update
    public function updateCourse(string $id, array $data): Course
    {
        $this->getCourseById($id);
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->update($id, $data);
    }

    // delete
    public function deleteCourse(string $id): bool
    {
        $this->getCourseById($id);
        Log::info("revalidate all courses cache");
        Cache::forget('all_courses');
        return $this->courseRepository->delete($id);
    }
```

### 3. GET /api/enrollments/my-courses

Mengambil semua kursus yang sudah dibeli user (payment status = PAID).

**Skenario cache:**

-   Jika data kursus user tidak ada di Redis, ambil dari database lalu simpan ke Redis.
-   Jika data kursus user ada di Redis, langsung kirim data dari cache.
-   Cache akan dihapus (invalidate) ketika user membeli course baru, status pembayaran berubah, atau enrollment dihapus.
-   Jika ada penambahan, perubahan, atau hapus data maka cache akan langsung dihapus agar data menjadi konsisten.

**Kode:**

```php
    public function getMyCourses(string $studentId): Collection
    {
        // cache selamat 5 menit
        Log::info('Fetching all my courses from cache.');
        return Cache::remember("user_{$studentId}_my_courses", 300, function () use ($studentId) {
            Log::info('Fetching all my courses from database.');
            return $this->enrollmentRepository->getPurchasedByUser($studentId);
        });
    }
```

**Hapus jika ada penambahan, perubahan, hapus data:**

```php
    // penambahan data
    /**
     * Membuat pendaftaran baru/membeli course.
     *
     * @param array $data
     * @throws EnrollCourseAlreadyExistsException
     * @return Enrollment
     */
    public function enrollStudentInCourse(array $data): Enrollment
    {
        $enrollment = $this->enrollmentRepository->findByStudentAndCourse($data['student_id'], $data['course_id']);

        if ($enrollment) {
            throw new EnrollCourseAlreadyExistsException();
        }
        Cache::forget("user_{$data['student_id']}_my_courses");
        return $this->enrollmentRepository->create($data);
    }

    // update
    public function updateEnrollment(string $enrollmentId, array $data): Enrollment
    {
        // Panggil metode getEnrollmentById untuk memeriksa keberadaan pendaftaran
        $enrollment = $this->getEnrollmentById($enrollmentId);
        Cache::forget("user_{$enrollment->student_id}_my_courses");
        return $this->enrollmentRepository->update($enrollmentId, $data);
    }

    // delete
    /**
     * Menghapus pendaftaran dari database.
     *
     * @param string $enrollmentId ID dari pendaftaran yang akan dihapus.
     * @return bool Mengembalikan true jika penghapusan berhasil.
     * @throws EnrollNotFoundException Jika pendaftaran tidak ditemukan.
     * @throws UnauthorizedException Jika role bukan admin.
     */
    public function deleteEnrollment(string $enrollmentId, User $user): bool
    {
        if ($user->role !== "ADMIN") {
            throw new UnauthorizedException();
        }
        // Panggil metode getEnrollmentById untuk memeriksa keberadaan pendaftaran
        $enrollment = $this->getEnrollmentById($enrollmentId);
        Cache::forget("user_{$enrollment->student_id}_my_courses");

        return $this->enrollmentRepository->delete($enrollmentId);
    }
```

---
