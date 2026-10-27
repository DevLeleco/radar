<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $name = env('RADAR_ADMIN_NAME');
        $email = env('RADAR_ADMIN_EMAIL');
        $password = env('RADAR_ADMIN_PASSWORD');

        if (! $name && ! $email && ! $password) {
            return;
        }

        if (! $name || ! $email || ! $password || ! filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 12) {
            throw new \RuntimeException('Configure RADAR_ADMIN_NAME, RADAR_ADMIN_EMAIL e uma RADAR_ADMIN_PASSWORD com pelo menos 12 caracteres.');
        }

        $admin = User::where('email', $email)->first();
        if ($admin) {
            if ((int) $admin->perfil !== 1) {
                throw new \RuntimeException('O e-mail configurado para o administrador já pertence a uma conta que não é administradora.');
            }

            return;
        }

        $admin = new User();
        $admin->forceFill([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'id_cliente' => null,
            'perfil' => 1,
        ]);
        $admin->save();
    }
}
