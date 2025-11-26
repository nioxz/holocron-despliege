<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CreateCompany extends Component
{
    // Variables de Empresa
    public $company_id, $company_name, $ruc;
    // Variables de Admin (Solo para creación)
    public $admin_name, $admin_email, $admin_password;
    
    public $isEditing = false; // Modo edición

    public function resetFields()
    {
        $this->reset(['company_id', 'company_name', 'ruc', 'admin_name', 'admin_email', 'admin_password', 'isEditing']);
    }

    public function edit($id)
    {
        $company = Company::find($id);
        $this->company_id = $company->id;
        $this->company_name = $company->name;
        $this->ruc = $company->ruc;
        $this->isEditing = true;
    }

    public function update()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'ruc' => 'required|string|max:11|unique:companies,ruc,'.$this->company_id,
        ]);

        $company = Company::find($this->company_id);
        $company->update([
            'name' => $this->company_name,
            'ruc' => $this->ruc,
            'slug' => Str::slug($this->company_name),
        ]);

        session()->flash('message', 'Empresa actualizada correctamente.');
        $this->resetFields();
    }

    public function submit()
    {
        $this->validate([
            'company_name' => 'required|string|max:255',
            'ruc' => 'required|string|max:11|unique:companies,ruc',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:users,email',
            'admin_password' => 'required|min:8',
        ]);

        $company = Company::create([
            'name' => $this->company_name,
            'ruc' => $this->ruc,
            'slug' => Str::slug($this->company_name),
        ]);

        User::create([
            'name' => $this->admin_name,
            'email' => $this->admin_email,
            'password' => Hash::make($this->admin_password),
            'role' => 'supervisor',
            'company_id' => $company->id,
            'job_title' => 'Administrador General',
            'is_password_changed' => true,
            'terms_accepted_at' => now(),
        ]);

        session()->flash('message', '¡Cliente creado exitosamente!');
        $this->resetFields();
    }

    public function delete($id)
    {
        // Opcional: Agregar lógica para "Soft Delete" o borrar todo
        Company::find($id)->delete();
    }

    public function render()
    {
        return view('livewire.admin.create-company', [
            'companies' => Company::withCount('users')->latest()->get()
        ])->layout('layouts.app');
    }
}