<?php

namespace App\Console\Commands;

use App\Models\Project;
use Illuminate\Console\Command;

class FixDeletedProjects extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fix:projects';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $projects = Project::onlyTrashed()->get();
        foreach ($projects as $project) {
            $this->info('deleting project: ' . $project->id);
            $project->images()->delete();
        }
    }
}
