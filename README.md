## Laravel Tenancy - Multi Database

> Material de estudos feito apatir do curso Laravel-Tenancy---Multi-Database cujo tem como Professor: Nanderson Castro </br>
Através da Plataforma: https://codeexperts.com.br/curso/laravel-tenancy-2.

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
        
E pode remover use Illuminate\Database\Eloquent\Model; póis ele jás extende de model 

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
    
E pode remover use Illuminate\Database\Eloquent\Model; póis ele jás extende de model 
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
