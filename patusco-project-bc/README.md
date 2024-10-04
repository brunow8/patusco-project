Para correr o backend php artisan serve
É necessário estar num ambiente com PHP 8.2!
Provavelmente irá estar á escuta na porta 8000 devido a ser laravel.

Logo de seguinda, verificar se tem a base de dados caso não tenha a sua criação será necessária.
php artisan migrate
php artisan db:seed

Contas:

Recepcionista:
emily.smith@gmail.com -> password: teste
laura.johnson@gmail.com -> password: teste

Medics:
michael.brown@gmail.com -> password teste
sophia.davis@gmail.com -> password teste

Clients
oliver.martinez@gmail.com -> password teste
emma.rodriguez@gmail.com -> password teste

No caso dos clientes é possível fazer o registro de um com a sua conta pessoal.
