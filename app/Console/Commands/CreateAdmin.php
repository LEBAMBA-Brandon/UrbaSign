<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

#[Signature('app:create-admin')]
#[Description('Create an administrator account interactively')]
class CreateAdmin extends Command
{
    public function handle(): int
    {
        $nom = trim((string) $this->ask('Nom'));
        $prenom = trim((string) $this->ask('Prénom'));
        $email = mb_strtolower(trim((string) $this->ask('Adresse e-mail')));

        $validator = Validator::make(
            compact('nom', 'prenom', 'email'),
            [
                'nom' => ['required', 'string', 'max:100'],
                'prenom' => ['required', 'string', 'max:100'],
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            ],
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $password = $this->secret('Mot de passe (12 caractères minimum)');
        $confirmation = $this->secret('Confirmer le mot de passe');

        if ($password === null || mb_strlen($password) < 12) {
            $this->error('Le mot de passe doit contenir au moins 12 caractères.');

            return self::FAILURE;
        }

        if ($password !== $confirmation) {
            $this->error('Les mots de passe ne correspondent pas.');

            return self::FAILURE;
        }

        if (! $this->confirm("Créer le compte administrateur pour {$email} ?")) {
            $this->warn('Création annulée.');

            return self::SUCCESS;
        }

        User::create([
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'password' => $password,
            'role' => 'admin',
        ]);

        $this->info("Compte administrateur créé pour {$email}.");

        return self::SUCCESS;
    }
}
