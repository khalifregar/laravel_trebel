<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Admin;

class AdminSearch extends Component
{
    public string $search = '';

    public function render()
    {
        $query = Admin::query();

        if (!empty($this->search)) {
            $query->search($this->search); // ⬅️ Gunakan scope dari model
        }

        $filteredAdmins = $query->latest()->get();

        return view('livewire.admin-search', [
            'filteredAdmins' => $filteredAdmins,
            'created_admin' => session('created_admin')
        ]);
    }

}


