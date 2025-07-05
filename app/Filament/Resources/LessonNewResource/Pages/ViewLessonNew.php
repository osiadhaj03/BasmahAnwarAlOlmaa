<?php

namespace App\Filament\Resources\LessonNewResource\Pages;

use App\Filament\Resources\LessonNewResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLessonNew extends ViewRecord
{
    protected static string $resource = LessonNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
