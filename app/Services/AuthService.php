<?php

namespace App\Services;

use App\Exceptions\InvalidCredentialsException;
use App\Exceptions\UnauthorizedException;
use App\Exceptions\UserAlreadyExistsException;
use App\Repositories\Interfaces\AuthServiceInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthService implements AuthServiceInterface
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository =  $userRepository;
    }
    /**
     * Mendaftarkan pengguna baru dan mengembalikan data user serta token JWT.
     *
     * @param array $data Data yang valid untuk membuat user, termasuk nama, email, password, dan role.
     * @return array{token: string, user: \App\Models\User}
     * @throws UserAlreadyExistsException Jika user sudah terdaftar.
     */
    public function register(array $data): array
    {
        $checkExistingUser = $this->userRepository->findByEmail($data['email']);
        if ($checkExistingUser) {
            throw new UserAlreadyExistsException();
        }
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepository->create($data);

        $token = Auth::setTTL(60 * 24 * 7)->fromUser($user);

        return [
            "token" => $token,
            "user" => $user
        ];
    }

    /**
     * Mengotentikasi user dan mengembalikan token JWT jika kredensial valid.
     *
     * @param array $credentials Kredensial login, biasanya email dan password.
     * @return array{token: string, user: \App\Models\User}
     * @throws InvalidCredentialsException Jika kredensial tidak valid.
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new InvalidCredentialsException();
        }
        $token = Auth::setTTL(60 * 24 * 7)->attempt($credentials);
        return [
            'token' => $token,
            "user" => $user
        ];
    }

    /**
     * Mengambil data user yang sedang terotentikasi.
     *
     * @return array Mengembalikan array berisi objek user.
     */
    public function me(): array
    {
        $user = auth("api")->user();
        if (!$user) {
            throw new UnauthorizedException();
        }
        return [
            'user' => $user,
        ];
    }

    /**
     * Mengeluarkan user dari sesi saat ini dengan membatalkan token JWT.
     *
     * @return void
     */
    public function logout(): void
    {
        auth("api")->logout();
    }


    /**
     * Memperbarui token JWT yang sudah kedaluwarsa.
     *
     * @return array Mengembalikan array berisi token JWT yang baru.
     */
    public function refresh(): array
    {
        return [
            'token' => auth("api")->refresh(),
        ];
    }
}
