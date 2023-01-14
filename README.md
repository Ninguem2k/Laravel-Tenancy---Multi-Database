## Laravel Tenancy - Multi Database

> Material de estudos feito apatir do curso Laravel-Tenancy---Multi-Database </br> Cujo tem como Professor: Nanderson Castro </br>
Através da Plataforma: https://codeexperts.com.br/curso/laravel-tenancy-2

__Iniciando Projeto__

* Para criar o projeto laravel com sail

        curl -s https://laravel.build/NOMEDOPROJETO?with=pgsql
        
* Criar um simplicador para o comando sail.
	
        alias sail="./vendor/bin/sail"

* para levantar os cotainers 

	    sail up -d 

__Configurando Pacote__
* Para baixar o pacote tenacy

	    sail composer require stancl/tenancy

* Para instalar o pacote tenacy no projeto

	    sail artisan tenancy:install

+ Habilitar no larael

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
                
                protected static function booted()
                {
                   static::creating(function($tenant){
                        $tenant->password = bcrypt($tenant->password);
                        $tenant->role = 'ROLE_ADMIN';
                   });
                }
        }

* Gere o model de Domain

	     sail artisan make:model Domain
    
_E pode remover use Illuminate\Database\Eloquent\Model; póis ele jás extende de model_ 
* Adicione no model de Domain

         use Stancl\Tenancy\Database\Models\Domain as BaseDomain;

	     class Domain extends BaseDomain
	     {
    		    use HasFactory;
	     }

* E em tenancy subtitua use Stancl\Tenancy\Database\Models\{Tenant, Domain} por

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

__Pasta Storage Inquilino Usando Jobs__

        sail artisan make:job CreateFrameworkDirectoriesForTenant

* Em seguida adicione no job criado
    
        namespace App\Jobs;

        use App\Models\Tenant;

        class CreateFrameworkDirectoriesForTenant implements ShouldQueue{
                protected $tenant;

                public function __construct(Tenant $tenant)
                {
                        $this->tenant = $tenant;
                }

                public function handle()
                {
                        $this->tenant->run(function ($tenant) {
                        $storage_path = storage_path();

                        mkdir("$storage_path/framework/cache", 0777, true);
                        });
                }
        }

* Logo a seguir após criação do job basta adicionar na lista de execurção
* faça a inportação Em App\Providers\TenancyServiceProvider.php

        use App\jobs\{CreateRootUserTenant, CreateFrameworkDirectoriesForTenant};

* E adicionado o comando a seguir em jobPipeline antes de CreateRootUserTenant::class,

        CreateFrameworkDirectoriesForTenant::class,
        

* Comando para execultar o ambiente de desevolvimento com docker sail automatizado

        @echo 
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
        
        public function store(RegisterTenantRequest $request){
                
                $tenant = Tenant::create($request->validated());
    
                $tenant->createDomain(['domain'=>$request->domain]);
        
                return redirect(tenant_route($tenant->domains()->first()->domain, 'login'));

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

        use App\Models\User;

        private $tenant;

        public function __construct($tenant)
        {
                $this->tenant = $tenant;
        }

        public function handle()
        {
            $this->tenant->run(function($tenant){
                User::create($tenant->only('name','email', 'password','role'));
            });
        }

__Validando__

* Criar um request de validação

        sail artisan make:request RegisterTenantRequest

* Em RegisterTenantRequest adicione após a função de validação

        >use Illuminate\Validation\Rules;
        >>para que validação da senha seja correta adicione

        protected function prepareForValidation()
        {
                $centralDomain = config('tenancy.central_domains')[0];
                $this->merge(['domain'=>$this->domain .'.'. $centralDomain]);
        }

__Livewire no Projeto__

* Inserir o livewire no projeto

        sail composer require livewire/livewire

* Adicione @livewireStyles @livewireScripts no layout do sistema

* Gere os Models para Restaurante e para Menus

        sail artisan make:model  Tenant/Restaurant
        sail artisan make:model  Tenant/Menu

* Gere o componente livewire

        sail artisan make:livewire tenant.restuarant

__Livewire Configs Específicas pro Tenancy__

* Acessar todos pacotes existente no projeto 

        sail artisan vendor:publish

* Selecione livewire:config para modificar a config selecionado o numero correspondente

* Em config livewire.php substua 'middleware_group' => 'web', por 'middleware_group' => ['web','universal', initializeTenancyByDomain::class],

* Em config tenancy.php no array features desconte 

        Stancl\Tenancy\Features\UniversalRoutes::class,

* Já em App\Http\kenel.php adicone 'universal' => [] emm middlewareGroups após api

* Para gerar os componetes no Livewire

        sail artisan make:livewire tenants.restaurant-menu.index       
        sail artisan make:livewire tenants.restaurant-menu.item       
        sail artisan make:livewire tenants.restaurant-menu.delete       

__Migrations retaurant__

* Correção da migration
        
        sail artisan make:migration alter_menus_table_remove_restaurant_id_column --table=menus --path=database/migrations/tenant

_Na migration de menus comende a chave estrageira_
_Já na migration criado logo a cima adicione no up_
        
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeing('menus_retaurant_id_foreign');
            $table->dropColumn('retaurant_id');
        });

_E em down_

        Schema::table('menus', function (Blueprint $table) {
            $table->foreignId('restaurant_id')
                ->constrained()
                ->cascadeOnDelete();
        });

_após isso basta rodar a migration no tenants_

        sail artisan tenants:migrate

__Trabalhando Preços Corretamente__

* EM App/Models/Tenant/Menu.php adicione;

        

        