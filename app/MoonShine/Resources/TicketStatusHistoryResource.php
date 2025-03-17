<?php

namespace App\MoonShine\Resources;

use App\Models\TicketStatusHistory;
use MoonShine\Resources\ModelResource;
use MoonShine\Fields\ID;
use MoonShine\Fields\Text;
use MoonShine\Fields\DateTime;
use MoonShine\Fields\BelongsTo;
use Illuminate\Database\Eloquent\Model;


/**
 * @extends ModelResource<TicketStatusHistory>
 */
class TicketStatusHistoryResource extends ModelResource
{
    protected string $model = TicketStatusHistory::class;

    public function fields(): array
    {
        return [
            ID::make()->sortable(),
            BelongsTo::make('Ticket', 'ticket', 'id')->hideOnIndex(),
            Text::make('Estado', 'status'),
            DateTime::make('Fecha de Cambio', 'changed_at'),
        ];
    }

    public function rules(Model $item): array
    {
        return [];
    }
}
