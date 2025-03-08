<?php 


namespace App\Console\Commands;

use Illuminate\Console\Command;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\TicketResource;

class GeneratePermissions extends Command
{
    protected $signature = 'generate:permissions';
    protected $description = 'Generates permissions for MoonShine resources';

    public function handle()
    {
        $this->call('moonshine-rbac:permissions', [ 
            'resourceName' => 'UserResource' 
        ]);
    
        $this->call('moonshine-rbac:permissions', [ 
            'resourceName' => 'RoleResource' 
        ]);
    
        $this->call('moonshine-rbac:permissions', [ 
            'resourceName' => 'PermissionResource' 
        ]);
    
        $this->call('moonshine-rbac:permissions', [ 
            'resourceName' => 'TicketResource'
        ]);
    }
}
