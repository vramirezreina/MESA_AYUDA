<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use Illuminate\Http\Request; 
use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Select;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Image;
use MoonShine\Fields\Text;
use MoonShine\Fields\Relationships\BelongsTo;
use MoonShine\Fields\Relation;
use MoonShine\Fields\DateTime;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;
use MoonShine\Components\MoonShineComponent;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use MoonShine\Pages\CustomPage;
//use App\Models\TicketStatusHistory;
use App\MoonShine\Resources\TicketStatusHistoryResource;
use MoonShine\Fields\Relationships\HasMany;


use MoonShine\Resources\Resource;
use MoonShine\Actions\Action;
use MoonShine\Actions\DeleteAction;

/**
 * @extends ModelResource<Ticket>
 */
class TicketResource extends ModelResource
{

    use WithRolePermissions;

    protected string $model = Ticket::class;

    protected bool $createInModal = true;
    protected bool $editInModal = true;
    protected bool $detailInModal = false;

    public function redirectAfterSave(): string
    {
        $referer = request()->header('referer'); 
        return $referer ?: '/'; 
    }

    public function fields(): array
    {
        return [
            Block::make([
                ID::make()->sortable(),
            
                Select::make('Tipo','tipo_ticket')
                    ->options([
                        'pregunta' => 'Pregunta',
                        'incidente' => 'Incidente',
                        'problema' => 'Problema',
                    ]) ->disabled(fn () => Auth::user()->hasRole('Soporte')),
            
                Select::make('Prioridad', 'prioridad')
                    ->options([
                        'Alta' => 'Alta',
                        'Media' => 'Media',
                        'Baja' => 'Baja',
                    ])
                    ->badge(fn($value) => match ($value) {
                        'Alta' => 'red',
                        'Media' => 'blue',
                        'Baja' => 'yellow',
                        default => 'gray'
                    }) ->disabled(fn () => Auth::user()->hasRole('Soporte')),
            
                Textarea::make('Descripcion','descripcion')
                ->disabled(fn () => Auth::user()->hasRole('Soporte')),

                Image::make('Archivo','archivo')
                ->disabled(fn () => Auth::user()->hasRole('Soporte')),
            
                BelongsTo::make('Usuario', 'user', 'name', new UserResource())
                    ->canSee(fn () => Auth::user()->hasRole(['Admin', 'Super_Administrador', 'Soporte']))
                    ->disabled(fn () => Auth::user()->hasRole('Soporte')),
            
                Select::make('Asignado a', 'assigned_to')
                    ->options(fn () => \App\Models\User::pluck('name', 'id')->toArray())
                    ->nullable()
                    ->canSee(fn () => Auth::user()->hasRole(['Admin', 'Super_Administrador']))
                    ->updateOnPreview()
                    ->customAttributes(['class' => 'w-full'])
                    ->disabled(fn () => Auth::user()->hasRole('Usuario')),
            
                Text::make('Asignado a', 'assignedTo.name')
                    ->badge('blue')
                    ->canSee(fn () => Auth::user()->hasRole(['Usuario']))
                    ->hideOnCreate(), // Ocultar al crear
            
                // CAMPO ESTADO SOLO PARA SOPORTE
                Select::make('Estado', 'estado')
                    ->options([
                        'Abierto' => 'Abierto',
                        'Resuelto' => 'Resuelto',
                        'Cerrado' => 'Cerrado',
                    ])
                    ->badge(fn($value) => match ($value) {
                        'Abierto' => 'yellow',
                        'Resuelto' => 'green',
                        'Cerrado' => 'red'
                    })
                    ->canSee(fn () => Auth::user()->hasRole(['Soporte', 'Usuario', 'Admin', 'Super_Administrador']))
                    ->disabled(fn () => Auth::user()->hasRole('Usuario'))
                    ->updateOnPreview()
                    ->hideOnCreate(), // Ocultar al crear

                Textarea::make('Comentario', 'comentario')
                    ->badge('gray')
                    ->canSee(fn () => Auth::user()->hasRole(['Soporte', 'Usuario', 'Admin', 'Super_Administrador']))
                    ->disabled(fn () => Auth::user()->hasRole('Usuario'))
                    ->hideOnCreate(), // Ocultar al crear

            

                 

HasMany::make('Historial de Estados', 'statusHistories', new TicketStatusHistoryResource()),

                    


            ])
        ];
    }

    protected function beforeCreating(Model $item): Model
    {
        $item->user_id = auth()->id();
        return $item;
    }

    public function query(): Builder
    {
        $user = Auth::user();

        if ($user->hasRole(['Admin', 'Super_Administrador'])) {
            return Ticket::query();
        }

        if ($user->hasRole('Soporte')) {
            return Ticket::query()->where('assigned_to', $user->id);
        }

        return Ticket::query()->where('user_id', $user->id);
    }

    public function exportFields(): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Tipo', 'tipo_ticket'),
            Text::make('Prioridad', 'prioridad'),
            Textarea::make('Descripción', 'descripcion'),
            Text::make('Archivo', 'archivo'),
            Text::make('Usuario', 'user.name'),
            Text::make('Asignado a', 'assignedTo.name'),
            Text::make('Estado', 'estado'),
            Textarea::make('Comentario', 'comentario'),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
