<?php

namespace App\Providers;

use App\Events\{
    VideoEvent
};
use Illuminate\Support\ServiceProvider;
use App\Repositories\Eloquent\{
    CastMemberEloquentRepository,
    CategoryEloquentRepository,
    GenreEloquentRepository,
    VideoEloquentRepository
};
use App\Repositories\Transaction\DBTransaction;
use App\Services\{
    Storage\FileStorage
};
use App\Services\AMQP\AMQPInterface;
use App\Services\AMQP\PhpAmqpService;
use Core\Domain\Repository\{
    CastMemberRepositoryInterface,
    CategoryRepositoryInterface,
    GenreRepositoryInterface,
    VideoRepositoryInterface
};
use Core\UseCase\Interfaces\{
    FileStorageInterface,
    TransactionInterface
};
use Core\UseCase\Video\Interfaces\VideoEventManagerInterface;

class CleanArchServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->bingRepositories();

        $this->app->singleton(
            FileStorageInterface::class,
            FileStorage::class,
        );

        $this->app->singleton(
            VideoEventManagerInterface::class,
            VideoEvent::class
        );

        /**
         * DB Transaction
         */
        $this->app->bind(
            TransactionInterface::class,
            DBTransaction::class,
        );

        /**
         * Services
         */
        $this->app->bind(
            AMQPInterface::class,
            PhpAmqpService::class,
        );
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function bingRepositories()
    {
        /**
         * Repositories
         */
        $this->app->singleton(
            CategoryRepositoryInterface::class,
            CategoryEloquentRepository::class
        );
        $this->app->singleton(
            GenreRepositoryInterface::class,
            GenreEloquentRepository::class
        );
        $this->app->singleton(
            CastMemberRepositoryInterface::class,
            CastMemberEloquentRepository::class
        );
        $this->app->singleton(
            VideoRepositoryInterface::class,
            VideoEloquentRepository::class
        );
    }
}
