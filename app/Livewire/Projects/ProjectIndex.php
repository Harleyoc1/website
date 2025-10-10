<?php

namespace App\Livewire\Projects;

use App\Models\Project;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Manage Portfolio')]
class ProjectIndex extends Component
{

    public $projects;

    public function mount(): void
    {
        $this->projects = Project::all()->sortBy('order_index');
    }

    #[On('update-project-order')]
    public function updateOrder(array $ordering): void
    {
        $case = 'CASE';
        for ($i = 0; $i < count($ordering); $i++) {
            $id = $ordering[$i];
            // Verify IDs are integers
            if (!is_int($id)) {
                return;
            }
            $case .= " WHEN id = $id THEN $i ";
        }
        $case .= 'END';

        // Batch update the order indexes
        DB::table('projects')
            ->whereIn('id', $ordering)
            ->update(['order_index' => DB::raw($case)]);

        // Workaround for project cells disappearing on update
        $this->redirect(route('management.portfolio.index'));
    }

}
