<?php

namespace App\Filament\Resources\AttendanceResource\Pages;

use App\Filament\Resources\AttendanceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAttendance extends ViewRecord
{
    protected static string $resource = AttendanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make()->label('تعديل'),
            Actions\Action::make('back')
                ->label('العودة للقائمة')
                ->url('/admin/attendances')
                ->icon('heroicon-o-arrow-left'),
        ];
    }
}
