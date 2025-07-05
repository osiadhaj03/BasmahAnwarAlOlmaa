<?php

namespace App\Filament\Resources\LessonNewResource\Pages;

use App\Filament\Resources\LessonNewResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLessonNews extends ListRecords
{
    protected static string $resource = LessonNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
