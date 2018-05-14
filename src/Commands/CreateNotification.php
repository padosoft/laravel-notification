<?php

namespace Padosoft\Laravel\Notification\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;

class CreateNotification extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notification-manager:create {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Notification and save it into mynotifications';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct($files);
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && !$this->option('force')) {
            return;
        }

        $this->writeMigration();

        if ($this->confirm('Run migrations?')) {
            $this->call('migrate');
        }
    }

    /**
     * Write the Migration the notification.
     *
     * @return void
     */
    protected function writeMigration()
    {
        $file = date('Y_m_d_His') . '_add_notification_' . snake_case($this->getNameInput()) . '.php';
        $path = database_path('migrations' . DIRECTORY_SEPARATOR . $file);
        $content = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'migration.stub');
        $content = str_replace('{name}', studly_case($this->getNameInput()), $content);
        $content = str_replace('{fullclass}', $this->qualifyClass($this->getNameInput()), $content);
        $this->files->put($path, $content);
        $this->line("<info>Created Migration:</info> {$file}");
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Notifications';
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'stubs' . DIRECTORY_SEPARATOR . 'notification.stub';
    }
}
