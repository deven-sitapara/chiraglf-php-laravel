<?php

namespace App\Filament\Admin\Resources\FileResource\RelationManagers;

use App\Filament\Admin\Resources\CompanyResource\CompanyResource;
use App\Filament\Admin\Resources\TSRResource\TSRResource;
use App\Forms\Components\OneDriveFileUpload;
use App\Helpers\FileHelper;
use App\Helpers\OneDriveFileHelper;
use App\Services\OneDriveService;
use Faker\Provider\ar_EG\Text;
use Filament\Actions\Action as ActionsAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Column;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class TSRRelationManager extends RelationManager
{
    protected static string $relationship = 'tsrs';

    protected static ?string $title = 'TSRs';  // More descriptive alternative

    public function form(Form $form): Form
    {
        return TSRResource::common_form($form, true);
    }


    public function table(Table $table): Table
    {
        return  TSRResource::table($table);
    }
}
