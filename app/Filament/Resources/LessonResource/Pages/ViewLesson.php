<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()
                ->label('تعديل'),
            Actions\DeleteAction::make()
                ->label('حذف')
                ->successRedirectUrl(route('admin.lessons.index')),
            Actions\Action::make('back')
                ->label('العودة للقائمة')
                ->url(route('admin.lessons.index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return route('admin.lessons.index');
    }
}
