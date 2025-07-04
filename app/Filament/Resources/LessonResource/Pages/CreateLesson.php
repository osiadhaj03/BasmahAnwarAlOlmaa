<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLesson extends CreateRecord
{
    protected static string $resource = LessonResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // If current user is a teacher, ensure they are assigned as the teacher
        if (auth()->user()?->role === 'teacher') {
            $data['teacher_id'] = auth()->id();
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
