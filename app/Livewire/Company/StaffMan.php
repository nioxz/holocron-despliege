<?php

namespace App\Livewire\Company;

use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffMan extends Component
{
    public $user_id, $name, $surname, $dni, $email, $job_title, $password;
    public $selected_role = 'worker'; 
    public $isEditing = false;

    protected $rules = [
        'name' => 'required',
        'surname' => 'required',
        'dni' => 'required',
        'email' => 'required|email', // Quitamos unique para manejar error manual
        'job_title' => 'required',
    ];

    public function resetFields()
    {
        $this->reset(['user_id', 'name', 'surname', 'dni', 'email', 'job_title', 'selected_role', 'password', 'isEditing']);
    }

    // --- FUNCIÓN PRINCIPAL PARA GUARDAR ---
    public function save()
    {
        $this->validate();

        // Verificar si el correo ya existe
        if (!$this->isEditing && User::where('email', $this->email)->exists()) {
            session()->flash('error', '¡Ese correo ya está registrado!');
            return;
        }

        // Mapeo de roles
        $sst_role = 'worker';
        $warehouse_role = 'user';
        if ($this->selected_role === 'supervisor') $sst_role = 'supervisor';
        if ($this->selected_role === 'warehouse') $warehouse_role = 'admin';

        try {
            if ($this->isEditing) {
                $user = User::find($this->user_id);
                $data = [
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'dni' => $this->dni,
                    'email' => $this->email,
                    'job_title' => $this->job_title,
                    'role' => $sst_role,
                    'warehouse_role' => $warehouse_role
                ];
                if (!empty($this->password)) {
                    $data['password'] = Hash::make($this->password);
                    $data['is_password_changed'] = false;
                }
                $user->update($data);
                session()->flash('message', 'Personal actualizado correctamente.');
            } else {
                $this->validate(['password' => 'required|min:4']);
                
                User::create([
                    'name' => $this->name,
                    'surname' => $this->surname,
                    'dni' => $this->dni,
                    'email' => $this->email,
                    'job_title' => $this->job_title,
                    'password' => Hash::make($this->password),
                    'company_id' => Auth::user()->company_id, // Hereda empresa del jefe
                    'role' => $sst_role,
                    'warehouse_role' => $warehouse_role,
                    'is_password_changed' => false,
                    'terms_accepted_at' => null,
                ]);
                session()->flash('message', '¡Colaborador registrado exitosamente!');
            }
            
            $this->resetFields(); // Limpiar formulario

        } catch (\Exception $e) {
            session()->flash('error', 'Error técnico: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $user = User::where('company_id', Auth::user()->company_id)->find($id);
        if($user) {
            $this->user_id = $user->id;
            $this->name = $user->name;
            $this->surname = $user->surname;
            $this->dni = $user->dni;
            $this->email = $user->email;
            $this->job_title = $user->job_title;
            
            if ($user->warehouse_role === 'admin') $this->selected_role = 'warehouse';
            elseif ($user->role === 'supervisor') $this->selected_role = 'supervisor';
            else $this->selected_role = 'worker';

            $this->isEditing = true;
        }
    }

    public function deleteUser($id)
    {
        $user = User::where('company_id', Auth::user()->company_id)->find($id);
        if($user) $user->delete();
    }

    public function render()
    {
        // Cargar lista actualizada
        $staff = User::where('company_id', Auth::user()->company_id)
                     ->where('id', '!=', Auth::id())
                     ->latest()->get();
        return view('livewire.company.staff-man', compact('staff'))->layout('layouts.app');
    }
}