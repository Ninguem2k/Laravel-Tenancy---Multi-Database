## Laravel Tenancy - Multi Database
 
> Material de estudos feito a partir do curso Laravel-Tenancy---Multi-Database </br> Cujo tem como Professor: Nanderson Castro </br>
Através da Plataforma: https://codeexperts.com.br/curso/laravel-tenancy-2
 
__Iniciando Projeto__
 
* Para criar o projeto laravel com sail
 
        curl -s https://laravel.build/NOMEDOPROJETO?with=pgsql
       
* Criar um simplificador para o comando sail.
       
        alias sail="./vendor/bin/sail"
 
* para levantar os containers
 
            sail up -d
 
__Configurando Pacote__
* Para baixar o pacote tenacy
 
            sail composer require stancl/tenancy
 
* Para instalar o pacote tenacy no projeto
 
            sail artisan tenancy:install
 
+ Pra habilitar o tenancy no laravel
 
* Vá em confing/app.php no array de 'providers'=>[ após RouterServiceProvider ]; adicione:
 
        App\Providers\TenancyServiceProvider::class
 
__Boas platicas, Adequando Models__
 
* Gere o model de Tenant
 
        sail artisan make:model Tenant
       
_E pode remover use Illuminate\Database\Eloquent\Model; póis ele jás extende de model_
 
* Substitua no model de Tenant
 
        namespace App\Models;
 
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Stancl\Tenancy\Database\Models\Tenant as TenantBase;
        use Stancl\Tenancy\Contracts\TenantWithDatabase;
        use Stancl\Tenancy\Database\Concerns\HasDatabase;
        use Stancl\Tenancy\Database\Concerns\HasDomains;
 
        class Tenant extends TenantBase implements TenantWithDatabase
        {
            use HasFactory, HasDatabase, HasDomains;
        }
 
* Gere o model de Domain
 
             sail artisan make:model Domain
   
_E pode remover use Illuminate\Database\Eloquent\Model; pois ele já estende de model_
* Adicione no model de Domain
 
         use Stancl\Tenancy\Database\Models\Domain as BaseDomain;
 
             class Domain extends BaseDomain
             {
                    use HasFactory;
             }
 
* E em tenancy substitua use Stancl\Tenancy\Database\Models\{Tenant, Domain} por
 
        use App\Models\{Tenant, Domain};
 
__Adequando Domínios Centrais__
* Abrar app/Providers/RouteServiceProvider.php substitua
 
        public function boot()
        {
                $this->configureRateLimiting();
 
                $this->routes(function () {
                        $this->mapWebRoutes();
 
                        $this->mapApiRoutes();
                });
         }
 
* E cole logo abaixo as 3 funções a seguir
 
        public function mapApiRoutes(){
            foreach($this->centralDomains() as $domain){
                Route::middleware('api')
                      ->domain($domain)
                      ->prefix('api')
                      ->group(base_path('routes/api.php'));
            }
        }
 
        public function mapWebRoutes(){
            foreach($this->centralDomains() as $domain){
                Route::middleware('web')
                      ->domain($domain)
                      ->group(base_path('routes/web.php'));
            }
        }
 
        protected function centralDomains()`
        {
            return config('tenancy.central_domains');
        }
       
* Criando Migrações Tenant
 
        sail artisan make:migration create_restaurants_table --path=database/migrations/tenant
        sail artisan make:migration create_menus_table --path=database/migrations/tenant

* Comando para execultar o ambiente de desevolvimento com docker sail automatizado

        start http://localhost
        call "C:\Program Files\Docker\Docker\Docker Desktop.exe"
        start ubuntu.exe
        echo cd /home/c2k/projects/vetrines; alias sail="./vendor/bin/sail"; sail up -d; code . | clip

salve como .bat

* Instale o pacote Breeze

        sail composer require laravel/breeze --dev

* Instalar o Breeze no Laravel

        sail artisan breeze:install
* Mova o comando a seguir de routes/web.php para routes/tenant.php logo abaixo da rota home "/"

        require __DIR__.'/auth.php';

* Para corrigir o erro de acents basta ir em config/tenancy.php e mudar de true para false ou subtituir como codigo abaixo

        'asset_helper_tenancy' => false,

* Crie um controller para  RegisterTenantController

        sail artisan make:controller RegisterTenantController

* No controller de RegisterTenantController cole seguintes functions

        public function register(){
                return view('auth.register-tenant');
        }
        
        public function store(Request $request){
                dd($request->all());
        }

* No App\Models\Tenant.php adcione

        protected static function booted(){
          static::creating(function($tenant){
             tenant->password =bcrypt($tenant->password);
             $tenant->role = 'ROLE_ADMIN';
          });
        }

* Em App\Providers\TenancyServiceProvider.php inporte CreateRootUserTenant

        use App\jobs\CreateRootUserTenant;

* Em App\Providers\TenancyServiceProvider.php>TenancyServiceProvider>events após // jobs\SeedDatabase::class, adicione 

        CreateRootUserTenant::class,

* Crie um novo job atraves 

        sail artisan make:job CreateRootUserTenant

* No App\jobs\CreateRootUserTenant adicione e substitua 

        private $tenant;

        public function __construct($tenant)
        {
            $this->tenant = $tenant;
        }
