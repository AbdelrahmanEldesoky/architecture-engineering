<?php

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Basic\BasicTable;
use App\Models\User;
use App\Services\UserService;
use Livewire\Component;
use Spatie\Permission\Models\Role;

class UsersTable extends BasicTable
{
    protected $listeners = ['refreshUsers' => '$refresh','confirmDelete'];
    public $status_id;
    public $role_id;
    public function search()
    {
        $service = new UserService();
        if($this->status_id == 'active')
            $status = 1;
        elseif($this->status_id == 'in-active')
            $status = 0;
        else
            $status = null;
        
        $usersQuery = $service->search($this->search);
        
        if (!empty($this->end_date) && !empty($this->start_date)) {
            $usersQuery->whereBetween('created_at', [$this->start_date . ' 00:00:00', $this->end_date . ' 00:00:00']);
        }
        $users = $usersQuery->with(['employee' ,'roles'])
                            ->whereHas("roles", fn ($q) => $q->when($this->role_id, function ($query) {
                                        $query->where('id', $this->role_id);
                                    }))
                            ->when($this->status_id,function ($query)use($status){
                                $query->where('active',$status);})
                            ->withCount('roles')
                            ->orderBy($this->orderBy, $this->orderDesc ? 'desc' : 'asc')
                            ->paginate($this->perPage);

        return $users;
    }
    public function render()
    {
        $users = $this->search();
        $roles = Role::get();

        return view('livewire.users.users-table',[
            'users' =>$users,
            'roles' =>$roles
        ]); 
    }

    public function toggle($id){
       $user = User::find($id);
        $user->active = !$user->active ;
        $user->save();
        $this->dispatchBrowserEvent('toastr',
            ['type' => 'success',  'message' =>__('message.updated',['model'=>__('names.user')])]);
        return ;
    }

    public function confirmDelete($id){
        $user = User::find($id);
        if ($user->employee()->exists()){
            $this->dispatchBrowserEvent('toastr',
                ['type' => 'warning',  'message' =>__('message.still-has',['model'=>__('names.user'),'relation'=>__('names.employee')])]);
            return ;
        }

    }

}
