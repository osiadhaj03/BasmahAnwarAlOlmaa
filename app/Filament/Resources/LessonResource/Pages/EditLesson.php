<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditLesson extends EditRecord
{
    protected static string $resource = LessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make()
                ->label('عرض')
                ->successRedirectUrl(static::getResource()::getUrl('index')),
            Actions\DeleteAction::make()
                ->label('حذف')
                ->successRedirectUrl(static::getResource()::getUrl('index')),
            Actions\Action::make('back')
                ->label('العودة للقائمة')
                ->url(static::getResource()::getUrl('index'))
                ->icon('heroicon-o-arrow-left'),
        ];
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If current user is a teacher, ensure they remain as the teacher
        if (auth()->user()?->role === 'teacher') {
            $data['teacher_id'] = auth()->id();
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return route('admin.lessons.index');
    }
}
