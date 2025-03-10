<?php

declare(strict_types=1);

namespace App\Providers;

use MoonShine\Providers\MoonShineApplicationServiceProvider;
use MoonShine\MoonShine;
use MoonShine\Menu\MenuGroup;
use MoonShine\Menu\MenuItem;
use MoonShine\Resources\MoonShineUserResource;
use MoonShine\Resources\MoonShineUserRoleResource;
use App\MoonShine\Resources\TicketResource;
use MoonShine\Contracts\Resources\ResourceContract;
use MoonShine\Menu\MenuElement;
use MoonShine\Pages\Page;
use Closure;
use MoonShine\Menu\MenuDivider;

class MoonShineServiceProvider extends MoonShineApplicationServiceProvider
{
    /**
     * @return list<ResourceContract>
     */
    protected function resources(): array
    {
        return [
            new TicketResource(),
        ];
    }

    /**
     * @return list<Page>
     */
    protected function pages(): array
    {
        return [];
    }

    /**
     * @return Closure|list<MenuElement>
     */
    protected function menu(): array
    {
        return [
            
            MenuGroup::make('System', [
                MenuItem::make('Usuarios', new \Sweet1s\MoonshineRBAC\Resource\UserResource(), 'heroicons.outline.users') ->canSee(static fn () => auth()->user()->roles()->whereIn('name', [ 'Admin', 'Super_administrador'])->exists()),
                MenuItem::make('Roles', new \Sweet1s\MoonshineRBAC\Resource\RoleResource(), 'heroicons.outline.shield-exclamation') ->canSee(static fn () => auth()->user()->roles()->whereIn('name',  [ 'Admin', 'Super_administrador'])->exists()),
                MenuItem::make('Permisos', new \Sweet1s\MoonshineRBAC\Resource\PermissionResource(), 'heroicons.outline.shield-exclamation') ->canSee(static fn () => auth()->user()->roles()->whereIn('name',  [ 'Admin', 'Super_administrador'] )->exists()),
            ], 'heroicons.outline.user-group'),

            MenuDivider::make(),
            MenuItem::make('Ticket', new TicketResource()) ->canSee(static fn () => auth()->user()->roles()->whereIn('name', ['Usuario', 'Soporte', 'Super_administrador'])->exists())


        ];
    }

    /**
     * @return Closure|array{css: string, colors: array, darkColors: array}
     */
    protected function theme(): array
    {
        return [
            'colors' => [
                'primary' => 'rgb(198, 210, 25)',
                'secondary' => 'rgb(238, 173, 26)',
                'body' => 'rgb(0, 112, 128)',
                'dark' => [
                    'DEFAULT' => 'rgb(0, 112, 128)',
                    50 => 'rgb(240, 234, 234)',
                    100 => 'rgb(74, 90, 121)',
                    200 => 'rgb(65, 81, 114)',
                    300 => 'rgb(53, 69, 103)',
                    400 => 'rgb(48, 61, 93)',
                    500 => 'rgb(41, 53, 82)',
                    600 => 'rgb(40, 51, 78)',
                    700 => 'rgb(39, 45, 69)',
                    800 => 'rgb(27, 37, 59)',
                    900 => 'rgb(255, 255, 255)',
                ],
 
                'success-bg' => 'rgb(0, 112, 128)',
                'success-text' => 'rgb(255, 255, 255)',
                'warning-bg' => 'rgb(255, 220, 42)',
                'warning-text' => 'rgb(139, 116, 0)',
                'error-bg' => 'rgb(238, 173, 26)',
                'error-text' => 'rgb(255, 255, 255)',
                'info-bg' => 'rgb(0, 121, 255)',
                'info-text' => '#000000',
            ],
            'darkColors' => [
                'body' => 'rgb(27, 37, 59)',
                'success-bg' => 'rgb(17, 157, 17)',
                'success-text' => 'rgb(178, 255, 178)',
                'warning-bg' => 'rgb(225, 169, 0)',
                'warning-text' => 'rgb(255, 255, 199)',
                'error-bg' => 'rgb(190, 10, 10)',
                'error-text' => 'rgb(255, 197, 197)',
                'info-bg' => 'rgb(38, 93, 205)',
                'info-text' => 'rgb(179, 220, 255)',
            ]

        ];
    }
}


