<?php

namespace App\Filament\Resources\LessonNewResource\Pages;

use App\Filament\Resources\LessonNewResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLessonNew extends EditRecord
{
    protected static string $resource = LessonNewResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
