<?php

declare(strict_types=1);

namespace App\MoonShine\Resources;

use Illuminate\Database\Eloquent\Model;
use App\Models\Ticket;
use Illuminate\Http\Request; 

use MoonShine\Resources\ModelResource;
use MoonShine\Decorations\Block;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\Select;
use MoonShine\Fields\Textarea;
use MoonShine\Fields\Image;
use MoonShine\Fields\Field;
use MoonShine\Components\MoonShineComponent;
use Sweet1s\MoonshineRBAC\Traits\WithRolePermissions;
use MoonShine\Fields\Badge;


//use MoonShine\Fields\Relationships\BelongsTo;



use Illuminate\Database\Eloquent\Builder;

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

    /**
     * @return list<MoonShineComponent|Field>
     */
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
                    ]),

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
                    }),

                Textarea::make('Descripcion','descripcion'),
                Image::make('Archivo','archivo'),
            


                
            ]),
        ];


    }

    


    /**
     * @param Ticket $item
     *
     * @return array<string, string[]|string>
     * @see https://laravel.com/docs/validation#available-validation-rules
     */
    public function rules(Model $item): array
    {
        return [];
    }
}
