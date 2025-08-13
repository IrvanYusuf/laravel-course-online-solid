<?php

namespace App\Repositories\Interfaces;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UserAlreadyExistsException;

interface AuthServiceInterface
{
    /**
     * Mendaftarkan pengguna baru dan mengembalikan data user serta token JWT.
     *
     * @param array $data Data yang valid untuk membuat user, termasuk nama, email, password, dan role.
     * @return array{token: string, user: \App\Models\User}
     * @throws UserAlreadyExistsException Jika user sudah terdaftar.
     */
    public function register(array $data): array;

    /**
     * Mengotentikasi user dan mengembalikan token JWT jika kredensial valid.
     *
     * @param array $credentials Kredensial login, biasanya email dan password.
     * @return array{token: string, user: \App\Models\User}
     * @throws InvalidCredentialsException Jika kredensial tidak valid.
     */
    public function login(array $credentials): array;

    /**
     * Mengambil data user yang sedang terotentikasi.
     *
     * @return array Mengembalikan array berisi objek user.
     */
    public function me(): array;

    /**
     * Mengeluarkan user dari sesi saat ini dengan membatalkan token JWT.
     *
     * @return void
     */
    public function logout(): void;

    /**
     * Memperbarui token JWT yang sudah kedaluwarsa.
     *
     * @return array Mengembalikan array berisi token JWT yang baru.
     */
    public function refresh(): array;
}
